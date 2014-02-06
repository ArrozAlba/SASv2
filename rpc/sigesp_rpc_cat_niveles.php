<?php
session_start();
if (array_key_exists("operacion",$_POST))
   {
     $ls_codniv    = $_POST["txtcodniv"];
	 $ls_desniv    = $_POST["txtdesniv"];
	 $ls_operacion = $_POST["operacion"];	 
   }
else 
   {
     $ls_codniv = "";
	 $ls_desniv = "";  
	 $ls_operacion = "BUSCAR";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Niveles</title>
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
<style type="text/css">
<!--
a:hover {
	color: #006699;
}
-->
</style></head>
<body>
<div align="center">
<p>&nbsp;</p>
<form name="form1" method="post" action="">
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-celda">
      <td height="22" colspan="4"><input name="operacion" type="hidden" id="operacion" value="<?php echo $ls_operacion ?>">
        Cat&aacute;logo de Niveles</td>
    </tr>
    <tr>
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td width="92" height="22" style="text-align:right">C&oacute;digo</td>
      <td height="22" colspan="3"><input name="txtcodniv" type="text" id="txtcodniv" maxlength="3" style="text-align:center"></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Denominaci&oacute;n</td>
      <td height="22" colspan="3"><input name="txtdesniv" type="text" id="txtdesniv" size="65" style="text-align:left"></td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="right"><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar Nivel..." width="20" height="20" border="0">Buscar</a></div></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td width="128" height="13">&nbsp;</td>
      <td width="138" height="13">&nbsp;</td>
      <td width="140" height="13">&nbsp;</td>
    </tr>
  </table>
  <p>
    <?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($ls_conect); 
$io_msg     = new class_mensajes();

if ($ls_operacion=='BUSCAR')
{
  $ls_sql  = " SELECT codniv,desniv,monmincon,monmaxcon 
                 FROM rpc_niveles 
				WHERE codemp='".$_SESSION["la_empresa"]["codemp"]."'
				  AND codniv like '%".$ls_codniv."%'
				  AND desniv like '%".$ls_desniv."%'
				ORDER BY codniv ASC";
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
		    echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			echo "<tr class=titulo-celda>";
			echo "<td width=50  style=text-align:center>C&oacute;digo</td>";
     		echo "<td width=220 style=text-align:center>Descripci&oacute;n</td>";
			echo "<td width=115 style=text-align:center>Monto M&iacute;nimo de Contrataci&oacute;n</td>";
			echo "<td width=115 style=text-align:center>Monto M&aacute;ximo de Contrataci&oacute;n</td>";
			echo "</tr>";  
			while($row=$io_sql->fetch_row($rs_data))
			     {
				   echo "<tr class=celdas-blancas>";
		  		   $ls_codniv    = $row["codniv"];
		  		   $ls_desniv    = $row["desniv"];
		  		   $ld_monmincon = number_format($row["monmincon"],2,',','.');
		  		   $ld_monmaxcon = number_format($row["monmaxcon"],2,',','.');
		  	       echo "<td width=50  style=text-align:center><a href=\"javascript: aceptar('$ls_codniv','$ls_desniv','$ld_monmincon','$ld_monmaxcon' );\">".$ls_codniv."</a></td>";
		           echo "<td width=220 style=text-align:left>".$ls_desniv."</td>";
		           echo "<td width=115 style=text-align:right>".$ld_monmincon."</td>";
		           echo "<td width=115 style=text-align:right>".$ld_monmaxcon."</td>";
		           echo "</tr>";			   
				 }
		    echo "</table>";
			$io_sql->free_result($rs_data);
		  }
       else
	      {
		    $io_msg->message("No se han creado Niveles !!!");
		  }
	 }
}
?></p>
</form>
</div>
</body>
<script language="JavaScript">
  function aceptar(codigoniv,descripcion,montomin,montomax)
  {
    opener.document.form1.txtcodniv.value=codigoniv;
	opener.document.form1.txtcodniv.readOnly=true;
    opener.document.form1.txtdesniv.value=descripcion;
	opener.document.form1.txtmontomin.value=montomin;
	opener.document.form1.txtmontomax.value=montomax;
	close();
  }
  
function ue_buscar()
{
  document.form1.action = "sigesp_rpc_cat_niveles.php";
  document.form1.operacion.value = "BUSCAR";
  document.form1.submit();
}  
</script>
</html>