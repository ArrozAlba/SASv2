// JavaScript Document

var url= "../../php/sigesp_srh_a_evaluacion_pasantia.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


function ue_cancelar()
{
     ue_nuevo();
    scrollTo(0,0);
    $('txtnropas').focus();
}


function ue_nuevo()
{
	f=document.form1;
	f=document.form1;
	f.operacion.value="NUEVO";
	f.existe.value="FALSE";		
	f.action="sigesp_srh_p_evaluacion_pasantias.php";
	f.submit();
  
}


function ue_guardar()
{
  lb_valido=true;
  lb_valido2=true;
 
  var la_objetos=new Array ("txtnropas", "txtfeceval", "combopas", "txtres","txtobs");
  var la_mensajes=new Array ("el numero de la pasantia", "la fecha de la evaluacion",  "el estado de la pasantia", "el resultado de la evaluacion", "la observacion");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
   
  lb_valido2 = ue_comparar_fechas($('txtfecini').value,$('txtfeceval').value)
  
  if (!lb_valido2)
  {
	  alert ('La fecha de evaluación de la pasantía debe ser posterior a la fecha de inicio');
	  $('txtfeceval').value="";
  }
  
  if ((lb_valido)&&(lb_valido2))
  { 
      divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
	   alert(respuesta.responseText);     
	   ue_cancelar();
	   	
	  }
	
	  //Arreglo con el detalle
	  var evaluacion = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	 g=2;
	 cod=0;
	 total=0;
	 for (f=1; f<(filas.length - 2); f++)
	  {
	    cod=cod + 1;
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("textarea");
		
		if (columnas[2].value=="")
		{
			puntos=0;
		}
		{
			puntos=columnas[2].value;
		}
		
		var eva = 
		{
		  "nropas"           : $F('txtnropas'),
		  "feceval"            : $F('txtfeceval'),
		  "codmeta"   		 : cod,
		  "metap"       	 : columnas[0].value,
  		  "obsmeta"       	 : columnas[1].value,
	      "puntos"       	 : puntos
		  
		}
		g++;
		evaluacion[f-1] = eva;
	  }
	  var evaluacion_p = 
	  {
		"nropas"     : $F('txtnropas'),
	    "feceval"    : $F('txtfeceval'),
		"res"		 : $F('txtres'), 
		"estado" 	 : $F('combopas'), 
		"obs"		 : $F('txtobs'),
		"metas" : evaluacion
		};
	
	
	  var objeto = JSON.stringify(evaluacion_p);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objtos=new Array ("txtnropas");
  var la_mensajes=new Array ("el número de la Pasantia. Seleccione una Pasantia del Catalago");
  lb_valido = valida_datos_llenos(la_objtos,la_mensajes);
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
	  
	  params = "operacion=ue_eliminar&nropas="+$F('txtnropas')+"&feceval="+$F('txtfeceval');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	  ue_cancelar();
	  alert("Eliminación Cancelada !!!");	  
	}
  }
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
		window.open("../catalogos/sigesp_srh_cat_evaluacion_pasantia.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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
		ls_codcamnew=eval("f.txtmetap"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			ls_codcam=eval("f.txtmetap"+li_i+".value");
			if((ls_codcam==ls_codcamnew)&&(li_i!=li_row))
			{
				alert("La Meta de Evaluacion ya fue agregada.");
				lb_valido=true;
			}
		}
		ls_nropas=ue_validarvacio(f.txtnropas.value);
		ls_feceval=ue_validarvacio(f.txtfeceval.value);
		li_res=ue_validarvacio(f.txtres.value);
		ls_res=ue_validarvacio(f.combopas.value);
		ls_obs=ue_validarvacio(f.txtobs.value);
		ls_metap=eval("f.txtmetap"+li_row+".value");
		ls_metap=ue_validarvacio(ls_metap);
		ls_obsm=eval("f.txtobsm"+li_row+".value");
		ls_obsm=ue_validarvacio(ls_obsm);
		li_valor=eval("f.txtvalor"+li_row+".value");
		li_valor=ue_validarvacio(li_valor);
		if((ls_nropas=="")||(ls_feceval=="")||(ls_metap=="")||(ls_obsm=="")||(li_valor=="")||(ls_obs=="")||(li_res==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_srh_p_evaluacion_pasantias.php";
			f.submit();
		}
	}
}



function uf_delete_dt(li_row, ide1, ide2)
{
	f=document.form1;
	li_total=f.totalfilas.value;
	if(li_total>li_row)
	{
		li_codcam=eval("f.txtmetap"+li_row+".value");
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
				f.action="sigesp_srh_p_evaluacion_pasantias.php";
				f.submit();
			}
		}
	}
}



function catalogo_pasantia()
{
     
   pagina="../catalogos/sigesp_srh_cat_pasantias.php?valor_cat=0";

  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}




function ue_suma (ide1)

{
	var filas = $('grid').getElementsByTagName("tr");
	g=2;
	total=0;
	for (f=1; f<(filas.length -1); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("textarea");
		if (columnas[2].value == "") {
			total= parseInt(total) + 0;
		}
		else
		{
		 total= parseInt(total) + parseInt (columnas[2].value);
		}
		g++;
	  }
   $(ide1).value=total;
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
   
