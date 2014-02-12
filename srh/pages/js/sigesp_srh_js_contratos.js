// JavaScript Document

var url= "../../php/sigesp_srh_a_contratos.php";
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
	$('txtcodper').focus();
	}
  }	

  params = "operacion=ue_nuevo_codigo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}


function ue_guardar()
{
  lb_valido=true;
  lb_valido2=true;
  var la_objetos=new Array ("txtnroreg","txtcodper", "txtcodtipcon", "txtfecini", "txtfecfin", "txtdes", "txtobs", "txtmontocon","txtfunc","comboestcon");
  
 
 var la_mensajes=new Array ("el numero de registro", "el código personal",   "el código del tipo de contrato", "la fecha de inicio del contrato", "la fecha de culminación del contrato", "la descripción del contrato", "la observacion del contrato", "el monto","las funciones del contratado","el estado del contrato");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  
  lb_valido2= ue_comparar_fechas($('txtfecini').value,$('txtfecfin').value,$('txtfecfin'));  
	
	if (!lb_valido2)
	{
	 	alert ('La Fecha de Final del Contrato debe ser mayor a la Fecha Inicial');
	 	$('txtfecfin').value=""; 
	 	lb_valido = false;
    }	
  
  
  if ((lb_valido) && (lb_valido2))
  {   divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
	   alert(respuesta.responseText);   
	   ue_cancelar();
	   }
	  var contrato = 
	  {
		  
	    "nroreg"    : $F('txtnroreg'),
		"codper"    : $F('txtcodper'),
		"nomper"    : $F('txtnomper'),
		"apeper"    : $F('txtapeper'),
		"nacper"    : $F('cmbnacper'),
		"codpro"    : $F('txtcodpro'),
		"codtipcon" : $F('txtcodtipcon'),
		"fecini"   	: $F('txtfecini'),
		"fecfin"    : $F('txtfecfin'),
		"obs" 		: $F('txtobs'),
		"des"       : $F('txtdes'),
		"monto"     : $F('txtmontocon'),
		"codcar"    : $F('txtcodcar'),
  		"codnom"    : $F('txtcodnom'),
		"coduniadm" : $F('txtcoduniadm'),
		"funcion"   : $F('txtfunc'),
		"horario"	: $F('txthor'), 
		"estado"	: $F('comboestcon')		
		};
	  var objeto = JSON.stringify(contrato);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroreg");
  var la_mensajes=new Array ("el número de Contrato. Seleccione un Contrato del Catalago");
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
	alert ('Debe elegir un resigtro del Catálogo');  
  }
}



function ue_nuevo()
{
  $('hidguardar').value = "";
  $('txtnroreg').value="";
  $('txtnomper').value="";
  $('txtapeper').value="";
  $('txtcodper').value="";
  $('cmbnacper').value="null";
  $('txtcodpro').value="";
  $('txtdespro').value="";
  $('txtcodtipcon').value="";
  $('txtdentipcon').value="";
  $('txtfecini').value="";
  $('txtfecfin').value="";
  $('txtobs').value="";
  $('txtdes').value="";
  $('txtmontocon').value="";   
  $('txtcodcar').value="";
  $('txtcodnom').value="";
  $('txtdescar').value="";
  $('txtcoduniadm').value="";
  $('txtdenuniadm').value="";
  $('txtfunc').value="";
  $('txthor').value="";  
  $('comboestcon').value="null";
  $('txtnroreg').readOnly=false;
  $('txtcodper').readOnly=false;
  $('txtnomper').readOnly=false;
  $('txtapeper').readOnly=false;
  divResultado = document.getElementById('mostrar');
  divResultado.innerHTML= '';
  ue_nuevo_codigo();
}


function catalogo_tipocontrato()
{
     
   pagina="../catalogos/sigesp_srh_cat_tipocontrato.php?valor_cat=0";

  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}


	
 

function catalogo_personal()
{
    
   pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=12";
 
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}

function catalogo_solicitud_empleo()
{
    
   pagina="../catalogos/sigesp_srh_cat_solicitud_empleo.php?valor_cat=0"+"&tipo=8";
 
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}






function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_contratos.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=580,height=420,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function catalogo_unidad_adm()
{
	window.open("../catalogos/sigesp_srh_cat_unidadadmin.php?valor_cat=0&tipo=3","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
}



function catalogo_cargo()
{
      f= document.form1;
	  pagina="../catalogos/sigesp_srh_cat_cargo.php?valor_cat=0";
      window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}

function catalogo_profesion()
{
     
   pagina="../catalogos/sigesp_srh_cat_profesion.php?valor_cat=0";

  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
 }

function catalogo_cargo_rac()
{
   pagina="../catalogos/sigesp_srh_cat_cargo_rac.php?valor_cat=0&tipo=2";
   window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
   
}




function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
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
