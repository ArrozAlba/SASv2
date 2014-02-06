/***********************************************************************************
* @Archivo javascript que incluye tanto los componentes como los eventos asociados 
* a la definición de Sistema. 
* @fecha de creación: 08/08/2008
* @autor: Ing. Gusmary Balza
*************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/

var panel = '';
var grid  = '';
var cambiar   = false;
var RecordDefUsu = '';
var gridUsu  = '';
var dsusuario = '';
var pantalla = 'sistema';
var arrEliminar = new Array();
var usuarioElim = '';
var toteliminar = 0;
var datosNuevo={'raiz':[{'codusu':'','nomusu':'','apeusu':''}]};
ruta  =  '../../controlador/sss/sigesp_ctr_sss_sistema.php';  

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
        	tooltip: 'Agregar un usuario al sistema'
		});
		
		var quitar = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitar,
			iconCls: 'bmenuquitar',
        	tooltip: 'Quitar usuario del sistema'
		});
				
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

/***********************************************************************************
* @Función para agregar un registro en la grid y llamar al catálogo de usuarios.
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
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
		var arreglovalores = new Array('codusu','cedusu','nomusu','apeusu','telusu','email','ultingusu','actusu','admusu','nota');
		ObjUsuario      = new catalogoUsuario();
		ObjUsuario.mostrarCatalogo('','',arreglotxt, arreglovalores);
	}	
	
	
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
		gridUsu.render('grid-usuario');
	}


/***********************************************************************************
* @Función para confirmar eliminar un registro de la grid de usuarios.
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
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
				dsusuario.remove(filaseleccionada);
				Ext.Msg.alert('Exito','Registro eliminado');				
			}
		} 
	}

	
/***********************************************************************************
* @Limpiar campos del formulario
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function limpiarCampos() 
	{
		Ext.getCmp('txtcodsistema').setValue('');
		Ext.getCmp('txtnombre').setValue('');
		gridUsu.store.removeAll();
		gridUsu.store.loadData(datosNuevo);
		gridUsu.store.commitChanges();
		arrEliminar = new Array();
		toteliminar=0;
	}
	
			
/***********************************************************************************
* @Función que limpia los campos y asigna un nuevo código
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irNuevo()
	{
		limpiarCampos();
		Ext.getCmp('txtcodsistema').enable();
		cambiar = false;
	}
	
	
/***********************************************************************************
* @Función que guarda o actualiza los datos de un sistema.
* @parametros: 
* @retorno: 
* @fecha de creación: 08/07/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irGuardar()
	{
		valido = true;
		if ((!tbnuevo)&&(!cambiar))
		{
			valido = false;
			Ext.MessageBox.alert('Error','No tiene permiso para Incluir.');
		}
		if ((!tbactualizar)&&(cambiar))
		{
			valido=false;
			Ext.MessageBox.alert('Error','No tiene permiso para Modificar.');
		}
		if ((validarObjetos('txtcodsistema','3','novacio|longexacta')!='0' && validarObjetos('txtnombre','60','novacio|alfanumerico')!='0') && (valido))
		{   
			if (!cambiar)
			{
				evento ='incluir';
			}
			else
			{	
				evento ='actualizar';			
			}
			obtenerMensaje('procesar','','Guardando Datos');
						 	
			var cadenaJson = "{'oper': evento,'sistema': sistema,'vista': vista,'codsis': '"+Ext.getCmp('txtcodsistema').getValue()+"','nomsis': '"+Ext.getCmp('txtnombre').getValue()+"'";				
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
			url : ruta,
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
					irNuevo();  
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
	}
	
	
/***********************************************************************************
* @Función que elimina un sistema seleccionado.
* @parametros: 
* @retorno: 
* @fecha de creación: 08/07/2008. 
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
				if (validarObjetos('txtnombre','50','novacio')=='0')
				{					
					Ext.MessageBox.alert('Mensaje','No existen datos para eliminar');
				}
				else
				{
					obtenerMensaje('procesar','','Eliminando Datos');
					
					var objdata ={
						'oper': 'eliminar', 
						'sistema': sistema,
						'vista': vista,
						'codsis': Ext.getCmp('txtcodsistema').getValue()						
					};	
					objdata=JSON.stringify(objdata);
					parametros = 'objdata='+objdata;
					Ext.Ajax.request({
					url : ruta,
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
	
	
/***********************************************************************************
*  @Función que llama al catalogo para mostrar los datos de los sistema.
* @parametros: 
* @retorno: 
* @fecha de creación: 09/07/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irBuscar()
	{
		var arreglotxt     = new Array('txtcodsistema','txtnombre');		
		var arreglovalores = new Array('codsis','nomsis');
		objCatSistema = new catalogoSistema();
		objCatSistema.mostrarCatalogoSistema(arreglotxt, arreglovalores);
		cambiar = true;
		Ext.getCmp('txtcodsistema').disable();
		limpiarCampos();
	}
	
	
/***********************************************************************************
*@Función que imprime un reporte ficha de un sistema seleccionado de acuerdo a un archivo 
* Xml generado.
* @parametros: 
* @retorno: 
* @fecha de creación:  22/07/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function irImprimir()
	{
		var objdata ={
			'oper': 'reporteficha',
			'codsis': Ext.getCmp('txtcodsistema').getValue(),
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