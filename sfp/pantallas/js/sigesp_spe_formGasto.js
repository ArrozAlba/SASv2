var IdPadre = '';
var Listo2 = false;
var Oper='';
var DatosNuevo ='';
var codemp='0001';
var tabs='';
var tabsplan='';
var combo1=''; 
var combo2=''; 
var Formulario1 = '';
var DataStore6='';
var denActual ='';
var DatosNuevo='';
var simple='';
var valor1='';  
var TipoEstructura='';
var valor2='';
var gridIntFuente='';
var gridIntProb='';
var gridIntVar='';
var FormEst='';
var anchoCombo=90;
var anchoTextoCombo=600;  
var nivelUbicaciones='';
var RecordDefPlaPre='';
var ObjFuente ='';
var RecordIntePlan='';
var winAd=null;
var RecordDefProb='';
var winUb=null; 
var RecordDefAd='';
var CatPreUnavez=false;
var CatAdUnavez=false;
var CatPlanUnavez=false;
var CatUbUnavez=false;
var RecordDefUb='';
var gridIntFuente2=null;
var Busqueda=false;
var DataStoreProb='';
var DataStoreAdmin='';
var DataStoreUb='';
var DataStoreGastos='';
var DataStoreVar='';
var DsBusqueda='';
ruta2='../../procesos/sigesp_spe_comboubgeopr.php';
rutaGrid ='../../procesos/sigesp_sfp_fuentefinpr.php';
rutaIntepr='../../procesos/sigesp_spe_formGastopr.php';
pantalla='sigesp_spe_formGasto.php';

Ext.onReady(function()
{
ObtenerSesion(rutaIntepr,pantalla);
function getobject()
{
	var myJSONObject ={
		"oper": 'datosInt',
		"tipodato": 'fuentefin', 
		"cod_fuenfin": "", 
		"denfuefin": "",
		"expfuefin":""
	};	
			
		//alert(DatosNuevo)	
			RecordIntePlan = Ext.data.Record.create([
			{name: 'cod_fuenfin'}, 
			{name: 'codinte'},
			{name: 'montot'},
			{name: 'denfuefin'}	
			]);
			var DatosNuevo={"raiz":[{"cod_fuenfin":'',"codinte":'',"montot":'',"denfuenfin":''}]};
			
			DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                     RecordIntePlan
			      ),
				data: DatosNuevo
                 });
			gridIntFuente = new Ext.grid.EditorGridPanel({
			width:770,
			id:'fuentefin',
			autoScroll:true,
                        border:true,
                        ds:DataStore,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Fuente de Financiamiento", width: 150, sortable: true,   dataIndex: 'denfuefin'},
                            {header: "Monto", width: 350, sortable: true, dataIndex: 'montot',editor: new Ext.form.NumberField({allowBlank: false})}			
 ]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
		gridIntFuente.render('grid-fuentes');
		
}


function ActualizarGridsDetalles()
{
		GridActual=ObtenerGrid(tabActual);
		var myJSONObject ={
		"oper": 'datosInt',
		"tipodato":GridActual.getId(), 
		"codinte":IdPadre
	};
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ){ 
	datos = resultado.responseText;
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			GridActual.store.loadData(DatosNuevo);
		 }
		}
	})	
			
}

function getGridAd()
{	
			var myJSONObject ={
				"oper": 'datosInt',
				"tipodato": 'uniAds', 
				"cod_fuenfin": "", 
				"denfuefin": "",
				"expfuefin":""
			};	

			RecordDefAd = Ext.data.Record.create
			([
				{name: 'coduac'},    
				{name: 'denuac'}, 
				{name: 'nivel'},
				{name: 'codinte'}
			]);
			
			var DatosNuevo={"raiz":[{"coduac":'',"denuac":'',"codinte":'',"nivel":'nivel'}]};	
			DataStoreAdmin =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',               
			    id: "id"   
			    },
                      RecordDefAd
			    ),
				data: DatosNuevo
                });
		
			 gridIntAd = new Ext.grid.EditorGridPanel({
			 id:'uniAds',	
			 width:1000,
			 height:150,
			 autoScroll:true,
                        border:true,
                        ds:DataStoreAdmin,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denuac'}
]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			
			stripeRows: true
                   });
		   
		gridIntAd.render('grid-uniads');
		
}
function getGridUb()
{
			var myJSONObject ={
				"oper": 'datosInt',
				"tipodato":'ubGeo'			
			};	
	
			RecordDefUb = Ext.data.Record.create
			([
				{name: 'codubgeo1'}, 
				{name: 'codubgeo2'},
				{name: 'codubgeo3'},
				{name: 'codubgeo4'},               
				{name: 'codubgeo5'}, 
				{name: 'nivel'}, 
				{name: 'denominacion'}
			]);
			
			var DatosNuevo={"raiz":[{"coduac":'',"denuac":'',"codinte":''}]};	
			DataStoreUb =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',               
			    id: "id"   
			    },
                      RecordDefUb
			    ),
				data: DatosNuevo
                });
			
			 gridIntUb = new Ext.grid.EditorGridPanel({
			 id:'uniAds',	
			width:1000,
			height:150,
			autoScroll:true,
                        border:true,
                        ds:DataStoreUb,
                        cm: new Ext.grid.ColumnModel([
                        {header: "Denominación", width: 350, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.TextField({allowBlank: false})}
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:
						{
                        forceFit:true
                        }
			,
			stripeRows: true
                   });				   		   
		gridIntUb.render('grid-ubgeos');
}

function getGridVar()
{
			var myJSONObject ={
				"oper": 'datosInt',
				"tipodato":'ubGeo'			
			};	
	
			RecordDefVar = Ext.data.Record.create 
			([
				{name: 'cod_var'}, 
				{name: 'montoglobal'},
				{name: 'meta'},
				{name: 'NuevoRegistro'},
				{name: 'enero_masc'},
				{name: 'febrero_masc'},
				{name: 'marzo_masc'},
				{name: 'abril_masc'},
				{name: 'mayo_masc'},
				{name: 'junio_masc'},
				{name: 'julio_masc'},
				{name: 'agosto_masc'},
				{name: 'septiembre_masc'},
				{name: 'octubre_masc'},
				{name: 'noviembre_masc'},
				{name: 'diciembre_masc'},
				{name: 'enero_fem'},
				{name: 'febrero_fem'},
				{name: 'marzo_fem'},
				{name: 'abril_fem'},
				{name: 'mayo_fem'},
				{name: 'junio_fem'},
				{name: 'julio_fem'},
				{name: 'agosto_fem'},
				{name: 'septiembre_fem'},
				{name: 'octubre_fem'},
				{name: 'noviembre_fem'},
				{name: 'diciembre_fem'}
			]);
			
			var DatosNuevo={"raiz":[{"cod_var":'',"meta":'',"unidad":'',"genero":''}]};	
			DataStoreVar =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',               
			    id: "id"   
			    },
                      RecordDefVar
			    ),
				data: DatosNuevo
                });
                
				var agregarvar = new Ext.Action(
				{
					text: 'Agregar',
					handler: MostrarCatalogoMeta,
					iconCls: 'bmenuagregar',
		        	tooltip: 'Agregar cuenta'
				});		
				var quitarvar = new Ext.Action(
				{
					text: 'Quitar',
					handler: irQuitarMeta,
					iconCls: 'bmenuquitar',
		        	tooltip: 'Eliminar registro'
				});
			
			gridIntVar = new Ext.grid.GridPanel({
			id:'Metas',	
			width:1000,
			height:110,
			tbar:[agregarvar,quitarvar],
			autoScroll:true,
            border:true,
            ds:DataStoreVar,
            cm: new Ext.grid.ColumnModel([
            {header: "Denominación", width: 350, sortable: true, dataIndex: 'meta'}
]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:true}),
                        viewConfig:
						{
                        	forceFit:true
                        },
		
			stripeRows: true
                   });				   		   
		gridIntVar.render('grid-vars');
		gridIntVar.addListener('celldblclick',getGridMontosMetas);
}

