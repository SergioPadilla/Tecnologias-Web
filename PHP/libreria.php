<?php
    require_once ("Configuracion/config.php");
/********************************************************************************************/
/*			Clase para controlar la base de datos MySQL*/
/********************************************************************************************/

class Servidor_Base_Datos{
    private $servidor, $usuario, $pass, $base_datos, $descriptor;
    private $resultado;

    function __construct($ser,$usu,$passw,$base){
        $this->servidor=$ser;
        $this->usuario=$usu;
        $this->pass=$passw;
        $this->base_datos=$base;
        $this->conectar_base_datos();
    }

    private function conectar_base_datos(){
        $this->descriptor=mysqli_connect($this->servidor,$this->usuario,$this->pass,$this->base_datos);
    }

    public function consulta($consulta){
        $this->resultado=mysqli_query($this->descriptor,$consulta);
    }

    public function ejecuta($consulta){
        return mysqli_query($this->descriptor,$consulta);
    }

    public function extraer_registro(){
        if($fila=mysqli_fetch_array($this->resultado,MYSQLI_ASSOC)){
            return $fila;
        } else {
            return false;
        }
    }

    public function extraer_registro_indice($indice){
        mysqli_data_seek($this->resultado,$indice);
        $fila=mysqli_fetch_array($this->resultado,MYSQLI_ASSOC);
        return ($fila);
    }

    public function numero_filas(){
        return mysqli_num_rows($this->resultado);
    }

    function __destruct(){
        mysqli_close($this->descriptor);
    }
}

/********************************************************************************************/
/*			Fin Clase para controlar la base de datos SQL*/
/********************************************************************************************/

function mostrarColas(){
    /**
     * Muestra una tabla con las colas a las que está apuntado un usuario
     */
    $ser=NOMBRE_SERVIDOR;
    $usu=USUARIO_BD;
    $pass=PASS_BD;
    $base=NOMBRE_BD;

    $conexion=new Servidor_Base_Datos($ser,$usu,$pass,$base);
    $sql='SELECT * FROM colas c, recursos r WHERE r.codigo = c.codigo_recurso AND c.estado=2 ORDER BY c.prioridad,c.id';
    $conexion->consulta($sql);

    if($conexion->numero_filas() != 0){
        $cadena = "<div class=\"table-responsive\">";
        $cadena .= "<table id=\"columns_index\" class=\"table table-striped\">";
        $cadena .= "<colgroup>";
        $cadena .= "<col width='25%'>";
        $cadena .= "<col width='25%'>";
        $cadena .= "<col width='25%'>";
        $cadena .= "<col width='25%'>";
        $cadena .= "</colgroup>";
        $cadena .= "<thead>";
        $cadena .= "<tr>";
        $cadena .= "<th>Usuario</th>";
        $cadena .= "<th>Nombre</th>";
        $cadena .= "<th>Lugar</th>";
        $cadena .= "<th>Hora</th>";
        $cadena .= "</tr>";
        $cadena .= "</thead>";
        $cadena .= "<tbody>";

        while($reg=$conexion->extraer_registro()) {
            $cadena .= "<tr>";
            $cadena .= "<td>".$reg["codigo_usuario"]."</td><td>".$reg["nombre"]."</td><td>".$reg["lugar"]."</td><td>".$reg["hora"]."</td>";
            $cadena .= "</tr>\n";
        }

        $cadena .= "</tbody>";
        $cadena .= "</table>";
        $cadena .= "</div>";

        echo $cadena;
    }
    else{
        echo "<h2>No hay turnos pendientes</h2>";
    }
}

function mostrar_perfil($conexion, $nick) {
    /**
     * Muestra el perfil del usuario
     */
    $cadena = "<h2 class=\"sub-header\">Perfil</h2>";
    $cadena .= "<div class=\"table-responsive\">";
    $cadena .= "<table class=\"table table-striped\">";
    $cadena .= "<thead>";
    $cadena .= "<tr>";
    $cadena .= "<th>Nick</th>";
    $cadena .= "<th>Nombre</th>";
    $cadena .= "<th>Apellidos</th>";
    $cadena .= "<th>DNI</th>";
    $cadena .= "<th>Opciones</th>";
    $cadena .= "</tr>";
    $cadena .= "</thead>";
    $cadena .= "<tbody>";

    $sql = 'SELECT * FROM usuarios WHERE nick="'. $nick.'"';
    $conexion->consulta($sql);

    if($conexion->numero_filas() != 0){
        while($reg=$conexion->extraer_registro()) {
            $cadena .= "<tr>";
            $cadena .= "<td>".$reg["nick"]."</td><td>".$reg["nombre"]."</td><td>".$reg["apellidos"]."</td><td>".$reg["dni"]."</td><td><span onclick='editar_perfil()' id=\"glyphicon\" class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span></td>";
            $cadena .= "</tr>\n";
        }
    }
    $cadena .= "</tbody>";
    $cadena .= "</table>";
    $cadena .= "</div>";

    $cadena .= "<button class='dar_de_baja' onclick='dar_de_baja()'>Dar de baja</button><button class='modificar_password' onclick='modificar_password_cliente()' style='margin-left: 10px'>Modificar Contraseña</button>";
    
    echo $cadena;
}

function modificar_password(){
    /**
     * Muestra un formulario modificar contraseña
     */
    $mensaje = "";

    $cadena = "<form name='form_editar_password' class=\"form-horizontal\" action=\"administracion.php\" method=\"post\" onsubmit='return validarPassword()'>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Contraseña actual</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"password_actual\" class=\"form-control\" placeholder=\"Contraseña actual\" maxlength=\"20\" required >";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Contraseña</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_password\" class=\"form-control\" placeholder=\"Contraseña\" maxlength=\"20\" required >";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Repite contraseña</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_password_2\" class=\"form-control\" placeholder=\"Contraseña\" required maxlength=\"20\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<div class=\"col-sm-offset-2 col-sm-10\">";
    $cadena .= "<button type=\"submit\" class=\"btn btn-default\">Modificar</button>";
    $cadena .= "</div>";
    $cadena .= "</div>";

    $cadena .= "<label id=\"mensaje_error\">" . $mensaje . "</label>"; // Para mostrar mensajes de error
    $cadena .= "</form>";

    echo $cadena;
}

