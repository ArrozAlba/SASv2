// ActionScript Remote Document
//--------------------------------------------------------
//	Función que Instancia el objeto ajax
//--------------------------------------------------------
function objetoAjax()
{
	var xmlhttp=false;
	try
	{
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E)
		{
			xmlhttp = false;
  		}
	}
	if(!xmlhttp && typeof XMLHttpRequest!='undefined')
	{
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function enviar_ajax(datos,dir_archivo,nombre_div,metodo,opciones,ruta)
{
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById(nombre_div);
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open(metodo,dir_archivo,true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			if(opciones==''){divgrid.innerHTML = "<img src='" + ruta + "shared/imagenes/cargando2.gif' width='20' height='20'><br>Cargando datos...";}
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					
					if(document.getElementById('txt_msj_error')!=null){document.getElementById('txt_msj_error').value = null;}
					if(document.getElementById('txt_msjajax_sigesp')!=null){document.getElementById('txt_msjajax_sigesp').value = null;}
															
					if(opciones=='input'){divgrid.value = ajax.responseText;}
					else{divgrid.innerHTML = ajax.responseText;}
					
					if(document.getElementById('txt_msj_error')!=null){mensajes_sigesp('ERROR DE CONEXIÓN',document.getElementById('txt_msj_error').value);}
					if(document.getElementById('txt_msjajax_sigesp')!=null){mensajes_sigesp('MENSAJE DE SISTEMA',document.getElementById('txt_msjajax_sigesp').value);}
					if(document.getElementById('txt_ejecutar_funcion')!=null){funcion_respuesta(document.getElementById('txt_ejecutar_funcion').value);}
				}
				else
				{
					if(ajax.status==404)
					{
						divgrid.innerHTML = "La página no existe";
					}
					else
					{//mostramos el posible error     
						divgrid.innerHTML = "Error:".ajax.status;
					}
				}
				
			}
		}
	}	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//ajax.setRequestHeader("charset","iso-8859-1");

	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send(datos);
}


