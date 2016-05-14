function getDate() {
    /**
     * Obtiene la fecha actual en el formato: dd/mm/aaaa
     * @type {Date}
     */
    var fecha = new Date();
    document.getElementById("fecha").innerHTML = fecha.getDate()+"/"+fecha.getMonth()+"/"+fecha.getFullYear();
}

function mostrar_usuarios() {
    $.post( "administracion.php", { action: "mostrar_usuarios"} ,function( data ) {
        $( "#tabla" ).html(data);
    });

}

