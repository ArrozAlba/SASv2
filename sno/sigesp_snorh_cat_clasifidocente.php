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
   function uf_print($as_codescdoc,$as_codcladoc,$as_descladoc,$as_tipo)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codescdoc  // Código de Escala Docente
		//				   as_codcladoc  // Código de Clasificación Docente
		//				   as_descladoc  // Descripción de Clasificación Docente
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		print "<td width=60>Código</td>";
		print "<td width=220>Descripción</td>";
		print "<td width=220>Tiempo de Servicio</td>";
		print "</tr>";
		$ls_sql="SELECT codemp, codescdoc, codcladoc, descladoc, tiesercladoc, suesupcladoc, suedircladoc, suedoccladoc ".
				"  FROM sno_clasificaciondocente ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codescdoc='".$as_codescdoc."'".
				"   AND codcladoc like '".$as_codcladoc."' AND descladoc like '".$as_descladoc."'".
				" ORDER BY codcladoc ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codcladoc=$row["codcladoc"];
				$ls_descladoc=$row["descladoc"];
				$ls_tiesercladoc=$row["tiesercladoc"];
				$li_suesupcladoc=$row["suesupcladoc"];
				$li_suedircladoc=$row["suedircladoc"];
				$li_suedoccladoc=$row["suedoccladoc"];
				$li_suesupcladoc=str_replace(".",",",$li_suesupcladoc);
				$li_suesupcladoc=$io_fun_nomina->uf_formatonumerico($li_suesupcladoc);
				$li_suedircladoc=str_replace(".",",",$li_suedircladoc);
				$li_suedircladoc=$io_fun_nomina->uf_formatonumerico($li_suedircladoc);
				$li_suedoccladoc=str_replace(".",",",$li_suedoccladoc);
				$li_suedoccladoc=$io_fun_nomina->uf_formatonumerico($li_suedoccladoc);
					
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codcladoc','$ls_descladoc','$ls_tiesercladoc','$li_suesupcladoc',";
						print "'$li_suedircladoc','$li_suedoccladoc');\">".$ls_codcladoc."</a></td>";
						print "<td>".$ls_descladoc."</td>";
						print "<td>".$ls_tiesercladoc."</td>";
						print "</tr>";			
						break;
						
					case "asignacion":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarasignacion('$ls_codcladoc','$ls_descladoc','$li_suedoccladoc');\">".$ls_codcladoc."</a></td>";
						print "<td>".$ls_descladoc."</td>";
						print "<td>".$ls_tiesercladoc."</td>";
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
	$li_rac="";
	if(array_key_exists("la_nomina",$_SESSION))
	{
		$li_rac=$_SESSION["la_nomina"]["racnom"];
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>C&aacute;talogo de Clasificaci&oacute;n Docente</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Clasificaci&oacute;n Docente </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodcladoc" type="text" id="txtcodcladoc" size="30" maxlength="4" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdescladoc" type="text" id="txtdescladoc" size="30" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
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
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codcladoc="%".$_POST["txtcodcladoc"]."%";
		$ls_descladoc="%".$_POST["txtdescladoc"]."%";
		$ls_codescdoc=$_POST["txtcodescdoc"];
		uf_print($ls_codescdoc,$ls_codcladoc,$ls_descladoc, $ls_tipo);
	}
	else
	{
		$ls_codescdoc=$_GET["codescdoc"];
		$ls_codcladoc="%%";
		$ls_descladoc="%%";
		uf_print($ls_codescdoc,$ls_codcladoc,$ls_descladoc, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
          <input name="txtcodescdoc" type="hidden" id="txtcodescdoc" value="<?php print $ls_codescdoc;?>">
          <input name="rac" type="hidden" id="rac" value="<?php print $li_rac;?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codcladoc,descladoc,tiesercladoc,suesupcladoc,suedircladoc,suedoccladoc)
{
	opener.document.form1.txtcodcladoc.value=codcladoc;
	opener.document.form1.txtcodcladoc.readOnly=true;
    opener.document.form1.txtdescladoc.value=descladoc;
	opener.document.form1.txttiesercladoc.value=tiesercladoc;
    opener.document.form1.txtsuesupcladoc.value=suesupcladoc;
    opener.document.form1.txtsuedircladoc.value=suedircladoc;
    opener.document.form1.txtsuedoccladoc.value=suedoccladoc;
	opener.document.form1.existe.value="TRUE";		
	close();
}

function aceptarasignacion(codcladoc,descladoc,suedoccladoc)
{
	f=document.form1;
	opener.document.form1.txtcodcladoc.value=codcladoc;
	opener.document.form1.txtcodcladoc.readOnly=true;
    opener.document.form1.txtdescladoc.value=descladoc;
	if(f.rac.value=="0") //Nóminas que no utiliza rac 
	{
		sueldo=opener.document.form1.txtsueper.value;
		while(sueldo.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			sueldo=sueldo.replace(".","");
		}
		sueldo=parseFloat(sueldo);
		if(sueldo==0)
		{
			opener.document.form1.txtsueper.value=suedoccladoc;
		}
	}
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
  	f.action="sigesp_snorh_cat_clasifidocente.php?tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
