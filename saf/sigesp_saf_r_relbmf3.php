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
require_once("class_funciones_activos.php");
$io_fun_activo=new class_funciones_activos();
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_r_relbmf3.php",$ls_permisos,$la_seguridad,$la_permisos);
require_once("sigesp_saf_c_activo.php");
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
$io_saf_tipcat= new sigesp_saf_c_activo();
$ls_rbtipocat=$io_saf_tipcat->uf_select_valor_config($ls_codemp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Relaci&oacute;n de Bienes Muebles Faltantes</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
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
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40" class="cd-logo"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Activos Fijos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
    <?php 
    if ($ls_rbtipocat == 1) 
    {
   ?>
   <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_csc.js"></script></td>
  <?php 
    }
	elseif ($ls_rbtipocat == 2)
	{
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_cgr.js"></script></td>
  <?php 
	}
	else
	{
   ?>
	<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php 
	}
   ?>
  </tr>
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:uf_mostrar_reporte();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a><a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" title="Ayuda" height="20"></td>
  </tr>
</table>
<?php
 
  
require_once("../shared/class_folder/sigesp_include.php");
$io_in=new sigesp_include();
$con=$io_in->uf_conectar();

require_once("../shared/class_folder/class_datastore.php");
$io_ds=new class_datastore();

require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);

require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();

require_once("../shared/class_folder/class_funciones.php");
$io_funcion=new class_funciones(); 

require_once("../shared/class_folder/grid_param.php");
$grid=new grid_param();


$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
}
if ($ls_operacion=="REPORT")
{
	$ld_fecdesde=$_POST["txtdesde"];
	$ld_fechasta=$_POST["txthasta"];
	$ls_coddesde=$_POST["txtcoddesde"];
	$ls_codhasta=$_POST["txtcodhasta"];
	if(($ls_coddesde!="")&&($ls_codhasta!=""))
	{
		$ls_desccod=" y desde el articulo ".$ls_coddesde." hasta el ".$ls_codhasta;
	}
	else
	{
		$ls_desccod="";
	}
	$ls_evento="REPORT";
	$ls_descripcion="Generó un reporte de Relacion de Bienes Muebles Faltantes. Desde el  ". $ld_fecdesde ." hasta el ".$ld_fechasta.$ls_desccod;
	$lb_variable= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
							$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
							$la_seguridad["ventanas"],$ls_descripcion);
}


