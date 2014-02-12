<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_cmp_ret_mcp.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<title>Comprobante de Retenciones Municipales</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
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
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<?Php
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/ddlb_meses.php");
require_once("sigesp_scb_c_cmp_ret_mcp.php");
require_once("../shared/class_folder/class_mensajes.php");

$io_grid		  = new grid_param();
$sig_inc		  = new sigesp_include();
$con			  = $sig_inc->uf_conectar();
$fun			  = new class_funciones();
$ddlb_mes		  = new ddlb_meses();
$in_class_cmp_ret = new sigesp_scb_c_cmp_ret('0000000003');
$io_msg			  = new class_mensajes();

	$arre       = $_SESSION["la_empresa"];
	$ls_empresa = $arre["codemp"];
    $la_emp     = $_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion= $_POST["operacion"];
	$ls_tipo     = $_POST["rb_provbene"];
	$ls_mes      = $_POST["mes"];
	$ls_agno     = $_POST["agno"];
	$ls_provbenedesde = $_POST["txtprovbendesde"];
	$ls_provbenehasta = $_POST["txtprovbenhasta"];
}
else
{
	//    Validando si las retenciones municipales se deben hacer por Banco o por Cuentas por pagar
	require_once("sigesp_scb_c_config.php");
	$in_classconfig=new sigesp_scb_c_config($la_seguridad);
	$ls_fuente=$in_classconfig->uf_select_fuente();
	if($ls_fuente!=false)
	{
		if($ls_fuente=="C")
		{
			print "<script>";
			print "alert('Los comprobantes deben ser generados a través del módulo de Cuentas por Pagar');";
			print "location.href='sigespwindow_blank.php';";
			print "</script>";
		}
	}
	else
	{
		$io_msg->message("Error al seleccionar la fuente");
	}
	$ls_operacion="";	
	$arr_fecha=getdate();
	$ls_agno=$arr_fecha["year"];
	$ls_mes=$arr_fecha["mon"];
	$ls_tipo="-";
	$ls_mes      = $fun->uf_cerosizquierda($ls_mes,2);
	$ls_provbenedesde = "";
	$ls_provbenehasta = "";
}

	if($ls_operacion=="PROCESAR")
	{		
		$ls_fecha_actual=date("m");
		if($ls_fecha_actual!=$ls_mes)
		{
			$io_msg->message("El mes de elaboración del pago no coincide con el mes actual. Favor modificar la fecha del servidor para generar el comprobante");
		}
		else
		{
			$in_class_cmp_ret->uf_comprobante_ret($ls_mes,$ls_agno,$ls_provbenedesde,$ls_provbenehasta,$ls_tipo);		
		}
	}
	
	if($ls_tipo=='-')
	{
		$rb_n="checked";
		$rb_p="";
		$rb_b="";			
	}
	if($ls_tipo=='P')
	{
		$rb_n="";
		$rb_p="checked";
		$rb_b="";			
	}
	if($ls_tipo=='B')
	{
		$rb_n="";
		$rb_p="";
		$rb_b="checked";			
	}
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action=""> 
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?> 
  <table width="474" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
   
    <tr>
      <td width="472"></td>
    </tr>
    <tr class="titulo-ventana">
      <td width="472" height="22" colspan="4" align="center">Comprobante de Retenciones Municipales </td>
    </tr>
    <tr>
      <td height="13" colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="55" colspan="4" align="center"><table width="398" border="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celda">
          <td colspan="4"><div align="center"><strong>Periodo</strong></div></td>
        </tr>
        <tr>
          <td width="66" height="22"><div align="right">Mes
          </div></td>
          <td width="113"><div align="left">
            	<?php $ddlb_mes->sel_mes($ls_mes); //Combo que contiene los meses del año y retorna selecciona el que el ususario tenga acutalmente ?>
          </div></td>
          <td width="88"><div align="right">A&ntilde;o            </div></td>
          <td width="121"><div align="left">
            <input name="agno" type="text" id="agno" style="text-align:center " value="<?php print $ls_agno;?>" size="10" maxlength="4" readonly="true">
</div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="78" colspan="4" align="center">
        <table width="398" border="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celda">
            <td colspan="4" align="center"><strong>Proveedor / Beneficiario </strong></td>
          </tr>
          <tr>
            <td height="22" colspan="4" align="right"><table width="249" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="353"><label>
                  <input type="radio" name="rb_provbene" id="radio2" value="P" class="sin-borde" style="width:10 ; height:10" onClick="javascript:uf_verificar_provbene(document.form1.tipo);" <?php print $rb_p;?>>
      Proveedor</label>
                    <label>
                    <input type="radio" name="rb_provbene" id="radio2" value="B" class="sin-borde"   style="width:10 ; height:10" onClick="javascript:uf_verificar_provbene(document.form1.tipo);" <?php print $rb_b;?>>
      Beneficiario</label>
                    <label>
                    <input name="rb_provbene" type="radio"  class="sin-borde" id="radio2" style="width:10 ; height:10" onClick="javascript:uf_verificar_provbene(document.form1.tipo);" value="-" checked <?php print $rb_n;?>>
      Ninguno</label>
                    <input name="tipo" type="hidden" id="tipo"></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td width="39" height="22" align="right">Desde</td>
            <td width="159" align="left"><input name="txtprovbendesde" type="text" id="txtprovbendesde">
              <a href="javascript:cat_desde()"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a></td>
            <td width="43" align="right">Hasta</td>
            <td width="147" align="left"><input name="txtprovbenhasta" type="text" id="txtprovbenhasta">
              <a href="javascript:cat_hasta()"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a></td>
          </tr>
      </table>      </td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center">
        <p align="right"><a href="javascript:ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" width="20" height="20" border="0">Ejecutar</a></p>        </td>

    </tr>
  </table>
 
</table>

<input name="operacion" type="hidden" id="operacion">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function ue_procesar()
	{
	  f=document.form1;
	  
	  f.operacion.value="PROCESAR";
	  f.action="sigesp_scb_p_cmp_ret_mcp.php";
	  f.submit();	  
	}


	function cat_desde()
	{
		f=document.form1;
		if(f.rb_provbene[0].checked)
		{
			window.open("sigesp_cat_prov_general.php?obj=txtprovbendesde","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else if(f.rb_provbene[1].checked)
		{
			window.open("sigesp_cat_bene_general.php?obj=txtprovbendesde","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		}
	}
	function cat_hasta()
	{
		f=document.form1;
		if(f.rb_provbene[0].checked)
		{
			window.open("sigesp_cat_prov_general.php?obj=txtprovbenhasta","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else if(f.rb_provbene[1].checked)
		{
			window.open("sigesp_cat_bene_general.php?obj=txtprovbenhasta","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		}
	}
	
	function uf_verificar_provbene(obj)
	{
		f=document.form1;
		if((f.rb_provbene[0].checked)&&(obj.value!='P'))
		{
			f.tipo.value='P';
			f.txtprovbendesde.value="";
			f.txtprovbenhasta.value="";	
		}
		if((f.rb_provbene[1].checked)&&(obj.value!='B'))
		{
			f.tipo.value='B';			
			f.txtprovbendesde.value="";
			f.txtprovbenhasta.value="";	
		}
		if((f.rb_provbene[2].checked)&&(obj.value!='N'))
		{
			f.tipo.value='N';			
			f.txtprovbendesde.value="";
			f.txtprovbenhasta.value="";	
		}
	}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>