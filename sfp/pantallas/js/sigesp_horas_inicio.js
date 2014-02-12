
ruta  = '../../procesos/sigesp_horas_iniciopr.php';  
var panel = '';
Ext.onReady
(
	function()
	{	
		//Cargar los datos de las base de datos para asociarlos al combo.
		var datosNuevo={"raiz":[{'codbasedatos':'db_20008','basedatos':'PRueba de BD'}]};
		
				Ext.QuickTips.init();
		// turn on validation errors beside the field globally
		Ext.form.Field.prototype.msgTarget = 'side';

		Xpos=((screen.width/2)-(350/2)); 
		Ypos=((screen.height/2)-(500/2));

		//Panel con los componentes del formulario
		panel = new Ext.FormPanel({
        labelWidth: 75,
		height: 200,
     	title: 'Ingresar al Sistema',
		bodyStyle:'padding:10px 10px 0',
		style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
		defaults: {width: 300},		   
		items:[{
				xtype:'fieldset',
				id:'fsusuario',
				title:'Datos del Usuario',
				autoHeight:true,
				items:[{
					xtype:'textfield',
					fieldLabel:'Usuario',
					name:'usuario',
					id:'txtcodusuario',
					width:170
				  },{
					xtype:'textfield',
					fieldLabel:'Contraseña',
					name:'contraseña',
					inputType:'password',
					id:'txtpasusuario',
					width:170
				}],
			  },{
			  buttons: [{
            		text: 'Aceptar',
					style: 'position:absolute;left:70px',
					handler: irAceptar
        			},{
            		text: 'Cancelar',
					style: 'position:absolute;left:170px',
					handler: irCancelar
       		 	}]
			  },{
				xtype: 'label',
				text: 'SIGESP Registro de Horas',
				style: 'position:absolute;top:260px;left:90px;font-size:10px;font-family:Verdana, Arial, Helvetica, sans-serif;color:red',
			  }]
		});
		panel.render(document.body);		
				
		panel.getComponent('fsusuario').getComponent('txtpasusuario').on('blur',encriptar);


function encriptar()
{
	AuxEn = Encriptar(Ext.getCmp('txtpasusuario').getValue());
	Ext.getCmp('txtpasusuario').setValue(AuxEn);
	
}		


/** 	
*	
* @Función para limpiar todos los campos.     
* @autor: Ing. Gusmary Balza.
* @fecha creación: 01/08/2008.
*
*/	
	function irCancelar()
	{
		panel.getComponent('fsinicio').getComponent('cmbbasedatos').setValue('');
		panel.getComponent('fsinicio').getComponent('cmbempresa').setValue('');
		panel.getComponent('fsusuario').getComponent('txtcodusuario').setValue('');
		panel.getComponent('fsusuario').getComponent('txtpasusuario').setValue('');
	}


/** 	
*	
* @Función para iniciar la sesión.     
* @autor: Ing. Gusmary Balza.
* @fecha creación: 01/08/2008.
*
*/		
	function irAceptar()
	{
		val1 = validarObjetos('txtpasusuario','50','novacio');
		if((validarObjetos('txtcodusuario','20','novacio|alfanumerico')!='0') && val1!='0')
		{
			var objdata =
			{
				'oper': 'iniciarsesion', 
				'codusu': panel.getComponent('fsusuario').getComponent('txtcodusuario').getValue(),
				'pwdusu': panel.getComponent('fsusuario').getComponent('txtpasusuario').getValue(),
			};
			objdata=JSON.stringify(objdata);		
			parametros = 'ObjSon='+objdata; 
			Ext.Ajax.request({
				url : ruta,
				params : parametros,
				method: 'POST',
				success: function (resultad,request)
				{
					datos = resultad.responseText;
					alert(datos);
					Res = datos.split('|');
					if (Res[1]=='1')
					{
						location.href='sigesp_registro_horas.php';
					}
					else
					{
						Ext.MessageBox.alert('Mensaje','Acceso denegado,consulte con su administrador de sistema');
					}
				},
				failure: function (result,request) 
				{ 
					Ext.MessageBox.alert('Error', 'No se pudo iniciar sesion'); 
				}					
			});	 
	
		}
	}

});	//fin del function principal





	
