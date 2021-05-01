<?php
use App\Services\MediaImportService;

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

Route::get('/', 'HomeController@index');

Route::get('/admin', 'AdminController@index')->name('admin');
Route::post('/admin', 'AdminController@update')->name('admin.update');
Route::get('/pm', function () {
  $ms = new  MediaImportService();
  $ms->import();
  return response('ok', 200);
});
Auth::routes();

