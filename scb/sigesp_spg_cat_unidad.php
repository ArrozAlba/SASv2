<?php
session_start();
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion = $_POST["operacion"];
	 $ls_coduniadm = $_POST["codigo"];
	 $ls_denuniadm = $_POST["denominacion"];
   }
else
   {
     $ls_operacion = "";
	 $ls_coduniadm = "";
	 $ls_denuniadm = "";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Unidades Administradoras</title>
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
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">&nbsp;</p>
  	 <br>
	 <table width="564" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="22" colspan="2" class="titulo-celda">Cat&aacute;logo de Unidades Administradoras
          <input name="operacion" type="hidden" id="operacion"></td>
       </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="111" height="22" style="text-align:right">C&oacute;digo</td>
        <td width="451" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" maxlength="5" style="text-align:center">        
        </div></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" style="text-align:left"><input name="denominacion" type="text" id="denominacion" size="75" maxlength="254" style="text-align:left"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	 <div align="center"><br>
       <?php
print "<table width=565 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=65 style=text-align:center>Código</td>";
print "<td width=200 style=text-align:center>Denominación</td>";
print "<td width=150 style=text-align:center>Unidad Central</td>";
print "<td width=150 style=text-align:center>Responsable</td>";
print "</tr>";

if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql="SELECT coduac,denuac,tipuac,resuac
			    FROM spg_ministerio_ua
			   WHERE codemp='".$ls_codemp."' 
			     AND coduac like '%".$ls_coduniadm."%' 
			 	 AND denuac like '%".$ls_denuniadm."%'" ;
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
						print "<tr class=celdas-blancas>";
						$ls_coduniadm = $row["coduac"];
						$ls_denuniadm = $row["denuac"];
						$ls_uniadmcen = $row["tipuac"];
						$ls_resuniadm = $row["resuac"];
						print "<td width=65 style=text-align:center><a href=\"javascript: aceptar('$ls_coduniadm','$ls_denuniadm','$ls_uniadmcen','$ls_resuniadm');\">".$ls_coduniadm."</a></td>";
						print "<td width=200 style=text-align:left>".$ls_denuniadm."</td>";
						print "<td width=150 style=text-align:center>".$ls_uniadmcen."</td>";
						print "<td width=150 style=text-align:left>".$ls_resuniadm."</td>";
						print "</tr>";			
					 }
			 } 
	      else
		     {
			   $io_msg->message("No se han definido registros !!!");
			 }
	    }
   }
print "</table>";
?>
</div>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(ls_coduniadm,ls_denuniadm,ls_uniadmcen,ls_resuniadm)
  {
    opener.document.form1.txtcoduniadm.value = ls_coduniadm;
    opener.document.form1.txtdenuniadm.value = ls_denuniadm;
	opener.document.form1.txtestuac.value    = ls_uniadmcen;
	opener.document.form1.txtresuac.value    = ls_resuniadm;
	close();
  }
  
function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_spg_cat_unidad.php";
	f.submit();
}
</script>
</html>