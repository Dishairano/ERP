<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoreFinanceAccountController;
use App\Http\Controllers\CoreFinanceJournalController;
use App\Http\Controllers\CoreFinanceJournalEntryController;
use App\Http\Controllers\CoreFinanceVendorController;
use App\Http\Controllers\CoreFinancePayableController;
use App\Http\Controllers\CoreFinancePayablePaymentController;
use App\Http\Controllers\CoreFinanceCustomerController;
use App\Http\Controllers\CoreFinanceReceivableController;
use App\Http\Controllers\CoreFinanceReceivablePaymentController;
use App\Http\Controllers\CoreFinanceAssetController;
use App\Http\Controllers\CoreFinanceAssetDepreciationController;
use App\Http\Controllers\CoreFinanceTaxController;
use App\Http\Controllers\CoreFinanceTaxTransactionController;
use App\Http\Controllers\CoreFinanceReportController;
use App\Http\Controllers\CoreFinanceBankAccountController;
use App\Http\Controllers\CoreFinanceBankTransactionController;
use App\Http\Controllers\CoreFinanceBankReconciliationController;
use App\Http\Controllers\CoreFinanceCashForecastController;
use App\Http\Controllers\CoreFinanceCashForecastItemController;
use App\Http\Controllers\CoreFinanceCashFlowController;
use App\Http\Controllers\CoreFinanceCashFlowEntryController;

