// JavaScript Document

var url= "../../php/sigesp_srh_a_inscripcion_concurso.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
 
 
//Event.observe(window,'load',ue_inicializar,false);


function ue_inicializar()
{
  
  params = "operacion=ue_inicializar";
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
 
  
  function onInicializar(respuesta)
  {
	
		if (trim(respuesta.responseText) != "")
		{	  
		  var respuestas = respuesta.responseText.split('&');
		  num_respuesta = -1;
		  //Países
		  num_respuesta++;
		  if (trim(respuestas[num_respuesta]) != "")
			{
				var pais = JSON.parse(respuestas[num_respuesta]);
				for (i=0; i<pais.despai.length; i++)
				{
				  $('cmbcodpainac').options[$('cmbcodpainac').options.length] = new Option(pais.despai[i],pais.codpai[i]);
				}
			} 
	    }	
	 
    }	//end function onInicializar

}


function LimpiarComboPais()
{
  $('cmbcodpainac').value="";	
  $('cmbcodpainac').selectedIndex = 0;
  LimpiarComboEstado();
}

function LimpiarComboEstado()
{
  removeAllOptions($('cmbcodestnac'));	
  $('cmbcodestnac').selectedIndex = 0;
  
}

function ue_cambiopais()
{
  function onInicializar(respuesta)
  {
	if (trim(respuesta.responseText) != "")
	{
	  var estados = JSON.parse(respuesta.responseText);
	  for (i=0; i<estados.desest.length; i++)
	  {$('cmbcodestnac').options[$('cmbcodestnac').options.length] = new Option(estados.desest[i],estados.codest[i]);}
	}
  }	
  LimpiarComboEstado();
  params = "operacion=ue_inicializarestado&codpai="+$('cmbcodpainac').value;
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
}



function ue_chequear_cedula()
{
	if ((ue_valida_null($('txtcodper'))) && ($('hidguardar').value!='modificar'))
    {
	
		function onChequearcodpersonal(respuesta)
		{	  
			  if (trim(respuesta.responseText) != "")
			  {
				  alert(respuesta.responseText);
				  Field.clear('txtcodper');//INICIALIZAR
				  Field.activate('txtcodper');//FOCUS
			  }
			  else
			  {
				//  Field.activate('txtnomper');
			  }
		}
		params = "operacion=ue_chequear_cedula&codper="+$F('txtcodper')+"&codcon="+$F('txtcodcon');
		new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onChequearcodpersonal});	
	}
}



function ue_guardar()
{
  
  lb_valido=true;
  var la_objetos=new Array ("txtcodcon","txtfecreg", "txtcodper","txtnomper", "txtapeper","txtfecnacper", "cmbsexper",  "cmbedocivper" , "txttelhabper", "txttelmovper", "cmbnacper", "cmbcodpainac", "cmbcodestnac", "txtdirper" );
  
 
  var la_mensajes=new Array ("el código del concurso", "la fecha de registro","la cedula/codigo del concursante",  "el nombre del concursante", "el apellido del concursante", "la fecha nacimiento del concursante","el sexo del concursante", "el estado civil del concrusante",  "el telefono de habitancion del concursante",  "el telofono movil del concursante", "la nacionalidad del concursante", "el pais de nacimiento del concursante", "el estado de nacimiento del concursante", "la parroquia de nacimiento del concursante",  "la direccion del concursante");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	 if($('txtfecnacper').value!="")
	{
		lb_valido= ue_comparar_fecha_nacimiento(document.form1.txtfecnacper.value);  
		
		if (!lb_valido)
		
		{
	     alert ('La Fecha de Nacamiento no es la correcta, la persona debe ser mayor de edad');
	     document.form1.txtfecnacper.value=""; 
	    }
		else
		{
	  
			  divResultado = document.getElementById('mostrar');
			  divResultado.innerHTML= img; 
			  
			  function onGuardar(respuesta)
			  {
			    alert(respuesta.responseText);
			    divResultado.innerHTML= '';
				if (trim(respuesta.responseText)!='Error al Guardar el Registro de Concursante')
				{
					ue_nuevo_codestudio();
					ue_nuevo_codcurso();
					ue_nuevo_trabajo();
					ue_nuevo_familiar();
				}
				
				
			  }
						
			  var concurso = 
			  {
				"codcon" : $F('txtcodcon'),
				"codper" : $F('txtcodper'),
				"fecreg" : $F('txtfecreg'),
				"apeper" : $F('txtapeper'),
				"nomper" : $F('txtnomper'),
				"sexper" : $F('cmbsexper'),
				"fecnac" : $F('txtfecnacper'),
				"telhab" : $F('txttelhabper'),				
				"codpai" : $F('cmbcodpainac'),				
				"codest" : $F('cmbcodestnac'),
				"dirper" : $F('txtdirper'),				
				"telmov" : $F('txttelmovper'),
				"estciv" : $F('cmbedocivper'),
				"tipper" : $F('txttipper'),
				"codpro" : $F('txtcodpro'),
				"corele" : $F('txtcoreleper'),
				"nivaca" : $F('cmbnivacaper'),
				"nacper" : $F('cmbnacper')
			};
			
			
			  var objeto = JSON.stringify(concurso);
			  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
			  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
			  
		}
	}
  }
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodcon","txtcodper");
  var la_mensajes=new Array ("el numero de concurso. Seleccione un  Registro del Catalago","el codigo del personal. Seleccione un Registro del Catalago");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img; 
	  function onEliminar(respuesta)
	  { divResultado.innerHTML= '';
		ue_cancelar();
		alert(respuesta.responseText);
	  }
	  
	  params = "operacion=ue_eliminar&codcon="+$F('txtcodcon')+"&codper="+$F('txtcodper');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	  ue_cancelar();
	  alert("Eliminación Cancelada !!!");	  
	}
  }
}



