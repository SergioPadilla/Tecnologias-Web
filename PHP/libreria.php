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
            $cadena .= "<td>".$reg["nick"]."</td><td>".$reg["password"]."</td><td>".$reg["nombre"]."</td><td>".$reg["apellidos"]."</td><td>".$reg["dni"]."</td><td>".$reg["rol"]."</td><td><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>    <span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></td>";
            $cadena .= "</tr>\n";
        }
    }
    $cadena .= "</tbody>";
    $cadena .= "</table>";
    $cadena .= "<p id=\"prueba\"></p>";
    $cadena .= "</div>";

    echo $cadena;
}

function mostrarColas(){
    $ser=NOMBRE_SERVIDOR;
    $usu=USUARIO_BD;
    $pass=PASS_BD;
    $base=NOMBRE_BD;

    $conexion=new Servidor_Base_Datos($ser,$usu,$pass,$base);
    $sql='SELECT * FROM colas WHERE estado=2 ORDER BY prioridad,id';
    $conexion->consulta($sql);

    if($conexion->numero_filas() != 0){
        $cadena = "<div class=\"table-responsive\">";
        $cadena .= "<table id=\"columns_index\" class=\"table table-striped\">";
        $cadena .= "<colgroup>";
        $cadena .= "<col width='50%'>";
        $cadena .= "<col width='50%'>";
        $cadena .= "</colgroup>";
        $cadena .= "<thead>";
        $cadena .= "<tr>";
        $cadena .= "<th>Código Usuario</th>";
        $cadena .= "<th>Recurso</th>";
        $cadena .= "</tr>";
        $cadena .= "</thead>";
        $cadena .= "<tbody>";

        while($reg=$conexion->extraer_registro()) {
            $cadena .= "<tr>";
            $cadena .= "<td>".$reg["id"]."</td><td>".$reg["codigo"]."</td>";
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
            $cadena .= "<td>".$reg["nick"]."</td><td>".$reg["password"]."</td><td>".$reg["nombre"]."</td><td>".$reg["apellidos"]."</td><td>".$reg["dni"]."</td><td>".$reg["rol"]."</td><td><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>    <span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></td>";
            $cadena .= "</tr>\n";
        }
    }
    $cadena .= "</tbody>";
    $cadena .= "</table>";
    $cadena .= "<p id=\"prueba\"></p>";
    $cadena .= "</div>";

    echo $cadena;
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
            $cadena .= "<tr>";
            $cadena .= "<td>".$reg["nombre"]."</td><td>".$reg["descripcion"]."</td><td>".$reg["lugar"]."</td><td>".$reg["hora_comienzo"]."</td><td><button class='button'>Solicitar turno</button></td>";
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
?>