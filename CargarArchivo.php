<?php
session_start();
$borrar = false;
$archivoBorrar = array();
if (isset($_SESSION['archivoExcel'])) {
    $borrar = true;
    $archivoBorrar[0] = $_SESSION['archivoExcel'];
}

if (isset($_SESSION['archivoImportar'])) {
    $borrar = true;
    $archivoBorrar[1] = $_SESSION['archivoImportar'];
}


if (isset($_SESSION['tabla'])) {
    session_unset();
}

if (isset($_SESSION['datos'])) {
    session_unset();
}

require_once dirname(__FILE__) . '/PHPExcel_1.8.0_doc/Classes/PHPExcel/IOFactory.php';
require_once 'dbConnection.php';
require_once 'json.php';

$db = new DbConnection();

$db->abrirConexion();

$tablasDb = $db->getTablas();

$tablasJson = verificarTablas($tablasDb);

$errorArchivo = '';

if (isset($_SESSION['errorArchivo'])) {
    $errorArchivo = $_SESSION['errorArchivo'];
    unset($_SESSION['errorArchivo']);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <script src="jQuery/jquery-1.11.3.js"></script>
        <link rel="stylesheet" href="css/cargarArchivo.css"> 
        <script>
            $(function () {
                $("#catalogosDispnibles input").first().prop('checked', true);
<?php
if ($borrar) {
    if (isset($archivoBorrar[0])) {
        $_SESSION['archivoExcel'] = $archivoBorrar[0];
    }

    if (isset($archivoBorrar[1])) {
        $_SESSION['archivoImportar'] = $archivoBorrar[1];
    }
    echo '$.post("borrarArchivo.php");';
}
?>
            });
        </script>
    </head>
    <body>
        <form action="MapeoColumnas.php" method="post" enctype="multipart/form-data">
            <div id="archivoEntrada">
                <table>
                    <tr>
                        <td colspan = "2">Selecciona un archivo de excel:</td>
                    </tr>
                    <tr>
                        <td><input type="file" name="fileToUpload" id="fileToUpload"></td>
                        <td id="fileError"><?php echo $errorArchivo; ?></td>
                    </tr>
                    <tr>
                        <td colspan = "2"><input type="submit" value="Enviar..." name="submit"></td>
                    </tr>
                </table>
            </div>
            <div id="catalogosDispnibles">
                <table>
                    <?php
                    foreach ($tablasJson as $tabla) {
                        echo '<tr>';
                        echo'<td><input type="radio" name="tabla" value="' . $tabla . '"></td>';
                        echo'<td>' . $tabla . '</td>';
                        echo '</tr>';
                    }
                    $db->cerrarConexion();
                    ?>
                </table>
            </div>
        </form>

    </body>
</html>