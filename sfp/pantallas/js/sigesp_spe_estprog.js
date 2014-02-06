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
var valor4='';
var DataStore1='';
var DataStore2='';
var DataStore3='';
var DataStore4='';
var DataStore5='';
var mijson='';
var Listo1 = false;
var Listo2 = false;
var Oper='';
var DatosNuevo ="";
var tabs='';
var filaActual='';
 
ruta ='../../procesos/sigesp_spe_estprogpr.php';
pantalla ='sigesp_spe_estprog.php';

Ext.onReady(function(){
ObtenerSesion(ruta,pantalla)


function getDatos(Metodo)
{
	var myJSONObject ={
		"oper": Metodo, 
		"numest":'1',
		"codest": "", 
		"denest": "",
		"numcar":""
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
			mijson = eval('(' + jsonserv + ')');
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

Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank2.php';
})

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
	valor1= grid1.getSelectionModel().getSelected().get('codest1');
	den1= grid1.getSelectionModel().getSelected().get('denest1');
	tabanterior = tabs.getItem('0').title;
	Ext.get('nivel1').dom.innerHTML=tabanterior +':';
	Ext.get('valornivel1').dom.innerHTML=valor1+'-'+den1;
	Ext.get('nivel2').dom.innerHTML='';
	Ext.get('valornivel2').dom.innerHTML='';
	if(grid2!='')
	{
	//	habilitarUna(parseInt(tab.id));	
		ActualizarData(valor1,'0','3','4','2');
	}
	
	MostrarForma(true);
	break;
	case 2:
	valor2= grid2.getSelectionModel().getSelected().get('codest2');
	den2= grid2.getSelectionModel().getSelected().get('denest2');
	//alert(valor2);
	tabanterior = tabs.getItem('1').title;
	Ext.get('nivel2').dom.innerHTML=tabanterior+':';
	Ext.get('valornivel2').dom.innerHTML=valor2+'-'+den2;
	Ext.get('nivel3').dom.innerHTML='';
	Ext.get('valornivel3').dom.innerHTML='';
	if(grid3!='')
	{
		deshabilitarAnt(2);
		ActualizarData(valor1,valor2,'3','4','3');
	}
	MostrarForma(true);
	break;
	case 3:
	//alert('2');
	valor3= grid3.getSelectionModel().getSelected().get('codest3');
	den3= grid3.getSelectionModel().getSelected().get('denest3');
	tabanterior = tabs.getItem('2').title;
	Ext.get('nivel3').dom.innerHTML=tabanterior+':';
	Ext.get('valornivel3').dom.innerHTML=valor3+'-'+den3;
	Ext.get('nivel4').dom.innerHTML='';
	Ext.get('valornivel4').dom.innerHTML='';
	if(grid4!='')
	{
		deshabilitarAnt(3);
		ActualizarData(valor1,valor2,valor3,'0','4');
	}
	MostrarForma(true);
	break;
	case 4:
	//alert('2');
	valor4= grid4.getSelectionModel().getSelected().get('codest4');
	den4= grid4.getSelectionModel().getSelected().get('denest4');
	//alert(valor2);
	tabanterior = tabs.getItem('3').title;
	Ext.get('nivel4').dom.innerHTML=tabanterior+':';
	Ext.get('valornivel4').dom.innerHTML=valor4+'-'+den4;
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
	UltiActual = cantidad-1;
	for(var r=0;r<cantidad;r++)
	{
		num2 = r+1;
		if(r==tab)
		{	
			
			if(r>0)
			{
				if(r==UltiActual)
				{
					tabs.getItem(r-1).enable();
				}
				else
				{
					tabs.getItem(num2).enable();
					tabs.getItem(r-1).enable();
					r++;
				}
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

function getgrid(numero)
{
	Auxnum = numero;
	var myJSONString ="{'oper': 'catestpro', 'numest':"+numero+",'codest"+numero+"': '','denest"+numero+"': ''";

	if(parseInt(Auxnum)>1)
	{
		for(var ind=1;ind<Auxnum;ind++)
		{
			myJSONString = myJSONString +",'codest"+ind+"':''"; 
		}
	}
	
	myJSONString = myJSONString+"}";	
	//alert(myJSONString);

//	aux = eval('(' + myJSONString + ')');
//	ObjSon=JSON.stringify(aux);
//	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
//	Listo1=numero;
//	Ext.Ajax.request({
//	url : ruta,
//	disableCaching:false,
//	params : parametros,
//	method: 'POST',
//	success: function ( resultado, request ) 
//	{ 
//		 datos = resultado.responseText;
		// alert(datos);
//		var DatosNuevo = eval('(' + datos + ')');
	//	 if(DatosNuevo.raiz!=null)
	//	{			
			//alert(DatosNuevo)	
	//	}
	//	else
	//	{
	//		var DatosNuevo={"raiz":[{"codemp":'',"codest1":'',"codest2":'',"codest3":'',"codest4":'',"codest5":'',"estcla_p":'',"ano_presupuesto":'',"denest1":'',"denest2":'',"denest3":'',"denest4":'',"denest5":''}]};
	//	}
		
			RecordDef = Ext.data.Record.create([
			{name: 'codemp'},     // "mapping" property not needed if it's the same as "name"
			{name: 'codest1'},
			{name: 'codest2'},
			{name: 'codest3'},
			{name: 'codest4'},
			{name: 'codest5'},
			{name: 'estcla_p'},
			{name: 'ano_presupuesto'},
			{name: 'coduac'},
			{name: 'denest1'},
			{name: 'denest2'},
			{name: 'denest3'},
			{name: 'denest4'},
			{name: 'denest5'}
				// This field will use "occupation" as the mapping.
			]);
			
			switch(numero)
			{
				case 1:
				ActualizarData('0','0','3','4','1');
				DataStore1 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDef
			     
			      )
                        });
			 grid1 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStore1,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 200, sortable: true,dataIndex: 'codest'+numero,editor: new Ext.form.TextField({allowBlank: false,enableKeyEvents:true,listeners: {
        'keypress':function(Obj,e){
    	    var whichCode = e.keyCode; 
    	    if (whichCode == 13)  
			{		 
				 grid1.startEditing(filaActual,1);
			
			}
    }
  }
})},
                            {header: "Denominación", width: 550, sortable: true, dataIndex: 'denest'+numero,editor: new Ext.form.TextField({allowBlank: false,id:'den1'})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:true}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
			grid1.addListener('cellclick', click_extra);
			grid1.on('afteredit', function(Obj){
	    	if(Obj.value!='' && Obj.field=='codest1')
	    	{
	    	  Auxvalor1 = Obj.value;
		      Auxvalor = ue_rellenarcampo(Auxvalor1,mijson.raiz[0].numcar);
		      grid1.getSelectionModel().getSelected().set('codest1',Auxvalor);
		     // alert('you changed the text of this input field');
	      	}
	    }
	    )
				
				
				
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
			     
			      )
			      //,
				//data: DatosNuevo
              });
			 grid2 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStore2,
                        cm: new Ext.grid.ColumnModel([
                        {header: "Código", width: 200, sortable: true,dataIndex: 'codest'+numero,editor: new Ext.form.TextField({allowBlank: false,enableKeyEvents:true,listeners: {
     'keypress':function(Obj,e){
    	    var whichCode = e.keyCode; 
    	    if (whichCode == 13)  
			{		 
				 grid2.startEditing(filaActual,1);
			
			}
    }
    
    
  }
})},
                            {header: "Denominación", width: 550, sortable: true, dataIndex: 'denest'+numero,editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
            grid2.on('afteredit', function(Obj){
	    	if(Obj.value!='' && Obj.field=='codest2')
	    	{
	    	  Auxvalor1 = Obj.value;
		      Auxvalor = ue_rellenarcampo(Auxvalor1,mijson.raiz[1].numcar);
		      grid2.getSelectionModel().getSelected().set('codest2',Auxvalor);
		     // alert('you changed the text of this input field');
	      	}
	    }
	    )
            
				grid2.addListener('cellclick', click_extra);
				grid2.render('grid1');
				
				break
			case 3:
			DataStore3 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    }
			    ,
                RecordDef
			      )
             });
			 grid3 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStore3,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 200, sortable: true,dataIndex: 'codest'+numero,editor: new Ext.form.TextField({allowBlank: false,enableKeyEvents:true,listeners: {
  
     'keypress':function(Obj,e){
    	    var whichCode = e.keyCode; 
    	    if (whichCode == 13)  
			{		 
				 grid3.startEditing(filaActual,1);
			
			}
    }
    
  }
})},
                            {header: "Denominación", width: 600, sortable: true, dataIndex: 'denest3',editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
         grid3.on('afteredit', function(Obj){
	    	if(Obj.value!='' && Obj.field=='codest3')
	    	{
	    	  Auxvalor1 = Obj.value;
		      Auxvalor = ue_rellenarcampo(Auxvalor1,mijson.raiz[2].numcar);
		      grid1.getSelectionModel().getSelected().set('codest3',Auxvalor);
		     // alert('you changed the text of this input field');
	      	}
	    }
	    )    
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
			     
			      )
             });
			 grid4 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStore4,
                        cm: new Ext.grid.ColumnModel([
						{header: "Código", width: 200, sortable: true,dataIndex: 'codest'+numero,editor: new Ext.form.TextField({allowBlank: false,enableKeyEvents:true,listeners: {
   
  'keypress':function(Obj,e){
    	    var whichCode = e.keyCode; 
    	    if (whichCode == 13)  
			{		 
				 grid4.startEditing(filaActual,1);
			
			}
    }
  }
})},
                            {header: "Denominación", width: 600, sortable: true, dataIndex: 'denest'+numero,editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
		grid4.addListener('cellclick', click_extra);
		 grid4.on('afteredit', function(Obj){
	    	if(Obj.value!='' && Obj.field=='codest4')
	    	{
	    	  Auxvalor1 = Obj.value;
		      Auxvalor = ue_rellenarcampo(Auxvalor1,mijson.raiz[3].numcar);
		      //alert(Auxvalor);
		      grid4.getSelectionModel().getSelected().set('codest4',Auxvalor);
		     // alert('you changed the text of this input field');
	      	}
	    }
	    )    
		
		
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
			     
			      )
              });			
			 grid5 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStore5,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 200, sortable: true,dataIndex: 'codest'+numero,editor: new Ext.form.TextField({allowBlank: false,enableKeyEvents:true,listeners: {
       'keypress':function(Obj,e){
    	    var whichCode = e.keyCode; 
    	    if (whichCode == 13)  
			{		 
				 grid5.startEditing(filaActual,1);
			
			}
    	}
    
  }
})},
                            {header: "Denominación", width: 600, sortable: true, dataIndex: 'denest'+numero,editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
        	grid5.on('afteredit', function(Obj){
	    	if(Obj.value!='' && Obj.field=='codest5')
	    	{
	    	  Auxvalor1 = Obj.value;
		      Auxvalor = ue_rellenarcampo(Auxvalor1,mijson.raiz[4].numcar);
		      grid5.getSelectionModel().getSelected().set('codest5',Auxvalor);
		     // alert('you changed the text of this input field');
	      	}
	    }
	    )    
            
		grid5.addListener('cellclick', click_extra);	
		grid5.render('grid4');
		break
		}	  		
 //}