function ue_cancelar()
{
     ue_nuevo();
    scrollTo(0,0);
   
}




function ue_nuevo()
{
	var mydate=new Date();
	
	$('hidguardar').value = "";
	$('hidcodestnac').value = "";
	$('txtcodcon').value = "";
	$('txtdescon').value = "";
	$('txtcodcar').value = "";
	$('txtdescar').value = "";
	$('txtcantcar').value = "";
	$('txtcodtipconcur').value = "";
	$('txtfechaaper').value = "";
	$('txtfechacie').value = "";
	$('txtcodper').value = "";
	$('txtfecreg').value =dia_actual();
	$('txtapeper').value = "";
	$('txtnomper').value = "";
	$('cmbsexper').value = "null";
	$('txtfecnacper').value = "";
	$('txttelhabper').value = "";	
	$('cmbcodpainac').value = "null";
	$('cmbcodestnac').value = "null";
	$('txtdirper').value = "";				
	$('txttelmovper').value = "";
	$('cmbedocivper').value = "null";
	$('cmbnacper').value = "null";
	$('cmbnivacaper').value = "";
	$('txtcodpro').value = "";
	$('txtdespro').value = "";
	$('txtcoreleper').value = "";
	$('txttipper').value = "";
	$('txtcodper').readOnly=false;
	$('txtapeper').readOnly=false;
	$('txtnomper').readOnly=false;
	$('txtfecnacper').readOnly=false;
	$('txttelhabper').readOnly=false;
	$('txtdirper').readOnly=false;		
	$('txttelmovper').readOnly=false;
	$('txtcodpro').readOnly=false;
	$('txtdespro').readOnly=false;
	$('txtcoreleper').readOnly=false;
    LimpiarComboPais();
	ue_limpiar_estudios();
	ue_limpiar_cursos();
	ue_limpiar_trabajo();
	ue_limpiar_familiar(); 

}




function valida_cmbcodestnac () {

f= document.form1;
if (f.cmbcodpainac.value =="") 
  {alert ('Debe seleccionar un Pais');   }

}


