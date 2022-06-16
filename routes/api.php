<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScrapeController;

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



Route::post('/login', [UserController::class, "login"]);



Route::middleware(['auth:sanctum'])->group(function ()
{
    // user routes
    Route::post('/create-user', [UserController::class, "create"]);
    Route::get('/show-user/{id}', [UserController::class, "show"]);
    Route::patch('/update-user/{id}', [UserController::class, "update"]);
    Route::delete('/delete-user/{id}', [UserController::class, "delete"]);
    
    // scrape section
    Route::get('/scrape_url', [ScrapeController::class, "scrape_url"]);


    // test routes
    Route::get('/testing', function (Request $request)
    {
        return [
            "message" => "You have reached your destination!!",
            "user"    => $request->user(),
        ];
    });
});


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

