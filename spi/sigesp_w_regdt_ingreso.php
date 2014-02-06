<?
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<title>Entrada de Comprobante de Ingresos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style2 {font-size: 11px}
-->
</style>
</head>
<body>
<?php
$dat=$_SESSION["la_empresa"];
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
require_once("../shared/class_folder/ddlb_generic_bd.php");
require_once("class_folder/class_funciones_spi.php");
$io_function=new class_funciones();	
$io_include=new sigesp_include();	
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_msg = new class_mensajes();
$ddlb_operaciones=new ddlb_generic_bd($io_connect);
require_once("sigesp_spi_c_comprobante.php");
$in_classcmp=new sigesp_spi_c_comprobante();
require_once("../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad= new sigesp_c_seguridad();
$io_class_spi = new class_funciones_spi();

    /////////////////////////////////////Parametros necesarios para seguridad////////////////////////////
	$ls_empresa=$dat["codemp"];
	$li_estpreing     = $dat["estpreing"];
	$li_estmodest     = $dat["estmodest"];
	$li_loncodestpro1 = $dat["loncodestpro1"];
	$li_loncodestpro2 = $dat["loncodestpro2"];
	$li_loncodestpro3 = $dat["loncodestpro3"];
	$li_loncodestpro4 = $dat["loncodestpro4"];
	$li_loncodestpro5 = $dat["loncodestpro5"];
	
	$li_nomestpro1 = $dat["nomestpro1"];
	$li_nomestpro2 = $dat["nomestpro2"];
	$li_nomestpro3 = $dat["nomestpro3"];
	$li_nomestpro4 = $dat["nomestpro4"];
	$li_nomestpro5 = $dat["nomestpro5"];
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
	$la_seguridad[1]=$ls_empresa;
	$la_seguridad[2]=$ls_sistema;
	$la_seguridad[3]=$ls_logusr;
	$la_seguridad[4]=$ls_ventana;
	//////////////////////////////////////////////////////////////////////////////////////////////////
if (array_key_exists("operacion",$_POST))
{
    $ls_operacion=$_POST["operacion"];
	$ls_documento=$_POST["txtdocumento"];
	$ls_cuentaplan=$_POST["txtcuenta"];
	$ls_denominacion=$_POST["txtdenominacion"];
	$ls_procedencia=$_POST["txtprocedencia"];
	$ls_descripcion=$_POST["txtdescripcion"];
	$ls_comprobante=$_POST["comprobante"];
	$ls_proccomp   =$_POST["procede"];
	$ls_desccomp   =$_POST["descripcion"];
	$ld_fecha	   =$_POST["fecha"];
	$ls_tipo       =$_POST["tipo"];
	$ls_provbene   =$_POST["provbene"];
	$ld_monto       = $_POST["txtmonto"];
	if($li_estpreing=='1')
    {
		$ls_estpro1=$_POST["codestpro1"];
		$ls_estpro2=$_POST["codestpro2"];
		$ls_estpro3=$_POST["codestpro3"];
		if($li_estmodest==2)
		{
			$ls_estpro4=$_POST["codestpro4"];
			$ls_estpro5=$_POST["codestpro5"];
		}
		$ls_estcla=$_POST["estcla"];
    }
}
else
{
	$ls_operacion="";
	$ls_documento="000000000000001";
	$ls_cuentaplan="";
	$ls_denominacion="";
	$ld_monto=0;
	$ls_procedencia="SPICMP";
	$ls_descripcion="";
	$ls_comprobante=$_GET["comprobante"];
	$ls_proccomp   =$_GET["procede"];
	$ls_desccomp   =$_GET["descripcion"];
	$ld_fecha	   =$_GET["fecha"];
	$ls_tipo       =$_GET["tipo"];
	$ls_provbene   =$_GET["provbene"];
	if($li_estpreing=='1')
    {
		$ls_estpro1="";
		$ls_estpro2="";
		$ls_estpro3="";
		$ld_monto="";
		if($li_estmodest==2)
		{
			$ls_estpro4="";
			$ls_estpro5="";
		}
		$ls_estcla="";
	}
}
if (array_key_exists("txtmonto",$_POST))
{
    $ld_monto       = $_POST["txtmonto"];
}
else
{
   	$ld_monto=0;
}

$_SESSION["fechacomprobante"] = $ld_fecha;

if($ls_operacion=="GUARDARPRE")
{
	$ls_comprobante=$_POST["comprobante"];
	$ld_fecha      =$_POST["fecha"];
	$ls_proccomp   =$_POST["procede"];
	$ls_desccomp   =$_POST["descripcion"];
	$ls_provbene   =$_POST["provbene"];	
	$ls_tipo	   =$_POST["tipo"];
	$ls_codban     = "---";
	$ls_ctaban     = "-------------------------";
	$ld_monto      = $_POST["txtmonto"];
	if($ls_tipo=="P")
	{
		$ls_prov=$ls_provbene;
		$ls_bene="----------";
	}
	else
	{
		$ls_bene=$ls_provbene;
		$ls_prov="----------";
	}
	if ($li_estpreing==1)
	 {
			//$ls_estcla     = trim($_POST["hidtipestpro"]);
			$ls_estcla     = trim($_POST["estcla"]);
			$ls_codestpro1 = str_pad(trim($_POST["codestpro1"]),25,0,0);
			$ls_codestpro2 = str_pad(trim($_POST["codestpro2"]),25,0,0);
			$ls_codestpro3 = str_pad(trim($_POST["codestpro3"]),25,0,0);
			if ($li_estmodest==2)
			{
				$ls_codestpro4 = str_pad(trim($_POST["codestpro4"]),25,0,0);
				$ls_codestpro5 = str_pad(trim($_POST["codestpro5"]),25,0,0);
			}
			else
			{
				$ls_codestpro4 = $ls_codestpro5 = str_pad("",25,0,0);
			}
	 }
	 else
	 {
			$ls_estcla = '-';
			$ls_codestpro1 = $ls_codestpro2 = $ls_codestpro3 = $ls_codestpro4 = $ls_codestpro5 = str_pad("",25,"-",0);
	 }
	$lb_valido=$in_classcmp->uf_guardar_automatico($ls_comprobante,$ld_fecha,$ls_proccomp,$ls_desccomp,&$ls_prov,&$ls_bene,$ls_tipo,1,$ls_codban,$ls_ctaban);
	
	$arr_cmp["comprobante"]=$ls_comprobante;
	$ld_fecdb=$io_function->uf_convertirdatetobd($ld_fecha);
	$arr_cmp["fecha"]      =$ld_fecdb;
	$arr_cmp["procedencia"]=$ls_proccomp;
	$arr_cmp["descripcion"]=$ls_desccomp;
	$arr_cmp["proveedor"]  =$ls_prov;
	$arr_cmp["beneficiario"]=$ls_bene;
	$arr_cmp["tipo"]       =$ls_tipo;
	$arr_cmp["codemp"]     =$dat["codemp"];
	$arr_cmp["tipo_comp"]  =1;
	if($lb_valido)
	{
		$ls_cuenta      = $_POST["txtcuenta"];
		$ls_documento   = $_POST["txtdocumento"];
		$ls_descripcion=$_POST["txtdescripcion"];
		$ls_denominacion= $_POST["txtdenominacion"];
		$ls_procede     = $_POST["txtprocedencia"];
		$ls_operacionpre= $_POST["ddlb_operacion"];
		$ld_monto       = $_POST["txtmonto"];
		$ldec_monto=str_replace(".","",$ld_monto);
		$ldec_monto=str_replace(",",".",$ldec_monto);
		//$in_classcmp->io_sql->begin_transaction();
		$lb_valido=$in_classcmp->uf_guardar_movimientos($arr_cmp,$ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,$ls_operacionpre,0,$ldec_monto,"C",$ls_codban,$ls_ctaban,
		                                                $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla);
		if($lb_valido)
		{	
		     $lb_valido = $io_class_spi->uf_convertir_sigespcmp($ls_procede,$ls_comprobante,$ld_fecdb,$ls_codban,$ls_ctaban,$la_seguridad);
			 if ($lb_valido)
		     { 
		    	$lb_valido = $io_class_spi->uf_convertir_spidtcmp($ls_procede,$ls_comprobante,$ld_fecdb,$ls_codban,$ls_ctaban,$la_seguridad);
		     }
		     if($lb_valido)
			 { 
			    $lb_valido=$io_class_spi->uf_convertir_scgdtcmp($ls_procede,$ls_comprobante,$ld_fecdb,$ls_codban,$ls_ctaban,$la_seguridad);
			 }
			 if ($lb_valido)
		     {
		      $in_classcmp->io_sql->commit();
		     }
		  	else
			  { 
				$in_classcmp->io_sql->rollback(); 
 
			  }  
			 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			if($in_classcmp->io_int_spi->is_log_transacciones!="")
			{
				$ls_desc_event=$in_classcmp->io_int_spi->is_log_transacciones.",Inserto el movimiento presupuestario ".$ls_documento." con operacion".$ls_operacionpre." por un monto de ".$ldec_monto." para la cuenta ".$ls_cuenta;
			}
			else
			{
				$ls_desc_event="Inserto el movimiento presupuestario ".$ls_documento." con operacion".$ls_operacionpre." por un monto de ".$ldec_monto." para la cuenta ".$ls_cuenta." ; para el comprobante ".$ls_comprobante." de fecha ".$ld_fecha;
			}
			$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
			////////////////////////////////         SEGURIDAD               //////////////////////////////
		}
		
		else	
		{	
			$in_classcmp->io_sql->rollback(); 
		}
	} 
	else
	{
		$ls_cuenta      = $_POST["txtcuenta"];
		$ls_documento   = $_POST["txtdocumento"];
		$ls_descripcion=$_POST["txtdescripcion"];
		$ls_denominacion= $_POST["txtdenominacion"];
		$ls_procede     = $_POST["txtprocedencia"];
		$ls_operacionpre= $_POST["ddlb_operacion"];
		$ld_monto       = $_POST["txtmonto"];
		$ls_estcla      = $_POST["estcla"];
	}   
	?>
	<script language="javascript">
		f=opener.document.form1;
		f.operacion.value="CARGAR_DT";
		f.action="sigesp_spi_p_comprobante.php";
		f.submit();
	</script>
	<?      
}
?>
<form method="post" name="form1" action=""> 
<table width="583" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
   <td colspan="2" class="titulo-celda">Entrada de Comprobante de Ingresos </td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="119" height="22" align="right">Documento</td>
    <td width="450"><input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="javascript:valid_cmp(this);" size="22" maxlength="15" value="<? print $ls_documento;?>"></td>
  </tr>
  <tr>
    <td height="22" align="right">Descripci&oacute;n</td>
    <td><input name="txtdescripcion" type="text" id="txtdescripcion" size="80" maxlength="100" style="text-align:left" value="<? print $ls_descripcion;?>"></td>
  </tr>
  <tr>
    <td height="22" align="right">Procedencia</td>
    <td><input name="txtprocedencia" type="text" id="txtprocedencia" size="22" maxlength="6" style="text-align:center" value="<? print $ls_procedencia;?>" readonly></td>
  </tr>
   <?
  if ($li_estpreing==1)
	{
  ?>
  <tr>
    <td height="22" style="text-align:right"><?php print $li_nomestpro1;  ?></td>
    <td><input name="codestpro1" type="text" id="codestpro1" size="<?php echo $li_size1; ?>" maxlength="<?php echo $li_loncodestpro1 ?>" style="text-align:center"  value="<?php print $ls_estpro1; ?>" >
      <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
      <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" size="50" readonly>
      <input name="hidtipestpro" type="hidden" id="hidtipestpro">
      <input name="estcla" type="hidden" id="estcla"></td>
  </tr>
  <tr>
    <td height="22" style="text-align:right"><?php print $li_nomestpro2 ; ?></td>
    <td><input name="codestpro2" type="text" id="codestpro2" size="<?php echo $li_size2; ?>" maxlength="<?php echo $li_loncodestpro2 ?>" style="text-align:center"  value="<?php print $ls_estpro2; ?>" >
      <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
      <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" size="50" readonly></td>
  </tr>
  <tr>
    <td height="22" style="text-align:right"><?php print $li_nomestpro3; ?></td>
    <td><input name="codestpro3" type="text" id="codestpro3" size="<?php echo $li_size3; ?>" maxlength="<?php echo $li_loncodestpro3 ?>" style="text-align:center"  value="<?php print $ls_estpro3; ?>" >
      <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
      <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" size="50" readonly></td>
  </tr>
   <?php 
  if ($li_estmodest==2)
     {
  ?>
  <tr>
    <td height="22" style="text-align:right"><?php print $li_nomestpro4;?></td>
    <td height="22"><label>
      <input name="codestpro4" type="text" id="codestpro4" size="<?php echo $li_size4; ?>" maxlength="<?php echo $li_loncodestpro4 ?>" style="text-align:center"  value="<?php print $ls_estpro4; ?>" >
      <a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 4"></a>
      <input name="denestpro4" type="text" class="sin-borde" id="denestpro4" size="50" readonly>
    </label></td>
  </tr>
  <tr>
    <td height="22" style="text-align:right"><?php print $li_nomestpro5;?></td>
    <td height="22"><label>
      <input name="codestpro5" type="text" id="codestpro5" size="<?php echo $li_size5; ?>" maxlength="<?php echo $li_loncodestpro5 ?>" style="text-align:center"  value="<?php print $ls_estpro5; ?>" >
      <a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 5"></a>
      <input name="denestpro5" type="text" class="sin-borde" id="denestpro5" size="50" readonly>
    </label></td>
  </tr>
  <? 
      }// fin deif ($li_estmodest==2) 
	  
    }// fin de  if ($li_estpreing==1)
  ?>
  <tr>
    <td height="22"><div align="right">Cuenta</div></td>
    <td><input name="txtcuenta" type="text" id="txtcuenta" value="<? print $ls_cuentaplan ?>" size="22" style="text-align:center" readonly="true"> 
    <a href="javascript:catalogo_cuentasSPI();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Cuentas de Gastos"></a>	 <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<? print $ls_denominacion ?>" size="50" maxlength="254"></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Operaci&oacute;n</div></td>
    <td><div align="left">
      <?
		$ddlb_operaciones->uf_cargar_ddlb("operacion,denominacion","operacion","denominacion","spi_operaciones"," WHERE reservado=0","ddlb_operacion",250,$ls_operacion);
	  ?>
    </div></td>
  </tr>
  <tr>
    <td height="22" align="right">Monto</td>
    <td><input name="txtmonto" type="text" id="txtmonto" value="<? print $ld_monto ?>"  style="text-align:right" size="22" onKeyPress="return(currencyFormat(this,'.',',',event))" onBlur="javascript:uf_format(this);"> 
      <a href="javascript:aceptar_presupuestario();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Agregar Detalle Presupuestario" width="15" height="15" border="0"></a> <a href="javascript: close();"><img src="../shared/imagebank/eliminar.gif" alt="Cancelar Registro de Detalle Presupuestario" width="15" height="15" border="0"></a></td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td><input name="operacion" type="hidden" id="operacion">
      <input name="comprobante" type="hidden" id="comprobante" value="<? print $ls_comprobante;?>">
      <input name="procede" type="hidden" id="procede" value="<? print $ls_proccomp;?>">
      <input name="fecha" type="hidden" id="fecha" value="<? print $ld_fecha;?>">
      <input name="provbene" type="hidden" id="provbene" value="<? print $ls_provbene;?>">
      <input name="tipo" type="hidden" id="tipo" value="<? print $ls_tipo;?>">
      <input name="descripcion" type="hidden" id="descripcion" value="<? print $ls_desccomp;?>"></td>
  </tr>
</table>
</form>
</body>
<script language="JavaScript">
  function aceptar_presupuestario()
  {
  	f=document.form1;
	ls_cuenta=f.txtcuenta.value;
	ls_descripcion=f.txtdescripcion.value;
	ls_procedencia=f.txtprocedencia.value;
	ls_documento=f.txtdocumento.value;
	ls_operacion=f.ddlb_operacion.value;
	ldec_monto=f.txtmonto.value;
	if((ls_cuenta!="")&&(ls_descripcion!="")&&(ls_procedencia!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto!=""))
	{
	f.operacion.value="GUARDARPRE";
	f.action="sigesp_w_regdt_ingreso.php";
	f.submit();
	}
	else
	{
		alert("Complete los detalles del movimiento");
	}
  }
  function uf_close()
  {
	  close()
  }
	
function valid_cmp(cmp)
{
	if((cmp.value==0)||(cmp.value==""))
	{
	alert("Introduzca un numero comprobante valido");
	cmp.focus();
	}
	else
	{
	rellenar_cad(cmp.value,15,"doc");
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

 function catalogo_cuentasSPI()
 {
   f=document.form1;
   li_estpreing = "<?php echo $_SESSION["la_empresa"]["estpreing"]; ?>";
   if (li_estpreing==1)
     {
	   ls_codestpro1 = f.codestpro1.value;
	   ls_codestpro2 = f.codestpro2.value;
	   ls_codestpro3 = f.codestpro3.value;
	   ls_denestpro1 = f.denestpro1.value;
	   ls_denestpro2 = f.denestpro2.value;
	   ls_denestpro3 = f.denestpro3.value;
	   //ls_estcla     = f.hidtipestpro.value;
	   ls_estcla     = f.estcla.value;
	   li_estmodest  = "<?php echo $_SESSION["la_empresa"]["estmodest"]; ?>";
	   if (li_estmodest==2)
		  {
		    ls_codestpro4 = f.codestpro4.value;
		    ls_codestpro5 = f.codestpro5.value;
		    ls_denestpro4 = f.denestpro4.value;
		    ls_denestpro5 = f.denestpro5.value;	 
		  }
	   else
		  {
		    ls_codestpro4 = ls_codestpro5 = '0000000000000000000000000';
		    ls_denestpro4 = ls_denestpro5 = "";
		  }   
	   lb_valido = true;
	   if (li_estmodest==2)
		  {
		    if (ls_codestpro1=='' || ls_codestpro2=='' || ls_codestpro3=='' || ls_codestpro4=='' || ls_codestpro5=='')
			   {
				 alert("Debe completar la Estructura Presupuestaria !!!");
				 lb_valido = false;
			   }
		  }
	   else
		  {
		    if (ls_codestpro1=='' || ls_codestpro2=='' || ls_codestpro3=='')
			   {
				 alert("Debe completar la Estructura Presupuestaria !!!");
				 lb_valido = false;
			   }
		  }
	   if (lb_valido)
		  {
		    pagina="sigesp_cat_ctasspi.php?codestpro1="+ls_codestpro1+"&codestpro2="+ls_codestpro2+"&codestpro3="+ls_codestpro3
			     +"&codestpro4="+ls_codestpro4+"&codestpro5="+ls_codestpro5+"&estcla="+ls_estcla+"&txtdenestpro1="+ls_denestpro1
				 +"&txtdenestpro2="+ls_denestpro2+"&txtdenestpro3="+ls_denestpro3+"&txtdenestpro4="+ls_denestpro4
				 +"&txtdenestpro5="+ls_denestpro5+"&estcla="+ls_estcla;
		    window.open(pagina,"_blank","dependent=yes,menubar=no,toolbar=no,scrollbars=yes,width=770,height=550,resizable=yes,location=no");	 
		  }
	 }
	  else
	  {
		   pagina="sigesp_cat_ctasspi.php";
		   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no");
      }
 } 
 
function  uf_format(obj)
{
	ldec_monto=obj.value;
	if(ldec_monto=="")
	{
	  ldec_monto=0;
	}
	obj.value=uf_convertir(ldec_monto);
}

function catalogo_estpro1()
{
  pagina="sigesp_spi_cat_public_estpro1.php";
  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,resizable=yes,location=no");
}

function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	//ls_estcla = f.hidtipestpro.value;
	ls_estcla     = f.estcla.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_spi_cat_public_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&estcla="+ls_estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no");
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
	codestpro3=f.codestpro3.value;
	denestpro2=f.denestpro2.value;
	//ls_estcla = f.hidtipestpro.value;
	ls_estcla     = f.estcla.value;
	if(<?php print $li_estmodest?>==1)
	{
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(codestpro3=="")&&(denestpro2!=""))
		{
			pagina="sigesp_spi_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
			                       +"&denestpro2="+denestpro2+"&estcla="+ls_estcla+"&tipo=comprobante";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=730,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="sigesp_cat_public_estpro.php?tipo=comprobante";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=730,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
			                       +"&denestpro2="+denestpro2+"&tipo=comprobante";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione la Estructura nivel 2 !!!");
		}
	}		
}

function catalogo_estpro4()
{
	f=document.form1;
	codestpro1 = f.codestpro1.value;
	denestpro1 = f.denestpro1.value;
	codestpro2 = f.codestpro2.value;
	denestpro2 = f.denestpro2.value;
	codestpro3 = f.codestpro3.value;
	denestpro3 = f.denestpro3.value;
	//ls_estcla  = f.hidtipestpro.value;
	ls_estcla  = f.estcla.value;
	if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(denestpro1!="")&&(denestpro2!="")&&(denestpro3!=""))
	{
		pagina="sigesp_spi_cat_public_estpro4.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&estcla="+ls_estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 3");
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
	ls_estcla  = f.estcla.value;
	if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(denestpro1!="")&&(denestpro2!="")&&(denestpro3!="")&&(codestpro4!="")&&(denestpro4!="")&&(codestpro5==""))
	{
		pagina="sigesp_spi_cat_public_estpro5.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
		+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&codestpro4="+codestpro4
		+"&denestpro4="+denestpro4+"&estcla="+ls_estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		pagina="sigesp_cat_public_estprograma.php";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
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
</html>