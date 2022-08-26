<?php
/**
 * En este fichero encontramos los resultados de la búsqueda de envíos del cron.
 */

spl_autoload_register(function ($class) {
    require("$class.php");
});
$name = filter_input(INPUT_GET, "name") ?? $_POST['name'] ?? null;

?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ErrorGes</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="index.js"></script>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<header>
    <h1 id="h1_header">ErrorGes</a></h1>
    <form name="formLogin" action="index.php" method="POST">
        <fieldset id="formLogin">
            <p>Usuario conectado como <?= $name ?></p>
            <button type='submit' id="botonSelec" value='Desconectar' name='desconectar'>Desconectar</button>
        </fieldset>
    </form>
</header>

<?php

class Envios
{

    public function enviar()
    {

        $mysqli = new Conexion();
        $con = $mysqli->connect();

        $condicion = "";
        $texto = "";

        /**
         * Se añaden las condiciones a la consulta MySql en función de los filtros introducidos por el usuario.
         */

        if (!empty($_POST['sf_hotel'])) {
            $sf = $_POST['sf_hotel'];
            $condicion = $condicion . " and id in (select errorges_envio_id from gestion_des.errorges_envio_detalle ed where ed.sf_id='$sf') ";
            $texto = $texto . " en la superficha '$sf' ";
        }

        if (!empty($_POST['mayorista'])) {
            $mayorista = $_POST['mayorista'];
            $condicion = $condicion . " and mayorista='$mayorista'";
            $texto = $texto . " de la mayorista '$mayorista'";
        }

        if (!empty($_POST['fecha'])) {
            $fecha_envio = $_POST['fecha'];
            $condicion = $condicion . "and DATE(fecha_envio)='$fecha_envio'";
            $texto = $texto . " desde fecha '$fecha_envio'";
        }

        ?>

        <?php

        /**
         * Se realiza la consulta a BBDD con las condiciones indicadas.
         */

       $query = " select id as errorges_envio_id, fecha_envio, fecha_errores_desde, fecha_errores_hasta, mayorista, errores, hoteles
        from gestion_des.errorges_envio
        where 1=1
        $condicion
        order by 1 desc
        limit 10";


        $resultado = mysqli_query($con, $query);
        echo "<h2>Errores $texto:</h2>";

        /**
         * Pintamos la tabla con los resultados
         */

        ?>
<div class="table">

<table border=1 cellspacing=1 cellpadding=0>
            <tr>
                <th>ID ENVÍO</th>
                <th>FECHA ENVÍO</th>
                <th>MAYORISTA</th>
                <th>TOTAL ERRORES</th>
                <th>TOTAL HOTELES</th>
            </tr>

            <?php
            while ($result = $resultado->fetch_assoc()){
            /**
             * Hacemos un bucle para recorrer todos los resultados y en la tabla obtenemos los values del radiobutton para posteriormente poder ver el detalle de cada envío.
             */
            ?>
            <tr>
                <td><input type="radio" name="radio" id="radio<?php echo $result['errorges_envio_id']; ?>"
                           oninput="radio('<?php echo $result['errorges_envio_id']; ?>')">
                    <label id="label2"
                           for="radio<?php echo $result['errorges_envio_id']; ?>"><?php echo $result['errorges_envio_id']; ?></label>
                </td>
                <td><?php echo $result['fecha_envio']; ?></td>
                <td><?php echo $result['mayorista']; ?></td>
                <td><?php echo $result['errores']; ?></td>
                <td><?php echo $result['hoteles']; ?></td>

                <?php
                }
                ?>
            </tr>
        </table>
</div>
        <button id='button' type="submit" onclick="mostrarDetalles()">Más detalles</button>
        <div id="resultados"></div>
        <?php
    }
}

$resultado = new Envios();
$res = $resultado->enviar();

?>
</body>
</html>


