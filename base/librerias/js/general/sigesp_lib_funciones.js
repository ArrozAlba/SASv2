/***********************************************************************************
* @Js que contiene las funciones que van a ser usadas en todas las pantallas.
* @fecha de creación: 16/05/2008 
* @autor: Ing. Gusmary Balza.   
* **************************
* @fecha modificacion 
* @autor   
* @descripcion 
***********************************************************************************/

/*********************************************************************
* @Función que valida un dato de acuerdo a varios tipos de validación.
* @Parámetros: id: propiedad id del objeto del formulario a validar. 
* long longitud del campo, tipoVal: tipo de validación.
* @Valor de Retorno: 0 o 1 si fue correcto o no.
* @Autor: Johny Porras. 
* @Fecha de Creación: 15/05/2008
********************************************************************
* @fecha modificación: 16/05/2008  
* @Descripción: Agregar casos para validar nombres,telefono y correo.
* @autor: Gusmary Balza.                 
*********************************************************************/
function validarObjetos(id,long,tipoVal)
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
					return '0';
				}
			break;
			
			case 'novaciodos':
				arrid=id.split('&');
			    obj1 = document.getElementById(arrid[0]);
			    obj2 =document.getElementById(arrid[1]);
			    if((obj1.value=='' || obj1.value=='Seleccione') && (obj2.value=='' || obj2.value=='Seleccione'))
			    {
			     	Ext.MessageBox.alert('Campos Vacios', 'Debe llenar algún campo: '+obj1.name+' o '+obj2.name+' por favor');
			     	return false;
			    }
			break;
			
			case 'nombre': //solo letras
				val = obj.value;
				longitud = val.length;
				validos='ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚabcdefghijklmnñopqrstuvwxyzáéíóú'+' ';
			/*	if (longitud<3)
				{
				 	Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' no tiene la longitud correcta');
				 	return '0';
				}
				else
				{*/
				 	for(r=0;r<longitud;r++)
				 	{
			      		ch=val.charAt(r);					  
				  		if(validos.search(ch) == -1) //busca en la cadena validos el caracter ch
				  		{
				   			Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' debe contener solo letras');
				   			return '0';
				  		}			
			     	}
				//}
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
						if((!(ch=='('))&&(!(ch==')')))
						{
							if (validos.search(ch) == -1) //busca en la cadena validos el caracter ch
							{
								Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' debe contener solo letras');
								return '0';
							}			
						}
			     	}
				}				
			break;
			
			case 'telefonoFormato':
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
				if ((longitud <= long) && (longitud!=0))
				{			
					//var er_numero=/^\d+$/; //expresión regular para solo digitos
					var er_numero=/^[+]?\d*$/;
					//var er_numero = /^[0-9\s.\-]+$/;
					if (!er_numero.test(val))
					{
						Ext.MessageBox.alert('Tipo de Dato incorrecto', 'El campo '+obj.name+' es incorrecto');
						return '0';	
					}					
				}
				else if (longitud!=0)
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
			
			case 'telefono':
				val = obj.value;
				longitud = val.length;
				validos='0123456789'+'-';
				for(r=0;r<longitud;r++)
				{
					ch=val.charAt(r);					
					if(validos.search(ch) == -1)
					{
						Ext.MessageBox.alert('Tipo de Dato Incorrecto', 'El campo '+obj.name+ ' es incorrecto');
						return '0';
					}					
				}				
			break;
			
			case 'alfanumerico':  //solo numeros o letras, guiones y espacios
				val = obj.value;
				longitud = val.length;
				if (longitud <= long)
				{
				//	var er_validos = /^[a-zA-Z0-9\s.\-]+$/;
					validos='ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚabcdefghijklmnñopqrstuvwxyzáéíóú0123456789'+'-'+')'+'('+'@'+'_'+' ';
					for(r=0;r<longitud;r++)
				//	if (!er_validos.test(val))
					{
						ch=val.charAt(r);			  
						if((!(ch=='('))&&(!(ch==')')))
						{
							if(validos.search(ch) == -1)
							{
								Ext.MessageBox.alert('Tipo de Dato Incorrecto', 'El campo '+obj.name+ ' no debe contener caracteres especiales');
								return '0';
							}
						}
					}
				}
				else
				{
					Ext.MessageBox.alert('Longitud Incorrecta', 'El campo '+obj.name+' no tiene la longitud correcta');
					return '0';	
				}
			break;

			case 'fecha':
				var valido = true;
			    var fecha= new String(obj.value);   
			    var anio= new String(fecha.substring(fecha.lastIndexOf("/")+1,fecha.length))   
			    var mes= new String(fecha.substring(fecha.indexOf("/")+1,fecha.lastIndexOf("/")))   
			    var dia= new String(fecha.substring(0,fecha.indexOf("/")))   
			    if (isNaN(anio) || anio.length<4 || parseFloat(anio)<1900)
			    {   
					valido = false;	
			    }   
			    if (isNaN(mes) || parseFloat(mes)<1 || parseFloat(mes)>12)   
			    {   
					valido = false;	
			    }   
			    if (isNaN(dia) || parseInt(dia, 10)<1 || parseInt(dia, 10)>31)   
			    {   
					valido = false;	
			    }   
			    if (mes==4 || mes==6 || mes==9 || mes==11 || mes==2) 
			    {   
			        if (dia>30) 
			        {   
						valido = false;	
			        }   
			    }   
			    if (valido == false)
			    {
					Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+' el valor es inválido.');
					return '0';	
			    }
			    else
			    {
			    	return '1';
			    }  						
			break;
			
			case 'rellenar': // rellenar con ceros según la longitud
				total=0;
			    auxiliar=obj.value;
				longitud=obj.value.length;
				total=long-longitud;
				if (total <= long)
				{
					for (index=0;index<total;index++)
					{
					   auxiliar="0"+auxiliar;      
					}
					obj.value = auxiliar;
				}
				return 	obj.value;
			break;
		}
	}
}

