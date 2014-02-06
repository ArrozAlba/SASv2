var Listo2 = false;
var Oper='';
var DatosNuevo ="";
var tabs='';
var combo1=''; 
var combo2=''; 
var combo3=''; 
var combo4=''; 
var combo5=''; 
var DatosNuevo='';
var valor1='';  
var valor2='';
var anchoCombo=40;
var anchoTextoCombo=600;  
rutaComboest ='../../procesos/sigesp_spe_comboestpr.php';
Ext.onReady(function(){
	  
function llenarCombo1()
{   
	var myJSONString ="{'oper':'catestpro','numest':'1','codest1':'','denest1': ''}";
	aux = eval('(' + myJSONString + ')');
	ObjSon=JSON.stringify(aux);
	parametros = 'ObjSon='+ObjSon;
	//alert(Auxnum); 
	Ext.Ajax.request({
	url : rutaComboest,
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
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODEST1'},
			{name: 'CODEST2'},
			{name: 'CODEST3'},
			{name: 'CODEST4'},
			{name: 'CODEST5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENEST1'},
			{name: 'DENEST2'},
			{name: 'DENEST3'},
			{name: 'DENEST4'},
			{name: 'DENEST5'}
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
		   
	combo1 = new Ext.form.ComboBox({
    store: DataStore1,
    displayField:'DENEST1',
    valueField:'CODEST1',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo1',
    editable:false
    
});
combo1.addListener('select',cambio1);
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
	url : rutaComboest,
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
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODEST1'},
			{name: 'CODEST2'},
			{name: 'CODEST3'},
			{name: 'CODEST4'},
			{name: 'CODEST5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENEST1'},
			{name: 'DENEST2'},
			{name: 'DENEST3'},
			{name: 'DENEST4'},
			{name: 'DENEST5'}
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
		   

	
	combo2 = new Ext.form.ComboBox({
    store: DataStore2,
    displayField:'DENEST2',
    valueField:'CODEST2',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo2',
    editable:false
    
});
combo2.addListener('select',cambio2);
}
});
}
function llenarCombo3(cod1,cod2)
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
	url : rutaComboest,
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
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODEST1'},
			{name: 'CODEST2'},
			{name: 'CODEST3'},
			{name: 'CODEST4'},
			{name: 'CODEST5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENEST1'},
			{name: 'DENEST2'},
			{name: 'DENEST3'},
			{name: 'DENEST4'},
			{name: 'DENEST5'}
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
		   
	combo3 = new Ext.form.ComboBox({
    store: DataStore3,
    displayField:'DENEST3',
    valueField:'CODEST3',
    typeAhead: true,
    mode: 'local',
    fieldLabel:'Hola',
    triggerAction: 'all',
    width :anchoCombo,
    listWidth:anchoTextoCombo,
    emptyText:'Seleccione una',
    selectOnFocus:true,
    renderTo:'combo3',
    editable:false
    
});
combo3.addListener('select',cambio3);
}
});
}

function llenarCombo4(cod1,cod2,cod3)
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
	url : rutaComboest,
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
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODEST1'},
			{name: 'CODEST2'},
			{name: 'CODEST3'},
			{name: 'CODEST4'},
			{name: 'CODEST5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENEST1'},
			{name: 'DENEST2'},
			{name: 'DENEST3'},
			{name: 'DENEST4'},
			{name: 'DENEST5'}
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
		   

	
	combo4 = new Ext.form.ComboBox({
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
    renderTo:'combo4',
    editable:false
    
});
 combo4.addListener('select',cambio4);
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
	url : rutaComboest,
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
		
			var DatosNuevo={"raiz":[{"CODEMP":'',"CODEST1":'',"CODEST2":'',"CODEST3":'',"CODEST4":'',"CODEST5":'',"ESTCLA_P":'',"ANO_PRESUPUESTO":'',"DENEST1":'',"DENEST2":'',"DENEST3":'',"DENEST4":'',"DENEST5":''}]};
		}

	RecordDef = Ext.data.Record.create([
			{name: 'CODEMP'},     
			{name: 'CODEST1'},
			{name: 'CODEST2'},
			{name: 'CODEST3'},
			{name: 'CODEST4'},
			{name: 'CODEST5'},
			{name: 'ESTCLA_P'},
			{name: 'ANO_PRESUPUESTO'},
			{name: 'CODUAC'},
			{name: 'DENEST1'},
			{name: 'DENEST2'},
			{name: 'DENEST3'},
			{name: 'DENEST4'},
			{name: 'DENEST5'}
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
    renderTo:'combo5',
    editable:false
    
});
 //combo4.addListener('select',cambio4);
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
	if(combo3=='')
	{
		llenarCombo3(valor1,valor2);	
	}
	else
	{
		ActualizarData(valor1,valor2,0,0,'3');
	}
	
}	 

function cambio3()
{
	valor3=combo3.getValue();
	if(combo4=='')
	{
		llenarCombo4(valor1,valor2,valor3);
	}
	else
	{
		ActualizarData(valor1,valor2,valor3,0,'4');
	}

}	
function cambio4()
{
	valor4=combo4.getValue();
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
	url : rutaComboest,
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
					combo3.clearValue();
					combo3.store.loadData(DatosEnBlanco);
					combo4.clearValue();
					combo4.store.loadData(DatosEnBlanco);
					combo5.clearValue();
					combo5.store.loadData(DatosEnBlanco);
					break;
					case '3':
					combo3.clearValue();
					combo3.store.loadData(DatosNuevo);
					combo4.clearValue();
					combo4.store.loadData(DatosEnBlanco);
					combo5.clearValue();
					combo5.store.loadData(DatosEnBlanco);
					break;
					case '4':
					combo4.clearValue();
					combo4.store.loadData(DatosNuevo);
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