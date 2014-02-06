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
	require_once("class_folder/class_funciones_rpc.php");
	$io_fun_rpc=new class_funciones_rpc();
	$io_fun_rpc->uf_load_seguridad("RPC","sigesp_rpc_p_cambioestatus_proveedor.php",$ls_permisos,$la_seguridad,$la_permisos);
  //////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	
	
	require_once("../sigesp_config.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("class_folder/sigesp_rpc_c_proveedor.php");
	require_once("../srh/class_folder/utilidades/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_proveedor=new sigesp_rpc_c_proveedor();
    $io_conect       = new sigesp_include();
	$con             = $io_conect-> uf_conectar ();
	$msg=new class_mensajes();
	
	if(array_key_exists("dbdestino",$_POST))
	{
	  $ls_dbdestino=$_POST["dbdestino"];     	
	}
	else
	{
	  $ls_dbdestino="";
	}		
	
	$_SESSION["ls_data_des"] = $ls_dbdestino;
	
	
	global $ls_operacion, $ls_codprov1,$ls_codprov2, $ls_rifprov, $ls_nomprov,$io_fun_nomina, $io_proveedor;	
	global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	$ls_operacion="";
	$ls_codprov1="";
	$ls_codprov2="";
	$ls_nomprov="";
	$ls_rifprov="";
	$ls_titletable="Proveedores";
	$li_widthtable=550;
	$ls_nametable="grid";
	$lo_title[1]="Código";
	$lo_title[2]="Denominación";
	$lo_title[3]="RIF";
	$lo_title[4]="Seleccionar";
	$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
	
	
//---------------------------------------------------------------------------------------------------------------------------
   function uf_cargar_dt($li_i)
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 27/11/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codpro,$ls_nompro,$ls_rifpro,$ls_selpro;

		$ls_codpro=$_POST["txtcodprov".$li_i];
		$ls_nompro=$_POST["txtdenprov".$li_i];
		$ls_rifpro=$_POST["txtrifprov".$li_i];
		$ls_selpro=$_POST["txtselprov".$li_i];
		
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
		$aa_object[$ai_totrows][1]="<input name=txtcodprov".$ai_totrows." type=text id=txtcodprov".$ai_totrows." class=sin-borde size=12 maxlength=10 readonly >";
		$aa_object[$ai_totrows][2]="<input name=txtdenprov".$ai_totrows." type=text id=txtdenprov".$ai_totrows." maxlength=100 class=sin-borde size=60  readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtrifprov".$ai_totrows." type=text id=txtrifprov".$ai_totrows." class=sin-borde size=17 maxlength=15  readonly>";
		$aa_object[$ai_totrows][4]="<input type=checkbox name=selprov".$ai_totrows." id=selprov".$ai_totrows." onChange='javascript: cambiar_valor(".$ai_totrows.");' ><input name=txtselprov".$ai_totrows." type=hidden id=txtselprov".$ai_totrows." readonly>";
				
   }
	
//---------------------------------------------------------------------------------------------------------------------------
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
	  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<title >Transferir Proveedores</title>
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
<link href="../shared/css/general.css" rel="stylesheet" type="text/css"></head>
<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="10" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="10" bgcolor="#E7E7E7" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Proveedores y Beneficiarios</td>
			<td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
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
    <td width="25" height="20" class="toolbar"><div align="center"><a href="javascript:ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Procesar" title="Procesar" name="Transferir" width="20" height="20" border="0" id="Transferir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"></a><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
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
	function uf_conectar_destino() 
	{
		global $msg;	

		if (strtoupper($_SESSION["ls_gestor_destino"])==strtoupper("mysql"))
		{ 
		    $conec = @mysql_connect($_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"]);						
			if($conec===false)
			{
				$msg->message("No pudo conectar con el servidor de datos MYSQL,".$_SESSION["ls_hostname_destino"]." , contacte al administrador del sistema");	
			}
			else
			{			    
				$lb_ok=@mysql_select_db(trim($_SESSION["ls_database_destino"]),$conec);
				if (!$lb_ok)
				{
					$msg->message("No existe la base de datos ".$_SESSION["ls_database_destino"]);					
				}
			}
		return $conec;
		}		
		if(strtoupper($_SESSION["ls_gestor_destino"])==strtoupper("postgre"))
		{
			$conec = @pg_connect("host=".$_SESSION["ls_hostname_destino"]." port=".$_SESSION["ls_port_destino"]."  dbname=".$_SESSION["ls_database_destino"]." user=".$_SESSION["ls_login_destino"]." password=".$_SESSION["ls_password_destino"]); 
		    
			if (!$conec)
			{
				$msg->message("No pudo conectar al servidor de base de datos POSTGRES, contacte al administrador del sistema");				
			}
      	 return $conec;
	    }		
	}// fin de function uf_conectar_destino() 	
		
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
			

		if ($ls_operacion=="BUSCAR")
	    {
			$posicion=$_POST["cmbdb"];
			//$posicion=$posicion+1; 
			//Realizo la conexion a la base de datos
			if($posicion!="") 
			{
				$_SESSION["ls_database_destino"] = $empresa["database"][$posicion];							
				$_SESSION["ls_hostname_destino"] = $empresa["hostname"][$posicion];
				$_SESSION["ls_login_destino"]    = $empresa["login"][$posicion];
				$_SESSION["ls_password_destino"] = $empresa["password"][$posicion];
				$_SESSION["ls_gestor_destino"]   = $empresa["gestor"][$posicion];	
				$_SESSION["ls_port_destino"]     = $empresa["port"][$posicion];	
				$_SESSION["ls_width_destino"]    = $empresa["width"][$posicion];
				$_SESSION["ls_height_destino"]   = $empresa["height"][$posicion];	
				$_SESSION["ls_logo_destino"]     = $empresa["logo"][$posicion];	
				$li_totrows=0;
				if(array_key_exists("txtcodprov",$_POST))
	 			{
					$ls_codprov1 = $_POST["txtcodprov"];
				}
				else
				{
					$ls_codprov1 = "";
				}
				if(array_key_exists("txtcodprov2",$_POST))
	 			{
					$ls_codprov2 = $_POST["txtcodprov2"];
				}
				else
				{
					$ls_codprov2 = "";
				}
				if(array_key_exists("txtnomprov",$_POST))
	 			{
					$ls_nomprov = $_POST["txtnomprov"];
				}
				else
				{
					$ls_nomprov = "";
				}
				if(array_key_exists("txtrifprov",$_POST))
	 			{
					$ls_rifprov = $_POST["txtrifprov"];
				}
				else
				{
					$ls_rifprov = "";
				}
								
			    $lb_valido=$io_proveedor->uf_consultar_proveedores ($ls_codprov1,$ls_codprov2,$ls_nomprov,$ls_rifprov,$li_totrows,$lo_object);
				
			}	
			
			
			
		}	
		else if ($ls_operacion=="MOSTRAR")
	    {
			$posicion=$_POST["cmbdb"];
			//$posicion=$posicion+1; 
			//Realizo la conexion a la base de datos
			if($posicion!='') 
			{
				$_SESSION["ls_database_destino"] = $empresa["database"][$posicion];							
				$_SESSION["ls_hostname_destino"] = $empresa["hostname"][$posicion];
				$_SESSION["ls_login_destino"]    = $empresa["login"][$posicion];
				$_SESSION["ls_password_destino"] = $empresa["password"][$posicion];
				$_SESSION["ls_gestor_destino"]   = $empresa["gestor"][$posicion];	
				$_SESSION["ls_port_destino"]     = $empresa["port"][$posicion];	
				$_SESSION["ls_width_destino"]    = $empresa["width"][$posicion];
				$_SESSION["ls_height_destino"]   = $empresa["height"][$posicion];	
				$_SESSION["ls_logo_destino"]     = $empresa["logo"][$posicion];
				for($li_i=1;$li_i<=$li_totrows;$li_i++)
				{
					uf_cargar_dt($li_i);
					$lo_object[$li_i][1]="<input name=txtcodprov".$li_i." type=text id=txtcodprov".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_codpro."' readonly >";
					$lo_object[$li_i][2]="<input name=txtdenprov".$li_i." type=text id=txtdenprov".$li_i." maxlength=100 class=sin-borde size=60 value='".$ls_nompro."' readonly>";
					$lo_object[$li_i][3]="<input name=txtrifprov".$li_i." type=text id=txtrifprov".$li_i." class=sin-borde size=17 maxlength=15 value='".$ls_rifpro."'  readonly>";
					$lo_object[$li_i][4]="<input type=checkbox name=selprov".$li_i." id=selprov".$li_i." onChange='javascript: cambiar_valor(".$li_i.");'><input name=txtselprov".$li_i." type=hidden id=txtselprov".$li_i." value='".$ls_selpro."' readonly>";
				}
			}
			else
			{
				 uf_agregarlineablanca($lo_object,1);
			}
			
		}
		else
		{
			if($ls_operacion="PROCESAR")
			{	
			    
				for($li_i=1;$li_i<=$li_totrows;$li_i++)
				{
					uf_cargar_dt($li_i);
					$lo_object[$li_i][1]="<input name=txtcodprov".$li_i." type=text id=txtcodprov".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_codpro."' readonly >";
					$lo_object[$li_i][2]="<input name=txtdenprov".$li_i." type=text id=txtdenprov".$li_i." maxlength=100 class=sin-borde size=60 value='".$ls_nompro."' readonly>";
					$lo_object[$li_i][3]="<input name=txtrifprov".$li_i." type=text id=txtrifprov".$li_i." class=sin-borde size=17 maxlength=15 value='".$ls_rifpro."'  readonly>";
					$lo_object[$li_i][4]="<input type=checkbox name=selprov".$li_i." id=selprov".$li_i." onChange='javascript: cambiar_valor(".$li_i.");'><input name=txtselprov".$li_i." type=hidden id=txtselprov".$li_i." value='".$ls_selpro."' readonly>";
					
				  	if ($ls_selpro=='1')
				  	{
				  		$lb_valido=$io_proveedor->uf_transferir_proveedores($ls_codpro, $_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"] ,$_SESSION["ls_database_destino"],	$_SESSION["ls_gestor_destino"]   );
						
				 	 }	 // Fin del if
		    } // Fin del For 
			if ($lb_valido)
			{
		 
			  $msg->message("Transferencia realizada con éxito !!!");
			}
			else
			{
			  $msg->message("Error al realizar Tranferencia !!!");
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
	$io_fun_rpc->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_rpc);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
 
  <table width="200" border="0" align="center">
    <tr>
      <td><div align="center">
        <table width="570" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
         <td height="22" colspan="5" class="titulo-celdanew">Transferir Proveedores</td>
      </tr>
     
      <?php
      if(($ls_operacion=="")||($ls_operacion=="MOSTRAR")||($ls_operacion=="BUSCAR")||($ls_operacion=="PROCESAR"))
      {
      ?>                    
          
                 
          <tr>
            <td height="31" colspan="5" ><strong>Base de Datos Destino</strong><?php
					$li_total = count($empresa["database"]);
				?>
                <select name="cmbdb" style="width:120px " onChange="javascript:selec();">
                  <option value="">Seleccione</option>
                  <?php
			for($i=1; $i <= $li_total ; $i++)
			{
				if($posicion==$i)
				{
					$selected="selected";
				}
				else
				{
					$selected="";
				}
				
		?>
                  <option value="<?php echo $i;?>" <?php print $selected; ?>>
                    <?php
						echo $empresa["database"][$i];				
					?>
                    </option>
                  <?php
		}
		?>
                  </select>
                <input name="dbdestino" type="hidden" id="dbdestino" value="<?php print $ls_dbdestino;?>">
                <input name="operacion" type="hidden" id="operacion" value="<?php print ($ls_operacion); ?>">
              </td>
            </tr>
          <?php
		  }	
		 	 
		  ?>
		  <tr>
        	 <td height="22" colspan="5" class="titulo-celdanew">Par&aacute;metros de B&uacute;squeda</td>
     	 </tr>
		 <tr>
            <td width="88"><div align="right"><span class="style1 style14">Desde</span></div></td>
            <td width="141"><input name="txtcodprov" type="text" id="txtcodprov" value="<?php print $ls_codprov1?>" size="12" maxlength="10"  style="text-align:center "  onBlur="javascript:rellenar_cad(this.value,10,document.form1.txtcodprov.name)">
              <a href="javascript:uf_catalogoprov();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" onClick="document.form1.hidrango.value=5"></a></td>
            <td width="74"><div align="right"><span class="style1 style14">Hasta</span></div></td>
            <td width="134"><input name="txtcodprov2" type="text" id="txtcodprov2" value="<?php print $ls_codprov2?>" size="12" maxlength="10"  style="text-align:center"  onBlur="javascript:rellenar_cad(this.value,10,document.form1.txtcodprov2.name)">
              <a href="javascript:uf_catalogoprov();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"  onClick="document.form1.hidrango.value=2"></a></td>
          </tr>
            <td width="88"><div align="right"><span class="style1 style14">Nombre</span></div></td>
            <td width="141" colspan="4"><input name="txtnomprov" type="text" id="txtnomprov" value="<?php print $ls_nomprov ?>" size="60" maxlength="100"  style="text-align:left "  ></td>
		 </tr>
		 <tr>
            <td width="88"><div align="right"><span class="style1 style14">Rif</span></div></td>
            <td width="141" colspan="4"><input name="txtrifprov" type="text" id="txtrifprov" value="<?php print $ls_rifprov ?>" size="20" maxlength="15"  style="text-align:left "  ></td>
		 </tr>
		 <tr>
           <td height="22"><div align="right"></div></td>
		   <td height="22"><div align="right"></div></td>
           <td colspan="3" align="right"><span class="toolbar"><a href="javascript: ue_buscar();">
		    <img src="../shared/imagebank/tools20/buscar.gif"  width="20" height="20" border="0">Buscar Proveedores</a></span></td>
          </tr>
		  <tr>
        	 <td height="22" colspan="5" class="titulo-celdanew">Datos de los Proveedores</td>
     	 </tr>
	 	 <tr>
           <td height="22"><div align="right"></div></td>
           
			<td width="141"></td>
			<td width="74">&nbsp;</td>
			 
            <td width="134"><a href="javascript: deseleccionar_todos();"><span class="toolbar"><a href="javascript: seleccionar_todos();"><img src="../shared/imagebank/tools20/aprobado.gif"  width="20" height="20" border="0">Seleccionar Todos</a><a href="javascript: deseleccionar_todos();"></a></td>
	        <td width="140"><a href="javascript: deseleccionar_todos();"><img src="../shared/imagebank/tools20/eliminar.gif" width="20" height="20" border="0">Deseleccionar Todos</a></td>
	 	 </tr>
	  
		<tr>
          <td colspan="5">
		  	<div align="center">
			<?php
				require_once("../shared/class_folder/grid_param.php");
				$io_grid=new grid_param();
				$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
				unset($io_grid);
			?>
			  </div>		  	
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">	
			  <input name="hidrango" type="hidden" id="hidrango">	   </td>		  
         </tr>
        </table>
      </div></td>
    </tr>
  </table>
     
  <div id=transferir style="visibility:hidden" align="center"><img src="../shared/imagebank/cargando.gif">Transfiriendo Proveedores... </div>
</form>      
</body>
<script language="javascript">

function ue_procesar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		f.operacion.value = "PROCESAR";
		f.action="sigesp_rpc_p_traspasar_proveedores.php";
		f.submit();
		
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function selec()
{	
	f=document.form1;
	
	var indice = f.cmbdb.selectedIndex
	var textoDB = f.cmbdb.options[indice].text 

	f.operacion.value="MOSTRAR";	
	valor = f.cmbdb.text;
	f.dbdestino.value=f.cmbdb.options[indice].text;
	f.action="sigesp_rpc_p_traspasar_proveedores.php";
	f.submit();
}

function cambiar_valor (li_i)
{
	f=document.form1;
	sel= eval ('document.form1.selprov'+li_i);	
	if (sel.checked)
	{
		selpro = eval ('document.form1.txtselprov'+li_i);	
		selpro.value = '1';
	}	
	else
	{
		selpro = eval ('document.form1.txtselprov'+li_i);	
		selpro.value = '0';
	}
	
}

function seleccionar_todos()
{	
	f=document.form1;
	li_total = f.totalfilas.value;
	for(li_i=1;li_i<=li_total;li_i++)
	{
	  ch= eval ('document.form1.selprov'+li_i);
	  ch.checked=true;
	  selpro = eval ('document.form1.txtselprov'+li_i);	
	  selpro.value = '1';
	}
}

function deseleccionar_todos()
{	
	f=document.form1;
	li_total = f.totalfilas.value;
	for(li_i=1;li_i<=li_total;li_i++)
	{
	  ch= eval ('document.form1.selprov'+li_i);	
	  ch.checked=false;
	  selpro = eval ('document.form1.txtselprov'+li_i);	
	  selpro.value = '1';
	}
}

function ue_buscar()
{	
	f=document.form1;
	
	if (f.cmbdb.value=="")
	{
		alert ('Debe Seleccionar una Base de Datos Destino');
	}
	else
	{
		
		if (f.txtcodprov.value > f.txtcodprov2.value)
		{
			alert ('Intervalo de Proveedores Inválido.');
		}
		else
		{
			var indice = f.cmbdb.selectedIndex
			var textoDB = f.cmbdb.options[indice].text 
			f.operacion.value="BUSCAR";	
			valor = f.cmbdb.text;
			f.dbdestino.value=f.cmbdb.options[indice].text;
			f.action="sigesp_rpc_p_traspasar_proveedores.php";
			f.submit();
		}
	}
}

function uf_catalogoprov()
{
    f=document.form1;
    pagina="sigesp_catdin_prove.php";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}

function rellenar_cad(cadena,longitud,objeto)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;

	total=longitud-lencad;
	if (cadena!="")
	   {
		for (i=1;i<=total;i++)
			{
			  cadena_ceros=cadena_ceros+"0";
			}
		cadena=cadena_ceros+cadena;
		if (objeto=="txtcodprov2")
		   {
			 document.form1.txtcodprov2.value=cadena;
		   }
		 else
		   {
			 document.form1.txtcodprov.value=cadena;
		   }  
        }
}

function ue_cerrar()
{
   location.href='../index_modules.php' 
}
</script> 
</html>