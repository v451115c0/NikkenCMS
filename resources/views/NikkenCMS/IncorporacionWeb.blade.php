@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
    <ol class="breadcrumb bg-primary">
        <li class="breadcrumb-item"><a href="{{ url('NikkenCMS/home') }}" class="text-white"><i
                    class="ri-home-4-line mr-1 float-left"></i>Home</a></li>
        <li class="breadcrumb-item active text-white" aria-current="page">Incorporacion Web</li>
    </ol>
    <input type="hidden" id="url" value="{{ asset('') }}">
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
                    <h6 class="title_table mb-4">Nuevos Asesores de Bienestar o Clientes preferentes que han terminado el
                        proceso
                        de incorporación,<span class="font-weight-bold"> por favor, verifique que la información del
                            contrato sea correcta.</span></h6>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="check_contrato_all" value="option1">
                        <label class="form-check-label" for="check_contrato_all">Mostrar todos los Registros</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="check_contrato_with_file" value="option2">
                        <label class="form-check-label" for="check_contrato_with_file">Mostrar registros con
                            archivos</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="check_contrato_without_file" value="option3">
                        <label class="form-check-label" for="check_contrato_without_file">Mostrar registros sin
                            archivos</label>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class=" js-exportable table table-bordered table-striped table-hover dataTable"
                            id="pendiente_contratos">
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
                    <div class="row">
                        <input type="hidden" name="id_contract_message" id="id_contract_message">
                        <div class="container">
                            <p>A continuación ingresa los comentarios relacionados al seguimiento de la incorporación, para
                                enviar el correo electrónico al asesor selecciona la opción <span
                                    class="font-weight-bold">Enviar comentario al correo electrónico del Asesor</span>.</p>


                            <div class="form-group">
                                <textarea class="form-control input-message" name="message_incorporate_pending" id="message_incorporate_pending"
                                    rows="6" placeholder="Ingresa tu mensaje aquí..."></textarea>
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="send_mail_sponsor"
                                        value="option1">
                                    <label class="form-check-label" for="send_mail_sponsor">Enviar Comentario al Correo
                                        electrónico del Asesor</label>
                                </div>
                            </div>





                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="update_log_pending">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Subir Comprobante-->
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
                    <form id="upload_payment_form" action="{{ url('upload_payment') }}" method="post"
                        enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="">Por favor, seleccione el comprobante de pago en formato PDF</label>
                        </div>
                        @csrf
                        <p><input type="file" accept=".pdf" name="myfile" id="myfile" /></p>
                        <input type="hidden" name="id_contract_upload" id="id_contract_upload">

                        <div class="form-group">
                            <input type="text" class="form-control input-message" name="number_payment"
                                id="number_payment" placeholder="Ingresa el número de confirmación de pago" />
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    {{-- <button type="submit" id="btn_update_payment" class="btn btn-primary">Guardar</button> --}}
                    <button type="button" id="btn_update_payment" class="btn btn-primary"
                        onclick="upload_payment()">Guardar</button>

                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Subir Archivos Contrato --}}
    <div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true" id="modal_document_contract">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Validar Documentos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form id="upload_document_form" action="" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group">
                                    <label for="view_documents_contract"><a href=""
                                            id="view_documents_contract">Revisar Documentos</a></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="">Por favor,seleccione sus archivos en caso que los documentos no
                                        sean los correctos</label>
                                </div>
                            </div>

                            <div class="row">
                                <p><input type="file" name="documents_contract[]"
                                        id="documents_contract[]" multiple="" /></p>
                                <input type="hidden" name="id_contract_document" id="id_contract_document">
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <p class="font-weight-bold mb-1 mt-4">Comentarios adicional para aprobar el contrato:
                                    </p>
                                    <textarea class="form-control input-message" name="message_update_contract" rows="6"
                                        placeholder="Ingresa tu mensaje aquí..."></textarea>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- <div class="form-group">
                            <input type="text" class="form-control input-message" name="number_payment" id="number_payment"
                                 placeholder="Ingresa el número de confirmación de pago"/>
                        </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    {{-- <button type="submit" id="btn_update_payment" class="btn btn-primary">Guardar</button> --}}
                    <button type="button" class="btn btn-success" onclick="upload_documents()" id="btn_aprobar_contrato">Aprobar</button>


                </div>
            </div>
        </div>
    </div>
@endsection
