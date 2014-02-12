/*
 * Ext JS Library 2.0.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * http://extjs.com/license
 */
 
 var gridOnOff = false;
var winOnOff = false;
var datos = null;
var grid = null;
var win = null;
var unavez = false;
var parametros='';
var ruta = '';
var RecordDef;
var  grid2='';
var DataStore='';
var DatosNuevo ="";
 
ruta ='../../procesos/sigesp_sfp_fuentefinpr.php';
Ext.onReady(function(){
    // basic tabs 1, built from existing content


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
	url : ruta,
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
                        viewConfig: {
                            forceFit:true	
                        },
			autoHeight:true,
			stripeRows: true
                   });
		   
		  
		   
                }
		grid.render('grid-example');
		
	}
	






});	
		
		
		
	
		
	





	//mygrid  = new Ext.Example();
		//Ext.reg('examplegrid', Ext.Example); 
		//alert(Ext.Example.superclass);
		//Ext.reg('combo1', combo); 
		//Ext.reg('', combo); 
		Ext.QuickTips.init(); 
		 tabs= new Ext.TabPanel(
                   {
                   baseCls:'x-plain',
			renderTo: 'tabs1',
			 activeTab: 0,
			 frame:true,
			//layout:'table',
			//layoutConfig:{columns:1},
                    //title: 'Cat&aacute;logo de Fuente de Financiamiento',
		    autoScroll:true,
                    width:800,
                    height:500,
		    //x:300,
		    //y:300,
		    style:'margin-left:120px;margin-top:40px',
		 //  anchor:'50% 30%',
                    modal: true,
                    closeAction:'hide',
                    plain: false
		    ,defaults: {frame:true, width:800, height: 200}
                   ,items:[{contentEl:'grid-example',title:'Fuentes de Financiamiento'},
                    {
                    title:'Item 2'
                },{
                    title:'Item 3'
                }
		    /* { 
					xtype:'tabpanel' 
					//,anchor:'50% 30%'
				//	,defaults:{ayout:'fit'} 
					,activeItem:0 
					,items: [
							{title:'Grid Tab' 
							,id:'gridtab2' 
							,xtype:'examplegrid' 
							,autoScroll:true 
							},{ 
							title:'Grid Tab' 
							,id:'gridtab' 
							,xtype:'examplegrid' 
							,autoScroll:true 
							}
							
					     ] 
		   }*/
		],
                    buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {
                 
		 
		 
		 
                   /*   Ext.get('txtcod').dom.value = grid.getSelectionModel().getSelected().get('cod_fuenfin');
                      Ext.get('txtden').dom.value = grid.getSelectionModel().getSelected().get('denfuefin');
                      Ext.get('txtexp').dom.value = grid.getSelectionModel().getSelected().get('expfuefin');
		      Ext.get('actualizar').dom.value = 'si'; */
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

	
		
		
			
		




}

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

		/*	editEvent.record.id = newID;
                        editEvent.record.set('nuevo','no');
                        editEvent.record.set('id',newID);
			*/
                       // DataStore.commitChanges();

		 
		 
	/*	 
                      Ext.get('txtcod').dom.value = grid.getSelectionModel().getSelected().get('cod_fuenfin');
                      Ext.get('txtden').dom.value = grid.getSelectionModel().getSelected().get('denfuefin');
                      Ext.get('txtexp').dom.value = grid.getSelectionModel().getSelected().get('expfuefin');
		      Ext.get('actualizar').dom.value = 'si'; */
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
		
});

 
              
             




