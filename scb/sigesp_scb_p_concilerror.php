<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$dat=$_SESSION["la_empresa"];
	require_once("class_funciones_banco.php");
	$io_fun_banco = new class_funciones_banco();
	$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_concilerror.php",$ls_permisos,&$la_seguridad,$la_permisos);

	if( array_key_exists("operacion",$_POST))
	{
		$ls_nombre=$_POST["nombre"];
		$ls_mesano=$_POST["mesano"];
		$ls_tip_mov=$_POST["tip_mov"];
	}
	else
	{
		$ls_nombre=$_GET["nombre"];
		$ls_tip_mov=$_GET["tip_mov"];
		$ls_codban=$_GET["txtcodban"];
		$ls_denban=$_GET["txtdenban"];
		$ls_cuenta_banco=$_GET["txtcuenta"];
		$ls_dencuenta_banco=$_GET["txtdenominacion"];
		$ls_cuenta_scg=$_GET["txtcuenta_scg"];
		$ldec_disponible=$_GET["txtdisponible"];		
		$ls_mesano=$_GET["mesano"];
		
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php print $ls_nombre;?></title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
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
</head>
<body> <!-- !-->

<span class="toolbar"><a name="00"></a></span>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="8" class="cd-logo"><div align="center"><img src="../shared/imagebank/header.jpg" width="776" height="40" align="middle" class="celdas-blancas"></div></td>
  </tr>
  <?php
  	if($ls_tip_mov=="B")
	{
  ?>
 	<tr>
    <td height="13" class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_eliminar_error_banco();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><!--a href="javascript: ue_ayuda();"--><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0"><!--/a--></div></td>
    <td class="toolbar" width="660"><div align="center"></div></td>
    <td class="toolbar" width="3"><div align="center"></div></td>
    <td class="toolbar" width="3"><div align="center"></div></td>
    <td class="toolbar" width="3">&nbsp;</td>
  </tr>
  <?php
  }
  else
  {?>
  		<tr>
    <td height="13" class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
      <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><!--a href="javascript: ue_ayuda();"--><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0"><!--/a--></div></td>
    <td class="toolbar" width="20"><div align="center"></div></td>
    <td class="toolbar" width="20"><div align="center"></div></td>
    <td class="toolbar" width="660"><div align="center"></div></td>
    <td class="toolbar" width="3">&nbsp;</td>
  </tr>
  <?php
  }
  ?>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	$msg=new class_mensajes();	
	require_once("../shared/class_folder/class_funciones.php");
	$fun=new class_funciones();	
	$lb_guardar=true;
    require_once("../shared/class_folder/sigesp_include.php");
    $sig_inc=new sigesp_include();
    $con=$sig_inc->uf_conectar();
	require_once("../shared/class_folder/ddlb_operaciones_spg.php");
 	$obj_spg=new ddlb_operaciones_spg($con);
	require_once("../shared/class_folder/ddlb_operaciones_spi.php");
 	$obj_spi=new ddlb_operaciones_spi($con);
	require_once("../shared/class_folder/ddlb_conceptos.php");
 	$obj_con=new ddlb_conceptos($con);
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("sigesp_scb_c_movbanco.php");

	//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
		$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
		$ls_logusr="";
	}
	$ls_sistema="SCB";
	$ls_ventanas="sigesp_scb_p_concilerror.php";
