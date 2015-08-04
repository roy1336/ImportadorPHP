<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="css/draggableStyle.css">

        <script src="jQuery/jquery-1.11.3.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="js/drag.js"></script>

    </head>
    <body>
        <?php
        if (isset($_SESSION['datos'])) {
            unset($_SESSION['datos']);
        }
        echo '<br/>';
        echo var_dump($_SESSION);
        echo '<br/><br/>';
        require_once 'dbConnection.php';
        require_once 'Levenshtein.php';
        if (!isset($_SESSION['archivoExcel'])) {
            require_once 'subirArchivo.php';
        }
        require_once 'lectorExcel.php';



        $columnas = fila($hoja, '1');

        $size = count($columnas);
        for ($i = 0; $i < $size; $i++) {
            if ($columnas[$i] === null) {
                unset($columnas[$i]);
            }
        }

        $db = new DbConnection();

        if (isset($_POST['tabla'])) {
            $_SESSION['tabla'] = $_POST['tabla'];
        }
        $db->abrirConexion();
        $campos = $db->getCampos($_SESSION['tabla']);
        $db->cerrarConexion();

        echo EOL;
        var_dump($columnas);
        echo EOL;
        print_r($_SESSION['tabla']);

        if (isset($_SESSION['camposMapeados'])) {
            $mapeo = array();
            if (!$_SESSION['incluirPrimeraFila']) {
                foreach ($_SESSION['camposMapeados']as $index => $elemento) {
                    $mapeo[$columnas[$index]] = $elemento;
                }
            } else {
                $mapeo[] = false;
            }
        } else {
            $mapeo = mapear($columnas, $campos);
        }

        $mapeoVacio = true;
        foreach ($mapeo as $elemento) {
            if ($elemento !== false) {
                $mapeoVacio = false;
            }
        }

        echo EOL;
        print_r($mapeo);
        ?>

        <div id="mioXD">
            <div id="contenedorTablas">
                <?php
                $i = 1;
                $j = 1;
                foreach ($campos as $campo) {
                    echo '<div id="contenedor' . $j . '" class="contenedor">';
                    if (!estaEn($campo, $mapeo)) {
                        echo '<a id="campo' . $i . '" class="campo" href="#">' . $campo . '</a>';
                        $i++;
                    }
                    echo '</div>';
                    $j++;
                }
                ?>
            </div>
            <div id="mapeo">
                <table>
                    <?php
                    foreach ($columnas as $columna) {
                        echo '<tr><td>' . $columna . '</td><td><div class="receptorTabla">';
                        if (!$mapeoVacio) {
                            if ($mapeo[$columna]) {
                                echo '<a id="campo' . $i . '" class="campo" href="#">' . $mapeo[$columna] . '</a>';
                                $i++;
                            }
                        }
                        echo '</div></td></tr>';
                    }
                    ?>
                </table>
                <form id="form" action="ImportarRegistros.php" method="post" enctype="multipart/form-data">
                    <input type = "hidden" id = "mapeoJson" name="camposMapeados" >
                    <input type = "hidden" id = "mapeoBool" name="incluirPrimeraFila" value="false">
                </form>
                <a id = "boton" href="#" class="boton">Continuar</a> 
            </div>
        </div>
        <div id="dialog-confirm" title="MapearColumnas?">
            <p>
                <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
                <span id="mensajeConfirmacion"></span>
            </p>
        </div>
        <a href="ImportarRegistros.php" id="continuar"></a>
    </body>
</html>

<?php

/**
 * Revisa si un objeto es un elemento de un arreglo
 * 
 * @param mixed $objeto
 * @param array $arreglo
 * @return boolean True si el objeto está en el arreglo, False si el arreglo no contiene al objeto.
 */
function estaEn($objeto, $arreglo) {
    if (is_array($arreglo)) {
        foreach ($arreglo as $elemento) {
            if ($elemento === $objeto) {
                return True;
            }
        }
    }
    return False;
}
