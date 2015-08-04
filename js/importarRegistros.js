$(function () {
    max = 0;
    size = 0;
    comando = 1;

    $("#vistaPrevia").css({
        "width": $('#vistaPrevia').width(),
    });

    widths = [];
    widthTabla = $("#contenidoInsertar").width();
    margenTabla = $("#vistaPrevia").css("margin-left");
    margenTabla = margenTabla.split("p")[0];
    margenTabla = parseInt(margenTabla);

    heightConfirmacion = $("#confirmacion").height();
    $("#espacio").width(widthTabla);
    $("#espacio").height($("#acciones").outerHeight());

    $("#acciones").css({
        "left": widthTabla + margenTabla,
        "top": heightConfirmacion
    });
    $("#contenidoInsertar tr th").each(function (index, element) {
        //widths[index] = Number(($(element).width()/widthTabla * 100).toFixed(0));
        $(element).width($(element).width());
        widths[index] = $(element).width();
    });

    $("#vistaInsertar2").hide();
    $("#vistaActualizar2").hide();
    $("#vistaInsertar").hide();
    $("#vistaActualizar").hide();

    $("#verTodoInsertar").click(function () {
        $("#masRegistrosInsertar").html('&nbsp;');
        $("#vistaInsertar2").slideDown();
    });
    
    $("#verTodoActualizar").click(function () {
        $("#masRegistrosActualizar").html('&nbsp;');
        $("#vistaActualizar2").slideDown();
    });

//    $.post("ImportarRegistros.php", {cmd: "" + comando}, function (data) {
//        console.log(data);
//        info = jQuery.parseJSON(data);
//        max = size = info['size'];
//        info = info['datos'];
//
//        $("#confirmacion").append();
//
//        if (size > 15) {
//            max = 15;
//            show = size - 15;
//            $("#masRegistrosInsertar span").text('Existen ' + show.toString() + ' registros más');
//        }
//        else {
//            $("#verTodo").hide();
//        }
//
//        // Registros insertados
//        filaT = '';
//        for (i = 0; i < max; i++) {
//            filaT = filaT + '<tr>';
//            for (j = 0; j < info[i].length; j++) {
//                filaT = filaT + '<td>' + info[i][j] + '</td>';
//            }
//            filaT = filaT + '</tr>';
//        }
//        //$("#contenidoInsertar").append(filaT);
//
//        filaT = '';
//        for (i = max; i < size; i++) {
//            filaT = filaT + '<tr>';
//            for (j = 0; j < info[i].length; j++) {
//                filaT = filaT + '<td>' + info[i][j] + '</td>';
//            }
//            filaT = filaT + '</tr>';
//        }
//        //$("#contenidoInsertar2").append(filaT);
//        if (filaT != '') {
//            $("#contenidoInsertar2 tr:first td").each(function (index, element) {
//                $(element).css({
//                    "width": widths[index],
//                    "border-width-top": 0});
//            });
//        }
//
//        ///Actualizar
//        for (i = 0; i < max; i++) {
//            filaT = '<tr>'
//            for (j = 0; j < info[i].length; j++) {
//                filaT = filaT + '<td>' + info[i][j] + '</td>';
//            }
//            filaT = filaT + '</tr>';
//            $("#contenidoActualizar").append(filaT);
//        }
//        max = size - 15;
//    });

    $("#contenidoInsertar2 tr:first td").each(function (index, element) {
        $(element).css({
            "width": widths[index],
            "border-width-top": 0});
    });

    $('#detalleInsertar').click(function () {
        if ($('#vistaInsertar').is(':visible')) {
            $('#vistaInsertar').slideUp();
        } else {
            if ($('#vistaActualizar').is(':visible')) {
                $('#vistaActualizar').slideUp(400, function () {
                    $('#vistaInsertar').slideDown();
                });
            } else {
                $('#vistaInsertar').slideDown();
            }
        }
    });

    $('#detalleActualizar').click(function () {
        if ($('#vistaActualizar').is(':visible')) {
            $('#vistaActualizar').slideUp();
        } else {
            if ($('#vistaInsertar').is(':visible')) {
                $('#vistaInsertar').slideUp(400, function () {
                    $('#vistaActualizar').slideDown();
                });
            } else {
                $('#vistaActualizar').slideDown();
            }
        }
    });

    $('#cancelar').click(function () {
        //borrar archivo
        $.post("borrarArchivo.php", function () {
            regresar.click();
        });
    });
    
    $('#siguiente').click(function () {
        //enviar el formulario
        console.log("que pedo macha");
        $("#form").submit();
    });

});

