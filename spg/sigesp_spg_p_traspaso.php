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
<head >
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
<title>Comprobante de Traspaso</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #f3f3f3;
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript" src="../shared/js/number_format.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo25 {color: #6699CC}
-->
</style>
</head>

<body onUnload="javascript:uf_valida_cuadre();">
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
function uf_valida_cuadre()
{
	f=document.form1;
	ls_comprobante=f.txtcomprobante.value;
	ldec_total1=f.txttotspg.value;
	ldec_total2=f.txttotspg2.value;
	ldec_total1=uf_convertir_monto(ldec_total1);
	ldec_total2=uf_convertir_monto(ldec_total2);
	ls_operacion=f.operacion.value;
	if((ls_operacion=="NUEVO")||(ls_operacion=="GUARDAR"))
	{
		if(parseFloat(ldec_total1)!=parseFloat(ldec_total2))
		{
			alert("Comprobante descuadrado,el monto a Disminuir debe ser igual al monto a Aumentar");
			f.operacion.value="CARGAR_DT";
			f.action="sigesp_spg_p_traspaso.php";
			f.submit();
		}
	}
}
</script>
<input type="hidden" id="titempresa" value=<?php echo $_SESSION["la_empresa"]["titulo"]; ?>>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="780" height="40"></td>
  </tr>
  	  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo25">Contabilidad Presupuestaria de Gasto</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
         <?php
	   if(array_key_exists("confinstr",$_SESSION["la_empresa"]))
	  {
      if($_SESSION["la_empresa"]["confinstr"]=='A')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='V')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2007.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='N')
	  {
   ?>
       <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
  <?php
      }
	  	 }
	  else
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
	<?php 
	}
	?>
  </tr>
  <tr>
    <td height="13" align="center" class="toolbar">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td width="25" height="20" align="center" class="toolbar"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="imprimir comprobante" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="530" class="toolbar">&nbsp;</td>
  </tr>
