<?php
session_start();
$dat=$_SESSION["la_empresa"];
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definición de Plan de Cuentas de Gasto.</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_imprimir();"><img src="../../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" class="sin-borde"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div>      
    <div align="center"></div>      <div align="center"></div>      <div align="center"></div></td>
  </tr>
</table>
<?php
	require_once("class_folder/sigesp_scg_c_casamientopresupuesto.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_fecha.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/class_sigesp_int.php");

	$io_msg     = new class_mensajes();
	$scg_cta = new sigesp_scg_c_casamientopresupuesto();
	
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
	$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
	$ls_logusr="";
	}
	$ls_sistema     = "CFG";
	$ls_ventanas    = "sigesp_spg_d_planctas.php";
	$la_security[1] = $ls_empresa;
	$la_security[2] = $ls_sistema;
	$la_security[3] = $ls_logusr;
	$la_security[4] = $ls_ventanas;
    $li_estmodest   = $arre["estmodest"];
	$ls_nomestpro4  = $dat["nomestpro4"];
	$ls_nomestpro5  = $dat["nomestpro5"];
	if ($li_estmodest=='1')
	   {
	    	$ls_loncodestpro4=25;
			$ls_loncodestpro5=25;			
	   }	

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos            = $_POST["permisos"];
			$la_accesos["leer"]     = $_POST["leer"];
			$la_accesos["incluir"]  = $_POST["incluir"];
			$la_accesos["cambiar"]  = $_POST["cambiar"];
			$la_accesos["eliminar"] = $_POST["eliminar"];
			$la_accesos["imprimir"] = $_POST["imprimir"];
			$la_accesos["anular"]   = $_POST["anular"];
			$la_accesos["ejecutar"] = $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]     = ""; 
		$la_accesos["incluir"]  = "";
		$la_accesos["cambiar"]  = "";
		$la_accesos["eliminar"] = "";
		$la_accesos["imprimir"] = "";
		$la_accesos["anular"]   = ""; 
		$la_accesos["ejecutar"] = "";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if  (array_key_exists("status",$_POST))
	{
  	  $ls_estatus=$_POST["status"];
	}
