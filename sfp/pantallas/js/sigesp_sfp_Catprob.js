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

function ActualizarData(criterio,cadena)
{
	var myJSONObject ={
		"oper": 'buscarcadena',
		"cadena": cadena,
		"criterio": criterio
	};

	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaProb2,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ){ 
	datos = resultado.responseText;
	if(datos!='')
	{
		var DatosNuevo = eval('(' + datos + ')');
		grid.store.loadData(DatosNuevo);
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
			

function PasarDatosGrids2()
{

	p = new RecordDefProb
	({
		'codprob':'',
		'denominacion':''
	});
	
	gridIntProb.store.insert(0,p);
	//gridIntProb.startEditing(0,0);
	p.set('denominacion',grid.getSelectionModel().getSelected().get('denominacion'));
	p.set('codprob',grid.getSelectionModel().getSelected().get('codprob'));
	//gridIntProb.stopEditing();	
}
               
function MostrarCatalogoProb()
{
	
	var myJSONObject =
	{
		"oper": 'catalogo', 
		"codprob": '', 
		"denominacion": '',
		"descripcion":'',
		"causa":'',
		"efecto":''
	};

	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : rutaProb2,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ){
		  datos = resultado.responseText;
		//  alert(datos); 
		  var myObject = eval('(' + datos + ')');
		 if(myObject.raiz==null)
		 {
			
			var myObject={"raiz":[{"codprob":'',"denominacion":'',"descripcion":'',"caracteristicas":'',"causa":'',"efecto":''}]};	
		
		}
		
		var RecordDef = Ext.data.Record.create([
		{name: 'codprob'},     
		{name: 'denominacion'},
		{name: 'descripcion'},
		{name: 'caracteristica'},
		{name: 'causa'},
		{name: 'efecto'}
		]);

            if (!gridOnOff)
            {
            grid = new Ext.grid.GridPanel({
			width:770,
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
                        {header: "Descripción", width: 70, sortable: true,   dataIndex: 'descripcion'},
                        {header: "Causas", width: 50, sortable: true, dataIndex: 'causa'},
			    		{header: "Efectos", width: 70, sortable: true, dataIndex: 'efecto'}
							
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
		if (simple=='')
		{		  		  
	 		simple = new Ext.FormPanel({
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
							 ActualizarData('codprob',v);
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
							 ActualizarData('descripcion',v);
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
                    title: 'Cat&aacute;logo de Problemas',
		    		autoScroll:true,
                    width:800,
                    height:400,
                    modal: true,
                    closeAction:'hide',
                    plain: false,
                    items:[simple,grid],
                    buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {
	                    PasarDatosGrids2();	    
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