function catalogo_concurso()
{
      f= document.form1;
     
     if(f.hidstatus.value=='C')
  {
   pagina="../catalogos/sigesp_srh_cat_concurso.php?valor=1";
  
  }else
  {
   pagina="../catalogos/sigesp_srh_cat_concurso.php?valor=0";
  } 
  window.open(pagina,"catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}


function ue_buscar()
{
	window.open("../catalogos/sigesp_srh_cat_inscripcion_concurso.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	
}

function catalogo_profesion()
{
      
   pagina="../catalogos/sigesp_srh_cat_profesion.php?valor_cat=0";
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}

function catalogo_personal()
{
  f= document.form1;
  pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=14";
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
  }


function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}



function validarreal2(e,t)
  { 
      valor=t.value;
      longitud=valor.length;
      
      keynum=0;
      if(window.event) // IE
      {   
        keynum = e.keyCode
      }      
      else if(e.which) // Netscape/Firefox/Opera
      {
        keynum = e.which
      }

	if ((keynum==9)||(keynum==13)||(keynum==8)||(keynum==0))
	{
	
	}
	else if (!(((keynum>=48)&&(keynum<=57))||(keynum==46)))
	{        
        return false;
	}
      else if (keynum==46)
      {
        if (longitud==0)
        {
          return false; 
        }
        if (haypunto(t))
        {
          return false; 
        } 
      }

  }
  
function haypunto(t)
  {
    valor=t.value;
    longitud=valor.length;
    punto=false;

    for (i=0;i<longitud;i++)
    {
      car=valor.substring(i,i+1);
      if (car==".")
      {
        punto=true;
      }
    }

    return punto;
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
function ue_comparar_fecha_nacimiento(fecha1)
{
	vali=false;
	ano1 = fecha1.substr(6,4);
	
	fecha_actual= new Date;
	
	ano2= fecha_actual.getFullYear();	
	
	if (ano1 < ano2)
	{
		resultado=ano2-ano1;
		if (resultado>18)
		{
		  vali = true;
		}		
	}	
	return vali;	
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
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// FORMACION ACADEMICA / PROFESIONAL ////


function ue_nuevo_codestudio()
{ 

 function onNuevo(respuesta)
  {	   
   $('txtcodestper').value  = trim(respuesta.responseText);
   $('txtcodestper').focus();
  }
  var codper = $F('txtcodper');
  var codcon = $F('txtcodcon');

 params = "operacion=ue_nuevo_estudio&codper="+codper+"&codcon="+codcon;
 new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});

}


function ue_limpiar_estudios()
{
   
   $('txtcodestper').value="";
   $('cmbtipestper').value="null";
   $('txtinsestper').value="";
   $('txtcar').value="";
   $('txtanofin').value="";
   $('txtanoapr').value="";
   $('chktit').checked=false;
   $('hidguardar_est').value="insertar";	   
   scrollTo(0,0);
}
		

function ue_guardar_estudios()
{
  lb_valido = false; 
  if (($('txtcodper').value =='')||($('txtcodcon').value ==''))
  { alert ('La información básica no puede estar vacía. Seleccione un registro de concursante del catalogo.');
    lb_valido= false;
  }
  else
  {  
  	lb_valido= true;
  }
	
  if(lb_valido)
  { 
		
		la_objetos =new Array ("txtcodestper", "cmbtipestper", "txtcar", "txtinsestper", "txtanofin", "txtanoapr");
		la_mensajes=new Array ("el Codigo del Estudio","el Nivel de Estudio","la Carrera","el Instituto",
							   "el Año de Finalizacion","los Años Aprobados");
		
		lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
	
		if (lb_valido)
		{
	
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img;
		  function onGuardar(respuesta)
		  {
			 alert(respuesta.responseText); 
			 divResultado = document.getElementById('mostrar');
			 divResultado.innerHTML= "";
			 if (trim(respuesta.responseText)!="Los Datos Basicos deben estar registrados para poder realizar esta operacion")
			 {
			 	ue_limpiar_estudios();
			 	ue_nuevo_codestudio();
			 }
			 
		  }		  
		  
		  if ($('chktit').checked)
		  {
		  	titulo=1;
		  }	
		  else
		  {
			titulo=0;  
		  }
		  
		  var estudios =
		  {
			  "codper"   	: $F('txtcodper'),
			  "codcon"   	: $F('txtcodcon'),
			  "codestper"	: $F('txtcodestper'),
			  "nivel"		: $F('cmbtipestper'),
			  "insestper"	: $F('txtinsestper'),
			  "carrera"		: $F('txtcar'),
			  "anofin"		: $F('txtanofin'),
			  "anoapr"		: $F('txtanoapr'),
			  "titulo"		: titulo	  
		  }
		
		  var objeto2 = JSON.stringify(estudios);
		  $aux = "insertar";	
		  params = "operacion=ue_guardar_estudios&objeto2="+objeto2+"&insmod="+$F('hidguardar_est')
		  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});	  
		}
			
	}

}


