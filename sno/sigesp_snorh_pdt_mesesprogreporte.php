<?php
   session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_p_programacionreporte.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title >Definici&oacute;n de Meses de Programaci&oacute;n de Reporte</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

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
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>
<body>
<p>
<?php 
	$ls_codigo=$_GET["codigo"];
	$ls_denominacion=$_GET["denominacion"];
	$ls_codded=$_GET["codded"];
	$ls_codtipper=$_GET["codtipper"];
	$li_numcar=$_GET["numcar"];
	$li_monasi=$_GET["monasi"];
	$li_dismonasi=$_GET["dismonasi"];
	$ls_automatico="";
	$ls_distribucion="Manual";
	if(($li_dismonasi=="0")||($li_dismonasi=="")) // Distribución Automática
	{
		$ls_automatico=" readonly";
		$ls_distribucion="Automática";
	}
	$li_monene=$_GET["monene"];
	$li_monfeb=$_GET["monfeb"];
	$li_monmar=$_GET["monmar"];
	$li_monabr=$_GET["monabr"];
	$li_monmay=$_GET["monmay"];
	$li_monjun=$_GET["monjun"];
	$li_monjul=$_GET["monjul"];
	$li_monago=$_GET["monago"];
	$li_monsep=$_GET["monsep"];
	$li_monoct=$_GET["monoct"];
	$li_monnov=$_GET["monnov"];
	$li_mondic=$_GET["mondic"];

	$li_carene=$_GET["carene"];
	$li_carfeb=$_GET["carfeb"];
	$li_carmar=$_GET["carmar"];
	$li_carabr=$_GET["carabr"];
	$li_carmay=$_GET["carmay"];
	$li_carjun=$_GET["carjun"];
	$li_carjul=$_GET["carjul"];
	$li_carago=$_GET["carago"];
	$li_carsep=$_GET["carsep"];
	$li_caroct=$_GET["caroct"];
	$li_carnov=$_GET["carnov"];
	$li_cardic=$_GET["cardic"];
	
	//------------------------
	$li_carenef=$_GET["carenef"];
	$li_carfebf=$_GET["carfebf"];
	$li_carmarf=$_GET["carmarf"];
	$li_carabrf=$_GET["carabrf"];
	$li_carmayf=$_GET["carmayf"];
	$li_carjunf=$_GET["carjunf"];
	$li_carjulf=$_GET["carjulf"];
	$li_caragof=$_GET["caragof"];
	$li_carsepf=$_GET["carsepf"];
	$li_caroctf=$_GET["caroctf"];
	$li_carnovf=$_GET["carnovf"];
	$li_cardicf=$_GET["cardicf"];
	
	$li_carenem=$_GET["carenem"];
	$li_carfebm=$_GET["carfebm"];
	$li_carmarm=$_GET["carmarm"];
	$li_carabrm=$_GET["carabrm"];
	$li_carmaym=$_GET["carmaym"];
	$li_carjunm=$_GET["carjunm"];
	$li_carjulm=$_GET["carjulm"];
	$li_caragom=$_GET["caragom"];
	$li_carsepm=$_GET["carsepm"];
	$li_caroctm=$_GET["caroctm"];
	$li_carnovm=$_GET["carnovm"];
	$li_cardicm=$_GET["cardicm"];
	//-------------------------
	$li_row=$_GET["row"];
