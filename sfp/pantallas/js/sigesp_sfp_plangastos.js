/*
 * Ext JS Library 2.0.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * http://extjs.com/license
 */
 
var gridOnOffFuente = false;
var winOnOff = false;
var datos = null;
var grid = null;
var grid2 = null;
var win = '';
var win2 = '';
var t=0;
var unavez = false;
var parametros='';
var ruta = '';
var RecordDef;
var gridCatCuentasCon="";
var grid2='';
var DataStore='';
var DatosNuevo ="";
var MontGlobal="";
var NumRegGrid=0;
var NumIni=0;
var Mostrado="";
var gridMontos='';
var winCuentasContable="";
var comboyear="";
var ForMontos='';
var gridIntFuente2="";
var CodCuenta="";
var NuevoRegistro = "";
var FormularioBus="";
var MontoAcum=0;
var gridCatCuentasCon="";
var gridMUnaVez=false;
var RegistroSel ='';
var Sesion='';
var gridIntFuenIng=null;
var RegistroSel="";
var ForMontosCon="";
var Year3='';
var AcuIng=0;
var Asientos=false;
var DataStoreFuenteIn="";
var datosRegistroActual="";
var rutaFuenteFin ='../../procesos/sigesp_sfp_fuentefinpr.php'; 
var rutaCatCont ='../../procesos/sigesp_spe_variacionpr.php';
var gridAsientoCont="";
var AcuDist=0;
var AcuMontoAux=0;
var anreal="";
var anpre ="";
var anest="";

function LlenaMontoEqCu(Obj)
{
	Asientos=true;
	ValorEq = ForMontos.getComponent('anpre').getValue();
	MontoEq = parseInt(ValorEq)/12;
	MontoRedondo=redondear(MontoEq);
	if(Obj.get('mes')!='Diciembre' && Obj.get('mes')!='Total')
	{
		Acum +=MontoRedondo;
	}
	else if(Obj.get('mes')=='Diciembre')
	{	
		MontoRedondo = ValorEq-Acum;
		Acum+=MontoRedondo;
	}
	else if(Obj.get('mes')=='Total')
	{
		MontoRedondo=Acum;
	}
	if(ValorEq!='' && Obj.get('year')!='')
	{
		MontoRedondo=numFormat(MontoRedondo,2, true);
		Obj.set('monto',MontoRedondo);
	}	
}


function CargaComboContable()
{
	//getIngresos();	
	var myJSONObject ={
		"oper": 'CatPlanContGas',
		"cuenta":CodCuenta
	};	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		datos = resultado.responseText;
	//	alert(datos);
		ArrDatos =  datos.split('|');
		DatosNuevo1 = eval('(' + ArrDatos[0] + ')');
		DatosNuevo2 = eval('(' + ArrDatos[1] + ')');
	//	alert(ArrDatos[1]);
		if(DatosNuevo1.raiz!=null)
		{
			//DataStoreCuentaContable.loadData(DatosNuevo1);
		}	
	}
})
}

// se vuelve a llamar al grid de fuentes de financiamiento.

function getobject2()
{

	/*
	var myJSONObject ={
		"oper": 'catalogo', 
		"cod_fuenfin": '', 
		"denfuefin": '',
		"expfuefin":''
};
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaFuenteFin,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		datos = resultado.responseText;
		//alert(datos);
		if(datos!='')
		{
			var myObject = eval('(' + datos + ')');
			if(myObject.raiz!=null)
			{
				myObject={"raiz":[{"codfuefin":'',"denfuefin":'',"monto":''}]};
			}
		}
		else
		{
			myObject={"raiz":[{"cod_fuenfin":'',"denfuefin":'',"monto":''}]};
		}
		var myObject = eval('(' + datos + ')');
		var RecordDef = Ext.data.Record.create([
		{name: 'cod_fuenfin'},     
		{name: 'denfuefin'},
		{name: 'monto',type:'float'}	
		]);
        if (!gridOnOffFuente)      
		{
          	gridIntFuente2 = new Ext.grid.EditorGridPanel({
			width:700,
			title:'Distribución por fuente de financimiento',
			autoScroll:true,
            border:true,
            ds: new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(myObject),
			reader: new Ext.data.JsonReader({
			root: 'raiz',
			id: "id"   
			 },
            RecordDef
			 ),
			data: myObject
          }),
          cm: new Ext.grid.ColumnModel([
          {header: "Denominación",width:50, sortable: true,   dataIndex: 'denfuefin'},
          {header: "Monto",width:20,sortable: true, dataIndex: 'monto',editor: new Ext.form.TextField({allowBlank: true})}    						]),

          	viewConfig: {
          	forceFit:true},
			autoHeight:true,
			stripeRows: true
           });
          gridOnOffFuente = true;
          gridIntFuente2.render('grid-fuentes2');
          }
          else
          {
          gridIntFuente2.store.loadData(myObject);
          } 				  		  
	
 }
 
 })
  
  */
  
}

function BlanquearMontoEq(Obj)
{
	Obj.set('monto',0000);
}


function validarMontosDist()
{
	Total=0;
	Arr = gridIntFuenIng.store.getModifiedRecords();
	for(i=0;i<Arr.length;i++)
	{
		if(Arr[i].get('montoadist')!='')
		{
			Total=Total+parseInt(ue_formato_calculo(Arr[i].get('montoadist')));
		}
	}
	//alert(Total);
	if(Total!=Ext.getCmp('anpre').getValue())
	{
		return false
	}
	else
	{
		return true;
	}		
			
}



function CatalogoCuentasContables()
{
			GridActual='1';
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
			
			
		function vercuentascon()
		{
			ActualizarDataCat('sc_cuenta','');
		}
		var MostrarCuentasCont = new Ext.Action(
		{
			text: 'Cuentas',
			handler: vercuentascon,
			iconCls: 'bmenuagregar',
		    tooltip: 'Mostrar Todas las cuentas'
		});				  			  
			
			if(gridCatCuentasCon=='')
			{
				gridCatCuentasCon = new Ext.grid.EditorGridPanel({
				width:780,
				height:200,
				tbar:[MostrarCuentasCont],
				id:'sc_cuenta',
				autoScroll:true,
	            border:true,
	            ds:DataStore,
	            cm: new Ext.grid.ColumnModel([
	            {header: "Código", width: 50, sortable: true, dataIndex: 'codigo'},
	            {header: "Denominación", width:250, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.TextField({allowBlank: false})}
	                ]),
	selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
	                        viewConfig: {
	                            forceFit:true
	                        },
				autoHeight:true,
				stripeRows: true
	            });
            }
    if(FormularioBus=="")
	{
		FormularioBus = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
        //url:'save-form.php',
        frame:true,
    //    style:'position:absolute;left:120px;top:35px',
        width: 350,
		height:90,
        defaults: {width: 230},
        defaultType: 'textfield',
		items: [{
                fieldLabel: 'Código',
                name: 'codc1',
				id:'codc1',
				changeCheck: function()
				{
					var v = this.getValue();
					GridA = gridCatCuentasCon;
					criterio=GridA.getId();
					ActualizarDataCat(criterio,v);
					if(String(v) !== String(this.startValue))
					{
						this.fireEvent('change', this, v, this.startValue);
					} 
				}
				, 
				initEvents:function()
				{
					AgregarKeyPress(this);
				}               
            }
			,
			{
                fieldLabel: 'Denominacion',
                name: 'denc1',
                id:'denc1',
				changeCheck: function()
						{
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
	else
	{
		Ext.getCmp('codc1').setValue('');
		Ext.getCmp('denc1').setValue('');
	}
            if(winCuentasContable=="")
            {
                   winCuentasContable = new Ext.Window(
                   {
                    //layout:'fit',
                    title: 'Cat&aacute;logo de Cuentas Contables',
		    		autoScroll:true,
                    width:800,
                    height:400,
                    modal: true,
                    closeAction:'hide',
                    plain: false,
                    items:[FormularioBus,gridCatCuentasCon],
                    buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {
                     	if(gridCatCuentasCon.getSelectionModel().getSelected())
                     	{
                     		//Nuevo=gridCatCuentasCon.getSelectionModel().getSelected().get('codigo')
		                   gridAsientoCont.getSelectionModel().getSelected().set('codigo',gridCatCuentasCon.getSelectionModel().getSelected().get('codigo'));
		                   gridAsientoCont.getSelectionModel().getSelected().set('denominacion',gridCatCuentasCon.getSelectionModel().getSelected().get('denominacion'));
			      		}
			      		else
			      		{
							Ext.MessageBox.alert('Mensaje','Debe seleccionar un registro')
						}
						winCuentasContable.hide();
                     }
                    }
					,
                    {
                     text: 'Salir',
                     handler: function()
                     {
                      win1.hide();
                     }
                    }]
                   });
                   //winOnOff = true;
                   //estaba alla donde dice aqui
                  }
         		winCuentasContable.show();
}

function ActualizarDataCat(criterio,valor)
{
	//alert(GridActual);
	GridAct = gridCatCuentasCon;
	var myJSONObject =
	{
		"oper": 'buscarcadena', 
		"tipo":GridAct.getId(),
		"criterio":criterio,
		"cadena": valor
	};	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaCatCont,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		datos = resultado.responseText;	
	//	alert(datos);  
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz==null)
		 {
			var DatosNuevo={"raiz":[{"spi_cuenta":'',"denominacion":'',"montoGlobal":'',"NuevoRegistro":''}]};
		 }		
		GridAct.store.loadData(DatosNuevo);
	}
});	

}