function ue_eliminar_estudio()
  {
  	  lb_valido = false; 
	  if ($('hidguardar_est').value !='modificar')
	  { alert ('Seleccione un estudio del catalogo para eliminar.');
		lb_valido= false;
	  }
	 else
	 {  
		lb_valido= true;
	 } 
		
	 if(lb_valido)
    {
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		alert(respuesta.responseText); 
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= "";
		ue_limpiar_estudios();
		ue_nuevo_codestudio();
	
	  }		  
	  
	  params = "operacion=ue_eliminar_estudio&codest="+$F('txtcodestper')+"&codper="+$F('txtcodper')+"&codcon="+$F('txtcodcon');
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onEliminar});	  
   }
   else   
    {
	  
	  alert("Eliminación Cancelada !!!");	  
	}
 
 }
	
}


function ue_buscar_estudios () 
{
	if ($('txtcodper').value=='') 
	{ alert ('Debe seleccionar un registro de concursante del catalogo');		}
	else 
	{ 
	  codper= $('txtcodper').value;
	  codcon= $('txtcodcon').value;
	  window.open("../catalogos/sigesp_srh_cat_estudios_concursante.php?codper="+codper+"&codcon="+codcon,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// FEDUCACIÓN INFORMAL ////


function ue_nuevo_codcurso()
{ 

 function onNuevo(respuesta)
  {	   
   $('txtcodcur').value  = trim(respuesta.responseText);
   $('txtdescur').focus();
  }
  var codper = $F('txtcodper');
  var codcon = $F('txtcodcon');

 params = "operacion=ue_nuevo_curso&codper="+codper+"&codcon="+codcon;
 new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});

}


function ue_limpiar_cursos()
{
   
   $('txtcodcur').value="";
   $('txtdescur').value="";
   $('cmbhorcur').value="null";
   $('hidguardar_cur').value="insertar";	   
   scrollTo(0,0);
}
		

function ue_guardar_cursos()
{
  lb_valido = false; 
  if (($('txtcodper').value =='')||($('txtcodcon').value ==''))
  { alert ('La información básica no puede estar vacía. Seleccione un registro de concursante del catalogo.');
    lb_valido= false;
  }
  else
  {  
  	lb_valido= true;
  }
	
  if(lb_valido)
  { 
		
		la_objetos =new Array ("txtcodcur", "txtdescur", "cmbhorcur");
		la_mensajes=new Array ("el Codigo del Curso","el Nombre del Curso","las Horas del Curso");
		
		lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
	
		if (lb_valido)
		{
	
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img;
		  function onGuardar(respuesta)
		  {
			 alert(respuesta.responseText); 
			 divResultado = document.getElementById('mostrar');
			 divResultado.innerHTML= "";
			 if (trim(respuesta.responseText)!="Los Datos Basicos deben estar registrados para poder realizar esta operacion")
			 {
			 	 ue_limpiar_cursos();
				 ue_nuevo_codcurso();
			 }
			 			 
		  }		  
				  
		  var cursos =
		  {
			  "codper"   	: $F('txtcodper'),
			  "codcon"   	: $F('txtcodcon'),
			  "codcur"		: $F('txtcodcur'),
			  "descur"		: $F('txtdescur'),
			  "horcur"	    : $F('cmbhorcur')
		  }
		
		  var objeto2 = JSON.stringify(cursos);
		  $aux = "insertar";	
		  params = "operacion=ue_guardar_cursos&objeto2="+objeto2+"&insmod="+$F('hidguardar_cur')
		  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});	  
		}
			
	}

}


function ue_eliminar_cursos()
  {
  	  lb_valido = false; 
	  if ($('hidguardar_cur').value !='modificar')
	  { alert ('Seleccione un curso del catalogo para eliminar.');
		lb_valido= false;
	  }
	 else
	 {  
		lb_valido= true;
	 } 
		
	 if(lb_valido)
    {
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		alert(respuesta.responseText); 
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= "";
		ue_limpiar_cursos();
		ue_nuevo_codcurso();
	
	  }		  
	  
	  params = "operacion=ue_eliminar_cursos&codcur="+$F('txtcodcur')+"&codper="+$F('txtcodper')+"&codcon="+$F('txtcodcon');
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onEliminar});	  
   }
   else   
    {
	  
	  alert("Eliminación Cancelada !!!");	  
	}
 
 }
	
}


