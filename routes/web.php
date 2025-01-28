<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpleadoController;
 
Route::get('/', function () {
    return view('empleados.index');
});
 

//Empleado
Route::get('/empleado', [EmpleadoController::class, 'index']);
Route::post('/empleado/store', [EmpleadoController::class, 'store'])->name('empleado.store');
Route::get('/empleado/fetchall', [EmpleadoController::class, 'fetchAll'])->name('empleado.fetchAll');
Route::delete('/empleado/delete', [EmpleadoController::class, 'delete'])->name('empleado.delete');
Route::get('/empleado/edit', [EmpleadoController::class, 'edit'])->name('empleado.edit');
Route::post('/empleado/update', [EmpleadoController::class, 'update'])->name('empleado.update');