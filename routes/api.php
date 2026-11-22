<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserCollectionController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - FlipPlay REST API (Modul 4)
|--------------------------------------------------------------------------
| Seluruh endpoint REST API FlipPlay didaftarkan di sini.
| Mendukung CRUD standar Media dan Custom Actions Koleksi Pengguna.
*/

// Media CRUD API Resource
Route::apiResource('media', MediaController::class)
    ->parameters(['media' => 'media']);

// User Collection Custom Actions
Route::prefix('users/{user}/collection')->group(function () {
    Route::get('/', [UserCollectionController::class, 'index'])->name('users.collection.index');
    Route::post('/{media}', [UserCollectionController::class, 'store'])->name('users.collection.store');
    Route::delete('/{media}', [UserCollectionController::class, 'destroy'])->name('users.collection.destroy');
});

// Helper route untuk mendapatkan data users untuk testing
Route::get('users', function () {
    return UserResource::collection(User::with('media')->get());
})->name('users.index');

Route::get('users/{user}', function (User $user) {
    return new UserResource($user->load('media'));
})->name('users.show');
