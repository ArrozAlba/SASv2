/*******************************************************************************
* @Reporte de permisos.
* @Archivo javascript el cual contiene los componentes del reporte de permisos.
* @versión: 1.0      
* @creado: 26/08/2008
* @autor: Ing. Gusmary Balza 
***************************
* @fecha modificacion
* @autor 
* @descripcion
*******************************************************************************/
ruta =  '../../controlador/msg/sigesp_ctr_msg_permisos.php'; 
pantalla = 'permisos';
var panel='';
Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		// turn on validation errors beside the field globally
		Ext.form.Field.prototype.msgTarget = 'side';
		
		Xpos = ((screen.width/2)-(550/2)); 
		Ypos = ((screen.height/2)-(550/2));
		panel = new Ext.FormPanel({
			title: 'Reporte de Permisos',
			bodyStyle:'padding:5px 5px 0px',
			style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
			width:550,
			tbar: [],
			items:[{
				xtype:'fieldset',
				title:'Tipo de Busqueda',
				id:'fsbusqueda',
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',		
				items:[{  
					xtype:'textfield',
					fieldLabel:'Usuario',
					name:'codigo del usuario',
					readOnly:true,
					id:'txtcodusuario',
					width:150
				},{
					xtype:'button',
					id:'btnBuscarUsuario',
					handler: irBuscarUsuario,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar un usuario',
					style:'position:absolute;left:275px;top:28px',
					width:50
				},{
					xtype:'textfield',
					name:'nombre del usuario',
					id:'hidnomusuario',
					disabled:true,
					hideLabel:true,
					width:100,
					style:'position:absolute;left:290px;top:-25px;border:none',
				},{
					xtype:'textfield',
					name:'apellido del usuario',
					id:'hidapeusuario',
					disabled:true,
					hideLabel:true,
					width:100,
					style:'position:absolute;left:390px;top:-29px;border:none',
				},{
					xtype:'textfield',
					fieldLabel:'Grupo',
					name:'codigo del grupo',
					readOnly:true,
					id:'txtcodgrupo',
					width:50
				},{
					xtype:'button',
					id:'btnBuscarGrupo',
					handler: irBuscarGrupo,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar un grupo',
					style:'position:absolute;left:175px;top:62px',
					width:50
				},{
					xtype:'textfield',
					name:'nombre del grupo',
					id:'hidgrupo',
					disabled:true,
					hideLabel:true,
					style:'position:absolute;left:190px;top:-25px;border:none',
					width:200
				},{
					xtype:'textfield',
					fieldLabel:'Sistema',
					name:'codigo del sistema',
					id:'txtcodsistema',
					readOnly:true,
					width:50
				},{
					xtype:'button',
					id:'btnBuscarSistema',
					handler: irBuscarSistema,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar un sistema',
					style:'position:absolute;left:175px;top:92px',
					width:50
				},{
					xtype:'textfield',
					name:'nombre del Sistema',
					id:'hidsistema',
					disabled:true,
					hideLabel:true,
					style:'position:absolute;left:190px;top:-25px;border:none',
					width:300
				}]
			},{
				xtype:'fieldset',
				title:'Ordenado Por',
				id:'fsorden',
				autoHeight:true,
				autoWidth:true,
				cls:'fondo',
				bodyStyle:'padding:5px 100px 0px',
				items:[{
					xtype:'radio',
					fieldLabel:'Usuario',
					name:'usuario',
					checked:true,
					id:'rdusuario'
				},{
					xtype:'radio',
					fieldLabel:'Grupo',
					name:'grupo',
					id:'rdgrupo'
				},{
					xtype:'radio',
					fieldLabel:'Sistema',
					name:'sistema',
					id:'rdsistema',
				}]
				
			}]
		});
		panel.render(document.body);
		
		Ext.getCmp('rdusuario').addListener('check',seleccionarUsuario);
		Ext.getCmp('rdsistema').addListener('check',seleccionarSistema);
		Ext.getCmp('rdgrupo').addListener('check',seleccionarGrupo);
		
		Ext.getCmp('btnBuscarUsuario').addListener('click',deshabilitarGrupo);
		Ext.getCmp('btnBuscarGrupo').addListener('click',deshabilitarUsuario);
			
}); //fin

/*******************************************************************
* @Función para seleccionar el usuario como parámetro para ordenar.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
*******************************************************************/
	function seleccionarUsuario()
	{
		ordUsuario = Ext.getCmp('rdusuario').getValue();
		if (ordUsuario)
		{
			Ext.getCmp('rdsistema').setValue(false);
			Ext.getCmp('rdgrupo').setValue(false);
		}
	}	
	
	
/******************************************************************
* @Función para seleccionar el sistema como parámetro para ordenar.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
*****************************************************************/		
	function seleccionarSistema()
	{
		ordSistema = Ext.getCmp('rdsistema').getValue();
		if (ordSistema)
		{
			Ext.getCmp('rdusuario').setValue(false);
			Ext.getCmp('rdgrupo').setValue(false);
		}
	}


