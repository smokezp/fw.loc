<?php

require_once('../../fw/Route.php');
use fw\Route;

Route::get('/sddsds', 'SiteController@index');
Route::post('/sddsds55566', 'SiteController@index2');

Route::load();
