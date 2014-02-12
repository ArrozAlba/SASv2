/********************************************************************************
*@archivo javascript para el catálogo de cuentas contables
*@version: 1.0
*@fecha de creación: 03/12/2008.
*@autor: Ing. Gusmary Balza.
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
**********************************************************************************/
var datos          = null;
var gridCuentaScg  = null;
var ventanaCuenta  = null;
var iniciargrid    = false;
var parametros     = '';
var rutaCuenta    = '../../controlador/scg/sigesp_ctr_scg_cuenta.php';


/***************************************************************************************
* @Función genérica para el uso del catálogo de cuentas
* @parametros: 
* @retorno: 
* @fecha de creación: 26/11/2008. 
* @autor: Ing. Gusmary Balza. 
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
****************************************************************************************/
	function catalogoCuentaScg()
	{	
		this.mostrarCatalogo = mostrarCatalogoCuentaScg;
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
	function actualizarDataCuenta(criterio,cadena)
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
		url : rutaCuenta,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request )
		{ 
			datos = resultado.responseText;
			if (datos!='')
			{
				var DatosNuevo = eval('(' + datos + ')');
				gridCuentaScg.store.loadData(DatosNuevo);
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

		
/*************************************************************************
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
**************************************************************************/
	function validarExistenciaCuenta()
	{
		cuentaCat    = gridCuentaScg.getSelectionModel().getSelected().get('sc_cuenta');
		cantCuenta   = gridContable.store.getCount()-1; //grid del formulario
		arrAuxCuenta = gridContable.store.getRange(0,cantCuenta);
		for (i=0; i<=arrAuxCuenta.length-1; i++)
		{
			if (arrAuxCuenta[i].get('sc_cuenta')==cuentaCat)
			{
				return true;
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
	function pasarDatosGridCuenta(datos)
	{
		/*p = new RecordDef
		({
		'sc_cuenta':''
		
		});
		
		gridContable.store.insert(0,p);
		p.set('sc_cuenta',datos.get('sc_cuenta'));
		//p.set('codcons',datos.get('codcons'));*/
		cuentaSele = gridContable.getSelectionModel().getSelected();
		cuentaSele.set('destino',datos.get('sc_cuenta'));
	}
	
			
/***************************************************************************
* @Función que busca el listado de cuentas.
* @parámetros: array: arreglo con los campos del formulario
* 			arrValores: arreglo con los campos de la base de datos.
* @fecha de creación: 07/10/2008
* @autor: Gusmary Balza.
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
****************************************************************************/
	function mostrarCatalogoCuentaScg(arrTxt, arrValores)
	{
		var objdata ={
			'oper': 'catalogo', 
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
		url : rutaCuenta,
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
					{name: 'sc_cuenta'},
					{name: 'denominacion'},
					{name: 'estatus'},
					{name: 'asignado'},
					{name: 'distribuir'},				
					]);
			        
			        gridCuentaScg = new Ext.grid.GridPanel({
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
			            new Ext.grid.CheckboxSelectionModel(),
			               	{header: 'Cuenta', width: 100, sortable: true, dataIndex: 'sc_cuenta'},
							{header: 'Denominación', width: 300, sortable: true, dataIndex: 'denominacion'},
						]),
						 sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
			            viewConfig: {
			                            forceFit:true
			                        },
						autoHeight:true,
						stripeRows: true
			                   });			                  				
					if (pantalla=='asociarcontables')
					{
						gridCuentaScg.getSelectionModel().singleSelect = true;	 
					}
					else 
					{
						gridCuentaScg.getSelectionModel().singleSelect = false;	
					}
										
					var panelCuenta = new Ext.FormPanel({
						labelWidth: 90, 
						frame:true,
						title: 'Búsqueda',
						bodyStyle:'padding:5px 5px 0',
						width: 350,
						height:100,
						defaults: {width: 230},
						defaultType: 'textfield',
						items: [{
							fieldLabel: 'Cuenta',
							name: 'sc_cuenta',
							id:'sc_cuenta',
							width:200,
							changeCheck: function()
							{
								  var v = this.getValue();
								  actualizarDataCuenta('sc_cuenta',v);
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
							name: 'denominacion',
							id:'denominacion',
							width:200,
							changeCheck: function()
							{
								  var v = this.getValue();
								  actualizarDataCuenta('denominacion',v);
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
					
						ventanaCuenta = new Ext.Window(
						{
							title: 'Cat&aacute;logo de Cuentas Contables',
					    	autoScroll:true,
			                width:600,
			                height:500,
			                modal: true,
			                closeAction:'hide',
			                plain: false,
			                items:[panelCuenta,gridCuentaScg],
			                buttons: [{
			                	text:'Aceptar',  
			                    handler: function()
								{ 
									if (pantalla=='traspasosol') //no es esta pantalla
									{
										for (i=0;i<arrTxt.length;i++)
										{
											Ext.getCmp(arrTxt[i]).setValue(gridCuentaScg.getSelectionModel().getSelected().get(arrValores[i]));
										}
									}
									else
									{
										if (validarExistenciaCuenta()==true)
										{
											Ext.Msg.alert('Mensaje','Registro ya agregado');
										}											
										else
										{
											seleccionados = gridCuentaScg.getSelectionModel().getSelections();
											for (i=0; i<seleccionados.length; i++)
											{
												pasarDatosGridCuenta(seleccionados[i]);
											}
											
										}
									}
									panelCuenta.destroy();
			                      	ventanaCuenta.destroy();
								}
								},{
			                     text: 'Salir',
			                     handler: function()
			                     {
			                      	panelCuenta.destroy();
			                      	ventanaCuenta.destroy();
			                     }
							}]
						});
			            
					ventanaCuenta.show();
					if(!iniciargrid)
					{
						gridCuentaScg.render('miGrid');
			            iniciargrid=false;
			        }
			        gridCuentaScg.getSelectionModel().selectFirstRow();
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
