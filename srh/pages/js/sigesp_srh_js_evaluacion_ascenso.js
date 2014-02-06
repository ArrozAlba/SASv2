// JavaScript Document

var url= "../../php/sigesp_srh_a_evaluacion_ascenso.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


function ue_cancelar()
{
     ue_nuevo();
    scrollTo(0,0);
    $('txtcodper').focus();
}




function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroreg", "txtfecha", "txtobs");
  var la_mensajes=new Array ("el numero de registro", "la fecha de la evaluación", "la observacion");
   
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
 
 
 if(lb_valido)
  { 
     lb_valido=ue_comparar_fechas($('txtfecreg').value,$('txtfecha').value);
  
     if (!lb_valido)
	 {
	   alert ('La fecha de evaluacion debe ser mayor a la fecha de registro del ascenso');	 
	   $('txtfecha').value="";	  
	 }
	 else
	 {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img; 
	  
	  function onGuardar(respuesta)
	  {
	   alert(respuesta.responseText);   
	   ue_cancelar();
	
	  }
	
	  //Arreglo 
	  var res_eval = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	 g=2;
	 total=0;
	 for (f=1; f<(filas.length - 1); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
		if (columnas[3].value=="")
		{
			puntos=0;
		}
		{
			puntos=columnas[3].value;
		}
		var req = 
		{
		  "nroreg"           : $F('txtnroreg'),
		  "fecha"            : $F('txtfecha'),
		  "codite"   		 : columnas[0].value,
		  "puntos"           : columnas[3].value
		  
		}
		
		g++;
		res_eval[f-1] = req;
	  }
	  var resultado = 
	  {
		"nroreg"     : $F('txtnroreg'),
		"fecha"   	 : $F('txtfecha'),
		"obs"		 : $F('txtobs'),
		"tipo_eval"	 : $F('txtcodeval'),
		"total"      : $F('txtres'),
		"res_eval"	 : res_eval
		};
	
	
	  var objeto = JSON.stringify(resultado);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
   }
  }
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroreg");
  var la_mensajes=new Array ("el numero de registro de ascenso. Seleccione un Registro de postulado para Ascenso del Catalago");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img; 
	  
	  function onEliminar(respuesta)
	  {
		ue_cancelar();
		alert(respuesta.responseText);
	  }
	  
	  params = "operacion=ue_eliminar&nroreg="+$F('txtnroreg')+"&fecha="+$F('txtfecha');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	  ue_cancelar();
	  alert("Eliminación Cancelada !!!");	  
	}
  }
}



function ue_nuevo()
{
  f=document.form1;
  f.operacion.value="NUEVO";
  f.existe.value="FALSE";		
  f.action="sigesp_srh_p_evaluacion_ascenso.php";
  f.submit(); 
}




function catalogo_personal()
{
      
   pagina="../catalogos/sigesp_srh_cat_nivelseleccion.php?valor_cat=0"+"&tipo=4";

  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}




function consultar_items () {
	
	 if  ( ($('txtcodeval').value=="") ) 		
	 {
		 alert("Debe llenar el  Tipo de Evaluación.");	
		 
	 }
	 else {
	  f=document.form1;
	  f.operacion.value="CONSULTAR";
  	  f.action="../pantallas/sigesp_srh_p_evaluacion_ascenso.php";
	  f.existe.value="TRUE";			
	  f.submit();	
	 }
	
	}



function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_evaluacion_ascenso.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=540,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}



function catalogo_registro_ascenso()
{
   
   pagina="../catalogos/sigesp_srh_cat_registro_ascenso.php?valor_cat=0";

  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=540,height=400,resizable=yes,location=no,dependent=yes");
  
 
}

function catalogo_evaluacion()
{
    
   pagina="../catalogos/sigesp_srh_cat_tipoevaluacion.php?valor_cat=0";

  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}


function ue_suma (ide1)

{
	var filas = $('grid').getElementsByTagName("tr");
	g=2;
	total=0;
	for (f=1; f<(filas.length -1); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
		if (columnas[3].value == "") {
			total= parseInt(total) + 0;
		}
		else
		{
		 total= parseInt(total) + parseInt (columnas[3].value);
		}
		g++;
	  }
   $(ide1).value=total;
}


function valida_puntos (ide1,ide2) 
{
	if (parseInt($(ide1).value) > parseInt($(ide2).value)) {
	alert ("El Puntaje Obtenido debe ser menor o igual al Puntaje Requerido.");	
	ide1.value="";
	 $(ide1).focus();
	}	
	
}


function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}



  
 //--------------------------------------------------------
//	Función que verifica que la fecha 2 sea mayor que la fecha 1
//----------------------------------------------------------
   function ue_comparar_fechas(fecha1,fecha2)
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
