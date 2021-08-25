// Funciones generales
$("#passwordInput").hide();
$("#inputEmail").focus();
$("#inputEmail").val('');
//$("#sap_code").keypress(validateNumber);
$("#userInfoMK").hide();
$("#nNombreMNK, #btnnNombreMNK").hide();
$(document).ready(function() {
    $('.dropify').dropify();
});
var userType = {
    'CI': 'Influencer',
    'CLUB': 'Miembro'
}
var countries = { 
    1: 'COL', 
    2: 'MEX', 
    3: 'PER', 
    4: 'ECU', 
    5: 'PAN', 
    6: 'GTM', 
    7: 'SLV', 
    8: 'CRI', 
    10: 'CHL'
};

function showLoadingIcon(element){
    element.addClass('lds-hourglass');
}

function hideLoadingIcon(element){
    element.removeClass('lds-hourglass');
}

/*function validateNumber(event) {
    var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46 || event.keyCode === 13) {
        var funcion = event;
        return console.log(funcion);
    } 
    else if (key < 48 || key > 57) {
        return false;
    }
    else {
        return true;
    }
}*/

// Login
function login(){
    var user = $("#inputEmail").val().trim();
    var pass = $("#inputPassword").val().trim();
    var token = $("#_token").val();
    if(user === '' && pass === ''){
        required('Todos los campos requeridos');
    }
    else{
        var data = { 
            user: user,
            pass: pass,
            token: token
        }
        $.ajax({
            type: "get",
            url: "authLogin",
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function(){
                disabled($("#goBack"));
                disabled($("#inputPassword"));
                disabled($("#loginBtn"));
                $("#validating").addClass('lds-hourglass');
            },
            success: function (response) {
                if(response === '1'){
                    $(location).attr('href', 'home');
                }
                else{
                    $("#validating").removeClass('lds-hourglass');
                    required('Valida tus credenciales');
                    enabled($("#goBack"));
                    enabled($("#inputPassword"));
                    enabled($("#loginBtn"));
                }
            },
            error: function(){
                $("#validating").removeClass('lds-hourglass');
                required('Error al validar datos');
                enabled($("#goBack"));
                enabled($("#inputPassword"));
                enabled($("#loginBtn"));
            }
        });
    }
}

function hidePasswordInput(){
    $("#userInput").show(500);
    $("#passwordInput").hide(500);
    $("#inputPassword").val('');
    $("#userName").text('');
}

function showPasswordInput(){
    if($("#inputEmail").val().trim() === ''){
        required('Todos los campos requeridos');
    }
    else{
        $("#userInput").hide(500);
        $("#inputPassword").focus();
        $("#passwordInput").show(500);
    }
}

$('#inputEmail').keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        if($("#inputEmail").val().trim() === ''){
            required('Todos los campos requeridos');
        }
        else{
            showPasswordInput();
            $("#inputPassword").focus();
            $("#userName").text($("#inputEmail").val());
        }
    }
});

$('#inputPassword').keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        if($("#inputPassword").val().trim() === ''){
            required('Todos los campos requeridos');
        }
        else{
            login();
        }
    }
});

function disabled(control){
    control.attr('disabled', true);
}

function enabled(control){
    control.attr('disabled', false);
}

function required(msg){
    var error = {
        'title': 'Error',
            'style': 'flat',
        'message': msg,
        'icon': 'danger-3',
    };
    
    var n4 = new notifi(error);
    n4.show();
    timeout();
}

var timeHide = "";
function timeout(){
    timeHide = setTimeout(function(){
        $("#notify-holster").empty();
        clearTimeout(timeHide);
    }, 8000);
}

/*=== home ===*/
function number_format(number, decimals, dec_point, thousands_point) {
    if (number == null || !isFinite(number)) {
        throw new TypeError("number is not valid");
    }
    if (!decimals) {
        var len = number.toString().split('.').length;
        decimals = len > 1 ? len : 0;
    }
    if (!dec_point) {
        dec_point = '.';
    }
    if (!thousands_point) {
        thousands_point = ',';
    }
    number = parseFloat(number).toFixed(decimals);
    number = number.replace(".", dec_point);
    var splitNum = number.split(dec_point);
    splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
    number = splitNum.join(dec_point);
    return number;
}

