/*
 * Ext JS Library 2.0.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * http://extjs.com/license
 */
 
var gridOnOff = false;
var winOnOff = false;
var datos = null;
var grid = null;
var grid3 = null;
var grid4 = null; 
var win = null;
var tabs = null;
var unavez = false;
var parametros='';
var ruta = '';
var RecordDef;
var RecordDefGI='';
var grid2='';
var DataStore='';
var DatosNuevo ="";
var RecordDefConv="";
var IndiceActual="";
var RegistroActual="";
var Oper = "";
var Actualizar='';
var FormularioBus="";
var win1="";
var win2="";
var grid3="";
var GridActual="";
var gridConversion="";
 
ruta ='../../procesos/sigesp_spe_variacionpr.php';
pantalla ='sigesp_spe_variacion.php';

Ext.onReady(function()
{
ObtenerSesion(ruta,pantalla)

function getobject2(obj,obj2)
{
			GridActual='1';
			Oper[0]='incluyendo';
			var DatosNuevo={"raiz":[{"codigo":'',"denominacion":''}]};
			RecordDef = Ext.data.Record.create([
			{name:'codigo'},
			{name: 'denominacion'}
			]);
			
			DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                
			    id: "id"   
			    },
                    RecordDef
			     
			    ),
				data: DatosNuevo
            });
			
			if(grid2=='')
			{
				grid2 = new Ext.grid.EditorGridPanel({
				width:780,
				height:200,
				id:'sc_cuenta',
				autoScroll:true,
	            border:true,
	            ds:DataStore,
	            cm: new Ext.grid.ColumnModel([
	            {header: "Código", width: 50, sortable: true, dataIndex: 'codigo'},
	            {header: "Denominación", width:250, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.TextField({allowBlank: false})}
	                ]),
	selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
	                        viewConfig: {
	                            forceFit:true
	                        },
				autoHeight:true,
				stripeRows: true
	            });
            }
            GetForm();
            if(win1=="")
            {
                   win1 = new Ext.Window(
                   {
                    //layout:'fit',
                    title: 'Cat&aacute;logo de Cuentas Contables',
		    		autoScroll:true,
                    width:800,
                    height:400,
                    modal: true,
                    closeAction:'hide',
                    plain: false,
                    items:[FormularioBus,grid2],
                    buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {
                     	if(grid2.getSelectionModel().getSelected())
                     	{
		                    codigo=grid2.getSelectionModel().getSelected().get('codigo');						   denominacion=grid2.getSelectionModel().getSelected().get('denominacion');
		                    AuxCuenta=codigo+' '+denominacion;
		                    obj.dom.value=AuxCuenta;
		                    obj2.dom.value=codigo;
				      		win1.hide();
			      		}
			      		else
			      		{
							Ext.MessageBox.alert('Mensaje','Debe seleccionar un registro')
						}
                     }
                    }
					,
                    {
                     text: 'Salir',
                     handler: function()
                     {
                      win1.hide();
                     }
                    }]
                   });
                   //winOnOff = true;
                   //estaba alla donde dice aqui
                  }
         		win1.show();
}

function getobject3(obj,obj2)
{
			GridActual='2';
			Oper[0]='incluyendo';
			var DatosNuevo={"raiz":[{"codigo":'',"denominacion":''}]};
			RecordDef = Ext.data.Record.create([
			{name:'codigo'},
			{name: 'denominacion'}
			]);
			
			DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                
			    id: "id"   
			    },
                    RecordDef
			     ),
				data: DatosNuevo
            });
			
		//	if(grid3=='')
		//	{
				grid3 = new Ext.grid.EditorGridPanel({
				width:780,
				height:200,
				id:'codplacaif',
				autoScroll:true,
	            border:true,
	            ds:DataStore,
	            cm: new Ext.grid.ColumnModel([
	            {header: "Código", width: 50, sortable: true, dataIndex: 'codigo'},
	            {header: "Denominación", width:250, sortable: true, dataIndex: 'denominacion',editor: new Ext.form.TextField({allowBlank: false})}
	                        ]),
	selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
	                        viewConfig: {
	                            forceFit:true
	                        },
				autoHeight:true,
				stripeRows: true
	            });       
		//	}
			 GetForm();
		//	 alert(FormularioBus);
          //  if(win2=="")
           // {
                   win2 = new Ext.Window(
                   {
                    //layout:'fit',
                    title: 'Cat&aacute;logo de Cuentas Presupuestarias',
		    		autoScroll:true,
                    width:800,
                    height:400,
                    modal: true,
                    closeAction:'hide',
                    plain: false,
                    items:[FormularioBus,grid3],
                    buttons: [{
                     text:'Aceptar',  
                     handler: function()
                     {
                     
                     	if(grid3.getSelectionModel().getSelected())
                     	{
		                    codigo=grid3.getSelectionModel().getSelected().get('codigo');						   denominacion=grid3.getSelectionModel().getSelected().get('denominacion');
		                    AuxCuenta=codigo+' '+denominacion;
		                    obj.dom.value=AuxCuenta;
		                    obj2.dom.value=codigo;
				      		win2.hide();
			      		}
			      		else
			      		{
							Ext.MessageBox.alert('Mensaje','Debe seleccionar un registro')
						}
                      
                     }
                    }
					,
                    {
                     text: 'Salir',
                     handler: function()
                     {
                      win2.hide();
                     }
                    }]
                   });
                   //winOnOff = true;
                   //estaba alla donde dice aqui
            //}
         	win2.show();
}