function modificar_password_cliente(){
    /**
     * Muestra un formulario modificar contraseña
     */
    $mensaje = "";

    $cadena = "<form name='form_editar_password' class=\"form-horizontal\" action=\"cliente.php\" method=\"post\" onsubmit='return validarPassword()'>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Contraseña actual</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"password_actual\" class=\"form-control\" placeholder=\"Contraseña actual\" maxlength=\"20\" required >";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Contraseña</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_password\" class=\"form-control\" placeholder=\"Contraseña\" maxlength=\"20\" required >";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Repite contraseña</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_password_2\" class=\"form-control\" placeholder=\"Contraseña\" required maxlength=\"20\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<div class=\"col-sm-offset-2 col-sm-10\">";
    $cadena .= "<button type=\"submit\" class=\"btn btn-default\">Modificar</button>";
    $cadena .= "</div>";
    $cadena .= "</div>";

    $cadena .= "<label id=\"mensaje_error\">" . $mensaje . "</label>"; // Para mostrar mensajes de error
    $cadena .= "</form>";

    echo $cadena;
}

function mostrar_perfil_administracion($conexion, $rol) {
    /**
     * Muestra el perfil del usuario
     */
    $cadena = "<h2 class=\"sub-header\">Perfil</h2>";
    $cadena .= "<div class=\"table-responsive\">";
    $cadena .= "<table class=\"table table-striped\">";
    $cadena .= "<thead>";
    $cadena .= "<tr>";
    $cadena .= "<th>Nick</th>";
    $cadena .= "<th>Nombre</th>";
    $cadena .= "<th>Apellidos</th>";
    $cadena .= "<th>DNI</th>";
    if($rol != "2") {
        $cadena .= "<th>Rol</th>";
    }
    $cadena .= "<th>Opciones</th>";
    $cadena .= "</tr>";
    $cadena .= "</thead>";
    $cadena .= "<tbody>";

    $sql = 'SELECT * FROM usuarios WHERE nick="'. $_SESSION[USUARIO].'"';
    $conexion->consulta($sql);

    if($conexion->numero_filas() != 0){
        while($reg=$conexion->extraer_registro()) {
            $cadena .= "<tr>";
            $cadena .= "<td>".$reg["nick"]."</td><td>".$reg["nombre"]."</td><td>".$reg["apellidos"]."</td><td>".$reg["dni"]."</td>";
            if($rol != "2") {
                $cadena .= "<td>" . $reg["rol"] . "</td>";
            }
            $cadena .= "<td><span onclick='editar_perfil_administracion()' id=\"glyphicon\" class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span></td>";
            $cadena .= "</tr>\n";
        }
    }
    $cadena .= "</tbody>";
    $cadena .= "</table>";
    $cadena .= "</div>";

    $cadena .= "<button class='dar_de_baja' onclick='dar_de_baja_administracion()'>Dar de baja</button><button class='modificar_password' onclick='modificar_password()' style='margin-left: 10px'>Modificar Contraseña</button>";

    echo $cadena;
}


function mostrar_colas($conexion, $nick) {
    /**
     * Muestra colas del usuario
     */
    $sql='SELECT * FROM colas c, recursos r WHERE c.estado != "3" AND r.codigo = c.codigo_recurso AND c.nick="'.$nick. '"';
    $conexion->consulta($sql);

    if($conexion->numero_filas() != 0){

            $cadena = "<h2 class=\"sub-header\">Colas</h2>";
            $cadena .= "<div class=\"table-responsive\">";
            $cadena .= "<table class=\"table table-striped\">";
            $cadena .= "<thead>";
            $cadena .= "<tr>";
            $cadena .= "<th>Nombre</th>";
            $cadena .= "<th>Descripción</th>";
            $cadena .= "<th>Lugar</th>";
            $cadena .= "<th>Hora de comienzo</th>";
            $cadena .= "</tr>";
            $cadena .= "</thead>";
            $cadena .= "<tbody>";

            while ($reg = $conexion->extraer_registro()) {
                $cadena .= "<tr>";
                $cadena .= "<td>" . $reg["nombre"] . "</td><td>" . $reg["descripcion"] . "</td><td>" . $reg["lugar"] . "</td><td>" . $reg["hora_comienzo"] . "</td><td><button onclick='dar_baja_recurso(".$reg["codigo"].")' class='button'>Dar de baja</button><button onclick='info(".$reg["codigo"].")' class='button'>Información</button></td>";
                $cadena .= "</tr>\n";
            }

            $cadena .= "</tbody>";
            $cadena .= "</table>";
            $cadena .= "</div>";

            echo $cadena;
    } else {
        echo "<h1>NO ESTAS APUNTADO A NINGÚN RECURSO</h1>";
    }
}

function info($conexion, $codigo_recurso, $nick){
    /**
     * Muestra al usuario su posición en la cola
     */
    $sql='SELECT * FROM colas WHERE codigo_recurso ="'.$codigo_recurso.'" AND estado="1" ORDER BY prioridad, id';
    $conexion->consulta($sql);
    $n = 0;

    if($conexion->numero_filas() != 0){
        while ($reg = $conexion->extraer_registro()) {
            if($reg['nick'] == $nick){
                echo "<script>alert(\"Quedan: ".$n." usuarios delante\")</script>";
            }
            else{
                $n++;
            }
        }
    }
}

function mostrar_recursos($conexion) {
    /**
     * Muestra los recursos disponibles para el usuario (cliente.php)
     */
    $cadena = "<h2 class=\"sub-header\">Recursos</h2>";
    $cadena .= "<div class=\"table-responsive\">";
    $cadena .= "<table class=\"table table-striped\">";
    $cadena .= "<thead>";
    $cadena .= "<tr>";
    $cadena .= "<th>Nombre</th>";
    $cadena .= "<th>Descripción</th>";
    $cadena .= "<th>Lugar</th>";
    $cadena .= "<th>Hora de comienzo</th>";
    $cadena .= "</tr>";
    $cadena .= "</thead>";
    $cadena .= "<tbody>";

    $sql = 'SELECT * FROM recursos ORDER BY hora_comienzo';
    $conexion->consulta($sql);

    if($conexion->numero_filas() != 0){
        while($reg=$conexion->extraer_registro()) {
            $codigo = $reg["codigo"];
            $cadena .= "<tr>";
            $cadena .= "<td>".$reg["nombre"]."</td><td>".$reg["descripcion"]."</td><td>".$reg["lugar"]."</td><td>".$reg["hora_comienzo"]."</td><td><button onclick='solicitar_turno(\"$codigo\");' class='button'>Solicitar turno</button></td>";
            $cadena .= "</tr>\n";
        }
    }
    $cadena .= "</tbody>";
    $cadena .= "</table>";
    $cadena .= "</div>";

    echo $cadena;
}


