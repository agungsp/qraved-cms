<?php

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('user')->group(function () {
    Route::get('get/{id}', [App\Http\Controllers\Api\UserController::class, 'get'])->name('api.user.get');
    Route::post('store', [App\Http\Controllers\Api\UserController::class, 'store'])->name('api.user.store');
});

Route::prefix('quiz')->group(function () {

    Route::get('get', [\App\Http\Controllers\Api\QuizController::class, 'get'])->name('api.quiz.get');
    Route::post('answer-question', [\App\Http\Controllers\Api\QuizController::class, 'answer_question'])->name('api.quiz.answer-question');

});

Route::prefix('user-log')->group(function () {
    Route::post('store', [\App\Http\Controllers\Api\UserLogController::class, 'store'])->name('api.user-log.store');
});

Route::prefix('resto')->group(function () {
    Route::get('get/{unique_code}', [\App\Http\Controllers\Api\RestaurantController::class, 'get'])->name('api.restaurant.get');
});
