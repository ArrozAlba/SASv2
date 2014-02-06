// JavaScript Document

var url= "../../php/sigesp_srh_a_odi.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";



function ue_cancelar()
{
     ue_nuevo();
    scrollTo(0,0);
    $('txtcodper').focus();
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
  lb_correcto=false;
  var la_objetos=new Array ("txtnroreg","txtcodper", "txtcodeva", "txtfecini1", "txtfecfin1", "txtfecini2", "txtfecfin2", "txtobj", "txtfecha", "txttotal");
  
 
  var la_mensajes=new Array ("el número de registro","el código del personal","el codigo del evaluador", "la fecha de inicio del primer periodo de revision","la fecha final del primer periodo de revision","la fecha de inicio del segundo periodo de revision","la fecha final del segundo periodo de revision", "el objetivo funcional de la unidad","la fecha del registro", "el peso total de los objetivos individuales de desempeño");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  
  
  if (($F('txtcodper') == $F('txtcodeva')) && ($F('txtcodper')!=""))
  {  
     alert("El Codigo del Personal y el Codigo del Evaluador deben ser diferentes.");
	 lb_valido= false;
  }
  else if (($('txttotal').value>50))
   {
		alert ('El peso total de los Objetivos Individuales de Desempeño debe ser igual a 50. No puede serguir agregando objetivos. Modifique los pesos para seguir agregando objetivos.');
		lb_valido= false;
  }
  
  
  if (lb_valido)
  {
      lb_correcto=true;
	  lb_valido2= ue_comparar_fechas($('txtfecini1').value,$('txtfecfin1').value,$('txtfecfin1'));  
			
		if (lb_valido2)
		{
		 lb_valido3= ue_comparar_fechas($('txtfecini2').value,$('txtfecfin2').value,$('txtfecfin2'));  
			
			if (lb_valido3)
			{
				if (parseInt ($('totalfilas').value) < 4) {
				 alert("Debe llenar al menor tres (3) objetivos de desempeño individual");
				 lb_correcto = false;
				  }
				  
			   else  if ((parseInt ($('txttotal').value) < 50) || (parseInt ($('txttotal').value) > 50))
				 {  
				 alert("El peso total de los Objetivos Individuales de Desempeño debe ser igual a 50");
				 lb_correcto = false;
				  }
			}
			else
			{
			 alert ('La Fecha de Final del Periodo de Revision debe ser mayor a la Fecha Inicial');
			 $('txtfecfin2').value=""; 
			 lb_correcto = false;
			}
		}
		else
		{
		 alert ('La Fecha de Final del Periodo de Revision debe ser mayor a la Fecha Inicial');
		 $('txtfecfin1').value=""; 
		 lb_correcto = false;
	  }	
  }	
 
  if(lb_correcto)
  {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
	   alert(respuesta.responseText);   
	   ue_cancelar();
	   divResultado = document.getElementById('mostrar');
       divResultado.innerHTML= '';
	   
	  }
	
	  //Arreglo 
	  var obj = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	 g=2;
	 total=0;
	 for (f=1; f<(filas.length - 2); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("textarea");
		cododi = eval ('document.form1.txtcododi'+f);
		var odi = 
		{
		  "nroreg"           : $F('txtnroreg'),
		  "cododi"           : cododi.value,
		  "odi"   			 : columnas[0].value,
		  "valor"            : columnas[1].value
		  
		}
		g++;
		obj[f-1] = odi;
	  }
	  var requisitos = 
	  {
	    "nroreg"     : $F('txtnroreg'),
		"codper"     : $F('txtcodper'),
		"codeva"     : $F('txtcodeva'),
		"obj"     	 : $F('txtobj'),
	    "fecha"   	 : $F('txtfecha'),
		"fecini1"    : $F('txtfecini1'),
		"fecfin1"    : $F('txtfecfin1'),
		"fecini2"    : $F('txtfecini2'),
		"fecfin2"    : $F('txtfecfin2'),
		"total"      : $F('txttotal'),
		"odi"	 	 : obj
		};
	
	
	  var objeto = JSON.stringify(requisitos);
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
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		ue_cancelar();
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= '';
		alert(respuesta.responseText);
		
	  }
	  
	  params = "operacion=ue_eliminar&nroreg="+$F('txtnroreg');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	  ue_cancelar();
	  alert("Eliminación Cancelada");	  
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
  f.action="sigesp_srh_p_odi.php";
  f.submit(); 
}




