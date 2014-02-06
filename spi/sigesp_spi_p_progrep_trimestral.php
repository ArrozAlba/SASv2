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
	$ls_ventanas="sigesp_spi_p_progrep_trimestral.php";

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
<title>Programacion de Reportes Trimestral</title>
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
<form name="form1" method="post" action="">
  <table width="798" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr>
      <td width="1220" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="798" height="40"></td>
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
  <p><?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");
require_once("../shared/class_folder/class_sigesp_int_spi.php");
require_once("sigesp_spi_class_progrep.php");
require_once("../shared/class_folder/grid_param.php");
$io_include = new sigesp_include();
$io_connect= $io_include-> uf_conectar ();
$io_sql=new class_sql($io_connect);
$io_msg=new class_mensajes();
$io_fecha=new class_fecha();
$io_function=new class_funciones();
$sig_int=new class_sigesp_int();
$int_spi=new class_sigesp_int_spi();
$ds_progrep=new class_datastore();
$io_class_progrep=new sigesp_spi_class_progrep();
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
	  $ls_codrep="s1";
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
  <table width="798" height="224" border="0" align="center">
    <tr>
      <td width="777"><table width="580" height="140" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
              <td height="17" colspan="5" class="titulo-ventana"><div align="center">Programaci&oacute;n de Reporte Trimestral </div></td>
            </tr>
            <tr>
              <td height="18" colspan="5"><span class="Estilo2"></span></td>
            </tr>
            <tr>
              <td width="141" height="21"><div align="right">Reporte</div></td>
              <td colspan="4"><select name="cmbrep" id="select" onChange="uf_cargargrid()">
			  <?php
			    if($ls_codrep=="s1")
				{
				    $ls_seleccione="selected";
					$ls_flujocaja="";
					$ls_inversiones="";
				}
			    if($ls_codrep=="00005")
				{
				    $ls_seleccione="";
					$ls_flujocaja="selected";
					$ls_inversiones="";
				}
			    if($ls_codrep=="0714")
				{
				    $ls_seleccione="";
					$ls_flujocaja="";
					$ls_inversiones="selected";
				}
			  ?>
                <option value="s1" <?php print $ls_seleccione ?>>Seleccione una opcion</option>
                <option value="00005" <?php print $ls_flujocaja ?>>Flujo de Caja </option>
                <option value="0714" <?php print $ls_inversiones ?>>Resumen de Inversiones(Forma 0714)</option>
                </select>
                  <input name="botRecargar" type="button" class="boton" id="botRecargar2" onClick="ue_recargar()" value="Recargar"></td>
            </tr>
            <tr>
              <td height="18"><div align="right">Distribuci&oacute;n</div></td>
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
              <td width="87"><input name="radiobutton" type="radio" value="N" <?php print $ls_ninguno ?> >              
              Ninguno</td>
              <td width="143">
                <input name="radiobutton" type="radio" value="A" <?php print $ls_auto ?>>
              Automatico</td>
              <td width="63"><input name="radiobutton" type="radio" value="M" <?php print $ls_manual ?>>
              Manual </td>
              <td width="133"><a href="javascript:ue_distribuir();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Aceptar" width="15" height="15" border="0"></a></td>
            </tr>
            <tr>
              <td height="22">&nbsp;</td>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
              <td height="22" colspan="5"><div align="center">
<?php	
 //Titulos de la tabla
 $title[1]="Cuenta";   $title[2]="Denominaci&oacute;n";  $title[3]="Previsto";  $ls_nombre="grid_progrep";

if($ls_operacion == "")
{
   $li_total=0;
   $object="";
   $io_class_grid->makegrid($li_total,$title,$object,800,' PROGRAMACION  DE  REPORTES ',$ls_nombre);  
}//$ls_operacion == ""

if($ls_operacion == "RECARGAR")
{
   $lb_valido=$io_class_progrep->uf_spi_cargar_data_original($ls_codrep,$la_seguridad);
   $li_total=0;
   $object="";
   $io_class_grid->makegrid($li_total,$title,$object,800,' PROGRAMACION  DE  REPORTES ',$ls_nombre); 
}//operacion=="RECARGAR"

