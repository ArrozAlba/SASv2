// JavaScript Document
var url    = '../php/sps_pro_sueldos.php';
var params = 'operacion=';
var metodo = 'get';
var catalogo;

Event.observe(window, 'load', ue_cancelar , false);

function ue_cancelar()
{
   document.form1.reset();
   limpiar_datos_detalle();
   limpiar_tabla_detalle();
   habilitar("txtfecincsue,txtmonsuebas,txtmonsueint,txtmonsuenordia");   
   deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,btnincdet,btnsuenom");
   scrollTo(0,0);
}
function ue_nuevo()
{
   ue_cancelar();
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function limpiar_datos_detalle()
{
  $('txtfecincsue').value = "";
  $('txtmonsuebas').value = "";
  $('txtmonsueint').value = "";
  $('txtmonsuenordia').value = "";
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function limpiar_tabla_detalle()
{  
  var FILAS = $("dt_sueldo").rows;
  if(FILAS.length > 1)
  {
	for (f=(FILAS.length-1); f>0; f--)
	{eliminarFila("dt_sueldo",FILAS[f].id)}
  }
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function ue_buscarpersonal()
{
  pagina="sps_cat_personal.html.php";
  catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=540,height=250,resizable=yes,location=no,top=0,left=0");
}
function ue_cargar_registro(arr_datos)
{
  var cajas = new Array('txtcodper','txtnomper','txtapeper','txtcodnom','txtdennom');
  for (i=0; i<cajas.length; i++)
  {
	  $(cajas[i]).value = arr_datos[i];
  }
  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom");
  habilitar("txtfecincsue,txtmonsuebas,txtmonsueint,txtmonsuenordia,btnincdet,btnsuenom");
  $('txtfecincsue').value = "";
  $('txtmonsuebas').value = "";
  $('txtmonsueint').value = "";
  $('txtmonsuenordia').value = "";
  $('txtfecincsue').focus();
  try
  {catalogo.close();}
  catch(e)
  {}
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function ue_agregar_detalle()  
{
  lb_valido=true;
  var la_objetos =new Array ("txtfecincsue","txtmonsuebas","txtmonsueint","txtmonsuenordia");  
  var la_mensajes=new Array ("la fecha ","el sueldo base","el sueldo integral", "el sueldo diario");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes );
  if(lb_valido)
  {
      var nuevaFila = clonarFila("dt_sueldo","fila0");
      copiarDatosDetalle(nuevaFila);
      limpiar_datos_detalle();
      try{$('txtfecincsue').focus();}
	  catch(e){}
  }
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function copiarDatosDetalle(Fila)
{
  var boton =  '<div class="menuBar">';
      boton += '<a class="menuButton" href="javascript:eliminarFila(\'dt_sueldo\','+Fila.id+');">';
	  boton += '<img src="../../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" width="15" height="15" border="0" align="absmiddle">';
	  boton += '</a>';
	  boton += '</div>';  
  var ultima_columna = boton ;
  valores = new Array($F("txtfecincsue"),$F("txtmonsuebas"),$F("txtmonsueint"),$F("txtmonsuenordia"),ultima_columna);
  alineaciones = new Array("center","right","right","right","center");
  
  for (cnt=0; cnt < valores.length; cnt++)
  {
	  agregarColumna(Fila,valores[cnt],alineaciones[cnt]);
  }
}
//-------------------------------------------------------------------------------------------------------------------------------------------//

//-------------------------------------------------------------------------------------------------------------------------------------------//
function ue_guardar()
{
	lb_valido=true;
	var la_objetos=new Array("txtcodper","txtcodnom");
	var la_mensajes=new Array("el código personal", "el código nómina" );
	lb_valido = valida_datos_llenos(la_objetos, la_mensajes);
	if(lb_valido)
	{
		if(($F('hidguardar')== "modificar")&&($F('hidpermisos').indexOf('m',0)<0 ))
		{
			alert("No tiene permiso para actualizar información.");
		}
		else
		{
			function onGuardar(respuesta)
			{
				if(trim(respuesta.responseText) !="" )
				{alert(respuesta.responseText);}
				ue_cancelar();
			}
			//Arreglo de detalles sueldos
			var sue_detalle = new Array();
			var filas = $('dt_sueldo').getElementsByTagName("tr");
			for (f=1; f<filas.length; f++)
			{
				var IdFila   = filas[f].getAttribute("id");
				var columnas = filas[f].getElementsByTagName("td");
				var sueldos = 
				{
				  "codper"      : $F('txtcodper'),
				  "codnom"      : $F('txtcodnom'),
				  "fecincsue"   : columnas[0].innerHTML,
				  "monsuebas"   : columnas[1].innerHTML,
				  "monsueint"   : columnas[2].innerHTML,
				  "monsuenordia": columnas[3].innerHTML
				}				
				sue_detalle[f-1] = sueldos;
			}
			var regsueldos =
			{
				"codper":$F('txtcodper'),
				"codnom":$F('txtcodnom'),
				"dt_sueldo":sue_detalle
			};
			
			var objeto = JSON.stringify(regsueldos);
			params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
			new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onGuardar});
		}
	}	
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function ue_configuracion()
{
  function onConfiguracion(respuesta)
  {  
  	if (trim(respuesta.responseText)!="")
	{ 
		var configuracion = (JSON.parse(respuesta.responseText));
		$('hidestsue').value= configuracion.estsue[0];
	}
  }	
  params = "operacion=ue_configuracion";
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onConfiguracion});
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function ue_sueldos_nomina()  
{
  lb_valido=true;
  ue_configuracion(); 
  if ( (ue_valida_null($('txtcodper')))&& (ue_valida_null($('txtcodnom'))) )
  {      
	  params = "operacion=ue_sueldos_nomina&codper="+trim($F('txtcodper'))+"&codnom="+$F('txtcodnom');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onSueldosNomina});  
  }
  function onSueldosNomina(respuesta)
  { 
        deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom");
        habilitar("txtfecincsue,txtmonsuebas,txtmonsueint,txtmonsuenordia");
	$('txtfecincsue').focus();
	$('hidguardar').value = "insertar";
	if (trim(respuesta.responseText)!= "")
	{       
		var sue_nomina = JSON.parse(respuesta.responseText);
		campos = "fecincsue,monsuebas,monsueint,monsuenordia";
		campos = campos.split(',');
		var cajas = new Array("txtfecincsue","txtmonsuebas","txtmonsueint","txtmonsuenordia");
		//Limpiamos los Datos del Detalle
		limpiar_detalle_sueldos(true);
		for (f=0; f<sue_nomina.fecincsue.length; f++)
		{  
		   for (c=0; c<campos.length; c++)
		   {  
			  if (f===0) 
			  { 
			  	  if (c==0)
				  {
					 eval(cajas[c]+".value = sue_nomina."+campos[c]+"["+f+"];");
					 li_ano = $F('txtfecincsue').substr(0,4);
					 li_mes = $F('txtfecincsue').substr(5,2);	
					 li_dia = $F('txtfecincsue').substr(8,2);  
					 $('txtfecincsue').value = (li_dia+"/"+li_mes+"/"+li_ano);
				  }
			  	  else
			  	  {
			     	 eval(cajas[c]+".value = uf_convertir(sue_nomina."+campos[c]+"["+f+"],2);");
			     	 if ($('hidestsue').value == 'B')
					 {
					 	var monto1 = eval("sue_nomina."+campos[1]+"["+f+"];");
					 }
					 else {var monto1 = eval("sue_nomina."+campos[2]+"["+f+"];");}
					 valido = true;
				  }	 
			  }
			  else
			  {
			  	  if (c==0)
				  {  
				  	 eval(cajas[c]+".value = sue_nomina."+campos[c]+"["+f+"];");
				  	 li_ano = $F('txtfecincsue').substr(0,4);
					 li_mes = $F('txtfecincsue').substr(5,2);	
					 li_dia = $F('txtfecincsue').substr(8,2);  
					 $('txtfecincsue').value = (li_dia+"/"+li_mes+"/"+li_ano);
				  }
			  	  else
			  	  {
					  if ($('hidestsue').value == 'B')
					  {
					 	 var monto2 = eval("sue_nomina."+campos[1]+"["+f+"];");
					  }
					  else {var monto2 = eval("sue_nomina."+campos[2]+"["+f+"];");}
					  if (monto2!=monto1) 
					  { 
					       eval(cajas[c]+".value = uf_convertir(sue_nomina."+campos[c]+"["+f+"],2);");
						   valido = true;
					  }
				  }	     
			  } 
		   }  
		   monto1 = monto2;
		   if (valido){
		   	 calcularSueldoDia();
		   	 ue_agregar_detalle();
			 valido = false;
		   }
		} //for

	} //if
  }	//onSueldosNomina
}
function calcularSueldoDia()
{
	sueldo_base=$F('txtmonsuebas');
	sueldo_dia = uf_convertir_monto(sueldo_base)/30;
	$('txtmonsuenordia').value = uf_convertir(sueldo_dia,2);  
	
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function limpiar_detalle_sueldos()
{  
  var FILAS = $("dt_sueldo").rows;
  if(FILAS.length > 1)
  {
	for (f=(FILAS.length-1); f>0; f--)
	{eliminarFila("dt_sueldo",FILAS[f].id)}
  }
  if ((arguments.length <= 0))
  {deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom");}
}
//--------------------------------------------------------------------------------------------------------------------------------------------//
function ue_eliminar()
{
  lb_valido = true;
  var la_objetos=new Array("txtcodper","txtcodnom");
  var la_mensajes=new Array("el código personal", "el código nómina" );
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	if (confirm("¿ Esta seguro de Eliminar los datos del Trabajador ?"))
	{
	  function onEliminar(respuesta)
	  {
		alert(respuesta.responseText);
		ue_cancelar();
	  }
	   params = "operacion=ue_eliminar&codper="+trim($F('txtcodper'))+"&codnom="+trim($F('txtcodnom'));
	   new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});	
	}
	else
	{
	   alert("Eliminación Cancelada !!!");	  
	   ue_cancelar();
	}
  };
}
//--------------------------------------------------------------------------------------------------------------------------------------------//
function ue_chequear_sueldos()
{
  if ( (ue_valida_null($('txtcodper')))&&(ue_valida_null($('txtcodnom'))) )
  {
	params = "operacion=ue_chequear_sueldos&codper="+trim($F('txtcodper'))+"&codnom="+$F('txtcodnom');  
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onChequear});		
  }
  
  function onChequear(respuesta)
  {
  	  //Deshabilitamos la cabecera
	  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom");
	  //Habilitamos el detalle
	  habilitar("txtfecincsue,txtmonsuebas,txtmonsueint,txtmonsuenordia,btnincdet,btnsuenom");
	  $('txtfecincsue').focus();		  
	  $('hidguardar').value = "modificar";
	  if (trim(respuesta.responseText) != "")
	  {
		  //Mostramos los Datos del Detalle
		  var det_sueldos = JSON.parse(respuesta.responseText);
		  campos = "fecincsue,monsuebas,monsueint,monsuenordia";
		  campos = campos.split(',');
		  var cajas = new Array("txtfecincsue","txtmonsuebas","txtmonsueint","txtmonsuenordia");
		  //Limpiamos los Datos del Detalle
		  limpiar_detalle_sueldos(true);
		  for (f=0; f<det_sueldos.fecincsue.length; f++)
		  { 
		    for (c=0; c<campos.length; c++)
		    {
			  if (cajas[c].substring(0,6)==="txtfec") 
			  {   
			  	 eval(cajas[c]+".value = det_sueldos."+campos[c]+"["+f+"];");  
				 li_ano = $F('txtfecincsue').substr(0,4);
				 li_mes = $F('txtfecincsue').substr(5,2);	
				 li_dia = $F('txtfecincsue').substr(8,2);  
				 $('txtfecincsue').value = (li_dia+"/"+li_mes+"/"+li_ano);  
				 
			  }
			  else
			  {
		      	      eval(cajas[c]+".value = det_sueldos."+campos[c]+"["+f+"];"); 
			  }
		    }
			$('txtmonsuebas').value = uf_convertir($('txtmonsuebas').value, 2);
			$('txtmonsueint').value = uf_convertir($('txtmonsueint').value, 2);
			$('txtmonsuenordia').value = uf_convertir($('txtmonsuenordia').value, 2);
			ue_agregar_detalle();
		  }
	   }
	   try
	   {catalogo.close();}
	   catch(e)
	   {}
	} //end of onChequear(respuesta)
}
//---------------------------------------------------------------------------------------------------------------------------------------------//
function ue_buscar()
{
	pagina="sps_cat_sueldos.html.php";
	catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}

function ue_cargar_registro_sueldos(arr_datos)
{
  ue_cancelar();
  $('hidguardar').value = "modificar";
  habilitar("btnincdet,btnsuenom");
  var cajas = new Array('txtcodper','txtnomper','txtapeper','txtcodnom','txtdennom');
  for (i=0; i<cajas.length; i++)
  {
	  $(cajas[i]).value = trim(arr_datos[i]);
  }
  ue_chequear_sueldos();
  if ((navigator.appName == "Netscape"))
  {	 
	  eval("$error_provocado;"); //Esta linea de abajo es un error provocado intencionalmente
  }
}

