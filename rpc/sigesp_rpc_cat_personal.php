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
	function uf_print($as_cedper, $as_nomper, $as_apeper)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_cedper  // Cédula de Pesonal
		//				   as_nomper  // Nombre de Personal
		//				   as_apeper // Apellido de Personal
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
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Cédula</td>";
		print "<td width=440>Nombre y Apellido</td>";
		print "</tr>";
		$ls_sql="SELECT cedper, nomper, apeper ".
				"  FROM sno_personal ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND estper='1' ".
				"   AND cedper like '".$as_cedper."'".
				"   AND nomper like '".$as_nomper."' ".
				"   AND apeper like '".$as_apeper."'".
				"   AND cedper NOT IN (SELECT ced_bene FROM rpc_beneficiario  WHERE codemp = '".$ls_codemp."')".
				" ORDER BY cedper ASC ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_cedper=$row["cedper"];
				$ls_nomper=$row["apeper"].", ".$row["nomper"];
				print "<tr class=celdas-blancas>";
				print "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_cedper');\">".$ls_cedper."</a></td>";
				print "<td style=text-align:left   width=400>".$ls_nomper."</td>";
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
		unset($io_sno);
		unset($ls_codemp);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Personal</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Personal </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="22"><div align="right">C&eacute;dula</div></td>
        <td><div align="left">
          <input name="txtcedper" type="text" id="txtcedper" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="txtnomper" type="text" id="txtnomper" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Apellido</div></td>
        <td><div align="left">
          <input name="txtapeper" type="text" id="txtapeper" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();">
          <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	if(array_key_exists("operacion",$_POST))
	{
		$ls_cedper="%".$_POST["txtcedper"]."%";
		$ls_nomper="%".$_POST["txtnomper"]."%";
		$ls_apeper="%".$_POST["txtapeper"]."%";

		uf_print($ls_cedper, $ls_nomper, $ls_apeper);
	}
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(ls_cedula)
{
    fop      = opener.document.form1;
	li_rango = fop.hidrango.value; 
	if (li_rango=='1')
	   {
         fop.txtcedula1.value = ls_cedula;
	   }
	else
	   {
	     fop.txtcedula2.value = ls_cedula;
	   }
	close();
}


function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_rpc_cat_personal.php";
  	f.submit();
}
</script>
</html>