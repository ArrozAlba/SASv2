/*
codigo javascript asociado al archivo fuentes de financiamiento

*/

var PanelPersonal="";
var gridCargos="";
var Actualizar=null
ruta ='../../procesos/sigesp_sfp_tipoindpr.php';
pantalla ='sigesp_sfp_tipoindi.php';
var Campos =new Array(
		
	        ['cod_tipoind','novacio|'],
	        ['denominacion','novacio|']
	    )

Ext.onReady(function(){
ObtenerSesion(ruta,pantalla);
Ext.get('BtnNuevo').on('click',LlamarNuevo);
Ext.get('BtnGrabar').on('click', LlamarActualizar);
Ext.get('BtnCat').on('click', MostrarCatEmp);
Ext.get('BtnElim').on('click',LlamarEliminar);

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




Ext.get('BtnSalir').on('click',function()
{
	location.href='sigesp_windowblank2.php';
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
//cargarempresa();
});