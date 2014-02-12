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
<title>Comprobante de Credito o Ingreso Adicional</title>
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
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
		ldec_diferencia=f.txtdiferencia.value;
		ldec_diferencia=uf_convertir_monto(ldec_diferencia);
		ls_operacion=f.operacion.value;
		if((ls_operacion=="NUEVO")||(ls_operacion=="GUARDAR"))
		{
			if(ldec_diferencia!=0)
			{
				alert("Comprobante descuadrado Contablemente");
				f.operacion.value="CARGAR_DT";
				f.action="sigesp_spg_p_adicional.php";
				f.submit();
			}
		}
	}
</script>
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
//require_once("../shared/class_folder/class_sql.php");
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
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
	$ls_sistema="SPG";
	$ls_ventana="sigesp_spg_p_adicional.php";
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
$sig_inc=new sigesp_include();
$con=$sig_inc->uf_conectar();
$fun_db=new class_funciones_db($con);
$in_classcmp=new sigesp_spg_c_mod_presupuestarias();
$fun=new class_funciones();
$int_scg=new class_sigesp_int_scg();
$int_spg=new class_sigesp_int_spg();
$msg=new class_mensajes();
$io_grid=new grid_param();
$int_fec=new class_fecha();
$la_emp=$_SESSION["la_empresa"];
$li_estmodest  = $la_emp["estmodest"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
 	$ls_procede = $_POST["txtproccomp"];
	$ls_comprobante = $_POST["txtcomprobante"];
	$ls_fecha     = $_POST["txtfecha"];
	$ls_descripcion = $_POST["txtdesccomp"];
	$ls_procede	  = $_POST["txtproccomp"];
	$li_estapro   = $_POST["estapro"];
	$li_fila	  = 0;
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
	$ls_descripcion = $_POST["txtdesccomp"];
	$ls_codemp=$la_emp["codemp"];
	
	$ls_contipo= $_POST["tipomod"];
	$ls_codtipo= $_POST["codtipomod"];
	
	if ($ls_contipo==0)
	{
		$ls_compread="";
	}
	else
	{
		//$ls_compread="readonly";
		$ls_compread="";
	}

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
				 $ls_comprobante = $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPGCRA');
			  }
			}
			else
			{
				$ls_comprobante=$_POST["txtcomprobante"];
			}
	}
	$li_fila = 0;
	$prov_sel="";
	$bene_sel="";
	$ning_sel="selected";
	$totalspg=1;
	$totalscg=1;	
    $li_estmodest=$la_emp["estmodest"];
	if($li_estmodest==1)
	{
	   $li_size=32;
	   $li_size_estmodest = 32;
	   $li_maxlength=29;
	   $li_sizedoc=30;
	   $li_maxlengthdoc=30;
	   $li_sizedes=40;
	   $li_maxlengthdes=254;
	}
	else
	{
	   $li_size=40;
	   $li_size_estmodest = 14;
	   $li_maxlength=33;
	   $li_sizedoc=37;
	   $li_maxlengthdoc=15;
	   $li_sizedes=41;
	   $li_maxlengthdes=254;
	}
	$object[1][1]="<input type=text name=txtcuenta1       value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
	$object[1][2]="<input type=text name=txtdencta1 value='' class=sin-borde readonly style=text-align:center>"; 
	$object[1][3]="<input type=text name=txtprogramatico1 value='' class=sin-borde readonly style=text-align:center size=$li_size_estmodest  maxlength=$li_maxlength><input type=hidden name=txtestcla id=txtestcla value=''>"; 
	$object[1][4]="<input type=text name=txtdocumento1    value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
	$object[1][5]="<input type=text name=txtdescripcion1  value='' class=sin-borde readonly style=text-align:center>";
	$object[1][6]="<input type=text name=txtprocede1      value='' class=sin-borde readonly style=text-align:center size=7  maxlength=6>";
	$object[1][7]="<input type=text name=txtoperacion1    value='' class=sin-borde readonly style=text-align:center size=4  maxlength=3>";
	$object[1][8]="<input type=text name=txtmonto1        value='' class=sin-borde readonly style=text-align:right>";		
	$object[1][9] ="";
	
	$object2[1][1]="<input type=text name=txtcontable1  value='' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
	$object2[1][2]="<input type=text name=txtdocscg1    value='' class=sin-borde readonly style=text-align:center size=$li_sizedoc maxlength=$li_maxlengthdoc>";
	$object2[1][3]="<input type=text name=txtdesdoc1    value='' class=sin-borde readonly style=text-align:center size=$li_sizedes maxlength=$li_maxlengthdes>";
	$object2[1][4]="<input type=text name=txtprocdoc1   value='' class=sin-borde readonly style=text-align:center size=7  maxlength=6>";
	$object2[1][5]="<input type=text name=txtdebhab1    value='' class=sin-borde readonly style=text-align:center size=3  maxlength=1>"; 
	$object2[1][6]="<input type=text name=txtmontocont1 value='' class=sin-borde readonly style=text-align:center size=22 maxlength=22>";
	$object2[1][7] ="";
	$ldec_totalspg=0;
	$ldec_mondeb=0;
	$ldec_monhab=0;
	$ldec_diferencia=0;
	$ldec_tototracta=0;
	
}
  //Titulos de la tabla de Detalle Presupuestario.
  /*$title[1]="Cuenta" ;   $title[2]="Programatico";     $title[3]="Documento"   ;    $title[4]="Descripción";   
  $title[5]="Procede"; $title[6]="Operación";     $title[7]="Monto" ;  $title[8]="Edición";*/
  $title[1]="Cuenta" ;   
  $title[2]="Denominación";
  if($li_estmodest==1)
  {
   $title[3]="Imputación Presupuestaria";
  }
  else
  { 
   $title[3]="Programatico";     
  }
  $title[4]="Documento"   ;    
  $title[5]="Descripción";   
  $title[6]="Procede"; 
  $title[7]="Operación";     
  $title[8]="Monto" ;  
  $title[9]="Edición";
  $grid1="grid_spg";	
  
  $title2[1]="Cuenta";   $title2[2]="Documento"  ;     $title2[3]="Descripción";     $title2[4]="Procede"  ;   	
  $title2[5]="D/H"  ;   $title2[6]="Monto" ;   $title2[7]="Edición";
  $grid2="grid_scg";	
   //Titulos de la tabla de Detalle Contable
	 
