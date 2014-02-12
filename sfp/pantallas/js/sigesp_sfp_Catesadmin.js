/*
 * Ext JS Library 2.0.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * http://extjs.com/license
 */
 
var valorAdActual=''; 
var denAdActual='';
var datosAd = null;
var gridAd = null;
var winAd = null;
var unavez = false;
var parametrosAd='';
var cantidadAd=0;
var rutaAd = '';
var RecordDefAd;
var ListoAd1='';
var datosAdSesion;
var gridAd1='';
var gridAd2='';
var gridAd3='';
var gridAd4='';
var gridAd5='';
var valorAd1='';
var valorAd2='';
var valorAd3='';
var valorAd4='';
var valorAd5='';
var nivelAd1='';
var nivelAd2='';
var nivelAd3='';
var nivelAd4='';
var nivelAd5='';
var DataStoreAd1='';
var DataStoreAd2='';
var DataStoreAd3='';
var DataStoreAd4='';
var DataStoreAd5='';
var DataStoreAd6='';
var DataStoreAd7='';
var ListoAd1 = false;
var ListoAd2 = false;
var Oper='';
var datosAdNuevo ="";
var tabsAd='';
var ListoUltimoAd="";
 
rutaAd ='../../procesos/sigesp_sfp_esadminpr.php';

 // basic tabsAd 1, built from existing content
function getDatosAd(Metodo)
{
	ListoAd1=false;
	var myJSONObject ={
		"oper": Metodo, 
		"numest":'1',
		"CODEST": "", 
		"DENEST": ""
};	
	getobjectTbAd();
	ObjSon=JSON.stringify(myJSONObject);
	parametrosAd = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaAd,
	params : parametrosAd,
	method: 'POST',
	success: function ( resultado, request) 
	{ 
		datosAd = resultado.responseText;
		if(datosAd!='')
		 {
		 	arr = datosAd.split("|");
		  	jsonserv = arr[1];
		  	cantidadAd = arr[0];
			var mijson = eval('(' + jsonserv + ')');
		 	switch(Metodo)
		 	{
		 		case 'getSesion':
		 	
		 			for(i=0;i<parseInt(cantidadAd);i++)
		 			{
		 				
		 				
		 				if(i==0)
		 				{
							getgridAd(1);
						}
						else
						{
							aux2=i+1;
							getgridAd2(aux2);
						}
		 				agregarTabAd(mijson.raiz[i].nombre_pest,'gridAd'+i);
					
					 }
					 ListoAd1=true;
					 MostrarForma(false);
					 tabsAd.activate(0);
					 habilitarUnaAd(0); 
					 break;
		 	}
		 
		 }
	}	
	
});	
	
}

function ManejarTabActivoAd(tab)
{
//alert(tab)
	num = parseInt(tab.id)+1;
	//alert(eval('grid'+num));
	//alert(grid1);
	
	if(ListoAd1)
	{
		
		switch(parseInt(tab.id))
		{
			case 1:
			valorAd1= gridAd1.getSelectionModel().getSelected().get('coduac');
			den1= gridAd1.getSelectionModel().getSelected().get('denuac');
			nivelAd1 = gridAd1.getSelectionModel().getSelected().get('nivel');
			tabanterior = tabsAd.getItem('0').title;
			Ext.get('nivelAd1').dom.innerHTML=tabanterior;
			Ext.get('valorAdnivel1').dom.innerHTML=den1;
			if(gridAd2!='')
			{
			//	habilitarUna(parseInt(tab.id));	
				ActualizarDataAd(valorAd1,nivelAd1,'2');
			}
			
			MostrarForma(true);
			break;
			case 2:
			
			valorAd2= gridAd2.getSelectionModel().getSelected().get('coduac');
			den2= gridAd2.getSelectionModel().getSelected().get('denuac');
			nivelAd2 = gridAd2.getSelectionModel().getSelected().get('nivel');
			tabanterior = tabsAd.getItem('1').title;
			Ext.get('nivelAd2').dom.innerHTML=tabanterior;
			Ext.get('valorAdnivel2').dom.innerHTML=den2;
			if(gridAd3!='')
			{
				//deshabilitarAntAd(2);
				ActualizarDataAd(valorAd2,nivelAd2,'3');
			}
			MostrarForma(true);
			break;
			case 3:
			//alert('2');
			valorAd3= gridAd3.getSelectionModel().getSelected().get('coduac');
			den3= gridAd3.getSelectionModel().getSelected().get('denuac');
			nivelAd3 = gridAd3.getSelectionModel().getSelected().get('nivel');
			tabanterior = tabsAd.getItem('2').title;
			Ext.get('nivelAd3').dom.innerHTML=tabanterior;
			Ext.get('valorAdnivel3').dom.innerHTML=den3;
			if(gridAd4!='')
			{
				//deshabilitarAnt(3);
				ActualizarDataAd(valorAd3,nivelAd3,'3');
			}
			MostrarForma(true);
			break;
			case 4:
			//alert('2');
			valorAd4= gridAd4.getSelectionModel().getSelected().get('coduac');
			den4= gridAd4.getSelectionModel().getSelected().get('denuac');
			nivelAd4 = gridAd4.getSelectionModel().getSelected().get('nivel');
			//alert(valor2);
			tabanterior = tabs.getItem('3').title;
			Ext.get('nivelAd4').dom.innerHTML=tabanterior;
			Ext.get('valorAdnivel4').dom.innerHTML=den4;
			if(gridAd5!='')
			{
				//deshabilitarAntAd(4);
				ActualizarDataAd(valorAd4,nivelAd3,'4');
			}
			MostrarForma(true);
			break;
			default:
			MostrarForma(false);
			break;	
		}
		
	}
}