function leerAsientos()
{	
	var myJSONObject =
	{
		"oper": 'leerAsientos',
		"sig_cuenta":CodCuenta,
		"ano_presupuesto":Year3,
		"codemp":codemp,
		"codinte":IdPadre
	};	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	Ext.Ajax.request
	({
		url :rutaIntepr,
		params : parametros,
		method: 'POST',
		success: function (resultado, request) 
		{
			 datos = resultado.responseText;
			 if(datos!='')
			 { 	
			 	ArrAux=datos.split('|');
			 	ObJsonAs=Ext.util.JSON.decode(ArrAux[0]);
			 	ObJsonCa=Ext.util.JSON.decode(ArrAux[1]);
			  	gridAsientoCont.store.loadData(ObJsonAs);
			  	gridCaif.store.loadData(ObJsonCa);	
			 }
		}
	})
	
}



function cambio()
{
	if(ForMontos.getComponent('Dist').value=='Equitativo')
	{
		ForMontos.getComponent('MontoEq').enable();
	}
	else
	{
		ForMontos.getComponent('MontoEq').disable();
	}
	//alert('cambio');
}


function obtenersesion()
{
	var myJSONObject =
	{
		"oper": 'leersesion'
	};	
	
	ObjSon=JSON.stringify(myJSONObject);
	//alert(ObjSon);
	//return false;
	parametros = 'ObjSon='+ObjSon;
	Ext.Ajax.request
	({
		url :rutaIntepr,
		params : parametros,
		method: 'POST',
		success: function (resultado, request) 
		{
			 datos = resultado.responseText;
			 if(datos!='')
			 { 	
			 	Sesion=Ext.util.JSON.decode(datos);
			 }
		}
	})
}



