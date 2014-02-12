/*
 * Ext JS Library 2.0.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * http://extjs.com/license
 */
 
var valorPlanActual=''; 
var denPlanActual='';
var datosPlan = null;
var gridPlan = null;
var winPlan = null;
var unavez = false;
var parametrosPlan='';
var cantidadPlan=0;
var rutaPlan = '';
var RecordDefPlan;
var datosPlanSesion;
var gridPlan1='';
var gridPlan2='';
var gridPlan3='';
var gridPlan4='';
var gridPlan5='';
var valorPlan1='';
var valorPlan2='';
var valorPlan3='';
var valorPlan4='';
var valorPlan5='';
var DataStorePlan1='';
var DataStorePlan2='';
var DataStorePlan3='';
var DataStorePlan4='';
var DataStorePlan5='';
var ListoPlan1 = false;
var ListoPlan2 = false;
var Oper='';
var datosPlanNuevo ="";
var tabsplan='';
var ListoUltimoPlan="";
 
rutaPlan ='../../procesos/sigesp_spe_estprogpr.php';

 // basic tabsplan 1, built from existing content
function getDatosPlan(Metodo)
{
	var myJSONObject ={
		"oper": Metodo, 
		"numest":'1',
		"codest": "", 
		"denest": ""
};	
	getobjectTbplan();
	ObjSon=JSON.stringify(myJSONObject);
	parametrosPlan = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaPlan,
	params : parametrosPlan,
	method: 'POST',
	success: function ( resultado, request) 
	{ 
		datosPlan = resultado.responseText;
		if(datosPlan!='')
		 {
		 //	alert(datosPlan);
		 	arr = datosPlan.split("|");
		  	jsonserv = arr[1];
		  	cantidadPlan = arr[0];
		 // 	alert(datosPlan);
			var mijson = eval('(' + jsonserv + ')');
		 	switch(Metodo)
		 	{
		 		case 'getSesion':
		 	//	alert(cantidadPlan);
		 	
		 			for(i=0;i<parseInt(cantidadPlan);i++)
		 			{
		 			
		 				agregarTabplan(mijson.raiz[i].nombre_pest,'gridPlan'+i);
					
					}
					MostrarForma(false);
					tabsplan.activate(0);
					habilitarUnaplan(0); 
				

		 		break;
		 	}
		 
		
		

		 }
	}	
	
});	
	
}


function EstadoInicialPlan()
{
	Ext.get('nivelPlan1').dom.innerHTML='';
	Ext.get('valorPlannivel1').dom.innerHTML='';
	Ext.get('nivelPlan2').dom.innerHTML='';
	Ext.get('valorPlannivel2').dom.innerHTML='';
	Ext.get('nivelPlan3').dom.innerHTML='';
	Ext.get('valorPlannivel3').dom.innerHTML='';
	Ext.get('nivelPlan4').dom.innerHTML='';
	Ext.get('valorPlannivel4').dom.innerHTML='';
	Ext.get('nivelPlan5').dom.innerHTML='';
	Ext.get('valorPlannivel5').dom.innerHTML='';
	ListoUltimoPlan=false;
	tabsplan.getItem('0').enable();
	tabsplan.setActiveTab('0');	
	
}

