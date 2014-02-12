ruta  = 'controlador/msg/sigesp_ctr_msg_inicio.php';  
dsbasedatos = '';
dsempresa = '';
var panel = '';
Ext.onReady
(
	function()
	{	
		//Cargar los datos de las base de datos para asociarlos al combo.
		var datosNuevo={"raiz":[{'codbasedatos':'db_20008','basedatos':'PRueba de BD'}]};
		
		record = Ext.data.Record.create([
			{name: 'codbasedatos'},     
			{name: 'basedatos'}
		]);					
		dsbasedatos =  new Ext.data.Store({
				proxy: new Ext.data.MemoryProxy(datosNuevo),
				reader: new Ext.data.JsonReader(
				{
					root: 'raiz',               
					id: 'id'   
				},
				record
				),
				data: datosNuevo			
			 });		
		
		//Cargar los datos de las empresas para asociarlos al combo. 
		var datosEmpresa={"raiz":[{'codempresa':'01','nombre':'Ejemplo'}]};
		
		record = Ext.data.Record.create([
			{name: 'codempresa'},     
			{name: 'nombre'}
		]);					
		dsempresa =  new Ext.data.Store({
				proxy: new Ext.data.MemoryProxy(datosEmpresa),
				reader: new Ext.data.JsonReader(
				{
					root: 'raiz',               
					id: 'id'   
				},
				record
				),
				data: datosEmpresa			
			 });
	

		Ext.QuickTips.init();
		// turn on validation errors beside the field globally
		Ext.form.Field.prototype.msgTarget = 'side';

		Xpos=((screen.width/2)-(350/2)); 
		Ypos=((screen.height/2)-(500/2));

		//Panel con los componentes del formulario
		panel = new Ext.FormPanel({
        labelWidth: 75,
		height: 300,
     	title: 'Ingresar al Sistema',
		bodyStyle:'padding:10px 10px 0',
		style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
		defaults: {width: 300},		   
		items:[{
				xtype:'fieldset',
				title:'Datos de la Empresa',
				id:'fsinicio',
				autoHeight:true,
				items:[{
					xtype:'combo',
					fieldLabel:'Base de Datos',
					name:'base de datos',
					id:'cmbbasedatos',
					emptyText:'Seleccione',
					displayField:'basedatos',
					valueField:'basedatos',
					typeAhead: true,
					mode: 'local',
					triggerAction: 'all',
					store: dsbasedatos,
					width:170
				  },{
					xtype:'combo',
					fieldLabel:'Empresa',
					name:'empresa',
					id:'cmbempresa',
					emptyText:'Seleccione',
					displayField:'nombre',
					valueField:'codpais',
					typeAhead: true,
					mode: 'local',
					triggerAction: 'all',
					store: dsempresa,
					width:170
				}]
			  },{
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
				text: 'SIGESP versión PHP/AJAX',
				style: 'position:absolute;top:260px;left:90px;font-size:10px;font-family:Verdana, Arial, Helvetica, sans-serif;color:red',
			  }]
		});
		panel.render(document.body);		
				
		panel.getComponent('fsusuario').getComponent('txtpasusuario').on('blur',encriptar);


/** 	
*	
* @Función para encriptar la contraseña del usuario.     
* @autor: Ing. Gusmary Balza.
* @fecha creación: 01/08/2008.
*
*/	
	function encriptar()
	{			
		if (validarObjetos('txtpasusuario','50','novacio')!='0')
		{
			var pasusuario = panel.getComponent('fsusuario').getComponent('txtpasusuario').getValue();
			pasusuario = "sigesp"+pasusuario;
			panel.getComponent('fsusuario').getComponent('txtpasusuario').setValue(b64_sha1(pasusuario));
		}
	}
		
/** 	
*	
* @Función para llenar el combo de base de datos.     
* @autor: Ing. Yesenia de Lang.
* @fecha creación: 31/07/2008.
*
*/			
	function irBasedatos()
	{
		var objdata ={'operacion': 'obtenerbd'};
		
		objdata=JSON.stringify(objdata);
		
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function (resultad,request)
			{
				datos = resultad.responseText;
				var datajson = eval('(' + datos + ')');
				if (datajson.raiz!=null)
				{
					dsbasedatos.loadData(datajson);
				}
			},
			failure: function ( result, request)
			{ 
				Ext.MessageBox.alert('Error', result.responseText); 
			},
			
		});	
		panel.getComponent('fsinicio').getComponent('cmbbasedatos').addListener('select',buscarEmpresas);
	}
	
	
