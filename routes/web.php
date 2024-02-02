<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json(['status' => 'OK'], 201);
});
Route::get('tos', function (\Illuminate\Http\Request $request) {
//    dd(storage_path('master' . DIRECTORY_SEPARATOR .'area.xlsx'));
    Excel::import(new \App\Imports\ChannelAtmImport(), public_path('master' . DIRECTORY_SEPARATOR .'atm.xlsx'));

//    return new \App\Http\Resources\MasterData\RegionalOffice\RegionalCollection(\App\Models\Office\Area::paginate());
});

