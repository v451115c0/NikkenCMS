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

use Carrooi\PdfExtractor\PdfExtractor;

use File;
use Response;

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
            $conexion = \DB::connection('migracion');
                $dataCell = $conexion->select("SELECT * FROM nikkenla_incorporation.users_fiscal_update;");
            \DB::disconnect('migracion');
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
            $conexion = \DB::connection('mysqlTV');
                $dataCell = $conexion->select("SELECT * FROM users_fiscal_files WHERE ERROR = 1;");
            \DB::disconnect('mysqlTV');
            $data = [
                'data' => $dataCell,
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
            $conexion = \DB::connection('migracion');
                $response = $conexion->select("SELECT * FROM nikkenla_incorporation.users_fiscal_update WHERE id = $id");
            \DB::disconnect('migracion');
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

            $conexion = \DB::connection('migracion');
                $response = $conexion->update("UPDATE nikkenla_incorporation.users_fiscal_update SET rfc = '$rfc', person_type = '$person_type', regimen_code = '$regimen_code', regimen_description = '$regimen_description', business_name = '$business_name', name = '$name', last_name = '$last_name1', second_last_name = '$last_name2', cp = '$cp', estado = '$estado', municipio = '$municipio', colonia = '$colonia', cfdi_code = '$cfdi_code', cfdi_description = '$cfdi_description', updated_on_sql_server = '$updated_on_sql_server', updated_at = '$updated_at' WHERE id = $id");
            \DB::disconnect('migracion');
            return $response;
        }
    }
    
    public function deleteFisData($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $id = $parameters['id'];
            $conexion = \DB::connection('migracion');
                $response = $conexion->select("SELECT sap_code FROM nikkenla_incorporation.users_fiscal_update WHERE id = $id");
            \DB::disconnect('migracion');
            $sap_code = $response[0]->sap_code;
            $conexion = \DB::connection('mysqlTV');
                $response = $conexion->delete("DELETE FROM users_fiscal_files WHERE sap_code = $sap_code");
            \DB::disconnect('mysqlTV');
            $conexion = \DB::connection('migracion');
                $response = $conexion->delete("DELETE FROM nikkenla_incorporation.users_fiscal_update WHERE id = $id");
            \DB::disconnect('migracion');
            return $response;
        }
    }
    
    ###########################################
    public function getValidateInfoSAT(Request $request){
        $sap_code =  request()->sap_code;
        $test = request()->test;
        date_default_timezone_set('America/Mexico_City');

        $conexion = \DB::connection('mysqlTV');
            $dataUser = $conexion->select("SELECT files.* FROM users_fiscal_files files
            INNER JOIN users us ON files.sap_code = us.sap_code
            WHERE  files.sap_code = $sap_code;");
        \DB::disconnect('mysqlTV');
        $PersonType = $dataUser[0]->person_type;
        $PDFfile = $dataUser[0]->fiscal_file;
        $sap_code = $dataUser[0]->sap_code;

        if(trim($PersonType) == 'FISICA'){
            $x = 0;
            ## extraemos los datos de la constancia que adjunta el usuario desde la TV.
            $PDFfile = $dataUser[$x]->fiscal_file;
            $sap_code = $dataUser[$x]->sap_code;

            $formato = explode("datos-fiscales", $PDFfile);
            $formato = explode(".", $formato[1]);
            $formato = $formato[1];
            if(trim($formato) === 'pdf'){
                $data2 = [];
                
                $parser = new \Smalot\PdfParser\Parser();
                try{
                    $pdf = $parser->parseFile($PDFfile);
                } 
                catch (\Exception $e) {
                    $logExec = "[" . date('Y-m-d H:i:s') . "] Constancia no oficial o no actualizada 2022: $sap_code\t";
                    return $logExec;
                }
                catch (\Throwable  $e) {
                    $logExec = "[" . date('Y-m-d H:i:s') . "] Constancia no oficial o no actualizada 2022: $sap_code\t";
                    return $logExec;
                }
                $data = [];
                $textGral = $pdf->getText();
                
                $textGralVal = explode("\n", $textGral);
                $search_term = "CÉDULA DE IDENTIFICACIÓN FISCAL";
                $position = $this->search_array($textGralVal, $search_term);
                if(trim($position) === ''){
                    $search_term = "CÉDULA DE IDENTIFICACION FISCAL";
                    $position = $this->search_array($textGralVal, $search_term);
                }
                else if(trim($position) === ''){
                    $search_term = "CEDULA DE IDENTIFICACION FISCAL";
                    $position = $this->search_array($textGralVal, $search_term);
                }
                else if(trim($position) === ''){
                    $search_term = "CEDULA DE IDENTIFICACION FISCAL ";
                    $position = $this->search_array($textGralVal, $search_term);
                }
                else if(trim($position) === ''){
                    $search_term = "CEDULA DE IDENTIFICACION FISCAL \t";
                    $position = $this->search_array($textGralVal, $search_term);
                }
                $titulo = trim($textGralVal[$position]);
                $titulo = trim($titulo);

                $validaTexto = false;
                if($titulo == 'CÉDULA DE IDENTIFICACIÓN FISCAL' || $titulo == 'CÉDULA DE IDENTIFICACION FISCAL' || $titulo == 'CEDULA DE IDENTIFICACION FISCAL'){
                    $validaTexto = true;
                }

                if($test >= 1 && $test <= 1){
                    return $textGralVal;
                }
                else if($test >= 2 && $test <= 2){
                    return $textGral;
                }

                $sap_code = $dataUser[$x]->sap_code;
                $tipo = $dataUser[$x]->person_type;
                $user_id = $dataUser[$x]->user_id;

                $arrayRegimenCode = [
                    "Régimen General de Ley Personas Morales" => 601,
                    'Régimen de Sueldos y Salarios e Ingresos Asimilados a Salarios' => 605,
                    'Régimen de Sueldos y Salarios e Ingresos Asimilados a Salari os' => 605,
                    'Régimen de Arrendamiento' => 606,
                    'Régimen de Enajenacion o Adquisicion de Bienes' => 607,
                    'Demás ingresos' => 608,
                    'Residentes en el Extranjero sin Establecimiento Permanente en Mexico' => 610,
                    'Régimen de Ingresos por Dividendos (socios y accionistas)' => 611,
                    'Régimen de las Personas Físicas con Actividades Empresariales y Profesionales' => 612,
                    'Régimen de los demás ingresos' => 612,
                    'Régimen de Incorporación Fiscal' => 612,
                    'Régimen de los ingresos por intereses' => 614,
                    'Régimen de los ingresos por obtencion de premios' => 615,
                    'Sin obligaciones Fiscales' => 616,
                    'Régimen de Incorporación Fiscal' => 621,
                    'Régimen de las Actividades Empresariales con ingresos a traves de Plataformas Tecnologicas' => 625,
                    'Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas.' => 625,
                    'Régimen Simplificado de Confianza' => 626,
                ];

                if ($validaTexto === false) {
                    $logExec = 'Constancia no oficial o no actualizada 2022 titulo';
                    return $logExec;
                }
                else {
                    $textGral = explode("\n", $textGral);
                    
                    $data['valido'] = true;
                    $data['titulo'] = trim($textGral[1]);

                    $data['sap_code'] = $sap_code;

                    try {
                        $search_term = "RFC:";
                        $position = $this->search_array($textGral, $search_term);
                        $rfc = explode(':', trim($textGral[$position]));
                        $rfc = $this->delete_space($rfc[1], ' ');
                        $data['RFC'] = trim($rfc);
                    } 
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }

                    $data['tipo'] = $tipo;

                    
                    try {
                        $search_term = "Régimen ";
                        $position = $this->search_array($textGral, $search_term);
                        if(empty($position) || $position <= 0 || trim($position) == ''){
                            $conexion = \DB::connection('mysqlTV');
                                $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'Sin Regimen descriptor' WHERE  sap_code = $sap_code");
                            \DB::disconnect('mysqlTV');
                            $return = "Sin Regimen descriptor: $sap_code";
                            $logExec = "[" . date('Y-m-d H:i:s') . "] " . $return . "\t";
                            Storage::append("logValidaPDFFiscal.txt", $logExec);
                            return;
                        }
                        else{
                            $data['regimenDescriptor'] = trim($this->deleteNumbersSepecialChar($this->delete_space($textGral[$position], ' '), ''));
                            $data['regimen'] = $arrayRegimenCode[trim($data['regimenDescriptor'])];
                        }
                    } 
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }

                    try {
                        $search_term = "Nombre\t(s)";
                        $position = $this->search_array($textGral, $search_term);
                        if(trim($position) === ''){
                            $search_term = "Nombre (s)";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        $nombre = explode(':', trim($textGral[$position]));
                        $nombre = $this->delete_space($nombre[1], ' ');
                        $data['nombre'] = trim($nombre);
                    } 
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    
                    try{
                        $search_term = "Primer\tApellido:";
                        $position = $this->search_array($textGral, $search_term);
                        if(trim($position) === ''){
                            $search_term = "Primer Apellido";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        $apellido1 = explode(':', trim($textGral[$position]));
                        $apellido1 = $this->delete_space($apellido1[1], ' ');
                        $data['apellido1'] = trim($apellido1);
                    } 
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    
                    try{
                        $search_term = "Segundo\tApellido:";
                        $position = $this->search_array($textGral, $search_term);
                        if(trim($position) === ''){
                            $search_term = "Segundo Apellido";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        $apellido2 = explode(':', trim($textGral[$position]));
                        $apellido2 = $this->delete_space($apellido2, ' ');
                        $data['apellido2'] = trim($apellido2[1]);
                    } 
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }

                    try{
                        $search_term = "Código\tPostal";
                        $position = $this->search_array($textGral, $search_term);
                        if(trim($position) === ''){
                            $search_term = "Código Postal";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        $cp = explode(':', trim($textGral[$position]));
                        $cp = $this->delete_space($cp[1], ' ');
                        $cp = explode(' ', trim($cp));
                        $data['cp'] = trim($cp[0]);
                    } 
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }

                    try{
                        $search_term = "Nombre\tde\tlaEntidad\tFederativa";
                        $position = $this->search_array($textGral, $search_term);
                        if(trim($position) === ''){
                            $search_term = "Nombre de la Entidad Federativa";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        if(trim($position) === ''){
                            $search_term = "Nombre\tde\tla\tEntidad\tFederativa";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        if(trim($position) === ''){
                            $search_term = "Federativa:";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        $entidad = explode(':', trim($textGral[$position]));
                        $entidad = $this->delete_space($entidad[1], ' ');
                        
                        $fl = array('Entre', 'Calle');
                        $entidad = str_replace($fl, '', "$entidad");
                        $data['estado'] = trim($entidad);
                        /*return $entidad;
                        $entidad = explode(' ', trim($entidad));
                        $data['estado'] = trim($entidad[0]);*/
                    }
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer estado: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer estado: $sap_code\t";
                        return $logExec;
                    }

                    try{
                        $search_term = "Nombre\tde\tlaLocalidad";
                        $position = $this->search_array($textGral, $search_term);
                        if(trim($position) === ''){
                            $search_term = "Nombre de la Localidad";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        if(trim($position) === ''){
                            $search_term = "Nombre\tde\tla\tLocalidad";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        $entidad = explode(':', trim($textGral[$position]));
                        $entidad = $this->delete_space($entidad[2], ' ');
                        $data['municipio'] = trim($entidad);
                    }
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer municipio: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer municipio: $sap_code\t";
                        return $logExec;
                    }
                    
                    try{
                        $search_term = "Colonia:";
                        $position = $this->search_array($textGral, $search_term);
                        $colonia = explode('Colonia:', trim($textGral[$position]));
                        $colonia = $this->delete_space($colonia[1], ' ');
                        $data['colonia'] = trim($colonia);
                    } 
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }

                    $data['codCFDI'] = 'S01';
                    $data['descCFDI'] = 'SIN EFECTOS FISCALES';
                    $data['pdffile'] = $PDFfile;
                    $data['updateSQL'] = '0';
                    $data['dateReg'] = Date('Y-m-d H:i:s');
                    $data['lastUpdate'] = Date('Y-m-d H:i:s');
                    $data['user_id'] = $user_id;
                }
                $data2['pdfUSER'] = $data;

                if($test >= 3 && $test <= 3){
                    return $data2;
                }

                $insert = "INSERT INTO nikkenla_incorporation.users_fiscal_update(user_id,sap_code,rfc,person_type,regimen_code,regimen_description,business_name,name,last_name,second_last_name,cp,estado,municipio,colonia,cfdi_code,cfdi_description,fiscal_file,comments,updated_on_sql_server,existeSap,created_at,updated_at)
                VALUES ('" . $data2['pdfUSER']['user_id'] . "', '" . $data2['pdfUSER']['sap_code'] . "', '" . strtoupper($data2['pdfUSER']['RFC']) . "', '" . $data2['pdfUSER']['tipo'] . "', '" . $data2['pdfUSER']['regimen'] . "', '" . strtoupper($data2['pdfUSER']['regimenDescriptor']) . "', '', '" . strtoupper($data2['pdfUSER']['nombre']) . "', '" . strtoupper($data2['pdfUSER']['apellido1']) . "', '" . strtoupper($data2['pdfUSER']['apellido2']) . "', '" . $data2['pdfUSER']['cp'] . "', '" . $data2['pdfUSER']['estado'] . "', '" . $data2['pdfUSER']['municipio'] . "', '" . $data2['pdfUSER']['colonia'] . "', '" . $data2['pdfUSER']['codCFDI'] . "', '" . $data2['pdfUSER']['descCFDI'] . "', '" . $data2['pdfUSER']['pdffile'] . "', '', '0', '0', '" . $data2['pdfUSER']['dateReg'] . "', '" . $data2['pdfUSER']['lastUpdate'] . "')";
                
                $conexion = \DB::connection('migracion');
                    $response = $conexion->insert("$insert");
                \DB::disconnect('migracion');

                $conexion = \DB::connection('mysqlTV');
                    $response = $conexion->update("UPDATE users_fiscal_files SET processed = 1, last_error_message = NULL, error = 0 WHERE sap_code = $sap_code");
                \DB::disconnect('mysqlTV');
    
                $logExec = "PDF procesado, usuario: $sap_code";
            }
            else{
                $logExec = "Formato de constancia incorrecto: $sap_code";
            }
            return $logExec;
        }
        else if(trim($PersonType) == 'MORAL'){
            $x = 0;
            ## extraemos los datos de la constancia que adjunta el usuario desde la TV.
            $PDFfile = $dataUser[$x]->fiscal_file;
            $sap_code = $dataUser[$x]->sap_code;

            $formato = explode("datos-fiscales", $PDFfile);
            $formato = explode(".", $formato[1]);
            $formato = $formato[1];
            if(trim($formato) === 'pdf'){
                $data2 = [];

                $parser = new \Smalot\PdfParser\Parser();
                try{
                    $pdf = $parser->parseFile($PDFfile);
                } 
                catch (\Exception $e) {
                    $logExec = "[" . date('Y-m-d H:i:s') . "] Constancia no oficial o no actualizada 2022: $sap_code\t";
                    return $logExec;
                }
                catch (\Throwable  $e) {
                    $logExec = "[" . date('Y-m-d H:i:s') . "] Constancia no oficial o no actualizada 2022: $sap_code\t";
                    return $logExec;
                }
                //$pdf = $parser->parseFile($PDFfile);
                $data = [];
                $textGral = $pdf->getText();

                $textGralVal = explode("\n", $textGral);
                $search_term = "CÉDULA DE IDENTIFICACIÓN FISCAL";
                $position = $this->search_array($textGralVal, $search_term);
                if(trim($position) === ''){
                    $search_term = "CÉDULA DE IDENTIFICACION FISCAL";
                    $position = $this->search_array($textGralVal, $search_term);
                }
                else if(trim($position) === ''){
                    $search_term = "CEDULA DE IDENTIFICACION FISCAL";
                    $position = $this->search_array($textGralVal, $search_term);
                }
                $titulo = trim($textGralVal[$position]);
                $titulo = trim($titulo);

                $validaTexto = false;
                if($titulo == 'CÉDULA DE IDENTIFICACIÓN FISCAL' || $titulo == 'CÉDULA DE IDENTIFICACION FISCAL' || $titulo == 'CEDULA DE IDENTIFICACION FISCAL'){
                    $validaTexto = true;
                }

                if($test >= 1 && $test <= 1){
                    return $textGralVal;
                }
                else if($test >= 2 && $test <= 2){
                    return $textGral;
                }

                $sap_code = $dataUser[$x]->sap_code;
                $tipo = $dataUser[$x]->person_type;
                $user_id = $dataUser[$x]->user_id;
                
                $arrayRegimenCode = [
                    "Régimen General de Ley Personas Morales" => 601,
                    "Personas Morales con Fines no Lucrativos" => 603,
                    "Residentes en el Extranjero sin Establecimiento Permanente en Mexico" => 610,
                    "Sin obligaciones Fiscales" => 616,
                    "Sociedades Cooperativas de Produccion que optan por diferir sus ingresos" => 620,
                    "Actividades Agricolas, Ganaderas, Silvicolas y Pesqueras" => 622,
                    "Régimen de Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras PM" => 622,
                    "Opcional para Grupos de Sociedades" => 623,
                    "Coordinados" => 624,
                    "Regimen Simplificado de Confianza" => 626,
                    'Régimen Simplificado de Confianza' => 626,
                ];

                if ($validaTexto === false) {
                    $return = 'Constancia no oficial o no actualizada 2022';
                    return $return;
                }
                else {
                    $textGral = explode("\n", $textGral);
                    
                    $data['valido'] = true;
                    $data['titulo'] = trim($textGral[1]);

                    $data['sap_code'] = $sap_code;

                    try{
                        $search_term = "RFC:";
                        $position = $this->search_array($textGral, $search_term);
                        $rfc = explode(':', trim($textGral[$position]));
                        $rfc = $this->delete_space($rfc[1], ' ');
                        $data['RFC'] = trim($rfc);
                    }
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }

                    $data['tipo'] = $tipo;

                    try{
                        $search_term = "Regímenes";
                        $position = $this->search_array($textGral, $search_term);
                        $position = (intval($position) + 2);
                        $data['regimenDescriptor'] = trim($this->deleteNumbersSepecialChar($this->delete_space($textGral[$position], ' '), ''));
                        $data['regimen'] = $arrayRegimenCode[trim($data['regimenDescriptor'])];
                    }
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }

                    try{
                        $search_term = "Denominación/Razón\tSocial:";
                        $position = $this->search_array($textGral, $search_term);
                        $nombre = explode(':', trim($textGral[$position]));
                        $nombre = $this->delete_space($nombre[1], ' ');
                        $data['nombre'] = trim($nombre);
                    }
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }

                    try{
                        $search_term = "Código\tPostal";
                        $position = $this->search_array($textGral, $search_term);
                        $cp = explode(':', trim($textGral[$position]));
                        $cp = $this->delete_space($cp[1], ' ');
                        $cp = explode(' ', trim($cp));
                        $data['cp'] = trim($cp[0]);
                    }
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }

                    try{
                        $search_term = "Código\tPostal";
                        $position = $this->search_array($textGral, $search_term);
                        if(trim($position) === ''){
                            $search_term = "Código Postal";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        $cp = explode(':', trim($textGral[$position]));
                        $cp = $this->delete_space($cp[1], ' ');
                        $cp = explode(' ', trim($cp));
                        $data['cp'] = trim($cp[0]);
                    } 
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    
                    try{
                        $search_term = "Nombre\tde\tlaEntidad\tFederativa";
                        $position = $this->search_array($textGral, $search_term);
                        if(trim($position) === ''){
                            $search_term = "Nombre de la Entidad Federativa";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        if(trim($position) === ''){
                            $search_term = "Nombre\tde\tla\tEntidad\tFederativa";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        $entidad = explode(':', trim($textGral[$position]));
                        $entidad = $this->delete_space($entidad[1], ' ');
                        $entidad = explode(' ', trim($entidad));
                        $data['estado'] = trim($entidad[0]);
                    }
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    
                    try{
                        $search_term = "Nombre\tde\tlaColonia:";
                        $position = $this->search_array($textGral, $search_term);
                        if(trim($position) === ''){
                            $search_term = "Nombre\tde\tla\tColonia:";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        else if(trim($position) === ''){
                            $search_term = "Nombre\tde\tla\tColonia:";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        $colonia = explode('Colonia:', trim($textGral[$position]));
                        $colonia = $this->delete_space($colonia[1], ' ');
                        $data['colonia'] = trim($colonia);
                    }
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return $logExec;
                    }

                    try{
                        $search_term = "Nombre\tdel\tMunicipio\toDemarcación\tTerritorial:";
                        $position = $this->search_array($textGral, $search_term);
                        if(trim($position) === ''){
                            $search_term = "Nombre del Municipio oDemarcación Territorial:";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        else if(trim($position) === ''){
                            $search_term = "Nombre\tdel\tMunicipio\toDemarcacion\tTerritorial:";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        else if(trim($position) === ''){
                            $search_term = "Nombre\tdel\tMunicipio\to\tDemarcación\tTerritorial:";
                            $position = $this->search_array($textGralVal, $search_term);
                        }
                        $entidad = explode(':', trim($textGral[$position]));
                        $entidad = $this->delete_space($entidad[2], ' ');
                        $data['municipio'] = trim($entidad);
                    }
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer municipio: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer municipio: $sap_code\t";
                        return $logExec;
                    }

                    $data['codCFDI'] = 'S01';
                    $data['descCFDI'] = 'SIN EFECTOS FISCALES';
                    $data['pdffile'] = $PDFfile;
                    $data['updateSQL'] = '0';
                    $data['dateReg'] = Date('Y-m-d H:i:s');
                    $data['lastUpdate'] = Date('Y-m-d H:i:s');
                    $data['user_id'] = $user_id;
                }
                $data2['pdfUSER'] = $data;

                if($test >= 3 && $test <= 3){
                    return $data2;
                }

                $insert = "INSERT INTO nikkenla_incorporation.users_fiscal_update(user_id,sap_code,rfc,person_type,regimen_code,regimen_description,business_name,name,last_name,second_last_name,cp,estado,municipio,colonia,cfdi_code,cfdi_description,fiscal_file,comments,updated_on_sql_server,existeSap,created_at,updated_at)
                VALUES ('" . $data2['pdfUSER']['user_id'] . "', '" . $data2['pdfUSER']['sap_code'] . "', '" . strtoupper($data2['pdfUSER']['RFC']) . "', '" . $data2['pdfUSER']['tipo'] . "', '" . $data2['pdfUSER']['regimen'] . "', '" . strtoupper($data2['pdfUSER']['regimenDescriptor']) . "', '" . strtoupper($data2['pdfUSER']['nombre']) . "', '', '', '', '" . $data2['pdfUSER']['cp'] . "', '" . $data2['pdfUSER']['estado'] . "', '" . $data2['pdfUSER']['municipio'] . "', '" . $data2['pdfUSER']['colonia'] . "', '" . $data2['pdfUSER']['codCFDI'] . "', '" . $data2['pdfUSER']['descCFDI'] . "', '" . $data2['pdfUSER']['pdffile'] . "', '', '0', '0', '" . $data2['pdfUSER']['dateReg'] . "', '" . $data2['pdfUSER']['lastUpdate'] . "')";
                
                $conexion = \DB::connection('migracion');
                    $response = $conexion->insert("$insert");
                \DB::disconnect('migracion');

                $conexion = \DB::connection('mysqlTV');
                    $response = $conexion->update("UPDATE users_fiscal_files SET processed = 1, last_error_message = NULL WHERE sap_code = $sap_code");
                \DB::disconnect('mysqlTV');
    
                $return = "PDF procesado, usuario: $sap_code";

                $logExec = "[" . date('Y-m-d H:i:s') . "] " . $return . "\t";
            }
            else{
                $logExec = "Formato de constancia incorrecto: $sap_code";
            }
            $logExec = "[" . date('Y-m-d H:i:s') . "] " . $return . "\t";
            return $logExec;
        }
        else{
            $logExec = "[" . date('Y-m-d H:i:s') . "] Tipo persona: No Aplica: $sap_code\t";
            return $logExec;
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

    public function downloadfile(){
        $filepath = public_path('viaje_japon_Grupo_2_v_23.xlsx');
        return Response::download($filepath); 
    }

    public function downloadfileGraph(){
        $filepath = public_path('Analisis_MK_Incorporaciones_y_Sistemas_de_Agua.xlsx');
        return Response::download($filepath); 
    }
}