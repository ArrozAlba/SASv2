<?php
session_start();
$dat=$_SESSION["la_empresa"];
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }

$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
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
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" tilte="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div>      <div align="center"></div>      <div align="center"></div>      <div align="center"></div></td>
  </tr>
</table>
<?php
	require_once("class_folder/sigesp_spg_c_planctas.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_fecha.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/class_sigesp_int.php");

	$io_msg     = new class_mensajes();
	$sig_spgcta = new sigesp_spg_c_planctas();
	
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
		 $ls_estcla 	= $_POST["txtestcla"];
		 $ls_codestpro1 = $_POST["txtcodestpro1"];
		 $ls_codestpro2 = $_POST["txtcodestpro2"];
		 $ls_codestpro3 = $_POST["txtcodestpro3"];
		 $ls_denestpro1 = $_POST["txtdenestpro1"];
		 $ls_denestpro2 = $_POST["txtdenestpro2"];
		 $ls_denestpro3 = $_POST["txtdenestpro3"];
		 $ls_scgctaint  = $_POST["hidscgctaint"];//Cuenta Contable Intercompañia.

		 if ($li_estmodest=='2')
		    {
		      $ls_codestpro4 = $_POST["txtcodestpro4"];
		      $ls_codestpro5 = $_POST["txtcodestpro5"];
		      $ls_denestpro4 = $_POST["txtdenestpro4"];
		      $ls_denestpro5 = $_POST["txtdenestpro5"]; 
		    }
		 else
		    {		   
		      $ls_codestpro4 = str_pad('',25,0,0);
		      $ls_codestpro5 = str_pad('',25,0,0);
		      $ls_denestpro4 = "";
		      $ls_denestpro5 = ""; 
		    }
		 $ls_maestro    = $_POST["hidmaestro"];
	   }
	else
	   {
		 $ls_operacion  = "NUEVO";
		 $ls_codestpro1 = "";
		 $ls_codestpro2 = "";
		 $ls_codestpro3 = "";	
		 $ls_codestpro4 = "";
		 $ls_codestpro5 = "";	
		 $ls_denestpro1 = "";
		 $ls_denestpro2 = "";
		 $ls_denestpro3 = "";
		 $ls_denestpro4 = "";
		 $ls_denestpro5 = "";
		 $ls_estcla     = "";
	     $ls_maestro    = "P";
		 $ls_scgctaint  = ""; 
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
		 $ls_estcla     = "";
		 $ls_codestpro1 = "";
		 $ls_codestpro2 = "";
		 $ls_codestpro3 = "";	
		 $ls_codestpro4 = "";
		 $ls_codestpro5 = "";	
		 $ls_denestpro1 = "";
		 $ls_denestpro2 = "";
		 $ls_denestpro3 = "";
		 $ls_denestpro4 = "";
		 $ls_denestpro5 = "";
		 $ls_scgctaint  = "";
		 for ($i=1;$i<=$total;$i++)
		     {
			   //Object que contiene los objetos y valores	iniciales del grid.	
			   $object[$i][1]="<input type=text name=txtcuentaspg".$i." value='' id=txtcuentaspg".$i."  class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=return keyRestrict(event,'1234567890'); onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$i."');>";		
			   $object[$i][2]="<input type=text name=txtdencuenta".$i." value=''  class=sin-borde style=text-align:left size=60 maxlength=254>";
			   $object[$i][3]="<input type=text name=txtcuentascg".$i." value=''  class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$i.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
			   $object[$i][4] ="<a href=javascript:uf_delete_dt('".$i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
		     }
		 $lastrow      = 0;
	   } 
	
	if ($ls_operacion=="BLANQUEAR")
	   {
		 for ($i=1;$i<=$total;$i++)
		     {
			   //Object que contiene los objetos y valores	iniciales del grid.	
			   $object[$i][1]="<input type=text name=txtcuentaspg".$i." value='' id=txtcuentaspg".$i."  class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=return keyRestrict(event,'1234567890'); onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$i."');>";		
			   $object[$i][2]="<input type=text name=txtdencuenta".$i." value=''  class=sin-borde style=text-align:left size=60 maxlength=254>";
			   $object[$i][3]="<input type=text name=txtcuentascg".$i." value=''  class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$i.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
			   $object[$i][4] ="<a href=javascript:uf_delete_dt('".$i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
		     }
		 $lastrow = 0;
	   } 
	
	/////////////////////// G U A R D A R///////////////////////////////////////////////////////////////////
	if ($ls_operacion=="GUARDAR")
	   {
	     $total         = $_POST["total"];
		 $lastrow       = $_POST["lastrow"];
		 $ls_estcla     = $_POST["txtestcla"];
		 $ls_codestpro1 = $_POST["txtcodestpro1"];
		 $ls_codestpro2 = $_POST["txtcodestpro2"];
		 $ls_codestpro3 = $_POST["txtcodestpro3"];
		 
		 if ($li_estmodest=='2')
	        {
		      $ls_codestpro4 = $_POST["txtcodestpro4"];
		      $ls_codestpro5 = $_POST["txtcodestpro5"];
		    }
		 else
		    {
		      $ls_codestpro4 = str_pad('',25,0,0);
		      $ls_codestpro5 = str_pad('',25,0,0);
		    }
		   
		 $la_estpro[0]  = str_pad($ls_codestpro1,25,0,0);
		 $la_estpro[1]  = str_pad($ls_codestpro2,25,0,0);
		 $la_estpro[2]  = str_pad($ls_codestpro3,25,0,0);
		 $la_estpro[3]  = str_pad($ls_codestpro4,25,0,0);
		 $la_estpro[4]  = str_pad($ls_codestpro5,25,0,0);
		 $la_estpro[5]  = $ls_estcla;
		 $la_estpro[6]  = $ls_scgctaint;
		
		 $li_error      = 0;
		 $li_save       = 0;
		 for ($i=1;$i<=$total;$i++)
	 	     {
			   $ls_cuentaspg    = trim($_POST["txtcuentaspg".$i]);
			   $ls_dencuentaspg = $_POST["txtdencuenta".$i];
		  	   $ls_cuentascg    = trim($_POST["txtcuentascg".$i]);
			   if (($ls_cuentaspg!="")&&($ls_dencuentaspg!="")&&($ls_cuentascg!=""))
			      {
				    $li_len=strlen($ls_cuentaspg);
				    if ($li_len!=$li_size_cta)
					   {
					     $ls_cuentaspg = str_pad($ls_cuentaspg,$li_size_cta,0,STR_PAD_RIGHT);
					   }
				    $lb_valido=$sig_spgcta->uf_valida_cuenta($ls_cuentaspg ,$la_estpro,$ls_cuentascg);
				    if ($lb_valido)//Si la cuenta es valida me permite insertar la cuenta
				       {
					     $lb_valido=$sig_spgcta->uf_procesar_cuentas($ls_cuentaspg,$ls_dencuentaspg,$la_estpro,$ls_cuentascg,$la_security);
						 if (!$lb_valido)//No pudo procesar la cuenta
						    {
							  $li_error++;
						    }
						 else//Generó correctamente la cuenta
						    {
						     $li_save++;
						    } 
					   }
				    else//La cuenta no es valida
				       {
					     $li_error++;
				       }
			      }
			   if (($ls_cuentaspg!="")&&($ls_cuentascg==""))
				  {
				    $io_msg->message("Cuenta Presupuestaria $ls_cuentaspg, necesita el casamiento contable !!!");
				  }
						
			   //Object que contiene los objetos y valores
			   $object[$i][1]="<input type=text name=txtcuentaspg".$i." value='".$ls_cuentaspg."' class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=\"return keyRestrict(event,'1234567890');\" onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$total."');>";		
			   $object[$i][2]="<input type=text name=txtdencuenta".$i." value='".$ls_dencuentaspg."' class=sin-borde style=text-align:left size=60 maxlength=254>";
			   $object[$i][3]="<input type=text name=txtcuentascg".$i." value='".$ls_cuentascg."' class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$i.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
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
		 $lastrow       = $lastrow-1;
		 $ls_estcla 	= $_POST["txtestcla"];
		 $ls_codestpro1 = $_POST["txtcodestpro1"];
		 $ls_codestpro2 = $_POST["txtcodestpro2"];
		 $ls_codestpro3 = $_POST["txtcodestpro3"];
		
		 if ($li_estmodest=='2')
		    {
			  $ls_codestpro4 = $_POST["txtcodestpro4"];
		      $ls_codestpro5 = $_POST["txtcodestpro5"];
			  $la_estpro[3]  = str_pad($ls_codestpro4,25,0,0);
		      $la_estpro[4]  = str_pad($ls_codestpro5,25,0,0);
		    }
		 else
		    {
		      $la_estpro[3]  = str_pad('',25,0,0);
		      $la_estpro[4]  = str_pad('',25,0,0);
		    }
		 $la_estpro[0] = str_pad($ls_codestpro1,25,0,0);
		 $la_estpro[1] = str_pad($ls_codestpro2,25,0,0);
		 $la_estpro[2] = str_pad($ls_codestpro3,25,0,0);
		 $la_estpro[5] = $ls_estcla;		

		 $li_error      = 0;
		 $li_save       = 0;
		 $li_no_existen = 0;
		 $li_temp       = 0;
		 for ($i=1;$i<=$total;$i++)
		     {
			   $ls_cuentaspg    = trim($_POST["txtcuentaspg".$i]);
			   $ls_dencuentaspg = $_POST["txtdencuenta".$i];
			   $ls_cuentascg    = trim($_POST["txtcuentascg".$i]);
			   if ($i!=$li_fila_del)
			      {
				    $li_temp++;
					$object[$li_temp][1]="<input type=text name=txtcuentaspg".$li_temp." value='".$ls_cuentaspg."' class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=\"return keyRestrict(event,'1234567890');\" onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$total."');>";		
					$object[$li_temp][2]="<input type=text name=txtdencuenta".$li_temp." value='".$ls_dencuentaspg."' class=sin-borde style=text-align:left size=60 maxlength=254>";
					$object[$li_temp][3]="<input type=text name=txtcuentascg".$li_temp." value='".$ls_cuentascg."' class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$li_temp.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
					$object[$li_temp][4] ="<a href=javascript:uf_delete_dt('".$li_temp."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
			      }
			   else
			      {
				    $li_fila_del=0;
				    $lb_valido=$sig_spgcta->uf_procesar_delete_cuenta($ls_cuentaspg,$ls_dencuentaspg,$la_estpro,$ls_cuentascg,&$lb_existe,$la_security);
				    if ((!$lb_valido)&&(!$lb_existe))
					   {
					     $li_no_existen++;
					   	 $ls_cuentaspg    = "";
					   	 $ls_dencuentaspg = "";
					     $ls_cuentascg    = "";
					   }
				    elseif((!$lb_valido)&&($lb_existe))
					   {
						 $li_error++;	
						 $li_temp++;
						 $object[$li_temp][1]="<input type=text name=txtcuentaspg".$li_temp." value='".$ls_cuentaspg."' class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=\"return keyRestrict(event,'1234567890');\" onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$total."');>";		
						 $object[$li_temp][2]="<input type=text name=txtdencuenta".$li_temp." value='".$ls_dencuentaspg."' class=sin-borde style=text-align:left size=60 maxlength=254>";
						 $object[$li_temp][3]="<input type=text name=txtcuentascg".$li_temp." value='".$ls_cuentascg."' class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$li_temp.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
						 $object[$li_temp][4] ="<a href=javascript:uf_delete_dt('".$li_temp."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";				
					   }
					elseif(($lb_valido)&&($lb_existe))
					   {
						 $li_save++;
						 $ls_cuentaspg   = "";
						 $ls_dencuentaspg= "";
						 $ls_cuentascg   = "";
					   }
			      }
		     }
		 $object[$total][1]="<input type=text name=txtcuentaspg".$total." value='".$ls_cuentaspg."' class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=\"return keyRestrict(event,'1234567890');\" onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$total."');>";		
		 $object[$total][2]="<input type=text name=txtdencuenta".$total." value='".$ls_dencuentaspg."' class=sin-borde style=text-align:left size=60 maxlength=254>";
		 $object[$total][3]="<input type=text name=txtcuentascg".$total." value='".$ls_cuentascg."' class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$total.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
		 $object[$total][4] ="<a href=javascript:uf_delete_dt('".$total."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
		 $io_msg->message("$li_save Cuenta(s) Eliminada(s) ,$li_error Cuenta(s) con error,$li_no_existen cuentas no existen");
	}

	/////////////////////// D E L E T E A L L ///////////////////////////////////////////////////////////////////	
	// Elimina todos las cuentas del detalle
	if ($ls_operacion=="DELETEALL")
	   {
	     $total         = $_POST["total"];
	 	 $lastrow       = $_POST["lastrow"];
	 	 $ls_estcla 	= $_POST["txtestcla"];
		 $ls_codestpro1 = $_POST["txtcodestpro1"];
		 $ls_codestpro2 = $_POST["txtcodestpro2"];
	  	 $ls_codestpro3 = $_POST["txtcodestpro3"];
		 $la_estpro[5]  = $ls_estcla;
		  
	 	 if ($li_estmodest=='2')
			{
			  $ls_codestpro4 = $_POST["txtcodestpro4"];
			  $ls_codestpro5 = $_POST["txtcodestpro5"];
			  $la_estpro[3]  = str_pad($ls_codestpro4,25,0,0);
			  $la_estpro[4]  = str_pad($ls_codestpro5,25,0,0);
			}
		 else
			{
		      $la_estpro[3]  = str_pad('',25,0,0);
			  $la_estpro[4]  = str_pad('',25,0,0);
		    }
		 $la_estpro[0]  = str_pad($ls_codestpro1,25,0,0);
	 	 $la_estpro[1]  = str_pad($ls_codestpro2,25,0,0);
	     $la_estpro[2]  = str_pad($ls_codestpro3,25,0,0);
			
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
					 $lb_valido=$sig_spgcta->uf_procesar_delete_cuenta($ls_cuentaspg,$ls_dencuentaspg,$la_estpro,$ls_cuentascg,&$lb_existe,$la_security);
					 if ((!$lb_valido)&&(!$lb_existe))
					    {
						  $li_no_existen++;
						  $ls_cuentaspg    = "";
						  $ls_dencuentaspg = "";
						  $ls_cuentascg    = "";
					    }
					 elseif((!$lb_valido)&&($lb_existe))
					    {
						  $li_error++;
					    }
					 elseif(($lb_valido)&&($lb_existe))
						{
						  $li_save++;
						  $ls_cuentaspg   = "";
						  $ls_dencuentaspg= "";
						  $ls_cuentascg   = "";
						}
				    }		
				$object[$i][1]="<input type=text name=txtcuentaspg".$i." value='".$ls_cuentaspg."' class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=\"return keyRestrict(event,'1234567890');\"  onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$total."');>";		
				$object[$i][2]="<input type=text name=txtdencuenta".$i." value='".$ls_dencuentaspg."' class=sin-borde style=text-align:left size=60 maxlength=254>";
				$object[$i][3]="<input type=text name=txtcuentascg".$i." value='".$ls_cuentascg."' class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$i.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
				$object[$i][4] ="<a href=javascript:uf_delete_dt('".$i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
			    $lastrow      = 0;
		     }
		 $io_msg->message("$li_save Cuenta(s) Eliminada(s) ,$li_error Cuenta(s) con error,$li_no_existen cuentas no existen");
	   }
	   
	   if ($ls_operacion=="CARGARCASAMIENTO")
	   { 
	     $lb_valido=$sig_spgcta->uf_load_casamiento_contable($ls_empresa);
		 $li_totrow_det=$sig_spgcta->ds->getRowCount("sig_cuenta");
		 for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
		  { 
			 $ls_cuentaspg=$sig_spgcta->ds->data["sig_cuenta"][$li_s];
			 $ls_dencuentaspg=$sig_spgcta->ds->data["denominacion"][$li_s];
			 $ls_cuentascg=$sig_spgcta->ds->data["sc_cuenta"][$li_s];
		
			 //Object que contiene los objetos y valores	iniciales del grid.	
			 $object[$li_s][1]="<input type=text name=txtcuentaspg".$li_s." value='".$ls_cuentaspg."' id=txtcuentaspg".$li_s."  class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=return keyRestrict(event,'1234567890'); onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$li_s."');>";		
			 $object[$li_s][2]="<input type=text name=txtdencuenta".$li_s." value='".$ls_dencuentaspg."'  class=sin-borde style=text-align:left size=60 maxlength=254>";
			 $object[$li_s][3]="<input type=text name=txtcuentascg".$li_s." value='".$ls_cuentascg."'  class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$li_s.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
			 $object[$li_s][4] ="<a href=javascript:uf_delete_dt('".$li_s."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
		  }
		 $ls_filaini=$li_totrow_det+1; 
		 for ($li_s=$ls_filaini;$li_s<=$total;$li_s++)
		 {
		     $object[$li_s][1]="<input type=text name=txtcuentaspg".$li_s." value='' id=txtcuentaspg".$li_s."  class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=return keyRestrict(event,'1234567890'); onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$li_s."');>";		
			 $object[$li_s][2]="<input type=text name=txtdencuenta".$li_s." value=''  class=sin-borde style=text-align:left size=60 maxlength=254>";
			 $object[$li_s][3]="<input type=text name=txtcuentascg".$li_s." value=''  class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$li_s.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
			 $object[$li_s][4] ="<a href=javascript:uf_delete_dt('".$li_s."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
		 } 
		  
		   $lastrow = 0;
	   } 
	   
	   if ($ls_operacion=="PARTIDASINGRESOS")
	   { 
	     $total         = $_POST["total"];
	 	 $lastrow       = $_POST["lastrow"];
	 	 $ls_estcla 	= $_POST["txtestcla"];
		 $ls_codestpro1 = $_POST["txtcodestpro1"];
		 $ls_codestpro2 = $_POST["txtcodestpro2"];
	  	 $ls_codestpro3 = $_POST["txtcodestpro3"];
		 $la_estpro[5]  = $ls_estcla;
	 	 if ($li_estmodest=='2')
			{
			  $ls_codestpro4 = $_POST["txtcodestpro4"];
			  $ls_codestpro5 = $_POST["txtcodestpro5"];
			  $la_estpro[3]  = str_pad($ls_codestpro4,25,0,0);
			  $la_estpro[4]  = str_pad($ls_codestpro5,25,0,0);
			}
		 else
			{
		      $la_estpro[3]  = str_pad('',25,0,0);
			  $la_estpro[4]  = str_pad('',25,0,0);
		    }
		 $la_estpro[0]  = str_pad($ls_codestpro1,25,0,0);
	 	 $la_estpro[1]  = str_pad($ls_codestpro2,25,0,0);
	     $la_estpro[2]  = str_pad($ls_codestpro3,25,0,0);
		 for($li_s=1;$li_s<=$total;$li_s++)
		  { 
		     $ls_cuentaspg    = trim($_POST["txtcuentaspg".$li_s]);
			 $ls_dencuentaspg = $_POST["txtdencuenta".$li_s];
			 $ls_cuentascg    = trim($_POST["txtcuentascg".$li_s]);
			 /*$ls_cuentaspg=$sig_spgcta->ds->data["sig_cuenta"][$li_s];
			 $ls_dencuentaspg=$sig_spgcta->ds->data["denominacion"][$li_s];
			 $ls_cuentascg=$sig_spgcta->ds->data["sc_cuenta"][$li_s];*/
			 if(($ls_cuentaspg!="")&&($ls_dencuentaspg!="")&&($ls_cuentascg!=""))
		     {
				 //Object que contiene los objetos y valores	iniciales del grid.	
				 $object[$li_s][1]="<input type=text name=txtcuentaspg".$li_s." value='".$ls_cuentaspg."' id=txtcuentaspg".$li_s."  class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=return keyRestrict(event,'1234567890'); onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$li_s."');>";		
				 $object[$li_s][2]="<input type=text name=txtdencuenta".$li_s." value='".$ls_dencuentaspg."'  class=sin-borde style=text-align:left size=60 maxlength=254>";
				 $object[$li_s][3]="<input type=text name=txtcuentascg".$li_s." value='".$ls_cuentascg."'  class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$li_s.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
				 $object[$li_s][4] ="<a href=javascript:uf_delete_dt('".$li_s."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
		     }
			 else
			 {
			 	 //Object que contiene los objetos y valores	iniciales del grid.	
        		 $object[$li_s][1]="<input type=text name=txtcuentaspg".$li_s." value='' id=txtcuentaspg".$li_s."  class=sin-borde style=text-align:center size=20 maxlength=".$li_size_cta." onKeyPress=return keyRestrict(event,'1234567890'); onBlur=javascript:uf_rellenar_cuenta('".$li_size_cta."','".$li_s."');>";		
		         $object[$li_s][2]="<input type=text name=txtdencuenta".$li_s." value=''  class=sin-borde style=text-align:left size=60 maxlength=254>";
		         $object[$li_s][3]="<input type=text name=txtcuentascg".$li_s." value=''  class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$li_s.");><img src=../../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contablese ' width=15 height=15 border=0></a>";
		         $object[$li_s][4] ="<a href=javascript:uf_delete_dt('".$li_s."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>";
			 }
		  }
	   } 
