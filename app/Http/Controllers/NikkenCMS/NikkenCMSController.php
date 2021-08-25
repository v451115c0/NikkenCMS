<?php

namespace App\Http\Controllers\NikkenCMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NikkenCMSController extends Controller{
    public function login(){
        session()->forget('tokenPass');
        session()->forget('tokenUser');
        return view('NikkenCMS.login');
    }

    public function authLogin(Request $request){
        $_token = $request->_token;
        $user = $request->user;
        $pass = $this->aes_sap_encrypt($request->pass);
        $conexion = \DB::connection('sqlsrv5');
            $data = $conexion->select("SELECT * FROM cmsLoginUsers WHERE User_cms = '$user' AND Password = '$pass'");
        \DB::disconnect('sqlsrv5');
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

    public function getViwe($view){
        if(empty(session('tokenPass'))){
            return redirect('/NikkenCMS/login');
        }
        else{
            return view('NikkenCMS.' . $view);
        }
    }

    public function getActions(Request $request){
        $action = $request->action;
        $parameters = $request->parameters;
        switch($action){
            case 'top5Activos':
                return $this->top5Activos($parameters);
                break;
            case 'graphVisitas':
                return $this->graphVisitas($parameters);
                break;
            case 'getHeaderTableQuery':
                return $this->getHeaderTableQuery($parameters);
                break;
            case 'ejecQueryFromWeb':
                return $this->ejecQueryFromWeb($parameters);
                break;
            case 'getDataSaleMK':
                return $this->getDataSaleMK($parameters);
                break;
            case 'getDataSaleMKSale':
                return $this->getDataSaleMKSale($parameters);
                break;
            case 'getDataSaleMKPayment':
                return $this->getDataSaleMKPayment($parameters);
                break;
            case 'getDataSaleMKProducts':
                return $this->getDataSaleMKProducts($parameters);
                break;
            case 'getDataUser':
                return $this->getDataUser($parameters);
                break;
            case 'changeNameMNK':
                return $this->changeNameMNK($parameters);
                break;
            case 'addKitInicioTV':
                return $this->addKitInicioTV($parameters);
                break;
            case 'TVLoadLogVueltaAcasa':
                return $this->TVLoadLogVueltaAcasa();
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
        }
    }

    public function top5Activos($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $plataforma = $parameters['plataforma'];
            $mes = Date('Y-m');
            $conexion = \DB::connection('sqlsrv5');
                $data = $conexion->select("SELECT TOP 5 Associateid, count(Associateid) AS 'Acciones' FROM RETOS_ESPECIALES.dbo.Metricas_Nikken WHERE Plataforma = '$plataforma' AND Fecha > '$mes-01' GROUP BY Associateid HAVING count(Associateid)>1 ORDER BY Acciones DESC");
            \DB::disconnect('sqlsrv5');
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
            $conexion = \DB::connection('sqlsrv5');
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
            \DB::disconnect('sqlsrv5');
            return $data;
        }
    }

