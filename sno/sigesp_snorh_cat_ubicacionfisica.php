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
   function uf_print($as_codubifis, $as_desubifis, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codubifis  // Código de la Ubicación Física
		//				   as_desubifis  // Descripción de la Ubicación Física
		//				   as_tipo  // Verifica de donde se está llamando el catálogo
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
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=440>Descripción</td>";
		print "</tr>";
		$ls_sql="SELECT codubifis, desubifis, codpai, codest, codmun, codpar, dirubifis, ".
				"		(SELECT despai FROM sigesp_pais ".
				"		  WHERE sigesp_pais.codpai = sno_ubicacionfisica.codpai ) AS despai, ".
				"		(SELECT desest FROM sigesp_estados ".
				"		  WHERE sigesp_estados.codpai = sno_ubicacionfisica.codpai ".
				"			AND sigesp_estados.codest = sno_ubicacionfisica.codest ) AS desest, ".
				"		(SELECT denmun FROM sigesp_municipio ".
				"		  WHERE sigesp_municipio.codpai = sno_ubicacionfisica.codpai ".
				"			AND sigesp_municipio.codest = sno_ubicacionfisica.codest ".
				"			AND sigesp_municipio.codmun = sno_ubicacionfisica.codmun ) AS desmun, ".
				"		(SELECT denpar FROM sigesp_parroquia ".
				"		  WHERE sigesp_parroquia.codpai = sno_ubicacionfisica.codpai ".
				"			AND sigesp_parroquia.codest = sno_ubicacionfisica.codest ".
				"			AND sigesp_parroquia.codmun = sno_ubicacionfisica.codmun ".
				"			AND sigesp_parroquia.codpar = sno_ubicacionfisica.codpar ) AS despar ".
				"  FROM sno_ubicacionfisica ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codubifis<>'0000'".
				"   AND codubifis like '".$as_codubifis."' ".
				"   AND desubifis like '".$as_desubifis."'".
				" ORDER BY codubifis ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codubifis=$row["codubifis"];
				$ls_desubifis=$row["desubifis"];
				$ls_codpai=$row["codpai"];
				$ls_despai=$row["despai"];
				$ls_codest=$row["codest"];
				$ls_desest=$row["desest"];
				$ls_codmun=$row["codmun"];
				$ls_desmun=$row["desmun"];
				$ls_codpar=$row["codpar"];
				$ls_despar=$row["despar"];
				$ls_dirubifis=$row["dirubifis"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codubifis','$ls_desubifis','$ls_codpai','$ls_despai',";
						print "'$ls_codest','$ls_desest','$ls_codmun','$ls_desmun','$ls_codpar','$ls_despar','$ls_dirubifis');\">".$ls_codubifis."</a></td>";
						print "<td>".$ls_desubifis."</td>";
						print "</tr>";			
						break;
						
					case "asignacion":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarasignacion('$ls_codubifis','$ls_desubifis');\">".$ls_codubifis."</a></td>";
						print "<td>".$ls_desubifis."</td>";
						print "</tr>";			
						break;
						
					case "listadopersonal":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarasignacion('$ls_codubifis','$ls_desubifis');\">".$ls_codubifis."</a></td>";
						print "<td>".$ls_desubifis."</td>";
						print "</tr>";			
						break;
						
					case "pagonomina":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarpagonomina('$ls_codubifis','$ls_desubifis');\">".$ls_codubifis."</a></td>";
						print "<td>".$ls_desubifis."</td>";
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
<title>Cat&aacute;logo de Ubicaci&oacute;n F&iacute;sica</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Ubicaci&oacute;n F&iacute;sica </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodubifis" type="text" id="txtcodubifis" size="30" maxlength="4" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdesubifis" type="text" id="txtdesubifis" size="30" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
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
		$ls_codubifis="%".$_POST["txtcodubifis"]."%";
		$ls_desubifis="%".$_POST["txtdesubifis"]."%";
		uf_print($ls_codubifis, $ls_desubifis, $ls_tipo);
	}
	else
	{
		$ls_codubifis="%%";
		$ls_desubifis="%%";
		uf_print($ls_codubifis, $ls_desubifis, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codubifis,desubifis,codpai,despai,codest,desest,codmun,desmun,codpar,despar,dirubifis)
{
	opener.document.form1.txtcodubifis.value=codubifis;
	opener.document.form1.txtcodubifis.readOnly=true;
    opener.document.form1.txtdesubifis.value=desubifis;
    opener.document.form1.txtcodpai.value=codpai;
    opener.document.form1.txtdespai.value=despai;
    opener.document.form1.txtcodest.value=codest;
    opener.document.form1.txtdesest.value=desest;
    opener.document.form1.txtcodmun.value=codmun;
    opener.document.form1.txtdesmun.value=desmun;
    opener.document.form1.txtcodpar.value=codpar;
    opener.document.form1.txtdespar.value=despar;
    opener.document.form1.txtdirubifis.value=dirubifis;
	opener.document.form1.existe.value="TRUE";
	close();
}

function aceptarasignacion(codubifis,desubifis)
{
	opener.document.form1.txtcodubifis.value=codubifis;
	opener.document.form1.txtcodubifis.readOnly=true;
    opener.document.form1.txtdesubifis.value=desubifis;
	close();
}

function aceptarpagonomina(codubifis,desubifis)
{
	opener.document.form1.txtcodubifis.value=codubifis;
	opener.document.form1.txtcodubifis.readOnly=true;
    opener.document.form1.txtdesubifis.value=desubifis;
	opener.document.form1.txtdesubifis.readOnly=true;
    opener.document.form1.txtcodest.value="";
	opener.document.form1.txtcodest.readOnly=true;
    opener.document.form1.txtdesest.value="";
	opener.document.form1.txtdesest.readOnly=true;
    opener.document.form1.txtcodmun.value="";
	opener.document.form1.txtcodmun.readOnly=true;
    opener.document.form1.txtdesmun.value="";
	opener.document.form1.txtdesmun.readOnly=true;
    opener.document.form1.txtcodpar.value="";
	opener.document.form1.txtcodpar.readOnly=true;
    opener.document.form1.txtdespar.value="";
	opener.document.form1.txtdespar.readOnly=true;
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
  	f.action="sigesp_snorh_cat_ubicacionfisica.php?tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
