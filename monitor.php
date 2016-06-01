<!DOCTYPE>
<Head>
    <?php
        require_once ("Configuracion/config.php");
        require_once ("PHP/libreria.php");

        /**
         * Establecemos la conexion con la base de datos
         */
        $ser=NOMBRE_SERVIDOR;
        $usu=USUARIO_BD;
        $pass=PASS_BD;
        $base=NOMBRE_BD;

        $conexion = new Servidor_Base_Datos($ser,$usu,$pass,$base);
    ?>
    <title>ROWHARD</title>
    <link rel="stylesheet" type="text/css" href="CSS/estilo.css">
    <script src="JS/ejercicio.js"></script>

    <!-- Bootstrap core CSS -->
    <link href="CSS/bootstrap.min.css" rel="stylesheet">
</Head>

<Body onload="setInterval('location.reload()',7000)">
    <!-- Cabecera -->
    <div class="cabecera">
            <img id="logo" src="imagenes/logo.png" ALT="rowhard" align="middle">
            <p id="fecha">
                <script>getDate()</script> <noscript>Error Javascript</noscript>
            </p>
        <iframe id="hora" src="http://free.timeanddate.com/clock/i5700uuq/n2322/tles4/fn15/fs39/fcfff/tct/pct/th1" frameborder="0" width="141" height="47" allowTransparency="true"></iframe>
    </div>

    <!-- Cuerpo central de la web -->
    <div class="cuerpo">
        <div id="titulo">
            <h1><?php echo TITULO_INDEX ?></h1>
        </div>

        <div class="tablas">
            <?php
                mostrarColas();
            ?>
        </div>
        <div id="mensaje_informativo">
            <?php
                /**
                 * Muestra los mensajes de los recursos si los hay
                 */
                $cadena .= "<div class=\"table-responsive\">";
                $cadena .= "<table class=\"table table-striped\">";
                $cadena .= "<tbody>";

                $sql = 'SELECT * FROM recursos';
                $conexion->consulta($sql);
                if($conexion->numero_filas() != 0){
                    while($reg=$conexion->extraer_registro()) {
                        if($reg["mensaje"]) {
                            $cadena .= "<tr>";
                            $cadena .= "<td style='color: blue; text-align: right'>" . $reg["nombre"] . "</td><td style='color: red; text-align: left' >" . $reg["mensaje"] . "</td>";
                            $cadena .= "</tr>\n";
                        }
                    }
                }
                $cadena .= "</tbody>";
                $cadena .= "</table>";
                $cadena .= "</div>";

                echo $cadena;
            ?>
        </div>
    </div>

    <!-- Pie -->
    <div class="pie">
        <ul class="autores">
            <li>&copy; Sergio Padilla López</li>
            <li>&copy; Javier Álvarez Castillo</li>
        </ul>
    </div>
</Body>