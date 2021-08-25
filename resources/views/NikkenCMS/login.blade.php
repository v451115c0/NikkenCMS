<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from designreset.com/preview-equation/default/user_login_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 04 May 2021 22:18:06 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>CMS NIKKEN</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('fpro/img/favicon.ico') }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
    <link href="{{ asset('fpro/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('fpro/css/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('fpro/css/users/login-2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('fpro/css/ui-kit/notification/notify.css') }}" rel="stylesheet" type="text/css" />
</head>
<body class="login">
    <div class="blur-bg"></div>
    <div class="form-login">
        <div class="row">
            <div class="col-md-12 text-center mb-3">
                <img alt="logo" src="{{ asset('fpro/img/min-logo-nikken-white.png') }}" class="theme-logo" width="30%">
                <h5 id="userName" class="text-black"></h5>
            </div>
            <div class="col-md-12">
                <label for="inputEmail" class="sr-only">User</label>
                <div class="input-group mb-3" id="userInput">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="icon-inputEmail"><i class="flaticon-user-7"></i> </span>
                    </div>
                    <input type="text" id="inputEmail" class="form-control" placeholder="Usuario" aria-describedby="inputEmail">
                    <button class="btn rounded-circle btn-rounded ml-3" onclick="showPasswordInput();"><i class="flaticon-arrow-left flaticon-circle-p"></i></button>
                </div>

                <label for="inputPassword" class="sr-only">Password</label>                
                <div class="input-group mb-1" id="passwordInput">
                    <button class="btn rounded-circle btn-rounded mr-3" id="goBack" onclick="hidePasswordInput();"><i class="flaticon-left-arrow-12 flaticon-circle-p"></i></button>
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="icon-inputPassword"><i class="flaticon-key-2"></i> </span>
                    </div>
                    <input type="password" id="inputPassword" class="form-control" placeholder="Contraseña" aria-describedby="inputPassword">
                    <button class="btn rounded-circle btn-rounded ml-3" id="loginBtn" onclick="login();"><i class="flaticon-arrow-left flaticon-circle-p"></i></button>
                </div>
                <center>
                    <div id="validating"></div>
                </center>
            </div>
        </div>
    </div>   
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('fpro/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('fpro/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('fpro/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('cmsNikken/js/cmsNikken.js') }}"></script>
    <script src="{{ asset('fpro/js/ui-kit/notification/notify.js') }}"></script>
</body>
</html>