function getGridMontosGastos(Obj,Row,col,Rec)
{

	//alert(gridIntGastos.getSelectionModel().getSelected().get('spg_cuenta'));
	datosRegistroActual = gridIntGastos.store.getAt(Row);	

	var fecha=new Date();
	/*Year = fecha.getFullYear();
	Year2 = Year-1;
	Year3 = Year+1;
	*/
	Year = '2008';
	Year2 = '2007';
	Year3 = '2009';
	
	
	
	anreal = 'Año Real al 31/12/'+(parseInt(Sesion.ano_presupuesto)-2);
	anpre = parseInt(Sesion.ano_presupuesto)+' Año del presupuesto';
	anest = (parseInt(Sesion.ano_presupuesto)-1)+' Último año estimado';	
	
	
	RegistroSel = gridIntGastos.getSelectionModel().getSelected();

	Tipos=
	[
		['Equitativo'],
		['Manual']
	]
	
	natGasto=
	[
		['Corrientes'],
		['Capital']
	]

	Meses =
	[
        ['1','Enero',gridIntGastos.getSelectionModel().getSelected().get('enero')],
        ['2','Febrero',gridIntGastos.getSelectionModel().getSelected().get('febrero')],
        ['3','Marzo',gridIntGastos.getSelectionModel().getSelected().get('marzo')],
        ['4','Abril',gridIntGastos.getSelectionModel().getSelected().get('abril')],
        ['5','Mayo',gridIntGastos.getSelectionModel().getSelected().get('mayo')],
        ['6','Junio',gridIntGastos.getSelectionModel().getSelected().get('junio')],
        ['7','Julio',gridIntGastos.getSelectionModel().getSelected().get('julio')],
        ['8','Agosto',gridIntGastos.getSelectionModel().getSelected().get('agosto')],
        ['9','Septiembre',gridIntGastos.getSelectionModel().getSelected().get('septiembre')],
        ['10','Octubre',gridIntGastos.getSelectionModel().getSelected().get('octubre')],
        ['11','Noviembre',gridIntGastos.getSelectionModel().getSelected().get('noviembre')],
        ['12','Diciembre',gridIntGastos.getSelectionModel().getSelected().get('diciembre')],
        ['13','Total',gridIntGastos.getSelectionModel().getSelected().get('montoglobal')]
	]	
	
	//alert(gridIntGastos.getSelectionModel().getSelected().get('monto_anest'));
	
	CodCuenta = gridIntGastos.getSelectionModel().getSelected().get('spg_cuenta');
	DenCuenta = gridIntGastos.getSelectionModel().getSelected().get('denominacion'); 
	Montoanest = gridIntGastos.getSelectionModel().getSelected().get('monto_anest');
	Montoanreal = gridIntGastos.getSelectionModel().getSelected().get('monto_anreal'); 
	EsNuevo = gridIntGastos.getSelectionModel().getSelected().get('NuevoRegistro');
	MontGlobal = gridIntGastos.getSelectionModel().getSelected().get('montoglobal');
	Evento = 'grabarPlan'; 
	//alert(MontGlobal);
	if(NuevoRegistro=='')
	{
		ano_pre = gridIntGastos.getSelectionModel().getSelected().get('ano_presupuesto');
		Evento = 'ActualizarPlan';
	}

     var storeMeses = new Ext.data.SimpleStore({
        fields: ['nombre'],
        data : Meses // from states.js
    });
    
    var storeTipo = new Ext.data.SimpleStore({
        fields: ['tipo'],
        data : Tipos // from states.js
    });
    
     var storenatGastos = new Ext.data.SimpleStore({
        fields: ['nat'],
        data : natGasto // from states.js
    });

	var DatosNuevoCont={"raiz":[{"codigo":'0000001',"denominacion":'78676789 Cuenta Numero1'}]};
		RecordDefCuentac = Ext.data.Record.create
		([
			{name: 'codigo'},// "mapping" property not needed if it's the same 
			{name: 'denominacion'},
		]);
			DataStoreCuentaContable =  new Ext.data.Store
			({
			proxy: new Ext.data.MemoryProxy(DatosNuevoCont),
			reader: new Ext.data.JsonReader({
			root: 'raiz',                
			id: "id"   
			},
                    RecordDefCuentac
			      ),
					data: DatosNuevoCont
        	});


//if(!gridMUnaVez)
//{
	  ForMontos = new Ext.FormPanel({
	  labelWidth:140, // label settings here cascade unless overridden,
	  labelAlign:'right',
	  title: 'Montos Mensuales',
	//  bodyStyle:'padding-top:5px;height:225px',
	  height:290,  
	  items:[
		{
		  xtype:'textfield', 
		  fieldLabel: 'Cuenta',
		  name: 'Cuenta',
		  value:CodCuenta,
		  readOnly:true,
		  id: 'Cuenta',
		  maxLength: 25,
		  maxLengthText: 'El campo excede la longitud máxima',
		  allowBlank:false,
		  width: 80
	    }
		,
		{
		  xtype:'textfield', 
		  fieldLabel: 'Denominación',
		  name: 'dennominacion',
		  readOnly:true,
		  value:DenCuenta,
		  id: 'denom',
		  maxLength: 470,
		  maxLengthText: 'El campo excede la longitud máxima',
		  allowBlank:false,
		  width: 370
	    }
		,
		{
		  xtype:'textfield', 
		  fieldLabel:anreal,
		  name: 'Cuenta',
		  value:Montoanest,
		  readOnly:true,
		  id: 'anreal',
		  maxLength: 25,
		  maxLengthText: 'El campo excede la longitud máxima',
		  allowBlank:false,
		  width: 80
	    }
		,
		{
		  xtype:'textfield', 
		  fieldLabel:anest,
		  name: 'anest',
		//  labelStyle:'width:160px;text-align:right',
		  value:Montoanreal,
		  readOnly:true,
		  id: 'anest',
		  maxLength: 25,
		  maxLengthText:'El campo excede la longitud máxima',
		  allowBlank:false,
		  allowDecimals:false,
		  width:80
	    }
	    ,
	   {
		  xtype:'textfield', 
		  fieldLabel: anpre,
		  name: 'anpre',
		  readOnly:false,
		  value:0,
		  id: 'anpre',
		  maxLength: 80,
		 // labelStyle:'width:160px;text-align:right',
		  maxLengthText: 'El campo excede la longitud máxima',
		  allowBlank:false,
		  allowDecimals:false,
		  width: 80		
		}
		,
		{
			  xtype:'combo',
			  editable:false, 
			  store : storenatGastos,
			  displayField:'nat',
			  value:'Corrientes',
			  fieldLabel: 'Naturaleza del Gasto',
			  name: 'natgas',
			  typeAhead: true,
			  triggerAction:'all',
			  id:'natgas',
		      mode:'local'
		    }
		,
	    {
		  xtype:'combo',
		  editable:true, 
		  store : storeTipo,
		  displayField:'tipo',
		 // labelStyle:'width:160px;text-align:right',
		  fieldLabel: 'Distribución',
		  name: 'Dist',
		  typeAhead: true,
		  triggerAction: 'all',
		  id:'Distri',
	      mode:'local'
	    },
	    {
	   	  xtype:'button',
		  handler:getIngresos,
		  text:'Fuentes de Financiamiento',
		  style:'position:absolute;left:370px;top:160px'
	    }
	    ]	  
	});
	
	function PonerAsientos()
	{
		NumrgridAs = gridAsientoCont.store.getCount()-1;
		RecgridAs = gridAsientoCont.store.getRange(0,NumrgridAs);
		for(i=0;i<RecgridAs.length;i++)
		{
			if(RecgridAs[i].get('codigo')!='')
			{
				RecgridAs[i].set('monto',Ext.getCmp('anpre').getValue());
			}
		}
		
				
		NumrgridAs = gridCaif.store.getCount()-1;
		RecgridAs = gridCaif.store.getRange(0,NumrgridAs);
		for(i=0;i<RecgridAs.length;i++)
		{
			if(RecgridAs[i].get('codigo')!='')
			{
				RecgridAs[i].set('monto',Ext.getCmp('anpre').getValue());
			}
		}
	}
	
	function Totalizar()
	{
		AuxPres = Ext.getCmp('anpre').getValue();
		//alert(AcuMontoAux);
		if(AuxPres==AcuMontoAux)
		{	
			PonerAsientos();
			Asientos=true;	
		}
		else
		{
			Ext.MessageBox.alert('Mensaje', 'La distribución de montos mensuales no coincide con el monto del presupuesto');	
			Asientos=false;	
		}	
	}
	
	
	gridMUnaVez=true;
	Ext.getCmp('Distri').on('select',function(){
	if(!MontGlobal)
	{
		if(Ext.getCmp('Distri').getValue()=='Equitativo' && Ext.getCmp('anpre').getValue()>0)
		{
			Acum=0;
			gridMontos.store.each(LlenaMontoEqCu);	
			PonerAsientos();
		}
		else if(Ext.getCmp('Distri').getValue()=='Manual' && Ext.getCmp('anpre').getValue()>0)
		{
			gridMontos.store.each(BlanquearMontoEq);
			Ext.getCmp('bntgenas').enable();
		}
	}
	})

//}
//else
//{
//	ForMontos.getComponent('Cuenta').setValue(CodCuenta);
//	ForMontos.getComponent('denom').setValue(DenCuenta);
//	ForMontos.getComponent('anpre').setValue(MontGlobal);
//}
			
			var DatosNuevo={"raiz":[{"programatica":'',"spg_cuenta":'',"year":'',"monto":''}]};	
			var storeMeses = new Ext.data.SimpleStore
			({
			        fields: 
					[
			           {name: 'numes'},
			           {name: 'mes'},
			           {name: 'monto'}
			        ]
			 });
			btn=new Ext.Button({text:'Generar Asientos',disabled:true,id:'bntgenas','handler':Totalizar}); 
			storeMeses.loadData(Meses);
			gridMontos = new Ext.grid.EditorGridPanel({
  			width:400,
	  		style:'margin-left:180px',
	  		bbar:btn,
  			title: 'Distribución Mensual',
			autoScroll:true,
            border:true,
            ds:storeMeses,
            cm: new Ext.grid.ColumnModel([
            {header: "Mes", width: 30, sortable: true,   dataIndex: 'mes'},
			{header: "Monto", width: 100, sortable: true, dataIndex: 'monto',editor: new Ext.form.NumberField({allowBlank: false,allowDecimals:false,id:'montomes'})}							
]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig: {
                            forceFit:true	
                        },
			autoHeight:true,
			stripeRows: true
            });
            
			AcuMontoAux=0;
		  	gridMontos.on('afteredit',function(Obj)
		  	{
		  		Ar = gridMontos.store.getRange(0,11);
		  		AcuMontoAux=0;
		  		for(i=0;i<Ar.length;i++)
		  		{
		  			AcuMontoAux +=Ar[i].get('monto');	
		  		}
			  		gridMontos.store.getAt(12).set('monto',AcuMontoAux);
			  	//return AcuMontoAux;	
		  	 })

		  	gridMontos.getView().getRowClass = function(record, index){
		  		if(record.data.mes=='Total')
		  		{
		  			return 'Total';
		  		}
      			
    		};	  	
 //	getobject2();
 /*	ForMontos2 = new Ext.Panel({
	  labelWidth: 75,   
	  items:[
		{
		  xtype:'panel',
		  name: 'dennominacion',
		  id: 'denomss',
		  contentEl:'grid-fuentesIng',
		  width:800,
		  height:150,
		 }
		]
		});*/
		
	CargaComboContable();		
	//definir el grid del asiento contable	
		var DatosNuevoAsientoCont={"raiz":[{"codigo":'',"denominacion":'',"operacion":'',"monto":''}]};
		RecordDefAsientoCont = Ext.data.Record.create
		([
			{name:'codigo'},     
			{name:'denominacion'},
			{name:'operacion'},
			{name:'monto'}
		]);
			DataStoreAsientoCont =  new Ext.data.Store
			({
			proxy: new Ext.data.MemoryProxy(DatosNuevoCont),
			reader: new Ext.data.JsonReader({
			root: 'raiz',                
			id: "id"   
			}
			,
               RecordDefAsientoCont
			   )
			   ,
				data:DatosNuevoAsientoCont
        	});
	
		gridAsientoCont = new Ext.grid.EditorGridPanel({
	  	width:780,
	  	title:'Asiento Contables',
		autoScroll:true,
	    border:true,
	    ds:DataStoreAsientoCont,
	    cm: new Ext.grid.ColumnModel([
	    {header: "Código", width: 40, sortable: true,   dataIndex: 'codigo'},
		{header: "Denominación", width: 130, sortable: true, dataIndex: 'denominacion'},
		{header: "Operación", width: 40, sortable: true, dataIndex: 'operacion'},
		{header: "Monto", width: 30, sortable: true, dataIndex: 'monto'}							
		]),
		selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
	    viewConfig: {
	    forceFit:true	
	    }
	    ,
		autoHeight:true,
		stripeRows: true
	 	});
	 	
/*
	 	//definir el grid del asiento contable	
		var DatosNuevoVarPat={"raiz":[{"codigo":'',"denominacion":'',"operacion":'',"monto":''}]};
		RecordDefVarPat = Ext.data.Record.create
		([
			{name:'codigo'},     
			{name:'denominacion'},
			{name:'operacion'},
			{name:'monto'}
		]);
			DataStoreVarPat =  new Ext.data.Store
			({
			proxy: new Ext.data.MemoryProxy(DatosNuevoCont)
			,reader: new Ext.data.JsonReader({
			root: 'raiz',                
			id: "id"   
			}
			,
               RecordDefVarPat
			)
			,
				data:DatosNuevoVarPat
        	});

	gridVarPat = new Ext.grid.EditorGridPanel({
  	width:780,
	autoScroll:true,
	title:'Asiento de Variación Patrimonial',
    border:true,
    ds:DataStoreVarPat,
    cm: new Ext.grid.ColumnModel([
    {header: "Código", width: 40, sortable: true,   dataIndex: 'codigo'},
	{header: "Denominación", width: 130, sortable: true, dataIndex: 'denominacion'},
	{header: "Operación", width: 40, sortable: true, dataIndex: 'operacion'},
	{header: "Monto", width: 30, sortable: true, dataIndex: 'monto'}							
	]),
	selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
    viewConfig: {
    forceFit:true	
    },
	autoHeight:true,
	stripeRows: true
 });
	
	*/
	

//definir el asiento de caif.
	
		var DatosNuevoCaif={"raiz":[{"codigo":'',"denominacion":'',"monto":''}]};
		RecordDefCaif = Ext.data.Record.create
		([
			{name:'codigo'},     
			{name:'denominacion'},
			{name:'operacion'},
			{name:'monto'}
		]);
			DataStoreCaif =  new Ext.data.Store
			({
			proxy: new Ext.data.MemoryProxy(DatosNuevoCont)
			,reader: new Ext.data.JsonReader({
			root: 'raiz',                
			id: "id"   
			}
			,
               RecordDefCaif
			)
			,
				data:DatosNuevoCaif
        	});
        	
	gridCaif = new Ext.grid.EditorGridPanel({
  	width:780,
	autoScroll:true,
	title:'Cuentas del formato CAIF que se afectan',
    border:true,
    ds:DataStoreCaif,
    cm: new Ext.grid.ColumnModel([
    {header: "Código", width: 40, sortable: true,   dataIndex: 'codigo'},
	{header: "Denominación", width: 130, sortable: true, dataIndex: 'denominacion'},
	{header: "Monto", width: 30, sortable: true, dataIndex: 'monto'}							
	]),
	selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
    viewConfig: {
    forceFit:true	
    },
	autoHeight:true,
	stripeRows: true
 	});
	 	
		gridAsientoCont.addListener('celldblclick',function()
		{
			if(gridAsientoCont.getSelectionModel().getSelected().get('operacion')=='Debe' && Ext.get('natgas').getValue()=='Corrientes')
			{
				Ext.MessageBox.alert('Mensaje', 'Solo se puede modificar la cuenta a debitar cuando la naturaleza del gasto sea de capital');
			}
			else
			{
				CatalogoCuentasContables();	
			}		
		});	
 	
			
 				win = new Ext.Window(
                {
                    layout:'anchor',
                    title: 'Montos',
		    		autoScroll:true,
                    width:800,
                    height:600,
                    modal: true,
                    closable:false,
                    plain: false,
                    items:[ForMontos,gridMontos,gridAsientoCont,gridCaif],
                     buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {
                     if(!MontGlobal)
					 {	
		      			if(GrabarPlanCuenta())
		      			{
		      				grabargasto();
		      			}
		      		}
		      				win.destroy();
		      				ForMontos.destroy();
			      		//	ForMontosCon.destroy();
			      			gridAsientoCont.destroy();
			      			gridCaif.destroy();
                     }
                    },
                    {
                     text: 'Salir',
                     handler: function()
                    {	
                     		ForMontos.destroy();
			      		//	ForMontosCon.destroy();
			      			gridAsientoCont.destroy();
			      			gridCaif.destroy();	
			      			win.destroy();
                     }
                    }]
                   });
                  win.show();

   function CancelarDist(Obj)
   {
 		Obj.set('montoadist',00);				 	
   }     
 	if(MontGlobal && datosRegistroActual.get('NuevoRegistro')!=true)
	{
		//alert(grid.getSelectionModel().getSelected().get('montoglobal'));
		// que cargan los datos de los asientos que ya se generaron para esta cuenta
	//	alert('aqui');
		leerAsientos();
		//Ext.getCmp('anreal').setValue(datosRegistroActual.get('montoanoanterior'));
		//Ext.getCmp('anest').setValue(datosRegistroActual.get('montoanoactual'));
		Ext.getCmp('anpre').setValue(datosRegistroActual.get('montoglobal'));
		Ext.getCmp('anreal').disable();
		Ext.getCmp('anest').disable();
		Ext.getCmp('anpre').disable();
		Ext.getCmp('montomes').disabled=true;
		Evento = 'ActualizarPlan';
	}
	else
	{
		//alert('por aqui se mete');
		//Se cargan los datos de los asientos que se van a generar 
		//tomando en cuenta la tabla de cuentas asociadas y el plan general de cuentas integrado
		
		//Ext.getCmp('anpre').setValue(datosRegistroActual.get('montoglobal'));	
		cantidadAsientoCont = gridAsientoCont.store.getCount();
		var p1 = new RecordDefAsientoCont
		(
		    {
				codigo:datosRegistroActual.get('codigohaber'),
			    denominacion:datosRegistroActual.get('denhaber'),
			    operacion: 'Haber',
			    monto:datosRegistroActual.get('montoglobal')
			}
	    );
	    var p2 = new RecordDefAsientoCont
		(
		    {
				codigo:datosRegistroActual.get('codigodebe'),
			    denominacion:datosRegistroActual.get('dendebe'),
			    operacion: 'Debe',
			    monto:datosRegistroActual.get('montoglobal')
			}
	    );
	    gridAsientoCont.store.insert(0,p1);
		gridAsientoCont.store.insert(0,p2);
		/*
		if(datosRegistroActual.get('codvardebe')!='')
		{
		     var p3 = new RecordDefVarPat
			(
				{
					codigo:datosRegistroActual.get('codvardebe'),
					denominacion:datosRegistroActual.get('denvardebe'),
					operacion:"Debe",
					monto:datosRegistroActual.get('montoglobal')
				}
			);
			gridVarPat.store.insert(0,p3);
			llenarGridCaif(datosRegistroActual.get('codvardebe'));
		}
		*/
		if(datosRegistroActual.get('codvarhaber')!='')
		{
		     var p4 = new RecordDefCaif
			(
				{
					codigo:datosRegistroActual.get('codvarhaber'),
					denominacion:datosRegistroActual.get('denvarhaber'),
					operacion:"Haber",
					monto:datosRegistroActual.get('montoglobal')
				}
			);
			//gridVarPat.store.insert(0,p4);
			//llenarGridCaif(datosRegistroActual.get('codvarhaber'));
		}
		var p5 = new RecordDefCaif
		(
			{
				codigo:datosRegistroActual.get('codcaif'),
				denominacion:datosRegistroActual.get('denominacion'),
				monto:datosRegistroActual.get('montoglobal')
			}
		);
	
		gridCaif.store.insert(0,p5);
		gridCaif.store.insert(1,p4);
	}
	
	if(datosRegistroActual.get('fuentes'))
	{	
			Arfuentes=datosRegistroActual.get('fuentes'); 		
			for(i=0;i<Arfuentes.length;i++)
			{
					//alert(Arfuentes[i]);
					Arun=Arfuentes[i].split("|");
					resp = gridIntFuenIng.store.find('sig_cuenta',Arun[0]);	
					if(resp>0)
					{
						AuxRec = gridIntFuenIng.store.getAt(resp);
						//alert(resp);
						//alert('antes del error');
						Auxmonto =parseInt(Arun[1]);
						AuxRec.set('montoadist',Auxmonto);
								//alert('despues del error');
								//Auxdisp = AuxRec.get('disponible');
								//AuxTotal =Auxdisp-Arun[1]; 
								//AuxRec.set('disponible',AuxTotal);
					 }
			 }
 	 }
 	else
 	 {
 		//gridIntFuenIng.store.each(CancelarDist)
 	 }
}