/**
 * Gestión usuarios
 */

function mostrar_usuarios($conexion) {
    /**
     * Muestra una tabla con todos los usuarios del sistema
     */
    $cadena = "<h2 class=\"sub-header\">Usuarios</h2>";
    $cadena .= "<div class=\"table-responsive\">";
    $cadena .= "<table class=\"table table-striped\">";
    $cadena .= "<thead>";
    $cadena .= "<tr>";
    $cadena .= "<th>Nick</th>";
    $cadena .= "<th>Nombre</th>";
    $cadena .= "<th>Apellidos</th>";
    $cadena .= "<th>DNI</th>";
    $cadena .= "<th>Rol</th>";
    $cadena .= "<th>Opciones</th>";
    $cadena .= "</tr>";
    $cadena .= "</thead>";
    $cadena .= "<tbody>";

    $sql = 'SELECT * FROM usuarios';
    $conexion->consulta($sql);

    if($conexion->numero_filas() != 0){
        while($reg=$conexion->extraer_registro()) {
            $cadena .= "<tr>";
            $cadena .= "<td>".$reg["nick"]."</td><td>".$reg["nombre"]."</td><td>".$reg["apellidos"]."</td><td>".$reg["dni"]."</td><td>".$reg["rol"]."</td><td><span  onclick=\"  editar_usuario('".$reg["nick"]."'); \" id=\"glyphicon\" class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>    <span onclick=\"  eliminar_usuario('".$reg["nick"]."'); \"  id=\"glyphicon\" class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></td>";
            $cadena .= "</tr>\n";
        }
    }
    $cadena .= "</tbody>";
    $cadena .= "</table>";
    $cadena .= "<p id=\"prueba\"></p>";
    $cadena .= "</div>";

    $cadena .= "<button class='crear_nuevo' onclick='crear_usuario()'>Crear nuevo usuario</button>";

    echo $cadena;
}

function editar_usuario($conexion, $usuario, $rol) {
    /**
     * Muestra un formulario que permite editar los datos del usuario
     *
     * in:
     *   $usuario: nick del usuario
     */
    $mensaje="";
    $sql = "SELECT * FROM usuarios WHERE nick = \"" . $usuario . "\" ";
    $conexion->consulta($sql);
    if($conexion->numero_filas() != 0){
        $reg=$conexion->extraer_registro();
    }

    $cadena = "<form name='form_editar_perfil' class=\"form-horizontal\" action=\"administracion.php\" method=\"post\" onsubmit='return validarEditarPerfilUsuario()'>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Nick</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<p class=\"form-control-static\">" . $reg['nick'] . "</p>";
    $cadena .= "<input type=\"hidden\" name=\"editar_nick\" value=\"" . $reg['nick'] . "\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Nombre</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_nombre\" class=\"form-control\" placeholder=\"Nombre\" value=\"" . $reg['nombre'] . "\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Apellidos</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_apellidos\" class=\"form-control\" placeholder=\"Apellidos\" value=\"" . $reg['apellidos'] . "\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">DNI</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_dni\" class=\"form-control\" placeholder=\"DNI\" value=\"" . $reg['dni'] . "\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    if($rol != "2") {
        $cadena .= "<div class=\"form-group\">";
        $cadena .= "<label class=\"col-sm-2 control-label\">Rol</label>";
        $cadena .= "<div class=\"col-sm-10\">";
        $cadena .= "<input name=\"editar_rol\" class=\"form-control\" placeholder=\"Rol\" value=\"" . $reg['rol'] . "\">";
        $cadena .= "</div>";
        $cadena .= "</div>";
    }
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<div class=\"col-sm-offset-2 col-sm-10\">";
    $cadena .= "<button type=\"submit\" class=\"btn btn-default\">Editar</button>";
    $cadena .= "</div>";
    $cadena .= "</div>";

    $cadena .= "<label id=\"mensaje_error\">". $mensaje ."</label>"; // Para mostrar mensajes de error
    $cadena .= "</form>";

    echo $cadena;
}

function update_usuarios($conexion, $nick, $nombre, $apellidos, $dni, $rol) {
    /**
     * Modifica un usuario
     *
     * in:
     *    Datos a modificar
     */
    $sql = "UPDATE usuarios SET nombre=\"" . $nombre . "\", apellidos=\"" . $apellidos . "\", dni=\"" . $dni . "\", rol=\"" . $rol . "\" WHERE nick=\"" . $nick . "\"";
    $exito = $conexion->ejecuta($sql);
    
    if($exito) {
        echo "<script>alert(\"Editado con éxito.\")</script>";
    }
    else{
        echo "<script>alert(\"Error al editar.\")</script>";
    }
}

function update_perfil_usuario($conexion, $nick, $nombre, $apellidos, $dni) {
    /**
     * Modifica un usuario
     *
     * in:
     *    Datos a modificar
     */
    $sql = "UPDATE usuarios SET nombre=\"" . $nombre . "\", apellidos=\"" . $apellidos . "\", dni=\"" . $dni . "\" WHERE nick=\"" . $nick . "\"";
    $exito = $conexion->ejecuta($sql);

    if($exito) {
        echo "<script>alert(\"Editado con éxito.\")</script>";
    }
    else{
        echo "<script>alert(\"Error al editar.\")</script>";
    }
}

function update_usuarios_profesional($conexion, $nick, $nombre, $apellidos, $dni) {
    /**
     * Modifica un usuario para profesional
     *
     * in:
     *    Datos a modificar sin rol
     */
    $sql = "UPDATE usuarios SET nombre=\"" . $nombre . "\", apellidos=\"" . $apellidos . "\", dni=\"" . $dni . "\" WHERE nick=\"" . $nick . "\"";
    $exito = $conexion->ejecuta($sql);

    if($exito) {
        echo "<script>alert(\"Editado con éxito.\")</script>";
    }
    else{
        echo "<script>alert(\"Error al editar.\")</script>";
    }
}

