<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\settingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Lab404\Impersonate\Controllers\ImpersonateController;

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
use Illuminate\Support\Facades\Mail;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/userDashboard', function () {

    return view('Employee.userDashboard');
})->middleware(['auth', 'verified'])->name('userdashboard');
// Route::middleware(['auth','role:super-admin|admin'])->group(function () {

Route::middleware(['auth'])->group(function () {


// Show the settings form
Route::get('/settings', [settingsController::class, 'show'])->name('settings.show');

// Update the settings
Route::put('/settings', [settingsController::class, 'update'])->name('settings.update');

    Route::impersonate();

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/uplode', [ProfileController::class, 'uplodephoto'])->name('profile.uplode');

    Route::get('/profiledashboard', [ProfileController::class, 'profileDashboard']);


Route::resource('permission',PermissionController::class);
Route::delete('permission/{permissionid}/delete',[PermissionController::class,'destroy']);
Route::post('createPermission',[PermissionController::class,'create']);
// Route::get('/permission/{id}', [PermissionController::class, 'show']);

// Route::get('/permission/{id}', [PermissionController::class, 'show']); // Fetch permission data
// Route::put('/permissions/{id}', [PermissionController::class, 'update']); // Update permission

Route::get('/permission/{id}', [PermissionController::class, 'show']);


//All roles Controls
Route::resource('roles',RoleController::class);
Route::get('roles/{roleid}/delete',[RoleController::class,'destroy']);
// ->middleware('permission:Delete role');
Route::get('roles/{roleid}',[RoleController::class,'show']);

// Route::put('rolesedit/{roleid}',[RoleController::class,'show']);

Route::get('roles/{roleId}/give-permission',[RoleController::class,'addPermissionToRole']);
Route::put('roles/{roleId}/give-permission',[RoleController::class,'givePermissionToRole']);


Route::resource('users',UserController::class);
Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
// Route::put('/users/{id}', [UserController::class, 'updateuser'])->name('users.update');

Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
Route::delete('users/{id}/forceDelete', [UserController::class, 'forceDelete'])->name('users.forceDelete');



Route::resource('employee',EmployeeController::class);
});

require __DIR__.'/auth.php';
