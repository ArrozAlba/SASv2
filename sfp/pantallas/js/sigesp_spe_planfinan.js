
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
var grid2 = null;
var winCatCuentas='';
var win2 = '';
var t=0;
var codcuenxcobrar ='';
var unavez = false;
var Sesion='';
var parametros='';
var ruta = '';
var Acum =0;
var RecordDef;
var grid2='';
var DataStore='';
var DatosNuevo ="";
var NumRegGrid=0;
var NumIni=0;
var Mostrado="";
var gridMontos='';
var comboyear="";
var ForMontos='';
var ForMontosCon='';
var NuevoRegistro = "";
var MontoTotal=0;
var simpleCuentasIn='';
var CodCuenta='';
var gridMUnaVez=false;
var datosRegistroActual='';
var Year3='';
var anpre='';
var codemp='0001';
var porcobrar = '';
var gridMontos="";
var FormularioBus="";
var winCuentasContable="";
var gridCatCuentasCon="";
var ForMontos= "";
var winMontos="";
var ForModif="";
var RecordDefAsientoCont="";
var RecordDefVarPat="";
var RecordDefCaif="";
var gridVarPat="";
var gridAsientoCont="";
var gridCaif="";
var Meses="";
var reg="";

ruta ='../../procesos/sigesp_sfp_fuentefinpr.php';
ruta2 ='../../procesos/sigesp_spe_planfinanpr.php';
pantalla ='sigesp_spe_planfinan.php';
rutaCatCont ='../../procesos/sigesp_spe_variacionpr.php';
Ext.onReady(function(){
ObtenerSesion(ruta2,pantalla);
function getobject()
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
	success: function(resultado, request) { 
	datos = resultado.responseText;
	DatosNuevo = eval('(' + datos + ')');
		if(DatosNuevo.raiz==null)
		{
			var DatosNuevo={"raiz":[{"spi_cuenta":'',"denominacion":'',"montoGlobal":'',"NuevoRegistro":''}]};
		}
		grid.store.loadData(DatosNuevo);
	}
	
});	
		var agregar = new Ext.Action(
		{
			text: 'Agregar',
			handler: irAgregar,
			iconCls: 'bmenuagregar',
        	tooltip: 'Agregar cuenta'
		});
		
		var modificar = new Ext.Action(
		{
			text: 'Modificar',
			handler: getActualizar,
			iconCls: 'bmenumodif',
        	tooltip: 'Modificar Monto Asignado'
		});
		
		var quitar = new Ext.Action(
		{
			text: 'Quitar',
			handler: irQuitar,
			iconCls: 'bmenuquitar',
        	tooltip: 'Eliminar Cuenta'
		});
		RecordDef = Ext.data.Record.create
		([
			{name: 'sig_cuenta'},// "mapping" property not needed if it's the same 
			{name: 'denominacion'},
			{name: 'montoglobal'},
			{name: 'montoglobalcob'},
			{name: 'NuevoRegistro'},
			{name: 'ano_presupuesto'},
			{name: 'enero'},
			{name: 'febrero'},
			{name: 'marzo'},
			{name: 'abril'},
			{name: 'mayo'},
			{name: 'junio'},
			{name: 'julio'},
			{name: 'agosto'},
			{name: 'septiembre'},
			{name: 'octubre'},
			{name: 'noviembre'},
			{name: 'diciembre'},
			{name: 'enerocob'},
			{name: 'febrerocob'},
			{name: 'marzocob'},
			{name: 'abrilcob'},
			{name: 'mayocob'},
			{name: 'juniocob'},
			{name: 'juliocob'},
			{name: 'agostocob'},
			{name: 'septiembrecob'},
			{name: 'octubrecob'},
			{name: 'noviembrecob'},
			{name: 'diciembrecob'},
			{name: 'montoanoanterior'},
			{name: 'montoanoactual'},
			{name: 'codigohaber'},
			{name: 'denhaber'},
			{name: 'codigodebe'},
			{name: 'dendebe'},
			{name: 'montoanoactual'},
			{name: 'codigohaber'},
			{name: 'denhaber'},
			{name: 'codigodebe'},
			{name: 'dendebe'},
			{name: 'codvarhaber'},
			{name: 'denvarhaber'},
			{name: 'codvardebe'},
			{name: 'denvardebe'},
			{name: 'codcaif'},
			{name: 'distribuido'},
			{name: 'codcaifvarpat'},
			{name:'monto_anest'},
			{name:'monto_anreal'},
			{name:'cuentaporcobrar'}	
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
			grid = new Ext.grid.EditorGridPanel({
  			width:750,
			autoScroll:true,
            border:true,
            ds:DataStore,
            tbar: [agregar,modificar,quitar],
            cm: new Ext.grid.ColumnModel([
            {header: "Código", width: 100, sortable: true,  dataIndex: 'sig_cuenta'},
            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denominacion'},
			{header: "Monto Global", width: 100, sortable: true, dataIndex: 'montoglobal',align:'right',editor: new Ext.form.TextField({allowBlank: false})}			
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig: {
                        forceFit:true	
                        },
			autoHeight:true,
			stripeRows: true
            });		   
		grid.render('grid-example');
		grid.addListener('celldblclick',getGridMontos);		
 		Ext.state.Manager.setProvider(new Ext.state.CookieProvider());        
        var viewport = new Ext.Viewport({
            layout:'border',
            items:[
                new Ext.BoxComponent({ // raw
                    region:'north',
                    el: 'norte',
                    height:50
                })
                ,
                new Ext.BoxComponent({ // raw
                    region:'center',
                    el: 'grid-example',
                    height:50,
                    style:'padding-top:60px;padding-left:110px'
                })
				,  
			  new Ext.BoxComponent({ // raw
                    region:'south',
                    el: 'progra',
                    height:0
               })
           	]
         })
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
		url :ruta2,
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


Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank2.php';
})

function BlanquearMontoEq(Obj)
{
	Obj.set('monto',0000);
	Obj.set('cobrado',0000);
	Obj.set('porcobrar',0000);
}


function LlenaMontoEq(Obj)
{
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
		Obj.set('monto',MontoRedondo);
	}	
}

function LlenaMontoEq2(Obj)
{
	ValorEq = ForModif.getComponent('nuevomonto').getValue();
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
		Obj.set('monto',MontoRedondo);
	}	
}



function CargaComboContable()
{
	var myJSONObject ={
		"oper": 'CatPlanContIng',
		"sig_cuenta":CodCuenta,
		"ano_presupuesto":Year3	
	};	
	
	ObjSon=JSON.stringify(myJSONObject);
	//alert(ObjSon);
	parametros = 'ObjSon='+ObjSon;
	Ext.Ajax.request({
	url : ruta2,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		datos = resultado.responseText;
		//alert(datos);
		ArrDatos =  datos.split('|');
		DatosNuevo1 = eval('(' + ArrDatos[0] + ')');
		DatosNuevo2 = eval('(' + ArrDatos[1] + ')');
	//	alert(ArrDatos[1]);
		if(DatosNuevo1.raiz!=null)
		{
		
		//	Ext.getCmp('ctaDeb').enable();
		//	Ext.getCmp('ctaDeb').enable();
			Ext.getCmp('anreal').enable();
			Ext.getCmp('anest').enable();
			Ext.getCmp('anpre').enable();
			DataStoreCuentaContable.loadData(DatosNuevo1);
		}	
		//alert(DatosNuevo2);
		if(DatosNuevo2)
		{
			if(DatosNuevo2.raiz!=null)
			{
			//	Ext.get('IdCuentaDebe').dom.value=DatosNuevo2.raiz[0].sc_cuenta;
			//	Ext.getCmp('ctaab').setValue(DatosNuevo2.raiz[1].denominacion);
				Ext.getCmp('ctaDeb').setValue(DatosNuevo2.raiz[0].denominacion);
				Ext.getCmp('ctaDeb').disable();
				Ext.getCmp('anreal').disable();
				Ext.getCmp('anest').disable();
				Ext.getCmp('anpre').disable();
				/*
				alert(DatosNuevo2.raiz[0].sc_cuenta);
				alert(DatosNuevo2.raiz[0].denominacion);
				alert(DatosNuevo2.raiz[1].sc_cuenta);
				alert(DatosNuevo2.raiz[1].denominacion);
				*/
			}
		}	
	}
})
}

function leerAsientos()
{	
	var myJSONObject =
	{
		"oper": 'leerAsientos',
		"sig_cuenta":CodCuenta,
		"ano_presupuesto":Year3,
		"codemp":codemp	
	};	
	
	ObjSon=JSON.stringify(myJSONObject);
	//alert(ObjSon);
	parametros = 'ObjSon='+ObjSon;
	Ext.Ajax.request
	({
		url : ruta2,
		params : parametros,
		method: 'POST',
		success: function (resultado, request) 
		{
			 datos = resultado.responseText;
			 if(datos!='')
			 { 	//alert(datos);
			 	ArrAux=datos.split('|');
			 	ObJsonAs=Ext.util.JSON.decode(ArrAux[0]);
			 	ObJsonCa=Ext.util.JSON.decode(ArrAux[1]);
			 	gridAsientoCont.store.loadData(ObJsonAs);
			 	gridCaif.store.loadData(ObJsonCa);
			 }
		}
	})
	
}


