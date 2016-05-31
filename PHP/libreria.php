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
}

function mostrar_perfil($conexion) {
    /**
     * Muestra el perfil del usuario
     */
    $cadena = "<h2 class=\"sub-header\">Perfil</h2>";
    $cadena .= "<div class=\"table-responsive\">";
    $cadena .= "<table class=\"table table-striped\">";
    $cadena .= "<thead>";
    $cadena .= "<tr>";
    $cadena .= "<th>Nick</th>";
    $cadena .= "<th>Password</th>";
    $cadena .= "<th>Nombre</th>";
    $cadena .= "<th>Apellidos</th>";
    $cadena .= "<th>DNI</th>";
    $cadena .= "<th>Rol</th>";
    $cadena .= "<th>Opciones</th>";
    $cadena .= "</tr>";
    $cadena .= "</thead>";
    $cadena .= "<tbody>";

    $sql = 'SELECT * FROM usuarios WHERE nick="'. $_SESSION[USUARIO].'"';
    $conexion->consulta($sql);

    if($conexion->numero_filas() != 0){
        while($reg=$conexion->extraer_registro()) {
            $cadena .= "<tr>";
            $cadena .= "<td>".$reg["nick"]."</td><td>".$reg["password"]."</td><td>".$reg["nombre"]."</td><td>".$reg["apellidos"]."</td><td>".$reg["dni"]."</td><td>".$reg["rol"]."</td><td><span onclick='editar_perfil()' class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span></td>";
            $cadena .= "</tr>\n";
        }
    }
    $cadena .= "</tbody>";
    $cadena .= "</table>";
    $cadena .= "<p id=\"prueba\"></p>";
    $cadena .= "</div>";

    echo $cadena;
}


function mostrar_colas($conexion) {
    /**
     * Muestra colas del usuario
     */
    $sql = 'SELECT * FROM colas WHERE nick='.$_SESSION[USUARIO];
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

        while($reg=$conexion->extraer_registro()) {
            $cadena .= "<tr>";
            $cadena .= "<td>".$reg["nombre"]."</td><td>".$reg["descripcion"]."</td><td>".$reg["lugar"]."</td><td>".$reg["hora_comienzo"]."</td><td><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>    <span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></td>";
            $cadena .= "</tr>\n";
        }

        $cadena .= "</tbody>";
        $cadena .= "</table>";
        $cadena .= "<p id=\"prueba\"></p>";
        $cadena .= "</div>";

        echo $cadena;
    } else {
        echo "<h1>NO ESTAS APUNTADO A NINGÚN RECURSO</h1>";
    }

}


