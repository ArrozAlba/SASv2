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
	$ls_campo="u.coduni";
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
<title>Cat&aacute;logo de Unidades de Medida</title>
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
$io_cunid=new sigesp_sob_c_unidad();
$ls_codemp=$la_datemp["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codtun="%".$_POST["cmbtipouni"]."%";
	$ls_nomuni="%".$_POST["nomuni"]."%";
	$ls_tipounidad=$_POST["cmbtipouni"];
	$ls_nomunidad=$_POST["nomuni"];

}
else
{
	$ls_operacion="";
	$ls_tipounidad="";
	$ls_nomunidad="";
}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="600" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Unidades de Medida </td>
    	</tr>
	 </table>
	 <br>
	 <table width="600" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="28"><div align="right">Tipo Unidad </div></td>
        <td width="431"><div align="left">
          <?Php
           $lb_vali=$io_cunid->uf_llenarcombo_tipouni(&$la_tipoun);
		   
		   if($lb_vali)
		   {
		    $io_data->data=$la_tipoun;
		    $totrow=$io_data->getRowCount("codtun");
		   }
		   ?>
             <select name="cmbtipouni" id="cmbtipouni">
			 <option value="">Seleccione</option>
         <?Php
			for($z=1;$z<=$totrow;$z++)
			 {
			  $ls_tipoun=$io_data->getValue("codtun",$z);
		      $ls_nombreun=$io_data->getValue("nomtun",$z);
		      if ($ls_tipoun==$ls_tipounidad)
			   {
				print "<option value='$ls_tipoun' selected>$ls_nombreun</option>";
			   }
		       else
			   {
				print "<option value='$ls_tipoun'>$ls_nombreun</option>";
			   }
		      }      
	        ?>
          </select>
                  <input name="hidestado" type="hidden" id="hidestado"  value="<? print $ls_tipounidad ?>">
</div></td>
      </tr>
      <tr>
        <td height="26"><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="nomuni" type="text" id="nomuni" value="<? print $ls_nomunidad;?>" size="60">
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

 $ls_cadena=" SELECT u.coduni,u.codtun,t.nomtun,u.nomuni,u.desuni ".
			" FROM sob_unidad u, sob_tipounidad t ".
			" WHERE u.codemp='".$ls_codemp."' AND u.codtun=t.codtun AND u.codtun like '".$ls_codtun."'  AND u.nomuni like '".$ls_nomuni."' ORDER BY $ls_campo $ls_orden";
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
					print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><a href=javascript:ue_ordenar('u.coduni','BUSCAR');><font color=#FFFFFF>Código</font></a></td>";
					print "<td><a href=javascript:ue_ordenar('u.nomuni','BUSCAR');><font color=#FFFFFF>Nombre</font></a></td>";
					print "<td><a href=javascript:ue_ordenar('t.nomtun','BUSCAR');><font color=#FFFFFF>Tipo</font></a></td>";
					$la_unidades=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$la_unidades;
					$totrow=$io_data->getRowCount("coduni");
						
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$coduni=$io_data->getValue("coduni",$z);
		                $codtun=$io_data->getValue("codtun",$z);
						$nomtun=$io_data->getValue("nomtun",$z);
						$nomuni=$io_data->getValue("nomuni",$z);
						$desuni=$io_data->getValue("desuni",$z);
						print "<td><a href=\"javascript: aceptar('$coduni','$codtun','$nomtun','$nomuni','$desuni');\">".$coduni."</a></td>";
						print "<td>".$nomuni."</td>";
						print "<td>".$nomtun."</td>";
					    print "</tr>";			
					}
				}
				else
				{
					$io_msg->message("No se han creado Unidades de Medida");
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
  function aceptar(coduni,codtun,nomtun,nomuni,desuni)
  {
  	codigoaux=coduni.substring(0,1);
	if(codigoaux=="-")
		alert("Seleccione una Unidad válida!!!");
	else
	{
    	opener.ue_cargarunidades(coduni,codtun,nomtun,nomuni,desuni);
		close();
	}
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_unidades.php";
  f.submit();
  }
</script>
</html>
