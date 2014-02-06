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
var valor1='P';
var valor2='';
var valor3='';
var nivel1='';
var nivel2='';
var valor1p='';
var nivel3='';
var nivel4='';
var nivel5='';
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
 
ruta ='../../procesos/sigesp_sfp_esadminpr.php';
pantalla ='sigesp_sfp_esadmin.php';

Ext.onReady(function(){

ObtenerSesion(ruta,pantalla)

function getDatos(Metodo)
{
	var myJSONObject =
	{
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
		 // alert(datos);
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


Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank2.php';
})


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
	valor1= grid1.getSelectionModel().getSelected().get('coduac');
	den1 = grid1.getSelectionModel().getSelected().get('denuac');
	nivel1 = grid1.getSelectionModel().getSelected().get('nivel');
	tabanterior = tabs.getItem('0').title;
	Ext.get('nivel1').dom.innerHTML=tabanterior+':';
	Ext.get('valornivel1').dom.innerHTML=valor1+'-'+den1;
	if(grid2!='')
	{
	//	habilitarUna(parseInt(tab.id));	
		ActualizarData(valor1,nivel1,'2');
	}
	
	MostrarForma(true);
	break;
	case 2:
	valor2= grid2.getSelectionModel().getSelected().get('coduac');
	den2= grid2.getSelectionModel().getSelected().get('denuac');
	nivel2 = grid2.getSelectionModel().getSelected().get('nivel');
	tabanterior = tabs.getItem('1').title;
	Ext.get('nivel2').dom.innerHTML=tabanterior+':';
	Ext.get('valornivel2').dom.innerHTML=valor2+'-'+den2;
	if(grid3!='')
	{
		deshabilitarAnt(2);
		ActualizarData(valor2,nivel2,'3');
	}
	MostrarForma(true);
	break;
	case 3:
	//alert('2');
	valor3= grid3.getSelectionModel().getSelected().get('coduac');
	den3= grid3.getSelectionModel().getSelected().get('denuac');
	nivel3 = grid3.getSelectionModel().getSelected().get('nivel');
	tabanterior = tabs.getItem('2').title;
	Ext.get('nivel3').dom.innerHTML=tabanterior+':';
	Ext.get('valornivel3').dom.innerHTML=valor3+'-'+den3;
	if(grid4!='')
	{
		deshabilitarAnt(3);
		ActualizarData(valor3,nivel1,'4');
	}
	MostrarForma(true);
	break;
	case 4:
	//alert('2');
	valor4= grid4.getSelectionModel().getSelected().get('coduac');
	den4 = grid4.getSelectionModel().getSelected().get('denuac');
	nivel4 = grid4.getSelectionModel().getSelected().get('nivel');
	//alert(valor2);
	tabanterior = tabs.getItem('3').title;
	Ext.get('nivel4').dom.innerHTML=tabanterior+':';
	Ext.get('valornivel4').dom.innerHTML=valor4+'-'+den4;
	if(grid5!='')
	{
		deshabilitarAnt(4);
		ActualizarData(valor4,nivel4,'5');
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



function getgrid2(numero)
{
	var myJSONString ="{'oper': 'catestpro', 'numest':"+numero+",'codpai': '','despai': ''";
		var DatosNuevo={"raiz":[{"coduac":'',"denuac":'',"denuac_p":''}]};
		RecordDef = Ext.data.Record.create([
			{name: 'coduac'},     
			{name: 'denuac'},
			{name: 'denuac_p'},
			{name: 'nivel'}
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
			 height:350,
			 autoScroll:true,
                        border:true,
                        ds:DataStore2,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 100, sortable: true,dataIndex: 'coduac',editor: new Ext.form.TextField({allowBlank: false,enableKeyEvents:true,listeners: {
  'keypress':function(Obj,e){
    	var whichCode = e.keyCode; 
    	if (whichCode == 13)  
		{		
			grid2.startEditing(filaActual,1);
			
		}
    }   
  }
})},
                            {header: "Denominación", width: 600, sortable: true, dataIndex: 'denuac',editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			stripeRows: true
            });
            	grid2.on('afteredit',function(Obj){
		    	if(Obj.value!='' && Obj.field=='coduac')
		    	{
		    	  Auxvalor1 = Obj.value;
			      Auxvalor = ue_rellenarcampo(Auxvalor1,5);
			      grid2.getSelectionModel().getSelected().set('coduac',Auxvalor);
			     
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
			    },
                RecordDef
			     
			      ),
				data: DatosNuevo
                        });
			
			 grid3 = new Ext.grid.EditorGridPanel({
			 width:770,
			 height:350,
			 autoScroll:true,
                        border:true,
                        ds:DataStore3,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 150, sortable: true,dataIndex: 'coduac',editor: new Ext.form.TextField({allowBlank: false,enableKeyEvents:true,listeners: {
   	'keypress':function(Obj,e){
    	var whichCode = e.keyCode; 
    	if (whichCode == 13)  
		{		 
			grid3.startEditing(filaActual,1);
		}
    }
  }
})},
                            {header: "Denominación", width: 500, sortable: true, dataIndex:'denuac',editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
              	grid3.on('afteredit',function(Obj){
		    	if(Obj.value!='' && Obj.field=='coduac')
		    	{
		    	  Auxvalor1 = Obj.value;
			      Auxvalor = ue_rellenarcampo(Auxvalor1,5);
			      grid3.getSelectionModel().getSelected().set('coduac',Auxvalor);
			     
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
			     
			      ),
				data: DatosNuevo
                        });
		
			 grid4 = new Ext.grid.EditorGridPanel({
			 width:770,
			 height:350,
			 autoScroll:true,
                        border:true,
                        ds:DataStore4,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 150, sortable: true,dataIndex: 'coduac',editor: new Ext.form.TextField({allowBlank: false,enableKeyEvents:true,listeners: {
   	'keypress':function(Obj,e){
    	var whichCode = e.keyCode; 
    	if (whichCode == 13)  
		{		 
			grid4.startEditing(filaActual,1);
		}
    }
  }
})},
                            {header: "Denominación", width: 500, sortable: true, dataIndex: 'denuac',editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			stripeRows: true
            });

                grid4.on('afteredit',function(Obj){
		    	if(Obj.value!='' && Obj.field=='coduac')
		    	{
		    	  Auxvalor1 = Obj.value;
			      Auxvalor = ue_rellenarcampo(Auxvalor1,5);
			      grid4.getSelectionModel().getSelected().set('coduac',Auxvalor);
			     
			     // alert('you changed the text of this input field');
		        }
		    	}
		     	)   
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
                            {header: "Código", width: 150, sortable: true,dataIndex: 'coduac',editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'change': function(){
    	if(this.getValue()!='')
    	{
    	  Auxvalor1 = this.getValue();
	      Auxvalor = ue_rellenarcampo(Auxvalor1,5);
	      grid5.getSelectionModel().getSelected().set('coduac',Auxvalor);
	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
  {header: "Denominación", width: 500, sortable: true, dataIndex: 'denuac',editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
            
            
             	grid5.on('afteredit',function(Obj){
		    	if(Obj.value!='' && Obj.field=='coduac')
		    	{
		    	  Auxvalor1 = Obj.value;
			      Auxvalor = ue_rellenarcampo(Auxvalor1,5);
			      grid5.getSelectionModel().getSelected().set('coduac',Auxvalor);
			     
			     // alert('you changed the text of this input field');
		        }
		    	}
		     	)   
            
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
                            {header: "Código", width: 150, sortable: true,dataIndex: 'coduac',editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'change': function(){
    	if(this.getValue()!='')
    	{
    	  Auxvalor1 = this.getValue();
	      Auxvalor = ue_rellenarcampo(Auxvalor1,5);
	      grid6.getSelectionModel().getSelected().set('coduac',Auxvalor);
	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
                            {header: "Denominación", width:500, sortable: true, dataIndex: 'denuac',editor: new Ext.form.NumberField({allowBlank: false})}
							
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
                            {header: "Código", width: 150, sortable: true,dataIndex: 'coduac',editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'change': function(){
    	if(this.getValue()!='')
    	{
    	  Auxvalor1 = this.getValue();
	      Auxvalor = ue_rellenarcampo(Auxvalor1,5);
	      grid7.getSelectionModel().getSelected().set('coduac',Auxvalor);
	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
                            {header: "Denominación", width:500, sortable: true, dataIndex: 'denuac',editor: new Ext.form.TextField({allowBlank: false})}
							
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
		
		myJSONString = myJSONString +",'coduac':''"; 
		myJSONString = myJSONString +",'denuac':''";
		myJSONString = myJSONString +",'denuac_p':''";
	
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
		
			var DatosNuevo={"raiz":[{"coduac":'',"denuac":'',"denuac_p":''}]};
		}
		
			RecordDef = Ext.data.Record.create([
			{name: 'coduac'},     
			{name: 'denuac'},
			{name: 'denuac_p'},
			{name: 'nivel'}
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
			 height:250,
			 autoScroll:true,
                        border:true,
                        ds:DataStore1,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 150, sortable: true,dataIndex: 'coduac',editor: new Ext.form.TextField({allowBlank: false,enableKeyEvents:true,listeners: {
    
      'keypress':function(Obj,e){
    	var whichCode = e.keyCode; 
    	if (whichCode == 13)  
		{		
			grid1.startEditing(filaActual,1);
			
		}
    }
        
  }
})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denuac',editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:true}),
                        viewConfig:{
                            forceFit:true
                        },
			//autoHeight:true,
			stripeRows: true
            });
            
            	grid1.on('afteredit',function(Obj){
		    	if(Obj.value!='' && Obj.field=='coduac')
		    	{
		    	  Auxvalor1 = Obj.value;
			      Auxvalor = ue_rellenarcampo(Auxvalor1,5);
			      grid1.getSelectionModel().getSelected().set('coduac',Auxvalor);
			     
			     // alert('you changed the text of this input field');
		        }
		    	}
		     	)
 				grid1.addListener('cellclick', click_extra);
				grid1.render('grid0');
				break;
				
	}   		  		
 }
	
});	

}

function ActualizarData(cod1,nivel1,nivel)
{
	if(nivel=='1')
	{
		Oper = 'catestpro';
	}
	else
	{
		Oper = 'filtrarEst';
	}
	
	var myJSONObject ={
		"oper": Oper,
		"coduac_p": cod1,
		"nivel_p": nivel1,
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
				var DatosNuevo={"raiz":[{"coduac":'',"denuac":'',"denuac_p":''}]};
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
                    enableTabScroll:true,
				    style:'margin-left:120px;margin-top:40px;overflow:scroll',
                    plain: false
		    		,defaults: {frame:true, width:800, height: 200}
                  
            });	
}			
Ext.get('BtnGrabar').on('click', function()
{
	tabActual  =  tabs.getActiveTab().id;
	GridActual = ObtenerGrid(tabActual); 
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
	NivelActual='';
	ValorActual = '';
	//alert(numDatos[0]);
	var reg = "{'oper':'"+ eve + "','datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
		if(i==0)
		{
			reg = reg + "{'codemp':'0001','coduac':'" + numDatos[i].get('coduac') +"','denuac':'" + numDatos[i].get('denuac')+"','nivel':'"+Nivel+"','coduac_p':'P'}";
		
		}	
		else
		{
			reg = reg + ",{'codemp':'0001','coduac':'" + numDatos[i].get('coduac') +"','denuac':'" + numDatos[i].get('denuac')+"','nivel':'"+Nivel+"','coduac_p':'P'}";

		}
			
	}
	reg = reg + "]}";
	break;
	case 2:
	numDatos = DataStore2.getModifiedRecords();
	ValorActual= valor1;
	NivelActual=nivel1;	
	//alert(numDatos[0]);
	var reg = "{'oper':'"+ eve + "','numest':'2','datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
		if(i==0)
		{
			reg = reg + "{'codemp':'0001','coduac':'" + numDatos[i].get('coduac') +"','denuac':'" + numDatos[i].get('denuac') +"','coduac_p':'"+valor1+"','coduac_pp':'"+valor1p+"','nivel':'"+Nivel+"','nivel_p':'"+nivel1+"'}";
		
		}	
		else
		{
			reg = reg +",{'codemp':'0001','coduac':'" + numDatos[i].get('coduac') +"','denuac':'" + numDatos[i].get('denuac') +"','coduac_p':'"+valor1+"','coduac_pp':'"+valor1p+"','nivel':'"+Nivel+"','nivel_p':'"+nivel1+"'}";

		}	
	}
	reg = reg + "]}";
	break;
	case 3:
	numDatos = DataStore3.getModifiedRecords();
	ValorActual= valor2;
	NivelActual=nivel2;	
	var reg = "{'oper':'"+ eve + "','numest':"+Nivel+",'datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
		if(i==0)
		{
			reg = reg + "{'codemp':'0001','coduac':'" + numDatos[i].get('coduac') +"','denuac':'" + numDatos[i].get('denuac') +"','coduac_p':'"+valor2+"','nivel':'"+Nivel+"','nivel_p':'"+nivel2+"'}";
		
		}	
		else
		{
			reg = reg +",{'codemp':'0001','coduac':'" + numDatos[i].get('coduac') +"','denuac':'" + numDatos[i].get('denuac') +"','coduac_p':'"+valor2+"','nivel':'"+Nivel+"','nivel_p':'"+nivel2+"'}";

		}			
	}
	reg = reg + "]}";
	break;
	case 4:
	numDatos = DataStore4.getModifiedRecords();
	ValorActual= valor3;
	NivelActual=nivel3;	
	var reg = "{'oper':'"+ eve + "','numest':'4','datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
		if(i==0)
		{
			reg = reg + "{'codemp':'0001','coduac':'" + numDatos[i].get('coduac') +"','denuac':'" + numDatos[i].get('denuac') +"','coduac_p':'"+valor3+"','nivel':'"+Nivel+",'nivel_p':'"+nivel3+"'}";
		
		}	
		else
		{
			reg = reg + ",{'codemp':'0001','coduac':'" + numDatos[i].get('coduac') +"','denuac':'" + numDatos[i].get('denuac') +"','coduac_p':'"+valor3+"','nivel':'"+Nivel+"','nivel_p':'"+nivel3+"'}";

		}
			
	}
	reg = reg + "]}";
	break;
	case 5:
	numDatos = DataStore5.getModifiedRecords();
	ValorActual= valor4;
	NivelActual= nivel4;	
	//alert(numDatos[0]);
	var reg = "{'oper':'"+ eve + "','numest':"+Nivel+",'datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
	if(i==0)
	{
			reg = reg + "{'codemp':'0001','coduac':'" + numDatos[i].get('coduac') +"','denuac':'" + numDatos[i].get('denuac') +"','coduac_p':'"+valor4+"','nivel':'"+Nivel+"','nivel_p':'"+nivel4+"'}";
		
	}	
	else
	{
		reg = reg + ",{'codemp':'0001','coduac':'" + numDatos[i].get('coduac') +"','denuac':'" + numDatos[i].get('denuac') +"','coduac_p':'"+valor4+"','nivel':'"+Nivel+"','nivel_p':'"+nivel4+"'}";

	}
			
	}
	reg = reg + "]}";
	break;

}
	
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
		//alert(datos);
		 var Registros = datos.split("|");
		Cod = Registros[1];
		if(Cod!='')
		{
			Ext.MessageBox.alert('Mensaje', 'Registro '+ Mens +' con exito');
			ActualizarData(ValorActual,NivelActual,Nivel.toString());
			GridActual.store.commitChanges();
				
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
//NumeroGrid = parseInt(tabActual) +1;
GridActual = ObtenerGrid(tabActual);
if(Oper!="incluyendo")
{
		var d = new RecordDef
			 (
	            {
					coduac:'',
		            denuac: '',
		            coduac_p: ''      
				}
	                   
	          );
	    next = GridActual.store.getCount();   
		if(next==1)
		{
			//alert('dsd');
			codigo1 = GridActual.store.getRange(0,1);
			codigo2 = codigo1[0].get('coduac');
			if(codigo2=='')
			{
				//GridActual.store.insert(0, d);
				GridActual.startEditing(0, 0);	
				GridActual.getSelectionModel().selectRow(0);
				filaActual=0;
			}
			else
			{
				GridActual.store.insert(1, d);
				GridActual.startEditing(1, 0);	
				GridActual.getSelectionModel().selectRow(1);
				filaActual=1;
			}	
		}
		else
		{
			
			codigo1 = GridActual.store.getRange(0,1);
			codigo2 = codigo1[1].get('coduac');
			if(codigo2=='')
			{
				GridActual.startEditing(1, 0);	
				GridActual.getSelectionModel().selectRow(1);
				filaActual=1;
			}
			else
			{
				GridActual.store.insert(next, d);
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
	NivelActual = parseInt(tabActual)+1; 
	GridActual = ObtenerGrid(tabActual);
	estevalor = GridActual.getSelectionModel().getSelected().get('coduac');
	estenivel = GridActual.getSelectionModel().getSelected().get('nivel');
	//alert(numDatos[0]);
	var reg = "{'oper':'eliminar','numest':'1','datos':[";
	reg = reg + "{'codemp':'0001','coduac':'" + estevalor +"','nivel':'"+estenivel+"'}";
	
	reg = reg + "]}";

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
					GridActual.store.commitChanges();
					GridActual.store.remove(GridActual.getSelectionModel().getSelected())
					
				 }
				 else
				 {
				  Ext.MessageBox.alert('Error', 'No se pudo eliminar el registro');
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

 
              
             




