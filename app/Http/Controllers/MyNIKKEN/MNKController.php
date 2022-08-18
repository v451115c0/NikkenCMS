<?php

namespace App\Http\Controllers\MyNIKKEN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use \ConvertApi\ConvertApi;
use Zxing\QrReader;

class MNKController extends Controller{

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

    public function apiDataFiscalPDF(Request $request) {
        $personType = strtoupper(request()->t);
        $pdfFile = request()->f;

        if(trim($personType) == 'FISICA'){

            $formato = explode("datos-fiscales", $pdfFile);
            $formato = explode(".", $formato[1]);
            $formato = $formato[1];
            if(trim($formato) === 'pdf'){
                $data2 = [];

                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($pdfFile);
                $data = [];
                $textGral = $pdf->getText();
                $find = "CÉDULA DE IDENTIFICACIÓN FISCAL";
                $validaTexto = strpos($textGral, $find);

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
                    return 'El PDF del usuario no corresponde al SAT';
                }
                else {
                    $textGral = explode("\n", $textGral);
                    
                    $search_term = "RFC:";
                    $position = $this->search_array($textGral, $search_term);
                    $rfc = explode(':', trim($textGral[$position]));
                    $rfc = $this->delete_space($rfc[1], ' ');
                    $data['RFC'] = trim($rfc);

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

                    $search_term = "Nombre\tde\tlaEntidad\tFederativa";
                    $position = $this->search_array($textGral, $search_term);
                    $entidad = explode(':', trim($textGral[$position]));
                    $entidad = $this->delete_space($entidad[1], ' ');
                    $entidad = explode(' ', trim($entidad));
                    $data['estado'] = trim($entidad[0]);

                    $data['ciudad'] = "";

                    $search_term = "Nombre\tde\tlaLocalidad";
                    $position = $this->search_array($textGral, $search_term);
                    $entidad = explode(':', trim($textGral[$position]));
                    return $entidad;
                    $entidad = explode('\tNombre\tdel\tMunicipio\to\tDemarcación\tTerritorial:', $entidad);
                    return $entidad;
                    $entidad = $this->delete_space($entidad[1], ' ');
                    $entidad = explode(' ', trim($entidad));
                    $data['municipio'] = trim($entidad[0]);

                    $data['CalleyNumero'] = "";

                    $data['actividadEconomica'] = "";

                    $data['regimen'] = "";

                    return $data;
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
                return "Consstancia Fiscal erronea";
            }
        }
        else if(trim($personType) == 'MORAL'){
            return $personType;
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
                    "Régimen General de Ley Personas Morales" => 601,
                    "Personas Morales con Fines no Lucrativos" => 603,
                    "Residentes en el Extranjero sin Establecimiento Permanente en Mexico" => 610,
                    "Sin obligaciones Fiscales" => 616,
                    "Sociedades Cooperativas de Produccion que optan por diferir sus ingresos" => 620,
                    "Actividades Agricolas, Ganaderas, Silvicolas y Pesqueras" => 622,
                    "Opcional para Grupos de Sociedades" => 623,
                    "Coordinados" => 624,
                    "Regimen Simplificado de Confianza" => 626
                ];

                if ($validaTexto === false) {
                    $conexion = \DB::connection('mysqlTVTest');
                        $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'El PDF del usuario no corresponde al SAT' WHERE sap_code = $sap_code");
                    \DB::disconnect('mysqlTVTest');
                    $return = 'El PDF del usuario no corresponde al SAT';
                    return $return;
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

                    $search_term = "Regímenes";
                    $position = $this->search_array($textGral, $search_term);
                    $position = (intval($position) + 2);
                    $data['regimenDescriptor'] = trim($this->deleteNumbersSepecialChar($this->delete_space($textGral[$position], ' '), ''));
                    $data['regimen'] = $arrayRegimenCode[trim($data['regimenDescriptor'])];

                    $search_term = "Denominación/Razón\tSocial:";
                    $position = $this->search_array($textGral, $search_term);
                    $nombre = explode(':', trim($textGral[$position]));
                    $nombre = $this->delete_space($nombre[1], ' ');
                    $data['nombre'] = trim($nombre);

                    $search_term = "Código\tPostal";
                    $position = $this->search_array($textGral, $search_term);
                    $cp = explode(':', trim($textGral[$position]));
                    $cp = $this->delete_space($cp[1], ' ');
                    $cp = explode(' ', trim($cp));
                    $data['cp'] = trim($cp[0]);

                    $conexion = \DB::connection('mysqlTV');
                        $response = $conexion->select("SELECT campo_uno_name AS estado, campo_dos_name AS municipio FROM states_countries WHERE CP = '" . $data['cp'] . "' LIMIT 1;");
                    \DB::disconnect('mysqlTV');
                    if(sizeof($response) <= 0){
                        $conexion = \DB::connection('mysqlTVTest');
                            $response = $conexion->update("UPDATE users_fiscal_files SET error = 1, last_error_message = 'Formato de constancia incorrecto' WHERE sap_code = $sap_code");
                        \DB::disconnect('mysqlTVTest');
                        $return = "Código Postal desconocido: $sap_code";
                        $logExec = "[" . date('Y-m-d H:i:s') . "] $return\t";
                        Storage::append("logValidaPDFFiscal.txt", $logExec);
                        return "";
                    }
                    $data['estado'] = strtoupper($response[0]->estado);
                    $data['municipio'] = strtoupper($response[0]->municipio);
                    
                    $search_term = "Nombre\tde\tlaColonia:";
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
                    $conexion = \DB::connection('mysqlTVTest');
                        $user = $conexion->select("SELECT count(sap_code) as total FROM users WHERE sap_code = $sap_code");
                    \DB::disconnect('mysqlTVTest');
                    $existe = $user[0]->total;

                    if($existe > 0){
                        $insert = "INSERT INTO users_fiscal_update(user_id,sap_code,rfc,person_type,regimen_code,regimen_description,business_name,name,last_name,second_last_name,cp,estado,municipio,colonia,cfdi_code,cfdi_description,fiscal_file,comments,updated_on_sql_server,existeSap,created_at,updated_at)
                        VALUES ('" . $data2['pdfUSER']['user_id'] . "', '" . $data2['pdfUSER']['sap_code'] . "', '" . strtoupper($data2['pdfUSER']['RFC']) . "', '" . $data2['pdfUSER']['tipo'] . "', '" . $data2['pdfUSER']['regimen'] . "', '" . strtoupper($data2['pdfUSER']['regimenDescriptor']) . "', '', '" . strtoupper($data2['pdfUSER']['nombre']) . "', '', '', '" . $data2['pdfUSER']['cp'] . "', '" . $data2['pdfUSER']['estado'] . "', '" . $data2['pdfUSER']['municipio'] . "', '" . $data2['pdfUSER']['colonia'] . "', '" . $data2['pdfUSER']['codCFDI'] . "', '" . $data2['pdfUSER']['descCFDI'] . "', '" . $data2['pdfUSER']['pdffile'] . "', '', '0', '0', '" . $data2['pdfUSER']['dateReg'] . "', '" . $data2['pdfUSER']['lastUpdate'] . "')";
                        
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