function llenarGridCaif(cuenta)
{
	var myJSONObject ={
		"oper":'buscarcaif', 
		"sig_cuenta":cuenta 
	};
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	 success: function ( resultad, request )
	 { 
	    datos = resultad.responseText;
	    if(datos!="")
	    {
	    	JsonCaif=Ext.util.JSON.decode(datos);
	    	var paux = new RecordDefCaif
			(
				{
					codigo:JsonCaif.raiz[0].codcaif,
					denominacion:JsonCaif.raiz[0].denominacion,
					monto:datosRegistroActual.get('montoglobal')
				}
			);
			gridCaif.store.insert(0,paux);
	    }
		else
		{
			Ext.MessageBox.alert('Error', 'El registro');				
		}
     }
      ,
	failure: function ( result, request)
	{ 
		Ext.MessageBox.alert('Error', result.responseText); 
	} 
    });
}

function GrabarPlanCuenta()
{
if(validarMontosDist())
{
	if(Asientos==true)
	{
		Anno = Year3;
		Cuenta = ForMontos.getComponent('Cuenta').getValue();
		CuentaDebe = gridAsientoCont.store.getAt(0).get('codigo');
		CuentaHaber = gridAsientoCont.store.getAt(1).get('codigo');
		montoanoanterior=Ext.getCmp('anreal').getValue();
		montoanoactual=Ext.getCmp('anest').getValue();
		montopres=parseInt(Ext.getCmp('anpre').getValue());
		MontosMeses = gridMontos.store.getRange(0,11);
		FuentesActual = gridIntFuenIng.store.getModifiedRecords();
		ArrFuentes=new Array();
		for(i=0;i<FuentesActual.length;i++)
		{
			if(FuentesActual[i].get('montoadist')!='0')
			{
				Registro= FuentesActual[i].get('sig_cuenta')+'|'+FuentesActual[i].get('montoadist');
				ArrFuentes.push(Registro);	
			}
			
		}	
		
		//if(parseInt(montopres)==parseInt(AcuDist))
		//{
	
			RegistroSel.set('montoglobal',montopres);
			RegistroSel.set('ano_presupuesto',Anno);
			RegistroSel.set('enero',MontosMeses[0].get("monto"));
			RegistroSel.set('febrero',MontosMeses[1].get("monto"));
			RegistroSel.set('marzo',MontosMeses[2].get("monto"));
			RegistroSel.set('abril',MontosMeses[3].get("monto"));
			RegistroSel.set('mayo',MontosMeses[4].get("monto"));
			RegistroSel.set('junio',MontosMeses[5].get("monto"));
			RegistroSel.set('julio',MontosMeses[6].get("monto"));
			RegistroSel.set('agosto',MontosMeses[7].get("monto"));
			RegistroSel.set('septiembre',MontosMeses[8].get("monto"));
			RegistroSel.set('octubre',MontosMeses[9].get("monto"));
			RegistroSel.set('noviembre',MontosMeses[10].get("monto"));
			RegistroSel.set('diciembre',MontosMeses[11].get("monto"));
			RegistroSel.set('fuentes',ArrFuentes);
			RegistroSel.set('NuevoRegistro',true);
			RegistroSel.set('montoanpre',montopres);
			RegistroSel.set('montoanoanterior',montoanoanterior);
			RegistroSel.set('montoanoactual',montoanoactual);
			RegistroSel.set('cuentadebe',CuentaDebe);
			RegistroSel.set('cuentahaber',CuentaHaber);
			return true;
		}
		else
		{
			Ext.MessageBox.alert('Mensaje','La operación no se puede realizar debido a que no se han generado los asientos contables, verifique la distribución de los montos mensuales y presione el botón generar asientos');
			return false;
		}
	}
	else
	{
		Ext.MessageBox.alert('Mensaje','El Monto presupuestado no coincide con la sumatoria del monto distribuido');
		return false;
	}
		
}
function BorrarCuenta()
{
	var selectedKeys = grid.selModel.selections.keys;
        if(selectedKeys.length > 0) {
            Ext.Msg.confirm('Mensaje','Realmente desea eliminar el registro?', deleteRecord);
        } else {
            Ext.Msg.alert('Mensaje','Seleccione un registro para eliminar');
        }
}

 function deleteRecord(btn) 
 {
	  if (btn=='yes') 
	  {
		var selectedRow = grid.getSelectionModel().getSelected();
		if(selectedRow)
		{
					Cuenta = grid.getSelectionModel().getSelected().get('spi_cuenta');
					Anno = grid.getSelectionModel().getSelected().get('ANO_PRESUPUESTO');
						reg = "{'oper':'eliminarPlan','ano_presupuesto':'"+Anno+"','spi_cuenta':'" + Cuenta +"','codemp':'0001'}";
 					Obj= eval('(' + reg + ')');
					ObjSon=JSON.stringify(Obj);
					parametros = 'ObjSon='+ObjSon; 
					Ext.Ajax.request({
					url : ruta2,
					params : parametros,
					method: 'POST',
					success: function ( resultad, request ){ 
				    datos = resultad.responseText;
					var Registros = datos.split("|");
					Cod = Registros[1];
						if(Cod!='')
						{
							Ext.MessageBox.alert('Mensaje', 'Registro eliminado con éxito');
							grid.store.commitChanges();
							//alert(grid2.store.getCount());
							ActualizarData();
						}
						else
						{
							Ext.MessageBox.alert('Error', 'El registro');				
						}
				      },
					failure: function ( result, request)
					 { 
						Ext.MessageBox.alert('Error', result.responseText); 
					 } 
				    });
			
	     		DataStore.remove(selectedRow);
		}
	  } 

}


