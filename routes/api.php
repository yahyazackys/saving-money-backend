<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [App\Http\Controllers\Api\ApiAuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Api\ApiAuthController::class, 'logout']);
    Route::post('/user/edit/{id}', [App\Http\Controllers\Api\ApiUserController::class, 'editProfile']);
    Route::post('/password/edit/{id}', [App\Http\Controllers\Api\ApiUserController::class, 'editPassword']);
    Route::delete('/user/delete/{id}', [App\Http\Controllers\Api\ApiUserController::class, 'deleteUser']);
    Route::post('/user/add-image', [App\Http\Controllers\Api\ApiUserController::class, 'updateImageUser']);

    Route::post('/income-add', [App\Http\Controllers\Api\ApiTransactionController::class, 'addIncome']);
    Route::get('/incomes', [App\Http\Controllers\Api\ApiTransactionController::class, 'getAllIncomes']);
    Route::post('/spending-add', [App\Http\Controllers\Api\ApiTransactionController::class, 'addSpending']);
    Route::get('/spendings', [App\Http\Controllers\Api\ApiTransactionController::class, 'getAllSpendings']);
    Route::get('/spending-to-mission/{missionId}', [App\Http\Controllers\Api\ApiTransactionController::class, 'getSpendingByMission']);

    Route::post('/mission-add', [App\Http\Controllers\Api\ApiMissionController::class, 'addMission']);
    Route::get('/missions', [App\Http\Controllers\Api\ApiMissionController::class, 'getAllMissions']);

    Route::get('/categories', [App\Http\Controllers\Api\ApiCategoryController::class, 'getAllCategories']);

    Route::get('/chart', [App\Http\Controllers\Api\ApiChartController::class, 'index']);
});
