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
var FormularioPrin="";
var GridActual="";
var gridConversion="";
 
ruta ='../../procesos/sigesp_spe_variacionpr.php';
ruta2 ='../../procesos/sigesp_spe_integraciongeneralpr.php';
pantalla = 'sigesp_spe_integraciongeneral.php';
Ext.onReady(function()
{
ObtenerSesion(ruta2,pantalla)
function getobject2(obj,obj2)
{
			GridActual='1';
			Oper[0]='incluyendo';
			var DatosNuevo={"raiz":[{"codigo":'',"denominacion":''}]};
			RecordDef = Ext.data.Record.create([
			{name:'codigo'},
			{name: 'denominacion'},
			{name: 'codvardebe'},
			{name: 'desvardebe'},
			{name: 'codvarhaber'},
			{name: 'desvarhaber'}
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
          
            	   GetForm();
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
		                    codigo=grid2.getSelectionModel().getSelected().get('codigo');						   
		                    denominacion=grid2.getSelectionModel().getSelected().get('denominacion');
		                    codvardebe = grid2.getSelectionModel().getSelected().get('codvardebe');
		                    desvardebe = grid2.getSelectionModel().getSelected().get('desvardebe');
		                    codvarhaber = grid2.getSelectionModel().getSelected().get('codvarhaber');
		                    desvarhaber = grid2.getSelectionModel().getSelected().get('desvarhaber');
		                    obj.dom.value=codigo;
		                    obj2.dom.value=denominacion;
		                    
		                    if(obj.dom.id=='codcontable1' && codvardebe && desvardebe)
		                    {
		                    	Ext.get('vardebe').dom.value=codvardebe+' '+desvardebe;
		                    }
		                    if(obj.dom.id=='codcontable2' && codvarhaber && desvarhaber)
		                    {
		                    	Ext.get('varhaber').dom.value=codvarhaber+' '+desvarhaber;
		                    }
		                    
		                   	win1.destroy();
				      		FormularioBus.destroy();
				      		FormularioBus="";
				      		grid2.destroy();
				      		grid2="";
				      		win1="";
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
                     		win1.destroy();
                      		FormularioBus.destroy();
				      		FormularioBus="";
				      		grid2.destroy();
				      		grid2="";
				      		win1="";
                     }
                    }]
                   });
                   //winOnOff = true;
                   //estaba alla donde dice aqui
                
         		win1.show();
}


Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank.php';
})


