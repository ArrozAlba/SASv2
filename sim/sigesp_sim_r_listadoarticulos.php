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
require_once("class_funciones_inventario.php");
$io_fun_activo=new class_funciones_inventario();
$io_fun_activo->uf_load_seguridad("SIM","sigesp_sim_r_listadoarticulos.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Listado de Art&iacute;culos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
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
.Estilo1 {font-weight: bold}
.Estilo2 {
	font-size: 14px;
	color: #6699CC;
}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="557" height="20" bgcolor="#E7E7E7" class="cd-menu"><span class="Estilo2 descripcion_sistema"><strong>Sistema de Inventario</strong></span></td>
    <td width="221" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
    <td width="1" height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"></div></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu">&nbsp;</td>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="8" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:uf_mostrar_reporte();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a><a href="../sim/sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
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

require_once("sigesp_sim_c_articulo.php");
$io_siv=new sigesp_sim_c_articulo();

$ls_year=date("Y");
$ld_fecdes="01/01/".$ls_year;
$ld_fechas=date("d/m/Y");
//$_SESSION["ls_codtienda"] = '0001';


$la_emp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";
}
	$li_catalogo=$io_siv->uf_sim_select_catalogo($li_estnum);
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
		$ls_descripcion="Genero un reporte de resumen de inventario. Desde el  ". $ld_fecdesde ." hasta el ".$ld_fechasta.$ls_desccod;
		$lb_variable= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
								$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
								$la_seguridad["ventanas"],$ls_descripcion);
	}
	if($ls_operacion="")
	{

	}

?>
</div>
<p>&nbsp;</p>
<div align="center">
  <table width="557" height="159" border="0" class="formato-blanco">
    <tr>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<td colspan="15" align="center">
<table width="550" border="1" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <table width="517" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="518" colspan="2" class="titulo-ventana">Listado de Productos </td>
    </tr>
  </table>
  <table width="517" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td colspan="3"></td>
    </tr>
    <tr>
      <td colspan="5" align="center"><div align="center"></div>
      <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">

         <?php
					if ($ls_codtie == '0001') {

					?>

                   <input type="hidden" name="hdnagrotienda" value=""/>

					<tr>
		                <td width="159" height="22"  align="right">Desde Unidad Operativa de Suministro:</td>
		                <td colspan="2" >

		                <input name="txtcodtienda_desde" type="text" id="txtcodtienda_desde" size="30">
		                <a href="javascript: ue_buscar_tienda('desde');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a>
		                 <input name="txtdentienda_desde" type="text" id="txtdentienda_desde" size="30" class="sin-borde">
		                </td>
					</tr>

					<tr>
		                <td height="22"  align="right">Hasta Unidad Operativa de Suministro:</td>
		                <td colspan="2" >
		                <input name="txtcodtienda_hasta" type="text" id="txtcodtienda_hasta" size="30">
		                <a href="javascript: ue_buscar_tienda('hasta');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a>
		                <input name="txtdentienda_hasta" type="text" id="txtdentienda_hasta" size="30" class="sin-borde">
		                </td>
					</tr>


					<?php
					}
					?>

<br/>

</table>

<tr>
      <td width="99" height="22" align="center"><div align="right" class="style1 style14"></div></td>
      <td colspan="2" align="left">&nbsp;        </td>

</tr>
<tr>
 <td colspan="15" align="center">
<table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="2"><strong> Productos </strong></td>
        </tr>
        <tr>
          <td width="89"><div align="right">Desde</div></td>
          <td width="409" height="22"><div align="left">
              <input name="txtcoddesde" type="text" id="txtcoddesde" size="24" maxlength="20"  style="text-align:center ">
              <a href="javascript:uf_catalogoarticulo('txtcoddesde','txtdendesde');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
              <input name="txtdendesde" type="text" class="sin-borde" id="txtdenart2" size="40" readonly>
</div>
            <div align="left"> </div></td>
        </tr>
        <tr>
          <td height="10"><div align="right"><span class="style1 style14">Hasta</span></div></td>
          <td height="22"><div align="left">
              <input name="txtcodhasta" type="text" id="txtcodprov22" size="24" maxlength="20"  style="text-align:center">
              <a href="javascript:uf_catalogoarticulo('txtcodhasta','txtdenhasta');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
              <input name="txtdenhasta" type="text" class="sin-borde" id="txtdenhasta2" size="40" readonly>