function eliminar_usuario($conexion, $nick) {
    /**
     * Función para eliminar usuario
     *
     * in:
     *   $nick = nick del usuario a borrar
     */
    $sql = "DELETE FROM usuarios WHERE nick=\"" . $nick . "\"";
    $exito = $conexion->ejecuta($sql);

    if($exito) {
        echo "<script>alert(\"Editado con éxito.\")</script>";
    }
    else{
        echo "<script>alert(\"Error al editar.\")</script>";
    }
}

function crear_usuario_form() {
    /**
     * Muestra un formulario crear un usuario
     */
    $mensaje="";

    $cadena = "<form name='form_crear_usuario' class=\"form-horizontal\" action=\"administracion.php\" method=\"post\" onsubmit='return validarCrearUsuario()'>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Nick</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"crear_nick\" class=\"form-control\" placeholder=\"Nick\" maxlength=\"20\" required >";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Contraseña</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"crear_password\" class=\"form-control\" placeholder=\"Contraseña\" maxlength=\"20\" required >";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Repite contraseña</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"crear_password_2\" class=\"form-control\" placeholder=\"Contraseña\" required maxlength=\"20\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Nombre</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"crear_nombre\" class=\"form-control\" placeholder=\"Nombre\" required >";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Apellidos</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"crear_apellidos\" class=\"form-control\" placeholder=\"Apellidos\" required >";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">DNI</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"crear_dni\" class=\"form-control\" placeholder=\"DNI\" required >";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Rol</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"crear_rol\" class=\"form-control\" placeholder=\"Rol\" required >";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<div class=\"col-sm-offset-2 col-sm-10\">";
    $cadena .= "<button type=\"submit\" class=\"btn btn-default\">Crear</button>";
    $cadena .= "</div>";
    $cadena .= "</div>";

    $cadena .= "<label id=\"mensaje_error\">". $mensaje ."</label>"; // Para mostrar mensajes de error
    $cadena .= "</form>";

    echo $cadena;
}

function crear_usuario($conexion, $nick, $password, $nombre, $apellidos, $dni, $rol){
    /**
     * Función para crear un usuario
     */
    $password_md5 = md5($password);
    $sql = "INSERT INTO usuarios (nick, password, nombre, apellidos, dni, rol) VALUES ('" . $nick . "','" . $password_md5 . "','" . $nombre . "','" . $apellidos . "','" . $dni . "','" . $rol . "')";
    $exito = $conexion->ejecuta($sql);

    if ($exito) {
        echo "<script>alert(\"Usuario creado con éxito\")</script>";
    }
    else
        echo "<script>alert(\"Usuario ya existe\")</script>";
}


/**
 * 
 * Gestión roles
 * 
 */
function mostrar_roles($conexion) {
    /**
     * Muestra una tabla con todos los roles
     */
    $cadena = "<h2 class=\"sub-header\">Roles</h2>";
    $cadena .= "<div class=\"table-responsive\">";
    $cadena .= "<table class=\"table table-striped\">";
    $cadena .= "<thead>";
    $cadena .= "<tr>";
    $cadena .= "<th>Rol</th>";
    $cadena .= "<th>Descripción</th>";
    $cadena .= "<th>Opciones</th>";
    $cadena .= "</tr>";
    $cadena .= "</thead>";
    $cadena .= "<tbody>";

    $sql = 'SELECT * FROM roles';
    $conexion->consulta($sql);

    if($conexion->numero_filas() != 0){
        while($reg=$conexion->extraer_registro()) {

            $cadena .= "<tr>";
            $cadena .= "<td>".$reg["rol"]."</td><td>".$reg["descripcion"]."</td><td><span  onclick=\"  editar_rol('".$reg["rol"]."'); \" id=\"glyphicon\" class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>    <span onclick=\"  eliminar_rol('".$reg["rol"]."'); \"  id=\"glyphicon\" class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></td>";
            $cadena .= "</tr>\n";
        }
    }
    $cadena .= "</tbody>";
    $cadena .= "</table>";
    $cadena .= "</div>";

    echo $cadena;
}


function editar_rol($conexion, $rol) {
    /**
     * Muestra un formulario para editar los permisos del rol
     */
    $sql = "SELECT * FROM roles WHERE rol = \"" . $rol . "\" ";
    $conexion->consulta($sql);
    if($conexion->numero_filas() != 0){
        $reg=$conexion->extraer_registro();
    }

    $cadena = "<form class=\"form-horizontal\" action=\"administracion.php\" method=\"post\">";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Rol</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<p class=\"form-control-static\">" . $reg['rol'] . "</p>";
    $cadena .= "<input type=\"hidden\" name=\"editar_rol_id\" value=\"" . $reg['rol'] . "\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Descripción</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_rol_descripcion\" class=\"form-control\" placeholder=\"Descripción\" value=\"". $reg['descripcion'] ."\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<div class=\"col-sm-offset-2 col-sm-10\">";
    $cadena .= "<button type=\"submit\" class=\"btn btn-default\">Editar</button>";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "</form>";

    echo $cadena;
}

function update_roles($conexion, $rol, $descripcion) {
    /**
     * Función para modificar un rol
     *
     * in:
     *    $rol = rol que modifica
     *    $descripcion = nueva descripcion
     */
    $sql = "UPDATE roles SET descripcion=\"" . $descripcion . "\" WHERE rol=\"" . $rol . "\"";
    $exito = $conexion->ejecuta($sql);

    if($exito) {
        echo "<script>alert(\"Editado con éxito.\")</script>";
    }
    else{
        echo "<script>alert(\"Error al editar.\")</script>";
    }
}

function eliminar_rol($conexion, $rol) {
    /**
     * Función para eliminar un rol
     */
    $sql = "DELETE FROM roles
            WHERE rol=\"" . $rol . "\"";
    $conexion->consulta($sql);
    echo "<script>alert(\"Eliminado con éxito.\")</script>";
}

/**
 * Gestión recursos
 */

