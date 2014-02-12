/*****************************************************************
* @Definición de Sistema
* @Archivo javascript el cual contiene todos los componentes 
* @de la Definición de Sistemas
* @versión: 1.0      
* @creado: 08/08/2008
* @autor: Ing. Gusmary Balza  
*************************************************
* @fecha modificacion
* @autor 
* @descripcion
*****************************************************************/
var panel = '';
var grid  = '';
var actualizar   = false;
var RecordDefUsu = '';
var gridUsu  = '';
var dsusuario = '';
pantalla = 'sistema';
ruta  =  '../../controlador/msg/sigesp_ctr_msg_sistema.php';  

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
        	tooltip: 'Agregar un usuario al sistema'
		});
		
		var quitar = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitar,
			iconCls: 'bmenuquitar',
        	tooltip: 'Quitar usuario del sistema'
		});
		
		var datosNuevo={"raiz":[{'codusuario':'','nombre':'','apellido':''}]};
				
		Xpos = ((screen.width/2)-(550/2)); 
		Ypos = ((screen.height/2)-(550/2));
		panel = new Ext.FormPanel({
     	title: 'Definición de Sistemas',
        bodyStyle:'padding:5px 5px 5px',
		style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
	   	tbar: [],
		items:[{
			    xtype:'fieldset',
				title:'Datos del Sistema',
				id:'fsformsistema',
    			autoHeight:true,
				autoWidth:true,
				cls :'fondo',		
				items:[{
						xtype:'textfield',
						fieldLabel:'Código',
						name:'código',
						id:'txtcodsistema',
						width:50
						
					  },{
						xtype:'textfield',
						fieldLabel:'Nombre',
						name:'nombre',
						id:'txtnombre',
						width:350
				}]				
			},{
				xtype:'panel',
				title:'Administradores del Sistema',
				tbar: [agregar,quitar],
				contentEl:'grid-usuario'
			}]
		});
		panel.render(document.body);
	
	//llamada a la función
	obtenerGridUsuario();
		

});	//fin

/****************************************************************
* @Función para agregar un registro en la grid y llamar 
* al catálogo de usuarios.
* ParamGridTarget para la grid a la cual se le va a pasar el dato.
* @parametros 
* @retorno
* @fecha creación: 08/08/2008. 
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
* @fecha creación: 08/08/2008
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
		dsusuario =  new Ext.data.Store({
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
				ds: dsusuario,
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
		gridUsu.render('grid-usuario');
	}

	
	
/**************************************************
* @Función para confirmar eliminar un registro 
* de la grid de usuarios.
* @parametros 
* @retorno
* @fecha creación: 11/08/2008
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
* @fecha creación: 13/08/2008
* @autor: Gusmary Balza.
*******************************************************/	
	function quitarUsuarios()
	{
		codsistema = Ext.getCmp('txtcodsistema').getValue();
		codusuario = gridUsu.getSelectionModel().getSelected().get('codusuario');
		var objdata ={
			'oper': 'eliminardetalle',
			'codsistema': codsistema,
			'codusuario': codusuario
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
		}
	});	
		
	}


/***********************************************
* @Función para eliminar un registro de la grid
* de usuarios.
* @parametros 
* @retorno
* @fecha creación: 11/08/2008
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
				
				dsusuario.remove(filaseleccionada);
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
		 panel.getComponent('fsformsistema').getComponent('txtcodsistema').setValue('');
		 panel.getComponent('fsformsistema').getComponent('txtnombre').setValue('');
		 dsusuario.removeAll();
	}
			
	
/****************************************
* @Función para crear un nuevo sistema.
* @parametros
* @retorno
* @fecha de creación: 08/08/2008
* @autor: Gusmary Balza.
**************************************/
	function irNuevo()
	{
		limpiarCampos();
		Ext.getCmp('txtcodsistema').enable();
		actualizar = false;

	}
	
	
