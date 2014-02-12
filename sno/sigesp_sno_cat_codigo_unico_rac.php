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
   function uf_print($as_codunirac)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codunirac  // Código de único de la asigancion de cargo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 03/11/2008 								Fecha Última Modificación : 
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
        $ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_codasicar=$_GET["codasicar"];
				
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=250>Código</td>";
		print "<td width=250>Estado</td>";
		print "</tr>";
		$ls_sql="SELECT codunirac,estcodunirac ".
				"  FROM sno_codigounicorac ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codnom='".$ls_codnom."'".
				"   AND codasicar='".$ls_codasicar."'".
				"   AND estcodunirac='0' ".
				"   AND codunirac like '".$as_codunirac."'".				
				" ORDER BY codunirac ";
			
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codunirac=$row["codunirac"];
				if ($row["estcodunirac"]=='0')
				{
					$ls_estcodunirac="DISPONIBLE";
				}
				
				print "<tr class=celdas-blancas>";
				print "<td align='center'><a href=\"javascript: aceptar('$ls_codunirac');\">".$ls_codunirac."</a></td>";
				print "<td align='center'>".$ls_estcodunirac."</td>";
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
		unset($ls_codnom);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de C&oacute;digo &Uacute;nico de Asignaci&oacute;n de Cargo</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de C&oacute;digo &Uacute;nico de Asignaci&oacute;n de Cargo</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodunirac" type="text" id="txtcodunirac" size="12" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">        
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
		$ls_codunirac="%".$_POST["txtcodunirac"]."%";		
		uf_print($ls_codunirac);
	}
	else
	{
		$ls_codunirac="%%";
		uf_print($ls_codunirac);
	}	
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codunirac)
{
	opener.document.form1.txtcodunirac.value=codunirac;
	opener.document.form1.txtcodunirac.readOnly=true;
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
  	f.action="sigesp_sno_cat_codigo_unico_rac.php";
  	f.submit();
}
</script>
</html>