/*	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventanas;

	if(array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos=true;
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
	}*/
	//Inclusión de la clase de seguridad.
	$in_classmovbco=new sigesp_scb_c_movbanco($la_seguridad);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	

	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_mov_operacion=$_POST["cmboperacion"];
		
		if($ls_operacion=="CAMBIO_OPERA")
		{
			$ls_opepre="";	
			$ls_codconmov="";
		}
		else
		{
			if(($ls_mov_operacion=="CH")||($ls_mov_operacion=="ND"))
			{			
				$ls_opepre=$_POST["opepre"];
			}
			elseif(($ls_mov_operacion=="DP")||($ls_mov_operacion=="NC"))
			{
				$ls_opepre=$_POST["opepre"];
			}
			else
			{
				$ls_opepre=$_POST["opepre"];
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
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$ldec_diferencia=0;
		$ldec_monspg=0;
		$ldec_monspi=0;
		$ldec_disponible=$_POST["txtdisponible"];
		$ldec_montomov=$_POST["txtmonto"];
		$ldec_monobjret=$_POST["txtmonobjret"];
		$ldec_montoret=$_POST["txtretenido"];
		$ldec_montomov=str_replace(".","",$ldec_montomov);
		$ldec_montomov=str_replace(",",".",$ldec_montomov);
		$ldec_monobjret=str_replace(".","",$ldec_monobjret);
		$ldec_monobjret=str_replace(",",".",$ldec_monobjret);
		$ldec_montoret=str_replace(".","",$ldec_montoret);
		$ldec_montoret=str_replace(",",".",$ldec_montoret);
		$ls_codconmov=$_POST["codconmov"];
		$ls_desmov=$_POST["txtconcepto"];
		$ls_cuenta_scg=$_POST["txtcuenta_scg"];
		$li_estint=$_POST["estint"];
		if(array_key_exists("nocontabili",$_POST))
		{
			$lb_nocontab="checked";
		}
		else
		{
			$lb_nocontab="";
		}
		if($lb_nocontab=="checked")
		{
			$ls_estmov="L";
		}
		else
		{
			$ls_estmov="N";
		}
		$ls_tip_ajuste=$_POST["tip_ajuste"];
		if($ls_tip_mov=="L")
		{
			
			if($ls_tip_ajuste=="A")
			{
				$lb_selecA="selected";
				$lb_selecB="";
				$lb_selecC="";
			}
			else
			{
				$lb_selecA="";
				$lb_selecB="selected";
				$lb_selecC="";
			}
		}
		else
		{
			$lb_selecA="";
			$lb_selecB="";
			$lb_selecC="selected";
		}

	}
	else
	{
		$ls_operacion= "NUEVO" ;
		if($ls_tip_mov=="L")
		{
			$lb_selecA="selected";
			$lb_selecB="";
			$lb_selecC="";
		}
		else
		{
			$lb_selecA="";
			$lb_selecB="";
			$lb_selecC="selected";
		}
	}	
	$li_row=0;
	$li_rows_spg=0;
	$li_rows_ret=0;
	$li_rows_spi=0;
	if($ls_operacion=="CARGAR_DT")
	{
		uf_cargar_dt();
	}
	
	function uf_cargar_dt()
	{
		global $in_classmovbco;
		global $objectScg;
		global $li_row;
		global $ls_estmov;
		global $ldec_mondeb;
		global $ldec_monhab;
		global $objectSpg;
		global $li_rows_spg;
		global $ldec_monspg;
		global $objectSpi;
		global $li_rows_spi;
		global $ldec_monspi;
		global $objectRet;
		global $li_rows_ret;
		global $ldec_montoret;
		global $ldec_diferencia;
		global $ls_docmov;
		global $ls_codban;
		global $ls_cuenta_banco;
		global $ls_mov_operacion;
		$ldec_montoret=0;
		$in_classmovbco->uf_cargar_dt($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov,&$objectScg,&$li_row,&$ldec_mondeb,&$ldec_monhab,&$objectSpg,&$li_rows_spg,&$ldec_monspg,&$objectSpi,&$li_rows_spi,&$ldec_monspi,&$objectRet,&$li_rows_ret,&$ldec_montoret);
		$ldec_diferencia=$ldec_mondeb-$ldec_monhab;
	}
	
	function uf_nuevo()
	{
		global $ls_mov_operacion;
		$ls_mov_operacion="NC";
	    global $ls_opepre;
		$ls_opepre="";
		global $ls_docmov;
		$ls_docmov="";
		global $ls_provbene;
		$ls_provbene="----------";
		global $ls_desproben;
		$ls_desproben="Ninguno";
		global $ls_tipo;
		$ls_tipo="-";
		global $lastspg;
		$lastspg = 0;
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		global $ld_fecha;
		global $fun;
		$ld_fecha=$fun->uf_cerosizquierda($ls_dia,2)."/".$fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
		global $lastscg;
		$lastscg=0;
		global $lastret;
		$lastret=0;
		global $lastspi;
		$lastspi=0;
		global $ldec_mondeb;
		$ldec_mondeb=0;
		global $ldec_monhab;
		$ldec_monhab=0;
		global $ldec_diferencia;
		$ldec_diferencia=0;
		global $ldec_monspg;
		$ldec_monspg=0;
		global $ldec_monspi;
		$ldec_monspi=0;
		global $ldec_montomov;
		$ldec_montomov="";
		global $ldec_monobjret;
		$ldec_monobjret="";
		global $ldec_montoret;
		$ldec_montoret="";
		global $ls_codconmov;
		$ls_codconmov='000';
		global $ls_desmov;
		$ls_desmov="";
		global $lb_nocontab;
		$lb_nocontab="";
		global $li_estint;
		$li_estint=0;
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		global $objectScg;
		global $objectSpg;
		global $objectSpi;
		global $objectRet;
		global $li_row_scg;
		$li_row_scg=1;
		$objectScg[$li_row_scg][1] = "<input type=text name=txtcontable".$li_row_scg." id=txtcontable".$li_row_scg."  value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";		
		$objectScg[$li_row_scg][2] = "<input type=text name=txtdocscg".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$objectScg[$li_row_scg][3] = "<input type=text name=txtdesdoc".$li_row_scg."    value='' class=sin-borde readonly style=text-align:left size=35 maxlength=254>";
		$objectScg[$li_row_scg][4] = "<input type=text name=txtprocdoc".$li_row_scg."   value='' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
		$objectScg[$li_row_scg][5] = "<input type=text name=txtdebhab".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
		$objectScg[$li_row_scg][6] = "<input type=text name=txtmontocont".$li_row_scg." value='' class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
		$objectScg[$li_row_scg][7] = "<a href=javascript:uf_delete_Scg('".$li_row_scg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
		global $li_temp_spg;
		$li_temp_spg=1;
		$objectSpg[$li_temp_spg][1]  = "<input type=text name=txtcuenta".$li_temp_spg."       id=txtcuenta".$li_temp_spg."       value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10 >";
		$objectSpg[$li_temp_spg][2]  = "<input type=text name=txtprogramatico".$li_temp_spg." id=txtprogramatico".$li_temp_spg." value='' class=sin-borde readonly style=text-align:center size=32 maxlength=29 >"; 
		$objectSpg[$li_temp_spg][3]  = "<input type=text name=txtdocumento".$li_temp_spg."    id=txtdocumento".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
		$objectSpg[$li_temp_spg][4]  = "<input type=text name=txtdescripcion".$li_temp_spg."  id=txtdescripcion".$li_temp_spg."  value='' class=sin-borde readonly style=text-align:left>";
		$objectSpg[$li_temp_spg][5]  = "<input type=text name=txtprocede".$li_temp_spg."      id=txtprocede".$li_temp_spg."      value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
		$objectSpg[$li_temp_spg][6]  = "<input type=text name=txtoperacion".$li_temp_spg."    id=txtoperacion".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
		$objectSpg[$li_temp_spg][7]  = "<input type=text name=txtmonto".$li_temp_spg."        id=txtmonto".$li_temp_spg."        value='' class=sin-borde readonly style=text-align:right>";		
		$objectSpg[$li_temp_spg][8]  = "<a href=javascript:uf_delete_Spg('".$li_temp_spg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Gasto' width=15 height=15 border=0></a>";	
		global $li_temp_spi;
		$li_temp_spi=1;
		$objectSpi[$li_temp_spi][1]  = "<input type=text name=txtcuentaspi".$li_temp_spi." value='' class=sin-borde readonly style=text-align:center size=6 maxlength=5>";
		$objectSpi[$li_temp_spi][2]  = "<input type=text name=txtdescspi".$li_temp_spi."   value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
		$objectSpi[$li_temp_spi][3]  = "<input type=text name=txtprocspi".$li_temp_spi."   value='' class=sin-borde readonly style=text-align:center size=32 maxlength=45>";
		$objectSpi[$li_temp_spi][4]  = "<input type=text name=txtdocspi".$li_temp_spi."    value='' class=sin-borde readonly style=text-align:center>";
		$objectSpi[$li_temp_spi][5]  = "<input type=text name=txtopespi".$li_temp_spi."    value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
		$objectSpi[$li_temp_spi][6]  = "<input type=text name=txtmontospi".$li_temp_spi."  value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
		$objectSpi[$li_temp_spi][7]  = "<a href=javascript:uf_delete_Spi('".$li_temp_spi."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Ingreso' width=15 height=15 border=0></a>";	
		global $li_temp_ret;
		$li_temp_ret=1;
		$objectRet[$li_temp_ret][1]  = "<input type=text name=txtdeduccion".$li_temp_ret."   value='' class=sin-borde readonly style=text-align:center  size=5 maxlength=5>";
		$objectRet[$li_temp_ret][2]  = "<input type=text name=txtcuentaret".$li_temp_ret."   value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
		$objectRet[$li_temp_ret][3]  = "<input type=text name=txtdescret".$li_temp_ret."     value='' class=sin-borde readonly style=text-align:left size=32 maxlength=45>";
		$objectRet[$li_temp_ret][4]  = "<input type=text name=txtdocret".$li_temp_ret."      value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$objectRet[$li_temp_ret][5]  = "<input type=text name=txtprocederet".$li_temp_ret."  value='' class=sin-borde readonly style=text-align:center size=6 maxlength=6>";
		$objectRet[$li_temp_ret][6]  = "<input type=text name=txtmontoobjret".$li_temp_ret." value='' class=sin-borde readonly style=text-align:right>";		
		$objectRet[$li_temp_ret][7]  = "<input type=text name=txtmontoret".$li_temp_ret."    value='' class=sin-borde readonly style=text-align:right >";
		$objectRet[$li_temp_ret][8]  = "<a href=javascript:uf_delete_Ret('".$li_temp_ret."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar Retención' width=15 height=15 border=0></a>";	
	}

	$titleSpi[1]="Cuenta";     $titleSpi[2]="Descripción";  $titleSpi[3]="Procede";     $titleSpi[4]="Documento";   	$titleSpi[5]="Operación";   $titleSpi[6]="Monto";   $titleSpi[7]="Edición";
	$title2[1]="Cuenta";       $title2[2]="Documento";      $title2[3]="Descripción";   $title2[4]="Procede";   	   $title2[5]="Debe/Haber";    $title2[6]="Monto";     $title2[7]="Edición";
	$title[1]="Cuenta";        $title[2]="Programatico";    $title[3]="Documento";      $title[4]="Descripción";   $title[5]="Procede";        $title[6]="Operación";  $title[7]="Monto";       $title[8]="Edición";
	$titleRet[1]="Deducción";  $titleRet[2]="Cuenta";       $titleRet[3]="Descripción"; $titleRet[4]="Documento";  $titleRet[5]="Procede"; $titleRet[6]="Objeto a Retencion";     $titleRet[7]="Retenido";  $titleRet[8]="Edición";
	
	$gridSpi="grid_Spi";
	$grid2="gridscg";	
    $grid1="grid_SPG";	
    $gridRet="grid_Ret";	
	
	if($ls_operacion == "NUEVO")
	{
		$ls_operacion= "" ;
		uf_nuevo();
	}
	if($ls_operacion == "GUARDAR")
	{			
		if($ls_tip_mov=='B')
		{
			$arr_errorbco["codban"]=$ls_codban;
			$arr_errorbco["ctaban"]=$ls_cuenta_banco;
			$arr_errorbco["numdoc"]=$ls_docmov;
			$arr_errorbco["codope"]=$ls_mov_operacion;
			if($lb_nocontab=="checked")
			{
				$ls_estmov="L";
				$arr_errorbco["estmov"]=$ls_estmov;
			}
			else
			{
				$ls_estmov="N";
				$arr_errorbco["estmov"]=$ls_estmov;
			}

			if(($ls_operacion=="CH")||($ls_operacion=="ND"))
			{			
				$li_cobrapaga=$_POST["ddlb_spg"];			
			}
			elseif(($ls_operacion=="DP")||($ls_operacion=="NC"))
			{
				$li_cobrapaga=$_POST["ddlb_spi"];
			}
			else
			{
				$li_cobrapaga=0;
			}
			if($ls_operacion=="CH")
			{
				$ls_chevau=$_POST["txtchevau"];
				if($ls_chevau=="")
				{
					$lb_valido=false;
				}
				else
				{
					$lb_valido=true;
				}
			}
			else
			{
				$ls_chevau="";
				$lb_valido=true;
			}
			if($ls_operacion=="NC")
			{
				if(array_key_exists("chkinteres",$_POST))
				{
					$li_estint=1;
				}
				else
				{
					$li_estint=0;
				}
			}
			else
			{
				$li_estint=0;
			}
			
			 $arr_errorbco["fecmes"]=$ls_mesano;
			 $arr_errorbco["fecmov"]=$ld_fecha;	
			 $arr_errorbco["conmov"]=$ls_desmov;
			 $arr_errorbco["monto"] =$ldec_montomov;
			 $arr_errorbco["monret"]=$ldec_montoret;
			 $arr_errorbco["chevau"]=$ls_chevau;
			 $arr_errorbco["estbpd"]=$ls_tip_mov;
			 $arr_errorbco["procede_doc"]='SCBMOV';
			 $arr_errorbco["estmovint"]=$li_estint;
			 $arr_errorbco["esterrcon"]='C';
			 $arr_errorbco["cobrapaga"]=$li_cobrapaga;

			$in_classmovbco->io_sql->begin_transaction();
			$lb_valido=$in_classmovbco->uf_procesar_errorbanco($arr_errorbco);
			if($lb_valido)
			{
				$in_classmovbco->io_sql->commit();
			}	
			else
			{
				$in_classmovbco->io_sql->rollback();			
			}
			$msg->message($in_classmovbco->is_msg_error);
			
		}
		if ($ls_tip_mov=='L')
		{
			$ls_provbene=$_POST["txtprovbene"];
			$ls_desproben=$_POST["txtdesproben"];
			$ls_tipo=$_POST["rb_provbene"];
			switch ($ls_tipo){
				case 'P':
					$ls_codpro=$ls_provbene;
					$ls_cedbene="----------";
					break;	
				case 'B':
					$ls_codpro="----------";
					$ls_cedbene=$ls_provbene;
					break;
				default:
					$ls_codpro="----------";
					$ls_cedbene="----------";
			}
			$ls_estdoc='C';
			$in_classmovbco->io_sql->begin_transaction();
			$lb_valido=$in_classmovbco->uf_guardar_automatico($ls_codban,$ls_cuenta_banco,$ls_docmov,$ls_mov_operacion,$ld_fecha,$ls_desmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_desproben,$ldec_montomov,$ldec_monobjret,$ldec_montoret,$ls_chevau,$ls_estmov,$li_estint,0,$ls_estbpd,'SCBMOV',' ',$ls_estdoc,$ls_tipo,$ls_codfuefin,$ls_numordpagmin,$ls_codtipfon);
			if($lb_valido)
			{
				$in_classmovbco->io_sql->commit();			
			}
			else
			{
				$in_classmovbco->io_sql->rollback();					
			}
			$msg->message($in_classmovbco->is_msg_error);
			uf_cargar_dt();			
	
		}
		else
		{
			uf_cargar_dt();			
		}
	}
	if($ls_operacion == "ELIMINAR")
	{
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_all_movimiento($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion);
		if($lb_valido)
		{
			$in_classmovbco->io_sql->commit();
		}	
		else
		{
			$in_classmovbco->io_sql->rollback();			
		}
		$msg->message($in_classmovbco->is_msg_error);
		uf_nuevo();
	}
	if($ls_operacion=="DELETESCG")
	{
		$li_row_delete=$_POST["delete_scg"];
		$ls_codded='00000';
		$ls_cuenta_scg=$_POST["txtcontable".$li_row_delete];
		$ls_debhab=$_POST["txtdebhab".$li_row_delete];
		$ls_numdoc=$_POST["txtdocscg".$li_row_delete];
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_dt_scg($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_numdoc,$ls_cuenta_scg,$ls_debhab,$ls_codded);
		$msg->message($in_classmovbco->is_msg_error);
		if($lb_valido)
		{
			$in_classmovbco->io_sql->commit();
		}
		else
		{
			$in_classmovbco->io_sql->rollback();
		}
		uf_cargar_dt();
	}
	if($ls_operacion=="DELETESPG")
	{
		$li_row_delete=$_POST["delete_spg"];
		$ls_cuenta_spg=$_POST["txtcuenta".$li_row_delete];
		$ls_programatica=$_POST["txtprogramatico".$li_row_delete];
		$ls_numdoc=$_POST["txtdocumento".$li_row_delete];
		$ls_operacion=$_POST["txtoperacion".$li_row_delete];
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_dt_spg($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_numdoc,$ls_cuenta_spg,$ls_operacion,$ls_programatica);
		$msg->message($in_classmovbco->is_msg_error);
		if($lb_valido)
		{
			$in_classmovbco->io_sql->commit();
		}
		else
		{
			$in_classmovbco->io_sql->rollback();
		}
		uf_cargar_dt();
	}
	if($ls_operacion=="DELETERET")
	{
		$li_row_delete=$_POST["delete_ret"];
		$ls_codded=$_POST["txtdeduccion".$li_row_delete];
		$ls_cuenta=$_POST["txtcontable".$li_row_delete];
		$ls_debhab=$_POST["txtdebhab".$li_row_delete];
		$ls_numdoc=$_POST["txtdocscg".$li_row_delete];
		$ldec_monto=$_POST["txtmontoret".$li_row_delete];
		$arr_movbco["codban"]=$ls_codban;
		$arr_movbco["ctaban"]=$ls_cuenta_banco;
		$arr_movbco["mov_document"]=$ls_docmov;
		$ld_fecdb=$fun->uf_convertirdatetobd($ld_fecha);
		$arr_movbco["codope"]=$ls_mov_operacion;
		$arr_movbco["fecha"]=$ld_fecha;
		if($ls_tipo=="P")
		{
			$arr_movbco["codpro"] =$ls_provbene;
			$arr_movbco["cedbene"]="----------";
		}
		else
		{
			$arr_movbco["cedbene"]=$ls_provbene;
			$arr_movbco["codpro"] ="----------";
		}
		$arr_movbco["monto_mov"]=$ldec_montomov;
		$arr_movbco["objret"]   =$ldec_monobjret;
		$arr_movbco["retenido"] =$ldec_montoret;
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_dt_scg($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_numdoc,$ls_cuenta,$ls_debhab,$ls_codded);
		$msg->message($in_classmovbco->is_msg_error);
		if($lb_valido)
		{
			if(($ls_mov_operacion=="ND")||($ls_mov_operacion=="RE")||($ls_mov_operacion=="CH"))
			{
				$ls_operacioncon="H";
			}
			else
			{
				$ls_operacioncon="D";
			}
			$ldec_monto=str_replace(".","",$ldec_monto);
			$ldec_monto=str_replace(",",".",$ldec_monto);
			$lb_valido=$in_classmovbco->uf_update_montodelete($arr_movbco,$ls_cuenta_scg,'SCBMOV',$ls_desmov,$ls_docmov,$ls_operacioncon,$ldec_monto,$ldec_monobjret,'00000');
			$in_classmovbco->io_sql->commit();
		}
		else
		{
			$in_classmovbco->io_sql->rollback();
		}
		uf_cargar_dt();
	}
	if($ls_operacion=="ELIMINAR_ERROR_BANCO")
	{
		$ls_mes=substr($ls_mesano,0,2);
		$ls_ano=substr($ls_mesano,3,4);
		$ls_fecmesano=$ls_mes.$ls_ano;
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_eliminar_error_banco($ls_docmov,$ls_cuenta_banco,$ls_codban,$ls_fecmesano);
		if($lb_valido)
		{
			$msg->message("El Error en Banco fue eliminado");
			$in_classmovbco->io_sql->commit();
		}	
		else
		{
			$msg->message("El Error en Banco no pudo ser eliminado");
			$in_classmovbco->io_sql->rollback();			
		}		
		uf_nuevo();	
	
	}
	if($ls_operacion == "CAMBIO_OPERA")
	{
		uf_cargar_dt();		
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
		if($li_estint==1)
		{
			$lb_checked="checked";
		}
		else
		{
			$lb_checked="";
		}
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
		$ls_chevau=$in_classmovbco->uf_generar_voucher($ls_empresa);
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
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
  ?>
  <br>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td colspan="4"><?php print $ls_nombre;?>
      <input name="nombre" type="hidden" id="nombre" value="<?php print  $ls_nombre;?>">
      <input name="tip_mov" type="hidden" id="tip_mov" value="<?php print $ls_tip_mov;?>"></td>
    </tr>
    <tr>
      <td colspan="4">
	  <input name="status_doc" type="hidden" id="status_doc">&nbsp;</td>
    </tr>
    <tr>
      <td width="110" height="22"><div align="right">Documento</div></td>
      <td width="202"><div align="left">
          <input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="rellenar_cad(this.value,15,'doc')" value="<?php print $ls_docmov;?>" size="24">
      </div></td>
      <td width="218"><div align="right">Fecha</div></td>
      <td width="248"><div align="left">
          <input name="txtfecha" type="text" id="txtfecha"  style="text-align:center" value="<?php print $ld_fecha;?>" size="24" maxlength="10" onKeyPress="currencyDate(this);"  datepicker="true">
          <input name="mesano" type="hidden" id="mesano" value="<?php print $ls_mesano;?>">
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
          <input name="txtcuenta_scg" type="text" id="txtcuenta_scg" style="text-align:center" value="<?php print $ls_cuenta_scg;?>" size="24" readonly>
      </div></td>
      <td><div align="right">Disponible</div></td>
      <td><div align="left">
          <input name="txtdisponible" type="text" id="txtdisponible" style="text-align:right" value="<?php print  $ldec_disponible;?>" size="24" readonly>
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
      <td><div align="right"><?php if($ls_mov_operacion=="CH")
								{
									print "Voucher";
								}
								?>
	  </div></td>
      <td><div align="left"><?php if($ls_mov_operacion=="CH")
								{
									print "<input name=txtchevau type=text id=txtchevau size=28 maxlength=25 value='".$ls_chevau."'>";
								}
								?>
	  </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right"><?php if($ls_mov_operacion!="RE")print "Afectación"; ?></div></td>
      <td><div align="left">
          <?php  if(($ls_mov_operacion=='ND')||($ls_mov_operacion=='CH'))
				{
					$obj_spg->uf_cargar_ddlb_spg(0,$ls_opepre,$ls_mov_operacion); 	
				}
				elseif(($ls_mov_operacion=='DP')||($ls_mov_operacion=='NC'))
				{
					$obj_spi->uf_cargar_ddlb_spi(0,$ls_opepre,$ls_mov_operacion); 
				}				
				?>
          <input name="opepre" type="hidden" id="opepre" value="<?php print $ls_opepre;?>">
</div></td>
      <td><div align="right">Concepto</div></td>
      <td><div align="left"><?php $obj_con->uf_cargar_conceptos($ls_mov_operacion,$ls_codconmov);	?>
        <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>">
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Concepto Movimiento </div></td>
      <td colspan="3"><div align="left">
          <input name="txtconcepto" type="text" id="txtconcepto" value="<?php print $ls_desmov;?>" size="120">
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right"><?php if(($ls_tip_mov=="L")||($ls_tip_mov=="B")) print "Tipo Ajuste";?> </div></td>
      <td colspan="2"><div align="left">
	<?php
	  if($ls_tip_mov=="L")
	  {
    ?>  <select name="tip_ajuste" id="tip_ajuste" style="width:200px">
            <option value="A" <?php print $lb_selecA;?>>Partidas no Registradas en Libro</option>
            <option value="B" <?php print $lb_selecB;?>>Error en Libro</option>
        </select>
	<?php
	  }
	  else
	  {
      ?>  <select name="tip_ajuste" id="tip_ajuste" style="width:200px">
            <option value="C" <?php print $lb_selecC;?>>Partidas no Registradas en Banco</option>
          </select>
	  <?php
	  }
	?>
      <input name="tipmov" type="hidden" id="tipmov" value="<?php print $ls_tip_mov;?>">
</div></td>
      <td>&nbsp;</td>
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
          <input name="txtmonto" type="text" id="txtmonto" style="text-align:right" onBlur="javascript:uf_format(this);" value="<?php print number_format($ldec_montomov,2,",",".");?>" size="24">
      </div></td>
      <td><div align="right">Monto Objeto a Retenci&oacute;n </div></td>
      <td><div align="left">
          <input name="txtmonobjret" type="text" id="txtmonobjret" style="text-align:right" onBlur="javascript:uf_format(this);" value="<?php print  number_format($ldec_monobjret,2,",",".");?>" size="24">
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Monto Retenido </div></td>
      <td><div align="left">
        <input name="txtretenido" type="text" id="txtretenido" style="text-align:right" value="<?php print number_format($ldec_montoret,2,",",".");?>" size="24" readonly>
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
          			<input name="chkinteres" type="checkbox" id="chkinteres" value="1" style="width:15px; height:15px" onClick="uf_selec_interes(this);" <?php print $lb_checked;?>>
         		<?php	
				}	
				?>
                <input name="estint" type="hidden" id="estint" value="<?php print $li_estint;?>">
</div></td>
      <td><div align="right">No Contabilizable </div></td>
      <td><div align="left">
        <input name="nocontabili" type="checkbox" id="nocontabili" value="checkbox" style="width:15px; height:15px" <?php print $lb_nocontab;?>>
      </div></td>
    </tr>
    <tr>
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
     <?php 
	 if($ls_tip_mov=='L')
	 {
	?>
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
      <td height="22" colspan="4">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtcon();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Contable </a> </td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center"><?php $io_grid->makegrid($li_row,$title2,$objectScg,770,'Detalles Contable',$grid2);?>
        
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
      <td height="22" colspan="4">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtpre();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Presupuesto</a> </td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center">
        <a name="01" id="01"></a>
        <?php $io_grid->makegrid($li_rows_spg,$title,$objectSpg,770,'Detalles Presupuestarios',$grid1);?>
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
      <td height="22" colspan="4">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtret('$ls_mov_operacion');"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Retenciones </a> </td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center"><a name="02" id="02"></a>
        <?php $io_grid->makegrid($li_rows_ret,$titleRet,$objectRet,770,'Detalles Retenciones',$gridRet);?>        
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
        <?php $io_grid->makegrid($li_rows_spi,$titleSpi,$objectSpi,770,'Detalle Ingresos',$gridSpi);?>
        <input name="totspi" type="hidden" id="totspi" value="<?php print $totalspi?>">
        <input name="lastspi" type="hidden" id="lastspi" value="<?php print $lastspi;?>">
        <input name="delete_spi" type="hidden" id="delete_scg4">
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center"><a href="#00">Volver Arriba</a> </div></td>
    </tr>
	<?php
	}
	?>
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
f.action="sigesp_scb_p_concilerror.php";
f.submit();
}

