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
   function uf_print($as_codban, $as_codage, $as_nomage)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codban  // Código de banco
		//				   as_codage  // Código de Agencia
		//				   as_nomage  // nombre del Agencia
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
		print "<td width=80>Código</td>";
		print "<td width=210>Descripción</td>";
		print "<td width=210>Banco</td>";
		print "</tr>";
		$ls_sql="SELECT scb_agencias.codage, scb_agencias.nomage, scb_banco.nomban ".
				"  FROM scb_agencias, scb_banco ".
				" WHERE scb_agencias.codemp='".$ls_codemp."' ".
				"   AND scb_agencias.codban='".$as_codban."' ".
				"   AND scb_agencias.codage like '".$as_codage."'".
				"   AND scb_agencias.nomage like '".$as_nomage."'".
				"   AND scb_agencias.codemp=scb_banco.codemp ".
				"   AND scb_agencias.codban=scb_banco.codban ".
				" ORDER BY scb_agencias.codage ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codage=$row["codage"];
				$ls_nomage=$row["nomage"];
				$ls_nomban=$row["nomban"];
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: aceptar('$ls_codage','$ls_nomage');\">".$ls_codage."</a></td>";
				print "<td>".$ls_nomage."</td>";
				print "<td>".$ls_nomban."</td>";
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
<title>Cat&aacute;logo de Agencia</title>
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
      <td width="496" colspan="2" class="titulo-ventana">Cat&aacute;logo de Agencia </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="111" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="380"><div align="left">
          <input name="txtcodage" type="text" id="txtcodage" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"> Nombre </div></td>
        <td><div align="left">
          <input name="txtnomage" type="text" id="txtnomage" size="30" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
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
	if($ls_operacion=="BUSCAR")
	{
		$ls_codban=$_POST["txtcodban"];
		$ls_codage="%".$_POST["txtcodage"]."%";
		$ls_nomage="%".$_POST["txtnomage"]."%";
		uf_print($ls_codban, $ls_codage, $ls_nomage);
	}
	else
	{
		$ls_codban=$_GET["txtcodban"];
		$ls_codage="%%";
		$ls_nomage="%%";
		uf_print($ls_codban, $ls_codage, $ls_nomage);
	}
	unset($io_fun_nomina);
?>
</div>
    <input name="txtcodban" type="hidden" id="txtcodban" value="<?php print $ls_codban; ?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codage,nomage)
{
	opener.document.form1.txtcodage.value=codage;
	opener.document.form1.txtcodage.readOnly=true;
	opener.document.form1.txtnomage.value=nomage;
	opener.document.form1.txtnomage.readOnly=true;
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
  	f.action="sigesp_snorh_cat_agencia.php";
  	f.submit();
}
</script>
</html>
