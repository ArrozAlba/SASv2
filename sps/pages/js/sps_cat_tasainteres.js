// JavaScript Document

var url    ='../php/sps_cat_tasainteres.php';
var params ='operacion';
var metodo ='get';

function ue_inicializar()
{	
	params = "operacion=ue_inicializar";
    new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
	function onInicializar( respuesta )
	{  
		if (trim(respuesta.responseText) != "")
		{   
			var respuestas = respuesta.responseText.split('&');
			num_respuesta = -1;
			num_respuesta++;
			if (trim(respuestas[num_respuesta]) != "")
			{   
			    var anos = JSON.parse(respuestas[num_respuesta]);
			    for (i=0; i<anos.length; i++)
				{ $('cmbano').options[$('cmbano').options.length] = new Option(anos[i].den,anos[i].cod);}
			}
		}
		
	}//function onInicializar
	//ue_ver_tasainteres();
}

function ue_ver_tasainteres()
{ 
  validarOpener();                    //valida que el catalogo sea llamado de una pagina
  if (arguments.length > 0)
  {changeCase(arguments[0]);}
  var params = 
  {
	operacion : "ue_ver_tasainteres",
	ano : $F('cmbano')
  };
  
  new Ajax.Request(url,{method:metodo,parameters:$H(params).toQueryString(),onComplete:onVerTasaInteres});
  
  function onVerTasaInteres(respuesta)
  { 
	var respuestas = respuesta.responseText.split('&');
	chequearFilas(respuestas[0],$('hidfilas'));
	$('viewPort').innerHTML = respuestas[1];

	// Creamos la Grid
    var opts = 
    {
	  prefetchBuffer: true, 
	  onscroll : updateHeader,
      sortAscendImg:  '../../../shared/imagebank/sort_asc.gif',
      sortDescendImg: '../../../shared/imagebank/sort_desc.gif'
    };
	var params = 
    {
	  ano    : $F('cmbano')
    };
    new Rico.LiveGrid("data_grid",12, respuestas[0],url+"?"+$H(params).toQueryString(),opts);
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