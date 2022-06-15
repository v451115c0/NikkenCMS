@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
                <h4 class="card-title text-center w-8">Actualización whatsapp TV</h4>
            </div>
            <div class="iq-card-body">
                <div class="col-lg-12">
                    <span id="log"></span>
                </div>
                <table class="table table-sm table-striped w-100 table-responsive" id="users_cell_phone_update">
                    <thead>
                       <tr>
                          <th scope="col">#</th>
                          <th scope="col">Código Usuario</th>
                          <th scope="col">Nombre</th>
                          <th scope="col">Código país</th>
                          <th scope="col">Número</th>
                          <th scope="col">Update_On_SQL_server</th>
                          <th scope="col">Use_As_My_Principal_phone</th>
                          <th scope="col">Fecha registro</th>
                          <th scope="col">Fecha actualización</th>
                          <th scope="col">acciones</th>
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
                                            <label for="nombreuser">Nombre</label>
                                            <input type="text" class="form-control" id="nombreuser" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="country_code">Código de país</label>
                                            <input type="text" class="form-control" id="country_code">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="numberCell">Númerp celular</label>
                                            <input type="text" class="form-control" id="numberCell">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="Update_On_SQL_server">Update_On_SQL_server</label>
                                            <input type="text" class="form-control" id="Update_On_SQL_server">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="Use_As_My_Principal_phone">Use_As_My_Principal_phone</label>
                                            <input type="text" class="form-control" id="Use_As_My_Principal_phone">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModal">Cancelar</button>
                                <button type="button" class="btn btn-primary" onclick="updateDataWSTV()">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection