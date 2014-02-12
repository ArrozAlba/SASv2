// JavaScript Document
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


//--------------------------------------------------------
//	Función que actualiza el total de las filas de un campo de un pagina opener
//--------------------------------------------------------
function ue_calcular_total_fila_opener(campo)
{
	existe=true;
	li_i=1;
	while(existe)
	{
		existe=opener.document.getElementById(campo+li_i);
		if(existe!=null)
		{
			li_i=li_i+1;
		}
		else
		{
			existe=false;
			li_i=li_i-1;
		}
	}
	return li_i
}

//--------------------------------------------------------
//	Función que valida el correo electrónico
//--------------------------------------------------------
function ue_validarcorreo(theElement)
{
	var s = theElement.value;
	var filter=/^[A-Za-z][A-Za-z0-9_]*@[A-Za-z0-9_]+\.[A-Za-z0-9_.]+[A-za-z]$/;
	if (s.length == 0 ) return true;
	if (filter.test(s))
	{
		return true;
	}
	else
	{
		alert("Ingrese una direccion de correo valida");
		theElement.focus();
		return false;
	}
}

//--------------------------------------------------------
//	Función que actualiza el total de las filas de un campo local
//--------------------------------------------------------
function ue_calcular_total_fila_local(campo)
{
	existe=true;
	li_i=1;
	while(existe)
	{
		existe=document.getElementById(campo+li_i);
		if(existe!=null)
		{
			li_i=li_i+1;
		}
		else
		{
			existe=false;
			li_i=li_i-1;
		}
	}
	return li_i
}
