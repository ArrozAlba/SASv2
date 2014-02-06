// JavaScript Document

var url= "../../php/sigesp_srh_a_evaluacion_desempeno.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";



function ue_cancelar()
{
     ue_nuevo();
    scrollTo(0,0);
 
}

function ue_nuevo_codigo()
{
  function onNuevo(respuesta)
  {
	if ($('txtnroeval').value=="") {
	
	$('txtnroeval').value  = trim(respuesta.responseText);
	$('txtcodeval').focus();
	}
  }	

  params = "operacion=ue_nuevo_codigo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}


function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroeval","txtcodeval", "txtfecini", "txtfecfin", "txtcodper", "txtfecha", "txtcodeva", "txtcodsup", "txtobs");
  
 
  var la_mensajes=new Array ("el numero de evaluacion","el tipo de evaluacion",  "la fecha inicial del periodo de evaluacion" ,"la fecha final del periodo de evaluacion", "el código del personal", "la fecha de la evaluacion","el código del evaluador", "el código del supervisor del evaluador", "el comentario del supervisor");
  
  lb_val = valida_datos_llenos(la_objetos,la_mensajes);
  
   if (($F('txtcodper') == $F('txtcodeva'))  && ($F('txtcodper')!=""))
    {  
     alert("El Codigo del Personal y el Codigo del Evaluador deben ser diferentes.");
	 lb_valido = false;
	}
	
  if (($F('txtcodper') == $F('txtcodsup'))  && ($F('txtcodper')!=""))
    {   
     alert("El Codigo del Personal y el Codigo del Supervisor deben ser diferentes.");
	 lb_valido = false;
	}
	
	
	
	
  lb_valido2= ue_comparar_fechas($('txtfecini').value,$('txtfecfin').value,$('txtfecfin'));  
	
	if (lb_valido2)
	{
	 lb_valido = true;

	}
	else
	{
	 alert ('La Fecha de Final del Periodo de Evaluacion debe ser mayor a la Fecha Inicial');
	 $('txtfecfin').value=""; 
	 lb_valido = false;
    }	
	  
	  if (($('totalfilas').value == '1') || ($('totalfilas2').value == '1'))
  	  {   
      alert ('Debe consultar los datos para obener las Competencias y Odis asociados a la evaluacion');
	  lb_valido=false;
  	  }
  
  
  if ((lb_valido) && (lb_val))
  {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
	   alert(respuesta.responseText);   	  
	   ue_cancelar();
	   divResultado = document.getElementById('mostrar');
       divResultado.innerHTML="";
	   
	  }
	
	  //Arreglo de los objetivos de desempeño individual
	  var odis = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	  g=2;
	 total=0;
	 for (f=1; f<(filas.length - 1); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("textarea");
		
		if (columnas[2].value=="")
		{
			rango1=0;	
	    }
		else
		{
			rango1=columnas[2].value;
		}
		
		if (columnas[3].value=="")
		{
			pesran1=0;	
	    }
		else
		{
			pesran1=columnas[3].value;
		}
		cododi = eval ('document.form1.txtcododi'+f);
		var odi = 
		{
		  "nroeval"          : $F('txtnroeval'),
		  "fecha"            : $F('txtfecha'),
		  "cododi"   		 : cododi.value,
		  "rango"          	 : rango1,
		  "pesran"         	 : pesran1
		  
		}
		g++;
		odis[f-1] = odi;
	  }
	  
	   //Arreglo de las competencias de evaluación
	  var competencia = new Array();
	  var filas = $('grid2').getElementsByTagName("tr");
	  g=2;
	 total=0;
	 for (f=1; f<(filas.length - 1); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("textarea");
		
		if (columnas[2].value=="")
		{
			peso2=0;	
	    }
		else
		{
			peso2=columnas[2].value;
		}
		
		if (columnas[3].value=="")
		{
			rango2=0;	
	    }
		else
		{
			rango2=columnas[3].value;
		}
		
		var reg = 
		{
		  "nroeval"          : $F('txtnroeval'),
		  "fecha"            : $F('txtfecha'),
		  "codcom"   		 : columnas[0].value,
		  "peso"          	 : peso2,
		  "rango"         	 : rango2
		  
		}
		g++;
		competencia[f-1] = reg;
	  }
	  
	  var evaluacion = 
	  {
		"nroeval"    : $F('txtnroeval'),
		"tipo"		 : $F('txtcodeval'),
		"fecha"   	 : $F('txtfecha'),
		"fecini"   	 : $F('txtfecini'),
		"fecfin"   	 : $F('txtfecfin'),
		"codper"	 : $F('txtcodper'),
		"codeva"	 : $F('txtcodeva'),
		"codsup"	 : $F('txtcodsup'),
		"totalodi"	 : $F('txtresodi'),
		"totalcom"	 : $F('txtrescom'),
		"total"		 : $F('txttotal'),
		"ranact"	 : $F('txtranact'),
		"comentario" : $F('txtobs'),
		"opinion"	 : $F('txtopi'),
		"odi"		 : odis,
		"competencia": competencia
		};
	
	
	  var objeto = JSON.stringify(evaluacion);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroeval");
  var la_mensajes=new Array ("el número de evaluación. Seleccione una Evaluación del Catalago");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if ((lb_valido) && ($('hidguardar').value=='modificar'))
  {
	divResultado = document.getElementById('mostrar');
    divResultado.innerHTML= img;
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{
	  function onEliminar(respuesta)
	  {
		ue_cancelar();
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= "";
		alert(respuesta.responseText);
	  }
	  
	  params = "operacion=ue_eliminar&nroeval="+$F('txtnroeval');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	  ue_cancelar();
	  alert("Eliminación Cancelada !!!");	  
	}
  }
  else
  {
	   alert("Debe elegir una Evaluación del Catalago");
	  
	 }
}



