// JavaScript Document
var url    = '../php/sps_def_articulos.php';
var params = 'operacion=';
var metodo = 'get';
var catalogo;

Event.observe(window, 'load', ue_cancelar , false);

function ue_cancelar()
{
   document.form1.reset();
   limpiar_datos_detalle();
   limpiar_tabla_detalle();
   habilitar("txtid_art,txtnumart,txtfecvig,btnbusart");
   deshabilitar("txtconart,txtnumlitart,cmboperador,txtcanmes,txtdiasal,cmbtiempo,cmbcondicion,cmbestacu,txtdiaacu");
   $('txtdiaacu').value = "0,00";
   scrollTo(0,0);
}

function ue_nuevo()
{	
  function onNuevo(respuesta)
  {    
        ue_cancelar();
	$('hidguardar').value = "insertar";
	$('txtid_art').value  = trim(respuesta.responseText);
	deshabilitar("txtid_art");
        habilitar("txtnumart,txtfecvig,txtconart,txtnumlitart,cmboperador,txtcanmes,txtdiasal,cmbtiempo,cmbcondicion,cmbestacu,txtdiaacu");
	$('txtnumart').focus();
  }	
  params = "operacion=ue_nuevo";
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onNuevo});
}

function ue_guardar()
{
	lb_valido=true;
	
	var la_objetos=new Array("txtid_art","txtnumart","txtfecvig","txtconart");
	var la_mensajes=new Array("el código", "el número","la fecha vigente","el concepto" );
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
				ue_cancelar();
				if(trim(respuesta.responseText) !="" )
				{alert(respuesta.responseText);}
			}
			//Arreglo de detalles 
			var art_detalle = new Array();
			var filas = $('dt_art').getElementsByTagName("tr");
			for (f=1; f<filas.length; f++)
			{
				var IdFila   = filas[f].getAttribute("id");
				var columnas = filas[f].getElementsByTagName("td");
				var dtart = 
				{
				  "id_art"      : $F('txtid_art'),
				  "numart"      : $F('txtnumart'),
				  "fecvig"      : $F('txtfecvig'),	
				  "conart"      : $F('txtconart'),
				  "numlitart"   : columnas[0].innerHTML,
				  "operador"    : columnas[1].innerHTML,
				  "canmes"      : columnas[2].innerHTML,
				  "diasal"      : columnas[3].innerHTML,
				  "tiempo"      : columnas[4].innerHTML,
				  "condicion"   : columnas[5].innerHTML,
				  "estacu"      : columnas[6].innerHTML,
				  "diaacu"      : columnas[7].innerHTML
				}				
				art_detalle[f-1] = dtart;	
			}
			var art =
			{
				"id_art":$F('txtid_art'),
				"numart":$F('txtnumart'),
				"fecvig":$F('txtfecvig'),
				"conart":$F('txtconart'),
				"dt_art":art_detalle
			};
			
			var objeto = JSON.stringify(art);
			params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
			new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onGuardar});
		}
	}	
}
function ue_buscar()
{
	pagina="sps_cat_articulos.html.php";
	catalogo = popupWin(pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=620,height=300,resizable=yes,location=no,top=0,left=0");
}

function ue_cargarCatalogo(arr_datos)
{
  ue_cancelar();
  $('hidguardar').value = "modificar";
  var cajas = new Array('txtid_art','txtnumart','txtfecvig','txtconart');
  for (i=0; i<cajas.length; i++)
  {
	  $(cajas[i]).value = trim(arr_datos[i]);
  }
  ue_chequear_articulo();
  if ((navigator.appName == "Netscape"))
  {	 
	  eval("$error_provocado;"); //Esta linea de abajo es un error provocado intencionalmente
  }
}

function ue_agregar_detalle()  
{
  lb_valido=true;
  var la_objetos =new Array ("txtnumlitart","cmboperador","txtcanmes","txtdiasal","cmbtiempo","cmbcondicion","cmbestacu");  
  var la_mensajes=new Array ("el literal ","Operador","cantidad en meses", "dias de salario", "el tiempo", "la condición ", "el estatus ");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes );
  if ((lb_valido) && ($F('cmbestacu') == "S"))
  {
	var la_objetos =new Array ("txtdiaacu");  
    var la_mensajes=new Array ("los dias acumulados");
    lb_valido = valida_datos_llenos(la_objetos,la_mensajes );
  }
  if(lb_valido)
  {
      var nuevaFila = clonarFila("dt_art","fila0");
      copiarDatosDetalle(nuevaFila);
      limpiar_datos_detalle();
      try{$('txtnumlitart').focus();}
	  catch(e){}
  }
}