function mostrar_recursos($conexion) {
    /**
     * Muestra los recursos disponibles para el usuario
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
    $cadena = "<h2 class=\"sub-header\">Usuarios</h2>";
    $cadena .= "<div class=\"table-responsive\">";
    $cadena .= "<table class=\"table table-striped\">";
    $cadena .= "<thead>";
    $cadena .= "<tr>";
    $cadena .= "<th>Nick</th>";
    $cadena .= "<th>Password</th>";
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
            $cadena .= "<td>".$reg["nick"]."</td><td>".$reg["password"]."</td><td>".$reg["nombre"]."</td><td>".$reg["apellidos"]."</td><td>".$reg["dni"]."</td><td>".$reg["rol"]."</td><td><span  onclick=\"  editar_usuario('".$reg["nick"]."'); \" id=\"glyphicon\" class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>    <span onclick=\"  eliminar_usuario('".$reg["nick"]."'); \"  id=\"glyphicon\" class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></td>";
            $cadena .= "</tr>\n";
        }
    }
    $cadena .= "</tbody>";
    $cadena .= "</table>";
    $cadena .= "<p id=\"prueba\"></p>";
    $cadena .= "</div>";

    echo $cadena;
}

function editar_usuario($conexion, $usuario) {
    /**
     * Muestra un formulario para editar los datos de un usuario
     */
    $sql = "SELECT * FROM usuarios WHERE nick = \"" . $usuario . "\" ";
    $conexion->consulta($sql);
    if($conexion->numero_filas() != 0){
        $reg=$conexion->extraer_registro();
    }

    $cadena = "<form class=\"form-horizontal\" action=\"administracion.php\" method=\"post\">";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Nick</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_nick\" class=\"form-control\" placeholder=\"Nick\" value=\"". $reg['nick'] ."\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Password</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_password\" class=\"form-control\" placeholder=\"Password\" value=\"". $reg['password'] ."\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Nombre</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_nombre\" class=\"form-control\" placeholder=\"Nombre\" value=\"". $reg['nombre'] ."\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Apellidos</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_apellidos\" class=\"form-control\" placeholder=\"Apellidos\" value=\"". $reg['apellidos'] ."\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">DNI</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_dni\" class=\"form-control\" placeholder=\"DNI\" value=\"". $reg['dni'] ."\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Rol</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_rol\" class=\"form-control\" placeholder=\"Rol\" value=\"". $reg['rol'] ."\">";
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

function update_usuarios($conexion, $nick, $password, $nombre, $apellidos, $dni, $rol) {
    $passwordmd5 = md5($password);
    $sql = "UPDATE usuarios
            SET password=\"" . $passwordmd5 . "\", nombre=\"" . $nombre . "\", apellidos=\"" . $apellidos . "\", dni=\"" . $dni . "\", rol=\"" . $rol . "\"
            WHERE nick=\"" . $nick . "\"";
    $conexion->consulta($sql);
    echo "<script>alert(\"Editado con éxito.\")</script>";
}

function eliminar_usuario($conexion, $nick) {
    $sql = "DELETE FROM usuarios
            WHERE nick=\"" . $nick . "\"";
    $conexion->consulta($sql);
    echo "<script>alert(\"Eliminado con éxito.\")</script>";
}

/**
 * Gestión permisos
 */

function mostrar_permisos($conexion) {
    $cadena = "<h2 class=\"sub-header\">Permisos</h2>";
    $cadena .= "<div class=\"table-responsive\">";
    $cadena .= "<table class=\"table table-striped\">";
    $cadena .= "<thead>";
    $cadena .= "<tr>";
    $cadena .= "<th>Permiso</th>";
    $cadena .= "<th>Descripción</th>";
    $cadena .= "<th>Opciones</th>";
    $cadena .= "</tr>";
    $cadena .= "</thead>";
    $cadena .= "<tbody>";

    $sql = 'SELECT * FROM permisos';
    $conexion->consulta($sql);

    if($conexion->numero_filas() != 0){
        while($reg=$conexion->extraer_registro()) {

            $cadena .= "<tr>";
            $cadena .= "<td>".$reg["permiso"]."</td><td>".$reg["descripcion"]."</td><td><span  onclick=\"  editar_permiso('".$reg["permiso"]."'); \" id=\"glyphicon\" class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>    <span onclick=\"  eliminar_permiso('".$reg["permiso"]."'); \"  id=\"glyphicon\" class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></td>";
            $cadena .= "</tr>\n";
        }
    }
    $cadena .= "</tbody>";
    $cadena .= "</table>";
    $cadena .= "</div>";

    echo $cadena;
}


function editar_permiso($conexion, $permiso) {
    /**
     * Muestra un formulario para editar la descripción de un permiso
     */
    $sql = "SELECT * FROM permisos WHERE permiso = \"" . $permiso . "\" ";
    $conexion->consulta($sql);
    if($conexion->numero_filas() != 0){
        $reg=$conexion->extraer_registro();
    }

    $cadena = "<form class=\"form-horizontal\" action=\"administracion.php\" method=\"post\">";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Permiso</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_permiso\" class=\"form-control\" placeholder=\"Permiso\" value=\"". $reg['permiso'] ."\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Descripción</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_permiso_descripcion\" class=\"form-control\" placeholder=\"Descripción\" value=\"". $reg['descripcion'] ."\">";
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

function update_permisos($conexion, $permiso, $descripcion) {
    $sql = "UPDATE permisos
            SET descripcion=\"" . $descripcion . "\"
            WHERE permiso=\"" . $permiso . "\"";
    $conexion->consulta($sql);
    echo "<script>alert(\"Editado con éxito.\")</script>";
}

function eliminar_permiso($conexion, $permiso) {
    $sql = "DELETE FROM permisos
            WHERE permiso=\"" . $permiso . "\"";
    $conexion->consulta($sql);
    echo "<script>alert(\"Eliminado con éxito.\")</script>";
}


/**
 * Gestión roles
 */

function mostrar_roles($conexion) {
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
    $cadena .= "<input name=\"editar_rol\" class=\"form-control\" placeholder=\"Rol\" value=\"". $reg['rol'] ."\">";
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
    $sql = "UPDATE roles
            SET descripcion=\"" . $descripcion . "\"
            WHERE rol=\"" . $rol . "\"";
    $conexion->consulta($sql);
    echo "<script>alert(\"Editado con éxito.\")</script>";
}

function eliminar_rol($conexion, $rol) {
    $sql = "DELETE FROM roles
            WHERE rol=\"" . $rol . "\"";
    $conexion->consulta($sql);
    echo "<script>alert(\"Eliminado con éxito.\")</script>";
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

        $cadena = "<form name=\"form_editar_perfil\" class=\"form-horizontal\" action=\"cliente.php\" method=\"post\" onsubmit=\"return validarEditarPerfil()\">";
        $cadena .= "<div class=\"form-group\">";
        $cadena .= "<label class=\"col-sm-2 control-label\">Nick</label>";
        $cadena .= "<div class=\"col-sm-10\">";
        $cadena .= "<input name=\"editar_nick\" class=\"form-control\" placeholder=\"Nick\" value=\"" . $reg['nick'] . "\" maxlength=\"20\">";
        $cadena .= "</div>";
        $cadena .= "</div>";
        $cadena .= "<div class=\"form-group\">";
        $cadena .= "<label class=\"col-sm-2 control-label\">Contraseña</label>";
        $cadena .= "<div class=\"col-sm-10\">";
        $cadena .= "<input name=\"editar_password\" class=\"form-control\" placeholder=\"Contraseña\" value=\"" . $reg['password'] . "\" maxlength=\"20\">";
        $cadena .= "</div>";
        $cadena .= "</div>";
        $cadena .= "<div class=\"form-group\">";
        $cadena .= "<label class=\"col-sm-2 control-label\">Repite contraseña</label>";
        $cadena .= "<div class=\"col-sm-10\">";
        $cadena .= "<input name=\"editar_password_2\" class=\"form-control\" placeholder=\"Contraseña\" value=\"" . $reg['password'] . "\" maxlength=\"20\">";
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
        }
    }
    $sql = "INSERT INTO colas (codigo_recurso, nick, codigo_usuario) VALUES ('" . $codigo_recurso . "','" . $nick . "','" . $codigo . "')";
    $exito = $conexion->ejecuta($sql);

    if ($exito) {
        echo "<script>alert(\"Registro realizado con exito. Sú código es: ".$codigo." \")</script>";
    }
    else
        echo "<script>alert(\"No puedes apuntarte a este recurso.\")</script>";

}


