<?php
/**
 * En este fichero buscamos con el nombre y localidad introducido por el usuario los hoteles que cumplen con las condiciones.
 */

spl_autoload_register(function ($class) {
    require("$class.php");
});

$hotel= $_GET['hotel'];
$localidad= $_GET['localidad'];

$query_hoteles="SELECT sf_id,aps.nombre as nombre_hotel,ed.nombre
FROM superfichas_des.api_superficha as aps, localidades_des.entidad_detalle as ed
where aps.entidad_administrativa_id=ed.entidad_id
and aps.nombre like  '%".$hotel."%'
and ed.nombre like  '%".$localidad."%'
limit 10";

$mysqli = new Conexion();
$con= $mysqli->connect();
$resultado=mysqli_query($con,$query_hoteles);

$myArray = array();
while($row = $resultado->fetch_assoc()) {
    $myArray[] = $row;
}
echo json_encode($myArray);

?>
