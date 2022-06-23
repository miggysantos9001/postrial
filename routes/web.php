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


Route::get('/','CustomerController@index');


Route::resource('penongs-bookings','PenongsController');
Route::get('penongs-bookings/delete/{id}','PenongsController@delete');
Route::get('penongs-bookings/view-booking-details/{booking_id}/{customer_id}','PenongsController@view_booking_details');

Route::post('penongs-bookings/additional/{id}','PenongsController@additional');
Route::post('penongs-bookings/additional-customer/{id}','PenongsController@post_additional');
Route::post('/penongs-bookings/create-booking/form','PenongsController@create_booking_form');
Route::post('penongs-bookings/view-booking-details/{booking_id}/{customer_id}','PenongsController@post_booking_details');


Route::resource('/bookings','BookingController');
Route::get('bookings/delete/{id}','BookingController@delete');
Route::get('bookings/view-booking-details/{booking_id}/{customer_id}','BookingController@view_booking_details');
Route::get('bookings/delete-booking-details/{booking_id}/{customer_id}','BookingController@delete_booking_details');

Route::post('bookings/additional/{id}','BookingController@additional');
Route::post('bookings/additional-customer/{id}','BookingController@post_additional');
Route::post('/bookings/create-booking/form','BookingController@create_booking_form');
Route::post('bookings/view-booking-details/{booking_id}/{customer_id}','BookingController@post_booking_details');

Route::resource('/clients','ClientController');
Route::resource('/client-groups','ClientgroupController');

Route::resource('/customer-groups','CustomergroupController');
Route::resource('/customers','CustomerController');
Route::get('/customers/pricing/{id}','CustomerController@view_pricing');
Route::get('/customers/view-account/{id}','CustomerController@view_account');

Route::post('/customers/pricing/{id}','CustomerController@post_pricing');

Route::resource('/donations','DonationController');
Route::get('donations/delete/{id}','DonationController@delete');
Route::post('/donations/create-donations/form','DonationController@create_booking_form');

Route::resource('/expenses','ExpenseController');
Route::get('/expenses/delete-expense/{id}','ExpenseController@delete');

Route::resource('/expensetypes','ExpensetypeController');
Route::resource('/products','ProductController');

Route::resource('/orders','OrderController');
Route::get('orders/delete/{id}','OrderController@delete');
Route::get('orders/delete-item/{id}','OrderController@delete_item');
Route::patch('orders/update-item/{id}','OrderController@update_item');

Route::resource('/payments','PaymentController');
Route::get('/payments/view-list/form','PaymentController@view_list');
Route::get('/payments/delete-payment/{id}','PaymentController@delete_payments');

Route::post('/payments/store-payments/{id}','PaymentController@store_payments');
Route::patch('/payments/update-payments/{id}','PaymentController@update_payments');

Route::get('/reports/view-summary','ReportController@view_summary');
Route::get('/reports/view-sales-summary','ReportController@view_sales_summary');
Route::get('/reports/view-daily-sales-summary','ReportController@view_daily_sales_summary');
Route::get('/reports/view-customer-soa','ReportController@view_customer_soa');
Route::get('/reports/view-discrepancy-report','ReportController@view_discrepancy');
Route::get('/reports/view-unpaids','ReportController@view_unpaids');
Route::get('/reports/view-lost-kilos','ReportController@view_lost_kilos');
Route::get('/reports/view-lost-kilo-report','ReportController@view_lost_kilo_report');
Route::get('/reports/view-bank-balance','ReportController@view_bank_balance');
Route::get('/reports/view-expense','ReportController@view_expenses');
Route::get('/reports/view-order-report','ReportController@view_order_report');
Route::get('/reports/view-donation-report','ReportController@view_donation_report');
Route::get('/reports/view-customer-order-report','ReportController@view_customer_order_report');
Route::get('/reports/yearly-report-client','ReportController@yearly_report_per_client');
Route::get('/reports/yearly-report-all-client','ReportController@yearly_report_all_client');
Route::get('/reports/yearly-sales-report','ReportController@yearly_sales_report');
Route::get('/reports/monthly-expense-report','ReportController@monthly_expense_report');
Route::get('/reports/yearly-expense-report','ReportController@yearly_expense_report');
Route::get('/reports/new-report-soa','ReportController@view_report_soa');

Route::post('/reports/view-summary','ReportController@post_summary');
Route::post('/reports/view-sales-summary','ReportController@post_sales_summary');
Route::post('/reports/view-daily-sales-summary','ReportController@post_daily_sales_summary');
Route::post('/reports/view-customer-soa','ReportController@post_customer_soa');
Route::post('/reports/view-discrepancy-report','ReportController@post_discrepancy');
Route::post('/reports/view-unpaids','ReportController@post_unpaids');
Route::post('/reports/view-lost-kilos','ReportController@post_lost_kilos');
Route::post('/reports/view-lost-kilos-values/{id}','ReportController@post_lost_kilos_values');
Route::post('/reports/view-lost-kilo-report','ReportController@post_lost_kilo_report');
Route::post('/reports/view-bank-balance','ReportController@post_bank_balance');
Route::post('/reports/view-expense','ReportController@post_expenses');
Route::post('/reports/view-order-report','ReportController@post_order_report');
Route::post('/reports/view-donation-report','ReportController@post_donation_report');
Route::post('/reports/view-customer-order-report','ReportController@post_customer_order_report');
Route::post('/reports/yearly-report-client','ReportController@print_yearly_report_per_client');
Route::post('/reports/yearly-report-all-client','ReportController@print_yearly_report_all_client');
Route::post('/reports/yearly-sales-report','ReportController@print_yearly_sales_report');
Route::post('/reports/monthly-expense-report','ReportController@print_monthly_expense_report');
Route::post('/reports/yearly-expense-report','ReportController@print_yearly_expense_report');
Route::post('/reports/new-report-soa','ReportController@post_report_soa');

Route::resource('/svi-bookings','SVIBookingController');

Route::resource('/terms','TermController');

Route::resource('/users','UserController');
Route::get('/users/change-password/{id}','UserController@changepassword');
Route::patch('/users/change-password/{id}','UserController@update_changepassword');
Route::resource('/setups','SetupController');

Route::resource('/start-balance','BankBalanceController');
Auth::routes();

