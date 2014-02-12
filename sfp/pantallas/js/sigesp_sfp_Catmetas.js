/*
Catalogo de metas
*/

var gridOnOff = false;
var winOnOff = false;
var datos = null;
var gridMeta = '';
var win = null;
var unavez = false;
var parametros='';
var ruta = '';
var Mygrid="";
var panelMeta ='';
var ParamGridTarget='';
rutaProb ='../../procesos/sigesp_spe_metapr.php';
function CatMetas()
{		

	this.MostrarCatalogo =MostrarCatalogoMeta;
	this.ActualizarData=ActualizarData;  
}

function ActualizarDataMeta(criterio,cadena)
{
	var myJSONObject ={
		"oper": 'buscarcadena',
		"cadena": cadena,
		"criterio": criterio
	};

	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaProb,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ){ 
	datos = resultado.responseText;
	//alert(datos);
	if(datos!='')
	{
		var DatosNuevo = eval('(' + datos + ')');
		gridMeta.store.loadData(DatosNuevo);
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
			

function PasarDatosGrids3()
{

	p = new RecordDefVar
	({
	'cod_var':'',
	'meta':'',
	'unidad':'',
	'genero':'',
	});
	
	gridIntVar.store.insert(0,p);
	//gridIntProb.startEditing(0,0);
	p.set('meta',gridMeta.getSelectionModel().getSelected().get('meta'));
	p.set('cod_var',gridMeta.getSelectionModel().getSelected().get('cod_var'));
	p.set('unidad',gridMeta.getSelectionModel().getSelected().get('unidad'));
	p.set('genero',gridMeta.getSelectionModel().getSelected().get('genero'));
	//gridIntProb.stopEditing();	
}
               
function MostrarCatalogoMeta()
{
	
	var myJSONObject =
	{
		"oper": 'catalogo', 
		"cod_var": '', 
		"meta": '',
		"cod_uni":'',
		"unidad":'',
		"genero":''
	};

	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaProb,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ){
		  datos = resultado.responseText;
		 // alert(datos); 
		  var myObject = eval('(' + datos + ')');
		 if(myObject.raiz==null)
		 {
			
			var myObject={"raiz":[{"cod_var":'',"meta":'',"cod_uni":'',"unidad":'',"genero":''}]};	
		
		}
		
		var RecordDef = Ext.data.Record.create([
		{name: 'cod_var'},     
		{name: 'meta'},
		{name: 'cod_uni'},
		{name: 'unidad'},
		{name: 'genero'},
		]);

            if (gridMeta=='')
            {
            gridMeta = new Ext.grid.GridPanel({
			width:600,
			autoScroll:true,
            border:true,
            ds: new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(myObject),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                
			     id: "id"   
            }
			,
               RecordDef
			),
			data: myObject
             }),
                        cm: new Ext.grid.ColumnModel([
                        {header: "Código", width: 100, sortable: true,   dataIndex: 'cod_var'},
                            {header: "Denominación", width: 200, sortable: true, dataIndex: 'meta'},
			//    {header: "Efectos", width: 70, sortable: true, dataIndex: 'efectos'}
							
                        ]),

                        viewConfig: {
                            forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });
                   //gridOnOff = true;
                 }
                  else
                  {
                  	gridMeta.store.loadData(myObject);
                  
                  } 
			if (panelMeta=='')
			{	  		  
		panelMeta = new Ext.FormPanel({
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
                name: 'codigom',
				id:'codigom',
				changeCheck: function(){
							  var v = this.getValue();
							 ActualizarDataMeta('cod_var',v);
							if(String(v) !== String(this.startValue)){
								this.fireEvent('change', this, v, this.startValue);
							} 
							 },
							 
							initEvents : function()
							{
								AgregarKeyPress(this);
							}
               
            },{
                fieldLabel: 'Denominación',
                name: 'den',
			changeCheck: function(){
							  var v = this.getValue();
							 ActualizarDataMeta('denominacion',v);
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
			}	
                  if(!winOnOff)
                  {
                   win = new Ext.Window(
                   {
                    //layout:'fit',
                    title: 'Cat&aacute;logo de Metas',
		    		autoScroll:true,
                    width:600,
                    height:400,
                    modal: true,
                    closeAction:'hide',
                    plain: false,
                    items:[panelMeta,gridMeta],
                    buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {
	                    PasarDatosGrids3();	    
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
                    gridMeta.render('miGrid');
                    unavez=false;
                   }
                   gridMeta.getSelectionModel().selectFirstRow();
        },
        failure: function ( resultado, request) { 
                   Ext.MessageBox.alert('Error', resultado.responseText); 
        }
   });

 };
