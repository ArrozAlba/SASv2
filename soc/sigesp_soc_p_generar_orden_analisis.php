<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
     print "<script language=JavaScript>";
     print "location.href='../sigesp_inicio_sesion.php'";
     print "</script>";		
   }
require_once("class_folder/class_funciones_soc.php");
$io_fun_compra = new class_funciones_soc();
$io_fun_compra->uf_load_seguridad("SOC","sigesp_soc_p_generar_orden_analisis.php",$ls_permisos,&$la_seguridad,$la_permisos);

$ls_logusr = $_SESSION["la_logusr"];
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
		//	   Creado Por: Ing. Laura Cabre
		// Fecha Creación: 05/08/2007			Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
	    global $ld_fecope,$li_totrows,$io_fun_compra,$ls_operacion,$ls_numanacot,$ls_solcot,$ld_fecope,$ld_fecdes,$ld_fechas,
		       $ls_tipope,$li_estciespg,$li_estciespi,$ls_disabled;
		
		require_once("../shared/class_folder/sigesp_include.php");
	    $io_include	  = new sigesp_include();
		$io_conexion  = $io_include->uf_conectar();
		
		$ld_fecope    = date("d/m/Y");
		$ld_fecdes    = "";
		$ld_fechas    = "";
		$ls_numanacot = "";
		$ls_solcot    = "";
		$li_totrows   = 0;
		$ls_tipope    = "-";
		$ls_operacion = $io_fun_compra->uf_obteneroperacion();
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
		//	   Creado Por: Ing. Laura Cabre
		// Fecha Creación: 05/08/2007			Fecha Última Modificación : 29/04/2007
		//////////////////////////////////////////////////////////////////////////////
	
	    global $ls_tipope,$li_totrows,$ls_operacion,$ls_numanacot,$ls_solcot,$ld_fecope,
		       $ld_fecdes,$ld_fechas,$li_estciespg,$li_estciespi;
		
   		$ld_fecope     = $_POST["txtfecope"];
		$ld_fecdes     = $_POST["txtfecdes"];
		$ld_fechas     = $_POST["txtfechas"];
		$ls_numanacot  = $_POST["txtnumanacot"];
		$li_totrows    = $_POST["hidtotrows"];
		$ls_operacion  = $_POST["operacion"];
		$li_estciespg = $_POST["hidestciespg"];//Estatus del Cierre Presupuestario de Gasto.
		$li_estciespi = $_POST["hidestciespi"];//Estatus del Presupuestario de Ingresos.
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../soc/js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<title>Generaci&oacute;n de Ordenes de Compra</title>
<link href="css/soc.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
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
.Estilo2 {font-size: 11px}
-->
</style></head>
<body onLoad="writetostatus('<?php print "Base de Datos: ".$_SESSION["ls_database"].". Usuario: ".$_SESSION["la_logusr"];?>')">
<?php
require_once("class_folder/sigesp_soc_c_generar_orden_analisis.php");
$io_soc = new sigesp_soc_c_generar_orden_analisis("../");
uf_limpiarvariables();
switch($ls_operacion){
  case 'GUARDAR':
			uf_load_variables();
			$lb_valido=$io_soc->uf_guardar($li_totrows,$ls_tipope,$ld_fecope,$la_seguridad);
  break;
}
?>
<div align="center">
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr>
      <td width="800" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" alt="Encabezado" width="800" height="40" /></td>
    </tr>
    <tr>
  <td height="20" colspan="12" bgcolor="#E7E7E7">
    <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo2">Ordenes de Compra</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table></td>
  </tr><tr>
      <td height="20" bgcolor="#E7E7E7" class="cd-menu" style="text-align:left"><script type="text/javascript" language="JavaScript1.2" src="../soc/js/menu.js"></script></td>
    </tr>
    <tr>
      <td height="13" colspan="11" class="toolbar"></td>
    </tr>
    <tr style="text-align:left">
      <td width="800" height="13" colspan="11" class="toolbar" style="text-align:left"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Procesar" width="20" height="20" border="0" title="Procesar" /></a><a href="../soc/sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir" /></a></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <form id="formulario" name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_compra->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_compra);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <table width="595" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="6" class="titulo-ventana">Generaci&oacute;n de &Oacute;rdenes de Compra
          <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion ?>" />
          <input name="hidestciespg" type="hidden" id="hidestciespg" value="<?php echo $li_estciespg ?>" />
          <input name="hidestciespi" type="hidden" id="hidestciespi" value="<?php echo $li_estciespi ?>" /></td>
      </tr>
      <tr>
        <td width="79" height="29" style="text-align:right">&nbsp;</td>
        <td height="29" colspan="2" style="text-align:left"><label></label></td>
        <td width="23" height="29">&nbsp;</td>
        <td height="29" colspan="2" style="text-align:right"><strong>Fecha</strong> 
        <input name="txtfecope" type="text" class="sin-borde" id="txtfecope" value="<?php print $ld_fecope ?>" size="15" maxlength="12" readonly="readonly" />&nbsp;&nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">N&uacute;mero</td>
        <td width="161" height="22" style="text-align:left"><input name="txtnumanacot" type="text" id="txtnumanacot" value="<?php print $ls_numanacot ?>" size="20" maxlength="15" style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890'); " onBlur="javascript:rellenar_cad(this.value,15)" <?php echo $ls_disabled; ?> /></td>
        <td width="133" height="22" style="text-align:right">Desde 
          <label>
          <input name="txtfecdes" type="text" id="txtfecdes" value="<?php print $ld_fecdes ?>" size="12" maxlength="10"  datepicker="true" <?php echo $ls_disabled; ?> onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"/>
        </label></td>
        <td height="22">&nbsp;</td>
        <td height="22" colspan="2" style="text-align:left">Hasta 
          <label>
          <input name="txtfechas" type="text" id="txtfechas" value="<?php print $ld_fechas ?>" size="12" maxlength="10"  datepicker="true" <?php echo $ls_disabled; ?> onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"/>
        </label></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Tipo</td>
        <td height="22" style="text-align:left"><label>
          <select name="cmbtipanacot" id="cmbtipanacot" <?php echo $ls_disabled; ?> style="width:120px">
            <option value="-" <?php if ($ls_tipope=='-'){print 'selected';}?>>---seleccione---</option>
            <option value="B" <?php if ($ls_tipope=='B'){print 'selected';}?>>Bienes</option>
            <option value="S" <?php if ($ls_tipope=='S'){print 'selected';}?>>Servicios</option>
          </select>
        </label></td>
        <td height="22"><input name="hidtotrows" type="hidden" id="hidtotrows" value="<?php print $li_totrows ?>" /></td>
        <td height="22">&nbsp;</td>
        <td width="147" height="22">&nbsp;</td>
        <td width="50" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="6"><div align="right"><a href="javascript: ue_buscar();" title="Buscar An&aacute;lisis de Cotizaciones"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar An&aacute;lisis de Cotizaciones" title="Buscar An&aacute;lisis de Cotizaciones" width="20" height="20" border="0">Buscar An&aacute;lisis de Cotizaciones</a>&nbsp;&nbsp;</div></td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13"><input type="hidden" name="tipo" id="tipo" value="SC" /></td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
       <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
         <tr> 
           <td align="center"><div id="analisiscotizaciones"></div></td>
         </tr>
       </table>
    </table>
    <p>&nbsp;</p>
  </form>
  </div>
