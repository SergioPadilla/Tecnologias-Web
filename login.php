<?php session_start();?>

<!DOCTYPE html>
<html lang="en">
<?php
require_once('Configuracion/config.php');
require_once('PHP/libreria.php');

$_COOKIE['remember_me']=""; // mirar cookies
$_SESSION[AUTENTICADO] = 'no';

$mensaje="";
if($_SERVER['REQUEST_METHOD']=='POST'){
    $ser=NOMBRE_SERVIDOR;
    $usu=USUARIO_BD;
    $pass=PASS_BD;
    $base=NOMBRE_BD;

    $password = md5($_POST['password']);
    $conexion=new Servidor_Base_Datos($ser,$usu,$pass,$base);
    $sql='SELECT * FROM usuarios WHERE nick="' . $_POST['user'] . '" AND password ="' . $password . '"';
    $conexion->consulta($sql);

    if($conexion->numero_filas()!=0){
        /**
         * Control de sesión si pincha en "recuerdame"
         */
        if($_POST['remember']) {
            $year = time() + 31536000;
            setcookie('remember_me', $_POST['user'], $year, "/");
        }
        elseif(!$_POST['remember']) {
            if(isset($_COOKIE['remember_me'])) {
                $past = time() - 100;
                setcookie(remember_me, gone, $past);
            }
        }

        // Guarda sesión y accede a la siguiente página
        $_SESSION[USUARIO]=$_POST['user'];
        $_SESSION[AUTENTICADO]="si";
        $fila = $conexion->extraer_registro();
        $rol = $fila["rol"];
        $_SESSION[ROL]=$rol;

        if($rol != 3)
            echo "<script>location.href='administracion.php'</script>";
        else
            echo "<script>location.href='cliente.php'</script>";
    }
    else{
        $usuario=$_POST['user'];
        $mensaje='Datos incorrectos, inténtelo de nuevo.';
    }
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Inicio de sesión para usuarios">
    <meta name="author" content="Sergio Padilla López">
    <meta name="author" content="Javier Álvarez Castillo">
    <link rel="icon" href="imagenes/logo.png">

    <title>Iniciar Sesión</title>

    <!-- Bootstrap core CSS -->
    <link href="CSS/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="CSS/signin.css" rel="stylesheet">
</head>

<body>
    <!-- Contenedor principal -->
    <div class="container">

        <form class="form-signin" method="post" action="login.php" >
            <h2 class="form-signin-heading">Inicio Sesión</h2>
            <input name = "user" type="text" id="inputEmail" class="form-control" placeholder="Usuario"
                   required autofocus maxlength="20" value="<?php echo $_COOKIE['remember_me']; ?>">
            <input name = "password" type="password" id="inputPassword" class="form-control" placeholder="Contraseña" required maxlength="20">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember"
                           <?php
                           if(isset($_COOKIE['remember_me']))
                                echo 'checked="checked"';
                           else
                                echo '';
                           ?>
                    > Recuérdame
                </label>
                <a id="registro" href="registro.php"> Crear cuenta </a> <!-- Opcion para registrarse -->
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Aceptar</button>

            <label id="mensaje_error" class="form-signin"><?php echo $mensaje ?></label> <!-- Para mostrar mensajes de error -->
        </form>
    </div>
</body>
</html>
