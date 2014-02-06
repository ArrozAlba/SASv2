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
	require_once("class_folder/class_funciones_rcm.php");
	$io_fun_rcm=new class_funciones_rcm();
	$io_fun_rcm->uf_load_seguridad("RCM","sigesp_rcm_p_modulos.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $lb_procfg,$lb_proscg,$lb_prospi,$lb_prospg,$lb_prorcp,$lb_prosep,$lb_prosoc,$lb_prosiv;
   		global $lb_procxp,$lb_proscb,$lb_prosaf,$lb_proscv,$lb_prosno,$lb_prohist;
		
		$lb_procfg=false;
		$lb_proscg=false;
		$lb_prospi=false;
		$lb_prospg=false;
		$lb_prorcp=false;
		$lb_prosep=false;
		$lb_prosoc=false;
		$lb_prosiv=false;
		$lb_procxp=false;
		$lb_proscb=false;
		$lb_prosaf=false;
		$lb_proscv=false;
		$lb_prosno=false;
		$lb_prohist=false;
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>M&ograve;dulo de Reconversion Monetaria </title>
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
<link href="css/general.css" rel="stylesheet" type="text/css">
<link href="css/tablas.css" rel="stylesheet" type="text/css">
<link href="css/ventanas.css" rel="stylesheet" type="text/css">
<link href="css/cabecera.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=new class_mensajes();
	$ls_operacion=$io_fun_rcm->uf_obteneroperacion();
	switch ($ls_operacion)
	{
		case"PROCESAR":
			$lb_valido=false;
			$ls_codsis=$io_fun_rcm->uf_obtenervalor("sistema","");
			if(!empty($ls_codsis))
			{
				$ls_codsisaux=strtolower($ls_codsis);
				require_once("class_folder/sigesp_rcm_c_".$ls_codsisaux.".php");
				switch ($ls_codsis)
				{
					case"SCV":
						$io_rcm= new sigesp_rcm_c_scv();
						$lb_valido=$io_rcm->uf_convertir_data($la_seguridad);
					break;
					case"CXP":
						$io_rcm= new sigesp_rcm_c_cxp();
						$lb_valido=$io_rcm->uf_convertir_data($la_seguridad);
					break;
					case"SOC":
						$io_rcm= new sigesp_rcm_c_soc();
						$lb_valido=$io_rcm->uf_convertir_data($la_seguridad);
					break;
					case"SNO":
						$io_rcm= new sigesp_rcm_c_sno();
						$lb_valido=$io_rcm->uf_convertir_data($la_seguridad);
					break;
					case"HIST":
						$io_rcm= new sigesp_rcm_c_hist();
						$lb_valido=$io_rcm->uf_convertir_data($la_seguridad);
					break;
					case"SCG":
						$io_rcm= new sigesp_rcm_c_scg();
						$lb_valido=$io_rcm->uf_convertir_data($la_seguridad);
					break;
					case"RPC":
						$io_rcm= new sigesp_rcm_c_rpc();
						$lb_valido=$io_rcm->uf_convertir_data($la_seguridad);
					break;
					case"SIV":
						$io_rcm= new sigesp_rcm_c_siv();
						$lb_valido=$io_rcm->uf_convertir_data($la_seguridad);
					break;
					case"SAF":
						$io_rcm= new sigesp_rcm_c_saf();
						$lb_valido=$io_rcm->uf_convertir_data($la_seguridad);
					break;
					case"CFG":
						$io_rcm= new sigesp_rcm_c_cfg();
						$lb_valido=$io_rcm->uf_convertir_data($la_seguridad);
					break;
					case"SEP":
						$io_rcm= new sigesp_rcm_c_sep();
						$lb_valido=$io_rcm->uf_convertir_data($la_seguridad);
					break;
					case"SCB":
						$io_rcm= new sigesp_rcm_c_scb();
						$lb_valido=$io_rcm->uf_convertir_data($la_seguridad);
					break;
					case"SPG":
						$io_rcm= new sigesp_rcm_c_spg();
						$lb_valido=$io_rcm->uf_convertir_data($la_seguridad);
					break;
					case"SPI":
						$io_rcm= new sigesp_rcm_c_spi();
						$lb_valido=$io_rcm->uf_convertir_data($la_seguridad);
					break;
				}
			}		
			if($lb_valido)
			{
				$io_msg->message("La Reconversion Monetaria se realizo Exitosamente");
			}
			else
			{
				$io_msg->message("Fallo el proceso de Reconversion Monetaria");
			}
		break;
	}
			uf_limpiarvariables();
			$lb_valido=$io_fun_rcm->uf_load_modulos_reconvertidos();
			if($lb_valido)
			{
				$li_totrow=$io_fun_rcm->DS->getRowCount("entry");
				for($li_i=1;$li_i<=$li_totrow;$li_i++)
				{
					$ls_entry= trim($io_fun_rcm->DS->data["entry"][$li_i]);
					$ls_value= $io_fun_rcm->DS->data["value"][$li_i];
					switch($ls_entry)
					{
						case "CFG":
							$lb_procfg=true;
						break;
						case "SCG":
							$lb_proscg=true;
						break;
						case "SPI":
							$lb_prospi=true;
						break;
						case "SPG":
							$lb_prospg=true;
						break;
						case "RPC":
							$lb_prorcp=true;
						break;
						case "SEP":
							$lb_prosep=true;
						break;
						case "SOC":
							$lb_prosoc=true;
						break;
						case "SIV":
							$lb_prosiv=true;
						break;
						case "CXP":
							$lb_procxp=true;
						break;
						case "SCB":
							$lb_proscb=true;
						break;
						case "SAF":
							$lb_prosaf=true;
						break;
						case "SCV":
							$lb_proscv=true;
						break;
						case "SNO":
							$lb_prosno=true;
						break;
						case "HIST":
							$lb_prohist=true;
						break;
					}
				}
			}

?>
<table width="800" border="0" align="center" cellpadding="1" cellspacing="0" class="contorno">
  <tr>
    <td width="570" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="800" height="40"></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"></a><a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_rcm->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='../index_modules.php'");
	unset($io_fun_rcm);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="598" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="596" height="22" class="titulo-ventana">M&oacute;dulos a Reconvertir</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><table width="580" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="580">&nbsp;</td>
        </tr>
        <tr>
          <td><table width="539" height="310" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">

            <tr>
              <td width="71" height="22"><div align="center"><img src="iconos/instalar.gif" width="40" height="40" border="0" title="Configuracion"></div></td>
              <td width="380" height="22" class="titulo-conect"><div align="left"><strong>Sistema de Configuracion</strong> (CFG) </div></td>
              <td width="86" height="22"><a href="javascript:ue_procesar('CFG');"><img src="tools20/calcular.png" width="30" height="30" border="0" title="Reconvertir CFG"></a><?php if($lb_procfg){?><img src="tools20/aprobado.gif" width="20" height="20" title="Exitoso"><?php }?></td>
            </tr>
            <tr>
              <td height="22"><div align="center"><img src="iconos/contabilidad.gif" width="40" height="40" border="0"></div></td>
              <td height="22" class="titulo-conect"><div align="left"><strong>Sistema de </strong>Contabilidad  (SCG) </div></td>
              <td height="22"><a href="javascript:ue_procesar('SCG');"><img src="tools20/calcular.png" width="30" height="30" border="0" title="Reconvertir SCG"></a><?php if($lb_proscg){?><img src="tools20/aprobado.gif" width="20" height="20" title="Exitoso"><?php }?></td>
            </tr>
            <tr>
              <td height="22"><div align="center"><img src="iconos/ingresos.gif" width="40" height="40" border="0"></div></td>
              <td height="22" class="titulo-conect"><div align="left"><strong>Sistema de </strong>Contabilidad Presupuestaria de Ingreso (SPI) </div></td>
              <td height="22"><a href="javascript:ue_procesar('SPI');"><img src="tools20/calcular.png" width="30" height="30" border="0" title="Reconvertir SPI"></a><?php if($lb_prospi){?><img src="tools20/aprobado.gif" width="20" height="20" title="Exitoso"><?php }?></td>
            </tr>
            <tr>
              <td height="22"><div align="center"><img src="iconos/gastos.gif" width="40" height="40" border="0"></div></td>
              <td height="22" class="titulo-conect"><div align="left"><strong>Sistema de </strong>Contabilidad Presupuestaria de Gasto (SPG) </div></td>
              <td height="22"><a href="javascript:ue_procesar('SPG');"><img src="tools20/calcular.png" width="30" height="30" border="0" title="Reconvertir SPG"></a><?php if($lb_prospg){?><img src="tools20/aprobado.gif" width="20" height="20" title="Exitoso"><?php }?></td>
            </tr>
            <tr>
              <td height="22"><div align="center"><img src="iconos/proveedores.gif" width="40" height="40" border="0"></div></td>
              <td height="22" class="titulo-conect"><div align="left"><strong>Sistema de </strong>Registro de Proveedores y Contratistas (RPC) </div></td>
              <td height="22"><a href="javascript:ue_procesar('RPC');"><img src="tools20/calcular.png" width="30" height="30" border="0" title="Reconvertir RPC"></a><?php if($lb_prorcp){?><img src="tools20/aprobado.gif" width="20" height="20" title="Exitoso"><?php }?></td>
            </tr>
            <tr>
              <td height="22"><div align="center"><img src="iconos/presupuestaria.gif" width="40" height="40" border="0"></div></td>
              <td height="22" class="titulo-conect"><div align="left"><strong>Sistema de </strong>Solicitudes de Ejecuci&oacute;n Presupuestaria (SEP) </div></td>
              <td height="22"><a href="javascript:ue_procesar('SEP');"><img src="tools20/calcular.png" width="30" height="30" border="0" title="Reconvertir SEP"></a><?php if($lb_prosep){?><img src="tools20/aprobado.gif" width="20" height="20" title="Exitoso"><?php }?></td>
            </tr>
            <tr>
              <td height="22"><div align="center"><img src="iconos/compras.gif" width="40" height="40" border="0"></div></td>
              <td height="22" class="titulo-conect"><div align="left"><strong>Sistema de </strong>Ordenes de Compra (SOC) </div></td>
              <td height="22"><a href="javascript:ue_procesar('SOC');"><img src="tools20/calcular.png" width="30" height="30" border="0" title="Reconvertir SOC"></a><?php if($lb_prosoc){?><img src="tools20/aprobado.gif" width="20" height="20" title="Exitoso"><?php }?></td>
            </tr>
            <tr>
              <td height="22"><div align="center"><img src="iconos/cart.gif" width="40" height="40" border="0"></div></td>
              <td height="22" class="titulo-conect"><div align="left"><strong>Sistema de </strong>Inventario (SIV) </div></td>
              <td height="22"><a href="javascript:ue_procesar('SIV');"><img src="tools20/calcular.png" width="30" height="30" border="0" title="Reconvertir SIV"></a><?php if($lb_prosiv){?><img src="tools20/aprobado.gif" width="20" height="20" title="Exitoso"><?php }?></td>
            </tr>
            <tr>
              <td height="22"><div align="center"><img src="iconos/pagar.gif" width="40" height="40" border="0"></div></td>
              <td height="22" class="titulo-conect"><div align="left"><strong>Sistema de </strong>Cuentas por Pagar (CXP) </div></td>
              <td height="22"><a href="javascript:ue_procesar('CXP');"><img src="tools20/calcular.png" width="30" height="30" border="0" title="Reconvertir CXP"></a><?php if($lb_procxp){?><img src="tools20/aprobado.gif" width="20" height="20" title="Exitoso"><?php }?></td>
            </tr>
            <tr>
              <td height="22"><div align="center"><img src="iconos/banco.gif" width="40" height="40" border="0"></div></td>
              <td height="22" class="titulo-conect"><div align="left"><strong>Sistema de </strong>Caja y Banco (SCB) </div></td>
              <td height="22"><a href="javascript:ue_procesar('SCB');"><img src="tools20/calcular.png" width="30" height="30" border="0" title="Reconvertir SCB"></a><?php if($lb_proscb){?><img src="tools20/aprobado.gif" width="20" height="20" title="Exitoso"><?php }?></td>
            </tr>
            <tr>
              <td height="22"><div align="center"><img src="iconos/fijos.gif" width="40" height="40" border="0"></div></td>
              <td height="22" class="titulo-conect"><div align="left"><strong>Sistema de </strong>Activos Fijos (SAF) </div></td>
              <td height="22"><a href="javascript:ue_procesar('SAF');"><img src="tools20/calcular.png" width="30" height="30" border="0" title="Reconvertir SAF"></a><?php if($lb_prosaf){?><img src="tools20/aprobado.gif" width="20" height="20" title="Exitoso"><?php }?></td>
            </tr>
            <tr>
              <td height="22"><div align="center"><img src="iconos/viaticos.gif" width="40" height="40"></div></td>
              <td height="22" class="titulo-conect"><div align="left"><strong>Sistema de </strong>Control de Viaticos (SCV) </div></td>
              <td height="22"><a href="javascript:ue_procesar('SCV');"><img src="tools20/calcular.png" width="30" height="30" border="0" title="Reconvertir SCV"></a><?php if($lb_proscv){?><img src="tools20/aprobado.gif" width="20" height="20" title="Exitoso"><?php }?></td>
            </tr>
            <tr>
              <td height="22"><div align="center"><img src="iconos/nomina.gif" width="40" height="40" border="0"></div></td>
              <td height="22" class="titulo-conect"><div align="left"><strong>Sistema de </strong>Nomina (SNO) </div></td>
              <td height="22"><a href="javascript:ue_procesar('SNO');"><img src="tools20/calcular.png" width="30" height="30" border="0" title="Reconvertir SNO"><?php if($lb_prosno){?></a><img src="tools20/aprobado.gif" width="20" height="20" title="Exitoso"><?php }?></td>
            </tr>
            <tr>
              <td height="22"><div align="center"><img src="iconos/redirects.gif" width="40" height="40" border="0"></div></td>
              <td height="22" class="titulo-conect"><div align="left">Historicos de Nomina</div></td>
              <td height="22"><a href="javascript:ue_procesar('HIST');"><img src="tools20/calcular.png" width="30" height="30" border="0" title="Reconvertir HISTORICOS"></a><?php if($lb_prohist){?><img src="tools20/aprobado.gif" width="20" height="20" title="Exitoso"><?php }?></td>
            </tr>

          </table></td>
        </tr>
        <tr>
          <td><input name="sistema" type="hidden" id="sistema">
            <input name="operacion" type="hidden" id="operacion"></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p align="center"><br>
  </p>
  </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_procesar(ls_codsis)
{
	f=document.form1;	
    f.sistema.value=ls_codsis;            
    f.operacion.value="PROCESAR"; 
	f.action="sigesp_rcm_p_modulos.php";
    f.submit();     
}
function ue_cerrar()
{
	location.href = "../index_modules.php";
}
</script>
</html>
