/***************************************************************
* @Proceso de asignar usuarios a grupos.
* @Archivo javascript el cual contiene los componentes del 
* @proceso de asignar usuarios a grupos
* @versión: 1.0      
* @creado: 15/08/2008
* @autor: Ing. Gusmary Balza 
***************************
* @fecha modificacion
* @autor 
* @descripcion
************************************************************/
var panel = '';
var pantalla='usuariogrupo'; //parametro para el catalogo
ruta =  '../../controlador/msg/sigesp_ctr_msg_usuariogrupo.php'; 
Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		// turn on validation errors beside the field globally
		Ext.form.Field.prototype.msgTarget = 'side';				
		var agregar = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregar,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar un usuario al grupo'
		});
		
		var quitar = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitar,
			iconCls: 'bmenuquitar',
        	tooltip: 'Quitar usuario del grupo'
		});
		
		var datosNuevo={"raiz":[{'codusuario':'','nombre':'','apellido':''}]};
		
		Xpos = ((screen.width/2)-(550/2)); 
		Ypos = ((screen.height/2)-(550/2));
		panel = new Ext.FormPanel({
     	title: 'Asignación de Usuarios a Grupo',
        bodyStyle:'padding:5px 5px 5px',
		style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
	   	tbar: [],
		items:[{
			    xtype:'fieldset',
				title:'Datos del Grupo',
				id:'fsformgrupo',
    			autoHeight:true,
				autoWidth:true,
				cls :'fondo',		
				items:[{
						xtype:'textfield',
						fieldLabel:'Código',
						name:'código',
						id:'txtcodgrupo',
						disabled:true,
						width:50
					  },{
						xtype:'textfield',
						fieldLabel:'Nombre',
						name:'nombre',
						id:'txtnombre',
						disabled:true,
						width:350
				}]				
			},{
				xtype:'panel',
				title:'Usuarios del Grupo',
				tbar: [agregar,quitar],
				contentEl:'grid-usuariogrupo'
			}]
		});
		panel.render(document.body);
		obtenerGridUsuario();
		
}); //fin
/****************************************************************
* @Función para agregar un registro en la grid y llamar 
* al catálogo de usuarios.
* ParamGridTarget para la grid a la cual se le va a pasar el dato.
* @parametros 
* @retorno
* @fecha creación: 15/08/2008. 
* @autor: Gusmary Balza. 
*******************************************/			
	function irAgregar()
	{
		ParamGridTarget = gridUsu;
		var arreglotxt     = new Array('','');		
		var arreglovalores = new Array('codusuario','cedula','nombre','apellido','telefono','email','fecultingreso','estatus','administrador','nota');
		ObjUsuario      = new catalogoUsuario();
		ObjUsuario.mostrarCatalogo('','',arreglotxt, arreglovalores);
	}	


/**************************************
* @Función para crear la grid y pasarle
* los datos del usuario.
* @parametros 
* @retorno
* @fecha creación: 15/08/2008
* @autor: Gusmary Balza.
****************************************/			
	function obtenerGridUsuario()
	{	
		RecordDefUsu = Ext.data.Record.create
		([
			{name: 'codusuario'}, 
			{name: 'nombre'},
			{name: 'apellido'}
		]);
		
		var DatosNuevo = {"raiz":[{"codusuario":'',"nombre":'',"apellido":''}]};	
		dsusuariogrupo =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader({
			root: 'raiz',               
			id: "id"   
			},
				  RecordDefUsu
			),
			data: DatosNuevo
			});
		
		gridUsu = new Ext.grid.GridPanel({
				width:500,
				autoScroll:true,
				border:true,
				ds: dsusuariogrupo,
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
		gridUsu.render('grid-usuariogrupo');
	}

	//llamada a la función
	
	

/**************************************************
* @Función para confirmar eliminar un registro 
* de la grid de usuarios.
* @parametros 
* @retorno
* @fecha creación: 15/08/2008
* @autor: Gusmary Balza.
****************************************/		
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


/*******************************************************
* @Función para eliminar un registro de la base de datos.
* @parametros 
* @retorno
* @fecha creación: 15/08/2008
* @autor: Gusmary Balza.
*******************************************************/	
	function quitarUsuarios()
	{
		codgrupo = Ext.getCmp('txtcodgrupo').getValue();
		codusuario = gridUsu.getSelectionModel().getSelected().get('codusuario');
		var objdata ={
			'oper': 'eliminardetalle',
			'codgrupo': codgrupo,
			'codusuario': codusuario
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
			}
		});	
	}