function EstadoInicialAd()
{

	Ext.get('nivelAd1').dom.innerHTML='';
	Ext.get('valorAdnivel1').dom.innerHTML='';
	Ext.get('nivelAd2').dom.innerHTML='';
	Ext.get('valorAdnivel2').dom.innerHTML='';
	Ext.get('nivelAd3').dom.innerHTML='';
	Ext.get('valorAdnivel3').dom.innerHTML='';
	Ext.get('nivelAd4').dom.innerHTML='';
	Ext.get('valorAdnivel4').dom.innerHTML='';
	Ext.get('nivelAd5').dom.innerHTML='';
	Ext.get('valorAdnivel5').dom.innerHTML='';
	tabsAd.setActiveTab('0');
	
}




function PonerUltimoTituloAd(tab)
{

	ObjActual = parseInt(tab)+1;
	Nivel = 'nivelAd'+ObjActual.toString();
	valorAdNivel = 'valorAdnivel'+ObjActual.toString();
	gridAdActual = ObtenergridAd(tab);
	tabanterior = tabsAd.getItem(tab).title;
	valorAdActual= gridAdActual.getSelectionModel().getSelected().get('coduac');
	denAdActual= gridAdActual.getSelectionModel().getSelected().get('denuac');
	tabanterior = tabsAd.getItem(tab).title;
	Ext.get(Nivel).dom.innerHTML=tabanterior+':';
	Ext.get(valorAdNivel).dom.innerHTML=denAdActual;	
	ListoUltimoAd=true;

}


function click_extraAd()
{

	habilitarUnaAd(tabsAd.getActiveTab().id,true) 

}
	
	
function deshabilitarAntAd(tab)
{			
		if(tab>1)
		{
			num2 = tab-2;
			tabsAd.getItem(num2).disable();	
		}
}

function habilitarUnaAd(tab,paso)
{
	UltiActual = cantidadAd-1;
	if(UltiActual){
	for(var r=0;r<cantidadAd;r++)
	{
		num2 = r+1;
		if(r==tab)
		{	
			
			if(r>0)
			{
			
				if(r==UltiActual)
				{
					tabsAd.getItem(r-1).enable();
					PonerUltimoTituloAd(tab);
					
				}
				else
				{
					tabsAd.getItem(num2).enable();
					tabsAd.getItem(r-1).enable();
					tabsAd.setActiveTab(num2);
					r++;
				}
			}
			else 
			{
				if(paso)
				{
					tabsAd.getItem(num2).enable();
					tabsAd.setActiveTab(num2);
					r++;
				}
				tabsAd.getItem(r).enable();		
			}
			
		}
		else
		{
			//tabsAd.getItem(r).disable();
		}
	}
	}
else
{
	PonerUltimoTituloAd(tab);
}
}

function MostrarForma(valorAd)
{
	if(valorAd==false)
	{
	//	Ext.get('formestprog').dom.style.display='none';
		
	}
	else
	{
	//	Ext.get('formestprog').dom.style.display='block';
	}
	
}

