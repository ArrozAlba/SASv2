/**********************************************************************************
* @Proceso de asignar perfiles a usuarios o grupos.
* @Archivo javascript el cual contiene los componentes del proceso 
* @de asignar perfiles a usuarios o grupos.
* @versión: 1.0      
* @fecha de creación: 19/08/2008
* @autor: Ing. Gusmary Balza 
*********************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
***********************************************************************************/
var panel = '';
var pantalla = 'perfiles';
var cambiar = false;
var rootnode = '';
var objdata = '';
var seleccionado = '';
ruta =  '../../controlador/sss/sigesp_ctr_sss_perfiles.php'; 
Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		Ext.form.Field.prototype.msgTarget = 'side';
		//cargar los datos de los sistemas para asociarlos al combo.
		var datosNuevo = {'raiz':[{'codsis':'PRB','nomsis':'Prueba'}]};
		
			record = Ext.data.Record.create([
				{name: 'codsis'},     
				{name: 'nomsis'}
			]);					
			dssistema =  new Ext.data.Store({
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
			
		//cargar los datos de los usuarios para asociarlos al combo.
		var datosUsuario = {'raiz':[{'codusu':'','nomusu':''}]};
		
			record = Ext.data.Record.create([
				{name: 'codusu'},     
				{name: 'nomusu'}
			]);					
			dsusuario =  new Ext.data.Store({
					proxy: new Ext.data.MemoryProxy(datosUsuario),
					reader: new Ext.data.JsonReader(
					{
						root: 'raiz',               
						id: 'id'   
					},
					record
					),
					data: datosUsuario			
				 });
			
		//cargar los datos de los grupos para asociarlos al combo.
		var datosGrupo = {'raiz':[{'nomgru':'','nota':''}]};
		
			record = Ext.data.Record.create([
				{name: 'nomgru'},     
				{name: 'nota'}
			]);					
			dsgrupo =  new Ext.data.Store({
					proxy: new Ext.data.MemoryProxy(datosGrupo),
					reader: new Ext.data.JsonReader(
					{
						root: 'raiz',               
						id: 'id'   
					},
					record
					),
					data: datosGrupo			
				 });
			
			
/*********************************************************************************
* @Función para cargar en el combo los nombres de los sistemas.
* @fecha de creación:19/08/2008
* @autor: Ing. Gusmary Balza
***************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
*********************************************************************************/			
	function cargarSistemas()
	{
		var objdata ={'operacion': 'obtenerSistema', 'sistema': sistema, 'vista': vista};		
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
					cargarUsuarios();
					cargarGrupos();					
				}
			},
			failure: function ( result, request)
			{ 
				Ext.MessageBox.alert('Error', result.responseText); 
			},
			
		});	
	}
	cargarSistemas();	
	
		
/*******************************************************************************
* @Función para cargar en el combo los códigos de los usuarios.
* @fecha de creación:19/08/2008
* @autor: Ing. Gusmary Balza
***************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
*********************************************************************************/	
	function cargarUsuarios()
	{
		var objdata ={'operacion': 'obtenerUsuario', 'sistema': sistema, 'vista': vista};		
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
					dsusuario.loadData(datajson);
				}
			},
			failure: function ( result, request)
			{ 
				Ext.MessageBox.alert('Error', result.responseText); 
			},
			
		});	
		Ext.getCmp('cmbusuario').addListener('select',cambiarGrupo);
	}	


/*******************************************************************************
* @Función para cargar en el combo los nombres de los grupos.
* @fecha de creación:19/08/2008
* @autor: Ing. Gusmary Balza
****************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
*******************************************************************************/
	function cargarGrupos()
	{
		var objdata ={'operacion': 'obtenerGrupo', 'sistema': sistema, 'vista': vista};		
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
				if (datajson.raiz[0].valido==true)
				{
					dsgrupo.loadData(datajson);
				}
			},
			failure: function ( result, request)
			{ 
				Ext.MessageBox.alert('Error', result.responseText); 
			},
			
		});	
		Ext.getCmp('cmbgrupo').addListener('select',cambiarUsuario);
	}
		
		
