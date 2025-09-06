<?php 
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LocationController;

Route::get('/', function () {
    return redirect()->route('employees.index');
});

// DataTables data route - put this BEFORE the resource route
Route::get('employees/data', [EmployeeController::class, 'data'])->name('employees.data');

// Employee routes
Route::resource('employees', EmployeeController::class);

// Department routes  
Route::resource('departments', DepartmentController::class);  

// Location API routes
Route::prefix('api')->group(function () {
    Route::get('countries', [LocationController::class, 'countries']);
    Route::get('states/{country}', [LocationController::class, 'states']);
    Route::get('cities/{country}/{state}', [LocationController::class, 'cities']);
         
    // Debug routes - remove these in production
    Route::get('test-location-api', [LocationController::class, 'testApi']);
    Route::post('clear-location-cache', [LocationController::class, 'clearCache']);
});