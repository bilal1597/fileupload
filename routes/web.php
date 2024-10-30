<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/products', [ProductController::class, 'showProducts'])->name('show.products');

Route::get('/add/product', [ProductController::class, 'getAddProduct'])->name('get.Addproduct');
Route::post('/add/product', [ProductController::class, 'postAddProduct'])->name('post.product');

Route::get('/edit/{id}', [ProductController::class, 'getEditProduct'])->name('get.Editproduct');
Route::post('/edit/product/', [ProductController::class, 'postEditProduct'])->name('post.Editproduct');

Route::get('/delete/{id}', [ProductController::class, 'deleteProduct'])->name('delete');

Route::get('/image/view/{productId}', [ProductController::class, 'ImgView'])->name('image.view');
Route::post('/image/view/{productId}', [ProductController::class, 'ImgPost'])->name('image.post');
Route::get('/image/delete/{imageId}', [ProductController::class, 'ImgDelete']);
