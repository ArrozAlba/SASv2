/*
codigo javascript asociado al archivo fuentes de financiamiento

*/

var PanelPersonal="";
var gridCargos="";
var Actualizar=null
var pantalla='sigesp_sfp_empresa.php';
var ruta ='../../procesos/sigesp_sfp_empresapr.php';
var Campos =new Array(
		
	        ['codemp','novacio|'],
	        ['nombre','novacio|'],
	        ['rifemp','novacio|'],
	        ['nitemp','novacio|'],
	        ['base_legal','novacio|'],
	        ['ano_inicio','novacio|'],
	        ['sector','novacio|'],
	        ['base_legal','novacio|'],
	        ['consolidadora','|'],
	        ['codemp_con','|'],
	        ['forma_juri','novacio|'],
	        ['mision','|'],
	        ['vision',''],
	        ['telemp','|'],
	        ['faxemp',''],
	        ['email','|'],
	        ['website',''],
	        ['email','|'],
	        ['website',''],
	        ['zonpos',''],
	        ['estemp','|'],
	        ['ciuemp',''],
	        ['diremp',''],
	        ['nom_presi','|'],
	        ['tel_presi',''],
	        ['nom_dirplan','|'],
	        ['tel_dirplan',''],
	        ['email_dirplan','|'],
	        ['email_presi',''],
   			['nom_diradmin','|'],
	        ['tel_diradmin',''],
	        ['email_diradmin','|'],	        
   			['nom_dirrh','|'],
	        ['tel_dirrh',''],
	        ['email_dirrh','|'],	       
	        ['nom_respre','|'],
	        ['tel_respre',''],
	        ['email_respre','|'],	
	        ['compat',''],
	        ['politicapre','|'],
	        ['emprin','|'],
	        ['formspi','|'],
	        ['formpre','|']            
 )

Ext.onReady(function(){

Ext.get('BtnNuevo').on('click',irLlamarNuevo);
Ext.get('BtnGrabar').on('click', LlamarActualizar);
Ext.get('BtnCat').on('click', MostrarCatEmp);
Ext.get('BtnElim').on('click',LlamarEliminar);
ObtenerSesion(ruta,pantalla)

function irLlamarNuevo()
{
	LlamarNuevo();
	cargarformatocuentas();
}

Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank.php';
})

	function getGridPersonas()
	{
		
		var item1 = new Ext.Panel({
		    title: 'Presidente de La Institución',
		    contentEl:'datpresi',
			cls:'empty'
	     });

        var item2 = new Ext.Panel({
             title: 'Planificación y Presupuesto',
             contentEl:'datdirplan',
             cls:'empty'
        });
      

        var item3 = new Ext.Panel({
             title: 'Administración y/o Finanzas',
             contentEl:'datdiradmin',
             cls:'empty'
        });
        
		  var item4 = new Ext.Panel({
		    title: 'Recursos Humanos y/o Personal',
		    contentEl:'datdirrh',
			cls:'empty'
	     });
	     
	      var item5 = new Ext.Panel({
		    title: 'Analistas Responsables del Presupuesto ',
		    contentEl:'datanres',
			cls:'empty'
	     });
	     

                PanelPersonal = new Ext.Panel({
                region:'south',
                width:410,
                height:250,
                renderTo:'panelaccordion',
                style:"height:250px;margin-left:250px;margin-top:20px",
                bodyStyle:'background-color:#DFE8F6',
                layout:'accordion',
                items:[item1,item2,item3,item4,item5]
				})
	}
	getGridPersonas();
	tabs= new Ext.TabPanel
	(
    {
            //baseCls:'x-plain',
			renderTo: 'tabs0',
			frame:true,
			activeTab:0,
			autoScroll:true,
            width:950,
            height:500,
			style:'margin-left:40px;margin-top:40px',
            plain: false
		    ,defaults: {frame:true, width:800, height: 200},
		  items:
		  [{
			id:'tab1',
			contentEl:'tabs1',
    		title: 'Datos Básicos',
    		closable:false,
    		autoScroll:true
		  },
		  {
			id:'tab2',
			contentEl:'tabs2',
    		title: 'Misión/Visión',
    		closable:false,
    		autoScroll:true
		  }
		  ,
		  {
			id:'tab3',
			contentEl:'tabs3',
    		title: 'Datos de Contacto',
    		closable:false,
    		autoScroll:true
		  }
		  ,
		  {
			id:'tab4',
			contentEl:'panelaccordion',
    		title: 'Datos del Personal',
    		closable:false,
    		autoScroll:true
		  }
		  ,
		  {
			id:'tab5',
			contentEl:'tabs5',
    		title: 'Política Presupuestaria',
    		closable:false,
    		autoScroll:true
		  }
		  ,
		  {
			id:'tab6',
			contentEl:'tabs6',
    		title: 'Formatos de Cuentas',
    		closable:false,
    		autoScroll:true
		  }
		  ]
    });	
	

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
                height:250,
                el:'centro'    
            })
            ]
          })

function cargarempresa()
{
		var myJSONObject ={
			"oper": 'leerempresa'
	};	
		ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		Ext.Ajax.request({
		url : ruta,
		params : parametros,
		method: 'POST',
		success: function (resultado, request) { 
		  datos = resultado.responseText;
		//  alert(datos);
		  var DatosNuevo = eval('(' + datos + ')');
		 if(datos=='' && datos.raiz==null)
		 {
			var DatosNuevo={"raiz":[{"codemp":'',"nombre":''}]};
		 }
		var unidad = Ext.get('codemp_con');
		for (i=0; i<DatosNuevo.raiz.length; i++)
		{	
			var opcion = document.createElement('option');
			opcion.value = DatosNuevo.raiz[i].codemp;
			opcion.text = DatosNuevo.raiz[i].nombre;
			unidad.dom.add(opcion,null);
		}
	}
})	
}



function cargarformatocuentas()
{
		var myJSONObject ={
			"oper": 'formatocuentas'
	    };	
		ObjSon=JSON.stringify(myJSONObject);
		parametros = 'ObjSon='+ObjSon; 
		Ext.Ajax.request({
		url : ruta,
		params : parametros,
		method: 'POST',
		success: function (resultado, request) { 
		 datos = resultado.responseText;
		 var DatosNuevo = eval('(' + datos + ')');
		 if(datos=='' && datos.raiz==null)
		 {
			var DatosNuevo={"raiz":[{"codemp":'',"nombre":''}]};
		 }
		 Ext.get('formpre').dom.value = DatosNuevo.raiz[0].formpre;
		 Ext.get('formspi').dom.value = DatosNuevo.raiz[0].formspi;

	}
})	
}



Ext.get('consolidadora').on('click',function(){
	auxValor=Ext.get('consolidadora').dom.checked;
	if(auxValor==true)
	{
		Ext.get('codemp_con').dom.disabled=true;
	}
	else
	{
		Ext.get('codemp_con').dom.disabled=false;
	}
})
cargarempresa();
});