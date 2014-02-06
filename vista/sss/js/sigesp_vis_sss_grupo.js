/***********************************************************************************
* @Archivo javascript que incluye tanto los componentes como los eventos asociados 
* a la definición de grupo. 
* @fecha de creación: 28/07/2008
* @autor: Ing. Gusmary Balza
*************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
var cambiar = false;
var panel      = '';
var gridPer     = '';
var gridCons    = '';
var gridNom     = '';
var gridUni     = '';
var gridPre  = '';
var DataStorePer = '';
var DataStoreCons = '';
var DataStoreNom = '';
var DataStoreUni = '';
var DataStoreEstPre = '';
var DatosNuevo   = '';
var DatosNuevoCons   = '';
var DatosNuevoNom   = '';
var DatosNuevoUni   = '';
var DatosNuevoEstPre   = '';
var arrEliminar = new Array();
var arrEliminarPer = new Array();
var arrEliminarCons = new Array();
var arrEliminarNom = new Array();
var arrEliminarUni = new Array();
var arrEliminarEst = new Array();
var usuarioElim = '';
var personalElim = '';
var constanteElim = '';
var nominaElim = '';
var unidadElim = '';
var estpreElim = '';
var j = 0;
var pantalla   = 'grupo'; //parámetro para el catalogo
var rutaGrupo = '../../controlador/sss/sigesp_ctr_sss_grupo.php';
var RecordDefUsu = '';
var gridUsu   = '';
var dsusuario = '';

var toteliminar = 0;
var datosNuevo={'raiz':[{'codusu':'','nomusu':'','apeusu':''}]};

Ext.onReady
(
	function()
	{
	    Ext.QuickTips.init();
		Ext.form.Field.prototype.msgTarget = 'side';
		var agregar = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregar,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar un usuario al grupo'
		});
		
		var quitar = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitar,
			iconCls: 'bmenuquitar',
        	tooltip: 'Quitar usuario del grupo'
		});
		
		agregarPer = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregarPer,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar personal'
		});		
		quitarPer = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitarPer,
			iconCls: 'bmenuquitar',
        	tooltip: 'Eliminar personal'
		});		
		agregarCons = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregarCons,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar constantes de nómina'
		});		
		quitarCons = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitarCons,
			iconCls: 'bmenuquitar',
        	tooltip: 'Eliminar constantes de nómina'
		});			
		agregarNom = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregarNom,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar nómina'
		});		
		quitarNom = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitarNom,
			iconCls: 'bmenuquitar',
        	tooltip: 'Eliminar nómina'
		});		
		agregarUni = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregarUni,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar unidad ejecutora'
		});		
		quitarUni = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitarUni,
			iconCls: 'bmenuquitar',
        	tooltip: 'Eliminar unidad ejecutora'
		});			
		agregarEstPre = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregarEstPre,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar estructura presupuestaria'
		});
		
		quitarEstPre = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitarEstPre,
			iconCls: 'bmenuquitar',
        	tooltip: 'Eliminar estructura presupuestaria'
		});		 
		
		
		Xpos = ((screen.width/2)-(750/2)); 
		Ypos = ((screen.height/2)-(700/2));
		//Panel con los componentes del formulario
		panel = new Ext.FormPanel({
        labelWidth: 75,
     	title: 'Definición de Grupos',
        bodyStyle:'padding:5px 5px 5px',
		style: 'margin-top:40px;margin-left:'+Xpos+'px',
		width:750,
	  	tbar: [],
        defaults: {width: 230},		   
		items:[{
			    xtype:'fieldset',
				title:'Datos del Grupo',
				id:'fsformgrupo',
    			autoHeight:true,
				autoWidth:true,
				cls :'fondo',	
			    items:[{			   
						xtype:'textfield',
						fieldLabel:'Nombre',
						name:'Nombre del grupo',
						id:'txtnombre',
						disabled: true,
						width:200
					},{
						xtype:'textarea',
						fieldLabel:'Nota',
						name:'Nota',
						id:'txtnota',
						width:400
					}]
				},{
					xtype:'panel',
					autoHeight:true,
					autoWidth:true,
					title:'Usuarios del Grupo',
					tbar: [agregar,quitar],
					contentEl:'grid-usuariogrupo'
				},{
					xtype:'tabpanel',
					border:false,
	            	activeTab:0,
	            	height:150,
	            	width:600,
	            	style: 'margin-top:10px',
	           	 	autoWidth:true,
	            	autoScroll:true,
	            	region:'south',
	            	items:
					[{
	                     contentEl:'pest1',
	                     title: 'Asignar constantes de Nómina',
	                     autoScroll:true
	                 	},{
	                     contentEl:'pest2',
	                     title: 'Asignar Nóminas',
	                     autoScroll:true
	                 	},{
	                     contentEl:'pest3',
	                     title: 'Asignar Unidades Ejecutoras',
	                     autoScroll:true
	                	},{
	                     contentEl:'pest4',
	                     title: 'Asignar Presupuestos',
	                     autoScroll:true                                
	                 	},{
	                     contentEl:'pest5',
	                     title: 'Asignar Tipo de Personal',
	                     autoScroll:true   
                 	}]    	
			}]
		});		
		panel.render(document.body);
	
		obtenerGridPersonal();
		obtenerGridConstantes();
		obtenerGridNominas();
		obtenerGridUnidades();
		obtenerGridPresupuestos();	
		obtenerGridUsuario();
	}
);	//FIN

		
/***********************************************************************************
* @Función para agregar un registro en la grid y llamar al catálogo de usuarios.
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function irAgregar()
	{
		ParamGridTarget = gridUsu;
		var arreglotxt     = new Array('','');		
		var arreglovalores = new Array('codusu','cedusu','nomusu','apeusu','telusu','email','ultingusu','actusu','admusu','nota');
		ObjUsuario      = new catalogoUsuario();
		ObjUsuario.mostrarCatalogo('','',arreglotxt, arreglovalores);
	}			


/***********************************************************************************
* @Función para crear la grid y pasarle los datos del usuario.
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function obtenerGridUsuario()
	{	
		RecordDefUsu = Ext.data.Record.create
		([
			{name: 'codusu'}, 
			{name: 'nomusu'},
			{name: 'apeusu'}
		]);
		
		var DatosNuevo = {'raiz':[{'codusu':'','nomusu':'','apeusu':''}]};	
		dsusuariogrupo =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader({
			root: 'raiz',               
			id: 'id'   
			},
				  RecordDefUsu
			),
			data: DatosNuevo
			});
		
		gridUsu = new Ext.grid.GridPanel({
				width:700,
				autoScroll:true,
				border:true,
				ds: dsusuariogrupo,
				cm: new Ext.grid.ColumnModel([
					{header: 'Código', width: 100, sortable: true,   dataIndex: 'codusu'},
					{header: 'Nombre', width: 200, sortable: true, dataIndex: 'nomusu'},
					{header: 'Apellido', width: 200, sortable: true, dataIndex: 'apeusu'}
				]),
				viewConfig: {
								forceFit:true
							},
				autoHeight:true,
				stripeRows: true
		});
		gridUsu.render('grid-usuariogrupo');
	}
	
		
/***********************************************************************************
* @Función para confirmar eliminar un registro de la grid de usuarios.
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irQuitar()
	{
		var claveseleccionada = gridUsu.selModel.selections.keys;
		if(claveseleccionada.length > 0)
		{
			Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarRegistro);
		} 
		else 
		{
			Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
		}
	}
	
	
/***********************************************************************************
* @Función para eliminar un registro de la grid de usuarios.
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function borrarRegistro(btn) 
	{
		if (btn=='yes') 
		{
			var filaseleccionada = gridUsu.getSelectionModel().getSelected();
			if (filaseleccionada)
			{
				usuarioElim    = gridUsu.getSelectionModel().getSelected().get('codusu');
				arrEliminar[toteliminar] = usuarioElim;
				toteliminar++;
				gridUsu.store.remove(filaseleccionada);
				Ext.Msg.alert('Exito','Registro eliminado');				
			}
		} 
	}
	
	
/********************************************************************************************
*Función que obtiene las constantes de nomina para mostrar en la grid.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
*********************************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
**********************************************************************************************/	
	function obtenerGridConstantes()
	{
		RecordDefCons = Ext.data.Record.create
		([
			{name: 'codemp'}, 
			{name: 'codnom'}, 
			{name: 'codcons'},
			{name: 'nomcon'},
		]);
		
		var DatosNuevoCons = {'raiz':[{'codemp':'','codnom':'','codcons':'','nomcon':''}]};	
		DataStoreCons =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevoCons),
		reader: new Ext.data.JsonReader({
		    root: 'raiz',               
		    id: 'id'   
		    },
	            RecordDefCons
		    ),
			data: DatosNuevoCons
		});				
		gridCons = new Ext.grid.GridPanel({
			id:'Cons',	
			width:730,
			autoScroll:true,
		    border:true,
		    ds:DataStoreCons,
		    tbar:[agregarCons,quitarCons],
		    cm: new Ext.grid.ColumnModel([
		    new Ext.grid.CheckboxSelectionModel(),
		    	{header: 'Código de la Nómina', width: 100, sortable: true, dataIndex: 'codnom'},
				{header: 'Código', width: 150, sortable: true, dataIndex: 'codcons'},
	            {header: 'Denominación', width: 400, sortable: true, dataIndex: 'nomcon'},
			]),
			sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
		                      viewConfig:{
		                      forceFit:true
		                      },
			autoHeight:true,
			stripeRows: true
		});				   		   
		gridCons.render('pest1');		
	}


