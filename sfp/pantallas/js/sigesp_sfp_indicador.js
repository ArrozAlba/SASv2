/*
codigo javascript asociado al archivo fuentes de financiamiento

*/

var PanelPersonal="";
var gridCargos="";
var Actualizar=null;
var dsTipo = "";
var FormulaProbada=false;
var gridForm = "";
var ruta ='../../procesos/sigesp_sfp_indicadorpr.php';
var pantalla ='sigesp_sfp_indicador.php';
var Arr1 = new Array(); 
var Arr2 = new Array(); 
var Campos =new Array(
	        ['cod_ind','novacio|'],
	        ['denominacion','novacio|'],
	        ['tipo','novacio|']
	    )

Ext.onReady(function(){
ObtenerSesion(ruta,pantalla);
Ext.get('BtnNuevo').on('click',LlamarNuevo);
Ext.get('BtnGrabar').on('click',ActualizarAux);
Ext.get('BtnCat').on('click', irCat);
Ext.get('BtnElim').on('click',LlamarEliminar);


function irCat()
{
	MostrarCatEmp('definicion','')
}

Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank2.php';
})


function ActualizarAux()
{
	//AuxForm = Ext.get('formula').dom.value;
	//AuxForm = AuxForm.replace('+','|@@@|');
	//Ext.get('formula').dom.value=AuxForm;
	LlamarActualizar();	
}


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
                height:250,
                el:'centro'    
            })
            ]
          })

function cargartipo()
{
		var myJSONObject ={
			"oper": 'leertipos'
	};	
		ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		Ext.Ajax.request({
		url : ruta,
		params : parametros,
		method: 'POST',
		success: function (resultado, request) { 
		  datos = resultado.responseText;
		  var DatosNuevo = eval('(' + datos + ')');
		 if(datos=='' && datos.raiz==null)
		 {
			var DatosNuevo={"raiz":[{"cod_tipoind":'',"denominacion":''}]};
		 }
		var unidad = Ext.get('tipo');
		for (i=0; i<DatosNuevo.raiz.length; i++)
		{	
			var opcion = document.createElement('option');
			opcion.value = DatosNuevo.raiz[i].cod_tipoind;
			opcion.text = DatosNuevo.raiz[i].denominacion;
			unidad.dom.add(opcion,null);
		}
	}
})	
}
	function llamarformula(){
		Label="El indicador "+ Ext.get('denominacion').dom.value+ " se determina por la fórmula";
		var comprobar = new Ext.Action(
		{
		text: 'Comprobar',
		handler: function(){
			ComprobarFormula();
		} 
		,
		iconCls: 'bmenuagregar',
        	tooltip: 'Comprobar Fórmula'
		});
		
		var aceptar = new Ext.Action(
		{
			text: 'Aceptar',
			handler: function(){
				alert('pasarcuenta');
			},
			iconCls: 'bmenumodif',
        	tooltip: 'Modificar Monto Asignado'
		});
		
		var borrar = new Ext.Action(
		{
			text: 'Limpiar',
			handler: function(){
				Ext.get('formula2').dom.value='';
			},
			iconCls: 'bmenuquitar',
        	tooltip: 'Eliminar Cuenta'
		});
		var suma = new Ext.Action(
		{
			text: '+',
			handler: function(){
				Ext.get('formula2').dom.value=Ext.get('formula2').dom.value+'+';
			},
        	tooltip: 'Suma'
		});
		
		var resta = new Ext.Action(
		{
			text: '-',
				handler: function(){
				Ext.get('formula2').dom.value=Ext.get('formula2').dom.value+'-';
			},
        	tooltip: 'Resta'
		});
		
		var multi = new Ext.Action(
		{
			text: '*',
				handler: function(){
				Ext.get('formula2').dom.value=Ext.get('formula2').dom.value+'*';
			},
        	tooltip: 'Multiplicación'
		});
		var divi = new Ext.Action(
		{
			text: '/',
			handler: function(){
				Ext.get('formula2').dom.value=Ext.get('formula2').dom.value+'/';
			},
        	tooltip: 'División'
		});
		
		var abrepar = new Ext.Action(
		{
			text: '(',
			handler: function(){
				Ext.get('formula2').dom.value=Ext.get('formula2').dom.value+'(';
			},
        	tooltip: 'Abrir Paréntesis'
		});
		
		var cierrapar = new Ext.Action(
		{
			text: ')',
			handler: function(){
				Ext.get('formula2').dom.value=Ext.get('formula2').dom.value+')';
			},
        	tooltip: 'Cerrar Paréntesis'
		});

		formFormula = new Ext.FormPanel({
        frame:true,
        title: 'Editor de Fórmulas',
        bodyStyle:'padding:5px 5px 0',
        width: 500,
        tbar:[comprobar,borrar],
        bbar:[suma,resta,divi,multi,abrepar,cierrapar],
		height:200,
        defaults: {width: 230},
		items:[
         {
		  xtype:'textarea', 
		  name: 'Formula',	 
		  fieldLabel:Label,
		  id: 'formula2',
		  maxLength: 25,
		  allowBlank:false,
		  height:100,
		  width: 350
	    }
	   ]
	});		

	// se crea el grid
		RecordDefMetas = Ext.data.Record.create([
				{name: 'cod_var'},    
				{name: 'meta'}
		]);
		var myObject={"raiz":[{"cod_var":'',"meta":''}]};
		dsEmp =  new Ext.data.Store({
				 proxy: new Ext.data.MemoryProxy(myObject),
				 reader: new Ext.data.JsonReader({
				 root: 'raiz',             
				 id: "id"   
				}
				,
		        	RecordDefMetas  
				),
				data: myObject
	  	})	
		
		 gridForm = new Ext.grid.GridPanel({
		 width:500,
		 height:400,
		 tbar:formBusEmp,
		 autoScroll:true,
	     border:true,
	     ds:dsEmp,
	     cm: new Ext.grid.ColumnModel([
	          {header: "Código", width: 30, sortable: true,   dataIndex: 'cod_var'},
	          {header: "Nombre", width: 50, sortable: true, dataIndex: 'meta'}
	       ]),
	       stripeRows: true,
	       viewConfig:{
	       forceFit:true
	      }
	      ,
	      });         
	               
            winForms = new Ext.Window(
            {
                title: 'Editor de Formulas de Indicadores',
  				autoScroll:true,
                width:600,
                height:500,
                modal: true,
                style:'padding-left:70px',
                closable:false,
                plain: false,
                items:[formFormula,gridForm],
                buttons: 
                	[{
	                text:'Aceptar',  
	                handler: function()
	                { 
	                	Ext.get('formula').dom.value=Ext.get('formula2').dom.value;
		                formFormula.destroy();
		    			gridForm.destroy();      
		    			winForms.destroy();                
	                }
	                }
	                ,
	                {
	                 text: 'Salir',
	                 handler: function()
	                 { 	
		                formFormula.destroy();
		    			gridForm.destroy();      
		    			winForms.destroy();             
	                 }
                	}]
            });
            winForms.show();  
            cargarDataStoreMeta();  
            gridForm.addListener('celldblclick',PasarMeta);	      						  
}

