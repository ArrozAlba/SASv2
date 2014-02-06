//--------------------------------------------------------
//	Función que valida que no se incluyan comillas simples 
//	en los textos ya que dañana la consulta SQL
//--------------------------------------------------------
function trim(str)
{
	while(''+str.charAt(0)==' ')
	str=str.substring(1,str.length);
	while(''+str.charAt(str.length-1)==' ')
	str=str.substring(0,str.length-1);
	return str;
}
function ue_validarcarater(valor,caracter)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto != caracter)&&(texto != caracter))
		{
			textocompleto += texto;
		}	
	}
	return textocompleto;
}

function AgregarKeyPress(Obj)
{
		Ext.form.TextField.superclass.initEvents.call(Obj);
		if(Obj.validationEvent == 'keyup')
		{
			Obj.validationTask = new Ext.util.DelayedTask(Obj.validate, Obj);
			Obj.el.on('keyup', Obj.filterValidation, Obj);
		}
		else if(Obj.validationEvent !== false)
		{
			Obj.el.on(Obj.validationEvent, Obj.validate, Obj, {buffer: Obj.validationDelay});
		}
		if(Obj.selectOnFocus || Obj.emptyText)
		{
			Obj.on("focus", Obj.preFocus, Obj);
			if(Obj.emptyText)
			{
				Obj.on('blur', Obj.postBlur, Obj);
				Obj.applyEmptyText();
			}
		}
		if(Obj.maskRe || (Obj.vtype && Obj.disableKeyFilter !== true && (Obj.maskRe = Ext.form.VTypes[Obj.vtype+'Mask']))){
			Obj.el.on("keypress", Obj.filterKeys, Obj);
		}
		if(Obj.grow)
		{
			Obj.el.on("keyup", Obj.onKeyUp,  Obj, {buffer:50});
			Obj.el.on("click", Obj.autoSize,  Obj);
		}
			Obj.el.on("keyup", Obj.changeCheck, Obj);
}
//pasar un registro seleccionado del grid activo hasta la definicion
function PasDatosGridDef(Registro)
{
	for(i=0;i<Campos.length;i++)
	{
		if(Registro.get(Campos[i][0])!='' && Registro.get(Campos[i][0]))
		{
			valor = Registro.get(Campos[i][0]);
			valor = valor.replace('|@@@|','+');
			palnueva='';
			for(j=0;j<valor.length;j++)
			{
				letra = valor.substr(j,1);
				//alert(letra);
				if(letra=='|')
				{
					letra = unescape('%0A');
				}
			palnueva=palnueva+letra;	
			}
			Ext.get(Campos[i][0]).dom.value =palnueva;
		}
	}
	Actualizar=true;			
}


function limpiarCampos()
{
	for(i=0;i<Campos.length;i++)
	{
		//alert(Campos[i][0]);
		Ext.get(Campos[i][0]).dom.value = '';
	}
}

function cargarJson(operacion)
{
	strJson="{'oper':'"+operacion+"'";
	for(i=0;i<Campos.length;i++)
	{
		if(Ext.get(Campos[i][0]).dom.type=="checkbox" && Ext.get(Campos[i][0]).dom.value=='')
		{
			Ext.get(Campos[i][0]).dom.value='0'
		}
		else if(Ext.get(Campos[i][0]).dom.type=="checkbox" && Ext.get(Campos[i][0]).dom.value!='')
		{
			Ext.get(Campos[i][0]).dom.value='1'
		}
		else if(Ext.get(Campos[i][0]).dom.type=="textarea" && Ext.get(Campos[i][0]).dom.value!='')
		{
		
		valor = Ext.get(Campos[i][0]).dom.value;	
		palnueva='';
		for(j=0;j<valor.length;j++)
		{
			letra = valor.substr(j,1);
			cod = escape(letra);
			if(cod=='%0A')
			{
				//alert('una');
				letra='|';	
			}
		palnueva=palnueva+letra;
			
		}
		
		Ext.get(Campos[i][0]).dom.value=palnueva
		}
		valor = Ext.get(Campos[i][0]).dom.value;
		strJson=strJson+",'"+Campos[i][0]+"':'"+valor+"'";

	}
	strJson=strJson+"}";
	return strJson; 
}

