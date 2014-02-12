/*
 * Ext JS Library 2.0.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * http://extjs.com/license
 */



var panel = '';
var gridMontos='';
var gridSinG='';
var gridUnaVez=false;
var ventana='';
var RegistroSelVar='';
var auxGridMetas = '';
var radio1='';
var auxCampo='';
var acuMonto=0;

function LlenaMontoEq(Obj)
{
	ValorEq = Ext.get('montorep').getValue();
	if(Obj.get('mes')!='Total')
	{
		Monto=ValorEq;
		acuMonto+=parseInt(ValorEq);
	}
	else if(Obj.get('mes')=='Total')
	{
		Monto=acuMonto;
	}
	if(ValorEq!='')
	{
		if(auxCampo=='fem')
		{
			Obj.set('cantfemenino',Monto);
		}
		else if(auxCampo=='mas')
		{
			Obj.set('cantmasculino',Monto);
		}
		else if(auxCampo=='dos')
		{
			Obj.set('cantmasculino',Monto);
			Obj.set('cantfemenino',Monto);
		}	
	}	
}


function repMontos()
{
	acuMonto=0
	valor = Ext.getCmp('tipodist').getValue();
	//alert(valor);
	if(valor)
	{
		auxCampo='mas';
	}
	else
	{
		if(Ext.getCmp('fem').getValue()==true)
		{
			auxCampo='fem';
		}
		if(Ext.getCmp('mas').getValue()==true)
		{
			auxCampo='mas';	
		}
		if(Ext.getCmp('todos').getValue()==true)
		{
			auxCampo='dos';	
		}
	}	
		//alert(auxCampo);
		auxGridMetas.store.each(LlenaMontoEq);
	
}

