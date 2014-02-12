/****************************************************************************************
* @Proceso de cambio de password.
* @Archivo javascript el cual contiene los componentes del proceso de cambio de password
* evaluando si es usuario administrador o no.
* @versión: 1.0      
* @creado: 18/08/2008
* @autor: Ing. Gusmary Balza 
****************************************************************
* @fecha modificacion: 29/10/2008
* @descripcion: Adaptar a los nuevos estandares
* @autor: Ing. Gusmary Balza
****************************************************************************************/
var panel = '';
var pantalla = 'cambiopasssword';
var administrador = null;
ruta =  '../../controlador/sss/sigesp_ctr_sss_cambiopassword.php'; 
Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		Ext.form.Field.prototype.msgTarget = 'side';		
		
		function revisarUsuario()
		{
			var objdata ={
				'oper': 'revisarUsuario',
				'sistema': sistema,
				'vista': vista
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
					if (datajson.raiz[0].existe==true)  
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
			labelWidth: 120,
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
					style:'position:absolute;left:245px;top:28px',
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

/******************************************************************************
* @ Función para verificar que la contraseña se haya escrito correctamente
* @parametros 
* @retorno
* @fecha creación: 22/07/2008
* @autor: Ing. Gusmary Balza
**************************************************
* @fecha modificacion: 29/10/2008
* @descripcion: Adaptar a los nuevos estandares
* @autor: Ing. Gusmary Balza
*******************************************************************************/			
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
				
				
/*****************************************************************************
* @Limpiar campos del formulario
* @parametros 
* @retorno
* @fecha creación 
* @autor 
****************************************************************
* @fecha modificacion: 
* @descripcion: 
* @autor: 
*******************************************************************************/		
	function irCancelar() 
	{
		 Ext.getCmp('txtcodusuario').setValue('');
		 Ext.getCmp('txtpassword').setValue('');
		 Ext.getCmp('txtnuevopassword').setValue('');
		 Ext.getCmp('txtverpassword').setValue('');
	}
			
	
/******************************************************************************
* @Función para buscar un usuario.
* @parametros
* @retorno
* @fecha de creación: 18/08/2008
* @autor: Gusmary Balza.
***************************************************************
* @fecha modificacion: 
* @descripcion: 
* @autor: 
******************************************************************************/
	function irBuscar()
	{
		var arreglotxt = new Array('txtcodusuario');		
		var arreglovalores = new Array('codusu');			
		objCatusuario = new catalogoUsuario();
		objCatusuario.mostrarCatalogo(panel,'fsformpassword',arreglotxt, arreglovalores);
	}
	
	
/*******************************************************************************
* @Función para guardar el cambio de password.
* @parametros
* @retorno
* @fecha de creación: 18/08/2008
* @autor: Gusmary Balza.
***************************************************************
* @fecha modificacion: 
* @descripcion: 
* @autor: 
*******************************************************************************/	
	function irGuardar()
	{
		continuar = false;
		valido=true;
		if((!tbactualizar)&&(cambiar))
		{
			valido=false;
			Ext.MessageBox.alert('Error','No tiene permiso para Modificar.');
		}
		if (!administrador)
		{
			if (validarObjetos('txtpassword','50','novacio')!='0' && validarObjetos('txtnuevopassword','50','novacio')!='0' && validarObjetos('txtverpassword','50','novacio')!='0')
			{				
				var passwordactual = Ext.getCmp('txtpassword').getValue();
				passwordactual = 'sigesp'+passwordactual;
				Ext.getCmp('txtpassword').setValue(b64_sha1(passwordactual));
				
				var password = Ext.getCmp('txtnuevopassword').getValue();
				password = 'sigesp'+password;
				Ext.getCmp('txtnuevopassword').setValue(b64_sha1(password));
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
				password = 'sigesp'+password;
				Ext.getCmp('txtnuevopassword').setValue(b64_sha1(password));
				continuar = true;			
				var objdata ={
					'oper': 'actualizar',
					'administrador': administrador,
					'codusu': Ext.getCmp('txtcodusuario').getValue(), 
					'nuevopassword':   Ext.getCmp('txtnuevopassword').getValue(),
					'sistema': sistema,
					'vista': vista
				};
			}
		}
		if (continuar)
		{
			obtenerMensaje('procesar','','Guardando Datos');
			
			objdata=JSON.stringify(objdata);
			parametros = 'objdata='+objdata; 
			Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function (resultad, request)
			{ 
				datos = resultad.responseText;
				Ext.Msg.hide();
				var datajson = eval('(' + datos + ')');
				if (datajson.raiz.valido==true)
				{
					Ext.MessageBox.alert('Mensaje',datajson.raiz.mensaje);
					irCancelar();  
				}
				else
				{
					Ext.MessageBox.alert('Mensaje', datajson.raiz.mensaje);
					
				}
			},
			failure: function (result,request) 
			{ 
				Ext.Msg.hide();
				Ext.MessageBox.alert('Error', 'El registro no se pudo incluir'); 
			}					
			});
		}
	}
	
	
