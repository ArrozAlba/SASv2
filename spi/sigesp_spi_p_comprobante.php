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
<title>Comprobante de Ingreso</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
		if((ls_operacion=="NUEVO")||(ls_operacion=="GUARDAR")||(ls_operacion==""))
		{
			if(ldec_diferencia!=0)
			{
				alert("Comprobante descuadrado Contablemente");
				f.operacion.value="CARGAR_DT";
				f.action="sigesp_spi_p_comprobante.php";
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
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo25">Sistema de Presupuesto de Ingreso</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
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
require_once("../shared/class_folder/class_sigesp_int_spi.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("class_folder/class_funciones_spi.php");
require_once("sigesp_spi_c_comprobante.php");
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

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
	$ls_sistema="SPI";
	$ls_ventana="sigesp_spi_p_comprobante.php";
	$la_seguridad["empresa"]=$ls_empresa;
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
		$lb_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventana);
	}

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_function=new class_funciones();	
$io_fecha=new class_fecha();
$io_msg = new class_mensajes();
$io_function_db=new class_funciones_db($io_connect);
$in_classcmp=new sigesp_spi_c_comprobante();
$io_int_scg=new class_sigesp_int_scg();
$io_int_spi=new class_sigesp_int_spi();
$io_msg=new class_mensajes();
$io_grid=new grid_param();
$io_class_spi = new class_funciones_spi();
$la_emp=$_SESSION["la_empresa"];
$li_estmodest=$la_emp["estmodest"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
 	$ls_procede = $_POST["txtproccomp"];
	$ls_comprobante = $_POST["txtcomprobante"];
	$ls_fecha     = $_POST["txtfecha"];
	$ls_provbene  = $_POST["txtprovbene"];
	$ls_desproben  = $_POST["txtdesproben"];
	$ls_tipo      = $_POST["tipo"];
	$ls_descripcion = $_POST["txtdesccomp"];
	$ls_status    = $_POST["status_actual"];
	$li_fila		 = 0;
	$ls_codban = $_POST["txtcodban"];
	$ls_ctaban = $_POST["txtctaban"];
	
}
else
{
	$ls_operacion="NUEVO";
	$_SESSION["ACTUALIZAR"]="NO";
	$_SESSION["ib_new"]	=true;
	$ls_fecha=date("d/m/Y");
	$li_fila = 0;
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
	$ls_codban=$_POST["txtcodban"];
	$ls_ctaban=$_POST["txtctaban"];

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
	     $ls_comprobante = $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPICMP');
	  }
	}
	$li_fila = 0;
	$prov_sel= "";
	$bene_sel= "";
	$ning_sel= "selected";
	$totalpre= 1;
	$totalcon= 1;
	$li_estpreing     = $la_emp["estpreing"];
	if ($li_estpreing==0)
	{	
		$object[1][1]  = "<input type=text name=txtcuenta1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$object[1][2]  = "<input type=text name=txtdocumento1 value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
		$object[1][3]  = "<input type=text name=txtdescripcion1 value='' class=sin-borde readonly style=text-align:center>";
		$object[1][4]  = "<input type=text name=txtprocede1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
		$object[1][5]  = "<input type=text name=txtoperacion1 value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
		$object[1][6]  = "<input type=text name=txtmonto1 value='' class=sin-borde readonly style=text-align:right>";		
		$object[1][7]  = "";		
     }
	else
	{
		$object[1][1]  = "<input type=text name=txtcuenta1 value='' class=sin-borde readonly style=text-align:center size=10 maxlength=20>";
		$object[1][2]  = "<input type=text name=txtprogramatica1 value='' class=sin-borde readonly style=text-align:center size=25 maxlength=20>";
		$object[1][3]  = "<input type=text name=txtdocumento1 value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
		$object[1][4]  = "<input type=text name=txtdescripcion1 value='' class=sin-borde readonly style=text-align:center>";
		$object[1][5]  = "<input type=text name=txtprocede1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
		$object[1][6]  = "<input type=text name=txtoperacion1 value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
		$object[1][7]  = "<input type=text name=txtmonto1 value='' size=5 class=sin-borde readonly style=text-align:right>";		
		$object[1][8]  = "";
	
	}
			
	$object2[1][1] = "<input type=text name=txtcontable1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";		
	$object2[1][2] = "<input type=text name=txtdocscg1 value='' class=sin-borde readonly style=text-align:center size=12 maxlength=10>";
	$object2[1][3] = "<input type=text name=txtdesdoc1 value='' class=sin-borde readonly style=text-align:center size=30 maxlength=254>";
	$object2[1][4] = "<input type=text name=txtprocdoc1 value='' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
	$object2[1][5] = "<input type=text name=txtdebhab1 value='' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
	$object2[1][6] = "<input type=text name=txtmontocont1 value='' class=sin-borde readonly style=text-align:center size=22 maxlength=22>";
	$object2[1][7] = "";

	uf_cargar_dt($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ls_codban,$ls_ctaban);
}
	
	  //Titulos de la tabla de Detalle Presupuestario.
	  $li_estpreing     = $la_emp["estpreing"];
	  if ($li_estpreing==0)
	  {
	     $title[1]="Cuenta";  
	     $title[2]="Documento";    
		 $title[3]="Descripción";   
		 $title[4]="Procede"; 
		 $title[5]="Operación";
		 $title[6]="Monto";  
		 $title[7]="Edición";
  	     $grid1="grid_SPI";	
	   }
	  else
	  {
	  	  $title[1]="Cuenta";
		  if($li_estmodest==1)
		  {
		   $title[2]="Imputación Presupuestaria";
		  }
		  else
		  { 
		   $title[2]="Programatica";     
		  } 
		  $title[3]="Documento";
		  $title[4]="Descripción";
		  $title[5]="Procede"; 
		  $title[6]="Operación";
		  $title[7]="Monto";  
		  $title[8]="Edición";
		  $grid1="grid_SPI";	
	  }
	   //Titulos de la tabla de Detalle Contable
	  $title2[1]="Cuenta";   $title2[2]="Documento";     $title2[3]="Descripción";     $title2[4]="Procede";   	$title2[5]="D/H";   $title2[6]="Monto";   $title2[7]="Edición";
      //$object[$i][1]="<input type=text name=txtnumdocumento".$i." value=$documento class=sin-borde readonly>";$object[$i][2]="<input type=text name=txtcompromiso".$i." class=sin-borde value=$compromiso readonly>";$object[$i][3]="<input type=text name=txtcodigo".$i." value=$codigo class=sin-borde readonly>"; $object[$i][4]="<input type=text name=txtdenominacion".$i." value=$denominacion class=sin-borde readonly>";
  	  $grid2="grid_SCG";
