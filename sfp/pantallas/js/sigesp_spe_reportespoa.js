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
var gridPlan="";
ruta ='../../procesos/sigesp_spe_reportespoapr.php';
pantalla ='sigesp_spe_reportespoa.php';
Ext.onReady(function()
{

ObtenerSesion(ruta,pantalla);
function getGridReportes()
{ 
	var myJSONObject ={
			"oper": 'catalogoreporte'
	};	
	
		ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		var DatosNuevo = eval('(' + datos + ')');
		
			var DatosNuevo={"raiz":[
			{"codigo":'0001',"nombre":'Consolidado por Partidas de Egresos',"explicacion":''},
			{"codigo":'0002',"nombre":'Distribucion Fisica y Financiera por Plan',"explicacion":''},
			]};

				RecordDefPlanCuentas = Ext.data.Record.create
		([
			{name:'codigo'},
			{name:'nombre'},
			{name:'explicacion'},
			{name:'estructura'},	
			{name:'fecha'},
			{name:'frecuencia'},
			{name:'codemp'},
			{name:'cuenta'}
		]);
			
			DataStorePlanCuentas =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                        RecordDefPlanCuentas
			      ),
				data: DatosNuevo
            });
                   
    combo1 = new Ext.form.ComboBox({
    store: DataStorePlanCuentas,
    displayField:'nombre',
    valueField:'codigo',
    typeAhead: true,
    mode: 'local',
    triggerAction: 'all',
    width :700,
    listWidth:700,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    editable:true,
    renderTo:'ContenedorGridCoversion'
    //,
    //editable:false
    
});
 		 //gridReportes.render('ContenedorGridCoversion');
		//gridConversion.addListener('celldblclick',Actualizar);
}


function getGridMetasIntegradas()
{
	
	var myJSONObject ={
			"oper": 'leerplanes'
		};	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function(resultado, request) { 
	datos = resultado.responseText;
	
	DatosNuevo = eval('(' + datos + ')');
		if(DatosNuevo.raiz==null)
		{
			var DatosNuevo={"raiz":[{"spi_cuenta":'',"denominacion":'',"montoGlobal":'',"NuevoRegistro":''}]};
		}
		
		gridPlan.store.loadData(DatosNuevo);
	}
	
});	
	
	
		RecordDef = Ext.data.Record.create
		([
			{name: 'codest1'},// "mapping" property not needed if it's the same 
			{name: 'codest2'},
			{name: 'codest3'},
			{name: 'codest4'},
			{name: 'codest5'},
			{name: 'codigo'},
			{name: 'denominacion'}
		]);
			
			DataStore =  new Ext.data.Store
			({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			root: 'raiz',                
			id: "id"   
			},
                    RecordDef
			      )
            });
			gridPlan = new Ext.grid.EditorGridPanel({
  			width:750,
  			height:130,
			autoScroll:true,
            border:true,
            ds:DataStore,
            cm: new Ext.grid.ColumnModel([
            {header: "Codigo", width: 100, sortable: true,  dataIndex: 'codigo'},
            {header: "Denominacion", width: 350, sortable: true, dataIndex: 'denominacion'}			
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig: {
                        forceFit:true	
                        },
			stripeRows: true
            });		  
				
			
}
getGridMetasIntegradas();


function getCriterioBusqueda()
{ 
	Frequencia=
	[
		["Mensual"],
		["Bimensual"],
		["Trimestral"],
		["Semestral"]
	]
	
   var storeFr = new Ext.data.SimpleStore({
       fields: ['Fre'],
       data :Frequencia // from states.js
    });               
	          
	Niveles=
	[
		[1],
		[2],
		[3],
		[4],
		[5],
		[6],
		[7]
	]
   
   var storeNivel = new Ext.data.SimpleStore({
       fields: ['nivel'],
       data : Niveles // from states.js
    });               
    
    comboFre = new Ext.form.ComboBox({
	    store: storeFr,
	    fieldLabel:'Distribucion',
	    displayField:'Fre',
	    id:'frecuenci',
	    name:'frecuencia',
	    valueField:'Fre',
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
	  width:800,
	  border:true,
	  renderTo:'ContenedorCriterios',
	  bodyStyle:'padding-top:40px;padding-left:0px;margin-left:150px;margin-top:20px',
	  height:250,  
	  items:[
			comboFre	
		,
		
				gridPlan
		
		
	    ,
	    {
	   	  xtype:'button',
		  handler:irMostrarReporte,
		  text:'Mostrar Reporte',
		  style:'position:absolute;left:250px;top:220px'
	    }
     ]
     })
}


Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank2.php';
})