/************************************************************************************************
*Función que obtiene las nominas para mostrar en la grid.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
**************************************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
***************************************************************************************************/	
	function obtenerGridNominas()
	{
		RecordDefNom = Ext.data.Record.create
		([
			{name: 'codemp'}, 
			{name: 'codnom'}, 
			{name: 'desnom'},
		]);		
		var DatosNuevoNom = {'raiz':[{'codemp':'','codnom':'','desnom':''}]};	
		DataStoreNom =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevoNom),
		reader: new Ext.data.JsonReader({
		    root: 'raiz',               
		    id: 'id'   
		    },
	            RecordDefNom
		    ),
			data: DatosNuevoNom
		});				
		gridNom = new Ext.grid.GridPanel({
			id:'Nom',	
			width:730,
			autoScroll:true,
		    border:true,
		    ds:DataStoreNom,
		    tbar:[agregarNom,quitarNom],
		    cm: new Ext.grid.ColumnModel([
		     new Ext.grid.CheckboxSelectionModel(),
				{header: 'Código', width: 200, sortable: true, dataIndex: 'codnom'},
				{header: 'Denominación', width: 500, sortable: true, dataIndex: 'desnom'},
			]),
			sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
		                      viewConfig:{
		                      forceFit:true
		                      },
			autoHeight:true,
			stripeRows: true
		});				   		   
		gridNom.render('pest2');		
	}


