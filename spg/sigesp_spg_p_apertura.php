<?php 
session_start(); 
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
    
	$dat=$_SESSION["la_empresa"];
	$ls_empresa=$dat["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SPG";
	$ls_ventanas="sigesp_spg_p_apertura.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;
	
	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos           = $_POST["permisos"];
			$la_accesos["leer"]    = $_POST["leer"];
			$la_accesos["incluir"] = $_POST["incluir"];
			$la_accesos["cambiar"] = $_POST["cambiar"];
			$la_accesos["eliminar"]= $_POST["eliminar"];
			$la_accesos["imprimir"]= $_POST["imprimir"];
			$la_accesos["anular"]  = $_POST["anular"];
			$la_accesos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]="";
		$la_accesos["incluir"]="";
		$la_accesos["cambiar"]="";
		$la_accesos["eliminar"]="";
		$la_accesos["imprimir"]="";
		$la_accesos["anular"]="";
		$la_accesos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
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
<title>APERTURA DE CUENTAS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="javascript1.2" src="js/valida_tecla_grid.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="styleshee t" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo2 {font-size: 15px}
-->
</style>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
.Estilo3 {color: #6699CC}
-->
</style>
</head>

<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="1162" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="798" height="40"></td>
  </tr>
  	  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo3">Contabilidad Presupuestaria de Gasto</td>
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
    <td height="20" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><img src="../shared/imagebank/tools20/espacio.gif" width="4" height="20"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif"  title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<form name="form1" method="post" action="">
<p>
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");
require_once("../shared/class_folder/class_sigesp_int_spi.php");
require_once("../shared/class_folder/grid_param.php");
require_once("sigesp_spg_class_apertura.php");
$io_include = new sigesp_include();
$io_connect= $io_include-> uf_conectar ();
$io_sql=new class_sql($io_connect);
$io_msg=new class_mensajes();
$io_function=new class_funciones();
$io_class_aper=new sigesp_spg_class_apertura();
$ds_aper=new class_datastore();
$io_class_grid=new grid_param();

if(array_key_exists("operacion",$_POST))
{
  $ls_operacion=$_POST["operacion"];
}
else
{
  $ls_operacion="";
}

if(array_key_exists("li_totnum",$_POST))
{
  $li_totnum=$_POST["li_totnum"];
}
else
{
  $li_totnum=0;
}

if(array_key_exists("radiobutton",$_POST))
{
  $ls_opcion=$_POST["radiobutton"];
}
else
{
  $ls_opcion="";
}
if (array_key_exists("txtFecha",$_POST))
{
  $ldt_fecha=$_POST["txtFecha"];
}
else
{
	$ls_periodo=$dat["periodo"];
	$ls_periodo=substr($ls_periodo,0,4);
	$ldt_fecha="01/01/".$ls_periodo;
}
if (array_key_exists("txtDenominacion",$_POST))
{
  $ls_denominacion=$_POST["txtDenominacion"];
}
else
{
  $ls_denominacion="";
}
if	(array_key_exists("codestpro1",$_POST))
	{
	  $ls_codestpro1=$_POST["codestpro1"];
	}
else
	{
	  $ls_codestpro1="";
	}
if	(array_key_exists("denestpro1",$_POST))
	{
	  $ls_denestpro1=$_POST["denestpro1"];
	}
else
	{
	  $ls_denestpro1="";
	}	
	
if	(array_key_exists("codestpro2",$_POST))
	{
	  $ls_codestpro2=$_POST["codestpro2"];
	}
else
	{
	  $ls_codestpro2="";
	}
	
if	(array_key_exists("denestpro2",$_POST))
	{
	  $ls_denestpro2=$_POST["denestpro2"];
	}
else
	{
	  $ls_denestpro2="";
	}		

if	(array_key_exists("codestpro3",$_POST))
	{
	  $ls_codestpro3=$_POST["codestpro3"];
	}
else
	{
	  $ls_codestpro3="";
	}
	
if	(array_key_exists("denestpro3",$_POST))
	{
	  $ls_denestpro3=$_POST["denestpro3"];
	}
else
	{
	  $ls_denestpro3="";
	}			
	if	(array_key_exists("codestpro4",$_POST))
	{
	  $ls_codestpro4=$_POST["codestpro4"];
	}
	else
	{
	  $ls_codestpro4="";
	}
	
	if	(array_key_exists("denestpro4",$_POST))
	{
	  $ls_denestpro4=$_POST["denestpro4"];
	}
	else
	{
	  $ls_denestpro4="";
	}
	if	(array_key_exists("codestpro5",$_POST))
	{
	  $ls_codestpro5=$_POST["codestpro5"];
	}
	else
	{
	  $ls_codestpro5="";
	}
	
	if	(array_key_exists("denestpro5",$_POST))
	{
	  $ls_denestpro5=$_POST["denestpro5"];
	}
	else
	{
	  $ls_denestpro5="";
	}			
//Radio Button
if  (array_key_exists("radiobutton",$_POST))
	{
	  $ls_distribucion=$_POST["radiobutton"];
    }
else
	{
	  $ls_distribucion="A";
	}	
if  (array_key_exists("estcla",$_POST))
	{
	  $ls_estcla=$_POST["estcla"];
    }
else
	{
	  $ls_estcla="";
	}	
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if (($ls_permisos)||($ls_logusr=="PSEGIS"))
	{
		print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
		print("<input type=hidden name=leer     id=leer     value='$la_accesos[leer]'>");
		print("<input type=hidden name=incluir  id=incluir  value='$la_accesos[incluir]'>");
		print("<input type=hidden name=cambiar  id=cambiar  value='$la_accesos[cambiar]'>");
		print("<input type=hidden name=eliminar id=eliminar value='$la_accesos[eliminar]'>");
		print("<input type=hidden name=imprimir id=imprimir value='$la_accesos[imprimir]'>");
		print("<input type=hidden name=anular   id=anular   value='$la_accesos[anular]'>");
		print("<input type=hidden name=ejecutar id=ejecutar value='$la_accesos[ejecutar]'>");
	}
	else
	{
		
		print("<script language=JavaScript>");
		print(" location.href='sigespwindow_blank.php'");
		print("</script>");
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?></p>
<table width="798" height="241" border="0" align="center">
  <tr>
    <td height="237"><p>
      <?php
			 $la_empresa =  $_SESSION["la_empresa"];
		     $li_estmodest  = $la_empresa["estmodest"];
			 if($li_estmodest==1)
			 {
				  $li_height="281";
			 }
			 else
			 {
				  $li_height="180";
			 }
		?>
        </p>
        <table width="570" height='288' border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
	  <tr>
        <td height="20" colspan="4" class="titulo-ventana">APERTURA DE CUENTAS</td>
      </tr>
      <tr>
        <td height="13" colspan="4">&nbsp;</td>
      </tr>
      <tr>
		<?php
			 $la_empresa =  $_SESSION["la_empresa"];
		     $li_estmodest  = $la_empresa["estmodest"];
			 $ls_codemp  =  $la_empresa["codemp"];
			 $ls_NomEstPro1 = $la_empresa["nomestpro1"];
			 $ls_NomEstPro2 = $la_empresa["nomestpro2"];
			 $ls_NomEstPro3 = $la_empresa["nomestpro3"];
			 $ls_NomEstPro4 = $la_empresa["nomestpro4"];
			 $ls_NomEstPro5 = $la_empresa["nomestpro5"];
			 
			 $ls_loncodestpro1 = $la_empresa["loncodestpro1"]+10;
			 $ls_loncodestpro2 = $la_empresa["loncodestpro2"]+10;
			 $ls_loncodestpro3 = $la_empresa["loncodestpro3"]+10;
			 $ls_loncodestpro4 = $la_empresa["loncodestpro4"]+10;
			 $ls_loncodestpro5 = $la_empresa["loncodestpro5"]+10;
           
		  ?>
        <td width="142" height="22"><div align="right"><?php print $ls_NomEstPro1;?></div></td>
        <td colspan="3">
          <div align="right"></div>
          <div align="left">
            <input name="codestpro1" type="text" id="codestpro1" style="text-align:center" value="<?php print $ls_codestpro1 ?>" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" readonly>
            <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
            <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" value="<?php print $ls_denestpro1 ?>" size="45">
            <input name="estcla" type="hidden" id="estcla" value="<?php print $ls_estcla;?>">
          </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="20"><div align="right"><?php print $ls_NomEstPro2;?></div></td>
        <td colspan="3"><input name="codestpro2" type="text" id="codestpro22" style="text-align:center" value="<?php print $ls_codestpro2 ?>" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" readonly>
		<a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
            <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" value="<?php print $ls_denestpro2 ?>" size="45"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="20"><div align="right"><?php print $ls_NomEstPro3;?></div></td>
        <td colspan="3"><input name="codestpro3" type="text" id="codestpro33" style="text-align:center"  value="<?php print $ls_codestpro3 ?>" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" readonly>
            <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
            <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" value="<?php print $ls_denestpro3 ?>" size="45">        </td>
      </tr>
        <?php
		 if($li_estmodest==2)
		 {	
	    ?>
      <tr>
        <td height="20"><div align="right"><?php print $ls_NomEstPro4;?></div></td>
        <td colspan="3"><input name="codestpro4" type="text" id="codestpro4" style="text-align:center"  value="<?php print $ls_codestpro4 ?>" size="<?php print $ls_loncodestpro4; ?>" maxlength="<?php print $ls_loncodestpro4; ?>" readonly>
            <a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 4"> </a>
            <input name="denestpro4" type="text" class="sin-borde" id="denestpro4" value="<?php print $ls_denestpro4 ?>" size="45" readonly>        </td>
      </tr>
      <tr>
        <td height="20"><div align="right"><?php print $ls_NomEstPro5;?></div></td>
        <td colspan="3"><input name="codestpro5" type="text" id="codestpro5" style="text-align:center"  value="<?php print $ls_codestpro5 ?>" size="<?php print $ls_loncodestpro5; ?>" maxlength="<?php print $ls_loncodestpro5; ?>" readonly>
            <a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 5"> </a>
            <input name="denestpro5" type="text" class="sin-borde" id="denestpro5" value="<?php print $ls_denestpro5 ?>" size="45" readonly>        </td>
        <?php  
		 }
		 ?>
      </tr>
      <tr>
        <td height="27"><div align="right">Fecha</div></td>
        <td colspan="3"><input name="txtFecha" type="text" class="formato-blanco" id="txtFecha" value="<?php print $ldt_fecha?>" size="10" readonly ></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Distribuci&oacute;n</div></td>
		  <?php 	 
		  if($ls_distribucion=="A")
		  {
				$ls_auto="checked";
				$ls_manual="";
		  }
		  elseif($ls_distribucion=="M")
		  {
				$ls_auto="";
				$ls_manual="checked";
		  }
		  ?>
        <td width="107">
          <input name="radiobutton" type="radio" value="A" checked <?php print $ls_auto ?>>
      Automatico</td>
        <td width="93"><input name="radiobutton" type="radio" value="M" <?php print $ls_manual ?>>
      Manual </td>
        <td width="226"><a href="javascript:ue_distribuir();"></a></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="4"><div align="center"><span class="Estilo2"> </span><span class="Estilo2"><span class="Estilo1">
          <input name="estmodest" type="hidden" id="estmodest" value="<?php print  $li_estmodest; ?>">
          </span>
<?php	
 //Titulos de la tabla
  $title[1]="Cuenta";   $title[2]="Denominaci&oacute;n";  $title[3]="Asignaci&oacute;n";  $title[4]="Enero";  
  $title[5]="Febrero";  $title[6]="Marzo";                $title[7]="Abril";              $title[8]="Mayo";    
  $title[9]="Junio";    $title[10]="Julio";               $title[11]="Agosto";            $title[12]="Septiembre";
  $title[13]="Octubre"; $title[14]="Noviembre";           $title[15]="Diciembre";		  $title[16]="Distribuci&oacute;n Fuente Financiamiento";         
  $ls_nombre="grid_apertura";

if ($ls_operacion == "")
{
   $la_empresa =  $_SESSION["la_empresa"];
   $ls_codemp  =  $la_empresa["codemp"];
   $ls_periodo =  $la_empresa["periodo"];
   $ld_agno_dia=date("Y");
   $ld_agno_periodo=substr($ls_periodo,0,4);
   $li_total=0;
   $object="";
   /*if($ld_agno_dia!=$ld_agno_periodo)
   {
		$io_msg->message("El Año del periodo es distinto al del año del sistema ir a Configuracion/Empresa/Periodo .....");
		print "<script language=JavaScript>";
		print "location.href='../index_modules.php'";
		print "</script>";		
   }*/
   $as_estmodape="";
   $lb_valido=$io_class_aper->uf_spg_select_modalidad_apertura($ls_codemp,$as_estmodape);
   if(($lb_valido)&&($as_estmodape==0))
   { 
	   $io_class_grid->make_gridScroll($li_total,$title,$object,610,'APERTURA',$ls_nombre,50);     
	   $lb_valido=$io_class_aper->uf_spg_procesar_apertura($la_seguridad);
   }
   elseif($as_estmodape==1)
   {
			?>
            <script language="javascript">
			alert("La Apertura ha sido configurada Trimestral......");
			f=document.form1;
			f.action="sigespwindow_blank.php";
			f.submit();
            </script>
            <?php
   }
}

if ($ls_operacion=="CARGAR" )
{
  $la_empresa =  $_SESSION["la_empresa"];
  $ls_codemp  =  $la_empresa["codemp"];
  //$ls_cmbbsf=$_POST["cmbbsf"];    si se muestra en bolivar fuerte 
  if($li_estmodest==2)
  {
	    $ls_codestpro1=$io_function->uf_cerosizquierda($ls_codestpro1,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro2=$io_function->uf_cerosizquierda($ls_codestpro2,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro3=$io_function->uf_cerosizquierda($ls_codestpro3,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro4=$io_function->uf_cerosizquierda($ls_codestpro4,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro5=$io_function->uf_cerosizquierda($ls_codestpro5,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
  }
  else
  {
	    $ls_codestpro1=$io_function->uf_cerosizquierda($ls_codestpro1,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro2=$io_function->uf_cerosizquierda($ls_codestpro2,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro3=$io_function->uf_cerosizquierda($ls_codestpro3,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro4=$io_function->uf_cerosizquierda(0,25);
	    $ls_codestpro5=$io_function->uf_cerosizquierda(0,25);
  }
  $ls_estcla=$_POST["estcla"];   
  $rs_load=$io_class_aper->uf_spg_load_cuentas_apertura($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla);
  $li_totnum=$rs_load->RecordCount();
  if($li_totnum>0)
  {
 /*  $data=$io_sql->obtener_datos($rs_load);
   $ds_aper->data=$data;
   $li_num=$ds_aper->getRowCount("spg_cuenta");
   $li_totnum=$li_num;
   for($i=1;$i<=$li_num;$i++)*/
   $i=1;
   while(!$rs_load->EOF)
   {    
        $ls_cuenta=$rs_load->fields["spg_cuenta"]; 
		$ls_denominacion=$rs_load->fields["denominacion"];
		$ls_distribuir=$rs_load->fields["distribuir"];
		$ld_asignado=number_format($rs_load->fields["asignado"],2,",",".");
		$ld_enero=number_format($rs_load->fields["enero"],2,",",".");
		$ld_febrero=number_format($rs_load->fields["febrero"],2,",",".");
		$ld_marzo=number_format($rs_load->fields["marzo"],2,",",".");
		$ld_abril=number_format($rs_load->fields["abril"],2,",",".");
		$ld_mayo=number_format($rs_load->fields["mayo"],2,",",".");
		$ld_junio=number_format($rs_load->fields["junio"],2,",",".");
		$ld_julio=number_format($rs_load->fields["julio"],2,",",".");
		$ld_agosto=number_format($rs_load->fields["agosto"],2,",",".");
		$ld_septiembre=number_format($rs_load->fields["septiembre"],2,",",".");
		$ld_octubre=number_format($rs_load->fields["octubre"],2,",",".");
		$ld_noviembre=number_format($rs_load->fields["noviembre"],2,",",".");
		$ld_diciembre=number_format($rs_load->fields["diciembre"],2,",",".");
				
		$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde readonly><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'>";
		$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=sin-borde readonly >";
		$object[$i][3]="<input type=text name=txtAsignacion".$i." class=sin-borde onBlur='uf_formato(this)' onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)' value=$ld_asignado  onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][4]="<input type=text name=txtEnero".$i."  onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)' value=$ld_enero class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][5]="<input type=text name=txtFebrero".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)' value=$ld_febrero class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][6]="<input type=text name=txtMarzo".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)' value=$ld_marzo class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][7]="<input type=text name=txtAbril".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' onKeyUp='ue_validarcomas_puntos(this)' value=$ld_abril class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][8]="<input type=text name=txtMayo".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' onKeyUp='ue_validarcomas_puntos(this)' value=$ld_mayo class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][9]="<input type=text name=txtJunio".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' onKeyUp='ue_validarcomas_puntos(this)' value=$ld_junio class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][10]="<input type=text name=txtJulio".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' onKeyUp='ue_validarcomas_puntos(this)' value=$ld_julio class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][11]="<input type=text name=txtAgosto".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' onKeyUp='ue_validarcomas_puntos(this)' value=$ld_agosto class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][12]="<input type=text name=txtSeptiembre".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' onKeyUp='ue_validarcomas_puntos(this)' value=$ld_septiembre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][13]="<input type=text name=txtOctubre".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' onKeyUp='ue_validarcomas_puntos(this)' value=$ld_octubre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][14]="<input type=text name=txtNoviembre".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=$ld_noviembre  class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][15]="<input type=text name=txtDiciembre".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)' value=$ld_diciembre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		//$object[$i][16]="<div align='center'><a href=javascript:uf_asignardisfuefin('".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_estcla."','".trim($ls_cuenta)."','".trim($ls_denominacion)."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>";
		$object[$i][16]="<div align='center'><a href=javascript:uf_asignardisfuefin('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla','".trim($ls_cuenta)."',$i);><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>";
		$i++;
		$rs_load->MoveNext();
   }//for  
    $io_class_grid->make_gridScroll($li_totnum,$title,$object,610,'APERTURA',$ls_nombre,245);     
  }//if
  else
  {
     $li_total=0;
     $object="";
	 $io_class_grid->make_gridScroll($li_total,$title,$object,610,'APERTURA',$ls_nombre,50);  
	 ?>
        <script language="javascript">
	    alert("La Estructura Programatica seleccionada no tiene cuentas asociadas... ");
		f=document.form1;
		f.action="sigesp_spg_p_apertura.php";
		f.codestpro1.value="";
		f.codestpro2.value="";
		f.codestpro3.value="";
		f.denestpro1.value="";
		f.denestpro2.value="";
		f.denestpro3.value="";
	    estmodest=f.estmodest.value;
		if(estmodest==2)
	    {
			f.codestpro4.value="";
			f.codestpro5.value="";
			f.denestpro4.value="";
			f.denestpro5.value="";	 
	    }
	  </script>
	<?php
  }//else
 }//cargar

if ($ls_operacion=="GUARDAR")
{
   $la_empresa =  $_SESSION["la_empresa"];
   $ls_codemp  =  $la_empresa["codemp"];
   $li_num=$_POST["li_totnum"];
   $lb_valido=true;
   for($i=1;$i<=$li_num;$i++)
   { 
        $ls_cuenta=$_POST["txtCuenta".$i];   
	    $ls_denominacion=$_POST["txtDenominacion".$i];
	    $ld_asignado=trim($_POST["txtAsignacion".$i]);
		$ls_distribuir=$_POST["distribuir".$i];
		$ld_enero=$_POST["txtEnero".$i];
	    $ld_febrero=$_POST["txtFebrero".$i];
	    $ld_marzo=$_POST["txtMarzo".$i];
	    $ld_abril=$_POST["txtAbril".$i];
	    $ld_mayo=$_POST["txtMayo".$i];
	    $ld_junio=$_POST["txtJunio".$i];
	    $ld_julio=$_POST["txtJulio".$i];
	    $ld_agosto=$_POST["txtAgosto".$i];
	    $ld_septiembre=$_POST["txtSeptiembre".$i];
	    $ld_octubre=$_POST["txtOctubre".$i];
	    $ld_noviembre=$_POST["txtNoviembre".$i];
	    $ld_diciembre=$_POST["txtDiciembre".$i];
        $ls_estcla=$_POST["estcla"];   
        
	    if($li_estmodest==2)
	    {
			$ls_codestpro1=$io_function->uf_cerosizquierda($ls_codestpro1,25);
			$ls_codestpro2=$io_function->uf_cerosizquierda($ls_codestpro2,25);
			$ls_codestpro3=$io_function->uf_cerosizquierda($ls_codestpro3,25);
			$ls_codestpro4=$io_function->uf_cerosizquierda($ls_codestpro4,25);
			$ls_codestpro5=$io_function->uf_cerosizquierda($ls_codestpro5,25);
			$estprog[0]  = $ls_codestpro1; 
			$estprog[1]  = $ls_codestpro2; 
			$estprog[2]  = $ls_codestpro3;
			$estprog[3]  = $ls_codestpro4;
			$estprog[4]  = $ls_codestpro5;
			$estprog[5]  = $ls_estcla;
	    }
	    else
	    {
			
			$estprog[0]  = $io_function->uf_cerosizquierda($ls_codestpro1,25); 
			$estprog[1]  = $io_function->uf_cerosizquierda($ls_codestpro2,25); 
			$estprog[2]  = $io_function->uf_cerosizquierda($ls_codestpro3,25);
			$estprog[3]  = $io_function->uf_cerosizquierda(0,25);
			$estprog[4]  = $io_function->uf_cerosizquierda(0,25);
			$estprog[5]  = $ls_estcla;
	    }
      
		$la_empresa =  $_SESSION["la_empresa"];
        $io_class_aper->is_codemp      =  $la_empresa["codemp"];
		$io_class_aper->is_procedencia = "SPGAPR";		
		$io_class_aper->is_comprobante = "0000000APERTURA";
		$io_class_aper->ii_tipo_comp   = 2;
		$io_class_aper->is_ced_ben     = "----------";
		$io_class_aper->is_cod_prov    = "----------";
		$io_class_aper->is_tipo        = "-";
		$io_class_aper->is_descripcion = "APERTURA DE CUENTAS";
		$io_class_aper->id_fecha       = $la_empresa["periodo"];
		$io_class_aper->as_codban      = "---";
		$io_class_aper->as_ctaban      = "-------------------------";
		
		$ld_asignado=str_replace('.','',$ld_asignado);
		$ld_asignado=str_replace(',','.',$ld_asignado);		
		$ld_enero=str_replace('.','',$ld_enero);
		$ld_enero=str_replace(',','.',$ld_enero);
		$ld_febrero=str_replace('.','',$ld_febrero);
		$ld_febrero=str_replace(',','.',$ld_febrero);
		$ld_marzo=str_replace('.','',$ld_marzo);
		$ld_marzo=str_replace(',','.',$ld_marzo);
		$ld_abril=str_replace('.','',$ld_abril);
		$ld_abril=str_replace(',','.',$ld_abril);
		$ld_mayo=str_replace('.','',$ld_mayo);
		$ld_mayo=str_replace(',','.',$ld_mayo);
		$ld_junio=str_replace('.','',$ld_junio);
		$ld_junio=str_replace(',','.',$ld_junio);
		$ld_julio=str_replace('.','',$ld_julio);
		$ld_julio=str_replace(',','.',$ld_julio);
		$ld_agosto=str_replace('.','',$ld_agosto);
		$ld_agosto=str_replace(',','.',$ld_agosto);
		$ld_septiembre=str_replace('.','',$ld_septiembre);
		$ld_septiembre=str_replace(',','.',$ld_septiembre);
		$ld_octubre=str_replace('.','',$ld_octubre);
		$ld_octubre=str_replace(',','.',$ld_octubre);
		$ld_noviembre=str_replace('.','',$ld_noviembre);
		$ld_noviembre=str_replace(',','.',$ld_noviembre);
		$ld_diciembre=str_replace('.','',$ld_diciembre);
		$ld_diciembre=str_replace(',','.',$ld_diciembre);		
			
		$lr_datos["spg_cuenta"][$i]=$ls_cuenta;
		$lr_datos["denominacion"][$i]=$ls_denominacion;
		$lr_datos["asignado"][$i]=$ld_asignado;
		$lr_datos["distribuir"][$i]=$ls_distribuir;
		$lr_datos["enero"][$i]=$ld_enero;
		$lr_datos["febrero"][$i]=$ld_febrero;
		$lr_datos["marzo"][$i]=$ld_marzo;
		$lr_datos["abril"][$i]=$ld_abril;
		$lr_datos["mayo"][$i]=$ld_mayo;
		$lr_datos["junio"][$i]=$ld_junio;
		$lr_datos["julio"][$i]=$ld_julio;
		$lr_datos["agosto"][$i]=$ld_agosto;
		$lr_datos["septiembre"][$i]=$ld_septiembre;
		$lr_datos["octubre"][$i]=$ld_octubre;
		$lr_datos["noviembre"][$i]=$ld_noviembre;
		$lr_datos["diciembre"][$i]=$ld_diciembre;
		
		$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde readonly ><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'>";
		$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=sin-borde readonly>";
		$object[$i][3]="<input type=text name=txtAsignacion".$i." class=sin-borde onBlur='uf_formato(this)' value=".number_format($ld_asignado,2,",",".")." onKeyPress='return keyRestrictgrid(event)' onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][4]="<input type=text name=txtEnero".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=".number_format($ld_enero,2,",",".")." class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][5]="<input type=text name=txtFebrero".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=".number_format($ld_febrero,2,",",".")." class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][6]="<input type=text name=txtMarzo".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=".number_format($ld_marzo,2,",",".")." class=sin-borde  onFocus= uf_fila(".$i.") style=text-align:right>";
	    $object[$i][7]="<input type=text name=txtAbril".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=".number_format($ld_abril,2,",",".")." class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][8]="<input type=text name=txtMayo".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=".number_format($ld_mayo,2,",",".")." class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][9]="<input type=text name=txtJunio".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=".number_format($ld_junio,2,",",".")." class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][10]="<input type=text name=txtJulio".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=".number_format($ld_julio,2,",",".")." class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][11]="<input type=text name=txtAgosto".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=".number_format($ld_agosto,2,",",".")." class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][12]="<input type=text name=txtSeptiembre".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=".number_format($ld_septiembre,2,",",".")." class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][13]="<input type=text name=txtOctubre".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=".number_format($ld_octubre,2,",",".")." class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][14]="<input type=text name=txtNoviembre".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=".number_format($ld_noviembre,2,",",".")."  class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][15]="<input type=text name=txtDiciembre".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=".number_format($ld_diciembre,2,",",".")." class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][16]="<div align='center'><a href=javascript:uf_asignardisfuefin('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla','".trim($ls_cuenta)."',$i);><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>";
	 }//for
	 if($lb_valido)
	 {
		  $lb_valido=$io_class_aper->procesar_guardar_apertura($lr_datos,$estprog,$la_seguridad,$li_num);
	 } 
	 if ($lb_valido)
	 {
	   $io_msg->message("La Apertura fue registrada con  exito.....");
	 }
	 else
	 {
	    $io_msg->message("La Apertura no fue registrada.....");
	 }
	 $io_class_grid->make_gridScroll($li_num,$title,$object,610,'APERTURA',$ls_nombre,245);
}//GUARDAR	  
?>
        <input name="operacion" type="hidden" id="operacion" value="<?php $_POST["operacion"] ?>">
        <input name="li_totnum" type="hidden" id="li_totnum" value="<?php print $li_totnum; ?>">
        <input name="fila" type="hidden" id="fila">
        </span><span class="Estilo2">
        <input name="fila2" type="hidden" id="fila2" value="<?php print $li_fila; ?>">
        </span></div></td>
        </tr>
    </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
