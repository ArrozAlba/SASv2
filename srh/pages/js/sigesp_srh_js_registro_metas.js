// JavaScript Document

var url= "../../php/sigesp_srh_a_registro_metas.php";
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


function ue_nuevo()
{
	f=document.form1;
	f=document.form1;
	f.operacion.value="NUEVO";
	f.existe.value="FALSE";		
	f.action="sigesp_srh_p_registro_metas.php";
	f.submit();
  
}


function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroreg", "txtfecreg", "txtcodper", "txtfecini", "txtfecfin","txtobs");
  var la_mensajes=new Array ("el numero del registro de meta", "la fecha del registro de metas",  "el codigo del personal", "la fecha de inicio de ejecución de la meta", "la fecha de finalización de ejecución de la meta", "la observacion");
  
  lb_val = valida_datos_llenos(la_objetos,la_mensajes);
  
  lb_valido2= ue_comparar_fechas($('txtfecini').value,$('txtfecfin').value,$('txtfecfin'));  
	
	if (lb_valido2)
	{
	 lb_valido = true;
	}
	else
	{
	 alert ('La Fecha de Final del Periodo de Evaluacion debe ser mayor a la Fecha Inicial');
	 $('txtfecfin').value=""; 
	 lb_valido=false;
    }	

  if ($('totalfilas').value == '1')
  {   
      alert ('Debe agragar una meta al personal');
	  lb_valido=false;
  }
  
  
  if ((lb_valido)&&(lb_val))
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
	
	  //Arreglo con el detalle
	  var registro = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	 g=2;
	
	 for (f=1; f<(filas.length - 2); f++)
	  {
	 
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("textarea");
		
		var reg = 
		{
		  "nroreg"           : $F('txtnroreg'),
		  "codmeta"   		 : columnas[0].value,
		  "meta"       	     : columnas[1].value,
  		  "estado"       	 : columnas[2].value
	       
		}
		
		g++;
		registro[f-1] = reg;
	  }
	  var metas = 
	  {
		"nroreg"     : $F('txtnroreg'),
	    "fecreg"     : $F('txtfecreg'),
		"codper"	 : $F('txtcodper'),
		"fecini" 	 : $F('txtfecini'),
		"fecfin" 	 : $F('txtfecfin'), 
		"obs"		 : $F('txtobs'),
		"meta" 		 : registro
		};
	
	
	  var objeto = JSON.stringify(metas);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objtos=new Array ("txtnroreg");
  var la_mensajes=new Array ("el número de registro. Seleccione un registro de meta de personal del Catalago");
  lb_valido = valida_datos_llenos(la_objtos,la_mensajes);
  if ((lb_valido)&& ($('hidguardar').value=='modificar'))
  {
	divResultado = document.getElementById('mostrar');
    divResultado.innerHTML= img;
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{
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

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_tablavacaciones.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}


function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("../catalogos/sigesp_srh_cat_registro_metas.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	if(li_total==li_row)
	{
		ls_codcamnew=eval("f.txtcodmet"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			ls_codcam=eval("f.txtcodmet"+li_i+".value");
			if((ls_codcam==ls_codcamnew)&&(li_i!=li_row))
			{
				alert("La Meta Personal ya fue agregada.");
				lb_valido=true;
			}
		}
		ls_nroreg=ue_validarvacio(f.txtnroreg.value);
		ls_fecreg=ue_validarvacio(f.txtfecreg.value);
		ls_codper=ue_validarvacio(f.txtcodper.value);
		ls_obs=ue_validarvacio(f.txtobs.value);
		ls_codmet=eval("f.txtcodmet"+li_row+".value");
		ls_codmet=ue_validarvacio(ls_codmet);
		ls_meta=eval("f.txtmeta"+li_row+".value");
		ls_meta=ue_validarvacio(ls_meta);
		ls_estado=eval("f.txtestmet"+li_row+".value");
		ls_estado=ue_validarvacio(ls_estado);
		if((ls_nroreg=="")||(ls_fecreg=="")||(ls_codmet=="")||(ls_meta=="")||(ls_estado=="")||(ls_obs=="")||(ls_codper==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_srh_p_registro_metas.php";
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
		li_codcam=eval("f.txtcodmet"+li_row+".value");
		li_codcam=ue_validarvacio(li_codcam);
		if(li_codcam=="")
		{
			alert("la fila a eliminar no debe estar vacio el codigo");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_srh_p_registro_metas.php";
				f.submit();
			}
		}
	}
}



function catalogo_personal()
{
    
   pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=9";
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