function getIngresos()
{		
	if(!MontGlobal)
	{
		Leer="todas"
	}
	else
	{
		Leer ="una";
	}
	
	var myJSONObject = {
		"oper":'leerecursos',
		"Leer":Leer,
		"sig_cuenta":CodCuenta
	};
	var RecordDef = Ext.data.Record.create([
			{name: 'sig_cuenta'},     
			{name: 'denominacion'},
			{name: 'montoglobal'},
			{name: 'distribuido'},
			{name: 'disponible'},
			{name: 'montoadist',type:'float'}		
			
		]);
			myObject={"raiz":[{"sig_cuenta":'',"denominacion":'',"montoglobal":'',"distribuido":'',"disponible":''}]};
			//if(gridIntFuenIng==null)
			//{	
				DataStoreFuenteIn = new Ext.data.Store({
				proxy: new Ext.data.MemoryProxy(myObject),
				reader: new Ext.data.JsonReader({
				root: 'raiz',
				id: "id"   
				 },
	            RecordDef
				 ),
				data: myObject
	          })
	        //}	
	        gridIntFuenIng = new Ext.grid.EditorGridPanel({
			width:850,
			title:'Monto: '+Ext.get('anpre').dom.value+' Bs',
			autoScroll:true,
			height:200,
          //  border:true,
           ds:DataStoreFuenteIn,
          cm: new Ext.grid.ColumnModel([
         {header: "Código",width:70, sortable: true,   dataIndex: 'sig_cuenta'},
         {header: "Denominación",width:300,sortable: true, dataIndex: 'denominacion'},
         {header: "Total Programado",width:115, sortable: true,   dataIndex: 'montoglobal'},
         {header: "Total Distribuido",width:115,sortable: true, dataIndex: 'distribuido',id:'distribuido'},
		 {header: "Total Disponible",width:115,sortable: true, dataIndex: 'disponible' ,id:'disponible'},
		 {header: "Montos a Distribuir",width:115,sortable: true,id:'montoadist', dataIndex: 'montoadist',editor: new Ext.form.TextField({allowBlank: true,allowDecimals:false,id:'montDist'})}]),
		  selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
          viewConfig:
          {
           //	forceFit:true	
          }
           });
          gridOnOffFuente = true;
    
	if(MontGlobal)
	{
		gridIntFuenIng.getColumnModel().getColumnById('montoadist').hidden=true;
		gridIntFuenIng.getColumnModel().getColumnById('disponible').hidden=true;
		gridIntFuenIng.getColumnModel().getColumnById('distribuido').header="Total Distribuido en la Partida "+CodCuenta;
		gridIntFuenIng.getColumnModel().getColumnById('distribuido').width=260;
	}
    
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url:rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		datos = resultado.responseText;
		//alert(datos);
		if(datos!='')
		{
			var myObject = eval('(' + datos + ')');
			if(myObject.raiz==null)
			{
				myObject={"raiz":[{"sig_cuenta":'',"denominacion":'',"montoglobal":'',"distribuido":'',"disponible":''}]};
			}
		}
		else
		{
				myObject={"raiz":[{"sig_cuenta":'',"denominacion":'',"montoglobal":'',"distribuido":'',"disponible":''}]};
		}
             	DataStoreFuenteIn.loadData(myObject);	
 	
 }
 })
 //}
 				
             	winIngresos = new Ext.Window(
                 {
                    layout:'anchor',
                    title: 'Fuentes de Financiamiento',
		    		autoScroll:true,
                    width:900,
                    height:250,
                    modal: true,
                    closable:false,
                    plain: false,
                    items:[gridIntFuenIng],
                    buttons: [{
                    text:'Aceptar',  
                    handler: function()
                     {	 
	                     if(!MontGlobal)
						 {	
			      				if(Ext.get('anpre').dom.value!='')
			      				{
			      					Ext.get('anpre').dom.value=ue_formato_calculo(AcuIng);
			      				}
			      				AcuIng=0;
	                     }
	                     winIngresos.destroy();
	                }
                    }
                    ,
                    {
                     text: 'Salir',
                     handler: function()
                     {
                     		winIngresos.destroy();
                     		AcuIng=0;
			      			//gridIntFuenIng.destroy();
                     }
                    }]
                   });
            winIngresos.show();
            
 			gridIntFuenIng.on('afteredit',function(Obj){
 			 if(!MontGlobal)
			 {
	 			Rec = Obj.record;
			    MontoDisponible = ue_formato_calculo(Rec.get('disponible'));
			    MontoDistribuido = ue_formato_calculo(Rec.get('distribuido'));
			    if(Obj.originalValue!='')
			    {
			    	MontoDisponible = parseInt(MontoDisponible)+parseInt(ue_formato_calculo(Obj.originalValue));
					MontoDistribuido = parseInt(MontoDistribuido)- parseInt(ue_formato_calculo(Obj.originalValue));
			    }
			    if(Obj.value=='')
			    {
			    	Obj.value=0;
			    }	
			    if(parseInt(Obj.value)>=0)
			    {
			    		AcuDist =Obj.value;
			    		if(parseInt(AcuDist)>parseInt(MontoDisponible))
			    		{
					    		Ext.MessageBox.alert('Mensaje','Este Monto es mayor al monto disponible');
					    		Rec.set('montoadist',0);
					    		
					    			
					    }
				    	else
				    	{
				    			AcuIng+=parseInt(ue_formato_calculo(Obj.value));	
				    			DispNuevo = parseInt(MontoDisponible)-ue_formato_calculo(Obj.value);
					    		DistNuevo = parseInt(MontoDistribuido)+ parseInt(ue_formato_calculo(Obj.value));
					    		DispNuevo = numFormat(DispNuevo,2, true);
					    		DistNuevo = numFormat(DistNuevo,2, true);
					    		Rec.set('disponible',DispNuevo);
					    		Rec.set('distribuido',DistNuevo);
					    		gridIntFuenIng.getSelectionModel().selectNext();	
				    	}
			    }
		    }
		})
	 
}



