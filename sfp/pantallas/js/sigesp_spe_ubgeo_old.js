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
var win = null;
var unavez = false;
var parametros='';
var cantidad=0;
var ruta = '';
var RecordDef;
var DatosSesion;
var grid1='';
var grid2='';
var grid3='';
var grid4='';
var grid5='';
var grid6='';
var grid7='';
var valor1='';
var valor2='';
var valor3='';
var DataStore1='';
var DataStore2='';
var DataStore3='';
var DataStore4='';
var DataStore5='';
var DataStore6='';
var DataStore7='';
var Listo1 = false;
var Listo2 = false;
var Oper='';
var DatosNuevo ="";
var tabs='';
 
ruta ='../../procesos/sigesp_spe_ubgeopr.php';
Ext.onReady(function(){
    // basic tabs 1, built from existing content


function getDatos(Metodo)
{
	var myJSONObject ={
		"oper": Metodo, 
		"numest":'1',
		"codest": "", 
		"denest": ""
};	

	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request) 
	{ 
		  datos = resultado.responseText;
		if(datos!='')
		 {
		 	arr = datos.split("|");
		  	jsonserv = arr[1];
		  	cantidad = arr[0];
		 // 	alert(datos);
			var mijson = eval('(' + jsonserv + ')');
		 	switch(Metodo)
		 	{
		 		case 'getSesion':
		 	
		 			for(i=0;i<parseInt(cantidad);i++)
		 			{
		 				
		 				if(i==0)
		 				{
							getgrid(1);
						}
						else
						{
							aux2=i+1;
							getgrid2(aux2);
						}
		 				agregarTab(mijson.raiz[i].nombre_pest,'grid'+i);
					
					 }
					 Listo1=true;
					MostrarForma(false);
					tabs.activate(0);
					habilitarUna(0); 
				
		 		break;
		 	}
	
		 }
	}	
	
});	
	
}


function ManejarTabActivo(tab)
{
num = parseInt(tab.id)+1;
//alert(eval('grid'+num));
//alert(grid1);

if(Listo1)
{
//alert('sdd');
switch(parseInt(tab.id))
{
	case 1:
	valor1= grid1.getSelectionModel().getSelected().get('codpai');
	den1= grid1.getSelectionModel().getSelected().get('despai');
	tabanterior = tabs.getItem('0').title;
	Ext.get('nivel1').dom.innerHTML=tabanterior;
	Ext.get('valornivel1').dom.innerHTML=den1;
	if(grid2!='')
	{
	//	habilitarUna(parseInt(tab.id));	
		ActualizarData(valor1,'0','3','4','2');
	}
	
	MostrarForma(true);
	break;
	case 2:
	valor2= grid2.getSelectionModel().getSelected().get('codest');
	den2= grid2.getSelectionModel().getSelected().get('desest');
	tabanterior = tabs.getItem('1').title;
	Ext.get('nivel2').dom.innerHTML=tabanterior;
	Ext.get('valornivel2').dom.innerHTML=den2;
	if(grid3!='')
	{
		deshabilitarAnt(2);
		ActualizarData(valor1,valor2,'3','4','3');
	}
	MostrarForma(true);
	break;
	case 3:
	//alert('2');
	valor3= grid3.getSelectionModel().getSelected().get('CODEST3');
	den3= grid3.getSelectionModel().getSelected().get('DENEST3');
	tabanterior = tabs.getItem('2').title;
	Ext.get('nivel3').dom.innerHTML=tabanterior;
	Ext.get('valornivel3').dom.innerHTML=den3;
	if(grid4!='')
	{
		deshabilitarAnt(3);
		ActualizarData(valor1,valor2,valor3,'0','4');
	}
	MostrarForma(true);
	break;
	case 4:
	//alert('2');
	valor4= grid4.getSelectionModel().getSelected().get('CODEST4');
	den4= grid4.getSelectionModel().getSelected().get('DENEST4');
	//alert(valor2);
	tabanterior = tabs.getItem('3').title;
	Ext.get('nivel4').dom.innerHTML=tabanterior;
	Ext.get('valornivel4').dom.innerHTML=den4;
	if(grid5!='')
	{
		deshabilitarAnt(4);
		ActualizarData(valor1,valor2,valor3,valor4,'5');
	}
	MostrarForma(true);
	break;

	default:
	MostrarForma(false);
	break;	
}

}
}


function click_extra()
{

	habilitarUna(tabs.getActiveTab().id,true) 

}
	
	
function deshabilitarAnt(tab)
{			
		if(tab>1)
		{
			num2 = tab-2;
			tabs.getItem(num2).disable();	
		}
}

function habilitarUna(tab,paso)
{
	//alert(tab);
	for(var r=0;r<cantidad;r++)
	{
		num2 = r+1;
		if(r==tab)
		{	
			
			if(r>0)
			{
				tabs.getItem(num2).enable();
				tabs.getItem(r-1).enable();
				r++;
			}
			else 
			{
				if(paso)
				{
					tabs.getItem(num2).enable();
					r++;
				}
				tabs.getItem(r).enable();		
			}
			
		}
		else
		{
			tabs.getItem(r).disable();
		}
	}
}

function MostrarForma(valor)
{
	if(valor==false)
	{
		Ext.get('formestprog').dom.style.display='none';
		
	}
	else
	{
		Ext.get('formestprog').dom.style.display='block';
	}
	
}




function getgrid2(numero)
{
	
	var DatosNuevo={"raiz":[{"codemp":'',"codest":'',"codmun":'',"codpai":'',"codpar":'',"codsector":'',"codmanzana":'',"codparcela":'',"despai":'',"desest":'',"denmun":'',"denpar":'',"denominacion":''}]};
	
		
			RecordDef = Ext.data.Record.create([
			{name: 'codemp'},     
			{name: 'codmun'},
			{name: 'codpai'},
			{name: 'codpar'},
			{name: 'codest'},
			{name: 'codsector'},
			{name: 'codmanzana'},
			{name: 'codparcela'},
			{name: 'despai'},
			{name: 'desest'},
			{name: 'denmun'},
			{name: 'denpar'},
			{name: 'denominacion'}
				// This field will use "occupation" as the mapping.
			]);	
	switch(numero)
	{
			case 2:
			DataStore2 =  new Ext.data.Store({
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
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStore2,
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'codest',editor: new Ext.form.NumberField({allowBlank: false})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'desest',editor: new Ext.form.NumberField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
				grid2.addListener('cellclick', click_extra);
				grid2.render('grid1');
				break
			case 3:
			DataStore3 =  new Ext.data.Store({
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
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStore3,
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'codmun',editor: new Ext.form.NumberField({allowBlank: false})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denmun',editor: new Ext.form.NumberField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
        grid3.addListener('cellclick', click_extra);
		grid3.render('grid2');
		break
		case 4:
			DataStore4 =  new Ext.data.Store({
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
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStore4,
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'codpar',editor: new Ext.form.NumberField({allowBlank: false})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denpar',editor: new Ext.form.NumberField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
		grid4.addListener('cellclick', click_extra);
		grid4.render('grid3');
		break
		case 5:
			DataStore5 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDef
			     
			      ),
				data: DatosNuevo
                        });			
			 grid5 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStore5,
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'codsector',editor: new Ext.form.NumberField({allowBlank: false})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.NumberField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
            
		grid5.addListener('cellclick', click_extra);	
		grid5.render('grid4');
		break
			case 6:
			DataStore6 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDef
			     
			      ),
				data: DatosNuevo
                        });			
			 grid6 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStore6,
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'codmanzana',editor: new Ext.form.NumberField({allowBlank: false})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.NumberField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
            
		grid6.addListener('cellclick', click_extra);	
		grid6.render('grid5');
		break

	
			case 7:
			DataStore7 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDef
			      ),
				data: DatosNuevo
                        });			
			 grid7 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStore7,
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'codparcela',editor: new Ext.form.NumberField({allowBlank: false})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.NumberField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
            
		grid7.addListener('cellclick', click_extra);	
		grid7.render('grid6');
		break
	}   		  		
}
	


