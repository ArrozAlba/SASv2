/****************************************************************
* @Proceso de cambio de password.
* @Archivo javascript el cual contiene los componentes 
* @del proceso de cambio de password
* evaluando si es usuario administrador o no.
* @versión: 1.0      
* @creado: 18/08/2008
* @autor: Ing. Gusmary Balza 
****************************************************************
* @fecha modificacion
* @autor 
* @descripcion
*****************************************************************/
var panel = '';
ruta =  '../../controlador/msg/sigesp_ctr_msg_cambiopassword.php'; 
pantalla = 'cambiopasssword';
var administrador = null;
Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		// turn on validation errors beside the field globally
		Ext.form.Field.prototype.msgTarget = 'side';		
		
		function revisarUsuario()
		{
			//codgrupo = Ext.getCmp('txtcodgrupo').getValue();
			var objdata ={
					'oper': 'revisarUsuario',
			};
			objdata=JSON.stringify(objdata);
			parametros = 'objdata='+objdata;
			Ext.Ajax.request({
				url : ruta,
				params : parametros,
				method: 'POST',
				success: function (resultado,request)
				{
					datos = resultado.responseText;
					var datajson = eval('(' + datos + ')');
					if (datajson.raiz.existe==true)  //es administrador
					{
						Ext.getCmp('txtpassword').disable();
						administrador = true;
					}
					else
					{
						Ext.getCmp('btnBuscar').disable();
						administrador = false;
					}
				},
				failure: function (result,request) 
				{ 
					Ext.MessageBox.alert('Error', 'No se pudo verificar el usuario'); 
				}					
			});
		}	
		revisarUsuario();
				
				
		Xpos = ((screen.width/2)-(400/2)); 
		Ypos = ((screen.height/2)-(500/2));
		panel = new Ext.FormPanel({
			title: 'Cambio de Password',
			bodyStyle:'padding:5px 5px 5px',
			style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
			tbar: [],
			items:[{
				xtype:'fieldset',
				title:'Datos del Usuario',
				id:'fsformpassword',
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',		
				items:[{
					xtype:'textfield',
					fieldLabel:'Usuario',
					name:'usuario',
					id:'txtcodusuario',
					disabled:true,
					width:100
				  },{
					xtype:'button',
					id:'btnBuscar',
					handler: irBuscar,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar un usuario',
					style:'position:absolute;left:225px;top:28px',
					width:50					  
				  },{
					xtype:'textfield',
					fieldLabel:'Contraseña Actual',
					name:'password actual',
					id:'txtpassword',
					inputType:'password',
					width:200					  
				  },{
					xtype:'textfield',
					fieldLabel:'Nueva Contraseña',
					name:'nuevo password',
					id:'txtnuevopassword',
					inputType:'password',
					width:200
				 },{
					xtype:'textfield',
					fieldLabel:'Verificar Contraseña',
					name:'verificar password',
					id:'txtverpassword',
					inputType:'password',
					width:200
				}]
			}]
		});
	panel.render(document.body);
	
	Ext.getCmp('txtverpassword').on('blur',verificar);	
	
}); //fin de archivo	

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
		var password    = Ext.getCmp('txtnuevopassword').getValue(); 
		var verpassword = Ext.getCmp('txtverpassword').getValue();
		if ((password)!=(verpassword))
		{
			Ext.MessageBox.alert('Mensaje','Las contraseñas no coinciden');			
			Ext.getCmp('txtnuevopassword').focus(true, true);
			Ext.getCmp('txtnuevopassword').setValue('');
			Ext.getCmp('txtverpassword').setValue('');
		}	
	}
	
			
				
/************************************
* @Limpiar campos del formulario
* @parametros 
* @retorno
* @fecha creación 
* @autor 
****************************************/		
	function limpiarCampos() 
	{
		 Ext.getCmp('txtcodusuario').setValue('');
		 Ext.getCmp('txtpassword').setValue('');
		 Ext.getCmp('txtnuevopassword').setValue('');
		 Ext.getCmp('txtverpassword').setValue('');
	}
			
	
/**********************************************
* @Función para cancelar el cambio de password.
* @parametros
* @retorno
* @fecha de creación: 18/08/2008
* @autor: Gusmary Balza.
*********************************************/
	function irCancelar()
	{
		limpiarCampos();
	}
	
	
/**********************************************
* @Función para buscar un usuario.
* @parametros
* @retorno
* @fecha de creación: 18/08/2008
* @autor: Gusmary Balza.
*********************************************/
	function irBuscar()
	{
		var arreglotxt = new Array('txtcodusuario');		
		var arreglovalores = new Array('codusuario');			
		objCatusuario = new catalogoUsuario();
		objCatusuario.mostrarCatalogo(panel,'fsformpassword',arreglotxt, arreglovalores);
	}
	
	
/**********************************************
* @Función para guardar el cambio de password.
* @parametros
* @retorno
* @fecha de creación: 18/08/2008
* @autor: Gusmary Balza.
*********************************************/	
	function irGuardar()
	{
		continuar = false;
		if (!administrador)
		{
			if (validarObjetos('txtpassword','50','novacio')!='0' && validarObjetos('txtnuevopassword','50','novacio')!='0' && validarObjetos('txtverpassword','50','novacio')!='0')
			{
				var passwordactual = Ext.getCmp('txtpassword').getValue();
				passwordactual = "sigesp"+passwordactual;
				passwordactual = Ext.getCmp('txtpassword').setValue(b64_sha1(passwordactual));
				
				var password = Ext.getCmp('txtnuevopassword').getValue();
				password = "sigesp"+password;
				password = Ext.getCmp('txtnuevopassword').setValue(b64_sha1(password));
				continuar = true;
				var objdata ={
						'oper': 'actualizar',
						'administrador': administrador,
						'password': Ext.getCmp('txtpassword').getValue(), 
						'nuevopassword': Ext.getCmp('txtnuevopassword').getValue(),
						'sistema': sistema,
						'vista': vista
				};
			}
		}
		else
		{			
			if (validarObjetos('txtcodusuario','20','novacio')!='0' && validarObjetos('txtnuevopassword','50','novacio')!='0' && validarObjetos('txtverpassword','50','novacio')!='0')
			{
				var password = Ext.getCmp('txtnuevopassword').getValue();
				password = "sigesp"+password;
				Ext.getCmp('txtnuevopassword').setValue(b64_sha1(password));
				continuar = true;			
				var objdata ={
					'oper': 'actualizar',
					'administrador': administrador,
					'codusuario': Ext.getCmp('txtcodusuario').getValue(), 
					'nuevopassword':   Ext.getCmp('txtnuevopassword').getValue(),
					'sistema': sistema,
					'vista': vista
				};
			}
		}
		mensaje = 'actualizado';
		
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
					Ext.MessageBox.alert('Mensaje','Password '+mensaje + ' con éxito');
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
	
	
