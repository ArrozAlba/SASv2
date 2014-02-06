var Listo2 = false;
var Oper='';
var DatosNuevo ="";
var tabs='';
var combo1=''; 
var combo2=''; 
var combo3=''; 
var combo4=''; 
var combo5=''; 
var combo6=''; 
var combo7=''; 
var combo8=''; 
var combo9=''; 
var combo10=''; 
var combo11=''; 
var combo12=''; 
var combo13=''; 
var combo14=''; 
var combo15='';
var DataStore6='';
var DatosNuevo='';
var simple='';
var valor1='';  
var valor2='';
var grid='';
var anchoCombo=90;
var anchoTextoCombo=600;  
ruta2 ='../../procesos/sigesp_spe_comboubgeopr.php';
ruta3 ='../../procesos/sigesp_sfp_comboestpr.php';
ruta ='../../procesos/sigesp_spe_comboestpr.php';
rutaGrid ='../../procesos/sigesp_sfp_fuentefinpr.php';
rutaIntepr='../../procesos/sigesp_spe_intprogpr.php';
Ext.onReady(function(){
	
function getobject()
{
	var myJSONObject ={
		"oper": 'catalogo', 
		"cod_fuenfin": "", 
		"denfuefin": "",
		"expfuefin":""
	};	
	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaGrid,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
		 // alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			
		//alert(DatosNuevo)	
			RecordDef = Ext.data.Record.create([
			{name: 'cod_fuenfin'},     // "mapping" property not needed if it's the same as "name"
			{name: 'denfuefin'},
			{name: 'expfuefin'}	// This field will use "occupation" as the mapping.
			]);
			
			 DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                        RecordDef
			     
			      ),
				data: DatosNuevo
                        });
			
			 grid = new Ext.grid.EditorGridPanel({
			
		//	Ext.ns('Example'); 
		//	Ext.Example = Ext.extend(Ext.grid.EditorGridPanel, { 
		//	initComponent:function() { 
		//	Ext.apply(this, { 
			width:770,
			autoScroll:true,
                        border:true,
                        ds:DataStore,
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'cod_fuenfin'},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denfuefin',editor: new Ext.form.NumberField({allowBlank: false})},
			    {header: "Explicación", width: 350, sortable: true, dataIndex: 'expfuefin',editor: new Ext.form.TextField({allowBlank: false})}
							
                        ]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
		   
		  
		   
                }
		grid.render('grid-example');
		
	}
	


});	
}

function getGridInd()
{
var myJSONObject ={
		"oper": 'catalogo', 
		"cod_fuenfin": "", 
		"denfuefin": "",
		"expfuefin":""
	};	
	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaGrid,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
		  alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			
		//alert(DatosNuevo)	
			RecordDef = Ext.data.Record.create([
			{name: 'cod_fuenfin'},     // "mapping" property not needed if it's the same as "name"
			{name: 'denfuefin'},
			{name: 'expfuefin'}	// This field will use "occupation" as the mapping.
			]);
			
			 DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                              RecordDef
			     
			      ),
				data: DatosNuevo
                        });
			
			 grid = new Ext.grid.EditorGridPanel({
			
		//	Ext.ns('Example'); 
		//	Ext.Example = Ext.extend(Ext.grid.EditorGridPanel, { 
		//	initComponent:function() { 
		//	Ext.apply(this, { 
			width:770,
			autoScroll:true,
                        border:true,
                        ds:DataStore,
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'cod_fuenfin'},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denfuefin',editor: new Ext.form.NumberField({allowBlank: false})},
			    {header: "Explicación", width: 350, sortable: true, dataIndex: 'expfuefin',editor: new Ext.form.TextField({allowBlank: false})}
							
                        ]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
		   
		  
		   
                }
		grid.render('grid-example');
		
	}
	


});	
}
function getGridVari()
{
var myJSONObject ={
		"oper": 'catalogo', 
		"cod_fuenfin": "", 
		"denfuefin": "",
		"expfuefin":""
	};	
	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaGrid,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
		  alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			
		//alert(DatosNuevo)	
			RecordDef = Ext.data.Record.create([
			{name: 'cod_fuenfin'},     // "mapping" property not needed if it's the same as "name"
			{name: 'denfuefin'},
			{name: 'expfuefin'}	// This field will use "occupation" as the mapping.
			]);
			
			 DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                              RecordDef
			     
			      ),
				data: DatosNuevo
                        });
			
			 grid = new Ext.grid.EditorGridPanel({
			
		//	Ext.ns('Example'); 
		//	Ext.Example = Ext.extend(Ext.grid.EditorGridPanel, { 
		//	initComponent:function() { 
		//	Ext.apply(this, { 
			width:770,
			autoScroll:true,
                        border:true,
                        ds:DataStore,
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'cod_fuenfin'},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denfuefin',editor: new Ext.form.NumberField({allowBlank: false})},
			    {header: "Explicación", width: 350, sortable: true, dataIndex: 'expfuefin',editor: new Ext.form.TextField({allowBlank: false})}
							
                        ]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
		   
		  
		   
                }
		grid.render('grid-example');
		
	}
	


});	
}
function getGridGasto()
{
var myJSONObject ={
		"oper": 'catalogo', 
		"cod_fuenfin": "", 
		"denfuefin": "",
		"expfuefin":""
	};	
	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaGrid,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
		  alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			
		//alert(DatosNuevo)	
			RecordDef = Ext.data.Record.create([
			{name: 'cod_fuenfin'},     // "mapping" property not needed if it's the same as "name"
			{name: 'denfuefin'},
			{name: 'expfuefin'}	// This field will use "occupation" as the mapping.
			]);
			
			 DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                              RecordDef
			     
			      ),
				data: DatosNuevo
                        });
			
			 grid = new Ext.grid.EditorGridPanel({
			
		//	Ext.ns('Example'); 
		//	Ext.Example = Ext.extend(Ext.grid.EditorGridPanel, { 
		//	initComponent:function() { 
		//	Ext.apply(this, { 
			width:770,
			autoScroll:true,
                        border:true,
                        ds:DataStore,
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'cod_fuenfin'},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denfuefin',editor: new Ext.form.NumberField({allowBlank: false})},
			    {header: "Explicación", width: 350, sortable: true, dataIndex: 'expfuefin',editor: new Ext.form.TextField({allowBlank: false})}
							
                        ]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
		   
		  
		   
                }
		grid.render('grid-example');
		
	}
	


});	
}