/**
 * Gestión recursos
 */

function mostrar_recursos_admin($conexion) {
    /**
     * Muestra todos los recursos disponibles
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
    $cadena .= "<th>Opciones</th>";
    $cadena .= "</tr>";
    $cadena .= "</thead>";
    $cadena .= "<tbody>";

    $sql = 'SELECT * FROM recursos';
    $conexion->consulta($sql);
    if($conexion->numero_filas() != 0){
        while($reg=$conexion->extraer_registro()) {
            $cadena .= "<tr>";
            $cadena .= "<td>".$reg["nombre"]."</td><td>".$reg["descripcion"]."</td><td>".$reg["lugar"]."</td><td>".$reg["hora_comienzo"]."</td><td><span  onclick=\"  editar_recurso('" . $reg["codigo"] . "'); \" id=\"glyphicon\" class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>    <span onclick=\"  eliminar_recurso('".$reg["codigo"]."'); \"  id=\"glyphicon\" class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></td>";
            $cadena .= "</tr>\n";
        }
    }
    $cadena .= "</tbody>";
    $cadena .= "</table>";
    $cadena .= "</div>";

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
    $cadena .= "<label class=\"col-sm-2 control-label\">Código</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_recurso_codigo\" class=\"form-control\" placeholder=\"Código\" value=\"". $reg['codigo'] ."\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Nombre</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_recurso_nombre\" class=\"form-control\" placeholder=\"Nombre\" value=\"". $reg['nombre'] ."\">";
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
    $cadena .= "<label class=\"col-sm-2 control-label\">Hora de comienzo</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_recurso_hora\" class=\"form-control\" placeholder=\"Hora de comienzo\" value=\"". $reg['hora_comienzo'] ."\">";
    $cadena .= "</div>";
    $cadena .= "</div>";
    $cadena .= "<div class=\"form-group\">";
    $cadena .= "<label class=\"col-sm-2 control-label\">Nick</label>";
    $cadena .= "<div class=\"col-sm-10\">";
    $cadena .= "<input name=\"editar_recurso_nick\" class=\"form-control\" placeholder=\"Nick\" value=\"". $reg['nick'] ."\">";
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

function update_recursos($conexion, $codigo, $nombre, $descripcion, $lugar, $hora, $nick) {
    $sql = "UPDATE recursos
            SET nombre=\"" . $nombre . "\", descripcion=\"" . $descripcion . "\", lugar=\"" . $lugar . "\", hora_comienzo=\"" . $hora . "\", nick=\"" . $nick . "\"
            WHERE codigo=\"" . $codigo . "\"";
    $conexion->consulta($sql);
    echo "<script>alert(\"Editado con éxito.\")</script>";
}

function eliminar_recurso($conexion, $recurso) {
    $sql = "DELETE FROM recursos
            WHERE codigo=\"" . $recurso . "\"";
    $conexion->consulta($sql);
    echo "<script>alert(\"Eliminado con éxito.\")</script>";
}
?>