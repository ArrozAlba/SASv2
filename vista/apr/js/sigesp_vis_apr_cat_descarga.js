/***********************************************************************************
* @Archivo javascript para el catálogo de descargas de archivos txt
* @fecha de creación: 27/10/2008
* @autor: Ing. Yesenia Moreno de Lang
*************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/

var gridDescargaCreada    = false;
var ventanaCreada = false;
var datos              = null;
var gridGrupo          = null;
var ventanaGrupo       = null;
var iniciargrid        = false;
var parametros         = '';
var rutaGrupo = '../../controlador/apr/sigesp_ctr_apr_descargas.php';

/***********************************************************************************
* @Función genérica para el uso del catálogo de descargas
* @parametros: 
* @retorno: 
* @fecha de creación: 27/10/2008. 
* @autor: Ing. Yesenia Moreno de Lang.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function catalogoDescarga()
	{		
		this.mostrarCatalogo = mostrarCatalogoDescarga;
	}
	
/***********************************************************************************
* @Función que busca los archivos dada una dirección
* @parámetros: form: id del formulario, 
* fieldset: id del fieldset,
* array: arrerglo con los campos del formulario
* arrayRecord: arreglo con los campos de la base de datos.
* @fecha de creación: 27/10/2008
* @autor: Ing. Yesenia Moreno de Lang 
************************************************************************************
* @fecha modificacion: 
* @autor:
* @descripcion: 
***********************************************************************************/
	function mostrarCatalogoDescarga()
	{
		var objdata ={
			'operacion': 'descargar',
			'ruta': rutadescarga, 
			'sistema': sistema,
			'vista': vista
		};
		objdata=JSON.stringify(objdata);
		parametros = 'objdata='+objdata;
		Ext.Ajax.request({
		url : rutaGrupo,
		params : parametros,
		method: 'POST',
		success: function ( resultado, request ) 
		{ 
			datos = resultado.responseText;
			var myObject = eval('(' + datos + ')');
			if(myObject.raiz[0].valido==true)
			{
				record = Ext.data.Record.create([
						 {name: 'archivo', mapping: 'archivo'},     
						 {name: 'ruta', mapping: 'ruta'},
						  {name: 'tope', mapping: 'tope'}
						 ]);					
				dsarchivos =  new Ext.data.Store({
						proxy: new Ext.data.MemoryProxy(myObject),
						reader: new Ext.data.JsonReader(
						{
							root: 'raiz',               
							id: 'id'   
						},
						record
						),
						data: myObject			
					 });
			 
			    var estilo = new Ext.XTemplate(
			        '<tpl for=".">',
			        '<div >',
			            '<h3>',
			            '<p>',
			            '<div align="center" style="position:absolute; left:5px; top:{tope}px;">',
			            '<a href="../../base/librerias/php/general/sigesp_lib_descarga.php?tipo=abrir&archivo={archivo}&enlace={ruta}" target="_blank" >{archivo}</a>',
			            '</div>',
			            '<div align="center" style="position:absolute; left:400px; top:{tope}px;">',
			            '<a href="../../base/librerias/php/general/sigesp_lib_descarga.php?tipo=eliminar&archivo={archivo}&enlace={ruta}" target="_blank" >Eliminar</a>',
			            '</div>',
			            '</h3>',
			            '</p>',
			            '<p></p>',
			         '</div></tpl>'
			    );
			    var panelArchivo = new Ext.Panel({
			        title:'Archivos',
			        height:300,
			        autoScroll:true,
			        items: new Ext.DataView({
			            tpl: estilo,
			            store: dsarchivos
			        })
			    });
			    if(!ventanaCreada)
			    {
			       ventanaDescarga = new Ext.Window({
			            title: 'Archivos para descargar',
			          	autoScroll:true,
			            closable:true,
			            closeAction: 'hide',
			            width:500,
			            height:300,
						modal:true,
			            items: [panelArchivo]
			        });
			        ventanaCreada=true;
			    }
			 	ventanaDescarga.show(this);			
		    }
		    else
		    {
				Ext.MessageBox.alert('Error', myObject.raiz[0].mensaje);
				close();
		    }
        },
        failure: function ( resultado, request)
		{ 
			Ext.MessageBox.alert('Error', resultado.responseText); 
        }
		});
	}