Ext.get('BtnBuscar').on('click',function()
{
			GridActual='1';
			Oper[0]='incluyendo';
			var DatosNuevo={"raiz":[{"codigo":'',"denominacion":''}]};
			RecordDefConv = Ext.data.Record.create
			([
				{name:'codcuenta'},
				{name:'dencuenta'},
				{name:'codigodebe'},
				{name:'dendebe'},	
				{name:'codigohaber'},
				{name:'denhaber'},
				{name:'codcaif'},
				{name:'codvarhaber'},	
				{name:'codvardebe'},
				{name:'denvarhaber'},
				{name:'denvardebe'},
				{name:'estatus'}
			]);
			
			DataStore =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			    root: 'raiz',                
			    id: "id"   
			    },
                    RecordDefConv
			    ),
				data: DatosNuevo
            });
		
			
				grid2 = new Ext.grid.EditorGridPanel({
				width:780,
				height:200,
				id:'sig_cuentaint',
				autoScroll:true,
	            border:true,
	            ds:DataStore,
	            cm: new Ext.grid.ColumnModel([
	            {header: "Código", width: 50, sortable: true, dataIndex: 'codcuenta'},
	            {header: "Denominación", width:250, sortable: true, dataIndex: 'dencuenta',editor: new Ext.form.TextField({allowBlank: false})}
	                ]),
				selModel: new Ext.grid.RowSelectionModel({singleSelect:false}),
	                       viewConfig:{
	                       forceFit:true
	                        },
				autoHeight:true,
				stripeRows: true
	            });
            	   
            	   GetForm2();
            	 //  alert(FormularioBus);
                   winInt = new Ext.Window(
                   {
                    //layout:'fit',
                    title: 'Cat&aacute;logo de Plan de Cuentas General Integrado',
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
		                    codigo=grid2.getSelectionModel().getSelected().get('codcuenta');						   
		                    denominacion=grid2.getSelectionModel().getSelected().get('dencuenta');
		                    codcontable1 = grid2.getSelectionModel().getSelected().get('codigodebe');
		                    dendebe1 = grid2.getSelectionModel().getSelected().get('dendebe');
		                    codcontable2 = grid2.getSelectionModel().getSelected().get('codigohaber');
		                    denhaber = grid2.getSelectionModel().getSelected().get('denhaber');
		                    vardebe = grid2.getSelectionModel().getSelected().get('codvardebe')+' '+grid2.getSelectionModel().getSelected().get('denvardebe');
		                    varhaber = grid2.getSelectionModel().getSelected().get('codvarhaber')+' '+grid2.getSelectionModel().getSelected().get('denvarhaber');
		                    codcaif = grid2.getSelectionModel().getSelected().get('codcaif');
		                    mov = grid2.getSelectionModel().getSelected().get('estatus');
		                       
		                    if(mov!='')
		                    {
		                    	Ext.getCmp('mov').setValue(true);
		                    }		                    
		                    Ext.get('cuentapre').dom.value = codigo; 
		                    Ext.get('denpre').dom.value = denominacion;
		                    Ext.get('codcontable1').dom.value = codcontable1;
		                    Ext.get('dencontable1').dom.value = dendebe1;
		                    Ext.get('codcontable2').dom.value = codcontable2;
		                    Ext.get('dencontable2').dom.value = denhaber;
		                    Ext.get('vardebe').dom.value = vardebe;
		                    Ext.get('varhaber').dom.value = varhaber; 
		                    Ext.get('caif').dom.value = codcaif;
		                  
		                    Actualizar=true;	
		                   	winInt.destroy();
				      		FormularioBus.destroy();
				      		FormularioBus="";
				      		grid2.destroy();
				      		grid2="";
				      		win1="";
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
                     		winInt.destroy();
                      		FormularioBus.destroy();
				      		FormularioBus="";
				      		grid2.destroy();
				      		grid2="";
				      		win1="";
                     }
                    }]
                   });               
         		winInt.show();
})



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
			     )
			     ,
				data: DatosNuevo
            });
			
		//	if(grid3=='')
		//	{
				grid3 = new Ext.grid.EditorGridPanel({
				width:780,
				height:200,
				id:'sig_cuenta',
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
		                    codigo=grid3.getSelectionModel().getSelected().get('codigo');						   
		                    denominacion=grid3.getSelectionModel().getSelected().get('denominacion');
		                    AuxCuenta=codigo+' '+denominacion;
		                    AuxTipoCuenta = codigo.substr(0,1);
		                    if(AuxTipoCuenta=='3')
		                    {
		                    	Ext.get('codcontable2').dom.value=codigo;
		                    	Ext.get('dencontable2').dom.value=denominacion;
		                    }
		                    else if(AuxTipoCuenta=='4')
		          			{
		          				Ext.get('codcontable1').dom.value=codigo;
		                    	Ext.get('dencontable1').dom.value=denominacion;			
		          			}
		          			
		            		obj.dom.value=codigo;
		                 	obj2.dom.value=denominacion;
					      	win2.destroy();
					      	FormularioBus.destroy();
					      	FormularioBus="";
				      		grid3.destroy();
					      	grid3="";
				      		win2="";
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
                     	
				      	win2.destroy();
				      	FormularioBus.destroy();
				      	FormularioBus="";
			      		grid3.destroy();
				      	grid3="";
			      		win2="";
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
	var FormBusqueda = new Ext.FormPanel
	({
        labelWidth: 75, // label settings here cascade unless overridden
        frame:true,
        bodyStyle:'padding:5px 5px 0',
        width: 350,
		height:55,
        defaults: {width: 230},
        items:[
        {
        	xtype:'textfield',
        	fieldLabel:'codigo',
			name:'txtcod',
			readOnly:false,
			id: 'txtcod',
			maxLength: 80,
			maxLengthText:'El campo excede la longitud máxima',
			width: 170	,
			enableKeyEvents:true,
			listeners:{
		    'keypress':function(Obj,e){
		    	var whichCode = e.keyCode; 
			    	if (whichCode == 13)  
					{		 
						ActualizarDataCat('sig_cuenta',this.getValue());	
					}
		    	}
			}
		}
		,
		{
		 	 xtype:'textfield',
			 fieldLabel:'denominacion',
			 name:'txtden',
			 readOnly:false,
			enableKeyEvents:true,
			listeners:{
		    'keypress':function(Obj,e){
		    	var whichCode = e.keyCode; 
			    	if (whichCode == 13)  
					{		 
						   Ext.MessageBox.show({
				           msg: 'Por Favor Espere',
				           title: 'Buscando Datos',
				           progressText: 'Buscando Datos',
				           width:300,
				           wait:true,
				           waitConfig:{interval:100},
				           animEl: 'mb7'
				      });
						ActualizarDataCat('denominacion',this.getValue());
						
					}
		    	}
			
			}			 ,
			 id: 'txtden',
			 maxLength: 80,
			 maxLengthText:'El campo excede la longitud máxima',
			 width: 170	
		}
		]     
     })

		Columna=
		[
			['Debe'],
			['Haber']
		]	
	var storeTipo = new Ext.data.SimpleStore
	(
		{
	        fields: ['col'],
	        data : Columna // from states.js
	    }
	);
    
	
	
		var DatosNuevo={"raiz":[{"codgi":'',"codcod":'',"dencod":'',"codcoh":'',"dencoh":'',"codvp":'',"denvp":'',"colvp":'',"codcai":'',"dencai":''}]};
		RecordDefConv = Ext.data.Record.create
		([
			{name:'codcuenta'},
			{name:'dencuenta'},
			{name:'codigodebe'},
			{name:'dendebe'},	
			{name:'codigohaber'},
			{name:'denhaber'},
			{name:'codcaif'},
			{name:'codvarhaber'},	
			{name:'codvardebe'},
			{name:'denvarhaber'},
			{name:'denvardebe'}
		]);
			
			DataStoreConversion =  new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(DatosNuevo),
			reader: new Ext.data.JsonReader({
			root: 'raiz',                // The property which contains an Array of row objects
			    id: "id"   
			    }
			    ,
                    RecordDefConv
			    )
			    ,
				data: DatosNuevo
            });

			gridConversion = new Ext.grid.EditorGridPanel({
			width:2500,
			height:300,
			id:'gridconversion',
			renderTo:'ContenedorGridCoversion',
			autoScroll:true,
			tbar:[FormBusqueda],
            border:true,
            ds:DataStoreConversion,
            cm: new Ext.grid.ColumnModel([
            // new Ext.grid.RowNumberer(),
            new Ext.grid.CheckboxSelectionModel(),
            {header: "Cuenta de Recurso/Gastos", width: 80, sortable: true, dataIndex: 'codcuenta'},
                            
			  {header: "Denominación", width:150, sortable: true, dataIndex: 'dencuenta'},
			  {header: "Cuenta Contable(Debe)", width: 70, sortable: true, dataIndex: 'codigodebe'},
                            
			  {header: "Denominación", width: 120, sortable: true, dataIndex: 'dendebe'}
,{header: "Cuenta Contable(Haber)", width:70, sortable: true, dataIndex: 'codigohaber'},
                            
			  {header: "Denominación", width: 120, sortable: true, dataIndex: 'denhaber'}
,{header: "Cuenta de CAIF Deudora(debe)", width: 110, sortable: true, dataIndex: 'codvardebe'},

{header: "Denominación", width: 150, sortable: true, dataIndex: 'denvardebe'}
,{header: "Cuenta de CAIF Acreedora(haber)", width: 110, sortable: true, dataIndex: 'codvarhaber'}
])
,
sm:new Ext.grid.CheckboxSelectionModel({singleSelect:false}),
                        viewConfig: {
                        forceFit:true
                        },
			autoHeight:true,
			stripeRows: true
     });   
		

	/*
		Ext.MessageBox.show({
           msg: 'Por Favor Espere',
           title: 'Cargando Datos',
          // progressText: 'Cargando Datos',
           width:300,
           wait:true,
           waitConfig:{interval:100}
          // animEl: 'mb7'
       });
		
		*/
		
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
			if(Ext.getCmp('mov').getValue()==true)
			{
				Movimiento ="C"; 
			}
			else
			{
				Movimiento ="";
			}
			
			if(Ext.getCmp('codcontable1').getValue()=="" && Ext.getCmp('codcontable2').getValue()=="" )
			{
				//validar
				return false;
			}
			else
			{
				presupuestaria = Ext.get('cuentapre').dom.value
				contable1 = Ext.get('codcontable1').dom.value
				contable2 = Ext.get('codcontable2').dom.value
				caif = Ext.get('caif').dom.value
				denominacion = Ext.get('denpre').dom.value
			}
			
			var myJSONObject ={
				"oper": evento,
				"sig_cuenta":presupuestaria,
				"codcaif":caif,
				"sc_cuenta":contable1,
				"sc_cuenta_haber":contable2,
				"denominacion":denominacion,
				"estatus":Movimiento
			};
			
	ObjSon=JSON.stringify(myJSONObject);
	//return false;
	parametros = 'ObjSon='+ObjSon; 
    Ext.Ajax.request({
	url : ruta2,
	params : parametros,
	method: 'POST',
	success: function (resultad, request) { 
                 datos = resultad.responseText;
				//alert(datos);
                 var Registros = datos.split("|");
                 if (Registros[1] == '1')
                 {
					Ext.MessageBox.alert('Mensaje','Registro '+Mensa + ' con éxito');
				//	gridConversion.store.commitChanges();
					LimpiarCampos();
					ActualizarDataConversion();
                 }
                 else if(Registros[1] == '-5' || Registros[1]=='-1')
                 {
                 	Ext.MessageBox.alert('Error', 'El Registro no pudo ser '+Mensa+ ', la cuenta con codigo '+ Ext.getCmp('cuentapre').getValue()+' ya esta registrada en el plan general de cuentas integrado' ); 
                 }
	},
	failure: function ( result, request) 
	{ 
		Ext.MessageBox.alert('Error', 'El Registro no pudo ser '+Mensa+', error de conexion'); 
	}

      });
}	
});
 


