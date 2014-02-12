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
   function uf_print($as_codded, $as_codtipper, $as_destipper, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codtipper  // Código de tipo de personal
		//				   as_destipper  // Descripción del tipo de personal
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
		print "<td width=220>Descripción</td>";
		print "<td width=220>Dedicación</td>";
		print "</tr>";
		$ls_sql="SELECT sno_tipopersonal.codded, sno_tipopersonal.codtipper, sno_tipopersonal.destipper, ".
				"  		sno_dedicacion.desded ".
				"  FROM sno_tipopersonal,sno_dedicacion ".
				" WHERE sno_tipopersonal.codemp='".$ls_codemp."' ".
				"   AND sno_tipopersonal.codded='".$as_codded."'".
				"   AND sno_tipopersonal.codtipper<>'0000'".
				"   AND sno_tipopersonal.codtipper like '".$as_codtipper."'".
				"   AND sno_tipopersonal.destipper like '".$as_destipper."'".
				"	AND sno_tipopersonal.codemp=sno_dedicacion.codemp ".
				"   AND sno_tipopersonal.codded=sno_dedicacion.codded ".
				" ORDER BY codtipper ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codtipper=$row["codtipper"];
				$ls_destipper=$row["destipper"];
				$ls_codded=$row["codded"];
				$ls_desded=$row["desded"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codded','$ls_desded','$ls_codtipper','$ls_destipper');\">".$ls_codtipper."</a></td>";
						print "<td>".$ls_destipper."</td>";
						print "<td>".$ls_desded."</td>";
						print "</tr>";			
						break;
						
					case "asignacion":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarasignacion('$ls_codtipper','$ls_destipper');\">".$ls_codtipper."</a></td>";
						print "<td>".$ls_destipper."</td>";
						print "<td>".$ls_desded."</td>";
						print "</tr>";			
						break;

					case "fideiconfigurable":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarfideiconfigurable('$ls_codtipper','$ls_destipper');\">".$ls_codtipper."</a></td>";
						print "<td>".$ls_destipper."</td>";
						print "<td>".$ls_desded."</td>";
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
<title>Cat&aacute;logo de Tipo de Personal</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Tipo de Personal </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodtipper" type="text" id="txtcodtipper" size="30" maxlength="4" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdestipper" type="text" id="txtdestipper" size="30" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
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
	$ls_codded=$io_fun_nomina->uf_obtenervalor_get("codded","");
	if($ls_operacion=="BUSCAR")
	{
		$ls_codtipper="%".$_POST["txtcodtipper"]."%";
		$ls_destipper="%".$_POST["txtdestipper"]."%";
		uf_print($ls_codded, $ls_codtipper, $ls_destipper, $ls_tipo);
	}
	else
	{
		$ls_codtipper="%%";
		$ls_destipper="%%";
		uf_print($ls_codded, $ls_codtipper, $ls_destipper, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codded,desded,codtipper,destipper)
{
	opener.document.form1.txtcodded.value=codded;
	opener.document.form1.txtcodded.readOnly=true;
    opener.document.form1.txtdesded.value=desded;
	opener.document.form1.txtdesded.readOnly=true;
	opener.document.form1.txtcodtipper.value=codtipper;
	opener.document.form1.txtcodtipper.readOnly=true;
	opener.document.form1.txtdestipper.value=destipper;
	opener.document.form1.existe.value="TRUE";
	close();
}

function aceptarasignacion(codtipper,destipper)
{
	opener.document.form1.txtcodtipper.value=codtipper;
	opener.document.form1.txtcodtipper.readOnly=true;
    opener.document.form1.txtdestipper.value=destipper;
	opener.document.form1.txtdestipper.readOnly=true;
	close();
}

function aceptarfideiconfigurable(codtipper,destipper)
{
	opener.document.form1.txtcodtipper.value=codtipper;
	opener.document.form1.txtcodtipper.readOnly=true;
    opener.document.form1.txtdestipper.value=destipper;
	opener.document.form1.txtdestipper.readOnly=true;
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
  	f.action="sigesp_snorh_cat_tipopersonal.php?tipo=<?php print $ls_tipo;?>&codded=<?php print $ls_codded;?>";
  	f.submit();
}
</script>
</html>