?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

  <table width="542" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="543" colspan="2" class="titulo-ventana">Relaci&oacute;n de Bienes Muebles Faltantes </td>
    </tr>
  </table>
  <table width="542" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="111"></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><table width="497" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr style="visibility:hidden">
          <td colspan="2"><p><strong>Reporte en</strong>
              <select name="cmbbsf" id="cmbbsf">
                <option value="0" selected>Bs.</option>
                <option value="1">Bs.F.</option>
              </select>
          </p>
            </td>
        </tr>
		 <tr>
      <td height="22" colspan="3" align="center">
            <div align="left" class="style14"><strong>Activos</strong></div>
          </div>
        </div>
      </div></td>
	  
    </tr>
        <tr>
          <td width="77"><div align="right">Desde</div></td>
          <td width="418" height="22"><div align="left">
              <input name="txtcoddesde" type="text" id="txtcoddesde" size="21" maxlength="20"  style="text-align:center ">
              <a href="javascript:uf_catalogo_activo('txtcoddesde','txtdendesde');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> 
              <input name="txtdendesde" type="text" class="sin-borde" id="txtdendesde" size="40" readonly>
              <input name="txtseract" type="hidden" id="txtseract">
              <input name="txtideact" type="hidden" id="txtideact">
          </div>
            <div align="left"> </div></td>
        </tr>
        <tr>
          <td height="10"><div align="right"><span class="style1 style14">Hasta</span></div></td>
          <td height="22"><div align="left">
              <input name="txtcodhasta" type="text" id="txtcodhasta" size="21" maxlength="20"  style="text-align:center">
              <a href="javascript:uf_catalogo_activo('txtcodhasta','txtdenhasta');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
              <input name="txtdenhasta" type="text" class="sin-borde" id="txtdenhasta" size="40" readonly>
          </div>
            <div align="left"> </div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><div align="right" class="style1 style14"></div>        
            <div align="left">
          <input name="hidunidad" type="hidden" id="hidunidad">
          <input name="hidstatus" type="hidden" id="hidstatus">
        </div></td>
    </tr>
    
    <tr>
      <td height="22" colspan="3" align="center"><div align="left" class="style14">
        <div align="left" class="style14">
          <div align="center">
            <table width="497" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td height="22" colspan="5"><strong>Intervalo de Fecha</strong></td>
              </tr>
              <tr>
                <td width="91"><div align="right">Desde</div></td>
                <td width="88"><input name="txtdesde" type="text" id="txtdesde"  onKeyPress="ue_separadores(this,'/',patron,true);" size="15" maxlength="10"  datepicker="true"></td>
                <td width="64"><div align="right">Hasta</div></td>
                <td width="151"><div align="left">
                    <input name="txthasta" type="text" id="txthasta"  onKeyPress="ue_separadores(this,'/',patron,true);" size="15" maxlength="10"  datepicker="true">
                </div></td>
                <td width="108">&nbsp;</td>
              </tr>
            </table>
          </div>
        </div>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><div align="left" class="style14">
        <div align="left" class="style14">
          <div align="left" class="style14">
            <div align="left" class="style14"></div>
          </div>
        </div>
      </div></td>
	  
    </tr>
	    <tr>
      <td height="22" colspan="3" align="center"><div align="left" class="style14">
        <div align="left" class="style14">
          <div align="left" class="style14">
            <div align="left" class="style14">
              <table width="510" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                <tr>
                  <td width="140"><strong>Unidad Administrativa </strong></td>
                  <td width="362">&nbsp;</td>
                </tr>
                <tr>
                  <td><div align="right">Servicio </div></td>
                  <td height="22"><input name="txtcoduni" type="text" id="txtcoduni" size="15"  style="text-align:center" readonly>
                      <a href="javascript: uf_unidad();"><img src="../shared/imagebank/tools15/buscar.gif" alt="BUSCAR" width="15" height="15" border="0"></a>
                      <input name="txtdenuni" type="text" class="sin-borde" id="txtdenuni" size="40" readonly>
                  </td>
                </tr>
                <tr>
                  <td><div align="right">Dependencia </div></td>
                  <td height="22"><input name="txtcoduniadm" type="text" id="txtcoduniadm" size="15"  style="text-align:center" readonly>
                      <a href="javascript: uf_dependencia('unidep');"><img src="../shared/imagebank/tools15/buscar.gif" alt="BUSCAR" width="15" height="15" border="0"></a>
                      <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" size="40" readonly></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div></td>
	  
    </tr>
	    <tr>
      <td height="22" colspan="3" align="center"><div align="left" class="style14">
        <div align="left" class="style14">
          <div align="left" class="style14">
            <div align="left" class="style14"></div>
          </div>
        </div>
      </div></td>
	  
    </tr>
	    <tr>
      <td height="22" colspan="3" align="center"><div align="left" class="style14">
        <div align="left" class="style14">
          <div align="center">
              <table width="497" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
                <tr>
                <td width="85"><strong>Clasificaci&oacute;n </strong></td>
                <td width="410">&nbsp;</td>
                </tr>
                <tr>
                  <td><div align="right">Grupo</div></td>
                  <td><input name="txtcodgru" type="text" id="txtcodgru" size="21" maxlength="20"  style="text-align:center ">
                      <a href="javascript:ue_grupo();"><img src="../shared/imagebank/tools15/buscar.gif" alt="BUSCAR" width="15" height="15" border="0"></a>
                      <input name="txtdengru" type="text" class="sin-borde" id="txtdengru" size="40" readonly>
					  <input name="operacion" type="hidden" id="operacion3">
                      <input name="buttonir" type="hidden" id="buttonir"></td>
                </tr>
                <tr>
                  <td><div align="right">Subgrupo</div></td>
                  <td><input name="txtcodsubgru" type="text" id="txtcodsubgru" size="21" maxlength="20"  style="text-align:center ">
                      <a href="javascript:ue_subgrupo();"><img src="../shared/imagebank/tools15/buscar.gif" alt="BUSCAR" width="15" height="15" border="0"></a>
                      <input name="txtdensubgru" type="text" class="sin-borde" id="txtdensubgru" size="40" readonly>
					  <input name="operacion2" type="hidden" id="operacion22"></td>
                <tr>
                  <td><div align="right">Seccion</div></td>
                  <td><input name="txtcodsec" type="text" id="txtcodsec" size="21" maxlength="20"  style="text-align:center ">
                      <a href="javascript:ue_seccion();"><img src="../shared/imagebank/tools15/buscar.gif" alt="BUSCAR" width="15" height="15" border="0"></a>
                      <input name="txtdensec" type="text" class="sin-borde" id="txtdensec" size="40" readonly>
					  <input name="operacion3" type="hidden" id="operacion32"></td>
                </tr>
				
              </table>
            </div>    
        </div>
      </div></td>
    </tr>
	    <tr>
      <td height="22" colspan="3" align="center"><div align="left" class="style14">
        <div align="left" class="style14">
          <div align="left" class="style14">
            <div align="left" class="style14"></div>
          </div>
        </div>
      </div></td>
    </tr>
    <tr>
	    <tr>
      <td height="22" colspan="3" align="center"><div align="left" class="style14">
        <div align="left" class="style14">
          <div align="center">
            <table width="497" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
                <tr>
                  <td colspan="2"><strong>Nro. de Comprobante</strong></td>
                </tr>
                <tr>
                  <td width="127"><div align="right">Comprobante
                  </div>
                  <div align="right">  </div></td>
                  <td width="368" height="22"><input name="codcmpmov" type="text" id="codcmpmov" size="15" style="text-align:center" readonly>
                    <a href="javascript: uf_desincorporaciones('codcmpmov');"><img src="../shared/imagebank/tools15/buscar.gif" alt="BUSCAR" width="15" height="15" border="0"></a>
				  </td>
                </tr>
                      </table>
          </div>
        </div>
      </div></td>
    </tr>
	    <tr>
	    <tr>
      <td height="22" colspan="3" align="center"><div align="left" class="style14">
        <div align="left" class="style14"></div>
      </div></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left">
        <table width="497" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="2"><span class="style14"><strong>Ordenado Por</strong></span></td>
          </tr>
          <tr>
            <td width="175"><div align="center"><strong>Activo</strong></div></td>
            <td height="22">&nbsp;</td>
          </tr>
          <tr>
            <td height="22"><div align="right">C&oacute;digo
                    <input name="radioordenact" type="radio" class="sin-borde" value="radiobutton" checked>
              </div></td>
            <td width="333" height="22">&nbsp;</td>
          </tr>
          <tr>
            <td height="22"><div align="right">Denominaci&oacute;n
                    <input name="radioordenact" type="radio" class="sin-borde" value="radiobutton">
              </div></td>
            <td height="22">&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="24" colspan="3" align="center"><div align="right">
      <input name="operacion"   type="hidden"   id="operacion2"   value="<?php print $ls_operacion;?>">
      </div></td>
    </tr>
    <tr>
      <td colspan="3" align="center">
        <div align="center">
          <p></p>
      </div></td>
    </tr>
  </table>
    <p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
