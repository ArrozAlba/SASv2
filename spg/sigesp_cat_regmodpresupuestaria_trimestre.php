<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
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
<title>Catalogo de Modificaciones al Programado del Presupuesto</title>
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style2 {font-size: 11px}
.Estilo1 {font-weight: bold}
-->
</style>
</head>
<body>
<?php
$dat=$_SESSION["la_empresa"];
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
$msg=new class_mensajes();
$siginc=new sigesp_include();
$con=$siginc->uf_conectar();
$fun=new class_funciones();
$io_sql=new class_sql($con);
require_once("sigesp_spg_c_mod_programado.php");
$in_classmod=new sigesp_spg_c_mod_programado();
$int_fec=new class_fecha();
/////////////////////////////////////Parametros necesarios para seguridad////////////////////////////
	$ls_empresa=$dat["codemp"];
	$li_estmodest=$dat["estmodest"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
		$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
		$ls_logusr="";
	}
	$ls_sistema="SPG";
	$ls_ventana="sigesp_spg_p_traspaso.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventana;
//////////////////////////////////////////////////////////////////////////////////////////////////
if (array_key_exists("operacion",$_POST))
{
    $ls_operacion=$_POST["operacion"];
    $ls_estpro1=$_POST["codestpro1"];
	$ls_estpro2=$_POST["codestpro2"];
	$ls_estpro3=$_POST["codestpro3"];
	$ls_cuentaplan=$_POST["txtcuenta"];
	$ls_denominacion=$_POST["txtdenominacion"];
	$ls_cuentaplan=$_POST["txtcuenta"];
	$ls_fechades=$_POST["txtfechades"];
	$ls_fechahas=$_POST["txtfechahas"]; 
	if($li_estmodest==2)
	{
		$ls_estpro4=$_POST["codestpro4"];
		$ls_estpro5=$_POST["codestpro5"];
	}
	else
	{
	 $ls_estpro4="";
	 $ls_estpro5="";
	}
	$ls_estcla=$_POST["estcla"];
}
else
{
    $ls_estpro1="";
	$ls_estpro2="";
	$ls_estpro3="";
    $ls_estpro4="";
    $ls_estpro5="";
	$ls_operacion = "";
	$ls_cuentaplan="";
	$ls_denominacion="";
	$ls_estcla="";
	$ls_fechades="01/".date("m/Y");
	$ls_fechahas=date("d/m/Y");	
}  
	?>
    <form method="post" name="form1" action="">
	 <input name="operacion" type="hidden" id="operacion" > 
