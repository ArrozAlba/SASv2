<?php
session_start();
require_once("class_funciones_activos.php");
$io_fac= new class_funciones_activos("../");
if(array_key_exists("coddestino",$_POST))
{
	$ls_coddestino=$_POST["coddestino"];
	$ls_dendestino=$_POST["dendestino"];
}
else
{
	$ls_coddestino=$io_fac->uf_obtenervalor_get("coddestino","txtcodact");
	$ls_dendestino=$io_fac->uf_obtenervalor_get("dendestino","txtdenact");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Activos </title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hidstatus" type="hidden" id="hidstatus">
</p>
  <table width="550" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Activos </td>
    </tr>
  </table>
<br>
    <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="85" height="18"><div align="right">C&oacute;digo</div></td>
        <td width="182" height="22"><div align="left">
          <input name="txtcodact" type="text" id="txtcodact">
        </div></td>
        <td colspan="2" rowspan="4"><div align="right"></div>          <div align="right">
          <table width="148" height="61" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td colspan="2"><div align="center" class="titulo-conect">Fecha de Registro </div></td>
            </tr>
            <tr>
              <td width="42"> <div align="right">Desde</div></td>
              <td width="104" height="22"><input name="txtdesde" type="text" id="txtdesde" onKeyPress="ue_separadores(this,'/',patron,true);" size="17" maxlength="10" datepicker="true"></td>
            </tr>
            <tr>
              <td> <div align="right">Hasta </div></td>
              <td height="22"><input name="txthasta" type="text" id="txthasta" onKeyPress="ue_separadores(this,'/',patron,true);"  size="17" maxlength="10" datepicker="true"></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </div></td>
        <td><input name="dendestino" type="hidden" id="dendestino" value="<?php print $ls_dendestino ?>">
            <input name="coddestino" type="hidden" id="coddestino" value="<?php print $ls_coddestino ?>"></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtdenact" type="text" id="txtdenact">
        </div></td>
        <td width="117">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Marca</div></td>
        <td height="22"><div align="left">
            <input name="txtmaract" type="text" id="txtmaract">
        </div></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Modelo</div></td>
        <td height="22"><div align="left">
          <input name="txtmodact" type="text" id="txtmodact">
        </div></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
<?php
require_once("../shared/class_folder/sigesp_include.php");
$in=     new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg= new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds=     new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql= new class_sql($con);
require_once("../shared/class_folder/class_funciones.php");
$io_fun= new class_funciones();
require_once("../shared/class_folder/class_fecha.php");
$io_fec= new class_fecha();
$arr=$_SESSION["la_empresa"];
$ls_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codact="%".$_POST["txtcodact"]."%";
	$ls_denact="%".$_POST["txtdenact"]."%";
	$ls_maract="%".$_POST["txtmaract"]."%";
	$ls_modact="%".$_POST["txtmodact"]."%";
	$ls_desde="%".$_POST["txtdesde"]."%";
	$ls_hasta="%".$_POST["txthasta"]."%";
	$ls_status="%".$_POST["hidstatus"]."%";
	
}
else
{
	$ls_operacion="";
	$ls_desde="dd/mm/aaaa";
	$ls_hasta="dd/mm/aaaa";

}
print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='50'>Codigo </td>";
print "<td width='270'>Denominacion</td>";
print "<td width='60'>Marca</td>";
print "<td width='60'>Modelo</td>";
print "<td>Registro</td>";
print "</tr>";

