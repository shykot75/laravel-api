<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// GET USER API
Route::get('/users/{id?}', [UserApiController::class, 'showUser']);

// POST USER API
Route::post('/store-user', [UserApiController::class, 'storeUser']);

// POST MULTIPLE USER API
Route::post('/store/multi-user', [UserApiController::class, 'storeMultiUser']);

// PUT API FOR UPDATE USER DETAILS
Route::put('/update-user/{id}', [UserApiController::class, 'updateUser']);

// PATCH API FOR UPDATE USER DETAIL
Route::patch('/update-user-single-record/{id}', [UserApiController::class, 'updateUserSingleRecord']);

// DELETE API FOR DELETE USER DETAILS
Route::delete('/delete-user/{id}', [UserApiController::class, 'deleteUser']);

// DELETE API FOR DELETE USER DETAILS WITH JSON
Route::delete('/delete-user-json/', [UserApiController::class, 'deleteUserJson']);

// DELETE API FOR MULTIPLE USER
Route::delete('/delete-multiple-user/{ids}', [UserApiController::class, 'deleteMultipleUser']);

// DELETE API FOR MULTIPLE USER WITH JSON
Route::delete('/delete-multiple-user-json/', [UserApiController::class, 'deleteMultipleUserJson']);

// SECURE THE API FOR STORE USER WITH JWT(JSON WEB TOKEN)
Route::post('/secure-store-user', [UserApiController::class, 'SecureStoreUser']);


// PASSPORT AUTHENTICATION - Register User
Route::post('/register-user-with-passport', [UserApiController::class, 'registerUserWithPassport']);

// PASSPORT AUTHENTICATION - Login User
Route::post('/login-user-with-passport', [UserApiController::class, 'loginUserWithPassport']);


Route::middleware(['auth:api'])->group(function(){

    // GET USER WITH AUTHENTICATION
    Route::get('/auth-users/{id?}', [UserApiController::class, 'showUserByAuth']);
    // STORE USER WITH AUTHENTICATION
    Route::post('/auth-user-store/', [UserApiController::class, 'storeUserByAuth']);

    // GET All USER BY RESOURCE COLLECTION
    Route::get('/get-users', [UserApiController::class, 'getUserByResource']);

    // GET Specific USER BY RESOURCE COLLECTION
    Route::get('/get-user/{id}', [UserApiController::class, 'getSpecificUserByResource']);

});