?>
<p>&nbsp;</p>
<div align="center">
  <table width="718" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="716" height="221" valign="top">
		<form name="formulario" method="post" action="" id="sigesp_spg_d_planctas.php">
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
                <td height="22" colspan="2">Definici&oacute;n Plan de Cuentas de Gasto</td>
              </tr>
              <tr class="formato-blanco">
                <td width="142" height="22">&nbsp;</td>
                <td width="536" height="22"><input name="status" type="hidden" id="status" value="<?php print $ls_estatus ?>">
                <input name="hidmaestro" type="hidden" id="hidmaestro" value="<?php print $ls_maestro ?>"></td>
              </tr>
                <tr>
    <td height="22" title="<?php print $dat["nomestpro1"]; ?>"><div align="right"><?php print $dat["nomestpro1"];  ?></div></td>
    <td height="22" colspan="2">
      <div align="left">
        <input name="txtcodestpro1" type="text" id="txtcodestpro1" style="text-align:center" value="<?php print $ls_codestpro1;?>" size="<?php print $ls_loncodestpro1+2 ?>" maxlength="<?php print $ls_loncodestpro1 ?>" readonly>
        <a href="javascript:catalogo_estpro1();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 1"></a>        
        <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php print $ls_denestpro1;?>" size="65" readonly>     
         <input name="txtestcla" type="hidden" class="" id="txtestcla" value="<?php print $ls_estcla;?>" size="2" readonly>
         <input name="hidscgctaint" type="hidden" id="hidscgctaint" value="<?php echo $ls_scgctaint ?>">     
      </div>     </td>
  </tr>
  <tr>
    <td height="22" title="<?php print $dat["nomestpro2"]; ?>"><div align="right"><?php print $dat["nomestpro2"] ; ?></div>      </td>
    <td height="22" colspan="2"><div align="left">
      <input name="txtcodestpro2" type="text" id="txtcodestpro2" style="text-align:center" value="<?php print $ls_codestpro2;?>" size="<?php print $ls_loncodestpro2+2 ?>" maxlength="<?php print $ls_loncodestpro2 ?>" readonly>
      <a href="javascript:catalogo_estpro2();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 2"></a>
        <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" value="<?php print $ls_denestpro2;?>" size="65" readonly>
    </div>	</td>
  </tr>
  <tr>
    <td height="22" title="<?php print $dat["nomestpro3"]; ?>"><div align="right"><?php print $dat["nomestpro3"] ; ?></div></td>
    <td height="22" colspan="2">      <div align="left">
      <input name="txtcodestpro3" type="text" id="txtcodestpro3" style="text-align:center" value="<?php print $ls_codestpro3;?>" size="<?php print $ls_loncodestpro3+2 ?>" maxlength="<?php print $ls_loncodestpro3 ?>" readonly onBlur="">

      <a href="javascript:catalogo_estpro3();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 3"></a>
      <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" value="<?php print $ls_denestpro3;?>" size="65" readonly>
    </div></td>
  </tr>
  <?php
