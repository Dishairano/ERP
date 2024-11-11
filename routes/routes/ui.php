<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UiController;

Route::middleware(['auth'])->group(function () {
  Route::get('/ui/buttons', [UiController::class, 'buttons'])->name('ui.buttons');
  Route::get('/ui/cards', [UiController::class, 'cards'])->name('ui.cards');
  Route::get('/ui/carousel', [UiController::class, 'carousel'])->name('ui.carousel');
  Route::get('/ui/dropdowns', [UiController::class, 'dropdowns'])->name('ui.dropdowns');
  Route::get('/ui/footer', [UiController::class, 'footer'])->name('ui.footer');
  Route::get('/ui/list-groups', [UiController::class, 'listGroups'])->name('ui.list-groups');
  Route::get('/ui/modals', [UiController::class, 'modals'])->name('ui.modals');
  Route::get('/ui/navbar', [UiController::class, 'navbar'])->name('ui.navbar');
  Route::get('/ui/offcanvas', [UiController::class, 'offcanvas'])->name('ui.offcanvas');
  Route::get('/ui/pagination', [UiController::class, 'pagination'])->name('ui.pagination');
  Route::get('/ui/progress', [UiController::class, 'progress'])->name('ui.progress');
  Route::get('/ui/spinners', [UiController::class, 'spinners'])->name('ui.spinners');
  Route::get('/ui/tabs-pills', [UiController::class, 'tabsPills'])->name('ui.tabs-pills');
  Route::get('/ui/toasts', [UiController::class, 'toasts'])->name('ui.toasts');
  Route::get('/ui/tooltips-popovers', [UiController::class, 'tooltipsPopovers'])->name('ui.tooltips-popovers');
  Route::get('/ui/typography', [UiController::class, 'typography'])->name('ui.typography');
});
