<?php

use App\Livewire\PurchaseList;
use App\Livewire\PurchaseForm;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});


Route::middleware([
    'auth',
    'role:Admin',
    ])->group(function () {
        Route::get('/purchases', PurchaseList::class)->name('purchases.index');
        Route::get('/purchases/create', PurchaseForm::class)->name('purchases.create');

    });

Route::middleware('auth')->group(function () {

});

require __DIR__ . '/settings.php';
