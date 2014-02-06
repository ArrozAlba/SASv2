/*******************************************************************************
* @Proceso de configurar envío de correos.
* @Archivo javascript el cual contiene los componentes del proceso de configurar 
* el envío de correos.
* @versión: 1.0      
* @creado: 10/11/2008
* @autor: Ing. Gusmary Balza 
************************************
* @fecha modificacion
* @autor 
* @descripcion
*******************************************************************************/

var rutaCorreo  =  '../../controlador/sss/sigesp_ctr_sss_enviocorreo.php'; 
var pantalla    = 'enviocorreo';
var panel       = '';
var arrEliminar = new Array();
var datosNuevo  = {'raiz':[{'codusu':'','nomusu':'','apeusu':''}]};
var gridUsu     = '';
var codsistema  = '';
var toteliminar = 0;
Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		Ext.form.Field.prototype.msgTarget = 'side';
		
		var agregar = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregar,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar usuario'
		});
		
		var quitar = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitar,
			iconCls: 'bmenuquitar',
        	tooltip: 'Quitar usuario'
		});		
				
		Xpos = ((screen.width/2)-(550/2)); 
		Ypos = ((screen.height/2)-(550/2));
		panel = new Ext.FormPanel({
			title: 'Configurar Envío de Correo',
			bodyStyle:'padding:5px 5px 5px',
			style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
			tbar: [],
			items:[{
				xtype:'fieldset',
				title:'Datos del Sistema',
				id:'fssistema',
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',		
				items:[{ 					
					xtype:'textfield',
					fieldLabel:'Sistema',
					name:'codigo del sistema',
					id:'txtcodsis',
					readOnly:true,
					width:50
				},{
					xtype:'button',
					id:'btnBuscarSistema',
					handler: irBuscarSistema,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar un sistema',
					style:'position:absolute;left:175px;top:28px',
					width:50					
				},{
					xtype:'textfield',
					name:'nombre del Sistema',
					id:'hidsistema',
					disabled:true,
					hideLabel:true,
					style:'position:absolute;left:190px;top:-25px;border:none',
					width:300	
				},{
					xtype:'hidden',
					fieldLabel:'Funcionalidad',
					name:'codigo del menú',
					readOnly:true,
					id:'hidmenu',
					width:300
				},{
					xtype:'textfield',
					fieldLabel:'Funcionalidad',
					name:'funcionalidad',
					readOnly:true,
					id:'txtmenu',
					width:300
				},{
					xtype:'button',
					id:'btnBuscarMenu',
					handler: irBuscarMenu,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar una funcionalidad',
					style:'position:absolute;left:425px;top:58px',
					width:50			
				}]
				},{
					xtype:'panel',
					width:500,
					title:'Usuarios para el envío de correo',
					tbar: [agregar,quitar],
					contentEl:'grid-usuarioscorreo'
			}]	
		});
		panel.render(document.body);
	
		obtenerGridUsuario();		
	}
);	


/********************************************************************************
* @Función para buscar en el catalogo el sistema seleccionado.
* @fecha de creación: 10/11/2008
* @autor: Ing. Gusmary Balza.
************************************
* @fecha modificacion
* @autor 
* @descripcion
*********************************************************************************/		
	function irBuscarSistema()
	{
		var arreglotxt = new Array('txtcodsis','hidsistema');		
		var arreglovalores = new Array('codsis','nomsis');				
		objCatSistema = new catalogoSistema();
		objCatSistema.mostrarCatalogoSistema(arreglotxt, arreglovalores);		
	}	
	
	
/********************************************************************************
* @Función para buscar en el catalogo los menus del sistema seleccionado.
* @fecha de creación: 10/11/2008
* @autor: Ing. Gusmary Balza.
************************************
* @fecha modificacion
* @autor 
* @descripcion
*********************************************************************************/		
	function irBuscarMenu()
	{		
		if (Ext.getCmp('txtcodsis').getValue()!='')
		{
			codsistema = Ext.getCmp('txtcodsis').getValue();
			var arreglotxt = new Array('hidmenu','txtmenu');		
			var arreglovalores = new Array('codmenu','nomlogico');				
			objCatMenu = new catalogoMenu();
			objCatMenu.mostrarCatalogo(arreglotxt, arreglovalores);
		}
		else
		{
			Ext.Msg.alert('Mensaje','Debe seleccionar un sistema');
		}
	}
	
	