function top5Activos(plataforma){
    $("#platformName").text(plataforma);
    $.ajax({
        type: "get",
        url: "/NikkenCMSpro/getActions",
        data: {
            action: 'top5Activos',
            parameters: {
                plataforma: plataforma
            }
        },
        beforeSend: function(){
            $("div[id=loadingIcon]").addClass('lds-hourglass');
            $("#contentTop5Activos").empty();
            $("#graphVisitas").empty();
        },
        success: function (response) {
            if(response === 'error'){
                $("#loadingIcon").removeClass('lds-hourglass');
                var html = '<div class="alert text-white bg-danger" role="alert">' +
                                '<div class="iq-alert-text"><a href="login">Inicar sesión</a></div>' +
                            '</div>';
                $("#contentTop5Activos").html(html);
                $("#loadingIcon").removeClass('lds-hourglass');
            }
            else{
                $("#loadingIcon").removeClass('lds-hourglass');
                var html = "";
                for(let x = 0; x < response.length; x++){
                    html += '<li class="d-flex mb-1 align-items-center p-1 sell-list border-success rounded">' +
                                '<div class="user-img img-fluid">' +
                                    '<img src="../cmsNikken/images/user/02.jpg" alt="story-img" class="img-fluid rounded-circle avatar-40">' +
                                '</div>' +
                                '<div class="media-support-info ml-1">' +
                                    '<h6>Código: ' + response[x]['Associateid'] + '</h6>' +
                                '</div>' +
                                '<div class="iq-card-header-toolbar d-flex align-items-center">' +
                                    '<div class="badge badge-pill badge-success">Acciones: ' + number_format(response[x]['Acciones'], 0) + '</div>' +
                                '</div>' +
                            '</li>';
                }
                $("#contentTop5Activos").html(html);
                graphVisitas(plataforma);
            }
        },
        error: function(){
            $("#loadingIcon").removeClass('lds-hourglass');
            var html = '<div class="alert text-white bg-danger" role="alert">' +
                            '<div class="iq-alert-text">No se pudieron cargar datos</div>' +
                            '<button type="button" class="close" onclick="top5Activos(\'' + $("#actvitieSite").val() + '\');">' +
                                'Reintentar' +
                            '</button>' +
                        '</div>';
            $("#contentTop5Activos").html(html);
        }
    });
}
if ( $("#contentTop5Activos").length > 0 ) {
    top5Activos($("#actvitieSite").val());
}

function graphVisitas(plataforma){
    $("#graphVisitas").empty();
    $.ajax({
        type: "get",
        url: "/NikkenCMSpro/getActions",
        data: {
            action: 'graphVisitas',
            parameters: {
                plataforma: plataforma
            }
        },
        beforeSend: function(){
            options = {
                chart: {
                    height: 350,
                    type: "bar"
                },
                plotOptions: {
                    bar: {
                        horizontal: !1,
                        columnWidth: "90%",
                        endingShape: "rounded"
                    }
                },
                dataLabels: {
                    enabled: !1
                },
                stroke: {
                    show: !0,
                    width: 2,
                    colors: ["transparent"]
                },
                colors: ["#827af3", "#27b345", "#b47af3"],
                series: [
                    {
                        name: "",
                        data: [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ]
                    },
                ],
                xaxis: {
                    categories: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]
                },
                yaxis: {
                    title: {
                        text: "Número de Visitas"
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(e) {
                            return "Usuarios activos: " + number_format(e, 0)
                        }
                    }
                }
            };
            (chart = new ApexCharts(document.querySelector("#graphVisitas"), options)).render();
            chart.destroy();
            chart.destroy();
            $("div[id=loadingIconGraph]").text('Actualizando datos...');
        },
        success: function (response) {
            $("div[id=loadingIconGraph]").text('');
            chart.destroy();
            if(response === 'error'){
                var html = '<div class="alert text-white bg-danger" role="alert">' +
                                '<div class="iq-alert-text"><a href="login">Inicar sesión</a></div>' +
                            '</div>';
                $("#graphVisitas").html(html);
            }
            else{
                options = {
                    chart: {
                        height: 350,
                        //type: "area"
                        type: "bar"
                    },
                    plotOptions: {
                        bar: {
                            horizontal: !1,
                            columnWidth: "90%",
                            endingShape: "rounded"
                        }
                    },
                    dataLabels: {
                        enabled: !1
                    },
                    stroke: {
                        show: !0,
                        width: 2,
                        colors: ["transparent"]
                    },
                    colors: ["#827af3", "#27b345", "#b47af3"],
                    series: [
                        {
                            name: "",
                            data: [response[1][0]['total'], response[2][0]['total'], response[3][0]['total'], response[4][0]['total'], response[5][0]['total'], response[6][0]['total'], response[7][0]['total'], response[8][0]['total'], response[9][0]['total'], response[10][0]['total'], response[11][0]['total'], response[12][0]['total']]
                        },
                    ],
                    xaxis: {
                        categories: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]
                    },
                    yaxis: {
                        title: {
                            text: "Número de Visitas"
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function(e) {
                                return "Usuarios activos: " + number_format(e, 0)
                            }
                        }
                    }
                };
                (chart = new ApexCharts(document.querySelector("#graphVisitas"), options)).render();
            }
        },
        error: function(){
            $("div[id=loadingIconGraph]").text('');
            var html = '<div class="alert text-white bg-danger" role="alert">' +
                            '<div class="iq-alert-text">No se pudieron cargar datos</div>' +
                            '<button type="button" class="close" onclick="graphVisitas(\'' + $("#actvitieSite").val() + '\');">' +
                                'Reintentar' +
                            '</button>' +
                        '</div>';
            $("#graphVisitas").html(html);
        }
    });
}

