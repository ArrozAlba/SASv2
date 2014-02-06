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
   function uf_print($as_codcont, $as_descont, $as_tipo)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codcont  // Código de Constancia
		//				   as_descont  // Descripción de la Constancia
		//				   as_tipo  // Verifica de donde se está llamando el catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_concont,$io_fun_nomina;
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
		$ls_sql="SELECT codcont, descont, concont, tamletcont, intlincont, marinfcont, marsupcont, titcont, piepagcont, ".
				"		tamletpiecont, arcrtfcont  ".
				"  FROM sno_constanciatrabajo ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codcont like '".$as_codcont."' AND descont like '".$as_descont."'".
				" ORDER BY codcont ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codcont=$row["codcont"];
				$ls_descont=$row["descont"];
				$ls_concont=$row["concont"];
				$li_tamletcont=$row["tamletcont"];
				$li_tamletpiecont=$row["tamletpiecont"];
				$li_intlincont=$row["intlincont"];
				$li_marinfcont=$row["marinfcont"];
				$li_marinfcont=$io_fun_nomina->uf_formatonumerico($li_marinfcont);
				$li_marsupcont=$row["marsupcont"];
				$li_marsupcont=$io_fun_nomina->uf_formatonumerico($li_marsupcont);
				$ls_titcont=$row["titcont"];
				$ls_arcrtfcont=$row["arcrtfcont"];
				if(!(file_exists("documentos/original/".$ls_arcrtfcont)))
				{
        			$io_mensajes->message("ERROR-> El archivo RTF asociado al reporte  fue eliminado.");
					$ls_arcrtfcont=""; 
				}
				$ls_piepagcont=$row["piepagcont"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codcont','$ls_descont','$li_tamletcont','$li_intlincont',";
						print "'$li_marinfcont','$li_marsupcont','$ls_titcont','$li_tamletpiecont','$ls_arcrtfcont');\">".$ls_codcont."</a></td>";
						print "<td><input name='txtconcont".$ls_codcont."' type='hidden' id='txtconcont".$ls_codcont."' value='$ls_concont'>".
							  "  <input name='txtpiepagcont".$ls_codcont."' type='hidden' id='txtpiepagcont".$ls_codcont."' value='$ls_piepagcont'>".$ls_descont."</td>";
						print "</tr>";			
						break;

					case "repconttrab":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepconttrab('$ls_codcont','$ls_descont','$ls_arcrtfcont');\">".$ls_codcont."</a></td>";
						print "<td>".$ls_descont."</td>";
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
<title>Cat&aacute;logo de Formatos de Reporte</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Formatos de Reporte </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodcont" type="text" id="txtcodcont" size="30" maxlength="3" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdescont" type="text" id="txtdescont" size="30" maxlength="120" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("class_folder/class_funciones_sob.php");
	$io_fun_sob=new class_funciones_sob();
	$ls_operacion =$io_fun_sob->uf_obteneroperacion();
	$ls_tipo=$io_fun_sob->uf_obtenertipo();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codcont="%".$_POST["txtcodcont"]."%";
		$ls_descont="%".$_POST["txtdescont"]."%";
		uf_print($ls_codcont, $ls_descont, $ls_tipo);
	}
	else
	{
		$ls_codcont="%%";
		$ls_descont="%%";
		uf_print($ls_codcont, $ls_descont, $ls_tipo);
	}	
	unset($io_fun_sob);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codigo,descripcion,letra,interlineado,marinfcont,marsupcont,titcont,tamletpiecont,arcrtfcont)
{
	f=document.form1;
	opener.document.form1.txtcodcont.value=codigo;
	opener.document.form1.txtcodcont.readOnly=true;
    opener.document.form1.txtdescont.value=descripcion;
    opener.document.form1.txtconcont.value=eval("f.txtconcont"+codigo+".value");
    opener.document.form1.txtpiepagcont.value=eval("f.txtpiepagcont"+codigo+".value");
    opener.document.form1.txtmarinfcont.value=marinfcont;
    opener.document.form1.txtmarsupcont.value=marsupcont;
    opener.document.form1.txttitcont.value=titcont;
    opener.document.form1.txttamletcont.value=letra;
    opener.document.form1.txttamletpiecont.value=tamletpiecont;
    opener.document.form1.cmdintlincont.value=interlineado;
	opener.document.form1.txtnomrtf.value=arcrtfcont;
	opener.document.form1.existe.value="TRUE";
	close();
}

function aceptarrepconttrab(codigo,descripcion,arcrtfcont)
{
	opener.document.form1.txtcodcont.value=codigo;
    opener.document.form1.txtdescont.value=descripcion;
	opener.document.form1.txtnomrtf.value=arcrtfcont;
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

function ue_search(existe)
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_sob_cat_formatoreporte.php?tipo=<?PHP print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>