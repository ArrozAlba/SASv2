// JavaScript Document
var url    ='../php/sps_cat_articulos.php';
var params ='operacion';
var metodo = 'get';

function ue_inicializar()
{
	validarOpener();                    //valida que el catalogo sea llamado de una pagina
	params = "operacion=ue_inicializar";
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
	function onInicializar(respuesta)
	{   
		var respuestas = respuesta.responseText.split('&');
		chequearFilas(respuestas[0]);
		$('viewPort').innerHTML  = respuestas[1];
		crearGrid(respuestas[0],url);
	}
}

function ue_seleccionar(objeto)
{
	eval("opener.ue_cargar"+window.name+"(objeto);");
	close();
}