function LlamarActualizar()
{
	if(Actualizar==null)
	{
		operacion='incluir';
		Mensa='Incluido';
	}
	else
	{	
		operacion='actualizar';
		Mensa='Modificado';			
	}
	if(validarObjetos2()==false)
	{
		return false;
	}
	else
	{
	Json=cargarJson(operacion);
	myJSONObject=Ext.util.JSON.decode(Json);	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
    Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultad, request ) { 
                 datos = resultad.responseText;
				//alert(datos);
			
                 var Registros = datos.split("|");
                 if (Registros[1] == '1')
                 {
					 Ext.MessageBox.alert('Mensaje','Registro '+Mensa + ' con éxito');
					 limpiarCampos()
  
                 }
                 else
                 {
                 	Ext.MessageBox.alert('Error', Registros[0]);
                 }
	}
	,
	failure: function (result, request) { 
		Ext.MessageBox.alert('Error', result.responseText); 
	}
     });
    }
}

function LlamarEliminar()
{

	var Result;
	Ext.MessageBox.confirm('Confirmar', '¿Desea eliminar este registro?', Result);
	function Result(btn)
	{
		if(btn=='yes')
		{
			Json=cargarJson('eliminar');
			Ob=Ext.util.JSON.decode(Json);
			ObjSon=JSON.stringify(Ob);
			parametros = 'ObjSon='+ObjSon; 
			Mensa = "Eliminado";
			Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function ( resultad, request ) { 
				 datos = resultad.responseText;
					//	alert(datos);
					
				 var Registros = datos.split("|");
				 if (Registros[1] == '1')
				 {
					Ext.MessageBox.alert('Mensaje','Registro '+Mensa + ' con éxito');
					limpiarCampos();
				 }
				 else
				 {
				  Ext.MessageBox.alert('Error', Registros[0]);
				 }
			},
			failure: function ( result, request) { 
				Ext.MessageBox.alert('Error', result.responseText); 
			} 
		      });

		}
	
	};
}
function LlamarNuevo()
{
	var myJSONObject ={
		"oper": 'buscarcodigo'
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultad, request ) 
	{ 
        datos = resultad.responseText;
        
		 var Registros = datos.split("|");
		Cod = Registros[1];
	//	alert(Cod);
		if(Cod!='')
		{
			limpiarCampos();
			Actualizar=null
			Ext.get(Campos[0][0]).dom.value = Cod;	
		}
		else
		{
			Ext.MessageBox.alert('Mensaje', 'No se pudo realizar la operación');
			LimpiarCampos();
		}
      },
	failure: function ( result, request) { 
		Ext.MessageBox.alert('Error', result.responseText); 
	} 
  });
	
}

hexadecimal = new Array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F")

function convierteHexadecimal(num)
{
    var hexaDec = Math.floor(num/16)
    var hexaUni = num - (hexaDec * 16)
    return hexadecimal[hexaDec] + hexadecimal[hexaUni]
}

//elimina los espacios en blanco de una cadena
function cadSinEspacio(cadena)
{
	if(cadena)
	{
		cadenaNueva=cadena.replace("&nbsp;",""); 
		return cadenaNueva;
	}
}

