/***********************************************************************************
* @Javascript para el manejo del Menu en todos los sistemas
* @fecha de creación: 19/05/2008
* @autor: Ing. Yesenia de Lang 
* **************************
* @fecha modificacion 
* @autor  
* @descripcion  
***********************************************************************************/
Ext.onReady
(
	function()
	{
    	Ext.QuickTips.init();
		// Tool Bar que va a obtener las Opciones de Menu
		var menuprincipal = new Ext.Toolbar();
		menuprincipal.render('toolbar');
		divgrid = document.getElementById('toolbar');
		var menunivel1 = new Ext.menu.Menu( 
			{
				id: 'mainMenu'
		});
		var nodofinal = new  Ext.menu.Item(
			{
				text: 'Traspaso Datos Básicos',
				iconCls: 'blist',
				hrefTarget: 'principal',
				href: 'sigesp_vis_apr_traspaso_datos_basicos.html'
			});
		// agrego el item al menu nivel 1
		menunivel1.addItem(nodofinal); 
		
		var nodofinal2 = new  Ext.menu.Item(
			{
				text: 'Traspaso Solicitudes',
				iconCls: 'blist',
				hrefTarget: 'principal',
				href: 'sigesp_vis_apr_traspaso_sol_cxp.html'
			});
		// agrego el item al menu nivel 1
		menunivel1.addItem(nodofinal2); 
		
		var nodofinal3 = new  Ext.menu.Item(
			{
				text: 'Traspaso Saldos y Movimientos en Tránsito',
				iconCls: 'blist',
				hrefTarget: 'principal',
				href: 'sigesp_vis_apr_traspaso_banco.html'
			});
		// agrego el item al menu nivel 1
		menunivel1.addItem(nodofinal3);
		
		var nodofinal4 = new  Ext.menu.Item(
			{
				text: 'Traspaso Movimiento Inicial de Inventario',
				iconCls: 'blist',
				hrefTarget: 'principal',
				href: 'sigesp_vis_apr_inventario.html'
			});
		// agrego el item al menu nivel 1
		menunivel1.addItem(nodofinal4);
		
		var nodofinal5 = new  Ext.menu.Item(
			{
				text: 'Apertura Ejercicio Contable',
				iconCls: 'blist',
				hrefTarget: 'principal',
				href: 'sigesp_vis_apr_apertura_ejercicio.html'
			});
		// agrego el item al menu nivel 1
		menunivel1.addItem(nodofinal5);			
		
		
		var menusubnivel = new Ext.menu.Menu( 
		{
			id: 'mainMenu'
		});
		var nodosubnivel1 = new  Ext.menu.Item(
		{
			text: 'Asociar Cuentas Contables',
			iconCls: 'blist',
			hrefTarget: 'principal',
			href: 'sigesp_vis_apr_asociar_contables.html'
		});
		menusubnivel.addItem(nodosubnivel1);
		
		var nodosubnivel2 = new  Ext.menu.Item(
		{
			text: 'Asociar Cuentas Presupuestarias',
			iconCls: 'blist',
			hrefTarget: 'principal',
			href: 'sigesp_vis_apr_asociar_presupuestarias.html'
		});
		menusubnivel.addItem(nodosubnivel2);
		
		var nodosubnivel3 = new  Ext.menu.Item(
		{
			text: 'Procesar Cuentas',
			iconCls: 'blist',
			hrefTarget: 'principal',
			href: 'sigesp_vis_apr_procesar_cuentas.html'
		});
		menusubnivel.addItem(nodosubnivel3);
		
		menunivel1.add(
		{
			text:'Asociar Cuentas',
            iconCls: 'bmenu',  // <-- icon
            menu: menusubnivel
		});
		
		var menunivel2 = new Ext.menu.Menu( 
		{
			id: 'mainMenu2'
		});		
		var nodofinal6 = new  Ext.menu.Item(
		{
			text: 'Volver',
			iconCls: 'blist',
			hrefTarget: '_parent',
			href: '../../index_modules.php'
		});
		menunivel2.addItem(nodofinal6);
		
		
		menuprincipal.add(
		{
			text: 'Procesos',
			iconCls: 'bmenu',  
			menu: menunivel1
		},{
			text: 'Volver',
			iconCls: 'bmenu',  
			menu: menunivel2
						
		});
		
		
		
		
	}
);