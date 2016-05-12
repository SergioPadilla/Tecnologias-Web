function getDate() {
    var fecha = new Date();
    document.getElementById("fecha").innerHTML = fecha.getDate()+"/"+fecha.getMonth()+"/"+fecha.getFullYear();
}