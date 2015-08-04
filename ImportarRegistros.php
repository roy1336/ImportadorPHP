<?php
session_start();

require_once 'lectorExcel.php';
require_once 'dbConnection.php';

if (isset($_POST['camposMapeados'])) {
    $campos = json_decode($_POST['camposMapeados']);
    $_SESSION['camposMapeados'] = $campos;
} else {
    
}

$_SESSION['incluirPrimeraFila'] = false;

if (isset($_POST['incluirPrimeraFila'])) {
    $_SESSION['incluirPrimeraFila'] = true;
}

$db = new DbConnection();
$db->abrirConexion();
$pk = $db->getPk($_SESSION['tabla']);
$db->cerrarConexion();

$_SESSION['pk'] = $pk;
$pkIndex = array_search($pk, $campos, true);
$_SESSION['archivoImportar'] = prepararArchivo($hoja, $pkIndex, $_SESSION['incluirPrimeraFila']);

$datos = getDatos($_SESSION['archivoImportar']);
$_SESSION['datos'] = $datos;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="css/importarStyle.css">
        <script src="jQuery/jquery-1.11.3.js"></script>
        <script src="js/importarRegistros.js"></script>

    </head>

    <body>
        <?php
//var_dump($datos['actualizar']) . '<br/><br/>';
//        echo var_dump($datos['insertar']) . '<br/><br/>';
//        echo var_dump($datos['actualizar']) . '<br/><br/>';
        ?>
        <div id = "confirmacion">
            <form id="form" action="Resultados.php" method="post" enctype="multipart/form-data">
                <?php if ($datos['insertar']['size'] > 0) { ?>
                    <div>
                        Se importarán <?php echo $datos['insertar']['size']; ?> registros <a id="detalleInsertar" href="#acciones">detalle</a>
                        <input type="checkbox" name="insertar" id="insertar" value="insertar" checked>Sí insertar
                    </div>
                    <?php
                }
                if ($datos['actualizar']['size'] > 0) {
                    ?>
                    <div>
                        Se actualizarán <?php echo $datos['actualizar']['size']; ?> registros <a id="detalleActualizar" href="#acciones">detalle</a>
                        <input type="checkbox" name="actualizar" id="actualizar" value="actualizar" checked>Sí actualizar
                    </div>
                <?php } ?>

            </form>
        </div>

        <div id="vistaPrevia">
            <div id="vistaInsertar">
                <table>
                    <?php
                    echo '<caption> Registros a importar en la tabla <b>' . $_SESSION['tabla'] . '</b></caption>';
                    echo '<tbody id = "contenidoInsertar"><tr>';
                    foreach ($campos as $campo) {
                        echo '<th>' . $campo . '</th>';
                    }
                    echo '</tr>';

                    for ($i = 0; $i < $datos['insertar']['size'] && $i < 15; $i++) {
                        echo '<tr>';
                        foreach ($datos['insertar']['datos'][$i] as $valor) {
                            echo '<td>' . $valor . '</td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
                <div id="vistaInsertar2">
                    <table>
                        <tbody id = "contenidoInsertar2">
                            <?php
                            for ($i = 15; $i < $datos['insertar']['size']; $i++) {
                                echo '<tr>';
                                foreach ($datos['insertar']['datos'][$i] as $valor) {
                                    echo '<td>' . $valor . '</td>';
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

            <div id="vistaActualizar">
                <table>
                    <?php
                    echo '<caption> Registros a actualizar en la tabla <b>' . $_SESSION['tabla'] . '</b></caption>';
                    echo '<tbody id = "contenidoActualizar"><tr>';
                    foreach ($campos as $campo) {
                        echo '<th>' . $campo . '</th>';
                    }
                    echo '</tr>';

                    for ($i = 0; $i < $datos['actualizar']['size'] && $i < 15; $i++) {
                        echo '<tr>';
                        foreach ($datos['actualizar']['datos'][$i] as $valor) {
                            echo '<td>' . $valor . '</td>';
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
                                echo '<tr>';
                                foreach ($datos['actualizar']['datos'][$i] as $valor) {
                                    echo '<td>' . $valor . '</td>';
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
            <div id="acciones">
                <a id = "cancelar" class="boton" href="#acciones">Cancelar</a>
                <a id = "volverMapeo" class="boton" href="MapeoColumnas.php">Regresar a mapeo</a>
                <a id = "siguiente" class="boton" href="#acciones">Siguiente</a>
            </div>
        </div>
        <a href="CargarArchivo.php" id="regresar"></a>
    </body>
</html>
<?php

function getDatos($nombreArchivo) {

    if (!file_exists($nombreArchivo)) {
        return('No se encontro el archivo' . $nombreArchivo . EOL);
        //algo ha de suceder aqui ;) :v
    }
    $objPHPExcel = PHPExcel_IOFactory::load($nombreArchivo);
    $hojaInsertar = $objPHPExcel->getSheet(0);
    $hojaActualizar = $objPHPExcel->getSheet(1);
    $datos = array();

    $datos['insertar'] = getInsertar($hojaInsertar);

    $datos['actualizar']=  getActualizar($hojaActualizar);
    return $datos;
}

function getInsertar($hojaInsertar){
    $datos = array();
    $rangoInsertar = $hojaInsertar->calculateWorksheetDataDimension();
    $maxFilaInsertar = $hojaInsertar->getHighestRow();

    $k = 0;
    for($i=strlen($rangoInsertar)-1;is_numeric(substr($rangoInsertar, $i,1));$i--){
        $k--;
    }
    $maxColumnaInsertar='A';
    for($j=1;$j<count($_SESSION['camposMapeados']);$j++){
        $maxColumnaInsertar++;
    }
    
    $rangoFinInsertar = $maxColumnaInsertar.substr($rangoInsertar,$k);
    $rangoInsertar = explode(':',$rangoInsertar)[0].':'.$rangoFinInsertar;
    
    $datos['datos'] = obtenerRango($hojaInsertar, $rangoInsertar);
    $datos['size'] = intval($maxFilaInsertar);

    if ($datos['size'] == 1) {
        $insertarVacio = true;
        foreach($datos['datos'][0] as $dato){
            if(!is_null($dato)){
                $insertarVacio = false;
                break;
            }
        }
        if ($insertarVacio) {
            $datos['datos'] = array();
            $datos['size'] = 0;
        }
    }
    return $datos;
}

function getActualizar($hojaActualizar){
    $datos = array();
    $rangoActualizar = $hojaActualizar->calculateWorksheetDataDimension();
    $maxFilaActualizar = $hojaActualizar->getHighestRow();

    $k = 0;
    for($i=strlen($rangoActualizar)-1;is_numeric(substr($rangoActualizar, $i,1));$i--){
        $k--;
    }
    $maxColumnaActualizar='A';
    for($j=1;$j<count($_SESSION['camposMapeados']);$j++){
        $maxColumnaActualizar++;
    }
    $rangoFinActualizar = $maxColumnaActualizar.substr($rangoActualizar,$k);
    $rangoActualizar = explode(':',$rangoActualizar)[0].':'.$rangoFinActualizar;

    $datos['datos'] = obtenerRango($hojaActualizar, $rangoActualizar);
    $datos['size'] = intval($maxFilaActualizar);

    if ($datos['size'] == 1) {
        $actualizarVacio = true;
        foreach($datos['datos'][0] as $dato){
            if(!is_null($dato)){
                $actualizarVacio = false;
                break;
            }
        }
        if ($actualizarVacio) {
            $datos['datos'] = array();
            $datos['size'] = 0;
        }
    }

    return $datos;
}