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
   function uf_print($as_codtippre, $as_codper, $as_stapre, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codtippre  // Código del tipo de Prestamo
		//				   as_codper  // Código del personal
		//				   as_stapre  // Estatus del Prestamo
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
		print "<td width=40>Código</td>";
		print "<td width=180>Personal</td>";
		print "<td width=180>Tipo</td>";
		print "<td width=50>Estatus</td>";
		print "<td width=50>Monto</td>";
		print "</tr>";
		$ls_sql="SELECT sno_prestamos.codper, sno_prestamos.numpre, sno_prestamos.codtippre, sno_prestamos.codconc, sno_prestamos.monpre, ".
				"		sno_prestamos.numcuopre, sno_prestamos.perinipre, sno_prestamos.monamopre, sno_prestamos.stapre, sno_prestamos.fecpre, ".
				"		sno_prestamos.obsrecpre, sno_prestamos.obssuspre, sno_personal.nomper, sno_personal.apeper, sno_tipoprestamo.destippre, ".
				"       sno_concepto.nomcon, sno_prestamosperiodo.feciniper, sno_prestamosperiodo.fecfinper, sno_personalnomina.sueper, sno_prestamos.tipcuopre,".
				"		(SELECT count(numcuo) ".
				"		   FROM sno_prestamosperiodo ".
				"		  WHERE sno_prestamosperiodo.estcuo=0 ".
				"   		AND sno_prestamos.codemp = sno_prestamosperiodo.codemp ".
				"   		AND sno_prestamos.codnom = sno_prestamosperiodo.codnom ".
				"   		AND sno_prestamos.codper = sno_prestamosperiodo.codper ".
				"   		AND sno_prestamos.numpre = sno_prestamosperiodo.numpre ".
				"  			AND sno_prestamos.codtippre = sno_prestamosperiodo.codtippre) as cuofal, ".
				"		(SELECT MAX(moncuo) ".
				"		   FROM sno_prestamosperiodo ".
				"		  WHERE sno_prestamosperiodo.estcuo = 0".
				"           AND sno_prestamos.codemp = sno_prestamosperiodo.codemp ".
				"			AND sno_prestamos.codnom = sno_prestamosperiodo.codnom ".
				"   		AND sno_prestamos.codper = sno_prestamosperiodo.codper ".
				"			AND sno_prestamos.numpre = sno_prestamosperiodo.numpre ".
				"  			AND sno_prestamos.codtippre = sno_prestamosperiodo.codtippre ".
				"		  GROUP BY sno_prestamosperiodo.codemp, sno_prestamosperiodo.codnom, sno_prestamosperiodo.numpre,  ".
				"				sno_prestamosperiodo.codper, sno_prestamosperiodo.codtippre ) as cuopre ".
				"  FROM sno_prestamos, sno_personal, sno_personalnomina, sno_tipoprestamo, sno_concepto, sno_prestamosperiodo ".
				" WHERE sno_prestamosperiodo.numcuo=1 ".
				"	AND	sno_prestamos.codemp='".$ls_codemp."' ".
				"   AND sno_prestamos.codnom='".$ls_codnom."' ".
				"   AND sno_prestamos.codtippre like '".$as_codtippre."' ".
				"	AND sno_prestamos.codper like '".$as_codper."' ".
				"	AND sno_prestamos.stapre = ".$as_stapre." ".
				"   AND sno_prestamos.codemp = sno_personal.codemp ".
				"   AND sno_prestamos.codper = sno_personal.codper ".
				"   AND sno_prestamos.codemp = sno_personalnomina.codemp ".
				"   AND sno_prestamos.codnom = sno_personalnomina.codnom ".
				"   AND sno_prestamos.codper = sno_personalnomina.codper ".
				"   AND sno_prestamos.codemp = sno_tipoprestamo.codemp ".
				"   AND sno_prestamos.codnom = sno_tipoprestamo.codnom ".
				"   AND sno_prestamos.codtippre = sno_tipoprestamo.codtippre ".
				"   AND sno_prestamos.codemp = sno_concepto.codemp ".
				"   AND sno_prestamos.codnom = sno_concepto.codnom ".
				"   AND sno_prestamos.codconc = sno_concepto.codconc ".
				"   AND sno_prestamos.codemp = sno_prestamosperiodo.codemp ".
				"   AND sno_prestamos.codnom = sno_prestamosperiodo.codnom ".
				"   AND sno_prestamos.codper = sno_prestamosperiodo.codper ".
				"   AND sno_prestamos.codtippre = sno_prestamosperiodo.codtippre ".
				"   AND sno_prestamos.numpre = sno_prestamosperiodo.numpre ".
				" ORDER BY sno_prestamos.numpre ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_sueper=$row["sueper"];
				$li_numpre=$row["numpre"];
				$ls_codper=$row["codper"];
				$ls_nomapeper=$row["nomper"]." ".$row["apeper"];
				$ls_codtippre=$row["codtippre"];
				$ls_destippre=$row["destippre"];
				$ls_codconc=$row["codconc"];
				$ls_nomcon=$row["nomcon"];
				$ls_perinipre=$row["perinipre"];
				$ld_feciniper=$io_funciones->uf_formatovalidofecha($row["feciniper"]);
				$ld_fecfinper=$io_funciones->uf_formatovalidofecha($row["fecfinper"]);
				$ld_feciniper=$io_funciones->uf_convertirfecmostrar($ld_feciniper);
				$ld_fecfinper=$io_funciones->uf_convertirfecmostrar($ld_fecfinper);
				$li_stapre=$row["stapre"];
				$li_monpre=$row["monpre"];
				$li_numcuopre=$row["numcuopre"];
				$li_monamopre=$row["monamopre"];
				$li_cuofal=$row["cuofal"];
				$li_saldo=($li_monpre-$li_monamopre);
				$li_cuota=$row["cuopre"];
				$ls_tipcuopre=$row["tipcuopre"];
				$li_monpre=$io_fun_nomina->uf_formatonumerico($li_monpre);
				$li_monamopre=$io_fun_nomina->uf_formatonumerico($li_monamopre);
				$li_saldo=$io_fun_nomina->uf_formatonumerico($li_saldo);
				$li_cuota=$io_fun_nomina->uf_formatonumerico($li_cuota);
				$ls_status="";
				switch ($li_stapre)
				{
					case 1:
						$ls_status="ACTIVO";
						break;
		
					case 2:
						$ls_status="SUSPENDIDO";
						break;
		
					case 3:
						$ls_status="CANCELADO";
						break;
				}			
				
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas >";
						print "<td align='center'><a href=\"javascript: aceptar('$li_numpre','$ls_codper','$ls_nomapeper','$ls_codtippre',";
						print "'$ls_destippre','$ls_codconc','$ls_nomcon','$ls_perinipre','$ld_feciniper','$ld_fecfinper','$li_stapre',";
						print "'$li_monpre','$li_numcuopre','$li_monamopre','$li_saldo','$li_cuota','$li_cuofal','$li_sueper','$ls_tipcuopre');\">".$li_numpre."</a></td>";
						print "<td align='center'>".$ls_nomapeper."</td>";
						print "<td align='center'>".$ls_destippre."</td>";
						print "<td align='center'>".$ls_status."</td>";
						print "<td align='right'>".$li_monpre."</td>";
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
<title>Cat&aacute;logo de Prestamo</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Prestamo </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="103" height="22"><div align="right">Tipo de Prestamo</div></td>
        <td width="391"><div align="left">
          <input name="txtcodtippre" type="text" id="txtcodtippre" size="10" maxlength="10">        
          <a href="javascript: ue_buscarprestamo();"><img id="tipo" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
          <input name="txtdestippre" type="text" class="sin-borde" id="txtdestippre" size="50" maxlength="100" readOnly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Personal </div></td>
        <td><div align="left">
          <input name="txtcodper" type="text" id="txtcodper" size="10" maxlength="100">
          <a href="javascript: ue_buscarpersonal();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
          <input name="txtnomper" type="text" class="sin-borde" id="txtnomper" size="50" maxlength="100" readOnly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Estatus</div></td>
        <td><div align="left">
          <select name="cmbstapre" id="cmbstapre">
            <option value="1" selected>Activo</option>
            <option value="2">Suspendido</option>
            <option value="3">Cancelado</option>
          </select> 
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
		$ls_codtippre="%".$_POST["txtcodtippre"]."%";
		$ls_codper="%".$_POST["txtcodper"]."%";
		$ls_stapre=$_POST["cmbstapre"];
		uf_print($ls_codtippre, $ls_codper, $ls_stapre, $ls_tipo);
	}
	else
	{
		$ls_codtippre="%%";
		$ls_codper="%%";
		$ls_stapre=1;
		uf_print($ls_codtippre, $ls_codper, $ls_stapre, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(numpre,codper,nomapeper,codtippre,destippre,codconc,nomcon,perinipre,feciniper,fecfinper,stapre,monpre,numcuopre,
				 monamopre,saldo,cuota,cuofal,sueper,tipcuopre)
{
	opener.document.form1.txtnumpre.value=numpre;
    opener.document.form1.txtcodper.value=codper;
    opener.document.form1.txtcodper.readOnly=true;
    opener.document.form1.txtnomper.value=nomapeper;
    opener.document.form1.txtnomper.readOnly=true;
	opener.document.images["personal"].style.visibility="hidden";	
    opener.document.form1.txtcodtippre.value=codtippre;
    opener.document.form1.txtcodtippre.readOnly=true;
    opener.document.form1.txtdestippre.value=destippre;
    opener.document.form1.txtdestippre.readOnly=true;
	opener.document.images["tipo"].style.visibility="hidden";	
    opener.document.form1.txtcodconc.value=codconc;
    opener.document.form1.txtcodconc.readOnly=true;
    opener.document.form1.txtnomcon.value=nomcon;
    opener.document.form1.txtnomcon.readOnly=true;
	opener.document.images["concepto"].style.visibility="hidden";	
    opener.document.form1.txtperinipre.value=perinipre;
    opener.document.form1.txtperinipre.readOnly=true;
    opener.document.form1.txtfecdesper.value=feciniper;
    opener.document.form1.txtfecdesper.readOnly=true;
    opener.document.form1.txtfechasper.value=fecfinper;
    opener.document.form1.txtfechasper.readOnly=true;
	opener.document.images["periodo"].style.visibility="hidden";	
	opener.document.images["cuota"].style.visibility="hidden";	
    opener.document.form1.txtmonpre.value=monpre;
	opener.document.form1.txtmonpre.readOnly=true;
    opener.document.form1.txtnumcuopre.value=numcuopre;
	opener.document.form1.txtnumcuopre.readOnly=true;
    opener.document.form1.cmbstapre.value=stapre;
    opener.document.form1.cmbstapre.disabled=true;
    opener.document.form1.txtmonamopre.value=monamopre;
    opener.document.form1.txtmonamopre.readOnly=true;
    opener.document.form1.txtsalactpre.value=saldo;
    opener.document.form1.txtsalactpre.readOnly=true;
    opener.document.form1.txtmoncuopre.value=cuota;
    opener.document.form1.txtmoncuopre.readOnly=true;
    opener.document.form1.btncuotas.disabled=false;
    opener.document.form1.btnrecalcular.disabled=false;
    opener.document.form1.btnsuspender.disabled=false;
    opener.document.form1.btnamortizar.disabled=false;
    opener.document.form1.btnrefinanciar.disabled=false;
	opener.document.form1.txtcuofal.value=cuofal;
	opener.document.form1.cmbtipcuopre.value=tipcuopre;
    opener.document.form1.cmbtipcuopre.disabled=true;
	opener.document.form1.txttipcuopre.value=tipcuopre;
	opener.document.form1.txtsueper.value=sueper;
	opener.document.form1.existe.value="TRUE";		
	close();
}

function ue_buscarprestamo()
{
	window.open("sigesp_sno_cat_tipoprestamo.php?tipo=prestamo","_blank","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpersonal()
{
	window.open("sigesp_sno_cat_personalnomina.php?tipo=catprestamo","_blank","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_search(existe)
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_sno_cat_prestamo.php?tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
