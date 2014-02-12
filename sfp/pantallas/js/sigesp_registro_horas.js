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
var win = '';
var win2 = '';
var t=0;
var Evento='incluir';
var unavez = false;
var parametros='';
var ruta = '';
var RecordDef;
var grid2='';
var CanHorasDia =0;
var DataStore='';
var DatosNuevo ="";
var NumRegGrid=0;
var NumIni=0;
var Mostrado="";
var codConsultor='';
var nomConsutor='';
var ObjTxtCli=""
var gridMontos='';
var comboyear="";
var ForMontos='';
var NuevoRegistro = "";
ruta2 ='../../procesos/sigesp_registro_horaspr.php';

Ext.onReady(function()
{
    // basic tabs 1, built from existing content

function getSesion()
{
		var myJSONObject =
	{
		"oper":'obtenersesion'
		
	};	
	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta2,
	params : parametros,
	method: 'POST',
	success: function (resultado, request ) 
	{ 
		datos = resultado.responseText;
		AuxDatos=datos.split("|");
		codConsultor=AuxDatos[0];
		nomConsultor=AuxDatos[1];
		if(codConsultor!='0' && nomConsultor!='0')
			{
				getobject();
			}
			else
			{
				location.href='http://www.google.com';
			}
	}
	})
	
	
	
}

function getobject()
{
	
	fecha=new Date();
	mes=fecha.getMonth()+1;

	if(mes.toString().length==1)
	{
		mes='0'+mes.toString();
	}
	fechaActual = fecha.getDate()+'/'+ mes +'/'+fecha.getFullYear();
	//alert(fechaActual);
//	fechaActual = '04/08/2008';
	Year = fecha.getFullYear();
	Year2 = Year+1;
	Year3 = Year+2;
	Years =
	[
        [Year],
        [Year2],
        [Year3]
	]	
	

	var RecordDefSer = Ext.data.Record.create([
			{name: 'propiedad0'},
			{name: 'propiedad1'}// "mapping" property not needed if it's the same 
			
		]);
	
	myObject={"raiz":[{"propiedad0":'',"propiedad1":''}]};	
	var dsSer= new Ext.data.Store({
	reader: new Ext.data.JsonReader({
	root:'raiz',               
	id: "id"   
	},
     RecordDefSer
	),
	data: myObject
   })
	
	var RecordDefMod = Ext.data.Record.create
		([
			{name: 'propiedad0'},
			{name: 'propiedad1'}// "mapping" property not needed if it's the same 
			
		]);
	
	myObject={"raiz":[{"propiedad0":'',"propiedad1":''}]};	
	var dsMod= new Ext.data.Store({
	reader: new Ext.data.JsonReader({
	root:'raiz',               
	id: "id"   
	},
     RecordDefMod
	),
	data: myObject
   })
	

  ForMontos = new Ext.FormPanel({
  labelWidth: 75, // label settings here cascade unless overridden,
  title: 'Registro de Horas',
  renderTo:'formprincipal',
  autoScroll:true,
  height:350,  
  items:[
	{
            xtype:'fieldset',
            layout:'column',
            title: 'Consultores',
            autoHeight:true,
            items:[
{
columnWidth:.3,layout:'form',border:false,
items:[
{xtype:'textfield',fieldLabel:'Consultor',name:'first',size:10,width:200,id:'nomcon',value:nomConsultor,readOnly:true},{xtype:'hidden',id:'cedcon',value:codConsultor}]
}
,
{
columnWidth:.2,layout:'form',border:false,
items:
[
{xtype:'datefield',fieldLabel:'Fecha de Registro',name:'last',size:8,id:'fecreg',format:'d/m/Y',value:fechaActual}
]
},
{
columnWidth:.3,layout:'form',border:false,
items:[
{xtype:'textfield',name:'last',size:10,width:195,value:'Registro en Estatus de aprobacion'}
]
}
,
{
columnWidth:.2,layout:'form',border:false,
items:
[
	{xtype:'textfield',readOnly:true,name:'last',size:8,value:fechaActual,width:80,id:'fecsys'}
]
}
]},{
	  
            xtype:'fieldset',
            layout:'column',
            id:'col2',
            title: 'Registro de Datos',
            autoHeight:true,
            items:[
{
columnWidth:.7,layout:'form',border:false,
items:[{xtype:'textfield',editable:true,fieldLabel:'Cliente',id:'denclientes',width:300},{xtype:'hidden',editable:true,id:'codcliente'},
{xtype:'textfield',editable:true,fieldLabel:'Servicio',id:'denser',width:500},{xtype:'hidden',editable:true,id:'codser'},  
{xtype:'textfield',editable:true,fieldLabel:'Módulo',id:'denmod',width:300},
{xtype:'hidden',editable:true,id:'codmod'},
{xtype:'timefield',fieldLabel:'Hora Inicio',id:'hi',increment:10},
{xtype:'timefield',id:'hf',fieldLabel:'Hora Fin',name:'last',size:8,increment:10},
{xtype:'textfield',fieldLabel:'Total',name:'last',size:3,width:50,id:'cantidad'}]
},
{
columnWidth:.3,layout:'form',border:false,
items:
[
{xtype:'textfield',fieldLabel:'C�digo de Proyecto',name:'last',size:8,labelStyle:'width:150px',id:'codproy'},
{xtype:'textfield',fieldLabel:'Solicitante',name:'last',size:8,labelStyle:'width:150px',id:'solicitante'}
]
}
]},{
	  
            xtype:'fieldset',
            layout:'column',
            title: 'Tipos de Problema(esta opci�n solo ser� usada por el call center)',
            autoHeight:true,
            items:[
{
columnWidth:.3,layout:'form',border:false,
items:[
{xtype:'checkbox',fieldLabel:'Usuario',name:'tipo1',size:2,id:'tp_usu',inputValue:'1'}]
},
{
columnWidth:.2,layout:'form',border:false,
items:
[
{xtype:'checkbox',fieldLabel:'Sistema',name:'tipo2',size:2,id:'tp_sis',inputValue:'1'}
]
},
{
columnWidth:.3,layout:'form',border:false,
items:[
{xtype:'checkbox',fieldLabel:'Nuevo Requerimiento',name:'tp_nrq',size:8
,id:'tp_nrq',inputValue:'1'}]
}
,
{
columnWidth:.2,layout:'form',border:false,
items:[{
xtype:'checkbox',fieldLabel:'Implantacion',name:'tp_imp',size:2,id:'tp_imp'
,inputValue:'1'}
]
}
]},
  			
			
	{
            xtype:'tabpanel',
            plain:true,
            activeTab:1,
            id:'panelobs',
            height:235,
          //  defaults:{bodyStyle:'padding:10px'},
            items:[
			  {
	             	  title:'Problema',
		              xtype:'textarea',
		              id:'problema' 
				 
	          }
				,
				{
					title:'Actividad',
                    xtype:'textarea',
                    id:'nota'
                }
            	,
                {
                	  title:'Soluci�n',
		              xtype:'textarea',
		              id:'solucion' 
				 }
	          
			 
			]
			}
			]
		}
		);
	
	Ext.get('cantidad').addListener('focus',valHoras);
	Ext.get('denclientes').addListener('click',agregarCliente);
	Ext.get('denser').addListener('click',agregarSer);
	Ext.get('denmod').addListener('click',agregarMod);
	//alert(idfor);
	//ForMontos.getComponent('col2').getComponent('formcol2').getComponent('cmbHoraFin').addListener('change',valHoras);
	var myJSONObject =
	{
		"oper":'Catalogos', 
		"cedcon": codConsultor,
		"fecreg":fechaActual
		
	};	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta2,
	params : parametros,
	method: 'POST',
	success: function (resultado, request ) { 
	datos = resultado.responseText;
//	alert(datos);
	ArrDatos = datos.split('|');
//	alert(ArrDatos[0]);
	DatosNuevoCli = eval('(' + ArrDatos[0] + ')');
//	dsClientes.loadData(DatosNuevoCli);
	DatosNuevoSer = eval('(' + ArrDatos[1] + ')');
	dsSer.loadData(DatosNuevoSer);
	DatosNuevoMod = eval('(' + ArrDatos[2] + ')');
	dsMod.loadData(DatosNuevoMod);
	DatosRegistro = eval('(' + ArrDatos[3] + ')');
	CanHorasDia = ArrDatos[4];
	//alert(CanHorasDia);
		if(DatosRegistro.raiz==null)
		{
			
			DatosRegistro={"raiz":[{"propiedad1":'',"propiedad2":'',"propiedad3":'',"propiedad9":''}]};
			
		}
				
		RecordDef = Ext.data.Record.create([
				{name: 'propiedad0'},
				{name: 'propiedad1'},
				{name: 'propiedad2'},
				{name: 'propiedad3'},
				{name: 'propiedad4'},
				{name: 'propiedad5'},
				{name: 'propiedad6'},
				{name: 'propiedad7'},
				{name: 'propiedad8'},
				{name: 'propiedad9'},
				{name: 'propiedad10'},
				{name: 'propiedad11'},
				{name: 'propiedad12'},
				{name: 'propiedad13'},
				{name: 'propiedad14'},
				{name: 'propiedad15'},
				{name: 'propiedad16'},
				{name: 'propiedad17'},
				{name: 'propiedad18'},
				{name: 'propiedad19'},
				{name: 'propiedad20'},
				{name: 'propiedad21'},
				{name: 'propiedad22'},
				{name: 'propiedad23'},
				{name: 'propiedad24'}
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
					data: DatosRegistro
            });
			
			grid = new Ext.grid.EditorGridPanel({
  			width:950,
  			height:120,
			autoScroll:true,
            border:true,
            ds:DataStore,
            cm: new Ext.grid.ColumnModel
			(
			[
            {header: "Fecha", width: 50, sortable: true,  dataIndex: 'propiedad1'},
            {header: "Hora de Inicio", width: 50, sortable: true, dataIndex: 'propiedad3'},
			{header: "Hora de Fin", width: 50, sortable: true, dataIndex: 'propiedad2'},
			{header: "Actividad", width: 250, sortable: true, dataIndex: 'propiedad9'}					
			]
			),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig: {
                        forceFit:true	
                        },
			//autoHeight:true,
			stripeRows: true
            });
		   
		grid.render('grid-example');
		grid.addListener('celldblclick',PasarDatos);
		
	}
	
});	
		
 		Ext.state.Manager.setProvider(new Ext.state.CookieProvider());        
       var viewport = new Ext.Viewport({
            layout:'border',
            items:[
                new Ext.BoxComponent({ // raw
                    region:'north',
                    el: 'norte',
                    height:20
                }),
                
                new Ext.BoxComponent({ // raw
                    region:'center',
                    el: 'progra',
                    height:500
                })
			,  
		
			  new Ext.BoxComponent
			  	({ // raw
                    region:'south',
                    el: 'sur',
                    height:130	
                })	
				]
         })
         
}
Ext.get('BtnNuevo').on('click', function()	
{
	LimpiarCampos();
	Ext.getCmp('codcliente').setValue('');
	Ext.getCmp('denclientes').setValue('');
	Ext.getCmp('codser').setValue('');
	Ext.getCmp('denser').setValue('');
	Ext.getCmp('codmod').setValue('');
	Ext.getCmp('denmod').setValue('');
	Evento='incluir';	
//	grid.store.removeAll();		
})
function LimpiarCampos()
{
	Ext.getCmp('hi').setValue('');
	Ext.getCmp('hf').setValue('');
	Ext.getCmp('cantidad').setValue('');
	Ext.getCmp('codproy').setValue('');
	Ext.getCmp('solicitante').setValue('');
	Ext.getCmp('nota').setValue('');
	Ext.getCmp('problema').setValue('');
	Ext.getCmp('solucion').setValue('');
	Ext.getCmp('tp_usu').setValue(false);
	Ext.getCmp('tp_sis').setValue(false);
	Ext.getCmp('tp_nrq').setValue(false);
	Ext.getCmp('tp_imp').setValue(false);
	Ext.getCmp('hi').enable();
	Ext.getCmp('hf').enable();

}


