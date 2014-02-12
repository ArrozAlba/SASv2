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
	require_once("class_funciones_gasto.php");
	$io_fun_gasto=new class_funciones_gasto();
	$io_fun_gasto->uf_load_seguridad("SPG","sigesp_spg_p_cerrarpre.php",$ls_permisos,$la_seguridad,$la_permisos);
  //////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

	require_once("../sigesp_config.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("sigesp_spg_c_transferencia.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	require_once("sigesp_spg_c_mod_presupuestarias.php");
	$io_comprobantes = new sigesp_spg_c_mod_presupuestarias;
	$io_seguridad = new sigesp_c_seguridad;
    $in        = new sigesp_include();
	$con       = $in->uf_conectar();
	$io_sql    =new class_sql($con);
	$msg=new class_mensajes();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_sistema="SPG";
	$ls_ventana="sigesp_spg_p_cerrarpre.php";
	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventana;
	
	if(array_key_exists("txtbddestino",$_POST))
	{
	  $ls_dbdestino=$_POST["txtbddestino"];     	
	}
	else
	{
	  $ls_dbdestino="";
	}
	
	if(array_key_exists("conexion",$_POST))
	{
	  $li_conexion=$_POST["conexion"];     	
	}
	else
	{
	  $li_conexion=0;
	}
	
	if(array_key_exists("hidciepre",$_POST))
	{
	  $li_ciepre=$_POST["hidciepre"];     	
	}
	else
	{
	  $li_ciepre = $io_fun_gasto->uf_select_cierre_pres();
	}
	
	if(array_key_exists("consolida",$_POST))
	{
	  $li_consolida = $_POST["consolida"];     	
	}
	else
	{
	  $li_consolida =$_SESSION["la_empresa"]["estempcon"];
    }
	
	if(array_key_exists("dbconsolida",$_POST))
	{
	  $ls_dbconsolida = $_POST["dbconsolida"];     	
	}
	else
	{
	  $ls_dbconsolida =$_SESSION["la_empresa"]["basdatcon"];
	}
	global $ls_operacion, $li_conexion, $li_ciepre, $li_consolida, $ls_dbconsolida;
	  
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
<title >Reverso/Cierre de Presupuesto de Gasto</title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="css/rpc.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="10" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="10" bgcolor="#E7E7E7" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
                  <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Contabilidad Presupuestaria de Gasto </td>
			        <td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </table></td>
  </tr>
  <tr>
           <?php
	   if(array_key_exists("confinstr",$_SESSION["la_empresa"]))
	  {
      if($_SESSION["la_empresa"]["confinstr"]=='A')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='V')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2007.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='N')
	  {
   ?>
       <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
  <?php
      }
	  	 }
	  else
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
	<?php 
	}
	?>
  </tr>
  <tr>
    <td width="780" height="13" colspan="10" class="toolbar"></td>
  </tr>
  <tr>
    <td width="25" height="20" class="toolbar"><div align="center"><a href="javascript:ue_procesar();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif"  title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"></a><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="22" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"></a><a href="javascript: ue_ayuda();"></a><a href="javascript: ue_cerrar();"></a><a href="javascript: ue_ayuda();"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>

