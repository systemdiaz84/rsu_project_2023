<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\FamilyController;
use App\Http\Controllers\admin\FamilyphotoController;
use App\Http\Controllers\admin\MapController;
use App\Http\Controllers\admin\ProcedureTypeController;
use App\Http\Controllers\admin\SpecieController;
use App\Http\Controllers\admin\StateController;
use App\Http\Controllers\admin\TreeController;
use App\Http\Controllers\admin\ZoneController;
use App\Http\Controllers\admin\ZoneCoordsController;
use App\Http\Controllers\admin\ResponsibleController;
use Illuminate\Support\Facades\Route;

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

?>