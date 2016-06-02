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

function validarCrearUsuario() {
    var pass = document.forms["form_crear_usuario"]["crear_password"].value;
    var pass2 = document.forms["form_crear_usuario"]["crear_password_2"].value;
    var dni = document.forms["form_crear_usuario"]["crear_dni"].value;

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

// Gestión usuarios

function mostrar_usuarios() {
    $.post( "administracion.php", { action: "mostrar_usuarios"} ,function( data ) {
        $("#tabla").html("");
        $( "#tabla" ).html(data);
    });
}

function editar_usuario(usuario) {
    $.post( "administracion.php", { action: "editar_usuario", usuario:usuario} ,function( data ) {
        $("#tabla").html("");
        $("#tabla").html(data);
    });
}

function eliminar_usuario(usuario) {
    var borrar = confirm("¿Desea eliminar el usuario: "+ usuario + "?");
    if (borrar) {
        $.post( "administracion.php", { action: "eliminar_usuario", usuario:usuario} ,function( data ) {
            $("#tabla").html("");
            $( "#tabla" ).html(data);
        });
    }
}

// Gestión permisos

function mostrar_permisos() {
    $.post( "administracion.php", { action: "mostrar_permisos"} ,function( data ) {
        $("#tabla").html("");
        $( "#tabla" ).html(data);
    });
}

function editar_permiso(permiso) {
    $.post( "administracion.php", { action: "editar_permiso", permiso:permiso} ,function( data ) {
        $("#tabla").html("");
        $("#tabla").html(data);
    });
}

function eliminar_permiso(permiso) {
    var borrar = confirm("¿Desea eliminar el permiso: "+ permiso + "?");
    if (borrar) {
        $.post( "administracion.php", { action: "eliminar_permiso", permiso:permiso} ,function( data ) {
            $("#tabla").html("");
            $( "#tabla" ).html(data);
        });
    }
}

//

// Gestión roles

function mostrar_roles() {
    $.post( "administracion.php", { action: "mostrar_roles"} ,function( data ) {
        $("#tabla").html("");
        $( "#tabla" ).html(data);
    });
}

function editar_rol(rol) {
    $.post( "administracion.php", { action: "editar_rol", rol:rol} ,function( data ) {
        $("#tabla").html("");
        $("#tabla").html(data);
    });
}

function eliminar_rol(rol) {
    var borrar = confirm("¿Desea eliminar el rol: "+ rol + "?");
    if (borrar) {
        $.post( "administracion.php", { action: "eliminar_rol", rol:rol} ,function( data ) {
            $("#tabla").html("");
            $( "#tabla" ).html(data);
        });
    }
}

//

// Gestión recursos

function mostrar_recursos_admin() {
    $.post( "administracion.php", { action: "mostrar_recursos_admin"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function mostrar_recursos_profesional() {
    $.post( "administracion.php", { action: "mostrar_recursos_profesional"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function editar_recurso(recurso) {
    $.post( "administracion.php", { action: "editar_recurso", recurso:recurso} ,function( data ) {
        $("#tabla").html("");
        $("#tabla").html(data);
    });
}

function eliminar_recurso(recurso) {
    var borrar = confirm("¿Desea eliminar el recurso: "+ recurso + "?");
    if (borrar) {
        $.post( "administracion.php", { action: "eliminar_recurso", recurso:recurso} ,function( data ) {
            $("#tabla").html("");
            $( "#tabla" ).html(data);
        });
    }
}

//

function mostrar_recursos() {
    $.post( "cliente.php", { action: "mostrar_recursos"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

/**
 * Perfiles
 */
function mostrar_perfil() {
    $.post( "cliente.php", { action: "mostrar_perfil"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function mostrar_perfil_administracion() {
    $.post( "administracion.php", { action: "mostrar_perfil"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function editar_perfil() {
    $.post( "cliente.php", { action: "editar_perfil"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function editar_perfil_administracion() {
    $.post( "administracion.php", { action: "editar_perfil"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function mostrar_colas() {
    $('.nav li').removeClass("active");
    $.post( "cliente.php", { action: "mostrar_colas"} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function solicitar_turno(codigo_recurso) {
    $.post( "cliente.php", { action: "solicitar_turno", codigo_recurso: codigo_recurso} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function crear_recurso() {
    $.post( "administracion.php", { action: "crear_recurso"}, function( data ) {
        $( "#tabla" ).html(data);
    });
}

function crear_usuario() {
    $.post( "administracion.php", { action: "crear_usuario"}, function( data ) {
        $( "#tabla" ).html(data);
    });
}

function pantalla_turnos() {
    $.post( "administracion.php", { action: "pantalla_turnos"}, function( data ) {
        $( "#tabla" ).html(data);
    });
}

function cargar_mensaje_pantalla_turnos() {
    var mensaje = document.forms["form_pantalla_turnos"]["mensaje_pantalla_turnos"].value;
    $.post( "index.php", { actualizar_mensaje: "PRUEBA"},function() {
            alert( "success" );
        })
        .done(function() {
            alert( "second success" );
        })
        .fail(function() {
            alert( "error" );
        })
}

function gestionar_recurso(codigo_recurso) {
    $.post( "administracion.php", { action: "gestionar_recurso", codigo_recurso: codigo_recurso} ,function( data ) {
        $( "#tabla" ).html(data);
    });
}

function cargarLogin() {
    location.href='login.php'
}