function CatPlanCuentas()
{
	var myJSONObject ={
		"oper": 'CatCuenGas', 
		"cod_fuenfin": "", 
		"denfuefin":"",
		"expfuefin":""
	};
	
 Ext.MessageBox.show({
           msg: 'Por Favor Espere',
           title: 'Cargando Datos',
           progressText: 'Cargando Datos',
           width:300,
           wait:true,
           waitConfig: {interval:40},
           animEl: 'mb7'
 });
	 
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	var myObject = {"raiz":[{"sig_cuenta":'',"denominacion":''}]};
	var RecordDef = Ext.data.Record.create([
		{name: 'codigo'},     
		{name: 'denominacion'},
		{name:'codigodebe'},
		{name:'dendebe'},	
		{name:'codigohaber'},
		{name:'denhaber'},
		{name:'codcaif'},
		{name:'codvarhaber'},	
		{name:'codvardebe'},
		{name:'denvarhaber'},
		{name:'denvardebe'},
		{name:'monto_anest'},
		{name:'monto_anreal'}
		]);
			
			gridCatGastos = new Ext.grid.GridPanel({
			width:770,
			autoScroll:true,
            border:true,
            ds: new Ext.data.Store({
			reader: new Ext.data.JsonReader({
			root:'raiz',                
			id: "id"       
			},
               RecordDef
			     
			),
			data: myObject
            })
			,
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.CheckboxSelectionModel(),
                 
                            {header: "Código", width: 20, sortable: true,   dataIndex: 'codigo'},
                            {header: "Denominación", width: 50, sortable: true, dataIndex: 'denominacion'}]),
	sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
          });
                
		gridOnOff = true;    
		function vercuentas()
		{
			ActualizarDataCuentas('sig_cuenta','');
		}
		var MostrarCuentas = new Ext.Action(
		{
			text: 'Cuentas',
			handler: vercuentas,
			iconCls: 'bmenuagregar',
		    tooltip: 'Mostrar Todas las cuentas'
		});				  			  
		var simple2 = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
        url:'save-form.php',
        frame:true,
        title: 'Búsqueda',
        bbar:[MostrarCuentas],
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
							 ActualizarDataCuentas('sig_cuenta',v);
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
							 ActualizarDataCuentas('denominacion',v);
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
		
                   winPlanGastos = new Ext.Window
				   (
				   {
                    title: 'Catálogo de Cuentas de Gastos',
		   			autoScroll:true,
                    width:800,
                    height:400,
                    modal: true,
                    closeAction:'hide',
                    plain: false,
                    items:[simple2,gridCatGastos],
                    buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {   
                     	//datosRegistroActual = gridCatGastos.getSelectionModel().getSelected();
		            	//PasarDatosgrid(datosRegistroActual);
		            	datosRegistroActual =  gridCatGastos.getSelectionModel().getSelections();
		            	resp = validarExistencia(gridCatGastos,gridIntGastos,'codigo','spg_cuenta');
		            	if(resp==true)
		            	{
		            		return false;
		            	}
	                  	for(i=0;i<datosRegistroActual.length;i++)
	                  	{
						  PasarDatosgrid(datosRegistroActual[i]);
						}
		              	winPlanGastos.destroy();
                     }
                    }
					,
                    {
                     text: 'Salir',
                     handler: function()
                     {
                      winPlanGastos.destroy();
                     }
                    }]
	                    
                   });
                    winOnOff = true;
	                winPlanGastos.show();
		            Ext.MessageBox.hide();                       
}