function validateQuery(){
    var query = $("#queryval").val();

    if (query.indexOf('FROM') != -1) {
        var tableName = query.substr(query.indexOf('FROM'), query.length).trim();
        tableName = tableName.split(' ');
        tableName = tableName[1].trim();
        showQueryLog('Validando Query...');
        getHeaderTableQuery(tableName);
    }
    else{
        alert('No se encuentra el texto buscado');
    }
}

function getHeaderTableQuery(tableName){
    $.ajax({
        type: "get",
        url: "getActions",
        data: {
            action: 'getHeaderTableQuery',
            parameters: {
                tableName: tableName
            }
        },
        beforeSend: function(){
            $("#tabQueryHeaders").empty();
            showQueryLog('Obteniendo estructura de la tabla...');
        },
        success: function (response) {
            var ths = "";
            //var dataJson = JSON.parse(json);
            for(let x = 0; x < response.length; x++){
                ths += "<th scope='col'>" + response[x]['Field'] + "</th>";
                //dataJson['data'].push
            }
            $("#tabQueryHeaders").html(ths);
            //console.log(dataJson);
            ejecQueryFromWeb();
        }
    });
}

function ejecQueryFromWeb(){
    var query = $("#queryval").val();
    $.ajax({
        type: "get",
        url: "getActions",
        data: {
            action: 'ejecQueryFromWeb',
            parameters: {
                query: window.btoa(query.trim())
            }
        },
        dataType: 'json',
        beforeSend: function(){
            showQueryLog('Mostrando datos...');
        },
        success: function (response) {
            var val = [];
            for(let x = 0; x < response.length; x++){
                console.log(response[0]);
            }
            var dataa = [
                    [
                        48111,
                        "2012-10-24 00:00:00",
                        "2",
                        1,
                        "CI",
                        9845903,
                        "{\"StartKit\":5}",
                        "BLANCO ORTIZ  PAULA",
                        "Diamante Real",
                        "Distrito Federal",
                        9280403,
                        "GARZA GONZALEZ  CARMEN LUCIA",
                        "nikkenmailpv@gmail.com",
                        "5522670210",
                        "5522670210",
                        1,
                        0,
                        0,
                        1,
                        0,
                        0,
                        1,
                        0,
                        0,
                        "wBhYGY9Y7IZX0uKmCsRLPvQrRjYyuhZX",
                        "2021-05-07 23:55:41",
                        null,
                        1
                    ]
                ]
            $('#tabQuery').DataTable({
                destroy: true,
                paging: false,
                data: dataa,
                dom: '<"row"<"col s12 m12 l12 xl12"<"row"<"col-md-6"B><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5 mb-md-0 mb-5"i><"col-md-7"p>>> >',
                buttons: {
                    buttons: [
                        { 
                            extend: 'excel', 
                            className: 'btn btn-primary', 
                            text:"<img src='https://services.nikken.com.mx/retos/img/excel.png' width='15px'></img> Exportar a Excel",
                        },
                    ]
                },
            });
        }
    });
}

function showQueryLog(event){
    $("#queryLog").text('');
    $("#queryLog").text(event);
}

