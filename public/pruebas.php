<?php
spl_autoload_register(function ($class) {
    require("$class.php");
});


$name = filter_input(INPUT_GET, "name") ?? $_POST['name'] ?? null;

?>

<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ErrorGes</title>
    <script type="text/javascript" src="index.js"></script>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
<header>
    <h1 id="h1_header"><a id="a_header" href="form.php">ErrorGes</a></h1>

    <form name="formLogin" action="index.php" method="POST">
        <fieldset id="formLogin">
            <p>Usuario conectado como <?= $name ?></p>
            <input type='submit' value='Desconectar' name='desconectar'>
        </fieldset>
    </form>
</header>

<?php

class Resultado
{

    public function resultados()
    {

        $mysqli = new Conexion();
        $con= $mysqli->connect();

        $condicion="";
        $texto= "";
        $email="";
        $telefono="";
        $sf="";
        $mayorista="";
        $fecha="";

        if (!empty($_POST['email'])) {
            $email = $_POST['email'];
            $condicion=$condicion . " and ae.mail_cliente='$email'";
            $texto = $texto. " con email '$email'";

        }


        if (!empty($_POST['telefono'])) {
            $telefono = $_POST['telefono'];
            $condicion=$condicion . " and ae.mail_telefono='$telefono'";
            $texto = $texto. " con teléfono '$telefono'";

        }


        if (!empty($_POST['sf'])) {
            $sf = $_POST['sf'];
            $condicion=$condicion . " and aps.sf_id='$sf' ";
            $texto = $texto. " en la superficha '$sf' ";

        }

        if (!empty($_POST['mayorista'])) {
            $mayorista = $_POST['mayorista'];
            $condicion=$condicion . " and sr.mayorista_code='$mayorista'";
            $texto = $texto. " de la mayorista '$mayorista'";


        }

        if (!empty($_POST['fecha'])) {
            $fecha = $_POST['fecha'];
            $condicion=$condicion . "and DATE(sr.ts)='$fecha'";
            $texto = $texto. "en fecha '$fecha'";

        }




        $query ="select *,DATE_FORMAT(alr.fecha_entrada,'%d-%m-%Y') as fecha_entrada_dispo,DATE_FORMAT(alr.fecha_salida,'%d-%m-%Y') as fecha_salida_dispo

    from gestion_des.sr_error as sr, gestion_des.disponibilidad3 as d3,superfichas_des.hotel_fuente as hf,superfichas_des.api_superficha as aps, nges_des.api_linea_reserva as alr, nges_des.api_expediente as ae
    where sr.dispo3_id=d3.id
    and sr.mayorista_key=hf.fuente_hotel_clave
    and sr.id_cdr=alr.id_cdr
    and ae.expediente_id=alr.api_expediente_id
    and hf.superficha_id=aps.sf_id
    and d3.fecha_entrada > NOW()
        $condicion
        order by ts desc
        limit 5
";


        $resultado=mysqli_query($con,$query);

        echo "<h2>Errores $texto:</h2>";
        ?>

        <table border=1 cellspacing=1 cellpadding=0>
            <tr>
                <th>SF_ID</th>
                <th>NOMBRE</th>
                <th>FECHA</th>
                <th>ID_CDR</th>
                <th>MAYORISTA</th>
            </tr>

            <?php
            while($result = $resultado->fetch_assoc()){
            ?>
                <tr><td><input type="radio" name="radio" id="radio"  onclick="radio();" value="<?php echo $sf=$result['id_cdr'];?>">

                        <?php echo $sf=$result['sf_id'];?></td>
                <td><?php echo $nombre=$result['nombre'];?></td>
                <td><?php echo $ts=$result['ts'];?></td>
                <td><?php echo $id_cdr=$result['id_cdr'];?></td>
                <td><?php echo $mayorista_code=$result['mayorista_code'];?></td>
                <td><?php echo $fecha_entrada = $result['fecha_entrada_dispo'];?></td>
                <td><?php echo $fecha_salida = $result['fecha_salida_dispo'];?> </td>




                    <?php
                    $entidad=$result['destino_id'];
                    $referer=$result['referer'];
                    $idioma = $result['idioma'];


                    }

                    ?>
                </tr>
        </table>

        <script>
            function radio(){
                var radio= document.querySelector('input[name="radio"]:checked').value;
                alert(radio);
            }
        </script>
        <button id='button' type="submit" onclick="window.open('http://desarrollo.centraldereservas.com/public/?idCdr=<?=$id_cdr?>', '_blank')">Ver reserva en NGES</button>

        <!--
                <button id='button' type="submit" onclick="window.open('http://desarrollo.centraldereservas.com/public/?idCdr=<?=$id_cdr?>', '_blank')">Ver reserva en NGES</button>
                <button id='button' type="submit" onclick="window.open('https://inicio.cdrst.com/fci/reportar/producto/mayorista/?&opcion=erroresRecurrentes', '_blank')">Crear FCI</button>
                <button id='button' type="submit" onclick="window.open('https://dev7.secure.centraldereservas.com/reserva/reservas/mas_datos.cgi?id_cdr=<?=$id_cdr?>', '_blank')">Ver xml</button>
                <button id='button' type="submit" onclick="window.open('https://dev7.secure.centraldereservas.com/~isabel/sr-master/cliente.cgi?tipo=A&lang=<?=$idioma?>&id=<?=$entidad?>&rf=<?=$id_cdr?>&fecha_entrada=<?=$fecha_entrada?>&fecha_salida=<?=$fecha_salida?>&numero_habs=1&no_adultos_hab1=2', '_blank')">Repetir búsqueda</button>
                <button id='button' type="submit" onclick="location.href='/'">Congelar hotel</button>
        -->


        <?php

    }
}
$resultados = new Resultado();
$res = $resultados->resultados();


?>



</body>
</html>


