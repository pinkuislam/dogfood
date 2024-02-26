<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes(['register' => false]);

Route::middleware(['XSS'])->group(function () {

    Route::get('/', "HomeController@RedirectToLogin");
    Route::get('switch/language/{lang}', 'LocalController@languageSwitch')->name('language.switch');

    //------------------------------- dashboard Admin--------------------------\\

    Route::group(['middleware'=>'auth','Is_Admin' , 'Is_Active'],function(){
        Route::get('dashboard/admin', "DashboardController@dashboard_admin")->name('dashboard'); 
    });

    Route::middleware(['auth', 'Is_Active'])->group(function () {
        
        Route::get('dashboard/employee', "DashboardController@dashboard_employee")->name('dashboard_employee');

        Route::get('dashboard_filter/{start_date}/{end_date}', "DashboardController@dashboard_filter");

        //-------------------------------  Reports ------------------------\\
        Route::prefix('reports')->group(function() {
            
            Route::get('report_profit', 'ReportController@report_profit')->name('report_profit');
            Route::get('report_profit/{start_date}/{end_date}/{warehouse}', 'ReportController@report_profit_filter')->name('report_profit_filter');

            Route::get('report_stock', 'ReportController@report_stock_page')->name('report_stock');
            Route::post('get_report_stock_datatable', 'ReportController@get_report_stock_datatable')->name('get_report_stock_datatable');

            Route::get('report_product', 'ReportController@report_product')->name('report_product');
            Route::post('get_report_product_datatable', 'ReportController@get_report_product_datatable')->name('get_report_product_datatable');

            Route::get('report_clients', 'ReportController@report_clients')->name('report_clients');
            Route::post('get_report_clients_datatable', 'ReportController@get_report_clients_datatable')->name('get_report_clients_datatable');

            Route::get('report_providers', 'ReportController@report_providers')->name('report_providers');
            Route::post('get_report_providers_datatable', 'ReportController@get_report_providers_datatable')->name('get_report_providers_datatable');
            
            Route::get('sale_report', 'ReportController@sale_report')->name('sale_report');
            Route::post('get_report_sales_datatable', 'ReportController@get_report_sales_datatable')->name('get_report_sales_datatable');
            Route::get('report_monthly_sale', 'ReportController@report_monthly_sale')->name('report_monthly_sale');

            Route::get('purchase_report', 'ReportController@purchase_report')->name('purchase_report');
            Route::post('get_report_Purchases_datatable', 'ReportController@get_report_Purchases_datatable')->name('get_report_Purchases_datatable');
            Route::get('report_monthly_purchase', 'ReportController@report_monthly_purchase')->name('report_monthly_purchase');

            Route::get('payment_sale', 'ReportController@payment_sale_report')->name('payment_sale');
            Route::post('get_payment_sale_reports_datatable', 'ReportController@get_payment_sale_reports_datatable')->name('get_payment_sale_reports_datatable');


            Route::get('payment_purchase', 'ReportController@payment_purchase_report')->name('payment_purchase');
            Route::post('get_payment_purchase_report_datatable', 'ReportController@get_payment_purchase_report_datatable')->name('get_payment_purchase_report_datatable');


            Route::get('payment_sale_return', 'ReportController@payment_sale_return_report')->name('payment_sale_return_report');

            Route::get('payment_purchase_return', 'ReportController@payment_purchase_return_report')->name('payment_purchase_return_report');
            
            Route::get('reports_quantity_alerts', 'ReportController@reports_quantity_alerts')->name('reports_quantity_alerts');
        });
        
        //------------------------------- products--------------------------\\
        Route::prefix('products')->group(function() {
            Route::resource('products', 'ProductsController');
            Route::post('get_product_datatable', 'ProductsController@get_product_datatable')->name('products_datatable');
            Route::get('show_product_data/{id}/{variant_id}', 'ProductsController@show_product_data');

            Route::get('products_by_warehouse/{id}', 'ProductsController@Products_by_warehouse');
            Route::get('print_labels', 'ProductsController@print_labels')->name('print_labels');
            Route::get('import_products', 'ProductsController@import_products_page');
            Route::post('import_products', 'ProductsController@import_products');

            //------------------------------- categories--------------------------\\
            Route::resource('categories', 'CategoriesController');
    
            //------------------------------- brands--------------------------\\
            Route::resource('brands', 'BrandsController');

            //------------------------------- units--------------------------\\
            Route::resource('units', 'UnitsController');
            Route::get('get_units_subbase', 'UnitsController@get_units_subbase');
            Route::get('get_sales_units', 'UnitsController@get_sales_units');
    
            //------------------------------- warehouses--------------------------\\
            Route::resource('warehouses', 'WarehousesController');
        });

        //------------------------------- adjustments--------------------------\\
        Route::resource('adjustment/adjustments', 'AdjustmentsController');


        //------------------------------- purchases --------------------------\\
        Route::prefix('purchase')->group(function() {
            Route::resource('purchases', 'PurchasesController');
            Route::post('get_purchases_datatable', 'PurchasesController@get_purchases_datatable')->name('get_purchases_datatable');

            Route::get('purchases/payments/{id}', 'PurchasesController@Get_Payments');
            Route::post('purchases/send/email', 'PurchasesController@Send_Email');
            Route::get('get_Products_by_purchase/{id}', 'PurchasesController@get_Products_by_purchase');
        });

        Route::resource('payment/purchase', 'PaymentPurchasesController');
        Route::get('payment/purchase/get_data_create/{id}', 'PaymentPurchasesController@get_data_create');
        Route::post('payment/purchase/send/email', 'PaymentPurchasesController@SendEmail');

        //------------------------------- Sales --------------------------\\
        Route::prefix('sale')->group(function() {
            Route::resource('sales', 'SalesController');
            Route::post('get_sales_datatable', 'SalesController@get_sales_datatable')->name('sales_datatable');

            Route::post('sales/send/email', 'SalesController@Send_Email');
            Route::get('sales/payments/{id}', 'SalesController@Payments_Sale');
            Route::get('get_Products_by_sale/{id}', 'SalesController@get_Products_by_sale');
        });

        //------------------------------- transfers --------------------------\\
        Route::resource('transfer/transfers', 'TransfersController');

        //------------------------------- Sales Return --------------------------\\
        Route::prefix('sales-return')->group(function() {
            Route::resource('returns_sale', 'SalesReturnController');
            Route::get('returns/sale/payment/{id}', 'SalesReturnController@Payment_Returns');

            Route::get('add_returns_sale/{id}', 'SalesReturnController@create_sell_return')->name('create_sell_return');
            Route::get('edit_returns_sale/{id}/{sale_id}', 'SalesReturnController@edit_sell_return')->name('edit_sell_return');
        });

        //------------------------------- Purchases Return --------------------------\\
        Route::prefix('purchase-return')->group(function() {
            Route::resource('returns_purchase', 'PurchasesReturnController');
            Route::post('returns/purchase/send/email', 'PurchasesReturnController@Send_Email');
            Route::get('returns/purchase/payment/{id}', 'PurchasesReturnController@Payment_Returns');

            Route::get('add_returns_purchase/{id}', 'PurchasesReturnController@create_purchase_return')->name('create_purchase_return');
            Route::get('edit_returns_purchase/{id}/{purchase_id}', 'PurchasesReturnController@edit_purchase_return')->name('edit_purchase_return');
        });

        //------------------------------- suppliers --------------------------\\

        Route::resource('products/suppliers', 'SuppliersController');
        Route::post('get_suppliers_datatable', 'SuppliersController@get_suppliers_datatable')->name('get_suppliers_datatable');

        Route::post("suppliers/delete/by_selection", "SuppliersController@delete_by_selection");
        Route::get('get_provider_debt_total/{id}', 'SuppliersController@get_provider_debt_total');
        Route::post('providers_pay_due', 'SuppliersController@providers_pay_due');

        Route::get('get_provider_debt_return_total/{id}', 'SuppliersController@get_provider_debt_return_total');
        Route::post('providers_pay_return_due', 'SuppliersController@providers_pay_return_due');


        //------------------------------- users & permissions --------------------------\\
        Route::prefix('user-management')->group(function() {
            Route::resource('users', 'UserController');
            Route::post('assignRole', 'UserController@assignRole');

            Route::resource('permissions', 'PermissionsController');
        });
        //-------------------------------  --------------------------\\

        //------------------------------- Profile --------------------------\\
        //----------------------------------------------------------------\\
        Route::put('updateProfile/{id}', 'ProfileController@updateProfile');
        Route::resource('profile', 'ProfileController');

        //-------------------------------  Print & PDF ------------------------\\

        Route::get('Sale_PDF/{id}', 'SalesController@Sale_PDF');
        Route::get('Purchase_PDF/{id}', 'PurchasesController@Purchase_pdf');
        Route::get('Payment_Purchase_PDF/{id}', 'PaymentPurchasesController@Payment_purchase_pdf');
        Route::get('payment_Sale_PDF/{id}', 'PaymentSalesController@payment_sale');
        Route::get('return_sale_pdf/{id}', 'SalesReturnController@Return_pdf');
        Route::get('return_purchase_pdf/{id}', 'PurchasesReturnController@Return_pdf');
        Route::get('payment_return_sale_pdf/{id}', 'PaymentSaleReturnsController@payment_return');
        Route::get('payment_return_purchase_pdf/{id}', 'PaymentPurchaseReturnsController@payment_return');

        //------------------------------- Settings --------------------------\\
        //----------------------------------------------------------------\\

        Route::prefix('settings')->group(function () {
            Route::resource('system_settings', 'SettingController');
            Route::resource('currency', 'CurrencyController');
            Route::resource('backup', 'BackupController');
            Route::resource('email_settings', 'EmailSettingController');

            // notifications_template
            Route::get('emails_template', 'Notifications_Template@get_emails_template');
            Route::put('update_custom_email', 'Notifications_Template@update_custom_email');
                
            // update_backup_settings
            Route::post('update_backup_settings', 'SettingController@update_backup_settings');

        });

        Route::get('GenerateBackup', 'BackupController@GenerateBackup');
        
        //------------------------------- clear_cache --------------------------\\
        Route::get("clear_cache", "SettingController@Clear_Cache");
    });
});
