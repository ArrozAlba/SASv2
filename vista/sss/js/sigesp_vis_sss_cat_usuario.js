/*******************************************************************************
* @archivo javascript para el catálogo de usuarios
* @version: 1.0
* @fecha de creación: 21/07/2008.
* @autor: Ing. Gusmary Balza.
*************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*******************************************************************************/
var datos         = null;
var grid          = '';
var ventana       = null;
var iniciargrid        = false;
var actualizar    = false;
var rutaUsuario   = '../../controlador/sss/sigesp_ctr_sss_usuario.php';
var ParamGridTarget='';
var gridUsuario = '';

/******************************************************************************
* @Función genérica para el uso del catálogo de usuarios
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*******************************************************************************/
	function catalogoUsuario()
	{
		this.mostrarCatalogo = mostrarCatalogo;
	}


/*****************************************************************************
* @Función que acualiza el catalgo para buscar por determinado campo
* @parametros: criterio: campo por el que se actualiza
*			   cadena: campo a actualizar
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
******************************************************************************/
	function actualizarDataUsuario(campo,cadena)
	{
		var objdata ={
			'oper': 'catalogo',
			'cadena': cadena,
			'campo': campo,
			'sistema': siscatUsuario,
			'vista': viscatUsuario
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
		url : rutaUsuario,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request )
		{ 
			datos = resultado.responseText;
			if(datos!='')
			{
				var datosNuevo = eval('(' + datos + ')');
				gridUsuario.store.loadData(datosNuevo);
			}
		}
		});
	}


/******************************************************************************
* Función para obtener el valor de los caracteres de la caja texto
*@parámetros: obj --> caja de texto.
*@retorna: valor obtenido del objeto.
*@fecha de creación:  21/05/2008
*@Función predeterminada.
******************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*******************************************************************************/		
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
		
	
/******************************************************************************
* @Función para insertar el registro seleccionado de la grid del catalgo 
* a la grid del formulario.
* @fecha de creación: 19/08/2008
* @autor: Gusmary Balza
******************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
********************************************************************************/
	function pasarDatosGrid()
	{
		p = new RecordDefUsu
		({
		'codusu':'',
		'nomusu':'',
		'apeusu':''
		});
		gridUsu.store.insert(0,p);
		p.set('apeusu',gridUsuario.getSelectionModel().getSelected().get('apeusu'));
		p.set('nomusu',gridUsuario.getSelectionModel().getSelected().get('nomusu'));
		p.set('codusu',gridUsuario.getSelectionModel().getSelected().get('codusu'));
	}


/************************************************************************************
* @Función para validar que el registro seleccionado de
* @la grid del catalogo no exista en la grid del formulario
* @parametros:
* @retorno: true si el registro ya está.
* @fecha de creación: 19/08/2008
* @autor: 
**********************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
**********************************************************************************/
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


/*******************************************************************************
* @Función para buscar los detalles de un usuario (permisos para constantes,
* nóminas, unidades ejecutoras,tipos de personal  y presupuestos)
* @parametros:
* @retorno: 
* @fecha de creación: 10/10/2008
* @autor: 
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*********************************************************************************/
	function cargarDetalle() 
	{
		codusu = Ext.getCmp('txtcodusuario').getValue();
		var objdata ={
				'oper': 'catalogodetalle',
				'codusu': codusu
					
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
			url : rutaUsuario,
			params : parametros,
			method: 'POST',
			success: function (resultado,request)
			{	
				datos = resultado.responseText;	
				if (datos!='')
				{
					arregloObjs = datos.split('|');
					datajsonPer = eval('(' + arregloObjs[0] + ')');
					datajsonCons = eval('(' + arregloObjs[1] + ')');
					datajsonNom = eval('(' + arregloObjs[2] + ')');
					datajsonUni = eval('(' + arregloObjs[3] + ')');
					datajsonEst = eval('(' + arregloObjs[4] + ')');
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
						datajsonEst = {'raiz':[{'codest':'','codcompleto':'','nombre':''}]};	
					}
					gridPre.store.loadData(datajsonEst);
				}
			}
		});
	}


