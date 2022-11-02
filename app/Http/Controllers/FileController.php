<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        return view('file');
    }

    public function store(Request $request)
    {
        if ($request->hasFile('myfile')) {
            try {
                $storage = new StorageClient([
                    'keyFilePath' => base_path() . '/public/credentials.json',
                ]);

                // $bucketName = env('GOOGLE_CLOUD_STORAGE_BUCKET');
                $bucketName = 'signuplatamm';
                $bucket = $storage->bucket($bucketName);

                //get filename with extension
                $filename = $request->file('myfile')->getClientOriginalName();
                $carpeta = 'Comprobantes/col/';
                //get filename without extension
                // $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                // //get file extension
                // $extension = $request->file('myfile')->getClientOriginalExtension();

                //filename to store
                //  $filenametostore =$filenamewithextension;
                //  $filenametostore = 'carpeta/'.$filenamewithextension;
                // $filenametostore= $filenamewithextension;

                // Storage::put('public/uploads/'. $filenametostore, fopen($request->file('myfile'), 'r+'));
                Storage::put('public/uploads/' . $filename, fopen($request->file('myfile'), 'r+'));

                $filepath = storage_path('app/public/uploads/' . $filename);

                // $object = $bucket->upload(
                //     fopen($filepath, 'r'),
                //     [
                //         'name' => $filenametostore,
                //         'predefinedAcl' => 'publicRead'
                //     ]
                // );

                $object = $bucket->upload(
                    fopen($filepath, 'r'),
                    [
                        'name' => $carpeta.$filename,
                        'predefinedAcl' => 'publicRead'
                    ]
                );

                // TEst
                // $objectName = $filenametostore;

                // $file = fopen($request->file('myfile'), 'r+');
                // $bucket = $storage->bucket($bucketName);
                // $object = $bucket->upload('/Comprobantes/col/' . $request->file('myfile'), [
                //     'name' => $objectName
                // ]);

                
                // delete file from local disk
                // Storage::delete('public/uploads/'. $filenametostore);

             return redirect('upload')->with('success', "File is uploaded successfully. File path is: https://storage.googleapis.com/$bucketName/$filename");
                
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}
