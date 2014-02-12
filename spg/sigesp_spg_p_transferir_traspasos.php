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
	$io_fun_gasto->uf_load_seguridad("SPG","sigesp_spg_p_transferir_traspasos.php",$ls_permisos,$la_seguridad,$la_permisos);
  //////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

	require_once("../sigesp_config.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("sigesp_spg_c_transferencia.php");
	require_once("sigesp_spg_c_mod_presupuestarias.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad = new sigesp_c_seguridad;
	$io_comprobantes = new sigesp_spg_c_mod_presupuestarias;
    $in        = new sigesp_include();
	$con       = $in->uf_conectar();
	$io_sql    =new class_sql($con);
	$msg=new class_mensajes();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_sistema="SPG";
	$ls_ventana="sigesp_spg_p_transferir_traspasos.php";
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
	
	global $ls_operacion, $li_conexion, $ls_codcmpdes ,$ls_codcmphas, $ls_descripcion, $ld_fecha, $ld_fecsolapr, $io_comprobantes;	
	global $ld_fecha,$ld_fecaprob;

	$ld_fecha="";
	$ld_fecaprob="";	 
	$ls_procede="";
	$ls_operacion="";
	$ls_codcmpdes ="";
	$ls_codcmphas="";
	$ls_codempdes="";
	$ls_descripcion="";
	$ls_titletable="Solicitudes Modificación Presupuestaria Aprobadas";
	$li_widthtable=550;
	$ls_nametable="grid";
	$lo_title[1]="";
	$lo_title[2]="Nro. de Solicitud";
	$lo_title[3]="Procedencia";
	$lo_title[4]="Fecha de Solicitud";
	$lo_title[5]="Fecha de Aprobación";
	$lo_title[6]="Concepto";
	$lo_title[7]="Detalle";
	$li_totrows=$io_fun_gasto->uf_obtenervalor("totalfilas",1);

    function uf_cargar_dt($li_i)
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 04/08/2008 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_concepto,$ls_comprobante,$ls_fecha,$ls_fecaprsol,$ls_procede,$ls_selusu,$ls_codban,$ls_ctaban;
		
		$ls_concepto    = $_POST["txtconcepto".$li_i];
		$ls_comprobante = $_POST["txtcomprobante".$li_i];
		$ls_procede		= $_POST["txtprocede".$li_i];
		$ls_fecha		= $_POST["txtfecha".$li_i];
		$ls_fecaprsol   = $_POST["txtfecaprsol".$li_i];
		$ls_selusu		= $_POST["txtselusu".$li_i];	
		$ls_codban      = $_POST["hidcodban".$li_i];
		$ls_ctaban		= $_POST["hidctaban".$li_i];
   }
	
	function uf_agregarlineablanca(&$aa_object,$ai_totrows)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input type=checkbox name=selusu".$ai_totrows." id=selusu".$ai_totrows." onChange='javascript: cambiar_valor(".$ai_totrows.");'><input name=txtselusu".$ai_totrows." type=hidden id=txtselusu".$ai_totrows." readonly>";
		$aa_object[$ai_totrows][2] = "<input type=text name=txtcomprobante".$ai_totrows."   value=''      class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";
		$aa_object[$ai_totrows][3] = "<input name=txtprocede".$ai_totrows." type=text id=txtprocede".$ai_totrows." class=sin-borde  readonly style=text-align:center value='' size=15 maxlength=12>";
		$aa_object[$ai_totrows][4] = "<input type=text name=txtfecha".$ai_totrows."   value=''    class=sin-borde readonly style=text-align:center size=20 maxlength=10 >";
		$aa_object[$ai_totrows][5] = "<input type=text name=txtfecaprsol".$ai_totrows."   value=''    class=sin-borde readonly style=text-align:center size=20 maxlength=10 >";
		$aa_object[$ai_totrows][6] = "<input type=text name=txtconcepto".$ai_totrows."  value=''    class=sin-borde readonly style=text-align:left size=80 maxlength=250>";			
		$aa_object[$ai_totrows][7] = "<div align='center'><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></div>";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<title>Transferencia de Modificaciones Presupuestarias</title>
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
			
                <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de  Presupuesto de Gasto</td>
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
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif"  title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
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
    
 if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		if ($ls_operacion=="BUSCAR")
	    {
				//Realizo la conexion a la base de datos
				 $li_totrows=0;
				 if(array_key_exists("txtcodcmpdes",$_POST))
	 			 {
			 		$ls_codcmpdes = $_POST["txtcodcmpdes"];
				 }
				 else
				 {
			 		$ls_codcmpdes = "";
				 }
				 if(array_key_exists("txtcodcmphas",$_POST))
	 			 {
					$ls_codcmphas = $_POST["txtcodcmphas"];
				 }
				 else
				 {
					$ls_codcmphas = "";
				 }
				 if(array_key_exists("txtdescripcion",$_POST))
	 			 {
					$ls_descripcion= $_POST["txtdescripcion"];
				 }
				 else
				 {
					$ls_descripcion= "";
				 }
				
				 if(array_key_exists("txtfechadoc",$_POST))
	 			 {
					$ld_fecha= $_POST["txtfechadoc"];
				 }
				 else
				 {
					$ld_fecha= "";
				 }
				 
				 if(array_key_exists("txtfecsolapr",$_POST))
	 			 {
					$ld_fecsolapr= $_POST["txtfecsolapr"];
				 }
				 else
				 {
					$ld_fecsolapr= "";
				 }
		  
		  $lb_valido=$io_comprobantes->uf_select_comprobantes_spg($ls_codcmpdes,$ls_codcmphas,$ld_fecha,$ld_fecsolapr,$ls_descripcion,"",1,$lo_object,$li_totrows);
		
		}	
		elseif ($ls_operacion=="MOSTRAR")
	    {
			
		}
		else
		   { 
		     if ($ls_operacion=="PROCESAR")
		        {	
			      $lb_valido_transferencia = false;				 
				  for ($li_i=1;$li_i<=$li_totrows;$li_i++)//$li_totrows numeros de comprobantes
				      {
					    uf_cargar_dt($li_i);
					    $resultado  = $io_comprobantes->uf_cargar_bddestino();//busca todas las bases configuradas como consolidadoras										
					    $li_numrows = $io_sql->num_rows($resultado);
					    if ($li_numrows > 0)
					       {
						     $ls_dbdestino = "";
						     $i = 0;	
						     if ($ls_selusu=='1')
						        { 
						          $la_bddestino = array();
							      $lb_valido_transferencia = true;							  				 
							      while(($li_numrows>0)&&($lb_valido_transferencia))
							           {
								         $row = $io_sql->fetch_row($resultado);
								         $li_numrows--;
								         $ls_dbdestino  = trim($row["nombasdat"]);
								         $lb_encontrado = uf_get_datos_bddestino($ls_dbdestino,$empresa);
										 if ($lb_encontrado)
										    {
											  $ls_registro = "";
											  $io_transferencia = new sigesp_spg_c_transferencia($_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"] ,$_SESSION["ls_database_destino"],$_SESSION["ls_gestor_destino"]);
											  $ls_codempdes = $io_transferencia->uf_obtener_codempresa_bd($_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"] ,$_SESSION["ls_database_destino"],$_SESSION["ls_gestor_destino"]);	
											  $lb_valido_transferencia = false;
											  $lb_valido_transferencia = $io_transferencia->uf_transferir_comprobantes(rtrim($ls_codempdes),rtrim($ls_comprobante),rtrim($ls_fecha),rtrim($ls_fecaprsol),rtrim($ls_procede),$ls_codban,$ls_ctaban);
											  if ($lb_valido_transferencia)
											     {
												   if ($li_i < $li_totrows)
													  {
													    $ls_registro = $ls_registro." ".$ls_comprobante." "; 
													  }
												   else
													  {
													    $ls_registro = $ls_registro." ".$ls_comprobante.".";
													  }
											       //arreglo que guarda las bases de datos cuando la insersiòn es exitosa
												   $la_bddestino[$i][0]   = $_SESSION["ls_hostname_destino"];
												   $la_bddestino[$i][1]   = $_SESSION["ls_login_destino"];
												   $la_bddestino[$i][2]   = $_SESSION["ls_password_destino"];
												   $la_bddestino[$i][3]   = $_SESSION["ls_database_destino"];
												   $la_bddestino[$i][4]   = $_SESSION["ls_gestor_destino"];
												   $i++;
									             }																
								            }
								         else
										    {
										      $msg->message("La Base de datos ".$ls_dbdestino." no esta en el archivo de configuración.");
											  $lb_valido_transferencia=false;
										    }
								         unset($io_transferencia);								   
							           }
							      //----en el caso que exista un error al insertar un comprobante se debe reversar---------------------
							      if (($i>0)&&(!$lb_valido_transferencia))
							         {    
							           $ls_valido_reverso = true;
									   $ls_valido_rev_md  = false;
									   $ls_valido_trans   = true;
									   $hostname_destino  = "";  
									   $login_destino     = "";   
									   $password_destino  = "";   
									   $database_destino  = ""; 
									   $gestor_destino    = "";
									   for ($ls_j=0;$ls_j<$i;$ls_j++)
									       {  
											 $hostname_destino = $la_bddestino[$ls_j][0];  
											 $login_destino    = $la_bddestino[$ls_j][1];   
											 $password_destino = $la_bddestino[$ls_j][2];   
											 $database_destino = $la_bddestino[$ls_j][3];  
											 $gestor_destino   = $la_bddestino[$ls_j][4];
																			
										     $io_revtran = new sigesp_spg_c_transferencia($hostname_destino,$login_destino,$password_destino,$database_destino,$gestor_destino);
										     global $io_revtran;
										     // elimina los comprobante de modificaciòn
										     $ls_valido_rev_md = $io_revtran->uf_reversar_transferencia_comprobantes_md(rtrim($ls_comprobante),rtrim($ls_fecha),
																												        rtrim($ls_fecaprsol),rtrim($ls_procede),$la_seguridad);											
										     if ($ls_valido_rev_md)
												{  
										          //elimina los comprobantes en sigesp_cmp
											      $ls_valido_reverso = $io_revtran->uf_reversar_transferencia_comprobantes(rtrim($ls_comprobante),rtrim($ls_fecha),
										                                                                                   rtrim($ls_fecaprsol),rtrim($ls_procede),$ls_codban,$ls_ctaban,$la_seguridad);
										          if (!$ls_valido_reverso)
													 {   
													   $ls_valido_trans=$io_revtran->uf_transferir_comprobantes_md(rtrim($ls_codempdes),rtrim($ls_comprobante),rtrim($ls_fecha),rtrim($ls_fecaprsol),rtrim($ls_procede));
													   if (!$ls_valido_trans)
													      {
														    $msg->message("Error al realizar Transferencia de Modificaciones Presupuestarias de Reverso!!!");
													      }
													 }
 										        }
										     unset($io_revtran);//libero el objeto
									       }// fin del for																	
								     }
								  else
								     {
								       unset($la_bddestino);
								     }
							      //---------------------------------------------------------------------------------------------------						  
							      //----------------si el traspaso fue exitoso en todas las bases de datos-----------------------------
							      if ($lb_valido_transferencia === true)
							         {
							    	$lb_valido_update=$io_comprobantes->uf_update_estatus(rtrim($ls_comprobante),rtrim($ls_fecha),rtrim($ls_fecaprsol),rtrim($ls_procede),$as_codban,$as_ctaban);
									$ls_evento="PROCESS";
									$ls_desc_event="Ejecutó el proceso de Transferencia de  Modificaciones Presupuestarias desde la Base de Datos Origen ".$_SESSION["ls_database"]." hacia la Base de Datos Destino  ".$_SESSION["ls_database_destino"]." , se transfirieron las siguientes solicitudes : ".$ls_registro;
									$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
							      }
						        }
				           }
				      }
				  if (($lb_valido_transferencia)&&($lb_valido_update))
				     {				 
					   $msg->message("Transferencia de Modificaciones Presupuestarias realizada con éxito !!!");
				     }
				  else
				     {
					   $msg->message("Error al realizar Transferencia de Modificaciones Presupuestarias !!!");
				     } 	
			    }
			 $io_comprobantes->uf_select_comprobantes_spg($ls_codcmpdes,$ls_codcmphas,$ld_fecha,$ld_fecsolapr,$ls_descripcion,"",1,$lo_object,$li_totrows);
           }
    }
 else
    {
      uf_agregarlineablanca($lo_object,1);
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
         <td height="22" colspan="5" class="titulo-celdanew">TRANSFERENCIA DE SOLICITUDES DE MODIFICACION PRESUPUESTARIA APROBADAS </td>
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
        	 <td height="22" colspan="5" class="titulo-celdanew">Par&aacute;metros de B&uacute;squeda</td>
     	 </tr>
		 <tr>
            <td width="118"><div align="right">Nro. de Solicitud Desde </div></td>
            <td width="130"><input name="txtcodcmpdes" type="text" id="txtcodcmpdes" value="<?php print $ls_codcmpdes?>" size="20" maxlength="15"  style="text-align:center ">
              <a href="javascript:uf_catalogomod('Desde');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" onClick="document.form1.hidrango.value=5"></a></td>
            <td width="36"><div align="right"><span class="style1 style14">Hasta</span></div></td>
            <td width="148"><input name="txtcodcmphas" type="text" id="txtcodcmphas" value="<?php print $ls_codcmphas?>" size="20" maxlength="15"  style="text-align:center">
              <a href="javascript:uf_catalogomod('Hasta');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"  onClick="document.form1.hidrango.value=2"></a></td>
          </tr>
            <tr>
              <td><div align="right">Concepto</div></td>
              <td colspan="4"><input name="txtdescripcion" type="text" id="txtdescripcion" value="<?php print $ls_descripcion ?>" size="60" maxlength="100"  style="text-align:left "  ></td>
            </tr>
            <tr>
              <td><div align="right">Fecha de Solicitud </div></td>
              <td colspan="4"><input name="txtfechadoc" type="text" id="txtfechadoc" style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ld_fecha;?>" maxlength="10" datepicker="true"></td>
            </tr>
            <tr>
              <td><div align="right">Fecha de Aprobaci&oacute;n: </div></td>
              <td colspan="4"><input name="txtfecsolapr" type="text" id="txtfecsolapr" style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ld_fecsolapr;?>" maxlength="10" datepicker="true"></td>
            </tr>
		 <tr>
           <td height="22"><div align="right"></div></td>
		   <td height="22"><div align="right"></div></td>
           <td colspan="3" align="right"><span class="toolbar"><a href="javascript: ue_buscar();">
		    <img src="../shared/imagebank/tools20/buscar.gif"  width="20" height="20" border="0">Buscar Solicitudes </a></span></td>
          </tr>
		  <tr>
        	 <td height="22" colspan="5" class="titulo-celdanew">&nbsp;</td>
     	 </tr>
	 	 <tr>
           <td height="22"><div align="right"></div></td>
           
			<td width="130"></td>
			<td width="36">&nbsp;</td>
			 
            <td width="148"><a href="javascript: deseleccionar_todos();"><span class="toolbar"><a href="javascript: seleccionar_todos();"><img src="../shared/imagebank/tools20/aprobado.gif"  width="20" height="20" border="0">Seleccionar Todas</a><a href="javascript: deseleccionar_todos();"></a></td>
	        <td width="126"><a href="javascript: deseleccionar_todos();"><img src="../shared/imagebank/tools20/eliminar.gif" width="20" height="20" border="0">Deseleccionar Todas</a></td>
	 	 </tr>
	  
		<tr>
          <td colspan="5">
		  	<div align="center">
			<?php
				require_once("../shared/class_folder/grid_param.php");
				$io_grid=new grid_param();
				if(empty($lo_object))
				{
				 uf_agregarlineablanca($lo_object,1);
				}
				$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
				unset($io_grid);
			?>
			  </div>		  	
            
			  <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">	
			  <input name="hidrango" type="hidden" id="hidrango"> <input name="hidconsolida" type="hidden" id="hidconsolida"></td>		  
         </tr>
        </table>
      </div></td>
    </tr>
  </table>
</form>      
</body>
<script language="javascript">
f = document.form1;
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_procesar()
{
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		 if ((f.totalfilas.value==1)&&(f.txtcomprobante1.value == ""))
		 {
		  alert("No ha seleccionado solicitudes por procesar !!!");
		 }
		 else
		 {
		  f.operacion.value = "PROCESAR";
		  f.action="sigesp_spg_p_transferir_traspasos.php";
		  f.submit();
		 } 

   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_conectar()
{
	if (f.txtbddestino.value == "")
	{
     alert("Debe seleccionar la Base de Datos Destino !!!");
	}
	else
	{
	 if (f.txtbdorigen.value != f.txtbddestino.value)
	 {
	 f.operacion.value = "CREARCONEXION";
     f.action="sigesp_spg_p_transferir_traspasos.php";
	 f.submit();
	 }
	 else
	 {
	  alert("La Base de Datos Destino y la Base de Datos Origen deben ser diferentes !!!");
	 }
	} 
}

function cambiar_valor (li_i)
{
	sel= eval ('document.form1.selusu'+li_i);	
	if (sel.checked)
	{
		selpro = eval ('document.form1.txtselusu'+li_i);	
		selpro.value = '1';
	}	
	else
	{
		selpro = eval ('document.form1.txtselusu'+li_i);	
		selpro.value = '0';
	}
}

function seleccionar_todos()
{	
	li_total = f.totalfilas.value;
	for(li_i=1;li_i<=li_total;li_i++)
	{
	  ch= eval ('document.form1.selusu'+li_i);
	  ch.checked=true;
	  selpro = eval ('document.form1.txtselusu'+li_i);	
	  selpro.value = '1';
	}
}

function deseleccionar_todos()
{	
	li_total = f.totalfilas.value;
	for(li_i=1;li_i<=li_total;li_i++)
	{
	  ch= eval ('document.form1.selusu'+li_i);	
	  ch.checked=false;
	  selpro = eval ('document.form1.txtselusu'+li_i);	
	  selpro.value = '0';
	}
}

function ue_buscar()
{	
	f.operacion.value="BUSCAR";
	f.action="sigesp_spg_p_transferir_traspasos.php";
	f.submit();
}

function uf_catalogomod(ls_destino)
{
  pagina="sigesp_cat_comprobantes_modificaciones.php?destino="+ls_destino+"";
  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no");
}

function uf_catalogobd()
{
  pagina = "sigesp_spg_cat_consolidacion.php";
  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
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

function uf_verdetalle(codcom,procede)
{
	Xpos=((screen.width/2)-(500/2)); 
	Ypos=((screen.height/2)-(400/2));
	window.open("sigesp_spg_pdt_spg.php?codcom="+codcom+"&procede="+procede+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=500,height=400,left="+Xpos+",top="+Ypos+",location=no,resizable=no");
}
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>