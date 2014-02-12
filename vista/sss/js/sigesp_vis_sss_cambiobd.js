/***********************************************************************************
* @Archivo javascript para el manejo de pantalla de cambio de base de datos.
* @fecha de creación: 21/11/2008
* @autor: Ing. Gusmary Balza
*************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/

ruta  = 'controlador/sss/sigesp_ctr_sss_inicio.php';  
dsbasedatos = '';
dsempresa = '';
var panel = '';
Ext.onReady
(
	function()
	{	

		//Cargar los datos de las base de datos para asociarlos al combo.
		var datosNuevo={'raiz':[{'codbasedatos':'db','basedatos':'Espere...'}]};
		
		record = Ext.data.Record.create([
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
		
		//Cargar los datos de las empresas para asociarlos al combo. 
		var datosEmpresa={'raiz':[{'codempresa':'0001','nombre':'Espere...'}]};
		
		record = Ext.data.Record.create([
			{name: 'codempresa'},     
			{name: 'nombre'}
		]);					
		dsempresa =  new Ext.data.Store({
				proxy: new Ext.data.MemoryProxy(datosEmpresa),
				reader: new Ext.data.JsonReader(
				{
					root: 'raiz',               
					id: 'id'   
				},
				record
				),
				data: datosEmpresa			
			 });
	
		Ext.QuickTips.init();
		// turn on validation errors beside the field globally
		Ext.form.Field.prototype.msgTarget = 'side';

		Xpos=((screen.width/2)-(350/2)); 
		Ypos=((screen.height/2)-(500/2));

		//Panel con los componentes del formulario
		panel = new Ext.FormPanel({
        labelWidth: 90,
		height: 200,
		width: 320,
		title: 'Cambiar de Base de Datos',
		bodyStyle:'padding:10px 10px 0',
		style:'position:absolute;top:100px;left:100px',
		defaults: {width: 300},		   
		items:[{
				xtype:'fieldset',
				title:'Datos de la Empresa',
				id:'fsinicio',
				autoHeight:true,
				items:[{
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
					width:170
				  },{
					xtype:'combo',
					fieldLabel:'Empresa',
					name:'empresa',
					id:'cmbempresa',
					emptyText:'Seleccione',
					displayField:'nombre',
					valueField:'codemp',
					typeAhead: true,
					mode: 'local',
					triggerAction: 'all',
					store: dsempresa,
					listWidth:350, 
					width:170
				}]
			},{
			  buttons: [{
            		text: 'Aceptar',
					//style: 'position:absolute;left:70px',
					handler: irAceptar
        		
       		 	}]
			  },{
				xtype: 'label',
				text: 'SIGESP Segunda Versión',
				style: 'position:absolute;top:260px;left:90px;font-size:10px;font-family:Verdana, Arial, Helvetica, sans-serif;color:red'
			  }]
		});
		panel.render(document.body);		
				
		irBasedatos();	
		

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
			success: function (resultad,request)
			{
				datos = resultad.responseText;
				var datajson = eval('(' + datos + ')');
				if (datajson.raiz!=null)
				{
					dsbasedatos.loadData(datajson);
				}
			},
			failure: function ( result, request)
			{ 
				Ext.MessageBox.alert('Error', result.responseText); 
			}
		});	
		Ext.getCmp('cmbbasedatos').addListener('select',buscarEmpresas);
	}
	
	
/***********************************************************************************
* @Función para buscar las empresas de acuerdo a la base de datos seleccionada.   
* @parametros: 
* @retorno:
* @fecha de creación: 01/08/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function buscarEmpresas()
	{
		valorBd = Ext.getCmp('cmbbasedatos').getValue();
		Ext.getCmp('cmbempresa').setValue('');
		irEmpresa(valorBd);	
	}
	
	
/***********************************************************************************
* @Función para llenar el combo de las empresas.     
* @parametros: 
* @retorno:
* @fecha de creación: 01/08/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function irEmpresa(bd)
	{		
		var objdata ={'operacion': 'obtenerempresa','basedatos':bd};		
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
					dsempresa.loadData(datajson);
				}
			},
			failure: function ( result, request)
			{ 
				Ext.MessageBox.alert('Error', result.responseText); 
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
	function irCancelar()
	{
		Ext.getCmp('cmbbasedatos').setValue('');
		Ext.getCmp('cmbempresa').setValue('');
	}
	
		
/***********************************************************************************
* @Función para iniciar la sesión.     
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
		if ((validarObjetos('cmbbasedatos','100','novacio')!='0') && (validarObjetos('cmbempresa','100','novacio')!='0'))
		{		
			var objdata ={
				'operacion': 'cambiarbd', 
				'basedatos': Ext.getCmp('cmbbasedatos').getValue(), 
				'codempresa': Ext.getCmp('cmbempresa').getValue(), 
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
					var dataJson = eval('(' + datos + ')');
					if (dataJson.raiz.valido==true)
					{
						irCancelar();
						ancho=screen.width-50;
						alto=screen.height-50;
						Xpos=((screen.width - ancho)/2); 
						Ypos=((screen.height - alto) /2);
						window.close();
						ventana=window.open("escritorio.html" , "SIGESP C.A." , "menubar=0,toolbar=0,scrollbars=1,resizable=0,width="+ancho+",height="+alto+",left="+Xpos+",top="+Ypos+"");
					}
					else
					{
						Ext.MessageBox.alert('Mensaje',dataJson.raiz.mensaje);
					}
				},
				failure: function (result,request) 
				{ 
					Ext.MessageBox.alert('Error', 'No se pudo iniciar sesion.'); 
				}					
			});	 
		}
	}
});	//fin del function principal