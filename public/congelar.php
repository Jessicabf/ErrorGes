<?php

/**
 * En este fichero creamos la query para hacer la INSERT a la tabla mayorista_hotel_bloqueo y congelar el hotel en cuestión con los datos de la búsqueda.
 */
spl_autoload_register(function ($class) {
    require("$class.php");
});


$fecha_entrada= $_GET['fecha_entrada'];
$fecha_salida = $_GET['fecha_salida'];
$mayorista_key= $_GET['mayorista_key'];
$USU=  $_GET['USU'];
$comentario= $_GET['comentario'];


$query_congelar="INSERT INTO gestion_des.mayorista_hotel_bloqueo (id, mayorista_hotel_id, fecha_entrada, fecha_salida, distrib, desde, hasta, activo, usuario_bloqueo, fecha_desbloqueo, comentario, usuario_desbloqueo, tracker)
SELECT NULL AS id,mayorista_hotel_id, '$fecha_entrada', '$fecha_salida', null, NOW(),  date_add(now(), interval 48 hour),1,'$USU',null,'$comentario',null, null
FROM gestion_des.mayorista_hotel mh
WHERE mh.mayorista_key='$mayorista_key'
limit 1";

$mysqli = new Conexion();
$con= $mysqli->connect();
$resultado=mysqli_query($con,$query_congelar);

echo $resultado;

?>