function getGridProb()
{
var myJSONObject ={
		"oper": 'catalogo', 
		"cod_fuenfin": "", 
		"denfuefin": "",
		"expfuefin":""
	};	
	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaGrid,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
		  alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			
		//alert(DatosNuevo)	
			RecordDef = Ext.data.Record.create([
			{name: 'cod_fuenfin'},     // "mapping" property not needed if it's the same as "name"
			{name: 'denfuefin'},
			{name: 'expfuefin'}	// This field will use "occupation" as the mapping.
			]);
			
			 DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                              RecordDef
			     
			      ),
				data: DatosNuevo
                        });
			
			 grid = new Ext.grid.EditorGridPanel({
			
		//	Ext.ns('Example'); 
		//	Ext.Example = Ext.extend(Ext.grid.EditorGridPanel, { 
		//	initComponent:function() { 
		//	Ext.apply(this, { 
			width:770,
			autoScroll:true,
                        border:true,
                        ds:DataStore,
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'cod_fuenfin'},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denfuefin',editor: new Ext.form.NumberField({allowBlank: false})},
			    {header: "Explicación", width: 350, sortable: true, dataIndex: 'expfuefin',editor: new Ext.form.TextField({allowBlank: false})}
							
                        ]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
		   
		  
		   
                }
		grid.render('grid-example');
		
	}
	


});	

}


function getGridMedios()
{
var myJSONObject ={
		"oper": 'catalogo', 
		"cod_fuenfin": "", 
		"denfuefin": "",
		"expfuefin":""
	};	
	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaGrid,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
		  alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			
		//alert(DatosNuevo)	
			RecordDef = Ext.data.Record.create([
			{name: 'cod_fuenfin'},     // "mapping" property not needed if it's the same as "name"
			{name: 'denfuefin'},
			{name: 'expfuefin'}	// This field will use "occupation" as the mapping.
			]);
			
			 DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                              RecordDef
			     
			      ),
				data: DatosNuevo
                        });
			
			 grid = new Ext.grid.EditorGridPanel({
			
		//	Ext.ns('Example'); 
		//	Ext.Example = Ext.extend(Ext.grid.EditorGridPanel, { 
		//	initComponent:function() { 
		//	Ext.apply(this, { 
			width:770,
			autoScroll:true,
                        border:true,
                        ds:DataStore,
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'cod_fuenfin'},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denfuefin',editor: new Ext.form.NumberField({allowBlank: false})},
			    {header: "Explicación", width: 350, sortable: true, dataIndex: 'expfuefin',editor: new Ext.form.TextField({allowBlank: false})}
							
                        ]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
		   
		  
		   
                }
		grid.render('grid-example');
		
	}
	


});	
}
function getGridSupuestos()
{
var myJSONObject ={
		"oper": 'catalogo', 
		"cod_fuenfin": "", 
		"denfuefin": "",
		"expfuefin":""
	};	
	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaGrid,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
		  alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			
		//alert(DatosNuevo)	
			RecordDef = Ext.data.Record.create([
			{name: 'cod_fuenfin'},     // "mapping" property not needed if it's the same as "name"
			{name: 'denfuefin'},
			{name: 'expfuefin'}	// This field will use "occupation" as the mapping.
			]);
			
			 DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                              RecordDef
			     
			      ),
				data: DatosNuevo
                        });
			
			 grid = new Ext.grid.EditorGridPanel({
			
		//	Ext.ns('Example'); 
		//	Ext.Example = Ext.extend(Ext.grid.EditorGridPanel, { 
		//	initComponent:function() { 
		//	Ext.apply(this, { 
			width:770,
			autoScroll:true,
                        border:true,
                        ds:DataStore,
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'cod_fuenfin'},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denfuefin',editor: new Ext.form.NumberField({allowBlank: false})},
			    {header: "Explicación", width: 350, sortable: true, dataIndex: 'expfuefin',editor: new Ext.form.TextField({allowBlank: false})}
							
                        ]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
                }
		grid.render('grid-example');
		
	}
	
});	
}
		Ext.QuickTips.init(); 
		 tabs2= new Ext.TabPanel(
        {
            baseCls:'x-plain',
			renderTo:'tabs2',
			 activeTab: 0,
			 frame:true,
		    autoScroll:true,
            width:1024,
            height:500,
            listeners: {activate: ManejarTabActivo},
            modal: true,
            closeAction:'hide',
            plain: false
		    ,defaults: {frame:true, width:800, height: 200}
                ,items:[{title:'Financiamiento'},
                    {
                    title:'Gastos y Aplicaciones'
                },{
                    title:'Variables y Metas'
                },
                {
                    title:'Indicadores'
                }
		  
				]
                   
        });


   		Ext.QuickTips.init(); 
		 tabs= new Ext.TabPanel(
                   {
                   baseCls:'x-plain',
			renderTo: 'tabs1',
			 activeTab: 0,
			 frame:true,
		    autoScroll:true,
                    width:1024,
                    height:500,
		 			
                    modal: true,
                    closeAction:'hide',
                    plain: false
		    ,defaults: {frame:true, width:800, height: 200}
                   ,items:[{contentEl:'grid-example',title:'Supuestos'},
                    {
                    title:'Medios de Verificación'
                },{
                    title:'Problematica a Enfrentar'
                }
		  
				]
                   
        });



