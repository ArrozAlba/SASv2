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
var grid3 = null;
var grid4 = null; 
var win = null;
var unavez = false;
var parametros='';
var ruta = '';
var RecordDef;
var winForms='';
var grid2='';
var DataStore='';
var formFormula='';
var Tipoub='';
var DatosNuevo ="";
var codpai='';
var codest='';
var codmun='';
var nivel1='';	

var Oper=new Array();
 
ruta ='../../procesos/sigesp_sfp_con_estplanpr.php';
pantalla ='sigesp_sfp_con_estprog.php';
Ext.onReady(function()
{
ObtenerSesion(ruta,pantalla);
function getobject()
{
	var myJSONObject ={
			"oper": 'catalogo', 
			"codnivel": "", 
			"tipo": "PL",
			"nivel":"",
			"numcar":"",
			"nombre_pest":""
	};	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado, request){ 
		  datos = resultado.responseText;
		//  alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');	
			Oper[0]='Actualizando';
    	 }
		else
		{
			Oper[0]='incluyendo';
			var DatosNuevo={"raiz":[{"codnivel":'',"tipoest":'',"nivel":'',"nombre_pest":''}]};
			//var DatosNuevo='';
		}	
			RecordDef = Ext.data.Record.create([
				{name:'codnivel'},
				{name: 'nivel'},	
				{name: 'nombre_pest'},
				{name: 'numcar'}
			]);
			DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader
			({
				root: 'raiz', 
				id: "id"   
			}
			,
                RecordDef 
			)
			,
				data: DatosNuevo
            });
			grid = new Ext.grid.EditorGridPanel({
			width:780,
			height:400,
			autoScroll:true,
            border:true,
            ds:DataStore,
            cm: new Ext.grid.ColumnModel([
              {header: "Nivel", width:100, sortable: true, dataIndex: 'nivel'},            
			  {header: "Nombre de la pestaña", width:200, sortable: true, dataIndex: 'nombre_pest',editor: new Ext.form.TextField({allowBlank: false})},
			  {header: "Número de caracteres", width:150, sortable: true, dataIndex: 'numcar',editor: new Ext.form.NumberField({allowBlank: false,id:'numcarac',maxValue:25})}
              ]),
			selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                 viewConfig: {
                 	forceFit:true
            },
			autoHeight:true,
			stripeRows: true
                   });
		grid.render('ContenedorGrid');
		Ext.getCmp('numcarac').on('change',function(Obj)
		{
			if(parseInt(Obj.getValue())>25)
			{
				Ext.MessageBox.alert('Mensaje','El numero de caracteres es mayor a 25');
				//Obj.setValue('0');
			}
		})
		
		
	}
	

});	
				
}

Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank2.php';
})



function irLlamarpantestpre()
{
	Auxcantidad = grid3.store.getCount()-1;
	if(Auxcantidad>=0)
	{
	
	Arregs = grid3.store.getRange(0,Auxcantidad);
	var myJSONObject ={
		"oper": 'copiarestpre'
	};	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado,request){ 
		 datos = resultado.responseText;
		 if(datos=='1')
		 {
		 	Ext.MessageBox.alert("Mensaje","La operación se realizó con éxito");
		 }
		 else
		 {
		 	Ext.MessageBox.alert("Mensaje","No se realizó la operación");
		 }
	}
})
}
else
{
	Ext.MessageBox.alert("Mensaje","Debe Definir los niveles de la estructura");
}
}



