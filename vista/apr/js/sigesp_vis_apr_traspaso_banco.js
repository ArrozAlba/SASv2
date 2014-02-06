/***********************************************************************************
* @Proceso para traspasar los saldos y moviemientos en tránsito.
* @parametros: 
* @retorno:
* @fecha de creación: 04/12/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/

var panel      = '';
var pantalla   = 'traspasobanco';
var actualizar = false;
var rutaBanco  =  '../../controlador/apr/sigesp_ctr_apr_banco.php'; 
var fecanterior = '';

Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		Ext.Ajax.timeout=0;
		Ext.form.Field.prototype.msgTarget = 'side';

		var datosNuevo = {'raiz':[{'codban':'','nomban':'No posee cuentas....'}]};
		
		record = Ext.data.Record.create([
			{name: 'codban'},     
			{name: 'nomban'}
		]);					
		dsbanco =  new Ext.data.Store({
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
			 
		var datosCuenta = {'raiz':[{'ctaban':'No posee cuentas....'}]};
		
		record = Ext.data.Record.create([
			{name: 'ctaban'}
		]);					
		dscuenta =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(datosCuenta),
			reader: new Ext.data.JsonReader(
			{
				root: 'raiz',               
				id: 'id'   
			},
			record
			),
			data: datosCuenta			
		 });	 
		
		//para mostrar la fecha final del periodo anterior	
		var fecha = new Date();
		annoactual = fecha.getFullYear();
 		annoanterior = annoactual-1;
 		fecanterior = '31/12/'+annoanterior;
 		
 		fecinicio = '01/01/'+annoactual;	
 				
 		var procesar = new Ext.Action(
		{
			text: 'Procesar',
			handler: irProcesar,
			iconCls: 'bmenuprocesar',
			tooltip: 'Procesar'
		});
		var descargar = new Ext.Action(
		{
			text: 'Descargar',
			handler: irDescargar,
			iconCls: 'bmenudescargar',
			tooltip: 'Descargar Archivos Generados'
		});		
 		var cancelar = new Ext.Action(
			{
				text: 'Cancelar',
				handler: irCancelar,
				iconCls: 'bmenucancelar',
				tooltip: 'Limpiar campos'
			});	
 					 
		//componentes del formulario
		Xpos = ((screen.width/2)-(500/2)); 
		Ypos = ((screen.height/2)-(550/2));
		
		panel = new Ext.FormPanel({
			title: 'Transferir Saldos y Movimientos en Tránsito',
			bodyStyle:'padding:5px 5px 0px',
			width:500,
			tbar: [cancelar,procesar,descargar],
			style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
			items:[{
				xtype:'fieldset',
				title:'Cuentas Disponibles',
				id:'fscriterio',				
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',
				items:[{	
					xtype:'combo',
					fieldLabel:'Banco',
					readOnly:true,
					name:'banco',
					id:'cmbbanco',
					emptyText:'Seleccione',
					displayField:'nomban',
					valueField:'codban',
					//hiddenName:'nomtipodoc',
					//hiddenId:'idtipodoc',
					typeAhead: true,
					mode: 'local',
					triggerAction: 'all',
					store: dsbanco,							
					width:300
				},{
					xtype:'combo',
					fieldLabel:'Cuenta',
					readOnly:true,
					name:'cuenta',
					id:'cmbcuenta',
					emptyText:'Seleccione',
					displayField:'ctaban',
					valueField:'ctaban',
					//hiddenName:'nomtipodoc',
					//hiddenId:'idtipodoc',
					typeAhead: true,
					mode: 'local',
					triggerAction: 'all',
					store: dscuenta,							
					width:300		
				}]
			},{
				xtype:'fieldset',
				title:'Datos a Transferir',
				id:'fsdatos',	
				labelWidth:180,			
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',
				items:[{
					xtype:'checkbox',
					fieldLabel:'Movimientos en Tránsito',
					name:'Movimientos en Tránsito',	
					id:'chkmov'
				},{
					xtype:'datefield',
					fieldLabel:'Fecha Final Período Anterior',
					name:'Fecha Final Período Anterior',
					value:fecanterior,
					readOnly:true,
					id:'txtfecfinant'
				},{
					xtype:'datefield',
					fieldLabel:'Fecha Inicial Nuevo Período',
					name:'Fecha Inicial Nuevo Período',
					value:fecinicio,
					readOnly:true,
					id:'txtfecininue'			
				}]		
			}]
		})				
		
		panel.render(document.body);	
		
		cargarBancos();
		
})	


/***********************************************************************************
* @Función para mostrar los bancos.   
* @parametros: 
* @retorno:
* @fecha de creación: 04/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function cargarBancos()
	{
		var objdata ={
			'operacion': 'obtenerBancos', 
			'sistema': sistema,
			'vista': vista
		};	
		objdata=Ext.util.JSON.encode(objdata);			
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
			url : rutaBanco,
			params : parametros,
			method: 'POST',
			success: function (resultad,request)
			{
				datos = resultad.responseText;
				var datajson = Ext.util.JSON.decode(datos);
				if (datajson.raiz[0]!=null)
				{
					dsbanco.loadData(datajson);										
				}				
				Ext.getCmp('cmbbanco').addListener('select',irCuentas);
			},
			failure: function ( result, request)
			{ 
				Ext.MessageBox.alert('Error', result.responseText); 
			},
			
		});		
	}
	
	
/***********************************************************************************
* @Función para definir la búsqueda de las cuentas asociadas al banco seleccionado.   
* @parametros: 
* @retorno:
* @fecha de creación: 04/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/				
	function irCuentas()
	{
		banco = Ext.getCmp('cmbbanco').getValue();
		Ext.getCmp('cmbcuenta').setValue('');
		cargarCuentas(banco);
	}
	
	
/***********************************************************************************
* @Función para mostrar los cuentas asociadas al banco seleccionado.   
* @parametros: 
* @retorno:
* @fecha de creación: 04/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function cargarCuentas(codban)
	{
		var objdata ={
			'operacion': 'obtenerCuenta', 
			'codban': codban,
			'sistema': sistema,
			'vista': vista
		};	
		objdata=Ext.util.JSON.encode(objdata);			
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
			url : rutaBanco,
			params : parametros,
			method: 'POST',
			success: function (resultad,request)
			{
				datos = resultad.responseText;
				var datajson = Ext.util.JSON.decode(datos);
				if (datajson.raiz[0]!=null)
				{
					dscuenta.loadData(datajson);
				}				
			},
			failure: function ( result, request)
			{ 
				Ext.MessageBox.alert('Error', result.responseText); 
			},
			
		});		
	}
	
	
/***********************************************************************************
* @Función para limpiar los campos.   
* @parametros: 
* @retorno:
* @fecha de creación: 04/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/				
	function irCancelar()
	{
		Ext.getCmp('cmbbanco').setValue('');
		Ext.getCmp('cmbcuenta').setValue('');
		Ext.getCmp('chkmov').setValue(false);
		Ext.getCmp('txtfecfinant').setValue(fecanterior);
		Ext.getCmp('txtfecininue').setValue(fecinicio);
	}
	
	
/***********************************************************************************
* @Función para procesar el traspaso de los saldos y movimientos en tránsito.   
* @parametros: 
* @retorno:
* @fecha de creación: 04/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/					
	function irProcesar()
	{
		if (validarObjetos('cmbbanco','60','novacio')!='0' && validarObjetos('cmbcuenta','25','novacio')!='0' 
			&& validarObjetos('txtfecfinant','10','novacio')!='0' && validarObjetos('txtfecfinant','10','novacio')!='0')
		{
			obtenerMensaje('procesar','','Transfiriendo Datos');		
		
			var objdata ={
				'operacion': 'irProcesar', 
				'movtransito': Ext.getCmp('chkmov').getValue(),
				'codban': Ext.getCmp('cmbbanco').getValue(),
				'ctaban': Ext.getCmp('cmbcuenta').getValue(),
				'fecfin': Ext.get('txtfecfinant').getValue(),
				'fecini': Ext.get('txtfecininue').getValue(),
				'sistema': sistema,
				'vista': vista
			};	
		
			objdata=Ext.util.JSON.encode(objdata);		
			parametros = 'objdata='+objdata;
			Ext.Ajax.request({
				url : rutaBanco,
				params : parametros,
				method: 'POST',
				success: function (resultad,request)
				{
					datos = resultad.responseText;
					Ext.Msg.hide();
					var datajson = Ext.util.JSON.decode(datos);
					if (datajson.raiz.valido==true)
					{
						//dscuenta.loadData(datajson);
						Ext.Msg.alert('Mensaje','Los saldos fueron trasladados satisfactoriamente');
						irCancelar();
					}	
					else
					{
						Ext.Msg.alert('Error',datajson.raiz.mensaje);
						irCancelar();
					}			
				},
				failure: function ( result, request)
				{ 
					Ext.Msg.hide();
					Ext.MessageBox.alert('Error', resultad.responseText); 
				},				
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
	
	
	function irEliminar()
	{
		
	}
	
	
	