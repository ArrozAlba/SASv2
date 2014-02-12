/*
codigo javascript asociado al archivo fuentes de financiamiento

*/

var gridOnOff = false;
var winOnOff = false;
var datos = null;
var grid = null;
var win = null;
var unavez = false;
var parametros='';
var ruta = '';
Ext.onReady(function(){

ruta ='../../procesos/sigesp_spe_rep_plan_financiamientopr.php';
//LimpiarCampos();

// Ext.get('BtnGrabar').on('click', function()
// {
//  
//if(ValidarObjetos('txtcod','novacio')!='0' && ValidarObjetos('txtden','novacio')!='0' && ValidarObjetos('txtexp','novacio')!='0')
//{
//		if(Ext.get('actualizar').dom.value=='')
//		{
//			evento ='incluir';
//			Mensa = "Incluido";
//		}
//		else
//		{	
//			evento ='actualizar';			
//			Mensa = "Modificado";
//		}
//	
//var myJSONObject ={
//				"oper": evento, 
//				"cod_fuenfin": Ext.get('txtcod').dom.value, 
//				"denfuefin": Ext.get('txtden').dom.value,
//				"expfuefin":Ext.get('txtexp').dom.value,
//				"codemp":'0001'
//			
//			     };
//
//
//ObjSon=JSON.stringify(myJSONObject);
//parametros = 'ObjSon='+ObjSon; 
//    Ext.Ajax.request({
//	url : ruta,
//	params : parametros,
//	method: 'POST',
//	success: function ( resultad, request ) { 
//                 datos = resultad.responseText;
//				//alert(datos);
//			
//                 var Registros = datos.split("|");
//                 if (Registros[1] == '1')
//                 {
//					 Ext.MessageBox.alert('Mensaje','Registro '+Mensa + ' con éxito');
//					 LimpiarCampos();
//  
//                 }
//                 else
//                 {
//                  Ext.MessageBox.alert('Error', Registros[0]);
//                 }
//	},
//	failure: function ( result, request) { 
//		Ext.MessageBox.alert('Error', result.responseText); 
//	}
//
//      });
//      }	
//    });
 
 
// Ext.get('BtnNuevo').on('click', function()
// {			
//	
//	var myJSONObject ={
//		"oper": 'buscarcodigo', 
//		"cod_fuenfin": Ext.get('txtcod').dom.value, 
//		"denfuefin": Ext.get('txtden').dom.value,
//		"expfuefin":Ext.get('txtexp').dom.value
//	};
//	
//	ObjSon=JSON.stringify(myJSONObject);
//	parametros = 'ObjSon='+ObjSon; 
//	Ext.Ajax.request({
//	url : ruta,
//	params : parametros,
//	method: 'POST',
//	success: function ( resultad, request ) { 
//                datos = resultad.responseText;
//		//alert(datos);
//		 var Registros = datos.split("|");
//		Cod = Registros[1];
//		if(Cod!='')
//		{
//			Ext.get('txtcod').dom.value = Cod;
//			Ext.get('actualizar').dom.value = '';
//		}
//		else
//		{
//			Ext.MessageBox.alert('Mensaje', 'El registro con cota ');
//						
//		}
//      },
//	failure: function ( result, request) { 
//		Ext.MessageBox.alert('Error', result.responseText); 
//	} 
//      });
//    });

 
//function LimpiarCampos()
//{
//	 Ext.get('txtcod').dom.value = '';
//         Ext.get('txtden').dom.value = '';
//         Ext.get('txtexp').dom.value = '';
//}

//Ext.get('BtnElim').on('click',function()
//{
//
//var Result;
//	Ext.MessageBox.confirm('Confirmar', '¿Desea eliminar este registro?', Result);
//	function Result(btn)
//	{
//		if(btn=='yes')
//		{
//			var myJSONObject ={
//				"oper": 'eliminar', 
//				"cod_fuenfin": Ext.get('txtcod').dom.value, 
//				"denfuefin": Ext.get('txtden').dom.value,
//				"expfuefin":Ext.get('txtexp').dom.value,
//				"codemp":'0001'
//			
//			     };	
//			ObjSon=JSON.stringify(myJSONObject);
//			parametros = 'ObjSon='+ObjSon; 
//				     
//			Mensa = "Eliminado";
//			Ext.Ajax.request({
//			url : ruta,
//			params : parametros,
//			method: 'POST',
//			success: function ( resultad, request ) { 
//				 datos = resultad.responseText;
//					//	alert(datos);
//					
//				 var Registros = datos.split("|");
//				 if (Registros[1] == '1')
//				 {
//					Ext.MessageBox.alert('Mensaje','Registro '+Mensa + ' con éxito');
//					LimpiarCampos();
//				 }
//				 else
//				 {
//				  Ext.MessageBox.alert('Error', Registros[0]);
//				 }
//			},
//			failure: function ( result, request) { 
//				Ext.MessageBox.alert('Error', result.responseText); 
//			} 
//		      });
//
//		}
//	
//	};
//	
//    });
 


Ext.get('BtnImp').on('click',function()
{
	var myJSONObject ={
	"oper": 'Reporte'
	}
	alert('por aqui');
ObjSon=JSON.stringify(myJSONObject);
parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) {
			datos = 'http://localhost:8080/birt/frameset?__report=plan_financiamiento.rptdesign'; 
		  //datos = resultado.responseText;
		  
		 // alert(datos);
		 if(datos!='')
		 {
			Abrir_ventana(datos);
		}
	
},
	failure: function ( result, request) 
	{ 
		Ext.MessageBox.alert('Error', result.responseText); 
	} 

});	

});


