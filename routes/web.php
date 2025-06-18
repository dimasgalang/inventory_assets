<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryQRController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MachineQRController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SmartITController;
use App\Http\Controllers\UserController;
use App\Models\InventoryQR;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'login'])->name('/');

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register/guest', [RegisterController::class, 'store'])->name('register.guest');

    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    //Register
    Route::get('/register/create', [RegisterController::class, 'create'])->name('register.create')->middleware(['auth', 'role:Admin']);
    Route::post('/register', [RegisterController::class, 'storeAuth'])->name('register')->middleware(['auth', 'role:Admin']);

    //Role
    Route::get('/role/index', [RoleController::class, 'index'])->name('role.index')->middleware(['auth', 'role:Admin']);
    Route::get('/role/delete/{id}', [RoleController::class, 'delete'])->name('role.delete')->middleware(['auth', 'role:Admin']);
    Route::get('/role/create', [RoleController::class, 'create'])->name('role.create')->middleware(['auth', 'role:Admin']);
    Route::post('/role/store', [RoleController::class, 'store'])->name('role.store')->middleware(['auth', 'role:Admin']);
    Route::get('/role/find/{id}', [RoleController::class, 'find'])->name('role.find')->middleware(['auth', 'role:Admin']);
    Route::post('/role/update', [RoleController::class, 'update'])->name('role.update')->middleware(['auth', 'role:Admin']);

    //User
    Route::get('/user/index', [UserController::class, 'index'])->name('user.index')->middleware(['auth', 'role:Admin']);
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/user/update', [UserController::class, 'update'])->name('user.update')->middleware(['auth', 'role:Admin']);
    Route::get('/user/detail/{id}', [UserController::class, 'detail'])->name('user.detail')->middleware(['auth', 'role:Admin']);
    Route::get('/user/delete/{id}', [UserController::class, 'delete'])->name('user.delete')->middleware(['auth', 'role:Admin']);
    Route::get('/user/assign/{id}', [UserController::class, 'assign'])->name('user.assign')->middleware(['auth', 'role:Admin']);
    Route::post('/user/assignrole', [UserController::class, 'assignrole'])->name('user.assignrole')->middleware(['auth', 'role:Admin']);

    //Document
    Route::get('/document/index', [DocumentController::class, 'index'])->name('document.index');
    Route::get('/document/void', [DocumentController::class, 'void'])->name('document.void');
    Route::get('/document/restore', [DocumentController::class, 'restore'])->name('document.restore');
    Route::get('/document/fetchdocument/{id}', [DocumentController::class, 'fetchdocument'])->name('document.fetchdocument');

    //Inventory QR
    Route::get('/inventoryqr/index', [InventoryQRController::class, 'index'])->name('inventoryqr.index');
    Route::get('/inventoryqr/create', [InventoryQRController::class, 'create'])->name('inventoryqr.create');
    Route::post('/inventoryqr/store', [InventoryQRController::class, 'store'])->name('inventoryqr.store');
    Route::get('/inventoryqr/void', [InventoryQRController::class, 'void'])->name('inventoryqr.void');
    Route::get('/inventoryqr/restore', [InventoryQRController::class, 'restore'])->name('inventoryqr.restore');
    Route::get('/inventoryqr/batchqr', [InventoryQRController::class, 'batchqr'])->name('inventoryqr.batchqr');
    Route::post('/inventoryqr/import', [InventoryQRController::class, 'import'])->name('inventoryqr.import');

    //Machine QR
    Route::get('/machineqr/index', [MachineQRController::class, 'index'])->name('machineqr.index');
    Route::get('/machineqr/create', [MachineQRController::class, 'create'])->name('machineqr.create');
    Route::post('/machineqr/store', [MachineQRController::class, 'store'])->name('machineqr.store');
    Route::get('/machineqr/find/{id}', [MachineQRController::class, 'find'])->name('machineqr.find');
    Route::post('/machineqr/update', [MachineQRController::class, 'update'])->name('machineqr.update');
    Route::get('/machineqr/void', [MachineQRController::class, 'void'])->name('machineqr.void');
    Route::get('/machineqr/restore', [MachineQRController::class, 'restore'])->name('machineqr.restore');
    Route::get('/machineqr/batchqr', [MachineQRController::class, 'batchqr'])->name('machineqr.batchqr');
    Route::get('/machineqr/generateqr/{id}', [MachineQRController::class, 'generateqr'])->name('machineqr.generateqr');
    Route::post('/machineqr/import', [MachineQRController::class, 'import'])->name('machineqr.import');

    //SmartIT
    Route::get('/smartit/fetchitem', [SmartITController::class, 'fetchitem'])->name('smartit.fetchitem');
    Route::get('/smartit/getitem/{barang_code}', [SmartITController::class, 'getitem'])->name('smartit.getitem');

    //PDF
    Route::get('/pdf/generatePDF', [InventoryQRController::class, 'generatePDF'])->name('pdf.generatePDF');
});
