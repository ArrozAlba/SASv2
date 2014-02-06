var Listo2 = false;
var Oper='';
var DatosNuevo ="";
var tabs='';
var combo6=''; 
var combo7=''; 
var combo8=''; 
var combo9=''; 
var combo10=''; 
var DatosNuevo='';
var valor1='';  
var valor2='';
var anchoCombo=40;
var anchoTextoCombo=600;  
ruta ='../../procesos/sigesp_spe_comboubgeopr.php';
Ext.onReady(function(){
function llenarCombo1()
{   
	var myJSONString ="{'oper':'catestpro','numest':'1','codest1':'','denest1': ''}";
	aux = eval('(' + myJSONString + ')');
	ObjSon=JSON.stringify(aux);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
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
						
		var DataStore1 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
		   
	combo6 = new Ext.form.ComboBox({
    store: DataStore1,
    displayField:'despai',
    valueField:'codpai',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo6',
    editable:false
    
});
combo6.addListener('select',cambio1);

}
});
}

function llenarCombo2(cod1)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '2',
		"cod1": cod1
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
	//	alert(DatosNuevo)	
		
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
						
		var DataStore2 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
		   

	
	combo7 = new Ext.form.ComboBox({
    store: DataStore2,
    displayField:'desest',
    valueField:'codest',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo7',
    editable:false
    
});
combo7.addListener('select',cambio2);
}

});
}
function llenarcombo8(cod1,cod2)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '3',
		"cod1": cod1,
		"cod2": cod2
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
		//	alert(DatosNuevo)	
		
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
						
		var DataStore3 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
		   
	combo8 = new Ext.form.ComboBox({
    store: DataStore3,
    displayField:'denmun',
    valueField:'codmun',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo8',
    editable:false
    
});
combo8.addListener('select',cambio3);
}
});
}

function llenarcombo9(cod1,cod2,cod3)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '4',
		"cod1": cod1,
		"cod2": cod2,
		"cod3": cod3
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
	
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz!=null)
		 {
			
		//	alert(DatosNuevo)	
		
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
						
		var DataStore4 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
		   

	
	combo9 = new Ext.form.ComboBox({
    store: DataStore4,
    displayField:'DENEST4',
    valueField:'CODEST4',
    typeAhead: true,
    mode:'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo9',
    editable:false
    
});
 combo9.addListener('select',cambio4);
}
});
}



function llenarCombo5(cod1,cod2,cod3,cod4)
{   
	var myJSONObject ={
		"oper": 'filtrarEst',
		"numest": '5',
		"cod1": cod1,
		"cod2": cod2,
		"cod3": cod3,
		"cod4": cod4
	};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : ruta,
	disableCaching:false,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		 datos = resultado.responseText;	
		 DatosNuevo = eval('(' + datos + ')');
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

			
			
						
		var DataStore5 =  new Ext.data.Store({
		proxy: new Ext.data.MemoryProxy(DatosNuevo),
		reader: new Ext.data.JsonReader(
		{
			root: 'raiz',               
			id: "id"   
		},
           RecordDef
			),
		   data: DatosNuevo
         });
	combo5 = new Ext.form.ComboBox({
    store: DataStore5,
    displayField:'DENEST5',
    valueField:'CODEST5',
    typeAhead: true,
    mode:'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo10',
    editable:false
    
});
 //combo9.addListener('select',cambio4);
}
});
}




function cambio1()
{
	valor1=combo1.getValue();
	if(combo2=='')
	{
		llenarCombo2(valor1);	
	}
	else
	{
		ActualizarData(valor1,0,0,0,'2');
	}
	
}	 	

function cambio2()
{

	valor2=combo2.getValue();
	if(combo8=='')
	{
		llenarcombo8(valor1,valor2);	
	}
	else
	{
		ActualizarData(valor1,valor2,0,0,'3');
	}
	
}	 

function cambio3()
{
	valor3=combo8.getValue();
	if(combo9=='')
	{
		llenarcombo9(valor1,valor2,valor3);
	}
	else
	{
		ActualizarData(valor1,valor2,valor3,0,'4');
	}

}	
function cambio4()
{
	valor4=combo9.getValue();
	if(combo5=='')
	{
		llenarCombo5(valor1,valor2,valor3,valor4);
	}
	else
	{
		ActualizarData(valor1,valor2,valor3,valor4,'5');
	}

}	


function ActualizarData(cod1,cod2,cod3,cod4,nivel)
{
	DatosEnBlanco = {"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
	
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
	
			if(DatosNuevo.raiz==null)
			{
				 DatosNuevo={"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
			}	
				switch(nivel)
				{
					case '2':
					combo2.clearValue();
					combo2.store.loadData(DatosNuevo);
					combo8.clearValue();
					combo8.store.loadData(DatosEnBlanco);
					combo9.clearValue();
					combo9.store.loadData(DatosEnBlanco);
					combo5.clearValue();
					combo5.store.loadData(DatosEnBlanco);
					break;
					case '3':
					combo8.clearValue();
					combo8.store.loadData(DatosNuevo);
					combo9.clearValue();
					combo9.store.loadData(DatosEnBlanco);
					combo5.clearValue();
					combo5.store.loadData(DatosEnBlanco);
					break;
					case '4':
					combo9.clearValue();
					combo9.store.loadData(DatosNuevo);
					combo5.clearValue();
					combo5.store.loadData(DatosEnBlanco);
					break;
					case '5':
					combo5.store.loadData(DatosNuevo);
					break;
	
				}
			
			
		}
}
});
	
}

llenarCombo1(1);


});