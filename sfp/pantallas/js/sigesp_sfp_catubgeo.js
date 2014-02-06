/*
 * Ext JS Library 2.0.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * http://extjs.com/license
 */
 
var valorUbActual=''; 
var denUbActual='';
var datosUb = null;
var gridUb = null;
var winUb = null;
var unavez = false;
var parametrosUb='';
var cantidadUb=0;
var rutaUb = '';
var RecordDefUb;
var datosUbSesion;
var gridUb1='';
var gridUb2='';
var gridUb3='';
var gridUb4='';
var gridUb5='';
var valorUb1='';
var valorUb2='';
var valorUb3='';
var valorUb4='';
var valorUb5='';
var DataStoreUb1='';
var DataStoreUb2='';
var DataStoreUb3='';
var DataStoreUb4='';
var DataStoreUb5='';
var ListoUb1 = false;
var ListoUb2 = false;
var Oper='';
var datosUbNuevo ="";
var tabsUb='';
var ListoUltimoUb="";
 
rutaUb ='../../procesos/sigesp_spe_ubgeopr.php';

 // basic tabsUb 1, built from existing content
function getDatosUb(Metodo)
{
	var myJSONObject ={
		"oper": Metodo, 
		"numest":'1',
		"codubgeo": "", 
		"DENEST": ""
};	
	getobjectTbUb();
	ObjSon=JSON.stringify(myJSONObject);
	parametrosUb = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaUb,
	params : parametrosUb,
	method: 'POST',
	success: function ( resultado, request) 
	{ 
		datosUb = resultado.responseText;
		if(datosUb!='')
		 {
		 //	alert(datosUb);
		 	arr = datosUb.split("|");
		  	jsonserv = arr[1];
		  	cantidadUb = arr[0];
		 // 	alert(datosUb);
			var mijson = eval('(' + jsonserv + ')');
		 	switch(Metodo)
		 	{
		 		case 'getSesion':
		 	//	alert(cantidadUb);
		 	
		 			for(i=0;i<parseInt(cantidadUb);i++)
		 			{
		 				agregarTabUb(mijson.raiz[i].nombre_pest,'gridUb'+i);
					}
					MostrarForma(false);
					tabsUb.activate(0);
					habilitarUnaUb(0); 
		 		break;
		 	}
		 }
	}	
	
});	
	
}


function EstadoInicialUb()
{
	Ext.get('nivelUb1').dom.innerHTML='';
	Ext.get('valorUbnivel1').dom.innerHTML='';
	Ext.get('nivelUb2').dom.innerHTML='';
	Ext.get('valorUbnivel2').dom.innerHTML='';
	Ext.get('nivelUb3').dom.innerHTML='';
	Ext.get('valorUbnivel3').dom.innerHTML='';
	Ext.get('nivelUb4').dom.innerHTML='';
	Ext.get('valorUbnivel4').dom.innerHTML='';
	Ext.get('nivelUb5').dom.innerHTML='';
	Ext.get('valorUbnivel5').dom.innerHTML='';
	ListoUltimoUb=false;
	tabsUb.getItem('0').enable();
	tabsUb.setActiveTab('0');	
	
}