/******************************************************************
* @Función para seleccionar el grupo como parámetro para ordenar.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
******************************************************************/				
	function seleccionarGrupo()
	{
		ordGrupo = Ext.getCmp('rdgrupo').getValue();
		if (ordGrupo)
		{
			Ext.getCmp('rdusuario').setValue(false);
			Ext.getCmp('rdsistema').setValue(false);
		}
	}	
	
	
/*****************************************************
* @Función para deshabilitar las opciones del grupo
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
*****************************************************/				
	function deshabilitarGrupo()
	{
		Ext.getCmp('btnBuscarGrupo').disable();
		Ext.getCmp('rdgrupo').disable();
	}
	
	
/******************************************************
* @Función para deshabilitar las opciones del usuario
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
*******************************************************/	
	function deshabilitarUsuario()
	{
		Ext.getCmp('btnBuscarUsuario').disable();
		Ext.getCmp('rdusuario').disable();
	}
	
	
/****************************************************************
* @Función para buscar en el catalogo el usuario seleccionado.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
*****************************************************************/	
	function irBuscarUsuario()
	{
		var arreglotxt = new Array('txtcodusuario','hidnomusuario','hidapeusuario');		
		var arreglovalores = new Array('codusuario','nombre','apellido');				
		objCatusuario = new catalogoUsuario();
		objCatusuario.mostrarCatalogo(panel,'fsbusqueda',arreglotxt, arreglovalores);
	}
	
	
/**************************************************************
* @Función para buscar en el catalogo el sistema seleccionado.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
***************************************************************/		
	function irBuscarSistema()
	{
		var arreglotxt = new Array('txtcodsistema','hidsistema');		
		var arreglovalores = new Array('codsistema','nombre');				
		objCatSistema = new catalogoSistema();
		objCatSistema.mostrarCatalogoSistema(panel,'fsbusqueda',arreglotxt, arreglovalores);
	
	}
	
	
/*************************************************************
* @Función para buscar en el catalogo el grupo seleccionado.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
*************************************************************/		
	function irBuscarGrupo()
	{
		var arreglotxt = new Array('txtcodgrupo','hidgrupo');		
		var arreglovalores = new Array('codgrupo','nombre');	
		objCatGrupo = new catalogoGrupo();
		objCatGrupo.mostrarCatalogo(panel,'fsbusqueda',arreglotxt, arreglovalores);
	}
	
/**************************************
* @Función para limpiar los campos
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
**************************************/			
	function irCancelar()
	{
		Ext.getCmp('txtcodusuario').setValue('');
		Ext.getCmp('txtcodgrupo').setValue('');
		Ext.getCmp('hidgrupo').setValue('');
		Ext.getCmp('txtcodsistema').setValue('');
		Ext.getCmp('hidsistema').setValue('');
		Ext.getCmp('hidnomusuario').setValue('');
		Ext.getCmp('hidapeusuario').setValue('');
		Ext.getCmp('rdusuario').enable();
		Ext.getCmp('rdgrupo').enable();
		Ext.getCmp('rdusuario').setValue(true);			
		Ext.getCmp('btnBuscarGrupo').enable();
		Ext.getCmp('btnBuscarUsuario').enable();
	}


/******************************************
* @Función para mostrar el reporte 
* @de los permisos por usuario y sistema.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
*****************************************/		
	function irImprimir()
	{
		continuar = false;
		if (validarObjetos('txtcodsistema','3','novacio')=='0')
		{
			Ext.Msg.alert('Mensaje','Seleccione el sistema');
		}
		else
		{
			//if (validarObjetos('txtcodusuario','20','novacio')=='0' && validarObjetos('txtcodgrupo','5','novacio')=='0')
			if (Ext.getCmp('txtcodusuario').getValue()=='' && Ext.getCmp('txtcodgrupo').getValue()=='')
			{
				Ext.MessageBox.alert('Mensaje','Seleccione el usuario o grupo');
			}
			else
			{
				if (Ext.getCmp('rdusuario').getValue()==true)
				{
					orden = 'codusuario';	
				}
				else
				{
					if (Ext.getCmp('rdgrupo').getValue()==true)
					{
						orden = 'codgrupo';
					}
					else
					{
						orden = 'codsistema';
					}
				}
				if (Ext.getCmp('txtcodusuario').getValue()=='')
				{
					codusuario = '--------------------';
					continuar = true;
					var objdata ={
					'operacion': 'permisos',
					'codusuario': codusuario,
					'codgrupo':   Ext.getCmp('txtcodgrupo').getValue(),
					'codsistema': Ext.getCmp('txtcodsistema').getValue(),
					'orden':	  orden,
					'sistema': sistema,
					'vista': vista
					}
				}
				else
				{
					codgrupo = '-----';
					continuar = true;
					var objdata ={
						'operacion': 'permisos',
						'codusuario': Ext.getCmp('txtcodusuario').getValue(),
						'codgrupo':   codgrupo,
						'codsistema': Ext.getCmp('txtcodsistema').getValue(),
						'orden':	  orden,
						'sistema': sistema,
						'vista': vista
					}
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
						Ext.MessageBox.alert('Error', result.responseText); 
					} 
					});	
				}
			} 
		}		   
	}