function getobject2()
{
var myJSONObject ={
		"oper": 'catalogo',  
		"tipo": "PR",
		"codnivel": "", 
		"nivel":"",
		"numcar":"",
		"nombre_pest":""
};	
	
		var datosdefecto = new Ext.Action(
		{
			text: 'Cargar Datos',
			handler: irLlamarpantestpre,
			iconCls: 'bmenuagregar',
	        tooltip: 'Cargar datos'
		});
		
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
			Oper[1]='Actualizando';
		}
		else
		{
			Oper[1]='incluyendo';
			var DatosNuevo={"raiz":[{"codnivel":'',"tipoest":'',"nivel":'',"nombre_pest":''}]};
			//var DatosNuevo='';
		}	
		//alert(DatosNuevo)	
			RecordDef = Ext.data.Record.create([
			{name:'codnivel'},
		   // "mapping" property not needed if it's the same as "name"
			{name: 'nivel'},	// This field will use "occupation" as the mapping.
			{name: 'nombre_pest'},
			{name: 'numcar'}
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
			
				grid2 = new Ext.grid.EditorGridPanel({
				tbar:[datosdefecto],
				width:780,
				autoScroll:true,
                  border:true,
                  ds:DataStore,
                  cm: new Ext.grid.ColumnModel([
               	  {header: "Nivel", width: 100, sortable: true, dataIndex: 'nivel'},      
				  {header: "Nombre de la pestaña", width:200, sortable: true, dataIndex: 'nombre_pest',editor: new Ext.form.TextField({allowBlank: false})},
				  {header: "Número de caracteres", width:150, sortable: true, dataIndex: 'numcar',editor: new Ext.form.NumberField({allowBlank: false,id:'numcarac',maxValue:25})}
               ]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        }
                        ,
			autoHeight:true,
			stripeRows: true
            });
		grid2.render('ContenedorGrid2');		
		Ext.getCmp('numcarac').on('change',function(Obj)
		{
			if(parseInt(Obj.getValue())>25)
			{
				Ext.MessageBox.alert('Mensaje','El numero de caracteres es mayor a 25');
				//Obj.setValue('0');
			}
		})
			
	}
	
});	
				
}

function irLlamarpantea()
{
	

	Auxcantidad = grid3.store.getCount()-1;
	Arregs = grid3.store.getRange(0,Auxcantidad);
	if(Auxcantidad>=0)
	{
	if(Auxcantidad==0)
	{
		var myJSONObject ={
		"oper": 'copiarunej'
		};	
	}	
	else
	{
		if(Auxcantidad>0)
		{
			var myJSONObject ={
			"oper": 'copiaruniadmin'
			};	
		}
	}
		
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado,request){ 
		 datos = resultado.responseText;
		 if(datos=='1')
		 {
		 	Ext.MessageBox.alert("Mensaje","La operación se realizó con éxito");
		 }
		 else
		 {
		 	Ext.MessageBox.alert("Mensaje","No se realizó la operación");
		 }
	}
})
}
else
{
	Ext.MessageBox.alert("Mensaje","Debe Definir los niveles de la estructura");
}
}