<p>
<?php
    
	function uf_conectar_destino($as_gestor,$as_dbdestino,$as_puerto,$as_servidor,$as_usuario,$as_password) 
	{
		global $msg;	
		if (strtoupper($as_gestor)==strtoupper("mysqlt"))
		{ 
		    $conec = @mysql_connect($as_servidor,$as_usuario,$as_password);						
			if($conec===false)
			{
				$msg->message("No pudo conectar con el servidor de datos MYSQL ".$as_servidor." , consulte a su Administrador de Sistema");	
			}
			else
			{			    
				$lb_ok=@mysql_select_db(trim($as_dbdestino),$conec);
				if (!$lb_ok)
				{
					$msg->message("No existe la base de datos ".$as_dbdestino);					
				}
			}
		}		
		elseif(strtoupper($as_gestor)==strtoupper("postgres"))
		{
			$conec = @pg_connect("host=".$as_servidor." port=".$as_puerto."  dbname=".$as_dbdestino." user=".$as_usuario." password=".$as_password); 
		    
			if (!$conec)
			{
				$msg->message("No pudo conectar a la base de datos ".$as_dbdestino." en el servidor ".$as_servidor.", consulte a su Administrador de Sistema");				
			}
      	 
	    }	
	 return $conec;
	}// fin de function uf_conectar_destino() 
	
	
	
	
	function uf_get_datos_bddestino($as_dbdestino,$aa_empresa)
	{
	 $encontrado = false;
	 $li_total = count($aa_empresa["database"]);
	 for($i=1;(($i <= $li_total)&&(!$encontrado)); $i++)
	 {
       if(trim($aa_empresa["database"][$i])==trim($as_dbdestino))
	   {
	    $lb_conecta = uf_conectar_destino($aa_empresa["gestor"][$i],$aa_empresa["database"][$i],$aa_empresa["port"][$i],$aa_empresa["hostname"][$i], $aa_empresa["login"][$i],$aa_empresa["password"][$i]);
		if($lb_conecta)
		{
			 $_SESSION["ls_database_destino"] = $aa_empresa["database"][$i];							
			 $_SESSION["ls_hostname_destino"] = $aa_empresa["hostname"][$i];
			 $_SESSION["ls_login_destino"]    = $aa_empresa["login"][$i];
			 $_SESSION["ls_password_destino"] = $aa_empresa["password"][$i];
			 $_SESSION["ls_gestor_destino"]   = $aa_empresa["gestor"][$i];	
			 $_SESSION["ls_port_destino"]     = $aa_empresa["port"][$i];
			 $encontrado = true;
		}
	   }		 
	}
	return $encontrado;  
   }
    
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		if($ls_operacion=="PROCESAR_CIERRE_MULTIEMPRESA")
		{	
			         $lb_valido_cierre = false;				 
					 $resultado =$io_comprobantes->uf_cargar_bddestino();//busca todas las bases configuradas como consolidadoras										
					 $li_numrows=$io_sql->num_rows($resultado);
					 if($li_numrows > 0)
					 {
						  $ls_dbdestino ="";
						  $i=0;	
                          $la_bddestino=array();
						  $lb_valido_cierre = true;							  				 
						  while(($li_numrows>0)&&($lb_valido_cierre))
						  {
							  $row=$io_sql->fetch_row($resultado);
							  $li_numrows=$li_numrows-1;
							  $ls_dbdestino=rtrim($row["nombasdat"]);
							  $lb_encontrado = uf_get_datos_bddestino($ls_dbdestino,$empresa);
						      if ($lb_encontrado)
							  {
								 $io_transferencia=new sigesp_spg_c_transferencia($_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"] ,$_SESSION["ls_database_destino"],$_SESSION["ls_gestor_destino"]);
							     $ls_codempdes = $io_transferencia->uf_obtener_codempresa_bd($_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"] ,$_SESSION["ls_database_destino"],$_SESSION["ls_gestor_destino"]);	
							     $lb_valido_cierre=$io_transferencia->uf_cerrar_presupuesto($ls_codempdes,1);
								 if ($lb_valido_cierre)
								 {
										///arreglo que guarda las bases de datos cuando la actualizacion es exitosa
										$la_bddestino[$i][0]   = $_SESSION["ls_hostname_destino"];
										$la_bddestino[$i][1]   = $_SESSION["ls_login_destino"];
										$la_bddestino[$i][2]   = $_SESSION["ls_password_destino"];
										$la_bddestino[$i][3]   = $_SESSION["ls_database_destino"];
										$la_bddestino[$i][4]   = $_SESSION["ls_gestor_destino"];
										$i++;
								 }
								 elseif($i>0)
								 {
								    $hostname_destino = "";  
									$login_destino    = "";   
									$password_destino = "";   
									$database_destino = ""; 
									$gestor_destino   = "";
									for ($ls_j=0;$ls_j<$i;$ls_j++)
									{  
										$hostname_destino = $la_bddestino[$ls_j][0];  
										$login_destino    = $la_bddestino[$ls_j][1];   
										$password_destino = $la_bddestino[$ls_j][2];   
										$database_destino = $la_bddestino[$ls_j][3];  
										$gestor_destino   = $la_bddestino[$ls_j][4];									
										$io_revcierre = new sigesp_spg_c_transferencia($hostname_destino,$login_destino,$password_destino,$database_destino,$gestor_destino);
										$ls_codemprev = $io_revcierre->uf_obtener_codempresa_bd($hostname_destino,$login_destino,$password_destino,$database_destino,$gestor_destino);	
										$lb_valido_cierre= $io_transferencia->uf_cerrar_presupuesto($ls_codemprev,0);
									}	
								 }																
									
									 
						      } // Si encontrado
								   unset($io_transferencia);								   
					      } //FIN DEL while	
							     
				     } // FIN DEL if($li_numrows > 0)
					 else
					 {
					  $msg->message("No existen Bases de Datos para Consolidacion Configuradas, consulte a su Administrador de Sistema!!!");
					 }
				 if ($lb_valido_cierre)
				 {				 
					  $lb_valido_cierre = $io_comprobantes->uf_cerrar_presupuesto(1);
					  if ($lb_valido_cierre)
					  {
					   $msg->message("El Presupuesto de Gasto fué cerrado con Éxito !!!");
					   $li_ciepre = 1;
					   $ls_evento="PROCESS";
			 		   $ls_desc_event="Ejecutó el proceso de Cierre de Presupuesto de Gasto ";
			 		   $ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);	 	
					  }
					  else
					  {
					   $msg->message("Error al realizar Cierre del Presupuesto de Gasto !!!");
					  } 
				 }
				 else
				 {
					  $msg->message("Error al realizar Cierre del Presupuesto de Gasto !!!");
				 }
				 
			 
							 
	     }//FIN DEL IF PROCESAR
		 elseif($ls_operacion=="PROCESAR_REVERSO_MULTIEMPRESA")
		 {
		     $lb_valido_cierre = false;				 
			 $resultado =$io_comprobantes->uf_cargar_bddestino();//busca todas las bases configuradas como consolidadoras										
			 $li_numrows=$io_sql->num_rows($resultado);
			 if($li_numrows > 0)
			 {
				  $ls_dbdestino ="";
				  $i=0;	
				  $la_bddestino=array();
				  $lb_valido_cierre = true;							  				 
				  while(($li_numrows>0)&&($lb_valido_cierre))
				  {
					  $row=$io_sql->fetch_row($resultado);
					  $li_numrows=$li_numrows-1;
					  $ls_dbdestino=rtrim($row["nombasdat"]);
					  $lb_encontrado = uf_get_datos_bddestino($ls_dbdestino,$empresa);
					  if ($lb_encontrado)
					  {
						 $io_transferencia=new sigesp_spg_c_transferencia($_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"] ,$_SESSION["ls_database_destino"],$_SESSION["ls_gestor_destino"]);
						 $ls_codempdes = $io_transferencia->uf_obtener_codempresa_bd($_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"] ,$_SESSION["ls_database_destino"],$_SESSION["ls_gestor_destino"]);	
						 $lb_valido_cierre=$io_transferencia->uf_cerrar_presupuesto($ls_codempdes,0);
						 if ($lb_valido_cierre)
						 {
								///arreglo que guarda las bases de datos cuando la actualizacion es exitosa
								$la_bddestino[$i][0]   = $_SESSION["ls_hostname_destino"];
								$la_bddestino[$i][1]   = $_SESSION["ls_login_destino"];
								$la_bddestino[$i][2]   = $_SESSION["ls_password_destino"];
								$la_bddestino[$i][3]   = $_SESSION["ls_database_destino"];
								$la_bddestino[$i][4]   = $_SESSION["ls_gestor_destino"];
								$i++;
						 }
						 elseif($i>0)
						 {
							$hostname_destino = "";  
							$login_destino    = "";   
							$password_destino = "";   
							$database_destino = ""; 
							$gestor_destino   = "";
							for ($ls_j=0;$ls_j<$i;$ls_j++)
							{  
								$hostname_destino = $la_bddestino[$ls_j][0];  
								$login_destino    = $la_bddestino[$ls_j][1];   
								$password_destino = $la_bddestino[$ls_j][2];   
								$database_destino = $la_bddestino[$ls_j][3];  
								$gestor_destino   = $la_bddestino[$ls_j][4];									
								$io_revcierre = new sigesp_spg_c_transferencia($hostname_destino,$login_destino,$password_destino,$database_destino,$gestor_destino);
								$ls_codemprev = $io_revcierre->uf_obtener_codempresa_bd($hostname_destino,$login_destino,$password_destino,$database_destino,$gestor_destino);	
								$io_transferencia->uf_cerrar_presupuesto($ls_codemprev,1);
							}	
						 }																
					  } // Si encontrado
						   unset($io_transferencia);								   
				  } //FIN DEL while	
						 
			 } // FIN DEL if($li_numrows > 0)
			 else
			 {
			  $msg->message("No existen Bases de Datos para Consolidacion Configuradas, consulte a su Administrador de Sistema!!!");
			 }
		 if (($lb_valido_cierre))
		 {				 
			  $lb_valido_cierre = $io_comprobantes->uf_cerrar_presupuesto(0);
			  if($lb_valido_cierre)
			  {
			   $msg->message("El Cierre de Presupuesto de Gasto fué reversado con Éxito !!!");
			   $li_ciepre = 0;
			   $ls_evento="PROCESS";
	           $ls_desc_event="Ejecutó el proceso de reverso de Cierre de Presupuesto de Gasto ";
	           $ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);	
			  }
			  else
			  {
			   $msg->message("Error al realizar Reverso del Cierre del Presupuesto de Gasto !!!");
			  } 
			  
		 }
		 else
		 {
			  $msg->message("Error al realizar Reverso del Cierre del Presupuesto de Gasto !!!");
		 }
		
	  }
	  elseif($ls_operacion=="PROCESAR_CIERRE")
	  {
	   	  $lb_valido_cierre = $io_comprobantes->uf_cerrar_presupuesto(1);
		  if ($lb_valido_cierre)
		  {
		   $msg->message("El Presupuesto de Gasto fué cerrado con Éxito !!!");
		   $li_ciepre = 1;
		   $ls_evento="PROCESS";
		   $ls_desc_event="Ejecutó el proceso de Cierre de Presupuesto de Gasto ";
		   $ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);	 	
		  }
		  else
		  {
		   $msg->message("Error al realizar Cierre del Presupuesto de Gasto !!!");
		  }  
	  }
	  elseif($ls_operacion=="PROCESAR_REVERSO")
	  {
	   	  $lb_valido_cierre = $io_comprobantes->uf_cerrar_presupuesto(0);
		  if($lb_valido_cierre)
		  {
		   $msg->message("El Cierre de Presupuesto de Gasto fué reversado con Éxito !!!");
		   $li_ciepre = 0;
		   $ls_evento="PROCESS";
		   $ls_desc_event="Ejecutó el proceso de reverso de Cierre de Presupuesto de Gasto ";
		   $ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);	
		  }
		  else
		  {
		   $msg->message("Error al realizar Reverso del Cierre del Presupuesto de Gasto !!!");
		  } 
	  }
 }

	