if($ls_operacion=="CARGAR")
{ 
   $ls_codrep=$_POST["cmbrep"];
   $ls_modrep=3;
   $rs_load=0;
   $lb_valido=$io_class_progrep->uf_spi_cargar_data($ls_codrep,$ls_modrep,$rs_load);
   if($lb_valido)
   {
	 $li=$io_sql->num_rows($rs_load);
	 if($row=$io_sql->fetch_row($rs_load))
	 {
		$data=$io_sql->obtener_datos($rs_load);
		$ds_progrep->data=$data;
		$li_num=$ds_progrep->getRowCount("spi_cuenta");
		$li_totnum=$li_num;
		for($i=1;$i<=$li_num;$i++)
		{    
			$ls_cuenta=$data["spi_cuenta"][$i];  
			$ls_denominacion=$data["denominacion"][$i];
			$ls_distribuir=$data["distribuir"][$i];
			$ls_modrep=$data["modrep"][$i];
			$ls_status=$data["status"][$i];
			$ls_referencia=$data["referencia"][$i];
			$ld_previsto=number_format($data["previsto"][$i],2,",",".");
			$ld_marzo=number_format($data["marzo"][$i],2,",",".");
			$ld_junio=number_format($data["junio"][$i],2,",",".");
			$ld_septiembre=number_format($data["septiembre"][$i],2,",",".");
			$ld_diciembre=number_format($data["diciembre"][$i],2,",",".");
			if($ls_status=="I")
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=formato-azul readonly>";
				$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_previsto onFocus= uf_fila(".$i.") style=text-align:right>
				                    <input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
								    <input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			}
			else
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=sin-borde size=105 >";
				$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_previsto onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
				                    <input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
								    <input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
		   }
	   }//for    
	   $io_class_grid->makegrid($li_totnum,$title,$object,800,'PROGRAMACION DE REPORTE',$ls_nombre);     
	  }//if
	  else
	  {
	       $li_total=0;
		   $object="";
		   $io_class_grid->makegrid($li_total,$title,$object,800,' PROGRAMACION  DE  REPORTES ',$ls_nombre);  
	  }
	}//if
 }//cargar
