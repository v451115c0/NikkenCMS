<?php

namespace App\Http\Controllers\MyNIKKEN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}