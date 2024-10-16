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
use App\Livewire\ProductsDetailsPage;
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

Route::get('/', HomePage::class)->name('home');
Route::get('/categories', CategoriesPage::class)->name('categories.home');
Route::get('/articles', ProductsPage::class)->name('products.home');
Route::get('/articles/categorie={c?}', ProductsPage::class)->name('products.home.oncategoryselected');
Route::get('/articles/marque.game={b?}', ProductsPage::class)->name('products.home.onbrandselected');

Route::get('/articles/article={slug}', ProductsDetailsPage::class)->name('product');
Route::get('/cart', CartsPage::class)->name('carts.home');
Route::get('/articles/article={product}', CartsPage::class)->name('cart');
Route::get('/checkout', CheckoutPage::class)->name('checkout');
Route::get('/mes-commandes', MyOrdersPage::class)->name('my_orders');
Route::get('/mes-commandes/{order}', MyOrderDetailPage::class)->name('my_order');


Route::get('/success', SuccessPage::class)->name('success');
Route::get('/annule', CancelPage::class)->name('cancel');


Route::get('/connexion', LoginPage::class)->name('login');
Route::get('/inscription', LoginPage::class)->name('register');
Route::get('/reinitialisation-mot-de-passe', ResetPasswordPage::class)->name('password.reset');
Route::get('/mot-de-passe-oublie', ForgotPasswordPage::class)->name('password.forgot');