</div>
            <div align="left"> </div></td>
        </tr>
        <tr>
          <td height="13" colspan="2">&nbsp;</td>
          </tr>
        <tr>
          <td height="10" width="189"><div align="right">Almac&eacute;n  </div></td>
          <td height="22"> <input name="txtnomfisalm" type="text"  id="txtnomfisalm" value="<?php print $ls_nomalmdes?>" size="60" class="sin-borde" readonly>
          <input name="txtcodalm" type="text" id="txtcodalm" size="24" style="text-align:center">
            <a href="javascript:uf_catalogoalmacen('txtcodalm','txtdenalm');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>

            <input name="txtdenalm" type="text" class="sin-borde" id="txtdenalm" size="40"></td>

        </tr>

        <tr>
          <td height="10"><div align="right">Tipo de Producto</div></td>
          <td height="22"><input name="txtcodtipart" type="text" id="txtcodtipart"  style="text-align:center" size="24">
            <a href="javascript:uf_catalogotipoarticulo('txtcodtipart','txtdentipart');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
            <input name="txtdentipart" type="text" class="sin-borde" id="txtdentipart" size="40">
            <input name="txtobstipart" type="hidden" id="txtobstipart">            </td>
        </tr>
	<?php
	if($li_catalogo==1)
	{
	?>
        <tr>
          <td height="10"><div align="right">SIGECOF</div></td>
          <td height="22"><input name="txtcodcatsig" type="text" id="txtcodcatsig"  style="text-align:center" size="24">
            <a href="javascript:uf_catalogosigecof();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
            <input name="txtdencatsig" type="text" class="sin-borde" id="txtdencatsig" size="40">
            <input name="txtspg_cuenta" type="hidden" id="txtspg_cuenta">
            </td>
        </tr>
	<?php
	}
	?>
   </table>
   </tr>
   </td>
    </tr>

    <tr>
      <td width="79" height="22" align="center"><div align="right" class="style1 style14"></div></td>

      <td width="50" align="center"><div align="right" class="style1 style14"></div></td>

      <div align="left">
        <input name="hidunidad" type="hidden" id="hidunidad">
        <input name="hidstatus" type="hidden" id="hidstatus">
        <input name="hidsigecof" type="hidden" id="hidsigecof" value="<?php print $li_catalogo ?>">
      </div>
    </tr>


    <tr>
      <td colspan="5" align="center"><div align="left">
        <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="5"><span class="style14"><strong>Ordenado Por</strong></span></td>
          </tr>
          <tr>
            <td width="111" height="33"><div align="right"></div>
          <div align="right">Productos
                    <input name="rdorden" type="radio" class="sin-borde" value="radiobutton" checked>
              </div></td>
            <td width="111"><div align="right">Almac&eacute;n
                <input name="rdorden" type="radio" class="sin-borde" value="radiobutton">
            </div></td>
            <td width="132"><div align="right">Tipo de Producto
              <input name="rdorden" type="radio" class="sin-borde" value="radiobutton">
            </div></td>
		<?php
		if($li_catalogo==1)
		{
		?>
            <td width="120"><div align="right">SIGECOF
              <input name="rdorden" type="radio" class="sin-borde" value="radiobutton">
            </div></td>
		<?php
		}
		?>
            <td width="24">&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="24" colspan="5" align="center"><div align="right">
      <input name="operacion"   type="hidden"   id="operacion2"   value="<?php print $ls_operacion;?>">
      </div></td>
    </tr>
    <tr>
      <td colspan="5" align="center">
        <div align="center">
          <p><span class="Estilo1">
          </span></p>
      </div></td>
    </tr>
  </table>
  <div align="left"></div>
  <p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
<input name="txtcodtiend" type="hidden" id="txtcodtiend">

 <input name="txtdesalm" type="hidden"  id="txtdesalm" value="<?php print $ls_nomalmdes?>" size="60" readonly>
  <input name="txttelalm" type="hidden" id="txttelalm4">
            <input name="txtubialm" type="hidden" id="txtubialm4">
            <input name="txtnomresalm" type="hidden" id="txtnomresalm3">
            <input name="txttelresalm" type="hidden" id="txttelresalm4">
            <input name="hidstatus" type="hidden" id="hidstatus4">
            <input name="txtdenunimed" type="hidden" id="txtdenunimed">
            <input name="txtunidad" type="hidden" id="txtunidad">
            <input name="txtobsunimed" type="hidden" id="txtobsunimed">
