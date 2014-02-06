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
	$ls_ventanas="sigesp_spg_p_progrep_trim.php";

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
<title>Programaci&oacute;n de Reportes Trimestral</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="styleshee t" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="javascript1.2" src="js/valida_tecla_grid.js"></script>
<style type="text/css">
<!--
.Estilo2 {font-size: 15px}
.Estilo3 {font-size: 11px}
-->
</style>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo4 {color: #6699CC}
-->
</style>
</head>

<body>
<table width="798" height="90" border="0" align="left" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="1007" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="798" height="40"></td>
  </tr>
      <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo4">Sistema de Presupuesto de Ingreso</td>
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
  <p>&nbsp;</p>
  <p>&nbsp;  </p>
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
require_once("sigesp_spg_class_progrep.php");
$class_progrep=new sigesp_spg_class_progrep();
require_once("../shared/class_folder/class_sigesp_int_spg.php");
$int_spg=new class_sigesp_int_spg();
$ds_progrep=new class_datastore();
require_once("../shared/class_folder/grid_param.php");
$class_grid=new grid_param();
$sig_int=new class_sigesp_int();

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

if (array_key_exists("txtDenominacion",$_POST))
{
  $ls_denominacion=$_POST["txtDenominacion"];
}
else
{
  $ls_denominacion="";
}

if	(array_key_exists("cmbrep",$_POST))
	{
	  $ls_codrep=$_POST["cmbrep"];
	}
else
	{
	  $ls_codrep="00001";
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
  <table width="798" height="169" border="0">
    <tr>
      <td width="833"><p>&nbsp;</p>
        <table width="580" height="192" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td height="26" colspan="5" class="titulo-ventana"><div align="center">Programaci&oacute;n de Reporte Trimestral</div></td>
        </tr>
        <tr>
          <td height="18" colspan="5"><span class="Estilo2"></span></td>
        </tr>
        <tr>
          <?php
	 
	  //print "codigo report ".$ls_codrep;
	  if ($ls_codrep=='00001')
	  {
	    $ej_f1="selected";
		$ej_f2="";
		$ej_f3="";
		$ej_f4="";
		$ej_f5="";
	  }
	  
	  if ($ls_codrep=='00002')
	  {
	    $ej_f1="";
		$ej_f2="selected";
		$ej_f3="";
		$ej_f4="";
		$ej_f5="";
	  }
	  
	  if ($ls_codrep=='00003')
	  {
	    $ej_f1="";
		$ej_f2="";
		$ej_f3="selected";
		$ej_f4="";
		$ej_f5="";
	  }
	  
	  if ($ls_codrep=='00004')
	  {
	    $ej_f1="";
		$ej_f2="";
		$ej_f3="";
		$ej_f4="selected";
		$ej_f5="";
	  }
	  
	  if ($ls_codrep=='00005')
	  {
	    $ej_f1="";
		$ej_f2="";
		$ej_f3="";
		$ej_f4="";
		$ej_f5="selected";
	  }
	
	?>
          <td width="144" height="21"><div align="right">Reporte</div></td>
          <td colspan="4"><select name="cmbrep" id="select2" onChange="uf_cargargrid()">
              <option value="00001" <?php print $ej_f1 ?>>Ejecuci&oacute;n Financiera - Formato 1</option>
              <option value="00002" <?php print $ej_f2 ?>>Ejecuci&oacute;n Financiera - Formato 2</option>
              <option value="00003" <?php print $ej_f3 ?>>Ejecuci&oacute;n Financiera - Formato 3</option>
              <option value="00004" <?php print $ej_f4 ?>>Ejecuci&oacute;n de Gastos Especificos</option>
              <option value="00005" <?php print $ej_f5 ?>>Flujo de Caja </option>
            </select>
              <input name="botRecargar" type="button" class="boton" id="botRecargar" onClick="ue_recargar()" value="Recargar"></td>
        </tr>
        <tr>
          <?php
	     $la_empresa =  $_SESSION["la_empresa"];
         $ls_codemp  =  $la_empresa["codemp"];
		 $ls_NomEstPro1 = $la_empresa["nomestpro1"];
		 $ls_NomEstPro2 = $la_empresa["nomestpro2"];
		 $ls_NomEstPro3 = $la_empresa["nomestpro3"];
	     $rs_SPG=$class_progrep->uf_llenar_combo_estpro1($ls_codemp);
	  ?>
          <td height="20"><div align="right"><span class="Estilo3">
              <?php
	     $rs_SPG2=$class_progrep->uf_llenar_combo_estpro2($ls_codemp, $ls_codestpro1);
	  ?>
            </span><?php print $ls_NomEstPro1;?></div>
              <div align="left"></div></td>
          <td colspan="4">
            <input name="codestpro1" type="text" id="codestpro1" style="text-align:center" value="<?php print $ls_codestpro1 ?>" size="22" maxlength="20" readonly>
            <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
            <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" value="<?php print $ls_denestpro1 ?>" size="45">
            <div align="right"></div>
            <div align="center"> </div></td>
        </tr>
        <tr class="formato-blanco">
          <td height="20"><div align="right"><?php print $ls_NomEstPro2;?></div></td>
          <td colspan="4"><input name="codestpro2" type="text" id="codestpro22" style="text-align:center" value="<?php print $ls_codestpro2 ?>" size="22" maxlength="6" readonly>
              <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
              <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" value="<?php print $ls_denestpro2 ?>" size="45"></td>
          <?php $rs_SPG3=$class_progrep->uf_llenar_combo_estpro3($ls_codemp, $ls_codestpro1, $ls_codestpro2);
		 ?>
        </tr>
        <tr class="formato-blanco">
          <td height="22"><div align="right"><?php print $ls_NomEstPro3;?></div></td>
          <td colspan="4"><input name="codestpro3" type="text" id="codestpro33" style="text-align:center"  value="<?php print $ls_codestpro3 ?>" size="22" maxlength="3" readonly>
              <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
              <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" value="<?php print $ls_denestpro3 ?>" size="45"></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Distribuci&oacute;n</div></td>
			  <?Php 	 
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
          <td width="123"><input name="radiobutton" type="radio" value="N" <?php print $ls_ninguno ?>>
      Ninguno</td>
          <td width="113">
            <input name="radiobutton" type="radio" value="A" <?php print $ls_auto ?>>
      Automatico</td>
          <td width="76"><input name="radiobutton" type="radio" value="M" <?php print $ls_manual ?>>
      Manual </td>
          <td width="121"><a href="javascript:ue_distribuir();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Aceptar" width="15" height="15" border="0"></a></td>
        </tr>
        <tr>
          <td height="13">&nbsp;</td>
          <td colspan="4">&nbsp;</td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
    </tr>
  </table>
  <p>
    <?php	
 //Titulos de la tabla
 $title[1]="Cuenta";   $title[2]="Denominación";  $title[3]="Asignación";  $title[4]="Trimestre(1)";  $title[5]="Trimestre(2)";  $title[6]="Trimestre(3)"; 
 $title[7]="Trimestre(4)"; $ls_nombre="grid_progrep_trim";  

if($ls_operacion == "")
{
   $li_total=0;
   $object="";
   $class_grid->makegrid($li_total,$title,$object,800,' PROGRAMACION  DE  REPORTES TRIMESTRAL',$ls_nombre);  
    
}//$ls_operacion == ""

if ($ls_operacion == "RECARGAR")
{
   $lb_valido=$class_progrep->uf_prog_report_load_original($ls_codemp, $ls_codrep, $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$la_seguridad);
   $li_total=0;
   $object="";
   $class_grid->makegrid($li_total,$title,$object,800,' PROGRAMACION DE REPORTE TRIMESTRAL ',$ls_nombre);  
   
}//operacion=="RECARGAR"

if ($ls_operacion=="CARGAR" )
{
   $la_empresa =  $_SESSION["la_empresa"];
   $ls_codemp  =  $la_empresa["codemp"];
   $ls_codrep=$_POST["cmbrep"];
   $ls_modrep=3;
   $rs_load=$class_progrep->uf_prog_report_load_data($ls_codemp, $ls_codrep, $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_modrep);
   
	 if($row=$SQL->fetch_row($rs_load))
	 {
		$data=$SQL->obtener_datos($rs_load);
		$ds_progrep->data=$data;
		$li_num=$ds_progrep->getRowCount("spg_cuenta");
		$li_totnum=$li_num;
		   
		for($i=1;$i<=$li_num;$i++)
		{    
			$ls_cuenta=$data["spg_cuenta"][$i];  
			$ls_denominacion=$data["denominacion"][$i];
			$ls_distribuir=$data["distribuir"][$i];
			$ls_modrep=$data["modrep"][$i];
			$ls_status=$data["status"][$i];
			$ld_asignado=number_format($data["asignado"][$i],2,",",".");
			$ld_marzo=number_format($data["marzo"][$i],2,",",".");
			$ld_junio=number_format($data["junio"][$i],2,",",".");
			$ld_septiembre=number_format($data["septiembre"][$i],2,",",".");
			$ld_diciembre=number_format($data["diciembre"][$i],2,",",".");
			
			if($ls_status=="I")
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=formato-azul readonly size=50>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event))  style=text-align:right >";
				$object[$i][4]="<input type=text name=txtMarzo".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=formato-azul readonly  style=text-align:right >";
				$object[$i][5]="<input type=text name=txtJunio".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=formato-azul readonly  style=text-align:right>";
				$object[$i][6]="<input type=text name=txtSeptiembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=formato-azul readonly  style=text-align:right>";
				$object[$i][7]="<input type=text name=txtDiciembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=formato-azul readonly style=text-align:right>";
			}
			else
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde readonly><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=sin-borde readonly >";
				$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)'  class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][4]="<input type=text name=txtMarzo".$i."  onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][5]="<input type=text name=txtJunio".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][6]="<input type=text name=txtSeptiembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][7]="<input type=text name=txtDiciembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		   }
	   }//for    
	   
	   $class_grid->makegrid($li_totnum,$title,$object,800,'PROGRAMACION DE REPORTE TRIMESTRAL',$ls_nombre);     
	  }//if
	  else
	  {
		   $li_total=0;
		   $object="";
		   $class_grid->makegrid($li_total,$title,$object,800,' PROGRAMACION  DE  REPORTES TRIMESTRAL',$ls_nombre);  
	  }
 }//cargar
 
