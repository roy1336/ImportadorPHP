<?php
session_start();
require_once 'dbConnection.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/resultados.css">

        <script src="jQuery/jquery-1.11.3.js"></script>
        <script>
            $(function () {
                $("#finalizar").click(function () {
                    console.log("click:)");
                    $.post("borrarArchivo.php", function () {
                        console.log(":)");
                        inicio.click();
                    });
                });
            });
        </script>
    </head>
    <body>
        <?php
//        echo 'Post:<br/>';
//        var_dump($_POST);
//        echo '<br/><br/>';
//        echo 'Sesion:<br/>';
//        print_r($_SESSION);
//        echo '<br/><br/>';
        $datos = $_SESSION['datos'];
//        echo 'Datos a importar:<br/>';
//        var_dump($datos['insertar']);
//        echo '<br/><br/>';
//        echo 'Datos a actualizar:<br/>';
//        var_dump($datos['actualizar']);
//        echo '<br/><br/>';
        if (isset($_POST['insertar'])) {
            
            $resultadoImportar = importarRegistros($datos['insertar']['datos']);
//            echo 'Resultados de imprtar:<br/><p>';
//            print_r($resultadoImportar);
//            echo '</p><br/><br/>';
            $fp = fopen('results.json', 'w');
            fwrite($fp, json_encode($resultadoImportar));
            fclose($fp);
            $ultimo = count($resultadoImportar[0]) - 1;
        }
        if (isset($_POST['actualizar'])) {
            $resultadoActualizar = actualizarRegistros($datos['actualizar']['datos']);
//            echo 'Resultados de actualizar:<br/>';
//            var_dump($resultadoActualizar);
//            echo '<br/><br/>';
            $ultimo = count($resultadoActualizar[0]) - 1;
        }
        ?>

        <div id = "resultadosInsertar">
            <table>
                <?php
                echo '<caption> Registros importados en la tabla <b>' . $_SESSION['tabla'] . '</b></caption>';
                echo '<tbody id = "contenidoInsertar"><tr>';
                foreach ($_SESSION['camposMapeados'] as $campo) {
                    echo '<th>' . $campo . '</th>';
                }
                echo '<th>Resultado</th></tr>';

                for ($i = 0; $i < $datos['insertar']['size'] && $i < 15; $i++) {

                    if ($resultadoImportar[$i][$ultimo]) {
                        echo '<tr class = "exito">';
                    } else {
                        echo '<tr class = "fracaso">';
                    }

                    for ($j = 0; $j < $ultimo; $j++) {
                        echo '<td>' . $resultadoImportar[$i][$j] . '</td>';
                    }
                    if ($resultadoImportar[$i][$ultimo]) {
                        echo '<td>Éxito</td>';
                    } else {
                        echo '<td>Fracaso</td>';
                    }
                    echo '</tr>';
                }
                ?>
            </table>
            <div id="vistaInsertar2">
                <table>
                    <tbody id = "contenidoInsertar2">
                        <?php
                        for ($i = 15; $i < $datos['insertar']['size']; $i++) {

                            if ($resultadoImportar[$i][$ultimo]) {
                                echo '<tr class = "exito">';
                            } else {
                                echo '<tr class = "fracaso">';
                            }

                            for ($j = 0; $j < $ultimo; $j++) {
                                echo '<td>' . $resultadoImportar[$i][$j] . '</td>';
                            }

                            if ($resultadoImportar[$i][$ultimo]) {
                                echo '<td>Éxito</td>';
                            } else {
                                echo '<td>Fracaso</td>';
                            }

                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div id="masRegistrosInsertar">
                <?php
                if ($datos['insertar']['size'] > 15) {
                    $masRegistros = $datos['insertar']['size'] - 15;
                    echo '<span>Existen ' . $masRegistros . ' registros más</span><a id = "verTodoInsertar" class="boton" href="#acciones">Ver todos los registros</a>';
                } else {
                    echo '&nbsp;';
                }
                ?>
            </div>
        </div>
        <div id = "resultadosActualizar">
            <table>
                <?php
                echo '<caption> Registros actualizados en la tabla <b>' . $_SESSION['tabla'] . '</b></caption>';
                echo '<tbody id = "contenidoActualizar"><tr>';
                foreach ($_SESSION['camposMapeados'] as $campo) {
                    echo '<th>' . $campo . '</th>';
                }
                echo '<th>Resultado</th></tr>';

                for ($i = 0; $i < $datos['actualizar']['size'] && $i < 15; $i++) {
                    if ($resultadoActualizar[$i][$ultimo]) {
                        echo '<tr class = "exito">';
                    } else {
                        echo '<tr class = "fracaso">';
                    }

                    for ($j = 0; $j < $ultimo; $j++) {
                        echo '<td>' . $resultadoActualizar[$i][$j] . '</td>';
                    }

                    if ($resultadoActualizar[$i][$ultimo]) {
                        echo '<td>Éxito</td>';
                    } else {
                        echo '<td>Fracaso</td>';
                    }
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
            <div id="vistaActualizar2">
                <table>
                    <tbody id = "contenidoActualizar2">
                        <?php
                        for ($i = 15; $i < $datos['actualizar']['size']; $i++) {
                            if ($resultadoActualizar[$i][$ultimo]) {
                                echo '<tr class = "exito">';
                            } else {
                                echo '<tr class = "fracaso">';
                            }

                            for ($j = 0; $j < $ultimo; $j++) {
                                echo '<td>' . $resultadoActualizar[$i][$j] . '</td>';
                            }

                            if ($resultadoActualizar[$i][$ultimo]) {
                                echo '<td>Éxito</td>';
                            } else {
                                echo '<td>Fracaso</td>';
                            }
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div id="masRegistrosActualizar">
                <?php
                if ($datos['actualizar']['size'] > 15) {
                    $masRegistros = $datos['actualizar']['size'] - 15;
                    echo '<span>Existen ' . $masRegistros . ' registros más</span><a id = "verTodoActualizar" class="boton" href="#acciones">Ver todos los registros</a>';
                } else {
                    echo '&nbsp;';
                }
                ?>
            </div>
        </div>

        <button id="finalizar" >Finalizar proceso</button>
        <a id="inicio" href="CargarArchivo.php"></a>
    </body>
</html>
<?php

function importarRegistros($datos) {
    $db = new DbConnection();
    $result = array();
    $db->abrirConexion();
    foreach ($datos as $row) {
        $row[] = $db->insertarRegistro($_SESSION['tabla'], $_SESSION['camposMapeados'], $row);
        $result[] = $row;
    }
    $db->cerrarConexion();
    return $result;
}

function actualizarRegistros($datos) {
    $db = new DbConnection();
    $result = array();
    $db->abrirConexion();
    foreach ($datos as $row) {
        $row[] = $db->actualizarRegistro($_SESSION['tabla'], $_SESSION['camposMapeados'], $row, $_SESSION['pk']);
        $result[] = $row;
    }
    $db->cerrarConexion();
    return $result;
}