/******************************************************************************
* @Función para deshabilitar el combo de usuario o el
* de grupo dependiendo si se elige el uno o el otro.
* @fecha de creación: 20/08/2008
* @autor: Ing.Gusmary Balza
***********************************************************************
* @fecha modificacion
* @autor 
* @descripcion
*********************************************************************************/
	function cambiarUsuario()
	{
		Ext.getCmp('cmbusuario').setValue('Seleccione');
	}
	
	function cambiarGrupo()
	{
		Ext.getCmp('cmbgrupo').setValue('Seleccione');
	}
		
		
	//nodo raiz para el menu del sistema 	
	rootnode = new Ext.tree.TreeNode({
		text:'Opciones del Menu del Sistema',
		qtip: 'Seleccione un sistema'
	});

	//componentes del formulario
	Xpos = ((screen.width/2)-(400/2)); 
	Ypos = ((screen.height/2)-(650/2));
	panel = new Ext.FormPanel({
		title: 'Aplicar Perfil a Usuario o Grupo',
		bodyStyle:'padding:5px 5px 0px',
		width:630,
		frame: true,
		style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
		tbar: [],
		items:[{
			xtype:'fieldset',
			title:'Datos del Sistema',
			id:'fsformperfil',
			autoHeight:true,
			autoWidth:true,
			cls :'fondo',		
			items:[{
				xtype:'combo',
				fieldLabel:'Sistema',
				//readOnly:true,
				name:'sistema',
				id:'cmbsistema',
				emptyText:'Seleccione',
				editable: true,
				selectOnFocus: true,
				displayField:'nomsis',
				valueField:'codsis',
				typeAhead: true,
				mode: 'local',
				triggerAction: 'all',
				store: dssistema,
				width:300		
			  },{
				xtype:'combo',
				fieldLabel:'Usuario',
				//readOnly:true,
				name:'usuario',
				id:'cmbusuario',
				emptyText:'Seleccione',
				displayField:'codusu',
				valueField:'codusu',
				typeAhead: true,
				editable:true,
				selectOnFocus: true,
				mode: 'local',
				triggerAction: 'all',
				store: dsusuario,
				width:150
			  },{
				xtype:'combo',
				fieldLabel:'Grupo',
				readOnly:true,
				name:'grupo',
				id:'cmbgrupo',
				emptyText:'Seleccione',
				displayField:'nomgru',
				valueField:'nomgru',
				typeAhead: true,
				editable:true,
				selectOnFocus: true,
				mode: 'local',
				triggerAction: 'all',
				store: dsgrupo,
				width:300				
			  }]
			},{
				xtype:'fieldset',
				title:'Opción del Menú',
				id:'fsformsistema',
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',		
				items:[{
						xtype:'textfield',
						fieldLabel:'Pantalla',
						name:'funcionalidad',
						id:'txtfuncionalidad',
						labelStyle: 'width:120px',
						width:350,
						readOnly :true
					},{
						xtype:'hidden',
						name:'menu',
						id:'hidmenu',
						itemCls : 'fondo',
						width:200
					},{
						xtype:'checkbox',
						fieldLabel:'Acceso a la pantalla',
						labelStyle: 'width:120px',
						name:'acceso',
						id:'chbacceso'
		 		},{
					xtype:'treepanel',
					title:'Funcionalidades del Sistema',
					id: 'arbolFunc',
					border: false,
					height: 400,
					width: 300,
					loader: new Ext.tree.TreeLoader(),
					rootVisible:true,
					lines:false,
					autoScroll:true,
					iconCls:'menu1',
					root: rootnode,
					style:'position:absolute;top:'+Ypos+'px;margin-left:60px',
					renderTo:'tree-div'
				}]
			},{
				xtype:'fieldset',
				title:'Opciones de Permisos',
				id:'fsformpermisos',
				layout:'column',
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',
				itemCls: 'fondo',
				// bodyStyle : 'fondo',
				items:[{			  
					columnWidth:.2,
					layout: 'form',
					labelWidth:50,
					border:false,					
					items: [{
						xtype:'checkbox',
						fieldLabel:'Todos',
						name:'todos',	
						id:'chbtodos',						
					},{
						xtype:'checkbox',
						fieldLabel:'Incluir',
						name:'incluir',
						id:'chbincluir',
					},{
						xtype:'checkbox',
						fieldLabel:'Buscar',
						name:'buscar',
						id:'chbbuscar',
					}]
				},{
					columnWidth:.2,
					layout: 'form',
					labelWidth:60,
					border:false,
					items: [{						
						xtype:'checkbox',
						fieldLabel:'Actualizar',
						name:'actualizar',
						id:'chbactualizar',
						anchor:'100%'
					 },{
						xtype:'checkbox',
						fieldLabel:'Eliminar',
						name:'eliminar',
						id:'chbeliminar'
					 },{
						xtype:'checkbox',
						fieldLabel:'Imprimir',
						name:'imprimir',
						id:'chbimprimir'
			 		}]
				},{
					columnWidth:.2,
					layout: 'form',
					labelWidth:80,
					border:false,
					items: [{	
						xtype:'checkbox',
						fieldLabel:'Ejecutar',
						name:'ejecutar',
						id:'chbejecutar'
					},{
						xtype:'checkbox',
						fieldLabel:'Anular',
						name:'anular',
						id:'chbanular'
					},{
						xtype:'checkbox',
						fieldLabel:'Administrativo',						
						name:'administrativo',
						id:'chbadministrativo'
					}]
				},{
					columnWidth:.2,
					layout: 'form',
					labelWidth:80,
					border:false,
					items: [{	
						/*xtype:'checkbox',
						fieldLabel:'Ayuda',
						name:'ayuda',
						id:'chbayuda'
					},{*/
						xtype:'checkbox',
						fieldLabel:'Cancelar',
						name:'cancelar',
						id:'chbcancelar'
					},{
						xtype:'checkbox',
						fieldLabel:'Enviar Correo',						
						name:'enviarcorreo',
						id:'chbenviarcorreo'
					},{
						xtype:'checkbox',
						fieldLabel:'Descargar',
						name:'descargar',
						id:'chbdescargar'	
					}]
			/*	},{
					columnWidth:.2,
					layout: 'form',
					labelWidth:60,
					border:false,
					items: [{	
						xtype:'checkbox',
						fieldLabel:'Descargar',
						name:'descargar',
						id:'chbdescargar'
				}]	*/				
			}]
		}]
	});
	panel.render(document.body);
	
	deshabilitarPermisos();
		
	Ext.getCmp('cmbsistema').addListener('select',cargarMenu);
	
	Ext.getCmp('chbtodos').addListener('check',seleccionarTodos);
	
	Ext.getCmp('cmbusuario').on('blur',function(){
		seleccionado = 'usuario';	
	});
	
	Ext.getCmp('cmbgrupo').on('blur',function(){
		seleccionado = 'grupo';
	});

	
