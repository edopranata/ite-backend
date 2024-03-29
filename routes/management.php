<?php

use App\Http\Controllers\Api\Management\PermissionController;
use App\Http\Controllers\Api\Management\RoleController;
use App\Http\Controllers\Api\Management\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'management', 'as' => 'management.'], function () {
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/', [UserController::class, 'index'])->name('index')->middleware('permission:app.management.users.index');
        Route::post('/', [UserController::class, 'store'])->name('createUser')->middleware('permission:app.management.users.createUser');
        Route::patch('/{user}', [UserController::class, 'update'])->name('updateUser')->middleware('permission:app.management.users.updateUser');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('deleteUser')->middleware('permission:app.management.users.deleteUser');
        Route::post('/{user}', [UserController::class, 'update'])->name('resetPassword')->middleware('permission:app.management.users.resetPassword');
    });

    Route::group(['prefix' => 'permissions', 'as' => 'permissions.'], function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index')->middleware('permission:app.management.permissions.index');
        Route::post('/', [PermissionController::class, 'sync'])->name('syncPermissions')->middleware('permission:app.management.permissions.syncPermissions');
        Route::get('/{id}/view', [PermissionController::class, 'view'])->name('viewPermission')->middleware('permission:app.management.permissions.viewPermission');
        Route::post('/{id}/view', [PermissionController::class, 'viewRolesUsers']);
    });

    Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('index')->middleware('permission:app.management.roles.index');
        Route::get('/{role}/view', [RoleController::class, 'show'])->name('viewRole')->middleware('permission:app.management.roles.viewRole');
        Route::post('/{role}/view', [RoleController::class, 'showDetails']);
        Route::patch('/{role}/view', [RoleController::class, 'addPermissionsToRole'])->name('addPermissionsToRole')->middleware('permission:app.management.roles.addPermissionsToRole');

        Route::post('/', [RoleController::class, 'store'])->name('createRole')->middleware('permission:app.management.roles.createRole');
        Route::patch('/{role}', [RoleController::class, 'update'])->name('updateRole')->middleware('permission:app.management.roles.updateRole');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('deleteRole')->middleware('permission:app.management.roles.deleteRole');
    });
});


