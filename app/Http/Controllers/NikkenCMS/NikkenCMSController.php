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
            case 'depClient':
                return $this->depClient($parameters);
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
            $conexion = \DB::connection('mysqlTVTest');
                $dataCell = $conexion->select("SELECT cell.*,  us.sap_code, CONCAT(us.name, ' ', us.last_name) AS nombre FROM users_cell_phone_update cell INNER JOIN users us ON cell.user_id = us.id");
            \DB::disconnect('mysqlTVTest');
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
            $conexion = \DB::connection('mysqlTVTest');
                $response = $conexion->select("SELECT cell.*,  us.sap_code, CONCAT(us.name, ' ', us.last_name) AS nombre FROM users_cell_phone_update cell INNER JOIN users us ON cell.user_id = us.id WHERE cell.id = $id");
            \DB::disconnect('mysqlTVTest');
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
            $conexion = \DB::connection('mysqlTVTest');
                $response = $conexion->update("UPDATE users_cell_phone_update SET area_code = '$country_code', cell_phone = '$numberCell', updated_on_sql_server = '$Update_On_SQL_server', use_as_my_principal_phone = '$Use_As_My_Principal_phone' WHERE id = $id");
            \DB::disconnect('mysqlTVTest');
            return $response;
        }
    }
    
    public function deleteDataWSTV($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $id = $parameters['id'];
            $conexion = \DB::connection('mysqlTVTest');
                $response = $conexion->delete("DELETE FROM users_cell_phone_update WHERE id = $id");
            \DB::disconnect('mysqlTVTest');
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
            $conexion = \DB::connection('mysqlTVTest');
                $response = $conexion->select("SELECT * FROM users_fiscal_update WHERE id = $id");
            \DB::disconnect('mysqlTVTest');
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

            $conexion = \DB::connection('mysqlTVTest');
                $response = $conexion->update("UPDATE users_fiscal_update SET rfc = '$rfc', person_type = '$person_type', regimen_code = '$regimen_code', regimen_description = '$regimen_description', business_name = '$business_name', name = '$name', last_name = '$last_name1', second_last_name = '$last_name2', cp = '$cp', estado = '$estado', municipio = '$municipio', colonia = '$colonia', cfdi_code = '$cfdi_code', cfdi_description = '$cfdi_description', updated_on_sql_server = '$updated_on_sql_server', updated_at = '$updated_at' WHERE id = $id");
            \DB::disconnect('mysqlTVTest');
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
        $conexion = \DB::connection('mysqlTVTest');
            $response = $conexion->select("SELECT * FROM users_fiscal_files WHERE error = 0 AND processed = 0;");
        \DB::disconnect('mysqlTVTest');
        ## extraemos los datos de la constancia que adjunta el usuario desde la TV.
        $PDFfile = $response[0]->fiscal_file;
        $data2 = [];

        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($PDFfile);
        $data = [];
        $textGral = $pdf->getText();
        $find = "CÉDULA DE IDENTIFICACIÓN FISCAL";
        $validaTexto = strpos($textGral, $find);
        $sap_code = $response[0]->sap_code;
        $tipo = $response[0]->person_type;
        $user_id = $response[0]->user_id;

        $arrayRegimenCode = [
            'Régimen de Sueldos y Salarios e Ingresos Asimilados a Salarios' => 605,
            'Régimen de Arrendamiento' => 606,
            'Regimen de Enajenacion o Adquisicion de Bienes' => 607,
            'Demás ingresos' => 608,
            'Residentes en el Extranjero sin Establecimiento Permanente en Mexico' => 610,
            'Régimen de Ingresos por Dividendos (socios y accionistas)' => 611,
            'Régimen de las Personas Físicas con Actividades Empresariales y Profesionales' => 612,
            'Ingresos por intereses' => 614,
            'Regimen de los ingresos por obtencion de premios' => 615,
            'Sin obligaciones Fiscales' => 616,
            'Incorporacion Fiscal' => 621,
            'Regimen de las Actividades Empresariales con ingresos a traves de Plataformas Tecnologicas' => 625,
            'Regimen Simplificado de Confianza' => 626,
        ];

        if ($validaTexto === false) {
            $conexion = \DB::connection('mysqlTVTest');
                $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'El PDF del usuario no corresponde al SAT' WHERE sap_code = $sap_code");
            \DB::disconnect('mysqlTVTest');
            return 'El PDF del usuario no corresponde al SAT';
        }
        else {
            $textGral = explode("\n", $textGral);
            
            $data['valido'] = true;
            $data['titulo'] = trim($textGral[1]);

            $data['sap_code'] = $sap_code;

            $search_term = "RFC:";
            $position = $this->search_array($textGral, $search_term);
            $rfc = explode(':', trim($textGral[$position]));
            $rfc = $this->delete_space($rfc[1], ' ');
            $data['RFC'] = trim($rfc);

            $data['tipo'] = $tipo;

            $search_term = "Régimen ";
            $position = $this->search_array($textGral, $search_term);
            $data['regimenDescriptor'] = trim($this->deleteNumbersSepecialChar($this->delete_space($textGral[$position], ' '), ''));
            $data['regimen'] = $arrayRegimenCode[trim($data['regimenDescriptor'])];

            $search_term = "Nombre\t(s)";
            $position = $this->search_array($textGral, $search_term);
            $nombre = explode(':', trim($textGral[$position]));
            $nombre = $this->delete_space($nombre[1], ' ');
            $data['nombre'] = trim($nombre);
            
            $search_term = "Primer\tApellido:";
            $position = $this->search_array($textGral, $search_term);
            $apellido1 = explode(':', trim($textGral[$position]));
            $apellido1 = $this->delete_space($apellido1[1], ' ');
            $data['apellido1'] = trim($apellido1);
            
            $search_term = "Segundo\tApellido:";
            $position = $this->search_array($textGral, $search_term);
            $apellido2 = explode(':', trim($textGral[$position]));
            $apellido2 = $this->delete_space($apellido2, ' ');
            $data['apellido2'] = trim($apellido2[1]);

            $search_term = "Código\tPostal";
            $position = $this->search_array($textGral, $search_term);
            $cp = explode(':', trim($textGral[$position]));
            $cp = $this->delete_space($cp[1], ' ');
            $cp = explode(' ', trim($cp));
            $data['cp'] = trim($cp[0]);

            $conexion = \DB::connection('mysqlTV');
                $response = $conexion->select("SELECT campo_uno_name AS estado, campo_dos_name AS municipio FROM states_countries WHERE CP = '" . $data['cp'] . "' LIMIT 1;");
            \DB::disconnect('mysqlTV');
            $data['estado'] = strtoupper($response[0]->estado);
            $data['municipio'] = strtoupper($response[0]->municipio);
            
            $search_term = "Colonia:";
            $position = $this->search_array($textGral, $search_term);
            $colonia = explode('Colonia:', trim($textGral[$position]));
            $colonia = $this->delete_space($colonia[1], ' ');
            $data['colonia'] = trim($colonia);

            $data['codCFDI'] = 'S01';
            $data['descCFDI'] = 'SIN EFECTOS FISCALES';
            $data['pdffile'] = $PDFfile;
            $data['updateSQL'] = '0';
            $data['dateReg'] = Date('Y-m-d H:i:s');
            $data['lastUpdate'] = Date('Y-m-d H:i:s');
            $data['user_id'] = $user_id;
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

        if (trim($urlQR[0]) == trim('https://siat.sat.gob.mx/app/qr/faces/pages/mobile/')) { 
            $origenSAT = true;
        }
        
        if($origenSAT == true){
            $rfcQR = explode('_', trim($urlQR[1]));
            if(trim($rfcQR[1]) == trim($data2['pdfUSER']['RFC'])) {
                $RFCfinal = true;
            }
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

            $conexion = \DB::connection('mysqlTVTest');
                $user = $conexion->select("SELECT count(sap_code) as total FROM users WHERE sap_code = $sap_code");
            \DB::disconnect('mysqlTVTest');
            $existe = $user[0]->total;
            if($existe > 0){
                $insert = "INSERT INTO users_fiscal_update(user_id,sap_code,rfc,person_type,regimen_code,regimen_description,business_name,name,last_name,second_last_name,cp,estado,municipio,colonia,cfdi_code,cfdi_description,fiscal_file,comments,updated_on_sql_server,existeSap,created_at,updated_at)
                VALUES ('" . $data2['pdfUSER']['user_id'] . "', '" . $data2['pdfUSER']['sap_code'] . "', '" . strtoupper($data2['pdfUSER']['RFC']) . "', '" . $data2['pdfUSER']['tipo'] . "', '" . $data2['pdfUSER']['regimen'] . "', '" . strtoupper($data2['pdfUSER']['regimenDescriptor']) . "', '', '" . strtoupper($data2['pdfUSER']['nombre']) . "', '" . strtoupper($data2['pdfUSER']['apellido1']) . "', '" . strtoupper($data2['pdfUSER']['apellido2']) . "', '" . $data2['pdfUSER']['cp'] . "', '" . $data2['pdfUSER']['estado'] . "', '" . $data2['pdfUSER']['municipio'] . "', '" . $data2['pdfUSER']['colonia'] . "', '" . $data2['pdfUSER']['codCFDI'] . "', '" . $data2['pdfUSER']['descCFDI'] . "', '" . $data2['pdfUSER']['pdffile'] . "', '', '0', '0', '" . $data2['pdfUSER']['dateReg'] . "', '" . $data2['pdfUSER']['lastUpdate'] . "')";
                
                $conexion = \DB::connection('mysqlTVTest');
                    $response = $conexion->insert("$insert");
                    $response = $conexion->update("UPDATE users_fiscal_files SET processed = 1 WHERE sap_code = $sap_code");
                \DB::disconnect('mysqlTVTest');
    
                return $insert;
            }
            else{
                return 'no existe en users';
            }
        }
        else{
            $conexion = \DB::connection('mysqlTVTest');
                $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'QR de constancia erroneo' WHERE  sap_code = $sap_code");
            \DB::disconnect('mysqlTVTest');
            return 'QR de constancia erroneo';
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

    public function search_array($array, $term){
        foreach ($array AS $key => $value) {
            if (stristr($value, $term) === FALSE) {
                continue;
            } else {
                return $key;
            }
        }
        return FALSE;
    }

    public function addMicroSitio(Request $request){
        $urlAction = request()->urlAction;
        $nameNSite = request()->nameNSite;
        $URLNSite = trim(request()->URLNSite);
        $concatSap_codeNSite = (request()->concatSap_codeNSite == 'on' || trim(request()->URLNSite) != 'javascript:void(0);') ? 1: 0;
        $dateStartNSite = str_replace("T", " ", request()->dateStartNSite);
        $dateEndNSite = str_replace("T", " ", request()->dateEndNSite);
        $unlimitedNDate = request()->unlimitedNDate;
        $tagNSite = request()->tagNSite;
        $chckCol = (request()->chckCol=='on')? 1: null;
        $chckMex = (request()->chckMex=='on')? 2: null;
        $chckPer = (request()->chckPer=='on')? 3: null;
        $chckCri = (request()->chckCri=='on')? 8: null;
        $chckEcu = (request()->chckEcu=='on')? 4: null;
        $chckSlv = (request()->chckSlv=='on')? 7: null;
        $chckGtm = (request()->chckGtm=='on')? 6: null;
        $chckPan = (request()->chckPan=='on')? 5: null;
        $chckChl = (request()->chckChl=='on')? 10: null;
        $country = $chckCol . ', ' .$chckMex . ', ' .$chckPer . ', ' .$chckCri . ', ' .$chckEcu . ', ' .$chckSlv . ', ' .$chckGtm . ', ' .$chckPan . ', ' .$chckChl;
        $iconNsite = "https://micrositios.s3.us-west-1.amazonaws.com/CmsSRC/Icono-MasterDay.png";

        $onClickNSite = request()->onClickNSite;
        $allowedUsersNsite = (trim(request()->allowedUsersNsite) == '') ? 'todos' : trim(request()->allowedUsersNsite);
        $chckDIR = (request()->chckDIR == 'on') ? 'DIR': null;
        $chckEXE = (request()->chckEXE == 'on') ? 'EXE': null;
        $chckPLA = (request()->chckPLA == 'on') ? 'PLA': null;
        $chckORO = (request()->chckORO == 'on') ? 'ORO': null;
        $chckPLO = (request()->chckPLO == 'on') ? 'PLO': null;
        $chckDIA = (request()->chckDIA == 'on') ? 'DIA': null;
        $chckDRL = (request()->chckDRL == 'on') ? 'DRL': null;
        $rangos = $chckDIR . ', ' . $chckEXE . ', ' . $chckPLA . ', ' . $chckORO . ', ' . $chckPLO . ', ' . $chckDIA . ', ' . $chckDRL;
        $chckNINNEAPP = (request()->chckNINNEAPP === 'on') ? 1 : 0;
        $chckMyNIKKEN = (request()->chckMyNIKKEN === 'on') ? 1 : 0;

        /*if ($request->has('iconNsite') && request()->iconNsite) {
            $path = request()->file('iconNsite')->store(
                NikkenCMSController::S3_SLIDERS_FOLDER,
                NikkenCMSController::S3_OPTIONS
            );
            $full_path = Storage::disk('s3')->url($path);
            $iconNsite = $full_path;
        }*/
        if ($request->has('iconNsite') && request()->iconNsite) {
            $filename = request()->iconNsite->getClientOriginalName();
            $disk = \storage::disk('gcs');
            $disk->put('MyNIKKEN_src/' . $filename, file_get_contents(request()->iconNsite));
            $url = $disk->url('MyNIKKEN_src/' . $filename);
            $iconNsite = $url;
        }
        if($unlimitedNDate == 'on'){
            $dateStartNSite = "2000-01-01 01:00:00";
            $dateEndNSite = "2050-12-31 23:59:59";
        }
        
        $insert = "INSERT INTO LAT_MyNIKKEN.dbo.Buscador_Mynikken VALUES ('$nameNSite', '$tagNSite', '$URLNSite', '$dateStartNSite', '$dateEndNSite', '$iconNsite', '$concatSap_codeNSite', '$country', '$allowedUsersNsite', '$rangos', '$onClickNSite', $chckNINNEAPP, $chckMyNIKKEN, 'inserta sitio " . session('tokenUser') . ' | ' . Date('Y-m-d H:i:s') .  "');";
        
        $conexion = \DB::connection('173');
            $data = $conexion->insert($insert);
        \DB::disconnect('173');
        return ($data)? 'added': 'error';
    }

    public function getSitesFilter(){
        $conexion = \DB::connection('173');
            $data = $conexion->select("SELECT DISTINCT(Plataforma) FROM RETOS_ESPECIALES.dbo.Metricas_Nikken;");
        \DB::disconnect('173');
        return $data;
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

    public function loadDataEditSite($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $id = $parameters['idSite'];
            $conexion = \DB::connection('173');
                $data = $conexion->select("SELECT * FROM LAT_MyNIKKEN.dbo.Buscador_Mynikken WHERE ID = $id;");
            \DB::disconnect('173');
            return $data;
        }
    }

    public function editMicrosito(Request $request){
        $idNSite = request()->idNSite;
        $urlAction = request()->urlAction;
        $nameNSite = request()->nameNSite;
        $URLNSite = trim(request()->URLNSite);
        $concatSap_codeNSite = (request()->concatSap_codeNSite == 'on' || trim(request()->URLNSite) != 'javascript:void(0);') ? 1: 0;
        $dateStartNSite = str_replace("T", " ", request()->dateStartNSite);
        $dateEndNSite = str_replace("T", " ", request()->dateEndNSite);
        $unlimitedNDate = request()->unlimitedNDate;
        $tagNSite = request()->tagNSite;
        $chckCol = (request()->chckCol=='on')? 1: null;
        $chckMex = (request()->chckMex=='on')? 2: null;
        $chckPer = (request()->chckPer=='on')? 3: null;
        $chckCri = (request()->chckCri=='on')? 8: null;
        $chckEcu = (request()->chckEcu=='on')? 4: null;
        $chckSlv = (request()->chckSlv=='on')? 7: null;
        $chckGtm = (request()->chckGtm=='on')? 6: null;
        $chckPan = (request()->chckPan=='on')? 5: null;
        $chckChl = (request()->chckChl=='on')? 10: null;
        $country = $chckCol . ', ' .$chckMex . ', ' .$chckPer . ', ' .$chckCri . ', ' .$chckEcu . ', ' .$chckSlv . ', ' .$chckGtm . ', ' .$chckPan . ', ' .$chckChl;

        $iconNsite = "https://micrositios.s3.us-west-1.amazonaws.com/CmsSRC/Icono-MasterDay.png";
        $conexion = \DB::connection('173');
            $data = $conexion->select("SELECT icono FROM LAT_MyNIKKEN.dbo.Buscador_Mynikken WHERE ID = $idNSite");
        \DB::disconnect('173');
        $iconNsite = $data[0]->icono;

        $onClickNSite = request()->onClickNSite;
        $allowedUsersNsite = (trim(request()->allowedUsersNsite) == '') ? 'todos' : trim(request()->allowedUsersNsite);
        $chckDIR = (request()->chckDIR == 'on') ? 'DIR': null;
        $chckEXE = (request()->chckEXE == 'on') ? 'EXE': null;
        $chckPLA = (request()->chckPLA == 'on') ? 'PLA': null;
        $chckORO = (request()->chckORO == 'on') ? 'ORO': null;
        $chckPLO = (request()->chckPLO == 'on') ? 'PLO': null;
        $chckDIA = (request()->chckDIA == 'on') ? 'DIA': null;
        $chckDRL = (request()->chckDRL == 'on') ? 'DRL': null;
        $rangos = $chckDIR . ', ' . $chckEXE . ', ' . $chckPLA . ', ' . $chckORO . ', ' . $chckPLO . ', ' . $chckDIA . ', ' . $chckDRL;
        $chckNINNEAPP = (request()->chckNINNEAPP === 'on') ? 1 : 0;
        $chckMyNIKKEN = (request()->chckMyNIKKEN === 'on') ? 1 : 0;

        /*if ($request->has('iconNsite') && request()->iconNsite) {
            $path = request()->file('iconNsite')->store(
                NikkenCMSController::S3_SLIDERS_FOLDER,
                NikkenCMSController::S3_OPTIONS
            );
            $full_path = Storage::disk('s3')->url($path);
            $iconNsite = $full_path;
        }*/
        if ($request->has('iconNsite') && request()->iconNsite) {
            $filename = request()->iconNsite->getClientOriginalName();
            $disk = \storage::disk('gcs');
            $disk->put('MyNIKKEN_src/' . $filename, file_get_contents(request()->iconNsite));
            $url = $disk->url('MyNIKKEN_src/' . $filename);
            $iconNsite = $url;
        }
        if($unlimitedNDate == 'on'){
            $dateStartNSite = "2000-01-01 01:00:00";
            $dateEndNSite = "2050-12-31 23:59:59";
        }
        
        $insert = "UPDATE LAT_MyNIKKEN.dbo.Buscador_Mynikken SET Reto = '$nameNSite', Tag = '$tagNSite', URL = '$URLNSite', FechaInicio = '$dateStartNSite', FechaFinzalizar = '$dateEndNSite', icono = '$iconNsite', concat_sap_code = '$concatSap_codeNSite', pais = '$country', showFor = '$allowedUsersNsite', rangos = '$rangos', onclick = '$onClickNSite', NikkenApp = '$chckNINNEAPP', MyNikken = '$chckMyNIKKEN', actionUser = 'actualiza sitio: " . session('tokenUser') . ' | ' . Date('Y-m-d H:i:s') . "' WHERE ID = $idNSite";
        
        $conexion = \DB::connection('173');
            $data = $conexion->update($insert);
        \DB::disconnect('173');
        return ($data)? 'added': 'error';
    }

    public function deleteSite($parameters){
        $id = $parameters['idSite'];
        $conexion = \DB::connection('173');
            $data = $conexion->delete("DELETE FROM LAT_MyNIKKEN.dbo.Buscador_Mynikken WHERE ID = $id;");
        \DB::disconnect('173');
        return ($data) ? 1 : 0;
    }

    public function getDatattableMetricas($parameters){
        $mes = $parameters['mes'];
        $andMes = ($mes == 'todos') ? "": " AND Fecha BETWEEN '$mes-01' AND '$mes-30'";
        $plataforma = $parameters['plataforma'];
        $pais = $parameters['pais'];
        $andPais = ($pais == 'latam') ? '': " AND Pais = '$pais'";
        $rango = $parameters['rango'];
        $andRango = ($rango == 'todos') ? '': " AND Rango = '$rango'";
        $stringClave = $parameters['clave'];
        $andClave = ($stringClave == '') ? '': " AND Accion LIKE '%$stringClave%'";

        $conexion = \DB::connection('173');
            $data = $conexion->select("SELECT * FROM RETOS_ESPECIALES.dbo.Metricas_Nikken WHERE Plataforma = '$plataforma' $andMes $andPais $andRango $andClave ORDER BY Fecha DESC;");
        \DB::disconnect('173');
        $data = [
            'data' => $data,
        ];
        return $data;
    }

    public function depClient($parameters){
        $email = $parameters['email'];
        $conexion = \DB::connection('mysqlTV');
            $data = $conexion->select("SELECT count(*) AS cliente FROM users WHERE email = '$email' AND client_type = 'CLIENTE'");
        \DB::disconnect('mysqlTV');
        $existe = $data[0]->cliente;
        if(intval($existe) > 0){
            $conexion = \DB::connection('mysqlTV');
                $data = $conexion->select("SELECT id FROM users WHERE email = '$email' AND client_type = 'CLIENTE'");
                $id = $data[0]->id;
                $data = $conexion->update("UPDATE users SET email = '$email" . '_' . Date('Ymdhis') . "_cms', status = 0, locked = 1 WHERE id = '$id' AND client_type = 'CLIENTE'");
            \DB::disconnect('mysqlTV');
            if($data){
                return 'success';
            }
            else{
                return 'error';
            }
        }
        else{
            return 'empty';
        }
    }
}