function ue_nuevo()
{
  f=document.form1;
  f.operacion.value="NUEVO";
  f.existe.value="FALSE";		
  f.action="sigesp_srh_p_evaluacion_desempeno.php";  
  f.submit(); 
}


function Consultar()
{
     if  ( ($('txtcodper').value=="") ) 		
	 {
		 alert("Debe llenar el Código de Personal.");	
		 
	 }
	 else if ( ($('txtcodeval').value=="") ) 		
	 {
		 alert("Debe llenar el Tipo de Evaluacion.");	
		 
	 }
	 else if (($('txtfecini').value=="")&&($('txtfecfin').value==""))
	 {
		alert ("Debe Llenar el Periodo de Evaluacion.");	 
	 }
	 else if  (!ue_comparar_fechas($('txtfecini').value,$('txtfecfin').value,$('txtfecfin'))) 
	{
		 alert ('La Fecha de Final del Periodo de Evaluacion debe ser mayor a la Fecha Inicial');
		 $('txtfecfin').value=""; 
		 
    }	
	 else 
	 {
		  f=document.form1;
		  f.operacion.value="CONSULTAR";
		  f.action="../pantallas/sigesp_srh_p_evaluacion_desempeno.php";
		  f.existe.value="TRUE";			
		  f.submit();	
	 }
}
	

function catalogo_personal()
{
  f= document.form1;
  pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=9";
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
  }
  
function catalogo_supervisor()
{
      f= document.form1;
      pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=2";
      window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
  }
  
  function catalogo_evaluador()
{
      f= document.form1;
     pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=3"; 
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
  }
  

function catalogo_evaluacion()
{
      f= document.form1;
      pagina="../catalogos/sigesp_srh_cat_tipoevaluacion.php?valor=0";
      window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}
 





function Limpiar_Datos()
{
  $('txtcodper').value="";
  $('txtdeneval').value="";
  $('txtcodeval').value="";
  $('txtnomper').value="";  
  $('txtcodcarper').value="";  
  $('txtfecini').value="";  
  $('txtfecfin').value="";  
}



function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_evaluacion_desempeno.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}




function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}


function ue_multiplicar (ide1, ide2, ide3)
{
  
  
  if ($(ide1).value!=" ")
  {
	   valor1 = parseInt($(ide1).value);

   }
  else
  {  
    valor1=0; 
	
   }
   
   if ($(ide2).value!=" ")
  {
	   valor2 = parseInt($(ide2).value); 
	 
   }
  else
  {  
     valor2=0;
	  
   } 
 
 
  $(ide3).value= valor1 * valor2;
 
}


function sumar_odi (ide1,ide2,ide3)

{
	var filas = $('grid').getElementsByTagName("tr");
	g=2;
	total=0;
	for (f=1; f<(filas.length - 1); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("textarea");
		if (columnas[3].value == "") {
			total= parseInt(total) + 0;
		}
		else
		{
		 total= parseInt(total) + parseInt(columnas[3].value);
		}
		g++;
	  }
   $(ide1).value=total;
   $(ide3).value=  parseInt($(ide2).value) + parseInt(total);
}



function sumar_competencias (ide1,ide2,ide3)

