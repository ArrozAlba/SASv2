// JavaScript Document

var url    = '../php/sps_def_causaretiro.php';
var params = 'operacion=';
var metodo = 'get';

Event.observe(window,'load',ue_cancelar,false);

function ue_cancelar()
{
  document.form1.reset();
  deshabilitar("txtcodcauret,txtdencauret");
  scrollTo(0,0);
}

function ue_nuevo()
{	
  function onNuevo(respuesta)
  {
	ue_cancelar();
	$('hidguardar').value  = "insertar";
	$('txtcodcauret').value= trim(respuesta.responseText);
	habilitar("txtdencauret");
	$('txtdencauret').focus();
  }	

  params = "operacion=ue_nuevo";
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onNuevo});
}

function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodcauret","txtdencauret");
  var la_mensajes=new Array ("el Código","la Denominación");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	if (($F('hidguardar') == "modificar") &&
		($F('hidpermisos').indexOf('m', 0) < 0))
	{
	  alert("NO TIENE PERMISO PARA MODIFICAR");
	}
	else
	{
	  function onGuardar(respuesta)
	  {
		ue_cancelar();
		if (trim(respuesta.responseText) != "")
		{alert(respuesta.responseText);}
	  }
	
	  var causaretiro = 
	  {
	    "codcauret" : $F('txtcodcauret'),
	    "dencauret" : $F('txtdencauret')
	  };
	
	  var objeto = JSON.stringify(causaretiro);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onGuardar});
	}
  };
}

function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodcauret");
  var la_mensajes=new Array ("el Código");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{
	  function onEliminar(respuesta)
	  {
		ue_cancelar();
		alert(respuesta.responseText);
	  }
	  
	  params = "operacion=ue_eliminar&codigo="+$F('txtcodcauret');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	  ue_cancelar();
	  alert("Eliminación Cancelada !!!");	  
	}
  };
}

function ue_buscar()
{
  pagina="sps_cat_causaretiro.html.php";
   catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}

function ue_cargar_registro(arr_datos)
{
  var cajas = new Array('txtcodcauret','txtdencauret');
  for (i=0; i<cajas.length; i++)
  {$(cajas[i]).value = arr_datos[i];}
  $('hidguardar').value = "modificar";
  habilitar("txtdencauret");
  $('txtdencauret').focus();
}

