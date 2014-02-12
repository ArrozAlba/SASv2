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
$ls_codtie=$_SESSION["ls_codtienda"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Articulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
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
.style6 {color: #000000}
-->
</style></head>

<body>
<form name="form1" method="post" action="">

<?

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
$funsob =   new sigesp_sob_c_funciones_sob();
$io_datastore= new class_datastore();
$io_utilidad = new sigesp_sfc_class_utilidades();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$ls_codemp=$la_datemp["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_denart="%".$_POST["denart"]."%";
}
else
{
	$ls_operacion="";
	$ls_denart="";
}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">

</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Producto </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">

      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="86"><div align="right">Descripcion</div></td>
        <td width="412"><div align="left">
          <input name="denart" type="text" id="denart" size="60" maxlength="225">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?


if($ls_operacion=="BUSCAR")
{
 /*$ls_cadena=" SELECT codart,denart,ultcosart,cosproart,exiart, ".
"      (SELECT denunimed FROM sim_unidadmedida".
				"        WHERE sim_unidadmedida.codunimed = sim_articulo.codunimed) AS denunimed,".
				"      (SELECT unidad FROM sim_unidadmedida".
				"        WHERE sim_unidadmedida.codunimed = sim_articulo.codunimed) AS unidad".
			" FROM sim_articulo ".
			" WHERE codemp ilike '".$ls_codemp."'  AND denart ilike '".$ls_denart."' AND substr(codart,5,1)='V'";*/

			$ls_cadena="SELECT s.codart,s.denart,(SELECT denunimed FROM sim_unidadmedida WHERE sim_unidadmedida.codunimed = s.codunimed) " .
					" AS denunimed, (SELECT unidad FROM sim_unidadmedida WHERE sim_unidadmedida.codunimed = s.codunimed)" .
					" AS unidad FROM sim_articulo s WHERE s.codemp ilike '".$ls_codemp."' AND s.estatus='t' AND s.denart ilike '".$ls_denart."' AND substr(s.codart,5,1)='V' ".
					" ORDER BY s.codart";
			//print $ls_cadena;
			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni))
				{
					print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><font color=#FFFFFF>C&oacute;digo</font></td>";
					print "<td><font color=#FFFFFF>Descripci&oacute;n</font></td>";
					print "<td><font color=#FFFFFF>Presentaci&oacute;n</font></td>";
					print "<td><font color=#FFFFFF>Unidad</font></td>";
					$la_tienda=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$la_tienda;
					$totrow=$io_data->getRowCount("codart");


					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codart=$io_data->getValue("codart",$z);
		                $denart=$io_data->getValue("denart",$z);
						$exis=$io_data->getValue("existencia",$z);
						$cosart=$funsob->uf_convertir_numerocadena($io_data->getValue("ultcosart",$z));
						$cosproart=$funsob->uf_convertir_numerocadena($io_data->getValue("cosproart",$z));
						$denunidad=$io_data->getValue("denunimed",$z);
						$unidad=$io_data->getValue("unidad",$z);
						print "<td><a href=\"javascript: aceptar('$codart','$denart','$cosart','$cosproart');\">".$codart."</a></td>";
						print "<td align=left>".$denart."</td>";
						print "<td align=left>".$denunidad."</td>";
						print "<td align=left>".$unidad."</td>";
						print "</tr>";
					}
				}
				else
				{
					$io_msg->message("No se han registrado Articulos");
				}
		}
}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(codart,denart,cosart,cospro)
  {
    opener.ue_cargarcliente(codart,denart,cosart,cospro);
	close();
  }

  function ue_search()
  {
    f=document.form1;
    f.operacion.value="BUSCAR";
    f.action="sigesp_cat_articulo.php";
    f.submit();
  }

</script>
</html>