Ext.get('ImgRestar').on('click', function()
{

	var selectedKeys = grid.selModel.selections.keys;
        if(selectedKeys.length > 0) {
            Ext.Msg.confirm('ALERTA!','Realmente desea eliminar el registro?', deleteRecord);
        } else {
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
		{name: 'cod_fuenfin'},     // "mapping" property not needed if it's the same as "name"
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
			    root: 'raiz',                // The property which contains an Array of row objects
			     id: "id"   
			    
			},
                              RecordDef
			     
			      ),
				data: myObject
                        }),
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                 
                            {header: "Código", width: 30, sortable: true,   dataIndex: 'cod_fuenfin'},
                            {header: "Denominación", width: 50, sortable: true, dataIndex: 'denfuefin'},
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
        },
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


getobject();
	
function llenarCombo1()
{   
	var myJSONString ="{'oper':'catestpro','numest':'1','codest1':'','denest1': ''}";
	aux = eval('(' + myJSONString + ')');
	ObjSon=JSON.stringify(aux);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
		//alert(DatosNuevo)	
		
		}
		else
		{
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODEST1'},
			{name: 'CODEST2'},
			{name: 'CODEST3'},
			{name: 'CODEST4'},
			{name: 'CODEST5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENEST1'},
			{name: 'DENEST2'},
			{name: 'DENEST3'},
			{name: 'DENEST4'},
			{name: 'DENEST5'}
			]);
						
		var DataStore1 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
		   
	combo1 = new Ext.form.ComboBox({
    store: DataStore1,
    displayField:'DENEST1',
    valueField:'CODEST1',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo1',
    editable:false
    
});
combo1.addListener('select',cambio1);
}
});
}

function llenarCombo2(cod1)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '2',
		"cod1": cod1
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
	//	alert(DatosNuevo)	
		
		}
		else
		{
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODEST1'},
			{name: 'CODEST2'},
			{name: 'CODEST3'},
			{name: 'CODEST4'},
			{name: 'CODEST5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENEST1'},
			{name: 'DENEST2'},
			{name: 'DENEST3'},
			{name: 'DENEST4'},
			{name: 'DENEST5'}
			]);
						
		var DataStore2 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
		   

	
	combo2 = new Ext.form.ComboBox({
    store: DataStore2,
    displayField:'DENEST2',
    valueField:'CODEST2',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo2',
    editable:false
    
});
combo2.addListener('select',cambio2);
}
});
}
function llenarCombo3(cod1,cod2)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '3',
		"cod1": cod1,
		"cod2": cod2
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
		//	alert(DatosNuevo)	
		
		}
		else
		{
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODEST1'},
			{name: 'CODEST2'},
			{name: 'CODEST3'},
			{name: 'CODEST4'},
			{name: 'CODEST5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENEST1'},
			{name: 'DENEST2'},
			{name: 'DENEST3'},
			{name: 'DENEST4'},
			{name: 'DENEST5'}
			]);
						
		var DataStore3 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
		   
	combo3 = new Ext.form.ComboBox({
    store: DataStore3,
    displayField:'DENEST3',
    valueField:'CODEST3',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo3',
    editable:false
    
});
combo3.addListener('select',cambio3);
}
});
}

function llenarCombo4(cod1,cod2,cod3)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '4',
		"cod1": cod1,
		"cod2": cod2,
		"cod3": cod3
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
		//	alert(DatosNuevo)	
		
		 }
		else
		{
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODEST1'},
			{name: 'CODEST2'},
			{name: 'CODEST3'},
			{name: 'CODEST4'},
			{name: 'CODEST5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENEST1'},
			{name: 'DENEST2'},
			{name: 'DENEST3'},
			{name: 'DENEST4'},
			{name: 'DENEST5'}
			]);
						
		var DataStore4 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
		   

	
	combo4 = new Ext.form.ComboBox({
    store: DataStore4,
    displayField:'DENEST4',
    valueField:'CODEST4',
    typeAhead: true,
    mode:'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo4',
    editable:false
    
});
 combo4.addListener('select',cambio4);
}
});
}