function getGridConversion()
{
	var myJSONObject ={
			"oper": 'catalogo'
	};	
	
		ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		Ext.Ajax.request({
		url : ruta,
		params : parametros,
		method: 'POST',
		success: function (resultado, request) { 
		  datos = resultado.responseText;
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			if(DatosNuevo.raiz==null)
			{
				var DatosNuevo={"raiz":[{"codcontable":'',"cuentadebe":'',"cuentahaber":'',"dencuentadebe":'',"dencontable":'',"dencuentahaber":''}]};	
			}
		 }
		 else
		 {
			
				var DatosNuevo={"raiz":[{"codcontable":'',"cuentadebe":'',"cuentahaber":'',"dencuentadebe":'',"dencontable":'',"dencuentahaber":''}]};
		}
	
		RecordDefConv = Ext.data.Record.create
		([
			{name:'cuentacontable'},
			{name:'dencontable'},
			{name:'cuentadebe'},	
			{name:'dencuentadebe'},
			{name:'cuentahaber'},
			{name:'dencuentahaber'}
		]);
			
			DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    },
                      RecordDefConv
			    ),
				data: DatosNuevo
            });
			
			gridConversion = new Ext.grid.EditorGridPanel({
			width:1124,
			autoScroll:true,
            border:true,
            ds:DataStore,
            cm: new Ext.grid.ColumnModel([
            // new Ext.grid.RowNumberer(),
              {header: "Cuenta Contable", width: 25, sortable: true, dataIndex: 'cuentacontable'},
                            
			  {header: "Denominación", width:70, sortable: true, dataIndex: 'dencontable'},
			  {header: "Cuenta de Variación(Debe)", width: 40, sortable: true, dataIndex: 'cuentadebe'},
                            
			  {header: "Denominación", width:70, sortable: true, dataIndex: 'dencuentadebe'}
			 ,{header: "Cuenta de Variación(Haber)", width:40, sortable: true, dataIndex: 'cuentahaber'},
              {header: "Denominación", width:70, sortable: true, dataIndex: 'dencuentahaber'}
])
,
selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
                        viewConfig: {
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
                   });   
		gridConversion.render('ContenedorGridCoversion');
		gridConversion.addListener('celldblclick',Actualizar);
}
				
})
}
Ext.get('BtnGrabar').on('click', function()
{
if(Actualizar!=true)
{
	evento ='incluir';
	Mensa = "Incluido";
}
else
{
	evento ='actualizar';
	Mensa = "Modificado";
}
	
if(ValidarRegistroGrid())
{
			var myJSONObject ={
				"oper": evento, 
				"codemp":'0001', 
				"cuentacontable":Ext.getCmp('codcontable').getValue(),
				"cuentadebe":Ext.getCmp('codvar1').getValue(),
				"cuentahaber":Ext.getCmp('codvar2').getValue()
			};
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
    Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultad, request) { 
                 datos = resultad.responseText;
			//	alert(datos);
                 var Registros = datos.split("|");
                 if (Registros[1] == '1')
                 {
					Ext.MessageBox.alert('Mensaje','Registro '+Mensa + ' con éxito');
				//	gridConversion.store.commitChanges();
					LimpiarCampos();
					ActualizarDataConversion();
                 }
                 else
                 {
                 	Ext.MessageBox.alert('Error', 'El Registro no pudo ser '+Mensa); 
                 }
	},
	failure: function ( result, request) 
	{ 
		Ext.MessageBox.alert('Error', 'El Registro no pudo ser '+Mensa); 
	}

      });
}	
});
 

Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank.php';
})