function irMostrarReporte()
{
	if(combo1.getValue()=='')
	{
		Ext.MessageBox.alert('Mensaje','Debe seleccionar un reporte en la opción formatos disponibles');
		return false;
	}	
		Ext.MessageBox.show({
          msg: 'Por Favor Espere',
          title: 'Procesando Datos',
          // progressText: 'Cargando Datos',
          width:300,
          wait:true,
          waitConfig:{interval:500}
          // animEl: 'mb7'
        });
	
	
	codReporteActual= combo1.getValue();
	frecuencia =  Ext.getCmp('frecuenci').getValue();
	
	if(codReporteActual)
	{
		
		if(codReporteActual=='0001')
		{
			var myJSONObject ={
					"oper": 'MostrarReporte',
					"codreporte":codReporteActual,
					"frecuencia":frecuencia
				};	

		}
		else if(codReporteActual=='0002')
		{
			Rec = gridPlan.getSelectionModel().getSelected();
			if(Rec)
			{
				
				var myJSONObject ={
						"oper": 'MostrarReporte',
						"codreporte":codReporteActual,
						"codest1":Rec.get('codest1'),
						"codest2":Rec.get('codest2'),
						"codest3":Rec.get('codest3'),
						"codest4":Rec.get('codest4'),
						"codest5":Rec.get('codest5'),
						"codigoplan":Rec.get('codigo'),
						"denplan":Rec.get('denominacion'),
						"frecuencia":frecuencia
					};
			}
		}
			
			ObjSon=JSON.stringify(myJSONObject);
			//alert(ObjSon);
			parametros = 'ObjSon='+ObjSon; 
			Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function (resultado, request)
			{ 
				datos = resultado.responseText;
			//	alert(datos);
			//	Ext.get('norte').dom.innerHTML=datos;
				if(datos!='')
				{
					//return false;
					Abrir_ventana(datos);
					Ext.MessageBox.hide();
				}		
			}
		})
	}

}

 

function ValidarRegistroGrid(RegistroActual)
{
	if(RegistroActual.get('codgi')=='')
	{
		Ext.Msg.alert('Mensaje','Debe incluir una cuenta de gastos o ingresos');
		return false;
	}
	else if(RegistroActual.get('codco1')=='')
	{
		Ext.Msg.alert('Mensaje','Debe incluir una cuenta de contable');
		return false;
		
	}
	else if(RegistroActual.get('codco2')=='')
	{
		Ext.Msg.alert('Mensaje','Debe incluir una cuenta de contable');
		return false;
	}
	else
	{
		return true;
	}
}

function ActualizarDataConversion()
{

	var myJSONObject ={
				"oper": 'catalogo'
	};	
		
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado, request)
	 { 
		  datos = resultado.responseText;
		//  alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
		 }
		else
		{
			var DatosNuevo={"raiz":[{"codgi":'',"codco1":'',"denco1":'',"codco2":'',"denco2":'',"codvp":'',"denvp":'',"colvp":'',"codcai":'',"dencai":''}]};
			
		}
		gridConversion.store.loadData(DatosNuevo);	
	}
});
	
}

function ActualizarDataPlanCuentas()
{
	var myJSONObject =
	{
			"oper": 'catalogoplacuentas'
	};	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado, request)
	 { 
		  datos = resultado.responseText;
		//  alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
		 }
		else
		{
			var DatosNuevo={"raiz":[{"codgi":'',"codco1":'',"denco1":'',"codco2":'',"denco2":'',"codvp":'',"denvp":'',"colvp":'',"codcai":'',"dencai":''}]};
			
		}	
		
		gridPlanCuentas.store.loadData(DatosNuevo);
			
	}
});
	
}



function ObtenerGrid(tab)
{
	switch(tab)
	{
		case "0":
			return grid;
			break;
		case "1":
			return grid2;
			break;
		case "2":
			return grid3;
			break;
		case "3":
			return grid4;
			break;
	}    
	
}


function ObtenerCodigo(tab)
{
	switch(tab)
	{
		case "0":
			return 'spi_cuenta';
			break;
		case "1":
			return 'sc_cuenta';
			break;
		case "2":
			return 'spi_cuenta';
			break;
		case "3":
			return 'spg_cuenta';
			break;
	}    
	
}



function LimpiarCampos()
{
	HabilitarObjetos(false);
	Actualizar='';
}


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