{
	var filas = $('grid2').getElementsByTagName("tr");
	g=2;
	total=0;
	h=filas.length - 1;
	aux_colum= filas[h].getElementsByTagName("textarea");
	if ((aux_colum[3].value == " ") || (aux_colum[2].value == " ") ) {
			
		for (f=1; f<(filas.length -2); f++)
		  {
			
			var IdFila   = filas[g].getAttribute("id");
			var columnas = filas[g].getElementsByTagName("textarea");
			 total= parseInt(total) + parseInt (columnas[4].value);
			 g++;
			}
			
			
		}
	else {
		for (f=1; f<(filas.length -1); f++)
		  {
			
			var IdFila   = filas[g].getAttribute("id");
			var columnas = filas[g].getElementsByTagName("textarea");
			 total= parseInt(total) + parseInt (columnas[4].value);
			 g++;
			}
	 }
   $(ide1).value=total;
   $(ide3).value=  parseInt($(ide2).value) + parseInt(total);
   
}


function ue_chequear_numero (ide1)
{
  valor1 = parseInt($(ide1).value);
  
  if ((valor1 <1) || (valor1 > 5))
  {
    alert("El rango debe ser un valor entero comprendido entre 1 y 5.");
	$(ide1).value=0;
  }
}





function consultar_rango_actuacion ()
 {
	if  ( ($('txttotal').value=="") || ($('txtcodeval').value=="")) 		
	 {
		 alert("El total y el tipo de evaluación deben ser llenados.");
		 
	 }
	 else {
	 
	   function onConsultar(respuesta)
		{
		$('txtranact').value  = trim(respuesta.responseText);
	
	  }	
	
	  params = "operacion=consultar_rango_actuacion&codeval="+$F('txtcodeval')+"&total="+$F('txttotal');
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onConsultar});
		
	 }
}


//FUNCIONES PARA EL CALENDARIO

// Esta es la funcion que detecta cuando el usuario hace click en el calendario, necesaria
function seleodid(cal, date) {
  cal.sel.value = date; // just update the date in the input field.
                           
  if (cal.dateClicked )
      cal.callCloseHandler();
}


function closeHandler(cal) {
  cal.hide();                        // hide the calendar

  _dynarch_popupCalendar = null;
}


function showCalendar(id, format, showsTime, showsOtherMonths) {
  var el = document.getElementById(id);
  if (_dynarch_popupCalendar != null) {
    // we already have some calendar created
    _dynarch_popupCalendar.hide();                 // so we hide it first.
  } else {
    // first-time call, create the calendar.

    var cal = new Calendar(1, null, seleodid, closeHandler);
    if (typeof showsTime == "string") {
      cal.showsTime = true;
      cal.time24 = (showsTime == "24");
    }
    if (showsOtherMonths) {
      cal.showsOtherMonths = true;
    }
    _dynarch_popupCalendar = cal;                  // remember it in the global var
    cal.setRange(1900, 2070);        // min/max year allowed.
    cal.create();
  }
  _dynarch_popupCalendar.setDateFormat(format);    // set the specified date format
  _dynarch_popupCalendar.parseDate(el.value);      // try to parse the text in field
  _dynarch_popupCalendar.sel = el;                 // inform it what input field we use
 _dynarch_popupCalendar.showAtElement(el, "T");        // show the calendar

  return false;
}


 //--------------------------------------------------------
//	Función que verifica que la fecha 2 sea mayor que la fecha 1
//----------------------------------------------------------
   function ue_comparar_fechas(fecha1,fecha2,oTxt)
{
	vali=false;
	dia1 = fecha1.substr(0,2);
	mes1 = fecha1.substr(3,2);
	ano1 = fecha1.substr(6,4);
	dia2 = fecha2.substr(0,2);
	mes2 = fecha2.substr(3,2);
	ano2 = fecha2.substr(6,4);
	if (ano1 < ano2)
	{
		vali = true; 
	}
    else 
	{ 
    	if (ano1 == ano2)
	 	{ 
      		if (mes1 < mes2)
	  		{
	   			vali = true; 
	  		}
      		else 
	  		{ 
       			if (mes1 == mes2)
	   			{
 					if (dia1 <= dia2)
					{
		 				vali = true; 
					}
	   			}
      		} 
     	} 	
	}
	
	return vali;
	
}
   


function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		codper=f.txtcodper.value;
		nroeval=f.txtnroeval.value;
		fecini=f.txtfecini.value;
		fecfin=f.txtfecfin.value;
		reporte=f.txtreporte.value;
	
			pagina="../../../reporte/"+reporte+"?codper="+codper+"&nroeval="+nroeval+"&fecini="+fecini+"&fecfin="+fecfin;
					window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		
		
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operaciÃƒÂ³n");
   	}		
}


