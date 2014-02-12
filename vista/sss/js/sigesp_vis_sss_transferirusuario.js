/***********************************************************************************
* @Archivo javascript que incluye tanto los componentes como los eventos asociados 
* al proceso de transferir usuarios y permisología. 
* @fecha de creación: 17/11/2008
* @autor: Ing. Gusmary Balza
*************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
var cambiar = false;
var panel      = '';
var pantalla   = 'traspaso';
var datosNuevo={'raiz':[{'codusu':'','cedusu':'','nomusu':'','apeusu':''}]};
var ruta = '../../controlador/sss/sigesp_ctr_sss_transferirusuario.php';
var conexion = false;

Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		Ext.form.Field.prototype.msgTarget = 'side';
		
		Xpos = ((screen.width/2)-(600/2)); 
		Ypos = ((screen.height/2)-(700/2));
		//Panel con los componentes del formulario
		panel = new Ext.FormPanel({	        
	     	title: 'Proceso de Transferir Usuarios y Permisología',
	        bodyStyle:'padding:5px 5px 5px',
			style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
		  	tbar: [],
		  	width: 600,
			items:[{
				xtype:'fieldset',
				labelWidth: 150,
				title:'Base de Datos Origen/Destino',
				id:'fsbd',
    			autoHeight:true,
				autoWidth:true,
				cls :'fondo',	
			    items:[{	
			    	xtype:'textfield',
					fieldLabel:'Base de Datos Origen',
					name:'Base de Datos Origen',
					id:'txtbdorigen',
					//disabled: true,
					readOnly:true,
					width:150
				},{
					xtype:'textfield',
					fieldLabel:'Base de Datos Destino',
					name:'Base de Datos Destino',
					id:'txtbddestino',
					//disabled: true,
					readOnly:true,
					width:150
				},{
					xtype:'button',
					id:'btnBuscarUsuario',
					handler: irBuscarBdDestino,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar Base de Datos Destino',
					style:'position:absolute;left:325px;top:54px',
					width:50	
				},{
					xtype:'button',
					id:'btnConectar',
					handler: irConectar,
					iconCls: 'bmenuconectar',
					tooltip: 'Cargar la base de datos',
					style:'position:absolute;left:355px;top:54px',
					width:50		
				}]		
			},{
				xtype:'fieldset',
				labelWidth: 75,
				title:'Parámetros de Búsqueda',
				id:'fsbusqueda',
    			autoHeight:true,
				autoWidth:true,
				cls :'fondo',	
			    items:[{	
			    	xtype:'textfield',
					fieldLabel:'Desde',
					id:'txtdesde',
					disabled: true,
					width:150
				},{
					xtype:'button',
					id:'btnBuscarUsuarioDesde',
					handler: irBuscarUsuarioDesde,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar Usuario',
					style:'position:absolute;left:250px;top:125px',
					width:50	
					
				},{
					xtype:'textfield',
					fieldLabel:'Hasta',
					id:'txthasta',
					disabled: true,
					width:150
				},{
					xtype:'button',
					id:'btnBuscarUsuarioHasta',
					handler: irBuscarUsuarioHasta,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar Usuario',
					style:'position:absolute;left:250px;top:150px',
					width:50				
				},{
					xtype:'textfield',
					fieldLabel:'Código',
					name:'Código del Usuario',
					id:'txtcodusu',
					width:200
				},{
					xtype:'textfield',
					fieldLabel:'Cédula',
					name:'Cédula del Usuario',
					id:'txtcedusu',
					width:100	
				},{
					xtype:'textfield',
					fieldLabel:'Nombre',
					name:'Nombre del Usuario',
					id:'txtnomusu',
					width:300
				},{
					xtype:'textfield',
					fieldLabel:'Apellido',
					name:'Apellido del Usuario',
					id:'txtapeusu',
					width:300
				},{
					buttons: [{
            			text: 'Buscar Usuarios',
            			handler: buscarUsuarios
            		}]			
				}]
			},{
				xtype:'panel',
				autoScroll:true,
				height: 150,
				title:'Datos de los Usuarios',
				contentEl:'grid-traspasousuarios'												
			}]
		});	
		panel.render(document.body);
		
		obtenerGridUsuario();
		mostrarBd();
	}
);	


/***********************************************************************************
* @Función para crear la grid y pasarle los datos del usuario.
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
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
			{name: 'cedusu'}, 
			{name: 'nomusu'},
			{name: 'apeusu'}
		]);
		
		dsusuario =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(datosNuevo),
		reader: new Ext.data.JsonReader({
			root: 'raiz',               
			id: 'id'   
			},
				  RecordDefUsu
			),
			data: datosNuevo
			});
		
		gridUsu = new Ext.grid.GridPanel({
			width:600,
			height: 200,
			autoScroll:true,
			border:true,
			ds: dsusuario,
			cm: new Ext.grid.ColumnModel([
			  new Ext.grid.CheckboxSelectionModel(),
				{header: 'Código', width: 50, sortable: true,   dataIndex: 'codusu'},
				{header: 'Cédula', width: 50, sortable: true,   dataIndex: 'cedusu'},
				{header: 'Nombre', width: 70, sortable: true, dataIndex: 'nomusu'},
				{header: 'Apellido', width: 70, sortable: true, dataIndex: 'apeusu'}
			]),
			sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
			viewConfig: {
							forceFit:true
						},
			autoHeight:true,
			stripeRows: true
		});
		gridUsu.render('grid-traspasousuarios');
	}


/***********************************************************************************
* @Función para mostrar la base de datos sobre la cual se encuentra.
* @parametros: 
* @retorno: 
* @fecha de creación: 17/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/					
	function mostrarBd()
	{
		var objdata ={
			'operacion': 'obtenerBdOrigen',
			'sistema': sistema,
			'vista': vista
		};		
		objdata=JSON.stringify(objdata);		
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function (resultad,request)
			{
				datos = resultad.responseText;
				var datajson = Ext.util.JSON.decode(datos);
				if (datajson!=null)
				{
					Ext.getCmp('txtbdorigen').setValue(datajson.ls_database);
				}
			},
			failure: function ( result, request)
			{ 
				Ext.MessageBox.alert('Error', result.responseText); 
			}
		});	 
	}


/***********************************************************************************
* @Función para buscar las base de datos destino.
* @parametros: 
* @retorno: 
* @fecha de creación: 17/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function irBuscarBdDestino()
	{		
		var arreglotxt = new Array('txtbddestino');		
		var arreglovalores = new Array('codbasedatos');				
		objCatBd = new catalogoBd();
		objCatBd.mostrarCatalogoBd(arreglotxt, arreglovalores);
	}
				

/***********************************************************************************
* @Función para cargar la base de datos destino seleccionada.
* @parametros: 
* @retorno: 
* @fecha de creación: 18/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function irConectar()
	{
		if (validarObjetos('txtbddestino','80','novacio')!='0')
		{		
			if (Ext.getCmp('txtbdorigen').getValue() != Ext.getCmp('txtbddestino').getValue())
			{
				var objdata ={
					'operacion': 'conectar',
					'basedatos': Ext.getCmp('txtbddestino').getValue(),
					'sistema': sistema,
					'vista': vista
				};	
				objdata=JSON.stringify(objdata);		
				parametros = 'objdata='+objdata; 
				Ext.Ajax.request({
					url : ruta,
					params : parametros,
					method: 'POST',
					success: function (resultad,request)
					{
						datos = resultad.responseText;
						var datajson = Ext.util.JSON.decode(datos);
						if (datajson.raiz!=null)
						{
							conexion = true;
							Ext.Msg.alert('Mensaje','La Conexión con la Base de Datos Destino es Correcta !!!');
						}
					},
					failure: function ( result, request)
					{ 
						Ext.MessageBox.alert('Error', result.responseText); 
					}
				});				
			}
			else
			{
				Ext.Msg.alert('Alerta','La Base de Datos Destino y la Base de Datos Origen deben ser diferentes!!!');
			}
		}	
	}


/***********************************************************************************
* @Función para buscar los usuarios.
* @parametros: 
* @retorno: 
* @fecha de creación: 17/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function irBuscarUsuarioDesde()
	{
		var arreglotxt = new Array('txtdesde');		
		var arreglovalores = new Array('codusu','nomusu','apeusu');				
		objCatusuario = new catalogoUsuario();
		objCatusuario.mostrarCatalogo(panel,'fsbusqueda',arreglotxt, arreglovalores);
	}
	

/***********************************************************************************
* @Función para buscar los usuarios.
* @parametros: 
* @retorno: 
* @fecha de creación: 17/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function irBuscarUsuarioHasta()
	{
		var arreglotxt = new Array('txthasta');		
		var arreglovalores = new Array('codusu','nomusu','apeusu');				
		objCatusuario = new catalogoUsuario();
		objCatusuario.mostrarCatalogo(panel,'fsbusqueda',arreglotxt, arreglovalores);
	}
	

/***********************************************************************************
* @Función para buscar los usuarios en el rango seleccionado.
* @parametros: 
* @retorno: 
* @fecha de creación: 18/11/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function buscarUsuarios()
	{
		if (validarObjetos('txtbddestino','80','novacio')!='0' && validarObjetos('txtcodusu','30','alfanumerico')!='0' && validarObjetos('txtcedusu','8','numero')!='0' && validarObjetos('txtnomusu','100','nombre')!='0' && validarObjetos('txtapeusu','50','nombre')!='0')
		{
			if (conexion)
			{
				var objdata ={
					'operacion': 'obtenerUsuarios',
					'desde': Ext.getCmp('txtdesde').getValue(),
					'hasta': Ext.getCmp('txthasta').getValue(),
					'codusu': Ext.getCmp('txtcodusu').getValue(),
					'cedusu': Ext.getCmp('txtcedusu').getValue(),
					'nomusu': Ext.getCmp('txtnomusu').getValue(),
					'apeusu': Ext.getCmp('txtapeusu').getValue(),
					'sistema': siscatUsuario,
					'vista': viscatUsuario
				};	
				objdata=JSON.stringify(objdata);		
				parametros = 'objdata='+objdata; 
				Ext.Ajax.request({
					url : ruta,
					params : parametros,
					method: 'POST',
					success: function (resultad,request)
					{
						datos = resultad.responseText;
						var datajson = Ext.util.JSON.decode(datos);
						if (datajson!=null)
						{
							gridUsu.store.loadData(datajson);
						}
					},
					failure: function ( result, request)
					{ 
						Ext.MessageBox.alert('Error', result.responseText); 
					}
				});				
			}
			else
			{
				Ext.Msg.alert('Alerta','No se ha verificado conexión a Base de Datos Destino');
			}			
		}
	}
	
	
	function limpiarCampos()
	{
		Ext.getCmp('txtcodusu').setValue('');
		Ext.getCmp('txtcedusu').setValue('');
		Ext.getCmp('txtnomusu').setValue('');
		Ext.getCmp('txtapeusu').setValue('');	
	}
	
	
/***********************************************************************************
* @Función para limpiar todos los campos.    
* @parametros: 
* @retorno:
* @fecha de creación: 17/11/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function irCancelar()
	{
		
		Ext.getCmp('txtbddestino').setValue('');
		Ext.getCmp('txtdesde').setValue('');
		Ext.getCmp('txthasta').setValue('');
		limpiarCampos();
		gridUsu.store.removeAll();
		gridUsu.store.loadData(datosNuevo);
		gridUsu.store.commitChanges();
	}
	

/***********************************************************************************
* @Función para verificar la empresa al conectarse a la base de datos destino.    
* @parametros: 
* @retorno:
* @fecha de creación: 18/11/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function irProcesar()
	{
		if (conexion)
		{		
			var objdata ={
				'operacion': 'conectarBdDestino',
				'sistema': sistema,
				'vista': vista
				
			};	
			objdata=JSON.stringify(objdata);		
			parametros = 'objdata='+objdata; 
			Ext.Ajax.request({
				url : ruta,
				params : parametros,
				method: 'POST',
				success: function (resultad,request)
				{
					datos = resultad.responseText;
					var datajson = Ext.util.JSON.decode(datos);
					if (datajson.raiz!=null)
					{
						transferirUsuarios();
					}
				},
				failure: function ( result, request)
				{ 
					Ext.MessageBox.alert('Error', result.responseText); 
				}
			});
		}
		else
		{
			Ext.Msg.alert('Alerta','No se ha creado conexion con la Base de Datos Destino !!!')
		}
	}	
	
	
/***********************************************************************************
* @Función para procesar la transferencia de usuarios y su permisología.
* @parametros: 
* @retorno: 
* @fecha de creación: 18/11/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function transferirUsuarios()
	{
		obtenerMensaje('procesar','','Transfiriendo Datos');
		
		if (conexion)
		{
			limpiarCampos();
			arrSelUsu = gridUsu.getSelectionModel().getSelections();
			total = arrSelUsu.length;
			
			var objdata = "{'operacion': 'procesar','sistema': sistema,'vista': vista";				
			
			objdata=objdata+ ",datosUsu:[";
			
			if (total>0)
			{	
				for (i=0; i < total; i++)
				{
					if (i==0)
					{
						objdata = objdata +"{'codusu':'"+ arrSelUsu[i].get('codusu')+ "'}";
					}
					else
					{
						objdata = objdata +",{'codusu':'"+ arrSelUsu[i].get('codusu')+ "'}";
					}
				}			
			}
			objdata = objdata + ']}';
			objdata= eval('(' + objdata + ')');
			objdata=JSON.stringify(objdata);		
			parametros = 'objdata='+objdata; 
			Ext.Ajax.request({
				url : ruta,
				params : parametros,
				method: 'POST',
				success: function (resultad,request)
				{
					datos = resultad.responseText;
					//alert(datos);
					Ext.Msg.hide();	
					var datajson = Ext.util.JSON.decode(datos);
					if (datajson.raiz.valido==true)
					{
						
						//Ext.Msg.alert('Mensaje', datajson.raiz.mensaje);
						Ext.Msg.alert('Mensaje', 'Transferencia de Usuarios realizada con éxito!');
						irCancelar();  
					}
					else
					{
						Ext.Msg.alert('Error', datajson.raiz.mensaje);
					}
				},
				failure: function ( result, request)
				{ 
					Ext.Msg.hide();	
					Ext.MessageBox.alert('Error', 'No se logró procesar la información'); 
				}
			});		
		}
		else
		{
			Ext.Msg.alert('Alerta','No se ha creado conexion con la Base de Datos Destino !!!')
		}
	}	
	
	
	
		