function getgrid(numero)
{
	
	Auxnum = numero;
	var myJSONString ="{'oper': 'catestpro', 'numest':"+numero+",'codpai': '','despai': ''";

	if(parseInt(Auxnum)>1)
	{
		myJSONString = myJSONString +",'codest':''"; 
		myJSONString = myJSONString +",'codmun':''";
		myJSONString = myJSONString +",'codpar':''";
		myJSONString = myJSONString +",'codsector':''";
		myJSONString = myJSONString +",'codmanzana':''";
		myJSONString = myJSONString +",'codparcela':''";
	}
	
	myJSONString = myJSONString+"}";
	aux = eval('(' + myJSONString + ')');
	ObjSon=JSON.stringify(aux);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum);
	Ext.Ajax.request({
	url : ruta,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) 
	{ 
		 datos = resultado.responseText;
		// alert(datos);
		var DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
		//alert(DatosNuevo)	
		
		}
		else
		{
		
			var DatosNuevo={"raiz":[{"codemp":'',"codest":'',"codmun":'',"codpai":'',"codpar":'',"codsector":'',"codmanzana":'',"codparcela":'',"despai":'',"desest":'',"denmun":'',"denpar":'',"denominacion":''}]};
		}
		
			RecordDef = Ext.data.Record.create([
			{name: 'codemp'},     
			{name: 'codmun'},
			{name: 'codpai'},
			{name: 'codpar'},
			{name: 'codsector'},
			{name: 'codmanzana'},
			{name: 'codparcela'},
			{name: 'despai'},
			{name: 'desest'},
			{name: 'denmun'},
			{name: 'denpar'},
			{name: 'denominacion'}
				// This field will use "occupation" as the mapping.
			]);
			switch(numero)
			{
			case 1:
			DataStore1 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDef
			     
			      ),
				data: DatosNuevo
                        });
			 grid1 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStore1,
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                            {header: "Código", width: 50, sortable: true,dataIndex: 'codpai',editor: new Ext.form.NumberField({allowBlank: false})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'despai',editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:true}),
                        viewConfig:{
                            forceFit:true
                        },
			//autoHeight:true,
			stripeRows: true
            });
				grid1.addListener('cellclick', click_extra);
				grid1.render('grid0');
				break;
				
	}   		  		
 }
	
});	

}

function ActualizarData(cod1,cod2,cod3,cod4,nivel)
{

	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": nivel,
		"cod1": cod1,
		"cod2": cod2,	
		"cod3": cod3, 
		"cod4": cod4
		};

ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	  Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;

		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			if(DatosNuevo.raiz!=null)
			{
				switch(nivel)
				{
					case '2':
					grid2.store.loadData(DatosNuevo);
					break;
					case '3':
					grid3.store.loadData(DatosNuevo);
					break;
					case '4':
					grid4.store.loadData(DatosNuevo);
					break;
					case '5':
					grid5.store.loadData(DatosNuevo);
					break;
	
				}
			}
			
		}
	
}
});
	
}


function agregarTab(titulo,Elemento)
{
        tabs.add({
        title: titulo,
        listeners: {activate: ManejarTabActivo},
        contentEl: Elemento,
        id:Elemento.substr(Elemento.length-1,1),
        closable:false
        }).show();
}
				