function ue_buscar_cursos () 
{
	if ($('txtcodper').value=='') 
	{ alert ('Debe seleccionar un registro de concursante del catalogo');		}
	else 
	{ 
	  codper= $('txtcodper').value;
	  codcon= $('txtcodcon').value;
	  window.open("../catalogos/sigesp_srh_cat_cursos_concursante.php?codper="+codper+"&codcon="+codcon,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}

}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// FEDUCACIÓN EXPERIENCIA LABORAL ////


function ue_nuevo_trabajo()
{ 

 function onNuevo(respuesta)
  {	   
   $('txtcodtraant').value  = trim(respuesta.responseText);
   $('txtemptraant').focus();
  }
  var codper = $F('txtcodper');
  var codcon = $F('txtcodcon');

 params = "operacion=ue_nuevo_trabajo&codper="+codper+"&codcon="+codcon;
 new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});

}


function ue_limpiar_trabajo()
{
   
   $('txtcodtraant').value="";
   $('txtemptraant').value="";
   $('txtultcartraant').value="";
   $('txtfecingtraant').value="";
   $('txtfecrettraant').value="";
   $('hidguardar_trab').value="insertar";	   
   scrollTo(0,0);
}
		

function ue_guardar_trabajos()
{
  lb_valido = false; 
  if (($('txtcodper').value =='')||($('txtcodcon').value ==''))
  { alert ('La información básica no puede estar vacía. Seleccione un registro de concursante del catalogo.');
    lb_valido= false;
  }
  else
  {  
  	lb_valido= true;
  }
	
  if(lb_valido)
  { 
		
		la_objetos =new Array ("txtcodtraant", "txtemptraant", "txtultcartraant","txtfecingtraant","txtfecrettraant");
		la_mensajes=new Array ("el Codigo de la experiencia","la Empresa","el Cargo", "la Fecha de Ingreso","la Fecha de Egreso");
		
		lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
		
		lb_valido= ue_comparar_fechas($('txtfecingtraant').value,$('txtfecrettraant').value);
		if (!lb_valido)
  		{
			 alert ('La fecha de egreso debe ser mayor a la fecha de ingreso');
	  		 $('txtfecrettraant').value="";
		}
	
		if (lb_valido)
		{
	
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img;
		  function onGuardar(respuesta)
		  {
			 alert(respuesta.responseText); 
			 divResultado = document.getElementById('mostrar');
			 divResultado.innerHTML= "";
			 if (trim(respuesta.responseText)!="Los Datos Basicos deben estar registrados para poder realizar esta operacion")
			 {
			 	ue_limpiar_trabajo();
			 	ue_nuevo_trabajo();
			 }
			 
			 
		  }		  
				  
		  var trabajos =
		  {
			  "codper"   	 	: $F('txtcodper'),
			  "codcon"   	 	: $F('txtcodcon'),
			  "codtraant"		: $F('txtcodtraant'),
			  "emptraant"		: $F('txtemptraant'),
			  "ultcartraant"	: $F('txtultcartraant'),
			  "fecingtraant"	: $F('txtfecingtraant'),
			  "fecrettraant"	: $F('txtfecrettraant')
		  }
		
		  var objeto2 = JSON.stringify(trabajos);
		  $aux = "insertar";	
		  params = "operacion=ue_guardar_trabajos&objeto2="+objeto2+"&insmod="+$F('hidguardar_trab')
		  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});	  
		}
			
	}

}


function ue_eliminar_trabajo()
  {
  	  lb_valido = false; 
	  if ($('hidguardar_trab').value !='modificar')
	  { alert ('Seleccione una experiencia del catalogo para eliminar.');
		lb_valido= false;
	  }
	 else
	 {  
		lb_valido= true;
	 } 
		
	 if(lb_valido)
    {
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		alert(respuesta.responseText); 
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= "";
		ue_limpiar_trabajo();
		ue_nuevo_trabajo();
	
	  }		  
	  
	  params = "operacion=ue_eliminar_trabajo&codtrab="+$F('txtcodtraant')+"&codper="+$F('txtcodper')+"&codcon="+$F('txtcodcon');
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onEliminar});	  
   }
   else   
    {
	  
	  alert("Eliminación Cancelada !!!");	  
	}
 
 }
	
}


