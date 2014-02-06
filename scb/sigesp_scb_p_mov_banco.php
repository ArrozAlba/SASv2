<?php
	session_start();
	$dat=$_SESSION["la_empresa"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Movimiento de Banco</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #CCCCCC;
	margin-left: 0px;
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
.Estilo5 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.Estilo6 {
	color: #006699;
	font-size: 12px;
}
.Estilo8 {font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; }
.Estilo10 {font-size: 10px}
.Estilo11 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo13 {font-size: 12px}
.Estilo14 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
-->
</style>
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>

</head>

<body>

<span class="toolbar"><a name="00"></a></span>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<?
	include("../shared/class_folder/class_mensajes.php");
	$msg=new class_mensajes();	
	include("../shared/class_folder/class_funciones.php");
	$fun=new class_funciones();	
	$lb_guardar=true;
    require_once("../shared/class_folder/sigesp_include.php");
    $sig_inc=new sigesp_include();
    $con=$sig_inc->uf_conectar();
	include("../shared/class_folder/ddlb_operaciones_spg.php");
 	$obj_spg=new ddlb_operaciones_spg($con);
	include("../shared/class_folder/ddlb_operaciones_spi.php");
 	$obj_spi=new ddlb_operaciones_spi($con);
	include("../shared/class_folder/ddlb_conceptos.php");
 	$obj_con=new ddlb_conceptos($con);
	include("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["CodEmp"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
		$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
		$ls_logusr="";
	}
	$ls_sistema="SCB";
	$ls_ventanas="sigesp_scb_p_mov_banco.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventanas;

	if(array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$lb_permisos=true;
			print("Bienvenido usuario SIGESP");
		}
		else
		{
			$lb_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$lb_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas);		
	}
	//Inclusión de la clase de seguridad.
	
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////


	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_mov_operacion=$_POST["cmboperacion"];
		if($ls_operacion=="CAMBIO_OPERA")
		{
			$ls_seleccionado="";	
		}
		else
		{
			if(($ls_mov_operacion=="CH")||($ls_mov_operacion=="ND"))
			{			
				$ls_seleccionado=$_POST["ddlb_spg"];
			}
			elseif(($ls_mov_operacion=="DP")||($ls_mov_operacion=="NC"))
			{
				$ls_seleccionado=$_POST["ddlb_spi"];
			}
		}
		$ls_docmov=$_POST["txtdocumento"];
		$ld_fecha=$_POST["txtfecha"];
		$ls_codban=$_POST["txtcodban"];
		$ls_denban=$_POST["txtdenban"];
		$ls_cuenta_banco=$_POST["txtcuenta"];
		$ls_dencuenta_banco=$_POST["txtdenominacion"];
		$ls_provbene=$_POST["txtprovbene"];
		$ls_desproben=$_POST["txtdesproben"];
		$ls_tipo=$_POST["rb_provbene"];
		$lastspg = $_POST["lastspg"];
		$lastscg = $_POST["lastscg"];
		$lastret = $_POST["lastret"];
		$lastspi = $_POST["lastspi"];
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$ldec_diferencia=0;
		$ldec_monspg=0;
		$ldec_monspi=0;
		$ldec_montomov=$_POST["txtmonto"];
		$ldec_monobjret=$_POST["txtmonobjret"];
		$ldec_montoret=$_POST["txtretenido"];

	}
	else
	{
		$ls_operacion= "NUEVO" ;
		$ls_mov_operacion="NC";
	    $ls_seleccionado="";
		$ls_docmov="";
		$ls_codban="";
		$ls_denban="";
		$ls_cuenta_banco="";
		$ls_dencuenta_banco="";	
		$ls_provbene="----------";
		$ls_desproben="Ninguno";
		$ls_tipo="-";
		$lastspg = 0;
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		$ld_fecha=$fun->uf_cerosizquierda($ls_dia,2)."/".$fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
		$lastscg=0;
		$lastret=0;
		$lastspi=0;
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$ldec_diferencia=0;
		$ldec_monspg=0;
		$ldec_monspi=0;
		$ldec_montomov="";
		$ldec_monobjret="";
		$ldec_montoret="";
		
	}
	

	if($ls_operacion=="RECARGAR")
	{
		$totalpre= $_POST["totpre"];
		$totalspi= $_POST["totspi"];
		$totalcon= $_POST["totcon"];	
		$totalret= $_POST["totret"];	
	}
	else
	{
		$totalpre= 5;
		$totalspi= 5;
		$totalcon= 5;	
		$totalret= 5;
	}
	
	$titleSpi[1]="Cuenta";   $titleSpi[2]="Descripción";     $titleSpi[3]="Procede";     $titleSpi[4]="Documento";   	$titleSpi[5]="Operación";   $titleSpi[6]="Monto";   $titleSpi[7]="Edición";
	$title2[1]="Cuenta";   $title2[2]="Documento";     $title2[3]="Descripción";     $title2[4]="Procede";   	$title2[5]="Debe/Haber";   $title2[6]="Monto";   $title2[7]="Edición";
	$title[1]="Cuenta";   $title[2]="Programatico";     $title[3]="Documento";    $title[4]="Descripción";   $title[5]="Procede"; $title[6]="Operación";     $title[7]="Monto";  $title[8]="Edición";
	$titleRet[1]="Deducción";   $titleRet[2]="Cuenta";     $titleRet[3]="Descripción";    $titleRet[4]="Documento";   $titleRet[5]="Procede"; $titleRet[6]="Objeto a Retencion";     $titleRet[7]="Retenido";  $titleRet[8]="Edición";
	
	$gridSpi="grid_Spi";
	$grid2="gridscg";	
    $grid1="grid_SPG";	
    $gridRet="grid_Ret";
	
	$li_temp_scg=0;
	$li_temp_spg=0;
	$li_temp_ret=0;
	$li_temp_spi=0;
	
	for($i=1;$i<=$totalcon;$i++)
	{
		if($ls_operacion=="DELETESCG")
		{
			$li_row_delete=$_POST["delete_scg"];
		}
		else
		{
			$li_row_delete=0;
		}
		if($i!=$li_row_delete)
		{		
			$li_temp_scg=$li_temp_scg+1;
			if(array_key_exists("txtcontable".$i,$_POST))
			{
				$ls_cuenta     = $_POST["txtcontable".$i];
				$ls_documento  = $_POST["txtdocscg".$i];
				$ls_descripcion= $_POST["txtdesdoc".$i];
				$ls_procede    = $_POST["txtprocdoc".$i];
				$ls_debhab     = $_POST["txtdebhab".$i];
				$ldec_monto    = $_POST["txtmontocont".$i];				
			}
			else
			{
				$ls_cuenta="";
				$ls_documento="";
				$ls_descripcion="";
				$ls_procede="";
				$ls_debhab="";
				$ldec_monto="";				
			}
			$objectScg[$li_temp_scg][1] = "<input type=text name=txtcontable".$li_temp_scg." id=txtcontable".$li_temp_scg."  value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";		
			$objectScg[$li_temp_scg][2] = "<input type=text name=txtdocscg".$li_temp_scg."    value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$objectScg[$li_temp_scg][3] = "<input type=text name=txtdesdoc".$li_temp_scg."    value='".$ls_descripcion."' class=sin-borde readonly style=text-align:left size=35 maxlength=254>";
			$objectScg[$li_temp_scg][4] = "<input type=text name=txtprocdoc".$li_temp_scg."   value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
			$objectScg[$li_temp_scg][5] = "<input type=text name=txtdebhab".$li_temp_scg."    value='".$ls_debhab."' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
			$objectScg[$li_temp_scg][6] = "<input type=text name=txtmontocont".$li_temp_scg." value='".$ldec_monto."' class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
			$objectScg[$li_temp_scg][7] = "<a href=javascript:uf_delete_Scg('".$li_temp_scg."');><img src=imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
			$ldec_monto=str_replace('.','',$ldec_monto);
			$ldec_monto=str_replace(',','.',$ldec_monto);
			if($ls_debhab=='D')
			{
				$ldec_mondeb=$ldec_mondeb+$ldec_monto;
				
			}
			else
			{
				$ldec_monhab=$ldec_monhab+$ldec_monto;
			}
		}
		else
		{	
			$li_row_delete=0;
			$lastscg = $lastscg - 1;
		}
		   if((array_key_exists("txtcontable".$totalcon,$_POST))&&($ls_operacion!="DELETESCG"))
			{
				$ls_cuenta=$_POST["txtcontable".$totalcon];
				$ls_documento=$_POST["txtdocscg".$totalcon];
				$ls_descripcion=$_POST["txtdesdoc".$totalcon];
				$ls_procede=$_POST["txtprocdoc".$totalcon];
				$ls_debhab=$_POST["txtdebhab".$totalcon];
				$ldec_monto=$_POST["txtmontocont".$totalcon];				
			}
			else
			{
				$ls_cuenta="";
				$ls_documento="";
				$ls_descripcion="";
				$ls_procede="";
				$ls_debhab="";
				$ldec_monto="";				
			}
			
			$objectScg[$totalcon][1] = "<input type=text name=txtcontable".$totalcon." id=txtcontable".$totalcon."  value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";		
			$objectScg[$totalcon][2] = "<input type=text name=txtdocscg".$totalcon."    value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$objectScg[$totalcon][3] = "<input type=text name=txtdesdoc".$totalcon."    value='".$ls_descripcion."' class=sin-borde readonly style=text-align:left size=35 maxlength=254>";
			$objectScg[$totalcon][4] = "<input type=text name=txtprocdoc".$totalcon."   value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
			$objectScg[$totalcon][5] = "<input type=text name=txtdebhab".$totalcon."    value='".$ls_debhab."' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
			$objectScg[$totalcon][6] = "<input type=text name=txtmontocont".$totalcon." value='".$ldec_monto."' class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
			$objectScg[$totalcon][7] = "<a href=javascript:uf_delete_Scg('".$totalcon."');><img src=imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
			$ldec_diferencia=$ldec_mondeb-$ldec_monhab;
	}
	
	for($li_i=1;$li_i<=$totalpre;$li_i++)
	{
		if($ls_operacion=="DELETESPG")
		{
			$li_row_delete=$_POST["delete_spg"];
		}
		else
		{
			$li_row_delete=0;
		}
		
		if($li_i!=$li_row_delete)
		{		
			$li_temp_spg=$li_temp_spg+1;
			if(array_key_exists("txtcuenta".$li_i,$_POST))
			{
				$ls_cuenta=$_POST["txtcuenta".$li_i];
				$ls_programatica=$_POST["txtprogramatico".$li_i];
				$ls_documento=$_POST["txtdocumento".$li_i];
				$ls_descripcion=$_POST["txtdescripcion".$li_i];
				$ls_procede=$_POST["txtprocede".$li_i];
				$ls_operacion_spg=$_POST["txtoperacion".$li_i];								
				$ldec_monto=$_POST["txtmonto".$li_i];		
			}
			else
			{
				$ls_cuenta="";
				$ls_programatica="";
				$ls_documento="";
				$ls_descripcion="";
				$ls_procede="";
				$ls_operacion_spg="";								
				$ldec_monto="";
			}
			$objectSpg[$li_temp_spg][1]  = "<input type=text name=txtcuenta".$li_temp_spg."       id=txtcuenta".$li_temp_spg."       value='".$ls_cuenta."'       class=sin-borde readonly style=text-align:center size=10 maxlength=10 >";
			$objectSpg[$li_temp_spg][2]  = "<input type=text name=txtprogramatico".$li_temp_spg." id=txtprogramatico".$li_temp_spg." value='".$ls_programatica."' class=sin-borde readonly style=text-align:center size=32 maxlength=29 >"; 
			$objectSpg[$li_temp_spg][3]  = "<input type=text name=txtdocumento".$li_temp_spg."    id=txtdocumento".$li_temp_spg."    value='".$ls_documento."'    class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
			$objectSpg[$li_temp_spg][4]  = "<input type=text name=txtdescripcion".$li_temp_spg."  id=txtdescripcion".$li_temp_spg."  value='".$ls_descripcion."'  class=sin-borde readonly style=text-align:left>";
			$objectSpg[$li_temp_spg][5]  = "<input type=text name=txtprocede".$li_temp_spg."      id=txtprocede".$li_temp_spg."      value='".$ls_procede."'      class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$objectSpg[$li_temp_spg][6]  = "<input type=text name=txtoperacion".$li_temp_spg."    id=txtoperacion".$li_temp_spg."    value='".$ls_operacion_spg."'    class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
			$objectSpg[$li_temp_spg][7]  = "<input type=text name=txtmonto".$li_temp_spg."        id=txtmonto".$li_temp_spg."        value='".$ldec_monto."'      class=sin-borde readonly style=text-align:right>";		
			$objectSpg[$li_temp_spg][8]  = "<a href=javascript:uf_delete_Spg('".$li_temp_spg."');><img src=imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Gasto' width=15 height=15 border=0></a>";	
			$ldec_monto=str_replace('.','',$ldec_monto);
			$ldec_monto=str_replace(',','.',$ldec_monto);
			$ldec_monspg=$ldec_monspg+$ldec_monto;
		}
		else
		{
			$li_row_delete=0;
			$lastspg = $lastspg - 1;
		}
		if((array_key_exists("txtcuenta".$totalpre,$_POST))&&($ls_operacion!="DELETESPG"))
			{
				$ls_cuenta=$_POST["txtcuenta".$totalpre];
				$ls_programatica=$_POST["txtprogramatico".$totalpre];
				$ls_documento=$_POST["txtdocumento".$totalpre];
				$ls_descripcion=$_POST["txtdescripcion".$totalpre];
				$ls_procede=$_POST["txtprocede".$totalpre];
				$ls_operacion_spg=$_POST["txtoperacion".$totalpre];								
				$ldec_monto=$_POST["txtmonto".$totalpre];		
			}
			else
			{
				$ls_cuenta="";
				$ls_programatica="";
				$ls_documento="";
				$ls_descripcion="";
				$ls_procede="";
				$ls_operacion_spg="";								
				$ldec_monto="";
			}
			$objectSpg[$totalpre][1]  = "<input type=text name=txtcuenta".$totalpre."       id=txtcuenta".$totalpre."       value='".$ls_cuenta."'       class=sin-borde readonly style=text-align:center size=10 maxlength=10 >";
			$objectSpg[$totalpre][2]  = "<input type=text name=txtprogramatico".$totalpre." id=txtprogramatico".$totalpre." value='".$ls_programatica."' class=sin-borde readonly style=text-align:center size=32 maxlength=29 >"; 
			$objectSpg[$totalpre][3]  = "<input type=text name=txtdocumento".$totalpre."    id=txtdocumento".$totalpre."    value='".$ls_documento."'    class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
			$objectSpg[$totalpre][4]  = "<input type=text name=txtdescripcion".$totalpre."  id=txtdescripcion".$totalpre."  value='".$ls_descripcion."'  class=sin-borde readonly style=text-align:left>";
			$objectSpg[$totalpre][5]  = "<input type=text name=txtprocede".$totalpre."      id=txtprocede".$totalpre."      value='".$ls_procede."'      class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$objectSpg[$totalpre][6]  = "<input type=text name=txtoperacion".$totalpre."    id=txtoperacion".$totalpre."    value='".$ls_operacion_spg."'    class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
			$objectSpg[$totalpre][7]  = "<input type=text name=txtmonto".$totalpre."        id=txtmonto".$totalpre."        value='".$ldec_monto."'      class=sin-borde readonly style=text-align:right>";		
			$objectSpg[$totalpre][8]  = "<a href=javascript:uf_delete_Spg('".$totalpre."');><img src=imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Gasto' width=15 height=15 border=0></a>";	

	}
	
	for($li_i=1;$li_i<=$totalret;$li_i++)
	{
		
		if($ls_operacion=="DELETERET")
		{
			$li_row_delete=$_POST["delete_ret"];
		}
		else
		{
			$li_row_delete=0;
		}

		if($li_i!=$li_row_delete)
		{		
			$li_temp_ret=$li_temp_ret+1;
			if(array_key_exists("txtcuentaret".$li_i,$_POST))
			{
				$ls_cuenta=$_POST["txtcuentaret".$li_i];
				$ls_deduccion=$_POST["txtdeduccion".$li_i];
				$ls_documento=$_POST["txtdocret".$li_i];
				$ls_descripcion=$_POST["txtdescret".$li_i];
				$ls_procede=$_POST["txtprocederet".$li_i];
				$ldec_monto=$_POST["txtmontoret".$li_i];								
				$ldec_monto_objret=$_POST["txtmontoobjret".$li_i];		
			}
			else
			{
				$ls_cuenta="";
				$ls_deduccion="";
				$ls_documento="";
				$ls_descripcion="";
				$ls_procede="";
				$ldec_monto="";
				$ldec_monto_objret="";
			}
			$objectRet[$li_temp_ret][1]  = "<input type=text name=txtdeduccion".$li_temp_ret."   value='".$ls_deduccion."' class=sin-borde readonly style=text-align:center  size=5 maxlength=5>";
			$objectRet[$li_temp_ret][2]  = "<input type=text name=txtcuentaret".$li_temp_ret."   value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
			$objectRet[$li_temp_ret][3]  = "<input type=text name=txtdescret".$li_temp_ret."     value='".$ls_descripcion."' class=sin-borde readonly style=text-align:left size=32 maxlength=45>";
			$objectRet[$li_temp_ret][4]  = "<input type=text name=txtdocret".$li_temp_ret."      value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$objectRet[$li_temp_ret][5]  = "<input type=text name=txtprocederet".$li_temp_ret."  value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=6 maxlength=6>";
			$objectRet[$li_temp_ret][6]  = "<input type=text name=txtmontoobjret".$li_temp_ret." value='".$ldec_monto_objret."' class=sin-borde readonly style=text-align:right>";		
			$objectRet[$li_temp_ret][7]  = "<input type=text name=txtmontoret".$li_temp_ret."    value='".$ldec_monto."' class=sin-borde readonly style=text-align:right >";
			$objectRet[$li_temp_ret][8]  = "<a href=javascript:uf_delete_Ret('".$li_temp_ret."');><img src=imagebank/tools15/eliminar.gif alt='Eliminar Retención' width=15 height=15 border=0></a>";	
		}
		else
		{
			$li_row_delete=0;
			$lastret = $lastret - 1;
		}
		if(array_key_exists("txtcuentaret".$totalret,$_POST))
		{
			$ls_cuenta=$_POST["txtcuentaret".$totalret];
			$ls_deduccion=$_POST["txtdeduccion".$totalret];
			$ls_documento=$_POST["txtdocret".$totalret];
			$ls_descripcion=$_POST["txtdescret".$totalret];
			$ls_procede=$_POST["txtprocederet".$totalret];
			$ldec_monto=$_POST["txtmontoret".$totalret];								
			$ldec_monto_objret=$_POST["txtmontoobjret".$totalret];		
		}
		else
		{
			$ls_cuenta="";
			$ls_deduccion="";
			$ls_documento="";
			$ls_descripcion="";
			$ls_procede="";
			$ldec_monto="";
			$ldec_monto_objret="";
		}
		$objectRet[$totalret][1]  = "<input type=text name=txtdeduccion".$totalret."   value='".$ls_deduccion."' class=sin-borde readonly style=text-align:center  size=5 maxlength=5>";
		$objectRet[$totalret][2]  = "<input type=text name=txtcuentaret".$totalret."   value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
		$objectRet[$totalret][3]  = "<input type=text name=txtdescret".$totalret."     value='".$ls_descripcion."' class=sin-borde readonly style=text-align:left size=32 maxlength=45>";
		$objectRet[$totalret][4]  = "<input type=text name=txtdocret".$totalret."      value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$objectRet[$totalret][5]  = "<input type=text name=txtprocederet".$totalret."  value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=6 maxlength=6>";
		$objectRet[$totalret][6]  = "<input type=text name=txtmontoobjret".$totalret." value='".$ldec_monto_objret."' class=sin-borde readonly style=text-align:right>";		
		$objectRet[$totalret][7]  = "<input type=text name=txtmontoret".$totalret."    value='".$ldec_monto."' class=sin-borde readonly style=text-align:right >";
		$objectRet[$totalret][8]  = "<a href=javascript:uf_delete_Ret('".$totalret."');><img src=imagebank/tools15/eliminar.gif alt='Eliminar Retención' width=15 height=15 border=0></a>";	
		
			
	}	
	for($i=1;$i<=$totalspi;$i++)
	{
		if(array_key_exists("txtcuentaspi".$i,$_POST))
		{
			$ls_cuenta=$_POST["txtcuentaspi".$i];
			$ls_descripcion=$_POST["txtdescspi".$i];
			$ls_procede=$_POST["txtprocspi".$i];
			$ls_documento=$_POST["txtdocspi".$i];
			$ls_operacion_spi=$_POST["txtopespi".$i];
			$ldec_monto=$_POST["txtmontospi".$i];									
		}
		else
		{
			$ls_cuenta="";
			$ls_descripcion="";
			$ls_procede="";
			$ls_documento="";
			$ls_operacion_spi="";
			$ldec_monto="";
		}
		$objectSpi[$i][1]  = "<input type=text name=txtcuentaspi".$i." value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=6 maxlength=5>";
		$objectSpi[$i][2]  = "<input type=text name=txtdescspi".$i."   value='".$ls_descripcion."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
		$objectSpi[$i][3]  = "<input type=text name=txtprocspi".$i."   value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=32 maxlength=45>";
		$objectSpi[$i][4]  = "<input type=text name=txtdocspi".$i."    value='".$ls_documento."' class=sin-borde readonly style=text-align:center>";
		$objectSpi[$i][5]  = "<input type=text name=txtopespi".$i."    value='".$ls_operacion_spi."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
		$objectSpi[$i][6]  = "<input type=text name=txtmontospi".$i."  value='".$ldec_monto."' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
		$objectSpi[$i][7]  = "<a href=javascript:uf_delete_Spi('".$i."');><img src=imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Ingreso' width=15 height=15 border=0></a>";	
	}	
	if($ls_operacion == "NUEVO")
	{
		$ls_operacion= "" ;
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		$ld_fecha=$fun->uf_cerosizquierda($ls_dia,2)."/".$fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
	}
	if($ls_operacion == "GUARDAR")
	{
		
			
	}
	if($ls_operacion == "ELIMINAR")
	{
		
	}
	if($ls_operacion == "CAMBIO_OPERA")
	{
					
		
	}
	if($ls_mov_operacion=='ND')
	{
		$lb_nd="selected";
		$lb_nc="";
		$lb_dp="";
		$lb_re="";
		$lb_ch="";
	}
	if($ls_mov_operacion=='NC')
	{
		$lb_nd="";
		$lb_nc="selected";
		$lb_dp="";
		$lb_re="";
		$lb_ch="";
	}
	if($ls_mov_operacion=='DP')
	{
		$lb_nd="";
		$lb_nc="";
		$lb_dp="selected";
		$lb_re="";
		$lb_ch="";
	}
	if($ls_mov_operacion=='RE')
	{
		$lb_nd="";
		$lb_nc="";
		$lb_dp="";
		$lb_re="selected";
		$lb_ch="";
	}
	if($ls_mov_operacion=='CH')
	{
		$lb_nd="";
		$lb_nc="";
		$lb_dp="";
		$lb_re="";
		$lb_ch="selected";
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
  <form name="form1" method="post" action="">
  <?php 
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
		if (($lb_permisos)||($ls_logusr=="PSEGIS"))
		{
			print("<input type=hidden name=permisos id=permisos value='$lb_permisos'>");			
		}
		else
		{			
			print("<script language=JavaScript>");
			print(" location.href='sigespwindow_blank.php'");
			print("</script>");
		}
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
  ?>
  <br>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td colspan="4">Movimientos de Banco </td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td width="110" height="22"><div align="right">Documento</div></td>
      <td width="202"><div align="left">
          <input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="rellenar_cad(this.value,15,'doc')" value="<?php print $ls_docmov;?>" size="24">
      </div></td>
      <td width="218"><div align="right">Fecha</div></td>
      <td width="248"><div align="left">
          <input name="txtfecha" type="text" id="txtfecha"  style="text-align:center" value="<?php print $ld_fecha;?>" size="24" maxlength="10"  onKeyPress="currencyDate(this);" readonly >
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Banco</div></td>
      <td colspan="3"><div align="left">
          <input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
          <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
          <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="51" class="sin-borde" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Cuenta</div></td>
      <td colspan="3"><div align="left">
          <input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_cuenta_banco; ?>" size="30" maxlength="25" readonly>
          <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_dencuenta_banco; ?>" size="50" maxlength="254" readonly>
          <input name="txttipocuenta" type="hidden" id="txttipocuenta">
          <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Cuenta Contable </div></td>
      <td><div align="left">
          <input name="txtcuenta_scg" type="text" id="txtcuenta_scg" style="text-align:center" size="24" readonly>
      </div></td>
      <td><div align="right">Disponible</div></td>
      <td><div align="left">
          <input name="txtdisponible" type="text" id="txtdisponible" style="text-align:right" size="24" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Operaci&oacute;n</div></td>
      <td><div align="left">
          <select name="cmboperacion" id="select" onChange="javascript:uf_verificar_operacion();" style="width:120px">
            <option value="ND" <?php print $lb_nd;?>>Nota de D&eacute;bito</option>
            <option value="NC" <?php print $lb_nc;?>>Nota Cr&eacute;dito</option>
            <option value="DP" <?php print $lb_dp;?>>Dep&oacute;sito</option>
            <option value="RE" <?php print $lb_re;?>>Retiro</option>
            <option value="CH" <?php print $lb_ch;?>>Cheque</option>
          </select>
      </div></td>
      <td><div align="right">Concepto</div></td>
      <td><div align="left">
          <?php $obj_con->uf_cargar_conceptos($ls_mov_operacion,$ls_seleccionado);	?>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right"><?php if($ls_mov_operacion!="RE")print "Afectación"; ?></div></td>
      <td colspan="3"><div align="left">
          <?php  if(($ls_mov_operacion=='ND')||($ls_mov_operacion=='CH'))
				{
					$obj_spg->uf_cargar_ddlb_spg(0,$ls_seleccionado,$ls_mov_operacion); 	
				}
				elseif(($ls_mov_operacion=='DP')||($ls_mov_operacion=='NC'))
				{
					$obj_spi->uf_cargar_ddlb_spi(0,$ls_seleccionado,$ls_mov_operacion); 
				}				
				?>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Concepto Movimiento </div></td>
      <td colspan="3"><div align="left">
          <input name="txtconcepto" type="text" id="txtconcepto" size="120">
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Tipo Destino</div></td>
      <td colspan="2">
	  	   <table width="249" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td width="353"><label>
                <input type="radio" name="rb_provbene" id="radio" value="P" class="sin-borde" style="width:10 ; height:10" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" <?php print $rb_p;?>>
Proveedor</label>
                <label>
                <input type="radio" name="rb_provbene" id="radio" value="B" class="sin-borde"   style="width:10 ; height:10" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" <?php print $rb_b;?>>
Beneficiario</label>
                <label>
                <input name="rb_provbene" id="radio" type="radio"  class="sin-borde" style="width:10 ; height:10" value="-" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" <?php print $rb_n;?>>
Ninguno</label>
                <input name="tipo" type="hidden" id="tipo"></td>
            </tr>
          </table>
	
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">
          <input name="txttitprovbene" type="text" class="sin-borde" id="txttitprovbene" style="text-align:right" size="15" readonly>
      </div></td>
      <td colspan="3"><div align="left">
          <input name="txtprovbene" type="text" id="txtprovbene" style="text-align:center" value="<?php print $ls_provbene?>" size="24" readonly>
          <a href="javascript:catprovbene()"><img id="bot_provbene" src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>
          <input name="txtdesproben" type="text" id="txtdesproben" size="42" maxlength="250" class="sin-borde" value="<?php print $ls_desproben;?>"  readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Monto</div></td>
      <td><div align="left">
          <input name="txtmonto" type="text" id="txtmonto" style="text-align:right" onBlur="javascript:uf_format(this);" value="<?php print $ldec_montomov;?>" size="24">
      </div></td>
      <td><div align="right">Monto Objeto a Retenci&oacute;n </div></td>
      <td><div align="left">
          <input name="txtmonobjret" type="text" id="txtmonobjret" style="text-align:right" onBlur="javascript:uf_format(this);" value="<?php print $ldec_monobjret;?>" size="24">
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Monto Retenido </div></td>
      <td><div align="left">
          <input name="txtretenido" type="text" id="txtretenido" style="text-align:right" value="<?php print $ldec_montoret;?>" size="24" readonly>
      </div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">
          		<?php
				if($ls_mov_operacion=="NC")
				{
				?>
          			<input name="txtint" id="txtint" type="text" value="Interes" class="sin-borde" size="10" style="text-align:right" readonly>
         		<?php	
				}	
				?>
      </div></td>
      <td><div align="left">
	            <?php
				if($ls_mov_operacion=="NC")
				{
				?>
          			<input name="chkinteres" type="checkbox" id="chkinteres" value="1">
         		<?php	
				}	
				?>
      </div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="21" colspan="4"><table width="613" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="207"><div align="center"><a href="#01">Detalle Presupuesto de Gasto </a></div></td>
          <td width="203"><div align="center"><a href="#02">Detalle Retenciones </a></div></td>
          <td width="203"><div align="center"><a href="#03">Detalle Presupuesto de Ingreso </a></div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="13"><div align="right"> </div> <a href="#01"> </a></td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtcon('$ls_mov_operacion');"><img src="imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Contable </a> </td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center"><?php $io_grid->makegrid($totalcon,$title2,$objectScg,770,'Detalles Contable',$grid2);?>
        
          <input name="totcon"  type="hidden" id="totcon"  size=5 value="<?php print $totalcon?>">
          <input name="lastscg" type="hidden" id="lastscg" size=5 value="<?php print $lastscg;?>">
          <input name="delete_scg" type="hidden" id="delete_scg" size=5>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="4"><table width="210" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr>
          <td width="82" height="20"><div align="right">Total Debe</div></td>
          <td width="128"><input name="txtdebe" type="text" id="txtdebe" value="<?php print number_format($ldec_mondeb,2,',','.');?>" style="text-align:right"></td>
        </tr>
        <tr>
          <td height="20"><div align="right">Total Haber</div></td>
          <td><input name="txthaber" type="text" id="txthaber" value="<?php print number_format($ldec_monhab,2,',','.');?>" style="text-align:right"></td>
        </tr>
        <tr>
          <td height="20"><div align="right">Diferencia</div></td>
          <td><input name="txtdiferencia" type="text" id="txtdiferencia" value="<?php print number_format($ldec_diferencia,2,',','.');?>" style="text-align:right"></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="4">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtpre('$ls_mov_operacion');"><img src="imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Presupuesto</a> </td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center">
        <a name="01" id="01"></a>
        <?php $io_grid->makegrid($totalpre,$title,$objectSpg,770,'Detalles Presupuestarios',$grid1);?>
        <input name="totpre"  type="hidden" id="totpre"  value="<?php print $totalpre?>">
        <input name="lastspg" type="hidden" id="lastspg" value="<?php print $lastspg;?>">
        <input name="delete_spg" type="hidden" id="delete_spg">
</div></td>
    </tr>
    <tr>
      <td height="22" colspan="4"><table width="223" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr>
          <td width="96" height="20"><div align="right">Total Presupuesto </div></td>
          <td width="127"><input name="totspg" type="text" id="totspg" value="<?php print number_format($ldec_monspg,2,',','.');?>" style="text-align:right"></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="4">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtret('$ls_mov_operacion');"><img src="imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Retenciones </a> </td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center"><a name="02" id="02"></a>
        <?php $io_grid->makegrid($totalret,$titleRet,$objectRet,770,'Detalles Retenciones',$gridRet);?>        
          <input name="totret"  type="hidden" id="totret"  value="<?php print $totalret?>">
          <input name="lastret" type="hidden" id="lastret" value="<?php print $lastret;?>">
          <input name="delete_ret" type="hidden" id="delete_scg3">
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="4"><table width="223" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr>
          <td width="96" height="20"><div align="right">Total Retenci&oacute;n </div></td>
          <td width="127"><input name="txtret" type="text" id="txtret" value="<?php print number_format($ldec_montoret,2,',','.');?>" style="text-align:right" readonly></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="4">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtpre();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Ingreso </a> </td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center">
        <a name="03"></a>
        <?php $io_grid->makegrid($totalspi,$titleSpi,$objectSpi,770,'Detalle Ingresos',$gridSpi);?>
        <input name="totspi" type="hidden" id="totspi" value="<?php print $totalspi?>">
        <input name="lastspi" type="hidden" id="lastspi" value="<?php print $lastspi;?>">
        <input name="delete_spi" type="hidden" id="delete_scg4">
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center"><a href="#00">Volver Arriba</a> </div></td>
    </tr>
  </table>
  <p><input name="operacion" type="hidden" id="operacion">
</p>
  </form>
</body>
<script language="javascript">

function ue_nuevo()
{
f=document.form1;
f.operacion.value ="NUEVO";
f.action="sigesp_scb_p_mov_banco.php";
f.submit();
}

function ue_guardar()
{
	f=document.form1;
	/*if(!"<?php print $lb_guardar;?>")
	{
	alert("No tiene derechos para registrar cheques");
	}
	else
	{*/
		if(uf_validar_campos())
		{
			f.operacion.value ="GUARDAR";
			f.action="sigesp_scb_p_mov_banco.php";
			f.submit();
		}
		else
		{
			alert("No ha completado los datos");
		}
	//}
}

function ue_eliminar()
{
	f=document.form1;
	ls_colocacion= f.txtcolocacion.value;
	ls_tipcol   = f.txttipocolocacion.value;
	ls_codban    = f.txtcodban.value;
	ls_ctaban    = f.txtcuenta.value;
	ls_cuentascg = f.txtcuentascg.value;
	ls_dencol    = f.txtdencolocacion.value;
	
	if((ls_colocacion!="")&&(ls_tipcol!="")&&(ls_codban!="")&&(ls_ctaban!="")&&(ls_cuentascg!="")&&(ls_dencol!=""))
	{
		f.operacion.value ="ELIMINAR";
		f.action="sigesp_scb_p_mov_banco.php";
		f.submit();
	}
	else
	{
		alert("No ha completado los datos");
	}
	//f.txtcuenta.focus(true);
}

function ue_buscar()
{
	window.open("sigesp_catdinamic_mov.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
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
		if(campo=="cmp")
		{
			document.form1.txtcomprobante.value=cadena;
		}
		if(campo=="cod")
		{
			document.form1.txtcodigo.value=cadena;
		}
		if(campo=="chequera")
		{
			document.form1.txtchequera.value=cadena;
		}
		if(campo=="numcheque")
		{
			document.form1.txtnumcheque.value=cadena;
		}
		if(campo=="desde")
		{
			document.form1.txtdesde.value=cadena;
		}
		if(campo=="hasta")
		{
			document.form1.txthasta.value=cadena;
		}
		
	}
	
	//Catalogo de cuentas contables
	function catalogo_cuentabanco()
	 {
	   f=document.form1;
	   ls_codban=f.txtcodban.value;
	   ls_denban=f.txtdenban.value;
	  	   if((ls_codban!=""))
		   {
			   pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&denban="+ls_denban;
			   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
		   }
		   else
		   {
				alert("Seleccione el Banco");   
		   }
	  
	 }
	 
	 function cat_tipocol()
	 {
	   f=document.form1;
	   pagina="sigesp_cat_tipocolocacion.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
	 
	 function catalogo_cuentascg()
	 {
	   f=document.form1;
	   pagina="sigesp_cat_filt_SCG.php?filtro="+'11102'+"&opener=sigesp_scb_d_colocacion.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
	 
	 function cat_bancos()
	 {
	   f=document.form1;
	   pagina="sigesp_cat_bancos.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }

	function cat_conceptos()
	 {
	   f=document.form1;
	   pagina="sigesp_cat_conceptos.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
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
			li_string=parseInt(ls_string);
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
			li_string=parseInt(ls_string);
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
			li_string=parseInt(ls_string);
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
   
   
   
  function uf_verificar_operacion()
   {
   		f=document.form1;
		f.operacion.value="CAMBIO_OPERA";
		f.submit();   
   }
   
   function uf_desaparecer(objeto)
   {
      eval("document.form1."+objeto+".style.visibility='hidden'");
   }
   function uf_aparecer(objeto)
   {
      eval("document.form1."+objeto+".style.visibility='visible'");
   }
   
function catprovbene()
{
	f=document.form1;
	if(f.rb_provbene[0].checked==true)
	{
		f.txtprovbene.disabled=false;	
		window.open("sigesp_catdinamic_prov.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else if(f.rb_provbene[1].checked==true)
	{
		f.txtprovbene.disabled=false;	
		window.open("sigesp_catdinamic_bene.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
}   

function uf_verificar_provbene(lb_checked,obj)
{
	f=document.form1;

	if((f.rb_provbene[0].checked)&&(obj!='P'))
	{
		f.tipo.value='P';
		f.txtprovbene.value="";
		f.txtdesproben.value="";
		f.txttitprovbene.value="Proveedor";
	}
	if((f.rb_provbene[1].checked)&&(obj!='B'))
	{
		f.txtprovbene.value="";
		f.txtdesproben.value="";
		f.tipo.value='B';
		f.txttitprovbene.value="Beneficiario";
	}
	if((f.rb_provbene[2].checked)&&(obj!='N'))
	{
		f.txtprovbene.value="----------";
		f.txtdesproben.value="Ninguno";
		f.tipo.value='N';
		f.txttitprovbene.value="";
	}
}

	function  uf_agregar_dtpre()
   {
   
	f=document.form1;
	ls_comprobante= f.txtcomprobante.value;
	ld_fecha      = f.txtfecha.value;
	ls_proccomp   = f.txtproccomp.value;
	ls_desccomp   = f.txtdesccomp.value;
	ls_provbene   = f.txtprovbene.value;	
	if(f.tipo[0].checked==true)
	{
		ls_tipo='P'
	}
	if(f.tipo[1].checked==true)
	{
		ls_tipo='B'
	}
	if(f.tipo[2].checked==true)
	{
		ls_tipo='N'
	}
	
	if((ls_comprobante!="")&&(ls_proccomp!="")&&(ld_fecha!="")&&(ls_provbene!="")&&(ls_tipo))
	{
		ls_pagina = "sigesp_w_regdt_presupuesto.php?procede="+ls_proccomp+"&comprobante="+ls_comprobante+"&fecha="+ld_fecha+"&descripcion="+ls_desccomp+"&tipo="+ls_tipo+"&provbene="+ls_provbene;
		window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=585,height=245,left=50,top=50,location=no,resizable=no,dependent=yes");
	}
	else
	{
		alert("Complete los datos del comprobante");
	}

   }
   var ls_debhab="";
   function  uf_agregar_dtcon(operacion)
   {
   		f=document.form1;
		ls_operacion=f.cmboperacion.value;
		var ls_debhab="";
		lb_valido=uf_chequear_operacion(ls_operacion,"AGREGARSCG");
		alert(ls_debhab);	
		li_lastscg=f.lastscg.value;
		li_newrow=parseInt(li_lastscg)+1;
		if(lb_valido)
		{
			ls_cuenta_scg=f.txtcuenta_scg.value;
			ls_descripcion=f.txtconcepto.value;
			ls_procede="SCBMOV";
			ls_documento=f.txtdocumento.value;
			ldec_monto=f.txtmonto.value;
			ls_cuenta_scg=f.txtcuenta_scg.value;
			total=f.totcon.value;
			eval("f.txtcontable"+li_newrow+".value='"+ls_cuenta_scg+"'");
			eval("f.txtdesdoc"+li_newrow+".value='"+ls_descripcion+"'");
			eval("f.txtdocscg"+li_newrow+".value='"+ls_documento+"'");
			eval("f.txtmontocont"+li_newrow+".value='"+ldec_monto+"'");
			eval("f.txtdebhab"+li_newrow+".value='"+ls_debhab+"'");
			eval("f.txtprocdoc"+li_newrow+".value='"+ls_procede+"'");
			f.lastscg.value=li_newrow;
			ls_pagina = "sigesp_w_regdt_contable.php?txtprocedencia=SCBMOV&totalcon="+total;
			window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=570,height=182,left=50,top=50,location=no,resizable=no,dependent=yes");
		}
   }
   
    function  uf_agregar_dtpre(operacion)
   {
   		f=document.form1;
		total=f.totpre.value;
		ls_pagina = "sigesp_w_regdt_presupuesto.php?txtprocedencia=SCBMOV&totalpre="+total;
		window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=570,height=250,left=50,top=50,location=no,resizable=no,dependent=yes");
   }
    function  uf_agregar_dtret(operacion)
   {
   		f=document.form1;
		total=f.totret.value;
		ldec_monobjret=f.txtmonobjret.value;
		ls_documento=f.txtdocumento.value;
		ls_procede="SCBMOV";
		if((ldec_monobjret!="")&&(ls_documento!=""))
		{
			ls_pagina = "sigesp_w_regdt_deducciones.php?cmbfilas="+total+"&monobjret="+ldec_monobjret+"&txtdocumento="+ls_documento+"&txtprocede="+ls_procede;
			window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=570,height=350,left=50,top=50,location=no,resizable=no,dependent=yes");
   		}
		else
		{
			alert("Debe completar los datos basicos del movimiento");
		}
   }
   
   function uf_chequear_operacion(operacion,accion)
   {
   
	   switch(operacion){
		case 'ND':
			var ls_debhab = 'H';
			if(accion=="AGREGARSPI")
			{
				return false;
			}
			else
			{
				return true;
			}
			break;
		case 'NC':
			var ls_debhab = 'D';
			if((accion=="AGREGARSPG")||(accion=="AGREGARRET"))
			{
				return false;
			}
			else
			{
				return true;
			}
			break;
		case 'CH':
			var ls_debhab = 'H';
			if(accion=="AGREGARSPI")
			{
				return false;
			}
			else
			{
				return true;
			}
			break;
		case 'RE':
			var ls_debhab = 'H';
			if((accion=="AGREGARSPI")||(accion=="AGREGARRET"))
			{
				return false;
			}
			else
			{
				return true;
			}
			break;
		case 'DP':
			var ls_debhab = 'D';
			if((accion=="AGREGARSPG")||(accion=="AGREGARRET"))
			{
				return false;
			}
			else
			{
				return true;
			}
			break;
		default: 
			alert("Verifique la operacion");
    	}
	}
   
   function uf_objeto(obj)
   {
   		alert(obj.name);
   
   }
   
   function uf_delete_Scg(row)
   {
   		f=document.form1;
		ls_cuenta=eval("f.txtcontable"+row+".value");
		ls_documento=eval("f.txtdocscg"+row+".value");
		ls_descripcion=eval("f.txtdesdoc"+row+".value");
		ls_procede=eval("f.txtprocdoc"+row+".value");
		ls_debhab=eval("f.txtdebhab"+row+".value");
		ldec_montocont=eval("f.txtmontocont"+row+".value");
		if((ls_cuenta!="")&&(ls_documento!="")&&(ls_descripcion!="")&&(ls_procede!="")&&(ls_debhab!=""))
		{
			f.operacion.value="DELETESCG";
			f.delete_scg.value=row;
			f.action="sigesp_scb_p_mov_banco.php";
			f.submit();
		}
		else
		{
			alert("No hay datos para eliminar");
		}
   }
   
   function uf_delete_Spg(row)
   {
   		f=document.form1;
		ls_cuenta=eval("f.txtcuenta"+row+".value");
		ls_estprog=eval("f.txtprogramatico"+row+".value");
		ls_documento=eval("f.txtdocumento"+row+".value");
		ls_descripcion=eval("f.txtdescripcion"+row+".value");
		ls_procede=eval("f.txtprocede"+row+".value");
		ls_operacion=eval("f.txtoperacion"+row+".value");
		ldec_monto=eval("f.txtmonto"+row+".value");
		if((ls_cuenta!="")&&(ls_estprog!="")&&(ls_documento!="")&&(ls_descripcion!="")&&(ls_procede!="")&&(ls_operacion!="")&&(ldec_monto!=""))
		{
			f.operacion.value="DELETESPG";
			f.delete_spg.value=row;
			f.action="sigesp_scb_p_mov_banco.php";
			f.submit();
		}
		else
		{
			alert("No hay datos para eliminar");
		}
   }
   function uf_delete_Ret(row)
   {
   		f=document.form1;
		f.operacion.value="DELETERET";
		f.delete_ret.value=row;
		f.action="sigesp_scb_p_mov_banco.php";
		f.submit();
   }
   function uf_delete_Spi(row)
   {
   		f=document.form1;
		f.operacion.value="DELETESPI";
		f.delete_spi.value=row;
		f.action="sigesp_scb_p_mov_banco.php";
		f.submit();
   }
   
   function uf_format(obj)
   {
		ldec_monto=uf_convertir(obj.value);
		obj.value=ldec_monto;
   }
   
   function uf_validar_campos(operacion)
   {
		f=document.form1;
		ls_documento=f.txtdocumento.value;
		if(ls_documento=="")
		{
			alert("Debe introducir un numero de documento");
			return false;	
		}
		
		ls_codban=f.txtcodban.value;
		ls_cuentaban=f.txtcuenta.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ld_fecha=f.txtfecha.value;
		ls_concepto=f.txtconcepto.value;
		if(f.rb_provbene[0].checked)
		{
			ls_tipo_dest="P";
		}
		if(f.rb_provbene[1].checked)
		{
			ls_tipo_dest="B";
		}
		if(f.rb_provbene[2].checked)
		{
			ls_tipo_dest="N";
		}
		ls_provbene=f.txtprovbene.value;
		ldec_monto=f.txtmonto.value;
		ldec_montoobjret=f.txtmonobjret.value;
		ldec_montoret=f.txtretenido.value;
		ldec_diferencia=f.txtdiferencia.value;
		
   }
</script>
</html>