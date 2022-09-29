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

        $conexion = \DB::connection('mysqlTV');
            $dataUser = $conexion->select("SELECT files.* FROM users_fiscal_files files
            INNER JOIN users us ON files.sap_code = us.sap_code
            WHERE files.error = 0 AND files.processed = 0 AND person_type != 'NO APLICA' AND files.fiscal_file IS NOT NULL ORDER BY files.sap_code DESC LIMIT 1;");
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
                    $this->updateWithError("Constancia no oficial o no actualizada 2022", $sap_code);
                    $logExec = "[" . date('Y-m-d H:i:s') . "] Constancia no oficial o no actualizada 2022: $sap_code\t";
                    return Storage::append("logValidaPDFFiscal.txt", $logExec);
                }
                catch (\Throwable  $e) {
                    $this->updateWithError("Constancia no oficial o no actualizada 2022", $sap_code);
                    $logExec = "[" . date('Y-m-d H:i:s') . "] Constancia no oficial o no actualizada 2022: $sap_code\t";
                    return Storage::append("logValidaPDFFiscal.txt", $logExec);
                }

                //$pdf = $parser->parseFile($PDFfile);
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
                    'Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas.' => 625,
                    'Régimen Simplificado de Confianza' => 626,
                ];

                if ($validaTexto === false) {
                    $conexion = \DB::connection('mysqlTV');
                        $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'Constancia no oficial o no actualizada 2022' WHERE sap_code = $sap_code");
                    \DB::disconnect('mysqlTV');
                    $return = 'Constancia no oficial o no actualizada 2022';
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
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }
                    catch (\Throwable  $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
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
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }
                    catch (\Throwable  $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }

                    try {
                        $search_term = "Nombre\t(s)";
                        $position = $this->search_array($textGral, $search_term);
                        $nombre = explode(':', trim($textGral[$position]));
                        $nombre = $this->delete_space($nombre[1], ' ');
                        $data['nombre'] = trim($nombre);
                    } 
                    catch (\Exception $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }
                    catch (\Throwable  $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }
                    
                    try{
                        $search_term = "Primer\tApellido:";
                        $position = $this->search_array($textGral, $search_term);
                        $apellido1 = explode(':', trim($textGral[$position]));
                        $apellido1 = $this->delete_space($apellido1[1], ' ');
                        $data['apellido1'] = trim($apellido1);
                    } 
                    catch (\Exception $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }
                    catch (\Throwable  $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }
                    
                    try{
                        $search_term = "Segundo\tApellido:";
                        $position = $this->search_array($textGral, $search_term);
                        $apellido2 = explode(':', trim($textGral[$position]));
                        $apellido2 = $this->delete_space($apellido2, ' ');
                        $data['apellido2'] = trim($apellido2[1]);
                    } 
                    catch (\Exception $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }
                    catch (\Throwable  $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
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
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }
                    catch (\Throwable  $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }

                    try{
                        $search_term = "Nombre\tde\tlaEntidad\tFederativa";
                        $position = $this->search_array($textGral, $search_term);
                        $entidad = explode(':', trim($textGral[$position]));
                        $entidad = $this->delete_space($entidad[1], ' ');
                        $entidad = explode(' ', trim($entidad));
                        $data['estado'] = trim($entidad[0]);
                    }
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer estado: $sap_code\t";
                        $this->updateWithError("pospuesto, error al extraer estado", $sap_code);
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer estado: $sap_code\t";
                        $this->updateWithError("pospuesto, error al extraer estado", $sap_code);
                        return $logExec;
                    }
                    
                    try{
                        $search_term = "Nombre\tde\tlaLocalidad";
                        $position = $this->search_array($textGral, $search_term);
                        $entidad = explode(':', trim($textGral[$position]));
                        $entidad = $this->delete_space($entidad[2], ' ');
                        $data['municipio'] = trim($entidad);
                    }
                    catch (\Exception $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer municipio: $sap_code\t";
                        $this->updateWithError("pospuesto, error al extraer estado", $sap_code);
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer municipio: $sap_code\t";
                        $this->updateWithError("pospuesto, error al extraer estado", $sap_code);
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
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }
                    catch (\Throwable  $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
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

                $insert = "INSERT INTO nikkenla_incorporation.users_fiscal_update(user_id,sap_code,rfc,person_type,regimen_code,regimen_description,business_name,name,last_name,second_last_name,cp,estado,municipio,colonia,cfdi_code,cfdi_description,fiscal_file,comments,updated_on_sql_server,existeSap,created_at,updated_at)
                VALUES ('" . $data2['pdfUSER']['user_id'] . "', '" . $data2['pdfUSER']['sap_code'] . "', '" . strtoupper($data2['pdfUSER']['RFC']) . "', '" . $data2['pdfUSER']['tipo'] . "', '" . $data2['pdfUSER']['regimen'] . "', '" . strtoupper($data2['pdfUSER']['regimenDescriptor']) . "', '', '" . strtoupper($data2['pdfUSER']['nombre']) . "', '" . strtoupper($data2['pdfUSER']['apellido1']) . "', '" . strtoupper($data2['pdfUSER']['apellido2']) . "', '" . $data2['pdfUSER']['cp'] . "', '" . $data2['pdfUSER']['estado'] . "', '" . $data2['pdfUSER']['municipio'] . "', '" . $data2['pdfUSER']['colonia'] . "', '" . $data2['pdfUSER']['codCFDI'] . "', '" . $data2['pdfUSER']['descCFDI'] . "', '" . $data2['pdfUSER']['pdffile'] . "', '', '0', '0', '" . $data2['pdfUSER']['dateReg'] . "', '" . $data2['pdfUSER']['lastUpdate'] . "')";
                
                $conexion = \DB::connection('migracion');
                    $response = $conexion->insert("$insert");
                \DB::disconnect('migracion');

                $conexion = \DB::connection('mysqlTV');
                    $response = $conexion->update("UPDATE users_fiscal_files SET processed = 1, last_error_message = NULL WHERE sap_code = $sap_code");
                \DB::disconnect('mysqlTV');
    
                $return = "PDF procesado, usuario: $sap_code";
            }
            else{
                $conexion = \DB::connection('mysqlTV');
                    $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'Formato de constancia incorrecto' WHERE sap_code = $sap_code");
                \DB::disconnect('mysqlTV');
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
                try{
                    $pdf = $parser->parseFile($PDFfile);
                } 
                catch (\Exception $e) {
                    $this->updateWithError("Constancia no oficial o no actualizada 2022", $sap_code);
                    $logExec = "[" . date('Y-m-d H:i:s') . "] Constancia no oficial o no actualizada 2022: $sap_code\t";
                    return Storage::append("logValidaPDFFiscal.txt", $logExec);
                }
                catch (\Throwable  $e) {
                    $this->updateWithError("Constancia no oficial o no actualizada 2022", $sap_code);
                    $logExec = "[" . date('Y-m-d H:i:s') . "] Constancia no oficial o no actualizada 2022: $sap_code\t";
                    return Storage::append("logValidaPDFFiscal.txt", $logExec);
                }
                //$pdf = $parser->parseFile($PDFfile);
                $data = [];
                $textGral = $pdf->getText();
                $find = "CÉDULA DE IDENTIFICACIÓN FISCAL";
                $validaTexto = strpos($textGral, $find);
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
                    "Opcional para Grupos de Sociedades" => 623,
                    "Coordinados" => 624,
                    "Regimen Simplificado de Confianza" => 626,
                    'Régimen Simplificado de Confianza' => 626,
                ];

                if ($validaTexto === false) {
                    $conexion = \DB::connection('mysqlTV');
                        $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'Constancia no oficial o no actualizada 2022' WHERE sap_code = $sap_code");
                    \DB::disconnect('mysqlTV');
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
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }
                    catch (\Throwable  $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
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
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }
                    catch (\Throwable  $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }

                    try{
                        $search_term = "Denominación/Razón\tSocial:";
                        $position = $this->search_array($textGral, $search_term);
                        $nombre = explode(':', trim($textGral[$position]));
                        $nombre = $this->delete_space($nombre[1], ' ');
                        $data['nombre'] = trim($nombre);
                    }
                    catch (\Exception $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }
                    catch (\Throwable  $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
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
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }
                    catch (\Throwable  $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }
                    try{
                        $search_term = "Nombre\tde\tlaEntidad\tFederativa";
                        $position = $this->search_array($textGral, $search_term);
                        $entidad = explode(':', trim($textGral[$position]));
                        $entidad = $this->delete_space($entidad[1], ' ');
                        $entidad = explode(' ', trim($entidad));
                        $data['estado'] = trim($entidad[0]);
                    }
                    catch (\Exception $e) {
                        
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer estado: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer estado: $sap_code\t";
                        return $logExec;
                    }
                    
                    try{
                        $search_term = "Nombre\tde\tlaLocalidad";
                        $position = $this->search_array($textGral, $search_term);
                        $entidad = explode(':', trim($textGral[$position]));
                        $entidad = $this->delete_space($entidad[2], ' ');
                        $data['municipio'] = trim($entidad);
                    }
                    catch (\Exception $e) {
                        $this->updateWithError("pospuesto, error al extraer municipio", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer municipio: $sap_code\t";
                        return $logExec;
                    }
                    catch (\Throwable  $e) {
                        $this->updateWithError("pospuesto, error al extraer municipio", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer municipio: $sap_code\t";
                        return $logExec;
                    }
                    
                    try{
                        $search_term = "Nombre\tde\tlaColonia:";
                        $position = $this->search_array($textGral, $search_term);
                        $colonia = explode('Colonia:', trim($textGral[$position]));
                        $colonia = $this->delete_space($colonia[1], ' ');
                        $data['colonia'] = trim($colonia);
                    }
                    catch (\Exception $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
                    }
                    catch (\Throwable  $e) {
                        $this->updateWithError("pospuesto, error al extraer $search_term", $sap_code);
                        $logExec = "[" . date('Y-m-d H:i:s') . "] pospuesto, error al extraer $search_term: $sap_code\t";
                        return Storage::append("logValidaPDFFiscal.txt", $logExec);
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
                Storage::append("logValidaPDFFiscal.txt", $logExec);
            }
            else{
                $conexion = \DB::connection('mysqlTV');
                    $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'Formato de constancia incorrecto' WHERE sap_code = $sap_code");
                \DB::disconnect('mysqlTV');
                $return = "Formato de constancia incorrecto: $sap_code";
            }
            $logExec = "[" . date('Y-m-d H:i:s') . "] " . $return . "\t";
            Storage::append("logValidaPDFFiscal.txt", $logExec);
        }
        else{
            $conexion = \DB::connection('mysqlTV');
                    $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'Tipo persona No Aplica' WHERE sap_code = $sap_code");
                \DB::disconnect('mysqlTV');
            $logExec = "[" . date('Y-m-d H:i:s') . "] Tipo persona: No Aplica: $sap_code\t";
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

    public function updateWithError($message, $sap_code){
        $message = trim($message);
        $conexion = \DB::connection('mysqlTV');
            $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = '$message' WHERE sap_code = $sap_code");
        \DB::disconnect('mysqlTV');
        return null;
    }
}
