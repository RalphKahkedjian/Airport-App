<?php

use App\Http\Controllers\Admin\auth\AdminAuthController;
use App\Http\Controllers\Admin\admin\AdminController;
use App\Http\Controllers\Admin\book\AdminBookingController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\user\AdminUserController;

use App\Http\Controllers\Admin\ticket\AdminTicketController;


use App\Http\Controllers\User\auth\UserAuthConroller;
use App\Http\Controllers\User\ticket\UserTicketContoller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Admin authentication routes
    Route::put('/Admin/auth/', [AdminAuthController::class, 'register']);
    Route::post('/Admin/auth/', [AdminAuthController::class, 'login']);

    Route::get('/Admin/admin', [AdminController::class, 'index']);
    Route::post('/Admin/admin', [AdminController::class, 'store']);
    Route::get('/Admin/admin/{id}', [AdminController::class, 'show']);
    Route::put('/Admin/admin/{id}', [AdminController::class, 'update']);
    Route::delete('/Admin/admin/{id}', [AdminController::class, 'destroy']);

    Route::get('/Admin/ticket', [AdminTicketController::class, 'index']);
    Route::post('/Admin/ticket', [AdminTicketController::class, 'store']);
    Route::get('/Admin/ticket/{id}', [AdminTicketController::class, 'show']);
    Route::put('/Admin/ticket/{id}', [AdminTicketController::class, 'update']);
    Route::delete('/Admin/ticket/{id}', [AdminTicketController::class, 'destroy']);

    Route::get('/Admin/user', [AdminUserController::class, 'index']);
    Route::post('/Admin/user', [AdminUserController::class, 'store']);
    Route::get('/Admin/user/{id}', [AdminUserController::class, 'show']);
    Route::put('/Admin/user/{id}', [AdminUserController::class, 'update']);
    Route::delete('/Admin/user/{id}', [AdminUserController::class, 'destroy']);

// Admin routes for managing admins
Route::middleware(['auth:admin'])->group(function () {

    // Admin routes for managing users

    // Admin routes for managing tickets

    // Admin routes for managing bookings
    Route::get('/Admin/book', [AdminBookingController::class, 'index']);
    Route::post('/Admin/book', [AdminBookingController::class, 'store']);
    Route::get('/Admin/book/{id}', [AdminBookingController::class, 'show']);
    Route::put('/Admin/book/{id}', [AdminBookingController::class, 'update']);
    Route::delete('/Admin/book/{id}', [AdminBookingController::class, 'destroy']);
});

// User authentication routes

Route::put('/User/auth', [UserAuthConroller::class, 'register']);
Route::post('/User/auth', [UserAuthConroller::class, 'login']);

Route::delete('/User/auth/{id}', [UserAuthConroller::class, 'deleteUser']);

Route::delete('/User/user/{id}', [UserController::class, 'destroy']);

Route::get('/User/ticket',[UserTicketContoller::class,'index']);
Route::get('/User/ticket/{id}',[UserTicketContoller::class,'show']);

Route::middleware(['auth:user'])->group(function () {

});