/*********************************************************************
* @Función que muestra un mensaje de acuerdo al caso de la operación
* @Parámetros: tipo del mensaje, función a ejecutar,titulo,mensaje 
* @Valor de Retorno: 
* @Autor: Johny Porras
* @Fecha de Creación: 24/11/2008
********************************************************************
* @fecha modificación: 11/12/2008  
* @Descripción: 
* @autor: Gusmary Balza.                 
********************************************************************/
function obtenerMensaje(tipomensaje,funcion,titulo,mensaje)
{
	switch (tipomensaje)
	{
	 	case 'exito':	 	
			Ext.Msg.show({
			   	title:'Mensaje',
			   	msg: 'La operación se realizó de manera exitosa',
			   	buttons: Ext.Msg.OK,
			   	fn: funcion,
			   	animEl: 'elId',
			   	icon: Ext.MessageBox.INFO
			});
		break;
		
		case 'error':		
			Ext.Msg.show({
		   	title:'Mensaje',
		   	msg: 'Ocurrió un error al realizar la operación',
		   	buttons: Ext.Msg.OK,
		   	fn: funcion,
		   	animEl: 'elId',
		   	icon: Ext.MessageBox.ERROR
			});
		break;
		
		case 'confirmar':
			Ext.Msg.show({
		   	title:titulo,
		   	msg: mensaje,
		   	buttons: Ext.Msg.YESNO,
		   	fn: funcion,
		   	animEl: 'elId',
		   	icon: Ext.MessageBox.QUESTION	  	
		});
		
		case 'procesar':
			Ext.MessageBox.show({
	           msg: 'Por Favor Espere',
	           title: titulo,
	           width:200,
	           wait:true,
	           waitConfig:{interval:200},
	           animEl: 'mb7'	
       		});
	}
}


/*********************************************************************
* @Función que abre una ventana emergente
* @Parámetros: pagina 
* @Valor de Retorno: 
* @Autor: Ing. Gusmary Balza
* @Fecha de Creación: 15/05/2008
********************************************************************/
function abrirVentana(pagina)
{
	var opciones='toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=1000, height=800, top=10, left=10';
	window.open(pagina,'',opciones);
}


/*********************************************************************
* @Instancias para crear Checkbox dentro de un Grid
* @Parámetros:  
* @Valor de Retorno: 
* @Autor: Ing. Yesenia Moreno de Lang
* @Fecha de Creación: 04/11/2008
********************************************************************/
Ext.grid.CheckColumn = function(config){
    Ext.apply(this, config);
    if(!this.id){
        this.id = Ext.id();
    }
    this.renderer = this.renderer.createDelegate(this);
};

Ext.grid.CheckColumn.prototype ={
    init : function(grid){
        this.grid = grid;
        this.grid.on('render', function(){
            var view = this.grid.getView();
            view.mainBody.on('mousedown', this.onMouseDown, this);
        }, this);
    },

    onMouseDown : function(e, t){
        if(t.className && t.className.indexOf('x-grid3-cc-'+this.id) != -1){
            e.stopEvent();
            var index = this.grid.getView().findRowIndex(t);
            var record = this.grid.store.getAt(index);
            record.set(this.dataIndex, !record.data[this.dataIndex]);
        }
    },

    renderer : function(v, p, record){
        p.css += ' x-grid3-check-col-td'; 
        return '<div class="x-grid3-check-col'+(v?'-on':'')+' x-grid3-cc-'+this.id+'">&#160;</div>';
    }
};