function grabargasto()
{
//alert("sss");
reg="";
if(IdPadre=='')
{
	Ext.MessageBox.alert('Mensaje','Debe Seleccionar una Estructura Presupuestaria');
	return false;
}	
else
{
	var reg = "{";
	reg = reg + "'oper':'actualizarInt','codemp':'0001','codinte':'"+IdPadre+"'";
		
}	
	if(gridIntVar.getSelectionModel().getSelected() && gridIntVar.getSelectionModel().getSelected().get('NuevoRegistro')==true)
	{
	arrMetas = gridIntVar.getSelectionModel().getSelected();
	metas=false;
	//if(arrMetas.length>0)
	//{	
		reg=reg+ ",DatosMetas:[";
	//	for(i=0;i<arrMetas.length;i++)
	//	{
	//		if(i==0)
	//		{
				reg= reg +"{'ano_presupuesto':'2009','cod_var':'"+ arrMetas.get('cod_var')+"','enero_masc':'"+arrMetas.get('enero_masc')+"','febrero_masc':'"+arrMetas[i].get('febrero_masc')+"','marzo_masc':'"+arrMetas.get('marzo_masc')+"','abril_masc':'"+arrMetas.get('abril_masc')+"','mayo_masc':'"+arrMetas.get('mayo_masc')+"','junio_masc':'"+arrMetas.get('junio_masc')+"','julio_masc':'"+arrMetas.get('julio_masc')+"','agosto_masc':'"+arrMetas.get('agosto_masc')+"','septiembre_masc':'"+arrMetas.get('septiembre_masc')+"','octubre_masc':'"+arrMetas.get('octubre_masc')+"','noviembre_masc':'"+arrMetas.get('noviembre_masc')+"','diciembre_masc':'"+arrMetas.get('diciembre_masc')+"','enero_fem':'"+arrMetas.get('enero_fem')+"','febrero_fem':'"+arrMetas.get('febrero_fem')+"','marzo_fem':'"+arrMetas.get('marzo_fem')+"','abril_fem':'"+arrMetas.get('abril_fem')+"','mayo_fem':'"+arrMetas.get('mayo_fem')+"','junio_fem':'"+arrMetas.get('julio_fem')+"','agosto_fem':'"+arrMetas.get('agosto_fem')+"','septiembre_fem':'"+arrMetas.get('septiembre_fem')+"','octubre_fem':'"+arrMetas.get('octubre_fem')+"','noviembre_fem':'"+arrMetas.get('noviembre_fem')+"','diciembre_fem':'"+arrMetas.get('diciembre_fem')+"'}";
		//	}
		//	else
		//	{
		//		reg= reg +",{'ano_presupuesto':'2009','cod_var':'"+ arrMetas[i].get('cod_var')+"','enero_masc':'"+arrMetas[i].get('enero_masc')+"','febrero_masc':'"+arrMetas[i].get('febrero_masc')+"','marzo_masc':'"+arrMetas[i].get('marzo_masc')+"','abril_masc':'"+arrMetas[i].get('abril_masc')+"','mayo_masc':'"+arrMetas[i].get('mayo_masc')+"','junio_masc':'"+arrMetas[i].get('julio_masc')+"','agosto_masc':'"+arrMetas[i].get('agosto_masc')+"','septiembre_masc':'"+arrMetas[i].get('septiembre_masc')+"','octubre_masc':'"+arrMetas[i].get('octubre_masc')+"','noviembre_masc':'"+arrMetas[i].get('noviembre_masc')+"','diciembre_masc':'"+arrMetas[i].get('diciembre_masc')+"','enero_fem':'"+arrMetas[i].get('enero_fem')+"','febrero_fem':'"+arrMetas[i].get('febrero_fem')+"','marzo_fem':'"+arrMetas[i].get('marzo_fem')+"','abril_fem':'"+arrMetas[i].get('abril_fem')+"','mayo_fem':'"+arrMetas[i].get('mayo_fem')+"','junio_fem':'"+arrMetas[i].get('julio_fem')+"','agosto_fem':'"+arrMetas[i].get('agosto_fem')+"','septiembre_fem':'"+arrMetas[i].get('septiembre_fem')+"','octubre_fem':'"+arrMetas[i].get('octubre_fem')+"','noviembre_fem':'"+arrMetas[i].get('noviembre_fem')+"','diciembre_fem':'"+arrMetas[i].get('diciembre_fem')+"'}";
		//	}
		//}
		reg = reg + "]";
	}
	else
	{
		//return false;
	}	
	if(gridIntGastos.getSelectionModel().getSelected() && gridIntGastos.getSelectionModel().getSelected().get('NuevoRegistro')==true )
	{
	arrGas = gridIntGastos.getSelectionModel().getSelected();
	montoFormateado=numFormat(arrGas.get('montoglobal'),2,true);
	
	//if(arrGas.length>0)
	//{	
			reg=reg+ ",DatosGas:[";
			reg= reg +"{'codemp':'0001','sig_cuenta':'"+ arrGas.get('spg_cuenta')+"','MontoGlobal':'"+ ue_formato_calculo(arrGas.get('montoglobal'))+"','ano_presupuesto':'"+ arrGas.get('ano_presupuesto')+"','enero':'"+ ue_formato_calculo(arrGas.get('enero'))+"','febrero':'"+ ue_formato_calculo(arrGas.get('febrero'))+"','marzo':'"+ ue_formato_calculo(arrGas.get('marzo'))+"','abril':'"+ ue_formato_calculo(arrGas.get('abril'))+"','mayo':'"+ ue_formato_calculo(arrGas.get('mayo'))+"','junio':'"+ ue_formato_calculo(arrGas.get('junio'))+"','julio':'"+ ue_formato_calculo(arrGas.get('julio'))+"','agosto':'"+ ue_formato_calculo(arrGas.get('agosto'))+"','septiembre':'"+ ue_formato_calculo(arrGas.get('septiembre'))+"','octubre':'"+ ue_formato_calculo(arrGas.get('octubre'))+"','noviembre':'"+ ue_formato_calculo(arrGas.get('noviembre'))+"','diciembre':'"+ ue_formato_calculo(arrGas.get('diciembre'))+"','monto':'"+ue_formato_calculo(arrGas.get('montoglobal'))+"','CuentaDebe':'"+arrGas.get('cuentadebe')+"','CuentaHaber':'"+arrGas.get('cuentahaber')+"','montoanreal':'"+ue_formato_calculo(arrGas.get('montoanoanterior'))+"','montoanant':'"+ue_formato_calculo(arrGas.get('montoanoactual'))+"'";
			Fuentes = arrGas.get('fuentes');	
			reg=reg+",'fuentes':[";
			for(j=0;j<Fuentes.length;j++)
			{
				auxArray = Fuentes[j].split('|');
				if(j==0)
				{
					reg=reg+ "{'sig_cuenta_ing':'"+auxArray[0]+"','montoasig':'"+ue_formato_calculo(auxArray[1])+"'}";
				}
				else
				{
					reg=reg+ ",{'sig_cuenta_ing':'"+auxArray[0]+"','montoasig':'"+ue_formato_calculo(auxArray[1])+"'}";
				}
				
			}
			reg=reg+"]}";
		reg = reg + "]";
	//}
	}
	else if(metas==false)
	{
		return false;
	}
	reg = reg + "}";
	
	
	//return false;
	Obj= eval('(' + reg + ')');
	ObjSon=JSON.stringify(Obj);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function ( resultad, request ){ 
        datos = resultad.responseText;
		// alert(datos);
		//Ext.get('norte').dom.innerHTML=datos;
		 var Registros = datos.split("|");
				if (Registros[1] == '1')
				 {
					Ext.MessageBox.alert('Mensaje','Los Montos fueron asignados con éxito')
					if(gridIntGastos.getSelectionModel().getSelected())
					{
						gridIntGastos.getSelectionModel().getSelected().set('NuevoRegistro',false);
						gridIntGastos.getSelectionModel().getSelected().set('montoglobal',montoFormateado);
						gridIntGastos.getSelectionModel().getSelected().commit();
					}
							
					if(gridIntVar.getSelectionModel().getSelected())
					{
						gridIntVar.getSelectionModel().getSelected().set('NuevoRegistro',false);
					}
						
							
					//location.href='sigesp_spe_formGasto.php';							
				 }
				 else if(Registros[1]=='-5')
				 {
				  	Ext.MessageBox.alert('Error', 'La integración presupuestaria seleccionada ya existe, la combinación de la estructura del plan y la estructura presupuestaria seleccionada ya fue registrada, verifique mediante el catálogo');
				  	EstadoInicial();
				 }
				else if(Registros[1]=='-1')
				 {
				  	Ext.MessageBox.alert('Error', 'La integración presupuestaria seleccionada ya existe, la combinación de la estructura del plan y la estructura presupuestaria seleccionada ya fue registrada, verifique mediante el catálogo');
				  	EstadoInicial();
				 } else if(Registros[1]=='0')
				 {
				 	Ext.MessageBox.alert('Mensaje', 'No se pudo realizar la operación');
				 }
				 else
				 {
				 	var myObject = eval('(' + datos + ')');
				 	IdPadre = myObject.raiz[0].codinte;
				 	DesabilitarGrids(false);
				 	ActualizarGrids();
				 }
				
      },
	failure: function ( result, request)
	 { 
		Ext.MessageBox.alert('Error', result.responseText); 
	 } 
});
};

