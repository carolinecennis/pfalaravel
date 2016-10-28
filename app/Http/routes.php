<?php

/*
|--------------------------------------------------------------------------
| Application Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function () {

    Route::get('/', 'HomeController@index');

    //upload items
    Route::get('/upload','UploadController@upload');
    Route::post('/upload', 'UploadController@upload');
    Route::post('/populateTables', 'UploadController@populateTables' );

    //duplicates items
    Route::post('/duplicates', 'DuplicatesController@processDuplicates');
    Route::get('/run','DuplicatesController@run');
    // Route::get('/updateDups/{key}/{value}/{division}', 'DuplicatesController@updateDuplicates');
    Route::post('/updateDups', 'DuplicatesController@updateDuplicates');
   // Route::post('/updateDups', 'DuplicatesController@updateDuplicates');

    //summing items
    Route::post('/updateSum', 'DuplicatesController@sumDuplicates');

    //division items
    Route::get('api/divisions/{division}', 'DeleteController@getDates');
    Route::get('api/runnumbers/{division}', 'DeleteController@getRunnumber');

    //delete items
    Route::get('/delete', 'DeleteController@deleteMain');
    Route::post('/delete/data', 'DeleteController@delete');

    //zero amt items
    Route::get('/zeroPaid', 'ZeroPaidController@index');
    Route::post('/zeroUpload', 'ZeroPaidController@upload');

    //report items
    Route::get('reports', 'ReportController@reportsHome');
    Route::post('reports/get', 'ReportController@getReports');
    Route::post('reports/view', 'ReportController@downloadReports');
    Route::controller('datatables', 'DatatablesController', [
        'anyData'  => 'datatables.data',
        'getIndex' => 'datatables',
    ]);

});

//Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
//    var_dump($query->sql);
//    var_dump($query->bindings);
//    var_dump($query->time);
//});
