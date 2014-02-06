// JavaScript Document

var url= "../../php/sigesp_srh_a_solicitud_empleo.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
 
 
Event.observe(window,'load',ue_inicializar,false);


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
				  $('cmbcodpai').options[$('cmbcodpai').options.length] = new Option(pais.despai[i],pais.codpai[i]);
				}
			} 
	    }	
	 
    }	//end function onInicializar

}


function LimpiarComboPais()
{
  $('cmbcodpai').value="";	
  $('cmbcodpai').selectedIndex = 0;
  LimpiarComboEstado();
}

function LimpiarComboEstado()
{
  removeAllOptions($('cmbcodest'));	
  $('cmbcodest').selectedIndex = 0;
  LimpiarComboMunicipio();
}



function LimpiarComboMunicipio()
{
  removeAllOptions($('cmbcodmun'));	
  $('cmbcodmun').selectedIndex = 0;
  LimpiarComboParroquia();
}


function LimpiarComboParroquia()
{
  removeAllOptions($('cmbcodpar'));
  $('cmbcodpar').selectedIndex = 0;
}



function ue_cambiopais()
{
  function onInicializar(respuesta)
  {
	if (trim(respuesta.responseText) != "")
	{
	  var estados = JSON.parse(respuesta.responseText);
	  for (i=0; i<estados.desest.length; i++)
	  {$('cmbcodest').options[$('cmbcodest').options.length] = new Option(estados.desest[i],estados.codest[i]);}
	}
  }	
  LimpiarComboEstado();
  params = "operacion=ue_inicializarestado&codpai="+$('cmbcodpai').value;
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
}


function ue_cambioestado()
{
  function onInicializar(respuesta)
  {
	if (trim(respuesta.responseText) != "")
	{
	  var municipios = JSON.parse(respuesta.responseText);
	  for (i=0; i<municipios.codmun.length; i++)
	  {$('cmbcodmun').options[$('cmbcodmun').options.length] = new Option(municipios.denmun[i],municipios.codmun[i]);}
	}
  }	
  LimpiarComboMunicipio();
  params = "operacion=ue_inicializarmunicipio&codpai="+$('cmbcodpai').value+"&codest="+$('cmbcodest').value;
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
}


function ue_cambiomunicipio()
{
  function onInicializar(respuesta)
  {
	if (trim(respuesta.responseText) != "")
	{
	  var parroquias = JSON.parse(respuesta.responseText);
	  for (i=0; i<parroquias.codpar.length; i++)
	  {$('cmbcodpar').options[$('cmbcodpar').options.length] = new Option(parroquias.denpar[i],parroquias.codpar[i]);}
	}
  }	
  LimpiarComboParroquia();
  params = "operacion=ue_inicializarparroquia&codpai="+$('cmbcodpai').value+"&codest="+$('cmbcodest').value+"&codmun="+$('cmbcodmun').value;
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
}


function ue_chequear_cedula()
{
	if ((ue_valida_null($('txtcedper'))) && ($('hidguardar').value!='modificar'))
    {
	
		function onChequearCedPersonal(respuesta)
		{	  
			  if (trim(respuesta.responseText) != "")
			  {
				  alert(respuesta.responseText);
				  Field.clear('txtcedper');//INICIALIZAR
				  Field.activate('txtcedper');//FOCUS
			  }
			  else
			  {
				//  Field.activate('txtnomper');
			  }
		}
		params = "operacion=ue_chequear_cedula&cedper="+$F('txtcedper');
		new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onChequearCedPersonal});	
	}
}



