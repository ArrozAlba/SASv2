/*
Catalo go de problemas
*/

var gridOnOff = false;
var winOnOff = false;
var datos = null;
var grid = null;
var win = null;
var unavez = false;
var parametros='';
var ruta = '';
var Mygrid="";
var simple ='';
var ParamGridTarget='';
rutaProb2 ='../../procesos/sigesp_spe_problemaspr.php';
function CatProb()
{		

	this.MostrarCatalogo =MostrarCatalogoProb;
	this.ActualizarData=ActualizarData;  
}

function ActualizarDataEst(criterio,cadena)
{
	gridEst.store.filter(criterio,cadena);
}


function AgregarKeyPress(Obj)
{
		Ext.form.TextField.superclass.initEvents.call(Obj);
		if(Obj.validationEvent == 'keyup')
		{
			Obj.validationTask = new Ext.util.DelayedTask(Obj.validate, Obj);
			Obj.el.on('keyup', Obj.filterValidation, Obj);
		}
		else if(Obj.validationEvent !== false)
		{
			Obj.el.on(Obj.validationEvent, Obj.validate, Obj, {buffer: Obj.validationDelay});
		}
		if(Obj.selectOnFocus || Obj.emptyText)
		{
			Obj.on("focus", Obj.preFocus, Obj);
			if(Obj.emptyText)
			{
				Obj.on('blur', Obj.postBlur, Obj);
				Obj.applyEmptyText();
			}
		}
		if(Obj.maskRe || (Obj.vtype && Obj.disableKeyFilter !== true && (Obj.maskRe = Ext.form.VTypes[Obj.vtype+'Mask']))){
			Obj.el.on("keypress", Obj.filterKeys, Obj);
		}
		if(Obj.grow)
		{
			Obj.el.on("keyup", Obj.onKeyUp,  Obj, {buffer:50});
			Obj.el.on("click", Obj.autoSize,  Obj);
		}
			Obj.el.on("keyup", Obj.changeCheck, Obj);
}
			

function LimpiarPantalla()
{
	gridEstPreSelec.store.removeAll();
	gridIntGastos.store.removeAll();
	gridIntVar.store.removeAll();
	
}


function PasarDatosGrids2(Registro)
{
		LimpiarPantalla();
		codinte=Registro.get('codinte');
		IdPadre=codinte;
		r=new RecordDefPlaPre
		(
			{
				'codigo':'',
				'descripcion':''				
			}
		);
	   gridEstPreSelec.store.insert(gridEstPreSelec.store.getCount(),r);
	   r.set('codigo',Registro.get('codigo'));
	   r.set('descripcion',Registro.get('descripcion'));
	  
	var myJSONObject =
	{
		"oper": 'buscardetalles',
		"codinte":codinte
	};

	ObjSon=JSON.stringify(myJSONObject);
	//alert(ObjSon);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ){ 
		  datos = resultado.responseText;
		// alert(datos);
				if(datos!='|0')
				{
					  	ArrayObject = datos.split('|');
					  	var DatJsonMetas = eval('(' + ArrayObject[0] + ')');
					  	var DatJsonCuentas = eval('(' + ArrayObject[1] + ')');		  
			  	  		var DatJsonIndis = eval('(' + ArrayObject[2] + ')');
			  	}
	  	   		if(DatJsonCuentas.raiz!=null)
				{
				    gridIntGastos.store.loadData(DatJsonCuentas);
				}
				if(DatJsonMetas.raiz!=null)
				{
				    gridIntVar.store.loadData(DatJsonMetas);
				}	
				if(DatJsonIndis.raiz!=null)
				{
				    gridIntInd.store.loadData(DatJsonIndis);
				}	
					  
	  }
	  })
	 
}
               
function irAgregarEstPre()
{
	var myJSONObject =
	{
		"oper": 'catalogoEstInt'
	};

	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaIntepr,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ){
		  datos = resultado.responseText;
		//  alert(datos); 
		  var myObject = eval('(' + datos + ')');
		 if(myObject.raiz==null)
		 {
			var myObject={"raiz":[{"codigo":'',"descripcion":''}]};
		 }
		
		var RecordDefEst = Ext.data.Record.create([
			{name: 'codigo'},     
			{name: 'descripcion'},
			{name: 'codinte'}
		]);

           
            gridEst = new Ext.grid.GridPanel({
			width:770,
			height:400,
			autoScroll:true,
            border:true,
            ds: new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(myObject),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                
			     id: "id"   
            }
			,
               RecordDefEst
			)
			,
			data: myObject
             }),
                      cm: new Ext.grid.ColumnModel([
                        {header: "Código", width: 70, sortable: true,   dataIndex: 'codigo'},
                        {header: "Descripción", width: 50, sortable: true, dataIndex:'descripcion'},
			           ]),

                        viewConfig: {
                            forceFit:true
                        }
                        ,
						stripeRows: true
                   }); 
                
                 
			  		  
	 	simpleEst = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
        url:'save-form.php',
        frame:true,
        title: 'Búsqueda',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
		height:120,
        defaults: {width: 230},
        defaultType: 'textfield',
		items: [{
                fieldLabel: 'Código',
                name: 'cod',
				id:'cod',
				changeCheck: function(){
							  var v = this.getValue();
							 ActualizarDataEst('codigo',v);
							if(String(v) !== String(this.startValue)){
								this.fireEvent('change', this, v, this.startValue);
							} 
							 },
							 
							initEvents : function()
							{
								AgregarKeyPress(this);
							}
               
            },{
                fieldLabel: 'Descripción',
                name: 'den',
						changeCheck: function(){
							  var v = this.getValue();
							 ActualizarDataEst('descripcion',v);
							if(String(v) !== String(this.startValue))
							{
								this.fireEvent('change', this, v, this.startValue);
							} 
							 },
							 
							initEvents : function()
							{
								AgregarKeyPress(this);
							}
			
			
            }]
		});
		
		
                   wincatEst = new Ext.Window(
                   {
                    //layout:'fit',
                    title: 'Cat&aacute;logo de Estructuras Presupuestarias Integradas',
		    		autoScroll:true,
                    width:800,
                    height:400,
                    modal: true,
                    closeAction:'hide',
                    plain: false,
                    items:[simpleEst,gridEst],
                    buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {
                     	Registro = gridEst.getSelectionModel().getSelected();
                     	PasarDatosGrids2(Registro);	    
			      		wincatEst.destroy();
                     }
                    },
                    {
                     text: 'Salir',
                     handler: function()
                     {
                      wincatEst.destroy();
                     }
                    }]
                   });
                  wincatEst.show();         
        }
        ,
        failure: function ( resultado, request) { 
                   Ext.MessageBox.alert('Error', resultado.responseText); 
        }
   });

 };
