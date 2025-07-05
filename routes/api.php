<?php

use App\Http\Controllers\GenManagerController;
use App\Http\Controllers\OfierArchitectsController;
use App\Http\Controllers\ParshaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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
Route::post('/q_login', [UserController::class, "q_login"]);
Route::post('/user/create', [UserController::class, "create"]);
Route::post('/user/q_create', [UserController::class, "q_create"]);

Route::post("/parsha/email_pref", [ParshaController::class, "email_pref"]);
// Route::post("/parsha/send_email/{parsha}/{template_id}", [ParshaController::class, "send_email"]);
Route::post("/parsha/send_email_req", [ParshaController::class, "send_email_req"]);
Route::post("/parsha/schedule_send", [ParshaController::class, "schedule_send_req"]);


// Route::middleware(['auth:sanctum', 'verified'])->group(function () {
//     // user routes
//     // Route::post('/create-user', [UserController::class, "create"]);
//     Route::get('/show-user/{id}', [UserController::class, "show"]);
//     Route::patch('/update-user/{id}', [UserController::class, "update"]);
//     Route::delete('/delete-user/{id}', [UserController::class, "delete"]);

//     // scrape section
//     Route::get('/scrape_url', [ScrapeController::class, "scrape_url"]);


//     // Recipe routes
//     Route::get('/recipes/all', [RecipeController::class, "GetAll"]);
//     Route::get('/recipes/allwcomments', [RecipeController::class, "GetAllWithComments"]);
//     Route::get('/recipes/single/{id}', [RecipeController::class, "GetSingle"]);
//     Route::post('/recipes/add', [RecipeController::class, "Add"]);
//     Route::patch('/recipes/update', [RecipeController::class, "Update"]);
//     Route::delete('/recipes/delete', [RecipeController::class, "Delete"]);

//     // Comment routes
//     Route::get('/comments/all', [CommentController::class, "GetAll"]);
//     Route::get('/comments/for_recipe/{recipe_id}', [CommentController::class, "get_for_recipe"]);
//     Route::post('/comments/add', [CommentController::class, "Add"]);
//     Route::patch('/comments/update', [CommentController::class, "Update"]);
//     Route::delete('/comments/delete', [CommentController::class, "Delete"]);


//     // test routes
//     Route::get('/testing', function (Request $request) {
//         $data = [
//             "subject" => "this is a test email",
//             "body" => "this is a test body of the email!!"
//         ];
//         // Mail::to($request->user()['email'])->send(new GeneralEmail($data));

//         return [
//             "message" => "You have reached your destination!!",
//             "user"    => $request->user(),
//         ];
//     });
// });

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('a_v1/{client}/{object_id}/get', [GenManagerController::class, "get_"]);
    Route::get('a_v1/{client}/{object_id}/get/{id}', [GenManagerController::class, "get_one_"]);
    Route::post('a_v1/{client}/{object_id}/add', [GenManagerController::class, "create_"])->middleware('ability:create');
    Route::patch('a_v1/{client}/{object_id}/update', [GenManagerController::class, "update"])->middleware('ability:update');
    Route::delete('a_v1/{client}/{object_id}/delete', [GenManagerController::class, "delete"])->middleware('ability:delete');

    Route::middleware(['ability:admin,*'])->group(function () {
        Route::get('a_v1/{client}/ad-{model}/get', [GenManagerController::class, "get_object"]);
        Route::get('a_v1/{client}/ad-{model}/get/{id}', [GenManagerController::class, "get_one_object"]);
        Route::post('a_v1/{client}/ad-{model}/add', [GenManagerController::class, "create_ad"]);
        Route::patch('a_v1/{client}/ad-{model}/update', [GenManagerController::class, "update_object"]);
        Route::delete('a_v1/{client}/ad-{model}/delete', [GenManagerController::class, "delete_object"]);
    });
});


// ofierarchitects.com routes

Route::get('/ofierarchitects/projects', [OfierArchitectsController::class, "projects"]);
Route::post('/ofierarchitects/contact-form', [OfierArchitectsController::class, "contactForm"]);

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
