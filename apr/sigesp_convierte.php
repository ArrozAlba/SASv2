<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Apertura de Sigesp</title>

<!--<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script> -->
<script language="JavaScript" type="text/javascript" src="js/ajax.js"></script>
<meta http-equiv="imagetoolbar" content="no">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css">
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
.xstooltip 
{
    visibility: hidden; 
    position: absolute; 
    top: 0;  
    left: 0; 
    z-index: 2; 

    font: normal 8pt sans-serif; 
    padding: 3px; 
    border: solid 1px;
}
</style>


<?php 
	$ls_modulo = "";	
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion = $_POST["operacion"];
		$ls_modulo = $_POST['modulo'];
	}
	else
	{
		$ls_operacion ="";
	}	
?>

</head>
 
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <!--  <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu2.js"></script></td>
  -->
  </tr>
</table>

<table width="780" border="0" align="center" cellpadding="1" cellspacing="0" class="contorno">
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"></a><a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="form1" id="form1" method="post" ><div align="center">
	<input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion ?>">
<?php 
	require_once("class_folder/class_validacion.php");
	$io_validacion=new class_validacion;
	$io_validacion->uf_select_sistema_apertura("SSS",$ls_disablesss);
	$io_validacion->uf_select_sistema_apertura("RPC",$ls_disablerpc);
	$io_validacion->uf_select_sistema_apertura("SCG",$ls_disablescg);
	$io_validacion->uf_select_sistema_apertura("SIV",$ls_disablesiv);
	$io_validacion->uf_select_sistema_apertura("SEP",$ls_disablesep);
	$io_validacion->uf_select_sistema_apertura("SOC",$ls_disablesoc);
	$io_validacion->uf_select_sistema_apertura("CXP",$ls_disablecxp);
	$io_validacion->uf_select_sistema_apertura("SCB",$ls_disablescb);
	$io_validacion->uf_select_sistema_apertura("SAF",$ls_disablesaf);
	$io_validacion->uf_select_sistema_apertura("SCV",$ls_disablescv);
	$io_validacion->uf_select_sistema_apertura("SNO",$ls_disablesno);
    unset($io_validacion);
	if($ls_modulo=='CONTABILIDAD')		
	{	
		require_once("sigesp_copia_scgspgspi.php");
		$isno_copia_scg = new sigesp_copia_scgspgspi();	
		if($ls_operacion=="COPIA")
		{	
			$isno_copia_scg->ue_copiar_scgpsgspi_basico();
			?>			
			<script language='JavaScript'> 
			f=document.form1;
			f.action='sigesp_apr_basicos.php'; 
			f.operacion.value="MOSTRAR";
			f.submit(); 
			</script>
			<?php
		}
		if ($ls_operacion=="LIMPIA")
		{
			$isno_copia_scg->ue_limpiar_scgpsgspi_basico();				
		}
		unset($isno_copia_scg);
	}
	if($ls_modulo=='CUENTAS POR PAGAR')
	{
		require_once("sigesp_copia_cxp.php");
		$isno_copia_cxp = new sigesp_copia_cxp();	
		if($ls_operacion=="COPIA")
		{
			$isno_copia_cxp->ue_copiar_cxp_basico();
			?>			
				<script language='JavaScript'> 
					f=document.form1;
					f.action='sigesp_apr_basicos.php'; 
					f.operacion.value="MOSTRAR";
					f.submit(); 
				</script>
			<?php
		}
		if($ls_operacion=="LIMPIA")
		{	
			$isno_copia_cxp->ue_limpiar_cxp_basico();				
		}		
		unset($isno_copia_cxp);
	}
	if($ls_modulo=='SOLICITUDES DE EJECUCION PRESUPUESTARIA')			
	{
		require_once("sigesp_copia_sep.php");
		$isno_copia_sep = new sigesp_copia_sep();	
		if($ls_operacion=="COPIA")
		{
			$isno_copia_sep->ue_copiar_sep_basico();
			?>
				<script language='JavaScript'> 
					f=document.form1;
					f.action='sigesp_apr_basicos.php'; 
					f.operacion.value="MOSTRAR";
					f.submit(); 
				</script>
			<?php			
		}
		if($ls_operacion=="LIMPIA")
		{	
			$isno_copia_sep->ue_limpiar_sep_basico();				
		}		
		unset($isno_copia_sep);
	}		
	if ($ls_modulo=='SEGURIDAD')			
	{
		require_once("sigesp_copia_sss.php");
		$isno_copia_sss = new sigesp_copia_sss();	
		if ($ls_operacion=="COPIA")
		{
			$isno_copia_sss->ue_copiar_sss_basico();
			?>
				<script language='JavaScript'> 
					f=document.form1;
					f.action='sigesp_apr_basicos.php'; 
					f.operacion.value="MOSTRAR";
					f.submit(); 
				</script>
			<?php			
		}
		if ($ls_operacion=="LIMPIA")
		{	
			$isno_copia_sss->ue_limpiar_sss_basico();				
		}		
		unset($isno_copia_sss);
	}		
	if ($ls_modulo=='PROVEEDORES')
	{		
		require_once("sigesp_copia_rpc.php");
		$isno_copia_rpc = new sigesp_copia_rpc();	
		if ($ls_operacion=="COPIA")
		{
			$isno_copia_rpc->ue_copiar_rpc_basico();
			?>			
				<script language='JavaScript'> 
					f=document.form1;
					f.action='sigesp_apr_basicos.php'; 
					f.operacion.value="MOSTRAR";
					f.submit(); 
				</script>
			<?php
		}
		if ($ls_operacion=="LIMPIA")
		{	
			$isno_copia_rpc->ue_limpiar_rpc_basico();				
		}		
		unset($isno_copia_rpc);
	}
	if ($ls_modulo=='COMPRAS')			
	{
		require_once("sigesp_copia_soc.php");
		$isno_copia_soc = new sigesp_copia_soc();	
		if ($ls_operacion=="COPIA")
		{
			$isno_copia_soc->ue_copiar_soc_basico();
			?>
				<script language='JavaScript'> 
					f=document.form1;
					f.action='sigesp_apr_basicos.php'; 
					f.operacion.value="MOSTRAR";
					f.submit(); 
				</script>
			<?php			
		}
		if ($ls_operacion=="LIMPIA")
		{	
			$isno_copia_soc->ue_limpiar_soc_basico();				
		}		
		unset($isno_copia_soc);
	}
	if ($ls_modulo=='INVENTARIO')			
	{
		require_once("sigesp_copia_siv.php");
		$isno_copia_siv = new sigesp_copia_siv();	
		if ($ls_operacion=="COPIA")
		{
			$isno_copia_siv->ue_copiar_siv_basico();
			?>
				<script language='JavaScript'> 
					f=document.form1;
					f.action='sigesp_apr_basicos.php'; 
					f.operacion.value="MOSTRAR";
					f.submit(); 
				</script>
			<?php			
		}
		if ($ls_operacion=="LIMPIA")
		{	
			$isno_copia_siv->ue_limpiar_siv_basico();				
		}		
		unset($isno_copia_siv);
	}
	if ($ls_modulo=='BANCO')			
	{
		require_once("sigesp_copia_scb.php");
		$isno_copia_banco = new sigesp_copia_scb();
		if ($ls_operacion=="COPIA")
		{
			$isno_copia_banco->ue_copiar_banco_basico();
			?>
				<script language='JavaScript'> 
					f=document.form1;
					f.action='sigesp_apr_basicos.php'; 
					f.operacion.value="MOSTRAR";
					f.submit(); 
				</script>
			<?php			
		}
		if ($ls_operacion=="LIMPIA")
		{	
			$isno_copia_banco->ue_limpiar_banco_basico();				
		}		
		unset($isno_copia_banco);
	}
	if ($ls_modulo=='NOMINA')		
	{	
		require_once("sigesp_copia_sno.php");
		$isno_copia_nomina = new sigesp_copia_sno();	
		if ($ls_operacion=="COPIA")
		{
			$isno_copia_nomina->ue_copiar_nomina_basico();
			?>			
				<script language='JavaScript'> 
					f=document.form1;
					f.action='sigesp_apr_basicos.php'; 
					f.operacion.value="MOSTRAR";
					f.submit(); 
				</script>
			<?php
		}
		if ($ls_operacion=="LIMPIA")
		{
			$isno_copia_nomina->ue_limpiar_nomina_basico();				
			$isno_copia_nomina->uf_select_nomina(&$li_totrows,&$lo_object);
		}
		if($ls_operacion=="")
		{
			$isno_copia_nomina->uf_select_nomina(&$li_totrows,&$lo_object);
		}
		unset($isno_copia_nomina);
	}
	if ($ls_modulo=='VIATICOS')		
	{	
		require_once("sigesp_copia_scv.php");
		$isno_copia_scv = new sigesp_copia_scv();	
		if ($ls_operacion=="COPIA")
		{
			$isno_copia_scv->ue_copiar_scv_basico();
			?>			
				<script language='JavaScript'> 
					f=document.form1;
					f.action='sigesp_apr_basicos.php'; 
					f.operacion.value="MOSTRAR";
					f.submit(); 
				</script>
			<?php
		}
		if ($ls_operacion=="LIMPIA")
		{
			$isno_copia_scv->ue_limpiar_scv_basico();				
		}
		unset($isno_copia_scv);
	}
	if ($ls_modulo=='ACTIVOS')			
	{
		require_once("sigesp_copia_saf.php");
		$isno_copia_saf = new sigesp_copia_saf();		
		if ($ls_operacion=="COPIA")
		{
			$isno_copia_saf->ue_copiar_saf_basico();
			?>			
				<script language='JavaScript'> 
					f=document.form1;
					f.action='sigesp_apr_basicos.php'; 
					f.operacion.value="MOSTRAR";
					f.submit();
				</script>
			<?php
		}
		if ($ls_operacion=="LIMPIA")
		{	
			$isno_copia_saf->ue_limpiar_saf_basico();				
		}		
		unset($isno_copia_saf);
	}
	?>  
