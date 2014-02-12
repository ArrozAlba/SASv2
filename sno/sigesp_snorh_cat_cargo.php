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
   function uf_print($as_codcar, $as_descar, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codcar  // Código del cargo
		//				   as_descar  // Descripción del Cargo
		//				   as_tipo  // Tipo de Llamada del catálogo
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
		print "<td width=100>Código</td>";
		print "<td width=200>Descripción</td>";
		print "<td width=200>Nómina</td>";
		print "</tr>";
		$ls_sql="SELECT sno_cargo.codcar, sno_cargo.descar, sno_cargo.codnom, sno_nomina.desnom ".
				"  FROM sno_cargo, sno_nomina ".
				" WHERE sno_cargo.codemp='".$ls_codemp."'".
				"   AND sno_cargo.codcar<>'0000000000'".
				"   AND sno_cargo.codcar like '".$as_codcar."' AND sno_cargo.descar like '".$as_descar."'".
				"   AND sno_cargo.codnom IN (SELECT sno_nomina.codnom ".
				"  					           FROM sno_nomina, sss_permisos_internos ".
				" 				              WHERE sno_nomina.codemp='".$ls_codemp."'".
				"   				            AND sss_permisos_internos.codsis='SNO'".
				"  					            AND sss_permisos_internos.codusu='".$_SESSION["la_logusr"]."'".
				"  					            AND sno_nomina.codemp = sss_permisos_internos.codemp ".
				"   				            AND sno_nomina.codnom = sss_permisos_internos.codintper ".
				" 					          GROUP BY sno_nomina.codnom) ".
				"   AND sno_cargo.codemp = sno_nomina.codemp ".
				"   AND sno_cargo.codnom = sno_nomina.codnom ".
				" ORDER BY codcar ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codcar=$row["codcar"];
				$ls_descar=$row["descar"];
				$ls_codnom=$row["codnom"];
				$ls_desnom=$row["desnom"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codcar','$ls_descar','$ls_codnom');\">".$ls_codcar."</a></td>";
						print "<td>".$ls_descar."</td>";
						print "<td>".$ls_desnom."</td>";
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
<title>Cat&aacute;logo de Cargo</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Cargo </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodcar" type="text" id="txtcodcar" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdescar" type="text" id="txtdescar" size="30" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
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
		$ls_codcar="%".$_POST["txtcodcar"]."%";
		$ls_descar="%".$_POST["txtdescar"]."%";
		uf_print($ls_codcar, $ls_descar, $ls_tipo);
	}
	else
	{
		$ls_codcar="%%";
		$ls_descar="%%";
		uf_print($ls_codcar, $ls_descar, $ls_tipo);
	}
	unset($io_fun_nomina);	
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codcar,descar,codnom)
{
	opener.document.form1.txtcodcar.value=codcar;
	opener.document.form1.txtcodcar.readOnly=true;
    opener.document.form1.txtdescar.value=descar;
    opener.document.form1.cmbnomina.value=codnom;
    opener.document.form1.cmbnomina.disabled=true;
    opener.document.form1.txtcodnom.value=codnom;
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

function ue_search(existe)
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_snorh_cat_cargo.php?tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
