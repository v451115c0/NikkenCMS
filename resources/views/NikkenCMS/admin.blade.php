<!doctype html>
<html lang="es">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>CMS Nikken LATAM</title>
        <link rel="shortcut icon" href="{{ asset('cmsNikken/images/favicon.ico') }}" />
        <link rel="stylesheet" href="{{ asset('cmsNikken/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('cmsNikken/css/typography.css') }}">
        <link rel="stylesheet" href="{{ asset('cmsNikken/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('cmsNikken/css/responsive.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="{{ asset('cmsNikken/css/cmsNikken.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('fpro/plugins/table/datatable/datatables.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('fpro/plugins/table/datatable/custom_dt_zero_config.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('fpro/plugins/table/datatable/custom_dt_html5.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('fproh/plugins/sweetalerts/sweetalert2.min.css') }}"/>
		<link rel="stylesheet" type="text/css" href="{{ asset('fproh/plugins/sweetalerts/sweetalert.css') }}"/>

        <link rel="stylesheet" href="{{ asset('fpro/css/dropify/dropify.css') }}">
    </head>
    <body>
        <div class="blur-bg"></div>
        <!-- loader Start -->
        <div id="loading">
            <div id="loading-center">
            </div>
        </div>
        <!-- loader END -->
        <!-- Wrapper Start -->
        <div class="wrapper">
            <!-- Sidebar  -->
            <div class="iq-sidebar">
                <div class="iq-sidebar-logo d-flex justify-content-between">
                    <a href="javascript:void(0)">
                        <div class="iq-light-logo">
                            <img src="{{ asset('fpro/img/min-logo-nikken-black.png') }}" class="img-fluid" alt="">
                        </div>
                    </a>
                    <div class="iq-menu-bt-sidebar">
                        <div class="iq-menu-bt align-self-center">
                            <div class="wrapper-menu">
                                <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
                                <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="sidebar-scrollbar">
                    <nav class="iq-sidebar-menu">
                        <ul id="iq-sidebar-toggle" class="iq-menu">
                            <li class="active">
                                <a href="{{ url('NikkenCMS/home') }}" class="iq-waves-effect"><i class="ri-home-8-line"></i><span>Dashboard</span></a>
                            </li>
                            <li>
                                <a href="#mokuteki" class="iq-waves-effect collapsed"  data-toggle="collapse" aria-expanded="false"><i class="ri-award-fill"></i><span>MOKUTEKI</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                                <ul id="mokuteki" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                    <li><a href="{{ url('NikkenCMS/valSaleMK') }}"><i class="ri-shopping-cart-line"></i>Validar ventas</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#myNIKKEN" class="iq-waves-effect collapsed"  data-toggle="collapse" aria-expanded="false"><i class="ri-funds-fill"></i><span>MyNikken</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                                <ul id="myNIKKEN" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                    <li><a href="{{ url('NikkenCMS/mnkChangeName') }}"><i class="ri-user-settings-fill"></i>Cambio de Nombre</a></li>
                                    <li>
                                        <ul>
                                            <li>
                                                <a href="#sub-menu" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-notification-4-fill"></i><span>Notificaciones</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                                                <ul id="sub-menu" class="iq-submenu iq-submenu-data collapse">
                                                    <li><a href="{{ url('NikkenCMS/mnkNotify') }}"><i class="ri-notification-4-fill"></i>Crear Notificación</a></li>
                                                    <li><a href="{{ url('NikkenCMS/mnkNotify') }}"><i class="ri-notification-4-fill"></i>Ver notificaciones</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#tv" class="iq-waves-effect collapsed"  data-toggle="collapse" aria-expanded="false"><i class="ri-store-2-line"></i><span>TV</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                                <ul id="tv" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                    <li><a href="{{ url('NikkenCMS/tvKitInicio') }}"><i class="ri-briefcase-fill"></i>Kit de inicio</a></li>
                                    <li><a href="{{ url('NikkenCMS/tvVueltaCasa') }}"><i class="ri-home-8-line"></i>Vuelta a Casa</a></li>
                                    <li><a href="{{ url('NikkenCMS/updateWs') }}"><i class="ri-whatsapp-line"></i>WhatsApp TV</a></li>
                                </ul>
                            </li>
                            <li><a href="todo.html" class="iq-waves-effect" aria-expanded="false"><i class="ri-chat-check-line"></i><span>Todo</span></a></li>
                            <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Components</span></li>
                            <li>
                                <a href="#menu-level" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-record-circle-line"></i><span>Menu Level</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                                <ul id="menu-level" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                    <li><a href="javascript:void(0)"><i class="ri-record-circle-line"></i>Menu 1</a></li>
                                    <li>
                                        <a href="javascript:void(0)"><i class="ri-record-circle-line"></i>Menu 2</a>
                                        <ul>
                                            <li>
                                                <a href="#sub-menu" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-play-circle-line"></i><span>Sub-menu</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                                                <ul id="sub-menu" class="iq-submenu iq-submenu-data collapse">
                                                    <li><a href="javascript:void(0)"><i class="ri-record-circle-line"></i>Sub-menu 1</a></li>
                                                    <li><a href="javascript:void(0)"><i class="ri-record-circle-line"></i>Sub-menu 2</a></li>
                                                    <li><a href="javascript:void(0)"><i class="ri-record-circle-line"></i>Sub-menu 3</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a href="javascript:void(0)"><i class="ri-record-circle-line"></i>Menu 3</a></li>
                                    <li><a href="javascript:void(0)"><i class="ri-record-circle-line"></i>Menu 4</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                    <div class="p-3"></div>
                </div>
            </div>
            <!-- TOP Nav Bar -->
            <div class="iq-top-navbar">
                <div class="iq-navbar-custom">
                    <div class="iq-sidebar-logo">
                        <div class="top-logo">
                            <a href="javascript:void(0)" class="logo">
                                <div class="iq-light-logo">
                                    <img src="{{ asset('fpro/img/min-logo-nikken-black.png') }}" class="img-fluid" alt="">
                                </div>
                                <div class="iq-dark-logo">
                                    <img src="{{ asset('fpro/img/min-logo-nikken-white.png') }}" class="img-fluid" alt="">
                                </div>
                                <span>vito</span>
                            </a>
                        </div>
                    </div>
                    <nav class="navbar navbar-expand-lg navbar-light p-0">
                        <div class="navbar-left">
                            <div class="iq-search-bar d-none d-md-block">
                                <h3>NIKKEN LATINOAMÉRICA</h3>
                            </div>
                        </div>
                        <div class="iq-menu-bt align-self-center">
                            <div class="wrapper-menu">
                                <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
                                <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
                            </div>
                        </div>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ml-auto navbar-list">
                                
                            </ul>
                        </div>
                        <ul class="navbar-list">
                            <li>
                                <a href="javascript:void(0)" class="search-toggle iq-waves-effect d-flex align-items-center bg-primary rounded">
                                    <img src="{{ asset('cmsNikken/images/user/1.jpg') }}" class="img-fluid rounded mr-3" alt="user">
                                    <div class="caption">
                                        <h6 class="mb-0 line-height text-white">{{ session('tokenUser') }}</h6>
                                    </div>
                                </a>
                                <div class="iq-sub-dropdown iq-user-dropdown">
                                    <div class="iq-card shadow-none m-0">
                                        <div class="iq-card-body p-0 ">
                                            <div class="bg-primary p-3">
                                                <h5 class="mb-0 text-white line-height">Hola {{ session('tokenUser') }}</h5>
                                                <span class="text-white font-size-12" hidden>Available</span>
                                            </div>
                                            <a href="javascript:void(0)" class="iq-sub-card iq-bg-primary-hover" hidden>
                                                <div class="media align-items-center">
                                                    <div class="rounded iq-card-icon iq-bg-primary">
                                                        <i class="ri-file-user-line"></i>
                                                    </div>
                                                    <div class="media-body ml-3">
                                                        <h6 class="mb-0 ">My Profile</h6>
                                                        <p class="mb-0 font-size-12">View personal profile details.</p>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)" class="iq-sub-card iq-bg-primary-hover" hidden>
                                                <div class="media align-items-center">
                                                    <div class="rounded iq-card-icon iq-bg-primary">
                                                        <i class="ri-profile-line"></i>
                                                    </div>
                                                    <div class="media-body ml-3">
                                                        <h6 class="mb-0 ">Edit Profile</h6>
                                                        <p class="mb-0 font-size-12">Modify your personal details.</p>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)" class="iq-sub-card iq-bg-primary-hover" hidden>
                                                <div class="media align-items-center">
                                                    <div class="rounded iq-card-icon iq-bg-primary">
                                                        <i class="ri-account-box-line"></i>
                                                    </div>
                                                    <div class="media-body ml-3">
                                                        <h6 class="mb-0 ">Account settings</h6>
                                                        <p class="mb-0 font-size-12">Manage your account parameters.</p>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)" class="iq-sub-card iq-bg-primary-hover" hidden>
                                                <div class="media align-items-center">
                                                    <div class="rounded iq-card-icon iq-bg-primary">
                                                        <i class="ri-lock-line"></i>
                                                    </div>
                                                    <div class="media-body ml-3">
                                                        <h6 class="mb-0 ">Privacy Settings</h6>
                                                        <p class="mb-0 font-size-12">Control your privacy parameters.</p>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="d-inline-block w-100 text-center p-3">
                                                <a class="btn btn-primary dark-btn-primary" href="{{ URL('NikkenCMS/login') }}" role="button">Cerrar Sesión<i class="ri-login-box-line ml-2"></i></a>
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
                    @yield('content')
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="iq-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><a href="javascript:void(0)"></a></li>
                            <li class="list-inline-item"><a href="javascript:void(0)"></a></li>
                        </ul>
                    </div>
                    <div class="col-lg-6 text-right">
                        Copyright {{ Date('Y') }} <a href="javascript:void(0)">NIKKEN LATAM</a>
                    </div>
                </div>
            </div>
        </footer>
        <nav class="iq-float-menu">
            <input type="checkbox" href="javascript:void(0)" class="iq-float-menu-open" name="menu-open" id="menu-open" />
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
        <script src="{{ asset('cmsNikken/js/rtl.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/customizer.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/popper.min.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/jquery.appear.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/countdown.min.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/waypoints.min.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/jquery.counterup.min.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/wow.min.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/apexcharts.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/slick.min.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/select2.min.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/jquery.magnific-popup.min.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/smooth-scrollbar.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/lottie.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/core.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/charts.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/animated.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/kelly.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="{{ asset('cmsNikken/js/custom.js') }}"></script>
        <script src="{{ asset('fpro/plugins/table/datatable/datatables.js') }}"></script>
        <script src="{{ asset('fpro/plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('fpro/plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
        <script src="{{ asset('fpro/plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('fpro/plugins/table/datatable/button-ext/buttons.print.min.js') }}"></script>
	    <script src="{{ asset('fproh/plugins/sweetalerts/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('fpro/js/dropify/dropify.js') }}"></script>
        <script src="{{ asset('cmsNikken/js/cmsNikken.js') }}"></script>
    </body>
</html>
