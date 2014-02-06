<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Transferencias</title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hidstatus" type="hidden" id="hidstatus">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Transferencias </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="1" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="80"><div align="right">Numero </div></td>
        <td width="418" height="22"><div align="left">
          <input name="txtnumtra" type="text" id="txtnumtra" maxlength="15">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Fecha</div></td>
        <td height="22"><div align="left"><input name="txtfecemi" type="text" id="txtfecemi" size="20" maxlength="12" datepicker="true">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	$in=     new sigesp_include();
	$con=    $in->uf_conectar();
	$ds=     new class_datastore();
	$io_sql= new class_sql($con);
	$io_func=new class_funciones();
	$io_msg= new class_mensajes();
	
	$arre=$_SESSION["la_empresa"];
	$ls_gestor=   $_SESSION["ls_gestor"];
	$ls_codemp=$arre["codemp"];

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_numtra="%".$_POST["txtnumtra"]."%";
		$ld_fecemi="%".$_POST["txtfecemi"]."%";
		if ($ld_fecemi!="%%")
		{
			$porc="%";
			$ld_fecemi=str_replace($porc,"",$ld_fecemi);
			$ld_fecemi=$io_func->uf_convertirdatetobd($ld_fecemi);
		}
		
		$ls_status="%".$_POST["hidstatus"]."%";
	}
	else
	{
		$ls_operacion="";
	
	}
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td width=50>Número</td>";
	print "<td width=50>Fecha</td>";
	print "<td width=140>Origen</td>";
	print "<td width=140>Destino</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{   
	   if($ls_gestor=="INFORMIX")
	   {
	     if ($ld_fecemi=="%%")
		  {
	        $ls_sql="SELECT siv_transferencia.*, ".
				"      (SELECT nomfisalm FROM siv_almacen".
				"        WHERE siv_almacen.codalm = siv_transferencia.codalmori) AS nomfisori,".
				"      (SELECT nomfisalm FROM siv_almacen".
				"        WHERE siv_almacen.codalm = siv_transferencia.codalmdes) AS nomfisdes".
			    "  FROM	siv_transferencia".
				" WHERE codemp = '".$ls_codemp."'".
				"   AND numtra like '".$ls_numtra."'";
		 }
		 else
		  {
	        $ls_sql="SELECT siv_transferencia.*, ".
				"      (SELECT nomfisalm FROM siv_almacen".
				"        WHERE siv_almacen.codalm = siv_transferencia.codalmori) AS nomfisori,".
				"      (SELECT nomfisalm FROM siv_almacen".
				"        WHERE siv_almacen.codalm = siv_transferencia.codalmdes) AS nomfisdes".
			    "  FROM	siv_transferencia".
				" WHERE codemp = '".$ls_codemp."'".
				"   AND numtra like '".$ls_numtra."'".
				"   AND fecemi = '".$ld_fecemi."'";
		}
		
	   }
	 else
	   {
	      if ($ld_fecemi=="%%")
		   {
		      $ls_sql="SELECT siv_transferencia.*, ".
				 "      (SELECT nomfisalm FROM siv_almacen".
				 "        WHERE siv_almacen.codalm = siv_transferencia.codalmori) AS nomfisori,".
				 "      (SELECT nomfisalm FROM siv_almacen".
				 "        WHERE siv_almacen.codalm = siv_transferencia.codalmdes) AS nomfisdes".
			     "  FROM	siv_transferencia".
				 " WHERE codemp = '".$ls_codemp."'".
				 "   AND numtra like '".$ls_numtra."'";
		   }
		   else
		     {
                $ls_sql="SELECT siv_transferencia.*, ".
				   "      (SELECT nomfisalm FROM siv_almacen".
				   "        WHERE siv_almacen.codalm = siv_transferencia.codalmori) AS nomfisori,".
				   "      (SELECT nomfisalm FROM siv_almacen".
				   "        WHERE siv_almacen.codalm = siv_transferencia.codalmdes) AS nomfisdes".
			       "  FROM	siv_transferencia".
				   " WHERE codemp = '".$ls_codemp."'".
				   "   AND numtra like '".$ls_numtra."'".
				   "   AND fecemi like '".$ld_fecemi."'";
			 }	 
	   }	
		$rs_cta=$io_sql->select($ls_sql);
		$data=$rs_cta;
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
	
			$totrow=$ds->getRowCount("numtra");
		
			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$ls_numtra=    $data["numtra"][$z];
				$ld_fecemi=    $data["fecemi"][$z];
				$ld_fecemi=    $io_func->uf_convertirfecmostrar($ld_fecemi);
				$ls_obstra=    $data["obstra"][$z];
				$ls_codusu=    $data["codusu"][$z];
				$ls_codalmori= $data["codalmori"][$z];
				$ls_codalmdes= $data["codalmdes"][$z];
				$ls_nomfisori= $data["nomfisori"][$z];
				$ls_nomfisdes= $data["nomfisdes"][$z];
				print "<td><a href=\"javascript: aceptar('$ls_numtra','$ld_fecemi','$ls_obstra','$ls_codusu','$ls_codalmori','$ls_codalmdes',";
				print "'$ls_nomfisori','$ls_nomfisdes','$ls_status');\">".$ls_numtra."</a></td>";
				print "<td>".$ld_fecemi."</td>";
				print "<td>".$data["nomfisori"][$z]."</td>";
				print "<td>".$data["nomfisdes"][$z]."</td>";
				print "</tr>";			
			}
		}
		else
		{
			$io_msg->message("No hay registros");
		}
	}
	print "</table>";
?>
</div>
<input name="hidlinea" type="hidden" id="hidlinea" value="<?php print $li_linea?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(ls_numtra,ld_fecemi,ls_obstra,ls_codusu,ls_codalmori,ls_codalmdes,ls_nomfisori,ls_nomfisdes,hidstatus)
	{ 
		opener.document.form1.txtnumtra.value=ls_numtra;
		opener.document.form1.txtfecemi.value=ld_fecemi;
		opener.document.form1.txtcodusu.value=ls_codusu;
		opener.document.form1.txtcodalm.value=ls_codalmori;
		opener.document.form1.txtcodalmdes.value=ls_codalmdes;
		opener.document.form1.txtnomfisalm.value=ls_nomfisori;
		opener.document.form1.txtnomfisdes.value=ls_nomfisdes;
		opener.document.form1.txtobstra.value=ls_obstra;
		opener.document.form1.hidestatus.value="C";
		opener.document.form1.operacion.value="BUSCARDETALLE";
		opener.document.form1.hidreadonly.value="false";
		opener.document.form1.action="sigesp_siv_p_transferencia.php";
		opener.document.form1.submit();
		close();
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_catdinamic_transferencia.php";
		f.submit();
	}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
