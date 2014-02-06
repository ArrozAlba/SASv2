<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	require_once("class_folder/sigesp_soc_c_analisis_cotizacion.php");
	$io_analisis=new sigesp_soc_c_analisis_cotizacion();
	require_once("../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();	
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	$ls_tipo = $_GET["tipsolcot"];
	if($ls_tipo=="B")
		$ls_tipsolcot="Bienes";
	else
		$ls_tipsolcot="Servicios";
	$ls_coditem=$_GET["coditem"];
	$ls_numsolcot=$_GET["numsolcot"];
	$li_totalcotizaciones=$_GET["totalcotizaciones"];
	$li_item=$_GET["item"];//Posicion de la fila de items donde se actualizara el registro;
	$io_analisis->uf_proveedores_item($_GET["tipsolcot"],$ls_numsolcot,$ls_coditem,$la_proveedores);//Se obtienen los proveedores que cotizaron el item seleccionado
	for($li_i=1;$li_i<=$li_totalcotizaciones;$li_i++)//Se obtiene en un arreglo los proveedores que estan participando en el analisis de cotizacion
	{
		$la_provcotizaciones[$li_i]=$_GET["codpro".$li_i];
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Proveedores por &Iacute;tem</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>

</head>
<script type="text/javascript"  src="js/funcion_soc.js" language="javascript"></script>
<body>
<form name="formulario" method="post" action="">
  <p><br> 
    <br> 
  </p>
  <div align="center"><table width="800" border="0" class="titulo-celda">
    <tr>
      <td>Cat&aacute;logo de Proveedores por <?php print $ls_tipsolcot?> </td>
    </tr>
  </table></div>
  <p><?php  
		$li_totrow=count($la_proveedores);
		if($li_totrow>=1)
		{
			print "<div align=center><table width=800 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
			print "<tr class=titulo-celda>";
			print "<td>Cod. Proveedor</td>";
			print "<td>Nombre</td>";
			print "<td>Cotización</td>";
			print "<td>Cantidad</td>";
			print "<td>Precio Unit.</td>";
			print "<td>I.V.A.</td>";
			print "<td>Total</td>";
			print "<td>Calificación</td>";
			print "</tr>";		
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_codpro=$la_proveedores[$li_i]["cod_pro"];
				if(array_search($ls_codpro,$la_provcotizaciones))
				{
					print "<tr class=celdas-blancas>";				
					$ls_nompro=        $la_proveedores[$li_i]["nompro"];
					$ls_numcot=        $la_proveedores[$li_i]["numcot"];
					$ls_cantidad=      number_format($la_proveedores[$li_i]["cantidad"],2,",",".");
					$ls_preciounitario=number_format($la_proveedores[$li_i]["preciounitario"],2,",",".");
					$ls_moniva=        number_format($la_proveedores[$li_i]["moniva"],2,",",".");
					$ls_montototal=    number_format($la_proveedores[$li_i]["montototal"],2,",",".");
					switch($la_proveedores[$li_i]["calidad"])
					{
						case "E":
							$ls_calificacion="Excelente";
						break;
						case "B":
							$ls_calificacion="Bueno";
						break;
						case "R":
							$ls_calificacion="Regular";
						break;
						case "M":
							$ls_calificacion="Malo";
						break;
						case "P":
							$ls_calificacion="Muy Malo";
						break;
					}							
					print "<td align=center><a href=\"javascript: ue_aceptar('$ls_nompro','$ls_numcot','$ls_cantidad','$ls_preciounitario','$ls_moniva','$ls_montototal','$ls_calificacion','$ls_coditem','$ls_tipo','$ls_codpro');\">".$ls_codpro."</a></td>";
					print "<td>".$ls_nompro."</td>";
					print "<td align=center>".$ls_numcot."</td>";
					print "<td align=center>".$ls_cantidad."</td>";
					print "<td align=right>".$ls_preciounitario."</td>";
					print "<td align=right>".$ls_moniva."</td>";
					print "<td align=right>".$ls_montototal."</td>";
					print "<td align=center>".$ls_calificacion."</td>";
					print "</tr>";
				}			
			}
			print "</table></div>";
		}
?></p>
  </form>      
</body>
<script language="JavaScript">
function ue_aceptar(as_nompro,as_numcot,as_cantidad,as_preciounitario,as_moniva,as_montototal,as_calificacion,as_coditem,as_tipsolcot,as_codpro)
{	
	total=ue_calcular_total_fila_opener("txtcoditem");//se calcula la cantidad de items que hay	
	//---------------------------------------------------------------------------------
	// Cargar las cotizaciones del opener y el seleccionado
	//---------------------------------------------------------------------------------
	parametros="";
	for(j=1;j<=total;j++)
	{ 
		coditem=eval("opener.document.formulario.txtcoditem"+j+".value");
    	nomitem=eval("opener.document.formulario.txtnomitem"+j+".value");
		observacion=eval("opener.document.formulario.txtobservacion"+j+".value");	

		if(coditem==as_coditem)//Se chequea para incluir los datos en la fila correcta
		{
			nomproitem=as_nompro;
			canselec=as_cantidad;
			preuniselec=as_preciounitario;
			ivaselec=as_moniva;
			monselec=as_montototal;
			numcot=as_numcot;
			codpro=as_codpro;
		}
		else
		{
			nomproitem=eval("opener.document.formulario.txtnomproitem"+j+".value");
			canselec=eval("opener.document.formulario.txtcanselec"+j+".value");
			preuniselec=eval("opener.document.formulario.txtpreuniselec"+j+".value");
			ivaselec=eval("opener.document.formulario.txtivaselec"+j+".value");		
			monselec=eval("opener.document.formulario.txtmonselec"+j+".value");
			numcot=eval("opener.document.formulario.txtnumcotsele"+j+".value");
			codpro=eval("opener.document.formulario.txtcodproselec"+j+".value");
		}		
		parametros=parametros+"&txtcoditem"+j+"="+coditem+"&txtnomitem"+j+"="+nomitem+"&txtnomproitem"+j+"="+nomproitem+"&txtcanselec"+j+"="+canselec+
		"&txtpreuniselec"+j+"="+preuniselec+"&txtivaselec"+j+"="+ivaselec+"&txtmonselec"+j+"="+monselec+"&txtobservacion"+j+"="+observacion+"&txtnumcotsele"+j+"="+numcot+
		"&txtcodproselec"+j+"="+codpro;		
	}
	//parametros=parametros+"&total="+total+"&as_tipsolcot="+as_tipsolcot;
	parametros=parametros+"&total="+total+"&tipsolcot1="+as_tipsolcot;
	
	
	
	if(parametros!="")
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("items");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_soc_c_analisis_cotizacion_ajax.php",true);
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1)
			{
				//divgrid.innerHTML = "";//<-- aqui iria la precarga en AJAX 
			}
			else
			{
				if(ajax.readyState==4)
				{
					if(ajax.status==200)
					{//mostramos los datos dentro del contenedor
						divgrid.innerHTML = ajax.responseText
					}
					else
					{
						if(ajax.status==404)
						{
							divgrid.innerHTML = "La página no existe";
						}
						else
						{//mostramos el posible error     
							divgrid.innerHTML = "Error:".ajax.status;
						}
					}
					
				}
			}
		}	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("proceso=ACTUALIZARITEMS"+parametros);
		//opener.document.formulario.totrowbienes.value=totalbienes;
	}
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>