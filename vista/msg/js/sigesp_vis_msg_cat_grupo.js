/***********************************************
*@archivo javascript para el catálogo de grupos
*@version: 1.0
*@fecha de creación: 09/07/2008.
*@autor: Ing. Gusmary Balza.
***********************************************/

var gridGrupoCreada    = false;
var ventanaGrupoCreada = false;
var datos              = null;
var gridGrupo          = null;
var ventanaGrupo       = null;
var unavez             = false;
var parametros         = '';
var rutaGrupo = '../../controlador/msg/sigesp_ctr_msg_grupo.php';
var rutaUsuarioGrupo = '../../controlador/msg/sigesp_ctr_msg_usuariogrupo.php';


/*****************************************************
*
*@Función genérica para el uso del catálogo de grupos
*
******************************************************/
function catalogoGrupo()
{		

	this.mostrarCatalogo = mostrarCatalogoGrupo;
//	this.actualizarData=actualizarData;
}


/*****************************************************************
* @Función que acualiza el catalgo para buscar por determinado campo
* @parametros: criterio: campo por el que se actualiza
*			   cadena: campo a actualizar
* @retorno:
* @fecha de creación:
*****************************************************************/
function actualizarDataGrupo(criterio,cadena)
{
	var myJSONObject ={
		"oper": 'buscarcadena',
		"cadena": cadena,
		"criterio": criterio,
		"codgrupo":'',
		"nombre":'',
		"nota":''
	};
	objdata=JSON.stringify(myJSONObject);
	parametros = 'objdata='+objdata; 
	Ext.Ajax.request({
	url : rutaGrupo,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request )
	{ 
		datos = resultado.responseText;
		if(datos!='')
		{
			var DatosNuevo = eval('(' + datos + ')');
			gridGrupo.store.loadData(DatosNuevo);
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
	
function cargarUsuariosGrupo()
{
	codgrupo = Ext.getCmp('txtcodgrupo').getValue();
	var objdata ={
			'oper': 'catalogodetalle',
			'codgrupo': codgrupo,
			'sistema': sistema,
			'vista': vista
				
	};
	objdata=JSON.stringify(objdata);
	parametros = 'objdata='+objdata;
	Ext.Ajax.request({
		url : rutaUsuarioGrupo,
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
	
	
/********************************************************
* @Función que busca el listado de grupos.
* @parámetros: form: id del formulario, 
* fieldset: id del fieldset,
* array: arrerglo con los campos del formulario
* arrayRecord: arreglo con los campos de la base de datos.
* @fecha de creación: 09/07/2008
* @autor: 
***************************
* @fecha modificacion: 23/07/2008
* @autor: Ing. Gusmary Balza.
* @descripcion: modificación al handler del aceptar, 
* inclusión de dos parámetros de tipo arreglo.
*********************************************************/
function mostrarCatalogoGrupo(form,fieldset,array, arrayRecord)
{
	var objdata ={
		"oper": 'catalogo', 
		"codgrupo": '', 
		"nombre": '',
		"nota":'',
		'sistema': 'MSG',
		'vista': 'sigesp_vis_msg_grupo.html'
	};
	objdata=JSON.stringify(objdata);
	parametros = 'objdata='+objdata;
	Ext.Ajax.request({
	url : rutaGrupo,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) 
	{ 
		datos = resultado.responseText;
		var myObject = eval('(' + datos + ')');
		var RecordDef = Ext.data.Record.create([
		{name: 'codgrupo'},     // "mapping" property not needed if it's the same as "name"
		{name: 'nombre'},
		{name: 'nota'}	// This field will use "occupation" as the mapping.
		]);
        if (!gridGrupoCreada)
		{
			gridGrupo = new Ext.grid.GridPanel({
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
				{header: "Código", width: 30, sortable: true,   dataIndex: 'codgrupo'},
                {header: "Nombre", width: 50, sortable: true, dataIndex: 'nombre'},
			]),
            viewConfig: {
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
                   gridGrupoCreada = true;
		}
		else
		{
			gridGrupo.store.loadData(myObject);
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
				name: 'codgrupo',
				id:'codgrupo',
				width:50,
				changeCheck: function()
				{
					  var v = this.getValue();
					  actualizarDataGrupo('codgrupo',v);
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
				name: 'nomgrupo',
				id:'nomgrupo',
				changeCheck: function()
				{
					var v = this.getValue();
					actualizarDataGrupo('nombre',v);
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
		if(!ventanaGrupoCreada)
        {
			ventanaGrupo = new Ext.Window(
			{
				title: 'Cat&aacute;logo de Grupos',
		    	autoScroll:true,
                width:500,
                height:400,
                modal: true,
                closeAction:'hide',
                plain: false,
                items:[panelBusqueda,gridGrupo],
                buttons: [{
                	text:'Aceptar',  
                    handler: function()
					{                     	
						if (fieldset=='')
						{
							pasarDatosGrid();
							ventanaGrupo.hide();
						}
						else
						{						
							for (i=0;i<array.length;i++)
							{
								form.getComponent(fieldset).getComponent(array[i]).setValue(gridGrupo.getSelectionModel().getSelected().get(arrayRecord[i]));
							}
							if (pantalla=='usuariogrupo')
							{
								cargarUsuariosGrupo();
							}
							ventanaGrupo.hide();
						}
					}
					},{
                     text: 'Salir',
                     handler: function()
                     {
                      ventanaGrupo.hide();
                     }
				}]
			});
            ventanaGrupoCreada = true;
		}
		ventanaGrupo.show();
		if(!unavez)
		{
			gridGrupo.render('miGrid');
            unavez=false;
        }
        gridGrupo.getSelectionModel().selectFirstRow();
        },
        failure: function ( resultado, request)
		{ 
			Ext.MessageBox.alert('Error', resultado.responseText); 
        }
   });
};
