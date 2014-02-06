/*****************************************************************************
*@archivo javascript para el catálogo de constantes de nomina
*@version: 1.0
*@fecha de creación: 09/08/2008.
*@autor: Ing. Gusmary Balza.
*************************************************************
* @fecha modificación:
* @descripción:
* @autor:
****************************************************************************/
var datos            = null;
var gridConstante    = null;
var ventanaConstante = null;
var iniciargrid      = false;
var parametros       = '';
var rutaConstantes   = '../../controlador/sno/sigesp_ctr_sno_constantes.php';
var ruta = '../../controlador/sss/sigesp_ctr_sss_usuariospermisos.php';

/*************************************************************************
*@Función genérica para el uso del catálogo de personal
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*******************************************************************************/
	function catalogoConstante()
	{	
		this.mostrarCatalogoConstante = mostrarCatalogoConstante;
	}	


/***********************************************************************
* @Función que acualiza el catalgo para buscar por determinado campo
* @parametros: criterio: campo por el que se actualiza
* 			   cadena: campo a actualizar
* @retorno:
* @fecha de creación:
************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
**************************************************************************/
	function actualizarDataConstante(criterio,cadena)
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
		url : rutaConstantes,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request )
		{ 
			datos = resultado.responseText;
			if (datos!='')
			{
				var DatosNuevo = eval('(' + datos + ')');
				gridConstante.store.loadData(DatosNuevo);
			}
		}
		});
	}


/***********************************************************************
*Obtener el valor de los caracteres de la caja texto
*@parámetros: obj --> caja de texto.
*@retorna: valor obtenido del objeto.
*@fecha de creación:  21/05/2008
*@Función predeterminada.
**************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
************************************************************************/		
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
	
		
/*******************************************************************
* @Función que valida la existencia 
* @Valor de Retorno: 0 o 1 si fue correcto o no.
* @Fecha de Creación: 19/12/2008
* @autor: Johny Porras. 
****************************************************************/
	function validarExistenciaCons(gridCat,gridPrin,codigo,codigo2,codigoprin,codigoprin2)
	{
		registroSel  = gridCat.getSelectionModel().getSelections();
	 	cantActual   = gridPrin.store.getCount()-1;
	 	registrosAct = gridPrin.store.getRange(0,cantActual);
	 	for (i=0; i<=registroSel.length-1; i++)
		{ 
	  		auxReg1 = registroSel[i].get(codigo);
	  		auxReg2 = registroSel[i].get(codigo2);
	  		for (j=0; j<=registrosAct.length-1; j++)
	  		{
	   			if (registrosAct[j].get(codigoprin)==auxReg1 && registrosAct[j].get(codigoprin2)==auxReg2)
	   			{
	    			Ext.MessageBox.alert('Mensaje','El registro con código '+ auxReg1 +' para la nómina ' +auxReg2+'ya está seleccionado');
	    			return true;
	   			} 
	  		}	  
	 	}
	}


/*************************************************************************
* @Función para insertar el registro seleccionado
* @de la grid del catalgo a la grid del formulario.
* @fecha de creación: 19/08/2008
* @autor: Gusmary Balza
**************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
**************************************************************************/
	function pasarDatosGridCons(datos)
	{
		p = new RecordDefCons
		({
		'codcons':'',
		'nomcon':'',
		'codnom':''
		});
		gridCons.store.insert(0,p);
		p.set('nomcon',datos.get('nomcon'));
		p.set('codcons',datos.get('codcons'));
		p.set('codnom',datos.get('codnom'));
	}

	
