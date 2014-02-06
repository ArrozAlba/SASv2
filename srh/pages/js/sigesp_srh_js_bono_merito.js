// JavaScript Document

var url= "../../php/sigesp_srh_a_bono_merito.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


function ue_cancelar()
{
     ue_nuevo();
    scrollTo(0,0);
    $('txtcodper').focus();
}




function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodper", "txtfecha","txtcodtipper", "txtcodesc");
  
 
  var la_mensajes=new Array ("el código del personal","la fecha de la evaluación", "el tipo de personal bono merito", "la escala de bono por unidad tributaria");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  
  g=2;
  var filas = $('grid').getElementsByTagName("tr");
  var IdFila   = filas[g].getAttribute("id");
  var columnas = filas[g].getElementsByTagName("input");
  
   if (columnas[0].value=="")
  {   
      alert ('Debe agragar un detalle al bono por merito');
	  lb_valido=false;
  }
 
  
  if(lb_valido)
  {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
	     if (trim(respuesta.responseText)=="No puede registrar el Bono por Merito porque el personal ya tiene una evaluacion en el mes de la fecha seleccionada. Modifique la fecha o haga click en nuevo para registrar otra  Evaluacion.")
		 {
			 alert(respuesta.responseText); 
			 divResultado = document.getElementById('mostrar');
			 divResultado.innerHTML= "";
			 
		 }
		 else
		 {	
			 alert(respuesta.responseText); 
			 divResultado = document.getElementById('mostrar');
			 divResultado.innerHTML= "";
			 ue_cancelar();
		 }
	
	  }
	
	  //Arreglo 
	  var pun_bono = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	 g=2;
	 total=0;
	 for (f=1; f<(filas.length - 1); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
		if (columnas[3].value=="")
	  	{
		   puntos = 0;
		}
	    else
		{
			puntos = columnas[3].value;
	    }
		var req = 
		{
		  "codper"           : $F('txtcodper'),
		  "fecha"            : $F('txtfecha'),
		  "codpunt"   		 : columnas[0].value,
		  "puntos"           : puntos,
		  "obs"         	 : columnas[4].value
		  
		}
		total=total + parseFloat(puntos);
		g++;
		pun_bono[f-1] = req;
	  }
	  var bono = 
	  {
		"codper"     : $F('txtcodper'),
	    "fecha"   	 : $F('txtfecha'),
		"codtipper"  : $F('txtcodtipper'),
		"codpun"	 : $F('txtcodesc'),
		"total"      : total,
		"pun_bono"	 : pun_bono
		};
	
	
	  var objeto = JSON.stringify(bono);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  
  if ($(hidguardar).value=='modificar')
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
	  
	  params = "operacion=ue_eliminar&codper="+$F('txtcodper')+"&fecha="+$F('txtfecha');
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
	  alert ('Debe elegir una Registro del Catalogo');
  }
}



function ue_nuevo()
{
  f=document.form1;
  f.operacion.value="NUEVO";
  f.existe.value="FALSE";		
  f.action="sigesp_srh_p_bono_merito.php";
  f.submit(); 
}




function catalogo_personal()
{
  
  pagina="../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=13";
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}



function catalogo_tipo_personal()
{	
     f=document.form1;
	 pagina="../catalogos/sigesp_srh_cat_tipopersonal.php?valor_cat=0";
	 window.open(pagina,"catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
		
}




function catalogo_tabla_bono()
{	
     f=document.form1;
	 pagina="../catalogos/sigesp_srh_cat_tablapuntosbonomerito.php?valor_cat=0&tipo=2";
	 window.open(pagina,"catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
		
}


function catalogo_puntuacion_bono()
{
     
   pagina="../catalogos/sigesp_srh_cat_puntuacion_bono_merito.php?valor=0";
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}



function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_bono_merito.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}




function consultar_items () {
	
	 if  ( ($('txtcodtipper').value=="") ) 		
	 {
		 alert("Debe llenar el  Tipo de Personal.");	
		 
	 }
	 else {
	  f=document.form1;
	  f.operacion.value="CONSULTAR";
  	  f.action="../pantallas/sigesp_srh_p_bono_merito.php";
	  f.existe.value="TRUE";			
	  f.submit();	
	 }
	
	}


function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	if(li_total==li_row)
	{
		li_codcamnew=eval("f.txtcodpunt"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			li_codcam=eval("f.txtcodpunt"+li_i+".value");
			if((li_codcam==li_codcamnew)&&(li_i!=li_row))
			{
				alert("La puntuación ya fue agregada. Seleccione Otra.");
				lb_valido=true;
			}
		}
		
		ls_codper=ue_validarvacio(f.txtcodper.value);
		ls_fecha=ue_validarvacio(f.txtfecha.value);
				
		ls_codcam=eval("f.txtcodpunt"+li_row+".value");
		ls_codcam=ue_validarvacio(ls_codcam);
		ls_dencam=eval("f.txtnombpunt"+li_row+".value");
		ls_dencam=ue_validarvacio(ls_dencam);
		li_puntos=eval("f.txtpuntos"+li_row+".value");
		li_puntos=ue_validarvacio(li_puntos);
		ls_obs=eval("f.txtobs"+li_row+".value");
		ls_obs=ue_validarvacio(ls_obs);
		if((ls_codper=="")||(ls_fecha=="")||(ls_codcam=="")||(li_puntos=="")||(ls_obs==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_srh_p_bono_merito.php";
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
		li_codcam=eval("f.txtcodpunt"+li_row+".value");
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
				f.action="sigesp_srh_p_bono_merito.php";
				f.submit();
				
			}
		}
	}
}


function validar_escala (num)

{
	
	escala = eval("document.form1.txtvalini"+num+".value");
	posicion = escala.indexOf ("/");
	valor1 =  escala.substring(0,posicion-1);
	valor2 =  escala.substring(posicion+2, escala.length);
	
	valor1 = parseInt (valor1);
	valor2 = parseInt (valor2);
	
	puntos = eval("document.form1.txtpuntos"+num+".value");
	puntos = parseInt (puntos);
	
	if ((puntos < valor1 ) || (puntos > valor2 ))
	{
	  alert ('El puntaje debe ser en valor comprendido en la escala de la puntuacion');	
	  total = eval("document.form1.txttotal");
	  total.value = parseInt (total.value) - puntos;
	  punt = eval("document.form1.txtpuntos"+num);
	  punt.value = "";
	}
	
	
}


function ue_validarnumero2(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="-")||(texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9")||(texto=="-1")||(texto=="-2")||(texto=="-3")||(texto=="-4")||(texto=="-5")||(texto=="-6")||(texto=="-7")||(texto=="-8")||(texto=="-9"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}



function ue_suma (ide1)

{
	var filas = $('grid').getElementsByTagName("tr");
	g=2;
	total=0;
	for (f=1; f<(filas.length -1); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
		if (columnas[3].value == "") {
			total= parseInt(total) + 0;
		}
		else
		{
		 total= parseInt(total) + parseInt (columnas[3].value);
		}
		g++;
	  }
   $(ide1).value=total;
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
