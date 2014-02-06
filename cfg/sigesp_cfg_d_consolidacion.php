<?php
session_start();
$dat = $_SESSION["la_empresa"];
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_folder/class_funciones_cfg.php");
$io_fun_cfg=new class_funciones_cfg();
$io_fun_cfg->uf_load_seguridad("CFG","sigesp_cfg_d_consolidacion.php",$ls_permisos,$la_seguridad,$la_permisos,"../");

$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
$li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
$li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Informaci&oacute;n de Consolidaci&oacute;n</title>
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
.Estilo5 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.Estilo6 {
	color: #006699;
	font-size: 12px;
}
.Estilo8 {font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; }
.Estilo10 {font-size: 10px}
.Estilo11 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo13 {font-size: 12px}
.Estilo14 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
-->
</style>
<link href="css/cfg.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="spg/js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones_configuracion.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<style type="text/css">
<!--
a:hover {
	color: #006699;
}
-->
</style></head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-negrita"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right" class="letras-negrita"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="spg/js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guadar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div>      
    <div align="center"></div>      <div align="center"></div>      <div align="center"></div></td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_mensajes.php");	
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");	
	require_once("class_folder/sigesp_cfg_c_consolidacion.php");
	
	$io_consolidacion = new sigesp_cfg_c_consolidacion();
	$io_funcion       = new class_funciones();
	$io_msg           = new class_mensajes();
	$io_conect        = new sigesp_include();
    $con              = $io_conect->uf_conectar();
	$io_sql           = new class_sql($con); //Instanciando  la clase sql

	$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
    $li_estmodest = $_SESSION["la_empresa"]["estmodest"];

	if  (array_key_exists("status",$_POST))
		{
		  $ls_estatus=$_POST["status"];
		}
	else
		{
		  $ls_estatus="NUEVO";	  
		}	
	
	//Instancia de la clase de manejo de Grid dinamico
	require_once("../shared/class_folder/grid_param.php");
	$io_grid = new grid_param();
	
    uf_limpiarvariables();	
	if (array_key_exists("operacion",$_POST))
	   {
		 $ls_operacion  = $_POST["operacion"];
		 $ls_codestpro1 = $_POST["txtcodestpro1"];
		 $ls_codestpro2 = $_POST["txtcodestpro2"];
		 $ls_codestpro3 = $_POST["txtcodestpro3"];
		 $ls_denestpro1 = $_POST["txtdenestpro1"];
		 $ls_denestpro2 = $_POST["txtdenestpro2"];
		 $ls_denestpro3 = $_POST["txtdenestpro3"];
		 if ($_SESSION["la_empresa"]["estmodest"]==2)
		    {
			  $ls_codestpro4 = $_POST["txtcodestpro4"];
			  $ls_codestpro5 = $_POST["txtcodestpro5"];
			  $ls_denestpro4 = $_POST["txtdenestpro4"];
			  $ls_denestpro5 = $_POST["txtdenestpro5"];
			}
		 else
		    {
			  $ls_codestpro4 = "";
			  $ls_codestpro5 = "";
			  $ls_denestpro4 = "";
			  $ls_denestpro5 = "";
			}
		 $ls_estcla = $_POST["hidestcla"];
		 $ls_bd     = $_POST["txtbd"];
	   }
	else
	   {
	     $ls_operacion = "CARGAR_DT";
		 $ls_codestpro1 = $ls_codestpro2 = $ls_codestpro3 = $ls_codestpro4 = $ls_codestpro5 = "";
		 $ls_denestpro1 = $ls_denestpro2 = $ls_denestpro3 = $ls_denestpro4 = $ls_denestpro5 = "";
		 $ls_estcla    = "";
		 $ls_bd		   = "";
		 uf_agregarlineablanca($lo_object,$li_totrows);
	   }
		
   function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 27/11/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

   		global $ls_bd,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$li_totrows,
		       $title,$grid1,$io_fun_cfg,$ls_denestpro1,$ls_denestpro2,$ls_denestpro3,$ls_denestpro4,$ls_denestpro5;
	 	$ls_bd		   = "";
		$ls_codestpro1 = $ls_codestpro2 = $ls_codestpro3 = $ls_codestpro4 = $ls_codestpro5 = "";
		$ls_denestpro1 = $ls_denestpro2 = $ls_denestpro3 = $ls_denestpro4 = $ls_denestpro5 = "";
		$ls_estcla     = "";
		
		$title[1] = "Base de Datos";   
		$title[2] = "Estructura Presupuestaria";
		$title[3] = "Tipo";
		$title[4] = "Eliminar";
		$grid1    = "grid";
		$li_totrows = $io_fun_cfg->uf_obtenervalor("totalfilas",1);
   }	
  
   function uf_agregarlineablanca(&$object,$ai_totrows)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $aa_object  // arreglo de titulos 
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que agrega una linea en blanco al final del grid
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 23/03/2006 								Fecha Última Modificación : 23/03/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Object que contiene los objetos y valores	iniciales del grid.	
 		$object[$ai_totrows][1] = "<input type=text  name=txtdesbd".$ai_totrows."     value='' id=txtdesbd".$ai_totrows."     class=sin-borde style=text-align:center size=40 readonly>";
		$object[$ai_totrows][2] = "<input type=text  name=txtcodestpre".$ai_totrows." value='' id=txtcodestpre".$ai_totrows." class=sin-borde style=text-align:center size=70 readonly maxlength=254>";
		$object[$ai_totrows][3] = "<input type=text  name=txtestcla".$ai_totrows."    value='' id=txtestcla".$ai_totrows."    class=sin-borde style=text-align:center size=10 readonly>";
		$object[$ai_totrows][4] = "<a href=javascript:uf_delete_dt('".$ai_totrows."');><img src=../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
  }

   function uf_cargar_dt($i)
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 27/11/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_txtdesbd,$ls_codestpro,$ls_estclapre;

		$ls_txtdesbd  = $_POST["txtdesbd".$i]; 
		$ls_codestpro = $_POST["txtcodestpre".$i];
		$ls_estclapre = $_POST["txtestcla".$i];
   }

   if ($ls_operacion=="BLANQUEAR")
	  {
	    $li_totrows   = $io_fun_cfg->uf_obtenervalor("totalfilas",1);
		$ls_codestpro = $_POST["txtcodestpre1"];
		$ls_bd 		  = $_POST["txtbd"];
		$ls_estcla    = $_POST["txtestcla"];
		$li_fila      = $_POST["filaaux"]; 
		for ($i=1;$i<=$li_totrows;$i++)
		    {
			  //Object que contiene los objetos y valores	iniciales del grid.	
		      $object[$i][1] = "<input type=text  name=txtdesbd".$i."      id=txtdesbd".$i."     value=''  class=sin-borde style=text-align:center size=40 readonly onKeyPress=return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz');>";		
			  $object[$i][2] = "<input type=text  name=txtcodestpre".$i."  id=txtcodestpre".$i." value=''  class=sin-borde style=text-align:left   size=40 readonly maxlength=254>";
		      $object[$i][3] = "<input type=text  name=txtestcla".$i."     id=txtestcla".$i."    value=''  class=sin-borde style=text-align:center size=40 readonly>";
		      $object[$i][4] = "<a href=javascript:uf_delete_dt('".$i."');><img src=../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
		    }  
		for ($i=$li_totrows;$i>$li_fila;$i--)
			{ 
			  $object[$i][1]="<input type=text  name=txtdesbd".$i."      value=''  id=txtdesbd".$i."     class=sin-borde style=text-align:center size=40 readonly>";		
			  $object[$i][2]="<input type=text  name=txtcodestpre".$i."  value=''  id=txtcodestpre".$i." class=sin-borde style=text-align:left   size=40 readonly maxlength=254 >";
			  $object[$i][3]="<input type=text  name=txtestcla".$i."     value=''  id=txtestcla".$i."    class=sin-borde style=text-align:center size=40 readonly>";
			  $object[$i][4] ="<a href=javascript:uf_delete_dt('".$i."');><img src=../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";	
			  $li_totrows=$li_totrows-1;
			}		
	  }

   if ($ls_operacion=="AGREGAR_DETALLE")
	  {
		$li_totrows = $io_fun_cfg->uf_obtenervalor("totalfilas",1);
		for ($li_i=1;$li_i<$li_totrows;$li_i++)
		    {  
			  uf_cargar_dt($li_i);
			  //Object que contiene los objetos y valores	iniciales del grid.	
			  $object[$li_i][1] = "<input type=text  name=txtdesbd".$li_i."      id=txtdesbd".$li_i."      value='".$ls_txtdesbd."'   class=sin-borde style=text-align:center size=40 readonly>";		
			  $object[$li_i][2] = "<input type=text  name=txtcodestpre".$li_i."  id=txtcodestpre".$li_i."  value='".$ls_codestpro."'  class=sin-borde style=text-align:center size=70 maxlength=254 readonly>";
			  $object[$li_i][3] = "<input type=text  name=txtestcla".$li_i."     id=txtestcla".$li_i."     value='".$ls_estclapre."'  class=sin-borde style=text-align:center size=10 readonly>";
		      $object[$li_i][4] = "<a href=javascript:uf_delete_dt('".$li_i."');><img src=../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";	
		    }
		uf_agregarlineablanca($object,$li_totrows);
		uf_limpiarvariables();	
	  }

   if ($ls_operacion=="GUARDAR")
	  {
	    $lb_valido = $io_consolidacion->uf_delete_datos_consolidacion($ls_codemp,$la_seguridad);
		if ($lb_valido)
		   {
			$li_totrows = $io_fun_cfg->uf_obtenervalor("totalfilas",1);
			for ($i=1;$i<$li_totrows;$i++)
				{
				  uf_cargar_dt($i);
				  //Object que contiene los objetos y valores
				  $object[$i][1] = "<input type=text  name=txtdesbd".$i."     id=txtdesbd".$i."     value='".$ls_txtdesbd ."'  class=sin-borde style=text-align:center  size=40  readonly>";		
				  $object[$i][2] = "<input type=text  name=txtcodestpre".$i." id=txtcodestpre".$i." value='".$ls_codestpro."'  class=sin-borde style=text-align:center  size=70  readonly maxlength=129>";
				  $object[$i][3] = "<input type=text  name=txtestcla".$i."    id=txtestcla".$i."    value='".$ls_estclapre."'  class=sin-borde style=text-align:center  size=10  readonly>";
				  $object[$i][4] = "<a href=javascript:uf_delete_dt('".$i."');><img src=../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
				  $lb_valido     = $io_consolidacion->uf_procesar_consolidacion($ls_codemp,$ls_txtdesbd,trim($ls_codestpro),$ls_estclapre,$la_seguridad);
				}
			if ($lb_valido)
			   {
				 $ls_operacion = "CARGAR_DT";
				 $io_msg->message("Registro con Éxito !!!");
			   }
			else
			   {
				 $io_msg->message("Error en Registro !!!");
				 uf_limpiarvariables(); 
				 uf_agregarlineablanca($object,1);
			   }
		   }
	    else
		   {
			 $io_msg->message("Error en Registro !!!");
			 uf_limpiarvariables(); 
			 uf_agregarlineablanca($object,1);
		   }
	  }

   if ($ls_operacion=="CARGAR_DT")
      {
	    $rs_datos   = $io_consolidacion->uf_load_datos_consolidacion(); 
		$li_totrows = $io_sql->num_rows($rs_datos);
		if ($li_totrows>0)
		   {
		      $li_row = 0;
			  while(!$rs_datos->EOF)
				   {
				    $li_row++;
					$ls_nombasdat  = $rs_datos->fields["nombasdat"];
					$ls_codestpre1 = trim(substr($rs_datos->fields["codestpro1"],-$ls_loncodestpro1));
					$ls_codestpre2 = trim(substr($rs_datos->fields["codestpro2"],-$li_loncodestpro2));
					$ls_codestpre3 = trim(substr($rs_datos->fields["codestpro3"],-$li_loncodestpro3));
					$ls_codestpre4 = trim(substr($rs_datos->fields["codestpro4"],-$li_loncodestpro4));
					$ls_codestpre5 = trim(substr($rs_datos->fields["codestpro5"],-$li_loncodestpro5));
					$ls_codestpre  = $ls_codestpre1." - ".$ls_codestpre2." - ".$ls_codestpre3;
					if ($_SESSION["la_empresa"]["estmodest"]==2)
					   {
					     $ls_codestpre  = $ls_codestpre." - ".$ls_codestpre4." - ".$ls_codestpre5;
					   }					
					$ls_estclapre  = $rs_datos->fields["estcla"];
					if ($ls_estclapre=='P')
					   {
					     $ls_denestcla = "PROYECTO";
					   }
					elseif($ls_estclapre=='A')
					   {
					     $ls_denestcla = "ACCIÓN";
					   }
					$object[$li_row][1] = "<input type=text  name=txtdesbd".$li_row."     value='".$ls_nombasdat."' id=txtdesbd".$li_row."     class=sin-borde style=text-align:center size=40 readonly>";
					$object[$li_row][2] = "<input type=text  name=txtcodestpre".$li_row." value='".$ls_codestpre."' id=txtcodestpre".$li_row." class=sin-borde style=text-align:center size=70 readonly maxlength=254>";
					$object[$li_row][3] = "<input type=text  name=txtestcla".$li_row."    value='".$ls_denestcla."' id=txtestcla".$li_row."    class=sin-borde style=text-align:center size=10 readonly>";
					$object[$li_row][4] = "<a href=javascript:uf_delete_dt('".$li_row."');><img src=../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
					$rs_datos->MoveNext();
				  }
	          $li_totrows++;
			  uf_agregarlineablanca($object,$li_totrows);
			}   
         else
		    {
			  uf_limpiarvariables(); 
			  uf_agregarlineablanca($object,1);
			} 
	  }
	
   if ($ls_operacion=="DELETE")
	  {
		$li_totrows  = $io_fun_cfg->uf_obtenervalor("totalfilas",1);
		$li_fila_del = $_POST["filadelete"];
		$li_totrows  = $li_totrows-1;
		$li_row      = 0;
	    for ($i=1;$i<=$li_totrows;$i++)
		    {  
			  $ls_desbd     = $_POST["txtdesbd".$i];
			  $ls_codestpre = $_POST["txtcodestpre".$i];
			  $ls_estclapre = $_POST["txtestcla".$i];
			  if ($i!=$li_fila_del)
			     {
	               $li_row++;
				   $object[$li_row][1] = "<input type=text name=txtdesbd".$li_row."      id=txtdesbd".$li_row."      value='".$ls_desbd."'     class=sin-borde style=text-align:center size=40  readonly>";		
				   $object[$li_row][2] = "<input type=text name=txtcodestpre".$li_row."  id=txtcodestpre".$li_row."  value='".$ls_codestpre."' class=sin-borde style=text-align:center size=70  readonly maxlength=129>";
                   $object[$li_row][3] = "<input type=text name=txtestcla".$li_row."     id=txtestcla".$li_row."     value='".$ls_estclapre."' class=sin-borde style=text-align:center size=10  readonly>";
		           $object[$li_row][4] = "<a href=javascript:uf_delete_dt('".$li_row."');><img src=../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
			     }
		    }
        uf_agregarlineablanca($object,$li_totrows);	  
	  }

   /////////////////////// D E L E T E A L L ///////////////////////////////////////////////////////////////////	
   if ($ls_operacion=="DELETEALL")
	  {
	    $lb_valido = $io_consolidacion->uf_delete_consolidacion($ls_codemp,$la_seguridad);
		if ($lb_valido)
		   {
		     $io_msg->message("Registro Eliminado !!!");
		   }
		else
		   {
		     $io_msg->message("Error en Eliminación !!!");
		   }
	    uf_limpiarvariables(); 
		$li_totrows = 1;
		uf_agregarlineablanca($object,$li_totrows);
	  }