function getGridMontos(Obj,Row,col,Rec)
{
	datosRegistroActual = grid.store.getAt(Row);
	var fecha=new Date();
	Year = fecha.getFullYear();
	Year2 = Year-1;
	Year3 = Year+1;
	anreal = 'Año Real al 31/12/'+Year2;
	anpre = Year3+' Año del presupuesto';
	anest = Year+' Último año estimado';	
	anreal = 'Año Real al 31/12/'+(parseInt(Sesion.ano_presupuesto)-2);
	anpre = parseInt(Sesion.ano_presupuesto)+' Año del presupuesto';
	anest = (parseInt(Sesion.ano_presupuesto)-1)+' Último año estimado';	
		
	Tipos=
	[
		['Equitativo'],
		['Manual']
	]
	
	Years =
	[
        [Year],
        [Year2],
        [Year3]
	]	

	if(datosRegistroActual.get('montoglobal'))
	{
		auxen = parseInt(ue_formato_calculo(datosRegistroActual.get('enero')))-parseInt(ue_formato_calculo(datosRegistroActual.get('enerocob')))
		auxfe = parseInt(ue_formato_calculo(datosRegistroActual.get('febrero')))-parseInt(ue_formato_calculo(datosRegistroActual.get('febrerocob')))
		auxmar = parseInt(ue_formato_calculo(datosRegistroActual.get('marzo')))-parseInt(ue_formato_calculo(datosRegistroActual.get('marzocob')))
		auxab = parseInt(ue_formato_calculo(datosRegistroActual.get('abril')))-parseInt(ue_formato_calculo(datosRegistroActual.get('abrilcob')))
		auxmay = parseInt(ue_formato_calculo(datosRegistroActual.get('mayo')))-parseInt(ue_formato_calculo(datosRegistroActual.get('mayocob')))
		auxjun = parseInt(ue_formato_calculo(datosRegistroActual.get('junio')))-parseInt(ue_formato_calculo(datosRegistroActual.get('juniocob')))
		auxjul = parseInt(ue_formato_calculo(datosRegistroActual.get('julio')))-parseInt(ue_formato_calculo(datosRegistroActual.get('juliocob')))
		auxag = parseInt(ue_formato_calculo(datosRegistroActual.get('agosto')))-parseInt(ue_formato_calculo(datosRegistroActual.get('agostocob')))
		auxsep = parseInt(ue_formato_calculo(datosRegistroActual.get('septiembre')))-parseInt(ue_formato_calculo(datosRegistroActual.get('septiembrecob')))
		auxoc = parseInt(ue_formato_calculo(datosRegistroActual.get('octubre')))-parseInt(ue_formato_calculo(datosRegistroActual.get('octubrecob')))
		auxno = parseInt(ue_formato_calculo(datosRegistroActual.get('noviembre')))-parseInt(ue_formato_calculo(datosRegistroActual.get('noviembrecob')))
		auxdic= parseInt(ue_formato_calculo(datosRegistroActual.get('diciembre')))-parseInt(ue_formato_calculo(datosRegistroActual.get('diciembrecob')))
		auxtotal = auxen+auxfe+auxmar+auxab+auxmay+auxjun+auxjul+auxag+auxsep+auxoc+auxno+auxdic									
	}
	else
	{
		auxen = 0;
		auxfe = 0;
		auxmar = 0;
		auxab = 0;
		auxmay = 0;
		auxjun = 0;
		auxjul = 0;
		auxag = 0;
		auxsep = 0;
		auxoc = 0;
		auxno = 0;
		auxdic= 0;
		auxtotal =0;									
			
	
	}
		
		Meses =
		[
	        ['m','Enero',grid.getSelectionModel().getSelected().get('enero'),datosRegistroActual.get('enerocob'),numFormat(auxen,"2",".")],
	        ['l','Febrero',grid.getSelectionModel().getSelected().get('febrero'),datosRegistroActual.get('febrerocob'),numFormat(auxfe,"2",".")],
	        ['k','Marzo',grid.getSelectionModel().getSelected().get('marzo'),datosRegistroActual.get('marzocob'),numFormat(auxmar,"2",".")],
	        ['j','Abril',grid.getSelectionModel().getSelected().get('abril'),datosRegistroActual.get('abrilcob'),numFormat(auxab,"2",".")],
	        ['i','Mayo',grid.getSelectionModel().getSelected().get('mayo'),datosRegistroActual.get('mayocob'),numFormat(auxmay,"2",".")],
	        ['h','Junio',grid.getSelectionModel().getSelected().get('junio'),datosRegistroActual.get('juniocob'),numFormat(auxjun,"2",".")],
	        ['g','Julio',grid.getSelectionModel().getSelected().get('julio'),datosRegistroActual.get('juliocob'),numFormat(auxjul,"2",".")],
	        ['f','Agosto',grid.getSelectionModel().getSelected().get('agosto'),datosRegistroActual.get('agostocob'),numFormat(auxag,"2",".")],
	        ['e','Septiembre',grid.getSelectionModel().getSelected().get('septiembre'),datosRegistroActual.get('septiembrecob'),numFormat(auxsep,"2",".")],
	        ['d','Octubre',grid.getSelectionModel().getSelected().get('octubre'),datosRegistroActual.get('octubrecob'),numFormat(auxoc,"2",".")],
	        ['c','Noviembre',grid.getSelectionModel().getSelected().get('noviembre'),datosRegistroActual.get('noviembrecob'),numFormat(auxno,"2",".")],
	        ['b','Diciembre',grid.getSelectionModel().getSelected().get('diciembre'),datosRegistroActual.get('diciembrecob'),numFormat(auxdic,"2",".")],
	        ['a','Total',grid.getSelectionModel().getSelected().get('montoglobal'),datosRegistroActual.get('montoglobalcob'),numFormat(auxtotal,"2",".")]
		]	
//}
	CodCuenta = grid.getSelectionModel().getSelected().get('sig_cuenta');
	DenCuenta = grid.getSelectionModel().getSelected().get('denominacion');
	Montoanest = grid.getSelectionModel().getSelected().get('monto_anest');
	Montoanreal = grid.getSelectionModel().getSelected().get('monto_anreal'); 
	
	EsNuevo = grid.getSelectionModel().getSelected().get('NuevoRegistro');
	MontGlobal = grid.getSelectionModel().getSelected().get('montoglobal');
	ano_pre =  Years[0];
	Evento = 'grabarPlan'; 
	 var storeCombo = new Ext.data.SimpleStore({
        fields: ['year'],
        data : Years // from states.js
    });
    

	 var storeTipo = new Ext.data.SimpleStore({
        fields: ['tipo'],
        data : Tipos // from states.js
    });
    
	 	var DatosNuevoCont={"raiz":[{"codigo":'0000001',"denominacion":'78676789 Cuenta Numero1'}]};
		RecordDefCuentac = Ext.data.Record.create
		([
			{name: 'codigo'},// "mapping" property not needed if it's the same 
			{name: 'denominacion'}
		]);
		
			DataStoreCuentaContable =  new Ext.data.Store
			({
			proxy: new Ext.data.MemoryProxy(DatosNuevoCont),
			reader: new Ext.data.JsonReader({
			root: 'raiz',                
			id: "id"   
			}
			, 
                   RecordDefCuentac
			 ),
				   data: DatosNuevoCont
        	});
	
		  ForMontos = new Ext.form.FormPanel({
		  labelWidth:140, // label settings here cascade unless overridden,
		  labelAlign:'right',
		  border:false,
		  title: 'Información de la cuenta',
		  bodyStyle:'padding-top:5px;height:170px;background-color:#DFE8F6',
		  style:'height:210px',
		  height:250,  
		  items:[
		{
		  xtype:'textfield', 
		  fieldLabel: 'Cuenta',
		  name: 'Cuenta',
		  value:CodCuenta,
		  readOnly:true,
		  id: 'Cuenta',
		  maxLength: 40,
		  maxLengthText: 'El campo excede la longitud máxima',
		  allowBlank:false,
		  width: 120
	    }
		,
		{
		  xtype:'textfield', 
		  fieldLabel: 'Denominación',
		  name: 'dennominacion',
		  readOnly:true,
		  value:DenCuenta,
		  id: 'denom',
		  maxLength: 500,
		  maxLengthText: 'El campo excede la longitud máxima',
		  allowBlank:false,
		  width: 450
	    }
		,
		{
		  xtype:'textfield', 
		  fieldLabel:anreal,
		  name: 'Cuenta',
		  value:Montoanreal,
		  readOnly:true,
		  id: 'anreal',
		  maxLength: 120,
		  allowDecimals:false,
		  maxLengthText: 'El campo excede la longitud máxima',
		  allowBlank:false,
		  width: 120
	    }
		,
		{
		  xtype:'textfield', 
		  fieldLabel:anest,
		  name: 'anest',
		  value:Montoanest,
		  readOnly:true,
		  id: 'anest',
		  maxLength: 120,
		  maxLengthText:'El campo excede la longitud máxima',
		  allowDecimals:false,
		  width:120
	    }
	    ,
	   {
		  xtype:'numberfield', 
		  fieldLabel: anpre,
		  name: 'anpre',
		  readOnly:false,
		  value:0,
		  id: 'anpre',
		 // validateOnBlur:false,
		  baseChars:'0123456789,.',
		  maxLength: 120,
		 // labelStyle:'width:160px;text-align:right',
		  maxLengthText: 'El campo excede la longitud máxima',
		  allowBlank:false,
		  allowDecimals:false,
		  width: 120		
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
	    }	
	    ,
	    {
		  xtype:'checkbox', 
		  fieldLabel: 'Usa Cuentas por Cobrar',
		  id: 'cxc',
		}  	  
	   	]
	});
	//gridMUnaVez=true;
	Ext.getCmp('anpre').on('blur',function()
	{	
		if(!MontGlobal)
		{
			//gridVarPat.store.getAt(0).set('monto',Ext.getCmp('anpre').getValue());
			gridCaif.store.getAt(0).set('monto',Ext.getCmp('anpre').getValue());
			gridCaif.store.getAt(1).set('monto',Ext.getCmp('anpre').getValue());
			gridAsientoCont.store.getAt(0).set('monto',Ext.getCmp('anpre').getValue());
			gridAsientoCont.store.getAt(1).set('monto',Ext.getCmp('anpre').getValue());
		}
	})
	Ext.getCmp('Distri').on('select',function()
	{
	if(!MontGlobal)
	{
			if(Ext.getCmp('Distri').getValue()=='Equitativo' && Ext.getCmp('anpre').getValue()>0)
			{
				Acum=0;
				gridMontos.store.each(LlenaMontoEq);	
			}
			else if(Ext.getCmp('Distri').getValue()=='Manual' && Ext.getCmp('anpre').getValue()>0)
			{
				gridMontos.store.each(BlanquearMontoEq);
			}
	}
	})
	
//}	
//else
//{
//	ForMontos.getComponent('Cuenta').setValue(CodCuenta);
//	ForMontos.getComponent('denom').setValue(DenCuenta);		
//}

	var DatosNuevo={"raiz":[{"programatica":'',"sig_cuenta":'',"year":'',"monto":''}]};
	var storeMeses = new Ext.data.SimpleStore({
		fields: 
		[
			{name: 'numes'},
			{name: 'mes'},
			{name: 'monto'},
			{name: 'cobrado'},
			{name: 'porcobrar'}
		]
	});
			
				storeMeses.loadData(Meses);
				storeMeses.sort('numes','DESC');
			//	alert(storeMeses.getAt(0).get('numes'));
				
				gridMontos = new Ext.grid.EditorGridPanel({
	  			width:500,
	  			style:'margin-left:70px;margin-top:50px',
	  			title:'Montos Mensuales',
				autoScroll:true,
	            border:true,
	            ds:storeMeses,
	            cm: new Ext.grid.ColumnModel([
	            {header: "Mes", width:100,sortable:false,dataIndex:'mes'},
				{header: "Devengado", width: 100,dataIndex: 'monto',editor: new Ext.form.TextField({allowBlank: false,allowDecimals:false,id:'montomes'})},
				{header: "Cobrado", width: 100,dataIndex: 'cobrado',id:'colcobrado',editor: new Ext.form.TextField({allowBlank: false,allowDecimals:false,id:'combrado'})},
				{header: "Por Cobrar", width: 100,dataIndex: 'porcobrar',id:'colporcobrar',editor: new Ext.form.TextField({allowBlank: false,allowDecimals:false,id:'porcobrar',readOnly:true})}							
				]),
				selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
	              viewConfig:{
	              forceFit:true	
	             }
	            ,
				autoHeight:true,
				stripeRows: true
				 });
 			
		  		gridMontos.getView().getRowClass = function(record, index){
		  		if(record.data.mes=='Total')
		  		{
		  			return 'Total';
		  		}	
    		}
    	gridMontos.getColumnModel().getColumnById('colcobrado').hidden=true;
    	gridMontos.getColumnModel().getColumnById('colporcobrar').hidden=true;
    	Ext.getCmp('cxc').on('check',function(){
    		if(Ext.getCmp('cxc').getValue()==true)
    		{
    			gridMontos.getColumnModel().setHidden(2,false);
    			gridMontos.getColumnModel().setHidden(3,false);
    		}
    		else
    		{
	 			gridMontos.getColumnModel().setHidden(2,true);
    			gridMontos.getColumnModel().setHidden(3,true);
    		}
    	})	
    	


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
			proxy: new Ext.data.MemoryProxy(DatosNuevoCont)
			,reader: new Ext.data.JsonReader({
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
  	width:500,
  	style:'margin-left:70px',
	autoScroll:true,
	title:'Asiento Contable',
    border:true,
    ds:DataStoreAsientoCont,
    cm: new Ext.grid.ColumnModel([
    {header: "Código", width: 40, sortable: true,   dataIndex: 'codigo'},
	{header: "Denominación", width: 130, sortable: true, dataIndex: 'denominacion'},
	{header: "Operación", width: 40, sortable: true, dataIndex: 'operacion'},
	{header: "Monto", width: 80, sortable: true, dataIndex: 'monto'}							
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
		if(gridAsientoCont.getSelectionModel().getSelected().get('operacion')=='Debe')
		{
			CatalogoCuentasContables();
		}
	});	
	
	
//definir el asiento de variacion patrimonial.

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
  	width:500,
  	style:'margin-left:70px',
	autoScroll:true,
	title:'Cuentas del formato CAIF que se afectan',
    border:true,
    ds:DataStoreCaif,
    cm: new Ext.grid.ColumnModel([
    {header: "Código", width: 40, sortable: true,   dataIndex: 'codigo'},
	{header: "Denominación", width: 130, sortable: true, dataIndex: 'denominacion'},
	{header: "Monto", width: 80, sortable: true, dataIndex: 'monto'}							
	]),
	selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
    viewConfig: {
    forceFit:true	
    },
	autoHeight:true,
	stripeRows: true
 	});
	
		//if(winMontos=="")
		//{
				   winMontos = new Ext.Window(
                   {
                    layout:'fit',
                    title: 'Montos',
		    		autoScroll:true,
                    width:650,
                    height:500,
                    closable:false,
                    modal: true,
                    closeAction:'hide',
                    plain: false,
                    items:[ForMontos,gridMontos,gridAsientoCont,gridCaif],
                    buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {
                     	if(!MontGlobal)
						{
							if(GrabarPlanCuenta()==true)
		      				{
		      					GrabarCuenta();
			      				winMontos.destroy();
			      				ForMontos.destroy();
			      				gridMontos.destroy();
			      				gridAsientoCont.destroy();
		      				}		
						}
						else
						{
							winMontos.destroy();
			      			ForMontos.destroy();
			      			gridMontos.destroy();
			      			gridAsientoCont.destroy();
						}
		      		 }
                    }
                    ,
                    {
                     text: 'Salir',
                     handler: function()
                     {
                      		winMontos.destroy();
		      				ForMontos.destroy();
		      				gridMontos.destroy();
		      				gridAsientoCont.destroy();
                     }
                    }
                    ]
                   });
              // }  
                  winMontos.show();
                
	if(MontGlobal && datosRegistroActual.get('NuevoRegistro')!=true)
	{
		//alert(grid.getSelectionModel().getSelected().get('montoglobal'));
		// que cargan los datos de los asientos que ya se generaron para esta cuenta
		leerAsientos();
		Ext.getCmp('anreal').disable();
		Ext.getCmp('anest').disable();
		Ext.getCmp('anpre').disable();
		Ext.getCmp('cxc').disable();
		Ext.getCmp('montomes').disable();
		Ext.getCmp('anreal').setValue(ue_formato_operaciones(grid.getSelectionModel().getSelected().get('montoanoanterior')));
		Ext.getCmp('anest').setValue(ue_formato_operaciones(grid.getSelectionModel().getSelected().get('montoanoactual')));
		Ext.getCmp('anpre').setValue(ue_formato_operaciones(grid.getSelectionModel().getSelected().get('montoglobal')));
		if(datosRegistroActual.get('cuentaporcobrar')!='')
		{
			 gridMontos.getColumnModel().setHidden(2,false);
    		 gridMontos.getColumnModel().setHidden(3,false);		
		}
		
		Evento = 'ActualizarPlan';
	}
	//alert(datosRegistroActual.get('RegistroNuevo'));
	else 
	{
		//Se cargan los datos de los asientos que se van a generar 
		//tomando en cuenta la tabla de cuentas asociadas y el plan general de cuentas integrado
		Ext.getCmp('anreal').enable();
		Ext.getCmp('anest').enable();
		Ext.getCmp('anpre').enable();
		Ext.getCmp('montomes').enable();	
		if(datosRegistroActual.get('montoanoanterior'))
		{
			Ext.getCmp('anreal').setValue(ue_formato_operaciones(datosRegistroActual.get('montoanoanterior')));
		}
	
		if(datosRegistroActual.get('montoanoactual'))
		{
			Ext.getCmp('anest').setValue(ue_formato_operaciones(datosRegistroActual.get('montoanoactual')));
		}
		if(datosRegistroActual.get('montoglobal'))
		{
			Ext.getCmp('anpre').setValue(ue_formato_operaciones(datosRegistroActual.get('montoglobal')));	
		}
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
		}

		if(datosRegistroActual.get('codvarhaber')!='')
		{
		     var p4 = new RecordDefVarPat
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
		gridCaif.store.insert(0,p3);
		gridCaif.store.insert(1,p4);	
	}
	    	gridMontos.on('afteredit',function(Obj){	    	
	    	TotalMonto = getTotalMonto();
	    	TotalPorCobrar = getTotalPorCobrar();
	    	TotalCobrado = getTotalCobrado();
	    	Rec = Obj.record;
	    	Campo = Obj.field;
	    	if(Campo=='monto')
	    	{
	    		if(gridMontos.store.getAt(12).get('porcobrar'))
	    		{
	    			AuxsaldoPorCobrar  = parseInt(gridMontos.store.getAt(12).get('porcobrar'));
	    		}
	    		else
	    		{
	    			AuxsaldoPorCobrar = 0;
	    		}
		    	SaldoPorCobrar = AuxsaldoPorCobrar+parseInt(Rec.get('monto')); 
		    	Rec.set('porcobrar',SaldoPorCobrar);
		    	Rec.set('cobrado',00);
	    	}
	    	
	    	if(Campo=='cobrado')
	    	{
	    		if(parseInt(gridMontos.store.getAt(12).get('porcobrar'))>0)
	    		{
	    			SaldoPorCobrar = parseInt(gridMontos.store.getAt(12).get('porcobrar'))-parseInt(Rec.get('cobrado'));
	    		}
	    		else
	    		{
		    		 Ext.Msg.alert('Mensaje','El saldo por Cobrar es 0');
		    		 return false;
		    	}
		    	Rec.set('porcobrar',SaldoPorCobrar);  
		    	gridMontos.store.getAt(12).set('cobrado',TotalCobrado);
	    	}
	    	gridMontos.store.getAt(12).set('porcobrar',SaldoPorCobrar);
 		})    
}

