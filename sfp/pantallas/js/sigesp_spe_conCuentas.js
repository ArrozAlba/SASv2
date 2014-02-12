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
var DataStoreConversion="";
var Oper = "";
var Actualizar='';
var FormularioBus="";
var gridPlanCuentas="";
var DataStorePlan="";
 
ruta ='../../procesos/sigesp_spe_conCuentaspr.php';
pantalla ='sigesp_spe_conCuentas.php';
Ext.onReady(function()
{
ObtenerSesion(ruta,pantalla)
function getGridConversion()
{
	var FormBusqueda = new Ext.FormPanel
	({
        labelWidth: 75, // label settings here cascade unless overridden
        frame:true,
        bodyStyle:'padding:5px 5px 0',
        width: 350,
		height:70,
        defaults: {width: 230},
        items:[
        {
        	xtype:'textfield',
        	fieldLabel:'codigo',
			name:'txtcod',
			readOnly:false,
			id: 'txtcod',
			maxLength: 80,
			maxLengthText:'El campo excede la longitud máxima',
			width: 170	,
			enableKeyEvents:true,
			listeners:{
		    'keypress':function(Obj,e){
		    	var whichCode = e.keyCode; 
			    	if (whichCode == 13)  
					{		 
						ActualizarDataCat('sig_cuenta',this.getValue());	
					}
		    	}
			}
		}
		,
		{
		 	 xtype:'textfield',
			 fieldLabel:'denominacion',
			 name:'txtden',
			 readOnly:false,
			enableKeyEvents:true,
			listeners:{
		    'keypress':function(Obj,e){
		    	var whichCode = e.keyCode; 
			    	if (whichCode == 13)  
					{		 
						   Ext.MessageBox.show({
				           msg: 'Por Favor Espere',
				           title: 'Buscando Datos',
				           progressText: 'Buscando Datos',
				           width:300,
				           wait:true,
				           waitConfig:{interval:100},
				           animEl: 'mb7'
				      });
						ActualizarDataCat('denominacion',this.getValue());
						
					}
		    	}
			
			}			 ,
			 id: 'txtden',
			 maxLength: 80,
			 maxLengthText:'El campo excede la longitud máxima',
			 width: 170	
		}
		]     
     })

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
    
	
	
		var DatosNuevo={"raiz":[{"codgi":'',"codcod":'',"dencod":'',"codcoh":'',"dencoh":'',"codvp":'',"denvp":'',"colvp":'',"codcai":'',"dencai":''}]};
		RecordDefConv = Ext.data.Record.create
		([
			{name:'codcuenta'},
			{name:'dencuenta'},
			{name:'codigodebe'},
			{name:'dendebe'},	
			{name:'codigohaber'},
			{name:'denhaber'},
			{name:'codcaif'},
			{name:'codvarhaber'},	
			{name:'codvardebe'},
			{name:'denvarhaber'},
			{name:'denvardebe'}
		]);
			
			DataStoreConversion =  new Ext.data.Store({
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
			width:2500,
			height:300,
			id:'gridconversion',
			renderTo:'ContenedorGridCoversion',
			autoScroll:true,
			tbar:[FormBusqueda],
            border:true,
            ds:DataStoreConversion,
            cm: new Ext.grid.ColumnModel([
            // new Ext.grid.RowNumberer(),
            new Ext.grid.CheckboxSelectionModel(),
            {header: "Cuenta de Recurso/Gastos", width: 80, sortable: true, dataIndex: 'codcuenta'},
                            
			  {header: "Denominación", width:150, sortable: true, dataIndex: 'dencuenta'},
			  {header: "Cuenta Contable(Debe)", width: 70, sortable: true, dataIndex: 'codigodebe'},
                            
			  {header: "Denominación", width: 120, sortable: true, dataIndex: 'dendebe'}
,{header: "Cuenta Contable(Haber)", width:70, sortable: true, dataIndex: 'codigohaber'},
                            
			  {header: "Denominación", width: 120, sortable: true, dataIndex: 'denhaber'}
,{header: "Cuenta CAIF(debe)", width: 110, sortable: true, dataIndex: 'codvardebe'},

{header: "Denominación", width: 150, sortable: true, dataIndex: 'denvardebe'}
,{header: "Cuenta CAIF(haber)", width: 110, sortable: true, dataIndex: 'codvarhaber'},
{header: "Denominación", width: 150, sortable: true, dataIndex: 'denvarhaber'}
])
,
sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
                        viewConfig: {
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
     });   
		

			
}

Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank2.php';
})
function VerificarExistencia(Dato)
{
		AuxResp = gridPlanCuentas.store.find('codcuenta',Dato);
		return AuxResp;				
}


function getGridPlanCuentas()
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
		success: function (resultado, request){ 
		datos = resultado.responseText;
		 // alert(datos);
		  //Ext.get('norte').dom.innerHTML=datos;
		  if(datos!='')
		  {
				 var DatosNuevo = eval('(' + datos + ')');
				 if(DatosNuevo.raiz==null)
				 {
					var DatosNuevo={"raiz":[{"codcuenta":'',"dencuenta":'',"codigodebe":'',"dendebe":'',"codigohaber":'',"denhaber":'',"codcaif":'',"codvarhaber":'',"codvardebe":'',"denvarhaber":'',"denvardebe":''}]};
				 }		

		  }
		  else
		  {		  	
		  		var DatosNuevo={"raiz":[{"codcuenta":'',"dencuenta":'',"codigodebe":'',"dendebe":'',"codigohaber":'',"denhaber":'',"codcaif":'',"codvarhaber":'',"codvardebe":'',"denvarhaber":'',"denvardebe":''}]};		
		  }
		RecordDefConv = Ext.data.Record.create
		([
			{name:'codcuenta'},
			{name:'dencuenta'},
			{name:'codigodebe'},
			{name:'dendebe'},	
			{name:'codigohaber'},
			{name:'denhaber'},
			{name:'codcaif'},
			{name:'codvarhaber'},	
			{name:'codvardebe'},
			{name:'estatus'},
			{name:'denvarhaber'},
			{name:'denvardebe'}
		]);
			
			DataStorePlan =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                        RecordDefConv
			      ),
				data: DatosNuevo
            });
			gridPlanCuentas = new Ext.grid.EditorGridPanel({
			width:2500,
			height:400,
			autoScroll:true,
            border:true,
            ds:DataStorePlan,
            cm: new Ext.grid.ColumnModel([
            // new Ext.grid.RowNumberer(),
            new Ext.grid.CheckboxSelectionModel(),
            {header: "Cuenta de Recurso/Gastos", width: 80, sortable: true, dataIndex: 'codcuenta'},
                            
			  {header: "Denominación", width:150, sortable: true, dataIndex: 'dencuenta'},
			  {header: "Estatus", width:150, sortable: true, dataIndex: 'estatus'},
			  {header: "Cuenta Contable(Debe)", width: 70, sortable: true, dataIndex: 'codigodebe'},             
			  {header: "Denominación", width: 120, sortable: true, dataIndex: 'dendebe'}
,{header: "Cuenta Contable(Haber)", width:70, sortable: true, dataIndex: 'codigohaber'},
                            
			  {header: "Denominación", width: 120, sortable: true, dataIndex: 'denhaber'}
,{header: "Cuenta CAIF(debe)", width: 110, sortable: true, dataIndex: 'codvardebe'},

{header: "Denominación", width: 150, sortable: true, dataIndex: 'denvardebe'}
,{header: "Cuenta CAIF(haber)", width: 110, sortable: true, dataIndex: 'codvarhaber'},
{header: "Denominación", width: 150, sortable: true, dataIndex: 'denvarhaber'}
])
,
sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
                        viewConfig: {
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
                      
                    gridPlanCuentas.render('ContenedorGridPlanCuentas');
				    gridPlanCuentas.addListener('celldblclick',getModifCuenta);					
					gridPlanCuentas.getView().getRowClass = function(record, index){
			  		if(record.data.estatus=='C')
			  		{
			  			return 'Filazul';
			  		}}						
}	
			
})
}

