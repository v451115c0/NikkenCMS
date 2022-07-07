<?php

namespace App\Http\Controllers\NikkenCMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use App\User;
use Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;
use \ConvertApi\ConvertApi;
use Zxing\QrReader;

class NikkenCMSController extends Controller{
    //Declaramos las configuraciones de amazon s3
    const S3_SLIDERS_FOLDER = 'CmsSRC';
    const S3_OPTIONS = ['disk' => 's3', 'visibility' => 'public'];

    static function aes_sap_encrypt($encriptado){
        $String = $encriptado;
        $plaintext = $String;
        $password = '}H69 #w3Hz+64.q';
        $method = 'aes-256-cbc';
        $password = substr(hash('sha256', $password, true), 0, 32);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
        $encrypted = base64_encode(openssl_encrypt($plaintext, $method, $password, OPENSSL_RAW_DATA, $iv));
        return $encrypted;
    }

    static function aes_decrypt($Encrypt = ""){
        $password = '}H69 #w3Hz+64.q';
        $method = 'aes-256-cbc';
        $password = substr(hash('sha256', $password, true), 0, 32);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
        $decrypted = openssl_decrypt(base64_decode(base64_decode($Encrypt)), $method, $password, OPENSSL_RAW_DATA, $iv);
        
        return \Response::json($decrypted);
    }

    public function login(){
        session()->forget('tokenPass');
        session()->forget('tokenUser');
        return view('NikkenCMS.login');
    }

    public function authLogin(Request $request){
        $_token = $request->_token;
        $user = $request->user;
        $pass = $this->aes_sap_encrypt($request->pass);
        $conexion = \DB::connection('173');
            $data = $conexion->select("SELECT * FROM cmsLoginUsers WHERE User_cms = '$user' AND Password = '$pass'");
        \DB::disconnect('173');
        if(sizeof($data) >= 1){
            session(['tokenPass' => $data[0]->Password]);
            session(['tokenUser' => $data[0]->Name]);
            session(['tokenUserType' => $data[0]->Rol]);
        }
        if(empty(session('tokenPass'))){
            return 0;
        }
        else{
            return 1;
        }
    }

    public function getViwe($view){
        if(empty(session('tokenPass'))){
            return redirect('/login');
        }
        else{
            return view('NikkenCMS.' . $view);
        }
    }

    public function getActions(Request $request){
        $action = request()->action;
        $parameters = request()->parameters;
        switch($action){
            case 'top5Activos':
                return $this->top5Activos($parameters);
                break;
            case 'graphVisitas':
                return $this->graphVisitas($parameters);
                break;
            case 'businessTools':
                return $this->businessTools($parameters);
                break;
            case 'addMicroSitio':
                return $this->addMicroSitio($parameters);
                break;
            case 'getSitesFilter':
                return $this->getSitesFilter($parameters);
                break;
            case 'getDataBuscador':
                return $this->getDataBuscador();
                break;
            case 'loadDataEditSite':
                return $this->loadDataEditSite($parameters);
                break;
            case 'deleteSite':
                return $this->deleteSite($parameters);
                break;
            case 'getDatattableMetricas':
                return $this->getDatattableMetricas($parameters);
                break;
            case 'getdataWhatsapp':
                return $this->getdataWhatsapp();
                break;
            case 'loadDataWSTVuser':
                return $this->loadDataWSTVuser($parameters);
                break;
            case 'updateDataWSTV':
                return $this->updateDataWSTV($parameters);
                break;
            case 'deleteDataWSTV':
                return $this->deleteDataWSTV($parameters);
                break;
            case 'loadDataFisData':
                return $this->loadDataFisData($parameters);
                break;
            case 'updateFisData':
                return $this->updateFisData($parameters);
                break;
            case 'deleteFisData':
                return $this->deleteFisData($parameters);
                break;
            case 'get_users_fiscal_update':
                return $this->get_users_fiscal_update();
                break;
            case 'get_users_fiscal_updateError':
                return $this->get_users_fiscal_updateError();
                break;
        }
    }

