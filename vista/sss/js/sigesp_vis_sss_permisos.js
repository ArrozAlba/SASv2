/*******************************************************************************
* @Reporte de permisos.
* @Archivo javascript el cual contiene los componentes del reporte de permisos.
* @versión: 1.0      
* @creado: 26/08/2008
* @autor: Ing. Gusmary Balza 
************************************
* @fecha modificacion
* @autor 
* @descripcion
*******************************************************************************/
ruta =  '../../controlador/sss/sigesp_ctr_sss_reportes.php'; 
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
					name:'nombre del grupo',
					readOnly:true,
					id:'txtnombre',
					width:150
				},{
					xtype:'button',
					id:'btnBuscarGrupo',
					handler: irBuscarGrupo,
					iconCls: 'bmenubuscar',
					tooltip: 'Buscar un grupo',
					style:'position:absolute;left:275px;top:62px',
					width:50
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
					style:'position:absolute;left:175px;top:89px',
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
					fieldLabel:'Sistema',
					name:'sistema',
					id:'rdsistema',
					checked:true
				},{	
					xtype:'radio',
					fieldLabel:'Usuario',
					name:'usuario',					
					id:'rdusuario'
				},{
					xtype:'radio',
					fieldLabel:'Grupo',
					name:'grupo',
					id:'rdgrupo'				
				}]
				
			}]
		});
		panel.render(document.body);
		
		Ext.getCmp('rdusuario').addListener('check',seleccionarUsuario);
		Ext.getCmp('rdsistema').addListener('check',seleccionarSistema);
		Ext.getCmp('rdgrupo').addListener('check',seleccionarGrupo);
		
		//Ext.getCmp('btnBuscarUsuario').addListener('click',deshabilitarGrupo);
		//Ext.getCmp('btnBuscarGrupo').addListener('click',deshabilitarUsuario);
			
}); //fin


/*****************************************************************************
* @Función para seleccionar el usuario como parámetro para ordenar.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
************************************
* @fecha modificacion
* @autor 
* @descripcion
*******************************************************************************/
	function seleccionarUsuario()
	{
		ordUsuario = Ext.getCmp('rdusuario').getValue();
		if (ordUsuario)
		{
			Ext.getCmp('rdsistema').setValue(false);
			Ext.getCmp('rdgrupo').setValue(false);
		}
	}	
	
	
/******************************************************************************
* @Función para seleccionar el sistema como parámetro para ordenar.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
************************************
* @fecha modificacion
* @autor 
* @descripcion
*******************************************************************************/		
	function seleccionarSistema()
	{
		ordSistema = Ext.getCmp('rdsistema').getValue();
		if (ordSistema)
		{
			Ext.getCmp('rdusuario').setValue(false);
			Ext.getCmp('rdgrupo').setValue(false);
		}
	}


/******************************************************************************
* @Función para seleccionar el grupo como parámetro para ordenar.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
************************************
* @fecha modificacion
* @autor 
* @descripcion
*******************************************************************************/				
	function seleccionarGrupo()
	{
		ordGrupo = Ext.getCmp('rdgrupo').getValue();
		if (ordGrupo)
		{
			Ext.getCmp('rdusuario').setValue(false);
			Ext.getCmp('rdsistema').setValue(false);
		}
	}	

	
/******************************************************************************
* @Función para buscar en el catalogo el usuario seleccionado.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
************************************
* @fecha modificacion
* @autor 
* @descripcion
********************************************************************************/	
	function irBuscarUsuario()
	{
		var arreglotxt = new Array('txtcodusuario','hidnomusuario','hidapeusuario');		
		var arreglovalores = new Array('codusu','nomusu','apeusu');				
		objCatusuario = new catalogoUsuario();
		objCatusuario.mostrarCatalogo(panel,'fsbusqueda',arreglotxt, arreglovalores);
	}
	
	
/********************************************************************************
* @Función para buscar en el catalogo el sistema seleccionado.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
************************************
* @fecha modificacion
* @autor 
* @descripcion
*********************************************************************************/		
	function irBuscarSistema()
	{
		var arreglotxt = new Array('txtcodsistema','hidsistema');		
		var arreglovalores = new Array('codsis','nomsis');				
		objCatSistema = new catalogoSistema();
		objCatSistema.mostrarCatalogoSistema(arreglotxt, arreglovalores);
	
	}
	
	
/*******************************************************************************
* @Función para buscar en el catalogo el grupo seleccionado.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
************************************
* @fecha modificacion
* @autor 
* @descripcion
*******************************************************************************/		
	function irBuscarGrupo()
	{
		var arreglotxt = new Array('txtnombre');		
		var arreglovalores = new Array('nomgru');	
		objCatGrupo = new catalogoGrupo();
		objCatGrupo.mostrarCatalogo(arreglotxt, arreglovalores);
	}
	
	
/********************************************************************************
* @Función para limpiar los campos
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
************************************
* @fecha modificacion
* @autor 
* @descripcion
********************************************************************************/			
	function irCancelar()
	{
		Ext.getCmp('txtcodusuario').setValue('');
		Ext.getCmp('txtnombre').setValue('');
		Ext.getCmp('txtcodsistema').setValue('');
		Ext.getCmp('hidsistema').setValue('');
		Ext.getCmp('hidnomusuario').setValue('');
		Ext.getCmp('hidapeusuario').setValue('');
		Ext.getCmp('rdusuario').enable();
		Ext.getCmp('rdgrupo').enable();
		Ext.getCmp('rdsistema').setValue(true);			
		Ext.getCmp('btnBuscarGrupo').enable();
		Ext.getCmp('btnBuscarUsuario').enable();
	}


/********************************************************************************
* @Función para mostrar el reporte de los permisos por usuario y sistema.
* @fecha de creación: 27/08/2008
* @autor: Ing. Gusmary Balza.
************************************
* @fecha modificacion
* @autor 
* @descripcion
*******************************************************************************/		
	function irImprimir()
	{
		continuar = false;
		if (Ext.getCmp('txtcodsistema').getValue()=='' && Ext.getCmp('txtcodusuario').getValue()=='' && Ext.getCmp('txtnombre').getValue()=='')
		{
			Ext.Msg.alert('Mensaje','Seleccione el tipo de búsqueda');
		}
		else
		{			
			continuar = true;
			if (Ext.getCmp('rdusuario').getValue()==true)
			{
				orden = 'codusu';	
			}
			else if (Ext.getCmp('rdgrupo').getValue()==true)
			{
				orden = 'nomgru';
			}
			else
			{
				orden = 'codsis';
			}
											
			if (Ext.getCmp('txtcodusuario').getValue()=='')
			{
				codusu = ''; 
			}
			else
			{
				codusu = Ext.getCmp('txtcodusuario').getValue();
			}
			if (Ext.getCmp('txtnombre').getValue()=='')
			{
				nomgru = ''; 
			}
			else
			{
				nomgru = Ext.getCmp('txtnombre').getValue();
			}
			if (Ext.getCmp('txtcodsistema').getValue()=='')
			{
				codsis = ''; 
			}
			else
			{
				codsis = Ext.getCmp('txtcodsistema').getValue();
			}
			
			var objdata ={
				'oper':   'permisos',
				'nomgru': nomgru,
				'codusu': codusu,
				'codsis': codsis,
				'orden':  orden,
				'sistema': sistema,
				'vista': vista
			};
				
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
