/**************************************************************
* @Definición de Usuario
* @Archivo javascript el cual contiene todos los componentes 
* @de la Definición de Usuario
* @versión: 1.0      
* @fecha de creación: 30/06/2008
* @autor: Ing. Gusmary Balza  
*************************************************************
* @fecha modificacion
* @autor 
* @descripcion: 
****************************************************************/
var panel = '';
var actualizar=false;
var pantalla='usuario';
ruta='../../controlador/msg/sigesp_ctr_msg_usuario.php';
Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		// turn on validation errors beside the field globally
		Ext.form.Field.prototype.msgTarget = 'side';
					
		//Cargar los datos de los estatus para asociarlos al combo. 
		var datosEstatus={"raiz":[{'codestatus':'1','estatus':'Activo'},{'codestatus':'2','estatus':'Suspendido'},{'codestatus':'3','estatus':'Bloqueado'}]};
		
		record = Ext.data.Record.create([
			{name: 'codestatus'},     
			{name: 'estatus'}
		]);					
		dsestatus =  new Ext.data.Store({
				proxy: new Ext.data.MemoryProxy(datosEstatus),
				reader: new Ext.data.JsonReader(
				{
					root: 'raiz',               
					id: 'id'   
				},
				record
				),
				data: datosEstatus			
			 });
	
		Xpos = ((screen.width/2)-(600/2)); 
		Ypos = ((screen.height/2)-(650/2));
		panel = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
     	title: 'Definición de Usuarios',
        bodyStyle:'padding:5px 5px 0',
		style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
		width:600,
       	tbar: [],
        defaults: {width: 230},		   
		items:[
			 {
				xtype:'fieldset',
				title:'Datos del Usuario',
				id:'fsformusuario',
    			autoHeight:true,
				autoWidth:true,
				cls :'fondo',	
			    items:[{
				xtype:'textfield',
				fieldLabel:'Usuario',
				name:'código del usuario',
				id:'txtcodusuario',
				width:150
			  },{
				xtype:'textfield',
				fieldLabel:'Cédula',
				name:'cédula',
				id:'txtcedula',
				width:80
			  },{
				xtype:'textfield',
				fieldLabel:'Nombre',
				name:'nombre',
				id:'txtnombre',
				width:250
			  },{
				xtype:'textfield',
				fieldLabel:'Apellido',
				name:'apellido',
				id:'txtapellido',
				width:250,
			 }/*,{
				 xtype: 'fileuploadfield',
            	id: 'form-file',
            emptyText: 'Select an image',
            fieldLabel: 'Photo',
            name: 'photo-path',
            buttonCfg: {
                text: '',
                iconCls: 'upload-icon'
            }
			  }*/,{
				xtype:'textfield',
				fieldLabel:'Contraseña',
				name:'contraseña',
				id:'txtpassword',
				inputType:'password',
				width:200
			  },{
				xtype:'textfield',
				fieldLabel:'Verificar',
				name:'verificar',
				id:'txtverpassword',
				inputType:'password',
				width:200
			},{
				xtype:'textfield',
				fieldLabel:'Teléfono',
				name:'telefono',
				id:'txttelefono',
				width:100
			},{
			  	xtype:'label',
				text: 'Formato: 5555-5555555',
				style:'position:absolute;left:200px;top:190px;font-size:12px;font-family:Verdana, Arial, Helvetica, sans-serif'
			  
			},{
				xtype:'textfield',
				fieldLabel:'E-mail',
				name:'email',
				id:'txtemail',
				width:350,
				vtype:'email'
			  },{
				xtype:'textfield', 
        	//	fieldLabel:'Últ ingreso',
				hideLabel: true,
        		name:'fecha ingreso',
        		id:'txtultingreso',
				readOnly: true,
				disabled:true,
				style:'position:absolute;left:330px;top:5px',
				width:100
			  },{
			  	xtype:'label',
				text: 'Fecha último ingreso:',
				style:'position:absolute;left:210px;top:245px;font-size:12px;font-family:Verdana, Arial, Helvetica, sans-serif'
			  
			  },{
				xtype:'combo',
			  	fieldLabel:'Estatus',
				name:'estatus',
				id:'cmbestatus',
				emptyText:'Seleccione',
				displayField:'estatus',
				valueField:'codestatus',
				typeAhead: true,
				mode: 'local',
				triggerAction: 'all',
				store: dsestatus,
				width:100			  
			  },{
			  	xtype:'checkbox',
				fieldLabel:'Administrador',
				name:'administrador',
        		id:'chbadmin',
			  },{
				xtype:'textfield',
				fieldLabel:'Nota',
				name:'nota',
				id:'txtnota',
				width:450
			  },{
			  	xtype:'panel',
				id:'foto',
				name:'foto',
				contentEl:'divfoto',
				width:100,
				height:100,
				style:'position:absolute;left:400px;top:30px',
			  }]
			 }]
		 
		});
		panel.render(document.body);			
		panel.getComponent('fsformusuario').getComponent('txtverpassword').on('blur',verificar);	
	
});	//fin del archivo
	
		
/********************************************
* @ Función para verificar que la contraseña 
* @ se haya escrito correctamente dos veces.
* @parametros 
* @retorno
* @fecha creación: 22/07/2008
* @autor: Ing. Gusmary Balza
*********************************************/			
function verificar()
{			
	var pasusuario    = panel.getComponent('fsformusuario').getComponent('txtpassword').getValue(); 
	var verpasusuario = panel.getComponent('fsformusuario').getComponent('txtverpassword').getValue();
	if ((pasusuario)!=(verpasusuario))
	{
		Ext.MessageBox.alert('Mensaje','Las contraseñas no coinciden');			
		panel.getComponent('fsformusuario').getComponent('txtpassword').focus(true, true);
		panel.getComponent('fsformusuario').getComponent('txtpassword').setValue('');
		panel.getComponent('fsformusuario').getComponent('txtverpassword').setValue('');
	}	
}