function Encriptar(pass)
{
	ls_acumini='';
	ls_acumfin='';
	cadena=null;
	Tam = pass.length;
	for(i=0;i<=Tam-1;i++)
	{
		Ascii = pass.substr(i,1);
		AuxAs = Ascii.charCodeAt(0);
		ls_temp=convierteHexadecimal(AuxAs);
		//alert(ls_temp);
		left = ls_temp.substr(0,1);
		right= ls_temp.substr(ls_temp.length-1,1);	
		//alert(left);
		//alert(right);
		ls_acumini =ls_acumini+right;
		ls_acumfin =left+ls_acumfin;
		
	}
	cadena=ls_acumini+ls_acumfin;
	return cadena;
}


/*******************************************************************
* @Función que valida un dato de acuerdo a 
* varios tipos de validación.
* @Parámetros: id: propiedad id del objeto del formulario a validar. 
* long longitud del campo, tipoVal: tipo de validación.
* @Valor de Retorno: 0 o 1 si fue correcto o no.
* @Autor: Johny Porras. 
* @Fecha de Creación: 15/05/2008
***************************************************************
* @fecha modificación: 16/05/2008  
* @Descripción: Agregar casos para validar nombres,telefono y correo.
* @autor: Gusmary Balza.                 
*********************************************************************/
function validarObjetos(id,tipoVal) 
{
	obj   = document.getElementById(id);
	arVal = tipoVal.split('|');
	for (i=0;i<arVal.length;i++)
	{
		switch(arVal[i])
		{
			case 'novacio':

				if ((obj.value=='') ||  (obj.value=='Seleccione'))
				{
					Ext.MessageBox.alert('Campos Vacios', 'Debe llenar el campo '+obj.name);
					return false;
				}
			break;
			case 'novaciodos':
				arrid=id.split('&');
				obj1 = document.getElementById(arrid[0]);
				obj2 =document.getElementById(arrid[1]);
				if((obj1.value=='' || obj1.value=='Seleccione') && (obj2.value=='' || obj2.value=='Seleccione'))
				{
					Ext.MessageBox.alert('Campos Vacios', 'Debe llenar algun campo: '+obj1.name+' o '+obj1.name+' por favor');
					return false;
				}
			break;
			case 'nombre': //solo letras
				val = obj.value;
				longitud = val.length;
				validos='ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚabcdefghijklmnñopqrstuvwxyzáéíóú'+' ';
				if (longitud<3)
				{
				 	Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' no tiene la longitud correcta');
				 	return '0';
				}
				else
				{
				 	for(r=0;r<longitud;r++)
				 	{
			      		ch=val.charAt(r);					  
				  		if(validos.search(ch) == -1) //busca en la cadena validos el caracter ch
				  		{
				   			Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' debe contener solo letras');
				   			return '0';
				  		}			
			     	}
				}
			break;
			
			case 'longexacta':
				val = obj.value;
				longitud = val.length;
				validos='ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚabcdefghijklmnñopqrstuvwxyzáéíóú'+' ';
				if ((longitud<long) || (longitud>long))
				{
				 	Ext.MessageBox.alert('Longitud Incorrecta', 'El campo '+obj.name+ ' no tiene la longitud correcta');
				 	return '0';
				}
				else
				{
				 	for(r=0;r<longitud;r++)
				 	{
			      		ch=val.charAt(r);					  
				  		if (validos.search(ch) == -1) //busca en la cadena validos el caracter ch
				  		{
				   			Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' debe contener solo letras');
				   			return '0';
				  		}			
			     	}
				}				
			break;
			
			case 'telefono':
				val = obj.value;	
			 	var er_tlf = /^\d{4}-\d{7}$/; //expresión regular para telefono con formato ejm: 0251-5555555
				if(!er_tlf.test(val))
				{
       			 	Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' es incorrecto');
				  	return '0';
            	}
			break;
			
			case 'vaciotelefono':
				val = obj.value;
				longitud = val.length;
				if ((longitud <= long) && longitud>0)
				{			
					var er_tlf = /^\d{4}-\d{7}$/; //expresión regular para telefono con formato ejm: 0251-5555555
					if (!er_tlf.test(val))
					{
						Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' es incorrecto');
						return '0';
					}
				}
			break;
			
			case 'email':
			   	val = obj.value;
			break;
			
			case 'vacioemail':
			   	val = obj.value;
				longitud = val.length;
				if ((longitud <= long) && longitud>0)
				{			
					var filtro=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/; //expresión regular para emails
					if (!filtro.test(val)) //test compara la cadena val con la de la expresión regular
					{
						Ext.MessageBox.alert('Campos Incorrectos', 'El campo '+obj.name+' es incorrecto');
						return '0';	
					}
				}
			break;
			
			case 'numero': //para solo numeros
				val = obj.value;
				longitud = val.length;
				if (longitud <= long)
				{			
					var er_numero=/^\d+$/; //expresión regular para solo digitos
					if (!er_numero.test(val))
					{
						Ext.MessageBox.alert('Tipo de Dato incorrecto', 'El campo '+obj.name+' es incorrecto');
						return '0';	
					}
				}
				else
				{					
					Ext.MessageBox.alert('Longitud Incorrecta', 'El campo '+obj.name+' no tiene la longitud correcta');
					return '0';	
				}
			break;
			
			case 'login':
				val = obj.value;
				var er_login = /^[a-zd_]{4,20}$/i; 
				if(!er_login.test(val))
				{
       			 	Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' es incorrecto');
				  	return '0';
            	}			
			break;
			
			case 'alfanumerico':  //solo numeros o letras, guiones y espacios
				val = obj.value;
				longitud = val.length;
				if (longitud <= long)
				{
				//	var er_validos = /^[a-zA-Z0-9\s.\-]+$/;
					validos='ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚabcdefghijklmnñopqrstuvwxyzáéíóú0123456789'+'-'+' ';

					for(r=0;r<longitud;r++)
				//	if (!er_validos.test(val))
					{
						ch=val.charAt(r);			  
						if(validos.search(ch) == -1)
						{
							Ext.MessageBox.alert('Tipo de Dato Incorrecto', 'El campo '+obj.name+ ' no debe contener caracteres especiales');
							return '0';
						}
					}
				}
				else
				{
					Ext.MessageBox.alert('Longitud Incorrecta', 'El campo '+obj.name+' no tiene la longitud correcta');
					return '0';	
				}
		}
	}
	return '1';
}



