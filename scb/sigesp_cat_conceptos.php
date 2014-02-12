<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Conceptos de Movimiento</title>
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
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2">Cat&aacute;logo de Conceptos de Movimiento</td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="10" maxlength="3" style="text-align:center">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="70" maxlength="80" style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
$in        = new sigesp_include();
$con       = $in->uf_conectar();
$io_msg    = new class_mensajes();
$io_sql       = new class_sql($con);
$ds        = new class_datastore();
$ls_codemp = $arr["codemp"];
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion    = $_POST["operacion"];
	 $ls_codigo       = "%".$_POST["codigo"]."%";
	 $ls_denominacion = "%".$_POST["denominacion"]."%";
   }
else
   {
	 $ls_operacion    = "BUSCAR";
	 $ls_codigo       = "%%";
	 $ls_denominacion = "%%";
   }

print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td style=text-align:center>Código </td>";
print "<td style=text-align:center>Denominación</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql     = "SELECT * FROM scb_concepto WHERE codconmov like '".$ls_codigo."' AND denconmov like '".$ls_denominacion."' ";
	 $rs_data    = $io_sql->select($ls_sql);
	 $li_numrows = $io_sql->num_rows($rs_data); 
	 if ($li_numrows>0)
	    {
		  while ($row=$io_sql->fetch_row($rs_data))
			    {
				  print "<tr class=celdas-blancas>";
				  $ls_codcon = $row["codconmov"];
				  $ls_dencon = $row["denconmov"];
				  print "<td style=text-align:center><a href=\"javascript: aceptar('$ls_codcon','$ls_dencon');\">".$ls_codcon."</a></td>";
				  print "<td style=text-align:left>".$ls_dencon."</td>";
				  print "</tr>";			
			    }
		}
	 else
	    {
	      $io_msg->message("No se han definido conceptos !!!");	
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
  function aceptar(codigo,deno)
  {
	opener.document.form1.txtcodconcep.value=codigo;
   opener.document.form1.txtconcepto.value=deno;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_conceptos.php";
  f.submit();
  }
</script>
</html>
