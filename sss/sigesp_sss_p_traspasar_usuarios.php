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
	require_once("class_funciones_seguridad.php");
	$io_fun_seguridad=new class_funciones_seguridad();
	$io_fun_seguridad->uf_load_seguridad("SSS","sigesp_sss_p_traspasar_usuarios.php",$ls_permisos,$la_seguridad,$la_permisos);
  //////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

	require_once("../sigesp_config.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("sigesp_sss_c_usuarios.php");
	$io_usuario=new sigesp_sss_c_usuarios();
    $in        = new sigesp_include();
	$con       = $in->uf_conectar();
	$msg=new class_mensajes();
	
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
	
	global $ls_operacion, $li_conexion, $ls_codusudes,$ls_codusuhas, $ls_codusu, $ls_cedusu, $ls_apeusu, $ls_nomusu,$io_fun_seguridad, $io_usuario;	
	global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title, $ls_codempdes;
	$ls_operacion="";
	$ls_codusudes="";
	$ls_codusuhas="";
	$ls_codempdes="";
	$ls_codusu="";
	$ls_cedusu="";
	$ls_nomusu="";
	$ls_apeusu="";
	$ls_titletable="Usuarios";
	$li_widthtable=550;
	$ls_nametable="grid";
	$lo_title[1]="Código";
	$lo_title[2]="Cédula";
	$lo_title[3]="Nombre";
	$lo_title[4]="Apellido";
	$lo_title[5]="Seleccionar";
	$li_totrows=$io_fun_seguridad->uf_obtenervalor("totalfilas",1);
	
	
//---------------------------------------------------------------------------------------------------------------------------
   function uf_cargar_dt($li_i)
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 04/08/2008 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codusu,$ls_cedusu,$ls_nomusu,$ls_apeusu,$ls_selusu;

		$ls_codusu=$_POST["txtcodusu".$li_i];
		$ls_cedusu=$_POST["txtcedusu".$li_i];
		$ls_nompro=$_POST["txtnomusu".$li_i];
		$ls_apeusu=$_POST["txtapeusu".$li_i];
		$ls_selusu=$_POST["txtselusu".$li_i];
		
   }
	