    public function top5Activos($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $plataforma = $parameters['plataforma'];
            $mes = Date('Y-m');
            $conexion = \DB::connection('173');
                $data = $conexion->select("SELECT TOP 5 Associateid, count(Associateid) AS 'Acciones' FROM RETOS_ESPECIALES.dbo.Metricas_Nikken WHERE Plataforma = '$plataforma' AND Fecha > '$mes-01' GROUP BY Associateid HAVING count(Associateid)>1 ORDER BY Acciones DESC");
            \DB::disconnect('173');
            return $data;
        }
    }

    public function graphVisitas($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $plataforma = $parameters['plataforma'];
            $year = Date('Y');
            $conexion = \DB::connection('173');
                $data[1] = $conexion->select("SELECT count(distinct Associateid) AS total FROM RETOS_ESPECIALES.dbo.Metricas_Nikken WHERE Fecha > '$year-01-01' AND Fecha < '$year-01-31' and Plataforma = '$plataforma';");
                $data[2] = $conexion->select("SELECT count(distinct Associateid) AS total FROM RETOS_ESPECIALES.dbo.Metricas_Nikken WHERE Fecha > '$year-02-01' AND Fecha < '$year-02-28' and Plataforma = '$plataforma';");
                $data[3] = $conexion->select("SELECT count(distinct Associateid) AS total FROM RETOS_ESPECIALES.dbo.Metricas_Nikken WHERE Fecha > '$year-03-01' AND Fecha < '$year-03-31' and Plataforma = '$plataforma';");
                $data[4] = $conexion->select("SELECT count(distinct Associateid) AS total FROM RETOS_ESPECIALES.dbo.Metricas_Nikken WHERE Fecha > '$year-04-01' AND Fecha < '$year-04-30' and Plataforma = '$plataforma';");
                $data[5] = $conexion->select("SELECT count(distinct Associateid) AS total FROM RETOS_ESPECIALES.dbo.Metricas_Nikken WHERE Fecha > '$year-05-01' AND Fecha < '$year-05-31' and Plataforma = '$plataforma';");
                $data[6] = $conexion->select("SELECT count(distinct Associateid) AS total FROM RETOS_ESPECIALES.dbo.Metricas_Nikken WHERE Fecha > '$year-06-01' AND Fecha < '$year-06-30' and Plataforma = '$plataforma';");
                $data[7] = $conexion->select("SELECT count(distinct Associateid) AS total FROM RETOS_ESPECIALES.dbo.Metricas_Nikken WHERE Fecha > '$year-07-01' AND Fecha < '$year-07-31' and Plataforma = '$plataforma';");
                $data[8] = $conexion->select("SELECT count(distinct Associateid) AS total FROM RETOS_ESPECIALES.dbo.Metricas_Nikken WHERE Fecha > '$year-08-01' AND Fecha < '$year-08-31' and Plataforma = '$plataforma';");
                $data[9] = $conexion->select("SELECT count(distinct Associateid) AS total FROM RETOS_ESPECIALES.dbo.Metricas_Nikken WHERE Fecha > '$year-09-01' AND Fecha < '$year-09-30' and Plataforma = '$plataforma';");
                $data[10] = $conexion->select("SELECT count(distinct Associateid) AS total FROM RETOS_ESPECIALES.dbo.Metricas_Nikken WHERE Fecha > '$year-10-01' AND Fecha < '$year-10-31' and Plataforma = '$plataforma';");
                $data[11] = $conexion->select("SELECT count(distinct Associateid) AS total FROM RETOS_ESPECIALES.dbo.Metricas_Nikken WHERE Fecha > '$year-11-01' AND Fecha < '$year-11-30' and Plataforma = '$plataforma';");
                $data[12] = $conexion->select("SELECT count(distinct Associateid) AS total FROM RETOS_ESPECIALES.dbo.Metricas_Nikken WHERE Fecha > '$year-12-01' AND Fecha < '$year-12-31' and Plataforma = '$plataforma';");
            \DB::disconnect('173');
            return $data;
        }
    }

    // Actualización de número de whatsapp
    public function getdataWhatsapp(){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $conexion = \DB::connection('mysqlTV');
                $dataCell = $conexion->select("SELECT cell.*,  us.sap_code, CONCAT(us.name, ' ', us.last_name) AS nombre FROM users_cell_phone_update cell INNER JOIN users us ON cell.user_id = us.id");
            \DB::disconnect('mysqlTV');
            $data = [
                'data' => $dataCell,
            ];
            return $data;
        }
    }

    public function loadDataWSTVuser($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $id = $parameters['id'];
            $conexion = \DB::connection('mysqlTV');
                $response = $conexion->select("SELECT cell.*,  us.sap_code, CONCAT(us.name, ' ', us.last_name) AS nombre FROM users_cell_phone_update cell INNER JOIN users us ON cell.user_id = us.id WHERE cell.id = $id");
            \DB::disconnect('mysqlTV');
            return $response;
        }
    }
    
    public function updateDataWSTV($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $id = $parameters['id'];
            $country_code = $parameters['country_code'];
            $numberCell = $parameters['numberCell'];
            $Update_On_SQL_server = $parameters['Update_On_SQL_server'];
            $Use_As_My_Principal_phone = $parameters['Use_As_My_Principal_phone'];
            $conexion = \DB::connection('mysqlTV');
                $response = $conexion->update("UPDATE users_cell_phone_update SET area_code = '$country_code', cell_phone = '$numberCell', updated_on_sql_server = '$Update_On_SQL_server', use_as_my_principal_phone = '$Use_As_My_Principal_phone' WHERE id = $id");
            \DB::disconnect('mysqlTV');
            return $response;
        }
    }
    
    public function deleteDataWSTV($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $id = $parameters['id'];
            $conexion = \DB::connection('mysqlTV');
                $response = $conexion->delete("DELETE FROM users_cell_phone_update WHERE id = $id");
            \DB::disconnect('mysqlTV');
            return $response;
        }
    }

    // admin de datos fiscales
    public function get_users_fiscal_update(){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $conexion = \DB::connection('mysqlTVTest');
                $dataCell = $conexion->select("SELECT * FROM users_fiscal_update;");
            \DB::disconnect('mysqlTVTest');
            $data = [
                'data' => $dataCell,
            ];
            return $data;
        }
    }

    public function get_users_fiscal_updateError(){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $conexion = \DB::connection('mysqlTVTest');
                $dataCell = $conexion->select("SELECT * FROM users_fiscal_update WHERE sap_code = 14829503");
            \DB::disconnect('mysqlTVTest');
            $error = [];
            $prop = [];
            foreach($dataCell as $idx => $row){
                if($row->fiscal_file != null || !empty($row->fiscal_file)){
                    $file = $row->fiscal_file;
                    $extension = explode('.', $file);
                    if(empty($row->business_name) || strlen($row->business_name) <= 0){
                        if(trim($extension[3]) === 'pdf'){
                            $prop = $this->getTextFromPDF($file);
                            if($prop['valido'] === true){
                                //if(trim($row->rfc) != trim($prop['RFC']) || trim($row->name) != trim($prop['nombre']) || trim($row->last_name) != trim($prop['apellido1']) || trim($row->second_last_name) != trim($prop['apellido2']) || trim($row->cp) != trim($prop['cp'])){
                                if(trim($row->rfc) != trim($prop['RFC']) || trim($row->name) != trim($prop['nombre']) || trim($row->last_name) != trim($prop['apellido1']) || trim($row->second_last_name) != trim($prop['apellido2'])){
                                    $error[$idx] = $row;
                                }
                            }
                        }
                    }
                }
            }
            $data = [
                'data' => $error,
            ];
            return $data;
        }
    }

    public function loadDataFisData($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $id = $parameters['id'];
            $conexion = \DB::connection('mysqlTV');
                $response = $conexion->select("SELECT * FROM users_fiscal_update WHERE id = $id");
            \DB::disconnect('mysqlTV');
            return $response;
        }
    }
    
    public function updateFisData($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $id = $parameters['id'];
            $sap_code = $parameters['sap_code'];
            $rfc = $parameters['rfc'];
            $person_type = $parameters['person_type'];
            $regimen_code = $parameters['regimen_code'];
            $regimen_description = $parameters['regimen_description'];
            $business_name = $parameters['business_name'];
            $name = $parameters['name'];
            $last_name1 = $parameters['last_name1'];
            $last_name2 = $parameters['last_name2'];
            $cp = $parameters['cp'];
            $estado = $parameters['estado'];
            $municipio = $parameters['municipio'];
            $colonia = $parameters['colonia'];
            $cfdi_code = $parameters['cfdi_code'];
            $cfdi_description = $parameters['cfdi_description'];
            $updated_on_sql_server = $parameters['updated_on_sql_server'];
            $created_at = $parameters['created_at'];
            $updated_at = Date('Y-m-d H-i-s');

            $conexion = \DB::connection('mysqlTV');
                $response = $conexion->update("UPDATE users_fiscal_update SET rfc = '$rfc', person_type = '$person_type', regimen_code = '$regimen_code', regimen_description = '$regimen_description', business_name = '$business_name', name = '$name', last_name = '$last_name1', second_last_name = '$last_name2', cp = '$cp', estado = '$estado', municipio = '$municipio', colonia = '$colonia', cfdi_code = '$cfdi_code', cfdi_description = '$cfdi_description', updated_on_sql_server = '$updated_on_sql_server', updated_at = '$updated_at' WHERE id = $id");
            \DB::disconnect('mysqlTV');
            return $response;
        }
    }
    
    public function deleteFisData($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $id = $parameters['id'];
            $conexion = \DB::connection('mysqlTV');
                $response = $conexion->delete("DELETE FROM users_fiscal_update WHERE id = $id");
            \DB::disconnect('mysqlTV');
            return $response;
        }
    }

    public function getTextFromPDF($PDFfile){
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($PDFfile);
        $data = [];
        $textGral = $pdf->getText();
        $find = "CÉDULA DE IDENTIFICACIÓN FISCAL";
        $validaTexto = strpos($textGral, $find);

        if ($validaTexto === false || empty($textGral)) {
            $data['valido'] = false;
        }
        else {
            $textGral = explode("\n", $textGral);
            $data['valido'] = true;
            $data['titulo'] = trim($textGral[1]);

            $nombre = explode(':', trim($textGral[13]));
            $order   = array("\r\n", "\n", "\r", "\t");
            $replace = ' ';
            $nombre = str_replace($order, $replace, $nombre);
            $data['nombre'] = trim($nombre[1]);

            $apellido1 = explode(':', trim($textGral[14]));
            $order   = array("\r\n", "\n", "\r", "\t");
            $replace = ' ';
            $apellido1 = str_replace($order, $replace, $apellido1);
            $data['apellido1'] = trim($apellido1[1]);

            $apellido2 = explode(':', trim($textGral[15]));
            $order   = array("\r\n", "\n", "\r", "\t");
            $replace = ' ';
            $apellido2 = str_replace($order, $replace, $apellido2);
            $data['apellido2'] = trim($apellido2[1]);

            $cp = explode(':', trim($textGral[21]));
            $order   = array("\r\n", "\n", "\r", "\t");
            $replace = ' ';
            $cp = str_replace($order, $replace, $cp[1]);
            $cp = explode(' ', trim($cp));
            $data['cp'] = trim($cp[0]);

            $data['RFC'] = trim($textGral[9]);
        }
        return $data;
    }

    public function getTextFromPDFview(Request $request){
        $PDFfile = request()->file;
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($PDFfile);
        $data = [];
        $textGral = $pdf->getText();
        $find = "CÉDULA DE IDENTIFICACIÓN FISCAL";
        $validaTexto = strpos($textGral, $find);

        if ($validaTexto === false) {
            $data['valido'] = false;
        }
        else {
            $textGral = explode("\n", $textGral);
            $data['valido'] = true;
            $data['titulo'] = trim($textGral[1]);

            $nombre = explode(':', trim($textGral[13]));
            $order   = array("\r\n", "\n", "\r", "\t");
            $replace = ' ';
            $nombre = str_replace($order, $replace, $nombre);
            $data['nombre'] = trim($nombre[1]);

            $apellido1 = explode(':', trim($textGral[14]));
            $order   = array("\r\n", "\n", "\r", "\t");
            $replace = ' ';
            $apellido1 = str_replace($order, $replace, $apellido1);
            $data['apellido1'] = trim($apellido1[1]);

            $apellido2 = explode(':', trim($textGral[15]));
            $order   = array("\r\n", "\n", "\r", "\t");
            $replace = ' ';
            $apellido2 = str_replace($order, $replace, $apellido2);
            $data['apellido2'] = trim($apellido2[1]);

            $cp = explode(':', trim($textGral[21]));
            $order   = array("\r\n", "\n", "\r", "\t");
            $replace = ' ';
            $cp = str_replace($order, $replace, $cp[1]);
            $cp = explode(' ', trim($cp));
            $data['cp'] = trim($cp[0]);

            $data['RFC'] = trim($textGral[9]);
        }
        return $data;
    }

    public function getImgFromPDFview(Request $request){
        ## extraemos los datos de la constancia que adjunta el usuario desde la TV.
        $PDFfile = request()->file;
        $data2 = [];

        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($PDFfile);
        $data = [];
        $textGral = $pdf->getText();
        $find = "CÉDULA DE IDENTIFICACIÓN FISCAL";
        $validaTexto = strpos($textGral, $find);
        if ($validaTexto === false) {
            $data['valido'] = false;
        }
        else {
            $textGral = explode("\n", $textGral);
            $data['valido'] = true;
            $data['titulo'] = trim($textGral[1]);

            $nombre = explode(':', trim($textGral[13]));
            $nombre = $this->delete_space($nombre[1], ' ');
            $data['nombre'] = trim($nombre);
            
            $apellido1 = explode(':', trim($textGral[14]));
            $apellido1 = $this->delete_space($apellido1[1], ' ');
            $data['apellido1'] = trim($apellido1);
            

            $apellido2 = explode(':', trim($textGral[15]));
            $order   = array("\r\n", "\n", "\r", "\t");
            $replace = ' ';
            $apellido2 = str_replace($order, $replace, $apellido2);
            $data['apellido2'] = trim($apellido2[1]);

            $cp = explode(':', trim($textGral[21]));
            $order   = array("\r\n", "\n", "\r", "\t");
            $replace = ' ';
            $cp = str_replace($order, $replace, $cp[1]);
            $cp = explode(' ', trim($cp));
            $data['cp'] = trim($cp[0]);

            $arrayRegimenCode = [
                'Régimen de Sueldos y Salarios e Ingresos Asimilados a Salarios' => 605,
                'Arrendamiento' => 606,
                'Regimen de Enajenacion o Adquisicion de Bienes' => 607,
                'Demás ingresos' => 608,
                'Residentes en el Extranjero sin Establecimiento Permanente en Mexico' => 610,
                'Ingresos por Dividendos (socios y accionistas)' => 611,
                'Régimen de las Personas Físicas con Actividades Empresariales y Profesionales' => 612,
                'Ingresos por intereses' => 614,
                'Regimen de los ingresos por obtencion de premios' => 615,
                'Sin obligaciones Fiscales' => 616,
                'Incorporacion Fiscal' => 621,
                'Regimen de las Actividades Empresariales con ingresos a traves de Plataformas Tecnologicas' => 625,
                'Regimen Simplificado de Confianza' => 626,
            ];

            $data['RFC'] = trim($textGral[9]);
            $data['tipo'] = 'FISICA';
            $data['regimenDescriptor'] = trim($this->deleteNumbersSepecialChar($this->delete_space($textGral[36], ' '), ''));
            $data['regimen'] = $arrayRegimenCode[trim($data['regimenDescriptor'])];

            $find2 = "Régimen";
            $validaTexto2 = strpos(trim($this->deleteNumbersSepecialChar($this->delete_space($textGral[37], ' '), '')), $find2);
            if ($validaTexto != false) {
                $data['regimenDescriptor2'] = trim($this->deleteNumbersSepecialChar($this->delete_space($textGral[37], ' '), ''));
                $data['regimen2'] = $arrayRegimenCode[trim($data['regimenDescriptor2'])];
            }
        }
        return $data;
        $data2['pdfUSER'] = $data;

        ## se procesa el archivo PDF generado a partir del QR en el archivo que adjunta el usuario desde la TV
        ConvertApi::setApiSecret('x73XwF7GsGyGeK1q');
        $result = ConvertApi::convert('jpg', [
                'File' => "$PDFfile",
                'PageRange' => '1-1',
            ], 'pdf'
        );
        $result->saveFiles('extraido/QR.jpg');
        $qrcode = new QrReader('./extraido/QR.jpg');
        $text = $qrcode->text();
        $urlQR = explode('validadorqr.jsf', trim($text));
        
        $origenSAT = false;
        $RFCfinal = false;
        (trim($urlQR[0]) == trim('https://siat.sat.gob.mx/app/qr/faces/pages/mobile/')) ? $origenSAT = true : null;
        if($origenSAT == true){
            $rfcQR = explode('_', trim($urlQR[1]));
            (trim($rfcQR[1]) == trim($data2['pdfUSER']['RFC'])) ? $RFCfinal = true : null;   
        }

        if($origenSAT == true && $RFCfinal == true){
            $result = ConvertApi::convert('pdf', [
                    'Url' => $text,
                    'PageRange' => '1-1',
                ], 'web'
            );
            $result->saveFiles('./extraido/PDF.pdf');
            
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile('./extraido/PDF.pdf');
            $textGral = $pdf->getText();
            $textGral = explode("\n", $textGral);
            $data = [];
            
            $nombre = explode(':', trim($textGral[3]));
            $nombre = $this->delete_space($nombre, '');
            $data['nombre'] = trim($nombre[2]);
            
            $apellido1 = explode(':', trim($textGral[4]));
            $apellido1 = $this->delete_space($apellido1[2], '');
            $data['apellido1'] = trim($apellido1);

            $apellido2 = explode(':', trim($textGral[5]));
            $apellido2 = $this->delete_space($apellido2[2], '');
            $data['apellido2'] = trim($apellido2);

            $cp = explode(':', trim($textGral[18]));
            $cp = $this->delete_space($cp[2], '');
            $data['cp'] = trim($cp);

            $rfc = explode(':', trim($textGral[0]));
            $rfc = explode(',', trim($rfc[2]));
            $rfc = $this->delete_space($rfc[0], '');
            $data['RFC'] = trim($rfc);
            
            $data2['pdfSAT'] = $data;

            $nombre = "";
            for($x = 0; $x < strlen($this->delete_space($data2['pdfUSER']['nombre'], '')); $x++){
                $nombre .= $data2['pdfSAT']['nombre'][$x];
            }
            ($nombre === $this->delete_space($data2['pdfUSER']['nombre'], '')) ? $nombreValido = "valido": $nombreValido = 'invalido';
            
            $apellido1 = "";
            for($x = 0; $x < strlen($this->delete_space($data2['pdfUSER']['apellido1'], '')); $x++){
                $apellido1 .= $data2['pdfSAT']['apellido1'][$x];
            }
            ($apellido1 === $this->delete_space($data2['pdfUSER']['apellido1'], '')) ? $apellido1 = "valido": $apellido1 = 'invalido';
            
            $apellido2 = "";
            for($x = 0; $x < strlen($this->delete_space($data2['pdfUSER']['apellido2'], '')); $x++){
                $apellido2 .= $data2['pdfSAT']['apellido2'][$x];
            }
            ($apellido2 === $this->delete_space($data2['pdfUSER']['apellido2'], '')) ? $apellido2 = "valido": $apellido2 = 'invalido';
            
            $cp = "";
            for($x = 0; $x < strlen($this->delete_space($data2['pdfUSER']['cp'], '')); $x++){
                $cp .= $data2['pdfSAT']['cp'][$x];
            }
            ($cp === $this->delete_space($data2['pdfUSER']['cp'], '')) ? $cp = "valido": $cp = 'invalido';
            
            $RFC = "";
            for($x = 0; $x < strlen($this->delete_space($data2['pdfUSER']['RFC'], '')); $x++){
                $RFC .= $data2['pdfSAT']['RFC'][$x];
            }
            ($RFC === $this->delete_space($data2['pdfUSER']['RFC'], '')) ? $RFC = "valido": $RFC = 'invalido';

            return "INSERT INTO users_fiscal_update(user_id,sap_code,rfc,person_type,regimen_code,regimen_description,business_name,name,last_name,second_last_name,cp,estado,municipio,colonia,cfdi_code,cfdi_description,fiscal_file,comments,updated_on_sql_server,existeSap,created_at,updated_at)
            VALUES ('1954', '14829503', '" . $data2['pdfUSER']['RFC'] . "', 'FISICA', '605', 'SUELDOS Y SALARIOS E INGRESOS ASIMILADOS A SALARIOS', '', '" . $data2['pdfUSER']['nombre'] . "', '" . $data2['pdfUSER']['apellido1'] . "', '" . $data2['pdfUSER']['apellido2'] . "', '" . $data2['pdfUSER']['cp'] . "', 'ESTADO DE MÉXICO', 'NICOLÁS ROMERO', 'BENITO JUÁREZ 1A. SECCIÓN (CABECERA MUNICIPAL)', 'S01', 'SIN EFECTOS FISCALES', 'https://storage.googleapis.com/tv-store/datos-fiscales/1656438198_XAXX010101000_correcto.pdf', '', '0', '0', '2022-07-06 15:26:15', '2022-07-06 15:26:15')";
            $table = '<table border="1px" width="100%">' .
                        '<thead>' .
                            '<tr>' .
                                '<td>PDF del usuario</td>' .
                                '<td>PDF del SAT</td>' .
                                '<td>Dato Real?</td>' .
                            '</tr>' .
                        '</thead>' .
                        '<tbody>' .
                            '<tr>' .
                                '<td>' . $data2['pdfUSER']['nombre'] . '</td>' .
                                '<td>' . $data2['pdfUSER']['nombre'] . '</td>' .
                                '<td>' . $nombreValido . '</td>' .
                            '</tr>' .
                            '<tr>' .
                                '<td>' . $data2['pdfUSER']['apellido1'] . '</td>' .
                                '<td>' . $data2['pdfUSER']['apellido1'] . '</td>' .
                                '<td>' . $apellido1 . '</td>' .
                            '</tr>' .
                            '<tr>' .
                                '<td>' . $data2['pdfUSER']['apellido1'] . '</td>' .
                                '<td>' . $data2['pdfUSER']['apellido1'] . '</td>' .
                                '<td>' . $apellido2 . '</td>' .
                            '</tr>' .
                            '<tr>' .
                                '<td>' . $data2['pdfUSER']['cp'] . '</td>' .
                                '<td>' . $data2['pdfUSER']['cp'] . '</td>' .
                                '<td>' . $cp . '</td>' .
                            '</tr>' .
                            '<tr>' .
                                '<td>' . $data2['pdfUSER']['RFC'] . '</td>' .
                                '<td>' . $data2['pdfUSER']['RFC'] . '</td>' .
                                '<td>' . $RFC . '</td>' .
                            '</tr>' .
                        '</tbody>' .
                    '</table>';

            return $table;
        }
        else{
            $conexion = \DB::connection('migracion');
                $date = Date('Y-m-d H:i:s');
                $response = $conexion->insert("INSERT INTO nikkenla_incorporation.error_cfi_data (sap_code, data_error, created_at, deleted_at) VALUES($sap_code, 'URL de validación al SAT invalida', '$date', '$date');");
            \DB::disconnect('migracion');
            return "<h5>EL PDF del usuario no corresponde al SAT</h5>";
        }
    }
    
    public function getValidateInfoSAT(Request $request){
        $sap_code =  request()->sap_code;
        $conexion = \DB::connection('mysqlTV');
            $response = $conexion->select("SELECT * FROM users_fiscal_update WHERE sap_code = $sap_code");
        \DB::disconnect('mysqlTV');
        
        ## extraemos los datos de la constancia que adjunta el usuario desde la TV.
        $PDFfile = $response[0]->fiscal_file;
        $data2 = [];

        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($PDFfile);
        $data = [];
        $textGral = $pdf->getText();
        $find = "CÉDULA DE IDENTIFICACIÓN FISCAL";
        $validaTexto = strpos($textGral, $find);
        if ($validaTexto === false) {
            $data['valido'] = false;
        }
        else {
            $textGral = explode("\n", $textGral);
            $data['valido'] = true;
            $data['titulo'] = trim($textGral[1]);

            $nombre = explode(':', trim($textGral[13]));
            $nombre = $this->delete_space($nombre[1], ' ');
            $data['nombre'] = trim($nombre);
            
            $apellido1 = explode(':', trim($textGral[14]));
            $apellido1 = $this->delete_space($apellido1[1], ' ');
            $data['apellido1'] = trim($apellido1);
            

            $apellido2 = explode(':', trim($textGral[15]));
            $order   = array("\r\n", "\n", "\r", "\t");
            $replace = ' ';
            $apellido2 = str_replace($order, $replace, $apellido2);
            $data['apellido2'] = trim($apellido2[1]);

            $cp = explode(':', trim($textGral[21]));
            $order   = array("\r\n", "\n", "\r", "\t");
            $replace = ' ';
            $cp = str_replace($order, $replace, $cp[1]);
            $cp = explode(' ', trim($cp));
            $data['cp'] = trim($cp[0]);

            $data['RFC'] = trim($textGral[9]);
        }
        $data2['pdfUSER'] = $data;

        ## se procesa el archivo PDF generado a partir del QR en el archivo que adjunta el usuario desde la TV
        ConvertApi::setApiSecret('x73XwF7GsGyGeK1q');
        $result = ConvertApi::convert('jpg', [
                'File' => "$PDFfile",
                'PageRange' => '1-1',
            ], 'pdf'
        );
        $result->saveFiles('extraido/QR.jpg');
        $qrcode = new QrReader('./extraido/QR.jpg');
        $text = $qrcode->text();
        $urlQR = explode('validadorqr.jsf', trim($text));
        
        $origenSAT = false;
        $RFCfinal = false;
        (trim($urlQR[0]) == trim('https://siat.sat.gob.mx/app/qr/faces/pages/mobile/')) ? $origenSAT = true : null;
        if($origenSAT == true){
            $rfcQR = explode('_', trim($urlQR[1]));
            (trim($rfcQR[1]) == trim($data2['pdfUSER']['RFC'])) ? $RFCfinal = true : null;   
        }

        if($origenSAT == true && $RFCfinal == true){
            $result = ConvertApi::convert('pdf', [
                    'Url' => $text,
                    'PageRange' => '1-1',
                ], 'web'
            );
            $result->saveFiles('./extraido/PDF.pdf');
            
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile('./extraido/PDF.pdf');
            $textGral = $pdf->getText();
            $textGral = explode("\n", $textGral);
            $data = [];
            
            $nombre = explode(':', trim($textGral[3]));
            $nombre = $this->delete_space($nombre, '');
            $data['nombre'] = trim($nombre[2]);
            
            $apellido1 = explode(':', trim($textGral[4]));
            $apellido1 = $this->delete_space($apellido1[2], '');
            $data['apellido1'] = trim($apellido1);

            $apellido2 = explode(':', trim($textGral[5]));
            $apellido2 = $this->delete_space($apellido2[2], '');
            $data['apellido2'] = trim($apellido2);

            $cp = explode(':', trim($textGral[18]));
            $cp = $this->delete_space($cp[2], '');
            $data['cp'] = trim($cp);

            $rfc = explode(':', trim($textGral[0]));
            $rfc = explode(',', trim($rfc[2]));
            $rfc = $this->delete_space($rfc[0], '');
            $data['RFC'] = trim($rfc);
            
            $data2['pdfSAT'] = $data;

            $nombre = "";
            for($x = 0; $x < strlen($this->delete_space($data2['pdfUSER']['nombre'], '')); $x++){
                $nombre .= $data2['pdfSAT']['nombre'][$x];
            }
            ($nombre === $this->delete_space($data2['pdfUSER']['nombre'], '')) ? $nombreValido = "valido": $nombreValido = 'invalido';
            
            $apellido1 = "";
            for($x = 0; $x < strlen($this->delete_space($data2['pdfUSER']['apellido1'], '')); $x++){
                $apellido1 .= $data2['pdfSAT']['apellido1'][$x];
            }
            ($apellido1 === $this->delete_space($data2['pdfUSER']['apellido1'], '')) ? $apellido1 = "valido": $apellido1 = 'invalido';
            
            $apellido2 = "";
            for($x = 0; $x < strlen($this->delete_space($data2['pdfUSER']['apellido2'], '')); $x++){
                $apellido2 .= $data2['pdfSAT']['apellido2'][$x];
            }
            ($apellido2 === $this->delete_space($data2['pdfUSER']['apellido2'], '')) ? $apellido2 = "valido": $apellido2 = 'invalido';
            
            $cp = "";
            for($x = 0; $x < strlen($this->delete_space($data2['pdfUSER']['cp'], '')); $x++){
                $cp .= $data2['pdfSAT']['cp'][$x];
            }
            ($cp === $this->delete_space($data2['pdfUSER']['cp'], '')) ? $cp = "valido": $cp = 'invalido';
            
            $RFC = "";
            for($x = 0; $x < strlen($this->delete_space($data2['pdfUSER']['RFC'], '')); $x++){
                $RFC .= $data2['pdfSAT']['RFC'][$x];
            }
            ($RFC === $this->delete_space($data2['pdfUSER']['RFC'], '')) ? $RFC = "valido": $RFC = 'invalido';

            return $textGral;
            $table = '<table border="1px" width="100%">' .
                        '<thead>' .
                            '<tr>' .
                                '<td>PDF del usuario</td>' .
                                '<td>PDF del SAT</td>' .
                                '<td>Dato Real?</td>' .
                            '</tr>' .
                        '</thead>' .
                        '<tbody>' .
                            '<tr>' .
                                '<td>' . $data2['pdfUSER']['nombre'] . '</td>' .
                                '<td>' . $data2['pdfUSER']['nombre'] . '</td>' .
                                '<td>' . $nombreValido . '</td>' .
                            '</tr>' .
                            '<tr>' .
                                '<td>' . $data2['pdfUSER']['apellido1'] . '</td>' .
                                '<td>' . $data2['pdfUSER']['apellido1'] . '</td>' .
                                '<td>' . $apellido1 . '</td>' .
                            '</tr>' .
                            '<tr>' .
                                '<td>' . $data2['pdfUSER']['apellido1'] . '</td>' .
                                '<td>' . $data2['pdfUSER']['apellido1'] . '</td>' .
                                '<td>' . $apellido2 . '</td>' .
                            '</tr>' .
                            '<tr>' .
                                '<td>' . $data2['pdfUSER']['cp'] . '</td>' .
                                '<td>' . $data2['pdfUSER']['cp'] . '</td>' .
                                '<td>' . $cp . '</td>' .
                            '</tr>' .
                            '<tr>' .
                                '<td>' . $data2['pdfUSER']['RFC'] . '</td>' .
                                '<td>' . $data2['pdfUSER']['RFC'] . '</td>' .
                                '<td>' . $RFC . '</td>' .
                            '</tr>' .
                        '</tbody>' .
                    '</table>';

            return $table;
        }
        else{
            $conexion = \DB::connection('migracion');
                $date = Date('Y-m-d H:i:s');
                $response = $conexion->insert("INSERT INTO nikkenla_incorporation.error_cfi_data (sap_code, data_error, created_at, deleted_at) VALUES($sap_code, 'URL de validación al SAT invalida', '$date', '$date');");
            \DB::disconnect('migracion');
            return "<h5>EL PDF del usuario no corresponde al SAT</h5>";
        }
    }

    public function delete_space($string, $replace){
        $order = array("\r\n", "\n", "\r", "\t", " ");
        $string = str_replace($order, $replace, $string);
        return $string;
    }

    public function deleteNumbersSepecialChar($string, $replace){
        $string = str_ireplace( array( '\'', '"', ',' , ';', '<', '>', '/' ), $replace, $string);
        $string = preg_replace('/[0-9]+/', $replace, $string);
        return $string;
    }

    public function getDataBuscador(){
        $conexion = \DB::connection('173');
            $data = $conexion->select("SELECT * FROM LAT_MyNIKKEN.dbo.Buscador_Mynikken;");
        \DB::disconnect('173');
        $data = [
            'data' => $data,
        ];
        return $data;
    }
}