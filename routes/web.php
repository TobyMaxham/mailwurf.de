<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum', 'verified'], function () {

    Route::get('/', fn() => redirect()->route('account.index'));
    Route::get('/dashboard', fn() => redirect()->route('account.index'))->name('dashboard');

    /* Mail Routes */
    Route::get('/mail', [MailController::class, 'index'])->name('mail.index');
    Route::get('/mail/{mail:mail_key}', [MailController::class, 'show'])->name('mail.show');
    Route::get('/mail/{mail:mail_key}/delete', [MailController::class, 'delete'])->name('mail.delete');
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::post('/account', [AccountController::class, 'store'])->name('account.store');
});

Route::get('/logout', function () {
    auth()->logout();

    return redirect('/');
})->name('logout');
