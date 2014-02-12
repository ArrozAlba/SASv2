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
var tabs = null;
var unavez = false;
var parametros='';
var ruta = '';
var RecordDef;
var RecordDefGI='';
var grid2='';
var DataStore='';
var DatosNuevo ="";
var RecordDefConv="";
var IndiceActual="";
var RegistroActual="";
var Oper = "";
var Actualizar='';
var FormularioBus="";
var gridPlanCuentas="";
var gridReportes=""; 
var combo1="";
ruta ='../../procesos/sigesp_formulacionpr.php';
pantalla ='sigesp_formulacion.php';
Ext.onReady(function()
{
ObtenerSesion(ruta,pantalla)
function getGridReportes()
{ 
	var myJSONObject ={
		"oper": 'leerempresa'
	};	
	
		ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		Ext.Ajax.request({
		url : ruta,
		params : parametros,
		method: 'POST',
		success: function (resultado, request){ 
		  datos = resultado.responseText;
		  var DatosNuevo = eval('(' + datos + ')');
		 if(datos=='' && datos.raiz==null)
		 {
			var DatosNuevo={"raiz":[{"codemp":'',"nombre":''}]};

		 }
		RecordDefPlanCuentas = Ext.data.Record.create
		([
			{name:'codemp'},
			{name:'nombre'}
		]);
			
			DataStorePlanCuentas =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			root: 'raiz',                // The property which contains an Array of row objects
			id: "id"   
			}
			,
               RecordDefPlanCuentas
			),
				data: DatosNuevo
            });
	/*		
			gridReportes = new Ext.grid.EditorGridPanel({
			width:800,
			autoScroll:true,
            border:true,
            ds:DataStorePlanCuentas,
            cm: new Ext.grid.ColumnModel([
            // new Ext.grid.RowNumberer(),
           // new Ext.grid.CheckboxSelectionModel(),
            {header: "Nombre del Formato", width: 150, sortable: true, dataIndex: 'nombre'}
])
,
sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
                        viewConfig: {
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });   
                   
               */    
    var fecha=new Date();
	Year1 = fecha.getFullYear();
	Year2=Year1+1;    
	Year3=Year1+2;
	Year4=Year1+3; 
	Year5=Year1+4;           
    Years=
	[
		[Year1],
		[Year2],
		[Year3],
		[Year4],
		[Year5]
	]
   var storeYear = new Ext.data.SimpleStore({
       fields: ['year'],
       data : Years // from states.js
    });               
    combo1 = new Ext.form.ComboBox({
	    store: DataStorePlanCuentas,
	    fieldLabel:'Empresa',
	    displayField:'nombre',
	    id:'cmbempresa',
	    name:'Empresa',
	    valueField:'codemp',
	    typeAhead: true,
	    mode: 'local',
	    triggerAction: 'all',
	    width :400,
	    listWidth:400,
	    emptyText:'Seleccione',
	    selectOnFocus:true,
	    editable:false
    })
     
      ForMontos = new Ext.FormPanel({
	  labelWidth:140, // label settings here cascade unless overridden,
	  labelAlign:'right',
	  width:820,
	  renderTo:'ContenedorGridCoversion',
	  bodyStyle:'padding-top:40px;padding-left:0px;margin-left:259px;margin-top:20px',
	  height:200,  
	  items:[
			combo1	
		,
		{
		  xtype:'combo',
		  editable:true, 
		  store : storeYear,
		  editable:false,
		  displayField:'year',
		  valueField:'year',
		  emptyText:'Seleccione',
		  fieldLabel: 'Año del Presupuesto',
		  name: 'Empresa',
		  id:'anpresupuesto',
		  typeAhead: true,
		  triggerAction: 'all',
	      mode:'local'
	    }
	    ,
	    {
	   	  xtype:'button',
		  handler:irEntrarFormulacion,
		  text:'Entrar',
		  style:'position:absolute;left:220px;top:130px'
	    }
     ]
     })
}				
})
}





function irEntrarFormulacion()
{
if(validarObjetos('cmbempresa','novacio|0')!='0' && validarObjetos('anpresupuesto','novacio|0')!='0')
{
	var myJSONObject ={
		"oper": 'grabarsesion',
		"empresa": Ext.getCmp('cmbempresa').getValue(),
		"ano_presupuesto": Ext.getCmp('anpresupuesto').getValue()
	};	
	
		ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		Ext.Ajax.request({
		url : ruta,
		params : parametros,
		method: 'POST',
		success: function (resultado, request){ 
		  datos = resultado.responseText;
		  if(datos=="1")
		  {
		  	location.href="sigesp_windowblank2.php";
		  }
		  
	    }
	    })
 }
}
Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank.php';
})

getGridReportes();

       var viewport = new Ext.Viewport({
            layout:'border',
            items:[
            	new Ext.BoxComponent({ // raw
                    region:'north',
                    el: 'norte',
                    height:100
                })
                ,
              new Ext.Panel
              ({
	                region:'center',
	                layout:'table',
	                title:'Formulación',
	                width: 710,
	                autoScroll:true,
	                bodyStyle:'background-color:#DFE8F6',
	                height:600,
	                contentEl:'centro'    
            	})
            
            ]
          })		
});
