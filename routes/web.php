<?php

use App\Livewire\PurchaseForm;
use App\Livewire\PurchaseList;
use App\Livewire\PurchaseView;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/purchases', PurchaseList::class)->name('purchases.index');
    Route::get('/purchases/{purchase}/view', PurchaseView::class)->name('purchases.view');

    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/purchases/create', PurchaseForm::class)->name('purchases.create');
        Route::get('/purchases/{purchase}/edit', PurchaseForm::class)->name('purchases.edit');
    });
});

require __DIR__ . '/settings.php';
