function uf_convertir(obj)
{
	var valor=new String(obj);
	
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
	if(li_punto>0)
	{
		ls_new=valor.substr(0,li_punto);
		ls_dec=valor.substr(li_punto+1,li_longitud-li_punto);	
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
	
	return ls_new_int+","+ls_dec;
	
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
		alert(ls_new_str);	
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