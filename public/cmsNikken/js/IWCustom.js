$(document).ready(function () {
    console.log("ready! en IW");
    Pendientes_Contrato();
    Pendientes_Pago();
    Pendientes_Asignar();
    $("#check_contrato_all").prop("checked", true);

});

// async function getData()
// {
//   let request = fetch('https://storage.googleapis.com/signuplatamm/Comprobantes/col/1234567891234123.pdf')
//   let respuesta = await request
//   let datos = await respuesta.json()

//    console.log(respuesta)
// }
// getData()

function Pendientes_Pago() {
    $("#pendientes_pago").DataTable().destroy();
    $("#pendientes_pago").DataTable({
        dom: "Bfrtip",
        responsive: true,
        // paging: false,
        // search: true,
        // bFilter: true,
        // bInfo: false,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json",
        },
        buttons: [
            // 'copy', 'csv', 'excel', 'pdf', 'print'
            {
                extend: "excel",
                className:
                    "btn btn-fill btn-fill-dark btn-rounded mb-4 mr-3 btnExcel",
                text: "<img src='https://services.nikken.com.mx/retos/img/excel.png' width='15px'></img> Exportar a Excel",
            },
        ],
        beforeSend: function () {
            cancelPreviousAjaxCall();
        },
        ajax: {
            url: "/pendientes_pago",
            type: "GET",
            // data: { id },
            // paging: false,
            // search: false,
            // bFilter: false,
            // bInfo: false,
            // sDom: "lfrtip",
        },
        columns: [
            // { data: "btn", className: "text-center" },
            { data: "fecha", className: "text-center" },
            { data: "dias", className: "text-center" },
            { data: "flag", className: "text-center" },
            { data: "tipo", className: "text-center" },
            { data: "code", className: "text-center" },
            { data: "name", className: "text-center" },
            { data: "email", className: "text-center" },
            { data: "cellular", className: "text-center" },
            { data: "sponsor", className: "text-center" },
            { data: "btn", className: "text-center" },

            // { data: "id_get", className: "text-center" },
            // // { data: 'description',
            // className: 'text-center' },
            // { data: "id_module", className: "text-center" },
        ],
    });
}

function Pendientes_Asignar() {
    $("#pendientes_asignar").DataTable().destroy();
    $("#pendientes_asignar").DataTable({
        dom: "Bfrtip",
        responsive: true,
        // paging: false,
        // search: true,
        // bFilter: true,
        // bInfo: false,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json",
        },
        buttons: [
            // 'copy', 'csv', 'excel', 'pdf', 'print'
            {
                extend: "excel",
                className:
                    "btn btn-fill btn-fill-dark btn-rounded mb-4 mr-3 btnExcel",
                text: "<img src='https://services.nikken.com.mx/retos/img/excel.png' width='15px'></img> Exportar a Excel",
            },
        ],
        beforeSend: function () {
            cancelPreviousAjaxCall();
        },
        ajax: {
            url: "/pendientes_asignar",
            type: "GET",
            // data: { id },
            // paging: false,
            // search: false,
            // bFilter: false,
            // bInfo: false,
            // sDom: "lfrtip",
        },
        columns: [
            // { data: "btn", className: "text-center" },
            { data: "fecha", className: "text-center" },
            { data: "dias", className: "text-center" },
            { data: "flag", className: "text-center" },
            { data: "tipo", className: "text-center" },
            { data: "name", className: "text-center" },
            { data: "email", className: "text-center" },
            { data: "cellular", className: "text-center" },
            { data: "residency_two", className: "text-center" },
            { data: "btn", className: "text-center" },

            // { data: "id_get", className: "text-center" },
            // // { data: 'description',
            // className: 'text-center' },
            // { data: "id_module", className: "text-center" },
        ],
    });
}

