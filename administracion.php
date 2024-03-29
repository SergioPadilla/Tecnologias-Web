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
    if($rol != 2) {
        update_usuarios($conexion, $_POST['editar_nick'], $_POST['editar_nombre'], $_POST['editar_apellidos'], $_POST['editar_dni'], $_POST['editar_rol']);
    }
    else{
        update_usuarios_profesional($conexion, $_POST['editar_nick'], $_POST['editar_nombre'], $_POST['editar_apellidos'], $_POST['editar_dni']);
    }
}
if(isset($_POST['editar_rol_id']) && !empty($_POST['editar_rol_id'])) {
    update_roles($conexion, $_POST['editar_rol_id'], $_POST['editar_rol_descripcion']);
}
if (isset($_POST['editar_recurso_nombre']) && !empty($_POST['editar_recurso_nombre'])) {
    update_recursos($conexion, $_POST['editar_recurso_codigo_recurso'], $_POST['editar_recurso_nombre'], $_POST['editar_recurso_descripcion'], $_POST['editar_recurso_lugar'], $_POST['editar_recurso_hora']);
}
if (isset($_POST['crear_recurso_nombre'])) {
    crear_recurso($conexion, $_POST['crear_recurso_nombre'], $_POST['crear_recurso_descripcion'], $_POST['crear_recurso_lugar'], $_POST['crear_recurso_hora'], $_SESSION[USUARIO]);
}
if (isset($_POST['crear_nick'])) {
    crear_usuario($conexion, $_POST['crear_nick'], $_POST['crear_password'], $_POST['crear_nombre'], $_POST['crear_apellidos'], $_POST['crear_dni'], $_POST['crear_rol']);
}
if(isset($_POST['mensaje_pantalla_turnos']) && !empty($_POST['mensaje_pantalla_turnos'])) {
    cargar_mensaje($conexion, $_POST["mensaje_pantalla_turnos_codigo_recurso"], $_POST['mensaje_pantalla_turnos']);
}
if(isset($_POST['editar_prioridad']) && !empty($_POST['editar_prioridad'])) {
    update_prioridad($conexion, $_POST['editar_prioridad'], $_POST['editar_prioridad_nick'], $_POST['editar_prioridad_codigo_recurso']);
}
if(isset($_POST['editar_estado']) && !empty($_POST['editar_estado'])) {
    update_estado($conexion, $_POST['editar_estado'], $_POST['editar_estado_nick'], $_POST['editar_estado_codigo_recurso']);
}
if(isset($_POST['password_actual']) && !empty($_POST['password_actual'])) {
    $sql = 'SELECT password FROM usuarios WHERE nick="'. $_SESSION[USUARIO] .'"';
    $conexion->consulta($sql);

    if($conexion->numero_filas() != 0) {
        $reg = $conexion->extraer_registro();
        if(md5($_POST['password_actual']) == $reg['password']) {
            $password_md5 = md5($_POST['editar_password']);
            update_password($conexion, $password_md5, $_SESSION[USUARIO]);
        }
        else{
            echo "<script>alert(\"La contraseña actual es erronea\")</script>";
        }
    }
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
            mostrar_perfil_administracion($conexion, $rol);
            break;
        case 'editar_perfil' :
            editar_usuario($conexion, $_SESSION[USUARIO], $rol);
            break;
        case 'mostrar_usuarios' :
            mostrar_usuarios($conexion);
            break;
        case 'editar_usuario' :
            editar_usuario($conexion, $usuario, $rol);
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
            mostrar_recursos_admin($conexion, $rol, $_SESSION[USUARIO]);
            break;
        case 'mostrar_recursos_profesional' :
            mostrar_recursos_profesional($conexion, $_SESSION[USUARIO]);
            break;
        case 'editar_recurso' :
            editar_recurso($conexion, $recurso);
            break;
        case 'eliminar_recurso' :
            eliminar_recurso($conexion, $recurso);
            mostrar_recursos($conexion);
            break;
        case 'crear_recurso' :
            crear_recurso_form();
            break;
        case 'crear_usuario' :
            crear_usuario_form();
            break;
        case 'pantalla_turnos' :
            pantalla_turnos_form($_POST["codigo_recurso"]);
            break;
        case 'gestionar_recurso' :
            gestionar_recurso($conexion, $_POST["codigo_recurso"]);
            break;
        case 'editar_estado' :
            editar_estado($conexion, $_POST['nick'], $_POST['estado'], $_POST['codigo_recurso']);
            break;
        case 'editar_prioridad' :
            editar_prioridad($conexion, $_POST['nick'], $_POST['prioridad'], $_POST['codigo_recurso']);
            break;
        case 'dar_de_baja' :
            eliminar_usuario($conexion, $_SESSION[USUARIO]);
            echo("<script>location.href='index.php'</script>");
            exit();
            break;
        case 'modificar_password' :
            modificar_password();
            break;
        case 'terminar' :
            $conexion2 = new Servidor_Base_Datos($ser,$usu,$pass,$base);
            terminar($conexion, $conexion2, $_POST["codigo_recurso"]);
            break;
        case 'siguiente' :
            $conexion2 = new Servidor_Base_Datos($ser,$usu,$pass,$base);
            siguiente($conexion, $conexion2, $_POST["codigo_recurso"]);
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
    <script type="text/javascript" src="JS/dropdown.js"></script>
   

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
            <a class="navbar-brand" href="administracion.php">Rowhard</a>
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
                    echo "<li onclick='mostrar_recursos_profesional()' ><a href=\"#\">Gestionar recursos</a></li>";
                ?>
            </ul>
        </div>
        <div id = "tabla" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        </div>
    </div>
</div>

</body>
</html>
