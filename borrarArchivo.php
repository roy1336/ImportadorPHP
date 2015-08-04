<?php
session_start();

if (isset($_SESSION)) {
    if (isset($_SESSION['archivoExcel'])) {
        if (file_exists($_SESSION['archivoExcel'])) {
            $confirm = unlink($_SESSION['archivoExcel']);
            
            if ($confirm) {
                unset($_SESSION['archivoExcel']);
                echo 'archivo borrado correctamente';
            } else {
                unset($_SESSION['archivoExcel']);
                echo 'no se pudo borrar el archivo';
            }
        } else {
            unset($_SESSION['archivoExcel']);
            echo 'no encontre el archivo :v';
        }
    } else {
        echo 'no está el nombre del archivo en la sesion';
    }
    
    if (isset($_SESSION['archivoImportar'])) {
        if (file_exists($_SESSION['archivoImportar'])) {
            $confirm = unlink($_SESSION['archivoImportar']);
            if ($confirm) {
                unset($_SESSION['archivoImportar']);
                echo 'archivo borrado correctamente';
            } else {
                echo 'no se pudo borrar el archivo';
                unset($_SESSION['archivoImportar']);
            }
        } else {
            unset($_SESSION['archivoImportar']);
            echo 'no encontre el archivo :v';
        } 
    } else {
        echo 'no está el nombre del archivo en la sesion';
    }
} else {
    echo 'no exste la sesion :v';
}
die();