if($ls_operacion=="NUEVO")//Acciones para un comprobante nuevo
{
	$ls_procede = "SPGCRA";
	$ls_existe="N";
	//$ls_comprobante = $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPGCRA');
	$ls_codtipo="";
	$ls_contipo= $in_classcmp->uf_buscar_tipos($la_emp["codemp"]); 
	if ($ls_contipo==0)
	{
		//$ls_comprobante = $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPGREC');
		$ls_comprobante = $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPGCRA');
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
	$ls_tipo      = "";
	$li_fila		 = 0;
	$ldec_mondeb=0;
	$ldec_diferencia=0;
	$ldec_monhab=0;	
	$ldec_totalspg=0;
	$ldec_totalscg=0;
	$ldec_totalspi=0;
	$li_estapro   = 0;
	$prov_sel="";
	$bene_sel="";
	$ning_sel="selected";
	$totalspg=1;
	$totalscg=1;	
    $li_estmodest=$la_emp["estmodest"];
	if($li_estmodest==1)
	{
	   $li_size=32;
	   $li_size_estmodest = 32;
	   $li_maxlength=29;
	   $li_sizedoc=30;
	   $li_maxlengthdoc=30;
	   $li_sizedes=40;
	   $li_maxlengthdes=254;
	}
	else
	{
	   $li_size=40;
	   $li_size_estmodest = 14;
	   $li_maxlength=33;
	   $li_sizedoc=37;
	   $li_maxlengthdoc=15;
	   $li_sizedes=41;
	   $li_maxlengthdes=254;
	}
	$object[1][1]="<input type=text name=txtcuenta1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
	$object[1][2]="<input type=text name=txtdencta1 value='' class=sin-borde readonly style=text-align:center>"; 
	$object[1][3]="<input type=text name=txtprogramatico1 value='' class=sin-borde readonly style=text-align:center size=$li_size_estmodest  maxlength=$li_maxlength><input type=hidden name=txtestcla id=txtestcla value=''>"; 
	$object[1][4]="<input type=text name=txtdocumento1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
	$object[1][5]="<input type=text name=txtdescripcion1 value='' class=sin-borde readonly style=text-align:center>";
	$object[1][6]="<input type=text name=txtprocede1 value='' class=sin-borde readonly style=text-align:center size=7  maxlength=6>";
	$object[1][7]="<input type=text name=txtoperacion1 value='' class=sin-borde readonly style=text-align:center size=4  maxlength=3>";
	$object[1][8]="<input type=text name=txtmonto1 value='' class=sin-borde readonly style=text-align:right>";		
	$object[1][9] ="";
	
	$object2[1][1]="<input type=text name=txtcontable1  value='' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
	$object2[1][2]="<input type=text name=txtdocscg1    value='' class=sin-borde readonly style=text-align:center size=$li_sizedoc maxlength=$li_maxlengthdoc>";
	$object2[1][3]="<input type=text name=txtdesdoc1    value='' class=sin-borde readonly style=text-align:center size=$li_sizedes maxlength=$li_maxlengthdes>";
	$object2[1][4]="<input type=text name=txtprocdoc1   value='' class=sin-borde readonly style=text-align:center size=7  maxlength=6>";
	$object2[1][5]="<input type=text name=txtdebhab1    value='' class=sin-borde readonly style=text-align:center size=3  maxlength=1>"; 
	$object2[1][6]="<input type=text name=txtmontocont1 value='' class=sin-borde readonly style=text-align:center size=22 maxlength=22>";
	$object2[1][7] ="";
	
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
	$ls_codban     = "---";
	$ls_ctaban     = "-------------------------";
	$ls_fuefin      = $_POST["cmbfuefin"];
	$ls_coduniadm      = $_POST["txtuniadm"];
	$ls_denuniadm      = $_POST["txtdenuni"];
	$ls_codemp=$la_emp["codemp"];
	
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
	$lb_valido=$in_classcmp->uf_guardar_automatico($ls_comprobante,$ld_fecha,$ls_procedencia,$ls_descripcion,
	                                               $int_int->is_cod_prov,$int_int->is_ced_ben,$ls_tipo,2,0,
												   $ls_fuefin,$ls_coduniadm);
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
	$ls_existe="N";
	$ls_coduniadm      = $_POST["txtuniadm"];
	$ls_denuniadm      = $_POST["txtdenuni"];
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
		$msg->message("Comprobante Eliminado Satisfactoriamente");
		$ls_comprobante="";
		$ld_fecha="";
		$ls_descripcion="";
		$li_estapro   = 0;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_desc_event="Elimino el comprobante presupuestario ".$ls_comprobante." de fecha ".$ld_fecha." y procedencia ".$ls_procedencia;
		$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////
		$in_classcmp->io_sql->commit();
		$ls_fuefin="";
		$ls_uniadm="";
	}
	else
	{
		$in_classcmp->io_sql->rollback();
		$msg->message("Error".$in_classcmp->is_msg_error);
	}

	uf_cargar_dt($la_emp["codemp"],$ls_procedencia,$ls_comprobantes,$ld_fechas);
}

