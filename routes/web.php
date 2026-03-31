<?php

use App\Livewire\Survey;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::livewire('/survey', Survey::class)->name('survey');