<div id="seleccionar" style="visibility:visible">	
  <table width="570" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
        <tr>
          <th height="22" colspan="5" class="titulo-celdanew"><div align="left">Apertura Modulo:  <?php echo $_REQUEST['modulo'] ?></div> </th>
        </tr>
    </table>
  </div>
  <table width="570" border="0" class="formato-blanco" align="center">
    <tr>
      <th scope="col">&nbsp;</th>
    </tr>
    <tr>
      <th width="338" scope="col"><div align="center">
      	<input name="chk_Limpiar" type="checkbox" class="sin-borde" id="limpiar" onMouseOver="xstooltip_show('tooltip_limpiar', 'limpiar', 289, 49);" onMouseOut="xstooltip_hide('tooltip_limpiar');" >
      	Solo Limpiar </div></th>
    </tr>
  <?php
if ($ls_modulo=='CONTABILIDAD')
   { ?>
	  <tr>
		<td height="20" colspan="2" class="titulo-celdanew">Contabilidad Presupuestaria de Gasto</td>
	  </tr>
	  <tr>
		<td height="13" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
		<td><div align="right"><strong>Transferir Cuentas de Contabilidad General</strong></div></td>
		<td width="220"><div align="left"><input name="chkscgtransferir" type="checkbox" class="sin-borde" id="chkscgtransferir" value="1" checked="true" onChange="javascript: ue_desmarcar();" ></div></td>
	  </tr>
	  <tr>
		<td><div align="right"><strong>Transferir Estructura Presupuestaria y Cuentas de Gasto</strong></div></td>
		<td width="220"><div align="left"><input name="chkspgtransferir" type="checkbox" class="sin-borde" id="chkspgtransferir" value="1" onChange="javascript: ue_validascg();"></div></td>
	  </tr>
	  <tr>
	    <td><div align="right"><strong>Transferir Cuentas de Ingreso </strong></div></td>
		<td width="220"><div align="left"><input name="chkspitransferir" type="checkbox" class="sin-borde" id="chkspitransferir" value="1"onChange="javascript: ue_validascg();" ></div></td>
      </tr>
<?php
   }