function mostrar_recursos_admin($conexion, $rol, $nick) {
    /**
     * Muestra todos los recursos disponibles
     */
    $cadena = "<h2 class=\"sub-header\">Recursos</h2>";
    $cadena .= "<div class=\"table-responsive\">";
    $cadena .= "<table class=\"table table-striped\">";
    $cadena .= "<thead>";
    $cadena .= "<tr>";
    $cadena .= "<th>Código</th>";
    $cadena .= "<th>Nombre</th>";
    $cadena .= "<th>Descripción</th>";
    $cadena .= "<th>Lugar</th>";
    $cadena .= "<th>Hora de comienzo</th>";
    $cadena .= "<th>Creador</th>";
    $cadena .= "<th>Opciones</th>";
    $cadena .= "</tr>";
    $cadena .= "</thead>";
    $cadena .= "<tbody>";

    if($rol == "2") {
        $sql = 'SELECT * FROM recursos WHERE nick ="'. $nick.'"';
    }
    else {
        $sql = 'SELECT * FROM recursos';
    }
    $conexion->consulta($sql);
    if($conexion->numero_filas() != 0){
        while($reg=$conexion->extraer_registro()) {
            $cadena .= "<tr>";
            $cadena .= "<td>".$reg["codigo"]."</td><td>".$reg["nombre"]."</td><td>".$reg["descripcion"]."</td><td>".$reg["lugar"]."</td><td>".$reg["hora_comienzo"]."</td><td>".$reg["nick"]."</td><td><span  onclick=\"  editar_recurso('" . $reg["codigo"] . "'); \" id=\"glyphicon\" class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>    <span onclick=\"  eliminar_recurso('".$reg["codigo"]."'); \"  id=\"glyphicon\" class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></td>";
            $cadena .= "</tr>\n";
        }
    }
    $cadena .= "</tbody>";
    $cadena .= "</table>";
    $cadena .= "</div>";

    $cadena .= "<button class='crear_nuevo' onclick='crear_recurso()'>Crear nuevo recurso</button>";

    echo $cadena;
}

function mostrar_recursos_profesional($conexion, $nick) {
    /**
     * Muestra todos los recursos del profesional para gestionarlos
     */
    $cadena = "<h2 class=\"sub-header\">Gestionar recursos</h2>";
    $cadena .= "<div class=\"table-responsive\">";
    $cadena .= "<table class=\"table table-striped\">";
    $cadena .= "<thead>";
    $cadena .= "<tr>";
    $cadena .= "<th>Código</th>";
    $cadena .= "<th>Nombre</th>";
    $cadena .= "<th>Descripción</th>";
    $cadena .= "<th>Lugar</th>";
    $cadena .= "<th>Hora de comienzo</th>";
    $cadena .= "<th>Creador</th>";
    $cadena .= "<th>Opciones</th>";
    $cadena .= "</tr>";
    $cadena .= "</thead>";
    $cadena .= "<tbody>";

    $sql = 'SELECT * FROM recursos WHERE nick ="'. $nick.'"';
    $conexion->consulta($sql);
    if($conexion->numero_filas() != 0){
        while($reg=$conexion->extraer_registro()) {
            $codigo = $reg["codigo"];
            $cadena .= "<tr>";
            $cadena .= "<td>".$codigo."</td><td>".$reg["nombre"]."</td><td>".$reg["descripcion"]."</td><td>".$reg["lugar"]."</td><td>".$reg["hora_comienzo"]."</td><td>".$reg["nick"]."</td><td><button onclick='gestionar_recurso(\"$codigo\");' class='button'>Gestionar</button></td>";
            $cadena .= "</tr>\n";
        }
    }
    $cadena .= "</tbody>";
    $cadena .= "</table>";
    $cadena .= "</div>";

    $cadena .= "<button class='crear_nuevo' onclick='crear_recurso()'>Crear nuevo recurso</button>";

    echo $cadena;
}

function editar_recurso($conexion, $recurso) {
    /**
     * Muestra un formulario para editar un recurso
     */
    $sql = "SELECT * FROM recursos WHERE codigo = \"" . $recurso . "\" ";
    $conexion->consulta($sql);
    if($conexion->numero_filas() != 0){
        $reg=$conexion->extraer_registro();
    }

    $cadena = "<form class=\"form-horizontal\" action=\"administracion.php\" method=\"post\">";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Nombre</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_recurso_nombre\" class=\"form-control\" placeholder=\"Nombre\" value=\"". $reg['nombre'] ."\">";
    $cadena .= "<input type=\"hidden\" name=\"editar_recurso_codigo_recurso\" value=\"" . $recurso . "\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Descripción</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_recurso_descripcion\" class=\"form-control\" placeholder=\"Descripción\" value=\"". $reg['descripcion'] ."\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Lugar</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_recurso_lugar\" class=\"form-control\" placeholder=\"Lugar\" value=\"". $reg['lugar'] ."\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Fecha y Hora de comienzo</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_recurso_hora\" class=\"form-control\" placeholder=\"Formato: YYYY-MM-DD hh-mm-ss\" value=\"". $reg['hora_comienzo'] ."\">";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<div class=\"col-sm-offset-2 col-sm-10\">";
    $cadena .= "<button type=\"submit\" class=\"btn btn-default\">Editar</button>";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "</form>";

    echo $cadena;
}

function update_recursos($conexion, $codigo, $nombre, $descripcion, $lugar, $hora) {
    /**
     * Modifica un recuso
     *
     * in:
     *    datos a modificar
     */
    $sql = "UPDATE recursos SET nombre=\"" . $nombre . "\", descripcion=\"" . $descripcion . "\", lugar=\"" . $lugar . "\", hora_comienzo=\"" . $hora . "\" WHERE codigo=\"" . $codigo . "\"";
    $exito = $conexion->ejecuta($sql);

    if($exito) {
        echo "<script>alert(\"Editado con éxito.\")</script>";
    }
    else{
        echo "<script>alert(\"Error al editar.\")</script>";
    }
}

function cargar_mensaje($conexion, $codigo, $mensaje) {
    /**
     * Modifica un recuso
     *
     * in:
     *    mensaje
     */
    $sql = "UPDATE recursos SET mensaje=\"" . $mensaje . "\" WHERE codigo=\"" . $codigo . "\"";
    $exito = $conexion->ejecuta($sql);

    if($exito) {
        echo "<script>alert(\"Editado con éxito.\")</script>";
    }
    else{
        echo "<script>alert(\"Error al editar.\")</script>";
    }
}