/***********************************************************************************
* @Función para agregar un registro en la grid y llamar al catálogo de usuarios.
* @parametros: 
* @retorno: 
* @fecha de creación: 10/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function irAgregar()
	{
		ParamGridTarget = gridUsu;
		var arreglotxt     = new Array('','');		
		var arreglovalores = new Array('codusu','nomusu','apeusu');
		ObjUsuario      = new catalogoUsuario();
		ObjUsuario.mostrarCatalogo('','',arreglotxt, arreglovalores);
	}			
	
	
/***********************************************************************************
* @Función para crear la grid y pasarle los datos del usuario.
* @parametros: 
* @retorno: 
* @fecha de creación: 24/10/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function obtenerGridUsuario()
	{	
		RecordDefUsu = Ext.data.Record.create
		([
			{name: 'codusu'}, 
			{name: 'nomusu'},
			{name: 'apeusu'}
		]);
		
		var DatosNuevo = {'raiz':[{'codusu':'','nomusu':'','apeusu':''}]};	
		dsusuario =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader({
			root: 'raiz',               
			id: 'id'   
			},
				  RecordDefUsu
			),
			data: DatosNuevo
			});
		
		gridUsu = new Ext.grid.GridPanel({
				width:500,
				autoScroll:true,
				border:true,
				ds: dsusuario,
				cm: new Ext.grid.ColumnModel([
					{header: 'Código', width: 100, sortable: true,   dataIndex: 'codusu'},
					{header: 'Nombre', width: 200, sortable: true, dataIndex: 'nomusu'},
					{header: 'Apellido', width: 200, sortable: true, dataIndex: 'apeusu'}
				]),
				viewConfig: {
								forceFit:true
							},
				autoHeight:true,
				stripeRows: true
		});
		gridUsu.render('grid-usuarioscorreo');
	}
	
		
/***********************************************************************************
* @Función para confirmar eliminar un registro de la grid de usuarios.
* @parametros: 
* @retorno: 
* @fecha de creación: 24/10/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irQuitar()
	{
		var claveseleccionada = gridUsu.selModel.selections.keys;
		if(claveseleccionada.length > 0)
		{
			Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarRegistro);
		} 
		else 
		{
			Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
		}
	}
	
	
/***********************************************************************************
* @Función para eliminar un registro de la grid de usuarios.
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function borrarRegistro(btn) 
	{
		if (btn=='yes') 
		{
			var filaseleccionada = gridUsu.getSelectionModel().getSelected();
			if (filaseleccionada)
			{
				usuarioElim    = gridUsu.getSelectionModel().getSelected().get('codusu');
				arrEliminar[toteliminar] = usuarioElim;
				toteliminar++;
				gridUsu.store.remove(filaseleccionada);
				Ext.Msg.alert('Exito','Registro eliminado');				
			}
		} 
	}
		
		
/***********************************************************************************
* @Función para limpiar los campos.
* @parametros: 
* @retorno: 
* @fecha de creación: 10/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function irCancelar()
	{
		Ext.getCmp('txtcodsis').setValue('');
		Ext.getCmp('hidsistema').setValue('');
		Ext.getCmp('txtmenu').setValue('');
		Ext.getCmp('hidmenu').setValue('');
		gridUsu.store.removeAll();
		gridUsu.store.loadData(datosNuevo);
		gridUsu.store.commitChanges();
		arrEliminar = new Array();
		toteliminar=0;
		cambiar = false;
	}
		
	
/*************************************************************************************
* @Función para guardar o actualizar los usuarios asociados a un menu para un sistema.
* @parametros: 
* @retorno: 
* @fecha de creación: 10/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*************************************************************************************/			
	function irGuardar()
	{
		valido = true;
		evento ='incluir';
		
		obtenerMensaje('procesar','','Guardando Datos');
		
		var cadenaJson = "{'oper': evento,'sistema': sistema,'vista': vista,'codsis': '"+Ext.getCmp('txtcodsis').getValue()+"','codmenu': '"+Ext.getCmp('hidmenu').getValue()+"'";				
		arrAdmin = gridUsu.store.getModifiedRecords();
		cadenaJson=cadenaJson+ ',datosAdmin:[';
		total = arrAdmin.length;
		if (total>0)
		{
			for (i=0; i < total; i++)
			{
				if (i==0)
				{
					cadenaJson = cadenaJson +"{'codusu':'"+ arrAdmin[i].get('codusu')+ "'}";
				}
				else
				{
					cadenaJson = cadenaJson +",{'codusu':'"+ arrAdmin[i].get('codusu')+ "'}";
				}
			}			
		}
		cadenaJson = cadenaJson + ']';
		cadenaJson = cadenaJson+ ',datosEliminar:[';
		total = arrEliminar.length;
		if (total>0)
		{
			for (i=0; i < total; i++)
			{
				if (i==0)
				{
					cadenaJson = cadenaJson +"{'codusu':'"+ arrEliminar[i]+ "'}";
				}
				else
				{
					cadenaJson = cadenaJson +",{'codusu':'"+ arrEliminar[i]+ "'}";
				}
			}			
		}
		cadenaJson = cadenaJson + ']}';
		objdata= eval('(' + cadenaJson + ')');				
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
		url : rutaCorreo,
		params : parametros,
		method: 'POST',
		success: function (resultado, request)
		{ 
			datos = resultado.responseText;
			Ext.Msg.hide();
			var datajson = eval('(' + datos + ')');
			if (datajson.raiz.valido==true)
			{	
				Ext.MessageBox.alert('Mensaje',datajson.raiz.mensaje);
				irCancelar();  
			}
			else
			{
				Ext.MessageBox.alert('Error', datajson.raiz.mensaje);
			}
		},
		failure: function (result,request) 
		{ 
			Ext.Msg.hide();
			Ext.MessageBox.alert('Error', 'Error al procesar la Información'); 
		}					
		});	
	}
		
		