/****************************************************************************** 	
* @Función para cargar las opciones de menú como 
* @ un árbol de acuerdo al sistema seleccionado.
* @parametros:
* @retorno:
* @fecha creación: 21/08/2008.
* @autor: Ing. Gusmary Balza.
*****************************************************************************
* @fecha modificacion:
* @autor:
* @descripcion
********************************************************************************/	
	function cargarMenu()
	{
		limpiarArbol();
		irCancelar();
		var objdata ={
			'operacion': 'obtenerMenu',
			'codsis': Ext.getCmp('cmbsistema').getValue(),
			'sistema': sistema, 'vista': vista
		};
		objdata = JSON.stringify(objdata);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function (resultad, request)
			{ 
				datos = resultad.responseText;
				//alert(datos);
				if (datos!='')
				{
					obj   = eval('('+datos+')');
					total = obj.raiz.length;
					// generar el árbol dinámico de funcionalidades
					for (i=0; i<total; i++) // Busco todos los nodos donde el nivel sea el maximo
					{
						if (obj.raiz[i].nivel==1)  // Buscar solo los nodos Principales 
						{
							//nodo padre del arbol
								rootnode0 = new Ext.tree.TreeNode({
								text:obj.raiz[i].nomlogico,
							});
							codpadre1 = obj.raiz[i].codmenu; //codigo
							nivel1    = obj.raiz[i].nivel;
							for (j=0; j<total; j++) // Recorrer todos los nodos para buscar los hijos del nivel 2
							{
								if (obj.raiz[j].codpadre==codpadre1) // Verificar que el padre del nodo sea igual al del nivel superior
								{
									if (obj.raiz[j].hijo==1) // si el nodo es padre buscar sus hijos
									{
										
										codpadre2     = obj.raiz[j].codmenu;
										//nodo padre del arbol
										rootnode1 = new Ext.tree.TreeNode({
											text:obj.raiz[j].nomlogico,
										});									
										for (l=0; l<total; l++) // Recorrer todos los nodos para buscar los hijos del nivel 3
										{
											if (obj.raiz[l].codpadre==codpadre2)
											{
												if (obj.raiz[l].hijo==1) // si el nodo es padre busco sus hijos
												{
													codpadre3     = obj.raiz[l].codmenu;
													//nodo padre del arbol
													rootnode2 = new Ext.tree.TreeNode({
														text:obj.raiz[l].nomlogico,
													});							
													for (k=0; k<total; k++) //Recorrer todos los nodos para buscar los hijos del nivel4
													{
														if (obj.raiz[k].codpadre==codpadre3)
														{
															if(obj.raiz[k].hijo==1) // si el nodo es padre busco sus hijos
															{
																codpadre4     = obj.raiz[k].codmenu;
																rootnode3 = new Ext.tree.TreeNode({
																	text:obj.raiz[k].nomlogico,
																});
																for (p=0; p<total; p++) // nivel 5
																{
																	if (obj.raiz[p].codpadre==codpadre4)
																	{
																		//nodo final
																		nodo = new Ext.tree.TreeNode({
																			text: obj.raiz[p].nomlogico,
																			id:obj.raiz[p].codmenu,
																			iconCls:'app_warning',
																			leaf:true,
																			listeners :{
																			click: function()
																				{
																					Ext.getCmp('txtfuncionalidad').setValue(this.text);
																					Ext.getCmp('hidmenu').setValue(this.id);
																					obtenerPermisos();
																					irBuscar();																	
																				}
																			}
												
																		});
																		rootnode3.appendChild(nodo); 																}
																}
																rootnode2.appendChild(rootnode3);
															}
															else
															{
																nodo = new Ext.tree.TreeNode({
																	text: obj.raiz[k].nomlogico,
																	id:obj.raiz[k].codmenu,
																	iconCls:'app_warning',
																	leaf:true,
																	listeners :{
																		click: function()
																		{
																			Ext.getCmp('txtfuncionalidad').setValue(this.text);
																			Ext.getCmp('hidmenu').setValue(this.id);
																			obtenerPermisos();	
																			irBuscar();																
																		}
																	}
												
																});
																rootnode2.appendChild(nodo); 
															}
														}
													}
													rootnode1.appendChild(rootnode2);
												}
												else
												{
													nodo = new Ext.tree.TreeNode({
														text: obj.raiz[l].nomlogico,
														id:obj.raiz[l].codmenu,
														iconCls:'app_warning',
														leaf:true,
														listeners :{
															click: function()
															{
																Ext.getCmp('txtfuncionalidad').setValue(this.text);
																Ext.getCmp('hidmenu').setValue(this.id);
																obtenerPermisos();	
																irBuscar();															
															}
														}
													});
													rootnode1.appendChild(nodo); 
												}
											}
										}
										rootnode0.appendChild(rootnode1);
									}
									else
									{
										nodo = new Ext.tree.TreeNode({
											text: obj.raiz[j].nomlogico,
											id:obj.raiz[j].codmenu,
											iconCls:'app_warning',
											leaf:true,
											listeners :{
											click: function()
											{
												Ext.getCmp('txtfuncionalidad').setValue(this.text);
												Ext.getCmp('hidmenu').setValue(this.id);
												obtenerPermisos();
												irBuscar();
											}
										}
										});
										rootnode0.appendChild(nodo); 
									}
								}
							}
							rootnode.appendChild(rootnode0); 
						}
					}					
					Ext.getCmp('arbolFunc').setRootNode(rootnode);
				}
				else
				{
					Ext.MessageBox.alert('Mensaje', 'El sistema no posee un menú');
					close();
				}		
			},
			failure: function (result,request) 
			{ 
				Ext.MessageBox.alert('Error', 'El menu no se pudo cargar'); 
			}					
		});
	}


}); //fin de archivo



