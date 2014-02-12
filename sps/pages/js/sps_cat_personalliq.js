// JavaScript Document

var url    ='../php/sps_cat_personalliq.php';
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
			    $('cmbcodnom').options[$('cmbcodnom').options.length] = new Option("Todas las Nominas","all");
				var nomina = JSON.parse(respuestas[num_respuesta]);
				for (i=0; i<nomina.codnom.length; i++)
				    $('cmbcodnom').options[$('cmbcodnom').options.length] = new Option(nomina.desnom[i], nomina.codnom[i]);
			}
		}
		
	}//function onInicializar

}
function ue_ver_personal()
{ 
  validarOpener();                    //valida que el catalogo sea llamado de una pagina
  if (arguments.length > 0)
  {changeCase(arguments[0]);}
  var params = 
  {
	operacion : "ue_ver_personal",
	cedper : $F('txtcedper'),
	nomper : $F('txtnomper'),
	apeper : $F('txtapeper'),
	codnom : $F('cmbcodnom')
  };
  new Ajax.Request(url,{method:metodo,parameters:$H(params).toQueryString(),onComplete:onVerPersonal});
  
  function onVerPersonal(respuesta)
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
	  cedper    : $F('txtcedper'),
	  nomper    : $F('txtnomper'),
	  apeper    : $F('txtapeper'),
	  codnom    : $F('cmbcodnom')
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