?>
</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_gasto->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_gasto);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
 
  <table width="200" border="0" align="center">
    <tr>
      <td><div align="center">
        <table width="570" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
         <td height="22" colspan="2" class="titulo-celdanew">REVERSO/CIERRE DE PRESUPUESTO DE GASTO </td>
      </tr>                 
          
                 
          <tr>
            <td colspan="2" ><p>
                <input name="dbdestino" type="hidden" id="dbdestino" value="<?php print $ls_dbdestino;?>">
                <input name="operacion" type="hidden" id="operacion" value="<?php print ($ls_operacion); ?>">
                <input name="conexion" type="hidden" id="conexion" value="<?php print ($li_conexion); ?>">
              </p>              <div align="right"></div></td>
            </tr>
          
		 <tr>
            <td height="50" align="center">
			<?php 
			      $ls_etiqueta = "";
			      if ($li_ciepre == 1) 
			      {
				   $ls_etiqueta = " REVERSAR CIERRE DE PRESUPUESTO DE GASTO ";
				  }
				  elseif($li_ciepre == 0)
				  {
				   $ls_etiqueta = " CERRAR PRESUPUESTO DE GASTO ";
				  } 
			?>
              <input name="btcerrar" type="button" class="boton" id="btcerrar" value= "<? print trim($ls_etiqueta); ?>" height="120" onClick="javascript:ue_procesar()"> 
            </td>
            </tr>
	  
		<tr>
          <td colspan="2"><input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">	
			  <input name="hidrango" type="hidden" id="hidrango"> <input name="hidconsolida" type="hidden" id="hidconsolida">
			  <input name="hidciepre" type="hidden" id="hidciepre" value="<?php print $li_ciepre;?>">
			  <input name="consolida" type="hidden" id="consolida" value="<?php print $li_consolida?>">
			  <input name="dbconsolida" type="hidden" id="dbconsolida" value="<?php print $ls_dbconsolida?>"></td>		  
         </tr>
        </table>
      </div></td>
    </tr>
  </table>
