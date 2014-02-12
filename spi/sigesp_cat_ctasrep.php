<?php
session_start();
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
$int_scg=new class_sigesp_int_scg();
$msg=new class_mensajes();
$fun=new class_funciones();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>C&aacute;talogo de Cuentas Presupuestarias de Ingreso</title>
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
  <p align="center">
    <?php
		if(array_key_exists("operacion",$_POST))
		{
			$ls_operacion=$_POST["operacion"];
			$ls_codigo="%".$_POST["codigo"]."%";
			$ls_denominacion="%".$_POST["nombre"]."%";
		}
		else
		{
			$ls_operacion="";
            $ls_codigo="";
			$ls_denominacion="";
		}
		?>
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <br>
  <div align="center">
    <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="3" align="right" class="titulo-celda">Cat&aacute;logo de Cuentas Presupuestaria Ingreso</td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td width="122" height="13">&nbsp;</td>
        <td width="341" height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="135" height="22" align="right">C&oacute;digo</td>
        <td height="22" colspan="2"><div align="left">
          <input name="codigo" type="text" id="codigo" size="22" maxlength="20">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre" size="72">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?php
print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=100 style=text-align:center>C&oacute;digo</td>";
print "<td width=450 style=text-align:center>Denominaci&oacute;n</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql =" SELECT spi_cuenta,denominacion FROM spi_cuentas ".
		     " WHERE codemp = '".$as_codemp."' AND spi_cuenta like '".$ls_codigo."' AND  ".
			 "       denominacion like '".$ls_denominacion."' AND status='C'             ".
			 " ORDER BY  spi_cuenta  ";
	$rs_data = $io_sql->select($ls_sql);
	if ($rs_data==false)
	   {
		 $msg->message($fun->uf_convertirmsg($io_sql->message));
 	   }
	else
	   {
		 $li_numrows = $io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
		    {
			  while($row=$io_sql->fetch_row($rs_data))
			       {
				     $ls_numcue = $row["spi_cuenta"];
				     $ls_dencue = $row["denominacion"];
				     print "<tr class=celdas-blancas>";
					 print "<td width=100 style=text-align:center><a href=\"javascript: aceptar('$ls_numcue','$ls_dencue');\">".$ls_numcue."</a></td>";
				 	 print "<td width=450 style=text-align:left>".$ls_dencue."</td>";
				     print "</tr>";	
			 	   }
				 $io_sql->free_result($rs_data);
			     $io_sql->close();
			}
		 else
		    {
			  print "No se han creado Cuentas de gasto para la programatica seleccionada";
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

  function aceptar(cuenta,deno)
  {
    opener.document.form1.txtcuentades.value=cuenta;
    opener.document.form1.txtcuentades.readOnly=true;
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_ctasrep.php";
	  f.submit();
  }
</script>
</html>