function getTotalMonto()
{
	Ar = gridMontos.store.getRange(0,11);
	AcuMontoAux=0;
	for(i=0;i<Ar.length;i++)
	{
		if(Ar[i].get('monto')!='')
		{
			AcuMontoAux+=parseInt(ue_formato_operaciones(Ar[i].get('monto')));	
		} 	
	}	
	gridMontos.store.getAt(12).set('monto',AcuMontoAux);
	return AcuMontoAux;
}

function getTotalPorCobrar()
{
	Ar = gridMontos.store.getRange(0,11);
	AcuMontoAux=0;
	for(i=0;i<Ar.length;i++)
	{
		if(Ar[i].get('porcobrar')!='')
		{
			AcuMontoAux +=parseInt(ue_formato_operaciones(Ar[i].get('porcobrar')));	
		}
	} 		
	return AcuMontoAux;
}

function getTotalCobrado()
{
	Ar = gridMontos.store.getRange(0,11);
	AcuMontoAux=0;
	for(i=0;i<Ar.length;i++)
	{
		if(Ar[i].get('cobrado')!='')
		{
			AcuMontoAux +=parseInt(ue_formato_operaciones(Ar[i].get('cobrado')));	
		}
	} 		
	return AcuMontoAux;
}


function getActualizar(Obj,Row,col,Rec)
{
		datosRegistroActual = grid.getSelectionModel().getSelected();
		Tipos=
		[
			['Equitativo'],
			['Manual']
		]				
		var DatosNuevoCont={"raiz":[{"codigo":'0000001',"denominacion":'78676789 Cuenta Numero1'}]};
		
		auxen = parseInt(ue_formato_calculo(datosRegistroActual.get('enero')))-parseInt(ue_formato_calculo(datosRegistroActual.get('enerocob')))
		auxfe = parseInt(datosRegistroActual.get('febrero'))-parseInt(datosRegistroActual.get('febrerocob'))
		auxmar = parseInt(datosRegistroActual.get('marzo'))-parseInt(datosRegistroActual.get('marzocob'))
		auxab = parseInt(datosRegistroActual.get('abril'))-parseInt(datosRegistroActual.get('abrilcob'))
		auxmay = parseInt(datosRegistroActual.get('mayo'))-parseInt(datosRegistroActual.get('mayocob'))
		auxjun = parseInt(datosRegistroActual.get('junio'))-parseInt(datosRegistroActual.get('juniocob'))
		auxjul = parseInt(datosRegistroActual.get('julio'))-parseInt(datosRegistroActual.get('juliocob'))
		auxag = parseInt(datosRegistroActual.get('agosto'))-parseInt(datosRegistroActual.get('agostocob'))
		auxsep = parseInt(datosRegistroActual.get('septiembre'))-parseInt(datosRegistroActual.get('septiembrecob'))
		auxoc = parseInt(datosRegistroActual.get('octubre'))-parseInt(datosRegistroActual.get('octubrecob'))
		auxno = parseInt(datosRegistroActual.get('noviembre'))-parseInt(datosRegistroActual.get('noviembrecob'))
		auxdic= parseInt(datosRegistroActual.get('diciembre'))-parseInt(datosRegistroActual.get('diciembrecob'))
		auxtotal = auxen+auxfe+auxmar+auxab+auxmay+auxjun+auxjul+auxag+auxsep+auxoc+auxno+auxdic									


		Meses =
		[
	        ['m','Enero',grid.getSelectionModel().getSelected().get('enero'),datosRegistroActual.get('enerocob'),numFormat(auxen,"2",".")],
	        ['l','Febrero',grid.getSelectionModel().getSelected().get('febrero'),datosRegistroActual.get('febrerocob'),numFormat(auxfe,"2",".")],
	        ['k','Marzo',grid.getSelectionModel().getSelected().get('marzo'),datosRegistroActual.get('marzocob'),numFormat(auxmar,"2",".")],
	        ['j','Abril',grid.getSelectionModel().getSelected().get('abril'),datosRegistroActual.get('abrilcob'),numFormat(auxab,"2",".")],
	        ['i','Mayo',grid.getSelectionModel().getSelected().get('mayo'),datosRegistroActual.get('mayocob'),numFormat(auxmay,"2",".")],
	        ['h','Junio',grid.getSelectionModel().getSelected().get('junio'),datosRegistroActual.get('juniocob'),numFormat(auxjun,"2",".")],
	        ['g','Julio',grid.getSelectionModel().getSelected().get('julio'),datosRegistroActual.get('juliocob'),numFormat(auxjul,"2",".")],
	        ['f','Agosto',grid.getSelectionModel().getSelected().get('agosto'),datosRegistroActual.get('agostocob'),numFormat(auxag,"2",".")],
	        ['e','Septiembre',grid.getSelectionModel().getSelected().get('septiembre'),datosRegistroActual.get('septiembrecob'),numFormat(auxsep,"2",".")],
	        ['d','Octubre',grid.getSelectionModel().getSelected().get('octubre'),datosRegistroActual.get('octubrecob'),numFormat(auxoc,"2",".")],
	        ['c','Noviembre',grid.getSelectionModel().getSelected().get('noviembre'),datosRegistroActual.get('noviembrecob'),numFormat(auxno,"2",".")],
	        ['b','Diciembre',grid.getSelectionModel().getSelected().get('diciembre'),datosRegistroActual.get('diciembrecob'),numFormat(auxdic,"2",".")],
	        ['a','Total',grid.getSelectionModel().getSelected().get('montoglobal'),datosRegistroActual.get('montoglobalcob'),numFormat(auxtotal,"2",".")]
		]	
		
		var storeMeses = new Ext.data.SimpleStore({
			fields: 
			[
				{name: 'numes'},
				{name: 'mes'},
				{name: 'monto'},
				{name: 'cobrado'},
				{name: 'porcobrar'}
			]
		});
			
				storeMeses.loadData(Meses);
				storeMeses.sort('numes','DESC');	
				
				gridMontos = new Ext.grid.EditorGridPanel({
	  			width:400,
	  			style:'margin-left:60px',
	  			title:'Montos Mensuales',
				autoScroll:true,
	            border:true,
	            ds:storeMeses,
	            cm: new Ext.grid.ColumnModel([
	            {header: "Mes", width:40,sortable:false,dataIndex:'mes'},
				{header: "Monto Devengado", width: 50,dataIndex: 'monto',editor: new Ext.form.TextField({allowBlank: false,allowDecimals:false,id:'montomes'})},
				{header: "Monto Cobrado", width: 50,dataIndex: 'cobrado',editor: new Ext.form.TextField({allowBlank: false,allowDecimals:false,id:'combrado'})},
				{header: "Monto Por Cobrar", width: 50,dataIndex: 'porcobrar',editor: new Ext.form.TextField({allowBlank: false,allowDecimals:false,id:'porcobrar',readOnly:true})}							
				]),
				selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
	              viewConfig: {
	             forceFit:true	
	             }
	            ,
				autoHeight:true,
				stripeRows: true
				 });
 			
		  		Ext.getCmp('montomes').on('blur',function(Obj)
		  		{	
		  			Ar = gridMontos.store.getRange(0,11);
		  			AcuMontoAux=0;
		  			for(i=0;i<Ar.length;i++)
		  			{
		  				AcuMontoAux +=parseInt(ue_formato_operaciones(Ar[i].get('monto')));	
		  			}
			  		gridMontos.store.getAt(12).set('monto',AcuMontoAux);
			  		
			  		
			  		if(grid.getSelectionModel().getSelected().get('cuentaporcobrar')!='')
	    			{
				  		gridAsientoCont.store.getAt(2).set('monto',AcuMontoAux);
				  		gridCaif.store.getAt(2).set('monto',AcuMontoAux);
			  		}
			  		else
			  		{
			  			gridAsientoCont.store.each(function(rec){
			  				rec.set('monto',AcuMontoAux);
			  			})
			  			
			  			gridCaif.store.each(function(rec){
			  				rec.set('monto',AcuMontoAux);
			  			})
			  		}
		  		})
		  		gridMontos.getView().getRowClass = function(record, index){
		  		if(record.data.mes=='Total')
		  		{
		  			return 'Total';
		  		}
			 }
		var storeTipo = new Ext.data.SimpleStore({
	        fields: ['tipo'],
	        data : Tipos // from states.js
    	});

		datosRegistroActual = grid.store.getAt(Row);
		CodCuenta = grid.getSelectionModel().getSelected().get('sig_cuenta');
		DenCuenta = grid.getSelectionModel().getSelected().get('denominacion');
		Montoanest = grid.getSelectionModel().getSelected().get('monto_anest');
		Distribuido = grid.getSelectionModel().getSelected().get('distribuido'); 
		EsNuevo = grid.getSelectionModel().getSelected().get('NuevoRegistro');
		MontGlobal = grid.getSelectionModel().getSelected().get('montoglobal');

		  ForModif = new Ext.form.FormPanel({
		  labelWidth:140, // label settings here cascade unless overridden,
		  labelAlign:'right',
		  border:false,
		  title: 'Modificar Montos',
		  bodyStyle:'padding-top:5px;height:170px;background-color:#DFE8F6',
		  style:'height:210px',
		  height:250,  
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
		  fieldLabel: "Monto Programado",
		  name: 'monpres',
		  readOnly:false,
		  value:MontGlobal,
		  id: 'monpres',
		 // validateOnBlur:false,
		  baseChars:'0123456789,.',
		  maxLength: 120,
		 // labelStyle:'width:160px;text-align:right',
		  maxLengthText: 'El campo excede la longitud máxima',
		  allowBlank:false,
		  allowDecimals:false,
		  width: 120		
		}
		,
	   {
		  xtype:'textfield', 
		  fieldLabel: "Monto Distribuido",
		  name: 'montodistri',
		  readOnly:false,
		  value:Distribuido,
		  id: 'mondist',
		  baseChars:'0123456789,.',
		  maxLength: 120,
		  maxLengthText: 'El campo excede la longitud máxima',
		  allowBlank:false,
		  allowDecimals:false,
		  width: 120		
		}
		,
	   {
		  xtype:'numberfield', 
		  fieldLabel: "Nuevo Monto",
		  name: 'nuevomonto',
		  readOnly:false,
		  value:0,
		  id: 'nuevomonto',
		 // validateOnBlur:false,
		  baseChars:'0123456789,.',
		  maxLength: 120,
		 // labelStyle:'width:160px;text-align:right',
		  maxLengthText: 'El campo excede la longitud máxima',
		  allowBlank:false,
		  allowDecimals:false,
		  width: 120		
		}
		,
	    {
		  xtype:'combo',
		  editable:true, 
		  store : storeTipo,
		  displayField:'tipo',
		  fieldLabel: 'Distribución',
		  name: 'Dist',
		  typeAhead: true,
		  triggerAction: 'all',
		  id:'Distri',
	      mode:'local'
	    }	  	  
	]
	});
	
		Ext.getCmp('nuevomonto').on('blur',function()
		{	
			if(grid.getSelectionModel().getSelected().get('cuentaporcobrar')=='')
	    	{
				gridCaif.store.getAt(0).set('monto',Ext.getCmp('nuevomonto').getValue());
				if(gridCaif.store.getAt(1))
				{
					gridCaif.store.getAt(1).set('monto',Ext.getCmp('nuevomonto').getValue());	
				}
				gridAsientoCont.store.getAt(0).set('monto',Ext.getCmp('nuevomonto').getValue());
				gridAsientoCont.store.getAt(1).set('monto',Ext.getCmp('nuevomonto').getValue());
			}
		})
				
				
		Ext.getCmp('Distri').on('select',function()
		{
			if(Ext.getCmp('Distri').getValue()=='Equitativo' && Ext.getCmp('nuevomonto').getValue()>0)
			{
				Acum=0;
				gridMontos.store.each(LlenaMontoEq2);	
			}
			else if(Ext.getCmp('Distri').getValue()=='Manual' && Ext.getCmp('nuevomonto').getValue()>0)
			{
				gridMontos.store.each(BlanquearMontoEq);
			}
		})
	
	
	
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
			proxy: new Ext.data.MemoryProxy(DatosNuevoCont)
			,reader: new Ext.data.JsonReader({
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
  	width:500,
  	style:'margin-left:20px',
	autoScroll:true,
	title:'Asiento Contable',
    border:true,
    ds:DataStoreAsientoCont,
    cm: new Ext.grid.ColumnModel([
    {header: "Código", width: 40, sortable: true,   dataIndex: 'codigo'},
	{header: "Denominación", width: 130, sortable: true, dataIndex: 'denominacion'},
	{header: "Operación", width: 40, sortable: true, dataIndex: 'operacion'},
	{header: "Monto", width: 80, sortable: true, dataIndex: 'monto'}							
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
		if(gridAsientoCont.getSelectionModel().getSelected().get('operacion')=='Debe')
		{
			CatalogoCuentasContables();
		}
	});	
	
	
//definir el asiento de variacion patrimonial.

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
  	width:500,
  	style:'margin-left:20px',
	autoScroll:true,
	title:'Cuentas del formato CAIF que se afectan',
    border:true,
    ds:DataStoreCaif,
    cm: new Ext.grid.ColumnModel([
    {header: "Código", width: 40, sortable: true,   dataIndex: 'codigo'},
	{header: "Denominación", width: 130, sortable: true, dataIndex: 'denominacion'},
	{header: "Monto", width: 80, sortable: true, dataIndex: 'monto'}							
	]),
	selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
    viewConfig: {
    forceFit:true	
    },
	autoHeight:true,
	stripeRows: true
 	});
			
				winModif = new Ext.Window(
                {
                    layout:'fit',
                    title: 'Montos',
		    		autoScroll:true,
                    width:550,
                    height:500,
                    closable:false,
                    modal: true,
                    closeAction:'hide',
                    plain: false,
                    items:[ForModif,gridMontos,gridAsientoCont,gridCaif],
                    buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {
                     	MontoAnterior = parseInt(ue_formato_calculo(Ext.getCmp('monpres').getValue()));
                     	MontoNuevo = parseInt(ue_formato_calculo(Ext.getCmp('nuevomonto').getValue()));
                     	if(MontoAnterior>0 && MontoNuevo>0)
                     	{
                     		//if(MontoNuevo>MontoAnterior)
                     		//{
                     			MontosMeses = gridMontos.store.getRange(0,11);
                     			ModifMonto(MontoNuevo,MontosMeses);		
                     			ForModif.destroy();
			      				winModif.destroy();	
                     		//}
                     		//else
                     		//{
                     		//	Ext.MessageBox.alert('Mensaje', 'El nuevo monto debe ser mayor al monto  programado');	
                     		//	return false;
                     		//}
                     	} 
                     	else
                     	{
                     			Ext.MessageBox.alert('Mensaje', 'Debe llenar los campos nuevo monto y monto anterior');	
                     			return false;
                     	}
                     	return false;
		      		 }
                    }
                    ,
                    {
	                     text: 'Salir',
	                     handler: function()
	                     {
			      				ForModif.destroy();
			      				winModif.destroy();
	                     }
                    }
                    ]
                   });
       leerAsientos();
       winModif.show();
	
	Ext.getCmp('Distri').on('select',function()
	{
		if(!MontGlobal)
		{
				if(Ext.getCmp('Distri').getValue()=='Equitativo' && Ext.getCmp('anpre').getValue()>0)
				{
					Acum=0;
					gridMontos.store.each(LlenaMontoEq);	
				}
				else if(Ext.getCmp('Distri').getValue()=='Manual' && Ext.getCmp('anpre').getValue()>0)
				{
					gridMontos.store.each(BlanquearMontoEq);
				}
		}
	})
		    gridMontos.on('afteredit',function(Obj){	    	
	    	TotalMonto = getTotalMonto();
	    	TotalPorCobrar = getTotalPorCobrar();
	    	TotalCobrado = getTotalCobrado();
	    	Rec = Obj.record;
	    	Campo = Obj.field;
	    	if(Campo=='monto')
	    	{
	    		if(gridMontos.store.getAt(12).get('porcobrar'))
	    		{
	    			AuxsaldoPorCobrar  = parseInt(gridMontos.store.getAt(12).get('porcobrar'));
	    		}
	    		else
	    		{
	    			AuxsaldoPorCobrar = 0;
	    		}
		    	SaldoPorCobrar = AuxsaldoPorCobrar+parseInt(Rec.get('monto')); 
		    	Rec.set('porcobrar',SaldoPorCobrar);
		    	Rec.set('cobrado',00);
	    	}
	    	if(Campo=='cobrado')
	    	{
	    		if(parseInt(gridMontos.store.getAt(12).get('porcobrar'))>0)
	    		{
	    			SaldoPorCobrar = parseInt(gridMontos.store.getAt(12).get('porcobrar'))-parseInt(Rec.get('cobrado'));
	    		}
	    		else
	    		{
		    		 Ext.Msg.alert('Mensaje','El saldo por Cobrar es 0');
		    		 return false;
		    	}
		    	Rec.set('porcobrar',SaldoPorCobrar);  
		    	gridMontos.store.getAt(12).set('cobrado',TotalCobrado);
		    	if(grid.getSelectionModel().getSelected().get('cuentaporcobrar')!='')
	    		{
			    	gridAsientoCont.store.getAt(1).set('monto',TotalCobrado);
			    	gridCaif.store.getAt(1).set('monto',TotalCobrado);
		    	}
	    	}	
	    	gridMontos.store.getAt(12).set('porcobrar',SaldoPorCobrar);
	    	if(grid.getSelectionModel().getSelected().get('cuentaporcobrar')!='')
	    	{
		    	gridAsientoCont.store.getAt(0).set('monto',SaldoPorCobrar);
		    	gridCaif.store.getAt(0).set('monto',SaldoPorCobrar);
	    	}
 		})  

		if(grid.getSelectionModel().getSelected().get('cuentaporcobrar')=='')
		{
			 gridMontos.getColumnModel().setHidden(2,true);
    		 gridMontos.getColumnModel().setHidden(3,true);		
		}
		else
		{
			 gridMontos.getColumnModel().setHidden(2,false);
    		 gridMontos.getColumnModel().setHidden(3,false);	
		}
}

