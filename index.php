<?php
    require_once("Configuracion/config.php");
    require_once('PHP/libreria.php');

    /**
     * Establecemos la conexion con la base de datos
     */
    $ser=NOMBRE_SERVIDOR;
    $usu=USUARIO_BD;
    $pass=PASS_BD;
    $base=NOMBRE_BD;

    $conexion = new Servidor_Base_Datos($ser,$usu,$pass,$base);
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

    <!-- JS -->
    <script type="text/javascript" src="JS/ejercicio.js"></script>

    <!-- CSS -->
    <link href="CSS/estilo.css" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="CSS/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="CSS/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="CSS/sticky-footer-navbar.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="JS/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="JS/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?php echo TITULO_INDEX ?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="login.php">Iniciar Sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Begin page content -->
<div class="container">
    <div class="page-header">
        <h1>¡ACCEDE A LOS RECURSOS QUE TENEMOS DISPONIBLES!</h1>
    </div>
        <?php
            /**
             * Muestra todos los recursos disponibles
             */
            $cadena .= "<div class=\"table-responsive\">";
            $cadena .= "<table class=\"table table-striped\">";
            $cadena .= "<thead>";
            $cadena .= "<tr>";
            $cadena .= "<th>Nombre</th>";
            $cadena .= "<th>Descripción</th>";
            $cadena .= "<th>Lugar</th>";
            $cadena .= "<th>Hora de comienzo</th>";
            $cadena .= "<th>Opciones</th>";
            $cadena .= "</tr>";
            $cadena .= "</thead>";
            $cadena .= "<tbody>";

            $sql = 'SELECT * FROM recursos';
            $conexion->consulta($sql);
            if($conexion->numero_filas() != 0){
                while($reg=$conexion->extraer_registro()) {
                    $cadena .= "<tr>";
                    $cadena .= "<td>".$reg["nombre"]."</td><td>".$reg["descripcion"]."</td><td>".$reg["lugar"]."</td><td>".$reg["hora_comienzo"]."</td><td><button onclick='cargarLogin()' class='button'>Solicitar turno</button></td>";
                    $cadena .= "</tr>\n";
                }
            }
            $cadena .= "</tbody>";
            $cadena .= "</table>";
            $cadena .= "</div>";

            echo $cadena;
        ?>
</div>

<footer class="footer">
    <div class="container">
        <ul class="autores">
            <li>&copy; Sergio Padilla López</li>
            <li>&copy; Javier Álvarez Castillo</li>
        </ul>
    </div>
</footer>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="JS/jquery.min.js"><\/script>')</script>
<script src="JS/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="JS/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