if ($li_estmodest=='2')
{ 
	 ?>		
        <tr>    
			<td height="22"><div align="right"><?php print $ls_nomestpro4 ; ?></div></td>
			<td height="22" colspan="2"><label>
			  <input name="txtcodestpro4" type="text" id="txtcodestpro4" value="<?php print $ls_codestpro4 ?>" size="<?php print $ls_loncodestpro4+2 ?>" maxlength="<?php print $ls_loncodestpro4 ?>" readonly style="text-align:center">
			  <a href="javascript:catalogo_estpro4();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> 
			  <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" value="<?php print $ls_denestpro4 ?>" size="65" readonly style="text-align:left">
			</label></td>
		  </tr>
		  <tr>
			<td height="22"><div align="right"><?php print $ls_nomestpro5 ?></div></td>
			<td height="22" colspan="2"><label>
			  <input name="txtcodestpro5" type="text" id="txtcodestpro5" value="<?php print $ls_codestpro5 ?>" size="<?php print $ls_loncodestpro5+2 ?>" maxlength="<?php print $ls_loncodestpro5 ?>" readonly style="text-align:center" onBlur="">
			  <a href="javascript:catalogo_estpro5();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> 
			  <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" value="<?php print $ls_denestpro5 ?>" size="65" readonly style="text-align:left">
			</label></td>
          </tr>
     
  <?php		 
   
	 }
  ?>
            <tr class="formato-blanco">
              <td height="22" colspan="2">&nbsp;&nbsp;
              <div align="left"> &nbsp;<a href="javascript: uf_blanquear();"> <img src="../../shared/imagebank/tools20/nuevo.gif" alt="Aplicar a Todas" width="20" height="20" border="0">Blanquear</a> &nbsp;&nbsp;  <a href="javascript: uf_agregar_cuentas();"> <img src="../../shared/imagebank/mas.gif" width="9" height="17" border="0">Agregar Cuentas</a> <a href="javascript:uf_delete_all();"><img src="../../shared/imagebank/tools20/deshacer.gif" alt="Borrar todas las cuentas" width="20" height="20" border="0"></a>&nbsp;&nbsp;<a href="javascript:uf_delete_all();">Borrar Todas</a> &nbsp;&nbsp;<!--<a href="javascript:cargar_casamiento();"> <img src="../../shared/imagebank/mas.gif" width="9" height="17" border="0"> Agregar Casamiento </a>-->&nbsp;&nbsp;
			<?php
			   $lb_valido=$sig_spgcta->uf_load_plan_cuenta_ingreso($ls_empresa);
			   if($lb_valido)
			   {
			?>  
			  <a href="javascript:seleccionar_partidasingreso();"><img src="../../shared/imagebank/mas.gif" width="9" height="17" border="0"> Agregar Cuentas de Ingreso</a></div></td></tr>
            <?php
			   }
			?>
		    <tr class="formato-blanco">
              <td height="22" colspan="2"><p align="center">
                <?php $io_grid->makegrid($total,$title,$object,580,'Detalles Cuenta',$grid1);?>
                <input name="total" type="hidden" id="total" value="<?php print $total?>">