function getGridInd()
{
			RecordDefProb = Ext.data.Record.create
			([
				{name: 'codinte'}, 
				{name: 'cod_ind'},
				{name: 'denominacion'},
				{name: 'montototal'},
				{name: 'enero'},
				{name: 'febrero'},
				{name: 'marzo'},
				{name: 'abril'},
				{name: 'mayo'},
				{name: 'junio'},
				{name: 'julio'},
				{name: 'agosto'},
				{name: 'septiembre'},
				{name: 'octubre'},
				{name: 'noviembre'},
				{name: 'diciembre'},
				{name: 'formula'},
				{name: 'NuevoRegistro'}
			]);
			
			var DatosNuevo={"raiz":[{"codinte":'',"cod_ind":'',"denominacion":'',"montototal":''}]};	
			DataStorePro =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',               
			    id: "id"   
			    },
                      RecordDefProb
			    ),
				data: DatosNuevo
                });
			
			
				var agregarind = new Ext.Action(
				{
					text: 'Agregar',
					handler: MostrarCatalogoInd,
					iconCls: 'bmenuagregar',
		        	tooltip: 'Agregar Indicador'
				});		
				var quitarind = new Ext.Action(
				{
					text: 'Quitar',
					handler: irQuitarInd,
					iconCls: 'bmenuquitar',
		        	tooltip: 'Eliminar Indicador'
				});
			 gridIntInd = new Ext.grid.GridPanel({
			 id:'s',	
			 width:1000,
			 tbar:[agregarind,quitarind],
			 autoScroll:true,
                        border:true,
                        ds:DataStorePro,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 50, sortable: true, dataIndex: 'cod_ind',editor: new Ext.form.TextField({allowBlank: false})},
                            {header: "Denominación", width: 450, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.TextField({allowBlank: false})},
                            {header: "Monto", width: 50, sortable: true, dataIndex: 'montototal',editor: new Ext.form.TextField({allowBlank: false})}
						]),
						selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });				   		   
		gridIntInd.render('grid-probs');
		gridIntInd.addListener('celldblclick',llamarformula);
}

function MostrarCatalogoInd()
{
	MostrarCatEmp('grid',gridIntInd);
}



Ext.get('BtnImp').on('click',function(){
	var myJSONObject =
	{
		"oper": 'LeerTodosRep'
	};
	ObjSon=JSON.stringify(myJSONObject);
	
	//return false;
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ){ 
		  datos = resultado.responseText;
		  if(datos!='')
		  {
		  	Abrir_ventana (datos);
		  }
	
	}
	})
})