/*******************************************************************
* @Función que valida la existencia 
* @Valor de Retorno: 0 o 1 si fue correcto o no.
* @Autor: Johny Porras. 
* @Fecha de Creación: 15/05/2008
****************************************************************/

function validarExistencia(gridCat,gridPrin,codigo,codigoprin)
{
	Registrosel  = gridCat.getSelectionModel().getSelections();
	cantUsuarios = gridPrin.store.getCount()-1;
	Registrosact = gridPrin.store.getRange(0,cantUsuarios);
	for (i=0; i<=Registrosel.length-1; i++)
	{	
		AuxReg1 = Registrosel[i].get(codigo);
		for (j=0; j<=Registrosact.length-1; j++)
		{
			if (Registrosact[j].get(codigoprin)==AuxReg1)
			{
				Ext.MessageBox.alert('Mensaje','El registro con codigo '+ AuxReg1 +' ya esta seleccionado');
				return true;
			}	
		}
	}
}



/*******************************************************************
* @Función que valida un dato de acuerdo a 
* varios tipos de validación.
* @Parámetros: id: propiedad id del objeto del formulario a validar. 
* long longitud del campo, tipoVal: tipo de validación.
* @Valor de Retorno: 0 o 1 si fue correcto o no.
* @Autor: Johny Porras. 
* @Fecha de Creación: 15/05/2008
***************************************************************
* @fecha modificación: 16/05/2008  
* @Descripción: Agregar casos para validar nombres,telefono y correo.
* @autor: Gusmary Balza.                 
*********************************************************************/
function validarObjetos2()
{

	for(j=0;j<Campos.length;j++)
	{
	obj   = document.getElementById(Campos[j][0]);
	arVal = Campos[j][1].split('|');
	for (i=0;i<arVal.length;i++)
	{
		switch(arVal[i])
		{
			case 'novacio':
				//alert(obj.id);
				if ((obj.value=='') ||  (obj.value=='Seleccione'))
				{
					Ext.MessageBox.alert('Campos Vacios', 'Debe llenar el campo '+obj.name);
					return false;
				}
			break;
			case 'nombre': //solo letras
				val = obj.value;
				longitud = val.length;
				validos='ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚabcdefghijklmnñopqrstuvwxyzáéíóú'+' ';
				if (longitud<3)
				{
				 	Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' no tiene la longitud correcta');
				 	return '0';
				}
				else
				{
				 	for(r=0;r<longitud;r++)
				 	{
			      		ch=val.charAt(r);					  
				  		if(validos.search(ch) == -1) //busca en la cadena validos el caracter ch
				  		{
				   			Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' debe contener solo letras');
				   			return '0';
				  		}			
			     	}
				}
			break;
			case 'longexacta':
				val = obj.value;
				longitud = val.length;
				validos='ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚabcdefghijklmnñopqrstuvwxyzáéíóú'+' ';
				if ((longitud<long) || (longitud>long))
				{
				 	Ext.MessageBox.alert('Longitud Incorrecta', 'El campo '+obj.name+ ' no tiene la longitud correcta');
				 	return '0';
				}
				else
				{
				 	for(r=0;r<longitud;r++)
				 	{
			      		ch=val.charAt(r);					  
				  		if (validos.search(ch) == -1) //busca en la cadena validos el caracter ch
				  		{
				   			Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' debe contener solo letras');
				   			return '0';
				  		}			
			     	}
				}				
			break;
			case 'telefono':
				val = obj.value;	
			 	var er_tlf = /^\d{4}-\d{7}$/; //expresión regular para telefono con formato ejm: 0251-5555555
				if(!er_tlf.test(val))
				{
       			 	Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' es incorrecto');
				  	return '0';
            	}
			break;
			case 'vaciotelefono':
				val = obj.value;
				longitud = val.length;
				if ((longitud <= long) && longitud>0)
				{			
					var er_tlf = /^\d{4}-\d{7}$/; //expresión regular para telefono con formato ejm: 0251-5555555
					if (!er_tlf.test(val))
					{
						Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' es incorrecto');
						return '0';
					}
				}
			break;
			
			case 'email':
			   	val = obj.value;
			break;
			
			case 'vacioemail':
			   	val = obj.value;
				longitud = val.length;
				if ((longitud <= long) && longitud>0)
				{			
					var filtro=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/; //expresión regular para emails
					if (!filtro.test(val)) //test compara la cadena val con la de la expresión regular
					{
						Ext.MessageBox.alert('Campos Incorrectos', 'El campo '+obj.name+' es incorrecto');
						return '0';	
					}
				}
			break;
			
			case 'numero': //para solo numeros
				val = obj.value;
				longitud = val.length;
				if (longitud <= long)
				{			
					var er_numero=/^\d+$/; //expresión regular para solo digitos
					if (!er_numero.test(val))
					{
						Ext.MessageBox.alert('Tipo de Dato incorrecto', 'El campo '+obj.name+' es incorrecto');
						return '0';	
					}
				}
				else
				{					
					Ext.MessageBox.alert('Longitud Incorrecta', 'El campo '+obj.name+' no tiene la longitud correcta');
					return '0';	
				}
			break;
			
			case 'login':
				val = obj.value;
				var er_login = /^[a-zd_]{4,20}$/i; 
				if(!er_login.test(val))
				{
       			 	Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' es incorrecto');
				  	return '0';
            	}			
			break;
			case 'alfanumerico':  //solo numeros o letras, guiones y espacios
				val = obj.value;
				longitud = val.length;
				if (longitud <= long)
				{
				//	var er_validos = /^[a-zA-Z0-9\s.\-]+$/;
					validos='ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚabcdefghijklmnñopqrstuvwxyzáéíóú0123456789'+'-'+' ';

					for(r=0;r<longitud;r++)
				//	if (!er_validos.test(val))
					{
						ch=val.charAt(r);			  
						if(validos.search(ch) == -1)
						{
							Ext.MessageBox.alert('Tipo de Dato Incorrecto', 'El campo '+obj.name+ ' no debe contener caracteres especiales');
							return '0';
						}
					}
				}
				else
				{
					Ext.MessageBox.alert('Longitud Incorrecta', 'El campo '+obj.name+' no tiene la longitud correcta');
					return '0';	
				}
		}
	}
}
	return '1';
	
}