/** 	
*	
* @Función para buscar las empresas de acuerdo a la base de datos seleccionada.     
* @autor: Ing. Gusmary Balza.
* @fecha creación: 01/08/2008.
*
*/		
	function buscarEmpresas()
		{
			valorBd=panel.getComponent('fsinicio').getComponent('cmbbasedatos').getValue();
			if (panel.getComponent('fsinicio').getComponent('cmbempresa').getValue()=='')
			{
				irEmpresa(valorBd);	
			}
			else
			{
				actualizarDatos(valorBd);
			}
		}
	
	
/** 	
*	
* @Función para llenar el combo de las empresas.     
* @autor: Ing. Gusmary Balza.
* @fecha creación: 01/08/2008.
*
*/		
	function irEmpresa(bd)
	{		
		var objdata ={'operacion': 'obtenerempresa','basedatos':bd};		
		objdata=JSON.stringify(objdata);		
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function (resultad,request)
			{
				datos = resultad.responseText;
				var datajson = eval('(' + datos + ')');
				if (datajson.raiz!=null)
				{
					dsempresa.loadData(datajson);
				}
			},
			failure: function ( result, request)
			{ 
				Ext.MessageBox.alert('Error', result.responseText); 
			}
		});	 
	}

/** 	
*	
* @Función para actualizar las empresas al cambiar de base de datos.     
* @autor: Ing. Gusmary Balza.
* @fecha creación: 01/08/2008.
*
*/	
	function actualizarDatos(cod) //revisar
	{
		datosEnblanco = {"raiz":[{'codbasedatos':'db_2008','basedatos':'Prueba de BD','codempresa':'','nombre':''}]};
		
		var objdata ={"operacion": 'obtenerempresa',"cod": cod};	
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function (resultado, request)
			{ 
				 datos = resultado.responseText;
				 alert(datos);
				 if(datos!='')
				 {
					var datajson = eval('(' + datos + ')');	
					if(datajson.raiz==null)
					{
						 datajson={"raiz":[{'codbasedatos':'db_20008','basedatos':'PRueba de BD','codempresa':'','nomempresa':''}]};
					}	
					panel.getComponent('fsinicio').getComponent('cmbempresa').clearValue();
					//cmbempresa.store.loadData(datajson);
					panel.getComponent('fsinicio').getComponent('cmbempresa').store.loadData(datajson);
				}
			}
		});
	}


irBasedatos();	

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
		if ((validarObjetos('cmbbasedatos','100','novacio')!='0') && (validarObjetos('cmbempresa','100','novacio')!='0') && (validarObjetos('txtcodusuario','20','novacio|alfanumerico')!='0') /*&& (validarObjetos('txtpasusuario','novacio')!='0')*/)
		{
			var objdata ={
				'operacion': 'iniciarsesion', 
				'basedatos': panel.getComponent('fsinicio').getComponent('cmbbasedatos').getValue(), 
				'codempresa': panel.getComponent('fsinicio').getComponent('cmbempresa').getValue(), 
				'codusuario': panel.getComponent('fsusuario').getComponent('txtcodusuario').getValue(),
				'pasusuario': panel.getComponent('fsusuario').getComponent('txtpasusuario').getValue(),
			};
			objdata=JSON.stringify(objdata);		
			parametros = 'objdata='+objdata; 
			Ext.Ajax.request({
				url : ruta,
				params : parametros,
				method: 'POST',
				success: function (resultad,request)
				{
					datos = resultad.responseText;
					var dataJson = eval('(' + datos + ')');
					if (dataJson.raiz.valido==true)
					{
						//location.href='desktop.html';
						window.open("desktop.html" , "SIGESP C.A." , "menubar=no,toolbar=no,scrollbars=yes,location=no,resizable=yes,_self");
					}
					else
					{
						Ext.MessageBox.alert('Mensaje',dataJson.raiz.mensaje);
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





	
