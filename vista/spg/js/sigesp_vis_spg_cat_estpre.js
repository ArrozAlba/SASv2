/*************************************************************************
*@archivo javascript para el catálogo de estructuras presupuestarias
*@version: 1.0
*@fecha de creación: 09/08/2008.
*@autor: Ing. Gusmary Balza.
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***************************************************************************/
var datos         = null;
var gridEstPre    = null;
var ventanaEstPre = null;
var iniciargrid   = false;
var parametros    = '';
var rutaEstPre    = '../../controlador/spg/sigesp_ctr_spg_estpre.php';
var ruta = '../../controlador/sss/sigesp_ctr_sss_usuariospermisos.php';

/******************************************************************************
* @Función genérica para el uso del catálogo de estructuras presupuestarias
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*******************************************************************************/
	function catalogoEstPre()
	{	
		this.mostrarCatalogoEstPre = mostrarCatalogoEstPre;
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
	function actualizarDataEstPre(criterio,cadena)
	{		
		if (pantalla=='traspasosol')
		{
			var objdata ={
			'oper': 'catalogogeneral',
			'codestpro1': codestpro1,
			'codestpro2': codestpro2,
			'codestpro3': codestpro3,
			'codestpro4': codestpro4,
			'sistema': sistema,
			'vista': vista
			};
		}
		else
		{
			var objdata ={
				'oper': 'catalogo',
				'cadena': cadena,
				'criterio': criterio,
				'sistema': sistema,
				'vista': vista
			};
		}	
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
		url : rutaEstPre,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request )
		{ 
			datos = resultado.responseText;
			if (datos!='')
			{
				var DatosNuevo = eval('(' + datos + ')');
				gridEstPre.store.loadData(DatosNuevo);
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
	
	
/****************************************************************************
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
*******************************************************************************/
	function validarExistenciaEst()
	{
		EstPreCat = gridEstPre.getSelectionModel().getSelected().get('codest');
		cantPre   = gridPre.store.getCount()-1;
		arrAuxPre = gridPre.store.getRange(0,cantPre);
		longitud  = arrAuxPre.length-1;
		for (i=0; i<=longitud; i++)
		{
			if (arrAuxPre[i].get('codest')==EstPreCat)
			{
				return true;
			}
		}	
	}


/****************************************************************************
* @Función para insertar el registro seleccionado
* @de la grid del catalgo a la grid del formulario.
* @fecha de creación:
* @autor:
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*****************************************************************************/
	function pasarDatosGridEst(datos)
	{
		p = new RecordDefPre
		({			
			'codest':'',
			'codcompleto':'',
			'nombre':'',
		});
		gridPre.store.insert(0,p);
		p.set('nombre',datos.get('nombre'));
		p.set('codest',datos.get('codestpro1')+datos.get('codestpro2')+datos.get('codestpro3')+datos.get('codestpro4')+datos.get('codestpro5')+datos.get('estcla'));
		p.set('codcompleto',datos.get('codcompleto'));
	}
	

/***********************************************************************************
* @Función que carga los usuarios de la estructura
* @parámetros: 
* @retorno: 
* @fecha de creación: 29/10/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function cargarUsuariosEstPre()
	{
		codtippersss = Ext.getCmp('txtcodcompleto').getValue();
		var objdata ={
				'oper': 'catalogodetalle',
				'codtippersss': codtippersss,
				'codsis': 'SPG',
				'campo': 'codest',
				'tabla': 'spg_ep5',
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
				//alert(datos);
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
	function mostrarTituloGrid5(nombrenivel1,nombrenivel2,nombrenivel3,nombrenivel4)
	{
		var objdata ={
			'oper': 'cargarTituloGridCat',
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
		url : rutaEstPre,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request )
		{ 
			datos = resultado.responseText;
			var datajson = Ext.util.JSON.decode(datos);
			if (datajson.raiz!=null)
			{
				var datajson = eval('(' + datos + ')');
				gridEstPre.getColumnModel().setColumnHeader(1,datajson.raiz.nivel1);
				gridEstPre.getColumnModel().setColumnHeader(2,datajson.raiz.nivel2);
				gridEstPre.getColumnModel().setColumnHeader(3,datajson.raiz.nivel3);
				gridEstPre.getColumnModel().setColumnHeader(4,datajson.raiz.nivel4);
				gridEstPre.getColumnModel().setColumnHeader(5,datajson.raiz.nivel5);
				Ext.getCmp('venEstPre5').setTitle('Catálogo de Estructuras Presupuestarias  '+datajson.raiz.nivel5);
				Ext.getCmp('codestpro1').setValue(nombrenivel1);
				Ext.getCmp('codestpro2').setValue(nombrenivel2);
				Ext.getCmp('codestpro3').setValue(nombrenivel3);
				Ext.getCmp('codestpro4').setValue(nombrenivel4);
				
				var label1 = Ext.DomQuery.select('label[for="codestpro1"]');
        		Ext.DomHelper.overwrite(label1[0],datajson.raiz.nivel1+':');
        		
        		var label2 = Ext.DomQuery.select('label[for="codestpro2"]');
        		Ext.DomHelper.overwrite(label2[0],datajson.raiz.nivel2+':');
        		
        		var label3 = Ext.DomQuery.select('label[for="codestpro3"]');
        		Ext.DomHelper.overwrite(label3[0],datajson.raiz.nivel3+':');
        		
        		if (datajson.raiz.nivel4=='' || datajson.raiz.nivel4=='-')
        		{	        		
	        		var label4 = Ext.DomQuery.select('label[for="codestpro4"]');
 					Ext.DomHelper.overwrite(label4[0],'');	
                    Ext.getCmp('codestpro4').hide();     
        		}
        		else
        		{
                    var label4 = Ext.DomQuery.select('label[for="codestpro4"]');
	        		Ext.DomHelper.overwrite(label4[0],datajson.raiz.nivel4+':');
        		}
        		if (datajson.raiz.nivel5=='' || datajson.raiz.nivel5=='-')
        		{
	        		var label5 = Ext.DomQuery.select('label[for="codestpro5"]');
 					Ext.DomHelper.overwrite(label5[0],'');	
                    Ext.getCmp('codestpro5').hide();
	        	}
	        	else
	        	{	        		
                    var label5 = Ext.DomQuery.select('label[for="codestpro5"]');
	        		Ext.DomHelper.overwrite(label5[0],datajson.raiz.nivel5+':');	  
                    
	        	}	        	
			}
		}
		});
	}	
					
		
/***************************************************************************
* @Función que busca el listado de personal.
* @parámetros: form: id del formulario, 
* 			fieldset: id del fieldset,
* 			array: arreglo con los campos del formulario
* 			arrValores: arreglo con los campos de la base de datos.
* @fecha de creación: 07/10/2008
* @autor: Gusmary Balza.
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
****************************************************************************/
	function mostrarCatalogoEstPre(arrTxt, arrValores,nombrenivel1,nombrenivel2,nombrenivel3,nombrenivel4)
	{
		if (pantalla=='traspasosol')
		{
			var objdata ={
			'oper': 'catalogogeneral',
			'codestpro1': codestpro1,
			'codestpro2': codestpro2,
			'codestpro3': codestpro3,
			'codestpro4': codestpro4,
			'sistema': sistema,
			'vista': vista
			};
		}
		else
		{
			var objdata ={
				'oper': 'catalogo', 
				'sistema': sistema,
				'vista': vista
			};
		}	
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
		url : rutaEstPre,
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
					{name: 'codestpro5'},
					{name: 'estcla'},
					{name: 'codcompleto'},
					{name: 'codest'},
					{name: 'nombre'},
					{name: 'denestpro5'},
					]);
			        
			        gridEstPre = new Ext.grid.GridPanel({
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
							{header: 'Código Est. 1', id: 'cod1', width: 100, sortable: true, dataIndex: 'codestpro1'},
							{header: 'Código Est. 2', id: 'cod2', width: 100, sortable: true, dataIndex: 'codestpro2'},
							{header: 'Código Est. 3', id: 'cod3', width: 100, sortable: true, dataIndex: 'codestpro3'},
							{header: 'Código Est. 4', id: 'cod4', width: 100, sortable: true, dataIndex: 'codestpro4'},
							{header: 'Código Est. 5', id: 'cod5', width: 100, sortable: true, dataIndex: 'codestpro5'},
							{header: 'Estatus', width: 150, sortable: true, dataIndex: 'estcla',renderer:mostrarEstatus},
							{header: 'Código', width: 100, sortable: true, hidden:true, dataIndex: 'codcompleto'},
							{header: 'Código', width: 100, sortable: true, hidden:true, dataIndex: 'codest'},
							{header: 'Denominación', width: 400, sortable: true, dataIndex: 'nombre'},
						]),
						 sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
			            viewConfig: {
			                            forceFit:true
			                        },
						autoHeight:true,
						stripeRows: true
			                   });
			                   gridCreadaEstPre = true;					
					
					vacio5 = true;
					vacio4 = true;
					gridEstPre.store.each(function (obj){
										if (obj.get('codestpro5')!='')
										{
											vacio5 = false;
										}
										if (obj.get('codestpro4')!='')
										{
											vacio4 = false;
										}
					})
					if (vacio5)
					{
						gridEstPre.getColumnModel().getColumnById('cod5').hidden = true;		
					}
					if (vacio4)
					{
						gridEstPre.getColumnModel().getColumnById('cod4').hidden = true;		
					}
	
					mostrarTituloGrid5(nombrenivel1,nombrenivel2,nombrenivel3,nombrenivel4);
					
					if (pantalla=='traspasosol')
					{
						gridEstPre.getSelectionModel().singleSelect = true;	 
					}
					else 
					{
						gridEstPre.getSelectionModel().singleSelect = false;	
					}	
					
					var panelEstPre = new Ext.FormPanel({
						labelWidth: 75, 
						frame:true,
						title: 'Búsqueda',
						bodyStyle:'padding:5px 5px 0',
						width: 400,
						height:220,
						defaults: {width: 230},
						defaultType: 'textfield',
						items: [{
							//fieldLabel: 'Código Est.1',
							name: 'codestpro1',
							id:'codestpro1',
							//labelWidth:150,
							disabled:true,
							width:250,
							changeCheck: function()
							{
								  var v = this.getValue();
								  actualizarDataEstPre('spg_ep5.codestpro1',v);
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
							//fieldLabel: 'Código Est.2',
							name: 'codestpro2',
							id:'codestpro2',
							disabled:true,
							width:250,
							changeCheck: function()
							{
								var v = this.getValue();
								actualizarDataEstPre('spg_ep5.codestpro2',v);
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
							//fieldLabel: 'Código Est.3',
							name: 'codestpro3',
							id:'codestpro3',
							disabled:true,
							width:250,
							changeCheck: function()
							{
								var v = this.getValue();
								actualizarDataEstPre('spg_ep5.codestpro3',v);
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
							//fieldLabel: 'Código Est.4',
							name: 'codestpro4',
							id:'codestpro4',
							disabled:true,
							width:250,
							changeCheck: function()
							{
								var v = this.getValue();
								actualizarDataEstPre('spg_ep5.codestpro4',v);
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
							//fieldLabel: 'Código Est.5',
							name: 'codestpro5',
							id:'codestpro5',
							width:100,
							changeCheck: function()
							{
								var v = this.getValue();
								actualizarDataEstPre('spg_ep5.codestpro5',v);
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
					
					if (pantalla=='usuariospresupuesto')
					{
						gridEstPre.getSelectionModel().singleSelect = true;		
						Ext.getCmp('codestpro1').enable();	
						Ext.getCmp('codestpro2').enable();	
						Ext.getCmp('codestpro3').enable();
						Ext.getCmp('codestpro4').enable();
						Ext.getCmp('codestpro5').enable();									
					}
					else 
					{
						gridEstPre.getSelectionModel().singleSelect = false;
						Ext.getCmp('codestpro1').enable();	
						Ext.getCmp('codestpro2').enable();	
						Ext.getCmp('codestpro3').enable();
						Ext.getCmp('codestpro4').enable();
						Ext.getCmp('codestpro5').enable();								
					}
					
					
						ventanaEstPre = new Ext.Window(
						{
							title: 'Cat&aacute;logo de Estructuras Presupuestarias',
					    	autoScroll:true,
					    	id: 'venEstPre5',
			                width:900,
			                height:500,
			                modal: true,
			                closeAction:'hide',
			                plain: false,
			                items:[panelEstPre,gridEstPre],
			                buttons: [{
			                	text:'Aceptar',  
			                    handler: function()
								{ 
									if (pantalla=='usuariospresupuesto' || pantalla=='traspasosol') 
									{
										for (i=0;i<arrTxt.length;i++)
										{
											Ext.getCmp(arrTxt[i]).setValue(gridEstPre.getSelectionModel().getSelected().get(arrValores[i]));
										}
										if (pantalla=='usuariospresupuesto')
										{
											cargarUsuariosEstPre();
										}	
									}
									else
									{
										if (validarExistenciaEst()==true)
										{
											Ext.Msg.alert('Mensaje','Registro ya agregado');
										}											
										else
										{
											seleccionados = gridEstPre.getSelectionModel().getSelections();
											for (i=0; i<seleccionados.length; i++)
											{
												pasarDatosGridEst(seleccionados[i]);
											}
											
										}
									}
									panelEstPre.destroy();
			                      	ventanaEstPre.destroy();
								}
								},{
			                     text: 'Salir',
			                     handler: function()
			                     {
			                      	panelEstPre.destroy();
			                      	ventanaEstPre.destroy();
			                     }
							}]
						});
			            
					ventanaEstPre.show();
					if(!iniciargrid)
					{
						gridEstPre.render('miGrid');
			            iniciargrid=false;
			        }
			        gridEstPre.getSelectionModel().selectFirstRow();
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