function Pendientes_Contrato() {
    $("#pendiente_contratos").DataTable().destroy();
    $("#pendiente_contratos").DataTable({
        dom: "Bfrtip",
        responsive: true,
        // paging: false,
        // search: true,
        // bFilter: true,
        // bInfo: false,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json",
        },
        buttons: [
            // 'copy', 'csv', 'excel', 'pdf', 'print'
            {
                extend: "excel",
                className:
                    "btn btn-fill btn-fill-dark btn-rounded mb-4 mr-3 btnExcel",
                text: "<img src='https://services.nikken.com.mx/retos/img/excel.png' width='15px'></img> Exportar a Excel",
            },
        ],
        beforeSend: function () {
            cancelPreviousAjaxCall();
        },
        ajax: {
            url: "/pendientes_contrato",
            type: "GET",
            // data: { id },
            // paging: false,
            // search: false,
            // bFilter: false,
            // bInfo: false,
            // sDom: "lfrtip",
        },
        columns: [
            // { data: "btn", className: "text-center" },
            { data: "fecha", className: "text-center" },
            { data: "dias", className: "text-center" },
            { data: "flag", className: "text-center" },
            { data: "tipo", className: "text-center" },
            { data: "code", className: "text-center" },
            { data: "name", className: "text-center" },
            { data: "email", className: "text-center" },
            { data: "cellular", className: "text-center" },
            { data: "sponsor", className: "text-center" },
            { data: "btn", className: "text-center" },

            // { data: "id_get", className: "text-center" },
            // // { data: 'description',
            // className: 'text-center' },
            // { data: "id_module", className: "text-center" },
        ],
    });
}

function Pendientes_Contrato_with_file() {
    base_url= $('#url').val();
    $("#pendiente_contratos").DataTable().destroy();
    $("#pendiente_contratos").DataTable({
        dom: "Bfrtip",
        responsive: true,
        // paging: false,
        // search: true,
        // bFilter: true,
        // bInfo: false,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json",
        },
        buttons: [
            // 'copy', 'csv', 'excel', 'pdf', 'print'
            {
                extend: "excel",
                className:
                    "btn btn-fill btn-fill-dark btn-rounded mb-4 mr-3 btnExcel",
                text: "<img src='https://services.nikken.com.mx/retos/img/excel.png' width='15px'></img> Exportar a Excel",
            },
        ],
        beforeSend: function () {
            cancelPreviousAjaxCall();
        },
        ajax: {
            url: base_url + "contrato_with_file",
            type: "GET",
            // data: { id },
            // paging: false,
            // search: false,
            // bFilter: false,
            // bInfo: false,
            // sDom: "lfrtip",
        },
        columns: [
            // { data: "btn", className: "text-center" },
            { data: "fecha", className: "text-center" },
            { data: "dias", className: "text-center" },
            { data: "flag", className: "text-center" },
            { data: "tipo", className: "text-center" },
            { data: "code", className: "text-center" },
            { data: "name", className: "text-center" },
            { data: "email", className: "text-center" },
            { data: "cellular", className: "text-center" },
            { data: "sponsor", className: "text-center" },
            { data: "btn", className: "text-center" },

            // { data: "id_get", className: "text-center" },
            // // { data: 'description',
            // className: 'text-center' },
            // { data: "id_module", className: "text-center" },
        ],
    });
}