if($ls_operacion=="DELETESPG")		
{
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha      =$_POST["txtfecha"];
	$ls_proccomp   =$_POST["txtproccomp"];
	$ls_desccomp   =$_POST["txtdesccomp"];
	$ls_provbene   ="----------";	
	$ls_tipo	   ="-";
	$li_fila	   =$_POST["fila"];
	$ls_prov=$ls_provbene;
	$ls_bene=$ls_provbene;
    $li_estmodest  = $la_emp["estmodest"];
	
	$ls_contipo= $_POST["tipomod"];
	$ls_codtipo= $_POST["codtipomod"]; 
	
	$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	        $ls_incio1=0;
	        $ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	        $ls_incio2=$ls_loncodestpro1;
	        $ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	        $ls_incio3=$ls_loncodestpro1+$ls_loncodestpro2;//25-$ls_loncodestpro3;
	        $ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	        $ls_incio4=$ls_incio3+$ls_loncodestpro3;//25-$ls_loncodestpro4;
	        $ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
	        $ls_incio5=$ls_incio4+$ls_loncodestpro4;//25-$ls_loncodestpro5;
	
	if($li_estmodest==2)
	{
		$estprog[0]=substr($_POST["txtprogramatico".$li_fila],$ls_incio1,$ls_loncodestpro1);
		$estprog[1]=substr($_POST["txtprogramatico".$li_fila],$ls_incio2,$ls_loncodestpro2);
		$estprog[2]=substr($_POST["txtprogramatico".$li_fila],$ls_incio3,$ls_loncodestpro3);
		$estprog[3]=substr($_POST["txtprogramatico".$li_fila],$ls_incio4,$ls_loncodestpro4);
		$estprog[4]=substr($_POST["txtprogramatico".$li_fila],$ls_incio5,$ls_loncodestpro5);
	}
	else
	{
		$estprog[0]=substr($_POST["txtprogramatico".$li_fila],$ls_incio1,$ls_loncodestpro1);
		$estprog[1]=substr($_POST["txtprogramatico".$li_fila],$ls_incio2,$ls_loncodestpro2);
		$estprog[2]=substr($_POST["txtprogramatico".$li_fila],$ls_incio3,$ls_loncodestpro3);
		$estprog[3]=$fun->uf_cerosizquierda(0,25);
		$estprog[4]=$fun->uf_cerosizquierda(0,25);
	}
	$estprog[0]  = $fun->uf_cerosizquierda($estprog[0],25);
	$estprog[1]  = $fun->uf_cerosizquierda($estprog[1],25);
	$estprog[2]  = $fun->uf_cerosizquierda($estprog[2],25);
	$estprog[3]  = $fun->uf_cerosizquierda($estprog[3],25);
	$estprog[4]  = $fun->uf_cerosizquierda($estprog[4],25);
	$estprog[5]  = $_POST["txtestcla".$li_fila];
	
	$ls_cuenta=$_POST["txtcuenta".$li_fila];
	$ls_dencuenta=$_POST["txtdencta".$li_fila];	
	$ls_procede_doc=$_POST["txtprocede".$li_fila];
	$ls_descripcion=$_POST["txtdescripcion".$li_fila];
	$ls_documento=$_POST["txtdocumento".$li_fila];
	$ls_operacion=$_POST["txtoperacion".$li_fila];
	$ldec_monto_anterior=$_POST["txtmonto".$li_fila];
	$ldec_monto_actual=0;
	$li_tipo_comp=1;

	$ls_mensaje=$int_spg->uf_operacion_codigo_mensaje($ls_operacion);
	$in_classcmp->is_codemp=$la_emp["codemp"];
	$in_classcmp->is_fecha=$fun->uf_convertirdatetobd($ld_fecha);
	$in_classcmp->is_procedencia=$ls_proccomp;
	$in_classcmp->is_comprobante=$ls_comprobante;
	$in_classcmp->is_tipo=$ls_tipo;
	$in_classcmp->is_cod_prov=$ls_prov;
	$in_classcmp->is_ced_ben=$ls_bene;
	$int_spg->ib_AutoConta=true;
	if ($ls_tipo=="B")  
	{ $ls_fuente = $ls_bene; }	
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
	  return false;
	}
	$lb_valido=$in_classcmp->uf_int_spg_delete_movimiento($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha,$ls_tipo,$ls_fuente,$ls_prov,$ls_bene,
													     $estprog,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_descripcion,$ls_mensaje,$li_tipo_comp,
													     $ldec_monto_anterior,$ldec_monto_actual,$ls_sc_cuenta);
	
	if($lb_valido)
	{
		$msg->message("Movimiento Eliminado Satisfactoriamente");
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_desc_event="Elimino el movimiento presupuestario ".$ls_documento." con operacion".$ls_operacion." por un monto de ".$ldec_monto_anterior." para la cuenta ".$ls_cuenta." correspondiente a la estructura programatica ".$estprog[0]."-".$estprog[1]."-".$estprog[2]."-".$estprog[3]."-".$estprog[4]."; para el comprobante ".$ls_comprobante." de fecha ".$ld_fecha;
		$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////
		$in_classcmp->io_sql->commit();
	}
	else
	{
		$msg->message(" Movimiento no fue  Eliminado ");
		$in_classcmp->io_sql->rollback();
	}
	uf_cargar_dt($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);

}

