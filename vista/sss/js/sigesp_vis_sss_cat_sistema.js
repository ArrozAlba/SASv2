/***********************************************************************************
* @Archivo javascript para el catálogo de sistemas.
* @fecha de creación: 09/07/2008.
* @autor: Ing. Gusmary Balza
*************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
var datos                = null;
var gridSistema          = null;
var ventanaSistema       = null;
var iniciargrid          = false;
var parametros           = '';
var rutaSistema          = '../../controlador/sss/sigesp_ctr_sss_sistema.php';

/***********************************************************************************
* @Función genérica para el uso del catálogo de sistemas
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function catalogoSistema()
	{	
		this.mostrarCatalogoSistema = mostrarCatalogoSistema;
	}


/***********************************************************************************
* @Función que acualiza el catalogo para buscar por determinado campo
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
	function actualizarDataSistema(criterio,cadena)
	{
		var myJSONObject ={
			'oper': 'catalogo',
			'cadena': cadena,
			'criterio': criterio,
			'codsis':'',
			'nomsis':'',
			'sistema': siscatSistema,
			'vista': viscatSistema
		};
		objdata=JSON.stringify(myJSONObject);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
			url : rutaSistema,
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
						gridSistema.store.loadData(myObject);
					}
					else
					{
						Ext.MessageBox.alert('Error', myObject.raiz[0].mensaje+' Al cargar los sistemas.');
					}
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
* @fecha de creación: 21/05/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function cargarUsuarios()
	{
		codsis = Ext.getCmp('txtcodsistema').getValue();
		var objdata ={
				'oper': 'catalogodetalle',
				'codsis': codsis,	
				'sistema': siscatSistema,
				'vista': viscatSistema
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
			url : rutaSistema,
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


/***********************************************************************************
* @Función para validar que el registro seleccionado dela grid del catalogo 
* no exista en la grid del formulario
* @parámetros: 
* @retorno: true si el registro ya está.
* @fecha de creación: 19/08/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function validarExistencia()
	{
		codusuarioCat  = gridUsuario.getSelectionModel().getSelected().get('codusu');
		cantUsuarios   = gridUsu.store.getCount()-1;
		arrAuxUsuarios = gridUsu.store.getRange(0,cantUsuarios);
		for (i=0; i<=arrAuxUsuarios.length-1; i++)
		{
			if (arrAuxUsuarios[i].get('codusu')==codusuarioCat)
			{
				return true;
			}
		}
	}


/***********************************************************************************
* @Función que busca el listado de sistemas.
* @parámetros: form: id del formulario, 
* fieldset: id del fieldset,
* array: arreglo con los campos del formulario
* arrayRecord: arreglo con los campos de la base de datos.
* @fecha de creación: 08/08/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificacion: 
* @autor: 
* @descripcion: 
***********************************************************************************/
	function mostrarCatalogoSistema(arrTxt, arrValores)
	{
		var objdata ={
			'oper': 'catalogo', 
			'codsis': '', 
			'nomsis': '',
			'sistema': siscatSistema,
			'vista': viscatSistema
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
		url : rutaSistema,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request ) 
		{ 
			datos = resultado.responseText;
			var myObject = eval('(' + datos + ')');
			if(myObject.raiz[0].valido==true)
			{
				var RecordDef = Ext.data.Record.create([
				{name: 'codsis'},    
				{name: 'nomsis'}
				]);
		    
				gridSistema = new Ext.grid.GridPanel({
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
						{header: 'Código', width: 30, sortable: true,   dataIndex: 'codsis'},
		                {header: 'Nombre', width: 50, sortable: true, dataIndex: 'nomsis'},
					]),
		            viewConfig: {
		                            forceFit:true
		                        },
					autoHeight:true,
					stripeRows: true
				});
				
				var panelSistema = new Ext.FormPanel({
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
						name: 'codigo',
						id:'codigo',
						width:50,
						changeCheck: function()
						{
							  var v = this.getValue();
							  actualizarDataSistema('codsis',v);
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
						name: 'nomb',
						id:'nomb',
						changeCheck: function()
						{
							var v = this.getValue();
							actualizarDataSistema('nomsis',v);
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
				ventanaSistema = new Ext.Window(
				{
					title: 'Cat&aacute;logo de Sistemas',
			    	autoScroll:true,
	                width:500,
	                height:400,
	                modal: true,
	                closeAction:'hide',
	                plain: false,
	                items:[panelSistema,gridSistema],
	                buttons: [{
	                	text:'Aceptar',  
	                    handler: function()
						{                     	
							if ((pantalla=='sistema') || (pantalla=='permisos') || (pantalla=='enviocorreo'))
							{
								for (i=0;i<arrTxt.length;i++)
								{
									Ext.getCmp(arrTxt[i]).setValue(gridSistema.getSelectionModel().getSelected().get(arrValores[i]));
								}
								if (pantalla=='sistema')
								{
									cargarUsuarios();
								}	 
							}
							else
							{
								if (validarExistencia()==true)
								{
									Ext.Msg.alert('Mensaje','Registro ya agregado');	
								}
								else
								{
									pasarDatosSis();										
								}
							}
							panelSistema.destroy();
							ventanaSistema.destroy();
						}
						},{
	                     text: 'Salir',
	                     handler: function()
	                     {
	                      	panelSistema.destroy();
							ventanaSistema.destroy();
	                     }
					}]
				});
		        ventanaSistema.show();
				if(!iniciargrid)
				{
					gridSistema.render('miGrid');
		            iniciargrid=false;
		        }
		        gridSistema.getSelectionModel().selectFirstRow();
		    }
		    else
		    {
				Ext.MessageBox.alert('Error', myObject.raiz[0].mensaje);
				close();
		    }
	        },
	        failure: function ( resultado, request)
			{ 
				Ext.MessageBox.alert('Error', resultado.responseText); 
	        }
	   });
	}
