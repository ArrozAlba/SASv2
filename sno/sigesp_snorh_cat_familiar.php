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
   function uf_print($as_codper, $as_cedfam, $as_nomfam, $as_apefam)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: ad_fecfer  // Fecha del Feriado
		//				   as_nomfer  // Descripción del Feriado
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
		print "<td width=60>Cédula</td>";
		print "<td width=440>Nombre y Apellido</td>";
		print "</tr>";
		$ls_sql="SELECT codemp, codper, cedfam, nomfam, apefam, sexfam, fecnacfam, nexfam, estfam, hcfam, hcmfam, hijesp, estbonjug, cedula ".
				"  FROM sno_familiar ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND cedula like '".$as_cedfam."' ".
				"   AND nomfam like '".$as_nomfam."' ".
				"   AND apefam like '".$as_apefam."' ".
				" ORDER BY cedfam";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_cedfam=$row["cedfam"];
				$ls_nomfam=$row["nomfam"];
				$ls_apefam=$row["apefam"];
				$ls_sexfam=$row["sexfam"];
				$ld_fecnacfam=$io_funciones->uf_convertirfecmostrar($row["fecnacfam"]);
				$ls_nexfam=$row["nexfam"];				
				$li_estfam=$row["estfam"];				
				$li_hcfam=$row["hcfam"];				
				$li_hcmfam=$row["hcmfam"];
				$li_hijesp=$row["hijesp"];
				$li_bonjug=$row["estbonjug"];				
				$ls_cedula=$row["cedula"];				
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: aceptar('$ls_cedfam','$ls_nomfam','$ls_apefam','$ls_sexfam','$ld_fecnacfam',";
				print "'$ls_nexfam','$li_estfam','$li_hcfam','$li_hcmfam','$li_hijesp','$li_bonjug','$ls_cedula');\">".$ls_cedfam."</a></td>";
				print "<td>".$ls_cedula."</td>";
				print "<td>".$ls_nomfam." ".$ls_apefam."</td>";
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
<title>Cat&aacute;logo de Familiar</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Familiar </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&eacute;dula</div></td>
        <td width="431"><div align="left">
          <input name="txtcedfam" type="text" id="txtcedfam" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="txtnomfam" type="text" id="txtnomfam" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Apellido</div></td>
        <td><div align="left">
          <input name="txtapefam" type="text" id="txtapefam" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
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
		$ls_cedfam="%".$_POST["txtcedfam"]."%";
		$ls_nomfam="%".$_POST["txtnomfam"]."%";
		$ls_apefam="%".$_POST["txtapefam"]."%";
		$ls_codper=$_POST["txtcodper"];
		uf_print($ls_codper,$ls_cedfam,$ls_nomfam, $ls_apefam);
	}
	else
	{
		$ls_codper=$_GET["codper"];
		$ls_cedfam="%%";
		$ls_nomfam="%%";
		$ls_apefam="%%";
		uf_print($ls_codper,$ls_cedfam,$ls_nomfam, $ls_apefam);
	}
	unset($io_fun_nomina);
?>
</div>
          <input name="txtcodper" type="hidden" id="txtcodper" value="<?php print $ls_codper;?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(cedfam,nomfam,apefam,sexfam,fecnacfam,nexfam,estfam,hcfam,hcmfam,hijesp,bonjug,cedula)
{
	opener.document.form1.txtcedfam.value=cedfam;
	opener.document.form1.txtcedfam.readOnly=true;
    opener.document.form1.txtnomfam.value=nomfam;
	opener.document.form1.txtapefam.value=apefam;
    opener.document.form1.cmbsexfam.value=sexfam;
    opener.document.form1.txtfecnacfam.value=fecnacfam;
    opener.document.form1.cmbnexfam.value=nexfam;
    opener.document.form1.txtcedula.value=cedula;
	opener.document.form1.existe.value="TRUE";		
	if(estfam==1)
	{
		opener.document.form1.chkestfam.checked=true;
	}
	else
	{
		opener.document.form1.chkestfam.checked=false;
	}
	if(hcfam==1)
	{
		opener.document.form1.chkhcfam.checked=true;
	}
	else
	{
		opener.document.form1.chkhcfam.checked=false;
	}
	if(hcmfam==1)
	{
		opener.document.form1.chkhcmfam.checked=true;
	}
	else
	{
		opener.document.form1.chkhcmfam.checked=false;
	}
	if(hijesp==1)
	{
		opener.document.form1.chkhijesp.checked=true;
	}
	else
	{
		opener.document.form1.chkhijesp.checked=false;
	}
	if(bonjug==1)
	{
		opener.document.form1.chkbonjug.checked=true;
	}
	else
	{
		opener.document.form1.chkbonjug.checked=false;
	}
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
  	f.action="sigesp_snorh_cat_familiar.php";
  	f.submit();
}
</script>
</html>
