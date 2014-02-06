<?php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_p_seleccionarnomina.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_cargarnomina()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargarnomina
		//		   Access: private
		//	  Description: Función que obtiene todas las nóminas y las carga en un 
		//				   combo para seleccionarlas
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

		$ls_sql="SELECT sno_nomina.codnom, sno_nomina.desnom ".
				"  FROM sno_nomina, sss_permisos_internos ".
				" WHERE sno_nomina.codemp='".$ls_codemp."'".
				"   AND sno_nomina.peractnom<>'000'".
				"   AND sss_permisos_internos.codsis='SNO'".
				"   AND sss_permisos_internos.codusu='".$_SESSION["la_logusr"]."'".
				"   AND sno_nomina.codemp = sss_permisos_internos.codemp ".
				"   AND sno_nomina.codnom = sss_permisos_internos.codintper ".
				" GROUP BY sno_nomina.codnom, sno_nomina.desnom ".
				" ORDER BY sno_nomina.codnom, sno_nomina.desnom ";
		$rs_data=$io_sql->select($ls_sql);
       	print "<select name='cmbnomina' id='cmbnomina' style='width:400px'>";
        print " <option value='' selected>--Seleccione Una--</option>";
		if($rs_data===false)
		{
        	$io_mensajes->message("Clase->Seleccionar Nómina Método->uf_cargarnomina Error->".$io_funciones->uf_convertirmsg($io_sql->message)); 
			print "<script language=JavaScript>";
			print "	close();";
			print "</script>";		
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codnom=$row["codnom"];
				$ls_desnom=$row["desnom"];
            	print "<option value='".$ls_codnom."'>".$ls_codnom."-".$ls_desnom."</option>";				
			}
			$io_sql->free_result($rs_data);
		}
       	print "</select>";
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title>Seleccionar N&oacute;mina</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <table width="482" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="487" height="20" colspan="2" class="titulo-ventana">Seleccionar N&oacute;mina </td>
    </tr>
  </table>
<br>
    <table width="479" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="91" height="22"><div align="right">N&oacute;mina</div></td>
        <td width="379"><div align="left"><?php uf_cargarnomina(); ?></div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td></td>
	</tr>
      <tr>
        <td colspan="2"><div align="center"><a href="javascript: ue_aceptar();"><img src="imagenes/Aceptar2.png" width="25" height="22" border="0" title="Aceptar" ></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: ue_cancelar();"><img src="imagenes/Cancelar2.png" width="25" height="22" border="0" title="Cancelar"></a></div></td>
      </tr>
  </table>
</form>
</body>
<script language="JavaScript">
function ue_aceptar()
{
	f=document.form1;
	valor=f.cmbnomina.selectedIndex;
	nomina=ue_validarvacio(f.cmbnomina.options[valor].value);
	if (nomina!="")
	{
		opener.document.form1.action="sigespwindow_blank_nomina.php?nom="+nomina;
		opener.document.form1.submit();
		close();
	}
	else
	{
		alert("Debe Seleccionar una Nómina");
	}
}

function ue_cancelar()
{
	close();
}
</script>
</html>
