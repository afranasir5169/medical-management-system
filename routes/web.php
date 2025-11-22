<?php

use App\Http\Controllers\SupplierController;

Route::get('/', function(){ return redirect()->route('suppliers.index'); });

Route::resource('suppliers', SupplierController::class);


use App\Http\Controllers\GrnController;

Route::resource('grns', GrnController::class);

