<?php
/**
 * En este método vemos el detalle de cada envío del fichero Envios.
 */
spl_autoload_register(function ($class) {
    require("$class.php");
});


$errorges_envio_id = $_GET['errorges_envio_id'];

$query_detalle = "select *
from gestion_des.errorges_envio_detalle
where errorges_envio_id = " . $errorges_envio_id . "
 limit 50";


$mysqli = new Conexion();
$con = $mysqli->connect();
$resultado = mysqli_query($con, $query_detalle);


$myArray = array();
while ($row = $resultado->fetch_assoc()) {
    $myArray[] = $row;
}
echo json_encode($myArray);
?>
