/** 	
*	
* @Menu para el Módulo de Configuación y Definiciones
* @versión: 1.0      
* @modificado: 19/05/2008
* @autor: Ing. Yesenia de Lang 
*
*/

Ext.onReady
(
	function()
	{
    	Ext.QuickTips.init();
		
		// Menú para las Definiciones
		
		var menuconfiguracion = new Ext.menu.Menu
		(
			{
				id: 'conMenu',
			}
		);
		var itenEstructura = new  Ext.menu.Item(
		{
			text: 'Configuración de niveles',
			id:'1',
           	iconCls: 'blist',  
			handler: llamarPantalla
		});
		menuconfiguracion.addItem(itenEstructura); 
		var itenPlanCuentas = new  Ext.menu.Item(
		{
			text: 'Plan de Cuentas',
			id:'pla1',
           	iconCls: 'blist',  
			handler: llamarPantalla
		});
		menuconfiguracion.addItem(itenPlanCuentas); 
		
		var itemtransdatos = new  Ext.menu.Item(
		{
			text: 'Transferencia de Datos',
			id:'tradat',
           	iconCls: 'blist',  
			handler: llamarPantalla
		});
		menuconfiguracion.addItem(itemtransdatos); 
		
				
		
		var menudefiniciones = new Ext.menu.Menu(
			{
				id: 'mainMenu',
			});
		// Opción del Menu de Definiciones
	/*	var itenfuente = new  Ext.menu.Item(
			{
				text: 'Fuente de Financiamiento',
           		iconCls: 'blist',  
           		id:'0',
				handler: llamarPantalla
			});
		menudefiniciones.addItem(itenfuente); */
		// Opción del Menu de Definiciones
	
		var itemDefPlan = new  Ext.menu.Item(
			{
				text: 'Estructura del Plan',
				id:'2',
           		iconCls: 'blist',  
				handler: llamarPantalla
			});
		menudefiniciones.addItem(itemDefPlan); 
		// Opción del Menu de Definiciones
		var itemDefEst = new  Ext.menu.Item(
			{
				text: 'Estructura Presupuestaria',
				id:'3',
           		iconCls: 'blist',  
				handler: llamarPantalla
			});
		menudefiniciones.addItem(itemDefEst); 
			var itemOrganoE = new  Ext.menu.Item(
			{
				text: 'Estructura Administrativa',
				id:'30',
           		iconCls: 'blist',  
				handler: llamarPantalla
			});
		menudefiniciones.addItem(itemOrganoE); 
			var itemOrganoE = new  Ext.menu.Item(
			{
				text: 'Ubicación Geográfica',
				id:'7',
           		iconCls: 'blist',  
				handler: llamarPantalla
			});
	
		menudefiniciones.addItem(itemOrganoE); 
		var integracion = new  Ext.menu.Item(
		{
			text: 'Integración de Estructuras',
			id:'98',
           	iconCls: 'blist',  
			handler: llamarPantalla
		});
		menudefiniciones.addItem(integracion); 		
		
		var submenuindi = new Ext.menu.Menu
		(
			{
				text:'ssss',
				id: 'indiMenu',
			}
		);
		
		
		
		var itemTipoOr = new  Ext.menu.Item(
			{
				text: 'Unidad de Medida',
				id:'4',
           		iconCls: 'blist',  
				handler: llamarPantalla
			});
		submenuindi.addItem(itemTipoOr); 
		// Opción del Menu de Definiciones
		var itemmeta  = new  Ext.menu.Item
			(
			{
				text: 'Metas',
				id:'11',
           		iconCls: 'blist',  
				handler: llamarPantalla
			});
		submenuindi.addItem(itemmeta); 
		var itemtipoindi  = new  Ext.menu.Item
			(
			{
				text: 'Tipo de Indicador',
				id:'70',
           		iconCls: 'blist',  
				handler: llamarPantalla
			});
		submenuindi.addItem(itemtipoindi); 
		var Indicador  = new  Ext.menu.Item
			(
			{
				text: 'Indicador de Gestión',
				id:'71',
           		iconCls: 'blist',  
				handler: llamarPantalla
			});
		submenuindi.addItem(Indicador); 
		
		menudefiniciones.add(
		{
			text: 'Indicadores de gestión',
			iconCls: 'bmenu',  
			menu:submenuindi
		});

		var itemOrganoE = new  Ext.menu.Item(
			{
				text: 'Ubicación Geográfica',
				id:'7',
           		iconCls: 'blist',  
				handler: llamarPantalla
			});
		menudefiniciones.addItem(itemOrganoE); 
	/*	var itemOrganoE = new  Ext.menu.Item
			(
			{
				text: 'Problemas',
				id:'10',
           			iconCls: 'blist',  
				handler: llamarPantalla
			});
		menudefiniciones.addItem(itemOrganoE); */
	
		//submenú de cuentas
		var menucuentas = new Ext.menu.Menu
		(
			{
				id: 'cuenMenu',
			}
		);
		var itencaif = new  Ext.menu.Item(
		{
			text: 'Catálogo CAIF',
			id:'cai',
           	iconCls: 'blist',  
			handler: llamarPantalla
		});
		menucuentas.addItem(itencaif); 	
		menudefiniciones.addItem(menucuentas);	
			
			
		// Menú para Regresar al Menu Principal
		var menuvolver = new Ext.menu.Menu(
			{
				id: 'mainMenu'
			});
		// Opción del Menu de Volver
		var itemmenumodulo = new  Ext.menu.Item(
			{
				text: 'Modulos',
				id:'modulos',
           			iconCls: 'blist',  
				handler: irMenuPrincipal
			});
			var itemmenuprincipal = new  Ext.menu.Item(
			{
				text: 'Menu Principal',
           			iconCls: 'blist',  
				id:'menuprin',
				handler: irMenuPrincipal
			});
			var itemmenuempresa = new  Ext.menu.Item(
			{
				text: 'Menu de Empresas',
           			iconCls: 'blist',  
				id:'menuemp',
				handler: irMenuPrincipal
			});
		menuvolver.addItem(itemmenuprincipal); 
		menuvolver.addItem(itemmenuempresa);
		menuvolver.addItem(itemmenumodulo); 
		
			var menuprocesos = new Ext.menu.Menu(
			{
				id: 'procMenu',
			});
		// Opción del Menu de Definiciones
		var iteninteproPlan = new  Ext.menu.Item(
			{
				text: 'Programación de Recursos',
           		iconCls: 'blist',  
           		id:'48',
				handler: llamarPantalla
			});
		menuprocesos.addItem(iteninteproPlan); 
		var itenintepro = new  Ext.menu.Item(
			{
				text: 'Programación de Gastos y Aplicaciones',
           		iconCls: 'blist',  
           		id:'20',
				handler: llamarPantalla
			});
		menuprocesos.addItem(itenintepro); 
		var cargabalance = new  Ext.menu.Item(
		{
				text: 'Carga de Saldos del Balance General',
           		iconCls: 'blist',  
           		id:'49',
				handler: llamarPantalla
		});
		menuprocesos.addItem(cargabalance); 
		// Opción del Menu de Definiciones
	var cargasaldos = new  Ext.menu.Item(
	{
			text: 'Carga de montos de año real y último estimado ',
           	iconCls: 'blist',  
           	id:'68',
			handler: llamarPantalla
	});
	menuprocesos.addItem(cargasaldos); 
	
	var menureportes = new Ext.menu.Menu(
			{
				id: 'mainMenu',
			});
		// Opción del Menu de Procesos
		
		var repforminstructivo = new  Ext.menu.Item
		({
				text: 'Formatos del Instructivo',
				id:'28',
           		iconCls: 'blist',  
				handler: llamarPantalla
		});
		menureportes.addItem(repforminstructivo); 
		 
		 
		 
		var repdesplan = new  Ext.menu.Item(
			{
				text: 'Formatos del POA',
           		iconCls: 'blist',  
           		id:'21',
				handler: llamarPantalla
			});
		menureportes.addItem(repdesplan); 
		// Opción del Menu de Definiciones
		/*
		var repdetfin = new  Ext.menu.Item(
			{
				text: 'Detalle de Financiamiento',
				id:'22',
           		iconCls: 'blist',  
				handler: llamarPantalla
			});
		menureportes.addItem(repdetfin); 
		
			var repinvarea = new  Ext.menu.Item(
			{
				text: 'Inversión por área estratégica',
           		iconCls: 'blist',  
           		id:'23',
				handler: llamarPantalla
			});
		menureportes.addItem(repinvarea); 
		// Opción del Menu de Definiciones
		var reprobenf = new  Ext.menu.Item(
			{
				text: 'Problematica a Enfrentar',
				id:'24',
           		iconCls: 'blist',  
				handler: llamarPantalla
			});
		menureportes.addItem(reprobenf); 

		var planfin = new  Ext.menu.Item(
			{
				text: 'Plan de Financiamiento',
				id:'25',
           		iconCls: 'blist',  
				handler: llamarPantalla
			});
		menureportes.addItem(planfin); 
		var repdistprogfin = new  Ext.menu.Item
		({
				text: 'Distribución Programática y financiera del Plan',
				id:'26',
           		iconCls: 'blist',  
				handler: llamarPantalla
		});
		menureportes.addItem(repdistprogfin); 
		var represuminv = new  Ext.menu.Item
		({
				text: 'Resumen de Inversión',
				id:'27',
           		iconCls: 'blist',  
				handler: llamarPantalla
		});
		menureportes.addItem(represuminv);
	*/
		
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
            text:'Configuración',
            iconCls: 'bmenu',  // <-- icon
            menu: menuconfiguracion // assign menu by instance
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
					location.href="sigesp_spe_estprog.php";
					break
				case '3':
					location.href="sigesp_sfp_estprog.php";
					break
				case '4':
					//location.href="sigesp_sfp_tipo_organismo.php";
					location.href="sigesp_sfp_unimedida.php";
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
					location.href="sigesp_spe_reportespoa.php";
					break;	
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
					location.href="sigesp_spe_planfinan.php";
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
				case '70':
					location.href='sigesp_sfp_tipoindi.php';
					break;			
				case '71':
					location.href='sigesp_sfp_indicador.php';
					break;
				case 'tradat':
					location.href='sigesp_traspaso_form.php';
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
				parent.location.href="sigesp_windowblank2.php";
			break;
			case 'menuemp':
				location.href='sigesp_windowblank.php';
			break;	
			}
			
		}
	}
);