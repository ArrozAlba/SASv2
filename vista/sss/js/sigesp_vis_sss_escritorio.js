/***********************************************************************************
* @Javascript  el manejo de pantalla del Escritorio
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
* **************************
* @fecha modificacion  07/10/2008
* @autor  Ing. Yesenia Moreno de Lang
* @descripcion  Se realizó el escritorio dinámico según la permisología del usuario
***********************************************************************************/
var derecha1=100;
var superior1=150;
var derecha5=100;
var superior5=150;

var derecha2=100;
var superior2=150;
var derecha3=100;
var superior3=150;
var derecha4=100;
var superior4=150;
var divmodprincipales='';
var divmodauxiliares='';
var divmodpersonal='';
var divmodherramientas='';
var divmodadministrativos='';
var cadenaprincipales='';
var cadenaauxiliares='';
var cadenaherramientas='';
var cadenaadministrativos='';

Ext.onReady
(
	function()
	{
		rutaarchivo ='controlador/sss/sigesp_ctr_sss_seguridad.php';
    	Ext.QuickTips.init();
		
		var objmenu ={
			'operacion': 'escritorio'
		};
		objmenu=JSON.stringify(objmenu);
		parametros = 'objdata='+objmenu; 
		Ext.Ajax.request({
		url : rutaarchivo,
		params : parametros,
		method: 'POST',
		success: function (resultado, request)
		{ 
			obj   = eval('('+resultado.responseText+')');
			total = obj.raiz.length;
			if(obj.raiz[0].valido==true)
			{
				divmodprincipales = document.getElementById('x-shortcuts');
				divmodauxiliares = document.getElementById('x-shortcuts2');
				divmodpersonal = document.getElementById('x-shortcuts3');
				divmodherramientas = document.getElementById('x-shortcuts4');
				divmodadministrativos = document.getElementById('x-shortcuts5');
				cadenaprincipales = '';
				cadenaauxiliares = '';
				cadenapersonal = '';
				cadenaherramientas = '';
				cadenaadministrativos = '';
				// Se recorren los módulos principales
				for (sistemas=0; sistemas<total; sistemas++) 
				{
					switch (obj.raiz[sistemas].tipsis)
					{
						case '1': // Modulo Principales
							if(obj.raiz[sistemas].total>0)
							{
								cadenaprincipales = formatoCadena(derecha1,superior1,cadenaprincipales,obj,sistemas);
							}
						break;
	
						case '2': // Modulo Auxiliares
							if(obj.raiz[sistemas].total>0)
							{
								cadenaauxiliares = formatoCadena(derecha2,superior2,cadenaauxiliares,obj,sistemas);
							}
						break;
	
						case '3': // Modulo Personal
							if(obj.raiz[sistemas].total>0)
							{
								cadenapersonal = formatoCadena(derecha3,superior3,cadenapersonal,obj,sistemas);
							}
						break;
	
						case '4': // Modulo Herramientas
							if(obj.raiz[sistemas].total>0)
							{
								cadenaherramientas = formatoCadena(derecha4,superior4,cadenaherramientas,obj,sistemas);
							}
						break;
	
						case '5': // Modulo Administrativos
							if(obj.raiz[sistemas].total>0)
							{
								cadenaadministrativos = formatoCadena(derecha5,superior5,cadenaadministrativos,obj,sistemas);
							}
						break;
					}
				}
				var nuevoDiv = document.createElement("div");
				nuevoDiv.innerHTML = cadenaprincipales;
				var container = document.getElementById('x-shortcuts');
				container.appendChild(nuevoDiv);

				var nuevoDiv = document.createElement("div");
				nuevoDiv.innerHTML = cadenaauxiliares;
				var container = document.getElementById('x-shortcuts2');
				container.appendChild(nuevoDiv);

				var nuevoDiv = document.createElement("div");
				nuevoDiv.innerHTML = cadenapersonal;
				var container = document.getElementById('x-shortcuts3');
				container.appendChild(nuevoDiv);

				var nuevoDiv = document.createElement("div");
				nuevoDiv.innerHTML = cadenaherramientas;
				var container = document.getElementById('x-shortcuts4');
				container.appendChild(nuevoDiv);

				var nuevoDiv = document.createElement("div");
				nuevoDiv.innerHTML = cadenaadministrativos;
				var container = document.getElementById('x-shortcuts5');
				container.appendChild(nuevoDiv);

				/*divmodprincipales.innerHTML = cadenaprincipales;
				divmodauxiliares.innerHTML = cadenaauxiliares;
				divmodpersonal.innerHTML = cadenapersonal;
				divmodherramientas.innerHTML = cadenaherramientas;
				divmodadministrativos.innerHTML = cadenaadministrativos;*/
				// Cargar los panel principales
	            var modprincipales = new Ext.Panel({
	                title: 'Módulos Principales',
				    contentEl: 'x-shortcuts',
					iconCls: 'modprincipal',
					baseCls: 'modfondo',
	                cls:'empty'
	            });
	
	            var modauxiliares = new Ext.Panel({
	                title: 'Módulos Auxiliares',
				  	contentEl: 'x-shortcuts2',
					iconCls: 'modauxiliar',
					baseCls: 'modfondo',
	                cls:'empty'
	            });
	
	            var modpersonal = new Ext.Panel({
	                title: 'Módulos de Personal',
				  	contentEl: 'x-shortcuts3',
					iconCls: 'modpersonal',
					baseCls: 'modfondo',
	                cls:'empty'
	            });
	           
				var modherramienta = new Ext.Panel({
	                title: 'Herramientas del Sistema',
				    contentEl: 'x-shortcuts4',
					iconCls: 'modherramienta',
					baseCls: 'modfondo',
	                cls:'empty'
	            });

				var modAdministrativos = new Ext.Panel({
	                title: 'Módulos Administrativos',
				    contentEl: 'x-shortcuts5',
					iconCls: 'modherramienta',
					baseCls: 'modfondo',
	                cls:'empty'
	            });

				var accordion = new Ext.Panel({
	                region:'west',
	                margins:'45 0 70 5',
	                split:true,
	                width: 850,
					baseCls: 'modfondo',
	                layout:'accordion',
	                items: [modprincipales, modAdministrativos, modauxiliares, modpersonal, modherramienta]
	            });
				
				var rootnode = new Ext.tree.AsyncTreeNode({
				text:'Notificaciones',
				expanded:true,
				children:[
				{
					 text:'Tareas Pendientes',
					 leaf:true
				}]
				});
	            var viewport = new Ext.Viewport({
	                layout:'border',
	                items:[
	                    accordion,
						{
				        region:'center',
						margins:'23 5 70 0',
	                    cls:'empty',
	                    bodyStyle:'background:#E9E9E9',
						items:[{
							region:'north',	
							xtype:'panel',
							title: 'Opciones del Sistema',
							contentEl:'opcionessistema',
							height:60
							},{
							xtype:'panel',
							title: 'Indicador de Tareas',
							height:600,
							items:[{
								   region: 'north',
								   	xtype:'treepanel',
									id:'notificaciones',
									border: true,
									//loader: new Ext.tree.TreeLoader(),
									rootVisible:true,
									lines:true,
									autoScroll:true,
									//iconCls:'menu1',
									root: rootnode
								   }]
							}						
							]
	                	}
						]
	            });
			}
			else
			{
				Ext.MessageBox.alert('Error', 'No se pudo Cargar el Escritorio.'+obj.raiz[0].mensaje);
				close();
			}
		},
		failure: function (result,request) 
		{ 
			Ext.MessageBox.alert('Error', 'No se pudo Cargar el Escritorio. Favor Contacte al administrador del Sistema.');
			close();
		}
		});
	}
);


