<?php
    session_start();   
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_funciones_scg.php");
	$io_fun_scg=new class_funciones_scg();
	$io_fun_scg->uf_load_seguridad("SCG","sigesp_scg_r_balance_general_consolidado.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];

$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
$ls_sel2005 = $ls_sel2006 = $ls_sel2007 = $ls_sel2008 = $ls_sel2009 = $ls_sel2010 = $ls_sel2011 = "";
$ls_sel2012 = $ls_sel2013 = $ls_sel2014 = $ls_sel2015 = $ls_sel2016 = $ls_sel2017 = $ls_sel2018 = "";
$ls_sel2019 = $ls_sel2020 = $ls_sel2021 = $ls_sel2022 = $ls_sel2023 = $ls_sel2024 = $ls_sel2025 = "";
$ls_anio = date("Y");
switch ($ls_anio){
  case '2005': $ls_sel2005 = 'selected';
  break; 
  case '2006': $ls_sel2006 = 'selected';
  break; 
  case '2007': $ls_sel2007 = 'selected';
  break; 
  case '2008': $ls_sel2008 = 'selected';
  break; 
  case '2009': $ls_sel2009 = 'selected';
  break; 
  case '2010': $ls_sel2010 = 'selected';
  break; 
  case '2011': $ls_sel2011 = 'selected';
  break; 
  case '2012': $ls_sel2012 = 'selected';
  break; 
  case '2013': $ls_sel2013 = 'selected';
  break; 
  case '2014': $ls_sel2014 = 'selected';
  break; 
  case '2015': $ls_sel2015 = 'selected';
  break; 
  case '2016': $ls_sel2016 = 'selected';
  break; 
  case '2017': $ls_sel2017 = 'selected';
  break; 
  case '2018': $ls_sel2018 = 'selected';
  break; 
  case '2019': $ls_sel2019 = 'selected';
  break; 
  case '2020': $ls_sel2020 = 'selected';
  break; 
  case '2021': $ls_sel2021 = 'selected';
  break; 
  case '2022': $ls_sel2022 = 'selected';
  break; 
  case '2023': $ls_sel2023 = 'selected';
  break; 
  case '2024': $ls_sel2024 = 'selected';
  break; 
  case '2025': $ls_sel2025 = 'selected';
  break; 
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Balance General Consolidado</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../spg/js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
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
</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
   <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Contabilidad Patrimonial</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:uf_print_reporte();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></td>
    <td class="toolbar" width="25"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
    <td class="toolbar" width="703">&nbsp;</td>
  </tr>
</table>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="" id="sigesp_scg_r_balance_general_consolidado.php">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_scg->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_scg);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="330" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="241"></td>
    </tr>
    <tr class="titulo-celdanew">
      <td height="22" colspan="3" align="center" class="titulo-ventana">Balance General Consolidado</td>
    </tr>
    <tr>
      <td height="72" colspan="3" align="center">
        <table width="293" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="13" colspan="5"><strong class="titulo-celdanew">Mes y A&ntilde;o</strong></td>
            </tr>
          <tr>
            <td width="28" height="22">&nbsp;</td>
            <td width="36" height="22"><div align="right">Mes </div></td>
            <td width="89" height="22">
                <div align="left">
                  <select name="cmbmes" id="cmbmes">
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                  </select>
                </div></td>
            <td width="44" height="22"><div align="right">A&ntilde;o</div></td>
            <td width="94" height="22"><select name="cmbagno" id="cmbagno">
              <option value="2005" <?php print $ls_sel2005; ?>>2005</option>
              <option value="2006" <?php print $ls_sel2006; ?>>2006</option>
              <option value="2007" <?php print $ls_sel2007; ?>>2007</option>
              <option value="2008" <?php print $ls_sel2008; ?>>2008</option>
              <option value="2009" <?php print $ls_sel2009; ?>>2009</option>
              <option value="2010" <?php print $ls_sel2010; ?>>2010</option>
              <option value="2011" <?php print $ls_sel2011; ?>>2011</option>
              <option value="2012" <?php print $ls_sel2012; ?>>2012</option>
              <option value="2013" <?php print $ls_sel2013; ?>>2013</option>
              <option value="2014" <?php print $ls_sel2014; ?>>2014</option>
              <option value="2015" <?php print $ls_sel2015; ?>>2015</option>
              <option value="2016" <?php print $ls_sel2016; ?>>2016</option>
              <option value="2017" <?php print $ls_sel2017; ?>>2017</option>
              <option value="2018" <?php print $ls_sel2018; ?>>2018</option>
              <option value="2019" <?php print $ls_sel2019; ?>>2019</option>
              <option value="2020" <?php print $ls_sel2020; ?>>2020</option>
			  <option value="2021" <?php print $ls_sel2021; ?>>2021</option>
			  <option value="2022" <?php print $ls_sel2022; ?>>2022</option>
			  <option value="2023" <?php print $ls_sel2023; ?>>2023</option>
			  <option value="2020" <?php print $ls_sel2024; ?>>2024</option>
			  <option value="2025" <?php print $ls_sel2025; ?>>2025</option>
            </select></td>
            </tr>
        </table>
        </td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"></td>
    </tr>
  </table>
</form>      
</body>
<script language="JavaScript">
function uf_print_reporte()
{
	f=document.form1;
	ls_codemp = "<?php echo $_SESSION["la_empresa"]["codemp"]; ?>";
	li_imprimir=f.imprimir.value;
	if (li_imprimir==1)
	   { 	
		 cmbmes  = f.cmbmes.value;
		 cmbagno = f.cmbagno.value;
		 pagina  = "reportes/sigesp_scg_rpp_balance_general_consolidado.php?hidcodemp="+ls_codemp+"&cmbmes="+cmbmes+"&cmbagno="+cmbagno;
		 window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	   }
	else
   	   {
 	     alert("No tiene permiso para realizar esta operación !!!");
   	   }		
}
</script>
</html>