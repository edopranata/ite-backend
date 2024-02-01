<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Resources\MasterData\BranchOffice\BranchResource;
use App\Http\Resources\MasterData\SubBranchOffice\SubBranchCollection;
use App\Http\Resources\MasterData\SubBranchOffice\SubBranchResource;
use App\Models\Office\Area;
use App\Models\Office\Branch;
use App\Models\Office\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->get('mode') === 'getBranch') {
            $branches = Branch::query()
                ->where('area_id', $request->area_id)
                ->get(['id', 'name']);
            return response()->json([
                'branches' => $branches
            ]);
        } else {
            $units = Unit::query()
                ->when($request->name, function ($query, $name) {
                    $query->where('name', 'like', "%$name%");
                })
                ->when($request->get('sortBy'), function ($query, $sort) {
                    $sortBy = collect(json_decode($sort));
                    return $query->orderBy($sortBy['key'], $sortBy['order']);
                })->paginate($request->get('limit', 10));

            $areas = Area::query()->get(['id', 'code', 'name']);

            return response()->json([
                'areas' => $areas,
                'units' => new SubBranchCollection($units)
            ], 201);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->only([
                'id',
                'area_id',
                'name',
                'type',
            ]), [
                'id' => 'required|numeric|unique:areas,id',
                'area_id' => 'required|exists:areas,id',
                'name' => 'required|string|max:30|unique:areas,name',
                'type' => 'required|string|in:CABANG,KCP,KANTOR KAS,UNIT',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()->toArray()], 422);
            }

            $unit = Unit::query()
                ->create([
                    'area_id' => $request->area_id,
                    'branch_id' => $request->branch_id,
                    'id' => $request->id,
                    'name' => $request->name,
                    'type' => $request->type,
                    'user_id' => auth()->id()
                ]);

            DB::commit();

            return new SubBranchResource($unit->load('user'));

        } catch (\Exception $exception) {
            abort(403, $exception->getCode() . ' ' . $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->only([
                'id',
                'area_id',
                'name',
                'type',
            ]), [
                'id' => 'required|numeric|unique:areas,id',
                'area_id' => 'required|exists:areas,id',
                'name' => 'required|string|max:30|unique:areas,name',
                'type' => 'required|string|in:CABANG,KCP,KANTOR KAS,UNIT',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()->toArray()], 422);
            }



            $unit->update([
                    'area_id' => $request->area_id,
                    'branch_id' => $request->branch_id,
                    'id' => $request->id,
                    'name' => $request->name,
                    'type' => $request->type,
                ]);

            DB::commit();

            return new SubBranchResource($unit->load('user'));

        } catch (\Exception $exception) {
            abort(403, $exception->getCode() . ' ' . $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        DB::beginTransaction();
        try {
            $unit->delete();
            DB::commit();
            return response()->json(['status' => true], 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(403, $exception->getCode() . ' ' . $exception->getMessage());
        }
    }
}
