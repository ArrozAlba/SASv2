<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
     print "<script language=JavaScript>";
     print "location.href='../sigesp_inicio_sesion.php'";
     print "</script>";		
   }
require_once("class_folder/class_funciones_soc.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_mensajes.php");
$io_fecha      = new class_fecha();
$io_msg        = new class_mensajes();
$io_fun_compra = new class_funciones_soc();
$io_fun_compra->uf_load_seguridad("SOC","sigesp_soc_p_aceptacion_servicios.php",$ls_permisos,&$la_seguridad,$la_permisos);

$ls_logusr = $_SESSION["la_logusr"];
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
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
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 03/06/2007			Fecha Última Modificación : 03/06/2007 
		//////////////////////////////////////////////////////////////////////////////
		global $io_fun_compra,$ls_operacion,$ls_parametros,$li_totrows,$ld_fecope,$ls_tipope,$li_estciespg,$li_estciespi,
		      $ls_disabled;
				
		$ls_operacion  = $io_fun_compra->uf_obteneroperacion();
		$ls_parametros = ""; 
		$li_totrows    = 0;
		$ld_fecope     = date("d/m/Y");
		$ls_tipope     = "-";
		$li_estciespg = $io_fun_compra->uf_load_estatus_cierre($li_estciespi,$li_estciescg);
		$ls_disabled  = "";
		if ($li_estciespg==1 || $li_estciespi==1)
		   {
		     $ls_disabled = "disabled";
		   }		
    }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 06/06/2007			Fecha Última Modificación : 06/06/2007
		//////////////////////////////////////////////////////////////////////////////
		global $ls_tipope,$ls_operacion,$ls_parametros,$li_totrows,$ld_fecope,$li_estciespg,$li_estciespi;
				
		$ls_operacion  = $_POST["operacion"];
		$ls_tipope     = $_POST["cmbtipope"];
		$ls_parametros = $_POST["parametros"]; 
		$li_totrows    = $_POST["hidtotrows"];
		$ld_fecope     = $_POST["hidfecha"];
		$li_estciespg  = $_POST["hidestciespg"];//Estatus del Cierre Presupuestario de Gasto.
		$li_estciespi  = $_POST["hidestciespi"];//Estatus del Presupuestario de Ingresos.
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Aceptaci&oacute;n de Servicios</title>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body onLoad="writetostatus('<?php print "Base de Datos: ".$_SESSION["ls_database"].". Usuario: ".$_SESSION["la_logusr"];?>')">
<?php
require_once("class_folder/sigesp_soc_c_aceptacion_orden_servicio.php");
$io_soc=new sigesp_soc_c_aceptacion_orden_servicio("../");
uf_limpiarvariables();
switch($ls_operacion){
  case 'CARGAR':
	uf_soc_load_ordenes_servicio();
  break;
  case 'PROCESAR':
    uf_load_variables();
	$lb_valido=$io_fecha->uf_valida_fecha_mes($ls_codemp,$ld_fecope);
	if ($lb_valido)
	   {
         $io_soc->uf_aceptar_orden_servicio($ls_tipope,$li_totrows,$la_seguridad);
	   }
	else
	   {
	     $io_msg->message("El mes no esta abierto");
	   }	 
  break;
}  
?>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" alt="Encabezado" width="800" height="40" /></td>
  </tr>
  <td height="20" colspan="12" bgcolor="#E7E7E7">
    <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Ordenes de Compra</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table></td><tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Procesar" width="20" height="20" border="0" title="Procesar" /></a><a href="../soc/sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir" /></a></td>
  </tr>