function ManejarTabActivoplan(tab)
{

num = parseInt(tab.id)+1;
//alert(eval('gridPlan'+num));
//alert(gridPlan1);
if(gridPlan1=='' && ListoPlan1==false)
{	
	getgridPlan(1);
}
if(tab.id==1 && gridPlan2=='' || tab.id==2 && gridPlan3=='' || tab.id==3 && gridPlan4=='' || tab.id==4 && gridPlan5=='')
{
	getgridPlan(num);
}
else
{
	//alert('sdd');
switch(parseInt(tab.id))
{
	case 1:
	valorPlan1= gridPlan1.getSelectionModel().getSelected().get('codest1');
	denPlan1= gridPlan1.getSelectionModel().getSelected().get('denest1');
	tabanterior = tabsplan.getItem('0').title;
	Ext.get('nivelPlan1').dom.innerHTML=tabanterior +':';
	Ext.get('valorPlannivel1').dom.innerHTML=valorPlan1+'-'+denPlan1;
	Ext.get('nivelPlan2').dom.innerHTML='';
	Ext.get('valorPlannivel2').dom.innerHTML='';
	if(gridPlan2!='')
	{
	//	habilitarUnaplan(parseInt(tab.id));	
		ActualizarDataplan(valorPlan1,'0','3','4','2');
	}
	
	MostrarForma(true);
	break;
	case 2:
	valorPlan2= gridPlan2.getSelectionModel().getSelected().get('codest2');
	denPlan2= gridPlan2.getSelectionModel().getSelected().get('denest2');
	//alert(valorPlan2);
	tabanterior = tabsplan.getItem('1').title;
	Ext.get('nivelPlan2').dom.innerHTML=tabanterior+':';
	Ext.get('valorPlannivel2').dom.innerHTML=valorPlan2+'-'+denPlan2;
	Ext.get('nivelPlan3').dom.innerHTML='';
	Ext.get('valorPlannivel3').dom.innerHTML='';
	if(gridPlan3!='')
	{
		deshabilitarAntplan(2);
		ActualizarDataplan(valorPlan1,valorPlan2,'3','4','3');
	}
	MostrarForma(true);
	break;
	case 3:
	valorPlan3 = gridPlan3.getSelectionModel().getSelected().get('codest3');
	denPlan3= gridPlan3.getSelectionModel().getSelected().get('denest3');
	tabanterior = tabsplan.getItem('2').title;
	Ext.get('nivelPlan3').dom.innerHTML=tabanterior+':';
	Ext.get('valorPlannivel3').dom.innerHTML=valorPlan3+'-'+denPlan3;
	Ext.get('nivelPlan4').dom.innerHTML='';
	Ext.get('valorPlannivel4').dom.innerHTML='';
	if(gridPlan4!='')
	{
		deshabilitarAntplan(3);
		ActualizarDataplan(valorPlan1,valorPlan2,valorPlan3,'0','4');
	}
	MostrarForma(true);
	break;
	case 4:
	//alert('2');
	valorPlan4= gridPlan4.getSelectionModel().getSelected().get('codest4');
	denPlan4= gridPlan4.getSelectionModel().getSelected().get('denest4');
	//alert(valorPlan2);
	tabanterior = tabsplan.getItem('3').title;
	Ext.get('nivelPlan4').dom.innerHTML=tabanterior+':';
	Ext.get('valorPlannivel4').dom.innerHTML=valorPlan4+'-'+denPlan4;
	if(gridPlan5!='')
	{
		deshabilitarAntplan(4);
		ActualizarDataplan(valorPlan1,valorPlan2,valorPlan3,valorPlan4,'5');
	}
	MostrarForma(true);
	break;

	default:
	MostrarForma(false);
	break;	
}

}
}


function PonerUltimoTituloplan(tab)
{

	ObjActual = parseInt(tab)+1;
	Nivel = 'nivelPlan'+ObjActual.toString();
	valorPlanNivel = 'valorPlannivel'+ObjActual.toString();
	gridPlanActual = ObtenergridPlan(tab);
	tabanterior = tabsplan.getItem(tab).title;
	valorPlanActual= gridPlanActual.getSelectionModel().getSelected().get('codest'+ObjActual.toString());
	denPlanActual= gridPlanActual.getSelectionModel().getSelected().get('denest'+ObjActual.toString());
	//alert(gridPlanActual.getSelectionModel().getSelected().get('codest'+ObjActual.toString()));
	tabanterior = tabsplan.getItem(tab).title;
	Ext.get(Nivel).dom.innerHTML=tabanterior+':';
	Ext.get(valorPlanNivel).dom.innerHTML=valorPlanActual+'-'+denPlanActual;	
	ListoUltimoPlan=true;

}


function click_extraplan()
{

	habilitarUnaplan(tabsplan.getActiveTab().id,true) 
}
	
	
function deshabilitarAntplan(tab)
{			
		if(tab>1)
		{
			num2 = tab-2;
			tabsplan.getItem(num2).disable();	
		}
}

