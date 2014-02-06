// JavaScript Document

var url= "../../php/sigesp_srh_a_evaluacion_psicologica.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


function ue_cancelar()
{
     ue_nuevo();
    scrollTo(0,0);
    $('txtcodper').focus();
}

function ue_chequear_codigo()
{
	if ((ue_valida_null($('txtcodper'))) && ($('hidguardar').value!='modificar'))
    {
	
		function onChequearcodpersonal(respuesta)
		{	  
			  if (trim(respuesta.responseText) != "")
			  {
				  alert(respuesta.responseText);
				  Field.clear('txtcodper');//INICIALIZAR
				  Field.clear('txtnomper');//INICIALIZAR
				  Field.clear('txtfecha');//INICIALIZAR
				  Field.activate('txtcodper');//FOCUS
			  }
			  else
			  {
				//  Field.activate('txtnomper');
			  }
		}
		params = "operacion=ue_chequear_codigo&codper="+$F('txtcodper')+"&codcon="+$F('txtcodcon');
		new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onChequearcodpersonal});	
	}
}


function ue_guardar()
{
  lb_valido=true;
  lb_valido2=true;
  lb_valido3=true;
 
  var la_objetos=new Array ("txtcodeval","txtcodper","txtcodcon", "txtfecha");
  
 
  var la_mensajes=new Array ("el tipo de evaluacion","el código del personal", "el código del concurso","la fecha de la evaluación");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
 
  if (lb_valido) 
  {
	  lb_valido2= ue_comparar_fechas($('txtfechaaper').value,$('txtfecha').value);
	  if (!lb_valido)
	  {
		 lb_valido2=false;
		 alert ('La fecha de la Evaluación debe ser mayor a la fecha de apertura del Concurso seleccionado.');
	  }
  }
  
  if ((lb_valido2)&&(lb_valido))
  {
	  if ($('totalfilas').value==1)
	  {
		lb_valido3=false;
		alert ('Debe consultar los items de evaluacion');
		  
	  }
  }
  
  if ((lb_valido)&&(lb_valido2)&&(lb_valido3))
  
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
	
	  //Arreglo con el detalle
	  var evaluacion = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	 g=2;
	 total=0;
	 for (f=1; f<(filas.length - 1); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
		if (columnas[3].value=="")
	  	{
		   puntos = 0;
		}
	    else
		{
			puntos = columnas[3].value;
	    }
		var eva = 
		{
		  "codper"           : $F('txtcodper'),
		  "fecha"            : $F('txtfecha'),
		  "codite"   		 : columnas[0].value,
		  "puntos"           : puntos
		  
		}
	
		g++;
		evaluacion[f-1] = eva;
	  }
	  var evaluacion_p = 
	  {
		"codper"     : $F('txtcodper'),
		"tipo"		 : $F('txtcodeval'),
		"codcon"     : $F('txtcodcon'),
	    "fecha"   	 : $F('txtfecha'),
		"total"		 : $F('txtres'),
		"evaluacion" : evaluacion
		};
	
	
	  var objeto = JSON.stringify(evaluacion_p);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodper");
  var la_mensajes=new Array ("el código de personal. Seleccione una Evaluación Psicologica del Catalago");
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
		divResultado = document.getElementById('mostrar');
       divResultado.innerHTML="";
	  }
	  
	  params = "operacion=ue_eliminar&codper="+$F('txtcodper')+"&fecha="+$F('txtfecha');
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
  f.action="sigesp_srh_p_evaluacion_psicologica.php";
  f.submit(); 
}




function catalogo_persona_concurso()
{

	f= document.form1;
	  codcon= f.txtcodcon.value;
  if (codcon=="") { alert ('Debe seleccionar un Concurso');}
  
  else {
    
	  pagina="../catalogos/sigesp_srh_cat_persona_concurso.php?valor_cat=0"+"&codcon="+codcon;
	  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  }
 
 
}


function catalogo_evaluacion()
{
    pagina="../catalogos/sigesp_srh_cat_tipoevaluacion.php?valor=0";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
 
 
}
 
function catalogo_concurso()
{
   pagina="../catalogos/sigesp_srh_cat_concurso.php?valor_cat=0";
  window.open(pagina,"catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}



function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_evaluacion_psicologica.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}



function consultar_items () {
	
	 if  ( ($('txtcodeval').value=="") ) 		
	 {
		 alert("Debe llenar el  Tipo de Evaluación.");	
		 
	 }
	 else {
	  f=document.form1;
	  f.operacion.value="CONSULTAR";
  	  f.action="../pantallas/sigesp_srh_p_evaluacion_psicologica.php";
	  f.existe.value="TRUE";			
	  f.submit();	
	 }
	
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
   
