$(document).ready(function () {
    console.log("ready! en IW");
    Pendientes_Contrato();
    Pendientes_Pago();
    Pendientes_Asignar();
   
});

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
    console.log(id_contract);

    let url_complete =
        "https://iw.nikkenlatam.com:8787/contratos/?data=" + btoa(id_contract);
    // console.log(url_complete);
    var win = window.open(url_complete, "_blank");
    win.focus();
});

$("#pendiente_contratos").on("click", ".fa-eye", function (e) {
    e.preventDefault();
    let id_contract = $(this).attr("data-id");
    console.log(id_contract);

    let url_complete =
        "https://iw.nikkenlatam.com:8787/contratos/?data=" + btoa(id_contract);
    // console.log(url_complete);
    var win = window.open(url_complete, "_blank");
    win.focus();
});

$("#pendientes_asignar").on("click", ".fa-eye", function (e) {
    e.preventDefault();
    let id_contract = $(this).attr("data-id");
    console.log(id_contract);

    let url_complete =
        "https://iw.nikkenlatam.com:8787/contratos/?data=" + btoa(id_contract);
    // console.log(url_complete);
    var win = window.open(url_complete, "_blank");
    win.focus();
});

$("#pendiente_contratos").on("click", ".fa-comment", function (e) {
    e.preventDefault();
    let id_contract = $(this).attr("data-id");
    $('#comment_modal').modal('show');
    console.log(id_contract);
});

$("#pendientes_pago").on("click", ".fa-comment", function (e) {
    e.preventDefault();
    let id_contract = $(this).attr("data-id");
    $('#comment_modal').modal('show');
    console.log(id_contract);
});

$("#pendientes_pago").on("click", ".fa-cloud-arrow-up", function (e) {
    e.preventDefault();
    let id_contract = $(this).attr("data-id");
    $('#upload_modal').modal('show');
    console.log(id_contract);
});



// function load_update_answer() {
//     var url = $("#url").val();
//     let id = $("#id_question").val();
//     // console.log(id);
//     //Exportable table
//     $("#table_update_answer").DataTable().destroy();
//     $("#table_update_answer").DataTable({
//         dom: "Bfrtip",
//         responsive: true,
//         paging: false,
//         search: false,
//         bFilter: false,
//         bInfo: false,
//         language: {
//             url: "https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json",
//         },
//         buttons: [
//             // 'copy', 'csv', 'excel', 'pdf', 'print'
//         ],
//         beforeSend: function () {
//             cancelPreviousAjaxCall();
//         },
//         ajax: {
//             url: url + "get_answers_update",
//             type: "GET",
//             data: { id },
//             // paging: false,
//             // search: false,
//             // bFilter: false,
//             // bInfo: false,
//             // sDom: "lfrtip",
//         },
//         columns: [
//             { data: "btn", className: "text-center" },
//             { data: "response", className: "text-center" },
//             // { data: "id_get", className: "text-center" },
//             // // { data: 'description',
//             // className: 'text-center' },
//             // { data: "id_module", className: "text-center" },
//         ],
//     });
// }
