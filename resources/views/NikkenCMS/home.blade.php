@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
<style>
    #actvitieSite {
        background-color: rgb(196, 191, 191);
    }
    #actvitieSite .form-control {
        border: 1px solid #ccc;
        color: #888ea8;
        font-size: 15px;
    }
    .input-group-text {
        background-color: #f3f4f7;
        border-color: #e9ecef;
        color: #6156ce;
    }
    select.form-control {
        display: inline-block;
        width: 100%;
        height: calc(2.25rem + 2px);
        vertical-align: middle;
        background: #fff url(../fpro/img/arrow-down.png) no-repeat right .75rem center ;
        background-size: 13px 14px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    select.form-control::-ms-expand { display: none; }
</style>
<div class="row">
    <div class="col-lg-6 col-md-6">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <h4 class="card-title text-center w-8">Top 5 activos mensualmente</h4>
            </div>
            <div class="iq-card-header d-flex">
                <div class="form-group w-100">
                    <label for="actvitieSite">Selecciona la plataforma</label>
                    <select class="form-control" id="actvitieSite" onchange="top5Activos(this.value)">
                        <option value="MyNIKKEN_prod">MyNIKKEN_prod</option>
                        <option value="Mokuteki PLUS">Mokuteki PLUS</option>
                    </select>
                </div>
                <a class="btn dark-icon btn-primary rounded-pill text-white mt-3 mr-3 ml-3" onclick="top5Activos($('#actvitieSite').val());">Actualizar</a>
            </div>
            <div class="iq-card-body">
                <center>
                    <div id="loadingIcon"></div>
                </center>
                <ul class="list-inline p-0 m-0" id="contentTop5Activos">
                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title text-center w-100">Usuarios activos por mes</h4>
                </div>
            </div>
            <div class="iq-card-body">
                <div class="iq-card-body">
                    <center>
                        <div id="loadingIconGraph"></div>
                    </center>
                    <div id="graphVisitas"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">Metricas</h4>
                </div>
                <div class="iq-card-header-toolbar d-flex align-items-center">
                    <div class="dropdown">
                        <span class="dropdown-toggle" id="dropdownMenuButton3" data-toggle="dropdown">
                            <i class="ri-more-fill"></i>
                        </span>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton3" style="">
                        <a class="dropdown-item" href="javascript:void(0)"><i class="ri-eye-fill mr-2"></i>View</a>
                        <a class="dropdown-item" href="javascript:void(0)"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                        <a class="dropdown-item" href="javascript:void(0)"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                        <a class="dropdown-item" href="javascript:void(0)"><i class="ri-printer-fill mr-2"></i>Print</a>
                        <a class="dropdown-item" href="javascript:void(0)"><i class="ri-file-download-fill mr-2"></i>Download</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="iq-card-body">
                <div class="row" >
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Mes de descarga</label>
                            <select class="form-control form-control-sm mb-3">
                                <option value="todos" selected disabled>Selecciona un mes de consulta</option>
                                <option value="todos">Todos los registros</option>
                                @php
                                    $añoAct = date('o');
                                    $añoAct = $añoAct - 1;
                                    for($i=date('o'); $i>=$añoAct; $i--){
                                        for ($e=12; $e>=1; $e--){
                                            $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                                            $mes=$meses[$e - 1];
                                            if($i == date("Y")){
                                                if($e <= date("n")){
                                                    echo '
                                                        <option value="'.$i.$e.'">'.$mes.' '.$i.'</option>
                                                    '; 
                                                }
                                            }
                                            else{
                                                if($e < 10){
                                                    echo '
                                                        <option value="'.$i.'0'.$e.'">'.$mes.' '.$i.'</option>
                                                    ';
                                                }
                                                else{
                                                    echo '
                                                        <option value="'.$i.$e.'">'.$mes.' '.$i.'</option>
                                                    '; 
                                                }
                                            }
                                        }
                                    }
                                @endphp
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Small</label>
                            <select class="form-control form-control-sm mb-3">
                                <option selected="">Open this select menu</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Small</label>
                            <select class="form-control form-control-sm mb-3">
                                <option selected="">Open this select menu</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Small</label>
                            <select class="form-control form-control-sm mb-3">
                                <option selected="">Open this select menu</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" >
                    <table class="table mb-0 table-borderless table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Código de usuario</th>
                                <th scope="col">Rango</th>
                                <th scope="col">País</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Plataforma</th>
                                <th scope="col">Acción realizada</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <textarea class="form-control" id="queryval" rows="2"></textarea>
                        </div>
                        <button class="btn btn-primary" onclick="validateQuery()">Ejecutar Query (F5)</button>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <p id="queryLog"></p>
                    </div>
                </div>
                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%"></table>
                <div class="table-responsive mt-4">
                    <table class="table mb-0 table-borderless table-sm table-striped" id="tabQuery">
                        <thead>
                            <tr id="tabQueryHeaders">
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection