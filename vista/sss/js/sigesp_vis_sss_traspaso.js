/****************************************************************************************
* @Reporte de traspaso.
* @Archivo javascript el cual contiene los componentes del reporte de traspaso.
* @versión: 1.0      
* @fecha de creación: 20/11/2008
* @autor: Ing. Gusmary Balza  
*******************************************************************
* @fecha modificacion
* @autor 
* @descripcion
****************************************************************************************/
var panel = '';
ruta =  '../../controlador/sss/sigesp_ctr_sss_reportes.php'; 
var pantalla = 'traspaso';
Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		Ext.form.Field.prototype.msgTarget = 'side';
		
		//componentes del formulario
		Xpos = ((screen.width/2)-(450/2)); 
		Ypos = ((screen.height/2)-(550/2));
		panel = new Ext.FormPanel({
			title: 'Reporte de Traspaso',
			bodyStyle:'padding:5px 5px 0px',
			style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
			width: 400,
			tbar: [],
			items:[{
				xtype:'fieldset',
				title:'Tipo de Busqueda',
				labelWidth: 150,
				id:'fsbusqueda',
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',		
				items:[{  
					xtype:'datefield',
					fieldLabel:'Desde',
					name:'fecha Desde',
					id:'fecdesde',
					format:'d/m/Y',
					readOnly:true
				},{
					xtype:'datefield',
					fieldLabel:'Hasta',
					name:'fecha Hasta',
					id:'fechasta',
					format:'d/m/Y',
					readOnly:true	
				},{
					xtype:'textfield',
					fieldLabel:'Base de Datos Destino',
					name:'Base de Datos Destino',
					readOnly:true,
					id:'txtbddestino',
					width:150	
				},{
					xtype:'button',
					id:'btnBuscarBd',
					handler: irBuscarBd,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar base de datos destino',
					style:'position:absolute;left:325px;top:80px',
					width:50	
				}]						
				
			}]
		});
		panel.render(document.body);
						
}); //fin


/**************************************************************************************
* @Función para buscar la base de datos destino.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
*******************************************************************
* @fecha modificacion
* @autor 
* @descripcion
**************************************************************************************/	
	function irBuscarBd()
	{
		var arreglotxt = new Array('txtbddestino');		
		var arreglovalores = new Array('codbasedatos');				
		objCatBd = new catalogoBd();
		objCatBd.mostrarCatalogoBd(arreglotxt, arreglovalores);
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
		Ext.getCmp('txtbddestino').setValue('');
		Ext.getCmp('fecdesde').setValue('');
		Ext.getCmp('fechasta').setValue('');
	}

	
/***************************************************************************************
* @Función para mostrar el reporte de los traspasos de usuarios
* @por usuario o grupo.
* @fecha de creación: 19/11/2008
* @autor: Ing. Gusmary Balza.
*******************************************************************
* @fecha modificacion
* @autor 
* @descripcion
***************************************************************************************/		
	function irImprimir()
	{
		continuar = false;		
		if (validarObjetos('txtbddestino','80','novacio')!='0')
		{
			continuar = true;
					
			if ((validarObjetos('fecdesde','80','novacio')!='0' && validarObjetos('fechasta','80','novacio')!='0'))
			{
					var objdata ={
						'oper': 	'traspaso',
						'bddestino': 	Ext.getCmp('txtbddestino').getValue(),
						'fecdesde': 	Ext.get('fecdesde').getValue(),
						'fechasta':	Ext.get('fechasta').getValue(),
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
	} 	
