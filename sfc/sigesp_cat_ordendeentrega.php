<?
/************************************************************************************************************************/
/***********************************  INICIO DE LA PAGINA E INICIO DE SESION   ******************************************/
/************************************************************************************************************************/
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";
}

$la_datemp=$_SESSION["la_empresa"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Factura</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">

<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css"> <!--  para icono de fecha -->

<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
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
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
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
	color: #006699#006699;
}
.style6 {color: #000000}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
<input type="hidden" name="campo" id="campo" value="<? print $ls_campo;?>" >
<input type="hidden" name="orden" id="orden" value="<? print $ls_orden;?>">
<?
/************************************************************************************************************************/
/********************************   LIBRERIAS   *************************************************************************/
/************************************************************************************************************************/
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
$io_utilidad = new sigesp_sfc_class_utilidades();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$ls_codemp=$la_datemp["codemp"];

/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_nomusu="%".$_POST["nomusu"]."%";
	$ls_numord=$_POST["numord"];
	$ls_cedcli=$_POST["cedcli"];
	$ls_codtienda=$_POST["tienda"];
	$ls_codcaja=$_POST["caja"];
	$ls_fecemi=$_POST["txtfecemi"];
	$ls_fecemihas=$_POST["txtfecemihas"];
	$ls_codptocol=$_POST["txtcodptocol"];
	$ls_denptocol=$_POST["txtdenptocol"];	
	
}
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
else
{
	$ls_operacion="";
	$ls_nomusu="";
	$ls_cedcli="";
	$ls_numfac="";
	$ls_fecemi=date("d/m/Y");
	$ls_fecemihas=date("d/m/Y");
	$ls_codtienda="";
	$ls_codcaja="";
	$ls_codptocol="";
	$ls_denptocol="";	
}

if(array_key_exists("tienda",$_REQUEST) and array_key_exists("caja",$_REQUEST))
{
	$ls_codtienda=$_REQUEST["tienda"];
	$ls_codcaja=$_REQUEST["caja"];
}
else{
	$ls_codtienda="";
	$ls_codcaja="";
}
/************************************************************************************************************************/
/***************************   TABLA DREAMWEAVER ************************************************************************/
/************************************************************************************************************************/
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
    <input name="tienda" type="hidden" id="tienda"  value="<? print $ls_codtienda?>">
    <input name="caja" type="hidden" id="caja"  value="<? print $ls_codcaja?>">

</p>
  	 <table width="636" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="632" colspan="2" class="titulo-celda">Cat&aacute;logo de Ordenes de Entrega </td>
    	</tr>
  </table>
	 <br>
	 <table width="629" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">

      <tr>
        <td width="112" height="22"><div align="right">Nombre</div></td>
        <td colspan="2"><div align="left">
          <input name="nomusu" type="text" id="nomusu"  size="60">
        </div></td>
      </tr>
	   <tr>
        <td width="112" height="22"><div align="right">Cédula/Rif</div></td>
        <td colspan="2"><div align="left">
          <input name="cedcli" type="text" id="cedcli"   maxlength="15" size="16">
        </div></td>
      </tr>
      <tr>
        <td width="112" height="22"><div align="right">Nro. Control de Orden </div></td>
        <td colspan="2"><input name="numord" type="text"  id="numord" size="26" maxlength="25"></td>
      </tr>
	  <tr>
        <td width="112" height="22"><div align="right">Fecha Desde </div></td>
        <td width="167"><input name="txtfecemi" type="text"  id="txtfecemi" value="<?php print $ls_fecemi?>" size="11" maxlength="10"  datepicker="true"></td>
        <td width="348">Fecha Hasta
          <label>
          <input name="txtfecemihas" type="text" id="txtfecemihas" value="<?php print $ls_fecemihas?>" size="11" maxlength="10" datepicker="true">
          </label></td>
	  </tr>

	 <tr>
	   <td height="22"><div align="right">Pto. Colocaci&oacute;n </div></td>
	   <td colspan="2"><label>
	     <input name="txtdentpocol" type="text" id="txtdentpocol" size="50">
       </label></td>
	   </tr>
	 <tr>
        <td height="22">&nbsp;</td>
        <td colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?
