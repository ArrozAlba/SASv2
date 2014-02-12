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
   function uf_print($ad_fecfer, $as_nomfer)
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
		print "<td width=60>Fecha</td>";
		print "<td width=440>Nombre</td>";
		print "</tr>";
		$ls_sql="SELECT fecfer, nomfer ".
				"  FROM sno_diaferiado ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND nomfer like '".$as_nomfer."'";
		if($ad_fecfer!="")
		{
			$ad_fecfer = $io_funciones->uf_convertirdatetobd($ad_fecfer);
			$ls_sql=$ls_sql."   AND fecfer='".$ad_fecfer."'";
		}
		$ls_sql=$ls_sql." ORDER BY fecfer ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ld_fecfer=$io_funciones->uf_formatovalidofecha($row["fecfer"]);
				$ld_fecfer=$io_funciones->uf_convertirfecmostrar($ld_fecfer);				
				$ls_nomfer=$row["nomfer"];
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: aceptar('$ld_fecfer','$ls_nomfer');\">".$ld_fecfer."</a></td>";
				print "<td>".$ls_nomfer."</td>";
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
<title>Cat&aacute;logo de D&iacute;a Feriado</title>
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
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de D&iacute;a Feriado</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">Fecha</div></td>
        <td width="431"><div align="left">
          <input name="txtfecfer" type="text" id="txtfecfer" size="30" maxlength="10"  onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" onKeyUp="javascript: ue_mostrar(this,event);" datepicker="true">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="txtnomfer" type="text" id="txtnomfer" size="30" maxlength="120" onKeyPress="javascript: ue_mostrar(this,event);">
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
		$ld_fecfer=$_POST["txtfecfer"];
		$ls_nomfer="%".$_POST["txtnomfer"]."%";
		uf_print($ld_fecfer, $ls_nomfer);
	}
	else
	{
		$ld_fecfer="";
		$ls_nomfer="%%";
		uf_print($ld_fecfer, $ls_nomfer);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function aceptar(fecfer,nomfer)
{
	opener.document.form1.txtfecfer.value=fecfer;
	opener.document.form1.txtfecfer.readOnly=true;
	opener.document.form1.txtnomfer.value=nomfer;
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

function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_snorh_cat_diaferiado.php";
  	f.submit();
}
</script>
</html>