<p>&nbsp;</p>
<p align="center">&nbsp;</p>
  <p align="center">&nbsp;</p>
  <p align="center">&nbsp;  </p>
  <p>&nbsp;</p>
</form>
</body>
<script language="javascript">

function uf_cambio_estpro1()
{
	f=document.form1;
	f.action="sigesp_spg_p_apertura.php";
	f.submit();
}

function uf_cambio_estpro2()
{
	f=document.form1;
	f.action="sigesp_spg_p_apertura.php";
	f.submit();
}


function uf_cargargrid()
{
	f=document.form1;
	f.operacion.value="CARGAR";
	f.action="sigesp_spg_p_apertura.php";
	f.submit();
}

function ue_distribuir()
{
   var i ;
   f=document.form1;
   if((f.codestpro1.value=="")||(f.codestpro2.value=="")||(f.codestpro3.value==""))
   {
     alert(" Por Favor Seleccione una Estructura Programatica....");
   }
   else
   {
		for (i=0;i<f.radiobutton.length;i++)
		{ 
		   if (f.radiobutton[i].checked) 
			  break; 
		} 
		document.opcion = f.radiobutton[i].value; 
		 
		if  (document.opcion=="M" ) 
		{
			 f=document.form1;
		     li=f.fila.value;
			 ls_distribuir=3;
			 distribuir="distribuir"+li;
			 eval("f."+distribuir+".value='"+ls_distribuir+"'") ;
			 f.action="sigesp_spg_p_apertura.php";
	   }
		 
	   if (document.opcion=="A")
	   {
		   f=document.form1;
		   li=f.fila.value;
		   ls_distribuir=1;
		   li_total=f.li_totnum.value;
		   if(li!="")
		   {
			 for (li=1;i<li_total;li++)
			 {
			   txtasig="txtAsignacion"+li;
			   ld_asignado=eval("f."+txtasig+".value");
			   ld_asignado=uf_convertir_monto(ld_asignado);
			   ld_division=parseFloat((ld_asignado/12));
			   /*ld_division=redondear(ld_division,2);
			   ld_suma_diciembre=redondear((ld_division*12),2);
			   ld_mes12=redondear((ld_suma_diciembre-ld_asignado),2);*/
			   
			   ld_division=redondear2(ld_division); 		   
		       ld_suma_diciembre=redondear2(ld_division*12);
		       ld_mes12=redondear2((ld_suma_diciembre-ld_asignado));
			   
			   if(ld_mes12>=0)
			   {
			    ld_diciembre=ld_division-ld_mes12;
			   } 			
               if(ld_mes12<0)
			   {
			    ld_diciembre=ld_division+ld_mes12;
			   } 	
			   ld_total=(ld_division*11)+ld_diciembre;
			   //ld_resto=redondear((ld_asignado-ld_total),2);
			   ld_resto=redondear2(ld_asignado-ld_total);
			   
               ld_diciembre=ld_diciembre+ld_resto;
			   ld_division=uf_convertir(ld_division);
			   ld_diciembre=uf_convertir(ld_diciembre);
                			
			   distribuir="distribuir"+li;
			   eval("f."+distribuir+".value='"+ls_distribuir+"'") ;
			   txtm1="txtEnero"+li;
			   eval("f."+txtm1+".value='"+ld_division+"'") ;
			   txtm2="txtFebrero"+li;
			   eval("f."+txtm2+".value='"+ld_division+"'") ;
			   txtm3="txtMarzo"+li;
			   eval("f."+txtm3+".value='"+ld_division+"'") ;
			   txtm4="txtAbril"+li;
			   eval("f."+txtm4+".value='"+ld_division+"'") ;
			   txtm5="txtMayo"+li;
			   eval("f."+txtm5+".value='"+ld_division+"'") ;
			   txtm6="txtJunio"+li;
			   eval("f."+txtm6+".value='"+ld_division+"'") ;
			   txtm7="txtJulio"+li;
			   eval("f."+txtm7+".value='"+ld_division+"'") ;
			   txtm8="txtAgosto"+li;
			   eval("f."+txtm8+".value='"+ld_division+"'") ;
			   txtm9="txtSeptiembre"+li;
			   eval("f."+txtm9+".value='"+ld_division+"'") ;
			   txtm10="txtOctubre"+li;
			   eval("f."+txtm10+".value='"+ld_division+"'") ;
			   txtm11="txtNoviembre"+li;
			   eval("f."+txtm11+".value='"+ld_division+"'") ;
			   txtm12="txtDiciembre"+li;
			   eval("f."+txtm12+".value='"+ld_diciembre+"'") ;
			  }  
			}
			else
			{
			 alert("Por favor coloque el cursor sobre la fila  a editar  ");
		    }	 
		    f.action="sigesp_spg_p_apertura.php";
	   }
   }
}

