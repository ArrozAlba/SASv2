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
$io_fun_compra->uf_load_seguridad("SOC","sigesp_soc_p_anulacion_orden_compra.php",$ls_permisos,&$la_seguridad,$la_permisos);

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
		// Fecha Creación: 11/04/2007			Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $io_fun_compra,$ld_fecope,$ld_fecdes,$ld_fechas,$ls_numordcom,$ls_codpro,$ls_nompro,
		       $ls_operacion,$li_totrows,$li_estciespg,$li_estciespi,$ls_disabled;
	
	    $ld_fecope    = date("d/m/Y");
		$ld_fecdes    = '01/'.date("m/Y");
	    $ld_fechas    = date("d/m/Y");
		$ls_numordcom = "";
		$li_totrows   = 0;
		$ls_codpro    = "";
		$ls_nompro    = ""; 
		$ls_operacion = $io_fun_compra->uf_obteneroperacion();
		$li_estciespg = $io_fun_compra->uf_load_estatus_cierre($li_estciespi,$li_estciescg);
		$ls_disabled  = "";
		if ($li_estciespg==1 || $li_estciespi==1)
		   {
		     $ls_disabled = "disabled";
		   }
	}	
	
   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 17/03/2007			Fecha Última Modificación : 29/04/2007
		//////////////////////////////////////////////////////////////////////////////
	
   		global $ld_fecope,$ld_fecdes,$ld_fechas,$ls_numordcom,$ls_codpro,$ls_nompro,
		       $ls_operacion,$li_totrows,$li_estciespg,$li_estciespi;
	
	    $ld_fecope    = $_POST["txtfecope"];
		$ld_fecdes    = $_POST["txtfecdes"];
	    $ld_fechas    = $_POST["txtfechas"];
		$ls_numordcom = $_POST["txtnumordcom"];
		$ls_codpro    = $_POST["txtcodprov"];
		$ls_nompro    = $_POST["txtnomprov"]; 
		$ls_operacion = $_POST["operacion"]; 
		$li_totrows   = $_POST["hidtotrows"];
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<title>Anulaci&oacute;n de Orden de Compra</title>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css" />
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
</style></head>
<body onLoad="writetostatus('<?php print "Base de Datos: ".$_SESSION["ls_database"].". Usuario: ".$_SESSION["la_logusr"];?>')">
<?php
require_once("class_folder/sigesp_soc_c_anulacion_orden_compra.php");
$io_soc=new sigesp_soc_c_anulacion_orden_compra("../");
uf_limpiarvariables();
switch($ls_operacion){
  case 'GUARDAR':
	 uf_load_variables();
	 $io_soc->uf_update_estatus_orden_compra($li_totrows,$la_seguridad);
  break;
}
?>
<div align="center">
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr>
      <td width="800" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" alt="Encabezado" width="800" height="40" /></td>
    </tr>
    <td height="20" colspan="12" bgcolor="#E7E7E7">
    <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Ordenes de Compra</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table></td>
	<tr>
      <td height="20" bgcolor="#E7E7E7" class="cd-menu" style="text-align:left"><script type="text/javascript" language="JavaScript1.2" src="../soc/js/menu.js"></script></td>
    </tr>
    <tr>
      <td height="13" colspan="11" class="toolbar"></td>
    </tr>
    <tr style="text-align:left">
      <td width="800" height="13" colspan="11" class="toolbar" style="text-align:left"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Procesar" width="20" height="20" border="0" title="Procesar" /></a><a href="../soc/sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir" /></a><a href="javascript: ue_ayuda();"></a></td>
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
    <p>&nbsp;</p>
    <table width="697" height="202" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="697" height="168"><div align="center">
          <table width="590" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td height="22" colspan="8" class="titulo-celdanew">Anulaci&oacute;n de Orden de Compra
                <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion ?>" />
                <input name="hidtotrows" type="hidden" id="hidtotrows" value="<?php print $li_totrows ?>" />
                <input name="hidestciespg" type="hidden" id="hidestciespg" value="<?php echo $li_estciespg ?>" />
                <input name="hidestciespi" type="hidden" id="hidestciespi" value="<?php echo $li_estciespi ?>" /></td>
            </tr>
            <tr>
              <td width="76" height="13">&nbsp;</td>
              <td width="128" height="13">&nbsp;</td>
              <td width="96" height="13">&nbsp;</td>
              <td width="121" height="13">&nbsp;</td>
              <td width="11" height="13">&nbsp;</td>
              <td width="9" height="13">&nbsp;</td>
              <td width="113" height="13">&nbsp;</td>
              <td width="37" height="13">&nbsp;</td>
            </tr>
            <tr>
              <td height="22" colspan="2" style="text-align:right"><div align="left"><strong>Fecha</strong>
                      <input name="txtfecope" type="text" class="sin-borde" id="txtfecope" value="<?php print $ld_fecope ?>" size="12" maxlength="12" readonly="readonly" />
              </div></td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22" colspan="2">&nbsp;</td>
              <td height="22">&nbsp;</td>
            </tr>
            <tr>
              <td height="22" style="text-align:right">N&uacute;mero</td>
              <td height="22"><label>
                <input name="txtnumordcom" type="text" id="txtnumordcom" value="<?php print $ls_numordcom ?>" size="20" maxlength="15" style="text-align:center" onkeypress="return keyRestrict(event,'1234567890');" onBlur="javascript:rellenar_cad(this.value,15)" <?php echo $ls_disabled; ?>/>
              </label></td>
              <td height="22">&nbsp;</td>
              <td height="22">Desde
                <input name="txtfecdes" type="text" id="txtfecdes" value="<?php print $ld_fecdes ?>" size="12" maxlength="10" datepicker="true" <?php echo $ls_disabled; ?> onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"/></td>
              <td height="22">&nbsp;</td>
              <td height="22" colspan="2"><label>Hasta
                <input name="txtfechas" type="text" id="txtfechas" value="<?php print $ld_fechas ?>" size="12" maxlength="10" datepicker="true" <?php echo $ls_disabled; ?> onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"/>
              </label></td>
              <td height="22">&nbsp;</td>
            </tr>
            <tr>
              <td height="22" style="text-align:right">Proveedor</td>
              <td height="22" colspan="7"><label>
                <input name="txtcodprov" type="text" id="txtcodprov" value="<?php print $ls_codpro ?>" size="20" maxlength="15" style="text-align:center" readonly="readonly" />
                <a href="javascript:ue_catalogo_proveedores();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar..." width="15" height="15" border="0" /></a></label>
                  <label>
                  <input name="txtnomprov" type="text" class="sin-borde" id="txtnomprov" style="text-align:left" value="<?php print $ls_nompro ?>" size="65" maxlength="65" readonly="readonly" />
                </label></td>
            </tr>
            <tr>
              <td height="13" style="text-align:right">&nbsp;</td>
              <td height="13"><label></label></td>
              <td height="13" colspan="3"><label></label></td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
              <td height="13">&nbsp;</td>
            </tr>
            <tr>
              <td height="22" colspan="8" style="text-align:right"><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar..." width="20" height="20" border="0" /></a><a href="javascript:ue_buscar();">Buscar Ordenes de Compra</a></td>
            </tr>
            <tr>
              <td height="13" colspan="8" style="text-align:right">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr>
        <td height="19">&nbsp;</td>
      </tr>
	  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0">
	    <tr>
	 	  <td align="center"><div id="ordenescompra"></div></td>
	    </tr>
	  </table>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
  </form>
