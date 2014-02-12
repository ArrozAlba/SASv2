/****************************************************************************************
* @Reporte de auditoria.
* @Archivo javascript el cual contiene los componentes del reporte de auditoria.
* @versión: 1.0      
* @fecha de creación: 28/08/2008
* @autor: Ing. Gusmary Balza  
*******************************************************************
* @fecha modificacion
* @autor 
* @descripcion
****************************************************************************************/
var panel = '';
ruta =  '../../controlador/sss/sigesp_ctr_sss_reportes.php'; 
var pantalla = 'auditoria';
recordDefecto = '';
Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		Ext.form.Field.prototype.msgTarget = 'side';
		//cargar los datos de los sistemas para asociarlos al combo.
		var datosSistema = {'raiz':[{'codsis':'PRB','nombre':'Prueba'}]};
		
			recordSistema = Ext.data.Record.create([
				{name: 'codsis'},     
				{name: 'nomsis'}
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
		var datosEvento = {'raiz':[{'evento':'evento','deseve':'descripcion'}]};
		
			record = Ext.data.Record.create([
				{name: 'evento'},     
				{name: 'deseve'}
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

		//cargar los datos de los eventos para asociarlos al combo.
		var datosTipoEve = {'raiz':[{'tipo':'Éxito'},{'tipo':'Falla'}]};
		
			record = Ext.data.Record.create([
				{name: 'tipo'}				
			]);					
			dstipoeve =  new Ext.data.Store({
					proxy: new Ext.data.MemoryProxy(datosTipoEve),
					reader: new Ext.data.JsonReader(
					{
						root: 'raiz',               
						id: 'id'   
					},
					record
					),
					data: datosTipoEve			
				 });
			

/**************************************************************************************
* @Función para cargar en el combo los nombres de los sistemas.
* @fecha de creación:19/08/2008
* @autor: Ing. Gusmary Balza
*******************************************************************
* @fecha modificacion
* @autor 
* @descripcion
**************************************************************************************/			
		function cargarSistemas()
		{
			var recordInicial = new recordSistema({
				nombre:'TODOS',				
			});		
					
			var objdata ={'oper': 'obtenerSistema'};		
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
						//dstipoeve.insert(0,recordInicial);
						Ext.getCmp('cmbtipoeve').setValue('TODOS');
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
		
		
/***************************************************************************************
* @Función para cargar en el combo los nombres de los eventos.
* @fecha de creación:19/08/2008
* @autor: Ing. Gusmary Balza
*******************************************************************
* @fecha modificacion
* @autor 
* @descripcion
***************************************************************************************/			
		function cargarEventos()
		{
			var recordDefecto = new record({
				evento:'TODOS',				
			});	
			
			var objdata ={'oper': 'obtenerEvento'};		
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
					xtype:'combo',
					fieldLabel:'Sistema',
					readOnly:true,
					name:'sistema',
					id:'cmbsistema',
					emptyText:'Seleccione',
					displayField:'nomsis',
					valueField:'codsis',
					typeAhead: true,
					mode: 'local',
					triggerAction: 'all',
					store: dssistema,
					width:300	
				},{	
					xtype:'combo',
					fieldLabel:'Tipo de Evento',
					readOnly:true,
					name:'tipo',
					id:'cmbtipoeve',
					emptyText:'Seleccione',
					displayField:'tipo',
					valueField:'tipo',
					typeAhead: true,
					mode: 'local',
					triggerAction: 'all',
					store: dstipoeve,
					width:100	
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
					width:100			
				}]
			},{
				xtype:'fieldset',
				title:'Periodo',
				id:'fsorden',
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',	
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
						
}); //fin


/**************************************************************************************
* @Función para buscar en el catalogo el usuario seleccionado.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
*******************************************************************
* @fecha modificacion
* @autor 
* @descripcion
**************************************************************************************/	
	function irBuscarUsuario()
	{
		var arreglotxt = new Array('txtcodusuario');		
		var arreglovalores = new Array('codusu','nomusu','apeusu');				
		objCatusuario = new catalogoUsuario();
		objCatusuario.mostrarCatalogo(panel,'fsbusqueda',arreglotxt, arreglovalores);
	}
		

/**************************************************************************************
* @Función para limpiar los campos
* @fecha de creación: 28/08/2008
* @autor: Ing. Gusmary Balza.
*******************************************************************
* @fecha modificacion
* @autor 
* @descripcion
**************************************************************************************/			
	function irCancelar()
	{
		Ext.getCmp('txtcodusuario').setValue('');
		Ext.getCmp('cmbsistema').setValue('TODOS');
		Ext.getCmp('cmbtipoeve').setValue('TODOS');
		Ext.getCmp('cmbevento').setValue('TODOS');
		Ext.getCmp('fecha').setValue('');
		Ext.getCmp('btnBuscarUsuario').enable();
	}

	
/***************************************************************************************
* @Función para mostrar el reporte de los registros de eventos en los sistemas
* @por usuario o grupo.
* @fecha de creación: 28/08/2008
* @autor: Ing. Gusmary Balza.
*******************************************************************
* @fecha modificacion
* @autor 
* @descripcion
***************************************************************************************/		
	function irImprimir()
	{
		continuar = false;		
		if (Ext.getCmp('fecha').getValue()=='')
		{
			Ext.MessageBox.alert('Mensaje','Seleccione un dia para imprimir');
		}
		else
		{		
			continuar = true;
			if (Ext.getCmp('cmbtipoeve').getValue()=='Éxito')
			{
				tipoeve = 'exito';
			}
			else if (Ext.getCmp('cmbtipoeve').getValue()=='Falla')
			{
				tipoeve = 'falla';
			}
			else
			{
				tipoeve = 'todostipo';
			}
				
			if (Ext.getCmp('txtcodusuario').getValue()=='')
			{
				fecha = Ext.get('fecha').getValue();
				if (Ext.getCmp('cmbevento').getValue()=='TODOS' /*|| Ext.getCmp('cmbevento').getValue()=='Seleccione'*/)
				{
					evento = '';
				}
				else
				{
					evento = Ext.getCmp('cmbevento').getValue();
				}
				if (Ext.getCmp('cmbsistema').getValue()=='TODOS')
				{
					codsis = '';
				}
				else
				{
					codsis = Ext.getCmp('cmbsistema').getValue();							
				}
				var objdata ={
					'oper': 	'auditoria',
					'codsis': 	codsis,
					'evento': 	evento,
					'fecha': 	Ext.get('fecha').getValue(),
					'tipoeve': 	tipoeve,
					'busqueda':	'todos',
					'sistema':	sistema,
					'vista': 	vista	
				}
			}
			else if (Ext.getCmp('cmbevento').getValue()=='TODOS')
			{
				evento = '';										
			}
			else
			{
				evento = Ext.getCmp('cmbevento').getValue();
			}
			if (Ext.getCmp('cmbsistema').getValue()=='TODOS')
			{
				codsis = '';						
			}
			else
			{
				codsis = Ext.getCmp('cmbsistema').getValue();						
			}
			var objdata ={
				'oper': 	'auditoria',
				'codusu': 	Ext.getCmp('txtcodusuario').getValue(),
				'codsis': 	codsis,
				'evento':	evento,
				'tipoeve': 	tipoeve,
				'fecha': 	Ext.get('fecha').getValue(),
				'busqueda': 'usuario',
				'sistema': 	sistema,
				'vista': 	vista
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
					Ext.MessageBox.alert('Error', 'No se pudo procesar la información'); 
				} 
			});			
			}
		} 
	} 	
