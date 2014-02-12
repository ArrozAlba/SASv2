// JavaScript Document

var url    = '../php/sps_conv_anticipos.php';
var params = 'operacion=';
var metodo = 'get';

Event.observe(window,'load',ue_cancelar,false);

function ue_cancelar()
{
  document.form1.reset();
  habilitar("txtarchivo,btnejecutar");
  scrollTo(0,0);
}


function ue_leer_archivo()
{
  function onLeerArchivo(respuesta)
  {
	if (trim(respuesta.responseText) != "")
		{
			ocultar_mensaje();
			alert(respuesta.responseText);
		}
  }	
  mostrar_mensaje();
  params = "operacion=ue_leer_archivo&archivo="+$F('txtarchivo');
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onLeerArchivo});
}