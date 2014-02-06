<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "close();";
	 print "opener.document.form1.submit();";
	 print "</script>";		
   }

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_codban    = $_POST["txtcodban"];
	 $ls_denban    = $_POST["txtdenban"];
   }
else
   {
	 $ls_operacion = "BUSCAR";
	 $ls_codban    = "";
	 $ls_denban    = "";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Bancos Tesoreria</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
	color: #006699;
}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
  <p align="center">&nbsp;</p>
  	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion" value="<?php echo $ls_operacion ?>">
        Cat&aacute;logo de Bancos Tesoreria </td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="82" height="22" style="text-align:right">C&oacute;digo</td>
        <td width="416" height="22" style="text-align:left"><input name="txtcodban" type="text" id="txtcodban" maxlength="3" value="<?php echo $ls_codban ?>" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" style="text-align:left"><input name="txtdenban" type="text" id="txtdenban" value="<?php echo $ls_denban ?>" size="70" style="text-align:left"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
<div align="center">
<p><br>
<?php
echo "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td height=22 width=100 style=text-align:center>Banco</td>";
echo "<td height=22 width=200 style=text-align:center>Denominación</td>";
echo "<td height=22 width=100 style=text-align:center>Cuenta</td>";
echo "<td height=22 width=200 style=text-align:center>Denominación</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql="SELECT scb_banco.codban,scb_banco.nomban,scb_ctabanco.ctaban,scb_ctabanco.dencta,scb_ctabanco.sc_cuenta 
			    FROM scb_banco, scb_ctabanco
			   WHERE scb_banco.codemp='".$ls_codemp."' 
			     AND scb_banco.esttesnac=1 
			 	 AND scb_banco.codban like '%".$ls_codban."%' 
			     AND scb_banco.nomban like '%".$ls_denban."%' 
				 AND scb_banco.codemp=scb_ctabanco.codemp 
				 AND scb_banco.codban=scb_ctabanco.codban 
		  	   ORDER BY scb_banco.codban ASC";

	 $rs_data = $io_sql->select($ls_sql);
	 if ($rs_data===false)
	    {
	      $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
	    }
	 else
	    {
	      $li_numrows = $io_sql->num_rows($rs_data);
	      if ($li_numrows>0)
		     {
			   while ($row=$io_sql->fetch_row($rs_data))
			         {
						echo "<tr class=celdas-blancas>";
						$ls_codban    = $row["codban"];
						$ls_denban    = $row["nomban"];
						$ls_scgcta    = trim($row["sc_cuenta"]);
						$ls_codctaban = $row["ctaban"];
						$ls_denctaban = $row["dencta"];
						echo "<td width=100 style=text-align:center><a href=\"javascript: aceptar('$ls_codban','$ls_denban','$ls_codctaban','$ls_denctaban','$ls_scgcta');\">".$ls_codban."</a></td>";
						echo "<td width=200 style=text-align:left>".$ls_denban."</td>";
						echo "<td width=100 style=text-align:center>".$ls_codctaban."</td>";
						echo "<td width=200 style=text-align:left>".$ls_denctaban."</td>";
						echo "</tr>";			
					 }
			 } 
	      else
		     {
			   $io_msg->message("No se han definido registros !!!");
			 }
	    }
   }
echo "</table>";
?>
</p>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(ls_codban,ls_denban,ls_ctaban,ls_dencta,ls_scgcta)
  {
    opener.document.form1.txtcodbansig.value       = ls_codban;
    opener.document.form1.txtnombansig.value       = ls_denban;
	opener.document.form1.txtctatesoreria.value    = ls_ctaban;
    opener.document.form1.txtdenctatesoreria.value = ls_dencta;
	opener.document.form1.txtcuenta_scg.value      = ls_scgcta;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_bancos_tesoreria.php";
  f.submit();
  }
</script>
</html>