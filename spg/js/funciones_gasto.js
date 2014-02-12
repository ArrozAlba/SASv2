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

//--------------------------------------------------------
//	Función que convierte los montos para poder hacer calculos
//--------------------------------------------------------
function ue_formato_calculo(monto)
{
	while(monto.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monto=monto.replace(".","");
	}
	monto=monto.replace(",",".");	
	return monto;
}

function redondear(cantidad, decimales) 
{
	var cantidad = parseFloat(cantidad);
	var decimales = parseFloat(decimales);
	decimales = (!decimales ? 2 : decimales);
    primera=Math.round(cantidad * Math.pow(10, decimales+1)) / Math.pow(10, decimales+1);
    return Math.round(primera * Math.pow(10, decimales)) / Math.pow(10, decimales);
}