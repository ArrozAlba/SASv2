// JavaScript Document
var url = '../php/sps_rep_dt_antiguedad.php';
var params = 'operacion';
var metodo = 'get';

Event.observe(window, 'load', ue_cancelar, false);

function ue_buscarpersonal()
{
	pagina="sps_cat_antiguedad.html.php";
    catalogo = popupWin(pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}
function ue_cargarCatalogo(arr_datos)
{    
	$('txtcodper').value = trim(arr_datos[0]);
}

function subirCampo()
{
	for(i=1; i<$('lstorden').options.length; i++)
	  if ($('lstorden').options[i].selected)
	  {
		 var opcionAnterior = null;
		 for (j=i-1; (j>=0) && (opcionAnterior == null); j--)
		   if ($('lstorden').options[j].selected == false)
		     opcionAnterior = new Option($('lstorden').options[j].text,$('lstorden').options[j].value);
		 if (opcionAnterior != null)
		 {
		   $('lstorden').options[j+1] = new Option($('lstorden').options[i].text,$('lstorden').options[i].value);
		   $('lstorden').options[j+1].selected = true;
		   $('lstorden').options[i] = opcionAnterior;
		 }
	  }
}

function bajarCampo()
{
	for(i=$('lstorden').options.length-2; i>=0; i--)
	  if ($('lstorden').options[i].selected)
	  {
		 var opcionPosterior = null;
		 for (j=i+1; (j<$('lstorden').options.length) && (opcionPosterior == null); j++)
		   if ($('lstorden').options[j].selected == false)
		     opcionPosterior = new Option($('lstorden').options[j].text,$('lstorden').options[j].value);
		 if (opcionPosterior != null)
		 {
		   $('lstorden').options[j-1] = new Option($('lstorden').options[i].text,$('lstorden').options[i].value);
		   $('lstorden').options[j-1].selected = true;
		   $('lstorden').options[i] = opcionPosterior;
		 }
	  }
}

function ue_cancelar()
{
	  document.form1.reset();
	  for (i=0; i<$('lstorden').options.length; i++)
	  {
		  var opcionCambiar = null;
		  for (j=i; (j<$('lstorden').options.length) && (opcionCambiar == null); j++)
		  {
			  if ($('lstorden').options[j].value.split('-')[1] == i)
			  {
			    opcionCambiar = new Option($('lstorden').options[j].text,$('lstorden').options[j].value);
				$('lstorden').options[j] = new Option($('lstorden').options[i].text,$('lstorden').options[i].value);
				$('lstorden').options[i] = opcionCambiar;
			  }
		  }
	  }
}

function ue_imprimir()
{
	var la_objetos=new Array("txtcodper","txtfechainicio","txtfechafin");
	var la_mensajes=new Array("el Código del Personal","la Fecha de Inicio en el Rango","la Fecha de Fin en el Rango");
	lb_valido = valida_datos_llenos(la_objetos, la_mensajes);
	if (lb_valido)
	   lb_valido = compararFechas("txtfechainicio","txtfechafin","La Fecha de Inicio del Rango debe ser Menor o Igual a la Fecha de Fin !!!");
	if(lb_valido)
	{
	  var orden = "";
	  for (i=0; i<$('lstorden').options.length; i++)
	  {
	    orden += $('lstorden').options[i].value.split('-')[0];
		if (i <($('lstorden').options.length-1))
		  orden += ",";
	  }
	  var parametros =
	  {
		"operacion"  :"ue_imprimir",
		"codper"     :$F('txtcodper'),
		"fechainicio":$F('txtfechainicio'),
		"fechafin"   :$F('txtfechafin'),
		"orden"      :orden
	  };
	  params = $H(parametros).toQueryString();

	  var pagina = "../../reports/documents/sps_reporte_dt_antiguedad.php?"+params;
	  ue_cancelar();
	  window.open(pagina,"reporte","menubar=no,toolbar=no,scrollbars=yes,width="+screen.width+",height="+(screen.height-60)+",resizable=yes,top=0,left=0");
	}
}