/*=== MOKUTEKI ===*/
function getDataSaleMK(){
    var sap_code = $("#sap_code").val();
    $.ajax({
        type: "get",
        url: "/NikkenCMSpro/getActions",
        data: {
            action: 'getDataSaleMK',
            parameters: {
                sap_code: sap_code
            }
        },
        beforeSend: function(){
            $("#loadingDataSale").empty();
            showLoadingIcon($("#loadingDataSale"));
            $("#sap_code_info").text(sap_code);
            $("#userInfoMK").hide(1000);
            $("#nameUserMK, #idUserMK, #emaiUserMK, #paisUserMK, #sap_codeUserMK, #typeUserMK, #rankUserMK, #registUserMK, #updateUserMK, #statusUserMK, #lockedUserMK, #idVentaMK").text('');
        },
        success: function (response) {
            if(response === 'error'){
                hideLoadingIcon($("#loadingDataSale"));
                var html = '<div class="alert text-white bg-danger" role="alert">' +
                                '<div class="iq-alert-text"><a href="login">Inicar sesión</a></div>' +
                            '</div>';
                $("#loadingDataSale").html(html);
            }
            else{
                $("#nNombreMNK, #btnnNombreMNK").hide();
                hideLoadingIcon($("#loadingDataSale"));
                $("#nameUserMK").text(response['user'][0]['name']);
                $("#idUserMK").text(response['user'][0]['id']);
                $("#emaiUserMK").text(response['user'][0]['email']);
                $("#paisUserMK").text(countries[response['user'][0]['country_id']]);
                $("#sap_codeUserMK").text(response['user'][0]['sap_code']);
                $("#typeUserMK").text(userType[response['user'][0]['client_type']]);
                $("#rankUserMK").text(response['user'][0]['rank']);
                $("#registUserMK").text(response['user'][0]['created_at']);
                $("#updateUserMK").text(response['user'][0]['updated_at']);
                $("#statusUserMK").text((response['user'][0]['status'] == 1) ? 'Activo': 'inactivo');
                $("#lockedUserMK").text((response['user'][0]['locked'] == 0) ? 'No': 'Si');
                $("#idVentaMK").text(response['products'][0]['sale_id']);
                $("#userInfoMK").show(1000);
                getDataSaleMKSale(response['user'][0]['id']);
                getDataSaleMKProducts(response['products'][0]['sale_id']);
            }
        },
        error: function(){
            hideLoadingIcon($("#loadingDataSale"));
            var html = '<div class="alert text-white bg-danger" role="alert">' +
                            '<div class="iq-alert-text">No se pudieron cargar datos</div>' +
                            '<button type="button" class="close" onclick="getDataSaleMK();">' +
                                'Reintentar' +
                            '</button>' +
                        '</div>';
            $("#loadingDataSale").html(html);
            $("#userInfoMK").hide(1000);
        }
    });
}

function getDataSaleMKSale(idUserMK){
    $("#dataSaleMK").DataTable({
        destroy: true,
        lengthChange: false,
        ordering: true,
        info: false,
        destroy: true,
        ajax: "/NikkenCMSpro/getActions?action=getDataSaleMKSale&parameters[user_id]=" + idUserMK,
        columns: [
            { data: 'id', className: 'text-center' },
            { data: 'reference_code', className: 'text-center' },
            { data: 'user_id', className: 'text-center' },
            { data: 'country_id', className: 'text-center' },
            { data: 'type_of_sale', className: 'text-center' },
            { data: 'status', className: 'text-center' },
            { data: 'subtotal', className: 'text-center' },
            { data: 'tax', className: 'text-center' },
            { data: 'total', className: 'text-center' },
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
        },
        createdRow: function( row, data, dataIndex ){
            console.log("estatus: " + data['status'] + " | venta: " + data['id']);
            if(data['status'] === "pagada"){
                $(row).children(':nth-child(6)').addClass('bg-success');
            }
            else if(data['status'] === "standby"){
                $(row).children(':nth-child(6)').addClass('bg-warning');
            }
            else if(data['status'] === "cancelada"){
                $(row).children(':nth-child(6)').addClass('bg-danger');
            }
            else if(data['status'] === "abierta"){
                $(row).children(':nth-child(6)').addClass('bg-info');
            }
        }
    });
}

function getDataSaleMKPayment(){

}

function getDataSaleMKProducts(idVentaMK){
    $("#dataSaleProductMK").DataTable({
        destroy: true,
        lengthChange: false,
        ordering: true,
        info: false,
        destroy: true,
        ajax: "/NikkenCMSpro/getActions?action=getDataSaleMKProducts&parameters[sale_id]=" + idVentaMK,
        columns: [
            { data: 'sale_id', className: 'text-center' },
            { data: 'sku', className: 'text-center' },
            { data: 'name', className: 'text-center' },
            { data: 'quantity', className: 'text-center' },
            { data: 'price', className: 'text-center' },
            { data: 'subtotal', className: 'text-center' },
            { data: 'tax', className: 'text-center' },
            { data: 'total', className: 'text-center' },
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
        }
    });
}