function getobject()
{
	   	Ext.QuickTips.init(); 
		 tabs= new Ext.TabPanel
		(
        {
            //baseCls:'x-plain',
			renderTo: 'tabs7',
			 //activeTab: 0,
			 		frame:true,
				    autoScroll:true,
                    width:800,
                    height:500,
                    enableTabScroll:true,
				    style:'margin-left:120px;margin-top:40px;overflow:scroll',
                    plain: false
		    		,defaults: {frame:true, width:800, height: 200}
                  
            });	
}			
Ext.get('BtnGrabar').on('click', function()
{
	tabActual = tabs.getActiveTab().id;
	Nivel = parseInt(tabActual)+1;
	if(Oper=="incluyendo")
	{
		eve = 'incluirestpro';
		Mens = 'Incluido';
	}
	else
	{
		eve = 'actualizarvarios';
		Mens = 'Modificado';
	}
		


	switch(Nivel)
	{
	case 1:
	numDatos = DataStore1.getModifiedRecords();
	//alert(numDatos[0]);
	var reg = "{'oper':'"+ eve + "','numest':'1','datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
		if(i==0)
		{
				reg = reg + "{'CODEMP':'0001','CODEST1':'" + numDatos[i].get('CODEST1') +"','ESTCLA_P':'T','ANO_PRESUPUESTO':'2008','CODUAC':'UU','DENEST1':'" + numDatos[i].get('DENEST1') +"'}";
		
		}	
		else
		{
	reg = reg + ",{'CODEMP':'0001','CODEST1':'" + numDatos[i].get('CODEST1') +"','ESTCLA_P':'T','ANO_PRESUPUESTO':'2008','CODUAC':'UU','DENEST1':'" + numDatos[i].get('DENEST1') +"'}";
		}
			
	}
	reg = reg + "]}";
	break;
	case 2:
	numDatos = DataStore2.getModifiedRecords();
	//alert(numDatos[0]);
	var reg = "{'oper':'"+ eve + "','numest':'2','datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
		if(i==0)
		{
			
		reg = reg + "{'CODEMP':'0001','CODEST1':'" + valor1 +"','CODEST2':'" + numDatos[i].get('CODEST2') +"','ESTCLA_P':'T','ANO_PRESUPUESTO':'2008','CODUAC':'UU','DENEST2':'" + numDatos[i].get('DENEST2') +"'}";
		
		}	
		else
		{
			
reg = reg + ",{'CODEMP':'0001','CODEST1':'" + valor1 +"','CODEST2':'" + numDatos[i].get('CODEST2') +"','ESTCLA_P':'T','ANO_PRESUPUESTO':'2008','CODUAC':'UU','DENEST2':'" + numDatos[i].get('DENEST2') +"'}";

		
		}
			
	}
	reg = reg + "]}";
	break;
	case 3:
	numDatos = DataStore3.getModifiedRecords();
	var reg = "{'oper':'"+ eve + "','numest':"+Nivel+",'datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
		if(i==0)
		{
			
		reg = reg + "{'CODEMP':'0001','CODEST1':'" + valor1 +"','CODEST2':'" + valor2 +"','CODEST3':'" + numDatos[i].get('CODEST3') +"','ESTCLA_P':'T','ANO_PRESUPUESTO':'2008','CODUAC':'UU','DENEST3':'" + numDatos[i].get('DENEST3') +"'}";
		
		}	
		else
		{
			
		reg = reg + ",{'CODEMP':'0001','CODEST1':'" + valor1 +"','CODEST2':'" + valor2 +"','CODEST3':'" + numDatos[i].get('CODEST3') +"','ESTCLA_P':'T','ANO_PRESUPUESTO':'2008','CODUAC':'UU','DENEST3':'" + numDatos[i].get('DENEST3') +"'}";

		
		}
			
	}
	reg = reg + "]}";
	break;
	case 4:
	numDatos = DataStore4.getModifiedRecords();
	var reg = "{'oper':'"+ eve + "','numest':'4','datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
		if(i==0)
		{
			
		reg = reg + "{'CODEMP':'0001','CODEST1':'" + valor1 +"','CODEST2':'" + valor2 +"','CODEST3':'" + valor3 +"','CODEST4':'" + numDatos[i].get('CODEST4') +"','ESTCLA_P':'T','ANO_PRESUPUESTO':'2008','CODUAC':'UU','DENEST4':'" + numDatos[i].get('DENEST4') +"'}";
		
		}	
		else
		{
			
		reg = reg + ",{'CODEMP':'0001','CODEST1':'" + valor1 +"','CODEST2':'" + valor2 +"','CODEST3':'" + valor3 +"','CODEST4':'" + numDatos[i].get('CODEST4') +"','ESTCLA_P':'T','ANO_PRESUPUESTO':'2008','CODUAC':'UU','DENEST4':'" + numDatos[i].get('DENEST4') +"'}";

		
		}
			
	}
	reg = reg + "]}";
	break;
	case 5:
	numDatos = DataStore5.getModifiedRecords();
	//alert(numDatos[0]);
	var reg = "{'oper':'"+ eve + "','numest':"+Nivel+",'datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
		if(i==0)
		{
			
		reg = reg + "{'CODEMP':'0001','CODEST1':'" + valor1 +"','CODEST2':'" + valor2 +"','CODEST3':'" + valor3 +"','CODEST4':'" +valor4 +"','CODEST5':'" + numDatos[i].get('CODEST5') +"','ESTCLA_P':'T','ANO_PRESUPUESTO':'2008','CODUAC':'UU','DENEST5':'" + numDatos[i].get('DENEST5') +"'}";
		
		}	
		else
		{
			
reg = reg + ",{'CODEMP':'0001','CODEST1':'" + valor1 +"','CODEST2':'" + valor2 +"','CODEST3':'" + valor3 +"','CODEST4':'" +valor4 +"','CODEST5':'" + numDatos[i].get('CODEST5') +"','ESTCLA_P':'T','ANO_PRESUPUESTO':'2008','CODUAC':'UU','DENEST5':'" + numDatos[i].get('DENEST5') +"'}";

		
		}
			
	}
	reg = reg + "]}";
	break;

}
	
	Obj= eval('(' + reg + ')');
	ObjSon=JSON.stringify(Obj);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultad, request ){ 
                datos = resultad.responseText;
	//	alert(datos);
		 var Registros = datos.split("|");
		Cod = Registros[1];
		if(Cod!='')
		{
			Ext.MessageBox.alert('Mensaje', 'Registro '+ Mens +' con exito ');
			oper='';		
			getobject();
		}
		else
		{
			Ext.MessageBox.alert('Mensaje', 'El registro con cota ');
						
		}
      },
	failure: function ( result, request)
	 { 
		Ext.MessageBox.alert('Error', result.responseText); 
	} 
      });
 	
	Oper='';
});


 Ext.get('BtnNuevo').on('click', function()
 {			

 	tabActual = tabs.getActiveTab().id;
 //	alert(tabActual);
if(Oper!="incluyendo")
{
 	switch(tabActual)
 	{
	 case '0':
	 var p = new RecordDef
			 (
	            {
					CODEST1:'',
		            CODEMP: '',
		            ESTCLA_P: '',
					ANO_PRESUPUESTO: '',	
					CODUAC: '',
					DENEST1: ''            
				}
	                   
	          );
	              
	    next = DataStore1.getCount();         
	    DataStore1.insert(next, p);	
		grid1.startEditing(next, 1);
		Oper="incluyendo";	
		break;
	case '1':
	 var p = new RecordDef
			 (
	            {
					CODEST1:'',
					CODEST2:'',
		            CODEMP: '',
		            ESTCLA_P: '',
					ANO_PRESUPUESTO: '',	
					CODUAC: '',
					DENEST2: ''            
				}
	                   
	          );
	              
	    next = DataStore2.getCount();         
	    DataStore2.insert(next, p);	
		grid2.startEditing(next, 1);
		Oper="incluyendo";	
		break;
	case '2':
	 var p = new RecordDef
			 (
	            {
					CODEST1:'',
					CODEST2:'',
					CODEST3:'',
		            CODEMP: '',
		            ESTCLA_P: '',
					ANO_PRESUPUESTO: '',	
					CODUAC: '',
					DENEST3: ''            
				}
	                   
	          );
	              
	    next = DataStore3.getCount();         
	    DataStore3.insert(next, p);	
		grid3.startEditing(next, 1);
		Oper="incluyendo";	
		break;
	 case '3':
	 var p = new RecordDef
			 (
	            {
					CODEST1:'',
					CODEST2:'',
					CODEST3:'',
					CODEST4:'',
		            CODEMP: '',
		            ESTCLA_P: '',
					ANO_PRESUPUESTO: '',	
					CODUAC: '',
					DENEST4: ''            
				}
	                   
	          );
	              
	    next = DataStore4.getCount();         
	    DataStore4.insert(next, p);	
		grid4.startEditing(next, 1);
		Oper="incluyendo";	
		break;
		case '4':
	 var p = new RecordDef
			 (
	            {
					CODEST1:'',
					CODEST2:'',
					CODEST3:'',
					CODEST4:'',
					CODEST5:'',
		            CODEMP: '',
		            ESTCLA_P: '',
					ANO_PRESUPUESTO: '',	
					CODUAC: '',
					DENEST5: ''            
				}
	                   
	          );
	              
	    next = DataStore5.getCount();         
	    DataStore5.insert(next, p);	
		grid5.startEditing(next, 1);
		Oper="incluyendo";	
		break;

		

		
 }	 
}	
});


