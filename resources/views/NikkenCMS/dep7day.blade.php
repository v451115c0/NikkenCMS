@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
<ol class="breadcrumb bg-primary">
    <li class="breadcrumb-item"><a href="{{ url('NikkenCMS/home') }}" class="text-white"><i class="ri-home-4-line mr-1 float-left"></i>Home</a></li>
    <li class="breadcrumb-item active text-white" aria-current="page">Incorporaciones sin pago</li>
</ol>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">Incorporaciones sin pago</h4>
                </div>
            </div>
            <div class="iq-card-body">
                <div class="table-responsive">
                    <table id="registros" class="table table-striped table-bordered table-hover text-center" >
                        <thead>
                            <tr class="text-center registros">
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Fecha de Creación</th>
                                <th>País</th>
                                <th>Patrocinador</th>
                                <th>Acciones</th>	
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection