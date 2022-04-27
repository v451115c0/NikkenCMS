function alert(tittle, html, type){
    swal({
        title: tittle,
        text: html,
        type: type,
        padding: '2em'
    });
}

function getFacturasCol(){
    $("#misFacturasTable").dataTable({
        destroy: true,
        ordering: false,
        ajax: "/getFacturasCol?d1=" + $("#period").val(),
        columns: [
            { data: 'DocType', className: 'text-center' },
            { data: 'CardCode', className: 'text-center' },
            { data: 'DocNum', className: 'text-center' },
            { data: 'DocDate', className: 'text-center' },
            { data: 'NumAtCard', className: 'text-center' },
            {
                data: 'NumAtCard',
                className: 'text-center',
                render: function(data, type, row){
                    return '<a href="/downloadFactura" target="_blank" title="Descargar PDF de mi factura">' +
                    '<div class="badge badge-pill badge-success">' + 
                        '<h4>' +
                            '<i class="ri-file-pdf-line"></i>' +
                        '</h4>' +
                    '</div>' +
                '</a>';
                }
            },
        ],
        dom: '<"row"<"col s12 m12 l12 xl12"<"row"<"col-md-6"B><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5 mb-md-0 mb-5"i><"col-md-7"p>>> >',
        language:{
            "paginate": {
                "previous": "<i class='material-icons dp48'>chevron_left</i></div>",
                "next": "<i class='material-icons dp48'>chevron_right</i></div>"
            },
            "search": "Buscar" ,
            "searchPlaceholder": "Buscar factura",
            "loadingRecords": '<center><div class="box">Cargando reporte... <i class="ri-refresh-line"></i></div></center>',
            'sEmptyTable': 'No se encontraron registros',
            "sZeroRecords": "No se encontraron coincidencias",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "",
        },
        buttons: {
            buttons: [
                'pageLength',
                { 
                    extend: 'excel', 
                    className: 'btn btn-fill btn-fill-dark btn-rounded mb-4 mr-3 btnExcel', 
                    text:"<img src='https://services.nikken.com.mx/retos/img/excel.png' width='15px'></img> Exportar a Excel",
                    title: 'Mis Facturas Colombia',
                },
            ]
        },
    });
    $(".buttons-page-length").hide();
}
getFacturasCol();

function downloadFactura(factura){
    $.ajax({
        type: "GET",
        //url: "/downloadFactura",
        url: "/getPDF",
        data: {
            factura:factura
        },
        success: function (response) {
            var blob = new Blob([response]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "factura.pdf";
            link.click();
            delete(a);
        }
    });
}