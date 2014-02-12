// JavaScript Document

var url    = '../php/sps_pro_aprobacionanticipos.php';
var params = 'operacion=';
var metodo = 'get';

Event.observe(window,'load',ue_cancelar,false);

function ue_cancelar()
{
  document.form1.reset();
  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtfecantper,txtmondeulab,txtmonporant,txtmonant,cmbestant,txtobsant");
  scrollTo(0,0);
}

function ue_nuevo()
{	
	ue_cancelar();
}

function ue_guardar()
{
  	  lb_valido=true;
	  var la_objetos=new Array ("txtcodper","txtcodnom","txtfecantper","txtmonant","cmbestant");
	  var la_mensajes=new Array ("el Código Personal"," el Código Nómina "," la fecha", "el monto del anticipo"," el status del anticipo ");
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
		  var anticipo = 
		  {
					  "codper": $F('txtcodper'),
					  "codnom": $F('txtcodnom'),
				   "fecantper": $F('txtfecantper'),
				      "monant": $F('txtmonant'),
					  "estant": $F('cmbestant'),
					  "obsant": $F('txtobsant')
		  };  
		  var objeto = JSON.stringify(anticipo);
		  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
		  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onGuardar});
		} // end del else 
	 }; // end del  if(lb_valido)
 } //end de function ue_guardar()


function ue_buscar()
{
   pagina="sps_cat_personal_anticipo.html.php";
   catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=250,resizable=yes,location=no,top=0,left=0");
}

function ue_cargar_registro_anticipo(arr_datos)
{
  $(txtcodper).value = arr_datos[0];
  $(txtnomper).value = arr_datos[1];
  $(txtapeper).value = arr_datos[2];	
  $(txtfecantper).value = arr_datos[3];	
  $(txtcodnom).value = arr_datos[4];	
  $(txtdennom).value = arr_datos[5];
 
  $(txtmondeulab).value = arr_datos[9];
  $(txtmonporant).value = arr_datos[10];
  $(txtmonant).value = arr_datos[11];
  $(cmbestant).value = arr_datos[14];
  $(txtobsant).value = arr_datos[13];

  $('txtmondeulab').value = uf_convertir($('txtmondeulab').value,2);
  $('txtmonporant').value = uf_convertir($('txtmonporant').value,2);
  $('txtmonant').value = uf_convertir($('txtmonant').value,2);
  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtfecantper,txtmondeulab,txtmonporant");
  habilitar("txtmonant,cmbestant,txtobsant");
  $('hidguardar').value = "modificar";
  $('txtmonant').focus();
}
