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
	$io_fun_gasto->uf_load_seguridad("SPG","sigesp_spg_p_trans_asipre.php",$ls_permisos,$la_seguridad,$la_permisos);
  //////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

	require_once("../sigesp_config.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("sigesp_spg_c_transferencia.php");
	require_once("sigesp_spg_c_mod_presupuestarias.php");
	require_once("sigesp_spg_c_comprobante.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad = new sigesp_c_seguridad;
	$io_comprobantes = new sigesp_spg_c_mod_presupuestarias;
	$io_cmp = new sigesp_spg_c_comprobante;
    $in        = new sigesp_include();
	$con       = $in->uf_conectar();
	$io_sql    =new class_sql($con);
	$msg=new class_mensajes();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_sistema="SPG";
	$ls_ventana="sigesp_spg_p_trans_asipre.php";
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
	
	if(array_key_exists("txtfechades",$_POST))
	{
	  $ld_fechades=$_POST["txtfechades"];     	
	}
	else
	{
	  $ld_fechades="";
	}
	
	if(array_key_exists("txtfechahas",$_POST))
	{
	  $ld_fechahas=$_POST["txtfechahas"];     	
	}
	else
	{
	  $ld_fechahas="";
	}
	
	if(array_key_exists("consolida",$_POST))
	{
	  $li_consolida = $_POST["consolida"];     	
	}
	else
	{
	  $li_consolida =$_SESSION["la_empresa"]["estempcon"];
	}
	
	if(array_key_exists("basdatcon",$_POST))
	{
	  $ls_basdatcon=$_POST["basdatcon"];     	
	}
	else
	{
	  $ls_basdatcon=$_SESSION["la_empresa"]["basdatcon"];
	}
	
	if(array_key_exists("codaltemp",$_POST))
	{
	  $ls_codaltemp=$_POST["codaltemp"];     	
	}
	else
	{
	  $ls_codaltemp=$_SESSION["la_empresa"]["codaltemp"];
	}
	
	global $ls_operacion, $li_conexion, $ld_fechades, $ld_fechahas, $io_comprobantes, $li_consolida, $li_basdatcon, $ls_codaltemp;	
	 
	$ls_operacion="";
		
//---------------------------------------------------------------------------------------------------------------------------

	  
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
<title >Transferencia de Asientos Presupuestarios y Contables</title>
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
    <td width="25" height="20" class="toolbar"><div align="center"><a href="javascript:ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title="Transferir" alt="Transferir personal..." name="Transferir" width="20" height="20" border="0" id="Transferir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"></a><a href="javascript: ue_ayuda();"></a><a href="javascript: ue_cerrar();"></a><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif"  title="Ayuda" alt="Ayuda" width="22" height="20" border="0"></a></div></td>
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
		    if($ls_operacion=="PROCESAR_CS")
		    {		
			        $lb_valido=false;
					$rs_data = ""; 
					$lb_valido_update = false;
			        $lb_valido = $io_cmp->uf_select_comprobantes_spg_int($ld_fechades,$ld_fechahas,$rs_data);
					if($lb_valido)
					{ 
			         $li_numcmp=$io_sql->num_rows($rs_data);
					 if($li_numcmp > 0)			 
					 {
					  while($li_numcmp>0)
					  {
					   $fila=$io_sql->fetch_row($rs_data);
					   $li_numcmp=$li_numcmp-1;
					   $ls_comprobante    =rtrim($fila["comprobante"]);
					   $ld_fecha          =$fila["fecha"];
					   $ls_procede        =$fila["procede"];
					   $ls_codban         =$fila["codban"];
					   $ls_ctaban         =$fila["ctaban"];
					   $resultado =$io_comprobantes->uf_cargar_bddestino();//busca todas las bases configuradas como consolidadoras										
					   $li_numrows=$io_sql->num_rows($resultado);
					   $lb_valido_transferencia = true;	
					   if($li_numrows > 0)
					   {
						  	  $ls_dbdestino ="";
						  	  $i=0;	
						      $la_bddestino=array();						  				 
							  while(($li_numrows>0)&&($lb_valido_transferencia))
							  {
								  $row=$io_sql->fetch_row($resultado);
								  $li_numrows=$li_numrows-1;
								  $ls_dbdestino=rtrim($row["nombasdat"]);
								  $lb_encontrado = uf_get_datos_bddestino($ls_dbdestino,$empresa);
								  if ($lb_encontrado)
								  {
									 $ls_registro = "";
									 $io_transferencia=new sigesp_spg_c_transferencia($_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"] ,$_SESSION["ls_database_destino"],$_SESSION["ls_gestor_destino"]);
									 $ls_codempdes = $io_transferencia->uf_obtener_codempresa_bd($_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"] ,$_SESSION["ls_database_destino"],$_SESSION["ls_gestor_destino"]);	
									 $lb_valido_transferencia = false;
									 $lb_valido_transferencia=$io_transferencia->uf_transferir_asipre($ls_codempdes,$ls_comprobante,$ld_fecha,$ls_procede,$ls_codban,$ls_ctaban,'CS');
									 if ($lb_valido_transferencia)
									 {
											$io_transferencia->uf_update_estatus($ls_codempdes,$ls_comprobante,$ld_fecha,$ls_procede,$ls_codban,$ls_ctaban);
											$la_bddestino[$i][0]   = $_SESSION["ls_hostname_destino"];
											$la_bddestino[$i][1]   = $_SESSION["ls_login_destino"];
											$la_bddestino[$i][2]   = $_SESSION["ls_password_destino"];
											$la_bddestino[$i][3]   = $_SESSION["ls_database_destino"];
											$la_bddestino[$i][4]   = $_SESSION["ls_gestor_destino"];
											$i++;
									 }																
								  } // Si encontrado
								   unset($io_transferencia);								   
							  } //FIN DEL while	
							   //----en el caso que exista un error al insertar un comprobante se debe reversar---------------------
							   if (($i>0)&&(!$lb_valido_transferencia))
							   {    
							        $ls_valido_reverso=true;
									$ls_valido_rev_md=false;
									$ls_valido_trans=true;
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
																			
										$io_revtran=new sigesp_spg_c_transferencia($hostname_destino,$login_destino,$password_destino,$database_destino,$gestor_destino);
										global $io_revtran;
								        $ls_valido_reverso=$io_revtran->uf_reversar_transferencia_comprobantes(rtrim($ls_comprobante),rtrim($ls_fecha),
										                                                                           rtrim($ls_fecaprsol),rtrim($ls_procede),$la_seguridad);
										unset($io_revtran);//libero el objeto
								    }// fin del for																	
							   }// fin del if ($i>0)
								else
								{
								 unset($la_bddestino);
								}
							   //---------------------------------------------------------------------------------------------------						  
							   //----------------si el traspaso fue exitoso en todas las bases de datos-----------------------------
							   if ($lb_valido_transferencia === true)
							   {
							    	$lb_valido_update=$io_comprobantes->uf_update_estatus(rtrim($ls_comprobante),rtrim($ld_fecha),rtrim($ld_fecha),rtrim($ls_procede));
									if($lb_valido_update)
									{
									  $ls_evento="PROCESS";
									  $ls_desc_event="Ejecutó el proceso de Transferencia de  Asientos Presupuestarios y Contables desde la Base de Datos Origen ".$_SESSION["ls_database"]." hacia la Base de Datos Destino  ".$_SESSION["ls_database_destino"];
									  $ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
									}
							   }
							   //---------------------------------------------------------------------------------------------------									       
				       } // FIN DEL if($li_numrows > 0) 
					    //unset($io_transferencia);
					  } // End While Comprobantes	
					  
					 } // if numcmp > 0
					 if (($lb_valido_transferencia)&&($lb_valido_update))
				     {				 
					  $msg->message("Transferencia de Asientos Presupuestarios y Contables realizada con éxito !!!");
				     }
				     else
				     {
					  $msg->message("Error al realizar Transferencia de Asientos Presupuestarios y Contables !!!");
				     }
					}
					else
					{
					  $msg->message("No hay Asientos Presupuestarios y Contables por transferir !!!");
					}    	
							 
			}//FIN DEL IF PROCESAR_CS
			elseif($ls_operacion=="PROCESAR_UE")
		    {		
			        $lb_valido=false;
					$rs_data = ""; 
			        $lb_valido = $io_cmp->uf_select_comprobantes_spg_int($ld_fechades,$ld_fechahas,$rs_data);
					if($lb_valido)
					{ 
			         $li_numcmp=$io_sql->num_rows($rs_data);
					 if($li_numcmp > 0)			 
					 {
					  while($li_numcmp>0)
					  {
						  $fila=$io_sql->fetch_row($rs_data);
						  $li_numcmp=$li_numcmp-1;
						  $ls_comprobante    =rtrim($fila["comprobante"]);
						  $ld_fecha          =$fila["fecha"];
						  $ls_procede        =$fila["procede"];
						  $ls_codban         =$fila["codban"];
						  $ls_ctaban         =$fila["ctaban"];
						  $ls_dbdestino ="";
						  $i=0;	
						  $la_bddestino=array();						  				 
						  $lb_encontrado = uf_get_datos_bddestino($ls_basdatcon,$empresa);
						  if ($lb_encontrado)
						  {
							 $ls_registro = "";
							 $io_transferencia=new sigesp_spg_c_transferencia($_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"] ,$_SESSION["ls_database_destino"],$_SESSION["ls_gestor_destino"]);
							 $ls_codempdes = $io_transferencia->uf_obtener_codempresa_bd($_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"] ,$_SESSION["ls_database_destino"],$_SESSION["ls_gestor_destino"]);	
							 $lb_valido_transferencia = false;
							 $lb_valido_transferencia=$io_transferencia->uf_transferir_asipre($ls_codempdes,$ls_comprobante,$ld_fecha,$ls_procede,$ls_codban,$ls_ctaban,'UE');
							 if ($lb_valido_transferencia)
							 {
									$la_bddestino[$i][0]   = $_SESSION["ls_hostname_destino"];
									$la_bddestino[$i][1]   = $_SESSION["ls_login_destino"];
									$la_bddestino[$i][2]   = $_SESSION["ls_password_destino"];
									$la_bddestino[$i][3]   = $_SESSION["ls_database_destino"];
									$la_bddestino[$i][4]   = $_SESSION["ls_gestor_destino"];
									$i++;
							 }																
						  } // Si encontrado
						  else
						  {
						   $msg->message("Error en conexion con Base de Datos que Consolida, contacte a su Admnistrador de Sistema !!!");
						   $lb_valido_transferencia = false;
						  }
						   unset($io_transferencia);								   
						   //----en el caso que exista un error al insertar un comprobante se debe reversar---------------------
						   if (($i>0)&&(!$lb_valido_transferencia))
						   {    
								$ls_valido_reverso=true;
								$ls_valido_rev_md=false;
								$ls_valido_trans=true;
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
																		
									$io_revtran=new sigesp_spg_c_transferencia($hostname_destino,$login_destino,$password_destino,$database_destino,$gestor_destino);
									global $io_revtran;
									$ls_valido_reverso=$io_revtran->uf_reversar_transferencia_comprobantes(rtrim($ls_comprobante),rtrim($ls_fecha),
																											   rtrim($ls_fecaprsol),rtrim($ls_procede),$la_seguridad);
									unset($io_revtran);//libero el objeto
								}// fin del for																	
						    }// fin del if ($i>0)
							else
							{
							 unset($la_bddestino);
							}
						   //---------------------------------------------------------------------------------------------------						  
						   //----------------si el traspaso fue exitoso en todas las bases de datos-----------------------------
						   if ($lb_valido_transferencia === true)
						   {
								$lb_valido_update=$io_comprobantes->uf_update_estatus(rtrim($ls_comprobante),rtrim($ld_fecha),rtrim($ld_fecha),rtrim($ls_procede));
								if($lb_valido_update)
								{
								  $ls_evento="PROCESS";
							  	  $ls_desc_event="Ejecutó el proceso de Transferencia de  Asientos Presupuestarios y Contables desde la Base de Datos Origen ".$_SESSION["ls_database"]." hacia la Base de Datos Destino  ".$_SESSION["ls_database_destino"];
							      $ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
								}
						   }
						   //---------------------------------------------------------------------------------------------------									       
					//unset($io_transferencia);
				  } // End While Comprobantes	
					  
					 } // if numcmp > 0
					 if (($lb_valido_transferencia)&&($lb_valido_update))
				     {				 
					  $msg->message("Transferencia de Asientos Presupuestarios y Contables realizada con éxito !!!");
				     }
				     else
				     {
					  $msg->message("Error al realizar Transferencia de Asientos Presupuestarios y Contables !!!");
				     }
					}
					else
					{
					  $msg->message("No hay Asientos Presupuestarios y Contables por transferir !!!");
					}    	
							 
			}//FIN DEL IF PROCESAR_UE
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
         <td height="22" colspan="5" class="titulo-celdanew">TRANSFERENCIA DE ASIENTOS PRESUPUESTARIOS Y CONTABLES </td>
      </tr>                 
          
                 
          <tr>
            <td ><div align="right"></div></td>
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr style="display:none">
            <td ><div align="right">
                  <p>&nbsp;</p>
                  </div></td>
            <td colspan="4">
              <p>
                <input name="dbdestino" type="hidden" id="dbdestino" value="<?php print $ls_dbdestino;?>">
	       <input name="operacion" type="hidden" id="operacion" value="<?php print ($ls_operacion); ?>">
	       <input name="conexion" type="hidden" id="conexion" value="<?php print ($li_conexion); ?>">
              </p>            </td>
          </tr>
		  <tr>
            <td ><div align="right"></div></td>
            <td colspan="4">&nbsp;</td>
          </tr>
		  <tr>
        	 <td height="22" colspan="5" class="titulo-celdanew">Rango de Fecha </td>
     	 </tr>
		 <tr>
            <td width="141"><div align="right">Desde:</div></td>
            <td width="179"><input name="txtfechades" type="text" id="txtfechades" style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ld_fechades;?>" maxlength="10" datepicker="true">
              <a href="javascript:uf_catalogomod('Desde');"></a></td>
            <td width="47"><div align="right"><span class="style1 style14">Hasta</span></div></td>
            <td width="193"><input name="txtfechahas" type="text" id="txtfechahas" style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ld_fechahas;?>" maxlength="10" datepicker="true">
              <a href="javascript:uf_catalogomod('Hasta');"></a></td>
          </tr>
	  
		<tr>
          <td colspan="5">
		  	<div align="center"></div>		  		
			  <input name="hidrango" type="hidden" id="hidrango"> 
			  <input name="consolida" type="hidden" id="consolida" value="<?php print $li_consolida?>">
			  <input name="basdatcon" type="hidden" id="basdatcon" value="<?php print $ls_basdatcon?>">
			  <input name="codaltemp" type="hidden" id="codaltemp" value="<?php print $ls_codaltemp?>"></td>		  
         </tr>
        </table>
      </div></td>
    </tr>
  </table>
  <div id=transferir style="visibility:hidden" align="center"><img src="../shared/imagebank/cargando.gif" alt="procesando">Transfiriendo Asientos Presupuestarios y Contables... </div>
</form>      
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_procesar()
{
	f=document.form1;
	li_ejecutar  = f.ejecutar.value;
	ls_basdatcon = f.basdatcon.value;
	ls_codaltemp = f.codaltemp.value;
	li_consolida = f.consolida.value;
	if (li_ejecutar==1)
   	{
      if((li_consolida == 1)&&(ls_codaltemp!=""))
	  {
		  if(confirm("¿Está seguro de hacer el traspaso de los Asientos Presupuestarios y Contables?"))
		  {
		   f.operacion.value = "PROCESAR_CS";
		   f.action="sigesp_spg_p_trans_asipre.php";
		   f.submit();
		  } 
	  }
	  else
	  {
	   if((li_consolida == 0)&&(ls_basdatcon != "")&&(ls_codaltemp!=""))
	   {
	     if(confirm("¿Está seguro de hacer el Traspaso de los Asientos Presupuestarios y Contables?"))
		 {
		  f.operacion.value = "PROCESAR_UE";
		  f.action="sigesp_spg_p_trans_asipre.php";
		  f.submit();
		 }
	   }
	   else
	   {
	    alert("No se puede determinar si la Base de Datos Consolida o es Consolidadora, verifique la configuracion respectiva");
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

function ue_formatofecha(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++)
		{
			val2 += val[r]	
		}
		if(nums)
		{
			for(z=0;z<val2.length;z++)
			{
				if(isNaN(val2.charAt(z)))
				{
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++)
		{
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++)
		{
			if(q ==0)
			{
				val = val3[q]
			}
			else
			{
				if(val3[q] != "")
				{
					val += sep + val3[q]
				}
			}
		}
		d.value = val
		d.valant = val
	}
}
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>