// JavaScript Document

var url    ='../php/sps_cat_causaretiro.php';
var params ='operacion';
var metodo = 'get';

function ue_inicializar()
{
	validarOpener();                     //valida que el catalogo sea llamado de una pagina
	params = "operacion=ue_inicializar";
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
	function onInicializar(respuesta)
	{
		var repuestas = respuesta.responseText.split('&');	
		$('data_grid_header').id = (repuestas[0]<=10)?"sin_ordenar":$('data_grid_header').id;
		$('viewPort').innerHTML  = repuestas[1];
		
		//Creamos la grid
		var opts =
		{
			prefetchBuffer:true,
			onScroll:updateHeader,
			sortAscendImg: '../../../shared/imagebank/tools20/sort_asc.gif',
			sortDescendImg: '../../../shared/imagebank/tools20/sort_desc.gif'
		};
	
		new Rico.LiveGrid("data_grid",10,repuestas[0],url,opts);
	}
}

//funcion para ordenar por campo y actualiza la tabla al hacer scroll
function updateHeader(liveGrid, offset)
{
	$('marcador').innerHTTML = "Mostrando Registros" + (offset+2) + " - " + 
	                           (offset+liveGrid.metaData.getPageSize()+1)	+ " de " +
	                            liveGrid.metaData.getTotalRows();
	
	var sortInfo = "";
	if (liveGrid.sortCol)
	{
		sortInfo = "&data_grid_sort_col=" + liveGrid.sortCol + "&data_grid_sort_dir="+liveGrid.sortDir;
	}
}

function ue_seleccionar(arreglo)
{
	opener.ue_cargar_registro(arreglo);
	close();
}