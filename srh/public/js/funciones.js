// JavaScript Document
/*****************************************************************
  Funcion que quita a los extremos de un string los espacios
  en blancos
******************************************************************/
function trim(cadena)
{
	return cadena.replace(/^\s*|\s*$/g,"");
}

/*****************************************************************
  Funcion que deshabilita las cajas de texto que se l pasen como 
  parametro en un string separadas por comas
******************************************************************/
function deshabilitar(cadenaCajas)
{
    cajas = cadenaCajas.split(',');
    for (i=0; i<cajas.length; i++)
    {
	  $(trim(cajas[i])).disabled = true;
	  $(trim(cajas[i])).style.backgroundColor = "#F0F8FF";
    }
}

/*****************************************************************
  Funcion que habilita las cajas de texto que se l pasen como 
  parametro en un string separadas por comas
******************************************************************/
function habilitar(cadenaCajas)
{
    cajas = cadenaCajas.split(',');
    for (i=0; i<cajas.length; i++)
    {
	  $(trim(cajas[i])).disabled = false;
	  $(trim(cajas[i])).style.backgroundColor = "#FFFFFF";
    }
}

/*****************************************************************
  Funcion que limpia un select (combo) de todos sus options (items)
******************************************************************/
function removeAllOptions(combo)
{
    for (i=(combo.options.length-1); i>=1; i--)
    {combo.remove(i)}
}

/****************************************************
  Function:  valida_null(field , mensaje)
	 *
	 *Descripción:   Función que se encarga de evaluar al objeto "field" para verificar si esta o no en blanco, en caso de que el objeto 
	                 este vacio se imprime el mensaje y se devuelve false,en caso contrario se devuelve true.
	  *Argumentos:   field: Objeto el cual va a ser chequeado su condicion de vacio. Ejemplo: txtcedula.  
	                 mensaje: Cadena de caracteres que se mostrara al usuario en caso de que el contenido del objeto sea igual a null o
					 igual a vacio(blanco).
*****************************************************/
function ue_valida_null(field,mensaje)
{
    with (field) 
    {
      if (((value==null||trim(value)=="") && 
           (type=="text"||type=="textarea")) ||
	      ((value=="null") && (type=="select-one")))
      {
		if ((arguments.length > 1) && (mensaje != ""))
		{
		  if ((type=="text") || (type=="textarea"))
		  {alert("Debe Indicar "+mensaje+"!!!");}
		  else if (type=="select-one")
		  {alert("Debe Seleccionar "+mensaje+"!!!")}
		}
        return false;
      }
      else
      {return true;}
    }
}

/*************************************************
  Funcion que valida si todos los elementos de un
  formulario pasados como parametros tienen algun
  valor o estan vacios
**************************************************/
function valida_datos_llenos(la_objetos,la_mensajes)
{
	var lb_valido = true;
	var li_i = 0;
	for (li_i;li_i<la_objetos.length;li_i++)
	{ 
	  if (arguments.length > 2)
	  {la_mensajes[li_i] = la_mensajes[li_i]+" "+arguments[2];}
	  if(ue_valida_null($(la_objetos[li_i]),la_mensajes[li_i])==false)
	  {
		if (arguments.length > 5)
		{eval(arguments[5]);}
	    else if ((arguments.length > 3) && (arguments[3].lastExpandedTab.titleBar.id != arguments[3].accordionTabs[arguments[4]].titleBar.id))
		{try{arguments[3].showTabByIndex(arguments[4]);}catch(e){}}
		if (la_mensajes[li_i] != "")
		{try{$(la_objetos[li_i]).focus();}catch(e){}};
		lb_valido=false;
		break;				
	  }
	}
	return lb_valido;
}

/*****************************************************************
  Funcion que realiza una copia de una fila patron en una tabla
  y devuelve la copia
******************************************************************/
function clonarFila(id_tabla, id_fila_patron)
{
    var TABLA = $(id_tabla);
    var FILA  = $(id_fila_patron);

    var nuevaFila = TABLA.insertRow(-1);
   //nuevaFila.className = FILA.attributes['class'].value;
    if (TABLA.rows.length > 2)
    {nuevaFila.id = parseInt(TABLA.rows[TABLA.rows.length-2].id)+1;}
    else
    {nuevaFila.id = TABLA.rows.length-1;}
    return nuevaFila;
}

/*****************************************************************
  Funcion que agrega una columna a una fila de una tabla con un
  contenido
******************************************************************/
function agregarColumna(Fila, Contenido, alineacion)
{
    var nuevaColumna  = Fila.insertCell(Fila.cells.length);
    nuevaColumna.align = alineacion;
    nuevaColumna.innerHTML = Contenido;
}

/*****************************************************************
  Funcion que elimina una fila de una tabla
******************************************************************/
function eliminarFila(id_tabla, id_fila)
{
    var TABLA = $(id_tabla);
    if(TABLA.rows.length > 1)
    {
	  var FILAS = TABLA.rows;
	  var eliminado = false;
	  var fila = FILAS.length - 1;
	  while ((eliminado == false) && (fila > 0))
	  {
	    if (FILAS[fila].id == id_fila)
	    {
	  	  TABLA.deleteRow(fila);
		  eliminado = true;
	    }
	    else
	    {fila--;}
	  }
    }
}

/*************************************************
  Funcion que convierte un numero real de formato
  1.000.000,00 a el formato 1000000.00
**************************************************/
function uf_convertir_monto(ldec_monto)
{
	var valor=new String(ldec_monto);
	while(valor.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		valor=valor.replace(".","");
	}
	valor=valor.replace(",",".");
	
	return valor;	
}

/*************************************************
  Funcion que convierte un numero real de formato
  1000000.00 a el formato 1.000.000,00
**************************************************/
function uf_convertir(obj)
{
	var num_decimales = 2;
	if (arguments.length>1)
	{num_decimales = arguments[1];}
	
	var valor=new String(obj);
	if(valor<0)
	{
		li_temp="-";
		valor=Math.abs(valor);
		valor=valor+".";
		for (i=0; i<num_decimales; i++)
		{valor += "0";}
	}
	else
	{
		li_temp="";			
	}

	li_coma=valor.indexOf(',');
	if(li_coma>0)
	{
		while(valor.indexOf('.')>0)
		{
			valor=valor.replace(".","");
		}
		valor=valor.replace(",",".");
	}
	li_punto=valor.indexOf('.');	
	li_longitud=valor.length;	
	if(li_punto>=0)
	{
		ls_new=valor.substr(0,li_punto);
		ls_dec=valor.substr(li_punto+1,li_longitud-li_punto);			
	}
	else
	{
		ls_new=valor;
		ls_dec="";
		for (j=0; j<num_decimales; j++)
		{ls_dec += "0";}
	}
	li_long_new=ls_new.length;
	if(li_long_new>3)
	{	
		ls_new_int=uf_convertir_entero(ls_new);
	}
	else
	{
		ls_new_int=ls_new;
	}
	if(ls_dec.length<num_decimales)
	{
		while(ls_dec.length<num_decimales)
		{
			ls_dec=ls_dec+"0";
		}
	}
	else
	{
		ls_dec=ls_dec.substr(0,num_decimales);
	}
	
	return li_temp+ls_new_int+","+ls_dec;
	
}