<?php
session_start();
	if(array_key_exists("coddestino",$_GET))
	{
		$ls_coddestino=$_GET["coddestino"];
		$ls_dendestino=$_GET["dendestino"];
	}
	else
	{
		if(array_key_exists("coddestino",$_POST))
		{
			$ls_coddestino=$_POST["coddestino"];
			$ls_dendestino=$_POST["dendestino"];
		}
		else
		{
			$ls_coddestino="";
		$ls_dendestino="";
		}



	}


if (array_key_exists("codalmori",$_GET))
	{
		$ls_codalmori=$_GET["codalmori"];
	}
	else
	{
		if(array_key_exists("codalmori",$_POST))
		{
			$ls_codalmori=$_POST["codalmori"];
		}
		else
		{
			$ls_codalmori=$_POST["codorigen"];
		}
	}



if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codalm="%".$_POST["txtcodalm"]."%";
		$ls_nomfisalm="%".$_POST["txtnomfisalm"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
		$ls_coddestino=$_POST["hidcoddestino"];
		$ls_dendestino=$_POST["hiddendestino"];
	}
	else
	{
		$ls_operacion="";
		$ls_codalm="%%";
		$ls_nomfisalm="%%";
		$ls_status="%%";
	}




?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Almac&eacute;n </title>
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
<?php



//$ls_codalmori=$_GET["codalmori"];

?>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hidstatus" type="hidden" id="hidstatus">

    <input name="hidcoddestino" type="hidden" id="hidcoddestino" value="<?php print $ls_coddestino ?>">
    <input name="hiddendestino" type="hidden" id="hiddendestino" value="<?php print $ls_dendestino ?>">
	<input name="codorigen" type="hidden" id="codorigen" value="<?php print $ls_codalmori ?>">

</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Almac&eacute;n </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>

        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="80"><div align="right">C&oacute;digo</div></td>
        <td width="418" height="22"><div align="left">
          <input name="txtcodalm" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre Fiscal </div></td>
        <td height="22"><div align="left">          <input name="txtnomfisalm" type="text" id="txtnomfisalm">
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
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	$in     =new sigesp_include();
	$con    =$in->uf_conectar();
	$io_msg =new class_mensajes();
	$ds     =new class_datastore();
	$io_sql =new class_sql($con);

	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];


	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>C�digo</td>";
	print "<td>Nombre Fiscal</td>";
	print "<td>Responsable</td>";
	print "<td>Unidad Operativa de Suministro</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{

		$ls_sql="SELECT a.*,t.dentie FROM sim_almacen a, sfc_tienda t".
				" WHERE a.codemp = '".$ls_codemp."'".
				" AND a.codalm iLIKE '".$ls_codalm."'".
				" AND a.nomfisalm iLIKE '".$ls_nomfisalm."' AND substr(a.codalm,7,4)=t.codtiend";


		$rs_cta=$io_sql->select($ls_sql);
		$data=$rs_cta;
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;

			$totrow=$ds->getRowCount("codalm");

			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$ls_codalm=     $data["codalm"][$z];
				$ls_nomfisalm=  $data["nomfisalm"][$z];
				$ls_desalm=     $data["desalm"][$z];
				$ls_telalm=     $data["telalm"][$z];
				$ls_ubialm=     $data["ubialm"][$z];
				$ls_nomresalm=  $data["nomresalm"][$z];
				$ls_telresalm=  $data["telresalm"][$z];
				$ls_codtiend=substr($ls_codalm,6,4);
				$ls_dentie= $data["dentie"][$z];
				$ls_coddestino=  $data["codalm"][$z];
				$ls_dendestino=  $data["nomfisalm"][$z];
				print "<td><a href=\"javascript: aceptar('$ls_codtiend','$ls_dentie','$ls_codalm','$ls_nomfisalm','$ls_status','$li_linea','$ls_coddestino','$ls_dendestino');\">".$ls_codalm."</a></td>";
				print "<td>".$data["nomfisalm"][$z]."</td>";
				print "<td>".$data["nomresalm"][$z]."</td>";
				print "<td>".$ls_codtiend." - ".$ls_dentie."</td>";
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
	function aceptar(ls_codtiend,ls_dentie,ls_codalm,ls_nomfisalm,hidstatus,li_linea,ls_coddestino,ls_dendestino)
	{
		f=document.form1;
		ls_codalmori=f.codorigen.value;
//alert(ls_codalmori);
//alert(ls_codalm);
		if (ls_codalmori==ls_codalm)
		{
			alert("No se puede realizar transferencia entre un mismo almacen, Seleccione otro!!");
			obj.value="";
			obj1.value="";
			close();

		}
		else
		{

			obj=eval("opener.document.form1.txtcodalmdes"+li_linea);
			obj.value=ls_codalm;
			opener.document.form1.txtcodtiend.value=ls_codtiend;
			opener.document.form1.txtnomfisdes.value=ls_dendestino+" - "+ls_dentie;

close();
		}


	}

	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_catdinamic_almacend.php";
		f.submit();
	}
</script>
</html>