<table width="567" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
   <td height="22" colspan="4" class="titulo-celda">Catalogo de Modificaciones al Programado del Presupuesto </td>
  </tr>
  <tr>
    <td width="75" height="13">&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
 	  <?php 
	  $li_estmodest  = $dat["estmodest"];
	  $ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	  $ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	  $ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	  $ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	  $ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
	  ?>
    <td height="22"><div align="right"><?php print $dat["nomestpro1"];  ?></div></td>
    <td colspan="3">
      <input name="codestpro1" type="text" id="codestpro1" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" style="text-align:center" value="<?php print $ls_estpro1; ?>" readonly>
      <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 1"></a>      <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" size="53" readonly>     
      <div align="left">      </div></td>
  </tr>
  <tr>
    <td><div align="right"><?php print $dat["nomestpro2"] ; ?></div>      </td>
    <td colspan="3"><input name="codestpro2" type="text" id="codestpro2" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" style="text-align:center" value="<?php print $ls_estpro2; ?>" readonly>
      <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 2"></a>
      <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" size="53" readonly></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro3"] ; ?></div></td>
    <td colspan="3">      <div align="left">
      <input name="codestpro3" type="text" id="codestpro3" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" style="text-align:center" value="<?php print $ls_estpro3; ?>" readonly>
      <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 3"></a>
      <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" size="53" readonly>
      </div></td>
  </tr>
  <?php if ($li_estmodest == 2){ ?>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro4"] ; ?></div></td>
    <td colspan="3"><div align="left">
        <input name="codestpro4" type="text" id="codestpro4" size="<?php print $ls_loncodestpro4; ?>" maxlength="<?php print $ls_loncodestpro4; ?>" style="text-align:center" value="<?php print $ls_estpro4; ?>" readonly>
        <a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
        <input name="denestpro4" type="text" class="sin-borde" id="denestpro4" size="53" readonly>
    </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro5"] ; ?></div></td>
    <td colspan="3"><div align="left">
        <input name="codestpro5" type="text" id="codestpro5" size="<?php print $ls_loncodestpro5; ?>" maxlength="<?php print $ls_loncodestpro5; ?>" style="text-align:center" value="<?php print $ls_estpro5; ?>" readonly>
        <a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
        <input name="denestpro5" type="text" class="sin-borde" id="denestpro5" size="53" readonly>
    </div></td>
    <?php 
	  }
	?>
  </tr>
    <tr><td height="22"><div align="right">Cuenta</div></td>
    <td colspan="3"><input name="txtcuenta" type="text" id="txtcuenta" readonly="true" value="<?php print $ls_cuentaplan ;?>" size="22" style="text-align:center">
        <a href="javascript:catalogo_cuentasSPG();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas de Presupuestarias de Gasto"></a>
        <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion3" style="text-align:left" value="<?php print $ls_denominacion ?>" size="53" maxlength="254"></td>
  </tr>
    <tr>
      <td height="22"><div align="right">Fecha</div></td>
      <td>Desde      </td>
      <td><input name="txtfechades" type="text" id="txtfechades" style="text-align:center" onBlur="valFecha(document.form1.txtfecha)" onKeyPress="javascript:currencyDate(this)" value="<?php print $ls_fechades;?>" size="18" maxlength="10" datepicker="true" ></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td> Hasta</td>
      <td><input name="txtfechahas" type="text" id="txtfechahas" style="text-align:center" onBlur="valFecha(document.form1.txtfecha)" onKeyPress="javascript:currencyDate(this)" value="<?php print $ls_fechahas;?>" size="18" maxlength="10" datepicker="true" ></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
    <td height="22">&nbsp;</td>
    <td width="49">
      </span>
      <input name="estcla"    type="hidden" id="estcla" value="<?php print $ls_estcla; ?>">
	  <input name="estmodest" type="hidden" id="estmodest" value="<?php print $li_estmodest; ?>">	  </td>
    <td width="400"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td width="41"><a href="javascript: ue_search();">Buscar</a></td>
    </tr>
</table>
<?php
function  uf_get_nombremes($as_mes)
{
 $ls_nombre="";
 switch($as_mes)
 {
  case "03":$ls_nombre= "Enero - Marzo";
  break;
  case "06":$ls_nombre= "Abril - Junio";
  break;
  case "09":$ls_nombre= "Julio - Septiembre";
  break;
  case "12":$ls_nombre= "Octubre - Diciembre";
  break; 
 }
 
 return $ls_nombre;
}
 