?> 
  </table>
    <p></p>
	  <div align="center">
		  <?php
		if ($ls_modulo=='NOMINA')
		   { ?>
		<table width="327" height="113" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="20" colspan="4" class="titulo-celdanew">Periodo</td>
          </tr>
          <tr>
            <td height="13" colspan="4">&nbsp;</td>
          </tr>
          <tr>
            <td width="148" height="29"><div align="right"><strong>Fecha de Inicio </strong></div></td>
            <td colspan="2"><div align="left">
              <input name="txtfechainicio" type="text" id="txtfechainicio" size="15" datepicker="true" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);">
            </div></td>
            <td width="25"&>&nbsp;</td>
          </tr>
          <tr>
            <td height="13">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="23"><div align="right"><strong>Fecha de Inicio Semanal</strong></div></td>
            <td colspan="2"><div align="left">
              <input name="txtfecinisem" type="text" id="txtfecinisem" size="15" datepicker="true" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);">
            </div></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="13">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
		<?php
					require_once("../shared/class_folder/grid_param.php");
					$io_grid=new grid_param();
					$ls_titletable="Períodos";
					$li_widthtable=600;
					$ls_nametable="grid";
					$lo_title[1]="Empresa";
					$lo_title[2]="Código Actual";
					$lo_title[3]="Descripción";
					$lo_title[4]="Código Nuevo";
					$lo_title[5]="Pasar";
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);

		?> 
          <tr>
            <td height="13"><input name="totrownomina" type="hidden" id="totrownomina" value="<?php print $li_totrows; ?>">  </td>
            <td width="14">&nbsp;</td>
            <td width="138">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
		<?php
		   }
		?> 
    </div>
  <p align="center">
    <input name"btn_proceder" id="comenzar" type="button" value="Proceder" onClick="javascript:ue_procesar();"  onMouseOver="xstooltip_show('tooltip_proceder', 'comenzar', 289, 49);" onMouseOut="xstooltip_hide('tooltip_proceder');" >
    &nbsp;</p>
