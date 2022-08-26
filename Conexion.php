<?php

class Conexion
{
    /**
     * Este método se encarga de establecer la conexión con la BBDD. Lo subo a GITHUB pero sin la parte de conexión que es privada de la empresa. Para el futuro, haré unas variables en otro fichero para poder tener ahí las conexiones de desarrollo de producción etc.
     */
    public function connect()
    {
        $this->mysqli = new mysqli("xxx", 'xxxxxxxx', 'xxxxxxxx', 'xxxxxxxxxxxxx');

        if ($this->mysqli->connect_error) {
            echo "Failed to connect to MySQL: " . $this->mysqli->connect_error;
            exit();
        } else {
            return $this->mysqli;
        }
    }

}

$mysqli = new Conexion();
$con = $mysqli->connect();
