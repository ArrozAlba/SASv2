// JavaScript Document

var url= "../../php/sigesp_srh_a_evaluacion_eficiencia.php";
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
	$('txtfecha').focus();
	}
  }	

  params = "operacion=ue_nuevo_codigo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}



function ue_guardar()
{
  lb_valido=true;
  lb_correcto=false;
  var la_objetos=new Array ("txtnroeval", "txtcodeval", "txtfecha", "txtcodper", "txtcodeva", "txtfecini1", "txtfecfin1", "txtcomsup", "txtobs", "txtaccion");
  
 
  var la_mensajes=new Array ("el número de evaluación", "el tipo de evaluacion", "la fecha de la evaluación", "el código del personal", "el código del evaluador", "la fecha inicial del período de evaluación", "la fecha final el período de evaluación", "el comentario del supervisor", "los aspectos a mejorar","las acciones");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  
 if (($F('txtcodper') == $F('txtcodeva'))  && ($F('txtcodper')!=""))
	{  
	 alert("El Codigo del Personal y el Codigo del Evaluador deben ser diferentes.");
	 lb_valido= false;
	}
  
  if (lb_valido) {
      
	  lb_correcto=true;      
	  lb_valido2= ue_comparar_fechas($('txtfecini1').value,$('txtfecfin1').value,$('txtfecfin1'));  
			
		if (lb_valido2)
		{
		 lb_correcto = true;
		}
		else
		{
		 alert ('La Fecha de Final del Periodo de Evaluacion debe ser mayor a la Fecha Inicial');
		 $('txtfecfin1').value=""; 
	  }	
	 if ($('totalfilas').value == '1') 
	  {   
	  alert ('Debe consultar los datos para obener los items asociados a la evaluacion');
	  lb_correcto=false;
	  }
  }
  if(lb_correcto)
  {   divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
	   alert(respuesta.responseText);  
	   divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= "";
	   ue_cancelar();
	   	
	  }
	
	  //Arreglo 
		var eval_efi = new Array();
		var filas = $('grid').getElementsByTagName("tr");
		g=2;
		h=0;
		m=1;
		total=0;
		puntos=0;
		j=0;
		encontro=false;
		while (h!=10000)
		{
			while (h <= g)
			{
				selec = eval ("document.form1.rdselec"+m);
				if(selec[h].checked)
				{
					g=selec[h].value;
					valor = eval("document.form1.txtpuntos"+g);
					puntos= valor.value; 
					codite= eval("document.form1.txtcodite"+g);
					var reg = 
					{
					  "nroeval"          : $F('txtnroeval'),
					  "codite"   		 : codite.value,
					  "puntos"           : puntos
					}
					
					eval_efi[j] = reg;
					j=j+1;
				}
				else
				{
					puntos=0;
					g=selec[h].value;
					codite= eval("document.form1.txtcodite"+g);
					var reg = 
					{
					  "nroeval"          : $F('txtnroeval'),
					  "codite"   		 : codite.value,
					  "puntos"           : puntos
					}
					
					eval_efi[j] = reg;
					j=j+1;
				}
							
				if (g < (filas.length-2))
				{
					h=h+1;	
					g=selec[h].value;					
				}
				else
				{
					h=10000;
					
				}
			}
			 
			if (g != (filas.length-2))
			{
			   h=0;
			   m=m+1;
			}
			else
			{
				h=10000;
				
			}
			
	  }
	
	
	  var evaluacion = 
	  {
		"nroeval"    : $F('txtnroeval'),
		"tipo"		 : $F('txtcodeval'),
		"codper"     : $F('txtcodper'),
		"codeva"     : $F('txtcodeva'),
	    "fecha"   	 : $F('txtfecha'),
		"fecini"   	 : $F('txtfecini1'),
		"fecfin"   	 : $F('txtfecfin1'),
		"comen"      : $F('txtcomsup'),
		"obs"        : $F('txtobs'),
		"accion"     : $F('txtaccion'),
		"total"      : $F('txttotal'),
		"ranact"	 : $F('txtranact'),
		"eval_efi"	 : eval_efi
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
  var la_mensajes=new Array ("el número de evaluación. Seleccione una Evaluación de Eficiencia del Catalago");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if ((lb_valido) && ($('hidguardar').value=='modificar'))
  {
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{ 
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
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
  { alert ('Debe elegir una evaluacion del Catalogo');}
}



function ue_nuevo()
{
  f=document.form1;
  f.operacion.value="NUEVO";
  f.existe.value="FALSE";		
  f.action="sigesp_srh_p_evaluacion_eficiencia.php";
  f.submit(); 
}




function catalogo_personal()
{
      f= document.form1;
      pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=9";
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




function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_evaluacion_eficiencia.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function consultar_items () {
	
	 if  ( ($('txtcodeval').value=="") ) 		
	 {
		 alert("Debe llenar el  Tipo de Evaluación");	
		 
	 }
	 else {
	  f=document.form1;
	  f.operacion.value="CONSULTAR";
  	  f.action="../pantallas/sigesp_srh_p_evaluacion_eficiencia.php";
	  f.existe.value="TRUE";			
	  f.submit();	
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



function valida_puntos (ide1,ide2) 
{
	if (parseInt($(ide1).value) != parseInt($(ide2).value)) {
	alert ("El Puntaje Obtenido debe ser igual al Puntaje Requerido.");	
	ide1.value=0;
	 $(ide1).focus();
	}	
	
}

function validar_factor ()
{
  alert ("Recuerde que solamente debe llenar un solo item de cada factor");


}


function suma (ide1)

{
		
	var filas = $('grid').getElementsByTagName("tr");
	g=2;
	h=0;
	m=1;
	total=0;
	puntos=0;
	encontro=false;
	while (h!=10000)
	{
		while (h <= g)
		{
			selec = eval ("document.form1.rdselec"+m);
			if(selec[h].checked)
			{
				g=selec[h].value;
				valor = eval("document.form1.txtpuntos"+g);
				puntos= valor.value; 
				total= parseFloat(total) + parseFloat(puntos);
		        puntos=0;
			}
						
			if (g < (filas.length-2))
			{
				h=h+1;	
				g=selec[h].value;					
			}
			else
			{
				h=10000;
				
			}
		}
		 
		if (g != (filas.length-2))
		{
		   h=0;
		   m=m+1;
		}
		else
		{
			h=10000;
			
		}
		
  }
   $(ide1).value=total;
}


function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}


//FUNCIONES PARA EL CALENDARIO

// Esta es la funcion que detecta cuando el usuario hace click en el calendario, necesaria
function selected(cal, date) {
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

    var cal = new Calendar(1, null, selected, closeHandler);
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
   