function habilitarUnaplan(tab,paso)
{
	UltiActual = cantidadPlan-1;
	for(var r=0;r<cantidadPlan;r++)
	{
		num2 = r+1;
		if(r==tab)
		{	
			
			if(r>0)
			{
			
				if(r==UltiActual)
				{
					tabsplan.getItem(r-1).enable();
					PonerUltimoTituloplan(tab);
					
				}
				else
				{
					tabsplan.getItem(num2).enable();
					tabsplan.getItem(r-1).enable();
					tabsplan.setActiveTab(num2);
					r++;
				}
			}
			else 
			{
				if(paso)
				{
					tabsplan.getItem(num2).enable();
					tabsplan.setActiveTab(num2);
					r++;
				}
				tabsplan.getItem(r).enable();		
			}
			
		}
		else
		{
			//tabsplan.getItem(r).disable();
		}
	}
}

function MostrarForma(valorPlan)
{
	if(valorPlan==false)
	{
	//	Ext.get('formestprog').dom.style.display='none';
		
	}
	else
	{
	//	Ext.get('formestprog').dom.style.display='block';
	}
	
}

function getgridPlan(numero)
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

	aux = eval('(' + myJSONString + ')');
	ObjSon=JSON.stringify(aux);
	parametrosPlan = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	ListoPlan1=numero;
	Ext.Ajax.request({
	url : rutaPlan,
	disableCaching:false,
	params : parametrosPlan,
	method: 'POST',
	success: function ( resultado, request ) 
	{ 
		 datosPlan = resultado.responseText;
		// alert(datosPlan);
		var datosPlanNuevo = eval('(' + datosPlan + ')');
		 if(datosPlanNuevo.raiz!=null)
		 {
			
		//alert(datosPlanNuevo)	
		
		}
		else
		{
		
			var datosPlanNuevo={"raiz":[{"CODEMP":'',"codest1":'',"codest2":'',"codest3":'',"codest4":'',"codest5":'',"ESTCLA":'',"ANO_PRESUPUESTO":'',"denest1":'',"denest2":'',"denest3":'',"denest4":'',"denest5":''}]};
		}
		
			RecordDefPlan = Ext.data.Record.create([
			{name: 'CODEMP'},     // "mapping" property not needed if it's the same as "name"
			{name: 'codest1'},
			{name: 'codest2'},
			{name: 'codest3'},
			{name: 'codest4'},
			{name: 'codest5'},
			{name: 'ESTCLA'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
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
				
				DataStorePlan1 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(datosPlanNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDefPlan
			     
			      ),
				data: datosPlanNuevo
                        });
			 gridPlan1 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStorePlan1,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 100, sortable: true,dataIndex: 'codest'+numero,editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'valid': function(){
    	if(this.getValue()!='')
    	{
    	  AuxvalorPlan1 = this.getValue();
	      AuxvalorPlan = ue_rellenarcampo(AuxvalorPlan1,25);
	      this.setValue(AuxvalorPlan);
	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denest'+numero,editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:true}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
				gridPlan1.addListener('cellclick', click_extraplan);
				gridPlan1.render('gridPlan0');
				break
				case 2:
			DataStorePlan2 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(datosPlanNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDefPlan
			     
			      ),
				data: datosPlanNuevo
                    });
			
			
			 gridPlan2 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStorePlan2,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 100, sortable: true,dataIndex: 'codest'+numero,editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'valid': function(){
    	if(this.getValue()!='')
    	{
    		AuxvalorPlan1 = this.getValue();
	      	AuxvalorPlan = ue_rellenarcampo(AuxvalorPlan1,25);
	      	this.setValue(AuxvalorPlan);	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex: 'denest'+numero,editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
				gridPlan2.addListener('cellclick', click_extraplan);
				gridPlan2.render('gridPlan1');
				
				break
			case 3:
			DataStorePlan3 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(datosPlanNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDefPlan
			     
			      ),
				data: datosPlanNuevo
                        });

						gridPlan3 = new Ext.grid.EditorGridPanel({
						width:770,
						autoScroll:true,
                        border:true,
                        ds:DataStorePlan3,
                        cm: new Ext.grid.ColumnModel([
                        {header: "Código", width: 100, sortable: true,dataIndex: 'codest'+numero,editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'valid': function(){
    	if(this.getValue()!='')
    	{
    	  AuxvalorPlan1 = this.getValue();
	      AuxvalorPlan = ue_rellenarcampo(AuxvalorPlan1,25);
	      this.setValue(AuxvalorPlan);	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex: 'denest3',editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
        gridPlan3.addListener('cellclick', click_extraplan);
		gridPlan3.render('gridPlan2');
		break
		case 4:
			DataStorePlan4 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(datosPlanNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDefPlan
			     
			      ),
				data: datosPlanNuevo
                        });
		
			 gridPlan4 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStorePlan4,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 100, sortable: true,dataIndex: 'codest'+numero,editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'valid': function(){
    	if(this.getValue()!='')
    	{
    	  AuxvalorPlan1 = this.getValue();
	      AuxvalorPlan = ue_rellenarcampo(AuxvalorPlan1,25);
	      this.setValue(AuxvalorPlan);
	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex: 'denest'+numero,editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
		gridPlan4.addListener('cellclick', click_extraplan);
		gridPlan4.render('gridPlan3');
		break
		case 5:
			DataStorePlan5 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(datosPlanNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDefPlan
			     
			      ),
				data: datosPlanNuevo
                        });			
			 gridPlan5 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStorePlan5,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 100, sortable: true,dataIndex: 'codest'+numero,editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'valid': function(){
    	if(this.getValue()!='')
    	{
    	  AuxvalorPlan1 = this.getValue();
	      AuxvalorPlan = ue_rellenarcampo(AuxvalorPlan1,25);
	      this.setValue(AuxvalorPlan);	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex: 'denest'+numero,editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
            
		gridPlan5.addListener('cellclick', click_extraplan);	
		gridPlan5.render('gridPlan4');
		break
		}
	   		  		
 }
	
});	

}

function ActualizarDataplan(cod1,cod2,cod3,cod4,nivel)
{
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
	parametrosPlan = 'ObjSon='+ObjSon; 
	  Ext.Ajax.request({
	url : rutaPlan,
	params : parametrosPlan,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datosPlan = resultado.responseText;
		  //alert(datosPlan);
		 if(datosPlan!='')
		 {
			var datosPlanNuevo = eval('(' + datosPlan + ')');
			if(datosPlanNuevo.raiz==null)
			{
					 datosPlanNuevo={"raiz":[{"CODEMP":'',"codest1":'',"codest2":'',"codest3":'',"codest4":'',"codest5":'',"ESTCLA":'',"ANO_PRESUPUESTO":'',"denest1":'',"denest2":'',"denest3":'',"denest4":'',"denest5":''}]};
			}
					
				switch(nivel)
				{
					case '1':
						gridPlan1.store.loadData(datosPlanNuevo);
						break;
					case '2':
						gridPlan2.store.loadData(datosPlanNuevo);
						break;
					case '3':
						gridPlan3.store.loadData(datosPlanNuevo);
						break;
					case '4':
						gridPlan4.store.loadData(datosPlanNuevo);
						break;
					case '5':
						gridPlan5.store.loadData(datosPlanNuevo);
						break;
	
				}
			
			
		}
	
}
});
	
}

function agregarTabplan(titulo,Elemento)
{
	//alert(Elemento);
        tabsplan.add({
        title: titulo,
        listeners: {activate: ManejarTabActivoplan},
        contentEl: Elemento,
        id:Elemento.substr(Elemento.length-1,1),
        closable:false
        }).show();
}
				
function getobjectTbplan()
{
		Formulario1 = new Ext.Panel({
	    title: 'Estructuras de Planificación',
	    height:120,
	    contentEl:'formestproPlan',
     	});
     	
	   	Ext.QuickTips.init(); 
		 tabsplan= new Ext.TabPanel
		(
        {
            //baseCls:'x-plain',
			renderTo: 'tabs7',
			 //activeTab: 0,
			 		frame:true,
				    autoScroll:true,
                    width:800,
                    height:500,
                    plain: false
		    		,defaults: {frame:true, width:800, height: 200}
                  
         });	
}			


function ObtenergridPlan(tab)
{
	//alert(tab);
	switch(tab)
	{
		case '0':
			return gridPlan1;
			break;
		case '1':
			return gridPlan2;
			break;
		case '2':
			return gridPlan3;
			break;
		case '3':
			return gridPlan4;
			break;
		case '4':
			return gridPlan5;
			break;
	}    
	
}


//getdatosPlanplan('getSesion');
//getNombreEtiquetas();
	


 
              
             