function GrabarPlanCuenta()
{
if(!MontGlobal)
{		
		RegistroSel=datosRegistroActual;
		CuentaDebe = gridAsientoCont.store.getAt(0).get('codigo');
		montoanoanterior=Ext.get('anreal').dom.value;
		montoanoactual=Ext.get('anest').dom.value;
		montopres=parseInt(Ext.get('anpre').dom.value);
		CuentaHaber = datosRegistroActual.get('codigohaber');
		Anno = Year3;
		Cuenta = ForMontos.getComponent('Cuenta').getValue();
		MontosMes = parseInt(gridMontos.store.getAt(12).get('monto'));
		MontosMeses = gridMontos.store.getRange(0,11);
		if(MontosMes>0 && montopres>0)
		{
			if(MontosMes==montopres)
			{
				RegistroSel.set('montoanoanterior',montoanoanterior);
				RegistroSel.set('montoanoactual',montoanoactual);
				datosRegistroActual.set('montoglobal',montopres);
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
				RegistroSel.set('enerocob',MontosMeses[0].get("cobrado"));
				RegistroSel.set('febrerocob',MontosMeses[1].get("cobrado"));
				RegistroSel.set('marzocob',MontosMeses[2].get("cobrado"));
				RegistroSel.set('abrilcob',MontosMeses[3].get("cobrado"));
				RegistroSel.set('mayocob',MontosMeses[4].get("cobrado"));
				RegistroSel.set('juniocob',MontosMeses[5].get("cobrado"));
				RegistroSel.set('juliocob',MontosMeses[6].get("cobrado"));
				RegistroSel.set('agostocob',MontosMeses[7].get("cobrado"));
				RegistroSel.set('septiembrecob',MontosMeses[8].get("cobrado"));
				RegistroSel.set('octubrecob',MontosMeses[9].get("cobrado"));
				RegistroSel.set('noviembrecob',MontosMeses[10].get("cobrado"));
				RegistroSel.set('diciembrecob',MontosMeses[11].get("cobrado"));
				RegistroSel.set('NuevoRegistro',true);
				RegistroSel.set('montoanpre',montopres);
				RegistroSel.set('montoanreal',montoanoanterior);
				RegistroSel.set('montoanant',montoanoactual);
				RegistroSel.set('codigodebe',CuentaDebe);
				RegistroSel.set('codigohaber',CuentaHaber);
				return true;
			}
			else
			{
				Ext.MessageBox.alert('Mensaje','El monto del año del presupuesto no coincide con la sumatoria de los montos mensuales');
				return false;				
			}
	}
	else
	{
		Ext.MessageBox.alert('Mensaje','No se introdujeron montos');
		return false;
	}
	return true;
}
}

