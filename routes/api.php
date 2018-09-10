<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/commands', 'CyberDuck\NovaArtisan\Http\Controllers\ArtisanController@list');
Route::get('/commands/{name}', 'CyberDuck\NovaArtisan\Http\Controllers\ArtisanController@command');
Route::any('/commands/{name}/run', 'CyberDuck\NovaArtisan\Http\Controllers\ArtisanController@run');