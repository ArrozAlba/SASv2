// JavaScript Document

var url    = '../php/sps_def_cartaanticipo.php';
var params = 'operacion=';
var metodo = 'get';

Event.observe(window,'load',ue_cancelar,false);

function ue_cancelar()
{
  document.form1.reset();
  deshabilitar("txtcodcarant,txtdescarant,txttamletcarant,txttamletpiepag,cmbintlincarant,txtmarsupcarant,txtmarinfcarant,txttitcarant,txtnomrtf,txtarcrtfcarant,cmbcampos,txtconcarant,txtpiepagcarant");
  scrollTo(0,0);
}

function ue_nuevo()
{	
  function onNuevo(respuesta)
  {
	ue_cancelar();
	$('hidguardar').value  = "insertar";
	$('txtcodcarant').value= trim(respuesta.responseText);
	habilitar("txtdescarant,txttamletcarant,txttamletpiepag,cmbintlincarant,txtmarsupcarant,txtmarinfcarant,txttitcarant,txtarcrtfcarant,cmbcampos,txtconcarant,txtpiepagcarant");
	$('txtdencauret').focus();
  }	
  params = "operacion=ue_nuevo";
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onNuevo});
}

function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodcarant","txtdescarant","txttitcarant","txtconcarant");
  var la_mensajes=new Array ("el Código","la Descripción","el Título", "el Concepto");
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
	  archivo=$('txtarcrtfcarant').value;
	  var cartaanticipo = 
	  {
	  	  "codcarant" : $F('txtcodcarant'),
		  "descarant" : $F('txtdescarant'),
		  "concarant": $F('txtconcarant'),
		  "tamletcarant": $F('txttamletcarant'),
		  "intlincarant": $F('cmbintlincarant'),
		  "marsupcarant": $F('txtmarsupcarant'),
		  "marinfcarant": $F('txtmarinfcarant'),
		  "titcarant": $F('txttitcarant'),
		  "piepagcarant": $F('txtpiepagcarant'),
		  "tamletpiepag": $F('txttamletpiepag'),
		  "arcrtfcarant": archivo
	  };
	
	  var objeto = JSON.stringify(cartaanticipo);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onGuardar});
	}
  };
}

function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodcarant");
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
	  
	  params = "operacion=ue_eliminar&codigo="+$F('txtcodcarant');
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
   pagina="sps_cat_cartaanticipo.html.php";
   catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}

function ue_cargar_registro(arr_datos)
{ 
  var cajas = new Array('txtcodcarant','txtdescarant','txtconcarant','txttamletcarant','cmbintlincarant','txtmarsupcarant','txtmarinfcarant','txttitcarant','txtpiepagcarant','txttamletpiepag','txtnomrtf');
  for (i=0; i<cajas.length; i++)
  {$(cajas[i]).value = arr_datos[i];}
  $('hidguardar').value = "modificar";
  habilitar("txtdescarant,txttamletcarant,txttamletpiepag,cmbintlincarant,txtmarsupcarant,txtmarinfcarant,txttitcarant,txtarcrtfcarant,cmbcampos,txtconcarant,txtpiepagcarant");
  $('txtdescarant').focus();
}
function ue_ingresarcampo()
{
	ls_campo=$F('cmbcampos');
	ls_contenido=$F('txtconcarant');
	ls_contenido=ls_contenido+ls_campo; 
	$('txtconcarant').value=ls_contenido;
}
