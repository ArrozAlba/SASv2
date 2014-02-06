<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	echo "<script language=JavaScript>";
	echo "close();";
	echo "opener.document.form1.submit();";
	echo "</script>";		
}
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_cedben    = $_POST["codigo"];
	 $ls_nomben    = $_POST["nombre"];
   }
else
   {
 	 $ls_operacion = "";
	 $ls_cedben    = "";
	 $ls_nomben    = "";
   }
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
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion" value="<?php echo $ls_operacion ?>">
        Cat&aacute;logo de Beneficiarios</td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="67" height="22" style="text-align:right">C&oacute;digo</td>
        <td width="431" height="22" style="text-align:left"><input name="codigo" type="text" id="codigo" style="text-align:center" maxlength="10">        
        </td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Nombre</td>
        <td height="22" style="text-align:left"><input name="nombre" type="text" id="nombre" style="text-align:left" size="75"></td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
    <div align="center"><br>
<?php
echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td height=22 width=100 style=text-align:center>Cédula</td>";
echo "<td height=22 width=400 style=text-align:center>Nombre del Beneficiario</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql = "SELECT rpc_beneficiario.ced_bene,rpc_beneficiario.nombene,rpc_beneficiario.apebene,
	                   rpc_beneficiario.codban,scb_banco.nomban,rpc_beneficiario.ctaban
			      FROM rpc_beneficiario, scb_banco
			     WHERE rpc_beneficiario.codemp='".$_SESSION["la_empresa"]["codemp"]."' 
				   AND rpc_beneficiario.codemp=scb_banco.codemp 
				   AND rpc_beneficiario.ced_bene like '%".$ls_cedben."%' 
			       AND rpc_beneficiario.nombene like '%".$ls_nomben."%' 
				   AND rpc_beneficiario.ced_bene<>'----------' 
				   AND rpc_beneficiario.codban=scb_banco.codban
				 ORDER BY rpc_beneficiario.ced_bene ASC";

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
						$ls_cedben = trim($row["ced_bene"]);
						$ls_nomben = $row["nombene"];
						$ls_apeben = $row["apebene"];
						if (!empty($ls_apeben))
						   {
						     $ls_nomben = $ls_nomben.', '.$ls_apeben;
						   }
						$ls_codban = $row["codban"];
						$ls_nomban = $row["nomban"];
						$ls_ctaban = $row["ctaban"];
			            echo "<td width=100 style=text-align:center><a href=\"javascript: aceptar('$ls_cedben','$ls_nomben','$ls_codban','$ls_nomban','$ls_ctaban');\">".$ls_cedben."</a></td>";
						echo "<td width=400 style=text-align:left>".$ls_nomben."</td>";
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
</div>
</div>
</form>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(ls_cedben,ls_nomben,ls_codban,ls_nomban,ls_ctaban)
  {
    opener.document.form1.txtprovbene.value  = ls_cedben;
    opener.document.form1.txtdesproben.value = ls_nomben;
	opener.document.form1.txtdenban.value    = ls_nomban;
	opener.document.form1.txtcuenta.value    = ls_ctaban;
	opener.document.form1.txtcodban.value    = ls_codban;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_bene_op.php";
  f.submit();
  }  
</script>
</html>