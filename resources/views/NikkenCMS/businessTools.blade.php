@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
<ol class="breadcrumb bg-primary">
    <li class="breadcrumb-item"><a href="{{ url('NikkenCMS/home') }}" class="text-white"><i class="ri-home-4-line mr-1 float-left"></i>Home</a></li>
    <li class="breadcrumb-item active text-white" aria-current="page">Herramientas de Negocio</li>
</ol>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <form id="nSite" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
            <input type="hidden"name="urlAction" id="urlAction" value="{{ route('addMicroSitio') }}">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <h4 class="card-title text-center w-8">Nuevo Micro-sitio</h4>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group row">
                                <label class="control-label col-sm-2 align-self-center mb-0" for="nameNSite">Micrositio*:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="nameNSite" name="nameNSite" placeholder="Nombre Micrositio" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group row">
                                <label class="control-label col-sm-2 align-self-center mb-0" for="URLNSite">URL sitio*:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="URLNSite" name="URLNSite" placeholder="https://... / #modal" required>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="concatSap_codeNSite" name="concatSap_codeNSite" checked>
                                        <label class="custom-control-label" for="concatSap_codeNSite">Concatenar Código ABI/Influencer</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="form-group row">
                                <label class="control-label col-sm-2 align-self-center mb-0" for="dateStartNSite">Mostrar desde:</label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" class="form-control" id="dateStartNSite" name="dateStartNSite">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="form-group row">
                                <label class="control-label col-sm-2 align-self-center mb-0" for="dateEndNSite">Mostrar hasta:</label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" class="form-control" id="dateEndNSite" name="dateEndNSite">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="form-group row">
                                <div class="custom-control custom-switch pt-1 ml-3">
                                    <input type="checkbox" class="custom-control-input" id="unlimitedNDate" name="unlimitedNDate" onchange="catchCheckboxDates()">
                                    <label class="custom-control-label" for="unlimitedNDate">De manera Indefinida <b>* Solo si son sitios permanentes.</b></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group row">
                                <label class="control-label col-sm-2 align-self-center mb-0" for="tagNSite">Tags* <br><b>(separado por coma ','</b>):</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="tagNSite" name="tagNSite" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group row">
                                <label class="control-label col-sm-2 align-self-center mb-0" for="onClickNSite">Evento al click <br><b>(separar por ';')</b>:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="onClickNSite" name="onClickNSite">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group mb-1">
                                <label for="iconNsite"></label>
                                <input type="file" class="dropify" data-max-file-size="3M" name="iconNsite"/ required>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title text-center w-100">Países *</h4>
                                    </div>
                                </div>
                                <div class="iq-card-body row">
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div class="form-group mt-2">
                                            <div class="n-chk">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="allCountries" name="allCountries" checked>
                                                    <label class="custom-control-label" for="allCountries">Todos los países</label>
                                                </div>
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckCol" id="chckCol" onchange="$('#allCountries').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">Colombia</span>
                                                </label>
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckMex" id="chckMex" onchange="$('#allCountries').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">México</span>
                                                </label>
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckPer" id="chckPer" onchange="$('#allCountries').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">Perú</span>
                                                </label>
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckCri" id="chckCri" onchange="$('#allCountries').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">Costa Rica</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div class="form-group mt-2">
                                            <div class="n-chk">
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckEcu" id="chckEcu" onchange="$('#allCountries').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">Ecuador</span>
                                                </label>
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckSlv" id="chckSlv" onchange="$('#allCountries').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">El Salvador</span>
                                                </label>
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckGtm" id="chckGtm" onchange="$('#allCountries').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">Guatemala</span>
                                                </label>
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckPan" id="chckPan" onchange="$('#allCountries').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">Panamá</span>
                                                </label>
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckChl" id="chckChl" onchange="$('#allCountries').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">Chile</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group mb-1">
                                <label for="iconNsite">Aploica para estos Códigos <b>(separados por coma)</b>:</label>
                                <textarea id="allowedUsersNsite" name="allowedUsersNsite" rows="5" class="form-control" required></textarea>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="forAllNSite" name="forAllNSite" checked onchange="catchCheckboxForAllUsers()">
                                    <label class="custom-control-label" for="forAllNSite">Aplica para todos los códigos</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title text-center w-100">Aplica para los rangos *:</h4>
                                    </div>
                                </div>
                                <div class="iq-card-body row">
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div class="form-group mt-2">
                                            <div class="n-chk">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="allRanges" name="allRanges" checked>
                                                    <label class="custom-control-label" for="allRanges">Todos los rangos</label>
                                                </div>
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckDIR" id="chckDIR" onchange="$('#allRanges').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">DIR</span>
                                                </label>
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckEXE" id="chckEXE" onchange="$('#allRanges').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">EXE</span>
                                                </label>
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckPLA" id="chckPLA" onchange="$('#allRanges').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">PLA</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div class="form-group mt-2">
                                            <div class="n-chk">
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckORO" id="chckORO" onchange="$('#allRanges').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">ORO</span>
                                                </label>
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckPLO" id="chckPLO" onchange="$('#allRanges').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">PLO</span>
                                                </label>
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckDIA" id="chckDIA" onchange="$('#allRanges').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">DIA</span>
                                                </label>
                                                <label class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="chckDRL" id="chckDRL" onchange="$('#allRanges').prop('checked', false)" checked>
                                                    <span class="custom-control-label"></span><span class="new-chk-content">DRL</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <center>
                        <input type="submit" class="btn btn-primary mb-1 mt-2 w-50 pt-2 pb-2" id="btnsave" name="btnsave" value="Guardar alerta">
                        <br>
                        <div id="loadingIcon"></div>
                    </center>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

