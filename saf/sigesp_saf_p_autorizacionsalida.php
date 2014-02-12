<?php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_funciones_activos.php");
	$io_fun_activo=new class_funciones_activos("../");
	$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_p_autorizacionsalida.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_reporte=$io_fun_activo->uf_select_config("SAF","REPORTE","FORMATO_AUTOSALIDA","sigesp_saf_rpp_autorizacionsalida.php","C");
	require_once("sigesp_saf_c_activo.php");
    $ls_codemp = $_SESSION["la_empresa"]["codemp"];
    $io_saf_tipcat= new sigesp_saf_c_activo();
    $ls_rbtipocat=$io_saf_tipcat->uf_select_valor_config($ls_codemp);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_obtenervalor($as_valor, $as_valordefecto)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_obtenervalor
	//	Access:    public
	//	Arguments:
    // 				as_valor         //  nombre de la variable que desamos obtener
    // 				as_valordefecto  //  contenido de la variable
    // Description: Función que obtiene el valor de una variable si viene de un submit
	//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists($as_valor,$_POST))
		{
			$valor=$_POST[$as_valor];
		}
		else
		{
			$valor=$as_valordefecto;
		}
   		return $valor; 
   }
	
	function uf_limpiarvariables()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
		global $ls_cmpsal,$ls_coduniadmcede,$ls_denuniadmcede,$ld_fechauto,$ld_fecent,$ld_fecdevo;
	    global $li_totrows,$ls_status,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_codprov,$ls_nomprov;
		global $ls_cedrepre,$ls_nomrepre,$ls_concepto,$ls_obser,$ls_estauto;
		
		$ls_cmpsal="";
		$ls_coduniadmcede="";
		$ls_denuniadmcede="";
		$li_totrows=1;
		$ls_codprov="";
		$ls_nomprov="";
		$ls_cedrepre="";
		$ls_nomrepre="";
		$ls_concepto="";  
		$ls_obser="";
		$ls_estauto="";
		$ls_titletable="Activos";
		$li_widthtable=750;
		$ls_nametable="grid";
		$lo_title[1]="Activo";
		$lo_title[2]="Serial";
		$lo_title[3]="Descripción del Activo";
		$lo_title[4]="Monto Activo";
		$lo_title[5]="";	
		$ld_fechauto= date("d/m/Y");
		$ld_fecent=date("d/m/Y");
		$ld_fecdevo=date("d/m/Y");
	}
	
  function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $aa_object // arreglo de titulos 
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que agrega una linea en blanco al final del grid
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 23/03/2006 								Fecha Última Modificación : 23/03/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtcodact".$ai_totrows." type=text id=txtcodact".$ai_totrows." class=sin-borde size=17 maxlength=15 readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtidact".$ai_totrows."  type=text   id=txtidact".$ai_totrows."  class=sin-borde size=17 maxlength=15 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtdenact".$ai_totrows." type=text   id=txtdenact".$ai_totrows." class=sin-borde size=45 readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtmonact".$ai_totrows." type=text   id=txtmonact".$ai_totrows." class=sin-borde size=15 readonly>";
		$aa_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";

   }
   
    function uf_pintardetalle(&$lo_object,$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pintardetalle
		//         Access: private
		//      Argumento: $aa_object // arreglo de objetos
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que se encarga de repintar el detalle existente en el grid.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/04/2006 								Fecha Última Modificación : 11/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		for($li_i=1;$li_i<$ai_totrows;$li_i++)
		{
			$ls_codact= $_POST["txtcodact".$li_i];
			$ls_denact= $_POST["txtdenact".$li_i];
			$ls_idact=  $_POST["txtidact".$li_i];
			$li_monact= $_POST["txtmonact".$li_i];

			$lo_object[$li_i][1]="<input name=txtcodact".$li_i." type=text id=txtcodact".$li_i." class=sin-borde size=17 maxlength=15 value='".$ls_codact."' readonly>";
			$lo_object[$li_i][2]="<input name=txtidact".$li_i."  type=text   id=txtidact".$li_i."  class=sin-borde size=17 maxlength=15 value='".$ls_idact."'  readonly>";
			$lo_object[$li_i][3]="<input name=txtdenact".$li_i." type=text   id=txtdenact".$li_i." class=sin-borde size=52 value='".$ls_denact."' readonly>";
			$lo_object[$li_i][4]="<input name=txtmonact".$li_i." type=text   id=txtmonact".$li_i." class=sin-borde size=15 value='".$li_monact."' readonly>";
			$lo_object[$li_i][5]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
		}
		uf_agregarlineablanca($lo_object,$ai_totrows);
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Autorizaci&oacute;n de Salida</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
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
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Activos Fijos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
 <?php 
    if ($ls_rbtipocat == 1) 
    {
   ?>
   <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_csc.js"></script></td>
  <?php 
    }
	elseif ($ls_rbtipocat == 2)
	{
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_cgr.js"></script></td>
  <?php 
	}
	else
	{
   ?>
	<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php 
	}
   ?>
  </tr>
  <tr>
    <td height="13" colspan="8" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_imprimir('<?php print $ls_reporte ?>');"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
    require_once("../shared/class_folder/sigesp_include.php");
	$in=     new sigesp_include();
    $con= $in->uf_conectar();
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$io_keygen= new sigesp_c_generar_consecutivo();
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_codusureg=$_SESSION["la_logusr"];
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();
	$ls_operacion=$io_fun_activo->uf_obteneroperacion();
	require_once("../shared/class_folder/class_funciones.php");
	$io_fun= new class_funciones();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fundb= new class_funciones_db($con);
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("sigesp_saf_c_movimiento.php");
	$io_saf= new sigesp_saf_c_movimiento();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=  new class_sql($con);
	$li_totrows = uf_obtenervalor("totalfilas",1);	
	
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();
		    $ls_cmpsal= $io_keygen->uf_generar_numero_nuevo("SAF","saf_autsalida","cmpsal","SAF",15,"","codemp",$ls_codemp);
			uf_agregarlineablanca($lo_object,$li_totrows);
		break;
		
	
		case "AGREGARDETALLE":
		    uf_limpiarvariables();
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$li_totrows=$li_totrows+1; 
			$ls_cmpsal=$_POST["txtautosali"];
			$ls_coduniadmcede=$_POST["txtcoduniadm"];
			$ls_denuniadmcede=$_POST["txtdenuniadm"];
			$ld_fechauto=$_POST["txtfechsalida"];
			$ls_codprov=$_POST["txtcodpro"];
	        $ls_nomprov=$_POST["txtdenpro"];
			$ls_cedrepre=$_POST["txtcedrepre"];
			$ls_nomrepre=$_POST["txtnomrepre"];
			$ls_concepto=$_POST["txtconcepto"];  
			$ld_fecent=$_POST["txtfecentrega"];
			$ld_fecdevo=$_POST["txtfecdevolucion"]; 
			$ls_obser=$_POST["txtobser"];   
			for ($li_i=1;$li_i<$li_totrows;$li_i++)
			    {
				  $ls_codact = $_POST["txtcodact".$li_i];
				  $ls_denact = $_POST["txtdenact".$li_i];
				  $ls_idact  = $_POST["txtidact".$li_i];
				  $li_monact = $_POST["txtmonact".$li_i];
				
				  $lo_object[$li_i][1]="<input name=txtcodact".$li_i." type=text id=txtcodact".$li_i." class=sin-borde size=17 maxlength=15  value='".$ls_codact."' readonly>";
				  $lo_object[$li_i][2]="<input name=txtidact".$li_i."  type=text id=txtidact".$li_i."    class=sin-borde size=17 maxlength=15 value='". $ls_idact ."' readonly>";
			      $lo_object[$li_i][3]="<input name=txtdenact".$li_i." type=text   id=txtdenact".$li_i." class=sin-borde size=52 value='".$ls_denact."' readonly>";
				  $lo_object[$li_i][4]="<input name=txtmonact".$li_i." type=text id=txtmonact".$li_i."   class=sin-borde size=15 value='".$li_monact."' readonly>";
				  $lo_object[$li_i][5]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
			    }	
			uf_agregarlineablanca($lo_object,$li_totrows);

		break;
		
		case "GUARDAR":
			uf_limpiarvariables();
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$ls_codusureg=$_SESSION["la_logusr"];
	        $ls_cmpsal=$_POST["txtautosali"];
			$ls_coduniadmcede=$_POST["txtcoduniadm"];
			$ls_denuniadmcede=$_POST["txtdenuniadm"];
			$ld_fechauto=$_POST["txtfechsalida"];
			$ls_codprov=$_POST["txtcodpro"];
	        $ls_nomprov=$_POST["txtdenpro"];
			$ls_cedrepre=$_POST["txtcedrepre"];
			$ls_nomrepre=$_POST["txtnomrepre"];
			$ls_concepto=$_POST["txtconcepto"];  
			$ld_fecent=$_POST["txtfecentrega"];
			$ld_fecdevo=$_POST["txtfecdevolucion"]; 
			$ls_obser=$_POST["txtobser"];   
	        $ls_estauto="0";
			if(($ls_cmpsal=="")||($ls_coduniadmcede=="")||($ld_fechauto=="")||($ls_codprov=="")||($ls_concepto=="")||($ld_fecent=="")||($ld_fecent=="")||($ls_obser==""))
			{
			  //uf_agregarlineablanca($lo_object,1);
			  $io_msg->message("Debe compeltar los campos");
			}
			else
			{ 
			   $ld_fechauto=$io_fun->uf_convertirdatetobd($ld_fechauto);
			   $ld_fecent=$io_fun->uf_convertirdatetobd($ld_fecent);
			   $ld_fecdevo=$io_fun->uf_convertirdatetobd($ld_fecdevo);
			   $lb_existe=$io_saf->uf_saf_select_autorizacion($ls_codemp,$ls_cmpsal,$ld_fechauto);
			   if($lb_existe)
			   {
				   if ($ls_estauto=="0")
					{   
    				  for($li_i=1;$li_i<=$li_totrows;$li_i++)
					  {
						  $ls_codact= $_POST["txtcodact".$li_i];  
						  $ls_denact= $_POST["txtdenact".$li_i];   
						  $ls_idact=  $_POST["txtidact".$li_i];
						  //$ls_desmov= $_POST["txtdesmov".$li_i];
						  $li_monact= $_POST["txtmonact".$li_i];
					      $lb_valido=$io_saf->uf_saf_update_autorizacion($ls_codemp,$ls_cmpsal,$ls_coduniadmcede,$ls_codprov,
																 $ls_cedrepre,$ld_fechauto,$ld_fecent,$ld_fecdevo,$ls_estauto,
																 $ls_concepto,$ls_obser,$la_seguridad);
					   }
					   if($lb_valido)
						{
						    $ls_estactpre="1";
						    $lb_valido=$io_saf->uf_saf_update_saf_dta($ls_codemp,$ls_codact,$ls_coduniadmcede,$ls_idact,
							                                          $ls_estactpre,$ld_fechauto,$ls_codprov,$la_seguridad);
						}
						if ($lb_valido)
						{
							$io_msg->message("El registro fue actualizado con exito");
							uf_agregarlineablanca($lo_object,1);
							uf_limpiarvariables();
						}	
						else
						{
							$io_msg->message("El registro no pudo ser actualizado");
							uf_agregarlineablanca($lo_object,1);
							uf_limpiarvariables();
						}
					
					 } // fin del if $ls_estauto
			    }
				else
				{
					$io_sql->begin_transaction();
					$lb_valido=$io_saf->uf_saf_insertar_autorizacion($ls_codemp,$ls_cmpsal,$ls_coduniadmcede,$ls_codprov,
																 $ls_cedrepre,$ld_fechauto,$ld_fecent,$ld_fecdevo,$ls_estauto,
																 $ls_concepto,$ls_obser,$la_seguridad);
					if($lb_valido)
					{
						$io_sql->commit();
						for($li_i=1;$li_i<$li_totrows;$li_i++)
						{
							$ls_codact= $_POST["txtcodact".$li_i];  
							$ls_denact= $_POST["txtdenact".$li_i];   
							$ls_idact=  $_POST["txtidact".$li_i];
							//$ls_desmov= $_POST["txtdesmov".$li_i];
							$li_monact= $_POST["txtmonact".$li_i];
							$li_monact= str_replace(".","",$li_monact);
							$li_monact= str_replace(",",".",$li_monact);
							$ld_fechauto=$io_fun->uf_convertirdatetobd($ld_fechauto);
							$lb_valido=$io_saf->uf_saf_insertar_dt_autorizacion($ls_codemp,$ls_cmpsal,$ls_coduniadmcede,$ls_codprov,
							                                                    $ls_cedrepre,$ld_fechauto,$ls_codact,$ls_idact,$la_seguridad);
							if ($lb_valido)
							{
							  $ls_estactpre="1";
							  $lb_valido=$io_saf->uf_saf_update_saf_dta($ls_codemp,$ls_codact,$ls_coduniadmcede,$ls_idact,
							                                             $ls_estactpre,$ld_fechauto,$ls_codprov,$la_seguridad);
							}
						}
						$io_msg->message("El registro fue incluido con exito");
						$ls_estpres=0;
						uf_agregarlineablanca($lo_object,1);
						uf_limpiarvariables();
						$li_totrows=1;
					  }	
					  else
					  {
						$io_sql->rollback();
						$io_msg->message("No se pudo incluir el registro");
						uf_pintardetalle($lo_object,$ai_totrows);
					  }
		         }
			 }
		break;
		
		case "ELIMINARDETALLE":
			uf_limpiarvariables();
			$li_totrows = uf_obtenervalor("totalfilas",1); 
			$ls_cmpsal=$_POST["txtautosali"];
			$ls_coduniadmcede=$_POST["txtcoduniadm"];
			$ls_denuniadmcede=$_POST["txtdenuniadm"];
			$ld_fechauto=$_POST["txtfechsalida"];
			$ls_codprov=$_POST["txtcodpro"];
	        $ls_nomprov=$_POST["txtdenpro"];
			$ls_cedrepre=$_POST["txtcedrepre"];
			$ls_nomrepre=$_POST["txtnomrepre"];
			$ls_concepto=$_POST["txtconcepto"];  
			$ld_fecent=$_POST["txtfecentrega"];
			$ld_fecdevo=$_POST["txtfecdevolucion"]; 
			$ls_obser=$_POST["txtobser"];   
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp=$li_temp+1;			
					$ls_codact= $_POST["txtcodact".$li_i];
					$ls_denact= $_POST["txtdenact".$li_i];
					$ls_idact=  $_POST["txtidact".$li_i];
					//$ls_desmov= $_POST["txtdesmov".$li_i];
					$li_monact= $_POST["txtmonact".$li_i];
					
					$lo_object[$li_temp][1]="<input name=txtcodact".$li_temp." type=text id=txtcodact".$li_temp." class=sin-borde size=17 maxlength=15  value='".$ls_codact."' readonly>";
					$lo_object[$li_temp][2]="<input name=txtidact".$li_temp."  type=text   id=txtidact".$li_temp."  class=sin-borde size=17 maxlength=15 value='". $ls_idact ."' readonly>";
			        $lo_object[$li_temp][3]="<input name=txtdenact".$li_temp." type=text   id=txtdenact".$li_temp." class=sin-borde size=52 value='".$ls_denact."' readonly>";
					$lo_object[$li_temp][4]="<input name=txtmonact".$li_temp." type=text   id=txtmonact".$li_temp." class=sin-borde size=15 value='". $li_monact ."' readonly>";
					$lo_object[$li_temp][5]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
				}
				else
				{
					$li_rowdelete= 0;
				}
			}
			if ($li_temp==0)
			{
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			else
			{				
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
		break;
		
		case "BUSCARDETALLE":
			uf_limpiarvariables();
			$ls_cmpsal=$_POST["txtautosali"];
			$ls_coduniadmcede=$_POST["txtcoduniadm"];
			$ls_denuniadmcede=$_POST["txtdenuniadm"];
			$ld_fechauto=$_POST["txtfechsalida"];
			$ls_codprov=$_POST["txtcodpro"];
	        $ls_nomprov=$_POST["txtdenpro"];
			$ls_cedrepre=$_POST["txtcedrepre"];
			$ls_nomrepre=$_POST["txtnomrepre"];
			$ls_concepto=$_POST["txtconcepto"];  
			$ld_fecent=$_POST["txtfecentrega"];
			$ld_fecdevo=$_POST["txtfecdevolucion"]; 
			$ls_obser=$_POST["txtobser"];   
			$ls_estauto=$_POST["hidestauto"];
			$lb_valido=$io_saf->uf_saf_load_detalle_autorizacion($ls_codemp,$ls_cmpsal,$ls_coduniadmcede,$ld_fechauto,$ls_codprov,
			                                                     $ls_cedrepre,$li_totrows,$lo_object);
			
		break;
		
		case "PROCESAR":
		    $ls_estprosal="1";
			$ls_cmpsal=$_POST["txtautosali"];
			$ls_coduniadmcede=$_POST["txtcoduniadm"];
			$ls_denuniadmcede=$_POST["txtdenuniadm"];
			$ld_fechauto=$_POST["txtfechsalida"];
			$ls_codprov=$_POST["txtcodpro"];
	        $ls_nomprov=$_POST["txtdenpro"];
			$ls_cedrepre=$_POST["txtcedrepre"];
			$ls_nomrepre=$_POST["txtnomrepre"];
			$ls_concepto=$_POST["txtconcepto"];  
			$ld_fecent=$_POST["txtfecentrega"];
			$ld_fecdevo=$_POST["txtfecdevolucion"]; 
			$ls_obser=$_POST["txtobser"]; 
			$ls_estauto=$_POST["hidestauto"];    
			$io_sql->begin_transaction();
			$lb_valido=$io_saf->uf_saf_update_procesarautorizacion($ls_codemp,$ls_cmpsal,$ls_coduniadmcede,
				                                               $ld_fechauto,$ls_codprov,$ls_estprosal,$la_seguridad);
			if($lb_valido)
			{
				$io_sql->commit();
				$io_msg->message("El registro fue procesado con exito");
				uf_agregarlineablanca($lo_object,1);
				uf_limpiarvariables();
				$li_totrows=1;
			}
			else
			{
				$io_sql->rollback();
				$io_msg->message("No se pudo procesar el registro");
				uf_limpiarvariables();
				uf_agregarlineablanca($lo_object,1);
			}

	break;
		

		case "REVERSAR":
			$ls_cmpsal=$_POST["txtautosali"];
			$ls_coduniadmcede=$_POST["txtcoduniadm"];
			$ls_denuniadmcede=$_POST["txtdenuniadm"];
			$ld_fechauto=$_POST["txtfechsalida"];
			$ls_codprov=$_POST["txtcodpro"];
			$ls_estauto=$_POST["hidestauto"];
	 		$ls_estprosal=0;
			$io_sql->begin_transaction(); 
			$lb_valido=$io_saf->uf_saf_update_procesarautorizacion($ls_codemp,$ls_cmpsal,$ls_coduniadmcede,
				                                               $ld_fechauto,$ls_codprov,$ls_estprosal,$la_seguridad);
			if($lb_valido)
			{
				$io_sql->commit();
				$io_msg->message("El registro fue reversado con exito");
				uf_agregarlineablanca($lo_object,1);
				uf_limpiarvariables();
				$li_totrows=1;
			}
			else
			{
				$io_sql->rollback();
				$io_msg->message("No se pudo reversar el registro");
				uf_limpiarvariables();
				uf_agregarlineablanca($lo_object,1);
			}
	
		break;

	}  // fin del switch
		
?>

<p>&nbsp;</p>
<div align="center">
  <table width="619" height="159" border="0" class="formato-blanco">
    <tr>
      <td width="611" height="153"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="584" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td colspan="3" class="titulo-ventana">Autorizaci&oacute;n de Salida </td>
  </tr>
  <tr class="formato-blanco">
    <td width="124" height="19">&nbsp;</td>
    <td width="362"><div align="right">Fecha</div></td>
    <td width="96"><input name="txtfechsalida" type="text" id="txtfechsalida" style="text-align:center " value="<?php print $ld_fechauto; ?>" size="13" maxlength="10" datepicker="true" onKeyPress="ue_separadores(this,'/',patron,true);"></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Comprobante</div></td>
    <td height="22" colspan="2">        <input name="txtautosali" type="text" id="txtautosali" value="<?php print $ls_cmpsal; ?>" maxlength="15" onBlur="javascript: ue_rellenarcampo(this,'15')" style="text-align:center "></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Unidad F&iacute;sica Cedente </div></td>
    <td height="22" colspan="2"><input name="txtcoduniadm" type="text" id="txtcoduniadm" style="text-align:center " value="<?php print $ls_coduniadmcede; ?>" size="13" maxlength="10" >
      <a href="javascript:ue_buscarunidad();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadmcede; ?>" size="50" readonly></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><a href="javascript: ue_agregarbienes();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" class="sin-borde">Agregar Activos </a></td>
    <td height="22" colspan="2">&nbsp;</td>
  </tr>
    
    
    
    <tr class="formato-blanco">
      <td height="22" colspan="3"><?php
		 $in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
	   ?></td>
    </tr>
    <tr class="formato-blanco">
      <td height="22" colspan="3">&nbsp;</td>
      </tr>
    <tr class="formato-blanco">
      <td height="22"><div align="right">Empresa al que se Entrega </div></td>
      <td height="22" colspan="2"><label>
        <input name="txtcodpro" type="text" id="txtcodpro" value="<?php print $ls_codprov ?>" size="20" maxlength="10" style="text-align:center" readonly tabindex="2" />
        <a href="javascript:ue_catalogo_proveedores();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"  /></a></label>
        <label>
        <input name="txtdenpro" type="text" class="sin-borde" id="txtdenpro" value="<?php print $ls_nomprov ?>" size="50" maxlength="50" readonly style="text-align:left" />
        </label></td>
    </tr>
    <tr class="formato-blanco">
    <td height="22"><div align="right">
      <p>Representante de la Empresa </p>
      </div></td>
    <td height="22" colspan="2"><label></label><label>
        <input name="txtcedrepre" type="text" id="txtcedrepre" style="text-align:center" tabindex="2" value="<?php print $ls_cedrepre ?>" size="20" maxlength="10" readonly />
        <a href="javascript:ue_catalogo_proveedores();"></a></label>
      <label>
      <input name="txtnomrepre" type="text" class="sin-borde" id="txtnomrepre" value="<?php print $ls_nomrepre ?>" size="50" maxlength="50" readonly style="text-align:left" />
      </label></td>
  </tr>
    <tr class="formato-blanco">
      <td height="22"><div align="right">Por Concepto </div></td>
      <td height="22">
        <div align="left">
          <textarea name="txtconcepto" cols="60" rows="3" id="txtconcepto"><?php print $ls_concepto?></textarea>
        </div></td>
      <td height="22"><input name="btnprocesar" type="button" class="boton" value="  Procesar" <?php if($ls_estauto==0){ print "onClick='javascript: ue_procesar();'";}?>>
        <input name="hidestauto" type="hidden" id="hidestauto" value="<?php print $ls_estauto ?>"></td>
    </tr>
    <tr class="formato-blanco">
      <td height="22">&nbsp;</td>
      <td height="22" colspan="2">&nbsp;</td>
    </tr>
    <tr class="formato-blanco">
      <td height="22"><div align="right">Fecha de Entrega </div></td>
      <td height="22"><input name="txtfecentrega" type="text" id="txtfecentrega" style="text-align:center " value="<?php print $ld_fecent; ?>" size="13" maxlength="10" datepicker="true" onKeyPress="ue_separadores(this,'/',patron,true);"></td>
      <td height="22"><input name="btnreversar" type="button" class="boton" value="  Reversar"  <?php if($ls_estauto==1){ print "onClick='javascript: ue_reversar();'";}?> ></td>
    </tr>
    
    <tr class="formato-blanco">
      <td height="22"><div align="right">Fecha de Devoluci&oacute;n </div></td>
      <td height="22" colspan="2"><input name="txtfecdevolucion" type="text" id="txtfecdevolucion" style="text-align:center " value="<?php print $ld_fecdevo;?>" size="13" maxlength="10" datepicker="true" onKeyPress="ue_separadores(this,'/',patron,true);"></td>
    </tr>
    <tr class="formato-blanco">
      <td height="22">&nbsp;</td>
      <td height="22" colspan="2">&nbsp;</td>
    </tr>
    <tr class="formato-blanco">
      <td height="22"><div align="right">Observaci&oacute;n</div></td>
      <td height="22" colspan="2"><textarea name="txtobser" cols="60" rows="3" id="txtobser"><?php print $ls_obser ?></textarea></td>
    </tr>
</table>
<input name="operacion" type="hidden" id="operacion">
          <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
          <input name="filadelete" type="hidden" id="filadelete">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_buscar()
{
  window.open("sigesp_saf_cat_autorizacionsalida.php?","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscarunidad()
{
 f=document.form1;
 ls_estauto=f.hidestauto.value;
 if (ls_estauto==1)
 {
  alert("Esta autorización esta procesada. No puede ser modificada.");
 }
 else
 {
 window.open("sigesp_saf_cat_unidadejecutora.php?destino=cedente","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
 }
}

function ue_catalogo_proveedores()
{
  f=document.form1;
  pagina="sigesp_saf_cat_prov.php?&destino=proveedor";
  ls_estauto=f.hidestauto.value;
  if (ls_estauto==1)
   {
    alert("Esta autorización esta procesada. No puede ser modificada.");
   }
  else
  {
   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=500 resizable=yes,location=no,left=50,top=50,dependent=yer");
  }
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="sigesp_saf_p_autorizacionsalida.php";
		f.submit();
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
function ue_guardar()
{
    f=document.form1;
	ls_cmpsal=f.txtautosali.value;
	ls_coduniadmcede=f.txtcoduniadm.value;
	ls_denuniadmcede=f.txtdenuniadm.value;
	ld_fechauto=f.txtfechsalida.value;
	ls_codprov=f.txtcodpro.value;
	ls_nomprov=f.txtdenpro.value;
	ls_cedrepre=f.txtcedrepre.value;
	ls_nomrepre=f.txtnomrepre.value;
	ls_concepto=f.txtconcepto.value;  
	ld_fecent=f.txtfecentrega.value;
    ld_fecdevo=f.txtfecdevolucion.value; 
	ls_obser=f.txtobser.value;   
    ls_estauto=f.hidestauto.value;
	if((ls_cmpsal!="")||(ls_coduniadmcede!="")||(ls_codprov!="")||(ls_cedrepre!="")||(ls_concepto!="")||(ld_fecent!="")||(ld_fecdevo!="")||(ls_obser!=""))
	{
	   if (ls_estauto==1)
		{
		alert("Esta autorización esta procesada. No puede ser modificada.");
		}
	   else
	   {
		f.operacion.value="GUARDAR";
		f.action="sigesp_saf_p_autorizacionsalida.php";
		f.submit();
	   }
	 }
}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}
function ue_imprimir(ls_reporte)
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	ls_cmpsal=f.txtautosali.value;
	ls_coduniadmcede=f.txtcoduniadm.value;
	ls_denuniadmcede=f.txtdenuniadm.value;
	ld_fechauto=f.txtfechsalida.value;
	ls_codprov=f.txtcodpro.value;
	ls_nomprov=f.txtdenpro.value;
	ls_cedrepre=f.txtcedrepre.value;
	ls_nomrepre=f.txtnomrepre.value;
	ls_concepto=f.txtconcepto.value;  
	ld_fecent=f.txtfecentrega.value;
	ld_fecdevo=f.txtfecdevolucion.value; 
	ls_obser=f.txtobser.value; 
	if(li_imprimir==1)
	{
		 pagina="reportes/"+ls_reporte+"?&ls_cmpsal="+ls_cmpsal+"&ls_coduniadmcede="+ls_coduniadmcede+"&ls_denuniadmcede="+ls_denuniadmcede+"";
		 pagina=pagina+"&ld_fechauto="+ld_fechauto+"&ls_codprov="+ls_codprov+"&ls_nomprov="+ls_nomprov+"&ls_cedrepre="+ls_cedrepre+"&ls_nomrepre="+ls_nomrepre+"";
		 pagina=pagina+"&ls_concepto="+ls_concepto+"&ld_fecent="+ld_fecent+"&ld_fecdevo="+ld_fecdevo+"&ls_obser="+ls_obser+"";
		 window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");

	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_agregarbienes()
{
	f=document.form1;
	ls_cmpsal=f.txtautosali.value;
	ls_coduniadmcede=f.txtcoduniadm.value;
	ls_denuniadmcede=f.txtdenuniadm.value;
	ls_estauto=f.hidestauto.value;
	if(ls_cmpsal=="")
	{
		alert("Debe existir un numero de comprobante");
	}
	else
	{
	 li_totrow=f.totalfilas.value;
	 if (ls_estauto!=1)
	 {
		 if(ls_coduniadmcede=="")
		   {
			alert("Debe seleccionar la Unidad Cedente");
			}
		 else
		  {
		   window.open("sigesp_saf_pdt_loteactivo.php?operacion=bienes&ls_coduniadmcede="+ls_coduniadmcede+"&totrow="+li_totrow+"&opener=autorizacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=850,height=350,left=50,top=50,location=no,resizable=yes");
		  }
	  }
	  else
	  {
	   alert("Esta autorización esta procesada. No puede ser modificada.");
	  }
	}
}

function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{	
		f.operacion.value="PROCESAR";
		f.action="sigesp_saf_p_autorizacionsalida.php";
		f.submit();
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_reversar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{	
		f.operacion.value="REVERSAR";
		f.action="sigesp_saf_p_autorizacionsalida.php";
		f.submit();
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_delete_dt(li_row)
{
  f=document.form1;
  li_fila=f.totalfilas.value;
  ls_estauto=f.hidestauto.value;
  if (ls_estauto==1)
   {
    alert("Esta autorización esta procesada. No puede ser modificada.");
   }
  else
  {
	if(li_fila!=li_row)
	{
		if(confirm("¿Desea eliminar el Registro actual?"))
		{	
			f.filadelete.value=li_row;
			f.operacion.value="ELIMINARDETALLE"
			f.action="sigesp_saf_p_autorizacionsalida.php";
			f.submit();
		}
	}
	else
	{
	 alert("La autorización debe tener al menos un detalle");
	}
  }	
}
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>