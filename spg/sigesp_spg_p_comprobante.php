<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
$li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
$li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head >
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones_gasto.js"></script>
<title>Comprobante Presupuestario</title>
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

<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo25 {color: #6699CC}
-->
</style>
</head>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<body>
<script language="javascript">	
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
				f.action="sigesp_spg_p_comprobante.php";
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
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif"  title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="530" class="toolbar">&nbsp;</td>
  </tr>
</table>
<?php
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("sigesp_spg_c_comprobante.php");
require_once("class_funciones_gasto.php");
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

	$io_seguridad= new sigesp_c_seguridad();
	
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
		$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
		$ls_logusr="";
	}
	$ls_sistema="SPG";
	$ls_ventana="sigesp_spg_p_comprobante.php";
	$la_seguridad["empresa"]=$ls_codemp;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventana;

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
		$lb_permisos=$io_seguridad->uf_sss_select_permisos($ls_codemp,$ls_logusr,$ls_sistema,$ls_ventana);
	}

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_function=new class_funciones();	
$io_fecha=new class_fecha();
$io_msg = new class_mensajes();
$io_function_db=new class_funciones_db($io_connect);
$in_classcmp=new sigesp_spg_c_comprobante();
$io_int_scg=new class_sigesp_int_scg();
$io_int_spg=new class_sigesp_int_spg();
$io_msg=new class_mensajes();
$io_grid=new grid_param();
$io_funciones_gasto= new class_funciones_gasto();

$la_emp=$_SESSION["la_empresa"];
$li_estmodest=$la_emp["estmodest"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion = $_POST["operacion"];
 	$ls_procede = $_POST["txtproccomp"];
	$ls_comprobante = $_POST["txtcomprobante"];
	$ls_fecha     = $_POST["txtfecha"];
	$ls_provbene  = $_POST["txtprovbene"];
	$ls_desproben  = $_POST["txtdesproben"];
	$ls_tipo      = $_POST["tipo"];
	$ls_descripcion = $_POST["txtdesccomp"];
	$ls_status    = $_POST["status_actual"];
	$ls_codban     = $_POST["txtcodban"];
	$ls_ctaban     = $_POST["txtctaban"];
	$li_fila		 = 0;
	$ls_rendicion="";
	$ls_rendfon=$io_funciones_gasto->uf_obtenervalor("chrenfon","0"); 
	$ls_disabled="";
	$ls_fuentefin=$_POST["txtfuente"];
	$ls_desfuente=$_POST["txtdesfuente"];
	$ls_existe=$_POST["existe"];
	$ls_parametros=$_POST["parametros"];
}
else
{
	$ls_operacion="NUEVO";
	$_SESSION["ACTUALIZAR"]="NO";
	$_SESSION["ib_new"]	=true;
	$ls_fecha=date("d/m/Y");
	$li_fila = 0;
	$ls_rendicion="";
	$ls_rendfon=$io_funciones_gasto->uf_obtenervalor("chrenfon","0"); 
	$ls_disabled=""; 
	$ls_fuentefin="";
	$ls_desfuente="";
	$ls_existe="N";
	$ls_parametros="";	
	
}
if  (array_key_exists("estcla",$_POST))
{
  $ls_estcla=$_POST["estcla"];
}
else
{
  $ls_estcla="";
}	

