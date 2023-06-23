<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\FamilyController;
use App\Http\Controllers\admin\FamilyphotoController;
use App\Http\Controllers\admin\MapController;
use App\Http\Controllers\admin\SpecieController;
use App\Http\Controllers\admin\StateController;
use App\Http\Controllers\admin\TreeController;
use App\Http\Controllers\admin\ZoneController;
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

?>