function llenarCombo5(cod1,cod2,cod3,cod4)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '5',
		"cod1": cod1,
		"cod2": cod2,
		"cod3": cod3,
		"cod4": cod4
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			//alert(DatosNuevo)	
		 }
		else
		{
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODEST1'},
			{name: 'CODEST2'},
			{name: 'CODEST3'},
			{name: 'CODEST4'},
			{name: 'CODEST5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENEST1'},
			{name: 'DENEST2'},
			{name: 'DENEST3'},
			{name: 'DENEST4'},
			{name: 'DENEST5'}
			]);
						
		var DataStore5 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
	combo5 = new Ext.form.ComboBox({
    store: DataStore5,
    displayField:'DENEST5',
    valueField:'CODEST5',
    typeAhead: true,
    mode:'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo5',
    editable:false
    
});
 //combo4.addListener('select',cambio4);
}
});
}




function cambio1()
{
	valor1=combo1.getValue();
	if(combo2=='')
	{
		llenarCombo2(valor1);	
	}
	else
	{
		ActualizarData(valor1,0,0,0,'2');
	}
	
}	 	

function cambio2()
{

	valor2=combo2.getValue();
	if(combo3=='')
	{
		llenarCombo3(valor1,valor2);	
	}
	else
	{
		ActualizarData(valor1,valor2,0,0,'3');
	}
	
}	 

function cambio3()
{
	valor3=combo3.getValue();
	if(combo4=='')
	{
		llenarCombo4(valor1,valor2,valor3);
	}
	else
	{
		ActualizarData(valor1,valor2,valor3,0,'4');
	}

}	
function cambio4()
{
	valor4=combo4.getValue();
	if(combo5=='')
	{
		llenarCombo5(valor1,valor2,valor3,valor4);
	}
	else
	{
		ActualizarData(valor1,valor2,valor3,valor4,'5');
	}

}	


function ActualizarData(cod1,cod2,cod3,cod4,nivel)
{
	DatosEnBlanco = {"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
	
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": nivel,
		"cod1": cod1,
		"cod2": cod2,	
		"cod3": cod3, 
		"cod4": cod4
		};

ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	  Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
	
			if(DatosNuevo.raiz==null)
			{
				 DatosNuevo={"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
			}	
				switch(nivel)
				{
					case '2':
					combo2.clearValue();
					combo2.store.loadData(DatosNuevo);
					combo3.clearValue();
					combo3.store.loadData(DatosEnBlanco);
					combo4.clearValue();
					combo4.store.loadData(DatosEnBlanco);
					combo5.clearValue();
					combo5.store.loadData(DatosEnBlanco);
					break;
					case '3':
					combo3.clearValue();
					combo3.store.loadData(DatosNuevo);
					combo4.clearValue();
					combo4.store.loadData(DatosEnBlanco);
					combo5.clearValue();
					combo5.store.loadData(DatosEnBlanco);
					break;
					case '4':
					combo4.clearValue();
					combo4.store.loadData(DatosNuevo);
					combo5.clearValue();
					combo5.store.loadData(DatosEnBlanco);
					break;
					case '5':
					combo5.store.loadData(DatosNuevo);
					break;
	
				}
			
			
		}
}
});
	
}

llenarCombo1(1);
	
	
function llenarCombo6()
{   
	var myJSONString ="{'oper':'catestpro','numest':'1','codest1':'','denest1': ''}";
	aux = eval('(' + myJSONString + ')');
	ObjSon=JSON.stringify(aux);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	
		var DatosNuevo={"raiz":[{"codemp":'',"codest":'',"codmun":'',"codpai":'',"codpar":'',"codsector":'',"codmanzana":'',"codparcela":'',"despai":'',"desest":'',"denmun":'',"denpar":'',"denominacion":''}]};
	
		
			RecordDef = Ext.data.Record.create([
			{name: 'codemp'},     
			{name: 'codmun'},
			{name: 'codpai'},
			{name: 'codpar'},
			{name: 'codest'},
			{name: 'codsector'},
			{name: 'codmanzana'},
			{name: 'codparcela'},
			{name: 'despai'},
			{name: 'desest'},
			{name: 'denmun'},
			{name: 'denpar'},
			{name: 'denominacion'}
				// This field will use "occupation" as the mapping.
			]);
						
		 DataStore6 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
	
	
	Ext.Ajax.request({
	url : ruta2,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			DataStore6.loadData(DatosNuevo);
		//alert(DatosNuevo)	
		
		}
       
}
});

	combo6 = new Ext.form.ComboBox({
    store: DataStore6,
    displayField:'despai',
    valueField:'codpai',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    grow:true,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo6'

	})
	
	combo6.addListener('select',cambio6);
}

