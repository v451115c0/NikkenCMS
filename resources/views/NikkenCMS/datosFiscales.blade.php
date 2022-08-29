@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <h4 class="card-title text-center w-8">Datos fiscales con diferencia de datos</h4>
            </div>
            <div class="iq-card-body">
                <table class="table table-sm table-striped w-100 table-responsive" id="users_fiscal_updateAlert">
                    <thead>
                       <tr>
                          <th scope="col">#</th>
                          <th scope="col">Id Registro</th>
                          <th scope="col">Código de usuario</th>
                          <th scope="col">Tipo de persona</th>
                          <th scope="col">URL de Archivo</th>
                          <th scope="col">Mensaje de Error</th>
                       </tr>
                    </thead>
                </table>
                <div class="modal fade modalValidatePDFsat" tabindex="-1" role="dialog"  aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Validación PDF vs SAT<span id="idCI"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="loadingdivValidatePDF" style="margin: auto;"></div>
                                <div class="row">
                                    <div class="col-lg-12" id="responseValidate">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <h4 class="card-title text-center w-8">Administrador de datos fiscales</h4>
            </div>
            <div class="iq-card-body">
                <div class="col-lg-12">
                    <span id="log"></span>
                </div>
                <table class="table table-sm table-striped w-100 table-responsive" id="users_fiscal_update">
                    <thead>
                       <tr>
                          <th scope="col">#</th>
                          <th scope="col">Código Usuario</th>
                          <th scope="col">RFC</th>
                          <th scope="col">Tipo de persona</th>
                          <th scope="col">Regimen Fiscal</th>
                          <th scope="col">Regimen Descriptor</th>
                          <th scope="col">Persona Moral Nombre</th>
                          <th scope="col">Persona Fisica Nombre</th>
                          <th scope="col">Persona Fisica Primer Apellido</th>
                          <th scope="col">Persona Fisica Segundo Apellido</th>
                          <th scope="col">Código Postal</th>
                          <th scope="col">Estado</th>
                          <th scope="col">Municipio</th>
                          <th scope="col">Colonia</th>
                          <th scope="col">Código CFDI</th>
                          <th scope="col">Descripción CFDI</th>
                          <th scope="col">Actualizado en SQL</th>
                          <th scope="col">Fecha de Registro</th>
                          <th scope="col">Ultima actualizacion</th>
                          <th scope="col">Comentarios</th>
                          <th scope="col">Archivo adjunto</th>
                          <th scope="col">Acciones</th>
                       </tr>
                    </thead>
                </table>
                <div class="modal fade modalUpdate" tabindex="-1" role="dialog"  aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar datos CI <span id="idCI"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="loadingdiv"></div>
                                <div class="row">
                                    <div class="col-lg-4" hidden>
                                        <div class="form-group">
                                            <label for="idreg"></label>
                                            <input type="text" class="form-control" id="idreg" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="sap_code">Código de usuario</label>
                                            <input type="text" class="form-control" id="sap_code" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="rfc">RFC</label>
                                            <input type="text" class="form-control" id="rfc" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="person_type">Tipo de persona</label>
                                            <input type="text" class="form-control" id="person_type">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="regimen_code">Regimen Fiscal</label>
                                            <input type="text" class="form-control" id="regimen_code">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="regimen_description">Regimen Descriptor</label>
                                            <input type="text" class="form-control" id="regimen_description">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="business_name">Persona Moral Nombre</label>
                                            <input type="text" class="form-control" id="business_name">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="name">Persona Fisica Nombre</label>
                                            <input type="text" class="form-control" id="name">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="last_name1">Persona Fisica primer Apellido</label>
                                            <input type="text" class="form-control" id="last_name1">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="last_name2">Persona Fisica segundo Apellido</label>
                                            <input type="text" class="form-control" id="last_name2">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="cp">Código Postal</label>
                                            <input type="text" class="form-control" id="cp">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="estado">Estado</label>
                                            <input type="text" class="form-control" id="estado">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="municipio">Municipio</label>
                                            <input type="text" class="form-control" id="municipio">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="colonia">Colonia</label>
                                            <input type="text" class="form-control" id="colonia">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="cfdi_code">Código CFDI</label>
                                            <input type="text" class="form-control" id="cfdi_code">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="cfdi_description">Descripción CFDI</label>
                                            <input type="text" class="form-control" id="cfdi_description">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="updated_on_sql_server">Actualizado en SQL <b>(0 = Sin procesar, 1 = Procesado)</b></label>
                                            <input type="text" class="form-control" id="updated_on_sql_server">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="created_at">Fecha de Registro</label>
                                            <input type="text" class="form-control" id="created_at" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="updated_at">Ultima actualizacion</label>
                                            <input type="text" class="form-control" id="updated_at" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!--<a href="" target="_blank" class="btn btn-success" id="getImgNewTab">Ver imagen adjunta</a>-->
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModal">Cancelar</button>
                                <button type="button" class="btn btn-primary" onclick="updateFisData()">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection