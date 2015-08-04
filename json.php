<?php
if(!isset($_SESSION)){
    header("Location: CargarArchivo.php");
    die();
}
function verificarTablas($tablas){
    $archivoJson = file_get_contents("config/test.json");
    $contenido = json_decode($archivoJson, true);
    
    if($contenido["Catalogos"] !== null){
        $contenido = $contenido["Catalogos"];
    }

    if($contenido !== null){
        $tablasRet = array();
        foreach($contenido as $elemento){
            for ($i=0;$i<count($tablas);$i++){
                if($elemento==$tablas[$i]){
                    array_push($tablasRet, $elemento);
                    $i = count($tablas);
                }
            }
        }
        return $tablasRet;
    }else{
        return $tablas;
    }
}
?>