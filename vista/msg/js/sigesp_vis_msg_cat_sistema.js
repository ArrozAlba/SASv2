/***********************************************
*@archivo javascript para el catálogo de sistemas
*@version: 1.0
*@fecha de creación: 08/08/2008.
*@autor: Ing. Gusmary Balza.
***********************************************/

var gridCreadaSistema    = false;
var ventanaCreadaSistema = false;
var datos                = null;
var gridSistema          = null;
var ventanaSistema       = null;
var unavez               = false;
var parametros           = '';
var rutaSistema          = '../../controlador/msg/sigesp_ctr_msg_sistema.php';
var panelSistema = false;
/*****************************************************
*
*@Función genérica para el uso del catálogo de sistemas
*
******************************************************/
function catalogoSistema()
{	
	this.mostrarCatalogoSistema = mostrarCatalogoSistema;
}


/*******************************************************
* @Función que acualiza el catalgo 
* para buscar por determinado campo
* @parametros: criterio: campo por el que se actualiza
* cadena: campo a actualizar
* @retorno:
* @fecha de creación:
********************************************************/
function actualizarDataSistema(criterio,cadena)
{
	var myJSONObject ={
		"oper": 'buscarcadena',
		"cadena": cadena,
		"criterio": criterio,
		"codsistema":'',
		"nombre":'',
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
			var DatosNuevo = eval('(' + datos + ')');
			gridSistema.store.loadData(DatosNuevo);
		}
	}
});
}


/****************************************************
*Obtener el valor de los caracteres de la caja texto
*@parámetros: obj --> caja de texto.
*@retorna: valor obtenido del objeto.
*@fecha de creación:  21/05/2008
*@Función predeterminada.
*************************************************/		
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
	
	
function cargarUsuarios()
{
	codsistema = Ext.getCmp('txtcodsistema').getValue();
	var objdata ={
			'oper': 'catalogodetalle',
			'codsistema': codsistema
				
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
				gridUsu.store.loadData(myObject);
			}
		}
	});
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

	
/********************************************************
* @Función que busca el listado de sistemas.
* @parámetros: form: id del formulario, 
* fieldset: id del fieldset,
* array: arreglo con los campos del formulario
* arrValores: arreglo con los campos de la base de datos.
* @fecha de creación: 08/08/2008
* @autor: Gusmary Balza.
*********************************************************/
function mostrarCatalogoSistema(form,fieldset,arrTxt, arrValores)
{
	var objdata ={
		"oper": 'catalogo', 
		"codsistema": '', 
		"nombre": '',
		'sistema': 'MSG',
		'vista': 'sigesp_vis_msg_sistema.html'
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
		var RecordDef = Ext.data.Record.create([
		{name: 'codsistema'},     // "mapping" property not needed if it's the same as "name"
		{name: 'nombre'}
		]);
        if (!gridCreadaSistema)
		{
			gridSistema = new Ext.grid.GridPanel({
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
				{header: "Código", width: 30, sortable: true,   dataIndex: 'codsistema'},
                {header: "Nombre", width: 50, sortable: true, dataIndex: 'nombre'},
			]),
            viewConfig: {
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
                   gridCreadaSistema = true;
		}
		else
		{
			gridSistema.store.loadData(myObject);
		} 
		if (!panelSistema)
		{
		    panelSistema = new Ext.FormPanel({
			labelWidth: 75, // label settings here cascade unless overridden
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
					  actualizarDataSistema('codsistema',v);
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
					actualizarDataSistema('nombre',v);
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
		}
		if(!ventanaCreadaSistema)
        {
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
						if (fieldset=='')
						{
							if (validarExistencia()==true)
							{
								Ext.Msg.alert('Mensaje','Registro ya agregado');	
							}
							else
							{
								pasarDatosGrid();
								ventanaSistema.hide();
							}
						}
						else
						{
						
							for (i=0;i<arrTxt.length;i++)
							{
								form.getComponent(fieldset).getComponent(arrTxt[i]).setValue(gridSistema.getSelectionModel().getSelected().get(arrValores[i]));
							}
							if (pantalla=='sistema') //validar que solo se haga en la pantalla de sistemas
							{
								cargarUsuarios(); 
							}
							ventanaSistema.hide();
						}
					}
					},{
                     text: 'Salir',
                     handler: function()
                     {
                      	ventanaSistema.hide();
                     }
				}]
			});
            ventanaCreadaSistema = true;
		}
		ventanaSistema.show();
		if(!unavez)
		{
			gridSistema.render('miGrid');
            unavez=false;
        }
        gridSistema.getSelectionModel().selectFirstRow();
        },
        failure: function ( resultado, request)
		{ 
			Ext.MessageBox.alert('Error', resultado.responseText); 
        }
   });
};
