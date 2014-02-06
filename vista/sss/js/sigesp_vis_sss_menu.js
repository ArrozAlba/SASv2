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
		rutaarchivo ='../../controlador/sss/sigesp_ctr_sss_menu.php';
    	Ext.QuickTips.init();
		// Tool Bar que va a obtener las Opciones de Menu
		var menuprincipal = new Ext.Toolbar();
		menuprincipal.render('toolbar');
		divgrid = document.getElementById('toolbar');
		var objmenu ={
			'operacion': 'menu', 
			'codsis': sistema
		};		
		objmenu=JSON.stringify(objmenu);
		parametros = 'objdata='+objmenu; 
		Ext.Ajax.request({
		url : rutaarchivo,
		params : parametros,
		method: 'POST',
		success: function (resultad, request)
		{ 			
			obj   = eval('('+resultad.responseText+')');
			total = obj.raiz.length;
			// Generar el menu de manera dinamica
			for (menu1=0; menu1<total; menu1++) 
			{
				if (obj.raiz[menu1].nivel==1)  // Busco los nodos Principales
				{
					// Menu Principal Nivel 1
					var menunivel1 = new Ext.menu.Menu( 
						{
							id: 'mainMenu'
					});
					codpadre1 = obj.raiz[menu1].codmenu; 
					nivel1    = obj.raiz[menu1].nivel;
					for (menu2=0; menu2<total; menu2++) // Recorro todos los nodos para buscar los hijos del nivel 1
					{
						if (obj.raiz[menu2].codpadre==codpadre1) // Verifico que el padre del nodo sea igual al del nivel superior
						{
							if (obj.raiz[menu2].hijo==1) // si el nodo es padre busco sus hijos
							{
								codpadre2 = obj.raiz[menu2].codmenu;
								// Menu nivel 2
								var menunivel2 = new Ext.menu.Menu(
									{
										id: 'mainMenu2'
									});
								for (menu3=0; menu3<total; menu3++) //Recorro todos los nodos para buscar los hijos del nivel2
								{
									if (obj.raiz[menu3].codpadre==codpadre2)// Verifico que el padre del nodo sea igual al del nivel superior
									{
										if (obj.raiz[menu3].hijo==1) // si el nodo es padre busco sus hijos
										{
											codpadre3 = obj.raiz[menu3].codmenu;
											// Menu nivel 3
											var menunivel3 = new Ext.menu.Menu(
												{
													id: 'mainMenu3'
												});
											for (menu4=0; menu4<total; menu4++) // Recorro todos los nodos para buscar los hijos del nivel 3
											{
												if (obj.raiz[menu4].codpadre==codpadre3)// Verifico que el padre del nodo sea igual al del nivel superior
												{
													if (obj.raiz[menu4].hijo==1) // si el nodo es padre busco sus hijos
													{
														codpadre4 = obj.raiz[menu4].codmenu;
														// Menu nivel 4
														var menunivel4 = new Ext.menu.Menu(
															{
																id: 'mainMenu4'
															});
														for (menu5=0; menu5<total; menu5++)// Recorro todos los nodos para buscar los hijos del nivel 5
														{
															if (obj.raiz[menu5].codpadre==codpadre4)// Verifico que el padre del nodo sea igual al del nivel superior
															{
																// es un item final
																var nodofinal = new  Ext.menu.Item(
																	{
																		text: obj.raiz[menu5].nomlogico,
																		iconCls: 'blist',
																		hrefTarget: obj.raiz[menu5].marco,
																		href: obj.raiz[menu5].nomfisico
																	});
																// Agrego el item al menu Nivel 4
																menunivel4.addItem(nodofinal); 
															}
														}
														// Agrego el Submenu 4 al menu nivel 3
														menunivel3.addSeparator();
														menunivel3.add(
														{
															text: obj.raiz[menu4].nomlogico,
															iconCls: 'bmenu',  
															menu: menunivel4
														});
													}
													else
													{	// es un item final
														var nodofinal = new  Ext.menu.Item(
															{
																text: obj.raiz[menu4].nomlogico,
																iconCls: 'blist',
																hrefTarget: obj.raiz[menu4].marco,
																href: obj.raiz[menu4].nomfisico
															});
														// Agrego el item al menu nivel 3
														menunivel3.addItem(nodofinal); 
													}
												}
											}
											// Agrego el submenu 3 al menu nivel 2
											menunivel2.addSeparator();
											menunivel2.add(
											{
												text: obj.raiz[menu3].nomlogico,
												iconCls: 'bmenu',  
												menu: menunivel3
											});
										}
										else
										{
											// es un item final
											var nodofinal = new  Ext.menu.Item(
												{
													text: obj.raiz[menu3].nomlogico,
													iconCls: 'blist',
													hrefTarget: obj.raiz[menu3].marco,
													href: obj.raiz[menu3].nomfisico
												});
											// Agrego al menu el item 
											menunivel2.addItem(nodofinal); 
										}
									}
								}
								// Agrego el submenu2 al Menu nivel 1
								menunivel1.addSeparator();
								menunivel1.add(
								{
									text: obj.raiz[menu2].nomlogico,
									iconCls: 'bmenu',  
									menu: menunivel2
								});
							}
							else
							{	// es un item final
								var nodofinal = new  Ext.menu.Item(
									{
										text: obj.raiz[menu2].nomlogico,
										iconCls: 'blist',
										hrefTarget: obj.raiz[menu2].marco,
										href: obj.raiz[menu2].nomfisico
									});
								// agrego el item al menu nivel 1
								menunivel1.addItem(nodofinal); 
							}
						}
					}
					// Agrego a la barra de herramientas el menu nivel 1
					menuprincipal.add(
					{
						text: obj.raiz[menu1].nomlogico,
						iconCls: 'bmenu',  
						menu: menunivel1  
					});
				}
			}
			var menunivel1 = new Ext.menu.Menu( 
				{
					id: 'mainMenu'
			});
			var nodofinal = new  Ext.menu.Item(
				{
					text: 'Volver',
					iconCls: 'blist',
					hrefTarget: '_parent',
					href: '../../escritorio.html'
				});
			// agrego el item al menu nivel 1
			menunivel1.addItem(nodofinal); 
			menuprincipal.add(
			{
				text: 'Menu Principal',
				iconCls: 'bmenu',  
				menu: menunivel1  
			});
		},
		failure: function (result,request) 
		{ 
			Ext.MessageBox.alert('Error', request); 
		}
		});
	}
);
