/** 	
*	
* @Menu para el Módulo de Configuación y Definiciones
* @versión: 1.0      
* @modificado: 19/05/2008
* @autor: Ing. Yesenia de Lang 
*/

Ext.onReady
(
	function()
	{
    	Ext.QuickTips.init();				
		var menudefiniciones = new Ext.menu.Menu(
		{
			id: 'mainMenu',
		});
		var itemDefPlan = new  Ext.menu.Item(
			{
				text: 'Definición de Empresa',
				id:'2',
           		iconCls: 'blist',  
				handler: llamarPantalla
			});
			
		var itenPlanGen = new  Ext.menu.Item(
		{
			text: 'Definicion de Cuentas Integradas',
			id:'3',
           	iconCls: 'blist',  
			handler: llamarPantalla
		});
		 
		var itenVarPat = new  Ext.menu.Item(
		{
			text: 'Cuentas de Variación Patrimonial',
			id:'4',
           	iconCls: 'blist',  
			handler: llamarPantalla
		});
		
			
		menudefiniciones.addItem(itemDefPlan); 
		menudefiniciones.addItem(itenPlanGen); 
		menudefiniciones.addItem(itenVarPat);
		// Opción del Menu de Definiciones
		
		var menuprocesos = new Ext.menu.Menu(
		{
			id: 'procMenu',
		});
		// Opción del Menu de Definiciones
		var iteninteproPlan = new  Ext.menu.Item(
			{
				text: 'Formulación de Presupuesto',
           		iconCls: 'blist',  
           		id:'48',
				handler: llamarPantalla
			});
		menuprocesos.addItem(iteninteproPlan); 


	
	var menureportes = new Ext.menu.Menu(
	{
			id: 'mainMenu',
	});
		// Opción del Menu de Procesos
		
		
	/*	
	var repdetfin = new  Ext.menu.Item(
	{
		text: 'Consolidados por Empresa',
		id:'22',
        iconCls: 'blist',  
		handler: llamarPantalla
	});
		menureportes.addItem(repdetfin); 
		*/
		
		// Menú para Regresar al Menu Principal
		var menuvolver = new Ext.menu.Menu(
			{
				id: 'mainMenu'
			});
		// Opción del Menu de Volver
		var itemmenuprincipal = new  Ext.menu.Item(
			{
				text: 'Ir a Modulos',
				id:'modulos',
           			iconCls: 'blist',  
				handler: irMenuPrincipal
			});
			var itemmenumodulo = new  Ext.menu.Item(
			{
				text: 'Menu Principal',
           			iconCls: 'blist',  
				id:'menuprin',
				handler: irMenuPrincipal
			});
		menuvolver.addItem(itemmenuprincipal); 
		menuvolver.addItem(itemmenumodulo); 
	
		// Tool Bar que va a obtener las Opciones de Menu
		var barraherramientas = new Ext.Toolbar();
		barraherramientas.render('toolbar');
	    barraherramientas.add(
		{
            text:'Definiciones',
            iconCls: 'bmenu',  // <-- icon
            menu: menudefiniciones  // assign menu by instance
        },'-',
        {
            text:'Procesos',
            iconCls: 'bmenu',  // <-- icon
            menu: menuprocesos  // assign menu by instance
        },'-',
        {
            text:'Reportes',
            iconCls: 'bmenu',  // <-- icon
            menu: menureportes  // assign menu by instance
        },'-',
		{
            text:'Volver',
            iconCls: 'bmenu',  // <-- icon
            menu: menuvolver  // assign menu by instance
        }
		);

		// Función para ejecutar en la opción de Menú de Consultor
		
		function llamarPantalla(item)
		{
			switch(item.getId())
			{
				case '0':
					location.href="sigesp_sfp_fuentefin.php";
					break
				case '1':
					location.href="sigesp_sfp_con_estprog.php";
					break
				case '2':
					location.href="sigesp_sfp_empresa.php";
					break
				case '3':
					location.href="sigesp_spe_integraciongeneral.php";
					break
				case '4':
					//location.href="sigesp_sfp_tipo_organismo.php";
					location.href="sigesp_spe_variacion.php";
					break
				case '6':
					location.href="sigesp_spe_organos_ejecutores.php";
					break	
				case '7':
					location.href="sigesp_spe_ubgeo.php";
					break
				case '8':
					location.href="sigesp_spe_ubgeo.php";
					break
				case '9':
					location.href="sigesp_spe_medios_verificacion.php";
					break
				case '10':
					location.href="sigesp_spe_problemas.php";
					break	
				case '11':
					location.href="sigesp_spe_meta.php";
					break		
				case '20':
					location.href="sigesp_spe_formGasto.php";
					break
				case '21':
					location.href="sigesp_spe_rep_descripcion_plan.php";
					break	
				case '22':
					location.href="sigesp_spe_rep_detalle_financiamiento.php";
					break
				case '23':
					location.href="sigesp_spe_rep_inversion_area_estrategica.php";
					break	
				case '25':
					location.href="sigesp_spe_rep_plan_financiamiento.php";
					break
				case '24':
					location.href="sigesp_spe_rep_problematica_a_enfrentar.php";
					break	
				case '26':
					location.href="sigesp_spe_rep_dist_presup_financ_plan.php";
					break	
				case '27':
					location.href="sigesp_spe_rep_resumen_inversion.php";
					break	
				case '28':
					location.href="sigesp_spe_reportes.php";	
					break;	
				case '48':
					location.href="sigesp_formulacion.php";
					break;	
				case '49':
					location.href="sigesp_spe_cargasaldos.php";
					break;	
				case '30':
					location.href="sigesp_sfp_esadmin.php";
					break;	
				case 'pla1':
					location.href="sigesp_spe_conCuentas.php";
					break;
				case 'pla2':
					location.href='sigesp_spe_variacion.php';
					break;	
				case '98':
					location.href='sigesp_spe_integracion.php';
					break;	
				case '68':
					location.href='sigesp_spe_saldosant.php';
					break;			
			}
		
		}
		
		function irConsultor(item)
		{
			parent.frames["principal"].location.href="sigesp_mcd_vis_consultor.html";
		}
		
		// Función para ejecutar en la opción de Menú de Cliente
		function irCliente(item)
		{
			parent.frames["principal"].location.href="sigesp_mcd_vis_cliente.html";
		}
		
		// Función para ejecutar en la opción de Menú de Menu Principal
		function irMenuPrincipal(item)
		{
			switch(item.getId())
			{
			case 'modulos':	
			parent.location.href="../../../index_modules.php";
			break;
			case 'menuprin':	
			parent.location.href="sigesp_windowblank.php";
			break;
			}
			
		}
	}
);