function getobject3()
{
	
var myJSONObject ={
		"oper": 'catalogo',  
		"tipo": "EA",
		"codnivel": "", 
		"nivel":"",
		"nombre_pest":""
};	
	
	
	var datosdefecto = new Ext.Action(
	{
		text: 'Cargar Datos',
		handler: irLlamarpantea,
		iconCls: 'bmenuagregar',
        tooltip: 'Cargar datos'
	});
	
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
			Oper[2]='Actualizando';
		}
		else
		{
			Oper[2]='incluyendo';
			var DatosNuevo={"raiz":[{"codnivel":'',"tipoest":'',"nivel":'',"nombre_pest":''}]};
			//var DatosNuevo='';
		}	
		//alert(DatosNuevo)	
			RecordDef = Ext.data.Record.create([
			{name:'codnivel'},
		   // "mapping" property not needed if it's the same as "name"
			{name: 'nivel'},	// This field will use "occupation" as the mapping.
			{name: 'nombre_pest'},
			{name: 'numcar'}
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
			
			grid3 = new Ext.grid.EditorGridPanel({
			tbar:[datosdefecto],
			width:780,
			autoScroll:true,
                        border:true,
                        ds:DataStore,
                        cm: new Ext.grid.ColumnModel([
                           // new Ext.grid.RowNumberer(),
                         {header: "Nivel", width: 100, sortable: true, dataIndex: 'nivel'},
                            
			  {header: "Nombre de la pestaña", width:650,sortable: true, dataIndex: 'nombre_pest',editor: new Ext.form.TextField({allowBlank: false})}
                        ]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig: {
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
		   
		grid3.render('ContenedorGrid3');
		
	}
	
});	
				
}

function crearforma()
{
	var myJSONObject ={
		"oper": 'catalogocombopais'
	};	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) { 
		datos = resultado.responseText;  
		if(datos!='')
		{
			var DatosNuevo = eval('(' + datos + ')');
		}
		RecordDef = Ext.data.Record.create([
			{name:'codpai'},
			{name: 'despai'}
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
            
		    var ComboTipo = new Ext.form.ComboBox({
				  store :DataStore,
				  editable:false,
				  fieldLabel:'País',
				  displayField:'despai',
				  diplayValue:'codpai',
				  hiddenName:'codpai4',
				  hiddenId:'codpai5',
				  name: 'tipo2',
				  id:'tipo2',
				  typeAhead: true,
			      triggerAction:'all'
			    
			})            
            
	        formFormula = new Ext.FormPanel({
	        frame:true,
	        title: 'Carga de Datos de Ubicación Geográfica',
	        bodyStyle:'padding:5px 5px 0',
	        width: 500,
			height:200,
	        defaults: {width: 230},
			items:[
	        	ComboTipo
		   ]
		});		
		
		
		    winForms = new Ext.Window(
            {
  				autoScroll:true,
                width:600,
                height:300,
                modal: true,
                style:'padding-left:70px',
                closable:false,
                plain: false,
                items:[formFormula],
                buttons: 
                	[{
	                text:'Aceptar',  
	                handler: function()
	                {
	                	   copiardatosub();  
	                	   ComboTipo.destroy();
	                	   formFormula.destroy();  
	                	   winForms.destroy();     
	                }
	                }
	                ,
	                {
	                 text: 'Salir',
	                 handler: function()
	                 { 	
		                   ComboTipo.destroy();
	                	   formFormula.destroy();  
	                	   winForms.destroy();           
	                 }
                	}]
            });

            winForms.show();  
			if(Tipoub=='MUNI' || Tipoub=='ESTADO')
			{
				ComboTipo.addListener('select',agregarcoboestado);
			} 
			else
			{
				ComboTipo.addListener('select',function(par,rec)
									{
										codpai = rec.get('codpai');
									
									})   
			}
			}
	})	
}

function copiardatosub()
{

	var myJSONObject ={
		"oper": 'copiardatosub',
		"codpai":codpai,
		"codest":codest,
		"codmun":codmun,
		"desde":nivel1
	};	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) { 
		datos = resultado.responseText;
		if(datos=="1")
		{
			Ext.MessageBox.alert("Mensaje","La operación se realizó con éxito");
		}
		
	}
	})
}


function agregarcoboestado(par,rec)
{
	codpai = rec.get('codpai');
	var myJSONObject ={
		"oper": 'catalogocomboestado',
		"codpai":codpai
	};	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) { 
		datos = resultado.responseText;
		if(datos!='')
		{
			var DatosNuevo = eval('(' + datos + ')');
		}
		RecordDef = Ext.data.Record.create([
			{name: 'codpai'},
			{name: 'codest'},
			{name: 'desest'}
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
            
		    var Comboest = new Ext.form.ComboBox({
				  store :DataStore,
				  editable:false,
				  fieldLabel:'Estado',
				  displayField:'desest',
				  diplayValue:'codest',
				  hiddenName:'codpai4',
				  hiddenId:'codpai5',
				  name: 'tipo3',
				  id:'tipo3',
				  typeAhead: true,
			      triggerAction:'all'
			})            
            //alert(formFormula);
	        formFormula.add(Comboest);
	        winForms.doLayout();
	        if(Tipoub=='MUNI')
			{
	        	Comboest.addListener('select',agregarcobomuni);
	        }
	        else
			{
				Comboest.addListener('select',function(par,rec)
									{
										codpai = rec.get('codpai');
										codest = rec.get('codest');
									})   
			}
	        
	}
	})	
}