/******************************************************************************************
*Función que obtiene las unidades ejecutoras para mostrar en la grid.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
*******************************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
********************************************************************************************/	
	function obtenerGridUnidades()
	{
		RecordDefUni = Ext.data.Record.create
		([
			{name: 'codemp'}, 
			{name: 'codsis'}, 
			{name: 'coduniadm'}, 
			{name: 'denuniadm'},
		]);		
		var DatosNuevoUni = {'raiz':[{'codemp':'','codsis':'SEP','coduniadm':'','denuniadm':''}]};	
		DataStoreUni =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevoUni),
		reader: new Ext.data.JsonReader({
		    root: 'raiz',               
		    id: 'id'   
		    },
	            RecordDefUni
		    ),
			data: DatosNuevoUni
		});		
		
		var datosSistemas={'raiz':[{'codsis':'SEP','nomsis':'Solicitud de Ejecución Presupuestaria'},
								   {'codsis':'SOC','nomsis':'Ordenes de Compra'},{'codsis':'CXP','nomsis':'Cuentas por Pagar'}]};
		
		record = Ext.data.Record.create([
				{name: 'codsis'},     
				{name: 'nomsis'}
		]);					
		dssistema =  new Ext.data.Store({
				proxy: new Ext.data.MemoryProxy(datosSistemas),
				reader: new Ext.data.JsonReader(
				{
					root: 'raiz',               
					id: 'id'   
				},
				record
				),
				data: datosSistemas			
			 });	
		
		
		gridUni = new Ext.grid.EditorGridPanel({
			id:'Uni',	
			width:730,
			autoScroll:true,
		    border:true,
		    ds:DataStoreUni,
		    tbar:[agregarUni,quitarUni],
		    cm: new Ext.grid.ColumnModel([
		    new Ext.grid.CheckboxSelectionModel(),
				{header: 'Sistema', width: 100, sortable: true, dataIndex: 'codsis',editor: new Ext.form.ComboBox({
																						name:'sistema',
																						id:'cmbsistema',
																						emptyText:'Seleccione',
																						displayField:'codsis',
																						valueField:'codsis',
																						typeAhead: true,
																						mode: 'local',
																						triggerAction: 'all',
																						store: dssistema
																						})},							  
				{header: 'Código', width: 200, sortable: true, dataIndex: 'coduniadm'},
				{header: 'Denominación', width: 500, sortable: true, dataIndex: 'denuniadm'},
			]),
			sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
		                      viewConfig:{
		                      forceFit:true
		                      },
			autoHeight:true,
			stripeRows: true
		});				   		   
		gridUni.render('pest3');
	}


