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
        $response = $client->request('POST', 'https://secfevalpruebas.ptesa.com.co:8443/api/fe/v1/security/oauth/token', [
            'form_params' => [
                'username' => 'crojas@nikkenlatam.com',
                'password' => 'P4w5W0rT',
                'grant_type' => 'password',
            ]
        ]);
        $response->getHeaderLine('x-www-form-urlencoded');
        $access_token = json_decode($response->getBody());
        $access_token = $access_token->access_token;

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://secfevalpruebas.ptesa.com.co:8443/api/fe/v1/integration/emission/company/100071/detail/documents/files', [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
            ],
            'form_params' => [
                "signature" => "d14025b5413060c5905d55e0e16062add8d5b9fe85400d17c69a0fd25fde0399a5d7bc1191bb670ffc8165b3c2318744",
                "customerIdentificationNumber" => "34041234",
                "documentFileRequest" => [
                    "documentFileType" => "Graphical Representation",
                    "documentType" => 1,
                    "documentIdentification" => "SETT002452332"
                ],
            ],
        ]);
        $response->getHeaderLine('raw');
        return $response->getBody();
        $pdfb64 = json_decode($response->getBody());

        return $pdfb64;
        return "hola";
    }
}
