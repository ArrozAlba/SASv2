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
   function uf_print($as_codcom, $as_codran, $as_desran, $as_tipo,$as_codcomdes,$as_codcomhas)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codcom  // Código del Componente 
		//				   as_codran  // Código del Rango
		//				   as_desran  // Descripción del Rango
		//				   as_tipo  // Verifica de donde se está llamando el catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
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
		print "<td width=220>Descripción</td>";
		print "<td width=220>Componente</td>";
		print "</tr>";
		$ls_criterio="";
		if (($as_codcomdes!="")&&($as_codcomhas!=""))
		{
			$ls_criterio="   AND sno_rango.codcom BETWEEN '".$as_codcomdes."' AND '".$as_codcomhas."'";
		}
		else
		{
			$ls_criterio="   AND sno_rango.codcom='".$as_codcom."'";
		}
		$ls_sql=" SELECT sno_rango.codcom, sno_rango.codran, sno_rango.desran, sno_rango.codcat, sno_componente.descom, ".
		        "  (SELECT sno_categoria_rango.descat FROM sno_categoria_rango     ".
				"    WHERE sno_categoria_rango.codemp=sno_rango.codemp             ".
				"      AND sno_categoria_rango.codcat=sno_rango.codcat ) as descat ".
				"  FROM sno_rango, sno_componente         ".			    
				" WHERE sno_rango.codemp='".$ls_codemp."' ".
				$ls_criterio.
				"   AND sno_rango.codran like '".$as_codran."'".
				"   AND sno_rango.desran like '".$as_desran."'".
				"	AND sno_rango.codemp=sno_componente.codemp ".
				"   AND sno_rango.codcom=sno_componente.codcom ".
				" ORDER BY codran "; 
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codran=$row["codran"];
				$ls_desran=$row["desran"];
				$ls_codcom=$row["codcom"];
				$ls_descom=$row["descom"];
				$ls_codcat=$row["codcat"];
				$ls_descat=$row["descat"];
				switch($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptar('$ls_codran','$ls_desran','$ls_codcat','$ls_descat');\">".$ls_codran."</a></td>";
						print "<td>".$ls_desran."</td>";
						print "<td>".$ls_descom."</td>";
						print "</tr>";			
						break;
						
					case "personal":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptarpersonal('$ls_codran','$ls_desran');\">".$ls_codran."</a></td>";
						print "<td>".$ls_desran."</td>";
						print "<td>".$ls_descom."</td>";
						print "</tr>";			
						break;
						
					case "rangodes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptardes('$ls_codran');\">".$ls_codran."</a></td>";
						print "<td>".$ls_desran."</td>";
						print "<td>".$ls_descom."</td>";
						print "</tr>";			
						break;
					
					case "rangohas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptarhas('$ls_codran');\">".$ls_codran."</a></td>";
						print "<td>".$ls_desran."</td>";
						print "<td>".$ls_descom."</td>";
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
<title>Cat&aacute;logo de Rango</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Rango </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodran" type="text" id="txtcodran" size="30" maxlength="4" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdesran" type="text" id="txtdesran" size="30" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
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
	$ls_codcom=$io_fun_nomina->uf_obtenervalor_get("codcom","");
	$ls_codcomdes=$io_fun_nomina->uf_obtenervalor_get("codcomdes","");
	$ls_codcomhas=$io_fun_nomina->uf_obtenervalor_get("codcomhas","");
	if($ls_operacion=="BUSCAR")
	{
		$ls_codran="%".$_POST["txtcodran"]."%";
		$ls_desran="%".$_POST["txtdesran"]."%";
		uf_print($ls_codcom, $ls_codran, $ls_desran, $ls_tipo, $ls_codcomdes,$ls_codcomhas);
	}
	else
	{
		$ls_codran="%%";
		$ls_desran="%%";
		uf_print($ls_codcom, $ls_codran, $ls_desran, $ls_tipo, $ls_codcomdes,$ls_codcomhas);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_aceptar(codran,desran,codcat,descat)
{
	opener.document.form1.txtcodran.value=codran;
	opener.document.form1.txtcodran.readOnly=true;
	opener.document.form1.txtdesran.value=desran;
	opener.document.form1.txtcodcat.value=codcat;
	opener.document.form1.txtdescat.value=descat;
	opener.document.form1.existe.value="TRUE";
	close();
}

function ue_aceptardes(codran)
{
	opener.document.form1.txtcodrandes.value=codran;
	opener.document.form1.txtcodrandes.readOnly=true;
	close();
}

function ue_aceptarhas(codran)
{
	codrandes=opener.document.form1.txtcodrandes.value;
	if (codrandes<=codran)
	{
		opener.document.form1.txtcodranhas.value=codran;
		opener.document.form1.txtcodranhas.readOnly=true;
		close();
	}
	else
	{
		alert("el rango seleccionado es invalido");
	}
}

function ue_aceptarpersonal(codran,desran)
{
	opener.document.form1.txtcodran.value=codran;
	opener.document.form1.txtcodran.readOnly=true;
	opener.document.form1.txtdesran.value=desran;
	opener.document.form1.txtdesran.readOnly=true;
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
  	f.action="sigesp_snorh_cat_rango.php?tipo=<?php print $ls_tipo;?>&codcom=<?php print $ls_codcom;?>";
  	f.submit();
}
</script>
</html>