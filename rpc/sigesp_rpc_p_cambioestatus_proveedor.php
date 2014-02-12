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

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $li_totrows,$ls_operacion,$ls_existe,$io_fun_rpc,$ls_activo,$ls_inactivo,$ls_bloqueado,$ls_suspendido;
		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_codprovdesde,$ls_codprovhasta;

	 	$ls_codprovdesde="";
		$ls_codprovhasta="";
		$ls_titletable="Proveedores";
		$li_widthtable=500;
		$ls_nametable="grid";
		$lo_title[1]=" ";
		$lo_title[2]="Código";
		$lo_title[3]="Nombre";
		$ls_activo="checked";
		$ls_inactivo="";
		$ls_bloqueado="";
		$ls_suspendido="";
		$li_totrows=$io_fun_rpc->uf_obtenervalor("hidtotrows",1);
		$ls_existe=$io_fun_rpc->uf_obtenerexiste();
		$ls_operacion=$io_fun_rpc->uf_obteneroperacion();
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px >";
		$aa_object[$ai_totrows][2]="<input name=txtcodprov".$ai_totrows." type=text id=txtcodprov".$ai_totrows." class=sin-borde size=11 maxlength=10 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtnomprov".$ai_totrows." type=text id=txtnomprov".$ai_totrows." class=sin-borde size=50 maxlength=100 readonly>";
   }
   //--------------------------------------------------------------
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
<title >Actualizar Estatus de Proveedores en Lote</title>
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
<?php 
	require_once("class_folder/sigesp_rpc_c_proveedor.php");
	$io_proveedor=new sigesp_rpc_c_proveedor();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

		case "BUSCAR":
		 	$ls_codprovdesde=$_POST["txtcodprovdesde"];
			$ls_codprovhasta=$_POST["txtcodprovhasta"];
			$ls_estprov=$_POST["estprov"];
			$ls_activo="";
			switch($ls_estprov)
			{
				case "0":
					$ls_activo="checked";
					break;
				case "1":
					$ls_inactivo="checked";
					break;
				case "2":
					$ls_bloqueado="checked";
					break;
				case "3":
					$ls_suspendido="checked";
					break;
			}
			$lb_valido=$io_proveedor->uf_load_proveedor_cambioestatus($ls_codprovdesde,$ls_codprovhasta,$ls_estprov,$lo_object,$li_totrows);
			break;

		case "PROCESAR":
			$lb_valido=true;
			$ls_estprovnew=$_POST["estprovnew"];
			$io_proveedor->io_sql->begin_transaction(); 
			for($li_i=1;($li_i<=$li_totrows)&&($lb_valido);$li_i++)
			{
				if(array_key_exists("chksel".$li_i,$_POST))
				{
					$ls_codprov=$_POST["txtcodprov".$li_i];
					$lb_valido=$io_proveedor->uf_update_proveedor_cambioestatus($ls_codprov,$ls_estprovnew,$la_seguridad);
				}
			}
			if($lb_valido)
			{
				$io_proveedor->io_sql->commit();
				$io_proveedor->io_msg->message("Los Proveedores fueron actualizados.");
			}
			else
			{
				$io_proveedor->io_sql->rollback();
				$io_proveedor->io_msg->message("Ocurrio un error al actualizar los proveedores.");
			}
			uf_limpiarvariables();
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;
	}
	unset($io_proveedor);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Proveedores y Beneficiarios</td>
			<td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>

      </table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"></a><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"></a><a href="javascript:ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Procesar" title="Procesar" name="Transferir" width="20" height="20" border="0" id="Transferir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"></a><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
         <input name="hidrango" type="hidden" id="hidrango">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_rpc->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_rpc);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="600" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Actualizar Estatus de Proveedores en Lote </td>
        </tr>
        <tr>
          <td width="115" height="15">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Proveedor desde </div></td>
          <td width="132"><div align="left">
            <input name="txtcodprovdesde" type="text" id="txtcodprovdesde" value="<?php print $ls_codprovdesde; ?>" size="12" maxlength="10"  style="text-align:center ">
            <a href="javascript:uf_catalogoprov();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" onClick="document.form1.hidrango.value=3"></a></td>
          <td width="120">Proveedor Hasta </td>
          <td width="173"><input name="txtcodprovhasta" type="text" id="txtcodprovhasta" value="<?php print $ls_codprovhasta; ?>" size="12" maxlength="10"  style="text-align:center ">
            <a href="javascript:uf_catalogoprov();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"  onClick="document.form1.hidrango.value=4"></a></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Estatus Actual del Proveedor</div></td>
          <td colspan="3"><div align="left">
            <input name="estprov" type="radio" class="sin-borde" value="0" <?php print $ls_activo; ?>>