</p>              </td>
            </tr>
          </table>
          <p align="center">&nbsp;          </p>
            <p align="center">
              <input name="operacion"  type="hidden" id="operacion" value="<?php print $ls_operacion;?>">
              <input name="lastrow"    type="hidden" id="lastrow"    value="<?php print $lastrow;?>" >
              <input name="filadelete" type="hidden" id="filadelete">
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
  if (li_incluir==1)
	 {	
	   f.operacion.value ="NUEVO";
	   f.action="sigesp_spg_d_planctas.php";
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
	  f.action="sigesp_spg_d_planctas.php";
	  f.submit();
    }
 else
    {
 	  alert("No tiene permiso para realizar esta operación !!!");
	}	
}					

function ue_buscar()
{
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
	     ls_codestpro1 = f.txtcodestpro1.value;
	     ls_estcla = f.txtestcla.value;
		 ls_codestpro2 = f.txtcodestpro2.value;
		 ls_codestpro3 = f.txtcodestpro3.value;
		 li_estmodest  = <?php print $li_estmodest ?>;
		 if (li_estmodest=='2')
		    {
		      ls_codestpro4 = f.txtcodestpro4.value;
		      ls_codestpro5 = f.txtcodestpro5.value;
			  if ((ls_codestpro1!="")&&(ls_codestpro2!="")&&(ls_codestpro3!="")&&(ls_codestpro4!="")&&(ls_codestpro5!=""))
				 { 
				   window.open("sigesp_spg_cat_ctaspg.php?codestpro1="+ls_codestpro1+"&codestpro2="+ls_codestpro2+
				   			   "&codestpro3="+ls_codestpro3+"&codestpro4="+ls_codestpro4+"&codestpro5="+ls_codestpro5+"&estcla="+ls_estcla,
							   "catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
				 }
			  else
				 {
				   alert("Seleccione la programatica !!!");
				 }
		    }
		 else
		    { 
		 	  if ((ls_codestpro1!="")&&(ls_codestpro2!="")&&(ls_codestpro3!=""))
				 {
				   window.open("sigesp_spg_cat_ctaspg.php?codestpro1="+ls_codestpro1+"&codestpro2="+ls_codestpro2+
				   			   "&codestpro3="+ls_codestpro3+"&estcla="+ls_estcla,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
				 }
			  else
				 {
				   alert("Seleccione la programatica !!!");
				 }		 
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
	  if (confirm("¿ Esta seguro de Eliminar todas las Cuentas ?"))
	     {
		   ls_codestpro1 = f.txtcodestpro1.value;
		   ls_codestpro2 = f.txtcodestpro2.value;
		   ls_codestpro3 = f.txtcodestpro3.value;
		   li_estmodest  = "<?php print $li_estmodest ?>"; 
		   if (li_estmodest=='2')
		      {
		        ls_codestpro4 = f.txtcodestpro4.value;
		        ls_codestpro5 = f.txtcodestpro5.value;
			  }
		   else
		      {
			    ls_codestpro4 = "00";
		        ls_codestpro5 = "00";
			  }
		   if ((ls_codestpro1!="")&&(ls_codestpro2!="")&&(ls_codestpro3!="")&&(ls_codestpro4!="")&&(ls_codestpro5!=""))
	 	      {  
			    f.operacion.value ="DELETEALL";
			    f.action="sigesp_spg_d_planctas.php";
			    f.submit();
		      }
		   else
		      {
			    alert("Seleccione la programatica !!!");
		      }
	     }
	}
  else
    {
      alert("No tiene permiso para realizar esta operación !!!");
	}
}

function cat_plan(fila)
{
	ls_cuentaspg=eval("f.txtcuentaspg"+fila+".value;");
	ls_dencuenta=eval("f.txtdencuenta"+fila+".value;");
	if((ls_cuentaspg!="")&&(ls_dencuenta!=""))
	{
		window.open("sigesp_sel_scg_plancuentaspg.php?fila="+fila,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes,dependent=yes");
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

function catalogo_estpro1()
{
	pagina="sigesp_spg_cat_estpro1.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function catalogo_estpro2()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_estcla     = f.txtestcla.value;
	if((ls_codestpro1!="")&&(ls_denestpro1!=""))
	{
		pagina="sigesp_spg_cat_estpro2.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtclasificacion="+ls_estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
    else
	{
	  alert("Debe seleccionar una estructura del Nivel 1 !!!");
	}
}
function catalogo_estpro3()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;
	ls_estcla     = f.txtestcla.value;	
	if((ls_codestpro1!="")&&(ls_denestpro1!="")&&(ls_codestpro2!="")&&(ls_denestpro2!=""))
	{
		pagina="sigesp_spg_cat_estpro3.php?submit=si&txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtclasificacion="+ls_estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=780,height=450,resizable=yes,location=no");
	}
	else
	{
		li_estmodest = "<?php print $li_estmodest ?>";
		if (li_estmodest=='1')
		   {
		     pagina="sigesp_cat_public_estpro.php?submit=si";
		     window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=780,height=450,resizable=yes,location=no");
	       }
		else
		   {
		     alert("Debe seleccionar una estructura del Nivel 2 !!!");
		   }
	}
}

function catalogo_estpro4()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;
	ls_denestpro3 = f.txtdenestpro3.value;
	ls_codestpro4 = f.txtcodestpro4.value;
	ls_estcla     = f.txtestcla.value;	
	if ((ls_codestpro1!="")&&(ls_denestpro1!="")&&(ls_codestpro2!="")&&(ls_denestpro2!="")&&(ls_codestpro3!="")&&(ls_denestpro3!="")&&(ls_codestpro4==""))
	   {
		 pagina="sigesp_spg_cat_estpro4.php?submit=si&txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtcodestpro3="+ls_codestpro3+"&txtdenestpro3="+ls_denestpro3+"&txtclasificacion="+ls_estcla;
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
 	   }
    else
	   {
	     alert("Debe seleccionar una estructuta del Nivel 3 !!!");
	   }
}

function catalogo_estpro5()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;
	ls_codestpro4 = f.txtcodestpro4.value;
    ls_codestpro5 = f.txtcodestpro5.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	ls_denestpro3 = f.txtdenestpro3.value;
	ls_denestpro4 = f.txtdenestpro4.value;
	ls_denestpro5 = f.txtdenestpro5.value;
	ls_estcla     = f.txtestcla.value;
	if ((ls_codestpro1!="")&&(ls_denestpro1!="")&&(ls_codestpro2!="")&&(ls_denestpro2!="")&&(ls_codestpro3!="")&&(ls_denestpro3!="")&&(ls_codestpro4!="")&&(ls_denestpro4!="")&&(ls_codestpro5==""))
	   {
    	 pagina="sigesp_spg_cat_estpro5.php?submit=si&txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtcodestpro3="+ls_codestpro3+"&txtdenestpro3="+ls_denestpro3+"&txtcodestpro4="+ls_codestpro4+"&txtdenestpro4="+ls_denestpro4+"&txtclasificacion="+ls_estcla;
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	   }
	else
	   {
		 pagina="sigesp_cat_public_estpro.php?submit=si";
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	   }
}

function uf_blanquear()
{
	f.operacion.value="BLANQUEAR";
	f.action="sigesp_spg_d_planctas.php";
	f.submit();
}

function  uf_agregar_cuentas()
{
	ls_estcla     = f.txtestcla.value;
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;
	
	li_estmodest  = "<?php print $li_estmodest ?>";
	if (li_estmodest=='2')
	   {
	     ls_codestpro4 = f.txtcodestpro4.value;
	     ls_codestpro5 = f.txtcodestpro5.value;		 
	   }
	else
	   {
	     ls_codestpro4 = "00";
	     ls_codestpro5 = "00";
	   }
	li_last       = f.lastrow.value;
	li_total      = f.total.value;
	ls_cuentas    = "";
	if((ls_codestpro1!="")&&(ls_codestpro2!="")&&(ls_codestpro3!="")&&(ls_codestpro4!="")&&(ls_codestpro5!=""))
	{
		ls_pagina = "sigesp_sel_ctaspg.php?txtcodestpro1="+ls_codestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtcodestpro3="+ls_codestpro3+"&txtcodestpro4="+ls_codestpro4+"&txtcodestpro5="+ls_codestpro5+"&txtestcla="+ls_estcla+"&lastrow="+li_last+"&cuentas="+ls_cuentas;
		window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=538,height=450,left=50,top=50,location=no,resizable=no,dependent=yes");
	}
	else
	{
		alert("Seleccione la programatica !!!");
	}
}

function uf_delete_dt(fila)
{
	if(confirm("Esta seguro de eliminar la Cuenta ?"))
	{
		f.filadelete.value = fila;
		codestpro1         = f.txtcodestpro1.value;
		codestpro2         = f.txtcodestpro2.value;
		codestpro3         = f.txtcodestpro3.value;
		codestpro2         = f.txtcodestpro2.value;
	    codestpro3         = f.txtcodestpro3.value;
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!=""))
		{
		f.operacion.value="DELETE";
		f.action="sigesp_spg_d_planctas.php";
		f.submit();
		}
		else
		{
			alert("Seleccione la programatica");
		}
	}
}

function uf_delete_all()
{
	if(confirm("Seguro de Eliminar todas las Cuentas ?"))
	{
		codestpro1=f.txtcodestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		codestpro3=f.txtcodestpro3.value;
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!=""))
		{
		f.operacion.value="DELETEALL";
		f.action="sigesp_spg_d_planctas.php";
		f.submit();
		}
		else
		{
			alert("Seleccione la programatica");
		}
	}
}