function eliminar_recurso($conexion, $recurso) {
    /**
     * Elimina recuso
     *
     * in:
     *    $recurso = codigo de recurso
     */
    $sql = "DELETE FROM recursos WHERE codigo=\"" . $recurso . "\"";
    $exito = $conexion->ejecuta($sql);

    if($exito) {
        echo "<script>alert(\"Editado con éxito.\")</script>";
    }
    else{
        echo "<script>alert(\"Error al editar.\")</script>";
    }
}

function crear_recurso_form() {
    /**
     * Muestra un formulario para crear un recurso
     */
    $cadena = "<form class=\"form-horizontal\" action=\"administracion.php\" method=\"post\">";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Nombre</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"crear_recurso_nombre\" class=\"form-control\" placeholder=\"Nombre\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Descripción</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"crear_recurso_descripcion\" class=\"form-control\" placeholder=\"Descripción\" >";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Lugar</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"crear_recurso_lugar\" class=\"form-control\" placeholder=\"Lugar\" >";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Fecha y Hora de comienzo</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"crear_recurso_hora\" class=\"form-control\" placeholder=\"Formato: YYYY-MM-DD hh:mm:ss\" >";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<div class=\"col-sm-offset-2 col-sm-10\">";
    $cadena .= "<button type=\"submit\" class=\"btn btn-default\">Crear</button>";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "</form>";

    echo $cadena;
}

function crear_recurso($conexion, $nombre, $descripcion, $lugar, $hora, $nick) {
    /**
     * Función para crear un recurso
     */
    $sql = "INSERT INTO recursos (nombre, descripcion, lugar, hora_comienzo, nick) VALUES ('" . $nombre . "','" . $descripcion . "','" . $lugar . "','" . $hora . "','" . $nick . "')";
    $exito = $conexion->ejecuta($sql);
    if ($exito) {
        $codigo = "";
        $sql = "SELECT codigo FROM recursos WHERE nick = \"" . $nick . "\" AND nombre = \"" . $nombre . "\" AND descripcion = \"" . $descripcion . "\" AND lugar = \"" . $lugar . "\"";
        $conexion->consulta($sql);
        if($conexion->numero_filas() != 0){
            $reg=$conexion->extraer_registro();
            $codigo = $reg['codigo'];
        }
        echo "<script>alert(\"Registro realizado con exito. Sú código de recurso es: ".$codigo." \")</script>";
    }
    else
        echo "<script>alert(\"Error al registrar el recurso\")</script>";
}

function gestionar_recurso($conexion, $codigo_recurso) {

    /**
     * Muestra todos los recursos del profesional para gestionarlos
     */
    $cadena = "<h2 class=\"sub-header\">Gestionar recurso</h2>";
    $cadena .= "<div class=\"table-responsive\">";
    $cadena .= "<table class=\"table table-striped\">";
    $cadena .= "<thead>";
    $cadena .= "<tr>";
    $cadena .= "<th>Usuario</th>";
    $cadena .= "<th>Estado</th>";
    $cadena .= "<th>Prioridad</th>";
    $cadena .= "</tr>";
    $cadena .= "</thead>";
    $cadena .= "<tbody>";

    $sql = "SELECT nick, estado, prioridad FROM colas WHERE codigo_recurso = \"" . $codigo_recurso . "\" AND estado != \"3\" ORDER BY estado DESC, prioridad DESC";
    $conexion->consulta($sql);
    if($conexion->numero_filas() != 0){
        while($reg=$conexion->extraer_registro()) {
            if ($reg["estado"] == "1")
                $estado = "En espera";
            else if ($reg["estado"] == "2")
                $estado = "Siendo atendido";
            else
                $estado = "Atendido";

            $cadena .= "<tr>";
            $cadena .= "<td>".$reg["nick"]."</td><td>".$estado." <span  onclick=\"  editar_estado('" . $reg["nick"] . "','" . $reg["estado"] . "','" . $codigo_recurso . "'); \" id=\"glyphicon\" class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> </td><td>".$reg["prioridad"]."  <span  onclick=\"  editar_prioridad('" . $reg["nick"] . "','" . $reg["prioridad"] . "','" . $codigo_recurso . "'); \" id=\"glyphicon\" class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span></td>";
            $cadena .= "</tr>\n";
        }
    }
    $cadena .= "</tbody>";
    $cadena .= "</table>";
    $cadena .= "<button class=\"crear_nuevo\"  onclick=\"pantalla_turnos('" . $codigo_recurso . "')\">Pantalla de turnos</button>";
    $cadena .= "<button class=\"crear_nuevo\"  onclick=\"siguiente('" . $codigo_recurso . "')\">Siguiente</button>";
    $cadena .= "<button class=\"crear_nuevo\"  onclick=\"terminar('" . $codigo_recurso . "')\">Terminar</button>";
    $cadena .= "</div>";

    echo $cadena;
}

function editar_perfil($conexion, $usuario) {
    /**
     * Muestra un formulario que permite editar los datos del usuario
     *
     * in:
     *   $usuario: nick del usuario
     */
    $mensaje="";
    $sql = "SELECT * FROM usuarios WHERE nick = \"" . $usuario . "\" ";
    $conexion->consulta($sql);
    if($conexion->numero_filas() != 0) {
        $reg = $conexion->extraer_registro();

        $cadena = "<form name=\"form_editar_perfil\" class=\"form-horizontal\" action=\"cliente.php\" method=\"post\" onsubmit=\"return validarEditarPerfilUsuario()\">";
        $cadena .= "<div class=\"form-group\">";
        $cadena .= "<label class=\"col-sm-2 control-label\">Nombre</label>";
        $cadena .= "<div class=\"col-sm-10\">";
        $cadena .= "<input name=\"editar_nombre\" class=\"form-control\" placeholder=\"Nombre\" value=\"" . $reg['nombre'] . "\">";
        $cadena .= "</div>";
        $cadena .= "</div>";
        $cadena .= "<div class=\"form-group\">";
        $cadena .= "<label class=\"col-sm-2 control-label\">Apellidos</label>";
        $cadena .= "<div class=\"col-sm-10\">";
        $cadena .= "<input name=\"editar_apellidos\" class=\"form-control\" placeholder=\"Apellidos\" value=\"" . $reg['apellidos'] . "\">";
        $cadena .= "</div>";
        $cadena .= "</div>";
        $cadena .= "<div class=\"form-group\">";
        $cadena .= "<label class=\"col-sm-2 control-label\">DNI</label>";
        $cadena .= "<div class=\"col-sm-10\">";
        $cadena .= "<input name=\"editar_dni\" class=\"form-control\" placeholder=\"DNI\" value=\"" . $reg['dni'] . "\">";
        $cadena .= "</div>";
        $cadena .= "</div>";
        $cadena .= "<div class=\"form-group\">";
        $cadena .= "<div class=\"col-sm-offset-2 col-sm-10\">";
        $cadena .= "<button type=\"submit\" class=\"btn btn-default\">Editar</button>";
        $cadena .= "</div>";
        $cadena .= "</div>";

        $cadena .= "<label id=\"mensaje_error\">". $mensaje ."</label>"; // Para mostrar mensajes de error
        $cadena .= "</form>";

        echo $cadena;

    }
}

