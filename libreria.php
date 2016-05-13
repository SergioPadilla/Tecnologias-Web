<?php

/********************************************************************************************/
/*			Funciones varias */
/********************************************************************************************/

function limpiarcadena($cadena){
    $cad="";
    for($i=0;$i<strlen($cadena);$i++){
        if(!(($cadena[$i]==" ") && ($cadena[$i+1]==" "))){
            $cad=$cad.$cadena[$i];
        }
    }
    $cad=ucwords(strtolower($cad));
    $cad=trim($cad);
    return ($cad);
}

function tratafecha($fecha){
    $fecha1=explode('-',$fecha);
    $result=$fecha1[2].'-'.$fecha1[1].'-'.$fecha1[0];
    return ($result);
}


function controlImagen($img){

    if(strlen($img)==0) $img="no.jpg";
    else if(!file_exists("imagenes/emo/".$img)) $img="no.jpg";

    return $img;
}

function tablaImagenes($bd){

    $cade = "</TABLE>";

    $c = "SELECT Nombre,Imagen FROM Emoticono";
    $bd->consulta($c);

    $cade .= "<TABLE BORDER>";
    $cade .= "<tr style=\"background-color:black;color:white\"><td> Nombre </td><td> Imagen </td></tr>";
    while($reg = $bd->extraerRegistro())
    {


        $cade .= "<tr>";
        $cade .= "<td>".$reg["Nombre"]."</td><td><img src=\"/imagenes/emo/".controlImagen($reg["Imagen"])."\" title=\"".$reg["Nombre"]."\" alt=\"".$reg["Nombre"]."\"></td>";
        $cade .= "</tr>";
    }
    $cade .= "</TABLE>";

    return $cade;
}


function creaTablasEmoticon(){
    $bd = new BD('mysql.hostinger.es','u881727594_u','contrasenia','u881727594_bd');

    $c = "CREATE TABLE IF NOT EXISTS Emoticono(	Id INT NOT NULL AUTO_INCREMENT,	Nombre VARCHAR(45) NOT NULL,	Imagen VARCHAR(150),	PRIMARY KEY(Id))";

    $bd->consulta($c);

    unset($bd);
}

/********************************************************************************************/
/*			Fin funciones varias */
/********************************************************************************************/
?>

<?php

/********************************************************************************************/
/*			Crear las tablas necesarias para la aplicaci髇 */
/********************************************************************************************/

function Crear_Tablas()
{
    $ser="mysql.hostinger.es";
    $usu="u147450505_test";
    $pass="javilon35";
    $base="u147450505_test";

    $conexion=new Servidor_Base_Datos($ser,$usu,$pass,$base);

    $consulta="CREATE TABLE IF NOT EXISTS Aeronaves ( ";
    $consulta .= "Id_Aeronave INT  AUTO_INCREMENT, ";
    $consulta .= "Matricula VARCHAR(50) , ";
    $consulta .= "Modelo VARCHAR(50) , ";
    $consulta .= "Fecha_Alta_Comp DATETIME , ";
    $consulta .= "Fecha_Ultima_Revision DATETIME , ";
    $consulta .= "Plazas INT , ";
    $consulta .= "Caracteristicas VARCHAR(200) , ";
    $consulta .= "Fotografia VARCHAR(150) , ";
    $consulta .= "PRIMARY KEY (Id_Aeronave))";

    $conexion->consulta($consulta);

    $consulta="CREATE TABLE IF NOT EXISTS Personal (";
    $consulta .= "Id_Personal INT NOT NULL AUTO_INCREMENT, ";
    $consulta .= "Nombre VARCHAR(25) , ";
    $consulta .= "Apellidos VARCHAR(50) ,";
    $consulta .= "Fecha_Incorporacion DATETIME , ";
    $consulta .= "Puesto VARCHAR(50) , ";
    $consulta .= "PRIMARY KEY (Id_Personal))";

    $conexion->consulta($consulta);

    $consulta="CREATE TABLE IF NOT EXISTS Lineas (";
    $consulta .= "Id_Linea INT NOT NULL AUTO_INCREMENT, ";
    $consulta .= "Origen VARCHAR(50) , ";
    $consulta .= "Destino VARCHAR(50) ,";
    $consulta .= "Fecha_Concesion DATETIME , ";
    $consulta .= "PRIMARY KEY (Id_Linea))";

    $conexion->consulta($consulta);

    $consulta="CREATE TABLE IF NOT EXISTS Vuelos (";
    $consulta .= "Id_Vuelo INT NOT NULL AUTO_INCREMENT, ";
    $consulta .= "Fecha DATETIME , ";
    $consulta .= "Id_Aeronave INT ,";
    $consulta .= "Id_Personal INT , ";
    $consulta .= "Id_Linea INT , ";
    $consulta .= "PRIMARY KEY (Id_Vuelo), ";
    $consulta .= "CONSTRAINT Aeronave FOREIGN KEY (Id_Aeronave) REFERENCES Aeronaves (Id_Aeronave),";
    $consulta .= "CONSTRAINT Comandante FOREIGN KEY (Id_Personal) REFERENCES Personal (Id_Personal), ";
    $consulta .= "CONSTRAINT Linea FOREIGN KEY (Id_Linea) REFERENCES Lineas (Id_Linea)) ";


    $conexion->consulta($consulta);


    unset($conexion);

}