if ($ls_operacion=="DISTRIBUIR")
{
  $ls_opcion=$_POST["tipo"];
  if ($ls_opcion=="M")
  {
    $li_rows=$_POST["fila"];
    $ls_codrep=$_POST["cmbrep"];
    $li_num=$_POST["li_totnum"];
    for($i=1;$i<=$li_num;$i++)
    {    
        $ls_cuenta=$_POST["txtCuenta".$i];   
		$ls_denominacion=$_POST["txtDenominacion".$i];
		$ld_previsto=$_POST["txtPrevisto".$i];
		$ls_status=$_POST["status".$i];
		$ls_referencia=$_POST["referencia".$i];
		$ls_distribuir=$_POST["distribuir".$i];
		$ls_modrep=$_POST["modrep".$i];
		$ld_marzo=$_POST["marzo".$i];
		$ld_junio=$_POST["junio".$i];
		$ld_septiembre=$_POST["septiembre".$i];
		$ld_diciembre=$_POST["diciembre".$i];
		if($li_rows==$i)
		{
			$ls_distribuir=3;
			$ls_modrep=3;
			$li_nivel=0;
		    $lb_valido = $io_class_progrep->uf_obtener_nivel_cta($ls_cuenta,$li_nivel);//Obtiene nivel de la cta 
            $ls_cta_ceros = $int_spi->uf_spi_cuenta_sin_cero($ls_cuenta);  //devuelve la cta sin ceros
			$ar_cuenta = $io_class_progrep->uf_disable_cta_inferior($ls_cta_ceros,$ls_cuenta,$ls_codrep);  
			$li_total_cuenta=count($ar_cuenta);
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=sin-borde size=105 >";
			$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_previsto onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
				                    <input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
								    <input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			
			$ls_status_in="I";	$ld_cero=0;
            $ld_cero=number_format($ld_cero,2,",",".");	
			$ld_previsto_total= $ld_cero;				
			$ld_m3=$ld_cero;		$ld_m6=$ld_cero;		
			$ld_m9=$ld_cero; 		$ld_m12=$ld_cero;
				
			for($li=1;$li<$li_total_cuenta;$li++)
			{
				$ls_cuenta=$ar_cuenta[$li]; 
				$ls_denominacion="";
				$lb_valido=$io_class_progrep->uf_select_denominacion($ls_cuenta,$ls_codrep,$ls_denominacion);
				if($lb_valido)
				{
					$i=$i+1;
					$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status_in'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
					$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=formato-azul readonly>";
					$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_previsto_total onFocus= uf_fila(".$i.") style=text-align:right>
				                    <input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_m3'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_m6'>
								    <input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_m9'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_m12'>";
			   }//if
			}//for	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		   if($ls_referencia!="")
		   {
				for($li=$li_rows-1;$li>=1;$li--)
				{
					$ls_cuenta_aux=$_POST["txtCuenta".$li];   
					$ls_denominacion_aux=$_POST["txtDenominacion".$li];
					$ld_previsto_aux=$_POST["txtPrevisto".$li];
					$ls_status_aux=$_POST["status".$li];
					$ls_referencia_aux=$_POST["referencia".$li];
					$ls_distribuir_aux=$_POST["distribuir".$li];
					$ls_modrep_aux=$_POST["modrep".$li];
					$ld_marzo_aux=$_POST["marzo".$li];
					$ld_junio_aux=$_POST["junio".$li];
					$ld_septiembre_aux=$_POST["septiembre".$li];
					$ld_diciembre_aux=$_POST["diciembre".$li];
					if($ls_cuenta_aux==$ls_referencia)
					{						
						$ld_previsto=str_replace('.','',$ld_previsto);              $ld_previsto=str_replace(',','.',$ld_previsto);
						$ld_previsto_aux=str_replace('.','',$ld_previsto_aux);	    $ld_previsto_aux=str_replace(',','.',$ld_previsto_aux);
						$ld_marzo=str_replace('.','',$ld_marzo);			        $ld_marzo=str_replace(',','.',$ld_marzo);
						$ld_marzo_aux=str_replace('.','',$ld_marzo_aux);		    $ld_marzo_aux=str_replace(',','.',$ld_marzo_aux);
						$ld_junio=str_replace('.','',$ld_junio);			        $ld_junio=str_replace(',','.',$ld_junio);
						$ld_junio_aux=str_replace('.','',$ld_junio_aux);		    $ld_junio_aux=str_replace(',','.',$ld_junio_aux);
						$ld_septiembre=str_replace('.','',$ld_septiembre);			$ld_septiembre=str_replace(',','.',$ld_septiembre);
						$ld_septiembre_aux=str_replace('.','',$ld_septiembre_aux);	$ld_septiembre_aux=str_replace(',','.',$ld_septiembre_aux);
						$ld_diciembre=str_replace('.','',$ld_diciembre);			$ld_diciembre=str_replace(',','.',$ld_diciembre);
						$ld_diciembre_aux=str_replace('.','',$ld_diciembre_aux);	$ld_diciembre_aux=str_replace(',','.',$ld_diciembre_aux);
						
						$ld_previsto_aux=$ld_previsto_aux+$ld_previsto;
						$ld_marzo_aux=$ld_marzo_aux+$ld_marzo;
						$ld_junio_aux=$ld_junio_aux+$ld_junio;
						$ld_septiembre_aux=$ld_septiembre_aux+$ld_septiembre;
						$ld_diciembre_aux=$ld_diciembre_aux+$ld_diciembre;
						
						$ld_previsto_aux=number_format($ld_previsto_aux,2,",",".");
						$ld_previsto=number_format($ld_previsto,2,",",".");
						$ld_marzo=number_format($ld_marzo,2,",",".");
						$ld_marzo_aux=number_format($ld_marzo_aux,2,",",".");
						$ld_junio=number_format($ld_junio,2,",",".");
						$ld_junio_aux=number_format($ld_junio_aux,2,",",".");
						$ld_septiembre=number_format($ld_septiembre,2,",",".");
						$ld_septiembre_aux=number_format($ld_septiembre_aux,2,",",".");
						$ld_diciembre=number_format($ld_diciembre,2,",",".");
						$ld_diciembre_aux=number_format($ld_diciembre_aux,2,",",".");

						$object[$li][1]="<input type=text name=txtCuenta".$li." value=$ls_cuenta_aux class=sin-borde size=10 readonly ><input name=referencia".$li." type=hidden id=referencia value='$ls_referencia_aux'><input name=status".$li." type=hidden id=status value='$ls_status_aux'><input name=cuenta".$li." type=hidden id=cuenta value='$ls_cuenta_aux'><input name=distribuir".$li." type=hidden id=distribuir value='$ls_distribuir_aux'><input name=modrep".$li." type=hidden id=modrep value='$ls_modrep_aux'>";
						$object[$li][2]="<input type=text name=txtDenominacion".$li." value='$ls_denominacion_aux' size=105 class=sin-borde readonly>";
						$object[$li][3]="<input type=text name=txtPrevisto".$li." class=sin-borde readonly value=$ld_previsto_aux onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$li.") style=text-align:right>
										<input name=marzo".$li." type=hidden id=marzo".$li." value='$ld_marzo_aux'><input name=junio".$li." type=hidden id=junio".$li." value='$ld_junio_aux'>
										<input name=septiembre".$li." type=hidden id=septiembre".$li." value='$ld_septiembre_aux'><input name=diciembre".$li." type=hidden id=diciembre".$li." value='$ld_diciembre_aux'>";
                        $ls_referencia=$ls_referencia_aux;				   
				   }//if
				}//for	
		   }//if				
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		}//if
		else
		{
			if($ls_status=="I")
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=formato-azul readonly>";
				$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_previsto onFocus= uf_fila(".$i.") style=text-align:right>
								<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
								<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			}
			else
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=sin-borde size=105 >";
				$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_previsto onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
								<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
								<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
           }//else	
	}//else
 }//for 
 $io_class_grid->makegrid($li_num,$title,$object,800,'PROGRAMACION DE REPORTE',$ls_nombre); 
 }//if ($ls_opcion=="M")
  if ($ls_opcion=="N")
  {
    $li_rows=$_POST["fila"];
    $ls_codrep=$_POST["cmbrep"];
    $li_num=$_POST["li_totnum"];
	for($i=1;$i<=$li_num;$i++)
    {      
        $ls_cuenta=$_POST["txtCuenta".$i];   
		$ls_denominacion=$_POST["txtDenominacion".$i];
		$ld_previsto=$_POST["txtPrevisto".$i];
		$ls_status=$_POST["status".$i];
		$ls_referencia=$_POST["referencia".$i];
		$ls_distribuir=$_POST["distribuir".$i];
		$ls_modrep=$_POST["modrep".$i];
		$ld_marzo=$_POST["marzo".$i];
		$ld_junio=$_POST["junio".$i];
		$ld_septiembre=$_POST["septiembre".$i];
		$ld_diciembre=$_POST["diciembre".$i];
		if($li_rows==$i)
		{	
			$ls_distribuir=1;
			$ls_modrep=3;
			$li_nivel=0;
		    $lb_valido = $io_class_progrep->uf_obtener_nivel_cta($ls_cuenta,$li_nivel);//Obtiene nivel de la cta 
            $ls_cta_ceros = $int_spi->uf_spi_cuenta_sin_cero($ls_cuenta);  //devuelve la cta sin ceros
			$ar_cuenta = $io_class_progrep->uf_disable_cta_inferior($ls_cta_ceros,$ls_cuenta,$ls_codrep);  
			$li_total_cuenta=count($ar_cuenta);
			
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=sin-borde size=105 >";
			$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_previsto onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
							<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
							<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			$cero=0;
            $ld_cero=number_format($cero,2,",",".");		
			$ld_previsto_total=$ld_cero; 	$ls_status_in="I";	
			$ld_m3=$ld_cero;		$ld_m6=$ld_cero;		
			$ld_m9=$ld_cero;  		$ld_m12=$ld_cero;
				
			for($li=1;$li<$li_total_cuenta;$li++)
			{
				$ls_cuenta=$ar_cuenta[$li]; 
				$ls_denominacion="";
				$lb_valido=$io_class_progrep->uf_select_denominacion($ls_cuenta,$ls_codrep,$ls_denominacion);
				if($lb_valido)
				{
					$i=$i+1;
					$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status_in'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
					$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=formato-azul readonly>";
					$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_previsto_total onFocus= uf_fila(".$i.") style=text-align:right>
									<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_m3'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_m6'>
									<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_m9'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_m12'>";
			   }//if
			}//for	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		   if($ls_referencia!="")
		   {
				for($li=$li_rows-1;$li>=1;$li--)
				{
					$ls_cuenta_aux=$_POST["txtCuenta".$li];   
					$ls_denominacion_aux=$_POST["txtDenominacion".$li];
					$ld_previsto_aux=$_POST["txtPrevisto".$li];
					$ls_status_aux=$_POST["status".$li];
					$ls_referencia_aux=$_POST["referencia".$li];
					$ls_distribuir_aux=$_POST["distribuir".$li];
					$ls_modrep_aux=$_POST["modrep".$li];
					$ld_marzo_aux=$_POST["marzo".$li];
					$ld_junio_aux=$_POST["junio".$li];
					$ld_septiembre_aux=$_POST["septiembre".$li];
					$ld_diciembre_aux=$_POST["diciembre".$li];
					if($ls_cuenta_aux==$ls_referencia)
					{						
						$ld_previsto=str_replace('.','',$ld_previsto);              $ld_previsto=str_replace(',','.',$ld_previsto);
						$ld_previsto_aux=str_replace('.','',$ld_previsto_aux);	    $ld_previsto_aux=str_replace(',','.',$ld_previsto_aux);
						$ld_marzo=str_replace('.','',$ld_marzo);			        $ld_marzo=str_replace(',','.',$ld_marzo);
						$ld_marzo_aux=str_replace('.','',$ld_marzo_aux);		    $ld_marzo_aux=str_replace(',','.',$ld_marzo_aux);
						$ld_junio=str_replace('.','',$ld_junio);			        $ld_junio=str_replace(',','.',$ld_junio);
						$ld_junio_aux=str_replace('.','',$ld_junio_aux);		    $ld_junio_aux=str_replace(',','.',$ld_junio_aux);
						$ld_septiembre=str_replace('.','',$ld_septiembre);			$ld_septiembre=str_replace(',','.',$ld_septiembre);
						$ld_septiembre_aux=str_replace('.','',$ld_septiembre_aux);	$ld_septiembre_aux=str_replace(',','.',$ld_septiembre_aux);
						$ld_diciembre=str_replace('.','',$ld_diciembre);			$ld_diciembre=str_replace(',','.',$ld_diciembre);
						$ld_diciembre_aux=str_replace('.','',$ld_diciembre_aux);	$ld_diciembre_aux=str_replace(',','.',$ld_diciembre_aux);
						
						$ld_previsto_aux=$ld_previsto_aux+$ld_previsto;
						$ld_marzo_aux=$ld_marzo_aux+$ld_marzo;
						$ld_junio_aux=$ld_junio_aux+$ld_junio;
						$ld_septiembre_aux=$ld_septiembre_aux+$ld_septiembre;
						$ld_diciembre_aux=$ld_diciembre_aux+$ld_diciembre;
						
						$ld_previsto_aux=number_format($ld_previsto_aux,2,",",".");
						$ld_previsto=number_format($ld_previsto,2,",",".");
						$ld_marzo=number_format($ld_marzo,2,",",".");
						$ld_marzo_aux=number_format($ld_marzo_aux,2,",",".");
						$ld_junio=number_format($ld_junio,2,",",".");
						$ld_junio_aux=number_format($ld_junio_aux,2,",",".");
						$ld_septiembre=number_format($ld_septiembre,2,",",".");
						$ld_septiembre_aux=number_format($ld_septiembre_aux,2,",",".");
						$ld_diciembre=number_format($ld_diciembre,2,",",".");
						$ld_diciembre_aux=number_format($ld_diciembre_aux,2,",",".");

						$object[$li][1]="<input type=text name=txtCuenta".$li." value=$ls_cuenta_aux class=sin-borde size=10 readonly ><input name=referencia".$li." type=hidden id=referencia value='$ls_referencia_aux'><input name=status".$li." type=hidden id=status value='$ls_status_aux'><input name=cuenta".$li." type=hidden id=cuenta value='$ls_cuenta_aux'><input name=distribuir".$li." type=hidden id=distribuir value='$ls_distribuir_aux'><input name=modrep".$li." type=hidden id=modrep value='$ls_modrep_aux'>";
						$object[$li][2]="<input type=text name=txtDenominacion".$li." value='$ls_denominacion_aux' size=105 class=sin-borde readonly>";
						$object[$li][3]="<input type=text name=txtPrevisto".$li." class=sin-borde readonly value=$ld_previsto_aux onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$li.") style=text-align:right>
										<input name=marzo".$li." type=hidden id=marzo".$li." value='$ld_marzo_aux'><input name=junio".$li." type=hidden id=junio".$li." value='$ld_junio_aux'>
										<input name=septiembre".$li." type=hidden id=septiembre".$li." value='$ld_septiembre_aux'><input name=diciembre".$li." type=hidden id=diciembre".$li." value='$ld_diciembre_aux'>";
                        $ls_referencia=$ls_referencia_aux;				   
				   }//if
				}//for	
		   }//if				
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		}//if
		else
		{
			if($ls_status=="I")
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=formato-azul readonly>";
				$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_previsto onFocus= uf_fila(".$i.") style=text-align:right>
								<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
								<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			}
			else
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=sin-borde size=105 >";
				$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_previsto onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
								<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
								<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
           }//else	
	  }//else
    }//for    
    $io_class_grid->makegrid($li_num,$title,$object,800,'PROGRAMACION DE REPORTE',$ls_nombre);
  } //if ($ls_opcion=="N")	
  
  if ($ls_opcion=="A")
  {
   $li_rows=$_POST["fila"];
   $ls_codrep=$_POST["cmbrep"];
   $li_num=$_POST["li_totnum"];
   for($i=1;$i<=$li_num;$i++)
   {    
        $ls_cuenta=$_POST["txtCuenta".$i];   
		$ls_denominacion=$_POST["txtDenominacion".$i];
		$ld_previsto=$_POST["txtPrevisto".$i];
		$ls_status=$_POST["status".$i];
		$ls_referencia=$_POST["referencia".$i];
		$ls_distribuir=$_POST["distribuir".$i];
		$ls_modrep=$_POST["modrep".$i];
		$ld_marzo=$_POST["marzo".$i];
		$ld_junio=$_POST["junio".$i];
		$ld_septiembre=$_POST["septiembre".$i];
		$ld_diciembre=$_POST["diciembre".$i];
		
		if($li_rows==$i)
		{
			$ls_distribuir=2;	
			$ls_modrep=3;
			$li_nivel=0;
            $lb_valido = $io_class_progrep->uf_obtener_nivel_cta($ls_cuenta,$li_nivel);//Obtiene nivel de la cta 
            $ls_cta_ceros = $int_spi->uf_spi_cuenta_sin_cero($ls_cuenta);  //devuelve la cta sin ceros
            $ar_cuenta = $io_class_progrep->uf_disable_cta_inferior($ls_cta_ceros,$ls_cuenta,$ls_codrep);  
            $li_total_cuenta=count($ar_cuenta);
			
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=sin-borde size=105 >";
			$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_previsto onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
							<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
							<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			$cero=0;
            $ld_cero=number_format($cero,2,",",".");		
			$ld_previsto_total=$ld_cero;	$ls_status_in="I";
			$ld_m3=$ld_cero;		$ld_m6=$ld_cero;
			$ld_m9=$ld_cero;   	    $ld_m12=$ld_cero;
				
			for($li=1;$li<$li_total_cuenta;$li++)
			{
				$ls_cuenta=$ar_cuenta[$li]; 
				$ls_denominacion="";
				$lb_valido=$io_class_progrep->uf_select_denominacion($ls_cuenta,$ls_codrep,$ls_denominacion);
				if($lb_valido)
				{
					$i=$i+1;
					$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status_in'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
					$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=formato-azul readonly>";
					$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_previsto_total onFocus= uf_fila(".$i.") style=text-align:right>
									<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_m3'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_m6'>
									<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_m9'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_m12'>";
			   }//if
			}//for	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		   if($ls_referencia!="")
		   {
				for($li=$li_rows-1;$li>=1;$li--)
				{
					$ls_cuenta_aux=$_POST["txtCuenta".$li];   
					$ls_denominacion_aux=$_POST["txtDenominacion".$li];
					$ld_previsto_aux=$_POST["txtPrevisto".$li];
					$ls_status_aux=$_POST["status".$li];
					$ls_referencia_aux=$_POST["referencia".$li];
					$ls_distribuir_aux=$_POST["distribuir".$li];
					$ls_modrep_aux=$_POST["modrep".$li];
					$ld_marzo_aux=$_POST["marzo".$li];
					$ld_junio_aux=$_POST["junio".$li];
					$ld_septiembre_aux=$_POST["septiembre".$li];
					$ld_diciembre_aux=$_POST["diciembre".$li];
					if($ls_cuenta_aux==$ls_referencia)
					{						
						$ld_previsto=str_replace('.','',$ld_previsto);              $ld_previsto=str_replace(',','.',$ld_previsto);
						$ld_previsto_aux=str_replace('.','',$ld_previsto_aux);	    $ld_previsto_aux=str_replace(',','.',$ld_previsto_aux);
						$ld_marzo=str_replace('.','',$ld_marzo);			        $ld_marzo=str_replace(',','.',$ld_marzo);
						$ld_marzo_aux=str_replace('.','',$ld_marzo_aux);		    $ld_marzo_aux=str_replace(',','.',$ld_marzo_aux);
						$ld_junio=str_replace('.','',$ld_junio);			        $ld_junio=str_replace(',','.',$ld_junio);
						$ld_junio_aux=str_replace('.','',$ld_junio_aux);		    $ld_junio_aux=str_replace(',','.',$ld_junio_aux);
						$ld_septiembre=str_replace('.','',$ld_septiembre);			$ld_septiembre=str_replace(',','.',$ld_septiembre);
						$ld_septiembre_aux=str_replace('.','',$ld_septiembre_aux);	$ld_septiembre_aux=str_replace(',','.',$ld_septiembre_aux);
						$ld_diciembre=str_replace('.','',$ld_diciembre);			$ld_diciembre=str_replace(',','.',$ld_diciembre);
						$ld_diciembre_aux=str_replace('.','',$ld_diciembre_aux);	$ld_diciembre_aux=str_replace(',','.',$ld_diciembre_aux);
						
						$ld_previsto_aux=$ld_previsto_aux+$ld_previsto;
						$ld_marzo_aux=$ld_marzo_aux+$ld_marzo;
						$ld_junio_aux=$ld_junio_aux+$ld_junio;
						$ld_septiembre_aux=$ld_septiembre_aux+$ld_septiembre;
						$ld_diciembre_aux=$ld_diciembre_aux+$ld_diciembre;
						
						$ld_previsto_aux=number_format($ld_previsto_aux,2,",",".");
						$ld_previsto=number_format($ld_previsto,2,",",".");
						$ld_marzo=number_format($ld_marzo,2,",",".");
						$ld_marzo_aux=number_format($ld_marzo_aux,2,",",".");
						$ld_junio=number_format($ld_junio,2,",",".");
						$ld_junio_aux=number_format($ld_junio_aux,2,",",".");
						$ld_septiembre=number_format($ld_septiembre,2,",",".");
						$ld_septiembre_aux=number_format($ld_septiembre_aux,2,",",".");
						$ld_diciembre=number_format($ld_diciembre,2,",",".");
						$ld_diciembre_aux=number_format($ld_diciembre_aux,2,",",".");

						$object[$li][1]="<input type=text name=txtCuenta".$li." value=$ls_cuenta_aux class=sin-borde size=10 readonly ><input name=referencia".$li." type=hidden id=referencia value='$ls_referencia_aux'><input name=status".$li." type=hidden id=status value='$ls_status_aux'><input name=cuenta".$li." type=hidden id=cuenta value='$ls_cuenta_aux'><input name=distribuir".$li." type=hidden id=distribuir value='$ls_distribuir_aux'><input name=modrep".$li." type=hidden id=modrep value='$ls_modrep_aux'>";
						$object[$li][2]="<input type=text name=txtDenominacion".$li." value='$ls_denominacion_aux' size=105 class=sin-borde readonly>";
						$object[$li][3]="<input type=text name=txtPrevisto".$li." class=sin-borde readonly value=$ld_previsto_aux onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$li.") style=text-align:right>
										 <input name=marzo".$li." type=hidden id=marzo".$li." value='$ld_marzo_aux'><input name=junio".$li." type=hidden id=junio".$li." value='$ld_junio_aux'>
										 <input name=septiembre".$li." type=hidden id=septiembre".$li." value='$ld_septiembre_aux'><input name=diciembre".$li." type=hidden id=diciembre".$li." value='$ld_diciembre_aux'>";
                        $ls_referencia=$ls_referencia_aux;				   
				   }//if
				}//for	
		   }//if				
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		}//if
		else
		{
			if($ls_status=="I")
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=formato-azul readonly>";
				$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_previsto onFocus= uf_fila(".$i.") style=text-align:right>
								<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
								<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
			}
			else
			{
				$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
				$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=sin-borde size=105 >";
				$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_previsto onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
								<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
								<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
            }//else	
	    }//else
     }//for 
     $io_class_grid->makegrid($li_num,$title,$object,800,'PROGRAMACION DE REPORTE',$ls_nombre);  
 }//fin de automatico
}//DISTRIBUIR

