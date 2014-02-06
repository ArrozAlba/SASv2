<?
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";		
} 
$la_datemp=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Ingenieros de Obras</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699#006699;
}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
<?

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sob_class_obra.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
$io_funsob=new sigesp_sob_c_funciones_sob();
$io_datastore=new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_obra=new sigesp_sob_class_obra();
$ls_codemp=$la_datemp["codemp"];



if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codpro="%".$_POST["txtcodpro"]."%";	
	$ls_nompro="%".$_POST["txtnompro"]."%";	
	$ls_codpro=$_POST["hidcodpro"];
	$ls_tipocatalogo=$_POST["hidtipocatalogo"];
}
else
{
	$ls_operacion="";
	$ls_codpro=$_GET["codpro"];
	$ls_tipocatalogo=$_GET["tipocatalogo"];
	//$ls_codigo=$_GET["codigo"];
	//$ls_denban=$_GET["denban"];
}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<?Php
			if($ls_tipocatalogo=="INSPECTOR")
			{
			?>
			<td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Ing. Inspectores de Obras </td>
			<?Php
			}
			elseif($ls_tipocatalogo=="RESIDENTE")
			{
			?>
			<td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Ing. Residentes de Obras </td>
			<?Php
			}
			?>
						
    	</tr>
	 </table>
	 <br>
	 <input name="hidcodpro" id="hidcodpro" type="hidden" value="<? print $ls_codpro; ?>">
	 <input name="hidtipocatalogo" id="hidtipocatalogo" type="hidden" value="<? print $ls_tipocatalogo; ?>">
<?

	$ls_sql="SELECT s.nomsup,p.cod_pro,p.nompro,s.civ,s.cedsup
			FROM rpc_supervisores s,rpc_proveedor p
			WHERE p.codemp='".$ls_codemp."' AND p.codemp=s.codemp AND p.cod_pro=s.cod_pro AND p.cod_pro='".$ls_codpro."' ORDER BY p.cod_pro ASC";			
	$rs_data=$io_sql->select($ls_sql);
	$data=$rs_data;
	if($rs_data===false)
	{
		$is_msg_error="Error en select".$io_funcion->uf_convertirmsg($io_sql->message);
		print $is_msg_error;
	}else
	{
		if($row=$io_sql->fetch_row($rs_data))
		{
			$data=$io_sql->obtener_datos($rs_data);
			$io_datastore->data=$data;
			$li_totrow=$io_datastore->getRowCount("nompro");
			print "<table width=650 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td>Código Empresa</td>";
			print "<td>Nombre Empresa</td>";
			print "<td>Ing. Residente</td>";
			print "</tr>";
			for($li_z=1;$li_z<=$li_totrow;$li_z++)
			{
				print "<tr class=celdas-blancas align=center>";
				$ls_codpro=$data["cod_pro"][$li_z];
				$ls_nomsup=$data["nomsup"][$li_z];
				$ls_cedsup=$data["cedsup"][$li_z];
				$ls_civ=$data["civ"][$li_z];
				$ls_nompro=$data["nompro"][$li_z];
				print "<td><a href=\"javascript: aceptar('$ls_codpro','$ls_nomsup','$ls_cedsup','$ls_civ');\">".$ls_codpro."</a></td>";
				print "<td>".$ls_nompro."</td>";
				print "<td>".$ls_nomsup."</td>";
				print "</tr>";			
			}
			print "</table>";
		}
		else
		  {
			if($ls_tipocatalogo=="INSPECTOR")
			{
				$io_msg->message("No existen Ing. Inspectores que cumplan con estos parámetros de búsqueda");
			}
			elseif($ls_tipocatalogo=="RESIDENTE")
			{
				$io_msg->message("No existen Ing. Residentes que cumplan con estos parámetros de búsqueda");
			}
			print $io_funcion->uf_convertirmsg($io_sql->message);
		  }
		$io_sql->free_result($rs_data);
		$io_sql->close();
	}

?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(ls_codpro,ls_nomsup,ls_cedsup,ls_civ)
  {
  	f=document.form1;
    if (f.hidtipocatalogo.value=="INSPECTOR")
	{
		opener.ue_cargarinspector(ls_codpro,ls_nomsup,ls_cedsup,ls_civ);
	}
	else
	{
		if(f.hidtipocatalogo.value=="RESIDENTE")
		{
			opener.ue_cargarresidente(ls_codpro,ls_nomsup,ls_cedsup,ls_civ);
		}
	}
	close();
  }  
 
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
</html>
