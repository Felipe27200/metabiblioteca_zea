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

Route::get('/orcid/list', [InvestigatorController::class, 'index']);
Route::get('/orcid/{orcid}', [InvestigatorController::class, 'show']);
Route::post('/orcid/create/{orcid}', [InvestigatorController::class, 'store']);
Route::delete('/orcid/delete/{orcid}', [InvestigatorController::class, 'destroy']);
