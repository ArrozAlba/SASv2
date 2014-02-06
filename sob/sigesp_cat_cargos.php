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
	$ls_campo="codcar";
	$ls_orden="ASC";
}
else
{
	$ls_campo=$_POST["campo"];
	$ls_orden=$_POST["orden"];
}
if(array_key_exists("tipdes",$_GET))
{
	$ls_tipdes=$_GET["tipdes"];
}
else
{
	if(array_key_exists("tipdes",$_POST))
		$ls_tipdes=$_POST["tipdes"];
	else
		$ls_tipdes="";
}
if($ls_tipdes=="VALUACION")
{
	if(array_key_exists("codasi",$_GET))
	{
		$ls_codasi=$_GET["codasi"];
	}
	else
	{
		if(array_key_exists("codasi",$_POST))
			$ls_codasi=$_POST["codasi"];
		else
			$ls_codasi="";
	}

}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cargos</title>
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
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
$io_funsob=new sigesp_sob_c_funciones_sob();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$ls_codemp=$la_datemp["codemp"];


?>
  <input name="tipdes" type="hidden" id="tipdes" value="<?php print $ls_tipdes; ?>">
  <input name="codasi" type="hidden" id="codasi" value="<?php print $ls_codasi; ?>">
  <p align="center">
   
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="500" colspan="2" class="titulo-celda">Cat&aacute;logo de Cargos</td>
    	</tr>
	 </table>
	 <br>
	 <br>

<?
if($ls_tipdes=="VALUACION")
{
	$ls_sql="SELECT sigesp_cargos.codcar,sigesp_cargos.dencar,sigesp_cargos.porcar,sigesp_cargos.formula,".	
			"       sigesp_cargos.codestpro,sigesp_cargos.estcla,sigesp_cargos.spg_cuenta".
			"  FROM sigesp_cargos,sob_cargoasignacion".
			" WHERE sigesp_cargos.codemp='".$ls_codemp."'".
			"   AND sob_cargoasignacion.codasi like '%".$ls_codasi."%'".
			"   AND sigesp_cargos.codemp=sob_cargoasignacion.codemp".
			"   AND sigesp_cargos.codcar=sob_cargoasignacion.codcar".
			" ORDER by $ls_campo $ls_orden";
}
else
{
	$ls_sql=" SELECT codcar,dencar,porcar,formula,codestpro,spg_cuenta".
			   " FROM sigesp_cargos".
			   " WHERE codemp='".$ls_codemp."' Order by $ls_campo $ls_orden";
}

			$rs_datauni=$io_sql->select($ls_sql);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				//$io_msg->message(io_funcion->uf_convertirmsg(io_sql->message));
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni))
				{
					print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><a href=javascript:ue_ordenar('codcar','');><font color=#FFFFFF>Código</font></a></td>";
					print "<td><a href=javascript:ue_ordenar('dencar','');><font color=#FFFFFF>Denominación</font></a></td>";
					print "<td><a href=javascript:ue_ordenar('porcar','');><font color=#FFFFFF>Porcentaje</font></a></td>";
					$la_unidades=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$la_unidades;
					$totrow=$io_data->getRowCount("codcar");
						
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codcar=$io_data->getValue("codcar",$z);
						$dencar=$io_data->getValue("dencar",$z);
						$porcar=$io_funsob->uf_convertir_numerocadena($io_data->getValue("porcar",$z));
						$forcar=$io_data->getValue("formula",$z);
						$ls_codestpro=$io_data->getValue("codestpro",$z);
						$ls_estcla=$io_data->getValue("estcla",$z);
						$ls_spgcuenta=$io_data->getValue("spg_cuenta",$z);
						if($ls_tipdes=="VALUACION")
						{
							print "<td align=center><a href=\"javascript: aceptar_valuacion('$codcar','$dencar','$forcar','$ls_codestpro','$ls_spgcuenta','$ls_estcla');\">".$codcar."</a></td>";
						}
						else
						{
							print "<td align=center><a href=\"javascript: aceptar('$codcar','$dencar','$forcar','$ls_codestpro','$ls_spgcuenta');\">".$codcar."</a></td>";
						}
						print "<td align=left>".$dencar."</td>";
						print "<td align=right>".$porcar."</td>";
					    print "</tr>";			
					}
				}
				else
				{
					$io_msg->message("No se han registrado Cargos");
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
  function aceptar(codcar,nomcar,formula,codestpro,spg_cuenta)
  {
    opener.ue_cargarcargo(codcar,nomcar,formula,codestpro,spg_cuenta);
	close();
  }

  function aceptar_valuacion(codcar,nomcar,formula,codestpro,spg_cuenta,ls_estcla)
  {
    opener.ue_cargarcargo(codcar,nomcar,formula,codestpro,spg_cuenta,ls_estcla);
	close();
  }
  
</script>
</html>
