<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('api')->group(base_path('routes/api-version/v1.php'));
Route::prefix('v2')->middleware('api')->group(base_path('routes/api-version/v2.php'));
