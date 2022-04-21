<?php

namespace App\Http\Controllers\facturasCol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

class facturasColController extends Controller{
    static function encrypt_decrypt($action, $string){
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = '}H69 #w3Hz+64.q';
        $secret_iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else {
            if ($action == 'decrypt') {
                $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
            }
        }
        return $output;
    }

    public function indexFacturaCol(Request $request){
        $sap_code_encrypted = request()->sap_code;
        $sap_code_decripted = $this->encrypt_decrypt('decrypt', "$sap_code_encrypted");
        session(['sap_code_decripted' => $sap_code_decripted]);
        return view('facturasCol.index');
    }

    public function getFacturasCol(){
        $sap_code_decripted = session('sap_code_decripted');
        $conexion = \DB::connection('170');
            $data = $conexion->select("EXEC NKN_PMK_DocInfo 'COL', 'TEST', '11233703', '2022', '03';");
        \DB::disconnect('170');
        $data = [
            'data' => $data,
        ];
        return $data;
    }

    public function downloadFactura(Request $request){
        $client = new \GuzzleHttp\Client();
        $response = $client->accept('application/json')->request('POST', 'https://secfevalpruebas.ptesa.com.co:8443/api/fe/v1/security/oauth/token', [
            'form_params' => [
                'username' => 'crojas@nikkenlatam.com',
                'password' => 'P4w5W0rT',
                'grant_type' => 'password',
            ]
        ]);
        $response->getHeaderLine('x-www-form-urlencoded');
        $token =  $response->getBody()->getContents();

        return $token;
        return "hola";
    }
}
