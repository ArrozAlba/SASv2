// JavaScript Document

var url= "../../php/sigesp_srh_a_llamada_atencion.php";
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
	if ($('txtnrollam').value=="")
	{
	
	$('txtnrollam').value  = trim(respuesta.responseText);
	$('txtfecllam').focus();
	}
  }	

  params = "operacion=ue_nuevo_codigo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}




function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnrollam", "txtfecllam","txtcodper", "cmbcausa", "cmbtipo","txtdes" );
  
 
  var la_mensajes=new Array ("el numero de registro", "la fecha de registro",   "el codigo del trabajador",  "la causa del registro (Amonestación / Llamda de Atención)", "el tipo", "una breve descripcion");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  
  if ($('totalfilas').value == '1')
  {   
      alert ('Debe agragar una causa de llamada de atencion');
	  lb_valido=false;
  }
  
  if(lb_valido)
  {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
	   alert(respuesta.responseText);   
	   divResultado = document.getElementById('mostrar');
       divResultado.innerHTML= '';
	   ue_cancelar();
	   
	  }
	
	 //Arreglo de Causas
	  var causas = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	  g=2;
	  for (f=1; f<(filas.length - 2); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
		var cau_llam_aten = 
		{
		  "nrollam"            : $F('txtnrollam'),
		  "codcaullam_aten"    : columnas[0].value
		  
		}
		g=g+1;
		causas[f-1] = cau_llam_aten;
	  }

	
	
	  var llamada_atencion = 
	  {
		  
	    "nrollam"    : $F('txtnrollam'),
		"fecllam"    : $F('txtfecllam'),
		"codtrab"    : $F('txtcodper'),
		"causa"      : $F('cmbcausa'),
		"tipo"       : $F('cmbtipo'),
		"coduniad"   : $F('txtuniad'),
		"descripcion" : $F('txtdes'),
		"causas"      : causas
		};
	
	
	  var objeto = JSON.stringify(llamada_atencion);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnrollam");
  var la_mensajes=new Array ("el número de Registro. Seleccione un Registro del Catalago");
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
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= '';
	  }
	  
	  params = "operacion=ue_eliminar&nrollam="+$F('txtnrollam');
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
   alert ('Debe elegir un Registro del Catalogo');
  }
}



function ue_nuevo()
{   
  f=document.form1;
   f.operacion.value="NUEVO";
   f.existe.value="FALSE";		
   f.action="sigesp_srh_p_llamada_atencion.php";
   f.submit();
  
  
}


function catalogo_personal()
{
    f= document.form1;
    pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=9"; 
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}


function catalogo_causas(num)
{
     f= document.form1;
     pagina="../catalogos/sigesp_srh_cat_causa_llamada_atencion.php?valor_cat=0"; 
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}



function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_llamada_atencion.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=605,height=500,left=50,top=50,location=no,resizable=yes");
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





function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	if(li_total==li_row)
	{
		li_codcamnew=eval("f.txtcodcaullam_aten"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			li_codcam=eval("f.txtcodcaullam_aten"+li_i+".value");
			if((li_codcam==li_codcamnew)&&(li_i!=li_row))
			{
				alert("La Causa de llamada de Atención ya fue agregada. Seleccione Otra.");
				lb_valido=true;
			}
		}
		
		ls_nrollam=ue_validarvacio(f.txtnrollam.value);
		
		ls_fecllam=ue_validarvacio(f.txtfecllam.value);
		ls_uniad=ue_validarvacio(f.txtuniad.value);
		ls_codtrab=ue_validarvacio(f.txtcodper.value);
		
		ls_des=ue_validarvacio(f.txtdes.value);
		
		ls_codcam=eval("f.txtcodcaullam_aten"+li_row+".value");
		ls_codcam=ue_validarvacio(ls_codcam);
		ls_dencam=eval("f.txtdencaullam_aten"+li_row+".value");
		ls_dencam=ue_validarvacio(ls_dencam);
		
		if((ls_nrollam=="")||(ls_fecllam=="")||(ls_codtrab=="")||(ls_codcam=="")||(ls_dencam==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_srh_p_llamada_atencion.php";
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
		li_codcam=eval("f.txtcodcaullam_aten"+li_row+".value");
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
				f.action="sigesp_srh_p_llamada_atencion.php";
				f.submit();
			}
		}
	}
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