?>
</p>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="637" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="635" height="136">
      <p>&nbsp;</p>
      <table width="580" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="100" height="22"><div align="right">C&oacute;digo</div></td>
          <td height="20" colspan="4">
              
              <div align="left">
                <input name="txtcodigo" type="text" class="sin-borde3" id="txtcodigo" style="cursor:text; font-weight: bolder; font-family: Arial, Helvetica, sans-serif; font-size: 11px; font-style: italic;" value="<?php print $ls_codigo; ?>" size="6" maxlength="6" readonly #invalid_attr_id="none">
              </div></td>
        </tr>
        <tr>
          <td height="24"><div align="right">Denominaci&oacute;n</div></td>
          <td height="24" colspan="4">
            <div align="left">
              <input name="txtdenominacion" type="text" class="sin-borde3" id="txtdenominacion" style="cursor:text; font-weight: bolder; font-family: Arial, Helvetica, sans-serif; font-size: 11px; font-style: italic;" value="<?php print $ls_denominacion; ?>" size="50" maxlength="50" readonly #invalid_attr_id="none">
              <input name="txtcodded" type="hidden" id="txtcodded" value="<?php print $ls_codded; ?>">
              <input name="txtcodtipper" type="hidden" id="txtcodtipper" value="<?php print $ls_codtipper; ?>">
              <input name="txtrow" type="hidden" id="txtrow" value="<?php print $li_row; ?>">
            </div></td></tr>
        <tr>
          <td height="22"><div align="right">N&uacute;mero de Cargos </div></td>
          <td height="20" colspan="4"><div align="left">
            <input name="txtnumcar" type="text" class="sin-borde3" id="txtnumcar" value="<?php print $li_numcar; ?>" size="20" maxlength="20" readonly>            
          </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Monto Asignado</div></td>
          <td height="20" colspan="4">
            <div align="left">
              <input name="txtmonasi" type="text" class="sin-borde3" id="txtmonasi" value="<?php print $li_monasi; ?>" size="20" maxlength="20" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Distribuci&oacute;n</div></td>
          <td height="20" colspan="4">
            <div align="left">
              <input name="txtdistribucion" type="text" class="sin-borde3" id="txtdistribucion" value="<?php print $ls_distribucion; ?>" size="20" maxlength="20" readonly>
              </div></td></tr>
        <tr class="titulo-celdanew">
          <td height="22">            <div align="right">Meses</div></td>
          <td width="103" height="22"><div align="center">Nro Cargos </div></td>
          <td width="85" height="22"><div align="center">Nro Cargos Femeninos </div></td>
          <td width="86" height="22"><div align="center">Nro Cargos Masculinos </div></td>		  
          <td width="194" height="22"><div align="center">Monto Asignaci&oacute;n </div></td>
        </tr>
        <tr >
          <td ><div align="right">Enero</div></td>
          <td >
            
              <div align="center">
                <input name="txtcarene" type="text" id="txtcarene" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carene;?>" size="15" maxlength="10">
            </div></td>
          <td > <div align="center">
                <input name="txtcarenef" type="text" id="txtcarenef" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carenef;?>" size="15" maxlength="10">
            </div></td>
          <td ><div align="center">
                <input name="txtcarenem" type="text" id="txtcarenem" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carenem;?>" size="15" maxlength="10">
            </div></td>
          <td >
            
              <div align="center">
                <input name="txtmonene" type="text" id="txtmonene" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php print $li_monene;?>" size="25" maxlength="23" <?php print $ls_automatico;?>>
            </div></td>
        </tr>
        <tr >
          <td ><div align="right">Febrero</div></td>
          <td ><div align="center">
            <input name="txtcarfeb" type="text" id="txtcarfeb" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carfeb;?>" size="15" maxlength="10">
          </div></td>
          <td ><div align="center">
            <input name="txtcarfebf" type="text" id="txtcarfebf" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carfebf;?>" size="15" maxlength="10">
          </div></td>
          <td ><div align="center">
            <input name="txtcarfebm" type="text" id="txtcarfebm" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carfebm;?>" size="15" maxlength="10">
          </div></td>
          <td ><div align="center">
            <input name="txtmonfeb" type="text" id="txtmonfeb" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php print $li_monfeb;?>" size="25" maxlength="23" <?php print $ls_automatico;?>>
          </div></td>
        </tr>
        <tr>
          <td ><div align="right">Marzo</div></td>
          <td ><div align="center">
            <input name="txtcarmar" type="text" id="txtcarmar" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carmar;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcarmarf" type="text" id="txtcarmarf" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carmarf;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcarmarm" type="text" id="txtcarmarm" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carmarm;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtmonmar" type="text" id="txtmonmar" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php print $li_monmar;?>" size="25" maxlength="23" <?php print $ls_automatico;?>>
          </div></td>
        </tr>
        <tr>
          <td><div align="right">Abril</div></td>
          <td><div align="center">
            <input name="txtcarabr" type="text" id="txtcarabr" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carabr;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcarabrf" type="text" id="txtcarabrf" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carabrf;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcarabrm" type="text" id="txtcarabrm" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carabrm;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtmonabr" type="text" id="txtmonabr" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php print $li_monabr;?>" size="25" maxlength="23" <?php print $ls_automatico;?>>
          </div></td>
        </tr>
        <tr>
          <td><div align="right">Mayo</div></td>
          <td><div align="center">
            <input name="txtcarmay" type="text" id="txtcarmay" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carmay;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcarmayf" type="text" id="txtcarmayf" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carmayf;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcarmaym" type="text" id="txtcarmaym" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carmaym;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtmonmay" type="text" id="txtmonmay" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php print $li_monmay;?>" size="25" maxlength="23" <?php print $ls_automatico;?>>
          </div></td>
        </tr>
        <tr>
          <td><div align="right">Junio</div></td>
          <td><div align="center">
            <input name="txtcarjun" type="text" id="txtcarjun" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carjun;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcarjunf" type="text" id="txtcarjunf" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carjunf;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcarjunm" type="text" id="txtcarjunm" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carjunm;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtmonjun" type="text" id="txtmonjun" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php print $li_monjun;?>" size="25" maxlength="23" <?php print $ls_automatico;?>>
          </div></td>
        </tr>
        <tr>
          <td><div align="right">Julio</div></td>
          <td><div align="center">
            <input name="txtcarjul" type="text" id="txtcarjul" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carjul;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcarjulf" type="text" id="txtcarjulf" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carjulf;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcarjulm" type="text" id="txtcarjulm" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carjulm;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtmonjul" type="text" id="txtmonjul" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php print $li_monjul;?>" size="25" maxlength="23" <?php print $ls_automatico;?>>
          </div></td>
        </tr>
        <tr>
          <td><div align="right">Agosto</div></td>
          <td><div align="center">
            <input name="txtcarago" type="text" id="txtcarago" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carago;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcaragof" type="text" id="txtcaragof" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_caragof;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcaragom" type="text" id="txtcaragom" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_caragom;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtmonago" type="text" id="txtmonago" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php print $li_monago;?>" size="25" maxlength="23" <?php print $ls_automatico;?>>
          </div></td>
        </tr>
        <tr>
          <td><div align="right">Septiembre</div></td>
          <td><div align="center">
            <input name="txtcarsep" type="text" id="txtcarsep" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carsep;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcarsepf" type="text" id="txtcarsepf" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carsepf;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcarsepm" type="text" id="txtcarsepm" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carsepm;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtmonsep" type="text" id="txtmonsep" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php print $li_monsep;?>" size="25" maxlength="23" <?php print $ls_automatico;?>>
          </div></td>
        </tr>
        <tr>
          <td><div align="right">Octubre</div></td>
          <td><div align="center">
            <input name="txtcaroct" type="text" id="txtcaroct" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_caroct;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcaroctf" type="text" id="txtcaroctf" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_caroctf;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcaroctm" type="text" id="txtcaroctm" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_caroctm;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtmonoct" type="text" id="txtmonoct" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php print $li_monoct;?>" size="25" maxlength="23" <?php print $ls_automatico;?>>
          </div></td>
        </tr>
        <tr>
          <td><div align="right">Noviembre</div></td>
          <td><div align="center">
            <input name="txtcarnov" type="text" id="txtcarnov" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carnov;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcarnovf" type="text" id="txtcarnovf" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carnovf;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcarnovm" type="text" id="txtcarnovm" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_carnovm;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtmonnov" type="text" id="txtmonnov" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php print $li_monnov;?>" size="25" maxlength="23" <?php print $ls_automatico;?>>
          </div></td>
        </tr>
        <tr>
          <td><div align="right">Diciembre</div></td>
          <td><div align="center">
            <input name="txtcardic" type="text" id="txtcardic" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_cardic;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcardicf" type="text" id="txtcardicf" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_cardicf;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtcardicm" type="text" id="txtcardicm" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_cardicm;?>" size="15" maxlength="10">
          </div></td>
          <td><div align="center">
            <input name="txtmondic" type="text" id="txtmondic" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php print $li_mondic;?>" size="25" maxlength="23" <?php print $ls_automatico;?>>
          </div></td>
        </tr>
        <tr>
          <td colspan="5"><p align="right"><a href="javascript:ue_aceptar();"><img src="../shared/imagebank/tools20/aprobado.gif" alt="Buscar" width="20" height="20" border="0">Aceptar</a>
		    <a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Imprimir" width="20" height="20" border="0">Cancelar</a></p></td>
        </tr>
      </table>    
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
</body>
<script language="javascript">
function ue_aceptar()
{
	f=document.form1;
	li_row=f.txtrow.value;
	totcar=f.txtnumcar.value;
	carene=f.txtcarene.value;
	carfeb=f.txtcarfeb.value;
	carmar=f.txtcarmar.value;
	carabr=f.txtcarabr.value;
	carmay=f.txtcarmay.value;
	carjun=f.txtcarjun.value;
	carjul=f.txtcarjul.value;
	carago=f.txtcarago.value;
	carsep=f.txtcarsep.value;
	caroct=f.txtcaroct.value;
	carnov=f.txtcarnov.value;
	cardic=f.txtcardic.value;
	//----------------------
	carenef=f.txtcarenef.value;
	carfebf=f.txtcarfebf.value;
	carmarf=f.txtcarmarf.value;
	carabrf=f.txtcarabrf.value;
	carmayf=f.txtcarmayf.value;
	carjunf=f.txtcarjunf.value;
	carjulf=f.txtcarjulf.value;
	caragof=f.txtcaragof.value;
	carsepf=f.txtcarsepf.value;
	caroctf=f.txtcaroctf.value;
	carnovf=f.txtcarnovf.value;
	cardicf=f.txtcardicf.value;
	
	carenem=f.txtcarenem.value;
	carfebm=f.txtcarfebm.value;
	carmarm=f.txtcarmarm.value;
	carabrm=f.txtcarabrm.value;
	carmaym=f.txtcarmaym.value;
	carjunm=f.txtcarjunm.value;
	carjulm=f.txtcarjulm.value;
	caragom=f.txtcaragom.value;
	carsepm=f.txtcarsepm.value;
	caroctm=f.txtcaroctm.value;
	carnovm=f.txtcarnovm.value;
	cardicm=f.txtcardicm.value;
	//----------------------

	monasi=f.txtmonasi.value;
	while(monasi.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monasi=monasi.replace(".","");
	}
	monasi=monasi.replace(",",".");

	monene=f.txtmonene.value;
	while(monene.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monene=monene.replace(".","");
	}
	monene=monene.replace(",",".");
	monfeb=f.txtmonfeb.value;
	while(monfeb.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monfeb=monfeb.replace(".","");
	}
	monfeb=monfeb.replace(",",".");
	monmar=f.txtmonmar.value;
	while(monmar.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monmar=monmar.replace(".","");
	}
	monmar=monmar.replace(",",".");
	monabr=f.txtmonabr.value;
	while(monabr.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monabr=monabr.replace(".","");
	}
	monabr=monabr.replace(",",".");
	monmay=f.txtmonmay.value;
	while(monmay.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monmay=monmay.replace(".","");
	}
	monmay=monmay.replace(",",".");
	monjun=f.txtmonjun.value;
	while(monjun.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monjun=monjun.replace(".","");
	}
	monjun=monjun.replace(",",".");
	monjul=f.txtmonjul.value;
	while(monjul.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monjul=monjul.replace(".","");
	}
	monjul=monjul.replace(",",".");
	monago=f.txtmonago.value;
	while(monago.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monago=monago.replace(".","");
	}
	monago=monago.replace(",",".");
	monsep=f.txtmonsep.value;
	while(monsep.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monsep=monsep.replace(".","");
	}
	monsep=monsep.replace(",",".");
	monoct=f.txtmonoct.value;
	while(monoct.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monoct=monoct.replace(".","");
	}
	monoct=monoct.replace(",",".");
	monnov=f.txtmonnov.value;
	while(monnov.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monnov=monnov.replace(".","");
	}
	monnov=monnov.replace(",",".");
	mondic=f.txtmondic.value;
	while(mondic.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		mondic=mondic.replace(".","");
	}
	mondic=mondic.replace(",",".");
	li_montototal=0;
	li_montototal=parseFloat(monene)+parseFloat(monfeb)+parseFloat(monmar)+parseFloat(monabr)+parseFloat(monmay)+parseFloat(monjun);
	li_montototal=parseFloat(li_montototal)+parseFloat(monjul)+parseFloat(monago)+parseFloat(monsep)+parseFloat(monoct)+parseFloat(monnov)+parseFloat(mondic);
	li_montototal=uf_redondear(li_montototal,2);


	if(parseFloat(li_montototal)!=parseFloat(monasi))
	{
		li_montototal=uf_convertir(li_montototal);
		totasi=uf_convertir(monasi);
		alert("La suma de la asignación de los meses no coincide con el monto total asignado. Monto Total Meses "+li_montototal+" Monto Asignado "+totasi);
	}
	else
	{
		li_cargototal=0;
		li_cargototal=parseFloat(carene)+parseFloat(carfeb)+parseFloat(carmar)+parseFloat(carabr)+parseFloat(carmay)+parseFloat(carjun);
		li_cargototal=parseFloat(li_cargototal)+parseFloat(carjul)+parseFloat(carago)+parseFloat(carsep)+parseFloat(caroct)+parseFloat(carnov)+parseFloat(cardic);
		if(parseFloat(li_cargototal)!=parseFloat(totcar))
		{
			alert("La suma de los cargos de los meses no coincide con el total de cargos. Monto Total Cargos "+li_cargototal+" Total de Cargos "+totcar);
		}
		else
		{
			eval("opener.document.form1.txtmonene"+li_row+".value='"+f.txtmonene.value+"'");
			eval("opener.document.form1.txtmonfeb"+li_row+".value='"+f.txtmonfeb.value+"'");
			eval("opener.document.form1.txtmonmar"+li_row+".value='"+f.txtmonmar.value+"'");
			eval("opener.document.form1.txtmonabr"+li_row+".value='"+f.txtmonabr.value+"'");
			eval("opener.document.form1.txtmonmay"+li_row+".value='"+f.txtmonmay.value+"'");
			eval("opener.document.form1.txtmonjun"+li_row+".value='"+f.txtmonjun.value+"'");
			eval("opener.document.form1.txtmonjul"+li_row+".value='"+f.txtmonjul.value+"'");
			eval("opener.document.form1.txtmonago"+li_row+".value='"+f.txtmonago.value+"'");
			eval("opener.document.form1.txtmonsep"+li_row+".value='"+f.txtmonsep.value+"'");
			eval("opener.document.form1.txtmonoct"+li_row+".value='"+f.txtmonoct.value+"'");
			eval("opener.document.form1.txtmonnov"+li_row+".value='"+f.txtmonnov.value+"'");
			eval("opener.document.form1.txtmondic"+li_row+".value='"+f.txtmondic.value+"'");

			eval("opener.document.form1.txtcarene"+li_row+".value='"+f.txtcarene.value+"'");
			eval("opener.document.form1.txtcarfeb"+li_row+".value='"+f.txtcarfeb.value+"'");
			eval("opener.document.form1.txtcarmar"+li_row+".value='"+f.txtcarmar.value+"'");
			eval("opener.document.form1.txtcarabr"+li_row+".value='"+f.txtcarabr.value+"'");
			eval("opener.document.form1.txtcarmay"+li_row+".value='"+f.txtcarmay.value+"'");
			eval("opener.document.form1.txtcarjun"+li_row+".value='"+f.txtcarjun.value+"'");
			eval("opener.document.form1.txtcarjul"+li_row+".value='"+f.txtcarjul.value+"'");
			eval("opener.document.form1.txtcarago"+li_row+".value='"+f.txtcarago.value+"'");
			eval("opener.document.form1.txtcarsep"+li_row+".value='"+f.txtcarsep.value+"'");
			eval("opener.document.form1.txtcaroct"+li_row+".value='"+f.txtcaroct.value+"'");
			eval("opener.document.form1.txtcarnov"+li_row+".value='"+f.txtcarnov.value+"'");
			eval("opener.document.form1.txtcardic"+li_row+".value='"+f.txtcardic.value+"'");
			
			eval("opener.document.form1.txtcarenef"+li_row+".value='"+f.txtcarenef.value+"'");
			eval("opener.document.form1.txtcarfebf"+li_row+".value='"+f.txtcarfebf.value+"'");
			eval("opener.document.form1.txtcarmarf"+li_row+".value='"+f.txtcarmarf.value+"'");
			eval("opener.document.form1.txtcarabrf"+li_row+".value='"+f.txtcarabrf.value+"'");
			eval("opener.document.form1.txtcarmayf"+li_row+".value='"+f.txtcarmayf.value+"'");
			eval("opener.document.form1.txtcarjunf"+li_row+".value='"+f.txtcarjunf.value+"'");
			eval("opener.document.form1.txtcarjulf"+li_row+".value='"+f.txtcarjulf.value+"'");
			eval("opener.document.form1.txtcaragof"+li_row+".value='"+f.txtcaragof.value+"'");
			eval("opener.document.form1.txtcarsepf"+li_row+".value='"+f.txtcarsepf.value+"'");
			eval("opener.document.form1.txtcaroctf"+li_row+".value='"+f.txtcaroctf.value+"'");
			eval("opener.document.form1.txtcarnovf"+li_row+".value='"+f.txtcarnovf.value+"'");
			eval("opener.document.form1.txtcardicf"+li_row+".value='"+f.txtcardicf.value+"'");
			
			eval("opener.document.form1.txtcarenem"+li_row+".value='"+f.txtcarenem.value+"'");
			eval("opener.document.form1.txtcarfebm"+li_row+".value='"+f.txtcarfebm.value+"'");
			eval("opener.document.form1.txtcarmarm"+li_row+".value='"+f.txtcarmarm.value+"'");
			eval("opener.document.form1.txtcarabrm"+li_row+".value='"+f.txtcarabrm.value+"'");
			eval("opener.document.form1.txtcarmaym"+li_row+".value='"+f.txtcarmaym.value+"'");
			eval("opener.document.form1.txtcarjunm"+li_row+".value='"+f.txtcarjunm.value+"'");
			eval("opener.document.form1.txtcarjulm"+li_row+".value='"+f.txtcarjulm.value+"'");
			eval("opener.document.form1.txtcaragom"+li_row+".value='"+f.txtcaragom.value+"'");
			eval("opener.document.form1.txtcarsepm"+li_row+".value='"+f.txtcarsepm.value+"'");
			eval("opener.document.form1.txtcaroctm"+li_row+".value='"+f.txtcaroctm.value+"'");
			eval("opener.document.form1.txtcarnovm"+li_row+".value='"+f.txtcarnovm.value+"'");
			eval("opener.document.form1.txtcardicm"+li_row+".value='"+f.txtcardicm.value+"'");
			close();
		}
	}
}

function ue_cerrar()
{
	close();
}

function uf_redondear(num, dec)
{ 
	num = parseFloat(num); 
	dec = parseFloat(dec); 
	dec = (!dec ? 2 : dec); 
	return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec); 
}

</script> 
</html>