<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\FamilyController;
use App\Http\Controllers\admin\FamilyphotoController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\HomeMembersController;
use App\Http\Controllers\admin\MapController;
use App\Http\Controllers\admin\ProcedureTypeController;
use App\Http\Controllers\admin\SpecieController;
use App\Http\Controllers\admin\StateController;
use App\Http\Controllers\admin\TreeController;
use App\Http\Controllers\admin\ZoneController;
use App\Http\Controllers\admin\ZoneCoordsController;
use App\Http\Controllers\admin\ResponsibleController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\PermissionsController;
use App\Http\Controllers\admin\RolesController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'permission']], function() {
    Route::get('/',[AdminController::class,'index'])->name('admin.index');

    Route::resource('families',FamilyController::class)->names('admin.families')->middleware('auth:sanctum');

    Route::get('species_family/{family_id}',[FamilyController::class,'species_family'])->name('admin.species_family');

    Route::resource('species', SpecieController::class)->names('admin.species');

    Route::resource('familyphotos', FamilyphotoController::class)->names('admin.familyphotos');

    Route::resource('zones', ZoneController::class)->names('admin.zones');

    Route::resource('trees', TreeController::class)->names('admin.trees');

    Route::resource('states', StateController::class)->names('admin.states');

    Route::resource('maps', MapController::class)->names('admin.maps');

    Route::resource('zonecoords', ZoneCoordsController::class)->names('admin.zonecoords');

    Route::resource('proceduretypes', ProcedureTypeController::class)->names('admin.proceduretypes');

    Route::resource('responsible', ResponsibleController::class)->names('admin.responsibles');

    Route::resource('users', UserController::class)->names('admin.users');

    Route::resource('roles', RolesController::class)->names('admin.roles');
    Route::resource('permissions', PermissionsController::class)->names('admin.permissions');
});
Route::resource('homes', HomeController::class)->names('admin.home');
Route::resource('homemembers', HomeMembersController::class)->names('admin.homemembers')->except(
    ['create','destroy']
);
Route::get('homemembers/create/{home_id}', [HomeMembersController::class, 'create'])->name('admin.homemembers.create');
Route::delete('homemembers/{id_home}/{id_member}', [HomeMembersController::class,'destroy'])->name('admin.homemembers.destroy');
?>