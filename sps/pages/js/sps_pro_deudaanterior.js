// JavaScript Document

var url    = '../php/sps_pro_deudaanterior.php';
var params = 'operacion=';
var metodo = 'get';

Event.observe(window,'load',ue_cancelar,false);

function ue_cancelar()
{
  document.form1.reset();
  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtfeccordeuant,txtdeuantant,txtdeuantint,txtantpag");
  scrollTo(0,0);
}

function ue_nuevo()
{	
	ue_cancelar();
}

function ue_guardar()
{
  	  lb_valido=true;
  	  var la_objetos=new Array ("txtcodper","txtcodnom","txtfeccordeuant");
	  var la_mensajes=new Array ("el Código Personal"," el Código Nómina ", "la fecha ");
	  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
	  if(lb_valido)
	  {
		if (($F('hidguardar') == "modificar") && ($F('hidpermisos').indexOf('m', 0) < 0))
		{
		  alert("NO TIENE PERMISO PARA MODIFICAR");
		}
		else
		{
		  function onGuardar(respuesta)
		  {
			if (trim(respuesta.responseText) != "")
			{alert(respuesta.responseText);}
			ue_cancelar();
		  }
		  var deudaanterior = 
		  {
					  "codper": $F('txtcodper'),
					  "codnom": $F('txtcodnom'),
				"feccordeuant": $F('txtfeccordeuant'),
				   "deuantant": $F('txtdeuantant'),
				   "deuantint": $F('txtdeuantint'),
					  "antpag": $F('txtantpag')

		  };
			  
		  var objeto = JSON.stringify(deudaanterior);
		  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
		  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onGuardar});
		} // end del else 
	 }; // end del  if(lb_valido)
 } //end de function ue_guardar()

function ue_eliminar()
{
  lb_valido=true;
  var la_objetos =new Array ("txtcodper", "txtcodnom", "txtfeccordeuant");
  var la_mensajes=new Array ("el Nº Personal", "el código nómina ", "fecha de corte deuda anterior");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{
	  function onEliminar(respuesta)
	  {
		alert(respuesta.responseText);
		ue_cancelar();
	  }
	  
	  params = "operacion=ue_eliminar&codper="+$F('txtcodper')+"&codnom="+$F('txtcodnom')+"&feccordeuant="+$F('txtfeccordeuant');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	   alert("Eliminación Cancelada !!!");	  
	   ue_cancelar();
	}
  };
}

function ue_buscar()
{
   pagina="sps_cat_deudaanterior.html.php";
   catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}

function ue_buscarpersonal()
{
  pagina="sps_cat_personal.html.php";
  catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}

function ue_cargar_registro(arr_datos)
{
  var cajas = new Array('txtcodper','txtnomper','txtapeper','txtcodnom','txtdennom');
  for (i=0; i<cajas.length; i++)
  {
	  $(cajas[i]).value = arr_datos[i];
  }
  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom");
  habilitar("txtfeccordeuant,txtdeuantant,txtdeuantint,txtantpag");
  $('txtfeccordeuant').value  = "";
  $('txtdeuantant').value = "";
  $('txtdeuantint').value = "";
  $('txtantpag').value = "";
  $('txtfeccordeuant').focus();
}
function ue_cargar_registro_catalogo(arr_datos)
{
  var cajas = new Array('txtcodper','txtnomper','txtapeper','txtcodnom','txtdennom','txtfeccordeuant', 'txtdeuantant', 'txtdeuantint', 'txtantpag');
  for (i=0; i<cajas.length; i++)
  {
	  $(cajas[i]).value = arr_datos[i];
  }
  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtfeccordeuant");
  habilitar("txtdeuantant,txtdeuantint,txtantpag");
  $('txtdeuantant').value = uf_convertir($('txtdeuantant').value, 2);
  $('txtdeuantint').value = uf_convertir($('txtdeuantint').value, 2);
  $('txtantpag').value    = uf_convertir($('txtantpag').value, 2);
  $('hidguardar').value   = "modificar";
  $('txtdeuantant').focus();
}





