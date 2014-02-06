// JavaScript Document

var url="../../php/sigesp_srh_a_solicitud_adiestramiento.php";
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
	$('txtfecsol').focus();
	}
  }	

  params = "operacion=ue_nuevo_codigo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}



function ue_guardar()
{
  lb_valido=true;
  lb_valido=true;
  var la_objetos=new Array ("txtnroreg","txtfecsol", "txtdes", "txtcodper", "txtcodunivi", "txtcodprov", "txtfecini", "txtfecfin", "txtdurhras","txtcosto", "txtobs", "txtobj", "txtare", "txtest");
  
 
  var la_mensajes=new Array ("el número de registro","la fecha de la solicitud", "la descripción de la solicitud", "el código del solicitante", "el código de la unidad administrativa", "el código del proveedor", "la fecha de inicio del adiestramiento", "la fecha de culminación del adiestramiento", "la duración en horas del adiestramiento","el costo del adiestramiento" ,"la observacion", "el objetivo del adiestramiento", "el area o contenido a ser atendido", "la estrategia de capacitación");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  
  lb_valido2 = ue_comparar_fechas($('txtfecini').value,$('txtfecfin').value);
  
  if (!lb_valido2)
  {
	 alert ('La fecha final del periodo de adiestramiento debe ser mayor a la inicial');
	 $('txtfecfin').value="";
	 lb_valido=false;
  }
  
  if ((lb_valido)&&(lb_valido2))
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
	 for (f=1; f<(filas.length - 2); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
		var registro = 
		{
		  "nroreg"      : $F('txtnroreg'),
		  "codper" 		: columnas[0].value,
		  "carper"      : columnas[2].value,
		  "depto"   	: columnas[3].value
		}
		g++;
		personal[f-1] = registro;
	  }
	  var solicitud = 
	  {
		"nroreg"     : $F('txtnroreg'),
		"fecsol"     : $F('txtfecsol'),
	    "descrip"  	 : $F('txtdes'),
		"codsol"     : $F('txtcodper'),
		"uniad"      : $F('txtcodunivi'),
	    "prov"   	 : $F('txtcodprov'),
		"fecini"     : $F('txtfecini'),
		"fecfin"     : $F('txtfecfin'),
	    "durhras"  	 : $F('txtdurhras'),
		"costo"      : $F('txtcosto'),
	    "obs" 	 	 : $F('txtobs'),
		"est" 	 	 : $F('txtest'),
		"are" 	 	 : $F('txtare'),
		"obj" 	 	 : $F('txtobj'),
		"personal"	 : personal
		};
	
	
	  var objeto = JSON.stringify(solicitud);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroreg");
  var la_mensajes=new Array ("el número de registro. Seleccione una Solicitud de Adiestramiento del Catalago");
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
	  alert ('Debe elegir una Solicitud de Adiestramiento del Catalogo');
  }
}



function ue_nuevo()
{
  f=document.form1;
  f.operacion.value="NUEVO";
  f.existe.value="FALSE";		
  f.action="sigesp_srh_p_solicitud_adiestramiento.php";
  f.submit(); 
}




function catalogo_personal()
{
  pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=5";

  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}

function catalogo_solicitante()
{
   pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=8";
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}



function catalogo_unidad()
{
    
   pagina="../catalogos/sigesp_srh_cat_uni_vipladin.php?valor=0";

  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}


function catalogo_proveedor()
{
   pagina="../catalogos/sigesp_srh_cat_proveedores.php";
  window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}



function ue_buscar()
{
	f=document.form1;
	window.open("../catalogos/sigesp_srh_cat_solicitud_adiestramiento.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=680,height=500,left=50,top=50,location=no,resizable=yes");
}




function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	if(li_total==li_row)
	{
		li_codcamnew=eval("f.txtcodper"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			li_codcam=eval("f.txtcodper"+li_i+".value");
			if((li_codcam==li_codcamnew)&&(li_i!=li_row))
			{
				alert("El personal ya fue agregado. Seleccione Otro.");
				lb_valido=true;
			}
		}
		
		ls_nroreg=ue_validarvacio(f.txtnroreg.value);
				
		ls_codcam=eval("f.txtcodper"+li_row+".value");
		ls_codcam=ue_validarvacio(ls_codcam);
		ls_dencam=eval("f.txtnomper"+li_row+".value");
		ls_dencam=ue_validarvacio(ls_dencam);
		ls_carper=eval("f.txtcarper"+li_row+".value");
		ls_carper=ue_validarvacio(ls_carper);
		ls_dep=eval("f.txtdep"+li_row+".value");
		ls_dep=ue_validarvacio(ls_dep);
		if((ls_nroreg=="")||(ls_codcam=="")||(ls_carper=="")||(ls_dep==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_srh_p_solicitud_adiestramiento.php";
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
		li_codcam=eval("f.txtcodper"+li_row+".value");
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
				f.action="sigesp_srh_p_solicitud_adiestramiento.php";
				f.submit();
			}
		}
	}
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
   