if($ls_operacion=="BUSCAR")
{
 $la_estpro[0]=$ls_estcla;
 $la_estpro[1]=str_pad($ls_estpro1,25,"0",0);
 $la_estpro[2]=str_pad($ls_estpro2,25,"0",0);
 $la_estpro[3]=str_pad($ls_estpro3,25,"0",0);
 $la_estpro[4]=str_pad($ls_estpro4,25,"0",0);
 $la_estpro[5]=str_pad($ls_estpro5,25,"0",0);
 $rs_data=NULL;
 $ld_fechades=$fun->uf_convertirdatetobd($ls_fechades);
 $ld_fechahas=$fun->uf_convertirdatetobd($ls_fechahas);
 $ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
 $ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
 $ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
 $ls_incio1=25-$ls_loncodestpro1;
 $ls_incio2=25-$ls_loncodestpro2;
 $ls_incio3=25-$ls_loncodestpro3;
 if($li_estmodest == 2)
 {
  $ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
  $ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
  $ls_incio4=25-$ls_loncodestpro4;
  $ls_incio5=25-$ls_loncodestpro5;
 }
 $in_classmod->uf_obtener_regmodificacion($ls_empresa,$la_estpro,$ls_cuentaplan,$ld_fechades,$ld_fechahas,$rs_data);
 print "<table width=565 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
 print "<tr class=titulo-celda>";
 print "<td align='center'>".$_SESSION["la_empresa"]["nomestpro1"]."</td>";
 print "<td align='center'>".$_SESSION["la_empresa"]["nomestpro2"]."</td>";
 print "<td align='center'>".$_SESSION["la_empresa"]["nomestpro3"]."</td>";
 if($li_estmodest == 2)
 {
  print "<td align='center'>".$_SESSION["la_empresa"]["nomestpro4"]."</td>";
  print "<td align='center'>".$_SESSION["la_empresa"]["nomestpro5"]."</td>";
 }
 print "<td align='center'>Cuenta</td>";
 print "<td align='center'>Trimestre Disminucion</td>";
 print "<td align='center'>Trimestre Aumento</td>";
 print "<td align='center'>Fecha</td>";
 print "<td align='center'>Monto</td>";
 print "</tr>";
 while(!$rs_data->EOF)
 {
		 $ls_spg_cuenta    = $rs_data->fields["spg_cuenta"];
		 $ls_codestpro1    = $rs_data->fields["codestpro1"];
		 $ls_denestpro1    = $rs_data->fields["denestpro1"];
		 $ls_codestpro2    = $rs_data->fields["codestpro2"];
		 $ls_denestpro2    = $rs_data->fields["denestpro2"];
		 $ls_codestpro3    = $rs_data->fields["codestpro3"];
		 $ls_denestpro3    = $rs_data->fields["denestpro3"];
		 $ls_codestpro4    = $rs_data->fields["codestpro4"];
		 $ls_denestpro4    = $rs_data->fields["denestpro4"];
		 $ls_codestpro5    = $rs_data->fields["codestpro5"];
		 $ls_denestpro5    = $rs_data->fields["denestpro5"];
		 $ls_estcla        = $rs_data->fields["estcla"];
		 $ld_fecha         = $rs_data->fields["fecha"];
		 $ld_fecha         =substr($ld_fecha,8,2)."/".substr($ld_fecha,5,2)."/".substr($ld_fecha,0,4);
		 $ld_monto         = $rs_data->fields["monto"];
		 $ls_mesaum        = $rs_data->fields["mesaumento"];
		 $ls_mesdis        = $rs_data->fields["mesdisminucion"];
		 $ls_codestpro1    =substr($ls_codestpro1,$ls_incio1,$ls_loncodestpro1);
		 $ls_codestpro2    =substr($ls_codestpro2,$ls_incio2,$ls_loncodestpro2);
		 $ls_codestpro3    =substr($ls_codestpro3,$ls_incio3,$ls_loncodestpro3);
		 if($li_estmodest == 2)
		 {
		  $ls_codestpro4=substr($ls_codestpro4,$ls_incio4,$ls_loncodestpro4);
		  $ls_codestpro5=substr($ls_codestpro5,$ls_incio5,$ls_loncodestpro5);
		 }
		 $ls_mesaumento        = uf_get_nombremes($ls_mesaum);
		 $ls_mesdisminucion    = uf_get_nombremes($ls_mesdis);
		 $ld_montoaux          = number_format($ld_monto,2,",",".");		 
		 print "<tr class=celdas-blancas>";
		 print "<td  align='center'><a href=\"javascript: uf_aceptar('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5', '$ls_denestpro1','$ls_denestpro2','$ls_denestpro3','$ls_denestpro4','$ls_denestpro5','$ls_estcla','$ls_spg_cuenta','$ls_mesaum','$ls_mesdis','$ld_fecha',$ld_monto);\">".$ls_codestpro1."</a></td>";
		 print "<td  align='center'><a href=\"javascript: uf_aceptar('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5', '$ls_denestpro1','$ls_denestpro2','$ls_denestpro3','$ls_denestpro4','$ls_denestpro5','$ls_estcla','$ls_spg_cuenta','$ls_mesaum','$ls_mesdis','$ld_fecha',$ld_monto);\">".$ls_codestpro2."</a></td>";
		 print "<td  align='center'><a href=\"javascript: uf_aceptar('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5', '$ls_denestpro1','$ls_denestpro2','$ls_denestpro3','$ls_denestpro4','$ls_denestpro5','$ls_estcla','$ls_spg_cuenta','$ls_mesaum','$ls_mesdis','$ld_fecha',$ld_monto);\">".$ls_codestpro3."</a></td>";
		 if($li_estmodest == 2)
		 {				
		  print "<td  align='center'><a href=\"javascript: uf_aceptar('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5', '$ls_denestpro1','$ls_denestpro2','$ls_denestpro3','$ls_denestpro4','$ls_denestpro5','$ls_estcla','$ls_spg_cuenta','$ls_mesaum','$ls_mesdis','$ld_fecha',$ld_monto);\">".$ls_codestpro4."</a></td>";
		  print "<td  align='center'><a href=\"javascript: uf_aceptar('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5', '$ls_denestpro1','$ls_denestpro2','$ls_denestpro3','$ls_denestpro4','$ls_denestpro5','$ls_estcla','$ls_spg_cuenta','$ls_mesaum','$ls_mesdis','$ld_fecha',$ld_monto);\">".$ls_codestpro5."</a></td>";
		 } 
		 print "<td  align='center'><a href=\"javascript: uf_aceptar('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5', '$ls_denestpro1','$ls_denestpro2','$ls_denestpro3','$ls_denestpro4','$ls_denestpro5','$ls_estcla','$ls_spg_cuenta','$ls_mesaum','$ls_mesdis','$ld_fecha',$ld_monto);\">".trim($ls_spg_cuenta)."</a></td>";
		 print "<td  align='center'><a href=\"javascript: uf_aceptar('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5', '$ls_denestpro1','$ls_denestpro2','$ls_denestpro3','$ls_denestpro4','$ls_denestpro5','$ls_estcla','$ls_spg_cuenta','$ls_mesaum','$ls_mesdis','$ld_fecha',$ld_monto);\">".$ls_mesdisminucion."</a></td>";
		 print "<td  align='center'><a href=\"javascript: uf_aceptar('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5', '$ls_denestpro1','$ls_denestpro2','$ls_denestpro3','$ls_denestpro4','$ls_denestpro5','$ls_estcla','$ls_spg_cuenta','$ls_mesaum','$ls_mesdis','$ld_fecha',$ld_monto);\">".$ls_mesaumento."</a></td>";
		 print "<td  align='center'><a href=\"javascript: uf_aceptar('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5', '$ls_denestpro1','$ls_denestpro2','$ls_denestpro3','$ls_denestpro4','$ls_denestpro5','$ls_estcla','$ls_spg_cuenta','$ls_mesaum','$ls_mesdis','$ld_fecha',$ld_monto);\">".$ld_fecha."</a></td>";
		 print "<td  align='right'><a href=\"javascript: uf_aceptar('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5', '$ls_denestpro1','$ls_denestpro2','$ls_denestpro3','$ls_denestpro4','$ls_denestpro5','$ls_estcla','$ls_spg_cuenta','$ls_mesaum','$ls_mesdis','$ld_fecha',$ld_monto);\">".$ld_montoaux."</a></td>";				
		 print "</tr>"; 
		 $rs_data->MoveNext();
 } 

}
print "</table>";	
?>
</form>
</body>
<script language="JavaScript">

