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
$io_fun_activo->uf_load_seguridad("SIM","sigesp_sim_r_recepcion.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reporte de Entradas de Suministros a Almac&eacute;n </title>
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
    <td height="30" colspan="4" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="3" bgcolor="#E7E7E7" class="cd-menu"><span class="Estilo2 descripcion_sistema"><strong>Sistema de Inventario</strong></span></td>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
    <td width="1" height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"></div></td>
  </tr>
  <tr>
    <td height="20" colspan="3" bgcolor="#E7E7E7" class="cd-menu">&nbsp;</td>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"></div></td>
  </tr>
  <tr>
    <td height="20" colspan="4" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td width="20" height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:uf_mostrar_reporte();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><a href="../sim/sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
    <td width="446" bgcolor="#FFFFFF" class="toolbar"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
    <td width="292" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
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

//$_SESSION["ls_codtienda"] = '0002';
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
	if ($ls_operacion=="REPORT")
	{
		$ld_fecdesde=$_POST["txtdesde"];
		$ld_fechasta=$_POST["txthasta"];
		$ls_evento="REPORT";
		$ls_descripcion="Gener� un reporte de entrada de suministros a almac�n. Desde el  ". $ld_fecdesde ." hasta el ".$ld_fechasta;
		$lb_variable= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
								$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
								$la_seguridad["ventanas"],$ls_descripcion);
	}

?>

</div>
<p>&nbsp;</p>
<div>
<table width="508" height="228" border="0" align="center"  class="formato-blanco">
<tr>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<td colspan="15" align="center">

  <table width="442" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="438" colspan="2" class="titulo-ventana">Reporte de Entradas de Suministros a Almac&eacute;n </td>
    </tr>
  </table>


    <tr>
      <td colspan="3" align="center">
        <div align="left"></div></td>
    </tr>
    <tr>
      <td height="33" colspan="3" align="center">      <div align="left">
        <table width="435" height="155" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <td colspan="5" align="center"><div align="center">
  <table width="420" height="80" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">

          <?php
					if ($ls_codtie == '0001') {

					?>

                   <input type="hidden" name="hdnagrotienda" value=""/>

					<tr>
		                <td height="22" align="right"><strong>Desde Unidad Operativa de Suministro:</strong></td>
		                <td colspan="3" >

		                <input name="txtcodtienda_desde" type="text" id="txtcodtienda_desde" size="30">
		                <a href="javascript: ue_buscar_tienda('desde');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a>
						<input name="txtdentienda_desde" type="text" id="txtdentienda_desde" size="30" class="sin-borde"></td>
					</tr>

					<tr>
		                <td height="22" align="right"><strong>Hasta Unidad Operativa de Suministro:</strong></td>
		                <td colspan="3" >
		                <input name="txtcodtienda_hasta" type="text" id="txtcodtienda_hasta" size="30">
		                <a href="javascript: ue_buscar_tienda('hasta');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a>
		                <input name="txtdentienda_hasta" type="text" id="txtdentienda_hasta" size="30" class="sin-borde"></td>
					</tr>


					<?php
					}
					?>
</table>
</td>

          <tr>
            <td colspan="2"><strong>Intervalo de Fechas </strong></td>
            <td width="65">&nbsp;</td>
            <td width="172">&nbsp;</td>
            <td width="30">&nbsp;</td>
          </tr>
          <tr>
            <td width="81"><div align="right">Desde</div></td>
            <td width="65"><input name="txtdesde" type="text" id="txtdesde" size="15"  onKeyPress="ue_separadores(this,'/',patron,true);"  datepicker="true"></td>
            <td><div align="right">Hasta</div></td>
            <td><div align="left">
                <input name="txthasta" type="text" id="txthasta" size="15"  onKeyPress="ue_separadores(this,'/',patron,true);"  datepicker="true">
            </div></td>
            <td>&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="right" class="style1 style14"></div>        <div align="right" class="style1 style14"></div>        <div align="left">
          <input name="hidstatus" type="hidden" id="hidstatus">
        </div></td>
    </tr>

    <tr>
      <td colspan="3" align="center"><div align="left" class="style14"></div></td>
    </tr>
 <tr>
      <td colspan="3" align="center"><div align="left" class="style14"></div></td>
    </tr>


    <tr>
      <td colspan="3" align="center"><div align="left">
        <table width="435" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="2"><span class="style14"><strong>Ordenado Por</strong></span></td>
          </tr>
          <tr>
            <td width="170"><div align="center"><span class="style1"><strong>Fecha</strong></span></div></td>
            <td width="243"><div align="center"></div></td>
            </tr>
          <tr>
            <td height="22"><div align="right"></div>              <div align="right"><span class="style1">Ascendente
                      <input name="radioordenfec" type="radio" class="sin-borde" value="0" checked  c&oacute;digo>
            </span></div></td>
            <td><div align="right"></div></td>
            </tr>
          <tr>
            <td height="22"><div align="right"></div>              <div align="right">Descendente
                    <input name="radioordenfec" type="radio" class="sin-borde" value="1"  Nombre>
              </div></td>
            <td><div align="right"></div></td>
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
          <p><span class="Estilo1">
          </span></p>
      </div></td>
    </tr>

  <div align="left"></div>
  <p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
</p>
</td>
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
	  f.txtdentienda_hasta.value=""
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

	function uf_catalogotipoarticulo(ls_coddestino,ls_dendestino)
	{
		window.open("sigesp_catdinamic_tipoarticulo.php?coddestino="+ ls_coddestino +"&dendestino="+ ls_dendestino +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=120,top=70,location=no,resizable=yes");
	}

	function uf_mostrar_reporte()
	{
		valido=ue_comparar_intervalo();
		if(valido)
		{
			f=document.form1;
			li_imprimir=f.imprimir.value;
			if(li_imprimir)
			{
				ld_desde=  f.txtdesde.value;
				ld_hasta=  f.txthasta.value;

				if (<?php echo $ls_codtie; ?> == '0001') {
				  ld_agro_desde = f.txtcodtienda_desde.value;
			      ld_agro_hasta = f.txtcodtienda_hasta.value;
		        }else {
		          ld_agro_desde = '';
			      ld_agro_hasta = '';
		        }

				if ((ld_desde!="")&&(ld_hasta!=""))
				{
					if(f.radioordenfec[0].checked)
					{
						li_ordenfec=0;
					}
					else
					{
						li_ordenfec=1;
					}

					window.open("reportes/sigesp_sim_rpp_recepcion.php?ordenfec="+li_ordenfec+"&desde="+ld_desde+"&hasta="+ld_hasta+"&agro_desde="+ld_agro_desde+"&agro_hasta="+ld_agro_hasta+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
					f.operacion.value="REPORT";
					f.action="sigesp_sim_r_recepcion.php";
					//f.submit();
				}
				else
				{
					alert("Debe indicar un rango de fechas");
				}
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operaci�n");
		}
	}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

//--------------------------------------------------------
//	Funci�n que da formato a la fecha colocando los separadores (/).
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
//	Funci�n que valida que un intervalo de tiempo sea valido
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
