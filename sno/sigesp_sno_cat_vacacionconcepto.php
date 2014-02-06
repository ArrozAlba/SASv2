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
   function uf_print($as_codconc, $as_nomcon, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codconc  // Código del concepto
		//				   as_nomcon  // nombre del concepto
		//				   as_tipo  // Tipo de Llamada del catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
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
        $ls_codnom=$_SESSION["la_nomina"]["codnom"];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=80>Código</td>";
		print "<td width=300>Nombre</td>";
		print "<td width=120>Signo</td>";
		print "</tr>";
		$ls_sql="SELECT sno_conceptovacacion.codconc, sno_conceptovacacion.forsalvac, sno_conceptovacacion.acumaxsalvac, ".
				"		sno_conceptovacacion.minsalvac, sno_conceptovacacion.maxsalvac, sno_conceptovacacion.consalvac, ".
				"		sno_conceptovacacion.forpatsalvac, sno_conceptovacacion.minpatsalvac, sno_conceptovacacion.maxpatsalvac, ".
				"		sno_conceptovacacion.forreivac, sno_conceptovacacion.acumaxreivac, sno_conceptovacacion.minreivac, ".
				"		sno_conceptovacacion.maxreivac, sno_conceptovacacion.conreivac, sno_conceptovacacion.forpatreivac, ".
				"		sno_conceptovacacion.minpatreivac, sno_conceptovacacion.maxpatreivac, sno_concepto.nomcon,sno_concepto.sigcon ".
				"  FROM sno_conceptovacacion, sno_concepto ".
				" WHERE sno_conceptovacacion.codemp='".$ls_codemp."'".
				"   AND sno_conceptovacacion.codnom='".$ls_codnom."'".
				"   AND sno_concepto.codconc like '".$as_codconc."' AND sno_concepto.nomcon like '".$as_nomcon."'".
				"   AND sno_conceptovacacion.codemp=sno_concepto.codemp ".
				"   AND sno_conceptovacacion.codnom=sno_concepto.codnom ".
				"   AND sno_conceptovacacion.codconc=sno_concepto.codconc ".
				" ORDER BY sno_conceptovacacion.codconc ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codconc=$row["codconc"];
				$ls_nomcon=$row["nomcon"];
				$ls_sigcon=$row["sigcon"];
				$ls_forsalvac=$row["forsalvac"];
				$li_acumaxsalvac=$row["acumaxsalvac"];
				$li_minsalvac=$row["minsalvac"];
				$li_maxsalvac=$row["maxsalvac"];
				$ls_consalvac=$row["consalvac"];
				$ls_forpatsalvac=$row["forpatsalvac"];
				$li_minpatsalvac=$row["minpatsalvac"];
				$li_maxpatsalvac=$row["maxpatsalvac"];
				$ls_forreivac=$row["forreivac"];
				$li_acumaxreivac=$row["acumaxreivac"];
				$li_minreivac=$row["minreivac"];
				$li_maxreivac=$row["maxreivac"];
				$ls_conreivac=$row["conreivac"];
				$ls_forpatreivac=$row["forpatreivac"];
				$li_minpatreivac=$row["minpatreivac"];
				$li_maxpatreivac=$row["maxpatreivac"];
				$li_acumaxsalvac=$io_fun_nomina->uf_formatonumerico($li_acumaxsalvac);
				$li_minsalvac=$io_fun_nomina->uf_formatonumerico($li_minsalvac);
				$li_maxsalvac=$io_fun_nomina->uf_formatonumerico($li_maxsalvac);
				$li_minpatsalvac=$io_fun_nomina->uf_formatonumerico($li_minpatsalvac);
				$li_maxpatsalvac=$io_fun_nomina->uf_formatonumerico($li_maxpatsalvac);
				$li_acumaxreivac=$io_fun_nomina->uf_formatonumerico($li_acumaxreivac);
				$li_minreivac=$io_fun_nomina->uf_formatonumerico($li_minreivac);
				$li_maxreivac=$io_fun_nomina->uf_formatonumerico($li_maxreivac);
				$li_minpatreivac=$io_fun_nomina->uf_formatonumerico($li_minpatreivac);
				$li_maxpatreivac=$io_fun_nomina->uf_formatonumerico($li_maxpatreivac);
	
				switch ($ls_sigcon)
				{
					case "A":
						$ls_sigcon="Asignación";
						break;
	
					case "D":
						$ls_sigcon="Deducción";
						break;
	
					case "P":
						$ls_sigcon="Aporte Patronal";
						break;
	
					case "R":
						$ls_sigcon="Reporte";
						break;
	
					case "B":
						$ls_sigcon="Reintegro Deducción";
						break;
	
					case "E":
						$ls_sigcon="Reintegro Asignación";
						break;
				}			
	
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codconc','$ls_nomcon','$ls_sigcon','$ls_forsalvac',";
						print "'$li_acumaxsalvac','$li_minsalvac','$li_maxsalvac','$ls_consalvac','$ls_forpatsalvac','$li_minpatsalvac',";
						print "'$li_maxpatsalvac','$ls_forreivac','$li_acumaxreivac','$li_minreivac','$li_maxreivac','$ls_conreivac',";
						print "'$ls_forpatreivac','$li_minpatreivac','$li_maxpatreivac');\">".$ls_codconc."</a></td>";
						print "<td>".$ls_nomcon."</td>";
						print "<td>".$ls_sigcon."</td>";
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
		unset($ls_codnom);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Vacacion Concepto</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Vacaci&oacute;n Concepto </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodconc" type="text" id="txtcodconc" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtnomcon" type="text" id="txtnomcon" size="30" maxlength="30" onKeyPress="javascript: ue_mostrar(this,event);">
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
		$ls_codconc="%".$_POST["txtcodconc"]."%";
		$ls_nomcon="%".$_POST["txtnomcon"]."%";
		uf_print($ls_codconc, $ls_nomcon, $ls_tipo);
	}
	else
	{
		$ls_codconc="%%";
		$ls_nomcon="%%";
		uf_print($ls_codconc, $ls_nomcon, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codconc,nomcon,sigcon,forsalvac,acumaxsalvac,minsalvac,maxsalvac,consalvac,forpatsalvac,minpatsalvac,maxpatsalvac,
				 forreivac,acumaxreivac,minreivac,maxreivac,conreivac,forpatreivac,minpatreivac,maxpatreivac)
{
	opener.document.form1.txtcodconc.value=codconc;
	opener.document.form1.txtcodconc.readOnly=true;
	opener.document.form1.txtnomcon.value=nomcon;
	opener.document.form1.txtnomcon.readOnly=true;
	opener.document.form1.txtsigcon.value=sigcon;
	opener.document.form1.txtsigcon.readOnly=true;
	opener.document.form1.txtforsalvac.value=forsalvac;
	opener.document.form1.txtacumaxsalvac.value=acumaxsalvac;
	opener.document.form1.txtminsalvac.value=minsalvac;
	opener.document.form1.txtmaxsalvac.value=maxsalvac;
	opener.document.form1.txtconsalvac.value=consalvac;
	opener.document.form1.txtforpatsalvac.value=forpatsalvac;
	opener.document.form1.txtminpatsalvac.value=minpatsalvac;
	opener.document.form1.txtmaxpatsalvac.value=maxpatsalvac;
	opener.document.form1.txtforreivac.value=forreivac;
	opener.document.form1.txtacumaxreivac.value=acumaxreivac;
	opener.document.form1.txtminreivac.value=minreivac;
	opener.document.form1.txtmaxreivac.value=maxreivac;
	opener.document.form1.txtconreivac.value=conreivac;
	opener.document.form1.txtforpatreivac.value=forpatreivac;
	opener.document.form1.txtminpatreivac.value=minpatreivac;
	opener.document.form1.txtmaxpatreivac.value=maxpatreivac;
	opener.document.images["concepto"].style.visibility="hidden";
	opener.document.form1.existe.value="TRUE";		
	close();
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

function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_sno_cat_vacacionconcepto.php?tipo=<?PHP print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