// Cambio de nombre MyNIKKEN
function getDataUser(){
    var sap_code = $("#sap_code").val();
    if(sap_code.trim() === ''){
        swal({
            title: '',
            icon: 'info',
            html:'Ingresa el sap_code o código de influencer',
            type: 'info',
            padding: '2em',
            allowOutsideClick: false,
            allowEscapeKey: false,
        })
    }
    else{
        $.ajax({
            type: "get",
            url: "/NikkenCMSpro/getActions",
            data: {
                action: 'getDataUser',
                parameters: {
                    sap_code: sap_code
                }
            },
            beforeSend: function(){
                $('#sap_code_info, #nameUserMNK, #nNameUserMNK').text('');
                $("#loadingDataSale").empty();
                showLoadingIcon($("#loadingDataSale"));
                $("#nNombreMNK, #btnnNombreMNK").hide(1000);
                $("#sap_code_info").text(sap_code);
                disabled($("#sap_code"));
            },
            success: function (response) {
                if(response === 'error'){
                    hideLoadingIcon($("#loadingDataSale"));
                    var html = '<div class="alert text-white bg-danger" role="alert">' +
                                    '<div class="iq-alert-text"><a href="login">Inicar sesión</a></div>' +
                                '</div>';
                    $("#loadingDataSale").html(html);
                }
                else{
                    hideLoadingIcon($("#loadingDataSale"));
                    $("#nNombreMNK, #btnnNombreMNK").show(1000);
                    $("#nameUserMNK").text(response[0]['AssociateName']);
                }
            },
            error: function(){
                hideLoadingIcon($("#loadingDataSale"));
                var html = '<div class="alert text-white bg-danger" role="alert">' +
                                '<div class="iq-alert-text">No se pudieron cargar datos</div>' +
                                '<button type="button" class="close" onclick="getDataSaleMK();">' +
                                    'Reintentar' +
                                '</button>' +
                            '</div>';
                $("#loadingDataSale").html(html);
                $("#userInfoMK").hide(1000);
            }
        });
    }
}

function changeNameMNK(origen){
    var sap_code = $("#sap_code").val();
    var nuevoNombre = $("#nNombreMNK").val();
    if(sap_code.trim() === '' || nuevoNombre.trim() === '' && origen === ''){
        swal({
            title: '',
            icon: 'info',
            html:'Ingresa el un nombre',
            type: 'info',
            padding: '2em',
            allowOutsideClick: false,
            allowEscapeKey: false,
        })
    }
    else{
        $.ajax({
            type: "get",
            url: "/NikkenCMSpro/getActions",
            data: {
                action: 'changeNameMNK',
                parameters: {
                    sap_code: sap_code,
                    nuevoNombre: nuevoNombre,
                    origen: origen
                }
            },
            beforeSend: function(){
                $("#loadingDataSale").empty();
                showLoadingIcon($("#loadingDataSale"));
            },
            success: function (response) {
                if(response === 'error'){
                    hideLoadingIcon($("#loadingDataSale"));
                    var html = '<div class="alert text-white bg-danger" role="alert">' +
                                    '<div class="iq-alert-text"><a href="login">Inicar sesión</a></div>' +
                                '</div>';
                    $("#loadingDataSale").html(html);
                }
                else{
                    hideLoadingIcon($("#loadingDataSale"));
                    $("#nNameUserMNK").text(response[0]['AssociateName']);
                    swal({
                        title: '',
                        icon: 'ok',
                        html:'Se cambio el nombre correctamente',
                        type: 'success',
                        padding: '2em',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    })
                }
            },
            error: function(){
                hideLoadingIcon($("#loadingDataSale"));
                var html = '<div class="alert text-white bg-danger" role="alert">' +
                                '<div class="iq-alert-text">No se pudieron cargar datos</div>' +
                                '<button type="button" class="close" onclick="getDataSaleMK();">' +
                                    'Reintentar' +
                                '</button>' +
                            '</div>';
                $("#loadingDataSale").html(html);
                $("#userInfoMK").hide(1000);
            }
        });
    }
}

// Notificaciones MyNIKKEN
function saveNotification(){
    var titulo = $("#alertTittle").val();
    var alertDate = $("#alertDate").val();
    var mensaje = $("#alertMsg").val();

    if(titulo.trim() == '' || alertDate.trim() == '' || mensaje.trim() == ''){
        swal({
            title: 'Ups...!',
            text: "Todos los campos son requeridos",
            type: 'error',
            padding: '2em'
        })
    }
    else{
        var data = {titulo:titulo, alertDate:alertDate, mensaje:mensaje};
        $.ajax({
            type: "GET",
            url: "/addNotifyMyNikken",
            data: data,
            cache:false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $("#alertTittle").attr('disabled', true);
                $("#alertDate").attr('disabled', true);
                $("#alertMsg").attr('disabled', true);
                $("#btnsave").attr('disabled', true);
            },
            success: function (response) {
                if(response == 'add'){
                    swal({
                        title: 'OK!',
                        text: "La alerta ha sido guardada y se publicara en breve en MyNikken",
                        type: 'success',
                        padding: '2em'
                    })
                    $("#alertTittle").attr('disabled', false);
                    $("#alertDate").attr('disabled', false);
                    $("#alertMsg").attr('disabled', false);
                    $("#alertTittle").val('');
                    $("#alertDate").val('');
                    $("#alertMsg").val('');
                    $("#btnsave").attr('disabled', false);
                }
                else{
                    swal({
                        title: 'Ups...!',
                        text: "No fue posible guardar la informaciÃ³n.",
                        type: 'error',
                        padding: '2em'
                    })
                }
            },
            fail: function(){
                if(response == 'add'){
                    swal({
                        title: 'OK!',
                        text: "La alerta ha sido guardada y se publicara en breve en MyNikken",
                        type: 'success',
                        padding: '2em'
                    })
                }
                else{
                    swal({
                        title: 'Ups...!',
                        text: "No fue posible guardar la informaciÃ³n.",
                        type: 'error',
                        padding: '2em'
                    })
                }
            }
        });
    }
}