function Actualizar()
{	
	 codigoContable=gridConversion.getSelectionModel().getSelected().get('cuentacontable');						  
	 denominacion=gridConversion.getSelectionModel().getSelected().get('dencontable');
	 codigoDebe=gridConversion.getSelectionModel().getSelected().get('cuentadebe');
	 denDebe=gridConversion.getSelectionModel().getSelected().get('dencuentadebe');
	 codigoHaber=gridConversion.getSelectionModel().getSelected().get('cuentahaber');
	 denHaber=gridConversion.getSelectionModel().getSelected().get('dencuentahaber');
	 AuxCuenta=codigoContable+' '+denominacion;
	 AuxCuenta1=codigoDebe+' '+denDebe;
	 AuxCuenta2=codigoHaber+' '+denHaber;
	 Ext.get('contable').dom.value=AuxCuenta;
	 Ext.get('codcontable').dom.value=codigoContable;
	 Ext.get('var1').dom.value=AuxCuenta1;
	 Ext.get('codvar1').dom.value=codigoDebe;
	 Ext.get('var2').dom.value=AuxCuenta2;
	 Ext.get('codvar2').dom.value=codigoHaber;
	//HabilitarObjetos(true);
	Actualizar=true;	
}

function ValidarRegistroGrid()
{
	
	if(Ext.getCmp('contable').getValue()=='')
	{
		Ext.Msg.alert('Mensaje','Debe incluir una cuenta contable');
		return false;
	}
	else if(Ext.getCmp('var1').getValue()=='')
	{
		Ext.Msg.alert('Mensaje','Debe incluir una cuenta de Variación patrimonial por el debe');
		return false;
		
	}
	else if(Ext.getCmp('var1').getValue()=='')
	{
		Ext.Msg.alert('Mensaje','Debe incluir una cuenta de Variación patrimonial por el haber');
		return false;
	}
	else
	{
		return true;
	}
}

function ActualizarDataConversion()
{

	var myJSONObject ={
				"oper": 'catalogo'
	};	
		
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado, request)
	 { 
		  datos = resultado.responseText;
		//  alert(datos);
		 if(datos!='')
		 {
			var DatosNuevo = eval('(' + datos + ')');
			if(DatosNuevo.raiz==null)
			{
				var DatosNuevo={"raiz":[{"codcontable":'',"cuentadebe":'',"cuentahaber":'',"dencuentadebe":'',"dencontable":'',"dencuentahaber":''}]};
				
			}	
		 }
		gridConversion.store.loadData(DatosNuevo);
			
	}
});
	
}

function ObtenerGrid(tab)
{
	switch(tab)
	{
		case "0":
			return grid;
			break;
		case "1":
			return grid2;
			break;
		case "2":
			return grid3;
			break;
		case "3":
			return grid4;
			break;
	}    
	
}

function ObtenerCodigo(tab)
{
	switch(tab)
	{
		case "0":
			return 'spi_cuenta';
			break;
		case "1":
			return 'sc_cuenta';
			break;
		case "2":
			return 'sig_cuenta';
			break;
		case "3":
			return 'spg_cuenta';
			break;
	}    
	
}


Ext.get('BtnNuevo').on('click', function()
{				
	Oper="incluyendo";	
	//HabilitarObjetos(true);
	
}	
);


function LimpiarCampos()
{
	Ext.getCmp('contable').setValue('');
	Ext.getCmp('var1').setValue('');
	Ext.getCmp('var2').setValue('');	
}

Ext.get('BtnElim').on('click',function()
{
	var Result;
	Ext.MessageBox.confirm('Confirmar', '¿Desea eliminar este registro?', Result);
	function Result(btn)
	{
		RegistroActual = gridConversion.getSelectionModel().getSelected();
		if(btn=='yes')
		{
			var myJSONObject ={
					"oper":'eliminar', 
					"codemp":'0001', 
					"cuentacontable":RegistroActual.get('cuentacontable'),
					"cuentadebe":RegistroActual.get('cuentadebe'),
					"cuentahaber":RegistroActual.get('cuentahaber')
			};
			ObjSon=JSON.stringify(myJSONObject);
			parametros = 'ObjSon='+ObjSon; 
			Mensa = "Eliminado";
			Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function ( resultad, request ) { 
				 datos = resultad.responseText;
				//	alert(datos);
					
				 var Registros = datos.split("|");
				 if (Registros[1] == '1')
				 {
					Ext.MessageBox.alert('Mensaje','Registro '+Mensa + ' con éxito');
					LimpiarCampos();
					ActualizarDataConversion();
						
				 }
				 else
				 {
				  Ext.MessageBox.alert('Error', Registros[0]);
				 }
			},
			failure: function ( result, request) { 
				Ext.MessageBox.alert('Error', result.responseText); 
			} 
		});

		}
	
	};
	
});
 
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