function agregarCliente()
{
	ObjTxtCli = Ext.getCmp('denclientes');
	ObjProb = new CatProb();
	ObjProb.MostrarCatalogo();	
}


function agregarSer ()
{
	//ObjTxtCli = Ext.getCmp('denclientes');
	ObjServ = new CatServ();
	ObjServ.MostrarCatalogoSer();	
}


function agregarMod()
{
	//ObjTxtCli = Ext.getCmp('denclientes');
	ObjServ = new CatMod();
	ObjServ.MostrarCatalogoMod();	
}


function valHoras()
{
Hora1=Ext.get('hi').getValue();
Hora2=Ext.get('hf').getValue();
if(Hora1.length==8)
{
	turnoH1=Hora1.substr(6,2);	
	Hora1=Hora1.substr(0,5)+':00';
}
else
{
	turnoH1=Hora1.substr(5,2);
	Hora1=Hora1.substr(0,4)+':00';
}
if(Hora2.length==8)
{
	turnoH2=Hora2.substr(6,2);
	Hora2=Hora2.substr(0,5)+':00';	
	MinHora2 = Hora2.substr(3,2)
}
else
{
	turnoH2=Hora2.substr(5,2);
	Hora2=Hora2.substr(0,4)+':00';
	MinHora2 = Hora2.substr(2,2);
}
//alert(turnoH1);
//alert(turnoH2);
if(turnoH1==turnoH2)
{
	TotalHoras = substractTimes(Hora2,Hora1);
	Aux = TotalHoras.substr(0,1);
	if(Aux=='0')
	{
		TotalHoras=TotalHoras.substr(1,4);
	}
}
else
{
	AuxHora1= substractTimes('12:00:00',Hora1);
	AuxHora2=Hora2.substr(0,1)+'.'+MinHora2;
	TotalHoras=sumaTiempos(AuxHora1,AuxHora2);
	//alert(TotalHoras);
}
Ext.getCmp('cantidad').setValue(TotalHoras);

}
function LlenaMontoEq(Obj)
{
	ValorEq = ForMontos.getComponent('MontoEq').getValue();
	MontoEq = parseInt(ValorEq)/12; 
	if(ValorEq!='' && Obj.get('year')!='')
	{
		Obj.set('monto',MontoEq);
	}		
}



function validarCampos(codcli,codser,act,horaI,horaF)
{
	if(codcli=='')
	{
		Ext.MessageBox.alert('Validaci�n','Debe Ingresar un Cliente');
		return false;
	}
	else if(codser=='')
	{
		Ext.MessageBox.alert('Validaci�n','Debe Ingresar un Servicio');
		return false
	}
	else if(!act)
	{
		Ext.MessageBox.alert('Validaci�n','Debe Ingresar una Actividad');
		return false;
	}
	else if(horaI=='')
	{
		Ext.MessageBox.alert('Validaci�n','Debe Ingresar la hora de inicio');
		return false;
	}
	else if(horaF=='')
	{
		Ext.MessageBox.alert('Validaci�n','Debe Ingresar la hora de fin');
		return false;
	}	
	else 
	{
		return true;
	}
}

function PasarDatos()
{
	Reg = grid.getSelectionModel().getSelected();
	Ext.getCmp('fecreg').setValue(Reg.get('propiedad1'));
	Ext.getCmp('codcliente').setValue(Reg.get('propiedad5'));
	Ext.getCmp('denclientes').setValue(Reg.get('propiedad19'));
	Ext.getCmp('codser').setValue(Reg.get('propiedad7'));
	Ext.getCmp('denser').setValue(Reg.get('propiedad20'));
	Ext.getCmp('codmod').setValue(Reg.get('propiedad8'));
	Ext.getCmp('denmod').setValue(Reg.get('propiedad21'));
	Ext.getCmp('hi').setValue(Reg.get('propiedad3'));
	Ext.getCmp('hf').setValue(Reg.get('propiedad2'));
	Ext.getCmp('hi').disable();
	Ext.getCmp('hf').disable();
	Ext.getCmp('cantidad').setValue(Reg.get('propiedad4'));
	Ext.getCmp('codproy').setValue(Reg.get('propiedad6'));
	Ext.getCmp('solicitante').setValue(Reg.get('propiedad10'));
	Ext.getCmp('nota').setValue(Reg.get('propiedad9'));
	Ext.getCmp('problema').setValue(Reg.get('propiedad11'));
	Ext.getCmp('solucion').setValue(Reg.get('propiedad12'));
	Ext.getCmp('tp_usu').setValue(Reg.get('propiedad13'));
	Ext.getCmp('tp_sis').setValue(Reg.get('propiedad14'));
	Ext.getCmp('tp_nrq').setValue(Reg.get('propiedad15'));
	Ext.getCmp('tp_imp').setValue(Reg.get('propiedad16'));
	Evento='modificar';
}


function CambiarValorCheck(valor)
{
	if(valor)
	{
		return '1';
	}
	else
	{
		return '0'
	}	
}

function GrabarPlanCuenta()
{
chUsu =CambiarValorCheck(Ext.getCmp('tp_usu').getValue());
chSis=CambiarValorCheck(Ext.getCmp('tp_sis').getValue());
chNrq=CambiarValorCheck(Ext.getCmp('tp_nrq').getValue());
chImp=CambiarValorCheck(Ext.getCmp('tp_imp').getValue());

//alert(chImp);

if(Evento=='incluir')
{
	//var dt = new Date(Ext.get('fecreg').getValue());
	//fecreg = dt.format('d/m/Y');	
	fecreg=Ext.get('fecreg').getValue();
}
else
{
	//alert('dsd');
	fecreg=Ext.get('fecreg').getValue();
}
cantidad = Ext.getCmp('cantidad').getValue();
codcli = Ext.getCmp('codcliente').getValue();
codser = Ext.getCmp('codser').getValue();
codmod = Ext.getCmp('codmod').getValue();
act = Ext.getCmp('nota').getValue();
horaI = Ext.getCmp('hi').getValue();
horaF = Ext.getCmp('hf').getValue();
act = cadSinEspacio(act);
sol = cadSinEspacio(Ext.getCmp('solucion').getValue());
prob = cadSinEspacio(Ext.getCmp('problema').getValue());
HorasPorDia = CanHorasDia +parseInt(cantidad);
if(!sol)
{
	sol="";
}
if(!prob)
{
	prob="";
}
Resp = validarCampos(codcli,codser,act,horaI,horaF);
//alert(codcli);
//alert(codser);
//alert(codmod);
//codcli = substr(1,7,Ext.get('codcli').getValue());

//	if(MontosMeses.length>0)
//	{
	//alert(fecreg);
if(Resp)
{	
	if(HorasPorDia>8)
	{
		Ext.Msg.alert('Validaci�n','El numero de horas registradas por usted con fecha '+fecreg+' supera el m�ximo permitido');
		return false;
	}
	
	
	reg = "{'oper':'"+Evento+"','cedcon':'"+Ext.getCmp('cedcon').getValue()+"','fecreg':'" + fecreg +"','hi':'"+Ext.getCmp('hi').getValue()+"','hf':'"+Ext.getCmp('hf').getValue()+"','cantidad':'"+Ext.getCmp('cantidad').getValue()+"','codcli':'"+Ext.getCmp('codcliente').getValue()+"','codproy':'"+Ext.getCmp('codproy').getValue()+"','codser':'"+Ext.getCmp('codser').getValue()+"','codmod':'"+Ext.getCmp('codmod').getValue()+"','fecsys':'"+Ext.getCmp('fecsys').getValue()+"','nota':'"+act+"','tcpip':'wwss','solicitante':'"+Ext.getCmp('solicitante').getValue()+"','problema':'"+prob+"','solucion':'"+sol+"','tp_usu':'"+chUsu+"','tp_sis':'"+chSis+"','aprobado':'1','tp_nrq':'"+chNrq+"','tp_imp':'"+chImp+"','facturable':'1','contabilizado':'1','codofi':'1','numfac':'1','mie':'1'}";


	Obj= eval('(' + reg + ')');
	ObjSon=JSON.stringify(Obj);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta2,
	params : parametros,
	method: 'POST',
	success: function ( resultad, request)
	{ 
    datos = resultad.responseText;
	alert(datos);
	var Registros = datos.split("|");
	Cod = Registros[1];
		if(Cod=='1')
		{
			//alert();
			Ext.MessageBox.alert('Mensaje','Registro Incluido Con �xito');
			ActualizarDataGrid();
			Evento='incluir';
			LimpiarCampos();
		}
		else
		{
			Ext.MessageBox.alert('Error', 'El registro no se incluy�');				
		}
      }
	  ,
	failure: function ( result, request)
	 { 
		Ext.MessageBox.alert('Error', 'El registro no se incluy�'); 
	 } 
    });
