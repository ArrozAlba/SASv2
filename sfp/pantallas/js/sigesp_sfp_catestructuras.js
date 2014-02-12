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
var rutaEstPre = '';
var RecordDef;
var DatosSesion;
var gridep1='';
var gridep2='';
var gridep3='';
var grid4='';
var grid5='';
var valor1='';
var valor2='';
var valor3='';
var valor4='';
var valor5='';
var DataStoreep1='';
var DataStoreep2='';
var DataStoreep3='';
var DataStore4='';
var DataStore5='';
var Listo1 = false;
var Listo2 = false;
var ListoUltimo=false;
var Oper='';
var DatosNuevo ="";
var Arsel ="";
var tabs='';
var valorActual='';
//var Contenidovalornivel="";
 
rutaEstPre ='../../procesos/sigesp_sfp_estprogpr.php';

    // basic tabs 1, built from existing content
function getDatos(Metodo)
{
	var myJSONObject ={
		"oper": Metodo, 
		"numest":'1',
		"codestpro": "", 
		"denestpro": ""
	};	
	getobjectTb();
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaEstPre,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		  datos = resultado.responseText;
		 
		if(datos!='')
		{
		 	//alert(datos);
		 	arr = datos.split("|");
		  	jsonserv = arr[1];
		  	cantidad = arr[0];
			var mijson = eval('(' + jsonserv + ')');
		 	switch(Metodo)
		 	{
		 		case 'getSesion':
		 			for(i=0;i<parseInt(cantidad);i++)
		 			{
		 				agregarTab(mijson.raiz[i].nombre_pest,'gridPre'+i);
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


function EstadoInicialPre()
{
	Ext.get('nivelPre1').dom.innerHTML='';
	Ext.get('valornivel1').dom.innerHTML='';
	Ext.get('nivelPre2').dom.innerHTML='';
	Ext.get('valornivel2').dom.innerHTML='';
	Ext.get('nivelPre3').dom.innerHTML='';
	Ext.get('valornivel3').dom.innerHTML='';
	Ext.get('nivelPre4').dom.innerHTML='';
	Ext.get('valornivel4').dom.innerHTML='';
	Ext.get('nivelPre5').dom.innerHTML='';
	Ext.get('valornivel5').dom.innerHTML='';
	ListoUltimo=false;
	tabs.getItem('0').enable();
	tabs.setActiveTab('0');
	
}


function ManejarTabActivo(tab)
{
num = parseInt(tab.id)+1;
//alert(eval('grid'+num));
//alert(grid1);
if(gridep1=='' && Listo1==false)
{	
	getgrid(1);
}
if(tab.id==1 && gridep2=='' || tab.id==2 && gridep3=='' || tab.id==3 && grid4=='' || tab.id==4 && grid5=='')
{
	getgrid(num);
}
else
{
	//alert('sdd');
switch(parseInt(tab.id))
{
	case 1:
	//alert(grid1.getSelectionModel().getSelected().get('estcla'));
	valor1= gridep1.getSelectionModel().getSelected().get('codestpro1');
	den1= gridep1.getSelectionModel().getSelected().get('denestpro1');
	TipoEstructura=gridep1.getSelectionModel().getSelected().get('estcla');
	tabanterior = tabs.getItem('0').title;
	Ext.get('nivelPre1').dom.innerHTML=tabanterior +':';
	Ext.get('valornivel1').dom.innerHTML=valor1+'-'+den1;
	Ext.get('nivelPre2').dom.innerHTML='';
	Ext.get('valornivel2').dom.innerHTML='';
	if(gridep2!='')
	{
		
		//habilitarUna(0,true)
		ActualizarData(valor1,'0','3','4','2');
	}
	
	MostrarForma(true);
	break;
	case 2:
	valor2= gridep2.getSelectionModel().getSelected().get('codestpro2');
	den2= gridep2.getSelectionModel().getSelected().get('denestpro2');
	TipoEstructura=gridep1.getSelectionModel().getSelected().get('estcla');
	//alert(valor2);
	tabanterior = tabs.getItem('1').title;
	Ext.get('nivelPre2').dom.innerHTML=tabanterior+':';
	Ext.get('valornivel2').dom.innerHTML=valor2+'-'+den2;
	Ext.get('nivelPre3').dom.innerHTML='';
	Ext.get('valornivel3').dom.innerHTML='';
	if(gridep3!='')
	{
		//deshabilitarAnt(1);
		//deshabilitarAnt(2);
		ActualizarData(valor1,valor2,'3','4','3');
	}
	MostrarForma(true);
	break;
	case 3:
	valor3= gridep3.getSelectionModel().getSelected().get('codestpro3');
	den3= gridep3.getSelectionModel().getSelected().get('denestpro3');
	tabanterior = tabs.getItem('2').title;
	Ext.get('nivelPre3').dom.innerHTML=tabanterior+':';
	Ext.get('valornivel3').dom.innerHTML=valor3+'-'+den3;
	Ext.get('nivelPre4').dom.innerHTML='';
	Ext.get('valornivel4').dom.innerHTML='';
	if(grid4!='')
	{
		//deshabilitarAnt(2);
		//deshabilitarAnt(3);
		ActualizarData(valor1,valor2,valor3,'0','4');
	}
	MostrarForma(true);
	break;
	case 4:
	valor4= grid4.getSelectionModel().getSelected().get('codestpro4');
	den4= grid4.getSelectionModel().getSelected().get('denestpro4');
	tabanterior = tabs.getItem('3').title;
	Ext.get('nivelPre4').dom.innerHTML=tabanterior+':';
	Ext.get('valornivel4').dom.innerHTML=valor4+'-'+den4;
	if(grid5!='')
	{
		//deshabilitarAnt(4);
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

function PonerUltimoTitulo(tab)
{
	ObjActual = parseInt(tab)+1;
	GridActual = ObtenerGrid(tab);
	tabanterior = tabs.getItem(tab).title;
	valorActual=GridActual.getSelectionModel().getSelected().get('codestpro'+ObjActual.toString());
	denActual=GridActual.getSelectionModel().getSelected().get('denestpro'+ObjActual.toString());
	//alert(denActual);
	tabanterior = tabs.getItem(tab).title;
			Ext.get('nivelPre'+ObjActual.toString()).dom.innerHTML=tabanterior+':';
	Arsel = GridActual.getSelectionModel().getSelections();
	TotSel='';
	for(i=0;i<Arsel.length;i++)
	{
		TotSel=TotSel+Arsel[i].get('codestpro'+ObjActual.toString())+'-'+Arsel[i].get('denestpro'+ObjActual.toString())+'<br>';	
	}
	Ext.get('valornivel'+ObjActual.toString()).dom.innerHTML=TotSel;
	ListoUltimo=true;	

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
					PonerUltimoTitulo(tab);
					
				}
				else
				{
					tabs.getItem(num2).enable();
					tabs.getItem(r-1).enable();
					tabs.setActiveTab(num2);
					r++;
				}
			}
			else 
			{
				if(paso)
				{
					tabs.getItem(num2).enable();
					tabs.setActiveTab(num2);
					r++;
				}
				tabs.getItem(r).enable();		
			}
			
		}
		else
		{
			//tabs.getItem(r).disable();
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
	var myJSONString ="{'oper': 'catestpro', 'numest':"+numero+",'codestpro"+numero+"': '','denestpro"+numero+"': ''";

	if(parseInt(Auxnum)>1)
	{
		for(var ind=1;ind<Auxnum;ind++)
		{
			myJSONString = myJSONString +",'codestpro"+ind+"':''"; 
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
	url : rutaEstPre,
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
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"codestpro1":'',"codestpro2":'',"codestpro3":'',"codestpro4":'',"codestpro5":'',"ESTCLA":'',"ANO_PRESUPUESTO":'',"denestpro1":'',"denestpro2":'',"denestpro3":'',"denestpro4":'',"denestpro5":''}]};
		}
		
			RecordDef = Ext.data.Record.create([
			{name: 'codemp'},    
			{name: 'codestpro1'},
			{name: 'codestpro2'},
			{name: 'codestpro3'},
			{name: 'codestpro4'},
			{name: 'codestpro5'},
			{name: 'estcla'},
			{name: 'ano_presupuesto'},
			{name: 'CODUAC'},
			{name: 'denestpro1'},
			{name: 'denestpro2'},
			{name: 'denestpro3'},
			{name: 'denestpro4'},
			{name: 'denestpro5'}
				// This field will use "occupation" as the mapping.
			]);
			
			
			
			//datastore que se usa para el catalogo de busqueda
			switch(numero)
			{
				case 1:
				
				DataStoreep1 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDef
			     
			      ),
				data: DatosNuevo
                        });
			 gridep1 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStoreep1,
                        cm: new Ext.grid.ColumnModel([
                        new Ext.grid.CheckboxSelectionModel(),
                            {header: "Código", width: 100, sortable: true,dataIndex: 'codestpro'+numero,editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'valid': function(){
    	if(this.getValue()!='')
    	{
    	  Auxvalor1 = this.getValue();
	      Auxvalor = ue_rellenarcampo(Auxvalor1,25);
	      this.setValue(Auxvalor);
	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
                            {header: "Denominación", width: 350, sortable: true, dataIndex: 'denestpro'+numero,editor: new Ext.form.TextField({allowBlank: false})}
							
]),
sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
				gridep1.addListener('cellclick', click_extra);
				gridep1.render('gridPre0');
				break
				case 2:
			DataStoreep2 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDef
			     
			      ),
				data: DatosNuevo
                    });
			
			
			 gridep2 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStoreep2,
                        cm: new Ext.grid.ColumnModel([
                        	new Ext.grid.CheckboxSelectionModel(),
                            {header: "Código", width: 100, sortable: true,dataIndex: 'codestpro'+numero,editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'valid': function(){
    	if(this.getValue()!='')
    	{
    		Auxvalor1 = this.getValue();
	      	Auxvalor = ue_rellenarcampo(Auxvalor1,25);
	      	this.setValue(Auxvalor);	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex: 'denestpro'+numero,editor: new Ext.form.TextField({allowBlank: false})}
							
]),
sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
				gridep2.addListener('cellclick', click_extra);
				gridep2.render('gridPre1');
				
				break
			case 3:
			DataStoreep3 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDef
			     
			      ),
				data: DatosNuevo
                        });

						gridep3 = new Ext.grid.EditorGridPanel({
						width:770,
						autoScroll:true,
                        border:true,
                        ds:DataStoreep3,
                        cm: new Ext.grid.ColumnModel([
                        new Ext.grid.CheckboxSelectionModel(),
                        {header: "Código", width: 100, sortable: true,dataIndex: 'codestpro'+numero,editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'valid': function(){
    	if(this.getValue()!='')
    	{
    	  Auxvalor1 = this.getValue();
	      Auxvalor = ue_rellenarcampo(Auxvalor1,25);
	      this.setValue(Auxvalor);	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex: 'denestpro3',editor: new Ext.form.TextField({allowBlank: false})}
							
]),
sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
        gridep3.addListener('cellclick', click_extra);
		gridep3.render('gridPre2');
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
                        new Ext.grid.CheckboxSelectionModel(),
                        {header: "Código", width: 100, sortable: true,dataIndex: 'codestpro'+numero,editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'valid': function(){
    	if(this.getValue()!='')
    	{
    	  Auxvalor1 = this.getValue();
	      Auxvalor = ue_rellenarcampo(Auxvalor1,25);
	      this.setValue(Auxvalor);
	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex: 'denestpro'+numero,editor: new Ext.form.TextField({allowBlank: false})}
							
]),
sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
		grid4.addListener('cellclick', click_extra);
		grid4.render('gridPre3');
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
                        new Ext.grid.CheckboxSelectionModel(),
                            {header: "Código", width: 100, sortable: true,dataIndex: 'codestpro'+numero,editor: new Ext.form.TextField({allowBlank: false,listeners: {
    'valid': function(){
    	if(this.getValue()!='')
    	{
    	  Auxvalor1 = this.getValue();
	      Auxvalor = ue_rellenarcampo(Auxvalor1,25);
	      this.setValue(Auxvalor);	     
	     // alert('you changed the text of this input field');
      }
    }
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex: 'denestpro'+numero,editor: new Ext.form.TextField({allowBlank: false})}
							
])
,
sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
            
		grid5.addListener('cellclick', click_extra);	
		grid5.render('gridPre4');
		break
		}  		
}
	
});	

}

function ActualizarData(cod1,cod2,cod3,cod4,nivel)
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
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaEstPre,
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
					 DatosNuevo={"raiz":[{"CODEMP":'',"codestpro1":'',"codestpro2":'',"codestpro3":'',"codestpro4":'',"codestpro5":'',"ESTCLA":'',"ANO_PRESUPUESTO":'',"denestpro1":'',"denestpro2":'',"denestpro3":'',"denestpro4":'',"denestpro5":''}]};
			}
					
				switch(nivel)
				{
					case '1':
						gridep1.store.loadData(DatosNuevo);
						break;
					case '2':
						gridep2.store.loadData(DatosNuevo);
						break;
					case '3':
						gridep3.store.loadData(DatosNuevo);
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
				
function getobjectTb()
{
		Formulario2 = new Ext.Panel({
	    title: 'Estructuras Presupuestarias',
	    height:180,
	    autoScroll:true,
	    contentEl:'formestproPre'
     	});
	
	   	Ext.QuickTips.init(); 
		 tabs= new Ext.TabPanel
		(
        {
            //baseCls:'x-plain',
			renderTo: 'tabs17',
			 //activeTab: 0,
			 		frame:true,
				    autoScroll:true,
                    width:800,
                    height:500,
                    plain: false
		    		,defaults: {frame:true, width:800, height: 200}
                  
         });	
            
}			

function ObtenerGrid(tab)
{
	switch(tab)
	{
		case '0':
			return gridep1;
			break;
		case '1':
			return gridep2;
			break;
		case '2':
			return gridep3;
			break;
		case '3':
			return grid4;
			break;
		case '4':
			return grid4;
			break;
	}    
}


//getDatos('getSesion');
//getNombreEtiquetas();
	


 
              
             




