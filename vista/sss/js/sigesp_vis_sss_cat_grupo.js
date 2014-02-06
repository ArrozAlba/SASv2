/***********************************************************************************
* @Archivo javascript para el catálogo de grupos.
* @fecha de creación: 09/07/2008.
* @autor: Ing. Gusmary Balza
*************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
var datos              = null;
var gridGrupo          = null;
var ventanaGrupo       = null;
var iniciargrid        = false;
var parametros         = '';
var rutaGrupo = '../../controlador/sss/sigesp_ctr_sss_grupo.php';

/***********************************************************************************
* @Función genérica para el uso del catálogo de grupos
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function catalogoGrupo()
	{		
		this.mostrarCatalogo = mostrarCatalogoGrupo;
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
	function actualizarDataGrupo(criterio,cadena)
	{
		var myJSONObject ={
			'oper': 'catalogo',
			'cadena': cadena,
			'criterio': criterio,
			'sistema': siscatGrupo,
			'vista': viscatGrupo
		};
		objdata=JSON.stringify(myJSONObject);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
			url : rutaGrupo,
			params : parametros,
			method: 'POST',
			success: function ( resultado, request )
			{ 
				datos = resultado.responseText;
				if(datos!='')
				{
					var DatosNuevo = eval('(' + datos + ')');
					gridGrupo.store.loadData(DatosNuevo);
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
* @Función que carga los usuarios del grupo
* @parámetros: 
* @retorno: 
* @fecha de creación: 21/05/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function cargarUsuariosGrupo()
	{
		nomgru = Ext.getCmp('txtnombre').getValue();
		var objdata ={
				'oper': 'catalogousuarios',
				'nomgru': nomgru,
				'sistema': siscatGrupo,
				'vista': viscatGrupo
					
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
			url : rutaGrupo,
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
					else
					{
						Ext.MessageBox.alert('Error', myObject.raiz[0].mensaje+' Al cargar los usuarios.');
					}
				}
			}
		});
	}	
	
	
/*******************************************************************************
* @Función para buscar los detalles de un grupo (permisos para constantes,
* nóminas, unidades ejecutoras,tipos de personal  y presupuestos)
* @parametros:
* @retorno: 
* @fecha de creación: 03/11/2008
* @autor: 
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*********************************************************************************/
	function cargarDetalleGrupo() 
	{
		nomgru = Ext.getCmp('txtnombre').getValue();
		var objdata ={
				'oper': 'catalogodetalle',
				'nomgru': nomgru					
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
			url : rutaGrupo,
			params : parametros,
			method: 'POST',
			success: function (resultado,request)
			{	
				datos = resultado.responseText;
				if (datos!='')
				{
					arregloObjs  = datos.split('|');
					datajsonPer  = eval('(' + arregloObjs[0] + ')');
					datajsonCons = eval('(' + arregloObjs[1] + ')');
					datajsonNom  = eval('(' + arregloObjs[2] + ')');
					datajsonUni  = eval('(' + arregloObjs[3] + ')');
					datajsonEst  = eval('(' + arregloObjs[4] + ')');
					if (datajsonPer.raiz=='')
					{
						datajsonPer = {'raiz':[{'codemp':'','codtippersss':'','dentippersss':''}]};						
					}
					gridPer.store.loadData(datajsonPer);
					if (datajsonCons.raiz=='')
					{
						datajsonCons = {'raiz':[{'codemp':'','codnom':'','codcons':'','nomcon':''}]};						
					}
					gridCons.store.loadData(datajsonCons);
					if (datajsonNom.raiz=='')
					{
						datajsonNom = {'raiz':[{'codemp':'','codnom':'','desnom':''}]};	
					}
					gridNom.store.loadData(datajsonNom);
					if (datajsonUni.raiz=='')
					{
						datajsonUni = {'raiz':[{'codemp':'','coduniadm':'','denuniadm':''}]};	
					}
					gridUni.store.loadData(datajsonUni);
					if (datajsonEst.raiz=='')
					{
						datajsonEst = {'raiz':[{'codest':'','nombre':''}]};	
					}
					gridPre.store.loadData(datajsonEst);
				}
			}
		});
	}
		
	
/***********************************************************************************
* @Función que busca el listado de grupos.
* @parámetros: form: id del formulario, 
* fieldset: id del fieldset,
* array: arrerglo con los campos del formulario
* arrayRecord: arreglo con los campos de la base de datos.
* @fecha de creación: 09/07/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificacion: 23/07/2008
* @autor: Ing. Gusmary Balza.
* @descripcion: modificación al handler del aceptar, 
* inclusión de dos parámetros de tipo arreglo.
***********************************************************************************/
	function mostrarCatalogoGrupo(array, arrayRecord)
	{
		var objdata ={
			'oper': 'catalogo', 
			'sistema': siscatGrupo,
			'vista': viscatGrupo
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
		url : rutaGrupo,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request ) 
		{ 
			datos = resultado.responseText;
			var myObject = eval('(' + datos + ')');
			if(myObject.raiz[0].valido==true)
			{
				var RecordDef = Ext.data.Record.create([
				{name: 'nomgru'},
				{name: 'nota'}	
				]);
		       
		       gridGrupo = new Ext.grid.GridPanel({
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
						{header: 'Nombre', width: 50, sortable: true, dataIndex: 'nomgru'},
					]),
		            viewConfig: {
		                            forceFit:true
		                        },
					autoHeight:true,
					stripeRows: true
		        });
		                   
				var panelBusqueda = new Ext.FormPanel({
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
						fieldLabel: 'Nombre',
						name: 'nomgrupo',
						id:'nomgrupo',
						changeCheck: function()
						{
							var v = this.getValue();
							actualizarDataGrupo('nomgru',v);
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
					ventanaGrupo = new Ext.Window(
					{
						title: 'Cat&aacute;logo de Grupos',
				    	autoScroll:true,
		                width:500,
		                height:400,
		                modal: true,
		                closable: false,
		                plain: false,
		                items:[panelBusqueda,gridGrupo],
		                buttons: [{
		                	text:'Aceptar',  
		                    handler: function()
							{
								if ((pantalla=='grupo') || (pantalla=='permisos'))
								{
									for (i=0;i<array.length;i++)
									{
										Ext.getCmp(array[i]).setValue(gridGrupo.getSelectionModel().getSelected().get(arrayRecord[i]));
									}
									if (pantalla=='grupo')
									{
										cargarUsuariosGrupo();
										cargarDetalleGrupo();
									}	
								}
								else
								{
									pasarDatosGrid();
								}
								panelBusqueda.destroy();
		                      	ventanaGrupo.destroy();
							}
							},{
		                     text: 'Salir',
		                     handler: function()
		                     {
		                      	panelBusqueda.destroy();
		                      	ventanaGrupo.destroy();
		                     }
						}]
					});
		        ventanaGrupo.show();
				if(!iniciargrid)
				{
					gridGrupo.render('miGrid');
		            iniciargrid=false;
		        }
		        gridGrupo.getSelectionModel().selectFirstRow();
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