function uf_aceptar(ls_codestpro1,ls_codestpro2,ls_codestpro3,ls_codestpro4,ls_codestpro5,ls_denestpro1,ls_denestpro2,
                    ls_denestpro3,ls_denestpro4,ls_denestpro5,ls_estcla,ls_spg_cuenta,ls_mesaum,ls_mesdis,ld_fecha,ld_monto)
{
 f=opener.document.form1;
 f.codestpro1.value=ls_codestpro1;
 f.denestpro1.value=ls_denestpro1;
 f.codestpro2.value=ls_codestpro2;
 f.denestpro2.value=ls_denestpro2;
 f.codestpro3.value=ls_codestpro3;
 f.denestpro3.value=ls_denestpro3;
 if(f.estmodest == 2)
 {
  f.codestpro4.value=ls_codestpro4;
  f.denestpro4.value=ls_denestpro4;
  f.codestpro5.value=ls_codestpro5;
  f.denestpro5.value=ls_denestpro5;
 } 
 f.txtcuenta.value = "";
 f.txtdenominacion.value = "";
 f.txtmonto.value="0,00";
 f.cuenta.value=ls_spg_cuenta;
 f.monto.value=ld_monto;
 f.estcla.value=ls_estcla;
 f.mes1.value=ls_mesdis;
 f.mes2.value=ls_mesaum;
 f.operacion.value="CARGAR_DT";
 f.action="sigesp_spg_p_modprog_trimestral.php";
 f.submit();
 close();
}

