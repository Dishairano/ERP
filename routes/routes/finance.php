<?php

use App\Http\Controllers\Accounting\GeneralLedgerController;
use App\Http\Controllers\Accounting\AccountsPayableController;
use App\Http\Controllers\Accounting\AccountsReceivableController;
use App\Http\Controllers\Accounting\FixedAssetsController;
use App\Http\Controllers\Accounting\TaxManagementController;
use App\Http\Controllers\Treasury\BankAccountsController;
use App\Http\Controllers\Treasury\CashManagementController;
use App\Http\Controllers\Treasury\InvestmentsController;
use App\Http\Controllers\FinancialReports\BalanceSheetController;
use App\Http\Controllers\FinancialReports\IncomeStatementController;
use App\Http\Controllers\FinancialReports\CashFlowController;
use App\Http\Controllers\FinancialReports\CustomReportsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
  // Accounting Routes
  Route::prefix('accounting')->group(function () {
    // General Ledger
    Route::resource('general-ledger', GeneralLedgerController::class);

    // Accounts Payable
    Route::resource('accounts-payable', AccountsPayableController::class);
    Route::post('accounts-payable/payments', [AccountsPayableController::class, 'recordPayment'])
      ->name('accounts-payable.payments.store');

    // Accounts Receivable
    Route::resource('accounts-receivable', AccountsReceivableController::class);
    Route::post('accounts-receivable/payments', [AccountsReceivableController::class, 'recordPayment'])
      ->name('accounts-receivable.payments.store');

    // Fixed Assets
    Route::resource('fixed-assets', FixedAssetsController::class);
    Route::post('fixed-assets/depreciation', [FixedAssetsController::class, 'recordDepreciation'])
      ->name('fixed-assets.depreciation.store');
    Route::post('fixed-assets/maintenance', [FixedAssetsController::class, 'scheduleMaintenance'])
      ->name('fixed-assets.maintenance.store');

    // Tax Management
    Route::resource('tax-management', TaxManagementController::class);
    Route::post('tax-management/filings', [TaxManagementController::class, 'scheduleFiling'])
      ->name('tax-management.filings.store');
    Route::post('tax-management/rates', [TaxManagementController::class, 'storeRate'])
      ->name('tax-management.rates.store');
  });

  // Treasury Routes
  Route::prefix('treasury')->group(function () {
    // Bank Accounts
    Route::resource('bank-accounts', BankAccountsController::class);
    Route::post('bank-accounts/import', [BankAccountsController::class, 'importTransactions'])
      ->name('bank-accounts.import');
    Route::post('bank-accounts/reconcile', [BankAccountsController::class, 'reconcile'])
      ->name('bank-accounts.reconcile');

    // Cash Management
    Route::resource('cash-management', CashManagementController::class);
    Route::post('cash-management/forecasts', [CashManagementController::class, 'storeForecast'])
      ->name('cash-management.forecasts.store');
    Route::post('cash-management/transfers', [CashManagementController::class, 'transferFunds'])
      ->name('cash-management.transfers.store');

    // Investments
    Route::resource('investments', InvestmentsController::class);
    Route::post('investments/rebalance', [InvestmentsController::class, 'rebalancePortfolio'])
      ->name('investments.rebalance');
  });

  // Financial Reports Routes
  Route::prefix('financial-reports')->group(function () {
    // Balance Sheet
    Route::get('balance-sheet', [BalanceSheetController::class, 'index'])
      ->name('financial-reports.balance-sheet');
    Route::post('balance-sheet/export', [BalanceSheetController::class, 'export'])
      ->name('financial-reports.balance-sheet.export');

    // Income Statement
    Route::get('income-statement', [IncomeStatementController::class, 'index'])
      ->name('financial-reports.income-statement');
    Route::post('income-statement/export', [IncomeStatementController::class, 'export'])
      ->name('financial-reports.income-statement.export');

    // Cash Flow Statement
    Route::get('cash-flow', [CashFlowController::class, 'index'])
      ->name('financial-reports.cash-flow');
    Route::post('cash-flow/export', [CashFlowController::class, 'export'])
      ->name('financial-reports.cash-flow.export');

    // Custom Reports
    Route::resource('custom-reports', CustomReportsController::class);
    Route::post('custom-reports/generate', [CustomReportsController::class, 'generate'])
      ->name('financial-reports.custom.generate');
  });
});