function cargar_casamiento()
{
   ls_codestpro1 = f.txtcodestpro1.value;
   ls_codestpro2 = f.txtcodestpro2.value;
   ls_codestpro3 = f.txtcodestpro3.value;
   li_estmodest  = "<?php print $li_estmodest ?>"; 
   if (li_estmodest=='2')
	  {
		ls_codestpro4 = f.txtcodestpro4.value;
		ls_codestpro5 = f.txtcodestpro5.value;
	  }
   else
	  {
		ls_codestpro4 = "00";
		ls_codestpro5 = "00";
	  }
   if ((ls_codestpro1!="")&&(ls_codestpro2!="")&&(ls_codestpro3!="")&&(ls_codestpro4!="")&&(ls_codestpro5!=""))
	  {
		f.operacion.value="CARGARCASAMIENTO";
		f.action="sigesp_spg_d_planctas.php";
		f.submit();
	  }
   else 
	   {
		alert("Seleccione la programatica !!!");
	   }

}

function uf_rellenar_cuenta(longitud,li_i)
{
		cadena_ceros="";
		cadena=	eval("document.formulario.txtcuentaspg"+li_i+".value");
		lencad=cadena.length;
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena+cadena_ceros;
		eval("document.formulario.txtcuentaspg"+li_i+".value="+cadena);
}