function getModifCuenta(Obj,Row,col,Rec)
{
	  datosRegistroActual = gridPlanCuentas.store.getAt(Row);
	  codigo = datosRegistroActual.get('codcuenta');
	  den = datosRegistroActual.get('dencuenta');
	  ForCuenta = new Ext.form.FormPanel({
	  labelWidth:140, // label settings here cascade unless overridden,
	  labelAlign:'right',
	  border:false,
	  title: 'Información de la cuenta',
	  bodyStyle:'padding-top:5px;height:170px;background-color:#DFE8F6',
	  style:'height:210px',
	  height:200,  
	  items:[
	 {
	  xtype:'textfield', 
	  fieldLabel: 'Cuenta',
	  name: 'Cuenta',
	  value: codigo,
	  id: 'Cuenta',
	  maxLength: 25,
	  maxLengthText: 'El campo excede la longitud máxima',
	  allowBlank:false,
	  width:200
    }
	,
	{
	  xtype:'textfield', 
	  fieldLabel: 'Denominación',
	  name: 'dennominacion',
	  value:den,
	  id: 'denom',
	  maxLength: 470,
	  maxLengthText: 'El campo excede la longitud máxima',
	  allowBlank:false,
	  width: 370
    }
   	]
	});
	
	  winCuenta = new Ext.Window(
      {
           layout:'fit',
           title: 'Cuenta',
   		   autoScroll:true,
           width:650,
           height:200,
           closable:false,
           modal: true,
           closeAction:'hide',
           plain: false,
           items:[ForCuenta],
           buttons: [{
           text:'Aceptar',  
           handler: function()
		   {
		   		nuevocod = Ext.getCmp('Cuenta').getValue();
		   		nuevaden = Ext.getCmp('denom').getValue();
		   		if(nuevocod!='' && nuevaden!='')
		   		{
		   			grabarnuevacuenta(nuevocod,nuevaden);
		   		}
		   		ForCuenta.destroy();
		    	winCuenta.destroy(); 
		   }}
		   ,
		   {
		    text:'Salir',
		    handler: function()
		    {
		    	ForCuenta.destroy();
		    	winCuenta.destroy(); 		
		    }
		   }
		]
		});
		winCuenta.show();
}