function ue_guardar()
{
	f=document.form1;
	f.operacion.value ="GUARDAR";
	f.action="sigesp_scb_p_concilerror.php";
	f.submit();	
}

function ue_eliminar()
{
	f=document.form1;
	ls_operacion=f.cmboperacion.value;		
	li_lastscg=f.lastscg.value;
	li_newrow=parseInt(li_lastscg,10)+1;
	ls_cuenta_scg=f.txtcuenta_scg.value;
	ls_descripcion=f.txtconcepto.value;
	ls_procede="SCBMOV";
	ls_documento=f.txtdocumento.value;
	ldec_monto=f.txtmonto.value;
	ls_cuenta_scg=f.txtcuenta_scg.value;
	ld_fecha=f.txtfecha.value;
	ls_codban=f.txtcodban.value;
	ls_ctaban=f.txtcuenta.value;
	ls_cuenta_scg=f.txtcuenta_scg.value;
	total=f.totcon.value;
	ldec_monobjret=f.txtmonobjret.value;
	ldec_monret=f.txtretenido.value;
	ls_nomproben=f.txtdesproben.value;
	if(f.nocontabili.checked==true)
	{
		ls_estmov="L";
	}
	else
	{
		ls_estmov="N";
	}

	if((ls_operacion=="CH")||(ls_operacion=="ND"))
	{			
		li_cobrapaga=f.ddlb_spg.value;			
	}
	else if((ls_operacion=="DP")||(ls_operacion=="NC"))
	{
		li_cobrapaga=f.ddlb_spi.value;
	}
	if(ls_operacion=="CH")
	{
		ls_chevau=f.txtchevau.value;
		if(ls_chevau=="")
		{
			lb_valido=false;
			f.txtchevau.focus();
		}
		else
		{
			lb_valido=true;
		}
	}
	else
	{
		ls_chevau=" ";
		lb_valido=true;
	}
	if(ls_operacion=="ND")
	{
		if(f.chkinteres.checked)
		{
			li_estint=1;
		}
		else
		{
			li_estint=0;
		}
	}
	else
	{
		li_estint=0;
	}
	if(f.rb_provbene[0].checked)
	{
		ls_tipo="P";
	}
	if(f.rb_provbene[1].checked)
	{
		ls_tipo="B";
	}
	if(f.rb_provbene[2].checked)
	{
		ls_tipo="-";
	}
	ls_provbene=f.txtprovbene.value;
	ldec_objret=ldec_monobjret;
	while(ldec_objret.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		ldec_objret=ldec_objret.replace(".","");
	}
	ldec_objret=ldec_objret.replace(",",".");
	while(ldec_monto.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		ldec_monto=ldec_monto.replace(".","");
	}
	ldec_monto=ldec_monto.replace(",",".");
	while(ldec_monret.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		ldec_monret=ldec_monret.replace(".","");
	}
	ldec_monret=ldec_monret.replace(",",".");
	if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
	{
		f.operacion.value ="ELIMINAR";
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
	f=document.form1;
	ls_codban=f.txtcodban.value;
	ls_ctaban=f.txtcuenta.value;
	ls_mesano=f.mesano.value;	
	pagina="sigesp_cat_error_banco.php?codban="+ls_codban+"&ctaban="+ls_ctaban+"&mesano="+ls_mesano;
	window.open(pagina,"catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=850,height=500,left=50,top=50,location=no,resizable=yes");
}

function ue_cerrar()
{
	f=document.form1;
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
	   pagina="sigesp_cat_filt_scg.php?filtro="+'11102'+"&opener=sigesp_scb_d_colocacion.php";
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
   
   
   
   
  function uf_verificar_operacion()
   {
   		f=document.form1;
		f.operacion.value="CAMBIO_OPERA";
		f.opepre.value=f.cmboperacion.value;
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
		window.open("sigesp_catdinamic_bene.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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

   function  uf_agregar_dtcon()
   {
   		f=document.form1;
		ls_operacion=f.cmboperacion.value;		
		li_lastscg=f.lastscg.value;
		li_newrow=parseInt(li_lastscg,10)+1;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ls_descripcion=f.txtconcepto.value;
		ls_procede="SCBMOV";
		ls_documento=f.txtdocumento.value;
		ldec_monto=f.txtmonto.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ld_fecha=f.txtfecha.value;
		ls_codban=f.txtcodban.value;
		ls_ctaban=f.txtcuenta.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		total=f.totcon.value;
		ldec_monobjret=f.txtmonobjret.value;
		ldec_monret=f.txtretenido.value;
		ls_nomproben=f.txtdesproben.value;
		ls_codconmov=f.ddlb_conceptos.value;
		if(ls_operacion=="CH")
		{
			ls_estbpd="D";
		}
		else
		{
			ls_estbpd="M";
		}
		

		if((ls_operacion=="CH")||(ls_operacion=="ND"))
		{			
			li_cobrapaga=f.ddlb_spg.value;			
		}
		else if((ls_operacion=="DP")||(ls_operacion=="NC"))
		{
			li_cobrapaga=f.ddlb_spi.value;
		}
		if(ls_operacion=="CH")
		{
			ls_chevau=f.txtchevau.value;
			if(ls_chevau=="")
			{
				lb_valido=false;
				f.txtchevau.focus();
			}
			else
			{
				lb_valido=true;
			}
		}
		else
		{
			ls_chevau=" ";
			lb_valido=true;
		}
		if(ls_operacion=="NC")
		{
			if(f.chkinteres.checked)
			{
				li_estint=1;
			}
			else
			{
				li_estint=0;
			}
		}
		else
		{
			li_estint=0;
		}
		if(f.rb_provbene[0].checked)
		{
			ls_tipo="P";
		}
		if(f.rb_provbene[1].checked)
		{
			ls_tipo="B";
		}
		if(f.rb_provbene[2].checked)
		{
			ls_tipo="-";
		}
		ls_tipmov=f.tip_mov.value;
		ls_estreglib=f.tip_ajuste.value;
		ls_provbene=f.txtprovbene.value;
		ldec_objret=ldec_monobjret;
		while(ldec_objret.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_objret=ldec_objret.replace(".","");
		}
		ldec_objret=ldec_objret.replace(",",".");
		while(ldec_monto.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_monto=ldec_monto.replace(".","");
		}
		ldec_monto=ldec_monto.replace(",",".");
		while(ldec_monret.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_monret=ldec_monret.replace(".","");
		}
		ldec_monret=ldec_monret.replace(",",".");
		if(f.nocontabili.checked==true)
		{
			ls_estmov="L";
		}
		else
		{
			ls_estmov="N";
		}
		
		if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
		{
			ls_pagina = "sigesp_w_regdt_contable.php?txtprocedencia=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+"&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_monobjret+"&retenido="+ldec_monret+"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&estbpd="+ls_estbpd+"&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+ls_codconmov+"&tip_mov="+ls_estreglib+"&opener=sigesp_scb_p_concilerror.php&estdoc="+ls_estmov;
			window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=570,height=182,left=50,top=50,location=no,resizable=no,dependent=yes");
		}
		else
		{
			alert("Complete los datos del Movimiento");
		}
   }
   
    function  uf_agregar_dtpre()
   {
  		 f=document.form1;
		ls_operacion=f.cmboperacion.value;		
		li_lastscg=f.lastscg.value;
		li_newrow=parseInt(li_lastscg,10)+1;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ls_descripcion=f.txtconcepto.value;
		ls_procede="SCBMOV";
		ls_documento=f.txtdocumento.value;
		ldec_monto=f.txtmonto.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ld_fecha=f.txtfecha.value;
		ls_codban=f.txtcodban.value;
		ls_ctaban=f.txtcuenta.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		total=f.totcon.value;
		ldec_objret=f.txtmonobjret.value;
		ldec_monret=f.txtretenido.value;
		ls_codconmov=f.ddlb_conceptos.value;
		ls_tipmov=f.tip_mov.value;
		if(ls_tipmov=='L')
		{
			ls_estreglib=f.tip_ajuste.value;
		}
		else
		{
			ls_estreglib=" ";
		}
		while(ldec_objret.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_objret=ldec_objret.replace(".","");
		}
		ldec_objret=ldec_objret.replace(",",".");
		while(ldec_monto.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_monto=ldec_monto.replace(".","");
		}
		ldec_monto=ldec_monto.replace(",",".");
		while(ldec_monret.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_monret=ldec_monret.replace(".","");
		}
		ldec_monret=ldec_monret.replace(",",".");
		if((ls_operacion=="CH")||(ls_operacion=="ND"))
		{			
			li_cobrapaga=f.ddlb_spg.value;			
		}
		else if((ls_operacion=="DP")||(ls_operacion=="NC"))
		{
			li_cobrapaga=f.ddlb_spi.value;
		}
		if(ls_operacion=="CH")
		{
			ls_chevau=f.txtchevau.value;
			if(ls_chevau=="")
			{
				lb_valido=false;
				f.txtchevau.focus();
			}
			else
			{
				lb_valido=true;
			}
		}
		else
		{
			ls_chevau=" ";
			lb_valido=true;
		}
		if(f.nocontabili.checked==true)
		{
			ls_estmov="L";
		}
		else
		{
			ls_estmov="N";
		}
		if(ls_operacion=="NC")
		{
			if(f.chkinteres.checked)
			{
				li_estint=1;
			}
			else
			{
				li_estint=0;
			}
		}
		else
		{
			li_estint=0;
		}
		if(f.rb_provbene[0].checked)
		{
			ls_tipo="P";
		}
		if(f.rb_provbene[1].checked)
		{
			ls_tipo="B";
		}
		if(f.rb_provbene[2].checked)
		{
			ls_tipo="-";
		}
		ls_provbene=f.txtprovbene.value;
		ls_nomproben=f.txtdesproben.value;
		if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0)&&(ldec_objret>0))
		{
			if((ls_operacion!="NC")&&(ls_operacion!="DP"))
			{
				ls_pagina = "sigesp_w_regdt_presupuesto.php?txtprocedencia=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+"&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_objret+"&retenido="+ldec_monret+"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&estbpd="+ls_estbpd+"&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+ls_codconmov+"&tip_mov="+ls_estreglib;
				
				window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=570,height=250,left=50,top=50,location=no,resizable=no,dependent=yes");
			}
			else
			{
				alert("Movimiento no puede registrar un gasto");			
			}
		}
		else
		{
			alert("Complete los datos del Movimiento");
		}
	}
    function  uf_agregar_dtret(operacion)
   {
   		f=document.form1;
		total=f.totret.value;
		ldec_monobjret=f.txtmonobjret.value;
		alert(ldec_monobjret);
		ls_documento=f.txtdocumento.value;
		ls_procede="SCBMOV";
		 f=document.form1;
		ls_operacion=f.cmboperacion.value;		
		li_lastscg=f.lastscg.value;
		li_newrow=parseInt(li_lastscg,10)+1;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ls_descripcion=f.txtconcepto.value;
		ls_procede="SCBMOV";
		ls_documento=f.txtdocumento.value;
		ldec_monto=f.txtmonto.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ld_fecha=f.txtfecha.value;
		ls_codban=f.txtcodban.value;
		ls_ctaban=f.txtcuenta.value;
		ls_cuenta_scg=f.txtcuenta_scg.value;
		total=f.totcon.value;
		ls_tipmov=f.tip_mov.value;
		if(ls_tipmov=='L')
		{
			ls_estreglib=f.tip_ajuste.value;
		}
		else
		{
			ls_estreglib=" ";
		}
		ldec_objret=f.txtmonobjret.value;
		ldec_monret=f.txtretenido.value;
		ls_codconmov=f.ddlb_conceptos.value;
		while(ldec_objret.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_objret=ldec_objret.replace(".","");
		}
		ldec_objret=ldec_objret.replace(",",".");
		while(ldec_monto.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_monto=ldec_monto.replace(".","");
		}
		ldec_monto=ldec_monto.replace(",",".");
		if((ls_operacion=="CH")||(ls_operacion=="ND"))
		{			
			li_cobrapaga=f.ddlb_spg.value;			
		}
		else if((ls_operacion=="DP")||(ls_operacion=="NC"))
		{
			li_cobrapaga=f.ddlb_spi.value;
		}
		if(ls_operacion=="CH")
		{
			ls_chevau=f.txtchevau.value;
			if(ls_chevau=="")
			{
				lb_valido=false;
				f.txtchevau.focus();
			}
			else
			{
				lb_valido=true;
			}
		}
		else
		{
			ls_chevau=" ";
		}
		if(f.nocontabili.checked==true)
		{
			ls_estmov="L";
		}
		else
		{
			ls_estmov="N";
		}
		if(ls_operacion=="NC")
		{
			if(f.chkinteres.checked)
			{
				li_estint=1;
			}
			else
			{
				li_estint=0;
			}
		}
		else
		{
			li_estint=0;
		}
		if(f.rb_provbene[0].checked)
		{
			ls_tipo="P";
		}
		if(f.rb_provbene[1].checked)
		{
			ls_tipo="B";
		}
		if(f.rb_provbene[2].checked)
		{
			ls_tipo="-";
		}
		ls_provbene=f.txtprovbene.value;
		ls_nomproben=f.txtdesproben.value;
		alert(ls_provbene+" "+ls_descripcion+" "+ls_codban+" "+ls_ctaban+" "+ls_documento+" "+ls_operacion+" "+ldec_monto+" "+ldec_objret);
		if((ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0)&&(ldec_objret>0))
		{
			if(ls_operacion=="CH")
			{
				ls_pagina = "sigesp_w_regdt_deducciones.php?objret="+ldec_monobjret+"&txtdocumento="+ls_documento+"&txtprocede=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+"&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_monobjret+"&retenido="+ldec_monret+"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&estbpd="+ls_tipmov+"&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+ls_codconmov+"&tip_mov="+ls_estreglib;
				window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=570,height=350,left=50,top=50,location=no,resizable=no,dependent=yes");
			}
			else
			{
				alert("Movimiento no aplican retenciones");
			}
		}
		else
		{
			alert("Complete los datos del Movimiento");
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
			f.action="sigesp_scb_p_concilerror.php";
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
			f.action="sigesp_scb_p_concilerror.php";
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
		f.action="sigesp_scb_p_concilerror.php";
		f.submit();
   }
   function uf_delete_Spi(row)
   {
   		f=document.form1;
		f.operacion.value="DELETESPI";
		f.delete_spi.value=row;
		f.action="sigesp_scb_p_concilerror.php";
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
		if((ls_codban=="")&&(ls_ctaban==""))
		{
			alert("Seleccione el banco y la cuenta");
		}
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
   function uf_selec_interes(obj)
   {
   		f=document.form1;
		alert(obj.checked);
		if(obj.checked==true)
		{
			f.estint.value=1;
		}
	   	else
		{
			f.estint.value=0;
		}
   }
   
   function ue_eliminar_error_banco()
   {
   		f=document.form1;
		ls_status=f.status_doc.value;
		ls_codigo=f.txtdocumento.value;
		ls_fecha=f.txtfecha.value;
		ls_codban=f.txtcodban.value;
		ls_cuenta=f.txtcuenta.value;
		if(ls_status=="C")
		{
			if((ls_codigo!="") && (ls_fecha!="") && (ls_codban!="") && (ls_cuenta!=""))
			{
				if(confirm("Esta seguro?, esta operación no puede ser reversada"))
				{
					f.operacion.value="ELIMINAR_ERROR_BANCO";
					f.submit();
				}
			}
			else
			{
				alert("Complete los datos");
			}
		}
   }
   
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>