    public function getHeaderTableQuery($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $tableName = $parameters['tableName'];
            $conexion = \DB::connection('mysql4');
                $data = $conexion->select("SELECT COLUMN_NAME AS `Field` FROM information_schema.COLUMNS  WHERE TABLE_SCHEMA = 'nikkenla_marketing' AND TABLE_NAME = '$tableName';");
            \DB::disconnect('mysql4');
            return $data;
        }
    }

    public function ejecQueryFromWeb($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $query = $parameters['query'];
            $query = base64_decode($query);
            $conexion = \DB::connection('mysql4');
                $response = $conexion->select("$query");
            \DB::disconnect('mysql4');
            return $response;
        }
    }

    // extrae información de venta por mokuteki
    function getDataSaleMK($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $sap_code = $parameters['sap_code'];
            $data[] = [];
            $conexion = \DB::connection('mysql2');
                $response = $conexion->select("SELECT id, email, country_id, sap_code, sap_code_sponsor, client_type, rank, name, last_name, status, created_at, updated_at, locked FROM users WHERE sap_code = $sap_code");
                $venta = $conexion->select("SELECT sa.id, sa.status, prod.sku
                FROM sales sa
                INNER JOIN sale_products prod ON prod.sale_id = sa.id
                INNER JOIN sales_information_payments pay ON pay.sale_id = sa.id
                WHERE sa.user_id = " . $response[0]->id . " and prod.sku = 5002 and sa.status not in ('cancelada')");
                $products = $conexion->select("SELECT sale_id, sku FROM sale_products WHERE sale_id IN (SELECT id FROM sales WHERE user_id = " . $response[0]->id . ") AND sku = 5002");
            \DB::disconnect('mysql2');
            $data['user'] = $response;
            $data['venta'] = $venta;
            $data['products'] = $products;
            return $data;
        }
    }

    function getDataSaleMKSale($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $user_id = $parameters['user_id'];
            $conexion = \DB::connection('mysql2');
                $response = $conexion->select("SELECT id, reference_code, user_id, country_id, type_of_sale, status, subtotal, tax, total FROM sales WHERE user_id = $user_id");
            \DB::disconnect('mysql2');
            $data = [
                'data' => $response
            ];
            return $data;
        }
    }
    
    function getDataSaleMKPayment($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $sap_code = $parameters['sap_code'];
            $conexion = \DB::connection('mysql2');
                $response = $conexion->select("SELECT sale_id, country_id, payment_method, payment_provider, payment_amount, confirmation_code, status, creared_at, updated_at FROM sales_information_payments WHERE sale_id = $sale_id");
            \DB::disconnect('mysql2');
            $data = [
                'data' => $response
            ];
            return $data;
        }
    }

    function getDataSaleMKProducts($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $sale_id = $parameters['sale_id'];
            $conexion = \DB::connection('mysql2');
                $response = $conexion->select("SELECT sale_id, sku, name, quantity, price, subtotal, tax, total FROM sale_products WHERE sale_id = $sale_id");
            \DB::disconnect('mysql2');
            $data = [
                'data' => $response
            ];
            return $data;
        }
    }

    // MyNIKKEN
    public function getDataUser($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $sap_code = $parameters['sap_code'];
            $conexion = \DB::connection('sqlsrvMynikken');
                $response = $conexion->select("SELECT AssociateName FROM Distributors_MD WHERE associateid = $sap_code;");
            \DB::disconnect('sqlsrvMynikken');
            return $response;
        }
    }

    public function changeNameMNK($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $sap_code = $parameters['sap_code'];
            $nuevoNombre = $parameters['nuevoNombre'];
            $origen = $parameters['origen'];
            $conexion = \DB::connection('sqlsrvMynikken');
                if($origen == 'TV'){
                    $conexionTV = \DB::connection('mysql2');
                        $response = $conexionTV->select("SELECT CONCAT(last_name, name) AS nombre FROM users WHERE sap_code = $sap_code;");
                    \DB::disconnect('mysql2');
                    $nuevoNombre = $response[0]->nombre;
                }
                $response = $conexion->update("UPDATE Distributors_MD SET AssociateName = '$nuevoNombre' WHERE associateid = $sap_code;");
                $response = $conexion->update("UPDATE [170].sboreport.dbo.associates1 SET ApFirstName = '$nuevoNombre' WHERE Associateid = $sap_code;");
                $response = $conexion->select("SELECT AssociateName FROM Distributors_MD WHERE associateid = $sap_code;");
            \DB::disconnect('sqlsrvMynikken');
            return $response;
        }
    }

    public function addKitInicioTV($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $sap_code = $parameters['sap_code'];
            $response = [];
            $sale_id = "";
            $conexion = \DB::connection('mysql2');
                $user_id = $conexion->select("SELECT id FROM users WHERE sap_code = $sap_code");
                if(!empty($user_id)){
                    $user_id = $user_id[0]->id;
                    $sale_id = $conexion->select("SELECT id FROM sales WHERE user_id = $user_id AND status = 'pagada' ORDER BY created_at ASC LIMIT 1");
                    if(!empty($sale_id)){
                        $sale_id = $sale_id[0]->id;
                        $products = $conexion->select("SELECT created_at, updated_at, sku FROM sale_products WHERE sale_id = '$sale_id'");
                        $tieneKit = "0";
                        for($x = 0; $x < sizeof($products); $x++){
                            if($products[$x]->sku == 5006 || $products[$x]->sku == 5023 || $products[$x]->sku == 5024 || $products[$x]->sku == 5025 || $products[$x]->sku == 5026 || $products[$x]->sku == 5027 || $products[$x]->sku == 5028){
                                $tieneKit = "1";
                            }
                        }
                        if($tieneKit == "0" || $tieneKit == 0){
                            $products = $conexion->insert("INSERT INTO sale_products (sale_id, product_id, sku, name, quantity, price, discount, discount_rate, subtotal, tax, total, extra_perception_total, extra_charges, points, vc, lading, created_at, updated_at, retail) VALUES ('$sale_id', '1646', '5006', 'KIT DE EMPRENDIMIENTO', '1', '50924.37', '0', '0', '50924.37', '9675.6303', '60600.0003', '0.00', '0.00', '0', '0', '9500', '" . $products[0]->created_at . "', '" . $products[0]->updated_at . "', '0')");
                            if($products){
                                $products = "1";
                            }
                            else{
                                $products = "0";
                            }
                        }
                        else{
                            $products = "Tiene kit";
                        }
                    }
                }
            \DB::disconnect('mysql2');
            $response['sale_id'] = $sale_id;
            $response['kit'] = $products;
            return $response;
        }
    }

    public function TVLoadLogVueltaAcasa(){
        $logFile = fopen('C:\Users\fmrmex\Desktop\Log_vuelta_a_Casa.txt', "r");
        $val = "";
        while(!feof($logFile)){
            $val .= fgets($logFile) . "<br>";
        }
        fclose($logFile);
        return $val;
    }

    // Actualización de número de whatsapp
    public function getdataWhatsapp(){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $conexion = \DB::connection('tvtest');
                $dataCell = $conexion->select("SELECT cell.*,  us.sap_code, CONCAT(us.name, ' ', us.last_name) AS nombre FROM users_cell_phone_update cell INNER JOIN users us ON cell.user_id = us.id");
            \DB::disconnect('tvtest');
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
            $conexion = \DB::connection('tvtest');
                $response = $conexion->select("SELECT cell.*,  us.sap_code, CONCAT(us.name, ' ', us.last_name) AS nombre FROM users_cell_phone_update cell INNER JOIN users us ON cell.user_id = us.id WHERE cell.id = $id");
            \DB::disconnect('tvtest');
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
            $conexion = \DB::connection('tvtest');
                $response = $conexion->update("UPDATE users_cell_phone_update SET area_code = '$country_code', cell_phone = '$numberCell', updated_on_sql_server = '$Update_On_SQL_server', use_as_my_principal_phone = '$Use_As_My_Principal_phone' WHERE id = $id");
            \DB::disconnect('tvtest');
            return $response;
        }
    }
    
    public function deleteDataWSTV($parameters){
        if(empty(session('tokenPass'))){
            return "error";
        }
        else{
            $id = $parameters['id'];
            $conexion = \DB::connection('tvtest');
                $response = $conexion->delete("DELETE FROM users_cell_phone_update WHERE id = $id");
            \DB::disconnect('tvtest');
            return $response;
        }
    }
}