/***********************************************************************************
* @Proceso para actualizar las cuentas contables y de presupuesto.
* @parametros: 
* @retorno:
* @fecha de creación: 15/12/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
var panel      = '';
var pantalla   = 'procesarcuentas';
var actualizar = false;
var rutaProceso  =  '../../controlador/apr/sigesp_ctr_apr_procesar_cuentas.php'; 

Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		Ext.Ajax.timeout=0;
		Ext.form.Field.prototype.msgTarget = 'side';		
		
		//componentes del formulario
		Xpos = ((screen.width/2)-(400/2)); 
		Ypos = ((screen.height/2)-(550/2));
		
		panel = new Ext.FormPanel({
			title: 'Procesar Cuentas Contables y de Presupuesto',
			bodyStyle:'padding:5px 5px 0px',
			width:400,
			tbar: [],
			style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
			items:[{
				xtype:'fieldset',
				id:'fscuentas',				
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',
				items:[{	
					xtype:'checkbox',
					fieldLabel:'Actualizar Cuentas Contables',
					labelStyle: 'width:250px',
					name:'Actualizar Cuentas Contables',
					id:'chbctasscg'	
				},{
					xtype:'checkbox',
					fieldLabel:'Actualizar Cuentas Presupuestarias',
					labelStyle: 'width:250px',
					name:'Actualizar Cuentas Presupuestarias',
					id:'chbctasspg'						
				}]
			}]	
		})
		panel.render(document.body);
		
	}
)
	
	
/***********************************************************************************
* @Función para limpiar los campos.
* @parametros: 
* @retorno:
* @fecha de creación: 10/12/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function irCancelar()
	{
		
	}	
	
	
/***********************************************************************************
* @Función para procesar la acualización de las cuentas presupuestarias.
* @parametros: 
* @retorno:
* @fecha de creación: 15/12/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/				
	function irProcesar()
	{
		obtenerMensaje('procesar','','Transfiriendo Datos');
				
		if (Ext.getCmp('chbctasscg').getValue()==false && Ext.getCmp('chbctasspg').getValue()==false)
		{
			Ext.Msg.alert('Mensaje','Seleccione al menos un actualizar');
		}
		else
		{
		
			var objdata ={
				'operacion': 'procesarCuentas', 
				'scg': Ext.getCmp('chbctasscg').getValue(),
				'spg': Ext.getCmp('chbctasspg').getValue(),
				'sistema': sistema,
				'vista': vista
			};	
			objdata=Ext.util.JSON.encode(objdata);	
			parametros = 'objdata='+objdata;
			Ext.Ajax.request({
			url : rutaProceso,
			params : parametros,
			method: 'POST',
			success: function ( resultado, request ) 
			{ 
				datos = resultado.responseText;
				Ext.Msg.hide();
				//alert(datos);					
				var datajson = Ext.util.JSON.decode(datos);	
				if(datajson.raiz.valido==true)
				{								
					obtenerMensaje('exito');
					Ext.getCmp('chbctasscg').disable();
					Ext.getCmp('chbctasspg').disable();
				}
				else
				{
					Ext.Msg.alert('Error', datajson.raiz.mensaje);
				}
			},
	        failure: function ( resultado, request)
			{ 
				Ext.Msg.hide();
				Ext.MessageBox.alert('Error', resultado.responseText); 
	        }
	        });   
		}	            
	}


/***********************************************************************************
* @Función para Descargar los archivos generados pór el módulo de apertura
* @parametros: 
* @retorno:
* @fecha de creación: 27/10/2008
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function irDescargar()
	{
		objCatDescarga = new catalogoDescarga();
		objCatDescarga.mostrarCatalogo();
	}
		