/***********************************************************************
* @Proceso de asignar perfiles a usuarios o grupos.
* @Archivo javascript el cual contiene los componentes del proceso 
* @de asignar perfiles a usuarios o grupos.
* @versión: 1.0      
* @fecha de creación: 19/08/2008
* @autor: Ing. Gusmary Balza 
***********************************************************************
* @fecha modificacion
* @autor 
* @descripcion
**********************************************************************/
var panel = '';
var pantalla = 'perfiles';
var actualizar = false;
var rootnode = '';
ruta =  '../../controlador/msg/sigesp_ctr_msg_perfiles.php'; 
Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		// turn on validation errors beside the field globally
		Ext.form.Field.prototype.msgTarget = 'side';
				
		//cargar los datos de los sistemas para asociarlos al combo.
		var datosNuevo = {"raiz":[{'codsistema':'PRB','nombre':'Prueba'}]};
		
			record = Ext.data.Record.create([
				{name: 'codsistema'},     
				{name: 'nombre'}
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
		var datosUsuario = {"raiz":[{'codusuario':'','nombre':''}]};
		
			record = Ext.data.Record.create([
				{name: 'codusuario'},     
				{name: 'nombre'}
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
		var datosGrupo = {"raiz":[{'codgrupo':'','nombre':''}]};
		
			record = Ext.data.Record.create([
				{name: 'codgrupo'},     
				{name: 'nombre'}
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
			
			
/**********************************
* @Función para cargar en el combo 
* los nombres de los sistemas.
*
* @fecha de creación:19/08/2008
* @autor: Ing. Gusmary Balza
************************************/			
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
			//Ext.getCmp('cmbsistema').addListener('select',cargarMenu);
		}
		cargarSistemas();	
		
		
/**********************************
* @Función para cargar en el combo 
* los códigos de los usuarios.
*
* @fecha de creación:19/08/2008
* @autor: Ing. Gusmary Balza
************************************/	
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


/**********************************
* @Función para cargar en el combo 
* los nombres de los grupos.
*
* @fecha de creación:19/08/2008
* @autor: Ing. Gusmary Balza
************************************/
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
					if (datajson.raiz!=null)
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
		
		
/*********************************************************
* @Función para deshabilitar el combo de usuario o el
* de grupo dependiendo si se elige el uno o el otro.
*
*@fecha de creación: 20/08/2008
*@autor: Ing.Gusmary Balza
*********************************************************/
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
			/*children:[
			{
				 text:'Administración de Usuarios',
			}]*/
		});

		//componentes del formulario
		Xpos = ((screen.width/2)-(500/2)); 
		Ypos = ((screen.height/2)-(650/2));
		panel = new Ext.FormPanel({
			title: 'Aplicar Perfil a Usuario o Grupo',
			bodyStyle:'padding:5px 5px 0px',
			width:500,
			frame: true, //quitar borde de las columnas, fondo del mismo color azul
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
					readOnly:true,
					name:'sistema',
					id:'cmbsistema',
					emptyText:'Seleccione',
					displayField:'nombre',
					valueField:'codsistema',
					typeAhead: true,
					mode: 'local',
					triggerAction: 'all',
					store: dssistema,
					width:300		
				  },{
					xtype:'combo',
					fieldLabel:'Usuario',
					readOnly:true,
					name:'usuario',
					id:'cmbusuario',
					emptyText:'Seleccione',
					displayField:'codusuario',
					valueField:'codusuario',
					typeAhead: true,
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
					displayField:'nombre',
					valueField:'codgrupo',
					typeAhead: true,
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
				columnWidth:.3,
				layout: 'form',
				border:false,
				items: [{	
					xtype:'textfield',
					fieldLabel:'Pantalla',
					name:'funcionalidad',
					id:'txtfuncionalidad',
					labelStyle: 'width:120px',
					readOnly :true,
					width:300
	/*			},{
					xtype:'hidden',
				//	fieldLabel:'Funcionalidad',
					name:'pantalla',
					id:'hidpantalla',
					width:300*/
				},{
					xtype:'hidden',
					name:'menu',
					id:'hidmenu',
					width:300
				},{
					xtype:'checkbox',
					//boxLabel:'Si',
					fieldLabel:'Acceso a la pantalla',
					labelStyle: 'width:120px',
					name:'acceso',
					id:'chbacceso',
				}]	
			 	},{
					xtype:'treepanel',
					title:'Funcionalidades del Sistema',
					id: 'arbolFunc',
					border: false,
					loader: new Ext.tree.TreeLoader(),
					rootVisible:true,
					lines:false,
					autoScroll:true,
					iconCls:'menu1',
					root: rootnode,
					renderTo:'tree-div'
			}]
			},{
			xtype:'fieldset',
			title:'Opciones de Permisos',
			id:'fsformpermisos',
			layout:'column',
			autoHeight:true,
			autoWidth:true,
			style:'background:#F9F9EE',		
			items:[{			  
					columnWidth:.3,
					layout: 'form',
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
				columnWidth:.3,
				layout: 'form',
				border:false,
				items: [{						
					xtype:'checkbox',
					fieldLabel:'Actualizar',
					name:'actualizar',
					id:'chbactualizar',
				 },{
					xtype:'checkbox',
					fieldLabel:'Eliminar',
					name:'eliminar',
					id:'chbeliminar',
				 },{
					xtype:'checkbox',
					fieldLabel:'Imprimir',
					name:'imprimir',
					id:'chbimprimir',
				 }]
			},{
				columnWidth:.3,
				layout: 'form',
				border:false,
				items: [{	
					xtype:'checkbox',
					fieldLabel:'Ejecutar',
					name:'ejecutar',
					id:'chbejecutar',
				},{
					xtype:'checkbox',
					fieldLabel:'Anular',
					name:'anular',
					id:'chbanular',
				},{
					xtype:'checkbox',
					fieldLabel:'Administrativo',
					name:'administrativo',
					id:'chbadministrativo',
				}]
			}]
		}]
	});
	panel.render(document.body);
	
	Ext.getCmp('cmbsistema').addListener('select',cargarMenu);
	Ext.getCmp('chbtodos').addListener('check',seleccionarTodos);


