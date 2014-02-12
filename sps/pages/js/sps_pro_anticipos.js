// JavaScript Document

var url    = '../php/sps_pro_anticipos.php';
var params = 'operacion=';
var metodo = 'get';
var catalogo;

Event.observe(window,'load',ue_cancelar,false);

function ue_cancelar()
{
  document.form1.reset();
  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtfecantper,txtanoserper,txtmesserper,txtdiaserper,txtmotant,txtmondeulab,txtmonporant,txtmonant,txtestant,txtobsant");
  scrollTo(0,0);
}

function ue_nuevo()
{	
	ue_cancelar();
}

function ue_guardar()
{
  	  lb_valido=true;
  	  var la_objetos=new Array ("txtcodper","txtcodnom","txtfecantper","txtmonant","txtmotant");
	  var la_mensajes=new Array ("el Código Personal"," el Código Nómina "," la fecha", "el monto del anticipo"," el motivo ");
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
		  var $ls_estant = 0;
		  if ( $F('txtmonant')<=$F('txtmonporant'))
		  {
			  var anticipo = 
			  {
						  "codper": $F('txtcodper'),
						  "codnom": $F('txtcodnom'),
					   "fecantper": $F('txtfecantper'),
					   "anoserper": $F('txtanoserper'),
					   "messerper": $F('txtmesserper'),
					   "diaserper": $F('txtdiaserper'),
						  "motant": $F('txtmotant'),
					   "mondeulab": $F('txtmondeulab'),
					   "monporant": $F('txtmonporant'),
						  "monant": $F('txtmonant'),
						  "estant": $ls_estant,
						  "obsant": $F('txtobsant')
			  };
				  
			  var objeto = JSON.stringify(anticipo);
			  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
			  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onGuardar});
		  }
		  else { alert("El Monto del Anticipo no puede ser mayor al porcentaje permitido.");
		  		 $('txtmonant').focus(); 
		  }
		} // end del else 
	 }; // end del  if(lb_valido)
 } //end de function ue_guardar()

function ue_eliminar()
{
  lb_valido=true;
  var la_objetos =new Array ("txtcodper", "txtcodnom", "txtfecantper");
  var la_mensajes=new Array ("el Nº Personal", "el código nómina ", "fecha del anticipo");
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
	  params = "operacion=ue_eliminar&codper="+$F('txtcodper')+"&codnom="+$F('txtcodnom')+"&fecantper="+$F('txtfecantper');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	   alert("Eliminación Cancelada !!!");	  
	   ue_cancelar();
	}
  };
}

function ue_buscarpersonal()
{
  pagina="sps_cat_personal.html.php";
  catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}
          
function ue_cargar_registro(arr_datos)
{ 
  $('hidguardar').value = "insertar"; 	
  var cajas = new Array('txtcodper','txtnomper','txtapeper','txtcodnom','txtdennom');
  for (i=0; i<cajas.length; i++)
  {
	  $(cajas[i]).value = arr_datos[i];
  }
  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom");
  habilitar("txtfecantper,txtmonant,txtmotant");
  $('txtfecantper').value  = "";
  $('txtmondeulab').value = "";
  $('txtmonporant').value = "";
  $('txtmotant').value = "";
  $('txtobsant').value = "";
  $('txtestant').value = "";
  $('txtfecantper').focus();
  try
  { catalogo.close(); }
  catch(e)
  {}
  ue_antiguedad();
  if ((navigator.appName == "Netscape"))
  {	 
	  eval("$error_provocado;"); //Esta linea de abajo es un error provocado intencionalmente
  }
}

function ue_buscar()
{
   pagina="sps_cat_personal_anticipo.html.php";
   catalogo = popupWin(pagina,"registro","menubar=no,toolbar=no,scrollbars=yes,width=600,height=250,resizable=yes,location=no,top=0,left=0");
}

function ue_cargar_registro_anticipo(arr_datos)
{  
  $('hidguardar').value = "modificar";	
                                                            
  var cajas = new Array('txtcodper','txtnomper','txtapeper','txtfecantper','txtcodnom','txtdennom','txtanoserper','txtmesserper','txtdiaserper','txtmondeulab','txtmonporant','txtmonant','txtmotant','txtobsant','txtestant' );
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
  if (arr_datos[14] == 0)
  { $(cajas[14]).value = 'Solicitud'; }
  if (arr_datos[14] == 1)
  { $(cajas[14]).value = 'Aprobado'; }
  if (arr_datos[14] == 2)
  { $(cajas[14]).value = 'Rechazado'; }
  if (arr_datos[14] == 3)
  { $(cajas[14]).value = 'Pagado'; }
  
  $('txtmondeulab').value = uf_convertir($('txtmondeulab').value,2);
  $('txtmonporant').value = uf_convertir($('txtmonporant').value,2);
  $('txtmonant').value    = uf_convertir($('txtmonant').value,2);
  
  if ($('txtestant').value==='Solicitud')
  {
     deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtfecantper,txtanoserper,txtmesserper,txtdiaserper,txtmondeulab,txtmonporant");
     habilitar("txtmonant,txtmotant,txtobsant");
  }
  else {deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtfecantper,txtanoserper,txtmesserper,txtdiaserper,txtmondeulab,txtmonporant,txtmonant,txtmotant,txtobsant");}
  $('txtmotant').focus();
}

function ue_antiguedad()
{
  lb_valido=true;
  var la_objetos =new Array ("txtcodper", "txtcodnom");
  var la_mensajes=new Array ("el Nº Personal", "el código nómina ");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	 function onAntiguedad(respuesta)
	 {  
		if (trim(respuesta.responseText) != "")
	  	{ 
		   var det_antig = JSON.parse(respuesta.responseText);
		   $('txtanoserper').value=det_antig.ano[0];
		   $('txtmesserper').value=det_antig.mes[0];
		   $('txtdiaserper').value=det_antig.dia[0];
		   $('txtmondeulab').value=uf_convertir(det_antig.mondeulab[0],2);
		   $('txtmonporant').value=uf_convertir(det_antig.monporant[0],2);
		}
	    
     }; //end onAntiguedad
	 params = "operacion=ue_antiguedad&codper="+$F('txtcodper')+"&codnom="+$F('txtcodnom');
	 new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onAntiguedad});
  } //end if (lb_valido)
} //end ue_antiguedad