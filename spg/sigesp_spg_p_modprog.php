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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
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
<title>Modificaci&oacute;n de Presupuesto Programado (Mensual) </title>
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

<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="7" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="780" height="40"></td>
  </tr>
    <tr>
    <td width="432" height="20" colspan="7" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo25">Contabilidad Presupuestaria de Gasto</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>	 </td>
  </tr>
  <tr>
       <?php
	   if(array_key_exists("confinstr",$_SESSION["la_empresa"]))
	  {
      if($_SESSION["la_empresa"]["confinstr"]=='A')
	  {
   ?>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
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
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td width="25" height="20" align="center" class="toolbar"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></td>
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
	require_once("class_funciones_gasto.php");
	require_once("sigesp_spg_c_mod_programado.php");
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	$io_modprog= new sigesp_spg_c_mod_programado();
	
	$arre=$_SESSION["la_empresa"];
    $li_estmodest=$arre["estmodest"];
	
	$ls_estmodape=$arre["estmodape"];// si la apertura fue mensual o trimestral
	
	$ls_estmodprog=$arre["estmodprog"];// si esta configurado para realizar la modificaciòn al monto programado	
	if ($ls_estmodprog=="1")
	{
		if($ls_estmodape!="0")
		{
			print("<script language=JavaScript>");
			print(" alert('La Programación se Realizo Trimestral, No se puede usar esta opción .');");
			print(" location.href='sigespwindow_blank.php'");
			print("</script>");
		}	
	}
	else
	{
		print("<script language=JavaScript>");
		print(" alert('No Esta Configurado para Realizar la Modificación del Programado Presupuestario .');");
		print(" location.href='sigespwindow_blank.php'");
		print("</script>");
	}	
	
	
	$ls_empresa=$arre["codemp"];
	$io_fun_gasto=new class_funciones_gasto();
	$io_fun_gasto->uf_load_seguridad("SPG","sigesp_spg_p_modprog.php",$ls_permisos,$la_seguridad,$la_permisos);    
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $title[1]="Mes Disminución";   
	$title[2]="Mes Aumento";
	$title[3]="Cuenta"; 
	$title[4]="Monto";
	$grid1="grid_modProg";	
	
    function cargar_linea_blanca($ls_fila, &$ls_object)
	{
		$ls_object[$ls_fila][1]="<input type=text name=txtmes1".$ls_fila." value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15 style=text-align:center>";
		$ls_object[$ls_fila][2]="<input type=text name=txtmes2".$ls_fila." value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15 style=text-align:center>";
		$ls_object[$ls_fila][3]="<input type=text name=txtcuentaplan".$ls_fila." value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15 style=text-align:center>";
		$ls_object[$ls_fila][4]="<input type=text name=txtmontoact".$ls_fila." value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15 style=text-align:right>";
	
	}/// fin de la funciòn 
	if(array_key_exists("la_logusr",$_SESSION))
	{
		$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
	$ls_logusr="";
	}
	$ls_sistema="SPG";
	$ls_ventana="sigesp_spg_p_modprog.php";
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
	$fun		 = new class_funciones();
	$int_scg	 = new class_sigesp_int_scg();
	$int_spg	 = new class_sigesp_int_spg();
	$msg		 = new class_mensajes();
	$io_grid	 = new grid_param();
	$int_fec	 = new class_fecha();
	$io_sql  	 = new class_sql($con);

	$la_emp=$_SESSION["la_empresa"];
	$li_estmodest  = $la_emp["estmodest"];
	$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
    $ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
	
	if(array_key_exists("operacion",$_POST))
	{
		$ls_fecha       = $_POST["txtfecha"];
		$ls_fila		= $_POST["fila"];	
		$ls_operacion=$_POST["operacion"];
		$ls_cuentaplan  = $_POST["txtcuenta"];		
		$ls_monto= $_POST["txtmonto"];
		$ls_denominacion=$_POST["txtdenominacion"];
		$ls_codestpro1=$_POST["codestpro1"];
		$ls_codestpro1=str_pad($ls_codestpro1,25,"0",0);
		$ls_codestpro2=$_POST["codestpro2"];
		$ls_codestpro2=str_pad($ls_codestpro2,25,"0",0);
		$ls_codestpro3=$_POST["codestpro3"];
		$ls_codestpro3=str_pad($ls_codestpro3,25,"0",0);
		$ls_codestpro4="00";
		$ls_codestpro4=str_pad($ls_codestpro4,25,"0",0);
		$ls_codestpro5="00";
		$ls_codestpro5=str_pad($ls_codestpro5,25,"0",0);
		
		$ls_denestpro1=$_POST["denestpro1"];
		$ls_denestpro2=$_POST["denestpro2"];
		$ls_denestpro3=$_POST["denestpro3"];
		if ($li_estmodest==2)
		{
			$ls_codestpro4=$_POST["codestpro4"];
			$ls_codestpro4=str_pad($ls_codestpro4,25,"0",0);
			$ls_codestpro5=$_POST["codestpro5"];
			$ls_codestpro5=str_pad($ls_codestpro5,25,"0",0);
			$ls_denestpro4=$_POST["denestpro5"];
		    $ls_denestpro5=$_POST["denestpro5"];
		}
		$li_mes1=$_POST["mes1"];
		$li_mes2=$_POST["mes2"];
		$li_existe=$_POST["existe"];
		$li_estcla=$_POST["estcla"];
	}
	else
	{
		$ls_operacion="NUEVO";
		$ls_denominacion="";
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		$ls_fecha=$fun->uf_cerosizquierda($ls_dia,2)."/".$fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
		$li_fila = 0;
		$li_estapro = 0;		
		
		$ls_cuentaplan  = "";
		$ls_denominacion ="";
		$ls_monto="0,00";
		$ls_codestpro1="";
		$ls_codestpro2="";
		$ls_codestpro3="";
		$ls_denestpro1="";
		$ls_denestpro2="";
		$ls_denestpro3="";
		if ($li_estmodest==2)
		{
			$ls_codestpro4="";
			$ls_codestpro5="";
			$ls_denestpro4="";
			$ls_denestpro5="";
		}
		$li_mes1=0;
		$li_mes2=0;
		$li_existe="N";
		$li_estcla="";
	}


	
		 
	if($ls_operacion=="NUEVO")//Acciones para un comprobante nuevo
	{
		$ls_codestpro1="";
		$ls_codestpro2="";
		$ls_codestpro3="";
		$ls_codestpro4="";
		$ls_codestpro5="";
		$ls_denestpro1="";
		$ls_denestpro2="";
		$ls_denestpro3="";
		$ls_denestpro4="";
		$ls_denestpro5="";
		$ls_cuentaplan  = "";
		$ls_denominacion = "";
		$ls_tipo      = "";
		$ls_fila		 =1;
		$li_estapro   = 0;
		$totalDI=1;
		$totalAU=1;
		$ls_monto="0,00";	
		$li_estmodest=$la_emp["estmodest"];//modalidad
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
		$li_mes1=0;
		$li_mes2=0;
		$ls_fila=1;
		$li_existe="N";
		$ls_fila=1;
		$li_estcla="";
		cargar_linea_blanca($ls_fila, $ls_object);	
	}

	
	if($ls_operacion=="GUARDAR")
	{
		$ls_fecha       = $_POST["txtfecha"]; 
		$ls_denominacion=$_POST["txtdenominacion"];
		$ls_fila		= $_POST["fila"];		
		$ls_cuentaplan  = $_POST["txtcuenta"];		
		$ls_monto= $_POST["txtmonto"];
		
		$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
		$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
		$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
		$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		
		$ls_codestpro1=$_POST["codestpro1"];
		$ls_codest1=str_pad($ls_codestpro1,25,"0",0);
		$ls_codestpro2=$_POST["codestpro2"];
		$ls_codest2=str_pad($ls_codestpro2,25,"0",0);
		$ls_codestpro3=$_POST["codestpro3"];
		$ls_codest3=str_pad($ls_codestpro3,25,"0",0);
		$ls_codestpro4="00";
		$ls_codest4=str_pad($ls_codestpro4,25,"0",0);
		$ls_codestpro5="00";
		$ls_codest5=str_pad($ls_codestpro5,25,"0",0);
		if ($li_estmodest==2)
		{
			$ls_codestpro4=$_POST["codestpro4"];
			$ls_codest4=str_pad($ls_codestpro4,25,"0",0);
			$ls_codestpro5=$_POST["codestpro5"];
			$ls_codest5=str_pad($ls_codestpro5,25,"0",0);
		}
		$li_estcla=$_POST["estcla"];
		
		$li_mes1=$_POST["mes1"];
		$li_mes2=$_POST["mes2"];
		$io_modprog->io_sql->begin_transaction();// iniciamos la transaccion
		$lb_valido=$io_modprog->uf_buscar_disponibilidad_mensual($li_mes1,$li_mes2,$ls_codest1,$ls_codest2,$ls_codest3,$ls_codest4,$ls_codest5,$li_estcla, $ls_cuentaplan, $ls_monto, $ls_fecha, $la_security);
		
		if ($lb_valido)
		{
			$io_modprog->io_sql->commit();
			$li_existe="C";	
		}
		else
		{
			$io_modprog->io_sql->rollback();	
		}
		
			
			
			if ($lb_valido)
			{
				$ls_fila=$ls_fila+1;
			}
			for ($i=1;$i<$ls_fila;$i++)
			{
			   $li_mespri=$_POST["txtmes1".$i];
			   if ($li_mespri=="")
			   {
					$li_mespri=$li_mes1;
			   }
			   switch ($li_mespri) 
			   {
					case "1":
						$li_mespri="Enero";
					break;
					case "2":
						$li_mespri="Febrero";
					break;
					case "3":
						$li_mespri="Marzo";
					break;
					case "4":
						$li_mespri="Abril";
					break;
					case "5":
						$li_mespri="Mayo";
					break;
					case "6":
						$li_mespri="Junio";
					break;
					case "7":
						$li_mespri="Julio";
					break;
					case "8":
						$li_mespri="Agosto";
					break;
					case "9":
						$li_mespri="Septiembre";
					break;
					case "10":
						$li_mespri="Octubre";
					break;
					case "11":
						$li_mespri="Noviembre";
					break;
					case "12":
						$li_mespri="Diciembre";
					break;
				}
				
			   $li_messegu=$_POST["txtmes2".$i];
			   if ($li_messegu=="")
			   {
					$li_messegu=$li_mes2;
			   }
			   
			   switch ($li_messegu) 
			   {
					case "1":
						$li_messegu="Enero";
					break;
					case "2":
						$li_messegu="Febrero";
					break;
					case "3":
						$li_messegu="Marzo";
					break;
					case "4":
						$li_messegu="Abril";
					break;
					case "5":
						$li_messegu="Mayo";
					break;
					case "6":
						$li_messegu="Junio";
					break;
					case "7":
						$li_messegu="Julio";
					break;
					case "8":
						$li_messegu="Agosto";
					break;
					case "9":
						$li_messegu="Septiembre";
					break;
					case "10":
						$li_messegu="Octubre";
					break;
					case "11":
						$li_messegu="Noviembre";
					break;
					case "12":
						$li_messegu="Diciembre";
					break;
				}
				
				
			   $ls_monto1=$_POST["txtmontoact".$i];
				if ( $ls_monto1=="")
			   {
					 $ls_monto1=$ls_monto;
			   }
			   $ls_cuenta1=$_POST["txtcuentaplan".$i];
			   if ( $ls_cuenta1=="")
			   {
					 $ls_cuenta1=$ls_cuentaplan;
			   }
			   $ls_object[$i][1]="<input type=text name=txtmes1".$i." value='".$li_mespri."' class=sin-borde readonly style=text-align:center size=15 maxlength=15 style=text-align:center>";
			   $ls_object[$i][2]="<input type=text name=txtmes2".$i." value='".$li_messegu."' class=sin-borde readonly style=text-align:center size=15 maxlength=15 style=text-align:center>";
			   $ls_object[$i][3]="<input type=text name=txtcuentaplan".$i."  value='".trim($ls_cuenta1)."' class=sin-borde readonly style=text-align:center size=15 maxlength=15 style=text-align:center>";
			   $ls_object[$i][4]="<input type=text name=txtmontoact".$i." value='".trim($ls_monto1)."' class=sin-borde readonly style=text-align:center size=15 maxlength=15 style=text-align:right>";
			}
				
		cargar_linea_blanca($ls_fila, &$ls_object);
	}
	
	function  uf_get_nombremes($as_mes)
	{
	 $ls_nombre="";
	 switch($as_mes)
	 {
	  case "01":$ls_nombre= "Enero";
	  break;
	  case "02":$ls_nombre= "Febrero";
	  break;
	  case "03":$ls_nombre= "Marzo";
	  break;
	  case "04":$ls_nombre= "Abril";
	  break;
	  case "05":$ls_nombre= "Mayo";
	  break;
	  case "06":$ls_nombre= "Junio";
	  break;
	  case "07":$ls_nombre= "Julio";
	  break;
	  case "08":$ls_nombre= "Agosto";
	  break;
	  case "09":$ls_nombre= "Septiembre";
	  break;
	  case "10":$ls_nombre= "Octubre";
	  break;
	  case "11":$ls_nombre= "Noviembre";
	  break;
	  case "12":$ls_nombre= "Diciembre";
	  break; 
	 }
	 
	 return $ls_nombre;
	}

	if($ls_operacion == "CARGAR_DT")
	{
	 $ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
     $ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
     $ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
     $ls_incio1=25-$ls_loncodestpro1;
     $ls_incio2=25-$ls_loncodestpro2;
     $ls_incio3=25-$ls_loncodestpro3;
     if($li_estmodest == 2)
     {
	  $ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	  $ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
	  $ls_incio4=25-$ls_loncodestpro4;
	  $ls_incio5=25-$ls_loncodestpro5;
	 }
	 $ls_codestpro1    =substr($ls_codestpro1,$ls_incio1,$ls_loncodestpro1);
	 $ls_codestpro2    =substr($ls_codestpro2,$ls_incio2,$ls_loncodestpro2);
	 $ls_codestpro3    =substr($ls_codestpro3,$ls_incio3,$ls_loncodestpro3);
	 if($li_estmodest == 2)
	 {
	  $ls_codestpro4=substr($ls_codestpro4,$ls_incio4,$ls_loncodestpro4);
	  $ls_codestpro5=substr($ls_codestpro5,$ls_incio5,$ls_loncodestpro5);
	 }
	 $ls_fila=2;
	 $ls_mes1 = uf_get_nombremes($_POST["mes1"]);
	 $ls_mes2 = uf_get_nombremes($_POST["mes2"]);          
	 $ls_object[1][1]="<input type=text name=txtmes11 value='".$ls_mes1."' class=sin-borde readonly style=text-align:center size=15 maxlength=15 style=text-align:center>";
	 $ls_object[1][2]="<input type=text name=txtmes21 value='".$ls_mes2."' class=sin-borde readonly style=text-align:center size=15 maxlength=15 style=text-align:center>";
	 $ls_object[1][3]="<input type=text name=txtcuentaplan1  value='".$_POST["cuenta"]."' class=sin-borde readonly style=text-align:center size=15 maxlength=15 style=text-align:center>";
	 $ls_object[1][4]="<input type=text name=txtmontoact1 value='".number_format($_POST["monto"],2,",",".")."' class=sin-borde readonly style=text-align:center size=15 maxlength=15 style=text-align:right>";
	 cargar_linea_blanca($ls_fila,&$ls_object);
	}

		
?> 
<form name="form1" method="post" action=""><div >
<?php 		
	$io_fun_gasto->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_gasto);		
    //////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>

<table width="780" height="367" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-nuevo">
        <td height="20" colspan="5">Modificaci&oacute;n de Presupuesto Programado (Mensual) </td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td colspan="3">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="161" height="20" style="text-align:right">&nbsp;</td>
        <td height="20" colspan="3">&nbsp;</td>
        <td width="165" height="20"><div align="left">Fecha
            <input name="txtfecha" type="text" id="txtfecha" style="text-align:center" onBlur="valFecha(document.form1.txtfecha)" value="<?php print $ls_fecha?>" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" size="15" maxlength="15" datepicker="true">
        </div></td>
      </tr>
      
	  <?php	 
	  if($li_estmodest==1)
	  {
	  ?>	 
      <tr >
         <td height="17" style="text-align:right" class="texto-azul">Unidad Ejecutora </td>
        <td colspan="3">&nbsp;</td>
        <td height="17">&nbsp;</td>
      </tr>
      <tr >
        <td height="22"><div align="right"><?php print $arre["nomestpro1"];  ?></div></td>
        <td colspan="3"><input name="codestpro1" type="text" id="codestpro1" size="<?php print $ls_loncodestpro1; ?>" maxlength="20" style="text-align:center"   value="<? print $ls_codestpro1?>" readonly>
            <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
            <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" size="53" value="<?php print $ls_denestpro1 ?>" readonly>
            <div align="left"> </div></td>
        <td height="12">&nbsp;</td>
      </tr>
      <tr >
        <td><div align="right"><?php print $arre["nomestpro2"] ; ?></div></td>
        <td colspan="3"><input name="codestpro2" type="text" id="codestpro2" size="<?php print $ls_loncodestpro2; ?>" maxlength="6" style="text-align:center" value="<? print $ls_codestpro2?>" readonly>
            <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
            <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" size="53" value="<?php print $ls_denestpro2 ?>" readonly></td>
        <td height="12">&nbsp;</td>
      </tr>
      <tr >
        <td height="22"><div align="right"><?php print $arre["nomestpro3"] ; ?></div></td>
        <td colspan="3"><div align="left">
            <input name="codestpro3" type="text" id="codestpro3" size="<?php print $ls_loncodestpro3; ?>" maxlength="3" style="text-align:center" value="<? print $ls_codestpro3?>" readonly>
            <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
            <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" size="53" value="<?php print $ls_denestpro3 ?>" readonly>
        </div></td>
        <td height="12">&nbsp;</td>
      </tr>
	  <?php
	   }
	   else
	   {
	  ?>
      <tr >
        <td height="22"><div align="right"><?php print $arre["nomestpro1"];  ?></div></td>
        <td colspan="3"><input name="codestpro1" type="text" id="codestpro1" size="<?php print $ls_loncodestpro1; ?>" maxlength="2" style="text-align:center"  value="<? print $ls_codestpro1?>" readonly>
            <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
            <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" size="53" value="<?php print $ls_denestpro1 ?>" readonly>
            <div align="left"> </div></td>
        <td height="12">&nbsp;</td>
      </tr>
      <tr >
        <td height="22"><div align="right"><?php print $arre["nomestpro2"] ; ?></div></td>
        <td colspan="3"><input name="codestpro2" type="text" id="codestpro2" size="<?php print $ls_loncodestpro2; ?>" maxlength="2" style="text-align:center" value="<? print $ls_codestpro2?>" readonly>
            <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
            <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" size="53" value="<?php print $ls_denestpro2 ?>" readonly></td>
        <td height="12">&nbsp;</td>
      </tr>
      <tr >
        <td height="22"><div align="right"><?php print $arre["nomestpro3"] ; ?></div></td>
        <td colspan="3"><div align="left">
            <input name="codestpro3" type="text" id="codestpro3" size="<?php print $ls_loncodestpro3; ?>" maxlength="2" style="text-align:center" value="<? print $ls_codestpro3?>" readonly>
            <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
            <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" size="53" value="<?php print $ls_denestpro3 ?>" readonly>
        </div></td>
        <td height="12">&nbsp;</td>
      </tr>
      <tr >
        <td height="22"><div align="right"><?php print $arre["nomestpro4"] ; ?></div></td>
        <td colspan="3"><div align="left">
            <input name="codestpro4" type="text" id="codestpro4" size="<?php print $ls_loncodestpro4; ?>" maxlength="2" style="text-align:center" value="<? print $ls_codestpro4?>" readonly>
            <a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
            <input name="denestpro4" type="text" class="sin-borde" id="denestpro4" size="53" value="<?php print $ls_denestpro4 ?>" readonly>
        </div></td>
        <td height="12">&nbsp;</td>
      </tr>
	  <br>
      <tr>
        <td height="22"><div align="right"><?php print $arre["nomestpro5"] ; ?></div></td>
        <td colspan="3"><div align="left">
            <input name="codestpro5" type="text" id="codestpro5" size="<?php print $ls_loncodestpro5; ?>" maxlength="3" style="text-align:center" value="<? print $ls_codestpro5?>" readonly>
            <a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
            <input name="denestpro5" type="text" class="sin-borde" id="denestpro5" size="53" value="<?php print $ls_denestpro5 ?>" readonly>
        </div></td>
        <td height="13">&nbsp;</td>
      </tr>
	   <?php 
	  }
	?>
       <tr>
         <td height="22"><div align="right">Cuenta</div></td>
         <td colspan="3"><input name="txtcuenta" type="text" id="txtcuenta" readonly="true" value="<?php print $ls_cuentaplan ;?>" size="22" style="text-align:center">
             <a href="javascript:catalogo_cuentasSPG();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas de Presupuestarias de Gasto"></a>
             <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion3" style="text-align:left" value="<?php print $ls_denominacion ?>" size="53" maxlength="254"></td>
         <td height="13">&nbsp;</td>
       </tr>
       <tr>
         <td height="22"><div align="right">Mes Disminuir</div></td>
         <td width="157" height="13"><label>
           <select name="cbmesdis">
             <option value="--">Ninguno</option>
             <option value="1">Enero</option>
             <option value="2">Febrero</option>
             <option value="3">Marzo</option>
             <option value="4">Abril</option>
             <option value="5">Mayo</option>
             <option value="6">Junio</option>
             <option value="7">Julio</option>
             <option value="8">Agosto</option>
             <option value="9">Septiembre</option>
             <option value="10">Octubre</option>
             <option value="11">Noviembre</option>
             <option value="12">Diciembre</option>
           </select>
         </label></td>
         <td width="125" height="3"><div align="right">Mes Aumentar </div></td>
         <td width="170"><label>
           <select name="cbmesau">
		   	<option value="--">Ninguno</option>
             <option value="1">Enero</option>
             <option value="2">Febrero</option>
             <option value="3">Marzo</option>
             <option value="4">Abril</option>
             <option value="5">Mayo</option>
             <option value="6">Junio</option>
             <option value="7">Julio</option>
             <option value="8">Agosto</option>
             <option value="9">Septiembre</option>
             <option value="10">Octubre</option>
             <option value="11">Noviembre</option>
             <option value="12">Diciembre</option>
           </select>
         </label></td>
         <td height="13">&nbsp;</td>
       </tr>
      
       <tr>
         <td height="22"><div align="right">Monto</div></td>
         <td height="13"><input name="txtmonto" type="text" id="txtmonto" size="22" maxlength="20" style="text-align:center" onKeyUp="ue_validarcomas_puntos(this)" onBlur="javascript:uf_format(this);"  value="<? print $ls_monto?>"></td>
         <td height="13">&nbsp;</td>
         <td height="13">&nbsp;</td>
         <td height="13">&nbsp;</td>
       </tr>
       <tr>
         <td height="13" colspan="5">&nbsp;</td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13"><a href="javascript:uf_modificar();">Modificar Programaci&oacute;n</a></td>
      </tr>
      <tr>
        <td height="13" colspan="5">&nbsp;</td>
      </tr>
      <tr>
        <td height="13" colspan="5">  <div align="center">
          <?php $io_grid->makegrid($ls_fila,$title,$ls_object,770,'Listados de Periodos Mensuales Modificados',$grid1);?>
        <input name="fila" type="hidden" id="fila" value="<?php print $ls_fila;?>">	  </td> 
      </tr>
      <tr>
        <td height="13" colspan="5">&nbsp;</td>
      </tr>
    </table>
  <div align="center">
    <input name="operacion" type="hidden" id="operacion">    
	<input name="estmodest" type="hidden" id="estmodest" value="<?php print  $li_estmodest; ?>"> 
	<input name="mes1" type="hidden" id="mes1" value="<?php print  $li_mes1; ?>">  
	<input name="mes2" type="hidden" id="mes2" value="<?php print  $li_mes2; ?>">
	<input name="monto" type="hidden" id="monto">
	<input name="cuenta" type="hidden" id="cuenta">
	<input name="existe" type="hidden" id="existe" value="<?php print  $li_existe; ?>">
    <input name="estcla" type="hidden" id="estcla" value="<?php print  $li_estcla; ?>">
  </div>
</div>
</form>
</body>
<script language="javascript">
//Funciones de operaciones sobre el comprobante
f = document.form1;
function ue_nuevo()
{
	f.operacion.value="NUEVO";
	f.submit();
}
function ue_close()
{
	close()
}

function uf_modificar()
{
	codest1=f.codestpro1.value;
	codest2=f.codestpro2.value;
	codest3=f.codestpro3.value;
	estmodest=f.estmodest.value;
	cuenta=f.txtcuenta.value;
	
	mes1=f.cbmesdis.value; 
	mes2=f.cbmesau.value; 
	f.mes1.value=mes1;
	f.mes2.value=mes2;
	monto= f.txtmonto.value;
	if (estmodest==2)
	{
		codest4=f.codestpro4.value;
		codest5=f.codestpro5.value;
	}
	
	if ((codest1!="")&&(codest2!="")&&(codest3!="")&&(cuenta!="")&&(mes1!="--")&&(mes2!="--")&&(monto!="0"))
	{
		if (mes1!=mes2)
		{
			if (confirm("¿ Esta seguro de realizar la modificación al monto programado?, recuerde que no existe reverso para este proceso"))
			{
				f.operacion.value="GUARDAR";
				f.submit();
			}
		}
		else
		{
			alert(" El Mes de Disminución no puede ser igual al Mes de Aumento!!!!!!");
		}	
	}
	else
	{
		alert("Debe ingresar todos los datos");
	}
}

function  uf_format(obj)
{
	ldec_monto=obj.value;
	obj.value=uf_convertir(ldec_monto);
}


function catalogo_estpro1()
{
	   existe=f.existe.value;
	   if (existe=="N")
	   {
	   	    pagina="sigesp_cat_public_estpro1.php";
	   		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	   }
	   else
	   {
	   		alert ("Debe generar un nuevo Procesos de Modificaciòn, seleccione la opción de nuevo en el Menú de la Pantalla ");
	   }
}

function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	estcla=f.estcla.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&estcla="+estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}


function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	codestpro3=f.codestpro3.value;
	denestpro3=f.denestpro3.value;
	estmodest=f.estmodest.value;
	existe=f.existe.value;
	estcla=f.estcla.value;
	if (existe=="N")
	{
		if(estmodest==1)
		{
			if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3=="")&&(denestpro3==""))
			{
				pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
						+"&denestpro2="+denestpro2+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
				pagina="sigesp_cat_public_estpro.php";
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
		}
		else
		{
			if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
			{
				pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
						+"&denestpro2="+denestpro2+"&estcla="+estcla;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			}
			else
			{
			   alert("Seleccione la Estructura nivel 2");
			}
		}
	}
	else
	{
		alert ("Debe generar un nuevo Procesos de Modificaciòn, seleccione la opción de nuevo en el Menú de la Pantalla ");
	}
}