/******************************************************************************
* @Función para seleccionar todas las opciones 
* @de los permisos al seleccionar la opción: todos.
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008.
* @autor: Ing.Gusmary Balza.
*************************************************************************
* @fecha modificacion: 21/10/2008
* @autor: Gusmary Balza
* @descripcion: seleccionar solo los campos habilitados
******************************************************************************/
	function seleccionarTodos()
	{
		if (Ext.getCmp('chbtodos').getValue()==1)
		{
			if (Ext.getCmp('chbincluir').disabled==false)
			{
				Ext.getCmp('chbincluir').setValue(1);
			}
			if (Ext.getCmp('chbbuscar').disabled==false)	
			{
				Ext.getCmp('chbbuscar').setValue(1);
			}
			if (Ext.getCmp('chbactualizar').disabled==false)	
			{
				Ext.getCmp('chbactualizar').setValue(1);
			}
			if (Ext.getCmp('chbeliminar').disabled==false)	
			{
				Ext.getCmp('chbeliminar').setValue(1);
			}		
			if (Ext.getCmp('chbimprimir').disabled==false)	
			{
				Ext.getCmp('chbimprimir').setValue(1);
			}
			if (Ext.getCmp('chbejecutar').disabled==false)	
			{
				Ext.getCmp('chbejecutar').setValue(1);
			}	
			if (Ext.getCmp('chbanular').disabled==false)	
			{
				Ext.getCmp('chbanular').setValue(1);
			}	
			if (Ext.getCmp('chbadministrativo').disabled==false)	
			{
				Ext.getCmp('chbadministrativo').setValue(1);
			}
			/*if (Ext.getCmp('chbayuda').disabled==false)	
			{
				Ext.getCmp('chbayuda').setValue(1);
			}*/
			if (Ext.getCmp('chbcancelar').disabled==false)	
			{
				Ext.getCmp('chbcancelar').setValue(1);
			}	
			if (Ext.getCmp('chbenviarcorreo').disabled==false)	
			{
				Ext.getCmp('chbenviarcorreo').setValue(1);
			}
			if (Ext.getCmp('chbdescargar').disabled==false)	
			{
				Ext.getCmp('chbdescargar').setValue(1);
			}		
		}
		else
		{
			Ext.getCmp('chbincluir').setValue(0);
			Ext.getCmp('chbbuscar').setValue(0);
			Ext.getCmp('chbactualizar').setValue(0);
			Ext.getCmp('chbeliminar').setValue(0);
			Ext.getCmp('chbimprimir').setValue(0);
			Ext.getCmp('chbejecutar').setValue(0);
			Ext.getCmp('chbanular').setValue(0);
			Ext.getCmp('chbadministrativo').setValue(0);
			//Ext.getCmp('chbayuda').setValue(0);
			Ext.getCmp('chbcancelar').setValue(0);
			Ext.getCmp('chbenviarcorreo').setValue(0);
			Ext.getCmp('chbdescargar').setValue(0);
		}
	}


/**********************************************************************************
* @Función para mostrar las opciones de permisos al seleccionar una funcionalidad.
* @parametros:
* @retorno:
* @fecha de creación: 21/10/2008.
* @autor: Ing.Gusmary Balza.
***************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
*********************************************************************************/
	function obtenerPermisos()
	{
		//obtenerMensaje('procesar','','Cargando Datos');
		//habilitarPermisos();
		limpiarCampos(); //deshabilitar y limpiar
			
		var objdata ={
			'operacion': 'obtenerPermisos', 
			'codsis':    Ext.getCmp('cmbsistema').getValue(),
			'codmenu': Ext.getCmp('hidmenu').getValue(),
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function (resultado, request)
			{ 
				datos = resultado.responseText;
				//alert(datos);
				//Ext.Msg.hide();
				if (datos!='')
				{
					var datajson = eval('(' + datos + ')');
					if (datajson.raiz[0].visible=='1')
					{
						//Ext.getCmp('chbcancelar').setValue(datajson.raiz[0].visible);
						Ext.getCmp('chbacceso').enable();
					}					
					if (datajson.raiz[0].incluir=='1')
					{						
						Ext.getCmp('chbincluir').enable();
						//Ext.getCmp('chbincluir').disable()
					}
					if (datajson.raiz[0].leer=='1')
					{						
						Ext.getCmp('chbbuscar').enable();
					}
					if (datajson.raiz[0].cambiar=='1')
					{						
						Ext.getCmp('chbactualizar').enable();
					}
					if (datajson.raiz[0].eliminar=='1')
					{						
						Ext.getCmp('chbeliminar').enable();
					}
					if (datajson.raiz[0].imprimir=='1')
					{						
						Ext.getCmp('chbimprimir').enable();
					}
					if (datajson.raiz[0].ejecutar=='1')
					{						
						Ext.getCmp('chbejecutar').enable();
					}
					if (datajson.raiz[0].anular=='1')
					{						
						Ext.getCmp('chbanular').enable();
					}
					if (datajson.raiz[0].administrativo=='1')
					{						
						Ext.getCmp('chbadministrativo').enable();
					}					
					/*if (datajson.raiz[0].ayuda=='0')
					{						
						Ext.getCmp('chbayuda').disable();
					}*/
					if (datajson.raiz[0].cancelar=='1')
					{						
						Ext.getCmp('chbcancelar').enable();
					}
					if (datajson.raiz[0].enviarcorreo=='1')
					{						
						Ext.getCmp('chbenviarcorreo').enable();
					}
					if (datajson.raiz[0].descargar=='1')
					{						
						Ext.getCmp('chbdescargar').enable();
					}						
				}
			},
			failure: function (result,request) 
			{ 
				//Ext.Msg.hide();
				Ext.MessageBox.alert('Error', 'Error al procesar la información'); 
			}					
		});		
	}


/******************************************************************************
* @Función para habilitar las opciones de permisos y permitir que seleccione 
* a las que se les puede aplicar permiso.
* @parametros:
* @retorno:
* @fecha de creación: 18/12/2008.
* @autor: Ing.Gusmary Balza.
*************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
******************************************************************************/
	function habilitarPermisos()
	{
		//permitir que aplique los permisos
		Ext.getCmp('chbtodos').enable();
		Ext.getCmp('chbacceso').enable();
		Ext.getCmp('chbincluir').enable();
		Ext.getCmp('chbactualizar').enable();
		Ext.getCmp('chbeliminar').enable();
		Ext.getCmp('chbbuscar').enable();
		Ext.getCmp('chbimprimir').enable();
		Ext.getCmp('chbanular').enable();
		Ext.getCmp('chbejecutar').enable();
		Ext.getCmp('chbadministrativo').enable();
		//Ext.getCmp('chbayuda').enable();
		Ext.getCmp('chbcancelar').enable();
		Ext.getCmp('chbenviarcorreo').enable();
		Ext.getCmp('chbdescargar').enable();
	}	
	
	
/******************************************************************************
* @Función para deshabilitar las opciones de permisos y que aplique sólo los 
* los permisos que vienen del menú.
* @parametros:
* @retorno:
* @fecha de creación: 18/12/2008.
* @autor: Ing.Gusmary Balza.
*************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
******************************************************************************/	
	function deshabilitarPermisos()
	{
		//no permitir que aplique los permisos sino por el menu
		//Ext.getCmp('chbtodos').disable();
		Ext.getCmp('chbacceso').disable();
		Ext.getCmp('chbincluir').disable();
		Ext.getCmp('chbactualizar').disable();
		Ext.getCmp('chbeliminar').disable();
		Ext.getCmp('chbbuscar').disable();
		Ext.getCmp('chbimprimir').disable();
		Ext.getCmp('chbanular').disable();
		Ext.getCmp('chbejecutar').disable();
		Ext.getCmp('chbadministrativo').disable();
		//Ext.getCmp('chbayuda').disable();
		Ext.getCmp('chbcancelar').disable();
		Ext.getCmp('chbenviarcorreo').disable();
		Ext.getCmp('chbdescargar').disable();
	}
	

/******************************************************************************
* @Función para limpiar los nodos del árbol de funcionalidades
* @parametros:
* @retorno:
* @fecha de creación: 21/10/2008.
* @autor: Ing.Gusmary Balza.
*************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
******************************************************************************/
	function limpiarArbol()
	{
		while (rootnode.hasChildNodes())
   		{
           rootnode.removeChild(rootnode.firstChild);          
   		}		
	}


	function limpiarCampos()
	{
		Ext.getCmp('chbtodos').setValue(0);
		Ext.getCmp('chbacceso').setValue(0);
		Ext.getCmp('chbincluir').setValue(0);
		Ext.getCmp('chbactualizar').setValue(0);
		Ext.getCmp('chbeliminar').setValue(0);
		Ext.getCmp('chbbuscar').setValue(0);
		Ext.getCmp('chbimprimir').setValue(0);
		Ext.getCmp('chbanular').setValue(0);
		Ext.getCmp('chbejecutar').setValue(0);
		Ext.getCmp('chbadministrativo').setValue(0);
		//Ext.getCmp('chbayuda').setValue(0);
		Ext.getCmp('chbcancelar').setValue(0);
		Ext.getCmp('chbenviarcorreo').setValue(0);
		Ext.getCmp('chbdescargar').setValue(0);
		//Ext.getCmp('cmbusuario').enable();
		//Ext.getCmp('cmbgrupo').enable();
	}
	
	
