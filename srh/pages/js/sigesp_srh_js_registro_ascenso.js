// JavaScript Document

var url= "../../php/sigesp_srh_a_registro_ascenso.php";
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
	if ($('txtnroreg').value=="") {
	
	$('txtnroreg').value  = trim(respuesta.responseText);
	$('txtfecreg').focus();
	}
  }	

  params = "operacion=ue_nuevo_codigo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}


function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroreg", "txtfecreg","txtcodcon","txtcodper", "txtobs","txtcodsup","txtopi");
  
 
  var la_mensajes=new Array ("el numero de la postulación", "la fecha de la postulación","el código del concurso","el código del personal", "la observación","el código del supervisor", "la opinión del supervisor");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  
   if (($F('txtcodper') == $F('txtcodsup'))  && ($F('txtcodper')!=""))
	{  
	 alert("El Codigo del Personal y el Codigo del Supervisor deben ser diferentes.");
	 lb_valido= false;
	}
  
  if(lb_valido)
  {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img; 
	  
	  function onGuardar(respuesta)
	  {
	   alert(respuesta.responseText);   
	   ue_cancelar();
	   
	  }
	
	 
	  var registro = 
	  {
		"nroreg"	 : $F('txtnroreg'),
		"fecha"		 : $F('txtfecreg'),
		"codcon"     : $F('txtcodcon'),
		"codper"   	 : $F('txtcodper'),
		"obs"		 : $F('txtobs'),
		"codsup"	 : $F('txtcodsup'),
		"opinion"	 : $F('txtopi')
		};
	
	
	  var objeto = JSON.stringify(registro);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroreg");
  var la_mensajes=new Array ("el número de postulación. Seleccione una Postulación para Ascenso del Catalago");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if ((lb_valido)&& ($('hidguardar').value=='modificar'))
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
	  
	  params = "operacion=ue_eliminar&nroreg="+$F('txtnroreg');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	  ue_cancelar();
	  alert("Eliminación Cancelada !!!");	  
	}
  }
   else
  { alert ('Debe elegir un Registro del Catalogo');}
}



function ue_nuevo()
{
  $('hidguardar').value = "";
  $('txtnroreg').value="";
  $('txtfecreg').value="";
  $('txtcodcon').value="";
  $('txtdescon').value="";
  $('txtdescar').value="";
  $('txtreqmin').value="";
  $('txtcodper').value="";
  $('txtnomper').value="";
  $('txtcaract').value="";
  $('txtfecing').value="";
  $('txtobs').value="";
  $('txtcodsup').value="";
  $('txtnomsup').value="";
  $('txtcodcarsup').value="";
  $('txtopi').value="";
  $('txtnroreg').readOnly=false
  divResultado = document.getElementById('mostrar');
  divResultado.innerHTML= ''; 	  
  ue_nuevo_codigo()
}


	

function catalogo_personal()
{
    
   pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=4";
 
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}


function catalogo_supervisor()
{
  
   pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=2";
   
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}


function catalogo_concurso()
{
      f= document.form1;
      pagina="../catalogos/sigesp_srh_cat_concurso.php?valor_cat=0";
      window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}




function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_registro_ascenso.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=540,height=400,left=50,top=50,location=no,resizable=yes");
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

