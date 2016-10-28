<?php

/*
|--------------------------------------------------------------------------
| Application API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['api'], 'prefix' => 'api'], function () {
    // NEO Routes
    Route::get('neo/person/{id}', 'NeoController@personSingle');

    // Category API Routes
    Route::get('categories', 'CategoryController@categoryList');
    Route::get('categories/{name}', 'CategoryController@categorySingle');
    Route::post('categories', 'CategoryController@categoryWrite');

    // Listing API Routes
    Route::get('listings', 'ListingController@listingAll');
    Route::get('listings/{id}', 'ListingController@listingSingle');
    Route::post('listings', 'ListingController@listingWrite');

    // Image API Routes
    Route::get('images', 'ImageController@imageAll');
    Route::get('images/{id}', 'ImageController@imageSingle');
});
