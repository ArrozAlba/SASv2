<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Unidad de Medida</title>
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
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Unidad de Medida</td>
    </tr>
</table>
<?php
/*require_once("class_folder/sigesp_cfg_c_ctrl_numero.php");
$io_ctrl_numero=new sigesp_cfg_c_ctrl_numero();*/
require_once("../../shared/class_folder/sigesp_include.php");
$io_conect=new sigesp_include();
$con=$io_conect->uf_conectar();
require_once("../../shared/class_folder/class_datastore.php");
$io_dsprocedencia=new class_datastore();
require_once("../../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
$la_emp=$_SESSION["la_empresa"];
    /*if (array_key_exists("txtcodusu",$_GET))
    {
    	$ls_codusu=$_GET["txtcodusu"];
    }
    else 
    {
    	$ls_codusu="";
    }*/
	if (array_key_exists("operacion",$_POST))
	   {
		 $ls_operacion=$_POST["operacion"];
		 //$ls_codusu   =$_POST["txtcodusu"];
		 $ls_codigo   ="%".$_POST["txtcodigo"]."%";
	   }
	else
	   {
		 $ls_operacion="";
	   }
?>
<form name="form1" method="post" action="">
  <table width="498" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="88" height="15" align="right">&nbsp;</td>
        <td width="149">&nbsp;        </td>
        <td width="159" align="right">&nbsp;</td>
      </tr>
      <tr>
        <td height="18" align="right">C&oacute;digo</td>
        <td><input name="txtcodigo" type="text" id="txtcodigo" style="text-align:center"  maxlength="3">
        <input name="operacion" type="hidden" id="operacion"></td>
        <td align="right"><div align="left"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" onClick="ue_search()">Buscar</a></div></td>
      <tr>
        <td height="18" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
  </table> 
  <p>&nbsp;</p>
</form>      
<div align="center">
<?php
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=100>Código</td>";
print "<td>Descripción</td>";
print "</tr>";

if ($ls_operacion=="BUSCAR")
   {
		$ls_sql= "SELECT codunimed,denunimed,unidad ".
		         "  FROM siv_unidadmedida ".
		         " WHERE codunimed like '".$ls_codigo."'".
				 " ORDER BY codunimed"; //print $ls_sql;
		$rs=$io_sql->select($ls_sql);
		$data=$rs;
    	if ($row=$io_sql->fetch_row($rs))
		   {
			 $data=$io_sql->obtener_datos($rs);
			 $arrcols=array_keys($data);
			 $totcol=count($arrcols);
			 $io_dsprocedencia->data=$data;
			 $totrow=$io_dsprocedencia->getRowCount("codunimed");
			 for ($z=1;$z<=$totrow;$z++)
				 {
			  	   print "<tr class=celdas-blancas>";
			  	   $ls_codunimed=$data["codunimed"][$z];
				   $ls_denunimed=$data["denunimed"][$z];				   
		           $ls_unidad=$data["unidad"][$z];
		           print "<td align=center><a href=\"javascript: aceptar('$ls_codunimed','$ls_denunimed','$ls_unidad');\">".$ls_codunimed."</a></td>";
				   print "<td align=left>".$ls_denunimed."</td>";
				   print "</tr>";			
			     }
		   print "</table>";
		   $io_sql->free_result($rs);
		   }
		else
		 { ?>
		   <script  language="javascript">
		   alert("No se han creado Unidades de medida !!!");
		   </script>
		 <?php
		 }  
}
?>
</div>
</body>
<script language="JavaScript">
  function aceptar(codunimed,denunimed)
  {
    opener.document.form1.txtcodunimed.value=codunimed;
	opener.document.form1.txtdenunimed.value=denunimed;
	
	opener.document.form1.txtcodunimed.readOnly=true;
	opener.document.form1.txtdenunimed.readOnly=true;
	//opener.document.form1.operacion.value        = "buscar";
	//opener.document.form1.submit();
	close();
  }
  
  function ue_search()
  {
    f=document.form1;
    f.operacion.value="BUSCAR";
    f.action="sigesp_cfg_cat_unidad_medida.php";
    f.submit();
  }
</script>
</html>