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
	$ls_campo="codpro";
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
<title>Cat&aacute;logo de Organismos Ejecutores</title>
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
require_once("class_folder/sigesp_sob_c_unidad.php");
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$ls_codemp=$la_datemp["codemp"];


?>
  <p align="center">
   
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="500" colspan="2" class="titulo-celda">Cat&aacute;logo de Organismos Ejecutores de Obras </td>
    	</tr>
	 </table>
	 <br>
	 <br>
    <?

print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td><a href=javascript:ue_ordenar('codpro','');><font color=#FFFFFF>Código</font></a></td>";
print "<td><a href=javascript:ue_ordenar('nompro','');><font color=#FFFFFF>Nombre</font></a></td>";

$ls_cadena=" SELECT codpro,nompro,telpro,dirpro,nomresppro,faxpro,emapro ".
			" FROM sob_propietario".
			" WHERE codemp='".$ls_codemp."' Order by $ls_campo $ls_orden";

			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni))
				{
					$la_unidades=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$la_unidades;
					$totrow=$io_data->getRowCount("codpro");
						
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codpro=$io_data->getValue("codpro",$z);
						$nompro=$io_data->getValue("nompro",$z);
						$telpro=$io_data->getValue("telpro",$z);
						$dirpro=$io_data->getValue("dirpro",$z);
						$nomresppro=$io_data->getValue("nomresppro",$z);
						$faxpro=$io_data->getValue("faxpro",$z);
						$emapro=$io_data->getValue("emapro",$z);
						print "<td  align=center><a href=\"javascript: aceptar('$codpro','$nompro','$telpro','$dirpro','$nomresppro','$faxpro','$emapro');\">".$codpro."</a></td>";
						print "<td align=left>".$nompro."</td>";
					    print "</tr>";			
					}
				}
				else
				{
					$io_msg->message("No se han creado Organismos que cumplan con estos parámetros de búsqueda");
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
  function aceptar(cod,nom,tel,dir,res,fax,ema)
  {
    opener.ue_cargarpropietario(cod,nom,tel,dir,res,fax,ema);
	close();
  }
  
</script>
</html>
