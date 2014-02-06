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
	$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_p_actaprestamo.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_reporte=$io_fun_activo->uf_select_config("SAF","REPORTE","FORMATO_ACTAPRESTAMO","sigesp_saf_rpp_acta_prestamo.php","C");
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
		global $ls_cmpres,$ls_coduniadmcede,$ls_denuniadmcede,$ls_coduniadmrece,$ls_denuniadmrece,$ld_fecenacta;
	    global $li_totrows,$ls_status,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_codper,$ls_nomper;
		global $ls_codresced,$ls_nomresced,$ls_testced,$ls_codreserec,$ls_nomresrec,$ls_testrec,$ls_estpres ;
		
		$ls_cmpres="";
		$ls_coduniadmcede="";
		$ls_denuniadmcede="";
		$li_totrows=1;
		$ls_status="";
		$ls_coduniadmrece= "";  
		$ls_denuniadmrece="";
		$ls_codper="";   
		$ls_nomper="";
		$ls_codresced="";
		$ls_nomresced="";
		$ls_testced="";
		$ls_codreserec="";
		$ls_nomresrec="";
		$ls_testrec="";
		$ls_estpres ="";
		$ls_titletable="Activos";
		$li_widthtable=750;
		$ls_nametable="grid";
		$lo_title[1]="Activo";
		$lo_title[2]="Serial";
		$lo_title[3]="Descripción del Activo";
		$lo_title[4]="Monto Activo";
		$lo_title[5]="";	
		$ld_fecenacta= date("d/m/Y");
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
		$aa_object[$ai_totrows][2]="<input name=txtidact".$ai_totrows."  type=text id=txtidact".$ai_totrows."  class=sin-borde size=17 maxlength=15 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtdenact".$ai_totrows." type=text id=txtdenact".$ai_totrows." class=sin-borde size=45 readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtmonact".$ai_totrows." type=text id=txtmonact".$ai_totrows." class=sin-borde size=15 readonly>";
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
			//$ls_desmov= $_POST["txtdesmov".$li_i];
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
<title >Acta de Préstamo </title>
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
    <td class="toolbar" width="24">&nbsp;</td>
    <td class="toolbar" width="24">&nbsp;</td>
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
	$io_sql=new class_sql($con);
	$li_totrows = uf_obtenervalor("totalfilas",1);	
	
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();
		    $ls_cmpres= $io_keygen->uf_generar_numero_nuevo("SAF","saf_prestamo","cmppre","SAF",15,"","codemp",$ls_codemp);
			uf_agregarlineablanca($lo_object,$li_totrows);
		break;
		
	
		case "AGREGARDETALLE":
		    uf_limpiarvariables();
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$li_totrows=$li_totrows+1;
			$ls_cmpres=$_POST["txtcmpres"];
			$ls_coduniadmcede=$_POST["txtcoduniadm"];
			$ls_denuniadmcede=$_POST["txtdenuniadm"];
			$ls_codresced=$_POST["txtcodresced"];
			$ls_nomresced=$_POST["txtnomresced"];
			$ls_testced=$_POST["txtestced"];
			$ls_codreserec=$_POST["txtcodresrece"];
			$ls_nomresrec=$_POST["txtnomresrec"];