if($ls_operacion=="BUSCAR")
{
	if (array_key_exists("txtdesde",$_POST))
	{
		$ls_desde=$_POST["txtdesde"];
		if (array_key_exists("txthasta",$_POST))
		{
			$ls_hasta=$_POST["txthasta"];

			$lb_fechavalida = $io_fec->uf_comparar_fecha($ls_desde,$ls_hasta);
			if($lb_fechavalida)
			{
				$ls_desde=$io_fun->uf_convertirdatetobd($ls_desde);
				$ls_hasta=$io_fun->uf_convertirdatetobd($ls_hasta);
				//$ls_cadena=" AND saf_activo.fecregact >= '".$ls_desde."' AND saf_activo.fecregact <= '".$ls_hasta."' ";

				$ls_sql="SELECT saf_activo.*, ".
						" 		(SELECT denfuefin FROM sigesp_fuentefinanciamiento ".
						"         WHERE sigesp_fuentefinanciamiento.codfuefin = saf_activo.codfuefin) as denfuefin, ".
						" 		(SELECT fecordcom FROM soc_ordencompra ".
						"         WHERE soc_ordencompra.numordcom = saf_activo.numordcom ".
						"           AND (estcondat='B' OR estcondat='-')) as fecordcom, ".
						" 		(SELECT densitcon FROM saf_situacioncontable ".
						"         WHERE saf_situacioncontable.codsitcon = saf_activo.codsitcon) as densitcon, ".
						" 		(SELECT denconcom FROM saf_condicioncompra ".
						"         WHERE saf_condicioncompra.codconcom = saf_activo.codconcom) as denconcom, ".
						" 		(SELECT denconbie FROM saf_conservacionbien ".
						"         WHERE saf_conservacionbien.codconbie = saf_activo.codconbie) as denconbie, ".
						" 		(SELECT dencat FROM saf_catalogo ".
						"         WHERE saf_catalogo.catalogo = saf_activo.catalogo) as dencat, ".	
						" 		(SELECT despai FROM sigesp_pais ".
						"         WHERE sigesp_pais.codpai = saf_activo.codpai) as despai, ".	
						" 		(SELECT desest FROM sigesp_estados ".
						"         WHERE sigesp_estados.codest = saf_activo.codest ".
						"           AND sigesp_estados.codpai = saf_activo.codpai) as desest, ".	
						" 		(SELECT denmun FROM sigesp_municipio ".
						"         WHERE sigesp_municipio.codmun = saf_activo.codmun ".
						"           AND sigesp_municipio.codest = saf_activo.codest ".
						"           AND sigesp_municipio.codpai = saf_activo.codpai) as desmun, ".	
						" 		(SELECT fecemisol FROM cxp_solicitudes".
						"         WHERE cxp_solicitudes.numsol = saf_activo.numsolpag) as fecemisol , ".	
						"        (SELECT dengru  ".
						"		  FROM   saf_grupo ".
						"		  WHERE  saf_grupo.codgru=saf_activo.codgru) as dengru, ".
						"		 (SELECT densubgru ".
					    "		  FROM   saf_subgrupo ".
						"		  WHERE  saf_subgrupo.codgru=saf_activo.codgru AND ".
						"				 saf_subgrupo.codsubgru=saf_activo.codsubgru) as densubgru, ".
						"		 (SELECT densec ".
					    " 		  FROM   saf_seccion ".
						"		  WHERE  saf_seccion.codgru=saf_activo.codgru AND ".
						"				 saf_seccion.codsubgru=saf_activo.codsubgru AND ".
						"				 saf_seccion.codsec=saf_activo.codsec) as densec, ".
						"		 (SELECT denite ".
					    " 		  FROM   saf_item ".
						"		  WHERE  saf_item.codgru=saf_activo.codgru AND ".
						"				 saf_item.codsubgru=saf_activo.codsubgru AND ".
						"				 saf_item.codsec=saf_activo.codsec AND ".
						"                saf_item.codite=saf_activo.codite ) as denite, ".
					    "        (SELECT max(denominacion) ".
						"          FROM spg_cuentas ".
						"		  WHERE spg_cuentas.spg_cuenta = saf_activo.spg_cuenta_act) as denspg ".   
						"  FROM saf_activo".	
						" WHERE saf_activo.codemp= '".$ls_codemp."'".	
						"   AND saf_activo.codact like '".$ls_codact."'".	
						"   AND saf_activo.denact like '".$ls_denact."'".	
						"   AND saf_activo.maract like '".$ls_maract."'".	
						"   AND saf_activo.modact like '".$ls_modact."'".
						"   AND saf_activo.fecregact >= '".$ls_desde."' ".
						"   AND saf_activo.fecregact <= '".$ls_hasta."'".
						" ORDER BY codact";
				$rs_cta=$io_sql->select($ls_sql);
			}
			else
			{
				$ls_sql="SELECT saf_activo.*, ".
						"       (SELECT denfuefin FROM sigesp_fuentefinanciamiento ".
						" 		  WHERE sigesp_fuentefinanciamiento.codfuefin = saf_activo.codfuefin) as denfuefin, ".
						"       (SELECT fecordcom FROM soc_ordencompra ".
						"         WHERE soc_ordencompra.numordcom = saf_activo.numordcom ".
						"           AND (estcondat='B' OR estcondat='-')) as fecordcom, ".
						" 		(SELECT densitcon FROM saf_situacioncontable ".
						"         WHERE saf_situacioncontable.codsitcon = saf_activo.codsitcon) as densitcon, ".
						" 		(SELECT denconcom FROM saf_condicioncompra ".
						"         WHERE saf_condicioncompra.codconcom = saf_activo.codconcom) as denconcom, ".
						" 		(SELECT denconbie FROM saf_conservacionbien ".
						"         WHERE saf_conservacionbien.codconbie = saf_activo.codconbie) as denconbie, ".
						" 		(SELECT spg_cuenta FROM saf_catalogo ".
						"         WHERE saf_catalogo.catalogo = saf_activo.catalogo) as ctaspg, ".	
						" 		(SELECT dencat FROM saf_catalogo ".
						"         WHERE saf_catalogo.catalogo = saf_activo.catalogo) as dencat, ".	
						" 		(SELECT despai FROM sigesp_pais ".
						"         WHERE sigesp_pais.codpai = saf_activo.codpai) as despai, ".	
						" 		(SELECT desest FROM sigesp_estados ".
						"         WHERE sigesp_estados.codest = saf_activo.codest ".
						"    	    AND sigesp_estados.codpai = saf_activo.codpai) as desest, ".	
						" 		(SELECT denmun FROM sigesp_municipio ".
						"         WHERE sigesp_municipio.codmun = saf_activo.codmun ".
						"    	  AND sigesp_municipio.codest = saf_activo.codest ".
						"    	  AND sigesp_municipio.codpai = saf_activo.codpai) as desmun, ".	
						" 		(SELECT fecemisol FROM cxp_solicitudes ".
						"         WHERE cxp_solicitudes.numsol = saf_activo.numsolpag) as fecemisol, ".
						"        (SELECT dengru  ".
						"		  FROM   saf_grupo ".
						"		  WHERE  saf_grupo.codgru=saf_activo.codgru) as dengru, ".
						"		 (SELECT densubgru ".
					    "		  FROM   saf_subgrupo ".
						"		  WHERE  saf_subgrupo.codgru=saf_activo.codgru AND ".
						"				 saf_subgrupo.codsubgru=saf_activo.codsubgru) as densubgru, ".
						"		 (SELECT densec ".
					    " 		  FROM   saf_seccion ".
						"		  WHERE  saf_seccion.codgru=saf_activo.codgru AND ".
						"				 saf_seccion.codsubgru=saf_activo.codsubgru AND ".
						"				 saf_seccion.codsec=saf_activo.codsec) as densec, ".
						"		 (SELECT denite ".
					    " 		  FROM   saf_item ".
						"		  WHERE  saf_item.codgru=saf_activo.codgru AND ".
						"				 saf_item.codsubgru=saf_activo.codsubgru AND ".
						"				 saf_item.codsec=saf_activo.codsec AND ".
						"                saf_item.codite=saf_activo.codite ) as denite, ".
						"        (SELECT max(denominacion) ".
						"          FROM spg_cuentas ".
						"		  WHERE spg_cuentas.spg_cuenta = saf_activo.spg_cuenta_act) as denspg ".  
						"  FROM saf_activo ".	
						" WHERE saf_activo.codemp= '".$ls_codemp."' ".	
						"   AND saf_activo.codact like '".$ls_codact."' ".	
						"   AND saf_activo.denact like '".$ls_denact."' ".	
						"   AND saf_activo.maract like '".$ls_maract."' ".	
						"   AND saf_activo.modact like '".$ls_modact."' ".
						" ORDER BY codact";
				$rs_cta=$io_sql->select($ls_sql);
			}
		}
	}
	$li_numrows = $io_sql->num_rows($rs_cta);
	if($li_numrows>0)
	{
		while($row=$io_sql->fetch_row($rs_cta))
		{
			print "<tr class=celdas-blancas>";
			$ls_codact= $row["codact"];
			$ls_denact= $row["denact"];
			$ls_maract= $row["maract"];
			$ls_modact= $row["modact"];
			$ld_fecregact= $row["fecregact"];
			$ld_fecregact= $io_fun->uf_convertirfecmostrar($ld_fecregact);
			$ld_feccmpact= $row["feccmpact"];
			$ld_feccmpact= $io_fun->uf_convertirfecmostrar($ld_feccmpact);			
			$li_cosact= $row["costo"];
			$li_cosact= number_format($li_cosact,2,",",".");
			$ls_codconbie= $row["codconbie"];
			$ls_denconbie= $row["denconbie"];
			$ls_codfuefin= $row["codfuefin"];
			$ls_denfuefin= $row["denfuefin"];
			$ls_codsitcon= $row["codsitcon"];
			$ls_densitcon= $row["densitcon"];
			$ls_codconcom= $row["codconcom"];
			$ls_denconcom= $row["denconcom"];
			$ls_codpai= $row["codpai"];
			$ls_denpai= $row["despai"];
			$ls_codest= $row["codest"];
			$ls_denest= $row["desest"];
			$ls_codmun= $row["codmun"];
			$ls_denmun= $row["desmun"];
			$ls_obsact= $row["obsact"];
			$ls_codcat= $row["catalogo"];
			$ls_dencat= $row["dencat"];
			$ls_ctaspg= $row["spg_cuenta_act"];
			$ls_esttipinm= $row["esttipinm"];
			$ls_fotact= $row["fotact"];
			$ls_numord= $row["numordcom"];
			$ls_codpro= $row["cod_pro"];
			$ld_fecordcom=$row["fecordcom"];
			$ld_fecordcom= $io_fun->uf_convertirfecmostrar($ld_fecordcom);
			$ls_nompro= $row["nompro"];
			$li_monord= $row["monordcom"];
			$ls_numsolpag= $row["numsolpag"];
			$ld_fecemisol= $row["fecemisol"];
			$ls_estdepact= $row["estdepact"];
			$ld_fecemisol= $io_fun->uf_convertirfecmostrar($ld_fecemisol);
			$li_monord= number_format($li_monord,2,",",".");
			$ls_codgru= $row["codgru"];
			$ls_codsubgru= $row["codsubgru"];
			$ls_codsec= $row["codsec"];
			$ls_codite= $row["codite"];
			$ls_dengru= $row["dengru"];
			$ls_densubgru= $row["densubgru"];
			$ls_densec= $row["densec"];
			$ls_denite= $row["denite"];
			$ls_denspg= $row["denspg"];
			$ls_clasif= $row["tipinm"];
			print " <td><a href=\"javascript: aceptar('$ls_codact','$ls_maract','$ls_modact','$ld_fecregact','$ld_feccmpact',".
			       "'$li_cosact','$ls_codconbie','$ls_denconbie','$ls_codfuefin','$ls_denfuefin','$ls_codsitcon','$ls_densitcon',".
			       "'$ls_codconcom','$ls_denconcom','$ls_codpai','$ls_denpai','$ls_codest','$ls_denest','$ls_codmun','$ls_denmun',".
			       "'$ls_codcat','$ls_dencat','$ls_ctaspg','$ls_esttipinm','$ls_numord','$ls_codpro','$ld_fecordcom',".
			       "'$ls_nompro','$li_monord','$ls_numsolpag','$ld_fecemisol','$ls_estdepact','$ls_fotact','$ls_coddestino',".
				   "'$ls_dendestino','$ls_codgru','$ls_codsubgru','$ls_codsec','$ls_codite','$ls_dengru','$ls_densubgru','$ls_densec','$ls_denite','$ls_denspg','$ls_clasif');\">".$ls_codact."</a></td>";
			print "<td><input name='txtdenact".$ls_codact."' type='hidden' id='txtdenact".$ls_codact."' value='$ls_denact'>".$row["denact"]."</td>";
			print "<td>".$row["maract"]."</td>";
			print "<td>".$row["modact"]."</td>";
			print "<td>".$ld_fecregact."<input name='txtobsact".$ls_codact."' type='hidden' id='txtobsact".$ls_codact."' value='".$ls_obsact."'></td>";
			print "</tr>";			
		}
	}
	else
	{
		$io_msg->message("No se encontraron registros");
	}
}
print "</table>";
?>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">   
  function aceptar(ls_codact,ls_maract,ls_modact,ld_fecregact,ld_feccmpact,li_cosact,ls_codconbie,ls_denconbie,ls_codfuefin,
  			       ls_denfuefin,ls_codsitcon,ls_densitcon,ls_codconcom,ls_denconcom,ls_codpai,ls_denpai,ls_codest,ls_denest,ls_codmun,
				   ls_denmun,ls_codcat,ls_dencat,ls_ctaspg,ls_esttipinm,ls_numord,ls_codpro,ld_fecordcom,ls_nompro,li_monord,
				   ls_numsolpag,ld_fecemisol,ls_estdepact,ls_fotact,ls_coddestino,ls_dendestino,ls_codgru,ls_codsubgru,ls_codsec,ls_codite,
				   ls_dengru,ls_densubgru,ls_densec,ls_denite,ls_denspg, ls_clasif)
  {
	f=document.form1;
	if(ls_coddestino!="txtcodact")
	{
		obj=eval("opener.document.form1."+ls_coddestino+"");
		obj.value=ls_codact;
		obj=eval("opener.document.form1."+ls_dendestino+"");
		obj.value=eval("f.txtdenact"+ls_codact+".value");
	}
	else
	{
		opener.document.form1.txtcodact.value=ls_codact;
		opener.document.form1.txtdenact.value=eval("f.txtdenact"+ls_codact+".value");
		opener.document.form1.txtobsact.value=eval("f.txtobsact"+ls_codact+".value");
		opener.document.form1.txtmaract.value=ls_maract;
		opener.document.form1.txtmodact.value=ls_modact;
		opener.document.form1.txtfecregact.value=ld_fecregact;
		opener.document.form1.txtfeccmpact.value=ld_feccmpact;
		opener.document.form1.txtcosact.value=li_cosact;
		opener.document.form1.txtcodfuefin.value=ls_codfuefin;
		opener.document.form1.txtdenfuefin.value=ls_denfuefin;
		opener.document.form1.txtcodsitcon.value=ls_codsitcon;
		opener.document.form1.txtdensitcon.value=ls_densitcon;
		opener.document.form1.txtcodconcom.value=ls_codconcom;
		opener.document.form1.txtdenconcom.value=ls_denconcom;
		opener.document.form1.txtcodconbie.value=ls_codconbie;
		opener.document.form1.txtdenconbie.value=ls_denconbie;
		opener.document.form1.txtcodpai.value=ls_codpai;
		opener.document.form1.txtdespai.value=ls_denpai;
		opener.document.form1.txtcodest.value=ls_codest;
		opener.document.form1.txtdesest.value=ls_denest;
		opener.document.form1.txtcodmun.value=ls_codmun;
		opener.document.form1.txtdesmun.value=ls_denmun;
		//opener.document.form1.txtobsact.value=ls_obsact;
		opener.document.form1.txtcatalogo.value=ls_codcat;
		opener.document.form1.txtcuenta.value=ls_ctaspg;
		opener.document.form1.txtdencat.value=ls_dencat;
		opener.document.form1.txtnumord.value=ls_numord;
		opener.document.form1.txtcodpro.value=ls_codpro;
		opener.document.form1.txtdenpro.value=ls_nompro;
		opener.document.form1.txtmonord.value=li_monord;
		opener.document.form1.txtfecordcom.value=ld_fecordcom;
		opener.document.form1.txtnumsolpag.value=ls_numsolpag;
		opener.document.form1.txtfecemisol.value=ld_fecemisol;
		opener.document.form1.txtdenominacion.value=ls_denspg;
		opener.document.form1.hidstatusact.value="C";
		switch(ls_esttipinm)
		{
			case '1':
				opener.document.form1.radiotipo[0].checked= true;
				break;
			case '2':
				opener.document.form1.radiotipo[1].checked= true;
				break;
			case '3':
				opener.document.form1.radiotipo[2].checked= true;
				break;
	
		}
		switch(ls_estdepact)
		{ 
			case '0':
				opener.document.form1.chkdepreciable.checked= false;
				opener.document.form1.chkdepreciable.disabled= true;
				opener.document.form1.btndepreciacion.disabled= true; 
			break;
			case '1':
				opener.document.form1.chkdepreciable.checked= true;
				opener.document.form1.chkdepreciable.disabled= false;
				opener.document.form1.btndepreciacion.disabled= false;
			break;
		}
		if(ls_fotact!="")
		{opener.document.images["foto"].src="fotosactivos/"+ls_fotact;}
		else
		{opener.document.images["foto"].src="fotosactivos/blanco.jpg";}
		opener.document.form1.txtcodgru.value=ls_codgru;
		opener.document.form1.txtcodsubgru.value=ls_codsubgru;
		opener.document.form1.txtcodsec.value=ls_codsec;
		opener.document.form1.txtcodite.value=ls_codite;
		opener.document.form1.txtdengru.value=ls_dengru;
		opener.document.form1.txtdensubgru.value=ls_densubgru;
		opener.document.form1.txtdensec.value=ls_densec;
		opener.document.form1.txtdenite.value=ls_denite;		
		if (ls_clasif!="")
		{
			fila1=opener.document.getElementById("fila1");
		    fila2=opener.document.getElementById("fila2");
			fila3=opener.document.getElementById("fila3");	
		    fila1.style.display="";
		    fila1.style.display="compact";
		    fila2.style.display="";
		    fila2.style.display="compact";
			fila3.style.display="";
		    fila3.style.display="compact";			
			if(ls_clasif=="1")
			{
				opener.document.form1.btnhojatrabajo.value="Edificios";
			}
			if(ls_clasif=="2")
			{
				opener.document.form1.btnhojatrabajo.value="Instalaciones Fijas";
			}
			if(ls_clasif=="3")
			{
				opener.document.form1.btnhojatrabajo.value="Terrenos";
			}
			opener.document.form1.cmbclasi.value=	ls_clasif;
		}
		else
		{
			fila1=opener.document.getElementById("fila1");
		    fila2=opener.document.getElementById("fila2");	
		    fila1.style.display="";
		    fila1.style.display="none";
		    fila2.style.display="";
		    fila2.style.display="none";
		}
		
	}
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_saf_cat_activo.php";
  f.submit();
  }
  