function grabarnuevacuenta(nuevocod,nuevaden)
{
	var myJSONObject =
	{
		"oper":'grabarnuevacuenta',
		"sig_cuenta":nuevocod,
		"denominacion":nuevaden
	};	
	
	ObjSon=JSON.stringify(myJSONObject);
	
	//alert(ObjSon);
	//return false;
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultad, request)
    { 
               datos = resultad.responseText;
               var Registros = datos.split("|");
               if (Registros[1] == '1')
               {
				Ext.MessageBox.alert('Mensaje','Registro Incluido con Éxito');
				ActualizarDataPlanCuentas();
				gridConversion.getSelectionModel().clearSelections();
               }
               else
               {
               	Ext.MessageBox.alert('Error', 'El Registro no pudo ser incluido'); 
               }
	},
	failure: function (result,request) 
	{ 
		Ext.MessageBox.alert('Error', 'El Registro no pudo ser incluido'); 
	}
  });
	
	
	
}
Ext.get('BtnGrabar').on('click', function()
{
	Ext.MessageBox.show({
           msg: 'La Información está siendo procesada',
           title: 'Por favor espere',
           progressText: 'Procesando...',
           width:300,
           wait:true,
           waitConfig:{interval:100},
           animEl: 'mb7'
    });
    numDatos = gridConversion.getSelectionModel().getSelections();
	//alert(numDatos.length);
	var reg = "{'oper':'pasaregistro','registros':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{
		Esta = VerificarExistencia(numDatos[i].get('codcuenta'));
	//	alert(Esta);
		if(Esta<0)
		{
			if(i==0)
			{
				reg = reg + "{'sig_cuenta':'" + numDatos[i].get('codcuenta') +"','estatus':'"+numDatos[i].get('estatus')+"'}";
			}	
			else
			{			
	            reg = reg + ",{'sig_cuenta':'" + numDatos[i].get('codcuenta')+"','estatus':'"+numDatos[i].get('estatus')+"'}";
			}
		}
		else
		{
			Ext.MessageBox.alert('Mensaje','La cuenta '+numDatos[i].get('codcuenta')+' ya existe dentro del plan de cuentas');	
		}
	}
	reg = reg + "]}";
	
	DatosNuevo = eval('(' + reg + ')');
	ObjSon=JSON.stringify(DatosNuevo);
	parametros = 'ObjSon='+ObjSon; 
    Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultad, request)
         { 
                 datos = resultad.responseText;
		    //	alert(datos);
                 var Registros = datos.split("|");
                 if (Registros[1] == '1')
                 {
					Ext.MessageBox.alert('Mensaje','Registro Incluido con Éxito');
					ActualizarDataPlanCuentas();
					gridConversion.getSelectionModel().clearSelections();
                 }
                 else
                 {
                 	Ext.MessageBox.alert('Error', 'El Registro no pudo ser incluido'); 
                 }
		},
	failure: function (result,request) 
	{ 
		Ext.MessageBox.alert('Error', 'El Registro no pudo ser incluido'); 
	}
  });
	
});
 

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
				 if(DatosNuevo.raiz==null)
				 {
					var DatosNuevo={"raiz":[{"codcuenta":'',"dencuenta":'',"codigodebe":'',"dendebe":'',"codigohaber":'',"denhaber":'',"codcaif":'',"codvarhaber":'',"codvardebe":'',"denvarhaber":'',"denvardebe":''}]};		
				 }		
		  }
		  else
		  {
		  		var DatosNuevo={"raiz":[{"codcuenta":'',"dencuenta":'',"codigodebe":'',"dendebe":'',"codigohaber":'',"denhaber":'',"codcaif":'',"codvarhaber":'',"codvardebe":'',"denvarhaber":'',"denvardebe":''}]};
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

Ext.get('BtnElim').on('click',function()
{
	var Result;
	Ext.MessageBox.confirm('Confirmar', '¿Desea eliminar este registro?', Result);
	function Result(btn)
	{
		RegistroActual = gridPlanCuentas.getSelectionModel().getSelected();
		if(RegistroActual)
		{
		if(btn=='yes')
		{
				var myJSONObject =
				{
					"oper": 'eliminar', 
					"sig_cuenta":RegistroActual.get('codcuenta'),
					"estatus":RegistroActual.get('estatus')	
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
					Ext.MessageBox.alert('Mensaje','Registro '+Mensa + ' con éxito');
					ActualizarDataPlanCuentas();			
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
		}
		else
		{
			 Ext.MessageBox.alert('Mensaje', 'Debe seleccionar un registro a eliminar del plan de cuentas');
		}
	
	};
	
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
                fieldLabel: 'Código',
                name: 'cod',
				id:'cod',
				changeCheck: function()
				{
					var v = this.getValue();
					auxCodigo = ObtenerCodigo(tabs.getActiveTab().id)
					ActualizarDataCat('codcuenta',auxCodigo);
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
							 ActualizarDataCat('denominacion',v);
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

function ActualizarDataCat(criterio,valor)
{
	      Ext.MessageBox.show({
          msg: 'Por Favor Espere',
          title: 'Cargando Datos',
          // progressText: 'Cargando Datos',
          width:300,
          wait:true,
          waitConfig:{interval:100}
          // animEl: 'mb7'
        });
		var myJSONObject =
		{
			"oper": 'catalogofiltro',
			"criterio":criterio,
			"valor":valor
		};	
				
		ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		Ext.Ajax.request({
		url : ruta,
		params : parametros,
		method: 'POST',
		success: function (resultado, request){ 
		  datos = resultado.responseText;
		  //alert(datos);
		 if(datos!='')
		 {
		 	var DatosNuevo = eval('(' + datos + ')');
				 if(DatosNuevo.raiz==null)
				 {
					var DatosNuevo={"raiz":[{"codcuenta":'',"dencuenta":'',"codigodebe":'',"dendebe":'',"codigohaber":'',"denhaber":'',"codcaif":'',"codvarhaber":'',"codvardebe":'',"denvarhaber":'',"denvardebe":''}]};
					
				 }
		 }
		else
		{
			var DatosNuevo={"raiz":[{"codgi":'',"codcod":'',"dencod":'',"codcoh":'',"dencoh":'',"codvp":'',"denvp":'',"colvp":'',"codcai":'',"dencai":''}]};
		}	
			DataStoreConversion.loadData(DatosNuevo);     
		 	Ext.MessageBox.hide();		
		}
				
		})
}

getGridConversion();
getGridPlanCuentas();
//GetForm()
			
	 
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
                title:'Plan General de Cuentas Integrado',
                width: 710,
                autoScroll:true,
               // bodyStyle:'background-color:#DFE8F6',
                height:400,
                contentEl:'centro'    
              })
            ,
            new Ext.Panel({
                region:'south',
                layout:'table',
                title:'Plan General de Cuentas Integrado del Organismo',
                width: 710,
                autoScroll:true,               
                bodyStyle:'background-color:#DFE8F6',
                style:'padding-top:10px;padding-bottom:20px',
                height:200,
                contentEl:'sur'    
            })
            ]
          })		
});
