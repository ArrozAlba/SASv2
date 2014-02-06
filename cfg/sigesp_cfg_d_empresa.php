<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='sigesp_conexion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Registro de Empresa</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="javascript" src="../shared/js/number_format.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style6 {color: #000000}
.Estilo1 {
	color: #6699CC;
	font-size: 14px;
}
-->
</style></head>
<body link="#006699" vlink="#006699" alink="#006699">
<a name="top"></a>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Configuraci&oacute;n</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-negrita"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right" class="letras-negrita"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
  <p>
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("class_folder/sigesp_cfg_c_empresa.php");

$io_conect  = new sigesp_include();
$conn       = $io_conect-> uf_conectar ();
$la_emp     = $_SESSION["la_empresa"];
$io_msg     = new class_mensajes(); //Instanciando  la clase mensajes 
$io_sql     = new class_sql($conn); //Instanciando  la clase sql
$io_funcion = new class_funciones(); 
$io_fecha   = new class_fecha();
$io_empresa = new sigesp_cfg_c_empresa($conn);

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "CFG";
	$ls_ventanas = "sigesp_cfg_d_empresa.php";

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	   {	
		 if ($ls_logusr=="PSEGIS")
		    {
			  $ls_permisos="";
		    }
		 else
		    {
			  $ls_permisos            = $_POST["permisos"];
			  $la_accesos["leer"]     = $_POST["leer"];
			  $la_accesos["incluir"]  = $_POST["incluir"];
			  $la_accesos["cambiar"]  = $_POST["cambiar"];
			  $la_accesos["eliminar"] = $_POST["eliminar"];
			  $la_accesos["imprimir"] = $_POST["imprimir"];
			  $la_accesos["anular"]   = $_POST["anular"];
			  $la_accesos["ejecutar"] = $_POST["ejecutar"];
		    }
	   }
	else
	   {
	     $la_accesos["leer"]     = "";
		 $la_accesos["incluir"]  = "";
		 $la_accesos["cambiar"]  = "";
		 $la_accesos["eliminar"] = "";
		 $la_accesos["imprimir"] = "";
		 $la_accesos["anular"]   = "";
		 $la_accesos["ejecutar"] = "";
		 $ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion = $_POST["operacion"];  
     $ls_readonly  = "";   
     $ls_numlicemp = $_POST["txtnumlicemp"];
     $ls_modgenret = $_POST["radiocmp"];
   } 
else
   {
     $ls_readonly  = 'readonly';   
     $ls_operacion = "";
     $ls_proyecto  = "checked";
     $ls_numlicemp = "";
     $ls_modgenret = 'B';
   }
$lr_datos['modgenret'] = $ls_modgenret;  
if ($ls_modgenret=='B')
   {
     $ls_impretban = 'checked';
     $ls_impretcxp = '';
   }   
else
   {
     $ls_impretban = '';
     $ls_impretcxp = 'checked';
   } 
if (array_key_exists("radiocmpiva",$_POST))
   {
      $ls_estretiva = $_POST["radiocmpiva"];
	  $lr_datos['estretiva'] = $ls_estretiva;
   }
else
   {
      $ls_estretiva = 'C';
	  $lr_datos['estretiva'] = $ls_estretiva;  
   }
if ($ls_operacion=='BUSCAR')
{
  $ls_disacompiva = 'disabled';
  $ls_disapertura = 'disabled'; 
}  
else
{
  $ls_disacompiva = 'enabled';
  $ls_disapertura = 'enabled'; 
} 

if ($ls_estretiva=='B')
   {
     $ls_compivaban = 'checked';
     $ls_compivacxp = '';
   }   
else
   {
     if ($ls_estretiva=='C') 
	 { 
		$ls_compivaban = '';
		$ls_compivacxp = 'checked';
	 }
	
   }
if (array_key_exists("modaper",$_POST))
   {
     $ls_modaper     = $_POST["modaper"];
     $ls_disapertura = 'disabled';
     $ls_distipcont = 'disabled';
  }
else
   {
     $ls_modaper     = false;
	 $ls_disapertura = '';
     $ls_distipcont = '';
   }
if (array_key_exists("hiddisabled",$_POST))
   {
     $li_disabled = $_POST["hiddisabled"];
   }
else
   {
	 $li_disabled = '0';
   }   
if  (array_key_exists("txtcodigo",$_POST))
	{
  	  $ls_codigo=$_POST["txtcodigo"];
      $lr_datos["codemp"]=$ls_codigo;
	}
else
	{
	  $ls_codigo="";
	}
if  (array_key_exists("txtnumlicemp",$_POST))
	{
  	  $ls_numlicemp          = $_POST["txtnumlicemp"];
      $lr_datos["numlicemp"] = $ls_numlicemp;
	}
else
	{
	  $ls_numlicemp = "";
	}
if (array_key_exists("txtnombre",$_POST))
   {
     $ls_nombre=$_POST["txtnombre"];
     $lr_datos["nombre"]=$ls_nombre;
   }
else
   {
     $ls_nombre="";
   }
if (array_key_exists("txtnomres",$_POST))
   {
     $ls_nomres=$_POST["txtnomres"];
     $lr_datos["nomres"]=$ls_nomres;
   }
else
   {
     $ls_nomres="";
   }
if (array_key_exists("txttitulo",$_POST))
   {
     $ls_titulo=$_POST["txttitulo"];
     $lr_datos["titulo"]=$ls_titulo;
   }
else
   {
     $ls_titulo="";
   }
if (array_key_exists("txtdireccion",$_POST))
   {
     $ls_direccion=$_POST["txtdireccion"];
     $lr_datos["direccion"]=$ls_direccion;
   }
else
   {
     $ls_direccion="";
   }
if (array_key_exists("txtciuemp",$_POST))
   {
     $ls_ciuemp=$_POST["txtciuemp"];
     $lr_datos["ciuemp"]=$ls_ciuemp;
   }
else
   {
     $ls_ciuemp="";
   }
if (array_key_exists("txtestemp",$_POST))
   {
     $ls_estemp=$_POST["txtestemp"];
     $lr_datos["estemp"]=$ls_estemp;
   }
else
   {
     $ls_estemp="";
   }
if (array_key_exists("txtzonpos",$_POST))
   {
     $ls_zonpos=$_POST["txtzonpos"];
     $lr_datos["zonpos"]=$ls_zonpos;
   }
else
   {
     $ls_zonpos="";
   }
if (array_key_exists("txttelefono",$_POST))
   {
     $ls_telefono=$_POST["txttelefono"];
     $lr_datos["telefono"]=$ls_telefono;
   }
else
   {
     $ls_telefono="";
   }               
if (array_key_exists("txtfax",$_POST))
   {
     $ls_fax=$_POST["txtfax"];
     $lr_datos["fax"]=$ls_fax;
   }
else
   {
     $ls_fax="";
   }               
if (array_key_exists("txtemail",$_POST))
   {
     $ls_email=$_POST["txtemail"];
     $lr_datos["email"]=$ls_email;
   }
else
   {
     $ls_email="";
   }               
if (array_key_exists("txtwebsite",$_POST))
   {
     $ls_website=$_POST["txtwebsite"];
     $lr_datos["website"]=$ls_website;
   }
else
   {
     $ls_website="";
   }
if (array_key_exists("txtperiodo",$_POST))
   {
      $ls_periodo=$_POST["txtperiodo"];
	  $lr_datos["periodo"]=$ls_periodo;
   }
else
   {
     $ls_periodo="";
   }
if (array_key_exists("txtplanunico",$_POST))
   {
     $ls_planunico=$_POST["txtplanunico"];
     $lr_datos["planunico"]=$ls_planunico;
   }
else
   {
     $ls_planunico="";
   }
if (array_key_exists("txtpgasto",$_POST))
   {
     $ls_pgasto=$_POST["txtpgasto"];
     $lr_datos["pgasto"]=$ls_pgasto;
   }
else
   {
     $ls_pgasto="";
   }
if (array_key_exists("txtcontabilidad",$_POST))
   {
     $ls_contabilidad=$_POST["txtcontabilidad"];
     $lr_datos["contabilidad"]=$ls_contabilidad;
   }
else
   {
     $ls_contabilidad="";
   }   
if (array_key_exists("txtpingreso",$_POST))
   {
     $ls_pingreso=$_POST["txtpingreso"];
     $lr_datos["pingreso"]=$ls_pingreso;
   }
else
   {
     $ls_pingreso="";
   }      
if (array_key_exists("txtactivo",$_POST))
   {
     $ls_activo=$_POST["txtactivo"];
     $lr_datos["activo"]=$ls_activo;
   }
else
   {
     $ls_activo="";
   }      
if (array_key_exists("txtpasivo",$_POST))
   {
     $ls_pasivo=$_POST["txtpasivo"];
     $lr_datos["pasivo"]=$ls_pasivo;
   }
else
   {
     $ls_pasivo="";
   } 
if (array_key_exists("txtingreso",$_POST))
   {
     $ls_ingreso=$_POST["txtingreso"];
     $lr_datos["ingreso"]=$ls_ingreso;
   }
else
   {
     $ls_ingreso="";
   } 
if (array_key_exists("txtgasto",$_POST))
   {
     $ls_gasto=$_POST["txtgasto"];
     $lr_datos["gasto"]=$ls_gasto;
   }
else
   {
     $ls_gasto="";
   } 
if (array_key_exists("txtresultado",$_POST))
   {
     $ls_resultado=$_POST["txtresultado"];
     $lr_datos["resultado"]=$ls_resultado;
   }
else
   {
     $ls_resultado="";
   }                    
if (array_key_exists("txtcapital",$_POST))
   {
     $ls_capital=$_POST["txtcapital"];
     $lr_datos["capital"]=$ls_capital;
   }
else
   {
     $ls_capital="";
   }                
if (array_key_exists("txtordendeudor",$_POST))
   {
     $ls_ordendeudor=$_POST["txtordendeudor"];
     $lr_datos["ordendeudor"]=$ls_ordendeudor;
   }
else
   {
     $ls_ordendeudor="";
   }                    
if (array_key_exists("txtordenacreedor",$_POST))
   {
     $ls_ordenacreedor=$_POST["txtordenacreedor"];
     $lr_datos["ordenacreedor"]=$ls_ordenacreedor;
   }
else
   {
     $ls_ordenacreedor="";
   }
if (array_key_exists("txtpresupuestogasto",$_POST))
   {
     $ls_presupuestogasto=$_POST["txtpresupuestogasto"];
     $lr_datos["presupuestogasto"]=$ls_presupuestogasto;
   }
else
   {
     $ls_presupuestogasto="";
   }                    
if (array_key_exists("txtpresupuestoingreso",$_POST))
   {
     $ls_presupuestoingreso=$_POST["txtpresupuestoingreso"];
     $lr_datos["presupuestoingreso"]=$ls_presupuestoingreso;
   }
else
   {
     $ls_presupuestoingreso="";
   }
if (array_key_exists("txthaciendaactivo",$_POST))
   {
     $ls_haciendaactivo=$_POST["txthaciendaactivo"];
     $lr_datos["haciendaactivo"]=$ls_haciendaactivo;
   }
else
   {
     $ls_haciendaactivo="";
   }                    
if (array_key_exists("txthaciendapasivo",$_POST))
   {
     $ls_haciendapasivo=$_POST["txthaciendapasivo"];
     $lr_datos["haciendapasivo"]=$ls_haciendapasivo;
   }
else
   {
     $ls_haciendapasivo="";
   }
if (array_key_exists("txthaciendaresul",$_POST))
   {
     $ls_haciendaresul=$_POST["txthaciendaresul"];
     $lr_datos["haciendaresul"]=$ls_haciendaresul;  
   }
else
   {
     $ls_haciendaresul="";
   }
if (array_key_exists("txtfiscalgasto",$_POST))
   {
     $ls_fiscalgasto=$_POST["txtfiscalgasto"];
     $lr_datos["fiscalgasto"]=$ls_fiscalgasto;  
   }
else
   {
     $ls_fiscalgasto="";
   }                    
if (array_key_exists("txtingresofiscal",$_POST))
   {
     $ls_fiscalingreso=$_POST["txtingresofiscal"];
     $lr_datos["fiscalingreso"]=$ls_fiscalingreso;  
   }
else
   {
     $ls_fiscalingreso="";
   }
if (array_key_exists("txtresultadoactual",$_POST))
   {
     $ls_resultadoactual=$_POST["txtresultadoactual"];
     $lr_datos["resultadoactual"]=$ls_resultadoactual;  
   }
else
   {
     $ls_resultadoactual="";
   }
if (array_key_exists("txtresultadoanterior",$_POST))
   {
     $ls_resultadoanterior=$_POST["txtresultadoanterior"];
     $lr_datos["resultadoanterior"]=$ls_resultadoanterior;
   }
else
   {
     $ls_resultadoanterior="";
   }
if (array_key_exists("txtdesestpro1",$_POST))
   {
     $ls_desestpro1          = $_POST["txtdesestpro1"];
     $lr_datos["desestpro1"] = $ls_desestpro1;
   }
else
   {
     $ls_desestpro1="";
     $lr_datos["desestpro1"] = $ls_desestpro1;
   }                    
if (array_key_exists("txtdesestpro2",$_POST))
   {
     $ls_desestpro2          = $_POST["txtdesestpro2"];
     $lr_datos["desestpro2"] = $ls_desestpro2;
   }
else
   {
     $ls_desestpro2          = "";
     $lr_datos["desestpro2"] = $ls_desestpro2;
   }
if (array_key_exists("txtdesestpro3",$_POST))
   {
     $ls_desestpro3=$_POST["txtdesestpro3"];
     $lr_datos["desestpro3"]=$ls_desestpro3;
   }
else
   {
     $ls_desestpro3          = "";
     $lr_datos["desestpro3"] = $ls_desestpro3;
   }                    
if (array_key_exists("txtdesestpro4",$_POST))
   {
     $ls_desestpro4=$_POST["txtdesestpro4"];
     $lr_datos["desestpro4"]=$ls_desestpro4;
   }
else
   {
     $ls_desestpro4          = "";
     $lr_datos["desestpro4"] = $ls_desestpro4;
   }
if (array_key_exists("txtdesestpro5",$_POST))
   {
     $ls_desestpro5=$_POST["txtdesestpro5"];
     $lr_datos["desestpro5"]=$ls_desestpro5;
   }
else
   {
     $ls_desestpro5          = "";
     $lr_datos["desestpro5"] = $ls_desestpro5;
   }
if (array_key_exists("txtcuentabienes",$_POST))
   {
     $ls_cuentabienes          = $_POST["txtcuentabienes"];
     $lr_datos["cuentabienes"] = $ls_cuentabienes;
   }
else
   {
     $ls_cuentabienes="";
   }
if (array_key_exists("txtcuentaservicios",$_POST))
   {
     $ls_cuentaservicios          = $_POST["txtcuentaservicios"];
     $lr_datos["cuentaservicios"] = $ls_cuentaservicios;
   }
else
   {
     $ls_cuentaservicios="";
   }
if (array_key_exists("chkestdesiva",$_POST))
   {
     $li_estdesiva          = $_POST["chkestdesiva"];
     $lr_datos["estdesiva"] = $li_estdesiva;
     $ls_desagregar         = 'checked'; 
   }
else
   {
     $li_estdesiva          = 0;
     $lr_datos["estdesiva"] = $li_estdesiva;
     $ls_desagregar         = ''; 
   }