function agregarcobomuni(par,rec)
{
	codpai = rec.get('codpai');
	codest = rec.get('codest');
	var myJSONObject ={
		"oper": 'catalogocombomuni',
		"codpai":codpai,
		"codest":codest
	};	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) { 
		datos = resultado.responseText;
		if(datos!='')
		{
			var DatosNuevo = eval('(' + datos + ')');
		}
		RecordDef = Ext.data.Record.create([
			{name: 'codpai'},
			{name: 'codest'},
			{name: 'codmun'},
			{name: 'denmun'}
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
            
		    var Combomun = new Ext.form.ComboBox({
				  store :DataStore,
				  editable:false,
				  fieldLabel:'Municipio',
				  displayField:'denmun',
				  diplayValue:'codmun',
				  hiddenName:'codpai7',
				  hiddenId:'codpai7',
				  name: 'tipo4',
				  id:'tipo5',
				  typeAhead: true,
			      triggerAction:'all'
			})            
            //alert(formFormula);
	        formFormula.add(Combomun);
	        winForms.doLayout();
	        Combomun.addListener('select',function(par,rec){
	        	codpai = rec.get('codpai');
				codest = rec.get('codest');
				codmun = rec.get('codmun');
	        
	        });
	}
	})	
}


function irLlamarpant()
{
	Auxcantidad = grid4.store.getCount()-1;
	Arregs = grid4.store.getRange(0,Auxcantidad);
	nivel1 = Arregs[0].get('nombre_pest');
	switch (nivel1)
	{
		case 'ESTADO':
			Tipoub='PAIS';
			crearforma();
		break;
		case 'MUNICIPIO':
			Tipoub='ESTADO';
			crearforma();
		break;
		case 'PARROQUIA':
			Tipoub='MUNI';
			crearforma();								
		break;
		case 'PAIS':
			copiardatosub();								
		break;
	}
}


function getobject4()
{
var myJSONObject ={
		"oper": 'catalogo',  
		"tipo": "UG",
		"codnivel": "", 
		"nivel":"",
		"nombre_pest":""
};	
	
	
	Tipo=
	[
		['1','PAIS'],
		['2','ESTADO'],
		['3','MUNICIPIO'],
		['4','PARROQUIA']
	]	
	var storeTipo = new Ext.data.SimpleStore
	(
		{
	        fields: ['cod','nivel'],
	        data : Tipo // from states.js
	    }
	);
	
	var ComboTipo = new Ext.form.ComboBox({
		  store :storeTipo,
		  editable:true,
		  displayField:'nivel',
		  diplayValue:'cod',
		  //hiddenName
		  name: 'tipo',
		  id:'tipo',
		  typeAhead: true,
	      triggerAction:'all',
	      mode: 'local'
	})
	
	
	var datosdefecto = new Ext.Action(
	{
		text: 'Cargar Datos',
		handler: irLlamarpant,
		iconCls: 'bmenuagregar',
        tooltip: 'Cargar datos'
	});
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) { 
		  datos = resultado.responseText;
		 // alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			Oper[3]='Actualizando';
		}
		else
		{
			Oper[3]='incluyendo';
			var DatosNuevo={"raiz":[{"codnivel":'',"tipoest":'',"nivel":'',"nombre_pest":''}]};
			//var DatosNuevo='';
		}	
		//alert(DatosNuevo)	
			RecordDef = Ext.data.Record.create([
			{name:'codnivel'},
		   // "mapping" property not needed if it's the same as "name"
			{name: 'nivel'},	// This field will use "occupation" as the mapping.
			{name: 'nombre_pest'},
			{name: 'numcar'}
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
			
			grid4 = new Ext.grid.EditorGridPanel({
			width:780,
			autoScroll:true,
			tbar:[datosdefecto],
            border:true,
            ds:DataStore,
            cm: new Ext.grid.ColumnModel([
            // new Ext.grid.RowNumberer(),
            {header: "Nivel", width: 100, sortable: true, dataIndex: 'nivel'},
                            
			  {header: "Nombre de la pestaña", width:650, sortable: true, dataIndex: 'nombre_pest',editor:ComboTipo}
                        ]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig: {
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
		   
		grid4.render('ContenedorGrid4');
		
	}
	
});	
				
}


Ext.get('BtnGrabar').on('click', function()
{
	tabActual = parseInt(tabs.getActiveTab().id);
	if(Oper[tabActual]=="incluyendo2")
	{
		eve = 'incluirvarios';
		Mens = 'Incluido';
	}
	else
	{
		eve = 'actualizarvarios';
		Mens = 'Modificado';
	}
	switch(tabActual)
	{
		case 0:
			numDatos = grid.store.getModifiedRecords();
			GridActual=grid;
			Tipo='PL';
			break;
		case 1:
			numDatos = grid2.store.getModifiedRecords();
			GridActual=grid2;
			Tipo='PR';
			break;
		case 2:
			numDatos = grid3.store.getModifiedRecords();
			GridActual=grid3;
			Tipo='EA';
			break;
		case 3:
			numDatos = grid4.store.getModifiedRecords();
			GridActual=grid4;
			Tipo='UG';
			break;
	}         
	
	var reg = "{'oper':'"+ eve + "','datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
		if(i==0)
		{
			reg = reg + "{'codnivel':'" + numDatos[i].get('codnivel') +"','nivel':'" + numDatos[i].get('nivel') +"','nombre_pest':'" + numDatos[i].get('nombre_pest') +"','tipo':'" + Tipo +"','numcar':'" + numDatos[i].get('numcar')+"'}";
		}	
		else
		{
			reg = reg + ",{'codnivel':'" + numDatos[i].get('codnivel') +"','nivel':'" + numDatos[i].get('nivel') +"','nombre_pest':'" + numDatos[i].get('nombre_pest') +"','tipo':'" + Tipo +"','numcar':'" + numDatos[i].get('numcar')+"'}";
		}		
	}
	reg = reg + "]}";
	//alert(reg);
	Obj= eval('(' + reg + ')');
	ObjSon=JSON.stringify(Obj);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultad, request ){ 
        datos = resultad.responseText;
       // alert(datos);
		var Registros = datos.split("|");
		Cod = Registros[1];
		if(Cod!='')
		{
			Ext.MessageBox.alert('Mensaje', 'Registro '+ Mens +' con exito ');
			GridActual.store.commitChanges();
			ActualizarData(Tipo);
			Oper[tabActual]="incluyendo";		
		}
		else
		{
			Ext.MessageBox.alert('Mensaje');				
		}
      },
	failure: function ( result, request)
	 { 
		Ext.MessageBox.alert('Error', result.responseText); 
	} 
      });
 	