if ($ls_operacion=="DISTRIBUIR" )
{
  $ls_opcion=$_POST["radiobutton"];
  if ($ls_opcion=="M")
  {
    $li_rows=$_POST["fila"];
    $la_empresa =  $_SESSION["la_empresa"];
    $ls_codemp  =  $la_empresa["codemp"];
    $ls_codrep=$_POST["cmbrep"];
    $ls_codestpro1=$_POST["codestpro1"];
    $ls_codestpro2=$_POST["codestpro2"];
    $ls_codestpro3=$_POST["codestpro3"];
    $li_num=$_POST["li_totnum"];
    for($i=1;$i<=$li_num;$i++)
    {    
        $ls_cuenta=$_POST["txtCuenta".$i];   
		$ls_denominacion=$_POST["txtDenominacion".$i];
		$ld_asignado =$_POST["txtAsignacion".$i];
		$ls_status=$_POST["status".$i];
		$ls_distribuir=$_POST["distribuir".$i];
		$ls_modrep=$_POST["modrep".$i];
		$ld_marzo=$_POST["txtMarzo".$i];
		$ld_junio=$_POST["txtJunio".$i];
		$ld_septiembre=$_POST["txtSeptiembre".$i];
		$ld_diciembre=$_POST["txtDiciembre".$i];
		 
		if($li_rows==$i)
		{
		    $ld_asignado=str_replace('.','',$ld_asignado);       $ld_asignado=str_replace(',','.',$ld_asignado);
		    $ld_marzo=str_replace('.','',$ld_marzo);  	         $ld_marzo=str_replace(',','.',$ld_marzo);
			$ld_junio=str_replace('.','',$ld_junio);  	         $ld_junio=str_replace(',','.',$ld_junio);
			$ld_septiembre=str_replace('.','',$ld_septiembre);   $ld_septiembre=str_replace(',','.',$ld_septiembre);
			$ld_diciembre=str_replace('.','',$ld_diciembre);     $ld_diciembre=str_replace(',','.',$ld_diciembre);
		    $ld_total=$ld_marzo+$ld_junio+$ld_septiembre+$ld_diciembre;
		    if($ld_total>$ld_asignado)
	        {
		      $msg->message("El Total es mayor al monto asignado. Por favor revise los montos ");  
	        }
			else
			{
			   $ld_asignado=$ld_total;
			}  
			$marzo=number_format($ld_marzo,2,",",".");
		    $ld_marzo=$marzo;
			$junio=number_format($ld_junio,2,",",".");
		    $ld_junio=$junio;
			$septiembre=number_format($ld_septiembre,2,",",".");
		    $ld_septiembre=$septiembre;
			$diciembre=number_format($ld_diciembre,2,",",".");
		    $ld_diciembre=$diciembre;
			$asig=number_format($ld_asignado,2,",",".");		   
            $ld_asignado=$asig;
		   
			$ls_distribuir=3;
			$ls_modrep=3;
		    $li_nivel_cta  = $class_progrep->uf_obt_nivel_cta($ls_cuenta,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3);//Obtiene nivel de la cta 
			$ls_cta_ceros = $class_progrep->uf_cuenta_sin_ceros($ls_cuenta );  //devuelve la cta sin ceros
			$ls_sc_cuenta = $class_progrep->uf_disable_cta_inferior($ls_cta_ceros, $ls_cuenta, $ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3);  
			$total=count($ls_sc_cuenta);
									
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
		    $object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=sin-borde readonly>";
			$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][4]="<input type=text name=txtMarzo".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][5]="<input type=text name=txtJunio".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][6]="<input type=text name=txtSeptiembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=sin-borde  onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][7]="<input type=text name=txtDiciembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
			
			$ls_status="I";
			$cero=0;
            $ld_cero=number_format($cero,2,",",".");	
			$ld_asignado= $ld_cero;	
			$ld_marzo=$ld_cero;			$ld_junio=$ld_cero;
			$ld_septiembre=$ld_cero;	$ld_diciembre=$ld_cero;
				
			for($li=1;$li<$total;$li++)
			{
				$ls_cuenta=$ls_sc_cuenta[$li]; 
				$ls_denominacion=$class_progrep->uf_select_denominacion($ls_cuenta,$ls_codemp,$ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3);
				$i=$i+1;
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=formato-azul readonly>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
				$object[$i][4]="<input type=text name=txtMarzo".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=formato-azul readonly style=text-align:right>";
				$object[$i][5]="<input type=text name=txtJunio".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=formato-azul readonly style=text-align:right>";
				$object[$i][6]="<input type=text name=txtSeptiembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=formato-azul readonly  style=text-align:right>";
				$object[$i][7]="<input type=text name=txtDiciembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=formato-azul readonly style=text-align:right>";
			}//for
		}//if
		else
		{
			if($ls_status=="I")
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			    $object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=formato-azul readonly size=50>";
			    $object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event))  style=text-align:right >";
			    $object[$i][4]="<input type=text name=txtMarzo".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=formato-azul readonly  style=text-align:right >";
			    $object[$i][5]="<input type=text name=txtJunio".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=formato-azul readonly  style=text-align:right>";
			    $object[$i][6]="<input type=text name=txtSeptiembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=formato-azul readonly  style=text-align:right>";
			    $object[$i][7]="<input type=text name=txtDiciembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=formato-azul readonly style=text-align:right>";
			}
			else
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=sin-borde readonly>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][4]="<input type=text name=txtMarzo".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][5]="<input type=text name=txtJunio".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][6]="<input type=text name=txtSeptiembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=sin-borde  onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][7]="<input type=text name=txtDiciembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
           }//else	
	}//else
			
 }//for 
 $class_grid->makegrid($li_num,$title,$object,800,'PROGRAMACION DE REPORTE TRIMESTRAL',$ls_nombre); 
 }//if ($ls_opcion=="M")
  	
  if ($ls_opcion=="N")
  {
    $li_rows=$_POST["fila"];
    $la_empresa =  $_SESSION["la_empresa"];
    $ls_codemp  =  $la_empresa["codemp"];
    $ls_codrep=$_POST["cmbrep"];
    $ls_codestpro1=$_POST["codestpro1"];
    $ls_codestpro2=$_POST["codestpro2"];
    $ls_codestpro3=$_POST["codestpro3"];
    $li_num=$_POST["li_totnum"];
	
	for($i=1;$i<=$li_num;$i++)
    {      
			$ls_cuenta=$_POST["txtCuenta".$i];   
			$ls_denominacion=$_POST["txtDenominacion".$i];
			$ls_status=$_POST["status".$i];
			$ld_asignado=$_POST["txtAsignacion".$i];
			$ls_distribuir=$_POST["distribuir".$i];
		    $ls_modrep=$_POST["modrep".$i];
			$ld_marzo=$_POST["txtMarzo".$i];
			$ld_junio=$_POST["txtJunio".$i];
			$ld_septiembre=$_POST["txtSeptiembre".$i];
			$ld_diciembre=$_POST["txtDiciembre".$i];
			
			if( $li_rows==$i)
		    {
			$li_nivel_cta  = $class_progrep->uf_obt_nivel_cta($ls_cuenta,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3);	//Obtiene nivel de la cta 
			$ls_cta_ceros = $class_progrep->uf_cuenta_sin_ceros($ls_cuenta );  //devuelve la cta sin ceros
			$ls_sc_cuenta = $class_progrep->uf_disable_cta_inferior($ls_cta_ceros, $ls_cuenta, $ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3);  
			$total=count($ls_sc_cuenta);
			$ls_distribuir=1;
			$ls_modrep=3;
			$cero=0;
            $ld_cero=number_format($cero,2,",",".");
			$ld_asignado=$ld_asignado;
			$ld_marzo=$ld_cero;			$ld_junio=$ld_cero;
			$ld_septiembre=$ld_cero;	$ld_diciembre=$ld_cero;
			
            $object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
	        $object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=sin-borde readonly>";
			$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][4]="<input type=text name=txtMarzo".$i." onBlur='uf_calcular(this)'  onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][5]="<input type=text name=txtJunio".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][6]="<input type=text name=txtSeptiembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=sin-borde  onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][7]="<input type=text name=txtDiciembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
		
			$cero=0;
            $ld_cero=number_format($cero,2,",",".");		
			$ld_asignado=$ld_cero;
			$ls_status="I";
			$ld_marzo=$ld_cero;			$ld_junio=$ld_cero;
			$ld_septiembre=$ld_cero;	$ld_diciembre=$ld_cero;
				
			for($li=1;$li<$total;$li++)
			{
				$ls_cuenta= $ls_sc_cuenta[$li]; 
				$ls_denominacion=$class_progrep->uf_select_denominacion($ls_cuenta,$ls_codemp,$ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3);
				$i=$i+1;
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=formato-azul readonly>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
				$object[$i][4]="<input type=text name=txtMarzo".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=formato-azul readonly style=text-align:right>";
				$object[$i][5]="<input type=text name=txtJunio".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=formato-azul readonly style=text-align:right>";
				$object[$i][6]="<input type=text name=txtSeptiembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=formato-azul readonly  style=text-align:right>";
				$object[$i][7]="<input type=text name=txtDiciembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=formato-azul readonly style=text-align:right>";
			}//for	
		}//if
		else
		{
			if($ls_status=="I")
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=formato-azul readonly>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
				$object[$i][4]="<input type=text name=txtMarzo".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=formato-azul readonly style=text-align:right>";
				$object[$i][5]="<input type=text name=txtJunio".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=formato-azul readonly style=text-align:right>";
				$object[$i][6]="<input type=text name=txtSeptiembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=formato-azul readonly  style=text-align:right>";
				$object[$i][7]="<input type=text name=txtDiciembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=formato-azul readonly style=text-align:right>";
			}
			else
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=sin-borde readonly>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][4]="<input type=text name=txtMarzo".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][5]="<input type=text name=txtJunio".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][6]="<input type=text name=txtSeptiembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=sin-borde  onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][7]="<input type=text name=txtDiciembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
           }//else	
	  }//else
  }//for    
  $class_grid->makegrid($li_num,$title,$object,800,'PROGRAMACION DE REPORTE TRIMESTRAL',$ls_nombre);
	  
  } //if ($ls_opcion=="N")	
  
  if ($ls_opcion=="A")
  {
   $li_rows=$_POST["fila"];
   $la_empresa =  $_SESSION["la_empresa"];
   $ls_codemp  =  $la_empresa["codemp"];
   $ls_codrep=$_POST["cmbrep"];
   $ls_codestpro1=$_POST["codestpro1"];
   $ls_codestpro2=$_POST["codestpro2"];
   $ls_codestpro3=$_POST["codestpro3"];
   $li_num=$_POST["li_totnum"];
   for($i=1;$i<=$li_num;$i++)
   {    
        $ls_cuenta=$_POST["txtCuenta".$i];   
		$ls_denominacion=$_POST["txtDenominacion".$i];
		$ld_asignado = $_POST["txtAsignacion".$i];
		$ls_status=$_POST["status".$i];
		$ls_distribuir=$_POST["distribuir".$i];
		$ls_modrep=$_POST["modrep".$i];
		$ld_marzo=$_POST["txtMarzo".$i];
		$ld_junio=$_POST["txtJunio".$i];
		$ld_septiembre=$_POST["txtSeptiembre".$i];
		$ld_diciembre=$_POST["txtDiciembre".$i];
		
		if( $li_rows==$i)
		{
		    $ls_referencia=$class_progrep->buscar_referencia($ls_cuenta,$ls_codemp,$ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3);
		    $li_nivel_cta  = $class_progrep->uf_obt_nivel_cta($ls_cuenta,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3);	//Obtiene nivel de la cta 
		    $ls_cta_ceros = $class_progrep->uf_cuenta_sin_ceros($ls_cuenta );  //devuelve la cta sin ceros
			$ls_sc_cuenta = $class_progrep->uf_disable_cta_inferior($ls_cta_ceros, $ls_cuenta, $ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3);  
			$total=count($ls_sc_cuenta);
			$ls_distribuir=2;	
			$ls_modrep=3;
			$ld_asignado=str_replace('.','',$ld_asignado);
			$ld_asignado=str_replace(',','.',$ld_asignado);
			
			$ld_div_asig = round(($ld_asignado/4),2);
			$ld_suma_dic=($ld_div_asig*4);
			$ld_m12=($ld_suma_dic-$ld_asignado);
			if($ld_m12>=0)
			{
			   $ld_diciembre=$ld_div_asig-$ld_m12;
			}
			else
			{
			   $ld_diciembre=$ld_div_asig+$ld_m12;
			}
			$ld_total=($ld_div_asig*3)+$ld_diciembre;
			$ld_resto=round(($ld_asignado-$ld_total),2);
			$ld_diciembre=$ld_diciembre+$ld_resto;
			$ld_div_asig=number_format($ld_div_asig,2,",",".");
			$ld_diciembre=number_format($ld_diciembre,2,",",".");
			$asig=number_format($ld_asignado,2,",",".");
			$ld_asignado=$asig;
			
			$ld_marzo=$ld_div_asig;
			$ld_junio=$ld_div_asig;
			$ld_septiembre=$ld_div_asig;
			$ld_diciembre=$ld_diciembre;
			
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
		    $object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=sin-borde readonly>";
			$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][4]="<input type=text name=txtMarzo".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][5]="<input type=text name=txtJunio".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][6]="<input type=text name=txtSeptiembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=sin-borde  onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][7]="<input type=text name=txtDiciembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
            
			$cero=0;
            $ld_cero=number_format($cero,2,",",".");		
			$ld_asignado=$ld_cero;
			$ls_status="I";
			$ld_marzo=$ld_cero;
			$ld_junio=$ld_cero;
			$ld_septiembre=$ld_cero;
			$ld_diciembre=$ld_cero;
				
			for($li=1;$li<$total;$li++)
			{
				$ls_cuenta= $ls_sc_cuenta[$li]; 
				$ls_denominacion=$class_progrep->uf_select_denominacion($ls_cuenta,$ls_codemp,$ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3);
				$i=$i+1;
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=formato-azul readonly>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
				$object[$i][4]="<input type=text name=txtMarzo".$i." onBlur='uf_calcular(this)'onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=formato-azul readonly style=text-align:right>";
				$object[$i][5]="<input type=text name=txtJunio".$i." onBlur='uf_calcular(this)'onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=formato-azul readonly style=text-align:right>";
				$object[$i][6]="<input type=text name=txtSeptiembre".$i." onBlur='uf_calcular(this)'onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=formato-azul readonly  style=text-align:right>";
				$object[$i][7]="<input type=text name=txtDiciembre".$i." onBlur='uf_calcular(this)'onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=formato-azul readonly style=text-align:right>";
			}//for	
		}//if
		else
		{
			if($ls_status=="I")
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=formato-azul readonly>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
				$object[$i][4]="<input type=text name=txtMarzo".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=formato-azul readonly style=text-align:right>";
				$object[$i][5]="<input type=text name=txtJunio".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=formato-azul readonly style=text-align:right>";
				$object[$i][6]="<input type=text name=txtSeptiembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=formato-azul readonly  style=text-align:right>";
				$object[$i][7]="<input type=text name=txtDiciembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=formato-azul readonly style=text-align:right>";
			}
			else
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=sin-borde readonly>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][4]="<input type=text name=txtMarzo".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][5]="<input type=text name=txtJunio".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][6]="<input type=text name=txtSeptiembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=sin-borde  onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][7]="<input type=text name=txtDiciembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
       }//else	
	}//else
 }//for 
    $class_grid->makegrid($li_num,$title,$object,800,'PROGRAMACION DE REPORTE TRIMESTRAL',$ls_nombre);   
 }//fin de automatico
}//DISTRIBUIR