function llenarCombo7(cod1)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '2',
		"cod1": cod1
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta2,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
	//	alert(DatosNuevo)	
		
		}
		else
		{
		
			var DatosNuevo={"raiz":[{"codemp":'',"codest":'',"codmun":'',"codpai":'',"codpar":'',"codsector":'',"codmanzana":'',"codparcela":'',"despai":'',"desest":'',"denmun":'',"denpar":'',"denominacion":''}]};
	
}
	RecordDef = Ext.data.Record.create([
			{name: 'codemp'},     
			{name: 'codmun'},
			{name: 'codpai'},
			{name: 'codpar'},
			{name: 'codest'},
			{name: 'codsector'},
			{name: 'codmanzana'},
			{name: 'codparcela'},
			{name: 'despai'},
			{name: 'desest'},
			{name: 'denmun'},
			{name: 'denpar'},
			{name: 'denominacion'}
			
		]);
						
		var DataStore2 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
		   

	
	combo7 = new Ext.form.ComboBox({
    store: DataStore2,
    displayField:'desest',
    valueField:'codest',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo7'
    
});
combo7.addListener('select',cambio7);

}

});
}
function llenarcombo8(cod1,cod2)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '3',
		"cod1": cod1,
		"cod2": cod2
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta2,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
		//	alert(DatosNuevo)	
		
		}
		else
		{
		
		var DatosNuevo={"raiz":[{"codemp":'',"codest":'',"codmun":'',"codpai":'',"codpar":'',"codsector":'',"codmanzana":'',"codparcela":'',"despai":'',"desest":'',"denmun":'',"denpar":'',"denominacion":''}]};
	
		}
	RecordDef = Ext.data.Record.create([
			{name: 'codemp'},     
			{name: 'codmun'},
			{name: 'codpai'},
			{name: 'codpar'},
			{name: 'codest'},
			{name: 'codsector'},
			{name: 'codmanzana'},
			{name: 'codparcela'},
			{name: 'despai'},
			{name: 'desest'},
			{name: 'denmun'},
			{name: 'denpar'},
			{name: 'denominacion'}
				// This field will use "occupation" as the mapping.
			]);
						
		var DataStore3 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
		   
	combo8 = new Ext.form.ComboBox({
    store: DataStore3,
    displayField:'denmun',
    valueField:'codmun',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo8'
    
});
combo8.addListener('select',cambio8);
}
});
}

function llenarcombo9(cod1,cod2,cod3)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '4',
		"cod1": cod1,
		"cod2": cod2,
		"cod3": cod3
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta2,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
		//	alert(DatosNuevo)	
		
		 }
		else
		{
		
		var DatosNuevo={"raiz":[{"codemp":'',"codest":'',"codmun":'',"codpai":'',"codpar":'',"codsector":'',"codmanzana":'',"codparcela":'',"despai":'',"desest":'',"denmun":'',"denpar":'',"denominacion":''}]};
	
		}
	RecordDef = Ext.data.Record.create([
			{name: 'codemp'},     
			{name: 'codmun'},
			{name: 'codpai'},
			{name: 'codpar'},
			{name: 'codest'},
			{name: 'codsector'},
			{name: 'codmanzana'},
			{name: 'codparcela'},
			{name: 'despai'},
			{name: 'desest'},
			{name: 'denmun'},
			{name: 'denpar'},
			{name: 'denominacion'}
			// This field will use "occupation" as the mapping.
			]);
						
		var DataStore4 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
		   

	
	combo9 = new Ext.form.ComboBox({
    store: DataStore4,
    displayField:'DENEST4',
    valueField:'CODEST4',
    typeAhead: true,
    mode:'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo9'
    
});
 combo9.addListener('select',cambio9);
}
});
}



function llenarCombo10(cod1,cod2,cod3,cod4)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '5',
		"cod1": cod1,
		"cod2": cod2,
		"cod3": cod3,
		"cod4": cod4
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta2,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			//alert(DatosNuevo)	
		 }
		else
		{	
		var DatosNuevo={"raiz":[{"codemp":'',"codest":'',"codmun":'',"codpai":'',"codpar":'',"codsector":'',"codmanzana":'',"codparcela":'',"despai":'',"desest":'',"denmun":'',"denpar":'',"denominacion":''}]};
	
		}
	RecordDef = Ext.data.Record.create([
			{name: 'codemp'},     
			{name: 'codmun'},
			{name: 'codpai'},
			{name: 'codpar'},
			{name: 'codest'},
			{name: 'codsector'},
			{name: 'codmanzana'},
			{name: 'codparcela'},
			{name: 'despai'},
			{name: 'desest'},
			{name: 'denmun'},
			{name: 'denpar'},
			{name: 'denominacion'}
				// This field will use "occupation" as the mapping.
			]);

					
		var DataStore5 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
	combo5 = new Ext.form.ComboBox({
    store: DataStore5,
    displayField:'DENEST5',
    valueField:'CODEST5',
    typeAhead: true,
    mode:'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo10'
});
 //combo10.addListener('select',cambio11);
}
});
}


function cambio6()
{
	valor1=combo6.getValue();
	if(combo7=='')
	{
		llenarCombo7(valor1);	
	}
	else
	{
		ActualizarData2(valor1,0,0,0,'2');
	}
	
}	 	

function cambio7()
{

	valor2=combo7.getValue();
	if(combo8=='')
	{
		llenarcombo8(valor1,valor2);	
	}
	else
	{
		ActualizarData2(valor1,valor2,0,0,'3');
	}
	
}	 

function cambio8()
{
	valor3=combo8.getValue();
	if(combo9=='')
	{
		llenarcombo9(valor1,valor2,valor3);
	}
	else
	{
		ActualizarData2(valor1,valor2,valor3,0,'4');
	}

}	
function cambio9()
{
	valor4=combo9.getValue();
	if(combo5=='')
	{
		llenarCombo10(valor1,valor2,valor3,valor4);
	}
	else
	{
		ActualizarData2(valor1,valor2,valor3,valor4,'5');
	}

}	