function solicitar_turno($conexion, $codigo_recurso, $nick){
    /**
     * Calculo el código de usuario formado por 4 primera letras del nombre de recurso + 4 últimos digitos de s DNI
     */
    $sql = 'SELECT nombre FROM recursos WHERE codigo="'.$codigo_recurso.'"';
    $conexion->consulta($sql);
    if($conexion->numero_filas() != 0) {
        $reg = $conexion->extraer_registro();
        $nombre = $reg["nombre"];
        $codigo = substr($nombre,0,4);

        $sql = 'SELECT dni FROM usuarios WHERE nick="'.$nick.'"';
        $conexion->consulta($sql);
        if($conexion->numero_filas() != 0) {
            $reg = $conexion->extraer_registro();
            $dni = $reg["dni"];
            $codigo .= substr($dni,4);
        
            $sql = "INSERT INTO colas (codigo_recurso, nick, codigo_usuario) VALUES ('" . $codigo_recurso . "','" . $nick . "','" . $codigo . "')";
            $exito = $conexion->ejecuta($sql);
        
            if ($exito) {
                $sql = 'SELECT id FROM colas WHERE codigo_recurso="'.$codigo_recurso.'" ORDER BY id';
                $conexion->consulta($sql);
                $personas_delante = $conexion->numero_filas() - 1;
                $rango = $personas_delante;
                if($personas_delante < 5){
                    $rango = "menos de 5";
                }
                else{
                    $personas_delante = $personas_delante%10;
                    if($personas_delante == 0){
                        $rango = "mas de 5";
                    }
                    else{
                        $rango = "mas de" . $personas_delante*10;
                    }
                }
                echo "<script>alert(\"Registro realizado con exito. Sú código es: ".$codigo.". Tiene, ".$rango." personas delante de usted.\")</script>";
            }
            else
                echo "<script>alert(\"No puedes apuntarte a este recurso.\")</script>";
        }
    }
}

function dar_baja_recurso($conexion, $codigo_recurso, $nick) {
    /**
     * Función para eliminar una fila de las colas
     */
    $sql = "DELETE FROM colas WHERE nick=\"" . $nick . "\" AND codigo_recurso=\"" . $codigo_recurso . "\"";
    $conexion->consulta($sql);
    echo "<script>alert(\"Eliminado con éxito.\")</script>";
}

function pantalla_turnos_form($codigo_recurso){
    /**
     * Muestra un formulario para cargar un mensaje en la pantalla de turnos
     */
    $cadena = "<h2 class=\"sub-header\">Escribe el mensaje para mostrar en la pantalla de turnos</h2>";
    $cadena .= "<form name='form_pantalla_turnos' class=\"form-horizontal\" action=\"administracion.php\" method=\"post\">";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Mensaje</label>";
    $cadena .= "<input type=\"hidden\" name=\"mensaje_pantalla_turnos_codigo_recurso\" value=\"" . $codigo_recurso . "\">";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"mensaje_pantalla_turnos\" class=\"form-control\" placeholder=\"Mensaje\">";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<div class=\"col-sm-offset-2 col-sm-10\">";
    $cadena .= "<button type=\"submit\" class=\"btn btn-default\">Enviar</button>";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "</form>";
    
    echo $cadena;
}

function editar_estado($conexion, $nick, $estado, $codigo_recurso) {

    $cadena = "<form name='form_editar_estado' class=\"form-horizontal\" action=\"administracion.php\" method=\"post\">";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Estado</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<label class=\"radio-inline\">";
    $cadena .= "<input type=\"radio\" name=\"editar_estado\" id=\"inlineRadio1\" value=\"1\"> En espera";
    $cadena .= "</label>";
    $cadena .= "<label class=\"radio-inline\">";
    $cadena .= "<input type=\"radio\" name=\"editar_estado\" id=\"inlineRadio2\" value=\"2\"> Siendo atendido";
    $cadena .= "</label>";
    $cadena .= "<label class=\"radio-inline\">";
    $cadena .= "<input type=\"radio\" name=\"editar_estado\" id=\"inlineRadio3\" value=\"3\"> Atendido";
    $cadena .= "</label>";
    $cadena .= "<input type=\"hidden\" name=\"editar_estado_nick\" value=\"" . $nick . "\">";
    $cadena .= "<input type=\"hidden\" name=\"editar_estado_codigo_recurso\" value=\"" . $codigo_recurso . "\">";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<div class=\"col-sm-offset-2 col-sm-10\">";
    $cadena .= "<button type=\"submit\" class=\"btn btn-default\">Editar</button>";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "</form>";

    echo $cadena;
}

function editar_prioridad($conexion, $nick, $prioridad, $codigo_recurso) {
    $cadena = "<form name='form_editar_prioridad' class=\"form-horizontal\" action=\"administracion.php\" method=\"post\">";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Prioridad</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<select name='editar_prioridad' class=\"form-control\">";
    $cadena .= "<option>1</option>";
    $cadena .= "<option>2</option>";
    $cadena .= "<option>3</option>";
    $cadena .= "<option>4</option>";
    $cadena .= "<option>5</option>";
    $cadena .= "<option>6</option>";
    $cadena .= "<option>7</option>";
    $cadena .= "<option>8</option>";
    $cadena .= "<option>9</option>";
    $cadena .= "<option>10</option>";
    $cadena .= "</select>";
    $cadena .= "<input type=\"hidden\" name=\"editar_prioridad_nick\" value=\"" . $nick . "\">";
    $cadena .= "<input type=\"hidden\" name=\"editar_prioridad_codigo_recurso\" value=\"" . $codigo_recurso . "\">";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<div class=\"col-sm-offset-2 col-sm-10\">";
    $cadena .= "<button type=\"submit\" class=\"btn btn-default\">Editar</button>";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "</form>";

    echo $cadena;
}

