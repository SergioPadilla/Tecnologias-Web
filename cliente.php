<?php
session_start();
require_once('Configuracion/config.php');
require_once('PHP/libreria.php');

if($_SESSION[AUTENTICADO] != "si"){
    /**
     * Evitamos que entren directamente sin estar logueados
     */
    echo("<script>location.href='login.php'</script>");
    exit();
}
else {
    /**
     * Establecemos la conexion con la base de datos
     */
    $ser=NOMBRE_SERVIDOR;
    $usu=USUARIO_BD;
    $pass=PASS_BD;
    $base=NOMBRE_BD;

    $conexion = new Servidor_Base_Datos($ser,$usu,$pass,$base);
    $rol = $_SESSION[ROL];
    if($rol != 3){
        // Si el rol es 1 o 2 => pagina de administración
        echo("<script>location.href='administracion.php'</script>");
        exit();
    }
}

if (isset($_POST['editar_nick']) && !empty($_POST['editar_nick'])) {
    update_usuarios($conexion, $_POST['editar_nick'], $_POST['editar_password'], $_POST['editar_nombre'], $_POST['editar_apellidos'], $_POST['editar_dni']);
}

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
        case 'mostrar_perfil' :
            echo mostrar_perfil($conexion);
            break;
        case 'mostrar_recursos' :
            echo mostrar_recursos($conexion);
            break;
        case 'mostrar_colas' :
            echo mostrar_colas($conexion);
            break;
        case 'editar_perfil' :
            echo editar_perfil($conexion, $_SESSION[usuario]);
            break;
        case 'solicitar_turno' :
            echo solicitar_turno($conexion, $_POST[codigo_recurso], $_SESSION[usuario]);
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="imagenes/logo.png">

    <title><?php echo TITULO_VENTANA?></title>

    <!-- Bootstrap core CSS -->
    <link href="CSS/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="CSS/dashboard.css" rel="stylesheet">

    <!-- Jquery y funciones js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script type="text/javascript" src="JS/ejercicio.js"></script>

    <!-- css -->
    <link rel="stylesheet" type="text/css" href="CSS/estilo.css">
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Rowhard</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a onclick="mostrar_perfil();" href="#">Perfil</a></li>
                <li><a href="login.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <!-- Cargamos las opciones para un usuario básico. -->
                <li id='recursos' onclick="mostrar_recursos()"><a href='#'>Recursos<span class='sr-only'>(current)</span></a></li>
                <li id="colas" onclick="mostrar_colas()"><a href="#">Colas</a></li>
            </ul>
        </div>

        <div id = "tabla" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    </div>
</div>

</body>
</html>