function ue_guardar()
{
  
  lb_valido=true;
  var la_objetos=new Array ("txtnrosol","txtfecsol", "txtcedper","txtnomper", "txtapeper","txtfecnacper", "cmbsexper",  "cmbedocivper" ,"txtcarfam", "txtcodpro", "txttelhabper", "txttelmovper", "cmbnacper", "cmbcodpai", "cmbcodest", "cmbcodmun",  "cmbcodpar", "txtpesper", "txtestaper", "txtdirper", "cmbnivacaper", "txtcomsol", "txtcodniv", "txtcomsol");
  
 
  var la_mensajes=new Array ("el numero de solicitud", "la fecha de solicitud","la cedula del solicitante",  "el nombre del solicitante", "el apellido del solicitante", "la fecha nacimiento del solicitante","el genero del solicitante ", "el estado civil del solicitante",  "la carga familiar del solicitante","la profesion del solicitante","el telefono de habitancion del solicitante",  "el telofono movil del solicitante", "la nacionalidad del solicitante", "el pais de nacimiento del solicitante", "el estado de nacimiento del solicitante", "el municipio de nacimiento del solicitante", "la parroquia de nacimiento del solicitante", "el peso del solicitante", "la estatura del solicitante", "la direccion del solicitante", "el nivel academico del solicitante","la competencia del solicitante", "el nivel de seleccion del solicitante","la competencia o perfil");
  
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
	  
			  divResultado = document.getElementById('mostrar'); //para mostrar imagen
			  divResultado.innerHTML= img; 
			  
			  function onGuardar(respuesta)
			  {
			   alert(respuesta.responseText);     
			   ue_cancelar();
			   
			  }
			
			 //Arreglo de Area
			/*  var area= new Array();
			  var filas = $('grid').getElementsByTagName("tr");
				 g=2;
				 for (f=1; f<(filas.length - 2); f++)
				  {
					var IdFila   = filas[g].getAttribute("id");
					var columnas = filas[g].getElementsByTagName("input");
					
					var are = 
					{
					  "nrosol"           : $F('txtnrosol'),
					  "codare"       	 : columnas[0].value,
					  "anoexp"       	 : columnas[2].value,
					  "obs"       		 : columnas[3].value
					  
					}
					
					g++;
					area[f-1] = are;
				  }
			*/
			
			  var solicitud = 
			  {
				"nrosol" : $F('txtnrosol'),
				"cedper" : $F('txtcedper'),
				"fecsol" : $F('txtfecsol'),
				"apeper" : $F('txtapeper'),
				"nomper" : $F('txtnomper'),
				"sexsol" : $F('cmbsexper'),
				"fecnacper" : $F('txtfecnacper'),
				"telhab" : $F('txttelhabper'),
				"email"  : $F('txtcoreleper'),
				"codpro" : $F('txtcodpro'), 
				"carfam" : $F('txtcarfam'),
				"codpai" : $F('cmbcodpai'),
				"codpar" : $F('cmbcodpar'),
				"codmun" : $F('cmbcodmun'),
				"codest" : $F('cmbcodest'),
				"dirper" : $F('txtdirper'),
				"comsol" : $F('txtcomsol'),
				"codniv" : $F('txtcodniv'),
				"telmov" : $F('txttelmovper'),
				"estciv" : $F('cmbedocivper'),
				"nacper" : $F('cmbnacper'),
				"nivaca" : $F('cmbnivacaper'),
				"estaper": $F('txtestaper'),
				"pesper" : $F('txtpesper')
				//"area_d" : area
				};
			
			
			  var objeto = JSON.stringify(solicitud);
			  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
			  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
			  
		}
	}
  }
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnrosol");
  var la_mensajes=new Array ("el numero de Solicitud. Seleccione una Solicitud del Catalago");
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
	  
	  params = "operacion=ue_eliminar&nrosol="+$F('txtnrosol');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	  ue_cancelar();
	  alert("Eliminación Cancelada !!!");	  
	}
  }
}



