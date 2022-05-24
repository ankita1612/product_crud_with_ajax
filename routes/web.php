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
  
Route::get('/', function () {
    return view('welcome');
});
Route::get('products', [ProductController::class, 'index']);
Route::post('product_store', [ProductController::class, 'store']);
Route::post('product_edit', [ProductController::class, 'edit']);
Route::post('product_delete', [ProductController::class, 'destroy']);
Route::get('image_delete/{id}', [ProductController::class, 'image_delete']);
Route::get('image_delete1', [ProductController::class, 'image_delete']);


