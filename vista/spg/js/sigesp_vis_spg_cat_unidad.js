/*************************************************************************
*@archivo javascript para el catálogo de unidades ejecutoras
*@version: 1.0
*@fecha de creación: 09/08/2008.
*@autor: Ing. Gusmary Balza.
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
**************************************************************************/
var datos               = null;
var gridUnidad         = null;
var ventanaUnidad      = null;
var iniciargrid             = false;
var parametros         = '';
var rutaUnidad         = '../../controlador/spg/sigesp_ctr_spg_unidad.php';
var ruta = '../../controlador/sss/sigesp_ctr_sss_usuariospermisos.php';

/******************************************************************************
* @Función genérica para el uso del catálogo de unidades ejecutoras
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*******************************************************************************/
	function catalogoUnidad()
	{	
		this.mostrarCatalogoUnidad = mostrarCatalogoUnidad;
	}


/*************************************************************************
* @Función que acualiza el catalgo para buscar por determinado campo
* @parametros: criterio: campo por el que se actualiza
* cadena: campo a actualizar
* @retorno:
* @fecha de creación:
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***************************************************************************/
	function actualizarDataUnidad(criterio,cadena)
	{
		var myJSONObject ={
			'oper': 'catalogo',
			'cadena': cadena,
			'criterio': criterio,
			'coduniadm':'',
			'denuniadm':'',
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(myJSONObject);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
		url : rutaUnidad,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request )
		{ 
			datos = resultado.responseText;
			if (datos!='')
			{
				var DatosNuevo = eval('(' + datos + ')');
				gridUnidad.store.loadData(DatosNuevo);
			}
		}
		});
	}


/**************************************************************************
*Obtener el valor de los caracteres de la caja texto
*@parámetros: obj --> caja de texto.
*@retorna: valor obtenido del objeto.
*@fecha de creación:  21/05/2008
*@Función predeterminada.
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*****************************************************************************/		
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
	
	
/***************************************************************************
* @Función para validar que el registro seleccionado de
* @la grid del catalogo no exista en la grid del formulario
* @parametros:
* @retorno: true si el registro ya está.
* @fecha de creación: 19/08/2008
* @autor: 
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*****************************************************************************/
	function validarExistenciaUni()
	{
		UnidadCat    = gridUnidad.getSelectionModel().getSelected().get('coduniadm');
		cantUnidad   = gridUni.store.getCount()-1;
		arrAuxUnidad = gridUni.store.getRange(0,cantUnidad);
		for (i=0; i<=arrAuxUnidad.length-1; i++)
		{
			if (arrAuxUnidad[i].get('codcon')==UnidadCat)
			{
				return true;
			}
		}		
	}


/***************************************************************************
* @Función para insertar el registro seleccionado
* @de la grid del catalgo a la grid del formulario.
* @fecha de creación: 19/08/2008
* @autor: Gusmary Balza
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*****************************************************************************/
	function pasarDatosGridUni(datos)
	{
		p = new RecordDefUni
		({
		'codsis':'',
		'coduniadm':'',
		'denuniadm':''
		});
		gridUni.store.insert(0,p);
		p.set('denuniadm',datos.get('denuniadm'));
		p.set('coduniadm',datos.get('coduniadm'));
	}

	