if (array_key_exists("chkprecomprometer",$_POST))
   {
     $li_precomprometer          = $_POST["chkprecomprometer"];
     $lr_datos["precomprometer"] = $li_precomprometer;
     $ls_comprometer             = 'checked';
   }
else
   {
     $li_precomprometer          = 0;
     $lr_datos["precomprometer"] = $li_precomprometer;
     $ls_comprometer             = '';
   }
   
if (array_key_exists("chkenero",$_POST))
   {
     $li_enero          = $_POST["chkenero"];
     $lr_datos["enero"] = $li_enero;
     $ls_enero          = "checked";
   }
else
   { 
     $li_enero          = 0;
     $lr_datos["enero"] = $li_enero;
     $ls_enero          = "";
   }
if (array_key_exists("chkfebrero",$_POST))
   {
     $li_febrero          = $_POST["chkfebrero"];
     $lr_datos["febrero"] = $li_febrero;
     $ls_febrero          = "checked";
   }
else
   {
     $li_febrero          = 0;
     $lr_datos["febrero"] = $li_febrero;
     $ls_febrero          = "";
   }
if (array_key_exists("chkmarzo",$_POST))
   {
     $li_marzo          = $_POST["chkmarzo"];
     $lr_datos["marzo"] = $li_marzo;
     $ls_marzo          = "checked";
   }
else
   {
     $li_marzo          = 0;
     $lr_datos["marzo"] = $li_marzo;
     $ls_marzo          = "";
   }
if (array_key_exists("chkabril",$_POST))
   {
     $li_abril          = $_POST["chkabril"];
     $lr_datos["abril"] = $li_abril;
     $ls_abril          = "checked";
   }
else
   {
     $li_abril=0;
     $lr_datos["abril"]=$li_abril;
	 $ls_abril="";
   }
if (array_key_exists("chkmayo",$_POST))
   {
     $li_mayo=$_POST["chkmayo"];
     $lr_datos["mayo"]=$li_mayo;
	 $ls_mayo="checked";
   }
else
   {
     $li_mayo=0;
     $lr_datos["mayo"]=$li_mayo;
	 $ls_mayo="";
   }
if (array_key_exists("chkjunio",$_POST))
   {
     $li_junio=$_POST["chkjunio"];
     $lr_datos["junio"]=$li_junio;
	 $ls_junio="checked";
   }
else
   {
     $li_junio=0;
     $lr_datos["junio"]=$li_junio;
	 $ls_junio="";
   }
   if (array_key_exists("chkjulio",$_POST))
   {
     $li_julio=$_POST["chkjulio"];
     $lr_datos["julio"]=$li_julio;
     $ls_julio="checked";
   }
else
   {
     $li_julio=0;
     $lr_datos["julio"]=$li_julio;
	 $ls_julio="";
   }
if (array_key_exists("chkagosto",$_POST))
   {
     $li_agosto=$_POST["chkagosto"];
     $lr_datos["agosto"]=$li_agosto;
     $ls_agosto="checked";
   }
else
   {
     $li_agosto=0;
     $lr_datos["agosto"]=$li_agosto;
	 $ls_agosto="";
   }
if (array_key_exists("chkseptiembre",$_POST))
   {
     $li_septiembre=$_POST["chkseptiembre"];
     $lr_datos["septiembre"]=$li_septiembre;
	 $ls_septiembre="checked";
   }
else
   {
     $li_septiembre=0;
     $lr_datos["septiembre"]=$li_septiembre;
	 $ls_septiembre="";
   }
   
   if (array_key_exists("chkoctubre",$_POST))
   {
     $li_octubre=$_POST["chkoctubre"];
     $lr_datos["octubre"]=$li_octubre;
	 $ls_octubre="checked";
   }
else
   {
     $li_octubre=0;
     $lr_datos["octubre"]=$li_octubre;
	 $ls_octubre="";
   }
if (array_key_exists("chknoviembre",$_POST))
   {
     $li_noviembre=$_POST["chknoviembre"];
     $lr_datos["noviembre"]=$li_noviembre;
	 $ls_noviembre="checked";
   }
else
   {
     $li_noviembre=0;
     $lr_datos["noviembre"]=$li_noviembre;
	 $ls_noviembre="";
   }
if (array_key_exists("chkdiciembre",$_POST))
   {
     $li_diciembre=$_POST["chkdiciembre"];
     $lr_datos["diciembre"]=$li_diciembre;
  	 $ls_diciembre="checked";
   }
else
   {
     $li_diciembre=0; 
     $lr_datos["diciembre"]=$li_diciembre;
	 $ls_diciembre="";
  }
if (array_key_exists("chkvalidacion",$_POST))
   {
     $li_estvaltra=$_POST["chkvalidacion"];
     $lr_datos["estvaltra"]=$li_estvaltra;
	 $ls_checked = 'checked';
   }
else
   {
     $li_estvaltra=0;
     $lr_datos["estvaltra"]=$li_estvaltra;
     $ls_checked = '';
   }
if (array_key_exists("cmbvalidacion",$_POST))
   {
     $li_nivelval=$_POST["cmbvalidacion"];
     $lr_datos["nivelval"]=$li_nivelval;
   }
else
   {
     $li_nivelval=1;
   }
if  (array_key_exists("radiocontabilidad",$_POST))
	{
	  $li_tipocontabilidad=$_POST["radiocontabilidad"];
    }
else
	{
	  $li_tipocontabilidad=1;
	}
if (array_key_exists("hidtipcont",$_POST))
   {
     $ls_hidtipcont  = $_POST["hidtipcont"];
   }
else
   {
     $ls_hidtipcont  = $li_tipocontabilidad;
   }
if ($ls_hidtipcont==1)
   {
     $ls_contabilidad_general="checked";
     $ls_contabilidad_fiscal="";
	 $lr_datos["tipocontabilidad"]=$ls_hidtipcont;
   }
elseif ($ls_hidtipcont==2)
   {
     $ls_contabilidad_general="";
     $ls_contabilidad_fiscal="checked";
	 $lr_datos["tipocontabilidad"]=$ls_hidtipcont;
   } 
     $ls_mensual="";
     $ls_trimestral="";
if  (array_key_exists("hidestmodape",$_POST))
	{
	  $li_estmodape=$_POST["hidestmodape"];
	  $lr_datos["estmodape"]=$li_estmodape;
	  if ($li_estmodape=='0')
	  {
	     $ls_mensual="checked";
		 $ls_trimestral="";
	  }
	  else
	  {
    	 $ls_trimestral="checked";
		 $ls_mensual="";
	  }
    }
else
	{
	  $li_estmodape=0;
 	  $lr_datos["estmodape"]=$li_estmodape;
	     $ls_mensual="checked";
     	 $ls_trimestral="";
	}
   if(array_key_exists("hid_roscg",$_POST))
   {
   		$ls_readonly_scg=$_POST["hid_roscg"];		
   }
   else
   {
   		$ls_readonly_scg="";		
   }
   
   if(array_key_exists("hid_rospg",$_POST))
   {
   		$ls_readonly_spg=$_POST["hid_rospg"];		
   }
   else
   {
   		$ls_readonly_spg="";		
   }
if (array_key_exists("txtcodorg",$_POST))
   {
     $ls_codorg             = $_POST["txtcodorg"];		
   	 $lr_datos["codorgsig"] = $ls_codorg;
  }
else
   {
 	 $ls_codorg = "";		
   }
if (array_key_exists("txtrif",$_POST))
   {
     $ls_rif              = $_POST["txtrif"];		
   	 $lr_datos["rifemp"] = $ls_rif;
  }
else
   {
 	 $ls_rif = "";		
   }
if (array_key_exists("txtnit",$_POST))
   {
     $ls_nit             = $_POST["txtnit"];		
   	 $lr_datos["nitemp"] = $ls_nit;
   }
else
   {
 	 $ls_nit = "";		
   }

if (array_key_exists("txtnit",$_POST))
   {
     $ls_ivss             = $_POST["txtivss"];		
   	 $lr_datos["nroivss"] = $ls_ivss;
   }
else
   {
 	 $ls_ivss = "";		
   }
   
if (array_key_exists("txtsalinipro",$_POST))
   {
     $ld_salinipro          = $_POST["txtsalinipro"];		
   	 $lr_datos["salinipro"] = $ld_salinipro;
  }
else
   {
 	 $ld_salinipro = "0,00";		
   }
if (array_key_exists("txtsalinieje",$_POST))
   {
     $ld_salinieje          = $_POST["txtsalinieje"];		
   	 $lr_datos["salinieje"] = $ld_salinieje;
  }
else
   {
 	 $ld_salinieje = "0,00";		
   }
if (array_key_exists("hidnumniv",$_POST))
   {
     $li_numnivest          = $_POST["hidnumniv"];		
   	 $lr_datos["numnivest"] = $li_numnivest;
  }
else
   {
 	 $li_numnivest          = "1";		
  	 $lr_datos["numnivest"] = $li_numnivest;
   }
if (array_key_exists("hidestmodest",$_POST))
   {
	 $li_estmodest          = $_POST["hidestmodest"];		
   	 $lr_datos["estmodest"] = $li_estmodest;
   }
else
   {
	 $li_estmodest          = "1";		
   	 $lr_datos["estmodest"] = $li_estmodest;
   }
if ($li_estmodest==1)
   {
     $ls_proyecto = "checked";
     $ls_programa = "";
   }
else
   {
     $ls_proyecto = "";
     $ls_programa = "checked";
   }
if (array_key_exists("txtnumordser",$_POST))
   {
     $ls_numordser          = $_POST["txtnumordser"];		
   	 $lr_datos["numordser"] = $ls_numordser;
  }
else
   {
 	 $ls_numordser = "";		
   } 
if (array_key_exists("txtnumordcom",$_POST))
   {
     $ls_numordcom          = $_POST["txtnumordcom"];		
   	 $lr_datos["numordcom"] = $ls_numordcom;
  }
else
   {
 	 $ls_numordcom = "";		
   }
if (array_key_exists("txtnumsolpag",$_POST))
   {
     $ls_numsolpag          = $_POST["txtnumsolpag"];		
   	 $lr_datos["numsolpag"] = $ls_numsolpag;
  }
else
   {
 	 $ls_numsolpag = "";		
   }
if (array_key_exists("txtnomorgads",$_POST))
   {
     $ls_nomorgads          = $_POST["txtnomorgads"];		
   	 $lr_datos["nomorgads"] = $ls_nomorgads;
  }
else
   {
 	 $ls_nomorgads = "";		
   } 
if (array_key_exists("txtconcomiva",$_POST))
   {
     $ls_concomiva          = $_POST["txtconcomiva"];		
   	 $lr_datos["concomiva"] = $ls_concomiva;
  }
else
   {
 	 $ls_concomiva = "";		
   }  
if (array_key_exists("txtconcommun",$_POST))
   {
     $ls_concommun          = $_POST["txtconcommun"];		
   	 $lr_datos["concommun"] = $ls_concommun;
  }
else
   {
 	 $ls_concommun = "";		
   }  
if (array_key_exists("chkestmodiva",$_POST))
   {
     $ls_estmodiva       = $_POST["chkestmodiva"];
     $lr_datos["estmodiva"] = $ls_estmodiva;
     $ls_estmodiva       = "checked";
   }
else
   {
     $ls_estmodiva          = 0;
     $lr_datos["estmodiva"] = $ls_estmodiva;
     $ls_estmodiva          = "";
   }
if (array_key_exists("txtcedben",$_POST))
   {
     $ls_cedben          = $_POST["txtcedben"];		
   	 $lr_datos["cedben"] = $ls_cedben;
  }
else
   {
 	 $ls_cedben = "";		
   }   
if (array_key_exists("txtnomben",$_POST))
   {
     $ls_nomben          = $_POST["txtnomben"];		
   	 $lr_datos["nomben"] = $ls_nomben;
  }
else
   {
 	 $ls_nomben = "";		
   }  
if (array_key_exists("txtscctaben",$_POST))
   {
     $ls_scctaben          = $_POST["txtscctaben"];		
   	 $lr_datos["scctaben"] = $ls_scctaben;
  }
else
   {
 	 $ls_scctaben = "";		
   } 
if (array_key_exists("txttesoroactivo",$_POST))
   {
     $ls_tesoroactivo          = $_POST["txttesoroactivo"];		
   	 $lr_datos["tesoroactivo"] = $ls_tesoroactivo;
  }
else
   {
 	 $ls_tesoroactivo = "";		
   }    
if (array_key_exists("txttesoropasivo",$_POST))
   {
     $ls_tesoropasivo          = $_POST["txttesoropasivo"];		
   	 $lr_datos["tesoropasivo"] = $ls_tesoropasivo;
  }
else
   {
 	 $ls_tesoropasivo = "";		
   }  
if (array_key_exists("txttesororesul",$_POST))
   {
     $ls_tesororesul          = $_POST["txttesororesul"];		
   	 $lr_datos["tesororesul"] = $ls_tesororesul;
  }
else
   {
 	 $ls_tesororesul = "";		
   }     
if (array_key_exists("txtctafinanciera",$_POST))
   {
     $ls_ctafinanciera          = $_POST["txtctafinanciera"];		
   	 $lr_datos["c_financiera"]  = $ls_ctafinanciera;
  }
else
   {
 	 $ls_ctafinanciera = "";		
   }  
if (array_key_exists("txtctafiscal",$_POST))
   {
     $ls_ctafiscal          = $_POST["txtctafiscal"];		
   	 $lr_datos["c_fiscal"]  = $ls_ctafiscal;
  }
else
   {
 	 $ls_ctafiscal = "";		
   }
if (array_key_exists("txtcodasiona",$_POST))
   {
     $ls_codasiona = $_POST["txtcodasiona"];
     $lr_datos["codasiona"] = $ls_codasiona;
   }
else
   {
     $ls_codasiona = "";
   } 
   
   
   if (array_key_exists("txtlonestpro1",$_POST))
   {
     $li_lonestpro1 = $_POST["txtlonestpro1"];
     $lr_datos["lonestpro1"] = $li_lonestpro1;
   }
   else
   {
    $li_lonestpro1='';
   
   }
   if (array_key_exists("txtlonestpro2",$_POST))
   {
     $li_lonestpro2 = $_POST["txtlonestpro2"];
     $lr_datos["lonestpro2"] = $li_lonestpro2;
   }
    else
   {
    $li_lonestpro2='';
   
   }
   if (array_key_exists("txtlonestpro3",$_POST))
   {
     $li_lonestpro3 = $_POST["txtlonestpro3"];
     $lr_datos["lonestpro3"] = $li_lonestpro3;
   } else
   {
    $li_lonestpro3='';
   
   }
   if (array_key_exists("txtlonestpro4",$_POST))
   {
     $li_lonestpro4 = $_POST["txtlonestpro4"];
     $lr_datos["lonestpro4"] = $li_lonestpro4;
   } else
   {
    $li_lonestpro4=0;
   
   }
   if (array_key_exists("txtlonestpro5",$_POST))
   {
     $li_lonestpro5 = $_POST["txtlonestpro5"];
     $lr_datos["lonestpro5"] = $li_lonestpro5;
   } else
   {
    $li_lonestpro5=0;
   
   }
   $ls_chkconrecdoc = "";
if (array_key_exists("conrecdoc",$_POST))
   {
   		if($_POST["conrecdoc"]=="1")
		{
			$ls_chkconrecdoc = "checked";
		}
   }
   
