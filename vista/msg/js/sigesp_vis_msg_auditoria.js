/**********************************************************************************
* @Reporte de auditoria.
* @Archivo javascript el cual contiene los componentes del reporte de auditoria.
* @versión: 1.0      
* @fecha de creación: 28/08/2008
* @autor: Ing. Gusmary Balza  
*******************************************************************
* @fecha modificacion
* @autor 
* @descripcion
************************************************************************************/
var panel = '';
ruta =  '../../controlador/msg/sigesp_ctr_msg_auditoria.php'; 
var pantalla = 'auditoria';
recordDefecto = '';
Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		// turn on validation errors beside the field globally
		Ext.form.Field.prototype.msgTarget = 'side';
					
		//cargar los datos de los sistemas para asociarlos al combo.
		var datosSistema = {"raiz":[{'codsistema':'PRB','nombre':'Prueba'}]};
		
			recordSistema = Ext.data.Record.create([
				{name: 'codsistema'},     
				{name: 'nombre'}
			]);					
			dssistema =  new Ext.data.Store({
					proxy: new Ext.data.MemoryProxy(datosSistema),
					reader: new Ext.data.JsonReader(
					{
						root: 'raiz',               
						id: 'id'   
					},
					recordSistema
					),
					data: datosSistema			
				 });
			
		//cargar los datos de los eventos para asociarlos al combo.
		var datosEvento = {"raiz":[{'evento':'evento','descripcion':'descripcion'}]};
		
			record = Ext.data.Record.create([
				{name: 'evento'},     
				{name: 'descripcion'}
			]);					
			dsevento =  new Ext.data.Store({
					proxy: new Ext.data.MemoryProxy(datosEvento),
					reader: new Ext.data.JsonReader(
					{
						root: 'raiz',               
						id: 'id'   
					},
					record
					),
					data: datosEvento			
				 });


/***************************************************************
* @Función para cargar en el combo los nombres de los sistemas.
* @fecha de creación:19/08/2008
* @autor: Ing. Gusmary Balza
**************************************************************/			
		function cargarSistemas()
		{
			var recordInicial = new recordSistema({
				nombre:'TODOS',
				
			});	
			
			var objdata ={'operacion': 'obtenerSistema'};		
			objdata=JSON.stringify(objdata);			
			parametros = 'objdata='+objdata;
			Ext.Ajax.request({
				url : ruta,
				params : parametros,
				method: 'POST',
				success: function (resultad,request)
				{
					datos = resultad.responseText;
					var datajson = eval('(' + datos + ')');
					if (datajson.raiz!=null)
					{
						dssistema.loadData(datajson);
						dssistema.insert(0,recordInicial);
						Ext.getCmp('cmbsistema').setValue('TODOS');
						cargarEventos();
					}
				},
				failure: function ( result, request)
				{ 
					Ext.MessageBox.alert('Error', result.responseText); 
				},
				
			});	
		}
		cargarSistemas();	
		