function limpiar_datos_detalle()
{
  $('txtnumlitart').value = "";
  $('txtcanmes').value = "";
  $('txtdiasal').value = "";
  $('txtdiaacu').value = "";
  $('cmbestacu').value = "";
}

function limpiar_tabla_detalle()
{  
  var FILAS = $("dt_art").rows;
  if(FILAS.length > 1)
  {
	for (f=(FILAS.length-1); f>0; f--)
	{eliminarFila("dt_art",FILAS[f].id)}
  }
}

function copiarDatosDetalle(Fila)
{
  var boton =  '<div class="menuBar">';
      boton += '<a class="menuButton" href="javascript:eliminarFila(\'dt_art\','+Fila.id+');">';
	  boton += '<img src="../../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" width="15" height="15" border="0" align="absmiddle">';
	  boton += '</a>';
	  boton += '</div>';  
  var ultima_columna = boton ;

  valores = new Array($F("txtnumlitart"),$F('cmboperador'),$F("txtcanmes"),$F("txtdiasal"),$F('cmbtiempo'),$F("cmbcondicion"),$F("cmbestacu"),$F("txtdiaacu"),ultima_columna);
  alineaciones = new Array("center","center","center","center","center","center","center","center","center");
  
  for (cnt=0; cnt < valores.length; cnt++)
  {
	  agregarColumna(Fila,valores[cnt],alineaciones[cnt]);
  }
}

function ue_chequear_articulo()
{
  if ( (ue_valida_null($('txtid_art')))&&(ue_valida_null($('txtnumart')))&&(ue_valida_null($('txtfecvig'))) )
  {
	params = "operacion=ue_chequear_articulo&id_art="+trim($F('txtid_art'))+"&numart="+$F('txtnumart')+"&fecvig="+$F('txtfecvig');  
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onChequear});		
  }
  
  function onChequear(respuesta)
  {            	  	
  	  deshabilitar("txtid_art,txtnumart,txtfecvig,btnbusart");
	  habilitar("txtconart,txtnumlitart,cmboperador,txtcanmes,txtdiasal,cmbtiempo,cmbcondicion,cmbestacu,txtdiaacu");
	  $('txtnumlitart').focus();		  
	  $('hidguardar').value = "modificar";
	  if (trim(respuesta.responseText) != "")
	  {       
		  //Mostramos los Datos del Detalle
		  var det_articulos = JSON.parse(respuesta.responseText);
		  campos = "numlitart,operador,canmes,diasal,tiempo,condicion,estacu,diaacu";
		  campos = campos.split(',');
		  var cajas = new Array("txtnumlitart","cmboperador","txtcanmes","txtdiasal","cmbtiempo","cmbcondicion","cmbestacu","txtdiaacu");
		  //Limpiamos los Datos del Detalle
		  limpiar_detalle_articulos(true);
		  for (f=0; f<det_articulos.numlitart.length; f++)
		  { 
		    for (c=0; c<campos.length; c++)
		    {
		      eval(cajas[c]+".value = trim(det_articulos."+campos[c]+"["+f+"]);");
		    }
			ue_agregar_detalle();
		  }
	   }
	   try
	   {catalogo.close();}
	   catch(e)
	   {}
	} //end of onChequear(respuesta)
}

function limpiar_detalle_articulos()
{  
  var FILAS = $("dt_art").rows;
  if(FILAS.length > 1)
  {
	for (f=(FILAS.length-1); f>0; f--)
	{eliminarFila("dt_art",FILAS[f].id)}
  }
  if ((arguments.length <= 0))
  {deshabilitar("txtid_art,txtnumart,txtfecvig");}
}

function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtid_art", "txtnumart", "txtfecvig");
  var la_mensajes=new Array ("el Código del Articulo", "el número de Articulo","la fecha vigente del Articulo");
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
	  params = "operacion=ue_eliminar&id_art="+$F('txtid_art')+"&numart="+$F('txtnumart')+"&fecvig="+$F('txtfecvig');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	  ue_cancelar();
	  alert("Eliminación Cancelada !!!");	  
	}
  };
}
