<?
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";
}

$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas por Cobrar</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css"> <!--  para icono de fecha -->

<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<!-- script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script -->
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
$ls_codtie=$_SESSION["ls_codtienda"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_nomcli="%".$_POST["nomcli"]."%";
	$ls_cedcli="%".$_POST["cedcli"]."%";
	$ls_numnot="%".$_POST["numnot"]."%";
	$ls_codtienda=$_POST["txtcodtienda"];
	$ls_destienda=$_POST["txtdestienda"];
	$ls_fecemi=$_POST["txtfecemi"];
	$ls_fecemi=$io_funcion->uf_convertirdatetobd($ls_fecemi);
	if ($ls_fecemi=="")
	{
		$ls_fecemi="%/".$ls_fecemi."%";
	}
}
else
{
	$ls_operacion="";
	$ls_nomcli="";
	$ls_cedcli="";
	$ls_numnot="";
	$ls_codtienda="";
	$ls_destienda="";
	$ls_fecemi="%%";
}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">

</p>
  	 <table width="700" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="696" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas por Cobrar </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">

      <tr>
        <td width="114" height="30"><div align="right">Unidad Operacitiva de Suministro </div></td>
        <td>
        	<input name="txtcodtienda" type="hidden" id="txtcodtienda"  value="<? print $ls_codtienda?>" >
        	<input name="txtdestienda" type="text" id="txtdestienda"  value="<? print $ls_destienda?>" size="50" maxlength="250">
        	<a href="javascript:ue_buscartienda();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a>
        </td>

      </tr>

      <tr>
        <td width="114"><div align="right">Nro. nota</div></td>
        <td width="384"><div align="left">
          <input name="numnot" type="text" id="numnot"  size="60">
        </div></td>
      </tr>

	  <td>&nbsp;</td>

	 <tr>
       <td width="114"><div align="right">Nombre</div></td>
        <td width="384"><div align="left">
          <input name="nomcli" type="text" id="nomcli"  size="60">
       </div></td>
      </tr>

	 <td>&nbsp;</td>

	 <tr>
       <td width="114"><div align="right">Cédula/Rif</div></td>
        <td width="384"><div align="left">
          <input name="cedcli" type="text" id="cedcli"  size="18" maxlength="15">
       </div></td>
      </tr>

	   <td>&nbsp;</td>

	 <tr>
       <td width="114"><div align="right">Fecha</div></td>
        <td width="384"><input name="txtfecemi" type="text"  id="txtfecemi"  datepicker="true" size="11" maxlength="10"></td>
      </tr>

	  <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?
