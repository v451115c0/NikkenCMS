<?php

namespace App\Http\Controllers\depuraciones7days;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class dep7dayController extends Controller{
    public function Depuraciones(){
        $fecha_actual = date("d-m-Y"); 
        $fecha7diasantes = date("Y-m-d",strtotime($fecha_actual."- 7 days"));
        $conexion5 = \DB::connection('migracion');
            $depuraciones = $conexion5->select("SELECT * FROM nikkenla_incorporation.contracts WHERE payment = 0 AND create_at < '$fecha7diasantes' AND code NOT LIKE '%2' AND type = 1 AND email != '' AND email NOT LIKE '%depuracion%' AND email NOT LIKE '%.20%' and email NOT LIKE '%_20%';");
        \DB::disconnect('migracion');
        $data = [
            'data' => $depuraciones,
        ];
        return $data;
    }

    public function Depurarmost7days(Request $request){
        $codigo = $request->codigo;
        $correo = $request->correo;
        $codigodepurado = $codigo."2";
        $correodepurado=$correo."_".date("YmdHis"); ;
        $created_at = date("Y-m-d H:i:s");

        $conexion5 = \DB::connection('mysqlTV');
            $userswithoutpay = $conexion5->select("SELECT a.sap_code, a.client_type, a.email, a.status, a.locked, b.id, b.status FROM mitiendanikken.users a
                INNER JOIN mitiendanikken.sales b ON a.id = b.user_id
                INNER JOIN mitiendanikken.sale_products c ON b.id = c.sale_id
                WHERE a.sap_code IN (
                    '$codigo'
                ) and b.status = 'pagada' and c.sku in (5024, 5025, 5026, 5027, 5028, 5002, 5031, 5032, 502719,502819,502619,502519,50249,50239); ");
        \DB::disconnect('mysqlTV');

        //si regresa 1 es porque si tiene pago
        //si regresa 0 es porque no tiene pago
        if (sizeof($userswithoutpay) > 0) {
            return 0;
           // echo "no se puede depurar, ya tiene pago ";
        }
        else{
            $conexiontv = \DB::connection('mysqlTV');
                $updatetv = $conexiontv->update("UPDATE users SET sap_code = '$codigodepurado', email = '$correodepurado', status = 2, locked = 1 WHERE sap_code = '$codigo'; ");
            \DB::disconnect('mysqlTV');

            /*Depura el  usuario en la TV*/
            /* if ($updatetv == 0) {
                    return "no se pudo depurar en tv";
            }else{*/
            $conexionov = \DB::connection('migracion');
                $updatecontrol_ci = $conexionov->update("UPDATE nikkenla_marketing.control_ci SET codigo = '$codigodepurado', correo = '$correodepurado', estatus = 2 WHERE codigo = '$codigo';");
            \DB::disconnect('migracion');
            //}

            /*if (sizeof($updatecontrol_ci) > 0) {
                return "no se pudo depurar en tv";
            }else{*/
            $conexioniw = \DB::connection('migracion');
                $updatecontrol_contracts = $conexioniw->update("UPDATE nikkenla_incorporation.contracts SET code = '$codigodepurado', email = '$correodepurado', status = 2 WHERE code = '$codigo';");
            \DB::disconnect('migracion');

            $conexionlog = \DB::connection('migracion');
                $log = $conexionlog->insert("INSERT INTO nikkenla_incorporation.socios_depurados VALUES ('$codigo','Panel Admin','$created_at')");
            \DB::disconnect('migracion');
             //}

            return 1;
            //echo "depurar registro";
        }
    }
}
