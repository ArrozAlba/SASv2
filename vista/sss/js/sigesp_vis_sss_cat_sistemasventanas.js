/***********************************************************************************
* @Archivo javascript para el catálogo de menus.
* @fecha de creación: 10/11/2008.
* @autor: Ing. Gusmary Balza
*************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
var gridMenu          = null;
var ventanaMenu       = null;
var iniciargrid        = false;
var parametros         = '';
var rutaMenu = '../../controlador/sss/sigesp_ctr_sss_enviocorreo.php';

/***********************************************************************************
* @Función genérica para el uso del catálogo de menu
* @parametros: 
* @retorno: 
* @fecha de creación: 10/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function catalogoMenu()
	{		
		this.mostrarCatalogo = mostrarCatalogoMenu;
	}


/***********************************************************************************
* @Función que acualiza el catalgo para buscar por determinado campo
* @parametros: criterio: campo por el que se actualiza
*			   cadena: campo a actualizar
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function actualizarDataMenu(criterio,cadena)
	{
		var myJSONObject ={
			'oper': 'filtrardatos',
			'codsis': codsistema,
			'cadena': cadena,
			'criterio': criterio,
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(myJSONObject);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
			url : rutaMenu,
			params : parametros,
			method: 'POST',
			success: function ( resultado, request )
			{ 
				datos = resultado.responseText;
				if(datos!='')
				{
					var DatosNuevo = eval('(' + datos + ')');
					gridMenu.store.loadData(DatosNuevo);
				}
			}
		});
	}


/***********************************************************************************
* @*Obtener el valor de los caracteres de la caja texto
* @parámetros: obj --> caja de texto.
* @retorno: 
* @fecha de creación: 21/05/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function agregarKeyPress(Obj)
	{
		Ext.form.TextField.superclass.initEvents.call(Obj);
		if(Obj.validationEvent == 'keyup')
		{
			Obj.validationTask = new Ext.util.DelayedTask(Obj.validate, Obj);
			Obj.el.on('keyup', Obj.filterValidation, Obj);
		}
		else if(Obj.validationEvent !== false)
		{
			Obj.el.on(Obj.validationEvent, Obj.validate, Obj, {buffer: Obj.validationDelay});
		}
		if(Obj.selectOnFocus || Obj.emptyText)
		{
			Obj.on('focus', Obj.preFocus, Obj);
			if(Obj.emptyText)
			{
				Obj.on('blur', Obj.postBlur, Obj);
				Obj.applyEmptyText();
			}
		}
		if(Obj.maskRe || (Obj.vtype && Obj.disableKeyFilter !== true && (Obj.maskRe = Ext.form.VTypes[Obj.vtype+'Mask']))){
			Obj.el.on('keypress', Obj.filterKeys, Obj);
		}
		if(Obj.grow)
		{
			Obj.el.on('keyup', Obj.onKeyUp,  Obj, {buffer:50});
			Obj.el.on('click', Obj.autoSize,  Obj);
		}
		Obj.el.on('keyup', Obj.changeCheck, Obj);
	}


/***********************************************************************************
* @Función que carga los usuarios del sistema
* @parámetros: 
* @retorno: 
* @fecha de creación: 11/11/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function cargarUsuarios()
	{
		codmenu = Ext.getCmp('hidmenu').getValue();
		var objdata ={
				'oper': 'catalogodetalle',
				'codsis': codsistema,
				'codmenu': codmenu,	
				'sistema': sistema,
				'vista': vista
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
			url : rutaMenu,
			params : parametros,
			method: 'POST',
			success: function (resultado,request)
			{
				datos = resultado.responseText;
				if (datos!='')
				{
					var myObject = eval('(' + datos + ')');
					if(myObject.raiz[0].valido==true)
					{
						gridUsu.store.loadData(myObject);
					}
				}
			}
		});
	}
	
	
/***********************************************************************************
* @Función que busca el listado de menus.
* @parámetros:
* array: arrerglo con los campos del formulario
* arrayRecord: arreglo con los campos de la base de datos.
* @fecha de creación: 10/11/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificacion: 
* @autor: 
* @descripcion: 
***********************************************************************************/
	function mostrarCatalogoMenu(array, arrayRecord)
	{
		var objdata ={
			'oper': 'obtenerMenu', 
			'codsis': codsistema,
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
		url : rutaMenu,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request ) 
		{ 
			datos = resultado.responseText;
			var myObject = eval('(' + datos + ')');
			if(myObject.raiz[0].valido==true)
			{
				var RecordDef = Ext.data.Record.create([
				{name: 'codmenu'},
				{name: 'codsis'},
				{name: 'nomlogico'}	
				]);
		       
		       gridMenu = new Ext.grid.GridPanel({
					width:500,
					autoScroll:true,
		            border:true,
		            ds: new Ext.data.Store({
					proxy: new Ext.data.MemoryProxy(myObject),
					reader: new Ext.data.JsonReader({
					    root: 'raiz',                
					    id: 'id'   
		                },
					RecordDef
					),
					data: myObject
		            }),
		            cm: new Ext.grid.ColumnModel([
						{header: 'Código del Menú', width: 100, sortable: true, dataIndex: 'codmenu'},
						//{header: 'Código del Sistema', width: 100, sortable: true, dataIndex: 'codsis'},
						{header: 'Nombre', width: 300, sortable: true, dataIndex: 'nomlogico'},
					]),
		            viewConfig: {
		                            forceFit:true
		                        },
					autoHeight:true,
					stripeRows: true
		        });
		                   
				var panelMenu = new Ext.FormPanel({
					labelWidth: 75, 
					url:'save-form.php',
					frame:true,
					title: 'Búsqueda',
					bodyStyle:'padding:5px 5px 0',
					width: 350,
					height:120,
					defaults: {width: 230},
					defaultType: 'textfield',
					items: [{					
						fieldLabel: 'Código',
						name: 'codmenu',
						id:'codmenu',
						changeCheck: function()
						{
							var v = this.getValue();
							actualizarDataMenu('codmenu',v);
							if(String(v) !== String(this.startValue))
							{
								this.fireEvent('change', this, v, this.startValue);
							} 
						},
						initEvents : function()
						{
							agregarKeyPress(this);
						}
						
					},{	
						fieldLabel: 'Nombre',
						name: 'nomlogico',
						id:'nomlogico',
						changeCheck: function()
						{
							var v = this.getValue();
							actualizarDataMenu('nomlogico',v);
							if(String(v) !== String(this.startValue))
							{
								this.fireEvent('change', this, v, this.startValue);
							} 
						},
						initEvents : function()
						{
							agregarKeyPress(this);
						}
					}]
				});
					ventanaMenu = new Ext.Window(
					{
						title: 'Cat&aacute;logo de Menú',
				    	autoScroll:true,
		                width:500,
		                height:400,
		                modal: true,
		                closable: false,
		                plain: false,
		                items:[panelMenu,gridMenu],
		                buttons: [{
		                	text:'Aceptar',  
		                    handler: function()
							{
								if (pantalla=='enviocorreo')
								{
									for (i=0;i<array.length;i++)
									{
										Ext.getCmp(array[i]).setValue(gridMenu.getSelectionModel().getSelected().get(arrayRecord[i]));
									}
									cargarUsuarios();
									
								}
								else
								{
									pasarDatosGrid();
								}
								panelMenu.destroy();
		                      	ventanaMenu.destroy();
							}
							},{
		                     text: 'Salir',
		                     handler: function()
		                     {
		                      	panelMenu.destroy();
		                      	ventanaMenu.destroy();
		                     }
						}]
					});
		        ventanaMenu.show();
				if(!iniciargrid)
				{
					gridMenu.render('miGrid');
		            iniciargrid=false;
		        }
		        gridMenu.getSelectionModel().selectFirstRow();
		    }
		    else
		    {
				Ext.MessageBox.alert('Mensaje', myObject.raiz[0].mensaje);
				close();
		    }
        },
        failure: function ( resultado, request)
		{ 
			Ext.MessageBox.alert('Error', myObject.raiz[0].mensaje); 
        }
		});
}