/************************************************************************************************************************/
/******************   BUSCAR --> BUSCA LA ORDEN DE ENTREGA Y ENVIA LOS DATOS A LA PAGINA DE ORDEN DE ENTREGA ******************************/
/************************************************************************************************************************/
if($ls_operacion=="BUSCAR")
{
	if($ls_codtienda != ""){
		$filtro=" AND f.codtiend='$ls_codtienda' AND f.cod_caja='$ls_codcaja' ";
	}else{
		$filtro="";
	}
	$ls_fecemi=$io_funcion->uf_convertirdatetobd($ls_fecemi);
	$ls_fecemihas=$io_funcion->uf_convertirdatetobd($ls_fecemihas);
	$ls_cadena="SELECT OE.codemp,OE.codordent,OE.numconordent,OE.cod_caja,OE.codciecaj,OE.codtiend,OE.codcli,OE.codptocoldes,
					   OE.numorddes,OE.nunordcom,OE.numfac,OE.numguisad,OE.codestordent,OE.codmotordent,OE.fecemi,OE.codusu,
					   OE.fechordespordent,OE.codempdesordent,OE.fechorllegordent,OE.fechordesgordent,OE.emppagtransp,OE.codveh,
					   OE.codempcho,OE.codconveh,OE.fecdevins,OE.numkilrec,OE.mondesbonreb,OE.monfle,OE.monexe,OE.monexo,
				       OE.monbasimp,OE.montot,OE.obsordent,OE.estatus,CL.razcli,CL.cedcli,EO.denestordent,PC.razptocol,
					   PC.dirptocol,PC.nomconptocol,PC.telmovconptocol
				  FROM siv_orden_entrega OE, sfc_cliente CL,siv_estatus_ordenentrega EO,sfc_puntocolocacion PC
				 WHERE OE.codemp=CL.codemp AND OE.codcli=CL.codcli AND OE.codestordent=EO.codestordent AND OE.codptocoldes=PC.codptocol 
				   AND OE.codcli=PC.codcliperptocol ".$filtro."  AND PC.razptocol ilike '%".$ls_denptocol."%' AND OE.numconordent ilike '%".$ls_numord."%'
				   AND OE.fecemi BETWEEN '".$ls_fecemi."' AND '".$ls_fecemihas."' AND CL.cedcli ilike '%".$ls_cedcli."%'
				 ORDER BY OE.numconordent ";

			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros de Ordenes de Entrega");
			}
			else
			{
				$li_i=0;
				print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><font color=#FFFFFF>No. de Orden</font></td>";
					print "<td><font color=#FFFFFF>No. Control</font></td>";
					print "<td><font color=#FFFFFF>Nombre cliente</font></td>";
					print "<td><font color=#FFFFFF>Cédula/Rif</font></td>";
					print "<td><font color=#FFFFFF>Fecha</font></td>";
					print "<td><font color=#FFFFFF>Monto</font></td>";
					print "<td><font color=#FFFFFF>Estatus</font></td>";
					print "</tr>";
				while($row=$io_sql->fetch_row($rs_datauni))
				{
					$li_i++;
					/*$ls_cotizacion=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$ls_cotizacion;
					$totrow=$io_data->getRowCount("codcli");
					for($z=1;$z<=$totrow;$z++)
					{*/
						print "<tr class=celdas-blancas>";
						$codcli=$row["codcli"];
						$rif=$row["cedcli"];
						$numconordent=$row["numconordent"];
						$codordent=$row["codordent"];
						$fecemi=$row["fecemi"];
						$obsordent=$row["obsordent"];
					    $fecemi=$io_funcion->uf_convertirfecmostrar($fecemi);
						$monto=number_format($row["montot"],'2',',','.');
						$numorddes=$row["numorddes"];
						$nunordcom=$row["nunordcom"];
					    $numguisad=$row["numguisad"];
						$razcli=$row["razcli"];
		                $codestord=$row["codestordent"];
						$denestord=$row["denestordent"];
		                $nombre=$row["razcli"];
						$codmotordent=$row["codmotordent"];
						$codptocol=$row["codptocoldes"];
						$denptocol=$row["razptocol"];
						$dirptocol=$row["dirptocol"];
						$nomconptocol=$row["nomconptocol"];
						$telmovconptocol=$row["telmovconptocol"];
						print "<td><a href=\"javascript:ue_aceptar('$codordent','$numconordent','$codcli','$rif','$razcli','$fecemi','$obsordent','$numorddes','$nunordcom','$numguisad','$codestordent','$codmotordent','$codptocol','$denptocol','$dirptocol','$nomconptocol','$telmovconptocol');\">".$codordent."</a></td>";
						print "<td>".$numconordent."</td>";
						print "<td align=left>".$nombre."</td>";
						print "<td align=left>".$rif."</td>";
						print "<td align=left>".$fecemi."</td>";
						print "<td align=right>".$monto."</td>";
						print "<td align=right>".$estord."</td>";
						print "</tr>";
						//print "faccon:".$estfaccon;
					//}
				}
				if($li_i==0)
				{
					$io_msg->message("No se han registrado Ordenes de Entrega");
				}
		}
}
print "</table>";
/*ob_end_flush();
ob_clean();*/
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/
/********************************************* RUTINAS JAVASCRIPT **************************************************************/
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/

  function ue_aceptar(codordent,numconordent,codcli,rif,razcli,fecemi,obsordent,numorddes,nunordcom,numguisad,codestordent,codmotordent,codptocol,denptocol,dirptocol,nomconptocol,telmovconptocol)
  {
  		opener.ue_cargarordenentrega(codordent,numconordent,codcli,rif,razcli,fecemi,obsordent,numorddes,nunordcom,numguisad,codestordent,codmotordent,codptocol,denptocol,dirptocol,nomconptocol,telmovconptocol );
		close();  	
  }
  
  
  function aceptar(codigo,numfac,numcon,cotizacion,orden,fecemi,conpag,monto,estfac,nombre,estfaccon,esppag,cedcli,observ,numdia)
  {
    opener.f.txtconsulta.value="M";
    opener.ue_cargarfactura(codigo,numfac,numcon,cotizacion,orden,fecemi,conpag,monto,estfac,nombre,estfaccon,esppag,cedcli,observ,numdia);
	close();
  }

  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_ordendeentrega.php";
  f.submit();
  }

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>

</html>