else
	{
	  $ls_estatus="NUEVO";	  
	}	
		
	$ls_formato    = trim($dat["formpre"]);
	$ls_formatoaux = str_replace( "-", "",$ls_formato);
	$li_size_cta   = strlen($ls_formatoaux);
	
	//Instancia de la clase de manejo de Grid dinamico
	require_once("../../shared/class_folder/grid_param.php");	
	$io_grid=new grid_param();
	
	
	if (array_key_exists("operacion",$_POST))
	   {
		 $ls_operacion  = $_POST["operacion"];
		 $ls_maestro    = $_POST["hidmaestro"];
		 $ls_fila    = $_POST["fila"];
		 $ls_filauso= $_POST["filauso"];
	   }
	else
	   {
		 $ls_operacion  = "NUEVO";
		 $ls_estcla     = "";
	     $ls_maestro    = "P";
		 $ls_scgctaint  = ""; 
		 $ls_filauso=49;
		 $ls_fila="";
	   }
	 	 //Titulos de la grid de Cuentas Presupuestarias.
		$title[1]="Cuenta Presupuestaria";   $title[2]="Denominación";     $title[3]="Cuenta Contable";		$title[4]="Edición";
  		//Nombr del grid
		$grid1="grid";	
		//Total de filas iniciales del grid
		$total=50;
	/////////////////////// N U E V O///////////////////////////////////////////////////////////////////
	if ($ls_operacion=="NUEVO")
	   {
		 $ls_scgctaint  = "";
		 for ($i=1;$i<=$total;$i++)
		     {
			   //Object que contiene los objetos y valores	iniciales del grid.	
			   $object[$i][1]="<input type=text name=txtcuentaspg".$i." value='' id=txtcuentaspg".$i."  class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=return keyRestrict(event,'1234567890'); onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$i."'); readonly>";		
			   $object[$i][2]="<input type=text name=txtdencuenta".$i." value=''  class=sin-borde style=text-align:left size=60 maxlength=254 readonly>";
			   $object[$i][3]="<input type=text name=txtcuentascg".$i." value=''  class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$i.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contables ' width=15 height=15 border=0></a>";
			   $object[$i][4] ="<a href=javascript:uf_delete_dt('".$i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0 readonly></a>";
		     }
		 $lastrow      = 50;
		 $ls_filauso=49;
		 $ls_fila="";
	   } 
	
	if ($ls_operacion=="BLANQUEAR")
	   {
		 for ($i=1;$i<=$total;$i++)
		     {
			   //Object que contiene los objetos y valores	iniciales del grid.	
			   $object[$i][1]="<input type=text name=txtcuentaspg".$i." value='' id=txtcuentaspg".$i."  class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=return keyRestrict(event,'1234567890'); onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$i."'); readonly>";		
			   $object[$i][2]="<input type=text name=txtdencuenta".$i." value=''  class=sin-borde style=text-align:left size=60 maxlength=254 readonly>";
			   $object[$i][3]="<input type=text name=txtcuentascg".$i." value=''  class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$i.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
			   $object[$i][4] ="<a href=javascript:uf_delete_dt('".$i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
		     }
		 $lastrow      = 50;
		 $ls_filauso=49;
		 $ls_fila="";
	   } 
	
	/////////////////////// G U A R D A R///////////////////////////////////////////////////////////////////
	if ($ls_operacion=="GUARDAR")
	   {
	     $total      = $_POST["total"];
		 $lastrow    = $_POST["lastrow"];
		 $ls_filauso = $_POST["filauso"];
		 $ls_fila    = $_POST["fila"];
		 $li_error   = $li_save = 0;
		 for ($i=1;$i<=$ls_fila;$i++)
	 	     {
			   $ls_cuentaspg    = trim($_POST["txtcuentaspg".$i]);
			   $ls_dencuentaspg = $_POST["txtdencuenta".$i];
		  	   $ls_cuentascg    = trim($_POST["txtcuentascg".$i]);
			  /* $lb_valido=$scg_cta->uf_buscar_cuenta($ls_empresa,$ls_cuentaspg,$ls_cuentascg); 
               if(!$lb_valido)
			   {*/
				   if (($ls_cuentaspg!="")&&($ls_dencuentaspg!="")&&($ls_cuentascg!=""))
					  {
						$lb_valido=$scg_cta->uf_procesar_cuentas($ls_cuentaspg,$ls_cuentascg,$la_security);
						 if (!$lb_valido)//No pudo procesar la cuenta
							{
							  $li_error++;
							}
						 else//Generó correctamente la cuenta
							{
							 $li_save++;
							} 
					  }
				   if (($ls_cuentaspg!="")&&($ls_cuentascg==""))
					  {
						$io_msg->message("Cuenta Presupuestaria necesita el casamiento contable !!!");
					  }
							
				   //Object que contiene los objetos y valores
				   $object[$i][1]="<input type=text name=txtcuentaspg".$i." value='".$ls_cuentaspg."' class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=\"return keyRestrict(event,'1234567890');\" onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$total."'); readonly>";		
				   $object[$i][2]="<input type=text name=txtdencuenta".$i." value='".$ls_dencuentaspg."' class=sin-borde style=text-align:left size=60 maxlength=254 readonly>";
				   $object[$i][3]="<input type=text name=txtcuentascg".$i." value='".$ls_cuentascg."' class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$i.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
				   $object[$i][4] ="<a href=javascript:uf_delete_dt('".$i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
		      /* }
			   else
			   {
			      $io_msg->message("La cuenta ya tiene un casamiento contable !!!");
				   //Object que contiene los objetos y valores
				   $object[$i][1]="<input type=text name=txtcuentaspg".$i." value='' class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=\"return keyRestrict(event,'1234567890');\" onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$total."'); readonly>";		
				   $object[$i][2]="<input type=text name=txtdencuenta".$i." value='' class=sin-borde style=text-align:left size=60 maxlength=254 readonly>";
				   $object[$i][3]="<input type=text name=txtcuentascg".$i." value='' class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$i.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
				   $object[$i][4] ="<a href=javascript:uf_delete_dt('".$i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
                   $lastrow      = 50;
				   $ls_filauso=49;
				   $ls_fila="";
			   }*/
		    }// fin del for
		 $lastrow      = 50;
	     $ls_filauso=49;
	     $ls_fila="";
		 $ls_filaini=$ls_fila+1; 
		 for ($i=$ls_filaini;$i<=$lastrow;$i++)
	 	   { 
		       $object[$i][1]="<input type=text name=txtcuentaspg".$i." value='' class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=\"return keyRestrict(event,'1234567890');\" onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$total."'); readonly>";		
			   $object[$i][2]="<input type=text name=txtdencuenta".$i." value='' class=sin-borde style=text-align:left size=60 maxlength=254 readonly>";
			   $object[$i][3]="<input type=text name=txtcuentascg".$i." value='' class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$i.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
			   $object[$i][4] ="<a href=javascript:uf_delete_dt('".$i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
		   
		   }
		 $io_msg->message("$li_save Cuenta(s) guardada(s) ,$li_error Cuenta(s) con error");
	   }
	
	/////////////////////// D E L E T E/////////////////////////////////////////////////////////////////
	//Elimina la fila presionada
	if ($ls_operacion=="DELETE")
	   {
		 $li_fila_del   = $_POST["filadelete"];
		 $total         = $_POST["total"];
		 $lastrow       = $_POST["lastrow"];
		 $ls_filauso= $_POST["filauso"];
		 $lastrow       = $lastrow-1;

		 $li_error      = 0;
		 $li_save       = 0;
		 $li_temp       = 0;
		 for ($i=1;$i<=$total;$i++)
		     {
			   $ls_cuentaspg    = trim($_POST["txtcuentaspg".$i]);
			   $ls_dencuentaspg = $_POST["txtdencuenta".$i];
			   $ls_cuentascg    = trim($_POST["txtcuentascg".$i]);
			   if ($i!=$li_fila_del)
			      {
				    $li_temp++;
					$object[$li_temp][1]="<input type=text name=txtcuentaspg".$li_temp." value='".$ls_cuentaspg."' class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=\"return keyRestrict(event,'1234567890');\" onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$total."'); readonly>";		
					$object[$li_temp][2]="<input type=text name=txtdencuenta".$li_temp." value='".$ls_dencuentaspg."' class=sin-borde style=text-align:left size=60 maxlength=254 readonly>";
					$object[$li_temp][3]="<input type=text name=txtcuentascg".$li_temp." value='".$ls_cuentascg."' class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$li_temp.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
					$object[$li_temp][4] ="<a href=javascript:uf_delete_dt('".$li_temp."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
			      }
			   else
			      {
				    $li_fila_del=0;
				    $lb_valido=$scg_cta->uf_procesar_delete_cuenta($ls_cuentaspg,$ls_cuentascg,$la_security);
				    if (!$lb_valido)
					   {
					     $li_no_existen++;
					   	 $ls_cuentaspg    = "";
					   	 $ls_dencuentaspg = "";
					     $ls_cuentascg    = "";
					   }
				    elseif(!$lb_valido)
					   {
						 $li_error++;	
						 $li_temp++;
						 $object[$li_temp][1]="<input type=text name=txtcuentaspg".$li_temp." value='".$ls_cuentaspg."' class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=\"return keyRestrict(event,'1234567890');\" onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$total."'); readonly>";		
						 $object[$li_temp][2]="<input type=text name=txtdencuenta".$li_temp." value='".$ls_dencuentaspg."' class=sin-borde style=text-align:left size=60 maxlength=254 readonly>";
						 $object[$li_temp][3]="<input type=text name=txtcuentascg".$li_temp." value='".$ls_cuentascg."' class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$li_temp.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
						 $object[$li_temp][4] ="<a href=javascript:uf_delete_dt('".$li_temp."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";				
					   }
					elseif($lb_valido)
					   {
						 $li_save++;
						 $ls_cuentaspg   = "";
						 $ls_dencuentaspg= "";
						 $ls_cuentascg   = "";
					   }
			      }
		     }
		 $object[$total][1]="<input type=text name=txtcuentaspg".$total." value='".$ls_cuentaspg."' class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=\"return keyRestrict(event,'1234567890');\" onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$total."'); readonly>";		
		 $object[$total][2]="<input type=text name=txtdencuenta".$total." value='".$ls_dencuentaspg."' class=sin-borde style=text-align:left size=60 maxlength=254 readonly>";
		 $object[$total][3]="<input type=text name=txtcuentascg".$total." value='".$ls_cuentascg."' class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$total.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
		 $object[$total][4] ="<a href=javascript:uf_delete_dt('".$total."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
		 $io_msg->message("$li_save Cuenta(s) Eliminada(s) ");
	}

	/////////////////////// D E L E T E A L L ///////////////////////////////////////////////////////////////////	
	// Elimina todos las cuentas del detalle
	if ($ls_operacion=="DELETEALL")
	   {
	     $total         = $_POST["total"];
	 	 $lastrow       = $_POST["lastrow"];
	 	 		
		 $li_error      = 0;
	 	 $li_save       = 0;
		 $li_no_existen = 0;
	 	 for ($i=1;$i<=$total;$i++)
			 {
			   $ls_cuentaspg    = trim($_POST["txtcuentaspg".$i]);
			   $ls_dencuentaspg = $_POST["txtdencuenta".$i];
			   $ls_cuentascg    = trim($_POST["txtcuentascg".$i]);
				
				if (($ls_cuentaspg!="")&&($ls_dencuentaspg!=""))			
				   {
				     $lb_valido=$scg_cta->uf_procesar_delete_cuenta($ls_cuentaspg,$ls_cuentascg,$la_security);
					if(!$lb_valido)
					    {
						  $li_error++;
					    }
					 else
						{
						  $li_save++;
						}
				    }		
				$object[$i][1]="<input type=text name=txtcuentaspg".$i." value='' class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=\"return keyRestrict(event,'1234567890');\"  onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$total."');>";		
				$object[$i][2]="<input type=text name=txtdencuenta".$i." value='' class=sin-borde style=text-align:left size=60 maxlength=254>";
				$object[$i][3]="<input type=text name=txtcuentascg".$i." value='' class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$i.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contables ' width=15 height=15 border=0></a>";
				$object[$i][4] ="<a href=javascript:uf_delete_dt('".$i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
			    $lastrow      = 0;
		     }
		 $io_msg->message("$li_save Cuenta(s) Eliminada(s) ,$li_error Cuenta(s) con error ");
	   }
