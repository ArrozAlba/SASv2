/*
 * Ext JS Library 2.0.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * http://extjs.com/license
 */


var gridForm='';
var panel = '';
var gridMontos='';
var gridSinG='';
var gridUnaVez=false;
var formulActual='';
var ventana='';
var RegistroSelVar='';
var storeMesesSinG = '';
var auxGridMetas = '';
var radio1='';
var Rec = '';
var formCompFormula='';
var auxCampo='';
var acuMonto=0;
var Arr1 = new Array(); 
var Arr2 = new Array(); 

function PasarMeta(Obj,Row,col,Rec)
{
	Reg = Obj.store.getAt(Row);
	Arr1.push(Reg.get('cod_var'));
	Arr2.push(Reg.get('meta'));
	Ext.get('formula2').dom.value=Ext.get('formula2').dom.value+"var["+trim(Reg.get('cod_var'))+"]";
}

function llamarformula(Obj,Row,col,Rec)
{
		Rec = Obj.store.getAt(Row);
		Label="El indicador "+ Rec.get('denominacion')+ " se determina por la fï¿½rmula";
		var comprobar = new Ext.Action(
		{
		text: 'Procesar Fórmula',
		handler: function(){
			ComprobarFormula(Rec);
		} 
		,
		iconCls: 'bmenuagregar',
        	tooltip: 'Procesar Fórmula'
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
				{name: 'meta'},
				{name: 'enero_masc'},
				{name: 'febrero_masc'},
				{name: 'marzo_masc'},
				{name: 'abril_masc'},
				{name: 'mayo_masc'},
				{name: 'junio_masc'},
				{name: 'julio_masc'},
				{name: 'agosto_masc'},
				{name: 'septiembre_masc'},
				{name: 'octubre_masc'},
				{name: 'noviembre_masc'},
				{name: 'diciembre_masc'},
				{name: 'enero_fem'},
				{name: 'febrero_fem'},
				{name: 'marzo_fem'},
				{name: 'abril_fem'},
				{name: 'mayo_fem'},
				{name: 'junio_fem'},
				{name: 'julio_fem'},
				{name: 'agosto_fem'},
				{name: 'septiembre_fem'},
				{name: 'octubre_fem'},
				{name: 'noviembre_fem'},
				{name: 'diciembre_fem'}
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
	                	GrabarPlanInd();
		      			grabarindi();
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

			
			if(Rec.get('formula'))
			{
				Ext.getCmp('formula2').setValue(Rec.get('formula').replace('@@@','+'));
			}
            winForms.show();  
            cargarDataStoreMeta();  
            gridForm.addListener('celldblclick',PasarMeta);	      						  
}


function ComprobarFormula(Rec)
{
		formulActual = Ext.get('formula2').dom.value;
		if(formulActual=='')
		{
			Ext.MessageBox.alert('Mensaje','Debe introducir una fï¿½rmula');
			return false;
		}
				
		
		formCompFormula = new Ext.FormPanel({
        frame:true,
        title: 'Comprobación de Fórmula',
        bodyStyle:'padding:5px 5px 0',
        width: 800,
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
		  width:240
	    }
	   ],
		});		
		for(i=0;i<Arr1.length;i++)
		{		
   			  auxObj= new Ext.form.Field({ 
			  name: 'Formula'+i,	 
			  value:Arr2[i]+'('+Arr1[i]+')',
			  id:Arr1[i],
			  
			  labelWidth:200,
			  allowBlank:false,
			  labelSeparator:'',
			  height:20,
			  width:350
			})
		 formCompFormula.add(auxObj);
		}
		
		
          Meses =
			[
		        ['1','Enero',Rec.get('enero')],
		        ['2','Febrero',Rec.get('febrero')],
		        ['3','Marzo',Rec.get('marzo')],
		        ['4','Abril',Rec.get('abril')],
		        ['5','Mayo',Rec.get('mayo')],
		        ['6','Junio',Rec.get('junio')],
		        ['7','Julio',Rec.get('julio')],
		        ['8','Agosto',Rec.get('agosto')],
		        ['9','Septiembre',Rec.get('septiembre')],
		        ['10','Octubre',Rec.get('octubre')],
		        ['11','Noviembre',Rec.get('noviembre')],
		        ['12','Diciembre',Rec.get('diciembre')],
		        ['13','Total',Rec.get('diciembre')]
			]	

			Mes =
			[
		        ['1','enero'],
		        ['2','febrero'],
		        ['3','marzo'],
		        ['4','abril'],
		        ['5','mayo'],
		        ['6','junio'],
		        ['7','julio'],
		        ['8','agosto'],
		        ['9','septiembre'],
		        ['10','octubre'],
		        ['11','noviembre'],
		        ['12','diciembre']
			]	
		
		
		
			 
			 var storeMesesSinG = new Ext.data.SimpleStore
			({
			        fields: 
					[
			           {name: 'numes'},
			           {name: 'mes'},	
			           {name: 'totalcant'}
			        ]
			 });
			 			
			storeMesesSinG.loadData(Meses);
         
            gridSinG = new Ext.grid.EditorGridPanel({
  			width:450,
  			style:'margin-left:40px',
  			title: 'Distribución Mensual del Indicador',
			autoScroll:true,
            border:true,
            ds:storeMesesSinG,
            cm: new Ext.grid.ColumnModel([
            {header: "Mes", width: 50, sortable: true,   dataIndex: 'mes'},
			{header: "Cantidad", width: 100, sortable: true, dataIndex: 'totalcant',editor:new Ext.form.NumberField({allowBlank:false,allowDecimals:false})},							
		]),

			selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                      viewConfig: {
                            forceFit:true	
             },
			autoHeight:true,
			stripeRows: true
            });
 		
			
		
	        winCompForm = new Ext.Window(
            {
  				autoScroll:true,
                width:800,
                height:550,
                modal: true,
                style:'padding-left:100px',
                closable:false,
                plain: false,
                items:[formCompFormula,gridSinG],
                buttons: 
                	[{
	                text:'Aceptar',  
	                handler: function()
	                { 
	                	formCompFormula.destroy();
	                	winCompForm.destroy();
	                	gridSinG.destroy();
		              
	                }
	                }                	
	               ]
            });
           
    		winCompForm.show();
    		pruebafinalformula();
    		
    		
}
		function pruebafinalformula()
		{
				//alert('armar con valores');
				valorfem=0;
				valormas=0;
				Objetos = formCompFormula.items;
				CantObjs=Objetos.length;
				grupomeses="{'oper':'comprobarformula','mesesformula':["
				//alert(CantObjs); 
				for(j=0;j<Mes.length;j++)
				{
					AuxForm = Ext.get('formulacomprobar').dom.value;
					for(i=1;i<CantObjs;i++)
					{
						auxcod1 = Objetos.get(i).getId();
						id1 = gridForm.store.find("cod_var",auxcod1);
						Auxreg =gridForm.store.getAt(id1);
						//alert(ue_formato_calculo(Auxreg.get('enero_masc')));			
						//return false;
						Id = "var["+trim(Objetos.get(i).getId())+"]";
						if(Auxreg.get(Mes[j][1]+'_fem'))
						{
							valorfem =ue_formato_calculo(parseInt(Auxreg.get(Mes[j][1]+'_fem')));
						}
						else
						{
							valorfem=0;
						}
					//	auxcampo = Mes[j][1]+'_masc';
						if(Auxreg.get(Mes[j][1]+'_masc'))
						{
							valormas =ue_formato_calculo(parseInt(Auxreg.get(Mes[j][1]+'_masc')));
						}
						else
						{
							valormas=0;
						}
						
						Valor = valormas+parseInt(valorfem);
						Valor = trim(Valor.toString());
						while(AuxForm.indexOf(Id)>=0)
						{
							AuxForm=AuxForm.replace(Id,Valor);
							
						}
				
					}
					AuxForm = AuxForm.replace('+','|@@@|');
					if(j==0)
					{
						grupomeses=grupomeses+"{'"+Mes[j][1]+"':'"+AuxForm+"'}"
					}
					else
					{
						grupomeses=grupomeses+",{'"+Mes[j][1]+"':'"+AuxForm+"'}"
					}
				}
				grupomeses=grupomeses+"]}";
				Json = Ext.util.JSON.decode(grupomeses);
				ObjSon = Ext.util.JSON.encode(Json);
				parametros = 'ObjSon='+ObjSon; 
				Ext.Ajax.request({
				url : ruta,
				params : parametros,
				method: 'POST',
				success: function(resultado, request) 
				{ 
					datos = resultado.responseText;
					Json = Ext.util.JSON.decode(datos);
					if(Json!='')
					{
						//alert(Json.raiz.enero);
						gridSinG.store.getAt(0).set('totalcant',Json.raiz.enero); 
						gridSinG.store.getAt(1).set('totalcant',Json.raiz.febrero); 
						gridSinG.store.getAt(2).set('totalcant',Json.raiz.marzo);
						gridSinG.store.getAt(3).set('totalcant',Json.raiz.abril); 
						gridSinG.store.getAt(4).set('totalcant',Json.raiz.mayo); 
						gridSinG.store.getAt(5).set('totalcant',Json.raiz.junio); 
						gridSinG.store.getAt(6).set('totalcant',Json.raiz.julio); 
						gridSinG.store.getAt(7).set('totalcant',Json.raiz.agosto); 
						gridSinG.store.getAt(8).set('totalcant',Json.raiz.septiembre); 
						gridSinG.store.getAt(9).set('totalcant',Json.raiz.octubre); 
						gridSinG.store.getAt(10).set('totalcant',Json.raiz.noviembre); 
						gridSinG.store.getAt(11).set('totalcant',Json.raiz.diciembre); 
						gridSinG.store.getAt(12).set('totalcant',Json.total); 
						
						//Ext.MessageBox.alert('Mensaje','La fórmula es correcta, el resultado es '+datos);
					}
					else
					{
						Ext.MessageBox.alert('Mensaje','La fórmula es incorrecta');
					}
				}	
				})	
		}