<p>&nbsp;</p>
</body>
<script language="javascript">
f = document.formulario;
function writetostatus(input){
    window.status=input
    return true
}

function rellenar_cad(cadena,longitud)
{
	var mystring = new String(cadena);
	cadena_ceros = "";
	lencad       = mystring.length;
    total        = longitud-lencad;
    if (cadena!="")
	   {
	     for (i=1;i<=total;i++)
		     {
			   cadena_ceros=cadena_ceros+"0";
		     }
	     cadena=cadena_ceros+cadena;
		 f.txtnumanacot.value=cadena;
	 } 
}

function ue_buscar()
{
  if (uf_evaluate_cierre())
     {
       ls_numanacot = f.txtnumanacot.value;
       ld_fecdes    = f.txtfecdes.value;
       ld_fechas    = f.txtfechas.value;
       ls_tipanacot = f.cmbtipanacot.value;
       li_leer      = f.leer.value;
       if (li_leer==1)
          {
			divgrid = document.getElementById('analisiscotizaciones');
			// Instancia del Objeto AJAX
			ajax=objetoAjax();
			// Pagina donde están los métodos para buscar y pintar los resultados
			ajax.open("POST","class_folder/sigesp_soc_c_generar_orden_analisis_ajax.php",true);
			ajax.onreadystatechange=function() {
		    if (ajax.readyState==1)
		       {
			     divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";
		       }
		    else
		       {
			     if (ajax.readyState==4) {
				      divgrid.innerHTML = ajax.responseText
					}
			   }
			} 
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			// Enviar todos los campos a la pagina para que haga el procesamiento
			ajax.send("tipanacot="+ls_tipanacot+"&numanacot="+ls_numanacot+"&fecdes="+ld_fecdes+"&fechas="+ld_fechas+"&proceso=BUSCAR");
		  }
       else
          {
            alert("No tiene permiso para realizar esta operación !!!");
	      }
	 }
}

function uf_select_all()
{
	li_totrows = ue_calcular_total_fila_local("txtnumanacot");
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

function ue_guardar()
{
  if (uf_evaluate_cierre())
     {
	   li_incluir = f.incluir.value;
	   if (li_incluir==1)
		  {
		    li_totrows = ue_calcular_total_fila_local("txtnumanacot");
		    f.hidtotrows.value = li_totrows;
		    f.operacion.value='GUARDAR';
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
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);	

</script>
<?php
if(($ls_operacion=="GUARDAR")&&(!$lb_valido))
{
	print "<script language=JavaScript>";
	print "   ue_buscar();";
	print "</script>";
}
?>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>