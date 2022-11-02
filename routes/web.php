<?php

// use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IncorporacionWeb\IwebController;


Route::get('/', function () {
    return view('welcome');
});

## NikkenCMS
Route::get('login', 'NikkenCMS\NikkenCMSController@login');
Route::get('authLogin', 'NikkenCMS\NikkenCMSController@authLogin');
Route::get('NikkenCMS/{view}', 'NikkenCMS\NikkenCMSController@getViwe');
Route::get('NikkenCMSpro/getActions', 'NikkenCMS\NikkenCMSController@getActions');
Route::get('NikkenCMS/encripytarPass/{pass}', 'NikkenCMS\NikkenCMSController@aes_sap_encrypt');
Route::get('downloadfile', 'NikkenCMS\NikkenCMSController@downloadfile');
Route::get('downloadfileGraph', 'NikkenCMS\NikkenCMSController@downloadfileGraph');

# Material MyNIKKEN <--> NIKKEN APP
Route::match(['get','post'],'addMicroSitio', 'MyNIKKEN\MNKController@addMicroSitio')->name('addMicroSitio');
Route::match(['get','post'],'editMicrosito', 'MyNIKKEN\MNKController@editMicrosito')->name('editMicrosito');

## Admin Datos Fiscales TV
Route::get('getTextFromPDFview', 'NikkenCMS\NikkenCMSController@getTextFromPDFview')->name('getTextFromPDFview');
Route::get('getImgFromPDFview', 'NikkenCMS\NikkenCMSController@getImgFromPDFview')->name('getImgFromPDFview');
Route::get('getValidateInfoSAT', 'NikkenCMS\NikkenCMSController@getValidateInfoSAT')->name('getValidateInfoSAT');

## Facturación COLOMBIA
Route::get('facturasCol/{sap_code}', 'facturasCol\facturasColController@indexFacturaCol');
Route::get('getFacturasCol', 'facturasCol\facturasColController@getFacturasCol');
Route::get('downloadFactura', 'facturasCol\facturasColController@downloadFactura');
Route::get('encryptarCardCode/{sap_code}', 'facturasCol\facturasColController@encryptarCardCode');

## Depuracion de CI con mas de 7 días sin pago 
Route::get('IndexDepuraciones', 'depuraciones7days\dep7dayController@indexDepuraciones');
Route::get('Depuraciones', 'depuraciones7days\dep7dayController@Depuraciones');
Route::get('Depurarmas7dias', 'depuraciones7days\dep7dayController@Depurarmost7days');

## Reportes
Route::get('getReport', 'reportes\reportesController@getReport');
Route::get('apiDataFiscalPDF', 'MyNIKKEN\MNKController@apiDataFiscalPDF');

Route::get('killprocess', 'MyNIKKEN\MNKController@killprocess');


## Incorporacion Web

Route::get('pendientes_pago',[IwebController::class, 'pendientes_pago'])->name('pendientes_pago');
Route::get('pendientes_asignar',[IwebController::class, 'pendientes_asignar'])->name('pendientes_asignar');
Route::get('pendientes_contrato',[IwebController::class, 'pendientes_contrato'])->name('pendientes_contrato');
Route::get('contrato_with_file',[IwebController::class, 'contrato_with_file'])->name('contrato_with_file');
Route::get('contrato_without_file',[IwebController::class, 'contrato_without_file'])->name('contrato_without_file');
Route::POST('upload_payment',[IwebController::class, 'upload_payment'])->name('upload_payment');
Route::post('upload_documents',[IwebController::class, 'upload_documents'])->name('upload_documents');
Route::POST('save_log_pending',[IwebController::class, 'save_log_pending'])->name('save_log_pending');
Route::get('test',[IwebController::class, 'test'])->name('test');


Route::get('upload', 'FileController@index');
Route::post('store', 'FileController@store');