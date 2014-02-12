/********************************************************************************
*@archivo javascript para el catálogo de estructuras presupuestarias de nivel 2
*@version: 1.0
*@fecha de creación: 26/11/2008.
*@autor: Ing. Gusmary Balza.
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
**********************************************************************************/
var datos          = null;
var gridEstPre2    = null;
var ventanaEstPre2 = null;
var iniciargrid    = false;
var parametros     = '';
var rutaEstPre2    = '../../controlador/spg/sigesp_ctr_spg_estpro2.php';
var nombrenivel ='';

/***************************************************************************************
* @Función genérica para el uso del catálogo de estructuras presupuestarias de nivel 2
* @parametros: 
* @retorno: 
* @fecha de creación: 26/11/2008. 
* @autor: Ing. Gusmary Balza. 
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
****************************************************************************************/
	function catalogoEstructura2()
	{	
		this.mostrarCatalogoEstructura2 = mostrarCatalogoEstPre2;
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
	function actualizarDataEstPre2(criterio,cadena)
	{
		var myJSONObject ={
			'oper': 'catalogo',
			'codestpro1': codestpro1, 
			'cadena': cadena,
			'criterio': criterio,
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(myJSONObject);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
		url : rutaEstPre2,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request )
		{ 
			datos = resultado.responseText;
			if (datos!='')
			{
				var DatosNuevo = eval('(' + datos + ')');
				gridEstPre2.store.loadData(DatosNuevo);
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
	function mostrarTituloGrid2(nombrenivel1)
	{
		var objdata ={
			'oper': 'cargarTituloGridCat',
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
		url : rutaEstPre2,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request )
		{ 
			datos = resultado.responseText;
			var datajson = Ext.util.JSON.decode(datos);
			if (datajson.raiz!=null)
			{
				var datajson = eval('(' + datos + ')');
				nombrenivel = datajson.raiz.nivel1;
				gridEstPre2.getColumnModel().setColumnHeader(1,datajson.raiz.nivel1);
				gridEstPre2.getColumnModel().setColumnHeader(2,datajson.raiz.nivel2);
				Ext.getCmp('venEstPre2').setTitle('Catálogo de Estructuras Presupuestarias  '+datajson.raiz.nivel2);
				Ext.getCmp('codestpro1aux').setValue(nombrenivel1);
				
				var label1 = Ext.DomQuery.select('label[for="codestpro1aux"]');
        		Ext.DomHelper.overwrite(label1[0],datajson.raiz.nivel1+':');
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
	function mostrarCatalogoEstPre2(arrTxt, arrValores,nombrenivel1)
	{
		var objdata ={
			'oper': 'catalogo',
			'codestpro1': codestpro1, 
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
		url : rutaEstPre2,
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
					{name: 'estcla'},
					{name: 'denestpro2'},
					]);
			        
			        gridEstPre2 = new Ext.grid.GridPanel({
						width:700,
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
			            	{header: 'Proyecto y/o Acción Centralizada', width: 200, sortable: true, dataIndex: 'codestpro1'},
							{header: 'Código', width: 200, sortable: true, dataIndex: 'codestpro2'},
							//{header: 'Estatus', width: 150, sortable: true, dataIndex: 'estcla',renderer:mostrarEstatus},
							{header: 'Denominacion', width: 300, sortable: true, dataIndex: 'denestpro2'},
						]),
						 sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
			            viewConfig: {
			                            forceFit:true
			                        },
						autoHeight:true,
						stripeRows: true
			                   });			                  				
				
					mostrarTituloGrid2(nombrenivel1);
					
					if (pantalla=='traspasosol')
					{
						gridEstPre2.getSelectionModel().singleSelect = true;	 
					}
					else 
					{
						gridEstPre2.getSelectionModel().singleSelect = false;	
					}	
															
					var panelEstPre2 = new Ext.FormPanel({
						labelWidth: 90, 
						frame:true,
						title: 'Búsqueda',
						bodyStyle:'padding:5px 5px 0',
						width: 400,
						height:160,
						defaults: {width: 230},
						defaultType: 'textfield',
						items: [{
							//fieldLabel: 'Est. de nivel 1',
							name: 'codestpro1aux',
							id:'codestpro1aux',
							disabled: true,
							width:300,
							changeCheck: function()
							{
								  var v = this.getValue();
								  actualizarDataEstPre2('spg_ep2.codestpro1',v);
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
							name: 'codestpro2',
							id:'codestpro2',
							width:200,
							changeCheck: function()
							{
								  var v = this.getValue();
								  actualizarDataEstPre2('codestpro2',v);
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
							name: 'denestpro2',
							id:'denestpro2',
							width:200,
							changeCheck: function()
							{
								var v = this.getValue();
								actualizarDataEstPre2('denestpro2',v);
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
					
						ventanaEstPre2 = new Ext.Window(
						{
							title: 'Cat&aacute;logo de Estructuras Presupuestarias Acciones Específicas',
					    	autoScroll:true,
					    	id: 'venEstPre2',
			                width:700,
			                height:400,
			                modal: true,
			                closeAction:'hide',
			                plain: false,
			                items:[panelEstPre2,gridEstPre2],
			                buttons: [{
			                	text:'Aceptar',  
			                    handler: function()
								{ 
									if (pantalla=='traspasosol')
									{
										for (i=0;i<arrTxt.length;i++)
										{
											Ext.getCmp(arrTxt[i]).setValue(gridEstPre2.getSelectionModel().getSelected().get(arrValores[i]));
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
											seleccionados = gridEstPre2.getSelectionModel().getSelections();
											for (i=0; i<seleccionados.length; i++)
											{
												pasarDatosGridEst(seleccionados[i]);
											}
											
										}
									}*/
									panelEstPre2.destroy();
			                      	ventanaEstPre2.destroy();
								}
								},{
			                     text: 'Salir',
			                     handler: function()
			                     {
			                      	panelEstPre2.destroy();
			                      	ventanaEstPre2.destroy();
			                     }
							}]
						});
			            
					ventanaEstPre2.show();
					if(!iniciargrid)
					{
						gridEstPre2.render('miGrid');
			            iniciargrid=false;
			        }
			        gridEstPre2.getSelectionModel().selectFirstRow();
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