function ue_nuevo_codigo()
{
  function onNuevo(respuesta)
  {
	if ($('txtnrosol').value=="") {
	
	$('txtnrosol').value  = trim(respuesta.responseText);
	$('txtfecsol').focus();
	}
  }	

  params = "operacion=ue_nuevo_codigo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}

function ue_cancelar()
{
     ue_nuevo();
    scrollTo(0,0);
   
}

/* f=document.form1;
	f=document.form1;
	f.operacion.value="NUEVO";
	f.existe.value="FALSE";		
	f.action="sigesp_srh_p_solicitud_empleo.php";
	f.submit();*/


function ue_nuevo()
{
      $('hidguardar').value = "";
	  $('hidcodpar').value = "";
	  $('hidcodmun').value = "";
	  $('hidcodest').value = "";
	  $('txtnrosol').value="";
	  $('txtcedper').value="";
	  $('txtapeper').value="";
	  $('txtnomper').value="";
	  $('txtfecsol').value="";
	  $('txtfecnacper').value="";
	  $('txttelhabper').value="";
	  $('cmbnacper').value="";
	  $('txtcoreleper').value="";
	  $('txtcodpro').value="";
	  $('txtdespro').value="";
	  $('txtcarfam').value="";
	  $('cmbcodest').value="";
	  $('cmbcodmun').value="";
	  $('cmbcodpar').value="";
	  $('txtcomsol').value="";
	  $('txtdirper').value="";
	  $('txttelmovper').value="";
	  $('cmbedocivper').value="";
	  $('txtcodniv').value="";
	  $('txtdenniv').value="";
	  $('cmbnivacaper').value="";
	  $('cmbsexper').value="";
	  $('txtestaper').value="";
	  $('txtpesper').value="";
	  $('txtnrosol').readOnly=true;
	  $('txtcedper').readOnly=false;
      divResultado = document.getElementById('mostrar');
	  divResultado.innerHTML= '';
      LimpiarComboPais();
	  ue_nuevo_codigo();
}




function valida_cmbcodest () {

f= document.form1;
if (f.cmbcodpai.value =="") 
  {alert ('Debe seleccionar un Pais');   }

}

function valida_cmbcodmun () {

f= document.form1;
if (f.cmbcodest.value =="") 
  {alert ('Debe seleccionar un Estado');   }

}


function valida_cmbcodpar () {

f= document.form1;
if (f.cmbcodmun.value =="")
  { alert ('Debe seleccionar un Municipio');   }
 
}




function catalogo_nivel()
{
    
   pagina="../catalogos/sigesp_srh_cat_nivelseleccion.php?valor_cat=0";
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}


function catalogo_profesion()
{
      
   pagina="../catalogos/sigesp_srh_cat_profesion.php?valor_cat=0";
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}



function catalogo_area()
{
     
   pagina="../catalogos/sigesp_srh_cat_area.php?valor_cat=0";

  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_solicitud_empleo.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function control_combo ()

{
$(txtcontrol).value='1';	
}

function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	if(li_total==li_row)
	{
		ls_codcamnew=eval("f.txtcodare"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			ls_codcam=eval("f.txtcodare"+li_i+".value");
			if((ls_codcam==ls_codcamnew)&&(li_i!=li_row))
			{
				alert("El area de desempeño ya fue agregada.");
				lb_valido=true;
			}
		}
		ls_nrosol=ue_validarvacio(f.txtnrosol.value);
		
		ls_codare=eval("f.txtcodare"+li_row+".value");
		ls_codare=ue_validarvacio(ls_codare);
		ls_anoexp=eval("f.txtanoexp"+li_row+".value");
		ls_anoexp=ue_validarvacio(ls_anoexp);
		ls_obs=eval("f.txtobs"+li_row+".value");
		ls_obs=ue_validarvacio(ls_obs);
		if((ls_nrosol=="")||(ls_codare=="")||(ls_anoexp=="")||(ls_obs==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_srh_p_solicitud_empleo.php";
			f.submit();
		}
	}
}



function uf_delete_dt(li_row)
{
	f=document.form1;
	li_total=f.totalfilas.value;
	if(li_total>li_row)
	{
		li_codcam=eval("f.txtcodare"+li_row+".value");
		li_codcam=ue_validarvacio(li_codcam);
		if(li_codcam=="")
		{
			alert("la fila a eliminar no debe estar vacio el lapso");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_srh_p_solicitud_empleo.php";
				f.submit();
			}
		}
	}
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
	   
		
		
			



