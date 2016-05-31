<?php 
session_start();
require_once('Configuracion/config.php');
require_once('PHP/libreria.php');
if($_SESSION[AUTENTICADO] != "si"){
    echo("<script>location.href='login.php'</script>");
    exit();
}
else {
    $ser=NOMBRE_SERVIDOR;
    $usu=USUARIO_BD;
    $pass=PASS_BD;
    $base=NOMBRE_BD;

    $conexion = new Servidor_Base_Datos($ser,$usu,$pass,$base);
    $rol = $_SESSION[ROL];

    if($rol == "3"){
        // Ningún usuario básico puede entrar a administración
        echo("<script>location.href='cliente.php'</script>");
        exit();
    }
}
if (isset($_POST['editar_nick']) && !empty($_POST['editar_nick'])) {
    update_usuarios($conexion, $_POST['editar_nick'], $_POST['editar_password'], $_POST['editar_nombre'], $_POST['editar_apellidos'], $_POST['editar_dni'], $_POST['editar_rol']);
}
if(isset($_POST['editar_permiso']) && !empty($_POST['editar_permiso'])) {
    update_permisos($conexion, $_POST['editar_permiso'], $_POST['editar_permiso_descripcion']);
}
if(isset($_POST['editar_rol']) && !empty($_POST['editar_rol'])) {
    update_roles($conexion, $_POST['editar_rol'], $_POST['editar_rol_descripcion']);
}
if (isset($_POST['editar_recurso_nombre']) && !empty($_POST['editar_recurso_nombre'])) {
    update_recursos($conexion, $_POST['editar_recurso_nombre'], $_POST['editar_recurso_descripcion'], $_POST['editar_recurso_lugar'], $_POST['editar_recurso_hora'], $_POST['editar_recurso_nick']);
}
if (isset($_POST['crear_recurso_nombre'])) {
    crear_recurso($conexion, $_POST['crear_recurso_nombre'], $_POST['crear_recurso_descripcion'], $_POST['crear_recurso_lugar'], $_POST['crear_recurso_hora'], $_SESSION[USUARIO]);
}

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];

    if(isset($_POST['usuario']) && !empty($_POST['usuario'])) {
        $usuario = $_POST['usuario'];
    }
    if(isset($_POST['permiso']) && !empty($_POST['permiso'])) {
        $permiso = $_POST['permiso'];
    }
    if(isset($_POST['rol']) && !empty($_POST['rol'])) {
        $rol_post = $_POST['rol'];
    }
    if(isset($_POST['recurso']) && !empty($_POST['recurso'])) {
        $recurso = $_POST['recurso'];
    }

    switch($action) {
        case 'mostrar_perfil' :
            echo mostrar_perfil_administracion($conexion);
            break;
        case 'editar_perfil' :
            echo editar_usuario($conexion, $_SESSION[usuario]);
            break;
        case 'mostrar_usuarios' :
            mostrar_usuarios($conexion);
            break;
        case 'editar_usuario' :
            editar_usuario($conexion, $usuario);
            break;
        case 'eliminar_usuario' :
            eliminar_usuario($conexion, $usuario);
            mostrar_usuarios($conexion);
            break;
        case 'mostrar_roles' :
            mostrar_roles($conexion);
            break;
        case 'editar_rol' :
            editar_rol($conexion, $rol_post);
            break;
        case 'eliminar_rol' :
            eliminar_rol($conexion, $rol_post);
            mostrar_roles($conexion);
            break;
        case 'mostrar_recursos_admin' :
            mostrar_recursos_admin($conexion);
            break;
        case 'editar_recurso' :
            editar_recurso($conexion, $recurso);
            break;
        case 'eliminar_recurso' :
            eliminar_recurso($conexion, $recurso);
            mostrar_recursos($conexion);
            break;
        case 'crear_recurso' :
            echo crear_recurso_form();
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

    <title>Administración</title>

    <!-- Bootstrap core CSS -->
    <link href="CSS/bootstrap.min.css" rel="stylesheet">

    <link href="CSS/estilo.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="CSS/dashboard.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script type="text/javascript" src="JS/ejercicio.js"></script>
   

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
                <li><a onclick="mostrar_perfil_administracion();" href="#">Perfil</a></li>
                <li><a href="login.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <!-- Hay que consultar los permisos del usuario que ha accedido y ver las opciones del menú según permisos. -->
                <?php
                if ($rol == "1") {
                    echo "<li onclick='mostrar_usuarios()'><a href=\"#\">Usuarios </a></li>";
                    echo "<li onclick='mostrar_roles()'><a href=\"#\">Roles</a></li>";
                }
                ?>
                <li onclick='mostrar_recursos_admin()'><a href="#">Recursos</a></li>

                <?php
                if ($rol == "2")
                    echo "<li><a href=\"#\">Colas</a></li>";
                ?>
            </ul>
        </div>
        <div id = "tabla" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        </div>
    </div>
</div>

</body>
</html>