if($ls_operacion=="VALIDAFECHA")
{
	$readonly="";
	$ldec_mondeb=0;
	$ldec_diferencia=0;
	$ldec_monhab=0;
	$ls_comprobante  = $_POST["txtcomprobante"];
	$ls_procede   = $_POST["txtproccomp"];
	$ls_fecha     = $_POST["txtfecha"];
	$ls_tipo      = $_POST["tipo"];
	$ls_provbene  = $_POST["txtprovbene"];
	$ls_desproben  = $_POST["txtdesproben"];
	$ls_descripcion = $_POST["txtdesccomp"];
	$ls_codemp=$la_emp["codemp"];

	$lb_valido=$io_fecha->uf_valida_fecha_periodo($ls_fecha,$ls_codemp);
	
	if(!($lb_valido))
	{
		$io_msg->message($io_fecha->is_msg_error);
		$ls_fecha="01/01/1900";
	}
	else
	{
	  $lb_existe=$in_classcmp->uf_verificar_comprobante($ls_codemp,$ls_procede,$ls_comprobante);
	  if($lb_existe)
	  {
		 $io_msg->message(" El Comprobante ya existe. El Sistema generara un nuevo numero de Comprobante");
	     $ls_comprobante = $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPGCMP');
	  }
	}
	$li_fila = 0;
	$prov_sel= "";
	$bene_sel= "";
	$ning_sel= "selected";
	$totalpre= 1;
	$totalcon= 1;	
    $li_estmodest=$la_emp["estmodest"];
	if($li_estmodest==1)
	{
	   $li_size=32;
	   $li_maxlength=29;
	   $li_sizedoc=30;
	   $li_maxlengthdoc=30;
	   $li_sizedes=40;
	   $li_maxlengthdes=254;
	}
	else
	{
	   $li_size=40;
	   $li_maxlength=33;
	   $li_sizedoc=37;
	   $li_maxlengthdoc=15;
	   $li_sizedes=41;
	   $li_maxlengthdes=254;
	}
	$object[1][1]  = "<input type=text name=txtcuenta1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=10>";
	$object[1][2]  = "<input type=text name=txtprogramatico1 value='' class=sin-borde readonly style=text-align:center size=$li_size maxlength=$li_maxlength><input type=hidden name=txtestcla id=txtestcla value=''>"; 
	$object[1][3]  = "<input type=text name=txtdocumento1 value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
	$object[1][4]  = "<input type=text name=txtdescripcion1 value='' class=sin-borde readonly style=text-align:left>";
	$object[1][5]  = "<input type=text name=txtprocede1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
	$object[1][6]  = "<input type=text name=txtoperacion1 value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
	$object[1][7]  = "<input type=text name=txtmonto1 value='' class=sin-borde readonly style=text-align:right>";		
	$object[1][8]  = "";		
		
	$object2[1][1] = "<input type=text name=txtcontable1 value='' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
	$object2[1][2] = "<input type=text name=txtdocscg1 value='' class=sin-borde readonly style=text-align:center size=$li_sizedoc maxlength=$li_maxlengthdoc>";
	$object2[1][3] = "<input type=text name=txtdesdoc1 value='' class=sin-borde readonly style=text-align:left size=$li_sizedes maxlength=$li_maxlengthdes>";
	$object2[1][4] = "<input type=text name=txtprocdoc1 value='' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
	$object2[1][5] = "<input type=text name=txtdebhab1 value='' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
	$object2[1][6] = "<input type=text name=txtmontocont1 value='' class=sin-borde readonly style=text-align:center size=22 maxlength=22>";
	$object2[1][7] = "";

	uf_cargar_dt($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha);
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
  $grid1="grid_SPG";	
   //Titulos de la tabla de Detalle Contable
  $title2[1]="Cuenta";   $title2[2]="Documento";     $title2[3]="Descripción";     $title2[4]="Procede";   	$title2[5]="D/H";  $title2[6]="Monto";   $title2[7]="Edición";  
  $grid2="grid_SCG";
  
if($ls_operacion=="NUEVO")//Acciones para un comprobante nuevo
{
	$ls_procede   = "SPGCMP";
	$ls_status    = "N";
	$ls_comprobante = $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPGCMP');
	$ls_provbene  = "----------";
	$ls_desproben = "";
	$ls_tipo      = "";
	$ls_descripcion = "";
	$ls_tipo      = "";
	$li_fila	  = 0;
	$ldec_mondeb  = 0;
	$ldec_diferencia=0;
	$ldec_monhab  = 0;	
	$ldec_totspg  = 0;
	$prov_sel     = "";
	$bene_sel     = "";
	$ning_sel     = "selected";
	$totalpre     = 1;
	$totalcon     = 1;
	$ls_codban     = "---";
	$ls_ctaban     = "-------------------------";
	$ls_fuentefin="--";
	$ls_desfuente="";
	$ls_existe="N";
		
    $li_estmodest=$la_emp["estmodest"];
	if($li_estmodest==1)
	{
	   $li_size=32;
	   $li_maxlength=29;
	   $li_sizedoc=30;
	   $li_maxlengthdoc=30;
	   $li_sizedes=40;
	   $li_maxlengthdes=254;
	}
	else
	{
	   $li_size=40;
	   $li_maxlength=33;
	   $li_sizedoc=37;
	   $li_maxlengthdoc=15;
	   $li_sizedes=41;
	   $li_maxlengthdes=254;
	}	
}

if($ls_operacion=="CARGAR_DT")
{
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha      =$_POST["txtfecha"];
	$ls_proccomp   =$_POST["txtproccomp"];
	$ls_desccomp   =$_POST["txtdesccomp"];
	$ls_provbene   =$_POST["txtprovbene"];	
	$ls_desproben  = $_POST["txtdesproben"];
	$ls_codban     =$_POST["txtcodban"];
	$ls_ctaban     =$_POST["txtctaban"];
	$ls_tipo	   =$_POST["tipo"];
	$ls_disabled="";
	$ls_rendfon    =$io_funciones_gasto->uf_obtenervalor("chrenfon","0"); 
	if ($ls_rendfon=='1')
	{				
		$ls_rendicion="checked";
		$ls_disabled="disabled";
	}
	else
	{
		$ls_rendicion="";
		$ls_disabled="disabled";
	}
	$ldec_mondeb=0;
	$ldec_diferencia=0;
	$ldec_monhab=0;
	
	if($ls_tipo=="P")
	{
		$ls_prov=$ls_provbene;
		$ls_bene="----------";
		$prov_sel="selected";
		$bene_sel="";
		$ning_sel="";
	}
	else
	{
		$ls_bene=$ls_provbene;
		$ls_prov="----------";
		$prov_sel="";
		$bene_sel="selected";
		$ning_sel="";		
	}
	uf_cargar_dt($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);
	
}	

///////////////////////////funcion para cargar (en lote)///////////////////////////////////////////////////////////////////////
	function uf_load_data(&$as_parametros,$as_comp, $as_comp2)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_load_variables
	//		   Access: private
	//	  Description: Función que carga todas las variables necesarias en la página
	//	   Creado Por: Ing. Jennifer Rivero
	// Fecha Creación: 03/12/2008								
	//////////////////////////////////////////////////////////////////////////////
		global $totalpre, $totalcon;		
		for($li_i=1;($li_i<$totalpre);$li_i++)
		{
			$ls_cuenta1=$_POST["txtcuenta".$li_i];
			$ls_programatica1=$_POST["txtprogramatico".$li_i];
			$ls_documento1=$_POST["txtdocumento".$li_i];
			if ($as_comp==$ls_documento1)
			{
				$ls_documento1=$as_comp2;
			}
			$ls_descrip1=$_POST["txtdescripcion".$li_i];
			$ls_procede1=$_POST["txtprocede".$li_i];
			$ls_operacion1=$_POST["txtoperacion".$li_i];
			$ls_monto1=$_POST["txtmonto".$li_i];
			$ls_estcla1=$_POST["txtestcla".$li_i];
			$ls_scg1=$_POST["txtscgcta".$li_i];
			$as_parametros=$as_parametros."&txtcuenta".$li_i."=".$ls_cuenta1."&txtprogramtico".$li_i."=".$ls_programatica1."".
					   "&txtdocumento".$li_i."=".$ls_documento1."&txtdescripcion".$li_i."=".$ls_descrip1."".
					   "&txtprocede".$li_i."=".$ls_procede1."&txtoperacion".$li_i."=".$ls_operacion1."".
					   "&txtmonto".$li_i."=".$ls_monto1."&txtestcla".$li_i."=".$ls_estcla1."&scgcta".$li_i."=".$ls_scg1;			
		}
		$as_parametros=$as_parametros."&totaldetalles=".$totalpre."";
		
		for($li_j=1;($li_j<$totalcon);$li_j++)
		{
			$ls_cuentascg1=$_POST["txtcontable".$li_j]; 
			$ls_docscg1=$_POST["txtdocscg".$li_j];
			if ($as_comp==$ls_docscg1)
			{
				$ls_docscg1=$as_comp2;
			}
			$ls_desdoc1=$_POST["txtdesdoc".$li_j];
			$ls_procdoc1=$_POST["txtprocdoc".$li_j];
			$ls_debhab1=$_POST["txtdebhab".$li_j];
			$ls_montocont1=$_POST["txtmontocont".$li_j];
			$as_parametros=$as_parametros."&txtcontable".$li_j."=".$ls_cuentascg1."&txtdocscg".$li_j."=".$ls_docscg1."".
					   "&txtdesdoc".$li_j."=".$ls_desdoc1."&txtprocdoc".$li_j."=".$ls_procdoc1."".
					   "&txtdebhab".$li_j."=".$ls_debhab1."&txtmontocont".$li_j."=".$ls_montocont1;	 
		}
		$as_parametros=$as_parametros."&totaldetallescont=".$totalcon."";
	}// fin de lafuncion uf_load_data()
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($ls_operacion=="GUARDAR")
{
	$ls_codemp=$la_emp["codemp"];
	$ls_operacion=$_POST["operacion"];
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha=$_POST["txtfecha"];
	$_SESSION["fechacomprobante"]=$ld_fecha;
	$ls_procedencia=$_POST["txtproccomp"];
	$ls_descripcion=$_POST["txtdesccomp"];
	$ls_tipo=$_POST["tipo"];
	$in_classcmp->io_int_int->is_tipo=$ls_tipo;
	$in_classcmp->io_int_int->is_cod_prov=$_POST["txtprovbene"];
	$in_classcmp->io_int_int->is_ced_ben=$_POST["txtprovbene"];
	$ls_desproben  = $_POST["txtdesproben"];
	$in_classcmp->io_int_int->ib_procesando_cmp=false;
	$in_classcmp->io_int_int->id_fecha=$io_function->uf_convertirdatetobd($ld_fecha);
	$ls_codban     = "---";
	$ls_ctaban     = "-------------------------";
	$ls_rendfon    =$io_funciones_gasto->uf_obtenervalor("chrenfon","0");
	
	$ls_existe=$_POST["existe"];
	 
	if ($ls_rendfon=='1')
	{
		$ls_rendicion="checked";
		$ls_disabled="disabled";
	}
	else
	{
		$ls_rendicion="";
		$ls_disabled="disabled";
	} 
	
	if( $ls_tipo=='P')
	{
		$ls_fuente = $_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_cod_prov=$_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_ced_ben="----------";
		$prov_sel="selected";
		$bene_sel="";
		$ning_sel="";
	}
	elseif($ls_tipo=='B')
	{
		$ls_fuente = $_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_ced_ben=$_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_cod_prov="----------";
		$prov_sel="";
		$bene_sel="selected";
		$ning_sel="";
	}
	else
	{
		$ls_fuente = "-";
		$in_classcmp->io_int_int->is_cod_prov="----------";
		$in_classcmp->io_int_int->is_ced_ben="----------";
		$prov_sel="";
		$bene_sel="";
		$ning_sel="selected";
		
	}
	$ls_codemp=$la_emp["codemp"];	 
	$lb_valido=$io_fecha->uf_valida_fecha_periodo($ls_fecha,$ls_codemp);
	
	if(!($lb_valido))
	{
		$io_msg->message($io_fecha->is_msg_error);
		$ls_fecha="01/01/1900";
	}
	else
	{
		$ls_fuentefin=$_POST["txtfuente"];
		$ls_comprobante2=$ls_comprobante;
		$ls_comprobante3=$ls_comprobante;
	    $in_classcmp->io_int_spg->io_sql->begin_transaction();	
		
		$lb_valido=$in_classcmp->uf_guardar_automatico($ls_comprobante,$ld_fecha,$ls_procedencia,$ls_descripcion,
		                                               $in_classcmp->io_int_int->is_cod_prov,
													   $in_classcmp->io_int_int->is_ced_ben,$ls_tipo,1,
													   $ls_codban,$ls_ctaban, $ls_rendfon, $ls_fuentefin, $ls_existe);
		if ($lb_valido)
		{ 
			$totalpre=$_POST["totpre"];
			$arr_cmp["comprobante"]=$ls_comprobante;
			$ld_fecdb=$io_function->uf_convertirdatetobd($ld_fecha);
			$arr_cmp["fecha"]      	 = $ld_fecdb;
			$arr_cmp["procedencia"]	 = $ls_procedencia;
			$arr_cmp["descripcion"]	 = $ls_descripcion;
			$arr_cmp["proveedor"]  	 = $in_classcmp->io_int_int->is_cod_prov;
			$arr_cmp["beneficiario"] = $in_classcmp->io_int_int->is_ced_ben;
			$arr_cmp["tipo"]         = $ls_tipo;
			$arr_cmp["codemp"]       = $ls_codemp;
			$arr_cmp["tipo_comp"]    = 1;			
			usleep(3000000);
			for ($i=1;($i<$totalpre && $lb_valido);$i++)
			    { 
				  $ls_cuenta	   = trim($io_funciones_gasto->uf_obtenervalor("txtcuenta".$i,""));				
				  $ls_programatica = trim($io_funciones_gasto->uf_obtenervalor("txtprogramatico".$i,""));								
				  $ls_documento	   = trim($io_funciones_gasto->uf_obtenervalor("txtdocumento".$i,""));	
				  if ($ls_documento==$ls_comprobante2)
				     {
					   $ls_documento=$ls_comprobante;
				     }			
				  $ls_descripcion = trim($io_funciones_gasto->uf_obtenervalor("txtdescripcion".$i,""));
				  $ls_procede	  = trim($io_funciones_gasto->uf_obtenervalor("txtprocede".$i,""));				
				  $ls_operacion	  = trim($io_funciones_gasto->uf_obtenervalor("txtoperacion".$i,""));
				  $ls_monto		  = trim($io_funciones_gasto->uf_obtenervalor("txtmonto".$i,""));
				  $ls_monto		  = str_replace(".","",$ls_monto);
			      $ls_monto		  = str_replace(",",".",$ls_monto);
				  $ls_estcla	  = trim($io_funciones_gasto->uf_obtenervalor("txtestcla".$i,""));
				  $ls_codestpro1  = str_pad(substr($ls_programatica,0,$li_loncodestpro1),25,0,0);
				  $ls_codestpro2  = str_pad(substr($ls_programatica,$li_loncodestpro1+1,$li_loncodestpro2),25,0,0);
				  $ls_codestpro3  = str_pad(substr($ls_programatica,$li_loncodestpro1+$li_loncodestpro2+2,$li_loncodestpro3),25,0,0);
				  if ($li_estmodest==2)
				     {
					   $ls_codestpro4 = str_pad(substr($ls_programatica,$li_loncodestpro1+$li_loncodestpro2+$li_loncodestpro3+$li_loncodestpro4+1,$li_loncodestpro4),25,0,0);
					   $ls_codestpro5 = str_pad(substr($ls_programatica,-$li_loncodestpro5),25,0,0);
					 }
  				  else
				     {
					   $ls_codestpro4 = $ls_codestpro5 = str_pad("",25,0,0);
					 }
				$ld_disponible=0;				
				$lb_valido=$in_classcmp->uf_spg_select_disponibilidad($ls_cuenta,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
				                                                      $ld_disponible,$ls_operacion,$ls_monto,$ls_estcla);
				if(($ld_disponible<$ls_monto)&&($lb_valido))
				{
				   $io_msg->message(" La Cuenta  ".trim($ls_cuenta)."  no tiene disponibilidad " ); 
				   $ls_cuentaplan= "";
				   $ls_denominacion= "";
				   $ls_descripcion= "";
				   $lb_valido=false;
				}
				else
				{
				    $lb_valido=$in_classcmp->uf_guardar_movimientos($arr_cmp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
					                                                $ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,
																	$ls_operacion,0,$ls_monto,"C",$ls_codban,$ls_ctaban,
																	$ls_estcla);
					if (!$lb_valido)
					{
						$lb_valido=false;						
						//return false;
					}					
				}// fin de disponibilidad
				
			}// fin del for 
		}// fin del Valido (guardar detalle presupuestarios)	
		
		if ($lb_valido)
		{
			$totalcon=$_POST["totcon"]; 
			
			for ($j=1;$j<$totalcon;$j++)
			{
				  
				  $ls_sc_cuenta=trim($io_funciones_gasto->uf_obtenervalor("txtcontable".$j,""));
				  $ls_documento=trim($io_funciones_gasto->uf_obtenervalor("txtdocscg".$j,""));
				  if ($ls_documento==$ls_comprobante3)
				  {
						$ls_documento=$ls_comprobante;
				  }	
				  $ls_desdoc=trim($io_funciones_gasto->uf_obtenervalor("txtdesdoc".$j,""));
				  $ls_procdoc=trim($io_funciones_gasto->uf_obtenervalor("txtprocdoc".$j,""));
				  $ls_debhab=trim($io_funciones_gasto->uf_obtenervalor("txtdebhab".$j,""));
				  $ldec_monto=trim($io_funciones_gasto->uf_obtenervalor("txtmontocont".$j,""));
				  $ldec_monto     = str_replace(".","",$ldec_monto);
		          $ldec_monto     = str_replace(",",".",$ldec_monto);
				  if (($ls_sc_cuenta!="")&&($ls_documento!=""))
				  {
				    	 $lb_valido=$in_classcmp->uf_guardar_movimientos_contable($arr_cmp,$ls_sc_cuenta,$ls_procdoc,$ls_desdoc,
																			   $ls_documento,$ls_debhab,$ldec_monto,
																			   $ls_codban,$ls_ctaban,$ld_fecdb);
				  }				 
			}// fin de for contable
			
		} // fin del Valido (guardar detalle contables)	
		if ($lb_valido)
	  	{  
			$in_classcmp->io_int_spg->io_sql->commit();			
			$ls_existe="C";
			$io_msg->message(" El Comprobante se registro Exitosamente..." ); 
		}
		else
		{
			$in_classcmp->io_int_spg->io_sql->rollback();			
			$io_msg->message($in_classcmp->is_msg_error);			
			$io_msg->message(" Error al guardar Comprobante" ); 			
		}		
	}// fin del else
	$ls_parametros="";
	uf_load_data($ls_parametros,$ls_comprobante2, $ls_comprobante);
}// fin del guardar


