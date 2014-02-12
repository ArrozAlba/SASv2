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
<title>Cat&aacute;logo de Cajero</title>
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

/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/


if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_nomcli="%".$_POST["nomcli"]."%";
	$ls_codcli=$_POST["txtcodcli"];
}
else
{
/************************************************************************************************************************/
/********************************************** NO SUBMIT ***************************************************************/
/************************************************************************************************************************/
	$ls_operacion="";
	$ls_nomcli="%%";  /* campo oculto para buscar no de nota(numnot) */
	$ls_codcli=$_GET["codcli1"];
   
}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
	
    <input name="txtcodcli" type="hidden" id="txtcodcli" value="<?php print $ls_codcli ?>">
  </p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Notas </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      
      <tr>
        <td width="67"><div align="right">Nombre</div></td>
        <td width="431"><div align="left">
          <input name="nomcli" type="text" id="nomcli"  size="60">
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
$ls_cadena=" SELECT DISTINCT  sfc_cliente.codcli,sfc_cliente.razcli,sfc_nota.numnot,sfc_nota.dennot,sfc_nota.tipnot,sfc_nota.fecnot,sfc_nota.monto,sfc_nota.estnota FROM sfc_cliente,sfc_nota ".
			" WHERE sfc_nota.estnota='P'  AND sfc_nota.tipnot='CXP' AND sfc_nota.codcli=sfc_cliente.codcli and sfc_nota.codcli='".$ls_codcli."' and sfc_cliente.razcli like '".$ls_nomcli."'";

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
					print "<td><font color=#FFFFFF>No. Nota</font></td>";
					print "<td><font color=#FFFFFF>Nombre Cliente</font></td>";
					print "<td><font color=#FFFFFF>Fecha</font></td>";
					print "<td><font color=#FFFFFF>Denominación</font></td>";
					print "<td><font color=#FFFFFF>Monto</font></td>";
					$la_nota=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$la_nota;
					$totrow=$io_data->getRowCount("numnot");
						
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codcli=$io_data->getValue("codcli",$z);
						$nomcli=$io_data->getValue("razcli",$z);
						$numnot=$io_data->getValue("numnot",$z);
						$dennot=$io_data->getValue("dennot",$z);
						$tipnot=$io_data->getValue("tipnot",$z);
		                $fecnot=$io_data->getValue("fecnot",$z);
						$fecnot=date('d/m/Y');
						$monto=$ld_monto=number_format($io_data->getValue("monto",$z),2, ',', '.');
						$estnot=$io_data->getValue("estnot",$z);
						
						print "<td><a href=\"javascript: aceptar('$nomcli','$codcli','$numnot','$dennot','$tipnot','$fecnot','$monto','$estnot');\">".$numnot."</a></td>";
						print "<td align=left>".$nomcli."</td>";
						print "<td align=left>".$fecnot."</td>";
						print "<td align=left>".$dennot."</td>";
						print "<td align=left>".$monto."</td>";
						print "</tr>";			
						
					}
					/*print $codcli."/".$numnot."/".$dennot."/".$tipnot."/".$fecnot.$monto;*/
				}
				else
				{
					$io_msg->message("No se han registrado Notas de crédito");
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

  function aceptar(nomcli,codcli,numnot,dennot,tipnot,fecnot,monto,estnot)
  {
    
    opener.ue_cargarnota(nomcli,codcli,numnot,dennot,tipnot,fecnot,monto,estnot);
	close();
  }
 
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_notafactura.php";
  f.submit();
  }
 
</script>
</html>
