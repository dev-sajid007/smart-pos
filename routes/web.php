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


Route::get('/', 'HomeController@index');
Route::get('backup', 'backupController@db_backup');



Auth::routes();

Route::group(['middleware' => ['auth', 'perm']], function(){

    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('dashboard/income_expense', 'HomeController@income_expense');

    Route::group(['middleware' => 'system_admin'], function(){
        Route::resource('packages', 'PackageController');
        Route::resource('company_packages', 'CompanyPackageController');

        Route::resource('software_payments', 'SoftwarePaymentController');
        
        Route::get('software_payments/invoice/{id}', 'SoftwarePaymentController@invoice');

    });
    Route::post('software_payments/paid', 'SoftwarePaymentController@paid');

    // <!-- sms related route -->
    Route::resource('sms-apis', 'SmsApiController');
    Route::resource('sms-settings', 'SmsSettingController');
    Route::resource('smsGroup', 'SmsGroupController');

    Route::get('sms/send', 'SmsSettingController@sendSms')->name('send.sms');
    Route::get('active-sms-api', 'SmsSettingController@getActiveSmsApi')->name('active.sms.api');

    Route::get('available-sms-balance', 'SMSController@getAvailableSmsBalance')->name('get.available.sms.balance');
    Route::get('update-available-sms-balance', 'SMSController@updateAvailablveSmsBalance')->name('update.sms.balance');
    Route::get('ajax-sms-send-by-controller', 'SMSController@sendFromHttpGuzzle')->name('ajax.sms.send');

    Route::get('ajax-sms-send-via-controller', 'SmsSendController@sendSmsViaHttpGuzzle')->name('ajax.send.sms');


    Route::get('groups/show/{id}', 'GroupController@show');
    Route::resource('groups', 'GroupController');


    Route::get('myProfile', 'ProfileController@index')->middleware('user_role');


    //<settings>
    Route::get('categories/get_json_list', 'CategoryController@get_json_list');

    Route::resource('settings/systems', 'GlobalSettingController');

    // <!-- accounta  -->
    Route::group(['prefix' => 'account'], function () {
        // <!-- setup  -->
        Route::group(['prefix' => 'setup'], function () {
            Route::resource('accounts', 'AccountController');
            Route::resource('gl-accounts', 'GlAccountController');
            Route::resource('accounts-charts', 'AccountChartController');
            Route::resource('payments-method', 'PaymentController');
            Route::resource('parties', 'PartyController');
            Route::resource('assets', 'AssetController');
            Route::resource('liabilities', 'LiabilityController');
        });

        // <!-- voucher  -->
        Route::group(['prefix' => 'voucher'], function () {
            Route::resource('payments', 'MakePaymentController');
            Route::resource('fund-transfers', 'FundTransferController');
            Route::resource('asset-purchases', 'AssetPurchaseController');
            Route::resource('liability-purchases', 'LiabilityPurchaseController');
        });

    });
    Route::get('accounts/charts/status/{id}', 'AccountChartController@status')->name('charts.status');
    Route::get('accounts/payments-method/status/{id}', 'PaymentController@status')->name('payment.status');
    Route::get('accounts/status/{id}', 'AccountController@status')->name('account.status');




    Route::group(['prefix' => 'user'], function () {
        Route::resource('users', 'UserController');
        Route::get('user-status-change/{user}', 'UserController@changeStatus')->name('users.status');
        Route::post('change-password', 'UserController@changePassword')->name('change.user.password');
        Route::resource('roles', 'RoleController');
        Route::resource('modules', 'ModuleController');
        Route::resource('permissions', 'PermissionController');
        Route::get('permission-assigns/{id}', 'RolePermissionController@permissionAssign')->name('permissions.assign');
        Route::post('permission-assigns/{id}', 'RolePermissionController@permissionAssignStore')->name('permissions.assign.store');
    });

    //    Route::post('users/role_permissions/assign_rp', 'RolePermissionController@assign_rp');
    //    Route::post('users/role_permissions/change', 'RolePermissionController@change');
    //    Route::get('users/role_permissions/get_id', 'RolePermissionController@get_id_by_role_permission');
    //    Route::resource('users/role_permissions', 'RolePermissionController');
    //    Route::resource('people/billers', 'BillerController');

    Route::group(['prefix' => 'people'], function () {
        // supplier
        Route::resource('suppliers', 'SupplierController');
        Route::get('upload-suppliers', 'SupplierController@uploadSuppliers')->name('upload-suppliers.create');
        Route::post('upload-suppliers', 'SupplierController@uploadSuppliersStore')->name('upload-suppliers.store');

        Route::get('supplier-confirm-list', 'SupplierController@supplierConfirmList')->name('supplier.confirm.list');
        Route::post('supplier-confirm-list', 'SupplierController@supplierConfirmListStore')->name('supplier-confirm-list.store');

        Route::delete('supplier-confirm-list-delete/{id}', 'SupplierController@confirmSupplierDelete')->name('confirm-list-supplier.delete');
        Route::get('supplier-confirm-list-delete-all', 'SupplierController@confirmSupplierDeleteAll')->name('confirm-list-all-supplier.delete');


        // customers
        Route::resource('customers', 'CustomerController');

        Route::get('upload-customers', 'CustomerController@uploadCustomers')->name('upload-customers.create');
        Route::post('upload-customers', 'CustomerController@uploadCustomersStore')->name('upload-customers.store');

        Route::get('customer-confirm-list', 'CustomerController@customerConfirmList')->name('customer-confirm-list.index');
        Route::post('customer-confirm-list', 'CustomerController@customerConfirmListStore')->name('customer-confirm-list.store');

        Route::delete('customer-confirm-list-delete/{id}', 'CustomerController@confirmCustomerDelete')->name('confirm-list-customers.delete');
        Route::get('customer-confirm-list-delete-all', 'CustomerController@confirmCustomerDeleteAll')->name('confirm-list-all-customers.delete');

    });


    Route::get('quotations/test', 'QuotationController@test');
    Route::get('quotations/invoice/{id}', 'QuotationController@invoice');
    Route::resource('quotations', 'QuotationController');


    //Customer Category

    Route::resource('customer-category', 'CustomerCategoryController');
    Route::resource('marketers', 'MarketerController');




    Route::get('sales/available_quantity/{id}', 'SaleController@available_quantity');
    Route::get('sales/product-available-quantity/{id}', 'SaleController@getProduct');
    Route::get('sales/delivery', 'SaleController@delivery')->name('sales.delivery');

    Route::get('sales/send-message/{sale}', 'SaleController@sendMessage')->name('sales.send-message');
    Route::resource('sales', 'SaleController');
    Route::resource('package-sales', 'PackageSaleController');
    Route::resource('curriers', 'CurrierController');

    Route::get('searchProduct', 'SaleController@searchProduct')->name('searchProduct');
    Route::get('search-product-by-customer-id/{customer_id}/warehouse/{warehouse_id?}', 'SaleController@searchProductByCustomerId')->name('search.product.by.customer.id');
    Route::get('search-holesale-product-by-customer-id/{customer_id}/warehouse/{warehouse_id?}', 'SaleController@searchHolesaleProductByCustomerId')->name('search.product.by.customer.id');
    Route::get('get-customer', 'SaleController@getCustomer')->name('get-customer');
    Route::get('get-customer-balance/{id}', 'SaleController@getCustomerBalance')->name('get.customer.balance');
    Route::get('get-supplier-balance/{id}', 'PurchaseController@getSupplierBalance')->name('get.supplier.balance');
    Route::get('get-supplier', 'SupplierController@getSupplier')->name('get-supplier');

    Route::get('customer-due-collection', 'DueController@customerDue')->name('due-collection');
    Route::post('customer-due-collection/{customer}', 'DueController@customerDueStore')->name('due-collection.store');

    Route::resource('customer-collections', 'CustomerCollectionController');
    Route::resource('due-collections', 'DueController');
    Route::PATCH('due-collections/{review}/approve', 'DueController@approve')->name('due-collections.approve');

    Route::resource('purchases', 'PurchaseController');
    Route::get('individual-purchase', 'PurchaseController@individualPurchase')->name('individual.purchase');
    Route::PATCH('purchases/{purchase}/approve', 'PurchaseController@approve')->name('purchases.approve');


    Route::resource('purchase-returns', 'PurchaseReturnController');
    Route::resource('purchase-return-receives', 'PurchaseReturnReceiveController');


    Route::get('get-supplier-invoices', 'PurchaseReturnController@getSupplierInvoices')->name('supplier.invoices');
    Route::get('get-supplier-purchased-products', 'PurchaseReturnController@getSupplierPurchasedProducts')->name('supplier.buying-products');


    Route::get('get-supplier-product/{id}', 'PurchaseController@get_supplier_product')->name('purchases.get-supplier-product');
    Route::post('complete-purchases/{id}', 'PurchaseController@completePurchases')->name('purchases.complete');

    // bulk purchase
    Route::get('bulk-purchase-create', 'BulkPurchaseController@bulkPurchaseCreate')->name('bulk.purchase.create');
    Route::post('bulk-purchase-store', 'BulkPurchaseController@bulkPurchaseStore')->name('bulk.purchase.upload.store');

    Route::get('bulk-purchase-confirm', 'BulkPurchaseController@bulkPurchaseConfirmList')->name('bulk-purchase-confirm-list');
    Route::post('bulk-purchase-confirm-store', 'BulkPurchaseController@bulkPurchaseConfirmListStore')->name('bulk.purchase.confirm.store');

    Route::delete('purchase-confirm-list-delete/{id}', 'BulkPurchaseController@confirmPurchaseDelete')->name('confirm-list-purchases.delete');
    Route::get('purchase-confirm-list-delete-all', 'BulkPurchaseController@confirmPurchaseDeleteAll')->name('confirm-list-all-purchases.delete');


    // ############################----------------- PRODUCTS ----------------- ############################ [Akash]
    Route::group(['prefix' => 'product', 'namespace' => 'Product'], function () {
        Route::resource('units', 'UnitController');
        Route::resource('product-groups', 'ProductGroupController');
        Route::resource('brands', 'BrandController');
        Route::resource('product-raks', 'ProductRakController');
        Route::resource('generics', 'GenericController');
        Route::resource('warehouses', 'WarehouseController');
        Route::resource('categories', 'CategoryController');
        Route::resource('subcategories', 'SubCategoryController');
        Route::resource('products', 'ProductController');
        Route::resource('product-packages', 'ProductPackageController');
        Route::resource('barcodes', 'BarcodeController');
        Route::resource('product-damages', 'ProductDamageController');
        Route::resource('customer-product-pricing', 'CustomerProductPricingController');

        // update serial sold status
        Route::post('serial-product','ProductController@UpdateSerial')->name('serial.update');
        // delete serial sold item
        // Route::delete('serial-product/delete','ProductController@DeleteSerial')->name('serial.delete');
        // Serial products
        Route::get('serial-products', 'ProductController@SerialProduct')->name('product.serial');
        Route::get('search-product', 'BarcodeController@searchProduct')->name('barcodes.product');
        Route::get('get-product-by-product-serial', 'ProductController@getProductBySerial')->name('product-by-serial');

        // product upload
        Route::get('upload-products', 'ProductUploadController@uploadProductView')->name('upload-products.index');
        Route::post('upload-products', 'ProductUploadController@uploadProductStore')->name('upload-products.store');
        Route::get('product-confirm-list', 'ProductUploadController@confirmList')->name('product-confirm-list.index');
        Route::post('product-confirm-list', 'ProductUploadController@confirmProductStore')->name('confirm-list-products.store');

        Route::delete('product-confirm-list-delete/{id}', 'ProductUploadController@confirmProductDelete')->name('confirm-list-products.delete');
        Route::get('product-confirm-list-delete-all', 'ProductUploadController@confirmProductDeleteAll')->name('confirm-list-all-products.delete');

        Route::get('product-prices-edit', 'ProductController@editProductPrice')->name('product.price.edit');
        Route::post('product-prices-update', 'ProductController@updateProductPrice')->name('product-price.update');
    });



    // ############################----------------- REPORTS ----------------- ############################ [Akash]
    Route::group(['prefix' => 'stock-transfer', 'namespace' => 'StockTransfer'], function () {
        Route::resource('warehouse-to-warehouses', 'WarehouseToWarehouseController');
        Route::resource('company-to-companies', 'CompanyToCompanyController');
        Route::post('company-to-company-receive/{id}', 'CompanyToCompanyController@receiveStock')->name('company-to-companies.receive');
    });


    // ############################----------------- REPORTS ----------------- ############################ [Akash]
    Route::group(['prefix' => 'reports'], function () {
        // Inventory Report
        Route::get('marketers-report', 'ReportsController@marketers_report')->name('marketers.report');

        Route::get('marketers-ledger/', 'MarketersledgerController@index')->name('marketers.ledger');
        Route::get('marketers-ledger/create/{id}', 'MarketersledgerController@create')->name('marketers.ledger.create');
        Route::post('marketers-ledger/store/', 'MarketersledgerController@store')->name('marketers.ledger.store');
        Route::get('marketers-ledger/edit/{id}', 'MarketersledgerController@edit')->name('marketers.ledger.edit');
        Route::post('marketers-ledger/update/', 'MarketersledgerController@update')->name('marketers.ledger.update');


        Route::get('serial-product-stocks', 'StockController@getProductSerials')->name('serial-product.stocks');
        Route::get('supplier-wise-stock-reports', 'StockController@report')->name('supplier.wise.stock.reports');
        Route::get('supplier-wise-stock-reports-print', 'StockController@stockPrint')->name('supplier.wise.stock.reports.print');
        Route::get('expire-products', 'ProductStockController@expireProducts')->name('expire.products');
        Route::post('product_stock/filter', 'ProductStockController@filter');
        Route::get('product_alert', 'ProductAlertController@index')->name('products.alert');
        Route::get('wastages', 'ReportsController@wastageReport')->name('wastages.report');
        Route::get('damage-report', 'DamageReportController')->name('damages.report');
        Route::get('sales', 'ReportsController@salesReport')->name('sales.report');
        Route::get('print_sales', 'ReportsController@printSales');
        Route::get('supplier-wise-sales-report', 'ReportsController@supplierWiseSalesReport')->name('supplier.wise.sales.report');
        Route::get('sale_discounts', 'SaleDiscountReportController@index')->name('supplier.wise.discount');
        Route::get('purchase', 'ReportsController@purchaseReport')->name('purchases.report');


        Route::group(['namespace' => 'Reports'], function () {
            Route::get('product-stocks', 'InventoryReportController@productStockReport')->name('product.wise.stock');
            Route::get('topsellproduct', 'InventoryReportController@topsellproduct')->name('top.sell.product');
        });

        // Financial Report
        Route::group(['namespace' => 'Reports'], function () {
            Route::get('cash-flow-reports', 'FinancialReportController@cashFlowReport')->name('cash.flow.reports');
            Route::get('customer-reports', 'FinancialReportController@customerReports')->name('customers.report');
            Route::get('supplier-reports', 'FinancialReportController@supplierReports')->name('suppliers.report');
            Route::get('receivable-due-reports', 'FinancialReportController@receivableDueReport')->name('receivable.due.report');
            Route::get('payable-due-reports', 'FinancialReportController@getPayableDueReport')->name('payable.due.report');
        });

        Route::get('daily-report', 'ReportsController@dailReport')->name('daily.report');
        Route::get('debit_credit', 'IncomeExpenseReportController@index')->name('income.expense.report');
        Route::get('ga-parties-report', 'ReportsController@gaPartiesReport')->name('ga.parities.report');
        Route::get('sale-returns', 'SaleReturnReportController@report')->name('sale.returns.report');
        Route::get('tax-report', 'ReportsController@taxReport')->name('tax.report');
        Route::get('product-wise-profit-report', 'ReportsController@productWiseProfitReport')->name('product.wise.profit.report');
        Route::get('profit_loss', 'ProfitLossReportController@index')->name('profit.loss.report');

        Route::get('date-product-reports', 'ProductStockController@date_product_reports');
        Route::get('customer_transaction', 'CustomerTransactionController');




        Route::get('print-sale-returns', 'SaleReturnReportController@printReport');

        Route::get('get_json_stock', 'ProductStockController@get_json_stock');

        Route::get('daily_quotation', 'DailyQuotationController@index');
        Route::get('daily_purchase', 'DailyPurchaseController@index');
        Route::get('daily_sales', 'DailySalesController@index');

    });


    Route::get('get-current-balance', 'BalanceController@currentBalance');

    Route::get('get-product-stock', 'ProductStockController@stockAlert');
    Route::resource('sale-distributors', 'SaleDistributorController');

    Route::get('get-all-sales', 'SaleReturnController@getAllSales');
    Route::get('get-product/{product}', 'Product\ProductController@getProduct');


    Route::get('saleable-products', 'Product\ProductController@saleableProducts');
    Route::resource('sale-returns', 'SaleReturnController');

    Route::get('get-customer-invoices', 'SaleReturnController@getCustomerInvoices')->name('customer.invoices');
    Route::get('get-customer-buying-products', 'SaleReturnController@getCustomerBuyingProducts')->name('customer.buying-products');
    Route::get('get-invoice-id', 'SaleReturnController@getInvoiceId')->name('customer.get-invoice-id');
    Route::get('get-customer-data', 'SaleReturnController@getCustomerData')->name('get-customer-data');



    // #######################     Settings   ######################
    Route::resource('companies', 'CompanyController');

    Route::get('refresh-application', 'SoftwareSettingController@junkClean')->name('refresh.application');
    Route::get('software-settings', 'SoftwareSettingController@index')->name('software-settings');
    Route::post('software-settings', 'SoftwareSettingController@update')->name('software-settings.update');

    Route::get('sdeveloper', 'DeveloperSettingController@index')->name('developer.settings');
    Route::post('sdeveloper', 'DeveloperSettingController@update')->name('developer.settings.update');

    Route::post('send-email/{id}','Backend\SendEmailController@index')->name('email.send');
    Route::post('send-email-purchase/{id}','Backend\SendEmailController@purchase')->name('email.send.purchase');

});