if  (array_key_exists("radioiva",$_POST))
{
	  $ls_iva=$_POST["radioiva"];
	  $lr_datos["confivaprecon"]=$ls_iva;
}
else
{  
     $ls_iva = $_SESSION["la_empresa"]["confiva"];
	 if($ls_iva=="")
	 {
	 	$ls_iva="P";
	 }
	 $lr_datos["confivaprecon"]=$ls_iva;
}
if ($ls_iva=="C")
{
     $ls_ivacon="checked";
     $ls_ivapre="";
}
else
{
     $ls_ivacon="";
     $ls_ivapre="checked";
} 

if (array_key_exists("txtdiacadche",$_POST))
   {
     $li_diacadche = $_POST["txtdiacadche"];
   }
else
   {
     $li_diacadche = "";
   }
$lr_datos["diacadche"] = $li_diacadche;

if (array_key_exists("txtnomrep",$_POST))
   {
     $ls_nomrep = $_POST["txtnomrep"];
	 $lr_datos["nomrep"] = $ls_nomrep;
   }
else
   {
     $ls_nomrep = "";
   }
   
if (array_key_exists("txtcedrep",$_POST))
   {
     $ls_cedrep = $_POST["txtcedrep"];
	 $lr_datos["cedrep"] = $ls_cedrep;
   }
else
   {
     $ls_cedrep = "";
   }
 
if (array_key_exists("txttelfrep",$_POST))
   {
     $ls_telfrep  = $_POST["txttelfrep"];
	 $lr_datos["telfrep"] = $ls_telfrep ;
   }
else
   {
     $ls_telfrep  = "";
   }
if (array_key_exists("txtcargo",$_POST))
   {
     $ls_cargo  = $_POST["txtcargo"];
	 $lr_datos["cargorep"] = $ls_cargo ;
   }
else
   {
     $ls_cargo  = "";
   }
if (array_key_exists("chkestmodpartsep",$_POST))
   {
     $ls_estmodpartsep  = $_POST["chkestmodpartsep"];
	 $lr_datos["estmodpartsep"] = $ls_estmodpartsep ;
	 $ls_chkestmodpartsep="checked";
   }
else
   {
     $ls_estmodpartsep  = "";
	 $ls_chkestmodpartsep="";
	 $lr_datos["estmodpartsep"]= 0 ;
   }
   
if (array_key_exists("chkestmodpartsoc",$_POST))
   {
     $ls_estmodpartsoc  = $_POST["chkestmodpartsoc"];
	 $lr_datos["estmodpartsoc"] = $ls_estmodpartsoc ;
	 $ls_chkestmodpartsoc="checked";
   }
else
   {
     $ls_estmodpartsoc  = "";
	 $ls_chkestmodpartsoc="";
	 $lr_datos["estmodpartsoc"]= 0 ;
   }

if (array_key_exists("chkclactacont",$_POST))
   {
     $ls_chkclactacont=$_POST["chkclactacont"];
     $lr_datos["clactacon"]=$ls_chkclactacont;
	 $ls_chkclactacont="checked";
   }
else
   {
     $ls_chkclactacont=0;
     $lr_datos["clactacon"]=$ls_chkclactacont;
	 $ls_chkclactacont          = "";
   }
if (array_key_exists("chkempconsolidadora",$_POST))
   {
     $li_chkempcons=$_POST["chkempconsolidadora"];
     $lr_datos["estempcon"]=$li_chkempcons;
	 $ls_chkempcons="checked"; 
   }
else
   { 
     $li_chkempcons=0;
     $lr_datos["estempcon"]=$li_chkempcons;
	 $ls_chkempcons= "";
   }

if (array_key_exists("txtbdconso",$_POST))
   {
     $ls_bdconsolida  = $_POST["txtbdconso"];
	 $lr_datos["basdatcon"] = $ls_bdconsolida ;
   }
else
   {
     $ls_bdconsolida  = "";
	 $lr_datos["basdatcon"] = $ls_bdconsolida ;
   }
if (array_key_exists("txtcodaltempcon",$_POST))
   {
     $ls_codaltempcon  = $_POST["txtcodaltempcon"];
	 $lr_datos["codaltemp"] = $ls_codaltempcon ; 
   }
else
   {
     $ls_codaltempcon  = "";
   }
if (array_key_exists("chkcamemp",$_POST))
   {
     $li_chkcamemp=$_POST["chkcamemp"];
     $lr_datos["estcamemp"]=$li_chkcamemp;
	 $ls_camemp="checked"; 
   }
else
   { 
     $li_chkcamemp=0;
     $lr_datos["estcamemp"]=$li_chkcamemp;
	 $ls_camemp= "";
   }
 if (array_key_exists("chkparnodis",$_POST))
   {
     $li_partnodis=$_POST["chkparnodis"];
     $lr_datos["estparsindis"]=$li_partnodis;
	 $ls_partnodis="checked"; 
   }
else
   { 
     $li_partnodis=0;
     $lr_datos["estparsindis"]=$li_partnodis;
	 $ls_partnodis= "";
   }
   
 if (array_key_exists("chkestmodprog",$_POST))
   {
     $li_estmodprog=$_POST["chkestmodprog"];
     $lr_datos["estmodprog"]=$li_estmodprog;
	 $ls_estmodprog="checked"; 
   }
else
   { 
     $li_estmodprog=0;
     $lr_datos["estmodprog"]=$li_estmodprog;
	 $ls_estmodprog= "";
   }
   
 if (array_key_exists("chkbloanu",$_POST))
   {
     $li_bloanu=$_POST["chkbloanu"];
     $lr_datos["bloanu"]=$li_bloanu;
	 $ls_bloanu="checked"; 
   }
else
   { 
     $li_bloanu=0;
     $lr_datos["bloanu"]=$li_bloanu;
	 $ls_bloanu= "";
   }
   
if (array_key_exists("chkintcompret",$_POST))
   {
     $ls_chkintcompret=$_POST["chkintcompret"]; 
     $ls_chkintcompret="checked"; 
   }
else
   { 
     $ls_chkintcompret= "";
   }
if (array_key_exists("chkdedconproben",$_POST))
   {
     $ls_chkdedconproben=$_POST["chkdedconproben"]; 
     $lr_datos["dedconproben"]=$ls_chkdedconproben;
     $ls_chkdedconproben="checked"; 
   }
else
   { 
     $lr_datos["dedconproben"]=0;
     $ls_chkdedconproben= "";
   }
   
if (array_key_exists("txtbdintcmp",$_POST))
   { 
     $ls_bdconscomp   = $_POST["txtbdintcmp"];
	 $lr_datos["basdatcmp"] = $ls_bdconscomp;
   }
else
   {
     $ls_bdconscomp= "";
	 $lr_datos["basdatcmp"] = $ls_bdconscomp;
   }
if (array_key_exists("chkintecred",$_POST))
   {
     $li_intecred=$_POST["chkintecred"];
     $lr_datos["estintcred"]=$li_intecred;
	 $ls_intecred="checked"; 
   }
else
   { 
     $li_intecred=0;
     $lr_datos["estintcred"]=$li_intecred;
	 $ls_intecred= "";
   }
   
//- Configuracion de los Instructivos   -//   
 if  (array_key_exists("radioinstr",$_POST))
{
	  $ls_instr=$_POST["radioinstr"];
	  $lr_datos["confinstr"]=$ls_instr;
}
else
{  
	  $ls_instr=$_SESSION["la_empresa"]["confinstr"];
 	  $lr_datos["confinstr"]=$ls_instr;
}
if ($ls_instr=="V")
{
     $ls_instrambos="";
	 $ls_instr2008="";
     $ls_instr2007="checked";
}
if($ls_instr=="N")
{
     $ls_instrambos="";
	 $ls_instr2008="checked";
     $ls_instr2007="";
} 
if($ls_instr=="A")
{
     $ls_instrambos="checked";
	 $ls_instr2008="";
     $ls_instr2007="";
}    
if (array_key_exists("chkestmanant",$_POST))
   {
     $ls_chkestmanant="checked";
     $lr_datos["estmanant"]=1;
   }
else
   {
     $li_chkestmanant=0;
     $lr_datos["estmanant"]=$li_chkestmanant;
     $ls_chkestmanant="";
   }
$readonly="";
if (array_key_exists("chkpresing",$_POST))
   {
     $ls_chkpresing="checked";
     $lr_datos["estpreing"]=1;
	 $li_presing = 1;
   }
else
   {
     $lr_datos["estpreing"]=0;
     $ls_chkpresing="";
	 $li_presing = 0;
   }
   if (array_key_exists("hidpresing",$_POST))
   {
    $lr_datos["estpreing"] = $_POST["hidpresing"];
   }
   
if (array_key_exists("chkcasconmov",$_POST))
   {
     $ls_chkcasconmov="checked";
     $lr_datos["casconmov"]=1;
   }
else
   {
     $li_casconmov=0;
     $lr_datos["casconmov"]=$li_casconmov;
     $ls_chkcasconmov="";
   }
if  (array_key_exists("radiocheqauto",$_POST))
	{
	  $li_confich=$_POST["radiocheqauto"];
	  $lr_datos["confich"]=$li_confich;
    }
else
	{
	  $li_confich=0;
 	  $lr_datos["confich"]=$li_confich;
	}
if ($li_confich==1)
   {
     $ls_manual="";
     $ls_auto="checked";
   }
else
   {
     $ls_manual="checked";
     $ls_auto="";
   }    
if (array_key_exists("txtctaresact",$_POST))
   {
     $ls_ctaresact = $_POST["txtctaresact"];
   }
else
   {
     $ls_ctaresact = "";
   }
if (array_key_exists("txtctaresant",$_POST))
   {
     $ls_ctaresant = $_POST["txtctaresant"];
   }
else
   {
     $ls_ctaresant = "";
   }
if (array_key_exists("chkestaprsep",$_POST))
   {
      $ls_estaprsep = $_POST["chkestaprsep"];
	  $lr_datos["estaprsep"]=$ls_estaprsep;
	  if($ls_estaprsep=="1")
	  {
	     $ls_chkestaprsep= 'checked'; 
	  }
   }
else
   {
     $ls_chkestaprsep = "";
   }
if (array_key_exists("chksujpasesp",$_POST))
   {
      $ls_sujpasesp = $_POST["chksujpasesp"];
	  $lr_datos["sujpasesp"]=$ls_sujpasesp;
	  if($ls_estaprsep=="1")
	  {
	     $ls_chksujpasesp= 'checked'; 
	  }
   }
else
   {
     $ls_sujpasesp=0;
     $lr_datos["sujpasesp"]=$ls_sujpasesp;
     $ls_chksujpasesp = "";
   }