</p>
</table>
</tr>
</form>
</tr>
</table>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

/************************* Unidad Operativa de Suministro ***************************************/
function ue_buscar_tienda(intervalo)
{
	f=document.form1;
	if (intervalo == 'desde') {
	  f.hdnagrotienda.value='desde';
	  f.txtcodtienda_desde.value="";
	  f.txtdentienda_desde.value="";
	}else {
	  f.hdnagrotienda.value='hasta';
	  f.txtcodtienda_hasta.value="";
	  f.txtdentienda_hasta.value="";
	}
	pagina="sigesp_cat_tienda.php";
	popupWin(pagina,"catalogo",600,250);
}


function ue_cargartienda (codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentapre,denpre,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)
{

	f=document.form1;
	if (f.hdnagrotienda.value == 'desde') {
	 f.txtcodtienda_desde.value=codtie;
	 f.txtdentienda_desde.value=nomtie;
	}else {
     f.txtcodtienda_hasta.value=codtie;
     f.txtdentienda_hasta.value=nomtie;
	}


}

/************************* Unidad Operativa de Suministro ***************************************/


	function uf_catalogoarticulo(ls_coddestino,ls_dendestino)
	{
		window.open("sigesp_catdinamic_articulom.php?coddestino="+ ls_coddestino +"&dendestino="+ ls_dendestino +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=120,top=70,location=no,resizable=yes");
	}

	function uf_catalogoalmacen(ls_coddestino,ls_dendestino)
	{
		window.open("sigesp_catdinamic_almacen.php?coddestino="+ ls_coddestino +"&dendestino="+ ls_dendestino +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=120,top=70,location=no,resizable=yes");
	}



	function uf_catalogotipoarticulo(ls_coddestino,ls_dendestino)
	{
		window.open("sigesp_catdinamic_tipoarticulo.php?coddestino="+ ls_coddestino +"&dendestino="+ ls_dendestino +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=120,top=70,location=no,resizable=yes");
	}

	function uf_catalogosigecof()
	{
		window.open("sigesp_sim_cat_sigecof.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=120,top=70,location=no,resizable=yes");
	}
	function uf_mostrar_reporte()
	{
		f=document.form1;
		li_imprimir=f.imprimir.value;

		if (<?php echo $ls_codtie; ?> == '0001') {
				  ld_agro_desde = f.txtcodtienda_desde.value;
			      ld_agro_hasta = f.txtcodtienda_hasta.value;
		        }else {
		          ld_agro_desde = '';
			      ld_agro_hasta = '';
		        }

		if(li_imprimir==1)
		{
			ls_coddesde= f.txtcoddesde.value;
			ls_codhasta= f.txtcodhasta.value;
			li_sigecof=f.hidsigecof.value;
			if(li_sigecof==1)
			{
				ls_codsigecof=f.txtcodcatsig.value;
				if(f.rdorden[3].checked){li_orden=3;}
			}
			else
			{ls_codsigecof="";}
			ls_codalm=f.txtcodalm.value;
			ls_codtipart=f.txtcodtipart.value;
			if(f.rdorden[0].checked){li_orden=0;}
			if(f.rdorden[1].checked){li_orden=1;}
			if(f.rdorden[2].checked){li_orden=2;}

			window.open("reportes/sigesp_sim_rpp_listadoarticulos.php?orden="+li_orden+"&coddesde="+ls_coddesde+"&codhasta="+ls_codhasta+"&codsigecof="+ls_codsigecof+"&codalm="+ls_codalm+"&codtipart="+ls_codtipart+"&agro_desde="+ld_agro_desde+"&agro_hasta="+ld_agro_hasta+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			f.operacion.value="REPORT";
			f.action="sigesp_sim_r_inventario.php";
			//f.submit();
		}
		else
		{alert("No tiene permiso para realizar esta operaci�n");}
	}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>

</html>