function update_prioridad($conexion, $prioridad, $nick, $codigo_recurso) {
    /**
     * Modifica la prioridad de un usuario en un recurso
     *
     * in:
     *    datos a modificar
     */
    $sql = "UPDATE colas SET prioridad=\"" . $prioridad . "\" WHERE codigo_recurso=\"" . $codigo_recurso . "\" AND nick=\"" . $nick . "\"";
    $exito = $conexion->ejecuta($sql);

    if($exito) {
        echo "<script>alert(\"Editado con éxito.\")</script>";
    }
    else{
        echo "<script>alert(\"Error al editar.\")</script>";
    }
}

function update_estado($conexion, $estado, $nick, $codigo_recurso) {
    /**
     * Modifica el estado de un usuario en un recurso
     *
     * in:
     *    datos a modificar
     */
    $sql = "UPDATE colas SET estado=\"" . $estado . "\" WHERE codigo_recurso=\"" . $codigo_recurso . "\" AND nick=\"" . $nick . "\"";
    $exito = $conexion->ejecuta($sql);

    if($exito) {
        echo "<script>alert(\"Editado con éxito.\")</script>";
    }
    else{
        echo "<script>alert(\"Error al editar.\")</script>";
    }
}

function update_password($conexion, $password, $nick) {
    /**
     * Modifica la contraseña de un usuario
     *
     * in:
     *    Datos a modificar
     */
    $sql = "UPDATE usuarios SET password=\"" . $password . "\" WHERE nick=\"" . $nick . "\"";
    $exito = $conexion->ejecuta($sql);

    if($exito) {
        echo "<script>alert(\"Editado con éxito.\")</script>";
    }
    else{
        echo "<script>alert(\"Error al editar.\")</script>";
    }
}

function terminar($conexion, $conexion2, $codigo_recurso) {
    /**
     * Avanza a los usuarios de un recurso del profesional
     *
     * in:
     *    datos a modificar
     */
    $sql = "SELECT nick, estado, prioridad FROM colas WHERE codigo_recurso = \"" . $codigo_recurso . "\" AND estado != \"3\" ORDER BY estado DESC, prioridad DESC";
    $conexion->consulta($sql);
    $primero = true;
    $siguiente = false;
    $dt = new DateTime("now", new DateTimeZone('Europe/Madrid'));
    $dt = $dt->format('Y-m-d H:i:s');

    while($reg=$conexion->extraer_registro()) {
        if ($siguiente && $reg["estado"] == "1") {
            $sql = "UPDATE colas SET estado=\"2\" ,hora=\"" . $dt . "\" WHERE codigo_recurso=\"" . $codigo_recurso . "\" AND nick=\"" . $reg["nick"] . "\"";
            $conexion2->consulta($sql);
            $siguiente = false;
        }
        if ($reg["estado"] == "2") {
            $sql = "UPDATE colas SET estado=\"3\" WHERE codigo_recurso=\"" . $codigo_recurso . "\" AND nick=\"" . $reg["nick"] . "\"";
            $conexion2->consulta($sql);
            $siguiente = true;
            $primero = false;
        }

        if ($primero) {
            $sql = "UPDATE colas SET estado=\"2\" ,hora=\"" . $dt . "\"  WHERE codigo_recurso=\"" . $codigo_recurso . "\" AND nick=\"" . $reg["nick"] . "\"";
            $conexion2->consulta($sql);
            $primero = false;
        }
    }
    gestionar_recurso($conexion, $codigo_recurso);
}

function siguiente($conexion, $conexion2, $codigo_recurso) {
    /**
     * Avanza a los usuarios de un recurso del profesional
     *
     * in:
     *    datos a modificar
     */
    $sql = "SELECT nick, estado, prioridad FROM colas WHERE codigo_recurso = \"" . $codigo_recurso . "\" AND estado != \"3\" ORDER BY estado DESC, prioridad DESC";
    $conexion->consulta($sql);
    $cambiado = false;
    $dt = new DateTime("now", new DateTimeZone('Europe/Madrid'));
    $dt = $dt->format('Y-m-d H:i:s');

    if($conexion->numero_filas() != 0) {
        $reg = $conexion->extraer_registro();

        $sql = "SELECT prioridad FROM  colas WHERE codigo_recurso=\"" . $codigo_recurso . "\" AND nick=\"" . $reg["nick"] . "\"";
        $conexion2->consulta($sql);
        $reg2 = $conexion2->extraer_registro();
        $prioridad = $reg2["prioridad"] + 1;

        if ($reg["estado"] == "2") {
            $sql = "UPDATE colas SET estado=\"1\", prioridad=\"" . $prioridad . "\" WHERE codigo_recurso=\"" . $codigo_recurso . "\" AND nick=\"" . $reg["nick"] . "\"";
            $conexion2->consulta($sql);
            $cambiado = true;
        } else if ($reg["estado"] == "1") {
            $sql = "UPDATE colas SET estado=\"2\" ,hora=\"" . $dt . "\" WHERE codigo_recurso=\"" . $codigo_recurso . "\" AND nick=\"" . $reg["nick"] . "\"";
            $conexion2->consulta($sql);
        }

        if ($cambiado) {
            if($conexion->numero_filas() != 0) {
                $reg = $conexion->extraer_registro();
                if ($reg["estado"] == "1") {
                    $sql = "UPDATE colas SET estado=\"2\" ,hora=\"" . $dt . "\" WHERE codigo_recurso=\"" . $codigo_recurso . "\" AND nick=\"" . $reg["nick"] . "\"";
                    $conexion2->consulta($sql);
                }
            }
        }

        gestionar_recurso($conexion, $codigo_recurso);
    }
}

?>