<input name="txtcodcau" type="hidden" id="txtcodcau">
<input name="txtdencau" type="hidden" id="txtdencau">
<input name="txtfeccmp" type="hidden" id="txtfeccmp">
<input name="txtdescmp" type="hidden" id="txtdescmp">
<input name="hidestpromov" type="hidden" id="hidestpromov">
<input name="hidstatus" type="hidden" id="hidstatus">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

	function uf_catalogo_activo(ls_coddestino,ls_dendestino)
	{
		window.open("sigesp_saf_cat_activo.php?coddestino="+ ls_coddestino +"&dendestino="+ ls_dendestino +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=580,height=400,left=120,top=70,location=no,resizable=yes");
	}
	
	function uf_unidad()
	{
		ls_destino="SERVICIO";
		window.open("sigesp_saf_cat_unidadejecutora.php?destino="+ ls_destino +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=580,height=400,left=120,top=70,location=no,resizable=yes");
	}
	
	function uf_dependencia(ls_destino)
	{
		ls_destino="activo";
		window.open("sigesp_saf_cat_unidadejecutora.php?destino="+ ls_destino +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=580,height=400,left=120,top=70,location=no,resizable=yes");
	}
	
	function uf_desincorporaciones(ls_coddestino)
	{
	    window.open("sigesp_saf_cat_desincorporaciones.php?coddestino="+ ls_coddestino +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=70,location=no,resizable=yes");
	}
	
	function ue_validarvacio(valor)
    {
	var texto;
	while(''+valor.charAt(0)==' ')
	{
		valor=valor.substring(1,valor.length)
	}
	texto = valor;
	return texto;
    }

	
	function ue_grupo()
{
	f=document.form1;
    window.open("sigesp_saf_cat_grupo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	f.txtcodsubgru.value="";
	f.txtdensubgru.value="";
	f.txtcodsec.value="";
	f.txtdensec.value="";

}
function ue_subgrupo()
{
	f=document.form1;
//	codigo=f.txtcodgru.value;
	codgru=ue_validarvacio(f.txtcodgru.value);
	if(codgru!="---")
	{
	    dengru = f.txtdengru.value;
		window.open("sigesp_saf_cat_subgrupo.php?txtcodgru="+codgru+"&txtdengru="+dengru+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		f.txtcodsec.value="";
		f.txtdensec.value="";
	}
	else
	{
		alert("Debe seleccionar un grupo.");
	}
}

function ue_seccion()
{
	f=document.form1;
	codgru=ue_validarvacio(f.txtcodgru.value);
	codsubgru=ue_validarvacio(f.txtcodsubgru.value);
	dengru = f.txtdengru.value;
	densubgru = f.txtdensubgru.value;
	if((codgru!="---")||(codsubgru!="---"))
	{
		window.open("sigesp_saf_cat_seccion.php?txtcodgru="+codgru+"&txtcodsubgru="+codsubgru+"&txtdengru="+dengru+"&txtdensubgru="+densubgru+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("Debe seleccionar un grupo y un subgrupo.");
	}
}

	function uf_mostrar_reporte()
	{
	 if (document.form1.codcmpmov.value != "")
	 {
			f=document.form1;
			li_imprimir=f.imprimir.value;
			if(li_imprimir==1)
			{
				ls_coddesde= f.txtcoddesde.value;
				ls_codhasta= f.txtcodhasta.value;
				ls_desde= f.txtdesde.value;
				ls_hasta= f.txthasta.value;
				ls_coduniadm= f.txtcoduni.value;
				ls_denuniadm= f.txtdenuni.value;
				ls_codunidep= f.txtcoduniadm.value;
				ls_denunidep= f.txtdenuniadm.value;
				ls_grupo= f.txtcodgru.value;
				ls_subgrupo= f.txtcodsubgru.value;
				ls_seccion= f.txtcodsec.value;
				ls_cmpmov_desde= f.codcmpmov.value;
				ls_cmpmov_hasta= f.codcmpmov.value;
				tipoformato = f.cmbbsf.value;
				if(f.radioordenact[0].checked)
				{
					li_ordenact=0;
				}
				else
				{
					li_ordenact=1;
				}
				window.open("reportes/sigesp_saf_rpp_relbmf3.php?ordenact="+li_ordenact+"&coddesde="+ls_coddesde+"&codhasta="+ls_codhasta+"&desde="+ls_desde+"&hasta="+ls_hasta+"&codgru="+ls_grupo+"&codsubgru="+ls_subgrupo+"&codsec="+ls_seccion+"&coduniadm="+ls_coduniadm+"&denuniadm="+ls_denuniadm+"&tipoformato="+tipoformato+"&cmpmov_desde="+ls_cmpmov_desde+"&cmpmov_hasta="+ls_cmpmov_hasta+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
				f.operacion.value="REPORT";
				f.action="sigesp_siv_r_relbmf3.php";
			}
			else
			{alert("No tiene permiso para realizar esta operación");}
	 }
	 else
	 {
	  alert("DEBE SELECCIONAR EL COMPROBANTE A IMPRIMIR");
	 }	
	}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

//--------------------------------------------------------
//	Función que obtiene el valor de el radio button
//--------------------------------------------------------
   function actualizaValor(oRad)
   { 
    var i 
	f=document.form1;
    for (i=0;i<f.radiostatus.length;i++)
	{ 
       if (f.radiostatus[i].checked) 
          break; 
    } 
    valor= i;
	f.hidradio.value=i;
   } 

//--------------------------------------------------------
//	Función que da formato a la fecha colocando los separadores (/).
//--------------------------------------------------------
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++){
			val2 += val[r]	
		}
		if(nums){
			for(z=0;z<val2.length;z++){
				if(isNaN(val2.charAt(z))){
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++){
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++){
			if(q ==0){
				val = val3[q]
			}
			else{
				if(val3[q] != ""){
					val += sep + val3[q]
					}
			}
		}
	d.value = val
	d.valant = val
	}
}

//--------------------------------------------------------
//	Función que valida que un intervalo de tiempo sea valido
//--------------------------------------------------------
   function ue_comparar_intervalo()
   { 

	f=document.form1;
   	ld_desde="f.txtdesde";
   	ld_hasta="f.txthasta";
	var valido = false; 
    var diad = f.txtdesde.value.substr(0, 2); 
    var mesd = f.txtdesde.value.substr(3, 2); 
    var anod = f.txtdesde.value.substr(6, 4); 
    var diah = f.txthasta.value.substr(0, 2); 
    var mesh = f.txthasta.value.substr(3, 2); 
    var anoh = f.txthasta.value.substr(6, 4); 
    
	if (anod < anoh)
	{
		 valido = true; 
	 }
    else 
	{ 
     if (anod == anoh)
	 { 
      if (mesd < mesh)
	  {
	   valido = true; 
	  }
      else 
	  { 
       if (mesd == mesh)
	   {
 		if (diad <= diah)
		{
		 valido = true; 
		}
	   }
      } 
     } 
    } 
    if (valido==false)
	{
		alert("El rango de fecha es invalido");
		f.txtdesde.value="";
		f.txthasta.value="";
		
	} 
	return valido;
   } 

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>

</html>