function PasarDatosgrid(Registro)
{

	var p = new RecordDef
	(
	    {
			spg_cuenta:'',
		    denominacion:'',
		    monto: 00,
		    NuevoRegistro:true,
		    enero:0,
		    febrero:0,
		    marzo:0,
		    abril:0,
		    mayo:0,
		    junio:0,
		    julio:0,
		    agosto:0,
		    septiembre:0,
		    octubre:0,
		    noviembre:0,
		    diciembre:0,	    
		    codigohaber:Registro.get('codigohaber'),
		    denhaber:Registro.get('denhaber'),
		    codigodebe:Registro.get('codigodebe'),
		    dendebe:Registro.get('dendebe'),
		    codvarhaber:Registro.get('codvarhaber'),
		    denvarhaber:Registro.get('denvarhaber'),
		    codvardebe:Registro.get('codvardebe'),
		    denvardebe:Registro.get('denvardebe'),
		    codcaif:Registro.get('codcaif'),
		    monto_anest:Registro.get('monto_anest'),
		    monto_anreal:Registro.get('monto_anreal')	
		}
    )
    ;
   // alert(NumIni);
    	
//	alert(p);
		
	NumIni = gridIntGastos.store.getCount();
/*	NumIni = NumRegGrid-1;	
		
    if(NumIni>0)
    {
    	NumIni= NumIni + 1;	
    }
    */
	gridIntGastos.store.insert(NumIni,p);
	//alert('probar');
	p.set('spg_cuenta',Registro.get('codigo'));	
	p.set('denominacion',Registro.get('denominacion'));	

}

function PasarDatosgrid2(Registro,indice)
{
	var p = new RecordDef
	(
	    {
			spg_cuenta:'',
		    denominacion:'',
		    monto: 00,
		    NuevoRegistro:true,
		    enero:0,
		    febrero:0,
		    marzo:0,
		    abril:0,
		    mayo:0,
		    junio:0,
		    julio:0,
		    agosto:0,
		    septiembre:0,
		    octubre:0,
		    noviembre:0,
		    diciembre:0,	    
		    codigohaber:Registro.raiz[0].codigohaber,
		    denhaber:Registro.raiz[0].denhaber,
		    codigodebe:Registro.raiz[0].codigodebe,
		    dendebe:Registro.raiz[0].dendebe,
		    codvarhaber:Registro.raiz[0].codvarhaber,
		    denvarhaber:Registro.raiz[0].denvarhaber,
		    codvardebe:Registro.raiz[0].codvardebe,
		    denvardebe:Registro.raiz[0].denvardebe,
		    codcaif:Registro.raiz[0].codcaif
		}
    )
    ;	
	NumIni = gridIntGastos.store.getCount();
	gridIntGastos.store.insert(indice,p);
	//alert('probar');
	p.set('spg_cuenta',Registro.raiz[0].codigo);	
	p.set('denominacion',Registro.raiz[0].denominacion);	
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

function ActualizarDataGridGastos()
{
	var myJSONObject ={
		"oper": 'CatPlanIn', 
		"cod_fuenfin": "", 
		"denfuefin": "",
		"expfuefin":""
	};	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta2,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request )
	{ 
		datos = resultado.responseText;	  
		DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
		//alert(DatosNuevo)	
		
		  }
		else
		 {	
			var DatosNuevo={"raiz":[{"spi_cuenta":'',"denominacion":'',"montoGlobal":'',"NuevoRegistro":''}]};
		 }
		grid.store.loadData(DatosNuevo);
}
});	
}


function ActualizarDataCuentas(criterio,valor)
{

	var myJSONObject ={
		"oper": 'CatCuenGasCad', 
		"criterio":criterio, 
		"cadena": valor
	};	
	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) 
	{ 
		datos = resultado.responseText;	  
		//alert(datos);
		DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz==null)
		 {
			
			var DatosNuevo={"raiz":[{"spg_cuenta":'',"denominacion":'',"montoGlobal":'',"NuevoRegistro":''}]};
		
		  }
	
		gridCatGastos.store.loadData(DatosNuevo);
		
	}
}
);
	
}
function ActualizarDataCuentas2(criterio,valor,indice)
{

	var myJSONObject ={
		"oper": 'CatCuenGasCad', 
		"criterio":criterio, 
		"cadena": valor
	};	
	
	//alert(indice);
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) 
	{ 
		datos = resultado.responseText;	  
		
		DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz==null)
		 {
			var DatosNuevo={"raiz":[{"spg_cuenta":'',"denominacion":'',"montoGlobal":'',"NuevoRegistro":''}]};
		 }
		 PasarDatosgrid2(DatosNuevo,indice);	
	}
}
);
}