$('#saveAlertForm').on('submit', function(e) {
    console.log('event form init');
    // evito que propague el submit
    e.preventDefault();
    //deshabilitamos el boton para que solo se haga una peticion de registro
    $("#btneventc").attr('disabled', true);

    // agrego la data del form a formData
    var formData = new FormData(this);
    formData.append('_token', $('input[name=_token]').val());
    var titulo = $("#alertTittle").val();
    var alertDate = $("#alertDate").val();
    var mensaje = $("#alertMsg").val();

    if(titulo.trim() == '' || alertDate.trim() == '' || mensaje.trim() == ''){
        swal({
            title: 'Ups...!',
            text: "Todos los campos son requeridos",
            type: 'error',
            padding: '2em'
        })
    }
    else{
        $.ajax({
            type:'POST',
            url: '/addNotifyMyNikken',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $("#alertTittle").attr('disabled', true);
                $("#alertDate").attr('disabled', true);
                $("#alertMsg").attr('disabled', true);
                $("#btnsave").attr('disabled', true);
            },
            success:function(data){
                if(data == 'add'){
                    swal({
                        title: 'OK!',
                        text: "La alerta ha sido guardada y se publicara en breve en MyNikken",
                        type: 'success',
                        padding: '2em'
                    })
                    $("#alertTittle").attr('disabled', false);
                    $("#alertDate").attr('disabled', false);
                    $("#alertMsg").attr('disabled', false);
                    $('#saveAlertForm').trigger('reset');
                    $("#btnsave").attr('disabled', false);
                    setDate();
                }
                else{
                    swal({
                        title: 'Ups...!',
                        text: "No fue posible guardar la informaciÃ³n.",
                        type: 'error',
                        padding: '2em'
                    })
                    $("#alertTittle").attr('disabled', false);
                    $("#alertDate").attr('disabled', false);
                    $("#alertMsg").attr('disabled', false);
                    $("#btnsave").attr('disabled', false);
                    setDate();
                }
            },
            error: function(jqXHR, text, error){
                Swal.fire({
                    type: 'error',
                    title: 'Error',
                    text: 'Error al guardar la informaciÃ³n',
                })
                $("#alertTittle").attr('disabled', false);
                $("#alertDate").attr('disabled', false);
                $("#alertMsg").attr('disabled', false);
                $("#btnsave").attr('disabled', false);
                setDate()
            }
        });
    }
});

$('#comunicados').DataTable({
	destroy: true,
	info: false,
	pageLength: 13,
	searching: true,
    ordering: false,
	lengthChange: false,
    "language": {
        "paginate": { "previous": "<i class='flaticon-arrow-left-1'></i>", "next": "<i class='flaticon-arrow-right'></i>" },
        "info": "Showing page _PAGE_ of _PAGES_"
    }
});

function setDate(){
    Date.prototype.toDateInputValue = (function() {
        var local = new Date(this);
        local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
        return local.toJSON().slice(0,10);
    });
    
    $('#alertDate').val(new Date().toDateInputValue());
}
setDate()

$("#allCountries").change(function () {
    $("#chckCol, #chckMex, #chckPer, #chckCri, #chckEcu, #chckSlv, #chckGtm, #chckPan, #chckChl").prop('checked', $(this).prop("checked"));
});

