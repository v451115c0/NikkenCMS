<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
	<title>NIKKEN | Depuraciones más de 7 días</title>
	<link rel="icon" type="image/x-icon" href="{{ asset('fpro/img/favicon.ico') }}"/>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('fpro/bootstrap/css/bootstrap.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('fpro/plugins/flaticon/style.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('fpro/css/pages/landing-page/styleediCA.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ asset('fpro/plugins/table/datatable/datatables.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('fpro/plugins/table/datatable/custom_dt_zero_config.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('fpro/plugins/table/datatable/custom_dt_html5.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ asset('fpro/plugins/sweetalerts/sweetalert2.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('fpro/plugins/sweetalerts/sweetalert.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('fpro/css/ui-kit/custom-sweetalert.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('fpro/css/aos.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('fpro/maincss/Centroamerica/centroamerica.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ asset('fproh/plugins/sweetalerts/sweetalert2.min.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('fproh/plugins/sweetalerts/sweetalert.css') }}"/>
	<style>
		body{
			background-image: unset !important;
		}
	</style>
</head>
<body>
	
</div>
<div style="height: 150px; overflow: hidden;">
	<svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 40%; width: 100%;">
		<path d="M0.00,92.27 C216.83,192.92 304.30,8.39 500.00,109.03 L500.00,0.00 L0.00,0.00 Z" style="stroke: none;  fill: #011633;"></path>
	</svg>
</div>

<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-4 text-center m-md-auto">
		
<button class="btn btn-success" type="button" onclick="DepurarRegistro();">Depuración Masiva</button>
	</div>
</div>


<div id="servicesWrapper" class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
								<div class="container">
									<div class="row">
										<div class="col-xl-12 col-lg-12 col-md-12 site-header-inner mb-5 table-responsive">
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



<div id="miniFooterWrapper" class="mt-5">
	<div class="container">
		<div class="row">
			<div class="col-xl-12 col-lg-12 col-md-12">
				<div class="position-relative">
					<div class="arrow text-center">
						<span class="flaticon-double-arrow-up"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-5 mx-auto col-lg-6 col-md-6 site-content-inner text-md-left text-center copyright align-self-center">
						<p class="mt-md-0 mt-4 mb-0">© {{ Date('Y')}} Rally Centroamerica <a href="https://nikkenlatam.com/oficina-virtual/mexico/">Nikken Latinoamérica</a>.</p>
					</div>
					<div class="col-xl-5 mx-auto col-lg-6 col-md-6 site-content-inner text-md-right text-center align-self-center"></div>
				</div>
			</div>		
		</div>
	</div>
</div>






		
	</body>
	<script src="{{ asset('fpro/js/libs/jquery-3.1.1.min.js') }}"></script>
	<script src="{{ asset('fpro/bootstrap/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('fpro/js/pages/landing-page/script.js') }}"></script>
	<script src="{{ asset('fpro/plugins/table/datatable/datatables.js') }}"></script>
	<script src="{{ asset('fpro/plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
	<script src="{{ asset('fpro/plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
	<script src="{{ asset('fpro/plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
	<script src="{{ asset('fpro/plugins/table/datatable/button-ext/buttons.print.min.js') }}"></script>
	<script src="{{ asset('fpro/js/aos.js') }}"></script>
	<script src="{{ asset('fproh/plugins/sweetalerts/sweetalert2.min.js') }}"></script>
	<script src="{{ asset('fpro/mainjs/Centroamerica/centroamerica.js?v=1.2') }}"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-153578520-1');
	</script>
	
	
	</html>