/***********************************************************************************
* @Función que carga los usuarios de la constante
* @parámetros: 
* @retorno: 
* @fecha de creación: 29/10/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function cargarUsuariosConstante()
	{
		codtippersss = Ext.getCmp('txtcodcons').getValue();
		var objdata ={
				'oper': 'catalogodetalle',
				'codtippersss': codtippersss,
				'campo': 'codcons',
				'codsis': 'SNO',
				'tabla': 'sno_constante',
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
* @Función que busca el listado de personal.
* @parámetros: 	form: id del formulario, 
* 				fieldset: id del fieldset,
* 				array: arreglo con los campos del formulario
* 				arrValores: arreglo con los campos de la base de datos.
* @fecha de creación: 07/10/2008
* @autor: Gusmary Balza.
**************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***************************************************************************/
	function mostrarCatalogoConstante(arrTxt, arrValores)
	{
		if (pantalla==='usuario' || pantalla=='usuariosconstante')
		{
			var objdata ={
				'oper': 'catalogoSeguridad', 
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
		url : rutaConstantes,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request ) 
		{ 
			datos = resultado.responseText;
			if (datos!='')
			{			
				var myObject = eval('(' + datos + ')');
				if(myObject.raiz[0].valido==true)
				{
					var RecordDef = Ext.data.Record.create([
					{name: 'codnom'}, 
					{name: 'codcons'},     
					{name: 'nomcon'}
					]);
			        
			        gridConstante = new Ext.grid.GridPanel({
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
			             	{header: 'Código Nómina', width: 30, sortable: true,   dataIndex: 'codnom'},
							{header: 'Código', width: 30, sortable: true,   dataIndex: 'codcons'},
			                {header: 'Nombre', width: 50, sortable: true, dataIndex: 'nomcon'},
						]),
						sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
			            viewConfig: {
			                            forceFit:true
			                        },
						autoHeight:true,
						stripeRows: true
			        });			                 
					
					if (pantalla=='usuariosconstante')
					{
						gridConstante.getSelectionModel().singleSelect = true;	 
					}
					else 
					{
						gridConstante.getSelectionModel().singleSelect = false;	
					}
					
					var panelConstante = new Ext.FormPanel({
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
							name: 'codcons',
							id:'codcons',
							width:100,
							changeCheck: function()
							{
								  var v = this.getValue();
								  actualizarDataConstante('codcons',v);
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
							name: 'nomcons',
							id:'nomcons',
							changeCheck: function()
							{
								var v = this.getValue();
								actualizarDataConstante('nomcon',v);
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
					
						ventanaConstante = new Ext.Window(
						{
							title: 'Cat&aacute;logo de Constantes',
					    	autoScroll:true,
			                width:500,
			                height:400,
			                modal: true,
			                closeAction:'hide',
			                plain: false,
			                items:[panelConstante,gridConstante],
			                buttons: [{
			                	text:'Aceptar',  
			                    handler: function()
								{                     	
									if (pantalla=='usuariosconstante')
									{
										for (i=0;i<arrTxt.length;i++)
										{											
											if (arrTxt[i]=='txtcodcons')
											{
												cod1 = gridConstante.getSelectionModel().getSelected().get('codnom');
												cod2 = gridConstante.getSelectionModel().getSelected().get(arrValores[i]);
												codigo = cod1+'-'+cod2;
												//Ext.getCmp(arrTxt[i]).setValue(gridConstante.getSelectionModel().getSelected().get(codigo));
												Ext.getCmp(arrTxt[i]).setValue(codigo);												
											}
											else
											{
												Ext.getCmp(arrTxt[i]).setValue(gridConstante.getSelectionModel().getSelected().get(arrValores[i]));
											}	
										}
										cargarUsuariosConstante();
									}
									else
									{
										if (validarExistenciaCons(gridConstante,gridCons,'codcons','codnom','codcons','codnom')==true)
										{
											//Ext.Msg.alert('Mensaje','Registro ya agregado');
											return false;
										}											
										else
										{
											seleccionados = gridConstante.getSelectionModel().getSelections();
											for (i=0; i<seleccionados.length; i++)
											{
												pasarDatosGridCons(seleccionados[i]);
											}
										}	
									}
									panelConstante.destroy();
			                      	ventanaConstante.destroy();
								}
								},{
			                     text: 'Salir',
			                     handler: function()
			                     {
			                      	panelConstante.destroy();
			                      	ventanaConstante.destroy();
			                     }
							}]
						});
			        ventanaConstante.show();
					if(!iniciargrid)
					{
						gridConstante.render('miGrid');
			            iniciargrid=false;
			        }
			        gridConstante.getSelectionModel().selectFirstRow();
		        }
			    else
			    {
					Ext.MessageBox.alert('Error', myObject.raiz[0].mensaje);
					close();
			    }
			}
			else
			{
				Ext.MessageBox.alert('Mensaje', 'No hay datos para mostrar');
			}			    
        },
        failure: function ( resultado, request)
		{ 
			Ext.MessageBox.alert('Error', resultado.responseText); 
        }
	   });
	};
