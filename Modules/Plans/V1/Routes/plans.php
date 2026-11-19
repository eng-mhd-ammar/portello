<?php

use Illuminate\Support\Facades\Route;
use Modules\Plans\Models\Plan;
use Modules\Plans\V1\Controllers\AdminPlanController;
use Modules\Plans\V1\Controllers\BillingController;

Route::middleware(['auth', 'can:billing.index'])->group(function () {
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/subscription', [BillingController::class, 'switch'])->name('billing.subscription.switch');
});

Route::middleware(['auth', 'can:plans.manage'])->prefix('admin/plans')->name('admin.plans.')->group(function () {
    Route::get('/', [AdminPlanController::class, 'index'])->name('index');
    Route::get('/create', [AdminPlanController::class, 'create'])->name('create');
    Route::post('/', [AdminPlanController::class, 'store'])->name('store');
    Route::get('/{plan}/edit', [AdminPlanController::class, 'edit'])->name('edit');
    Route::put('/{plan}', [AdminPlanController::class, 'update'])->name('update');
});

Route::bind('plan', fn (string $value) => Plan::query()->whereKey($value)->whereNull('deleted_at')->firstOrFail());
