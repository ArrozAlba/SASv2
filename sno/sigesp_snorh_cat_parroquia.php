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
   function uf_print($as_codpai, $as_codest, $as_codmun, $as_codpar, $as_despar)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codpai  // Código de País
		//				   as_codest  // Código de Estado
		//				   as_codmun  // Código de Municipio
		//				   as_codpar  // Código de Parroquia
		//				   as_despar  // Descripciónd de Parroquia
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
		$ls_sql="SELECT codpar,denpar FROM sigesp_parroquia ".
				" WHERE codpar <> '---' ".
				"   AND codpai = '".$as_codpai."' ".
				"   AND codest = '".$as_codest."' ".
				"   AND codmun = '".$as_codmun."' ".
				"   AND codpar like '".$as_codpar."' AND denpar like '".$as_despar."'".
				" ORDER BY codpar ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codpar=$row["codpar"];
				$ls_despar=$row["denpar"];
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: aceptar('$ls_codpar','$ls_despar');\">".$ls_codpar."</a></td>";
				print "<td>".$ls_despar."</td>";
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
<title>Cat&aacute;logo de Parroquia</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Parroquia </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodpar" type="text" id="txtcodpar" size="30" maxlength="3" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdespar" type="text" id="txtdespar" size="30" maxlength="50" onKeyPress="javascript: ue_mostrar(this,event);">
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
		$ls_codpar="%".$_POST["txtcodpar"]."%";
		$ls_despar="%".$_POST["txtdespar"]."%";
		$ls_codpai=$_POST["txtcodpai"];
		$ls_codest=$_POST["txtcodest"];
		$ls_codmun=$_POST["txtcodmun"];
		uf_print($ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_despar);
	}
	else
	{
		$ls_codpai=$_GET["codpai"];
		$ls_codest=$_GET["codest"];
		$ls_codmun=$_GET["codmun"];
		$ls_codpar="%%";
		$ls_despar="%%";
		uf_print($ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_despar);
	}
	unset($io_fun_nomina);
?>
</div>
          <input name="txtcodpai" type="hidden" id="txtcodpai" value="<?php print $ls_codpai;?>">
          <input name="txtcodest" type="hidden" id="txtcodest" value="<?php print $ls_codest;?>">
          <input name="txtcodmun" type="hidden" id="txtcodmun" value="<?php print $ls_codmun;?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codigo,descripcion)
{
	opener.document.form1.txtcodpar.value=codigo;
	opener.document.form1.txtcodpar.readOnly=true;
    opener.document.form1.txtdespar.value=descripcion;
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
  	f.action="sigesp_snorh_cat_parroquia.php";
  	f.submit();
}
</script>
</html>
