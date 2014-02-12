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
if(!array_key_exists("campo",$_POST))
{
	$ls_campo="cod_pro";
	$ls_orden="ASC";
}
else
{
	$ls_campo=$_POST["campo"];
	$ls_orden=$_POST["orden"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Rubros</title>
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
<input type="hidden" name="campo" id="campo" value="<? print $ls_campo;?>" >
<input type="hidden" name="orden" id="orden" value="<? print $ls_orden;?>">
<?

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
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
	$ls_dentipo="%".$_POST["dentipo"]."%";

}
else
{
	$ls_operacion="";
	$ls_dentipo="";

}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
  </p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Rubros</td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
     <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td><div align="left">
          <input name="dentipo" type="text" id="dentipo"  size="60">
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
 $ls_cadena=" SELECT u.*,c.denominacion as denciclo,r.denominacion as denrenglon,te.id_tipoexplotacion,te.denominacion as ".
 			"dentipoexplotacion  FROM sfc_rubro u,sfc_ciclo c,sfc_renglon r,sfc_tipoexplotacion te".
			" WHERE u.id_ciclo=c.id_ciclo AND u.id_renglon=r.id_renglon AND te.id_tipoexplotacion=r.id_tipoexplotacion AND ".
			" u.denominacion ilike '".$ls_dentipo."' ORDER BY u.id_rubro ASC";
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
					print "<td><a href=javascript:ue_ordenar('codtipo','BUSCAR');><font color=#FFFFFF>ID</font></a></td>";
					print "<td><font color=#FFFFFF>C&oacute;digo</font></a></td>";
					print "<td><font color=#FFFFFF>Denominaci&oacute;n</font></a></td>";
					print "<td><font color=#FFFFFF>Ciclo</font></a></td>";
					print "<td><font color=#FFFFFF>Renglon</font></a></td>";
					print "<td><font color=#FFFFFF>Tipo de Explotaci&oacute;n</font></a></td>";
					$la_tipo=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$la_tipo;
					$totrow=$io_data->getRowCount("id_rubro");

					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$idrubro=$io_data->getValue("id_rubro",$z);
						$codrubromac=$io_data->getValue("cod_rubro",$z);
		                $nomrubro=$io_data->getValue("denominacion",$z);
						$codciclo=$io_data->getValue("id_ciclo",$z);
						$codciclomac=$io_data->getValue("cod_ciclo",$z);
						$nomciclo=$io_data->getValue("denciclo",$z);
						$codrenglon=$io_data->getValue("id_renglon",$z);
						$codrenglonmac=$io_data->getValue("cod_renglon",$z);
		                $nomrenglon=$io_data->getValue("denrenglon",$z);
						$codtipoexplotacion=$io_data->getValue("id_tipoexplotacion",$z);
		                $nomtipoexplotacion=$io_data->getValue("dentipoexplotacion",$z);
		                print "<td><a href=\"javascript: aceptar('$idrubro','$codrubromac','$nomrubro','$codciclo','$codciclomac','$nomciclo','$codrenglon','$codrenglonmac','$nomrenglon','$codtipoexplotacion','$nomtipoexplotacion');\">".$idrubro."</a></td>";
						print "<td align=left>".$codrubromac."</td>";
						print "<td align=left>".$nomrubro."</td>";
						print "<td align=left>".$nomciclo."</td>";
						print "<td align=left>".$nomrenglon."</td>";
						print "<td align=left>". $nomtipoexplotacion."</td>";
						print "</tr>";
					}
				}
				else
				{
					$io_msg->message("No se han registrado Rubros");
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

  function aceptar(idrubro,codrubromac,nomrubro,codciclo,codciclomac,nomciclo,codrenglon,codrenglonmac,nomrenglon,codtipoexplotacion,nomtipoexplotacion)
  {

    opener.ue_cargar_rubro(idrubro,codrubromac,nomrubro,codciclo,codciclomac,nomciclo,codrenglon,codrenglonmac,nomrenglon,codtipoexplotacion,nomtipoexplotacion);
	close();
  }

  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_rubro.php";
  f.submit();
  }

</script>
</html>
