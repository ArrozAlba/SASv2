<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Beneficiarios</title>
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
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de Beneficiarios        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="88" height="22" style="text-align:right">C&eacute;dula/C&oacute;digo</td>
        <td width="410" height="22" style="text-align:left"><input name="codigo" type="text" id="codigo" size="15" maxlength="10" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Nombre</td>
        <td height="22" style="text-align:left"><input name="nombre" type="text" id="nombre"  size="70" maxlength="254" style="text-align:left"></td>
      </tr>
	  <tr>
        <td height="22" style="text-align:right">Apellido</td>
        <td height="22" style="text-align:left"><input name="apellido" type="text" id="apellido" size="70" maxlength="254" style="text-align:left"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></div></td>
      </tr>
    </table>
    <p align="center">
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($ls_conect);
$io_msg     = new class_mensajes();

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_cedben    = "%".$_POST["codigo"]."%";
	 $ls_nomben    = "%".$_POST["nombre"]."%";
	 $ls_apeben    = "%".$_POST["apellido"]."%";
   }
else
   {
     $ls_operacion="";
   }
   
echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center width=100>C&eacute;dula</td>";
echo "<td style=text-align:center width=400>Nombre</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql = "SELECT TRIM(ced_bene) AS ced_bene,LTRIM(nombene) AS nombene,LTRIM(apebene) AS apebene
	              FROM rpc_beneficiario 
				 WHERE codemp='".$_SESSION["la_empresa"]["codemp"]."'
			       AND ced_bene like '".$ls_cedben."'
		 	       AND nombene like '".$ls_nomben."'
			       AND apebene like '".$ls_apeben."'
		           AND ced_bene <>'----------'
	             ORDER BY ced_bene";

     $rs_data = $io_sql->select($ls_sql);
	 if ($rs_data===false)
	    {
		  $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
	    }
     else
	    {
		  $li_totrows = $io_sql->num_rows($rs_data);
		  if ($li_totrows>0)
		     {
			   while (!$rs_data->EOF)
				     {
					   echo "<tr class=celdas-blancas>";
					   $ls_cedben = $rs_data->fields["ced_bene"];
					   $ls_nomben = $rs_data->fields["nombene"];
					   $ls_apeben = $rs_data->fields["apebene"];
					   if (!empty($ls_apeben))
					      {
						    $ls_nomben = $ls_nomben.', '.$ls_apeben;
						  }
					   echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_cedben','$ls_nomben');\">".$ls_cedben."</a></td>";
			           echo "<td style=text-align:left   width=400 title='".$ls_nomben."'>".$ls_nomben."</td>";
					   echo "</tr>";
					   $rs_data->MoveNext();						
                     }
             }
          else
		     {
			   $io_msg->message("No se han Definido Beneficiarios !!!");
			 }
		}
   }
echo "</table>";
?>
</p>
</form>
</body>
<script language="JavaScript">
  function aceptar(as_cedben,as_nomben)
  {
    opener.document.form1.txtprovbene.value  = as_cedben;
    opener.document.form1.txtnomproben.value = as_nomben;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_catdinamic_bene.php";
  f.submit();
  }
</script>
</html>
