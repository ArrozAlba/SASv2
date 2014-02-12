// JavaScript Document

var url="../../php/sigesp_srh_a_evaluacion_adiestramiento.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


function ue_cancelar()
{
     ue_nuevo();
    scrollTo(0,0);
    $('txtnroreg').focus();
}




function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroreg","txtfecha", "txtobseval");
  
 
  var la_mensajes=new Array ("el número de registro","la fecha de evaluacion", "la observacion de la solicitud");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if ((lb_valido) && ($('hidguardar').value=='modificar'))
  {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
	   alert(respuesta.responseText);
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= "";
	   ue_cancelar();	
	  }
	
	  //Arreglo 
	  var personal = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	 g=2;
	 total=0;
	 for (f=1; f<(filas.length - 1); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
	    if (columnas[4].checked) {
			 
			 asistencia= '1'
			 }
		 
		 else {
			 
			 asistencia= '0'
			 }
		 
		var registro = 
		{
		  "nroreg"      : $F('txtnroreg'),
		  "fecha"       : $F('txtfecha'),
		  "codper" 		: columnas[0].value,
		  "asistencia"  : asistencia
		
		}
		g++;
		personal[f-1] = registro;
	  }
	  var evaluacion = 
	  {
		"nroreg"     : $F('txtnroreg'),
		"fecha"      : $F('txtfecha'),
	    "obs" 	 	 : $F('txtobseval'),
		"personal"	 : personal
		};
	
	
	  var objeto = JSON.stringify(evaluacion);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  }
  
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroreg");
  var la_mensajes=new Array ("el número de registro. Seleccione una Evaluación de Adiestramiento del Catalago");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if ((lb_valido) && ($('hidguardar').value='modificar'))
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
	  
	  params = "operacion=ue_eliminar&nroreg="+$F('txtnroreg')+"&fecha="+$F('txtfecha');
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
  f.action="sigesp_srh_p_evaluacion_adiestramiento.php";
  f.submit(); 
}



function Consultar()
{
     if  ($('txtnroreg').value=="")  		
	 {
		 alert("Debe llenar el número de registro. Seleccione uno del catálogo!");	
		 
	 }
	 else {
	  f=document.form1;
	  f.operacion.value="CONSULTAR";
  	  f.action="../pantallas/sigesp_srh_p_evaluacion_adiestramiento.php";
	  f.existe.value="TRUE";			
	  f.submit();	
	 }
}

function Limpiar_Datos()
{
  $('txtnroreg').value="";
  $('txtcodsol').value="";
  $('txtdes').value="";
  $('txtnomsol').value="";
  $('txtfecsol').value="";
  $('txtuniad').value="";
  $('txtdenuniad').value="";
  $('txtcodprov').value="";
  $('txtdenprov').value="";
  $('txtdurhras').value="";
  $('txtcosto').value="";
  $('txtfecfin').value="";
  $('txtfecini').value="";
  $('txtobs').value="";
 
}





function catalogo_adiestramiento()
{
     pagina="../catalogos/sigesp_srh_cat_solicitud_adiestramiento.php?valor=0";

  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=680,height=500,resizable=yes,location=no,dependent=yes");
  
 
}



function ue_buscar()
{
	f=document.form1;
	window.open("../catalogos/sigesp_srh_cat_evaluacion_adiestramiento.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,left=50,top=50,location=no,resizable=yes");
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