function ActualizarData2(cod1,cod2,cod3,cod4,nivel)
{
	DatosEnBlanco = {"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
	
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": nivel,
		"cod1": cod1,
		"cod2": cod2,	
		"cod3": cod3, 
		"cod4": cod4
		};

ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	  Ext.Ajax.request({
	url : ruta2,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
	
			if(DatosNuevo.raiz==null)
			{
				 DatosNuevo={"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
			}	
				switch(nivel)
				{
					case '2':
					combo7.clearValue();
					combo7.store.loadData(DatosNuevo);
					combo8.clearValue();
					combo8.store.loadData(DatosEnBlanco);
					combo9.clearValue();
					combo9.store.loadData(DatosEnBlanco);
					combo10.clearValue();
					combo10.store.loadData(DatosEnBlanco);
					break;
					case '3':
					combo8.clearValue();
					combo8.store.loadData(DatosNuevo);
					combo9.clearValue();
					combo9.store.loadData(DatosEnBlanco);
					combo10.clearValue();
					combo10.store.loadData(DatosEnBlanco);
					break;
					case '4':
					combo9.clearValue();
					combo9.store.loadData(DatosNuevo);
					combo10.clearValue();
					combo10.store.loadData(DatosEnBlanco);
					break;
					case '5':
					combo10.store.loadData(DatosNuevo);
					break;
	
				}
			
			
		}
}
});
	
}

llenarCombo6(1);


function llenarCombo11()
{   
	var myJSONString ="{'oper':'catestpro','numest':'1','codest1':'','denest1': ''}";
	aux = eval('(' + myJSONString + ')');
	ObjSon=JSON.stringify(aux);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta3,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
		//alert(DatosNuevo)	
		
		}
		else
		{
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODESTPRO1":'',"CODESTPRO2":'',"CODESTPRO3":'',"CODESTPRO4":'',"CODESTPRO5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENESTPRO1":'',"DENESTPRO2":'',"DENESTPRO3":'',"DENESTPRO4":'',"DENESTPRO5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODESTPRO1'},
			{name: 'CODESTPRO2'},
			{name: 'CODESTPRO3'},
			{name: 'CODESTPRO4'},
			{name: 'CODESTPRO5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENESTPRO1'},
			{name: 'DENESTPRO2'},
			{name: 'DENESTPRO3'},
			{name: 'DENESTPRO4'},
			{name: 'DENESTPRO5'}
			]);
						
		var DataStore1 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
		   
	combo11 = new Ext.form.ComboBox({
    store: DataStore1,
    displayField:'DENESTPRO1',
    valueField:'CODESTPRO1',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo11',
    editable:false
    
});
combo11.addListener('select',cambio11);
}
});
}

function llenarCombo12(cod1)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '2',
		"cod1": cod1
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
	//	alert(DatosNuevo)	
		
		}
		else
		{
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODESTPRO1":'',"CODESTPRO2":'',"CODESTPRO3":'',"CODESTPRO4":'',"CODESTPRO5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENESTPRO1":'',"DENESTPRO2":'',"DENESTPRO3":'',"DENESTPRO4":'',"DENESTPRO5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODESTPRO1'},
			{name: 'CODESTPRO2'},
			{name: 'CODESTPRO3'},
			{name: 'CODESTPRO4'},
			{name: 'CODESTPRO5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENESTPRO1'},
			{name: 'DENESTPRO2'},
			{name: 'DENESTPRO3'},
			{name: 'DENESTPRO4'},
			{name: 'DENESTPRO5'}
			]);
						
		var DataStore2 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
		   

	
	combo12 = new Ext.form.ComboBox({
    store: DataStore2,
    displayField:'DENESTPRO2',
    valueField:'CODESTPRO2',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo12',
    editable:false
    
});
combo12.addListener('select',cambio12);
}
});
}
function llenarCombo13(cod1,cod2)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '3',
		"cod1": cod1,
		"cod2": cod2
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta3,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
		//	alert(DatosNuevo)	
		
		}
		else
		{
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODESTPRO1":'',"CODESTPRO2":'',"CODESTPRO3":'',"CODESTPRO4":'',"CODESTPRO5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENESTPRO1":'',"DENESTPRO2":'',"DENESTPRO3":'',"DENESTPRO4":'',"DENESTPRO5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODESTPRO1'},
			{name: 'CODESTPRO2'},
			{name: 'CODESTPRO3'},
			{name: 'CODESTPRO4'},
			{name: 'CODESTPRO5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENESTPRO1'},
			{name: 'DENESTPRO2'},
			{name: 'DENESTPRO3'},
			{name: 'DENESTPRO4'},
			{name: 'DENESTPRO5'}
			]);
						
		var DataStore3 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
		   
	combo13 = new Ext.form.ComboBox({
    store: DataStore3,
    displayField:'DENESTPRO3',
    valueField:'CODESTPRO3',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo13',
    editable:false
    
});
combo13.addListener('select',cambio13);
}
});
}

function llenarCombo14(cod1,cod2,cod3)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '4',
		"cod1": cod1,
		"cod2": cod2,
		"cod3": cod3
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta3,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
		//	alert(DatosNuevo)	
		
		 }
		else
		{
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODESTPRO1":'',"CODESTPRO2":'',"CODESTPRO3":'',"CODESTPRO4":'',"CODESTPRO5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENESTPRO1":'',"DENESTPRO2":'',"DENESTPRO3":'',"DENESTPRO4":'',"DENESTPRO5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODESTPRO1'},
			{name: 'CODESTPRO2'},
			{name: 'CODESTPRO3'},
			{name: 'CODESTPRO4'},
			{name: 'CODESTPRO5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENESTPRO1'},
			{name: 'DENESTPRO2'},
			{name: 'DENESTPRO3'},
			{name: 'DENESTPRO4'},
			{name: 'DENESTPRO5'}
			]);
						
		var DataStore4 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
		   

	
	combo14 = new Ext.form.ComboBox({
    store: DataStore4,
    displayField:'DENESTPRO4',
    valueField:'CODESTPRO4',
    typeAhead: true,
    mode:'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo14',
    editable:false
    
});
 combo14.addListener('select',cambio14);
}
});
}


