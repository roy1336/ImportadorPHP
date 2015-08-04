<?php
if(!isset($_SESSION)){
    header("Location: CargarArchivo.php");
    die();
}
// input misspelled word
$columnasExcel = array('carrrot', 'pineapple', 'perro', 'bananaman', 'oranjo', 'Rodrigo', 'PEA');

// array of words to check against
$columnasTabla  = array('apple','pineapple','banana','orange',
                'radish','carrot','pea','bean','potato');


/**
 * mapea los dos arreglos para asociar las palabras que se parecen entre ambos arreglos
 * 
 * @param array $columnasExcel
 * @param array $columnasTabla
 * @return array Regresa un arreglo donde las llaves del arreglo son las palabras de uno de los
 * arreglos y los valores son las palabras del segundo arreglo. Si no logra emparejar alguna palabra
 * del primer arreglo, el elemento con esa llave del arreglo resultante será falso
 */
function mapear($columnasExcel, $columnasTabla){
    $mapeo = array();
    
    $shortest = -1;
    foreach($columnasExcel as $columna){
    $indice = $columna;
    $columna = strtolower($columna);
    // loop through words to find the closest
    $shortest = -1;
        foreach ($columnasTabla as $campo) {
        
            // calculate the distance between the input word,
            // and the current word
            $lev = levenshtein($columna, $campo);
        
            // check for an exact match
            if ($lev == 0) {
        
                // closest word is this one (exact match)
                $closest = $campo;
                $shortest = 0;
        
                // break out of the loop; we've found an exact match
                break;
            }
        
            // if this distance is less than the next found shortest
            // distance, OR if a next shortest word has not yet been found
            if ($lev <= $shortest || $shortest < 0) {
                // set the closest match, and shortest distance
                $closest  = $campo;
                $shortest = $lev;
            }
        }
        
        if($shortest > strlen($columna)*3/2){
            $mapeo[$indice] = false;
        }
        else{
            $mapeo[$indice] = $closest;
        }
    }

    return $mapeo;
}
/*
$mapeo = mapear($columnasExcel, $columnasTabla);
echo'<div>';
echo '<table border="1">';
foreach ($columnasExcel as $columna){
    echo'<tr>';
    if($mapeo[$columna]){
        echo'<td>'.$columna.'</td>';
        echo'<td>'.$mapeo[$columna].'</td>';
    }
    else{
        echo'<td style="color:red">'.$columna.'</td>';
        echo'<td style="background-color:lightgrey"></td>';
    }
    echo'</tr>';
}
echo '</table>';
echo'</div>';*/
?>