if($ls_operacion=="DELETESCG")		
{
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha      =$_POST["txtfecha"];
	$ls_proccomp   =$_POST["txtproccomp"];
	$ls_desccomp   =$_POST["txtdesccomp"];
	$li_fila	   =$_POST["fila"];
	$ls_prov="----------";
	$ls_bene="----------";
	$ls_tipo="-";
	$ls_cuenta=$_POST["txtcontable".$li_fila];	
	$ls_procdoc=$_POST["txtprocdoc".$li_fila];
	$ls_desdoc=$_POST["txtdesdoc".$li_fila];
	$ls_docscg=$_POST["txtdocscg".$li_fila];
	$ls_debhab=$_POST["txtdebhab".$li_fila];
	$ldec_monto_anterior=$_POST["txtmontocont".$li_fila];
	$ldec_monto_actual=0;
	$li_tipo_comp=1;
	$ls_coduniadm      = $_POST["txtuniadm"];
	$ls_denuniadm      = $_POST["txtdenuni"];
	
	$ls_contipo= $_POST["tipomod"];
	$ls_codtipo= $_POST["codtipomod"]; 
	
	
	//$ls_mensaje=$int_scg->uf_operacion_codigo_mensaje($ls_operacion);
	$in_classcmp->is_codemp=$la_emp["codemp"];
	$in_classcmp->is_fecha=$fun->uf_convertirdatetobd($ld_fecha);
	$in_classcmp->is_procedencia=$ls_proccomp;
	$in_classcmp->is_comprobante=$ls_comprobante;
	$in_classcmp->is_tipo=$ls_tipo;
	$in_classcmp->is_cod_prov=$ls_prov;
	$in_classcmp->is_ced_ben=$ls_bene;
	$in_classcmp->ib_AutoConta=true;
	
	$lb_valido=$in_classcmp->uf_scg_delete_movimiento($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$in_classcmp->is_fecha, $ls_cuenta, $ls_procdoc, $ls_docscg,$ls_debhab );
	//uf_scg_procesar_delete_movimiento($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$int_scg->is_fecha, $ls_cuenta, $ls_procdoc, $ls_docscg,$ls_debhab, $ldec_monto_anterior);
	if($lb_valido)
	{
		$msg->message("Movimiento Eliminado Satisfactoriamente");
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_desc_event="Elimino el movimiento contable ".$ls_docscg." con operacion ".$ls_debhab." por un monto de ".$ldec_monto_anterior." para la cuenta ".$ls_cuenta."; para el comprobante ".$ls_comprobante." de fecha ".$ld_fecha;
		$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////
		$in_classcmp->io_sql->commit();
		$ls_fuefin="";
		$ls_uniadm="";
	}
	else
	{
		$msg->message(" Movimiento no fue  Eliminado ");
		$in_classcmp->io_sql->rollback();
	}
	uf_cargar_dt($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);

}

