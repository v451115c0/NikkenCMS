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

    public function encryptarCardCode(Request $request){
        $sap_code = request()->sap_code;
        $sap_code = $this->encrypt_decrypt('encrypt', "$sap_code");
        return $sap_code;
    }

    public function getFacturasCol(){
        $sap_code_decripted = session('sap_code_decripted');
        $periodo = request()->d1;
        $periodo = explode('|', $periodo);
        $anio = $periodo[0];
        $month = $periodo[1];
        $conexion = \DB::connection('170');
            $data = $conexion->select("EXEC NKN_PMK_DocInfo 'COL', 'PROD', '$sap_code_decripted', '$anio', '$month';");
        \DB::disconnect('170');
        $data = [
            'data' => $data,
        ];
        return $data;
    }

    public function downloadFactura(Request $request){
        $client = new \GuzzleHttp\Client();
        //$response = $client->request('POST', 'https://secfevalpruebas.ptesa.com.co:8443/api/fe/v1/security/oauth/token', [
        $response = $client->request('POST', 'https://facturaelectronicavp.ptesa.com.co/api/fe/v1/security/oauth/token', [
            'form_params' => [
                'username' => 'crojas@nikkenlatam.com',
                'password' => 'P4w5W0rT',
                'grant_type' => 'password',
            ],
        ]);
        $response->getHeaderLine('x-www-form-urlencoded');
        $access_token = json_decode($response->getBody());
        $access_token = $access_token->access_token;
        //return $access_token;

        $NitOF = '830129024-3';
        $ClaveOF = 'S3cr3tC0d3';
        $NitCliente = request()->d1;
        $signature = "$NitOF" . "$ClaveOF" . "$NitCliente";
        $signature = hash('sha384', $signature);

        $folio = request()->d2;
        return $signature;

        ## lo que retorna 2541e33a41a3e25a168b71d68e398d563ea63e0e07564f167edd73f121ef5c8883a045136e35850904488c8be2aaa90e

        $client = new \GuzzleHttp\Client();
        $GetOrder = [
            "signature" => "$signature",
            "customerIdentificationNumber" => "$NitCliente",
            "documentFileRequest" => [
                "documentFileType" => "Graphical Representation",
                "documentType" => 1,
                "documentIdentification" => "$folio"
            ],
        ];
        //$response = $client->request('POST', 'https://secfevalpruebas.ptesa.com.co:8443/api/fe/v1/integration/emission/company/100071/detail/documents/files', [
        $response = $client->request('POST', 'https://facturaelectronicavp.ptesa.com.co/api/fe/v1/integration/emission/company/2516/detail/documents/files', [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
                'Accept' => '*/*',
            ],
            'json' => $GetOrder,
        ]);
        
        $pdfb64 = json_decode($response->getBody());
        $pdfb64 = $pdfb64->result->base64;
        $bin = base64_decode($pdfb64, true);
        file_put_contents('factura_nikken.pdf', $bin);
        return response()->file(public_path("factura_nikken.pdf"));
    }
}