/********************************************************************************************/
/*			Clase para controlar la base de datos SQL*/
/********************************************************************************************/

class Servidor_Base_Datos
{
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

    public function extraer_registro(){
        if($fila=mysqli_fetch_array($this->resultado,MYSQLI_ASSOC)){
            return $fila;
        }else{
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


/********************************************************************************************/
/*			Clase para la barra de desplazamiento de los formularios*/
/********************************************************************************************/

class Barra_Desplazamiento
{
    private $primero,$anterior,$siguiente,$ultimo,$guardar,$cancelar,$nuevo,$eliminar;
    private $disabled,$enabled;
    private $cad;

    function __construct($p,$a,$s,$u,$g,$c,$n,$e){
        $this->disabled='disabled="disabled" style="opacity:0.3"';
        $this->enabled='style="opacity:1"';

        $this->primero=($p=="d")?$this->disabled:$this->enabled;
        $this->anterior=($a=="d")?$this->disabled:$this->enabled;
        $this->siguiente=($s=="d")?$this->disabled:$this->enabled;
        $this->ultimo=($u=="d")?$this->disabled:$this->enabled;
        $this->guardar=($g=="d")?$this->disabled:$this->enabled;
        $this->cancelar=($c=="d")?$this->disabled:$this->enabled;
        $this->nuevo=($n=="d")?$this->disabled:$this->enabled;
        $this->eliminar=($e=="d")?$this->disabled:$this->enabled;
    }

    function escribe_barra(){
        $this->cad ='<input name="boton" id="botonprimero" class="boton_manteni"' . $this->primero . ' type="image" value="Primero">';
        $this->cad .='<input name="boton" id="botonant" class="boton_manteni"' . $this->anterior . ' type="image" value="Anterior">';
        $this->cad .='<input name="boton" id="botonsig" class="boton_manteni"' . $this->siguiente . ' type="image" value="Siguiente">';
        $this->cad .='<input name="boton" id="botonultimo" class="boton_manteni"' . $this->ultimo . ' type="image" value="Ultimo">';
        $this->cad .='<input name="boton" id="botonuevo" class="boton_manteni"' . $this->nuevo . ' type="image" value="Anadir">';
        $this->cad .='<input name="boton" id="botonelimina" class="boton_manteni"' . $this->eliminar . ' type="image" value="Eliminar" onClick="confirma()">';
        $this->cad .='<input name="boton" id="botonguarda" class="boton_manteni"' . $this->guardar . ' type="image" value="Guardar">';
        $this->cad .='<input name="boton" id="botoncancela" class="boton_manteni"' . $this->cancelar . ' type="image" value="Cancelar">';

        return $this->cad;
    }
}


?>