if ($ls_operacion=="GUARDAR" )
{
   $cont_insert=0;
   $ls_codrep=$_POST["cmbrep"];
   $li_num=$_POST["li_totnum"];
   for($i=1;$i<=$li_num;$i++)
   { 
        $ls_cuenta=$_POST["txtCuenta".$i];   
	    $ls_denominacion=$_POST["txtDenominacion".$i];
	    $ld_previsto=trim($_POST["txtPrevisto".$i]);
		$ls_status=$_POST["status".$i];
		$ls_referencia=$_POST["referencia".$i];
		$ls_distribuir=$_POST["distribuir".$i];
		$ls_modrep=$_POST["modrep".$i];
		$cero=0;
        $ld_cero=number_format($cero,2,",",".");		
	    $ld_enero=$ld_cero;
	    $ld_febrero=$ld_cero;
		$ld_marzo=$_POST["marzo".$i];
		$ld_abril=$ld_cero;
		$ld_mayo=$ld_cero;
		$ld_junio=$_POST["junio".$i];
		$ld_julio=$ld_cero;
		$ld_agosto=$ld_cero;
		$ld_septiembre=$_POST["septiembre".$i];
		$ld_octubre=$ld_cero;
		$ld_noviembre=$ld_cero;
		$ld_diciembre=$_POST["diciembre".$i];
		 
		if ($ls_status=="I")
		{
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=formato-azul size=10 readonly ><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' size=105 class=formato-azul readonly>";
			$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=formato-azul readonly value=$ld_previsto onFocus= uf_fila(".$i.") style=text-align:right>
							<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
							<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
		}
		else
		{		
			$object[$i][1]="<input type=text name=txtCuenta".$i." value=$ls_cuenta class=sin-borde size=10 readonly><input name=referencia".$i." type=hidden id=referencia value='$ls_referencia'><input name=status".$i." type=hidden id=status value='$ls_status'><input name=cuenta".$i." type=hidden id=cuenta value='$ls_cuenta'><input name=distribuir".$i." type=hidden id=distribuir value='$ls_distribuir'><input name=modrep".$i." type=hidden id=modrep value='$ls_modrep'>";
			$object[$i][2]="<input type=text name=txtDenominacion".$i." value='$ls_denominacion' class=sin-borde size=105 >";
			$object[$i][3]="<input type=text name=txtPrevisto".$i." onBlur='uf_formato(this)' class=sin-borde value=$ld_previsto onKeyPress=return(ue_formatonumero(this,'.',',',event)) onFocus= uf_fila(".$i.") style=text-align:right>
							<input name=marzo".$i." type=hidden id=marzo".$i." value='$ld_marzo'><input name=junio".$i." type=hidden id=junio".$i." value='$ld_junio'>
							<input name=septiembre".$i." type=hidden id=septiembre".$i." value='$ld_septiembre'><input name=diciembre".$i." type=hidden id=diciembre".$i." value='$ld_diciembre'>";
        }
	           
		$ld_previsto=str_replace('.','',$ld_previsto);	    $ld_previsto=str_replace(',','.',$ld_previsto);		
		$ld_enero=str_replace('.','',$ld_enero);   	        $ld_enero=str_replace(',','.',$ld_enero);
		$ld_febrero=str_replace('.','',$ld_febrero);	    $ld_febrero=str_replace(',','.',$ld_febrero);
		$ld_marzo=str_replace('.','',$ld_marzo); 	        $ld_marzo=str_replace(',','.',$ld_marzo);
		$ld_abril=str_replace('.','',$ld_abril);		    $ld_abril=str_replace(',','.',$ld_abril);
		$ld_mayo=str_replace('.','',$ld_mayo); 		        $ld_mayo=str_replace(',','.',$ld_mayo);
		$ld_junio=str_replace('.','',$ld_junio);		    $ld_junio=str_replace(',','.',$ld_junio);
		$ld_julio=str_replace('.','',$ld_julio);		    $ld_julio=str_replace(',','.',$ld_julio);
		$ld_agosto=str_replace('.','',$ld_agosto);		    $ld_agosto=str_replace(',','.',$ld_agosto);
		$ld_septiembre=str_replace('.','',$ld_septiembre);  $ld_septiembre=str_replace(',','.',$ld_septiembre);
		$ld_octubre=str_replace('.','',$ld_octubre);	    $ld_octubre=str_replace(',','.',$ld_octubre);
		$ld_noviembre=str_replace('.','',$ld_noviembre);    $ld_noviembre=str_replace(',','.',$ld_noviembre);
		$ld_diciembre=str_replace('.','',$ld_diciembre);    $ld_diciembre=str_replace(',','.',$ld_diciembre);
	 
		$lb_valido=$io_class_progrep->uf_spi_guardar_programacion_reportes($ls_status,$ld_previsto,$ls_distribuir,$ls_modrep,$ld_enero,
															 $ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,$ld_julio,$ld_agosto,
															 $ld_septiembre,$ld_octubre,$ld_noviembre,$ld_diciembre,$ls_cuenta,
															 $ls_codrep);
	   if ($lb_valido)
	   {
		 $cont_insert=$cont_insert+1;
	   }
	 }//for 		
	 if($cont_insert==$li_num)
	 {
          $io_sql->begin_transaction();
          //////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion =" Guardar la programacion de reportes ";
			$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
											$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
											$la_seguridad["ventanas"],$ls_descripcion);
  		 /////////////////////////////////         SEGURIDAD               /////////////////////////////	
			
		 $io_sql->commit();
	     $io_msg->message(" Los Datos fueron guardados con  exito ");
	 }
	 else
	 {
	 	$io_sql->rollback();  
		$io_msg->message(" Error en los datos en el guardar  ");
	 }	  
  $io_class_grid->makegrid($li_num,$title,$object,800,'PROGRAMACION DE REPORTE',$ls_nombre);
}//GUARDAR	
 