if ($ls_operacion=="ELIMINAR")
{
   $li_rows=$_POST["fila"];
   $la_empresa =  $_SESSION["la_empresa"];
   $ls_codemp  =  $la_empresa["codemp"];
   $ls_codrep=$_POST["cmbrep"];
   $ls_codestpro1=$_POST["codestpro1"];
   $ls_codestpro2=$_POST["codestpro"];
   $ls_codestpro3=$_POST["codestpro3"];
   $li_num=$_POST["li_totnum"];
   for($i=1;$i<=$li_num;$i++)
   {    
        $ls_cuenta=$_POST["txtCuenta".$i];   
		$ls_denominacion=$_POST["txtDenominacion".$i];
		$ld_asignado = $_POST["txtAsignacion".$i];
		$ls_status=$_POST["status".$i];
		$ls_distribuir=$_POST["distribuir".$i];
		$ls_modrep=$_POST["modrep".$i];
		$ld_marzo=$_POST["txtMarzo".$i];
		$ld_junio=$_POST["txtJunio".$i];
		$ld_septiembre=$_POST["txtSeptiembre".$i];
		$ld_diciembre=$_POST["txtDiciembre".$i];
	
		if($li_rows==$i)
		{	
		  if ($ld_asignado<>0)
		  {
		     $msg->message(" La Cuenta ".$ls_cuenta." Tiene Monto Asignado de ".$ld_asignado." No se puede eliminar.");  
		  }
		  else
		  {
		     $li_nivel_cta  = $class_progrep->uf_obt_nivel_cta($ls_cuenta,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3);	//Obtiene nivel de la cta 
			 $ls_cta_ceros = $class_progrep->uf_cuenta_sin_ceros($ls_cuenta );  //devuelve la cta sin ceros
			 $ls_sc_cuenta = $class_progrep->uf_disable_cta_inferior($ls_cta_ceros, $ls_cuenta, $ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3);  
			 $total=count($ls_sc_cuenta);
		     for($li=1;$li<=$total;$li++)
			 {
	            $ls_cuenta=$ls_sc_cuenta[$li];		     
                $lb_valido=$class_progrep->uf_spg_delete_cuenta($ls_cuenta,$ls_codemp,$ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3);		  
			    $i=$i+1;
			}//for
		  }//else
        }//if
		else
		{
		if ($ls_status=="I")
		{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=formato-azul readonly>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
				$object[$i][4]="<input type=text name=txtMarzo".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=formato-azul readonly style=text-align:right>";
				$object[$i][5]="<input type=text name=txtJunio".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=formato-azul readonly style=text-align:right>";
				$object[$i][6]="<input type=text name=txtSeptiembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=formato-azul readonly  style=text-align:right>";
				$object[$i][7]="<input type=text name=txtDiciembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=formato-azul readonly style=text-align:right>";
		}
		else
		{		
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=sin-borde readonly>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
				$object[$i][4]="<input type=text name=txtMarzo".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=sin-borde style=text-align:right>";
				$object[$i][5]="<input type=text name=txtJunio".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=sin-borde style=text-align:right>";
				$object[$i][6]="<input type=text name=txtSeptiembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=sin-borde  style=text-align:right>";
				$object[$i][7]="<input type=text name=txtDiciembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=sin-borde style=text-align:right>";
		}//else
	}//else
   }//for 
   $class_grid->makegrid($li_num,$title,$object,800,'PROGRAMACION DE REPORTE TRIMESTRAL',$ls_nombre);   

}//fin de $ls_operacion=="ELIMINAR"


if ($ls_operacion=="CALCULAR" )
{
   $li_rows=$_POST["fila"];
   $li_num=$_POST["li_totnum"];
   for($i=1;$i<=$li_num;$i++)
   {     
     $ls_cuenta=$_POST["txtCuenta".$i];   
	 $ls_denominacion=$_POST["txtDenominacion".$i];
	 $ld_asignado=$_POST["txtAsignacion".$i];
	 $ls_distribuir=$_POST["distribuir".$i];
	 $ls_modrep=$_POST["modrep".$i];
	 $ls_status=$_POST["status".$i];
	 $ld_marzo=$_POST["txtMarzo".$i];
	 $ld_junio=$_POST["txtJunio".$i];
	 $ld_septiembre=$_POST["txtSeptiembre".$i];
	 $ld_diciembre=$_POST["txtDiciembre".$i];
	
	if($li_rows==$i)
	{
		    $ld_marzo=str_replace('.','',$ld_marzo);		    $ld_marzo=str_replace(',','.',$ld_marzo);
			$ld_junio=str_replace('.','',$ld_junio);		    $ld_junio=str_replace(',','.',$ld_junio);
			$ld_septiembre=str_replace('.','',$ld_septiembre);  $ld_septiembre=str_replace(',','.',$ld_septiembre);
			$ld_diciembre=str_replace('.','',$ld_diciembre);    $ld_diciembre=str_replace(',','.',$ld_diciembre);
			
			$total = $ld_marzo + $ld_junio + $ld_septiembre + $ld_diciembre;
		    
			$ld_total=number_format($total,2,",",".");
			$marzo=number_format($ld_marzo,2,",",".");
		    $ld_marzo=$marzo;
			$junio=number_format($ld_junio,2,",",".");
		    $ld_junio=$junio;
			$septiembre=number_format($ld_septiembre,2,",",".");
		    $ld_septiembre=$septiembre;
			$diciembre=number_format($ld_diciembre,2,",",".");
		    $ld_diciembre=$diciembre;
			if($ld_total>$ld_asignado)
		    {
		      $msg->message("El Total es mayor al monto asignado. Por favor revise los montos ");  
		    }
			
	        if($ls_status=="I")
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=formato-azul readonly>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
				$object[$i][4]="<input type=text name=txtMarzo".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=formato-azul readonly style=text-align:right>";
				$object[$i][5]="<input type=text name=txtJunio".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=formato-azul readonly style=text-align:right>";
				$object[$i][6]="<input type=text name=txtSeptiembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=formato-azul readonly  style=text-align:right>";
				$object[$i][7]="<input type=text name=txtDiciembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=formato-azul readonly style=text-align:right>";
			}
			else
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=sin-borde readonly>";
				$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][4]="<input type=text name=txtMarzo".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][5]="<input type=text name=txtJunio".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][6]="<input type=text name=txtSeptiembre".$i." onBlur='uf_calcular(this)' onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=sin-borde  onFocus= uf_fila(".$i.") style=text-align:right>";
				$object[$i][7]="<input type=text name=txtDiciembre".$i."  onBlur='uf_calcular(this)'onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
           }//else
	}//if
	else
	{
	    if($ls_status=="I")
		{
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=formato-azul readonly>";
			$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
			$object[$i][4]="<input type=text name=txtMarzo".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=formato-azul readonly style=text-align:right>";
			$object[$i][5]="<input type=text name=txtJunio".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=formato-azul readonly style=text-align:right>";
			$object[$i][6]="<input type=text name=txtSeptiembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=formato-azul readonly  style=text-align:right>";
			$object[$i][7]="<input type=text name=txtDiciembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=formato-azul readonly style=text-align:right>";
		}
		else
		{
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=sin-borde readonly>";
			$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][4]="<input type=text name=txtMarzo".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][5]="<input type=text name=txtJunio".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][6]="<input type=text name=txtSeptiembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=sin-borde  onFocus= uf_fila(".$i.") style=text-align:right>";
			$object[$i][7]="<input type=text name=txtDiciembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=sin-borde onFocus= uf_fila(".$i.") style=text-align:right>";
	   }//else
	}//else
  }//for
  $class_grid->makegrid($li_num,$title,$object,800,'PROGRAMACION DE REPORTE TRIMESTRAL',$ls_nombre); 
}//($ls_operacion=="CALCULAR" )         