function Pendientes_Contrato_without_file() {
    $("#pendiente_contratos").DataTable().destroy();
    $("#pendiente_contratos").DataTable({
        dom: "Bfrtip",
        responsive: true,
        // paging: false,
        // search: true,
        // bFilter: true,
        // bInfo: false,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json",
        },
        buttons: [
            // 'copy', 'csv', 'excel', 'pdf', 'print'
            {
                extend: "excel",
                className:
                    "btn btn-fill btn-fill-dark btn-rounded mb-4 mr-3 btnExcel",
                text: "<img src='https://services.nikken.com.mx/retos/img/excel.png' width='15px'></img> Exportar a Excel",
            },
        ],
        beforeSend: function () {
            cancelPreviousAjaxCall();
        },
        ajax: {
            url: "/contrato_without_file",
            type: "GET",
            // data: { id },
            // paging: false,
            // search: false,
            // bFilter: false,
            // bInfo: false,
            // sDom: "lfrtip",
        },
        columns: [
            // { data: "btn", className: "text-center" },
            { data: "fecha", className: "text-center" },
            { data: "dias", className: "text-center" },
            { data: "flag", className: "text-center" },
            { data: "tipo", className: "text-center" },
            { data: "code", className: "text-center" },
            { data: "name", className: "text-center" },
            { data: "email", className: "text-center" },
            { data: "cellular", className: "text-center" },
            { data: "sponsor", className: "text-center" },
            { data: "btn", className: "text-center" },

            // { data: "id_get", className: "text-center" },
            // // { data: 'description',
            // className: 'text-center' },
            // { data: "id_module", className: "text-center" },
        ],
    });
}



$("#pendientes_pago").on("click", ".fa-eye", function (e) {
    e.preventDefault();
    let id_contract = $(this).attr("data-id");

    let url_complete =
        "https://iw.nikkenlatam.com:8787/contratos/?data=" + btoa(id_contract);
    // console.log(url_complete);
    var win = window.open(url_complete, "_blank");
    win.focus();
});

$("#pendiente_contratos").on("click", ".fa-eye", function (e) {
    e.preventDefault();
    let id_contract = $(this).attr("data-id");

    let url_complete =
        "https://iw.nikkenlatam.com:8787/contratos/?data=" + btoa(id_contract);
    // console.log(url_complete);
    var win = window.open(url_complete, "_blank");
    win.focus();
});

$("#pendientes_asignar").on("click", ".fa-eye", function (e) {
    e.preventDefault();
    let id_contract = $(this).attr("data-id");
    // console.log(id_contract);

    let url_complete =
        "https://iw.nikkenlatam.com:8787/contratos/?data=" + btoa(id_contract);
    // console.log(url_complete);
    var win = window.open(url_complete, "_blank");
    win.focus();
});

$("#pendiente_contratos").on("click", ".fa-comment", function (e) {
    e.preventDefault();
    let id_contract = $(this).attr("data-id");
    $("#comment_modal").modal("show");
    // console.log(id_contract);
});

$('#pendiente_contratos').on('click','.fa-file',function(){
    $('#modal_document_contract').modal('show');
    let id_contract = $(this).attr("data-id");
    $('#id_contract_document').val(id_contract);
    $('#documents_contract').val('');
    url = 'https://iw.nikkenlatam.com:8787/files-upload/'+id_contract;
    $('#view_documents_contract').attr('href',url);
})

$("#pendientes_pago").on("click", ".fa-comment", function (e) {
    e.preventDefault();
    let id_contract = $(this).attr("data-id");
    $("#comment_modal").modal("show");
    $('#id_contract_message').val(id_contract);
    $('#message_incorporate_pending').val('');
    // console.log(id_contract);
});

$("#pendientes_pago").on("click", ".fa-cloud-arrow-up", function (e) {
    e.preventDefault();
    let id_contract = $(this).attr("data-id");
    $("#upload_modal").modal("show");
    $('#id_contract_upload').val(id_contract);
    $('#myfile').val('');
    
});


function upload_payment(){
    base_url = $('#url').val();
    var parametros = new FormData($('#upload_payment_form')[0]);

    var fileName = $('#myfile').val();
    let validar = ValidateExtension(fileName);
    number_payment = $('#number_payment').val();
    if (validar && number_payment != ''){
         Swal.fire({
        title: '¿Estás segur@?',    
        text: "Estás apunto de aprobar el pago de una incorporación, asegurate de haber seleccionado los documentos correctos.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aprobar Incorporación'
      }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                data: parametros,
                url : base_url + 'upload_payment',
                type : 'POST',
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $('#btn_update_payment').text('Subiendo Pago....');
                    $('#btn_update_payment').prop('disabled', true);
                },
                success: function(response){
                    // console.log(response);
                    if(response.status == 200){
                        $("#upload_modal").modal("hide");
                          Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "El pago fue guardado correctamente",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        Pendientes_Pago();
                        Pendientes_Contrato();
                        Pendientes_Contrato_with_file();
                        Pendientes_Contrato_without_file();
                    }
                    else{
                        $("#upload_modal").modal("hide");
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Algo salió mal, por favor intente de nuevo.',
                          });
                    }
                }
            });
        }
      });
    }else{
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Por favor revise que la información este completa y correcta.',
          });
    }

}