function getGridMontosMetas(Obj)
{
	RegistroSelVar = gridIntVar.getSelectionModel().getSelected();
	Genero = RegistroSelVar.get('genero');
		if(Genero=='1')
        {
        	radio1 = new Ext.form.RadioGroup(
		    {
              id:'tipodist',
              labelSeparator:'',
                items:
                [
                    {boxLabel: 'Femenino', name: 'rb-auto', inputValue: 1 ,checked: true,id:'fem'},
                    {boxLabel: 'Masculino', name: 'rb-auto', inputValue: 2,id:'mas'},
                    {boxLabel: 'Todos', name: 'rb-auto', inputValue: 3, id:'todos'}   
                ]
            }
		  
  			)	    
        	
            Meses =
			[
		        ['1','Enero',gridIntVar.getSelectionModel().getSelected().get('enero_fem'),gridIntVar.getSelectionModel().getSelected().get('enero_masc')],
		        ['2','Febrero',gridIntVar.getSelectionModel().getSelected().get('febrero_fem'),gridIntVar.getSelectionModel().getSelected().get('febrero_masc')],
		        ['3','Marzo',gridIntVar.getSelectionModel().getSelected().get('marzo_fem'),gridIntVar.getSelectionModel().getSelected().get('marzo_masc')],
		        ['4','Abril',gridIntVar.getSelectionModel().getSelected().get('abril_fem'),gridIntVar.getSelectionModel().getSelected().get('abril_masc')],
		        ['5','Mayo',gridIntVar.getSelectionModel().getSelected().get('mayo_fem'),gridIntVar.getSelectionModel().getSelected().get('mayo_masc')],
		        ['6','Junio',gridIntVar.getSelectionModel().getSelected().get('junio_fem'),gridIntVar.getSelectionModel().getSelected().get('junio_masc')],
		        ['7','Julio',gridIntVar.getSelectionModel().getSelected().get('julio_fem'),gridIntVar.getSelectionModel().getSelected().get('julio_masc')],
		        ['8','Agosto',gridIntVar.getSelectionModel().getSelected().get('agosto_fem'),gridIntVar.getSelectionModel().getSelected().get('agosto_masc')],
		        ['9','Septiembre',gridIntVar.getSelectionModel().getSelected().get('septiembre_fem'),gridIntVar.getSelectionModel().getSelected().get('septiembre_masc')],
		        ['10','Octubre',gridIntVar.getSelectionModel().getSelected().get('octubre_fem'),gridIntVar.getSelectionModel().getSelected().get('octubre_masc')],
		        ['11','Noviembre',gridIntVar.getSelectionModel().getSelected().get('noviembre_fem'),gridIntVar.getSelectionModel().getSelected().get('noviembre_masc')],
		        ['12','Diciembre',gridIntVar.getSelectionModel().getSelected().get('diciembre_fem'),gridIntVar.getSelectionModel().getSelected().get('diciembre_masc')],
		        ['13','Total',0,0]
			]	

        }
        else
        {
        	radio1 = new Ext.form.Hidden
			(
				 {
	               name:'hid1',
			  	   value:true,
			  	   id:'tipodist'
			  	  }
	  		)	    
        	
           Meses =
			[
		        ['1','Enero',gridIntVar.getSelectionModel().getSelected().get('enero_masc')],
		        ['2','Febrero',gridIntVar.getSelectionModel().getSelected().get('febrero_masc')],
		        ['3','Marzo',gridIntVar.getSelectionModel().getSelected().get('marzo_masc')],
		        ['4','Abril',gridIntVar.getSelectionModel().getSelected().get('abril_masc')],
		        ['5','Mayo',gridIntVar.getSelectionModel().getSelected().get('mayo_masc')],
		        ['6','Junio',gridIntVar.getSelectionModel().getSelected().get('junio_masc')],
		        ['7','Julio',gridIntVar.getSelectionModel().getSelected().get('julio_masc')],
		        ['8','Agosto',gridIntVar.getSelectionModel().getSelected().get('agosto_masc')],
		        ['9','Septiembre',gridIntVar.getSelectionModel().getSelected().get('septiembre_masc')],
		        ['10','Octubre',gridIntVar.getSelectionModel().getSelected().get('octubre_masc')],
		        ['11','Noviembre',gridIntVar.getSelectionModel().getSelected().get('noviembre_masc')],
		        ['12','Diciembre',gridIntVar.getSelectionModel().getSelected().get('diciembre_masc')],
		        ['13','Total',gridIntVar.getSelectionModel().getSelected().get('diciembre_masc')]
			]	

        }
	
	
	CodMeta = gridIntVar.getSelectionModel().getSelected().get('cod_var');
	DenMeta = gridIntVar.getSelectionModel().getSelected().get('meta'); 
	EsNuevo = gridIntVar.getSelectionModel().getSelected().get('NuevoRegistro');
	MontGlobal = gridIntVar.getSelectionModel().getSelected().get('montoGlobal');
	Unidad = gridIntVar.getSelectionModel().getSelected().get('unidad');
	
	//alert(Genero);
	/*Evento = 'grabarPlan'; 	
	if(NuevoRegistro=='')
	{
		ano_pre = gridIntVar.getSelectionModel().getSelected().get('ano_presupuesto');
		Evento = 'ActualizarPlan';
	}*/

     var storeMeses = new Ext.data.SimpleStore({
        fields: ['nombre'],
        data : Meses // from states.js
    });
    
  /*  var storeTipo = new Ext.data.SimpleStore({
        fields: ['tipo'],
        data : Tipos // from states.js
    });*/
    
     /*var storenatGastos = new Ext.data.SimpleStore({
        fields: ['nat'],
        data : natGasto // from states.js
    });*/



	/*var DatosNuevoCont={"raiz":[{"codigo":'0000001',"denominacion":'78676789 Cuenta Numero1'}]};
		RecordDefCuentac = Ext.data.Record.create
		([
			{name: 'codigo'},// "mapping" property not needed if it's the same 
			{name: 'denominacion'},
		]);
			DataStoreCuentaContable =  new Ext.data.Store
			({
			proxy: new Ext.data.MemoryProxy(DatosNuevoCont),
			reader: new Ext.data.JsonReader({
			root: 'raiz',                
			id: "id"   
			},
                    RecordDefCuentac
			      ),
					data: DatosNuevoCont
        });*/


//if(!gridUnaVez)
//{
	  panelMetas = new Ext.FormPanel({
	  labelWidth:140, // label settings here cascade unless overridden,
	  labelAlign:'right',
	  title: 'Descripción de la Meta',
	  bodyStyle:'padding-top:5px',
	  height:210,  
	  items:[
		{
		  xtype:'textfield', 
		  fieldLabel: 'Meta',
		  name: 'meta',
		  value:CodMeta,
		  readOnly:true,
		  id: 'Meta',
		  maxLength: 25,
		  maxLengthText: 'El campo excede la longitud máxima',
		  allowBlank:false,
		  width: 80
	    },{
		  xtype:'textfield', 
		  fieldLabel: 'Denominación',
		  name: 'denominacion',
		  readOnly:true,
		  value:DenMeta,
		  id: 'denom',
		  maxLength: 470,
		  maxLengthText: 'El campo excede la longitud máxima',
		  allowBlank:false,
		  width: 370
	    }
	    ,
	    {
		  xtype:'textfield', 
		  fieldLabel: 'Monto global',
		  name: 'montorep',
		  id: 'montorep',
		  maxLength: 470,
		  width: 50
	    }
	    ,
	    radio1
	    ,
	    {
	   	  xtype:'button',
		  handler:repMontos,
		  text:'Repetir Montos',
		  style:'position:absolute;left:140px;top:110px'
	    }
	   ]
	});
	gridUnaVez=true;

/*	Ext.getCmp('Dist').on('select',function(){
		if(Ext.getCmp('Dist').getValue()=='Equitativo' && Ext.getCmp('MontoEq').getValue()>0)
		{
			//Acum=0;
			//gridMontos.store.each(LlenaMontoEq);	
		}
	})
*/	
/*
}
else
{
	panelMetas.getComponent('Meta').setValue(CodMeta);
	panelMetas.getComponent('denom').setValue(DenMeta);
	//panelMetas.getComponent('MontoEq').setValue(MontGlobal);
}
*/			
		//	var DatosNuevo={"raiz":[{"programatica":'',"spg_cuenta":'',"year":'',"monto":''}]};	
			var storeMesesConG = new Ext.data.SimpleStore
			({
			        fields: 
					[
			           {name: 'numes'},
			           {name: 'mes'},
			           {name: 'cantmasculino'},
			           {name: 'cantfemenino'},
			           {name: 'totalcant'}
			        ]
			 });
			 
			 var storeMesesSinG = new Ext.data.SimpleStore
			({
			        fields: 
					[
			           {name: 'numes'},
			           {name: 'mes'},
			           {name: 'cantmasculino'},
			           {name: 'cantfemenino'},
			           {name: 'totalcant'}
			        ]
			 });
			 			 
			storeMesesConG.loadData(Meses);
			storeMesesSinG.loadData(Meses);
			gridConG = new Ext.grid.EditorGridPanel({
  			width:450,
  			style:'margin-left:40px',
  			title: 'Distribución Mensual por Género',
			autoScroll:true,
            border:true,
            ds:storeMesesConG,
            cm: new Ext.grid.ColumnModel([
            {header: "Mes", width: 50, sortable: true,   dataIndex: 'mes'},
			{header: "Femenino", width: 80, sortable: true, dataIndex: 'cantfemenino',editor: new Ext.form.NumberField({allowBlank:false,allowDecimals:false,id:'montofem'})},
			{header: "Masculino", width: 80, sortable: true, dataIndex: 'cantmasculino',editor: new Ext.form.NumberField({allowBlank:false,allowDecimals:false,id:'montomas'})}
										
]),

			selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
            viewConfig: {
            	forceFit:true	
            }
            ,
			autoHeight:true
            });
            
         
            gridSinG = new Ext.grid.EditorGridPanel({
  			width:450,
  			style:'margin-left:40px',
  			title: 'Distribución Mensual',
			autoScroll:true,
            border:true,
            ds:storeMesesSinG,
            cm: new Ext.grid.ColumnModel([
            {header: "Mes", width: 50, sortable: true,   dataIndex: 'mes'},
			{header: "Cantidad", width: 100, sortable: true, dataIndex: 'cantmasculino',editor:new Ext.form.NumberField({allowBlank:false,allowDecimals:false})},							
]),

selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig: {
                            forceFit:true	
                        },
			autoHeight:true,
			stripeRows: true
            });
               	
            	if (Genero=='1')
            	{
            		auxGridMetas = gridConG;
            	}
            	else
            	{
            		auxGridMetas = gridSinG;
            	}
                  
                  ventana = new Ext.Window(
                 {
                    layout:'anchor',
                    title: 'Distribución de la Meta',
		    		autoScroll:true,
                    width:550,
                    height:500,
                    modal: true,
                    closeAction:'hide',
                    plain: false,
                    items:[panelMetas,auxGridMetas],
                    buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {
		      			GrabarPlanMeta();
		      			grabarmeta();
		      			ventana.destroy();
		      			panelMetas.destroy();
		      			auxGridMetas.destroy();
		      			radio1.destroy();
		      			radio1=''
                     }
                    },{
                     text: 'Salir',
                     handler: function()
                     {
                     	 ventana.destroy();
                     	 panelMetas.destroy();
		      			 auxGridMetas.destroy();
		      			 radio1.destroy();
		      			 radio1=''
                     }
                    }]
                   });
                  
                  ventana.show();
                  
            AcuMontoMasAux=0;
            AcuMontoTotAux=0;
		  	Ext.getCmp('montomas').on('blur',function(Obj)
		  	{
		  		Rec =gridConG.getSelectionModel().getSelected();
		  		if(Rec.get('cantfemenino')=='')
		  		{
		  			Rec.set('cantfemenino',0);
		  		}
		  		if(Rec.get('cantmasculino')=='')
		  		{
		  			Rec.set('cantmasculino',0);
		  		}
				
		  		valortotal = parseInt(Rec.get('cantmasculino'))+parseInt(Rec.get('cantfemenino'));
		  		Rec.set('totalcant',valortotal);
		  		AcuMontoTotAux+=parseInt(valortotal);
		  		AuxMonto = Obj.getValue();
			  	if(AuxMonto=='')
			  	{
			  		AuxMonto=0;
			  	}
		  		AcuMontoMasAux+=parseInt(AuxMonto);
		  		gridConG.store.getAt(12).set('cantmasculino',AcuMontoMasAux);
		  		gridConG.store.getAt(12).set('totalcant',AcuMontoTotAux);
		  		gridConG.getSelectionModel().selectNext();
		  	});
		  	
		  	AcuMontoFemAux=0;
		  	Ext.getCmp('montofem').on('blur',function(Obj)
		  	{
		  		AuxMonto = Obj.getValue();
			  	if(AuxMonto=='')
			  	{
			  		AuxMonto=0;
			  	}
			  	AcuMontoFemAux +=parseInt(AuxMonto);
		  		gridConG.store.getAt(12).set('cantfemenino',AcuMontoFemAux);
		  		gridConG.store.getAt(12).set('cantfemenino',AcuMontoFemAux);
		  	}); 			  			 

		  	gridConG.getView().getRowClass = function(record, index){
		  	if(record.data.mes=='Total')
		  		{
		  			return 'Total';
		  		}
      			
    		}; 
                                  
}

