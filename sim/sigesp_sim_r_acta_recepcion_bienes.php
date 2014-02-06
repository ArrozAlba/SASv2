<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";
   }
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_inventario.php");
$io_fun_inventario=new class_funciones_inventario();
$io_fun_inventario->uf_load_seguridad("SIM","sigesp_sim_r_acta_recepcion_bienes.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
<title>Acta de Recepcion de Bienes </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../spi/js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
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
-->
</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
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
    <td width="483" height="20" bgcolor="#E7E7E7" class="cd-menu"><span class="Estilo2 descripcion_sistema"><strong>Sistema de Inventario</strong></span></td>
    <td height="20" colspan="6" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>

    <td width="18" height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"></div></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu">&nbsp;</td>
    <td width="295" height="20" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../sim/js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_showouput();"><img src="../shared/imagebank/tools20/imprimir.gif" width="20" height="20" border="0"></a><a href="../sim/sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
  <?php

  $_SESSION["ls_codtienda"] = '0001';


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

if	(array_key_exists("txtnumordcom",$_POST))
	{
	  $ls_numordecom=$_POST["txtnumordcom"];
    }
else
	{
	  $ls_numordcom="";
	}
?>
</div>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
		$io_fun_inventario->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
		unset($io_fun_inventario);
	//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////
?>
  <table width="400" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="604" colspan="2" class="titulo-ventana">Acta de Recepcion de Bienes </td>
    </tr>
  </table>
  <table width="400" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="463"></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left">
        <table width="364" height="51" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="formato-blanco">
            <td height="13" colspan="2">&nbsp;</td>
          </tr>

                  <?php
					if ($ls_codtie == '0001') {

					?>

                   <input type="hidden" name="hdnagrotienda" value=""/>

					<tr>
		                <td height="22" align="right">Desde Unidad Operativa de Suministro:</td>
		                <td colspan="2" >

		                <input name="txtcodtienda_desde" type="text" id="txtcodtienda_desde" size="30">
		                <a href="javascript: ue_buscar_tienda('desde');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>

					<tr>
		                <td height="22" align="right">Hasta Unidad Operativa de Suministro:</td>
		                <td colspan="2" >
		                <input name="txtcodtienda_hasta" type="text" id="txtcodtienda_hasta" size="30">
		                <a href="javascript: ue_buscar_tienda('hasta');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>


					<?php
					}
					?>

          <tr>



            <td width="155" height="34"><div align="right"><strong>Orden de Compra </strong></div></td>
            <td width="207"><div align="left">
                <input name="txtnumordcom" type="text" id="txtnumordcom" style="text-align:center" value="<?php print $ls_numordcom; ?>" size="20">
            <a href="javascript:catalogo_ordenes_compra();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Ordenes de Compra"></a></div></td>
            </tr>
        </table>
      </div>        </td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="right"> <span class="Estilo1">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></a></div></td>
    </tr>
  </table>
  <div align="left"></div>
  <p align="center">&nbsp;</p>
</form>
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
	}else {
	  f.hdnagrotienda.value='hasta';
	  f.txtcodtienda_hasta.value="";
	}
	pagina="sigesp_cat_tienda.php";
	popupWin(pagina,"catalogo",600,250);
}


function ue_cargartienda (codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentapre,denpre,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)
{

	f=document.form1;
	if (f.hdnagrotienda.value == 'desde') {
	 f.txtcodtienda_desde.value=codtie;
	}else {
     f.txtcodtienda_hasta.value=codtie;
	}


}

/************************* Unidad Operativa de Suministro ***************************************/
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
		if (objeto=="txtcodprov1")
		   {
			 document.form1.txtcodprov1.value=cadena;
		   }
		 else
		   {
			 document.form1.txtcodprov2.value=cadena;
		   }
        }
}

function ue_showouput()
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
	   txtnumordcom = f.txtnumordcom.value;
	   pagina="reportes/sigesp_sim_rpp_acta_recepcion_bienes.php?txtnumordcom="+txtnumordcom+"&agro_desde="+ld_agro_desde+"&agro_hasta="+ld_agro_hasta;
	   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=700,resizable=yes,location=no");
	}
	else
	{
       alert("No tiene permiso para realizar esta operacion");
	}
}
function catalogo_ordenes_compra()
{
	   pagina="sigesp_cat_recepcion.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

</script>
</html>