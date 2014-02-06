<?php
session_start();
$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
$li_longestpro1= (25-$ls_loncodestpro1)+1;
$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
$li_longestpro2= (25-$ls_loncodestpro2)+1;
$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
$li_longestpro3= (25-$ls_loncodestpro3)+1;
$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
$li_longestpro4= (25-$ls_loncodestpro4)+1;
$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
$li_longestpro5= (25-$ls_loncodestpro5)+1;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Otros Créditos</title>
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
<table width="700" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Otros Créditos</td>
    </tr>
</table>
  <div align="center"><br>
    <?php
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../class_folder/class_funciones_cfg.php");

$io_conect     = new sigesp_include();
$con           = $io_conect->uf_conectar();
$io_dsotroscre = new class_datastore();
$io_sql        = new class_sql($con);
$io_funciones_cfg= new class_funciones_cfg();
$arr           = $_SESSION["la_empresa"];
$ls_codemp     = $arr["codemp"];
$li_estmodest  = $arr["estmodest"];
$ls_confiva    = $_GET["confiva"];
$ls_sql        = "SELECT * FROM sigesp_cargos WHERE codemp='".$ls_codemp."' ORDER BY codcar ASC";
$rs_otroscre   = $io_sql->select($ls_sql);
$data          = $rs_otroscre;

if ($row=$io_sql->fetch_row($rs_otroscre))
   {
     $data                = $io_sql->obtener_datos($rs_otroscre);
	 $arrcols             = array_keys($data);
     $totcol              = count($arrcols);
     $io_dsotroscre->data = $data;
     $totrow              = $io_dsotroscre->getRowCount("codcar");
	 print "<table width=700 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 print "<tr class=titulo-celda>";
	 print "<td width=40>Código</td>";
	 print "<td width=350>Denominación</td>";
	 if($ls_confiva=='P')
	 {
		 print "<td width=200>Programática</td>";
	 }	 
	 print "<td width=50>Porcentaje</td>";
	 print "<td width=250>Fórmula</td>";
	 print "</tr>";
	 for ($z=1;$z<=$totrow;$z++)
		 {
			print "<tr class=celdas-blancas>";
			$codigo       = $data["codcar"][$z];
			$denominacion = $data["dencar"][$z];
			$codestpro    = $data["codestpro"][$z];
			$ls_estcla    = $data["estcla"][$z];
			$io_funciones_cfg->uf_formato_estructura($codestpro,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5);
			$as_codestpro1= substr($codestpro,0,25);
			$as_codestpro2= substr($codestpro,25,25);
			$as_codestpro3= substr($codestpro,50,25);
			$as_codestpro4= substr($codestpro,75,25);
			$as_codestpro5= substr($codestpro,100,25);
			if ($li_estmodest=='1')
			   { 
			     $codestpro = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3;
			   }
			else
			   {
			     $codestpro = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			   }
			$porcentaje   = $data["porcar"][$z];
			$formula      = $data["formula"][$z];
  		    $estlibcom    = $data["estlibcom"][$z];
			$spg_cuenta   = $data["spg_cuenta"][$z];
			print "<td style=text-align:center><a href=\"javascript: aceptar('$codigo','$denominacion','$codestpro','$porcentaje','$formula','$estlibcom','$spg_cuenta','$ls_estcla','$as_codestpro1','$as_codestpro2','$as_codestpro3','$as_codestpro4','$as_codestpro5','$ls_confiva');\">".$codigo."</a></td>";
			print "<td style=text-align:left>".$denominacion."</td>";
			if($ls_confiva=='P')
			{
				print "<td style=text-align:center>".$codestpro."</td>";
			}	
			print "<td style=text-align:right>".$porcentaje."</td>";
			print "<td style=text-align:right>".$formula."</td>";
			print "</tr>";			
		}
print "</table>";
$io_sql->free_result($rs_otroscre);
}
else
   {
   ?>
    <script language="javascript">
	alert("No se han creado Otros Créditos !!!");
	close();
	</script>
   <?php
   }
?>
   <input name="confiva" type="hidden" id="confiva"  value="<?php print $ls_confiva?>">
</div>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,programatica,porcentaje,formula,estatus,spg_cuenta,ls_estcla,
                  as_codestpro1,as_codestpro2,as_codestpro3,as_codestpro4,as_codestpro5,ls_confiva)
  {
    opener.document.form1.txtcodigo.value        = codigo;
    opener.document.form1.txtcodigo.readOnly     = true;
	opener.document.form1.txtdenominacion.value  = denominacion;
	if(ls_confiva=='P')
	{
		opener.document.form1.txtcodestpro.value = programatica;
		opener.document.form1.txtestcla.value        =ls_estcla;
		opener.document.form1.txtcodestpro1.value    =as_codestpro1;
		opener.document.form1.txtcodestpro2.value    =as_codestpro2;
		opener.document.form1.txtcodestpro3.value    =as_codestpro3;
		opener.document.form1.txtcodestpro4.value    =as_codestpro4;
		opener.document.form1.txtcodestpro5.value    =as_codestpro5;
	}
	opener.document.form1.txtporcentaje.value    = porcentaje;
	opener.document.form1.txtformula.value       = formula;
	opener.document.form1.txtpresupuestaria.value= spg_cuenta;
	opener.document.form1.hidestatus.value       ='GRABADO';
	opener.document.form1.chklibcompras.value    =estatus;
	
    if (estatus==1)
	   {
	     opener.document.form1.chklibcompras.checked=true;
	   }
	else
	   {
	     opener.document.form1.chklibcompras.checked=false;
	   }   
	close();
  }
</script>
</html>