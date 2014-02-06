
/*
 * Ext JS Library 2.0.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * http://extjs.com/license
 */
 
var Oper = "";
var Actualizar='';
var FormularioBus="";
var gridPlanCuentas="";
var gridReportes=""; 
var combo1="";
ruta ='../../procesos/sigesp_spe_reportespr.php';
pantalla ='sigesp_spe_reportes.php';
Ext.onReady(function()
{
	   Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
       var tools = [{
           id:'gear',
           handler: function(){
               Ext.Msg.alert('Message', 'The Settings tool was clicked.');
           }
       },{
           id:'close',
           handler: function(e, target, panel){
               panel.ownerCt.remove(panel, true);
          }
       }];

			    // shorthand reference to (slightly) reduce complexity later
			    var Tree = Ext.tree;
				// create a treePanel object. Note that the region:'west' is what tells Ext which panel to display the tree in 
			    var tree = new Tree.TreePanel({
					region:'west',				
	                id:'west-panel',
	                title:'Ayuda',
	                split:false,
	                width: 200,
	                minSize: 175,
	                maxSize: 400,
	                collapsible: true,
	                margins:'35 0 5 5',
	                cmargins:'35 5 5 5',
					// these are the config options for the tree itself				
			        autoScroll:true,
			        animate:true,
			        enableDD:true, // Allow tree nodes to be moved (dragged and dropped)
			        containerScroll: true,
			       root: new Ext.tree.AsyncTreeNode({
			       text:'Opciones de Ayuda',
                    children: [{
                        text: 'Informacion General',
                        id:'0',
                        expanded: false,
                        children: [{
                        	id:'1',
                            text: 'Introducción',	
                            leaf: true
                        }, {
                        	id:'2',
                            text: 'Objetivos',
                            leaf: true
                        },{
                        	id:'3',
                            text: 'Características principales',
                            leaf: true
                        }]
                    }
                     ,
                           { 
                            			text: 'Instalación del Módulo',
		                            	children: [{
			                            text: 'Instalación',
			                            leaf: true,
			                            id:'8'
			                        }
			                        ]
		                  }
                    , {
                        text: 'Opciones del Menú Empresa',
                        expanded: false,
                        children: [{
                            text: 'Creación/Actualización de la Empresa',
                            id:'defemp',
                            leaf: true
                        }
                        ,
                          {
                            			text: 'Plan General de Cuentas Integrado',
		                            	children: [{
			                            text: 'Creación de nuevas cuentas',
			                            leaf: true,
			                            id:'4'
			                        }
			                        ,
			                        {
			                            text: 'Modificación de cuentas existentes',
			                            leaf: true,
			                            id:'5'
			                        }
			                        ]
		                    }
		                    ,
                        	{
                            			text: 'Casamiento de Cuentas',
		                            	children: [{
			                            text: 'Creación de nuevas cuentas',
			                            leaf: true,
			                            id:'4'
			                        }
			                        ,
			                        {
			                            text: 'Modificación de cuentas existentes',
			                            leaf: true,
			                            id:'5'
			                        }
			                        ]
		                    }
		                    ]
                        }
                        ]
                })
			    });	
			    
				tree.addListener('click', function (node, event){
				//	alert(node.attributes.id+'='+node.attributes.text);
					id=node.attributes.id;
					switch(id)
					{
						case '8':
						Ext.get('frame-welcome').dom.src='../../otros/ayudas/instalacion.htm';
						break;
						case 'defemp':
						Ext.get('frame-welcome').dom.src='../../otros/ayudas/empresa.htm';
						break;
					}
				});
			
			/* End of tree definition */	
	
	// Define the Viewport object. This is a modifed version of the original example code
       var viewport = new Ext.Viewport({
           layout:'border',
           items:[
 	      new Ext.Panel({
	      id: 'tab',
		plain: false,  //remove the header border
		region:'center',
		height:400,
		margins:'35 0 5 0',
	    cmargins:'35 5 5 5',
		items:[{
			title: 'Vista previa del formato',
			iconCls:'home_icon',
			html : '<iframe id="frame-welcome" src="http://www.google.com" border="0" width="800" height="550" style="border:0"  scrolling="yes"></iframe>'

		}]  
          }) 
          ,
		tree
		] // end viewport items array 
       }); // end of viewport object definition
   }); // end of Ext.onReady function