function sumaTiempos(t1, t2){
var dot1 = t1.indexOf(".");
var dot2 = t2.indexOf(".");
var m1 = t1.substr(0, dot1);
var m2 = t2.substr(0, dot2);
var s1 = t1.substr(dot1 + 1);
var s2 = t2.substr(dot2 + 1);
var sRes = (Number(s1) + Number(s2));
var mRes;
var addMinute = false;
if (sRes >= 60){
addMinute = true;
sRes -= 60;
}
mRes = (Number(m1) + Number(m2) + (addMinute? 1: 0));
return String(mRes) + "." + String(sRes);
}




function padNmb(nStr, nLen)
{
    var sRes = String(nStr);
    var sCeros = "0000000000";
    return sCeros.substr(0, nLen - sRes.length) + sRes;
}

   function stringToSeconds(tiempo){
    var sep1 = tiempo.indexOf(":");
    var sep2 = tiempo.lastIndexOf(":");
    var hor = tiempo.substr(0, sep1);
    var min = tiempo.substr(sep1 + 1, sep2 - sep1 - 1);
    var sec = tiempo.substr(sep2 + 1);
    return (Number(sec) + (Number(min) * 60) + (Number(hor) * 3600));
   }

   function secondsToTime(secs){
    var hor = Math.floor(secs / 3600);
    var min = Math.floor((secs - (hor * 3600)) / 60);
    var sec = secs - (hor * 3600) - (min * 60);
    return padNmb(hor, 2) + "." + padNmb(min, 2);
   }

   function substractTimes(t1, t2){
    var secs1 = stringToSeconds(t1);
    var secs2 = stringToSeconds(t2);
    var secsDif = secs1 - secs2;
    return secondsToTime(secsDif);
   }