if($ls_operacion=="BUSCAR")
{
$ls_cxc="CXC";
if($ls_codtienda != ""){
	$filtro=" AND n.codtiend='$ls_codtienda' ";
}else{
	$filtro="";
}
//print $ls_cxc;

if ($ls_fecemi=="%/%")
{
	$ls_cadena=" SELECT DISTINCT cl.codcli,cl.razcli,cl.cedcli,n.codtiend,n.numnot,n.dennot,n.tipnot,n.fecnot,n.monto,n.nro_documento,n.estnota,n.codtiend,t.dentie ".
				" FROM sfc_cliente cl,sfc_nota n,sfc_tienda t,sfc_factura f ".
				" WHERE n.codtiend='".$ls_codtie."' and n.codcli=cl.codcli and n.tipnot like '%".$ls_cxc."%' and cl.razcli ilike '%".$ls_nomcli."%' ".
				" AND n.numnot ilike '%".$ls_numnot."%' AND cl.cedcli ilike '%".$ls_cedcli."%' AND t.codtiend=n.codtiend " .$filtro.
				"  order by cl.codcli ASC";
}
elseif($ls_fecemi<>"%/%")
{
	$ls_cadena=" SELECT DISTINCT cl.codcli,cl.razcli,cl.cedcli,n.codtiend,n.numnot,n.dennot,n.tipnot,n.fecnot,n.monto,n.nro_documento,n.estnota,n.codtiend,t.dentie ".
				" FROM sfc_cliente cl,sfc_nota n,sfc_tienda t,sfc_factura f ".
				" WHERE n.codtiend='".$ls_codtie."' and n.codcli=cl.codcli and n.tipnot like '%".$ls_cxc."%' and cl.razcli ilike '%".$ls_nomcli."%'    ".
				" AND n.numnot ilike '%".$ls_numnot."%' AND cl.cedcli ilike '%".$ls_cedcli."%' AND n.fecnot='".$ls_fecemi."' AND t.codtiend=n.codtiend ".
				"  ".$filtro." order by cl.codcli ASC";
}
    //print $ls_cadena."<br>";
	$rs_datauni=$io_sql->select($ls_cadena);

	if($rs_datauni==false&&($io_sql->message!=""))
	{
		$io_msg->message("No hay registros");
	}
	else
	{
		if($row=$io_sql->fetch_row($rs_datauni))
		{
			print "<table width=650 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td><font color=#FFFFFF>No. Nota</font></td>";
			print "<td><font color=#FFFFFF>Nombre Cliente</font></td>";
			print "<td><font color=#FFFFFF>Cédula/Rif</font></td>";
			print "<td><font color=#FFFFFF>Fecha</font></td>";
			print "<td><font color=#FFFFFF>Denominación</font></td>";
			print "<td><font color=#FFFFFF>Monto</font></td>";
			print "<td><font color=#FFFFFF>Estatus</font></td>";

			$la_nota=$io_sql->obtener_datos($rs_datauni);
			$io_data->data=$la_nota;
			$totrow=$io_data->getRowCount("numnot");

			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";

				$codcli=$io_data->getValue("codcli",$z);
				$nomcli=$io_data->getValue("razcli",$z);
				$cedcli=$io_data->getValue("cedcli",$z);
				$numnot=$io_data->getValue("numnot",$z);
				$dennot=$io_data->getValue("dennot",$z);
				$tipnot=$io_data->getValue("tipnot",$z);
                $fecnot=$io_data->getValue("fecnot",$z);
				$fecnot=$io_funcion->uf_convertirfecmostrar($fecnot);

				$monto=$ld_monto=number_format($io_data->getValue("monto",$z),2, ',', '.');
				$estnota=$io_data->getValue("estnota",$z);
				$nro_factura=$io_data->getValue("nro_documento",$z);
				$cedcli=$io_data->getValue("cedcli",$z);
				$codtiend=$io_data->getValue("codtiend",$z);
				$tienda=$io_data->getValue("dentie",$z);
				$caja=$io_data->getValue("cod_caja",$z);

				if($estnota=='P')
				{
					$nota="Pendiente";
				}
				elseif($estnota=='C')
				{
					$nota="Cancelada";
				}
				print "<td><a href=\"javascript: aceptar('$nomcli','$codcli','$numnot','$dennot','$tipnot','$fecnot','$monto','$estnota','$nro_factura','$cedcli','$codtiend','$tienda','$caja');\">".$numnot."</a></td>";
				print "<td align=left>".$nomcli."</td>";
				print "<td align=left>".$cedcli."</td>";
				print "<td align=left>".$fecnot."</td>";
				print "<td align=left>".$dennot."</td>";
				print "<td align=left>".$monto."</td>";
				print "<td align=left>".$nota."</td>";
				print "</tr>";
			}
			//print $codcli."/".$numnot."/".$dennot."/".$tipnot."/".$fecnot."/".$monto;
		}
		else
		{
			$io_msg->message("No se han registrado Notas de crédito");
		}
	}
}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

function aceptar(nomcli,codcli,numnot,dennot,tipnot,fecnot,monto,estnota,nro_factura,cedcli,codtiend,tienda,caja)
{
	//nomcli,codcli,cedcli,numnot,dennot,tipnot,fecnot,monto,estnota,nro_factura
	opener.ue_cargarnota(nomcli,codcli,numnot,dennot,tipnot,fecnot,monto,estnota,nro_factura,cedcli,codtiend,tienda,caja);
	close();
}

function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_cat_ctasporcobrar.php";
	f.submit();
}

function ue_buscartienda()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_tienda.php";
	popupWin(pagina,"catalogo_tiendas",600,250);
}

function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentaiva,deniva)
{
	f=document.form1;
	f.txtcodtienda.value=codtie;
    f.txtdestienda.value=nomtie;
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