//}
//else
//{

}
}

Ext.get('BtnElim').on('click', function()
{
	Evento='eliminar';
	fecreg=Ext.get('fecreg').getValue();
	var selectedKeys = grid.selModel.selections.keys;
        if(selectedKeys.length > 0)
		{
            Ext.Msg.confirm('Mensaje','Realmente desea eliminar el registro?', deleteRecord);
        } 
		else 
		{
            Ext.Msg.alert('Mensaje','Seleccione un registro para eliminar');
        }

});

Ext.get('BtnImp').on('click', function()
{
	Evento='imprimir';
	fecreg=Ext.get('fecreg').getValue();
	
		if(fecreg=='')
		{
			Ext.Msg.alert('Mensaje','Debe seleccionar una fecha para generar el reporte');
			return false;
		}
		if(Ext.getCmp('codcliente').getValue()=='')
		{
			Ext.Msg.alert('Mensaje','Debe seleccionar un cliente para generar el reporte');	
		}
					
		reg = "{'oper':'"+Evento+"','cedcon':'"+Ext.getCmp('cedcon').getValue()+"','fecreg':'" + fecreg +"','codcli':'"+Ext.getCmp('codcliente').getValue()+"'}";
 					Obj= eval('(' + reg + ')');
					ObjSon=JSON.stringify(Obj);
					parametros = 'ObjSon='+ObjSon; 
					Ext.Ajax.request({
					url : ruta2,
					params : parametros,
					method: 'POST',
					success: function ( resultad, request ){ 
				    datos = resultad.responseText;
				    if(datos)
				    {
				    	Abrir_ventana(datos);
						Evento='incluir';	
					}
	}
	})
		
});

 function deleteRecord(btn) 
 {
	  if (btn=='yes') 
	  {
		var selectedRow = grid.getSelectionModel().getSelected();
		if(selectedRow)
		{
					
				reg = "{'oper':'"+Evento+"','cedcon':'"+Ext.getCmp('cedcon').getValue()+"','fecreg':'" + fecreg +"','hi':'"+Ext.getCmp('hi').getValue()+"','hf':'"+Ext.getCmp('hf').getValue()+"'}";
 					Obj= eval('(' + reg + ')');
					ObjSon=JSON.stringify(Obj);
					parametros = 'ObjSon='+ObjSon; 
					Ext.Ajax.request({
					url : ruta2,
					params : parametros,
					method: 'POST',
					success: function ( resultad, request )
					{ 
				    datos = resultad.responseText;
				   // alert(datos);
					var Registros = datos.split("|");
					Cod = Registros[1];
						if(Cod!='')
						{
							Ext.MessageBox.alert('Mensaje', 'Registro eliminado con �xito')
							grid.store.commitChanges();
							Evento='incluir';
							LimpiarCampos();
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


Ext.get('ImgSumar').on('click', function()
{
	GrabarPlanCuenta();
});


function PasarDatosgrid(Registro,i)
{
	var p = new RecordDef
	(
	    {
			spi_cuenta:Registro.get('spi_cuenta'),
		    denominacion:Registro.get('denominacion'),
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
		    diciembre:0
		    
		}
    );
    
    if(NumIni>0)
    {
    	NumIni= NumIni + 1;	
    }
	grid.store.insert(NumIni,p);
	
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



function ActualizarDataGrid()
{
	//alert(fechaActual);
	var myJSONObject =
	{
		"oper":'Catalogos', 
		"cedcon": codConsultor,
		"fecreg":fechaActual
		
	};	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request
	({
	url : ruta2,
	params : parametros,
	method: 'POST',
	success: function (resultado, request )
	{ 
	datos = resultado.responseText;
//	alert(datos);
	ArrDatos = datos.split('|');
	DatosRegistro = eval('(' + ArrDatos[3] + ')');
	if(DatosRegistro.raiz==null)
	{
			
		DatosRegistro={"raiz":[{"propiedad1":'',"propiedad2":'',"propiedad3":'',"propiedad9":''}]};
			
	}
	
		grid.store.loadData(DatosRegistro);
	
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

getSesion();

//ActualizarData();
		
});

 
              
             




