<?php

use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\CancelPage;
use App\Livewire\CartsPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\HomePage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\ProductsPage;
use App\Livewire\SuccessPage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', HomePage::class);
Route::get('/categories', CategoriesPage::class)->name('categories.home');
Route::get('/products', ProductsPage::class)->name('products.home');
Route::get('/cart', CartsPage::class)->name('carts.home');
Route::get('/products/{product}', CartsPage::class)->name('product');
Route::get('/checkout', CheckoutPage::class)->name('checkout');
Route::get('/orders', MyOrdersPage::class)->name('my_orders');
Route::get('/my_orders/{order}', MyOrderDetailPage::class)->name('my_order');


Route::get('/success', SuccessPage::class)->name('success');
Route::get('/cancel', CancelPage::class)->name('cancel');


Route::get('/login', LoginPage::class)->name('login');
Route::get('/register', LoginPage::class)->name('register');
Route::get('/reset', ResetPasswordPage::class)->name('reset');
Route::get('/forgot', ForgotPasswordPage::class)->name('reset');