/******************************************************************************* 	
* @Función para limpiar todos los campos. 
* @parametros:
* @retorno:
* @fecha creación: 20/08/2008.
* @autor: Ing. Gusmary Balza.
*********************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
******************************************************************************/	
	function irCancelar()
	{
		Ext.getCmp('cmbusuario').setValue('Seleccione');
		Ext.getCmp('cmbgrupo').setValue('Seleccione');
		Ext.getCmp('txtfuncionalidad').setValue('');
		Ext.getCmp('txtfuncionalidad').disable();
	
		Ext.getCmp('cmbusuario').enable();
		Ext.getCmp('cmbgrupo').enable();
		cambiar = false;
		limpiarCampos();
		deshabilitarPermisos();
	}
	
	
/*******************************************************************************
* @Función para cambiar un valor de true o false a 1 o 0.
* @fecha de creación: 26/08/2008.
* @autor: Ing.Gusmary Balza.
****************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
******************************************************************************/	
	function cambiarValor(valor)
	{
		if (valor)
		{
			return '1';	
		}
		else
		{
			return '0';	
		}
	}


/******************************************************************************* 
* @Función para guardar el perfil aplicado a un usuario o grupo.
* @fecha creación: 25/08/2008.
* @autor: Ing. Gusmary Balza.
*****************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
*******************************************************************************/		
	function irGuardar()
	{
		continuar  = false;
		visible    = cambiarValor(Ext.getCmp('chbacceso').getValue());
		incluir    = cambiarValor(Ext.getCmp('chbincluir').getValue())
		leer       = cambiarValor(Ext.getCmp('chbbuscar').getValue());
		actualizar = cambiarValor(Ext.getCmp('chbactualizar').getValue());
		eliminar   = cambiarValor(Ext.getCmp('chbeliminar').getValue());
		imprimir   = cambiarValor(Ext.getCmp('chbimprimir').getValue());
		ejecutar   = cambiarValor(Ext.getCmp('chbejecutar').getValue());
		anular     = cambiarValor(Ext.getCmp('chbanular').getValue());
		administrativo = cambiarValor(Ext.getCmp('chbadministrativo').getValue());
		//ayuda      = cambiarValor(Ext.getCmp('chbayuda').getValue());
		ayuda     = '1';
		cancelar   = cambiarValor(Ext.getCmp('chbcancelar').getValue());
		enviarcorreo = cambiarValor(Ext.getCmp('chbenviarcorreo').getValue());
		descargar  = cambiarValor(Ext.getCmp('chbdescargar').getValue());
		
		if (validarObjetos('cmbsistema','50','novacio')!='0')
		{
			if (validarObjetos('cmbusuario&cmbgrupo','0','novaciodos')!=false)			
			{
				obtenerMensaje('procesar','','Guardando Datos');
				
				if (validarObjetos('txtfuncionalidad','60','novacio')!='0')
			  	{
					if (cambiar==false)
					{							
						evento ='incluir';
						codintper = '---------------------------------';
					}
					else
					{
						evento ='actualizarUno';
						codintper = '';
					}
					
					continuar = true;
					if (Ext.getCmp('cmbgrupo').getValue()=='Seleccione')
					{				
						objdata ={
							'operacion':	evento, 
							'codsis':    	Ext.getCmp('cmbsistema').getValue(),
							'codusu':   	Ext.getCmp('cmbusuario').getValue(), 
							'funcionalidad':Ext.getCmp('txtfuncionalidad').getValue(),
							'codmenu':		Ext.getCmp('hidmenu').getValue(),
							'codintper': 	codintper,
							'visible':		visible,
							'enabled': 1,
							'incluir':      incluir,
							'leer':        	leer,
							'cambiar':    	actualizar,
							'eliminar':     eliminar,
							'imprimir':     imprimir,
							'ejecutar':     ejecutar,
							'anular':       anular,
							'administrativo':administrativo,
							'ayuda':		ayuda,
							'cancelar':		cancelar,
							'enviarcorreo':	enviarcorreo,
							'descargar':	descargar,
							'sistema': sistema,
							'vista': vista,
							'seleccionado': seleccionado
						};
					}
					else
					{					
						objdata ={
							'operacion':    evento, 
							'codsis':   Ext.getCmp('cmbsistema').getValue(),
							'nomgru':     Ext.getCmp('cmbgrupo').getValue(), 
							'funcionalidad':Ext.getCmp('txtfuncionalidad').getValue(),
							'codmenu':		Ext.getCmp('hidmenu').getValue(),
							'codintper':codintper,
							'visible':		visible,
							'enabled': 1,
							'incluir':      incluir,
							'leer':        	leer,
							'cambiar':    	actualizar,
							'eliminar':     eliminar,
							'imprimir':     imprimir,
							'ejecutar':     ejecutar,
							'anular':       anular,
							'administrativo':administrativo,
							'ayuda':		ayuda,
							'cancelar':		cancelar,
							'enviarcorreo':	enviarcorreo,
							'descargar':	descargar,
							'sistema': sistema,
							'vista': vista,
							'seleccionado': seleccionado
						};
					}
					if (continuar)
					{
						objdata=JSON.stringify(objdata);
						parametros = 'objdata='+objdata;
						Ext.Ajax.request({
						url : ruta,
						params : parametros,
						method: 'POST',
						success: function (resultad, request)
						{ 
							datos = resultad.responseText;
							Ext.Msg.hide();
							var datajson = eval('(' + datos + ')');
							if (datajson.raiz.valido==true)
							{
								Ext.MessageBox.alert('Mensaje',datajson.raiz.mensaje);
								irCancelar();  
							}
							else
							{
								Ext.MessageBox.alert('Mensaje', datajson.raiz.mensaje);
								irCancelar();
							}
						},
						failure: function (result,request) 
						{ 
							Ext.Msg.hide();
							Ext.MessageBox.alert('Error', 'Error al procesar la Información'); 
						}					
						});
					}
				}
				else
				{
					if (cambiar==false)
					{							
						evento  = 'insertarTodas';
						codintper = '---------------------------------';						
					}
					/*else
					{
						evento  = 'actualizar'; 
					}*/
					Ext.Msg.confirm('Confirmar', '¿Desea aplicar los permisos a todas las pantallas?', Result);					
				} 
		//	} 
			}
		} 
	} 
	
	