Route::middleware(['auth'])->group(function () {
  // Chart of Accounts Routes
  Route::prefix('accounts')->name('finance.accounts.')->group(function () {
    Route::get('/', [CoreFinanceAccountController::class, 'index'])->name('index');
    Route::get('/create', [CoreFinanceAccountController::class, 'create'])->name('create');
    Route::post('/', [CoreFinanceAccountController::class, 'store'])->name('store');
    Route::get('/{account}', [CoreFinanceAccountController::class, 'show'])->name('show');
    Route::get('/{account}/edit', [CoreFinanceAccountController::class, 'edit'])->name('edit');
    Route::put('/{account}', [CoreFinanceAccountController::class, 'update'])->name('update');
    Route::delete('/{account}', [CoreFinanceAccountController::class, 'destroy'])->name('destroy');
    Route::get('/chart', [CoreFinanceAccountController::class, 'chart'])->name('chart');
    Route::get('/balances', [CoreFinanceAccountController::class, 'balances'])->name('balances');
  });

  // Journal Routes
  Route::prefix('journals')->name('finance.journals.')->group(function () {
    Route::get('/', [CoreFinanceJournalController::class, 'index'])->name('index');
    Route::get('/create', [CoreFinanceJournalController::class, 'create'])->name('create');
    Route::post('/', [CoreFinanceJournalController::class, 'store'])->name('store');
    Route::get('/{journal}', [CoreFinanceJournalController::class, 'show'])->name('show');
    Route::get('/{journal}/edit', [CoreFinanceJournalController::class, 'edit'])->name('edit');
    Route::put('/{journal}', [CoreFinanceJournalController::class, 'update'])->name('update');
    Route::delete('/{journal}', [CoreFinanceJournalController::class, 'destroy'])->name('destroy');
    Route::post('/{journal}/post', [CoreFinanceJournalController::class, 'post'])->name('post');
    Route::post('/{journal}/approve', [CoreFinanceJournalController::class, 'approve'])->name('approve');
    Route::get('/general-ledger', [CoreFinanceJournalController::class, 'generalLedger'])->name('general-ledger');

    // Journal Entries
    Route::get('/{journal}/entries', [CoreFinanceJournalEntryController::class, 'index'])->name('entries.index');
    Route::get('/{journal}/entries/create', [CoreFinanceJournalEntryController::class, 'create'])->name('entries.create');
    Route::post('/{journal}/entries', [CoreFinanceJournalEntryController::class, 'store'])->name('entries.store');
    Route::get('/{journal}/entries/{entry}', [CoreFinanceJournalEntryController::class, 'show'])->name('entries.show');
    Route::get('/{journal}/entries/{entry}/edit', [CoreFinanceJournalEntryController::class, 'edit'])->name('entries.edit');
    Route::put('/{journal}/entries/{entry}', [CoreFinanceJournalEntryController::class, 'update'])->name('entries.update');
    Route::delete('/{journal}/entries/{entry}', [CoreFinanceJournalEntryController::class, 'destroy'])->name('entries.destroy');
    Route::post('/{journal}/entries/bulk-update', [CoreFinanceJournalEntryController::class, 'bulkUpdate'])->name('entries.bulk-update');
  });

  // Vendor Routes
  Route::prefix('vendors')->name('finance.vendors.')->group(function () {
    Route::get('/', [CoreFinanceVendorController::class, 'index'])->name('index');
    Route::get('/create', [CoreFinanceVendorController::class, 'create'])->name('create');
    Route::post('/', [CoreFinanceVendorController::class, 'store'])->name('store');
    Route::get('/{vendor}', [CoreFinanceVendorController::class, 'show'])->name('show');
    Route::get('/{vendor}/edit', [CoreFinanceVendorController::class, 'edit'])->name('edit');
    Route::put('/{vendor}', [CoreFinanceVendorController::class, 'update'])->name('update');
    Route::delete('/{vendor}', [CoreFinanceVendorController::class, 'destroy'])->name('destroy');
    Route::get('/{vendor}/statement', [CoreFinanceVendorController::class, 'statement'])->name('statement');
    Route::get('/aging', [CoreFinanceVendorController::class, 'aging'])->name('aging');
  });

  // Payable Routes
  Route::prefix('payables')->name('finance.payables.')->group(function () {
    Route::get('/', [CoreFinancePayableController::class, 'index'])->name('index');
    Route::get('/create', [CoreFinancePayableController::class, 'create'])->name('create');
    Route::post('/', [CoreFinancePayableController::class, 'store'])->name('store');
    Route::get('/{payable}', [CoreFinancePayableController::class, 'show'])->name('show');
    Route::get('/{payable}/edit', [CoreFinancePayableController::class, 'edit'])->name('edit');
    Route::put('/{payable}', [CoreFinancePayableController::class, 'update'])->name('update');
    Route::delete('/{payable}', [CoreFinancePayableController::class, 'destroy'])->name('destroy');
    Route::post('/{payable}/approve', [CoreFinancePayableController::class, 'approve'])->name('approve');
    Route::get('/aging', [CoreFinancePayableController::class, 'aging'])->name('aging');

    // Payable Payments
    Route::get('/{payable}/payments', [CoreFinancePayablePaymentController::class, 'index'])->name('payments.index');
    Route::get('/{payable}/payments/create', [CoreFinancePayablePaymentController::class, 'create'])->name('payments.create');
    Route::post('/{payable}/payments', [CoreFinancePayablePaymentController::class, 'store'])->name('payments.store');
    Route::get('/{payable}/payments/{payment}', [CoreFinancePayablePaymentController::class, 'show'])->name('payments.show');
    Route::get('/{payable}/payments/{payment}/edit', [CoreFinancePayablePaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/{payable}/payments/{payment}', [CoreFinancePayablePaymentController::class, 'update'])->name('payments.update');
    Route::delete('/{payable}/payments/{payment}', [CoreFinancePayablePaymentController::class, 'destroy'])->name('payments.destroy');
    Route::post('/{payable}/payments/{payment}/approve', [CoreFinancePayablePaymentController::class, 'approve'])->name('payments.approve');
  });

  // Customer Routes
  Route::prefix('customers')->name('finance.customers.')->group(function () {
    Route::get('/', [CoreFinanceCustomerController::class, 'index'])->name('index');
    Route::get('/create', [CoreFinanceCustomerController::class, 'create'])->name('create');
    Route::post('/', [CoreFinanceCustomerController::class, 'store'])->name('store');
    Route::get('/{customer}', [CoreFinanceCustomerController::class, 'show'])->name('show');
    Route::get('/{customer}/edit', [CoreFinanceCustomerController::class, 'edit'])->name('edit');
    Route::put('/{customer}', [CoreFinanceCustomerController::class, 'update'])->name('update');
    Route::delete('/{customer}', [CoreFinanceCustomerController::class, 'destroy'])->name('destroy');
    Route::get('/{customer}/statement', [CoreFinanceCustomerController::class, 'statement'])->name('statement');
    Route::get('/aging', [CoreFinanceCustomerController::class, 'aging'])->name('aging');
  });

  // Receivable Routes
  Route::prefix('receivables')->name('finance.receivables.')->group(function () {
    Route::get('/', [CoreFinanceReceivableController::class, 'index'])->name('index');
    Route::get('/create', [CoreFinanceReceivableController::class, 'create'])->name('create');
    Route::post('/', [CoreFinanceReceivableController::class, 'store'])->name('store');
    Route::get('/{receivable}', [CoreFinanceReceivableController::class, 'show'])->name('show');
    Route::get('/{receivable}/edit', [CoreFinanceReceivableController::class, 'edit'])->name('edit');
    Route::put('/{receivable}', [CoreFinanceReceivableController::class, 'update'])->name('update');
    Route::delete('/{receivable}', [CoreFinanceReceivableController::class, 'destroy'])->name('destroy');
    Route::post('/{receivable}/approve', [CoreFinanceReceivableController::class, 'approve'])->name('approve');
    Route::get('/aging', [CoreFinanceReceivableController::class, 'aging'])->name('aging');

    // Receivable Payments
    Route::get('/{receivable}/payments', [CoreFinanceReceivablePaymentController::class, 'index'])->name('payments.index');
    Route::get('/{receivable}/payments/create', [CoreFinanceReceivablePaymentController::class, 'create'])->name('payments.create');
    Route::post('/{receivable}/payments', [CoreFinanceReceivablePaymentController::class, 'store'])->name('payments.store');
    Route::get('/{receivable}/payments/{payment}', [CoreFinanceReceivablePaymentController::class, 'show'])->name('payments.show');
    Route::get('/{receivable}/payments/{payment}/edit', [CoreFinanceReceivablePaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/{receivable}/payments/{payment}', [CoreFinanceReceivablePaymentController::class, 'update'])->name('payments.update');
    Route::delete('/{receivable}/payments/{payment}', [CoreFinanceReceivablePaymentController::class, 'destroy'])->name('payments.destroy');
    Route::post('/{receivable}/payments/{payment}/approve', [CoreFinanceReceivablePaymentController::class, 'approve'])->name('payments.approve');
  });

  // Asset Routes
  Route::prefix('assets')->name('finance.assets.')->group(function () {
    Route::get('/', [CoreFinanceAssetController::class, 'index'])->name('index');
    Route::get('/create', [CoreFinanceAssetController::class, 'create'])->name('create');
    Route::post('/', [CoreFinanceAssetController::class, 'store'])->name('store');
    Route::get('/{asset}', [CoreFinanceAssetController::class, 'show'])->name('show');
    Route::get('/{asset}/edit', [CoreFinanceAssetController::class, 'edit'])->name('edit');
    Route::put('/{asset}', [CoreFinanceAssetController::class, 'update'])->name('update');
    Route::post('/{asset}/record-depreciation', [CoreFinanceAssetController::class, 'recordDepreciation'])->name('record-depreciation');
    Route::post('/{asset}/dispose', [CoreFinanceAssetController::class, 'dispose'])->name('dispose');
    Route::post('/{asset}/write-off', [CoreFinanceAssetController::class, 'writeOff'])->name('write-off');
    Route::get('/register', [CoreFinanceAssetController::class, 'register'])->name('register');
    Route::get('/depreciation-schedule', [CoreFinanceAssetController::class, 'depreciationSchedule'])->name('depreciation-schedule');

    // Asset Depreciation Entries
    Route::get('/{asset}/depreciations', [CoreFinanceAssetDepreciationController::class, 'index'])->name('depreciations.index');
    Route::get('/{asset}/depreciations/create', [CoreFinanceAssetDepreciationController::class, 'create'])->name('depreciations.create');
    Route::post('/{asset}/depreciations', [CoreFinanceAssetDepreciationController::class, 'store'])->name('depreciations.store');
    Route::get('/{asset}/depreciations/{depreciation}', [CoreFinanceAssetDepreciationController::class, 'show'])->name('depreciations.show');
    Route::get('/{asset}/depreciations/{depreciation}/edit', [CoreFinanceAssetDepreciationController::class, 'edit'])->name('depreciations.edit');
    Route::put('/{asset}/depreciations/{depreciation}', [CoreFinanceAssetDepreciationController::class, 'update'])->name('depreciations.update');
    Route::delete('/{asset}/depreciations/{depreciation}', [CoreFinanceAssetDepreciationController::class, 'destroy'])->name('depreciations.destroy');
  });

  // Tax Routes
  Route::prefix('taxes')->name('finance.taxes.')->group(function () {
    Route::get('/', [CoreFinanceTaxController::class, 'index'])->name('index');
    Route::get('/create', [CoreFinanceTaxController::class, 'create'])->name('create');
    Route::post('/', [CoreFinanceTaxController::class, 'store'])->name('store');
    Route::get('/{tax}', [CoreFinanceTaxController::class, 'show'])->name('show');
    Route::get('/{tax}/edit', [CoreFinanceTaxController::class, 'edit'])->name('edit');
    Route::put('/{tax}', [CoreFinanceTaxController::class, 'update'])->name('update');
    Route::delete('/{tax}', [CoreFinanceTaxController::class, 'destroy'])->name('destroy');
    Route::get('/filing-report', [CoreFinanceTaxController::class, 'filingReport'])->name('filing-report');
    Route::get('/summary-report', [CoreFinanceTaxController::class, 'summaryReport'])->name('summary-report');

    // Tax Transactions
    Route::get('/transactions', [CoreFinanceTaxTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [CoreFinanceTaxTransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [CoreFinanceTaxTransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}', [CoreFinanceTaxTransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{transaction}/edit', [CoreFinanceTaxTransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction}', [CoreFinanceTaxTransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [CoreFinanceTaxTransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::post('/transactions/{transaction}/file', [CoreFinanceTaxTransactionController::class, 'file'])->name('transactions.file');
    Route::post('/transactions/{transaction}/pay', [CoreFinanceTaxTransactionController::class, 'pay'])->name('transactions.pay');
  });

  // Report Routes
  Route::prefix('reports')->name('finance.reports.')->group(function () {
    Route::get('/', [CoreFinanceReportController::class, 'index'])->name('index');
    Route::get('/create', [CoreFinanceReportController::class, 'create'])->name('create');
    Route::post('/', [CoreFinanceReportController::class, 'store'])->name('store');
    Route::get('/{report}', [CoreFinanceReportController::class, 'show'])->name('show');
    Route::get('/{report}/edit', [CoreFinanceReportController::class, 'edit'])->name('edit');
    Route::put('/{report}', [CoreFinanceReportController::class, 'update'])->name('update');
    Route::delete('/{report}', [CoreFinanceReportController::class, 'destroy'])->name('destroy');
    Route::post('/preview', [CoreFinanceReportController::class, 'preview'])->name('preview');
    Route::get('/{report}/export-pdf', [CoreFinanceReportController::class, 'exportPdf'])->name('export-pdf');
    Route::get('/{report}/export-excel', [CoreFinanceReportController::class, 'exportExcel'])->name('export-excel');
  });

  // Bank Account Routes
  Route::prefix('bank-accounts')->name('finance.bank-accounts.')->group(function () {
    Route::get('/', [CoreFinanceBankAccountController::class, 'index'])->name('index');
    Route::get('/create', [CoreFinanceBankAccountController::class, 'create'])->name('create');
    Route::post('/', [CoreFinanceBankAccountController::class, 'store'])->name('store');
    Route::get('/{account}', [CoreFinanceBankAccountController::class, 'show'])->name('show');
    Route::get('/{account}/edit', [CoreFinanceBankAccountController::class, 'edit'])->name('edit');
    Route::put('/{account}', [CoreFinanceBankAccountController::class, 'update'])->name('update');
    Route::delete('/{account}', [CoreFinanceBankAccountController::class, 'destroy'])->name('destroy');
    Route::get('/{account}/statement', [CoreFinanceBankAccountController::class, 'statement'])->name('statement');
    Route::get('/{account}/reconciliation-report', [CoreFinanceBankAccountController::class, 'reconciliationReport'])->name('reconciliation-report');
    Route::get('/{account}/cash-flow', [CoreFinanceBankAccountController::class, 'cashFlow'])->name('cash-flow');

    // Bank Transactions
    Route::get('/{account}/transactions', [CoreFinanceBankTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/{account}/transactions/create', [CoreFinanceBankTransactionController::class, 'create'])->name('transactions.create');
    Route::post('/{account}/transactions', [CoreFinanceBankTransactionController::class, 'store'])->name('transactions.store');
    Route::get('/{account}/transactions/{transaction}', [CoreFinanceBankTransactionController::class, 'show'])->name('transactions.show');
    Route::get('/{account}/transactions/{transaction}/edit', [CoreFinanceBankTransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/{account}/transactions/{transaction}', [CoreFinanceBankTransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/{account}/transactions/{transaction}', [CoreFinanceBankTransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::post('/{account}/transactions/{transaction}/void', [CoreFinanceBankTransactionController::class, 'void'])->name('transactions.void');

    // Bank Reconciliations
    Route::get('/{account}/reconciliations', [CoreFinanceBankReconciliationController::class, 'index'])->name('reconciliations.index');
    Route::get('/{account}/reconciliations/create', [CoreFinanceBankReconciliationController::class, 'create'])->name('reconciliations.create');
    Route::post('/{account}/reconciliations', [CoreFinanceBankReconciliationController::class, 'store'])->name('reconciliations.store');
    Route::get('/{account}/reconciliations/{reconciliation}', [CoreFinanceBankReconciliationController::class, 'show'])->name('reconciliations.show');
    Route::get('/{account}/reconciliations/{reconciliation}/edit', [CoreFinanceBankReconciliationController::class, 'edit'])->name('reconciliations.edit');
    Route::put('/{account}/reconciliations/{reconciliation}', [CoreFinanceBankReconciliationController::class, 'update'])->name('reconciliations.update');
    Route::post('/{account}/reconciliations/{reconciliation}/complete', [CoreFinanceBankReconciliationController::class, 'complete'])->name('reconciliations.complete');
    Route::post('/{account}/reconciliations/{reconciliation}/cancel', [CoreFinanceBankReconciliationController::class, 'cancel'])->name('reconciliations.cancel');
  });

  Route::prefix('cash-forecasts')->name('finance.cash-forecasts.')->group(function () {
    Route::get('/', [CoreFinanceCashForecastController::class, 'index'])->name('index');
    Route::get('/create', [CoreFinanceCashForecastController::class, 'create'])->name('create');
    Route::post('/', [CoreFinanceCashForecastController::class, 'store'])->name('store');
    Route::get('/{forecast}', [CoreFinanceCashForecastController::class, 'show'])->name('show');
    Route::get('/{forecast}/edit', [CoreFinanceCashForecastController::class, 'edit'])->name('edit');
    Route::put('/{forecast}', [CoreFinanceCashForecastController::class, 'update'])->name('update');
    Route::delete('/{forecast}', [CoreFinanceCashForecastController::class, 'destroy'])->name('destroy');
    Route::get('/analysis', [CoreFinanceCashForecastController::class, 'analysis'])->name('analysis');
    Route::get('/variance', [CoreFinanceCashForecastController::class, 'variance'])->name('variance');
    Route::get('/accuracy', [CoreFinanceCashForecastController::class, 'accuracy'])->name('accuracy');

    // Forecast Items
    Route::get('/{forecast}/items', [CoreFinanceCashForecastItemController::class, 'index'])->name('items.index');
    Route::get('/{forecast}/items/create', [CoreFinanceCashForecastItemController::class, 'create'])->name('items.create');
    Route::post('/{forecast}/items', [CoreFinanceCashForecastItemController::class, 'store'])->name('items.store');
    Route::get('/{forecast}/items/{item}', [CoreFinanceCashForecastItemController::class, 'show'])->name('items.show');
    Route::get('/{forecast}/items/{item}/edit', [CoreFinanceCashForecastItemController::class, 'edit'])->name('items.edit');
    Route::put('/{forecast}/items/{item}', [CoreFinanceCashForecastItemController::class, 'update'])->name('items.update');
    Route::delete('/{forecast}/items/{item}', [CoreFinanceCashForecastItemController::class, 'destroy'])->name('items.destroy');
    Route::post('/{forecast}/items/{item}/realize', [CoreFinanceCashForecastItemController::class, 'realize'])->name('items.realize');
    Route::post('/{forecast}/items/{item}/cancel', [CoreFinanceCashForecastItemController::class, 'cancel'])->name('items.cancel');
    Route::get('/{forecast}/items/{item}/variance', [CoreFinanceCashForecastItemController::class, 'variance'])->name('items.variance');
    Route::post('/{forecast}/items/bulk-update', [CoreFinanceCashForecastItemController::class, 'bulkUpdate'])->name('items.bulk-update');
  });

  // Cash Flow Routes
  Route::prefix('cash-flows')->name('finance.cash-flows.')->group(function () {
    Route::get('/', [CoreFinanceCashFlowController::class, 'index'])->name('index');
    Route::get('/create', [CoreFinanceCashFlowController::class, 'create'])->name('create');
    Route::post('/', [CoreFinanceCashFlowController::class, 'store'])->name('store');
    Route::get('/{cashFlow}', [CoreFinanceCashFlowController::class, 'show'])->name('show');
    Route::get('/{cashFlow}/edit', [CoreFinanceCashFlowController::class, 'edit'])->name('edit');
    Route::put('/{cashFlow}', [CoreFinanceCashFlowController::class, 'update'])->name('update');
    Route::delete('/{cashFlow}', [CoreFinanceCashFlowController::class, 'destroy'])->name('destroy');
    Route::get('/{cashFlow}/analysis', [CoreFinanceCashFlowController::class, 'analysis'])->name('analysis');
    Route::get('/trend', [CoreFinanceCashFlowController::class, 'trend'])->name('trend');
    Route::get('/{cashFlow}/ratios', [CoreFinanceCashFlowController::class, 'ratios'])->name('ratios');
    Route::get('/projection', [CoreFinanceCashFlowController::class, 'projection'])->name('projection');

    // Cash Flow Entries
    Route::get('/{cashFlow}/entries', [CoreFinanceCashFlowEntryController::class, 'index'])->name('entries.index');
    Route::get('/{cashFlow}/entries/create', [CoreFinanceCashFlowEntryController::class, 'create'])->name('entries.create');
    Route::post('/{cashFlow}/entries', [CoreFinanceCashFlowEntryController::class, 'store'])->name('entries.store');
    Route::get('/{cashFlow}/entries/{entry}', [CoreFinanceCashFlowEntryController::class, 'show'])->name('entries.show');
    Route::get('/{cashFlow}/entries/{entry}/edit', [CoreFinanceCashFlowEntryController::class, 'edit'])->name('entries.edit');
    Route::put('/{cashFlow}/entries/{entry}', [CoreFinanceCashFlowEntryController::class, 'update'])->name('entries.update');
    Route::delete('/{cashFlow}/entries/{entry}', [CoreFinanceCashFlowEntryController::class, 'destroy'])->name('entries.destroy');
    Route::post('/{cashFlow}/entries/import', [CoreFinanceCashFlowEntryController::class, 'import'])->name('entries.import');
  });
});