//	Oper='';
});




function ActualizarData(Tipo)
{
	tabActual = parseInt(tabs.getActiveTab().id);
	var myJSONObject ={
			"oper": 'catalogo',  
			"tipo": Tipo,
			"codnivel": "", 
			"nivel":"",
			"nombre_pest":"",
			"numcar":""
	};	

	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
		  //alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			switch(tabActual)
			{
				case 0:
					grid.store.loadData(DatosNuevo);
					break;
				case 1:
					grid2.store.loadData(DatosNuevo);
					break;
				case 2:
					grid3.store.loadData(DatosNuevo);
					break;
				case 3:
					grid4.store.loadData(DatosNuevo);
					break;
			}         

		}
	
}
});
	
}


function ObtenerGrid(tab)
{
	switch(tab)
	{
		case 0:
			return grid;
			break;
		case 1:
			return grid2;
			break;
		case 2:
			return grid3;
			break;
		case 3:
			return grid4;
			break;
	}    
	
}


Ext.get('BtnNuevo').on('click', function()
{				
	tabActual = parseInt(tabs.getActiveTab().id);
	gridActual = ObtenerGrid(tabActual);
	Cantidad = gridActual.store.getCount();
	if(Cantidad==1)
	{
			codigo1 = gridActual.store.getRange(0,1);
			codigo2 = codigo1[0].get('nivel');
			if(codigo2=='')
			{	
				for(i=5;i>=1;i--)
				{
				//	alert('dd');
					 var p = new RecordDef
					(
				        {
							codnivel:'',
					        nivel: i,
					        nombre_pest: ''
						}
				                   
				    );
				   
							
					gridActual.store.insert(0, p);
						
				    
				}	
			}
			else
			{
				 var p = new RecordDef
					(
				        {
							codnivel:'',
					        nivel: 2,
					        nombre_pest: ''
						}
				                   
				    );
				   
							
					gridActual.store.insert(1, p);
					gridActual.startEditing(1,1);
			}
	}
	else
	{
						Actual =  gridActual.store.getCount();	
						Proximo = Actual +1;	
						if(Actual<5)
						{				
							var p = new RecordDef
							(
								{
									codnivel:'',
									nivel: Proximo,
									nombre_pest: ''
								}
								                   
							);
							gridActual.store.insert(Actual, p);
							gridActual.startEditing(Actual,1);
						}
						else
						{
							Ext.MessageBox.alert('Mensaje', 'solo puede definirse hasta 5 niveles de Estructuras');
							
						}
						

	}	
	Oper[tabActual]="incluyendo2";
});