if ($ls_operacion=="GUARDAR" )
{
   $cont_insert=0;
   $la_empresa =  $_SESSION["la_empresa"];
   $ls_codemp  =  $la_empresa["codemp"];
   $ls_codestpro1=$_POST["codestpro1"];
   $ls_codestpro2=$_POST["codestpro2"];
   $ls_codestpro3=$_POST["codestpro3"];
   $li_num=$_POST["li_totnum"];
  
   for($i=1;$i<=$li_num;$i++)
   { 
        $ls_cuenta=$_POST["txtCuenta".$i];   
	    $ls_denominacion=$_POST["txtDenominacion".$i];
	    $ld_asignado=trim($_POST["txtAsignacion".$i]);
		$ls_status=$_POST["status".$i];
		$ls_distribuir=$_POST["distribuir".$i];
		$ls_modrep=$_POST["modrep".$i];
		$cero=0;
        $ld_cero=number_format($cero,2,",",".");		
	    $ld_enero=$ld_cero;
	    $ld_febrero=$ld_cero;
	    $ld_marzo=$_POST["txtMarzo".$i];
	    $ld_abril=$ld_cero;
	    $ld_mayo=$ld_cero;
	    $ld_junio=$_POST["txtJunio".$i];
	    $ld_julio=$ld_cero;
	    $ld_agosto=$ld_cero;
	    $ld_septiembre=$_POST["txtSeptiembre".$i];
	    $ld_octubre=$ld_cero;
	    $ld_noviembre=$ld_cero;
	    $ld_diciembre=$_POST["txtDiciembre".$i];
		 
		if ($ls_status=="I")
		{
		    $object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
		    $object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=formato-azul readonly>";
			$object[$i][3]="<input type=text name=txtAsignacion".$i."  onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
			$object[$i][4]="<input type=text name=txtMarzo".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=formato-azul readonly style=text-align:right>";
			$object[$i][5]="<input type=text name=txtJunio".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=formato-azul readonly style=text-align:right>";
			$object[$i][6]="<input type=text name=txtSeptiembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=formato-azul readonly  style=text-align:right>";
			$object[$i][7]="<input type=text name=txtDiciembre".$i."   onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=formato-azul readonly style=text-align:right>";
		}
		else
		{		
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde readonly ><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=50 class=sin-borde readonly>";
			$object[$i][3]="<input type=text name=txtAsignacion".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_asignado  onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
			$object[$i][4]="<input type=text name=txtMarzo".$i." onBlur='uf_calcular(this)'  onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_marzo class=sin-borde style=text-align:right>";
			$object[$i][5]="<input type=text name=txtJunio".$i." onBlur='uf_calcular(this)'  onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_junio class=sin-borde style=text-align:right>";
			$object[$i][6]="<input type=text name=txtSeptiembre".$i." onBlur='uf_calcular(this)'  onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_septiembre class=sin-borde  style=text-align:right>";
			$object[$i][7]="<input type=text name=txtDiciembre".$i." onBlur='uf_calcular(this)'  onKeyPress=return(ue_formatonumero(this,'.',',',event)) value=$ld_diciembre class=sin-borde style=text-align:right>";
        }
		   
            $ld_asignado=str_replace('.','',$ld_asignado);    $ld_asignado=str_replace(',','.',$ld_asignado);  
		    $ld_enero=str_replace('.','',$ld_enero);	      $ld_enero=str_replace(',','.',$ld_enero);
		    $ld_febrero=str_replace('.','',$ld_febrero);	  $ld_febrero=str_replace(',','.',$ld_febrero);
		    $ld_marzo=str_replace('.','',$ld_marzo);		  $ld_marzo=str_replace(',','.',$ld_marzo);
			$ld_abril=str_replace('.','',$ld_abril);		  $ld_abril=str_replace(',','.',$ld_abril);
			$ld_mayo=str_replace('.','',$ld_mayo);		      $ld_mayo=str_replace(',','.',$ld_mayo);
			$ld_junio=str_replace('.','',$ld_junio);		  $ld_junio=str_replace(',','.',$ld_junio);
			$ld_julio=str_replace('.','',$ld_julio);		  $ld_julio=str_replace(',','.',$ld_julio);
			$ld_agosto=str_replace('.','',$ld_agosto);		   $ld_agosto=str_replace(',','.',$ld_agosto);
			$ld_septiembre=str_replace('.','',$ld_septiembre); $ld_septiembre=str_replace(',','.',$ld_septiembre);
			$ld_octubre=str_replace('.','',$ld_octubre);	   $ld_octubre=str_replace(',','.',$ld_octubre);
			$ld_noviembre=str_replace('.','',$ld_noviembre);   $ld_noviembre=str_replace(',','.',$ld_noviembre);
			$ld_diciembre=str_replace('.','',$ld_diciembre);   $ld_diciembre=str_replace(',','.',$ld_diciembre);
		 
		     $total=$ld_marzo+$ld_junio+$ld_septiembre+$ld_diciembre;
            
			$ld_total=number_format($total,2,",",".");
			
		   if($ld_total>$ld_asignado)
	       {
	         $msg->message("El Total es mayor al monto asignado. Por favor revise los montos ");  
	       }
		   else
		   {
	          $lb_valido=$class_progrep->uf_spg_guardar_progrep($ls_codemp,$ls_status,$ld_asignado,$ls_distribuir,$ls_modrep,$ld_enero,$ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,$ld_julio,$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,$ld_diciembre,$ls_cuenta,$ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3);
		      if ($lb_valido)
	          {
			     $cont_insert=$cont_insert+1;
	          }
		   }  
	 }//for 		
     $class_grid->makegrid($li_num,$title,$object,800,'PROGRAMACION DE REPORTE TRIMESTRAL',$ls_nombre);

	if($cont_insert==$li_num)
	{
          $SQL->begin_transaction();
		    //////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Guardar la programacion de reportes ";
			$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
											$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
											$la_seguridad["ventanas"],$ls_descripcion);
  		 /////////////////////////////////         SEGURIDAD               /////////////////////////////	
			
		 $SQL->commit();
	     $msg->message(" Los Datos fueron guardados con  exito ");
	 }
	 else
	 {
	 	$SQL->rollback();  
		$msg->message(" Error en los datos en el guardar  ");
	 }	  
}//GUARDAR	
?>
    <input name="operacion" type="hidden" id="operacion" value="<?php $_POST["operacion"]?>">
    <input name="li_totnum" type="hidden" id="li_totnum" value="<?php print $li_totnum; ?>">
    <input name="fila" type="hidden" id="fila">