/* añadir kit a venta para cambio de contraseña */
function addKitInicioTV(){
    var sap_code = $("#sap_code").val();
    $.ajax({
        type: "get",
        url: "/NikkenCMSpro/getActions",
        data: {
            action: 'addKitInicioTV',
            parameters: {
                sap_code: sap_code,
            }
        },
        beforeSend: function(){
            $("#loadingData").empty();
            showLoadingIcon($("#loadingData"));
            $('#kitVal, #nKitVal').text('');
        },
        success: function (response) {
            if(response === 'error'){
                hideLoadingIcon($("#loadingData"));
                var html = '<div class="alert text-white bg-danger" role="alert">' +
                                '<div class="iq-alert-text"><a href="login">Inicar sesión</a></div>' +
                            '</div>';
                $("#loadingData").html(html);
            }
            else{
                hideLoadingIcon($("#loadingData"));
                $("#kitVal").text(response['sale_id']);
                if(response['kit'] == 0){
                    $("#nKitVal").text("No se pudo agregar kit");
                    swal({
                        title: '',
                        icon: 'error',
                        html:'No se pudo agregar kit',
                        type: 'error',
                        padding: '2em',
                    })
                }
                else if(response['kit'] == 0){
                    $("#nKitVal").text("Kit Agregado");
                    swal({
                        title: '',
                        icon: 'ok',
                        html:'Se agrego el kit correctamente',
                        type: 'success',
                        padding: '2em',
                    })
                }
                else{
                    $("#nKitVal").text("Ya se encontro kit");
                    swal({
                        title: '',
                        icon: 'ok',
                        html:'Ya se encontro kit',
                        type: 'success',
                        padding: '2em',
                    })
                }
            }
        },
        error: function(){
            hideLoadingIcon($("#loadingData"));
            var html = '<div class="alert text-white bg-danger" role="alert">' +
                            '<div class="iq-alert-text">No se pudieron cargar datos</div>' +
                            '<button type="button" class="close" onclick="getDataSaleMK();">' +
                                'Reintentar' +
                            '</button>' +
                        '</div>';
            $("#loadingData").html(html);
        }
    })
}

function TVLoadLogVueltaAcasa(){
    var sap_code = $("#sap_code").val();
    $.ajax({
        type: "get",
        url: "/NikkenCMSpro/getActions",
        data: {
            action: 'TVLoadLogVueltaAcasa',
            parameters: {}
        },
        beforeSend: function(){
            $("#loadingData").empty();
            showLoadingIcon($("#loadingData"));
            $('#logCronContent').text('');
        },
        success: function (response) {
            if(response === 'error'){
                hideLoadingIcon($("#loadingData"));
                var html = '<div class="alert text-white bg-danger" role="alert">' +
                                '<div class="iq-alert-text"><a href="login">Inicar sesión</a></div>' +
                            '</div>';
                $("#loadingData").html(html);
            }
            else{
                hideLoadingIcon($("#loadingData"));
                $('#logCronContent').html(response);
            }
        },
        error: function(){
            hideLoadingIcon($("#loadingData"));
            var html = '<div class="alert text-white bg-danger" role="alert">' +
                            '<div class="iq-alert-text">No se pudieron cargar datos</div>' +
                            '<button type="button" class="close" onclick="TVLoadLogVueltaAcasa();">' +
                                'Reintentar' +
                            '</button>' +
                        '</div>';
            $("#loadingData").html(html);
        }
    })
}

function users_cell_phone_update(){
    $('#users_cell_phone_update').DataTable({
        destroy: true,
        lengthChange: false,
        ordering: false,
        info: false,
        ajax: "/NikkenCMSpro/getActions?action=getdataWhatsapp",
        columns: [
            { data: 'id', className: 'text-center' },
            { data: 'sap_code', className: 'text-center' },
            { data: 'nombre', className: 'text-center' },
            { data: 'area_code', className: 'text-center' },
            { data: 'cell_phone', className: 'text-center' },
            { data: 'updated_on_sql_server', className: 'text-center' },
            { data: 'use_as_my_principal_phone', className: 'text-center' },
            { data: 'created_at', className: 'text-center' },
            { data: 'updated_at', className: 'text-center' },
            { 
                data: 'updated_at',
                className: 'text-center',
                render: function(data, type, row){
                    return '<a href="javascript:void(0)" data-toggle="modal" data-target=".modalUpdate" onclick="loadDataWSTVuser(' + row.id + ', ' + row.sap_code + ')"><i class="ri-ball-pen-fill text-success pr-1" style="font-size: 20px"></i></a>' +
                    '<a href="javascript:void(0)"><i class="ri-delete-bin-5-line text-danger" style="font-size: 20px" onclick="deleteDataWSTV((' + row.id + '))"></i></a>';
                }
            },
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
            "paginate": { "previous": "<i class='flaticon-arrow-left-1'></i>", "next": "<i class='flaticon-arrow-right'></i>" },
            "info": "Showing page _PAGE_ of _PAGES_",
            "search": "Buscar",
        }
    });
}
users_cell_phone_update();