//Funciones de validacion de fecha.
function rellenar_cad(cadena,longitud,campo)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;
	
	total=longitud-lencad;
	for(i=1;i<=total;i++)
	{
		cadena_ceros=cadena_ceros+"0";
	}
	cadena=cadena_ceros+cadena;
	if(campo=="doc")
	{
		document.form1.txtdocumento.value=cadena;
	}
	else
	{
		document.form1.txtcomprobante.value=cadena;
	}

}

function ue_search()
  {
	  f=document.form1;
	  if(f.txtfechades.value > f.txtfechahas.value)
	  {
	   alert("La Fecha Desde no puede ser mayor a la Fecha Hasta");
	  }
	  else
	  { 
	   f.operacion.value="BUSCAR";
	   f.action="sigesp_cat_regmodpresupuestaria_trimestre.php";
	   f.submit();
	  } 
  }

 function catalogo_cuentasSPG()
 {
       f=document.form1;
       codest1 = f.codestpro1.value;
       codest2 = f.codestpro2.value;
  	   codest3 = f.codestpro3.value;
	   estmodest = f.estmodest.value;
       estcla = f.estcla.value;
	   destino="txtcuenta";
	   if(estmodest==1)
    {
		
	}
	else
	{
		codestpro4  = f.codestpro4.value;
		codestpro5  = f.codestpro5.value;
		codestpro4h = f.codestpro4h.value;
		codestpro5h = f.codestpro5h.value;
		
	}
	   if(estmodest==1)
	   {
		   if((codest1!="")&&(codest2!="")&&(codest3!=""))
		   {
			  pagina="sigesp_cat_ctasrep.php?codestpro1="+codest1+"&codestpro2="+codest2+"&codestpro3="+codest3
				+"&codestpro1h="+codest1+"&codestpro2h="+codest2+"&codestpro3h="+codest3+"&estclades="+estcla
				+"&estclahas="+estcla+"&destino="+destino;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		   }
		   else
		   {
			   alert("Debe completar la Estructura Presupuestaria");
		   }
	    }   
		else
		{
		   codest4=f.codestpro4.value;
		   codest5=f.codestpro5.value;
		   if((codest1!="")&&(codest2!="")&&(codest3!="")&&(codest4!="")&&(codest5!=""))
		   {
			   pagina="sigesp_cat_ctasrep.php?codestpro1="+codest1+"&codestpro2="+codest2+"&codestpro3="+codest3
				+"&codestpro4="+codest4+"&codestpro5="+codest5+"&codestpro1h="+codest1+"&codestpro2h="+codest2
				+"&codestpro3h="+codest3+"&codestpro4h="+codest4+"&codestpro5h="+codest5+"&estclades="+estcla
				+"&estclahas="+estcla+"&destino="+destino;
		       window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		   }
		   else
		   {
			   alert("Debe completar la programatica");
		   }
		}
 }
 