function seleccionar_partidasingreso()
{
   ls_codestpro1 = f.txtcodestpro1.value;
   ls_codestpro2 = f.txtcodestpro2.value;
   ls_codestpro3 = f.txtcodestpro3.value;
   li_estmodest  = "<?php print $li_estmodest ?>"; 
   ls_estcla=f.txtestcla.value;
   if (li_estmodest=='2')
	  {
		ls_codestpro4 = f.txtcodestpro4.value;
		ls_codestpro5 = f.txtcodestpro5.value;
	  }
   else
	  {
		ls_codestpro4 = "00";
		ls_codestpro5 = "00";
	  }
	if((ls_codestpro1!="")&&(ls_codestpro2!="")&&(ls_codestpro3!="")&&(ls_codestpro4!="")&&(ls_codestpro5!=""))
	{
	    ls_pagina = "sigesp_spi_d_partingresos.php?destino=destino&txtcodestpro1="+ls_codestpro1+"&txtcodestpro2="+ls_codestpro2
		             +"&txtcodestpro3="+ls_codestpro3+"&txtcodestpro4="+ls_codestpro4+"&txtcodestpro5="+ls_codestpro5
					 +"&txtestcla="+ls_estcla;
		window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=500,left=60,top=70,location=no,resizable=no,dependent=yes");

		f.operacion.value="PARTIDASINGRESOS";
	    f.action="sigesp_spg_d_planctas.php";
	    f.submit();
	}
	else
	{
		alert("Seleccione la programatica !!!");
	}

}
</script>
</html>