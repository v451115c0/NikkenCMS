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
Route::match(['get','post'],'addMicroSitio', 'NikkenCMS\NikkenCMSController@addMicroSitio')->name('addMicroSitio');
Route::match(['get','post'],'editMicrosito', 'NikkenCMS\NikkenCMSController@editMicrosito')->name('editMicrosito');
Route::get('getTextFromPDFview', 'NikkenCMS\NikkenCMSController@getTextFromPDFview')->name('getTextFromPDFview');
Route::get('getImgFromPDFview', 'NikkenCMS\NikkenCMSController@getImgFromPDFview')->name('getImgFromPDFview');
Route::get('enviarMail', 'NikkenCMS\NikkenCMSController@contact');

##Facturación COLOMBIA
Route::get('facturasCol/{sap_code}', 'facturasCol\facturasColController@indexFacturaCol');
Route::get('getFacturasCol', 'facturasCol\facturasColController@getFacturasCol');
Route::get('downloadFactura', 'facturasCol\facturasColController@downloadFactura');
Route::get('encryptarCardCode/{sap_code}', 'facturasCol\facturasColController@encryptarCardCode');

##Depuracion de CI con mas de 7 días sin pago 
Route::get('IndexDepuraciones', 'depuraciones7days\dep7dayController@indexDepuraciones');
Route::get('Depuraciones', 'depuraciones7days\dep7dayController@Depuraciones');
Route::get('Depurarmas7dias', 'depuraciones7days\dep7dayController@Depurarmost7days');

## Google Cloud Storage
Route::post('googlebucket', function(){
    $disk = \storage::disk('gcs');
    $disk->put('MyNIKKEN_src/cmstest.txt', 'hola mundo');
    $url = $disk->url('cmstest.txt');
    return $url;
});