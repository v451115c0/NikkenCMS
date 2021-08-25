@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
<ol class="breadcrumb bg-primary">
    <li class="breadcrumb-item"><a href="{{ url('NikkenCMS/home') }}" class="text-white"><i class="ri-home-4-line mr-1 float-left"></i>Home</a></li>
    <li class="breadcrumb-item active text-white" aria-current="page">Agregar kit de inicio</li>
</ol>
<div class="row">
    <div class="col-lg-6 col-md-6">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <h4 class="card-title text-center w-8">Código</h4>
            </div>
            <div class="iq-card-body">
                <input type="text" class="form-control mb-3" id="sap_code" data-funcion="getDataUser()">
                <button class="btn btn-primary" onclick="addKitInicioTV();">Buscar</button>
                <button class="btn iq-bg-danger" onclick="$('#sap_code').val(''); enabled($('#sap_code')); $('#kitVal, #nKitVal').text('');">Limpiar</button>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title text-center w-100">Kit: <span id="sap_code_info"></span></h4>
                </div>
            </div>
            <div class="iq-card-body">
                <center>
                    <div id="loadingData"></div>
                </center>
                <div class="row" id="userKitInfo">
                    <div class="col-12 mb-3"><b>Venta a agregar kit: <br><span id="kitVal"></span></b></div>
                    <hr>
                    <div class="col-12"><b>Se agrego kit: <br><span id="nKitVal"></b></span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection