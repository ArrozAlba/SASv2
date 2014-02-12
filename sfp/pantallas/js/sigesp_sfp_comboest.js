var Listo2 = false;
var Oper='';
var DatosNuevo ="";
var tabs='';
var combo11=''; 
var combo12=''; 
var combo13=''; 
var combo14=''; 
var combo15=''; 
var DatosNuevo='';
var valor1='';  
var valor2='';
var anchoCombo=40;
var anchoTextoCombo=600;  
ruta ='../../procesos/sigesp_sfp_comboestpr.php';
Ext.onReady(function(){
function llenarCombo11()
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
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODESTPRO1":'',"CODESTPRO2":'',"CODESTPRO3":'',"CODESTPRO4":'',"CODESTPRO5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENESTPRO1":'',"DENESTPRO2":'',"DENESTPRO3":'',"DENESTPRO4":'',"DENESTPRO5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODESTPRO1'},
			{name: 'CODESTPRO2'},
			{name: 'CODESTPRO3'},
			{name: 'CODESTPRO4'},
			{name: 'CODESTPRO5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENESTPRO1'},
			{name: 'DENESTPRO2'},
			{name: 'DENESTPRO3'},
			{name: 'DENESTPRO4'},
			{name: 'DENESTPRO5'}
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
		   
	combo11 = new Ext.form.ComboBox({
    store: DataStore1,
    displayField:'DENESTPRO1',
    valueField:'CODESTPRO1',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo11',
    editable:false
    
});
combo11.addListener('select',cambio11);
}
});
}

function llenarCombo12(cod1)
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
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODESTPRO1":'',"CODESTPRO2":'',"CODESTPRO3":'',"CODESTPRO4":'',"CODESTPRO5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENESTPRO1":'',"DENESTPRO2":'',"DENESTPRO3":'',"DENESTPRO4":'',"DENESTPRO5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODESTPRO1'},
			{name: 'CODESTPRO2'},
			{name: 'CODESTPRO3'},
			{name: 'CODESTPRO4'},
			{name: 'CODESTPRO5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENESTPRO1'},
			{name: 'DENESTPRO2'},
			{name: 'DENESTPRO3'},
			{name: 'DENESTPRO4'},
			{name: 'DENESTPRO5'}
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
		
	combo12 = new Ext.form.ComboBox({
    store: DataStore2,
    displayField:'DENESTPRO2',
    valueField:'CODESTPRO2',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo12',
    editable:false
    
});
combo12.addListener('select',cambio12);
}
});
}
function llenarCombo13(cod1,cod2)
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
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODESTPRO1":'',"CODESTPRO2":'',"CODESTPRO3":'',"CODESTPRO4":'',"CODESTPRO5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENESTPRO1":'',"DENESTPRO2":'',"DENESTPRO3":'',"DENESTPRO4":'',"DENESTPRO5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODESTPRO1'},
			{name: 'CODESTPRO2'},
			{name: 'CODESTPRO3'},
			{name: 'CODESTPRO4'},
			{name: 'CODESTPRO5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENESTPRO1'},
			{name: 'DENESTPRO2'},
			{name: 'DENESTPRO3'},
			{name: 'DENESTPRO4'},
			{name: 'DENESTPRO5'}
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
		   
	combo13 = new Ext.form.ComboBox({
    store: DataStore3,
    displayField:'DENESTPRO3',
    valueField:'CODESTPRO3',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo13',
    editable:false
    
});
combo13.addListener('select',cambio13);
}
});
}

function llenarCombo14(cod1,cod2,cod3)
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
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODESTPRO1":'',"CODESTPRO2":'',"CODESTPRO3":'',"CODESTPRO4":'',"CODESTPRO5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENESTPRO1":'',"DENESTPRO2":'',"DENESTPRO3":'',"DENESTPRO4":'',"DENESTPRO5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODESTPRO1'},
			{name: 'CODESTPRO2'},
			{name: 'CODESTPRO3'},
			{name: 'CODESTPRO4'},
			{name: 'CODESTPRO5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENESTPRO1'},
			{name: 'DENESTPRO2'},
			{name: 'DENESTPRO3'},
			{name: 'DENESTPRO4'},
			{name: 'DENESTPRO5'}
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
		   

	
	combo14 = new Ext.form.ComboBox({
    store: DataStore4,
    displayField:'DENESTPRO4',
    valueField:'CODESTPRO4',
    typeAhead: true,
    mode:'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo14',
    editable:false
    
});
 combo14.addListener('select',cambio14);
}
});
}



function llenarCombo15(cod1,cod2,cod3,cod4)
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
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODESTPRO1":'',"CODESTPRO2":'',"CODESTPRO3":'',"CODESTPRO4":'',"CODESTPRO5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENESTPRO1":'',"DENESTPRO2":'',"DENESTPRO3":'',"DENESTPRO4":'',"DENESTPRO5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODESTPRO1'},
			{name: 'CODESTPRO2'},
			{name: 'CODESTPRO3'},
			{name: 'CODESTPRO4'},
			{name: 'CODESTPRO5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENESTPRO1'},
			{name: 'DENESTPRO2'},
			{name: 'DENESTPRO3'},
			{name: 'DENESTPRO4'},
			{name: 'DENESTPRO5'}
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
	combo15 = new Ext.form.ComboBox({
    store: DataStore5,
    displayField:'DENESTPRO5',
    valueField:'CODESTPRO5',
    typeAhead: true,
    mode:'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo15',
    editable:false
    
});
 //combo14.addListener('select',cambio14);
}
});
}




function cambio11()
{
	valor1=combo11.getValue();
	if(combo12=='')
	{
		llenarCombo12(valor1);	
	}
	else
	{
		ActualizarData(valor1,0,0,0,'2');
	}
	
}	 	

function cambio12()
{

	valor2=combo12.getValue();
	if(combo13=='')
	{
		llenarCombo13(valor1,valor2);	
	}
	else
	{
		ActualizarData(valor1,valor2,0,0,'3');
	}
	
}	 

function cambio13()
{
	valor3=combo13.getValue();
	if(combo14=='')
	{
		llenarCombo14(valor1,valor2,valor3);
	}
	else
	{
		ActualizarData(valor1,valor2,valor3,0,'4');
	}

}	
function cambio14()
{
	valor4=combo14.getValue();
	if(combo15=='')
	{
		llenarCombo15(valor1,valor2,valor3,valor4);
	}
	else
	{
		ActualizarData(valor1,valor2,valor3,valor4,'5');
	}

}	


function ActualizarData(cod1,cod2,cod3,cod4,nivel)
{
	DatosEnBlanco = {"raiz":[{"CODEMP":'',"CODESTPRO1":'',"CODESTPRO2":'',"CODESTPRO3":'',"CODESTPRO4":'',"CODESTPRO5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENESTPRO1":'',"DENESTPRO2":'',"DENESTPRO3":'',"DENESTPRO4":'',"DENESTPRO5":''}]};
	
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
				 DatosNuevo={"raiz":[{"CODEMP":'',"CODESTPRO1":'',"CODESTPRO2":'',"CODESTPRO3":'',"CODESTPRO4":'',"CODESTPRO5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENESTPRO1":'',"DENESTPRO2":'',"DENESTPRO3":'',"DENESTPRO4":'',"DENESTPRO5":''}]};
			}	
				switch(nivel)
				{
					case '2':
					combo12.clearValue();
					combo12.store.loadData(DatosNuevo);
					combo13.clearValue();
					combo13.store.loadData(DatosEnBlanco);
					combo14.clearValue();
					combo14.store.loadData(DatosEnBlanco);
					combo15.clearValue();
					combo15.store.loadData(DatosEnBlanco);
					break;
					case '3':
					combo13.clearValue();
					combo13.store.loadData(DatosNuevo);
					combo14.clearValue();
					combo14.store.loadData(DatosEnBlanco);
					combo15.clearValue();
					combo15.store.loadData(DatosEnBlanco);
					break;
					case '4':
					combo14.clearValue();
					combo14.store.loadData(DatosNuevo);
					combo15.clearValue();
					combo15.store.loadData(DatosEnBlanco);
					break;
					case '5':
					combo15.store.loadData(DatosNuevo);
					break;
	
				}
			
			
		}
}
});
	
}

llenarCombo11(1);


});