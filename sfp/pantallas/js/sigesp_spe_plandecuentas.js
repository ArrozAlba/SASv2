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
 
ruta ='../../procesos/sigesp_spe_conCuentaspr.php';
Ext.onReady(function()
{
    // basic tabs 1, built from existing content

function getobject()
{
			Oper[0]='incluyendo';
			var DatosNuevo={"raiz":[{"codGI":'',"denGI":''}]};
			RecordDefGI = Ext.data.Record.create([
			{name:'codigo'},
			{name: 'denominacion'}
			]);
			
			DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                
			    id: "id"   
			    },
                    RecordDefGI
			     
			      ),
				data: DatosNuevo
            });
			
			 grid = new Ext.grid.EditorGridPanel({
			width:780,
			id:'GI',
			height:300,
			autoScroll:true,
            border:true,
            ds:DataStore,
            cm: new Ext.grid.ColumnModel([
            {header: "C�digo", width: 50, sortable: true, dataIndex: 'codigo'},
            {header: "Denominaci�n", width:250, sortable: true, dataIndex: 'denominacion'}
                        ]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig: {
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
        grid.addListener('celldblclick',PasarDatos);
		grid.render('ContenedorGrid');
						
}



function getobject2()
{
	Oper[0]='incluyendo';
			var DatosNuevo={"raiz":[{"codigo":'',"denominacion":''}]};
			RecordDef = Ext.data.Record.create([
			{name:'codigo'},
			{name: 'denominacion'}
			]);
			
			DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                
			    id: "id"   
			    },
                    RecordDef
			     
			      ),
				data: DatosNuevo
            });
			
			grid2 = new Ext.grid.EditorGridPanel({
			width:780,
			height:400,
			id:'CO',
			autoScroll:true,
            border:true,
            ds:DataStore,
            cm: new Ext.grid.ColumnModel([
            {header: "C�digo", width: 50, sortable: true, dataIndex: 'codigo'},
            {header: "Denominaci�n", width:250, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.TextField({allowBlank: false})}
                        ]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig: {
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
        grid2.addListener('celldblclick',PasarDatos);
		grid2.render('ContenedorGrid2');
				
}


function getobject3()
{
	
			Oper[0]='incluyendo';
			var DatosNuevo={"raiz":[{"codigo":'',"denominacion":''}]};
			RecordDef = Ext.data.Record.create([
			{name:'codigo'},
			{name: 'denominacion'}
			]);
			
			DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                
			    id: "id"   
			    },
                    RecordDef
			     
			      ),
				data: DatosNuevo
            });
			
			 grid3 = new Ext.grid.EditorGridPanel({
			width:780,
			height:400,
			id:'VP',
			autoScroll:true,
            border:true,
            ds:DataStore,
            cm: new Ext.grid.ColumnModel([
            {header: "C�digo", width: 50, sortable: true, dataIndex: 'codigo'},
            {header: "Denominaci�n", width:250, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.TextField({allowBlank: false})}
                        ]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig: {
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
        grid3.addListener('celldblclick',PasarDatos);   
		grid3.render('ContenedorGrid3');
}

function getobject4()
{
	
			Oper[0]='incluyendo';
			var DatosNuevo={"raiz":[{"codigo":'',"denominacion":''}]};
			RecordDef = Ext.data.Record.create([
			{name:'codigo'},
			{name: 'denominacion'}
			]);
			
			DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                
			    id: "id"   
			    },
                    RecordDef
			     
			      ),
				data: DatosNuevo
            });
			
			 grid4 = new Ext.grid.EditorGridPanel({
			width:780,
			height:400,
			id:'CAI',
			autoScroll:true,
            border:true,
            ds:DataStore,
            cm: new Ext.grid.ColumnModel([
            {header: "C�digo", width: 50, sortable: true, dataIndex: 'codigo'},
            {header: "Denominaci�n", width:250, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.TextField({allowBlank: false})}
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


function getGridConversion()
{

	Columna=
	[
		['Debe'],
		['Haber']
	]	
	var storeTipo = new Ext.data.SimpleStore
	(
		{
	        fields: ['col'],
	        data : Columna // from states.js
	    }
	);
    
	var myJSONObject ={
			"oper": 'catalogo'
	};	
	
	
		ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		Ext.Ajax.request({
		url : ruta,
		params : parametros,
		method: 'POST',
		success: function (resultado, request) { 
		  datos = resultado.responseText;
	//	  alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			
		 }
		else
		{
			var DatosNuevo={"raiz":[{"codgi":'',"codcod":'',"dencod":'',"codcoh":'',"dencoh":'',"codvp":'',"denvp":'',"colvp":'',"codcai":'',"dencai":''}]};
			
		}		
		RecordDefConv = Ext.data.Record.create
		([
			{name:'codnivel'},
			{name:'CODGI'},
			{name:'DENGI'},
			{name:'CODCOD'},	
			{name:'DENCOD'},
			{name:'CODCOH'},
			{name:'dencoh'},
			{name:'CODVP'},
			{name:'denvp'},	
			{name:'colvp'},
			{name:'codcai'},
			{name:'dencai'},
			{name:'codconversion'}
		]);
			
			DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                        RecordDefConv
			      ),
				data: DatosNuevo
            });
			
			gridConversion = new Ext.grid.EditorGridPanel({
			width:2000,
			autoScroll:true,
            border:true,
            ds:DataStore,
            cm: new Ext.grid.ColumnModel([
            // new Ext.grid.RowNumberer(),
            {header: "Cuenta de Recurso/Gastos", width: 25, sortable: true, dataIndex: 'CODGI'},
                            
			  {header: "Denominaci�n", width:120, sortable: true, dataIndex: 'dengi'},
			  {header: "Cuenta Contable(Debe)", width: 25, sortable: true, dataIndex: 'CODCOD'},
                            
			  {header: "Denominaci�n", width: 120, sortable: true, dataIndex: 'dencod'}
,{header: "Cuenta Contable(Haber)", width:25, sortable: true, dataIndex: 'CODCOH'},
                            
			  {header: "Denominaci�n", width: 120, sortable: true, dataIndex: 'DENCOH'}
,{header: "Cuenta de Variaci�n Patrimonial", width: 25, sortable: true, dataIndex: 'codvp'},

{header: "Denominaci�n", width: 120, sortable: true, dataIndex: 'denvp'},
{header: "Columna", width: 50, sortable: true, dataIndex: 'colvp',editor:new Ext.form.ComboBox({
		  store :storeTipo,
		  editable:false,
		  displayField:'col',
		  name: 'columna',
		  id:'columna',
		  typeAhead: true,
	      triggerAction:'all',
	      mode: 'local'
		})
}
,{header: "Cuenta Ahorro/Inversi�n/Financiamiento(CAIF)", width: 25, sortable: true, dataIndex: 'codcai'}
,
{header: "Denominaci�n", width: 120, sortable: true, dataIndex: 'codcai'}
])
,
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig: {
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });   
		gridConversion.render('ContenedorGridCoversion');
		gridConversion.addListener('celldblclick',Actualizar);
}
				
})
}
Ext.get('BtnGrabar').on('click', function()
{
	
		if(Actualizar=='')
		{
			evento ='incluir';
			Mensa = "Incluido";
		}
		else
		{	
			evento ='actualizar';			
			Mensa = "Modificado";
		}

if(ValidarRegistroGrid(RegistroActual))
{

			var myJSONObject ={
					"oper": evento, 
					"codgi":RegistroActual.get('codgi'), 
					"codco1": RegistroActual.get('codco1'),
					"codco2":RegistroActual.get('codco2'),
					"codvp": RegistroActual.get('codvp'), 
					"colvp": RegistroActual.get('colvp'),
					"codcai":RegistroActual.get('codcai'),
					"dengi":RegistroActual.get('dengi'),
					"denvp":RegistroActual.get('denvp'),
					"codconversion":RegistroActual.get('codconversion'),
					"codemp":'0001'
			};

	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
    Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultad, request) { 
                 datos = resultad.responseText;
			//	alert(datos);
                 var Registros = datos.split("|");
                 if (Registros[1] == '1')
                 {
					Ext.MessageBox.alert('Mensaje','Registro '+Mensa + ' con �xito');
					gridConversion.store.commitChanges();
					LimpiarCampos();
					ActualizarDataConversion();
                 }
                 else
                 {
                 	Ext.MessageBox.alert('Error', 'El Registro no pudo ser '+Mensa); 
                 }
	},
	failure: function ( result, request) 
	{ 
		Ext.MessageBox.alert('Error', 'El Registro no pudo ser '+Mensa); 
	}

      });
}	
});
 


