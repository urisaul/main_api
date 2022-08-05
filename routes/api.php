<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScrapeController;
use App\Mail\GeneralEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;


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


    // Recipe routes
    Route::get('/recipes/all', [RecipeController::class, "GetAll"]);
    Route::post('/recipes/add', [RecipeController::class, "Add"]);
    Route::patch('/recipes/update', [RecipeController::class, "Update"]);
    Route::delete('/recipes/delete', [RecipeController::class, "Delete"]);


    // test routes
    Route::get('/testing', function (Request $request)
    {
        Mail::to($request->user()['email'])->send(new GeneralEmail());

        return [
            "message" => "You have reached your destination!!",
            "user"    => $request->user(),
        ];
    });
});


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

 
// reset password routes

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
 
    $status = Password::sendResetLink(
        $request->only('email')
    );
 
    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');