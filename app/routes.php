<?php

require_once('../../fw/Route.php');
use fw\Route;

Route::get('/sddsds', 'SiteController@index');
Route::get('/sddsds55566', 'SiteController1@index2');

Route::load();
