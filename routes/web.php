<?php

use App\Http\Controllers\ContactController;
use App\Livewire\Survey;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');

Route::livewire('/survey', Survey::class)->name('survey');
