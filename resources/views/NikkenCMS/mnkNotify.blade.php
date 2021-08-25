@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
<ol class="breadcrumb bg-primary">
    <li class="breadcrumb-item"><a href="{{ url('NikkenCMS/home') }}" class="text-white"><i class="ri-home-4-line mr-1 float-left"></i>Home</a></li>
    <li class="breadcrumb-item active text-white" aria-current="page">Notificaciones MyNikken</li>
</ol>
<form id="saveAlertForm" method="POST" enctype="multipart/form-data">
    <div class="row">
        {{ csrf_field() }}
        <div class="col-lg-6 col-md-6">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <h4 class="card-title text-center w-8">Nueva notificación</h4>
                </div>
                <div class="iq-card-body">
                    <div class="form-group row">
                        <label class="control-label col-sm-2 align-self-center mb-0" for="alertTittle">Titulo*:</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="alertTittle" name="alertTittle" placeholder="Titulo de notificación">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-2 align-self-center mb-0" for="alertDate">Fecha*:</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="alertDate" name="alertDate" placeholder="Titulo de notificación">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title text-center w-100">Agregar link (Opcional)</h4>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <div class="form-group">
                                    <label for="micrositioslct">Micro-sitio:</label>
                                    <select class="form-control-rounded form-control" id="micrositioslct" name="micrositioslct" onchange="setlinkval(this.value)">
                                        <option value="0" selected="">Selecciona el micro-sitio</option>
                                        <option value="https://services.nikken.com.mx/viajeros/">Club Viajeros</option>
                                        <option value="https://services.nikken.com.mx/regactivinf/">Actividad de Influencer</option>
                                        <option value="https://services.nikken.com.mx/influencia30/">Simulador 2.0</option>
                                        <option value="https://services.nikken.com.mx/PlanInfluencia/">Controlador Nikken Challenge</option>
                                        <option value="https://services.nikken.com.mx/viajeros_premium/">Viajeros Premium</option>
                                        <option value="https://services.nikken.com.mx/puntos_connection/">Puntos Conection</option>
                                        <option value="https://services.nikken.com.mx/calculadoraNikken/">Calculadora NIKKEN</option>
                                        <option value="https://services.nikken.com.mx/inc1USD/">Incorporación a 1 Dolar (mokuteki)</option>
                                        <option value="https://services.nikken.com.mx/depuracion/">Renovados 2021</option>
                                        <option value="https://services.nikken.com.mx/retosEspeciales2021/">Retos especiales 2021</option>
                                    </select>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="linkFinal">Liga:</label>
                                    <input type="text" name="linkFinal" id="linkFinal" class="form-control-rounded form-control">
                                </div>
                                <div class="form-group mt-3">
                                    <label for="linkname">Texto a mostrar:</label>
                                    <input type="text" name="linkname" id="linkname" class="form-control-rounded form-control">
                                </div>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch2" name="customSwitch2" checked="">
                                    <label class="custom-control-label" for="customSwitch2">Concatenar Código ABI/Influencer</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="alertMsg">Mensaje*:</label>
                        <textarea class="form-control-rounded form-control" id="alertMsg" name="alertMsg" rows="6" lang="es"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title text-center w-100">Crear nueva notificación</h4>
                    </div>
                </div>
                <div class="iq-card-body row">
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                        <div class="form-group mb-1">
                            <label for="alertFile1">Archivo 1 (opcional)</label>
                            <input type="file" class="dropify" data-max-file-size="3M" name="alertFile1"/>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                        <div class="form-group mb-1">
                            <label for="alertFile2">Archivo 2 (opcional)</label>
                            <input type="file" class="dropify" data-max-file-size="3M" name="alertFile2"/>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                        <div class="form-group mb-1">
                            <label for="alertFile3">Archivo 3 (opcional)</label>
                            <input type="file" class="dropify" data-max-file-size="3M" name="alertFile3"/>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                        <div class="form-group mb-1">
                            <label for="alertFile4">Archivo 4 (opcional)</label>
                            <input type="file" class="dropify" data-max-file-size="3M" name="alertFile4"/>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                        <div class="form-group mb-1">
                            <label for="alertFile5">Archivo 5 (opcional)</label>
                            <input type="file" class="dropify" data-max-file-size="3M" name="alertFile5"/>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                        <div class="form-group mb-1">
                            <label for="alertFile6">Archivo 6 (opcional)</label>
                            <input type="file" class="dropify" data-max-file-size="3M" name="alertFile6"/>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                        <div class="form-group mb-1">
                            <label for="alertFile7">Archivo 7 (opcional)</label>
                            <input type="file" class="dropify" data-max-file-size="3M" name="alertFile7"/>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                        <div class="form-group mb-1">
                            <label for="alertFile8">Archivo 8 (opcional)</label>
                            <input type="file" class="dropify" data-max-file-size="3M" name="alertFile8"/>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12">
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
                                                <input type="checkbox" class="custom-control-input" id="allCountries" name="allCountries">
                                                <label class="custom-control-label" for="allCountries">Todos los países</label>
                                            </div>
                                            <label class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="chckCol" id="chckCol">
                                                <span class="custom-control-label"></span><span class="new-chk-content">Colombia</span>
                                            </label>
                                            <label class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="chckMex" id="chckMex">
                                                <span class="custom-control-label"></span><span class="new-chk-content">México</span>
                                            </label>
                                            <label class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="chckPer" id="chckPer">
                                                <span class="custom-control-label"></span><span class="new-chk-content">Perú</span>
                                            </label>
                                            <label class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="chckCri" id="chckCri">
                                                <span class="custom-control-label"></span><span class="new-chk-content">Costa Rica</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                    <div class="form-group mt-2">
                                        <div class="n-chk">
                                            <label class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="chckEcu" id="chckEcu">
                                                <span class="custom-control-label"></span><span class="new-chk-content">Ecuador</span>
                                            </label>
                                            <label class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="chckSlv" id="chckSlv">
                                                <span class="custom-control-label"></span><span class="new-chk-content">El Salvador</span>
                                            </label>
                                            <label class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="chckGtm" id="chckGtm">
                                                <span class="custom-control-label"></span><span class="new-chk-content">Guatemala</span>
                                            </label>
                                            <label class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="chckPan" id="chckPan">
                                                <span class="custom-control-label"></span><span class="new-chk-content">Panamá</span>
                                            </label>
                                            <label class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="chckChl" id="chckChl">
                                                <span class="custom-control-label"></span><span class="new-chk-content">Chile</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" class="btn btn-primary mb-1 mt-2" id="btnsave" value="Guardar alerta">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection