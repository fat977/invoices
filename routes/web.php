<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Invoices\InvoiceArchivedController;
use App\Http\Controllers\Invoices\InvoiceAttachementController;
use App\Http\Controllers\Invoices\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Reports\CustomerReportController;
use App\Http\Controllers\Reports\InvoiceReportController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register'=>false]);

//sections
Route::resource('/sections',SectionController::class);

//products
Route::resource('/products',ProductController::class);

Route::group(['prefix'=>'invoices'],function(){
    Route::get('invoices_report',[InvoiceReportController::class,'index'])->name('reports.invoice');
    Route::post('search_invoices',[InvoiceReportController::class,'search'])->name('reports.search_invoice');
    
    Route::get('customers_report', [CustomerReportController::class,'index'])->name('reports.customer');
    Route::post('search_customers', [CustomerReportController::class,'search'])->name('reports.search_customer');
    
    //invoices
    Route::resource('/invoices',InvoiceController::class);
    
    //notifications
    Route::get('read_all',[InvoiceController::class, 'MarkAsRead_all'])->name('MarkAsRead_all');
    
    // get products by section
    Route::get('/section/{id}', [InvoiceController::class, 'getProducts'])->name('products.section');
    
    Route::get('/status/{id}',[InvoiceController::class,'status'])->name('status.show');
    Route::post('/status_update/{id}',[InvoiceController::class,'updateStatus'])->name('status.update');
    
    Route::get('/print_invoice/{id}',[InvoiceController::class,'print'])->name('invoices.print');
    Route::get('export_invoices',[InvoiceController::class,'export'])->name('invoices.export');
});

//archived
Route::get('invoices/restore/{id}', [InvoiceArchivedController::class, 'restore'])->name('invoices.restore');
Route::delete('invoices/archive/{id}', [InvoiceArchivedController::class, 'archive'])->name('invoices.archive');
Route::delete('invoices/delete_permanently/{id}', [InvoiceArchivedController::class, 'deletePermanently'])->name('invoices.deletePermanently');

// attachements
Route::resource('/invoice_attachements',InvoiceAttachementController::class);
Route::get('download/{invoice_number}/{file_name}',[InvoiceAttachementController::class,'download'])->name('attachements.download');
Route::get('view/{invoice_number}/{file_name}',[InvoiceAttachementController::class,'view'])->name('attachements.view');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles',RoleController::class);
    Route::resource('users',UserController::class);
});
Route::get('/index',[HomeController::class,'index']);