function CatalogoCuentasContables()
{
			GridActual='1';
			var DatosNuevo={"raiz":[{"codigo":'',"denominacion":''}]};
			RecordDef = Ext.data.Record.create([
			{name:'codigo'},
			{name:'denominacion'},
			{name:'codvardebe'},
			{name:'desvardebe'},
			{name:'codvarhaber'},
			{name:'desvarhaber'}
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
			
			if(gridCatCuentasCon=='')
			{
				gridCatCuentasCon = new Ext.grid.EditorGridPanel({
				width:780,
				height:200,
				id:'sc_cuenta',
				autoScroll:true,
	            border:true,
	            ds:DataStore,
	            cm: new Ext.grid.ColumnModel([
	            new Ext.grid.CheckboxSelectionModel(), 
	            {header: "Código", width: 50, sortable: true, dataIndex: 'codigo'},
	            {header: "Denominación", width:250, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.TextField({allowBlank: false})}
	           ])
	           ,
				sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
	                        viewConfig:{
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
                     	   AuxSele = gridCatCuentasCon.getSelectionModel().getSelections()  	
                     		for(i=0;i<AuxSele.length;i++)
                     		{
	                     		if(i==0)
	                     		{
				                   gridAsientoCont.getSelectionModel().getSelected().set('codigo',AuxSele[i].get('codigo'));
				                   gridAsientoCont.getSelectionModel().getSelected().set('denominacion',AuxSele[i].get('denominacion'));			                   
				                   gridAsientoCont.getSelectionModel().getSelected().set('monto',gridMontos.store.getAt(12).get('cobrado'));
								   
								   		
				                   	gridCaif.store.getAt(0).set('codigo',AuxSele[i].get('codvardebe'));
				                   	gridCaif.store.getAt(0).set('denominacion',AuxSele[i].get('desvardebe'));
				                  	gridCaif.store.getAt(0).set('monto',gridMontos.store.getAt(12).get('cobrado')); 
				      			}
				      			else
				      			{
									var p = new RecordDefAsientoCont
									(
									    {
										    codigo:AuxSele[i].get('codigo'),
										    denominacion:AuxSele[i].get('denominacion'),
											operacion:'Debe',
											monto:gridMontos.store.getAt(12).get('porcobrar')	
										}
								    );
									gridAsientoCont.store.insert(1,p);
									codcuenxcobrar=AuxSele[i].get('codigo');
									var p = new RecordDefCaif
									(
									    {
										    codigo:AuxSele[i].get('codvardebe'),
										    denominacion:AuxSele[i].get('desvardebe'),
											monto:gridMontos.store.getAt(12).get('porcobrar')	
										}
								    );
									gridCaif.store.insert(1,p);
				      			}
			      			}
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

function irQuitar()
{
	var selectedKeys = grid.selModel.selections.keys;
        if(selectedKeys.length > 0) {
            Ext.Msg.confirm('Mensaje','Realmente desea eliminar el registro?', deleteRecord);
        } else {
            Ext.Msg.alert('Mensaje','Seleccione un registro para eliminar');
        }

}
//);

 function deleteRecord(btn) 
 {
	  if (btn=='yes') 
	  {
		var selectedRow = grid.getSelectionModel().getSelected();
		if(selectedRow)
		{
					Cuenta = grid.getSelectionModel().getSelected().get('sig_cuenta');
					Anno = grid.getSelectionModel().getSelected().get('ano_presupuesto');
					reg = "{'oper':'eliminarPlan','ano_presupuesto':'"+Anno+"','sig_cuenta':'" + Cuenta +"','codemp':'0001'}";
 					Obj= eval('(' + reg + ')');
					ObjSon=JSON.stringify(Obj);
					parametros = 'ObjSon='+ObjSon; 
					Ext.Ajax.request({
					url : ruta2,
					params : parametros,
					method: 'POST',
					success: function ( resultad, request ){ 
				    datos = resultad.responseText;
				   // alert(datos);
					var Registros = datos.split("|");
					Cod = Registros[1];		
						if(Cod=='1')
						{
							Ext.MessageBox.alert('Mensaje', 'Registro eliminado con éxito');
							grid.store.commitChanges();
							//alert(grid2.store.getCount());
							ActualizarData();
						}
						else if(Cod=='-1')
						{
							Ext.MessageBox.alert('Mensaje', 'La cuenta '+Cuenta+' no se puede eliminar debido a que esta siendo usada como fuente de financiamiento en la programación del gasto');
						}
						else
						{
							Ext.MessageBox.alert('Error', 'No se relizo la operacion');				
						}
				      },
					failure: function ( result, request)
					 { 
						Ext.MessageBox.alert('Error', result.responseText); 
					 } 
				    });
			
	     		//DataStore.remove(selectedRow);
		}
	  } 

}


 function ModifMonto(montonuevo,Registro) 
 {
 	if(!Registro[0].get('enerocob'))
	{
		Registro[0].set('enerocob',0);
	}
	if(!Registro[1].get('febrerocob'))
	{
		Registro[1].set('febrerocob',0);
	}
	if(!Registro[2].get('marzocob'))
	{
		Registro[2].set('marzocob',0);
	}
	if(!Registro[3].get('abrilcob'))
	{
		Registro[3].set('abrilcob',0);
	}
	if(!Registro[4].get('mayocob'))
	{
		Registro[4].set('mayocob',0);
	}
	if(!Registro[5].get('juniocob'))
	{
		Registro[5].set('juniocob',0);
	}
	if(!Registro[6].get('juliocob'))
	{
		Registro[6].set('juliocob',0);
	}
	if(!Registro[7].get('agostocob'))
	{
		Registro[7].set('agostocob',0);
	}
	if(!Registro[8].get('septiembrecob'))
	{
		Registro[8].set('septiembrecob',0);
	}
	if(!Registro[9].get('octubrecob'))
	{
		Registro[9].set('octubrecob',0);
	}
	if(!Registro[10].get('noviembrecob'))
	{
		Registro[10].set('noviembrecob',0);
	}
	if(!Registro[11].get('diciembrecob'))
	{
		Registro[11].set('diciembrecob',0);
	}
	
		var selectedRow = grid.getSelectionModel().getSelected();
		if(selectedRow)
		{
			Cuenta = grid.getSelectionModel().getSelected().get('sig_cuenta');
			Anno = grid.getSelectionModel().getSelected().get('ano_presupuesto');
			reg = "{'oper':'modificarPlan','ano_presupuesto':'"+Anno+"','sig_cuenta':'" + Cuenta +"','codemp':'0001','monto':'"+montonuevo+"','enero':'"+ ue_formato_calculo(Registro[0].get('monto')) + "','febrero':'"+ ue_formato_calculo(Registro[1].get('monto')) +"','marzo':'"+ ue_formato_calculo(Registro[2].get('monto'))+"','abril':'"+ ue_formato_calculo(Registro[3].get('monto'))+"','mayo':'"+ ue_formato_calculo(Registro[4].get('monto'))+"','junio':'"+ ue_formato_calculo(Registro[5].get('monto'))+"','julio':'"+ ue_formato_calculo(Registro[6].get('monto'))+"','agosto':'"+ ue_formato_calculo(Registro[7].get('monto'))+"','septiembre':'"+ ue_formato_calculo(Registro[8].get('monto'))+"','octubre':'"+ ue_formato_calculo(Registro[9].get('monto'))+"','noviembre':'"+ ue_formato_calculo(Registro[10].get('monto'))+"','diciembre':'"+ ue_formato_calculo(Registro[11].get('monto'))+"'";	
			reg=reg+",'enerocob':'"+ ue_formato_calculo(Registro[0].get('enerocob'))+"','febrerocob':'"+ue_formato_calculo(Registro[1].get('febrerocob'))+"','marzocob':'"+ ue_formato_calculo(Registro[2].get('marzocob'))+"','abrilcob':'"+ ue_formato_calculo(Registro[3].get('abrilcob'))+"','mayocob':'"+ ue_formato_calculo(Registro[4].get('mayocob'))+"','juniocob':'"+ ue_formato_calculo(Registro[5].get('juniocob'))+"',";
			reg=reg+"'juliocob':'"+ ue_formato_calculo(Registro[6].get('juliocob'))+"','agostocob':'"+ ue_formato_calculo(Registro[7].get('agostocob'))+"','septiembrecob':'"+ ue_formato_calculo(Registro[8].get('septiembrecob'))+"','octubrecob':'"+ ue_formato_calculo(Registro[9].get('octubrecob'))+"','noviembrecob':'"+ ue_formato_calculo(Registro[10].get('noviembrecob'))+"','diciembrecob':'"+ ue_formato_calculo(Registro[11].get('diciembrecob'))+"'";
			cantregcon = gridAsientoCont.store.getCount()-1;
			arrregcont = gridAsientoCont.store.getRange(0,cantregcon);
			reg=reg+",'movimientocon':[";
			for(i=0;i<arrregcont.length;i++)
			{
				if(i==0)
				{
					reg=reg+"{'monto':'"+ue_formato_calculo(arrregcont[i].get('monto'))+"','sc_cuenta':'"+arrregcont[i].get('codigo')+"'}";	
				}
				else
				{
					reg=reg+",{'monto':'"+ue_formato_calculo(arrregcont[i].get('monto'))+"','sc_cuenta':'"+arrregcont[i].get('codigo')+"'}";
				}
			}				
			reg=reg+"]";
			cantregcaif = gridAsientoCont.store.getCount()-1;
			arrregcaif = gridAsientoCont.store.getRange(0,cantregcon);
			reg=reg+",'movimientocaif':[";
			for(i=0;i<arrregcaif.length;i++)
			{
				if(i==0)
				{
					reg=reg+"{'monto':'"+ue_formato_calculo(arrregcaif[i].get('monto'))+"','sig_cuenta':'"+arrregcaif[i].get('codigo')+"'}";	
				}
				else
				{
					reg=reg+",{'monto':'"+ue_formato_calculo(arrregcaif[i].get('monto'))+"','sig_cuenta':'"+arrregcaif[i].get('codigo')+"'}";
				}
			}				
			reg=reg+"]";
			reg = reg+"}";
			Obj= Ext.util.JSON.decode(reg);
			ObjSon=Ext.util.JSON.encode(Obj);
			parametros = 'ObjSon='+ObjSon; 
			Ext.Ajax.request({
			url : ruta2,
			params : parametros,
			method: 'POST',
			success: function (resultad, request){ 
		    datos = resultad.responseText;
			var Registros = datos.split("|");
			Cod = Registros[1];
				if(Cod!='')
				{
					Ext.MessageBox.alert('Mensaje', 'Registro Modificado con éxito');
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
		}
}


 //Ext.get('ImgSumar').on('click', function()
function irAgregar()
{
	var myJSONObject ={
		"oper": 'CatCuenIn', 
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
	NumRegGrid = grid.store.getTotalCount();
	NumIni = NumRegGrid-1; 
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
//	Ext.Ajax.request({
//	url : ruta2,
//	params : parametros,
//	method: 'POST',
//	success: function ( resultado, request ){ 
//		  datos = resultado.responseText;
		  //alert(datos)
		  var myObject = {"raiz":[{"codigo":'',"denominacion":''}]};
		//};
		  var RecordDef = Ext.data.Record.create([
			{name:'codigo'},     
			{name:'denominacion'},
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
       

            grid2 = new Ext.grid.GridPanel({
			width:770,
			autoScroll:true,
            border:true,
            ds: new Ext.data.Store({
		//	proxy: new Ext.data.MemoryProxy(myObject),
			reader: new Ext.data.JsonReader({
			    root:'raiz',                // The property which contains an Array of row objects
			     id: "id"   
			    
			},
                    RecordDef
			     
			      ),
				data: myObject
                        }),
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

	if(simpleCuentasIn=='')
	{		
		simpleCuentasIn = new Ext.FormPanel({
        labelWidth:75, // label settings here cascade unless overridden
        //url:'save-form.php',
        frame:true,
        bbar:[MostrarCuentas],
        title: 'Búsqueda',
        bodyStyle:'padding:5px 5px 0;height:50px',
        width: 350,
		height:120,
        defaults:{width: 230},
        defaultType: 'textfield',
		items: [{
                fieldLabel: 'Código',
                name:'codigoCuenta',
				id:'codigoCuenta',
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
                name: 'denCuenta',
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
	}
	
	    		if(winCatCuentas=='')
                 {
                   winCatCuentas = new Ext.Window(
                   {
	                 title: 'Catálogo de Cuentas de Ingresos',
			   		 autoScroll:true,
	                 width:800,
	                 height:400,
	                 modal: true,
	                 plain: false,
	                 closable:false,
	                 items:[simpleCuentasIn,grid2],
	                 buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {
                     		datosRegistroActual = grid2.getSelectionModel().getSelections();
	                  		for(i=0;i<datosRegistroActual.length;i++)
	                  		{
	                  		//alert(datosRegistroActual[i]);
	                  		rec = datosRegistroActual[i];
	                  		resp = validarExistencia(grid2,grid,'codigo','sig_cuenta');
	                  		
	                  			if(resp)
	                  			{
	                  				return false;		
	                  			}
	                  		
						  		PasarDatosgrid(rec);
							}

	                  	winCatCuentas.destroy();
	                  	simpleCuentasIn.destroy();
	                  	grid2.destroy(); 
	                  	winCatCuentas="";
	                  	simpleCuentasIn="";
	                  		
                     }
                    }
                    ,
                    {
                     text: 'Salir',
                     handler: function()
                     {
	                        winCatCuentas.destroy();
	                  		simpleCuentasIn.destroy();
	                  		grid2.destroy();
	                  		winCatCuentas="";
		                  	simpleCuentasIn="";                		
                     }
                    }]
                   });
                   }
                   Ext.MessageBox.hide();
                   winCatCuentas.show();
                  
	               
	               
        //},
//        failure: function ( resultado, request){ 
//                   Ext.MessageBox.alert('Error', resultado.responseText); 
//        }
	
	
  // });
	
}
//);

function PasarDatosgrid(Registro)
{
	var p = new RecordDef
	(
	    {
			sig_cuenta:Registro.get('codigo'),
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
    );
    
    if(NumIni>0)
    {
    	NumIni= NumIni + 1;	
    }
	grid.store.insert(NumIni,p);
	p.set('denominacion',Registro.get('denominacion'));
	
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

function ActualizarData()
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
	//alert(datos); 
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
		"oper": 'buscarcadena', 
		"criterio":criterio, 
		"cadena": valor
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
		//alert(datos);
		DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {	
		//alert(DatosNuevo)	
		
		  }
		else
		 {
			var DatosNuevo={"raiz":[{"spi_cuenta":'',"denominacion":'',"montoGlobal":'',"NuevoRegistro":''}]};
		 }
		grid2.store.loadData(DatosNuevo);
	}
});
	
}
obtenersesion();
getobject();
function GrabarCuenta(){
reg="";
Registro = grid.getSelectionModel().getSelected();
if(Ext.getCmp('cxc').getValue()==true)
{
	if(codcuenxcobrar!='')
	{
		porcobrar = codcuenxcobrar	
	}
	else
	{
		Ext.MessageBox.alert('Mensaje', 'No se ha definido la cuenta por cobrar');
		return false;
	}
}

if(Registro.get('NuevoRegistro')==true)
{
	if(!Registro.get('enerocob'))
	{
		Registro.set('enerocob',0);
	}
	if(!Registro.get('febrerocob'))
	{
		Registro.set('febrerocob',0);
	}
	if(!Registro.get('marzocob'))
	{
		Registro.set('marzocob',0);
	}
	if(!Registro.get('abrilcob'))
	{
		Registro.set('abrilcob',0);
	}
	if(!Registro.get('mayocob'))
	{
		Registro.set('mayocob',0);
	}
	if(!Registro.get('juniocob'))
	{
		Registro.set('juniocob',0);
	}
	if(!Registro.get('juliocob'))
	{
		Registro.set('juliocob',0);
	}
	if(!Registro.get('agostocob'))
	{
		Registro.set('agostocob',0);
	}
	if(!Registro.get('septiembrecob'))
	{
		Registro.set('septiembrecob',0);
	}
	if(!Registro.get('octubrecob'))
	{
		Registro.set('octubrecob',0);
	}
	if(!Registro.get('noviembrecob'))
	{
		Registro.set('noviembrecob',0);
	}
	if(!Registro.get('diciembrecob'))
	{
		Registro.set('diciembrecob',0);
	}
	
	

	totalMovimientos = gridAsientoCont.store.getCount()-1;
	arrMovimientos = gridAsientoCont.store.getRange(0,totalMovimientos);
	reg=reg+"{'oper':'grabarPlan','DatosIng':[";
	reg=reg+"{'cuentaporcobrar':'"+porcobrar+"','codemp':'0001','sig_cuenta':'"+ Registro.get('sig_cuenta')+"','MontoGlobal':'"+ Registro.get('montoglobal')+"','disponible':'"+Registro.get('montoglobal')+"','ano_presupuesto':'"+ Registro.get('ano_presupuesto')+"','enero':'"+ Registro.get('enero')+"','febrero':'"+ Registro.get('febrero')+"','marzo':'"+ Registro.get('marzo')+"','abril':'"+ Registro.get('abril')+"','mayo':'"+ Registro.get('mayo')+"','junio':'"+ Registro.get('junio')+"',";
	reg=reg+"'julio':'"+ Registro.get('julio')+"','agosto':'"+ Registro.get('agosto')+"','septiembre':'"+ Registro.get('septiembre')+"','octubre':'"+ Registro.get('octubre')+"','noviembre':'"+ Registro.get('noviembre')+"','diciembre':'"+ Registro.get('diciembre')+"'";
	reg=reg+",'enerocob':'"+ Registro.get('enerocob')+"','febrerocob':'"+Registro.get('febrerocob')+"','marzocob':'"+ Registro.get('marzocob')+"','abrilcob':'"+ Registro.get('abrilcob')+"','mayocob':'"+ Registro.get('mayocob')+"','juniocob':'"+ Registro.get('juniocob')+"',";
	reg=reg+"'juliocob':'"+ Registro.get('juliocob')+"','agostocob':'"+ Registro.get('agostocob')+"','septiembrecob':'"+ Registro.get('septiembrecob')+"','octubrecob':'"+ Registro.get('octubrecob')+"','noviembrecob':'"+ Registro.get('noviembrecob')+"','diciembrecob':'"+ Registro.get('diciembrecob')+"'";	 
	reg=reg+",'monto':'"+Registro.get('montoglobal')+"','montoanoanterior':'"+Registro.get('montoanoanterior')+"','montoanoactual':'"+Registro.get('montoanoactual')+"'}],'movimientos':[";
	
		for(i=0;i<arrMovimientos.length;i++)
		{
			if(arrMovimientos[i].get('codigo')!='')
			{
				if(i==0)
				{
					reg=reg+"{'sc_cuenta':'"+arrMovimientos[i].get('codigo')+"','operacion':'"+arrMovimientos[i].get('operacion')+"','monto':'"+arrMovimientos[i].get('monto')+"'}";
				}
				else
				{
					reg=reg+",{'sc_cuenta':'"+arrMovimientos[i].get('codigo')+"','operacion':'"+arrMovimientos[i].get('operacion')+"','monto':'"+arrMovimientos[i].get('monto')+"'}";
				}
			}
		}
	reg = reg+"]}";
	Obj= Ext.util.JSON.decode(reg);
	ObjSon=Ext.util.JSON.encode(Obj);
	//alert(ObjSon);
	//return false;	
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta2,
	params : parametros,
	method: 'POST',
	success: function(resultad, request){ 
    datos = resultad.responseText;
	//alert(datos);
	//return false;
	var Registros = datos.split("|");
	Cod = Registros[1];
		if(Cod!='')
		{
				Ext.MessageBox.alert('Mensaje', 'Los montos de las cuenta seleccionada se registraron con éxito');
				grid.store.each(function(Obj){
				Obj.set('NuevoRegistro',false);	
			})
			ActualizarData();
		}
		else
		{
			Ext.MessageBox.alert('Error','El registro');				
		}
      }
      ,
	failure: function ( result, request)
	{ 
		Ext.MessageBox.alert('Error', result.responseText); 
	} 
    });
    }
}
//}

		
});

 
              
             




