// JavaScript Document

function objetoAjax()
{
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
		   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
  		}
	}

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}



var url= "../../php/sigesp_srh_a_concurso.php";
var metodo="POST";
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
var ajax=objetoAjax();



function ue_validaexiste()
{
  

	var divResultado= $('existe');
	var paran="txtcodcon="+$F('txtcodcon');
	if (($F('txtcodcon')!="") && ($F('hidstatus')!='C'))
	{
	   ajax.open(metodo,url+"?valor=existe",true);
	   ajax.onreadystatechange=function() 
	   {
		  if (ajax.readyState==4)
		  {
				if(ajax.status==200)
				{
					 divResultado.innerHTML = ajax.responseText
					 if(divResultado.innerHTML=='')
					 {
					 }
					 else
					 {
						  Field.clear('txtcodcon');
						  Field.activate('txtcodcon');
						  
						  alert(divResultado.innerHTML);
						 
					 }
				}
				else
				{
					 alert('ERROR '+ajax.status);
				}
		  }
	   }
	
      ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	  ajax.send(paran);
	}
}//fin ue_validaexiste()


function ue_cancelar()
{
  
  document.form1.hidstatus.value="";
  document.form1.txtdescon.value="";
  document.form1.txtfechaaper.value="";
  document.form1.txtfechacie.value="";
  document.form1.txtcodcar.value="";
  document.form1.txtcodnom.value="";
  document.form1.txtdescar.value="";
  document.form1.txtcantcar.value="";
  document.form1.comboestatus.value="null";
  document.form1.combotipo.value="null";
  document.form1.hidstatus.value="";
   scrollTo(0,0);
}

function ue_nuevo()
{
  function onNuevo(respuesta)
  {
    ue_cancelar();	
	$('txtcodcon').value  = trim(respuesta.responseText);
	$('txtdescon').focus();
  }	

  params = "operacion=ue_nuevo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
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
   


function ue_validavacio()
{
  lb_valido=true;
  f=document.form1;
  
if(f.txtcodcon.value=="")
  {
		alert('Falta Código del Concurso');
		lb_valido=false;
   }
   else if(f.txtdescon.value=="")
   {
	   alert('Falta Descripcion del Concurso');
	   lb_valido=false;
   }
   else if(f.txtfechaaper.value=="")
   {
	   alert('Falta Fecha de Apertura del Concurso')  ;
	   lb_valido=false;
   }
   else if(f.txtfechacie.value=="")
   {
	   alert('Falta Fecha de Cierre del Concurso')  ;
	   lb_valido=false;
   }
   else if(f.txtcodcar.value=="")
   {
	   alert('Falta Codigo del Cargo del Concurso')  ;
	  lb_valido=false;
   }
   else if(f.txtcantcar.value=="")
   {
	   alert('Falta Cantidad de Cargos del Concurso')  ;
	   lb_valido=false;
   }
   else if(f.combotipo.value=="null")
	 {
	   alert('Falta Tipo del Concurso')  ;
	   lb_valido=false;
   }
    else if(f.comboestatus.value=="null")
	 {
	   alert('Falta Estado del Concurso')  ;
	   lb_valido=false;
   }
   
   return lb_valido;
 

}


function ue_guardar_registro()
{
				  //donde se mostrará lo resultados
	        divResultado = document.getElementById('mostrar');
         	divResultado.innerHTML= img;


			  //valores de las cajas de texto
			  codcon=document.form1.txtcodcon.value;
			  descon=document.form1.txtdescon.value;
			  fechaaper=document.form1.txtfechaaper.value;
			  fechacie=document.form1.txtfechacie.value;
			  codcar=document.form1.txtcodcar.value;
			  cantcar=document.form1.txtcantcar.value;
			  estatus=document.form1.comboestatus.value;
			  tipo=document.form1.combotipo.value;
			  codnom=document.form1.txtcodnom.value;
		  
			 
			  //instanciamos el objetoAjax
			  ajax=objetoAjax();
			  //uso del medoto POST
			  //archivo que realizará la operacion
			  
			  ajax.open(metodo,url+"?valor=guardar",true);
			  ajax.onreadystatechange=function() 
			  {
				  if (ajax.readyState==4)
				  {
				  //mostrar resultados en esta capa
				  divResultado.innerHTML = ajax.responseText;
				   if(divResultado.innerHTML)
					{
					   if(ajax.status==200)
					   {
					   	alert(divResultado.innerHTML);
					   }
					   else
					   {
						alert(ajax.statusText);   
						}				  
				}
					
				  
				  }
				 ue_nuevo();
			  }
   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
   ajax.send("txtcodcon="+codcon+"&txtdescon="+descon+"&txtfechaaper="+fechaaper+"&txtfechacie="+fechacie+"&txtcodcar="+codcar+"&txtcantcar="+cantcar+"&comboestatus="+estatus+"&combotipo="+tipo+"&txtcodnom="+codnom);
			 
			
	
}

function ue_guardar()
{
	lb_valido=ue_validavacio();
	
	if(lb_valido)
	{
		lb_valido2= ue_comparar_fechas(document.form1.txtfechaaper.value,document.form1.txtfechacie.value,document.form1.txtfechacie);  
		
		if (lb_valido2)
		{
	  	  ue_guardar_registro();
		}
		else
		{
	     alert ('La Fecha de Cierre debe ser mayor a la fecha de Apertura');
	     document.form1.txtfechacie.value=""; 
	   }
     }//lb_valido
}



function ue_eliminar()
{
		if(document.form1.hidstatus.value=="C")
		{
			if (confirm("Esta seguro de eliminar este registro?"))
			{
		
			  //donde se mostrará lo resultados
  			 divResultado = document.getElementById('mostrar');
			divResultado.innerHTML=img;
			  codcon=document.form1.txtcodcon.value;			
			  
			 
			  //instanciamos el objetoAjax
			  ajax=objetoAjax();
			  //uso del medoto POST
			  //archivo que realizará la operacion
			  
			  
			  ajax.open(metodo,url+"?valor=eliminar",true);
			  ajax.onreadystatechange=function() 
			  {
				  if (ajax.readyState==4)
				  {
				  //mostrar resultados en esta capa
				  divResultado.innerHTML = ajax.responseText;
				  
				  
				   if(divResultado.innerHTML)
					{
					   if(ajax.status==200)
					   {
					   	alert (divResultado.innerHTML);
					   }
					   else
					   {
						alert(ajax.statusText);   
						}
					 } 
					
					
				  }
				 ue_nuevo();
			  }
   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
   ajax.send("txtcodcon="+codcon);
   }
		}
		else
	   {
		
		alert('Debe elegir un Concurso del Catalogo');
		
		}
}




function catalogo_cargo()
{
   pagina="../catalogos/sigesp_srh_cat_cargo.php?valor_cat=0";
   window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
   
}

function catalogo_cargo_rac()
{
   pagina="../catalogos/sigesp_srh_cat_cargo_rac.php?valor_cat=0&tipo=2";
   window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
   
}

function catalogo_tipo_concurso () {
	

      pagina="../catalogos/sigesp_srh_cat_tipoconcurso.php?valor_cat=0";
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
 
	
	}


function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		
		window.open("../catalogos/sigesp_srh_cat_concurso.php?valor_cat=1&tipo=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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