//function ActualizarData(criterio,cadena)
//{
//
//	var myJSONObject ={
//		"oper": 'buscarcadena',
//		"cadena": cadena,
//		"criterio": criterio,	
//		"cod_fuenfin": Ext.get('txtcod').dom.value, 
//		"denfuefin": Ext.get('txtden').dom.value,
//		"expfuefin":Ext.get('txtexp').dom.value
//	};
//
//
//
//
//ObjSon=JSON.stringify(myJSONObject);
//	parametros = 'ObjSon='+ObjSon; 
//	  Ext.Ajax.request({
//	url : ruta,
//	params : parametros,
//	method: 'POST',
//	success: function ( resultado, request ) { 
//		  datos = resultado.responseText;
//		  //alert(datos);
//		 if(datos!='')
//		 {
//			var DatosNuevo = eval('(' + datos + ')');
//			grid.store.loadData(DatosNuevo);
//		}
//	
//}
//});
//	
//}


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
			
               
function ValidarObjetos(Obj,TipoVal)
{
	switch(TipoVal)
	{
		case 'novacio':
			if(Ext.get(Obj).dom.value=='')
			{
				Ext.MessageBox.alert('Campos Vacios', 'Debe llenar el campo '+Ext.get(Obj).dom.title);
				Ext.get(Obj).dom.focus();
				return '0';
			}
	}
	return '1';
}


	
	
 Ext.get('BtnCat').on('click', function(){
 
	var myJSONObject ={
		"oper": 'catalogo', 
		"cod_fuenfin": Ext.get('txtcod').dom.value, 
		"denfuefin": Ext.get('txtden').dom.value,
		"expfuefin":Ext.get('txtexp').dom.value
	};
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	  Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function ( resultado, request ) { 
		  datos = resultado.responseText;
		//  alert(datos);
		  var myObject = eval('(' + datos + ')');
		  var RecordDef = Ext.data.Record.create([
		{name: 'cod_fuenfin'},     // "mapping" property not needed if it's the same as "name"
		{name: 'denfuefin'},
		{name: 'expfuefin'}	// This field will use "occupation" as the mapping.
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
			    root: 'raiz',                // The property which contains an Array of row objects
			     id: "id"   
			    
                            	},
                              RecordDef
			     
			      ),
				data: myObject
                        }),
                        cm: new Ext.grid.ColumnModel([
                            new Ext.grid.RowNumberer(),
                 
                            {header: "Código", width: 30, sortable: true,   dataIndex: 'cod_fuenfin'},
                            {header: "Denominación", width: 50, sortable: true, dataIndex: 'denfuefin'},
			    {header: "Explicación", width: 70, sortable: true, dataIndex: 'expfuefin'}
							

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
                fieldLabel: 'Código',
                name: 'cod',
				id:'cod',
				changeCheck: function(){
							  var v = this.getValue();
							 ActualizarData('cod_fuenfin',v);
							if(String(v) !== String(this.startValue)){
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
			changeCheck: function(){
							  var v = this.getValue();
							 ActualizarData('denfuefin',v);
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
                    title: 'Cat&aacute;logo de Fuente de Financiamiento',
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
                 
                      Ext.get('txtcod').dom.value = grid.getSelectionModel().getSelected().get('cod_fuenfin');
                      Ext.get('txtden').dom.value = grid.getSelectionModel().getSelected().get('denfuefin');
                      Ext.get('txtexp').dom.value = grid.getSelectionModel().getSelected().get('expfuefin');
		      Ext.get('actualizar').dom.value = 'si';
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

 });

});