Ext.get('BtnElim').on('click',function()
{
	if(grid.getSelectionModel().getSelected() || grid2.getSelectionModel().getSelected() || grid3.getSelectionModel().getSelected() || grid4.getSelectionModel().getSelected())
	{
	
	tabActual = parseInt(tabs.getActiveTab().id);
	//	alert('por aqui, por aqui')	
	var Resul;
	Ext.MessageBox.confirm('Confirmar', '¿Desea eliminar este registro?', Resul);
	
 }
 else
 {
	Ext.MessageBox.alert('Mensaje','Debe seleccionar un registro');	
 }

	function Resul(btn)
	{		
		if(btn=='yes')
		{
			
			switch(tabActual)
			{
					case 0:			
						codigoNivel = grid.getSelectionModel().getSelected().get('codnivel');
						Nivel = grid.getSelectionModel().getSelected().get('nivel');
						lagrid= grid;
						Reg=grid.getSelectionModel().getSelected();
						Tipo='PL';
						break;
					case 1:
						codigoNivel = grid2.getSelectionModel().getSelected().get('codnivel');
						Nivel = grid2.getSelectionModel().getSelected().get('nivel');
						lagrid= grid2;
						Reg=grid2.getSelectionModel().getSelected();
						Tipo='PR';
						break;
					case 2:
						codigoNivel = grid3.getSelectionModel().getSelected().get('codnivel');
						Nivel = grid3.getSelectionModel().getSelected().get('nivel');
						lagrid= grid3;
						Reg=grid3.getSelectionModel().getSelected();
						Tipo='EA';
						break;
					case 3:
						codigoNivel = grid4.getSelectionModel().getSelected().get('codnivel');	
						Nivel = grid4.getSelectionModel().getSelected().get('nivel');
						lagrid= grid4;
						Reg=grid4.getSelectionModel().getSelected();
						Tipo='UG';
						break;	
			
			}         
			var myJSONObject ={
				"oper": 'eliminar', 
				"codnivel": codigoNivel,
				"nivel" : Nivel,
				"tipo" : Tipo,
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
					
				 var Registros = datos.split("|");
				 if (Registros[1] == '1')
				 {
					Ext.MessageBox.alert('Mensaje','Registro '+Mensa + ' con éxito');
					//ActualizarData(Tipo);
					Oper[tabActual]="incluyendo";
					lagrid.store.remove(Reg);
					
				 }
				 else
				 {
				  Ext.MessageBox.alert('Error', 'No se puede eliminar este nivel debido a que tiene  datos asociados');
				 }
			},
			failure: function ( result, request) { 
				Ext.MessageBox.alert('Error', result.responseText); 
			} 
		    });

		}
	
	};




	
})



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


getobject()  
getobject2()
getobject3()
getobject4()

			var tabs = 	new Ext.TabPanel({
                            border:false,
                            activeTab:0,
                            width:780,
                        	style:'position:absolute;left:120px;top:50px',
                            renderTo:'tabPrin',
                            items:[{
	                            contentEl:'ContenedorGrid',
								title: 'Plan',
								id:'0',
								autoScroll:true
                            },
							{
                                contentEl:'ContenedorGrid2',
                                title: 'Estructura Presupuestaria',
                                autoScroll:true,
                                id:'1'
                            },
                            {
                                contentEl:'ContenedorGrid3',
                                title: 'Estructura Administrativa',
                                autoScroll:true,
                                id:'2'

                            },
                            {
                                contentEl:'ContenedorGrid4',
                                title: 'Ubicacion Geográfica',
                                autoScroll:true,
                                id:'3'
                            }
							] 
                })



       var viewport = new Ext.Viewport({
            layout:'border',
            items:[
                new Ext.BoxComponent({ // raw
                    region:'north',
                    el: 'norte',
                    height:100
                }),
                new Ext.BoxComponent({
                region:'center',
                width: 210,
                height:200,
                el:'centro'    
            })
            ]
          })		
});
