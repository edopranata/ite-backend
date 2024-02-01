<?php

use App\Http\Controllers\Api\MasterData\AreaController;
use App\Http\Controllers\Api\MasterData\AtmController;
use App\Http\Controllers\Api\MasterData\BranchController;
use App\Http\Controllers\Api\MasterData\CctvController;
use App\Http\Controllers\Api\MasterData\UnitController;
use App\Http\Controllers\Api\MasterData\UpsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'masterData', 'as' => 'masterData.'], function () {
    Route::group(['prefix' => 'regionalOffice', 'as' => 'regionalOffice.'], function () {
        Route::get('/', [AreaController::class, 'index'])->name('index')->middleware('permission:app.masterData.regionalOffice.index');
        Route::post('/', [AreaController::class, 'store'])->name('createRegional')->middleware('permission:app.masterData.regionalOffice.createRegional');
        Route::patch('/{area}', [AreaController::class, 'update'])->name('updateRegional')->middleware('permission:app.masterData.regionalOffice.updateRegional');
        Route::delete('/{area}', [AreaController::class, 'destroy'])->name('deleteRegional')->middleware('permission:app.masterData.regionalOffice.deleteRegional');
    });
    Route::group(['prefix' => 'branchOffice', 'as' => 'branchOffice.'], function () {
        Route::get('/', [BranchController::class, 'index'])->name('index')->middleware('permission:app.masterData.branchOffice.index');
        Route::post('/', [BranchController::class, 'store'])->name('createBranch')->middleware('permission:app.masterData.branchOffice.createBranch');
        Route::patch('/{branch}', [BranchController::class, 'update'])->name('updateBranch')->middleware('permission:app.masterData.branchOffice.updateBranch');
        Route::delete('/{branch}', [BranchController::class, 'destroy'])->name('deleteBranch')->middleware('permission:app.masterData.branchOffice.deleteBranch');
    });
    Route::group(['prefix' => 'subBranchOffice', 'as' => 'subBranchOffice.'], function () {
        Route::get('/', [UnitController::class, 'index'])->name('index')->middleware('permission:app.masterData.subBranchOffice.index');
        Route::post('/', [UnitController::class, 'store'])->name('createSubBranch')->middleware('permission:app.masterData.subBranchOffice.createSubBranch');
        Route::patch('/{unit}', [UnitController::class, 'update'])->name('updateSubBranch')->middleware('permission:app.masterData.subBranchOffice.updateSubBranch');
        Route::delete('/{unit}', [UnitController::class, 'destroy'])->name('deleteSubBranch')->middleware('permission:app.masterData.subBranchOffice.deleteSubBranch');
    });

    Route::group(['prefix' => 'ups', 'as' => 'ups.'], function () {
        Route::get('/', [UpsController::class, 'index'])->name('index')->middleware('permission:app.masterData.ups.index');
        Route::post('/', [UpsController::class, 'store'])->name('createUps')->middleware('permission:app.masterData.ups.createUps');
        Route::patch('/{ups}', [UpsController::class, 'update'])->name('updateUps')->middleware('permission:app.masterData.ups.updateUps');
        Route::delete('/{ups}', [UpsController::class, 'destroy'])->name('deleteUps')->middleware('permission:app.masterData.ups.deleteUps');
    });
    Route::group(['prefix' => 'cctv', 'as' => 'cctv.'], function () {
        Route::get('/', [CctvController::class, 'index'])->name('index')->middleware('permission:app.masterData.cctv.index');
        Route::post('/', [CctvController::class, 'store'])->name('createCctv')->middleware('permission:app.masterData.cctv.createCctv');
        Route::patch('/{cctv}', [CctvController::class, 'update'])->name('updateCctv')->middleware('permission:app.masterData.cctv.updateCctv');
        Route::delete('/{cctv}', [CctvController::class, 'destroy'])->name('deleteCctv')->middleware('permission:app.masterData.cctv.deleteCctv');
    });
    Route::group(['prefix' => 'atm', 'as' => 'atm.'], function () {
        Route::get('/', [AtmController::class, 'index'])->name('index')->middleware('permission:app.masterData.atm.index');
        Route::post('/', [AtmController::class, 'store'])->name('createAtm')->middleware('permission:app.masterData.atm.createAtm');
        Route::patch('/{atm}', [AtmController::class, 'update'])->name('updateAtm')->middleware('permission:app.masterData.atm.updateAtm');
        Route::delete('/{atm}', [AtmController::class, 'destroy'])->name('deleteAtm')->middleware('permission:app.masterData.atm.deleteAtm');
    });
});
