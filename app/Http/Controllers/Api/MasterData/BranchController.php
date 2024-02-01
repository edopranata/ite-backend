<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Resources\MasterData\BranchOffice\BranchCollection;
use App\Http\Resources\MasterData\BranchOffice\BranchResource;
use App\Models\Office\Area;
use App\Models\Office\Branch;
use App\Models\Office\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $branches = Branch::query()
            ->when($request->name, function ($query, $name){
                $query->where('name', 'like',  "%$name%");
            })
            ->when($request->get('sortBy'), function ($query, $sort) {
                $sortBy = collect(json_decode($sort));
                return $query->orderBy($sortBy['key'], $sortBy['order']);
            })->paginate($request->get('limit', 10));

        $areas = Area::query()->get(['id', 'code', 'name']);

        return response()->json([
            'areas' => $areas,
            'branches' => new BranchCollection($branches)
        ], 201);
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
            ]), [
                'id' => 'required|numeric|unique:areas,id',
                'area_id' => 'required|exists:areas,id',
                'name' => 'required|string|max:30|unique:areas,name',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()->toArray()], 422);
            }

            $branch = Branch::query()
                ->create([
                    'area_id' => $request->area_id,
                    'id' => $request->id,
                    'name' => $request->name,
                    'user_id' => auth()->id()
                ]);

            Unit::query()
                ->create([
                    'area_id' => $request->area_id,
                    'branch_id' => $branch->id,
                    'id' => $request->id,
                    'name' => $request->name,
                    'type' => 'CABANG',
                    'user_id' => auth()->id()
                ]);

            DB::commit();

            return new BranchResource($branch->load('user'));

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
    public function update(Request $request, Branch $branch)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->only([
                'id',
                'area_id',
                'name',
            ]), [
                'id' => 'required|numeric|unique:areas,id',
                'area_id' => 'required|exists:areas,id',
                'name' => 'required|string|max:30|unique:areas,name',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()->toArray()], 422);
            }

            $branch->update([
                'id' => $request->id,
                'area_id' => $request->area_id,
                'name' => $request->name,
            ]);


            Unit::query()->where('id', $branch->id)
                ->update([
                    'name' => $request->name,
                ]);

            DB::commit();

            return new BranchResource($branch->load('user'));

        } catch (\Exception $exception) {
            abort(403, $exception->getCode() . ' ' . $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        DB::beginTransaction();
        try {

            $branch->units()->delete();
            $branch->delete();

            DB::commit();
            return response()->json(['status' => true], 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(403, $exception->getCode() . ' ' . $exception->getMessage());
        }
    }
}