/**************************************************************
* @Función para cargar en el combo los nombres de los eventos.
* @fecha de creación:19/08/2008
* @autor: Ing. Gusmary Balza
***************************************************************/			
		function cargarEventos()
		{
			var recordDefecto = new record({
				evento:'TODOS',
				
			});	
			
			var objdata ={'operacion': 'obtenerEvento'};		
			objdata=JSON.stringify(objdata);			
			parametros = 'objdata='+objdata;
			Ext.Ajax.request({
				url : ruta,
				params : parametros,
				method: 'POST',
				success: function (resultad,request)
				{
					datos = resultad.responseText;
					var datajson = eval('(' + datos + ')');
					if (datajson.raiz!=null)
					{
						dsevento.loadData(datajson);
						dsevento.insert(0,recordDefecto);
						Ext.getCmp('cmbevento').setValue('TODOS');
					}
				},
				failure: function ( result, request)
				{ 
					Ext.MessageBox.alert('Error', result.responseText); 
				},
				
			});	
		}
		
		//componentes del formulario
		Xpos = ((screen.width/2)-(450/2)); 
		Ypos = ((screen.height/2)-(550/2));
		panel = new Ext.FormPanel({
			title: 'Reporte de Auditoría',
			bodyStyle:'padding:5px 5px 0px',
			style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
			tbar: [],
			items:[{
				xtype:'fieldset',
				title:'Tipo de Busqueda',
				id:'fsbusqueda',
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',		
				items:[{  
					xtype:'textfield',
					fieldLabel:'Usuario',
					name:'codigo del usuario',
					readOnly:true,
					id:'txtcodusuario',
					width:150
				},{
					xtype:'button',
					id:'btnBuscarUsuario',
					handler: irBuscarUsuario,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar un usuario',
					style:'position:absolute;left:275px;top:28px',
					width:50
				},{
					xtype:'textfield',
					fieldLabel:'Grupo',
					name:'codigo del grupo',
					readOnly:true,
					id:'txtcodgrupo',
					width:50
				},{
					xtype:'button',
					id:'btnBuscarGrupo',
					handler: irBuscarGrupo,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar un grupo',
					style:'position:absolute;left:175px;top:55px',
					width:50
				},{
					xtype:'textfield',
					name:'nombre del grupo',
					id:'hidgrupo',
					disabled:true,
					hideLabel:true,
					style:'position:absolute;left:190px;top:-25px;border:none',
					width:200
				},{
					xtype:'combo',
					fieldLabel:'Sistema',
					readOnly:true,
					name:'sistema',
					id:'cmbsistema',
					emptyText:'Seleccione',
					displayField:'nombre',
					valueField:'codsistema',
					typeAhead: true,
					mode: 'local',
					triggerAction: 'all',
					store: dssistema,
					width:300	
				},{
					xtype:'combo',
					fieldLabel:'Evento',
					readOnly:true,
					name:'evento',
					id:'cmbevento',
					emptyText:'Seleccione',
					displayField:'evento',
					valueField:'evento',
					typeAhead: true,
					mode: 'local',
					triggerAction: 'all',
					store: dsevento,
					width:90			
				}]
			},{
				xtype:'fieldset',
				title:'Periodo',
				id:'fsorden',
				autoHeight:true,
				autoWidth:true,
				style:'background:#F9F9EE',
				items:[{
					xtype:'datefield',
					fieldLabel:'Día a consultar',
					name:'fecha',
					id:'fecha',
					format:'d/m/Y',
					readOnly:true
				}]
			}]
		});
		panel.render(document.body);
		
		Ext.getCmp('btnBuscarUsuario').addListener('click',deshabilitarGrupo);
		Ext.getCmp('btnBuscarGrupo').addListener('click',deshabilitarUsuario);
		
}); //fin

/****************************************************
* @Función para deshabilitar las opciones del grupo
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
****************************************************/				
		function deshabilitarGrupo()
		{
			Ext.getCmp('btnBuscarGrupo').disable();
		}
		
		
/*****************************************************
* @Función para deshabilitar las opciones del usuario
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
*****************************************************/	
		function deshabilitarUsuario()
		{
			Ext.getCmp('btnBuscarUsuario').disable();
		}


/*****************************************************************
* @Función para buscar en el catalogo el usuario seleccionado.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
******************************************************************/	
		function irBuscarUsuario()
		{
			var arreglotxt = new Array('txtcodusuario');		
			var arreglovalores = new Array('codusuario','nombre','apellido');				
			objCatusuario = new catalogoUsuario();
			objCatusuario.mostrarCatalogo(panel,'fsbusqueda',arreglotxt, arreglovalores);
		}
		