?>
<p>&nbsp;</p>
<div align="center">
  <table width="718" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="716" height="221" valign="top">
<form name="formulario" method="post" action="" id="sigesp_cfg_d_consolidacion.php">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cfg->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cfg);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>          <p>&nbsp;</p>
          <table width="680" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="2">Informaci&oacute;n de Consolidaci&oacute;n <span class="titulo-celda">
                  <input name="hidestcla"  type="hidden" id="hidestcla"  value="<?php echo $ls_estcla; ?>" />
                </span></td>
              </tr>
              <tr class="formato-blanco">
                <td width="142" height="22">&nbsp;</td>
                <td width="536" height="22"><input name="status" type="hidden" id="status" value="<?php print $ls_estatus ?>"></td>
              </tr>
                <tr>
                  <td height="22" title="<?php print $dat["nomestpro1"]; ?>" style="text-align:right">Base de Datos</td>
                  <td height="22" colspan="2"><input name="txtbd" type="text" id="txtbd" style="text-align:center" value="<?php print $ls_bd;?>" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'_');"></td>
                </tr>
                <tr>
    <td height="22" title="<?php print $dat["nomestpro1"]; ?>"><div align="right"><?php print $dat["nomestpro1"];  ?></div></td>
    <td height="22" colspan="2">
      <div align="left">
        <input name="txtcodestpro1" type="text" id="txtcodestpro1" style="text-align:center" value="<?php print $ls_codestpro1;?>" size="<?php print $ls_loncodestpro1+2 ?>" maxlength="<?php print $ls_loncodestpro1 ?>" readonly>
        <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 1"></a>        
        <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php print $ls_denestpro1;?>" size="65" readonly>
      </div>     </td>
  </tr>
                <tr>
                  <td height="22" title="<?php print $dat["nomestpro2"]; ?>" style="text-align:right"><?php print $dat["nomestpro2"];  ?></td>
                  <td height="22" colspan="2"><input name="txtcodestpro2" type="text" id="txtcodestpro2" style="text-align:center" value="<?php print $ls_codestpro2;?>" size="<?php print $li_loncodestpro2+2 ?>" maxlength="<?php print $li_loncodestpro2 ?>" readonly>
                  <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
                  <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" value="<?php print $ls_denestpro2;?>" size="65" readonly>
                  </td>
                </tr>
                <tr>
                  <td height="22" title="<?php print $dat["nomestpro3"]; ?>" style="text-align:right"><?php print $dat["nomestpro3"];  ?></td>
                  <td height="22" colspan="2"><input name="txtcodestpro3" type="text" id="txtcodestpro3" style="text-align:center" value="<?php print $ls_codestpro3;?>" size="<?php print $li_loncodestpro3+2 ?>" maxlength="<?php print $li_loncodestpro3 ?>" readonly>
                  <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
                  <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" value="<?php print $ls_denestpro3; ?>" size="65" readonly>
                  </td>
                </tr>
                <?php
				  if ($_SESSION["la_empresa"]["estmodest"]==2)
				     {
				?>
				<tr>
                  <td height="22" title="<?php print $dat["nomestpro4"]; ?>" style="text-align:right"><?php print $dat["nomestpro4"];  ?></td>
                  <td height="22" colspan="2"><input name="txtcodestpro4" type="text" id="txtcodestpro4" style="text-align:center" value="<?php print $ls_codestpro4;?>" size="<?php print $li_loncodestpro4+2 ?>" maxlength="<?php print $li_loncodestpro4 ?>" readonly>
                  <a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 4"></a>
                  <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" value="<?php print $ls_denestpro4; ?>" size="65" readonly>
                  </td>
                </tr>
                <tr>
                  <td height="22" title="<?php print $dat["nomestpro5"]; ?>" style="text-align:right"><?php print $dat["nomestpro5"];  ?></td>
                  <td height="22" colspan="2"><input name="txtcodestpro5" type="text" id="txtcodestpro5" style="text-align:center" value="<?php print $ls_codestpro5;?>" size="<?php print $li_loncodestpro5+2 ?>" maxlength="<?php print $li_loncodestpro5 ?>" readonly>
                  <a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 5"></a>
                  <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" value="<?php print $ls_denestpro5; ?>" size="65" readonly>
                  </td>
                </tr>
				<?php
				     }
				?>
       <tr class="formato-blanco">
              <td height="22" colspan="2">&nbsp;&nbsp;
              <div align="left"><a href="javascript: uf_agregar_detalle();"> <img src="../shared/imagebank/mas.gif" width="9" height="17" border="0">Agregar Detalle </a></div>              </td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="2"><p align="center">
                <?php $io_grid->makegrid($li_totrows,$title,$object,580,'Detalle Consolidación',$grid1);?>
             </td>
            </tr>
          </table>
          <p align="center">&nbsp;          </p>
            <p align="center">
              <input name="operacion"  type="hidden" id="operacion" >
              <input name="filadelete" type="hidden" id="filadelete">
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
            </p>
		</form></td>
      </tr>
  </table>
