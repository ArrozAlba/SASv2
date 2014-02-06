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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_p_seleccionarhnomina.php",$ls_permisos,$la_seguridad,$la_permisos);
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

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/04/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codnom,$li_anocurnom,$ls_peractnom,$ld_fecdesper,$ld_fechasper,$ls_operacion,$io_fun_nomina;
		
		$ls_codnom="";
		$li_anocurnom="";
		$ls_peractnom="";
		$ld_fecdesper="";
		$ld_fechasper="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
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
<title>Seleccionar N&oacute;mina Hist&oacute;rica</title>
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
<?php 
	require_once("sigesp_snorh_c_seleccionarhnomina.php");
	$io_hnomina=new sigesp_snorh_c_seleccionarhnomina();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "CARGARDATOS":
			$ls_codnom=$_POST["cmbnomina"];
			$li_anocurnom=$_POST["txtanocurnom"];
			$ls_peractnom=$_POST["txtperactnom"];
			$ld_fecdesper=$_POST["txtfecdesper"];
			$ld_fechasper=$_POST["txtfechasper"];
			$lb_valido=$io_hnomina->uf_procesarhistorico($ls_codnom,$li_anocurnom,$ls_peractnom);
			if($lb_valido)
			{  
				print "<script language=JavaScript>";
				print "		opener.document.form1.action='sigespwindow_blank_hnomina.php?codnom=".$ls_codnom."&anocurnom=".$li_anocurnom."&peractnom=".$ls_peractnom."';";
				print "		opener.document.form1.submit();";
				print "		close();";
				print "</script>";		
			}
			break;
	}
	$io_hnomina->uf_destructor();
	unset($io_hnomina);
?>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="482" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="487" height="20" colspan="2" class="titulo-ventana">Seleccionar N&oacute;mina Hist&oacute;rica </td>
    </tr>
  </table>
<br>
    <table width="479" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="91" height="22"><div align="right">N&oacute;mina</div></td>
        <td width="379"><div align="left"><?php uf_cargarnomina(); ?></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">A&ntilde;o y Per&iacute;odo</div></td>
        <td><div align="left">
          <input name="txtanocurnom" type="text" id="txtanocurnom" value="<?php print $li_anocurnom;?>" size="7" maxlength="4" readonly>
          <input name="txtperactnom" type="text" id="txtperactnom" value="<?php print $ls_peractnom;?>" size="6" maxlength="3" readonly>
          <a href="javascript: ue_buscarperiodo();"><img id="periodo" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
          <input name="txtfecdesper" type="text" id="txtfecdesper" value="<?php print $ld_fecdesper;?>" size="13" maxlength="10" readonly>
-
<input name="txtfechasper" type="text" id="txtfechasper" value="<?php print $ld_fechasper;?>" size="13" maxlength="10" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"></div></td>
        <td><div align="left">
          <input name="operacion" type="hidden" id="operacion">
        </div></td>
      </tr>
      <tr>
        <td colspan="2"><div align="center"><a href="javascript: ue_aceptar();"><img src="imagenes/Aceptar2.png" width="25" height="22" border="0" title="Aceptar"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: ue_cancelar();"><img src="imagenes/Cancelar2.png" width="25" height="22" border="0" title="Cancelar"></a></div></td>
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
	anocurnom=ue_validarvacio(f.txtanocurnom.value);
	peractnom=ue_validarvacio(f.txtperactnom.value);
	if((nomina!="")&&(anocurnom!="")&&(peractnom!=""))
	{
		f=document.form1;
		f.operacion.value="CARGARDATOS";
		f.action="sigesp_snorh_p_seleccionarhnomina.php";
		f.submit();
	}
	else
	{
		alert("Debe Seleccionar una Nómina, Año y Período.");
	}
}

function ue_buscarperiodo()
{
	f=document.form1;
	if((f.cmbnomina.value!=""))
	{
		window.open("sigesp_sno_cat_hperiodo.php?codnom="+f.cmbnomina.value+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar una nómina.");
	}
}

function ue_cancelar()
{
	close();
}
</script>
</html>
