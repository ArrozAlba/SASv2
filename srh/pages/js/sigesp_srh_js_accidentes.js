// JavaScript Document

var url= "../../php/sigesp_srh_a_accidentes.php";
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
	$('txtfecelab').focus();
	}
  }	

  params = "operacion=ue_nuevo_codigo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}



function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroreg","txtfecelab", "txtcodper", "txtcodacc", "txtfecacc", "txtdes", "txttestigos");
  
 
 var la_mensajes=new Array ("el numero de registro", "la fecha de elaboracion","el código personal",   "el código del accidente", "la fecha del accidente",  "la descripción el accidente", "el o los testigos del accidente");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
	    alert(respuesta.responseText);   
	   ue_cancelar();
	   
	  }
	
	
	
	  var accidente = 
	  {
		  
	    "nroreg"    : $F('txtnroreg'),
		"fecelab"   : $F('txtfecelab'),
		"codper"    : $F('txtcodper'),
		"codacc"    : $F('txtcodacc'),
		"fecacc"   	: $F('txtfecacc'),
		"des" 		: $F('txtdes'),
		"testigos"  : $F('txttestigos'),
		"reposo"	: $F('txtrep')
		
		};
	
	
	  var objeto = JSON.stringify(accidente);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroreg");
  var la_mensajes=new Array ("el número de Registro. Seleccione un Registro del Catalago");
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
	  
	  params = "operacion=ue_eliminar&nroreg="+$F('txtnroreg');
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
  $('txtnroreg').value="";
  $('txtfecelab').value="";
  $('txtnomper').value="";
  $('txtcodper').value="";
  $('txtcodacc').value="";
  $('txtdenacc').value="";
  $('txtfecacc').value="";
  $('txtdes').value="";
  $('txttestigos').value="";
  $('txtrep').value="";
  $('txtnroreg').readOnly=true;
  divResultado = document.getElementById('mostrar');
  divResultado.innerHTML= '';
  ue_nuevo_codigo();
}


function catalogo_tipoaccidentes()
{
     
	 
   pagina="../catalogos/sigesp_srh_cat_tipoaccidente.php?valor_cat=0";

  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}


	
 

function catalogo_personal()
{
  
   pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=8";
 
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}






function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_accidentes.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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
