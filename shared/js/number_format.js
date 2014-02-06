function uf_convertir(obj)
{
	var valor=new String(obj);
	if(valor<0)
	{
		li_temp="-";
		valor = Math.abs(valor);
		valor = new String(valor);
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
	valor=roundNumber(valor);
	var valor=new String(valor);
	li_punto=valor.indexOf('.');	
	li_longitud=valor.length;
	if(li_punto>=0)
	{
		ls_new=valor.substr(0,li_punto);
		ldec_monto=roundNumber(valor);
		var aux=new String(ldec_monto);
		ls_dec=aux.substr(li_punto+1,li_longitud-li_punto);
	}
	else
	{
		ls_new=valor;
		ls_dec="00";
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
	if(ls_dec.length<2)
	{
		while(ls_dec.length<2)
		{
			ls_dec=ls_dec+"0";
		}
	}
	else
	{
		ls_dec=ls_dec.substr(0,2);
	}	
	return li_temp+ls_new_int+","+ls_dec;	
}

function roundNumber(obj)
{ 
	//var numberField = obj; // Field where the number appears 
	var rnum = obj;
	var rlength = 2; // The number of decimal places to round to 
	var cantidad = parseFloat(obj);
	var decimales = parseFloat(rlength);
	decimales = (!decimales ? 2 : decimales);
return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);
/*	var original=parseFloat(obj);
	var newnumber=Math.round(original*100)/100;*/
/*	if (rnum > 8191 && rnum < 10485) 
	{ 
	alert("rnum->"+rnum);	
		rnum = rnum-5000; 
		var newnumber = Math.round(rnum*Math.pow(10,rlength))/Math.pow(10,rlength);
		newnumber = newnumber+5000; 
	alert("newnumber->"+newnumber);	
	}
	else 
	{ 
		var newnumber = Math.round(rnum*Math.pow(10,rlength))/Math.pow(10,rlength); 
	}*/
	//return newnumber;
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

function currencyFormat(fld, milSep, decSep, e) { 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Enter 
	if (whichCode == 127) return true; // Enter 	
	if (whichCode == 9) return true; // Enter 	
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
    return false; 
   }
  


