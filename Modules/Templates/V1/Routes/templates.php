<?php

use Illuminate\Support\Facades\Route;
use Modules\Templates\Models\Template;
use Modules\Templates\V1\Controllers\AdminTemplateController;
use Modules\Templates\V1\Controllers\TemplateCatalogController;

Route::middleware(['auth', 'can:templates.index'])->prefix('templates')->name('templates.')->group(function () {
    Route::get('/', [TemplateCatalogController::class, 'index'])->name('index');
    Route::get('/{template}', [TemplateCatalogController::class, 'show'])->name('show');
});

Route::middleware(['auth', 'can:templates.manage'])->prefix('admin/templates')->name('admin.templates.')->group(function () {
    Route::get('/', [AdminTemplateController::class, 'index'])->name('index');
    Route::get('/create', [AdminTemplateController::class, 'create'])->name('create');
    Route::post('/', [AdminTemplateController::class, 'store'])->name('store');
    Route::get('/{template}/edit', [AdminTemplateController::class, 'edit'])->name('edit');
    Route::put('/{template}', [AdminTemplateController::class, 'update'])->name('update');
});

Route::bind('template', function (string $value) {
    $query = Template::query()->whereKey($value)->whereNull('deleted_at');

    if (! auth()->user()?->can('templates.manage')) {
        $query->where('is_active', true);
    }

    return $query->firstOrFail();
});
