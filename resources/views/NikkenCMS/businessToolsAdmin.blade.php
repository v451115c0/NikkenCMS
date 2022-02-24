@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
<ol class="breadcrumb bg-primary">
    <li class="breadcrumitem"><a href="{{ url('NikkenCMS/home') }}" class="text-white"><i class="ri-home-4-line mr-1 float-left"></i>Home</a></li>
    <li class="breadcrumitem active text-white" aria-current="page">Herramientas de Negocio</li>
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
                            <table class="table-sm" width="100%">
                                <thead>
                                  <th>#</th>
                                  <th>Heading</th>
                                  <th>Heading</th>
                                  <th>Heading</th>
                                  <th>Heading</th>
                                  <th>Heading</th>
                                  <th>Heading</th>
                                  <th>Heading</th>
                                  <th>Heading</th>
                                  <th>Heading</th>
                                </thead>
                                <tbody>
                                  <tr>
                                    <th>1</th>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                  </tr>
                                  <tr>
                                    <th>2</th>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                  </tr>
                                  <tr>
                                    <th>3</th>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