//}
//);	

}

function ActualizarData(cod1,cod2,cod3,cod4,nivel)
{
	//alert('fgdf');
	cod1=ue_rellenarcampo(cod1,25);
	cod2=ue_rellenarcampo(cod2,25);
	cod3=ue_rellenarcampo(cod3,25);
	cod4=ue_rellenarcampo(cod4,25);
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": nivel,
		"cod1": cod1,
		"cod2": cod2,	
		"cod3": cod3, 
		"cod4": cod4
	};

	ObjSon=JSON.stringify(myJSONObject);
	//alert(ObjSon);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
		  //alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			if(DatosNuevo.raiz==null)
			{
					 DatosNuevo={"raiz":[{"codemp":'',"codest1":'',"codest2":'',"codest3":'',"codest4":'',"codest5":'',"ESTCLA_P":'',"ano_presupuesto":'',"denest1":'',"denest2":'',"denest3":'',"denest4":'',"denest5":''}]};
			}
							
				switch(nivel)
				{
					case '1':
						grid1.store.loadData(DatosNuevo);
						break;
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
				    style:'margin-left:120px;margin-top:40px',
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
		if(cantidad==Nivel)
		{
			eve = 'incluirUltimo';
		}
		else
		{
			eve = 'incluirestpro';
		}
		
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
		codest1 = ue_rellenarcampo(numDatos[i].get('codest1'),25)
		if(i==0)
		{
			reg = reg + "{'codemp':'0001','codest1':'" + codest1 +"','ESTCLA_P':'T','ano_presupuesto':'2008','CODUAC':'UU','denest1':'" + numDatos[i].get('denest1') +"'}";
		}	
		else
		{
			reg = reg + ",{'codemp':'0001','codest1':'" + codest1 +"','ESTCLA_P':'T','ano_presupuesto':'2008','CODUAC':'UU','denest1':'" + numDatos[i].get('denest1') +"'}";
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
		valor1 = ue_rellenarcampo(valor1,25);
		codest2 = ue_rellenarcampo(numDatos[i].get('codest2'),25);
		if(i==0)
		{
			
		reg = reg + "{'codemp':'0001','codest1':'" + valor1 +"','codest2':'" + codest2 +"','ESTCLA_P':'T','ano_presupuesto':'2008','CODUAC':'UU','denest2':'" + numDatos[i].get('denest2') +"'}";
		
		}	
		else
		{
			
reg = reg + ",{'codemp':'0001','codest1':'" + valor1 +"','codest2':'" + codest2 +"','ESTCLA_P':'T','ano_presupuesto':'2008','CODUAC':'UU','denest2':'" + numDatos[i].get('denest2') +"'}";		
		}
			
	}
	reg = reg + "]}";
	break;
	case 3:
	numDatos = DataStore3.getModifiedRecords();
	var reg = "{'oper':'"+ eve + "','numest':"+Nivel+",'datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
		valor1 = ue_rellenarcampo(valor1,25);
		valor2 = ue_rellenarcampo(valor2,25);
		codest3 = ue_rellenarcampo(numDatos[i].get('codest3'),25);
		if(i==0)
		{
			
			reg = reg + "{'codemp':'0001','codest1':'" + valor1 +"','codest2':'" + valor2 +"','codest3':'" + codest3 +"','ESTCLA_P':'T','ano_presupuesto':'2008','CODUAC':'UU','denest3':'" + numDatos[i].get('denest3') +"'}";
		
		}	
		else
		{
			
			reg = reg + ",{'codemp':'0001','codest1':'" + valor1 +"','codest2':'" + valor2 +"','codest3':'" + codest3 +"','ESTCLA_P':'T','ano_presupuesto':'2008','CODUAC':'UU','denest3':'" + numDatos[i].get('denest3') +"'}";

		
		}
			
	}
	reg = reg + "]}";
	break;
	case 4:
	numDatos = DataStore4.getModifiedRecords();
	var reg = "{'oper':'"+ eve + "','numest':'4','datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
		valor1 = ue_rellenarcampo(valor1,25);
		valor2 = ue_rellenarcampo(valor2,25);
		valor3 = ue_rellenarcampo(valor3,25);
		codest4 = ue_rellenarcampo(numDatos[i].get('codest4'),25);
		if(i==0)
		{
			
		reg = reg + "{'codemp':'0001','codest1':'" + valor1 +"','codest2':'" + valor2 +"','codest3':'" + valor3 +"','codest4':'" + codest4 +"','ESTCLA_P':'T','ano_presupuesto':'2008','CODUAC':'UU','denest4':'" + numDatos[i].get('denest4') +"'}";
		
		}	
		else
		{
			
		reg = reg + ",{'codemp':'0001','codest1':'" + valor1 +"','codest2':'" + valor2 +"','codest3':'" + valor3 +"','codest4':'" + codest4 +"','ESTCLA_P':'T','ano_presupuesto':'2008','CODUAC':'UU','denest4':'" + numDatos[i].get('denest4') +"'}";

		
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
		valor1 = ue_rellenarcampo(valor1,25);
		valor2 = ue_rellenarcampo(valor2,25);
		valor3 = ue_rellenarcampo(valor3,25);
		valor4 = ue_rellenarcampo(valor4,25);
		codest5 = ue_rellenarcampo(numDatos[i].get('codest5'),25);
		
		if(i==0)
		{
			
		reg = reg + "{'codemp':'0001','codest1':'" + valor1 +"','codest2':'" + valor2 +"','codest3':'" + valor3 +"','codest4':'" +valor4 +"','codest5':'" + codest5 +"','ESTCLA_P':'T','ano_presupuesto':'2008','CODUAC':'UU','denest5':'" + numDatos[i].get('denest5') +"'}";
		
		}	
		else
		{
			
reg = reg + ",{'codemp':'0001','codest1':'" + valor1 +"','codest2':'" + valor2 +"','codest3':'" + valor3 +"','codest4':'" +valor4 +"','codest5':'" + codest5 +"','ESTCLA_P':'T','ano_presupuesto':'2008','CODUAC':'UU','denest5':'" + numDatos[i].get('denest5') +"'}";

		
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
		//alert(datos);
		 var Registros = datos.split("|");
		Cod = Registros[1];
		if(Cod!='')
		{
			Ext.MessageBox.alert('Mensaje', 'Registro '+ Mens +' con exito ');
			GridActual = ObtenerGrid(tabActual);
			GridActual.store.commitChanges();
			ActualizarData(valor1,valor2,valor3,valor4,Nivel.toString());
			oper='';		
		}
		else
		{
			Ext.MessageBox.alert('Mensaje', 'No se pudo incluir el registro');
						
		}
      },
	failure: function ( result, request)
	 { 
		Ext.MessageBox.alert('Error', result.responseText); 
	} 
      });
 	
	Oper='';
});


