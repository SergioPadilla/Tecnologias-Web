function getDate() {
    /**
     * Obtiene la fecha actual en el formato: dd/mm/aaaa
     * @type {Date}
     */
    var fecha = new Date();
    document.getElementById("fecha").innerHTML = fecha.getDate()+"/"+fecha.getMonth()+"/"+fecha.getFullYear();
}

function validarRegistro() {
    var pass = document.forms["form_registro"]["password"].value;
    var pass2 = document.forms["form_registro"]["password2"].value;
    var dni = document.forms["form_registro"]["dni"].value;
    
    if(pass != pass2){
        document.getElementById("mensaje_error").innerHTML = "Las contraseñas no coinciden";
        return false;
    } else {
        return validarDNI(dni);
    }
}

function validarDNI(dni) {
    var numero;
    var letr;
    var letra;
    var expresion_regular_dni = /^\d{8}[a-zA-Z]$/;

    if(expresion_regular_dni.test (dni) == true){
        numero = dni.substr(0,dni.length-1);
        letr = dni.substr(dni.length-1,1);
        numero = numero % 23;
        letra='TRWAGMYFPDXBNJZSQVHLCKET';
        letra=letra.substring(numero,numero+1);
        if (letra!=letr.toUpperCase()) {
            document.getElementById("mensaje_error").innerHTML = "Letra incorrecta";
            return false;
        }
    } else {
        document.getElementById("mensaje_error").innerHTML = "DNI no válido";
        return false;
    }
}

function mostrar_usuarios() {
    $.post( "administracion.php", { action: "mostrar_usuarios"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function editar_usuario(usuario) {
    var user = JSON.parse(usuario);
    $.post( "administracion.php", { action: "editar_usuario", usuario:user} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function editar_usuario2(json_array) {
    var e = "" + json_array;
    alert (e);

}

function mostrar_perfil() {
    $.post( "cliente.php", { action: "mostrar_perfil"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function mostrar_recursos() {
    $( "#tabla" ).clear();
    $.post( "cliente.php", { action: "mostrar_recursos"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function mostrar_colas() {
    $( "#tabla" ).clear();
    $.post( "cliente.php", { action: "mostrar_colas"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}