function getGridGastos()
{
			var myJSONObject ={
				"oper": 'datosInt',
				"tipodato":'ubGeo'			
			};	
	
			RecordDef = Ext.data.Record.create
			([
				{name: 'spg_cuenta'}, 
				{name: 'denominacion'},
				{name: 'codinte'},
				{name: 'montoglobal'},
				{name: 'NuevoRegistro'},
				{name: 'ano_presupuesto'},
				{name: 'enero'},
				{name: 'febrero'},
				{name: 'marzo'},
				{name: 'abril'},
				{name: 'mayo'},
				{name: 'junio'},
				{name: 'julio'},
				{name: 'agosto'},
				{name: 'septiembre'},
				{name: 'octubre'},
				{name: 'noviembre'},
				{name: 'diciembre'},
				{name: 'montoanoanterior'},
				{name: 'montoanoactual'},
				{name: 'codigohaber'},
				{name: 'denhaber'},
				{name: 'codigodebe'},
				{name: 'dendebe'},
				{name: 'montoanoactual'},
				{name: 'codigohaber'},
				{name: 'denhaber'},
				{name: 'codigodebe'},
				{name: 'dendebe'},
				{name: 'codvarhaber'},
				{name: 'denvarhaber'},
				{name: 'codvardebe'},
				{name: 'denvardebe'},
				{name: 'codcaif'},		
				{name: 'montoanpre'},
				{name: 'montoanant'},
				{name: 'montoanreal'},
				{name: 'cuentadebe'},
				{name: 'cuentahaber'},
				{name:'monto_anest'},
				{name:'monto_anreal'},
				{name: 'fuentes'}
			]);
			
			var DatosNuevo={"raiz":[{"spg_cuenta":'',"denominacion":'',"codinte":''}]};	
			
			DataStoreGastos =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',               
			    id: "id"   
			    },
                      RecordDef
			    ),
				data: DatosNuevo
                });
                
            var agregarcuen = new Ext.Action(
			{
				text: 'Agregar',
				handler: CatPlanCuentas,
				iconCls: 'bmenuagregar',
		        tooltip: 'Agregar cuenta'
			});		
			var quitarcuen = new Ext.Action(
			{
				text: 'Quitar',
				handler: irQuitarEjeFin,
				iconCls: 'bmenuquitar',
		        tooltip: 'Eliminar registro'
			});
            
			gridIntGastos = new Ext.grid.EditorGridPanel({
		//	id:'Probss',	
			title:'Programación Financiera',
			id:'cuentas',
			tbar:[agregarcuen,quitarcuen],
			height:170,
			width:1020,
			autoScroll:true,
                        border:true,
                        ds:DataStoreGastos,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Codigo de la Cuenta", width: 100, sortable: true, dataIndex: 'spg_cuenta'},
                            {header: "Denominación", width: 400, sortable: true, dataIndex: 'denominacion'},
							{header: "Monto Global", width: 150, sortable: true, dataIndex:'montoglobal',editor: new Ext.form.TextField({allowBlank: false}),align:'right'}                            
]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			stripeRows: true
        });				   		   
	//	gridIntGastos.render('grid-Gastos');
		gridIntGastos.addListener('celldblclick',getGridMontosGastos);
		
}
	getGridInd();
		/*
			RecordDefEst = Ext.data.Record.create
			([
				{name: 'spg_cuenta'}, 
				{name: 'denominacion'}
			]);
			
			var DatosNuevo={"raiz":[{"spg_cuenta":'',"denominacion":'',"codinte":''}]};	
			
			DataStoreEst =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',               
			    id: "id"   
			    },
                      RecordDefEst
			    ),
				data: DatosNuevo
       		});
		gridGenEst = new Ext.grid.EditorGridPanel({
		id:'Estprog',
		height:150,
		width:1000,
		autoScroll:true,
        border:true,
        ds:DataStoreEst,
        cm: new Ext.grid.ColumnModel([
           {header: "Codigo de la Cuenta", width: 100, sortable: true, dataIndex: 'spg_scuenta'},
           {header: "Denominación", width: 400, sortable: true, dataIndex: 'denominascion'},
		   {header: "Monto Global", width: 150, sortable: true, dataIndex:'montoglsobal',editor: new Ext.form.TextField({allowBlank: false}),align:'right'}                            
		])
		,
		selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
              viewConfig:{
              forceFit:true
        }
        ,
			stripeRows: true
        });				   		   
		gridGenEst.render('grid-GenEst');
		
		
		*/
		
		tabsGenEst= new Ext.Panel(
        {
          baseCls:'x-plain',
		  renderTo: 'panelgeneral',
		  activeTab: 0,
		  frame:true,
		  autoScroll:true,
          width:1024,
          height:280,		
          modal: true,
          closeAction:'hide',
          plain: false
           ,items:[
        		  	{
                    	id:'estpre',
                    	height:90,
                    	contentEl:'EstSeleccionado'
					}
					,
					{
                    	title:'Programación Física',
                    	id:'profis',
                    	height:180,
                    	contentEl:'tabs1'
                	}
				]    
        });
	
   		Ext.QuickTips.init(); 
		tabsProb= new Ext.TabPanel(
        {
          baseCls:'x-plain',
		  renderTo: 'tabs1',
		  activeTab: 0,
		  frame:true,
		  autoScroll:true,
          width:1024,
          height:180,		
          modal: true,
          closeAction:'hide',
          plain: false
		    ,defaults: {frame:true, width:800, height: 200}
          ,items:[
        		  	{
	        		  	title:'Metas',
	                    id:'Itab4',
	                    contentEl:'grid-vars'
					}
					,
					{
						title:'Indicadores de Gestón',	
						contentEl:'grid-probs',
						id:'Itab30'
					} 
				]     
        });


Ext.get('ImgRestar').on('click', function()
{

	var selectedKeys = grid.selModel.selections.keys;
        if(selectedKeys.length > 0) {
            Ext.Msg.confirm('ALERTA!','Realmente desea eliminar el registro?', deleteRecord);
        } 
        else
        {
            Ext.Msg.alert('ALERTA!','Seleccione un registro para eliminar');
        }
});

 function deleteRecord(btn) 
 {
	  if (btn=='yes') 
	  {
		var selectedRow = grid.getSelectionModel().getSelected();
		if(selectedRow)
		{
			DataStore.remove(selectedRow);
		}
	  } 
}


 Ext.get('ImgSumar').on('click', function(){
	var myJSONObject ={
		"oper": 'catalogo', 
		"cod_fuenfin": "", 
		"denfuefin":"",
		"expfuefin":""
	};
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ){ 
		  datos = resultado.responseText;
		//  alert(datos);
		  var myObject = eval('(' + datos + ')');
		  var RecordDef = Ext.data.Record.create([
		{name: 'cod_fuenfin'},     
		{name: 'denfuefin'},
		{name: 'expfuefin'}	// This field will use "occupation" as the mapping.
		]);
                  if (!gridOnOff)
                  {
		            grid2 = new Ext.grid.GridPanel({
					width:770,
					autoScroll:true,
		            border:true,
                    ds: new Ext.data.Store({
					proxy: new Ext.data.MemoryProxy(myObject),
					reader: new Ext.data.JsonReader({
				    root: 'raiz', 
			     	id: "id"   
			    
					},
                        RecordDef
			     
			       ),
				data: myObject
                        }),
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
{header: "Código", width: 30, sortable: true,   dataIndex: 'cod_fuenfin'},
{header:"Denominación", width: 50, sortable: true, dataIndex: 'denfuefin'},
{header: "Explicación", width: 70, sortable: true, dataIndex: 'expfuefin'}
							
                        ]),

                        viewConfig: {
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
                   gridOnOff = true;
                 }
                  else
                  {
                  	grid2.store.loadData(myObject);
                  } 
				  
				  
		var simple = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
        url:'save-form.php',
        frame:true,
        title: 'Búsqueda',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
		height:120,
        defaults: {width: 230},
        defaultType: 'textfield',
		items: [{
                fieldLabel: 'Código',
                name: 'cod',
				id:'cod',
				changeCheck: function(){
							  var v = this.getValue();
							 ActualizarData('cod_fuenfin',v);
							if(String(v) !== String(this.startValue)){
								this.fireEvent('change', this, v, this.startValue);
							} 
							 },
							 
							initEvents : function()
							{
								AgregarKeyPress(this);
							}
               
            },{
                fieldLabel: 'Denominacion',
                name: 'den',
				changeCheck: function(){
							  var v = this.getValue();
							 ActualizarData('denfuefin',v);
							if(String(v) !== String(this.startValue))
							{
								this.fireEvent('change', this, v, this.startValue);
							} 
							 },
							 
							initEvents : function()
							{
								AgregarKeyPress(this);
							}

            }]
		});
		
					  
                  if(!winOnOff)
                  {
                   win = new Ext.Window(
                   {
                    //layout:'fit',
                    title: 'Cat&aacute;logo de Fuente de Financiamiento',
		    		autoScroll:true,
                    width:800,
                    height:400,
                    modal: true,
                    closeAction:'hide',
                    plain: false,
                    items:[simple,grid2],
                    buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {
 				   var p = new RecordDef(
                    {cod_fuenfin:'nuevo',
                    denfuefin: 'nuevo',
                    expfuefin: 'nuevo'}
                   
                );
                //grid.stopEditing();
                DataStore.insert(0, p);
                grid.startEditing(0, 0);
		        win.hide();
                      
                     }
                    },
                    {
                     text: 'Salir',
                     handler: function()
                     {
                      win.hide();
                     }
                    }]
                   });
                   //winOnOff = true;
                   //estaba alla donde dice aqui
                  }
                  else
                  {
                   //win.add(grid);
                   //alert(win.title);
                  }
                  //estaba aqui
                  win.show();
                   if(!unavez)
                   {
                    grid.render('miGrid');
                    unavez=false;
                   }
                   grid.getSelectionModel().selectFirstRow();
        }
		,
        failure: function ( resultado, request) { 
                   Ext.MessageBox.alert('Error', resultado.responseText); 
        }
	
   });

 });


