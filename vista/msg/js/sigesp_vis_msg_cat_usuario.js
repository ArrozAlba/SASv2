/***********************************************
* @archivo javascript para el catálogo de usuarios
* @version: 1.0
* @fecha de creación: 21/07/2008.
* @autor: Ing. Gusmary Balza.
***********************************************/

var gridCreada    = false;
var ventanaCreada = false;
var datos         = null;
var grid          = '';
var ventana       = null;
var unavez        = false;
var actualizar    = false;
var rutaUsuario   = '../../controlador/msg/sigesp_ctr_msg_usuario.php';

var ParamGridTarget='';
var gridUsuario = '';

/*****************************************************
*
*@Función genérica para el uso del catálogo de grupos
*
******************************************************/
function catalogoUsuario()
{
	this.mostrarCatalogo = mostrarCatalogo;
}


/*****************************************************************
* @Función que acualiza el catalgo para buscar por determinado campo
* @parametros: criterio: campo por el que se actualiza
*			   cadena: campo a actualizar
* @retorno:
* @fecha de creación:
*****************************************************************/
function actualizarDataUsuario(criterio,cadena)
{

	var objdata ={
		"oper": 'buscarcadena',
		"cadena": cadena,
		"criterio": criterio,
		'codusuario': '', 
		'cedula': '',
		'nombre': '',
		'apellido': '',
		'foto': '',
		'password': '',
		'telefono': '',
		'email': '',
		'fecultingreso': '',			
		'nota': '',
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


/*************************************************************************
*Obtener el valor de los caracteres de la caja texto
*@parámetros: obj --> caja de texto.
*@retorna: valor obtenido del objeto.
*@fecha de creación:  21/05/2008
*@Función predeterminada.
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
			Obj.on("focus", Obj.preFocus, Obj);
			if(Obj.emptyText)
			{
				Obj.on('blur', Obj.postBlur, Obj);
				Obj.applyEmptyText();
			}
		}
		if(Obj.maskRe || (Obj.vtype && Obj.disableKeyFilter !== true && (Obj.maskRe = Ext.form.VTypes[Obj.vtype+'Mask']))){
			Obj.el.on("keypress", Obj.filterKeys, Obj);
		}
		if(Obj.grow)
		{
			Obj.el.on("keyup", Obj.onKeyUp,  Obj, {buffer:50});
			Obj.el.on("click", Obj.autoSize,  Obj);
		}
			Obj.el.on("keyup", Obj.changeCheck, Obj);
}
	
	
/**************************************************
* @Función para insertar el registro seleccionado
* @de la grid del catalgo a la grid del formulario.
* @fecha de creación:
* @autor:
****************************************************/
function pasarDatosGrid()
{
	p = new RecordDefUsu
	({
	'codusuario':'',
	'nombre':'',
	'apellido':''
	});
	gridUsu.store.insert(0,p);
	p.set('apellido',gridUsuario.getSelectionModel().getSelected().get('apellido'));
	p.set('nombre',gridUsuario.getSelectionModel().getSelected().get('nombre'));
	p.set('codusuario',gridUsuario.getSelectionModel().getSelected().get('codusuario'));
}


/***********************************************************
* @Función para validar que el registro seleccionado de
* @la grid del catalogo no exista en la grid del formulario
* @parametros:
* @retorno: true si el registro ya está.
* @fecha de creación: 19/08/2008
* @autor: 
************************************************************/
function validarExistencia()
{
	codusuarioCat  = gridUsuario.getSelectionModel().getSelected().get('codusuario');
	cantUsuarios   = gridUsu.store.getCount()-1;
	arrAuxUsuarios = gridUsu.store.getRange(0,cantUsuarios);
	for (i=0; i<=arrAuxUsuarios.length-1; i++)
	{
		if (arrAuxUsuarios[i].get('codusuario')==codusuarioCat)
		{
			return true;
		}
	}
	
}


/*********************************************************
* @Función que busca el listado de usuarios.
* @parámetros: form: id del formulario, 
* fieldset: id del fieldset,
* array: arrerglo con los campos del formulario
* arrayRecord: arreglo con los campos de la base de datos.
* @fecha de creación: 21/07/2008
* @autor: Gusmary Balza.
***************************
* @fecha modificacion: 23/07/2008
* @autor: Ing. Gusmary Balza.
* @descripcion: modificación al handler del aceptar, 
* inclusión de dos parámetros de tipo arreglo.
*********************************************************/
function mostrarCatalogo(form,fieldset, array, arrayRecord) 
{
	if (pantalla!='usuario')
	{	
		var objdata ={
			"oper": 'catalogoActivos', 
			'codusuario': '', 
			'cedula': '',
			'nombre': '',
			'apellido': '',
			'foto': '',
			'password': '',
			'telefono': '',
			'email': '',
			'feultingreso': '',			
			'nota': '',
			'sistema': sistema,
			'vista': vista
		};
	}
	else
	{	
		var objdata ={
			"oper": 'catalogo', 
			'codusuario': '', 
			'cedula': '',
			'nombre': '',
			'apellido': '',
			'foto': '',
			'password': '',
			'telefono': '',
			'email': '',
			'feultingreso': '',			
			'nota': '',
			'sistema': sistema,
			'vista': vista
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
		var RecordDef = Ext.data.Record.create([
		{name: 'codusuario'},
		{name: 'cedula'},
		{name: 'nombre'},
		{name: 'apellido'},
		{name: 'telefono'},
		{name: 'email'},
		{name: 'fecultingreso'},
		{name: 'estatus'},
		{name: 'administrador'},
		{name: 'nota'},
		]);	
	
	    if (!gridCreada)
		{
			gridUsuario = new Ext.grid.GridPanel({
			width:500,
			autoScroll:true,
            border:true,
            ds: new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(myObject),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
                },
			RecordDef
			),
			data: myObject
            }),
            cm: new Ext.grid.ColumnModel([
				{header: "Código", width: 50, sortable: true,   dataIndex: 'codusuario'},
                {header: "Nombre", width: 70, sortable: true, dataIndex: 'nombre'},
			    {header: "Apellido", width: 70, sortable: true, dataIndex: 'apellido'}
			]),
            viewConfig: {
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
                   gridCreada = true;
		}
		else
		{
			gridUsuario.store.loadData(myObject);
		} 
			  		  
		var panelBusqueda = new Ext.FormPanel({
			labelWidth: 75, // label settings here cascade unless overridden
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
					  actualizarDataUsuario('codusuario',v);
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
					actualizarDataUsuario('nombre',v);
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
		if (!ventanaCreada)
        {
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
								ventana.hide();
							}
						}
						else
						{
							for (i=0;i<array.length;i++)
							{
								form.getComponent(fieldset).getComponent(array[i]).setValue(gridUsuario.getSelectionModel().getSelected().get(arrayRecord[i]));
							}
							ventana.hide();
						}
					}
					},{
                     text: 'Salir',
                     handler: function()
                     {
                      ventana.hide();
                     }
				}]
			});
            ventanaCreada = true;
		}
		ventana.show();
		if(!unavez)
		{
			gridUsuario.render('miGrid');
            unavez=false;
        }
        gridUsuario.getSelectionModel().selectFirstRow();
        },
        failure: function ( resultado, request)
		{ 
			Ext.MessageBox.alert('Error', resultado.responseText); 
        }
   });
}