$lr_datos["ctaresact"] = $ls_ctaresact;
$lr_datos["ctaresant"] = $ls_ctaresant;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="GUARDAR")
   { 
	 $lb_existe = $io_empresa->uf_select_empresa();
	 if ($lb_existe)
        { 
		  $io_empresa->uf_update_empresa($lr_datos,$la_seguridad);
  	    }  
	 else  //Si no existe 
	    {  
		  $io_empresa->uf_insert_empresa();
	    } 
$ls_codigo             = "";
$ls_nombre             = "";
$ls_nomres             = "";
$ls_titulo             = "";
$ls_direccion          = "";
$ls_ciuemp             = "";
$ls_estemp			   = "";
$ls_zonpos             = "";
$ls_telefono           = "";
$ls_fax                = "";
$ls_email              = "";
$ls_website            = "";
$ls_numlicemp          = "";
$ls_nomorgads          = "";
$ls_periodo            = "";
$ls_planunico          = "";
$ls_pgasto             = "";
$ls_pingreso           = "";
$ls_activo             = "";
$ls_pasivo             = "";
$ls_gasto              = "";
$ls_resultado          = "";
$ls_contabilidad       = "";
$ls_ingreso            = "";
$ls_capital            = "";
$ls_ordendeudor        = "";
$ls_ordenacreedor      = "";
$ls_presupuestogasto   = "";
$ls_presupuestoingreso = "";
$ls_haciendaactivo     = "";
$ls_haciendapasivo     = "";
$ls_haciendaresul      = "";
$ls_fiscalingreso      = "";
$ls_fiscalgasto        = "";
$ls_resultadoactual    = "";
$ls_resultadoanterior  = "";
$ls_desestpro1         = "";
$ls_desestpro2         = "";
$ls_desestpro3         = "";
$ls_desestpro4         = "";
$ls_desestpro5         = "";
$ls_cuentabienes       = "";
$ls_cuentaservicios    = "";
$ls_rif                = "";
$ls_nit                = "";
$ls_ivss			   = "";
$ls_concomiva		   = "";	
$ls_concommun		   = "";	
$ls_cedben		       = "";	
$ls_nomben		       = "";	
$ls_scctaben		   = "";	
$ls_ctafinanciera      = "";	
$ls_ctafiscal          = "";
$ls_tesoroactivo       = "";	
$ls_tesoropasivo       = "";
$ls_tesororesul        = "";
$ls_codasiona          = "";	
$li_lonestpro1         ="";
$li_lonestpro2         ="";
$li_lonestpro3         ="";
$li_lonestpro4         ="";
$li_lonestpro5         ="";
$li_diacadche          ="";
$ls_nomrep			   ="";
$ls_cedrep			   ="";
$ls_telfrep 		   ="";
$ls_cargo    		   ="";
$ls_bdconsolida        ="";
$ls_codaltempcon       ="";
$ls_ctaresact = "";
$ls_ctaresant = "";
$ls_camemp="";
$ls_partnodis="";
$ls_bdconscomp="";
$ls_instr2008="checked";
$ls_intecred="";
$ls_chkestmanant="";
$ls_chkpresing="";
$ls_manual="checked";
$ls_auto="";
   }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
  </p>
  <form name="form1" method="post" action="">
  <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=permisos value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=permisos value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=permisos value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=permisos value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=permisos value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=permisos value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=permisos value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="755" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
      <tr class="titulo-ventana">
        <th height="22" colspan="6" class="titulo-ventana" scope="col">Empresa&nbsp;</th>
    </tr>
      <tr class="formato-blanco">
        <td height="22" colspan="6"><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>"></td>
      </tr>
      <tr class="formato-blanco">
        <td width="138" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="137" height="22"><input name="txtcodigo" type="text" id="txtcodigo" style="text-align:center " value="<?php print $ls_codigo ?>" size="8" maxlength="4" readonly></td>
        <td width="23" height="22"><input name="operacion" type="hidden" id="operacion"></td>
        <td width="117" height="22">&nbsp;</td>
        <td width="100" height="22">&nbsp;</td>
        <td width="238" height="22">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22" colspan="5"><input name="txtnombre" type="text" id="txtnombre" value="<?php print $ls_nombre ?>" size="75" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" style="text-align:left"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Nombre Resumido </div></td>
        <td height="22" colspan="5"><input name="txtnomres" type="text" id="txtnomres" value="<?php print $ls_nomres ?>" size="75" maxlength="20"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz '+'-()*/.,;@{}&#?&iquest;!&iexcl;');" style="text-align:left"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Siglas</div></td>
        <td height="22" colspan="5"><input name="txttitulo" type="text" id="txttitulo" value="<?php print $ls_titulo ?>" size="75" style="text-align:left" maxlength="100"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Direcci&oacute;n</div></td>
        <td height="22" colspan="5"><input name="txtdireccion" type="text" id="txtdireccion" value="<?php print $ls_direccion ?>" size="75" maxlength="254" style="text-align:left"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Ciudad</div></td>
        <td height="22" colspan="5">          <input name="txtciuemp" type="text" id="txtciuemp" value="<?php print $ls_ciuemp;?>" size="58" maxlength="50" style="text-align:left">        </td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Estado</div></td>
        <td height="22" colspan="5"><input name="txtestemp" type="text" id="txtestemp" value="<?php print $ls_estemp;?>" size="58" maxlength="50" style="text-align:left">        </td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Zona Postal </div></td>
        <td height="22" colspan="5"><input name="txtzonpos" type="text" id="txtzonpos" value="<?php print $ls_zonpos;?>" size="8" maxlength="5" onKeyPress="return keyRestrict(event,'1234567890');" style="text-align:left">        </td>
      </tr>
      
      <tr class="formato-blanco">
        <td height="22"><div align="right">Tel&eacute;fono</div></td>
        <td height="22"><input name="txttelefono" type="text" id="txttelefono"  style="text-align:right" onKeyPress="return keyRestrict(event,'1234567890'+'()-');" value="<?php print $ls_telefono ?>" size="24" maxlength="20"></td>
        <td height="22"><div align="right">Fax</div></td>
        <td height="22"><input name="txtfax" type="text" id="txtfax"  style="text-align:right"  onKeyPress="return keyRestrict(event,'1234567890'+'()-');" value="<?php print $ls_fax ?>" size="22" maxlength="18"></td>
        <td height="22" colspan="2">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">RIF</div></td>
        <td height="22" colspan="5"><label>
          <input name="txtrif" type="text" id="txtrif" value="<?php print $ls_rif ?>" size="20" maxlength="15" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz'+'-');" style="text-align:right">
        </label></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">NIT</div></td>
        <td height="22" colspan="5"><label>
          <input name="txtnit" type="text" id="txtnit" value="<?php print $ls_nit ?>" size="20" maxlength="15" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz'+'-');" style="text-align:right">
        </label></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Nro. I.V.S.S </div></td>
        <td height="22" colspan="5"><input name="txtivss" type="text" id="txtivss" value="<?php print $ls_ivss ?>" size="20" maxlength="15" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz'+'-');" style="text-align:right"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">E-mail</div></td>
        <td height="22" colspan="5"><input name="txtemail" type="text" id="txtemail" value="<?php print $ls_email ?>" size="75" maxlength="100" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'._-@');" style="text-align:left"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Website</div></td>
        <td height="22" colspan="5"><input name="txtwebsite" type="text" id="txtwebsite" value="<?php print $ls_website ?>" size="75" maxlength="100" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'._-@');" style="text-align:left"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Nro de Licencia </div></td>
        <td height="22" colspan="5"><label>
          <input name="txtnumlicemp" type="text" id="txtnumlicemp" value="<?php print $ls_numlicemp ?>" size="35" maxlength="25" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'.,#$%&_-@');" style="text-align:center">
        </label></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Organismo de Adscripci&oacute;n </div></td>
        <td height="22" colspan="5"><label>
        <input name="txtnomorgads" type="text" id="txtnomorgads" value="<?php print $ls_nomorgads ?>" size="75" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'.,#$%&_-@');" style="text-align:left">
        </label></td>
      </tr>
      <tr class="formato-blanco" style="display:none">
        <td height="22"><div align="right"></div></td>
        <td height="22"><div align="left">
          <input name="chkestdesiva" type="checkbox" id="chkestdesiva" value="1" <?php print $ls_desagregar ?>>
        Desagregar Impuesto </div></td>
        <td height="22" colspan="2">&nbsp;</td>
        <td height="22" colspan="2" align="center" valign="middle"><div align="left">
          <input name="chkprecomprometer" type="checkbox" id="chkprecomprometer" value="1" <?php print $ls_comprometer ?>> 
        Modalidad Precomprometer </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13" colspan="2">&nbsp;</td>
        <td height="13" colspan="2" align="center" valign="middle">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="19">&nbsp;</td>
        <td height="19" colspan="3"><input name="chkcamemp" type="checkbox" id="chkcamemp" value="1" <?php print $ls_camemp ?>> 
           Permitir cambio de Empresa</td>
        <td height="19" colspan="2" align="center" valign="middle"><div align="left">
          <input name="chkintecred" type="checkbox" id="chkintecred" value="1" <?php print $ls_intecred ?>>
        Integrar con Sistemas de Cr&eacute;ditos </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="19">&nbsp;</td>
        <td height="19" colspan="5"><input name="chkestmanant" type="checkbox" id="chkestmanant" value="1" <?php print $ls_chkestmanant; ?>>
        Manejo Anticipo (Caso Pago Directo)</td>
      </tr>
	  <?php
	     $lb_valido=$io_empresa->uf_buscar_partida_ingreso($ls_empresa);
	     if(($lb_valido)&& ($ls_operacion=='BUSCAR'))
		 { 
		   $readonly="disabled";
		 }
		 else
		 {
		   $readonly="";
		 }
	  ?>
      <tr class="formato-blanco">
        <td height="19"><input name="hidpresing" type="hidden" id="hidpresing" value="<?php print $li_presing?>"></td>
        <td height="19" colspan="5"><input name="chkpresing" type="checkbox" id="chkpresing" "<?php print $ls_chkpresing; ?>" "<?php print $readonly; ?>">
          Permitir Presupuesto de Ingresos por  Estructura </td>
      </tr>
      <tr class="formato-blanco">
        <td height="19">&nbsp;</td>
        <td height="19" colspan="5"><input name="chksujpasesp" type="checkbox" id="chksujpasesp" value="1" <?php print $ls_chksujpasesp; ?>> 
          Sujeto Pasivo Especial </td>
      </tr>
      
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
            <tr class="titulo-ventana">
              <td height="22" colspan="3"> Informaci&oacute;n de Consolidaci&oacute;n </td>
            </tr>
            <tr class="formato-blanco">
              <td height="13">&nbsp;</td>
              <td height="13" colspan="2">&nbsp;</td>
            </tr>
			 
            <tr class="formato-blanco">
              <td width="45" height="22" style="text-align:right"><input name="chkempconsolidadora" type="checkbox" id="chkempconsolidadora" value="1" <?php print $ls_chkempcons ?> onClick="javascript: ue_llamarcodigo2();"></td>
              <td height="22" colspan="2"><span style="text-align:right">Empresa que consolida</span></td>
            </tr>
	         
            <tr class="formato-blanco">
              <td height="22" colspan="2" style="text-align:right">Base de Datos donde Consolida </td>
              <td width="426" height="22" style="text-align:right"><div align="left">
                <input name="txtbdconso" type="text" id="txtbdconso" value="<?php print $ls_bdconsolida ?>" style="text-align:center"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'_');" onBlur="javascript: ue_llamarcodigo2();">
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="2" style="text-align:right">C&oacute;digo Alterno de la Empresa </td>
              <td height="22" style="text-align:right"><div align="left">
                <input name="txtcodaltempcon" type="text" id="txtcodaltempcon" style="text-align:center"  onBlur="javascript: ue_llamarcodigo2();" onChange="javascript:rellenar_cad(this.value,4,'cod')"  onKeyPress="return keyRestrict(event,'1234567890');" value="<?php print $ls_codaltempcon ?>" size="4" maxlength="4">
              </div></td>
            </tr>
		     
            <tr class="formato-blanco">
              <td colspan="3">&nbsp;</td>
            </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="7"><div align="center">Representante Legal</div></td>
          </tr>
          <tr class="formato-blanco">
            <td width="166" height="22"><div align="right">Apellidos y Nombres </div></td>
            <td width="309" height="22"><input name="txtnomrep" type="text" id="txtnomrep" value="<?php print $ls_nomrep ?>" size="75" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" style="text-align:left"></td>
            <td width="116" height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">C&eacute;dula de Identidad </div></td>
            <td height="22"><input name="txtcedrep" type="text" id="txtcedrep" value="<?php print $ls_cedrep ?>" size="24" maxlength="25" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'.,#$%&_-@');" style="text-align:right"></td>
            <td height="22">&nbsp;</td>
          </tr>
         <tr class="formato-blanco">
            <td height="22"><div align="right">Tel&eacute;fono</div></td>
            <td height="22"><input name="txttelfrep" type="text" id="txttelfrep"  style="text-align:right" onKeyPress="return keyRestrict(event,'1234567890'+'()-');" value="<?php print $ls_telfrep ?>" size="24" maxlength="20"></td>
            <td height="22">&nbsp;</td>
          </tr>
         <tr class="formato-blanco">
           <td height="22"><div align="right">Cargo </div></td>
           <td height="22"><input name="txtcargo" type="text" id="txtcargo"  style="text-align:right" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()*/.,;@{}&#?¿!¡');" value="<?php print $ls_cargo ?>" size="24" maxlength="20" ></td>
           <td height="22">&nbsp;</td>
         </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="6"><div align="center">Periodo</div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22">&nbsp;</td>
            <td height="22" colspan="2">&nbsp;</td>
            <td height="22">&nbsp;</td>
            <td width="31" height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td width="78" height="22"><div align="right">Periodo</div></td>
            <td height="22" colspan="2"><input name="txtperiodo" type="text" id="txtperiodo"  style="text-align:right" value="<?php print $ls_periodo ?>" size="12" maxlength="10"  onKeyPress="javascript:currencyDate(this);"></td>
            <td width="53" height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
            <td width="256" height="22">
              <div align="left">Validaci&oacute;n
                <select name="cmbvalidacion" id="cmbvalidacion">
                <?php 
				if ($li_nivelval==1)    
                   {
                   	 $ls_partida="selected";
					 $ls_generica="";
					 $ls_especifica="";
					 $ls_subespecifica="";
					 $ls_auxiliar="";
                   }
                else
                  {
                    if ($li_nivelval==2)
				       {
                         $ls_partida="";
						 $ls_generica="selected";
						 $ls_especifica="";
						 $ls_subespecifica="";
						 $ls_auxiliar="";
                       }
                    else 
				       {
                         if ($li_nivelval==3)
						    {
						      $ls_partida="";
						      $ls_generica="";
						      $ls_especifica="selected";
						      $ls_subespecifica="";
						      $ls_auxiliar="";
							}
						 else
						    {
							  if ($li_nivelval==4)
						         {
						           $ls_partida="";
								   $ls_generica="";
								   $ls_especifica="";
								   $ls_subespecifica="selected";
								   $ls_auxiliar="";
							     }
							  else
							    {
								  $ls_partida="";
								  $ls_generica="";
								  $ls_especifica="";
								  $ls_subespecifica="";
								  $ls_auxiliar="selected";
								}
						    }
                       }				  
				  }
                ?>
                    <option value="1"<?php print $ls_partida ?>>Partida</option>
                    <option value="2"<?php print $ls_generica ?>>Gen&eacute;rica</option>
                    <option value="3"<?php print $ls_especifica ?>>Espec&iacute;fica</option>
                    <option value="4"<?php print $ls_subespecifica ?>>Sub-Espec&iacute;fica</option>
                    <option value="5"<?php print $ls_auxiliar ?>>Auxiliar 1</option>
                </select>
            </div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="13">&nbsp;</td>
            <td height="13" colspan="2">&nbsp;</td>
            <td height="13">&nbsp;</td>
            <td height="13">&nbsp;</td>
            <td height="13">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="13" colspan="3"><div align="right"></div></td>
            <td height="13">&nbsp;</td>
            <td height="2"><div align="right">
              <input name="chkparnodis" type="checkbox" id="chkparnodis" value="1"  <?php print $ls_partnodis ?>>
            </div></td>
            <td height="13">No permitir generar SEP con Bienes, Servicios o Conceptos sin disponibilidad Presupuestaria</td>
          </tr>
          <tr class="formato-blanco">
            <td height="13">&nbsp;</td>
            <td height="13" colspan="2">&nbsp;</td>
            <td height="13">&nbsp;</td>
            <td height="13"><div align="right">
              <input name="chkestmodprog" type="checkbox" id="chkestmodprog" value="1" <?php print $ls_estmodprog; ?>>
            </div></td>
            <td height="13">Validaci&oacute;n por el  Programado</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22" colspan="3" style="text-align:left"><strong>Meses Abiertos</strong></td>
            <td height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="13">&nbsp;</td>
            <td height="13">&nbsp;</td>
            <td width="46" height="13">&nbsp;</td>
            <td height="13">&nbsp;</td>
            <td height="13">&nbsp;</td>
            <td height="13">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="13">&nbsp;</td>
            <td height="13">&nbsp;</td>
            <td height="13" colspan="4"><input name="chkbloanu" type="checkbox" id="chkbloanu" value="1" <?php print $ls_bloanu; ?>>
            Bloquear Anulación de Documentos en meses Cerrados</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22">&nbsp;</td>
            <td width="127" height="22" align="right"><input name="chkenero" type="checkbox" id="chkenero" value="1" <?php print $ls_enero ?>>
            <td height="22">Enero            
            <td width="53" height="22">            
            <td height="22"><div align="right">
              <input name="chkjulio" type="checkbox" id="chkjulio2" value="1"  <?php print $ls_julio ?>>
            </div>
            <td width="256" height="22">Julio </td>
          </tr>
          <tr class="formato-blanco">
            <td height="22">&nbsp;</td>
            <td height="22"><div align="right">
              <input name="chkfebrero" type="checkbox" id="chkfebrero" value="1" <?php print $ls_febrero ?>>
            </div></td>
            <td height="22">Febrero            </td>
            <td height="22">&nbsp;</td>
            <td height="22"><div align="right">
              <input name="chkagosto" type="checkbox" id="chkagosto" value="1"  <?php print $ls_agosto ?>>
            </div></td>
            <td height="22">Agosto            </td>
          </tr>
          <tr class="formato-blanco">
            <td height="22">&nbsp;</td>
            <td height="22"><div align="right">
              <input name="chkmarzo" type="checkbox" id="chkmarzo" value="1" <?php print $ls_marzo ?>>
            </div></td>
            <td height="22">Marzo            </td>
            <td height="22">&nbsp;</td>
            <td height="22"><div align="right">
              <input name="chkseptiembre" type="checkbox" id="chkseptiembre" value="1"  <?php print $ls_septiembre ?>>
            </div></td>
            <td height="22">Septiembre            </td>
          </tr>
          <tr class="formato-blanco">
            <td height="22">&nbsp;</td>
            <td height="22"><div align="right">
              <input name="chkabril" type="checkbox" id="chkabril" value="1" <?php print $ls_abril ?>>
            </div></td>
            <td height="22">Abril            </td>
            <td height="22">&nbsp;</td>
            <td height="22"><div align="right">
              <input name="chkoctubre" type="checkbox" id="chkoctubre" value="1"  <?php print $ls_octubre ?>>
            </div></td>
            <td height="22">Octubre            </td>
          </tr>
          <tr class="formato-blanco">
            <td height="22">&nbsp;</td>
            <td height="22"><div align="right">
              <input name="chkmayo" type="checkbox" id="chkmayo" value="1"  <?php print $ls_mayo ?>>
