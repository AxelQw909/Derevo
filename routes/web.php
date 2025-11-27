<?php

use App\Http\Controllers\FamilyTreeController;
use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Маршруты для FamilyTree (ресурсные)
    Route::resource('family-trees', FamilyTreeController::class);

    // Маршруты для Person (не ресурсные, т.к. они вложены в дерево)
    Route::get('/family-trees/{family_tree}/people', [PersonController::class, 'index'])->name('people.index');
    Route::post('/family-trees/{family_tree}/people', [PersonController::class, 'store'])->name('people.store');
    Route::get('/people/{person}/edit', [PersonController::class, 'edit'])->name('people.edit');
    Route::put('/people/{person}', [PersonController::class, 'update'])->name('people.update');
    Route::delete('/people/{person}', [PersonController::class, 'destroy'])->name('people.destroy');

    // Маршрут для сохранения позиций карточек
    Route::post('/people/{person}/update-position', [PersonController::class, 'updatePosition'])->name('people.update-position');

    // Маршруты для GEDCOM
    Route::post('/family-trees/{family_tree}/import-gedcom', [FamilyTreeController::class, 'importGedcom'])->name('family-trees.import-gedcom');
    Route::get('/family-trees/{family_tree}/export-gedcom', [FamilyTreeController::class, 'exportGedcom'])->name('family-trees.export-gedcom');
});

require __DIR__.'/auth.php';