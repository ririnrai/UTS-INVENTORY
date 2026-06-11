<?php

use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/inventories');

Route::resource('inventories', InventoryController::class)->except(['show']);
Route::post('inventories/outflow', [InventoryController::class, 'outflow'])->name('inventories.outflow');