/*********************************************************
* @Función que guarda o actualiza los datos de un sistema.
* @parametros
* @retorno
* @fecha de creación: 08/08/2008
* @autor: Gusmary Balza
*********************************************************/	
	function irGuardar()
	{
		valido = true;
		if ((!tbnuevo)&&(!actualizar))
		{
			valido = false;
			Ext.MessageBox.alert('Error','No tiene permiso para Incluir.');
		}
		if ((!tbactualizar)&&(actualizar))
		{
			valido=false;
			Ext.MessageBox.alert('Error','No tiene permiso para Modificar.');
		}
		if ((validarObjetos('txtcodsistema','3','novacio|longexacta')!='0' && validarObjetos('txtnombre','60','novacio|alfanumerico')!='0') && (valido))
		{   
			if (!actualizar)
			{
				evento ='incluirSistema';
				mensaje = 'Incluido';
			}
			else
			{	
				evento ='actualizar';			
				mensaje = 'Modificado';
			}			 	
			var cadenaJson = "{'oper': evento,'sistema': sistema,'vista': vista,'codsistema': '"+panel.getComponent('fsformsistema').getComponent('txtcodsistema').getValue()+"','nombre': '"+panel.getComponent('fsformsistema').getComponent('txtnombre').getValue()+"'";				
				arrAdmin = gridUsu.store.getModifiedRecords();
				if (arrAdmin.length>0)
				{	
					cadenaJson=cadenaJson+ ",datosAdmin:[";
					for (i=0; i < arrAdmin.length; i++)
					{
						if (i==0)
						{
							cadenaJson = cadenaJson +"{'codusuario':'"+ arrAdmin[i].get('codusuario')+ "'}";
						}
						else
						{
							cadenaJson = cadenaJson +",{'codusuario':'"+ arrAdmin[i].get('codusuario')+ "'}";
						}
					}			
					cadenaJson = cadenaJson + "]";
				}
				cadenaJson = cadenaJson + "}";
			
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
	
		
/***********************************************
* @Función que elimina un sistema seleccionado.
* @parametros
* @retorno
* @fecha de creación: 08/08/2008
* @autor: Gusmary Balza.
************************************************/	
	function irEliminar()
	{
		var Result;
		Ext.MessageBox.confirm('Confirmar', '¿Desea eliminar este registro?', Result);
		function Result(btn)
		{
			if(btn=='yes')
			{ 
				if (validarObjetos('txtnombre','50','novacio')=='0')
				{					
					Ext.MessageBox.alert('Mensaje','No existen datos para eliminar');
				}
				else
				{
					var cadenaJson = "{'oper': 'eliminar','sistema': sistema,'vista': vista,'codsistema': '"+panel.getComponent('fsformsistema').getComponent('txtcodsistema').getValue()+"','nombre': '"+panel.getComponent('fsformsistema').getComponent('txtnombre').getValue()+"'";				
				arrAdmin = gridUsu.store.getModifiedRecords();
				if (arrAdmin.length>0)
				{	
					cadenaJson=cadenaJson+ ",datosAdmin:[";
					for (i=0; i < arrAdmin.length; i++)
					{
						if (i==0)
						{
							cadenaJson = cadenaJson +"{'codusuario':'"+ arrAdmin[i].get('codusuario')+ "'}";
						}
						else
						{
							cadenaJson = cadenaJson +",{'codusuario':'"+ arrAdmin[i].get('codusuario')+ "'}";
						}
					}			
					cadenaJson = cadenaJson + "]";
				}
				cadenaJson = cadenaJson + "}";
					 
				objdata= eval('(' + cadenaJson + ')')
				objdata=JSON.stringify(objdata);
				parametros = 'objdata='+objdata;
				mensaje = 'Eliminado';
				Ext.Ajax.request({
				url : ruta,
				params : parametros,
				method: 'POST',
				success: function ( resultad, request )
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
						Ext.MessageBox.alert('Error', datajson.raiz.mensaje);
					}
				},
				failure: function ( result, request)
				{ 
					Ext.MessageBox.alert('Error', result.responseText); 
				} 
				});
			}
		}
	};		
}
	
	
/*****************************************
* @Función que llama al catalogo para 
* mostrar los datos de los sistemas.
* @parametros
* @retorno
* @fecha de creación: 08/08/2008
* @autor: Ing. Gusmary Balza.
*****************************************/
	function irBuscar()
	{
		var arreglotxt     = new Array('txtcodsistema','txtnombre');		
		var arreglovalores = new Array('codsistema','nombre');
		objCatSistema = new catalogoSistema();
		objCatSistema.mostrarCatalogoSistema(panel,'fsformsistema',arreglotxt, arreglovalores);
		actualizar = true;
		Ext.getCmp('txtcodsistema').disable();
		limpiarCampos();
	}
	
	
/*****************************************************
*Función que imprime un reporte ficha de un sistema. 
* seleccionado de acuerdo a un archivo Xml generado.
*@parámetros: 
*@retorna: 
*@fecha de creación:  08/08/2008
*@Autor: Gusmary Balza.	
*******************************************************/	
	function irImprimir()
	{
		var objdata ={
			'oper': 'reporteficha',
			'codsistema': panel.getComponent('fsformsistema').getComponent('txtcodsistema').getValue(),
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
				abrirVentana(datos);
			}			
			else
			{
				Ext.MessageBox.alert('Mensaje', 'No existen datos para imprimir');		
			}
		},
		failure: function ( result, request) 
		{ 
			Ext.MessageBox.alert('Error', result.responseText); 
		} 
		});				
	}	
		