////////////////////////    Validar la Fecha     ///////////////////////////
function valSep(oTxt){ 
    var bOk = false; 
    var sep1 = oTxt.value.charAt(2); 
    var sep2 = oTxt.value.charAt(5); 
    bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
    bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
    return bOk; 
   } 

   function finMes(oTxt){ 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    var nAno = parseInt(oTxt.value.substr(6), 10); 
    var nRes = 0; 
    switch (nMes){ 
     case 1: nRes = 31; break; 
     case 2: nRes = 28; break; 
     case 3: nRes = 31; break; 
     case 4: nRes = 30; break; 
     case 5: nRes = 31; break; 
     case 6: nRes = 30; break; 
     case 7: nRes = 31; break; 
     case 8: nRes = 31; break; 
     case 9: nRes = 30; break; 
     case 10: nRes = 31; break; 
     case 11: nRes = 30; break; 
     case 12: nRes = 31; break; 
    } 
    return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
   } 

   function valDia(oTxt){ 
    var bOk = false; 
    var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
    bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
    return bOk; 
   } 

   function valMes(oTxt){ 
    var bOk = false; 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
    return bOk; 
   } 

   function valAno(oTxt){ 
    var bOk = true; 
    var nAno = oTxt.value.substr(6); 
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
    if (bOk){ 
     for (var i = 0; i < nAno.length; i++){ 
      bOk = bOk && esDigito(nAno.charAt(i)); 
     } 
    } 
    return bOk; 
   } 

   function valFecha(oTxt){ 
    var bOk = true; 
	
		if (oTxt.value != ""){ 
		 bOk = bOk && (valAno(oTxt)); 
		 bOk = bOk && (valMes(oTxt)); 
		 bOk = bOk && (valDia(oTxt)); 
		 bOk = bOk && (valSep(oTxt)); 
		 if (!bOk){ 
		  alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
		  oTxt.value = "01/01/2005"; 
		  oTxt.focus(); 
		 } 
		}
	 
   }

  function esDigito(sChr){ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
   }

////////////////////////    Validar la Fecha     ///////////////////////////
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums){
if(d.valant != d.value){
	val = d.value
	largo = val.length
	val = val.split(sep)
	val2 = ''
	for(r=0;r<val.length;r++){
		val2 += val[r]	
	}
	if(nums){
		for(z=0;z<val2.length;z++){
			if(isNaN(val2.charAt(z))){
				letra = new RegExp(val2.charAt(z),"g")
				val2 = val2.replace(letra,"")
			}
		}
	}
	val = ''
	val3 = new Array()
	for(s=0; s<pat.length; s++){
		val3[s] = val2.substring(0,pat[s])
		val2 = val2.substr(pat[s])
	}
	for(q=0;q<val3.length; q++){
		if(q ==0){
			val = val3[q]
		}
		else{
			if(val3[q] != ""){
				val += sep + val3[q]
				}
		}
	}
	d.value = val
	d.valant = val
	}
}
function ue_limpiar(periodo)
{
	f=document.form1;
	if(periodo=="Desde")
	{
		f.txtdesde.value="";
	}
	else
	{
		if(periodo=="Hasta")
		{
			f.txthasta.value="";
		}
	}
	
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
