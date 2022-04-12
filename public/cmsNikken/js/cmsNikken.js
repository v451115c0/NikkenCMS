// Funciones generales
$("#passwordInput").hide();

$(document).ready(function() {
    $('.dropify').dropify();
    $('#allCountries').prop('checked', true);
    $('#allRanges').prop('checked', true);
    $("#unlimitedNDate").prop('checked', true);
    $("#forAllNSite").prop('checked', true);
    $("#allSistem").prop('checked', true);
    catchCheckboxDates();
    catchCheckboxForAllUsers();
    $("#inputEmail").focus();
    $("#inputEmail").val('');
    $("#userInfoMK").hide();
    $("#nNombreMNK, #btnnNombreMNK").hide();
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

function validateNumber(event) {
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
}

function alert(tittle, html, type){
    swal({
        title: tittle,
        text: html,
        type: type,
        padding: '2em'
    });
}

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
                    $(location).attr('href', 'NikkenCMS/home');
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

var timeHide = "";
function timeout(){
    timeHide = setTimeout(function(){
        $("#notify-holster").empty();
        clearTimeout(timeHide);
    }, 8000);
}

/*=== home ===*/
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
if ($("#contentTop5Activos").length > 0 ) {
    getSitesFilter();
}

function getSitesFilter(){
    $.ajax({
        type: "get",
        url: "/NikkenCMSpro/getActions",
        data: {
            action: 'getSitesFilter',
        },
        beforeSend: function(){
            $("div[id=loadingIcon]").addClass('lds-hourglass');
            $("#contentTop5Activos").empty();
            $("#graphVisitas").empty();
            $('#actvitieSite option').remove();
            $('#plataformaTabFilter option').remove();
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
                for(x = 0; x < response.length; x++){
                    $('#actvitieSite').append($('<option>', {
                        value: response[x]['Plataforma'],
                        text: response[x]['Plataforma']
                    }));
                    $('#plataformaTabFilter').append($('<option>', {
                        value: response[x]['Plataforma'],
                        text: response[x]['Plataforma']
                    }));
                }
                $("#loadingIcon").removeClass('lds-hourglass');
                top5Activos($("#actvitieSite").val());
            }
        },
        error: function(){
            $("#loadingIcon").removeClass('lds-hourglass');
            var html = '<div class="alert text-white bg-danger" role="alert">' +
                            '<div class="iq-alert-text">No se pudieron cargar datos</div>' +
                            '<button type="button" class="close" onclick="getSitesFilter()">' +
                                'Reintentar' +
                            '</button>' +
                        '</div>';
            $("#contentTop5Activos").html(html);
        }
    });
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

/*=== Agregar sitios a buscador MyNIKKEN ===*/

$('#nSite').on('submit', function(e) {
    var utltarget = $("#urlAction").val();
    // evito que propague el submit
    e.preventDefault();

    if($("#chckCol").prop('checked') != true & $("#chckMex").prop('checked') != true & $("#chckPer").prop('checked') != true & $("#chckCri").prop('checked') != true & $("#chckEcu").prop('checked') != true & $("#chckSlv").prop('checked') != true & $("#chckGtm").prop('checked') != true & $("#chckPan").prop('checked') != true & $("#chckChl").prop('checked') != true){
        alert('Ups...!', "Se debe selccionar por lo menos un país.", 'error');
    }
    else if($("#chckDIR").prop('checked') != true & $("#chckEXE").prop('checked') != true & $("#chckPLA").prop('checked') != true & $("#chckORO").prop('checked') != true & $("#chckPLO").prop('checked') != true & $("#chckDIA").prop('checked') != true & $("#chckDRL").prop('checked') != true){
        alert('Ups...!', "Se debe selccionar por lo menos un rango.", 'error');
    }
    else if($("#chckNINNEAPP").prop('checked') != true & $("#chckMyNIKKEN").prop('checked') != true){
        alert('Ups...!', "Se debe selccionar por lo menos una plataforma.", 'error');
    }
    else{
        //deshabilitamos el boton para que solo se haga una peticion de registro
        disabled($("#btnsave"));

        // agrego la data del form a formData
        var formData = new FormData(this);
        //formData.append('_token', $('input[name=_token]').val());
        $.ajax({
            type:'POST',
            url: utltarget,
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $("div[id=loadingIcon]").addClass('lds-hourglass');
                disabled($("#nameNSite"));
                disabled($("#URLNSite"));
                disabled($("#customSwitch2"));
                disabled($("#dateStartNSite"));
                disabled($("#dateEndNSite"));
                disabled($("#unlimitedNDate"));
                disabled($("#tagNSite"));
                disabled($("#iconNsite"));
                disabled($("#allCountries"));
                disabled($("#chckCol"));
                disabled($("#chckMex"));
                disabled($("#chckPer"));
                disabled($("#chckCri"));
                disabled($("#chckEcu"));
                disabled($("#chckSlv"));
                disabled($("#chckGtm"));
                disabled($("#chckPan"));
                disabled($("#chckChl"));
            },
            success:function(data){
                if(data == 'added'){
                    alert('OK!', "El micrositio se agregó correctamente", 'success');
                    $('#nSite').trigger('reset');
                    $(".dropify-clear").trigger("click");
                }
                else{
                    alert('Ups...!', "No fue posible guardar la información.", 'error');
                }
                enabled($("#btnsave"));
                $("#loadingIcon").removeClass('lds-hourglass');
                enabled($("#nameNSite"));
                enabled($("#URLNSite"));
                enabled($("#customSwitch2"));
                enabled($("#dateStartNSite"));
                enabled($("#dateEndNSite"));
                enabled($("#unlimitedNDate"));
                enabled($("#tagNSite"));
                enabled($("#iconNsite"));
                enabled($("#allCountries"));
                enabled($("#chckCol"));
                enabled($("#chckMex"));
                enabled($("#chckPer"));
                enabled($("#chckCri"));
                enabled($("#chckEcu"));
                enabled($("#chckSlv"));
                enabled($("#chckGtm"));
                enabled($("#chckPan"));
                enabled($("#chckChl"));
            },
            error: function(jqXHR, text, error){
                $("#loadingIcon").removeClass('lds-hourglass');
                alert('Ups...!', "No fue posible guardar la información.", 'error');
                enabled($("#nameNSite"));
                enabled($("#URLNSite"));
                enabled($("#customSwitch2"));
                enabled($("#dateStartNSite"));
                enabled($("#dateEndNSite"));
                enabled($("#unlimitedNDate"));
                enabled($("#tagNSite"));
                enabled($("#iconNsite"));
                enabled($("#allCountries"));
                enabled($("#chckCol"));
                enabled($("#chckMex"));
                enabled($("#chckPer"));
                enabled($("#chckCri"));
                enabled($("#chckEcu"));
                enabled($("#chckSlv"));
                enabled($("#chckGtm"));
                enabled($("#chckPan"));
                enabled($("#chckChl"));
                enabled($("#btnsave"));
            }
        });
    }
});

function setDate(){
    Date.prototype.toDateInputValue = (function() {
        var local = new Date(this);
        local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
        return local.toJSON().slice(0,10);
    });
    
    $('#dateStartNSite').val(new Date().toDateInputValue());
}
setDate();

function getDatattableMetricas(){
    var mes = $("#mesConsultaTabFilter").val();
    var plataforma = $("#plataformaTabFilter").val();
    var pais = $("#paisTabFilter").val();
    var rango = $("#rangoTabFilter").val();
    var clave = $("#clave").val();

    $("#tabMetricas").dataTable({
        destroy: true,
        ordering: false,
        deferRender: true,
        ajax:{
            type: "get",
            url: "/NikkenCMSpro/getActions",
            data: {
                action: 'getDatattableMetricas',
                parameters: {
                    mes: mes,
                    plataforma: plataforma,
                    pais: pais,
                    rango: rango,
                    clave: clave.trim(),
                }
            },
        },
        columns: [
            { data: 'Associateid', className: 'text-center' },
            { data: 'Rango', className: 'text-center' },
            { data: 'Pais', className: 'text-center' },
            { data: 'Fecha', className: 'text-center' },
            { data: 'Plataforma', className: 'text-center' },
            { data: 'Accion', className: 'text-center' },
        ],
        buttons: {
            buttons: [
                { 
                    extend: 'excel', 
                    className: 'btn dark-icon btn-primary rounded-pill text-white mr-3 ml-3 btnExcel',
                    text:"<img src='https://services.nikken.com.mx/retos/img/excel.png' width='15px'></img> Exportar a Excel",
                },
            ]
        },
        dom: '<"row"<"col s12 m12 l12 xl12"<"row"<"col-md-6"B><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5 mb-md-0 mb-5"i><"col-md-7"p>>> >',
    });
}

/*=== Administrador de buscador / material nikkenAPP-MyNIKKEN ===*/
if ($("#tabBusinessTools").length > 0 ) {
    getDataBuscador();
}

function getDataBuscador(){
    $("#tabBusinessTools").dataTable({
        destroy: true,
        ordering: false,
        ajax:{
            type: "get",
            url: "/NikkenCMSpro/getActions",
            data: {
                action: 'getDataBuscador',
            },
        },
        columns: [
            { data: 'Reto', className: 'text-center' },
            { data: 'Tag', className: 'text-center' },
            { data: 'URL', className: 'text-center' },
            {
                data: 'icono',
                className: 'text-center',
                render: function(data, type, row){
                    var icono = row.icono;
                    return "<img src=" + icono + " width='100%'>";
                }
            },
            { data: 'FechaInicio', className: 'text-center' },
            { data: 'FechaFinzalizar', className: 'text-center' },
            {
                data: 'concat_sap_code',
                className: 'text-center',
                render: function(data, type, row){
                    return (row.concat_sap_code == 1 & row.concat_sap_code == true) ? 'Si' : 'NO';
                }
            },
            { data: 'pais', className: 'text-center' },
            { 
                data: 'showFor',
                className: 'text-center',
                render: function(data, type, row){
                    return '<p style="max-width: 150px !important">' + row.showFor + '</p>';
                }
            },
            { data: 'rangos', className: 'text-center' },
            { data: 'onclick', className: 'text-center' },
            {
                data: 'NikkenApp',
                className: 'text-center',
                render: function(data, type, row){
                    if(row.NikkenApp == 1){
                        return '<span class="mb-2 ml-2 badge badge-pills badge-success">Aplica</span>';
                    }
                    else{
                        return '<span class="mb-2 ml-2 badge badge-pills badge-danger">No Aplica</span>';
                    }
                }
            },
            {
                data: 'MyNikken',
                className: 'text-center',
                render: function(data, type, row){
                    if(row.MyNikken == 1){
                        return '<span class="mb-2 ml-2 badge badge-pills badge-success">Aplica</span>';
                    }
                    else{
                        return '<span class="mb-2 ml-2 badge badge-pills badge-danger">No Aplica</span>';
                    }
                }
            },
            {
                data: 'MyNikken',
                className: 'text-center',
                render: function(data, type, row){
                    return  '<button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target=".editSite" onclick="loadDataEditSite(' + row.ID + ')"><i class="ri-pencil-fill pr-0"></i></button>' + 
                            '<button type="button" class="btn btn-danger" onclick="deleteSite(' + row.ID + ')"><i class="ri-delete-bin-fill pr-0"></i></button>';
                }
            }
        ],
    });
}

function loadDataEditSite(id){
    $.ajax({
        type: "GET",
        url: "/NikkenCMSpro/getActions",
        data: {
            action: 'loadDataEditSite',
            parameters: {
                idSite: id
            }
        },
        success: function (response) {
            $("#nameNSite").val(response[0]['Reto']);
            $("#idNSite").val(response[0]['ID']);
            $("#URLNSite").val(response[0]['URL']);

            $("#dateStartNSite").val(response[0]['FechaInicio']);
            $("#dateEndNSite").val(response[0]['FechaFinzalizar']);

            $("#tagNSite").val(response[0]['Tag']);
            $("#onClickNSite").val(response[0]['onclick']);
            $("#currentIcon").attr('src', response[0]['icono']);
        },
        error: function(){
            
        }
    });
}

$('#editSite').on('submit', function(e) {
    var utltarget = $("#urlAction").val();
    // evito que propague el submit
    e.preventDefault();
    //deshabilitamos el boton para que solo se haga una peticion de registro
    disabled($("#btnsave"));

    // agrego la data del form a formData
    var formData = new FormData(this);
    //formData.append('_token', $('input[name=_token]').val());
    $.ajax({
        type:'POST',
        url: utltarget,
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        beforeSend: function(){
            $("div[id=loadingIcon]").addClass('lds-hourglass');
            disabled($("#nameNSite"));
            disabled($("#URLNSite"));
            disabled($("#customSwitch2"));
            disabled($("#dateStartNSite"));
            disabled($("#dateEndNSite"));
            disabled($("#unlimitedNDate"));
            disabled($("#tagNSite"));
            disabled($("#iconNsite"));
            disabled($("#allCountries"));
            disabled($("#chckCol"));
            disabled($("#chckMex"));
            disabled($("#chckPer"));
            disabled($("#chckCri"));
            disabled($("#chckEcu"));
            disabled($("#chckSlv"));
            disabled($("#chckGtm"));
            disabled($("#chckPan"));
            disabled($("#chckChl"));
        },
        success:function(data){
            if(data == 'added'){
                alert('OK!', "El micrositio se agregó correctamente", 'success');
                $('#nSite').trigger('reset');
                $(".dropify-clear").trigger("click");
            }
            else{
                alert('Ups...!', "No fue posible guardar la información.", 'error');
            }
            enabled($("#btnsave"));
            $("#loadingIcon").removeClass('lds-hourglass');
            enabled($("#nameNSite"));
            enabled($("#URLNSite"));
            enabled($("#customSwitch2"));
            enabled($("#dateStartNSite"));
            enabled($("#dateEndNSite"));
            enabled($("#unlimitedNDate"));
            enabled($("#tagNSite"));
            enabled($("#iconNsite"));
            enabled($("#allCountries"));
            enabled($("#chckCol"));
            enabled($("#chckMex"));
            enabled($("#chckPer"));
            enabled($("#chckCri"));
            enabled($("#chckEcu"));
            enabled($("#chckSlv"));
            enabled($("#chckGtm"));
            enabled($("#chckPan"));
            enabled($("#chckChl"));
            getDataBuscador();
        },
        error: function(jqXHR, text, error){
            $("#loadingIcon").removeClass('lds-hourglass');
            alert('Ups...!', "No fue posible guardar la información.", 'error');
            enabled($("#nameNSite"));
            enabled($("#URLNSite"));
            enabled($("#customSwitch2"));
            enabled($("#dateStartNSite"));
            enabled($("#dateEndNSite"));
            enabled($("#unlimitedNDate"));
            enabled($("#tagNSite"));
            enabled($("#iconNsite"));
            enabled($("#allCountries"));
            enabled($("#chckCol"));
            enabled($("#chckMex"));
            enabled($("#chckPer"));
            enabled($("#chckCri"));
            enabled($("#chckEcu"));
            enabled($("#chckSlv"));
            enabled($("#chckGtm"));
            enabled($("#chckPan"));
            enabled($("#chckChl"));
            enabled($("#btnsave"));
            getDataBuscador();
        }
    });
});

function deleteSite(id){
    $.ajax({
        type: "GET",
        url: "/NikkenCMSpro/getActions",
        data: {
            action: 'deleteSite',
            parameters: {
                idSite: id
            }
        },
        success: function (response) {
            alert('OK', 'Sitio eliminado correctamente.', 'success');
            getDataBuscador();
        },
        error: function(){
            getDataBuscador();
        }
    });
}

$("#allCountries").change(function () {
    $("#chckCol, #chckMex, #chckPer, #chckCri, #chckEcu, #chckSlv, #chckGtm, #chckPan, #chckChl").prop('checked', $(this).prop("checked"));
});

$("#allRanges").change(function () {
    $("#chckDIR, #chckEXE, #chckPLA, #chckORO, #chckPLO, #chckDIA, #chckDRL").prop('checked', $(this).prop("checked"));
});

$("#allSistem").change(function () {
    $("#chckNINNEAPP, #chckMyNIKKEN").prop('checked', $(this).prop("checked"));
});

function catchCheckboxDates(){
    var status = $("#unlimitedNDate").prop('checked');
    if(status){
        disabled($("#dateStartNSite"));
        disabled($("#dateEndNSite"));
        $("#dateStartNSite, #dateEndNSite").removeAttr('required');
    }
    else{
        enabled($("#dateStartNSite"));
        enabled($("#dateEndNSite"));
        $("#dateStartNSite, #dateEndNSite").attr('required', 'required');
    }
}

function catchCheckboxForAllUsers(){
    var status = $("#forAllNSite").prop('checked');
    if(status){
        $("#allowedUsersNsite").attr('readonly', 'readonly');
        $("#allowedUsersNsite").val('todos');
    }
    else{
        $("#allowedUsersNsite").removeAttr('readonly');
        $("#allowedUsersNsite").val('');
    }
}