function cargarDataStoreMeta()
{

		var myJSONObject={
			"oper": 'leermetas',
			"codinte":IdPadre
		}
		ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		Ext.Ajax.request({
		url : rutaIntepr,
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


function GrabarPlanInd()
{	

	MontosMeses = gridSinG.store.getRange(0,11);
	Rec = gridIntInd.getSelectionModel().getSelected();
	Rec.set('enero',MontosMeses[0].get("totalcant"));
	Rec.set('febrero',MontosMeses[1].get("totalcant"));
	Rec.set('marzo',MontosMeses[2].get("totalcant"));
	Rec.set('abril',MontosMeses[3].get("totalcant"));
	Rec.set('mayo',MontosMeses[4].get("totalcant"));
	Rec.set('junio',MontosMeses[5].get("totalcant"));
	Rec.set('julio',MontosMeses[6].get("totalcant"));
	Rec.set('agosto',MontosMeses[7].get("totalcant"));
	Rec.set('septiembre',MontosMeses[8].get("totalcant"));
	Rec.set('octubre',MontosMeses[9].get("totalcant"));
	Rec.set('noviembre',MontosMeses[10].get("totalcant"));
	Rec.set('diciembre',MontosMeses[11].get("totalcant"));
	Rec.set('NuevoRegistro', true);
}



function grabarindi()
{
//alert("sss");
formulActual = formulActual.replace('+','@@@');
reg="";
if(IdPadre=='')
{
	Ext.MessageBox.alert('Mensaje','Debe Seleccionar una Estructura');
	return false;
}	
else
{
	var reg = "{";
	reg = reg + "'oper':'actualizarInt','codemp':'0001','codinte':'"+IdPadre+"'";
		
}	
	if(gridIntInd.getSelectionModel().getSelected() && gridIntInd.getSelectionModel().getSelected().get('NuevoRegistro')==true)
	{
	arrMetas = gridIntInd.getSelectionModel().getSelected();
	metas=false;
		reg=reg+ ",DatosIndi:[";

		reg= reg +"{'ano_presupuesto':'2009','formula':'"+ formulActual +"','cod_ind':'"+ arrMetas.get('cod_ind')+"','enero':'"+arrMetas.get('enero')+"','febrero':'"+arrMetas.get('febrero')+"','marzo':'"+arrMetas.get('marzo')+"','abril':'"+arrMetas.get('abril')+"','mayo':'"+arrMetas.get('mayo')+"','junio':'"+arrMetas.get('junio')+"','julio':'"+arrMetas.get('julio')+"','agosto':'"+arrMetas.get('agosto')+"','septiembre':'"+arrMetas.get('septiembre')+"','octubre':'"+arrMetas.get('octubre')+"','noviembre':'"+arrMetas.get('noviembre')+"','diciembre':'"+arrMetas.get('diciembre')+"'}";
		
		reg = reg + "]";
	}
	else
	{
		return false;
	}	
	
	reg = reg + "}";
	
	//alert(reg);
	//return false;
	Obj= eval('(' + reg + ')');
	ObjSon=JSON.stringify(Obj);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function ( resultad, request ){ 
        datos = resultad.responseText;
		// alert(datos);
		//Ext.get('norte').dom.innerHTML=datos;
		 var Registros = datos.split("|");
				if (Registros[1] == '1')
				 {
					Ext.MessageBox.alert('Mensaje','Los Montos fueron asignados con éxito')
					if(gridIntInd.getSelectionModel().getSelected())
						gridIntInd.getSelectionModel().getSelected().set('NuevoRegistro',false);
					//location.href='sigesp_spe_formGasto.php';							
				 }
				 else if(Registros[1]=='-5')
				 {
				  	Ext.MessageBox.alert('Error', 'La integración presupuestaria seleccionada ya existe, la combinación de la estructura del plan y la estructura presupuestaria seleccionada ya fue registrada, verifique mediante el catálogo');
				  	//EstadoInicial();
				 }
				else if(Registros[1]=='-1')
				 {
				  	Ext.MessageBox.alert('Error', 'La integraciï¿½n presupuestaria seleccionada ya existe, la combinaciï¿½n de la estructura del plan y la estructura presupuestaria seleccionada ya fue registrada, verifique mediante el catï¿½logo');
				  	//EstadoInicial();
				 } else if(Registros[1]=='0')
				 {
				 	Ext.MessageBox.alert('Mensaje', 'No se pudo realizar la operaciï¿½n');
				 }
				 else
				 {
				 	var myObject = eval('(' + datos + ')');
				 	IdPadre = myObject.raiz[0].codinte;
				 	DesabilitarGrids(false);
				 	ActualizarGrids();
				 }
				
      },
	failure: function ( result, request)
	 { 
		Ext.MessageBox.alert('Error', result.responseText); 
	 } 
});
};

