<?php
if(!isset($_SESSION)){
    header("Location: CargarArchivo.php");
    die();
}
$errores = array();
$directorio = 'excelTmp/';
$archivo = $directorio . basename($_FILES['fileToUpload']['name']);

if($_FILES['fileToUpload']['error']==UPLOAD_ERR_NO_FILE){
    $_SESSION['errorArchivo'] = 'No se seleccionó ningun archivo';
    header("Location: http://localhost:8079/ImportadorCatalogos/Importador/CargarArchivo.php");
}
$uploadOk = 1;
$FileType = pathinfo($archivo,PATHINFO_EXTENSION);
$_SESSION['archivoExcel']= $archivo;
if(isset($_POST['submit'])) {
    echo '<br />'.var_dump($_FILES).'<br />';
    if($FileType === 'xlsx' or $FileType === 'xls') {
        echo 'El archivo es un archivo de Excel - .'.$FileType.'<br />';
        $uploadOk = 1;
    } else {
        echo 'El archivo es rechazado porque no es un archivo de Excel'.'<br />';
        $uploadOk = 0;
    }
}
 // Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.".'<br />';
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "El archivo no fue cargado";
    
    //header("Location: http://localhost:8079/ImportadorCatalogos/Importador/CargarArchivo.php");
    
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $archivo)) {
        echo "El archivo ". basename( $_FILES["fileToUpload"]["name"]). " ha sido cargado con éxito :D"."<br />";
        logCambios(''.$archivo);
        //header("Location: http://localhost:8079/ImportadorCatalogos/upload.html"); /* Redirect browser */
        //exit();
    } else {
        echo "Ocurrió un error y no se pudo cargar el archivo.";
    }
}

//log the upload
function logCambios($nombreArchivo){
    $bitacora = fopen("config/archivosCargados.log", "a+") or die("Error al abrir el archivo!");
    $txt = date('Y/m/d H:i:s').' - '.$nombreArchivo."\n";
    fwrite($bitacora, $txt);
    fclose($bitacora);
    echo '<br />'.$nombreArchivo;
    //unlink($nombreArchivo);
}
