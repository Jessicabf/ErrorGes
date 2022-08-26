<?php

/**
 * En este archivo index.php se realiza el Login de Usuario, arrastrando la variable nombre al resto de ficheros.
 */
$msj = filter_input(INPUT_GET, 'msj', FILTER_SANITIZE_STRING) ?? null;

if (isset($_POST["submit"])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
    if (!empty($name) && trim($name) != ""  && !empty($pass) && trim($pass) != "") {
        header("Location:form.php?name=$name");
        exit();
    } else {
        $msj = "Datos incorrectos";
    }
}
?>


<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ErrorGes</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div id="contenedor">
    <div id="central">
        <div id="login">
            <h2 class="h2_header"> <?= $msj ?></h2>
            <div class="titulo">Bienvenido</div>
            <form action="index.php" method="post">
                <input type="text" name="name" placeholder="Usuario" required>
                <input type="password" placeholder="Contraseña" name="pass" required>
                <button type="submit" title="Entrar" value="Entrar" name="submit">Login</button>
            </form>
        </div>
    </div>
</div>
</form>
</body>
</html>