function Actualizar()
{	
	HabilitarObjetos(true);
	Actualizar=true;	
}

function ValidarRegistroGrid()
{
	if(Ext.get('cuentapre').dom.value=='')
	{
		Ext.Msg.alert('Mensaje','Debe incluir una cuenta origen');
		return false;
	}
	else if(Ext.get('codcontable1').dom.value=='')
	{
		Ext.Msg.alert('Mensaje','Debe incluir una cuenta contable debe');
		return false;
	}else if(Ext.get('codcontable2').dom.value=='')
	{
		Ext.Msg.alert('Mensaje','Debe incluir una cuenta contable haber');
		return false;
		
	} else if(Ext.get('caif').dom.value=='')
	{
		Ext.Msg.alert('Mensaje','Debe incluir una cuenta Caif');
		return false;
		
	}
	return true;
}

function getobjectCaif(obj)
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
                   wincaif = new Ext.Window(
                   {
                    //layout:'fit',
                    title: 'Cat&aacute;logo Cuenta Ahorro Inversión Financiamiento (CAIF)',
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
		                    codigo=grid3.getSelectionModel().getSelected().get('codigo');						   
		                    denominacion=grid3.getSelectionModel().getSelected().get('denominacion');
		                    
		                    obj.dom.value=codigo;
		                    Cuenta = Ext.get('cuentapre').dom.value;
		                    TipoCuenta = Ext.get('cuentapre').dom.value.substr(0,1)
		                    if(TipoCuenta=='3' && Cuenta==Ext.get('codcontable2').dom.value)
		                    {
		                    	Ext.get('varhaber').dom.value = codigo+' '+denominacion;
		                    }
		                    
		                    if(TipoCuenta=='4' && Cuenta==Ext.get('codcontable1').dom.value)
		                    {
		                    	Ext.get('vardebe').dom.value = codigo+' '+denominacion;
		                    }
		                    
		          			wincaif.destroy();
				      		FormularioBus.destroy();
					      	FormularioBus="";
				      		grid3.destroy();
					      	grid3="";
				      		
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
                      wincaif.hide();
                     }
                    }]
                   });
                   //winOnOff = true;
                   //estaba alla donde dice aqui
            //}
         	wincaif.show();
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
		 }
		else
		{
			var DatosNuevo={"raiz":[{"codgi":'',"codco1":'',"denco1":'',"codco2":'',"denco2":'',"codvp":'',"denvp":'',"colvp":'',"codcai":'',"dencai":''}]};
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
	HabilitarObjetos(true);	
}	
);