function AgregarKeyPress(Obj)
{
		Ext.form.TextField.superclass.initEvents.call(Obj);
		if(Obj.validationEvent == 'keyup')
		{
			Obj.validationTask = new Ext.util.DelayedTask(Obj.validate, Obj);
			Obj.el.on('keyup', Obj.filterValidation, Obj);
		}
		else if(Obj.validationEvent !== false)
		{
			Obj.el.on(Obj.validationEvent, Obj.validate, Obj, {buffer: Obj.validationDelay});
		}
		if(Obj.selectOnFocus || Obj.emptyText)
		{
			Obj.on("focus", Obj.preFocus, Obj);
			if(Obj.emptyText)
			{
				Obj.on('blur', Obj.postBlur, Obj);
				Obj.applyEmptyText();
			}
		}
		if(Obj.maskRe || (Obj.vtype && Obj.disableKeyFilter !== true && (Obj.maskRe = Ext.form.VTypes[Obj.vtype+'Mask']))){
			Obj.el.on("keypress", Obj.filterKeys, Obj);
		}
		if(Obj.grow)
		{
			Obj.el.on("keyup", Obj.onKeyUp,  Obj, {buffer:50});
			Obj.el.on("click", Obj.autoSize,  Obj);
		}
			Obj.el.on("keyup", Obj.changeCheck, Obj);
}

obtenersesion();
getGridAd();
getGridUb();
getGridVar();
getGridGastos();


		var agregarEstPlan = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregarEstPlan,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar cuenta'
		});		
		var quitarEstPlan = new Ext.Action(
		{
			text: 'Quitar',
		//	handler: irQuitarEstPlan,
			iconCls: 'bmenuquitar',
        	tooltip: 'quitar cuenta'
		});	

		var agregarEstPre = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregarEstPre,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar cuenta'
		});		
		var quitarEstPre = new Ext.Action(
		{
			text: 'Quitar',
			//handler: irQuitarEstPre,
			iconCls: 'bmenuquitar',
        	tooltip: 'Eliminar registro'
		});  	
		   var expander = new Ext.grid.RowExpander({
		        tpl : new Ext.Template(
		            '<p><b>Descripción:</b> {DenSel}</p><br>'
		        )
		    });
		
			
		    var DatosNuevo={"raiz":[{"NombreNivel":'',"CodSel":'',"DenSel":''}]};
			RecordDefPlaPre = Ext.data.Record.create
			([
				{name: 'NombreNivel'},     // "mapping" property not needed if it's the same as "name"
				{name: 'CodSel'},
				{name: 'DenSel'}	// This field will use "occupation" as the mapping.
			]);

			DataStorePlan =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    }
			    ,
                    RecordDefPlaPre
			     
			    )
			    ,
			data: DatosNuevo
              });

		 gridEstPlanSelec = new Ext.grid.EditorGridPanel({
		 width:1000,
		 autoScroll:true,		
         border:true,
         height:150,
         ds:DataStorePlan,
          cm: new Ext.grid.ColumnModel([
          					expander,
                            {header: "", width: 110, sortable: true,   dataIndex: 'NombreNivel'},
                            {header: "", width: 350, sortable: true, dataIndex: 'CodSel'}
							
                        ]),
                        
			        plugins: expander,
			        collapsible: true,
			        animCollapse: false,
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			stripeRows: true
     });
     
     	 	gridEstPlanSelec.render('PlanSeleccionado');
     	 	
     	 RecordDefEstPre = Ext.data.Record.create
			([
				{name: 'codigo'},     // "mapping" property not needed if it's the same as "name"
				{name: 'descripcion'},
				{name: 'codinte'}
			]);
     	 	DataStorePre =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                     RecordDefEstPre
			     
			    )
              });
     	
     	 var expander2 = new Ext.grid.RowExpander({
		        tpl : new Ext.Template(
		            '<p><b>Descripción:</b>{descripcion}</p><br>'
		        )
		}); 
     	DsBusqueda =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader({
			root: 'raiz',                
			id: "id"   
			 },
            RecordDefPlaPre
			),
			data: DatosNuevo
         });   
		 gridEstPreSelec = new Ext.grid.GridPanel({
		 width:1000,
		 height:200,
		 tbar:[agregarEstPre,quitarEstPre],
		 plugins: expander2,
		 collapsible: true,
		 animCollapse: false,
		 autoScroll:true,
         border:true,
         ds:DataStorePre,
         cm: new Ext.grid.ColumnModel([
         					expander2,
                            {header: "Código de la estructura", width: 180, sortable: true,dataIndex: 'codigo'}  
							
                        ]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			stripeRows: true
                   });
	gridEstPreSelec.render('EstSeleccionado');
/*
	simple2 = new Ext.Panel({
	renderTo:'progra',
	layout:'table',
	width:1024,
	height:200,
	layoutConfig:{columns:0},
	 defaults: {frame:true, width:512, height: 512},
	id:'main-panel',
    items:[{
    title:'Estructura del Plan',
    contentEl:'PlanSeleccionado',
     },
	 {
    title:'Estructura Presupuestaria',
    contentEl:'EstSeleccionado',
     }
	]
	});  		
  		*/
  		var agregarEstPlan = new Ext.Action(
		{
			text: 'Agregar',
			//handler: irAgregarEstPlan,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar cuenta'
		});		
		var quitarEstPlan = new Ext.Action(
		{
			text: 'Agregar',
		//	handler: irQuitarEstPlan,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar cuenta'
		});
		var agregarEstPre = new Ext.Action(
		{
			text: 'Agregar',
			//handler: irAgregarEstPre,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar cuenta'
		});		
		var quitarEstPre = new Ext.Action(
		{
			text: 'Quitar',
			//handler: irQuitarEstPre,
			iconCls: 'bmenuquitar',
        	tooltip: 'Eliminar registro'
		});
  		

  		var agregarEjeFis = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregarEjeFis,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar cuenta'
		});
		
		var quitarEjeFis = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitarEjeFis,
			iconCls: 'bmenuquitar',
        	tooltip: 'Eliminar registro'
		});
		
		var agregarEjeFin = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregarEjeFin,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar cuenta'
		});
		
		var quitarEjeFin = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitarEjeFin,
			iconCls: 'bmenuquitar',
        	tooltip: 'Eliminar registro'
		});	
		