function upload_documents(){
    base_url = $('#url').val();
    var parametros = new FormData($('#upload_document_form')[0]);

    // var fileName = $('#documents_contract[0]').val();
    // let validar = ValidateExtension(fileName[0]);
    // number_payment = $('#number_payment').val();

    // console.log(fileName);
    //  console.log(parametros);
    $val = $('#message_update_contract').val();
    
   if ($val != ''){
         Swal.fire({
        title: '¿Estás segur@?',    
        text: "Estás apunto de aprobar el pago de una incorporación, asegurate de haber seleccionado los documentos correctos.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aprobar Incorporación'
      }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                data: parametros,
                url : base_url + 'upload_documents',
                type : 'POST',
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $('#btn_aprobar_contrato').text('Enviando...');
                    $('#btn_aprobar_contrato').prop('disabled', true);
                },
                success: function(response){
                    //  console.log(response);
                    if(response.status == 200){
                        
                          Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "El contrato fue aprobado correctamente",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        $("#modal_document_contract").modal("hide");
                        if($("#check_contrato_all").is(":checked")) Pendientes_Contrato();
                        if($("#check_contrato_with_file").is(":checked")) Pendientes_Contrato_with_file();
                        if($("#check_contrato_without_file").is(":checked")) Pendientes_Contrato_without_file();
                    }
                    else{
                        $("#modal_document_contract").modal("hide");
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Algo salió mal, por favor intente de nuevo.',
                          });
                    }
                }
            });
        }
      });
    }else{
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Por favor revise que la información este completa y correcta.',
          });
    }

}


function ValidateExtension(file) {
    var ext = file.split('.').pop();
    if (file != '') {
        if (ext == "pdf") {
           return true;
        } else {
            //file.val('');
            return false;
        }
    }
}

$('#check_contrato_all').on('click',function(){
    $("#check_contrato_all").prop("checked", true);
    $("#check_contrato_with_file").prop("checked", false);
    $("#check_contrato_without_file").prop("checked", false);
    Pendientes_Contrato();
})

$('#check_contrato_with_file').on('click',function(){
    $("#check_contrato_with_file").prop("checked", true);
    $("#check_contrato_all").prop("checked", false);
    $("#check_contrato_without_file").prop("checked", false);
    Pendientes_Contrato_with_file();
})

$('#check_contrato_without_file').on('click',function(){
    $("#check_contrato_without_file").prop("checked", true);
    $("#check_contrato_all").prop("checked", false);
    $("#check_contrato_with_file").prop("checked", false);
    Pendientes_Contrato_without_file();
})


$('#update_log_pending').on('click',function(){
    message_log_pending= $('#message_incorporate_pending').val();
    let id_contract = $('#id_contract_message').val();
    base_url = $('#url').val();
    $.ajax({
        url:  base_url+"save_log_pending",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
            ,
        // dataType : 'json',
        data: { message: message_log_pending, id_contract },
        success: function (resp) {
            // resp = JSON.parse(resp);
            console.log(resp.status);
            if(resp.status == 200){
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "El mensaje fue guardado con éxito.",
                    showConfirmButton: false,
                    timer: 1500,
                });
                $('#comment_modal').modal('hide');
            }
            else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Algo salió mal, por favor intente de nuevo.',
                  });
                  $('#comment_modal').modal('hide');
            }

        },
    });

})