/***********************************************************************************
* @Función para eliminar un registro de usuarios para un menu en un sistema.
* @parametros: 
* @retorno: 
* @fecha de creación: 10/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function irEliminar()
	{
		var Result;
		Ext.MessageBox.confirm('Confirmar', '¿Desea eliminar este registro?', Result);
		function Result(btn)
		{
			if(btn=='yes')
			{ 
				if (validarObjetos('txtcodsis','50','novacio')=='0')
				{					
					Ext.MessageBox.alert('Mensaje','No existen datos para eliminar');
				}
				else
				{
					obtenerMensaje('procesar','','Eliminado Datos');
					
					var objdata ={
						'oper': 'eliminar', 
						'sistema': sistema,
						'vista': vista,
						'codsis': Ext.getCmp('txtcodsis').getValue(),
						'codmenu': Ext.getCmp('hidmenu').getValue()					
					};	
					objdata=JSON.stringify(objdata);
					parametros = 'objdata='+objdata;
					Ext.Ajax.request({
					url : rutaCorreo,
					params : parametros,
					method: 'POST',
					success: function ( resultado, request )
					{ 
						datos = resultado.responseText;
						Ext.Msg.hide();
						var datajson = eval('(' + datos + ')');
						if (datajson.raiz.valido==true)
						{
							Ext.MessageBox.alert('Mensaje',datajson.raiz.mensaje);
							limpiarCampos();		  
						}
						else
						{
							Ext.MessageBox.alert('Error', datajson.raiz.mensaje);
						}
					},
					failure: function ( result, request)
					{ 
						Ext.Msg.hide();
						Ext.MessageBox.alert('Error', result.responseText); 
					} 
					});
				}
			}
		};			
	}
	
	