function DesabilitarGrids(valor)
{
	item1.setDisabled(valor);
	item2.setDisabled(valor);	
}
		
	
	
	
 		Ext.state.Manager.setProvider(new Ext.state.CookieProvider());        
       		var viewport = new Ext.Viewport({
            layout:'border',
            items:[
                new Ext.BoxComponent({ // raw
                    region:'north',
                    el: 'norte',
                    height:125
                })
				,
                new Ext.Panel({
                region:'south',
                layout:'table',
                width:210,
                height:180,
              //  style:"height:250px",
               
                bodyStyle:'background-color:#DFE8F6',
                items:[
                    	gridIntGastos
					]
                //,
                //items: []
            })
			,
                new Ext.BoxComponent({ // raw
                    region:'center',
                    el: 'panelgeneral',
                    height:180
                })]
         })	
;
function MostrarCatest()
{
	      win = new Ext.Window(
          {
          	//layout:'fit',
            title: 'Cat&aacute;logo de Estructuras',
		    autoScroll:true,
            width:800,
            height:400,
            modal: true,
            closeAction:'hide',
            plain: false,
            items:[Formulario1,tabsplan],
            buttons:[{
            text:'Aceptar',  
            handler: function()
            {            	
            if(ListoUltimoPlan==true)
            { 
                if(valorPlan1!='')
                 {
                	PasarDatosGrids(Ext.get('nivelPlan1').dom.innerHTML,
valorPlan1,denPlan1,gridEstPlanSelec);
				 }
				 if(valorPlan2!='')
				 {
					PasarDatosGrids(Ext.get('nivelPlan2').dom.innerHTML,
valorPlan2,denPlan2,gridEstPlanSelec);					
				 }
  				if(valorPlan3!='')
  				{
					PasarDatosGrids(Ext.get('nivelPlan3').dom.innerHTML,
valorPlan3,denPlan3,gridEstPlanSelec);				
						
				}
				if(valorPlan4!='')
  				{
					PasarDatosGrids(Ext.get('nivelPlan4').dom.innerHTML,
valorPlan4,denPlan4,gridEstPlanSelec);				
			
				}
	
				if(valorPlan5!='')
  				{
  				
					PasarDatosGrids(Ext.get('nivelPlan5').dom.innerHTML,
valorPlan5,denPlan5,gridEstPlanSelec);				
						
				}
				if(valorPlanActual!='')				
				{
					PasarDatosGrids(Ext.get('nivelPlan'+cantidadPlan).dom.innerHTML,
valorPlanActual,denPlanActual,gridEstPlanSelec);				
						
				}
		      	win.hide();       
            }
            else
            {
				Ext.Msg.alert('Mensaje','Debe seleccionar toda la estructura');
			}
            }
            },
             {
            text: 'Salir',
            handler: function()
            {
                win.hide();
            }
            }]
            });	
            win.show();
}

function MostrarCatUb()
{
	
	      winUb = new Ext.Window(
          {
          	//layout:'fit',
            title: 'Cat&aacute;logo de Ubicación Geográfica',
		    autoScroll:true,
            width:800,
            height:400,
            modal: true,
            closeAction:'hide',
            plain: false,
            items:[Formulario1,tabsUb],
            buttons:[{
            text:'Aceptar',  
            handler: function()
            {
            if(ListoUltimoUb==true)
            { 
				if(valorUbActual!='')				
				{
					switch(cantidadUb)
					{
						case "1":
						valorUb1=valorUbActual;
						break;
						case "2":
						valorUb2=valorUbActual;
						break;
						case "3":
						valorUb3=valorUbActual;
						break;
						case "4":
						valorUb4=valorUbActual;
						break;
						case "5":
						valorUb5=valorUbActual;
						break;
						
						
					}
				
				}
				
					r=new RecordDefUb({
						'codubgeo1':'',
						'codubgeo2':'',
						'codubgeo3':'',
						'codubgeo4':'',
						'codubgeo5':'',
						'nivel':'',
						'denominacion':''	
					});
					CantidadDatos = gridIntUb.store.getCount()-1;
					gridIntUb.store.insert(CantidadDatos,r);		
					r.set('codubgeo1',valorUb1);
					r.set('codubgeo2',valorUb2);
					r.set('codubgeo3',valorUb3);
					r.set('codubgeo4',valorUb4);
					r.set('codubgeo5',valorUb5);
					r.set('nivel',cantidadUb);
					r.set('denominacion',denUbActual);
					r.set('codinte',1);	
		      		winUb.hide();       
            }
            else
            {
				Ext.Msg.alert('Mensaje','Debe seleccionar toda la estructura');
			}
            }
            }
			,
            {
            text: 'Salir',
            handler: function()
            {
                winUb.hide();
            }
            }]
            });	
            winUb.show();
}


function MostrarCatestAd()
{
		winAd = new Ext.Window(
          {
          	//layout:'fit',
            title: 'Cat&aacute;logo de Estructuras',
		    autoScroll:true,
            width:800,
            height:400,
            modal: true,
            closeAction:'hide',
            plain: false,
            items:[FormularioAd,tabsAd],
            buttons:[{
            text:'Aceptar',  
            handler: function()
            {
            	 	
			if(ListoUltimoAd==true)
			{
					r=new RecordDefAd
					({
						'coduac':'',
						'denuac':'',
						'nivel': '' ,
						'codinte':''	
					});
					

					
					CantidadDatos = gridIntAd.store.getCount()-1;
					gridIntAd.store.insert(CantidadDatos,r);  
					r.set('coduac',valorAdActual);
					r.set('denuac',denAdActual);
					r.set('nivel',ObjActual);
					r.set('codinte',1);
		      		winAd.hide(); 
			}
			else
			{
					Ext.Msg.alert('Mensaje','Debe seleccionar toda la estructura');
			}  
					  		      
            }
            },
             {
            text: 'Salir',
            handler: function()
            {
                winAd.hide();
            }
            }]
            });	
            winAd.show();
}