/*******************************************************************************
* @Función para aceptar el aplicar perfil a todas las pantallas
* @fecha de creación: 26/08/2008.
* @autor: Ing.Gusmary Balza.
*****************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
******************************************************************************/	
	function Result(btn)
	{
		if (btn=='yes')
		{ 
			obtenerMensaje('procesar','','Guardando Datos');
			
			codintper = '---------------------------------';
			if (Ext.getCmp('cmbgrupo').getValue()=='Seleccione')
			{			
				var objdata ={
				'operacion':     evento, 
				'codsis':    	Ext.getCmp('cmbsistema').getValue(),
				'codusu':	 	Ext.getCmp('cmbusuario').getValue(),
				'codintper':	codintper,
				'sistema': sistema,
				'vista': vista,
				'seleccionado': seleccionado
				};
			}
			else
			{			
				var objdata ={
					'operacion':     evento, 
					'codsis':		Ext.getCmp('cmbsistema').getValue(),
					'nomgru':   	Ext.getCmp('cmbgrupo').getValue(), 
					'codintper': 	codintper,
					'sistema': sistema,
					'vista': vista,
					'seleccionado': seleccionado
				};
			}
			objdata=JSON.stringify(objdata);
			parametros = 'objdata='+objdata;
			Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function (resultad, request)
			{ 
				datos = resultad.responseText;
				Ext.Msg.hide();
				var datajson = eval('(' + datos + ')');
				if (datajson.raiz.valido==true)
				{
					Ext.MessageBox.alert('Mensaje',datajson.raiz.mensaje);
					irCancelar();  
				}
				else
				{
					Ext.MessageBox.alert('Mensaje', datajson.raiz.mensaje);
					irCancelar();
				}
			},
			failure: function (result,request) 
			{ 
				Ext.Msg.hide();
				Ext.MessageBox.alert('Error', 'Error al procesar la información'); 
			}					
			});		
		}
	}
	
	
/*********************************************************************************
* @Función para buscar los permisos para una o todas las funcionalidades 
* @por usuario o grupo.
* @fecha de creación: 26/08/2008.
* @autor: Ing.Gusmary Balza.
***************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
*********************************************************************************/					
	function irBuscar()
	{		
		deshabilitarPermisos();
		if (validarObjetos('cmbsistema','50','novacio')!='0')
		{
			
			if (validarObjetos('cmbusuario&cmbgrupo','0','novaciodos')!=false)
			//if (validarObjetos('cmbusuario','30','novacio')!='0' || validarObjetos('cmbgrupo','60','novacio')!='0')
			{
				codintper = '---------------------------------';
				if (validarObjetos('txtfuncionalidad','50','novacio')=='0')
				{
					//Ext.Msg.confirm('Confirmar', '¿Desea buscar los permisos de todas las pantallas?', resultBuscar);
					Ext.Msg.alert('Mensaje','Debe seleccionar una funcionalidad');
				}
				else
				{				
					//obtenerMensaje('procesar','','Cargando Datos');
					if (Ext.getCmp('cmbgrupo').getValue()=='Seleccione')
					{					
						var objdata ={
							'operacion':     'buscarUno', 
							'codsis':   Ext.getCmp('cmbsistema').getValue(),
							'codusu':	Ext.getCmp('cmbusuario').getValue(),
							'codintper': codintper,
							'codmenu':  Ext.getCmp('hidmenu').getValue(),
							'sistema': sistema,
							'vista': vista,
							'seleccionado': seleccionado
						};
					}
					else
					{					
						var objdata ={
							'operacion': 'buscarUno', 
							'codsis':    Ext.getCmp('cmbsistema').getValue(),
							'nomgru':  Ext.getCmp('cmbgrupo').getValue(), 
							'codintper': codintper,
							'codmenu':	Ext.getCmp('hidmenu').getValue(),
							'sistema': sistema,
							'vista': vista,
							'seleccionado': seleccionado
						};
					}					
					objdata=JSON.stringify(objdata);
					parametros = 'objdata='+objdata;
					Ext.Ajax.request({
						url : ruta,
						params : parametros,
						method: 'POST',
						success: function (resultado, request)
						{ 
							datos = resultado.responseText;
							if (datos!='')
							{
								var datajson = eval('(' + datos + ')');
								Ext.getCmp('chbacceso').setValue(datajson.raiz[0].visible);
								Ext.getCmp('chbincluir').setValue(datajson.raiz[0].incluir);
								Ext.getCmp('chbbuscar').setValue(datajson.raiz[0].leer);
								Ext.getCmp('chbactualizar').setValue(datajson.raiz[0].cambiar);
								Ext.getCmp('chbeliminar').setValue(datajson.raiz[0].eliminar);
								Ext.getCmp('chbimprimir').setValue(datajson.raiz[0].imprimir);
								Ext.getCmp('chbejecutar').setValue(datajson.raiz[0].ejecutar);
								Ext.getCmp('chbanular').setValue(datajson.raiz[0].anular);
								Ext.getCmp('chbadministrativo').setValue(datajson.raiz[0].administrativo);
								//Ext.getCmp('chbayuda').setValue(datajson.raiz[0].ayuda);
								Ext.getCmp('chbcancelar').setValue(datajson.raiz[0].cancelar);
								Ext.getCmp('chbenviarcorreo').setValue(datajson.raiz[0].enviarcorreo);	
								Ext.getCmp('chbdescargar').setValue(datajson.raiz[0].descargar);
								//irCancelar();	
								cambiar = true;										
							}
							else
							{
								//irBuscar();
								Ext.Msg.alert('Mensaje','No posee permisos para esta funcionalidad');
								//deshabilitarPermisos();
								//obtenerPermisos();
								cambiar = false;
							}
						},
						failure: function (result,request) 
						{							
							Ext.MessageBox.alert('Error', 'Error al procesar la información'); 
						}					
					});
				}
			}
		}
	}
		
	
