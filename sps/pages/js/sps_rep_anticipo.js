// JavaScript Document

Event.observe(window, 'load', ue_cancelar, false);

function ue_buscarpersonal()
{
	//pagina="sps_cat_rep_anticipo.html.php";
	pagina="sps_cat_personal_anticipo.html.php";
    catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=250,resizable=yes,location=no,top=0,left=0");
}

function ue_cargar_registro_anticipo(arr_datos)
{    
	$('txtcodper').value = trim(arr_datos[0]);
	$('txtnomper').value = trim(arr_datos[1]+' '+arr_datos[2]);
}

function ue_buscarcartaanticipo()
{
	pagina="sps_cat_cartaanticipo.html.php";
    	catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}

function ue_cargar_registro(arr_datos)
{    
	$('txtcodcarant').value = trim(arr_datos[0]);
	$('txtdescarant').value = trim(arr_datos[1]);
	$('txtnomrtf').value = trim(arr_datos[10]);
}

function ue_cancelar()
{
	  document.form1.reset();
	  deshabilitar("txtcodper,txtnomper,txtcodcarant,txtdescarant,txtnomrtf");
}

function ue_imprimir()
{
	var la_objetos=new Array("txtcodper");
	var la_mensajes=new Array("el Código del Personal");
	lb_valido = valida_datos_llenos(la_objetos, la_mensajes);
	if(lb_valido)
	{
	  var parametros =
	  {
		"operacion"  :"ue_imprimir",
		"codper"     :$F('txtcodper'),
		"codcarant"  :$F('txtcodcarant')
	  };
	  params = $H(parametros).toQueryString();
	  var pagina = "../../reports/documents/sps_reporte_cartaanticipo.php?"+params;
	  ue_cancelar();
	  window.open(pagina,"reporte","menubar=no,toolbar=no,scrollbars=yes,width="+screen.width+",height="+(screen.height-60)+",resizable=yes,top=0,left=0");
	}
}

function ue_print_word()
{
	ls_nomrtf = $F('txtnomrtf');
	if(ls_nomrtf!="")
	{
		codcarant=$F('txtcodcarant');
		if(codcarant!="")
		{
			  var parametros =
			  {
				"operacion"  :"ue_imprimir",
				"codper"     :$F('txtcodper'),
				"codcarant"  :$F('txtcodcarant')
			  };
			  params = $H(parametros).toQueryString();
			  var pagina = "../../reports/documents/sps_reporte_cartaanticipo_word.php?"+params;
			  ue_cancelar();
			  window.open(pagina,"reporte","menubar=no,toolbar=no,scrollbars=yes,width="+screen.width+",height="+(screen.height-60)+",resizable=yes,top=0,left=0");
		
		}
		else
		{
			alert("Debe Seleccionar un Modelo de Carta de Anticipo.");
		}
	}
	else
	{
		alert("Esta Carta de Anticipo no tiene plantilla rtf.");
	}		
}
