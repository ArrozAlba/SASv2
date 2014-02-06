<?php
session_start();

	require_once("../shared/class_folder/sigesp_include.php");
	$in     =new sigesp_include();
	$con    =$in->uf_conectar();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg =new class_mensajes();
	require_once("../shared/class_folder/class_funciones.php");
	$io_fun =new class_funciones();
	require_once("../shared/class_folder/class_datastore.php");
	$ds     =new class_datastore();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql =new class_sql($con);
	require_once("sigesp_sim_c_articulo.php");
	$io_siv= new sigesp_sim_c_articulo();

	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$li_estnum="";
	$li_catalogo=$io_siv->uf_sim_select_catalogo($li_estnum);

	$li_linea=$_REQUEST["linea"];
	$ls_almacen=$_REQUEST["almacen"];
	$ls_tienda=$_REQUEST["tienda"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Art&iacute;culo</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<?

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codart="%".$_POST["txtcodart"]."%";
	$ls_denart="%".$_POST["txtdenart"]."%";
	$ls_status="%".$_POST["hidstatus"]."%";
	$li_linea=$_POST["hidlinea"];
	$ls_almacen=$_POST["hidalmacen"];
	$ls_tienda=$_POST["hidtienda"];
}
else
{
	$ls_operacion="";

}

?>
<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hidstatus" type="hidden" id="hidstatus">
    <input name="hidlinea" type="hidden" id="hidlinea" value="<? print $li_linea;?>">
    <input name="hidalmacen" type="hidden" id="hidalmacen" value="<? print $ls_almacen;?>">
    <input name="hidtienda" type="hidden" id="hidtienda" value="<? print $ls_tienda;?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo del Producto </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="80"><div align="right">C&oacute;digo</div></td>
        <td width="418" height="22"><div align="left">
          <input name="txtcodart" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22">
        	<div align="left"><input name="txtdenart" type="text" id="txtdenart"></div>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search('<?php print $li_linea ?>');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
