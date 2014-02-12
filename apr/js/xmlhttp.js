// JavaScript Document

function getXMLHTTPRequest() {
try {
req = new XMLHttpRequest();
} catch(err1) {
  try {
  req = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (err2) {
    try {
    req = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (err3) {
      req = false;
    }
  }
}
return req;
}

var http = getXMLHTTPRequest();



function llamarAjax() {
var apellido = document.form1.minombre.value;
var miAleatorio=parseInt(Math.random()*99999999);
var url = "miscriptdeservidor.php?apellido=" + apellido;
miPeticion.open("GET", url+ "&rand=" + miAleatorio, true);
miPeticion.onreadystatechange = respuestaAjax;
miPeticion.send(null);
}

function respuestaAjax() {
    // sólo estamos interesados en un readyState de 4,
    // es decir "completado"
    if(miPeticion.readyState == 4) {
        // si la respuesta HTTP del servidor es "OK"
        if(miPeticion.status == 200) {
            // ... declaraciones a ejecutar por el programa ...
        } else {
            // crear un mensaje de error para cualquier
            // otra respuesta HTTP del servidor
            alert("Ha ocurrido un error: " + miPeticion.statusText);
        }
    }
}


// ejecutado automáticamente cuando un mensaje es recibido desde el servidor
function useHttpResponse() 
{
  // se ejecuta sólo si la transacción se ha completado
  if (http.readyState == 4) 
  {
    // estatus de 200 indica que la transacción se ha completado correctamente
    if (http.status == 200) 
    {
      // extraemos el XML recuperado del servidor
      xmlResponse = http.responseXML;
      // obtenemos el "document element" (el elemento raíz) de la estructura XML
      xmlDocumentElement = xmlResponse.documentElement;
      // obtenemso el mensaje de texto, que está en el primer hijo de
	  // el "document element"
      helloMessage = xmlDocumentElement.firstChild.data;
      // actualizamos la pantalla del usuario usando los datos recibidos del servidor
      document.getElementById("divMessage").innerHTML = 
                                            '<i>' + helloMessage + '</i>';
      // reiniciar secuencia
      setTimeout('proceso()', 1000);
    } 
    // un estatus HTTP distinto de 200 indica que ha habido un error
    else 
    {
      alert("Ha habido un problema al acceder al servidor: " + http.statusText);
    }
  }
}