</div></td>
            <td height="22">Mayo            </td>
            <td height="22">&nbsp;</td>
            <td height="22"><div align="right">
              <input name="chknoviembre" type="checkbox" id="chknoviembre" value="1"  <?php print $ls_noviembre ?>>
            </div></td>
            <td height="22">Noviembre            </td>
          </tr>
          <tr class="formato-blanco">
            <td height="22">&nbsp;</td>
            <td height="22"><div align="right">
              <input name="chkjunio" type="checkbox" id="chkjunio" value="1" <?php print $ls_junio ?>>
            </div></td>
            <td height="22">Junio            </td>
            <td height="22">&nbsp;</td>
            <td height="22"><div align="right">
              <input name="chkdiciembre" type="checkbox" id="chkdiciembre" value="1"  <?php print $ls_diciembre ?>>
            </div></td>
            <td height="22">Diciembre</td>
          </tr>
          <tr class="formato-blanco">
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        <div align="center"></div></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="265" colspan="6"><div align="center">
          <table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
            <tr class="titulo-ventana">
              <td height="22" colspan="10"><div align="center">Formatos</div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="10">&nbsp;              </td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="2">&nbsp;</td>
              <td height="22" colspan="3"><div align="right">
                <input name="hidtipcont" type="hidden" id="hidtipcont" value="<?php print $ls_hidtipcont; ?>">
                <input name="radiocontabilidad" type="radio" value="1" "<?php print $ls_contabilidad_general; ?>" "<?php print $ls_distipcont; ?>">
              Contabilidad General</div></td>
              <td width="32" height="22">                <div align="right"></div></td>
              <td height="22" colspan="3"><div align="right">
                  <input name="radiocontabilidad" type="radio" value="2" "<?php print $ls_contabilidad_fiscal; ?>" "<?php print $ls_distipcont; ?>">
              Contabilidad Fiscal</div></td>
              <td width="153" height="22">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="2">&nbsp;</td>
              <td height="13" colspan="3">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td width="33" height="13">&nbsp;</td>
              <td height="13" colspan="2">&nbsp;</td>
              <td height="13">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="2">&nbsp;</td>
              <td height="22" colspan="3"><div align="right">
                Plan Unico
                    <input name="txtplanunico" type="text" id="txtplanunico" onKeyPress="return keyRestrict(event,'9'+'-');" value="<?php print $ls_planunico ?>" maxlength="30" <?php print $ls_readonly_scg;?> readonly>
              </div></td>
              <td height="22" colspan="2"><div align="right">P. Gasto</div></td>
              <td height="22" colspan="3"><input name="txtpgasto" type="text" id="txtpgasto" value="<?php print $ls_pgasto ?>" maxlength="30" onKeyPress="return keyRestrict(event,'9'+'-');" <?php print $ls_readonly_spg;?>  >
              <input name="hid_roscg" type="hidden" id="hid_roscg" value="<?php print $ls_readonly_scg; ?>">
              <input name="hid_rospg" type="hidden" id="hid_rospg" value="<?php print $ls_readonly_spg; ?>"></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="5" style="text-align:right">Contabilidad
                <input name="txtcontabilidad" type="text" id="txtcontabilidad" value="<?php print $ls_contabilidad ?>" maxlength="30" onKeyPress="return keyRestrict(event,'9'+'-');" <?php print $ls_readonly_scg;?>>              </td>
              <td height="22" colspan="2"><div align="right">P. Ingreso</div></td>
              <td height="22" colspan="3"><input name="txtpingreso" type="text" id="txtpingreso" value="<?php print $ls_pingreso ?>" maxlength="30" onKeyPress="return keyRestrict(event,'9'+'-');"></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="2">&nbsp;</td>
              <td height="22" colspan="3">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22" colspan="2">&nbsp;</td>
              <td height="22">&nbsp;</td>
            </tr>
            <tr class="titulo-ventana">
              <td height="22" colspan="10"> <div align="center">D&iacute;gitos de Cuentas </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="10" colspan="10">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="10">                              
              <div align="center">
                <table width="466" height="19" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td width="56" height="22" scope="col"><div align="right">Activo</div></td>
                    <td width="24" scope="col">
                        <div align="left">
                          <input name="txtactivo" type="text" id="txtactivo4"  style="text-align:center" value="<?php print $ls_activo ?>" size="3" maxlength="3"  onKeyPress="return keyRestrict(event,'1234567890');">
                        </div></td>
                    <td width="49" scope="col"><div align="right">Pasivo</div></td>
                    <td width="15" scope="col"><div align="left">
                      <input name="txtpasivo" type="text" id="txtpasivo"  style="text-align:center" value="<?php print $ls_pasivo ?>" size="3" maxlength="3"  onKeyPress="return keyRestrict(event,'1234567890');">
                    </div></td>
                    <td width="51" scope="col"><div align="right">Ingreso</div></td>
                    <td width="20" scope="col">
                      <div align="right">
                        <input name="txtingreso" type="text" id="txtingreso"  style="text-align:center" value="<?php print $ls_ingreso ?>" size="3" maxlength="3"  onKeyPress="return keyRestrict(event,'1234567890');">
                      </div></td>
                    <td width="48" scope="col"><div align="right">Gasto</div></td>
                    <td width="37" scope="col"><div align="left">
                      <input name="txtgasto" type="text" id="txtgasto"  style="text-align:center" value="<?php print $ls_gasto ?>" size="3" maxlength="3"  onKeyPress="return keyRestrict(event,'1234567890');">
                    </div></td>
                    <td width="53" scope="col"><div align="right">Resultado</div></td>
                    <td width="27" scope="col"><div align="left">
                      <input name="txtresultado" type="text" id="txtresultado"  style="text-align:center" value="<?php print $ls_resultado ?>" size="3" maxlength="3"  onKeyPress="return keyRestrict(event,'1234567890');">
                    </div></td>
                    <td width="44" scope="col"><div align="right">Capital</div></td>
                    <td width="42" scope="col"><input name="txtcapital" type="text" id="txtcapital"  style="text-align:center;" value="<?php print $ls_capital ?>" size="3" maxlength="3"  onKeyPress="return keyRestrict(event,'1234567890');"></td>
                    </tr>
                </table>
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="10" colspan="10">&nbsp;</td>
            </tr>
            <tr class="titulo-ventana">
              <td height="22" colspan="10">                              
              <div align="center"><strong>Digitos de Cuenta - Contabilidad General </strong></div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="6" style="text-align:left"><strong>Orden</strong></td>
              <td height="22" colspan="4"><strong>Presupuesto</strong></td>
            </tr>
            <tr class="formato-blanco">
              <td width="63" height="22"><div align="right">Deudor</div></td>
              <td height="22" colspan="2"><input name="txtordendeudor" type="text" size="3" maxlength="3" style="text-align:center" value="<?php print $ls_ordendeudor ?>"  onKeyPress="return keyRestrict(event,'1234567890');"></td>
              <td width="14" height="22">&nbsp;</td>
              <td width="160" height="22"><div align="left">Acreedor
                <input name="txtordenacreedor" id="txtordenacreedor" type="text" size="3" maxlength="3"  style="text-align:center"  value="<?php print $ls_ordenacreedor ?>"  onKeyPress="return keyRestrict(event,'1234567890');">
              </div></td>
              <td height="22">&nbsp;</td>
              <td height="22"><div align="right">Gasto</div></td>
              <td width="55" height="22"><input name="txtpresupuestogasto" id="txtpresupuestogasto"  type="text" size="3" maxlength="3" style="text-align:center"  value="<?php print $ls_presupuestogasto ?>" onKeyPress="return keyRestrict(event,'1234567890');"></td>
              <td height="22" colspan="2">Ingreso
              <input name="txtpresupuestoingreso" type="text" id="txtpresupuestoingreso"  style="text-align:center" value="<?php print $ls_presupuestoingreso ?>" size="3" maxlength="3"  onKeyPress="return keyRestrict(event,'1234567890');"></td>
            </tr>
            <tr class="formato-blanco">
              <td colspan="2">&nbsp;</td>
              <td colspan="3">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr class="titulo-ventana">
              <td height="22" colspan="10"><div align="center"><strong>Digitos de Cuenta - Contabilidad Fiscal </strong></div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="5"><strong><span class="style6">Hacienda</span></strong></td>
              <td height="22">&nbsp;</td>
              <td height="22" colspan="2" align="left"><p><strong><span class="style6">Fiscal</span></strong></p></td>
              <td width="57" height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="10"><div align="center">
                <table width="541" height="19" border="0" cellpadding="0" cellspacing="0">
                  <tr class="formato-blancotabla">
                    <td width="34" scope="col"><div align="left">Activo</div></td>
                    <td width="15" scope="col">
                      <div align="left">
                        <input name="txthaciendaactivo" type="text" id="txthaciendaactivo"  style="text-align:center" value="<?php print $ls_haciendaactivo ?>" size="3" maxlength="3"  onKeyPress="return keyRestrict(event,'1234567890');">
                    </div></td>
                    <td width="47" scope="col"><div align="right">Pasivo</div></td>
                    <td width="15" scope="col"><div align="left">
                        <input name="txthaciendapasivo" type="text" id="txthaciendapasivo"  style="text-align:center" value="<?php print $ls_haciendapasivo ?>" size="3" maxlength="3"  onKeyPress="return keyRestrict(event,'1234567890');">
                    </div></td>
                    <td width="60" scope="col"><div align="right">Resultado</div></td>
                    <td width="32" scope="col">
                        <div align="left">
                          <input name="txthaciendaresul" type="text" id="txthaciendaresul"  style="text-align:center" value="<?php print $ls_haciendaresul ?>" size="3" maxlength="3"  onKeyPress="return keyRestrict(event,'1234567890');">
                      </div></td>
                    <td width="70" scope="col" align="left"><div align="right">Gasto</div></td>
                    <td width="67" scope="col"><div align="left">
                      <input name="txtfiscalgasto" type="text" id="txtfiscalgasto"  style="text-align:center" value="<?php print $ls_fiscalgasto ?>" size="3" maxlength="3"  onKeyPress="return keyRestrict(event,'1234567890');">
                    </div></td>
                    <td width="41"align="left" scope="col"><div align="left">Ingreso</div></td>
                    <td width="160" scope="col">
                      <div align="left">
                        <input name="txtingresofiscal" type="text" id="txtingresofiscal"  style="text-align:center" value="<?php print $ls_fiscalingreso ?>" size="3" maxlength="3"  onKeyPress="return keyRestrict(event,'1234567890');">
                      </div></td>
                    </tr>
                </table>
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="10"><strong><span class="style6">Tesoro</span></strong></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22"> <div align="right">Activo</div></td>
              <td height="22" colspan="9"><div align="left">
                <table width="261" height="19" border="0" cellpadding="0" cellspacing="0">
                  <tr class="formato-blancotabla">
                    <td width="24" scope="col">
                      
                        <div align="left">
                          <input name="txttesoroactivo" type="text" id="txttesoroactivo"  style="text-align:center" value="<?php print $ls_tesoroactivo ?>" size="3" maxlength="3"  onKeyPress="return keyRestrict(event,'1234567890');">
                      </div></td>
                    <td width="41" scope="col"><div align="right">Pasivo</div></td>
                    <td width="27" scope="col"><div align="left">
                        <input name="txttesoropasivo" type="text" id="txttesoropasivo"  style="text-align:center" value="<?php print $ls_tesoropasivo ?>" size="3" maxlength="3"  onKeyPress="return keyRestrict(event,'1234567890');">
                    </div></td>
                    <td width="60" scope="col"><div align="right">Resultado</div></td>
                    <td width="109" scope="col"><div align="left">
                        <input name="txttesororesul" type="text" id="txttesororesul"  style="text-align:center" value="<?php print $ls_tesororesul ?>" size="3" maxlength="3"  onKeyPress="return keyRestrict(event,'1234567890');">
                    </div></td>
                  </tr>
                </table>
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="2">&nbsp;</td>
              <td height="22" colspan="3">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22" colspan="2">&nbsp;</td>
              <td height="22">&nbsp;</td>
            </tr>
          </table>
          </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22" colspan="6"><div align="center"><a href="#top">Volver Arriba</a> </div></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"><div align="center">
          <table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
            <tr class="titulo-ventana">
              <td height="22" colspan="7" style="text-align:center">Cuentas</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="3"><strong>Cuentas Resultados </strong></td>
              <td height="22">&nbsp;</td>
              <td height="22"><input name="hidresultado" type="hidden" id="hidresultado"></td>
              <td height="22">&nbsp;</td>
              <td height="22"><input name="hiddisabled" type="hidden" id="hiddisabled" value="<?php print $li_disabled ?>"></td>
				<?php
				if ($li_disabled=='1')
				   {
					 $ls_disabled = 'disabled';
					 $ls_readonly = 'readonly';
				   }
				else
				   {
					 $ls_readonly = '';
					 $ls_disabled = '';
				   }
				?>
			</tr>
            <tr class="formato-blanco">
              <td height="22" colspan="3"> <div align="right">Resultado Actual
                  <input name="txtresultadoactual" type="text" id="txtresultadoactual" value="<?php print $ls_resultadoactual ?>"  style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890');">
              &nbsp;<a href="javascript:uf_catalogo_scgcuentas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" onClick="document.form1.hidresultado.value=1"></a></div></td><td height="22" colspan="2"><div align="right">Resultado Anterior </div></td>
              <td height="22" colspan="2"><div align="left">
                <input name="txtresultadoanterior" type="text" id="txtresultadoanterior" value="<?php print $ls_resultadoanterior ?>"  style="text-align:center"  onKeyPress="return keyRestrict(event,'1234567890');">
              &nbsp;<a href="javascript:uf_catalogo_scgcuentas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"  onClick="document.form1.hidresultado.value=2"></a></div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="3">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="3"><strong>Cuentas Resultados Consolidaci&oacute;n </strong></td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="3"><div align="right">Resultado Actual
                  <input name="txtctaresact" type="text" id="txtctaresact" value="<?php print $ls_ctaresact ?>"  style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890');">
