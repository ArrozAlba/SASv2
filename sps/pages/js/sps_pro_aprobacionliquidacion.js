// JavaScript Document

var url    = '../php/sps_pro_aprobacionliquidacion.php';
var params = 'operacion=';
var metodo = 'get';

Event.observe(window,'load',ue_cancelar,false);

function ue_cancelar()
{
  document.form1.reset();
  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtfecliq,txtfecing,txtfecegr,txtmonliq");
  habilitar("cmbestliq,txtobsliq");
  scrollTo(0,0);
}
function ue_guardar()
{
  	  lb_valido=true;
	  var la_objetos=new Array ("txtcodper","txtcodnom","txtnumliq","txtfecliq","cmbestliq");
	  var la_mensajes=new Array ("el Código Personal"," el Código Nómina "," el Nº Liquidación", "la fecha de liquidación"," el status de la liquidación ");
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
			ue_cancelar();
			if (trim(respuesta.responseText) != "")
			{alert(respuesta.responseText);}
		  }
		  var liquidacion = 
		  {
					  "codper": $F('txtcodper'),
					  "codnom": $F('txtcodnom'),
					  "numliq": $F('txtnumliq'),
				      "fecliq": $F('txtfecliq'),
				      "estliq": $F('cmbestliq'),
					  "obsliq": $F('txtobsliq')
		  };  
		  var objeto = JSON.stringify(liquidacion);
		  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
		  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onGuardar});
		} // end del else 
	 }; // end del  if(lb_valido)
 } //end de function ue_guardar()

function ue_buscar()
{
   pagina="sps_cat_aprob_liquidacion.html.php";
   catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}

function ue_cargar_registro_aprob_liq(arr_datos)
{                       
  var cajas = new Array('txtnumliq','txtnomper','txtapeper','txtcodper','txtcodnom','txtdennom','txtfecliq','txtfecing','txtfecegr','txtmonliq','cmbestliq','txtobsliq' );
  for (i=0; i<cajas.length; i++)
  {
	  if (cajas[i].substring(0,6)==="txtfec" )
	  {   
	  	  li_ano = arr_datos[i].substr(0,4);
		  li_mes = arr_datos[i].substr(5,2);	
		  li_dia = arr_datos[i].substr(8,2);
		  $(cajas[i]).value = (li_dia+"/"+li_mes+"/"+li_ano);  
	  }
	  else
	  {
	  	$(cajas[i]).value = arr_datos[i];
	  }
  }
  $('txtmonliq').value = uf_convertir($('txtmonliq').value, 2);
  deshabilitar("txtnumliq,txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtfecliq,txtfecing,txtfecegr,txtmonliq");
  habilitar("cmbestliq,txtobsliq");
  if ($('cmbestliq').value == 'P'){ deshabilitar("cmbestliq"); }
  $('hidguardar').value = "modificar";
  $('cmbestliq').focus();
}