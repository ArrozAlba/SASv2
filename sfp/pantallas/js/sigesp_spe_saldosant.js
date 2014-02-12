/*
 * Ext JS Library 2.0.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * http://extjs.com/license
 */
 
ruta ='../../procesos/sigesp_spe_saldosantpr.php';
pantalla ='sigesp_spe_saldosant.php';
var gridCarga ='';
Ext.onReady(function(){
ObtenerSesion(ruta,pantalla)
function getgrid()
{
	var myJSONObject ={
		"oper": 'catalogo', 
};	   
 
 	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado, request ) { 
	datos = resultado.responseText;
	//alert(datos);
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
			//alert(DatosNuevo)	
		
		 }
		else
		{
			var DatosNuevo={"raiz":[{"sc_cuenta":'',"denominacion":'',"monto_anreal":'',"monto_anest":''}]};
		}
		//var DatosNuevo={"raiz":[{"sc_cuenta":'',"denominacion":'',"monto_anreal":'',"monto_anest":''}]};
		RecordDef = Ext.data.Record.create
		([
			{name: 'codcuenta'},// "mapping" property not needed if it's the same 
			{name: 'dencuenta'},
			{name: 'monto_anreal'},
			{name: 'estatus'},
			{name: 'monto_anest'},
				
		]);
			
			DataStore =  new Ext.data.Store
			({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			root: 'raiz',                
			id: "id"   
			},
                    RecordDef
			      ),
					data: DatosNuevo
            });
			gridCarga = new Ext.grid.EditorGridPanel({
			width:1000,
			height:450,
			autoScroll:true,
			clicksToEdit:1, 
            border:true,
            ds:DataStore,
           // tbar: [agregar,quitar],
            cm: new Ext.grid.ColumnModel([
            // new Ext.grid.RowNumberer(),

           // new Ext.grid.CheckboxSelectionModel(),

              {header: "Cuenta", width: 200, sortable: true, dataIndex: 'codcuenta'},                            
			  {header: "Denominación", width:600, sortable: true, dataIndex: 'dencuenta'},
			  {header: "Año Real", width: 100, sortable: true, dataIndex: 'monto_anreal',editor: new Ext.form.TextField({allowBlank: false,allowDecimals:false})},              
			  {header: "Último Año Estimado", width: 100, sortable: true, dataIndex: 'monto_anest',editor: new Ext.form.TextField({allowBlank: false,allowDecimals:false})}
			])
,

	//sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),

                        viewConfig: {
                        forceFit:true
                        },
			
			stripeRows: true
     });   
		gridCarga.render('ContenedorGridCarga');
}				
})
 
}

function ActualizarGrid()
{
	var myJSONObject ={
		"oper": 'catalogo', 
};	   
 
 	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado, request ) { 
	datos = resultado.responseText;
	//alert(datos);
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
			//alert(DatosNuevo)	
		
		 }
		else
		{
			var DatosNuevo={"raiz":[{"sc_cuenta":'',"denominacion":'',"monto_anreal":'',"monto_anest":''}]};
		}
		gridCarga.store.loadData(DatosNuevo);
}	
})
}

Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank2.php';
})

Ext.get('BtnGrabar').on('click', function()
{
  	var numDatos = gridCarga.store.getModifiedRecords();;
	//alert(numDatos.length);
	var reg = "{'oper':'incluirMontos','datos':["; 
	for(var i=0;i<=numDatos.length-1;i++)
	{
		if(i==0)
		{
			reg = reg + "{'estatus':'"+numDatos[i].get('estatus')+"','sig_cuenta':'" + numDatos[i].get('codcuenta') +"','monto_anreal':'" + numDatos[i].get('monto_anreal') +"','monto_anest':'" + numDatos[i].get('monto_anest') +"'}";
		}	
		else
		{
			reg = reg + ",{'estatus':'"+numDatos[i].get('estatus')+"','sig_cuenta':'" + numDatos[i].get('codcuenta')+"','monto_anreal':'" + numDatos[i].get('monto_anreal') +"','monto_anest':'" + numDatos[i].get('monto_anest') +"'}";
		}		
	}
	reg = reg + "]}";
	//alert(reg);
	//return false;
	Obj= eval('(' + reg + ')');
	ObjSon=JSON.stringify(Obj);
	//alert(ObjSon);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultad, request ){ 
        datos = resultad.responseText;
     //   alert(datos);
		var Registros = datos.split("|");
		Cod = Registros[1];
		if(Cod=='1')
		{
			Ext.MessageBox.alert('Mensaje', 'Registro guardado con exito ');
			gridCarga.store.commitChanges();
			ActualizarGrid();		
		}
		else
		{
			Ext.MessageBox.alert('Mensaje',"No se pudo guardar los registros");				
		}
      },
	failure: function ( result, request)
	 { 
		Ext.MessageBox.alert('Error', result.responseText); 
	} 
 	});
	
}); 
 
getgrid(); 
Ext.state.Manager.setProvider(new Ext.state.CookieProvider()); 
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
                title:'Plan general de cuentas integrado del organismo',
                width: 600,
                autoScroll:true,
               // bodyStyle:'background-color:#DFE8F6',
                height:50,
                //style:'padding-top:30px;padding-left:50px',
                contentEl:'centro'    
            })
          
            ]
          })
          
           var agregar = new Ext.Action(
			{
				text: 'Agregar',
				//handler: irAgregar,
				iconCls: 'bmenuagregar',
	        	tooltip: 'Agregar cuenta'
			});
		
		var quitar = new Ext.Action(
		{
			text: 'Quitar',
			//handler: irQuitar,
			iconCls: 'bmenuquitar',
        	tooltip: 'Eliminar registro'
		});
});          		