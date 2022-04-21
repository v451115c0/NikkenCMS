<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Mis Facturas | NIKKEN Colombia</title>
        <link rel="shortcut icon" href="{{ asset('cmsNikken/images/favicon.ico') }}" />
        <link rel="stylesheet" href="{{ asset('cmsNikken/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('cmsNikken/css/typography.css') }}">
        <link rel="stylesheet" href="{{ asset('cmsNikken/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('cmsNikken/css/responsive.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('fpro/plugins/table/datatable/datatables.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('fpro/plugins/table/datatable/custom_dt_zero_config.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('fpro/plugins/table/datatable/custom_dt_html5.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('fpro/plugins/sweetalerts/sweetalert2.min.css') }}"/>
		<link rel="stylesheet" type="text/css" href="{{ asset('fpro/plugins/sweetalerts/sweetalert.css') }}"/>
    <body class="iq-page-menu-horizontal">
        <div id="loading">
            <div id="loading-center">
            </div>
        </div>
        <div class="wrapper">
            <div class="iq-top-navbar">
                <div class="iq-navbar-custom d-flex align-items-center justify-content-between">
                    <div class="iq-sidebar-logo">
                        <div class="top-logo">
                            <a href="javascript:void(0)" class="logo">
                                <div class="iq-light-logo">
                                    <img src="../NikkenCMS/images/logo.gif" class="img-fluid" alt="">
                                </div>
                                <div class="iq-dark-logo">
                                    <img src="images/logo-dark.gif" class="img-fluid" alt="">
                                </div>
                                <span style="text-transform: uppercase">NIKKEN Colombia</span>
                            </a>
                        </div>
                    </div>
                    <nav class="navbar navbar-expand-lg navbar-light p-0">
                        <ul class="navbar-list">
                            <li>
                                <a href="javascript:void(0)" class="search-toggle iq-waves-effect d-flex align-items-center">
                                <img src="https://services.nikken.com.mx/fpro/img/flags/colombia.png" class="img-fluid rounded mr-3" alt="user">
                                </a>
                                <div class="iq-sub-dropdown iq-user-dropdown">
                                    <div class="iq-card shadow-none m-0">
                                        <div class="iq-card-body p-0 ">
                                            <div class="bg-primary p-3">
                                                <h5 class="mb-0 text-white line-height">Hello Nik jone</h5>
                                            </div>
                                            <div class="d-inline-block w-100 text-center p-3">
                                                <a class="btn btn-primary dark-btn-primary" href="sign-in.html" role="button">Salir<i class="ri-login-box-line ml-2"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div id="content-page" class="content-page">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                        <div class="iq-card-body">
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12">
                                                    <div class="d-flex align-items-center mb-3 mb-lg-0">
                                                        <div class="rounded-circle iq-card-icon iq-bg-primary dark-icon-light mr-3"> <i class="ri-file-copy-2-line"></i></div>
                                                        <div class="text-left">
                                                            <h4>Facturas del mes</h4>
                                                            <p class="mb-0"><b>120</b></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                                        <div class="iq-card-body">
                                            <h3 class="mb-4">Listado de mis facturas</h3>
                                            <div class="table-responsive">
                                                <table class="table mb-0 table-borderless" id="misFacturasTable">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Documento</th>
                                                            <th scope="col">No. Asesor de Bienestar</th>
                                                            <th scope="col">No. Docuemtno</th>
                                                            <th scope="col">Fecha de operación</th>
                                                            <th scope="col">Folio</th>
                                                            <th scope="col" class="text-center">Descargar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @for ($i = 0; $i < 10; $i++)
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td class="text-center">
                                                                    <a href="javascript:void(0)" title="Descargar PDF de mi factura">
                                                                        <div class="badge badge-pill badge-success">
                                                                            <h4>
                                                                                <i class="ri-file-pdf-line"></i>
                                                                            </h4>
                                                                        </div>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endfor
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="iq-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><a href="privacy-policy.html">Privacy Policy</a></li>
                            <li class="list-inline-item"><a href="terms-of-service.html">Terms of Use</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-6 text-right">
                        Copyright 2020 <a href="#">Vito</a> All Rights Reserved.
                    </div>
                </div>
            </div>
        </footer>
        <nav class="iq-float-menu">
            <input type="checkbox" href="#" class="iq-float-menu-open" name="menu-open" id="menu-open" />
            <label class="iq-float-menu-open-button" for="menu-open">
            <span class="lines line-1"></span>
            <span class="lines line-2"></span>
            <span class="lines line-3"></span>
            </label>
            <button class="iq-float-menu-item bg-info"  data-toggle="tooltip" data-placement="top" title="Direction Mode" data-mode="rtl"><i class="ri-text-direction-r"></i></button>
            <button class="iq-float-menu-item bg-danger"  data-toggle="tooltip" data-placement="top" title="Color Mode" id="dark-mode" data-active="true"><i class="ri-sun-line"></i></button>
            <button class="iq-float-menu-item bg-warning" data-toggle="tooltip" data-placement="top" title="Comming Soon"><i class="ri-palette-line"></i></button> 
        </nav>
        <script src="{{ asset('cmsNikken/js/jquery.min.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/customizer.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/popper.min.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/jquery.magnific-popup.min.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/smooth-scrollbar.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/animated.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/custom.js') }}"></script>
        <script src="{{ asset('fpro/plugins/table/datatable/datatables.js') }}"></script>
        <script src="{{ asset('fpro/plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('fpro/plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
        <script src="{{ asset('fpro/plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('fpro/plugins/table/datatable/button-ext/buttons.print.min.js') }}"></script>
	    <script src="{{ asset('fpro/plugins/sweetalerts/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('fpro/facturasCol.js') }}"></script>
    </body>
</html>
