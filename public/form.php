<?php
/**
 * En este fichero se encuentran los 2 formularios principales de búsqueda.
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
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ErrorGes</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="index.js"></script>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<header>
    <h1 id="h1_header">ErrorGes</h1>
    <form name="formLogin" action="index.php" method="POST">
        <fieldset id="formLogin">
            <p>Usuario conectado como <?= $name ?></p>
            <input type='submit' value='Desconectar' name='desconectar'>
        </fieldset>
    </form>
</header>
<div id="formularioBusqueda">
    <form name="formularioBusqueda" action="Resultado.php" method="post">
        <fieldset>
            <legend class="legend_tittle">BÚSQUEDA DE ERRORES</legend>
            <fieldset>
                <legend>HOTEL</legend>
                <input type="text" id="nombre_hotel" name="nombre_hotel" size="30">
                <label for="nombre">Nombre:</label><br>
                <input type="text" id="localidad" name="localidad" size="30">
                <label for="localidad">Localidad:</label><br>
                <button id="button" onclick="obtenerHoteles(); return false;">Buscar hotel</button>
                <div id="resultados"></div>
            </fieldset>
            <fieldset>
                <legend>SF</legend>
                <input type="text" name="sf" id="sf" size="10">
                <label for="sf">SF:</label>
                <div id="resultados"></div>
            </fieldset>
            <fieldset>
                <legend>CLIENTE</legend>
                <input type="text" name="telefono" id="telefono" maxlength="9" onfocusout="validarTelefono()">
                <label for="telefono">Teléfono:</label><br>
                <input name="email" type="text" id="email" onfocusout="validarEmail()" />
                <label for="email">Email:</label>
            </fieldset>
            <fieldset>
                <legend>MAYORISTA</legend>
                <input type="text" name="mayorista" id="mayorista" size="10">
                <label for="mayorista">Mayorista:</label>
            </fieldset>
            <fieldset>
                <legend>FECHA</legend>
                <input type="date" name="fecha_desde" id="fecha_desde" size="40" required>
                <label for="fecha_desde">Fecha desde: (*)</label><br>
                <input type="date" name="fecha_hasta" id="fecha_hasta" size="40" required>
                <label for="fecha_hasta">Fecha hasta: (*)</label>
            </fieldset>
            <p>
                <input id="button" type="submit" value="Buscar" name="submit">
                <input id="button" type="reset" value="Borrar" onclick="borrarHoteles()">
                <input type='hidden' value='<?= $name ?>' name='name'>
            </p>
        </fieldset>
    </form>
</div>
<div id="errores"></div>


<div id="formularioCron">
    <fieldset>
        <legend class="legend_tittle">CRON ENVÍO MAYORISTAS</legend>
        <form name="cron" action="Envios.php" method="post">
            <fieldset>
                <legend>FECHA ENVÍO</legend>
                <label for="fecha">Fecha:</label>
                <input type="date" name="fecha" id="fecha" size="40">
            </fieldset>
            <fieldset>
                <legend>MAYORISTA</legend>
                <label for="mayorista">Mayorista:</label>
                <input type="text" name="mayorista" id="mayorista" size="10">
            </fieldset>
            <fieldset>
                <legend>HOTEL</legend>
                <label for="sf_hotel">SF Hotel:</label>
                <input type="text" name="sf_hotel" id="sf_hotel" size="10">
            </fieldset>
            <input id="button" type="submit" value="Buscar" name="submit">
            <input id="button" type="reset" value="Borrar">
            <input type='hidden' value='<?= $name ?>' name='name'>
    </fieldset>
    </form>
</div>
</body>
</html>
