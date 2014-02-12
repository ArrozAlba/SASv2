<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo Beneficiarios</title>
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
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");

$io_conect=new sigesp_include();
$con=$io_conect->uf_conectar();
$io_msg=new class_mensajes();
$io_dsbene=new class_datastore();
$io_sql=new class_sql($con);
$la_emp=$_SESSION["la_empresa"];

	if (array_key_exists("operacion",$_POST))
	   {
		 $ls_operacion=$_POST["operacion"];
		 $ls_cedula="%".$_POST["txtcedula"]."%";
		 $ls_nombre="%".$_POST["txtnombre"]."%";
	   }
	else
	   {
		$ls_operacion="";
	   }
?>
<form name="form1" method="post" action="">
  <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="4" class="titulo-celda">Catalogo de Beneficiarios </td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="64" height="27"><div align="right">Cedula&nbsp;</div></td>
        <td width="139"><div align="left">
          <input name="txtcedula" type="text" id="txtcedula">        
        </div></td>
        <td width="58"><div align="right">Apellido&nbsp;</div></td>
        <td width="219"><div align="left">
          <input name="txtapellido" type="text" id="txtapellido" size="25">
        </div></td>
      </tr>
      <tr>
        <td height="32"><div align="right">Nombre&nbsp;</div></td>
        <td><div align="left">
          <input name="txtnombre" type="text" id="nombre2">
        </div></td>
        <td><div align="right">Banco&nbsp;</div></td>
        <td><?php
		/*Llenar Combo Banco*/
		$ls_codemp=$la_emp["codemp"];
		$ls_sql="SELECT * FROM scb_banco WHERE codemp='".$ls_codemp."' ORDER BY codban ASC";
		$rs_banco=$io_sql->select($ls_sql);
		?> <div align="left">
            <select name="cmbbanco" id="select" style="width:150px">
              <option value="000">Selecciones un Banco</option>
              <?php
		while ($row=$io_sql->fetch_row($rs_banco))
  			  {
			    $ls_codban=$row["codban"];
			    $ls_nomban=$row["nomban"];
			    if ($ls_codban==$ls_banco)
			 	   {
					 print "<option value='$ls_codban' selected>$ls_nomban</option>";
				   }
			    else
				   {
					 print "<option value='$ls_codban'>$ls_nomban</option>";
				   }
			  } 
	  $io_sql->free_result($rs_ben);
	  ?>
            </select>
          <input name="operacion" type="hidden" id="operacion2">     
        </div>
      <tr>
        <td height="32" colspan="4"><div align="right"></div>          <div align="left"></div>          
        <div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0" onClick="ue_search()">Buscar Beneficiario</a>        </div></td>
  </table> 
</form>      

<?php
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=100 style=text-align:center>Cédula</td>";
print "<td width=400 style=text-align:center>Nombre del Beneficiario</td>";
print "</tr>";

if ($ls_operacion=="BUSCAR")
   {
	$ls_cedbene="%".$_POST["txtcedula"]."%";
	$ls_nombene="%".$_POST["txtnombre"]."%";
	$ls_apebene="%".$_POST["txtapellido"]."%";
	$ls_codban="%".$_POST["cmbbanco"]."%";
	if ($ls_codban=="%000%")
	{  
	  $ls_codban="%%";	
	} 
	$ls_codemp=$la_emp["codemp"];
    $ls_sql="SELECT  *                ".
	        "FROM    rpc_beneficiario ".
	        "WHERE   codemp='".$ls_codemp."'        AND ced_bene like '".$ls_cedbene."' AND ".
			"        nombene like '".$ls_nombene."' AND apebene like '".$ls_apebene."'  AND ".
			"        codban like '".$ls_codban."'   AND ced_bene<>'----------'              ".
			"ORDER BY ced_bene ASC"  ;
			
	$rs_bene=$io_sql->select($ls_sql);
	$data=$rs_bene;
    if ($row=$io_sql->fetch_row($rs_bene))
	{
	    $data=$io_sql->obtener_datos($rs_bene);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$io_dsbene->data=$data;
		$totrow=$io_dsbene->getRowCount("ced_bene");
		for ($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_cedbene=$data["ced_bene"][$z];
			$ls_nombene=$data["nombene"][$z];
			print "<td width=100 style=text-align:center><a href=\"javascript: aceptar('$ls_cedbene','$ls_nombene');\">".$ls_cedbene."</a></td>";
			print "<td width=400 style=text-align:left>".$data["nombene"][$z]."</td>";
			print "</tr>";			
		}
	}
}
print "</table>";
?>
</body>

<script language="JavaScript">
  function aceptar(cedula,nombre)
  {
	opener.document.form1.txtnombre.value    = nombre;
	opener.document.form1.txtcodproben.value = cedula;	
	close();
  }
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cxp_cat_ben.php";
	  f.submit();
  }
</script>
</html>