?>
<p>&nbsp;</p>
<div align="center">
  <table width="718" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="716" height="221" valign="top">
		<form name="form1" method="post" action="" id="sigesp_spg_d_planctas.php">
<?php 
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=permisos value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=permisos value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=permisos value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=permisos value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=permisos value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=permisos value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=permisos value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
		
          <p>&nbsp;</p>
          <table width="680" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="2">Casamiento Presupuestario </td>
              </tr>
              <tr class="formato-blanco">
                <td width="142" height="22">&nbsp;</td>
                <td width="536" height="22"><input name="status" type="hidden" id="status" value="<?php print $ls_estatus ?>">
                <input name="hidmaestro" type="hidden" id="hidmaestro" value="<?php print $ls_maestro ?>"></td>
              </tr>
 
            <tr class="formato-blanco">
              <td height="22" colspan="2">&nbsp;&nbsp;
              <div align="left"> &nbsp;<a href="javascript: uf_blanquear();"> <img src="../../shared/imagebank/tools20/nuevo.gif" alt="Aplicar a Todas" width="20" height="20" border="0">Blanquear</a> &nbsp;&nbsp;  <a href="javascript: buscarctacontable();"> <img src="../../shared/imagebank/mas.gif" width="9" height="17" border="0">Agregar Cuentas</a></div>              </td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" colspan="2"><p align="center">
                <?php $io_grid->makegrid($total,$title,$object,580,'Detalles Cuenta',$grid1);?>
                <input name="total" type="hidden" id="total" value="<?php print $total?>">
