<!DOCTYPE>
<Head>
    <?php require_once ("Configuracion/config.php") ?>
    <?php require_once ("PHP/libreria.php") ?>
    <title>ROWHARD</title>
    <link rel="stylesheet" type="text/css" href="CSS/estilo.css">
    <script src="JS/ejercicio.js"></script>

    <!-- Bootstrap core CSS -->
    <link href="CSS/bootstrap.min.css" rel="stylesheet">
</Head>

<Body>
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
    </div>

    <!-- Pie -->
    <div class="pie">
        <ul class="autores">
            <li>&copy; Sergio Padilla López</li>
            <li>&copy; Javier Álvarez Castillo</li>
        </ul>
    </div>
</Body>