/*********************************************************************************
*Función que obtiene las estructuras presupuestarias para mostrar en la grid.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
**********************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
*********************************************************************************/	
	function obtenerGridPresupuestos()
	{
		RecordDefPre = Ext.data.Record.create
		([
			{name: 'codest'}, 
			{name: 'codcompleto'}, 
			{name: 'codestpro1'}, 
			{name: 'codestpro2'}, 
			{name: 'codestpro3'}, 
			{name: 'codestpro4'}, 
			{name: 'codestpro5'}, 
			{name: 'denestpro5'},
			{name: 'nombre'}
		]);		
		var DatosNuevoPre = {'raiz':[{'codest':'','codcompleto':'','nombre':''}]};	
		DataStorePre =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevoPre),
		reader: new Ext.data.JsonReader({
		    root: 'raiz',               
		    id: 'id'   
		    },
				RecordDefPre
		    ),
			data: DatosNuevoPre
	    });				
		gridPre = new Ext.grid.GridPanel({
			id:'Pre',	
			width:730,
			autoScroll:true,
	        border:true,
	        ds:DataStorePre,
	        tbar:[agregarEstPre,quitarEstPre],
	        cm: new Ext.grid.ColumnModel([
	         new Ext.grid.CheckboxSelectionModel(),
	       		{header: 'Código', width: 200, sortable: true, dataIndex: 'codest'},
	       		{header: 'Código de la Estructura', width: 30, sortable: true,hidden:true, dataIndex: 'codcompleto'},
	       		{header: 'Denominación', width: 500, sortable: true, dataIndex: 'nombre'}
			]),
	
				sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
	                        viewConfig:{
	                        forceFit:true
	                        },
				autoHeight:true,
				stripeRows: true
		});				   		   
		gridPre.render('pest4');	
	}


/**********************************************************************************
*Función que obtiene los tipos de personal para mostrar en la grid.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
**********************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
************************************************************************************/	
	function obtenerGridPersonal()
	{
		RecordDefPer = Ext.data.Record.create
		([
			{name: 'codemp'}, 
			{name: 'codtippersss'}, 
			{name: 'dentippersss'},
		]);		
		var DatosNuevo={'raiz':[{'codemp':'','codtippersss':'','dentippersss':''}]};	
		DataStorePer =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader({
		    root: 'raiz',               
		    id: 'id'   
		    },
	            RecordDefPer
		    ),
			data: DatosNuevo
		});		
		gridPer = new Ext.grid.GridPanel({
			id:'Pers',	
			width:730,
			autoScroll:true,
	        border:true,
	        ds:DataStorePer,
	        tbar:[agregarPer,quitarPer],
	        cm: new Ext.grid.ColumnModel([
	         new Ext.grid.CheckboxSelectionModel(),
				{header: 'Código', width: 200, sortable: true, dataIndex: 'codtippersss'},
	            {header: 'Denominación', width: 500, sortable: true, dataIndex: 'dentippersss',editor: new Ext.form.TextField({allowBlank: false})},
			]),
	
			sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
	                      viewConfig:{
	                      forceFit:true
	                      },
			autoHeight:true,
			stripeRows: true
	    });				   		   
		gridPer.render('pest5');	
	}


/***********************************************************************************
*Función que llama al catalogo de los tipos de personal.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
************************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
************************************************************************************/	
	function irAgregarPer()
	{	
		objCatPersonal = new catalogoPersonal();
		objCatPersonal.mostrarCatalogoPersonal(panel,'','', '');	
	
	}


/***********************************************************************************
*Función que selecciona un registro de la grid para eliminarlo.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
***********************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
************************************************************************************/	
	function irQuitarPer()
	{
		var personal = gridPer.selModel.selections.keys;
		if(personal.length > 0)
		{
			Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarPersonal);
		} 
		else 
		{
			Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
		}			
	}


/**********************************************************************************
*Función que elimina un registro de la grid.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
***********************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
***********************************************************************************/	
	function borrarPersonal(btn)
	{
		if (btn=='yes') 
		{
			var filas = gridPer.getSelectionModel().getSelections();
			for (j=0;j<=filas.length;j++)
			{			
				personalElim      = filas[j].get('codtippersss');
				arrEliminarPer[j] = personalElim;				
				gridPer.store.remove(filas[j]);
				Ext.MessageBox.alert('Mensaje', 'Registro eliminado con éxito');
				gridPer.getSelectionModel().clearSelections();
			}
		}	
	}


/*******************************************************************************************
*Función que llama al catalogo de las constantes de nomina.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
********************************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
********************************************************************************************/	
	function irAgregarCons()
	{	
		objCatConstantes = new catalogoConstante();
		objCatConstantes.mostrarCatalogoConstante(panel,'','', '');	
	}
	
	
/*************************************************************************************
*Función que selecciona un registro de la grid para eliminarlo.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
***************************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
****************************************************************************************/		
	function irQuitarCons()
	{
		var constante = gridCons.selModel.selections.keys;
		if(constante.length > 0)
		{
			Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarConstante);
		} 
		else 
		{
			Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
		}			
	}


/***************************************************************************************
*Función que elimina un registro de la grid.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
*****************************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
******************************************************************************************/	
	function borrarConstante(btn)
	{
		if (btn=='yes') 
		{
			var filas = gridCons.getSelectionModel().getSelections();
			for (j=0;j<=filas.length;j++)		
			{					
				constanteElim   = filas[j].get('codcons');;
				arrEliminarCons[j] = constanteElim;
				gridCons.store.remove(filas[j]);
				Ext.MessageBox.alert('Mensaje', 'Registro eliminado con éxito');
				gridCons.getSelectionModel().clearSelections();
			}
		}	
	}		