</p>              </td>
            </tr>
          </table>
          <p align="center">&nbsp;          </p>
            <p align="center">
              <input name="operacion"  type="text" id="operacion" >
              <input name="lastrow"    type="hidden" id="lastrow"    value="<?php print $lastrow;?>" >
              <input name="filadelete" type="hidden" id="filadelete">
              <input name="filauso" type="hidden" id="filauso" value="<?php print $ls_filauso;?>">
              <input name="fila" type="hidden" id="fila" value="<?php print $ls_fila;?>">
            </p>
		</form></td>
      </tr>
  </table>
</div>
</body>
<script language="javascript">
f = document.form1;
function ue_nuevo()
{
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
	   f.operacion.value ="NUEVO";
	   f.action="sigesp_scg_d_casamientopresupuesto.php";
	   f.submit();
	 }
  else
     {
 	   alert("No tiene permiso para realizar esta operación");
	 }
}

function ue_guardar()
{
li_incluir = f.incluir.value;
li_cambiar = f.cambiar.value;
lb_status  = f.status.value;
if (((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
   {
	  f.operacion.value ="GUARDAR";
	  f.action="sigesp_scg_d_casamientopresupuesto.php";
	  f.submit();
    }
 else
    {
 	  alert("No tiene permiso para realizar esta operación !!!");
	}	
}					

function ue_buscar()
{
    f=document.form1;
	li_leer=f.leer.value;
	fila=f.fila.value; 
	filauso=f.filauso.value;
	if(fila=="")
	{
	   filacat=0;
	} 
	else
	{
	  filacat=fila;
	}
	if (li_leer==1)
	   {
         window.open("sigesp_scg_cat_casamientopresupuestario.php?filacat="+filacat+"&filauso="+filauso+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
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
	  if (confirm("¿ Esta seguro de Eliminar todas las Cuentas ?"))
	     {
			    f.operacion.value ="DELETEALL";
			    f.action="sigesp_scg_d_casamientopresupuesto.php";
			    f.submit();

	     }
	}
  else
    {
      alert("No tiene permiso para realizar esta operación !!!");
	}
}

function cat_plan(fila)
{
    f=document.form1;
	ls_cuentaspg=eval("f.txtcuentaspg"+fila+".value;");
	ls_dencuenta=eval("f.txtdencuenta"+fila+".value;");
	if((ls_cuentaspg!="")&&(ls_dencuenta!=""))
	{
		window.open("sigesp_scg_cat_ctas.php?destino=destino&fila="+fila+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes,dependent=yes");
	}
	else
	{
		alert("Seleccione la cuenta presupuestaria");
	}
}

function ue_cerrar()
{
	f.action="sigespwindow_blank.php";
	f.submit();
}

function ue_imprimir()
{
    f=document.form1;
	li_imprimir=f.imprimir.value;
	if (li_imprimir==1)
	{
		pantalla    = "reportes/sigesp_spg_rpp_casamientos.php";
		window.open(pantalla,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
	}

}
function uf_blanquear()
{
	f.operacion.value="BLANQUEAR";
	f.action="sigesp_scg_d_casamientopresupuesto.php";
	f.submit();
}

function buscarctacontable()
{
	f=document.form1;
	f.operacion.value="CTACONT";
	filauso=f.filauso.value;
	window.open("sigesp_scg_cat_ctaspure.php?destino=destino&filauso="+filauso+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function uf_delete_dt(fila)
{
	f=document.form1;
	if(confirm("Esta seguro de eliminar la Cuenta ?"))
	{
		f.filadelete.value = fila;
	
		f.operacion.value="DELETE";
		f.action="sigesp_scg_d_casamientopresupuesto.php";
		f.submit();
	
	}
}

function uf_rellenar_cuenta(longitud,li_i)
{
		cadena_ceros="";
		f=document.form1;
		cadena=	eval("f.txtcuentaspg"+li_i+".value");
		lencad=cadena.length;
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena+cadena_ceros;
		eval("document.form1.txtcuentaspg"+li_i+".value="+cadena);
}
</script>
</html>