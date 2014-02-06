<?php
session_start();
ini_set('max_execution_time ','0');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Contables</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">&nbsp;</p>
  <br>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="3" align="right" class="titulo-celda"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de Cuentas Contables </td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td width="271">&nbsp;</td>
        <td width="138">&nbsp;</td>
      </tr>
      <tr>
        <td width="89" height="22" align="right">Cuenta</td>
        <td height="22" colspan="2" style="text-align:left"><input name="codigo" type="text" id="codigo" style="text-align:center" size="30" maxlength="25" onKeyPress="return keyRestrict(event,'1234567890');"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" colspan="2" style="text-align:left"><div align="left">
          <input name="nombre" type="text" id="nombre" size="65" maxlength="500">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a>
            <input name="obj" type="hidden" id="obj" value="<? print $ls_obj?>">
        </div></td>
      </tr>
    </table>
	<p>
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_scgcta    = $_POST["codigo"]."%";
	 $ls_denctascg = "%".$_POST["nombre"]."%";
   }
else
   {
	 $ls_operacion="";
   }
echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center width=120>Cuenta</td>";
echo "<td style=text-align:center width=380>Denominación</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql =" SELECT TRIM(sc_cuenta) as sc_cuenta, denominacion, status
		          FROM scg_cuentas
		         WHERE codemp = '".$ls_codemp."'
				   AND sc_cuenta like '".$ls_scgcta."'
				   AND denominacion like '".$ls_denctascg."'
				   AND status='C'
				 ORDER BY sc_cuenta";

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
			   while (!$rs_data->EOF)
				     {
					   echo "<tr class=celdas-blancas>";
					   $ls_scgcta    = trim($rs_data->fields["sc_cuenta"]);
					   $ls_denscgcta = ltrim($rs_data->fields["denominacion"]);
					   $ls_estscgcta = trim($rs_data->fields["status"]);
					   echo "<td style=text-align:center width=120><a href=\"javascript: aceptar('$ls_scgcta','$ls_denscgcta','$ls_estscgcta');\">".$ls_scgcta."</a></td>";
				       echo "<td style=text-align:left   width=380 title='".$ls_denscgcta."'>".$ls_denscgcta."</td>";
				       echo "</tr>";
					   $rs_data->MoveNext();					 
					 }
               unset($rs_data);
			 }
		  else
		     {
			   $io_msg->message("No se han creado Cuentas Contables !!!");
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
  function aceptar(cuenta,d,status)
  {
    opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=d;
	 close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_scg.php";
	  f.submit();
  }
</script>
</html>