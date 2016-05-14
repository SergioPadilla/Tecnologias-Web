<?php session_start();?>

<!DOCTYPE html>
<html lang="en">
<?php
require_once('Configuracion/config.php');
require_once('PHP/libreria.php');

$mensaje="";
if($_SERVER['REQUEST_METHOD']=='POST'){
    $ser=nombre_servidor;
    $usu=usuario_bd;
    $pass=pass_bd;
    $base=nombre_bd;

    $conexion=new Servidor_Base_Datos($ser,$usu,$pass,$base);
    $sql='SELECT * FROM usuarios WHERE nick="' . $_POST['user'] . '" AND password ="' . $_POST['password'] . '"';
    $conexion->consulta($sql);

    if($conexion->numero_filas()!=0){
        /**
         * Control de sesión si pincha en "recuerdame"
         */
        if($_POST['remember']) {
            echo '<p> dentro del if</p>';
            $year = time() + 31536000;
            setcookie('remember_me', $_POST['user'], $year, "/");
        }
        elseif(!$_POST['remember']) {
            echo '<p> dentro del else</p>';
            if(isset($_COOKIE['remember_me'])) {
                echo '<p> dentro del elseif</p>';
                $past = time() - 100;
                setcookie(remember_me, gone, $past);
            }
        }

        // Guarda sesión y accede a la siguiente página
        $_SESSION["user"]=$_POST['user'];
        $_SESSION["autenticado"]="si";
        $fila = $conexion->extraer_registro();
        $rol = $fila["rol"];
        $_SESSION["rol"]=$rol;

        if($rol != 3)
            echo "<script>location.href='administracion.php'</script>";
        else
            echo "<label>$rol</label>";
    }
    else{
        $usuario=$_POST['user'];
        echo $sql;
        $mensaje='Datos incorrectos, inténtelo de nuevo.';
    }
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
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- importamos funciones javascript -->
    <script src="JS/ejercicio.js"></script>
</head>

<body>
<!-- Contenedor principal -->
<div class="container">

    <form name="form_registro" class="form-signin" method="post" action="login.php" onsubmit="return validarRegistro()">
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