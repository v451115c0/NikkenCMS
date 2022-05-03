@extends('NikkenCMS.' . session('tokenUserType'))

@section('content')
<ol class="breadcrumb bg-primary">
    <li class="breadcrumb-item"><a href="{{ url('NikkenCMS/home') }}" class="text-white"><i class="ri-home-4-line mr-1 float-left"></i>Home</a></li>
    <li class="breadcrumb-item active text-white" aria-current="page">Administrar Herramientas de Negocio</li>
</ol>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h4 class="card-title">Administrar Herramientas de Negocio</h4>
                </div>
            </div>
            <div class="iq-card-body">
                <div class="table-responsive">
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
</div>
@endsection

<script>
    var meses = {'202101': 'Enero 2021', '202102': 'Febrero 2021', '202103': 'Marzo 2021', '202104': 'Abril 2021', '202105': 'Mayo 2021', '202106': 'Junio 2021', '202107': 'Julio 2021', '202108': 'Agosto 2021', '202109': 'Septiembre', '202110': 'Octubre 2021', '202111': 'Noviembre 2021', '202112': 'Diciembre 2021'};

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

function formatMoney(amount, decimalCount, decimal = ".", thousands = ",") {
    try {
        decimalCount = Math.abs(decimalCount);
        decimalCount = isNaN(decimalCount) ? 0 : decimalCount;
        const negativeSign = amount < 0 ? "-" : "";
        let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
        let j = (i.length > 3) ? i.length % 3 : 0;
        return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    }
    catch (e) {
        console.log(e)
    }
};

var flags = {'3': 'peru.png', '2': 'mexico.png', '9': 'mexico.png', '1': 'colombia.png', '10': 'chile.png', '4': 'ecuador.png', '5': 'panama.png', '7': 'salvador.png', '6': 'guatemala.png', '8': 'costarica.png'};


function Estados(){
    $("#Estados").dataTable({
    lengthChange: false,
    ordering: true,
    info: false,
    destroy: true,
    ajax: "/Estados",
    columns: [
        
        { data: 'pais', className: 'text-center' },
        { data: 'colony_code', className: 'text-center' },
        { data: 'province_code', className: 'text-center' },
        { data: 'state_code', className: 'text-center' },
        { data: 'postal_code', className: 'text-center' }
        
        
        
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

function Depuraciones(){
    $("#registros").dataTable({
    lengthChange: false,
    ordering: true,
    info: false,
    destroy: true,
    ajax: "/Depuraciones",
    columns: [
        
        { data: 'code', className: 'text-center' },
        { data: 'name', className: 'text-center' },
        { data: 'email', className: 'text-center' },
        { data: 'create_at', className: 'text-center' },
       /* { 
            data: 'country', 
            className: 'text-center',
            "render": function(data, type, row){
                var paisText = row.country;
                if(paisText == 'LAT'){
                    paisText = "MEX";
                }
                return "<img src='../fpro/img/flags/" + flags[row.country.trim()] + "' width=25px'> <br> " + paisText;
            }
        },*/
        { data: 'country', className: 'text-center' },
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
    /*dom: '<"row"<"col s12 m12 l12 xl12"<"row"<"col-md-6"B><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5 mb-md-0 mb-5"i><"col-md-7"p>>> >',
        buttons: {
            buttons: [
                { 
                    extend: 'excel', 
                    className: 'btn btn-fill btn-fill-dark btn-rounded mb-4 mr-3 btnExcel', 
                    text:"<img src='https://services.nikken.com.mx/retos/img/excel.png' width='15px'></img> Exportar a Excel",
                },
            ]
        },*/
    language: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
    }
});
}

Depuraciones();

function DepurarRegistro(codigo,correo){
        /*alert(codigo);
        alert(correo);*/

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
                    alert('no se puede depurar, cuenta con pago');
            }else if(data == 1){
                    alert('depurado correctamente');
            }
            else{
                    alert('ocurrio un error');
            }/*
            $("#typedocument").find('option').remove();
            $("#typedocument").append('<option value="" selected>'+selreg+'</option>');
         //   $("#region").append('<option value="" selected>selecciona una opcion</option>');
           // $("#comuna").append('<option value="" selected>selecciona una opcion</option>');
          //  $("#ciudad").append('<option value="" selected>selecciona una opcion</option>');
          $.each(data,function(key, registro) {

            $("#comuna").append('<option value='+registro.id_type.replace(/ /g, "%")+'>'+registro.name+'</option>');
          });*/
        },
        error: function(data) {
                 alert('ocurrio un error en la solicitud');
        }
      });
}