function GetForm()
{
		FormularioBus = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
        url:'save-form.php',
        frame:true,
        applyTo:'form',
        style:'position:absolute;left:120px;top:35px',
        width: 310,
		height:50,
        defaults: {width: 230},
        defaultType: 'textfield',
		items: [{
                fieldLabel: 'Código',
                name: 'cod',
				id:'cod',
				changeCheck: function()
				{
					var v = this.getValue();
					auxCodigo = ObtenerCodigo(tabs.getActiveTab().id)
					ActualizarDataCat(auxCodigo,'spg_cuenta',v);
					if(String(v) !== String(this.startValue))
					{
						this.fireEvent('change', this, v, this.startValue);
					} 
				}
							 , 
				initEvents : function()
				{
					AgregarKeyPress(this);
				}
               
            }
			,
			{
                fieldLabel: 'Denominacion',
                name: 'den',
                id:'den',
				changeCheck: function(){
							  var v = this.getValue();
							 ActualizarDataCat('denominacion','denominacion',v);
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
}


function PasarDatos(Registro)
{	
	if(Actualizar==true)
	{
		RegistroActual = gridConversion.getSelectionModel().getSelected();
	}	
	TabActivo=tabs.getActiveTab().id;
	GridActual = ObtenerGrid(TabActivo);
	Cod = GridActual.getSelectionModel().getSelected().get('codigo');
	Den = GridActual.getSelectionModel().getSelected().get('denominacion');
	idGridActual=GridActual.getId();
	switch(idGridActual)
	{
		case 'GI':
			RegistroActual.set('codgi',Cod);
			RegistroActual.set('dengi',Den);
			break;
		case 'CO':
			if(!RegistroActual.get('codco1'))
			{
				RegistroActual.set('codco1',Cod);
				RegistroActual.set('denco1',Den);	
			}
			else
			{
				RegistroActual.set('codco2',Cod);
				RegistroActual.set('denco2',Den);	
			}
			break;
		case 'VP':
			RegistroActual.set('codvp',Cod);
			RegistroActual.set('denvp',Den);	
			break;
		
	}
}

function ActualizarDataCat(criterio,criterio2,valor)
{
	TabActivo=tabs.getActiveTab().id;
	GridActual = ObtenerGrid(TabActivo);
	var myJSONObject ={
		"oper": 'buscarcadena', 
		"tipo":GridActual.getId(),
		"criterio":criterio, 
		"criterio2":criterio2, 
		"cadena": valor
	};	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) 
	{ 
		datos = resultado.responseText;	
		//alert(datos);  
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz==null)
		 {
			
			var DatosNuevo={"raiz":[{"spi_cuenta":'',"denominacion":'',"montoGlobal":'',"NuevoRegistro":''}]};
	
		 }		
		GridActual.store.loadData(DatosNuevo);
		
	}
});
	
}


function formCuentas()
{
	var myJSONObject =
	{
		"oper": 'catalogoplacuentas'
	};	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		datos = resultado.responseText;
	//	alert(datos);
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz==null)
		 {
			var DatosNuevo={"raiz":[{"codigo":'',"denominacion":''}]};
		 }		
		
		RecordDefCuentac = Ext.data.Record.create
		([
			{name: 'codigo'},// "mapping" property not needed if it's the same 
			{name: 'denominacion'},
		]);
			DataStoreCuentaContable =  new Ext.data.Store
			({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			root: 'raiz',                
			id: "id"   
			},
                    RecordDefCuentac
			      ),
				data: DatosNuevo
        });

		ForMontosCon = new Ext.Panel({
			layout:'form',	
			id:'formcuentas',
		//	renderTo:'sur',
			bodyStyle:'padding:5px 5px 0',
			height:150,  
			width:700,
			items:
			[
	  		{
			  xtype:'combo',
			  editable:true, 
			  store : DataStoreCuentaContable,
			  displayField:'denominacion',
			  valueField:'codigo',
			  hiddenName:'valorCuentaDebe',
			  hiddenId:'IdCuentaDebe',
			  listWidth:700, 
			  fieldLabel:'Desde',
			  name: 'desde',
			  typeAhead: true,
			  triggerAction: 'all',
			  id:'desde',
		      mode:'local'
	    	}
	    	,
	    	{
			  xtype:'combo',
			  editable:true, 
			  store : DataStoreCuentaContable,
			  displayField:'denominacion',
			  valueField:'codigo',
			  hiddenName:'valorCuentaDebe',
			  hiddenId:'IdCuentaDebe',
			  listWidth:700, 
			  fieldLabel:'Hasta',
			  name: 'hasta',
			  typeAhead: true,
			  triggerAction: 'all',
			  id:'hasta',
		      mode:'local'
	    	}	  	   
		]
	});
	}
})		
}

getGridReportes();
getCriterioBusqueda();

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
	                title:'Formatos Disponibles',
	                width: 710,
	                autoScroll:true,
	                bodyStyle:'background-color:#DFE8F6',
	                height:600,
	                contentEl:'centro'    
            	})
            	,
 	           new Ext.Panel({
	   
	   /*          region:'south',
	              layout:'table',
	              title:'Criterios de filtro para generar el Formato',
	              width: 710,
	              autoScroll:true,
	              bodyStyle:'background-color:#DFE8F6',
	              height:200,
	              contentEl:'sur'    
            })
        */    
            
        id: 'tab',
		plain: false,  //remove the header border
		region:'south',
		height:370,
		autoScroll:true,
		margins: '0 0 0 0',
		contentEl:'sur'
	})
            
            ]
          })		
});