//---------------------------------------------------------------------------------------------------------------------------	
	
	function uf_agregarlineablanca(&$aa_object,$ai_totrows)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtcodusu".$ai_totrows." type=text id=txtcodusu".$ai_totrows." class=sin-borde size=12 maxlength=10 readonly >";
		$aa_object[$ai_totrows][2]="<input name=txtcedusu".$ai_totrows." type=text id=txtcedusu".$ai_totrows." class=sin-borde size=12 maxlength=10 readonly >";
		$aa_object[$ai_totrows][3]="<input name=txtnomusu".$ai_totrows." type=text id=txtnomusu".$ai_totrows." maxlength=100 class=sin-borde size=60  readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtapeusu".$ai_totrows." type=text id=txtapeusu".$ai_totrows." class=sin-borde size=60 maxlength=100  readonly>";
		$aa_object[$ai_totrows][5]="<input type=checkbox name=selusu".$ai_totrows." id=selusu".$ai_totrows." onChange='javascript: cambiar_valor(".$ai_totrows.");' ><input name=txtselusu".$ai_totrows." type=hidden id=txtselusu".$ai_totrows." readonly>";
				
   }
	
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
<title >Transferencia de Usuarios y Permisolog&iacute;a</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<link href="css/rpc.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>
<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="10" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="10" bgcolor="#E7E7E7" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Seguridad </td>
			  <td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </table></td>
  </tr>
  <tr>
    <td height="20" colspan="10" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="10" class="toolbar"></td>
  </tr>
  <tr>
    <td width="25" height="20" class="toolbar"><div align="center"><a href="javascript:ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Transferir personal..." name="Transferir" width="20" height="20" border="0" id="Transferir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"></a><a href="javascript: ue_ayuda();"></a><a href="javascript: ue_cerrar();"></a><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="22" height="20" border="0"></a></div></td>
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
		if ($ls_operacion=="BUSCAR")
	    {
				//Realizo la conexion a la base de datos
				 $li_totrows=0;
				 if(array_key_exists("txtcodusudes",$_POST))
	 			 {
			 		$ls_codusudes = $_POST["txtcodusudes"];
				 }
				 else
				 {
			 		$ls_codusudes = "";
				 }
				 if(array_key_exists("txtcodusuhas",$_POST))
	 			 {
					$ls_codusuhas = $_POST["txtcodusuhas"];
				 }
				 else
				 {
					$ls_codusuhas = "";
				 }
				 if(array_key_exists("txtcodusu",$_POST))
	 			 {
					$ls_codusu = $_POST["txtcodusu"];
				 }
				 else
				 {
					$ls_codusu = "";
				 }
				
				 if(array_key_exists("txtcedusu",$_POST))
	 			 {
					$ls_cedusu = $_POST["txtcedusu"];
				 }
				 else
				 {
					$ls_cedusu = "";
				 }
				
				 if(array_key_exists("txtnomusu",$_POST))
	 			 {
					$ls_nomusu = $_POST["txtnomusu"];
				 }
				 else
				 {
					$ls_nomusu = "";
				 }
				 
				 if(array_key_exists("txtapeusu",$_POST))
	 			 {
					$ls_apeusu = $_POST["txtapeusu"];
				 }
				 else
				 {
					$ls_apeusu = "";
				 }
				 							
			    $lb_valido=$io_usuario->uf_consultar_usuarios($ls_codusudes,$ls_codusuhas,$ls_codusu,$ls_cedusu,$ls_nomusu,$ls_apeusu,$li_totrows,$lo_object);
		
		}	
		elseif ($ls_operacion=="MOSTRAR")
	    {
			$posicion=$_POST["cmbdb"]; 
			//Realizo la conexion a la base de datos
				for($li_i=1;$li_i<=$li_totrows;$li_i++)
				{
					uf_cargar_dt($li_i);
					$lo_object[$li_i][1]="<input name=txtcodusu".$li_i." type=text id=txtcodusu".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_codusu."' readonly >";
					$lo_object[$li_i][2]="<input name=txtcedusu".$li_i." type=text id=txtcedusu".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_cedusu."' readonly >";
					$lo_object[$li_i][3]="<input name=txtnomusu".$li_i." type=text id=txtnomusu".$li_i." maxlength=100 class=sin-borde size=60 value='".$ls_nomusu."' readonly>";
					$lo_object[$li_i][4]="<input name=txtapeusu".$li_i." type=text id=txtapeusu".$li_i." class=sin-borde size=60 maxlength=100 value='".$ls_apeusu."'  readonly>";
					$lo_object[$li_i][5]="<input type=checkbox name=selusu".$li_i." id=selusu".$li_i." onChange='javascript: cambiar_valor(".$li_i.");'><input name=txtselusu".$li_i." type=hidden id=txtselusu".$li_i." value='".$ls_selusu."' readonly>";
				}
			
		}
		else
		{ 
		    if($ls_operacion=="PROCESAR")
		    {	
				    $lb_valido_transferencia = false;
					$lb_valido = $io_usuario->uf_obtener_codempresa_bd($_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"] ,$_SESSION["ls_database_destino"],$_SESSION["ls_gestor_destino"],$ls_codempdes);	
					for($li_i=1;$li_i<=$li_totrows;$li_i++)
					{
						uf_cargar_dt($li_i);
						$lo_object[$li_i][1]="<input name=txtcodusu".$li_i." type=text id=txtcodusu".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_codusu."' readonly >";
						$lo_object[$li_i][2]="<input name=txtcedusu".$li_i." type=text id=txtcedusu".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_cedusu."' readonly >";
						$lo_object[$li_i][3]="<input name=txtnomusu".$li_i." type=text id=txtnomusu".$li_i." maxlength=100 class=sin-borde size=60 value='".$ls_nomusu."' readonly>";
						$lo_object[$li_i][4]="<input name=txtapeusu".$li_i." type=text id=txtapeusu".$li_i." class=sin-borde size=60 maxlength=100 value='".$ls_apeusu."'  readonly>";
						$lo_object[$li_i][5]="<input type=checkbox name=selusu".$li_i." id=selusu".$li_i." onChange='javascript: cambiar_valor(".$li_i.");'><input name=txtselusu".$li_i." type=hidden id=txtselusu".$li_i." value='".$ls_selusu."' readonly>";
						
						if ($ls_selusu=='1')
						{
							$lb_valido_transferencia=$io_usuario->uf_transferir_usuarios($ls_codempdes,$ls_codusu, $_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"] ,$_SESSION["ls_database_destino"],$_SESSION["ls_gestor_destino"]);
							
						}	 // Fin del if
					} // Fin del For 
					if ($lb_valido_transferencia)
					{
				 
					  $msg->message("Transferencia de Usuarios realizada con éxito !!!");
					}
					else
					{
					  $msg->message("Error al realizar Transferencia de Usuarios !!!");
					}
			}		
		    else
		    {
				if ($ls_operacion == "CREARCONEXION")
				{
				  $lb_conexion = uf_get_datos_bddestino($ls_dbdestino,$empresa);
				  if ($lb_conexion)
				  {
				   $li_conexion = 1;
				   $msg->message("La Conexión con la Base de Datos Destino es Correcta !!!");
				  }
				}
		    } 
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
	$io_fun_seguridad->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_seguridad);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
 
  <table width="200" border="0" align="center">
    <tr>
      <td><div align="center">
        <table width="570" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
         <td height="22" colspan="5" class="titulo-celdanew">TRANSFERIR USUARIOS Y PERMISOLOG&Iacute;A </td>
      </tr>                 
          
                 
          <tr>
            <td ><div align="right"></div></td>
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr class="titulo-celdanew">
            <td height="22" colspan="5" ><div align="center"> Base de Datos Origen/ Destino </div></td>
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
		    <td ><div align="left"><strong>Base de Datos Origen: </strong></div></td>
		    <td colspan="4"><input name="txtbdorigen" type="text" class="sin-borde" id="txtbdorigen" style="text-align:left " value="<?PHP print $_SESSION["ls_database"];?>" size="30" maxlength="100" ></td>
		    </tr>
		  <tr>
		    <td ><div align="left"><strong>Base de Datos Destino:</strong> </div></td>
		    <td colspan="4"><input name="txtbddestino" type="text" id="txtbddestino" size="30" maxlength="100"  style="text-align:left"  readonly="true" value="<?php print $ls_dbdestino; ?>">
		      <a href="javascript:uf_catalogobd();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" onClick="document.form1.hidrango.value=5"></a><a href="javascript:ue_conectar();"><img src="../shared/imagebank/tools15/actualizar(1).gif" alt="" width="15" height="15" border="0"></a></td>
		    </tr>
		  <tr>
            <td ><div align="right"></div></td>
            <td colspan="4">&nbsp;</td>
          </tr>
		  <tr>
        	 <td height="22" colspan="5" class="titulo-celdanew">Par&aacute;metros de B&uacute;squeda</td>
     	 </tr>
		 <tr>
            <td width="99"><div align="right"><span class="style1 style14">Desde</span></div></td>
            <td width="100"><input name="txtcodusudes" type="text" id="txtcodusudes" value="<?php print $ls_codusudes?>" size="12" maxlength="10"  style="text-align:center ">
              <a href="javascript:uf_catalogousu('Desde');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" onClick="document.form1.hidrango.value=5"></a></td>
            <td width="50"><div align="right"><span class="style1 style14">Hasta</span></div></td>
            <td width="169"><input name="txtcodusuhas" type="text" id="txtcodusuhas" value="<?php print $ls_codusuhas?>" size="12" maxlength="10"  style="text-align:center">
              <a href="javascript:uf_catalogousu('Hasta');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"  onClick="document.form1.hidrango.value=2"></a></td>
          </tr>
            <tr>
              <td><div align="right">Login</div></td>
              <td colspan="4"><input name="txtcodusu" type="text" id="txtcodusu" value="<?php print $ls_codusu ?>" size="60" maxlength="100"  style="text-align:left "  ></td>
            </tr>
            <tr>
              <td><div align="right">Cedula</div></td>
              <td colspan="4"><input name="txtcedusu" type="text" id="txtcedusu" value="<?php print $ls_cedusu ?>" size="15"  style="text-align:left "  ></td>
            </tr>
            <td width="99"><div align="right"><span class="style1 style14">Nombre</span></div></td>
            <td colspan="4"><input name="txtnomusu" type="text" id="txtnomusu" value="<?php print $ls_nomusu ?>" size="60" maxlength="100"  style="text-align:left "  ></td>
		 </tr>
		 <tr>
            <td width="99"><div align="right"><span class="style1 style14">Apellido</span></div></td>
            <td colspan="4"><input name="txtapeusu" type="text" id="txtapeusu" value="<?php print $ls_apeusu ?>" size="60" maxlength="100"  style="text-align:left "  ></td>
		 </tr>
		 <tr>
           <td height="22"><div align="right"></div></td>
		   <td height="22"><div align="right"></div></td>
           <td colspan="3" align="right"><span class="toolbar"><a href="javascript: ue_buscar();">
		    <img src="../shared/imagebank/tools20/buscar.gif"  width="20" height="20" border="0">Buscar Usuarios </a></span></td>
          </tr>
		  <tr>
        	 <td height="22" colspan="5" class="titulo-celdanew">Datos de los Usuarios </td>
     	 </tr>
	 	 <tr>
           <td height="22"><div align="right"></div></td>
           
			<td width="100"></td>
			<td width="50">&nbsp;</td>
			 
            <td width="169"><a href="javascript: deseleccionar_todos();"><span class="toolbar"><a href="javascript: seleccionar_todos();"><img src="../shared/imagebank/tools20/aprobado.gif"  width="20" height="20" border="0">Seleccionar Todos</a><a href="javascript: deseleccionar_todos();"></a></td>
	        <td width="140"><a href="javascript: deseleccionar_todos();"><img src="../shared/imagebank/tools20/eliminar.gif" width="20" height="20" border="0">Deseleccionar Todos</a></td>
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
			  <input name="hidrango" type="hidden" id="hidrango"> </td>		  
         </tr>
        </table>
      </div></td>
    </tr>
  </table>
</form>      
</body>
<script language="javascript">

function ue_procesar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		if(f.conexion.value == 1)
		{
		 f.operacion.value = "PROCESAR";
		 f.action="sigesp_sss_p_traspasar_usuarios.php";
		 f.submit();
		}
		else
		{
		 alert("No se ha creado conexion con la Base de Datos Destino !!!");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_conectar()
{
	f=document.form1;
	if (f.txtbddestino.value == "")
	{
     alert("Debe seleccionar la Base de Datos Destino !!!");
	}
	else
	{
	 if (f.txtbdorigen.value != f.txtbddestino.value)
	 {
	 f.operacion.value = "CREARCONEXION";
     f.action="sigesp_sss_p_traspasar_usuarios.php";
	 f.submit();
	 }
	 else
	 {
	  alert("La Base de Datos Destino y la Base de Datos Origen deben ser diferentes !!!");
	 }
	} 
}

function selec()
{	
	f=document.form1;
	f.operacion.value="MOSTRAR";	
	f.dbdestino.value=f.txtbddestino.value;
	f.action="sigesp_sss_p_traspasar_usuarios.php";
	f.submit();
}

function cambiar_valor (li_i)
{
	f=document.form1;
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
	f=document.form1;
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
	f=document.form1;
	li_total = f.totalfilas.value;
	for(li_i=1;li_i<=li_total;li_i++)
	{
	  ch= eval ('document.form1.selusu'+li_i);	
	  ch.checked=false;
	  selpro = eval ('document.form1.txtselusu'+li_i);	
	  selpro.value = '1';
	}
}

function ue_buscar()
{	
	f=document.form1;
	
	if (f.txtbddestino.value=="")
	{
		alert ('Debe Seleccionar una Base de Datos Destino');
	}
	else
	{
	    if(f.conexion.value == 1)
		{
			f.operacion.value="BUSCAR";	
			f.dbdestino.value=f.txtbddestino.value;
			f.action="sigesp_sss_p_traspasar_usuarios.php";
			f.submit();
		}
		else
		{
		 alert ('No se ha verificado conexión a Base de Datos Destino');
		}	
	}
}

function uf_catalogousu(ls_destino)
{
    f=document.form1;
    pagina="sigesp_sss_cat_usuarios.php?destino="+ls_destino+"";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}

function uf_catalogobd()
{
    f=document.form1;
    pagina="sigesp_sss_cat_consolidacion.php";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}

function ue_cerrar()
{
   location.href='../index_modules.php' 
}
</script> 
</html>