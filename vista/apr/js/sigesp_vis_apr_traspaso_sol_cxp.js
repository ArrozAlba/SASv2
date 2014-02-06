/***********************************************************************************
* @Proceso para traspasar las solicitudes pendientes de cuentas por pagar
* @parametros: 
* @retorno:
* @fecha de creación: 25/11/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/

var panel = '';
var pantalla = 'traspasosol';
var actualizar = false;
var rutaSol =  '../../controlador/apr/sigesp_ctr_apr_solicitudes.php'; 
var datosNuevo={'raiz':[{'numsol':'','fecemisol':'','consol':'','monsol':'','pagado':''}]};
var codestpro1 = '';
var codestpro2 = '';
var codestpro3 = '';
var codestpro4 = '';
var codestpro5 = '';
var gridSolicitud = '';
Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		Ext.Ajax.timeout=0;
		// turn on validation errors beside the field globally
		Ext.form.Field.prototype.msgTarget = 'side';


		var datosNuevo = {'raiz':[{'codtipdoc':'','dentipdoc':''}]};
		
			record = Ext.data.Record.create([
				{name: 'codtipdoc'},     
				{name: 'dentipdoc'}
			]);					
			dstipodoc =  new Ext.data.Store({
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
		Xpos = ((screen.width/2)-(850/2)); 
		Ypos = ((screen.height/2)-(750/2));
		panel = new Ext.FormPanel({
			title: 'Transferir Solicitudes Cuentas por Pagar',
			bodyStyle:'padding:5px 5px 0px',
			width:850,
			frame: true,
			tbar: [cancelar,procesar,descargar],
			style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
			items:[{				
				xtype:'fieldset',
				title:'Criterio de Cambio',
				id:'fscriterio',				
				labelWidth:150, 
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',
				items:[{
					xtype:'fieldset',
					title:'Tipo de Documento',
					id:'fscriterio',
					layout:'column',
					autoHeight:true,
					autoWidth:true,
					cls :'fondo',
					items:[{
						columnWidth:.2,
						layout: 'form',
						labelWidth:80,
						border:false,					
						items: [{	
							xtype:'radio',
							fieldLabel:'Contable',
							checked: true,
							name:'TipoDoc',	
							id:'rdcontable'
						}]							
					},{
						columnWidth:.2,
						layout: 'form',
						labelWidth:100,
						border:false,					
						items: [{	
							xtype:'radio',
							fieldLabel:'Presupuestario',
							name:'TipoDoc',	
							id:'rdpresupuestario'
						}]
					},{
						columnWidth:.3,
						layout: 'form',
						labelWidth:100,
						border:false,					
						items: [{	
							xtype:'combo',
							hideLabel:true,
							readOnly:true,
							name:'tipodoc',
							id:'cmbtipodoc',
							//emptyText:'Seleccione',
							displayField:'dentipdoc',
							valueField:'codtipdoc',
							hiddenName:'nomtipodoc',
							hiddenId:'idtipodoc',
							typeAhead: true,
							mode: 'local',
							triggerAction: 'all',
							store: dstipodoc,							
							width:200
						}]	
					},{
						columnWidth:.2,
						layout: 'form',
						labelWidth:40,
						border:false,					
						items: [{
							xtype:'datefield',
							fieldLabel:'Fecha',
							name:'Fecha',
							value:new Date(),
							readOnly:true,
							id:'txtfecha'
						}]		
					}]
				},{	
					xtype:'textfield',
					name:'codestpro1',
					//fieldLabel: '<div id="label1" style="float:left;">Inicial</div>',
					readOnly:true,
					width:200,
					id:'txtcodestpro1'
				},{
					xtype:'hidden',
					id:'hidestcla'	
				},{
					xtype:'button',
					id:'btnBuscarcodestpro1',
					handler: irBuscarEstructura1,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar estructura 1',
					style:'position:absolute;left:375px;top:98px',
					width:50
				},{
					xtype:'textfield',
					hideLabel:true,
					readOnly:true,
					id:'txtdencodestpro1',
					disabled:true,
					width:400,
					style:'position:absolute;left:390px;top:-38px'		
				},{
					xtype:'textfield',
					hideLabel:true,					
					readOnly:true,
					id:'txtdencodestpro2',
					disabled:true,
					width:400,
					style:'position:absolute;left:390px;top:5px'		
				},{
					xtype:'textfield',
					//hideLabel:true,
					//fieldLabel:'Acción Específica',
					//fieldLabel: '<div id="label2" style="float:left;">Inicial</div>',
					name:'codestpro2',
					readOnly:true,
					width: 200,
					id:'txtcodestpro2'					
				},{
					xtype:'button',
					id:'btnBuscarcodestpro2',
					handler: irBuscarEstructura2,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar estructura 2',
					style:'position:absolute;left:375px;margin-top:-25px',
					width:50			
				},{
					xtype:'textfield',
					//fieldLabel:'Otros',
					//fieldLabel: '<div id="label3" style="float:left;">Inicial</div>',
					//hideLabel:true,
					name:'codestpro3',
					readOnly:true,
					width:200,
					id:'txtcodestpro3'	
				},{				
					xtype:'button',
					id:'btnBuscarcodestpro3',
					handler: irBuscarEstructura3,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar estructura 3',
					style:'position:absolute;left:375px;margin-top:-25px',
					width:50			
				},{
					xtype:'textfield',
					hideLabel:true,
					readOnly:true,
					id:'txtdencodestpro3',
					disabled:true,
					width:400,
					style:'position:absolute;left:390px;top:-25px'		
				},{
				
					xtype:'textfield',
					name:'codestpro4',
					//fieldLabel: '<div id="label4" style="float:left;">Estructura 4</div>',
					readOnly:true,
					width:200,
					id:'txtcodestpro4'	
				},{
					xtype:'button',
					id:'btnBuscarcodestpro4',
					handler: irBuscarEstructura4,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar estructura 4',
					style:'position:absolute;left:375px;margin-top:-25px',
					width:50	
				},{
					xtype:'textfield',
					hideLabel:true,
					readOnly:true,
					id:'txtdencodestpro4',
					disabled:true,
					width:400,
					style:'position:absolute;left:390px;top:-25px'				
				},{
					xtype:'textfield',
					name:'codestpro5',
					//labelSeparator:'',
					//fieldLabel: '<div id="label5" style="float:left;">Estructura 5</div>',
					readOnly:true,
					//style:'position:absolute;left:10px;top:10px',
					width:200,
					id:'txtcodestpro5'
				},{
					xtype:'button',
					id:'btnBuscarcodestpro5',					
					handler: irBuscarEstructura5,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar estructura 5',
					style:'position:absolute;left:375px;margin-top:-25px',
					width:50
				},{
					xtype:'textfield',
					hideLabel:true,
					readOnly:true,
					id:'txtdencodestpro5',
					disabled:true,
					width:400,
					style:'position:absolute;left:390px;top:-25px'	
				},{
					xtype:'textfield',
					//fieldLabel: '<div id="cuenta" style="float:left;">Cuenta</div>',
					fieldLabel:'Cuenta',					 
					name:'Cuenta',
					readOnly:true,
					width:150,
					id:'txtcuenta'
				},{
					xtype:'button',
					id:'btnBuscarCuenta',
					handler: irBuscarCuenta,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar Denominación de la Cuenta',
					style:'position:absolute;left:325px;margin-top:-25px',
					width:50	
				},{
					xtype:'textfield',
					hideLabel:true,
					name:'Denominación',
					readOnly:true,
					id:'txtdencuenta',
					disabled:true,
					width:350,
					style:'position:absolute;left:340px;top:-25px'
				}]
			},{	
				xtype:'fieldset',
				title:'Criterio de Busqueda',
				id:'fsbusqueda',
				layout:'column',
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',
				itemCls: 'fondo',		
				items:[{
					columnWidth:.3,
					layout: 'form',
					labelWidth:100,
					border:false,					
					items: [{	
						xtype:'datefield',
						fieldLabel:'Fecha Desde',
						name:'Fecha Desde',
						readOnly:true,
						id:'txtfecdesde'
					},{						
						xtype:'radio',
						fieldLabel:'Pago Parcial',
						name:'Estatus',
						value:'S',	
						id:'rdpagoparcial'							
					}]	
				},{
					columnWidth:.3,
					layout: 'form',
					labelWidth:100,
					border:false,					
					items: [{
						xtype:'datefield',
						fieldLabel:'Fecha Hasta',
						name:'Fecha Hasta',
						readOnly:true,
						id:'txtfechasta'	
				
					},{
						xtype:'radio',
						fieldLabel:'Contabilizada',
						name:'Estatus',
						checked:true,
						value:'C',	
						id:'rdcontabilizada'
					}]	
				},{
					columnWidth:.2,
					layout: 'form',
					labelWidth:50,
					border:false,					
					items: [{
						xtype:'textfield',
						fieldLabel:'Prefijo',
						name:'Prefijo',
						width:50,
						id:'txtprefijo'
					}]						
				}]
			},{
				buttons:[{
					text: 'Buscar',
					handler: irBuscar
				}]
			},{
				xtype:'textfield',
				fieldLabel:'Concepto',
				name:'Concepto',
				width:500,
				//cls:'fondo',				
				id:'txtconcepto',
				enableKeyEvents:true,
				listeners:{
      				'keypress':function(Obj,e)
      				{
				     	//actualizarGridSol('consol',this.getValue());
				    }
				 }   			
			},{	
				xtype:'panel',
				autoScroll:true,
				height: 150,
				width:800,
				title:'Solicitudes',
				contentEl:'grid-solcxp'				
			}]
		});
		panel.render(document.body);
		
		obtenergridSolicitudes();
		
		verificarEstructuras();			
		
		Ext.getCmp('rdpresupuestario').addListener('check',buscarTipoDoc);
		Ext.getCmp('rdcontable').addListener('check',buscarTipoDoc);
		buscarTipoDoc();
	
})

	function actualizarGridSol(criterio,valor)
	{		
	 	dsconcepto.filter(criterio,valor);
	}

/***********************************************************************************
* @Función para mostrar los.
* @parametros: 
* @retorno: 
* @fecha de creación: 27/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function buscarTipoDoc()
	{
		if (Ext.getCmp('rdcontable').checked==true)
		{
			var tipodoc = 1;
			
			Ext.getCmp('txtcodestpro1').disable();
			Ext.getCmp('btnBuscarcodestpro1').disable();
			Ext.getCmp('txtcodestpro2').disable();
			Ext.getCmp('btnBuscarcodestpro2').disable();
			Ext.getCmp('txtcodestpro3').disable();
			Ext.getCmp('btnBuscarcodestpro3').disable();			
			Ext.getCmp('txtcodestpro4').disable();
			Ext.getCmp('btnBuscarcodestpro4').disable();
			Ext.getCmp('txtcodestpro5').disable();
			Ext.getCmp('btnBuscarcodestpro5').disable();
			Ext.getCmp('txtcuenta').disable();
			Ext.getCmp('btnBuscarCuenta').disable();						
		}
		else 
		{
			var tipodoc = 2;
			
			Ext.getCmp('txtcodestpro1').enable();
			Ext.getCmp('btnBuscarcodestpro1').enable();
			Ext.getCmp('txtcodestpro2').enable();
			Ext.getCmp('btnBuscarcodestpro2').enable();
			Ext.getCmp('txtcodestpro3').enable();
			Ext.getCmp('btnBuscarcodestpro3').enable();			
			Ext.getCmp('txtcodestpro4').enable();
			Ext.getCmp('btnBuscarcodestpro4').enable();
			Ext.getCmp('txtcodestpro5').enable();
			Ext.getCmp('btnBuscarcodestpro5').enable();	
			Ext.getCmp('txtcuenta').enable();	
			Ext.getCmp('btnBuscarCuenta').enable();				
		}
		
		var objdata ={
			'operacion': 'obtenerTipoDoc', 
			'tipodoc': tipodoc,
			'sistema': sistema,
			'vista': vista
		};		
		objdata=JSON.stringify(objdata);			
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
			url : rutaSol,
			params : parametros,
			method: 'POST',
			success: function (resultad,request)
			{
				datos = resultad.responseText;
				var datajson = Ext.util.JSON.decode(datos);
				if (datajson.raiz[0]!=null)
				{
					dstipodoc.loadData(datajson);
					Ext.getCmp('cmbtipodoc').setValue(datajson.raiz[0].dentipdoc);
					Ext.get('idtipodoc').dom.value = datajson.raiz[0].codtipdoc;
				}
			},
			failure: function ( result, request)
			{ 
				Ext.MessageBox.alert('Error', result.responseText); 
			},
			
		});	
	
	}


/***********************************************************************************
* @Función para crear la grid y pasarle los datos de los conceptos.
* @parametros: 
* @retorno: 
* @fecha de creación: 26/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function obtenergridSolicitudes()
	{
		RecordDef = Ext.data.Record.create
		([
			{name: 'numsol'}, 
			{name: 'fecemisol'}, 
			{name: 'consol'},
			{name: 'monsol'},
			{name: 'pagado'}
		]);
		
		dsconcepto =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(datosNuevo),
		reader: new Ext.data.JsonReader({
			root: 'raiz',               
			id: 'id'   
			},
				  RecordDef
			),
			data: datosNuevo
			});
		
		gridSolicitud = new Ext.grid.GridPanel({
			width:800,
			height: 300,
			autoScroll:true,
			border:true,
			ds: dsconcepto,
			cm: new Ext.grid.ColumnModel([
			  new Ext.grid.CheckboxSelectionModel(),
				{header: 'Solicitud', width: 150, sortable: true,   dataIndex: 'numsol'},
				{header: 'Emisión', width: 100, sortable: true,   dataIndex: 'fecemisol'},
				{header: 'Concepto', width: 250, sortable: true, dataIndex: 'consol'},
				{header: 'Solicitado Bs.', width: 150, sortable: true, dataIndex: 'monsol', align:'right'},
				{header: 'Pagado Bs.', width: 150, sortable: true, dataIndex: 'pagado', align:'right'}
			]),
			sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
			viewConfig: {
							forceFit:true
						},
			autoHeight:true,
			stripeRows: true
		});
		gridSolicitud.render('grid-solcxp');
	}


/**************************************************************************************
* @Función para verificar los niveles de las estructuras presupuestarias de la empresa.
* @parametros: 
* @retorno: 
* @fecha de creación: 26/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***************************************************************************************/	
	function verificarEstructuras()
	{
		var objdata ={
			'operacion': 'verificarEstructuras',
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(objdata);		
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
			url : rutaSol,
			params : parametros,
			method: 'POST',
			success: function (resultad,request)
			{
				datos = resultad.responseText;
				var datajson = Ext.util.JSON.decode(datos);
				if (datajson.raiz!=null)
				{
					if (datajson.raiz.nivel1!='-')
					{						
        				var label1 = Ext.DomQuery.select('label[for="txtcodestpro1"]');
        				Ext.DomHelper.overwrite(label1[0],datajson.raiz.nivel1+':');
					}
					if (datajson.raiz.nivel2!='-')
					{
						var label2 = Ext.DomQuery.select('label[for="txtcodestpro2"]');
						Ext.DomHelper.overwrite(label2[0],datajson.raiz.nivel2+':');
					}
					if (datajson.raiz.nivel3!='-')
					{
						var label3 = Ext.DomQuery.select('label[for="txtcodestpro3"]');
						Ext.DomHelper.overwrite(label3[0],datajson.raiz.nivel3+':');
					}					
					if (datajson.raiz.nivel4=='' || datajson.raiz.nivel4=='-') //preguntar por el valor por defecto
					{
						Ext.getCmp('txtdencodestpro4').hide();
						Ext.getCmp('btnBuscarcodestpro4').hide();
						var label4 = Ext.DomQuery.select('label[for="txtcodestpro4"]');
 						Ext.DomHelper.overwrite(label4[0],'');	
                        Ext.getCmp('txtcodestpro4').hide();                       
					}
					else
					{
						var label4 = Ext.DomQuery.select('label[for="txtcodestpro4"]');
						Ext.DomHelper.overwrite(label4[0],datajson.raiz.nivel4+':');
					}
					if (datajson.raiz.nivel5=='' || datajson.raiz.nivel5=='-')
					{						
						Ext.getCmp('txtdencodestpro5').hide();
						Ext.getCmp('btnBuscarcodestpro5').hide();
						//obtener la etiqueta del elemento						
						var label5 = Ext.DomQuery.select('label[for="txtcodestpro5"]'); 
						//para quitar los :
 						Ext.DomHelper.overwrite(label5[0],'');	
                        Ext.getCmp('txtcodestpro5').hide(); 
					}
					else
					{
						var label5 = Ext.DomQuery.select('label[for="txtcodestpro5"]'); 
						Ext.DomHelper.overwrite(label5[0],datajson.raiz.nivel5+':');
					}					
				}
			},
			failure: function ( resultad, request)
			{ 
				Ext.MessageBox.alert('Error', 'No se logró procesar la información'); 
			}
		});	 
	}
	

