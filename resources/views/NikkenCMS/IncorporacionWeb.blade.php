@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
    <ol class="breadcrumb bg-primary">
        <li class="breadcrumb-item"><a href="{{ url('NikkenCMS/home') }}" class="text-white"><i
                    class="ri-home-4-line mr-1 float-left"></i>Home</a></li>
        <li class="breadcrumb-item active text-white" aria-current="page">Incorporacion Web</li>
    </ol>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Depurar Correo de CLIENTE TV</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <center>
                        <div id="loadingIcon"></div>
                    </center>
                    <div class="form-group">
                        <label for="clientMail">Correo de Cliente</label>
                        <input type="email" class="form-control" id="clientMail" placeholder="correo a liberar...">
                    </div>
                    <button type="button" class="btn btn-primary" onclick="depClient($('#clientMail').val());">Validar y
                        depurar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-lg-12 col-md-12">
            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Incorporaciones pendientes de asignar patrocinador</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <h6 class="title_table">Nuevo prospecto que ha seleccionado la opción <span
                            class="font-weight-bold">"Conocí Nikken a través de un Asesor de Bienestar. Sin embargo, no
                            conozco su código."</span>, por favor contáctelo para retomar la incorporación por la opción
                        Retomar incorporación.</h6>
                    <div class="table-responsive mt-4">
                        <table class=" js-exportable table table-bordered table-striped table-hover dataTable"
                            id="pendientes_asignar">
                            <thead>
                                <td>Fecha</td>
                                <td>Días</td>
                                <td>País</td>
                                <td>Tipo</td>
                                <td>Nombre</td>
                                <td>Correo</td>
                                <td>Teléfono</td>
                                <td>Ciudad/Estado</td>
                                <td></td>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Incorporaciones pendientes de pago</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="table-responsive">
                        <table class=" js-exportable table table-bordered table-striped table-hover dataTable"
                            id="pendientes_pago">
                            <thead>
                                <td>Fecha</td>
                                <td>Días</td>
                                <td>País</td>
                                <td>Tipo</td>
                                <td>Código</td>
                                <td>Nombre</td>
                                <td>Correo</td>
                                <td>Teléfono</td>
                                <td>Patrocinador</td>
                                <td>Accion</td>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Incorporaciones pendientes de verificar Contrato</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <h6 class="title_table">Nuevos Asesores de Bienestar o Clientes preferentes que han terminado el proceso
                        de incorporación,<span class="font-weight-bold"> por favor, verifique que la información del
                            contrato sea correcta.</span></h6>
                    <div class="table-responsive mt-4">
                        <table class=" js-exportable table table-bordered table-striped table-hover dataTable"
                            id="pendiente_contratos">
                            <thead>
                                <td>Fecha</td>
                                <td>Días</td>
                                <td>País</td>
                                <td>Tipo</td>
                                <td>Nombre</td>
                                <td>Correo</td>
                                <td>Teléfono</td>
                                <td>Ciudad/Estado</td>
                                <td></td>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Comentario-->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true" id="comment_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Agregar Comentario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Subir Archivos-->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true" id="upload_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Subir Comprobante de Pago</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <label for="">Por favor, seleccione el comprobante de pago en formato PDF</label>
                   <input type="file" name="payment_upload" id="payment_upload">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
