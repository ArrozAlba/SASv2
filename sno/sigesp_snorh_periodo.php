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
   function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codnom,$ls_dennom,$li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title;

		$ls_codnom=$_GET["txtcodigo"];
		$ls_dennom=$_GET["txtdenominacion"];
		$ls_titletable="Períodos de ".$ls_dennom;
		$li_widthtable=500;
		$ls_nametable="grid";
		$lo_title[1]="Período";
		$lo_title[2]="Desde";
		$lo_title[3]="Hasta";
		$lo_title[4]="Cerrada";
		$lo_title[5]="Nomina Contabilizada";
		$lo_title[6]="Aporte Contabilizado";
		$lo_title[7]="Ingreso Contabilizado";
		$lo_title[8]="Prestación Contabilizado";
		$lo_title[9]="Total";
		$li_totrows="0";
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
<title>Per&iacute;odos de la Nomina</title>
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
	require_once("sigesp_snorh_c_nominas.php");
	$io_nomina=new sigesp_snorh_c_nominas();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	$io_nomina->uf_load_periodo($ls_codnom,$li_totrows,$lo_object);
	unset($io_nomina);
?>
<table width="570" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td valign="top">
		  <form name="form1" method="post" action="">
            <div align="center">
			    <?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			    ?>
            </div>
		  </form>
	  </td>
    </tr>
</table>
</div>
</body>
</script>
</html>