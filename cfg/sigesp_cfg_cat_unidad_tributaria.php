<?php
session_start();
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Unidades Tributarias</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="scb/js/number_format.js"></script>
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
	color: #006699#006699;
}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
<?php

require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();
require_once("../shared/class_folder/class_funciones.php");
$fun=new class_funciones();

/*require_once("scb/sigesp_c_cuentas_banco.php");
$io_ctaban = new sigesp_c_cuentas_banco();*/

if(array_key_exists("operacion",$_POST))
{
	 $ls_operacion=$_POST["operacion"];
     //$ls_codigo="%".$_POST["txtcodigo"]."%";
}
else
{
	$ls_operacion="BUSCAR";
	//$ls_codigo   ="%".$_POST["txtcodigo"]."%";
	//$ls_operacion="";
}
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="600" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Unidades Tributarias </td>
    	</tr>
	 </table>
	 <?php
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Año</td>";
print "<td>Gaceta Oficial</td>";
print "<td>Fecha Publicación</td>";
print "<td>Valor</td>";
print "</tr>";

if($ls_operacion=="BUSCAR")
{
	//$ls_codigo="%".$_POST["txtcodigo"]."%";
	$ls_sql=" SELECT * ".
		         " FROM sigesp_unidad_tributaria ORDER BY codunitri" ;
				// " WHERE codunitri like '".$ls_codigo."' "; 
	        $rs_uni=$SQL->select($ls_sql);
			if($rs_uni===false)
			{
				$io_msg->message("Error en select");
			}
			else
			{
				if($row=$SQL->fetch_row($rs_uni))
				{
					$data=$SQL->obtener_datos($rs_uni);
					$arrcols=array_keys($data);
					$totcol=count($arrcols);
					$ds->data=$data;
					$totrow=$ds->getRowCount("codunitri");
						
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codigo=$data["codunitri"][$z];
						$anno=$data["anno"][$z];
						$fecentvig=$data["fecentvig"][$z];
						$fecentvig=$fun->uf_convertirfecmostrar($data["fecentvig"][$z]);
						$gacofi=$data["gacofi"][$z];
						$fecpubgac=$data["fecpubgac"][$z];
						$fecpubgac=$fun->uf_convertirfecmostrar($data["fecpubgac"][$z]);
						$decnro=$data["decnro"][$z];
						$fecdec=$data["fecdec"][$z];
						$fecdec=$fun->uf_convertirfecmostrar($data["fecdec"][$z]);
						$valunitri=$data["valunitri"][$z];
						$valunitri=number_format($valunitri,3,",",".");	
					    print "<td><a href=\"javascript: aceptar('$codigo','$anno','$fecentvig','$gacofi','$fecpubgac','$decnro','$fecdec','$valunitri');\">".$codigo."</a></td>";
						print "<td>".$anno."</td>";
					    print "<td>".$gacofi."</td>";
						print "<td>".$fecpubgac."</td>";					   		
						print "<td>".$valunitri."</td>";					
						print "</tr>";			
					}
				}
				else
				{
					$io_msg->message("No se han definido Unidades Tributarias");
					print("<script language=JavaScript>");
				    print(" close();");
		            print("</script>");
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
  function aceptar(codigo,anno,fecentvig,gacofi,fecpubgac,decnro,fecdec,valunitri)
  {
    opener.document.form1.txtcodigo.value=codigo;
    opener.document.form1.txtanno.value=anno;
	opener.document.form1.txtfecentvig.value=fecentvig;
	opener.document.form1.txtgacofi.value=gacofi;
	opener.document.form1.txtfecpubgac.value=fecpubgac;
	opener.document.form1.txtdecnro.value=decnro;
	opener.document.form1.txtfecdec.value=fecpubgac;
	opener.document.form1.txtvalunitri.value=valunitri;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cfg_cat_unidad_tributaria.php";
  f.submit();
  }
</script>
</html>
