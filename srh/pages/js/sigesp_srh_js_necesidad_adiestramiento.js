// JavaScript Document

var url= "../../php/sigesp_srh_a_necesidad_adiestramiento.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


function ue_cancelar()
{
     ue_nuevo();
    scrollTo(0,0);
    $('txtnroreg').focus();
}


function ue_nuevo_codigo()
{
  function onNuevo(respuesta)
  {
	if ($('txtnroreg').value=="") {
	
	$('txtnroreg').value  = trim(respuesta.responseText);
	$('txtfecha').focus();
	}
  }	

  params = "operacion=ue_nuevo_codigo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}




function ue_guardar()
{
  lb_valido=true;

  var la_objetos=new Array ("txtnroreg","txtfecha","txtcodunivi","txtcodper", "txtcodsup", "txtcompe", "txtarea","txtobj","txtestcap","txtobs" );
  var la_mensajes=new Array ("el numero de registro", "la fecha de diagnostico","el código de la unidad vipladin", "el código del personal","el codigo del supervisor","las competencias a ser fortalecidas "," el area ser atendido","el objetivo del adiestramiento","la estrategia de capacitacion","la observacion");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if (($F('txtcodper') == $F('txtcodsup'))  && ($F('txtcodper')!=""))
    {   
     alert("El Codigo del Personal y el Codigo del Supervisor deben ser diferentes.");
	 lb_valido = false;
	}
  
  if(lb_valido)
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
	   
	 
	  //Arreglo de las causas de adiestramiento
	  var causa = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	  g=0;
	 total=0;
	 for (f=1; f<(filas.length - 1); f++)
	  {
		selecc= eval ('document.form1.cmbselcau'+f);
		
		if (selecc.value=='S')
		{	
		    codcau = eval ('document.form1.txtcodcauadi'+f);	
			var reg_cau = 
			{
			  "nroreg"          : $F('txtnroreg'),		  
			  "codcau"   		: codcau.value
			  
			}
	
		   causa[g] = reg_cau;
		   g=g+1;
		}
	  }
	  
	   //Arreglo de las competencias de evaluación
	  var competencia = new Array();
	  var filas = $('grid2').getElementsByTagName("tr");
	  c=0;
	  for (f=1; f<(filas.length - 1); f++)
	  {
		  
		selecc2= eval ('document.form1.cmbprio'+f);		
		if (selecc2.value!='0')
		{
	
			comp= eval ('document.form1.txtcodcomp'+f);
			prio= eval ('document.form1.cmbprio'+f);
			var reg_com = 
			{
			  "nroreg"       : $F('txtnroreg'),
			  "codcom"       : comp.value,
			  "prio"   		 : prio.value
			}
			competencia[c] = reg_com;
			c=c+1;
		}
	  }
	  
	  var necesidad = 
	  {
		"nroreg"      : $F('txtnroreg'),
		"fecha"		  : $F('txtfecha'),
		"coduni"   	  : $F('txtcodunivi'),
		"codper"	  : $F('txtcodper'),
		"codsup"	  : $F('txtcodsup'),
		"comptec"	  : $F('txtcompe'),
		"area"	 	  : $F('txtarea'),
		"obj"		  : $F('txtobj'),
		"estra"	      : $F('txtestcap'),
		"obs" 		  : $F('txtobs'),
		"causas"	  : causa,
		"competencias": competencia
		};
	
	
	  var objeto = JSON.stringify(necesidad);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}



function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroreg");
  var la_mensajes=new Array ("el número de registro. Seleccione un Registro del Catalago");
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
  {
	   alert("Debe elegir un Registro del Catalago");
	  
	 }
}



function ue_nuevo()
{
  f=document.form1;
  f.operacion.value="NUEVO";
  f.existe.value="FALSE";		
  f.action="sigesp_srh_p_necesidad_adiestramiento.php";
  f.submit(); 
}


function Consultar()
{
    
	  f=document.form1;
	  f.operacion.value="CONSULTAR";
  	  f.action="../pantallas/sigesp_srh_p_necesidad_adiestramiento.php";
	  f.existe.value="TRUE";			
	  f.submit();	
	 
}
	



function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_necesidad_adiestramiento.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}



function catalogo_unidad()
{
	window.open("../catalogos/sigesp_srh_cat_uni_vipladin.php?valor_cat=0&tipo=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function catalogo_personal()
{
  f= document.form1;
  pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=10";
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
  }
  
function catalogo_supervisor()
{
      f= document.form1;
      pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=2";
      window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
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