</table>
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");
require_once("../shared/class_folder/class_sigesp_int_spi.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("sigesp_spg_c_mod_presupuestarias.php");
require_once("class_funciones_gasto.php");
$io_fun_gasto=new class_funciones_gasto();
$io_fun_gasto->uf_load_seguridad("SPG","sigesp_spg_p_traspaso.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre=$_SESSION["la_empresa"];
    $li_estmodest=$arre["estmodest"];
	$ls_empresa=$arre["codemp"];
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

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$lb_permisos=true;
		}
		else
		{
			$lb_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$lb_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventana);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$sig_inc	 = new sigesp_include();
$con		 = $sig_inc->uf_conectar();
$fun_db		 = new class_funciones_db($con);
$in_classcmp = new sigesp_spg_c_mod_presupuestarias();
$fun		 = new class_funciones();
$int_scg	 = new class_sigesp_int_scg();
$int_spg	 = new class_sigesp_int_spg();
$msg		 = new class_mensajes();
$io_grid	 = new grid_param();
$int_fec	 = new class_fecha();
$io_sql  	 = new class_sql($con);

$ls_reporte = $io_fun_gasto->uf_select_config("SPG","REPORTE","MODIFICACION_PRESUPUESTARIA_TRASPASO","sigesp_spg_rpp_sol_mod_pre_forma0301.php","C");

$la_emp=$_SESSION["la_empresa"];
$li_estmodest  = $la_emp["estmodest"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion   = $_POST["operacion"];
 	$ls_procede     = $_POST["txtproccomp"];
	$ls_comprobante = $_POST["txtcomprobante"];
	$ls_fecha       = $_POST["txtfecha"];
	$ls_descripcion = $_POST["txtdesccomp"];
	$ls_procede	    = $_POST["txtproccomp"];
	$li_estapro     = $_POST["estapro"];
	$li_fila		= 0;
	$ls_contipo= $_POST["tipomod"];
	$ls_codtipo= $_POST["codtipomod"];
	//$ls_compread="readonly";
	$ls_compread="";
	$ls_existe=$_POST["existe"];
}
else
{
	$ls_operacion="NUEVO";
	$ls_existe="N";
	$_SESSION["ACTUALIZAR"]="NO";
	$_SESSION["ib_new"]	=true;
	$array_fecha=getdate();
	$ls_dia=$array_fecha["mday"];
	$ls_mes=$array_fecha["mon"];
	$ls_ano=$array_fecha["year"];
	$ls_fecha=$fun->uf_cerosizquierda($ls_dia,2)."/".$fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
	$li_fila = 0;
	$li_estapro = 0;
}

if(array_key_exists("cmbfuefin",$_POST))
{
	$ls_fuefin      = $_POST["cmbfuefin"];
}
else
{
	$ls_fuefin  = "--";
}

if(array_key_exists("txtuniadm",$_POST))
{
	$ls_coduniadm      = $_POST["txtuniadm"];
	$ls_denuniadm      = $_POST["txtdenuni"];
}
else
{
   	$ls_coduniadm      = "-----";
	$ls_denuniadm      = "";
}

if($ls_operacion=="VALIDAFECHA")
{
	$readonly="";
	$ls_existe=$_POST["existe"];
	$ls_comprobante  = $_POST["txtcomprobante"];
	$ls_procede   = $_POST["txtproccomp"];
	$ls_fecha     = $_POST["txtfecha"];
	$ldec_totdi=$_POST["txttotspg"];
	$ldec_totau=$_POST["txttotspg2"];
	$ls_descripcion = $_POST["txtdesccomp"];
	$ls_codemp=$la_emp["codemp"];
	if ($ls_contipo==0)
	{
		$ls_compread="";
	}
	else
	{
		//$ls_compread="readonly";
		$ls_compread="";
	}
	$ls_contipo= $_POST["tipomod"];
	$ls_codtipo= $_POST["codtipomod"];
	
	$lb_valido=$int_fec->uf_valida_fecha_periodo($ls_fecha,$ls_codemp);
	if(!($lb_valido))
	{
		$msg->message($int_fec->is_msg_error);
		$ls_fecha="01/01/1900";
	}
	else
	{
	  if ($ls_existe=="N")
	    { 
		  $lb_existe=$in_classcmp->uf_verificar_comprobante($ls_codemp,$ls_procede,$ls_comprobante);
		  if (($ls_comprobante=="000000000000000")&&($ls_contipo==1))
		  {
		  	$msg->message(" Debe Seleccionar un Tipo de Modificación Presupuestaria para generar el Número del Comprobante");
		  }
		  if($lb_existe)
		  {
			 $msg->message(" El Comprobante ya existe. El Sistema generara un nuevo numero de Comprobante");
			 //$ls_comprobante = $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPGINS');
			 $ls_comprobante = $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPGTRA');
		  }
		}
		else
		{
			$ls_comprobante=$_POST["txtcomprobante"];
		}
	}
	$li_fila		 = 0;
	$prov_sel="";
	$bene_sel="";
	$ning_sel="selected";
	$totalDI=1;
	$totalAU=1;
    $li_estmodest=$la_emp["estmodest"];
	if($li_estmodest==1)
	{
	   $li_size=32;
	   $li_maxlength=29;
	}
	else
	{
	   $li_size=40;
	   $li_maxlength=33;
	}
	$objectDI[1][1]="<input type=text name=txtcuentaDI1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
	$objectDI[1][2]="<input type=text name=txtprogramaticoDI1 value='' class=sin-borde readonly style=text-align:center size=$li_size maxlength=$li_maxlength><input type=hidden name=txtestcladis id=txtestcladis value=''>"; 
	$objectDI[1][3]="<input type=text name=txtdocumentoDI1 value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
	$objectDI[1][4]="<input type=text name=txtdescripcionDI1 value='' class=sin-borde readonly style=text-align:center>";
	$objectDI[1][5]="<input type=text name=txtprocedeDI1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
	$objectDI[1][6]="<input type=text name=txtoperacionDI1 value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
	$objectDI[1][7]="<input type=text name=txtmontoDI1 value='' class=sin-borde readonly style=text-align:right>";		
	$objectDI[1][8] ="";
	
	$objectAU[1][1]="<input type=text name=txtcuentaAU1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
	$objectAU[1][2]="<input type=text name=txtprogramaticoAU1 value='' class=sin-borde readonly style=text-align:center size=$li_size maxlength=$li_maxlength><input type=hidden name=txtestclaaum id=txtestclaaum value=''>"; 
	$objectAU[1][3]="<input type=text name=txtdocumentoAU1 value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
	$objectAU[1][4]="<input type=text name=txtdescripcionAU1 value='' class=sin-borde readonly style=text-align:center>";
	$objectAU[1][5]="<input type=text name=txtprocedeAU1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
	$objectAU[1][6]="<input type=text name=txtoperacionAU1 value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
	$objectAU[1][7]="<input type=text name=txtmontoAU1 value='' class=sin-borde readonly style=text-align:right>";		
	$objectAU[1][8] ="";
	
}
  //Titulos de la tabla de Detalle Presupuestario.
  $title[1]="Cuenta";        
  if($li_estmodest==1)
  {
   $title[2]="Imputación Presupuestaria";
  }
  else
  { 
   $title[2]="Programatico";     
  }     
  $title[3]="Documento";    
  $title[4]="Descripción";   
  $title[5]="Procede";          
  $title[6]="Operación";     
  $title[7]="Monto";         
  $title[8]="Edición";
  $grid1="grid_DI";	         
  $grid2="grid_AU";	
   //Titulos de la tabla de Detalle Contable
	 
if($ls_operacion=="NUEVO")//Acciones para un comprobante nuevo
{
	$ls_existe="N";
	$ls_procede = "SPGCMP";
	//$ls_comprobante = $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPGTRA');
	$ls_codtipo="";
	$ls_contipo= $in_classcmp->uf_buscar_tipos($la_emp["codemp"]); 
	if ($ls_contipo==0)
	{
		//$ls_comprobante = $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPGREC');
		$ls_comprobante = $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPGTRA');
		$ls_compread="";
	}
	else
	{
		//$ls_compread="readonly";
		$ls_compread="";
		print("<script language=JavaScript>");
		print "window.open('sigesp_spg_cat_tipomod.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes');";			
		print("</script>");
		$ls_comprobante="";
	}
	$ls_tipomod=""; // codigo del tipo de modificación presupuestaria
	$ls_descripcion = "";
	$ls_fuefin="";
	$ls_coduniadm      = "-----";
	$ls_denuniadm      = "";
	$ls_uniadm="";
	$ls_tipo      = "";
	$li_fila		 = 0;
	$ldec_mondeb=0;
	$ldec_diferencia=0;
	$ldec_monhab=0;	
	$prov_sel="";
	$bene_sel="";
	$ning_sel="selected";
	$ldec_totdi=0;
	$ldec_totau=0;
	$li_estapro   = 0;
	$totalDI=1;
	$totalAU=1;	
    $li_estmodest=$la_emp["estmodest"];
	if($li_estmodest==1)
	{
	   $li_size=32;
	   $li_maxlength=29;
	}
	else
	{
	   $li_size=40;
	   $li_maxlength=33;
	}
	$objectDI[1][1]="<input type=text name=txtcuentaDI1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
	$objectDI[1][2]="<input type=text name=txtprogramaticoDI1 value='' class=sin-borde readonly style=text-align:center size=$li_size maxlength=$li_maxlength><input type=hidden name=txtestcladis id=txtestcladis value=''>"; 
	$objectDI[1][3]="<input type=text name=txtdocumentoDI1 value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
	$objectDI[1][4]="<input type=text name=txtdescripcionDI1 value='' class=sin-borde readonly style=text-align:center>";
	$objectDI[1][5]="<input type=text name=txtprocedeDI1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
	$objectDI[1][6]="<input type=text name=txtoperacionDI1 value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
	$objectDI[1][7]="<input type=text name=txtmontoDI1 value='' class=sin-borde readonly style=text-align:right>";		
	$objectDI[1][8] ="";
	
	$objectAU[1][1]="<input type=text name=txtcuentaAU1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
	$objectAU[1][2]="<input type=text name=txtprogramaticoAU1 value='' class=sin-borde readonly style=text-align:center size=$li_size maxlength=$li_maxlength><input type=hidden name=txtestclaaum id=txtestclaaum value=''>"; 
	$objectAU[1][3]="<input type=text name=txtdocumentoAU1 value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
	$objectAU[1][4]="<input type=text name=txtdescripcionAU1 value='' class=sin-borde readonly style=text-align:center>";
	$objectAU[1][5]="<input type=text name=txtprocedeAU1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
	$objectAU[1][6]="<input type=text name=txtoperacionAU1 value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
	$objectAU[1][7]="<input type=text name=txtmontoAU1 value='' class=sin-borde readonly style=text-align:right>";		
	$objectAU[1][8] ="";

}

if(($ls_operacion=="CARGAR_DT")||($ls_operacion=="VALIDAFECHA"))
{
	
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha      =$_POST["txtfecha"];
	$ls_proccomp   =$_POST["txtproccomp"];
	$ls_desccomp   =$_POST["txtdesccomp"];
	$ls_provbene   ="----------";	
	$ls_tipo	   ="-";
	$ldec_mondeb=0;
	$ldec_diferencia=0;
	$ldec_monhab=0;	
	$ls_fuefin      = $_POST["cmbfuefin"];
	$ls_coduniadm      = $_POST["txtuniadm"];
	$ls_denuniadm      = $_POST["txtdenuni"];
	
	$ls_contipo= $_POST["tipomod"];
	$ls_codtipo= $_POST["codtipomod"]; 
	$ls_existe=$_POST["existe"];
	uf_cargar_dt($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);
	
}	
if($ls_operacion=="GUARDAR")
{
	$ls_codemp=$la_emp["codemp"];
	$ls_existe="C";
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha=$_POST["txtfecha"];
	$ls_procedencia=$_POST["txtproccomp"];
	$ls_descripcion=$_POST["txtdesccomp"];
	$ls_tipo="-";
	$int_int->is_tipo=$ls_tipo;
	$int_int->is_cod_prov="----------";
	$int_int->is_ced_ben="----------";
	$int_int->ib_procesando_cmp=false;
	$int_int->is_fecha=$fun->uf_convertirdatetobd($ld_fecha);
	$ls_codemp=$la_emp["codemp"];
	$ls_fuefin      = $_POST["cmbfuefin"];
	$ls_coduniadm      = $_POST["txtuniadm"];
	$ls_denuniadm      = $_POST["txtdenuni"];
	
	$ls_contipo= $_POST["tipomod"];
	$ls_codtipo= $_POST["codtipomod"];
	
	$lb_valido=$int_fec->uf_valida_fecha_periodo($ld_fecha,$ls_codemp);
	if(!($lb_valido))
	{
		$msg->message($int_fec->is_msg_error);
		$ls_fecha="01/01/1900";
	}
	else
	{
		$lb_valido=$in_classcmp->uf_guardar_automatico($ls_comprobante,$ld_fecha,$ls_procedencia,$ls_descripcion,$int_int->is_cod_prov,$int_int->is_ced_ben,$ls_tipo,2,0,$ls_fuefin,$ls_coduniadm);
		if(!$lb_valido)
		{
			$msg->message($in_classcmp->is_msg_error);
		}
		else
		{
		    $msg->message("Comprobante Guardado Satisfactoriamente");
		}
	}
    uf_cargar_dt($la_emp["codemp"],$ls_procedencia,$ls_comprobante,$ld_fecha);
}

if($ls_operacion=="ELIMINAR")
{
	$ls_coduniadm      = $_POST["txtuniadm"];
	$ls_denuniadm      = $_POST["txtdenuni"];
	$ls_existe="N";
	$lb_valido=false;
	$ls_codemp=$la_emp["codemp"];
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha=$_POST["txtfecha"];
	$ls_procedencia=$_POST["txtproccomp"];
	$ls_descripcion=$_POST["txtdesccomp"];
	$ls_tipo="-";
	$in_classcmp->is_tipo=$ls_tipo;
	$in_classcmp->is_cod_prov="----------";
	$in_classcmp->is_ced_ben="----------";
	$in_classcmp->ib_procesando_cmp=false;
	$in_classcmp->is_fecha=$fun->uf_convertirdatetobd($ld_fecha);
	
	$ls_fuente = "----------";
	$in_classcmp->is_cod_prov="----------";
	$in_classcmp->is_ced_ben="----------";
	$ls_comprobantes=$ls_comprobante;
	$ld_fechas=$ld_fecha;	
	
	$ls_contipo= $_POST["tipomod"];
	$ls_codtipo= $_POST["codtipomod"];
		
		
	$lb_valido=$in_classcmp->uf_delete_all_comprobante($ls_codemp,$ls_comprobante,$ld_fecha,$ls_procedencia);
	if($lb_valido)
	{
		$msg->message("Comprobante eliminado satisfactoriamente !!!");
		$ls_comprobante="";
		$ld_fecha="";
		$ls_descripcion="";
		$li_estapro   = 0;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_desc_event="Elimino el Comprobante ".$ls_comprobante." de fecha ".$ld_fecha."  con procedencia ".$ls_procedencia;
		$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////
		$int_spg->io_sql->commit();
		$ls_fuefin="";
		$ls_uniadm="";
	}
	else
	{
		$int_spg->io_sql->rollback();
		$msg->message("Error".$in_classcmp->is_msg_error);
	}

	uf_cargar_dt($la_emp["codemp"],$ls_procedencia,$ls_comprobantes,$ld_fechas);
}

if($ls_operacion=="DELETEDI")		
{
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha      =$_POST["txtfecha"];
	$ls_proccomp   =$_POST["txtproccomp"];
	$ls_desccomp   =$_POST["txtdesccomp"];
	$ls_provbene   ="----------";	
	$ls_tipo	   ="-";
	$li_fila	   =$_POST["fila"];
	
	$ls_contipo= $_POST["tipomod"];
	$ls_codtipo= $_POST["codtipomod"];
	
	$ls_prov=$ls_provbene;
	$ls_bene=$ls_provbene;
    
	$li_estmodest=$la_emp["estmodest"];
	$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	$ls_incio1=0;
	$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	$ls_incio2=$ls_loncodestpro1;
	$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	$ls_incio3=$ls_loncodestpro1+$ls_loncodestpro2;
	$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	$ls_incio4=$ls_incio3+$ls_loncodestpro3;
	$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
	$ls_incio5=$ls_incio4+$ls_loncodestpro4;
	
	if($li_estmodest==2)
	{
		$estprog[0]=substr($_POST["txtprogramaticoDI".$li_fila],$ls_incio1,$ls_loncodestpro1);
		$estprog[1]=substr($_POST["txtprogramaticoDI".$li_fila],$ls_incio2,$ls_loncodestpro2);
		$estprog[2]=substr($_POST["txtprogramaticoDI".$li_fila],$ls_incio3,$ls_loncodestpro3);
		$estprog[3]=substr($_POST["txtprogramaticoDI".$li_fila],$ls_incio4,$ls_loncodestpro4);
		$estprog[4]=substr($_POST["txtprogramaticoDI".$li_fila],$ls_incio5,$ls_loncodestpro5);
	}
	else
	{
		$estprog[0]=substr($_POST["txtprogramaticoDI".$li_fila],$ls_incio1,$ls_loncodestpro1);
		$estprog[1]=substr($_POST["txtprogramaticoDI".$li_fila],$ls_incio2,$ls_loncodestpro2);
		$estprog[2]=substr($_POST["txtprogramaticoDI".$li_fila],$ls_incio3,$ls_loncodestpro3);
		$estprog[3]=$fun->uf_cerosizquierda(0,25);
		$estprog[4]=$fun->uf_cerosizquierda(0,25);
	}
	$estprog[0] = $fun->uf_cerosizquierda($estprog[0],25);
	$estprog[1] = $fun->uf_cerosizquierda($estprog[1],25);
	$estprog[2] = $fun->uf_cerosizquierda($estprog[2],25);
	$estprog[3] = $fun->uf_cerosizquierda($estprog[3],25);
	$estprog[4] = $fun->uf_cerosizquierda($estprog[4],25);
	$estprog[5] = $_POST["txtestcladis".$li_fila];
	
	//print_r($estprog); 
	$ls_cuenta=$_POST["txtcuentaDI".$li_fila];	
	$ls_procede_doc=$_POST["txtprocedeDI".$li_fila];
	$ls_descripcion=$_POST["txtdescripcionDI".$li_fila];
	$ls_documento=$_POST["txtdocumentoDI".$li_fila];
	$ls_operacion=$_POST["txtoperacionDI".$li_fila];
	$ldec_monto_anterior=$_POST["txtmontoDI".$li_fila];
	$ldec_monto_actual=0;
	$li_tipo_comp=2;
	
	$ls_mensaje=$int_spg->uf_operacion_codigo_mensaje($ls_operacion);
	$int_spg->is_codemp=$la_emp["codemp"];
	//$int_spg->is_fecha=$fun->uf_formatovalidofecha($ld_fecha);
	$int_spg->is_fecha=$fun->uf_convertirdatetobd($ld_fecha);
	$int_spg->is_procedencia=$ls_proccomp;
	$int_spg->is_comprobante=$ls_comprobante;
	$int_spg->is_tipo=$ls_tipo;
	$int_spg->is_cod_prov=$ls_prov;
	$int_spg->is_ced_ben=$ls_bene;
	$int_spg->ib_AutoConta=true;
    if ($ls_tipo=="B")  
	{
	   $ls_fuente = $ls_bene; 
	}	
	else
	{ 
		if ($ls_tipo=="P")
		 {  
			$ls_fuente = $ls_prov; 
		 }	
		 else 
		 {  
			$ls_fuente = "----------"; 
		 } 
	}
    $ls_coduniadm      = $_POST["txtuniadm"];
	$ls_denuniadm      = $_POST["txtdenuni"];
	if(!$int_spg->uf_spg_select_cuenta($la_emp["codemp"],$estprog,$ls_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
	{  
	  return false;
	}
	
	$lb_valido=$in_classcmp->uf_int_spg_delete_movimiento($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha,$ls_tipo,$ls_fuente,$ls_prov,$ls_bene,
													     $estprog,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_descripcion,$ls_mensaje,$li_tipo_comp,
													     $ldec_monto_anterior,$ldec_monto_actual,$ls_sc_cuenta);

	if($lb_valido)
	{
		$msg->message("Movimiento eliminado satisfactoriamente");
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_desc_event="Elimino el movimiento presupuestario ".$ls_documento." con operacion".$ls_operacion." por un monto de ".$ldec_monto_anterior." para la cuenta ".$ls_cuenta." correspondiente a la estructura programatica ".$estprog[0]."-".$estprog[1]."-".$estprog[2]."-".$estprog[3]."-".$estprog[4]."; para el comprobante ".$ls_comprobante." de fecha ".$ld_fecha;
		$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////
		$int_spg->io_sql->commit();
	}
	else
	{
		$msg->message("Movimiento no pudo ser eliminado");
		$int_spg->io_sql->rollback();
	}
	uf_cargar_dt($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);

}

if($ls_operacion=="DELETEAU")		
{
	$ls_coduniadm      = $_POST["txtuniadm"];
	$ls_denuniadm      = $_POST["txtdenuni"];
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha      =$_POST["txtfecha"];
	$ls_proccomp   =$_POST["txtproccomp"];
	$ls_desccomp   =$_POST["txtdesccomp"];
	$ls_provbene   ="----------";	
	$ls_tipo	   ="-";
	$li_fila	   =$_POST["fila"];
	$ls_prov=$ls_provbene;
	$ls_bene=$ls_provbene;
    $li_estmodest=$la_emp["estmodest"];
	$li_estmodest=$la_emp["estmodest"];
	$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	$ls_incio1=0;
	$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	$ls_incio2=$ls_loncodestpro1;
	$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	$ls_incio3=$ls_loncodestpro1+$ls_loncodestpro2;
	$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	$ls_incio4=$ls_incio3+$ls_loncodestpro3;
	$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
	$ls_incio5=$ls_incio4+$ls_loncodestpro4;
	
	$ls_contipo= $_POST["tipomod"];
	$ls_codtipo= $_POST["codtipomod"];
	
	if($li_estmodest==2)
	{
		$estprog[0]=substr($_POST["txtprogramaticoAU".$li_fila],$ls_incio1,$ls_loncodestpro1);
		$estprog[1]=substr($_POST["txtprogramaticoAU".$li_fila],$ls_incio2,$ls_loncodestpro2);
		$estprog[2]=substr($_POST["txtprogramaticoAU".$li_fila],$ls_incio3,$ls_loncodestpro3);
		$estprog[3]=substr($_POST["txtprogramaticoAU".$li_fila],$ls_incio4,$ls_loncodestpro4);
		$estprog[4]=substr($_POST["txtprogramaticoAU".$li_fila],$ls_incio5,$ls_loncodestpro5);
	}
	else
	{
		$estprog[0]=substr($_POST["txtprogramaticoAU".$li_fila],$ls_incio1,$ls_loncodestpro1);
		$estprog[1]=substr($_POST["txtprogramaticoAU".$li_fila],$ls_incio2,$ls_loncodestpro2);
		$estprog[2]=substr($_POST["txtprogramaticoAU".$li_fila],$ls_incio3,$ls_loncodestpro3);
		$estprog[3]=$fun->uf_cerosizquierda(0,25);
		$estprog[4]=$fun->uf_cerosizquierda(0,25);
	}
	$estprog[0]  = $fun->uf_cerosizquierda($estprog[0],25);
	$estprog[1]  = $fun->uf_cerosizquierda($estprog[1],25);
	$estprog[2]  = $fun->uf_cerosizquierda($estprog[2],25);
	$estprog[3]  = $fun->uf_cerosizquierda($estprog[3],25);
	$estprog[4]  = $fun->uf_cerosizquierda($estprog[4],25);
	$estprog[5]  = $_POST["txtestclaaum".$li_fila];
	$ls_cuenta=$_POST["txtcuentaAU".$li_fila];	
	$ls_procede_doc=$_POST["txtprocedeAU".$li_fila];
	$ls_descripcion=$_POST["txtdescripcionAU".$li_fila];
	$ls_documento=$_POST["txtdocumentoAU".$li_fila];
	$ls_operacion=$_POST["txtoperacionAU".$li_fila];
	$ldec_monto_anterior=$_POST["txtmontoAU".$li_fila];
	$ldec_monto_actual=0;
	$li_tipo_comp=2;
	
	$ls_mensaje=$int_spg->uf_operacion_codigo_mensaje($ls_operacion);
	$int_spg->is_codemp=$la_emp["codemp"];
	$int_spg->is_fecha=$fun->uf_convertirdatetobd($ld_fecha);
	$int_spg->is_procedencia=$ls_proccomp;
	$int_spg->is_comprobante=$ls_comprobante;
	$int_spg->is_tipo=$ls_tipo;
	$int_spg->is_cod_prov=$ls_prov;
	$int_spg->is_ced_ben=$ls_bene;
	$int_spg->ib_AutoConta=true;
    if ($ls_tipo=="B")  
	{ 
		$ls_fuente = $ls_bene; 
	}	
	else
	{ 
		if ($ls_tipo=="P")
		 {  
			$ls_fuente = $ls_prov; 
		 }	
		 else 
		 {  
			$ls_fuente = "----------"; 
		 } 
	}

	if(!$int_spg->uf_spg_select_cuenta($la_emp["codemp"],$estprog,$ls_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
	{  
	  $msg->message(" La cuenta ".$ls_cuenta." no existe ...");
	  return false;
	}
	
	$lb_valido=$in_classcmp->uf_int_spg_delete_movimiento($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha,$ls_tipo,$ls_fuente,$ls_prov,$ls_bene,
													      $estprog,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_descripcion,$ls_mensaje,$li_tipo_comp,
													      $ldec_monto_anterior,$ldec_monto_actual,$ls_sc_cuenta);

	if($lb_valido)
	{
		$msg->message(" Movimiento eliminado satisfactoriamente");
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_desc_event="Elimino el movimiento presupuestario ".$ls_documento." con operacion ".$ls_operacion." por un monto de 
		".$ldec_monto_anterior." para la cuenta ".$ls_cuenta." correspondiente a la estructura programatica ".$estprog[0]."-".
		$estprog[1]."-".$estprog[2]."-".$estprog[3]."-".$estprog[4]."; para el comprobante ".$ls_comprobante." de fecha ".$ld_fecha;
		$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////
		$int_spg->io_sql->commit();
	}
	else
	{
		$int_spg->io_sql->rollback();
		$msg->message(" El Movimiento no se pudo Eliminar");
	}
	/////////////////////LLamado a la función de cargar los detalles////////////////////////////////////////////
	uf_cargar_dt($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);

}

function uf_cargar_dt($ls_codemp,$ls_proccomp,$ls_comprobante,$ld_fecha)
{
	global $in_classcmp;
	global $la_emp;
	global $totalDI;
	global $totalAU;
	global $objectDI;
	global $objectAU;
	global $ldec_mondeb;
	global $ldec_monhab;
	global $ldec_diferencia;
	global $ldec_totdi;
	global $ldec_totau;
	$ldec_mondeb=0;
	$ldec_monhab=0;
	$ldec_diferencia=0;
	$ldec_totdi=0;
	$ldec_totau=0;
	$i=0;
	$rs_dtcmp=$in_classcmp->uf_cargar_dt_comprobante($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);
	$li_numrows=$in_classcmp->io_sql->num_rows($rs_dtcmp);
	$h=0;

	if($li_numrows>0)
	{	
			while($row=$in_classcmp->io_sql->fetch_row($rs_dtcmp))
			{
            $li_estmodest=$la_emp["estmodest"];
			$ls_cuenta=$row["spg_cuenta"];
			$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
			$ls_incio1=25-$ls_loncodestpro1;
			$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
			$ls_incio2=25-$ls_loncodestpro2;
			$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
			$ls_incio3=25-$ls_loncodestpro3;
			$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
			$ls_incio4=25-$ls_loncodestpro4;
			$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
			$ls_incio5=25-$ls_loncodestpro5;
				
				if($li_estmodest==2)
				{
					$ls_programatico=substr($row["codest1"],$ls_incio1,$ls_loncodestpro1).substr($row["codest2"],$ls_incio2,$ls_loncodestpro2).substr($row["codest3"],$ls_incio3,$ls_loncodestpro3).substr($row["codest4"],$ls_incio4,$ls_loncodestpro4).substr($row["codest5"],$ls_incio5,$ls_loncodestpro5);
					
				}
				else
				{
					$ls_programatico=substr($row["codest1"],$ls_incio1,$ls_loncodestpro1).substr($row["codest2"],$ls_incio2,$ls_loncodestpro2).substr($row["codest3"],$ls_incio3,$ls_loncodestpro3);
					
				}
				$ls_codprog=$row["codest1"].$row["codest2"].$row["codest3"].$row["codest4"].$row["codest5"];
				$ls_documento=$row["documento"];
				$ls_descripcion=$row["descripcion"];
				$ls_procede=$row["procede_doc"];
				$ls_operacion=$row["operacion"];
				$ldec_monto=$row["monto"];
			    $ls_estcla=trim($row["estcla"]);
				if(trim($ls_operacion)=="DI")
				{
					$i=$i+1;
					$li_estmodest=$la_emp["estmodest"];
					if($li_estmodest==1)
					{
					   $li_size=32;
					   $li_maxlength=29;
					}
					else
					{
					   $li_size=40;
					   $li_maxlength=33;
					}
					$objectDI[$i][1]="<input type=text name=txtcuentaDI".$i." value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
					$objectDI[$i][2]="<input type=text name=txtprogramaticoDI".$i." value='".$ls_programatico."' class=sin-borde readonly style=text-align:center size=$li_size maxlength=$li_maxlength><input type=hidden name=txtestcladis".$i." id=txtestcladis".$i." value='".$ls_estcla."'>"; 
					$objectDI[$i][3]="<input type=text name=txtdocumentoDI".$i." value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>".
								     "<input type=hidden name=txtcodprogDI".$i." id=txtcodprogDI".$i." value='".$ls_codprog."'>"; 
					$objectDI[$i][4]="<input type=text name=txtdescripcionDI".$i." value='".$ls_descripcion."' title='".$ls_descripcion."' class=sin-borde readonly style=text-align:center>";
					$objectDI[$i][5]="<input type=text name=txtprocedeDI".$i." value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
					$objectDI[$i][6]="<input type=text name=txtoperacionDI".$i." value='".$ls_operacion."' class=sin-borde readonly style=text-align:center size=4 maxlength=3>";
					$objectDI[$i][7]="<input type=text name=txtmontoDI".$i." value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right>";		
					$objectDI[$i][8] ="<a href=javascript:uf_delete_disminucion(".($i).");><img src=../shared/imagebank/tools15/eliminar.gif width=15 height=15 border=0 alt=Eliminar Detalle Presupuesto></a>  <a href=javascript:uf_abrir_pdt_presupuestodis(".($i).");><img src=../shared/imagebank/mas.gif width=13 height=18 border=0 alt=Abrir Detalle></a>";
					$ldec_totdi = $ldec_totdi + $ldec_monto;
				}
				else
				{
					$h=$h+1;
					$li_estmodest=$la_emp["estmodest"];
					if($li_estmodest==1)
					{
					   $li_size=32;
					   $li_maxlength=29;
					}
					else
					{
					   $li_size=40;
					   $li_maxlength=33;
					}
					$objectAU[$h][1]="<input type=text name=txtcuentaAU".$h." value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
					$objectAU[$h][2]="<input type=text name=txtprogramaticoAU".$h." value='".$ls_programatico."' class=sin-borde readonly style=text-align:center size=$li_size maxlength=$li_maxlength><input type=hidden name=txtestclaaum".$h." id=txtestclaaum".$h."  value='".$ls_estcla."'>"; 
					$objectAU[$h][3]="<input type=text name=txtdocumentoAU".$h." value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>".
								     "<input type=hidden name=txtcodprogAU".$h." id=txtcodprogAU".$h." value='".$ls_codprog."'>"; 
					$objectAU[$h][4]="<input type=text name=txtdescripcionAU".$h." value='".$ls_descripcion."' title='".$ls_descripcion."' class=sin-borde readonly style=text-align:center>";
					$objectAU[$h][5]="<input type=text name=txtprocedeAU".$h." value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
					$objectAU[$h][6]="<input type=text name=txtoperacionAU".$h." value='".$ls_operacion."' class=sin-borde readonly style=text-align:center size=4 maxlength=3>";
					$objectAU[$h][7]="<input type=text name=txtmontoAU".$h." value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right>";		
					$objectAU[$h][8] ="<a href=javascript:uf_delete_aumento(".($h).");><img src=../shared/imagebank/tools15/eliminar.gif width=15 height=15 border=0 alt=Eliminar Detalle Presupuesto></a>  <a href=javascript:uf_abrir_pdt_presupuestoaum(".($h).");><img src=../shared/imagebank/mas.gif width=13 height=18 border=0 alt=Abrir Detalle></a>";
					$ldec_totau = $ldec_totau + $ldec_monto;
				}
			}//End While
		    $in_classcmp->io_sql->free_result($rs_dtcmp);

		}//En if 
		
		if($i==0)
		{
			$li_estmodest=$la_emp["estmodest"];
			if($li_estmodest==1)
			{
			   $li_size=32;
			   $li_maxlength=29;
			}
			else
			{
			   $li_size=40;
			   $li_maxlength=33;
			}
			$objectDI[1][1]="<input type=text name=txtcuentaDI1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$objectDI[1][2]="<input type=text name=txtprogramaticoDI1 value='' class=sin-borde readonly style=text-align:center size=$li_size maxlength=$li_maxlength><input type=hidden name=txtestcladis id=txtestcladis value=''>"; 
			$objectDI[1][3]="<input type=text name=txtdocumentoDI1 value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
			$objectDI[1][4]="<input type=text name=txtdescripcionDI1 value='' class=sin-borde readonly style=text-align:center>";
			$objectDI[1][5]="<input type=text name=txtprocedeDI1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$objectDI[1][6]="<input type=text name=txtoperacionDI1 value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
			$objectDI[1][7]="<input type=text name=txtmontoDI1 value='' class=sin-borde readonly style=text-align:right>";		
			$objectDI[1][8] ="";
		}
		if($h==0)
		{
			$li_estmodest=$la_emp["estmodest"];
			if($li_estmodest==1)
			{
			   $li_size=32;
			   $li_maxlength=29;
			}
			else
			{
			   $li_size=40;
			   $li_maxlength=33;
			}
			$objectAU[1][1]="<input type=text name=txtcuentaAU1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$objectAU[1][2]="<input type=text name=txtprogramaticoAU1 value='' class=sin-borde readonly style=text-align:center size=$li_size maxlength=$li_maxlength><input type=hidden name=txtestclaaum id=txtestclaaum value=''>"; 
			$objectAU[1][3]="<input type=text name=txtdocumentoAU1 value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
			$objectAU[1][4]="<input type=text name=txtdescripcionAU1 value='' class=sin-borde readonly style=text-align:center>";
			$objectAU[1][5]="<input type=text name=txtprocedeAU1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$objectAU[1][6]="<input type=text name=txtoperacionAU1 value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
			$objectAU[1][7]="<input type=text name=txtmontoAU1 value='' class=sin-borde readonly style=text-align:right>";		
			$objectAU[1][8] ="";
		}
	$totalDI=$i;
	$totalAU=$h;
}
		
?> 
<form name="form1" method="post" action=""><div >
<?php 
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	/*if (($lb_permisos)||($ls_logusr=="PSEGIS"))
	{
		print("<input type=hidden name=permisos id=permisos value='$lb_permisos'>");
	}
	else
	{
		print("<script language=JavaScript>");
		print(" location.href='sigespwindow_blank.php'");
		print("</script>");
	}*/
	$io_fun_gasto->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_gasto);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<input type="hidden" id="nombreformato" value=<? echo $ls_reporte; ?>>
<table width="780" height="295" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-nuevo">
        <td height="20" colspan="3">Comprobante de Traspaso </td>
      </tr>
      <tr>
        <td height="15">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="139" height="22" style="text-align:right">Procedencia</td>
        <td width="432" height="22">
          <input name="txtproccomp" type="text" id="txtproccomp" value="SPGTRA" readonly="true" style="text-align:center" >
          <input name="estapro" type="hidden" class="sin-borde" value="<?php print $li_estapro;?>">
		  <input name="tipomod" type="hidden" class="sin-borde" value="<?php print $ls_contipo;?>">
		  <input name="codtipomod" type="hidden" class="sin-borde" value="<?php print $ls_codtipo;?>"></td>
        <td width="148" height="22"><div align="left">Fecha
            <input name="txtfecha" type="text" id="txtfecha" style="text-align:center" onBlur="valFecha(document.form1.txtfecha)" value="<?php print $ls_fecha?>" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" size="15" maxlength="15" datepicker="true">
        </div></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">N&uacute;mero de la Modificaci&oacute;n</td>
        <td height="22"><input name="txtcomprobante" type="text" id="txtcomprobante" onBlur="javascript: valid_cmp(document.form1.txtcomprobante.value);" maxlength="15" style="text-align:center" value="<?php print $ls_comprobante ?>" <? print $ls_compread?>></td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Concepto de la Modificaci&oacute;n </td>
        <td height="22"><input name="txtdesccomp" type="text" id="txtdesccomp" size="70" value="<?php print $ls_descripcion?>"></td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Fuente de Financiamiento </td>
        <td height="22"><label>
          <span style="text-align:left">
          <?php
            //Llenar Combo Fuentes de Financiamiento.
            $rs_data = $in_classcmp->uf_load_fuentes_financiamiento($ls_empresa);
          ?>
        </span><span style="text-align:left">
        <select name="cmbfuefin" id="cmbfuefin" style="width:150px">
          <?php
		  while($row=$io_sql->fetch_row($rs_data))
		  {
			 $ls_codfuefin = $row["codfuefin"];
			 $ls_denfuefin = $row["denfuefin"];
			 if ($ls_codfuefin==$ls_fuefin)
			 {
				  print "<option value='$ls_codfuefin' selected>$ls_denfuefin</option>";
			 }
			 else
			 {
				  print "<option value='$ls_codfuefin'>$ls_denfuefin</option>";
			 }
		  } 
	     ?>
          </select>
        </span></label></td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr >
        <td height="22" style="text-align:right">Unidad Administradora </td>
        <td height="22"><label><span style="text-align:left">
          <input name="txtuniadm" type="text" id="txtuniadm" value="<? print $ls_coduniadm ?>"  style="text-align:center" >
          <a href="javascript:buscar_unidad();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
          <input name="txtdenuni" type="text" id="txtdenuni" class="sin-borde" value="<? print $ls_denuniadm ?>" readonly style="text-align:center"  maxlength="120" size="50">
          </select>
        </span></label></td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr >
        <td height="12" colspan="3"><div align="center"></div></td>
      </tr>
      <tr>
        <td height="20" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: uf_agregar_disminucion();"><img src="../shared/imagebank/tools/nuevo.gif" width="20" height="20" border="0">Partidas Cedentes </a> </td>
      </tr>
        <tr>
        <td height="17" colspan="3">
		<div align="center"><?php $io_grid->makegrid($totalDI,$title,$objectDI,770,'Partidas Cedentes',$grid1);?>
		  <input name="totDI" type="hidden" id="totDI" value="<?php print $totalDI?>">
		</div></td>
      </tr>
	  <br>
      <tr>
        <td height="18" colspan="3"><table width="777" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="607" style="text-align:right">Total Partidas Cedentes </td>
            <td width="170"><div align="center">
              <input name="txttotspg" type="text" id="txttotspg" value="<?php print number_format($ldec_totdi,2,",",".");?>" size="28" style="text-align:right">
            </div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="20" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: uf_agregar_aumento();"><img src="../shared/imagebank/tools/nuevo.gif" width="20" height="20" border="0">Partidas Receptoras </a></td>
      </tr>
      <tr>
        <td height="17" colspan="3">
          <div align="center"><?php $io_grid->makegrid($totalAU,$title,$objectAU,770,'Partidas Receptoras',$grid2);?><input name="totAU" type="hidden" id="totAU" value="<?php print $totalAU?>">
        </div>		</td>
      </tr>
      <tr>
        <td height="17" colspan="3"><table width="777" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="607" style="text-align:right">Total Partidas Receptoras</td>
            <td width="170"><div align="center">
                <input name="txttotspg2" type="text" id="txttotspg2" value="<?php print number_format($ldec_totau,2,",",".");?>" size="28" style="text-align:right">
            </div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="13" colspan="3">&nbsp;</td>
      </tr>
    </table>
  <div align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="totalpre" type="hidden" id="totalpre2" value="<?php print $totpre; ?>" >    
    <input name="totalcon" type="hidden" id="totalcon" value="<?php print $totcon; ?>" > 
    <input name="fila" type="hidden" id="fila" value="<?php print $li_fila;?>">
	<input name="existe" type="hidden" id="existe" value="<? print $ls_existe?>" >
  </div>
</div>
</form>
</body>
<script language="javascript">
//Funciones de operaciones sobre el comprobante
f = document.form1;
function ue_nuevo()
{
	f=document.form1;
	ldec_total1=f.txttotspg.value;
	ldec_total2=f.txttotspg2.value;
	ldec_total1=uf_convertir_monto(ldec_total1);
	ldec_total2=uf_convertir_monto(ldec_total2);
	if(parseFloat(ldec_total1)!=parseFloat(ldec_total2))
    {
	 alert("Comprobante descuadrado,el monto a Disminuir debe ser igual al monto a Aumentar");
	}
	else
	{
	 f.operacion.value="NUEVO";
	 f.action="sigesp_spg_p_traspaso.php";
	 f.submit();
	} 
}

function ue_guardar()
{
	f=document.form1;
	ldec_total1=f.txttotspg.value;
	ldec_total2=f.txttotspg2.value;
	ldec_total1=uf_convertir_monto(ldec_total1);
	ldec_total2=uf_convertir_monto(ldec_total2);
	if(f.estapro.value==0)
	{
		if(valida_campos())
		{
		  if(parseFloat(ldec_total1)!=parseFloat(ldec_total2))
		  {
			alert("Comprobante descuadrado,el monto a Disminuir debe ser igual al monto a Aumentar");
		  }
		  else
		  {
		   f.operacion.value="GUARDAR";
		   f.action="sigesp_spg_p_traspaso.php";
		   f.submit();
		  }	
		}
	}
	else
	{
		alert("Modificacion Presupuestaria ya fue Aprobada no puede ser modificada !!!");
	}
}
function ue_eliminar()
{
	f=document.form1;
	if(f.estapro.value==0)
	{
		if(valida_campos())
		{
			if(confirm("¿Está seguro que desea eliminar el comprobante?"))
			{
				f.operacion.value="ELIMINAR";
				f.action="sigesp_spg_p_traspaso.php";
				f.submit();
			}
		}	
	}
	else
	{
		alert("Modificacion Presupuestaria ya fue Aprobada no puede ser modificada !!!");
	}
}
function ue_buscar()
{
    f=document.form1;
	ldec_total1=f.txttotspg.value;
	ldec_total2=f.txttotspg2.value;
	ldec_total1=uf_convertir_monto(ldec_total1);
	ldec_total2=uf_convertir_monto(ldec_total2);
	if(parseFloat(ldec_total1)!=parseFloat(ldec_total2))
    {
	 alert("Comprobante descuadrado,el monto a Disminuir debe ser igual al monto a Aumentar");
	}
	else
	{
	window.open("sigesp_cat_traspaso.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=680,height=420,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_cerrar()
{
	f=document.form1;
	ldec_total1=f.txttotspg.value;
	ldec_total2=f.txttotspg2.value;
	ldec_total1=uf_convertir_monto(ldec_total1);
	ldec_total2=uf_convertir_monto(ldec_total2);
	if(parseFloat(ldec_total1)!=parseFloat(ldec_total2))
    {
	 alert("El Traspaso se encuentra descuadrado!!!");
	}
	else
	{
	 f.action="sigespwindow_blank.php";
	 f.submit();
	} 
}
function ue_close()
{
	close()
}

function valida_campos()
{
	f              = document.form1;
	ls_procede     = f.txtproccomp.value;
	ls_fecha       = f.txtfecha.value;
	ls_comprobante = f.txtcomprobante.value;
	ls_desccomp    = f.txtdesccomp.value;
    ls_codfuefin   = f.cmbfuefin.value;
    ls_coduniadm   = f.txtuniadm.value;
	lb_valido      = true;
	
	if(ls_procede=="")
	{
		alert("Introduzca la procedencia del comprobante !!!");
		lb_valido=false;
	}
	
	if((ls_fecha=="")||(ls_fecha=="01/01/1900")||(ls_fecha=="01-01-1900"))
	{
		alert("Introduzca una fecha valida");
		lb_valido=false;
	}
	
	if((ls_comprobante=="")||(ls_comprobante=="000000000000000"))
	{
		alert("Introduzca un numero de comprobante!!!");
		lb_valido=false;
	}
	
	if(ls_desccomp=="")
	{
		alert("Debe registrar la descripcion del comprobante !!!");
		lb_valido=false;
	}
	
	if ((ls_codfuefin=="--") || (ls_coduniadm=="-----"))
    {
		 alert("Debe registrar la Fuente de Financiamiento y la Unidad Administradora !!!");
		 lb_valido=false;
    }
	return 	lb_valido;
}

function valid_cmp(cmp)
{
	rellenar_cad(cmp,15,"cmp");
	f=document.form1;
	f.operacion.value="VALIDAFECHA";
	f.action="sigesp_spg_p_traspaso.php";
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
	else
	{
		document.form1.txtcomprobante.value=cadena;
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

  function esDigito(sChr){ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
   }

function EvaluateText(cadena, obj){ 
	
    opc = false; 
	
    if (cadena == "%d")  
      if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
      opc = true; 
    if (cadena == "%f"){ 
     if (event.keyCode > 47 && event.keyCode < 58) 
      opc = true; 
     if (obj.value.search("[.*]") == -1 && obj.value.length != 0) 
      if (event.keyCode == 46) 
       opc = true; 
    } 
	 if (cadena == "%s") // toma numero y letras
     if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)) 
      opc = true; 
	 if (cadena == "%c") // toma numero y punto
     if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==46))
      opc = true; 
 	 if (cadena == "%a") // toma numero y punto
     if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==45)|| (event.keyCode ==47))
      opc = true; 
    if(opc == false) 
     event.returnValue = false; 
   } 
   
   function  uf_agregar_disminucion()
   {
		f			   = document.form1;
		//ls_comprobante = f.txtcomprobante.value;
		ls_contar= f.tipomod.value;
		ls_comprobante= f.txtcomprobante.value;
		ls_tipomod= f.codtipomod.value;
		if ((ls_contar==1)&&(ls_comprobante==""))
		{
			alert("Debe seleccionar un Tipo de Modificación Presupuestaria para generar el número del comprobante");	
		}		
		ls_proccomp    = f.txtproccomp.value;
		ld_fecha       = f.txtfecha.value;
		ls_desccomp    = f.txtdesccomp.value;
		ls_codfuefin   = f.cmbfuefin.value;
		ls_coduniadm   = f.txtuniadm.value;
		if(f.estapro.value==0)
		{
			if(valida_campos())
			{
				ls_pagina = "sigesp_w_regdt_traspaso.php?procede="+ls_proccomp+"&comprobante="+ls_comprobante+"&fecha="+ld_fecha+"&descripcion="+ls_desccomp+"&tipo=-&provbene=----------&txtoperacion=DI&txtprocedencia="+ls_proccomp+"&codfuefin="+ls_codfuefin+"&coduniadm="+ls_coduniadm+"&tipomod="+ls_contar+"&codtipomod="+ls_tipomod;		
				window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=320,left=50,top=50,location=no,resizable=yes,dependent=yes");
			}
		}
		else
		{
			alert("Modificacion Presupuestaria ya fue Aprobada no puede ser modificada !!!");
		}	
   }
  
   function  uf_agregar_aumento()
   {
		f			   = document.form1;
		ls_contar= f.tipomod.value;
		ls_tipomod= f.codtipomod.value;
		ls_comprobante = f.txtcomprobante.value;
		ls_proccomp    = f.txtproccomp.value;
		ld_fecha       = f.txtfecha.value;
		ls_desccomp    = f.txtdesccomp.value;
		ls_codfuefin   = f.cmbfuefin.value;
		ls_coduniadm   = f.txtuniadm.value;
		if(f.estapro.value==0)
		{
			if(valida_campos())
			{
				ls_pagina = "sigesp_w_regdt_traspaso.php?procede="+ls_proccomp+"&comprobante="+ls_comprobante+"&fecha="+ld_fecha+"&descripcion="+ls_desccomp+"&tipo=-&provbene=----------&txtoperacion=AU&txtprocedencia="+ls_proccomp+"&codfuefin="+ls_codfuefin+"&coduniadm="+ls_coduniadm+"&tipomod="+ls_contar+"&codtipomod="+ls_tipomod;	
				window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=600,height=320,left=50,top=50,location=no,resizable=no,dependent=yes");
			}
		}
		else
		{
			alert("Modificacion Presupuestaria ya fue Aprobada no puede ser modificada !!!");
		}
   }
  
   function uf_delete_disminucion(row)
   {
		f=document.form1;
		if(f.estapro.value==0)
		{
		  	f.action="sigesp_spg_p_traspaso.php";
		  	f.operacion.value="DELETEDI";
		 	// grid_SPG.deleteRow(row);
		  	f.fila.value=row;
		  	f.submit();
		}
		else
		{
			alert("Modificacion Presupuestaria ya fue Aprobada no puede ser modificada");
		}
    }  
   function uf_delete_aumento(row)
   {
		f=document.form1;
		if(f.estapro.value==0)
		{
			f.action="sigesp_spg_p_traspaso.php";
			f.operacion.value="DELETEAU";
			// grid_SPG.deleteRow(row);
			f.fila.value=row;
			f.submit();
		}
		else
		{
			alert("Modificacion Presupuestaria ya fue Aprobada no puede ser modificada");
		}
    }  
    
	function uf_abrir_pdt_presupuestoaum(row)
	{
		f=document.form1;
		if(f.estapro.value==0)
		{
			comprobante=f.txtcomprobante.value;
			fecha=f.txtfecha.value;		
			cuentaspg= eval("f.txtcuentaAU"+row+".value");
			dencta= eval("f.txtdescripcionAU"+row+".value");
			codestpro= eval("f.txtcodprogAU"+row+".value");
			estcla= eval("f.txtestclaaum"+row+".value");
			procede= eval("f.txtprocedeAU"+row+".value");
			operacion= eval("f.txtoperacionAU"+row+".value");
			monto= eval("f.txtmontoAU"+row+".value");
			origen="sigesp_spg_p_traspaso.php";
			ls_pagina = "sigesp_spg_p_programacion_mensual.php?comprobante="+comprobante+"&fecha="+fecha+"&cuentaspg="+cuentaspg+"&dencta="+dencta+"&codestpro="+codestpro+"&estcla="+estcla+"&procede="+procede+"&operacion="+operacion+"&monto="+monto+"&origen="+origen;
			window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=500,left=50,top=50,location=no,resizable=yes,dependent=yes");
		}
		else
		{
			alert("Modificacion Presupuestaria ya fue Aprobada no puede ser modificada");
		}	
	}
	function uf_abrir_pdt_presupuestodis(row)
	{
		f=document.form1;
		if(f.estapro.value==0)
		{
			comprobante=f.txtcomprobante.value;
			fecha=f.txtfecha.value;		
			cuentaspg= eval("f.txtcuentaDI"+row+".value");
			dencta= eval("f.txtdescripcionDI"+row+".value");
			codestpro= eval("f.txtcodprogDI"+row+".value");
			estcla= eval("f.txtestcladis"+row+".value");
			procede= eval("f.txtprocedeDI"+row+".value");
			operacion= eval("f.txtoperacionDI"+row+".value");
			monto= eval("f.txtmontoDI"+row+".value");
			origen="sigesp_spg_p_traspaso.php";
			ls_pagina = "sigesp_spg_p_programacion_mensual.php?comprobante="+comprobante+"&fecha="+fecha+"&cuentaspg="+cuentaspg+"&dencta="+dencta+"&codestpro="+codestpro+"&estcla="+estcla+"&procede="+procede+"&operacion="+operacion+"&monto="+monto+"&origen="+origen;
			window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=500,left=50,top=50,location=no,resizable=yes,dependent=yes");
		}
		else
		{
			alert("Modificacion Presupuestaria ya fue Aprobada no puede ser modificada");
		}	
	}
//--------------------------------------------------------
//	Función que le da formato a la fecha
//--------------------------------------------------------
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
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

function ue_imprimir()
{
  f=document.form1;
  ls_comprobante = f.txtcomprobante.value; 
  ls_procede     = f.txtproccomp.value;
  ld_fecha       = f.txtfecha.value;
  ls_codfuefin   = f.cmbfuefin.value;
  ls_coduniadm   = f.txtuniadm.value;
  
  if ((ls_comprobante!='') && (ls_procede=='SPGTRA') && (ld_fecha!=""))
     {
       nombreformato=document.getElementById('nombreformato').value;
       ls_pagina = "reportes/"+nombreformato+"?comprobante="+ls_comprobante+"&procede="+ls_procede+"&fecha="+ld_fecha;
       window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
     }
  else
     {
	   alert("Deben estar completos los datos de la Modificación !!!");
	 }
}

function  uf_update_operacion(row,operacion)
   {
		f			   = document.form1;
		ls_comprobante = f.txtcomprobante.value;
		ls_proccomp    = f.txtproccomp.value;
		ld_fecha       = f.txtfecha.value;
		ls_desccomp    = f.txtdesccomp.value;
		ls_codfuefin   = f.cmbfuefin.value;
		ls_coduniadm   = f.txtuniadm.value;
		ls_cuenta=eval("f.txtcuenta"+operacion+row+".value");
		ls_programatico=eval("f.txtprogramatico"+operacion+row+".value");
		ls_documento=eval("f.txtdocumento"+operacion+row+".value");
		ls_descripcion=eval("f.txtdescripcion"+operacion+row+".value");
		ls_procede=eval("f.txtprocede"+operacion+row+".value");
		ls_operacion=eval("f.txtoperacion"+operacion+row+".value");
		if(f.estapro.value==0)
		{
			if(valida_campos())
			{
				ls_pagina = "sigesp_w_regdt_traspaso.php?procede="+ls_proccomp+"&comprobante="+ls_comprobante+"&fecha="+ld_fecha+"&tipo=-&provbene=----------&txtoperacion=DI&txtprocedencia="+ls_proccomp+"&codfuefin="+ls_codfuefin+"&coduniadm="+ls_coduniadm
				+"&cuenta="+ls_cuenta+"&programatico="+ls_programatico+"&documento="+ls_documento+"&descripcion="+ls_descripcion+"&operacion="+ls_operacion;		
				window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=320,left=50,top=50,location=no,resizable=yes,dependent=yes");
			}
		}
		else
		{
			alert("Modificacion Presupuestaria ya fue Aprobada no puede ser modificada !!!");
		}	
   }

function buscar_unidad()
{   
	window.open("sigesp_spg_cat_uniadm.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=320,left=50,top=50,location=no,resizable=yes,dependent=yes");
}
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>