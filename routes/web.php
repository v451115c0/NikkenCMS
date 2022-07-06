<?php

//use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*=== NikkenCMS ===*/
Route::get('login', 'NikkenCMS\NikkenCMSController@login');
Route::get('authLogin', 'NikkenCMS\NikkenCMSController@authLogin');
Route::get('NikkenCMS/{view}', 'NikkenCMS\NikkenCMSController@getViwe');
Route::get('NikkenCMSpro/getActions', 'NikkenCMS\NikkenCMSController@getActions');
Route::get('NikkenCMS/encripytarPass/{pass}', 'NikkenCMS\NikkenCMSController@aes_sap_encrypt');

# Material MyNIKKEN <--> NIKKEN APP
#Route::match(['get','post'],'addMicroSitio', 'MyNIKKEN\MNKController@addMicroSitioGCP')->name('addMicroSitio');
Route::match(['get','post'],'addMicroSitio', 'MyNIKKEN\MNKController@addMicroSitio')->name('addMicroSitio');
Route::match(['get','post'],'editMicrosito', 'MyNIKKEN\MNKController@editMicrosito')->name('editMicrosito');

## Admin Datos Fiscales TV
Route::get('getTextFromPDFview', 'NikkenCMS\NikkenCMSController@getTextFromPDFview')->name('getTextFromPDFview');
Route::get('getImgFromPDFview', 'NikkenCMS\NikkenCMSController@getImgFromPDFview')->name('getImgFromPDFview');
Route::get('getValidateInfoSAT', 'NikkenCMS\NikkenCMSController@getValidateInfoSAT')->name('getValidateInfoSAT');

##Facturación COLOMBIA
Route::get('facturasCol/{sap_code}', 'facturasCol\facturasColController@indexFacturaCol');
Route::get('getFacturasCol', 'facturasCol\facturasColController@getFacturasCol');
Route::get('downloadFactura', 'facturasCol\facturasColController@downloadFactura');
Route::get('encryptarCardCode/{sap_code}', 'facturasCol\facturasColController@encryptarCardCode');

##Depuracion de CI con mas de 7 días sin pago 
Route::get('IndexDepuraciones', 'depuraciones7days\dep7dayController@indexDepuraciones');
Route::get('Depuraciones', 'depuraciones7days\dep7dayController@Depuraciones');
Route::get('Depurarmas7dias', 'depuraciones7days\dep7dayController@Depurarmost7days');
