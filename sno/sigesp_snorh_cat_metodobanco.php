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
   function uf_print($as_codmet, $as_desmet, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codmet  // Código del método
		//				   as_desmet  // Descripción del mátodo
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
		print "<td width=50>Código</td>";
		print "<td width=180>Método</td>";
		print "<td width=150>Tipo</td>";
		print "</tr>";
		$ls_sql="SELECT codemp, codmet, desmet, tipmet, codempnom, tipcuecrenom, tipcuedebnom, numplalph, numconlph, suclph, ".
				"		cuelph, grulph, subgrulph, conlph, numactlph, numofifps, numfonfps, confps, nroplafps, codofinom, ".
				"		debcuelph, codagelph, apaposlph, numconnom, pagtaqnom, nroref ".
				"  FROM sno_metodobanco ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codmet like '".$as_codmet."'".
				"   AND desmet like '".$as_desmet."'";
		if(($as_tipo=="replisban")||($as_tipo=="repconlisban"))
		{
			$ls_sql=$ls_sql." AND tipmet='0' ";
		}		
		$ls_sql=$ls_sql." ORDER BY  tipmet, desmet ASC";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codmet=$row["codmet"];
				$ls_desmet=$row["desmet"];
				$ls_tipmet=$row["tipmet"];
				$ls_codempnom=$row["codempnom"];
				$ls_tipcuecrenom=$row["tipcuecrenom"];
				$ls_tipcuedebnom=$row["tipcuedebnom"];
				$ls_numplalph=$row["numplalph"];
				$ls_numconlph=$row["numconlph"];
				$ls_suclph=$row["suclph"];
				$ls_cuelph=$row["cuelph"];
				$ls_grulph=$row["grulph"];
				$ls_subgrulph=$row["subgrulph"];
				$ls_conlph=$row["conlph"];
				$ls_numactlph=$row["numactlph"];
				$ls_numofifps=$row["numofifps"];
				$ls_numfonfps=$row["numfonfps"];
				$ls_confps=$row["confps"];
				$ls_nroplafps=$row["nroplafps"];
				$ls_numconnom=$row["numconnom"];
				$ls_pagtaqnom=$row["pagtaqnom"];
				$ls_ref=$row["nroref"];
				switch ($ls_tipmet)
				{
					case "0";
						$ls_metodo="Nómina";
						break;
	
					case "1";
						$ls_metodo="Ley de Política";
						break;
	
					case "2";
						$ls_metodo="Prestaciones Sociales";
						break;
				}
				switch ($ls_pagtaqnom)
				{
					case "0";
						$ls_metodo=$ls_metodo." (Depósito a Banco)";
						break;
	
					case "1";
						$ls_metodo=$ls_metodo." (Pago Taquilla)";
						break;
				}
				switch ($as_tipo)
				{
					case "":				
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codmet','$ls_desmet','$ls_tipmet','$ls_metodo');\">".$ls_codmet."</a></td>";
						print "<td>".$ls_desmet."</td>";
						print "<td>".$ls_metodo."</td>";
						print "</tr>";			
						break;
					
					case "replisban":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisban('$ls_codmet','$ls_desmet','$ls_pagtaqnom','$ls_ref');\">".$ls_codmet."</a></td>";
						print "<td>".$ls_desmet."</td>";
						print "<td>".$ls_metodo."</td>";
						print "</tr>";		
						break;	
					
					case "repconlisban":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepconlisban('$ls_codmet','$ls_desmet','$ls_pagtaqnom');\">".$ls_codmet."</a></td>";
						print "<td>".$ls_desmet."</td>";
						print "<td>".$ls_metodo."</td>";
						print "</tr>";		
						break;	
					
					case "replisben":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisben('$ls_codmet','$ls_desmet','$ls_pagtaqnom');\">".$ls_codmet."</a></td>";
						print "<td>".$ls_desmet."</td>";
						print "<td>".$ls_metodo."</td>";
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
<title>Cat&aacute;logo de M&eacute;todo Banco</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de M&eacute;todo Banco </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="111" height="22"><div align="right"> M&eacute;todo </div></td>
        <td width="380"><div align="left">
          <input name="txtcodmet" type="text" id="txtcodmet" size="30" maxlength="4" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdesmet" type="text" id="txtdesmet" size="30" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
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
		$ls_codmet="%".$_POST["txtcodmet"]."%";
		$ls_desmet="%".$_POST["txtdesmet"]."%";
		uf_print($ls_codmet, $ls_desmet, $ls_tipo);
	}
	else
	{
		$ls_codmet="%%";
		$ls_desmet="%%";
		uf_print($ls_codmet, $ls_desmet, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codmet,desmet,tipmet,metodo)
{
	opener.document.form1.txtcodmet.value=codmet;
	opener.document.form1.txtcodmet.readOnly=true;
    opener.document.form1.txtdesmet.value=desmet;
	opener.document.form1.txtdesmet.readOnly=true;
    opener.document.form1.txttipmet.value=tipmet;	
	opener.document.form1.txttipmet.readOnly=true;
    opener.document.form1.txtnomtipmet.value=metodo;	
	opener.document.form1.txtnomtipmet.readOnly=true;
	opener.document.form1.existe.value="TRUE";		
  	opener.document.form1.operacion.value="BUSCAR";
  	opener.document.form1.action="sigesp_snorh_d_metodobanco.php";
  	opener.document.form1.submit();
	close();
}

function aceptarreplisban(codmet,desmet,pagtaqnom,ref)
{
	opener.document.form1.txtcodmet.value=codmet;
	opener.document.form1.txtcodmet.readOnly=true;
    opener.document.form1.txtdesmet.value=desmet;
	opener.document.form1.txtdesmet.readOnly=true;
    opener.document.form1.chkpagtaqnom.value=pagtaqnom;
	opener.document.form1.txtref.value=ref;
	close();
}

function aceptarrepconlisban(codmet,desmet,pagtaqnom)
{
	opener.document.form1.txtcodmet.value=codmet;
	opener.document.form1.txtcodmet.readOnly=true;
    opener.document.form1.txtdesmet.value=desmet;
	opener.document.form1.txtdesmet.readOnly=true;
    opener.document.form1.chkpagtaqnom.value=pagtaqnom;
	close();
}

function aceptarreplisben(codmet,desmet,pagtaqnom)
{
	opener.document.form1.txtcodmet.value=codmet;
	opener.document.form1.txtcodmet.readOnly=true;
    opener.document.form1.txtdesmet.value=desmet;
	opener.document.form1.txtdesmet.readOnly=true;
    opener.document.form1.chkpagtaqnom.value=pagtaqnom;
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
  	f.action="sigesp_snorh_cat_metodobanco.php?tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
