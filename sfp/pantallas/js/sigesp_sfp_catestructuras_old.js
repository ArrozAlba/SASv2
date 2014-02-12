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
var valor1='';
var valor2='';
var valor3='';
var DataStore1='';
var DataStore2='';
var DataStore3='';
var DataStore4='';
var DataStore5='';
var Listo1 = false;
var Listo2 = false;
var Oper='';
var DatosNuevo ="";

 
ruta ='../../procesos/sigesp_sfp_estprogpr.php';
//Ext.onReady(function(){
    // basic tabs 1, built from existing content

function getDatos(Metodo)
{
	var myJSONObject ={
		"oper": Metodo, 
		"numest":'1',
		"CODESTPRO": "", 
		"DENESTPRO": ""
};	
	getobjectTabs();
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
		 	//	alert(cantidad);
		 	
		 			for(i=0;i<parseInt(cantidad);i++)
		 			{
		 				
		 				agregarTab(mijson.raiz[i].nombre_pest,'grid'+i);
					
					}
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
if(grid1=='' && Listo1==false)
{	
	getgrid(1);
}
if(tab.id==1 && grid2=='' || tab.id==2 && grid3=='' || tab.id==3 && grid4=='' || tab.id==4 && grid5=='')
{
	getgrid(num);
}
else
{
	//alert('sdd');
switch(parseInt(tab.id))
{
	case 1:
	valor1= grid1.getSelectionModel().getSelected().get('CODESTPRO1');
	den1= grid1.getSelectionModel().getSelected().get('DENESTPRO1');
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
	valor2= grid2.getSelectionModel().getSelected().get('CODESTPRO2');
	den2= grid2.getSelectionModel().getSelected().get('DENESTPRO2');
	//alert(valor2);
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
	valor3= grid3.getSelectionModel().getSelected().get('CODESTPRO3');
	den3= grid3.getSelectionModel().getSelected().get('DENESTPRO3');
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
	valor4= grid4.getSelectionModel().getSelected().get('CODESTPRO4');
	den4= grid4.getSelectionModel().getSelected().get('DENESTPRO4');
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
	//	Ext.get('formestprog').dom.style.display='none';
		
	}
	else
	{
	//	Ext.get('formestprog').dom.style.display='block';
	}
	
}

function getgrid(numero)
{
	
	Auxnum = numero;
	var myJSONString ="{'oper': 'catestpro', 'numest':"+numero+",'CODESTPRO"+numero+"': '','DENESTPRO"+numero+"': ''";

	if(parseInt(Auxnum)>1)
	{
		for(var ind=1;ind<Auxnum;ind++)
		{
			myJSONString = myJSONString +",'CODESTPRO"+ind+"':''"; 
		}
	}
	
	myJSONString = myJSONString+"}";	
	//alert(myJSONString);

	aux = eval('(' + myJSONString + ')');
	ObjSon=JSON.stringify(aux);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Listo1=numero;
	Ext.Ajax.request({
	url : ruta,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) 
	{ 
		 datos = resultado.responseText;
		var DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
		//alert(DatosNuevo)	
		
		}
		else
		{
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODESTPRO1":'',"CODESTPRO2":'',"CODESTPRO3":'',"CODESTPRO4":'',"CODESTPRO5":'',"ESTCLA":'',"ANO_PRESUPUESTO":'',"DENESTPRO1":'',"DENESTPRO2":'',"DENESTPRO3":'',"DENESTPRO4":'',"DENESTPRO5":''}]};
		}
		
			RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     // "mapping" property not needed if it's the same as "name"
			{name: 'CODESTPRO1'},
			{name: 'CODESTPRO2'},
			{name: 'CODESTPRO3'},
			{name: 'CODESTPRO4'},
			{name: 'CODESTPRO5'},
			{name: 'ESTCLA'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENESTPRO1'},
			{name: 'DENESTPRO2'},
			{name: 'DENESTPRO3'},
			{name: 'DENESTPRO4'},
			{name: 'DENESTPRO5'}
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
                            {header: "Código", width: 50, sortable: true,dataIndex: 'CODESTPRO'+numero,editor: new Ext.form.NumberField({allowBlank: false})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'DENESTPRO'+numero,editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:true}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
				grid1.addListener('cellclick', click_extra);
				grid1.render('grid0');
				break
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
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'CODESTPRO'+numero,editor: new Ext.form.NumberField({allowBlank: false})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'DENESTPRO'+numero,editor: new Ext.form.NumberField({allowBlank: false})}
							
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
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'CODESTPRO'+numero,editor: new Ext.form.NumberField({allowBlank: false})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'DENESTPRO3',editor: new Ext.form.NumberField({allowBlank: false})}
							
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
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'CODESTPRO'+numero,editor: new Ext.form.NumberField({allowBlank: false})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'DENESTPRO'+numero,editor: new Ext.form.NumberField({allowBlank: false})}
							
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
                            {header: "Código", width: 50, sortable: true,   dataIndex: 'CODESTPRO'+numero,editor: new Ext.form.NumberField({allowBlank: false})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'DENESTPRO'+numero,editor: new Ext.form.NumberField({allowBlank: false})}
							
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
				
function getobjectTabs()
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
				    style:'margin-left:120px;margin-top:40px',
                    plain: false
		    		,defaults: {frame:true, width:800, height: 200}
                  
            });	
}			




//getDatos('getSesion');
//getNombreEtiquetas();
	
//});

 
              
             




