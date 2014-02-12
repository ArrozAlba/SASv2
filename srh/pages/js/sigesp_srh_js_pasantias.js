// JavaScript Document

var url= "../../php/sigesp_srh_a_pasantias.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";

Event.observe(window,'load',ue_inicializar,false);



function ue_nuevo_codigo()
{
  function onNuevo(respuesta)
  {
	if ($('txtnropas').value=="") {
	
	$('txtnropas').value  = trim(respuesta.responseText);
	$('txtfecini').focus();
	}
  }	

  params = "operacion=ue_nuevo_codigo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}


function ue_inicializar()
{
  params = "operacion=ue_inicializar&codpai="+'058';
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});

  function onInicializar(respuesta)
  {
	if (trim(respuesta.responseText) != "")
	{	  
	  var respuestas = respuesta.responseText.split('&');
	  num_respuesta = -1;
	  //Estados
	  num_respuesta++;
	 if (trim(respuestas[num_respuesta]) != "")
	{
	  var estados = JSON.parse(respuestas[num_respuesta]);
	  for (i=0; i<estados.desest.length; i++)
	  {$('comboest').options[$('comboest').options.length] = new Option(estados.desest[i],estados.codest[i]);}
	}
  
  }	
   
  }	
}


function LimpiarComboEstado()
{
  removeAllOptions($('combomun'));	
  $('combomun').selectedIndex = 0;
  LimpiarComboMunicipio();
}


function LimpiarComboMunicipio()
{
  removeAllOptions($('combopar'));
  $('combopar').selectedIndex = 0;
}




function ue_cancelarmun()
{
  $('combomun').selectedIndex = 0;
  removeAllOptions($('combopar'));  
  ue_LimpiarComboParroquia();
  $('hidguardar').value = "";  
  scrollTo(0,0);
}

function ue_cambioestado()
{
  LimpiarComboEstado();
  if (ue_valida_null($('comboest')))
  {
    function onCambioEstado(respuesta)
    {
	  var municipios = JSON.parse(respuesta.responseText);
	  for (j=0; j<municipios.codmun.length; j++)
	  {$('combomun').options[$('combomun').options.length] = new Option(municipios.denmun[j],municipios.codmun[j]);}
	  //El siguiente if es usado cuando viene del catalogo	  
	  if (trim($('hidcodmun').value) != "")
	  {
		$('combomun').value = $('hidcodmun').value;
		$('hidcodmun').value = "";
		ue_cambiomunicipio();
	  }
    }
		
    params = "operacion=ue_cambioestado&codpai="+'058'+"&codest="+$F('comboest');
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onCambioEstado});
  }  
}


function ue_cambiomunicipio()
{
  LimpiarComboMunicipio();
  if (ue_valida_null($('combomun')))
  {
    function onCambioMunicipio(respuesta)
    {
	  var parroquias = JSON.parse(respuesta.responseText);
	  for (i=0; i<parroquias.codpar.length; i++)
	  {$('combopar').options[$('combopar').options.length] = new Option(parroquias.denpar[i],parroquias.codpar[i]);}	  
	  //El siguiente if es usado cuando viene del catalogo	  
	  if (trim($('hidcodpar').value) != "")
	  {
		$('combopar').value = $('hidcodpar').value;
		$('hidcodpar').value = "";
		ocultar_mensaje("mensaje");
		if ($F('hidguardar') == "modificar")
		{ try{catalogo.close();}catch(e){}}
	  }
    }
    params = "operacion=ue_cambiomunicipio&codpai="+'058'+"&codest="+$F('comboest')+"&codmun="+$F('combomun');
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onCambioMunicipio});  
  }  
}



function ue_cancelar()
{
     ue_nuevo();
    scrollTo(0,0);
   
}




