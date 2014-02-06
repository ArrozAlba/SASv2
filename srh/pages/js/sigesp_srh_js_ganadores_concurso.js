// JavaScript Document

var url= "../../php/sigesp_srh_a_ganadores_concurso.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";



function ue_cancelar()
{
     ue_nuevo();
    scrollTo(0,0);
    
}


function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodcon", "txtfecha");
  
 
  var la_mensajes=new Array ("el codigo del concurso","la fecha de registro");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
	   alert(respuesta.responseText);   	  
	   ue_cancelar();
	   	
	  }
	
	  //Arreglo 
	  var obj = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	 g=2;
	 
	 for (f=1; f<(filas.length - 1); f++)
	  {	
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
		var registro = 
		{
		  "codcon"           : $F('txtcodcon'),
		  "fecha"            : $F('txtfecha'),
		  "codper"   		 : columnas[0].value,
		  "total"            : columnas[3].value,
		  "posicion"         : columnas[4].value
		  
		}
		g++;
		obj[f-1] = registro;
	  }
	  var ganadores = 
	  {
		"codcon"     : $F('txtcodcon'),
		"fecha"   	 : $F('txtfecha'),
		"ganadores"	 : obj
		};
	
	
	  var objeto = JSON.stringify(ganadores);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodcon");
  var la_mensajes=new Array ("el codigo de Concurso. Seleccione una Registrp del Catalago");
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
	  
	  params = "operacion=ue_eliminar&codcon="+$F('txtcodcon');
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
  f=document.form1;
  f.operacion.value="NUEVO";
  f.existe.value="FALSE";		
  f.action="sigesp_srh_p_ganadores_concurso.php";
  f.submit(); 
}


function Consultar()
{
     if   ($('txtcodcon').value=="") 		
	 {
		 alert("Debe llenar el Codigo de Concurso!");	
		 
	 }
	 else {
	  f=document.form1;
	  f.operacion.value="CONSULTAR";
  	  f.action="../pantallas/sigesp_srh_p_ganadores_concurso.php";
	  f.existe.value="TRUE";			
	  f.submit();	
	 }
}
	


function catalogo_concurso()
{
      f= document.form1;

     if(f.hidstatus.value=='C')
  {
   pagina="../catalogos/sigesp_srh_cat_concurso.php";
  
  }else
  {
   pagina="../catalogos/sigesp_srh_cat_concurso.php";
  } 
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}


function Limpiar_Datos()
{
  $('txtcodcon').value="";
  $('txtdescon').value="";
  
}



function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_ganadores_concurso.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=540,height=400,left=50,top=50,location=no,resizable=yes");
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

