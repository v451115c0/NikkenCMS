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
            { data: 'actionUser', className: 'text-center' },
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

var flags = {'3': 'peru.png', '2': 'mexico.png', '9': 'mexico.png', '1': 'colombia.png', '10': 'chile.png', '4': 'ecuador.png', '5': 'panama.png', '7': 'salvador.png', '6': 'guatemala.png', '8': 'costarica.png'};

function Depuraciones(){
    $("#registros").dataTable({
        lengthChange: false,
        ordering: true,
        info: true,
        destroy: true,
        ajax: "/Depuraciones",
        columns: [
            { data: 'code', className: 'text-center' },
            { data: 'name', className: 'text-center' },
            { data: 'email', className: 'text-center' },
            { data: 'create_at', className: 'text-center' },
            { data: 'pais', className: 'text-center' },
            { data: 'sponsor', className: 'text-center' },
            { 
                data: 'code',
                className: 'text-center',
                "render": function(data, type, row){
                    var email = "\'"+row.email+"\'";
                    
                        btndepurar = '<button class="btn btn-danger" type="button" onclick="DepurarRegistro('+row.code+','+email+');">Depurar</button>';
                    
                    return btndepurar;
                }
            },
            
            
        ],
        dom: '<"row"<"col s12 m12 l12 xl12"<"row"<"col-md-6"B><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5 mb-md-0 mb-5"i><"col-md-7"p>>> >',
        buttons: {
            buttons: [
                { 
                    extend: 'excel', 
                    className: 'btn btn-fill btn-fill-dark btn-rounded mb-4 mr-3 btnExcel', 
                    text:"<img src='https://services.nikken.com.mx/retos/img/excel.png' width='15px'></img> Exportar a Excel",
                },
            ]
        },
        language: {
            url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
        }
    });
}
Depuraciones();

function DepurarRegistro(codigo,correo){
    $.ajax({
        type: "GET",
        url: '/Depurarmas7dias',
        dataType: "json",
        contentType: "text/json; charset=UTF-8",
        data: {
            codigo: codigo,
            correo: correo
        },
        success: function(data){
            if (data == 0) {
                alert('Ups..', 'no se puede depurar, cuenta con pago', 'error');
            }
            else if(data == 1){
                alert('Ok', 'depurado correctamente', 'success');
            }
            else{
                alert('Ups...', 'ocurrio un error', 'error');
            }
            Depuraciones();
        },
        error: function(data) {
                alert('ocurrio un error en la solicitud');
        }
    });
}