Ext.get('BtnImp').on('click', function()
{				

			Ext.MessageBox.show({
	          msg: 'Por Favor Espere',
	          title: 'Procesando Datos',
	          // progressText: 'Cargando Datos',
	          width:300,
	          wait:true,
	          waitConfig:{interval:500}
	          // animEl: 'mb7'
	        });
			   var myJSONObject ={
					"oper":'reporte'
				};
				ObjSon=JSON.stringify(myJSONObject);
				parametros = 'ObjSon='+ObjSon; 
				Mensa = "Eliminado";
				Ext.Ajax.request({
				url : ruta2,
				params : parametros,
				method: 'POST',
				success: function ( resultad, request ) { 
					 datos = resultad.responseText;
				//		alert(datos);
						if(datos!='')
						{
							Abrir_ventana(datos);
							Ext.MessageBox.hide();
						}		
				},
				failure: function ( result, request) { 
					Ext.MessageBox.alert('Error', result.responseText); 
				} 
			});
}	
);









function LimpiarCampos()
{
	FormularioPrin.getComponent('cuentapre').setValue('');
	FormularioPrin.getComponent('denpre').setValue('');
	FormularioPrin.getComponent('codcontable1').setValue('');
	FormularioPrin.getComponent('dencontable1').setValue('');
	FormularioPrin.getComponent('codcontable2').setValue('');
	FormularioPrin.getComponent('dencontable2').setValue('');
	FormularioPrin.getComponent('vardebe').setValue('');
	FormularioPrin.getComponent('varhaber').setValue('');
	FormularioPrin.getComponent('caif').setValue('');
	FormularioPrin.getComponent('mov').setValue('');
}