function llenarCombo15(cod1,cod2,cod3,cod4)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '5',
		"cod1": cod1,
		"cod2": cod2,
		"cod3": cod3,
		"cod4": cod4
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta3,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			//alert(DatosNuevo)	
		 }
		else
		{
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODESTPRO1":'',"CODESTPRO2":'',"CODESTPRO3":'',"CODESTPRO4":'',"CODESTPRO5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENESTPRO1":'',"DENESTPRO2":'',"DENESTPRO3":'',"DENESTPRO4":'',"DENESTPRO5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODESTPRO1'},
			{name: 'CODESTPRO2'},
			{name: 'CODESTPRO3'},
			{name: 'CODESTPRO4'},
			{name: 'CODESTPRO5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENESTPRO1'},
			{name: 'DENESTPRO2'},
			{name: 'DENESTPRO3'},
			{name: 'DENESTPRO4'},
			{name: 'DENESTPRO5'}
			]);
						
		var DataStore5 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
	combo15 = new Ext.form.ComboBox({
    store: DataStore5,
    displayField:'DENESTPRO5',
    valueField:'CODESTPRO5',
    typeAhead: true,
    mode:'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo15',
    editable:false
    
});
 //combo14.addListener('select',cambio14);
}
});
}




function cambio11()
{
	valor1=combo11.getValue();
	if(combo12=='')
	{
		llenarCombo12(valor1);	
	}
	else
	{
		ActualizarData(valor1,0,0,0,'2');
	}
	
}	 	

function cambio12()
{

	valor2=combo12.getValue();
	if(combo13=='')
	{
		llenarCombo13(valor1,valor2);	
	}
	else
	{
		ActualizarData(valor1,valor2,0,0,'3');
	}
	
}	 

function cambio13()
{
	valor3=combo13.getValue();
	if(combo14=='')
	{
		llenarCombo14(valor1,valor2,valor3);
	}
	else
	{
		ActualizarData(valor1,valor2,valor3,0,'4');
	}

}	
function cambio14()
{
	valor4=combo14.getValue();
	if(combo15=='')
	{
		llenarCombo15(valor1,valor2,valor3,valor4);
	}
	else
	{
		ActualizarData(valor1,valor2,valor3,valor4,'5');
	}

}	


function ActualizarData(cod1,cod2,cod3,cod4,nivel)
{
	DatosEnBlanco = {"raiz":[{"CODEMP":'',"CODESTPRO1":'',"CODESTPRO2":'',"CODESTPRO3":'',"CODESTPRO4":'',"CODESTPRO5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENESTPRO1":'',"DENESTPRO2":'',"DENESTPRO3":'',"DENESTPRO4":'',"DENESTPRO5":''}]};
	
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": nivel,
		"cod1": cod1,
		"cod2": cod2,	
		"cod3": cod3, 
		"cod4": cod4
		};

ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	  Ext.Ajax.request({
	url : ruta3,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
	
			if(DatosNuevo.raiz==null)
			{
				 DatosNuevo={"raiz":[{"CODEMP":'',"CODESTPRO1":'',"CODESTPRO2":'',"CODESTPRO3":'',"CODESTPRO4":'',"CODESTPRO5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENESTPRO1":'',"DENESTPRO2":'',"DENESTPRO3":'',"DENESTPRO4":'',"DENESTPRO5":''}]};
			}	
				switch(nivel)
				{
					case '2':
					combo12.clearValue();
					combo12.store.loadData(DatosNuevo);
					combo13.clearValue();
					combo13.store.loadData(DatosEnBlanco);
					combo14.clearValue();
					combo14.store.loadData(DatosEnBlanco);
					combo15.clearValue();
					combo15.store.loadData(DatosEnBlanco);
					break;
					case '3':
					combo13.clearValue();
					combo13.store.loadData(DatosNuevo);
					combo14.clearValue();
					combo14.store.loadData(DatosEnBlanco);
					combo15.clearValue();
					combo15.store.loadData(DatosEnBlanco);
					break;
					case '4':
					combo14.clearValue();
					combo14.store.loadData(DatosNuevo);
					combo15.clearValue();
					combo15.store.loadData(DatosEnBlanco);
					break;
					case '5':
					combo15.store.loadData(DatosNuevo);
					break;
	
				}
			
			
		}
}
});
	
}

