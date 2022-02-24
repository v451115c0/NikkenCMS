@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
<ol class="breadcrumb bg-primary">
    <li class="breadcrumb-item"><a href="{{ url('NikkenCMS/home') }}" class="text-white"><i class="ri-home-4-line mr-1 float-left"></i>Home</a></li>
    <li class="breadcrumb-item active text-white" aria-current="page">Herramientas de Negocio</li>
</ol>
<div class="row">
    <div class="col-lg-12 col-md-12">
            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <h4 class="card-title text-center w-8">Administrar Micro-sitios</h4>
                </div>
                <div class="iq-card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <b-table-simple responsive>
                                <b-thead>
                                  <b-th>#</b-th>
                                  <b-th>Heading</b-th>
                                  <b-th>Heading</b-th>
                                  <b-th>Heading</b-th>
                                  <b-th>Heading</b-th>
                                  <b-th>Heading</b-th>
                                  <b-th>Heading</b-th>
                                  <b-th>Heading</b-th>
                                  <b-th>Heading</b-th>
                                  <b-th>Heading</b-th>
                                </b-thead>
                                <b-tbody>
                                  <b-tr>
                                    <b-th>1</b-th>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                  </b-tr>
                                  <b-tr>
                                    <b-th>2</b-th>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                  </b-tr>
                                  <b-tr>
                                    <b-th>3</b-th>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                    <b-td>Cell</b-td>
                                  </b-tr>
                                </b-tbody>
                              </b-table-simple>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

