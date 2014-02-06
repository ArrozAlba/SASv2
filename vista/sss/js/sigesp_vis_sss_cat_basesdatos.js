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
var datos     = null;
var gridBd    = null;
var ventanaBd = null;
var iniciargrid  = false;
var parametros   = '';
var rutaBd         = '../../controlador/sss/sigesp_ctr_sss_transferirusuario.php';
//var ruta = '../../controlador/sss/sigesp_ctr_sss_usuariospermisos.php';

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
	function catalogoBd()
	{	
		this.mostrarCatalogoBd = mostrarCatalogoBd;
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
	function actualizarDataBd(criterio,cadena)
	{
		var myJSONObject ={
			'operacion': 'catalogo',
			'cadena': cadena,
			'criterio': criterio,
			'codbasedatos':'',
			'basedatos':'',
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(myJSONObject);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
		url : rutaBd,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request )
		{ 
			datos = resultado.responseText;
			if (datos!='')
			{
				var datosNuevo = eval('(' + datos + ')');
				gridBd.store.loadData(datosNuevo);
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
		
	
/**************************************************************************
* @Función que busca el listado de base de datos.
* @parámetros: form: id del formulario, 
* 			fieldset: id del fieldset,
* 			array: arreglo con los campos del formulario
* 			arrValores: arreglo con los campos de la base de datos.
* 			@fecha de creación: 17/11/2008
* 		@autor: Gusmary Balza.
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
****************************************************************************/
	function mostrarCatalogoBd(arrTxt, arrValores)
	{
		var objdata ={
			'operacion': 'obtenerBdDestino', 
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
		url : rutaBd,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request ) 
		{ 			
			datos = resultado.responseText;
			if (datos!='')
			{
				var myObject = eval('(' + datos + ')');
				//if (myObject.raiz[0].valido==true)
			//	{
					var RecordDef = Ext.data.Record.create([
					{name: 'codbasedatos'},     
					{name: 'basedatos'}
					]);
			       gridBd = new Ext.grid.GridPanel({
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
			            // new Ext.grid.CheckboxSelectionModel(),
							{header: 'Código', width: 30, sortable: true,   dataIndex: 'codbasedatos'},
			                {header: 'Nombre', width: 50, sortable: true, dataIndex: 'basedatos'}
						]),
					
						//sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
				
			            viewConfig: {
			                            forceFit:true
			                        },
						autoHeight:true,
						stripeRows: true
			         });			         
					
					 
					/*var panelBd = new Ext.FormPanel({
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
								  actualizarDataBd('codbasedatos',v);
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
								actualizarDataBd('denuniadm',v);
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
					});*/
				
						ventanaBd = new Ext.Window(
						{
							title: 'Cat&aacute;logo de Bases de Datos',
					    	autoScroll:true,
			                width:500,
			                height:400,
			                modal: true,
			                closeAction:'hide',
			                plain: false,
			                items:[gridBd],
			                buttons: [{
			                	text:'Aceptar',  
			                    handler: function()
								{                     	
									if (pantalla=='traspaso')
									{
										for (i=0;i<arrTxt.length;i++)
										{
											Ext.getCmp(arrTxt[i]).setValue(gridBd.getSelectionModel().getSelected().get(arrValores[i]));
										}
										//cargarUsuariosUnidad();
									}
								/*	else
									{
										if (validarExistenciaUni()==true)
										{
											Ext.Msg.alert('Mensaje','Registro ya agregado');
										}											
										else
										{
											seleccionados = gridBd.getSelectionModel().getSelections();
											for (i=0; i<seleccionados.length; i++)
											{
												pasarDatosGridUni(seleccionados[i]);
											}
										}	
									}*/									
									//panelBd.destroy();
			                      	ventanaBd.destroy();
								}
								},{
			                     text: 'Salir',
			                     handler: function()
			                     {
			                      	//panelBd.destroy();
			                      	ventanaBd.destroy();
			                     }
							}]
						});
			           
					ventanaBd.show();
					if(!iniciargrid)
					{
						gridBd.render('miGrid');
			            iniciargrid=false;
			        }
			        gridBd.getSelectionModel().selectFirstRow();
		    /*    }
		        else
		        {
		        	Ext.MessageBox.alert('Error', myObject.raiz[0].mensaje);
					close();
				}*/
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
