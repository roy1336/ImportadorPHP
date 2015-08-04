<?php

if (!isset($_SESSION)) {
    header("Location: CargarArchivo.php");
    die();
}
define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once dirname(__FILE__) . '/PHPExcel_1.8.0_doc/Classes/PHPExcel/IOFactory.php';

$archivo = $_SESSION['archivoExcel'];

if (!file_exists($archivo)) {
    exit('No se encontro el archivo' . $archivo . EOL);
}
$objPHPExcel = PHPExcel_IOFactory::load($archivo);
$hoja = $objPHPExcel->getActiveSheet();

function fila($hoja, $fila) {
    $valores = array();
    $ultimaColumna = $hoja->getHighestColumn();
    $ultimaColumna++;
    for ($columna = 'A'; $columna != $ultimaColumna; $columna++) {
        $cell = $hoja->getCell($columna . $fila);
        //if($cell->getValue()!==null){
        array_push($valores, $cell->getValue());
        //}
    }
    return $valores;
}

function obtenerRango($hoja, $rango) {
    $datos = $hoja->rangeToArray($rango);
    return $datos;
}

/**
 * 
 * @param PHPExcel_Worksheet $hoja
 * @param int $pk representa el índice de la columna en el archivo de excel que está asociada con la llave primaria de la tabla
 * @return string rerpesenta el nombre la ruta del archivo creado. Si no se pudo crear el archivo se regresa otra cosa :p
 */
function prepararArchivo($hoja, $pk = false, $incluirPrimeraFila = false) {
    $objetoExcel = new PHPExcel();
    $hojaInsertar = $objetoExcel->getSheet(0);
    $hojaInsertar->setTitle('Insertar');
    if ($objetoExcel->getSheetCount() > 1) {
        $hojaActualizar = $objetoExcel->getSheet(1);
        $hojaActualizar->setTitle('Actualizar');
    } else {
        $hojaActualizar = new PHPExcel_Worksheet();
        $hojaActualizar->setTitle('Actualizar');
        $objetoExcel->addSheet($hojaActualizar);
    }

    $rango = $hoja->calculateWorksheetDataDimension();

    if (!$incluirPrimeraFila) {
        $rango[1] = '2';
    }
    $contenidoExcel = $hoja->rangeToArray($rango);
    $datos = array();
    $datos['insertar'] = array();
    $datos['actualizar'] = array();
    if ($pk) {
        $db = new DbConnection();
        $db->abrirConexion();
        $llavePrimaria = $_SESSION['pk'];
        foreach ($contenidoExcel as $fila) {
            $existe = $db->existeRegistro($_SESSION['tabla'], $llavePrimaria, $fila[$pk]);

            if ($existe) {
                $datos['actualizar'][] = $fila;
            } else {
                $datos['insertar'][] = $fila;
            }
        }

        $db->cerrarConexion();
    } else {
        foreach ($contenidoExcel as $fila) {
            $datos['insertar'][] = $fila;
        }
    }

    $hojaInsertar->fromArray($datos['insertar'], null, 'A1', true);
    $hojaActualizar->fromArray($datos['actualizar'], null, 'A1', true);
    $escritorExcel = PHPExcel_IOFactory::createWriter($objetoExcel, 'Excel2007');
    $escritorExcel->save('excelTmp/tmp_import_upload.xlsx');
    return 'excelTmp/tmp_import_upload.xlsx';
}
