<?php
   session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_logusr   = $_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_rpc.php");
	$io_fun_rpc=new class_funciones_rpc();
	$io_fun_rpc->uf_load_seguridad("RPC","sigesp_rpc_w_proxesp.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_operacion,$li_totrows,$ls_titletable,$li_widthtable,$io_fun_rpc;
		global $ls_nametable,$lo_title,$li_calculada;
		
		$ls_operacion=$io_fun_rpc->uf_obteneroperacion();
		$li_totrows=$io_fun_rpc->uf_obtenervalor("totalfilas",1);
		$ls_titletable="Especialidades por Proveedor";
		$li_widthtable=550;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Descripción";
		$lo_title[3]=" ";
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//		   Access: private
		//	    Arguments: aa_object  // arreglo de Objetos
		//			       ai_totrows  // total de Filas
		//	  Description: Función que agrega una linea mas en el grid
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtcodesp".$ai_totrows." type=text id=txtcodesp".$ai_totrows." class=sin-borde size=15 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][2]="<input name=txtdenesp".$ai_totrows." type=text id=txtdenesp".$ai_totrows." class=sin-borde size=50 maxlength=100 >";
		$aa_object[$ai_totrows][3]="<input name=chkselec type=checkbox class=sin-borde value='1'>";
   }
   //--------------------------------------------------------------

if (array_key_exists("txtcodesp",$_POST))
   {
     $ls_codesp = $_POST["txtcodesp"];
	 $ls_codesphasta = $_POST["txtcodesphasta"];
	 $ls_denesp = $_POST["txtdenesp"];
   }
else
   {
     $ls_codesp = "";
	 $ls_denesp = "";
	 $ls_codesphasta="";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<title >Especialidades por Proveedor</title>
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
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #333333}
-->
</style>
</head>

<body>
<?php 
	require_once("class_folder/sigesp_rpc_c_proxesp.php");
	$io_especialidad=new sigesp_rpc_c_proxesp();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$ls_codprov = $_GET["codprov"];
			$ls_nomprov = $_GET["nomprov"];
			$io_especialidad->uf_load_especialidad($ls_codprov,$ls_codesp,$ls_denesp,$ls_codesphasta,$li_totrows,$lo_object);
			break;

		case "BUSCAR":
			$ls_codprov = $_POST["txtcodprov"];
			$ls_nomprov = $_POST["txtnomprov"];
			$io_especialidad->uf_load_especialidad($ls_codprov,$ls_codesp,$ls_denesp,$ls_codesphasta,$li_totrows,$lo_object);
			break;
		
		case "GUARDAR":
			$ls_codprov=$_POST["txtcodprov"];
			$ls_nomprov=$_POST["txtnomprov"];
			$ls_codespaux=$ls_codesp;
			$ls_codeshasaux=$ls_codesphasta;
			$io_especialidad->io_sql->begin_transaction();
			$lb_valido=$io_especialidad->uf_eliminar_especialidad($ls_codprov,$la_seguridad);
			for($li_i=1;(($li_i<$li_totrows)&&($lb_valido));$li_i++)
			{
				$ls_codesp=$_POST["txtcodesp".$li_i];
				$li_seleccionado=$io_fun_rpc->uf_obtenervalor("chkselec".$li_i,"");
				if($li_seleccionado!="")
				{
					$lb_valido=$io_especialidad->uf_insert_especialidad($ls_codprov,$ls_codesp,$la_seguridad);
				}
			}
			if($lb_valido)
			{
				$io_especialidad->io_sql->commit();
				$io_especialidad->io_mensajes->message("Las Especialidades fueron Registrada.");
			}
			else
			{
				$io_especialidad->io_sql->rollback();
				$io_especialidad->io_mensajes->message("Ocurrio un error al guardar las Especialidades.");
			}
			uf_limpiarvariables();
			$li_totrows=1;
			$ls_codprov=$_POST["txtcodprov"];
			$ls_nomprov=$_POST["txtnomprov"];
			$io_especialidad->uf_load_especialidad($ls_codprov,$ls_codespaux,$ls_denesp,$ls_codeshasaux,$li_totrows,$lo_object);
			break;
	}
	$io_especialidad->uf_destructor();
	unset($io_especialidad);
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_rpc->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_rpc);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="600" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td height="22" colspan="3" class="titulo-celda" style="text-align:center">Especialidades por Proveedor</td>
          </tr>
        <tr>
          <td width="111" height="22" style="text-align:right">Proveedor</td>
          <td height="20" colspan="2">
            <div align="left">
              <input name="txtnomprov" type="text" class="sin-borde2" id="txtnomprov" style="cursor:text; font-weight: bolder; font-family: Arial, Helvetica, sans-serif; font-size: 11px; font-style: italic;" value="<?php print $ls_nomprov;?>" size="60" maxlength="100" readonly #invalid_attr_id="none">
              <input name="txtcodprov" type="hidden" id="txtcodprov" value="<?php print $ls_codprov;?>">
              <input name="operacion" type="hidden" id="operacion">          
            </div></td>
        </tr>
        <tr>
          <td height="22" style="text-align:right">Desde</td>
          <td width="72" height="20"><label>
            <input name="txtcodesp" type="text" id="txtcodesp" style="text-align:center" size="9" maxlength="3" onKeyPress="return keyRestrict(event,'0123456789');">
          </label></td>
          <td width="359"> Hasta
            <input name="txtcodesphasta" type="text" id="txtcodesphasta" style="text-align:center" size="9" maxlength="3" onKeyPress="return keyRestrict(event,'0123456789');"></td>
        </tr>
        <tr>
          <td height="22" style="text-align:right">Denominaci&oacute;n</td>
          <td height="20" colspan="2"><label>
            <input name="txtdenesp" type="text" id="txtdenesp" size="70" maxlength="254" style="text-align:left">
          </label></td>
        </tr>
        <tr>
          <td height="15" colspan="3" style="text-align:right"><a href="javascript:uf_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar Especialidad..." width="20" height="20" border="0">Buscar</a></td>
          </tr>
        <tr>
          <td height="15">&nbsp;</td>
          <td height="15" colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3"><div align="center">
            <?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
          </div>
            <p align="right">
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
              <a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Buscar" width="20" height="20" border="0">Grabar</a>
		    <a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Imprimir" width="20" height="20" border="0">Cancelar</a></p></td>
        </tr>
      </table>    
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	if((li_cambiar==1)||(li_incluir==1))
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_rpc_w_proxesp.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}		
}

function ue_cerrar()
{
	close();
}

function uf_buscar()
{
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_rpc_w_proxesp.php";
  f.submit();
}
</script> 
</html>