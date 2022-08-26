<?php

class Conexion
{
    /**
     * Este método se encarga de establecer la conexión con la BBDD
     */
    public function connect()
    {
        $this->mysqli = new mysqli("89.17.208.210", 'jessica_d', 'jeba345d', 'superfichas_des');

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
