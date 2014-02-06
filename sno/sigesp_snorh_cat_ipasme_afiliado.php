<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}

   //--------------------------------------------------------------
   function uf_print($as_codper, $as_coddep,  $as_tipo)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_coddep  // Código de la Dependencia
		//				   as_codper  // código de Personal
		//				   as_tipo  // Verifica de donde se está llamando el catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=300>Apellidos y Nombre</td>";
		print "<td width=140>Dependencia</td>";
		print "</tr>";
		$ls_sql="SELECT sno_ipasme_afiliado.codper, sno_ipasme_afiliado.tiptraafi, sno_ipasme_afiliado.coddep, sno_ipasme_afiliado.actlabafi, ".
				"		sno_ipasme_afiliado.tipafiafi, sno_ipasme_afiliado.codban, sno_ipasme_afiliado.cuebanafi, sno_ipasme_afiliado.tipcueafi, ".
				"		sno_ipasme_afiliado.codent, sno_ipasme_afiliado.codmun, sno_ipasme_afiliado.codloc, sno_ipasme_afiliado.urbafi, ".
				"		sno_ipasme_afiliado.aveafi, sno_ipasme_afiliado.nomresafi, sno_ipasme_afiliado.pisafi, sno_ipasme_afiliado.zonafi, ".
				"		sno_ipasme_afiliado.numresafi, sno_personal.nomper, sno_personal.apeper, sno_personal.fecnacper, sno_ipasme_dependencias.desdep ".
				"  FROM sno_ipasme_afiliado, sno_personal, sno_ipasme_dependencias ".
				" WHERE sno_ipasme_afiliado.codemp = sno_personal.codemp ".
				"	AND sno_ipasme_afiliado.codper = sno_personal.codper ".
				"   AND sno_ipasme_afiliado.codemp = sno_ipasme_dependencias.codemp ".
				"	AND sno_ipasme_afiliado.coddep = sno_ipasme_dependencias.coddep ".
				"	AND	sno_ipasme_afiliado.codemp='".$ls_codemp."'".
				"   AND sno_ipasme_afiliado.coddep like '".$as_coddep."' ".
				"	AND sno_ipasme_afiliado.codper like '".$as_codper."'".
				" ORDER BY sno_ipasme_afiliado.codper ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_nomper=$row["apeper"].", ".$row["nomper"];
				$ls_coddep=$row["coddep"];
				$ls_desdep=$row["desdep"];
				$ls_tiptraafi=$row["tiptraafi"];
				$ls_actlabafi=$row["actlabafi"];
				$ls_tipafiafi=$row["tipafiafi"];
				$ls_codban=$row["codban"];
				$ls_cuebanafi=$row["cuebanafi"];
				$ls_tipcueafi=$row["tipcueafi"];
				$ls_codent=$row["codent"];
				$ls_codmun=$row["codmun"];
				$ls_codloc=$row["codloc"];
				$ls_urbafi=$row["urbafi"];
				$ls_aveafi=$row["aveafi"];
				$ls_nomresafi=$row["nomresafi"];
				$ls_pisafi=$row["pisafi"];
				$ls_zonafi=$row["zonafi"];
				$ls_numresafi=$row["numresafi"];
				$ld_fecnacper=$io_funciones->uf_convertirfecmostrar($row["fecnacper"]);
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codper','$ls_nomper','$ls_coddep','$ls_desdep','$ls_tiptraafi',";
						print "'$ls_actlabafi','$ls_tipafiafi','$ls_codban','$ls_cuebanafi','$ls_tipcueafi','$ls_codent',";
						print "'$ls_codmun','$ls_codloc','$ls_urbafi','$ls_aveafi','$ls_nomresafi','$ls_pisafi',";
						print "'$ls_zonafi','$ls_numresafi','$ld_fecnacper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_desdep."</td>";
						print "</tr>";			
						break;

					case "replisafides":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisafides('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_desdep."</td>";
						print "</tr>";			
						break;
					
					case "replisafihas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisafihas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_desdep."</td>";
						print "</tr>";			
						break;

					case "replisbendes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisbendes('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_desdep."</td>";
						print "</tr>";			
						break;
					
					case "replisbenhas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisbenhas('$ls_codper');\">".$ls_codper."</a></td>";
						print "<td>".$ls_nomper."</td>";
						print "<td>".$ls_desdep."</td>";
						print "</tr>";			
						break;
				}
			}
			$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Afiliado</title>
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
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Afiliado </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="121" height="22"><div align="right">C&oacute;digo Personal</div></td>
        <td width="373"><div align="left">
          <input name="txtcodper" type="text" id="txtcodper" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">C&oacute;digo  Dependencia </div></td>
        <td><div align="left">
          <input name="txtcoddep" type="text" id="txtcoddep" size="30" maxlength="11" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codper="%".$_POST["txtcodper"]."%";
		$ls_coddep="%".$_POST["txtcoddep"]."%";
		uf_print($ls_codper, $ls_coddep, $ls_tipo);
	}
	else
	{
		$ls_codper="%%";
		$ls_coddep="%%";
		uf_print($ls_codper, $ls_coddep, $ls_tipo);
	}	
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codper,nomper,coddep,desdep,tiptraafi,actlabafi,tipafiafi,codban,cuebanafi,tipcueafi,codent,codmun,codloc,urbafi,
				 aveafi,nomresafi,pisafi,zonafi,numresafi,fecnacper)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtnomper.readOnly=true;
	opener.document.images["personal"].style.visibility="hidden";
	opener.document.form1.txtcoddep.value=coddep;
	opener.document.form1.txtcoddep.readOnly=true;
    opener.document.form1.txtdesdep.value=desdep;
	opener.document.form1.txtdesdep.readOnly=true;
    opener.document.form1.cmbtiptraafi.value=tiptraafi;
    opener.document.form1.cmbactlabafi.value=actlabafi;
    opener.document.form1.cmbtipafiafi.value=tipafiafi;
    opener.document.form1.cmbcodban.value=codban;
    opener.document.form1.txtcuebanafi.value=cuebanafi;
    opener.document.form1.cmbtipcueafi.value=tipcueafi;
    opener.document.form1.cmbcodent.value=codent;
	opener.document.form1.cmbcodent.onchange();
    opener.document.form1.cmbcodmun.value=codmun;
	opener.document.form1.cmbcodmun.onchange();
    opener.document.form1.cmbcodloc.value=codloc;
    opener.document.form1.txturbafi.value=urbafi;
    opener.document.form1.txtaveafi.value=aveafi;
    opener.document.form1.txtnomresafi.value=nomresafi;
    opener.document.form1.txtpisafi.value=pisafi;
    opener.document.form1.txtzonafi.value=zonafi;
    opener.document.form1.txtnumresafi.value=numresafi;
    opener.document.form1.txtfecnacper.value=fecnacper;
	opener.document.form1.existe.value="TRUE";
	opener.document.form1.btnbeneficiario.disabled=false;
	close();
}

function aceptarreplisafides(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	close();
}

function aceptarreplisafihas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango de Personal Inválido");
	}
}

function aceptarreplisbendes(codper)
{
	opener.document.form1.txtcodperdes.value=codper;
	opener.document.form1.txtcodperdes.readOnly=true;
	opener.document.form1.txtcodperhas.value="";
	close();
}

function aceptarreplisbenhas(codper)
{
	if(opener.document.form1.txtcodperdes.value<=codper)
	{
		opener.document.form1.txtcodperhas.value=codper;
		opener.document.form1.txtcodperhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango de Personal Inválido");
	}
}

function ue_mostrar(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (keycode == 13)
	{
		ue_search();
		return false;
	}
	else
		return true
}

function ue_search(existe)
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_snorh_cat_ipasme_afiliado.php?tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