Ext.get('BtnElim').on('click',function()
{
	var cuenta  = Ext.get('cuentapre').dom.value;
	var Result;
	
		if(cuenta!='')
		{
			Ext.MessageBox.confirm('Confirmar', '¿Desea eliminar este registro?', Result);
		}
		function Result(btn)
		{
			
			if(btn=='yes')
			{
				var myJSONObject ={
						"oper":'eliminar', 
						"codemp":'0001', 
						"sig_cuenta":cuenta
				};
				ObjSon=JSON.stringify(myJSONObject);
				parametros = 'ObjSon='+ObjSon; 
				Mensa = "Eliminado";
				Ext.Ajax.request({
				url : ruta2,
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
				 ,        
 
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
							}
							 ,
							initEvents : function()
							{
								AgregarKeyPress(this);
							}
							,
										enableKeyEvents:true			 		
            }]
		});	
}



function GetForm2()
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
			enableKeyEvents:true,
			listeners:{
		    'keypress':function(Obj,e){
		    	var whichCode = e.keyCode; 
			    	if (whichCode == 13)  
					{		 
						   Ext.MessageBox.show({
				           msg: 'Por Favor Espere',
				           title: 'Buscando Datos',
				           progressText: 'Buscando Datos',
				           width:300,
				           wait:true,
				           waitConfig:{interval:150},	
				           animEl: 'mb7'
				      });
						ActualizarDataCat('sig_cuentaint',this.getValue());
					}
		    	}
			}			 
           }
			,
			{
                fieldLabel: 'Denominacion',
                name: 'den',
                id:'den',
			listeners:{
		    'keypress':function(Obj,e){
		    	var whichCode = e.keyCode; 
			    	if (whichCode == 13)  
					{		 
						   Ext.MessageBox.show({
				           msg: 'Por Favor Espere',
				           title: 'Buscando Datos',
				           progressText: 'Buscando Datos',
				           width:300,
				           wait:true,
				           waitConfig:{interval:150},
				           animEl: 'mb7'
				      });
						ActualizarDataCat('denominacion',this.getValue());	
					}
		    	}
			}			 		
            }]
		});	
}