&nbsp;<a href="javascript:uf_catalogo_scgcuentas();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Cuentas de Resultado para la Consolidaci&oacute;n Contable" width="15" height="15" border="0" onClick="document.form1.hidresultado.value=3"></a></div></td>
              <td height="13">&nbsp;</td>
              <td height="13" colspan="3">Resultado Anterior
              <input name="txtctaresant" type="text" id="txtctaresant" value="<?php print $ls_ctaresant ?>"  style="text-align:center"  onKeyPress="return keyRestrict(event,'1234567890');"  onClick="document.form1.hidresultado.value=1">
              <a href="javascript:uf_catalogo_scgcuentas();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Cuentas de Resultado para la Consolidaci&oacute;n Contable" width="15" height="15" border="0"  onClick="document.form1.hidresultado.value=4"></a></td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="3">&nbsp; </td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="3"><strong>Situaci&oacute;n del Tesoro </strong></td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="3"><div align="right">Financiera(199)
                  <input name="txtctafinanciera" type="text" id="txtctafinanciera" value="<?php print $ls_ctafinanciera ?>"  style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890');">
                &nbsp;<a href="javascript:uf_catalogo_scgcuentas_financiera();"><img src="../shared/imagebank/tools15/buscar.gif" alt="" width="15" height="15" border="0" onClick="document.form1.hidresultado.value=1"></a></div></td>
              <td height="22" colspan="2"><div align="right">Fiscal(200) </div></td>
              <td height="22" colspan="2"><div align="left">
                  <input name="txtctafiscal" type="text" id="txtctafiscal" value="<?php print $ls_ctafiscal ?>"  style="text-align:center"  onKeyPress="return keyRestrict(event,'1234567890');"  onClick="document.form1.hidresultado.value=1">
                &nbsp;<a href="javascript:uf_catalogo_scgcuentas_fiscal();"><img src="../shared/imagebank/tools15/buscar.gif" alt="" width="15" height="15" border="0"  onClick="document.form1.hidresultado.value=2"></a></div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="3">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="3">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="7"><strong>Configuraci&oacute;n Estructura Presupuestaria o Program&aacute;tica</strong>
              <input name="hidestmodest" type="hidden" id="hidestmodest" value="<?php print $li_estmodest ?>" readonly></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="7"><table width="381" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                <tr>
                  <td width="49"><div align="right">
                    <label>
                    <input name="radioestructura" type="radio" onClick="javascript:uf_cambio();" value="1" "<?php print $ls_proyecto ?>" "<?php print $ls_disabled ?>">
                    </label>
                  </div></td>
                  <td width="85">Por Proyectos </td>
                  <td width="94"><div align="right">
                    <label>
                    <input name="radioestructura" type="radio" value="2" "<?php print $ls_programa ?>" "<?php print $ls_disabled ?>" onClick="javascript:uf_cambio();">
                    </label>
                  </div></td>
                  <td width="151">Por Programas </td>
                </tr>
              </table></td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="3">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="7"><strong>N&uacute;mero de Niveles de la Estructura</strong> 
                <label>
                <select name="cmbnumniv" id="cmbnumniv" onChange="javascript:uf_cambio_niveles();" disabled="true" >
				<?php
				if ($li_numnivest==1)    
                   {
                   	 $ls_nivel_1="selected";
					 $ls_nivel_2="";
					 $ls_nivel_3="";
					 $ls_nivel_4="";
					 $ls_nivel_5="";
                   }
                else
                  {
                    if ($li_numnivest==2)
				       {
						 $ls_nivel_1="";
						 $ls_nivel_2="selected";
						 $ls_nivel_3="";
						 $ls_nivel_4="";
						 $ls_nivel_5="";
                       }
                    else 
				       {
                         if ($li_numnivest==3)
						    {
							  $ls_nivel_1="";
							  $ls_nivel_2="";
							  $ls_nivel_3="selected";
							  $ls_nivel_4="";
							  $ls_nivel_5="";
							}
						 else
						    {
							  if ($li_numnivest==4)
						         {
								   $ls_nivel_1="";
								   $ls_nivel_2="";
								   $ls_nivel_3="";
								   $ls_nivel_4="selected";
								   $ls_nivel_5="";
							     }
							  else
							    {
								  $ls_nivel_1="";
								  $ls_nivel_2="";
								  $ls_nivel_3="";
								  $ls_nivel_4="";
								  $ls_nivel_5="selected";
								}
						    }
                       }				  
				  }
                  ?>
				  <option value="1"<?php print $ls_nivel_1 ?>>1</option>
                  <option value="2"<?php print $ls_nivel_2 ?>>2</option>
                  <option value="3"<?php print $ls_nivel_3 ?>>3</option>
                  <option value="4"<?php print $ls_nivel_4 ?>>4</option>
                  <option value="5"<?php print $ls_nivel_5 ?>>5</option>
                </select>
                <input name="hidnumniv" type="hidden" id="hidnumniv" value="<?php print $li_numnivest ?>">
              </label></td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="3">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="3"><div align="left"><strong>Estructura Presupuestaria</strong></div></td>
              <td width="35" height="22">&nbsp;</td>
              <td width="96" height="22">&nbsp;</td>
              <td width="76" height="22">&nbsp;</td>
              <td width="106" height="22">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="2"><div align="right">Nombre Nivel 1</div></td>
              <td width="165" height="22">
                <div align="right">
                  <input name="txtdesestpro1" type="text" id="txtdesestpro1" value="<?php print $ls_desestpro1 ?>" size="33" maxlength="100" <?php print $ls_readonly ?>>
              </div></td><td height="22">&nbsp;</td>
              <td height="22"><div align="right">Nombre Nivel 4</div></td>
              <td height="22" colspan="2"><input name="txtdesestpro4" type="text" id="txtdesestpro4" value="<?php print $ls_desestpro4 ?>" size="25" maxlength="100" <?php print $ls_readonly ?>></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="2"><div align="right">Nombre Nivel 2</div></td>
              <td height="22"><div align="right">
                <input name="txtdesestpro2" type="text" id="txtdesestpro2" value="<?php print $ls_desestpro2 ?>" size="33" maxlength="100" <?php print $ls_readonly ?>>
              </div></td>
              <td height="22">&nbsp;</td>
              <td height="22"><div align="right">Nombre Nivel 5</div></td>
              <td height="22" colspan="2"><input name="txtdesestpro5" type="text" id="txtdesestpro5" value="<?php print $ls_desestpro5 ?>" size="25" maxlength="100" <?php print $ls_readonly ?>></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="2"><div align="right">Nombre Nivel 3</div></td>
              <td height="22"><div align="right">
                <input name="txtdesestpro3" type="text" id="txtdesestpro3" value="<?php print $ls_desestpro3 ?>" size="33" maxlength="100" <?php print $ls_readonly ?>>
              </div></td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="3">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="4"><div align="left"><strong>Logitud del C&oacute;digo de la Estructura Presupuestaria</strong></div></td>
              <td width="96" height="22">&nbsp;</td>
              <td width="76" height="22">&nbsp;</td>
              <td width="106" height="22">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="2"><div align="right">Longitud  Nivel 1</div></td>
              <td width="165" height="22">
                <div align="left">
                  <input name="txtlonestpro1" type="text" id="txtlonestpro1" value="<?php print $li_lonestpro1 ?>" size="3" maxlength="2" onChange="javascript:uf_validarlongitud(txtlonestpro1)" <?php print $ls_readonly ?>>
              </div></td><td height="22">&nbsp;</td>
              <td height="22"><div align="right">Longitud Nivel 4</div></td>
              <td height="22" colspan="2"><input name="txtlonestpro4" type="text" id="txtlonestpro4" value="<?php print $li_lonestpro4 ?>" size="3" maxlength="2" onChange="javascript:uf_validarlongitud(txtlonestpro4)" <?php print $ls_readonly ?>></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="2"><div align="right">Longitud Nivel 2</div></td>
              <td height="22"><div align="left">
                <input name="txtlonestpro2" type="text" id="txtlonestpro2" value="<?php print $li_lonestpro2 ?>" size="3" maxlength="100" onChange="javascript:uf_validarlongitud(txtlonestpro2)" <?php print $ls_readonly ?>>
              </div></td>
              <td height="22">&nbsp;</td>
              <td height="22"><div align="right">Longitud Nivel 5</div></td>
              <td height="22" colspan="2"><input name="txtlonestpro5" type="text" id="txtlonestpro5" value="<?php print $li_lonestpro5 ?>" size="3" maxlength="2" onChange="javascript:uf_validarlongitud(txtlonestpro5)" <?php print $ls_readonly ?>></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="2"><div align="right">Longitud Nivel 3</div></td>
              <td height="22"><div align="left">
                <input name="txtlonestpro3" type="text" id="txtlonestpro3" value="<?php print $li_lonestpro3 ?>" size="3" maxlength="100" onChange="javascript:uf_validarlongitud(txtlonestpro3)" <?php print $ls_readonly ?>>
              </div></td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td width="72" height="22">&nbsp;</td>
              <td width="38" height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"><div align="center">
          <table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
            <tr class="titulo-ventana">
              <td height="22" colspan="4"><div align="center">Compras</div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="4"><strong>Partidas  de Compras 
                <input name="hidcompras" type="hidden" id="hidcompras">
              </strong></td>
            </tr>
            <tr class="formato-blanco">
              <td width="100" height="22"><div align="right">Bienes</div></td>
              <td width="161" height="22"><input name="txtcuentabienes" type="text" id="txtcuentabienes" value="<?php print $ls_cuentabienes ?>" maxlength="100" style="text-align:center " onKeyPress="return keyRestrict(event,'1234567890'+',');"> &nbsp;<a href="javascript:uf_catalogo_spgcuentas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" onClick="document.form1.hidcompras.value=1"></a></td>
              <td width="119" height="22"><div align="right">Servicios</div></td>
              <td width="211" height="22"><input name="txtcuentaservicios" type="text" id="txtcuentaservicios" value="<?php print $ls_cuentaservicios ?>" maxlength="100"  style="text-align:center"  onKeyPress="return keyRestrict(event,'1234567890'+',');"> &nbsp;<a href="javascript:uf_catalogo_spgcuentas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"  onClick="document.form1.hidcompras.value=2"></a></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="4"><strong>Inicio de Contadores </strong> </td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="4"><div align="left">
                  <table width="562" border="0" align="left" cellpadding="0" cellspacing="0">
                    <tr class="formato-blanco">
                      <td width="100" style="text-align:right">Orden de Compra</td>
                      <td width="133"><label>
                          <div align="left">
                            <input name="txtnumordcom" type="text" id="txtnumordcom" style="text-align:right" value="<?php print $ls_numordcom ?>" maxlength="15"  onKeyPress="return keyRestrict(event,'1234567890');">
                          </div>
                        </label></td>
                      <td width="149" style="text-align:right">Orden de Servicio</td>
                      <td width="183"><label>
                        <input name="txtnumordser" type="text" id="txtnumordser" style="text-align:right" value="<?php print $ls_numordser ?>" maxlength="15" onKeyPress="return keyRestrict(event,'1234567890');">
                      </label></td>
                    </tr>
                  </table>
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="4"><table width="562" border="0" align="left" cellpadding="0" cellspacing="0">
                  <tr class="formato-blanco">
                    <td width="100" style="text-align:right">Solicitud de Pago</td>
                    <td width="333" style="text-align:left"><input name="txtnumsolpag" type="text" id="txtnumsolpag" style="text-align:right" value="<?php print $ls_numsolpag ?>" maxlength="15" onKeyPress="return keyRestrict(event,'1234567890');"></td>
                    <td width="77">&nbsp;</td>
                    <td width="53"><label></label></td>
                  </tr>
              </table></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="4">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="2"><div align="center">Permitir Modificaci&oacute;n de las  partidas en la Imputaci&oacute;n Presupuestaria</div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="13" colspan="2">&nbsp;</td>
          </tr>

          <tr class="formato-blanco">
            <td width="184" height="22"><div align="right">
              <input name="chkestmodpartsep" type="checkbox" class="sin-borde" id="chkestmodpartsep" value="1" <?php print $ls_chkestmodpartsep; ?>>
            </div></td>
            <td width="407"><div align="left">Solicitud de Ejecuci&oacute;n Presupuestaria </div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">
              <input name="chkestmodpartsoc" type="checkbox" class="sin-borde" id="chkestmodpartsoc" value="1" <?php print $ls_chkestmodpartsoc; ?>>
            </div></td>
            <td><div align="left">Orden de Compra </div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="13" colspan="2">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="2"><div align="center">Permitir Aprobar Documentos sin Disponibilidad </div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="13" colspan="2">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td width="184" height="22"><div align="right">
                <input name="chkestaprsep" type="checkbox" class="sin-borde" id="chkestaprsep" value="1" <?php print $ls_chkestaprsep; ?>>
            </div></td>
            <td width="407"><div align="left">Solicitud de Ejecuci&oacute;n Presupuestaria </div></td>
          </tr>

          <tr class="formato-blanco">
            <td height="13" colspan="2">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"><div align="center">
          <table width="593" border="0" cellpadding="0" cellspacing="0" class="contorno">
            <tr class="titulo-ventana">
              <td height="22" colspan="5" class="titulo-ventana">Gasto</td>
            </tr>
            <tr class="formato-blanco">
              <td width="90">&nbsp;</td>
              <td colspan="2">&nbsp;</td>
              <td width="136">&nbsp;</td>
              <td width="128">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td><div align="right" style="display:none" >
			    <input name="chkvalidacion" type="checkbox" id="chkvalidacion" value="1" "<?php print $ls_checked ?>">
              </div></td>
              <td colspan="2" style="display:none">Validar porcentaje de Traspasos 
              <input name="modaper" type="hidden" id="modaper" value="<?php print $ls_modaper ?>"></td>
              <td colspan="2"><p>&nbsp;</p>
                <label></label></td></tr>
            <tr class="formato-blanco">
              <td>&nbsp;</td>
              <td colspan="4" ><p>Codigo ONAPRE    Asignado a la Empresa 
                  <input name="txtcodasiona" type="text" id="txtcodasiona" value="<?php print $ls_codasiona ?>" size="15" maxlength="10" style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz '+'-');" ></p> </td>
            </tr>
            <tr class="formato-blanco">
              <td>&nbsp;</td>
              <td colspan="2" style="display:none">&nbsp;</td>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="19" colspan="5"><strong>Modalidad de la 
              Apertura</strong></td>
            </tr>
            <tr class="formato-blanco">
              <td colspan="5"><div align="center">
                <table width="408" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td width="50"><div align="right">
                      <input name="radioestmodape" type="radio" value="0" "<?php print $ls_mensual; ?>" "<?php print $ls_disapertura; ?>">
                    </div></td>
                    <td width="87">Mensual</td>
                    <td width="111"><div align="right">
                      <input name="radioestmodape" type="radio" value="1" "<?php print $ls_trimestral; ?>" "<?php print  $ls_disapertura; ?>">
	                  <input name="hidestmodape" type="hidden" id="hidestmodape"value="<?php print $li_estmodape;?>"  >
                    </div></td>
                    <td width="158">Trimestral</td>
                  </tr>
                </table>
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="5"><strong>Saldos Iniciales </strong></td>
            </tr>
            <tr class="formato-blanco">
              <td><div align="right"> Programado </div></td>
              <td width="181"><div align="left">
                <label>
                <input name="txtsalinipro" type="text" id="txtsalinipro" value="<?php print $ld_salinipro ?>" style="text-align:right" onKeyPress="return(currencyFormat(this,'.',',',event))">
                </label>
              </div></td>
              <td width="56"><div align="right"> Ejecutado </div></td>
              <td colspan="2"><label>
                <input name="txtsalinieje" type="text" id="txtsalinieje" value="<?php print $ld_salinieje ?>"  style="text-align:right" onKeyPress="return(currencyFormat(this,'.',',',event))">
              </label></td>
            </tr>
            <tr class="formato-blanco">
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"><div align="center">
          <table width="593" border="0" cellpadding="0" cellspacing="0" class="contorno">
            <tr class="titulo-ventana">
              <td height="22" colspan="3">Datos SIGECOF </td>
            </tr>
            <tr class="formato-blanco">
              <td width="88" height="13">&nbsp;</td>
              <td width="256" height="13">&nbsp;</td>
              <td width="244" height="13">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22">&nbsp;</td>
              <td height="22"><label>C&oacute;digo del Organismo
                  <input name="txtcodorg" type="text" value="<?php print $ls_codorg ?>" maxlength="5" style="text-align:center">
              </label></td>
              <td height="22">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="14" colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="14" colspan="6"><div align="center">
          <table width="593" border="0" cellpadding="0" cellspacing="0" class="contorno">
            <tr class="titulo-ventana">
              <td height="22" colspan="4">Generaci&oacute;n de Comprobantes de Retenci&oacute;n de Impuesto Municipal </td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="2">&nbsp;</td>
              <td width="98" height="13">&nbsp;</td>
              <td width="244" height="13">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td width="86" height="22"><div align="right">
                <label>
                <input name="radiocmp" type="radio" class="sin-borde" value="C" <?php print $ls_impretcxp ?>>
                </label>
              </div></td>
              <td width="163">M&oacute;dulo de Cuentas Por Pagar </td>
              <td height="22"><div align="right">
                <label></label>
                <input name="radiocmp" type="radio" class="sin-borde" value="B" <?php print $ls_impretban ?>>
              </div></td>
              <td height="22">              M&oacute;dulo de Banco </td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="2"><div align="right">Valor Inicial del Contador</div></td>
              <td height="13"><input name="txtconcommun" type="text" id="txtconcommun" value="<?php print $ls_concommun; ?>" size="5" maxlength="6" style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890');"></td>
              <td height="13">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="2">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="13" colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="13" colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="4">Generaci&oacute;n de Comprobantes de Retenci&oacute;n de IVA /ISLR </td>
          </tr>
          <tr class="formato-blanco">
            <td height="13" colspan="2">&nbsp;</td>
            <td width="99" height="13">&nbsp;</td>
            <td width="244" height="13">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right">
              <input name="radiocmpiva" type="radio" class="sin-borde" value="C" "<?php print $ls_compivacxp ?>" >
            </div></td>
            <td>M&oacute;dulo de Cuentas Por Pagar </td>
            <td height="22"><div align="right">
                <input name="radiocmpiva" type="radio" class="sin-borde" value="B" "<?php print $ls_compivaban ?>" >
            </div></td>
            <td height="22">M&oacute;dulo de Banco </td>
          </tr>
          <tr class="formato-blanco">
            <td width="86" height="22"><div align="right">
                <label></label>
            </div></td>
            <td width="162"><div align="right">Valor Inicial del Contador( IVA) </div></td>
            <td height="22" colspan="2"><div align="right">
                <label></label>
                <div align="left">
                  <input name="txtconcomiva" type="text" id="txtconcomiva" style="text-align:center" value="<?php print $ls_concomiva ?>" size="5" maxlength="6" onKeyPress="return keyRestrict(event,'1234567890');">
            </div></div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="13" colspan="2">&nbsp;</td>
            <td height="13">&nbsp;</td>
            <td height="13">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td height="13" colspan="6">&nbsp;</td>
      </tr>
      
      <tr class="formato-blanco">
        <td height="13" colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="4"> Cuentas por Pagar </td>
          </tr>
          <tr class="formato-blanco">
            <td width="79" height="13">&nbsp;</td>
            <td height="13" colspan="3">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22">&nbsp;</td>
            <td width="130" height="22"><div align="right">
              <label>
              <input name="chkestmodiva" type="checkbox" class="sin-borde" id="chkestmodiva" value="1" <?php print $ls_estmodiva; ?>>
              </label>
            </div></td>
            <td colspan="2"><div align="left">Permitir Modificar IVA en Recepci&oacute;n de Documento </div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="13">&nbsp;</td>
            <td height="13"> <div align="right">
              <input name="chkconrecdoc" type="checkbox" class="sin-borde" id="chkconrecdoc" value="1" <?php print $ls_chkconrecdoc;?> disabled>
            </div></td>
            <td height="13" colspan="2">Contabilizar Recepciones de Documento
            <input name="conrecdoc" type="hidden" id="conrecdoc" value="<?php print $ls_chkconrecdoc ?>"></td>
          </tr>
          <tr class="formato-blanco">
            <td height="13">&nbsp;</td>
            <td height="13">
              <div align="right">
                <input name="chkclactacont" type="checkbox" class="sin-borde" id="chkclactacont" value="1" <?php print $ls_chkclactacont;?> >
                </div>
            </div></td>
            <td height="22" colspan="2">Permitir Cuenta Contable en Clasificador de Cuentas por Pagar </td>
          </tr>
		 <tr class="formato-blanco">
            <td height="13">&nbsp;</td>
            <td height="13">
              <div align="right">
                <input name="chkintcompret" type="checkbox" class="sin-borde" id="chkintcompret" value="1" "<?php print $ls_chkintcompret;?>" onClick="javascript:uf_cambio2();" >
              </div></td>
            <td height="13" colspan="2">Consolidar Comprobantes de Retencion</td>
          </tr>
		 <tr class="formato-blanco">
		   <td height="13">&nbsp;</td>
		   <td height="13"> <div align="right">
                <input name="chkdedconproben" type="checkbox" class="sin-borde" id="chkdedconproben" value="1" "<?php print $ls_chkdedconproben;?>">
              </div></td>
		   <td height="13" colspan="2">Mostrar solo Deducciones configuradas al Proveedor &oacute; Beneficiario </td>
	      </tr>
		 
          
          <tr class="formato-blanco">
            <td height="13">&nbsp;</td>
            <td height="13" colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="13">&nbsp;</td>
            <td height="13" colspan="2"><div align="right">Base de Datos Integradora </div></td>
            <td width="354"><input name="txtbdintcmp" type="text" id="txtbdintcmp" value="<?php print $ls_bdconscomp ?>" "<?php if(($ls_operacion=='BUSCAR')&&($ls_chkintcompret=="")){print "disabled";}?>" style="text-align:center"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz '+'_') ;" onBlur="javascript:uf_cambio3();" ></td>
          </tr>
          <tr class="formato-blanco">
            <td height="13">&nbsp;</td>
            <td height="13" colspan="3">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td height="13" colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="13" colspan="6"><div align="center">
          <table width="593" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
            <tr class="titulo-ventana">
              <td height="22" colspan="3"> Carta Orden </td>
            </tr>
            <tr class="formato-blanco">
              <td width="38" height="13">&nbsp;</td>
              <td height="13" colspan="2">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22">&nbsp;</td>
              <td width="69" height="22" style="text-align:right">Beneficiario</td>
              <td width="484"><div align="left">
                <label>
                <a href="javascript:uf_catalogo_beneficiario();"><img src="../shared/imagebank/tools15/buscar.gif" alt="cc" width="15" height="15" border="0" onClick="document.form1.hidcompras.value=1"></a>
                <input name="txtcedben" type="text" id="txtcedben" value="<?php   print $ls_cedben; ?>" readonly>
                </label>
                <label>
                <input name="txtnomben" type="text" id="txtnomben" value="<?php print $ls_nomben; ?>" size="30" maxlength="100" readonly>
                </label>
                <label>
                <input name="txtscctaben" type="text" id="txtscctaben" value="<?php  print  $ls_scctaben; ?>" readonly>
                </label>