?>
                <input name="operacion" type="hidden" id="operacion" value="<?php $_POST["operacion"]?>">
                <input name="li_totnum" type="hidden" id="li_totnum" value="<?php print $li_totnum; ?>">
                <input name="fila" type="hidden" id="fila">
                <input name="tipo" type="hidden" id="tipo">
</div></td>
            </tr>
            <tr>
              <td height="22" colspan="5">&nbsp;</td>
            </tr>
        </table>
        <p align="center">&nbsp;</p></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
<script language="javascript">
function uf_cargargrid()
{
	f=document.form1;
	f.operacion.value="CARGAR";
	f.action="sigesp_spi_p_progrep_trimestral.php";
	f.submit();
}

function ue_recargar()
{
    f=document.form1;
    resp=confirm("Este proceso borrara todas las cuentas y las copiara del plan original(Todas las programatica). ¿ Esta seguro de proceder ?");
	if (resp==true)
    {
		f.operacion.value="RECARGAR";
		f.action="sigesp_spi_p_progrep_trimestral.php";
		f.submit();
    }
}

function ue_distribuir()
{
    var i ;
    f=document.form1;
    li=f.fila.value;
	for (i=0;i<f.radiobutton.length;i++)
	{ 
	   if (f.radiobutton[i].checked) 
		  break; 
	} 
	document.opcion = f.radiobutton[i].value; 
	if(document.opcion=="M" ) 
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
		 pagina="sigesp_spi_p_progrep_trimestral_distribucion.php?fila="+li+"&txtPrevisto="+ld_previsto
				 +"&marzo="+ld_marzo+"&junio="+ld_junio+"&septiembre="+ld_septiembre+"&diciembre="+ld_diciembre
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
				   ld_division=redondear(ld_division,2);
				   ld_previsto=redondear(ld_previsto,2);
				   ld_suma_diciembre=redondear((ld_division*4),2);
				   ld_mes12=(ld_previsto-ld_suma_diciembre);
				   ld_mes12=redondear(ld_mes12,2);
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
				   ld_total_general=redondear(ld_total_general,2);
				   ld_resto=(ld_previsto-ld_total_general);
				   ld_resto=redondear(ld_resto,2);
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
				
				   pagina="sigesp_spi_p_progrep_trimestral_distribucion.php?fila="+li+"&txtPrevisto="+ld_previsto
						   +"&marzo="+ld_marzo+"&junio="+ld_junio+"&septiembre="+ld_septiembre+"&diciembre="+ld_diciembre
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
		   f.operacion.value="DISTRIBUIR";
		   f.tipo.value="N";
		   f.submit();
		}   
		else
		{
		 alert("Por favor coloque el cursor sobre la fila  a editar  ");
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
		f.action="sigesp_spi_p_progrep_trimestral.php";
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
   f.action="sigesp_spg_p_progrep.php";
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
function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
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
