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
                    $entidad = $this->delete_space($entidad[2], ' ');
                    $data['municipio'] = trim($entidad);

                    $search_term = "Nombre\tde\tVialidad";
                    $position = $this->search_array($textGral, $search_term);
                    $calle = explode(':', trim($textGral[$position]));
                    $calle = $this->delete_space($calle[1], ' ');
                    $calle = explode('Número', $calle);
                    $data['calle'] = trim($calle[0]);

                    $search_term = "Número\tExterior";
                    $position = $this->search_array($textGral, $search_term);
                    $numero = explode('Exterior:', trim($textGral[$position]));
                    $numero = $this->delete_space($numero[1], ' ');
                    $data['numero'] = trim($numero);

                    $search_term = "Actividades Económicas";
                    $position = $this->search_array($textGral, $search_term);
                    $actividadEconomica = $textGral[($position + 2)];
                    $actividadEconomica = $this->delete_space($actividadEconomica, ' ');
                    $actividadEconomica = $this->deleteNumbersSepecialChar($actividadEconomica, ' ');
                    $data['actividadEconomica'] = trim($actividadEconomica);

                    $search_term = "Regímenes:";
                    $position = $this->search_array($textGral, $search_term);
                    $regimen = $textGral[($position + 2)];
                    $regimen = $this->delete_space($regimen, ' ');
                    $regimen = $this->deleteNumbersSepecialChar($regimen, ' ');
                    $data['regimen'] = trim($regimen);

                    $regimen = $textGral[($position + 3)];
                    $regimen = $this->delete_space($regimen, ' ');
                    $regimen = $this->deleteNumbersSepecialChar($regimen, ' ');
                    $validador = explode(' ', $regimen);
                    if(trim($validador[0]) == 'Régimen'){
                        $data['regimen2'] = trim($regimen);
                    }

                    $regimen = $textGral[($position + 4)];
                    $regimen = $this->delete_space($regimen, ' ');
                    $regimen = $this->deleteNumbersSepecialChar($regimen, ' ');
                    $validador = explode(' ', $regimen);
                    if(trim($validador[0]) == 'Régimen'){
                        $data['regimen3'] = trim($regimen);
                    }

                    $regimen = $textGral[($position + 5)];
                    $regimen = $this->delete_space($regimen, ' ');
                    $regimen = $this->deleteNumbersSepecialChar($regimen, ' ');
                    $validador = explode(' ', $regimen);
                    if(trim($validador[0]) == 'Régimen'){
                        $data['regimen4'] = trim($regimen);
                    }

                    $regimen = $textGral[($position + 6)];
                    $regimen = $this->delete_space($regimen, ' ');
                    $regimen = $this->deleteNumbersSepecialChar($regimen, ' ');
                    $validador = explode(' ', $regimen);
                    if(trim($validador[0]) == 'Régimen'){
                        $data['regimen5'] = trim($regimen);
                    }

                    return $data;
                }
            }
            else{
                return "Constancia Fiscal erronea";
            }
        }
        else if(trim($personType) == 'MORAL'){
            $formato = explode("datos-fiscales", $pdfFile);
            $formato = explode(".", $formato[1]);
            $formato = $formato[1];
            if(trim($formato) === 'pdf'){
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($pdfFile);
                $data = [];
                $textGral = $pdf->getText();
                $find = "CÉDULA DE IDENTIFICACIÓN FISCAL";
                $validaTexto = strpos($textGral, $find);
                
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

                    $search_term = "Denominación/Razón\tSocial:";
                    $position = $this->search_array($textGral, $search_term);
                    $nombre = explode(':', trim($textGral[$position]));
                    $nombre = $this->delete_space($nombre[1], ' ');
                    $data['razonSocial'] = trim($nombre);

                    $search_term = "Actividades Económicas";
                    $position = $this->search_array($textGral, $search_term);
                    $actividadEconomica = $textGral[($position + 2)];
                    $actividadEconomica = $this->delete_space($actividadEconomica, ' ');
                    $actividadEconomica = $this->deleteNumbersSepecialChar($actividadEconomica, ' ');
                    $data['actividadEconomica'] = trim($actividadEconomica);

                    $search_term = "Código\tPostal";
                    $position = $this->search_array($textGral, $search_term);
                    $cp = explode(':', trim($textGral[$position]));
                    $cp = $this->delete_space($cp[1], ' ');
                    $cp = explode(' ', trim($cp));
                    $data['cp'] = trim($cp[0]);

                    $data['ciudad'] = "";

                    $search_term = "Nombre\tde\tlaLocalidad";
                    $position = $this->search_array($textGral, $search_term);
                    $entidad = explode(':', trim($textGral[$position]));
                    $entidad = $this->delete_space($entidad[2], ' ');
                    $data['municipio'] = trim($entidad);

                    $search_term = "Nombre\tde\tVialidad";
                    $position = $this->search_array($textGral, $search_term);
                    $calle = explode(':', trim($textGral[$position]));
                    $calle = $this->delete_space($calle[1], ' ');
                    $calle = explode('Número', $calle);
                    $data['calle'] = trim($calle[0]);

                    $search_term = "Número\tExterior";
                    $position = $this->search_array($textGral, $search_term);
                    $numero = explode('Exterior:', trim($textGral[$position]));
                    $numero = $this->delete_space($numero[1], ' ');
                    $data['numero'] = trim($numero);

                    $search_term = "Regímenes:";
                    $position = $this->search_array($textGral, $search_term);
                    $regimen = $textGral[($position + 2)];
                    $regimen = $this->delete_space($regimen, ' ');
                    $regimen = $this->deleteNumbersSepecialChar($regimen, ' ');
                    $data['regimen'] = trim($regimen);

                    $regimen = $textGral[($position + 3)];
                    $regimen = $this->delete_space($regimen, ' ');
                    $regimen = $this->deleteNumbersSepecialChar($regimen, ' ');
                    $validador = explode(' ', $regimen);
                    if(trim($validador[0]) == 'Régimen'){
                        $data['regimen2'] = trim($regimen);
                    }

                    $regimen = $textGral[($position + 4)];
                    $regimen = $this->delete_space($regimen, ' ');
                    $regimen = $this->deleteNumbersSepecialChar($regimen, ' ');
                    $validador = explode(' ', $regimen);
                    if(trim($validador[0]) == 'Régimen'){
                        $data['regimen3'] = trim($regimen);
                    }

                    $regimen = $textGral[($position + 5)];
                    $regimen = $this->delete_space($regimen, ' ');
                    $regimen = $this->deleteNumbersSepecialChar($regimen, ' ');
                    $validador = explode(' ', $regimen);
                    if(trim($validador[0]) == 'Régimen'){
                        $data['regimen4'] = trim($regimen);
                    }

                    $regimen = $textGral[($position + 6)];
                    $regimen = $this->delete_space($regimen, ' ');
                    $regimen = $this->deleteNumbersSepecialChar($regimen, ' ');
                    $validador = explode(' ', $regimen);
                    if(trim($validador[0]) == 'Régimen'){
                        $data['regimen5'] = trim($regimen);
                    }

                    $search_term = "Número:";
                    $position = $this->search_array($textGral, $search_term);
                    $telefono = explode('Número:', trim($textGral[$position]));
                    $telefono = $this->delete_space($telefono[1], ' ');
                    $data['telefono'] = trim($telefono);
                    
                    return $data;
                }
            }
            else{
                return "Constancia Fiscal erronea";
            }
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

    public function killprocess(){
        $conexion = \DB::connection('migracion');
            $data = $conexion->select("SELECT CONCAT('KILL ',id,';') AS muerte FROM information_schema.processlist 
            WHERE user='nikkenla_mkrt' AND Info LIKE '%SELECT codigo, nombre, rango FROM nikkenla_marketing.control_ci WHERE codigo = code AND (rango IN (%'");
            $log = "";
            for($x = 0; $x < sizeof($data); $x++){
                $queryKill = $data[$x]->muerte;
                try{
                    $conexion->statement("$queryKill");
                    $log .= "$queryKill <br> ";
                }
                catch (\Exception $e) {
                    $log .= "error: $queryKill <br> ";
                }
                catch (\Throwable  $e) {
                    $log .= "error: $queryKill <br> ";
                }
            }
        \DB::disconnect('migracion');
        return $log;
    }
}