<?php

namespace App\Http\Controllers\IncorporacionWeb;

use Storage;
use DateTime;
use App\Models\Contracts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LogsIncorporation;
use Google\Cloud\Storage\StorageClient;
use PhpParser\Node\Expr\Isset_;

class IwebController extends Controller
{
  //
  public function pendientes_pago()
  {
    $contracts = Contracts::where('payment', '=', 0)
      ->where('country', '=', '1')
      ->where('status', '=', 1)
      // ->where('user_approved','=','')
      ->get();

    $flag_icon = ['LATAM', 'COL', 'MEX', 'PER', 'ECU', 'PAN', 'GTM', 'SLV', 'CRI', 'CHI'];
    $fechaActual = date('Y-m-d');
    $fecha1 = date_create($fechaActual);
    foreach ($contracts as $c) {
      # code...
      $creado = substr($c->create_at, 0, 10);
      $fecha2 = date_create($creado);
      $diff = $fecha1->diff($fecha2);
      $c->dias = $diff->days;

      $date1 = new DateTime($c->create_at);
      $fechaActual = $date1->format('d/m/Y');
      $c->fecha = $fechaActual;

      if ($c->country != 0) {
        $c->flag = '<img src="https://mitiendanikken.com/images/site/flags/' . $flag_icon[$c->country] . '.png" alt="imagen_pais"/>';
      } else {
        $c->flag = 'LATAM';
      }

      if ($c->type_incorporate == 0) $c->tipo = 'Miembro de la Comunidad';
      else $c->tipo = 'Influencer';

      $c->btn = '<i class="fa-regular fa-eye" data-id =' . $c->id_contract . '></i>
            <i class="fa-solid fa-comment" data-id =' . $c->id_contract . '></i>
            <i class="fa-solid fa-cloud-arrow-up" data-id =' . $c->id_contract . '></i>';
    }

    $send['data'] = $contracts;
    return $send;
  }

  public function pendientes_asignar()
  {
    $contracts = Contracts::where('country', '=', '1')->where('sponsor', '=', '')->get();

    $flag_icon = ['LATAM', 'COL', 'MEX', 'PER', 'ECU', 'PAN', 'GTM', 'SLV', 'CRI', 'CHI'];
    $fechaActual = date('Y-m-d');
    $fecha1 = date_create($fechaActual);
    foreach ($contracts as $c) {
      # code...
      $creado = substr($c->create_at, 0, 10);
      $fecha2 = date_create($creado);
      $diff = $fecha1->diff($fecha2);
      $c->dias = $diff->days;

      $date1 = new DateTime($c->create_at);
      $fechaActual = $date1->format('d/m/Y');
      $c->fecha = $fechaActual;

      if ($c->country != 0) {
        $c->flag = '<img src="https://mitiendanikken.com/images/site/flags/' . $flag_icon[$c->country] . '.png" alt="imagen_pais"/>';
      } else {
        $c->flag = 'LATAM';
      }


      if ($c->type_incorporate == 0) $c->tipo = 'Miembro de la Comunidad';
      else $c->tipo = 'Influencer';

      $c->btn = '<i class="fa-regular fa-eye" data-id =' . $c->id_contract . '></i>';
    }

    $send['data'] = $contracts;
    return $send;
  }

  public function pendientes_contrato()
  {
    $contracts = Contracts::where('country', '=', '1')
      ->where('user_approved', '=', '')
      // ->where('status', '=', 1)
      ->get();

    $flag_icon = ['LATAM', 'COL', 'MEX', 'PER', 'ECU', 'PAN', 'GTM', 'SLV', 'CRI', 'CHI'];
    $fechaActual = date('Y-m-d');
    $fecha1 = date_create($fechaActual);
    foreach ($contracts as $c) {
      # code...
      $creado = substr($c->create_at, 0, 10);
      $fecha2 = date_create($creado);
      $diff = $fecha1->diff($fecha2);
      $c->dias = $diff->days;

      $date1 = new DateTime($c->create_at);
      $fechaActual = $date1->format('d/m/Y');
      $c->fecha = $fechaActual;

      if ($c->country != 0) {
        $c->flag = '<img src="https://mitiendanikken.com/images/site/flags/' . $flag_icon[$c->country] . '.png" alt="imagen_pais"/>';
      } else {
        $c->flag = 'LATAM';
      }


      if ($c->type_incorporate == 0) $c->tipo = 'Miembro de la Comunidad';
      else $c->tipo = 'Influencer';

      $c->btn = '<i class="fa-regular fa-eye" data-id =' . $c->id_contract . '></i>
            <i class="fa-solid fa-comment" data-id =' . $c->id_contract . '></i>
            <i class="fa-regular fa-file" data-id= '.$c->id_contract.'></i>';
    }

    $send['data'] = $contracts;
    return $send;
  }


