/*Carga la lista de campos que se desean en los reportes*/
function ue_cargarlista(origen,destino,campoobligatorio)
{
	f=document.form1;
	li_filas=origen.options.length;
	for(li_i=li_filas-1;li_i>=0;li_i--)
	{
		if(origen.options[li_i].selected && origen.options[li_i].value!=campoobligatorio)
		{
			var io_opcion = document.createElement("OPTION");
			destino.appendChild(io_opcion);
			io_opcion.text =origen.options[li_i].text;
			io_opcion.value = origen.options[li_i].value;
			origen.removeChild(origen.options[li_i]);
		}
		else
		{	
			if(origen.options[li_i].selected && origen.options[li_i].value==campoobligatorio )
			{
				alert("Campo Obligatorio!!!");
			}
		}
	}						
}	

function ue_cargarlistaespecial(origen,destino,campoobligatorio)//especial para aquellos grupos conformados por 3 listas
{
	f=document.form1;
	li_filas=origen.options.length;	
	for(li_i=li_filas-1;li_i>=0;li_i--)
	{
		if(origen.options[li_i].selected && origen.options[li_i].value!=campoobligatorio)
		{
			var io_opcion = document.createElement("OPTION");
			//alert(destino.id);
			if(destino.id=="lst1")
			{
				li_tam=origen.options[li_i].text.length;
				li_pos=origen.options[li_i].text.indexOf("(");
				ls_tipocampo=origen.options[li_i].text.slice(li_pos+1,li_tam-1);
				li_pos=f.txtlista.value.lastIndexOf(" ");
				ls_tipolista=f.txtlista.value.slice(li_pos+1);
				if(ls_tipocampo==ls_tipolista)
				{
					destino.appendChild(io_opcion);
				}
			}else
			{
				destino.appendChild(io_opcion);
			}
			
			io_opcion.text =origen.options[li_i].text;
			io_opcion.value = origen.options[li_i].value;
			origen.removeChild(origen.options[li_i]);
		}
		else
		{	
			if(origen.options[li_i].selected && origen.options[li_i].value==campoobligatorio )
			{
				alert("Campo Obligatorio!!!");
			}
		}
	}						
}	

/*Retorna arreglo JS con los campos seleccionados para el reporte*/
function ue_obtenerarreglocampos(lista,doble)
{
	li_filas=lista.options.length;	
	arreglo=new Array();
	arreglo1=new Array();
	for (li_i=0;li_i<li_filas;li_i++)
	{
			arreglo1[li_i]=lista.options[li_i].value;
	}
	if(doble)
	{
		arreglo2=new Array();
		for (li_i=0;li_i<li_filas;li_i++)
		{
			arreglo2[li_i]=lista.options[li_i].text;
		}		
		arreglo[1]=arreglo1;
		arreglo[0]=arreglo2;
		//alert("entro "+arreglo.length);
	}
	else
	{
		arreglo=arreglo1;
	}
	
	return arreglo;	
}

/*Metodo que se utiliza para obtener una tira string con los campos a ser utilizados en los reportes*/
function ue_codificardata(arreglo,token,doble)//de arreglo a cadena
{
		if(doble)
		{
			arretext=arreglo[0];
			arreval=arreglo[1];
			var tira="";
			for(i=0;i<arretext.length;i++)
			{
					tira=tira+arretext[i]+token+arreval[i];
					if(i+1<arretext.length)
					{
						tira=tira+token;
					}
			}			
		}
		else
			var tira=arreglo.join(token);
		
		return tira;
}


function ue_cargar_listacondicional(lista,listadestino)
{
	f=document.form1;
	cadenaorigen=eval("f."+lista.value+".value");//datos de la tabla q selecciona el usuario	
	f.hidtabla.value=lista.value;
	arregloorigen=cadenaorigen.split("?");//la cadena la convierto en arreglo
	filasorigen=arregloorigen.length;//tamaño de ese arreglo
	filasdestino=listadestino.options.length;//cantidad de campos en la lista destino
	arreglofinal=new Array();
	index=0;
	index2=1;
	for(li_i=0;li_i<filasorigen;li_i++)
	{		
		encontrado=false;		
		for(li_j=0;li_j<filasdestino;li_j++)
		{
			if(arregloorigen[index2]==listadestino.options[li_j].value)
			{
				encontrado=true;
				break;
			}
		}
		if(!encontrado)
		{			
			arreglofinal[index]=arregloorigen[index2-1];
			index++;
			arreglofinal[index]=arregloorigen[index2];
			index++;
		}		
		index2=index2+2;
	}
	
	f.hidlista1.value=ue_codificardata(arreglofinal,"?",false);
	
	//alert(cadena);
	//f.hidlista1.value=eval("f."+lista.value+".value");	
	f.hidscroll.value=valorScroll();
	data=ue_obtenerarreglocampos(f.lst2,true);
	tira=ue_codificardata(data,"?",true);
	f.hidlista2.value=tira;
	f.submit();
}

function ue_habilitar_deshabilitar_botones(lista,btn1,btn2)
{
		li_filas=lista.options.length;
		if(lista.options[0].selected)
		{
			btn1.disabled=true;			
		}
		else
			btn1.disabled=false;
		if(lista.options[li_filas-1].selected)
			btn2.disabled=true;
		else
			btn2.disabled=false;		
}

function ue_moveritem(lista,direccion,btn1,btn2)//"arriba" o "abajo"
{
	li_filas=lista.options.length;
	ue_habilitar_deshabilitar_botones(lista,btn1,btn2);
	if (direccion=="arriba" && btn1.disabled==false)
	{
		for(li_i=0;li_i<li_filas;li_i++)
		{
			if(lista.options[li_i].selected)
			{			
				ls_textaux=lista.options[li_i].text;
				ls_valueaux=lista.options[li_i].value;
				lista.options[li_i].text=lista.options[li_i-1].text;
				lista.options[li_i].value=lista.options[li_i-1].value;
				lista.options[li_i-1].text=ls_textaux;
				lista.options[li_i-1].value=ls_valueaux;
				lista.options[li_i-1].selected=true;
				lista.options[li_i].selected=false;				
			}
		}
	}
	else
		if(direccion=="abajo" && btn2.disabled==false)
		{
			for(li_i=(li_filas-1);li_i>=0;li_i--)
			{
				if(lista.options[li_i].selected)
				{			
					ls_textaux=lista.options[li_i].text;
					ls_valueaux=lista.options[li_i].value;
					lista.options[li_i].text=lista.options[li_i+1].text;
					lista.options[li_i].value=lista.options[li_i+1].value;
					lista.options[li_i+1].text=ls_textaux;
					lista.options[li_i+1].value=ls_valueaux;
					lista.options[li_i+1].selected=true;
					lista.options[li_i].selected=false;				
				}
			}
		}
		
}