<?php

use App\Http\Controllers\InvestigatorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

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

Route::get('/saludo/{nombre}', function ($nombre = 'Usuario'){
    return 'Hola '.$nombre." :3";
});

// Route::post('/orcid/create', function(Request $request) {
//     $response = Http::accept('application/json')
//         ->get('https://pub.orcid.org/v3.0/'.$request->orcid);

//     return $response->json();
// });

Route::post('/orcid/create/', [InvestigatorController::class, 'create']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