/********************************************************************************
* @Función que busca el listado de usuarios.
* @parámetros: form: id del formulario, 
* 				fieldset: id del fieldset,
* 				array: arrerglo con los campos del formulario
* 				arrayRecord: arreglo con los campos de la base de datos.
* @fecha de creación: 21/07/2008
* @autor: Gusmary Balza.
*********************************************************************************
* @fecha modificacion: 23/07/2008
* @autor: Ing. Gusmary Balza.
* @descripcion: modificación al handler del aceptar, 
* inclusión de dos parámetros de tipo arreglo.
********************************************************************************/
	function mostrarCatalogo(form,fieldset, array, arrayRecord) 
	{
		if (pantalla!='usuario')
		{	
			var objdata ={
				'oper': 'catalogoActivos', 
				'sistema': siscatUsuario,
				'vista': viscatUsuario
			};
		}
		else
		{	
			var objdata ={
				'oper': 'catalogo', 
				'sistema': siscatUsuario,
				'vista': viscatUsuario
			};
		}
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
		url : rutaUsuario,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request ) 
		{		
			datos = resultado.responseText;
			var myObject = eval('(' + datos + ')');
			if (myObject.raiz[0].valido==true)
			{
				var RecordDef = Ext.data.Record.create([
				{name: 'codusu'},
				{name: 'cedusu'},
				{name: 'nomusu'},
				{name: 'apeusu'},
				{name: 'telusu'},
				{name: 'email'},
				{name: 'ultingusu'},
				{name: 'estatus'},
				{name: 'admusu'},
				{name: 'nota'},
				{name: 'fecnacusu'}
				]);	
			
		  		gridUsuario = new Ext.grid.GridPanel({
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
						{header: 'Código', width: 50, sortable: true,   dataIndex: 'codusu'},
		                {header: 'Nombre', width: 70, sortable: true, dataIndex: 'nomusu'},
					    {header: 'Apellido', width: 70, sortable: true, dataIndex: 'apeusu'}
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
						fieldLabel: 'Código',
						name: 'cod',
						id:'cod',
						width:100,
						changeCheck: function()
						{
							  var v = this.getValue();
							  actualizarDataUsuario('codusu',v);
							  if(String(v) !== String(this.startValue))
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
						name: 'nom',
						id:'nom',
						changeCheck: function()
						{
							var v = this.getValue();
							actualizarDataUsuario('nomusu',v);
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
				
				ventana = new Ext.Window(
				{
					title: 'Cat&aacute;logo de Usuarios',
			    	autoScroll:true,
	                width:500,
	                height:400,
	                modal: true,
	                closeAction:'hide',
	                plain: false,
	                items:[panelBusqueda,gridUsuario],
	                buttons: [{
	                	text:'Aceptar',  
	                    handler: function()
						{                     	
							if (fieldset=='')
							{						
								if (validarExistencia()==true)
								{
									Ext.Msg.alert('Mensaje','Registro ya agregado');	
								}
								else
								{
									pasarDatosGrid();										
								}
							}
							else
							{							
								for (i=0;i<array.length;i++)
								{								
									Ext.getCmp(array[i]).setValue(gridUsuario.getSelectionModel().getSelected().get(arrayRecord[i]));
								}
								if (pantalla=='usuario')
								{
									cargarDetalle(); 
								}									
							}								
							panelBusqueda.destroy();
							ventana.destroy();
						}
						},{
	                     text: 'Salir',
	                     handler: function()
	                     {
	                     	panelBusqueda.destroy();
	                      	ventana.destroy();
	                     }
					}]
				});
						           
				ventana.show();
				if(!iniciargrid)
				{
					gridUsuario.render('miGrid');
		            iniciargrid=false;
		        }
		        gridUsuario.getSelectionModel().selectFirstRow();
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

