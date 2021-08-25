@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
<ol class="breadcrumb bg-primary">
    <li class="breadcrumb-item"><a href="{{ url('NikkenCMS/home') }}" class="text-white"><i class="ri-home-4-line mr-1 float-left"></i>Home</a></li>
    <li class="breadcrumb-item active text-white" aria-current="page">Log de ejecución "Vuela a Casa"</li>
</ol>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <h4 class="card-title text-center w-8">Log de ejecución</h4>
            </div>
            <div class="iq-card-body">
                <button class="btn btn-primary mb-1" onclick="TVLoadLogVueltaAcasa();">Cargar Log</button>
                <center>
                    <div id="loadingData"></div>
                </center>
                <div id="logCronContent" class="w-100"></div>
            </div>
        </div>
    </div>
</div>
@endsection