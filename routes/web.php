<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/transactions', function () {
        return view('transactions');
    })->name('transactions');

    Route::get('/categories', function () {
        return view('categories');
    })->name('categories');

    Route::get('/budgets', function () {
        return view('budgets');
    })->name('budgets');

    Route::get('/reports', function () {
        return view('reports');
    })->name('reports');

    Route::get('/loans', function () {
        return view('loans');
    })->name('loans');

    Route::get('/accounts', function () {
        return view('accounts');
    })->name('accounts');

    Route::get('/recurring', function () {
        return view('recurring');
    })->name('recurring');
});