</table>
<p>&nbsp;</p>
<form id="formulario" name="formulario" method="post" action="">
<div align="center">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_compra->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_compra);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  </div>
  <table width="599" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-celda">
      <td height="22" colspan="8" class="titulo-ventana">Aceptaci&oacute;n de Servicios 
      <input name="operacion"  type="hidden" id="operacion"  value="<?php print $ls_operacion ?>" />
      <input name="parametros" type="hidden" id="parametros" value="<?php print $ls_parametros;?>" />
      <input name="hidtotrows" type="hidden" id="hidtotrows" value="<?php print $li_totrows ?>" />
      <input name="hidfecha"   type="hidden" id="hidfecha"   value="<?php print $ld_fecope ?>" />
      <span class="titulo-celdanew">
      <input name="hidestciespg" type="hidden" id="hidestciespg" value="<?php echo $li_estciespg ?>" />
      <input name="hidestciespi" type="hidden" id="hidestciespi" value="<?php echo $li_estciespi ?>" />
      </span></td>
    </tr>
    <tr>
      <td width="108" height="22">&nbsp;</td>
      <td width="56" height="22">&nbsp;</td>
      <td width="40" height="22">&nbsp;</td>
      <td width="79" height="22">&nbsp;</td>
      <td width="79" height="22">&nbsp;</td>
      <td width="79" height="22">&nbsp;</td>
      <td width="79" height="22">&nbsp;</td>
      <td width="77" height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right"><strong>Operaci&oacute;n</strong></td>
      <td height="22" colspan="3" style="text-align:left"><label>
        <select name="cmbtipope" id="cmbtipope" onchange="javascript:uf_load_ordenes_compra();" <?php echo $ls_disabled; ?>>
          <option value="-" <?php if ($ls_tipope=='-'){print 'selected';}?>>---seleccione---</option>
          <option value="A" <?php if ($ls_tipope=='A'){print 'selected';}?>>Aceptaci&oacute;n</option>
          <option value="R" <?php if ($ls_tipope=='R'){print 'selected';}?>>Reverso de Aceptaci&oacute;n</option>
        </select>
      </label></td>
      <td height="22">&nbsp;</td>
      <td height="22" style="text-align:right"><strong>Fecha</strong></td>
      <td height="22" colspan="2"><label>
        <input name="txtfecope" type="text" class="sin-borde" id="txtfecope" value="<?php print $ld_fecope ?>" size="12" maxlength="12" readonly/>
      </label></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
	   <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
         <tr> 
           <td align="center"><div id="ordenesservicios"></div></td>
         </tr>
       </table>
  </table>
</form>
<p>&nbsp;</p>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
f = document.formulario;
function writetostatus(input){
    window.status=input
    return true
}

function uf_load_ordenes_compra()
{
  if (uf_evaluate_cierre())
     {
	   li_leer   = f.leer.value;
	   if (li_leer==1)
		  {
		    ls_tipope = f.cmbtipope.value;
		    if (ls_tipope!='-')
			   {
				 divgrid = document.getElementById('ordenesservicios');
				 // Instancia del Objeto AJAX
				 ajax=objetoAjax();
				 // Pagina donde están los métodos para buscar y pintar los resultados
				 ajax.open("POST","class_folder/sigesp_soc_c_aceptacion_orden_servicio_ajax.php",true);
				 ajax.onreadystatechange=function(){
				 if (ajax.readyState==1)
				    {
					  divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
				    }
				 else
				    {
					  if (ajax.readyState==4)
					     {
						   if (ajax.status==200)
						      {//mostramos los datos dentro del contenedor
							    divgrid.innerHTML = ajax.responseText
						      }
						   else
						      {
							    if (ajax.status==404)
							       {
								     divgrid.innerHTML = "La página no existe";
							       }
							    else
							       {//mostramos el posible error     
								     divgrid.innerHTML = "Error:".ajax.status;
							       }
						      }
						
					     }
				    }
				 }	
			     ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			     // Enviar todos los campos a la pagina para que haga el procesamiento
			     ajax.send("proceso=CARGAR"+"&tipope="+ls_tipope);
			   }
		  } 
	   else
		  {
		    alert("No tiene permiso para realizar esta operación !!!");
		  }
	 }
}

function uf_select_all()
{
	li_totrows = ue_calcular_total_fila_local("txtnumord");
	if (f.chkall.checked==true)
	   {
         sel_all='T';	
	   }
	else
	   {
	     sel_all='F';
	   }
	if (sel_all=='T')
	   {
	     for (i=1;i<=li_totrows;i++)	
		     {
			   eval("f.chk"+i+".checked=true");
		     }
	   }
     else
	   {
         for (i=1;i<=li_totrows;i++)	
		     {
			   eval("f.chk"+i+".checked=false");
		     }
  	   } 
}

function ue_procesar()
{
  if (uf_evaluate_cierre())
     {
	   li_incluir   = f.incluir.value;
	   if (li_incluir=='1')
		  {
		    li_totrows         = ue_calcular_total_fila_local("txtnumord");
		    f.hidtotrows.value = li_totrows;
		    f.operacion.value  = 'PROCESAR';
		    f.submit();
		  }
	   else
		  {
		    alert("No tiene permiso para realizar esta operación !!!");
		  }
	 }
}

function uf_evaluate_cierre()
{
  lb_valido = true;
  li_estciespg = f.hidestciespg.value;
  li_estciespi = f.hidestciespi.value;
  if (li_estciespg==1 || li_estciespi==1)
	 {
	   lb_valido = false;
	   alert("Ya fué procesado el Cierre Presupuestario, No pueden efectuarse movimientos, Contacte al Administrador del Sistema !!!");
	 }
  return lb_valido;
}
</script>
<?php
if (($ls_operacion=="PROCESAR") && ($ls_tipope!='-'))
   {
	 print "<script language=JavaScript>";
	 print "   uf_load_ordenes_compra();";
	 print "</script>";
   }
?>
</html>