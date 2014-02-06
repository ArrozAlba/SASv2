/***********************************************************************************
* @Proceso para el movimiento inicial de existencias de inventario.
* @parametros: 
* @retorno:
* @fecha de creación: 15/12/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
var panel      = '';
var pantalla   = 'movinicialinventario';
var actualizar = false;
var rutaProceso  =  '../../controlador/apr/sigesp_ctr_apr_inventario.php'; 

Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		Ext.Ajax.timeout=0;
		Ext.form.Field.prototype.msgTarget = 'side';		
		
		//componentes del formulario
		Xpos = ((screen.width/2)-(450/2)); 
		Ypos = ((screen.height/2)-(450/2));
		
		var procesar = new Ext.Action(
		{
			text: 'Procesar',
			handler: irProcesar,
			iconCls: 'bmenuprocesar',
			tooltip: 'Procesar'
		});
		var descargar = new Ext.Action(
		{
			text: 'Descargar',
			handler: irDescargar,
			iconCls: 'bmenudescargar',
			tooltip: 'Descargar Archivos Generados'
		});
		
		panel = new Ext.FormPanel({
			title: 'Movimiento Inicial de Existencias de Inventario',
			bodyStyle:'padding:5px 5px 0px',
			width:400,
			tbar: [procesar,descargar],
			style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
			items:[{
				xtype:'fieldset',
				id:'fsmovinventario',				
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',
				items:[{	
								
				}]
			}]	
		})
		panel.render(document.body);
		
	}
)
	
	
/***********************************************************************************
* @Función para limpiar los campos.
* @parametros: 
* @retorno:
* @fecha de creación: 10/12/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function irCancelar()
	{
		
	}	
	
	
/***********************************************************************************
* @Función para procesar el movimiento inicial de existencias de inventario.
* @parametros: 
* @retorno:
* @fecha de creación: 15/12/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/				
	function irProcesar()
	{
		obtenerMensaje('procesar','','Transfiriendo Datos');				
			
		var objdata ={
			'operacion': 'procesarInventario', 
			'sistema': sistema,
			'vista': vista
		};	
		objdata=Ext.util.JSON.encode(objdata);	
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
		url : rutaProceso,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request ) 
		{ 
			datos = resultado.responseText;
			Ext.Msg.hide();
			alert(datos);					
			var datajson = Ext.util.JSON.decode(datos);	
			if(datajson.raiz.valido==false)			
			{
				Ext.Msg.alert('Error', datajson.raiz.mensaje);
			}
		},
        failure: function ( resultado, request)
		{ 
			Ext.Msg.hide();
			Ext.MessageBox.alert('Error', resultado.responseText); 
        }
        });   
	}


/***********************************************************************************
* @Función para Descargar los archivos generados pór el módulo de apertura
* @parametros: 
* @retorno:
* @fecha de creación: 27/10/2008
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function irDescargar()
	{
		objCatDescarga = new catalogoDescarga();
		objCatDescarga.mostrarCatalogo();
	}
		