<?php

use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\ScraperController;
use App\Http\Controllers;
use Goutte\Client;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', [ScraperController::class, 'view']);

//Route::get('spider', [ScraperController::class, 'index']);

Route::get('/', 'HomeController@index');
Route::get('/article-details/{id}', 'HomeController@getArticleDetails');
Route::get('/category/{id}', 'HomeController@getCategory');

Route::group(['prefix' => 'dashboard'], function() {
    Route::resource('/websites', 'WebsitesController');
    Route::resource('/categories', 'CategoriesController');
    Route::patch('/links/set-item-schema', 'LinksController@setItemSchema');
    Route::post('/links/scrape', 'LinksController@scrape');
    Route::resource('/links', 'LinksController');
    Route::resource('/item-schema', 'ItemSchemaController');
    Route::resource('/articles', 'ArticlesController');
});

