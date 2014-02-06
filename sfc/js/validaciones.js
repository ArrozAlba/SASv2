// JavaScript Document
function currencyFormat(fld, milSep, decSep, e) 
{ 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
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
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
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
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
	/*if (fld.id != "txtmonto")
    	validamonto(fld,500);*/
    return false; 
} 
   
function uf_format(obj)
{
	ldec_monto=uf_convertir(obj.value);
	obj.value=ldec_monto;
}

/*function ue_validacaracter(cadena, obj)////Ojo esta como q no funciona con IE.
{ 
   opc = false; 
   if (cadena == "%d")//toma solo caracteres  
   if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
   opc = true; 

   if (cadena == "%e")//toma el @, el punto y caracteres. Para Email
   if ((event.keyCode > 63 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode ==46)||(event.keyCode ==95)||(event.keyCode > 47 && event.keyCode < 58))  
   opc = true;    

   if (cadena == "%f")//Toma solo numeros
   { 
     if (event.keyCode > 47 && event.keyCode < 58) 
     opc = true; 
     if (obj.value.search("[,*]") == -1 && obj.value.length != 0) 
     if (event.keyCode == 44) 
     opc = true; 
   } 
   
   if (cadena == "%s") // toma numero y letras
   if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)||(event.keyCode ==47)||(event.keyCode ==35)||(event.keyCode ==45)) 
   opc = true; 
   
   if (cadena == "%c") // toma numero, punto y guion. Para telefonos
   if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode > 44 && event.keyCode < 47))
   opc = true; 
   
   if(opc == false) 
   event.returnValue = false;
}*/

/*function esDigito(sChr)
{ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
}*/

/*Function:  valida_null(field , mensaje)
	 *
	 *Descripción:   Función que se encarga de evaluar al objeto "field" para verificar si esta o no en blanco, en caso de que el objeto 
	                 este vacio se imprime el mensaje y se devuelve false,en caso contrario se devuelve true.
	  *Argumentos:   field: Objeto el cual va a ser chequeado su condicion de vacio. Ejempo: txtcedula.  
	                 mensaje: Cadena de caracteres que se mostrara al usuario en caso de que el contenido del objeto sea igual a null o
					 igual a vacio(blanco).*/
function ue_valida_null(field,campo)
{
	with (field) 
  {
    if (value==null||value==""||value=="s1" || value=="---")
      {
        alert("El campo "+campo+" está vacío!!!");
        return false;
      }
    else
      {
   	    return true;
      }
  }
}

function ue_valida_nuevo(field,campo)
{
	with (field) 
  {
    if (value==null||value==""||value=="s1" || value=="---")
      {
        alert(campo+" para poder realizar el proceso!!!");
        return false;
      }
    else
      {
   	    return true;
      }
  }
}

