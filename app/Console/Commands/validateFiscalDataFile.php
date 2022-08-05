<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;
use \ConvertApi\ConvertApi;
use Zxing\QrReader;

class validateFiscalDataFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validar:constanciaFiscal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'valida constancia Fiscal de usuario a traves de la TV';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        date_default_timezone_set('America/Mexico_City');

        $conexion = \DB::connection('mysqlTVTest');
            $dataUser = $conexion->select("SELECT files.* FROM users_fiscal_files files
            INNER JOIN users us ON files.sap_code = us.sap_code
            WHERE files.error = 0 AND files.processed = 0 AND sap_code = 123456;");
        \DB::disconnect('mysqlTVTest');
        return $dataUser;
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
                $pdf = $parser->parseFile($PDFfile);
                $data = [];
                $textGral = $pdf->getText();
                $find = "CÉDULA DE IDENTIFICACIÓN FISCAL";
                $validaTexto = strpos($textGral, $find);
                $sap_code = $dataUser[$x]->sap_code;
                $tipo = $dataUser[$x]->person_type;
                $user_id = $dataUser[$x]->user_id;

                $arrayRegimenCode = [
                    'Régimen de Sueldos y Salarios e Ingresos Asimilados a Salarios' => 605,
                    'Régimen de Sueldos y Salarios e Ingresos Asimilados a Salari os' => 605,
                    'Régimen de Arrendamiento' => 606,
                    'Régimen de Enajenacion o Adquisicion de Bienes' => 607,
                    'Demás ingresos' => 608,
                    'Residentes en el Extranjero sin Establecimiento Permanente en Mexico' => 610,
                    'Régimen de Ingresos por Dividendos (socios y accionistas)' => 611,
                    'Régimen de las Personas Físicas con Actividades Empresariales y Profesionales' => 612,
                    'Régimen de Incorporación Fiscal' => 612,
                    'Régimen de los ingresos por intereses' => 614,
                    'Régimen de los ingresos por obtencion de premios' => 615,
                    'Sin obligaciones Fiscales' => 616,
                    'Régimen de Incorporación Fiscal' => 621,
                    'Régimen de las Actividades Empresariales con ingresos a traves de Plataformas Tecnologicas' => 625,
                    'Régimen Simplificado de Confianza' => 626,
                ];

                if ($validaTexto === false) {
                    $conexion = \DB::connection('mysqlTVTest');
                        $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'El PDF del usuario no corresponde al SAT' WHERE sap_code = $sap_code");
                    \DB::disconnect('mysqlTVTest');
                    $return = 'El PDF del usuario no corresponde al SAT';
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
                $result->saveFiles(public_path('extraido/QR.jpg'));
                $qrcode = new QrReader(public_path('extraido/QR.jpg'));
                $text = $qrcode->text();
                $urlQR = explode('validadorqr.jsf', trim($text));
                
                $origenSAT = false;
                $RFCfinal = false;

                if (trim($urlQR[0]) == trim('https://siat.sat.gob.mx/app/qr/faces/pages/mobile/')) { 
                    $origenSAT = true;
                }
                
                if($origenSAT == true){
                    $rfcQR = explode('_', trim($urlQR[1]));
                    if(trim($rfcQR[1]) == trim($data['RFC'])) {
                        $RFCfinal = true;
                    }
                }

                if($origenSAT == true && $RFCfinal == true){
                    $result = ConvertApi::convert('pdf', [
                            'Url' => $text,
                            'PageRange' => '1-1',
                        ], 'web'
                    );
                    $result->saveFiles(public_path('extraido/PDF.pdf'));
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf = $parser->parseFile(public_path('extraido/PDF.pdf'));
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
            
                        $return = "PDF procesado, usuario: $sap_code";
                    }
                    else{
                        $conexion = \DB::connection('mysqlTVTest');
                            $response = $conexion->update("UPDATE users_fiscal_files SET processed = 1 WHERE sap_code = $sap_code");
                        \DB::disconnect('mysqlTVTest');
                        $return = "no existe en users: $sap_code";
                    }
                }
                else{
                    $conexion = \DB::connection('mysqlTVTest');
                        $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'QR de constancia erroneo' WHERE  sap_code = $sap_code");
                    \DB::disconnect('mysqlTVTest');
                    $return = 'QR de constancia erroneo';
                }
            }
            else{
                $conexion = \DB::connection('mysqlTVTest');
                    $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'Formato de constancia incorrecto' WHERE sap_code = $sap_code");
                \DB::disconnect('mysqlTVTest');
                $return = "Formato de constancia incorrecto: $sap_code";
            }
            $logExec = "[" . date('Y-m-d H:i:s') . "] " . $return . "\t";
            Storage::append("logValidaPDFFiscal.txt", $logExec);
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
                $pdf = $parser->parseFile($PDFfile);
                $data = [];
                $textGral = $pdf->getText();
                $find = "CÉDULA DE IDENTIFICACIÓN FISCAL";
                $validaTexto = strpos($textGral, $find);
                $sap_code = $dataUser[$x]->sap_code;
                $tipo = $dataUser[$x]->person_type;
                $user_id = $dataUser[$x]->user_id;

                $arrayRegimenCode = [
                    'Régimen de Sueldos y Salarios e Ingresos Asimilados a Salarios' => 605,
                    'Régimen de Sueldos y Salarios e Ingresos Asimilados a Salari os' => 605,
                    'Régimen de Arrendamiento' => 606,
                    'Régimen de Enajenacion o Adquisicion de Bienes' => 607,
                    'Demás ingresos' => 608,
                    'Residentes en el Extranjero sin Establecimiento Permanente en Mexico' => 610,
                    'Régimen de Ingresos por Dividendos (socios y accionistas)' => 611,
                    'Régimen de las Personas Físicas con Actividades Empresariales y Profesionales' => 612,
                    'Régimen de Incorporación Fiscal' => 612,
                    'Régimen de los ingresos por intereses' => 614,
                    'Régimen de los ingresos por obtencion de premios' => 615,
                    'Sin obligaciones Fiscales' => 616,
                    'Régimen de Incorporación Fiscal' => 621,
                    'Régimen de las Actividades Empresariales con ingresos a traves de Plataformas Tecnologicas' => 625,
                    'Régimen Simplificado de Confianza' => 626,
                ];

                if ($validaTexto === false) {
                    $conexion = \DB::connection('mysqlTVTest');
                        $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'El PDF del usuario no corresponde al SAT' WHERE sap_code = $sap_code");
                    \DB::disconnect('mysqlTVTest');
                    $return = 'El PDF del usuario no corresponde al SAT';
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
                $result->saveFiles(public_path('extraido/QR.jpg'));
                $qrcode = new QrReader(public_path('extraido/QR.jpg'));
                $text = $qrcode->text();
                $urlQR = explode('validadorqr.jsf', trim($text));
                
                $origenSAT = false;
                $RFCfinal = false;

                if (trim($urlQR[0]) == trim('https://siat.sat.gob.mx/app/qr/faces/pages/mobile/')) { 
                    $origenSAT = true;
                }
                
                if($origenSAT == true){
                    $rfcQR = explode('_', trim($urlQR[1]));
                    if(trim($rfcQR[1]) == trim($data['RFC'])) {
                        $RFCfinal = true;
                    }
                }

                if($origenSAT == true && $RFCfinal == true){
                    $result = ConvertApi::convert('pdf', [
                            'Url' => $text,
                            'PageRange' => '1-1',
                        ], 'web'
                    );
                    $result->saveFiles(public_path('extraido/PDF.pdf'));
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf = $parser->parseFile(public_path('extraido/PDF.pdf'));
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
            
                        $return = "PDF procesado, usuario: $sap_code";
                    }
                    else{
                        $conexion = \DB::connection('mysqlTVTest');
                            $response = $conexion->update("UPDATE users_fiscal_files SET processed = 1 WHERE sap_code = $sap_code");
                        \DB::disconnect('mysqlTVTest');
                        $return = "no existe en users: $sap_code";
                    }
                }
                else{
                    $conexion = \DB::connection('mysqlTVTest');
                        $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'QR de constancia erroneo' WHERE  sap_code = $sap_code");
                    \DB::disconnect('mysqlTVTest');
                    $return = 'QR de constancia erroneo';
                }
            }
            else{
                $conexion = \DB::connection('mysqlTVTest');
                    $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'Formato de constancia incorrecto' WHERE sap_code = $sap_code");
                \DB::disconnect('mysqlTVTest');
                $return = "Formato de constancia incorrecto: $sap_code";
            }
            $logExec = "[" . date('Y-m-d H:i:s') . "] " . $return . "\t";
            Storage::append("logValidaPDFFiscal.txt", $logExec);
        }
        else{
            $logExec = "[" . date('Y-m-d H:i:s') . "] Tipo persona: No Aplica\t";
            Storage::append("logValidaPDFFiscal.txt", $logExec);
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
}