function users_cell_phone_update(){
    $('#users_cell_phone_update').DataTable({
        destroy: true,
        lengthChange: false,
        ordering: false,
        info: true,
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
                    '<a href="javascript:void(0)"><i class="ri-delete-bin-5-line text-danger" style="font-size: 20px" onclick="deleteDataWSTV(' + row.id + ')"></i></a>';
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

function get_users_fiscal_update(){
    $('#users_fiscal_update').DataTable({
        destroy: true,
        lengthChange: false,
        info: true,
        ajax: "/NikkenCMSpro/getActions?action=get_users_fiscal_update",
        dom: '<"row"<"col-md-12"<"row"<"col-md-6"B><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5 mb-md-0 mb-5"i><"col-md-7"p>>> >',
        columns: [
            { data: 'id', className: 'text-center' },
            { data: 'sap_code', className: 'text-center' },
            { data: 'rfc', className: 'text-center' },
            { data: 'person_type', className: 'text-center' },
            { data: 'regimen_code', className: 'text-center' },
            { data: 'regimen_description', className: 'text-center' },
            { data: 'business_name', className: 'text-center' },
            { data: 'name', className: 'text-center' },
            { data: 'last_name', className: 'text-center' },
            { data: 'second_last_name', className: 'text-center' },
            { data: 'cp', className: 'text-center' },
            { data: 'estado', className: 'text-center' },
            { data: 'municipio', className: 'text-center' },
            { data: 'colonia', className: 'text-center' },
            { data: 'cfdi_code', className: 'text-center' },
            { data: 'cfdi_description', className: 'text-center' },
            { 
                data: 'updated_on_sql_server',
                className: 'text-center',
                render: function(data, type, row){
                    var dato = row.updated_on_sql_server;
                    if(dato == 1){
                        return '<span class="badge badge-success ml-3">Procesado</span>';
                    }
                    else{
                        return '<span class="badge badge-info ml-3">Sin procesar</span>';
                    }
                }
            },
            { data: 'created_at', className: 'text-center' },
            { data: 'updated_at', className: 'text-center' },
            { data: 'comments', className: 'text-center' },
            { 
                data: 'fiscal_file',
                className: 'text-center',
                render: function(data, type, row){
                    var archivo = row.fiscal_file;
                    if(row.fiscal_file === null || row.fiscal_file === undefined || row.fiscal_file === NaN){
                        archivo = 'https://micrositios.s3.us-west-1.amazonaws.com/srcMyNIKKEN/color-swatch.jpg';
                    }
                    return '<a href="' + archivo + '" target="_blank" class="btn btn-success" id="getImgNewTab">Ver imagen adjunta</a><br><a href="javascript:void(0)" target="_blank" class="btn btn-success" id="getImgNewTab"><i class="ri-eye-2-line"></i> Validar documento</a>';
                },
            },
            { 
                data: 'updated_at',
                className: 'text-center',
                render: function(data, type, row){
                    return '<a href="javascript:void(0)" data-toggle="modal" data-target=".modalUpdate" onclick="loadDataFisData(' + row.id + ', ' + row.sap_code + ')"><i class="ri-ball-pen-fill text-success pr-1" style="font-size: 20px"></i></a>' +
                            '<a href="javascript:void(0)"><i class="ri-delete-bin-5-line text-danger" style="font-size: 20px" onclick="deleteFisData(' + row.id + ')"></i></a>';
                }
            },
        ],
        buttons: {
            buttons: [
                { 
                    extend: 'excel', 
                    className: 'btn btn-fill btn-fill-dark btn-rounded mb-4 mr-3 btnExcel', 
                    text:"<img src='https://services.nikken.com.mx/retos/img/excel.png' width='15px'></img> Exportar a Excel",
                },
            ]
        },
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
            "paginate": { "previous": "<i class='flaticon-arrow-left-1'></i>", "next": "<i class='flaticon-arrow-right'></i>" },
            "info": "Showing page _PAGE_ of _PAGES_",
            "search": "Buscar",
        }
    });

    $('#users_fiscal_updateAlert').DataTable({
        destroy: true,
        lengthChange: false,
        info: true,
        ajax: "/NikkenCMSpro/getActions?action=get_users_fiscal_updateError",
        dom: '<"row"<"col-md-12"<"row"<"col-md-6"B><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5 mb-md-0 mb-5"i><"col-md-7"p>>> >',
        columns: [
            { data: 'id', className: 'text-center table-danger' },
            { data: 'sap_code', className: 'text-center table-danger' },
            { data: 'rfc', className: 'text-center table-danger' },
            { data: 'person_type', className: 'text-center table-danger' },
            { data: 'regimen_code', className: 'text-center table-danger' },
            { data: 'regimen_description', className: 'text-center table-danger' },
            { data: 'business_name', className: 'text-center table-danger' },
            { data: 'name', className: 'text-center table-danger' },
            { data: 'last_name', className: 'text-center table-danger' },
            { data: 'second_last_name', className: 'text-center table-danger' },
            { data: 'cp', className: 'text-center table-danger' },
            { data: 'estado', className: 'text-center table-danger' },
            { data: 'municipio', className: 'text-center table-danger' },
            { data: 'colonia', className: 'text-center table-danger' },
            { data: 'cfdi_code', className: 'text-center table-danger' },
            { data: 'cfdi_description', className: 'text-center table-danger' },
            { 
                data: 'updated_on_sql_server',
                className: 'text-center table-danger',
                render: function(data, type, row){
                    var dato = row.updated_on_sql_server;
                    if(dato == 1){
                        return '<span class="badge badge-success ml-3">Procesado</span>';
                    }
                    else{
                        return '<span class="badge badge-info ml-3">Sin procesar</span>';
                    }
                }
            },
            { data: 'created_at', className: 'text-center table-danger' },
            { data: 'updated_at', className: 'text-center table-danger' },
            { data: 'comments', className: 'text-center table-danger' },
            { 
                data: 'fiscal_file',
                className: 'text-center table-danger',
                render: function(data, type, row){
                    var archivo = row.fiscal_file;
                    if(row.fiscal_file === null || row.fiscal_file === undefined || row.fiscal_file === NaN){
                        archivo = 'https://micrositios.s3.us-west-1.amazonaws.com/srcMyNIKKEN/color-swatch.jpg';
                    }
                    return '<a href="' + archivo + '" target="_blank" class="btn btn-success" id="getImgNewTab">Ver imagen adjunta</a>';
                },
            },
            { 
                data: 'updated_at',
                className: 'text-center table-danger',
                render: function(data, type, row){
                    return '<a href="javascript:void(0)" data-toggle="modal" data-target=".modalUpdate" onclick="loadDataFisData(' + row.id + ', ' + row.sap_code + ')"><i class="ri-ball-pen-fill text-success pr-1" style="font-size: 20px"></i></a>' +
                            '<a href="javascript:void(0)"><i class="ri-delete-bin-5-line text-danger" style="font-size: 20px" onclick="deleteFisData(' + row.id + ')"></i></a>';
                }
            },
        ],
        buttons: {
            buttons: [
                { 
                    extend: 'excel', 
                    className: 'btn btn-fill btn-fill-dark btn-rounded mb-4 mr-3 btnExcel', 
                    text:"<img src='https://services.nikken.com.mx/retos/img/excel.png' width='15px'></img> Exportar a Excel",
                },
            ]
        },
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
            "paginate": { "previous": "<i class='flaticon-arrow-left-1'></i>", "next": "<i class='flaticon-arrow-right'></i>" },
            "info": "Showing page _PAGE_ of _PAGES_",
            "search": "Buscar",
        },
    });
}
get_users_fiscal_update();

function loadDataFisData(id, sap_code){
    $.ajax({
        type: "get",
        url: "/NikkenCMSpro/getActions",
        data: {
            action: 'loadDataFisData',
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
                $("#rfc").val(response[0]['rfc']);
                $("#person_type").val(response[0]['person_type']);
                $("#regimen_code").val(response[0]['regimen_code']);
                $("#regimen_description").val(response[0]['regimen_description']);
                $("#business_name").val(response[0]['business_name']);
                $("#name").val(response[0]['name']);
                $("#last_name1").val(response[0]['last_name']);
                $("#last_name2").val(response[0]['second_last_name']);
                $("#cp").val(response[0]['cp']);
                $("#estado").val(response[0]['estado']);
                $("#municipio").val(response[0]['municipio']);
                $("#colonia").val(response[0]['colonia']);
                $("#cfdi_code").val(response[0]['cfdi_code']);
                $("#cfdi_description").val(response[0]['cfdi_description']);
                $("#updated_on_sql_server").val(response[0]['updated_on_sql_server']);
                $("#created_at").val(response[0]['created_at']);
                $("#updated_at").val(response[0]['updated_at']);
                
                var archivo = response[0]['fiscal_file'];
                if(response[0]['fiscal_file'] === null || response[0]['fiscal_file'] === undefined || response[0]['fiscal_file'] === NaN){
                    archivo = 'https://micrositios.s3.us-west-1.amazonaws.com/srcMyNIKKEN/color-swatch.jpg';
                }
                $("#getImgNewTab").attr('href', archivo);
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

function updateFisData(){
    var id = $("#idreg").val();
    var sap_code = $("#sap_code").val()
    var rfc = $("#rfc").val()
    var person_type = $("#person_type").val()
    var regimen_code = $("#regimen_code").val()
    var regimen_description = $("#regimen_description").val()
    var business_name = $("#business_name").val()
    var name = $("#name").val()
    var last_name1 = $("#last_name1").val()
    var last_name2 = $("#last_name2").val()
    var cp = $("#cp").val()
    var estado = $("#estado").val()
    var municipio = $("#municipio").val()
    var colonia = $("#colonia").val()
    var cfdi_code = $("#cfdi_code").val()
    var cfdi_description = $("#cfdi_description").val()
    var updated_on_sql_server = $("#updated_on_sql_server").val()
    var created_at = $("#created_at").val()
    var updated_at = $("#updated_at").val()

    $.ajax({
        type: "get",
        url: "/NikkenCMSpro/getActions",
        data: {
            action: 'updateFisData',
            parameters: {
                id: id,
                sap_code: sap_code,
                rfc: rfc,
                person_type: person_type,
                regimen_code: regimen_code,
                regimen_description: regimen_description,
                business_name: business_name,
                name: name,
                last_name1: last_name1,
                last_name2: last_name2,
                cp: cp,
                estado: estado,
                municipio: municipio,
                colonia: colonia,
                cfdi_code: cfdi_code,
                cfdi_description: cfdi_description,
                updated_on_sql_server: updated_on_sql_server,
                created_at: created_at,
                updated_at: updated_at,
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
                swal({
                    title: "OK",
                    text: "Registro actualizado...",
                    type: "success",
                });
                hideLoadingIcon($("#loadingdiv"));
                $("#idreg, #sap_code, #rfc, #person_type, #regimen_code, #regimen_description, #business_name, #name, #last_name, #cp, #estado, #municipio, #colonia, #cfdi_code, #cfdi_description, #updated_on_sql_server, #created_at, #updated_at").val('');
                $("#closeModal").trigger('click');
                get_users_fiscal_update();
            }
            $("#idreg").val('');
            $("#sap_code").val('');
            $("#rfc").val('');
            $("#person_type").val('');
            $("#regimen_code").val('');
            $("#regimen_description").val('');
            $("#business_name").val('');
            $("#name").val('');
            $("#last_name1").val('');
            $("#last_name2").val('');
            $("#cp").val('');
            $("#estado").val('');
            $("#municipio").val('');
            $("#colonia").val('');
            $("#cfdi_code").val('');
            $("#cfdi_description").val('');
            $("#updated_on_sql_server").val('');
            $("#created_at").val('');
            $("#updated_at").val('');
        },
        error: function(){
            hideLoadingIcon($("#loadingdiv"));
            var html = '<div class="alert text-white bg-danger" role="alert">' +
                            '<div class="iq-alert-text">No se pudieron cargar datos</div>' +
                            '<button type="button" class="close" onclick="loadDataFisData(' + id + ',' + sap_code + ')">' +
                                'Reintentar' +
                            '</button>' +
                        '</div>';
            $("#loading").html(html);
        }
    });
}

function deleteFisData(id){
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
                    action: 'deleteFisData',
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
                    get_users_fiscal_update();
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