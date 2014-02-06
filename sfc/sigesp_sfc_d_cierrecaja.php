<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_conexion.php'";
	print "</script>";
}
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Cierre de Caja</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
</script>
<style type="text/css">
<!--
.Estilo3 {color: #6699CC}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="523" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo3">Sistema de Facturaci&oacute;n</span></td>
    <td width="255" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>
	<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>	</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?php
require_once("../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad= new sigesp_c_seguridad();

$arre=$_SESSION["la_empresa"];
$ls_empresa=$arre["codemp"];
$ls_usuario=$_SESSION["la_logusr"];
$ls_sistema="SFC";
$ls_ventanas="sigesp_sfc_d_cierrecaja.php";

$la_seguridad["empresa"]=$ls_empresa;
$la_seguridad["logusr"]=$ls_usuario;
$la_seguridad["sistema"]=$ls_sistema;
$la_seguridad["ventanas"]=$ls_ventanas;

if (array_key_exists("permisos",$_POST))
{

		$ls_permisos=             $_POST["permisos"];
		$la_permisos["leer"]=     $_POST["leer"];
		$la_permisos["incluir"]=  $_POST["incluir"];
		$la_permisos["cambiar"]=  $_POST["cambiar"];
		$la_permisos["eliminar"]= $_POST["eliminar"];
		$la_permisos["imprimir"]= $_POST["imprimir"];
		$la_permisos["anular"]=   $_POST["anular"];
		$la_permisos["ejecutar"]= $_POST["ejecutar"];

}
else
{
	$la_permisos["leer"]="";
	$la_permisos["incluir"]="";
	$la_permisos["cambiar"]="";
	$la_permisos["eliminar"]="";
	$la_permisos["imprimir"]="";
	$la_permisos["anular"]="";
	$la_permisos["ejecutar"]="";
	$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_usuario,$ls_sistema,$ls_ventanas,$la_permisos);
}
require_once("class_folder/sigesp_sfc_c_cajero.php");
require_once("class_folder/sigesp_sfc_c_cierre.php");
require_once("class_folder/sigesp_sfc_c_int_spi.php");
require_once("class_folder/sigesp_sfc_c_int_data.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("class_folder/sigesp_sfc_c_secuencia.php");
require_once("../shared/class_folder/class_funciones.php");
//require_once("class_folder/sigesp_sfc_c_factura.php");



$io_secuencia=new sigesp_sfc_c_secuencia();
$io_datastore= new class_datastore();
$io_datascg= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_data_scg=new class_datastore();
$io_cierre=new sigesp_sfc_c_cierre();
$io_funcion = new class_funciones();
//$io_factura=new  sigesp_sfc_c_factura();


$io_msg=new class_mensajes();
$io_cajero=new sigesp_sfc_c_cajero();
$io_intspi=new sigesp_sfc_c_int_spi();
$io_intdat=new sigesp_sfc_c_int_data();
$ls_total_facturado=0;
$ls_total_cobrado=0;
$ls_total_notfac=0;
$ls_total_notcob=0;


if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codusu="%".$_POST["txtcodusu"]."%";
	$ls_codusuV=$_POST["txtcodusu"];
	$ls_nomusuV=$_POST["txtnomusu"];
	$ls_codcie=$_POST["hidcodcie"];
	$ls_feccie=$_POST["txtfeccie"];

	$ls_hidstatus=$_POST["hidstatus"];
	if(array_key_exists("chkprecie",$_POST))
	{
       $ls_precie="V";
    }
	else
	{
	   $ls_precie="F";
	}
	$ls_codcaj=$_POST["txtcodcaj"];
	$ls_nomcaj=$_POST["txtnomcaj"];

}
else
{
	$ls_operacion="";
	$ls_codusu="";
	$ls_codusuV="";
	$ls_nomusuV="";
	$ls_nomusu="";
	$ls_codcie="";
	$ls_precie="";
	$ls_feccie=date('d/m/Y');
	$ls_hidstatus="";
	$ls_codcaj="";
	$ls_nomcaj="";
}

?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if ($ls_permisos)
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer value='$la_permisos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir value='$la_permisos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar value='$la_permisos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_permisos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_permisos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular value='$la_permisos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_permisos[ejecutar]'>");
}
else
{
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>

    <table width="617" height="443" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="560" height="195"><div align="center">
          <table width="509"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td colspan="3" class="titulo-ventana">Cierre de caja </td>
            </tr>
            <tr>
              <td >
			  <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
			  <input name="hidstatus" type="hidden" id="hidstatus" value="<? print $ls_hidstatus?>">
			  <input name="hidcodcie" type="hidden" id="hidcodcie" value="<?php print $ls_codcie ?>"></td>
              <td colspan="2" >&nbsp;</td>
            </tr>

			<tr>
			  <td height="22" align="right">&nbsp;</td>
			  <td width="341" ><div align="right">Fecha</div></td>
		      <td width="81" ><input name="txtfeccie" type="text" id="txtfeccie"  style="text-align:left" value="<?php print $ls_feccie?>" size="11" maxlength="10"  datepicker="true"  readonly="true"></td>
			</tr>

			<tr>
              <td width="85" height="22" align="right">Cajero </td>
              <td colspan="2" ><input name="txtcodusu" type="text" id="txtcodusu" style="text-align:center " value="<?php print $ls_codusuV?>" size="15" maxlength="15"  readonly="true">
			  <!-- javascript:ue_catusuario(); -->
              <a href="javascript:ue_catusuario();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de tipos de unidades"></a>
              <input name="txtnomusu" type="text" id="txtnomusu" class="sin-borde" size="40" readonly="true" value="<?php print $ls_nomusuV?>" ></td>
            </tr>
            <tr>
              <td width="85" height="22" align="right">Caja </td>
              <td colspan="2" ><input name="txtcodcaj" type="text" id="txtcodcaj" style="text-align:center " value="<?php print $ls_codcaj?>" size="15" maxlength="15"  readonly="true">
			  <!-- javascript:ue_catusuario(); -->
              <a href="javascript:ue_catcaja();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de tipos de unidades"></a>
              <input name="txtnomcaj" type="text" id="txtnomcaj" class="sin-borde" size="40" readonly="true" value="<?php print $ls_nomcaj?>" ></td>
            </tr>
			<tr>
			  <td height="22" align="right">Pre-cierre</td>
			  <td colspan="2" ><label>
			    <input type="checkbox" name="chkprecie" value="checkbox">
			  </label></td>
		    </tr>

            <tr>
              <td width="85" height="22" align="right">&nbsp;</td>
              <td colspan="2" ><div align="left">
                <p>&nbsp;</p>
                <p><a href="javascript:ue_procesar();">Procesar<img src="Imagenes/ejecutar.gif" width="20" height="20" border="0"></a></p>
              </div></td>
            </tr>

            <tr>
              <td height="8" colspan="3"><table width="415" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                <tr>
                  <td height="17" colspan="2" align="right" class="titulo-ventana">Resumen de Devoluci&oacute;n (CANCELADAS POR CAJA) </td>
                </tr>
                <tr>
                  <td width="142" align="right">&nbsp;</td>
                  <td >&nbsp;</td>
                </tr>
				<tr>
					<td height="17" align="center" class="titulo-ventana">Forma de Pago</td>
					<td height="17" align="center" class="titulo-ventana">Total</td>
			      </tr>
<?php
if($ls_operacion=="PROCESAR")
{
  $lb_cajero_valido=$io_cierre->uf_validar_cajero($ls_usuario);


  if(!$lb_cajero_valido)
  {
	 $lb_valido_logusr=1;
	 $io_msg->message ("El usuario no es personal de la tienda no puede generar CIERRES DE CAJA!");


  }
   else
  {

   $ls_fecprueba   =substr($ls_feccie,6,4)."-".substr($ls_feccie,3,2)."-".substr($ls_feccie,0,2);


   $lb_validocierre=$io_cierre->uf_select_cierre_caja($ls_fecprueba,$ls_codcaj,&$fechacierre);


   if ($lb_validocierre)
   {
  	 $io_msg->message ("Ya fue realizado el cierre para este Dia!!!");

   }
   else
   {
	   if($ls_precie=="F")
	   {

		   $io_secuencia->uf_obtener_secuencia("numerocierre",&$ls_codcie);
		   $ls_codcie=substr($ls_codcie,8,strlen($ls_codcie));
		   $lb_valido=$io_cierre->uf_guardar_cierre($ls_codcie,$ls_codusuV,$ls_feccie,$ls_codcaj,$ls_total_general,$la_seguridad);
	   }
	   elseif($ls_precie=="V")
	   {
		   $lb_valido=true;
	   }

		  if($lb_valido)
		   {
	            if($ls_precie=="F")
				{
				  $io_cierre->uf_actualizar_estnot("P",$ls_codcie,$ls_fecprueba,$la_seguridad);
				  $ls_sql="SELECT  SUM(monto) as total" .
				  		  " FROM    sfc_nota " .
				  		  " WHERE   tipnot='CXP' " .
				  		  " AND     estnota='C' " .
				  		  " AND     nro_documento like 'DEV%' " .
				  		  " AND     estcie='P' " .
				  		  " AND     codciecaj='".$ls_codcie."'" .
						  " AND codtiend='".$ls_codtie."' ";
				}
				elseif($ls_precie=="V")
				{
				  $ls_sql="SELECT  SUM(monto) as total" .
				  		  " FROM    sfc_nota " .
				  		  " WHERE   tipnot='CXP' AND estnota='C' AND fecnot='".$ls_fecprueba."' " .
				  		  " AND     nro_documento like 'DEV%' AND estcie='N'" .
				  		  " AND codtiend='".$ls_codtie."'";
				}
				//print $ls_sql."<br>".$ls_precie."<br>";
				$rs_data=$io_sql->select($ls_sql);
				if($row=$io_sql->fetch_row($rs_data))
				 {
				 	$ls_total_devuelto=$row["total"];
					$ls_total_devueltocancelado=number_format($row["total"],2, ',', '.');
				 }
					$ls_total_devueltocancelado=number_format($row["total"],2, ',', '.');
				 }
				?>
				<tr>
                   <td height="22" align="center" >Nota de Credito (por Devoluci&oacute;n)</td>
                   <td align="center"><?php print $ls_total_devueltocancelado ?></td>
                  </tr>
                <tr>
                  <td width="142" align="right">&nbsp;</td>
                  <td >&nbsp;</td>
                </tr>
                <tr>
                  <td height="17" colspan="2" align="right" class="titulo-ventana">Resumen de Factura </td>
                </tr>
                <tr>
                  <td width="142" align="right">&nbsp;</td>
                  <td >&nbsp;</td>
                </tr>
				<tr>
					<td height="17" align="center" class="titulo-ventana">Forma de pago</td>
					<td height="17" align="center" class="titulo-ventana">Total</td>
			      </tr>
<?php
			if($ls_precie=="F")
			{
				$io_cierre->uf_actualizar_estfaccaj($ls_codcaj,"P",$ls_codcie,$ls_fecprueba,$la_seguridad);
				$ls_sql="SELECT fp.codforpag ,fp.denforpag, SUM(i.monto) as total,SUM(f.montopar)" .
						" FROM  sfc_formapago fp,sfc_instpago i,sfc_factura f" .
						" WHERE i.codforpag=fp.codforpag AND i.numfac=f.numfac" .
						" AND f.estfac='P' AND f.codciecaj='".$ls_codcie."'" .
						" AND f.cod_caja like '".$ls_codcaj."' AND f.estfaccon<>'A'" .
						" AND fp.codforpag <>'04' AND i.codtiend=f.codtiend AND f.codtiend='".$ls_codtie."' " .
						" AND i.codtiend='".$ls_codtie."' GROUP BY fp.codforpag,fp.denforpag";

			}
			elseif($ls_precie=="V")
			{
			    $ls_sql="SELECT fp.codforpag ,fp.denforpag, SUM(i.monto) as total,SUM(f.montopar)" .
			    		" FROM  sfc_formapago fp,sfc_instpago i,sfc_factura f" .
			    		" WHERE i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.cod_caja like '".$ls_codcaj."'" .
			    		" AND DATE(f.fecemi)='".$ls_fecprueba."' AND f.estfaccon<>'A' AND f.estfac='N' AND fp.codforpag <>'04'" .
			    		" AND i.codtiend=f.codtiend AND f.codtiend='".$ls_codtie."' AND i.codtiend='".$ls_codtie."'" .
			    		" GROUP BY fp.codforpag,fp.denforpag";
			}
                //print $ls_sql."<br><br>";
				$rs_data=$io_sql->select($ls_sql);
				if($row=$io_sql->fetch_row($rs_data))
				 {
					$data=$io_sql->obtener_datos($rs_data);

					$io_datastore->data=$data;
					$li_totrow=$io_datastore->getRowCount("codforpag");
					for($li_z=1;$li_z<=$li_totrow;$li_z++)
					{

				 		$ls_desforpag=$data["denforpag"][$li_z];

						$ls_codforpag=$data["codforpag"][$li_z];

						if ($ls_codforpag=='01')
						{
							$ls_totalefectivo=$ls_totalefectivo+ $data["total"][$li_z];
						}
						else if($ls_codforpag=='02')
						{
							$ls_totalcheque=$ls_totalcheque+ $data["total"][$li_z];
						}
						else if($ls_codforpag=='03')
						{
							if (-$data["montopar"][$i+1]<$data["total"][$i+1])
							{
								$ls_totalcredito=$ls_totalcredito+ ($data["total"][$li_z]+$data["montopar"][$li_z]);
							}
						}
						else if($ls_codforpag=='05')
						{
							$ls_totaldeposito=$ls_totaldeposito+ $data["total"][$li_z];
						}
						else if($ls_codforpag=='07' or $ls_codforpag=='08')
						{
							$ls_totaldebito=$ls_totaldebito+ $data["total"][$li_z];
						}
						else
						{}

						if ($ls_codforpag=='03' and -$data["montopar"][$i+1]<$data["total"][$i+1])
						{
							$ls_total_facturado= $ls_total_facturado+$data["total"][$li_z]+$data["montopar"][$i+1];
						}else{
							$ls_total_facturado=$ls_total_facturado + $data["total"][$li_z];
						}

						$ls_total=number_format($data["total"][$li_z],2, ',', '.');

						$ls_total=number_format($data["total"][$li_z],2, ',', '.');
				?>

                <tr>
					<td height="26" align="center" style=""><?php print $ls_desforpag ?></td>
					<td height="26" align="center" td><?php print $ls_total ?></td>
                  </tr>
				<?php
				   }

				?>
				<tr>
                   <td height="22" align="right" class="titulo-ventana">Total facturado </td>
                   <td  class="titulo-ventana"><span class="titulo-ventana"><?php print number_format($ls_total_facturado,2, ',', '.') ?></span></td>
                  </tr>
<?php
                }
?>
                <tr>
                  <td width="142" align="right">&nbsp;</td>
                  <td >&nbsp;</td>
                </tr>
                <tr>
                  <td height="17" colspan="2" align="right" class="titulo-ventana">Resumen de Cobranza </td>
                </tr>
                <tr>
                  <td width="142" align="right">&nbsp;</td>
                  <td >&nbsp;</td>
                </tr>
				<tr>
					<td height="17" align="center" class="titulo-ventana">Forma de pago</td>
					<td height="17" align="center" class="titulo-ventana">Total</td>
			      </tr>
<?php
		if($ls_precie=="F")
		{
			$io_cierre->uf_actualizar_estcobcaj($ls_codcaj,"P",$ls_codcie,$ls_fecprueba,$la_seguridad);
			$ls_sql="SELECT fp.codforpag ,fp.denforpag, SUM(ic.monto) as total" .
					" FROM   sfc_formapago fp,sfc_instpagocob ic" .
					" WHERE  ic.codforpag=fp.codforpag" .
					" AND ic.numcob  IN (SELECT c.numcob FROM sfc_cobrocartaorden c" .
					" WHERE  c.estcob='P' AND c.codciecaj='".$ls_codcie."' " .
					" AND c.codtiend='".$ls_codtie."'  AND c.feccob='".$ls_fecprueba."' AND c.cod_caja like '".$ls_codcaj."') " .
					" AND ic.codtiend='".$ls_codtie."'" .
					" GROUP BY fp.codforpag,fp.denforpag";
		}
		elseif($ls_precie=="V")
		{
			$ls_sql="SELECT fp.codforpag ,fp.denforpag, SUM(ic.monto) as total" .
					" FROM   sfc_formapago fp,sfc_instpagocob ic WHERE ic.codforpag=fp.codforpag" .
					" AND  ic.numcob IN (SELECT c.numcob FROM sfc_cobro_cliente c" .
					" WHERE  c.feccob='".$ls_fecprueba."' AND    c.cod_caja like '".$ls_codcaj."'" .
					" AND  c.estcob<>'P' AND c.codtiend='".$ls_codtie."') AND ic.codtiend='".$ls_codtie."' " .
					" GROUP BY fp.codforpag,fp.denforpag";
		}
                  //print $ls_sql."<br>".$ls_precie."<br>";
			  	  $rs_data=$io_sql->select($ls_sql);
				  if($row=$io_sql->fetch_row($rs_data))
				  {
					$data=$io_sql->obtener_datos($rs_data);
					$io_datastore->data=$data;
					$li_totrow=$io_datastore->getRowCount("codforpag");
					for($li_z=1;$li_z<=$li_totrow;$li_z++)
					{
				 		$ls_desforpag=$data["denforpag"][$li_z];
						$ls_codforpag=$data["codforpag"][$li_z];
						if ($ls_codforpag=='01')
						{
							$ls_totalefectivo=$ls_totalefectivo+ $data["total"][$li_z];
						}
						else if ($ls_codforpag=='02')
						{
							$ls_totalcheque=$ls_totalcheque+ $data["total"][$li_z];
						}
						else if ($ls_codforpag=='03')
						{
							$ls_totalcredito=$ls_totalcredito+ $data["total"][$li_z];
						}
						else if ($ls_codforpag=='05')
						{
							$ls_totaldeposito=$ls_totaldeposito+ $data["total"][$li_z];
						}
						else if ($ls_codforpag=='07' or $ls_codforpag=='08')
						{
							$ls_totaldebito=$ls_totaldebito+ $data["total"][$li_z];
						}

						$ls_total=number_format($data["total"][$li_z],2, ',', '.');
						$ls_total=number_format($data["total"][$li_z],2, ',', '.');
						$ls_total_cobrado=$ls_total_cobrado + $data["total"][$li_z];
				?>

                <tr>
					<td height="26" align="center" style=""><?php print $ls_desforpag ?></td>
					<td height="26" align="center" td><?php print $ls_total ?></td>
                  </tr>
				<?php
				   }

				?>
				<tr>
                   <td height="22" align="right" class="titulo-ventana">Total cobrado </td>
                   <td  class="titulo-ventana"><span class="titulo-ventana"><?php print number_format($ls_total_cobrado,2, ',', '.') ?></span></td>
                  </tr>
<?php
  }
	print "<script language=JavaScript>document.form1.hidcodcie.value='".$ls_codcie."' </script>";


	$ls_total_cierre=($ls_totalefectivo)-$ls_total_devuelto;
	$ls_total_deposito=$ls_totalefectivo+$ls_totalcheque;


    if($ls_precie=="V")
	{
	  $io_msg->message ("Acaba de ejecutar un pre cierre!!!");
	}
	else
	{
		$io_cierre->uf_monto_cierre($ls_codcie,$ls_total_cierre);
		$ls_contenido="";
		$io_intspi->initproc($ls_rutarc,$ls_codcie);
    }

  }
}


	if(($ls_precie=="V") OR ($lb_valido_logusr==1))
	{
	  $ls_operacion="";
	}
	else
	{
		 $ls_operacion="VER";
    }


 /* print("<script language=JavaScript>");
  print("pagina='sigesp_sfc_d_cierrecaja.php';");
  print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
  print("</script>");*/

}
elseif($ls_operacion=="ue_eliminar")
{
    $lb_valido=$io_cierre->uf_reversar_cierre($ls_codcie,$la_seguridad);
	if($lb_valido)
	{
        $io_msg->message ("El Reverso del Cierre fue realizado");
	}
	else
	{
      $io_msg->message ("El Reverso del Cierre No fue realizado");
	}
}
$ls_total_general=($ls_total_facturado+$ls_total_cobrado)-$ls_total_devuelto;
?>
				<tr>
                  <td width="142" align="right">&nbsp;</td>
                  <td >&nbsp;</td>
                </tr>
				<tr class="celdas-grises">
					<td height="17" align="left" class="texto-azul">Total Efectivo</td>
					<td height="17" align="right" class="texto-azul"><?php print number_format($ls_totalefectivo,2, ',', '.') ?></td>
			      </tr>
				<tr class="celdas-grises">
					<td height="17" align="left" class="texto-azul">Total Cheque</td>
					<td height="17" align="right" class="texto-azul"><?php print number_format($ls_totalcheque,2, ',', '.') ?></td>
			      </tr>

				  <tr class="celdas-grises">
					<td height="17" align="left" class="texto-azul">Total Nota de Credito</td>
					<td height="17" align="right" class="texto-azul"><?php print number_format($ls_totalcredito,2, ',', '.') ?></td>
			      </tr>
				  <tr class="celdas-grises">
					<td height="17" align="left" class="texto-azul">Total Deposito</td>
					<td height="17" align="right" class="texto-azul"><?php print number_format($ls_totaldeposito,2, ',', '.') ?></td>
			      </tr>
				  <tr class="celdas-grises">
					<td height="17" align="left" class="texto-azul">Total Nota de Debito</td>
					<td height="17" align="right" class="texto-azul"><?php print number_format($ls_totaldebito,2, ',', '.') ?></td>
			      </tr>

				  <tr class="celdas-grises">
					<td height="17" align="left" class="texto-rojo">Total Devoluci&oacute;n</td>
					<td height="17" align="right" class="texto-rojo"><?php print '-'.number_format($ls_total_devuelto,2, ',', '.') ?></td>
			      </tr>


				  <tr class="titulo-celda">
					<td height="17" align="left">Total General</td>
					<td height="17" align="right"><?php print number_format($ls_total_general,2, ',', '.') ?></td>
			      </tr>
				   <tr class="celdas-grises">
					<td height="17" align="left" class="texto-azul">Total Efectivo en Caja</td>
					<td height="17" align="right" class="texto-azul"><?php print number_format($ls_total_cierre,2, ',', '.') ?></td>
			      </tr>
				  <tr class="celdas-grises">
					<td height="17" align="left" class="texto-azul">MONTO TOTAL A DEPOSITAR </td>
					<td height="17" align="right" class="texto-azul"><?php print number_format($ls_total_deposito,2, ',', '.') ?></td>
			      </tr>
			  </table>

              <p>&nbsp;</p></td>
            </tr>
          </table>
          <p>&nbsp;</p>
        </div></td>
      </tr>
  </table>

<?php
if($ls_operacion=="VER")
{
        $ls_operacion="";
        $ls_fecprueba   =substr($ls_feccie,6,4)."-".substr($ls_feccie,3,2)."-".substr($ls_feccie,0,2);
        $lb_validocierre=$io_cierre->uf_select_cierre_caja($ls_fecprueba,$ls_codcaj,&$fechacierre);

		$ls_sql_fac="SELECT f.numfac,f.codcli,c.cedcli,c.razcli,fp.codforpag,fp.denforpag,f.montopar,i.monto as montofac " .
				" FROM sfc_cliente c,sfc_formapago fp,sfc_instpago i,sfc_factura f " .
				" WHERE f.codcli=c.codcli AND i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.estfac='P' AND DATE(f.fecemi)='".$ls_fecprueba."' " .
				" AND f.codciecaj='".$ls_codcie."' AND i.codforpag<>'04' AND f.estfaccon<>'A' AND i.codtiend='".$ls_codtie."'" .
				" AND f.codtiend='".$ls_codtie."' AND i.codtiend=f.codtiend AND c.codcli=i.codcli AND i.codcli=f.codcli " .
				" ORDER BY numfac ASC;";

		$ls_sql_cob="SELECT ic.numinst,co.numcob,co.codcli,c.cedcli,c.razcli,fp.codforpag as codforpagcob,fp.denforpag,ic.monto " .
				" FROM sfc_cliente c,sfc_formapago fp,sfc_instpagocob ic,sfc_cobro_cliente co " .
				" WHERE  co.codcli=c.codcli AND ic.codforpag=fp.codforpag AND ic.numcob=co.numcob AND co.estcob='P' and co.moncob<>0 " .
				" AND co.feccob='".$ls_fecprueba."' AND co.codciecaj='".$ls_codcie."' AND co.estcob<>'A' AND ic.codcli=c.codcli" .
				" AND ic.codtiend='".$ls_codtie."' AND co.codtiend='".$ls_codtie."' AND ic.codtiend=co.codtiend" .
				" ORDER BY co.numcob ASC;";


		$ls_sql_dev="SELECT c.cedcli as cedula,c.razcli as nombre,n.numnot as numnota,n.nro_documento as codforpagdev,n.dennot,n.monto as montodev " .
				" FROM sfc_nota n,sfc_cliente c " .
				" WHERE tipnot='CXP' AND estnota='C' AND nro_documento like 'DEV%' AND n.estcie='P' AND c.codcli=n.codcli " .
				" AND n.fecnot='".$ls_fecprueba."' AND n.codciecaj='".$ls_codcie."' AND n.codtiend='".$ls_codtie."' ORDER BY numnota;";

	//print $fechacierre."<br>".$ls_sql_fac."<br>".$ls_sql_cob."<br>".$ls_sql_dev;


?>
     <script language="JavaScript">
   	 	var ls_sql_fac="<?php print $ls_sql_fac; ?>";
		var ls_sql_cob="<?php print $ls_sql_cob; ?>";
		var ls_sql_dev="<?php print $ls_sql_dev; ?>";
		var ls_feccie ="<?php print $fechacierre; ?>";
		pagina_F="reportes/sigesp_sfc_rep_cierrecaja.php?sql_f="+ls_sql_fac+"&sql_c="+ls_sql_cob+"&ls_sql_dev="+ls_sql_dev+"&ls_feccie="+ls_feccie;
	  	popupWin(pagina_F,"Reporte Cierre",580,700);
	  </script>
<?php
}
/************************************************************************************************************************/
/***************************************   FIN DEL FORMULARIO  **********************************************************/
/************************************************************************************************************************/
?>
</form>
</body>
<script language="JavaScript">
function ue_procesar()
{
  f=document.form1;
  if(f.txtcodcaj.value=="" || f.txtcodusu.value=="")
  {
  	alert("Seleccione el cajero y la caja de la cual desea hacer el cierre");
  }
  else
  {
  	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		if (lb_status!="C")
		{
			f.hidstatus.value="C";
		}

		f.operacion.value="PROCESAR";
		f.action="sigesp_sfc_d_cierrecaja.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
  }
}

function ue_ver()
{
  f=document.form1;
  f.operacion.value="VER";
  f.action="sigesp_sfc_d_cierrecaja.php";
  f.submit();
}

function ue_nuevo()
{
	f=document.form1;

	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		f.operacion.value="ue_nuevo";
		f.txtcodusu.value="";
		f.txtnomusu.value="";
		f.txtcodtie.value="";
		f.txtnomtie.value="";
		f.action="sigesp_sfc_d_cajero.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_guardar()
{
	f=document.form1;

	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		if (lb_status!="C")
		{
			f.hidstatus.value="C";
		}

		with(f)
		{
			if (ue_valida_null(txtcodusu,"Usuario")==false)
			{
				txtcodusu.focus();
			}
			else
			{
				if (ue_valida_null(txtcodtie,"Tienda")==false)
				{
				  txtcodtie.focus();
				}
				else
				{
					f.operacion.value="ue_guardar";
					f.action="sigesp_sfc_d_cajero.php";
					f.submit();
				}
			}
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_eliminar()
{
	f=document.form1;

	li_eliminar=f.eliminar.value;
	if( li_eliminar==1)
	{
		with(f)
		{
			if (ue_valida_null(txtcodusu,"Usuario")==false)
			{
				txtcodusu.focus();
			}
			else
			{
				if (confirm("� Esta seguro de eliminar este registro ?"))
				{
					f.operacion.value="ue_eliminar";
					f.action="sigesp_sfc_d_cierrecaja.php";
					f.submit();
				}
				else
				{
					f=document.form1;
					f.action="sigesp_sfc_d_cierrecaja.php";
					alert("Eliminaci�n Cancelada !!!");
					f.txtcodusu.value="";
					f.txtnomusu.value="";
					f.operacion.value="";
					f.submit();
				}
			}
		}
    }
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_buscar()
{
	f=document.form1;

	li_leer=f.leer.value;
	if(li_leer==1)
	{
		f.operacion.value="";
		pagina="sigesp_cat_cierre.php";
		popupWin(pagina,"catalogo",650,300);

	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_cargarcaja(codtienda,ls_destienda,codcaja,desccaja,precot,prefac,predev,preped,sercot,serfac,serdev,serped,sernot,sercon,formalibre,precob,sercob)/*(codcaja,desccaja,precot,prefac,predev,preped,sercot,serfac,serdev,serped,sernot,sercon,formalibre,precob,sercob)*/

{
	f=document.form1;
	f.txtcodcaj.value = codcaja;
	f.txtnomcaj.value = desccaja;


}
function ue_catcaja(){
	pagina="sigesp_cat_caja.php";
	popupWin(pagina,"catalogo",650,300);
}
function ue_catusuario()
{
	f=document.form1;
	f.operacion.value="";
	pagina="../sss/sigesp_sss_cat_usuarios.php?destino=Reporte";
	popupWin(pagina,"catalogo",520,200);
}
function ue_cattienda()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_tienda.php";
	popupWin(pagina,"catalogo",520,200);
}
function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar)
{
	f=document.form1;
	f.txtcodtie.value=codtie;
	f.txtnomtie.value=nomtie;

}
function ue_cargarcajero(codusu,nomusu,codtie,nomtie)
{
	f=document.form1;
	f.txtcodusu.value=codusu;
	f.txtnomusu.value=nomusu;
}
function ue_cargarcierre(codcie,codusu,feccie,nomusu,codcaja,dencaja)
{
	f=document.form1;
	f.hidcodcie.value=codcie;
	f.txtfeccie.value=feccie;
	f.txtcodusu.value=codusu;
	f.txtnomusu.value=nomusu;
	f.txtcodcaj.value = codcaja;
	f.txtnomcaj.value = dencaja;
	f.hidstatus.value="C";

	f.operacion.value="VER";
	f.submit();
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>