function GetForm()
{
	if(FormularioBus=="")
	{
		FormularioBus = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
        //url:'save-form.php',
        frame:true,
    //    style:'position:absolute;left:120px;top:35px',
        width: 350,
		height:90,
        defaults: {width: 230},
        defaultType: 'textfield',
		items: [{
                fieldLabel: 'Código',
                name: 'cod',
				id:'cod',
				changeCheck: function()
				{
					var v = this.getValue();
					GridA = ObtenerGrid(GridActual);
					criterio=GridA.getId();
					ActualizarDataCat(criterio,v);
					if(String(v) !== String(this.startValue))
					{
						this.fireEvent('change', this, v, this.startValue);
					} 
				}
				, 
				initEvents:function()
				{
					AgregarKeyPress(this);
				}               
            }
			,
			{
                fieldLabel: 'Denominacion',
                name: 'den',
                id:'den',
				changeCheck: function(){
							  var v = this.getValue();
							 ActualizarDataCat('denominacion',v);
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
	else
	{
		Ext.getCmp('cod').setValue('');
		Ext.getCmp('den').setValue('');
	}
		
}


function getForma()
{
	FormularioPrin = new Ext.FormPanel
	({
        labelWidth:165, // label settings here cascade unless overridden
        frame:true,
        applyTo:'form',
        style:'position:absolute;left:120px;top:35px',
        width: 750,
		height:100,
        defaults: {width: 230},
        defaultType: 'textfield',
		items:[
			{
				xtype:'textfield',
				readOnly:true,
				fieldLabel:'Cuenta Contable Origen',
				id:'contable',width:550
			},
			{
				xtype:'hidden',
				editable:true,
				id:'codcontable'
			},
			{
				xtype:'textfield',
				readOnly:true,
				fieldLabel:'Cuenta de Variacion Patrimonial Debe',
				id:'var1',
				width:550
			},
			{
				xtype:'hidden',
				editable:true,
				id:'codvar1'
			},  
			{
				xtype:'textfield',
				readOnly:true,
				fieldLabel:'Cuenta de Variacion Patrimonial Haber',
				id:'var2',width:550
			},
			{
				xtype:'hidden',
				editable:true,
				id:'codvar2'
			},
		]
	})	
	Ext.get('contable').on('dblclick', function()
	{
		obj = Ext.get('contable');
		obj2 = Ext.get('codcontable');
		getobject2(obj,obj2);
	})
	
	Ext.get('var1').on('dblclick', function()
	{
		obj = Ext.get('var1');
		obj2 = Ext.get('codvar1');
		getobject3(obj,obj2);
	})
	Ext.get('var2').on('dblclick', function()
	{
		obj = Ext.get('var2');
		obj2 = Ext.get('codvar2');
		getobject3(obj,obj2);
	})

	//Ext.get('contable').addListener('click',getobject2,this);
//	Ext.get('denser').addListener('click',getobject3);
}


function ActualizarDataCat(criterio,valor)
{
	
	GridAct = ObtenerGrid(GridActual);
	var myJSONObject =
	{
		"oper": 'buscarcadena', 
		"tipo":GridAct.getId(),
		"criterio":criterio,
		"cadena": valor
	};	
	
	ObjSon=JSON.stringify(myJSONObject);
	parametros = 'ObjSon='+ObjSon; 
	Ext.Ajax.request({
	url : ruta,
	params : parametros,
	method: 'POST',
	success: function (resultado, request) 
	{ 
		datos = resultado.responseText;	
	//	alert(datos);  
		 DatosNuevo = eval('(' + datos + ')');
		 if(DatosNuevo.raiz==null)
		 {
			
			var DatosNuevo={"raiz":[{"spi_cuenta":'',"denominacion":'',"montoGlobal":'',"NuevoRegistro":''}]};
	
		 }		
		GridAct.store.loadData(DatosNuevo);
		
	}
});
	
}

function HabilitarObjetos(estado)
{
	if(estado==true)
	{
		Ext.getCmp('contable').enable();
		Ext.getCmp('var1').enable();
		Ext.getCmp('var2').enable();
		Ext.getCmp('contable').focus();
	}
	else
	{
		Ext.getCmp('contable').disable();
		Ext.getCmp('var1').disable();
		Ext.getCmp('var2').disable();
	}
}


function ManejarTabActivo(tab)
{
	txtCod = FormularioBus.getComponent('cod');
	txtCod.reset();
	txtDen = FormularioBus.getComponent('den');
	txtDen.reset();
}

getForma();
getGridConversion();
//HabilitarObjetos(false);
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
                height:200,
                el:'centro'    
            }),
            new Ext.Panel({
                region:'south',
                layout:'table',
                title:'Integración de Cuentas',
                width: 710,
                autoScroll:true,
                bodyStyle:'background-color:#DFE8F6',
                height:300,
                style:'padding-bottom:20px',
                contentEl:'sur'    
            })

            ]
          })		
});
