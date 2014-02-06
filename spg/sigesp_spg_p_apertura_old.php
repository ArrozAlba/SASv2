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
.Estilo3 {color: #6699CC}
-->
</style>
</head>

<body>
<table width="780" border="0" align="left" cellpadding="0" cellspacing="0" class="contorno">
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
    <td height="20" class="toolbar"><img src="../shared/imagebank/tools20/espacio.gif" width="4" height="20"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<form name="form1" method="post" action="">
<p>&nbsp;</p>
<p>&nbsp;</p>
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
require_once("../shared/class_folder/grid_param.php");
require_once("sigesp_spg_class_apertura.php");

$class_aper=new sigesp_spg_class_apertura();
$ds_aper=new class_datastore();
$class_grid=new grid_param();


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

//Radio Button
if  (array_key_exists("radiobutton",$_POST))
	{
	  $ls_distribucion=$_POST["radiobutton"];
    }
else
	{
	  $ls_distribucion="";
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
        ?>
</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="798" height="241" border="0">
  <tr>
    <td height="237"><p>&nbsp;</p>
      <table width="570" height="171" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="20" colspan="5" class="titulo-ventana">APERTURA DE CUENTAS</td>
      </tr>
      <tr>
        <td height="18" colspan="5"><span class="Estilo2"></span></td>
      </tr>
      <tr>
        <?php
	     $la_empresa =  $_SESSION["la_empresa"];
         $ls_codemp  =  $la_empresa["codemp"];
		 $ls_NomEstPro1 = $la_empresa["nomestpro1"];
		 $ls_NomEstPro2 = $la_empresa["nomestpro2"];
		 $ls_NomEstPro3 = $la_empresa["nomestpro3"];
	     $rs_SPG=$class_aper->uf_llenar_combo_estpro1($ls_codemp);
	  ?>
        <td width="123" height="22"><div align="right">
            <?php
	     $rs_SPG2=$class_aper->uf_llenar_combo_estpro2($ls_codemp, $ls_codestpro1);
	  ?>
            <?php print $ls_NomEstPro1;?></div>
            <div align="left"></div></td>
        <td colspan="4">
          <div align="right"></div>
          <div align="left">
            <input name="codestpro1" type="text" id="codestpro1" style="text-align:center" value="<?php print $ls_codestpro1 ?>" size="22" maxlength="20" readonly>
            <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
            <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" value="<?php print $ls_denestpro1 ?>" size="45">
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right"><?php print $ls_NomEstPro2;?></div></td>
        <td colspan="4"><input name="codestpro2" type="text" id="codestpro22" style="text-align:center" value="<?php print $ls_codestpro2 ?>" size="22" maxlength="6" readonly>          <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
            <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" value="<?php print $ls_denestpro2 ?>" size="45"></td><?php $rs_SPG3=$class_aper->uf_llenar_combo_estpro3($ls_codemp, $ls_codestpro1, $ls_codestpro2);
		 ?>
      </tr>
      <tr class="formato-blanco">
        <td height="21"><div align="right"><?php print $ls_NomEstPro3;?></div></td>
        <td colspan="4"><input name="codestpro3" type="text" id="codestpro33" style="text-align:center"  value="<?php print $ls_codestpro3 ?>" size="22" maxlength="3" readonly>
            <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
            <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" value="<?php print $ls_denestpro3 ?>" size="45">
        </td>
      </tr>
      <tr>
        <td height="22"><div align="right">Fecha</div></td>
        <td colspan="4"><input name="txtFecha" type="text" class="formato-blanco" id="txtFecha" value="<?php print $ldt_fecha?>" size="10" readonly ></td>
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
        <td width="94"><input name="radiobutton" type="radio" value="N" <?php print $ls_ninguno ?>>
      Ninguno</td>
        <td width="113">
          <input name="radiobutton" type="radio" value="A" <?php print $ls_auto ?>>
      Automatico</td>
        <td width="70"><input name="radiobutton" type="radio" value="M" <?php print $ls_manual ?>>
      Manual </td>
        <td width="168"><a href="javascript:ue_distribuir();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Aceptar" width="15" height="15" border="0"></a></td>
      </tr>
      <tr>
        <td height="22"><span class="Estilo2"> </span></td>
        <td colspan="4"><span class="Estilo2"></span></td>
      </tr>
    </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
<p>
  <?php	
 //Titulos de la tabla
 $title[1]="Cuenta";   $title[2]="Denominación";  $title[3]="Asignación";  $title[4]="Enero";  $title[5]="Febrero";  $title[6]="Marzo"; 
 $title[7]="Abril";    $title[8]="Mayo";   $title[9]="Junio";  $title[10]="Julio";  $title[11]="Agosto"; $title[12]="Septiembre";
 $title[13]="Octubre"; $title[14]="Noviembre"; $title[15]="Diciembre"; $ls_nombre="grid_apertura";

if ($ls_operacion == "")
{
   $la_empresa =  $_SESSION["la_empresa"];
   $ls_codemp  =  $la_empresa["codemp"];
   $li_total=0;
   $object="";
   $as_estmodape="";
   $lb_valido=$class_aper->uf_spg_select_modalidad_apertura($ls_codemp,$as_estmodape);
   if(($lb_valido) && ($as_estmodape==0))
   { 
	   $class_grid->makegrid($li_total,$title,$object,800,'APERTURA',$ls_nombre);     
	   $lb_valido=$class_aper->uf_spg_procesar_apertura($la_seguridad);
   }
   elseif($as_estmodape==1)
   {
		?>
		<script language="javascript">
		alert("La Apertura ha sido configurada Trimestral... ");
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
   $rs_load=$class_aper->uf_spg_load_cuentas_apertura($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3);
   
  if($row=$SQL->fetch_row($rs_load))
  {
   $data=$SQL->obtener_datos($rs_load);
   $ds_aper->data=$data;
   $li_num=$ds_aper->getRowCount("spg_cuenta");
   $li_totnum=$li_num;
   for($i=1;$i<=$li_num;$i++)
   {    
        $ls_cuenta=$data["spg_cuenta"][$i]; 
		//$ls_cuenta=$data["status"][$i];  
		$ls_denominacion=$data["denominacion"][$i];
		$ls_distribuir=$data["distribuir"][$i];
		$ld_asignado=number_format($data["asignado"][$i],2,",",".");
		$ld_enero=number_format($data["enero"][$i],2,",",".");
		$ld_febrero=number_format($data["febrero"][$i],2,",",".");
		$ld_marzo=number_format($data["marzo"][$i],2,",",".");
		$ld_abril=number_format($data["abril"][$i],2,",",".");
		$ld_mayo=number_format($data["mayo"][$i],2,",",".");
		$ld_junio=number_format($data["junio"][$i],2,",",".");
		$ld_julio=number_format($data["julio"][$i],2,",",".");
		$ld_agosto=number_format($data["agosto"][$i],2,",",".");
		$ld_septiembre=number_format($data["septiembre"][$i],2,",",".");
		$ld_octubre=number_format($data["octubre"][$i],2,",",".");
		$ld_noviembre=number_format($data["noviembre"][$i],2,",",".");
		$ld_diciembre=number_format($data["diciembre"][$i],2,",",".");
				
        $object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde readonly><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'>";
		$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=sin-borde readonly >";
		$object[$i][3]="<input type=text name=txtAsignacion".$i." class=sin-borde onBlur='uf_formato(this)' onKeyPress='return keyRestrictgrid(event)' value=$ld_asignado  onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][4]="<input type=text name=txtEnero".$i."  onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=$ld_enero class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][5]="<input type=text name=txtFebrero".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=$ld_febrero class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][6]="<input type=text name=txtMarzo".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)'  value=$ld_marzo class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][7]="<input type=text name=txtAbril".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=$ld_abril class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][8]="<input type=text name=txtMayo".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=$ld_mayo class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][9]="<input type=text name=txtJunio".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=$ld_junio class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][10]="<input type=text name=txtJulio".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=$ld_julio class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][11]="<input type=text name=txtAgosto".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=$ld_agosto class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][12]="<input type=text name=txtSeptiembre".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=$ld_septiembre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][13]="<input type=text name=txtOctubre".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=$ld_octubre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][14]="<input type=text name=txtNoviembre".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=$ld_noviembre  class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		$object[$i][15]="<input type=text name=txtDiciembre".$i." onBlur=uf_format(this) onKeyPress='return keyRestrictgrid(event)' value=$ld_diciembre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
   }//for    
    $class_grid->makegrid($li_totnum,$title,$object,800,'APERTURA',$ls_nombre);     
  }//if
  else
  {
     $li_total=0;
     $object="";
	 $class_grid->makegrid($li_total,$title,$object,800,'APERTURA',$ls_nombre);  
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
        
        $estprog[0]  = $ls_codestpro1; 
        $estprog[1]  = $ls_codestpro2; 
        $estprog[2]  = $ls_codestpro3;
        $estprog[3]  = "00";
        $estprog[4]  = "00";
      
		$la_empresa =  $_SESSION["la_empresa"];
        $class_aper->is_codemp  =  $la_empresa["codemp"];
		$class_aper->is_procedencia = "SPGAPR";		
		$class_aper->is_comprobante = "0000000APERTURA";
		$class_aper->ii_tipo_comp   = 2;
		$class_aper->is_ced_ben     = "----------";
		$class_aper->is_cod_prov    = "----------";
		$class_aper->is_tipo        = "-";
		$class_aper->is_descripcion = "APERTURA DE CUENTAS";
		$class_aper->id_fecha = $la_empresa["periodo"];
		
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
</p>
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
	//f.operacion.value="est1";
	f.submit();
}

function uf_cambio_estpro2()
{
	f=document.form1;
	f.action="sigesp_spg_p_apertura.php";
	//f.operacion.value="est2";
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
			   txtasig="txtAsignacion"+li;
			   ld_asignado=eval("f."+txtasig+".value");
			   ld_asignado=uf_convertir_monto(ld_asignado);
			   ld_division=parseFloat((ld_asignado/12));
			   ld_division=redondear(ld_division,2);
			   ld_suma_diciembre=redondear((ld_division*12),2);
			   ld_mes12=redondear((ld_suma_diciembre-ld_asignado),2);
               if(ld_mes12>=0)
			   {
			    ld_diciembre=ld_division-ld_mes12;
			   } 			
               if(ld_mes12<0)
			   {
			    ld_diciembre=ld_division+ld_mes12;
			   } 	
			   ld_total=(ld_division*11)+ld_diciembre;
			   ld_resto=redondear((ld_asignado-ld_total),2);
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
			else
			{
			 alert("Por favor coloque el cursor sobre la fila  a editar  ");
		    }	 
		 f.action="sigesp_spg_p_apertura.php";
	   }
	   
	   if (document.opcion=="N")
	   {
		   f=document.form1;
		   li=f.fila.value;
		   if(li!="")
		   {
		       distribuir="distribuir"+li;
		       eval("f."+distribuir+".value='"+ls_distribuir+"'") ;
			   txtasig="txtAsignacion"+li;
			   ld_asignado=eval("f."+txtasig+".value");
			   ld_cero="0,00";
			   txtm1="txtEnero"+li;
			   eval("f."+txtm1+".value='"+ld_cero+"'") ;
			   txtm2="txtFebrero"+li;
			   eval("f."+txtm2+".value='"+ld_cero+"'") ;
			   txtm3="txtMarzo"+li;
			   eval("f."+txtm3+".value='"+ld_cero+"'") ;
			   txtm4="txtAbril"+li;
			   eval("f."+txtm4+".value='"+ld_cero+"'") ;
			   txtm5="txtMayo"+li;
			   eval("f."+txtm5+".value='"+ld_cero+"'") ;
			   txtm6="txtJunio"+li;
			   eval("f."+txtm6+".value='"+ld_cero+"'") ;
			   txtm7="txtJulio"+li;
			   eval("f."+txtm7+".value='"+ld_cero+"'") ;
			   txtm8="txtAgosto"+li;
			   eval("f."+txtm8+".value='"+ld_cero+"'") ;
			   txtm9="txtSeptiembre"+li;
			   eval("f."+txtm9+".value='"+ld_cero+"'") ;
			   txtm10="txtOctubre"+li;
			   eval("f."+txtm10+".value='"+ld_cero+"'") ;
			   txtm11="txtNoviembre"+li;
			   eval("f."+txtm11+".value='"+ld_cero+"'") ;
			   txtm12="txtDiciembre"+li;
			   eval("f."+txtm12+".value='"+ld_cero+"'") ;
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
     obj.value=uf_convertir(ldec_temp1);
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
		if ((ld_total>ld_asignado)||(ld_total<ld_asignado))
		{
		  alert("La Distribución no cuadra con lo asignado. Por favor revise los montos ");
          obj.focus();
		}	
	 }	
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
		f.action="sigesp_spg_p_apertura.php";
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
	
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1;
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
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
	{
    	pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&tipo=apertura";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
    	pagina="sigesp_cat_public_estpro.php?tipo=apertura";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}
</script>
</html>
