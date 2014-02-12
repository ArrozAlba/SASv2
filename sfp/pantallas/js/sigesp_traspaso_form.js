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
var gridEmpresas ="";
var RecordDefConv="";
var IndiceActual="";
var RegistroActual="";
var Oper = "";
var Actualizar='';
var gridEmpresas="";
var FormularioBus="";
var gridPlanCuentas="";
var gridReportes=""; 
var combo1="";
ruta ='../../procesos/sigesp_traspaso_formpr.php';
pantalla ='sigesp_traspaso_form.php';

Ext.onReady(function()
{
ObtenerSesion(ruta,pantalla)
function getGridReportes()
{ 
	var myJSONObject ={
		"oper": 'leerempresas'
	};	
	
		ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		Ext.Ajax.request({
		url : ruta,
		params : parametros,
		method: 'POST',
		success: function (resultado, request) { 
		  datos = resultado.responseText;
		 // alert(datos);
		  auxArr = datos.split("|");
		
		  var DatosNuevo = eval('(' + auxArr[0] + ')');
		 if(datos=='' && datos.raiz==null)
		 {
			var DatosNuevo={"raiz":[{"codemp":'',"nombre":''}]};
		 }
		var DatosNuevo2={"raiz":[{"codemp":'',"nombre":''}]};
		RecordDefPlanCuentas = Ext.data.Record.create
		([
			{name:'port'},
			{name:'database'},
			{name:'hostname'},
			{name:'login'},
			{name:'password'},
			{name:'gestor'}
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
				data: DatosNuevo2
            });
            
	  for(i=1;i<=parseInt(auxArr[1]);i++)
	  {
	  	 p=new RecordDefPlanCuentas
         (
	         {
		         port:DatosNuevo[i].port,
		         database:DatosNuevo[i].database,
		         hostname:DatosNuevo[i].hostname,
		         login:DatosNuevo[i].login,
		         password:DatosNuevo[i].password,
		         gestor:DatosNuevo[i].gestor
		     }
	     )
	     DataStorePlanCuentas.insert(0,p);
	  }
			gridEmpresas = new Ext.grid.EditorGridPanel({
  			width:750,
  			height:130,
  			renderTo:'ContenedorGridCoversion',
			autoScroll:true,
            border:true,
            style:'margin-left:130px;margin-top:30px',
            ds:DataStorePlanCuentas,
            cm: new Ext.grid.ColumnModel([
            {header: "Base de Datos", width: 100, sortable: true,  dataIndex: 'database'}
            ]),
			selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
             viewConfig:{
             forceFit:true	
             },
			stripeRows: true,
			 buttons :[
			 	{
	                 text: 'Transferir Datos',
	                 handler: function()
	                {
						irTransferencia();
	                }
                 }
			 ]
            });
}				
})
}


function irTransferencia()
{
if(gridEmpresas.getSelectionModel().getSelected())
{

	Ext.MessageBox.show({
           msg: 'Por Favor Espere',
           title: 'Transfiriendo Datos',
           progressText:'Transfiriendo Datos',
           width:300,
           wait:true,
           waitConfig:{interval:100},
           animEl: 'mb7'
    });

	var myJSONObject ={
		"oper": 'transferir',
		"database": gridEmpresas.getSelectionModel().getSelected().get('database'),
		"hostname": gridEmpresas.getSelectionModel().getSelected().get('hostname'),
		"login": gridEmpresas.getSelectionModel().getSelected().get('login'),
		"password": gridEmpresas.getSelectionModel().getSelected().get('password'),
		"gestor": gridEmpresas.getSelectionModel().getSelected().get('gestor')
	};	
		ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		Ext.Ajax.request({
		url : ruta,
		params : parametros,
		method: 'POST',
		success: function (resultado, request){ 
		datos = resultado.responseText;
		  Ext.MessageBox.alert('Mensaje','La transferencia se realizó con éxito');
		  //Ext.MessageBox.hide();
	    }
	    })
 }
 else
 {
 	 Ext.MessageBox.alert('Mensaje','Debe seleccionar una base de datos');
 }
}
Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank2.php';
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