function catalogo_personal()
{
    f= document.form1;
    pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=9";

  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}


 
  function catalogo_evaluador()
{
  f= document.form1;
  pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=3";
   
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
  }



function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_odi.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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
	if (f.totalfilas.value=='6')
	{ 
		alert("El máximo de objetivos de desempeño individual es 5");
		
	}	
	else
	{ 
		if(li_total==li_row)
		{
			li_codcamnew=eval("f.txtodi"+li_row+".value");
			li_total=f.totalfilas.value;
			lb_valido=false;
			for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
			{
				li_codcam=eval("f.txtodi"+li_i+".value");
				if((li_codcam==li_codcamnew)&&(li_i!=li_row))
				{
					alert("El ODI ya fue agregado.");
					lb_valido=true;
				}
			}
			
			ls_codeva=ue_validarvacio(f.txtcodeva.value);
			ls_codper=ue_validarvacio(f.txtcodper.value);
			ls_nroreg=ue_validarvacio(f.txtnroreg.value);
		
			ls_fecha=ue_validarvacio(f.txtfecha.value);
					
			ls_codcam=eval("f.txtodi"+li_row+".value");
			ls_codcam=ue_validarvacio(ls_codcam);
			li_valor=eval("f.txtvalor"+li_row+".value");
			li_valor=ls_codcam=ue_validarvacio(li_valor);
			if((ls_nroreg=="")||(ls_codper=="")||(ls_codeva=="")||(ls_fecha=="")||(ls_codcam=="")||(li_valor==""))
			{
				alert("Debe llenar todos los campos");
				lb_valido=true;
			}
			
			if(!lb_valido)
			{
				f.operacion.value="AGREGARDETALLE";
				f.action="sigesp_srh_p_odi.php";
				f.submit();
		}
		
	}
 }

}

function uf_delete_dt(li_row, ide1, ide2)
{
	f=document.form1;
	li_total=f.totalfilas.value;
	if(li_total>li_row)
	{
		li_codcam=eval("f.txtodi"+li_row+".value");
		li_codcam=ue_validarvacio(li_codcam);
		if(li_codcam=="")
		{
			alert("la fila a eliminar no debe estar vacio el lapso");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				operador1 = parseInt ($(ide1).value);
				operador2 = parseInt ($(ide2).value);
				$(ide1).value=operador1 - operador2;
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_srh_p_odi.php";
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






function ue_sumar (ide1,ide2)
{

	f=document.form1;	
	li_total=f.totalfilas.value;
	if (f.totalfilas.value!='6')
	{ 
		
		var filas = $('grid').getElementsByTagName("tr");
		g=2;
		total=0;
		h=filas.length - 1;
		aux_colum= filas[h].getElementsByTagName("textarea");
		if (aux_colum[1].value == " ") {
				
			for (f=1; f<(filas.length -2); f++)
			  {
				
				var IdFila   = filas[g].getAttribute("id");
				var columnas = filas[g].getElementsByTagName("textarea");
				if  (columnas[1].value=="")
				{
					columnas[1].value=0;
				}
				 total= parseInt(total) + parseInt (columnas[1].value);
				 g++;
				}
				
				
			}
		else {
			for (f=1; f<(filas.length -1); f++)
			  {
				
				var IdFila   = filas[g].getAttribute("id");
				var columnas = filas[g].getElementsByTagName("textarea");
				if  (columnas[1].value=="")
				{
					columnas[1].value=0;
				}
				 total= parseInt(total) + parseInt (columnas[1].value);
				 g++;
				}
		 }
	   $(ide1).value=total;
	   if ($(ide1).value > 50) 
		{
			alert("El peso total de los Objetivos Individuales de Desempeño debe ser igual a 50");
			$(ide1).value="";
			$(ide2).value="";
			$(ide2).focus();
		}
		
	}
}



//FUNCIONES PARA EL CALENDARIO

// Esta es la funcion que detecta cuando el usuario hace click en el calendario, necesaria
function seleodid(cal, date) {
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

    var cal = new Calendar(1, null, seleodid, closeHandler);
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
   