</div>
</body>
<script language="javascript">
f = document.formulario;
function writetostatus(input){
    window.status=input
    return true
}

function ue_catalogo_proveedores()
{
  if (uf_evaluate_cierre())
     {
	   pagina="sigesp_soc_cat_proveedor.php";
	   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=500,resizable=yes,location=no,left=50,top=50,dependent=yes");
	 }
}

function ue_buscar()
{
  if (uf_evaluate_cierre())
     {
	   ls_numordcom = f.txtnumordcom.value;
	   ls_codpro    = f.txtcodprov.value;
	   ld_fecdes    = f.txtfecdes.value;
	   ld_fechas    = f.txtfechas.value;
	   li_leer      = f.leer.value;
	   if (li_leer==1)
		  {  
		    divgrid = document.getElementById('ordenescompra');
		    // Instancia del Objeto AJAX
		    ajax=objetoAjax();
		    // Pagina donde están los métodos para buscar y pintar los resultados
		    ajax.open("POST","class_folder/sigesp_soc_c_anulacion_orden_compra_ajax.php",true);
		    ajax.onreadystatechange=function() {
			if (ajax.readyState==1)
			   {
			     divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
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
			ajax.send("numordcom="+ls_numordcom+"&codpro="+ls_codpro+"&fecdes="+ld_fecdes+"&fechas="+ld_fechas+"&proceso=BUSCAR");
		  }
	   else
		  {
		    alert("No tiene permiso para realizar esta operación !!!");
		  }
	 }
}

function ue_guardar()
{
  if (uf_evaluate_cierre())
     {
	   li_incluir = f.incluir.value;
	   li_cambiar = f.cambiar.value;
	   li_totrows = ue_calcular_total_fila_local("txtnumord");
	   f.hidtotrows.value = li_totrows;
	   if ((li_incluir==1) || (li_cambiar==1))
		  {    	 	 
		    f.operacion.value="GUARDAR";
		    f.action="sigesp_soc_p_anulacion_orden_compra.php";
		    f.submit(); 
		  } 
	   else
		  {
		    alert("No tiene permiso para realizar esta operación !!!");
		  }
	 }
}

function rellenar_cad(cadena,longitud)
{
	var mystring = new String(cadena);
	cadena_ceros = "";
	lencad       = mystring.length;
    total        =longitud-lencad;
    if (cadena!="")
	   {
	     for (i=1;i<=total;i++)
		     {
			   cadena_ceros=cadena_ceros+"0";
		     }
	     cadena=cadena_ceros+cadena;
		 f.txtnumordcom.value=cadena;
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
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>