</div>
</body>
<script language="javascript">
f = document.formulario;
function ue_nuevo()
{
  li_incluir=f.incluir.value;
  fila=f.totalfilas.value;
  if (li_incluir==1)
	 {	
	   f.operacion.value ="CARGAR_DT";
	   f.action="sigesp_cfg_d_consolidacion.php";
	   f.submit();
	 }
  else
     {
 	   alert("No tiene permiso para realizar esta operación");
	 }
}

function ue_guardar()
{
  li_incluir    = f.incluir.value;
  li_cambiar    = f.cambiar.value;
  lb_status     = f.status.value;
  ls_codestpro1 = f.txtcodestpro1.value;
  ls_bd         = f.txtbd.value; 
  ls_estcla1    = f.hidestcla.value;
  fila          = f.totalfilas.value;
  if (((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
     {
       if ((ls_bd=="")&&(ls_codestpro1==""))
	      {
			 f.operacion.value ="GUARDAR";
			 f.action="sigesp_cfg_d_consolidacion.php";
			 f.submit();			   
		  }
	   else
	      {
	        alert("Presione Agregar Detalle para Incluir el Registro al Grid !!!");
	      }
     }
  else
     {
 	   alert("No tiene permiso para realizar esta operación !!!");
	 }	
}					

function ue_eliminar()
{
li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
	  if (confirm("¿ Esta seguro de Eliminar toda la Información de Consolidación  ?"))
	     {
		   ls_codestpro1 = f.txtcodestpro1.value;
		   f.operacion.value ="DELETEALL";
		   f.action="sigesp_cfg_d_consolidacion.php";
		   f.submit();
		 
	     }
	}
  else
    {
      alert("No tiene permiso para realizar esta operación !!!");
	}
}

function ue_cerrar()
{
	f.action="sigespwindow_blank.php";
	f.submit();
}

function catalogo_estpro1()
{
  ls_opener = f.id;
  f.operacion.value="CASTEST";
  pagina    = "spg/sigesp_spg_cat_estpro1.php?opener="+ls_opener;
  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=620,height=400,resizable=yes,location=no");
}

function catalogo_estpro2()
{
  ls_opener     = f.id;
  ls_codestpro1 = f.txtcodestpro1.value;
  ls_denestpro1 = f.txtdenestpro1.value;
  ls_estcla     = f.hidestcla.value;
  if ((ls_codestpro1!="")&&(ls_denestpro1!=""))
	 {
	   pagina="spg/sigesp_spg_cat_estpro2.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtclasificacion="+ls_estcla+"&opener="+ls_opener;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=620,height=400,resizable=yes,location=no");
	 }
  else
	 {
	   alert("Debe seleccionar una estructura del Nivel 1 !!!");
	 }
}

function catalogo_estpro3()
{
  ls_codestpro1 = f.txtcodestpro1.value;
  ls_codestpro2 = f.txtcodestpro2.value;
  li_estmodest  = "<?php print $_SESSION["la_empresa"]["estmodest"]; ?>";
  if ((ls_codestpro1!='' && ls_codestpro2=='') || (ls_codestpro1=='' && ls_codestpro2=='' && li_estmodest=='2'))
     {
	   alert("Debe seleccionar una estructura del Nivel 2 !!!");
	 }
  else
     {
	   ls_estcla     = f.hidestcla.value;
	   ls_opener     = f.id;
	   ls_denestpro1 = f.txtdenestpro1.value;
	   ls_denestpro2 = f.txtdenestpro2.value;
	   ls_codestpro3 = f.txtcodestpro3.value;   
	   pagina = "spg/sigesp_spg_cat_estpro3.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtclasificacion="+ls_estcla+"&opener="+ls_opener;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=780,height=450,resizable=yes,location=no");
	 }
}

function catalogo_estpro4()
{
  ls_codestpro1 = f.txtcodestpro1.value;
  ls_codestpro2 = f.txtcodestpro2.value;
  ls_codestpro3 = f.txtcodestpro3.value;

  if (ls_codestpro1=='' || ls_codestpro2=='' || ls_codestpro3=='')
     {
	   alert("Debe seleccionar una estructura del Nivel 3 !!!");
	 }
  else
     {
	   ls_estcla     = f.hidestcla.value;
	   ls_opener     = f.id;	   
	   ls_denestpro1 = f.txtdenestpro1.value;
	   ls_denestpro2 = f.txtdenestpro2.value;
	   ls_denestpro3 = f.txtdenestpro3.value;
	   
	   pagina = "spg/sigesp_spg_cat_estpro4.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1
	                                     +"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2
										 +"&txtcodestpro3="+ls_codestpro3+"&txtdenestpro3="+ls_denestpro3
										 +"&txtclasificacion="+ls_estcla+"&opener="+ls_opener;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=780,height=450,resizable=yes,location=no");
	 }
}

function catalogo_estpro5()
{
  ls_codestpro1 = f.txtcodestpro1.value;
  ls_codestpro2 = f.txtcodestpro2.value;
  ls_codestpro3 = f.txtcodestpro3.value;
  ls_codestpro4 = f.txtcodestpro4.value;
  if ((ls_codestpro1!='' && ls_codestpro2!='' && ls_codestpro3!='' && ls_codestpro4=='') ||
     (ls_codestpro1!='' && ls_codestpro2!='' && ls_codestpro3=='' && ls_codestpro4=='') ||
	 (ls_codestpro1!='' && ls_codestpro2=='' && ls_codestpro3=='' && ls_codestpro4==''))
     {
	   alert("Debe seleccionar una estructura del Nivel 4 !!!");
	 }
  else
     {
	   ls_estcla     = f.hidestcla.value;
	   ls_opener     = f.id;
	   
	   ls_denestpro1 = f.txtdenestpro1.value;
	   ls_denestpro2 = f.txtdenestpro2.value;
	   ls_denestpro3 = f.txtdenestpro3.value;
	   ls_denestpro4 = f.txtdenestpro4.value;
	   
	   pagina = "spg/sigesp_spg_cat_estpro5.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1
	                                     +"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2
										 +"&txtcodestpro3="+ls_codestpro3+"&txtdenestpro3="+ls_denestpro3
										 +"&txtcodestpro4="+ls_codestpro4+"&txtdenestpro4="+ls_denestpro4
										 +"&txtclasificacion="+ls_estcla+"&opener="+ls_opener;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=780,height=450,resizable=yes,location=no");
	 }
}

function  uf_agregar_detalle()
{
  ls_estcla     = f.hidestcla.value;
  li_estmodest  = "<?php print $_SESSION["la_empresa"]["estmodest"]; ?>";
  ls_codestpro1 = f.txtcodestpro1.value;
  ls_codestpro2 = f.txtcodestpro2.value;
  ls_codestpro3 = f.txtcodestpro3.value;
  if (li_estmodest==2)
     {
	   ls_codestpro4 = f.txtcodestpro4.value;
	   ls_codestpro5 = f.txtcodestpro5.value;
	 }
  else
     {
	   ls_codestpro4 = "";
	   ls_codestpro5 = "";
	 }	
	ls_basdat     = f.txtbd.value; 
	fila		  = f.totalfilas.value; 
	ls_operacion  = f.operacion.value;
	if (ls_operacion=="CASTEST")
	   {
	     if (ls_basdat=="")
		    {
			  alert("Especifíque el nombre de la Base de Datos !!!");
			}
		 else
		    {
			  lb_valido = true;
			  if (ls_codestpro1=="" || ls_codestpro2=="" || ls_codestpro3=="" || ls_estcla=="")
			     {
				   lb_valido = false;
				   alert("Debe completar la Estructura Presupuestaria !!!");
				 }  
			  if (li_estmodest==2 && (ls_codestpro4=="" || ls_codestpro5=="" || ls_estcla==""))
			     {
				   lb_valido = false;
				   alert("Debe completar la Estructura Presupuestaria !!!");
				 }
			  if (lb_valido)
			     {
				   li_totrows = ue_calcular_total_fila_local("txtcodestpre");
				   if (li_totrows>0)
				      {
					    lb_existe    = false;
						ls_codestpro = ls_codestpro1+" - "+ls_codestpro2+" - "+ls_codestpro3;
						if (li_estmodest==2)
						   {
						     ls_codestpro = ls_codestpro+" - "+ls_codestpro4+" - "+ls_codestpro5;
						   }
					    ls_codpre = ls_codestpro+ls_estcla;//Código Presupuestario.
						for (li_i=1;li_i<=li_totrows;li_i++)
						    {
							  ls_claestpre = eval("f.txtestcla"+li_i+".value");//Clasificación de la Estructura Presupuestaria.
							  ls_claestpre = ls_claestpre.substr(0,1);
							  ls_codestpre = eval("f.txtcodestpre"+li_i+".value");
							  ls_codestpre = ls_codestpre+ls_claestpre;
							  if (ls_codestpre==ls_codpre && ls_claestpre!=="")
							     {
									lb_existe = true;
									alert("La Estructura Presupuestaria ya fué Incluida !!!");
									break;
								 }
							}
					     if (!lb_existe)
						    {
							  eval("f.txtdesbd"+li_totrows+".value='"+ls_basdat+"'");
							  eval("f.txtcodestpre"+li_totrows+".value='"+ls_codestpro+"'");
							  if (ls_estcla=='P')
							     {
								   ls_denestcla = "PROYECTO";
								 }
							  else if(ls_estcla=='A')
							     {
								   ls_denestcla = "ACCIÓN";
								 }
							  eval("f.txtestcla"+li_totrows+".value='"+ls_denestcla+"'");  
							  f.totalfilas.value = li_totrows+1;
							  f.operacion.value = "AGREGAR_DETALLE";
							  f.action		    = "sigesp_cfg_d_consolidacion.php";
							  f.submit();
							}
					  }
				 }
			}
	   }
	else
	   {
	     alert("No puede Modificar esta Información.");
	   }
}

function uf_delete_dt(fila)
{
  li_fila = f.totalfilas.value;
  if (li_fila!=fila)
	 {
	   if (confirm("¿Desea Eliminar el Registro Actual?"))
		  {	
		    f.filadelete.value=fila;
			f.operacion.value="DELETE"
			f.action="sigesp_cfg_d_consolidacion.php";
			f.submit();
		  }
	 }
}

function uf_delete_all()
{
	f=document.formulario;
	if(confirm("Está Seguro de Eliminar toda la información de consolidación ?"))
	{
		ls_codestpro1 = f.txtcodestpro1.value;
	    ls_bd = f.txtbd.value; 
		
		f.operacion.value="DELETEALL";
		f.action="sigesp_cfg_d_consolidacion.php";
		f.submit();
		
	}
}
</script>
</html>