//--------------------------------------------------------
//	Función que valida que solo se incluyan números en los textos
//--------------------------------------------------------
function ue_validarnumero(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}

//--------------------------------------------------------
//	Función que valida que el texto no esté vacio
//--------------------------------------------------------
function ue_validarvacio(valor)
{
	var texto;
	while(''+valor.charAt(0)==' ')
	{
		valor=valor.substring(1,valor.length)
	}
	texto = valor;
	return texto;
}

//--------------------------------------------------------
//	Función que rellena un campo con ceros a la izquierda
//--------------------------------------------------------
function ue_rellenarcampo(valor,maxlon)
{
	var total;
	var auxiliar;
	var longitud;
	var index;
	total=0;
    auxiliar=valor;
	longitud=valor.length;
	total=maxlon-longitud;
	if (total < maxlon)
	{
		for (index=0;index<total;index++)
		{
		   auxiliar="0"+auxiliar;      
		}
		valor = auxiliar;
	}
	return valor;
}

//--------------------------------------------------------
//	Función que formatea un número
//--------------------------------------------------------
function ue_formatonumero(fld, milSep, decSep, e)
{ 
	var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 

	if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Return
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

//--------------------------------------------------------
//	Función que verifica que la fecha  no tenga letras
//--------------------------------------------------------
function ue_validarfecha(valor)
{
	var texto;
	if ((valor=="dd/mm/aaaa")||(valor==""))
	{
		texto="1900-01-01";
	}
	else
	{
		texto = valor;
	}
	return texto;
}


function ValidarRegistroGrid()
{
	alert('validar');
	Resp = RegistroActual.get('codgi')=='' || RegistroActual.get('codco1')=='' || RegistroActual.get('codco2')=='' || RegistroActual.get('codvp')=='' || RegistroActual.get('colvp')=='' || RegistroActual.get('codcai')=='';
	//alert (Resp);
	return Resp;
	
}



//--------------------------------------------------------
//	Función que valida que solo se incluyan números(1234567890),guiones(-) y Espacios en blanco
//--------------------------------------------------------
function ue_validartelefono(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9")||(texto=="-")||(texto==" "))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}

//--------------------------------------------------------
//	Función que le da formato a la fecha
//--------------------------------------------------------
function ue_formatofecha(d,sep,pat,nums)
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

//---------------------------------------------------------------------
//     Funcion que devuelve un monto con el formato
//	   debido para realizar operaciones matemeticas
//---------------------------------------------------------------------
function ue_formato_operaciones(valor)
{
	valor=valor.toString();
	while (valor.indexOf('.')>0)
	{
		valor=valor.replace(".","");
	}
	valor=valor.replace(",",".");
	return valor;
	
}

//--------------------------------------------------------
//	Función que valida que un intervalo de tiempo sea valido
//--------------------------------------------------------
   function ue_comparar_intervalo(ld_desde,ld_hasta)
   { 

	f=document.form1;
	var valido = false; 
    var diad = ld_desde.substr(0, 2); 
    var mesd = ld_desde.substr(3, 2); 
    var anod = ld_desde.substr(6, 4); 
    var diah = ld_hasta.substr(0, 2); 
    var mesh = ld_hasta.substr(3, 2); 
    var anoh = ld_hasta.substr(6, 4); 
    
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
		alert("El rango de fecha es invalido");
	} 
	return valido;
   } 
   
   //-----------------------------------------------------
   // @Funcion que redondea un numero decimal a uno entero
   // @Autor: Johny Porras
   //----------------------------------------------------
  

   function redondear(numero)
    {
    	numero2='';
		numero=parseFloat(numero);
	//	if(numero%1>0.5)
//		{
//			numero+=.0;
//		}
		numero=Math.ceil(numero*10)/10
		AuxString = numero.toString();
		if(AuxString.indexOf('.')>=0)
		{
			AuxArr=AuxString.split('.');
			if(AuxArr[1]>=5)
			{
				numero=Math.ceil(numero);
			}
			else
			{
				numero=Math.floor(numero);
			}
		}
	
			return numero;
	
	} 
   
//----------------------------------------------------------------------------------------------
//	Función usada en la funcion keyrestrcitgrid
//----------------------------------------------------------------------------------------------

function getKeyCode(e)
{
 if (window.event)
    return window.event.keyCode;
 else if (e)
    return e.which;
 else
    return null;
}
//----------------------------------------------------------------------------------------------
//	Función que valida para que se incluyan datos alfanumericos y guiones(-) para los codigos 
//----------------------------------------------------------------------------------------------

function keyrestrictgrid(e) 
{
 var validchars='';	
 var key='', keychar='';
 
 validchars='1234567890abcdefghijklmnopqrstuvwxyz-';
 key = getKeyCode(e);
 if (key == null) return true;
 keychar = String.fromCharCode(key);
 keychar = keychar.toLowerCase();
 validchars = validchars.toLowerCase();
 if (validchars.indexOf(keychar) != -1)
  return true;
 if ( key==null || key==0 || key==8 || key==9 || key==13 || key==27 )
  return true;
 return false;
}

//--------------------------------------------
//
//----------------------------------------
function ObtenerSesion(rutap,pantalla)
{
	var myJSONObject ={
		"oper":"ObtenerSesion" ,
		"pantalla":pantalla 
	};
	
	ObjSon=Ext.util.JSON.encode(myJSONObject);
	parametros ='ObjSon='+ObjSon; 
   Ext.Ajax.request({
	url : rutap,
	params : parametros,
	method: 'POST',
	success: function ( resultad, request ) { 
            datos = resultad.responseText;
        //    alert(datos);
		    arDatos = datos.split("|");
		    if(arDatos[1]=='nosesion')
            {
            	 alert('Usted no ha iniciado sesión');
				 location.href='../../../sigesp_inicio_sesion.php';
				 return false
            }
		    
		    if(arDatos[2]=="1")
		    {
			 	 Seguridad=Ext.util.JSON.decode(arDatos[0]);
		    	 Permisos=Ext.util.JSON.decode(arDatos[1]);
		    	//alert(Seguridad.logusr);
		    	 //titulo[0].innerHTML=Seguridad.logusr;
		    	// Ext.get('nombreusuario').dom.innerHTML=Seguridad.logusr;
		    	 
		    }	
		   else
		   {
		   		Ext.Msg.show({
				   title:'Mensaje',
				   msg: 'No tiene permiso para usar esta pantalla',
				   buttons: Ext.Msg.OK,
				   fn: processResult,
				   animEl: 'elId',
				   icon: Ext.MessageBox.INFO
				});
		   }	
	}
	,
	failure: function ( result, request) 
	{ 
		Ext.MessageBox.alert('Error', 'El Registro no pudo ser '+Mensa); 
	}

      });		
      
      function processResult()
      {
      	location.href='sigesp_windowblank.php';
      }
}


//------------------------------------------------------------
// Funcion para sacar una ventana emergente
//-----------------------------------------------------------

function Abrir_ventana (pagina) {
var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1000, height=800, top=10, left=10";
window.open(pagina,"",opciones);
}


//funcion que le da formato a un calculo

function ue_formato_calculo(monto)
{
	monto=monto.toString();	
	 while(monto.indexOf('.')>0)
	 {//Elimino todos los puntos o separadores de miles
	  monto=monto.replace(".","");
	 }
	 monto=monto.replace(",","."); 
	 return monto;
}


//--------------------------------------------------------
// Función que formatea un número
//dec:cantidad de decimales a usar
//miles:simbolo de separdor de miles
//--------------------------------------------------------
function numFormat(num,dec,miles)
{
//var num = this.valor, 
signo=3, expr='';
var cad = ""+num;
var ceros = "", pos, pdec, i;
for (i=0; i < dec; i++)
ceros += '0';
pos = cad.indexOf(',')
if (pos < 0)
    cad = cad+","+ceros;
else
    {
    pdec = cad.length - pos -1;
    if (pdec <= dec)
        {
        for (i=0; i< (dec-pdec); i++)
            cad += '0';
        }
    else
        {
        num = num*Math.pow(10, dec);
        num = Math.round(num);
        num = num/Math.pow(10, dec);
        cad = new String(num);
        }
    }
pos = cad.indexOf(',')
if (pos < 0) pos = cad.lentgh
if (cad.substr(0,1)=='-' || cad.substr(0,1) == '+')
       signo = 4;
if (miles && pos > signo)
    do{
        expr = /([+-]?\d)(\d{3}[\.\,]\d*)/
        cad.match(expr)
        cad=cad.replace(expr, RegExp.$1+'.'+RegExp.$2)
        }
while (cad.indexOf('.') > signo)
    if (dec<0) cad = cad.replace(/\./,'')
        return cad;
}




function KeyCheck(e)
{
   var KeyID = e.keyCode;
   switch(KeyID)
   {
      case 113:
      Abrir_ventana("sigesp_spe_ayudaprin.php");  
   }
}