/************************************
* @Limpiar campos del formulario
* @parametros 
* @retorno
* @fecha creación: 21/07/2008
* @autor: Ing. Gusmary Balza
****************************************/				
function limpiarCampos()
{
	panel.getComponent('fsformusuario').getComponent('txtcodusuario').setValue('');
	panel.getComponent('fsformusuario').getComponent('txtcedula').setValue('');
	panel.getComponent('fsformusuario').getComponent('txtnombre').setValue('');
	panel.getComponent('fsformusuario').getComponent('txtapellido').setValue('');
//	panel.getComponent('fsformusuario').getComponent('txtfotusuario').setValue('');
	panel.getComponent('fsformusuario').getComponent('txtpassword').setValue('');
	panel.getComponent('fsformusuario').getComponent('txtverpassword').setValue('');
	panel.getComponent('fsformusuario').getComponent('txttelefono').setValue('');
	panel.getComponent('fsformusuario').getComponent('txtemail').setValue('');
	panel.getComponent('fsformusuario').getComponent('txtultingreso').setValue('');
	panel.getComponent('fsformusuario').getComponent('cmbestatus').setValue('Seleccione');
	panel.getComponent('fsformusuario').getComponent('chbadmin').setValue(0);
	panel.getComponent('fsformusuario').getComponent('txtnota').setValue('');
	
}
	
	
/*********************************************************
* @Función que limpia los campos y asigna un nuevo código
* @parametros
* @retorno
* @fecha de creación: 21/07/2008
* @autor: Ing. Gusmary Balza.
*********************************
* @fecha modificacion
* @autor 
* @descripcion: 
*********************************************************/		
function irNuevo(item)
{
	limpiarCampos();
	panel.getComponent('fsformusuario').getComponent('txtcodusuario').enable(),
	panel.getComponent('fsformusuario').getComponent('txtpassword').enable();
	panel.getComponent('fsformusuario').getComponent('txtverpassword').enable();
	actualizar = false;
}
	
	
/*********************************************************
* @Función que guarda o actualiza los datos de un usuario.
* @parametros
* @retorno
* @fecha de creación: 21/07/2008
* @autor: Ing. Gusmary Balza.
***************************
* @fecha modificacion
* @autor 
* @descripcion
*********************************************************/
function irGuardar(item)
{		
	valido = true;
	continuar = false;
	if ((!tbnuevo)&&(!actualizar))
	{
		valido=false;
		Ext.MessageBox.alert('Error','No tiene permiso para Incluir.');
	}
	if ((!tbactualizar)&&(actualizar))
	{
		valido = false;
		Ext.MessageBox.alert('Error','No tiene permiso para Modificar.');
	}
	if (!actualizar)
	{							
		var pasusuario = panel.getComponent('fsformusuario').getComponent('txtpassword').getValue();
		pasusuario = "sigesp"+pasusuario;
		panel.getComponent('fsformusuario').getComponent('txtpassword').setValue(b64_sha1(pasusuario));	
		if ((validarObjetos('txtcodusuario','50','novacio|alfanumerico')!='0' && validarObjetos('txtcedula','8','novacio|numero')!='0' && validarObjetos('txtnombre','50','novacio|nombre')!='0' && validarObjetos('txtapellido','50','novacio|nombre')!='0' && validarObjetos('txtpassword','50','novacio')!='0' && validarObjetos('txttelefono','50','vaciotelefono')!='0' && validarObjetos('txtemail','100','vacioemail')!='0' && validarObjetos('cmbestatus','15','novacio')!='0'&& validarObjetos('txtnota','2000','alfanumerico')!='0') && (valido))
		{   
			continuar = true;
			evento ='incluir';
			mensaje = 'Incluido';
			
			var objdata ={
			'oper': evento, 
			'codusuario': 	 panel.getComponent('fsformusuario').getComponent('txtcodusuario').getValue(), 
			'cedula':     	 panel.getComponent('fsformusuario').getComponent('txtcedula').getValue(),
			'nombre':     	 panel.getComponent('fsformusuario').getComponent('txtnombre').getValue(),
			'apellido':   	 panel.getComponent('fsformusuario').getComponent('txtapellido').getValue(),
		//	'foto':       	 panel.getComponent('fsformusuario').getComponent('txtfotusuario').getValue(), //todavia no
			'password':      panel.getComponent('fsformusuario').getComponent('txtpassword').getValue(),
			'telefono':      panel.getComponent('fsformusuario').getComponent('txttelefono').getValue(),
			'email':         panel.getComponent('fsformusuario').getComponent('txtemail').getValue(),
			'estatus':		 panel.getComponent('fsformusuario').getComponent('cmbestatus').getValue(),
			'administrador': panel.getComponent('fsformusuario').getComponent('chbadmin').getValue(), 
			'nota':          panel.getComponent('fsformusuario').getComponent('txtnota').getValue(),
			'sistema': sistema,
			'vista': vista
			};
		}
	}
	else
	{	
		if ((validarObjetos('txtcodusuario','50','novacio|alfanumerico')!='0' && validarObjetos('txtcedula','8','novacio|numero')!='0' && validarObjetos('txtnombre','50','novacio|nombre')!='0' && validarObjetos('txtapellido','50','novacio|nombre')!='0' && validarObjetos('txttelefono','50','vaciotelefono')!='0' && validarObjetos('txtemail','100','vacioemail')!='0' && validarObjetos('cmbestatus','15','novacio')!='0'&& validarObjetos('txtnota','2000','alfanumerico')!='0') && (valido))
		{   
			continuar = true;
			evento ='actualizar';			
			mensaje = 'Modificado';			
								
			var objdata ={
			'oper': evento, 
			'codusuario': 	 panel.getComponent('fsformusuario').getComponent('txtcodusuario').getValue(), 
			'cedula':     	 panel.getComponent('fsformusuario').getComponent('txtcedula').getValue(),
			'nombre':     	 panel.getComponent('fsformusuario').getComponent('txtnombre').getValue(),
			'apellido':   	 panel.getComponent('fsformusuario').getComponent('txtapellido').getValue(),
		//	'foto':       	 panel.getComponent('fsformusuario').getComponent('txtfotusuario').getValue(), //todavia no
			'telefono':      panel.getComponent('fsformusuario').getComponent('txttelefono').getValue(),
			'email':         panel.getComponent('fsformusuario').getComponent('txtemail').getValue(),
			'estatus':		 panel.getComponent('fsformusuario').getComponent('cmbestatus').getValue(),
			'administrador': panel.getComponent('fsformusuario').getComponent('chbadmin').getValue(), 
			'nota':          panel.getComponent('fsformusuario').getComponent('txtnota').getValue(),
			'sistema': 'MSG',
			'vista': 'sigesp_vis_msg_usuario.html'
			};
		}
	}
	if (continuar)
	{
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
		url : ruta,
		params : parametros,
		method: 'POST',
		success: function (resultad, request)
		{ 
			datos = resultad.responseText;
			var datajson = eval('(' + datos + ')');
			if (datajson.raiz.valido==true)
			{
				Ext.MessageBox.alert('Mensaje','Registro '+mensaje + ' con éxito');
				limpiarCampos();  
			}
			else
			{
				Ext.MessageBox.alert('Mensaje', datajson.raiz.mensaje);
				limpiarCampos();
			}
		},
		failure: function (result,request) 
		{ 
			Ext.MessageBox.alert('Error', 'El registro no se pudo incluir'); 
		}					
		});
	}
}