/**************************************************************************************
*Función que llama al catalogo de las nominas.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
**************************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
***************************************************************************************/		
	function irAgregarNom()
	{	
		objCatNomina = new catalogoNomina();
		objCatNomina.mostrarCatalogoNomina(panel,'','', '');	
	}


/**************************************************************************************
*Función que selecciona un registro de la grid para eliminarlo.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
***************************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
**************************************************************************************/		
	function irQuitarNom()
	{
		var nomina = gridNom.selModel.selections.keys;
		if(nomina.length > 0)
		{
			Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarNomina);
		} 
		else 
		{
			Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
		}			
	}


/************************************************************************************
*Función que elimina un registro de la grid.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
*************************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
*************************************************************************************/	
	function borrarNomina(btn)
	{
		if (btn=='yes') 
		{
			var filas = gridNom.getSelectionModel().getSelections();
			for (j=0;j<=filas.length;j++)		
			{			
				nominaElim   = filas[j].get('codnom');
				arrEliminarNom[j] = nominaElim;
				gridNom.store.remove(filas[j]);
				Ext.MessageBox.alert('Mensaje', 'Registro eliminado con éxito');
				gridNom.getSelectionModel().clearSelections();
			}
		}	
	}		

	
/***********************************************************************************
*Función que llama al catalogo de las unidades ejecutoras.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
***********************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
**********************************************************************************/		
	function irAgregarUni()
	{	
		objCatUnidad = new catalogoUnidad();
		objCatUnidad.mostrarCatalogoUnidad(panel,'','', '');
	}
	
	
/***************************************************************************************
*Función que selecciona un registro de la grid para eliminarlo.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
*****************************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
*******************************************************************************************/		
	function irQuitarUni()
	{
		var unidad = gridUni.selModel.selections.keys;
		if(unidad.length > 0)
		{
			Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarUnidad);
		} 
		else 
		{
			Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
		}			
	}


/***************************************************************************************
*Función que elimina un registro de la grid.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
***********************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
*******************************************************************************************/	
	function borrarUnidad(btn)
	{
		if (btn=='yes') 
		{
			var filas = gridUni.getSelectionModel().getSelections();
			for (j=0;j<filas.length;j++)		
			{				
				unidadElim   = filas[j];
				arrEliminarUni[j] = unidadElim;
				gridUni.store.remove(filas[j]);
				Ext.MessageBox.alert('Mensaje', 'Registro eliminado con éxito');
				gridUni.getSelectionModel().clearSelections();
			}
		}	
	}		


/************************************************************************************
*Función que llama al catalogo de las estructuras presupuestarias.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
************************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
************************************************************************************/	
	function irAgregarEstPre()
	{	
		objCatEstPre = new catalogoEstPre();
		objCatEstPre.mostrarCatalogoEstPre(panel,'','', '');
	}
	
	
/************************************************************************************
*Función que selecciona un registro de la grid para eliminarlo.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
*************************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
**************************************************************************************/		
	function irQuitarEstPre()
	{
		var estpre = gridPre.selModel.selections.keys;
		if(estpre.length > 0)
		{
			Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarEstPre);
		} 
		else 
		{
			Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
		}			
	}


/************************************************************************************
*Función que elimina un registro de la grid.
*@parámetros: 
*@retorna: 
*@fecha de creación:  09/10/2008
*@Autor: Gusmary Balza.	
*************************************************************************************
* @fecha modificacion
* @autor 
* @descripcion
*************************************************************************************/	
	function borrarEstPre(btn)
	{
		if (btn=='yes') 
		{
			var filas = gridPre.getSelectionModel().getSelections();
			for (j=0;j<=filas.length;j++)		
			{					
				estpreElim   = filas[j].get('codest');
				arrEliminarEst[j] = estpreElim;
				gridPre.store.remove(filas[j]);
				Ext.MessageBox.alert('Mensaje', 'Registro eliminado con éxito');
				gridPre.getSelectionModel().clearSelections();
			}
		}	
	}	
		
	