</div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="13">&nbsp;</td>
              <td height="13" colspan="2">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="13" colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="127" colspan="6"><div align="center">
          <table width="593" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
            <tr class="titulo-ventana">
              <td height="22" colspan="8"> Banco</td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="8">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td width="16" height="22">&nbsp;</td>
              <td width="118" style="text-align:right">Caducidad del Cheque </td>
              <td width="105" style="text-align:left"><input name="txtdiacadche" type="text" id="txtdiacadche" value="<?php echo $li_diacadche ?>" size="6" maxlength="3" style="text-align:right" onKeyPress="return keyRestrict(event,'1234567890');"> <strong>d&iacute;as.</strong></td>
              <td width="153" height="22" style="text-align:right">Generaci&oacute;n de Cheques </td>
              <td width="78" height="22" style="text-align:left"><input name="radiocheqauto" type="radio" class="sin-borde" value="0" "<?php  print $ls_manual ?>" >
                Manual.</td>
              <td height="22" colspan="3" style="text-align:left"><input name="radiocheqauto" type="radio" class="sin-borde" value="1" "<?php print $ls_auto?>" >
                Autom&aacute;tica.</td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="8"><div align="center">
                <input name="chkcasconmov" type="checkbox" class="sin-borde" id="chkcasconmov" value="1" <?php echo $ls_chkcasconmov; ?>>
                Uso del Casamiento Conceptos  Movimiento con Cuentas Bancarias</div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="13" colspan="6">&nbsp;</td>
              <td width="83" height="13" colspan="2">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="13" colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="13" colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
          <tr class="titulo-ventana">
            <td height="22" colspan="6"> Configuraci&oacute;n del Iva </td>
          </tr>
          <tr class="formato-blanco">
            <td width="84" height="13">&nbsp;</td>
            <td height="13" colspan="5">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="13">&nbsp;</td>
            <td width="106" height="13"><div align="right">Manejo del Iva </div></td>
            <td width="73" height="13"><div align="right">
                <label></label>
                <?php 
				    $lb_valido=$io_empresa->uf_existe_ivaconfigurado($_SESSION["la_empresa"]["codemp"],&$li_totalcargos);
					if(($lb_valido)&&($li_totalcargos==0))
					{
					  $ls_disableconfiva='';
					}
					else
					{
					  $ls_disableconfiva='disabled';
					}
				 ?>
                <input name="radioiva" type="radio" class="sin-borde" value="P" "<?php  print $ls_ivapre ?>" "<?php print $ls_disableconfiva ?>">
            </div></td>
            <td width="136"><div align="left">Iva Presupuestario </div></td>
            <td width="50"><div align="right">
                <label>
                <input name="radioiva" type="radio" class="sin-borde" value="C" "<?php  print $ls_ivacon ?>" "<?php print $ls_disableconfiva ?>">
                </label>
            </div></td>
            <td width="142"><div align="left">Iva Contable </div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="13">&nbsp;</td>
            <td height="13" colspan="5">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td height="13" colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="13" colspan="6"><table width="593" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
            <tr class="titulo-ventana">
              <td height="22" colspan="8"> Configuracion de los Instructivos ONAPRE </td>
            </tr>
            <tr class="formato-blanco">
              <td width="54" height="13">&nbsp;</td>
              <td height="13" colspan="7">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="13">&nbsp;</td>
              <td width="144" height="13"><div align="right"> Instructivo </div></td>
              <td width="67" height="13"><div align="right">
                  <label></label>
                  <?php 
				    /*$lb_valido=$io_empresa->uf_existe_ivaconfigurado($_SESSION["la_empresa"]["codemp"],&$li_totalcargos);
					if(($lb_valido)&&($li_totalcargos==0))
					{
					  $ls_disableinstr='';
					}
					else
					{
					  $ls_disableinstr='disabled';
					}*/
					$ls_disableinstr='';
				 ?>
                  <input name="radioinstr" type="radio" class="sin-borde" value="V" "<?php  print $ls_instr2007 ?>" "<?php print $ls_disableinstr ?>">
              </div></td>
              <td width="46"><div align="left">2007</div></td>
              <td width="46"><div align="right">
                  <label>
                  <input name="radioinstr" type="radio" class="sin-borde" value="N" "<?php  print $ls_instr2008 ?>" "<?php print $ls_disableinstr ?>">
                  </label>
              </div></td>
              <td width="57"><div align="left">2008 </div></td>
              <td width="54"><div align="right">
                  <input name="radioinstr" type="radio" class="sin-borde" value="A" "<?php  print $ls_instrambos ?>" "<?php print $ls_disableinstr ?>">
              </div></td>
              <td width="123"><div align="left">Ambos</div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="13">&nbsp;</td>
              <td height="13" colspan="7">&nbsp;</td>
            </tr>
        </table></td>
      </tr>
      
      <tr class="formato-blanco">
        <td height="13" colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="13" colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="22" colspan="6"><div align="center"><a href="#top">Volver Arriba </a></div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
      </tr>
    </table>
    <div align="center"></div>
  </form>
</body>
<script language="javascript">
function uf_catalogo_beneficiario()
{
	f      = document.form1;
	pagina = "sigesp_catdinamic_bene.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
}

/*Function:  ue_guardar()
	 *
	 *Descripción: Función que se llama al presionar la opcion de "Grabar" en el toolbar o en el menu la cual realiza primero
	               la verificacion de que las cajas de textos no esten vacias. En caso de que exista un campo vacio se enviara
				   un mensaje Javascript al usuario indicandole que campo(s) debe rellenar apoyandose en la funcion valida_null y en caso de que todos los campos estén llenos
				   se procede al llamado del codigo PHP respectivo a si la opcion es "GUARDAR".*/
function ue_guardar()
{//1
var resul="";
f=document.form1;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status=f.hidestatus.value;
if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status!="GRABADO")&&(li_incluir==1))
   {
     with (document.form1)
	      {
	        if (valida_null(txtnombre,"El Nombre de la Empresa esta vacio !!!")==false)
		       {//3
		         f.txtnombre.focus();
		       }//3
		    else
		       {//4
		         if (valida_null(txttitulo,"El Titulo de la empresa esta vacio !!!")==false)
		            {//5
			          f.txttitulo.focus();
		            } //5
		         else
		            {//6
		              if (valida_null(txtdireccion,"La Dirección de la empresa esta vacia !!!")==false)
		                 {//7
			               f.txtdireccion.focus();
		                 }//7
		              else
		                 {//8
		                   if (valida_null(txtperiodo,"El periodo esta vacio !!!")==false)
		                      {//9
			                    f.txtperiodo.focus();
		                      }//9
		                   else
		                      {//10
		 	                    if (valida_null(txtpgasto,"El formato Presupuesto Gasto esta vacio !!!")==false)
			                       {//11
				                   f.txtpgasto.focus();
			                     }//11
			                  else
							     {//12	
								   if (valida_null(txtpingreso,"El formato Presupuesto Ingreso esta vacio !!!")==false)
									  {//13
									    f.txtpingreso.focus();
									  }//13
								   else
									  {//14
									    if (valida_null(txtplanunico,"El formato Plan Unico esta vacio !!!")==false)
										   {//15
											   f.txtplanunico.focus();
										   }//15
										else
										   {//16
										     if (valida_null(txtcontabilidad,"El formato Contabilidad esta vacio !!!")==false)
												{//17
												  f.txtcontabilidad.focus();
												}//17
											 else
												{//18
												  if (valida_null(txtordendeudor,"Orden deudor esta vacio !!!")==false)
													 {//19
														  f.txtordendeudor.focus();
												  	 }//19
												  else
													 {//20
													   if (valida_null(txtordenacreedor,"Orden acreedor esta vacio !!!")==false)
														  {//21
															   f.txtordenacreedor.focus();
														  }//21
							                           else
														  {//22     
													        if (valida_null(txtresultadoanterior,"El resultado Anterior esta vacio !!!")==false)
														  	   {//23
															     f.txtresultadoanterior.focus();
															   }//23
															else
															   {//24
															     if (valida_null(txtresultadoactual,"El resultado Actual esta vacio !!!")==false)
																    {//25
																	  f.txtresultadoactual.focus();
																	}//25
																 else
																	{//26
																	  if (valida_null(txtcapital,"El formato Capital esta vacio !!!")==false)
																	     {//27
																		   f.txtcapital.focus();
																		 }//27
																	  else
																		 {//28
																		   if (valida_null(txtresultado,"El formato Resultado esta vacio !!!")==false)
																			  {//29
																		        f.txtresultado.focus();
																			  }//29
																		   else
																			  {//30
																			    if (valida_null(txtgasto,"El formato Gasto esta vacio !!!")==false)
																				   {//31
																				     f.txtgasto.focus();
																				   }//31
																				else
																				   {//32
																				     if (valida_null(txtingreso,"El formato Ingreso esta vacio !!!")==false)
																					    {//33
																					      f.txtingreso.focus();
																						}//33
																					 else
																						{//34
																					      if (valida_null(txtpasivo,"El formato Pasivo esta vacio !!!")==false)
																							 {//35
																							   f.txtpasivo.focus();
																							 }//35
																						  else
																							 {//36
																							   if (valida_null(txtactivo,"El formato Activo esta vacio !!!")==false)
																								  {//37
																								    f.txtactivo.focus();
																								  }//37
																							   else
																								  {//38
																									 if (f.radioestructura[0].checked == true)
																									 {
																									   li_tipo = f.radioestructura[0].value;
																									 }
																								     else
																									 {
																									   li_tipo = f.radioestructura[1].value;
																									 }
																								     f.hidestmodest.value = li_tipo; 
																									 
																									 if(li_tipo =='2')
																									 {
																											
																									
																											if (valida_null(txtlonestpro1,"La Longitud de la Estrutura Presupuestaria 1 esta vacia !!!")==false)
																											{
																											   f.txtlonestpro1.focus();
																											}//f.operacion.value="GUARDAR";
																											else
																											{//39
																											   if (valida_null(txtlonestpro2,"La Longitud de la Estrutura Presupuestaria 2 esta vacia !!!")==false)
																												{
																													f.txtlonestpro2.focus();
																												}
																												else
																												{//40
																													 if (valida_null(txtlonestpro3,"La Longitud de la Estrutura Presupuestaria 3 esta vacia !!!")==false)
																													 {
																														f.txtlonestpro3.focus();
																													 }
																													 else
																													 {//41
																													 
																														if (valida_null(txtlonestpro4,"La Longitud de la Estrutura Presupuestaria 4 esta vacia !!!")==false)
																														{
																															f.txtlonestpro4.focus();
																														}
																														else
																														{//42
																															
																															if (valida_null(txtlonestpro5,"La Longitud de la Estrutura Presupuestaria 5 esta vacia !!!")==false)
																															{
																																f.txtlonestpro5.focus();
																															}
																															else
																															{//43
																																if(uf_validarlongitud(txtlonestpro5))
																																{
																																    //f.chkpresing.disabled=false;
		
																																	f.operacion.value="GUARDAR";
																																	f.action="sigesp_cfg_d_empresa.php";
																																	f.submit();
																																}
																															}//43
																														}//42
																											         }//41
																									           }//40
																									     }//39
																									 }else 
																									 
																									 {
																									 	if (valida_null(txtlonestpro1,"La Longitud de la Estrutura Presupuestaria 1 esta vacia !!!")==false)
																											{
																											   f.txtlonestpro1.focus();
																											}//f.operacion.value="GUARDAR";
																											else
																											{//39
																											   if (valida_null(txtlonestpro2,"La Longitud de la Estrutura Presupuestaria 2 esta vacia !!!")==false)
																												{
																													f.txtlonestpro2.focus();
																												}
																												else
																												{//40
																													 if (valida_null(txtlonestpro3,"La Longitud de la Estrutura Presupuestaria 3 esta vacia !!!")==false)
																													 {
																														f.txtlonestpro3.focus();
																													 }
																													 else
																													 {//41
																													 	if(uf_validarlongitud(txtlonestpro3))
																														{
																									
																																if (f.chkpresing.checked == true)
																																{
																																 f.hidpresing.value =1;
																																}
																																else
																																{
																																 f.hidpresing.value =0;
																																}
																																
																																f.operacion.value="GUARDAR";
																																f.action="sigesp_cfg_d_empresa.php";
																																f.submit();
																														 }
																													  }//43
																												}
																										}
																											
													
																									 }//IF
																								  }//38
			                                                                                  }//36
		                                                                                  }//34
		                                                                               }//32
		                                                                           }//30
		                                                                      }//28
	                                                                     }//26 
	                                                               }//24
	                                                           }//22
                                                           }//20
													   }//18
												  }//16
											   }//14
										   }//12
                                         }//10
								   }//8
						   }//6
					  }//4
	   }//2
}
else
  {
	  alert("No tiene permiso para realizar esta operación");
  }
}//1