function currencyDate(date)
  { 
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string,10);

			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}
			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string,10);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
			//alert(ls_long);


  //  return false; 
   }
   
   /*************************************************
  Funcion que coloca en una caja de texto de numeros
  enteros con formato de separador de miles
  Ejm: 1000000 -> 1.000.000
**************************************************/
function FormatoMiles(fld, milSep, e) 
{ 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
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

   
function textCounter(field,maxlimit) 
{	
	if (field.value.length > maxlimit) { // if too long...trim it!
	field.value = field.value.substring(0, maxlimit);
	alert("ha llegado al máximo de caracteres permitidos");	
    }
}

/*************************************************
  Funcion que valida el texto de un caja de texto
  segun el tipo de dto que se quiere validar
  "i" -> Enteros (Ejm: Codigos)
  "s" -> Alfabeticos (Ejm: Nombres)
  "a" -> Alfanumericos (Ejm: Direcciones)
  "e" -> email
  "t" -> telefono (Ejm: 0251-2555555)
  "d" -> double (Ejm: 2.000.000,00)
  "m" -> enteros con puntos de miles (Ejm: 123.456.789)
  "x" -> No permite introducir "'", ' " ',"|","^"
  NOTA: Algunos caracteres para guiarse 
   Backspace=8, Enter=13, Barra Espaciadora= 32, '0'=48, '9'=57, 'A'=65, 'Z'=90, 'a'=97, 'z'=122		
**************************************************/
var nav4 = window.Event ? true : false;
function validaCajas(cajaTexto,tipo_dato,evt)
{	
	var key = nav4 ? evt.which : evt.keyCode;	
	//alert(key);
	switch(tipo_dato)
	{
		case "x": return( (key!=39) && (key!=34) && (key!=94) && (key!=124) ); break;//
		case "i": return ((key <= 13) || (key >= 48 && key <= 57)); break;
		case "s": return ((key <= 13) || (key == 32) || (key >= 65 && key <= 90) || (key >= 97 && key <= 122) || 
					      (key == 225) || (key == 233) || (key == 237) || (key == 243) || (key == 250) || //VOCALES MINUSCULAS ACENTUADAS
						  (key == 193) || (key == 201) || (key == 205) || (key == 211) || (key == 218) || //VOCALES MAYUSCULAS ACENTUADAS
						  (key == 241) || (key == 209) || (key == 44)// Ñ, ñ y ","
						 ); break;
	case "z": if (cajaTexto.value.length == 0 && (cajaTexto.value.indexOf('V', 0) == -1 || cajaTexto.value.indexOf('J', 0) == -1 || cajaTexto.value.indexOf('G', 0) == -1 || cajaTexto.value.indexOf('E', 0) == -1))
		      {return (key ==69 || key == 71 || key == 74 || key == 86 || key == 8); break;}
			  else{
				return ((key >= 48 && key <= 57 || key == 8)); break;  
				  
				  }			  
			 
		case "a": return ((key <= 13) || (key == 32) || (key >= 48 && key <= 57) || (key >= 65 && key <= 90) || (key >= 97 && key <= 122) || 
					      (key == 225) || (key == 233) || (key == 237) || (key == 243) || (key == 250) || //VOCALES MINUSCULAS ACENTUADAS
						  (key == 193) || (key == 201) || (key == 205) || (key == 211) || (key == 218) || //VOCALES MAYUSCULAS ACENTUADAS
						  (key == 241) || (key == 209) || (key == 44)// Ñ, ñ y ","
						 ); break;
		case "e": return ((key <= 13) || (key >= 45 && key <= 57) || (key >= 65 && key <= 122) || (key == 64 && cajaTexto.value.indexOf('@', 0) == -1));break;
		case "t": if (cajaTexto.value.length == 4 && cajaTexto.value.indexOf('-', 0) == -1)
		          {cajaTexto.value = cajaTexto.value + "-";}
		          return ((key <= 13) || (key > 48 && key <= 57 && cajaTexto.value != "") || (key == 48 ));break;
		case "d": if (arguments.length > 3)
		          {
					if (parseFloat(uf_convertir_monto(cajaTexto.value)) == 0)
					{cajaTexto.value = "";}
					if (document.selection)//IE
		              selecciono = (document.selection.createRange().text.length > 0);
				    else//NS ó MFF
				      selecciono = (cajaTexto.selectionStart < cajaTexto.value.length);
		            if ((cajaTexto.value.length < arguments[3]) || (key <= 13) || (selecciono))
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
					  return (currencyFormat(cajaTexto,'.',',',evt));
					}
				    else
				    {return false;}
				  }
				  else
				  {return (currencyFormat(cajaTexto,'.',',',evt));}
				  break;
		case "m": if (arguments.length > 3)
		          {
				    if (document.selection)//IE
		              (selecciono = document.selection.createRange().text.length > 0);
				    else//NS ó MFF
				      (selecciono = cajaTexto.selectionStart < cajaTexto.value.length);
		            if ((cajaTexto.value.length < arguments[3]) || (key <= 13) || (selecciono))
		            {
					   if (parseFloat(uf_convertir_monto(cajaTexto.value)) == 0)
					   {cajaTexto.value = "";}
					   return (FormatoMiles(cajaTexto,'.',evt));
				    }
				    else
				    {return false;}
				  }
				  else
				  {return (FormatoMiles(cajaTexto,'.',evt));} 
		          break;
	}	
}

