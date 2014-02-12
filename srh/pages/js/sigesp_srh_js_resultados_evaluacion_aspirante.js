// JavaScript Document

var url= "../../php/sigesp_srh_a_resultados_evaluacion_aspirante.php";
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
  var la_objetos=new Array ("txtcodper","txtcodcon", "txtfecha", "txttoteva","txtconclu");
  
 
  var la_mensajes=new Array ("el codigo del personal", "el codigo del concurso","la fecha de registro de resultados","el resultado de la evaluacion","la conclusión de la evaluación");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {   divResultado = document.getElementById('mostrar');
	  divResultado.innerHTML= '';
	  function onGuardar(respuesta)
	  {
	   alert(respuesta.responseText); 
	   ue_cancelar();
	   	
	  }
	
	  var resultado = 
	  {
	    "codper" : $F('txtcodper'),
		"codcon" : $F('txtcodcon'),
	    "fecha" : $F('txtfecha'),
		"totaleval" : parseFloat($F('txttoteva')),
		"conclu" : $F('txtconclu')

		};
	
	
	  var objeto = JSON.stringify(resultado);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodper");
  var la_mensajes=new Array ("el codigo del aspirante. Seleccione un Registro del Catalago");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{ divResultado = document.getElementById('mostrar');
	  divResultado.innerHTML= '';
	  function onEliminar(respuesta)
	  {
		ue_cancelar();
		alert(respuesta.responseText);
	  }
	  
	  params = "operacion=ue_eliminar&codper="+$F('txtcodper')+"&codcon="+$F('txtcodcon');
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
  $('hidguardar').value = "";
  $('txtcodper').value="";
  $('txtnomper').value="";
  $('txtcodcon').value="";
  $('txtdescon').value="";
  $('txtfecha').value="";
  $('txtpunreqmin').value="";
  $('txtpunevalpsi').value="";
  $('txtpunenttec').value="";
  $('txttoteva').value="";
  $('txtconclu').value="";
  $('txtcodper').readOnly=true;
}


function Limpiar_Datos()
{
		
	
  $('txtcodper').value="";
  $('txtnomper').value="";
  $('txtcodcon').value="";
  $('txtdescon').value="";
  
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
				  Field.activate('txtcodper');//FOCUS
			  }
			  else
			  {
					Consultar();
			  }
		}
		params = "operacion=ue_chequear_codigo&codper="+$F('txtcodper')+"&codcon="+$F('txtcodcon');
		new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onChequearcodpersonal});	
	}
}

		
function Consultar()
{
     if  ( ($('txtcodper').value=="") || ($('txtcodcon').value=="")) 		
	 {
		 alert("Debe llenar los datos de Código de Aspirante y Código de Concurso!");	
		 
	 }
	 else {
	  f=document.form1;
	  f.operacion.value="CONSULTAR";
  	  f.action="../pantallas/sigesp_srh_p_resultados_evaluacion_aspirante.php";
	  f.existe.value="TRUE";			
	  f.submit();	
	 }
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




function catalogo_concurso()
{
     
   pagina="../catalogos/sigesp_srh_cat_concurso.php?valor_cat=0";

  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}


function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_resultados_evaluacion_aspirante.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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

