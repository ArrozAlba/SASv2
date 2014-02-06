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
   function uf_print($as_codper,$ad_feciniper,$ad_fecfinper)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codper  // Código de Personal
		//				   ad_feciniper  // Fecha de Inicio
		//				   ad_fecfinper  // Fecha Fin
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
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
		$ad_feciniper=$io_funciones->uf_convertirdatetobd($ad_feciniper);
		$ad_fecfinper=$io_funciones->uf_convertirdatetobd($ad_fecfinper);
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Número</td>";
		print "<td width=50>Fecha Inicio</td>";
		print "<td width=50>Fecha Fin</td>";
		print "<td width=90>Tipo</td>";
		print "<td width=250>Observación</td>";
		print "</tr>";
		$ls_sql="SELECT codemp, codper, numper, feciniper, fecfinper, numdiaper, afevacper, tipper, obsper, remper, tothorper ".
				"  FROM sno_permiso ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codper='".$as_codper."'";
		if($ad_feciniper!="")
		{
			$ls_sql=$ls_sql."   AND feciniper>='".$ad_feciniper."'";
		}
		if($ad_fecfinper!="")
		{
			$ls_sql=$ls_sql."   AND feciniper<='".$ad_fecfinper."'";
		}
		$ls_sql=$ls_sql." ORDER BY codper ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_numper=$row["numper"];
				$ld_feciniper=$io_funciones->uf_convertirfecmostrar($row["feciniper"]);
				$ld_fecfinper=$io_funciones->uf_convertirfecmostrar($row["fecfinper"]);
				$li_numdiaper=$row["numdiaper"];
				$li_afevacper=$row["afevacper"];
				$li_tipper=$row["tipper"];
				$ls_obsper=$row["obsper"];
				$ls_remper=$row["remper"];
				$ls_numhoras=$row["tothorper"];
				
				
				switch ($li_tipper)
				{
					case '1':
						$ls_destipper='ESTUDIO';
					break;
					case '2':
						$ls_destipper='MEDICO';
					break;
					case '3':
						$ls_destipper='TRAMITES';
					break;
					case '4':
						$ls_destipper='OTRO';
					break;
					case '5':
						$ls_destipper='REPOSO';
					break;
				
				}
	
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: aceptar('$li_numper','$ld_feciniper','$ld_fecfinper','$li_numdiaper','$li_afevacper','$li_tipper','$ls_obsper','$ls_remper','$ls_numhoras');\">".$li_numper."</a></td>";
				print "<td>".$ld_feciniper."</td>";
				print "<td>".$ld_fecfinper."</td>";
				print "<td>".$ls_destipper."</td>";
				print "<td>".$ls_obsper."</td>";
				print "</tr>";			
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
<title>Cat&aacute;logo de Permiso</title>
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
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Permiso </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="106" height="22"><div align="right">Fecha Inicio </div></td>
        <td width="127"><div align="left">
            <input name="txtfeciniper" type="text" id="txtfeciniper" size="13" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true"  onKeyUp="javascript: ue_mostrar(this,event);">
        </div></td>
        <td width="62"><div align="right">Fecha Fin</div></td>
        <td width="195"><div align="left">
          <input name="txtfecfinper" type="text" id="txtfecfinper" size="13" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true"  onKeyUp="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td colspan="3"><div align="right"><a href="javascript: ue_search();">
          <img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	if($ls_operacion=="BUSCAR")
	{
		$ld_feciniper=$_POST["txtfeciniper"];
		$ld_fecfinper=$_POST["txtfecfinper"];
		$ls_codper=$_POST["txtcodper"];
		uf_print($ls_codper,$ld_feciniper,$ld_fecfinper);
	}
	else
	{
		$ld_feciniper="";
		$ld_fecfinper="";
		$ls_codper=$_GET["codper"];
		uf_print($ls_codper,$ld_feciniper,$ld_fecfinper);
	}
	unset($io_fun_nomina);
?>
</div>
          <input name="txtcodper" type="hidden" id="txtcodper" value="<?php print $ls_codper;?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function aceptar(numper,feciniper,fecfinper,numdiaper,afevacper,tipper,obsper,remper, numhoras)
{
	opener.document.form1.txtnumper.value=numper;
	opener.document.form1.txtnumper.readOnly=true;
    opener.document.form1.txtfeciniper.value=feciniper;
	opener.document.form1.txtfecfinper.value=fecfinper;
    opener.document.form1.txtnumdiaper.value=numdiaper;
	 opener.document.form1.txthoras.value=numhoras;
    opener.document.form1.cmbtipper.value=tipper;
    opener.document.form1.txtobsper.value=obsper;
	opener.document.form1.chkafevacper.checked=false;
	opener.document.form1.chkremper.checked=false;
	if (afevacper=="1")
	{
		opener.document.form1.chkafevacper.checked=true;
	}
	if (remper=="1")
	{
		opener.document.form1.chkremper.checked=true;
	}
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
  	f.action="sigesp_snorh_cat_permiso.php";
  	f.submit();
}
</script>
</html>
