/***********************************************************************************
* @Proceso para actualizar las cuentas contables.
* @parametros: 
* @retorno:
* @fecha de creación: 09/12/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
var panel      = '';
var pantalla   = 'asociarcontables';
var actualizar = false;
var rutaContables  =  '../../controlador/apr/sigesp_ctr_apr_ctascontables.php'; 
var datosNuevo = {'raiz':[{'sc_cuenta':''}]};
var gridContable = '';
Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		Ext.Ajax.timeout=0;
		Ext.form.Field.prototype.msgTarget = 'side';		
		
		//componentes del formulario
		Xpos = ((screen.width/2)-(600/2)); 
		Ypos = ((screen.height/2)-(550/2));
		
		panel = new Ext.FormPanel({
			title: 'Actualizar Cuentas Contables',
			bodyStyle:'padding:5px 5px 0px',
			width:600,
			tbar: [],
			style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
			items:[{
				xtype:'fieldset',
				//title:'Cuentas Contables',
				id:'fscontables',				
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',
				items:[{	
					xtype:'panel',
					autoScroll:true,
					width:550,
					heidht:200,
					title:'Cuentas Contables',
					contentEl:'grid-ctasscg'	
				}]
			}]	
		})
		panel.render(document.body);
		
		obtenerGridContables();
				
	}
)		


/***********************************************************************************
* @Función para crear la grid y pasarle los datos de las cuentas contables.
* @parametros: 
* @retorno: 
* @fecha de creación: 09/12/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function obtenerGridContables()
	{
		var objdata ={
			'operacion': 'catalogocuentas', 
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
		url : rutaContables,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request ) 
		{ 
			datos = resultado.responseText;
			if (datos!='')
			{
				var myObject = eval('(' + datos + ')');
				//if (myObject.raiz[0].valido==true)
				//{
		
					RecordDef = Ext.data.Record.create
					([
						{name: 'origen'}, 
						{name:'destino'}						
					]);
					
					dsctasscg =  new Ext.data.Store({
					proxy: new Ext.data.MemoryProxy(myObject),
					reader: new Ext.data.JsonReader({
						root: 'raiz',               
						id: 'id'   
						},
							  RecordDef
						),
						data: myObject
						});
					
					gridContable = new Ext.grid.GridPanel({
						width:550,
						height: 200,
						autoScroll:true,
						border:true,
						ds: dsctasscg,
						cm: new Ext.grid.ColumnModel([
						  //new Ext.grid.CheckboxSelectionModel(),
							{header: 'Cuenta Anterior', width: 150, sortable: true, dataIndex: 'origen'},
							{header: 'Cuenta Actual', width: 150, sortable: true, dataIndex: 'destino'}
						]),
						sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
						viewConfig: {
										forceFit:true
									},
						stripeRows: true
					});
					gridContable.render('grid-ctasscg');
			//	}
			}
					
		},
        failure: function ( resultado, request)
		{ 
			Ext.MessageBox.alert('Error', resultado.responseText); 
        }
        });		
	}	


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
		/*total  = gridContable.store.getCount();
		arrTotal = gridContable.store.getRange(0,total);
		alert(total);
		alert(arrTotal.length);
		for (i=0; i<=arrTotal.length; i++)
		{
			arrTotal[i].set('sc_cuenta','');
		}*/	
	}	


/***********************************************************************************
* @Función para buscar las cuentas contables actuales.
* @parametros: 
* @retorno:
* @fecha de creación: 10/12/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function irBuscar()
	{
		if (gridContable.getSelectionModel().getSelected() && gridContable.getSelectionModel().getSelected().get('destino')=='')
		{		
			var arreglotxt = new Array('ctaactual');		
			var arreglovalores = new Array('destino');				
			objCatCuenta = new catalogoCuentaScg();
			objCatCuenta.mostrarCatalogo(arreglotxt, arreglovalores);
		}
		else if (gridContable.getSelectionModel().getSelected() && gridContable.getSelectionModel().getSelected().get('destino')!='')
		{
			Ext.Msg.alert('Mensaje','La cuenta ya está asociada');
		}
		else
		{
			Ext.Msg.alert('Mensaje','Debe seleccionar la cuenta anterior');
		}
	}
	
	
/***********************************************************************************
* @Función para procesar la acualización de las cuentas contables.
* @parametros: 
* @retorno:
* @fecha de creación: 10/12/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function irProcesar()
	{
		obtenerMensaje('procesar','','Transfiriendo Datos');
		
		arrCuentas = gridContable.store.getModifiedRecords();
		total = arrCuentas.length;
				
		var objdata = "{'operacion': 'procesar','sistema': sistema,'vista': vista ";
		objdata=objdata+ ",datosCuentas:[";	
		if (total>0)
		{					
			for (i=0; i < total; i++)
			{
				if (i==0)
				{
					objdata = objdata +"{'ctaanterior':'"+ arrCuentas[i].get('origen')+
								"','ctaactual':'"+ arrCuentas[i].get('destino')+ "'}";
				}
				else
				{
					objdata = objdata +",{'ctaanterior':'"+ arrCuentas[i].get('origen')+
								"','ctaactual':'"+ arrCuentas[i].get('destino')+ "'}";
				}
			}
		//}
		objdata = objdata + ']}';
		objdata = Ext.util.JSON.decode(objdata);
		objdata = Ext.util.JSON.encode(objdata);
		//alert(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
		url : rutaContables,
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
				gridContable.store.commitChanges();
				Ext.Msg.alert('Mensaje', datajson.raiz.mensaje);
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
        else
        {
        	Ext.Msg.alert('Mensaje','No existen nuevas cuentas para asociar');
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