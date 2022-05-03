@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
<ol class="breadcrumb bg-primary">
    <li class="breadcrumb-item"><a href="{{ url('NikkenCMS/home') }}" class="text-white"><i class="ri-home-4-line mr-1 float-left"></i>Home</a></li>
    <li class="breadcrumb-item active text-white" aria-current="page">Administrar Herramientas de Negocio</li>
</ol>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">Administrar Herramientas de Negocio</h4>
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

<script>
    var flags = {'3': 'peru.png', '2': 'mexico.png', '9': 'mexico.png', '1': 'colombia.png', '10': 'chile.png', '4': 'ecuador.png', '5': 'panama.png', '7': 'salvador.png', '6': 'guatemala.png', '8': 'costarica.png'};

    function Depuraciones(){
        $("#registros").dataTable({
            lengthChange: false,
            ordering: true,
            info: false,
            destroy: true,
            ajax: "/Depuraciones",
            columns: [
                
                { data: 'code', className: 'text-center' },
                { data: 'name', className: 'text-center' },
                { data: 'email', className: 'text-center' },
                { data: 'create_at', className: 'text-center' },
                { data: 'country', className: 'text-center' },
                { data: 'sponsor', className: 'text-center' },
                { 
                    data: 'code',
                    className: 'text-center',
                    "render": function(data, type, row){
                        var email = "\'"+row.email+"\'";
                        
                            btndepurar = '<button class="btn btn-danger" type="button" onclick="DepurarRegistro('+row.code+','+email+');">Depurar</button>';
                        
                        return btndepurar;
                    }
                },
                
                
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
            }
        });
    }
    Depuraciones();

    function DepurarRegistro(codigo,correo){
        $.ajax({
            type: "GET",
            url: '/Depurarmas7dias',
            dataType: "json",
            contentType: "text/json; charset=UTF-8",
            data: {
                codigo: codigo,
                correo: correo
            },
            success: function(data){
                if (data == 0) {
                        alert('no se puede depurar, cuenta con pago');
                }
                else if(data == 1){
                        alert('depurado correctamente');
                }
                else{
                        alert('ocurrio un error');
                }
            },
            error: function(data) {
                    alert('ocurrio un error en la solicitud');
            }
        });
    }
</script>
@endsection


