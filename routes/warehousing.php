<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehousingController;

// Locations
Route::get('/locations', [WarehousingController::class, 'locations'])->name('warehousing.locations');
Route::post('/locations', [WarehousingController::class, 'storeLocation'])->name('warehousing.locations.store');
Route::put('/locations/{warehouse}', [WarehousingController::class, 'updateLocation'])->name('warehousing.locations.update');
Route::delete('/locations/{warehouse}', [WarehousingController::class, 'deleteLocation'])->name('warehousing.locations.delete');

// Zones
Route::get('/zones', [WarehousingController::class, 'zones'])->name('warehousing.zones');
Route::post('/zones', [WarehousingController::class, 'storeZone'])->name('warehousing.zones.store');
Route::put('/zones/{zone}', [WarehousingController::class, 'updateZone'])->name('warehousing.zones.update');
Route::delete('/zones/{zone}', [WarehousingController::class, 'deleteZone'])->name('warehousing.zones.delete');

// Bins
Route::post('/bins', [WarehousingController::class, 'storeBin'])->name('warehousing.bins.store');
Route::put('/bins/{bin}', [WarehousingController::class, 'updateBin'])->name('warehousing.bins.update');
Route::delete('/bins/{bin}', [WarehousingController::class, 'deleteBin'])->name('warehousing.bins.delete');

// Picking Orders
Route::get('/picking', [WarehousingController::class, 'picking'])->name('warehousing.picking');
Route::post('/picking', [WarehousingController::class, 'storePicking'])->name('warehousing.picking.store');
Route::put('/picking/{order}', [WarehousingController::class, 'updatePicking'])->name('warehousing.picking.update');
Route::delete('/picking/{order}', [WarehousingController::class, 'deletePicking'])->name('warehousing.picking.delete');
Route::post('/picking/{order}/assign', [WarehousingController::class, 'assignPicker'])->name('warehousing.picking.assign');
Route::post('/picking/{order}/status', [WarehousingController::class, 'updatePickingStatus'])->name('warehousing.picking.status');

// Putaway Orders
Route::get('/putaway', [WarehousingController::class, 'putaway'])->name('warehousing.putaway');
Route::post('/putaway', [WarehousingController::class, 'storePutaway'])->name('warehousing.putaway.store');
Route::put('/putaway/{order}', [WarehousingController::class, 'updatePutaway'])->name('warehousing.putaway.update');
Route::delete('/putaway/{order}', [WarehousingController::class, 'deletePutaway'])->name('warehousing.putaway.delete');
Route::post('/putaway/{order}/assign', [WarehousingController::class, 'assignHandler'])->name('warehousing.putaway.assign');
Route::post('/putaway/{order}/status', [WarehousingController::class, 'updatePutawayStatus'])->name('warehousing.putaway.status');