function ue_guardar()
{
  lb_valido=true;
  lb_valido2=true;
  lb_valido3=true;
  var la_objetos=new Array ("txtnropas","txtfecini", "txtfecfin", "txtcedpas","txtnompas", "txtapepas","txtfecnac", "combosexo",  "comboedociv" , "txttelhab", "txttelmov",  "comboest", "combomun",  "combopar", "txtdirpas", "txtuniv", "txtcarre","txtcodper");
  
 
  var la_mensajes=new Array ("el numero de pasatia", "la fecha de incorporacion","la fecha de culminacion","la cedula del pasante",  "el nombre del pasante", "el apellido del pasante", "la fecha nacimiento ","el sexo ", "el estado civil", "el telefono de habitancion",  "el telofono movil",  "el estado", "el municipio", "la parroquia", "la direccion del pasante","el insituto universitario", "la carrera", "el tutor empresarial");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  
  
  lb_valido2= ue_comparar_fecha_nacimiento (document.form1.txtfecnac.value); 
  
  if (!lb_valido2)	
	{
	 alert ('La Fecha de Nacamiento no es la correcta, la persona debe ser mayor a 15 quince años');
	 document.form1.txtfecnac.value=""; 
	  lb_valido2=false;
   }
   
   lb_valido3=ue_comparar_fechas($('txtfecini').value,$('txtfecfin').value);
   
   if (!lb_valido3)	
	{
	 alert ('La Fecha de Inicio de la Pasantía debe ser menor a la Fecha de Culminacion');
	 document.form1.txtfecfin.value=""; 
	  lb_valido3=false;
   }
   
  
  if ((lb_valido)&&(lb_valido2)&&(lb_valido3))
  {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
	   alert(respuesta.responseText);     
	   ue_cancelar();
	  
	  }
	
	
	
	  var pasantia = 
	  {
	    "nropas" : $F('txtnropas'),
		"cedpas" : $F('txtcedpas'),
	    "fecini" : $F('txtfecini'),
		"fecfin" : $F('txtfecfin'),
		"apepas" : $F('txtapepas'),
		"nompas" : $F('txtnompas'),
	    "sexpas" : $F('combosexo'),
		"fecnac" : $F('txtfecnac'),
		"telhab" : $F('txttelhab'),
		"email"  : $F('txtemail'),
		"codpar" : $F('combopar'),
		"codmun" : $F('combomun'),
		"codest" : $F('comboest'),
		"dirpas" : $F('txtdirpas'),
		"telmov" : $F('txttelmov'),
		"edociv" : $F('comboedociv'),
	    "inst_univ" : $F('txtuniv'),
	    "carrera" : $F('txtcarre'),
        "tutor" : $F('txtcodper'),
	   "estado" : 'Activa'

		};
	
	
	  var objeto = JSON.stringify(pasantia);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnropas");
  var la_mensajes=new Array ("el numero de Pasantia. Seleccione un Registro de Pasantia del Catalago");
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
	  
	  params = "operacion=ue_eliminar&nropas="+$F('txtnropas');
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
  $('hidcodpar').value = "";
  $('hidcodmun').value = "";
  $('txtnropas').value="";
  $('txtcedpas').value="";
  $('txtapepas').value="";
  $('txtnompas').value="";
  $('txtfecini').value="";
  $('txtfecfin').value="";
  $('txtfecnac').value="";
  $('txttelhab').value="";
  $('txtemail').value="";
  $('comboest').value="null";
  $('combomun').value="null";
  $('combopar').value="null";
  $('txtdirpas').value="";
  $('txttelmov').value="";
  $('txtcarre').value="";
  $('txtuniv').value="";
  $('txtcodper').value="";
  $('txtnomper').value="";
  $('comboedociv').value="null";
  $('combosexo').value="null";
  $('txtnropas').readOnly=false;
   divResultado = document.getElementById('mostrar');
  divResultado.innerHTML= '';
  LimpiarComboMunicipio();
  LimpiarComboEstado();
  ue_nuevo_codigo();
}


function valida_combomun () {

f= document.form1;
if (f.comboest.value =="null") 
  {alert ('Debe seleccionar un Estado');   }

}


function valida_combopar () {

f= document.form1;
if (f.combomun.value =="null")
  { alert ('Debe seleccionar un Municipio');   }
 
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
		window.open("../catalogos/sigesp_srh_cat_pasantias.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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



function ue_comparar_fecha_nacimiento(fecha1)
{
	vali=false;
	ano1 = fecha1.substr(6,4);
	
	fecha_actual= new Date;
	
	ano2= fecha_actual.getFullYear();	
	
	if (ano1 < ano2)
	{
		resultado=ano2-ano1;
		if (resultado>15)
		{
		  vali = true;
		}		
	}	
	return vali;	
}


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

