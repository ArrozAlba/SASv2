<?php
session_start();
require_once("class_folder/class_funciones_viaticos.php");
$io_fun_viaticos=new class_funciones_viaticos();
if(array_key_exists("hiddestino",$_POST))
{
	$ls_destino=$io_fun_viaticos->uf_obtenervalor("hiddestino","");
}
else
{
	$ls_destino=$io_fun_viaticos->uf_obtenervalor_get("destino","");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Solicitudes de Vi&aacute;ticos</title>
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
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hiddestino" type="hidden" id="hiddestino" value="<?php print $ls_destino;?>">
    <input name="hidstatus" type="hidden" id="hidstatus">
  </p>
  <table width="578" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="574" colspan="2" class="titulo-celda">Cat&aacute;logo de Solicitudes de Vi&aacute;ticos</td>
    </tr>
  </table>
<br>
    <table width="578" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="115"><div align="right">C&oacute;digo</div></td>
        <td width="461" height="22"><div align="left">
          <input name="txtcodsolvia" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Mision</div></td>
        <td height="22"><div align="left">          <input name="txtdenmis" type="text" id="txtdenmis">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Ruta</div></td>
        <td height="22"><input name="txtdesrut" type="text" id="txtdesrut"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">C&eacute;dula Beneficiario </div></td>
        <td height="22"><input name="txtcedben" type="text" id="txtcedben"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <?php
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
require_once("../shared/class_folder/class_funciones.php");
$io_fun= new class_funciones();
$ls_codemp=$_SESSION["la_empresa"]["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codsolvia="%".$_POST["txtcodsolvia"]."%";
	$ls_denmis="%".$_POST["txtdenmis"]."%";
	$ls_desrut="%".$_POST["txtdesrut"]."%";
	$ls_cedben="%".$_POST["txtcedben"]."%";
}
else
{
	$ls_operacion="";

}
print "<table width=580 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='60' align='center'>Solicitud</td>";
print "<td width='260'>Misión</td>";
print "<td>Ruta</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT scv_solicitudviatico.codsolvia,scv_solicitudviatico.codmis,scv_solicitudviatico.codrut,".
            "       scv_solicitudviatico.fecsolvia,scv_solicitudviatico.coduniadm,scv_solicitudviatico.fecsalvia,".
			"       scv_solicitudviatico.fecregvia,scv_solicitudviatico.obssolvia,".
			"       scv_solicitudviatico.numdiavia,scv_solicitudviatico.estsolvia,scv_solicitudviatico.solviaext,".
			"       scv_misiones.denmis,scv_rutas.desrut,denuniadm,scv_solicitudviatico.codfuefin,  
			        spg_dt_unidadadministrativa.codestpro1,spg_dt_unidadadministrativa.codestpro2,
					spg_dt_unidadadministrativa.codestpro3,spg_dt_unidadadministrativa.codestpro4,
					spg_dt_unidadadministrativa.codestpro5,spg_dt_unidadadministrativa.estcla ".			
			"  FROM scv_solicitudviatico,scv_misiones,scv_rutas,scv_dt_personal,spg_unidadadministrativa, ".
			"        spg_dt_unidadadministrativa ".
			" WHERE scv_solicitudviatico.codemp='".$ls_codemp."'".
			"   AND scv_solicitudviatico.codsolvia LIKE '".$ls_codsolvia."'".
			"   AND scv_misiones.denmis LIKE '".$ls_denmis."'".
			"   AND scv_rutas.desrut LIKE '".$ls_desrut."'".
			"   AND scv_solicitudviatico.codemp=scv_dt_personal.codemp".
			"   AND scv_solicitudviatico.codsolvia=scv_dt_personal.codsolvia".
			"   AND scv_solicitudviatico.codsolvia=scv_dt_personal.codsolvia".
			"   AND scv_dt_personal.codper LIKE '".$ls_cedben."'".
			"   AND scv_solicitudviatico.codemp=scv_misiones.codemp".
			"   AND scv_solicitudviatico.codmis=scv_misiones.codmis".
			"   AND scv_solicitudviatico.codemp=scv_rutas.codemp".
			"   AND scv_solicitudviatico.codrut=scv_rutas.codrut".
			"   AND scv_solicitudviatico.codemp=spg_unidadadministrativa.codemp".
			"   AND scv_solicitudviatico.coduniadm=spg_unidadadministrativa.coduniadm ".
			"  	AND spg_dt_unidadadministrativa.codemp=scv_solicitudviatico.codemp ".
			"   AND spg_dt_unidadadministrativa.estcla=scv_solicitudviatico.estcla ".
			"   AND spg_dt_unidadadministrativa.codestpro1=scv_solicitudviatico.codestpro1 ".
			"   AND spg_dt_unidadadministrativa.codestpro2=scv_solicitudviatico.codestpro2 ".
			"   AND spg_dt_unidadadministrativa.codestpro3=scv_solicitudviatico.codestpro3 ".
			"   AND spg_dt_unidadadministrativa.codestpro4=scv_solicitudviatico.codestpro4 ".
			"   AND spg_dt_unidadadministrativa.codestpro5=scv_solicitudviatico.codestpro5".
			" GROUP BY scv_solicitudviatico.codemp, scv_solicitudviatico.codsolvia, scv_solicitudviatico.codmis, ".
			"          scv_solicitudviatico.codrut,".
            "          scv_solicitudviatico.fecsolvia,scv_solicitudviatico.coduniadm,scv_solicitudviatico.fecsalvia,".
			"          scv_solicitudviatico.fecregvia,scv_solicitudviatico.obssolvia,".
			"          scv_solicitudviatico.numdiavia,scv_solicitudviatico.estsolvia,scv_solicitudviatico.solviaext,".
			"          scv_misiones.denmis,scv_rutas.desrut,denuniadm,scv_solicitudviatico.codfuefin,
			           spg_dt_unidadadministrativa.codestpro1,spg_dt_unidadadministrativa.codestpro2,
					   spg_dt_unidadadministrativa.codestpro3,spg_dt_unidadadministrativa.codestpro4,
					   spg_dt_unidadadministrativa.codestpro5,spg_dt_unidadadministrativa.estcla ".
			" ORDER BY scv_solicitudviatico.codsolvia";

    $rs_cta=$io_sql->select($ls_sql);
    $data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;

		$totrow=$ds->getRowCount("codsolvia");
	
		for($z=1;$z<=$totrow;$z++)
		{
			switch ($ls_destino)
			{
				case "SOLICITUD":
					print "<tr class=celdas-blancas>";
					$ls_codsolvia=$data["codsolvia"][$z];
					$ls_codmis=    $data["codmis"][$z];
					$ls_denmis=    $data["denmis"][$z];
					$ls_codrut=    $data["codrut"][$z];
					$ls_desrut=    $data["desrut"][$z];
					$ld_fecsolvia= $data["fecsolvia"][$z];
					$ls_coduniadm= $data["coduniadm"][$z];
					$ls_denuniadm= $data["denuniadm"][$z];
					$ld_fecsalvia= $data["fecsalvia"][$z];
					$ld_fecregvia= $data["fecregvia"][$z];
					$ls_obssolvia= $data["obssolvia"][$z];
					$li_numdiavia= $data["numdiavia"][$z];
					$ls_estsolvia= $data["estsolvia"][$z];
					$li_solviaext= $data["solviaext"][$z];
					$ls_codfuefin= $data["codfuefin"][$z];
					$ld_fecsolvia=$io_fun->uf_convertirfecmostrar($ld_fecsolvia);
					$ld_fecsalvia=$io_fun->uf_convertirfecmostrar($ld_fecsalvia);
					$ld_fecregvia=$io_fun->uf_convertirfecmostrar($ld_fecregvia);
					$li_numdiavia=number_format($li_numdiavia,2,',','.');
					
					$ls_codestpro1= $data["codestpro1"][$z];
					$ls_codestpro2= $data["codestpro2"][$z];
					$ls_codestpro3= $data["codestpro3"][$z];
					$ls_codestpro4= $data["codestpro4"][$z];
					$ls_codestpro5= $data["codestpro5"][$z];
					$ls_estcla= $data["estcla"][$z];
					
					print "<td align='center'><a href=\"javascript: aceptar('$ls_codsolvia','$ls_codmis','$ls_denmis','$ls_codrut',".
						  "                                                 '$ls_desrut','$ld_fecsolvia','$ls_coduniadm',".
						  "													'$ls_denuniadm','$ld_fecsalvia','$ld_fecregvia',".
						  "                                                 '$ls_obssolvia','$li_numdiavia',".
						  "                                                 '$ls_estsolvia','$li_solviaext','$ls_codfuefin','$ls_codestpro1',
						                                                    '$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5',
																			'$ls_estcla'
																			 );\">".$ls_codsolvia."</a></td>";
					print "<td>".$ls_denmis."</td>";
					print "<td>".$ls_desrut."</td>";
					print "</tr>";			
				break;
				case "CALCULO":
					$ls_estsolvia= $data["estsolvia"][$z];
					if($ls_estsolvia=="R")
					{
						print "<tr class=celdas-blancas>";
						$ls_codsolvia=$data["codsolvia"][$z];
						$ls_codmis=    $data["codmis"][$z];
						$ls_denmis=    $data["denmis"][$z];
						$ls_codrut=    $data["codrut"][$z];
						$ls_desrut=    $data["desrut"][$z];
						$ld_fecsolvia= $data["fecsolvia"][$z];
						$ls_coduniadm= $data["coduniadm"][$z];
						$ls_denuniadm= $data["denuniadm"][$z];
						$ld_fecsalvia= $data["fecsalvia"][$z];
						$ld_fecregvia= $data["fecregvia"][$z];
						$ls_obssolvia= $data["obssolvia"][$z];
						$li_numdiavia= $data["numdiavia"][$z];
						$li_solviaext= $data["solviaext"][$z];
						$ls_codfuefin= $data["codfuefin"][$z];
						$ld_fecsolvia=$io_fun->uf_convertirfecmostrar($ld_fecsolvia);
						$ld_fecsalvia=$io_fun->uf_convertirfecmostrar($ld_fecsalvia);
						$ld_fecregvia=$io_fun->uf_convertirfecmostrar($ld_fecregvia);
						$li_numdiavia=number_format($li_numdiavia,2,',','.');
						print "<td align='center'><a href=\"javascript: aceptar_cal('$ls_codsolvia', '$ls_codmis','$ls_denmis', 
						  														   '$ls_codrut',".
							  "                                                    '$ls_desrut','$ld_fecsolvia','$ls_coduniadm',".
							  "													   '$ls_denuniadm','$ld_fecsalvia',  ".
							  "                                                    '$ld_fecregvia',".
							  "                                                    '$ls_obssolvia','$li_numdiavia',".
							  "                                                    '$ls_estsolvia','$li_solviaext', ". 
							  "                                                    '$ls_codfuefin');\">".$ls_codsolvia."</a></td>";
						print "<td>".$ls_denmis."</td>";
						print "<td>".$ls_desrut."</td>";
						print "</tr>";			
					}
				break;
				case "REPORTESOLICITUDPAGODESDE":
					$ls_estsolvia= $data["estsolvia"][$z];
					if($ls_estsolvia=="P")
					{
						print "<tr class=celdas-blancas>";
						$ls_codsolvia=$data["codsolvia"][$z];
						$ls_codmis=    $data["codmis"][$z];
						$ls_denmis=    $data["denmis"][$z];
						$ls_codrut=    $data["codrut"][$z];
						$ls_desrut=    $data["desrut"][$z];
						$ld_fecsolvia= $data["fecsolvia"][$z];
						$ls_coduniadm= $data["coduniadm"][$z];
						$ls_denuniadm= $data["denuniadm"][$z];
						$ld_fecsalvia= $data["fecsalvia"][$z];
						$ld_fecregvia= $data["fecregvia"][$z];
						$ls_obssolvia= $data["obssolvia"][$z];
						$li_numdiavia= $data["numdiavia"][$z];
						$li_solviaext= $data["solviaext"][$z];
						$ld_fecsolvia=$io_fun->uf_convertirfecmostrar($ld_fecsolvia);
						$ld_fecsalvia=$io_fun->uf_convertirfecmostrar($ld_fecsalvia);
						$ld_fecregvia=$io_fun->uf_convertirfecmostrar($ld_fecregvia);
						$li_numdiavia=number_format($li_numdiavia,2,',','.');
						print "<td align='center'><a href=\"javascript: aceptar_solicituddesde('$ls_codsolvia');\">".$ls_codsolvia."</a></td>";
						print "<td>".$ls_denmis."</td>";
						print "<td>".$ls_desrut."</td>";
						print "</tr>";			
					}
				break;
				case "REPORTESOLICITUDPAGOHASTA":
					$ls_estsolvia= $data["estsolvia"][$z];
					if($ls_estsolvia=="P")
					{
						print "<tr class=celdas-blancas>";
						$ls_codsolvia=$data["codsolvia"][$z];
						$ls_codmis=    $data["codmis"][$z];
						$ls_denmis=    $data["denmis"][$z];
						$ls_codrut=    $data["codrut"][$z];
						$ls_desrut=    $data["desrut"][$z];
						$ld_fecsolvia= $data["fecsolvia"][$z];
						$ls_coduniadm= $data["coduniadm"][$z];
						$ls_denuniadm= $data["denuniadm"][$z];
						$ld_fecsalvia= $data["fecsalvia"][$z];
						$ld_fecregvia= $data["fecregvia"][$z];
						$ls_obssolvia= $data["obssolvia"][$z];
						$li_numdiavia= $data["numdiavia"][$z];
						$li_solviaext= $data["solviaext"][$z];
						$ld_fecsolvia=$io_fun->uf_convertirfecmostrar($ld_fecsolvia);
						$ld_fecsalvia=$io_fun->uf_convertirfecmostrar($ld_fecsalvia);
						$ld_fecregvia=$io_fun->uf_convertirfecmostrar($ld_fecregvia);
						$li_numdiavia=number_format($li_numdiavia,2,',','.');
						print "<td align='center'><a href=\"javascript: aceptar_solicitudhasta('$ls_codsolvia');\">".$ls_codsolvia."</a></td>";
						print "<td>".$ls_denmis."</td>";
						print "<td>".$ls_desrut."</td>";
						print "</tr>";			
					}
				break;
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
  function aceptar(ls_codsolvia,ls_codmis,ls_denmis,ls_codrut,ls_desrut,ld_fecsolvia,ls_coduniadm,ls_denuniadm,
  				   ld_fecsalvia,ld_fecregvia,ls_obssolvia,li_numdiavia,ls_estsolvia,li_solviaext,ls_codfuefin,
				   ls_codestpro1,ls_codestpro2,ls_codestpro3,ls_codestpro4,ls_codestpro5,ls_estcla)
  {
	opener.document.form1.txtcodsolvia.value=ls_codsolvia;
	opener.document.form1.txtcodmis.value=ls_codmis;
	opener.document.form1.txtdenmis.value=ls_denmis;
	opener.document.form1.txtcodrut.value=ls_codrut;
	opener.document.form1.txtdesrut.value=ls_desrut;
	opener.document.form1.txtfecsolvia.value=ld_fecsolvia;
	opener.document.form1.txtcoduniadm.value=ls_coduniadm;
	opener.document.form1.txtdenuniadm.value=ls_denuniadm;
	opener.document.form1.txtfecsal.value=ld_fecsalvia;
	opener.document.form1.txtfecreg.value=ld_fecregvia;
	opener.document.form1.txtobssolvia.value=ls_obssolvia;
	opener.document.form1.txtnumdia.value=li_numdiavia;
	opener.document.form1.hidestsolvia.value=ls_estsolvia;
	opener.document.form1.txtcodfuefin.value=ls_codfuefin;
	opener.document.form1.txtcodestpro1.value=ls_codestpro1;
	opener.document.form1.txtcodestpro2.value=ls_codestpro2;
	opener.document.form1.txtcodestpro3.value=ls_codestpro3;
	opener.document.form1.txtcodestpro4.value=ls_codestpro4;
	opener.document.form1.txtcodestpro5.value=ls_codestpro5;
	opener.document.form1.hidestcla.value=ls_estcla;
	
	opener.document.form1.operacion.value="BUSCARDETALLE";
	opener.document.form1.hidestatus.value="C";
	opener.document.form1.action="sigesp_scv_p_solicitudviaticos.php";
	if(li_solviaext==1)
	{opener.document.form1.chksolviaext.checked=true;}
	else
	{opener.document.form1.chksolviaext.checked=false;}
	opener.document.form1.submit();
	close();
  }

  function aceptar_cal(ls_codsolvia,ls_codmis,ls_denmis,ls_codrut,ls_desrut,ld_fecsolvia,ls_coduniadm,ls_denuniadm,
  				       ld_fecsalvia,ld_fecregvia,ls_obssolvia,li_numdiavia,ls_estsolvia,li_solviaext,ls_codfuefin)
  {
	opener.document.form1.txtcodsolvia.value=ls_codsolvia;
	opener.document.form1.txtcodmis.value=ls_codmis;
	opener.document.form1.txtdenmis.value=ls_denmis;
	opener.document.form1.txtcodrut.value=ls_codrut;
	opener.document.form1.txtdesrut.value=ls_desrut;
	opener.document.form1.txtfecsolvia.value=ld_fecsolvia;
	opener.document.form1.txtcoduniadm.value=ls_coduniadm;
	opener.document.form1.txtdenuniadm.value=ls_denuniadm;
	opener.document.form1.txtfecsal.value=ld_fecsalvia;
	opener.document.form1.txtfecreg.value=ld_fecregvia;
	opener.document.form1.txtobssolvia.value=ls_obssolvia;
	opener.document.form1.txtnumdia.value=li_numdiavia;
	opener.document.form1.hidestsolvia.value=ls_estsolvia;
	opener.document.form1.txtcodfuefin.value=ls_codfuefin;
	opener.document.form1.operacion.value="BUSCARDETALLE";
	opener.document.form1.hidestatus.value="C";
	opener.document.form1.action="sigesp_scv_p_calcularviaticos.php";
	if(li_solviaext==1)
	{opener.document.form1.chksolviaext.checked=true;}
	else
	{opener.document.form1.chksolviaext.checked=false;}
	opener.document.form1.submit();
	close();
  }

  function aceptar_solicituddesde(ls_codsolvia)
  {
	opener.document.form1.txtcodsoldes.value=ls_codsolvia;
	opener.document.form1.txtcodsoldes.readonly=true;
	opener.document.form1.txtcodsolhas.value="";
	opener.document.form1.txtcodsolhas.readonly=true;
	close();
  }

  function aceptar_solicitudhasta(ls_codsolvia)
  {
	if(opener.document.form1.txtcodsoldes.value<=ls_codsolvia)
	{
		opener.document.form1.txtcodsolhas.value=ls_codsolvia;
		opener.document.form1.txtcodsolhas.readonly=true;
	}
	else
	{
		alert("El Rango esta Inválido");
	}
	close();
  }

  function ue_search()
  {
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_scv_cat_sol_via.php";
	f.submit();
  }
</script>
</html>
