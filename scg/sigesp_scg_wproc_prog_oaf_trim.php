<?php 
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
    
	$dat=$_SESSION["la_empresa"];
	$ls_empresa=$dat["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SCG";
	$ls_ventanas="sigesp_scg_wproc_prog_oaf_trim.php";

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
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<title>Origen y Aplicacion de Fondos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
.Estilo1 {font-size: 15px}
.Estilo2 {color: #6699CC}
-->
</style></head>

<body>
<table width="780" border="0" align="left" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="7" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="780" height="40"></td>
  </tr>
   <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo2">Sistema de Contabilidad Patrimonial</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" align="center" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="toolbar"><div align="left"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></div></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
  <table width="780" height="180" border="0" align="left">
  <tr>
    <td>
      <?php
require_once("../shared/class_folder/sigesp_include.php");
$in = new sigesp_include();
$con= $in-> uf_conectar ();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con); //Instanciando  la clase sql
require_once("sigesp_scg_procesos.php");
$iscg_procesos= new sigesp_scg_procesos();
$ds_proaf=new class_datastore(); //Instanciando la clase datastore
require_once("../shared/class_folder/class_mensajes.php");
$msg=new class_mensajes();
require_once("../shared/class_folder/class_funciones.php");
$fun=new class_funciones();
require_once("class_funciones_scg.php");
$funciones_scg=new class_funciones_scg();
if(array_key_exists("operacion",$_POST))
{
  $ls_operacion=$_POST["operacion"];
}
else
{
  $ls_operacion="";
}

if(array_key_exists("radiobutton",$_POST))
{
  $ls_opcion=$_POST["radiobutton"];
}
else
{
  $ls_opcion="";
}

if (array_key_exists("txtAsignado",$_POST))
{
  $ld_asignado=$_POST["txtAsignado"];
}
else
{
  $ld_asignado="";
}

if (array_key_exists("txtCuenta",$_POST))
{
  $ls_cuenta=$_POST["txtCuenta"];
}
else
{
  $ls_cuenta="";
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
      <table width="644" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td colspan="5" ><div align="center" >Programaci&oacute;n de Origen y Aplicacion de Fondos Trimestral </div></td>
        </tr>
        <tr class="contorno">
          <td colspan="5"></td>
        </tr>
        <tr class="formato-blanco">
          <td>&nbsp;</td>
          <td class="fd-blanco">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td class="Estilo3">&nbsp;</td>
        </tr>
        <tr class="formato-blanco">
          <td width="86"><div align="right" class="fd-blanco">Distribuci&oacute;n</div></td>
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
          <td width="127" class="fd-blanco">
            <input name="radiobutton" type="radio" value="N" <?php print $ls_ninguno ?>>
        Ninguno</td>
          <td width="116"><span class="Estilo3">
            <input name="radiobutton" type="radio" value="A" <?php print $ls_auto ?>>
          </span> <span class="fd-blanco">Automatico</span></td>
          <td width="104"><span class="Estilo3">
            <input name="radiobutton" type="radio" value="M" <?php print $ls_manual ?>>
          </span> <span class="fd-blanco">Manual </span></td>
          <td width="209" class="Estilo3"><span class="Estilo1"> </span> <span class="Estilo1">
            <input name="botDistribuir" type="button" class="boton" id="botDistribuir4" onClick="ue_distribuir()" value="Totalizar">
            <input name="botRecargar" type="button" class="boton" id="botRecargar5"  onClick="ue_recargar()" value="Recargar">
          </span></td>
        </tr>
        <tr class="contorno">
          <td colspan="5"></td>
        </tr>
        <tr class="formato-blanco">
          <td><span class="Estilo1"></span></td>
          <td colspan="4"><span class="Estilo1"><span class="Estilo3"></span></span></td>
        </tr>
        <tr class="formato-blanco">
          <td class="fd-blanco Estilo1">&nbsp;</td>
          <td class="fd-blanco Estilo1">&nbsp;</td>
          <td class="fd-blanco Estilo1">&nbsp;</td>
          <td colspan="2" class="fd-blanco Estilo1">&nbsp;</td>
        </tr>
        <tr>
          <td class="fd-blanco"><div align="right"> Asignado</div></td>
          <td class="fd-blanco"> <span class="cd-titulo">
            <div align="left">
              <input name="txtAsignado" type="text" class="fd-blanco" id="txtAsignado2" onBlur="uf_format(this)" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $ld_asignado ; ?>" size="23">
            </div>
          </span></td>
          <td class="fd-blanco"><div align="right">Cuenta</div></td>
          <td colspan="2" class="fd-blanco">
            <div align="left">
              <input name="txtCuenta" type="text" id="txtCuenta" value="<?php print $ls_cuenta ?>" readonly>
          </div></td>
        </tr>
        <tr class="formato-blanco">
          <td class="fd-blanco Estilo1"><input name="operacion" type="hidden" id="operacion" value="<?php $_POST["operacion"]?>"></td>
          <td class="fd-blanco Estilo1">&nbsp;</td>
          <td class="fd-blanco Estilo1">&nbsp;</td>
          <td colspan="2" class="fd-blanco Estilo1">&nbsp;</td>
        </tr>
    </table></td></tr>
</table>
  <p>&nbsp;  </p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>
    <input name="fila" type="hidden" id="fila">
    <input name="numrow" type="hidden" id="numrow">
    <input name="status" type="hidden" id="status">
</p>
  <tr class="fd-blanco">        
  <tr class="fd-blanco">          
  <tr class="fd-blanco">  
  <table width="1209" border="0" align="left" cellpadding="1" cellspacing="1" class="fondo-tabla">
    <tr class="titulo-ventana">
      <td width="110"><div align="center">Cuenta</div></td>
      <td width="169">Denominacion</td>
      <td width="153"><div align="center">Asignado</div></td>
      <td width="202"><div align="center">Trimestre(1)</div></td>
      <td width="156"><div align="center">Trimestre(2)</div></td>
      <td width="156"><div align="center">Trimestre(3)</div></td>
      <td width="161"><div align="center">Trimestre(4)</div></td>
    </tr>
    <?php
if($ls_operacion=="")
{
		$fun->uf_limpiar_sesion();

   	/*$arr=array_keys($_SESSION);	
	$li_count=count($arr);
	for($i=0;$i<$li_count;$i++)
	{
		$col=$arr[$i];
		if(($col!="ls_hostname")&&($col!="ls_login")&&($col!="ls_password")&&($col!="ls_database")&&($col!="ls_gestor")&&($col!="con")&&($col!="gestor")&&($col!="la_empresa")&&($col!="la_logusr")
		&&($col!="la_ususeg")&&($col!="la_sistema")&&($col!="ls_port")&&($col!="ls_width")&&($col!="ls_height")&&($col!="ls_logo")&&($col!="la_apeusu")&&($col!="la_nomusu")&&($col!="la_cedusu"))
		{
			unset($_SESSION["$col"]);			
		}
	}*/

   $la_empresa =  $_SESSION["la_empresa"];
   $ls_codemp  =  $la_empresa["codemp"];
   $li_rtn=0 ;
   $lb_valido=true;
   $ls_cod_report="0409"; // OAF Instr 4
   $_SESSION["cod_report"]=$ls_cod_report;
   $ls_cod_report=$_SESSION["cod_report"];
   
		 $lb_valido=$iscg_procesos->uf_select_reporte($ls_codemp,$ai_cuantos,$ls_cod_report);
		 if(($lb_valido)&&($ai_cuantos<=0))
		 {
			if ($ls_cod_report=="0409")
			{
				$lb_valido = $iscg_procesos->uf_cargar_origen_y_aplic_fondos_txt($ls_codemp);
				if($lb_valido)
				{
	                 $msg->message("Los datos fueron cargados");
				     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
					 $SQL->num_rows($rs_cta);
					 ///$msg->message($SQL->num_rows($rs_cta));
					 $data=$SQL->obtener_datos($rs_cta);
					 $ds_proaf->data=$data;
					 $li_num=$ds_proaf->getRowCount("cod_report");
					 for($i=1;$i<=$li_num;$i++)
					 { 
								$ls_status=$data["status"][$i];
								if($ls_status=="C")
								{
									?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_proaf->getValue("asignado",$i);?>','<?php print $ds_proaf->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_proaf->getValue("sc_cuenta",$i);?></a></td>
      <?php
								}
								elseif($ls_status=="S")
								{
								   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_proaf->getValue("asignado",$i);?>','<?php print $ds_proaf->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_proaf->getValue("sc_cuenta",$i);?></a></td>
      <?php
								}
								elseif($ls_status=="I")
								{
								   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_proaf->getValue("sc_cuenta",$i)?></td>
      <?php
								}
								 print"<td width=250>".$ds_proaf->getValue("denominacion",$i)."</td>";
								 print"<td>".number_format($ds_proaf->getValue("asignado",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_proaf->getValue("marzo",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_proaf->getValue("junio",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_proaf->getValue("septiembre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_proaf->getValue("diciembre",$i),2,",",".")."</td>";
								 print"</tr>";
							 }//for
		         }//if($lb_valido)
				else
				{
				  $msg->message("Error al cargar los datos");
				}
			}//if($ls_cod_report==0409")
		}//($li_rtn>0)
			
	    elseif(($lb_valido)&&($ai_cuantos>0))
	    {
  
	     $rs_cta=$iscg_procesos->uf_select_datastore_trim( $ls_codemp, $ls_cod_report );
		 $data=$SQL->obtener_datos($rs_cta);
		 $ds_proaf=new class_datastore();
		 $ds_proaf->data=$data;
		 $li_num=$ds_proaf->getRowCount("cod_report");
		 for($i=1;$i<=$li_num;$i++)
		 { 
			$ls_status=$ds_proaf->getValue("status",$i);
			if($ls_status=="C")
			{
				?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_proaf->getValue("asignado",$i);?>','<?php print $ds_proaf->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_proaf->getValue("sc_cuenta",$i);?></a></td>
      <?php
		    }
		    elseif($ls_status=="S")
		    {
			   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_proaf->getValue("asignado",$i);?>','<?php print $ds_proaf->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_proaf->getValue("sc_cuenta",$i);?></a></td>
      <?php
			}
			elseif($ls_status=="I")
			{
			   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_proaf->getValue("sc_cuenta",$i)?></td>
      <?php
			}
			 print"<td width=250>".$ds_proaf->getValue("denominacion",$i)."</td>";
			 print"<td>".number_format($ds_proaf->getValue("asignado",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_proaf->getValue("marzo",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_proaf->getValue("junio",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_proaf->getValue("septiembre",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_proaf->getValue("diciembre",$i),2,",",".")."</td>";
		     print"</tr>";
		 }//for
	  }//if ($li_rtn>0)			
  }//operacion==""
  
  if ($ls_operacion=="DISTRIBUIR")
  {
	 $li_indice=1;
	 $la_empresa=$_SESSION["la_empresa"];
	 $ls_formcont=$la_empresa["formcont"];
	 $ls_codemp  =  $la_empresa["codemp"];
	 $ls_cod_report = $_SESSION["cod_report"];
	 $_SESSION["ld_asignado"]=$ld_asignado;
	 $ld_asignado=$_SESSION["ld_asignado"];
   	 $li_lenCta  = strlen(trim($ls_cuenta)) ;
	 	    	   
   	 //COLOCA FORMATO A LA CUENTA  
	 $li_pos      = $fun->uf_posocurrencia( $ls_formcont, "-", $li_indice );
	 $ls_sub      = substr($ls_cuenta,0, $li_pos );
	 $ls_new_cad  = $ls_sub . "-" . substr($ls_cuenta,$li_pos, $li_lenCta ); 
	 $li_max_nivel= 1;
	
   	for($i=1;$i<=$li_pos;$i++)
	{
   	 	 $li_indice = $li_indice + 1;
   	 	 $li_lenCta = strlen(trim($ls_new_cad));
		 $li_pos    = $fun->uf_posocurrencia( $ls_formcont, "-", $li_indice );
		    	 	    	 
	   	 if ($li_pos > 0)
	   	 {
	   	  	 $ls_sub     = substr($ls_new_cad, 0, $li_pos );
			 $len=$li_lenCta - $li_pos;
	   	  	 $ls_new_cad  = $ls_sub . "-" . substr($ls_new_cad,$li_pos,$len );
			 $li_max_nivel= $li_max_nivel + 1;
	   	 }
   	 }//for
    $li_nivel_cta  = $iscg_procesos->uf_obt_nivel_cta($ls_cuenta);	      //Obtiene nivel de la cta 
    $ls_cta_ceros   = $iscg_procesos->uf_cuenta_sin_ceros($ls_cuenta );  //devuelve la cta sin ceros
    $ls_sc_cuenta = $iscg_procesos->uf_disable_cta_inferior( $ls_cta_ceros, $ls_cuenta, $ls_cod_report);
	
	$ls_opcion=$_POST["radiobutton"];
	if ($ls_opcion=="N")
	{
	    $ds_proaf->data=$_SESSION["objact"];
		$i=$_POST["fila"];
		$ld_asignado=str_replace('.','',$ld_asignado);
		$ld_asignado=str_replace(',','.',$ld_asignado);
		$ds_proaf->updateRow("asignado",$ld_asignado,$i);
		$total=count($ls_sc_cuenta);
	    $ls_modrep="3"; //Modalidad Trimestral
		$ls_distribuir="1";
		
		for($li=1;$li<=$total;$li++)
		{
		  $pos=$ds_proaf->find("sc_cuenta",$ls_sc_cuenta[$li]);
		  $ds_proaf->updateRow("status","I",$pos);
		} 
		$ds_proaf->updateRow("modrep",$ls_modrep,$i);
		$ds_proaf->updateRow("distribuir",$ls_distribuir,$i);
	}
	if ($ls_opcion=="M")
	{
		$ds_proaf->data=$_SESSION["objact"];
		$i=$_POST["fila"];
		$total=count($ls_sc_cuenta);
		for($li=1;$li<=$total;$li++)
		{
		  $pos=$ds_proaf->find("sc_cuenta",$ls_sc_cuenta[$li]);
		  $ds_proaf->updateRow("status","I",$pos);
		} 
	}//fin de opcion ==M
	
	if($ls_opcion=="A")
	{
	    $ds_proaf->data=$_SESSION["objact"];
		$ld_asignado=str_replace('.','',$ld_asignado);
		$ld_asignado=str_replace(',','.',$ld_asignado);
		$ld_div_asig = ($ld_asignado/4);
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
		
		$i=$_POST["fila"];
		$li_num=$ds_proaf->getRowCount("cod_report");
		$ls_modrep="3"; //Modalidad Trimestral
		$ls_distribuir="3";
		$total=count($ls_sc_cuenta);
		for($li=1;$li<=$total;$li++)
		{
		  $pos=$ds_proaf->find("sc_cuenta",$ls_sc_cuenta[$li]);
		  $ds_proaf->updateRow("status","I",$pos);
		} 
		
		$ds_proaf->updateRow("asignado",$ld_asignado,$i);
		$ds_proaf->updateRow("marzo",$ld_div_asig,$i);
		$ds_proaf->updateRow("junio",$ld_div_asig,$i);
		$ds_proaf->updateRow("septiembre",$ld_div_asig,$i);
		$ds_proaf->updateRow("diciembre",$ld_diciembre,$i);
		$ds_proaf->updateRow("modrep",$ls_modrep,$i);		
		$ds_proaf->updateRow("distribuir",$ls_distribuir,$i);

	}//fin del else $ls_opcion="A"

    $li_num=$ds_proaf->getRowCount("cod_report");
    for($i=1;$i<=$li_num;$i++)
	{ 
			$ls_status=$ds_proaf->getValue("status",$i);
			if($ls_status=="C")
			{
				?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_proaf->getValue("asignado",$i);?>','<?php print $ds_proaf->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_proaf->getValue("sc_cuenta",$i);?></a></td>
      <?php
		    }
		    elseif($ls_status=="S")
		    {
			   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_proaf->getValue("asignado",$i);?>','<?php print $ds_proaf->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_proaf->getValue("sc_cuenta",$i);?></a></td>
      <?php
			}
			elseif($ls_status=="I")
			{
			   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_proaf->getValue("sc_cuenta",$i)?></td>
      <?php
			}
			 print"<td width=250>".$ds_proaf->getValue("denominacion",$i)."</td>";
			 print"<td>".number_format($ds_proaf->getValue("asignado",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_proaf->getValue("marzo",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_proaf->getValue("junio",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_proaf->getValue("septiembre",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_proaf->getValue("diciembre",$i),2,",",".")."</td>";
		     print"</tr>";
		 }//for
 }//if ($ls_operacion=="AUTODISTRIBUIR")

if($ls_operacion=="GUARDAR")
{	
    $ld_asignado="";
	$ls_cuenta="";
    $ds_proaf->data=$_SESSION["objact"];
    $la_empresa=$_SESSION["la_empresa"];
	$ls_codemp  =  $la_empresa["codemp"];
 	$li_rows=0; $li_count=-2; $li_acum_insert=0; $li_acum_update=0; $li_rtn_ins=0; $li_rtn_upd=0;
 	$ls_cod_report=""; $ls_sc_cuenta=""; 
    $li_num=$ds_proaf->getRowCount("cod_report");    
	for ($i=1; $i<=$li_num; $i++)
	{
		$la_datos[0] = $ds_proaf->getValue("cod_report",$i);  
		$la_datos[1] = $ds_proaf->getValue("sc_cuenta",$i); 
		$la_datos[2] = $ds_proaf->getValue("denominacion",$i); 
		$la_datos[3] = $ds_proaf->getValue("status",$i);  
		$la_datos[4] = $ds_proaf->getValue("asignado",$i);  
		$la_datos[5] = $ds_proaf->getValue("distribuir",$i); 
		//meses
		$la_datos[6]  = 0;
		$la_datos[7]  = 0;
		$la_datos[8]  = $ds_proaf->getValue("marzo",$i);
		$la_datos[9]  = 0;
		$la_datos[10] = 0;
		$la_datos[11] = $ds_proaf->getValue("junio",$i);
		$la_datos[12] = 0;
		$la_datos[13] = 0;
		$la_datos[14] = $ds_proaf->getValue("septiembre",$i);
		$la_datos[15] = 0;
		$la_datos[16] = 0;
		$la_datos[17] = $ds_proaf->getValue("diciembre",$i);
		$la_datos[18] = $ds_proaf->getValue("nivel",$i);
		$la_datos[19] = $ds_proaf->getValue("referencia",$i);
		$la_datos[20] = $ds_proaf->getValue("no_fila",$i);
		$la_datos[21] = $ds_proaf->getValue("tipo",$i);
		$la_datos[22] = $ds_proaf->getValue("cta_res",$i);
		$la_datos[23] = $ds_proaf->getValue("modrep",$i);
		$ls_cod_report = $la_datos[0];
		$ls_sc_cuenta  = $la_datos[1];
		
		$li_count = $iscg_procesos->uf_count_scg_plantillacuentareporte( $ls_codemp, $ls_cod_report, $ls_sc_cuenta );
		
		if ($li_count== 0) 
		{
			$li_rtn_ins = $iscg_procesos->uf_insert_scg_plantillacuentareporte( $la_datos );
			if ($li_rtn_ins == 0)
			{
				$msg->message("Error al insertar"); 
			}
			else
			{
				$li_acum_insert = $li_acum_insert + $li_rtn_ins;
			}
		}	
		if ($li_count==1)
		{
			$lb_valido = $iscg_procesos->uf_update_scg_plantillacuentareporte($la_datos);
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/// PARA LA CONVERSIÓN MONETARIA
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($lb_valido)
		{
			$lb_valido = $funciones_scg->uf_convertir_scgpcreporte($la_datos,$la_seguridad);
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}//for
	//si ejecuto todos los registros realiza las transacciones de commit o rollback
	if($lb_valido)
	{   
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="UPDATE";
		$ls_descripcion =" Actualizo la Programacion de Reportes ";
		$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
										$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
										$la_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	    $lb_valido = $iscg_procesos->uf_sql_transaction(true);	
		if ($lb_valido)
		{
			$msg->message("Los datos han sido guardados con exito......."); 
		}
		else
		{
			$msg->message("Error en la transacion"); 	
		}
	}
	else
	{
		$lb_valido = $iscg_procesos->uf_sql_transaction(false);
		$msg->message("Error en la transacion"); 	
	} 
	
	$la_empresa =  $_SESSION["la_empresa"];
    $ls_codemp  =  $la_empresa["codemp"];
    $ls_cod_report=$_SESSION["cod_report"];
	$rs_cta=$iscg_procesos->uf_select_datastore_trim( $ls_codemp, $ls_cod_report );
    $data=$SQL->obtener_datos($rs_cta);
    $ds_proaf->data=$data;
    $li_num=$ds_proaf->getRowCount("cod_report");
    for($i=1;$i<=$li_num;$i++)
	{ 
			$ls_status=$ds_proaf->getValue("status",$i);
			if($ls_status=="C")
			{
				?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_proaf->getValue("asignado",$i);?>','<?php print $ds_proaf->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_proaf->getValue("sc_cuenta",$i);?></a></td>
      <?php
		    }
		    elseif($ls_status=="S")
		    {
			   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_proaf->getValue("asignado",$i);?>','<?php print $ds_proaf->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_proaf->getValue("sc_cuenta",$i);?></a></td>
      <?php
			}
			elseif($ls_status=="I")
			{
			   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_proaf->getValue("sc_cuenta",$i)?></td>
      <?php
			}
			 print"<td>".$ds_proaf->getValue("denominacion",$i)."</td>";
			 print"<td>".number_format($ds_proaf->getValue("asignado",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_proaf->getValue("marzo",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_proaf->getValue("junio",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_proaf->getValue("septiembre",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_proaf->getValue("diciembre",$i),2,",",".")."</td>";
		     print"</tr>";
		 }//for
}	//fin de ue_guardar 
if($ls_operacion=="RECARGAR")
{
   $la_empresa =  $_SESSION["la_empresa"];
   $ls_codemp  =  $la_empresa["codemp"];
   $li_rtn=0 ;
   $lb_valido=true;
   $ls_cod_report="0409"; // OAF Instr 4
   $lb_valido= $iscg_procesos->delete_scg_pc_reporte($ls_cod_report);
		   
    if ($lb_valido)   
    {
			if ($ls_cod_report=="0409")
			{
				$lb_valido = $iscg_procesos->uf_cargar_origen_y_aplic_fondos_txt($ls_codemp);
				if($lb_valido)
				{
	                 $msg->message("Los datos fueron cargados");
				     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
					 $data=$SQL->obtener_datos($rs_cta);
					 $ds_proaf->data=$data;
					 $li_num=$ds_proaf->getRowCount("cod_report");
					 for($i=1;$i<=$li_num;$i++)
					 { 
								$ls_status=$data["status"][$i];
								if($ls_status=="C")
								{
									?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_proaf->getValue("asignado",$i);?>','<?php print $ds_proaf->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_proaf->getValue("sc_cuenta",$i);?></a></td>
      <?php
								}
								elseif($ls_status=="S")
								{
								   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_proaf->getValue("asignado",$i);?>','<?php print $ds_proaf->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_proaf->getValue("sc_cuenta",$i);?></a></td>
      <?php
								}
								elseif($ls_status=="I")
								{
								   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_proaf->getValue("sc_cuenta",$i)?></td>
      <?php
								}
								 print"<td>".$ds_proaf->getValue("denominacion",$i)."</td>";
								 print"<td>".number_format($ds_proaf->getValue("asignado",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_proaf->getValue("marzo",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_proaf->getValue("junio",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_proaf->getValue("septiembre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_proaf->getValue("diciembre",$i),2,",",".")."</td>";
								 print"</tr>";
							 }//for
		         }//if($lb_valido)
				else
				{
				  $msg->message("Error al cargar los datos");
				}
			}//if($ls_cod_report==0409")
		}//($li_rtn>0)
}//fin de recargar

?>
    </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <tr class="fd-blanco">    
  <tr class="fd-blanco">
    <?php
$_SESSION["objact"]=$ds_proaf->data;
?>
</form>
</body>
<script language="javascript">

function uf_format(obj)
{
	if(obj.value=="")
	{
	 obj.value="0.0000";
	} 
	else
	{
		ldec_monto=uf_convertir(obj.value);
		obj.value=ldec_monto;
	}
}

function ue_distribuir()
{
   var i 
   f=document.form1;
   if((f.txtAsignado.value=="")||(f.txtCuenta.value==""))
   {
     alert(" Por Favor Seleccione una Cuenta....");
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
		 i=f.fila.value;
		 f=document.form1;
		 if((f.txtAsignado.value=="0.0000")||(f.txtAsignado.value=="0,00"))
		 {
			 alert(" Monto Incorrecto....");
			 f.txtAsignado.focus();
		 }
		 else
		 {
			 pagina="sigesp_scg_wdist_manual_trim.php?fila="+i+"&txtAsignado="+f.txtAsignado.value;
			 window.open(pagina,"Asignación","menubar=no,toolbar=no,scrollbars=no,width=500,height=390,left=50,top=50,resizable=yes,location=no");
			 f.operacion.value="DISTRIBUIR";
		 }	 
	 }
     
	   if (document.opcion=="A")
	   {
		 f=document.form1;
		 if((f.txtAsignado.value=="0.0000")||(f.txtAsignado.value=="0,00"))
		 {
			 alert(" Monto Incorrecto....");
			 f.txtAsignado.focus();
		 }
		 else
		 {
		   f.operacion.value="DISTRIBUIR";
		   f.action="sigesp_scg_wproc_prog_oaf_trim.php";
		   f.submit();
		 } 
	   }
	   if (document.opcion=="N")
	   {
		     f=document.form1;
			 if((f.txtAsignado.value=="0.0000")||(f.txtAsignado.value=="0,00"))
			 {
				 alert(" Monto Incorrecto....");
				 f.txtAsignado.focus();
			 }
			 else
			 {
			   f.operacion.value="DISTRIBUIR";
			   f.action="sigesp_scg_wproc_prog_oaf_trim.php";
			   f.submit();
			 } 
	   }
   } 
}

function aceptar(c,d,i,li_row,s)
{
f=document.form1;
f.txtAsignado.value=c;
f.txtCuenta.value=d;
f.fila.value=i;
f.numrow.value=li_row;
f.status.value=s;
f.txtAsignado.focus(true);
}

function ue_guardar()
{
f=document.form1;
f.operacion.value="GUARDAR";
f.action="sigesp_scg_wproc_prog_oaf_trim.php";
f.submit();
}

function ue_recargar()
{
 f=document.form1;
resp=confirm("Este proceso borrara todas las cuentas y las copiara del plan original. ¿ Esta seguro de proceder ?");
if (resp==true)
{
f.operacion.value="RECARGAR";
f.action="sigesp_scg_wproc_prog_oaf_trim.php";
f.submit();
}
else
{
 f.action="sigesp_scg_wproc_progrep_oaf_trim.php";
}
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
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
    if(opc == false) 
     event.returnValue = false; 
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
</script>
</html>