function ActualizarTodosGrids(Intesel)
{

//alert(Intesel);
return false;

	var myJSONObject =
	{
		"oper": 'buscardetalles',
		"codinte":Intesel
	};

	ObjSon=JSON.stringify(myJSONObject);
	//alert(ObjSon);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
	//	 alert(datos);
	// Ext.get('norte').dom.innerHTML=datos;
		  if(datos!='|0')
		  {
		  	ArrayObject = datos.split('|');
		  	//alert(ArrayObject[0]);
		  	var DatJson = eval('(' + ArrayObject[0] + ')');
		  	var DatJsonProg = eval('(' + ArrayObject[1] + ')');
		  	var DatJsonPlan = eval('(' + ArrayObject[2] + ')');
		  	var DatJsonProb = eval('(' + ArrayObject[3] + ')');
		  	var DatJsonUb = eval('(' + ArrayObject[4] + ')');
		  	var DatJsonAd = eval('(' + ArrayObject[5] + ')');
		  	var DatJsonCuentas = eval('(' + ArrayObject[6] + ')');
		  	var DatJsonMetas = eval('(' + ArrayObject[7] + ')');
		  	nivelUbicaciones = ArrayObject[8];
		  	if(DatJson.raiz!=null)
		  	{
		  		IdPadre=DatJson.raiz[0].codinte;
		  		if(DatJson.raiz[0].codest1)
		  		{
					AuxCadJson = AuxCadJson+"raiz:[{'NombreNivel':'"+DatJsonPlan.raiz[0].nombre_pest+"','CodSel':'"+DatJson.raiz[0].codest1+"','DenSel':'"+DatJson.raiz[0].denest1+"'}";
				}
				if(DatJson.raiz[0].codest2)
		  		{
					AuxCadJson = AuxCadJson+",{'NombreNivel':'"+DatJsonPlan.raiz[1].nombre_pest+"','CodSel':'"+DatJson.raiz[0].codest2+"','DenSel':'"+DatJson.raiz[0].denest2+"'}";
				}


			if(DatJson.raiz[0].codest3)
			{
					AuxCadJson = AuxCadJson+",{'NombreNivel':'"+DatJsonPlan.raiz[2].nombre_pest+"','CodSel':'"+DatJson.raiz[0].codest3+"','DenSel':'"+DatJson.raiz[0].denest3+"'}";
			}
		
			if(DatJson.raiz[0].codest4)
			{
					AuxCadJson = AuxCadJson+ ",{'NombreNivel':'"+DatJsonPlan.raiz[3].nombre_pest+"','CodSel':'"+DatJson.raiz[0].codest4+"','DenSel':'"+DatJson.raiz[0].denest4+"'}";
			}
		
			if(DatJson.raiz[0].codest5)
			{
					AuxCadJson = AuxCadJson+ ",{'NombreNivel':'"+DatJsonPlan.raiz[4].nombre_pest+"','CodSel':'"+DatJson.raiz[0].codest5+"','DenSel':'"+DatJson.raiz[0].denest5+"'}";
			}
					AuxCadJson = AuxCadJson+']}';
					CodigoEst='';
					DenEst='';			
					if(Arsel[0].get('codestpro1') && CodigoEst=='')
					{
						CodigoEst =Arsel[0].get('codestpro1'); 
					}
					if(Arsel[0].get('codestpro2'))
						CodigoEst =CodigoEst+'-'+Arsel[0].get('codestpro2');
					if(Arsel[0].get('codestpro3'))
						CodigoEst =CodigoEst+'-'+Arsel[0].get('codestpro3');
					if(Arsel[0].get('codestpro4'))
						CodigoEst =CodigoEst+'-'+Arsel[0].get('codestpro4');
					if(Arsel[0].get('codestpro5'))
						CodigoEst =CodigoEst+'-'+Arsel[0].get('codestpro5');	
					
					if(Arsel[0].get('denestpro1'))
						DenEst =Arsel[0].get('denestpro1'); 
					if(Arsel[0].get('denestpro2'))
						DenEst =Arsel[0].get('denestpro2');
					if(Arsel[0].get('denestpro3'))
						DenEst =Arsel[0].get('denestpro3');
					if(Arsel[0].get('denestpro4'))
						DenEst =Arsel[0].get('denestpro4');
					if(Arsel[0].get('denestpro5'))
						DenEst =Arsel[0].get('denestpro5');					
						
					
									  	
				  	if(DatJsonAd.raiz!=null)
				  	{
				  	
				    	gridIntAd.store.loadData(DatJsonAd);
				    }
				    if(DatJsonUb.raiz!=null)
				  	{
				    	gridIntUb.store.loadData(DatJsonUb);
				    }
				    if(DatJsonCuentas.raiz!=null)
				  	{
				    	gridIntGastos.store.loadData(DatJsonCuentas);
				    }
				    
				    if(DatJsonMetas.raiz!=null)
				  	{
				    	gridIntVar.store.loadData(DatJsonMetas);
				    }
}
else
{
	Ext.Msg.alert('Mensaje','No existen datos asociados a la estructura seleccionada');	
}
}
}
});
}




function ActualizarGridsDetalles(Json,GridActual)
{
	var myJSONObject =
	{
		"oper":'buscardetalles',
		"codinte": IdPadre
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request
	(
		{
			url : rutaIntepr,
			params : parametros,
			method: 'POST',
			success: function ( resultado, request ) 
			{ 
				  datos2 = resultado.responseText;
//				  alert(datos2);
		
			}
		}
	)
		
}

Ext.get('BtnNuevo').on('click', function()
{
	EstadoInicial()
		
})	


Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank2.php';
})


//Ext.get('SumEstPla').on('click', function()
function irAgregarEstPlan()
{
	Busqueda=false;
	if(CatPlanUnavez==false)
	{
		getDatosPlan('getSesion');
		CatPlanUnavez=true;
	}
	else
	{
		EstadoInicialPlan();
	}
	MostrarCatest();

}
//);

//Ext.get('SumEstPre').on('click', function()


function EstadoInicial()
{
	DatosNuevo={"raiz":[{"NombreNivel":'',"CodSel":'',"DenSel":''}]};
	DataStorePlan.loadData(DatosNuevo);
	DataStorePre.loadData(DatosNuevo);
	DatosNuevoAd={"raiz":[{"NombreNivel":'',"CodSel":'',"DenSel":''}]};
	gridIntAd.store.loadData(DatosNuevoAd);
	DatosNuevoUb={"raiz":[{"coduac":'',"denuac":'',"codinte":''}]};
	gridIntUb.store.loadData(DatosNuevoUb);
	DatosNuevoProb={"raiz":[{"codprob":'',"denominacion":'',"causa":''}]};	
	gridIntProb.store.loadData(DatosNuevoProb);
	DatosNuevoGas={"raiz":[{"spg_cuenta":'',"denominacion":'',"codinte":''}]};
	gridIntGastos.store.loadData(DatosNuevoGas);

}

