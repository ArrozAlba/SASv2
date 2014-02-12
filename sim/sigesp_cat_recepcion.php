<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Entradas de Suministros a Almac&eacute;n </title>
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
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Entradas de Suministros a Almac&eacute;n </td>
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
          <input name="txtnumordcom" type="text" id="txtnumordcom" size="15" maxlength="15">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Código Almac&eacute;n</div></td>
        <td height="22"><div align="left">
          <input name="txtcodalm" type="text" id="txtcodalm" size="11" maxlength="10">
</div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	$in=     new sigesp_include();
	$con=    $in->uf_conectar();
	$ds=     new class_datastore();
	$io_sql= new class_sql($con);
	$io_func=new class_funciones();
	$io_msg= new class_mensajes();
	$io_fun= new class_funciones();
	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_numordcom="%".$_POST["txtnumordcom"]."%";
		$ls_codalm="%".$_POST["txtcodalm"]."%";
	}
	else
	{
		$ls_operacion="";
	
	}
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td width=100>Compra/Factura</td>";
	print "<td width=140>Almacén</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql=" SELECT distinct sim_dt_movimiento.numdoc,(SELECT nomfisalm ".
                "                                           FROM sim_almacen ".
                "                                           WHERE sim_almacen.codalm = sim_dt_movimiento.codalm) AS nomfisalm ".
                " FROM  sim_movimiento,sim_dt_movimiento ".
                " WHERE sim_movimiento.nummov=sim_dt_movimiento.nummov  AND ".
                "       sim_movimiento.fecmov=sim_dt_movimiento.fecmov  AND ".
                "       sim_dt_movimiento.numdoc ilike '".$ls_numordcom."' AND sim_dt_movimiento.codalm ilike '".$ls_codalm."' ";
		$rs_cta=$io_sql->select($ls_sql);
		if($row=$io_sql->fetch_row($rs_cta))
		{
			while($row=$io_sql->fetch_row($rs_cta))
			{
				print "<tr class=celdas-blancas>";
				$ls_numordcom= $row["numdoc"];
				$ls_nomfisalm= $row["nomfisalm"];
				print "<td><a href=\"javascript: aceptar('$ls_numordcom','$ls_nomfisalm');\">".$ls_numordcom."</a></td>";
				print "<td>".$row["nomfisalm"]."</td>";
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
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(ls_numordcom,ls_nomfisalm)
	{ 
		opener.document.form1.txtnumordcom.value=ls_numordcom;
		close();
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_cat_recepcion.php";
		f.submit();
	}
</script>
</html>