Ext.get('BtnElim').on('click',function()
{
	tabActual = tabs.getActiveTab().id;
	Nivel = parseInt(tabActual)+1;
	switch(Nivel)
	{
	case 1:
	valor1 = grid1.getSelectionModel().getSelected().get('CODEST1');
	//alert(numDatos[0]);
	var reg = "{'oper':'eliminar','numest':'1','datos':[";
	reg = reg + "{'CODEMP':'0001','CODEST1':'" + valor1 +"','ESTCLA_P':'T','ANO_PRESUPUESTO':'2008','CODUAC':'UU','DENEST1':'888'}";
	
	reg = reg + "]}";
	break;
	case 2:
	valor2 = grid2.getSelectionModel().getSelected().get('CODEST2');
	var reg = "{'oper':'eliminar','numest':'2','datos':[";
	reg = reg + "{'CODEMP':'0001','CODEST1':'" + valor1 +"','CODEST2':'" + valor2 +"','ESTCLA_P':'T','ANO_PRESUPUESTO':'2008','CODUAC':'UU','DENEST1':'888'}";
	reg = reg + "]}";	
	break;
	case 3:
	valor3 = grid3.getSelectionModel().getSelected().get('CODEST3');
	var reg = "{'oper':'eliminar','numest':'3','datos':[";
	reg = reg + "{'CODEMP':'0001','CODEST1':'" + valor1 +"','CODEST2':'" + valor2 +"',,'CODEST3':'" + valor3 +"','ESTCLA_P':'T','ANO_PRESUPUESTO':'2008','CODUAC':'UU','DENEST1':'888'}";
	reg = reg + "]}";	
	break;
	case 4:
	valor4 = grid4.getSelectionModel().getSelected().get('CODEST4');
	var reg = "{'oper':'eliminar','numest':'4','datos':[";
	reg = reg + "{'CODEMP':'0001','CODEST1':'" + valor1 +"','CODEST2':'" + valor2 +"',,'CODEST3':'" + valor3 +"','CODEST4':'" + valor4 +"','ESTCLA_P':'T','ANO_PRESUPUESTO':'2008','CODUAC':'UU','DENEST1':'888'}";
	reg = reg + "]}";	
	break;
	case 5:
	valor5 = grid5.getSelectionModel().getSelected().get('CODEST5');
	var reg = "{'oper':'eliminar','numest':'5','datos':[";
	reg = reg + "{'CODEMP':'0001','CODEST1':'" + valor1 +"','CODEST2':'" + valor2 +"',,'CODEST3':'" + valor3 +"','CODEST4':'" + valor4 +"','CODEST5':'" + valor5 +"','ESTCLA_P':'T','ANO_PRESUPUESTO':'2008','CODUAC':'UU','DENEST1':'888'}";
	reg = reg + "]}";	
	break;
}	

	var Result;
	Ext.MessageBox.confirm('Confirmar', '¿Desea eliminar este registro?', Result);
	function Result(btn)
	{
		
	//	alert('sss');
		if(btn=='yes')
		{
			//alert('ss');
		
			parametros = 'ObjSon='+reg;    
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
					
				 }
				 else
				 {
				  Ext.MessageBox.alert('Error', 'No se pudo eliminar el archivo');
				 }
			},
			failure: function ( result, request){ 
				Ext.MessageBox.alert('Error', result.responseText); 
			} 
		      });

		}
	
	};
//	}
    });






getobject();
getDatos('getSesion');
//getNombreEtiquetas();
	
});

 
              
             