function loadDataWSTVuser(id, sap_code){
    $.ajax({
        type: "get",
        url: "/NikkenCMSpro/getActions",
        data: {
            action: 'loadDataWSTVuser',
            parameters: {
                id: id
            }
        },
        beforeSend: function(){
            $("#loadingdiv").empty();
            showLoadingIcon($("#loadingdiv"));
            $("#sap_code").val(sap_code);
            $("#idCI").val(sap_code);
            $("#country_code, #numberCell, #Update_On_SQL_server, #Use_As_My_Principal_phone").attr('disabled', false);
            $("#sap_code, #nombreuser, #country_code, #numberCell, #Update_On_SQL_server, #Use_As_My_Principal_phone").val('');
        },
        success: function (response) {
            if(response === 'error'){
                hideLoadingIcon($("#loadingdiv"));
                var html = '<div class="alert text-white bg-danger" role="alert">' +
                                '<div class="iq-alert-text"><a href="login">Inicar sesión</a></div>' +
                            '</div>';
                $("#loadingdiv").html(html);
            }
            else{
                hideLoadingIcon($("#loadingdiv"));
                $("#idreg").val(id);
                $("#sap_code").val(response[0]['sap_code']);
                $("#nombreuser").val(response[0]['nombre']);
                $("#country_code").val(response[0]['area_code']);
                $("#numberCell").val(response[0]['cell_phone']);
                $("#Update_On_SQL_server").val(response[0]['updated_on_sql_server']);
                $("#Use_As_My_Principal_phone").val(response[0]['use_as_my_principal_phone']);
            }
        },
        error: function(){
            hideLoadingIcon($("#loadingdiv"));
            var html = '<div class="alert text-white bg-danger" role="alert">' +
                            '<div class="iq-alert-text">No se pudieron cargar datos</div>' +
                            '<button type="button" class="close" onclick="loadDataWSTVuser(' + id + ',' + sap_code + ')">' +
                                'Reintentar' +
                            '</button>' +
                        '</div>';
            $("#loading").html(html);
        }
    });
}

function updateDataWSTV(){
    var id = $("#idreg").val();
    var country_code = $("#country_code").val();
    var numberCell = $("#numberCell").val();
    var Update_On_SQL_server = $("#Update_On_SQL_server").val();
    var Use_As_My_Principal_phone = $("#Use_As_My_Principal_phone").val();

    $.ajax({
        type: "get",
        url: "/NikkenCMSpro/getActions",
        data: {
            action: 'updateDataWSTV',
            parameters: {
                id: id,
                country_code: country_code,
                numberCell: numberCell,
                Update_On_SQL_server: Update_On_SQL_server,
                Use_As_My_Principal_phone: Use_As_My_Principal_phone,
            }
        },
        beforeSend: function(){
            $("#loadingdiv").empty();
            showLoadingIcon($("#loadingdiv"));
            $("#sap_code").val(sap_code);
            $("#country_code, #numberCell, #Update_On_SQL_server, #Use_As_My_Principal_phone").attr('disabled', true);
        },
        success: function (response) {
            if(response === 'error'){
                hideLoadingIcon($("#loadingdiv"));
                var html = '<div class="alert text-white bg-danger" role="alert">' +
                                '<div class="iq-alert-text"><a href="login">Inicar sesión</a></div>' +
                            '</div>';
                $("#loadingdiv").html(html);
            }
            else{
                hideLoadingIcon($("#loadingdiv"));
                $("#idreg, #sap_code, #nombreuser, #country_code, #numberCell, #Update_On_SQL_server, #Use_As_My_Principal_phone").val('');
                $("#closeModal").trigger('click');
                users_cell_phone_update();
            }
        },
        error: function(){
            hideLoadingIcon($("#loadingdiv"));
            var html = '<div class="alert text-white bg-danger" role="alert">' +
                            '<div class="iq-alert-text">No se pudieron cargar datos</div>' +
                            '<button type="button" class="close" onclick="loadDataWSTVuser(' + id + ',' + sap_code + ')">' +
                                'Reintentar' +
                            '</button>' +
                        '</div>';
            $("#loading").html(html);
        }
    });
}

function deleteDataWSTV(id){
    swal({
        title: "Eliminar registro",
        text: "¿Desea el registro de la tabla?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        padding: '2em',
        reverseButtons: true
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                type: "get",
                url: "/NikkenCMSpro/getActions",
                data: {
                    action: 'deleteDataWSTV',
                    parameters: {
                        id: id,
                    }
                },
                beforeSend: function(){
                    $("#log").text('Procesando...');
                },
                success: function (response) {
                    $("#log").text('');
                    swal({
                        title: '',
                        icon: 'ok',
                        html:'Se elimino el registro correctamente.' ,
                        type: 'success',
                        padding: '2em',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    });
                    users_cell_phone_update();
                },
                fail: function(){
                    $("#log").text('');
                    swal({
                        title: '',
                        icon: 'info',
                        html:'Ocurrio un error al eliminar el registro, intente nuevamente',
                        type: 'error',
                        padding: '2em',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    })
                }
            });
        }
    })
}