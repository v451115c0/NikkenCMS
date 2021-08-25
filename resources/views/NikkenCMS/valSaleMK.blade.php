@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
<ol class="breadcrumb bg-primary">
    <li class="breadcrumb-item"><a href="{{ url('NikkenCMS/home') }}" class="text-white"><i class="ri-home-4-line mr-1 float-left"></i>Home</a></li>
    <li class="breadcrumb-item active text-white" aria-current="page">Validar venta por MOKUTEKI</li>
</ol>
<div class="row">
    <div class="col-lg-6 col-md-6">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <h4 class="card-title text-center w-8">Código del incoporado</h4>
            </div>
            <div class="iq-card-body">
                <input type="text" class="form-control mb-3" id="sap_code">
                <button class="btn btn-primary" onclick="getDataSaleMK()">Buscar</button>
                <button class="btn iq-bg-danger">Limpiar</button>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title text-center w-100">Datos del código: <span id="sap_code_info"></span></h4>
                </div>
            </div>
            <div class="iq-card-body">
                <center>
                    <div id="loadingDataSale"></div>
                </center>
                <div class="row" id="userInfoMK">
                    <div class="col-12"><h3 id="nameUserMK">BLANCO ORTIZ  PAULA</h3></div>
                    <div class="col-3">ID:</div>
                    <div class="col-9"><span id="idUserMK">11765</span></div>
                    <div class="col-3">Email:</div>
                    <div class="col-9"><span id="emaiUserMK">nikkenmailpv@gmail.com</span></div>
                    <div class="col-3">País:</div>
                    <div class="col-9"><span id="paisUserMK">2</span></div>
                    <div class="col-3">Sponsor:</div>
                    <div class="col-9"><span id="sap_codeUserMK">9280403</span></div>
                    <div class="col-3">Tipo:</div>
                    <div class="col-9"><span id="typeUserMK">Influencer</span></div>
                    <div class="col-3">Rango:</div>
                    <div class="col-9"><span id="rankUserMK">Diamante Real</span></div>
                    <div class="col-3">Registro:</div>
                    <div class="col-9"><span id="registUserMK">2021-06-08 23:11:25</span></div>
                    <div class="col-3">Actualización:</div>
                    <div class="col-9"><span id="updateUserMK">2017-02-08 02:58:06</span></div>
                    <div class="col-3">Estatus:</div>
                    <div class="col-9"><span id="statusUserMK">Activo</span></div>
                    <div class="col-3">Bloqueado:</div>
                    <div class="col-9"><span id="lockedUserMK">No</span></div>
                    <div class="col-3">Venta MK:</div>
                    <div class="col-9"><span id="idVentaMK">0</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title text-center w-100">Datos de Venta</h4>
                </div>
            </div>
            <div class="iq-card-body">
                <div class="table-responsive">
                    <table id="dataSaleMK" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th># Venta</th>
                                <th>Referencia</th>
                                <th>Código de usuario</th>
                                <th>País</th>
                                <th>Tipo de venta</th>
                                <th>Estatus</th>
                                <th>Subtotal</th>
                                <th>IVA</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title text-center w-100">Datos de Pago</h4>
                </div>
            </div>
            <div class="iq-card-body">
                <div class="table-responsive">
                    <table id="dataSalePayMK" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th># Venta</th>
                                <th>País</th>
                                <th>Metodo de pago</th>
                                <th>Proveedor de paog</th>
                                <th>Monto de pago</th>
                                <th>Código de confirmación</th>
                                <th>Estatus</th>
                                <th>Fechade pago</th>
                                <th>Actualización</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title text-center w-100">Productos de la venta</h4>
                </div>
            </div>
            <div class="iq-card-body">
                <div class="table-responsive">
                    <table id="dataSaleProductMK" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th># Venta</th>
                                <th>SKU</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                                <th>IVA</th>
                                <th>Montot total</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection