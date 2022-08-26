<?php
/**
 * En este fichero encontramos los resultados de la búsqueda de errores con los filtros indicados por el usuario.
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
            <p>Usuario conectado como <span id="USU"><?= $name ?></span></p>
            <button type='submit' id="botonSelec" value='Desconectar' name='desconectar'>Desconectar</button>
        </fieldset>
    </form>
</header>

<?php

class Resultado
{

    public function resultados()
    {

        $mysqli = new Conexion();
        $con = $mysqli->connect();

        $condicion = "";
        $texto = "";

        /**
         * Se añaden las condiciones a la consulta MySql en función de los filtros introducidos por el usuario.
         */

        if (!empty($_POST['email'])) {
            $email = $_POST['email'];
            $condicion = $condicion . " and ae.mail_cliente='$email'";
            $texto = $texto . " con email '$email'";
        }

        if (!empty($_POST['telefono'])) {
            $telefono = $_POST['telefono'];
            $condicion = $condicion . " and ae.mail_telefono='$telefono'";
            $texto = $texto . " con teléfono '$telefono'";
        }

        if (!empty($_POST['sf'])) {
            $sf = $_POST['sf'];
            $condicion = $condicion . " and aps.sf_id='$sf' ";
            $texto = $texto . " en la superficha '$sf' ";
        }

        if (!empty($_POST['mayorista'])) {
            $mayorista = $_POST['mayorista'];
            $condicion = $condicion . " and sr.mayorista_code='$mayorista'";
            $texto = $texto . " de la mayorista '$mayorista'";
        }

        if (!empty($_POST['fecha_desde'])) {
            $fecha_desde = $_POST['fecha_desde'];
            $condicion = $condicion . "and DATE(sr.ts)>='$fecha_desde'";
            $texto = $texto . " desde fecha '$fecha_desde'";
        }

        if (!empty($_POST['fecha_hasta'])) {
            $fecha_hasta = $_POST['fecha_hasta'];
            $condicion = $condicion . "and DATE(sr.ts)<='$fecha_hasta'";
            $texto = $texto . " hasta fecha '$fecha_hasta'";
        }

        /**
         * Se realiza la consulta a BBDD con las condiciones indicadas.
         */

        $query = "select *,DATE_FORMAT(alr.fecha_entrada,'%d-%m-%Y') as fecha_entrada_dispo,DATE_FORMAT(alr.fecha_salida,'%d-%m-%Y') as fecha_salida_dispo
    from gestion_des.sr_error as sr, gestion_des.disponibilidad3 as d3,superfichas_des.hotel_fuente as hf,superfichas_des.api_superficha as aps, nges_des.api_linea_reserva as alr, nges_des.api_expediente as ae
    where sr.dispo3_id=d3.id
    and sr.mayorista_key=hf.fuente_hotel_clave
    and sr.id_cdr=alr.id_cdr
    and ae.expediente_id=alr.api_expediente_id
    and hf.superficha_id=aps.sf_id
    and d3.fecha_entrada > NOW()
        $condicion
        order by ts desc
        limit 10
";

        $resultado = mysqli_query($con, $query);
        echo "<h2>Errores $texto:</h2>";

        ?>
    <?php
    $n = mysqli_num_rows($resultado);
    if ($n > 0){

        /**
         * Pintamos la tabla con los resultados
         */
    ?>
<div class="table">
        <table border=1 cellspacing=1 cellpadding=0>
            <tr>
                <th>SF_ID</th>
                <th>NOMBRE</th>
                <th>FECHA</th>
                <th>ID_CDR</th>
                <th>MAYORISTA</th>
            </tr>

            <?php
            while ($result = $resultado->fetch_assoc()){

            /**
             * Hacemos un bucle para recorrer todos los resultados y en la tabla obtenemos los values del radiobutton para posteriormente utilizarlos en los botones.
             */
            ?>
            <tr>
                <td><input type="radio" name="radio" id="radio<?php echo $result['id_cdr']; ?>"
                           oninput="radioResultado('<?php echo $result['id_cdr']; ?>','<?php echo $result['referer']; ?>',
                               '<?php echo $result['destino_id']; ?>','<?php echo $result['idioma']; ?>','<?php echo $result['tipo_busqueda']; ?>',
                               '<?php echo $result['fecha_entrada_dispo']; ?>','<?php echo $result['fecha_salida_dispo']; ?>','<?php echo $result['fecha_entrada']; ?>','<?php echo $result['fecha_salida']; ?>','<?php echo $result['mayorista_key']; ?>')">
                    <label id="label2"
                           for="radio<?php echo $result['id_cdr']; ?>"><?php echo $result['sf_id']; ?></label></td>
                <td><?php echo $result['nombre']; ?></td>
                <td><?php echo $result['ts']; ?></td>
                <td><?php echo $result['id_cdr']; ?></td>
                <td><?php echo $result['mayorista_code']; ?></td>

                <?php
                }
                ?>
            </tr>
        </table>
</div>
        <?php
        /**
         * Creamos los botones para ejecutar las acciones necesarias.
         */

        /**
         * En el botón repetir búsqueda volvemos a lanzar al Sistema de Reservas la misma petición que hizo el cliente inicialmente.
         */
        ?>
       <button id='button' type="submit"
                onclick="window.open('https://dev7.secure.centraldereservas.com/~isabel/sr-master/cliente.cgi?tipo=' + tipo_busqueda + '&lang=' + idioma + '&id=' + entidad + '&rf=' +referer + '&fecha_entrada=' + fecha_entrada_dispo + '&fecha_salida=' + fecha_salida_dispo + '&numero_habs=1&no_adultos_hab1=2&auto=1', '_blank')">
            Repetir búsqueda
        </button>

        <?php
        /**
        * En el botón ver reserva en NGES, lanzamos contra el sistema de gestión interno el número de reserva.
        */
        ?>
        <button id='button' type="submit"
                onclick="window.open('http://desarrollo.centraldereservas.com/public/?idCdr=' + idCdr, '_blank')">Ver
            reserva en NGES
        </button>

        <?php
        /**
         * En el botón Crear FCI, creamos una incidencia en JIRA del departamento técnico.
         */
        ?>
        <button id='button' type="submit"
                onclick="window.open('https://inicio.cdrst.com/fci/reportar/producto/mayorista/?&opcion=erroresRecurrentes', '_blank')">
            Crear FCI
        </button>

        <?php
        /**
         * En el botón ver xml podemos ver el xml completo de la reserva
         */
        ?>
        <button id='button' type="submit"
                onclick="window.open('http://dev7.secure.centraldereservas.com/reserva/reservas/mas_datos.cgi?id_cdr=' + idCdr, '_blank')">
            Ver xml
        </button>

        <?php
        /**
         * En el botón congelar, se puede desactivar el hotel durante 48h.
         */
        ?>
        <button id='button' type="submit" onclick="congelar()">Congelar hotel</button>
    <?php
    } else {
        echo '<h4>No hay resultados</h4>';
    }
    ?>
        <?php
    }
}
$resultados = new Resultado();
$res = $resultados->resultados();

?>

</body>
</html>


