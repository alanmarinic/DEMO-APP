<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/categories', [CategoryController::class, 'categories']);
Route::get('/root-categories', [CategoryController::class, 'rootCategories']);
Route::get('/{categoryId}/subcategories', [CategoryController::class, 'subcategories']);
