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
var Listo1 = false;
var Listo2 = false;
var Oper='';
var DatosNuevo ="";
var tabs='';
var mijson="";
 
ruta ='../../procesos/sigesp_sfp_estprogpr.php';
pantalla='sigesp_sfp_estprog.php';
Ext.onReady(function()
{

ObtenerSesion(ruta,pantalla)
function getDatos(Metodo)
{
	var myJSONObject ={
		"oper": Metodo, 
		"numest":'1',
		"codestpro": "", 
		"denestpro": "",
		"numcar": ""
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
		 //	alert(datos);
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
	valor1= grid1.getSelectionModel().getSelected().get('codestpro1');
	den1= grid1.getSelectionModel().getSelected().get('denestpro1');
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
	valor2= grid2.getSelectionModel().getSelected().get('codestpro2');
	den2= grid2.getSelectionModel().getSelected().get('denestpro2');
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
	valor3= grid3.getSelectionModel().getSelected().get('codestpro3');
	den3= grid3.getSelectionModel().getSelected().get('denestpro3');
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
	valor4= grid4.getSelectionModel().getSelected().get('codestpro4');
	den4= grid4.getSelectionModel().getSelected().get('denestpro4');
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


function formatDate(value)
{
    return value ? value.dateFormat('d-M-Y') : '';
};


function getgrid(numero)
{
	Tipo=
	[
		['Proyecto','P'],
		['Acción Centralizada','A']
	]	
	var storeTipo = new Ext.data.SimpleStore
	(
		{
	        fields: ['col','tipo'],
	        data : Tipo // from states.js
	    }
	);
	
	var ComboTipo = new Ext.form.ComboBox({
		  store :storeTipo,
		  editable:false,
		  displayField:'tipo',
		  diplayValue:'tipo',
		  //hiddenName
		  name: 'tipo',
		  id:'tipo',
		  typeAhead: true,
	      triggerAction:'all',
	      mode: 'local'
		})
	
	Auxnum = numero;
	var myJSONString ="{'oper': 'catestpro', 'numest':"+numero+",'codestpro"+numero+"': '','denestpro"+numero+"': ''";

	if(parseInt(Auxnum)>1)
	{
		for(var ind=1;ind<Auxnum;ind++)
		{
			myJSONString = myJSONString +",'codestpro"+ind+"':''"; 
		}
	}
	
				
			RecordDef = Ext.data.Record.create
			(
				[
					{name: 'codemp'}, 
					{name: 'codestpro1'},
					{name: 'codestpro2'},
					{name: 'codestpro3'},
					{name: 'codestpro4'},
					{name: 'codestpro5'},
					{name: 'estcla'},
					{name: 'ano_presupuesto'},
					{name: 'coduac'},
					{name: 'denestpro1'},
					{name: 'denestpro2'},
					{name: 'denestpro3'},
					{name: 'denestpro4'},
					{name: 'denestpro5'},
					{name: 'fecha_ini',type:'date',dateFormat:'d/m/Y'},
					{name: 'fecha_fin',type:'date',dateFormat:'d/m/Y'},
					{name: 'costototal'},
					{name: 'responsable'}		
				]
			);
				
			switch(numero)
			{
				case 1:
				ActualizarData('0','0','3','4','1');
				DataStore1 =  new Ext.data.Store({
				proxy: new Ext.data.MemoryProxy(DatosNuevo),
				reader: new Ext.data.JsonReader({
			    root: 'raiz',                
			    id: "id"   
			    },
                RecordDef
			      )
                });
                        
			 grid1 = new Ext.grid.EditorGridPanel({
			 width:935,
			 height:280,
			 autoScroll:true,
                        border:true,
                        ds:DataStore1,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 100, sortable: true,dataIndex: 'codestpro'+numero,editor: new Ext.form.TextField({allowBlank: false,id:'codestr',enableKeyEvents:true,listeners: {

    'keypress':function(Obj,e)
    {
	    	var whichCode = e.keyCode; 
	    	if (whichCode == 13)  
			{		 
				grid1.startEditing(filaActual,1);	
			}
    }
  }
})},
                            {header: "Denominación", width: 330, sortable: true, dataIndex: 'denestpro'+numero,editor: new Ext.form.TextField({allowBlank: false})},
                            
                            {header: "Tipo", width: 70, sortable: true, dataIndex: 'estcla',editor:ComboTipo},
                            {header: "Fecha de Inicio", width: 100, sortable: true, dataIndex:'fecha_ini',editor: new Ext.form.DateField({allowBlank:true,format:'d/m/Y',id:'fecha_ini'}),renderer: Ext.util.Format.dateRenderer('d/m/Y')},
                            {header: "Fecha Final", width: 100, sortable: true, dataIndex: 'fecha_fin',editor: new Ext.form.DateField({allowBlank: true,id:'fecha_fin'}),renderer: Ext.util.Format.dateRenderer('d/m/Y')},
                            {header: "Persona Responsable", width: 150, sortable: true, dataIndex:'responsable',editor: new Ext.form.TextField({allowBlank: true,id:'responsable',value:''})},
{header: "Costo Total", width: 100, sortable: true, dataIndex: 'costototal',editor: new Ext.form.NumberField({allowBlank:true,id:'costototal',decimalPrecision:2,decimalSeparator:','})}                            
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:true}),
                        viewConfig:{
                            forceFit:true
                        },
			
			stripeRows: true
            });
			    grid1.on('afteredit', function(Obj){
			    
			    	if(Obj.value!='' && Obj.field=='codestpro1')
			    	{
			    	  Auxvalor1 = Obj.value;
				      Auxvalor = ue_rellenarcampo(Auxvalor1,mijson.raiz[0].numcar);
				      grid1.getSelectionModel().getSelected().set('codestpro1',Auxvalor);
				     // alert('you changed the text of this input field');
			      	}
			    }
    			)
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
			     
			      )
                  });

			 grid2 = new Ext.grid.EditorGridPanel({
			 width:770,
			 height:250,
			 autoScroll:true,
                        border:true,
                        ds:DataStore2,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 100, sortable: true,dataIndex: 'codestpro'+numero,editor: new Ext.form.TextField({allowBlank: false,enableKeyEvents:true,listeners: {
        'keypress':function(Obj,e){
    	var whichCode = e.keyCode; 
    	if (whichCode == 13)  
		{		 
			grid2.startEditing(filaActual,1);
			
		}
    }
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex: 'denestpro'+numero,editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			
			stripeRows: true
            });
            
            	grid2.on('afteredit',function(Obj){
		    	if(Obj.value!='' && Obj.field=='codestpro2')
		    	{
		    		Auxvalor1 = Obj.value;
			      	Auxvalor = ue_rellenarcampo(Auxvalor1,mijson.raiz[1].numcar);
			      	grid2.getSelectionModel().getSelected().set('codestpro2',Auxvalor);	     
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
			     
			      )
                        });
			
						grid3 = new Ext.grid.EditorGridPanel({
						width:770,
						autoScroll:true,
                        border:true,
                        ds:DataStore3,
                        cm: new Ext.grid.ColumnModel([
                        {header: "Código", width: 100, sortable: true,dataIndex: 'codestpro'+numero,editor: new Ext.form.TextField({allowBlank: false,enableKeyEvents:true,listeners: {
     	'keypress':function(Obj,e){
    	var whichCode = e.keyCode; 
    	if (whichCode == 13)  
		{		 
			grid3.startEditing(filaActual,1);
		}
    }
    	
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex: 'denestpro3',editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
            
        grid3.on('afteredit', function(Obj){
    	if(Obj.value!='' && Obj.field=='codestpro3')
    	{
    	  Auxvalor1 =Obj.value;
	      Auxvalor = ue_rellenarcampo(Auxvalor1,mijson.raiz[2].numcar);
	      grid3.getSelectionModel().getSelected().set('codestpro3',Auxvalor);     
	     // alert('you changed the text of this input field');
      	}
    	})    
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
                            {header: "Código", width: 100, sortable: true,dataIndex: 'codestpro'+numero,editor: new Ext.form.TextField({allowBlank: false,enableKeyEvents:true,listeners: {
       'keypress':function(Obj,e){
    	var whichCode = e.keyCode; 
    	if (whichCode == 13)  
		{		 
			grid4.startEditing(filaActual,1);
		}
    }
    
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex: 'denestpro'+numero,editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
            
     grid4.on('change', function(Obj)
    {
    	if(Obj.value!='' && Obj.field=='codestpro4')
    	{
    	  Auxvalor1 = Obj.value;
	      Auxvalor = ue_rellenarcampo(Auxvalor1,mijson.raiz[3].numcar);
	      grid4.getSelectionModel().getSelected().set('codestpro4',Auxvalor);

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
			      )
			      
                        });			
			 grid5 = new Ext.grid.EditorGridPanel({
			 width:770,
			 id:'grid5',
			 autoScroll:true,
                        border:true,
                        ds:DataStore5,
                        cm: new Ext.grid.ColumnModel([
                            {header: "Código", width: 100, sortable: true,dataIndex: 'codestpro'+numero,editor: new Ext.form.TextField({allowBlank: false,enableKeyEvents:true,listeners: {
      'keypress':function(Obj,e){
    	var whichCode = e.keyCode; 
    	if (whichCode == 13)  
		{		 
			grid5.startEditing(filaActual,1);
		}
    }
    
  }
})},
 {header: "Denominación", width: 350, sortable: true, dataIndex: 'denestpro'+numero,editor: new Ext.form.TextField({allowBlank: false})}
							
]),
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig:{
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
            });
        grid5.on('afteredit',function(Obj){
    	if(Obj.value!='' && Obj.field=='codestpro5')
    	{
    	  Auxvalor1 = Obj.value;
	  	  Auxvalor = ue_rellenarcampo(Auxvalor1,mijson.raiz[4].numcar);
	      grid5.getSelectionModel().getSelected().set('codestpro5',Auxvalor);   
		     // alert('you changed the text of this input field');
	    }
	    }
	    )        
		grid5.addListener('cellclick', click_extra);	
		grid5.render('grid4');
		break
		}
	   		  		

}

function ActualizarData(cod1,cod2,cod3,cod4,nivel)
{
	cod1=ue_rellenarcampo(cod1,25);
	cod2=ue_rellenarcampo(cod2,25);
	cod3=ue_rellenarcampo(cod3,25);
	cod4=ue_rellenarcampo(cod4,25); 	
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest":nivel,
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
		  //alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			if(DatosNuevo.raiz==null)
			{
					DatosNuevo={"raiz":[{"codemp":'',"codestpro1":'',"codestpro2":'',"codestpro3":'',"codestpro4":'',"codestpro5":'',"estcla":'',"ano_presupuesto":'',"denestpro1":'',"denestpro2":'',"denestpro3":'',"denestpro4":'',"denestpro5":''}]};
			}
				//alert(nivel);	
				switch(nivel)
				{
					case '1':
					//	alert('sas');
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
			frame:true,
			autoScroll:true,
                    width:950,
                    height:500,
				    style:'margin-left:40px;margin-top:40px',
                    plain: false
		    		,defaults: {frame:true, width:800, height: 200}
                  
            });	
}			
Ext.get('BtnGrabar').on('click', function()
{
	tabActual = tabs.getActiveTab().id;
	Nivel = parseInt(tabActual)+1;
	TipoProyecto = grid1.getSelectionModel().getSelected().get('estcla');
	//alert()
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
	var reg = "{'oper':'"+ eve + "','numest':'1','datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
		var dt = new Date(numDatos[i].get('fecha_ini'));
		fec_in = dt.format('d/m/Y');	
		var dt2 = new Date(numDatos[i].get('fecha_fin'));
		fec_fin = dt2.format('d/m/Y');	
		codest1 = ue_rellenarcampo(numDatos[i].get('codestpro1'),25);
		if(i==0)
		{
			if(numDatos[i].get('estcla')=='')
			{
				Ext.MessageBox.alert('Mensaje', 'Debe Indicar el tipo de estructura');
				return false;
			}
			if(numDatos[i].get('denestpro1')=='')
			{
				Ext.MessageBox.alert('Mensaje', 'Debe Indicar el nombre de la estructura');
				return false;
			}
			reg = reg + "{'codemp':'0001','codestpro1':'" + codest1 +"','estcla':'"+numDatos[i].get('estcla')+"','ano_presupuesto':'2008','coduac':'UU','denestpro1':'" + numDatos[i].get('denestpro1') +"','fecha_ini':'"+fec_in+"','fecha_fin':'"+fec_fin+"','responsable':'"+numDatos[i].get('responsable')+"','costototal':'"+numDatos[i].get('costototal')+"'}";
		
		}	
		else
		{
			reg = reg + ",{'codemp':'0001','codestpro1':'" + codest1 +"','estcla':'"+numDatos[i].get('estcla')+"','ano_presupuesto':'2008','coduac':'UU','denestpro1':'" + numDatos[i].get('denestpro1') +"','fecha_ini':'"+fec_in+"','fecha_fin':'"+fec_fin+"','responsable':'"+numDatos[i].get('resonsable')+"','costototal':'"+numDatos[i].get('costototal')+"'}";
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
		codest2= ue_rellenarcampo(numDatos[i].get('codestpro2'),25);
		valor1=ue_rellenarcampo(valor1,25);
		
		if(i==0)
		{
			if(numDatos[i].get('denestpro2')=='')
			{
				Ext.MessageBox.alert('Mensaje', 'Debe Indicar el nombre de la estructura');
				return false;
			}	
			reg = reg + "{'codemp':'0001','codestpro1':'" + valor1 +"','codestpro2':'" + codest2 +"','estcla':'"+TipoProyecto+"','ano_presupuesto':'2008','coduac':'UU','denestpro2':'" + numDatos[i].get('denestpro2') +"'}";
		
		}	
		else
		{	
			reg = reg + ",{'codemp':'0001','codestpro1':'" + valor1 +"','codestpro2':'" + codest2 +"','estcla':'"+TipoProyecto+"','ano_presupuesto':'2008','coduac':'UU','denestpro2':'" + numDatos[i].get('denestpro2') +"'}";		
		}
			
	}
	reg = reg + "]}";
	break;
	case 3:
	numDatos = DataStore3.getModifiedRecords();
	var reg = "{'oper':'"+ eve + "','numest':"+Nivel+",'datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{
		codest3= ue_rellenarcampo(numDatos[i].get('codestpro3'),25);
		valor1=ue_rellenarcampo(valor1,25);	
		valor2=ue_rellenarcampo(valor2,25);	
		
		if(i==0)
		{		
			if(numDatos[i].get('denestpro3')=='')
			{
				Ext.MessageBox.alert('Mensaje', 'Debe Indicar el nombre de la estructura');
				return false;
			}	
				
			reg = reg + "{'codemp':'0001','codestpro1':'" + valor1 +"','codestpro2':'" + valor2 +"','codestpro3':'" + codest3 +"','estcla':'"+TipoProyecto+"','ano_presupuesto':'2008','coduac':'UU','denestpro3':'" + numDatos[i].get('denestpro3') +"'}";
		}	
		else
		{	
			reg = reg + ",{'codemp':'0001','codestpro1':'" + valor1 +"','codestpro2':'" + valor2 +"','codestpro3':'" +codest3 +"','estcla':'"+TipoProyecto+"','ano_presupuesto':'2008','coduac':'UU','denestpro3':'" + numDatos[i].get('denestpro3') +"'}";
		}
	}
	reg = reg + "]}";
	break;
	case 4:
	numDatos = DataStore4.getModifiedRecords();
	var reg = "{'oper':'"+ eve + "','numest':'4','datos':[";
	for(var i=0;i<=numDatos.length-1;i++)
	{	
		codest4= ue_rellenarcampo(numDatos[i].get('codestpro4'),25);
		valor1=ue_rellenarcampo(valor1,25);	
		valor2=ue_rellenarcampo(valor2,25);
		valor3=ue_rellenarcampo(valor3,25);	
		if(i==0)
		{
			if(numDatos[i].get('denestpro4')=='')
			{
				Ext.MessageBox.alert('Mensaje', 'Debe Indicar el nombre de la estructura');
				return false;
			}	
			
			
		reg = reg + "{'codemp':'0001','codestpro1':'" + valor1 +"','codestpro2':'" + valor2 +"','codestpro3':'" + valor3 +"','codestpro4':'" + codest4 +"','estcla':'"+TipoProyecto+"','ano_presupuesto':'2008','coduac':'UU','denestpro4':'" + numDatos[i].get('denestpro4') +"'}";
		
		}	
		else
		{
			
		reg = reg + ",{'codemp':'0001','codestpro1':'" + valor1 +"','codestpro2':'" + valor2 +"','codestpro3':'" + valor3 +"','codestpro4':'" + codest4 +"','estcla':'"+TipoProyecto+"','ano_presupuesto':'2008','coduac':'UU','denestpro4':'" + numDatos[i].get('denestpro4') +"'}";

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
		codest5= ue_rellenarcampo(numDatos[i].get('codestpro5'),25);
		valor1=ue_rellenarcampo(valor1,25);	
		valor2=ue_rellenarcampo(valor2,25);
		valor3=ue_rellenarcampo(valor3,25);	
		valor4=ue_rellenarcampo(valor4,25);	
		if(i==0)
		{
			if(numDatos[i].get('denestpro5')=='')
			{
				Ext.MessageBox.alert('Mensaje', 'Debe Indicar el nombre de la estructura');
				return false;
			}	
			
			
			reg = reg + "{'codemp':'0001','codestpro1':'" + valor1 +"','codestpro2':'" + valor2 +"','codestpro3':'" + valor3 +"','codestpro4':'" +valor4 +"','codestpro5':'" + codest5 +"','estcla':'"+TipoProyecto+"','ano_presupuesto':'2008','coduac':'UU','denestpro5':'" + numDatos[i].get('denestpro5') +"'}";
		}	
		else
		{
			
		reg = reg + ",{'codemp':'0001','codestpro1':'" + valor1 +"','codestpro2':'" + valor2 +"','codestpro3':'" + valor3 +"','codestpro4':'" +valor4 +"','codestpro5':'" + codest5 +"','estcla':'"+TipoProyecto+"','ano_presupuesto':'2008','coduac':'UU','denestpro5':'" + numDatos[i].get('denestpro5') +"'}";

		}
			
	}
	reg = reg + "]}";
	break;

}
	//alert(reg);
	Obj= eval('(' + reg + ')');
	ObjSon=JSON.stringify(Obj);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultad, request ){ 
        datos = resultad.responseText;
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
GridActual = ObtenerGrid(tabActual);
//alert(GridActual.id);
if(Oper!="incluyendo")
{

		 var p = new RecordDef
			 (
	            {
					codestpro1:'',
					codestpro2:'',
					codestpro3:'',
					codestpro5:'',
					codestpro4:'',
					denestpro2: '', 
		            codemp: '',
		            estcla: '',
					ano_presupuesto: '',	
					coduac: '',
					denestpro1: '',
					denestpro2: '',
					denestpro3: '',
					denestpro4: '',
					denestpro5: '',     
					responsable:''       
				}
	                   
	          );
	              
	    next = GridActual.store.getCount();   
		if(next==1)
		{
			codigo1 = GridActual.store.getRange(0,1);
			codigo2 = codigo1[0].get('codestpro1');
			if(codigo2=='')
			{
			//	GridActual.store.insert(0, p);
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
		}
		else if(next==0)
		{
				GridActual.store.insert(0,p);
				GridActual.startEditing(0, 0);
				GridActual.getSelectionModel().selectRow(0);	
				filaActual=0;			
		}
		else
		{

			codigo1 = GridActual.store.getRange(0,1);
			codigo2 = codigo1[1].get('codestpro1');
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
	TipoProyecto = grid1.getSelectionModel().getSelected().get('estcla');
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
	tabActual = tabs.getActiveTab().id;
	Nivel = parseInt(tabActual)+1;
	switch(Nivel)
	{
	case 1:
	valor1 = grid1.getSelectionModel().getSelected().get('codestpro1');
	valor1=ue_rellenarcampo(valor1,25);	
	//alert(numDatos[0]);
	var reg = "{'oper':'"+eve+"','numest':'1','datos':[";
	reg = reg + "{'codemp':'0001','codestpro1':'" + valor1 +"','estcla':'"+TipoProyecto+"','ano_presupuesto':'2008','CODUAC':'UU','denestpro1':'888'}";
	
	reg = reg + "]}";
	break;
	case 2:
	valor2 = grid2.getSelectionModel().getSelected().get('codestpro2');
	valor1=ue_rellenarcampo(valor1,25);
	valor2=ue_rellenarcampo(valor2,25);			
	var reg = "{'oper':'"+eve+"','numest':'2','datos':[";
	reg = reg + "{'codemp':'0001','codestpro1':'" + valor1 +"','codestpro2':'" + valor2 +"','estcla':'"+TipoProyecto+"','ano_presupuesto':'2008','coduac':'UU','denestpro1':'888'}";
	reg = reg + "]}";	
	break;
	case 3:
	valor3 = grid3.getSelectionModel().getSelected().get('codestpro3');
	valor1=ue_rellenarcampo(valor1,25);
	valor2=ue_rellenarcampo(valor2,25);	
	valor3=ue_rellenarcampo(valor3,25);		
	var reg = "{'oper':'"+eve+"','numest':'3','datos':[";
	reg = reg + "{'codemp':'0001','codestpro1':'" + valor1 +"','codestpro2':'" + valor2 +"','codestpro3':'" + valor3 +"','estcla':'"+TipoProyecto+"','ano_presupuesto':'2008','coduac':'UU','denestpro1':'888'}";
	reg = reg + "]}";	
	break;
	case 4:
	valor4 = grid4.getSelectionModel().getSelected().get('codestpro4');
	valor1=ue_rellenarcampo(valor1,25);
	valor2=ue_rellenarcampo(valor2,25);	
	valor3=ue_rellenarcampo(valor3,25);	
	valor4=ue_rellenarcampo(valor4,25);		
	var reg = "{'oper':'"+eve+"','numest':'4','datos':[";
	reg = reg + "{'codemp':'0001','codestpro1':'" + valor1 +"','codestpro2':'" + valor2 +"',,'codestpro3':'" + valor3 +"','codestpro4':'" + valor4 +"','estcla':'"+TipoProyecto+"','ano_presupuesto':'2008','coduac':'UU','denestpro1':'888'}";
	reg = reg + "]}";	
	break;
	case 5:
	valor5 = grid5.getSelectionModel().getSelected().get('codestpro5');
	valor1=ue_rellenarcampo(valor1,25);
	valor2=ue_rellenarcampo(valor2,25);	
	valor3=ue_rellenarcampo(valor3,25);	
	valor4=ue_rellenarcampo(valor4,25);	
	valor5=ue_rellenarcampo(valor5,25);		
	var reg = "{'oper':'"+eve+"','numest':'5','datos':[";
	reg = reg + "{'codemp':'0001','codestpro1':'" + valor1 +"','codestpro2':'" + valor2 +"',,'codestpro3':'" + valor3 +"','codestpro4':'" + valor4 +"','codestpro5':'" + valor5 +"','estcla':'"+TipoProyecto+"','ano_presupuesto':'2008','coduac':'UU','denestpro1':'888'}";
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
			success: function ( resultad, request ){ 
				 datos = resultad.responseText;
				var Registros = datos.split("|");
				 if (Registros[1] == '1')
				 {
					Ext.MessageBox.alert('Mensaje','Registro '+Mensa + ' con ï¿½xito');
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

 
              
             




