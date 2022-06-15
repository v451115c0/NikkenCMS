@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
<ol class="breadcrumb bg-primary">
    <li class="breadcrumb-item"><a href="{{ url('NikkenCMS/home') }}" class="text-white"><i class="ri-home-4-line mr-1 float-left"></i>Home</a></li>
    <li class="breadcrumb-item active text-white" aria-current="page">PDF a Texto</li>
</ol>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">PDF a Texto</h4>
                </div>
            </div>
            <div class="iq-card-body">
                <form action="/getTextFromPDF" method="post">
                    <div class="form-group">
                            <label for="colFormLabelSm">Token</label>
                            <input type="text" class="form-control form-control-sm" name="_token" id="_token" value="{{ csrf_token() }}">
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile" name="customFile">
                            <label class="custom-file-label" for="customFile">Cargar PDF</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="submit" class="btn iq-bg-danger">cancle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


