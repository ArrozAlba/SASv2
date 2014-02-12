/*
Catalo go de functes de financiamiento
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
var codfuefin='';
var denfuefin='';
var gridCli="";
var expfuefin = '';
var ParamTarget='';
rutaProb ='../../procesos/sigesp_registro_horaspr.php';
function CatProb()
{		

	this.MostrarCatalogo =MostrarCatalogoProb;
	this.ActualizarData=ActualizarData;  
}

function ActualizarData(criterio,cadena)
{
	var myJSONObject ={
		"oper": 'buscarcadenaClie',
		"cadena": cadena,
		"criterio": criterio
	};

	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaProb,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request )
	{ 
		datos = resultado.responseText;
		if(datos!='')
		{
		//	alert(datos);
			var DatosNuevo = eval('(' + datos + ')');
			gridCli.store.loadData(DatosNuevo);
		}	
	}
});
	
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
			

function PasarDatosGrids1()
{

if(gridCli.getSelectionModel().getSelected())
{
	//gridCli.getSelectionModel().getSelected().get('propiedad1')
	ObjTxtCli.setValue(gridCli.getSelectionModel().getSelected().get('propiedad1'));
	Ext.getCmp('codcliente').setValue(gridCli.getSelectionModel().getSelected().get('propiedad0'));
	
}
	
}
               
function MostrarCatalogoProb()
{
	
var myJSONObject =
	{
		"oper": 'Catalogos', 
		"cedcon": "0000000001"
		
	};	
	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta2,
	params : parametros,
	method: 'POST',
	success: function (resultado, request ) { 
	datos = resultado.responseText;
	//alert(datos);
	ArrDatos = datos.split('|');
//	alert(ArrDatos[0]);
	DatosNuevoCli = eval('(' + ArrDatos[0] + ')');
//	dsClientes.loadData(DatosNuevoCli);
	//DatosNuevoSer = eval('(' + ArrDatos[1] + ')');
	//dsSer.loadData(DatosNuevoSer);
	//DatosNuevoMod = eval('(' + ArrDatos[2] + ')');
	//dsMod.loadData(DatosNuevoMod);
	DatosRegistro = eval('(' + ArrDatos[3] + ')');

		 if(myObject.raiz==null)
		 {
			
			DatosNuevoCli={"raiz":[{"propiedad0":'001',"propiedad1":'denominacion'}]};	
		
		 }
		
	var RecordDefClient = Ext.data.Record.create
		([
			{name: 'propiedad0'},// "mapping" property not needed if it's the same 
			{name: 'propiedad1'}
		]);

	
		var dsClientes= new Ext.data.Store({
		reader: new Ext.data.JsonReader({
		root:'raiz',               
		id: "id"   
		},
	     RecordDefClient
		),
		data: DatosNuevoCli
	   })



            if (!gridOnOff)
            {
            gridCli = new Ext.grid.GridPanel({
			width:770,
			autoScroll:true,
            border:true,
            ds: dsClientes,
                        cm: new Ext.grid.ColumnModel([
                        {header: "Código", width: 30, sortable: true,   dataIndex: 'propiedad0'},
                        {header: "Denominación", width:70, sortable: true, dataIndex: 'propiedad1'}
							
                        ]),

                        viewConfig: {
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
                   gridOnOff = true;
                 }
                  else
                  {
                  	grid.store.loadData(myObject);
                  
                  } 
				  		  
	var simple = new Ext.FormPanel({
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
                fieldLabel: 'Denominacion',
                name: 'den',
			changeCheck: function(){
							  var v = this.getValue();
							 ActualizarData('nomcli',v);
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
				  
		
                  if(!winOnOff)
                  {
                  	
                   win = new Ext.Window(
                   {
                    //layout:'fit',
                    title: 'Cat&aacute;logo de Clientes',
		    		autoScroll:true,
                    width:800,
                    height:400,
                    modal: true,
                    closeAction:'hide',
                    plain: false,
                    items:[simple,gridCli],
                    buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {
	                    PasarDatosGrids1();	    
			      		win.hide();
                      
                     }
                    },
                    {
                     text: 'Salir',
                     handler: function()
                     {
                      win.hide();
                     }
                    }]
                   });
                   //winOnOff = true;
                   //estaba alla donde dice aqui
                  }
                  else
                  {
                   //win.add(grid);
                   //alert(win.title);
                  }
                  //estaba aqui
                  win.show();
                   if(!unavez)
                   {
                    grid.render('miGrid');
                    unavez=false;
                   }
                   grid.getSelectionModel().selectFirstRow();
        },
        failure: function ( resultado, request) { 
                   Ext.MessageBox.alert('Error', resultado.responseText); 
        }
   });

 };
