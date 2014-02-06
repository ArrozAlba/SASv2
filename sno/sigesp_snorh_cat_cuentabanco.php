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
   function uf_print($as_codban, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codban  // Código de banco
		//				   as_tipo  // Tipo de llamada del catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 								Fecha Última Modificación : 
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
		print "<td width=200>Cuenta Extendida</td>";
		print "<td width=200>Descripción Cuenta</td>";
		print "<td width=100>Tipo de Cuenta</td>";
		print "</tr>";
		$ls_sql="SELECT scb_ctabanco.ctabanext, scb_ctabanco.dencta, scb_tipocuenta.nomtipcta, scb_ctabanco.sc_cuenta, scb_ctabanco.ctaban ".
				"  FROM scb_ctabanco, scb_tipocuenta  ".
				" WHERE scb_ctabanco.codtipcta=scb_tipocuenta.codtipcta ".
				"	AND scb_ctabanco.codemp='".$ls_codemp."'".
				"   AND scb_ctabanco.codban='".$as_codban."'".
				" ORDER BY scb_ctabanco.ctabanext ";

		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_ctabanext=$row["ctabanext"];
				$ls_nomtipcta=$row["nomtipcta"];
				$ls_sc_cuenta=$row["sc_cuenta"];
				$ls_ctaban=$row["ctaban"];
				$ls_dencta=$row["dencta"];
				switch ($as_tipo)
				{
					case "replisban": // el llamado se hace desde  sigesp_sno_r_listadopersonalcheque.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisban('$ls_ctabanext','$ls_sc_cuenta','$ls_ctaban');\">".$ls_ctabanext."</a></td>";
						print "<td>".$ls_nomtipcta."</td>";
						print "<td>".$ls_dencta."</td>";
						print "</tr>";			
						break;

					case "replisben": // el llamado se hace desde  sigesp_sno_r_listadopersonalcheque.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisben('$ls_ctabanext','$ls_sc_cuenta','$ls_ctaban');\">".$ls_ctabanext."</a></td>";
						print "<td>".$ls_nomtipcta."</td>";
						print "<td>".$ls_dencta."</td>";
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
<title>Cat&aacute;logo de Cuenta Banco</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de  cuentas Banco </td>
    </tr>
  </table>
<br>
    <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	$ls_codban=$io_fun_nomina->uf_obtenervalor_get("codban","");
	uf_print($ls_codban, $ls_tipo);
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptarreplisban(ctabanext,sc_cuenta,ctaban)
{
	opener.document.form1.txtcodcue.value=ctabanext;
	opener.document.form1.txtcodcue.readOnly=true;
	opener.document.form1.txtsc_cuenta.value=sc_cuenta;
	opener.document.form1.txtsc_cuenta.readOnly=true;
	opener.document.form1.txtctaban.value=ctaban;
	opener.document.form1.txtctaban.readOnly=true;
	close();
}

function aceptarreplisben(ctabanext,sc_cuenta,ctaban)
{
	opener.document.form1.txtcodcue.value=ctabanext;
	opener.document.form1.txtcodcue.readOnly=true;
	opener.document.form1.txtsc_cuenta.value=sc_cuenta;
	opener.document.form1.txtsc_cuenta.readOnly=true;
	opener.document.form1.txtctaban.value=ctaban;
	opener.document.form1.txtctaban.readOnly=true;
	close();
}

function ue_search(existe)
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_snorh_cat_cuentabanco.php?codban<?php print $ls_codban;?>&tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
