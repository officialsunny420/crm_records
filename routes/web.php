<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/list-user', [App\Http\Controllers\HomeController::class, 'listUser'])->name('list.user');
Route::get('/add-user', [App\Http\Controllers\HomeController::class, 'addUser'])->name('add.user');
Route::post('/add-user', [App\Http\Controllers\HomeController::class, 'storeUser'])->name('store.user');
Route::get('/edit-user/{id}', [App\Http\Controllers\HomeController::class, 'edit'])->name('user.edit');
Route::post('/user-update/{id}', 'App\Http\Controllers\HomeController@update')->name('user.update');
Route::delete('/user-destroy/{id}', 'App\Http\Controllers\HomeController@destroy')->name('user.destroy');
Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profileEdit'])->name('profile.edit');
Route::post('/profile', 'App\Http\Controllers\HomeController@profileUpdate')->name('profile.update');

Route::get('/add-balance', [App\Http\Controllers\HomeController::class, 'addBalance'])->name('add.balance');
Route::post('/add-balance', [App\Http\Controllers\HomeController::class, 'storeBalance'])->name('store.balance');

Route::prefix('stock')->group(function () {
   // Route::get('/', 'UserController@index')->name('stock.index');
    Route::get('/index', [App\Http\Controllers\StocksController::class, 'index'])->name('stock.index');
  //  Route::get('/list-user', [App\Http\Controllers\StocksController::class, 'listUser'])->name('stock.user');
    Route::get('/add', [App\Http\Controllers\StocksController::class, 'add'])->name('stock.add');
    Route::post('/add', [App\Http\Controllers\StocksController::class, 'store'])->name('stock.store');
    Route::get('/edit/{id}', [App\Http\Controllers\StocksController::class, 'edit'])->name('stock.edit');
    Route::post('/update/{id}', 'App\Http\Controllers\StocksController@update')->name('stock.update');
    Route::delete('/destroy/{id}', 'App\Http\Controllers\StocksController@destroy')->name('stock.destroy');
});
Route::prefix('entry')->group(function () {
  // Route::get('/', 'UserController@index')->name('stock.index');
   Route::get('/index', [App\Http\Controllers\EntryController::class, 'index'])->name('entry.index');
 //  Route::get('/list-user', [App\Http\Controllers\StocksController::class, 'listUser'])->name('stock.user');
   Route::get('/add', [App\Http\Controllers\EntryController::class, 'add'])->name('entry.add');
   Route::post('/add', [App\Http\Controllers\EntryController::class, 'store'])->name('entry.store');
   Route::get('/edit/{id}', [App\Http\Controllers\EntryController::class, 'edit'])->name('entry.edit');
   Route::post('/update/{id}', 'App\Http\Controllers\EntryController@update')->name('entry.update');
   Route::delete('/destroy/{id}', 'App\Http\Controllers\EntryController@destroy')->name('entry.destroy');
});