function ObtenergridInt(tab)
{
	//alert(tab);
	switch(tab)
	{
		case '0':
			return gridIntFuente;
			break;
		case '1':
			return gridIntAd;
			break;
		case '2':
			return gridAd3;
			break;
		case '3':
			return gridAd4;
			break;
		case '4':
			return gridAd5;
			break;
	}    
	
}


function PasarDatosGrids(NombreNivel,codigo,Deno,ParamGrid)
{
	r=new RecordDefPlaPre
	(
		{
			'NombreNivel':NombreNivel,
			'CodSel':codigo,
			'DenSel': Deno 	
		}
	);
	
	CantidadDatos = ParamGrid.store.getCount()-1;
	CanDatos = DsBusqueda.getCount()-1;
	if(Busqueda==false)
	{
		//alert(CantidadDatos);
		ParamGrid.store.insert(CantidadDatos,r);	
	}
	else
	{
		if(CanDatos<0)
		{
			CanDatos=0;
			DsBusqueda.insert(CanDatos,r);
		}
		//alert('llenando los datos para la busqueda: '+codigo);
		DsBusqueda.insert(CanDatos,r);
	}	
}
//Ext.get('LlamarCatalogosTabs1').on('click', function()
function irAgregarEjeFis()
{
	tabActual = tabsProb.getActiveTab().id;
	switch(tabActual)
	{
		case 'Itab1':
		if(CatAdUnavez==false)
		{
			getDatosAd('getSesion');
			CatAdUnavez=true;
		}
		else
		{
			EstadoInicialAd();
		}
		MostrarCatestAd();
		break;	
		case 'Itab2':
		if(CatUbUnavez==false)
		{
			getDatosUb('getSesion');
			CatUbUnavez=true;
		}
		else
		{
			EstadoInicialUb();
		}
		MostrarCatUb();
		break;	
		case 'Itab3':
			ObjProb = new CatProb();
			ParamGridTarget = gridIntProb;
			ObjProb.MostrarCatalogo();
			break;
		case 'Itab4':
		//alert('si metas');
			ObjMeta = new CatMetas();
			ParamGridTarget = gridIntVar;
			ObjMeta.MostrarCatalogo();
		break;	
	}
	
}
//);

function irQuitarEjeFis()
{
	tabActual = tabsProb.getActiveTab().id;
	switch(tabActual)
	{
		case 'Itab1':
			var meta = gridIntAd.selModel.selections.keys;
			if(meta.length > 0)
			{
				Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarUniads);
			} 
			else 
			{
				Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
			}			
		break;	
		case 'Itab2':
			var Ubs = gridIntUb.selModel.selections.keys;
			if(Ubs.length>0)
			{
				Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarUbs);
			} 
			else 
			{
				Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
			}
		break;
		case 'Itab3':
			var problema = gridIntProb.selModel.selections.keys;
			if(problema.length > 0)
			{
				Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarProblema);
			} 
			else 
			{
				Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
			}
		break;
		case 'Itab4':
			var meta = gridIntVar.selModel.selections.keys;
			if(meta.length > 0)
			{
				Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarMeta);
			} 
			else 
			{
				Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
			}
		break;

	}
	
}
function borrarMeta(btn) 
{
//alert('eliminar metas');
	if (btn=='yes') 
	{
		var fila = gridIntVar.getSelectionModel().getSelected();
		if (fila)
		{		
				codinte = IdPadre;
				codmeta = gridIntVar.getSelectionModel().getSelected().get('cod_var');
				ano_presupuesto = gridIntVar.getSelectionModel().getSelected().get('ano_presupuesto');
				reg = "{'oper':'eliminarMetas',DatosMetas:[{'cod_var':'"+codmeta+"','codemp':'0001','codinte':'"+codinte+"'}]}";
 				//alert(reg);
 				Obj= eval('(' + reg + ')');
				ObjSon=JSON.stringify(Obj);
				parametros = 'ObjSon='+ObjSon; 
				Ext.Ajax.request({
				url : rutaIntepr,
				params : parametros,
				method: 'POST',
				success: function(resultad,request ){ 
				datos = resultad.responseText;
				//alert(datos);
				var Registros = datos.split("|");
				Cod = Registros[1];
					if(Cod=='1')
					{
						Ext.MessageBox.alert('Mensaje','Registro eliminado con éxito');
						gridIntVar.store.remove(fila);
						gridIntVar.store.commitChanges();
					}
					else
					{
						Ext.MessageBox.alert('Error', 'El registro');				
					}
				      },
						failure: function ( result, request)
					 { 
						Ext.MessageBox.alert('Error', result.responseText); 
					 } 
				    });
			gridIntProb.store.remove(fila);
			Ext.Msg.alert('Exito','Registro eliminado');				
			
			
			gridIntVar.store.remove(fila);
			Ext.Msg.alert('Exito','Registro eliminado');				
		}
	} 
}
	



function borrarIndi(btn) 
{
//alert('eliminar metas');
	if (btn=='yes') 
	{
		var fila = gridIntInd.getSelectionModel().getSelected();
		if(fila)
		{		
				codinte = IdPadre;
				codmeta = gridIntInd.getSelectionModel().getSelected().get('cod_ind');
				ano_presupuesto = gridIntInd.getSelectionModel().getSelected().get('ano_presupuesto');
				reg = "{'oper':'eliminarIndi',DatosIndi:[{'cod_ind':'"+codmeta+"','codemp':'0001','codinte':'"+codinte+"'}]}";
 				
 				Obj= eval('(' + reg + ')');
				ObjSon=JSON.stringify(Obj);
				parametros = 'ObjSon='+ObjSon; 
				Ext.Ajax.request({
				url : rutaIntepr,
				params : parametros,
				method: 'POST',
				success: function(resultad,request ){ 
				datos = resultad.responseText;
				//alert(datos);
				var Registros = datos.split("|");
				Cod = Registros[1];
					if(Cod=='1')
					{
						Ext.MessageBox.alert('Mensaje','Registro eliminado con éxito');
						gridIntInd.store.remove(fila);
						gridIntInd.store.commitChanges();
					}
					else
					{
						Ext.MessageBox.alert('Error', 'El registro');				
					}
				      },
						failure: function ( result, request)
					 { 
						Ext.MessageBox.alert('Error', result.responseText); 
					 } 
				    });
			gridIntInd.store.remove(fila);
			Ext.Msg.alert('Exito','Registro eliminado');							
		}
	} 
}












	
function borrarUniads(btn)
{
	if (btn=='yes') 
	{
		var selectedRow = gridIntAd.getSelectionModel().getSelected();
		if(selectedRow)
		{
					Coduac = gridIntAd.getSelectionModel().getSelected().get('coduac');
					Nivel = gridIntAd.getSelectionModel().getSelected().get('nivel');
					reg = "{'oper':'eliminarUnis',DatosAd:[{'coduac':'"+Coduac+"','nivel':'" + Nivel +"','codinte':'"+IdPadre+"','codemp':'0001'}]}";
 					Obj= eval('(' + reg + ')');
					ObjSon=JSON.stringify(Obj);
					parametros = 'ObjSon='+ObjSon; 
					Ext.Ajax.request({
					url : rutaIntepr,
					params : parametros,
					method: 'POST',
					success: function ( resultad, request ){ 
				    datos = resultad.responseText; 
					var Registros = datos.split("|");
					Cod = Registros[1];
						if(Cod=='1')
						{
							Ext.MessageBox.alert('Mensaje', 'Registro eliminado con éxito');
							gridIntAd.store.remove(selectedRow);
							gridIntAd.store.commitChanges();
							//alert(grid2.store.getCount());
							//ActualizarData();
						}
						else
						{
							Ext.MessageBox.alert('Error', 'El registro');				
						}
				      },
					failure: function ( result, request)
					 { 
						Ext.MessageBox.alert('Error', result.responseText); 
					 } 
				    });
			
	     		DataStore.remove(selectedRow);
		}
	  } 

}	