function PasarMeta(Obj,Row,col,Rec)
{
	Reg = Obj.store.getAt(Row);
	Arr1.push(Reg.get('cod_var'));
	Arr2.push(Reg.get('meta'));
	Ext.get('formula2').dom.value=Ext.get('formula2').dom.value+"var["+trim(Reg.get('cod_var'))+"]";
}

function ComprobarFormula()
{
		formulActual = Ext.get('formula2').dom.value;
		if(formulActual=='')
		{
			Ext.MessageBox.alert('Mensaje','Debe introducir una fórmula');
			return false;
		}
		var pruebafinal = new Ext.Action(
		{
				text: 'Comprobar Formula',
				handler: function(){
				//alert('armar con valores');
				AuxForm = Ext.get('formulacomprobar').dom.value;
				Objetos = formCompFormula.items;
				CantObjs=Objetos.length;
				//alert(CantObjs); 
				for(i=1;i<CantObjs;i++)
				{
					Id = "var["+trim(Objetos.get(i).getId())+"]";
					Valor = Objetos.get(i).getValue();
					Valor = trim(Valor.toString());
					while(AuxForm.indexOf(Id)>=0)
					{
						AuxForm=AuxForm.replace(Id,Valor);
					}
				}
				AuxForm = AuxForm.replace('+','|@@@|');	
				var myJSONObject={
					"oper": 'comprobarformula',
					"formula":AuxForm 
				}
				ObjSon=JSON.stringify(myJSONObject);
				parametros = 'ObjSon='+ObjSon; 
				Ext.Ajax.request({
				url : ruta,
				params : parametros,
				method: 'POST',
				success: function(resultado, request) 
				{ 
					datos = resultado.responseText;
					if(datos!='0')
					{
						Ext.MessageBox.alert('Mensaje','La fórmula es correcta, el resultado es '+datos);
					}
					else
					{
						Ext.MessageBox.alert('Mensaje','La fórmula es incorrecta');
					}
				}	
				})	
				
		}
		,
        	tooltip: 'Comprobar Fórmula'
		});
		
		formCompFormula = new Ext.FormPanel({
        frame:true,
        title: 'Comprobación de Fórmula',
        bodyStyle:'padding:5px 5px 0',
        width: 800,
        bbar:[pruebafinal],
       // bbar:[suma,resta,divi,multi,abrepar,cierrapar],
		autoHeight:true,
		items:[
         {
		  xtype:'textfield', 
		  name: 'Formula',	 
		  fieldLabel:'Formula',
		  id: 'formulacomprobar',
		  readOnly : true,
		  value:Ext.get('formula2').dom.value,
		  maxLength: 25,
		  allowBlank:false,
		  height:20,
		  width:140
	    }
	   ],
        defaults: {width: 230}
		});		
		for(i=0;i<Arr1.length;i++)
		{		
   			  auxObj= new Ext.form.Field({ 
			  name: 'Formula'+i,	 
			  fieldLabel:Arr2[i]+'('+Arr1[i]+')',
			  id:Arr1[i],
			  maxLength: 25,
			  labelWidth:200,
			  allowBlank:false,
			  height:20,
			  width:10
			})
		 formCompFormula.add(auxObj);
		}
		
					
	        winCompForm = new Ext.Window(
            {
                title: 'Editor de Formulas de Indicadores',
  				autoScroll:true,
                width:800,
                height:400,
                modal: true,
                style:'padding-left:70px',
                closable:false,
                plain: false,
                items:[formCompFormula],
                buttons: 
                	[{
	                text:'Aceptar',  
	                handler: function()
	                { 
	                	formCompFormula.destroy();
		                winCompForm.destroy();
	                }
	                }
	                ,
	                {
	                 text: 'Salir',
	                 handler: function()
	                 { 	
		             	  winCompForm.destroy();
		             }
                	}]
            });
           
    		winCompForm.show();
}

