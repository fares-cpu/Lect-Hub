<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\HTTP\Controllers\AuthController;
use App\HTTP\Controllers\LectureController;

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

Route::controller(UserController::class)->group(function(){
    Route::post('/register', 'store');
    Route::get('/user/params', 'params');
});

Route::controller(AuthController::class)->group(function(){
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');

    Route::get('/upload/params', 'upload_params');
    Route::post('/upload', 'upload')->middleware('auth:sanctum');
});

Route::controller(LectureController::class)->group(function(){
    //Trees
    Route::get('/tree/universities', 'universities_tree');
    Route::get('/tree/faculties', 'facTree');  
    Route::get('/tree/types', 'typeTree');
    Route::get('/tree/{university}/faculties', 'facultiesOfUniversity');
    Route::get('/tree/{faculty}/universities', 'universitiesOfFaculty');
    Route::get('/tree/{university}/{faculty}/courses', 'coursesOfFaculty');
    Route::get('/tree/{type}/courses', 'coursesOfType');
    Route::get('/tree/{course}/lectures', 'lecturesOfCourse');

    
});
