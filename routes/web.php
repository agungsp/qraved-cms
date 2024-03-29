<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\Snappy\Facades\SnappyPdf;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes(['verify' => true ]);

Route::get('/', function () {
    return auth()->check() ? redirect()->route('cms.dashboard.index') : redirect()->route('login');
});
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');*

Route::middleware(['auth', 'verified'])->group(function () {

    Route::prefix('dashboad')->group(function () {
        Route::get('/', [App\Http\Controllers\Cms\DashboardController::class, 'index'])->name('cms.dashboard.index');
        Route::get('get-chart', [App\Http\Controllers\Cms\DashboardController::class, 'getChart'])->name('cms.dashboard.get-chart');
        Route::get('get-table', [App\Http\Controllers\Cms\DashboardController::class, 'getTable'])->name('cms.dashboard.get-table');
        Route::get('export-table', [App\Http\Controllers\Cms\DashboardController::class, 'exportTable'])->name('cms.dashboard.export-table');
    });

    Route::prefix('users')->group(function () {

        Route::prefix('cms')->group(function () {
            Route::get('/', [App\Http\Controllers\Cms\UserController::class, 'index'])->name('cms.user.cms.index');
            Route::get('get-users/{lastId}', [App\Http\Controllers\Cms\UserController::class, 'getUsers'])->name('cms.user.cms.get-users');
            Route::get('get-user/{id}', [App\Http\Controllers\Cms\UserController::class, 'getUser'])->name('cms.user.cms.get-user');
            Route::post('store', [App\Http\Controllers\Cms\UserController::class, 'store'])->name('cms.user.cms.store');
            Route::post('delete', [App\Http\Controllers\Cms\UserController::class, 'delete'])->name('cms.user.cms.delete');
        });

        Route::prefix('qraved')->group(function () {
            Route::get('/', [App\Http\Controllers\Cms\UserController::class, 'qravedIndex'])->name('cms.user.qraved.index');
            Route::get('get-users/{lastId}', [App\Http\Controllers\Cms\UserController::class, 'qravedGetUsers'])->name('cms.user.qraved.get-users');
            Route::get('get-user/{id}', [App\Http\Controllers\Cms\UserController::class, 'qravedGetUser'])->name('cms.user.qraved.get-user');
            Route::get('export-to-csv', [App\Http\Controllers\Cms\UserController::class, 'exportToCsv'])->name('cms.user.qraved.export-to-csv');
            Route::post('delete', [App\Http\Controllers\Cms\UserController::class, 'qravedDelete'])->name('cms.user.qraved.delete');
        });
    });

    Route::prefix('restaurants')->group(function () {
        Route::get('/', [App\Http\Controllers\Cms\RestaurantController::class, 'index'])->name('cms.restaurant.index');
        Route::get('get-restaurants/{lastId}', [App\Http\Controllers\Cms\RestaurantController::class, 'getRestaurants'])->name('cms.restaurant.get-restaurants');
        Route::get('get-restaurant/{id}', [App\Http\Controllers\Cms\RestaurantController::class, 'getRestaurant'])->name('cms.restaurant.get-restaurant');
        Route::get('qr-code-preview/{restaurant_id}', [App\Http\Controllers\Cms\RestaurantController::class, 'qrCodePreview'])->name('cms.restaurant.qr-code-preview');
        Route::get('available-qr', [App\Http\Controllers\Cms\RestaurantController::class, 'availableQr'])->name('cms.restaurant.available-qr');
        Route::post('store', [App\Http\Controllers\Cms\RestaurantController::class, 'store'])->name('cms.restaurant.store');
        Route::post('delete', [App\Http\Controllers\Cms\RestaurantController::class, 'delete'])->name('cms.restaurant.delete');
        Route::post('qr-connect', [App\Http\Controllers\Cms\RestaurantController::class, 'qrConnect'])->name('cms.restaurant.qr-connect');
    });

    Route::prefix('qr-codes')->group(function () {
        Route::get('/', [App\Http\Controllers\Cms\QrCodeController::class, 'index'])->name('cms.qr-code.index');
        Route::get('get-qrcodes/{lastId}', [App\Http\Controllers\Cms\QrCodeController::class, 'getQrcodes'])->name('cms.qr-code.get-qrcodes');
        Route::get('get-qrcode/{code}', [App\Http\Controllers\Cms\QrCodeController::class, 'getQrcode'])->name('cms.qr-code.get-qrcode');
        Route::get('qr-builder/{code}', [App\Http\Controllers\Cms\QrCodeController::class, 'qrBuilder'])->name('cms.qr-code.qr-builder');
        Route::post('store', [App\Http\Controllers\Cms\QrCodeController::class, 'store'])->name('cms.qr-code.store');
        Route::post('delete', [App\Http\Controllers\Cms\QrCodeController::class, 'delete'])->name('cms.qr-code.delete');
    });

    Route::prefix('quiz')->group(function () {
        Route::get('/', [App\Http\Controllers\Cms\QuizController::class, 'index'])->name('cms.quiz.index');
        Route::get('get-questions/{lastId}', [App\Http\Controllers\Cms\QuizController::class, 'getQuestions'])->name('cms.quiz.get-questions');
        Route::get('get-question/{id}', [App\Http\Controllers\Cms\QuizController::class, 'getQuestion'])->name('cms.quiz.get-question');
        Route::post('store', [App\Http\Controllers\Cms\QuizController::class, 'store'])->name('cms.quiz.store');
        Route::post('delete', [App\Http\Controllers\Cms\QuizController::class, 'delete'])->name('cms.quiz.delete');
    });

    Route::prefix('logs')->group(function () {
        Route::get('/', [App\Http\Controllers\Cms\LogController::class, 'index'])->name('cms.log.index');
        Route::get('get-logs', [App\Http\Controllers\Cms\LogController::class, 'getLogs'])->name('cms.log.get-logs');
        Route::get('export-to-csv', [App\Http\Controllers\Cms\LogController::class, 'exportToCsv'])->name('cms.log.export-to-csv');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [App\Http\Controllers\Cms\ProfileController::class, 'index'])->name('cms.profile.index');
        Route::get('avatar/{filename}', [App\Http\Controllers\Cms\ProfileController::class, 'avatar'])->name('cms.profile.avatar');
        Route::post('store', [App\Http\Controllers\Cms\ProfileController::class, 'store'])->name('cms.profile.store');
    });

    Route::prefix('settings')->group(function () {
        Route::get('/', [App\Http\Controllers\Cms\SettingController::class, 'index'])->name('cms.setting.index');
        Route::get('get-qrcode/{code_base64}', [App\Http\Controllers\Cms\SettingController::class, 'getQrCode'])->name('cms.setting.get-qrcode');
        Route::post('store', [App\Http\Controllers\Cms\SettingController::class, 'store'])->name('cms.setting.store');
    });

    Route::view('documentation', 'cms.documentation')->name('cms.documentation');

});

Route::prefix('images')->group(function () {
    Route::get('question_images/{path}', [\App\Http\Controllers\Cms\ImageController::class, 'question_images'])->name('images.question_images');
    Route::get('answer_image/{path}', [\App\Http\Controllers\Cms\ImageController::class, 'answer_image'])->name('images.answer_image');
    Route::get('logo', [\App\Http\Controllers\Cms\ImageController::class, 'logo'])->name('images.logo');
});

Route::get('restaurants/export/{restaurant_id}', [App\Http\Controllers\Cms\RestaurantController::class, 'export'])->name('cms.restaurant.export');

Route::get('resto', [\App\Http\Controllers\Cms\RestaurantController::class, 'resto'])->name('cms.resto');

Route::get('print', function () {

    return PDF::loadHTML('<h1>Test</h1>')
              ->setPaper('a4')
              ->setOrientation('landscape')
              ->inline('myfile.pdf');


});