/*************************************************************
* @Función para buscar en el catalogo el grupo seleccionado.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
************************************************************/		
		function irBuscarGrupo()
		{
			var arreglotxt = new Array('txtcodgrupo','hidgrupo');		
			var arreglovalores = new Array('codgrupo','nombre');	
			objCatGrupo = new catalogoGrupo();
			objCatGrupo.mostrarCatalogo(panel,'fsbusqueda',arreglotxt, arreglovalores);
		}


/**************************************
* @Función para limpiar los campos
* @fecha de creación: 28/08/2008
* @autor: Ing. Gusmary Balza.
**************************************/			
		function irCancelar()
		{
			Ext.getCmp('txtcodusuario').setValue('');
			Ext.getCmp('txtcodgrupo').setValue('');
			Ext.getCmp('hidgrupo').setValue('');
			Ext.getCmp('cmbsistema').setValue('TODOS');
			Ext.getCmp('cmbevento').setValue('TODOS');
			Ext.getCmp('fecha').setValue('');
			Ext.getCmp('btnBuscarGrupo').enable();
			Ext.getCmp('btnBuscarUsuario').enable();
		}
	
	
/******************************************
* @Función para mostrar el reporte 
* @de los registros de eventos en los sistemas
* @por usuario o grupo.
* @fecha de creación: 28/08/2008
* @autor: Ing. Gusmary Balza.
*****************************************/		
	function irImprimir()
	{
		continuar = false;
	//	if (validarObjetos('txtcodusuario','20','novacio')=='0' && validarObjetos('txtcodgrupo','5','novacio')=='0')
		if ((Ext.getCmp('txtcodusuario').getValue()=='') && (Ext.getCmp('txtcodgrupo').getValue()==''))
		{
			Ext.MessageBox.alert('Mensaje','Seleccione el usuario o grupo');
		}
		else
		{
			if (Ext.getCmp('fecha').getValue()=='')
			{
				Ext.MessageBox.alert('Mensaje','Seleccione un dia para imprimir');
			}
			else
			{
				if (Ext.getCmp('txtcodusuario').getValue()=='')
				{
					codusuario = '--------------------';
					fecha = Ext.get('fecha').getValue();
					if (Ext.getCmp('cmbevento').getValue()=='TODOS' /*|| Ext.getCmp('cmbevento').getValue()=='Seleccione'*/)
				//	if (Ext.getCmp('cmbevento').getValue()=='Seleccione')
					{
						evento = '';
					}
					else
					{
						evento = Ext.getCmp('cmbevento').getValue();	
					}
					if (Ext.getCmp('cmbsistema').getValue()=='TODOS')
					{
						sistema = '';
					}
					else
					{
						sistema = Ext.getCmp('cmbsistema').getValue();	
					}
					continuar = true;
					var objdata ={
					'operacion': 'auditoria',
					'codusuario': codusuario,
					'codgrupo':   Ext.getCmp('txtcodgrupo').getValue(),
					'codsistema': Ext.getCmp('cmbsistema').getValue(),
					'evento':     evento,
					'fecha': fecha,
					'sistema': sistema,
					'vista': vista
					}
				}
				else
				{
					if (Ext.getCmp('cmbevento').getValue()=='TODOS')
					{
						evento = '';
					}
					else
					{
						evento = Ext.getCmp('cmbevento').getValue();	
					}
					if (Ext.getCmp('cmbsistema').getValue()=='TODOS')
					{
						sistema = '';
					}
					else
					{
						sistema = Ext.getCmp('cmbsistema').getValue();	
					}
					codgrupo = '-----';
					fecha = Ext.get('fecha').getValue();
					continuar = true;
					var objdata ={
						'operacion': 'auditoria',
						'codusuario': Ext.getCmp('txtcodusuario').getValue(),
						'codgrupo':   codgrupo,
						'codsistema': sistema,
						'evento':     evento,
						'fecha': 	  Ext.get('fecha').getValue(),
						'sistema': sistema,
						'vista': vista
					}
				}
				if (continuar)
				{
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
			}
		} 
	} 
	
