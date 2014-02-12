/********************************************************************************
*@archivo javascript para el catálogo de estructuras presupuestarias de nivel 3
*@version: 1.0
*@fecha de creación: 26/11/2008.
*@autor: Ing. Gusmary Balza.
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
**********************************************************************************/
var datos          = null;
var gridEstPre4    = null;
var ventanaEstPre4 = null;
var iniciargrid    = false;
var parametros     = '';
var rutaEstPre4    = '../../controlador/spg/sigesp_ctr_spg_estpro4.php';


/***************************************************************************************
* @Función genérica para el uso del catálogo de estructuras presupuestarias de nivel 3
* @parametros: 
* @retorno: 
* @fecha de creación: 26/11/2008. 
* @autor: Ing. Gusmary Balza. 
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
****************************************************************************************/
	function catalogoEstructura4()
	{	
		this.mostrarCatalogoEstructura4 = mostrarCatalogoEstPre4;
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
	function actualizarDataEstPre4(criterio,cadena)
	{
		var myJSONObject ={
			'oper': 'catalogo',
			'codestpro1': codestpro1,
			'codestpro2': codestpro2,
			'codestpro3': codestpro3,
			'cadena': cadena,
			'criterio': criterio,
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(myJSONObject);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
		url : rutaEstPre4,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request )
		{ 
			datos = resultado.responseText;
			if (datos!='')
			{
				var DatosNuevo = eval('(' + datos + ')');
				gridEstPre4.store.loadData(DatosNuevo);
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
	function mostrarTituloGrid4(nombrenivel1,nombrenivel2,nombrenivel3)
	{
		var objdata ={
			'oper': 'cargarTituloGridCat',
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
		url : rutaEstPre4,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request )
		{ 
			datos = resultado.responseText;
			var datajson = Ext.util.JSON.decode(datos);
			if (datajson.raiz!=null)
			{
				var datajson = eval('(' + datos + ')');
				gridEstPre4.getColumnModel().setColumnHeader(1,datajson.raiz.nivel1);
				gridEstPre4.getColumnModel().setColumnHeader(2,datajson.raiz.nivel2);
				gridEstPre4.getColumnModel().setColumnHeader(3,datajson.raiz.nivel3);
				gridEstPre4.getColumnModel().setColumnHeader(4,datajson.raiz.nivel4);
				Ext.getCmp('venEstPre4').setTitle('Catálogo de Estructuras Presupuestarias  '+datajson.raiz.nivel4);
				Ext.getCmp('codestpro1aux3').setValue(nombrenivel1);
				Ext.getCmp('codestpro2aux3').setValue(nombrenivel2);
				Ext.getCmp('codestpro3aux3').setValue(nombrenivel3);
				
				var label1 = Ext.DomQuery.select('label[for="codestpro1aux3"]');
        		Ext.DomHelper.overwrite(label1[0],datajson.raiz.nivel1+':');
        		
        		var label2 = Ext.DomQuery.select('label[for="codestpro2aux3"]');
        		Ext.DomHelper.overwrite(label2[0],datajson.raiz.nivel2+':');
        		
        		var label3 = Ext.DomQuery.select('label[for="codestpro3aux3"]');
        		Ext.DomHelper.overwrite(label3[0],datajson.raiz.nivel2+':');		
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
	function mostrarCatalogoEstPre4(arrTxt, arrValores,nombrenivel1,nombrenivel2,nombrenivel3)
	{
		var objdata ={
			'oper': 'catalogo', 
			'codestpro1': codestpro1,
			'codestpro2': codestpro2,
			'codestpro3': codestpro3,
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
		url : rutaEstPre4,
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
					{name: 'codestpro2'},
					{name: 'codestpro3'},
					{name: 'codestpro4'},
					{name: 'estcla'},
					{name: 'denestpro4'},
					]);
			        
			        gridEstPre4 = new Ext.grid.GridPanel({
						width:900,
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
			            	{header: 'Proyecto y/o Acción Centralizada', width: 190, sortable: true, dataIndex: 'codestpro1'},
							{header: 'Acción Específica', width: 190, sortable: true, dataIndex: 'codestpro2'},
							{header: 'Código 3', width: 190, sortable: true, dataIndex: 'codestpro3'},
							{header: 'Código', width: 100, sortable: true, dataIndex: 'codestpro4'},
							//{header: 'Estatus', width: 150, sortable: true, dataIndex: 'estcla',renderer:mostrarEstatus},
							{header: 'Denominacion', width: 330, sortable: true, dataIndex: 'denestpro4'},
						]),
						 sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
			            viewConfig: {
			                            forceFit:true
			                        },
						autoHeight:true,
						stripeRows: true
			                   });			                  				
				
					mostrarTituloGrid4(nombrenivel1,nombrenivel2,nombrenivel3);
						
					if (pantalla=='traspasosol')
					{
						gridEstPre4.getSelectionModel().singleSelect = true;	 
					}
					else 
					{
						gridEstPre4.getSelectionModel().singleSelect = false;	
					}		
										
					var panelEstPre4 = new Ext.FormPanel({
						labelWidth: 90, 
						frame:true,
						title: 'Búsqueda',
						bodyStyle:'padding:5px 5px 0',
						width: 400,
						height:230,
						//defaults: {width: 230},
						defaultType: 'textfield',
						items: [{
							//fieldLabel: 'Est. 1',
							name: 'codestpro1aux3',
							id:'codestpro1aux3',
							disabled: true,
							width:300,
							changeCheck: function()
							{
								  var v = this.getValue();
								  actualizarDataEstPre4('spg_ep4.codestpro1',v);
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
							//fieldLabel: ' Est. 2',
							name: 'codestpro2aux3',
							id:'codestpro2aux3',
							disabled: true,
							width:300,
							changeCheck: function()
							{
								  var v = this.getValue();
								  actualizarDataEstPre4('spg_ep4.codestpro2',v);
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
							//fieldLabel: ' Est. 3',
							name: 'codestpro3aux3',
							id:'codestpro3aux3',
							disabled:true,
							width:200,
							changeCheck: function()
							{
								  var v = this.getValue();
								  actualizarDataEstPre4('spg_ep4.codestpro3',v);
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
							fieldLabel: 'Código',
							name: 'codestpro4',
							id:'codestpro4',
							width:200,
							changeCheck: function()
							{
								  var v = this.getValue();
								  actualizarDataEstPre4('codestpro4',v);
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
							name: 'denestpro4',
							id:'denestpro4',
							width:200,
							changeCheck: function()
							{
								var v = this.getValue();
								actualizarDataEstPre4('denestpro4',v);
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
					
						ventanaEstPre4 = new Ext.Window(
						{
							title: 'Cat&aacute;logo de Estructuras Presupuestarias 4',
					    	autoScroll:true,
					    	id: 'venEstPre4',
			                width:900,
			                height:500,
			                modal: true,
			                closeAction:'hide',
			                plain: false,
			                items:[panelEstPre4,gridEstPre4],
			                buttons: [{
			                	text:'Aceptar',  
			                    handler: function()
								{ 
									if (pantalla=='traspasosol')
									{
										for (i=0;i<arrTxt.length;i++)
										{
											Ext.getCmp(arrTxt[i]).setValue(gridEstPre4.getSelectionModel().getSelected().get(arrValores[i]));
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
											seleccionados = gridEstPre4.getSelectionModel().getSelections();
											for (i=0; i<seleccionados.length; i++)
											{
												pasarDatosGridEst(seleccionados[i]);
											}
											
										}
									}*/
									panelEstPre4.destroy();
			                      	ventanaEstPre4.destroy();
								}
								},{
			                     text: 'Salir',
			                     handler: function()
			                     {
			                      	panelEstPre4.destroy();
			                      	ventanaEstPre4.destroy();
			                     }
							}]
						});
			            
					ventanaEstPre4.show();
					if(!iniciargrid)
					{
						gridEstPre4.render('miGrid');
			            iniciargrid=false;
			        }
			        gridEstPre4.getSelectionModel().selectFirstRow();
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
