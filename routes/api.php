<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PartOneController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\SubTasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationApiController;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Response;
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

Route::post('/register', [UserController::class, 'register']);
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::get('/logout', [UserController::class, 'logout']);

//Route to check role
Route::get('/userRole/{id}', [UserController::class, 'checkRole']);

Route::group(['middleware' => ['role:Admin']], function () {
    Route::get('/project/{id}', [ProjectsController::class, 'getAllProjectsById']);
    Route::put('/project', [ProjectsController::class, 'update']);
    Route::put('/subtask', [SubTasksController::class, 'update']);
    Route::delete('/project/{id}', [ProjectsController::class, 'destroy']);
    // Route::delete('/subtask/{id}', [SubTasksController::class, 'destroy']);
});
Route::group(['middleware' => ['role:Admin|Supervisor']], function () {
    Route::get('projects', [ProjectsController::class, 'getAllProjects']);
    Route::post('/project', [ProjectsController::class, 'createProjects']);
    Route::post('/subtask', [SubTasksController::class, 'createSubTask']);
});
Route::middleware('role:Employee|Admin')->group(function () {
    Route::get('/subtask', [SubTasksController::class, 'getAllSubTasks']);
    Route::put('/subtask/statusupdate', [SubTasksController::class, 'statusUpdate']);
});



