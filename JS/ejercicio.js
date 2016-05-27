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

function validarEditarPerfil() {
    var pass = document.forms["form_editar_perfil"]["editar_password"].value;
    var pass2 = document.forms["form_editar_perfil"]["editar_password_2"].value;
    var dni = document.forms["form_editar_perfil"]["editar_dni"].value;

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

function mostrar_perfil() {
    $.post( "cliente.php", { action: "mostrar_perfil"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function mostrar_recursos() {
    $.post( "cliente.php", { action: "mostrar_recursos"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function mostrar_colas() {
    $('.nav li').removeClass("active");
    $.post( "cliente.php", { action: "mostrar_colas"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function editar_perfil() {
    $.post( "cliente.php", { action: "editar_perfil"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function solicitar_turno(codigo_recurso) {
    $.post( "cliente.php", { action: "solicitar_turno", codigo_recurso: codigo_recurso} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}