if($ls_operacion=="ELIMINAR")
{
	$lb_valido=false;
	$ls_codemp=$la_emp["codemp"];
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha=$_POST["txtfecha"];
	$ls_procedencia=$_POST["txtproccomp"];
	$ls_descripcion=$_POST["txtdesccomp"];
	$ls_tipo=$_POST["tipo"];
	$in_classcmp->io_int_int->is_tipo=$ls_tipo;
	$in_classcmp->io_int_int->is_cod_prov=$_POST["txtprovbene"];
	$in_classcmp->io_int_int->is_ced_ben=$_POST["txtprovbene"];
	$ls_desproben  = $_POST["txtdesproben"];
	$in_classcmp->io_int_int->ib_procesando_cmp=false;
	$in_classcmp->io_int_int->id_fecha=$io_function->uf_convertirdatetobd($ld_fecha);
	$ls_codban     = "---";
	$ls_ctaban     = "-------------------------";
	
	$ls_rendfon    =$io_funciones_gasto->uf_obtenervalor("chrenfon","0"); 
	if ($ls_rendfon=='1')
	{
		$ls_disabled="";
		$ls_rendicion="checked";
		$ls_disabled="disabled";
	}
	else
	{
		$ls_disabled="";
		$ls_rendicion="";
		$ls_disabled="";
	}
	
	if($ls_tipo=='P')
	{
		$ls_fuente = $_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_cod_prov=$_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_ced_ben="----------";
		$prov_sel="selected";
		$bene_sel="";
		$ning_sel="";
	}
	elseif($ls_tipo=='B')
	{
		$ls_fuente = $_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_ced_ben=$_POST["txtprovbene"];
		$in_classcmp->io_int_int->is_cod_prov="----------";
		$prov_sel="";
		$bene_sel="selected";
		$ning_sel="";	
	}
	else
	{
		$ls_fuente = "-";
		$in_classcmp->io_int_int->is_cod_prov="----------";
		$in_classcmp->io_int_int->is_ced_ben="----------";
		$prov_sel="";
		$bene_sel="";
		$ning_sel="selected";		
	}

	$lb_valido=$in_classcmp->io_int_int->uf_init_delete($ls_codemp,$ls_procedencia,$ls_comprobante, $in_classcmp->io_int_int->id_fecha,$ls_tipo,$in_classcmp->io_int_int->is_ced_ben,$in_classcmp->io_int_int->is_cod_prov,false,$ls_codban,$ls_ctaban);

	if (!$lb_valido) 
    {	
	   $io_msg->message("Comprobante no existe");	
	}	
	else
	{
	    $lb_valido = $in_classcmp->io_int_int->uf_int_init_transaction_begin();
		if(!$lb_valido)
		{
			$io_msg->message($in_classcmp->io_int_int->is_msg_error);
		}	
		if($lb_valido)
		{	
			$lb_valido = $in_classcmp->io_int_int->uf_init_end_transaccion_integracion($la_seguridad);
			if (!$lb_valido)
			{
				$io_msg->message("Error".$in_classcmp->io_int_int->is_msg_error);
				$in_classcmp->io_int_int->io_sql->rollback();
			}
			else
			{
				$io_msg->message("Comprobante eliminado satisfactoriamente");
 				$ls_comprobante =  $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPGCMP');
				$ls_fecha     = date("d/m/Y");
				$ls_provbene  = "";
				$ls_desproben  = "";
				$ls_tipo      = "-";
				$ls_descripcion = "";				
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_desc_event="Elimino el comprobante presupuestario ".$ls_comprobante." de fecha ".$ld_fecha." y procedencia ".$ls_procedencia;
				$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_codemp,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
				////////////////////////////////         SEGURIDAD               //////////////////////////////
				$in_classcmp->io_int_int->io_sql->commit();
			}
		}
    }		
	uf_cargar_dt($la_emp["codemp"],$ls_procedencia,$ls_comprobante,$ld_fecha);
}
if($ls_operacion=="DELETESPG")		
{
	$ls_comprobante= $_POST["txtcomprobante"];
	$ld_fecha      = $_POST["txtfecha"];
	$_SESSION["fechacomprobante"] = $ld_fecha;
	$ls_proccomp   = $_POST["txtproccomp"];
	$ls_desccomp   = $_POST["txtdesccomp"];
	$ls_provbene   = $_POST["txtprovbene"];	
	$ls_tipo	   = $_POST["tipo"];
	$li_fila	   = $_POST["fila"];
	$ls_desproben  = $_POST["txtdesproben"];
	$ls_codban     = "---";
	$ls_ctaban     = "-------------------------";
	
	$ls_rendfon    =$io_funciones_gasto->uf_obtenervalor("chrenfon","0"); 
	if ($ls_rendfon=='1')
	{
		$ls_disabled="";
		$ls_rendicion="checked";
		$ls_disabled="disabled";
	}
	else
	{
		$ls_disabled="";
		$ls_rendicion="";
		$ls_disabled="";
	}
	
	
	if($ls_tipo=="P")
	{
		$ls_prov=$ls_provbene;
		$ls_bene="----------";
		$prov_sel="selected";
		$bene_sel="";
		$ning_sel="";
	}
	elseif($ls_tipo=="B")
	{
		$ls_bene=$ls_provbene;
		$ls_prov="----------";
		$prov_sel="";
		$bene_sel="selected";
		$ning_sel="";		
	}
	else
	{
		$ls_bene="----------";
		$ls_prov="----------";
		$prov_sel="";
		$bene_sel="";
		$ning_sel="selected";		
	}
    $li_estmodest=$la_emp["estmodest"];
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
	$ls_estcla=$_POST["txtestcla".$li_fila];			
	
	if($li_estmodest==2)
	{
		$estprog[0]=substr(str_replace("-","",$_POST["txtprogramatico".$li_fila]),$ls_incio1,$ls_loncodestpro1);
		$estprog[1]=substr(str_replace("-","",$_POST["txtprogramatico".$li_fila]),$ls_incio2,$ls_loncodestpro2);
		$estprog[2]=substr(str_replace("-","",$_POST["txtprogramatico".$li_fila]),$ls_incio3,$ls_loncodestpro3);
		$estprog[3]=substr(str_replace("-","",$_POST["txtprogramatico".$li_fila]),$ls_incio4,$ls_loncodestpro4);
		$estprog[4]=substr(str_replace("-","",$_POST["txtprogramatico".$li_fila]),$ls_incio5,$ls_loncodestpro5);
	}
	else
	{
		$estprog[0]=substr(str_replace("-","",$_POST["txtprogramatico".$li_fila]),$ls_incio1,$ls_loncodestpro1);
		$estprog[1]=substr(str_replace("-","",$_POST["txtprogramatico".$li_fila]),$ls_incio2,$ls_loncodestpro2);
		$estprog[2]=substr(str_replace("-","",$_POST["txtprogramatico".$li_fila]),$ls_incio3,$ls_loncodestpro3);
		$estprog[3]=$io_function->uf_cerosizquierda(0,25);
		$estprog[4]=$io_function->uf_cerosizquierda(0,25);
	}
	
	$estprog[0] = $io_function->uf_cerosizquierda($estprog[0],25);
	$estprog[1] = $io_function->uf_cerosizquierda($estprog[1],25);
	$estprog[2] = $io_function->uf_cerosizquierda($estprog[2],25);
	$estprog[3] = $io_function->uf_cerosizquierda($estprog[3],25);
	$estprog[4] = $io_function->uf_cerosizquierda($estprog[4],25);
    $estprog[5] = $ls_estcla;

	$ls_cuenta=$_POST["txtcuenta".$li_fila];	
	$ls_procede_doc=$_POST["txtprocede".$li_fila];
	$ls_descripcion=$_POST["txtdescripcion".$li_fila];
	$ls_documento=$_POST["txtdocumento".$li_fila];
	$ls_operacion2=$_POST["txtoperacion".$li_fila];
	$ldec_monto_anterior=$_POST["txtmonto".$li_fila];
	$ldec_monto_actual=0;
	$li_tipo_comp=1;
	
	$ls_mensaje=$io_int_spg->uf_operacion_codigo_mensaje($ls_operacion2);
	$io_int_spg->is_codemp     = $la_emp["codemp"];
	$io_int_spg->id_fecha      = $io_function->uf_convertirdatetobd($ld_fecha);
	$io_int_spg->is_procedencia= $ls_proccomp;
	$io_int_spg->is_comprobante= $ls_comprobante;
	$io_int_spg->is_tipo       = $ls_tipo;
	$io_int_spg->is_cod_prov   = $ls_prov;
	$io_int_spg->is_ced_ben    = $ls_bene;
	$io_int_spg->ib_AutoConta  = true;
    $ls_denominacion="";	
	$ls_status="";	
	$ls_sc_cuenta="";	
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
	
	if(!$io_int_spg->uf_spg_select_cuenta($la_emp["codemp"],$estprog,$ls_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
	{
	  $io_msg->message(" Registro No Fue Eliminado, la cuenta No existe");
	}
	
	$lb_valido=$io_int_spg->uf_int_spg_delete_movimiento($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha,$ls_tipo,
	                                                     $ls_fuente,$ls_prov,$ls_bene,$estprog,$ls_cuenta,$ls_procede_doc,
														 $ls_documento,$ls_descripcion,$ls_mensaje,$li_tipo_comp,
													     $ldec_monto_anterior,$ldec_monto_actual,$ls_sc_cuenta,$ls_codban,
														 $ls_ctaban);
	if($lb_valido)
	{
		$io_msg->message(" Registro Eliminado Satisfactoriamente");
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_desc_event="Elimino el movimiento presupuestario ".$ls_documento." con operacion".$ls_operacion2." por un monto de ".$ldec_monto_anterior." para la cuenta ".$ls_cuenta." correspondiente a la estructura programatica ".$estprog[0]."-".$estprog[1]."-".$estprog[2]."-00-00; para el comprobante ".$ls_comprobante." de fecha ".$ld_fecha;
		$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_codemp,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////
		$io_int_spg->io_sql->commit();
	}
	else
	{
 		$io_int_spg->io_sql->rollback();
		$io_msg->message(" Registro No Fue Eliminado ");
	}
	uf_cargar_dt($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);
}

if($ls_operacion=="DELETESCG")		
{
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha      =$_POST["txtfecha"];
	$ls_proccomp   =$_POST["txtproccomp"];
	$ls_desccomp   =$_POST["txtdesccomp"];
	$ls_provbene   =$_POST["txtprovbene"];	
	$ls_tipo	   =$_POST["tipo"];
	$li_fila	   =$_POST["fila"];
	$ls_desproben  = $_POST["txtdesproben"];
	$ls_codban     = "---";
	$ls_ctaban     = "-------------------------";
	
		
	$ls_rendfon    =$io_funciones_gasto->uf_obtenervalor("chrenfon","0"); 
	if ($ls_rendfon=='1')
	{
		$ls_disabled="";
		$ls_rendicion="checked";
		$ls_disabled="disabled";
	}
	else
	{
		$ls_disabled="";
		$ls_rendicion="";
		$ls_disabled="";
	}
	
	if($ls_tipo=="P")
	{
		$ls_prov=$ls_provbene;
		$ls_bene="----------";
		$prov_sel="selected";
		$bene_sel="";
		$ning_sel="";
	}
	else
	{
		$ls_bene=$ls_provbene;
		$ls_prov="----------";
		$prov_sel="";
		$bene_sel="selected";
		$ning_sel="";		
	}
	
	$ls_cuenta=$_POST["txtcontable".$li_fila];	
	$ls_procdoc=$_POST["txtprocdoc".$li_fila];
	$ls_desdoc=$_POST["txtdesdoc".$li_fila];
	$ls_docscg=$_POST["txtdocscg".$li_fila];
	$ls_debhab=$_POST["txtdebhab".$li_fila];
	$ldec_monto_anterior=$_POST["txtmontocont".$li_fila];
	$ldec_monto_actual=0;
	$li_tipo_comp=1;
	
	//$ls_mensaje=$int_scg->uf_operacion_codigo_mensaje($ls_operacion);
	$io_int_scg->is_codemp=$la_emp["codemp"];
	$io_int_scg->id_fecha=$io_function->uf_convertirdatetobd($ld_fecha);
	$io_int_scg->is_procedencia=$ls_proccomp;
	$io_int_scg->is_comprobante=$ls_comprobante;
	$io_int_scg->is_tipo=$ls_tipo;
	$io_int_scg->is_cod_prov=$ls_prov;
	$io_int_scg->is_ced_ben=$ls_bene;
	$io_int_scg->ib_AutoConta=true;
						
	$lb_valido=$io_int_scg->uf_scg_procesar_delete_movimiento($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$io_int_scg->id_fecha, $ls_cuenta, $ls_procdoc, $ls_docscg,$ls_debhab, $ldec_monto_anterior,$ls_codban,$ls_ctaban);
	if($lb_valido)
	{
		$io_msg->message(" Registro Eliminado Satisfactoriamente");
		$io_int_scg->io_sql->commit();
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_desc_event="Elimino el movimiento contable ".$ls_docscg." con operacion ".$ls_debhab." por un monto de ".$ldec_monto_anterior." para la cuenta ".$ls_cuenta."; para el comprobante ".$ls_comprobante." de fecha ".$ld_fecha;
		$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_codemp,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////
	}
	else
	{
 		$io_int_scg->io_sql->rollback();
		$io_msg->message(" Registro No Fue Eliminado ");
	}
	uf_cargar_dt($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);

}

function uf_cargar_dt($ls_codemp,$ls_proccomp,$ls_comprobante,$ld_fecha)
{
	global $in_classcmp;
	global $la_emp;
	global $totalpre;
	global $totalcon;
	global $object;
	global $object2;
	global $ldec_mondeb;
	global $ldec_monhab;
	global $ldec_diferencia;
	global $ldec_totspg;
	$ldec_totspg=0;
	$ldec_mondeb=0;
	$ldec_monhab=0;
	$ldec_diferencia=0;
	$i=0;
	$rs_dtcmp=$in_classcmp->uf_cargar_dt_comprobante($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);
	$li_numrows=$in_classcmp->io_sql->num_rows($rs_dtcmp);
	$totalpre=1;
	$totalcon=1;
	$li_estmodest=$la_emp["estmodest"];
	$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
	if($li_numrows>0)
	{
	    $totalpre=$li_numrows;
		while($row=$in_classcmp->io_sql->fetch_row($rs_dtcmp))
		{
			$i=$i+1;
			$ls_cuenta=$row["spg_cuenta"];
			
			if($li_estmodest==2)
			{
				$ls_programatico=substr($row["codest1"],-$ls_loncodestpro1).substr($row["codest2"],-$ls_loncodestpro2).substr($row["codest3"],-$ls_loncodestpro3).substr($row["codest4"],-$ls_loncodestpro4).substr($row["codest5"],-$ls_loncodestpro5);
			}
			else
			{
				$ls_programatico=substr($row["codest1"],-$ls_loncodestpro1).substr($row["codest2"],-$ls_loncodestpro2).substr($row["codest3"],-$ls_loncodestpro3);
			}
			$ls_documento=$row["documento"];
			$ls_descripcion=$row["descripcion"];
			$ls_procede=$row["procede_doc"];
			$ls_operacion=$row["operacion"];
			$ldec_monto=$row["monto"];
			$ls_estcla=$row["estcla"];
			if($li_estmodest==1)
			{
			   $li_size=32;
			    $li_maxlength=$ls_loncodestpro1+$ls_loncodestpro2+$ls_loncodestpro3+10;
			}
			else
			{
			   $li_size=40;
			   $li_maxlength=$ls_loncodestpro1+$ls_loncodestpro2+$ls_loncodestpro3+$ls_loncodestpro4+$ls_loncodestpro5+10;
			}
			
			$object[$i][1]="<input type=text name=txtcuenta".$i." value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$object[$i][2]="<input type=text name=txtprogramatico".$i." value='".$ls_programatico."' class=sin-borde readonly style=text-align:center size=30 maxlength=$li_maxlength><input type=hidden name=txtestcla".$i." id=txtestcla".$i." value='".$ls_estcla."'>"; 
			$object[$i][3]="<input type=text name=txtdocumento".$i." value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$object[$i][4]="<input type=text name=txtdescripcion".$i." value='".$ls_descripcion."' title='".$ls_descripcion."' class=sin-borde readonly style=text-align:left>";
			$object[$i][5]="<input type=text name=txtprocede".$i." value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$object[$i][6]="<input type=text name=txtoperacion".$i." value='".$ls_operacion."' class=sin-borde readonly style=text-align:center size=4 maxlength=3>";
			$object[$i][7]="<input type=text name=txtmonto".$i." value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right>";		
			$object[$i][8] ="<a href=javascript:uf_delete_dt_presupuesto(".($i).");><img src=../shared/imagebank/tools15/eliminar.gif width=15 height=15 border=0 alt=Eliminar Detalle Presupuesto></a>";
			$ldec_totspg = $ldec_totspg + $ldec_monto;
		}
		$in_classcmp->io_sql->free_result($rs_dtcmp);
	}
	else
	{
	  if ($li_estmodest==1)
		 {
		   $li_size=32;
		   $li_maxlength=$ls_loncodestpro1+$ls_loncodestpro2+$ls_loncodestpro3+10;
		 }
	  else
		 {
		   $li_size=40;
		   $li_maxlength=$ls_loncodestpro1+$ls_loncodestpro2+$ls_loncodestpro3+$ls_loncodestpro4+$ls_loncodestpro5+10;
		 }
		
		$object[1][1]="<input type=text name=txtcuenta1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$object[1][2]="<input type=text name=txtprogramatico1 value='' class=sin-borde readonly style=text-align:center size=$li_size maxlength=$li_maxlength>"; 
		$object[1][3]="<input type=text name=txtdocumento1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$object[1][4]="<input type=text name=txtdescripcion1 value='' class=sin-borde readonly style=text-align:left>";
		$object[1][5]="<input type=text name=txtprocede1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
		$object[1][6]="<input type=text name=txtoperacion1 value='' class=sin-borde readonly style=text-align:center size=4 maxlength=3>";
		$object[1][7]="<input type=text name=txtmonto1 value='' class=sin-borde readonly style=text-align:right>";		
		$object[1][8] ="";
	}
$i=0;
$rs_dtscg=$in_classcmp->uf_cargar_dt_contable_cmp($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha);
$li_numrows=$in_classcmp->io_sql->num_rows($rs_dtscg);
//$totalpre=$li_numrows;

	if($li_numrows>0)
	{
	    $totalcon=$li_numrows;
		while($row=$in_classcmp->io_sql->fetch_row($rs_dtscg))
		{
			$i=$i+1;+
			$ls_sc_cuenta=$row["sc_cuenta"];
			$ls_documento=$row["documento"];
			$ls_desdoc=$row["descripcion"];
			$ls_procdoc=$row["procede_doc"];
			$ls_debhab=$row["debhab"];
			$ldec_monto=$row["monto"];
			if($ls_debhab=="D")	
			{
				$ldec_mondeb=$ldec_mondeb+$ldec_monto;
			}
			else
			{
				$ldec_monhab=$ldec_monhab+$ldec_monto;
			}
			if($li_estmodest==1)
			{
			   $li_sizedoc=30;
			   $li_sizedes=40;
			}
			else
			{
			   $li_sizedoc=37;
			   $li_sizedes=41;
			}
			$object2[$i][1]="<input type=text name=txtcontable".$i." value='".$ls_sc_cuenta."' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
			$object2[$i][2]="<input type=text name=txtdocscg".$i." value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=$li_sizedoc maxlength=254>";
			$object2[$i][3]="<input type=text name=txtdesdoc".$i." value='".$ls_desdoc."' title='".$ls_desdoc."' class=sin-borde readonly style=text-align:center size=$li_sizedes maxlength=254>";
			$object2[$i][4]="<input type=text name=txtprocdoc".$i." value='".$ls_procdoc."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$object2[$i][5]="<input type=text name=txtdebhab".$i." value='".$ls_debhab."' class=sin-borde readonly style=text-align:center size=3 maxlength=1>"; 
			$object2[$i][6]="<input type=text name=txtmontocont".$i." value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right size=22 maxlength=28>";
			$object2[$i][7] ="<a href=javascript:uf_delete_dt_contable(".($i).");><img src=../shared/imagebank/tools15/eliminar.gif width=15 height=15 border=0 alt=Eliminar Detalle Contable></a>";
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
			   $li_sizedes=40;
			}
			else
			{
			   $li_sizedoc=37;
			   $li_sizedes=41;
			}
			$object2[1][1]="<input type=text name=txtcontable1 value='' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
			$object2[1][2]="<input type=text name=txtdocscg1 value='' class=sin-borde readonly style=text-align:center size=$li_sizedoc maxlength=254>";
			$object2[1][3]="<input type=text name=txtdesdoc1 value='' class=sin-borde readonly style=text-align:center size=$li_sizedes maxlength=254>";
			$object2[1][4]="<input type=text name=txtprocdoc1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$object2[1][5]="<input type=text name=txtdebhab1 value='' class=sin-borde readonly style=text-align:center size=3 maxlength=1>"; 
			$object2[1][6]="<input type=text name=txtmontocont1 value='' class=sin-borde readonly style=text-align:center size=22 maxlength=22>";
			$object2[1][7] ="";
	}

}
	if($ls_tipo=='P')
	{
	$prov_sel="checked";
	$bene_sel="";
	$ning_sel="";
	}
	elseif($ls_tipo=='B')
	{
	$prov_sel="";
	$bene_sel="checked";
	$ning_sel="";
	}
	else
	{
	$prov_sel="";
	$bene_sel="";
	$ning_sel="checked";
	}
	if($ls_status=="C")
	{
		$readonly="readonly";
	}
	else
	{
		$readonly="";
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
<table width="780" height="321" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-nuevo">
        <td height="20" colspan="3">Comprobante Presupuestario </td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="143" height="22">
        <p align="right"> Procedencia</p></td>
        <td width="504">
          <input name="txtproccomp" type="text" id="txtproccomp" value="<?php print $ls_procede?>" readonly="true" style="text-align:center" >
          <input name="status_actual" type="hidden" id="status_actual" value="<?php print $ls_status;?>">
          <input name="estcla" type="hidden" id="estcla" value="<?php print $ls_estcla;?>"></td>
        <td width="168"><div align="left">Fecha
            <input name="txtfecha" type="text" id="txtfecha" style="text-align:center"  value="<?php print $ls_fecha?>" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);"  <?php print $readonly;?>  size="15" maxlength="15" datepicker="true">
        </div></td>
      </tr>
      <tr>
        <td height="22">
        <p align="right">Comprobante</p></td>
        <td><input name="txtcomprobante" type="text" id="txtcomprobante" onBlur="javascript: valid_cmp(document.form1.txtcomprobante.value);" maxlength="15" style="text-align:center" value="<?php print $ls_comprobante ?>" <?php print $readonly ;?>></td>
        <td><input type="checkbox" name="chrenfon" value="1" <?php print $ls_rendicion;?> <? print $ls_disabled?>> 
          Rendici&oacute;n de Fondos </td>
      </tr>
      <tr>
        <td height="22">
        <p align="right">Descripci&oacute;n </p></td>
        <td colspan="2"><input name="txtdesccomp" type="text" id="txtdesccomp" size="120" value="<?php print $ls_descripcion;?>"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tipo</div></td>
        <td><p>
          <label>
          <input name="tipo" id="tipo" type="radio" class="sin-borde" value="P" <?php print $prov_sel;?> onClick="javascript:uf_verificar_provbene();">
  Proveedor</label>
          <label>
          <input name="tipo" id="tipo" type="radio" class="sin-borde" value="B" <?php print $bene_sel;?> onClick="javascript:uf_verificar_provbene();">
  Beneficiario</label>
          <label>
          <input name="tipo" id="tipo" type="radio" class="sin-borde" value="-" <?php print $ning_sel;?> onClick="javascript:uf_verificar_provbene();">
  Ninguno</label>
          <input name="tipsel" type="hidden" id="tipsel" value="<?php print $ls_tipo;?>">
          <label></label>
          <br>
        </p></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><p align="right">C&oacute;digo/C&eacute;dula</p></td>
        <td><input name="txtprovbene" type="text" id="txtprovbene" style="text-align:center" value="<?php print $ls_provbene?>" readonly >
            <a href="javascript:catprovbene()"><img  src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>
            <input name="txtdesproben" type="text" id="txtdesproben" size="60" maxlength="250" class="sin-borde" value="<?php print $ls_desproben;?>" ></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="22">
        <p align="right">Fuente de Financiamiento </p></td>
        <td>
          <input name="txtfuente" type="text" id="txtfuente" style="text-align:center" value="<?php print $ls_fuentefin?>" readonly >
          <a href="javascript:cat_fuente()"><img  src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>
          <input name="txtdesfuente" type="text" id="txtdesfuente" size="60" maxlength="250" class="sin-borde" value="<?php print $ls_desfuente;?>" ></td>
        <td>&nbsp;</td>
      </tr>
      <tr >
        <td height="22" colspan="3"><div align="center"></div></td>
      </tr>
      <tr >
        <td height="17" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: uf_agregar_dtpre();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Presupuesto</a> </td>
      </tr>
        <tr>
          <td height="13" colspan="3"><div id="detallespres"></div></td>
        </tr>
        <tr>
        <td height="19" colspan="3">
		<div align="center"><?php //$io_grid->makegrid($totalpre,$title,$object,820,'Detalles Presupuestarios',$grid1);?>
		  <input name="totpre" type="hidden" id="totpre" value="<?php print $totalpre?>">
		</div></td>
      </tr>
        <tr>
          <td height="13" colspan="3"><table width="805" border="0" cellpadding="0" cellspacing="0" class="celdas-blancas">
            <tr>
              <!--<td width="650" height="22"><div align="right">Total Presupuestario </div></td>
              <td width="155"><div align="center">
                <input name="txttotspg" type="text" id="txttotspg" value="<?php print number_format($ldec_totspg,2,",",".");?>" size="28" style="text-align:right">
              </div></td>-->
            </tr>
          </table></td>
        </tr>
      <tr>
        <!--<td height="22" colspan="3"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: uf_agregar_dtcon();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0"></a><a href="javascript: uf_agregar_dtcon();">Agregar detalle Contable </a> </p>        </td>-->
      </tr>
        <tr>
          <td height="19" colspan="3">
		  <div align="center"><?php /*$io_grid->makegrid($totalcon,$title2,$object2,820,'Detalles Contable',$grid2);*/?> 
		    <input name="totcon" type="hidden" id="totcon" value="<?php print $totalcon?>">
		  </div></td>
        </tr>
	  <br>
      <tr>
        <td height="42" colspan="3" valign="top" bordercolor="#FFFFFF"><table width="805" border="0" align="center" cellpadding="0" cellspacing="0" class="celdas-blancas">
            <tr>
              <td height="13">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <!--<td><div align="right">Debe</div></td>-->
              <td><div align="center">
                <!--<input name="txtdebe" type="text" id="txtdebe" style="text-align:right" value="<?php print number_format($ldec_mondeb,2,",",".");?>" size="28" readonly>-->
              </div></td>
            </tr>
            <tr>
              <td height="13">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
             <!-- <td><div align="right">Haber</div></td>
              <td><div align="center">
                <input name="txthaber" type="text" id="txthaber" style="text-align:right" value="<?php print number_format($ldec_monhab,2,",",".");?>" size="28" readonly>
              </div></td>-->
            </tr>
            <tr>
              <td width="79" height="13">&nbsp;</td>
              <td width="100">&nbsp;</td>
              <td width="131">&nbsp;</td>
              <td width="212"><div align="right"> </div></td>
              <!--<td width="118"><div align="center"></div>
              <div align="right">Diferencia</div></td>
              <td width="165"><div align="center">
                  <p>
                    <input name="txtdiferencia" type="text" id="txtdiferencia" style="text-align:right" value="<?php print number_format($ldec_diferencia,2,",",".") ;?>" size="28" readonly>
                  </p>
              </div></td>-->
            </tr>
        </table></td>
      </tr>
    </table>
    <input name="operacion" type="hidden" id="operacion">
    <input name="totalpre" type="hidden" id="totalpre" value="<?php print $totpre; ?>" >
    <input name="totalcon" type="hidden" id="totalcon" value="<?php print $totcon; ?>" > 
    <input name="fila" type="hidden" id="fila" value="<?php print $li_fila;?>">
	<input name="txtcodban" type="hidden" id="txtcodban" value="<?php print $ls_codban ?>">
	<input name="txtctaban" type="hidden" id="txtctaban" value="<?php print $ls_ctaban; ?>">
	<input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
	<input name="parametros"    type="hidden" id="parametros"    value="<?php print $ls_parametros;?>">
</div>
</form>
</body>
<script language="javascript">
//Funcion de carga de Catalogos

function cat()
{
	f=document.form1;
	f.txtcuenta.disabled=false;
	window.open("sigesp_catdinamic_ctas.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function catprovbene()
{
	f=document.form1;
	if(f.tipo[0].checked==true)
	{
		f.txtprovbene.disabled=false;	
		window.open("sigesp_catdinamic_prov.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else if(f.tipo[1].checked==true)
	{
		f.txtprovbene.disabled=false;	
		window.open("sigesp_catdinamic_bene.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
	 alert("Debe Indicar el Tipo (Proveedor o  Beneficiario) , Verifique por Favor");
	}
}

//Funciones de operaciones para el detalle del comprobante
function editar(fila,cuenta , deno , procede,documento,debhab,monto)
{
	f=document.form1;
	f.fila.value=fila;
	f.txtcuenta.disabled=false;
	f.txtcuenta.value=cuenta;
	f.txtdescdoc.value=deno;
	f.txtprocdoc.value=procede;
	f.txtdocumento.value=documento;
	f.debhab.value=debhab;
	f.txtmonto.value=monto;
	f.operacion.value ="EDITAR";
	f.action="sigesp_spg_p_comprobante.php";
	f.txtcuenta.focus(true	);
	f.submit();

}

function uf_save_mov()
{
	f=document.form1;
	f.operacion.value="AGREGAR";
	f.action="sigesp_spg_p_comprobante.php";
	f.submit();
}
function uf_cargar_dt()
{
	f=document.form1;
	f.operacion.value="CARGAR";
	f.action="sigesp_spg_p_comprobante.php";
	f.submit();
}
function uf_del_mov(cuenta,desc,proc,doc,debhab,monto)
{
	f=document.form1;
	f.txtcuenta.value=cuenta;
	f.txtdescdoc.value=desc;
	f.txtprocdoc.value=proc;
	f.txtdocumento.value=doc;
	f.debhab.value=debhab;
	f.txtmonto.value=monto;
	f.operacion.value="DELMOV";
	f.action="sigesp_spg_p_comprobante.php";
	f.submit();
}

function uf_upd_mov(fila,cuenta,desc,proc,doc,debhab,monto)
{
	f=document.form1;
	f.fila.value=fila;
	f.txtcuenta.value=cuenta;
	f.txtdescdoc.value=desc;
	f.txtprocdoc.value=proc;
	f.txtdocumento.value=doc;
	f.debhab.value=debhab;
	f.txtmonto.value=monto;
	f.operacion.value="UPDMOV";
	f.action="sigesp_spg_p_comprobante.php";
	f.submit();
}
//Funciones de operaciones sobre el comprobante
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="sigesp_spg_p_comprobante.php";
	f.submit();	
}
function ue_guardar()
{
	f=document.form1;
	ls_procede=f.txtproccomp.value;
	
	filapre= ue_calcular_total_fila_local("txtcuenta");  
	ls_filacont= ue_calcular_total_fila_local("txtcontable");
	f.totpre.value=filapre+1; 
	f.totcon.value=ls_filacont+1; 
	
	if(ls_procede=="SPGCMP")
	{
		if(valida_campos())
		{
			
			ldec_diferencia=f.txtdiferencia.value;
			ldec_diferencia=uf_convertir_monto(ldec_diferencia);			
			if(ldec_diferencia!=0)
			{
				alert("Comprobante descuadrado");
			}
			else
			{
				f.operacion.value="GUARDAR";
				f.chrenfon.disabled="";
				f.action="sigesp_spg_p_comprobante.php";
				f.submit();
			}
		}
	}
	else
	{
		alert("No puede editar un comprobante que no fue generado por este módulo");
	}
	
}
function ue_eliminar()
{
	f=document.form1;
	ls_procede=f.txtproccomp.value;	
	if(ls_procede=="SPGCMP")
	{
		if(confirm("Seguro desea eliminar el comprobante"))
		{
	
		f.operacion.value="ELIMINAR";
		f.action="sigesp_spg_p_comprobante.php";
		f.submit();
		}
	}
	else
	{
		alert("No puede editar un comprobante que no fue generado por este módulo");
	}	
}
function ue_buscar()
{
	f=document.form1;	
	//ldec_diferencia=f.txtdiferencia.value;
	//ldec_diferencia=uf_convertir_monto(ldec_diferencia);
	ldec_diferencia=0;
	if(ldec_diferencia!=0)
	{
		alert("Comprobante descuadrado");
	}
	else
	{
		window.open("sigesp_cat_comprobantesspg.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");
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
f=document.form1;

ls_procede=f.txtproccomp.value;
ls_fecha=f.txtfecha.value;
ls_comprobante=f.txtcomprobante.value;
ls_desccomp=f.txtdesccomp.value;
ls_tipo=f.tipo.value;
ls_provbene=f.txtprovbene.value;
lb_valido=true;

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
	alert("Debe registrar la descripcion del comporbante");
	lb_valido=false;
}

if((ls_tipo=="B")||(ls_tipo=="P"))
{
	if(ls_provbene=="")
	{
		alert("Debe seleccionar un Proveedor o Beneficiario");
		lb_valido=false;
	}
}
return 	lb_valido;

}

function valid_cmp(cmp)
{
rellenar_cad(cmp,15,"cmp");
f=document.form1;
f.operacion.value="VALIDAFECHA";
f.action="sigesp_spg_p_comprobante.php";
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

function uf_verificar_provbene()
{
	f=document.form1;
	ls_tipsel=f.tipsel.value;
	if(f.tipo[0].checked)
	{
		if(ls_tipsel!='P')
		{
			f.txtprovbene.value="";
			f.txtdesproben.value="";
			f.tipsel.value='P';
		}
	}
	if(f.tipo[1].checked)
	{
		if(ls_tipsel!='B')
		{
			f.txtprovbene.value="";
			f.txtdesproben.value="";
			f.tipsel.value='B';
		}
	}
	if(f.tipo[2].checked)
	{
		if(ls_tipsel!='-')
		{
			f.txtprovbene.value="----------";
			f.txtdesproben.value="";
			f.tipsel.value='-';
		}
	}
}

function EvaluateText(cadena, obj)
{ 
	
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
   
   
   //--------------///----------------------///------------------------------//----------
   		function lTrim(sStr)
		{
			 while (sStr.charAt(0) == " ")
		     sStr = sStr.substr(1, sStr.length - 1);
			 return sStr;
		}	 
		
		function rTrim(sStr)
		{
			 while (sStr.charAt(sStr.length - 1) == " ")
		     sStr = sStr.substr(0, sStr.length - 1);
			 return sStr;
		}
		function allTrim(sStr){
		  return rTrim(lTrim(sStr));
		}
   
   //--------------///---------------------///-------------------------------//-----------
   function  uf_agregar_dtpre()
   {
   
	f=document.form1;
	ls_comprobante= f.txtcomprobante.value;
	ld_fecha      = f.txtfecha.value;
	ls_proccomp   = f.txtproccomp.value;
	ls_desccomp   = allTrim(f.txtdesccomp.value); 
	ls_provbene   = f.txtprovbene.value;
	ls_codfuefin  = f.txtfuente.value; 
	
	totalfila     = f.totpre.value;
	
	if (totalfila==1)
	{
	
		if (ue_calcular_total_fila_local("txtcuenta")!=1)
		{
			totalfila= ue_calcular_total_fila_local("txtcuenta")+1; 
		}		
	}
	
	totcont       = f.totcon.value;
	
	if (totcont==1)
	{
		if (ue_calcular_total_fila_local("txtcontable")!=1)
		{
			totcont= ue_calcular_total_fila_local("txtcontable")+1;  
		}
	}		
	
	if (f.chrenfon.checked)
	{
		renfon=f.chrenfon.value;
	}
	else
	{
		renfon=0;
	}
		
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
		ls_tipo='-'
	}
	if(ls_proccomp=="SPGCMP")
	{
		if((ls_comprobante!="")&&(ls_proccomp!="")&&(ld_fecha!="")&&(ls_provbene!="")&&(ls_tipo)&&(ls_codfuefin!=""))
		{
			if (ls_desccomp!="")
			{
				ls_pagina = "sigesp_w_regdt_presupuesto_2.php?procede="+ls_proccomp+"&comprobante="+ls_comprobante+"&fecha="+ld_fecha+"&descripcion="+ls_desccomp+"&tipo="+ls_tipo+"&provbene="+ls_provbene+"&renfon="+renfon+"&codfuefin="+ls_codfuefin+"&filapre="+totalfila+"&totcont="+totcont;
				window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=320,left=50,top=50,location=no,resizable=yes,dependent=yes");
			}
			else
			{
				alert("Complete los datos del comprobante");
			}
		}
	}		
	else
	{
		 alert("No puede editar un comprobante que no fue generado por este módulo");
	}
   }
   
   //---------------------------------------------------------
   function ue_calcular_total_fila_local(campo)
	{
		existe=true;
		li_i=1;
		while(existe)
		{
			existe=document.getElementById(campo+li_i); 
			if(existe!=null)
			{
				li_i=li_i+1;
			}
			else
			{
				existe=false;
				li_i=li_i-1;
			}
		}
		return li_i
	}
 //--------------------------------------------------------------  
   
    function  uf_agregar_dtcon()
   {
   
	f=document.form1;
	ls_comprobante= f.txtcomprobante.value;
	ld_fecha      = f.txtfecha.value;
	ls_proccomp   = f.txtproccomp.value;
	ls_desccomp   = f.txtdesccomp.value;
	ls_provbene   = f.txtprovbene.value;	
	ls_tipo	      = f.tipo.value;
	if(ls_proccomp=="SPGCMP")
	{
		if((ls_comprobante!="")&&(ls_proccomp!="")&&(ld_fecha!="")&&(ls_provbene!="")&&(ls_tipo!=""))
		{
			ls_pagina = "sigesp_w_regdt_contable_2.php?procede="+ls_proccomp+"&comprobante="+ls_comprobante+"&fecha="+ld_fecha+"&descripcion="+ls_desccomp+"&tipo="+ls_tipo+"&provbene="+ls_provbene;
			window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=590,height=220,left=50,top=50,location=no,resizable=yes,dependent=yes");
		}
		else
		{
			alert("Complete los datos del comprobante");
		}
	}
	else
	{
	     alert("No puede editar un comprobante que no fue generado por este módulo");
	}
   }
   
   function uf_delete_dt_presupuesto(row)
   {
		  f=document.form1;
		  ls_procede=f.txtproccomp.value;
		  filas=row;
		  parametros="";
		  if(ls_procede=="SPGCMP")
		  {
				//filapre       =  document.form1.totpre.value;
				filapre= ue_calcular_total_fila_local("txtcuenta");
				//ls_filacont   =  document.form1.totcon.value;	
				ls_filacont= ue_calcular_total_fila_local("txtcontable"); 			
				valido=true;
				cuenta_scg="";			
				li_i=0;
				for(i=1;(i<=filapre)&&(valido);i++)
				{
					if(i!=filas)
				    {
						li_i=li_i+1;
						cuenta1=eval("document.form1.txtcuenta"+i+".value");
						programatica1=eval("document.form1.txtprogramatico"+i+".value");
						documento1=eval("document.form1.txtdocumento"+i+".value");
						descrip1=eval("document.form1.txtdescripcion"+i+".value");
						procede1=eval("document.form1.txtprocede"+i+".value");
						operacion1=eval("document.form1.txtoperacion"+i+".value");
						monto1=eval("document.form1.txtmonto"+i+".value");
						estcla1=eval("document.form1.txtestcla"+i+".value");
						scgcta1=eval("document.form1.txtscgcta"+i+".value");						
						//cuenta_scg=scgcta1; 
						//monto_scg=monto1;
						parametros=parametros+"&txtcuenta"+li_i+"="+cuenta1+"&txtprogramtico"+li_i+"="+programatica1+""+
								   "&txtdocumento"+li_i+"="+documento1+"&txtdescripcion"+li_i+"="+descrip1+""+
								   "&txtprocede"+li_i+"="+procede1+"&txtoperacion"+li_i+"="+operacion1+""+
								   "&txtmonto"+li_i+"="+monto1+"&txtestcla"+li_i+"="+estcla1+"&scgcta"+li_i+"="+scgcta1;
						
					}
					else
					{
					 scgcta1=eval("document.form1.txtscgcta"+i+".value");
					 monto1=eval("document.form1.txtmonto"+i+".value");						
					 cuenta_scg=scgcta1;
					 monto_scg=monto1;
					}
				}// fin del for
				/*if (li_i==1)
				{
					li_i=li_i+1;
				}*/
				totaldetalles=eval(li_i);
				parametros=parametros+"&totaldetalles="+totaldetalles;
				li_j=1;	
				ls_filacont=ls_filacont+1;
				encontrado=true; 					  
				for(k=1;k<ls_filacont;k++)
				{
				   	cuentascg1=eval("document.form1.txtcontable"+k+".value"); 
					docscg1=eval("document.form1.txtdocscg"+k+".value");
					desdoc1=eval("document.form1.txtdesdoc"+k+".value");
					procdoc1=eval("document.form1.txtprocdoc"+k+".value");
					debhab1=eval("document.form1.txtdebhab"+k+".value");
					montocont1=eval("document.form1.txtmontocont"+k+".value");
					if ((allTrim(cuenta_scg)==allTrim(cuentascg1))&&(debhab1=="D"))
					{
					    ld_mondetcon  = ue_formato_calculo(montocont1);
				        ld_montotcon  = ue_formato_calculo(monto_scg);
					    if(ld_mondetcon > ld_montotcon)
						{
					     montocont1= parseFloat(ld_mondetcon)-parseFloat(ld_montotcon);
						 montocont1    = uf_convertir(montocont1);	
			  		     /*parametros=parametros+"&txtcontable"+k+"="+cuentascg1+"&txtdocscg"+k+"="+docscg1+""+
							       "&txtdesdoc"+k+"="+desdoc1+"&txtprocdoc"+k+"="+procdoc1+""+
								   "&txtdebhab"+k+"="+debhab1+"&txtmontocont"+k+"="+montocont1;*/
								   
						parametros=parametros+"&txtcontable"+li_j+"="+cuentascg1+"&txtdocscg"+li_j+"="+docscg1+""+
				                   "&txtdesdoc"+li_j+"="+desdoc1+"&txtprocdoc"+li_j+"="+procdoc1+""+
				                   "&txtdebhab"+li_j+"="+debhab1+"&txtmontocont"+li_j+"="+montocont1;		   
						li_j=li_j+1;		   
						}
						else if (montocont1!= monto_scg)
						{
						 
						 parametros=parametros+"&txtcontable"+li_j+"="+cuentascg1+"&txtdocscg"+li_j+"="+docscg1+""+
				                   "&txtdesdoc"+li_j+"="+desdoc1+"&txtprocdoc"+li_j+"="+procdoc1+""+
				                   "&txtdebhab"+li_j+"="+debhab1+"&txtmontocont"+li_j+"="+montocont1;		   
						 li_j=li_j+1;
						}		   										
					}
					else
					{
					 /*parametros=parametros+"&txtcontable"+k+"="+cuentascg1+"&txtdocscg"+k+"="+docscg1+""+
							       "&txtdesdoc"+k+"="+desdoc1+"&txtprocdoc"+k+"="+procdoc1+""+
								   "&txtdebhab"+k+"="+debhab1+"&txtmontocont"+k+"="+montocont1;*/
					 			   
				     parametros=parametros+"&txtcontable"+li_j+"="+cuentascg1+"&txtdocscg"+li_j+"="+docscg1+""+
				                   "&txtdesdoc"+li_j+"="+desdoc1+"&txtprocdoc"+li_j+"="+procdoc1+""+
				                   "&txtdebhab"+li_j+"="+debhab1+"&txtmontocont"+li_j+"="+montocont1;
					 li_j=li_j+1;		
					}
					
					/*if ((allTrim(cuenta_scg)==allTrim(cuentascg1))&&(parseFloat(montocont1)!=parseFloat(monto_scg)))
					{
					   parametros=parametros+"&txtcontable"+k+"="+cuentascg1+"&txtdocscg"+k+"="+docscg1+""+
								   "&txtdesdoc"+k+"="+desdoc1+"&txtprocdoc"+k+"="+procdoc1+""+
								   "&txtdebhab"+k+"="+debhab1+"&txtmontocont"+k+"="+montocont1;
					}	*/				
					/*if (debhab1=="H")
					{
						montocont1= parseFloat(montocont1)- parseFloat(monto_scg);	
			  		    parametros=parametros+"&txtcontable"+k+"="+cuentascg1+"&txtdocscg"+k+"="+docscg1+""+
							       "&txtdesdoc"+k+"="+desdoc1+"&txtprocdoc"+k+"="+procdoc1+""+
								   "&txtdebhab"+k+"="+debhab1+"&txtmontocont"+k+"="+montocont1;		
					}*/					
				 }// fin del for
				 if (filapre==1)
				 {
				 	//ls_filacont=2;
					ls_filacont=1;
				 }
				 if(li_j==2)
				 {
				  if(ExisteObjetoDestino(eval("document.form1.txtcontable1")))
				  {
				   if(document.form1.txtcontable1.value == "")
				   {
				    li_j=li_j-1;
				   }
				  }
				 }
				 ls_filacont=eval(li_j);				 
				 totaldetallescont=eval(ls_filacont);
			     parametros=parametros+"&totaldetallescont="+totaldetallescont;	
				 if ((parametros!=""))
				 {
						// Div donde se van a cargar los resultados
						divgrid = document.getElementById("detallespres");
						// Instancia del Objeto AJAX
						ajax=objetoAjax();
						// Pagina donde están los métodos para buscar y pintar los resultados
						ajax.open("POST","class_folder/sigesp_spg_c_comprobante_ajax.php",true);
						ajax.onreadystatechange=function()
						{
							if(ajax.readyState==4)
							{
								if(ajax.status==200)
								{//mostramos los datos dentro del contenedor
									divgrid.innerHTML = ajax.responseText
								}
								else
								{
									if(ajax.status==404)
									{
										divgrid.innerHTML = "La página no existe";
									}
									else
									{//mostramos el posible error     
										divgrid.innerHTML = "Error:".ajax.status;
									}
								}					
							}// fin del if
						}// fin del la funcion ajax
						ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
						// Enviar todos los campos a la pagina para que haga el procesamiento
						ajax.send("proceso=AGREGARDETALLES"+parametros);								
						//document.form1.totcont.value=totaldetallescont;				
					}//FIN del if
					if (totaldetalles==2)
					{
						totaldetalles=totaldetalles-1;
					}
					document.form1.totpre.value=totaldetalles;					
					document.form1.totcon.value=totaldetallescont;							
		  }
		  else
		  {
			alert("No puede editar un comprobante que no fue generado por este módulo");
		  }	
		/*  
		  existe=f.existe.value;  
		  if (existe=="C")
		  {
			  if (f.chrenfon.checked)
			  {
				  valor =f.totpre.value;
				  if (valor==filas)
				  {		 
					  if (filas==1)
					  {
						f.chrenfon.value=0;
						f.chrenfon.disabled="";
					  }		  
					  if(ls_procede=="SPGCMP")
					  {
						  f.chrenfon.disabled="";
						  f.action="sigesp_spg_p_comprobante.php";
						  f.operacion.value="DELETESPG";						 
						  f.fila.value=row;
						  f.submit();
					   }
					   else
					   {
						 alert("No puede editar un comprobante que no fue generado por este módulo");
					   }
					}
					else
					{
						alert("No se puede eliminar existen detalle posteriores a este....");
					}
				}
			  else
			  {
					  if(ls_procede=="SPGCMP")
					  {
						  if (filas==1)
						  {
							f.chrenfon.value=0;
							f.chrenfon.disabled="";
						  }	
						  f.chrenfon.disabled="";
						  f.action="sigesp_spg_p_comprobante.php";
						  f.operacion.value="DELETESPG";
						  f.fila.value=row;
						  f.submit();
					   }
					   else
					   {
						 alert("No puede editar un comprobante que no fue generado por este módulo");
					   }
				}
			}// fin del existe*/
    }// fin de la funcion  
  
   function uf_delete_dt_contable(row)
   {
	  f=document.form1;
	  ls_procede=f.txtproccomp.value;
	  parametros="";
	  filas=row;
	  if(ls_procede=="SPGCMP")
	  {
			filapre= ue_calcular_total_fila_local("txtcuenta"); 			
			ls_filacont= ue_calcular_total_fila_local("txtcontable");		
			valido=true;
			for(i=1;(i<=filapre)&&(valido);i++)
			{
				if(ExisteObjetoDestino(eval("document.form1.txtcuenta"+i)))
				{
				 cuenta1=eval("document.form1.txtcuenta"+i+".value");
				 programatica1=eval("document.form1.txtprogramatico"+i+".value");
				 documento1=eval("document.form1.txtdocumento"+i+".value");
				 descrip1=eval("document.form1.txtdescripcion"+i+".value");
				 procede1=eval("document.form1.txtprocede"+i+".value");
				 operacion1=eval("document.form1.txtoperacion"+i+".value");
				 monto1=eval("document.form1.txtmonto"+i+".value");
				 estcla1=eval("document.form1.txtestcla"+i+".value");
				 scgcta1=eval("document.form1.txtscgcta"+i+".value");				
				 parametros=parametros+"&txtcuenta"+i+"="+cuenta1+"&txtprogramtico"+i+"="+programatica1+""+
								   "&txtdocumento"+i+"="+documento1+"&txtdescripcion"+i+"="+descrip1+""+
								   "&txtprocede"+i+"="+procede1+"&txtoperacion"+i+"="+operacion1+""+
								   "&txtmonto"+i+"="+monto1+"&txtestcla"+i+"="+estcla1+"&scgcta"+i+"="+scgcta1;
				}					
			}// fin del for
			/*if (filapre==1)
			{
				filapre=filapre+1;
			}*/	
			totaldetalles=eval(filapre);
			parametros=parametros+"&totaldetalles="+totaldetalles;
			li_i=1;
			for(k=1;k<=ls_filacont;k++)
			{
				if(k!=filas)
				{
					   if(ExisteObjetoDestino(eval("document.form1.txtcontable"+k)))
				       {
						cuentascg1=eval("document.form1.txtcontable"+k+".value"); 
						docscg1=eval("document.form1.txtdocscg"+k+".value");
						desdoc1=eval("document.form1.txtdesdoc"+k+".value");
						procdoc1=eval("document.form1.txtprocdoc"+k+".value");
						debhab1=eval("document.form1.txtdebhab"+k+".value");
						montocont1=eval("document.form1.txtmontocont"+k+".value");
						parametros=parametros+"&txtcontable"+li_i+"="+cuentascg1+"&txtdocscg"+li_i+"="+docscg1+""+
										   "&txtdesdoc"+li_i+"="+desdoc1+"&txtprocdoc"+li_i+"="+procdoc1+""+
										   "&txtdebhab"+li_i+"="+debhab1+"&txtmontocont"+li_i+"="+montocont1;
						li_i=li_i+1;
					    }	
				}					
			}// fin del for	
			if (li_i==1)
			{
				if(ExisteObjetoDestino(eval("document.form1.txtcontable1")))
				{
				 if(document.form1.txtcontable1.value != "")
				 {
				 // li_i=li_i+1;
				 }
				}
			}		
			totaldetallescont=eval(li_i);
			parametros=parametros+"&totaldetallescont="+totaldetallescont;			
			if ((parametros!=""))
			{
				// Div donde se van a cargar los resultados
				divgrid = document.getElementById("detallespres");
				// Instancia del Objeto AJAX
				ajax=objetoAjax();
				// Pagina donde están los métodos para buscar y pintar los resultados
				ajax.open("POST","class_folder/sigesp_spg_c_comprobante_ajax.php",true);
				ajax.onreadystatechange=function()
				{
					if(ajax.readyState==4)
					{
						if(ajax.status==200)
						{//mostramos los datos dentro del contenedor
							divgrid.innerHTML = ajax.responseText
						}
						else
						{
							if(ajax.status==404)
							{
								divgrid.innerHTML = "La página no existe";
							}
							else
							{//mostramos el posible error     
								divgrid.innerHTML = "Error:".ajax.status;
							}
						}					
					}// fin del if
				}// fin del la funcion ajax
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				// Enviar todos los campos a la pagina para que haga el procesamiento
				ajax.send("proceso=AGREGARDETALLES"+parametros);										
				//document.form1.totcont.value=totaldetallescont;				
		   }//FIN del if
		   document.form1.totpre.value=totaldetalles;
		   if (totaldetallescont==2)
		   {
		   		totaldetallescont=totaldetallescont-1;
		   }
		   document.form1.totcon.value=totaldetallescont;				
	  }
	  else
	  {
	  	  alert("No puede editar un comprobante que no fue generado por este módulo");
	  }
	 /* existe=f.existe.value;  
	  if (existe=="C")
	  {
		  if(ls_procede=="SPGCMP")
		  {
			  f.chrenfon.disabled="";
			  f.action="sigesp_spg_p_comprobante.php";
			  f.operacion.value="DELETESCG";
			  f.fila.value=row;
			  f.submit();
		  }
		  else
		  {
			  alert("No puede editar un comprobante que no fue generado por este módulo");
		  }
	  }// fin del if*/
	  
    }// fin de lafuncion
	
	  
  function cat_fuente()
  {
		window.open("sigesp_spg_cat_fuente.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");
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


function ue_reload()
{
	f=document.form1;
	parametros=f.parametros.value;	
	if (parametros!="")
	{
		divgrid = document.getElementById("detallespres");
		ajax=objetoAjax();
		ajax.open("POST","class_folder/sigesp_spg_c_comprobante_ajax.php",true);
		ajax.onreadystatechange=function()
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText
				}					
			}// fin del if
		}// fin del la funcion ajax
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("proceso=AGREGARDETALLES"+parametros);
	}// fin de parametros	
}/// fin de la funcion

function ExisteObjetoDestino(objeto)
{
if (!objeto) {
    return false;
} 
else {
    return true;
}
}

function ue_reload2()
{
	f=document.form1;	
	parametros="";
	estmod=<? print $_SESSION["la_empresa"]["estmodest"]?>;
	comprobante=f.txtcomprobante.value;
	fecha=f.txtfecha.value;
	procede=f.txtproccomp.value;	
	parametros=parametros+"&comprobante="+comprobante+"&fecha="+fecha+"&procede="+procede+"&estmod="+estmod;	
	if (parametros!="")
	{
		divgrid = document.getElementById("detallespres");
		ajax=objetoAjax();
		ajax.open("POST","class_folder/sigesp_spg_c_comprobante_ajax.php",true);
		ajax.onreadystatechange=function()
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText
				}					
			}// fin del if
		}// fin del la funcion ajax
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("proceso=LOADPRESUPUESTO"+parametros);
	}// fin de parametros	
}/// fin de la funcion

</script> 
<?php
if (($ls_operacion=="CG")||($ls_operacion=="GUARDAR"))
{
   	print "<script language=JavaScript>";
	print "   ue_reload();";
	print "</script>";
}

if (($ls_operacion=="DELETESPG")||($ls_operacion=="DELETESCG"))
{
	print "<script language=JavaScript>";
	print "   ue_reload2();";
	print "</script>";
}
?>		
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>

</html>