<?php

use Illuminate\Support\Facades\Route;
use Modules\Portfolios\Models\Portfolio;
use Modules\Portfolios\V1\Controllers\PortfolioController;

Route::middleware(['auth', 'can:portfolios.index'])->group(function () {
    Route::get('/portfolios', [PortfolioController::class, 'index'])->name('portfolios.index');
    Route::get('/portfolios/create', [PortfolioController::class, 'create'])
        ->middleware('can:portfolios.create')
        ->name('portfolios.create');
    Route::post('/portfolios', [PortfolioController::class, 'store'])
        ->middleware('can:portfolios.create')
        ->name('portfolios.store');
    Route::post('/portfolios/{portfolio}/publish', [PortfolioController::class, 'publish'])
        ->middleware('can:portfolios.update')
        ->name('portfolios.publish');
});

Route::bind('portfolio', fn (string $value) => Portfolio::query()->whereKey($value)->whereNull('deleted_at')->firstOrFail());