  public function contrato_with_file(){
    $contracts = Contracts::where('country', '=', '1')
      ->where('user_approved', '=', '')
      // ->where('status', '=', 1)
      ->get();

    $flag_icon = ['LATAM', 'COL', 'MEX', 'PER', 'ECU', 'PAN', 'GTM', 'SLV', 'CRI', 'CHI'];
    $fechaActual = date('Y-m-d');
    $fecha1 = date_create($fechaActual);
    $posiciones = [];
    $contador = 0;
    $send = [];

      foreach ($contracts as $c) {
        # code...
        $creado = substr($c->create_at, 0, 10);
        $fecha2 = date_create($creado);
        $diff = $fecha1->diff($fecha2);
        $c->dias = $diff->days;
  
        $date1 = new DateTime($c->create_at);
        $fechaActual = $date1->format('d/m/Y');
        $c->fecha = $fechaActual;
  
        if ($c->country != 0) {
          $c->flag = '<img src="https://mitiendanikken.com/images/site/flags/' . $flag_icon[$c->country] . '.png" alt="imagen_pais"/>';
        } else {
          $c->flag = 'LATAM';
        }
  
  
        if ($c->type_incorporate == 0) $c->tipo = 'Miembro de la Comunidad';
        else $c->tipo = 'Influencer';
  
        $c->btn = '<i class="fa-regular fa-eye" data-id =' . $c->id_contract . '></i>
              <i class="fa-solid fa-comment" data-id =' . $c->id_contract . '></i>
              <i class="fa-regular fa-file" data-id= '.$c->id_contract.'></i>';
              $buscar = 'Incorporación Web';
              $ch = curl_init();
              // curl_setopt($ch, CURLOPT_URL,'https://iw.nikkenlatam.com:8787/files-upload/22072617420137' );
              curl_setopt($ch, CURLOPT_URL,'https://iw.nikkenlatam.com:8787/files-upload/'.$c->id_contract );
              curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
              $response = curl_exec($ch);
              if(curl_errno($ch)) echo curl_error($ch);
              else $val1 =  strpos($response, $buscar) ;
              //  var_dump($response);
              $res = ($val1 === false) ? 1: 0;
               curl_close($ch);
  
        if($res == 0) // Validamos las que no tengan archivos
        {
         array_push($posiciones,$contador);  // Almacenamos los indices que no tiene para quitarlas del array posteriormente
        }
          
      $contador++;
      }
  
      foreach ($posiciones as $p) {
        # code...
         unset($contracts[$p]);
      }
  
      $indice = 0;
      foreach ($contracts as $c) {
        # code...
        $send['data'][$indice] = $c;
        $indice++;
      }
    
        return $send;

    

    

  }

  public function contrato_without_file(){
    $contracts = Contracts::where('country', '=', '1')
      ->where('user_approved', '=', '')
      // ->where('status', '=', 1)
      ->get();

    $flag_icon = ['LATAM', 'COL', 'MEX', 'PER', 'ECU', 'PAN', 'GTM', 'SLV', 'CRI', 'CHI'];
    $fechaActual = date('Y-m-d');
    $fecha1 = date_create($fechaActual);
    $posiciones = [];
    $contador = 0;
    foreach ($contracts as $c) {
      # code...
      $creado = substr($c->create_at, 0, 10);
      $fecha2 = date_create($creado);
      $diff = $fecha1->diff($fecha2);
      $c->dias = $diff->days;

      $date1 = new DateTime($c->create_at);
      $fechaActual = $date1->format('d/m/Y');
      $c->fecha = $fechaActual;

      if ($c->country != 0) {
        $c->flag = '<img src="https://mitiendanikken.com/images/site/flags/' . $flag_icon[$c->country] . '.png" alt="imagen_pais"/>';
      } else {
        $c->flag = 'LATAM';
      }


      if ($c->type_incorporate == 0) $c->tipo = 'Miembro de la Comunidad';
      else $c->tipo = 'Influencer';

      $c->btn = '<i class="fa-regular fa-eye" data-id =' . $c->id_contract . '></i>
            <i class="fa-solid fa-comment" data-id =' . $c->id_contract . '></i>
            <i class="fa-regular fa-file" data-id= '.$c->id_contract.'></i>';
            $buscar = 'Incorporación Web';
            $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL,'https://iw.nikkenlatam.com:8787/files-upload/22072617420137' );
            curl_setopt($ch, CURLOPT_URL,'https://iw.nikkenlatam.com:8787/files-upload/'.$c->id_contract );
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            $response = curl_exec($ch);
            if(curl_errno($ch)) echo curl_error($ch);
            else $val1 =  strpos($response, $buscar) ;
            //  var_dump($response);
            $res = ($val1 === false) ? 1: 0;
             curl_close($ch);

      if($res == 1) // Validamos las que tengan archivos
      {
       array_push($posiciones,$contador);  // Almacenamos los indices que tienen para quitarlas del array posteriormente
      }
        
    $contador++;
    }

    foreach ($posiciones as $p) {
      # code...
       unset($contracts[$p]);
    }

