/***********************************************************************************
* @Proceso para traspasar los datos Básicos de una Base de Datos a Otra
* @parametros: 
* @retorno:
* @fecha de creación: 20/10/2008
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/

var panel = '';
var actualizar = false;
ruta =  '../../controlador/apr/sigesp_ctr_apr_datos_basicos.php'; 
var sistemas = new Array();
var sno;
var nominapanel;
var dataNomina;
var gridNominas;
var aperturado = new Array();
sistemas[0]='sss';
sistemas[1]='rpc';
sistemas[2]='scg';
sistemas[3]='spg';
sistemas[4]='spi';
sistemas[5]='saf';
sistemas[6]='cxp';
sistemas[7]='siv';
sistemas[8]='sep';
sistemas[9]='soc';
sistemas[10]='scb';
sistemas[11]='scv';
sistemas[12]='sob';
sistemas[13]='sno';
sistemas[14]='srh';
sistemas[15]='sps';
sistemas[16]='his';
sistemas[17]='mov';
aperturado['sss']=0;
aperturado['rpc']=0;
aperturado['scg']=0;
aperturado['spg']=0;
aperturado['spi']=0;
aperturado['saf']=0;
aperturado['cxp']=0;
aperturado['siv']=0;
aperturado['sep']=0;
aperturado['soc']=0;
aperturado['scb']=0;
aperturado['scv']=0;
aperturado['sob']=0;
aperturado['sno']=0;
aperturado['srh']=0;
aperturado['sps']=0;
aperturado['his']=0;
aperturado['mov']=0;

Ext.onReady
(
	function()
	{
		Ext.QuickTips.init();
		Ext.Ajax.timeout=0;

		// turn on validation errors beside the field globally
		Ext.form.Field.prototype.msgTarget = 'side';

		// Módulo de seguridad
		var sss = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Datos Básicos',
			labelStyle: 'width:250px',
			name:'seguridad',
			id:'chbsss'		
		});
		// Módulo de Proveedores y Beneficiarios
		var rpc = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Proveedores y Beneficiarios',
			labelStyle: 'width:250px',
			name:'proveedores',
			id:'chbrpc'		
		});		
		// Contabilidad General
		var scg = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Contabilidad General',
			labelStyle: 'width:250px',
			name:'contabilidad',
			id:'chbscg'		
		});
		// Presupuesto de Gasto
		var spg = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Presupuesto de Gasto',
			labelStyle: 'width:250px',
			name:'gasto',
			id:'chbspg'		
		});
		// Presupuesto de Ingreso
		var spi = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Presupuesto de Ingreso',
			labelStyle: 'width:250px',
			name:'ingreso',
			id:'chbspi'		
		});
		// Activos Fijos
		var saf = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Activos Fijos',
			labelStyle: 'width:250px',
			name:'activos',
			id:'chbsaf'		
		});
		// Cuentas por Pagar
		var cxp = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Cuentas por Pagar',
			labelStyle: 'width:250px',
			name:'cuentasporpagar',
			id:'chbcxp'		
		});		
		// Inventario
		var siv = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Inventario',
			labelStyle: 'width:250px',
			name:'inventario',
			id:'chbsiv'		
		});		
		// Solicitud de Ejecución Presupuestaria
		var sep = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Solicitud de Ejecución Presupuestaria',
			labelStyle: 'width:250px',
			name:'solicitud',
			id:'chbsep'		
		});
		// Compras
		var soc = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Compras',
			labelStyle: 'width:250px',
			name:'compras',
			id:'chbsoc'		
		});
		// Bancos
		var scb = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Bancos',
			labelStyle: 'width:250px',
			name:'banco',
			id:'chbscb'		
		});
		// Control de Viaticos
		var scv = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Control de Viaticos',
			labelStyle: 'width:250px',
			name:'viaticos',
			id:'chbscv'		
		});
		// Obras
		var sob = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Obras',
			labelStyle: 'width:250px',
			name:'obras',
			id:'chbsob'		
		});		
		// Recursos Humanos
		var srh = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Recursos Humanos',
			labelStyle: 'width:250px',
			name:'recursoshumanos',
			id:'chbsrh'		
		});		
		// Prestaciones Sociales
		var sps = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Prestaciones Sociales',
			labelStyle: 'width:250px',
			name:'prestaciones',
			id:'chbsps'		
		});
		// Nómina
		sno = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Nómina',
			labelStyle: 'width:250px',
			name:'nomina',
			id:'chbsno'		
		});		
		sno.addListener('check',activarGrid);
		his = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'historicos de Nómina',
			labelStyle: 'width:250px',
			name:'historico',
			id:'chbhis'		
		});		
		mov = new Ext.form.Checkbox(
		{
			xtype:'checkbox',
			fieldLabel:'Traspaso de Movimientos',
			labelStyle: 'width:250px',
			name:'movimientos',
			id:'chbmov'		
		});		


		ObjNominas={'raiz':[{'codemp':'','codnom':'','desnom':'','codnuenom':'','transferir':'0'}]};

					// Acción de Eliminar
					var eliminar = new Ext.Action(
					{
						text: 'Eliminar',
						handler: irEliminar,
						iconCls: 'bmenueliminar',
						tooltip: 'Eliminar un Registro'
					});
					var procesar = new Ext.Action(
					{
						text: 'Procesar',
						handler: irProcesar,
						iconCls: 'bmenuprocesar',
						tooltip: 'Procesar'
					});
					var descargar = new Ext.Action(
					{
						text: 'Descargar',
						handler: irDescargar,
						iconCls: 'bmenudescargar',
						tooltip: 'Descargar Archivos Generados'
					});

		//componentes del formulario
		Xpos = ((screen.width/2)-(650/2)); 
		Ypos = ((screen.height/2)-(790/2));
		panel = new Ext.FormPanel({
			title: 'Trasferir Datos Básicos',
			bodyStyle:'padding:5px 5px 0px',
			width:600,
			style:'position:absolute;top:'+Ypos+'px;left:'+Xpos+'px',
			tbar: [procesar,eliminar,descargar],
			items:[{
				xtype:'fieldset',
				title:'',
				id:'fsformtransferir',
				autoHeight:true,
				autoWidth:true,
				cls :'fondo',		
				items:[sss,rpc,scg,spg,spi,saf,cxp,siv,sep,soc,scb,scv,sob,sno,srh,sps,his,mov]
			}]
		});		
	panel.render(document.body);
	verificarApertura();
	obtenerDatosNominas();
})


/***********************************************************************************
* @Función para llenar el combo de las empresas.     
* @parametros: 
* @retorno:
* @fecha de creación: 01/08/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function obtenerDatosNominas()
	{		
		var objdata ={
			'operacion': 'obtenerDatosNomina',
			'codsis': 'APR',
			'sistema': sistema,
			'vista': vista
			};
		
		objdata=JSON.stringify(objdata);
		
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function (resultado,request)
			{
				datos = resultado.responseText;
				var objresultado = eval('(' + datos + ')');
				if (objresultado.raiz[0].valido==true)
				{
					ObjNominas = objresultado;
				}
				else
				{
					Ext.MessageBox.alert('Error', objresultado.raiz[0].mensaje); 
				}
			},
			failure: function ( resultado, request)
			{ 
				Ext.MessageBox.alert('Error', resultado.responseText); 
			}
		});	
	}
	

/***********************************************************************************
* @Función para limpiar todos los campos del formulario  
* @parametros: 
* @retorno:
* @fecha de creación: 20/10/2008
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irCancelar()
	{
		total = sistemas.length;
		valido = true;
		for (contador = 0; ((contador < total) && valido); contador++)
		{
			codsis=sistemas[contador];
			if(aperturado[codsis]==0)
			{
				eval("Ext.getCmp('chb"+codsis+"').setValue('0')");
			}
		}
	}


/***********************************************************************************
* @Función para verificar a que módulos se les realizó la apertura.
* @parametros: 
* @retorno:
* @fecha de creación: 20/10/2008
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function verificarApertura()
	{
		var objdata ={
			'operacion': 'verificarapertura',
			'codsis': 'APR',
			'sistema': sistema,
			'vista': vista
			};
		
		objdata=JSON.stringify(objdata);
		
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function (resultado,request)
			{
				datos = resultado.responseText;
				var objresultado = eval('(' + datos + ')');
				if (objresultado.raiz[0].valido==true)
				{
					total = objresultado.raiz.length;
					for (cont=0; cont<total; cont++) 
					{
						if(objresultado.raiz[cont].codsis!='')
						{
							codsis = objresultado.raiz[cont].codsis;
							codsis = codsis.toLowerCase();
							eval("Ext.getCmp('chb"+codsis+"').setValue('1')");
							eval("Ext.getCmp('chb"+codsis+"').disable()");
							aperturado[codsis]='1';
						}
					}
				}
				else
				{
					Ext.MessageBox.alert('Error', objresultado.raiz[0].mensaje); 
				}
			},
			failure: function ( resultado, request)
			{ 
				Ext.MessageBox.alert('Error', resultado.responseText); 
			}
		});	
	}


/***********************************************************************************
* @Función para procesar la apertura.
* @parametros: 
* @retorno:
* @fecha de creación: 20/10/2008
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irProcesar()
	{
		total = sistemas.length;
		valido = true;
		for (contador = 0; ((contador < total) && valido); contador++)
		{
			codsis=sistemas[contador];
			if(aperturado[codsis]==0)
			{
				if(eval("Ext.getCmp('chb"+codsis+"').getValue()")=='1')
				{
					if (codsis == 'sno')
					{
						valido = procesarAperturaNomina(codsis);
					}
					else
					{
						valido = procesarApertura(codsis);
					}
				}
			}
		}
	}


/***********************************************************************************
* @Función para procesar la apertura.
* @parametros: 
* @retorno:
* @fecha de creación: 20/10/2008
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function procesarApertura(codsis)
	{
		panel.load({url:'', waitMsg:'Procesando...'});
		valido=false;
		var objdata ={
			'operacion': 'procesar',
			'codsis': codsis,
			'sistema': sistema,
			'vista': vista
			};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata; 
		Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function (resultado,request)
			{
				datos = resultado.responseText;
				var objresultado = eval('(' + datos + ')');
				if (objresultado.raiz.valido==true)
				{
					valido = true;
					eval("Ext.getCmp('chb"+codsis+"').setValue('1')");
					eval("Ext.getCmp('chb"+codsis+"').disable()");
					aperturado[codsis]='1';					
					Ext.MessageBox.alert('Mensaje', objresultado.raiz.mensaje+' Sistema '+codsis); 
				}
				else
				{
					Ext.MessageBox.alert('Error', objresultado.raiz.mensaje+' Sistema '+codsis); 
					
				}
				return valido;
			},
			failure: function ( resultado, request)
			{ 
				Ext.MessageBox.alert('Error', resultado.responseText); 
				return valido;
			}
		});	
	}	
	
	
/***********************************************************************************
* @Función para procesar la apertura del módulo de nómina ya que esta tiene unos parámetros distíntos.
* @parametros: 
* @retorno:
* @fecha de creación: 04/11/2008
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function procesarAperturaNomina(codsis)
	{
		panel.load({url:'', waitMsg:'Procesando...'});
		valido=true;
		if ((validarObjetos('txtfecinimen','10','novacio|fecha')!='0') && (validarObjetos('txtfecinisem','10','novacio|fecha')!='0'))   
		{
			fecha = new Date(Ext.get('txtfecinimen').getValue());
			fecinimen = fecha.format('Y-m-d');
			fecha = new Date(Ext.get('txtfecinisem').getValue());
			fecinisem = fecha.format('Y-m-d');
			var objdata ="{'operacion': 'procesarsno','codsis': codsis, 'sistema':sistema, 'vista': vista,"+
						 "'fecinimen': '"+fecinimen+"','fecinisem': '"+fecinisem+"'";
			arrNomina = gridNominas.store.getModifiedRecords();
			objdata = objdata+ ",datosNomina:[";
			total = arrNomina.length;
			if (total>0)
			{				
				for (i=0; i < total; i++)
				{
					if (arrNomina[i].get('transferir'))
					{
						if (i > 0)
						{
							objdata = objdata +",";
						}
						objdata = objdata +"{'codnom': '"+arrNomina[i].get('codnom')+"','codnuenom': '"+ arrNomina[i].get('codnuenom')+ "'}";
					}
				}				
			}
			objdata = objdata + "]}";
			objdata= eval('(' + objdata + ')');	
			objdata=JSON.stringify(objdata);
			parametros = 'objdata='+objdata; 
			Ext.Ajax.request({
				url : ruta,
				params : parametros,
				method: 'POST',
				success: function (resultado,request)
				{
					datos = resultado.responseText;
					var objresultado = eval('(' + datos + ')');
					if (objresultado.raiz.valido==true)
					{
						valido = true;
						eval("Ext.getCmp('chb"+codsis+"').setValue('1')");
						eval("Ext.getCmp('chb"+codsis+"').disable()");
						aperturado[codsis]='1';					
						Ext.MessageBox.alert('Mensaje', objresultado.raiz.mensaje+' Sistema '+codsis); 
					}
					else
					{
						Ext.MessageBox.alert('Error', objresultado.raiz.mensaje+' Sistema '+codsis); 
						
					}
					return valido;
				},
				failure: function ( resultado, request)
				{ 
					Ext.MessageBox.alert('Error', resultado.responseText); 
					return valido;
				}
			});
		}
	}
	
		
/***********************************************************************************
* @Función para eliminar la apertura del sistema seleccionado
* @parametros: 
* @retorno:
* @fecha de creación: 23/10/2008
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irEliminar()
	{
		total = (sistemas.length-1);
		valido = true;
		for (contador = total; ((contador >= 0) && valido); contador--)
		{
			codsis=sistemas[contador];
			if(aperturado[codsis]==1)
			{
				if(eval("Ext.getCmp('chb"+codsis+"').getValue()")=='1')
				{
					valido = procesarEliminar(codsis);
				}
			}
		}
	}
	

/***********************************************************************************
* @Función para Eliminar la apertura
* @parametros: 
* @retorno:
* @fecha de creación: 23/10/2008
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function procesarEliminar(codsis)
	{
		panel.load({url:'', waitMsg:'Procesando...'});
		valido=false;
		var objdata ={
			'operacion': 'eliminar',
			'codsis': codsis,
			'sistema': sistema,
			'vista': vista
			};
		
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
			url : ruta,
			params : parametros,
			method: 'POST',
			success: function (resultado,request)
			{
				datos = resultado.responseText;
				var objresultado = eval('(' + datos + ')');
				if (objresultado.raiz.valido==true)
				{
					valido = true;
					eval("Ext.getCmp('chb"+codsis+"').setValue('0')");
					eval("Ext.getCmp('chb"+codsis+"').enable()");
					aperturado[codsis]='0';										
					Ext.MessageBox.alert('Mensaje', objresultado.raiz.mensaje+' Sistema '+codsis); 
				}
				else
				{
					Ext.MessageBox.alert('Error', objresultado.raiz.mensaje+' Sistema '+codsis);
				}
				return valido;
			},
			failure: function ( resultado, request)
			{ 
				Ext.MessageBox.alert('Error', resultado.responseText); 
				return valido;
			}
		});	
	}	
	
/***********************************************************************************
* @Función para Descargar los archivos generados pór el módulo de apertura
* @parametros: 
* @retorno:
* @fecha de creación: 27/10/2008
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function irDescargar()
	{
		objCatDescarga = new catalogoDescarga();
		objCatDescarga.mostrarCatalogo();
	}	


/***********************************************************************************
* @Función que muestra el Grid de las Nóminas
* @parametros: 
* @retorno:
* @fecha de creación: 03/11/2008
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function activarGrid()
	{
		if (sno.checked)
		{
		    var checkColumn = new Ext.grid.CheckColumn({
		       header: 'Transferir',
		       dataIndex: 'transferir',
		       width: 55
		    });
    
			dataNomina = Ext.data.Record.create([
				{name: 'codemp'},
				{name: 'codnom'},	
				{name: 'desnom'},	
				{name: 'codnuenom', type: 'string', Format: '0000'},	
				{name: 'transferir', type: 'bool'}	
			]);			
			gridNominas = new Ext.grid.EditorGridPanel({
				width:560,
				height:100,
				id:'gridNominas',
		        plugins:checkColumn,
		        clicksToEdit:1,
   				autoScroll:true,
	           	border:true,
	           	ds: new Ext.data.Store({
					proxy: new Ext.data.MemoryProxy(ObjNominas),
					reader: new Ext.data.JsonReader({
			    		root: 'raiz',                
			    		id: 'id'   
	            	},
					dataNomina
					),
					data: ObjNominas
	           }),
	           cm: new Ext.grid.ColumnModel([
					{header: 'Empresa', width: 45, sortable: true, dataIndex: 'codemp'},
					{header: 'Cod. Act', width: 45, sortable: true, dataIndex: 'codnom'},
					{header: 'Nombre', width: 150, sortable: true, dataIndex: 'desnom'},
					{header: 'Cod. Nuevo', width: 45, sortable: true, dataIndex: 'codnuenom', 
					 editor: new Ext.form.TextField({minLength:4, maxLength:4, allowBlank:false, regex :/(^([0-9]{4,4})|^)$/,regexText:'Formato Inválido.' })},
					checkColumn
				]),
				
	           	viewConfig: {forceFit:true},
				stripeRows: true
			});
			gridNominas.startEditing(0, 0);
			//{header: 'Transferir', width: 45, sortable: true, dataIndex: 'transferir', editor: new Ext.form.Checkbox()},
			var fecinimen = new Ext.form.DateField(
			{
				fieldLabel:'Fecha Inicio Nominas Mensuales',
				labelStyle: 'width:140px',
				name:'Fecha Inicio Mensual',
				id:'txtfecinimen',
				format:'d/m/Y',
				value: '01/01/2009',
				width:120
			});
			
			var fecinisem = new Ext.form.DateField(
			{
				fieldLabel:'Fecha Inicio Nominas Semanales',
				labelStyle: 'width:140px',
				name:'Fecha Inicio Semanal',
				id:'txtfecinisem',
				format:'d/m/Y',
				value: '01/01/2009',
				width:120
			});

			nominapanel = new Ext.form.FieldSet({
					title:'Información Nómina',
					id:'fsformnomina',
					autoHeight:true,
					autoWidth:true,
					cls :'fondo',		
					items:[{	
					  	layout:'column',
					  	border:false,
					  	baseCls: 'fondo',
					  	items:[{
					  		columnWidth:.5,
							layout: 'form',
							border:false,
							baseCls: 'fondo',
							items: [fecinimen]
						},{	
							columnWidth:.5,
							layout: 'form',
							border:false,
							baseCls: 'fondo',
							items: [fecinisem]
						}]},gridNominas]
			});
			panel.add(nominapanel);
		}
		else
		{
			panel.remove(nominapanel);
		}
		panel.render(document.body);
	}	