<?php
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();


$ls_pagina = "../html/sigespwindow_blank.html";
$ls_metodo = "";
$ls_visible = "1";

//////////////////////////////////// SEGURIDAD SIGESP //////////////////////////////////////////////////
require_once("../../../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad=new sigesp_c_seguridad();

if(array_key_exists("la_empresa",$_SESSION))
{
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
}
//////////////////////////////////// SEGURIDAD SIGESP //////////////////////////////////////////////////
 
  function uf_validar_permisos( $ps_opciones,$pa_permisos,&$ps_new_botonera)
  {
	$ps_new_botonera = "";
	$nuevo = strpos($ps_opciones,"n");
	if (($nuevo===false)||($nuevo==="")) $nuevo=-1;
	
	$grabar = strpos($ps_opciones,"g");
	if (($grabar===false)||($grabar==="")) $grabar=-1;
	
	$buscar = strpos($ps_opciones,"b");
	if (($buscar===false)||($buscar==="")) $buscar=-1;
	
	$imprimir = strpos($ps_opciones,"i");
	if (($imprimir===false)||($imprimir==="")) $imprimir=-1;
	
	$eliminar = strpos($ps_opciones,"e");
	if (($eliminar===false)||($eliminar==="")) $eliminar=-1;
	
	if (($pa_permisos["incluir"]==1)&&($pa_permisos["cambiar"]==1)&&(($nuevo>=0)))
	{ $ps_new_botonera.= "ng"; }
	if (($pa_permisos["incluir"]==1)&&($pa_permisos["cambiar"]==1)&&(($grabar>=0)))
	{ $ps_new_botonera.= "g"; }
	if (($pa_permisos["leer"]==1)&&(($buscar>=0)))
	{ $ps_new_botonera.= "b"; }
	if (($pa_permisos["imprimir"]==1)&&(($imprimir>=0)))
	{ $ps_new_botonera.= "i"; }
	if (($pa_permisos["eliminar"]==1)&&(($eliminar>=0)))
	{ $ps_new_botonera.= "e"; }

  }

if ($ls_visible == "1")
{
  //Filtramos los permisos utiles segun la pagina
  //con los botones que pueden ser usados
  $ls_permisos = "";
  $ls_botonera = "";  
  
  if ($_GET["pagina"] == "sps_def_articulos.html.php")  
  {
  	$lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_def_articulos.html.php",$la_permisos);
	if ($lb_valido)   
    {       
		$ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
	    $ls_botones  = "ngbe";    //Nuevo, Grabar, Buscar, Eliminar
	    uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}
  }
  elseif ($_GET["pagina"] == "sps_def_causaretiro.html.php")
  {
  	$lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_def_causaretiro.html.php",$la_permisos);
	if ($lb_valido)   
    {   
	    $ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "ngbe";    //Nuevo, Grabar, Buscar, Eliminar
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif ($_GET["pagina"] == "sps_def_tasainteres.html.php")
  {
  	$lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_def_tasainteres.html.php",$la_permisos);
	if ($lb_valido)   
    {   
	    $ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "ngbe";    //Nuevo, Grabar, Buscar, Eliminar
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif ($_GET["pagina"] == "sps_def_cartaanticipo.html.php")
  {
  	$lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_def_cartaanticipo.html.php",$la_permisos);
	if ($lb_valido)   
    {   
	    $ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "ngbe";    //Nuevo, Grabar, Buscar, Eliminar
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif ($_GET["pagina"] == "sps_def_configuracion.html.php")
  {
  	$lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_def_configuracion.html.php",$la_permisos);
	if ($lb_valido)   
    {   
	    $ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "g";       //Grabar
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif ($_GET["pagina"] == "sps_pro_sueldos.html.php")
  {
  	$lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_pro_sueldos.html.php",$la_permisos);
	if ($lb_valido)   
    {   
	    $ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "ngbe";    //Nuevo, Grabar, Buscar, Eliminar
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif ($_GET["pagina"] == "sps_pro_deudaanterior.html.php")
  {
  	$lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_pro_deudaanterior.html.php",$la_permisos);
	if ($lb_valido)   
    {   
	    $ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "gbe";     //Grabar, Buscar, Eliminar
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif ($_GET["pagina"] == "sps_pro_anticipos.html.php")
  {
  	$lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_pro_anticipos.html.php",$la_permisos);
	if ($lb_valido)   
    {   
	    $ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "gbe";    //Grabar, Buscar, Eliminar
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif ($_GET["pagina"] == "sps_pro_aprobacionanticipos.html.php")
  {
  	$lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_pro_aprobacionanticipos.html.php",$la_permisos);
	if ($lb_valido)   
    {   
	    $ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "gb";    //Nuevo, Grabar, Buscar
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif ($_GET["pagina"] == "sps_pro_antiguedad.html.php")
  {
    
	$lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_pro_antiguedad.html.php",$la_permisos);
	if ($lb_valido)   
    {   
		$ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "gbe";    //Nuevo, Grabar, Buscar
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif ($_GET["pagina"] == "sps_pro_antig_nomina.html.php")
  {
    
	$lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_pro_antig_nomina.html.php",$la_permisos);
	if ($lb_valido)   
    {   
		$ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "gbe";    //Nuevo, Grabar, Buscar
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif ($_GET["pagina"] == "sps_pro_liquidaciones.html.php")
  {
  	$lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_pro_liquidaciones.html.php",$la_permisos);
	if ($lb_valido)   
    {   
	    $ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "ngbe";    //Nuevo, Grabar, Buscar,eliminar
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif ($_GET["pagina"] == "sps_pro_aprobacionliquidacion.html.php")
  {
    $lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_pro_aprobacionliquidacion.html.php",$la_permisos);
	if ($lb_valido)   
    {   
		$ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "gb";    //Grabar, Buscar
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif ($_GET["pagina"] == "sps_rep_detalle_antiguedad.html.php")
  {
    $lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_rep_detalle_antiguedad.html.php",$la_permisos);
	if ($lb_valido)   
    {   
		$ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "i";   //Imprimir
	    uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif  ($_GET["pagina"] == "sps_rep_liquidacion.html.php")
  {
    $lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_rep_liquidacion.html.php",$la_permisos);
	if ($lb_valido)   
    {   
		$ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "i";    //Imprimir
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif  ($_GET["pagina"] == "sps_rep_anticipo.html.php")
  {
    $lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_rep_anticipo.html.php",$la_permisos);
	if ($lb_valido)   
    {   
		$ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "i";    //Imprimir
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif  ($_GET["pagina"] == "sps_rep_detalle_sueldos.html.php")
  {
    $lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_rep_detalle_sueldos.html.php",$la_permisos);
	if ($lb_valido)   
    {   
		$ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "i";    //Imprimir
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif ($_GET["pagina"] == "sps_rep_deuda_ps.html.php")
  {
    $lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_rep_deuda_ps.html.php",$la_permisos);
	if ($lb_valido)   
    {   
		$ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "i";    //Imprimir
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
	}	
  }
  elseif ($_GET["pagina"] == "sps_conv_prestaciones.html.php")
  {
    $lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_conv_prestaciones.html.php",$la_permisos);
	if ($lb_valido)   
    {   
		$ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "";    //Imprimir
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
		$ls_botonera .= "cs";
	}	
  }
  elseif ($_GET["pagina"] == "sps_conv_anticipos.html.php")
  {
    $lb_valido = $io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,"SPS","sps_conv_anticipos.html.php",$la_permisos);
	if ($lb_valido)   
    {   
		$ls_permisos = "bimepra"; //Buscar, Incluir, Modificar, Eliminar, Imprimir, Ejecutar,
		$ls_botones = "";    //Imprimir
		uf_validar_permisos( $ls_botones,$la_permisos,$ls_botonera);
		$ls_botonera .= "cs";
	}	
  }
  
  if ($ls_botonera!="")
  {
	  $ls_botonera .= "cs";
	  //Chequeamos el nombre de la pagina y completamos su direccion url
	  $ls_pagina = "../html/".$_GET["pagina"];
  }
  else
  {
	 $ls_pagina = "../html/sigespwindow_blank.php";
	 
  }
}
?>
<html>
<body>
<form id="form1" name="form1" method="post" action="<?Php print $ls_pagina?>">
  <input type="hidden" id="botonera" name="botonera" value="<?Php print $ls_botonera?>"/>
  <input type="hidden" id="permisos" name="permisos" value="<?Php print $ls_permisos?>"/>
</form>
<script language="javascript">
  document.form1.submit();
</script>
</body>
</html>