/********************************************************************************
* @Función para eliminar el perfil de un usuario o grupo para 
* una o todas las funcionalidades.
* @fecha de creación: 26/08/2008.
* @autor: Ing.Gusmary Balza.
*********************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
*********************************************************************************/	
	function irEliminar()
	{
		var result;
		Ext.MessageBox.confirm('Confirmar', '¿Desea eliminar este perfil?', result);
		function result(btn)
		{
			if (btn=='yes')
			{ 
				if (validarObjetos('cmbsistema','20','novacio')=='0')
				{					
					Ext.MessageBox.alert('Mensaje','No existe un perfil para eliminar');
				}
				else
				{
					obtenerMensaje('procesar','','Eliminando Datos');
					codintper = '---------------------------------';
					if (Ext.getCmp('txtfuncionalidad').getValue()=='')
					{
						if (Ext.getCmp('cmbgrupo').getValue()=='Seleccione')
						{
							var objdata ={
								'operacion':     'eliminarTodos', 
								'codsis':    Ext.getCmp('cmbsistema').getValue(),
								'codusu':	 Ext.getCmp('cmbusuario').getValue(),
								'funcionalidad': Ext.getCmp('txtfuncionalidad').getValue(),
								'codmenu':		 Ext.getCmp('hidmenu').getValue(),
								'codintper': codintper,							
								'sistema': sistema,
								'vista': vista,
								'seleccionado': seleccionado
							};
						}
						else
						{
							var objdata ={
								'operacion':     'eliminarTodos', 
								'codsis':    Ext.getCmp('cmbsistema').getValue(),
								'nomgru':      Ext.getCmp('cmbgrupo').getValue(), 
								'funcionalidad': Ext.getCmp('txtfuncionalidad').getValue(),
								'codmenu':		 Ext.getCmp('hidmenu').getValue(),
								'codintper': codintper,
								'sistema': sistema,
								'vista': vista,
								'seleccionado': seleccionado
							};
						}
					}
					else
					{
						if (Ext.getCmp('cmbgrupo').getValue()=='Seleccione')
						{
							var objdata ={
								'operacion':     'eliminarUno', 
								'codsis':    Ext.getCmp('cmbsistema').getValue(),
								'codusu':	 Ext.getCmp('cmbusuario').getValue(),
								'funcionalidad': Ext.getCmp('txtfuncionalidad').getValue(),
								'codmenu':		 Ext.getCmp('hidmenu').getValue(),
								'codintper': codintper,
								'sistema': sistema,
								'vista': vista,
								'seleccionado': seleccionado
							};
						}
						else
						{
							var objdata ={
								'operacion':     'eliminarUno', 
								'codsis':    Ext.getCmp('cmbsistema').getValue(),
								'nomgru':      Ext.getCmp('cmbgrupo').getValue(), 
								'funcionalidad': Ext.getCmp('txtfuncionalidad').getValue(),
								'codmenu':		 Ext.getCmp('hidmenu').getValue(),
								'codintper': codintper,
								'sistema': sistema,
								'vista': vista,
								'seleccionado': seleccionado
							};
						}
					}
					objdata=JSON.stringify(objdata);
					parametros = 'objdata='+objdata;				     
					mensaje = 'Eliminado';
					Ext.Ajax.request({
					url : ruta,
					params : parametros,
					method: 'POST',
					success: function ( resultad, request )
					{ 
						datos = resultad.responseText;
						Ext.Msg.hide();
						var datajson = eval('(' + datos + ')');
						if (datajson.raiz.valido==true)						
						{
							Ext.MessageBox.alert('Mensaje',datajson.raiz.mensaje);
							irCancelar();		  
						}
						else
						{
							Ext.MessageBox.alert('Error', datajson.raiz.mensaje);
						}
					},
					failure: function ( result, request)
					{ 
						Ext.Msg.hide();
						Ext.MessageBox.alert('Error', 'Error al procesar la información'); 
					} 
					});
				}
			}
		}		
	}
		
	
