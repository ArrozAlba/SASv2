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
	function uf_print($as_codper, $ai_codtraant, $as_emptraant)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codper  // código de personal
		//				   ai_codtraant  // codigo de trabajo anterior
		//				   as_emptraant  // empresa
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
		print "<td width=270>Empresa</td>";
		print "<td width=100>Cargo</td>";
		print "<td width=100>Sueldo</td>";
		print "</tr>";
		
		if ($ai_codtraant >= 0 )  {
		$ls_sql="SELECT codtraant, emptraant, ultcartraant, ultsuetraant, fecingtraant, fecrettraant, emppubtraant,".
				"       codded, anolab, meslab, dialab, ".
				"		(SELECT desded FROM sno_dedicacion ".
				"		  WHERE sno_dedicacion.codemp = sno_trabajoanterior.codemp ".
				"           AND sno_dedicacion.codded = sno_trabajoanterior.codded) AS desded ".
				"  FROM sno_trabajoanterior ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codper='".$as_codper."'".
				/*"   AND codtraant like '".$ai_codtraant."'".*/
				"   AND emptraant like '".$as_emptraant."'".
				" ORDER BY codtraant ";
				/*print $ls_sql."<br>";*/
		
		} 	
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_codtraant=$row["codtraant"];
				$ls_emptraant=$row["emptraant"];
				$ls_ultcartraant=$row["ultcartraant"];
				$li_ultsuetraant=$row["ultsuetraant"];
				$li_ultsuetraant=$io_fun_nomina->uf_formatonumerico($li_ultsuetraant);
				$ld_fecingtraant=$io_funciones->uf_convertirfecmostrar($row["fecingtraant"]);				
				$ld_fecrettraant=$io_funciones->uf_convertirfecmostrar($row["fecrettraant"]);				
				$ls_emppubtraant=$row["emppubtraant"];
				$ls_codded=$row["codded"];
				$ls_desded=$row["desded"];
				$li_anolab=$row["anolab"];
				$li_meslab=$row["meslab"];
				$li_dialab=$row["dialab"];
	
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: aceptar('$li_codtraant','$ls_emptraant','$ls_ultcartraant','$li_ultsuetraant',";
				print "'$ld_fecingtraant','$ld_fecrettraant','$ls_emppubtraant','$ls_codded','$ls_desded','$li_anolab','$li_meslab','$li_dialab');\">".$li_codtraant."</a></td>";
				print "<td>".$ls_emptraant."</td>";
				print "<td>".$ls_ultcartraant."</td>";
				print "<td>".$li_ultsuetraant."</td>";
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
<title>Cat&aacute;logo de Trabajo Anterior</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Trabajo Anterior </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodtraant" type="text" id="txtcodtraant" size="30" maxlength="3" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Empresa</div></td>
        <td><div align="left">
          <input name="txtemptraant" type="text" id="txtemptraant" size="30" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
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
		$li_codtraant="%".$_POST["txtcodtraant"]."%";
		$ls_emptraant="%".$_POST["txtemptraant"]."%";
		$ls_codper=$_POST["txtcodper"];
		uf_print($ls_codper, $li_codtraant, $ls_emptraant);
	}
	else
	{
		$ls_codper=$_GET["codper"];
		$li_codtraant="%%";
		$ls_emptraant="%%";
		uf_print($ls_codper, $li_codtraant, $ls_emptraant);
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
function aceptar(codtraant,emptraant,ultcartraant,ultsuetraant,fecingtraant,fecrettraant,emppubtraant,codded,
				 desded,anolab,meslab,dialab)
{
	opener.document.form1.txtcodtraant.value=codtraant;
	opener.document.form1.txtcodtraant.readOnly=true;	
	opener.document.form1.txtemptraant.value=emptraant;
	opener.document.form1.txtultcartraant.value=ultcartraant;
	opener.document.form1.txtultsuetraant.value=ultsuetraant;
	opener.document.form1.txtfecingtraant.value=fecingtraant;
	opener.document.form1.txtfecrettraant.value=fecrettraant;
	opener.document.form1.cmbemppubtraant.value=emppubtraant;
	opener.document.form1.txtcodded.value=codded;
	opener.document.form1.txtdesded.value=desded;
	opener.document.form1.txtanolab.value=anolab;
	opener.document.form1.txtmeslab.value=meslab;
	opener.document.form1.txtdialab.value=dialab;	
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
  	f.action="sigesp_snorh_cat_trabajoanterior.php";
  	f.submit();
}
</script>
</html>
