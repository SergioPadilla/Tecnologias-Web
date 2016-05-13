<?php session_start();?>

<!DOCTYPE html>
<html lang="en">
<?php
require_once('libreria.php');

$mensaje="";
if($_SERVER['REQUEST_METHOD']=='POST'){
    $ser="localhost";
    $usu="ejercicio_pw";
    $pass="pass_ejercicio_pw";
    $base="20077113E";

    $conexion=new Servidor_Base_Datos($ser,$usu,$pass,$base);
    $sql='SELECT * FROM usuarios WHERE nick="' . $_POST['user'] . '" AND password ="' . $_POST['password'] . '"';
    $conexion->consulta($sql);

    if($conexion->numero_filas()!=0){
        $_SESSION["user"]=$_POST['user'];
        $_SESSION["autenticado"]="si";
        echo ("<script>location.href='administracion.php'</script>");
    }
    else{
        $usuario=$_POST['user'];
        echo $sql;
        $mensaje='<br><p style="color:blue"><b>Datos incorrectos, intentelo de nuevo.</b></p>';
    }
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Signin Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="container">

    <form class="form-signin" method="post" action="login.php" >
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input name = "user" type="text" id="inputEmail" class="form-control" placeholder="User" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input name = "password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>
    <?php echo $mensaje ?>
</div> <!-- /container -->


<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>