/*Function:  valida_null(field , mensaje)
	 *
	 *Descripción:   Función que se encarga de evaluar al objeto "field" para verificar si esta o no en blanco, en caso de que el objeto 
	                 este vacio se imprime el mensaje y se devuelve false,en caso contrario se devuelve true.
	  *Argumentos:   field: Objeto el cual va a ser chequeado su condicion de vacio. Ejempo: txtcedula.  
	                 mensaje: Cadena de caracteres que se mostrara al usuario en caso de que el contenido del objeto sea igual a null o
					 igual a vacio(blanco).*/
function valida_null(field,mensaje)
{
  with (field) 
  {
    if (value==null||value=="")
      {
        alert(mensaje);
        return false;
      }
    else
      {
   	    return true;
      }
  }
}	
/*Fin de la Funcion valida_null*/


/*Function:  ue_buscar()
	 *
	 *Descripción: Función que se encarga de hacer el llamado al catalogo de los proveedores*/   
   function ue_buscar()
	{
		f=document.form1;
		li_leer=f.leer.value;
		if (li_leer==1)
		{
			f.operacion.value="BUSCAR";
			pagina="sigesp_cfg_cat_empresa.php";
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
		}
		else
		{
			alert("No tiene permiso para realizar esta operación");
		}   
    }
/*Fin de la Función ue_buscar()*/

function currencyFormat(fld, milSep, decSep, e) 
{ 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
	if (whichCode == 8)  return true; // Enter 
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);  
    aux = ''; 

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

	
	function esDigito(sChr)
{ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
}

	function valSep(oTxt)
		{ 
			var bOk = false; 
			var sep1 = oTxt.value.charAt(2); 
			var sep2 = oTxt.value.charAt(5); 
			bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
			bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
			return bOk; 
		} 
		
		function finMes(oTxt)
		{ 
			var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
			var nAno = parseInt(oTxt.value.substr(6), 10); 
			var nRes = 0; 
			switch (nMes)
			{ 
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
		
		function valDia(oTxt)
		{ 
		   var bOk = false; 
		   var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
		   bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
		   return bOk; 
		} 
		
		function valMes(oTxt)
		{ 
			var bOk = false; 
			var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
			bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
			return bOk; 
		} 
		
		function valAno(oTxt)
		{ 
			var bOk = true; 
			var nAno = oTxt.value.substr(6); 
			bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
			if (bOk)
			   { 
				 for (var i = 0; i < nAno.length; i++)
				     { 
				       bOk = bOk && esDigito(nAno.charAt(i)); 
				     } 
			   } 
		 return bOk; 
		 } 
		
		 function valFecha(oTxt)
		 { 
			var bOk = true; 
			if (oTxt.value !="")
			   { 
				bOk = bOk && (valAno(oTxt)); 
				bOk = bOk && (valMes(oTxt)); 
				bOk = bOk && (valDia(oTxt)); 
				bOk = bOk && (valSep(oTxt)); 
				if (!bOk)
				   { 
					 alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
					 oTxt.value = "01/01/1900"; 
					 oTxt.focus(); 
				   } 
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
		if (li_long==10)
		   {
			 ls_string=ls_date.substr(6,4);
			 li_string=parseInt(ls_string,10);
			 if ((li_string>=1900)&&(li_string<=2090))
			    {
				  date.value=ls_date;
			    }
			else
			    {
				  date.value=ls_date.substr(0,6);
			    }
		}
   }
   
/*  esta funcion desabilita los radiobuttom    
     onclick="deshabilitar(this.form,1)" en el metodo del mismo */
function deshabilitar()
{ 
    f=document.form1;
	if(f.radioestmodape[0].checked = true)
	{
	  f.radioestmodape[0].checked = true 
	  f.radioestmodape[0].blur(); 
	  f.radioestmodape[1].blur(); 
	}
	else
	{
	  f.radioestmodape[1].checked = true 
	  f.radioestmodape[0].blur(); 
	  f.radioestmodape[1].blur(); 
	}
}

function uf_catalogo_scgcuentas()
{
	f            = document.form1;
	ls_resultado = f.txtresultado.value;
	ls_tipctares = f.hidresultado.value;//Tipo Cuenta de Resultado.
	pagina       = "sigesp_cfg_cat_scgcuentas.php?txtresultado="+ls_resultado+"&tipctares="+ls_tipctares;
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
}

function uf_catalogo_scgcuentas_financiera()
{
	f            = document.form1;
	ls_ctafinan  = "13";
	pagina       = "sigesp_cfg_cat_scgcuentas_financiera.php?txtresultado="+ls_ctafinan;
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
}

function uf_catalogo_scgcuentas_fiscal()
{
	f            = document.form1;
	ls_ctafiscal = "21";
	pagina       = "sigesp_cfg_cat_scgcuentas_financiera.php?txtresultado="+ls_ctafiscal;
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
}

function uf_catalogo_spgcuentas()
{
	pagina="sigesp_cfg_cat_spgcuentas.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no,top=0,left=0");
} 

function uf_cambio_niveles ()
{
  f                 = document.form1;
  li_nivel          = f.cmbnumniv.value;
  f.hidnumniv.value = li_nivel;
  if (li_nivel=='1')
     {
	   f.txtdesestpro1.readOnly = false;
	   f.txtdesestpro2.value    = "";
	   f.txtdesestpro2.readOnly = true;
	   f.txtdesestpro3.value    = "";
	   f.txtdesestpro3.readOnly = true;
	   f.txtdesestpro4.value    = "";
	   f.txtdesestpro4.readOnly = true;
	   f.txtdesestpro5.value    = "";
	   f.txtdesestpro5.readOnly = true;
	   f.txtlonestpro1.value    = "";
	   f.txtlonestpro1.readOnly = false;
	 
	 }
  else
     {
	   if (li_nivel=='2')
	      {
			f.txtdesestpro1.readOnly = false;
			f.txtdesestpro2.readOnly = false;
			f.txtdesestpro3.value    = "";
		    f.txtdesestpro3.readOnly = true;
		    f.txtdesestpro4.value    = "";
		    f.txtdesestpro4.readOnly = true;
		    f.txtdesestpro5.value    = "";
		    f.txtdesestpro5.readOnly = true;
			f.txtlonestpro2.value    = "";
	   		f.txtlonestpro2.readOnly = false;
			
		  }
	   else
	      {
		    if (li_nivel=='3')
			   {
	             f.txtdesestpro1.readOnly = false;
	             f.txtdesestpro2.readOnly = false;
	             f.txtdesestpro3.readOnly = false;
				 f.txtdesestpro4.value    = "";
				 f.txtdesestpro4.readOnly = true;
				 f.txtdesestpro5.value    = "";
				 f.txtdesestpro5.readOnly = true;
				 f.txtlonestpro3.value    = "";
	   			 f.txtlonestpro3.readOnly = false;
				 
			   }
		     else
			   {
				 if (li_nivel=='4')
				    {
					  f.txtdesestpro1.readOnly = false;
					  f.txtdesestpro2.readOnly = false;
					  f.txtdesestpro3.readOnly = false;
					  f.txtdesestpro4.readOnly = false;
					  f.txtdesestpro5.value    = "";
					  f.txtdesestpro5.readOnly = true;
					  f.txtlonestpro4.value    = "";
	   				  f.txtlonestpro4.readOnly = false;
					
				    }
			     else
				    {
					  f.txtdesestpro1.readOnly = false;
					  f.txtdesestpro2.readOnly = false;
					  f.txtdesestpro3.readOnly = false;
					  f.txtdesestpro4.readOnly = false;
					  f.txtdesestpro5.readOnly = false;
					  f.txtlonestpro5.value    = "";
	                  f.txtlonestpro5.readOnly = false;
					  
					}
			   }
		  } 
	 }
}

function uf_cambio()
{
  f                        = document.form1;
  if (f.radioestructura[0].checked == true)
     {
	   li_tipo = f.radioestructura[0].value;
	 }
  else
     {
	   li_tipo = f.radioestructura[1].value;
	 }
  f.hidestmodest.value = li_tipo; 
  if (li_tipo=='1')
     {
	    f.hidnumniv.value='3';
	   f.cmbnumniv.value        = '3';
	   f.cmbnumniv.disabled     = true;
	   f.txtdesestpro1.value    = "";
	   f.txtdesestpro1.readOnly = false;
	   f.txtdesestpro2.value    = "";
	   f.txtdesestpro2.readOnly = false;
	   f.txtdesestpro3.value    = "";
	   f.txtdesestpro3.readOnly = false;
	   f.txtdesestpro4.value    = "";
	   f.txtdesestpro4.readOnly = true;
	   f.txtdesestpro5.value    = "";
	   f.txtdesestpro5.readOnly = true;
	   //--Longitud
	   f.txtlonestpro1.value    = "";
	   f.txtlonestpro1.readOnly = false;
	   f.txtlonestpro2.value    = "";
	   f.txtlonestpro2.readOnly = false;
	   f.txtlonestpro3.value    = "";
	   f.txtlonestpro3.readOnly = false;
	   f.txtlonestpro4.value    = "";
	   f.txtlonestpro4.readOnly = true;
	   f.txtlonestpro5.value    = "";
	   f.txtlonestpro5.readOnly = true;
	   f.txtdesestpro1.focus();	 
	 }
  else
     {
	    f.hidnumniv.value='5';
	   f.cmbnumniv.value        = '5';
	   f.cmbnumniv.disabled     = true;
	   f.txtdesestpro1.value    = "";
	   f.txtdesestpro1.readOnly = false;
	   f.txtdesestpro2.value    = "";
	   f.txtdesestpro2.readOnly = false;
	   f.txtdesestpro3.value    = "";
	   f.txtdesestpro3.readOnly = false;
	   f.txtdesestpro4.value    = "";
	   f.txtdesestpro4.readOnly = false;
	   f.txtdesestpro5.value    = "";
	   f.txtdesestpro5.readOnly = false;
	   //long
	   f.txtlonestpro1.value    = "";
	   f.txtlonestpro1.readOnly = false;
	   f.txtlonestpro2.value    = "";
	   f.txtlonestpro2.readOnly = false;
	   f.txtlonestpro3.value    = "";
	   f.txtlonestpro3.readOnly = false;
	   f.txtlonestpro4.value    = "";
	   f.txtlonestpro4.readOnly = false;
	   f.txtlonestpro5.value    = "";
	   f.txtlonestpro5.readOnly = false;
	   f.txtdesestpro1.focus();	 
	 }
  }
function uf_cambio2()
{
  f = document.form1;
 if (f.chkintcompret.checked==true)
     { 
	   f.txtbdintcmp.disabled=false;
	 }
  else
     { 
	   f.txtbdintcmp.disabled=true;
	   f.txtbdintcmp.value="";
	 }
}

function uf_cambio3()
{
  f = document.form1;
  li_bdintecmp=f.txtbdintcmp.value;
 if (li_bdintecmp!="")
     { 
	   f.chkintcompret.disabled=false;
	 }
  else
     { 
	   f.chkintcompret.checked=false;
	   f.chkintcompret.disabled=true;
	   f.txtbdintcmp.disabled=true;
	 }
}


function ue_llamarcodigo2()
{
  f = document.form1;
  li_bdconso=f.txtbdconso.value;
  li_codaltempcon=f.txtcodaltempcon.value;
 if ((f.chkempconsolidadora.checked==true)&&(li_codaltempcon==""))
   { 
	 alert("Debe introducir el código alterno de la empresa");
   }
 if((li_bdconso!="")&&(li_codaltempcon==""))
   {
     alert("Debe introducir el código alterno de la empresa");
   }
}
  
function uf_validarlongitud(field)
{
  with (field) 
  {
    if (value>=26)
      {
        alert("La Longitud del Nivel debe estar entre 1 y 25");
		value="";
       return false;
      }
	  else
	  {
	    return true;
	  }
  }
}

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
		if(campo=="cod")
		{
			document.form1.txtcodaltempcon.value=cadena;
		}
	}	
</script>
</html>