function uf_cargar_dt($ls_codemp,$ls_proccomp,$ls_comprobante,$ld_fecha)
{
	global $in_classcmp;
	global $la_emp;
	global $totalspg;
	global $totalscg;
	global $object;
	global $object2;
	global $ldec_mondeb;
	global $ldec_monhab;
	global $ldec_diferencia;
	global $ldec_totalspg;
	global $ldec_totalspi;
	$ldec_totalspg=0;
	$ldec_totalspi=0;
	$ldec_mondeb=0;
	$ldec_monhab=0;
	$ldec_diferencia=0;
	$i=0;
	$rs_dtcmp=$in_classcmp->uf_cargar_dt_comprobante($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);
	$li_numrows=$in_classcmp->io_sql->num_rows($rs_dtcmp);
	$totalspg=1;
	$totalscg=1;
	if($li_numrows>0)
	{
		$totalspg=$li_numrows;
		while($row=$in_classcmp->io_sql->fetch_row($rs_dtcmp))
		{
			$i=$i+1;
			$ls_cuenta=$row["spg_cuenta"];
			$ls_dencuenta=$row["dencuenta"];
            $li_estmodest  = $_SESSION["la_empresa"]["estmodest"];
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
			if($li_estmodest==1)
			{
			   $li_size=32;
			   $li_size_estmodest=32; 
			   $li_maxlength=29;
			}
			else
			{
			   $li_size=40;
			   $li_size_estmodest=14; 
			   $li_maxlength=33;
			}
			$object[$i][1]="<input type=text name=txtcuenta".$i."       value='".$ls_cuenta."'       class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$object[$i][2]="<input type=text name=txtdencta".$i." value='".$ls_dencuenta."' class=sin-borde readonly style=text-align:center >"; 
			$object[$i][3]="<input type=text name=txtprogramatico".$i." value='".$ls_programatico."' class=sin-borde readonly style=text-align:center size=$li_size_estmodest  maxlength=$li_maxlength>".
						   "<input type=hidden name=txtestcla".$i." id=txtestcla".$i." value='".$ls_estcla."'>".
						   "<input type=hidden name=txtcodprog".$i." id=txtcodprog".$i." value='".$ls_codprog."'>"; 
			$object[$i][4]="<input type=text name=txtdocumento".$i."    value='".$ls_documento."'    class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$object[$i][5]="<input type=text name=txtdescripcion".$i."  value='".$ls_descripcion."' title='".$ls_descripcion."'  class=sin-borde readonly style=text-align:center>";
			$object[$i][6]="<input type=text name=txtprocede".$i."      value='".$ls_procede."'      class=sin-borde readonly style=text-align:center size=7  maxlength=6>";
			$object[$i][7]="<input type=text name=txtoperacion".$i."    value='".$ls_operacion."'    class=sin-borde readonly style=text-align:center size=4  maxlength=3>";
			$object[$i][8]="<input type=text name=txtmonto".$i."        value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right>";		
			$object[$i][9]="<a href=javascript:uf_delete_dt_presupuesto(".($i).");><img src=../shared/imagebank/tools15/eliminar.gif width=15 height=15 border=0 alt=Eliminar Detalle Presupuesto></a>  <a href=javascript:uf_abrir_pdt_presupuesto(".($i).");><img src=../shared/imagebank/mas.gif width=13 height=18 border=0 alt=Abrir Detalle></a>";
			$ldec_totalspg = $ldec_totalspg + $ldec_monto;
		}
		$in_classcmp->io_sql->free_result($rs_dtcmp);
	}
	else
	{
		$li_estmodest=$la_emp["estmodest"];
		if($li_estmodest==1)
		{
		   $li_size=32;
		   $li_size_estmodest = 32;
		   $li_maxlength=29;
		}
		else
		{
		   $li_size=40;
		   $li_size_estmodest = 14;
		   $li_maxlength=33;
		}
		$object[1][1]="<input type=text name=txtcuenta1       value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$object[1][2]="<input type=text name=txtdencta1 value='' class=sin-borde readonly style=text-align:center>"; 
		$object[1][3]="<input type=text name=txtprogramatico1 value='' class=sin-borde readonly style=text-align:center size=$li_size_estmodest  maxlength=$li_maxlength><input type=hidden name=txtestcla id=txtestcla value=''>"; 
		$object[1][4]="<input type=text name=txtdocumento1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$object[1][5]="<input type=text name=txtdescripcion1 value='' class=sin-borde readonly style=text-align:center>";
		$object[1][6]="<input type=text name=txtprocede1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
		$object[1][7]="<input type=text name=txtoperacion1 value='' class=sin-borde readonly style=text-align:center size=4 maxlength=3>";
		$object[1][8]="<input type=text name=txtmonto1 value='' class=sin-borde readonly style=text-align:right>";		
		$object[1][9] ="";
	}
	$i=0;
	$rs_dtscg=$in_classcmp->uf_cargar_dt_contable_cmp($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);
	$li_numrows=$in_classcmp->io_sql->num_rows($rs_dtscg);
	if($li_numrows>0)
	{
	    $totalscg=$li_numrows;
		while($row=$in_classcmp->io_sql->fetch_row($rs_dtscg))
		{
			$i=$i+1;
			$ls_sc_cuenta=$row["sc_cuenta"];
			$ls_documento=$row["documento"];
			$ls_desdoc=$row["descripcion"];
			$ls_procdoc=$row["procede_doc"];
			$ls_debhab=$row["debhab"];
			$ldec_monto=$row["monto"];
			if(trim($ls_debhab)=="D")	
			{
				$ldec_mondeb=$ldec_mondeb+$ldec_monto;
			}
			else
			{
				$ldec_monhab=$ldec_monhab+$ldec_monto;
			}
			$li_estmodest=$la_emp["estmodest"];
			if($li_estmodest==1)
			{
			   $li_sizedoc=30;
			   $li_maxlengthdoc=30;
			   $li_sizedes=40;
			   $li_maxlengthdes=254;

			}
			else
			{
			   $li_sizedoc=37;
			   $li_maxlengthdoc=15;
			   $li_sizedes=41;
			   $li_maxlengthdes=254;
			}
			$object2[$i][1] = "<input type=text name=txtcontable".$i." value='".$ls_sc_cuenta."' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
			$object2[$i][2] = "<input type=text name=txtdocscg".$i." value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=$li_sizedoc maxlength=$li_maxlengthdoc>";
			$object2[$i][3] = "<input type=text name=txtdesdoc".$i." value='".$ls_desdoc."' title='".$ls_desdoc."' class=sin-borde readonly style=text-align:center size=$li_sizedes maxlength=$li_maxlengthdes>";
			$object2[$i][4] = "<input type=text name=txtprocdoc".$i." value='".$ls_procdoc."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$object2[$i][5] = "<input type=text name=txtdebhab".$i." value='".$ls_debhab."' class=sin-borde readonly style=text-align:center size=3 maxlength=1>"; 
			$object2[$i][6] = "<input type=text name=txtmontocont".$i." value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right size=22 maxlength=28>";
			$object2[$i][7] = "<a href=javascript:uf_delete_dt_contable(".($i).");><img src=../shared/imagebank/tools15/eliminar.gif width=15 height=15 border=0 alt=Eliminar Detalle Contable></a>";
		}
		$ldec_diferencia=$ldec_mondeb-$ldec_monhab;
		$in_classcmp->io_sql->free_result($rs_dtscg);
	}
	else
	{
			$li_estmodest=$la_emp["estmodest"];
			if($li_estmodest==1)
			{
			   $li_sizedoc=30;
			   $li_maxlengthdoc=30;
			   $li_sizedes=40;
			   $li_maxlengthdes=254;
			}
			else
			{
			   $li_sizedoc=37;
			   $li_maxlengthdoc=15;
			   $li_sizedes=41;
			   $li_maxlengthdes=254;
			}
			$object2[1][1]="<input type=text name=txtcontable1  value='' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
			$object2[1][2]="<input type=text name=txtdocscg1    value='' class=sin-borde readonly style=text-align:center size=$li_sizedoc maxlength=$li_maxlengthdoc>";
			$object2[1][3]="<input type=text name=txtdesdoc1    value='' class=sin-borde readonly style=text-align:center size=$li_sizedes maxlength=$li_maxlengthdes>";
			$object2[1][4]="<input type=text name=txtprocdoc1   value='' class=sin-borde readonly style=text-align:center size=7  maxlength=6>";
			$object2[1][5]="<input type=text name=txtdebhab1    value='' class=sin-borde readonly style=text-align:center size=3  maxlength=1>"; 
			$object2[1][6]="<input type=text name=txtmontocont1 value='' class=sin-borde readonly style=text-align:center size=22 maxlength=22>";
			$object2[1][7] ="";
	}
}
?> 
<form name="form1" method="post" action=""><div >
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
<table width="780" height="397" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-nuevo">
        <td height="20" colspan="3">Comprobante de Cr&eacute;dito &oacute; Ingreso Adicional </td>
      </tr>
      <tr>
        <td height="15">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="139" height="22">
        <p align="right"> Procedencia</p></td>
        <td width="432">
          <input name="txtproccomp" type="text" id="txtproccomp" value="SPGCRA" readonly="true" style="text-align:center" >
          <input name="estapro" type="hidden" class="sin-borde" value="<?php print $li_estapro;?>">
		  <input name="tipomod" type="hidden" class="sin-borde" value="<?php print $ls_contipo;?>">
		  <input name="codtipomod" type="hidden" class="sin-borde" value="<?php print $ls_codtipo;?>"></td>
        <td width="148"><div align="left">Fecha
            <input name="txtfecha" type="text" id="txtfecha" style="text-align:center" onBlur="valFecha(document.form1.txtfecha)" value="<?php print $ls_fecha?>" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" size="15" maxlength="15" datepicker="true">
        </div></td>
      </tr>
      <tr>
        <td height="22">
        <p align="right">N&uacute;mero de la Modificaci&oacute;n</p></td>
        <td><input name="txtcomprobante" type="text" id="txtcomprobante" onBlur="javascript: valid_cmp(document.form1.txtcomprobante.value);" maxlength="15" style="text-align:center" value="<?php print $ls_comprobante ?>" <? print $ls_compread?>></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="22">
        <p align="right"><span style="text-align:right">Concepto de la Modificaci&oacute;n </span></p></td>
        <td><input name="txtdesccomp" type="text" id="txtdesccomp" size="70" value="<?php print $ls_descripcion?>"></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Fuente de Financiamiento </td>
        <td height="22"><?php
            //Llenar Combo Fuentes de Financiamiento.
            $rs_data = $in_classcmp->uf_load_fuentes_financiamiento($ls_empresa);
          ?>
            <span style="text-align:left">
            <select name="cmbfuefin" id="cmbfuefin" style="width:150px">
              <?php
		  while($row=$in_classcmp->io_sql->fetch_row($rs_data))
		  {
			 $ls_codfuefin = $row["codfuefin"];//print "ejele";
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
          </span></td>
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
      <tr >
        <td height="20" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: uf_agregar_dtspg();"><img src="../shared/imagebank/tools/nuevo.gif" width="20" height="20" border="0">Agregar  detalles de Gasto</a> </td>
      </tr>
        <tr>
        <td height="17" colspan="3">
		<div align="center"><?php $io_grid->makegrid($totalspg,$title,$object,820,'Detalles de Gasto',$grid1);?>
		  <input name="totspg" type="hidden" id="totspg" value="<?php print $totalspg?>">
		</div></td>
      </tr>
	  <br>
      <tr>
        <td height="20" colspan="3"><table width="805" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="607"><div align="right">Total Cuentas de Rectificaciones </div></td>
            <td width="170"><div align="center">
                <input name="txttotspg" type="text" id="txttotspg" value="<?php print number_format($ldec_totalspg,2,",",".");?>" size="28" style="text-align:right" readonly="true">
            </div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="20" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: uf_agregar_dtscg();"><img src="../shared/imagebank/tools/nuevo.gif" width="20" height="20" border="0">Agregar detalle Contable</a></td>
      </tr>
      <tr>
        <td height="17" colspan="3"><div align="center">
          <?php $io_grid->makegrid($totalscg,$title2,$object2,820,'Otras Cuentas',$grid2);?>
          <input name="totscg" type="hidden" id="totscg" value="<?php print $totalscg?>">
        </div></td>
      </tr>
      <tr>
        <td height="17" colspan="3">
          <div align="center">
            <table width="805" border="0" align="center" cellpadding="0" cellspacing="0" class="celdas-blancas">
              <tr>
                <td height="23">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><div align="right">Debe</div></td>
                <td><div align="center">
                    <input name="txtdebe" type="text" id="txtdebe" style="text-align:right" value="<?php print number_format($ldec_mondeb,2,",",".");?>" size="28" readonly="true">
                </div></td>
              </tr>
              <tr>
                <td height="21">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><div align="right">Haber</div></td>
                <td><div align="center">
                    <input name="txthaber" type="text" id="txthaber" style="text-align:right" value="<?php print number_format($ldec_monhab,2,",",".");?>" size="28" readonly="true">
                </div></td>
              </tr>
              <tr>
                <td width="77" height="21">&nbsp;</td>
                <td width="97">&nbsp;</td>
                <td width="88">&nbsp;</td>
                <td width="216"><div align="right"> </div></td>
                <td width="130"><div align="center"></div>
                    <div align="right">Diferencia</div></td>
                <td width="170"><div align="center">
                    <p>
                      <input name="txtdiferencia" type="text" id="txtdiferencia" style="text-align:right" value="<?php print number_format($ldec_diferencia,2,",",".") ;?>" size="28" readonly = "true">
                    </p>
                </div></td>
              </tr>
            </table>
          </div>		</td>
      </tr>
      <tr>
        <td height="13" colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td height="17" colspan="3">&nbsp;<a href="javascript: uf_agregar_disminucion();"><img src="../shared/imagebank/tools20/nuevo.gif" width="20" height="20" border="0">Agregar detalle Ingresos</a></td>
      </tr>
      <tr>
        <td height="17" colspan="3"><div align="center">
          <?php //$io_grid->makegrid($totalDI,$title,$objectDI,770,'Cuentas de Rectificaciones Presupuestarias',$grid1);?>
          <input name="totDI22" type="hidden" id="totDI22" value="<?php print $totalDI?>">
        </div></td>
      </tr>
      <tr>
        <td height="17" colspan="3"><table width="805" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="609"><div align="right" >Total Otras Cuentas </div></td>
            <td width="169"><div align="center">
                <input name="txttotspi" type="text" id="txttotspi" value="<?php print number_format($ldec_totalspi,2,",",".");?>" size="28" style="text-align:right" readonly="true">
            </div></td>
          </tr>
        </table></td>
      </tr>
    </table>
  <input name="operacion" type="hidden" id="operacion">
  <input name="fila" type="hidden" id="fila" value="<?php print $li_fila;?>">
  <input name="existe" type="hidden" id="existe" value="<? print $ls_existe?>" >
</div>
</form>
</body>
<script language="javascript">
//Funciones de operaciones para el detalle del comprobante

function ue_imprimir()
{
  f=document.form1;
  ls_comprobante = f.txtcomprobante.value; 
  ls_procede     = f.txtproccomp.value;
  ld_fecha       = f.txtfecha.value;
  ls_codfuefin   = f.cmbfuefin.value;
  ls_coduniadm   = f.txtuniadm.value;
  ls_denfuefin   = f.cmbfuefin.options[f.cmbfuefin.selectedIndex].text;

  if ((ls_comprobante!='') && (ls_procede=='SPGCRA') && (ld_fecha!=""))
     {
       ls_pagina = "reportes/sigesp_spg_rpp_sol_mod_pre_forma0301.php?comprobante="+ls_comprobante+"&procede="+ls_procede+"&fecha="+ld_fecha+"&fuefin="+ls_denfuefin;
       window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
     }
  else
     {
	   alert("Deben estar completos los datos de la Modificación !!!");
	 }
}


function uf_agregar_disminucion()
{
  alert(" Disculpe el Modulo de Ingreso esta en Desarrollo....");
}
//Funciones de operaciones sobre el comprobante
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="sigesp_spg_p_adicional.php";
	f.submit();
}
function ue_guardar()
{
	f=document.form1;
	ldec_diferencia=f.txtdiferencia.value;
	ldec_diferencia=uf_convertir_monto(ldec_diferencia);
	if(f.estapro.value==0)
	{
		if(valida_campos())
		{
			if(ldec_diferencia!=0)
			{
				alert("Comprobante descuadrado");
			}
			else
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_spg_p_adicional.php";
				f.submit();
			}
		}
	}
	else
	{
		alert("Modificacion Presupuestaria ya fue Aprobada no puede ser modificada");
	}
}
function ue_eliminar()
{
	f=document.form1;
	if(f.estapro.value==0)
	{
		if(valida_campos())
		{
			if(confirm("Seguro desea eliminar el comprobante"))
			{
			f=document.form1;
			f.operacion.value="ELIMINAR";
			f.action="sigesp_spg_p_adicional.php";
			f.submit();
			}
		}
	}		
	else
	{
		alert("Modificacion Presupuestaria ya fue Aprobada no puede ser modificada");
	}
}
function ue_buscar()
{
	f=document.form1;
	ldec_diferencia=f.txtdiferencia.value;
	ldec_diferencia=uf_convertir_monto(ldec_diferencia);
	if(ldec_diferencia!=0)
	{
		alert("Comprobante descuadrado");
	}
	else
	{
		window.open("sigesp_cat_adicional.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_cerrar()
{
	f=document.form1;
	ldec_diferencia=f.txtdiferencia.value;
	ldec_diferencia=uf_convertir_monto(ldec_diferencia);
	if(ldec_diferencia==0)
	{
		f.action="sigespwindow_blank.php";
		f.submit();
	}
	else
	{
		alert("Comprobante descuadrado contablemente");
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
		alert("Introduzca la procedencia del comprobante");
		lb_valido=false;
	}
	
	if((ls_fecha=="")||(ls_fecha=="01/01/1900")||(ls_fecha=="01-01-1900"))
	{
		alert("Introduzca una fecha valida");
		lb_valido=false;
	}
	
	if((ls_comprobante=="")||(ls_comprobante=="000000000000000"))
	{
		alert("Introduzca un numero de comprobante");
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
f.action="sigesp_spg_p_adicional.php";
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
     if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode == 32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode == 46)) 
      opc = true; 
	 if (cadena == "%c") // toma numero y punto
     if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode == 46))
      opc = true; 
 	 if (cadena == "%a") // toma numero y punto
     if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode == 45)|| (event.keyCode == 47))
      opc = true; 
    if(opc == false) 
     event.returnValue = false; 
   } 
   
   function uf_agregar_dtspg()
   {   
	f=document.form1;
	if(f.estapro.value==0)
	{
		//ls_comprobante= f.txtcomprobante.value;
		ls_contar= f.tipomod.value;
		ls_comprobante= f.txtcomprobante.value;
		ls_tipomod= f.codtipomod.value;
		if ((ls_contar==1)&&(ls_comprobante==""))
		{
			alert("Debe seleccionar un Tipo de Modificación Presupuestaria para generar el número del comprobante");	
		}		
		ls_proccomp   = f.txtproccomp.value;
		ld_fecha      = f.txtfecha.value;
		ls_desccomp   = f.txtdesccomp.value;
		ls_codfuefin   = f.cmbfuefin.value;
		ls_coduniadm   = f.txtuniadm.value;
		if(valida_campos())
		{
			ls_pagina = "sigesp_w_regdt_adicionalspg.php?procede="+ls_proccomp+"&comprobante="+ls_comprobante+"&fecha="+ld_fecha+"&descripcion="+ls_desccomp+"&tipo=-&provbene=----------&txtoperacion=AU&txtprocedencia="+ls_proccomp+"&codfuefin="+ls_codfuefin+"&coduniadm="+ls_coduniadm+"&tipomod="+ls_contar+"&codtipomod="+ls_tipomod;
			window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=320,left=50,top=50,location=no,resizable=yes,dependent=yes");
		}	
	}
	else
	{
		alert("Modificacion Presupuestaria ya fue Aprobada no puede ser modificada");
	}	
   }
  
   function uf_agregar_dtscg()
   {   
	f=document.form1;
	if(f.estapro.value==0)
	{	
		ls_comprobante= f.txtcomprobante.value;
		ls_proccomp   = f.txtproccomp.value;
		ld_fecha      = f.txtfecha.value;
		ls_desccomp   = f.txtdesccomp.value;
		ls_codfuefin   = f.cmbfuefin.value;
		ls_coduniadm   = f.txtuniadm.value;
		if(valida_campos())
		{
			ls_pagina = "sigesp_w_regdt_adicionalscg.php?procede="+ls_proccomp+"&comprobante="+ls_comprobante+"&fecha="+ld_fecha+"&descripcion="+ls_desccomp+"&tipo=-&provbene=----------&txtprocedencia="+ls_proccomp+"&codfuefin="+ls_codfuefin+"&coduniadm="+ls_coduniadm;
			window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=590,height=220,left=50,top=50,location=no,resizable=yes,dependent=yes");
		}
	}
	else
	{
		alert("Modificacion Presupuestaria ya fue Aprobada no puede ser modificada");
	}		
   }
  
   function uf_delete_dt_presupuesto(row)
   {
	f=document.form1;
	if(f.estapro.value==0)
	{
	  f.action="sigesp_spg_p_adicional.php";
      f.operacion.value="DELETESPG";
	 // grid_SPG.deleteRow(row);
	  f.fila.value=row;
      f.submit();
	}
	else
	{
		alert("Modificacion Presupuestaria ya fue Aprobada no puede ser modificada");
	}	
    }  
   function uf_delete_dt_contable(row)
   {
	f=document.form1;
	if(f.estapro.value==0)
	{
		f.action="sigesp_spg_p_adicional.php";
		f.operacion.value="DELETESCG";
		// grid_SPG.deleteRow(row);
		f.fila.value=row;
		f.submit();
	}
	else
	{
		alert("Modificacion Presupuestaria ya fue Aprobada no puede ser modificada");
	}	
   }  
   
	function uf_abrir_pdt_presupuesto(row)
	{
		f=document.form1;
		if(f.estapro.value==0)
		{
			comprobante=f.txtcomprobante.value;
			fecha=f.txtfecha.value;		
			cuentaspg= eval("f.txtcuenta"+row+".value");
			dencta= eval("f.txtdencta"+row+".value");
			codestpro= eval("f.txtcodprog"+row+".value");
			estcla= eval("f.txtestcla"+row+".value");
			procede= eval("f.txtprocede"+row+".value");
			operacion= eval("f.txtoperacion"+row+".value");
			monto= eval("f.txtmonto"+row+".value");
			origen="sigesp_spg_p_adicional.php";
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
function buscar_unidad()
{   
	window.open("sigesp_spg_cat_uniadm.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=320,left=50,top=50,location=no,resizable=yes,dependent=yes");
}

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>