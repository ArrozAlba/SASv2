// JavaScript Document

var url = '../php/sps_def_tasainteres.php';
var params = 'operacion=';
var metodo = 'get';

Event.observe(window, 'load', ue_inicializar , false);

function ue_inicializar()
{	
    params = "operacion=ue_inicializar";
    new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
	
	function onInicializar( respuesta )
	{
		if (trim(respuesta.responseText) != "")
		{
		    var respuestas = respuesta.responseText.split('&');
	        num_respuesta = -1;
		    //Años
		    num_respuesta++;
		    if (trim(respuestas[num_respuesta]) != "")
		    {	  
		  	  var anos = JSON.parse(respuestas[num_respuesta]);
			  for (i=0; i<anos.length; i++)
			  {
			    $('cmbano').options[$('cmbano').options.length] = new Option(anos[i].den,anos[i].cod);
		 	  }
		    }			
		    //Meses
		    num_respuesta++;
		    if (trim(respuestas[num_respuesta]) != "")
		    {	  
		  	  var meses = JSON.parse(respuestas[num_respuesta]);
			  for (i=0; i<meses.length; i++)
			  {
			     $('cmbmes').options[$('cmbmes').options.length] = new Option(meses[i].den,meses[i].cod);
			  }
		    }
		 }
	}	
}

function ue_eliminar()
{
  lb_valido = true;
  var la_objetos  = new Array ("cmbmes","cmbano");
  var la_mensajes = new Array ("Mes","Año");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	if (confirm("¿ Esta seguro de eliminar este registro ?"))
	{
	  function onEliminar(respuesta)
	  {
		ue_cancelar();
		alert(respuesta.responseText);
	  }	  
	  params = "operacion=ue_eliminar&mes="+$F('cmbmes')+"&ano="+$F('cmbano');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	  ue_cancelar();
	  alert("El proceso fue cancelado!");	  
	}
  };
}

function ue_cancelar()
{
   document.form1.reset();
   habilitar("cmbmes,cmbano,txtbcv,txtnumgac");
   scrollTo(0,0);
}

function ue_nuevo()
{	
  ue_cancelar();
  $('hidguardar').value = "insertar";
  habilitar("cmbmes,cmbano,txtbcv,txtnumgac");
}

function ue_guardar()
{
	lb_valido=true;
	var la_objetos  = new Array ("cmbano","cmbmes","txtbcv","txtnumgac");
	var la_mensajes = new Array("Mes","Año","Porcentaje Banco Central de Venezuela", "el número de gaceta");
	lb_valido = valida_datos_llenos(la_objetos, la_mensajes);
	if(lb_valido)
	{
		if(($F('hidguardar')== "modificar")&&($F('hidpermisos').indexOf('m',0)<0 ))
		{
			alert("No tiene permiso para actualizar información.");
		}
		else
		{
			function onGuardar(respuesta)
			{
				ue_cancelar();
				if(trim(respuesta.responseText) !="" )
				{alert(respuesta.responseText);}
			}
			var tasainteres =
			{
				"ano":$F('cmbano'),
				"mes":$F('cmbmes'),
				"bcv":$F('txtbcv'),
				"numgac":$F('txtnumgac')
			};
			var objeto = JSON.stringify(tasainteres);
			params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
			new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onGuardar});
		}
	}	
}

function ue_buscar()
{
	pagina="sps_cat_tasainteres.html.php";
    catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=300,resizable=yes,location=no,top=0,left=0");
}

function ue_cargar_registro(arr_datos)
{ 
  var cajas = new Array('cmbano','cmbmes','txtbcv','txtnumgac');
  for (i=0; i<cajas.length; i++)
  {
	  if (cajas[i].substring(0,3)=="cmb" )
	  {   
	  	  if((arr_datos[i])<10)
		  {
			 $(cajas[i]).value = "0"+(arr_datos[i]);
		  }
		  else{
	  	  $(cajas[i]).value = (arr_datos[i]); }
	  }
	  else
	  {
		 $ls_bcv = arr_datos[i].replace(".", "," );	  
		 $(cajas[i]).value = $ls_bcv;
	  }
  }
  $('hidguardar').value = "modificar";
  deshabilitar("cmbano, cmbmes");
  habilitar("txtbcv");
  $('txtbcv').focus();
  
   /*//Esta condicion es util cuando cargamos el registro desde el catalogo
    if ((navigator.appName == "Netscape"))
    {
	  //Esta linea de abajo es un error provocado intencionalmente
	  eval("$error_provocado;");
    }*/
}
