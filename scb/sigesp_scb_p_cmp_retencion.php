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
	require_once("../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("class_funciones_banco.php");
	$io_fun_scb=new class_funciones_banco();
	$io_fun_scb->uf_load_seguridad("SCB","sigesp_scb_p_cmp_retencion.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   
   //--------------------------------------------------------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 19/04/2007				Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_mes,$ls_agno,$ls_operacion,$ls_tipproben,$io_funciones,$ls_provbenedesde,$io_fun_scb,$ls_provbenehasta;
		
		$arr_fecha        = getdate();
		$ls_agno          = $arr_fecha["year"];
		$ls_mes           = $arr_fecha["mon"];
		$ls_mes           = $io_funciones->uf_cerosizquierda($ls_mes,2);
		$ls_tipproben     = "-";
		$ls_provbenedesde = "";
		$ls_provbenehasta = "";
		$ls_operacion     = $io_fun_scb->uf_obteneroperacion();
   }
   //--------------------------------------------------------------------------------------------------------------

   //--------------------------------------------------------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 23/04/2007			Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_tipproben,$ls_mes,$ls_agno,$ls_provbenedesde,$ls_provbenehasta;
		
		$ls_mes           = $_POST["mes"];
	    $ls_agno          = $_POST["agno"];
	    $ls_tipproben     = $_POST["estprov"];
	    $ls_provbenedesde = $_POST["txtprovbendesde"];
	    $ls_provbenehasta = $_POST["txtprovbenhasta"];
   }
   //--------------------------------------------------------------------------------------------------------------	

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
<title>Comprobante Retenci&oacute;n Iva</title>
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
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
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
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php
require_once("../shared/class_folder/ddlb_meses.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("class_folder/sigesp_scb_c_cmp_retencion.php");
$io_cmbmes    = new ddlb_meses();
$io_retencion = new sigesp_scb_c_cmp_retencion("../");
$io_msg       = new class_mensajes();
$ls_conivaret = $_SESSION["la_empresa"]["estretiva"];
$lb_chepro    = "checked";
$lb_cheben    = "";
uf_limpiarvariables();

	switch($ls_operacion)
	{
		case "NEW":
			if($ls_conivaret=="C")
			{
				$io_msg->message("Los Comprobantes de Retenciones IVA se generan por el Módulo de Cuentas por Pagar !!!");	
			}
			else
			{
			  uf_load_variables(); 
			}
		break;
        
		case "PROCESAR":
			uf_load_variables();
			if ($ls_tipproben=='P' || $ls_tipproben=='-')
			   {
				 $lb_chepro = "checked";
				 $lb_cheben = "";
			   }
			elseif($ls_tipproben=='B')
			  {
				 $lb_cheben = "checked";
				 $lb_chepro = "";  
			  }
			$li_totrocmp = $io_retencion->uf_procesar_comprobante_retencion($ls_mes,$ls_agno,$ls_provbenedesde,$ls_provbenehasta,$ls_tipproben,$la_numcmp,$la_seguridad);
	        if ($li_totrocmp>0)
	           {
	             for ($li_i=1;$li_i<=$li_totrocmp;$li_i++)
		             {
	                   $io_msg->message("Se procesó satisfactoriamente el Comprobante Nº.".$la_numcmp[$li_i]);
		             } 
	           }
	        else
	           {
	             $io_msg->message("No se generaron Comprobantes de Retención verifique sus datos !!!");
	           }	
		break;
		
		case "VALIDARMES":
		  $io_msg->message("Sólo es posible generar comprobantes de retención de el mes en curso !!!");
		break;
	}
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">  
  <p>
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_scb->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_scb);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  </p>
  <table width="474" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr class="titulo-celdanew">
      <td width="472" height="22" style="text-align:center" class="titulo-celdanew">Comprobante de Retenci&oacute;n Iva </td>
    </tr>
    <tr>
      <td height="13" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="48" align="center"><table width="398" border="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celda">
          <td height="15" colspan="4" style="text-align:center">Periodo</td>
          </tr>
        <tr>
          <td width="66" height="22" style="text-align:right">Mes</td>
          <td width="113" style="text-align:left"><?php $io_cmbmes->sel_mes($ls_mes); ?></td>
          <td width="88" style="text-align:right">A&ntilde;o</td>
          <td width="121" style="text-align:left"><input name="agno" type="text" id="agno" style="text-align:center " value="<?php print $ls_agno;?>" size="10" maxlength="4"></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="73" align="center">
        <table width="398" border="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celda">
            <td colspan="4" align="center"><strong>Proveedor / Beneficiario </strong></td>
          </tr>
          <tr>
            <td height="15" colspan="4" align="right">&nbsp;</td>
          </tr>
            <?php
 			if($ls_conivaret!="C")
			{
			?>
          <tr>
            <td height="22" colspan="4" align="right">
                  <div align="center">Proveedor
                    <input name="estprov" type="radio" class="sin-borde"  onClick="javascript:uf_cambio()" value="P" <?php echo $lb_chepro ?>>
                  Beneficiario
                  <input name="estprov" type="radio" class="sin-borde"  onClick="javascript:uf_cambio()" value="B" <?php echo $lb_cheben ?>>
            </div>			</td>
          </tr>
          <tr>
            <td height="15" colspan="4" align="right">&nbsp;</td>
          </tr>
          <tr>
            <td width="40" height="22" align="right">Desde</td>
            <td width="158" align="left"><input name="txtprovbendesde" type="text" id="txtprovbendesde" style="text-align:center" value="<?php echo $ls_provbenedesde ?>">
            <a href="javascript:uf_catproben_desde('D');"><img src="../shared/imagebank/tools15/buscar.gif" name="buscar1" width="15" height="15" border="0"  id="buscar1" onClick="document.form1.hidrangocodigos.value=1"></a></td>
            <td width="43" align="right">Hasta</td>
            <td width="147" align="left"><input name="txtprovbenhasta" type="text" id="txtprovbenhasta" style="text-align:center" value="<?php echo $ls_provbenehasta ?>">
            <a href="javascript:uf_catproben_hasta('H');"><img src="../shared/imagebank/tools15/buscar.gif" name="buscar2" width="15" height="15" border="0" id="buscar2"  onClick="document.form1.hidrangocodigos.value=2"></a></td>
          </tr>
			<?php }?>
      </table>      </td>
    </tr>
    <tr>
      <td height="22" align="center">
        <p align="right"><a href="javascript:ue_procesar('<?php print $ls_conivaret ?>');"><img src="../shared/imagebank/tools20/ejecutar.gif" width="20" height="20" border="0">Ejecutar</a></p></td>
    </tr>
  </table> 