</div>

	<div id="progress" style="visibility:hidden">
	
	  <div align="center">
	    <p><img src="iconos/indicator.gif" width="32" height="32"> </p>
	    <p>Por favor espere... </p>
	  </div>
	</div>
	<div id="detalles" >
	</div>
  <p>&nbsp;</p>
  <input name="modulo" type="hidden" id="modulo" value="<?php echo $_REQUEST['modulo']; ?>">  
  <input name="procesadosss" type="hidden" id="procesadosss" value="<?php echo $ls_disablesss; ?>">  
  <input name="procesadorpc" type="hidden" id="procesadorpc" value="<?php echo $ls_disablerpc; ?>">  
  <input name="procesadoscg" type="hidden" id="procesadoscg" value="<?php echo $ls_disablescg; ?>">  
  <input name="procesadosiv" type="hidden" id="procesadosiv" value="<?php echo $ls_disablesiv; ?>">  
  <input name="procesadosep" type="hidden" id="procesadosep" value="<?php echo $ls_disablesep; ?>">  
  <input name="procesadosoc" type="hidden" id="procesadosoc" value="<?php echo $ls_disablesoc; ?>">  
  <input name="procesadocxp" type="hidden" id="procesadocxp" value="<?php echo $ls_disablecxp; ?>">  
  <input name="procesadoscb" type="hidden" id="procesadoscb" value="<?php echo $ls_disablescb; ?>">  
  <input name="procesadosaf" type="hidden" id="procesadosaf" value="<?php echo $ls_disablesaf; ?>">  
  <input name="procesadoscv" type="hidden" id="procesadoscv" value="<?php echo $ls_disablescv; ?>">  
  <input name="procesadosno" type="hidden" id="procesadosno" value="<?php echo $ls_disablesno; ?>">  