</form>      
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	li_cierre=f.hidciepre.value;
	li_consolida = f.consolida.value;
	ls_dbconsolida = f.dbconsolida.value;
	if (li_ejecutar==1)
   	{
	  if (li_consolida == 1)
	  { 
		  if (li_cierre == 1)
		  {
		   if (confirm("¿Esta seguro de reversar el Cierre de Presupuesto de Gasto?"))
		   {
		    f.operacion.value = "PROCESAR_REVERSO_MULTIEMPRESA";
		    f.action="sigesp_spg_p_cerrarpre.php";
		    f.submit();
		   }
		  }
		  else
		  {
		   if (li_cierre == 0)
		   {
		    if (confirm("¿Esta seguro de hacer el Cierre de Presupuesto de Gasto?"))
		    {
			 f.operacion.value = "PROCESAR_CIERRE_MULTIEMPRESA";
		     f.action="sigesp_spg_p_cerrarpre.php";
		     f.submit();
			} 
		   } 
		  }
	  }
	  else
	  {
	   if((ls_dbconsolida == "")&&(li_consolida ==0))
	   {
	    if (li_cierre == 1)
		  {
		   if (confirm("¿Esta seguro de reversar el Cierre de Presupuesto de Gasto?"))
		   {
		    f.operacion.value = "PROCESAR_REVERSO";
		    f.action="sigesp_spg_p_cerrarpre.php";
		    f.submit();
		   }
		  }
		  else
		  {
		   if (li_cierre == 0)
		   {
		    if (confirm("¿Esta seguro de hacer el Cierre de Presupuesto de Gasto?"))
		    {
			 f.operacion.value = "PROCESAR_CIERRE";
		     f.action="sigesp_spg_p_cerrarpre.php";
		     f.submit();
			} 
		   } 
		  }
	   }
	   else
	   {
	    alert("El proceso sólo se puede ejecutar desde la Base de Datos que consolida, consulte a su Administrador de Sistema!!!");
	   }
	  }	   

   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}


function ue_cerrar()
{
   location.href='../index_modules.php' 
}

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>