/***********************************************************************************
* @Función para Cargar los div que van al escritorio
* @parametros: derecha, superior, cadena
* @retorno: Cadena 
* @fecha de creación: 12/09/2008.
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function formatoCadena(derecha,superior,cadena,obj,id)
	{			//<a href='"+obj.raiz[id].accsis+"'>
		cadena = cadena+
		         " <dt id='acc-win-shortcut' style='position:absolute; left:"+derecha+"px;top:"+superior+"px'> "+
				 " <a href='"+obj.raiz[id].accsis+"'><img src='base/imagenes/"+obj.raiz[id].imgsis+"'>"+
				 "<div>"+obj.raiz[id].nomsis+"</div></a></dt> ";
		if(eval('derecha'+obj.raiz[id].tipsis+'>500'))
		{
			eval('derecha'+obj.raiz[id].tipsis+'=100;');
			eval('superior'+obj.raiz[id].tipsis+'=superior'+obj.raiz[id].tipsis+'+150;');
		}
		else
		{
			eval('derecha'+obj.raiz[id].tipsis+'=derecha'+obj.raiz[id].tipsis+'+200;');
		}
		return cadena;
	}


/***********************************************************************************
* @Función para abrir la ventana de cambio de base de datos.
* @parametros:
* @retorno: 
* @fecha de creación: 21/11/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function cambiarBd()
	{
		ancho=screen.width-600;
		alto=screen.height-400;
		Xpos=((screen.width - ancho)/2); 
		Ypos=((screen.height - alto) /2);
		ventana = window.open ("cambiobd.html","Cambio BD","menubar=1,resizable=1,width="+ancho+",height="+alto+",left="+Xpos+",top="+Ypos+"");  
			
	}