<?php

	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td width=100>C&oacute;digo</td>";
	print "<td>Denominacion</td>";
	print "<td>Proveedor</td>";
	print "<td>Existencia</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		if($li_linea <> "" AND $ls_almacen <> ""){
			if(substr($ls_codart,0,5)=='0000V'){
			$ls_sql="SELECT a.codart, a.denart, aa.existencia, aa.cod_pro, p.ultcosart, p.cosproart, a.tipcos, pr.nompro,".
				" (SELECT dentipart FROM sim_tipoarticulo".
				"   WHERE sim_tipoarticulo.codtipart=a.codtipart) as dentipart,".
				" (SELECT dencat FROM saf_catalogo".
				"   WHERE saf_catalogo.catalogo=a.codcatsig) as dencatsig,".
				" (SELECT denunimed FROM sim_unidadmedida".
				"   WHERE sim_unidadmedida.codunimed=a.codunimed) as denunimed".
				" FROM sim_articulo a, sim_articuloalmacen aa, sfc_producto p, rpc_proveedor pr".
				" WHERE a.codemp = '".$ls_codemp."'".
				" AND a.codart ilike '".$ls_codart."' AND a.denart ilike '".$ls_denart."' AND a.codart=aa.codart " .
				" AND a.codart=p.codart AND p.codart=aa.codart AND aa.codalm='".$ls_almacen."' AND p.codtiend='".$ls_tienda."' " .
				" AND pr.cod_pro=aa.cod_pro AND aa.existencia > 0 ";
			}else{
				$ls_sql="SELECT a.codart, a.denart, aa.existencia, aa.cod_pro, a.tipcos, pr.nompro,".
				" ta.dentipart, um.denunimed, " .
				" (SELECT dencat FROM saf_catalogo".
				"  WHERE saf_catalogo.catalogo=a.codcatsig) as dencatsig," .
				" (SELECT dt.preuniart FROM sim_recepcion r, sim_dt_recepcion dt " .
				" WHERE r.numconrec=dt.numconrec AND dt.codart ilike '".$ls_codart."' AND dt.codart=a.codart AND r.estrevrec = 1 " .
				" GROUP BY r.numconrec, r.fecrec, dt.codart, dt.preuniart " .
				" ORDER BY r.numconrec DESC LIMIT 1) as ultcosart ".
				" FROM sim_articulo a, sim_articuloalmacen aa, rpc_proveedor pr, sim_tipoarticulo ta, sim_unidadmedida um".
				" WHERE a.codemp = '".$ls_codemp."'AND a.codart ilike '".$ls_codart."' AND a.denart ilike '".$ls_denart."' " .
				" AND a.codart=aa.codart AND ta.codtipart=a.codtipart AND um.codunimed=a.codunimed " .
				" AND aa.codalm='".$ls_almacen."' AND pr.cod_pro=aa.cod_pro AND aa.existencia > 0 ";
			}
		}else{
			$ls_sql="SELECT sim_articulo.*,".
				" (SELECT dentipart FROM sim_tipoarticulo".
				"   WHERE sim_tipoarticulo.codtipart=sim_articulo.codtipart) as dentipart,".
				" (SELECT dencat FROM saf_catalogo".
				"   WHERE saf_catalogo.catalogo=sim_articulo.codcatsig) as dencatsig,".
				" (SELECT denunimed FROM sim_unidadmedida".
				"   WHERE sim_unidadmedida.codunimed=sim_articulo.codunimed) as denunimed".
				" FROM sim_articulo".
				" WHERE codemp = '".$ls_codemp."'".
				" AND codart ilike '".$ls_codart."'".
				" AND denart ilike '".$ls_denart."'".
				" AND exiart > 0 ";
		}

		//print $ls_sql;

		$rs_cta=$io_sql->select($ls_sql);
		$data=$rs_cta;
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;

			$totrow=$ds->getRowCount("codart");

			if($li_linea <> "" and $ls_almacen <> ""){
				for($z=1;$z<=$totrow;$z++)
				{
					print "<tr class=celdas-blancas>";
					$ls_codart=     $data["codart"][$z];
					$ls_denart=     $data["denart"][$z];
					$li_exiart=     $data["existencia"][$z];
					$li_exiart=     number_format($li_exiart,2,",",".");
					$li_ultcosart=  $data["ultcosart"][$z];
					$li_ultcosart=  number_format($li_ultcosart,2,",",".");
					if(substr($ls_codart,0,5)=='0000V'){
						$li_cosproart=  $data["cosproart"][$z];
					}else if(substr($ls_codart,0,5)=='0000B'){
						$li_cosproart=  $data["ultcosart"][$z];
					}
					$li_cosproart=  number_format($li_cosproart,2,",",".");
					$li_codprov=  $data["cod_pro"][$z];
					$li_nomprov=  $data["nompro"][$z];


					print "<td><a href=\"javascript: aceptar2('$ls_codart','$ls_denart','$li_ultcosart','$li_linea','$li_codprov','$li_nomprov','$li_exiart');\">".$ls_codart."</a></td>";
					print "<td>".$data["denart"][$z]." ".$data["denunimed"][$z]."</td>";
					print "<td>".$data["nompro"][$z]."</td>";
					print "<td align='left'> ".$data["existencia"][$z]." </td>";
					print "</tr>";

				}
			}else{

				for($z=1;$z<=$totrow;$z++)
				{
					print "<tr class=celdas-blancas>";
					$ls_codart=     $data["codart"][$z];
					$ls_denart=     $data["denart"][$z];
					$ls_codtipart=  $data["codtipart"][$z];
					$ls_dentipart=  $data["dentipart"][$z];
					$ls_codunimed=  $data["codunimed"][$z];
					$ls_denunimed=  $data["denunimed"][$z];
					$ld_feccreart=  $data["feccreart"][$z];
					$ls_obsart=     $data["obsart"][$z];
					$li_exiart=     $data["exiart"][$z];
					$li_exiart=     number_format($li_exiart,2,",",".");
					$li_exiiniart=  $data["exiiniart"][$z];
					$li_exiiniart=  number_format($li_exiiniart,2,",",".");
					$li_minart=     $data["minart"][$z];
					$li_minart=     number_format($li_minart,2,",",".");
					$li_maxart=     $data["maxart"][$z];
					$li_maxart=     number_format($li_maxart,2,",",".");
					$li_prearta=    $data["prearta"][$z];
					$li_prearta=    number_format($li_prearta,2,",",".");
					$li_preartb=    $data["preartb"][$z];
					$li_preartb=    number_format($li_preartb,2,",",".");
					$li_preartc=    $data["preartc"][$z];
					$li_preartc=    number_format($li_preartc,2,",",".");
					$li_preartd=    $data["preartd"][$z];
					$li_preartd=    number_format($li_preartd,2,",",".");
					$ld_fecvenart=  $data["fecvenart"][$z];
					$ls_codcatsig=  $data["codcatsig"][$z];
					$ls_dencatsig=  $data["dencatsig"][$z];
					$ls_spg_cuenta= $data["spg_cuenta"][$z];
					$li_pesart=     $data["pesart"][$z];
					$li_pesart=     number_format($li_pesart,2,",",".");
					$li_altart=     $data["altart"][$z];
					$li_altart=     number_format($li_altart,2,",",".");
					$li_ancart=     $data["ancart"][$z];
					$li_ancart=     number_format($li_ancart,2,",",".");
					$li_proart=     $data["proart"][$z];
					$li_proart=     number_format($li_proart,2,",",".");
					$li_ultcosart=  $data["ultcosart"][$z];
					$li_ultcosart=  number_format($li_ultcosart,2,",",".");
					$li_cosproart=  $data["cosproart"][$z];
					$li_cosproart=  number_format($li_cosproart,2,",",".");
					$ls_fotart=       $data["fotart"][$z];
					$ld_feccreart=$io_fun->uf_convertirfecmostrar($ld_feccreart);
					$ld_fecvenart=$io_fun->uf_convertirfecmostrar($ld_fecvenart);

					print "<td><a href=\"javascript: aceptar('$ls_codart','$ls_denart','$ls_codtipart','$ls_dentipart','$ls_codunimed',";
					print "'$ls_denunimed','$ld_feccreart','$ls_obsart','$li_exiart','$li_exiiniart','$li_minart','$li_maxart','$li_prearta','$li_preartb',";
					print "'$li_preartc','$li_preartd','$ld_fecvenart','$ls_spg_cuenta','$li_pesart','$li_altart',";
					print "'$li_ancart','$li_proart','$li_ultcosart','$li_cosproart','$ls_fotart','$ls_codcatsig','$ls_dencatsig','$li_catalogo','$li_linea');\">".$ls_codart."</a></td>";
					print "<td>".$data["denart"][$z]." ".$data["denunimed"][$z]."</td>";
					print "<td align='rigth'>".$data["exiart"][$z]."</td>";
					print "</tr>";
				}
			}

		}
		else
		{
			$io_msg->message("No hay registros");
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
	function aceptar(ls_codart,ls_denart,ls_codtipart,ls_dentipart,ls_codunimed,ls_denunimed,ld_feccreart,ls_obsart,li_exiart,
	                 li_exiiniart,li_minart,li_maxart,li_prearta,li_preartb,li_preartc,li_preartd,ld_fecvenart,ls_spg_cuenta,
					 li_pesart,li_altart,li_ancart,li_proart,li_ultcosart,li_cosproart,ls_fotart,ls_codcatsig,ls_dencatsig,li_catalogo,li_linea)
	{
		if(opener.document.form1.tipo.value=="Salida"){
			obj=eval("opener.document.form1.txtdenart"+li_linea+"");
			obj.value=ls_denart;
			obj1=eval("opener.document.form1.txtcodart"+li_linea+"");
			obj1.value=ls_codart;
			obj2=eval("opener.document.form1.txtpreuniart"+li_linea+"");
			obj2.value=li_ultcosart;
		}else{
			opener.document.form1.txtcodart.value=   ls_codart;
			opener.document.form1.txtdenart.value=   ls_denart;
			opener.document.form1.txtcodtipart.value=ls_codtipart;
			opener.document.form1.txtdentipart.value=ls_dentipart;
			opener.document.form1.txtcodunimed.value=ls_codunimed;
			opener.document.form1.txtdenunimed.value=ls_denunimed;
			opener.document.form1.txtfeccreart.value=ld_feccreart;
			opener.document.form1.txtobsart.value=   ls_obsart;
			opener.document.form1.txtexiart.value=   li_exiart;
			opener.document.form1.txtexiiniart.value=li_exiiniart;
			opener.document.form1.txteximinart.value=li_minart;
			opener.document.form1.txteximaxart.value=li_maxart;
			opener.document.form1.txtprearta.value=  li_prearta;
			opener.document.form1.txtpreartb.value=  li_preartb;
			opener.document.form1.txtpreartc.value=  li_preartc;
			opener.document.form1.txtpreartd.value=  li_preartd;
			opener.document.form1.txtfecvenart.value=ld_fecvenart;
			opener.document.form1.txtspg_cuenta.value=ls_spg_cuenta;
			opener.document.form1.txtpesart.value=   li_pesart;
			opener.document.form1.txtaltart.value=   li_altart;
			opener.document.form1.txtancart.value=   li_ancart;
			opener.document.form1.txtproart.value=   li_proart;
			opener.document.form1.txtultcosart.value=li_ultcosart;
			opener.document.form1.txtcosproart.value=li_cosproart;
			if(ls_fotart!="")
			{opener.document.images["foto"].src="fotosarticulos/"+ls_fotart;}
			else
			{opener.document.images["foto"].src="fotosarticulos/blanco.jpg";}
			opener.document.form1.hidstatusc.value="C";
			opener.document.form1.btnregistrar.disabled=false;
			opener.document.form1.btncargos.disabled=false;
			if(li_catalogo==1)
			{
				opener.document.form1.txtcodcatsig.value= ls_codcatsig;
				opener.document.form1.txtdencatsig.value= ls_dencatsig;
			}
		}

		window.close();
	}

	function aceptar2(ls_codart,ls_denart,li_ultcosart,li_linea,li_codprov,li_nomprov,li_exiart)
	{
		//alert(li_codprov+" -- "+li_nomprov);
		if(opener.document.form1.tipo.value=="Salida"){
			obj=eval("opener.document.form1.txtdenart"+li_linea+"");
			obj.value=ls_denart;
			obj1=eval("opener.document.form1.txtcodart"+li_linea+"");
			obj1.value=ls_codart;
			obj2=eval("opener.document.form1.txtpreuniart"+li_linea+"");
			obj2.value=li_ultcosart;
			obj3=eval("opener.document.form1.txtcodprov"+li_linea+"");
			obj3.value=li_codprov;
			obj4=eval("opener.document.form1.txtnomprov"+li_linea+"");
			obj4.value=li_nomprov;
			obj5=eval("opener.document.form1.hidexistencia"+li_linea+"");
			obj5.value=li_exiart;
		}
		window.close();

	}

	function ue_search(li_linea)
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_catdinamic_articulo.php?linea="+li_linea+"";
		f.submit();
	}
</script>
</html>
