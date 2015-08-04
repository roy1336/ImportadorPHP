$(function () {
    drag = null;
    algo = null;
    posOriginal = $("#contenedorTablas").offset();
    posOriginal.top += 10;
    posOriginal.left += 10;
    anchoCont = 0;
    alturaCont = 0;
    padre = [];

    $("#contenedorTablas").find(".campo").each(function () {
        elemento = padre[$(this).attr("id")] = $(document).find("#contenedor" + $(this).attr("id").substr(5));
        padre[$(this).attr("id")].append($(this));
        elemento.css({
            "width": elemento.width(),
            "height": elemento.height()
        });
    });

    $("#mapeo").find(".campo").each(function () {
        padreOriginal = $(this).parent();
        elemento = padre[$(this).attr("id")] = $(document).find("#contenedor" + $(this).attr("id").substr(5));
        elemento.append($(this));
        elemento.css({
            "width": elemento.width(),
            "height": elemento.height()
        });
        console.log(padreOriginal.outerHeight());
        padreOriginal.append($(this));
    });

    anchoCont = $(".contenedor").width();
    alturaCont = $(".contenedor").height();

    $(".receptorTabla").css({
        "height": alturaCont
    });

    $("#mapeo").find(".campo").each(function () {
        $(this).css({
            "position": "relative",
            "top": (padreOriginal.height() - $(this).outerHeight()) / 2,
            "left": 0
        });
    });

    $(".campo").draggable({
        revert: "invalid",
        start: function (event, ui) {
            drag = $(this);
        }
    });
    $(".receptorTabla").droppable({
        drop: function (event, ui) {
            algo = $(this).find(".campo");
            $(this).append(drag);
            if (algo.length !== 0) {
                if (algo[0] !== drag[0]) {
                    pos = algo.offset();
                    padre[algo.attr("id")].append(algo[0]);
                    algo.css({
                        "position": "relative",
                        "top": pos.top - padre[algo.attr("id")].offset().top,
                        "left": pos.left - padre[algo.attr("id")].offset().left
                    });
                    algo.animate({
                        top: 0,
                        left: 0
                    }, 500);
                }
            }
            algo = $(this).find(".campo");
            algo.css({
                "position": "relative",
                "top": ($(this).height() - algo.outerHeight()) / 2,
                "left": 0
            });

        }
    });

    $("#contenedorTablas").droppable({
        drop: function (event, ui) {
            if (drag.parent().attr("id") !== padre[drag.attr("id")].attr("id")) {
                pos = drag.offset();
                padre[drag.attr("id")].append(drag);
                drag.css({
                    "position": "relative",
                    "top": pos.top - padre[drag.attr("id")].offset().top,
                    "left": pos.left - padre[drag.attr("id")].offset().left
                });
                drag.animate({
                    top: 0,
                    left: 0
                }, 500);
            } else {
                drag.animate({
                    top: 0,
                    left: 0
                }, 500);
            }
        }
    });

    $('#boton').click(function () {
        camposColumnas = [];
        mapeoIncompleto = false;

        $(".receptorTabla").each(function () {
            if ($(this).is(':empty')) {
                mapeoIncompleto = true;
                return false;
            }
            camposColumnas.push($(this).find(".campo").first().text());
        });
//        $(document).find(".campo").each(function () {
//            if (bool) {
//                if ($(this).parent().attr("id") === padre[$(this).attr("id")].attr("id")) {
//                    $("#mensajeConfirmacion").text("Shalala");
//                    $("#dialog-confirm").dialog("open");
//                    bool = false;
//                } else {
//                    camposColumnas.push($(this).text());
//                    i++;
//                }
//            }
//        });
        if (mapeoIncompleto) {
            $("#mensajeConfirmacion").text("Shalala");
            $("#dialog-confirm").dialog("open");
        } else {
            console.log(JSON.stringify(camposColumnas));
            $("#mapeoBool").remove();
            $("#mapeoJson").val(JSON.stringify(camposColumnas));
            $("#form").submit();
        }
    });

    $("#dialog-confirm").dialog({
        autoOpen: false,
        resizable: true,
        modal: true,
        buttons: {
            "Mapear Columnas": function () {
                $(this).dialog("close");
            },
            "Continuar sin Mapear": function () {
                i = 0;
                camposColumnas = [];
                $(document).find(".campo").each(function () {
                    camposColumnas.push($(this).text());
                    i++;
                });
                $("#mapeoJson").val(JSON.stringify(camposColumnas));
                $("#form").submit();
                $(this).dialog("close");
            }
        }
    });

    mapeoVacio = true;

    $(".receptorTabla").each(function () {
        if (!$(this).is(':empty')) {
            mapeoVacio = false;
            return false;
        }
    });

    if (mapeoVacio) {
        $("#mensajeConfirmacion").text("Shalala");
        $("#dialog-confirm").dialog("open");
    }
});