/************************************************
* @Función que elimina un usuario seleccionado.
* @parametros
* @retorno
* @fecha de creación: 21/07/2008
* @autor: Ing.Gusmary Balza.
***************************
* @fecha modificacion
* @autor 
* @descripcion
************************************************/	
function irEliminar(item)
{
	var Result;
	Ext.MessageBox.confirm('Confirmar', '¿Desea eliminar este registro?', Result);
	function Result(btn)
	{
		if(btn=='yes')
		{ 
			if(validarObjetos('txtcodusuario','20','novacio')=='0')
			{					
				Ext.MessageBox.alert('Mensaje','No existen datos para eliminar');
			}
			else
			{
				var objdata ={
					'oper': 'eliminar', 
					'codusuario': 	 panel.getComponent('fsformusuario').getComponent('txtcodusuario').getValue(), 
					'cedula':     	 panel.getComponent('fsformusuario').getComponent('txtcedula').getValue(),
					'nombre':     	 panel.getComponent('fsformusuario').getComponent('txtnombre').getValue(),
					'apellido':   	 panel.getComponent('fsformusuario').getComponent('txtapellido').getValue(),
				//	'foto':       	 panel.getComponent('fsformusuario').getComponent('txtfotusuario').getValue(),
					'passwword':     panel.getComponent('fsformusuario').getComponent('txtpassword').getValue(),
					'telefono':      panel.getComponent('fsformusuario').getComponent('txttelefono').getValue(),
					'email':         panel.getComponent('fsformusuario').getComponent('txtemail').getValue(),
					'fecultingreso': panel.getComponent('fsformusuario').getComponent('txtultingreso').getValue(),
					'estatus':		 panel.getComponent('fsformusuario').getComponent('cmbestatus').getValue(),
					'administrador': panel.getComponent('fsformusuario').getComponent('chbadmin').getValue(),
					'nota':          panel.getComponent('fsformusuario').getComponent('txtnota').getValue(),
					'sistema': 'MSG',
					'vista': 'sigesp_vis_msg_usuario.html'
				 };	
				 
				objdata=JSON.stringify(objdata);
				parametros = 'objdata='+objdata;				     
				mensaje = 'Eliminado';
				Ext.Ajax.request({
				url : ruta,
				params : parametros,
				method: 'POST',
				success: function ( resultad, request )
				{ 
					datos = resultad.responseText;
					var datajson = eval('(' + datos + ')');
					if (datajson.raiz.valido==true)						
					{
						Ext.MessageBox.alert('Mensaje','Registro '+mensaje + ' con éxito');
						limpiarCampos();		  
					}
					else
					{
						Ext.MessageBox.alert('Error', datajson.raiz.mensaje);
					}
				},
				failure: function ( result, request)
				{ 
					Ext.MessageBox.alert('Error', result.responseText); 
				} 
				});
			}
		}
	};		
}


