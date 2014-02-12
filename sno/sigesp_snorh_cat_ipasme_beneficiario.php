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
   function uf_print($as_codper, $as_codben, $as_cedben, $as_tipo)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codper  // Código de Personal
		//	    		   as_codben  // Código del Beneficiario
		//				   as_cedben  // Cédula del Beneficiario
		//				   as_tipo  // Verifica de donde se está llamando el catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		print "<td width=60>Cédula</td>";
		print "<td width=240>Apellidos y Nombre</td>";
		print "<td width=140>Parentesco</td>";
		print "</tr>";
		$ls_sql="SELECT codemp, codper, codben, cedben, tiptraben, codpare, nacben, prinomben, segnomben, priapeben, segapeben, ".
				"		sexben, fecnacben, estcivben, fecfalben, codban, numcueben, tipcueben ".
				"  FROM sno_ipasme_beneficiario ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND codper = '".$as_codper."' ".
				"   AND codben like '".$as_codben."' AND cedben like '".$as_cedben."'".
				" ORDER BY codben, cedben ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li_codben=$row["codben"];
				$ls_cedben=$row["cedben"];
				$ls_tiptraben=$row["tiptraben"];
				$ls_codpare=$row["codpare"];
				$ls_nacben=$row["nacben"];
				$ls_prinomben=$row["prinomben"];
				$ls_segnomben=$row["segnomben"];
				$ls_priapeben=$row["priapeben"];
				$ls_segapeben=$row["segapeben"];
				$ls_sexben=$row["sexben"];
				$ld_fecnacben=$io_funciones->uf_convertirfecmostrar($row["fecnacben"]);
				$ls_estcivben=$row["estcivben"];
				$ld_fecfalben=$io_funciones->uf_convertirfecmostrar($row["fecfalben"]);
				$ls_codban=$row["codban"];
				$ls_numcueben=$row["numcueben"];
				$ls_tipcueben=$row["tipcueben"];
				switch($ls_codpare)
				{
					case "01":
						$ls_parentesco="Padres";
						break;

					case "02":
						$ls_parentesco="Abuelos";
						break;
						
					case "03":
						$ls_parentesco="Hijos";
						break;
						
					case "04":
						$ls_parentesco="Hermanos";
						break;
						
					case "05":
						$ls_parentesco="Conyuge";
						break;
						
					case "06":
						$ls_parentesco="Concubino";
						break;
				}
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$li_codben','$ls_cedben','$ls_tiptraben','$ls_codpare','$ls_nacben',";
						print "'$ls_prinomben','$ls_segnomben','$ls_priapeben','$ls_segapeben','$ls_sexben','$ld_fecnacben',";
						print "'$ls_estcivben','$ld_fecfalben','$ls_codban','$ls_numcueben','$ls_tipcueben');\">".$li_codben."</a></td>";
						print "<td>".$ls_cedben."</td>";
						print "<td>".$ls_priapeben.", ".$ls_prinomben."</td>";
						print "<td>".$ls_parentesco."</td>";
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
<title>Cat&aacute;logo de Beneficiario</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de  Beneficiario </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="121" height="22"><div align="right">C&oacute;digo </div></td>
        <td width="373"><div align="left">
          <input name="txtcodben" type="text" id="txtcodben" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">C&eacute;dula </div></td>
        <td><div align="left">
          <input name="txtcedben" type="text" id="txtcedben" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">
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
		$ls_codper=$_GET["codper"];
		$ls_codben="%".$_POST["txtcodben"]."%";
		$ls_cedben="%".$_POST["txtcedben"]."%";
		uf_print($ls_codper, $ls_codben, $ls_cedben, $ls_tipo);
	}
	else
	{
		$ls_codper=$_GET["codper"];
		$ls_codben="%%";
		$ls_cedben="%%";
		uf_print($ls_codper,$ls_codben, $ls_cedben, $ls_tipo);
	}	
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codben,cedben,tiptraben,codpare,nacben,prinomben,segnomben,priapeben,segapeben,sexben,fecnacben,estcivben,fecfalben,
				 codban,numcueben,tipcueben)
{
	opener.document.form1.txtcodben.value=codben;
	opener.document.form1.txtcodben.readOnly=true;
    opener.document.form1.txtcedben.value=cedben;
	opener.document.form1.txtcedben.readOnly=true;
	opener.document.form1.cmbtiptraben.value=tiptraben;
    opener.document.form1.cmbcodpare.value=codpare;
    opener.document.form1.cmbnacben.value=nacben;
    opener.document.form1.txtprinomben.value=prinomben;
    opener.document.form1.txtsegnomben.value=segnomben;
    opener.document.form1.txtpriapeben.value=priapeben;
    opener.document.form1.txtsegapeben.value=segapeben;	
    opener.document.form1.cmbsexben.value=sexben;
    opener.document.form1.txtfecnacben.value=fecnacben;
    opener.document.form1.cmbestcivben.value=estcivben;
    opener.document.form1.txtfecfalben.value=fecfalben;
    opener.document.form1.cmbcodban.value=codban;
    opener.document.form1.txtnumcueben.value=numcueben;
    opener.document.form1.cmbtipcueben.value=tipcueben;
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
  	f.action="sigesp_snorh_cat_ipasme_beneficiario.php?tipo=<?php print $ls_tipo;?>&codper=<?php print $ls_codper;?>";
  	f.submit();
}
</script>
</html>
