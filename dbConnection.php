<?php

if (!isset($_SESSION)) {
    header("Location: CargarArchivo.php");
    die();
}

class DbConnection {

    private $serverName = "localhost:3306";
    private $userName = "root";
    private $password = "";
    private $database = "tuldinamico";
    private $conn;

    function __construct($serverName = 'localhost:3306') {
        $this->serverName = $serverName;
        $this->userName = "root";
        $this->password = "";
        $this->database = "tuldinamico";
    }

    // Create connection
    function abrirConexion() {
        $this->conn = new mysqli($this->serverName, $this->userName, $this->password, $this->database);

        // Check connection
        if ($this->conn->connect_error) {
            throw new Exception('Error al conectarse a la base de datos: ' . $this->conn->connect_error);
        }
        //echo "Connected successfully";
    }

    function cerrarConexion() {
        $this->conn->close();
    }

    function getTablas() {
        $resultado = $this->conn->query('SHOW TABLES FROM tuldinamico');
        $tablas = array();
        while ($info = $resultado->fetch_row()) {
            array_push($tablas, $info[0]);
        }
        return $tablas;
    }

    function getCampos($tabla) {
        $campos = array();
        $resultado = $this->conn->query('SELECT * FROM ' . $tabla . ' limit 1');
        while ($info = $resultado->fetch_field()) {
            array_push($campos, $info->name);
        }

        return $campos;
    }

    function getPk($tabla) {
        $pk = null;
        $resultado = $this->conn->query('SHOW COLUMNS FROM ' . $tabla);
        while (($fila = $resultado->fetch_row()) && $pk === null) {
            if ($fila[3] === 'PRI') {
                $pk = $fila[0];
            }
        }
        return $pk;
    }

    function existeRegistro($tabla, $pk, $id) {
        if(is_null($id)){
            return false;
        }
        $resultado = $this->conn->query('SELECT * FROM ' . $tabla . ' WHERE ' . $pk . ' = ' . $id);
        return $resultado->num_rows > 0;
    }

    function insertarRegistro($tabla, $campos, $datos) {
        $fields = '';
        $values = '';
        foreach ($campos as $index => $campo) {
            $fields = $fields . $campo . ', ';
            if (is_null($datos[$index])) {
                $values = $values . "NULL, ";
            } else {
                $values = $values . "'" . $datos[$index] . "', ";
            }
        }
        $fields = substr($fields, 0, -2);

        $values = substr($values, 0, -2);
        //$result = true;
        //$result = 'INSERT INTO ' . $tabla . ' (' . $fields . ') VALUES (' . $values . ')';
        $result = $this->conn->query('INSERT INTO ' . $tabla . ' (' . $fields . ') VALUES (' . $values . ')');
        return $result;
    }

    function actualizarRegistro($tabla, $campos, $datos, $pk) {
        $pkIndex = array_search($pk, $campos);
        unset($campos[$pkIndex]);
        $cadenaUpdate = '';
        foreach ($campos as $index => $campo) {
            $cadenaUpdate = $cadenaUpdate . ' ' . $campo . "='" . $datos[$index] . "', ";
        }
        $cadenaUpdate=  substr($cadenaUpdate, 0, -2);
        //$result = false;
        //$result = 'UPDATE ' . $tabla . ' SET'.$cadenaUpdate.' WHERE '.$pk.'='.$datos[$pkIndex];
        $result = $this->conn->query('UPDATE ' . $tabla . ' SET'.$cadenaUpdate.' WHERE '.$pk.'='.$datos[$pkIndex]);
        return $result;
    }

}

?>