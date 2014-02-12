var dsEmp="";
var gridEmp="";
var formBusEmp="";
var RecordDefEmp="";
function crearDataStoreEmp()
{
		 RecordDefEmp = Ext.data.Record.create([
				{name: 'codemp'},    
				{name: 'nombre'},
				{name: 'rifemp'},	
				{name: 'nitemp'},
				{name: 'base_legal'},
				{name: 'ano_inicio'},
				{name: 'sector'},
				{name: 'base_legal'},
				{name: 'consolidadora'},
				{name: 'codemp_con'},
				{name: 'forma_juri'},
				{name: 'mision'},
				{name: 'vision'},
				{name: 'telemp'},
				{name: 'faxemp'},
				{name: 'email'},
				{name: 'website'},
				{name: 'zonepos'},
				{name: 'estemp'},
				{name: 'ciuemp'},
				{name: 'diremp'},
				{name: 'nom_presi'},
				{name: 'tel_presi'},
				{name: 'email_presi'},
				{name: 'nom_dirplan'},
				{name: 'tel_dirplan'},
				{name: 'email_dirplan'},
				{name: 'nom_diradmin'},
				{name: 'tel_diradmin'},
				{name: 'email_diradmin'},
				{name: 'nom_dirrh'},
				{name: 'tel_dirrh'},
				{name: 'email_dirrh'},
				{name: 'nom_respre'},
				{name: 'tel_respre'},
				{name: 'email_respre'},
				{name: 'compat'},
				{name: 'politicapre'},
				{name: 'emprin'},
				{name: 'formspi'},
				{name: 'formpre'}
		]);
			var myObject={"raiz":[{"codemp":'',"nombre":''}]};
			 dsEmp =  new Ext.data.Store({
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

function actDataStoreEmp(criterio,cadena)
{
	var myJSONObject ={
		"oper": 'buscarcadena',
		"cadena": cadena,
		"criterio": criterio
	};
	ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		Ext.Ajax.request({
		url : ruta,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request ){ 
			  datos = resultado.responseText;
			  //alert(datos);
			 if(datos!='')
			 {
				var DatosNuevo = eval('(' + datos + ')');
			 	if(DatosNuevo.raiz!=null)
			 	{
			 		dsEmp.loadData(DatosNuevo);
				}
			 }	
		}
	});	
}


function crearFormBusqueda()
{
		formBusEmp = new Ext.FormPanel({
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
							actDataStoreEmp('codemp',v);
							if(String(v) !== String(this.startValue))
							{
								this.fireEvent('change', this, v, this.startValue);
							} 
							},							 
							initEvents : function()
							{
								AgregarKeyPress(this);
							}               
            },{
                fieldLabel: 'Denominacion',
                name: 'den',
			changeCheck: function()
			{
							var v = this.getValue();
							actDataStoreEmp('nombre',v);
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

function CrearGrid()
{
	 	crearFormBusqueda();
		crearDataStoreEmp();
		 
	 gridEmp = new Ext.grid.GridPanel({
	 width:770,
	 height:400,
	 tbar:formBusEmp,
	 autoScroll:true,
     border:true,
     ds:dsEmp,
     cm: new Ext.grid.ColumnModel([
          {header: "Código", width: 30, sortable: true,   dataIndex: 'codemp'},
          {header: "Nombre", width: 50, sortable: true, dataIndex: 'nombre'}
       ]),
       stripeRows: true,
      viewConfig: {
      	forceFit:true
      }
      ,
      });            
} 

function MostrarCatEmp()
{
				   CrearGrid();
				   ObjetoFuente='definicion';
                   winCatEmp = new Ext.Window(
                   {
                    //layout:'fit',
                    title: 'Cat&aacute;logo de Empresas',
		    		autoScroll:true,
                    width:800,
                    height:400,
                    modal: true,
                    closable:false,
                    plain: false,
                    items:[gridEmp],
                    buttons: [{
                    text:'Aceptar',  
                    handler: function()
                    { 
                    	Registro = gridEmp.getSelectionModel().getSelected();
                    	switch(ObjetoFuente)   
                    	{
                    		case 'definicion':
                    			limpiarCampos();
	                    		PasDatosGridDef(Registro);
	                    	break;
                    		case 'grid':
		                    	PasDatosGridGrid(Registro);
	                    	break;
                    		case 'objeto':
	                    		PasDatosGridObj(Registro);
	                    	break;
 
                    		
                    	}          
                    	gridEmp.destroy();
		      			winCatEmp.destroy();                      
                    }
                    }
                    ,
                    {
                     text: 'Salir',
                     handler: function()
                     {
                     	
                      	gridEmp.destroy();
		      			winCatEmp.destroy();
                     }
                    }]
                    
                   });
                  winCatEmp.show();       
 }