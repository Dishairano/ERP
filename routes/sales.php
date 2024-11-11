<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;

Route::prefix('sales')->name('sales.')->group(function () {
  // Dashboard
  Route::get('/', [SalesController::class, 'dashboard'])->name('dashboard');

  // Quotes
  Route::get('/quotes', [SalesController::class, 'quotes'])->name('quotes');
  Route::get('/quotes/create', [SalesController::class, 'createQuote'])->name('quotes.create');
  Route::post('/quotes', [SalesController::class, 'storeQuote'])->name('quotes.store');
  Route::post('/quotes/{quote}/status', [SalesController::class, 'updateQuoteStatus'])->name('quotes.status');
  Route::post('/quotes/{quote}/convert', [SalesController::class, 'convertQuoteToOrder'])->name('quotes.convert');

  // Orders
  Route::get('/orders', [SalesController::class, 'orders'])->name('orders');
  Route::get('/orders/create', [SalesController::class, 'createOrder'])->name('orders.create');
  Route::post('/orders', [SalesController::class, 'storeOrder'])->name('orders.store');
  Route::get('/orders/{order}/items', [SalesController::class, 'getOrderItems'])->name('orders.items');

  // Invoices
  Route::get('/invoices', [SalesController::class, 'invoices'])->name('invoices');
  Route::post('/invoices/{invoice}/status', [SalesController::class, 'updateInvoiceStatus'])->name('invoices.status');
  Route::get('/invoices/{invoice}/download', [SalesController::class, 'downloadInvoice'])->name('invoices.download');
  Route::post('/invoices/{invoice}/send', [SalesController::class, 'sendInvoice'])->name('invoices.send');

  // Returns
  Route::get('/returns', [SalesController::class, 'returns'])->name('returns');
  Route::get('/returns/create', [SalesController::class, 'createReturn'])->name('returns.create');
  Route::post('/returns', [SalesController::class, 'storeReturn'])->name('returns.store');
  Route::post('/returns/{return}/status', [SalesController::class, 'updateReturnStatus'])->name('returns.status');
});
