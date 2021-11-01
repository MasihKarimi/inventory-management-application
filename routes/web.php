<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localeSessionRedirect',
        'localizationRedirect',
        'localeViewPath'
    ]
], function () {
    Auth::routes(['register' => false]);

    Route::group(['middleware' => ['auth', 'web']], function (){
        Route::get('/', 'HomeController@index')->name('home');

        Route::group(['prefix' => 'customers'], function () {
            Route::get('/', 'CustomerController@viewCustomers')->name('customers-view');
            Route::post('/view', 'CustomerController@viewCustomersDataTable')->name('customers-view-data');
            Route::post('/register', 'CustomerController@registerNewCustomerPost')->name('customers-register-submit');
            Route::post('/update', 'CustomerController@updateCustomer')->name('customers-update');
            Route::post('/delete', 'CustomerController@deleteCustomerPost')
                ->name('customers-delete')
                ->middleware('checkRole:Admin');

            Route::post('/get-details', 'CustomerController@getCustomerInformation')->name('customer-get-details');
            Route::get('/get-balance/{id}', 'CustomerController@getCustomerBalance')->name('customer-get-balance');
        });

        Route::group(['prefix' => 'products'], function () {
            Route::get('/', 'ProductController@viewProducts')->name('products-view');
            Route::post('/view', 'ProductController@viewProductsDataTable')->name('products-view-data');
            Route::post('/register', 'ProductController@registerNewProductPost')->name('products-register-submit');
            Route::post('/update', 'ProductController@updateProduct')->name('products-update');
            //Route::post('/stock/update', 'ProductController@updateProductStock')->name('product-stock-update');
            Route::post('/delete', 'ProductController@deleteProductPost')
                ->name('products-delete')
                ->middleware('checkRole:Admin');
            Route::post('/stock-delete', 'ProductController@deleteStockPost')
                ->name('products-stock-delete')
                ->middleware('checkRole:Admin');

            Route::post('/get-details', 'ProductController@getProductInformation')->name('product-get-details');
            Route::post('/stocks/get-details', 'ProductController@getProductStockInformation')->name('product-stocks-get-details');
            Route::post('/view/{product_id}', 'ProductController@productStocksDataTable')->name('product-stocks-view-data');
            Route::get('/get-details/{id}', 'ProductController@getProductDetails')->name('products-get-details');
        });

        Route::group(['prefix' => 'purchases'], function () {
            Route::get('/', 'PurchaseController@viewPurchases')->name('purchases-view');
            Route::post('/view', 'PurchaseController@viewPurchasesDataTable')->name('purchases-view-data');
            Route::post('/register', 'PurchaseController@registerNewPurchasePost')->name('purchases-register-submit');
            Route::post('/edit', 'PurchaseController@editPurchasePost')
                ->name('purchase-edit-submit')
                ->middleware('checkRole:Admin');
            Route::post('/delete', 'PurchaseController@deletePurchasePost')
                ->name('purchases-delete')
                ->middleware('checkRole:Admin');

            Route::post('/details', 'PurchaseController@purchaseDetails')->name('purchase-details');
            Route::post('/view/{purchase_id}', 'PurchaseController@purchaseProductsDataTable')->name('purchase-products-view-data');
        });

        Route::group(['prefix' => 'invoices'], function () {
            Route::get('/', 'InvoiceController@viewInvoices')->name('invoices-view');
            Route::post('/view', 'InvoiceController@viewInvoicesDataTable')->name('invoices-view-data');
            Route::post('/register', 'InvoiceController@newInvoicePost')->name('invoices-new-submit');
            Route::post('/edit', 'InvoiceController@editInvoicePost')
                ->name('invoices-edit')
                ->middleware('checkRole:Admin');
            Route::post('/delete', 'InvoiceController@deleteInvoicePost')
                ->name('invoices-delete')
                ->middleware('checkRole:Admin');

            Route::post('/details', 'InvoiceController@invoiceDetails')->name('invoice-details');
            Route::post('/view/{invoice_id}', 'InvoiceController@invoiceItemsDataTable')->name('invoice-items-view-data');
            Route::post('/print/{invoice_id}', 'InvoiceController@invoicePrint')->name('invoice-print');
        });

        Route::group(['prefix' => 'quotations'], function () {
            Route::get('/', 'QuotationController@viewQuotations')->name('quotations-view');
            Route::post('/view', 'QuotationController@viewQuotationsDataTable')->name('quotations-view-data');
            Route::post('/register', 'QuotationController@newQuotationPost')->name('quotations-new-submit');
            Route::post('/edit', 'QuotationController@editQuotationPost')
                ->name('quotations-edit')
                ->middleware('checkRole:Admin');
            Route::post('/delete', 'QuotationController@deleteQuotationPost')
                ->name('quotations-delete')
                ->middleware('checkRole:Admin');

            Route::post('/details', 'QuotationController@quotationDetails')->name('quotation-details');
            Route::post('/detail-for-sale', 'QuotationController@quotationDetailsForSale')->name('quotation-details-for-sale');
            Route::post('/sell', 'QuotationController@sellQuotationPost')->name('quotation-sell');
            Route::post('/view/{quotation_id}', 'QuotationController@quotationItemsDataTable')->name('quotation-items-view-data');
            Route::post('/print/{quotation_id}', 'QuotationController@quotationPrint')->name('quotation-print');
        });

        Route::group(['prefix' => 'transactions'], function () {
            Route::get('/', 'TransactionController@viewTransactions')->name('transactions-view');
            Route::post('/view', 'TransactionController@viewTransactionsDataTable')->name('transactions-view-data');
            Route::post('/register', 'TransactionController@newTransactionPost')->name('transactions-new-submit');
            Route::post('/delete', 'TransactionController@deleteTransactionPost')
                ->name('transactions-delete')
                ->middleware('checkRole:Admin');

            Route::post('/view/{customer_id}', 'TransactionController@customerTransactionsDataTable')
                ->name('customer-transactions-view-data');
        });

        Route::group(['prefix' => 'expenses'], function () {
            Route::get('/', 'ExpenseController@viewExpenses')->name('expenses-view');
            Route::post('/view', 'ExpenseController@viewExpensesDataTable')->name('expenses-view-data');
            Route::post('/add', 'ExpenseController@addNewExpensePost')->name('expenses-add-submit');
            Route::post('/delete', 'ExpenseController@deleteExpensePost')
                ->name('expenses-delete')
                ->middleware('checkRole:Admin');

            Route::group(['prefix' => 'types'], function () {
                Route::post('/add', 'ExpenseController@addNewExpenseTypePost')->name('expense-types-add-submit');
                Route::post('/delete', 'ExpenseController@deleteExpenseTypePost')
                    ->name('expense-types-delete')
                    ->middleware('checkRole:Admin');
            });
        });

        Route::group(['prefix' => 'search'], function() {
            Route::get('/customer-for-invoice', 'CustomerController@customerSearch')->name('customer-search');
            Route::get('/product-for-invoice', 'ProductController@productSearch')->name('product-search');
        });

        Route::group(['prefix' => 'reports'], function () {

            Route::group(['prefix' => 'expenses'], function () {
                Route::get('/', 'ReportController@expensesView')->name('reports-expenses-view');
                Route::post('/generate', 'ReportController@expensesGenerate')
                    ->name('reports-expenses-generate');
                Route::post('/export', 'ReportController@expensesExport')
                    ->name('reports-expenses-export');
            });

            Route::group(['prefix' => 'transactions'], function () {
                Route::get('/', 'ReportController@transactionsView')->name('reports-transactions-view');
                Route::post('/generate', 'ReportController@transactionsGenerate')
                    ->name('reports-transactions-generate');
                Route::post('/export', 'ReportController@transactionsExport')
                    ->name('reports-transactions-export');
            });
        });

        Route::group(['prefix' => 'users', 'middleware' => 'checkRole:Admin'], function () {
            Route::get('/', 'UserController@viewUsers')->name('users-view');
            Route::post('/view', 'UserController@viewUsersDataTable')->name('users-view-data');
            Route::post('/add', 'UserController@addNewUserPost')->name('users-add-submit');
            Route::post('/delete', 'UserController@deleteUserPost')->name('users-delete');
        });
    });
});
