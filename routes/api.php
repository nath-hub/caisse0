<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Mail\MonMail;
use App\Http\Controllers\NewPasswordController;



Route::middleware('auth:api')->get('/user', function (Request $request) {
    Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout']);
});


Route::put('/up/{id}', [App\Http\Controllers\UserController::class, 'updateUser']);

//resend email
Route::put('/resend/{id}', [App\Http\Controllers\UserController::class, 'renvoiCode']);

//mot de passe oublier
Route::post('/mail', [App\Http\Controllers\UserController::class, 'getEmail']);
Route::post('/pass', [App\Http\Controllers\UserController::class, 'renvoieCode']);
Route::put('/savePass/{id}', [App\Http\Controllers\UserController::class, 'upPass']);

//user
Route::post('/register', [App\Http\Controllers\UserController::class, 'register']);
Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout']);


Route::post('/forgot-password', [App\Http\Controllers\NewPasswordController::class, 'forgotPassword']);
Route::post('/reset-password', [App\Http\Controllers\NewPasswordController::class, 'reset']);


Route::post('password/email',  [ForgotPasswordController::class, '__construct']);
Route::post('password/code/check', [CodeCheckController::class, '__construct']);
Route::post('password/reset', [ResetPasswordController::class, '__construct']);


Route::get('/mail', function () {
    return view('mail');
});



Route::get('/show/{id}', [App\Http\Controllers\UserController::class, 'get']);

//proprietaire
Route::post('proprietaire/register', [App\Http\Controllers\ProprietaireController::class, 'register']);
Route::post('proprietaire/login', [App\Http\Controllers\ProprietaireController::class, 'login']);

//utilisateur
Route::post('utilisateur/register', [App\Http\Controllers\UtilisateurController::class, 'register']);
Route::post('utilisateur/login', [App\Http\Controllers\UtilisateurController::class, 'login']);

//gestionnaire
Route::post('gestionnaire/register', [App\Http\Controllers\GestionnaireController::class, 'register']);
Route::post('gestionnaire/login', [App\Http\Controllers\GestionnaireController::class, 'login']);

//superAdmin
Route::post('superAdmin/register', [App\Http\Controllers\SuperAdminController::class, 'register']);
Route::post('superAdmin/login', [App\Http\Controllers\SuperAdminController::class, 'login']);

//client
Route::post('/client/register', [App\Http\Controllers\ClientController::class, 'register']);
Route::post('/client/login', [App\Http\Controllers\ClientController::class, 'login']);

Route::post('/invit',[App\Http\Controllers\CaisseController::class,'LienInvitation']);

Route::get('/getUser',[App\Http\Controllers\CaisseController::class,'get']);
Route::get('/getId/{id}',[App\Http\Controllers\CaisseController::class,'getId']);


//caisse
Route::get('/getCaisse',[App\Http\Controllers\CaisseController::class,'showCaisseUser']);

Route::post('/loginInviter', [App\Http\Controllers\CaisseController::class, 'loginInviter']);
Route::post('/asset',[App\Http\Controllers\CaisseController::class,'index']);
Route::get('/caisse/{id}',[App\Http\Controllers\CaisseController::class,'show']);
Route::post('/caisse',[App\Http\Controllers\CaisseController::class,'store']);
Route::put('/caisse/{id}',[App\Http\Controllers\CaisseController::class,'update']);
Route::delete('/caisse/{id}',[App\Http\Controllers\CaisseController::class,'destroy']);


