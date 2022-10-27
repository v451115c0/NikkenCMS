<?php

namespace App\Http\Controllers\IncorporacionWeb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contracts;
use DateTime;

class IwebController extends Controller
{
    //
    public function pendientes_pago(){
        $contracts = Contracts::where('payment', '=',0)
                                ->where('country','=','1')
                                ->where('status','=',1)
                                // ->where('user_approved','=','')
                                ->get();
   
        $flag_icon = ['LATAM','COL','MEX','PER','ECU','PAN','GTM','SLV','CRI','CHI'];
        $fechaActual = date('Y-m-d');
        $fecha1 = date_create($fechaActual);
        foreach ($contracts as $c) {
            # code...
            $creado = substr($c->create_at,0,10);
            $fecha2 = date_create($creado);
             $diff = $fecha1->diff($fecha2);
            $c->dias = $diff->days;

            $date1 = new DateTime($c->create_at);
            $fechaActual = $date1->format('d/m/Y');
            $c->fecha = $fechaActual;
             
            if($c->country != 0){
                $c->flag = '<img src="https://mitiendanikken.com/images/site/flags/'.$flag_icon[$c->country].'.png" alt="imagen_pais"/>';
              }
              else{
                $c->flag = 'LATAM';
              }

            if($c->type_incorporate == 0) $c->tipo = 'Miembro de la Comunidad';
            else $c->tipo = 'Influencer';

            $c->btn = '<i class="fa-regular fa-eye" data-id ='.$c->id_contract.'></i>
            <i class="fa-solid fa-comment" data-id ='.$c->id_contract.'></i>
            <i class="fa-solid fa-cloud-arrow-up" data-id ='.$c->id_contract.'></i>';

        }

        $send['data']=$contracts;
        return $send;
    }

    public function pendientes_asignar(){
        $contracts = Contracts::where('country','=','1')->where('sponsor', '=','')->get();
   
        $flag_icon = ['LATAM','COL','MEX','PER','ECU','PAN','GTM','SLV','CRI','CHI'];
        $fechaActual = date('Y-m-d');
        $fecha1 = date_create($fechaActual);
        foreach ($contracts as $c) {
            # code...
            $creado = substr($c->create_at,0,10);
            $fecha2 = date_create($creado);
             $diff = $fecha1->diff($fecha2);
            $c->dias = $diff->days;

            $date1 = new DateTime($c->create_at);
            $fechaActual = $date1->format('d/m/Y');
            $c->fecha = $fechaActual;
             
            if($c->country != 0){
                $c->flag = '<img src="https://mitiendanikken.com/images/site/flags/'.$flag_icon[$c->country].'.png" alt="imagen_pais"/>';
              }
              else{
                $c->flag = 'LATAM';
              }


            if($c->type_incorporate == 0) $c->tipo = 'Miembro de la Comunidad';
            else $c->tipo = 'Influencer';

            $c->btn = '<i class="fa-regular fa-eye" data-id ='.$c->id_contract.'></i>';

        }

        $send['data']=$contracts;
        return $send;
    }

    public function pendientes_contrato()
    {
        $contracts = Contracts::where('country','=','1')
                                ->where('user_approved','=','')
                                ->where('status','=',1)
                                ->get();
   
        $flag_icon = ['LATAM','COL','MEX','PER','ECU','PAN','GTM','SLV','CRI','CHI'];
        $fechaActual = date('Y-m-d');
        $fecha1 = date_create($fechaActual);
        foreach ($contracts as $c) {
            # code...
            $creado = substr($c->create_at,0,10);
            $fecha2 = date_create($creado);
             $diff = $fecha1->diff($fecha2);
            $c->dias = $diff->days;

            $date1 = new DateTime($c->create_at);
            $fechaActual = $date1->format('d/m/Y');
            $c->fecha = $fechaActual;
             
            if($c->country != 0){
                $c->flag = '<img src="https://mitiendanikken.com/images/site/flags/'.$flag_icon[$c->country].'.png" alt="imagen_pais"/>';
              }
              else{
                $c->flag = 'LATAM';
              }


            if($c->type_incorporate == 0) $c->tipo = 'Miembro de la Comunidad';
            else $c->tipo = 'Influencer';

            $c->btn = '<i class="fa-regular fa-eye" data-id ='.$c->id_contract.'></i>
            <i class="fa-solid fa-comment" data-id ='.$c->id_contract.'></i>';

        }

        $send['data']=$contracts;
        return $send;
    }


}