/***********************************************************************************
* @Función para buscar las estructuras presupuestarias 1.
* @parametros: 
* @retorno: 
* @fecha de creación: 26/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function irBuscarEstructura1()
	{
		var arreglotxt = new Array('txtcodestpro1','txtdencodestpro1','hidestcla');		
		var arreglovalores = new Array('codestpro1','denestpro1','estcla');				
		objCatEst1 = new catalogoEstructura1();
		objCatEst1.mostrarCatalogoEstructura1(arreglotxt, arreglovalores);
	
	}


/***********************************************************************************
* @Función para buscar las estructuras presupuestarias 2.
* @parametros: 
* @retorno: 
* @fecha de creación: 26/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irBuscarEstructura2()
	{
		if (Ext.getCmp('txtcodestpro1').getValue()=='')
		{
			Ext.Msg.alert('Mensaje','Seleccione la Estructura del Nivel Anterior');
		}
		else
		{
			codestpro1 = Ext.getCmp('txtcodestpro1').getValue();
			denestpro1 = Ext.getCmp('txtdencodestpro1').getValue();
			var arreglotxt = new Array('txtcodestpro2','txtdencodestpro2');		
			var arreglovalores = new Array('codestpro2','denestpro2');				
			objCatEst2 = new catalogoEstructura2();
			objCatEst2.mostrarCatalogoEstructura2(arreglotxt, arreglovalores,denestpro1);
		}	
	}


/***********************************************************************************
* @Función para buscar las estructuras presupuestarias 3.
* @parametros: 
* @retorno: 
* @fecha de creación: 26/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irBuscarEstructura3()
	{
		if (Ext.getCmp('txtcodestpro2').getValue()=='')
		{
			Ext.Msg.alert('Mensaje','Seleccione la Estructura del Nivel Anterior');
		}
		else
		{
			codestpro1 = Ext.getCmp('txtcodestpro1').getValue();
			codestpro2 = Ext.getCmp('txtcodestpro2').getValue();
			denestpro1 = Ext.getCmp('txtdencodestpro1').getValue();
			denestpro2 = Ext.getCmp('txtdencodestpro2').getValue();
			var arreglotxt = new Array('txtcodestpro3','txtdencodestpro3');		
			var arreglovalores = new Array('codestpro3','denestpro3');				
			objCatEst3 = new catalogoEstructura3();
			objCatEst3.mostrarCatalogoEstructura3(arreglotxt, arreglovalores,denestpro1,denestpro2);
		}
	}
	
/***********************************************************************************
* @Función para buscar las estructuras presupuestarias 4.
* @parametros: 
* @retorno: 
* @fecha de creación: 26/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irBuscarEstructura4()
	{
		if (Ext.getCmp('txtcodestpro3').getValue()=='')
		{
			Ext.Msg.alert('Mensaje','Seleccione la Estructura del Nivel Anterior');
		}
		else
		{
			codestpro1 = Ext.getCmp('txtcodestpro1').getValue();
			codestpro2 = Ext.getCmp('txtcodestpro2').getValue();
			codestpro3 = Ext.getCmp('txtcodestpro3').getValue();
			denestpro1 = Ext.getCmp('txtdencodestpro1').getValue();
			denestpro2 = Ext.getCmp('txtdencodestpro2').getValue();
			denestpro3 = Ext.getCmp('txtdencodestpro3').getValue();
			var arreglotxt = new Array('txtcodestpro4','txtdencodestpro4');		
			var arreglovalores = new Array('codestpro4','denestpro4');				
			objCatEst4 = new catalogoEstructura4();
			objCatEst4.mostrarCatalogoEstructura4(arreglotxt, arreglovalores,denestpro1,denestpro2,denestpro3);
		}	
	}


/***********************************************************************************
* @Función para buscar las estructuras presupuestarias 5.
* @parametros: 
* @retorno: 
* @fecha de creación: 26/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irBuscarEstructura5()
	{
		if (Ext.getCmp('txtcodestpro4').getValue()=='')
		{
			Ext.Msg.alert('Mensaje','Seleccione la Estructura del Nivel Anterior');
		}
		else
		{
			codestpro1 = Ext.getCmp('txtcodestpro1').getValue();
			codestpro2 = Ext.getCmp('txtcodestpro2').getValue();
			codestpro3 = Ext.getCmp('txtcodestpro3').getValue();
			codestpro4 = Ext.getCmp('txtcodestpro4').getValue();
			denestpro1 = Ext.getCmp('txtdencodestpro1').getValue();
			denestpro2 = Ext.getCmp('txtdencodestpro2').getValue();
			denestpro3 = Ext.getCmp('txtdencodestpro3').getValue();
			denestpro4 = Ext.getCmp('txtdencodestpro4').getValue();
			var arreglotxt = new Array('txtcodestpro5','txtdencodestpro5');		
			var arreglovalores = new Array('codestpro5','denestpro5');				
			objCatEst5 = new catalogoEstPre();
			objCatEst5.mostrarCatalogoEstPre(arreglotxt, arreglovalores,denestpro1,denestpro2,denestpro3,denestpro4);
		}	
	}


/***********************************************************************************
* @Función para buscar las cuentas.
* @parametros: 
* @retorno: 
* @fecha de creación: 26/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irBuscarCuenta()
	{
		if (Ext.getCmp('rdpresupuestario').checked==true)
		{
			if (Ext.getCmp('txtcodestpro1').getValue()=='' && Ext.getCmp('txtcodestpro2').getValue()=='' && Ext.getCmp('txtcodestpro3').getValue()=='')
			{
				Ext.Msg.alert('Mensaje','Seleccione la Estructura Presupuestaria');
			}
			else
			{
				codestpro1 = Ext.getCmp('txtcodestpro1').getValue();
				codestpro2 = Ext.getCmp('txtcodestpro2').getValue();
				codestpro3 = Ext.getCmp('txtcodestpro3').getValue();
				codestpro4 = Ext.getCmp('txtcodestpro4').getValue();
				codestpro5 = Ext.getCmp('txtcodestpro5').getValue();
				var arreglotxt = new Array('txtcuenta','txtdencuenta');		
				var arreglovalores = new Array('spg_cuenta','denominacion');				
				objCatCuenta = new catalogoCuenta();
				objCatCuenta.mostrarCatalogo(arreglotxt, arreglovalores);
			}	
		}
	}
	
	
/*************************************************************************************************
* @Función para buscar las solicitudes.
* @parametros: 
* @retorno: 
* @fecha de creación: 27/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
**************************************************************************************************/	
	function irBuscar()
	{
		if (Ext.getCmp('txtfecdesde').getValue()=='' || Ext.getCmp('txtfechasta').getValue()=='')
		{
			Ext.Msg.alert('Mensaje','Debe indicar el periodo de búsqueda');
		}
		else if (Ext.getCmp('rdpagoparcial').checked==true)
		{
			var estatus = 'S';
		}
		else if (Ext.getCmp('rdcontabilizada').checked==true)
		{
			var estatus = 'C';
		}		
	
		if (estatus=='')
		{
			Ext.Msg.alert('Mensaje','Debe seleccionar el estatus de las solicitudes a buscar');
		}
		else
		{
			var objdata ={
				'operacion': 'buscar',
				'estatus': estatus,
			//	'tipoprov': tipoprov,
				'concepto': Ext.getCmp('txtconcepto').getValue(),
				'fecdesde': Ext.get('txtfecdesde').getValue(),
				'fechasta': Ext.get('txtfechasta').getValue(),
				'sistema': sistema,
				'vista': vista
			};
			objdata=JSON.stringify(objdata);		
			parametros = 'objdata='+objdata;
			Ext.Ajax.request({
				url : rutaSol,
				params : parametros,
				method: 'POST',
				success: function (resultad,request)
				{
					datos = resultad.responseText;
					//alert(datos);					
					var datajson = Ext.util.JSON.decode(datos);						
					if(datajson.raiz[0].valido==true)
					{
						gridSolicitud.store.loadData(datajson);
						//Ext.Msg.alert('Mensaje', datajson.raiz[0].mensaje);
					}
					else
					{
						Ext.Msg.alert('Mensaje', datajson.raiz[0].mensaje+' Al cargar las solicitudes.');
					}
				},
				failure: function ( resultad, request)
				{ 
					Ext.Msg.alert('Error', 'No se logró procesar la información'); 
				}
			});	
		}	
	}

	