Activo
<input name="estprov" type="radio" class="sin-borde" value="1" <?php print $ls_inactivo; ?>>
Inactivo
<input name="estprov" type="radio" class="sin-borde" value="2" <?php print $ls_bloqueado; ?>>
Bloqueado
<input name="estprov" type="radio" class="sin-borde" value="3"  <?php print $ls_suspendido; ?>>
Suspendido</div></td>
          </tr>
        <tr>
          <td height="22" colspan="4" class="sin-bordeAzul"><div align="right"></div>
		    <div align="left"></div></td>
          </tr>
        <tr>
          <td><div align="right">Estatus nuevo </div></td>
          <td colspan="3"><div align="left">
            <input name="estprovnew" type="radio" class="sin-borde" value="0">
Activo
<input name="estprovnew" type="radio" class="sin-borde" value="1">
Inactivo
<input name="estprovnew" type="radio" class="sin-borde" value="2">
Bloqueado
<input name="estprovnew" type="radio" class="sin-borde" value="3">
Suspendido</div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="3"><label></label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="3"><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"> 
			<input name="hidtotrows" type="hidden" id="hidtotrows" value="<?php print $li_totrows ?>"></td>
        </tr>
        <tr>
          <td colspan="4">
		  	<div align="center">
			<?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
			  </div>			</td>		  
          </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
</body>
<script language="javascript">
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		ls_estprov="";
		if(f.estprov[0].checked==true)
		{
			ls_estprov="0";
		}
		if(f.estprov[1].checked==true)
		{
			ls_estprov="1";
		}
		if(f.estprov[2].checked==true)
		{
			ls_estprov="2";
		}
		if(f.estprov[3].checked==true)
		{
			ls_estprov="3";
		}
		if(ls_estprov!="")
		{
			f.operacion.value = "BUSCAR";
			f.action="sigesp_rpc_p_cambioestatus_proveedor.php";
			f.submit();
		}
		else
		{
			alert("Debe Seleccionar un Estatus para el proveedor");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_procesar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		ls_estprov="";
		ls_estprovnew="";
		if(f.estprovnew[0].checked==true)
		{
			ls_estprovnew="0";
		}
		if(f.estprovnew[1].checked==true)
		{
			ls_estprovnew="1";
		}
		if(f.estprovnew[2].checked==true)
		{
			ls_estprovnew="2";
		}
		if(f.estprovnew[3].checked==true)
		{
			ls_estprovnew="3";
		}
		if(f.estprov[0].checked==true)
		{
			ls_estprov="0";
		}
		if(f.estprov[1].checked==true)
		{
			ls_estprov="1";
		}
		if(f.estprov[2].checked==true)
		{
			ls_estprov="2";
		}
		if(f.estprov[3].checked==true)
		{
			ls_estprov="3";
		}
		if(ls_estprovnew!=ls_estprov)
		{
			f.operacion.value = "PROCESAR";
			f.action="sigesp_rpc_p_cambioestatus_proveedor.php";
			f.submit();
		}
		else
		{
			alert("El estatus actual es igual al estatus nuevo. Debe cambiarlo para procesar");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_catalogoprov()
{
    f=document.form1;
    pagina="sigesp_catdin_prove.php?tipo=CAMBIOESTATUS";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}
</script> 
</html>