function ObtenerGrid(tab)
{
	switch(tab)
	{
		case '0':
			return grid1;
			break;
		case '1':
			return grid2;
			break;
		case '2':
			return grid3;
			break;
		case '3':
			return grid4;
			break;
		case '4':
			return grid5;
			break;
	}    
	
}

Ext.get('BtnNuevo').on('click', function()
{			
tabActual = tabs.getActiveTab().id;
NumeroGrid = parseInt(tabActual) +1;
GridActual = ObtenerGrid(tabActual);
if(Oper!="incluyendo")
{
		 var p = new RecordDef
			 (
	            {
					codest1:'',
					codest2:'',
					codest3:'',
					codest5:'',
					codest4:'',
					denest2: '', 
		            codemp: '',
		            ESTCLA_P: '',
					ano_presupuesto: '',	
					CODUAC: '',
					denest1: '',
					denest2: '',
					denest3: '',
					denest4: '',
					denest5: ''            
				}
	                   
	          );
	              
	    next = GridActual.store.getCount();   
	   // alert(next);
		if(next==1)
		{
			codigo1 = GridActual.store.getRange(0,1);
			codigo2 = codigo1[0].get('codest1');
			if(codigo2=='')
			{
				//GridActual.store.insert(0, p);
				GridActual.startEditing(0, 0);
				GridActual.getSelectionModel().selectRow(0);	
				filaActual=0;
			}
			else
			{
				GridActual.store.insert(1, p);
				GridActual.startEditing(1, 0);	
				GridActual.getSelectionModel().selectRow(1);
				filaActual=1;
			}	
		}else if(next==0)
		{
				GridActual.store.insert(0, p);
				GridActual.startEditing(0, 0);
				GridActual.getSelectionModel().selectRow(0);	
				filaActual=0;			
		}
		else
		{
			
			codigo1 = GridActual.store.getRange(0,1);
			codigo2 = codigo1[1].get('codest1');
			//alert(codigo2);
			if(codigo2=='')
			{
				GridActual.startEditing(1, 0);	
				GridActual.getSelectionModel().selectRow(1);
				filaActual=1;
			}
			else
			{
				GridActual.store.insert(next, p);
				GridActual.startEditing(next,0);
				GridActual.getSelectionModel().selectRow(next);	
				filaActual=next;
			}
		}      
	   	Oper="incluyendo";	
	
}	
});