    $indice = 0;
    foreach ($contracts as $c) {
      # code...
      $send['data'][$indice] = $c;
      $indice++;
    }

    return $send;

  }


  public function test()
  {
    // $buscar = 'Incorporación Web';
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL,'https://iw.nikkenlatam.com:8787/files-upload/22103108442172' );
    // curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    // $response = curl_exec($ch);
    // if(curl_errno($ch)) echo curl_error($ch);
    // else $decoded = json_decode($response);
    // //  var_dump($response);
    // $val1 =  strpos($response, $buscar) ;
    // $res = ($val1 === false) ? "Tiene archivos": "No tiene Archivos";
    
    //  curl_close($ch);


    //  return $res;
    // try {
    //   $storage = new StorageClient([
    //     'keyFilePath' => getcwd() . '/credentials.json',
    //   ]);
    //   $bucketName = 'signuplatamm';
    //   $fileName = 'test.pdf';
    //   $fileroute = 'test.pdf';
    //   $bucket = $storage->bucket($bucketName);
    //   $object = $bucket->upload(
    //     fopen($fileName, 'r')
    //   );
    //   echo "archivo subido correctammente";
    // } catch (Exception $e) {
    //   echo $e->getMessage();
    // }

    // $disk =  Storage::disk('gcs');
    // $disk->put('hola.txt',"hola.txt");

     
    return $user = session('tokenUser');
    
  }


  public function upload_documents(Request $request)
  {
    $result="";
    // return $request;
    foreach ($request->documents_contract as $document) {
      # code...
        try {
        $storage = new StorageClient([
          'keyFilePath' => base_path() . '/public/credentials.json',
        ]);
        $bucketName = 'signuplatamm';
        $bucket = $storage->bucket($bucketName);
        $carpeta = 'COL/'.$request->id_contract_document.'/';
         $filename_original = $document->getClientOriginalName();
         $extension = $document->getClientOriginalExtension();
        $filename= $filename_original.'_'.uniqid().'.'.$extension;
        Storage::put('public/uploads/' . $filename, fopen($document, 'r+'));

        $filepath = storage_path('app/public/uploads/' . $filename);
        $object = $bucket->upload(
          fopen($filepath, 'r'),
          [
            'name' => $carpeta.$filename,
            'predefinedAcl' => 'publicRead'
          ]
        );
         $result = "OK";
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }

    if($result == "OK"){
      $msg = new LogsIncorporation();
      $msg->id_contract = $request->id_contract_document;
      $msg->message = $request->message_update_contract;
      $msg->user_create = session('tokenUser');
      
      $contract = Contracts::where('id_contract','=',$request->id_contract_document)->first();
      // dd($contract);
      $contract->user_approved = session('tokenUser');
      $send = $contract->save();
      $send_two = $msg->save();
     $response['status'] = 200;
      if($send && $send_two){
       return $response;
      }
      else{
       $response['status']= 300;
       return $response;
      }
    }else{
      $response['status']= 300;
       return $response;
    }



  }

   public function upload_payment(Request $request)
  {
    $result="";
    
    if ($request->hasFile('myfile')) {
      try {
        $storage = new StorageClient([
          'keyFilePath' => base_path() . '/public/credentials.json',
        ]);
        $bucketName = 'signuplatamm';
        $bucket = $storage->bucket($bucketName);
        $carpeta = 'Comprobantes/col/';
        // $filename = $request->file('myfile')->getClientOriginalName();
        $filename= $request->id_contract_upload.'.pdf';
        Storage::put('public/uploads/' . $filename, fopen($request->file('myfile'), 'r+'));

        $filepath = storage_path('app/public/uploads/' . $filename);
        $object = $bucket->upload(
          fopen($filepath, 'r'),
          [
            'name' => $carpeta.$filename,
            'predefinedAcl' => 'publicRead'
          ]
        );
        $response['link']= 'https://storage.googleapis.com/'.$bucketName.'/'.$carpeta.$filename;
        $response['status'] = 200;
        // return redirect('NikkenCMS/IncorporacionWeb')->with('success', 'File is uploaded successfully. File path is: https://storage.googleapis.com/'.$bucketName.'/'.$carpeta.$filename);
         $result = "OK";
        // return $request->file('myfile');
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }

    if($result == "OK"){
      $contract = Contracts::where('id_contract','=',$request->id_contract_upload)->first();
      // dd($contract);
      $contract->payment = intval($request->number_payment);
      $send = $contract->save();
      if($send){
        return $response;
      }
    }
  }

  


  public function save_log_pending(Request $request)
  {
    $msg = new LogsIncorporation();
     $msg->id_contract = $request->id_contract;
     $msg->message = $request->message;
     $msg->user_create = session('tokenUser');


     $send = $msg->save();
    $response['status'] = 200;
     if($send){
      return $response;
     }
     else{
      $response['status']= 300;
      return $response;
     }

  }


}