function ue_buscar_trabajos () 
{
	if ($('txtcodper').value=='') 
	{ alert ('Debe seleccionar un registro de concursante del catalogo');		}
	else 
	{ 
	  codper= $('txtcodper').value;
	  codcon= $('txtcodcon').value;
	  window.open("../catalogos/sigesp_srh_cat_trabajos_concursante.php?codper="+codper+"&codcon="+codcon,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// CARGA FAMILIAR ////


function ue_nuevo_familiar()
{ 

 function onNuevo(respuesta)
  {	   
   $('txtcodfam').value  = trim(respuesta.responseText);
   $('txtnomfam').focus();
  }
  var codper = $F('txtcodper');
  var codcon = $F('txtcodcon');

 params = "operacion=ue_nuevo_familiar&codper="+codper+"&codcon="+codcon;
 new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});

}


function ue_limpiar_familiar()
{
   
   $('txtcodfam').value="";
   $('txtcedfam').value="";
   $('txtnomfam').value="";
   $('txtapefam').value="";
   $('cmbsexfam').value="null";
   $('txtfecnacperfam').value="";
   $('cmbnexfam').value="null";  
   $('hidguardar_trab').value="insertar";	   
   scrollTo(0,0);
}
		

function ue_guardar_familiares()
{
  lb_valido = false; 
  if (($('txtcodper').value =='')||($('txtcodcon').value ==''))
  { alert ('La información básica no puede estar vacía. Seleccione un registro de concursante del catalogo.');
    lb_valido= false;
  }
  else
  {  
  	lb_valido= true;
  }
	
  if(lb_valido)
  { 
		
		la_objetos =new Array ("txtcodfam", "txtnomfam", "txtapefam","cmbsexfam","txtfecnacperfam","cmbnexfam");
		la_mensajes=new Array ("el Codigo del Familiar","el Nombre del Familiar","el Apellido del Familiar", "la Género del Familiar","la Fecha de Nacimiento del Familiar", "el Parentesco del Familiar");
		
		lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
	
		if (lb_valido)
		{
	
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img;
		  function onGuardar(respuesta)
		  {
			 alert(respuesta.responseText); 
			 divResultado = document.getElementById('mostrar');
			 divResultado.innerHTML= "";
			 if (trim(respuesta.responseText)!="Los Datos Basicos deben estar registrados para poder realizar esta operacion")
			 {
			 	ue_limpiar_familiar();
				 ue_nuevo_familiar();
			 }
			 
		  }		  
				  
		  var familiares =
		  {
			  "codper"   	: $F('txtcodper'),
			  "codcon"   	: $F('txtcodcon'),
			  "codfam"		: $F('txtcodfam'),
			  "nomfam"		: $F('txtnomfam'),
			  "cedfam"		: $F('txtcedfam'),
			  "apefam"		: $F('txtapefam'),
			  "sexfam"		: $F('cmbsexfam'),
			  "fecnacfam"	: $F('txtfecnacperfam'),
			  "nexfam"		: $F('cmbnexfam')
		  }
		
		  var objeto2 = JSON.stringify(familiares);
		  $aux = "insertar";	
		  params = "operacion=ue_guardar_familiar&objeto2="+objeto2+"&insmod="+$F('hidguardar_fam')
		  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});	  
		}
			
	}

}


function ue_eliminar_familiar()
  {
  	  lb_valido = false; 
	  if ($('hidguardar_fam').value !='modificar')
	  { alert ('Seleccione un familiar del catalogo para eliminar.');
		lb_valido= false;
	  }
	 else
	 {  
		lb_valido= true;
	 } 
		
	 if(lb_valido)
    {
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		alert(respuesta.responseText); 
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= "";
		ue_limpiar_familiar();
		ue_nuevo_familiar();
	
	  }		  
	  
	  params = "operacion=ue_eliminar_familiar&codfam="+$F('txtcodfam')+"&codper="+$F('txtcodper')+"&codcon="+$F('txtcodcon');
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onEliminar});	  
   }
   else   
    {
	  
	  alert("Eliminación Cancelada !!!");	  
	}
 
 }
	
}