/***********************************************
* @Función para eliminar un registro de la grid
* de usuarios.
* @parametros 
* @retorno
* @fecha creación: 15/08/2008
* @autor: Gusmary Balza.
****************************************/	
	function borrarRegistro(btn) 
	{
		if (btn=='yes') 
		{
			var filaseleccionada = gridUsu.getSelectionModel().getSelected();
			if (filaseleccionada)
			{
				quitarUsuarios();
				
				dsusuariogrupo.remove(filaseleccionada);
				Ext.Msg.alert('Exito','Registro eliminado');				
			}
		} 
	}
	
	
/************************************
* @Limpiar campos del formulario
* @parametros 
* @retorno
* @fecha creación 
* @autor 
****************************************/		
	function limpiarCampos() 
	{
		 panel.getComponent('fsformgrupo').getComponent('txtcodgrupo').setValue('');
		 panel.getComponent('fsformgrupo').getComponent('txtnombre').setValue('');
		 dsusuariogrupo.removeAll();
	}
			
	
/****************************************
* @Función para crear un nueva asignación.
* @parametros
* @retorno
* @fecha de creación: 15/08/2008
* @autor: Gusmary Balza.
**************************************/
	function irCancelar()
	{
		limpiarCampos();
		actualizar = false;
	}
		
	
/******************************************************************
* @Función que guarda o actualiza los datos de usuarios en un grupo.
* @parametros
* @retorno
* @fecha de creación: 15/08/2008
* @autor: Gusmary Balza
********************************************************************/	
	function irGuardar()
	{
		evento  = 'incluir';			
		mensaje = 'incluido';
		var cadenaJson = "{ 'oper': evento,'sistema': sistema,'vista': vista,'usuarios':[ ";	//cada detalle			
		arrAdmin = gridUsu.store.getModifiedRecords();
		if (arrAdmin.length>0)
		{	
			for (i=0; i < arrAdmin.length; i++)
			{
				if (i==0)
				{
					cadenaJson=cadenaJson+"{'codgrupo': '"+panel.getComponent('fsformgrupo').getComponent('txtcodgrupo').getValue()+"','codusuario':'"+ arrAdmin[i].get('codusuario')+ "'}";
				}
				else
				{
					cadenaJson=cadenaJson+",{'codgrupo': '"+panel.getComponent('fsformgrupo').getComponent('txtcodgrupo').getValue()+"','codusuario':'"+ arrAdmin[i].get('codusuario')+ "'}";
				}
			}			
		}
		cadenaJson = cadenaJson + "]}";
		if (Ext.getCmp('txtcodgrupo').getValue()=='')
		{
			Ext.Msg.alert('Mensaje','Seleccione el grupo');
		}
		else
		{
			objdata= eval('(' + cadenaJson + ')');				
			objdata=JSON.stringify(objdata);
			parametros = 'objdata='+objdata; 
			Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function (resultad, request)
			{ 
				datos = resultad.responseText;
				var datajson = eval('(' + datos + ')');
				if (datajson.raiz.valido==true)
				{	
					Ext.MessageBox.alert('Mensaje','Registro '+mensaje + ' con éxito');
					limpiarCampos();  
				}
				else
				{
					Ext.MessageBox.alert('Mensaje', datajson.raiz.mensaje);
				}
			},
			failure: function (result,request) 
			{ 
				Ext.MessageBox.alert('Error', 'El registro no se pudo incluir'); 
			}					
			});
		}
	}
	
/*****************************************
* @Función que llama al catalogo para 
* mostrar los datos de los grupos.
* @parametros
* @retorno
* @fecha de creación: 15/08/2008
* @autor: Ing. Gusmary Balza.
*****************************************/
	function irBuscar()
	{
		var arreglotxt     = new Array('txtcodgrupo','txtnombre');		
		var arreglovalores = new Array('codgrupo','nombre');
		objCatGrupo = new catalogoGrupo();
		objCatGrupo.mostrarCatalogo(panel,'fsformgrupo',arreglotxt, arreglovalores);
		Ext.getCmp('txtcodgrupo').disable();
		Ext.getCmp('txtnombre').disable();
		limpiarCampos();
	}	
	
	
