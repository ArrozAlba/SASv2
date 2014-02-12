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
	require_once("class_folder/class_funciones_ins.php");
	$oi_fun_instala=new class_funciones_ins("../");
	$oi_fun_instala->uf_load_seguridad("INS","sigesp_ins_p_reprocesar_fechacomprobantes.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Reprocesar Fecha de Comprobantes</title>
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
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	require_once("class_folder/sigesp_ins_c_reprocesar_comprobantes.php");
	$io_reprocesar=new sigesp_ins_c_reprocesar_comprobantes();
	$ls_sistema="";
	$ls_operacion="NUEVO";	
	$lb_valido=false;
	if(array_key_exists("operacion",$_POST))
	{
		if(array_key_exists("cmbsistema",$_POST))
		{
			$ls_sistema=$_POST["cmbsistema"];
		}
		$ls_operacion= $_POST["operacion"];
	}
	if($ls_operacion=="EJECUTAR")
	{
		switch($ls_sistema)
		{
			case "SEP": // Sistema de Solicitud de Ejecución Presupuestaria
				$lb_valido=$io_reprocesar->uf_reprocesar_fecha_comprobante_sep($la_seguridad);
				break;
			case "SOC": // Sistema de Compras
				$lb_valido=$io_reprocesar->uf_reprocesar_fecha_comprobante_soc($la_seguridad);
				break;
			case "CXP": // Sistema de Cuentas por Pagar
				$lb_valido=$io_reprocesar->uf_reprocesar_fecha_comprobante_cxp($la_seguridad);
				break;
			case "SCB": // Sistema de Caja y Banco
				$lb_valido=$io_reprocesar->uf_reprocesar_fecha_comprobante_scb($la_seguridad);
				break;
			case "SOB": // Sistema de Obras
				$lb_valido=$io_reprocesar->uf_reprocesar_fecha_comprobante_sob($la_seguridad);
				break;
			case "SNO": // Sistema de Nómina
				$lb_valido=$io_reprocesar->uf_reprocesar_fecha_comprobante_sno($la_seguridad);
				break;
			case "SAF": // Sistema de Activos Fijos
				$lb_valido=$io_reprocesar->uf_reprocesar_fecha_comprobante_saf($la_seguridad);
				break;
			case "MODPRE": // Modificaciones Presupuestarias
				$lb_valido=$io_reprocesar->uf_reprocesar_fecha_comprobante_modpre($la_seguridad);
				break;
		}
		if($lb_valido)
		{
			$io_reprocesar->io_message->message("Proceso Ejecutado Satisfactoriamente");
		}
		else
		{
			$io_reprocesar->io_message->message("Ocurrio un error al reprocesar la fecha de los comprobantes");
		}
	}
	unset($io_reprocesar);
?>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Instala</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
</table>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$oi_fun_instala->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($oi_fun_instala);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<p>&nbsp;</p>
<table width="442" height="223" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="571" height="221" valign="top">
        <p>&nbsp;</p>
        <table width="360" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-ventana">
            <td colspan="4"><p>Reprocesar Fecha de Comprobantes </p>            </td>
          </tr>
          <tr class="formato-blanco">
            <td width="72" height="18">&nbsp;</td>
            <td width="286" colspan="3">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">Sistema 
            </div>
            <div align="right"></div></td>
            <td colspan="3"><div align="left">
              <label>
              <select name="cmbsistema" id="cmbsistema">
                <option value="" selected>--Seleccione Uno--</option>
                <option value="SEP">Solicitud de Ejecución Presupuestaria</option>
                <option value="SOC">Compras</option>
                <option value="CXP">Cuentas por Pagar</option>
                <option value="SCB">Caja y Banco</option>
                <option value="SOB">Obras</option>
                <option value="SNO">Nómina</option>
                <option value="SAF">Activos Fijos</option>
                <option value="MODPRE">Modificaciones Presupuestarias</option>
              </select>
              </label>
            </div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right"></div></td>
            <td colspan="3"><div align="left"></div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22" colspan="4"><div align="center">
              <input name="botejecutar" type="button" class="boton" id="botejecutar" onClick="javascript:uf_ejecutar();" value="Ejecutar">
            </div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="20">&nbsp;</td>
            <td colspan="3">&nbsp;</td>
          </tr>
        </table>
        <p>
          <input name="operacion" type="hidden" id="operacion">
        </p>
      </td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
function  uf_ejecutar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		if(f.cmbsistema.value!="")
		{
			f.operacion.value="EJECUTAR";
			f.action="sigesp_ins_p_reprocesar_fechacomprobantes.php";
			f.submit();
		}
		else
		{
			alert("Debe seleccionar un sistema.");
		}
	}
	else
	{
      alert("No tiene permiso para realizar esra operacion");	
	}	
}
</script>
</html>