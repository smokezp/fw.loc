<?php

require_once(__FW__ . 'Route.php');

use fw\Route;

Route::get('/{hhhj}/{sassas}', 'SiteController@index');
Route::post('/sddsds55566/{sdsdsdsd}', 'SiteController@index2')->name('dddd');
Route::get('/ffdff3', 'SiteController@index3');

Route::load();
