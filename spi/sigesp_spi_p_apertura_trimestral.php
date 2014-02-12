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
	$ls_sistema="SPI";
	$ls_ventanas="sigesp_spi_p_apertura_trimestral.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;
	
	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$ls_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas);
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
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="799" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="798" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo3">Sistema de Presupuesto de Ingreso</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
    </td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><img src="../shared/imagebank/tools20/espacio.gif" width="4" height="20"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<form name="form1" method="post" action="">
<p>
  <?php
require_once("../shared/class_folder/sigesp_include.php");
$in = new sigesp_include();
$con= $in-> uf_conectar ();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
require_once("../shared/class_folder/class_mensajes.php");
$msg=new class_mensajes();
require_once("../shared/class_folder/class_funciones.php");
$fun=new class_funciones();
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");
require_once("../shared/class_folder/class_sigesp_int_spi.php");
require_once("../shared/class_folder/grid_param.php");
require_once("sigesp_spi_class_apertura.php");
$class_aper=new sigesp_spi_class_apertura();
$ds_aper=new class_datastore();
$class_grid=new grid_param();
$la_empresa =  $_SESSION["la_empresa"];
$li_estpreing = $la_empresa["estpreing"];

if(array_key_exists("operacion",$_POST))
{
    $ls_operacion=$_POST["operacion"];
    if($li_estpreing=='1')
	 {
		$ls_mostrar_estruc = 'style="display:compact"';
	 }
	 else
	 { 
		$ls_mostrar_estruc = 'style="display:none"';
	 }
}
else
{
   $ls_operacion="CARGAR";
   if($li_estpreing=='1')
	{
	$ls_mostrar_estruc = 'style="display:compact"';
	}
	else
	{ 
	$ls_mostrar_estruc = 'style="display:none"';
	}
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
//Radio Button
if  (array_key_exists("radiobutton",$_POST))
	{
	  $ls_distribucion=$_POST["radiobutton"];
    }
else
	{
	  $ls_distribucion="";
	}

$_SESSION["fechacomprobante"] = $ldt_fecha;
$ls_codban = '---';
$ls_ctaban = '-------------------------';
	
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
	if  (array_key_exists("estcla",$_POST))
	{
	  $ls_estcla=$_POST["estcla"];
    }
    else
	{
	  $ls_estcla="";
	}
?>
  <?php
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if (($ls_permisos)||($ls_logusr=="PSEGIS"))
	{
		print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
		
	}
	else
	{
		
		print("<script language=JavaScript>");
		print(" location.href='sigespwindow_blank.php'");
		print("</script>");
	}
	//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////
  if($li_estpreing=='1')
 {
	$ls_mostrar_estruc = 'style="display:compact"';
 }
 else
 { 
	$ls_mostrar_estruc = 'style="display:none"';
 }
  
  ?>
</p>
<table width="798" height="241" border="0" align="center">
  <tr>
    <td height="237"><table width="550" height="272" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <?php
		  if($li_estpreing=='1')
		  {
		?>
		 <tr <? print $ls_mostrar_estruc ?>>
         <td height="5" colspan="8" class="titulo-ventana">APERTURA DE CUENTAS</td>
         </tr>
		<?php  
		  }
		  else
		  {
	     ?>
	 	   <tr>
            <td height="20" colspan="5" class="titulo-ventana">APERTURA DE CUENTAS</td>
          </tr>
       <?php  
		  }
	     ?>

	   <tr height="50" colspan="5">
	     <td height="15" <? print $ls_mostrar_estruc ?>>&nbsp;</td>
	     <td colspan="4" <? print $ls_mostrar_estruc ?>>&nbsp;</td>
	     </tr>
	   
	  <tr <? print $ls_mostrar_estruc ?>>
	  <?php
			 $la_empresa =  $_SESSION["la_empresa"];
		     $li_estmodest  = $la_empresa["estmodest"];
			 $li_estpreing = $la_empresa["estpreing"];
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
			<td height="22" <? print $ls_mostrar_estruc ?>><div align="right"><?php print $ls_NomEstPro1;?></div></td>
			<td width="439" colspan="4" ><div align="right"></div>
			  <div align="left"><input name="codestpro1" type="text" id="codestpro1" style="text-align:center" value="<?php print $ls_codestpro1 ?>" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>"  readonly>
			  <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
			  <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" value="<?php print $ls_denestpro1 ?>" size="45">
			  <input name="estcla" type="hidden" id="estcla" value="<?php print $ls_estcla;?>"></div></td>
		  </tr>
		   <tr class="formato-blanco" <? print $ls_mostrar_estruc ?>>
			<td height="20" ><div align="right" ><?php print $ls_NomEstPro2;?></div></td>
			<td colspan="3" ><input name="codestpro2" type="text" id="codestpro22" style="text-align:center" value="<?php print $ls_codestpro2 ?>" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>"  readonly>
			<a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
				<input name="denestpro2" type="text" class="sin-borde" id="denestpro2" value="<?php print $ls_denestpro2 ?>" size="45"></td>
		  </tr>
		  <tr class="formato-blanco"<? print $ls_mostrar_estruc ?> >
			<td height="20" ><div align="right"><?php print $ls_NomEstPro3;?></div></td>
			<td colspan="3" ><input name="codestpro3" type="text" id="codestpro33" style="text-align:center"  value="<?php print $ls_codestpro3 ?>" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>"  readonly>
				<a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
				<input name="denestpro3" type="text" class="sin-borde" id="denestpro3" value="<?php print $ls_denestpro3 ?>" size="45">        </td>
		  </tr>
			<?php
				 if($li_estmodest==2)
				 {	
				?>
			  <tr <? print $ls_mostrar_estruc ?> >
				<td height="20" ><div align="right" ><?php print $ls_NomEstPro4;?></div></td>
				<td colspan="3" ><input name="codestpro4" type="text" id="codestpro4" style="text-align:center"  value="<?php print $ls_codestpro4 ?>" size="<?php print $ls_loncodestpro4; ?>" maxlength="<?php print $ls_loncodestpro4; ?>" readonly>
					<a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 4"> </a>
					<input name="denestpro4" type="text" class="sin-borde" id="denestpro4" value="<?php print $ls_denestpro4 ?>" size="45"  readonly>        </td>
			  </tr>
			  <tr <? print $ls_mostrar_estruc ?> >
				<td height="20" ><div align="right"><?php print $ls_NomEstPro5;?></div></td>
				<td colspan="3" ><input name="codestpro5" type="text" id="codestpro5" style="text-align:center"  value="<?php print $ls_codestpro5 ?>" size="<?php print $ls_loncodestpro5; ?>" maxlength="<?php print $ls_loncodestpro5; ?>"   readonly>
					<a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 5"> </a>
					<input name="denestpro5" type="text" class="sin-borde" id="denestpro5" value="<?php print $ls_denestpro5 ?>" size="45"  readonly>        </td>
				<?php  
				   }
				 ?>
      </tr>
      
      <tr >
        <td height="22" colspan="5"><div align="center">
          <table width="450" height="53" border="0" align="center" class="formato-blanco">
            <tr>
              <td width="55"><div align="right">Fecha</div></td>
              <td width="78"><div align="left">
                <input name="txtFecha2" type="text" class="formato-blanco" id="txtFecha3" value="<?php print $ldt_fecha?>" size="10" readonly >
              </div></td>
              <td width="88">&nbsp;</td>
              <td width="73">&nbsp;</td>
              <td width="50"><div align="left">
              </div></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Distribuci&oacute;n</div></td>
              <?php 	 
			  if(($ls_distribucion=="N")||($ls_distribucion==""))
			  {
					$ls_ninguno="checked";		
					$ls_auto="";
					$ls_manual="";
			  }
			  elseif($ls_distribucion=="A")
			  {
					$ls_ninguno="";		
					$ls_auto="checked";
					$ls_manual="";
			  }
			  elseif($ls_distribucion=="M")
			  {
					$ls_ninguno="";		
					$ls_auto="";
					$ls_manual="checked";
			  }
		  ?>
              <td><input name="radiobutton" type="radio" value="N" <?php print $ls_ninguno ?>>
    Ninguno</td>
              <td>
                <input name="radiobutton" type="radio" value="A" <?php print $ls_auto ?>>
    Automatico</td>
              <td><input name="radiobutton" type="radio" value="M" <?php print $ls_manual ?>>
    Manual </td>
              <td><a href="javascript:ue_distribuir();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Aceptar" width="15" height="15" border="0"></a></td>
            </tr>
          </table>
        </div></td>
        </tr>
      <tr <? print $ls_mostrar_estruc ?>>
        <td width="109" height="22" <? print $ls_mostrar_estruc ?>>&nbsp;</td>
        <td colspan="4" <? print $ls_mostrar_estruc ?>>&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="5"><div align="center"><span class="Estilo2"> </span><span class="Estilo2">
          <span class="Estilo1">
          <input name="estmodest" type="hidden" id="estmodest" value="<?php print  $li_estmodest; ?>">
          </span>
          <?php	
 //Titulos de la tabla
 $title[1]="Cuenta";   $title[2]="Denominaci&oacute;n";  $title[3]="Monto Previsto"; 
 $ls_nombre="grid_apertura";

if ($ls_operacion == "CARGAR")
{
   $la_empresa =  $_SESSION["la_empresa"];
   $ls_codemp  =  $la_empresa["codemp"];
   $ls_estmodape = $la_empresa["estmodape"];
   $li_estpreing = $la_empresa["estpreing"];
   $li_total=0;
   $object="";
    if($li_estpreing=='1')
    {
       $ls_estcla=$_POST["estcla"];
	}
	else
	{
	  $ls_estcla="";
	}
   if($li_estmodest==2)
   {
		$ls_codestpro1=$fun->uf_cerosizquierda($ls_codestpro1,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
		$ls_codestpro2=$fun->uf_cerosizquierda($ls_codestpro2,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
		$ls_codestpro3=$fun->uf_cerosizquierda($ls_codestpro3,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
		$ls_codestpro4=$fun->uf_cerosizquierda($ls_codestpro4,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
		$ls_codestpro5=$fun->uf_cerosizquierda($ls_codestpro5,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
   }
   else
   {
		$ls_codestpro1=$fun->uf_cerosizquierda($ls_codestpro1,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
		$ls_codestpro2=$fun->uf_cerosizquierda($ls_codestpro2,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
		$ls_codestpro3=$fun->uf_cerosizquierda($ls_codestpro3,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
		$ls_codestpro4=$fun->uf_cerosizquierda(0,25);
		$ls_codestpro5=$fun->uf_cerosizquierda(0,25);
   }
   if($ls_estmodape==1)
   { 
      $lb_valido=$class_aper->uf_spi_procesar_apertura($la_seguridad);
      if($lb_valido)
      {
		   $rs_data=0;
		   $lb_valido=$class_aper->uf_spi_load_cuentas_apertura($rs_data);
		   if($lb_valido)
		   {
			  if($row=$SQL->fetch_row($rs_data))
			  {
			   $data=$SQL->obtener_datos($rs_data);
			   $ds_aper->data=$data;
			   $li_num=$ds_aper->getRowCount("spi_cuenta");
			   $li_totnum=$li_num;
			   for($i=1;$i<=$li_num;$i++)
			   {    
					$ls_cuenta=$data["spi_cuenta"][$i]; 
					$ls_denominacion=$data["denominacion"][$i];
					$ls_distribuir=$data["distribuir"][$i];
					$ld_previsto=number_format($data["previsto"][$i],2,",",".");
					$ld_marzo=number_format($data["marzo"][$i],2,",",".");
					$ld_junio=number_format($data["junio"][$i],2,",",".");
					$ld_septiembre=number_format($data["septiembre"][$i],2,",",".");
					$ld_diciembre=number_format($data["diciembre"][$i],2,",",".");
							
					$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'>";
					$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=sin-borde readonly >";
					$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=sin-borde onBlur='uf_formato(this)' onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)' onFocus= uf_fila(".$i.") value=$ld_previsto style=text-align:right>
				                    <input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo' onBlur='uf_formato(this)' onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)'>
									<input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio' onBlur='uf_formato(this)' onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)'>
								    <input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre' onBlur='uf_formato(this)' onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)'>
									<input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre' onBlur='uf_formato(this)' onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)'>";
			   }//for    
				$class_grid->makegrid($li_totnum,$title,$object,800,'APERTURA',$ls_nombre);     
			  }//if
		   }
		 }  
	   }
	   elseif($ls_estmodape==0)
	   {
		?>
		  <script language="javascript">
			alert("La Apertura ha sido configurada Mensual... ");
			f=document.form1;
			f.action="sigespwindow_blank.php";
			f.submit();
		  </script>
		<?php
	   }
}//if ($ls_operacion == "CARGAR")

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
	    $ld_previsto=$_POST["txtPrevisto".$i];
		$ls_distribuir=$_POST["distribuir".$i];
		$cero=0;
        $ld_cero=number_format($cero,2,",",".");		
		$ld_enero=$ld_cero;	    $ld_febrero=$ld_cero;
	    $ld_marzo=$_POST["marzo".$i];
	    $ld_abril=$ld_cero;	    $ld_mayo=$ld_cero;
	    $ld_junio=$_POST["junio".$i];
	    $ld_julio=$ld_cero;	    $ld_agosto=$ld_cero;
	    $ld_septiembre=$_POST["septiembre".$i];
	    $ld_octubre=$ld_cero;	    $ld_noviembre=$ld_cero;
	    $ld_diciembre=$_POST["diciembre".$i];

		$la_empresa =  $_SESSION["la_empresa"];
        $class_aper->is_codemp  =  $la_empresa["codemp"];
		$class_aper->is_procedencia = "SPIAPR";		
		$class_aper->is_comprobante = "0000000APERTURA";
		$class_aper->ii_tipo_comp   = 2;
		$class_aper->is_ced_ben     = "----------";
		$class_aper->is_cod_prov    = "----------";
		$class_aper->is_tipo        = "-";
		$class_aper->is_descripcion = "APERTURA DE CUENTAS";
		$class_aper->id_fecha = $la_empresa["periodo"];
		$class_aper->as_codban      = $ls_codban;
		$class_aper->as_ctaban      = $ls_ctaban;
		
		$ld_previsto=str_replace('.','',$ld_previsto);
		$ld_previsto=str_replace(',','.',$ld_previsto);
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
		$lr_datos["spi_cuenta"][$i]=$ls_cuenta;
		$lr_datos["denominacion"][$i]=$ls_denominacion;
		$lr_datos["previsto"][$i]=$ld_previsto;
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
		
		if($li_estmodest==2)
	    {
			$ls_codestpro1=$fun->uf_cerosizquierda($ls_codestpro1,25);
			$ls_codestpro2=$fun->uf_cerosizquierda($ls_codestpro2,25);
			$ls_codestpro3=$fun->uf_cerosizquierda($ls_codestpro3,25);
			$ls_codestpro4=$fun->uf_cerosizquierda($ls_codestpro4,25);
			$ls_codestpro5=$fun->uf_cerosizquierda($ls_codestpro5,25);
			$estprog[0]  = $ls_codestpro1; 
			$estprog[1]  = $ls_codestpro2; 
			$estprog[2]  = $ls_codestpro3;
			$estprog[3]  = $ls_codestpro4;
			$estprog[4]  = $ls_codestpro5;
			$estprog[5]  = $ls_estcla;
	    }
	    else
	    {
			$estprog[0]  = $fun->uf_cerosizquierda($ls_codestpro1,25); 
			$estprog[1]  = $fun->uf_cerosizquierda($ls_codestpro2,25); 
			$estprog[2]  = $fun->uf_cerosizquierda($ls_codestpro3,25);
			$estprog[3]  = $fun->uf_cerosizquierda(0,25);
			$estprog[4]  = $fun->uf_cerosizquierda(0,25);
			$estprog[5]  = $ls_estcla;
	    }
		
		$ld_previsto=number_format($ld_previsto,2,",",".");
		$ld_enero=number_format($ld_enero,2,",",".");
	    $ld_febrero=number_format($ld_febrero,2,",",".");
	    $ld_marzo=number_format($ld_marzo,2,",",".");
	    $ld_abril=number_format($ld_abril,2,",",".");
	    $ld_mayo=number_format($ld_mayo,2,",",".");
	    $ld_junio=number_format($ld_junio,2,",",".");
	    $ld_julio=number_format($ld_julio,2,",",".");
	    $ld_agosto=number_format($ld_agosto,2,",",".");
	    $ld_septiembre=number_format($ld_septiembre,2,",",".");
	    $ld_octubre=number_format($ld_octubre,2,",",".");
	    $ld_noviembre=number_format($ld_noviembre,2,",",".");
	    $ld_diciembre=number_format($ld_diciembre,2,",",".");
		$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'>";
		$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=sin-borde readonly >";
		$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=sin-borde onKeyPress='return keyRestrictgrid(event)' onFocus= uf_fila(".$i.") value=$ld_previsto style=text-align:right>
						<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo' onBlur='uf_formato(this)' onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)'>
						<input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio' onBlur='uf_formato(this)' onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)'>
						<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre' onBlur='uf_formato(this)' onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)'>
						<input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre' onBlur='uf_formato(this)' onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)'>";
	 }//for
	 if($lb_valido)
	 {
		$lb_valido=$class_aper->procesar_guardar_apertura($lr_datos,$estprog,$la_seguridad,$li_num);
	 } 
     if ($lb_valido)
     {
	    $msg->message(" La Apertura fue registrada con  exito..... ");
     }
	
	 $class_grid->makegrid($li_num,$title,$object,800,'APERTURA',$ls_nombre);
}//GUARDAR	  
?>
          <input name="operacion" type="hidden" id="operacion" value="<?php $_POST["operacion"] ?>">
          <input name="li_totnum" type="hidden" id="li_totnum" value="<?php print $li_totnum; ?>">
          <input name="fila" type="hidden" id="fila">
        </span></div></td>
        </tr>
    </table>
      </td>
  </tr>
</table>
<p>&nbsp;</p>
</form>
</body>
<script language="javascript">
function ue_distribuir()
{
   var i ;
   f=document.form1;
   li=f.fila.value;
   if(li!="")
   {
	   txtprevisto="txtPrevisto"+li;
	   ld_previsto=eval("f."+txtprevisto+".value");
	   if(ld_previsto=="0,00")
	   {
		 alert(" Por Favor el monto es incorrecto ...");
	   }
	   else
	   {
			for (i=0;i<f.radiobutton.length;i++)
			{ 
			   if (f.radiobutton[i].checked) 
				  break; 
			} 
			document.opcion = f.radiobutton[i].value; 
			 
			if (document.opcion=="M" ) 
			{
				 ls_distribuir=3;
				 opcion=document.opcion;
				 distribuir="distribuir"+li;
				 eval("f."+distribuir+".value='"+ls_distribuir+"'") ;
				 txtPrevisto="txtPrevisto"+li;
				 ld_previsto=eval("f."+txtPrevisto+".value");
				 txtCuenta="txtCuenta"+li;
				 ls_cuenta=eval("f."+txtCuenta+".value");
				 txtDenominacion="txtDenominacion"+li;
				 ls_denominacion=eval("f."+txtDenominacion+".value");
				 marzo="marzo"+li;
				 ld_marzo=eval("f."+marzo+".value");
				 junio="junio"+li;
				 ld_junio=eval("f."+junio+".value");
				 septiembre="septiembre"+li;
				 ld_septiembre=eval("f."+septiembre+".value");
				 diciembre="diciembre"+li;
				 ld_diciembre=eval("f."+diciembre+".value");
				 
				 pagina="sigesp_spi_p_apertura_trimestral_distribucion.php?fila="+li+"&txtPrevisto="+ld_previsto+"&marzo="+ld_marzo
				         +"&junio="+ld_junio+"&septiembre="+ld_septiembre+"&diciembre="+ld_diciembre
						 +"&txtCuenta="+ls_cuenta+"&txtDenominacion="+ls_denominacion+"&tipo="+opcion;
				 window.open(pagina,"Asignación","menubar=no,toolbar=no,scrollbars=no,width=650,height=450,left=50,top=50,resizable=yes,location=no");
		   }
			 
		   if (document.opcion=="A")
		   {
			   f=document.form1;
			   li=f.fila.value;
			   ls_distribuir=1;
			   distribuir="distribuir"+li;
			   eval("f."+distribuir+".value='"+ls_distribuir+"'") ;
			   li_total=f.li_totnum.value;
			   opcion=document.opcion;
			   if(li!="")
			   {
				   txtCuenta="txtCuenta"+li;
				   ls_cuenta=eval("f."+txtCuenta+".value");
				   txtDenominacion="txtDenominacion"+li;
				   ls_denominacion=eval("f."+txtDenominacion+".value");
				   txtprevis="txtPrevisto"+li;
				   ld_previsto=eval("f."+txtprevis+".value");
				   ld_previsto=uf_convertir_monto(ld_previsto);
				   ld_division=parseFloat((ld_previsto/4));
				  /* ld_division=redondear(ld_division,2);
				   ld_previsto=redondear(ld_previsto,2);
				   ld_suma_diciembre=redondear((ld_division*4),2);*/
				   ld_division=redondear2(ld_division);
				   ld_previsto=redondear2(ld_previsto);
				   ld_suma_diciembre=redondear2(ld_division*4);
				   ld_mes12=(ld_previsto-ld_suma_diciembre);
				  // ld_mes12=redondear(ld_mes12,2);
				   ld_mes12=redondear2(ld_mes12);
				   if(ld_mes12>=0)
				   {
					ld_diciembre=ld_division+ld_mes12;
				   } 			
				   else//if(ld_mes12<0)
				   {
					ld_diciembre=ld_division+ld_mes12;
				   } 
				   ld_total=(ld_division*3);
				   ld_total_general=ld_total+ld_diciembre;
				   //ld_total_general=redondear(ld_total_general,2);
				   ld_total_general=redondear2(ld_total_general);
				   ld_resto=(ld_previsto-ld_total_general);
				   //ld_resto=redondear(ld_resto,2);
				   ld_resto=redondear2(ld_resto);
				   ld_diciembre=ld_diciembre+ld_resto;
				   ld_division=uf_convertir(ld_division);
				   ld_diciembre=uf_convertir(ld_diciembre);
				   distribuir="distribuir"+li;
				   eval("f."+distribuir+".value='"+ls_distribuir+"'") ;
				   m3="marzo"+li;
				   ld_marzo=eval("f."+m3+".value='"+ld_division+"'") ;
				   m6="junio"+li;
				   ld_junio=eval("f."+m6+".value='"+ld_division+"'") ;
				   m9="septiembre"+li;
				   ld_septiembre=eval("f."+m9+".value='"+ld_division+"'") ;
				   m12="diciembre"+li;
				   ld_diciembre=eval("f."+m12+".value='"+ld_diciembre+"'") ;
				
				   pagina="sigesp_spi_p_apertura_trimestral_distribucion.php?fila="+li+"&txtPrevisto="+ld_previsto+"&marzo="+ld_marzo
				          +"&junio="+ld_junio+"&septiembre="+ld_septiembre+"&diciembre="+ld_diciembre
						  +"&txtCuenta="+ls_cuenta+"&txtDenominacion="+ls_denominacion+"&tipo="+opcion;
				   window.open(pagina,"Asignación","menubar=no,toolbar=no,scrollbars=no,width=650,height=450,left=50,top=50,resizable=yes,location=no");
				}
				else
				{
				 alert("Por favor coloque el cursor sobre la fila  a editar  ");
				}	 
		   }
		   
		   if (document.opcion=="N")
		   {			  
			   f=document.form1;
			   li=f.fila.value;
			   if(li!="")
			   {
				   ld_cero="0,00";
				   ls_distribuir=2;
				   distribuir="distribuir"+li;
				   eval("f."+distribuir+".value='"+ls_distribuir+"'") ;
				   txtprevis="txtPrevisto"+li;
				   ld_previsto=eval("f."+txtprevis+".value");
				   m3="marzo"+li;
				   ld_marzo=eval("f."+m3+".value='"+ld_cero+"'") ;
				   m6="junio"+li;
				   ld_junio=eval("f."+m6+".value='"+ld_cero+"'") ;
				   m9="septiembre"+li;
				   ld_septiembre=eval("f."+m9+".value='"+ld_cero+"'") ;
				   m12="diciembre"+li;
				   ld_diciembre=eval("f."+m12+".value='"+ld_cero+"'") ;
				}   
				else
				{
				 alert("Por favor coloque el cursor sobre la fila  a editar  ");
				}	 
		   }
		}
  }
  else
  {
    alert("Por favor coloque el cursor sobre la fila  a editar  "); 
  }	 
}

function redondear(num, dec)
{ 
    num = parseFloat(num); 
    dec = parseFloat(dec); 
    dec = (!dec ? 2 : dec); 
    return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec); 
}
    
 function uf_formato(obj)
  { alert("entro aqui");
	 ldec_temp1=obj.value;
	 if((ldec_temp1=="")||(ldec_temp1==".")||(ldec_temp1==","))
	 {
      ldec_temp1="0,00";
	 } 
     obj.value=uf_convertir(ldec_temp1); 
  }
  
function ue_guardar()
{
	f=document.form1;
	if(f.li_totnum.value==0)
	{
	  alert(" Debe tener al menos un registro cargado  ");
	}
	else
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_spi_p_apertura_trimestral.php";
		f.submit();
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
//--------------------------------------------------------
//	Función que formatea un número
//--------------------------------------------------------
function ue_formatonumero(fld, milSep, decSep, e)
{ 
	var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 

	if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Return
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

 function uf_formato(obj)
  { alert("entro aqui");
	 ldec_temp1=obj.value;
	 if((ldec_temp1=="")||(ldec_temp1==".")||(ldec_temp1==","))
	 {
      ldec_temp1="0,00";
	 } 
     obj.value=uf_convertir(ldec_temp1); 
  }
  
function catalogo_estpro1()
{
	   pagina="sigesp_spi_cat_public_estpro1.php";
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
		pagina="sigesp_spi_cat_public_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&estcla="+estcla;
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
			pagina="sigesp_spi_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
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
			pagina="sigesp_spi_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
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
		pagina="sigesp_spi_cat_public_estpro4.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
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
			pagina="sigesp_spi_cat_public_estpro5.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
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

</script>
</html>