function getForma()
{
	FormularioPrin = new Ext.FormPanel
	({
        labelWidth:250, // label settings here cascade unless overridden
        frame:true,
        applyTo:'form',
        
        style:'position:absolute;left:120px;top:35px',
        width: 790,
		height:355,
        defaults: {width: 230},
        defaultType: 'textfield',
		items:[
			{
				xtype:'textfield',
				editable:false,
				fieldLabel:'Codigo de la Cuenta Presupuestaria Origen',
				id:'cuentapre',
				width:100
			}
			,
			{
				xtype:'textfield',
				editable:true,
				id:'denpre',
				fieldLabel:'Denominacion',
				width:500
			}
			,
			{
				xtype:'textfield',
				editable:true,
				fieldLabel:'Cuenta contable que se afecta(Debe)',
				id:'codcontable1',
				width:100
			}
			,
			{
				xtype:'textfield',
				editable:true,
				fieldLabel:'Denominacion',
				id:'dencontable1',
				width:500
			}
			,  
			{
				xtype:'textfield',
				editable:true,
				fieldLabel:'Cuenta contable que se afecta(Haber)',
				id:'codcontable2',
				width:100
			}
			,
			{
				xtype:'textfield',
				editable:true,
				fieldLabel:'Denominacion',
				id:'dencontable2',
				width:500
			}
			,
			{
				xtype:'textfield',
				editable:true,
				fieldLabel:'Codigo Caif Asociado',
				id:'caif',
				width:100
			}
			,
			{
				xtype:'textfield',
				editable:true,
				fieldLabel:'Cuenta CAIF Deudora(Debe)',
				id:'vardebe',
				width:500
			}
			,
			{
				xtype:'textfield',
				editable:true,
				fieldLabel:'Cuenta CAIF Acreedora(Haber)',
				id:'varhaber',
				width:500
			}
			,
			{
				xtype:'checkbox',
				fieldLabel:'Cuenta de movimiento',
				id:'mov'
			}
		]
	})	

	Ext.get('codcontable1').on('dblclick', function()
	{
		obj = Ext.get('codcontable1');
		obj2 = Ext.get('dencontable1');
		getobject2(obj,obj2);
	})	
	Ext.get('codcontable2').on('dblclick', function()
	{
		obj = Ext.get('codcontable2');
		obj2 = Ext.get('dencontable2');
		getobject2(obj,obj2);
	})
	Ext.get('cuentapre').on('dblclick', function()
	{
		obj = Ext.get('cuentapre');
		obj2 = Ext.get('denpre');
		getobject3(obj,obj2);
	})
	Ext.get('caif').on('dblclick', function()
	{
		obj = Ext.get('caif');
		getobjectCaif(obj);
	})

}




function ActualizarDataCat(criterio,valor)
{
	//alert(criterio);
	//alert(valor);
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
	url : ruta2,
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
		Ext.MessageBox.hide();
		
	}
});
	
}

function HabilitarObjetos(estado)
{
/*
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
	*/
}


function ManejarTabActivo(tab)
{
	txtCod = FormularioBus.getComponent('cod');
	txtCod.reset();
	txtDen = FormularioBus.getComponent('den');
	txtDen.reset();
}

getForma();
//getGridConversion();
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
                height:450,
                el:'centro'    
            })
          /*  ,
            new Ext.Panel({
                region:'south',
                layout:'table',
                title:'Catálogo de Cuentas Integradas',
                width: 710,
                autoScroll:true,
                bodyStyle:'background-color:#DFE8F6',
                height:210,
                style:'padding-bottom:20px',
                contentEl:'sur'    
            })
*/
          ]
          })		
});