function GrabarPlanMeta()
{	
	//Anno = ForMontos.getComponent('comboYear').getValue();
//	Meta = panelMetas.getComponent('denom').getValue();
	MontosMeses = auxGridMetas.store.getRange(0,11);
	
	RegistroSelVar.set('enero_masc',MontosMeses[0].get("cantmasculino"));
	RegistroSelVar.set('febrero_masc',MontosMeses[1].get("cantmasculino"));
	RegistroSelVar.set('marzo_masc',MontosMeses[2].get("cantmasculino"));
	RegistroSelVar.set('abril_masc',MontosMeses[3].get("cantmasculino"));
	RegistroSelVar.set('mayo_masc',MontosMeses[4].get("cantmasculino"));
	RegistroSelVar.set('junio_masc',MontosMeses[5].get("cantmasculino"));
	RegistroSelVar.set('julio_masc',MontosMeses[6].get("cantmasculino"));
	RegistroSelVar.set('agosto_masc',MontosMeses[7].get("cantmasculino"));
	RegistroSelVar.set('septiembre_masc',MontosMeses[8].get("cantmasculino"));
	RegistroSelVar.set('octubre_masc',MontosMeses[9].get("cantmasculino"));
	RegistroSelVar.set('noviembre_masc',MontosMeses[10].get("cantmasculino"));
	RegistroSelVar.set('diciembre_masc',MontosMeses[11].get("cantmasculino"));
	RegistroSelVar.set('enero_fem',MontosMeses[0].get("cantfemenino"));
	RegistroSelVar.set('febrero_fem',MontosMeses[1].get("cantfemenino"));
	RegistroSelVar.set('marzo_fem',MontosMeses[2].get("cantfemenino"));
	RegistroSelVar.set('abril_fem',MontosMeses[3].get("cantfemenino"));
	RegistroSelVar.set('mayo_fem',MontosMeses[4].get("cantfemenino"));
	RegistroSelVar.set('junio_fem',MontosMeses[5].get("cantfemenino"));
	RegistroSelVar.set('julio_fem',MontosMeses[6].get("cantfemenino"));
	RegistroSelVar.set('agosto_fem',MontosMeses[7].get("cantfemenino"));
	RegistroSelVar.set('septiembre_fem',MontosMeses[8].get("cantfemenino"));
	RegistroSelVar.set('octubre_fem',MontosMeses[9].get("cantfemenino"));
	RegistroSelVar.set('noviembre_fem',MontosMeses[10].get("cantfemenino"));
	RegistroSelVar.set('diciembre_fem',MontosMeses[11].get("cantfemenino"));
	RegistroSelVar.set('NuevoRegistro', true);
	
}
function grabarmeta()
{
//alert("sss");
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
	if(gridIntVar.getSelectionModel().getSelected() && gridIntVar.getSelectionModel().getSelected().get('NuevoRegistro')==true)
	{
	arrMetas = gridIntVar.getSelectionModel().getSelected();
	metas=false;
	//if(arrMetas.length>0)
	//{	
		reg=reg+ ",DatosMetas:[";
	//	for(i=0;i<arrMetas.length;i++)
	//	{
	//		if(i==0)
	//		{
				reg= reg +"{'ano_presupuesto':'2009','cod_var':'"+ arrMetas.get('cod_var')+"','enero_masc':'"+arrMetas.get('enero_masc')+"','febrero_masc':'"+arrMetas.get('febrero_masc')+"','marzo_masc':'"+arrMetas.get('marzo_masc')+"','abril_masc':'"+arrMetas.get('abril_masc')+"','mayo_masc':'"+arrMetas.get('mayo_masc')+"','junio_masc':'"+arrMetas.get('junio_masc')+"','julio_masc':'"+arrMetas.get('julio_masc')+"','agosto_masc':'"+arrMetas.get('agosto_masc')+"','septiembre_masc':'"+arrMetas.get('septiembre_masc')+"','octubre_masc':'"+arrMetas.get('octubre_masc')+"','noviembre_masc':'"+arrMetas.get('noviembre_masc')+"','diciembre_masc':'"+arrMetas.get('diciembre_masc')+"','enero_fem':'"+arrMetas.get('enero_fem')+"','febrero_fem':'"+arrMetas.get('febrero_fem')+"','marzo_fem':'"+arrMetas.get('marzo_fem')+"','abril_fem':'"+arrMetas.get('abril_fem')+"','mayo_fem':'"+arrMetas.get('mayo_fem')+"','junio_fem':'"+arrMetas.get('julio_fem')+"','agosto_fem':'"+arrMetas.get('agosto_fem')+"','septiembre_fem':'"+arrMetas.get('septiembre_fem')+"','octubre_fem':'"+arrMetas.get('octubre_fem')+"','noviembre_fem':'"+arrMetas.get('noviembre_fem')+"','diciembre_fem':'"+arrMetas.get('diciembre_fem')+"'}";
		//	}
		//	else
		//	{
		//		reg= reg +",{'ano_presupuesto':'2009','cod_var':'"+ arrMetas[i].get('cod_var')+"','enero_masc':'"+arrMetas[i].get('enero_masc')+"','febrero_masc':'"+arrMetas[i].get('febrero_masc')+"','marzo_masc':'"+arrMetas[i].get('marzo_masc')+"','abril_masc':'"+arrMetas[i].get('abril_masc')+"','mayo_masc':'"+arrMetas[i].get('mayo_masc')+"','junio_masc':'"+arrMetas[i].get('julio_masc')+"','agosto_masc':'"+arrMetas[i].get('agosto_masc')+"','septiembre_masc':'"+arrMetas[i].get('septiembre_masc')+"','octubre_masc':'"+arrMetas[i].get('octubre_masc')+"','noviembre_masc':'"+arrMetas[i].get('noviembre_masc')+"','diciembre_masc':'"+arrMetas[i].get('diciembre_masc')+"','enero_fem':'"+arrMetas[i].get('enero_fem')+"','febrero_fem':'"+arrMetas[i].get('febrero_fem')+"','marzo_fem':'"+arrMetas[i].get('marzo_fem')+"','abril_fem':'"+arrMetas[i].get('abril_fem')+"','mayo_fem':'"+arrMetas[i].get('mayo_fem')+"','junio_fem':'"+arrMetas[i].get('julio_fem')+"','agosto_fem':'"+arrMetas[i].get('agosto_fem')+"','septiembre_fem':'"+arrMetas[i].get('septiembre_fem')+"','octubre_fem':'"+arrMetas[i].get('octubre_fem')+"','noviembre_fem':'"+arrMetas[i].get('noviembre_fem')+"','diciembre_fem':'"+arrMetas[i].get('diciembre_fem')+"'}";
		//	}
		//}
		reg = reg + "]";
	}
	else
	{
		return false;
	}	
	
	reg = reg + "}";
	
	
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
					if(gridIntVar.getSelectionModel().getSelected())
						gridIntVar.getSelectionModel().getSelected().set('NuevoRegistro',false);	
							
					//location.href='sigesp_spe_formGasto.php';							
				 }
				 else if(Registros[1]=='-5')
				 {
				  	Ext.MessageBox.alert('Error', 'La integración presupuestaria seleccionada ya existe, la combinación de la estructura del plan y la estructura presupuestaria seleccionada ya fue registrada, verifique mediante el catálogo');
				  	//EstadoInicial();
				 }
				else if(Registros[1]=='-1')
				 {
				  	Ext.MessageBox.alert('Error', 'La integración presupuestaria seleccionada ya existe, la combinación de la estructura del plan y la estructura presupuestaria seleccionada ya fue registrada, verifique mediante el catálogo');
				  	//EstadoInicial();
				 } else if(Registros[1]=='0')
				 {
				 	Ext.MessageBox.alert('Mensaje', 'No se pudo realizar la operación');
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