//			$ls_testrec=$_POST["txtestrec"];
			$ls_coduniadmrece=$_POST["txtcoduni2"];
			$ls_denuniadmrece=$_POST["txtdenuni2"];
			$ld_fecenacta=$_POST["txtfecenacta"];
			//$ls_descmp=$_POST["txtdescmp"];
			$ls_codper=$_POST["txtcodper"];   
			$ls_nomper=$_POST["txtnomper"];
			
			for ($li_i=1;$li_i<$li_totrows;$li_i++)
			    {
				  $ls_codact = $_POST["txtcodact".$li_i];
				  $ls_denact = $_POST["txtdenact".$li_i];
				  $ls_idact  = $_POST["txtidact".$li_i];
				  $li_monact = $_POST["txtmonact".$li_i];
				  
				  $lo_object[$li_i][1]="<input name=txtcodact".$li_i." type=text id=txtcodact".$li_i." class=sin-borde size=17 maxlength=15  value='".$ls_codact."' readonly>";
				  $lo_object[$li_i][2]="<input name=txtidact".$li_i."  type=text id=txtidact".$li_i."  class=sin-borde size=17 maxlength=15 value='". $ls_idact ."' readonly>";
			      $lo_object[$li_i][3]="<input name=txtdenact".$li_i." type=text id=txtdenact".$li_i." class=sin-borde size=52 value='".$ls_denact."' readonly>";
				  $lo_object[$li_i][4]="<input name=txtmonact".$li_i." type=text id=txtmonact".$li_i." class=sin-borde size=15 value='".$li_monact."' readonly style=text-align:right>";
				  $lo_object[$li_i][5]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
			    }	
			uf_agregarlineablanca($lo_object,$li_totrows);

		break;
		
		case "GUARDAR":
			uf_limpiarvariables();
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$ls_codusureg=$_SESSION["la_logusr"];
			$ls_cmpres=$_POST["txtcmpres"];
			$ls_coduniadmcede=$_POST["txtcoduniadm"];
			$ls_denuniadmcede=$_POST["txtdenuniadm"];
			$ls_codresced=$_POST["txtcodresced"];
			$ls_nomresced=$_POST["txtnomresced"];
			$ls_testced=$_POST["txtestced"];
			$ls_codreserec=$_POST["txtcodresrece"];
			$ls_nomresrec=$_POST["txtnomresrec"];
			//$ls_testrec=$_POST["txtestrec"];
			$ls_coduniadmrece=$_POST["txtcoduni2"];
			$ls_denuniadmrece=$_POST["txtdenuni2"];
			$ld_fecenacta=$_POST["txtfecenacta"];
			//$ls_descmp=$_POST["txtdescmp"];
			$ls_codper=$_POST["txtcodper"];   
			$ls_nomper=$_POST["txtnomper"];
	        $ls_estpres="0";
			if( ($ls_coduniadmcede=="")||($ls_codresced=="")||($ls_codreserec=="")||($ls_coduniadmrece=="")||($ls_codper==""))
			{
				$io_msg->message("Debe compeltar los campos");
			}
			else
			{
			  $ld_fecenacta=$io_fun->uf_convertirdatetobd($ld_fecenacta);
			  $lb_existe=$io_saf->uf_saf_select_prestamo($ls_codemp,$ls_cmpres,$ld_fecenacta,$ls_coduniadmcede,$ls_coduniadmrece);
			  if($lb_existe) 
			   {
					if ($ls_estpres=="0")
					{
					   for($li_i=1;$li_i<=$li_totrows;$li_i++)
						{
							$ls_codact= $_POST["txtcodact".$li_i];  
							$ls_denact= $_POST["txtdenact".$li_i];   
							$ls_idact=  $_POST["txtidact".$li_i];
							//$ls_desmov= $_POST["txtdesmov".$li_i];
							$li_monact= $_POST["txtmonact".$li_i];
							$lb_valido=$io_saf->uf_saf_update_prestamo($ls_codemp,$ls_cmpres,$ld_fecenacta,$ls_coduniadmcede,
																	   $ls_coduniadmrece,$ls_codresced,$ls_codreserec,$ls_codper,
																	   $ls_estpres,$la_seguridad);
						 
						}
					    if ($lb_valido)
						{
							$ls_estactpre="1";
							$lb_valido=$io_saf->uf_saf_update_saf_dta($ls_codemp,$ls_codact,$ls_coduniadmcede,$ls_idact,
																		$ls_estactpre,$ld_fecenacta,$ls_coduniadmrece,$la_seguridad);
						}
						if($lb_valido)
						{
							$io_msg->message("El registro fue actualizado con exito");
							uf_limpiarvariables();
						}	
						else
						{
							$io_msg->message("El registro no pudo ser actualizado");
							uf_limpiarvariables();
						}						
					} // fin del if $ls_estpres
			    }
			   else
				{
					$ld_fecenacta=$io_fun->uf_convertirdatetobd($ld_fecenacta);
					$io_sql->begin_transaction();
					$lb_valido=$io_saf->uf_saf_insertar_prestamo($ls_codemp,$ls_cmpres,$ld_fecenacta,$ls_coduniadmcede,
																 $ls_coduniadmrece,$ls_codreserec,$ls_codreserec,$ls_codper,$ls_estpres,$la_seguridad);
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
							$ld_fecenacta=$io_fun->uf_convertirdatetobd($ld_fecenacta);
							$lb_valido=$io_saf->uf_saf_insert_dt_prestamo($ls_codemp,$ls_cmpres,$ld_fecenacta,$ls_coduniadmcede,
																		  $ls_coduniadmrece,$ls_codact,$ls_idact,$la_seguridad);
							if ($lb_valido)
							{
							  $ls_estactpre="1";
							  $lb_valido=$io_saf->uf_saf_update_saf_dta($ls_codemp,$ls_codact,$ls_coduniadmcede,$ls_idact,
							                                            $ls_estactpre,$ld_fecenacta,$ls_coduniadmrece,$la_seguridad);
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
			$ls_cmpres=$_POST["txtcmpres"];
	        $ls_coduniadmcede=$_POST["txtcoduniadm"];
			$ls_coduniadmrece=$_POST["txtcoduni2"];
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp++;			
					$ls_codact= $_POST["txtcodact".$li_i];
					$ls_denact= $_POST["txtdenact".$li_i];
					$ls_idact=  $_POST["txtidact".$li_i];
					//$ls_desmov= $_POST["txtdesmov".$li_i];
					$li_monact= $_POST["txtmonact".$li_i];
					
					$lo_object[$li_temp][1]="<input name=txtcodact".$li_temp." type=text id=txtcodact".$li_temp." class=sin-borde size=17 maxlength=15  value='".$ls_codact."' readonly>";
					$lo_object[$li_temp][2]="<input name=txtidact".$li_temp."  type=text   id=txtidact".$li_temp."  class=sin-borde size=17 maxlength=15 value='". $ls_idact ."' readonly>";
			        $lo_object[$li_temp][3]="<input name=txtdenact".$li_temp." type=text   id=txtdenact".$li_temp." class=sin-borde size=52 value='".$ls_denact."' readonly>";
					$lo_object[$li_temp][4]="<input name=txtmonact".$li_temp." type=text   id=txtmonact".$li_temp." class=sin-borde size=15 value='". $li_monact ."' readonly style=text-align:right>";
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
			$ls_cmpres		  = $_POST["txtcmpres"];
			$ls_coduniadmcede = $_POST["txtcoduniadm"];
			$ls_denuniadmcede = $_POST["txtdenuniadm"];
			$ls_codresced	  = $_POST["txtcodresced"];
			$ls_nomresced	  = $_POST["txtnomresced"];
			$ls_testced		  = $_POST["txtestced"];
			$ls_codreserec	  = $_POST["txtcodresrece"];
			$ls_nomresrec	  = $_POST["txtnomresrec"];
			$ls_testrec		  = $_POST["txtestres"];
			$ls_coduniadmrece = $_POST["txtcoduni2"];
			$ls_denuniadmrece = $_POST["txtdenuni2"];
			$ld_fecenacta	  = $_POST["txtfecenacta"]; 
			$ls_codper		  = $_POST["txtcodper"];   
			$ls_nomper		  = $_POST["txtnomper"];
			$ls_estpres		  =	$_POST["hidestpres"];
			$li_montot="";

			$lb_valido=$io_saf->uf_saf_load_detalle_prestamo($ls_codemp,$ls_cmpres,$ld_fecenacta,$ls_coduniadmcede,
					                             $ls_coduniadmrece,$li_totrows,$lo_object);
			
		break;
		
		case "PROCESAR":
		    $ls_estpres="1";
			$ls_cmpres=$_POST["txtcmpres"];
			$ld_fecenacta=$_POST["txtfecenacta"]; 
			$ls_coduniadmcede=$_POST["txtcoduniadm"];
			$ls_coduniadmrece=$_POST["txtcoduni2"];
            $ld_fecenacta=$io_fun->uf_convertirdatetobd($ld_fecenacta);
			$io_sql->begin_transaction();
			$lb_valido=$io_saf->uf_saf_update_procesarprestamo($ls_codemp,$ls_cmpres,$ls_coduniadmcede,
				                                               $ls_coduniadmrece,$ld_fecenacta,$ls_estpres,$la_seguridad);
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
			$ls_cmpres=$_POST["txtcmpres"];
			$ls_coduniadmcede=$_POST["txtcoduniadm"];
			$ls_coduniadmrece=$_POST["txtcoduni2"];
			$ld_fecenacta=$_POST["txtfecenacta"]; 
            $ld_fecenacta=$io_fun->uf_convertirdatetobd($ld_fecenacta);
			$ls_estpres=0;
			$io_sql->begin_transaction();
			$lb_valido=$io_saf->uf_saf_update_procesarprestamo($ls_codemp,$ls_cmpres,$ls_coduniadmcede,
				                                               $ls_coduniadmrece,$ld_fecenacta,$ls_estpres,$la_seguridad);
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
    <td colspan="3" class="titulo-ventana">Acta de Pr&eacute;stamos </td>
  </tr>
  <tr class="formato-blanco">
    <td width="124" height="19">&nbsp;</td>
    <td width="376"><div align="right">Fecha</div></td>
    <td width="82"><input name="txtfecenacta" type="text" id="txtfecenacta" style="text-align:center " value="<?php print $ld_fecenacta; ?>" size="13" maxlength="10" datepicker="true" onKeyPress="ue_separadores(this,'/',patron,true);"></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Comprobante</div></td>
    <td height="22" colspan="2">        <input name="txtcmpres" type="text" id="txtcmpres" value="<?php print $ls_cmpres; ?>" maxlength="15" onBlur="javascript: ue_rellenarcampo(this,'15')" style="text-align:center "></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Unidad F&iacute;sica Cedente </div></td>
    <td height="22" colspan="2"><input name="txtcoduniadm" type="text" id="txtcoduniadm" style="text-align:center " value="<?php print $ls_coduniadmcede; ?>" size="13" maxlength="10" >
      <a href="javascript:ue_buscarunidad();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadmcede; ?>" size="50" readonly>
      <input name="hidestpres" type="hidden" id="hidestpres" value="<?php print $ls_estpres ?>">
	  <input name="btnprocesar" type="button" class="boton" value="  Procesar" <?php if($ls_estpres==0){ print "onClick='javascript: ue_procesar();'";}?>>
      </td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Responsable de la Unidad Cedente </div></td>
    <td height="22" colspan="2"><input name="txtcodresced" type="text" id="txtcodresced" value="<?php print $ls_codresced;?>" size="13" maxlength="10" style="text-align:center" readonly>
      <a href="javascript: ue_buscarresponsablecedente();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
      <input name="txtnomresced" type="text" class="sin-borde" id="txtnomresced" value="<?php print $ls_nomresced;?>" size="50" maxlength="120" readonly>
      <input name="txtestced" type="hidden" class="sin-borde" id="txtestced" value="<?php print $ls_testced; ?>" size="20" maxlength="20" readonly> </td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Unidad F&iacute;sica Receptora</div></td>
    <td height="22" colspan="2"><input name="txtcoduni2" type="text" id="txtcoduni2" style="text-align:center " value="<?php print $ls_coduniadmrece; ?>" size="13" maxlength="10" >
      <a href="javascript:ue_buscarunidadrecep();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtdenuni2" type="text" class="sin-borde" id="txtdenuni2" value="<?php print $ls_denuniadmrece; ?>" size="50" readonly>
      <input name="btnreversar" type="button" class="boton" value="  Reversar"  <?php if($ls_estpres==1){ print "onClick='javascript: ue_reversar();'";}?> ></td></tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Responsable de la Unidad Receptora </div></td>
    <td height="22" colspan="2"><input name="txtcodresrece" type="text" id="txtcodresrece" value="<?php print $ls_codreserec; ?>" size="13" maxlength="10" readonly style="text-align:center">
      <a href="javascript: ue_buscarresponsablereceptora();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> 
      <input name="txtnomresrec" type="text" class="sin-borde" id="txtnomresrec" value="<?php print $ls_nomresrec; ?>" size="50" maxlength="120" readonly>
      <input name="txtestres" type="hidden" class="sin-borde" id="txtestres" value="<?php print $ls_testrec; ?>" size="20" maxlength="20" readonly></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Testigo</div></td>
    <td height="22" colspan="2"><input name="txtcodper" type="text" id="txtcodper" value="<?php print $ls_codper;?>" size="13" maxlength="10" readonly style="text-align:center">
      <a href="javascript: ue_buscarpersonal();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> &nbsp;
      <input name="txtnomper" type="text" class="sin-borde" id="txtnomper" value="<?php print $ls_nomper;?>" size="50" maxlength="120" readonly>
      <input name="txtestper" type="hidden" id="txtestper" value="<?php echo $ls_estper ?>"></td>
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
      <td height="13" colspan="3">&nbsp;</td>
      </tr>
    <tr class="formato-blanco">
    <td height="22">&nbsp;</td>
    <td height="22" colspan="2">&nbsp;</td>
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
  window.open("sigesp_saf_cat_prestamo.php?","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscarunidad()
{
 f=document.form1;
 ls_estpres=f.hidestpres.value;
  if (ls_estpres==1)
   {
    alert("Esta acta está procesada. No puede ser modificada.");
   }
  else
  {
  window.open("sigesp_saf_cat_unidadejecutora.php?destino=cedente","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
  }
}

function ue_buscarunidadrecep()
{
 f=document.form1;
 ls_coduniadmcede=f.txtcoduniadm.value;
 ls_codresced=f.txtcodresced.value;
 ls_estpres=f.hidestpres.value;
 if ((ls_coduniadmcede=="")||(ls_codresced==""))
 {
  alert ("Debe seleccionar la Unidad Cedente y su responsable");
 } 
 else
 {
  if (ls_estpres==1)
   {
    alert("Esta acta está procesada. No puede ser modificada.");
   }
  else
  {
  window.open("sigesp_saf_cat_unidadejecutora.php?destino=receptora&filtro=filtro&ls_coduniadmcede="+ls_coduniadmcede+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
  }
 }
}

function ue_buscarpersonal()
{
	f=document.form1;
	ls_coduniadmcede=f.txtcoduniadm.value;
	ls_codresced=f.txtcodresced.value;
	ls_codreserec=f.txtcodresrece.value;
	ls_estpres=f.hidestpres.value;
	if  ((ls_coduniadmcede=="")||(ls_codresced==""))
	{
	 alert ("Debe seleccionar todos los campos anteriores");
	} 
	else
	{
	  if (ls_estpres==1)
	  {
	  alert("Esta acta está procesada. No puede ser modificada.");
	  }
	  else
	  {
	   window.open("sigesp_snorh_cat_personal.php?tipo=asignacion&ls_codresced="+ls_codresced+"&buscartest=buscartest&ls_codreserec="+ls_codreserec+"&buscar=1"+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
      }
	}
}
function ue_buscarresponsablecedente()
{
	f=document.form1;
	ls_coduniadmcede=f.txtcoduniadm.value;
	ls_estpres=f.hidestpres.value;
	if (ls_coduniadmcede=="")
	{
	 alert ("Debe seleccionar la Unidad Cedente");
	}
	else
	{
	  if (ls_estpres==1)
	  {
	  alert("Esta acta está procesada. No puede ser modificada.");
	  } 
	 else
	  {
	  window.open("sigesp_snorh_cat_personal.php?tipo=asignacion2&buscar=0","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	  }
	 }
}
function ue_buscarresponsablereceptora()
{
	f=document.form1;
	ls_coduniadmcede=f.txtcoduniadm.value;
	ls_codresced=f.txtcodresced.value; 
	ls_codreserec=f.txtcodresrece.value;
	ls_coduniadmrece=f.txtcoduni2.value;
	ls_estpres=f.hidestpres.value;
	if  ((ls_coduniadmcede=="")||(ls_codresced=="")||(ls_coduniadmrece=="")||(ls_coduniadmrece==""))
	{
	 alert ("Debe seleccionar todos los campos anteriores");
	} 
	else
	{
	  if (ls_estpres==1)
	  {
	  alert("Esta acta está procesada. No puede ser modificada.");
	  }
	 else
	 {
	 window.open("sigesp_snorh_cat_personal.php?tipo=asignacion3&buscaresced=buscaresced&ls_codresced="+ls_codresced+"&buscar=0","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
     }    
	}
}
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="sigesp_saf_p_actaprestamo.php";
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
	ls_estpres=f.hidestpres.value;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	ls_cmpres=f.txtcmpres.value;
	ls_coduniadmcede=f.txtcoduniadm.value;
	ls_denuniadmcede=f.txtdenuniadm.value;
	ls_codresced=f.txtcodresced.value;
	ls_nomresced=f.txtnomresced.value;
	ls_codreserec=f.txtcodresrece.value;
	ls_nomresrec=f.txtnomresrec.value;
	ls_coduniadmrece=f.txtcoduni2.value;
	ls_denuniadmrece=f.txtdenuni2.value;
	ld_fecenacta=f.txtfecenacta.value;
	ls_codper=f.txtcodper.value;   
	ls_nomper=f.txtnomper.value;
	if (ls_estpres!=1)
	   {
		 li_totrows = f.totalfilas.value
		 ls_codact  = eval("f.txtcodact1.value")
		 if (li_totrows>=1 && ls_codact!='')
		    {
			  f.operacion.value="GUARDAR";
			  f.action="sigesp_saf_p_actaprestamo.php";
			  f.submit();			
			}
	     else
		    {
			  alert("El acta debe tener al menos un detalle !!!");
			}
	   }
	else
	   {
	     alert("Este documento esta procesdo no puede ser modificado");
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
	ls_cmpres=f.txtcmpres.value;
	ls_coduniadmcede=f.txtcoduniadm.value;
	ls_denuniadmcede=f.txtdenuniadm.value;
	ls_codresced=f.txtcodresced.value;
	ls_nomresced=f.txtnomresced.value;
	ls_testced=f.txtestced.value;
	ls_codreserec=f.txtcodresrece.value;
	ls_nomresrec=f.txtnomresrec.value;
	ls_testrec=f.txtestres.value;
	ls_coduniadmrece=f.txtcoduni2.value;
	ls_denuniadmrece=f.txtdenuni2.value;
	ld_fecenacta=f.txtfecenacta.value;
	ls_codper=f.txtcodper.value;   
	ls_nomper=f.txtnomper.value;
	if(li_imprimir==1)
	{
		 pagina="reportes/"+ls_reporte+"?ls_cmpres="+ls_cmpres+"&ls_coduniadmcede="+ls_coduniadmcede+"&ls_coduniadmrece="+ls_coduniadmrece+"";
		 pagina=pagina+"&ld_fecenacta="+ld_fecenacta+"&ls_codper="+ls_codper+"&ls_nomper="+ls_nomper+"&ls_denuniadmcede="+ls_denuniadmcede+"";
		 pagina=pagina+"&ls_denuniadmrece="+ls_denuniadmrece+"&ls_codresced="+ls_codresced+"&ls_nomresced="+ls_nomresced+"";
		 pagina=pagina+"&ls_codreserec="+ls_codreserec+"&ls_nomresrec="+ls_nomresrec+"";
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
	ls_cmpres=f.txtcmpres.value;
	ls_coduniadmcede=f.txtcoduniadm.value;
	ls_denuniadmcede=f.txtdenuniadm.value;
	ls_codresced=f.txtcodresced.value;
	ls_nomresced=f.txtnomresced.value;
	ls_codreserec=f.txtcodresrece.value;
	ls_nomresrec=f.txtnomresrec.value;
	ls_coduniadmrece=f.txtcoduni2.value; 
	ls_denuniadmrece=f.txtdenuni2.value;
	ls_codper=f.txtcodper.value;
	ls_estpres=f.hidestpres.value; 
	if(ls_cmpres=="")
	{
		alert("Debe existir un numero de comprobante");
	}
	else
	{
	  li_totrow=f.totalfilas.value;
	 if((ls_coduniadmcede=="")||(ls_codresced=="")||(ls_codreserec=="")||(ls_coduniadmrece=="")||(ls_codper==""))
	   {
	    alert("Debe seleccionar todos los campos");
	    }
	 else
   	  {
	    if (ls_estpres==1)
	    {
	     alert("Esta acta está procesada. No puede ser modificada.");
	    }
	    else
	    {
 	     window.open("sigesp_saf_pdt_loteactivo.php?operacion=bienes&ls_coduniadmcede="+ls_coduniadmcede+"&totrow="+li_totrow+"&opener=acta","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=850,height=350,left=50,top=50,location=no,resizable=yes");
	    }
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
		f.action="sigesp_saf_p_actaprestamo.php";
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
		f.action="sigesp_saf_p_actaprestamo.php";
		f.submit();
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_delete_dt(li_row)
{
  f = document.form1;
  li_estpres = f.hidestpres.value;
  if (li_estpres==0)
     {
	   if (confirm("¿Desea eliminar el Registro actual?"))
		  {	
		    f.filadelete.value=li_row;
		    f.operacion.value="ELIMINARDETALLE"
		    f.action="sigesp_saf_p_actaprestamo.php";
		    f.submit();
		  }	 
	 }
  else
     {
	   alert("Este documento esta procesdo no puede ser modificado !!!");
	 }
}
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>