var contador = 0;
var meses = {'202101': 'Enero 2021', '202102': 'Febrero 2021', '202103': 'Marzo 2021', '202104': 'Abril 2021', '202105': 'Mayo 2021', '202106': 'Junio 2021', '202107': 'Julio 2021', '202108': 'Agosto 2021', '202109': 'Septiembre', '202110': 'Octubre 2021', '202111': 'Noviembre 2021', '202112': 'Diciembre 2021'};
var flag = {'PER': 'peru.png', 'MEX': 'mexico.png', 'LAT': 'mexico.png', 'COL': 'colombia.png', 'CHL': 'chile.png', 'ECU': 'ecuador.png', 'PAN': 'panama.png', 'SLV': 'salvador.png', 'GTM': 'guatemala.png', 'CRI': 'costarica.png'};
var mainCode = $("#associateid").val();
$("#rankingTabCA").dataTable({
    lengthChange: false,
    ordering: true,
    info: false,
    destroy: true,
    ajax: "/vpGetRankGTMSLVPAN",
    columns: [
        { 
            data: 'Ranking', 
            className: 'text-center'
           
        },
        { data: 'AssociateName', className: 'text-center' },
        { data: 'Rango', className: 'text-center' },
        { 
            data: 'Pais', 
            className: 'text-center',
            "render": function(data, type, row){
                var paisText = row.Pais;
                if(paisText == 'LAT'){
                    paisText = "MEX";
                }
                return "<img src='../fpro/img/flags/" + flag[row.Pais.trim()] + "' width=25px'> <br> " + paisText;
            }
        },
         { 
            data: 'VP',
            className: 'text-center',
            "render": function(data, type, row){
                var vp = row.VP;
                var vp_cumple = row.Cumple_VP;
                if(vp_cumple == 'YES'){
                    vp = formatMoney(row.VP) +'<br><span class="badge badge-success badge-pill"><i class="flaticon-single-circle-tick"></i> Cumple</span>';
                }
                else{
                    vp = formatMoney(row.VP) + '<br><span class="badge badge-danger badge-pill"><i class="flaticon-close"></i> No cumple</span>';
                }
                return vp;
            }
        },
        {
                        data: 'AssociateName', className: 'text-center',
                        render: function(data, type, row){
                            return meses[row.Periodo];
                        }
                    },
        { data: 'Total_IncorporVP100', className: 'text-center' },
        {
            data: 'VGP',
            className: 'text-center',
            "render": function(data, type, row){
                var VGP_Acumulado = row.VGP;
                return formatMoney(VGP_Acumulado);
            }

        },
        
        
    ],
    language: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
    }
});


$("#rankingTabCRIB").dataTable({
    lengthChange: false,
    ordering: true,
    info: false,
    destroy: true,
    ajax: "/vpGetRankCRIB",
    columns: [
        { 
            data: 'Ranking', 
            className: 'text-center'
           
        },
        { data: 'AssociateName', className: 'text-center' },
        { data: 'Rango', className: 'text-center' },
        { 
            data: 'Pais', 
            className: 'text-center',
            "render": function(data, type, row){
                var paisText = row.Pais;
                if(paisText == 'LAT'){
                    paisText = "MEX";
                }
                return "<img src='../fpro/img/flags/" + flag[row.Pais.trim()] + "' width=25px'> <br> " + paisText;
            }
        },
         { 
            data: 'VP',
            className: 'text-center',
            "render": function(data, type, row){
                var vp = row.VP;
                var vp_cumple = row.Cumple_VP;
                if(vp_cumple == 'YES'){
                    vp = formatMoney(row.VP) +'<br><span class="badge badge-success badge-pill"><i class="flaticon-single-circle-tick"></i> Cumple</span>';
                }
                else{
                    vp = formatMoney(row.VP) + '<br><span class="badge badge-danger badge-pill"><i class="flaticon-close"></i> No cumple</span>';
                }
                return vp;
            }
        },
        {
                        data: 'AssociateName', className: 'text-center',
                        render: function(data, type, row){
                            return meses[row.Periodo];
                        }
                    },
        { data: 'Total_IncorporVP100', className: 'text-center' },
        {
            data: 'VGP',
            className: 'text-center',
            "render": function(data, type, row){
                var VGP_Acumulado = row.VGP;
                return formatMoney(VGP_Acumulado);
            }

        },
        
        
    ],

    language: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
    }
});

Estados();

$("#rankingTabCRIL").dataTable({
    lengthChange: false,
    ordering: true,
    info: false,
    destroy: true,
    ajax: "/vpGetRankCRIL",
    columns: [
        { 
            data: 'Ranking', 
            className: 'text-center'
           
        },
        { data: 'AssociateName', className: 'text-center' },
        { data: 'Rango', className: 'text-center' },
        { 
            data: 'Pais', 
            className: 'text-center',
            "render": function(data, type, row){
                var paisText = row.Pais;
                if(paisText == 'LAT'){
                    paisText = "MEX";
                }
                return "<img src='../fpro/img/flags/" + flag[row.Pais.trim()] + "' width=25px'> <br> " + paisText;
            }
        },
         { 
            data: 'VP',
            className: 'text-center',
            "render": function(data, type, row){
                var vp = row.VP;
                var vp_cumple = row.Cumple_VP;
                if(vp_cumple == 'YES'){
                    vp = formatMoney(row.VP) +'<br><span class="badge badge-success badge-pill"><i class="flaticon-single-circle-tick"></i> Cumple</span>';
                }
                else{
                    vp = formatMoney(row.VP) + '<br><span class="badge badge-danger badge-pill"><i class="flaticon-close"></i> No cumple</span>';
                }
                return vp;
            }
        },
        {
                        data: 'AssociateName', className: 'text-center',
                        render: function(data, type, row){
                            return meses[row.Periodo];
                        }
                    },
        { data: 'Total_IncorporVP100', className: 'text-center' },
        {
            data: 'VGP',
            className: 'text-center',
            "render": function(data, type, row){
                var VGP_Acumulado = row.VGP;
                return formatMoney(VGP_Acumulado);
            }

        },
        
        
    ],
    language: {
        url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
    }
});


    
    

</script>