if($ls_operacion=="NUEVO")//Acciones para un comprobante nuevo
{
	$ls_procede   = "SPICMP";
	$ls_status    = "N";
	$ls_comprobante = $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPICMP');
	$ls_provbene  = "----------";
	$ls_desproben = "";
	$ls_tipo      = "";
	$ls_descripcion = "";
	$ls_tipo      = "";
	$li_fila	  = 0;
	$ldec_mondeb  = 0;
	$ldec_diferencia=0;
	$ldec_monhab  = 0;	
	$ldec_totspi  = 0;
	$prov_sel     = "";
	$bene_sel     = "";
	$ning_sel     = "selected";
	$totalpre     = 1;
	$totalcon     = 1;	

    $li_estpreing     = $la_emp["estpreing"];
	if ($li_estpreing==0)
	{
		$object[1][1]  = "<input type=text name=txtcuenta1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$object[1][2]  = "<input type=text name=txtdocumento1 value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
		$object[1][3]  = "<input type=text name=txtdescripcion1 value='' class=sin-borde readonly style=text-align:center>";
		$object[1][4]  = "<input type=text name=txtprocede1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
		$object[1][5]  = "<input type=text name=txtoperacion1 value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
		$object[1][6]  = "<input type=text name=txtmonto1 value='' class=sin-borde readonly style=text-align:right>";		
		$object[1][7]  = "";	
	}
	else
	{ 
	    $object[1][1]  = "<input type=text name=txtcuenta1 value='' class=sin-borde readonly style=text-align:center size=10 maxlength=20>";
		$object[1][2]  = "<input type=text name=txtprogramatica1 value='' class=sin-borde readonly style=text-align:center size=25 maxlength=20>";
		$object[1][3]  = "<input type=text name=txtdocumento1 value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
		$object[1][4]  = "<input type=text name=txtdescripcion1 value='' class=sin-borde readonly style=text-align:center>";
		$object[1][5]  = "<input type=text name=txtprocede1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
		$object[1][6]  = "<input type=text name=txtoperacion1 value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
		$object[1][7]  = "<input type=text name=txtmonto1 value='' size=10 class=sin-borde readonly style=text-align:right>";		
		$object[1][8]  = "";
	}	

	$object2[1][1] = "<input type=text name=txtcontable1 value='' class=sin-borde readonly style=text-align:center size=14 maxlength=14>";		
	$object2[1][2] = "<input type=text name=txtdocscg1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
	$object2[1][3] = "<input type=text name=txtdesdoc1 value='' class=sin-borde readonly style=text-align:center size=30 maxlength=254>";
	$object2[1][4] = "<input type=text name=txtprocdoc1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
	$object2[1][5] = "<input type=text name=txtdebhab1 value='' class=sin-borde readonly style=text-align:center size=3 maxlength=1>"; 
	$object2[1][6] = "<input type=text name=txtmontocont1 value='' class=sin-borde readonly style=text-align:center size=22 maxlength=22>";
	$object2[1][7] = "";
	
}