function Actualizar()
{	
	HabilitarObjetos(true);
	Actualizar=true;	
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


Ext.get('BtnNuevo').on('click', function()
{
GridActual= gridConversion;					
if(Oper!="incluyendo")
{
 
		 	var p = new RecordDefConv
			 (
	            {
					codgi:'',
					dengi:'',
					codco1:'',
					denco1:'',
					codco2:'',
					denco2: '', 
		            CODEMP: '',
		            codvp: '',
					denvp: '',	
					colvp: '',
					codcai: '',
					dencai: ''            
				}
	                   
	          );
	              
	    next = GridActual.store.getCount();   
		if(next==1)
		{
			codigo1 = GridActual.store.getRange(0,1);
			codigo2 = codigo1[0].get('codgi');
			if(!codigo2)
			{
				IndiceActual=0;
				RegistroActual = gridConversion.store.getAt(IndiceActual);				
				
			}
			else
			{
				
				GridActual.store.insert(1, p);
				GridActual.startEditing(1, 0);
				IndiceActual=1;	
				RegistroActual = gridConversion.store.getAt(IndiceActual);	
			}	
		}
		else
		{
			
			codigo1 = GridActual.store.getRange(0,1);
			codigo2 = codigo1[1].get('codgi');
			if(codigo2=='')
			{
				GridActual.startEditing(1, 0);	
				IndiceActual=1;
				RegistroActual = gridConversion.store.getAt(IndiceActual);	
			}
			else
			{
				GridActual.store.insert(next, p);
				GridActual.startEditing(next,0);
				IndiceActual=next;	
				RegistroActual = gridConversion.store.getAt(IndiceActual);	
			}
		}      
	   	Oper="incluyendo";	
	   	HabilitarObjetos(true);
	
}	
});


function LimpiarCampos()
{
	HabilitarObjetos(false);
	Actualizar='';
}

Ext.get('BtnElim').on('click',function()
{
	var Result;
	Ext.MessageBox.confirm('Confirmar', '�Desea eliminar este registro?', Result);
	function Result(btn)
	{
		RegistroActual = gridConversion.getSelectionModel().getSelected();
		if(btn=='yes')
		{
				var myJSONObject ={
					"oper": 'eliminar', 
					"codconversion":RegistroActual.get('codconversion')			
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
				//	alert(datos);
					
				 var Registros = datos.split("|");
				 if (Registros[1] == '1')
				 {
					Ext.MessageBox.alert('Mensaje','Registro '+Mensa + ' con �xito');
					LimpiarCampos();
					ActualizarDataConversion();
						
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
	
});
 
Ext.get('BtnImp').on('click',function()
{
	var myJSONObject ={
	"oper": 'Reporte'
	}
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ){ 
		  datos = resultado.responseText;
		 // alert(datos);
		 if(datos!='')
		 {
			Abrir_ventana(datos);
		 }
	
},
	failure: function ( result, request) 
	{ 
		Ext.MessageBox.alert('Error', result.responseText); 
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



function GetForm()
{
	
	FormularioBus = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
        url:'save-form.php',
        frame:true,
        applyTo:'form',
        style:'position:absolute;left:120px;top:35px',
        width: 350,
		height:50,
        defaults: {width: 230},
        defaultType: 'textfield',
		items: [{
                fieldLabel: 'C�digo',
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

function HabilitarObjetos(estado)
{
	if(estado==true)
	{
		FormularioBus.getComponent('cod').enable();
		FormularioBus.getComponent('cod').focus();
		FormularioBus.getComponent('den').enable();
		tabs.enable();	
	}
	else
	{
		FormularioBus.getComponent('cod').disable();
		FormularioBus.getComponent('den').disable();
		tabs.disable();	
	}
}


function ManejarTabActivo(tab)
{
	txtCod = FormularioBus.getComponent('cod');
	txtCod.reset();
	txtDen = FormularioBus.getComponent('den');
	txtDen.reset();
}


getobject();  
getobject2();
getobject3();
getobject4();
getGridConversion();
GetForm()
			var tabs = 	new Ext.TabPanel({
                            border:false,
                            activeTab:0,
                            height:200,
                            width:780,
                            style:'position:absolute;left:120px;top:95px',
                            renderTo:'tabPrin',
                            items:
							[
								{
		                            contentEl:'ContenedorGrid',
		                            listeners: {activate: ManejarTabActivo},
									title: 'Cuentas de Recursos/Gastos',
									id:'0',
									autoScroll:true
	                            }
								,
								{
	                                contentEl:'ContenedorGrid2',
	                                listeners: {activate: ManejarTabActivo},
	                                title: 'Cuentas Contables',
	                                autoScroll:true,
	                                id:'1'
	                            }
								,
	                            {
	                                contentEl:'ContenedorGrid3',
	                                listeners: {activate: ManejarTabActivo},
	                                title: 'Cuentas de Variaci�n Patrimonial',
	                                autoScroll:true,
	                                id:'2'
	
	                            }
								,
	                            {
	                                contentEl:'ContenedorGrid4',
	                                listeners: {activate: ManejarTabActivo},
	                                title: 'Cuentas de Ahorro/Inversi�n/Financiamiento(CAIF)',
	                                autoScroll:true,
	                                id:'3'
	                            }
							] 
                })

	   HabilitarObjetos(false);
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
            }),
            new Ext.Panel({
                region:'south',
                layout:'table',
                title:'Integraci�n de Cuentas',
                width: 710,
                autoScroll:true,
                bodyStyle:'background-color:#DFE8F6',
                height:200,
                contentEl:'sur'    
            })

            ]
          })		
});