/************************************************ 	
* @Función para cargar las opciones de menú como 
* @ un árbol de acuerdo al sistema seleccionado.
* @parametros:
* @retorno:
* @fecha creación: 21/08/2008.
* @autor: Ing. Gusmary Balza.
************************************************/	
	function cargarMenu()
	{
		var objdata ={
			'operacion': 'obtenerMenu',
			'codsistema': Ext.getCmp('cmbsistema').getValue(),
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
				obj   = eval('('+datos+')');
				total = obj.raiz.length;
				// generar el árbol dinámico de funcionalidades
				for (i=0; i<total; i++) // Busco todos los nodos donde el nivel sea el maximo
				{
					if (obj.raiz[i].nivel==1)  // Buscar solo los nodos Principales 
					{
						//nodo padre del arbol
						var rootnode0 = new Ext.tree.TreeNode({
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
									var rootnode1 = new Ext.tree.TreeNode({
										text:obj.raiz[i].nomlogico,
									});
									//////////////////////////////////////////////////////
									
									for (l=0; l<total; l++) // Recorrer todos los nodos para buscar los hijos del nivel 3
									{
										if (obj.raiz[l].codpadre==codpadre2)
										{
											if (obj.raiz[l].hijo==1) // si el nodo es padre busco sus hijos
											{
												codpadre3     = obj.raiz[l].codmenu;
												//nodo padre del arbol
												var rootnode2 = new Ext.tree.TreeNode({
													text:obj.raiz[l].nomlogico,
												});							
												for (k=0; k<total; k++) //Recorrer todos los nodos para buscar los hijos del nivel4
												{
													if (obj.raiz[k].codpadre==codpadre3)
													{
														if(obj.raiz[k].hijo==1) // si el nodo es padre busco sus hijos
														{
															codpadre4     = obj.raiz[k].codmenu;
															var rootnode3 = new Ext.tree.TreeNode({
																text:obj.raiz[k].nomlogico,
															});
															for (p=0; p<total; p++) // nivel 5
															{
																if (obj.raiz[p].codpadre==codpadre4)
																{
																	//nodo final
																	var nodo = new Ext.tree.TreeNode({
																		text: obj.raiz[p].nomlogico,
																		id:obj.raiz[p].codmenu,
																		iconCls:'app_warning',
																		leaf:true,
																		listeners :{
																		click: function()
																			{
																				Ext.getCmp('txtfuncionalidad').setValue(this.text);
																				Ext.getCmp('hidmenu').setValue(this.id);
																				
																			}
																		}
											
																	});
																	rootnode3.appendChild(nodo); 
																}
															}
															rootnode2.appendChild(rootnode3);
														}
														else
														{
															var nodo = new Ext.tree.TreeNode({
																text: obj.raiz[k].nomlogico,
																id:obj.raiz[k].codmenu,
																iconCls:'app_warning',
																leaf:true,
																listeners :{
																	click: function()
																	{
																		Ext.getCmp('txtfuncionalidad').setValue(this.text);
																		Ext.getCmp('hidmenu').setValue(this.id);
																		
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
												var nodo = new Ext.tree.TreeNode({
													text: obj.raiz[l].nomlogico,
													id:obj.raiz[l].codmenu,
													iconCls:'app_warning',
													leaf:true,
													listeners :{
														click: function()
														{
															Ext.getCmp('txtfuncionalidad').setValue(this.text);
															Ext.getCmp('hidmenu').setValue(this.id);
															
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
									var nodo = new Ext.tree.TreeNode({
										text: obj.raiz[j].nomlogico,
										id:obj.raiz[j].codmenu,
										iconCls:'app_warning',
										leaf:true,
										listeners :{
										click: function()
										{
											Ext.getCmp('txtfuncionalidad').setValue(this.text);
											Ext.getCmp('hidmenu').setValue(this.id);
											
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
			},
			failure: function (result,request) 
			{ 
				Ext.MessageBox.alert('Error', 'El menu no se pudo cargar'); 
			}					
		});
	}



}); //fin de archivo



/**************************************************
* @Función para seleccionar todas las opciones 
* @de los permisos al seleccionar la opción: todos.
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008.
* @autor: Ing.Gusmary Balza.
**************************************************/
	function seleccionarTodos()
	{
		if (Ext.getCmp('chbtodos').getValue()==1)
		{
			Ext.getCmp('chbincluir').setValue(1);
			Ext.getCmp('chbbuscar').setValue(1);
			Ext.getCmp('chbactualizar').setValue(1);
			Ext.getCmp('chbeliminar').setValue(1);
			Ext.getCmp('chbimprimir').setValue(1);
			Ext.getCmp('chbejecutar').setValue(1);
			Ext.getCmp('chbanular').setValue(1);
			Ext.getCmp('chbadministrativo').setValue(1);
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
		}
	}


/******************************************** 	
* @Función para limpiar todos los campos. 
* @parametros:
* @retorno:
* @fecha creación: 20/08/2008.
* @autor: Ing. Gusmary Balza.
*******************************************/	
	function irCancelar()
	{
		//Ext.getCmp('cmbsistema').setValue('Seleccione');
		Ext.getCmp('cmbusuario').setValue('Seleccione');
		Ext.getCmp('cmbgrupo').setValue('Seleccione');
		Ext.getCmp('txtfuncionalidad').setValue('');
		Ext.getCmp('txtfuncionalidad').disable();
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
		Ext.getCmp('cmbusuario').enable();
		Ext.getCmp('cmbgrupo').enable();
		actualizar = false;
	}

	
/*********************************************************
* @Función para cambiar un valor de true o false a 1 o 0.
* @fecha de creación: 26/08/2008.
* @autor: Ing.Gusmary Balza.
*********************************************************/	
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


/*************************************************************** 
* @Función para guardar el perfil aplicado a un usuario o grupo.
* @fecha creación: 25/08/2008.
* @autor: Ing. Gusmary Balza.
***************************************************************/		
	function irGuardar()
	{
		continuar = false;
		visible    = cambiarValor(Ext.getCmp('chbacceso').getValue());
		incluir    = cambiarValor(Ext.getCmp('chbincluir').getValue())
		leer       = cambiarValor(Ext.getCmp('chbbuscar').getValue());
		actualizar = cambiarValor(Ext.getCmp('chbactualizar').getValue());
		eliminar   = cambiarValor(Ext.getCmp('chbeliminar').getValue());
		imprimir   = cambiarValor(Ext.getCmp('chbimprimir').getValue());
		ejecutar   = cambiarValor(Ext.getCmp('chbejecutar').getValue());
		anular     = cambiarValor(Ext.getCmp('chbanular').getValue());
		administrativo = cambiarValor(Ext.getCmp('chbadministrativo').getValue());
		
		if (validarObjetos('cmbsistema','50','novacio')!='0')
		{
			if (Ext.getCmp('cmbusuario').getValue()=='' && Ext.getCmp('cmbgrupo').getValue()=='')
			{
				Ext.MessageBox.alert('Mensaje','Debe seleccionar un usuario o un grupo');
			}
			else
			{
				if (validarObjetos('txtfuncionalidad','60','novacio')!='0')
			  	//if (Ext.getCmp('txtfuncionalidad').getValue()=='')
				{
					if (actualizar=='0')
					{							
						evento ='incluir';
						mensaje = 'Incluido';
					}
					else
					{
						evento ='actualizarUno';
						mensaje = 'Actualizado';
					}
					if (Ext.getCmp('cmbgrupo').getValue()=='Seleccione')
					{
						codgrupo = '-----';
						codintpermiso = '----------';
						continuar = true;
						var objdata ={
							'operacion':     evento, 
							'codsistema':    Ext.getCmp('cmbsistema').getValue(),
							'codusuario':    Ext.getCmp('cmbusuario').getValue(), 
							'codgrupo':		 codgrupo,
							'funcionalidad': Ext.getCmp('txtfuncionalidad').getValue(),
							'codmenu':		 Ext.getCmp('hidmenu').getValue(),
							'codintpermiso': codintpermiso,
							'visible':		 visible,
							'incluir':       incluir,
							'leer':        	 leer,
							'actualizar':    actualizar,
							'eliminar':      eliminar,
							'imprimir':      imprimir,
							'ejecutar':      ejecutar,
							'anular':        anular,
							'administrativo':administrativo,
							'sistema': sistema,
							'vista': vista
						};
					}
					else
					{
						codusuario    = '--------------------';
						codintpermiso = '----------';
						continuar = true;
						var objdata ={
							'operacion':     evento, 
							'codsistema':    Ext.getCmp('cmbsistema').getValue(),
							'codgrupo':      Ext.getCmp('cmbgrupo').getValue(), 
							'codusuario':	 codusuario,
							'funcionalidad': Ext.getCmp('txtfuncionalidad').getValue(),
							'codmenu':		 Ext.getCmp('hidmenu').getValue(),
							'codintpermiso': codintpermiso,
							'visible':		 visible,
							'incluir':       incluir,
							'leer':        	 leer,
							'actualizar':    actualizar,
							'eliminar':      eliminar,
							'imprimir':      imprimir,
							'ejecutar':      ejecutar,
							'anular':        anular,
							'administrativo':administrativo,
							'sistema': sistema,
							'vista': vista
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
							var datajson = eval('(' + datos + ')');
							if (datajson.raiz.valido==true)
							{
								Ext.MessageBox.alert('Mensaje','Perfil '+mensaje + ' con éxito');
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
							Ext.MessageBox.alert('Error', 'El registro no se pudo incluir'); 
						}					
						});
					}
				}
				else
				{
					if (actualizar=='1')
					{							
						evento  = 'insertarTodas';
						mensaje = 'Incluido';
					}
					else
					{
						evento  = 'actualizar'; //considerar para agregar
						mensaje = 'Actualizado';
					}
					Ext.Msg.confirm('Confirmar', '¿Desea aplicar los permisos a todas las pantallas?', Result);
					
				} //fin else aplicar todas pantallas
			} //fin else selecciono usuario o grupo
		} //fin del if validar vacio sistema
	} //fin de la funcion
	
	
/**************************************************************
* @Función para aceptar el aplicar perfil a todas las pantallas
* @fecha de creación: 26/08/2008.
* @autor: Ing.Gusmary Balza.
**************************************************************/	
	function Result(btn)
	{
		if (btn=='yes')
		{ 
			if (Ext.getCmp('cmbgrupo').getValue()=='Seleccione')
			{
				codgrupo = '-----';
				codintpermiso = '----------';
				
				var objdata ={
				'operacion':     evento, 
				'codsistema':    Ext.getCmp('cmbsistema').getValue(),
				'codgrupo':      codgrupo, 
				'codusuario':	 Ext.getCmp('cmbusuario').getValue(),
				'codintpermiso': codintpermiso,
				'visible':		 visible,
				'incluir':       incluir,
				'leer':        	 leer,
				'actualizar':    actualizar,
				'eliminar':      eliminar,
				'imprimir':      imprimir,
				'ejecutar':      ejecutar,
				'anular':        anular,
				'administrativo':administrativo,
				'sistema': sistema,
				'vista': vista
				};
			}
			else
			{
				codintpermiso = '---------';
				codusuario = '--------------------';
				
				var objdata ={
					'operacion':     evento, 
					'codsistema':    Ext.getCmp('cmbsistema').getValue(),
					'codgrupo':      Ext.getCmp('cmbgrupo').getValue(), 
					'codusuario':	 codusuario,
					'codintpermiso': codintpermiso,
					'visible':		 visible,
					'incluir':       incluir,
					'leer':        	 leer,
					'actualizar':    actualizar,
					'eliminar':      eliminar,
					'imprimir':      imprimir,
					'ejecutar':      ejecutar,
					'anular':        anular,
					'administrativo':administrativo,
					'sistema': sistema,
					'vista': vista
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
				var datajson = eval('(' + datos + ')');
				if (datajson.raiz.valido==true)
				{
					Ext.MessageBox.alert('Mensaje','Perfil '+mensaje + ' con éxito');
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
				Ext.MessageBox.alert('Error', 'El registro no se pudo incluir'); 
			}					
			});
			
		}
	} //fin funcion result
	
	
/***********************************************************************
* @Función para buscar los permisos para una o todas las funcionalidades 
* @por usuario o grupo.
* @fecha de creación: 26/08/2008.
* @autor: Ing.Gusmary Balza.
************************************************************************/					
	function irBuscar()
	{
		if (validarObjetos('cmbsistema','50','novacio')!='0')
		{
			if ((Ext.getCmp('cmbusuario').getValue()=='') && (Ext.getCmp('cmbgrupo').getValue()==''))
			{
				Ext.Msg.alert('Mensaje','Debe seleccionar un usuario o un grupo');
			}
			else
			{
				actualizar = true;
				if (Ext.getCmp('txtfuncionalidad').getValue()=='')
				{
					Ext.Msg.confirm('Confirmar', '¿Desea buscar los permisos de todas las pantallas?', resultBuscar);
				}
				else
				{
					if (Ext.getCmp('cmbgrupo').getValue()=='Seleccione')
					{
						codgrupo = '-----';
						codintpermiso = '----------';
						
						var objdata ={
						'operacion':     'buscarUno', 
						'codsistema':    Ext.getCmp('cmbsistema').getValue(),
						'codgrupo':      codgrupo, 
						'codusuario':	 Ext.getCmp('cmbusuario').getValue(),
						'codintpermiso': codintpermiso,
						'codmenu':		 Ext.getCmp('hidmenu').getValue(),
						'sistema': sistema,
						'vista': vista
						};
					}
					else
					{
						codintpermiso = '----------';
						codusuario = '--------------------';
						
						var objdata ={
							'operacion':     'buscarUno', 
							'codsistema':    Ext.getCmp('cmbsistema').getValue(),
							'codgrupo':      Ext.getCmp('cmbgrupo').getValue(), 
							'codusuario':	 codusuario,
							'codintpermiso': codintpermiso,
							'codmenu':		 Ext.getCmp('hidmenu').getValue(),
							'sistema': sistema,
							'vista': vista
						};
					}					
					objdata=JSON.stringify(objdata);
					parametros = 'objdata='+objdata;
					alert(parametros);
					Ext.Ajax.request({
						url : ruta,
						params : parametros,
						method: 'POST',
						success: function (resultado, request)
						{ 
							datos = resultado.responseText;
							alert(datos);
							if (datos!='')
							{
								var datajson = eval('(' + datos + ')');
								Ext.getCmp('chbacceso').setValue(datajson.raiz[0].visible);
								Ext.getCmp('chbincluir').setValue(datajson.raiz[0].incluir);
								Ext.getCmp('chbbuscar').setValue(datajson.raiz[0].leer);
								Ext.getCmp('chbactualizar').setValue(datajson.raiz[0].actualizar);
								Ext.getCmp('chbeliminar').setValue(datajson.raiz[0].eliminar);
								Ext.getCmp('chbimprimir').setValue(datajson.raiz[0].imprimir);
								Ext.getCmp('chbejecutar').setValue(datajson.raiz[0].ejecutar);
								Ext.getCmp('chbanular').setValue(datajson.raiz[0].anular);
								Ext.getCmp('chbadministrativo').setValue(datajson.raiz[0].administrativo);
												
							}
						},
						failure: function (result,request) 
						{ 
							Ext.MessageBox.alert('Error', 'El registro no se pudo encontrar'); 
						}					
					});
				}
			}
		}
	}
	
	
/***************************************************************
* @Función para aceptar el buscar todas las funcionalidades.
* @fecha de creación: 26/08/2008.
* @autor: Ing.Gusmary Balza.
*************************************************************/
	function resultBuscar(btn) 
	{
		if (btn=='yes')
		{ 
			actualizar = true;
			if (Ext.getCmp('cmbgrupo').getValue()=='Seleccione')
			{
				codgrupo = '-----';
				codintpermiso = '----------';
				
				var objdata ={
				'operacion':     'buscarTodos', 
				'codsistema':    Ext.getCmp('cmbsistema').getValue(),
				'codgrupo':      codgrupo, 
				'codusuario':	 Ext.getCmp('cmbusuario').getValue(),
				'codintpermiso': codintpermiso,
				'sistema': sistema,
				'vista': vista
				};
			}
			else
			{
				codintpermiso = '---------';
				codusuario = '--------------------';
				
				var objdata ={
					'operacion':     'buscarTodos', 
					'codsistema':    Ext.getCmp('cmbsistema').getValue(),
					'codgrupo':      Ext.getCmp('cmbgrupo').getValue(), 
					'codusuario':	 codusuario,
					'codintpermiso': codintpermiso,
					'sistema': sistema,
					'vista': vista
				};
			}
			objdata=JSON.stringify(objdata);
			parametros = 'objdata='+objdata;
			alert(parametros);
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
						/*Ext.getCmp('chbacceso').setValue(datajson.raiz[0].visible);
						Ext.getCmp('chbincluir').setValue(datajson.raiz[0].incluir);
						Ext.getCmp('chbbuscar').setValue(datajson.raiz[0].leer);
						Ext.getCmp('chbactualizar').setValue(datajson.raiz[0].actualizar);
						Ext.getCmp('chbeliminar').setValue(datajson.raiz[0].eliminar);
						Ext.getCmp('chbimprimir').setValue(datajson.raiz[0].imprimir);
						Ext.getCmp('chbejecutar').setValue(datajson.raiz[0].ejecutar);
						Ext.getCmp('chbanular').setValue(datajson.raiz[0].anular);
						Ext.getCmp('chbadministrativo').setValue(datajson.raiz[0].administrativo);*/
					}
				},
				failure: function (result,request) 
				{ 
					Ext.MessageBox.alert('Error', 'El registro no se pudo encontrar'); 
				}					
			});
		} //fin del btn
	} //fin funcion result

	
/******************************************************************
* @Función para eliminar el perfil de un usuario o grupo para una 
* @o todas las funcionalidades.
* @fecha de creación: 26/08/2008.
* @autor: Ing.Gusmary Balza.
*****************************************************************/	
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
					if (Ext.getCmp('txtfuncionalidad').getValue()=='')
					{
						if (Ext.getCmp('cmbgrupo').getValue()=='Seleccione')
						{
							codgrupo = '-----';
							codintpermiso = '----------';
							
							var objdata ={
								'operacion':     'eliminarTodos', 
								'codsistema':    Ext.getCmp('cmbsistema').getValue(),
								'codgrupo':      codgrupo, 
								'codusuario':	 Ext.getCmp('cmbusuario').getValue(),
								'funcionalidad': Ext.getCmp('txtfuncionalidad').getValue(),
								'codmenu':		 Ext.getCmp('hidmenu').getValue(),
								'codintpermiso': codintpermiso,
								'visible':		 Ext.getCmp('chbacceso').getValue(),
								'incluir':       Ext.getCmp('chbincluir').getValue(),
								'leer':      	 Ext.getCmp('chbbuscar').getValue(),
								'actualizar':    Ext.getCmp('chbactualizar').getValue(),
								'eliminar':      Ext.getCmp('chbeliminar').getValue(),
								'imprimir':      Ext.getCmp('chbimprimir').getValue(),
								'ejecutar':      Ext.getCmp('chbejecutar').getValue(),
								'anular':        Ext.getCmp('chbanular').getValue(),
								'administrativo':Ext.getCmp('chbadministrativo').getValue(),
								'sistema': sistema,
								'vista': vista
							};
						}
						else
						{
							codintpermiso = '---------';
							codusuario = '--------------------';
							
							var objdata ={
								'operacion':     'eliminarTodos', 
								'codsistema':    Ext.getCmp('cmbsistema').getValue(),
								'codgrupo':      Ext.getCmp('cmbgrupo').getValue(), 
								'codusuario':	 codusuario,
								'funcionalidad': Ext.getCmp('txtfuncionalidad').getValue(),
								'codmenu':		 Ext.getCmp('hidmenu').getValue(),
								'codintpermiso': codintpermiso,
								'visible':		 Ext.getCmp('chbacceso').getValue(),
								'incluir':       Ext.getCmp('chbincluir').getValue(),
								'leer':      	 Ext.getCmp('chbbuscar').getValue(),
								'actualizar':    Ext.getCmp('chbactualizar').getValue(),
								'eliminar':      Ext.getCmp('chbeliminar').getValue(),
								'imprimir':      Ext.getCmp('chbimprimir').getValue(),
								'ejecutar':      Ext.getCmp('chbejecutar').getValue(),
								'anular':        Ext.getCmp('chbanular').getValue(),
								'administrativo':Ext.getCmp('chbadministrativo').getValue(),
								'sistema': sistema,
								'vista': vista
							};
						}
					}
					else
					{
						if (Ext.getCmp('cmbgrupo').getValue()=='Seleccione')
						{
							codgrupo = '-----';
							codintpermiso = '----------';
							
							var objdata ={
								'operacion':     'eliminarUno', 
								'codsistema':    Ext.getCmp('cmbsistema').getValue(),
								'codgrupo':      codgrupo, 
								'codusuario':	 Ext.getCmp('cmbusuario').getValue(),
								'funcionalidad': Ext.getCmp('txtfuncionalidad').getValue(),
								'codmenu':		 Ext.getCmp('hidmenu').getValue(),
								'codintpermiso': codintpermiso,
								'visible':		 Ext.getCmp('chbacceso').getValue(),
								'incluir':       Ext.getCmp('chbincluir').getValue(),
								'leer':      	 Ext.getCmp('chbbuscar').getValue(),
								'actualizar':    Ext.getCmp('chbactualizar').getValue(),
								'eliminar':      Ext.getCmp('chbeliminar').getValue(),
								'imprimir':      Ext.getCmp('chbimprimir').getValue(),
								'ejecutar':      Ext.getCmp('chbejecutar').getValue(),
								'anular':        Ext.getCmp('chbanular').getValue(),
								'administrativo':Ext.getCmp('chbadministrativo').getValue(),
								'sistema': sistema,
								'vista': vista
							};
						}
						else
						{
							codintpermiso = '---------';
							codusuario = '--------------------';
							
							var objdata ={
								'operacion':     'eliminarUno', 
								'codsistema':    Ext.getCmp('cmbsistema').getValue(),
								'codgrupo':      Ext.getCmp('cmbgrupo').getValue(), 
								'codusuario':	 codusuario,
								'funcionalidad': Ext.getCmp('txtfuncionalidad').getValue(),
								'codmenu':		 Ext.getCmp('hidmenu').getValue(),
								'codintpermiso': codintpermiso,
								'visible':		 Ext.getCmp('chbacceso').getValue(),
								'incluir':       Ext.getCmp('chbincluir').getValue(),
								'leer':      	 Ext.getCmp('chbbuscar').getValue(),
								'actualizar':    Ext.getCmp('chbactualizar').getValue(),
								'eliminar':      Ext.getCmp('chbeliminar').getValue(),
								'imprimir':      Ext.getCmp('chbimprimir').getValue(),
								'ejecutar':      Ext.getCmp('chbejecutar').getValue(),
								'anular':        Ext.getCmp('chbanular').getValue(),
								'administrativo':Ext.getCmp('chbadministrativo').getValue(),
								'sistema': sistema,
								'vista': vista
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
						var datajson = eval('(' + datos + ')');
						if (datajson.raiz.valido==true)						
						{
							Ext.MessageBox.alert('Mensaje','Perfil '+mensaje + ' con éxito');
							irCancelar();		  
						}
						else
						{
							Ext.MessageBox.alert('Error', datajson.raiz.mensaje);
						}
					},
					failure: function ( result, request)
					{ 
						Ext.MessageBox.alert('Error', result.responseText); 
					} 
					});
				}
			}
		}		
	}
	
	