</table>
 <div align="center">
   <input name="hidflag" type="hidden" id="hidflag" value="<?php print $ls_flag ?>">
   <input name="hidrangocodigos" type="hidden" id="hidrangocodigos">
   <input name="operacion" type="hidden" id="operacion">
   </p>
 </div>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
f = document.form1;
function ue_nuevo()
{
  li_incluir=f.incluir.value;	
  if (li_incluir==1)
	 {	
	   f.operacion.value="NEW";
	   f.action="sigesp_scb_p_cmp_retencion.php";
	   f.submit();	  
	 }
  else
	 {
	   alert("No tiene permiso para realizar esta operación !!!");
	 }  
}
	
function ue_procesar(as_modretiva)
{
  li_ejecutar=f.ejecutar.value;	
  if (li_ejecutar==1)
	 {	
	   if (as_modretiva=='B')
	      {
		    f.operacion.value="PROCESAR";
		    f.action="sigesp_scb_p_cmp_retencion.php";
		    f.submit();		  
		  }
	   else
	      {
		    alert("Los Comprobantes de Retenciones IVA se generan por el Módulo de Cuentas por Pagar !!!");
		  }
	 }
  else
	 {
	   alert("No tiene permiso para realizar esta operación !!!");
	 }  
}
    
function uf_cambio()
{
  f.txtprovbendesde.value="";
  f.txtprovbenhasta.value="";
}

function validarmes()
{
  f.operacion.value="VALIDARMES";
  f.action="sigesp_scb_p_cmp_retencion.php";
  f.submit();
}

function uf_catproben_desde()
{
  if (f.estprov[0].checked)
	 {
	   window.open("sigesp_cat_prov_general.php?obj=txtprovbendesde","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
	 }
  else if(f.estprov[1].checked)
	 {
	   window.open("sigesp_cat_bene_general.php?obj=txtprovbendesde","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
	 }
}

function uf_catproben_hasta()
{
  if (f.estprov[0].checked)
	 {
	   window.open("sigesp_cat_prov_general.php?obj=txtprovbenhasta","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
	 }
  else if(f.estprov[1].checked)
	 {
	   window.open("sigesp_cat_bene_general.php?obj=txtprovbenhasta","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
	 }
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>