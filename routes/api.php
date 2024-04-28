<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('users', function () {
    return response()->json([
        'data' => [
            'name' => 'Yaqoub',
            'age' => 32,
            'gender' => 'male'
        ]
    ]);
});



Route::prefix('auth')->group(
    function () {
        Route::post('register-user', [AuthController::class, 'registerUser']);
        Route::post('login', [AuthController::class, 'login']);
        Route::get('logout', [AuthController::class, 'logout']);
    }
);

// Route::group(['middleware' => 'auth:api'], function () {
//     Route::apiResource('articles', ArticleController::class);
// });
Route::apiResource('articles', ArticleController::class);