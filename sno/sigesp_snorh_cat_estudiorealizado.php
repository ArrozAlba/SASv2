<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print($as_codper, $ai_codestrea, $as_insestrea)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codper  // Código de Personal
		//				   ai_codestrea  // Código del Estudio Realizado
		//				   as_insestrea  // Instituto
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=30>Código</td>";
		print "<td width=270>Instituto</td>";
		print "<td width=200>Título</td>";
		print "</tr>";
		$ls_sql="SELECT codestrea, tipestrea, insestrea, desestrea, titestrea, calestrea, fecgraestrea, ".
				"		escval, feciniact, fecfinact, soladi, aprestrea, anoaprestrea, horestrea ".
				"  FROM sno_estudiorealizado ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND insestrea like '".$as_insestrea."'".
				" ORDER BY codestrea ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_codestrea=$row["codestrea"];
				$ls_tipestrea=$row["tipestrea"];
				$ls_insestrea=$row["insestrea"];
				$ls_desestrea=$row["desestrea"];
				$ls_titestrea=$row["titestrea"];
				$li_calestrea=$row["calestrea"];
				$ls_escval=$row["escval"];
				$li_calestrea=$io_fun_nomina->uf_formatonumerico($li_calestrea);
				$ld_fecgraestrea=$io_funciones->uf_convertirfecmostrar($row["fecgraestrea"]);			
				$ld_feciniact=$io_funciones->uf_convertirfecmostrar($row["feciniact"]);			
				$ld_fecfinact=$io_funciones->uf_convertirfecmostrar($row["fecfinact"]);			
				$ls_aprestrea=$row["aprestrea"];
				$ls_anoaprestrea=$row["anoaprestrea"];
				$ls_horestrea=$row["horestrea"];
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: aceptar('$li_codestrea','$ls_tipestrea','$ls_insestrea','$ls_titestrea',";
				print "'$li_calestrea','$ld_fecgraestrea','$ls_escval','$ld_feciniact','$ld_fecfinact','$ls_desestrea',";
				print "'$ls_aprestrea','$ls_anoaprestrea','$ls_horestrea');\">".$li_codestrea."</a></td>";
				print "<td>".$ls_insestrea."</td>";
				print "<td>".$ls_titestrea."</td>";
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
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Estudio Realizado</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Estudio Realizado </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodestrea" type="text" id="txtcodestrea" size="30" maxlength="3" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Instituto</div></td>
        <td><div align="left">
          <input name="txtinsestrea" type="text" id="txtinsestrea" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();">
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
		$li_codestrea="%".$_POST["txtcodestrea"]."%";
		$ls_insestrea="%".$_POST["txtinsestrea"]."%";
		$ls_codper=$_POST["txtcodper"];
		uf_print($ls_codper, $li_codestrea, $ls_insestrea);
	}
	else
	{
		$ls_codper=$_GET["codper"];
		$li_codestrea="%%";
		$ls_insestrea="%%";
		uf_print($ls_codper, $li_codestrea, $ls_insestrea);
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
function aceptar(codestrea,tipestrea,insestrea,titestrea,calestrea,fecgraestrea,escval,feciniact,fecfinact,desestrea,aprestrea,
				 anoaprestrea,horestrea)
{
	opener.document.form1.txtcodestrea.value=codestrea;
	opener.document.form1.txtcodestrea.readOnly=true;	
	opener.document.form1.cmbtipestrea.value=tipestrea;
	opener.document.form1.txtinsestrea.value=insestrea;
	opener.document.form1.txttitestrea.value=titestrea;
	opener.document.form1.txtcalestrea.value=calestrea;
	opener.document.form1.txtfecgraestrea.value=fecgraestrea;
	opener.document.form1.txtescval.value=escval;
	opener.document.form1.txtfeciniact.value=feciniact;
	opener.document.form1.txtfecfinact.value=fecfinact;
	opener.document.form1.txtdesestrea.value=desestrea;
	opener.document.form1.cmbaprestrea.value=aprestrea;
	opener.document.form1.txtanoaprestrea.value=anoaprestrea;
	opener.document.form1.txthorestrea.value=horestrea;
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
  	f.action="sigesp_snorh_cat_estudiorealizado.php";
  	f.submit();
}
</script>
</html>