function crearDataStoreTipo()
{
		 RecordDefEmp = Ext.data.Record.create([
				{name: 'cod_tipoind'},    
				{name: 'denominacion'}
				
		]);
			var myObject={"raiz":[{"cod_tipoind":'',"denominacion":''}]};
			 dsTipo =  new Ext.data.Store({
				 proxy: new Ext.data.MemoryProxy(myObject),
				 reader: new Ext.data.JsonReader({
				 root: 'raiz',             
				 id: "id"   
				}
				,
		        	RecordDefEmp   
				),
				data: myObject
	  		})	
		

		var myJSONObject ={
		"oper": 'catalogo'
		}
		ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		Ext.Ajax.request({
		url : ruta,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request) 
		{ 
			datos = resultado.responseText;
			var myObject = eval('(' + datos + ')');
			if(myObject!='')
			{
				dsEmp.loadData(myObject);
			}
		}	
	})
}

function cargarDataStoreMeta()
{
		var myJSONObject={
			"oper": 'leervariables'
		}
		
		ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		Ext.Ajax.request({
		url : ruta,
		params : parametros,
		method: 'POST',
		success: function (resultado, request) 
		{ 
			datos = resultado.responseText;
			var myObject = eval('(' + datos + ')');
			if(myObject!='')
			{
				gridForm.store.loadData(myObject);
			}
		}	
	})
}



function CrearGrid()
{
	 crearDataStoreTipo();		 
	 gridTip = new Ext.grid.GridPanel({
	 width:770,
	 height:400,
	 tbar:formBusEmp,
	 autoScroll:true,
     border:true,
     ds:dsEmp,
     cm: new Ext.grid.ColumnModel([
          {header: "Código", width: 30, sortable: true,   dataIndex: 'cod_tipoind'},
          {header: "Nombre", width: 50, sortable: true, dataIndex: 'denominacion'}
       ]),
       stripeRows: true,
      viewConfig: {
      	forceFit:true
      }
      ,
      });            
} 



cargartipo();
});