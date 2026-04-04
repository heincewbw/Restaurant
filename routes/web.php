<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\DokuQrisController;

Route::get('/', [FrontendController::class, 'scanForm'])->name('scan');
Route::post('/scan', [FrontendController::class, 'scanSubmit'])->name('scan.submit');
Route::get('/table/{code}', [FrontendController::class, 'scanDirect'])->name('scan.direct');
Route::get('/menu', [FrontendController::class, 'menuList'])->name('menu.list');
Route::get('/cart', [FrontendController::class, 'cart'])->name('cart');
Route::post('/cart/add', [FrontendController::class, 'cartAdd'])->name('cart.add');
Route::post('/cart/add-multiple', [FrontendController::class, 'cartAddMultiple'])->name('cart.addMultiple');
Route::post('/checkout', [FrontendController::class, 'checkout'])->name('checkout');
Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen');
Route::post('/kitchen/{order}/start', [KitchenController::class, 'start'])->name('kitchen.start');
Route::post('/kitchen/{order}/done', [KitchenController::class, 'done'])->name('kitchen.done');

// QRIS checkout
Route::get('/checkout-qris', [FrontendController::class, 'checkoutQris'])->name('checkout.qris');
Route::post('/checkout-qris-confirm', [FrontendController::class, 'checkoutQrisConfirm'])->name('checkout.qris.confirm');

// use App\Http\Controllers\AuthController; // Dihapus duplikat
use App\Http\Controllers\Admin\MenuController as AdminMenuController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->group(function () {
    Route::get('/menu', [AdminMenuController::class, 'index'])->name('admin.menu.index');
    Route::get('/menu/create', [AdminMenuController::class, 'create'])->name('admin.menu.create');
    Route::post('/menu', [AdminMenuController::class, 'store'])->name('admin.menu.store');
    Route::get('/menu/{menu}/edit', [AdminMenuController::class, 'edit'])->name('admin.menu.edit');
    Route::put('/menu/{menu}', [AdminMenuController::class, 'update'])->name('admin.menu.update');
    Route::delete('/menu/{menu}', [AdminMenuController::class, 'destroy'])->name('admin.menu.destroy');
});

// Simple login for admin/koki
use App\Http\Controllers\AuthController;

Route::get('/login-simple', [AuthController::class, 'showSimpleLogin'])->name('login.simple');
Route::post('/login-simple', [AuthController::class, 'simpleLogin'])->name('login.simple.post');
Route::post('/logout-simple', [AuthController::class, 'simpleLogout'])->name('logout.simple');

Route::get('/qris-doku', [DokuQrisController::class, 'showQris'])->name('qris.doku');
Route::post('/doku/webhook', [DokuQrisController::class, 'webhook'])->name('doku.webhook');