/***********************************************************************************
* @Función para limpiar los campos.
* @parametros: 
* @retorno: 
* @fecha de creación: 26/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function irCancelar()
	{
		Ext.getCmp('txtfecdesde').setValue('');
		Ext.getCmp('txtfechasta').setValue('');
		Ext.getCmp('txtprefijo').setValue('');
		Ext.getCmp('txtcodestpro1').setValue('');
		Ext.getCmp('txtdencodestpro1').setValue('');
		Ext.getCmp('txtcodestpro2').setValue('');
		Ext.getCmp('txtdencodestpro2').setValue('');
		Ext.getCmp('txtcodestpro3').setValue('');
		Ext.getCmp('txtdencodestpro3').setValue('');
		Ext.getCmp('txtcodestpro4').setValue('');
		Ext.getCmp('txtdencodestpro4').setValue('');
		Ext.getCmp('txtcodestpro5').setValue('');
		Ext.getCmp('txtdencodestpro5').setValue('');
		Ext.getCmp('txtcuenta').setValue('');
		Ext.getCmp('txtdencuenta').setValue('');	
		Ext.getCmp('txtconcepto').setValue('');
		
		gridSolicitud.store.removeAll();
		gridSolicitud.store.loadData(datosNuevo);
		gridSolicitud.store.commitChanges();
		arrEliminar = new Array();
		toteliminar=0;
	}


/***********************************************************************************
* @Función para realizar la transferencia de solicitudes.
* @parametros: 
* @retorno: 
* @fecha de creación: 28/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function irProcesar() //destino,valores,donde
	{
		obtenerMensaje('procesar','','Transfiriendo Datos');
		
		if (validarObjetos('txtprefijo','4','novacio|alfanumerico')!='0' && validarObjetos('txtfecdesde','12','novacio')!='0' && validarObjetos('txtfechasta','12','novacio')!='0' /*&& validarObjetos('txtcuenta','25','novacio')!='0' */&& validarObjetos('cmbtipodoc','80','novacio')!='0') 
		{
			codestpro4 = validarObjetos('txtcodestpro4','25','rellenar');
			codestpro5 = validarObjetos('txtcodestpro5','25','rellenar');
			
			tipodoc = Ext.get('idtipodoc').dom.value;
			if (Ext.getCmp('rdcontable').checked==true) //revisar si es asi
			{
				estconpre = 0; 
			}
			else if (Ext.getCmp('rdpresupuestario').checked==true)
			{
				estconpre = 1; 
			}		
			arrSelSol = gridSolicitud.getSelectionModel().getSelections();
			total = arrSelSol.length;			
			
			var objdata = "{'operacion': 'procesar','cuenta':'"+Ext.getCmp('txtcuenta').getValue()+
								"','fecdesde': '"+Ext.get('txtfecdesde').getValue()+
								"','fechasta': '"+Ext.get('txtfechasta').getValue()+
								"','prefijo': '"+Ext.getCmp('txtprefijo').getValue()+
								"','consol': '"+Ext.getCmp('txtconcepto').getValue()+
								"','codestpro1': '"+Ext.getCmp('txtcodestpro1').getValue()+
								"','codestpro2': '"+Ext.getCmp('txtcodestpro2').getValue()+
								"','codestpro3': '"+Ext.getCmp('txtcodestpro3').getValue()+
								"','codestpro4': '"+codestpro4+
								"','codestpro5': '"+codestpro5+
								"','estcla': '"+Ext.getCmp('hidestcla').getValue()+
								"','tipodoc': tipodoc,'estconpre':  estconpre,'fecope': '"+Ext.get('txtfecha').getValue()+
								"','sistema': sistema,'vista': vista ";				
			objdata=objdata+ ",datosSol:[";			
			if (total>0)
			{					
				for (i=0; i < total; i++)
				{
					if (i==0)
					{
						objdata = objdata +"{'numsol':'"+ arrSelSol[i].get('numsol')+"','fecemisol':'"+ arrSelSol[i].get('fecemisol')+ 
								"','monsol':'"+ arrSelSol[i].get('monsol')+ "','pagado':'"+ arrSelSol[i].get('pagado')+ "'}";
								//"','consol':'"+ arrSelSol[i].get('consol')+ "','monsol':'"+ arrSelSol[i].get('monsol')+ "','pagado':'"+ arrSelSol[i].get('pagado')+ "'}";
					}
					else
					{
						objdata = objdata +",{'numsol':'"+ arrSelSol[i].get('numsol')+ "','fecemisol':'"+ arrSelSol[i].get('fecemisol')+ 
								 "','monsol':'"+ arrSelSol[i].get('monsol')+ "','pagado':'"+ arrSelSol[i].get('pagado')+ "'}";
								//"','consol':'"+ arrSelSol[i].get('consol')+ "','monsol':'"+ arrSelSol[i].get('monsol')+ "','pagado':'"+ arrSelSol[i].get('pagado')+ "'}";
					}
				}			
			
				
				objdata = objdata + ']}';
				//objdata= eval('(' + objdata + ')');
				objdata = Ext.util.JSON.decode(objdata);
				objdata = Ext.util.JSON.encode(objdata);	
				parametros = 'objdata='+objdata;
				Ext.Ajax.request({
					url : rutaSol,
					params : parametros,
					method: 'POST',
					success: function (resultad,request)
					{
						datos = resultad.responseText;
						Ext.Msg.hide();
						var datajson = Ext.util.JSON.decode(datos);	
						if(datajson.raiz.valido==true)
						{
							//gridSolicitud.store.loadData(datajson);
							Ext.MessageBox.alert('Mensaje', datajson.raiz.mensaje+' Solicitudes Transferidas ');
							irCancelar(); 
						}
						else
						{
							Ext.Msg.alert('Error', datajson.raiz.mensaje);
						}
					},
					failure: function ( resultad, request)
					{ 
						Ext.Msg.hide();
						Ext.Msg.alert('Error', 'No se logró procesar la información'); 
					}
				});	
			}
			else
			{
				Ext.Msg.alert('Mensaje','Debe seleccionar al menos una solicitud');
			}		
		}
	}


/***********************************************************************************
* @Función para eliminar(cancelar) la transferencia de solicitudes.
* @parametros: 
* @retorno: 
* @fecha de creación: 28/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function irEliminar()
	{
		
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
	