/*************************************************
  Funcion que valida si el texto de un caja de texto
  tiene formato de email.
  Ejm: xxxxx@xxxx.xxx
**************************************************/
function valida_Email(cajaTexto)
{
	var caja = cajaTexto
	if (caja.value.indexOf('@', 0) == -1 ||
		caja.value.indexOf('.', 0) == -1)
	{ 
		alert("Dirección de e-mail inválida");
		caja.focus();
		caja.value="";
		
	}
}

function valida_datos_llenos(la_objetos,la_mensajes)
{
	f=document.form1;	
	lb_valido=true;
	for (li_i=0;li_i<la_objetos.length;li_i++)
	{
		if(ue_valida_null(eval("f."+la_objetos[li_i]),la_mensajes[li_i])==false)
		{
			eval("f."+la_objetos[li_i]+".focus();");
			lb_valido=false;
			break;				
		}
	}
	return lb_valido;
}

 function ue_comparar_intervalo(fechaInicio,fechaFin,mensaje)
 { 
	var valido = false; 
	f=document.form1;
	
    var diad = eval("f."+fechaInicio+".value.substr(0, 2);"); 
    var mesd = eval("f."+fechaInicio+".value.substr(3, 2);"); 
    var anod = eval("f."+fechaInicio+".value.substr(6, 4);"); 
    var diah = eval("f."+fechaFin+".value.substr(0, 2);"); 
    var mesh = eval("f."+fechaFin+".value.substr(3, 2);"); 
    var anoh = eval("f."+fechaFin+".value.substr(6, 4);");
	if(diad!="" && diah!="")
	{    
		if (anod < anoh)
		{
			 valido = true; 
		 }
		else 
		{ 
		 if (anod == anoh)
		 { 
		  if (mesd < mesh)
		  {
		   valido = true; 
		  }
		  else 
		  { 
		   if (mesd == mesh)
		   {
			if (diad <= diah)
			{
			 valido = true; 
			}
		   }
		  } 
		 } 
		} 
		if (valido==false)
		{
			if(mensaje!="")
				alert(mensaje);
			eval("f."+fechaFin+".value='';");		
		} 
		return valido;
	}
 }
 
function ue_valida_combojs(nombre,maximo) 
{		
	//alert("nombre "+nombre);

	if (eval(nombre+".txtview.value.length") > maximo) // if too long...trim it!
	{
		eval(nombre+".txtview.value="+nombre+".value.substring(0,"+maximo+");");	
		eval(nombre+".valcon.value="+nombre+".txtview.value;");
	}
} 	

function ue_ordenar(campo,operacion)
{
	f=document.form1;
	if(operacion!="")
		f.operacion.value=operacion;
	if(campo==f.campo.value)
	{
		if(f.orden.value=="ASC")
			f.orden.value="DESC";
		else
			f.orden.value="ASC";
	}
	else
		f.orden.value="ASC";
	f.campo.value=campo;	
	f.submit();
}

var popupwin = null;
function popupWin(url,name,ancho,alto) {
	
	popupwin = window.open(url,name,"menubar=no,toolbar=no,scrollbars=yes,width="+ancho+",height="+alto+",resizable=no,location=no,top=40,left=40,modal=yes,dialog=yes,minimizable=no");
	
	if (!document.all) {
	document.captureEvents (Event.CLICK);
	}
	document.onclick = function() {
	if (popupwin != null && !popupwin.closed) {
		
	popupwin.focus();
	}
}
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

function ue_getformat(txt)
{

	if(txt.value=="")
		txt.value="0,00";
	else
	{
		ls_txt=parseFloat(uf_convertir_monto(txt.value));
		if(ls_txt!=0)
			txt.value=uf_convertir(uf_convertir_monto(txt.value));
		else
			txt.value="0,00";
	}		
}




