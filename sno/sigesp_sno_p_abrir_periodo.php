<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_abrir_periodo.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $lo_title,$li_widthtable,$ls_titletable,$ls_nametable,$li_totrows,$lo_object,$ls_operacion,$io_fun_nomina;
		
		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();
		$lb_valido=$io_sno->uf_crear_sessionnomina();		
		if($lb_valido==false)
		{
			print "<script language=JavaScript>";
			print "location.href='sigespwindow_blank.php'";
			print "</script>";		
		}
		unset($io_sno);
		$lo_title[1]="Periodos";
		$lo_title[2]="Desde";
		$lo_title[3]="Hasta";
		$lo_title[4]="Cerrada";
		$lo_title[5]="Nomina Contabilizada";
		$lo_title[6]="Aporte Contabilizado";
		$lo_title[7]="Ingreso Contabilizado";
		$lo_title[8]="Prestación Contabilizado";
		$lo_title[9]="Total";    
		$li_widthtable=700;
		$ls_titletable="Período a Abrir";
		$ls_nametable="grid";
		$li_totrows=0;
		$lo_object[1]="";
		$lo_object[2]="";
		$lo_object[3]="";
		$lo_object[4]="";
		$lo_object[5]="";
		$lo_object[6]="";
		$lo_object[7]="";
		$lo_object[8]="";
		$lo_object[9]="";
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
<title>Abrir Per&iacute;odos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #EAEAEA;
	margin-left: 0px;
}
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
</head>
<body>
<?php
	require_once("sigesp_sno_c_cierre_periodo.php");
	$io_cierreperiodo = new sigesp_sno_c_cierre_periodo();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
		 	$lb_valido=$io_cierreperiodo->uf_select_periodo_anterior($li_totrows,$lo_object); 
			break;

		case "ABRIR_PERIODO":
			$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
			$ls_codperi_abrir=$io_cierreperiodo->io_funciones->uf_rellenar_izq((intval($ls_peractnom)-1),0,3);
			$lb_valido=$io_cierreperiodo->uf_procesar_abrir_periodo($ls_codperi_abrir,$ls_peractnom,$la_seguridad);
			print "<script language='javascript'>"; 
			print "	close();";
			print "	opener.document.form1.submit();";
			print "	opener.document.form1.operacion.value='NUEVO';";
			print "</script>";
			break;
	}
	$io_cierreperiodo->uf_destructor();
	unset($io_cierreperiodo);
?>
<p>&nbsp;</p>
<div align="center">
 <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <table width="305" height="72" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="425" height="70" valign="top">
          <p align="center"> 
            <input name="operacion" type="hidden" id="operacion">
            <?php	
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
		 </p>
          <p align="center">
            <input name="botabrir" type="button" class="boton" id="botabrir"   onClick="javascript: uf_abrir()" value="Abrir Periodo">
          </p>
      </td>
    </tr>
    <tr>
      <td width="425" height="70" valign="top">
		<div id=transferir style="visibility:hidden" align="center"><img src="../shared/imagebank/cargando.gif">Abriendo Periodo... </div>
      </td>
    </tr>
  </table>
</form>  
</div>
</body>
<script language="javascript">
function uf_abrir()
{
	f=document.form1;
	f.botabrir.disabled=true;
	li_ejecutar=f.ejecutar.value;
	if (li_ejecutar==1)
   	{
		contabilizada=eval("f.contabilizado1.value");
		aporte=eval("f.aporte1.value");
		ingreso=eval("f.ingreso1.value");
		fideicomiso=eval("f.fideicomiso1.value");
		if((contabilizada=="0")&&(aporte=="0")&&(ingreso=="0")&&(fideicomiso=="0"))
		{
			mostrar('transferir');
			f.operacion.value="ABRIR_PERIODO";
			f.action="sigesp_sno_p_abrir_periodo.php";
			f.submit();
		}
		else
		{
			alert("El período que va a abrir esta contabilizado, debe reversar la contabilización y luego abrirlo.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}
function mostrar(nombreCapa)
{
	capa= document.getElementById(nombreCapa) ;
	capa.style.visibility="visible"; 
} 
</script>
</html>