function ue_buscar_familiares () 
{
	if ($('txtcodper').value=='') 
	{ alert ('Debe seleccionar un registro de concursante del catalogo');		}
	else 
	{ 
	  codper= $('txtcodper').value;
	  codcon= $('txtcodcon').value;
	  window.open("../catalogos/sigesp_srh_cat_familiares_concursante.php?codper="+codper+"&codcon="+codcon,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}

}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function ue_buscar_requisitos()
{
	
	 codper= $('txtcodper').value;
	 codcon= $('txtcodcon').value;
	 operacion = "CONSULTAR";
	 
	 if ((codcon=="") || (codper==""))
	 {
		 alert ('El código del concurso y el código del concursante no pueden estar vacíos. Guarde primero los datos básicos');
	 }
	 else
	 {
	 	window.open("sigesp_srh_p_requitos_concursante.php?codper="+codper+"&codcon="+codcon+"&operacion="+operacion,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=570,left=50,top=50,location=no,resizable=yes");
	 }
}

function ue_guardar_requisitos()
{
	
	 codper= $('txtcodper').value;
	 codcon= $('txtcodcon').value;
	 operacion = "CARGAR";
	 
	  if ((codcon=="") || (codper==""))
	 {
		 alert ('El código del concurso y el código del concursante no pueden estar vacíos. Guarde primero los datos básicos');
	 }
	 else
	 {
	 												
	 	window.open("sigesp_srh_p_requitos_concursante.php?codper="+codper+"&codcon="+codcon+"&operacion="+operacion,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=570,left=50,top=50,location=no,resizable=yes");
	 }
}



function ue_guardar_requisitos_concurso()
{


 	 divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
		   alert(respuesta.responseText);   
		   divResultado = document.getElementById('mostrar');
		   divResultado.innerHTML= '';
			
	  }
	
	  //Arreglo 
	  var detalle = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	 g=2;
	 for (f=1; f<(filas.length - 1); f++)
	  {
		
		 codreqcon = eval ('document.form1.txtcodreqcon'+f);	
		 entreqcon = eval ('document.form1.cmbentreq'+f);	
		 canentreq= eval  ('document.form1.txtcanentreq'+f);	
		var reg = 
		{
		  "codcon"           : $F('txtcodcon'),
		  "codper"           : $F('txtcodper'),	  
		  "codreqcon"  		 : codreqcon.value,
		  "entreqcon"  		 : entreqcon.value,
   	      "canentreq"        : canentreq.value
		}
		g++;
		detalle[f-1] = reg;
	  }
	 	  
	  var reqcon = 
	  {
		"codcon"     : $F('txtcodcon'),
		"codper"     : $F('txtcodper'),	
 		"detalle"	 : detalle
	  };
	
	
	  var objeto = JSON.stringify(reqcon);
	  params = "operacion=ue_guardar_requisitos&objeto="+objeto;
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});

}
  
function ue_valida_catidad (num)
{
	req=eval("document.form1.txtcanreqcon"+num);
	canreq=req.value;
	
	reqent=eval("document.form1.txtcanentreq"+num);
	canreqent=reqent.value;	
	
	if (parseInt(canreqent)>parseInt(canreq))
	{
		alert ('La Cantidad Entregada del Requisito no debe ser mayor a la cantidad Solicitada');
		reqent.value="";
	}
}


function dia_actual()
{
	
var mydate=new Date();
var year=mydate.getYear();
if (year < 1000)
year+=1900;
var day=mydate.getDay();
var month=mydate.getMonth()+1;
if (month<10)
month="0"+month;
var daym=mydate.getDate();
if (daym<10)
daym="0"+daym;
dia=daym+"/"+month+"/"+year;

return dia;
}

function ue_imprimir (ls_reporte)
{
	f=document.form1;    
	li_imprimir=f.imprimir.value;
	codper=f.txtcodper.value;
	codcon=f.txtcodcon.value;
	descon=f.txtdescon.value;
	codcar=f.txtcodcar.value;
	if(li_imprimir==1)
	{	
		
		 pagina="../../../reporte/"+ls_reporte+"?codper="+codper+"&codcon="+codcon+"&descon="+descon+"&codcar="+codcar;
		 window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");	
				  
	 } // fin del if imprimir
   else		
	{
	 alert("No tiene permiso para realizar esta operación");
	 }
	
}