/***********************************************************************************
* @Limpiar campos del formulario
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function limpiarCampos() 
	{		 
		Ext.getCmp('txtnombre').setValue('');
		Ext.getCmp('txtnota').setValue('');
		gridUsu.store.removeAll();
		gridUsu.store.loadData(datosNuevo);
		gridUsu.store.commitChanges();
		
		DatosNuevoPer  = {'raiz':[{'codemp':'','codtippersss':'','dentippersss':''}]};
		DatosNuevoCons = {'raiz':[{'codemp':'','codnom':'','codcons':'','nomcon':''}]};	
		DatosNuevoNom  = {'raiz':[{'codemp':'','codnom':'','desnom':''}]};
		DatosNuevoUni  = {'raiz':[{'codemp':'','coduniadm':'','denuniadm':''}]};
		DatosNuevoPre  = {'raiz':[{'codest':'','denestpro5':''}]};
		gridPer.store.removeAll();		
		DataStorePer.loadData(DatosNuevoPer);
		gridPer.store.commitChanges();
		gridCons.store.removeAll();
		DataStoreCons.loadData(DatosNuevoCons);
		gridCons.store.commitChanges();
		gridNom.store.removeAll();
		DataStoreNom.loadData(DatosNuevoNom);
		gridNom.store.commitChanges();
		gridUni.store.removeAll();
		DataStoreUni.loadData(DatosNuevoUni);
		gridUni.store.commitChanges();
		gridPre.store.removeAll();
		DataStorePre.loadData(DatosNuevoPre);
		gridPre.store.commitChanges();
				
		
	}
	
		
/***********************************************************************************
* @Función que limpia los campos y asigna un nuevo código
* @parametros: 
* @retorno: 
* @fecha de creación: 15/08/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irNuevo()
	{
		limpiarCampos();
		Ext.getCmp('txtnombre').enable();		
		arrEliminar = new Array();
		toteliminar=0;
		cambiar = false;
	}


/***********************************************************************************
* @Función que guarda o actualiza los datos de un grupo.
* @parametros: 
* @retorno: 
* @fecha de creación: 08/07/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación: 03/11/2008
* @descripción: Se agregaron los campos para guardar los permisos internos
* @autor: Ing. Gusmary Balza.
***********************************************************************************/
	function irGuardar()
	{
		valido=true;
		if((!tbnuevo)&&(!cambiar))
		{
			valido=false;
			Ext.MessageBox.alert('Error','No tiene permiso para Incluir.');
		}
		if((!tbactualizar)&&(cambiar))
		{
			valido=false;
			Ext.MessageBox.alert('Error','No tiene permiso para Modificar.');
		}
		if ((validarObjetos('txtnombre','60','novacio|alfanumerico')!='0' && validarObjetos('txtnota','5000','alfanumerico')!='0') && (valido))
		{   
			if (!cambiar)
			{
				evento ='incluir';
			}
			else
			{	
				evento ='actualizar';			
			}
			
			obtenerMensaje('procesar','','Guardando Datos');
						 	
			var cadenaJson = "{'oper': evento,'sistema': sistema,'vista': vista,'nomgru': '"+Ext.getCmp('txtnombre').getValue()+"','nota': '"+Ext.getCmp('txtnota').getValue()+"'";				
			arrAdmin = gridUsu.store.getModifiedRecords();
			cadenaJson=cadenaJson+ ",datosAdmin:[";
			total = arrAdmin.length;
			if (total>0)
			{	
				for (i=0; i < total; i++)
				{
					if (i==0)
					{
						cadenaJson = cadenaJson +"{'codusu':'"+ arrAdmin[i].get('codusu')+ "'}";
					}
					else
					{
						cadenaJson = cadenaJson +",{'codusu':'"+ arrAdmin[i].get('codusu')+ "'}";
					}
				}			
			}
			cadenaJson = cadenaJson + ']';
			
			cadenaJson=cadenaJson+ ',datosEliminar:[';
			total = arrEliminar.length;
			if (total>0)
			{
				for (i=0; i < total; i++)
				{
					if (i==0)
					{
						cadenaJson = cadenaJson +"{'codusu':'"+ arrEliminar[i]+ "'}";
					}
					else
					{
						cadenaJson = cadenaJson +",{'codusu':'"+ arrEliminar[i]+ "'}";
					}
				}			
			}
			cadenaJson = cadenaJson + ']';
			
			arrPer = gridPer.store.getModifiedRecords();
			cadenaJson = cadenaJson+ ",datosPer:[";
			total = arrPer.length;
			if (total>0)
			{				
				for (i=0; i < total; i++)
				{
					if (i==0)
					{
						cadenaJson = cadenaJson +"{'nomgru':'"+Ext.getCmp('txtnombre').getValue()+
						"','codsis':'SNO','codintper':'"+ arrPer[i].get('codtippersss')+ "','enabled':1}";
					}
					else
					{
						cadenaJson = cadenaJson +",{'nomgru':'"+Ext.getCmp('txtnombre').getValue()+
						"','codsis':'SNO','codintper':'"+ arrPer[i].get('codtippersss')+ "','enabled':1}";
					}
				}				
			}
			cadenaJson = cadenaJson + "]";
			
			cadenaJson = cadenaJson+ ",datosEliminarPer:[";
			total = arrEliminarPer.length;
			if (total>0)
			{
				for (i=0; i < total; i++)
				{
					if (i==0)
					{
						cadenaJson = cadenaJson +"{'nomgru':'"+nomgru+
						"','codsis':'SNO','codintper':'"+ arrEliminarPer[i]+ "'}";
					}
					else
					{
						cadenaJson = cadenaJson +",{'nomgru':'"+nomgru+
						"','codsis':'SNO','codintper':'"+ arrEliminarPer[i]+ "'}";
					}
				}				
			}		
			cadenaJson = cadenaJson + "]";			
					
			arrCons = gridCons.store.getModifiedRecords();
			cadenaJson = cadenaJson+ ",datosCons:[";
			total = arrCons.length;
			if (total>0)
			{				
				for (i=0; i < total; i++)
				{
					if (i==0)
					{
						cadenaJson = cadenaJson +"{'nomgru':'"+Ext.getCmp('txtnombre').getValue()+
						"','codsis':'SNO','codintper':'"+ arrCons[i].get('codnom')+ "-"+arrCons[i].get('codcons')+"','enabled':1}";
					}
					else
					{
						cadenaJson = cadenaJson +",{'nomgru':'"+Ext.getCmp('txtnombre').getValue()+
						"','codsis':'SNO','codintper':'"+ arrCons[i].get('codnom')+ "-"+arrCons[i].get('codcons')+"','enabled':1}";
					}
				}				
			}
			cadenaJson = cadenaJson + "]";
			
			cadenaJson = cadenaJson+ ",datosEliminarCons:[";
			total = arrEliminarCons.length;
			for (i=0; i<total; i++)
			{
				if (i == 0)
				{
					cadenaJson = cadenaJson +"{'nomgru':'"+nomgru+
				"','codsis':'SNO','codintper':'"+ arrEliminarCons[i]+ "'}";
				}
				else
				{
					cadenaJson = cadenaJson +",{'nomgru':'"+nomgru+
				"','codsis':'SNO','codintper':'"+ arrEliminarCons[i]+ "'}";
				}
			}
			cadenaJson = cadenaJson+"]";		
			
			arrNom = gridNom.store.getModifiedRecords();
			cadenaJson = cadenaJson+ ",datosNom:[";
			total = arrNom.length;
			if (total>0)
			{	
				
				for (i=0; i < total; i++)
				{
					if (i==0)
					{
						cadenaJson = cadenaJson +"{'nomgru':'"+Ext.getCmp('txtnombre').getValue()+
						"','codsis':'SNO','codintper':'"+ arrNom[i].get('codnom')+ "','enabled':1}";
					}
					else
					{
						cadenaJson = cadenaJson +",{'nomgru':'"+Ext.getCmp('txtnombre').getValue()+
						"','codsis':'SNO','codintper':'"+ arrNom[i].get('codnom')+ "','enabled':1}";
					}
				}				
			}
			cadenaJson = cadenaJson + "]";	
					
			total = arrEliminarNom.length;
			cadenaJson = cadenaJson+ ",datosEliminarNom:[";
			for (i=0; i<total; i++)
			{
				if (i == 0)
				{
					cadenaJson = cadenaJson +"{'nomgru':'"+nomgru+
				"','codsis':'SNO','codintper':'"+ arrEliminarNom[i]+ "'}";
				}
				else
				{
					cadenaJson = cadenaJson +",{'nomgru':'"+nomgru+
				"','codsis':'SNO','codintper':'"+ arrEliminarNom[i]+ "'}";
				}
			}
			cadenaJson = cadenaJson + "]";
			
			arrUni = gridUni.store.getModifiedRecords();
			cadenaJson = cadenaJson+ ",datosUni:[";
			total = arrUni.length;
			if (total>0)
			{				
				for (i=0; i < total; i++)
				{
					if (i==0)
					{
						cadenaJson = cadenaJson +"{'nomgru':'"+Ext.getCmp('txtnombre').getValue()+
						"','codsis':'"+ arrUni[i].get('codsis')+ "','codintper':'"+ arrUni[i].get('coduniadm')+ "','enabled':1}";
					}
					else
					{
						cadenaJson = cadenaJson +",{'nomgru':'"+Ext.getCmp('txtnombre').getValue()+
						"','codsis':'"+ arrUni[i].get('codsis')+ "','codintper':'"+ arrUni[i].get('coduniadm')+ "','enabled':1}";
					}
				}				
			}
			cadenaJson = cadenaJson + "]";		
				
			total = arrEliminarUni.length;
			cadenaJson = cadenaJson+ ",datosEliminarUni:[";
			for (i=0; i<total; i++)
			{
				if (i == 0)
				{
					cadenaJson = cadenaJson +"{'nomgru':'"+nomgru+
				"','codsis':'"+ arrEliminarUni[i].get('codsis')+ "','codintper':'"+ arrEliminarUni[i].get('coduniadm')+ "'}";
				}
				else
				{
					cadenaJson = cadenaJson +",{'nomgru':'"+nomgru+
				"','codsis':'"+ arrEliminarUni[i].get('codsis')+ "','codintper':'"+ arrEliminarUni[i].get('coduniadm')+ "'}";
				}
			}
			cadenaJson = cadenaJson + "]";
			
			arrEstPre = gridPre.store.getModifiedRecords();
			cadenaJson = cadenaJson+ ",datosEstPre:[";
			total = arrEstPre.length;
			if (total>0)
			{				
				for (i=0; i < total; i++)
				{
					if (i==0)
					{	
						cadenaJson = cadenaJson +"{'nomgru':'"+Ext.getCmp('txtnombre').getValue()+
						"','codsis':'SPG','codintper':'"+ arrEstPre[i].get('codcompleto')+ "','enabled':1}";
					}
					else
					{
						cadenaJson = cadenaJson +",{'nomgru':'"+Ext.getCmp('txtnombre').getValue()+
						"','codsis':'SPG','codintper':'"+ arrEstPre[i].get('codcompleto')+ "','enabled':1}";
					}
				}			
			}			
			cadenaJson = cadenaJson + "]";	
					
			total = arrEliminarEst.length;
			cadenaJson = cadenaJson+ ",datosEliminarPre:[";
			for (i=0; i<total; i++)
			{
				if (i == 0)
				{
					cadenaJson = cadenaJson +"{'nomgru':'"+nomgru+
				"','codsis':'SPG','codintper':'"+ arrEliminarEst[i]+ "'}";
				}
				else
				{
					cadenaJson = cadenaJson +",{'nomgru':'"+nomgru+
				"','codsis':'SPG','codintper':'"+ arrEliminarEst[i]+ "'}";
				}
			}
			cadenaJson = cadenaJson + ']}';
			objdata= eval('(' + cadenaJson + ')');	
			objdata=JSON.stringify(objdata);
			parametros = 'objdata='+objdata; 
			Ext.Ajax.request({
			url : rutaGrupo,
			params : parametros,
			method: 'POST',
			success: function (resultado, request)
			{ 
				datos = resultado.responseText;
				Ext.Msg.hide();
				var datajson = eval('(' + datos + ')');
				if (datajson.raiz.valido==true)
				{	
					Ext.MessageBox.alert('Mensaje', datajson.raiz.mensaje);
					irNuevo();  
				}
				else
				{
					Ext.MessageBox.alert('Error', datajson.raiz.mensaje);
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


/***********************************************************************************
*  @Función que llama al catalogo para mostrar los datos de los grupos.
* @parametros: 
* @retorno: 
* @fecha de creación: 09/07/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irBuscar()
	{
		var arreglotxt     = new Array('txtnombre','txtnota');		
		var arreglovalores = new Array('nomgru','nota');			
		objCatGrupo = new catalogoGrupo();
		objCatGrupo.mostrarCatalogo(arreglotxt, arreglovalores);
		Ext.getCmp('txtnombre').disable();
		cambiar = true;
	}
	
	
/***********************************************************************************
* @Función que elimina un grupo seleccionado.
* @parametros: 
* @retorno: 
* @fecha de creación: 08/07/2008. 
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irEliminar()
	{
		var Result;
		Ext.MessageBox.confirm('Confirmar', '¿Desea eliminar este registro?', Result);
		function Result(btn)
		{
			if(btn=='yes')
			{ 
				if (validarObjetos('txtnombre','60','novacio')=='0')
				{					
					Ext.MessageBox.alert('Mensaje','No existen datos para eliminar');
				}
				else
				{
					obtenerMensaje('procesar','','Guardando Datos');
					
					var objdata ={
						'oper': 'eliminar', 
						'sistema': sistema,
						'vista': vista,
						'nomgru': Ext.getCmp('txtnombre').getValue()						
					};	
					objdata=JSON.stringify(objdata);
					parametros = 'objdata='+objdata;
					mensaje = 'Eliminado';
					Ext.Ajax.request({
					url : rutaGrupo,
					params : parametros,
					method: 'POST',
					success: function ( resultado, request )
					{ 
						datos = resultado.responseText;
						Ext.Msg.hide();
						var datajson = eval('(' + datos + ')');
						if (datajson.raiz.valido==true)
						{
							Ext.MessageBox.alert('Mensaje',datajson.raiz.mensaje);
							irNuevo();	  
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
		};		
	}
	
	
/***********************************************************************************
* @Función que imprime un reporte ficha de un grupo seleccionado de acuerdo a un archivo 
* Xml generado.
* @parametros: 
* @retorno: 
* @fecha de creación:  22/07/2008
* @autor: Ing. Gusmary Balza. 
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irImprimir()
	{
		var objdata ={
			'oper': 'reporteficha',
			'nomgru': Ext.getCmp('txtnombre').getValue(),
			'sistema': sistema,
			'vista': vista
		}
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
		url : rutaGrupo,
		params : parametros,
		method: 'POST',
		success: function (resultado,request)
		{
			datos = resultado.responseText;
			if (validarObjetos('txtnombre','60','novacio')!='0')
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