function redondear(num, dec)
{ 
    num = parseFloat(num); 
    dec = parseFloat(dec); 
    dec = (!dec ? 2 : dec); 
    return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec); 
}
    
function uf_calcular()
{  
	f=document.form1;
	
    var ld_asignado;
	li=f.fila.value; 	 
	f=document.form1;

	txta="txtAsignacion"+li;
	ld_asignado=eval("f."+txta+".value");  
	ld_asignado=parseFloat(uf_convertir_monto(ld_asignado));
	txtm1="txtEnero"+li;
	ld_m1=eval("f."+txtm1+".value"); 
	ld_m1=parseFloat(uf_convertir_monto(ld_m1));

	txtm2="txtFebrero"+li;
	ld_m2=eval("f."+txtm2+".value");    
	ld_m2=parseFloat(uf_convertir_monto(ld_m2));
	
	txtm3="txtMarzo"+li;
	ld_m3=eval("f."+txtm3+".value");    
	ld_m3=parseFloat(uf_convertir_monto(ld_m3));
	
	txtm4="txtAbril"+li;
	ld_m4=eval("f."+txtm4+".value"); 
	ld_m4=parseFloat(uf_convertir_monto(ld_m4));

	txtm5="txtMayo"+li;
	ld_m5=eval("f."+txtm5+".value");
	ld_m5=parseFloat(uf_convertir_monto(ld_m5));

	txtm6="txtJunio"+li;
	ld_m6=eval("f."+txtm6+".value");
	ld_m6=parseFloat(uf_convertir_monto(ld_m6));

	txtm7="txtJulio"+li;
	ld_m7=eval("f."+txtm7+".value");       
	ld_m7=parseFloat(uf_convertir_monto(ld_m7));

	txtm8="txtAgosto"+li;
	ld_m8=eval("f."+txtm8+".value");       
	ld_m8=parseFloat(uf_convertir_monto(ld_m8));

	txtm9="txtSeptiembre"+li;
	ld_m9=eval("f."+txtm9+".value");       
	ld_m9=parseFloat(uf_convertir_monto(ld_m9));

	txtm10="txtOctubre"+li;
	ld_m10=eval("f."+txtm10+".value");       
	ld_m10=parseFloat(uf_convertir_monto(ld_m10));

	txtm11="txtNoviembre"+li;
	ld_m11=eval("f."+txtm11+".value");       
	ld_m11=parseFloat(uf_convertir_monto(ld_m11));

	txtm12="txtDiciembre"+li;
	ld_m12=eval("f."+txtm12+".value");       
	ld_m12=parseFloat(uf_convertir_monto(ld_m12));

	ld_total = parseFloat(ld_m1 + ld_m2 + ld_m3 + ld_m4 + ld_m5 + ld_m6 +ld_m7 + ld_m8 + ld_m9 + ld_m10 + ld_m11 + ld_m12);
	ld_total=redondear(ld_total,2);
	if (ld_total>ld_asignado)
	{
	  alert(" El Total es mayor al monto asignado. Por favor revise los montos ");
	}	
	f.action="sigesp_spg_p_apertura.php";
  }
  
  function uf_formato(obj)
  {
	ldec_temp1=obj.value;
	if((ldec_temp1=="")||(ldec_temp1==".")||(ldec_temp1==","))
	{
	  ldec_temp1="0";
	}
	else
	{
	for (i=0;i<f.radiobutton.length;i++)
	{ 
	   if (f.radiobutton[i].checked) 
		  break; 
	} 
	document.opcion = f.radiobutton[i].value; 
	if(document.opcion=="A")
	{	
		 obj.value=uf_convertir(ldec_temp1);
		 f=document.form1;
		 li=f.fila.value;
		 ls_distribuir=1;
		 li_total=f.li_totnum.value;
		 if(li!="")
		 {
		   txtasig="txtAsignacion"+li;
		   ld_asignado=eval("f."+txtasig+".value");
		   ld_asignado=uf_convertir_monto(ld_asignado);
		   ld_division=parseFloat((ld_asignado/12)); 		   
		   ld_division=redondear2(ld_division); 		   
		   ld_suma_diciembre=redondear2(ld_division*12);
		   ld_mes12=redondear2((ld_suma_diciembre-ld_asignado));		  
		   if(ld_mes12>=0)
		   {
			 ld_diciembre=ld_division-ld_mes12;
		   } 			
		   if(ld_mes12<0)
		   {
			 ld_diciembre=ld_division+ld_mes12;
		   } 	
		   ld_total=(ld_division*11)+ld_diciembre;
		  // alert("total---->"+ld_total);
		   ld_resto=redondear((ld_asignado-ld_total),2);
		   //alert("resto---->"+ld_resto);
		   ld_diciembre=(ld_diciembre+ld_resto);
		   //alert("diciembre---->"+ld_diciembre);
		   ld_division=uf_convertir(ld_division);
		   ld_diciembre=uf_convertir(ld_diciembre);
		   
		   //alert("diciembre---->"+ld_diciembre);
		   
		   distribuir="distribuir"+li;
		   eval("f."+distribuir+".value='"+ls_distribuir+"'") ;
		   txtm1="txtEnero"+li;
		   eval("f."+txtm1+".value='"+ld_division+"'") ;
		   txtm2="txtFebrero"+li;
		   eval("f."+txtm2+".value='"+ld_division+"'") ;
		   txtm3="txtMarzo"+li;
		   eval("f."+txtm3+".value='"+ld_division+"'") ;
		   txtm4="txtAbril"+li;
		   eval("f."+txtm4+".value='"+ld_division+"'") ;
		   txtm5="txtMayo"+li;
		   eval("f."+txtm5+".value='"+ld_division+"'") ;
		   txtm6="txtJunio"+li;
		   eval("f."+txtm6+".value='"+ld_division+"'") ;
		   txtm7="txtJulio"+li;
		   eval("f."+txtm7+".value='"+ld_division+"'") ;
		   txtm8="txtAgosto"+li;
		   eval("f."+txtm8+".value='"+ld_division+"'") ;
		   txtm9="txtSeptiembre"+li;
		   eval("f."+txtm9+".value='"+ld_division+"'") ;
		   txtm10="txtOctubre"+li;
		   eval("f."+txtm10+".value='"+ld_division+"'") ;
		   txtm11="txtNoviembre"+li;
		   eval("f."+txtm11+".value='"+ld_division+"'") ;
		   txtm12="txtDiciembre"+li;
		   eval("f."+txtm12+".value='"+ld_diciembre+"'") ;
		 }
		 else
		 {
		  alert("Por favor coloque el cursor sobre la fila  a editar  ");
		 }	
	 }
	 if(document.opcion=="M" ) 
	 {
	   obj.value=uf_convertir(ldec_temp1);
	   f=document.form1;
	   li=f.fila.value;
	   ls_distribuir=3;
	   ld_division="0,00";
	   distribuir="distribuir"+li;
	   eval("f."+distribuir+".value='"+ls_distribuir+"'") ;
	   
	   txtm1="txtEnero"+li;
	   eval("f."+txtm1+".value='"+ld_division+"'") ;
	   txtm2="txtFebrero"+li;
	   eval("f."+txtm2+".value='"+ld_division+"'") ;
	   txtm3="txtMarzo"+li;
	   eval("f."+txtm3+".value='"+ld_division+"'") ;
	   txtm4="txtAbril"+li;
	   eval("f."+txtm4+".value='"+ld_division+"'") ;
	   txtm5="txtMayo"+li;
	   eval("f."+txtm5+".value='"+ld_division+"'") ;
	   txtm6="txtJunio"+li;
	   eval("f."+txtm6+".value='"+ld_division+"'") ;
	   txtm7="txtJulio"+li;
	   eval("f."+txtm7+".value='"+ld_division+"'") ;
	   txtm8="txtAgosto"+li;
	   eval("f."+txtm8+".value='"+ld_division+"'") ;
	   txtm9="txtSeptiembre"+li;
	   eval("f."+txtm9+".value='"+ld_division+"'") ;
	   txtm10="txtOctubre"+li;
	   eval("f."+txtm10+".value='"+ld_division+"'") ;
	   txtm11="txtNoviembre"+li;
	   eval("f."+txtm11+".value='"+ld_division+"'") ;
	   txtm12="txtDiciembre"+li;
	   eval("f."+txtm12+".value='"+ld_division+"'") ;
	   f.action="sigesp_spg_p_apertura.php";
    }
  }
} 
  
  function uf_format(obj)
  {
	f=document.form1;
	ldec_temp1=obj.value;
	if((ldec_temp1=="")||(ldec_temp1==".")||(ldec_temp1==","))
	{
	  obj.value="0,00";
	  obj.focus();
	}
	
	if(ldec_temp1.indexOf('.')<0)
	{
	   ldec_temp1=ldec_temp1+".00"
	}
	if(ldec_temp1.indexOf(',')<0)
    {
	  ldec_temp1=ldec_temp1.replace(",",".");
    }
	
	if((ldec_temp1.indexOf('.')>0)||(ldec_temp1.indexOf(',')>0))
	{
		obj.value=uf_convertir(ldec_temp1);
		
		var ld_asignado;
		li=f.fila.value;
		lz=parseInt(li)+1;
		txta="txtAsignacion"+li;
		ld_asignado=eval("f."+txta+".value");  
		ld_asignado=parseFloat(uf_convertir_monto(ld_asignado));
		
		txtm1="txtEnero"+li;
		ld_m1=eval("f."+txtm1+".value"); 
		ld_m1=parseFloat(uf_convertir_monto(ld_m1));
		
		txtm2="txtFebrero"+li;
		ld_m2=eval("f."+txtm2+".value");    
		ld_m2=parseFloat(uf_convertir_monto(ld_m2));
		
		txtm3="txtMarzo"+li;
		ld_m3=eval("f."+txtm3+".value");    
		ld_m3=parseFloat(uf_convertir_monto(ld_m3));
		
		txtm4="txtAbril"+li;
		ld_m4=eval("f."+txtm4+".value"); 
		ld_m4=parseFloat(uf_convertir_monto(ld_m4));
	
		txtm5="txtMayo"+li;
		ld_m5=eval("f."+txtm5+".value");
		ld_m5=parseFloat(uf_convertir_monto(ld_m5));
	
		txtm6="txtJunio"+li;
		ld_m6=eval("f."+txtm6+".value");
		ld_m6=parseFloat(uf_convertir_monto(ld_m6));
	
		txtm7="txtJulio"+li;
		ld_m7=eval("f."+txtm7+".value");       
		ld_m7=parseFloat(uf_convertir_monto(ld_m7));
	
		txtm8="txtAgosto"+li;
		ld_m8=eval("f."+txtm8+".value");       
		ld_m8=parseFloat(uf_convertir_monto(ld_m8));
	
		txtm9="txtSeptiembre"+li;
		ld_m9=eval("f."+txtm9+".value");       
		ld_m9=parseFloat(uf_convertir_monto(ld_m9));
	
		txtm10="txtOctubre"+li;
		ld_m10=eval("f."+txtm10+".value");       
		ld_m10=parseFloat(uf_convertir_monto(ld_m10));
	
		txtm11="txtNoviembre"+li;
		ld_m11=eval("f."+txtm11+".value");       
		ld_m11=parseFloat(uf_convertir_monto(ld_m11));
	
		txtm12="txtDiciembre"+li;
		ld_m12=eval("f."+txtm12+".value");       
		ld_m12=parseFloat(uf_convertir_monto(ld_m12));
	
		//ld_total = parseFloat(ld_m1 + ld_m2 + ld_m3 + ld_m4 + ld_m5 + ld_m6 +ld_m7 + ld_m8 + ld_m9 + ld_m10 + ld_m11 + ld_m12);
		//ld_total=redondear(ld_total,2);
		//if ((ld_total>ld_asignado)||(ld_total<ld_asignado))
		//{
		  //alert("La Distribución no cuadra con lo asignado. Por favor revise los montos ");
		//}	
	 }	
  }

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	if((li_cambiar==1)||(li_incluir==1))
	{
		if(f.li_totnum.value==0)
		{
		  alert(" Debe tener al menos un registro cargado  ");
		}
		else
		{
			var ld_asignado;
			li=f.fila.value; 	 
			ls_cuenta="txtCuenta"+li;
			ls_cuenta=eval("f."+ls_cuenta+".value");  
			
			txta="txtAsignacion"+li;
			ld_asignado=eval("f."+txta+".value");  
			ld_asignado=parseFloat(uf_convertir_monto(ld_asignado));
			
			txtm1="txtEnero"+li;
			ld_m1=eval("f."+txtm1+".value"); 
			ld_m1=parseFloat(uf_convertir_monto(ld_m1));
			
			txtm2="txtFebrero"+li;
			ld_m2=eval("f."+txtm2+".value");    
			ld_m2=parseFloat(uf_convertir_monto(ld_m2));
			
			txtm3="txtMarzo"+li;
			ld_m3=eval("f."+txtm3+".value");    
			ld_m3=parseFloat(uf_convertir_monto(ld_m3));
			
			txtm4="txtAbril"+li;
			ld_m4=eval("f."+txtm4+".value"); 
			ld_m4=parseFloat(uf_convertir_monto(ld_m4));
		
			txtm5="txtMayo"+li;
			ld_m5=eval("f."+txtm5+".value");
			ld_m5=parseFloat(uf_convertir_monto(ld_m5));
		
			txtm6="txtJunio"+li;
			ld_m6=eval("f."+txtm6+".value");
			ld_m6=parseFloat(uf_convertir_monto(ld_m6));
		
			txtm7="txtJulio"+li;
			ld_m7=eval("f."+txtm7+".value");       
			ld_m7=parseFloat(uf_convertir_monto(ld_m7));
		
			txtm8="txtAgosto"+li;
			ld_m8=eval("f."+txtm8+".value");       
			ld_m8=parseFloat(uf_convertir_monto(ld_m8));
		
			txtm9="txtSeptiembre"+li;
			ld_m9=eval("f."+txtm9+".value");       
			ld_m9=parseFloat(uf_convertir_monto(ld_m9));
		
			txtm10="txtOctubre"+li;
			ld_m10=eval("f."+txtm10+".value");       
			ld_m10=parseFloat(uf_convertir_monto(ld_m10));
		
			txtm11="txtNoviembre"+li;
			ld_m11=eval("f."+txtm11+".value");       
			ld_m11=parseFloat(uf_convertir_monto(ld_m11));
		
			txtm12="txtDiciembre"+li;
			ld_m12=eval("f."+txtm12+".value");       
			ld_m12=parseFloat(uf_convertir_monto(ld_m12));
		
			ld_total = parseFloat(ld_m1 + ld_m2 + ld_m3 + ld_m4 + ld_m5 + ld_m6 +ld_m7 + ld_m8 + ld_m9 + ld_m10 + ld_m11 + ld_m12);
			ld_total=redondear(ld_total,2);
			ld_resta=(ld_asignado-ld_total);
			ld_resta=Math.abs(ld_resta);
			ld_resta=uf_convertir(ld_resta);
			if (ld_total!=ld_asignado)
			{
			  alert("La Distribución no cuadra con lo asignado. Por favor revise la cuenta  "+ls_cuenta+" Con una diferencia de  "+ld_resta +" Bs.");
			}	
			else
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_spg_p_apertura.php";
				f.submit();
			}	
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function uf_validacaracter(cadena, obj)
{ 
   opc = false; 
   if (cadena == "%d")//toma solo caracteres  
   if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
   opc = true; 

   if (cadena == "%e")//toma el @, el punto y caracteres. Para Email
   if ((event.keyCode > 63 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode ==46)||(event.keyCode ==95)||(event.keyCode > 47 && event.keyCode < 58))  
   opc = true;    

   if (cadena == "%f")//Toma solo numeros
   { 
     if (event.keyCode > 47 && event.keyCode < 58) 
     opc = true; 
     if (obj.value.search("[.*]") == -1 && obj.value.length != 0) 
     if (event.keyCode == 46) 
     opc = true; 
   } 
   
   if (cadena == "%s") // toma numero y letras
   if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)||(event.keyCode ==47)||(event.keyCode ==35)||(event.keyCode ==45)) 
   opc = true; 
   
   if (cadena == "%c") // toma numero, punto y guion. Para telefonos
   if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode > 44 && event.keyCode < 47))
   opc = true; 
   
   if(opc == false) 
   event.returnValue = false;
}
/*Fin de la Función uf_validacaracter()*/
function uf_fila(i)
{
  f=document.form1;
  f.fila.value=i;
}