function getgridAd2(numero)
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
			DataStoreAd2 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDef
			     
			      ),
				data: DatosNuevo
                    });	
			 gridAd2 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStoreAd2,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 100, sortable: true,dataIndex: 'coduac'},
                            {header: "Denominación", width: 600, sortable: true, dataIndex: 'denuac'}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
				gridAd2.addListener('cellclick', click_extraAd);
				gridAd2.render('gridAd1');
				break
			case 3:
			DataStoreAd3 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDef
			     
			      ),
				data: DatosNuevo
                        });
			
			 gridAd3 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStoreAd3,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 150, sortable: true,dataIndex: 'coduac'},
                            {header: "Denominación", width: 500, sortable: true, dataIndex:'denuac'}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
        gridAd3.addListener('cellclick', click_extraAd);
		gridAd3.render('gridAd2');
		break
		case 4:
			DataStoreAd4 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDef
			     
			      ),
				data: DatosNuevo
                        });
		
			 gridAd4 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStoreAd4,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 150, sortable: true,dataIndex: 'coduac'},
                            {header: "Denominación", width: 500, sortable: true, dataIndex: 'denuac'}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
		gridAd4.addListener('cellclick', click_extraAd);
		gridAd4.render('gridAd3');
		break
		case 5:
			DataStoreAd5 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',               
			    id: "id"   
			    },
                RecordDef	     
			      ),
				data: DatosNuevo
                        });			
			 gridAd5 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStoreAd5,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 150, sortable: true,dataIndex: 'coduac'},
                            {header: "Denominación", width: 500, sortable: true, dataIndex: 'denuac'}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
            
		gridAd5.addListener('cellclick', click_extraAd);	
		gridAd5.render('gridAd4');
		break
			case 6:
			DataStoreAd6 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDef
			     
			      ),
				data: DatosNuevo
                        });			
			 gridAd6 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStoreAd6,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 150, sortable: true,dataIndex: 'coduac'},
                            {header: "Denominación", width:500, sortable: true, dataIndex: 'denuac'}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
            
		gridAd6.addListener('cellclick', click_extraAd);	
		gridAd6.render('gridAd5');
		break

	
			case 7:
			DataStoreAd7 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDef
			      ),
				data: DatosNuevo
                        });			
			 gridAd7 = new Ext.grid.EditorGridPanel({
			 width:770,
			 autoScroll:true,
                        border:true,
                        ds:DataStoreAd7,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 150, sortable: true,dataIndex: 'coduac'},
                            {header: "Denominación", width:500, sortable: true, dataIndex: 'denuac'}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
            
		gridAd7.addListener('cellclick', click_extraAd);	
		gridAd7.render('gridAd6');
		break
	}   		  		
}
	


function getgridAd(numero)
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
	url : rutaAd,
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
			DataStoreAd1 =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                RecordDef
			     
			      ),
				data: DatosNuevo
                        });
			 gridAd1 = new Ext.grid.EditorGridPanel({
			 width:770,
			 height:100,
			 autoScroll:true,
                        border:true,
                        ds:DataStoreAd1,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 150, sortable: true,dataIndex: 'coduac'},
  {header: "Denominación", width: 350, sortable: true,dataIndex:'denuac'}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:true}),
                        viewConfig:{
                            forceFit:true
                        },
			//autoHeight:true,
			stripeRows: true
            });
				gridAd1.addListener('cellclick', click_extraAd);
				gridAd1.render('gridAd0');
				break;
				
	}   		  		
 }
	
});	

}


function ActualizarDataAd(cod1,nivel1,nivel)
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
		"nivel_p": nivel1
	};



	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaAd,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		datosAd = resultado.responseText;
		 if(datosAd!='')
		 {
			var datosAdNuevo = eval('(' + datosAd + ')');
			if(datosAdNuevo.raiz==null)
			{
				 var datosAdNuevo={"raiz":[{"coduac":'',"denuac":'',"denuac_p":''}]};
			}
					
				switch(nivel)
				{
					case '1':
						gridAd1.store.loadData(datosAdNuevo);
						break;
					case '2':
						gridAd2.store.loadData(datosAdNuevo);
						break;
					case '3':
						gridAd3.store.loadData(datosAdNuevo);
						break;
					case '4':
						gridAd4.store.loadData(datosAdNuevo);
						break;
					case '5':
						gridAd5.store.loadData(datosAdNuevo);
						break;
	
				}
			
			
		}
	
}
});
	
}

function agregarTabAd(titulo,Elemento)
{
	//alert(Elemento);
        tabsAd.add({
        title: titulo,
        listeners: {activate: ManejarTabActivoAd},
        contentEl: Elemento,
        id:Elemento.substr(Elemento.length-1,1),
        closable:false
        }).show();
}
				
function getobjectTbAd()
{
		FormularioAd = new Ext.Panel({
	    title: 'Estructura Administrativa',
	    height:120,
	    contentEl:'formestproAd',
     	});
	
	   	Ext.QuickTips.init(); 
		 tabsAd= new Ext.TabPanel
		(
        {
            //baseCls:'x-plain',
			renderTo: 'tabsAd',
			 //activeTab: 0,
			 		frame:true,
				    autoScroll:true,
                    width:800,
                    height:500,
                    plain: false
		    		,defaults: {frame:true, width:800, height: 200}
                  
            });	
           
}			


function ObtenergridAd(tab)
{
	//alert(tab);
	switch(tab)
	{
		case '0':
			return gridAd1;
			break;
		case '1':
			return gridAd2;
			break;
		case '2':
			return gridAd3;
			break;
		case '3':
			return gridAd4;
			break;
		case '4':
			return gridAd5;
			break;
	}    
	
}


//getdatosAdAd('getSesion');
//getNombreEtiquetas();
	


 
              
             




