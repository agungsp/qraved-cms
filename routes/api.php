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

Route::prefix('quiz')->group(function () {

    Route::get('get', [\App\Http\Controllers\Api\QuizController::class, 'get'])->name('api.quiz.get');
    Route::post('answer-question', [\App\Http\Controllers\Api\QuizController::class, 'answer_question'])->name('api.quiz.answer-question');

});
