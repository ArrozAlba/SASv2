<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo Clasificaci&oacute;n de Conceptos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
	color: #006699;
}
-->
</style></head>

<body>
<?php
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../cfg/class_folder/class_funciones_configuracion.php");
$io_fun_cfg=new class_funciones_configuracion();
$io_conect=new sigesp_include();
$conn=$io_conect->uf_conectar();
$io_dsclasi=new class_datastore();
$io_sql=new class_sql($conn);
$arr=$_SESSION["la_empresa"];
$ls_clactacont=$_SESSION["la_empresa"]["clactacon"]; 

if($ls_clactacont==1)
{
$ls_sql=" SELECT codcla,dencla,sc_cuenta ".
        " FROM cxp_clasificador_rd WHERE codcla<>'--'".
		" ORDER BY codcla";
}
else
{
$ls_sql=" SELECT codcla,dencla ".
        " FROM cxp_clasificador_rd WHERE codcla<>'--'".
		" ORDER BY codcla"; 
}
$rs_clas=$io_sql->select($ls_sql);
$data=$rs_clas;
?>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo Clasificaci&oacute;n de Conceptos </td>
  </tr>
</table>
  <div align="center"><br>
    <?php
if ($row=$io_sql->fetch_row($rs_clas))
   {
     $data=$io_sql->obtener_datos($rs_clas);
	 $arrcols=array_keys($data);
     $totcol=count($arrcols);
     $io_dsclasi->data=$data;
     $totrow=$io_dsclasi->getRowCount("codcla");
     print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 print "<tr class=titulo-celda>";
	 print "<td>Código</td>";
	 print "<td>Denominación</td>";
 	 print "</tr>";
   
	 for ($z=1;$z<=$totrow;$z++)
         {
		   switch ($ls_clactacont)
			{  
			   case "1":
					print "<tr class=celdas-blancas>";
					$codigo=$data["codcla"][$z];
					$denominacion=$data["dencla"][$z];
					$sc_cuenta=$data["sc_cuenta"][$z];
					print "<td  style=text-align:center><a href=\"javascript: aceptar2('$codigo','$denominacion','$sc_cuenta');\">".$codigo."</a></td>";
					print "<td  style=text-align:left>".$denominacion."</td>";
					print "</tr>";
			    break;
				case ""	:
				    print "<tr class=celdas-blancas>";
					$codigo=$data["codcla"][$z];
					$denominacion=$data["dencla"][$z];
					print "<td  style=text-align:center><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
					print "<td  style=text-align:left>".$denominacion."</td>";
					print "</tr>";	
			    break;
				case "0"	:
				    print "<tr class=celdas-blancas>"; 
					$codigo=$data["codcla"][$z];
					$denominacion=$data["dencla"][$z];
					print "<td  style=text-align:center><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
					print "<td  style=text-align:left>".$denominacion."</td>";
					print "</tr>";	
			    break;
			 }	
         }
	print "</table>";
$io_sql->free_result($rs_clas);
	}
else
   {
   ?>
    <script language="javascript">
	alert("No se han creado Clasificadores !!!");
	close();
	</script>
   <?php
   }	 
?>
    </table>
  </div>
</body>
<script language="JavaScript">
  function aceptar2(codigo,denominacion,sc_cuenta)
  {
    opener.document.form1.txtcodigo.value=codigo;
    opener.document.form1.txtcodigo.readOnly=true;
	opener.document.form1.txtdenominacion.value=denominacion;
	opener.document.form1.txtcuenta.value=sc_cuenta;
	opener.document.form1.hidestatus.value="GRABADO";
	close();
  }
  function aceptar(codigo,denominacion)
  {
    opener.document.form1.txtcodigo.value=codigo;
    opener.document.form1.txtcodigo.readOnly=true;
	opener.document.form1.txtdenominacion.value=denominacion;
	opener.document.form1.hidestatus.value="GRABADO";
	close();
  }
</script>
</html>