Ext.get('BtnElim').on('click',function()
{
	tabActual = tabs.getActiveTab().id;
	Nivel = parseInt(tabActual)+1;
	if(cantidad==Nivel)
	{
		eve = 'eliminarUltimo';
	}
	else
	{
		eve = 'eliminar';
	}

	switch(Nivel)
	{
	case 1:
	valor1 = ue_rellenarcampo(grid1.getSelectionModel().getSelected().get('codest1'),25);	
	//alert(numDatos[0]);
	var reg = "{'oper':'"+ eve + "','numest':'1','datos':[";
	reg = reg + "{'codemp':'0001','codest1':'" + valor1 +"','ESTCLA_P':'T','ano_presupuesto':'2008','CODUAC':'UU','denest1':'888'}";
	
	reg = reg + "]}";
	break;
	case 2:
	valor2 = grid2.getSelectionModel().getSelected().get('codest2');
	valor1 = ue_rellenarcampo(valor1,25);
	valor2 = ue_rellenarcampo(valor2,25);
	var reg = "{'oper':'"+ eve + "','numest':'2','datos':[";
	reg = reg + "{'codemp':'0001','codest1':'" + valor1 +"','codest2':'" + valor2 +"','ESTCLA_P':'T','ano_presupuesto':'2008','CODUAC':'UU','denest1':'888'}";
	reg = reg + "]}";	
	break;
	case 3:
	valor3 = grid3.getSelectionModel().getSelected().get('codest3');
	valor1 = ue_rellenarcampo(valor1,25);
	valor2 = ue_rellenarcampo(valor2,25);
	valor3 = ue_rellenarcampo(valor3,25);	
	var reg = "{'oper':'"+ eve + "','numest':'3','datos':[";
	reg = reg + "{'codemp':'0001','codest1':'" + valor1 +"','codest2':'" + valor2 +"',,'codest3':'" + valor3 +"','ESTCLA_P':'T','ano_presupuesto':'2008','CODUAC':'UU','denest1':'888'}";
	reg = reg + "]}";	
	break;
	case 4:
	valor4 = grid4.getSelectionModel().getSelected().get('codest4');
	valor1 = ue_rellenarcampo(valor1,25);
	valor2 = ue_rellenarcampo(valor2,25);
	valor3 = ue_rellenarcampo(valor3,25);	
	valor4 = ue_rellenarcampo(valor4,25);	
	var reg = "{'oper':'"+ eve + "','numest':'4','datos':[";
	reg = reg + "{'codemp':'0001','codest1':'" + valor1 +"','codest2':'" + valor2 +"',,'codest3':'" + valor3 +"','codest4':'" + valor4 +"','ESTCLA_P':'T','ano_presupuesto':'2008','CODUAC':'UU','denest1':'888'}";
	reg = reg + "]}";	
	break;
	case 5:
	valor5 = grid5.getSelectionModel().getSelected().get('codest5');
	valor1 = ue_rellenarcampo(valor1,25);
	valor2 = ue_rellenarcampo(valor2,25);
	valor3 = ue_rellenarcampo(valor3,25);	
	valor4 = ue_rellenarcampo(valor4,25);	
	valor5 = ue_rellenarcampo(valor5,25);
	var reg = "{'oper':'"+ eve + "','numest':'5','datos':[";
	reg = reg + "{'codemp':'0001','codest1':'" + valor1 +"','codest2':'" + valor2 +"',,'codest3':'" + valor3 +"','codest4':'" + valor4 +"','codest5':'" + valor5 +"','ESTCLA_P':'T','ano_presupuesto':'2008','CODUAC':'UU','denest1':'888'}";
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
						//alert(datos);
				 var Registros = datos.split("|");
				 if (Registros[1] == '1')
				 {
					Ext.MessageBox.alert('Mensaje','Registro '+Mensa + ' con éxito');
					ActualizarData(valor1,valor2,valor3,valor4,Nivel.toString())
					
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
            })
            ]
          })



	
});      
             




