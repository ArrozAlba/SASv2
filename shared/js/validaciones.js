/*  Validaciones JavaScript library
 *  (c) 2006 Ing. Edgar M. Pastrán C.
 *  edgar_pastran@yahoo.es 
 *
 *  Libreria usada para validar muchas de las operaciones rutinarias
 *  en aplicaciones web
/*--------------------------------------------------------------------------*/

function currencyFormat(fld, milSep, decSep, whichCode) 
{ 
    var num_decimales = 2;
	if (arguments.length > 4)
	{num_decimales = arguments[4];}
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    if (whichCode == 13) return true; // Enter 
    if (whichCode == 8) return true; // Backspace <-
    if (whichCode == 127) return true; // Suprimir -Del 
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 	
    len = fld.value.length;
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0)
	{fld.value = ''}
	else if (len <= num_decimales)
	{
	  fld.value = '0'+decSep;
	  for (d=0; d<(num_decimales-len); d++)
	  {fld.value += '0';}
	  fld.value += aux;
	}
    else
	{ 
     aux2 = ''; 
     for (j = 0, i = len - (num_decimales+1); i >= 0; i--)
	 { 
       if (j == 3)
	   { 
         aux2 += milSep; 
         j = 0; 
       } 
       aux2 += aux.charAt(i); 
       j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
      fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - num_decimales, len); 
    } 
    return false; 
} 
/*****************************************************************
  Funcion que genera un error en javascript en el navegador Mozilla
  o Netscape ocasionado de manera causal,
  necesario para realizar una peticion en una ventana despues de haber
  recibido informacion desde otra ventana, como un catalogo
******************************************************************/
function errorProvocado()
{
	if ((navigator.appName == "Netscape"))
    {
	  //Esta linea de abajo es un error provocado intencionalmente
	  eval("$error_provocado;");
   }
}
/*************************************************
  Funcion que coloca en una caja de texto de numeros
  enteros con formato de separador de miles
  Ejm: 1000000 -> 1.000.000
**************************************************/
function FormatoMiles(fld, milSep, tecla) 
{ 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = tecla;
    if (whichCode == 13) return true; // Enter 
    if (whichCode == 8) return true; // Backspace <-
    if (whichCode == 127) return true; // Suprimir -Del 
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key
	if (document.selection)//IE
	{
	  if (document.selection.createRange().text.length > 0)
	  {
	    seleccion = document.selection.createRange();
		seleccion.text="";
		fld.createTextRange().moveStart('character',-1);
		fld.createTextRange().moveEnd('character',0);
		fld.createTextRange().select();
	  }	  
	}
	else//Otro NS ó MFF
	{ 
	  if (fld.selectionStart < fld.value.length)
		fld.value = (fld.value).substring(0,fld.selectionStart);
	}
    len = fld.value.length; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1 || len == 2)
	{
		if (aux[0] == "0")
		{aux = aux.substring(1,len);}
		fld.value = aux;
	}
    if (len > 2)
	{ 
     aux2 = ''; 
     for (j = 2, i = len - 3; i >= 0; i--)
	 { 
      if (j == 3)
	  { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
      fld.value += aux2.charAt(i); 
     fld.value +=  aux.substr(len-2, len); 
    }
    return false; 
} 

/*************************************************
  Funcion que valida el texto de un caja de texto
  segun el tipo de dto que se quiere validar
  "x" -> Cualquier caracter menos comillas simples(') y comillas dobles(")
  "i" -> Numericos (Ejm: Codigos)
  "c" -> Numericos con guiones (Ejm: Cuentas Bancarias)
  "s" -> Alfabeticos (Ejm: Nombres)
  "a" -> Alfanumericos (Ejm: Direcciones)
  "e" -> email
  "t" -> telefono (Ejm: 0251-2555555)
  "g" -> Codigos alfanumericos y guiones
  "d" -> double (Ejm: 2.000.000,00)
  "m" -> enteros con puntos de miles (Ejm: 123.456.789)
  NOTA: Algunos caracteres para guiarse 
   Backspace=8, Enter=13, Barra Espaciadora= 32, Atras(<-)=37, Adelante(->)=39
   '0'=48, '9'=57, 'A'=65, 'Z'=90, 'a'=97, 'z'=122		
**************************************************/
//var nav4 = window.Event ? true : false;
function validaCajas(cajaTexto,tipo_dato,evt)
{
	key = evt.which || evt.keyCode;
	if ((key <= 13) || (key == 37))
	{return true;}
	if ((tipo_dato == "x")||(tipo_dato == "i")||(tipo_dato == "c")||(tipo_dato == "s")||
	    (tipo_dato == "a")||(tipo_dato == "e")||(tipo_dato == "t")||(tipo_dato == "g")||
		(tipo_dato == "r")||
		(tipo_dato == 0)||(tipo_dato == 1)||(tipo_dato == 2)||(tipo_dato == 3)||
	    (tipo_dato == 4)||(tipo_dato == 5)||(tipo_dato == 6)||(tipo_dato == 7)||
		(tipo_dato == 10))
	{
		if (((arguments.length > 3) && (cajaTexto.value.length < arguments[3])) ||
		    (arguments.length <= 3))
		{
			switch(tipo_dato)
			{
				case "x": case 0: return ((key != 34) && (key != 39));break;
				case "i": case 1: return ((key >= 48 && key <= 57)); break;
				case "c": case 2: return ((key >= 48 && key <= 57) || (key == 45)); break;
				case "s": case 3: return ((key == 32) || 
								  (key >= 65 && key <= 90) || (key >= 97 && key <= 122) || 
								  (key == 225) || (key == 233) || (key == 237) || (key == 243) || (key == 250) || //VOCALES MINUSCULAS ACENTUADAS
								  (key == 193) || (key == 201) || (key == 205) || (key == 211) || (key == 218) || //VOCALES MAYUSCULAS ACENTUADAS
								  (key == 241) || (key == 209) || (key == 44) || (key == 46) // Ñ, ñ, "," y "."
								  ); break;
				case "a": case 4: return ((key == 32) || (key >= 48 && key <= 57) || 
								  (key >= 65 && key <= 90) || (key >= 97 && key <= 122) || 
								  (key == 225) || (key == 233) || (key == 237) || (key == 243) || (key == 250) || //VOCALES MINUSCULAS ACENTUADAS
								  (key == 193) || (key == 201) || (key == 205) || (key == 211) || (key == 218) || //VOCALES MAYUSCULAS ACENTUADAS
								  (key == 241) || (key == 209) || (key == 44)// Ñ, ñ y ","
								  ); break;
				case "e": case 5: return ((key >= 45 && key <= 57) || (key >= 65 && key <= 122) || (key == 64 && cajaTexto.value.indexOf('@', 0) == -1));break;
				case "t": case 6: if (cajaTexto.value.length == 4 && cajaTexto.value.indexOf('-', 0) == -1 && key != 8)
						          {cajaTexto.value = cajaTexto.value + "-";}
						          return ((key > 48 && key <= 57 && cajaTexto.value != "") || (key == 48 ));break;
				case "g": case 7: return ((key == 32) || (key >= 48 && key <= 57) ||
								  (key >= 65 && key <= 90) || (key >= 97 && key <= 122) ||
								  (key == 241) || (key == 209) || (key == 45)// Ñ, ñ y "-"
								  ); break;
				case "r": case 10: return ((key >= 48 && key <= 57 && cajaTexto.value != "") || (cajaTexto.value == "" && (key == 74 || key == 106)));break;
			}
		}
		else
		{return false;}
	}
	else
	{
		switch(tipo_dato)
		{
			case "d":
			case 8  : if (arguments.length > 3)			
					  {
						if (parseFloat(uf_convertir_monto(cajaTexto.value)) == 0)
						{cajaTexto.value = "";}
						if (document.selection)//IE
						  selecciono = (document.selection.createRange().text.length > 0);
						else//NS ó MFF
						  selecciono = (cajaTexto.selectionStart < cajaTexto.value.length);
						if ((cajaTexto.value.length < arguments[3]) || (key == 45) || (key <= 13) || (selecciono))
						{
						  if (selecciono)
						  {
							if (document.selection)//IE
							{
							  seleccion = document.selection.createRange();
							  seleccion.text="";
							  cajaTexto.createTextRange().moveStart('character',-1);
							  cajaTexto.createTextRange().moveEnd('character',0);
							  cajaTexto.createTextRange().select();
							}
							else//Otro NS ó MFF
							{
							  cajaTexto.value = (cajaTexto.value).substring(0,cajaTexto.selectionStart);
							}
						  };
						  var num_decimales = 2;
						  if (arguments.length > 4)
						  {num_decimales = arguments[4];}
						  return (currencyFormat(cajaTexto,'.',',',key,num_decimales));
						}
						else
						{return false;}
					  }
					  else
					  {return (currencyFormat(cajaTexto,'.',',',key));}
					  break;
			case "m": 
			case 9  : if (arguments.length > 3)
					  {
						if (document.selection)//IE
						  selecciono = (document.selection.createRange().text.length > 0);
						else//NS ó MFF
						  selecciono = (cajaTexto.selectionStart < cajaTexto.value.length);
						if ((cajaTexto.value.length < arguments[3]) || (key <= 13) || (selecciono))
						{
						   if (parseFloat(uf_convertir_monto(cajaTexto.value)) == 0)
						   {cajaTexto.value = "";}
						   return (FormatoMiles(cajaTexto,'.',key));
						}
						else
						{return false;}
					  }
					  else
					  {return (FormatoMiles(cajaTexto,'.',key));} 
					  break;			
		}
	}
}

/*************************************************
  Funcion que valida si el texto de un caja de texto
  tiene formato de email.
  Ejm: xxxxx@xxxx.xxx
**************************************************/
function valida_Email(cajaTexto)
{
  if (cajaTexto.value != "")
  {
	if (cajaTexto.value.indexOf('@', 0) == -1 ||
		cajaTexto.value.indexOf('.', 0) == -1 ||
		cajaTexto.value.indexOf('.', 0) >= cajaTexto.value.length - 2)
	{ 
		alert("Dirección de e-mail inválida \nEjm: xxxxxxxxx@xxxxx.xxx");
		cajaTexto.focus();
	}
  }
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
      if (((value==null||trim(value)==""||parseFloat(uf_convertir_monto(value))==0) && 
           (type=="text"||type=="textarea"||type=="password")) ||
	      ((value=="null"||value==null||trim(value)=="") && 
		   (type=="select-one"||type=="select-multiple")))
      {
		if ((arguments.length > 1) && (mensaje != ""))
		{
		  if ((type=="text") || (type=="textarea") || (type=="password"))
		  {alert("Debe Indicar "+mensaje+"!!!");}
		  else if ((type=="select-one") || (type=="select-multiple"))
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

/*************************************************
  Funcion que coloca el contenido de una caja de
  texto con formato de double (xxx.xxx,xx)
  Ejm: 1..00.0 -> 1.000,00
**************************************************/
function ue_getformat(txt)
{		
    var num_decimales = 2;
    if ((arguments.length > 1) && (arguments[1]) != "i")
	  num_decimales = arguments[1];
	var texto = txt.value;
	if (txt.value.indexOf(",") < 0)
	{
	  texto = txt.value.substring(0,txt.value.length-num_decimales);
	  if (texto == "")
	    texto = "0";
	  texto += ","+txt.value.substring(txt.value.length-num_decimales,txt.value.length);
	}
	if(ue_valida_null(txt,"") == false)
	{
		txt.value="0,";
		for (d=0; d<num_decimales; d++)
		{txt.value+="0";}
	}
	else
	{txt.value=uf_convertir(uf_convertir_monto(texto),num_decimales);}
	if ((arguments.length > 2) && (arguments[2] == "i"))
	{txt.value=texto.substring(0,texto.length-3);}
}
function ue_deduccion_liq(txt)
{
	var texto = txt.value;
	if (txt.value.indexOf("-") == 0)
	{
	  alert('Debe indicar la Cuenta Contable');
	}
}
/*************************************************
  Funcion que cambia el texto de un caja de texto
  a letras Mayusculas. Ejm
  vEnEZuElA -> VENEZUELA
**************************************************/
function mayusculas(cajaTexto)
{
    cajaTexto.value = cajaTexto.value.toUpperCase();
}

/*************************************************
  Funcion que cambia el texto de un caja de texto
  a letras MInusculas. Ejm
  vEnEZuElA -> venezuela
**************************************************/
function minusculas(cajaTexto)
{
    cajaTexto.value = cajaTexto.value.toLowerCase();
}

/*************************************************
  Funcion que cambia el texto de un caja de texto
  en formato de titulo. Ejm
  VENEZUELA -> Venezuela
**************************************************/
function changeCase(cajaTexto)
{
	var index;
	var tmpStr;
	var tmpChar;
	var preString;
	var postString;
	var strlen;
	f=document.form1;
	tmpStr = cajaTexto.value.toLowerCase();
	strLen = tmpStr.length;
	if (strLen > 0)
	{
		for (index = 0; index < strLen; index++)
		{
			if (index == 0)
			{
				tmpChar = tmpStr.substring(0,1).toUpperCase();
				postString = tmpStr.substring(1,strLen);
				tmpStr = tmpChar + postString;
			}
			else
			{
				tmpChar = tmpStr.substring(index, index+1);
				if (tmpChar == " " && index < (strLen-1))
				{
					tmpChar = tmpStr.substring(index+1, index+2).toUpperCase();
					preString = tmpStr.substring(0, index+1);
					postString = tmpStr.substring(index+2,strLen);
					tmpStr = preString + tmpChar + postString;
				}
			}
		}
	}
	cajaTexto.value = tmpStr;
}

/*****************************************************************
  Funcion que resta el valor de la caja de texto de la fecha Final
  con el valor de la caja de texto de la Fecha Inicial y calcula
  la cantidad de (días, meses o años) transcurridos y coloca el
  resultado en la caja de texto de Periodo
******************************************************************/
function restarFechas(cajaFechaInicio,cajaFechaFinal,cajaPeriodo,tipoPeriodo) 
{
	f = document.form1;
	if ((eval("f."+cajaFechaInicio+".value") != "") && (eval("f."+cajaFechaFinal+".value") != ""))
	{	  
	  var fechainicio = eval("f."+cajaFechaInicio+".value;");
	  var fechafinal  = eval("f."+cajaFechaFinal+".value;");
	  var fechaini = new Date();
	  fechaini.setFullYear(parseFloat(fechainicio.substr(6,4)),(parseFloat(fechainicio.substr(3,2))-1),parseFloat(fechainicio.substr(0,2)));
	  var fechafin = new Date();
	  fechafin.setFullYear(parseFloat(fechafinal.substr(6,4)),(parseFloat(fechafinal.substr(3,2))-1),parseFloat(fechafinal.substr(0,2)));
	  var periodo = ""; 
	  if (compararFechas(cajaFechaInicio,cajaFechaFinal,""))
	  {
		  var tiempoRestante = fechafin.getTime() - fechaini.getTime();		  		  
		  var divisor;
		  switch(tipoPeriodo)
		  {
			case 'd': divisor = 1000 * 60 * 60 * 24; break;
			case 'm': divisor = 1000 * 60 * 60 * 24 * 30; break;
			case 'a': divisor = 1000 * 60 * 60 * 24 * 365; break;
		  }  
		  periodo = (Math.floor(tiempoRestante / divisor)).toString();
	  }
	  eval ("f."+cajaPeriodo+".value = '"+periodo+"';");
	};
}

/*****************************************************************
  Funcion que compara dos cajas de textos con fechas y devuelve si
  la primera es menor o igual que la segunda fecha. En caso contrario 
  muestra un mensaje
******************************************************************/
function compararFechas(cajaFechaInicio,cajaFechaFinal,mensaje) 
{
	f = document.form1;
	var fechainicio;
	if (document.getElementById(cajaFechaInicio) == null)
	{fechainicio = cajaFechaInicio;}
	else
	{fechainicio = eval("f."+cajaFechaInicio+".value;");}
	var fechafinal;
	if (document.getElementById(cajaFechaFinal) == null)
	{fechafinal = cajaFechaFinal;}
	else
	{fechafinal = eval("f."+cajaFechaFinal+".value;");}
	var fechaini = new Date();
	fechaini.setFullYear(parseFloat(fechainicio.substr(6,4)),(parseFloat(fechainicio.substr(3,2))-1),parseFloat(fechainicio.substr(0,2)));
	var fechafin = new Date();
	fechafin.setFullYear(parseFloat(fechafinal.substr(6,4)),(parseFloat(fechafinal.substr(3,2))-1),parseFloat(fechafinal.substr(0,2)));
	var booleano;
	if (fechaini.getTime() <= fechafin.getTime())
	{
		booleano = true;
	}
	else
	{
		booleano = false;
	}
	if ((booleano == false) && (mensaje != ""))
	{
		alert(mensaje);
		try
		{eval ("f."+cajaFechaFinal+".focus();");}
		catch(e)
		{
		try
		{eval ("f."+cajaFechaInicio+".focus();");}
		catch(e)
		{}
	    }		
	}
	return booleano;
}

/*****************************************************************
  Funcion que retorno la fecha actual en el siguiente formato
  (dd/mm/yyyy)  
******************************************************************/
function hoy() 
{
	var hoy = new Date();
	var dia = hoy.getDate();
	var mes = hoy.getMonth()+1;
	var ano = hoy.getFullYear();
	var fecha;
	if (dia < 10)
	{fecha = "0" + dia.toString();}
	else
	{fecha = dia.toString();}
	if (mes < 10)
	{fecha = fecha + "/0" + mes.toString();}
	else
	{fecha = fecha + "/"+ mes.toString();}
    fecha = fecha + "/" + ano.toString();
	return fecha;
}

/*****************************************************************
  Funcion que compara dos cajas de textos con montos o dos montos 
  y devuelve si la segunda es mayor o igual que la primera.
  En caso contrario muestra un mensaje
******************************************************************/
function compararMontos(cajaMonto1,cajaMonto2,mensaje) 
{
	f = document.form1;	
	var lb_caja1null = true;
	var lb_caja2null = true;
	if (document.getElementById(cajaMonto1) == null)
	{monto1 = parseFloat(uf_convertir_monto(cajaMonto1));}
	else
	{monto1 = parseFloat(uf_convertir_monto(eval("f."+cajaMonto1+".value;"))); lb_caja1null= false;}
	if (document.getElementById(cajaMonto2) == null)
	{monto2 = parseFloat(uf_convertir_monto(cajaMonto2));}
	else
	{monto2 = parseFloat(uf_convertir_monto(eval("f."+cajaMonto2+".value;"))); lb_caja2null= false;}

	var booleano;
	if (monto2 >= monto1)
	{
		booleano = true;
	}
	else
	{
		booleano = false;
	}
	if ((booleano == false) && (mensaje != ""))
	{
		alert(mensaje);		
		if (lb_caja1null == false)
		{
		  eval("f."+cajaMonto1+".focus();");
		  if (eval("f."+cajaMonto1+".type") == "text")
		  {eval("f."+cajaMonto1+".select();");}
		}
		else if (lb_caja2null == false)
		{
		  eval("f."+cajaMonto2+".focus();");
		  if (eval("f."+cajaMonto2+".type") == "text")
		  {eval("f."+cajaMonto2+".select();");}
		}
	}
	return booleano;
}

/*****************************************************************
  Funcion que valida que el monto de una caja de texto se encuentre 
  en un rango (Mayor al valor minimo y menor o igual al valor maximo)
******************************************************************/
function validarRangoMonto(valorminimo,valormaximo,caja,mensaje) 
{
	f = document.form1;
	var valor = parseFloat(uf_convertir_monto(eval("f."+caja+".value;")));
	var minimo = parseFloat(uf_convertir_monto(valorminimo));
	var maximo = parseFloat(uf_convertir_monto(valormaximo));
	var booleano;
	if ((valor > minimo)&&(valor <= maximo))
	{booleano = true;}
	else
	{booleano = false;}
	if ((booleano == false) && (mensaje != ""))
	{
		eval("f."+caja+".focus();");
		eval("f."+caja+".select();");
		alert(mensaje);
	}
	return booleano;
}

/*****************************************************************
  Funcion que reañiza una operacion aritmetica con dos valores y
  devuelve el resultado (con formato-> 000.000,00)
******************************************************************/
function operacionAritmetica(valor1, valor2, operador)
{
  var resultado;
  valor1 = parseFloat(uf_convertir_monto(valor1));
  valor2 = parseFloat(uf_convertir_monto(valor2));
  eval("resultado =  valor1 "+operador+" valor2;");
  resultado = Math.round(resultado*100)/100;
  resultado = uf_convertir(resultado);
  return resultado;
}

/*****************************************************************
  Funcion que recibe una cifra numerica y devuelve su equivalente
  en cantidad en letras
******************************************************************/
function NumeroToLetras(numero)
{
  var linea;
  function NumToLet(num)
  {
	arr_unidades = new Array('UN','DOS','TRES','CUATRO','CINCO','SEIS','SIETE','OCHO','NUEVE','DIEZ','ONCE','DOCE','TRECE','CATORCE','QUINCE');
	arr_decenas  = new Array('DIECI','VEINTI','TREINTA','CUARENTA','CINCUENTA','SESENTA','SETENTA','OCHENTA','NOVENTA');
	arr_centenas = new Array('CIENTO','DOSCIENTOS','TRESCIENTOS','CUATROCIENTOS','QUINIENTOS','SEISCIENTOS','SETECIENTOS','OCHOCIENTOS','NOVECIENTOS');
	var centena, doble, decena, unidad;

	if (num == 100)
	{linea = ' CIEN ';}
	else
	{
	  linea   = '';
	  centena = Math.floor(num/100);
	  doble   = num - (centena * 100);
	  decena  = Math.floor(num/10) - (centena * 10);
	  unidad  = num - (decena*10) - (centena*100);
	  if (centena>0)
	  {linea += arr_centenas[centena-1]+' ';}
	  if (doble > 0)
	  {
        if (doble == 20)
	    {linea += ' VEINTE ';}
        else if (doble < 16)
		{linea += arr_unidades[doble-1];}
        else
		{
          linea += arr_decenas[decena-1];
          if ((decena>2) && (unidad!=0))
		  {linea += ' Y ';}
          if (unidad > 0)
		  {linea += arr_unidades[unidad-1];}
		} 
	  }
    }
  }

  numero = uf_convertir_monto(numero);
  var entero    = parseInt(numero.substr(0,numero.indexOf('.', 0)));
  var decimales = numero.substring(numero.indexOf('.', 0)+1,numero.length);

  //Inicializamos el string que contendrá las letras según el valor numérico
  var millones, miles, unidades, letras;
  if (entero == 0)
  {letras = 'CERO';}
  else if (entero < 0)
  {letras = 'MENOS';}
  else if (entero == 1)
  {letras = 'UN';return letras;}
  else if (entero > 1)
  {letras = '';}
  
  //Determinamos el Nº de millones, miles y unidades de numero en positivo
  entero   = Math.abs(entero);
  millones = Math.floor(entero/1000000);
  miles    = Math.floor((entero - (millones*1000000))/1000);
  unidades = entero - ((millones*1000000)+(miles*1000));

  //Vamos poniendo en el string las cadenas de los números(llamando a subfuncion)
  if (millones == 1)
  {letras += ' UN MILLON ';}
  else if (millones > 1)
  {NumToLet(millones);letras +=  linea + ' MILLONES ';}

  if (miles == 1)
  {letras += ' MIL ';}
  else if (miles > 1)
  {NumToLet(miles);letras +=  linea + ' MIL ';}

  if (unidades > 0)
  {NumToLet(unidades);letras += linea;}
  
  if (decimales.length > 0)
  {
    letras += ' CON '+decimales+'/1';
    for (d=0; d<decimales.length; d++)
    {letras += '0';}
  }
  
  return trim(letras);
}

/*****************************************************************
  Funcion que limpia un select (combo) de todos sus options (items)
******************************************************************/
function removeAllOptions(combo)
{
    for (i=(combo.options.length-1); i>=1; i--)
    {combo.remove(i)}
}

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
	  $(trim(cajas[i])).setAttribute("class","caja-deshabilitada");
	  $(trim(cajas[i])).setAttribute("className","caja-deshabilitada");
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
	  $(trim(cajas[i])).setAttribute("class","caja-habilitada");
	  $(trim(cajas[i])).setAttribute("className","caja-habilitada");
    }
}

/*****************************************************************
  Funcion usada en los catalogos que valida que la pagina halla
  sido abirta desde otra principal sino se cierra
******************************************************************/
function validarOpener()
{
    if (!opener)
    {
	  var ventana = window.self;
	  ventana.opener = window.self;
	  parent.close();
    };
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
    nuevaFila.className = FILA.attributes['class'].value;
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

/*****************************************************************
  Funcion que carga una foto desde la ruta contenida en un input
  file en un img. Solo para Internet Explorer (Usada en el evento
  onChange del input file)
******************************************************************/
function cargar_foto_IE(input_file,img_foto)
{
    if (navigator.appName == 'Microsoft Internet Explorer')
    {
	  if (trim(input_file.value).length > 0)
	  {
	    var extension = input_file.value.substring(input_file.value.length-4).toLowerCase();
	    if (extension == ".jpg" || extension == "jpeg")
	    {
	      try
	      {img_foto.src = "file:///"+input_file.value;}
	      catch(e)
	      {limpiar_foto(img_foto);}
	    }
	    else
	    {
		  alert("Debe Seleccionar una Imagen de Formato JPG");
		  limpiar_foto(img_foto);
	    }	  
	  }
    }
}

/*****************************************************************
  Funcion que carga una foto desde la ruta contenida en un input
  file en un img. Solo para Netscape y Firefox (Usada en el evento
  onClick del input file)
******************************************************************/
function cargar_foto_NS(input_file,img_foto)
{
    if (navigator.appName == 'Netscape')
    {
	  if (trim(input_file.value).length > 0)
	  {
	    var extension = input_file.value.substring(input_file.value.length-4).toLowerCase();
	    if (extension == ".jpg" || extension == "jpeg")
	    {
	      try
	      {img_foto.src = "../../../shared/class_folder/class_codificar_foto.php?ruta_foto="+input_file.value;}
	      catch(e)
	      {limpiar_foto(img_foto);}
	    }
	    else
	    {
		  alert("Debe Seleccionar una Imagen de Formato JPG");
		  limpiar_foto(img_foto);
	    }
	  }
    }
}  

/*****************************************************************
  Funcion que carga un img con una imagen en blanco
******************************************************************/
function limpiar_foto(img_foto)
{
    img_foto.src = "../../../kernel/images/tools20/blanco.jpg";
}

/*****************************************************************
  Funcion que devuelve un arreglo con todas las cajas de texto,
  combos y textarea de un elemento en la pagina html
******************************************************************/
// Recorremos todos los nodos de manera recursiva
function getElements(contenedor)
{
    var elementos = new Array();
    for (var i=0;i < contenedor.childNodes.length;i++)
      if (contenedor.childNodes[i].nodeType==1)
      {
	    if ((contenedor.childNodes[i].nodeName.toLowerCase()=="input" && contenedor.childNodes[i].type.toLowerCase()=="text") ||
		    (contenedor.childNodes[i].nodeName.toLowerCase()=="select") ||
		    (contenedor.childNodes[i].nodeName.toLowerCase()=="textarea"))
	    {elementos[elementos.length] = contenedor.childNodes[i];}
	    else
	    {elementos = elementos.concat(getElements(contenedor.childNodes[i]));}
      }
    return elementos;
}

/*****************************************************************
  Funcion que devuelve un arreglo con todas elementos de un tipo
  (text,textarea,select-one,checkbox,etc...)
  que estan dentro de otro elemento en la pagina html
******************************************************************/
// Recorremos todos los nodos de manera recursiva
function getElementsByType(contenedor, tipo)
{
    var elementos = new Array();
    for (var i=0;i < contenedor.childNodes.length;i++)
      if (contenedor.childNodes[i].nodeType==1)
      {
	    if (((contenedor.childNodes[i].nodeName.toLowerCase()=="input") ||
		     (contenedor.childNodes[i].nodeName.toLowerCase()=="select") ||
		     (contenedor.childNodes[i].nodeName.toLowerCase()=="textarea")) &&
			(contenedor.childNodes[i].type.toLowerCase()==tipo)) 
	    {elementos[elementos.length] = contenedor.childNodes[i];}
	    else
	    {elementos = elementos.concat(getElementsByType(contenedor.childNodes[i],tipo));}
      }
    return elementos;
}

/*****************************************************************
  Funcion que devuelve el valor en que se encuentra el scroll
  vertical de una ventana
******************************************************************/
function valorScroll()
{
	if (navigator.appName == "Microsoft Internet Explorer")
	{return document.body.scrollTop;}
	else
	{return window.pageYOffset;} 
}
/*****************************************************************
  Funcion usada en los catalogos que valida que la pagina halla
  sido abirta desde otra principal sino se cierra
******************************************************************/
function validarOpener()
{
    if (!opener)
    {
	  var ventana = window.self;
	  ventana.opener = window.self;
	  parent.close();
    };
}
/*****************************************************************
  Funcion usada para mostrar mensaje de esspera mientras 
  realiza la busqueda o proceso
******************************************************************/
function mostrar_mensaje()
{
  var id_div = "mensaje";
  if (arguments.length > 0)
  {id_div = arguments[0];}
  var mensaje = "Espere un Momento...";
  if (arguments.length > 1)
  {mensaje = arguments[1];}
  var ancho = 200;
  var alto  = 70;
  var top   = ((screen.height-alto)/2)+valorScroll();
  var left  = (screen.width-ancho)/2;
  if (arguments.length > 2)
  {top = arguments[2];}
  if (arguments.length > 3)
  {left = arguments[3];}
  $(id_div).innerHTML         = "<p><b>"+mensaje+"</b></p><p><img src='../../../shared/imagebank/tools20/progress.gif'></p>";
  $(id_div).align             = "center";
  $(id_div).style.font.weight = "bold";
  $(id_div).style.position    = "absolute";
  $(id_div).style.top         = top+"px";
  $(id_div).style.left        = left+"px";
  $(id_div).style.height      = alto+"px";
  $(id_div).style.width       = ancho+"px";
  $(id_div).style.background  = "#EEEEEE";/*"#DAE9F3";*/
  $(id_div).style.border      = "solid #9EADC6"; /*#000000";*/
 settings = {
	  tl: { radius: 10 },
	  tr: { radius: 10 },
	  bl: { radius: 10 },
	  br: { radius: 10 },
	  antiAlias: true,
	  autoPad: true,
	  validTags: ["div"]
  }
  $(id_div).style.visibility  = "visible";
}
/*****************************************************************
  Funcion que esconde el div con el nombre que se le pasa 
  por parametro
******************************************************************/

function ocultar_mensaje()
{
  var id_div = "mensaje";
  if (arguments.length > 0)
  {id_div = arguments[0];}
  $(id_div).style.visibility = "hidden";
}




/*****************************************************************
  Funcion que mejora la funcion window.open, ya que mantiene
  el popup abiero de manera modal
******************************************************************/
var popupwin = null;
function popupWin(url,name,parametros) 
{
	popupwin = window.open(url,name,parametros);
	return popupwin;
}
if (!document.all) 
{
	document.captureEvents (Event.CLICK);
}
document.onclick = 
function() 
{
  if (popupwin != null && !popupwin.closed) 
  {popupwin.focus();}
}


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

function uf_convertir_entero(valor)
{
	li_long=valor.length;
	if((li_long>3)&&(li_long<=6))
	{
		ls_algo=valor.substr(li_long-3,3);
		ls_new_str=valor.substr(0,li_long-3)+"."+ls_algo;		
	}
	
	if((li_long>6)&&(li_long<=9))
	{
		ls_ultimo=valor.substr(li_long-3,3);
		ls_penultimo=valor.substr(li_long-6,3);
		ls_new_str=valor.substr(0,li_long-6)+"."+ls_penultimo+"."+ls_ultimo;		
	}
	if((li_long>9)&&(li_long<=12))
	{
		ls_ultimo=valor.substr(li_long-3,3);
		ls_penultimo=valor.substr(li_long-6,3);
		ls_antepenultimo=valor.substr(li_long-9,3);
		ls_new_str=valor.substr(0,li_long-9)+"."+ls_antepenultimo+"."+ls_penultimo+"."+ls_ultimo;
		
	}
	if((li_long>12)&&(li_long<=15))
	{
		ls_ultimo=valor.substr(li_long-3,3);
		ls_penultimo=valor.substr(li_long-6,3);
		ls_antepenultimo=valor.substr(li_long-9,3);
		ls_segundo=valor.substr(li_long-12,3);
		ls_new_str=valor.substr(0,li_long-12)+"."+ls_segundo+"."+ls_antepenultimo+"."+ls_penultimo+"."+ls_ultimo;
	}
	if((li_long>15)&&(li_long<=18))
	{
		ls_ultimo=valor.substr(li_long-3,3);
		ls_penultimo=valor.substr(li_long-6,3);
		ls_antepenultimo=valor.substr(li_long-9,3);
		ls_tercero=valor.substr(li_long-12,3);
		ls_segundo=valor.substr(li_long-15,3);
		ls_new_str=valor.substr(0,li_long-15)+"."+ls_segundo+"."+ls_tercero+"."+ls_antepenultimo+"."+ls_penultimo+"."+ls_ultimo;
	}
	return ls_new_str;
}

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
/*****************************************************************
  Funcion que chequea en una grid de Rico si hay mas de 10 registros
  para coloocar las columnas de modo ordenable o no; tambien coloca
  el titulo de la cantidad de registros mostrados.
  Ejm: "Mostrando Registro 1-10 de 20"
******************************************************************/
function chequearFilas(numFilas)
{     
	if (arguments.length > 1)
	{
	  if ((arguments[1].value == "") || (arguments[1].value >= 12))
	    $('data_grid_header').id = (numFilas<=10)?"sin_ordenar":$('data_grid_header').id;
	  else
	    $('sin_ordenar').id = (numFilas>=12)?"data_grid_header":$('sin_ordenar').id;
	  arguments[1].value = numFilas;
	}
	else
	  $('data_grid_header').id = (numFilas<=12)?"sin_ordenar":$('data_grid_header').id;

	if (numFilas > 12)	  
	  $('marcador').innerHTML = "Mostrando Registros 1 - 12 de " +numFilas;
	else if (numFilas > 0)
	  $('marcador').innerHTML = "Mostrando Registros 1 - "+numFilas+" de " +numFilas;
	else
	  $('marcador').innerHTML = "Mostrando Registros 0 - 0 de 0";
}
/*****************************************************************
  Funcion que le coloca un estilo a una fila de una tabla, muy
  usada en los catalogos
******************************************************************/
function seleccionarFila(fila)
{
	fila.setAttribute("class","tabla-detalle-seleccionado");
	fila.setAttribute("className","tabla-detalle-seleccionado");
}

/*****************************************************************
  Funcion que le coloca un estilo a una fila de una tabla, muy
  usada los catalogos
******************************************************************/
function deseleccionarFila(fila)
{
	fila.setAttribute("class","tabla-detalle");
	fila.setAttribute("className","tabla-detalle");
}
/*****************************************************************
  Funcion que dibuja un DIV necesario en los catalogos para mostrar
  la grid de datos
******************************************************************/
function colocarGrid()
{
  if (navigator.appName != "Microsoft Internet Explorer")
  {
	document.write("<table width='100%' cellpadding='0' cellspacing='1'><tr><td>");
	document.write("<div id='viewPort' align='left'></div>");
	document.write("</td></tr></table>");
	document.write("<div id='mensaje'></div>");
  }
  else
  {
	document.write("<div id='viewPort' align='left'></div>");
	document.write("<div id='mensaje'></div>");
  }
}
/*****************************************************************
  Funcion que construye un grid con la estructura de una
  Grid de la libreria Rico.
******************************************************************/
function crearGrid(numRegistros,urlActualizar)
{
  var opts =
  {
	prefetchBuffer:true,
	onscroll:updateHeader,
	sortAscendImg:  '../../../kernel/images/tools20/sort_asc.gif',
	sortDescendImg: '../../../kernel/images/tools20/sort_desc.gif'
  };
  new Rico.LiveGrid("data_grid",12,numRegistros,urlActualizar,opts);
	
  //funcion para ordenar por campo y actualiza la tabla al hacer scroll
  function updateHeader(liveGrid, offset)
  {
	$('marcador').innerHTML = "Mostrando Registros " + (offset+2) + " - " + 
	(offset+liveGrid.metaData.getPageSize()+1)	+ " de " +
	liveGrid.metaData.getTotalRows();
	
	var sortInfo = "";
	if (liveGrid.sortCol)
	{
		sortInfo = "&data_grid_sort_col=" + liveGrid.sortCol + "&data_grid_sort_dir="+liveGrid.sortDir;
	}
  }
}
/*****************************************************************
  Funcion que chequea en una grid de Rico si hay mas de 10 registros
  para coloocar las columnas de modo ordenable o no; tambien coloca
  el titulo de la cantidad de registros mostrados.
  Ejm: "Mostrando Registro 1-10 de 20"
******************************************************************/
function chequearFilas(numFilas)
{
	if (arguments.length > 1)
	{
	  if ((arguments[1].value == "") || (arguments[1].value >= 10))
	    $('data_grid_header').id = (numFilas<=10)?"sin_ordenar":$('data_grid_header').id;
	  else
	    $('sin_ordenar').id = (numFilas>=10)?"data_grid_header":$('sin_ordenar').id;
	  arguments[1].value = numFilas;
	}
	else
	  $('data_grid_header').id = (numFilas<=10)?"sin_ordenar":$('data_grid_header').id;

	if (numFilas > 10)	  
	  $('marcador').innerHTML = "Mostrando Registros 1 - 10 de " +numFilas;
	else if (numFilas > 0)
	  $('marcador').innerHTML = "Mostrando Registros 1 - "+numFilas+" de " +numFilas;
	else
	  $('marcador').innerHTML = "Mostrando Registros 0 - 0 de 0";
}
//--------------------------------------------------------
//	Función que verifica qel formato de la fecha
//--------------------------------------------------------

function ue_validar_formatofecha(fecha)
{

    if (fecha != undefined && fecha.value != "" )
	{

        if (!/^\d{2}\/\d{2}\/\d{4}$/.test(fecha.value))
		{

            alert("Formato de Fecha No Válido (dd/mm/aaaa)");
			fecha.value="";
            return false;

        }
	}
}

//--------------------------------------------------------
//	Función que le da formato a la fecha
//--------------------------------------------------------

function ue_formato_fecha(d,sep,pat,nums,e)
{
	
	if(e.keyCode==46)
	{
		d.value="";	
	}
	else
	{
		if(d.valant != d.value)
		{
			val = d.value
			largo = val.length
			val = val.split(sep)
			val2 = ''
			for(r=0;r<val.length;r++)
			{
				val2 += val[r]	
			}
			if(nums)
			{
				for(z=0;z<val2.length;z++)
				{
					if(isNaN(val2.charAt(z)))
					{
						letra = new RegExp(val2.charAt(z),"g")
						val2 = val2.replace(letra,"")
					}
				}
			}
			val = ''
			val3 = new Array()
			for(s=0; s<pat.length; s++)
			{
				val3[s] = val2.substring(0,pat[s])
				val2 = val2.substr(pat[s])
			}
			for(q=0;q<val3.length; q++)
			{
				if(q ==0)
				{
					val = val3[q]
				}
				else
				{
					if(val3[q] != "")
					{
						val += sep + val3[q]
					}
				}
			}
			d.value = val
			d.valant = val
		}
	}
}