/***********************************************************************************
* @Función que carga los usuarios de la unidad
* @parámetros: 
* @retorno: 
* @fecha de creación: 29/10/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function cargarUsuariosUnidad()
	{
		codtippersss = Ext.getCmp('txtcoduniadm').getValue();
		codsis = Ext.getCmp('cmbsistema').getValue();
		var objdata ={
				'oper': 'catalogodetalle',
				'codtippersss': codtippersss,
				'codsis': codsis,
				'campo': 'coduniadm',
				'tabla': 'spg_unidadadministrativa',
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
	
	
/**************************************************************************
* @Función que busca el listado de unidades.
* @parámetros: form: id del formulario, 
* 			fieldset: id del fieldset,
* 			array: arreglo con los campos del formulario
* 			arrValores: arreglo con los campos de la base de datos.
* 			@fecha de creación: 07/10/2008
* 		@autor: Gusmary Balza.
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
****************************************************************************/
	function mostrarCatalogoUnidad(arrTxt, arrValores)
	{
		var objdata ={
			'oper': 'catalogo', 
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
		url : rutaUnidad,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request ) 
		{ 
			datos = resultado.responseText;
			if (datos!='')
			{
				var myObject = eval('(' + datos + ')');
				if (myObject.raiz[0].valido==true)
				{
					var RecordDef = Ext.data.Record.create([
					{name: 'coduniadm'},     
					{name: 'denuniadm'}
					]);
			       gridUnidad = new Ext.grid.GridPanel({
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
			             new Ext.grid.CheckboxSelectionModel(),
							{header: 'Código', width: 30, sortable: true,   dataIndex: 'coduniadm'},
			                {header: 'Nombre', width: 50, sortable: true, dataIndex: 'denuniadm'}
						]),
					
						sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
				
			            viewConfig: {
			                            forceFit:true
			                        },
						autoHeight:true,
						stripeRows: true
			         });
			         
					if (pantalla=='usuariosunidad')
					{
						gridUnidad.getSelectionModel().singleSelect = true;	 
					}
					else 
					{
						gridUnidad.getSelectionModel().singleSelect = false;	
					}
					 
					var panelUnidad = new Ext.FormPanel({
						labelWidth: 75, 
						frame:true,
						title: 'Búsqueda',
						bodyStyle:'padding:5px 5px 0',
						width: 350,
						height:120,
						defaults: {width: 230},
						defaultType: 'textfield',
						items: [{
							fieldLabel: 'Código',
							name: 'coduni',
							id:'coduni',
							width:50,
							changeCheck: function()
							{
								  var v = this.getValue();
								  actualizarDataUnidad('coduniadm',v);
								  if (String(v) !== String(this.startValue))
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
							name: 'nomuni',
							id:'nomuni',
							changeCheck: function()
							{
								var v = this.getValue();
								actualizarDataUnidad('denuniadm',v);
								if (String(v) !== String(this.startValue))
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
				
						ventanaUnidad = new Ext.Window(
						{
							title: 'Cat&aacute;logo de Unidades Ejecutoras',
					    	autoScroll:true,
			                width:500,
			                height:400,
			                modal: true,
			                closeAction:'hide',
			                plain: false,
			                items:[panelUnidad,gridUnidad],
			                buttons: [{
			                	text:'Aceptar',  
			                    handler: function()
								{                     	
									if (pantalla=='usuariosunidad')
									{
										for (i=0;i<arrTxt.length;i++)
										{
											Ext.getCmp(arrTxt[i]).setValue(gridUnidad.getSelectionModel().getSelected().get(arrValores[i]));
										}
										cargarUsuariosUnidad();
									}
									else
									{
										if (validarExistenciaUni()==true)
										{
											Ext.Msg.alert('Mensaje','Registro ya agregado');
										}											
										else
										{
											seleccionados = gridUnidad.getSelectionModel().getSelections();
											for (i=0; i<seleccionados.length; i++)
											{
												pasarDatosGridUni(seleccionados[i]);
											}
										}	
									}									
									panelUnidad.destroy();
			                      	ventanaUnidad.destroy();
								}
								},{
			                     text: 'Salir',
			                     handler: function()
			                     {
			                      	panelUnidad.destroy();
			                      	ventanaUnidad.destroy();
			                     }
							}]
						});
			           
					ventanaUnidad.show();
					if(!iniciargrid)
					{
						gridUnidad.render('miGrid');
			            iniciargrid=false;
			        }
			        gridUnidad.getSelectionModel().selectFirstRow();
		        }
		        else
		        {
		        	Ext.MessageBox.alert('Error', myObject.raiz[0].mensaje);
					close();
				}
			}
			else
			{
				Ext.MessageBox.alert('Mensaje', 'No existen datos para mostrar');
			}		
        },
        failure: function ( resultado, request)
		{ 
			Ext.MessageBox.alert('Error', resultado.responseText); 
        }
	   });
	};