/******************************************************
* @Función que llama al catalogo para mostrar 
* los datos de los usuarios.
* @parametros
* @retorno
* @fecha de creación: 21/07/2008
* @autor: Ing. Gusmary Balza.
***************************
* @fecha modificacion
* @autor 
* @descripcion
*****************************************************/
function irBuscar(item)
{
	var arreglotxt = new Array('txtcodusuario','txtcedula','txtnombre','txtapellido','txttelefono','txtemail','txtultingreso','cmbestatus','chbadmin','txtnota');
	
	var arreglovalores = new Array('codusuario','cedula','nombre','apellido','telefono','email','fecultingreso','estatus','administrador','nota');
		
	objCatusuario = new catalogoUsuario();
	objCatusuario.mostrarCatalogo(panel,'fsformusuario',arreglotxt, arreglovalores);
	actualizar = true;
	
	panel.getComponent('fsformusuario').getComponent('txtcodusuario').disable();
	panel.getComponent('fsformusuario').getComponent('txtpassword').disable();
	panel.getComponent('fsformusuario').getComponent('txtverpassword').disable();
}


/*****************************************************
*Función que imprime un reporte ficha de un usuario 
seleccionado de acuerdo a un archivo Xml generado.
*@parámetros: 
*@retorna: 
*@fecha de creación:  21/07/2008
*@Autor: Gusmary Balza.	
***************************
* @fecha modificacion
* @autor 
* @descripcion
******************************************************/	
function irImprimir(item)
{
	var objdata ={
		"oper": 'reporteficha',
		"codusuario": panel.getComponent('fsformusuario').getComponent('txtcodusuario').getValue(),
		'sistema': 'MSG',
		'vista': 'sigesp_vis_msg_usuario.html'
			
	}
	objdata=JSON.stringify(objdata);
	parametros = 'objdata='+objdata; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado,request)
	{
		datos = resultado.responseText;
		if(datos!='')
		{
			abrirVentana(datos);
		}			
		else
		{
			Ext.MessageBox.alert('Mensaje', 'No existen datos para imprimir');		
		}
	/*	if(validarObjetos('txtcodcon','novacio')=='0' || validarObjetos('txtnomcon','novacio')=='0')
			{					
				Ext.MessageBox.alert('Mensaje','No existen datos para imprimir');
			}
			else
			{
				abrirVentana(datos);
			}*/
	},
	failure: function ( result, request) 
	{ 
		Ext.MessageBox.alert('Error', result.responseText); 
	} 
	});				
}


		
		
		