function catalogo_estpro1()
{
	   pagina="sigesp_cat_public_estpro1.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
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
	estcla=f.estcla.value;
	if(estmodest==1)
	{
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3=="")&&(denestpro3==""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&tipo=apertura"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="sigesp_cat_public_estpro.php?tipo=apertura"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&tipo=apertura"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
		   alert("Seleccione la Estructura nivel 2");
		}
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
				+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&tipo=apertura"
				+"&estcla="+estcla;
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
					+"&denestpro4="+denestpro4+"&tipo=apertura"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
			pagina="sigesp_cat_public_estprograma.php?tipo=apertura"+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
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


function redondear2(numero)
{
	numero2='';
	numero=parseFloat(numero);
	numero=Math.ceil(numero*10)/10
	AuxString = numero.toString();
	if(AuxString.indexOf('.')>=0)
	{
		AuxArr=AuxString.split('.');
		if(AuxArr[1]>=5)
		{
			numero=Math.ceil(numero);
		}
		else
		{
			numero=Math.floor(numero);
		}
	}
    return numero;
}

function uf_asignardisfuefin(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,spg_cuenta,i)
{
	Xpos=((screen.width/2)-(500/2)); 
	Ypos=((screen.height/2)-(400/2));
	f=document.form1;
	ls_dencuenta="txtDenominacion"+i;
	ls_dencuenta=eval("f."+ls_dencuenta+".value");
	ld_asignacion="txtAsignacion"+i;
	ld_asignacion=eval("f."+ld_asignacion+".value");
	if(parseFloat(uf_convertir_monto(ld_asignacion))>0)
	{    
	 window.open("sigesp_w_regdt_distfuefin.php?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&codestpro5="+codestpro5+"&estcla="+estcla+"&spg_cuenta="+spg_cuenta+"&denominacion="+ls_dencuenta+"&asignacion="+ld_asignacion+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=500,height=400,left="+Xpos+",top="+Ypos+",location=no,resizable=no");
	}
	else
	{
	 alert("La Cuenta debe tener una Asignacion mayor a cero para poder realizar la Distribucion por Fuente de Financiamiento, Verifique por Favor!!!");
	}
}
</script>
</html>