function ManejarTabActivoUb(tab)
{

num = parseInt(tab.id)+1;
//alert(eval('gridUb'+num));
//alert(gridUb1);
if(gridUb1=='' && ListoUb1==false)
{	
	getgridUb(1);
}
if(tab.id==1 && gridUb2=='' || tab.id==2 && gridUb3=='' || tab.id==3 && gridUb4=='' || tab.id==4 && gridUb5=='')
{
	getgridUb(num);
}
else
{
	//alert('sdd');
switch(parseInt(tab.id))
{
	case 1:
	valorUb1= gridUb1.getSelectionModel().getSelected().get('codubgeo1');
	denUb1= gridUb1.getSelectionModel().getSelected().get('denominacion');
	tabanterior = tabsUb.getItem('0').title;
	Ext.get('nivelUb1').dom.innerHTML=tabanterior +':';
	Ext.get('valorUbnivel1').dom.innerHTML=denUb1;
	Ext.get('nivelUb2').dom.innerHTML='';
	Ext.get('valorUbnivel2').dom.innerHTML='';
	if(gridUb2!='')
	{
	//	habilitarUnaUb(parseInt(tab.id));	
		ActualizarDataUb(valorUb1,'0','3','4','2');
	}
	
	MostrarForma(true);
	break;
	case 2:
	valorUb2= gridUb2.getSelectionModel().getSelected().get('codubgeo2');
	denUb2= gridUb2.getSelectionModel().getSelected().get('denominacion');
	//alert(valorUb2);
	tabanterior = tabsUb.getItem('1').title;
	Ext.get('nivelUb2').dom.innerHTML=tabanterior+':';
	Ext.get('valorUbnivel2').dom.innerHTML=denUb2;
	Ext.get('nivelUb3').dom.innerHTML='';
	Ext.get('valorUbnivel3').dom.innerHTML='';
	if(gridUb3!='')
	{
		//deshabilitarAntUb(2);
		ActualizarDataUb(valorUb1,valorUb2,'3','4','3');
	}
	MostrarForma(true);
	break;
	case 3:
	valorUb3 = gridUb3.getSelectionModel().getSelected().get('codubgeo3');
	denUb3= gridUb3.getSelectionModel().getSelected().get('denominacion');
	tabanterior = tabsUb.getItem('2').title;
	Ext.get('nivelUb3').dom.innerHTML=tabanterior+':';
	Ext.get('valorUbnivel3').dom.innerHTML=denUb3;
	Ext.get('nivelUb4').dom.innerHTML='';
	Ext.get('valorUbnivel4').dom.innerHTML='';
	if(gridUb4!='')
	{
		//deshabilitarAntUb(3);
		ActualizarDataUb(valorUb1,valorUb2,valorUb3,'0','4');
	}
	MostrarForma(true);
	break;
	case 4:
	//alert('2');
	valorUb4= gridUb4.getSelectionModel().getSelected().get('codubgeo4');
	denUb4= gridUb4.getSelectionModel().getSelected().get('denominacion');
	//alert(valorUb2);
	tabanterior = tabsUb.getItem('3').title;
	Ext.get('nivelUb4').dom.innerHTML=tabanterior+':';
	Ext.get('valorUbnivel4').dom.innerHTML=denUb4;
	if(gridUb5!='')
	{
		//deshabilitarAntUb(4);
		ActualizarDataUb(valorUb1,valorUb2,valorUb3,valorUb4,'5');
	}
	MostrarForma(true);
	break;

	default:
	MostrarForma(false);
	break;	
}

}
}


function PonerUltimoTituloUb(tab)
{

	ObjActual = parseInt(tab)+1;
	Nivel = 'nivelUb'+ObjActual.toString();
	valorUbNivel = 'valorUbnivel'+ObjActual.toString();
	gridUbActual = ObtenergridUb(tab);
	tabanterior = tabsUb.getItem(tab).title;
	valorUbActual= gridUbActual.getSelectionModel().getSelected().get('codubgeo'+ObjActual.toString());

	denUbActual= gridUbActual.getSelectionModel().getSelected().get('denominacion');
	tabanterior = tabsUb.getItem(tab).title;
	Ext.get(Nivel).dom.innerHTML=tabanterior+':';
	Ext.get(valorUbNivel).dom.innerHTML=denUbActual;	
	ListoUltimoUb=true;

}


function click_extraUb()
{

	habilitarUnaUb(tabsUb.getActiveTab().id,true) 

}
	
	
function deshabilitarAntUb(tab)
{			
		if(tab>1)
		{
			num2 = tab-2;
			tabsUb.getItem(num2).disable();	
		}
}

function habilitarUnaUb(tab,paso)
{
	UltiActual = cantidadUb-1;
	if(UltiActual==0 && paso)
	{
		PonerUltimoTituloUb(tab);
		return false;
	}
	for(var r=0;r<cantidadUb;r++)
	{
		num2 = r+1;
		if(r==tab)
		{	
			if(r>0)
			{	
				if(r==UltiActual)
				{
					tabsUb.getItem(r-1).enable();
					PonerUltimoTituloUb(tab);	
				}
				else
				{
					tabsUb.getItem(num2).enable();
					tabsUb.getItem(r-1).enable();
					tabsUb.setActiveTab(num2);
					r++;
				}
			}
			else 
			{
				if(paso)
				{
					tabsUb.getItem(num2).enable();
					tabsUb.setActiveTab(num2);
					r++;
				}
				tabsUb.getItem(r).enable();		
			}
		}
		else
		{
			//tabsUb.getItem(r).disable();
		}
	}
}