</p>
  <p>&nbsp;</p>
</form>
</body>
<script language="javascript">

function uf_cambio_estpro1()
{
	f=document.form1;
	f.action="sigesp_spg_p_progrep_trim.php";
	//f.operacion.value="est1";
	f.submit();
}

function uf_cambio_estpro2()
{
	f=document.form1;
	f.action="sigesp_spg_p_progrep_trim.php";
	//f.operacion.value="est2";
	f.submit();
}

function uf_cargargrid()
{
	f=document.form1;
	f.operacion.value="CARGAR";
	f.action="sigesp_spg_p_progrep_trim.php";
	f.submit();
}

function ue_recargar()
{
    f=document.form1;
    resp=confirm("Este proceso borrara todas las cuentas y las copiara del plan original(Todas las programatica). ¿ Esta seguro de proceder ?");
	if (resp==true)
   {
		f.operacion.value="RECARGAR";
		f.action="sigesp_spg_p_progrep_trim.php";
		f.submit();
   }
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
		//alert(document.opcion);
		 
		 if  (document.opcion=="M" ) 
		 {
			 f=document.form1;
			 f.operacion.value="DISTRIBUIR";
			 f.action="sigesp_spg_p_progrep_trim.php";
			 f.submit();
		 }
		 
	   if (document.opcion=="A")
	   {
		   f=document.form1;
		   f.operacion.value="DISTRIBUIR";
		   f.action="sigesp_spg_p_progrep_trim.php";
		   f.submit();
	   }
	   
	   if (document.opcion=="N")
	   {
		   f=document.form1;
		   f.operacion.value="DISTRIBUIR";
		   f.action="sigesp_spg_p_progrep_trim.php";
		   f.submit();
	   }
    }
}

 function uf_calcular(obj)
  {  
	f=document.form1;
	ldec_temp1=obj.value;
	if(ldec_temp1=="")
	{
	  obj.value="0,00";
	  obj.focus();
	}
	var ld_asignado;
	li=f.fila.value; 	 

	txta="txtAsignacion"+li;
	ld_asignado=eval("f."+txta+".value");  
	ld_asignado=parseFloat(uf_convertir_monto(ld_asignado));
	
	txtm3="txtMarzo"+li;
	ld_m3=eval("f."+txtm3+".value");    
	ld_m3=parseFloat(uf_convertir_monto(ld_m3));

	txtm6="txtJunio"+li;
	ld_m6=eval("f."+txtm6+".value");
	ld_m6=parseFloat(uf_convertir_monto(ld_m6));

	txtm9="txtSeptiembre"+li;
	ld_m9=eval("f."+txtm9+".value");       
	ld_m9=parseFloat(uf_convertir_monto(ld_m9));

	txtm12="txtDiciembre"+li;
	ld_m12=eval("f."+txtm12+".value");       
	ld_m12=parseFloat(uf_convertir_monto(ld_m12));

	ld_total = parseFloat( ld_m3 +  ld_m6 + ld_m9 + ld_m12);
	ld_total=redondear(ld_total,2);
	if (ld_total>ld_asignado)
	{
	  alert(" La Distribución no cuadra con lo asignado. Por favor revise los montos ");
	  obj.focus();
	}	
	//f.operacion.value="CALCULAR";
	f.action="sigesp_spg_p_progrep_trim.php";
	//f.submit();
  }
function redondear(num, dec)
{ 
	num = parseFloat(num); 
	dec = parseFloat(dec); 
	dec = (!dec ? 2 : dec); 
	return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec); 
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
		f.action="sigesp_spg_p_progrep_trim.php";
		f.submit();
	}	
}

function ue_eliminarcuenta()
{
 f=document.form1;
 resp=confirm("¿Esta seguro de eliminar esta cuenta? ");
 if (resp==true)
 {
   f.operacion.value="ELIMINAR";
   f.action="sigesp_spg_p_progrep_trim.php";
   f.submit();
 }  
}

function EvaluateText(cadena, obj)
{ 
	
    opc = false; 
	
    if (cadena == "%d")  
      if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
      opc = true; 
    if (cadena == "%f")
	{ 
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
    if(opc == false) 
     event.returnValue = false; 
   } 
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
    	pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&tipo=progrep";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
    	pagina="sigesp_cat_public_estpro.php?tipo=progrep";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
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

</script>
</html>
