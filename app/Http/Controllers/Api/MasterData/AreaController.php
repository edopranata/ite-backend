<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Resources\MasterData\RegionalOffice\RegionalCollection;
use App\Http\Resources\MasterData\RegionalOffice\RegionalResource;
use App\Models\Office\Area;
use App\Models\Office\Branch;
use App\Models\Office\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $regions = Area::query()
            ->when($request->name, function ($query, $name){
                $query->where('name', 'like',  "%$name%");
            })
            ->when($request->get('sortBy'), function ($query, $sort) {
                $sortBy = collect(json_decode($sort));
                return $query->orderBy($sortBy['key'], $sortBy['order']);
            })->paginate($request->get('limit', 10));

        return new RegionalCollection($regions);
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
                'initial_id',
                'name',
            ]), [
                'id' => 'required|numeric|unique:areas,id',
                'initial_id' => 'required|string|min:1|max:5|unique:areas,code',
                'name' => 'required|string|max:30|unique:areas,name',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()->toArray()], 422);
            }

            $regional = Area::query()
                ->create([
                    'id' => $request->id,
                    'code' => $request->initial_id,
                    'name' => $request->name,
                    'user_id' => auth()->id()
                ]);

            $branch = Branch::query()
                ->create([
                    'area_id' => $regional->id,
                    'id' => $request->id,
                    'name' => $request->name,
                    'user_id' => auth()->id()
                ]);
            Unit::query()
                ->create([
                    'area_id' => $regional->id,
                    'branch_id' => $branch->id,
                    'id' => $request->id,
                    'name' => $request->name,
                    'type' => 'KANWIL',
                    'user_id' => auth()->id()
                ]);

            DB::commit();

            return new RegionalResource($regional->load('user'));

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
    public function update(Request $request, Area $area)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->only([
                'id',
                'initial_id',
                'name',
            ]), [
                'id' => 'required|numeric|unique:areas,id,'. $request->id,
                'initial_id' => 'required|string|min:1|max:5|unique:areas,code,'. $request->id,
                'name' => 'required|string|max:30|unique:areas,name,'. $request->id,
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()->toArray()], 422);
            }

            $area->update([
                'id' => $request->id,
                'code' => $request->initial_id,
                'name' => $request->name,
            ]);

            Branch::query()->where('id', $area->id)
                ->update([
                    'name' => $request->name,
                ]);

            Unit::query()->where('id', $area->id)
                ->update([
                    'name' => $request->name,
                ]);


            DB::commit();

            return new RegionalResource($area->load('user'));

        } catch (\Exception $exception) {
            abort(403, $exception->getCode() . ' ' . $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        DB::beginTransaction();
        try {

            $area->branches()->delete();
            $area->units()->delete();
            $area->delete();


            DB::commit();
            return response()->json(['status' => true], 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(403, $exception->getCode() . ' ' . $exception->getMessage());
        }
    }
}
