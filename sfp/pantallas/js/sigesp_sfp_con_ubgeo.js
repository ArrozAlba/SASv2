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
var Oper="";
 
ruta ='../../procesos/sigesp_sfp_con_ubgeopr.php';
Ext.onReady(function(){
    // basic tabs 1, built from existing content

function getobject()
{
	var myJSONObject ={
		"oper": 'catalogo', 
		"codnivel": "", 
		"tipoest": "",
		"nivel":"",
		"nombre_pest":""
	};	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
		  alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			Oper='Actualizando';
		}
		else
		{
			var DatosNuevo={"raiz":[{"codnivel":'',"tipoest":'',"nivel":'',"nombre_pest":''}]};
			//var DatosNuevo='';
		}	
		//alert(DatosNuevo)	
			RecordDef = Ext.data.Record.create([
			{name:'codnivel'},
			{name: 'nivel'},	// This field will use "occupation" as the mapping.
			{name: 'nombre_pest'}
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
			
			width:600,
			autoScroll:true,
                        border:true,
                        ds:DataStore,
                        cm: new Ext.grid.ColumnModel([
                           // new Ext.grid.RowNumberer(),
                         {header: "Nivel", width: 50, sortable: true, dataIndex: 'nivel'},
                            
			  {header: "Nombre de la pestaña", width:250, sortable: true, dataIndex: 'nombre_pest',editor: new Ext.form.TextField({allowBlank: false})}
                        ]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig: {
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
		   
		  
		   
              
		grid.render('Contenedor-Grid');
		
	}
	

});	
				
}

Ext.get('BtnGrabar').on('click', function()
{
	if(Oper=="incluyendo")
	{
		eve = 'incluirvarios';
		Mens = 'Incluido';
	}
	else
	{
		eve = 'actualizarvarios';
		Mens = 'Modificado';
	}
		
	numDatos = DataStore.getModifiedRecords();
	var reg = "{'oper':'"+ eve + "','datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
		
		if(i==0)
		{
			reg = reg + "{'codnivel':'" + numDatos[i].get('codnivel') +"','nivel':'" + numDatos[i].get('nivel') +"','nombre_pest':'" + numDatos[i].get('nombre_pest') +"'}";
		}	
		else
		{
			reg = reg + ",{'nivel':'" + numDatos[i].get('nivel') +"','nombre_pest':'" + numDatos[i].get('nombre_pest') +"'}";
		}
			
	}
	reg = reg + "]}";

	
	Obj= eval('(' + reg + ')');
	ObjSon=JSON.stringify(Obj);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultad, request ){ 
                datos = resultad.responseText;
		alert(datos);
		 var Registros = datos.split("|");
		Cod = Registros[1];
		if(Cod!='')
		{
			Ext.MessageBox.alert('Mensaje', 'Registro '+ Mens +' con exito ');
			oper='';		
			getobject();
		}
		else
		{
			Ext.MessageBox.alert('Mensaje', 'El registro con cota ');
						
		}
      },
	failure: function ( result, request)
	 { 
		Ext.MessageBox.alert('Error', result.responseText); 
	} 
      });
 	
	Oper='';
});


 Ext.get('BtnNuevo').on('click', function()
 {			
 
	var myJSONObject =[{
		"oper": 'buscarcodigo', 
		"cod_fuenfin":'',
		"denfuefin":'',
		"expfuefin":''
	}]

if(Oper!="incluyendo")
{
	for(i=5;i>=1;i--)
	{
		 var p = new RecordDef
			 (
	            {
					codnivel:'',
		            nivel: i,
		            nombre_pest: ''
				}
	                   
	          );
	              
	    DataStore.insert(0, p);	
	}	
		grid.startEditing(0, 1);
		Oper="incluyendo";	
}	
	
});


Ext.get('BtnElim').on('click',function()
{

	var Result;
	Ext.MessageBox.confirm('Confirmar', '¿Desea eliminar este registro?', Result);
	function Result(btn)
	{
		
	//	alert('sss');
		if(btn=='yes')
		{
			//alert('ss');
			var myJSONObject ={
				"oper": 'eliminar', 
				"codnivel": grid.getSelectionModel().getSelected().get('codnivel'),
				"codemp":'0001'
			
			     };	
			ObjSon=JSON.stringify(myJSONObject);
			parametros = 'ObjSon='+ObjSon; 
				     
			Mensa = "Eliminado";
			Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function ( resultad, request ) { 
				 datos = resultad.responseText;
						//alert(datos);
					
				 var Registros = datos.split("|");
				 if (Registros[1] == '1')
				 {
					Ext.MessageBox.alert('Mensaje','Registro '+Mensa + ' con éxito');
					DataStore.remove(grid.getSelectionModel().getSelected());
					
							
		  
				 }
				 else
				 {
				  Ext.MessageBox.alert('Error', Registros[0]);
				 }
			},
			failure: function ( result, request) { 
				Ext.MessageBox.alert('Error', result.responseText); 
			} 
		      });

		}
	
	};
//	}
    });












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
	success: function ( resultado, request ) { 
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

 
              
             




