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
   function uf_print($as_codpridoc,$as_despridoc,$ai_valpridoc,$as_tippridoc, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codescdoc  // Código de escala docente
		//				   as_desescdoc  // Descripción de la escala docente
		//				   as_tipo  // Verifica de donde se está llamando el catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Carlos Zambrano
		// Fecha Creación: 16/03/2009 								Fecha Última Modificación : 
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
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=440>Descripción</td>";
		print "</tr>";
		$ls_sql="SELECT codpridoc, despridoc, valpridoc, tippridoc ".
				"  FROM sno_primasdocentes ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codpridoc<>'0000'".
				"   AND codpridoc like '".$as_codpridoc."' AND despridoc like '".$as_despridoc."' AND tippridoc like '".$as_tippridoc."'".
				" ORDER BY codpridoc ";
				
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codpridoc=$row["codpridoc"];
				$ls_despridoc=$row["despridoc"];
				$ls_valpridoc=number_format($row["valpridoc"],2,',','.');
				$li_tippridocaux=$li_tippridoc=$row["tippridoc"];
				switch ($li_tippridoc)
				{
					case "0":
						$ls_tippridoc='Jerarquia';
					break;
					case "1":
						$ls_tippridoc='Antiguedad';
					break;
					case "2":
						$ls_tippridoc='Hogar e Hijos';
					break;
				}
				switch($as_tipo)
				{
					case "": // Se hace el llamado desde sigesp_snorh_d_proyecto.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codpridoc','$ls_despridoc','$ls_valpridoc','$li_tippridocaux');\">".$ls_codpridoc."</a></td>";
						print "<td>".$ls_despridoc."</td>";
						print "</tr>";		
					break;
					case "personalprima": // Se hace el llamado desde sigesp_sno_d_personaproyecto.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: ue_aceptarpersonalprima('$ls_codpridoc','$ls_despridoc','$ls_valpridoc','$ls_tippridoc','$li_tippridoc');\">".$ls_codpridoc."</a></td>";
						print "<td align=center>".$ls_despridoc."</td>";
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
<title>Cat&aacute;logo de Primas por Docente</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Primas por Docente </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodescdoc" type="text" id="txtcodescdoc" size="30" maxlength="4" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdesescdoc" type="text" id="txtdesescdoc" size="30" maxlength="120" onKeyPress="javascript: ue_mostrar(this,event);">
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
		$ls_codpridoc="%".$_POST["txtcodpridoc"]."%";
		$ls_despridoc="%".$_POST["txtdespridoc"]."%";
		$ls_valpridoc="%".$_POST["txtvalpridoc"]."%";
		$ls_tippridoc="%".$_POST["cmbtippridoc"]."%";		
		uf_print($ls_codpridoc, $ls_despridoc, $ls_valpridoc, $ls_tippridoc, $ls_tipo);
	}
	else
	{
		$ls_codpridoc="%%";
		$ls_despridoc="%%";
		$ls_valpridoc="%%";
		$ls_tippridoc="%%";		
		uf_print($ls_codpridoc, $ls_despridoc, $ls_valpridoc, $ls_tippridoc, $ls_tipo);
	}

	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codpridoc,despridoc,valpridoc,tippridoc)
{
	
	opener.document.form1.txtcodpridoc.value=codpridoc;
	opener.document.form1.txtcodpridoc.readOnly=true;
    opener.document.form1.txtdespridoc.value=despridoc;
	opener.document.form1.txtvalpridoc.value=valpridoc;
	opener.document.form1.cmbtippridoc.value=tippridoc;
	opener.document.form1.hidtippri.value=tippridoc;
	opener.document.form1.cmbtippridoc.disabled=true;
	opener.document.form1.existe.value="TRUE";
	close();
}

function ue_aceptarpersonalprima(codpridoc,despridoc,valpridoc,tippridoc,ai_tippridoc)
{
	li_totrow=opener.document.form1.totalfilas.value;
	valido=true;
	for(li_i=1;(li_i<li_totrow)&&(valido);li_i++)
	{
		codigo= eval("opener.document.form1.txtcodpridoc"+li_i+".value;");
		if(codigo==codpridoc)
		{
			alert("El Proyecto ya lo tiene asignado el personal");
			valido=false;
		}
	}
	if (valido)
	{
		lb_existe=false;
		for (li_i=1;li_i<=li_totrow;li_i++)
		 { 
			ls_codtippri=eval("opener.document.form1.hidtippridoc"+li_i+".value");
			if (ls_codtippri==ai_tippridoc)
			{
				lb_existe=true;
				alert("El tipo de prima ya está asignada al personal !!!");
				break;
			}
		 }
	}
	if(valido && !lb_existe)
	{
		eval("opener.document.form1.txtcodpridoc"+li_totrow+".value='"+codpridoc+"';");
		eval("opener.document.form1.txtdespridoc"+li_totrow+".value='"+despridoc+"';");
		eval("opener.document.form1.txttippridoc"+li_totrow+".value='"+tippridoc+"';");
		eval("opener.document.form1.hidtippridoc"+li_totrow+".value='"+ai_tippridoc+"';");
		opener.document.form1.operacion.value="CARGARPROYECTO";
		opener.document.form1.action="sigesp_sno_d_primadocpersonal.php";
		opener.document.form1.submit();
		close();
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
  	f.action="sigesp_snorh_cat_primasdocentes.php?valor=<?php print $ls_codpridoc;?>";
  	f.submit();
}
</script>
</html>