</form>

<div id="tooltip_limpiar" class="xstooltip">
	Marcando esta caja le indicará al sistema que <b>borre el contenido de las tablas</b> del módulo seleccionado<br/>
	Si ocurre un error durante el mismo, serán revertidos los cambios<br/>
	Si el proceso culmina sin errores los cambios <b>no podrán</b> ser revertidos<br/>
</div>

<div id="tooltip_proceder" class="xstooltip">
	Haga click en este botón para comenzar el proceso de copiado<br/>
	Si ocurre un error durante el mismo, serán revertidos los cambios<br/>
	Si el proceso culmina sin errores los cambios <b>no podrán</b> ser revertidos<br/>
</div> 

</body>

<script language="javascript">
f=document.form1;
var patron = new Array(2,2,4);
function ue_cerrar()
{
	window.open("sigespwindow_blank.php","Blank","_self");
}

function ue_desmarcar()
{
	if(document.form1.chkscgtransferir.checked==false)
	{
		document.form1.chkspgtransferir.checked=false;
		document.form1.chkspitransferir.checked=false;
	}
}


function ue_validascg()
{
	if(document.form1.chkscgtransferir.checked==false)
	{
		document.form1.chkspgtransferir.checked=false;
		document.form1.chkspitransferir.checked=false;
		alert("Para porder transferir Presupuesto de Gasto y Presupuesto de Ingreso debe transferir Contabilidad General.");
	}
}

