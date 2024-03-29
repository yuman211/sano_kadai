<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTController;

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('/register', [JWTController::class, 'register']);
    Route::post('/login', [JWTController::class, 'login']);
    Route::post('/logout', [JWTController::class, 'logout']);
    Route::post('/refresh', [JWTController::class, 'refresh']);
    Route::post('/profile', [JWTController::class, 'profile']);
});

Route::get('/blog_list/{id?}', [BlogController::class, 'showBlogs']);
Route::get('/blog_category_list', [BlogController::class, 'showBlogsWithCategory']);
Route::get('/search_blogs_category', [CategoryController::class, 'searchBlogsByCategory']);
Route::get('/search_blogs_tags',[TagController::class, 'searchBlogsByTags']);

Route::middleware('auth')->group(function () {
    Route::post('/blog/create', [BlogController::class, 'createBlogs']);
    Route::post('/blog/edit', [BlogController::class, 'editBlogs']);
    Route::post('/blog/delete', [BlogController::class, 'deleteBlogs']);
});