function borrarUbs(btn)
{
	if (btn=='yes') 
	{
		var selectedRow = gridIntUb.getSelectionModel().getSelected();
		if(selectedRow)
		{
					codubgeo1 = gridIntUb.getSelectionModel().getSelected().get('codubgeo1');
					codubgeo2 = gridIntUb.getSelectionModel().getSelected().get('codubgeo2');
					codubgeo3 = gridIntUb.getSelectionModel().getSelected().get('codubgeo3');
					codubgeo4 = gridIntUb.getSelectionModel().getSelected().get('codubgeo4'); 
					Nivel = gridIntUb.getSelectionModel().getSelected().get('nivel');
					reg = "{'oper':'eliminarUbs','NivelUb':'"+nivelUbicaciones+"',DatosUb:[{'codubgeo1':'"+codubgeo1+"','codubgeo2':'" + codubgeo2 +"','codinte':'"+IdPadre+"','codemp':'0001','codubgeo3':'"+codubgeo3+"','codubgeo4':'" +codubgeo4+"'}]}";
 					//alert(reg);
 					Obj= eval('(' + reg + ')');
					ObjSon=JSON.stringify(Obj);
					parametros = 'ObjSon='+ObjSon; 
					Ext.Ajax.request({
					url : rutaIntepr,
					params : parametros,
					method: 'POST',
					success: function( resultad, request ){ 
				    datos = resultad.responseText;
					var Registros = datos.split("|");
					Cod = Registros[1];
						if(Cod=='1')
						{
							Ext.MessageBox.alert('Mensaje', 'Registro eliminado con éxito');
							gridIntAd.store.remove(gridIntUb);
							gridIntAd.store.commitChanges();
							//alert(grid2.store.getCount());
							//ActualizarData();
						}
						else
						{
							Ext.MessageBox.alert('Error', 'El registro');				
						}
				      }
				      ,
					failure: function ( result, request)
					 { 
						Ext.MessageBox.alert('Error', result.responseText); 
					 } 
				    });
	     		DataStore.remove(selectedRow);
		}
	  } 
}	

function irQuitarEjeFin()
{
	var meta = gridIntGastos.selModel.selections.keys;
	if(meta.length > 0)
	{
		Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?', borrarCuentas);
	} 
	else 
	{
		Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
	}			
}

function irQuitarMeta()
{
	var meta = gridIntVar.selModel.selections.keys;
	if(meta.length > 0)
	{
		Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?',borrarMeta);
	} 
	else 
	{
		Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
	}			
}

function irQuitarInd()
{
	var meta = gridIntInd.selModel.selections.keys;
	if(meta.length > 0)
	{
		Ext.Msg.confirm('Alerta!','Realmente desea eliminar el registro?',borrarIndi);
	} 
	else 
	{
		Ext.Msg.alert('Alerta!','Seleccione un registro para eliminar');
	}			
}




function borrarCuentas(btn)
{
	if (btn=='yes') 
	{
		var fila = gridIntGastos.getSelectionModel().getSelected();
		var idx = gridIntGastos.store.indexOf(fila); 
		if (fila)
		{	
			codinte = IdPadre;
			codcuenta = gridIntGastos.getSelectionModel().getSelected().get('spg_cuenta');
			ano_presupuesto = gridIntGastos.getSelectionModel().getSelected().get('ano_presupuesto');
			reg = "{'oper':'eliminarCuentas',DatosGas:[{'sig_cuenta':'"+codcuenta+"','codemp':'0001','codinte':'"+codinte+"','ano_presupuesto':'"+ano_presupuesto+"'}]}";
 			Obj= eval('(' + reg + ')');
			ObjSon=JSON.stringify(Obj);
			parametros = 'ObjSon='+ObjSon; 
			Ext.Ajax.request({
			url : rutaIntepr,
			params : parametros,
			method: 'POST',
			success: function(resultad,request ){ 
			datos = resultad.responseText;
			//Ext.get('norte').dom.innerHTML=datos;
			//alert(datos);
			var Registros = datos.split("|");
			Cod = Registros[1];
			if(Cod=='1' || Cod=='2')
			{
				Ext.MessageBox.alert('Mensaje','Registro eliminado con éxito');
				
				if(!fila.get('montoglobal'))
				{
					gridIntGastos.store.remove(fila);	
				}
				else
				{
					auxNuevoReg = ActualizarDataCuentas2('sig_cuenta',fila.get('spg_cuenta'),idx);
					gridIntGastos.store.remove(fila);
					//fila.set('montoglobal','');
				}
				
				gridIntGastos.store.commitChanges();
			}
			else
			{
				Ext.MessageBox.alert('Error', 'El registro');				
			}
		  },
				failure: function ( result, request)
				{ 
					Ext.MessageBox.alert('Error', result.responseText); 
				} 
		});
		gridIntProb.store.remove(fila);
		Ext.Msg.alert('Exito','Registro eliminado');				
		}
	} 		
}
		

//Ext.get('LlamarCatalogosTabs2').on('click', function()
function irAgregarEjeFin()
{
	tabActual = tabs2.getActiveTab().id;
	switch(tabActual)
	{
		case 'Itab6':
			CatPlanCuentas();
			break;
			
	}
	
}
});