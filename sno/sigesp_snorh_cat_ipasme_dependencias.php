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
   function uf_print($as_coddep, $as_desdep, $as_tipo)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_coddep  // Código de la Dependencia
		//				   as_desdep  // Descripción de la Dependencia
		//				   as_tipo  // Verifica de donde se está llamando el catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/07/2006 								Fecha Última Modificación : 
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
		print "<td width=440>Descripción</td>";
		print "</tr>";
		$ls_sql="SELECT coddep, desdep, entdep, mundep, locdep ".
				"  FROM sno_ipasme_dependencias ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND coddep like '".$as_coddep."' AND desdep like '".$as_desdep."'".
				" ORDER BY coddep ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_coddep=$row["coddep"];
				$ls_desdep=$row["desdep"];
				$ls_entdep=$row["entdep"];
				$ls_mundep=$row["mundep"];
				$ls_locdep=$row["locdep"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_coddep','$ls_desdep','$ls_entdep','$ls_mundep','$ls_locdep');\">".$ls_coddep."</a></td>";
						print "<td>".$ls_desdep."</td>";
						print "</tr>";			
						break;
					
					case "afiliado": // llamado desde sigesp_snorh_d_ipasme_afiliado.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarafiliado('$ls_coddep','$ls_desdep');\">".$ls_coddep."</a></td>";
						print "<td>".$ls_desdep."</td>";
						print "</tr>";			
						break;
					
					case "replisdepdes": // llamado desde sigesp_snorh_r_ipasme_dependencias.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisdepdes('$ls_coddep');\">".$ls_coddep."</a></td>";
						print "<td>".$ls_desdep."</td>";
						print "</tr>";			
						break;
					
					case "replisdephas": // llamado desde sigesp_snorh_r_ipasme_dependencias.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisdephas('$ls_coddep');\">".$ls_coddep."</a></td>";
						print "<td>".$ls_desdep."</td>";
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
<title>Cat&aacute;logo de Dependencias</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Dependencias</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcoddep" type="text" id="txtcoddep" size="30" maxlength="11" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdesdep" type="text" id="txtdesdep" size="30" maxlength="120" onKeyPress="javascript: ue_mostrar(this,event);">
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
		$ls_coddep="%".$_POST["txtcoddep"]."%";
		$ls_desdep="%".$_POST["txtdesdep"]."%";
		uf_print($ls_coddep, $ls_desdep, $ls_tipo);
	}
	else
	{
		$ls_coddep="%%";
		$ls_desdep="%%";
		uf_print($ls_coddep, $ls_desdep, $ls_tipo);
	}	
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(coddep,desdep,entdep,mundep,locdep)
{
	opener.document.form1.txtcoddep.value=coddep;
	opener.document.form1.txtcoddep.readOnly=true;
    opener.document.form1.txtdesdep.value=desdep;
    opener.document.form1.cmbcodent.value=entdep;
	opener.document.form1.cmbcodent.onchange();
    opener.document.form1.cmbcodmun.value=mundep;
	opener.document.form1.cmbcodmun.onchange();
    opener.document.form1.cmbcodloc.value=locdep;
	opener.document.form1.existe.value="TRUE";
	close();
}

function aceptarafiliado(coddep,desdep)
{
	opener.document.form1.txtcoddep.value=coddep;
	opener.document.form1.txtcoddep.readOnly=true;
    opener.document.form1.txtdesdep.value=desdep;
	opener.document.form1.txtdesdep.readOnly=true;
	close();
}


function aceptarreplisdepdes(coddep)
{
	opener.document.form1.txtcoddepdes.value=coddep;
	opener.document.form1.txtcoddepdes.readOnly=true;
	opener.document.form1.txtcoddephas.value="";
	close();
}

function aceptarreplisdephas(coddep)
{
	if(opener.document.form1.txtcoddepdes.value<=coddep)
	{
		opener.document.form1.txtcoddephas.value=coddep;
		opener.document.form1.txtcoddephas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango de Dependencia Inválido");
	}
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
  	f.action="sigesp_snorh_cat_ipasme_dependencias.php?tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