function catalogo_estpro1()
{
	   pagina="sigesp_cat_public_estpro1.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
    estcla = f.estcla.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&estcla="+estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	codestpro3=f.codestpro3.value;
	denestpro3=f.denestpro3.value;
	estmodest=f.estmodest.value;
    estcla = f.estcla.value;
	if(estmodest==1)
	{
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3=="")&&(denestpro3==""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="sigesp_cat_public_estpro.php?estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
		   alert("Seleccione la Estructura nivel 2");
		}
	}
}
function catalogo_estpro4()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	codestpro3=f.codestpro3.value;
	denestpro3=f.denestpro3.value;
    estcla = f.estcla.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!=""))
	{
		pagina="sigesp_cat_public_estpro4.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
				+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&estcla="+estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 3 ");
	}
}
function catalogo_estpro5()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	codestpro3=f.codestpro3.value;
	denestpro3=f.denestpro3.value;
	codestpro4=f.codestpro4.value;
	denestpro4=f.denestpro4.value;
	codestpro5=f.codestpro5.value;
	denestpro5=f.denestpro5.value;
    estcla = f.estcla.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!="")&&
	   (codestpro4!="")&&(denestpro4!="")&&(codestpro5=="")&&(denestpro5==""))
	{
			pagina="sigesp_cat_public_estpro5.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&codestpro4="+codestpro4
					+"&denestpro4="+denestpro4+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
			pagina="sigesp_cat_public_estprograma.php?estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
}

function  uf_format(obj)
{
	ldec_monto=obj.value;
	obj.value=uf_convertir(ldec_monto);
}

function currency_Format(fld, milSep, decSep, e) 
{ 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Enter 
	if (whichCode == 127) return true; // Enter 	
	if (whichCode == 9) return true; // Enter 	
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    /*if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; */
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
      fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
    return false; 
   }
   
 function finMes(oTxt){ 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    var nAno = parseInt(oTxt.value.substr(6), 10); 
    var nRes = 0; 
    switch (nMes){ 
     case 1: nRes = 31; break; 
     case 2: nRes = 28; break; 
     case 3: nRes = 31; break; 
     case 4: nRes = 30; break; 
     case 5: nRes = 31; break; 
     case 6: nRes = 30; break; 
     case 7: nRes = 31; break; 
     case 8: nRes = 31; break; 
     case 9: nRes = 30; break; 
     case 10: nRes = 31; break; 
     case 11: nRes = 30; break; 
     case 12: nRes = 31; break; 
    } 
    return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
   } 

   function valDia(oTxt){ 
    var bOk = false; 
    var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
    bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
    return bOk; 
   } 

   function valMes(oTxt){ 
    var bOk = false; 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
    return bOk; 
   } 

   function valAno(oTxt){ 
    var bOk = true; 
    var nAno = oTxt.value.substr(6); 
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
    if (bOk){ 
     for (var i = 0; i < nAno.length; i++){ 
      bOk = bOk && esDigito(nAno.charAt(i)); 
     } 
    } 
    return bOk; 
   } 
   
   function esDigito(sChr){ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
   }
 
 function valFecha(oTxt){ 
    var bOk = true; 
	
		if (oTxt.value != ""){ 
		 bOk = bOk && (valAno(oTxt)); 
		 bOk = bOk && (valMes(oTxt)); 
		 bOk = bOk && (valDia(oTxt)); 
		 bOk = bOk && (valSep(oTxt)); 
		 if (!bOk){ 
		  alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
		  oTxt.value = "01/01/1900"; 
		  oTxt.focus(); 
		 } 
		}
	 
   }
   
   function valSep(oTxt){ 
    var bOk = false; 
    var sep1 = oTxt.value.charAt(2); 
    var sep2 = oTxt.value.charAt(5); 
    bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
    bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
    return bOk; 
   } 
   
 function currencyDate(date)
  { 
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string,10);

			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}
			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string,10);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
			//alert(ls_long);


  //  return false; 
   }       
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>