// JavaScript Document

var url= "../../php/sigesp_srh_a_defcontrato.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";




function ue_nuevo_codigo()
{
  function onNuevo(respuesta)
  {
	if ($('txtcodcont').value=="") {
	
	$('txtcodcont').value  = trim(respuesta.responseText);
	$('txtdescont').focus();
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


function ue_nuevo()
{
  $('hidguardar').value = "";
  $('txtcodcont').value="";
  $('txtdescont').value="";
  $('txttamletcont').value="";
  $('txttamletpiecont').value="";
  $('cmdintlincont').value="1";
  $('txtmarsupcont').value="";
  $('txtmarinfcont').value="";
  $('txttitcont').value="";
  $('txtnomrtf').value="";
  $('txtarcrtfcont').value="";   
  $('cmbcamper').value="";
  $('txtconcont').value="";
  $('txtpiepagcont').value="";
  divResultado=document.getElementById('mostrar');
  divResultado.innerHTML='';
  ue_nuevo_codigo();
}



function ue_guardar()
{
	lb_valido=true;
	var la_objetos=new Array ("txtcodcont","txtdescont", "txttamletcont", "txttamletpiecont"); 
	var la_mensajes=new Array ("el numero de registro", "la descripcion",   "el tamaño de letra", "el tamaño de letra del pir de página");
 	lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
   
	 if (lb_valido)
	 {  
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img;
		  function onGuardar(respuesta)
		  {
		   alert(respuesta.responseText);   
		   ue_cancelar();
		   }
		  var contrato = 
		  {
		 	"codcont"       :  $('txtcodcont').value,
			"descont"       :  $('txtdescont').value,
			"tamletcont"    :  $('txttamletcont').value,
			"tamletpiecont" :  $('txttamletpiecont').value,
			"intlincont"    :  $('cmdintlincont').value,
			"marsupcont"    :  $('txtmarsupcont').value,
			"marinfcont"    :  $('txtmarinfcont').value,
			"titcont"       :  $('txttitcont').value,
			"nomrtf"        :  $('txtnomrtf').value,
			"arcrtfcont"    :  $('txtarcrtfcont').value,
			"camper"        :  $('cmbcamper').value,
			"concont"       :  $('txtconcont').value,
			"piepagcont"    :  $('txtpiepagcont').value
		  };
		  var objeto = JSON.stringify(contrato);
		  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
		  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
	  }
}

function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodcont");
  var la_mensajes=new Array ("el código de la configuración de Contrato. Seleccione un Contrato del Catalago");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if ((lb_valido) && ($('hidguardar').value =='modificar'))
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
	  
	  params = "operacion=ue_eliminar&codcont="+$F('txtcodcont');
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
    alert ('Debe elegir un registro del Catálogo');
  }
}



function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}


function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("../catalogos/sigesp_srh_cat_defcontrato.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_ingresarcampo()
{
	f=document.form1;
	ls_campo=f.cmbcamper.value;
	ls_contenido=f.txtconcont.value;
	ls_contenido=ls_contenido+ls_campo; 
	f.txtconcont.value=ls_contenido;
}

function validar_cero (ide)
{
  if ($(ide).value=='0')
  {
    alert ('El tamaño de la letra debe ser un valor mayor a cero');
	$(ide).value="";
  }
}

function cambiar_nombre ()
{
  archivo= $('txtarcrtfcont').value;
  longitud = archivo.length;
  encontro=false;
  cont=0;
  pos=0;
  for (i=longitud; i>=0 && (!encontro); i--)
  {
	 cont=cont+1;
	if (archivo.charAt(i)=="\\")  
	{
	   	encontro=true;
		pos=i+1;
		
    }
	
  }
   nombre_archivo = archivo.substr(pos,cont);
   $('txtnomrtf').value =  nombre_archivo; 
}

