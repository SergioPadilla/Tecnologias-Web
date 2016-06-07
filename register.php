<?php session_start();?>

<!DOCTYPE html>
<html lang="en">
<?php
require_once('Configuracion/config.php');
require_once('PHP/libreria.php');

$mensaje="";
if($_SERVER['REQUEST_METHOD']=='POST'){
    $ser=NOMBRE_SERVIDOR;
    $usu=USUARIO_BD;
    $pass=PASS_BD;
    $base=NOMBRE_BD;

    $conexion=new Servidor_Base_Datos($ser,$usu,$pass,$base);
    $nick = $_POST['nick'];
    $password = md5($_POST['password']);
    $nombre = $_POST['nombre'];
    $dni = $_POST['dni'];
    $apellidos = $_POST['apellidos'];

    $sql="INSERT INTO usuarios (nick, password, nombre, apellidos, dni) VALUES ('".$nick."','".$password."','".$nombre."','".$apellidos."','".$dni."')";
    $exito = $conexion->ejecuta($sql);

    if($exito)
        echo "<script>location.href='login.php'</script>";
    else
        $mensaje = "usuario ya existe";
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Registro de usuarios">
    <meta name="author" content="Sergio Padilla López">
    <meta name="author" content="Javier Álvarez Castillo">
    <link rel="icon" href="imagenes/logo.png">

    <title>Registro</title>

    <!-- Bootstrap core CSS -->
    <link href="CSS/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="CSS/signin.css" rel="stylesheet">

    <!-- importamos funciones javascript -->
    <script src="JS/ejercicio.js"></script>
</head>

<body>
<!-- Contenedor principal -->
<div class="container">

    <form name="form_registro" class="form-signin" method="post" action="register.php" onsubmit="return validarRegistro()">
        <h2 class="form-signin-heading">Registro</h2>
        <input name = "nick" type="text" id="inputEmail" class="form-control" placeholder="Nick" required autofocus maxlength="20">
        <input name = "nombre" type="text" class="form-control" placeholder="Nombre" required maxlength="100">
        <input name = "apellidos" type="text" class="form-control" placeholder="Apellidos" required maxlength="100">
        <input name = "dni" type="text" class="form-control" placeholder="DNI" required maxlength="9">
        <input name = "password" type="password" class="form-control" placeholder="Contraseña" required maxlength="20">
        <input name = "password2" type="password" id="inputPassword" class="form-control" placeholder="Repite contraseña" required maxlength="20">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Aceptar</button>

        <label id="mensaje_error" class="form-signin"><?php echo $mensaje ?></label> <!-- Para mostrar mensajes de error -->
    </form>
</div>
</body>
</html>