llenarCombo11(1);

		RecordDef = Ext.data.Record.create([
			{name: 'CODORGEJ'},     
			{name: 'NOMBRE'}
		]);
		
		var DatosNuevoOrg={"raiz":[{"CODEMP":'',"CODORGEJ":'001',"NOMBRE":'Organo Ejecutor1'},{"CODORGEJ":'002',"NOMBRE":'Organo Ejecutor2'}]};
		
		var DataStoreOrg =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevoOrg),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
		),
		   data: DatosNuevoOrg
         });
	comboOrganoE = new Ext.form.ComboBox({
    displayField:'NOMBRE',
    store: DataStoreOrg,
    valueField:'CODORGEJ',
    fieldLabel:'Seleccione una',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    editable:false
});


	comboOrgan = new Ext.form.ComboBox({
    displayField:'NOMBRE',
    store: DataStoreOrg,
    valueField:'CODORGEJ',
    fieldLabel:'Seleccione una',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    editable:false

});


  simple = new Ext.FormPanel({
  labelWidth: 75, // label settings here cascade unless overridden,
  title: 'Datos del proyecto',
  bodyStyle:'padding:5px 5px 0',
  renderTo:'comboOrgano',
  defaults: {width: 530},   
  items: [{
  xtype:'textfield', 
  fieldLabel: 'Organo que invierte',
  name: 'Nombre',
  id: 'txtnomcon',
  maxLength: 10,
  labelStyle:'width:120px',
  maxLengthText: 'El campo excede la longitud máxima',
  allowBlank:false,
  width: 200
    },{
		 	xtype:'combo',
	    	fieldLabel:'Organo Ejecutor',
	        displayField:'NOMBRE',
		    store: DataStoreOrg,
		    labelStyle:'width:120px',
		    valueField:'CODORGEJ',
		    triggerAction: 'all',
		    width :anchoCombo,
		    listWidth:anchoTextoCombo,
		    emptyText:'Seleccione una',
		    selectOnFocus:true,
		    width: 200,
		    editable:false
	},{
	xtype:'panel',
	layout:'table',
	width:700,
	id:'main-panel',
    items:[{
    title:'Ubicacion Geográfica',
    contentEl:'DivUb'
     }]
    
	}]
	});

	simple2 = new Ext.FormPanel({
	labelWidth: 75, // label settings here cascade unless overridden,
	title: 'Datos del proyecto',
	bodyStyle:'padding:5px 5px 0',
	renderTo:'progra',
	defaults: {width: 530},   
	items: [{
	xtype:'panel',
	layout:'table',
	width:700,
	id:'main-panel',
    items:[{
    title:'Estructura del Plan',
    contentEl:'DivEst'
     }]},
	 {
	xtype:'panel',
	layout:'table',
	width:700,
    items:[{
    title:'Estructura Presupuestaria',
    contentEl:'DivEstSfp'
     }]}
	]
	});


	var item1 = new Ext.Panel({
	    title: 'Problematica a Enfrentar',
	    contentEl:'tabs1',
		cls:'empty'
     });

            var item2 = new Ext.Panel({
                title: 'Asignacion Presupuestaria',
                contentEl:'tabs2',
                cls:'empty'
            });
 Ext.state.Manager.setProvider(new Ext.state.CookieProvider());        
       var viewport = new Ext.Viewport({
            layout:'border',
            items:[
                new Ext.BoxComponent({ // raw
                    region:'north',
                    el: 'norte',
                    height:100
                }),
                new Ext.Panel({
                region:'south',
                width: 210,
                height:250,
                bodyStyle:'background-color:#DFE8F6',
                layout:'accordion',
                items: [item1,item2]
            })
			, new Ext.TabPanel({
                            border:false,
                            activeTab:0,
                            region:'center',
                            items:[
							{
                                contentEl:'progra',
                                title: 'Integración Presupuestaria de Proyecto',
                                autoScroll:true

                            }
							]
                    
                })]
         })
         
	  
	
function ManejarTabActivo()
{
	
}

Ext.get('grabar').on('click', function()
{
		
	numDatos = DataStore.getModifiedRecords();	
	var reg = "{";
	reg = reg + "'oper':'incluirInt','codemp':'0001','codinte':'','CODEST1':'"+combo1.getValue()+"','CODEST2':'"+combo2.getValue()+"','CODORGEJ':'0001','ano_desde':'2007','ano_hasta':'2008','estcla_p':'ttt','ano_presupuesto':'443','estcla':'ww";


if(combo3!='')
{
	reg = reg + "','CODEST3':'"+combo3.getValue();
}

if(combo4!='')
{
	reg = reg + "','CODEST4':'"+combo4.getValue();
}

if(combo5!='')
{
	reg = reg + "','CODEST5':'"+combo5.getValue();
}

if(combo6!='')
{
	reg = reg + "','codpai':'"+combo6.getValue();
}

if(combo7!='')
{
	reg = reg + "','codest':'"+combo7.getValue();
}

if(combo8!='')
{
	reg = reg + "','codmun':'"+combo8.getValue();
}

if(combo9!='')
{
	reg = reg + "','codpar':'"+combo9.getValue();
}

if(combo10!='')
{
	reg = reg + "','codsector':'"+combo10.getValue();
}

if(combo11!='')
{
	reg = reg + "','codestpro1':'"+combo11.getValue();
}

if(combo12!='')
{
	reg = reg + "','codestpro2':'"+combo12.getValue();
}

if(combo13!='')
{
	reg = reg + "','codestpro3':'"+combo13.getValue();
}

if(combo14!='')
{
	reg = reg + "','codestpro4':'"+combo14.getValue();
}

if(combo15!='')
{
	reg = reg + "','codestpro5':'"+combo15.getValue();
}






	reg = reg + "'}";

	
	Obj= eval('(' + reg + ')');
	ObjSon=JSON.stringify(Obj);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function ( resultad, request ){ 
        datos = resultad.responseText;
		alert(datos);
	
		
      },
	failure: function ( result, request)
	 { 
		Ext.MessageBox.alert('Error', result.responseText); 
	 } 
      });
 	
	
});

});