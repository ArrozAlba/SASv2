/***********************************************************************************
* @Archivo javascript para el manejo de pantalla de selección de la base de datos 
* para la apertura.
* @fecha de creación: 17/10/2008
* @autor: Ing. Yesenia Moreno de Lang
*************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/

var ruta  = '../../controlador/apr/sigesp_ctr_apr_inicio.php';  
var dsbasedatos = '';
var panel = '';
var ventana;
Ext.onReady
(
	function()
	{	
		Ext.QuickTips.init();
		Ext.form.Field.prototype.msgTarget = 'side';

		//Cargar los datos de las base de datos para asociarlos al combo.
		var datosNuevo={'raiz':[{'codbasedatos':'db','basedatos':'Espere...'}]};
		
		var record = Ext.data.Record.create([
			{name: 'codbasedatos'},     
			{name: 'basedatos'}
		]);					
		dsbasedatos =  new Ext.data.Store({
				proxy: new Ext.data.MemoryProxy(datosNuevo),
				reader: new Ext.data.JsonReader(
				{
					root: 'raiz',               
					id: 'id'   
				},
				record
				),
				data: datosNuevo			
			 });		
		Xpos=((screen.width/2)-(400/2)); 
		Ypos=((screen.height/2)-(300/2));

		//Panel con los componentes del formulario
		panel = new Ext.FormPanel
		({
        	labelWidth: 75,
			height: 200,
     		title: 'Seleccionar Base de Datos Apertura',
			bodyStyle:'padding:10px 10px 0',
            region: 'center',
       		layout:'absolute',			
			style:'top:'+Ypos+'px;left:'+Xpos+'px',
			defaults: {width: 400},		   
			items:
			[{
				xtype:'fieldset',
				title:'',
				id:'fsinicio',
				autoHeight:true,
				width:430,
				items:
				[{
					xtype:'combo',
					fieldLabel:'Base de Datos',
					name:'base de datos',
					id:'cmbbasedatos',
					emptyText:'Seleccione',
					displayField:'basedatos',
					valueField:'basedatos',
					typeAhead: true,
					mode: 'local',
					triggerAction: 'all',
					store: dsbasedatos,
					labelStyle: 'width:120px',
					width:200
				 }]
			},{
				xtype: 'label',
				text: 'Recuerde ejecutar el release antes de Realizar el proceso de apertura a la Base de Datos.',
				style:'top:70px;left:20px;font-size:15px;color:red',
				width: 400
			},{
				xtype: 'label',
				text: 'Antes de Realizar cualquier proceso debe Seleccionar la base de Datos que le va a realizar la apertura. ',
				style:'top:110px;left:20px;font-size:15px;color:gray',
				width: 400
			}],
			buttons: 
			[{
            	text: 'Aceptar',
				handler: irAceptar
        	},{
            	text: 'Cancelar',
				handler: irCancelar
        	}]
		});

       ventana = new Ext.Window({
            title: 'Apertura',
            closable:false,
            width:450,
            height:300,
			modal:true,
            plain:true,
            layout: 'border',
            items: [panel]
        });
 		ventana.show(this);
		irBasedatos();
});	//fin del function principal


/***********************************************************************************
* @Función para llenar el combo de base de datos.     
* @parametros: 
* @retorno:
* @fecha de creación: 31/07/2008.
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function irBasedatos()
	{
		var objdata ={'operacion': 'obtenerbd'};
		
		objdata=JSON.stringify(objdata);
		
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function (resultado,request)
			{
				datos = resultado.responseText;
				var datajson = eval('(' + datos + ')');
				if (datajson.raiz!=null)
				{
					dsbasedatos.loadData(datajson);
				}
			},
			failure: function ( resultado, request)
			{ 
				Ext.MessageBox.alert('Error', resultado.responseText); 
			}
		});	
	}

	
/***********************************************************************************
* @Función para limpiar todos los campos.    
* @parametros: 
* @retorno:
* @fecha de creación: 01/08/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function irAceptar()
	{
		panel.load({url:'', waitMsg:'Procesando...'});
		valorBd=Ext.getCmp('cmbbasedatos').getValue();
		if(valorBd!='')
		{
			var objdata ={'operacion': 'verificarsession','basedatos':valorBd};
			
			objdata=JSON.stringify(objdata);
			
			parametros = 'objdata='+objdata; 
			Ext.Ajax.request({
				url : ruta,
				params : parametros,
				method: 'POST',
				success: function (resultado,request)
				{
					datos = resultado.responseText;
					if(datos.indexOf('{"raiz":')!=-1)
					{
						posicion=datos.indexOf('{"raiz":');
						lontitud=datos.length-posicion;
					}
					datos=datos.substr(posicion,lontitud)
					var datos = eval('(' + datos + ')');
					if (datos.raiz.valido==true)
					{
						Ext.MessageBox.alert('Mensaje', 'Base de Datos Seleccionada'); 
						ventana.close(this);				
					}
					else
					{
						Ext.MessageBox.alert('Error', datos.raiz.mensaje); 
						setTimeout('irCancelar()',3000);				
					}
				},
				failure: function ( resultado, request)
				{ 
					Ext.MessageBox.alert('Error', resultado.responseText); 
				}
			});
		}
		else
		{
			Ext.MessageBox.alert('Error', 'Debe seleccionar una Base de Datos.'); 
		}	
	}


/***********************************************************************************
* @Función para Cancelar la entrada al módulo de apertura
* @parametros: 
* @retorno:
* @fecha de creación: 20/10/2008.
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function irCancelar()
	{
		parent.location.target='_parent';
		parent.location.href='../../index_modules.php';
		ventana.close(this);
	}	