function ue_procesar()
{
	var searchValue	= document.getElementById('modulo').value;
	var limpiar	= document.form1.chk_Limpiar.value;
	
	if (searchValue == "NOMINA")
	{
		f.action="sigesp_convierte.php";
		f.operacion.value ="COPIA";
		if (document.form1.chk_Limpiar.checked)
		{
			f.operacion.value ="LIMPIA";
		    mostrar('progress');
		    f.submit();		
		}
		else
		{
				ls_fecini    = f.txtfechainicio.value;
				ls_fecinisem = f.txtfecinisem.value;
				if (ls_fecini!="" && ls_fecinisem!="")
				   {
					 f.operacion.value = "COPIA";
					 mostrar('progress');
					 f.submit();		
				   }
				else
				   {
					 alert("Deben establecerse ambas fechas para realizar la transferencia !!!");
				   }
		}
	}		
	else if (searchValue == "CONTABILIDAD")
	{		
		f.action="sigesp_convierte.php";
		f.operacion.value ="COPIA";
		if (document.form1.chk_Limpiar.checked)
		{
			f.operacion.value ="LIMPIA";
		}
		else
		{
			f.operacion.value ="COPIA";
		}
		mostrar('progress');
		f.submit();
	}	
	else if (searchValue == "PROVEEDORES")
	{		
		f.action="sigesp_convierte.php";
		f.operacion.value ="COPIA";
		if (document.form1.chk_Limpiar.checked)
		{
			f.operacion.value ="LIMPIA";
		}
		else
		{
			f.operacion.value ="COPIA";
		}
		mostrar('progress');
		f.submit();
	}	
	else if (searchValue == "CUENTAS POR PAGAR")
	{		
		f.action="sigesp_convierte.php";
		f.operacion.value ="COPIA";
		if (document.form1.chk_Limpiar.checked)
		{
			f.operacion.value ="LIMPIA";
		}
		else
		{
			f.operacion.value ="COPIA";
		}
		mostrar('progress');
		f.submit();
	}
	else if (searchValue == "ACTIVOS")
	{		
		f.action="sigesp_convierte.php";
		f.operacion.value ="COPIA";
		if (document.form1.chk_Limpiar.checked)
		{
			f.operacion.value ="LIMPIA";
		}
		else
		{
			f.operacion.value ="COPIA";
		}
		mostrar('progress');
		f.submit();
	}
	else if (searchValue == "BANCO")
	{		
		f.action="sigesp_convierte.php";
		f.operacion.value ="COPIA";
		if (document.form1.chk_Limpiar.checked)
		{
			f.operacion.value ="LIMPIA";
		}
		else
		{
			f.operacion.value ="COPIA";
		}
		mostrar('progress');
		f.submit();
	}
	else if (searchValue == "INVENTARIO")
	{		
		f.action="sigesp_convierte.php";
		f.operacion.value ="COPIA";
		if (document.form1.chk_Limpiar.checked)
		{
			f.operacion.value ="LIMPIA";
		}
		else
		{
			f.operacion.value ="COPIA";
		}
		mostrar('progress');
		f.submit();
	}
	else if (searchValue == "SEGURIDAD")
	{		
		f.action="sigesp_convierte.php";
		f.operacion.value ="COPIA";
		if (document.form1.chk_Limpiar.checked)
		{
			f.operacion.value ="LIMPIA";
		}
		else
		{
			f.operacion.value ="COPIA";
		}
		mostrar('progress');
		f.submit();
	}
		else if (searchValue == "SOLICITUDES DE EJECUCION PRESUPUESTARIA")	
	{		
		f.action="sigesp_convierte.php";
		f.operacion.value ="COPIA";
		if (document.form1.chk_Limpiar.checked)
		{
			f.operacion.value ="LIMPIA";
		}
		else
		{
			f.operacion.value ="COPIA";
		}
		mostrar('progress');
		f.submit();
	}
	else if (searchValue == "COMPRAS")	
	{		
		f.action="sigesp_convierte.php";
		f.operacion.value ="COPIA";
		if (document.form1.chk_Limpiar.checked)
		{
			f.operacion.value ="LIMPIA";
		}
		else
		{
			f.operacion.value ="COPIA";
		}
		mostrar('progress');
		f.submit();
	}
	else if (searchValue == "VIATICOS")	
	{		
		f.action="sigesp_convierte.php";
		f.operacion.value ="COPIA";
		if (document.form1.chk_Limpiar.checked)
		{
			f.operacion.value ="LIMPIA";
		}
		else
		{
			f.operacion.value ="COPIA";
		}
		mostrar('progress');
		f.submit();
	}
}

function ue_cerrar()
{	
	f.action='sigesp_apr_basicos.php'; 
	f.operacion.value="MOSTRAR";
	f.submit(); 
}
//--------------------------------------------------------
//	Función que valida que solo se incluyan números en los textos
//--------------------------------------------------------
function ue_validarnumero(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}
//--------------------------------------------------------
//	Función que rellena un campo con ceros a la izquierda
//--------------------------------------------------------
function ue_rellenarcampo(valor,maxlon)
{
	var total;
	var auxiliar;
	var longitud;
	var index;
	
	total=0;
    auxiliar=valor.value;
	longitud=valor.value.length;
	total=maxlon-longitud;
	if (total < maxlon)
	{
		for (index=0;index<total;index++)
		{
		   auxiliar="0"+auxiliar;      
		}
		valor.value = auxiliar;
	}
}

function ue_formatofecha(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++)
		{
			val2 += val[r]	
		}
		if(nums)
		{
			for(z=0;z<val2.length;z++)
			{
				if(isNaN(val2.charAt(z)))
				{
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++)
		{
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++)
		{
			if(q ==0)
			{
				val = val3[q]
			}
			else
			{
				if(val3[q] != "")
				{
					val += sep + val3[q]
				}
			}
		}
		d.value = val
		d.valant = val
	}
}
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>