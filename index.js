/**
 * En este fichero se crean las funciones Javascript.
 */


function validarTelefono() {
    /**
     * En este método comprobamos que el teléfono tenga el formato requerido.
     */
    var telefono = document.getElementById("telefono").value;
    var patronTelefono = /^\d{9}$/;

    if (!(patronTelefono.test(telefono))) {
        document.getElementById("errores").innerHTML = "Por favor, introduce telefono correctamente.";
        document.getElementById("telefono").blur();
        return false;
    } else {
        document.getElementById("errores").innerHTML = "";

    }
}

function validarEmail() {
    /**
     * En este método comprobamos que el email tenga el formato requerido.
     */
    var email = document.getElementById("email").value;
    var patronEmail = /^([\dA-Z_\.-]+)@([\dA-Z\.-]+)\.([A-Z\.]{2,6})$/gmi;

    if (!(patronEmail.test(email))) {
        document.getElementById("errores").innerHTML = "Por favor, introduce email correctamente.";
        document.getElementById("email").blur();
        return false;
    } else {
        document.getElementById("errores").innerHTML = "";

    }
}


var idCdr = '';
var referer = '';
var entidad = '';
var idioma = '';
var tipo_busqueda = '';
var fecha_entrada_dispo = '';
var fecha_salida_dispo = '';
var fecha_entrada = '';
var fecha_salida = '';
var mayorista_key = '';
var errorges_envio_id = '';

function radioResultado(value, value2, value3, value4, value5, value6, value7, value8, value9, value10) {
    /**
     * En este método asignamos los values del radiobutton de los resultados de Resultado.php a variables para poder trabajar con ellas posteriormente.
     */
    idCdr = value;
    referer = value2;
    entidad = value3;
    idioma = value4;
    tipo_busqueda = value5;
    fecha_entrada_dispo = value6.replaceAll('-', '%2F');
    fecha_salida_dispo = value7.replaceAll('-', '%2F');
    fecha_entrada = value8;
    fecha_salida = value9;
    mayorista_key = value10;
}

function radio(value) {
    /**
     * En este método asignamos los values del radiobutton de los resultados de Envios.php a variables para poder trabajar con ella posteriormente.
     */
    errorges_envio_id = value;
}

function congelar() {
    /**
     * En este método congelamos (desactivamos) el hotel del radiobutton
     */
    let usu = document.getElementById('USU').textContent;
    if (fecha_entrada.length != 0 && fecha_salida.length != 0 && mayorista_key.length != 0 && usu.length != 0) {
        $.get("congelar.php?fecha_entrada=" + fecha_entrada + "&fecha_salida=" + fecha_salida + "&mayorista_key=" + mayorista_key + "&USU=" + document.getElementById('USU').textContent + "&comentario=Congelado desde ErrorGES", function (data, status) {
            alert("Ok, hotel congelado durante 48h");
        })
    }
}

function mostrarDetalles() {
    /**
     * En este método detallamos cada envío del cron mostrando los hoteles que han dado error.
     */
    $.get("envio_cron.php?errorges_envio_id=" + errorges_envio_id, function (data, status) {

        let html = "<table><tr><th>ID ENVIO</th><th>HOTEL</th><th>SF_ID</th><th>ERRORES</th><th>CÓDIGO</th></tr><tbody>";
        JSON.parse(data).forEach(d => html += "<tr><td>" + d.errorges_envio_id + "</td><td>" + d.hotel_nombre + "</td><td>" + d.sf_id + "</td><td>"+ d.errores + "</td><td>" + d.hotel_codigo + "</td></tr>");
        html += "</tbody></table>";
        document.getElementById("resultados").innerHTML = html;
    })

}


function obtenerHoteles() {
    /**
     * En este método mostramos un "suggest" de hotel y localidad para que el usuario elija. Siempre tienen que estar ambos rellenos.
     */
    let hotel = document.getElementById("nombre_hotel").value;
    let localidad = document.getElementById("localidad").value;

    if (hotel.length == 0) {
        alert('¡Has de rellenar el nombre del hotel!')
    } else if (localidad.length == 0) {
        alert('¡Has de rellenar la localidad!')
    } else {
        $.get("hoteles.php?hotel=" + hotel + "&localidad=" + localidad, function (data, status) {
            let html = "<table><tr><th>SF_ID</th><th>NOMBRE</th><th>LOCALIDAD</th></tr><tbody>";
            JSON.parse(data).forEach(d => html += "<tr><td><button id='boton' onclick=\"selectHotel(" + d.sf_id + ",'" + escape(d.nombre_hotel) + "','" + escape(d.nombre) + "'); return false;\">Select</button>" + d.sf_id + "</td><td>" + d.nombre_hotel + "</td><td>" + d.nombre + "</td></tr>");
            html += "</tbody></table>";

            document.getElementById("resultados").innerHTML = html;
        })
    }
}

function selectHotel(sf_id, nombre, localidad) {
    /**
     * En este método seleccionamos y volcamos el hotel, sf_id y localidad.
     */
    document.getElementById("sf").value = sf_id;
    document.getElementById("nombre_hotel").value = nombre;
    document.getElementById("localidad").value = localidad;
}

function borrarHoteles() {
    /**
     * En este método borramos el hotel seleccionado al hacer reset.
     */
    document.getElementById("resultados").innerHTML = '';
}

