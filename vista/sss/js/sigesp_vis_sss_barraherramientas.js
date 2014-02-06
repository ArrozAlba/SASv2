/***********************************************************************************
* @Barra de Herramientas Genéricas para todas las funcionalidades del sistema 
* @fecha de creación: 19/05/2008
* @autor: Ing. Yesenia de Lang 
*************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/

var tbguardar = false;
Ext.onReady
(
	function()
	{
		var rutaarchivo ='../../controlador/sss/sigesp_ctr_sss_menu.php';
    	Ext.QuickTips.init();
		// Tool Bar que va a obtener las Opciones de Menu
		var objbarraherramienta ={
			'operacion': 'barraherramienta', 
			'codsis': sistema,
			'nomfisico': vista
		};
		var objbarraherramienta = JSON.stringify(objbarraherramienta);
		var parametros = 'objdata='+objbarraherramienta; 
		Ext.Ajax.request({
		url : rutaarchivo,
		params : parametros,
		method: 'POST',
		success: function (resultado, request)
		{ 
			obj   = eval('('+resultado.responseText+')');
			total = obj.raiz.length;
			herramienta=0;
			// Generar el menu de manera dinamica
			for (menu=0; menu<total; menu++) 
			{
				herramienta=1;
				if (obj.raiz[menu].cancelar==1)
				{
					// Acción de cancelar
					var cancelar = new Ext.Action(
					{
						text: 'Cancelar',
						handler: irCancelar,
						iconCls: 'bmenucancelar',
						tooltip: 'Limpiar campos'
					});
					panel.getTopToolbar().add(cancelar);
				}
				if (obj.raiz[menu].incluir==1)
				{
					// Acción de Nuevo
					var nuevo = new Ext.Action(
					{
						text: 'Nuevo',
						handler: irNuevo,
						iconCls: 'bmenunuevo',
						tooltip: 'Crear un nuevo registro'
					});
					tbnuevo = true;
					panel.getTopToolbar().add(nuevo);
				}
				if (obj.raiz[menu].cambiar==1)
				{
					// Acción de Actualizar
					tbactualizar = true;
				}
				if (((tbnuevo==true)|| (tbactualizar==true)) && (tbguardar==false))
				{
					// Acción de Guardar
					var guardar = new Ext.Action(
					{
						text: 'Guardar',
						handler: irGuardar,
						iconCls: 'bmenuguardar',
						tooltip: 'Guardar ó Actualizar un Registro'
					});
					tbguardar = true;
					panel.getTopToolbar().add(guardar);
				}
				if (obj.raiz[menu].leer==1)
				{
					// Acción de Leer
					var buscar = new Ext.Action(
					{
						text: 'Buscar',
						handler: irBuscar,
						iconCls: 'bmenubuscar',
						tooltip: 'Buscar un registro'
					});
					panel.getTopToolbar().add(buscar);
				}
				if (obj.raiz[menu].eliminar==1)
				{
					// Acción de Eliminar
					var eliminar = new Ext.Action(
					{
						text: 'Eliminar',
						handler: irEliminar,
						iconCls: 'bmenueliminar',
						tooltip: 'Eliminar un Registro'
					});
					panel.getTopToolbar().add(eliminar);
				}
				if (obj.raiz[menu].anular==1)
				{
					// Acción de Anular
					var anular = new Ext.Action(
					{
						text: 'Anular',
						handler: irAnular,
						iconCls: 'bmenuanular',
						tooltip: 'Anular un Registro'
					});
					panel.getTopToolbar().add(anular);
				}
				if (obj.raiz[menu].ejecutar==1)
				{
					// Acción de Procesar
					var procesar = new Ext.Action(
					{
						text: 'Procesar',
						handler: irProcesar,
						iconCls: 'bmenuprocesar',
						tooltip: 'Procesar'
					});
					panel.getTopToolbar().add(procesar);
				}
				if (obj.raiz[menu].administrativo==1)
				{
					// Acción de Administrador
					tbadministrativo = true;
				}
				if (obj.raiz[menu].imprimir==1)
				{
					// Acción de Imprimir
					var imprimir = new Ext.Action(
					{
						text: 'Imprimir',
						handler: irImprimir,
						iconCls: 'bmenuimprimir',
						tooltip: 'Imprimir un Registro'
					});
					panel.getTopToolbar().add(imprimir);
				}
				if (obj.raiz[menu].descargar==1)
				{
					// Acción de Descargar
					var descargar = new Ext.Action(
					{
						text: 'Descargar',
						handler: irDescargar,
						iconCls: 'bmenudescargar',
						tooltip: 'Descargar Archivos Generados'
					});
					panel.getTopToolbar().add(descargar);
				}
				if (obj.raiz[menu].ayuda==1)
				{
					// Acción de Ayuda
					var ayuda = new Ext.Action(
					{
						text: 'Ayuda',
						handler: irAyuda,
						iconCls: 'bmenuayuda',
						tooltip: 'Ayuda sobre la funcionalidad'
					});
					panel.getTopToolbar().add(ayuda);
				}
			}
			if (herramienta == 1)
			{
				// Acción de Volver
				var volver = new Ext.Action(
				{
					text: 'Volver',
					handler: irVolver,
					iconCls: 'bmenuvolver',
					tooltip: 'Volver Menu Principal'
				});
				panel.getTopToolbar().add(volver);
			}						
		},
		failure: function (resultado,request) 
		{ 
			Ext.MessageBox.alert('Error', request); 
		}
		});
		

/***********************************************************************************
* @Función para mostrar el archivo de ayuda.
* @parametros: pagina
* @retorno: 
* @fecha de creación: 02/09/2008
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
		function irAyuda(pagina)
		{
			pagina = vista.replace('sigesp_vis_','sigesp_ayu_');
			pagina = 'ayuda/'+pagina;
			abrirVentana(pagina);
		}


/***********************************************************************************
* @Función para Volver a la página de inicio
* @parametros: 
* @retorno: 
* @fecha de creación: 12/11/2008
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
		function irVolver()
		{
			cadena = sistema.toLowerCase();
			location.href = 'sigesp_vis_'+cadena+'_inicio.html';
		}
	}
);

