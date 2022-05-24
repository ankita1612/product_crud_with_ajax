<?php
  
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DataTableAjaxCRUDController;
use App\Http\Controllers\MultiFileUploadAjaxController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group that
| contains the "web" middleware group. Now create something great!
|
*/
  
//Route::resource('products', ProductController::class);
Route::get('users', [UserController::class, 'index'])->name('users.index');

Route::get('products', [ProductController::class, 'index']);
Route::post('product_store', [ProductController::class, 'store']);
Route::post('product_edit', [ProductController::class, 'edit']);
Route::post('product_delete', [ProductController::class, 'destroy']);
Route::post('image_delete', [ProductController::class, 'image_delete']);

//ajax crud 
Route::get('ajax-crud-datatable', [DataTableAjaxCRUDController::class, 'index']);
Route::post('store-company', [DataTableAjaxCRUDController::class, 'store']);
Route::post('edit-company', [DataTableAjaxCRUDController::class, 'edit']);
Route::post('delete-company', [DataTableAjaxCRUDController::class, 'destroy']);

//file uploaf
Route::get('multi-file-ajax-upload', [MultiFileUploadAjaxController::class, 'index']); 
Route::post('store-multi-file-ajax', [MultiFileUploadAjaxController::class, 'storeMultiFile']);
