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
            </div>
            <div class="iq-card-body">
                <div class="row" >
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Mes de descarga</label>
                            <select class="form-control form-control-sm mb-3" id="mesConsultaTabFilter">
                                <option value="todos" selected disabled>Selecciona un mes de consulta</option>
                                <option value="todos">Todos los meses</option>
                                @php
                                    $añoAct = date('o');
                                    $añoAct = $añoAct;
                                    for($i=date('o'); $i>=$añoAct; $i--){
                                        for ($e=12; $e>=1; $e--){
                                            $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                                            $mes=$meses[$e - 1];
                                            if($i == date("Y")){
                                                if($e <= date("n")){
                                                    echo '
                                                        <option value="'. $i . '-0'. $e .'">'.$mes.' '.$i.'</option>
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
                                                        <option value="'.$i . '-0'. $e.'">'.$mes.' '.$i.'</option>
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
                            <label>Plataforma:</label>
                            <select class="form-control form-control-sm mb-3" id="plataformaTabFilter">
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>País:</label>
                            <select class="form-control form-control-sm mb-3" id="paisTabFilter">
                                <option value="latam" selected>Todos los países</option>
                                <option value="CHL">CHL</option>
                                <option value="CL">CL</option>
                                <option value="COL">COL</option>
                                <option value="CRI">CRI</option>
                                <option value="ECU">ECU</option>
                                <option value="GTM">GTM</option>
                                <option value="MEX">MEX</option>
                                <option value="PAN">PAN</option>
                                <option value="PER">PER</option>
                                <option value="SLV">SLV</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Rango:</label>
                            <select class="form-control form-control-sm mb-3" id="rangoTabFilter">
                                <option value="todos">Todos los rangos</option>
                                <option value="BRC">BRC</option>
                                <option value="DIA">DIA</option>
                                <option value="DIR">DIR</option>
                                <option value="DRL">DRL</option>
                                <option value="EXE">EXE</option>
                                <option value="ORO">ORO</option>
                                <option value="PLA">PLA</option>
                                <option value="PLO">PLO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group row">
                            <label class="control-label col-sm-3 align-self-center mb-0" for="clave">palabra clave:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="clave" name="clave" placeholder="palabra clave para filtrado (opcional)" required="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-2">
                        <a class="btn dark-icon btn-primary rounded-pill text-white mr-3 ml-3" onclick="getDatattableMetricas();">Actualizar</a>
                    </div>
                </div>
                <div class="table-responsive" >
                    <table class="table mb-0 table-borderless table-sm" id="tabMetricas">
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
                
            </div>
        </div>
    </div>
</div>
@endsection