if($ls_operacion=="CARGAR_DT")
{
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha      =$_POST["txtfecha"];
	$ls_proccomp   =$_POST["txtproccomp"];
	$ls_desccomp   =$_POST["txtdesccomp"];
	$ls_provbene   =$_POST["txtprovbene"];	
	$ls_desproben  = $_POST["txtdesproben"];
	$ls_tipo	   =$_POST["tipo"];
	$ldec_mondeb=$_POST["txttotspg"];
	$ls_codban=$_POST["txtcodban"];
	$ls_ctaban=$_POST["txtctaban"];
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
	
	uf_cargar_dt($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha,$ls_codban,$ls_ctaban);
	
}	
if($ls_operacion=="GUARDAR")
{
	$ls_codemp		  	 = $la_emp["codemp"];
	$ls_comprobante  	 = $_POST["txtcomprobante"];
	$ld_fecha		 	 = $_POST["txtfecha"];
	$ls_procedencia	 	 = $_POST["txtproccomp"];
	$ls_descripcion	 	 = $_POST["txtdesccomp"];
	$ls_tipo		 	 = $_POST["tipo"];
	$int_int->is_tipo	 = $ls_tipo;
	$int_int->is_cod_prov= $_POST["txtprovbene"];
	$int_int->is_ced_ben = $_POST["txtprovbene"];
	$ls_desproben        = $_POST["txtdesproben"];
	$ls_codban=$_POST["txtcodban"];
	$ls_ctaban=$_POST["txtctaban"];
	$int_int->ib_procesando_cmp=false;
	$int_int->id_fecha=$io_function->uf_convertirdatetobd($ld_fecha);
	$int_int->is_codban = $ls_codban;
	$int_int->is_ctaban = $ls_ctaban;
	
	if($ls_tipo=='P')
	{
		$ls_fuente = $_POST["txtprovbene"];
		$int_int->is_cod_prov=$_POST["txtprovbene"];
		$int_int->is_ced_ben="----------";
		$prov_sel="selected";
		$bene_sel="";
		$ning_sel="";
	}
	elseif($ls_tipo=='B')
	{
		$ls_fuente = $_POST["txtprovbene"];
		$int_int->is_ced_ben=$_POST["txtprovbene"];
		$int_int->is_cod_prov="----------";
		$prov_sel="";
		$bene_sel="selected";
		$ning_sel="";
	}
	else
	{
		$ls_fuente = "-";
		$int_int->is_cod_prov="----------";
		$int_int->is_ced_ben="----------";
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
		$lb_valido=$in_classcmp->uf_guardar_automatico($ls_comprobante,$ld_fecha,$ls_procedencia,$ls_descripcion,
		                                               $int_int->is_cod_prov,$int_int->is_ced_ben,$ls_tipo,1,
													   $ls_codban,$ls_ctaban);
		
		
		
		if(!$lb_valido)
		{
			$io_msg->message($in_classcmp->is_msg_error);			
		}
			
	}
	uf_cargar_dt($la_emp["codemp"],$ls_procedencia,$ls_comprobante,$ld_fecha,$ls_codban,$ls_ctaban);
}
if($ls_operacion=="ELIMINAR")
{
	$lb_valido=false;
	require_once("../shared/class_folder/class_sigesp_int_int.php");
	require_once("../shared/class_folder/class_sigesp_int_spg.php");
	require_once("../shared/class_folder/class_sigesp_int_spi.php");
	$int_int=new class_sigesp_int_int();
	$ls_codemp=$la_emp["codemp"];
	$ls_comprobante=$_POST["txtcomprobante"];
	$ld_fecha=$_POST["txtfecha"];
	$ls_procedencia=$_POST["txtproccomp"];
	$ls_descripcion=$_POST["txtdesccomp"];
	$ls_tipo=$_POST["tipo"];
	$int_int->is_tipo=$ls_tipo;
	$int_int->is_cod_prov=$_POST["txtprovbene"];
	$int_int->is_ced_ben=$_POST["txtprovbene"];
	$ls_desproben  = $_POST["txtdesproben"];
	$int_int->ib_procesando_cmp=false;
	$int_int->id_fecha=$io_function->uf_convertirdatetobd($ld_fecha);
	$ls_codban=$_POST["txtcodban"];
	$ls_ctaban=$_POST["txtctaban"];
	if($ls_tipo=='P')
	{
		$ls_fuente = $_POST["txtprovbene"];
		$int_int->is_cod_prov=$_POST["txtprovbene"];
		$int_int->is_ced_ben="----------";
		$prov_sel="selected";
		$bene_sel="";
		$ning_sel="";
	}
	elseif($ls_tipo=='B')
	{
		$ls_fuente = $_POST["txtprovbene"];
		$int_int->is_ced_ben=$_POST["txtprovbene"];
		$int_int->is_cod_prov="----------";
		$prov_sel="";
		$bene_sel="selected";
		$ning_sel="";	
	}
	else
	{
		$ls_fuente = "-";
		$int_int->is_cod_prov="----------";
		$int_int->is_ced_ben="----------";
		$prov_sel="";
		$bene_sel="";
		$ning_sel="selected";		
	}

	$lb_valido=$int_int->uf_init_delete($ls_codemp,$ls_procedencia,$ls_comprobante,$int_int->id_fecha,$ls_tipo,
	                                    $int_int->is_ced_ben,$int_int->is_cod_prov,false,$ls_codban,$ls_ctaban);
	if (!$lb_valido) 
    {	
	   $io_msg->message("Comprobante no existe");	
	}	
	else
	{
	    $lb_valido = $int_int->uf_int_init_transaction_begin();
		if(!$lb_valido)
		{
			$io_msg->message($int_int->is_msg_error);
		}	
		if($lb_valido)
		{	
			$lb_valido = $int_int->uf_init_end_transaccion_integracion($la_seguridad);
			if (!$lb_valido)
			{
				$io_msg->message("Error".$int_int->is_msg_error);
				$int_int->io_sql->rollback();
			}
			else
			{
				$io_msg->message("Comprobante eliminado satisfactoriamente");
 				$ls_comprobante =  $in_classcmp->uf_generar_num_cmp($la_emp["codemp"],'SPICMP');
				$ls_fecha     = date("d/m/Y");
				$ls_provbene  = "";
				$ls_desproben  = "";
				$ls_tipo      = "-";
				$ls_descripcion = "";				
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_desc_event="Elimino el comprobante presupuestario ".$ls_comprobante." de fecha ".$ld_fecha." y procedencia ".$ls_procedencia;
				$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
				////////////////////////////////         SEGURIDAD               //////////////////////////////
				$int_int->io_sql->commit();
			}
		}
    }	
	uf_cargar_dt($la_emp["codemp"],$ls_procedencia,$ls_comprobante,$ld_fecha,$ls_codban,$ls_ctaban);
}
if($ls_operacion=="DELETESPI")		
{
	$ls_comprobante= $_POST["txtcomprobante"];
	$ld_fecha      = $_POST["txtfecha"];
	$ls_proccomp   = $_POST["txtproccomp"];
	$ls_desccomp   = $_POST["txtdesccomp"];
	$ls_provbene   = $_POST["txtprovbene"];	
	$ls_tipo	   = $_POST["tipo"];
	$li_fila	   = $_POST["fila"];
	$ls_desproben  = $_POST["txtdesproben"];
	$ls_codban=$_POST["txtcodban"];
	$ls_ctaban=$_POST["txtctaban"];
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
	$ls_cuenta=$_POST["txtcuenta".$li_fila];
	if($li_estpreing==1)
	{
	   $ls_estructura =$_POST["txtestructura".$li_fila];
	} 
	$ls_procede_doc=$_POST["txtprocede".$li_fila];
	$ls_descripcion=$_POST["txtdescripcion".$li_fila];
	$ls_documento=$_POST["txtdocumento".$li_fila];
	$ls_operacion=$_POST["txtoperacion".$li_fila];
	$ldec_monto_anterior=$_POST["txtmonto".$li_fila];
	$ldec_monto_actual=0;
	$li_tipo_comp=1;
	
	$ls_mensaje=$io_int_spi->uf_operacion_codigo_mensaje($ls_operacion);
	$io_int_spi->is_codemp     = $la_emp["codemp"];
	$io_int_spi->id_fecha      = $io_function->uf_convertirdatetobd($ld_fecha);
	$io_int_spi->is_procedencia= $ls_proccomp;
	$io_int_spi->is_comprobante= $ls_comprobante;
	$io_int_spi->is_tipo       = $ls_tipo;
	$io_int_spi->is_cod_prov   = $ls_prov;
	$io_int_spi->is_ced_ben    = $ls_bene;
	$io_int_spi->ib_AutoConta  = true;
    $io_int_spi->is_codban     = $ls_codban;
	$io_int_spi->is_ctaban     = $ls_ctaban;

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
	
	if(!$io_int_spi->uf_spi_select_cuenta($la_emp["codemp"],$ls_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
	{  
	  return false;
	}
	if($li_estpreing==1)
	{
		$ls_codest1 = substr($ls_estructura,0,25);
		$ls_codest2 = substr($ls_estructura,25,25);
		$ls_codest3 = substr($ls_estructura,50,25);
		$ls_codest4 = substr($ls_estructura,75,25);
		$ls_codest5 = substr($ls_estructura,100,25);
		$ls_estcla6 = substr($ls_estructura,125,1);
	}
	else
	{
	    $ls_codest1 = '-------------------------';
		$ls_codest2 = '-------------------------';
		$ls_codest3 = '-------------------------';
		$ls_codest4 = '-------------------------';
		$ls_codest5 = '-------------------------';
		$ls_estcla6 = '-';
	}
	$lb_valido=$io_int_spi->uf_int_spi_delete_movimiento($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha,$ls_tipo,$ls_fuente,$ls_prov,$ls_bene,
													     $ls_cuenta,$ls_procede_doc,$ls_documento,$ls_descripcion,$ls_mensaje,$li_tipo_comp,
													     $ldec_monto_anterior,$ldec_monto_actual,$ls_sc_cuenta,$ls_codban,$ls_ctaban,$ls_codest1,$ls_codest2,$ls_codest3,
														 $ls_codest4,$ls_codest5,$ls_estcla6);
	if($lb_valido)
	{
		$io_int_spi->io_sql->commit();
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_desc_event="Elimino el movimiento presupuestario ".$ls_documento." con operacion".$ls_operacion." por un monto de ".$ldec_monto_anterior." para la cuenta ".$ls_cuenta."; para el comprobante ".$ls_comprobante." de fecha ".$ld_fecha;
		$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////
	}
	else
	{
 		$io_int_spi->io_sql->rollback();
	}
	uf_cargar_dt($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha,$ls_codban,$ls_ctaban);

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
	$ls_codban=$_POST["txtcodban"];
	$ls_ctaban=$_POST["txtctaban"];
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
	$io_int_scg->is_codban = $ls_codban;
	$io_int_scg->is_ctaban = $ls_ctaban;
						
	$lb_valido=$io_int_scg->uf_scg_procesar_delete_movimiento($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$io_int_scg->id_fecha, $ls_cuenta, $ls_procdoc, $ls_docscg,$ls_debhab, $ldec_monto_anterior,$ls_codban,$ls_ctaban);
	if($lb_valido)
	{
		$io_int_scg->io_sql->commit();
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_desc_event="Elimino el movimiento contable ".$ls_docscg." con operacion ".$ls_debhab." por un monto de ".
		$ldec_monto_anterior." para la cuenta ".$ls_cuenta."; para el comprobante ".$ls_comprobante." de fecha ".$ld_fecha;
		$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
		////////////////////////////////         SEGURIDAD               //////////////////////////////
	}
	uf_cargar_dt($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha,$ls_codban,$ls_ctaban);

}

function uf_cargar_dt($ls_codemp,$ls_proccomp,$ls_comprobante,$ld_fecha,$ls_codban,$ls_ctaban)
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
	global $ldec_totspi;
	global $ls_codban;
	global $ls_ctaban;
	global $ls_estructura;
	
	$ldec_totspi=0;
	$ldec_mondeb=0;
	$ldec_monhab=0;
	$ldec_diferencia=0;
	$i=0;
    $li_estpreing     = $la_emp["estpreing"];
	$ls_estmodest     = $la_emp["estmodest"];
	$li_loncodestpro1 = $la_emp["loncodestpro1"];
    $li_loncodestpro2 = $la_emp["loncodestpro2"];
	$li_loncodestpro3 = $la_emp["loncodestpro3"];
	$li_loncodestpro4 = $la_emp["loncodestpro4"];
	$li_loncodestpro5 = $la_emp["loncodestpro5"];
    $ls_estructura = "";
	$rs_dtcmp=$in_classcmp->uf_cargar_dt_comprobante($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha,$ls_codban,$ls_ctaban);

	$li_numrows=$in_classcmp->io_sql->num_rows($rs_dtcmp);
	$totalpre=1;
	$totalcon=1;
	if($li_numrows>0)
	{
	    $totalpre=$li_numrows;
		while($row=$in_classcmp->io_sql->fetch_row($rs_dtcmp))
		{
			$i=$i+1;
			$ls_cuenta=trim($row["spi_cuenta"]);			
			$ls_documento=trim($row["documento"]);
			$ls_descripcion=trim($row["descripcion"]);
			$ls_procede=trim($row["procede_doc"]);
			$ls_operacion=trim($row["operacion"]);
			$ldec_monto=$row["monto"];
			//----------------estructuras asociadas al ingresos--------------------------------------------------
				$ls_codestpro1=$row["codestpro1"]; 
				$ls_codestpro2=$row["codestpro2"]; 
				$ls_codestpro3=$row["codestpro3"]; 
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"];
				$ls_estcla=$row["estcla"];
				$ls_estructura =$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estcla;  
				$ls_codestpro1    = trim(substr($ls_codestpro1,-$li_loncodestpro1));
				$ls_codestpro2    = trim(substr($ls_codestpro2,-$li_loncodestpro2));
				$ls_codestpro3    = trim(substr($ls_codestpro3,-$li_loncodestpro3));
				if ($ls_estmodest==2)
				{  
				    $ls_denestcla="";
					$ls_codestpro4   = trim(substr($ls_codestpro4,-$li_loncodestpro4));
				    $ls_codestpro5   = trim(substr($ls_codestpro5,-$li_loncodestpro5));
					$ls_programatica = $ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3."-".$ls_codestpro4."-".$ls_codestpro5;
				}
				else
				{
					if ($ls_estcla=='P')
					{
						$ls_denestcla = 'Proyecto';
					}
					else
					{
					    $ls_denestcla = 'Acción Centralizada';
					}
					    $ls_programatica=$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
				}
			//--------------------------------------------------------------------------------------------------
			if ($li_estpreing==0)
			{
				$object[$i][1]="<input type=text name=txtcuenta".$i." value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				$object[$i][2]="<input type=text name=txtdocumento".$i." value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				$object[$i][3]="<input type=text name=txtdescripcion".$i." value='".$ls_descripcion."' class=sin-borde readonly style=text-align:center>";
				$object[$i][4]="<input type=text name=txtprocede".$i." value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
				$object[$i][5]="<input type=text name=txtoperacion".$i." value='".$ls_operacion."' class=sin-borde readonly style=text-align:center size=4 maxlength=3>";
				$object[$i][6]="<input type=text name=txtmonto".$i." value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right>";		
				$object[$i][7]="<a href=javascript:uf_delete_dt_presupuesto(".($i).");><img src=../shared/imagebank/tools15/eliminar.gif width=15 height=15 border=0 alt=Eliminar Detalle Presupuesto></a>";
			    $ldec_totspi = $ldec_totspi + $ldec_monto;
			}
			else
			{
				$object[$i][1]="<input type=text name=txtcuenta".$i." value='".$ls_cuenta."' class=sin-borde readonly style=text-align:center size=15 maxlength=20>";
				$object[$i][2]="<input type=text name=txtprogramatica".$i." value='".$ls_programatica."' class=sin-borde readonly style=text-align:center size=45 maxlength=15>
				                <input type=hidden name=txtestructura".$i." id=txtestructura".$i." value='".$ls_estructura."'>";
				$object[$i][3]="<input type=text name=txtdocumento".$i." value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				$object[$i][4]="<input type=text name=txtdescripcion".$i." value='".$ls_descripcion."' class=sin-borde readonly style=text-align:center>";
				$object[$i][5]="<input type=text name=txtprocede".$i." value='".$ls_procede."' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
				$object[$i][6]="<input type=text name=txtoperacion".$i." value='".$ls_operacion."' class=sin-borde readonly style=text-align:center size=4 maxlength=3>";
				$object[$i][7]="<input type=text name=txtmonto".$i." value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right>";		
				$object[$i][8]="<a href=javascript:uf_delete_dt_presupuesto(".($i).");><img src=../shared/imagebank/tools15/eliminar.gif width=15 height=15 border=0 alt=Eliminar Detalle Presupuesto></a>";
				$ldec_totspi = $ldec_totspi + $ldec_monto;
			}
		}
		$in_classcmp->io_sql->free_result($rs_dtcmp);
	}
	else
	{
	 	if ($li_estpreing==0)
	    {
			$object[1][1]="<input type=text name=txtcuenta1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$object[1][2]="<input type=text name=txtdocumento1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$object[1][3]="<input type=text name=txtdescripcion1 value='' class=sin-borde readonly style=text-align:center>";
			$object[1][4]="<input type=text name=txtprocede1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$object[1][5]="<input type=text name=txtoperacion1 value='' class=sin-borde readonly style=text-align:center size=4 maxlength=3>";
			$object[1][6]="<input type=text name=txtmonto1 value='' class=sin-borde readonly style=text-align:right>";		
			$object[1][7]="";
		}
		else
		{
		  	$object[1][1]="<input type=text name=txtcuenta1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=20>";
			$object[1][2]="<input type=text name=txtprogramatica1 value='' class=sin-borde readonly style=text-align:center size=25 maxlength=15>";
			$object[1][3]="<input type=text name=txtdocumento1 value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$object[1][4]="<input type=text name=txtdescripcion1 value='' class=sin-borde readonly style=text-align:center>";
			$object[1][5]="<input type=text name=txtprocede1 value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			$object[1][6]="<input type=text name=txtoperacion1 value='' class=sin-borde readonly style=text-align:center size=4 maxlength=3>";
			$object[1][7]="<input type=text name=txtmonto1 value='' class=sin-borde readonly style=text-align:right>";		
			$object[1][8]="";
		}
	}
$i=0;
$rs_dtscg=$in_classcmp->uf_cargar_dt_contable_cmp($la_emp["codemp"],$ls_proccomp,$ls_comprobante,$ld_fecha,$ls_codban,$ls_ctaban);
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
			
			$object2[$i][1]="<input type=text name=txtcontable".$i." value='".$ls_sc_cuenta."' class=sin-borde readonly style=text-align:center size=14 maxlength=14>";		
			$object2[$i][2]="<input type=text name=txtdocscg".$i." value='".$ls_documento."' class=sin-borde readonly style=text-align:center size=16 maxlength=15>";
			$object2[$i][3]="<input type=text name=txtdesdoc".$i." value='".$ls_desdoc."' class=sin-borde readonly style=text-align:center size=30 maxlength=254>";
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
			$object2[1][1]="<input type=text name=txtcontable1 value='' class=sin-borde readonly style=text-align:center size=14 maxlength=14>";		
			$object2[1][2]="<input type=text name=txtdocscg1 value='' class=sin-borde readonly style=text-align:center size=16 maxlength=15>";
			$object2[1][3]="<input type=text name=txtdesdoc1 value='' class=sin-borde readonly style=text-align:center size=30 maxlength=254>";
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
<table width="780" height="293" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-nuevo">
        <td height="20" colspan="3">Comprobante Presupuestario de Ingresos </td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="98" height="22">
        <p align="right"> Procedencia</p></td>
        <td width="519">
          <input name="txtproccomp" type="text" id="txtproccomp" value="<? print $ls_procede?>" readonly="true" style="text-align:center" >
          <input name="status_actual" type="hidden" id="status_actual" value="<? print $ls_status;?>">
          <input name="estcla" type="hidden" id="estcla" value="<?php print $ls_estcla;?>"></td>
        <td width="161"><div align="left">Fecha
            <input name="txtfecha" type="text" id="txtfecha" style="text-align:center"  value="<? print $ls_fecha?>" onKeyPress="currencyDate(this);" size="15" maxlength="10" <? print $readonly;?> datepicker="true">
        </div></td>
      </tr>
      <tr>
        <td height="22">
        <p align="right">Comprobante</p></td>
        <td><input name="txtcomprobante" type="text" id="txtcomprobante" onBlur="javascript: valid_cmp(document.form1.txtcomprobante.value);" maxlength="15" style="text-align:center" value="<? print $ls_comprobante ?>" <? print $readonly ;?>></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="22">
        <p align="right">Descripci&oacute;n </p></td>
        <td colspan="2"><input name="txtdesccomp" type="text" id="txtdesccomp" size="120" value="<? print $ls_descripcion;?>"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tipo</div></td>
        <td><p>
          <label>
          <input name="tipo" id="tipo" type="radio" class="sin-borde" value="P" <? print $prov_sel;?> onClick="javascript:uf_verificar_provbene();">
  Proveedor</label>
          <label>
          <input name="tipo" id="tipo" type="radio" class="sin-borde" value="B" <? print $bene_sel;?> onClick="javascript:uf_verificar_provbene();">
  Beneficiario</label>
          <label>
          <input name="tipo" id="tipo" type="radio" class="sin-borde" value="-" <? print $ning_sel;?> onClick="javascript:uf_verificar_provbene();">
  Ninguno</label>
          <input name="tipsel" type="hidden" id="tipsel" value="<? print $ls_tipo;?>">
          <br>
        </p></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="22">
        <p align="right">C&oacute;digo/C&eacute;dula</p></td>
        <td>
          <input name="txtprovbene" type="text" id="txtprovbene" style="text-align:center" value="<? print $ls_provbene?>" <? print $readonly;?>>
          <a href="javascript:catprovbene()"><img  src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>
          <input name="txtdesproben" type="text" id="txtdesproben" size="42" maxlength="250" class="sin-borde" value="<? print $ls_desproben;?>" ></td>
        <td>&nbsp;</td>
      </tr>
      <tr >
        <td height="22" colspan="3"><div align="center">
          <input type="hidden" name="txtcodban" id="txtcodban" value="---">
          <input type="hidden" name="txtctaban" id="txtctaban" value="-------------------------">
        </div></td>
      </tr>
      <tr >
        <td height="22" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: uf_agregar_dtpre();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Ingresos </a> </td>
      </tr>
        <tr>
        <td height="22" colspan="3">
		<div align="center"><? $io_grid->makegrid($totalpre,$title,$object,770,'Detalles Presupuestarios',$grid1);?>
		  <input name="totpre" type="hidden" id="totpre" value="<? print $totalpre?>">
		</div></td>
      </tr>
        <tr>
          <td height="17" colspan="3"><table width="777" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="607" height="22"><div align="right">Total Ingresos </div></td>
              <td width="170"><div align="center">
                <input name="txttotspg" type="text" id="txttotspg" value="<? print number_format($ldec_totspi,2,",",".");?>" size="28" style="text-align:right">
              </div></td>
            </tr>
          </table></td>
        </tr>
      <tr>
        <td height="22" colspan="3"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: uf_agregar_dtcon();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0"></a><a href="javascript: uf_agregar_dtcon();">Agregar detalle Contable </a> </p>        </td>
      </tr>
        <tr>
          <td height="22" colspan="3">
		  <div align="center"><? $io_grid->makegrid($totalcon,$title2,$object2,770,'Detalles Contable',$grid2);?> 
		    <input name="totcon" type="hidden" id="totcon" value="<? print $totalcon?>">
		  </div></td>
        </tr>
	  <br>
      <tr>
        <td height="73" colspan="3" valign="top" bordercolor="#FFFFFF"><table width="778" border="0" cellspacing="0" cellpadding="0" class="celdas-blancas">
            <tr>
              <td height="22">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><div align="right">Debe</div></td>
              <td><div align="center">
                <input name="txtdebe" type="text" id="txtdebe" style="text-align:right" value="<? print number_format($ldec_mondeb,2,",",".");?>" size="28" readonly>
              </div></td>
            </tr>
            <tr>
              <td height="22">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><div align="right">Haber</div></td>
              <td><div align="center">
                <input name="txthaber" type="text" id="txthaber" style="text-align:right" value="<? print number_format($ldec_monhab,2,",",".");?>" size="28" readonly>
              </div></td>
            </tr>
            <tr>
              <td width="77" height="22">&nbsp;</td>
              <td width="97">&nbsp;</td>
              <td width="88">&nbsp;</td>
              <td width="216"><div align="right"> </div></td>
              <td width="130"><div align="center"></div>
                  <div align="right">Diferencia</div></td>
              <td width="170"><div align="center">
                  <p>
                    <input name="txtdiferencia" type="text" id="txtdiferencia" style="text-align:right" value="<? print number_format($ldec_diferencia,2,",",".") ;?>" size="28" readonly>
                  </p>
              </div></td>
            </tr>
        </table></td>
      </tr>
    </table>
  <input name="operacion" type="hidden" id="operacion">
    <input name="totalpre" type="hidden" id="totalpre" value="<? print $totpre; ?>" >
    <input name="totalcon" type="hidden" id="totalcon" value="<? print $totcon; ?>" > 
    <input name="fila" type="hidden" id="fila" value="<? print $li_fila;?>">
</div>
</form>
</body>
<script language="javascript">
//Funcion de carga de Catalogos

function catprovbene()
{
f=document.form1;
if(f.tipo[0].checked==true)
{
	f.txtprovbene.disabled=false;	
	window.open("sigesp_catdinamic_prov.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=525,height=400,left=50,top=50,location=no,resizable=yes");
}
else if(f.tipo[1].checked==true)
{
	f.txtprovbene.disabled=false;	
	window.open("sigesp_catdinamic_bene.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=525,height=400,left=50,top=50,location=no,resizable=yes");
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
	f.action="sigesp_spi_p_comprobante.php";
	f.txtcuenta.focus(true	);
	f.submit();

}

function uf_save_mov()
{
	f=document.form1;
	f.operacion.value="AGREGAR";
	f.action="sigesp_spi_p_comprobante.php";
	f.submit();
}
function uf_cargar_dt()
{
	f=document.form1;
	f.operacion.value="CARGAR";
	f.action="sigesp_spi_p_comprobante.php";
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
	f.action="sigesp_spi_p_comprobante.php";
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
	f.action="sigesp_spi_p_comprobante.php";
	f.submit();
}
//Funciones de operaciones sobre el comprobante
function ue_nuevo()
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
		f.operacion.value="NUEVO";
		f.action="sigesp_spi_p_comprobante.php";
		f.submit();
	}
}
function ue_guardar()
{
	f=document.form1;
	ls_procede=f.txtproccomp.value;
	if(ls_procede=="SPICMP")
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
				f.action="sigesp_spi_p_comprobante.php";
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
	if(ls_procede=="SPICMP")
	{
		if(confirm("Seguro desea eliminar el comprobante"))
		{
			f.operacion.value="ELIMINAR";
			f.action="sigesp_spi_p_comprobante.php";
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
	ldec_diferencia=f.txtdiferencia.value;
	ldec_diferencia=uf_convertir_monto(ldec_diferencia);
	if(ldec_diferencia!=0)
	{
		alert("Comprobante descuadrado");
	}
	else
	{
		window.open("sigesp_cat_comprobantesspi.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
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
f.action="sigesp_spi_p_comprobante.php";
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
		ls_tipo='-'
	}
	if(ls_proccomp=="SPICMP")
	{
		if((ls_comprobante!="")&&(ls_proccomp!="")&&(ld_fecha!="")&&(ls_provbene!="")&&(ls_tipo))
		{
			ls_pagina = "sigesp_w_regdt_ingreso.php?procede="+ls_proccomp+"&comprobante="+ls_comprobante+"&fecha="+ld_fecha+"&descripcion="+ls_desccomp+"&tipo="+ls_tipo+"&provbene="+ls_provbene;
			window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=585,height=280,left=50,top=50,location=no,resizable=no,dependent=yes");
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
    function  uf_agregar_dtcon()
   {
   
	f=document.form1;
	ls_comprobante= f.txtcomprobante.value;
	ld_fecha      = f.txtfecha.value;
	ls_proccomp   = f.txtproccomp.value;
	ls_desccomp   = f.txtdesccomp.value;
	ls_provbene   = f.txtprovbene.value;	
	if (f.tipo[0].checked==true)
	   {
	     ls_tipo = f.tipo[0].value; 
	   }
	else
	   {
	     if (f.tipo[1].checked==true)
		    {
			  ls_tipo = f.tipo[1].value; 
			}
	     else
		    {
			  ls_tipo = f.tipo[2].value; 
			}
	   }
	if(ls_proccomp=="SPICMP")
	{
		if((ls_comprobante!="")&&(ls_proccomp!="")&&(ld_fecha!="")&&(ls_provbene!="")&&(ls_tipo!=""))
		{
			ls_pagina = "sigesp_w_regdt_contable.php?procede="+ls_proccomp+"&comprobante="+ls_comprobante+"&fecha="+ld_fecha+"&descripcion="+ls_desccomp+"&tipo="+ls_tipo+"&provbene="+ls_provbene;
			window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=570,height=200,left=50,top=50,location=no,resizable=no,dependent=yes");
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
	  if(ls_procede=="SPICMP")
	  {
		  f.action="sigesp_spi_p_comprobante.php";
		  f.operacion.value="DELETESPI";
		 // grid_SPG.deleteRow(row);
		  f.fila.value=row;
		  f.submit();
	   }
	   else
	   {
	     alert("No puede editar un comprobante que no fue generado por este módulo");
	   }
    }  
  
   function uf_delete_dt_contable(row)
   {
	  f=document.form1;
	  ls_procede=f.txtproccomp.value;
	  if(ls_procede=="SPICMP")
	  {
		  f.action="sigesp_spi_p_comprobante.php";
		  f.operacion.value="DELETESCG";
		 // grid_SPG.deleteRow(row);
		  f.fila.value=row;
		  f.submit();
	  }
	  else
	  {
	  	  alert("No puede editar un comprobante que no fue generado por este módulo");
	  }
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
}
   
function currencyFormat(fld, milSep, decSep, e) 
{ 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789-'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Enter 
	if (whichCode == 127) return true; // Enter 	
	if (whichCode == 9) return true; // Enter 	
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
      fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
    return false; 
   }
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>