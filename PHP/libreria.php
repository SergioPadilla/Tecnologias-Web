<?php
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

function getNicks(){
    return "probando";
}

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
    if($conexion->numero_filas()!=0){
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
?>