function catalogo_estpro4()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	codestpro3=f.codestpro3.value;
	denestpro3=f.denestpro3.value;
	estcla=f.estcla.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!=""))
	{
			pagina="sigesp_cat_public_estpro4.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 3 ");
	}
}
function catalogo_estpro5()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	codestpro3=f.codestpro3.value;
	denestpro3=f.denestpro3.value;
	codestpro4=f.codestpro4.value;
	denestpro4=f.denestpro4.value;
	codestpro5=f.codestpro5.value;
	denestpro5=f.denestpro5.value;
	estcla=f.estcla.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!="")&&
	   (codestpro4!="")&&(denestpro4!="")&&(codestpro5=="")&&(denestpro5==""))
	{
			pagina="sigesp_cat_public_estpro5.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&codestpro4="+codestpro4
					+"&denestpro4="+denestpro4+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
			pagina="sigesp_cat_public_estprograma.php";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
}

function catalogo_cuentasSPG()
 {
       f=document.form1;
       codest1=f.codestpro1.value;
       codest2=f.codestpro2.value;
  	   codest3=f.codestpro3.value;
	   estmodest   = f.estmodest.value;
	   estcla = f.estcla.value;
	   
	   if(estmodest==1)
	   {
		   if((codest1!="")&&(codest2!="")&&(codest3!=""))
		   {
		   pagina="sigesp_cat_ctasspg.php?codestpro1="+codest1+"&hicodest2="+codest2+"&hicodest3="+codest3+"&estcla="+estcla;
		   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no");
		   }
		   else
		   {
		   alert("Debe completar la programatica");
		   }
	    }   
		else
		{
		   codest4=f.codestpro4.value;
		   codest5=f.codestpro5.value;
		   if((codest1!="")&&(codest2!="")&&(codest3!="")&&(codest4!="")&&(codest5!=""))
		   {
		   pagina="sigesp_cat_ctasspg.php?codestpro1="+codest1+"&hicodest2="+codest2+"&hicodest3="+codest3+"&hicodest4="+codest4
		   +"&hicodest5="+codest5+"&estcla="+estcla;
		   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=750,height=400,resizable=yes,location=no");
		   }
		   else
		   {
		   alert("Debe completar la programatica");
		   }
		}
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
  
}

function ue_validarcomillas(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
	texto = valor.value.substring(r,r+1);
	if((texto != "'")&&(texto != '"')&&(texto != "\\"))
	{
	textocompleto += texto;
	}
	}
	valor.value=textocompleto;
}

function currency_Format(fld, milSep, decSep, e) 
{ 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
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
	if (len == 0) fld.value = ''; 
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
   
function ue_validarcomas_puntos(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto != ",")&&(texto != '.'))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}
function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("sigesp_cat_regmodpresupuestaria.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
	 alert("No tiene permiso para realizar esta operacion");
	}
}
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>