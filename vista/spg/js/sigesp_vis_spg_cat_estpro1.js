/********************************************************************************
*@archivo javascript para el catálogo de estructuras presupuestarias de nivel 1
*@version: 1.0
*@fecha de creación: 26/11/2008.
*@autor: Ing. Gusmary Balza.
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
**********************************************************************************/
var datos          = null;
var gridEstPre1    = null;
var ventanaEstPre1 = null;
var iniciargrid    = false;
var parametros     = '';
var rutaEstPre1    = '../../controlador/spg/sigesp_ctr_spg_estpro1.php';


/***************************************************************************************
* @Función genérica para el uso del catálogo de estructuras presupuestarias de nivel 1
* @parametros: 
* @retorno: 
* @fecha de creación: 26/11/2008. 
* @autor: Ing. Gusmary Balza. 
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
****************************************************************************************/
	function catalogoEstructura1()
	{	
		this.mostrarCatalogoEstructura1 = mostrarCatalogoEstPre1;
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
*****************************************************************************/
	function actualizarDataEstPre1(criterio,cadena)
	{
		var myJSONObject ={
			'oper': 'catalogo',
			'cadena': cadena,
			'criterio': criterio,
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(myJSONObject);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
		url : rutaEstPre1,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request )
		{ 
			datos = resultado.responseText;
			if (datos!='')
			{
				var DatosNuevo = eval('(' + datos + ')');
				gridEstPre1.store.loadData(DatosNuevo);
			}
		}
		});
	}


/*************************************************************************
*Obtener el valor de los caracteres de la caja texto
*@parámetros: obj --> caja de texto.
*@retorna: valor obtenido del objeto.
*@fecha de creación:  21/05/2008
*@Función predeterminada.
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
**************************************************************************/		
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
* @Función que muestra el nombre del estatus de clasificación
* @parámetros: 
* @retorno: 
* @fecha de creación: 13/11/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function mostrarEstatus(est)
	{
		if (est=='P')
		{
			return 'Proyecto';
		}
		else if (est=='A')
		{
			return 'Acción Centralizada';	
		}
	}
		
					
/***********************************************************************************
* @Función que muestra los titulos de las columnas de la grid de acuerdo al nivel
* de la estructura.
* @parámetros: 
* @retorno: 
* @fecha de creación: 28/11/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/				
	function mostrarTituloGrid1()
	{
		var objdata ={
			'oper': 'cargarTituloGridCat',
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
		url : rutaEstPre1,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request )
		{ 
			datos = resultado.responseText;
			var datajson = Ext.util.JSON.decode(datos);
			if (datajson.raiz!=null)
			{
				var datajson = eval('(' + datos + ')');
				//gridEstPre1.getColumnModel().setColumnHeader(1,datajson.raiz.nivel1);
				//ventanaEstPre1
				Ext.getCmp('venEstPre1').setTitle('Catálogo de Estructuras Presupuestarias  '+datajson.raiz.nivel1);
			}
		}
		});
	}
		
		
/***************************************************************************
* @Función que busca el listado de personal.
* @parámetros: array: arreglo con los campos del formulario
* 			arrValores: arreglo con los campos de la base de datos.
* @fecha de creación: 07/10/2008
* @autor: Gusmary Balza.
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
****************************************************************************/
	function mostrarCatalogoEstPre1(arrTxt, arrValores)
	{
		var objdata ={
			'oper': 'catalogo', 
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
		url : rutaEstPre1,
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
					{name: 'codestpro1'},
					{name: 'estcla'},
					{name: 'denestpro1'},
					]);
			        
			        var tiposeleccion = new Ext.grid.CheckboxSelectionModel({singleSelect:false});
			        
			        gridEstPre1 = new Ext.grid.GridPanel({
						width:600,
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
			            tiposeleccion,
							{header: 'Código', id: 'cod1', width: 200, sortable: true, dataIndex: 'codestpro1'},
							//{header: 'Estatus', width: 150, sortable: true, dataIndex: 'estcla',renderer:mostrarEstatus},
							{header: 'Denominación', width: 400, sortable: true, dataIndex: 'denestpro1'},
						]),
						 //sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
						 sm: tiposeleccion,
						 		viewConfig: {
			                            forceFit:true
			                        },
						autoHeight:true,
						stripeRows: true
			                   });	
			                   		                  				
					
					mostrarTituloGrid1();
			
					if (pantalla=='traspasosol')
					{
						gridEstPre1.getSelectionModel().singleSelect = true;	 
					}
					else 
					{
						gridEstPre1.getSelectionModel().singleSelect = false;	
					}	
															
					var panelEstPre1 = new Ext.FormPanel({
						labelWidth: 75, 
						frame:true,
						title: 'Búsqueda',
						bodyStyle:'padding:5px 5px 0',
						width: 350,
						height:100,
						defaults: {width: 230},
						defaultType: 'textfield',
						items: [{
							fieldLabel: 'Código',
							name: 'codestpro1',
							id:'codestpro1',
							width:200,
							changeCheck: function()
							{
								  var v = this.getValue();
								  actualizarDataEstPre1('codestpro1',v);
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
							fieldLabel: 'Denominación',
							name: 'denestpro1',
							id:'denestpro1',
							width:200,
							changeCheck: function()
							{
								var v = this.getValue();
								actualizarDataEstPre1('denestpro1',v);
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
					
						ventanaEstPre1 = new Ext.Window(
						{
							title: 'Cat&aacute;logo de Estructuras Presupuestarias Proyectos y/o Acciones Centralizadas',
					    	autoScroll:true,
					    	id:'venEstPre1',
			                width:600,
			                height:400,
			                modal: true,
			                closeAction:'hide',
			                plain: false,
			                items:[panelEstPre1,gridEstPre1],
			                buttons: [{
			                	text:'Aceptar',  
			                    handler: function()
								{ 
									if (pantalla=='traspasosol')
									{
										for (i=0;i<arrTxt.length;i++)
										{
											Ext.getCmp(arrTxt[i]).setValue(gridEstPre1.getSelectionModel().getSelected().get(arrValores[i]));
										}
									}
									/*else
									{
										if (validarExistenciaEst()==true)
										{
											Ext.Msg.alert('Mensaje','Registro ya agregado');
										}											
										else
										{
											seleccionados = gridEstPre1.getSelectionModel().getSelections();
											for (i=0; i<seleccionados.length; i++)
											{
												pasarDatosGridEst(seleccionados[i]);
											}
											
										}
									}*/
									panelEstPre1.destroy();
			                      	ventanaEstPre1.destroy();
								}
								},{
			                     text: 'Salir',
			                     handler: function()
			                     {
			                      	panelEstPre1.destroy();
			                      	ventanaEstPre1.destroy();
			                     }
							}]
						});
			            
					ventanaEstPre1.show();
					if(!iniciargrid)
					{
						gridEstPre1.render('miGrid');
			            iniciargrid=false;
			        }
			        gridEstPre1.getSelectionModel().selectFirstRow();
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