function MostrarForma(valorUb)
{
	if(valorUb==false)
	{
	//	Ext.get('formestprog').dom.style.display='none';
		
	}
	else
	{
	//	Ext.get('formestprog').dom.style.display='block';
	}
}

function getgridUb(numero)
{
	
	Auxnum = numero;
	var myJSONString ="{'oper': 'catestpro', 'numest':"+numero+",'codubgeo"+numero+"': '','DENEST"+numero+"': ''";

	if(parseInt(Auxnum)>1)
	{
		for(var ind=1;ind<Auxnum;ind++)
		{
			myJSONString = myJSONString +",'codubgeo"+ind+"':''"; 
		}
	}
	
	myJSONString = myJSONString+"}";	
	aux = eval('(' + myJSONString + ')');
	ObjSon=JSON.stringify(aux);
	parametrosUb = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	ListoUb1=numero;
	Ext.Ajax.request({
	url : rutaUb,
	disableCaching:false,
	params : parametrosUb,
	method: 'POST',
	success: function ( resultado, request ) 
	{ 
		 datosUb = resultado.responseText;
		var datosUbNuevo = eval('(' + datosUb + ')');
		 if(datosUbNuevo.raiz!=null)
		 {
		
			//alert(datosUbNuevo)	
		
		 }
		else
		{
		
			var datosUbNuevo={"raiz":[{"codemp":'',"codubgeo1":'',"codubgeo2":'',"codubgeo3":'',"codubgeo4":'',"codubgeo5":'',"ESTCLA":'',"ano_presupuesto":'',"denominacion":''}]};
		}
		
			RecordDefUb = Ext.data.Record.create([
			{name: 'codemp'},   
			{name: 'codubgeo1'},
			{name: 'codubgeo2'},
			{name: 'codubgeo3'},
			{name: 'codubgeo4'},
			{name: 'codubgeo5'},
			{name: 'ESTCLA'},
			{name: 'ano_presupuesto'},
			{name: 'coduac'},
			{name: 'denominacion'}
			]);

			switch(numero)
			{
				case 1:
				
			DataStoreUb1 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(datosUbNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',               
			    id: "id"   
			    },
                RecordDefUb
			     
			      ),
				data: datosUbNuevo
                        });
			 gridUb1 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStoreUb1,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 100, sortable: true,dataIndex: 'codubgeo'+numero,editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'valid': function(){
    	if(this.getValue()!='')
    	{
    	  AuxvalorUb1 = this.getValue();
	      AuxvalorUb = ue_rellenarcampo(AuxvalorUb1,25);
	      this.setValue(AuxvalorUb);
	    
      	}
    }
  }
})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:true}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
				gridUb1.addListener('cellclick', click_extraUb);
				gridUb1.render('gridUb0');
				break
				case 2:
			DataStoreUb2 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(datosUbNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDefUb
			     
			      ),
				data: datosUbNuevo
                    });
			
			
			 gridUb2 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStoreUb2,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 100, sortable: true,dataIndex: 'codubgeo'+numero,editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'valid': function(){
    	if(this.getValue()!='')
    	{
    		AuxvalorUb1 = this.getValue();
	      	AuxvalorUb = ue_rellenarcampo(AuxvalorUb1,25);
	      	this.setValue(AuxvalorUb);	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
				gridUb2.addListener('cellclick', click_extraUb);
				gridUb2.render('gridUb1');
				
				break
			case 3:
			DataStoreUb3 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(datosUbNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDefUb
			     
			      ),
				data: datosUbNuevo
                        });
			
						gridUb3 = new Ext.grid.EditorGridPanel({
						width:770,
						autoScroll:true,
                        border:true,
                        ds:DataStoreUb3,
                        cm: new Ext.grid.ColumnModel([
                        {header: "Código", width: 100, sortable: true,dataIndex: 'codubgeo'+numero,editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'valid': function(){
    	if(this.getValue()!='')
    	{
    	  AuxvalorUb1 = this.getValue();
	      AuxvalorUb = ue_rellenarcampo(AuxvalorUb1,25);
	      this.setValue(AuxvalorUb);	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
        gridUb3.addListener('cellclick', click_extraUb);
		gridUb3.render('gridUb2');
		break
		case 4:
			DataStoreUb4 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(datosUbNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDefUb
			     
			      ),
				data: datosUbNuevo
                        });
		
			 gridUb4 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStoreUb4,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 100, sortable: true,dataIndex: 'codubgeo'+numero,editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'valid': function(){
    	if(this.getValue()!='')
    	{
    	  AuxvalorUb1 = this.getValue();
	      AuxvalorUb = ue_rellenarcampo(AuxvalorUb1,25);
	      this.setValue(AuxvalorUb);
	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex:'denominacion',editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
		gridUb4.addListener('cellclick', click_extraUb);
		gridUb4.render('gridUb3');
		break
		case 5:
			DataStoreUb5 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(datosUbNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDefUb
			     
			      ),
				data: datosUbNuevo
                        });			
			 gridUb5 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStoreUb5,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 100, sortable: true,dataIndex: 'codubgeo'+numero,editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'valid': function(){
    	if(this.getValue()!='')
    	{
    	  AuxvalorUb1 = this.getValue();
	      AuxvalorUb = ue_rellenarcampo(AuxvalorUb1,25);
	      this.setValue(AuxvalorUb);	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex:'denominacion',editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
            
		gridUb5.addListener('cellclick', click_extraUb);	
		gridUb5.render('gridUb4');
		break
		}
	   		  		
 }
	
});	

}

function ActualizarDataUb(cod1,cod2,cod3,cod4,nivel)
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
	parametrosUb = 'ObjSon='+ObjSon; 
	  Ext.Ajax.request({
	url : rutaUb,
	params : parametrosUb,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datosUb = resultado.responseText;
		  //alert(datosUb);
		 if(datosUb!='')
		 {
			var datosUbNuevo = eval('(' + datosUb + ')');
			if(datosUbNuevo.raiz==null)
			{
					 datosUbNuevo={"raiz":[{"codemp":'',"codubgeo1":'',"codubgeo2":'',"codubgeo3":'',"codubgeo4":'',"codubgeo5":'',"ESTCLA":'',"ano_presupuesto":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
			}
					
				switch(nivel)
				{
					case '1':
						gridUb1.store.loadData(datosUbNuevo);
						break;
					case '2':
						gridUb2.store.loadData(datosUbNuevo);
						break;
					case '3':
						gridUb3.store.loadData(datosUbNuevo);
						break;
					case '4':
						gridUb4.store.loadData(datosUbNuevo);
						break;
					case '5':
						gridUb5.store.loadData(datosUbNuevo);
						break;
	
				}
			
			
		}
	
}
});
	
}

function agregarTabUb(titulo,Elemento)
{
	//alert(Elemento);
        tabsUb.add({
        title: titulo,
        listeners: {activate: ManejarTabActivoUb},
        contentEl: Elemento,
        id:Elemento.substr(Elemento.length-1,1),
        closable:false
        }).show();
}
				
function getobjectTbUb()
{
		Formulario1 = new Ext.Panel({
	    title: 'Estructuras d Ubificación',
	    height:120,
	    contentEl:'formestproUb',
     	});
	
	   	Ext.QuickTips.init(); 
		 tabsUb= new Ext.TabPanel
		(
        {
            //baseCls:'x-plain',
			renderTo: 'tabsUb',
			 //activeTab: 0,
			 		frame:true,
				    autoScroll:true,
                    width:800,
                    height:500,
                    plain: false
		    		,defaults: {frame:true, width:800, height: 200}
                  
            });	
}			


function ObtenergridUb(tab)
{
	//alert(tab);
	switch(tab)
	{
		case '0':
			return gridUb1;
			break;
		case '1':
			return gridUb2;
			break;
		case '2':
			return gridUb3;
			break;
		case '3':
			return gridUb4;
			break;
		case '4':
			return gridUb5;
			break;
	}    
	
}


//getdatosUbUb('getSesion');
//getNombreEtiquetas();
	


 
              
             




