<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\SocialNetworkController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Register and - login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    // Route::get('/users', [UserController::class, 'index']);
    // Route::get('/users/{id}', [UserController::class, 'show']);
    // Route::put('/users/{id}', [UserController::class, 'update']);
    // Route::post('/users/{id}', [UserController::class, 'update']);
    // Route::delete('/users/{id}', [UserController::class, 'destroy']);


    //Socials
    Route::get('/socials', [\App\Http\Controllers\SocialController::class, 'index']);
    Route::post('/socials', [\App\Http\Controllers\SocialController::class, 'store']);
    Route::delete('/socials/{id}', [\App\Http\Controllers\SocialController::class, 'destroy']);
});

//Search users
Route::middleware('auth:sanctum')->get('/search', [UserController::class, 'search']);



//-------------------------------------------- ADMIN --------------------------------

// Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
//     //User actions
//     Route::get('/users', [UserManagementController::class, 'index']);
//     Route::get('/users/{id}', [UserManagementController::class, 'show']);
//     Route::put('/users/{id}', [UserManagementController::class, 'update']);
//     Route::delete('/users/{id}', [UserManagementController::class, 'destroy']);


//     //Roles
//     Route::post('/roles', [UserManagementController::class, 'createRole']);
//     Route::post('/permissions', [UserManagementController::class, 'createPermission']);
//     Route::post('/roles/{role}/permissions', [UserManagementController::class, 'assignPermissions']);

//     //New admin user
//     Route::post('/admin-users', [UserManagementController::class, 'createAdminUser']);
// });


Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    // USER ACTIONS
    Route::get('/users', [UserManagementController::class, 'index'])
        ->middleware('check.permission:view_user');

    Route::get('/users/{id}', [UserManagementController::class, 'show'])
        ->middleware('check.permission:view_user');

    Route::put('/users/{id}', [UserManagementController::class, 'update'])
        ->middleware('check.permission:edit_user');

    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])
        ->middleware('check.permission:delete_user');

    // ROLE MANAGEMENT (sadece super_admin yapabilsin, özel middleware istersen tanımlarız)
    Route::post('/roles', [UserManagementController::class, 'createRole'])
        ->middleware('check.permission:assign_roles');

    Route::post('/permissions', [UserManagementController::class, 'createPermission'])
        ->middleware('check.permission:assign_roles');

    Route::post('/roles/{role}/permissions', [UserManagementController::class, 'assignPermissions'])
        ->middleware('check.permission:assign_roles');

    // YENİ ADMIN EKLEME
    Route::post('/admin-users', [UserManagementController::class, 'createAdminUser'])
        ->middleware('check.permission:assign_roles');

    Route::post('/logout', [AdminAuthController::class, 'logout']);


    //Social Netowrks
    Route::get('/social-networks', [SocialNetworkController::class, 'index'])
        ->middleware('check.permission:assign_roles');

    Route::post('/social-networks', [SocialNetworkController::class, 'store'])
        ->middleware('check.permission:assign_roles');

    Route::delete('/social-networks/{id}', [SocialNetworkController::class, 'destroy'])
        ->middleware('check.permission:assign_roles');
});

//
