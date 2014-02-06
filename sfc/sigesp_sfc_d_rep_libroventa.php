<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";
   }


$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Libro de Ventas</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 14px;
	color: #6699CC;
}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_rep_libroventa.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST))
	{

			$ls_permisos=             $_POST["permisos"];
			$la_permisos["leer"]=     $_POST["leer"];
			$la_permisos["incluir"]=  $_POST["incluir"];
			$la_permisos["cambiar"]=  $_POST["cambiar"];
			$la_permisos["eliminar"]= $_POST["eliminar"];
			$la_permisos["imprimir"]= $_POST["imprimir"];
			$la_permisos["anular"]=   $_POST["anular"];
			$la_permisos["ejecutar"]= $_POST["ejecutar"];

	}
	else
	{
		$la_permisos["leer"]="";
		$la_permisos["incluir"]="";
		$la_permisos["cambiar"]="";
		$la_permisos["eliminar"]="";
		$la_permisos["imprimir"]="";
		$la_permisos["anular"]="";
		$la_permisos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_permisos);
	}

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
require_once("../shared/class_folder/ddlb_meses.php");
$ddlb_mes   = new ddlb_meses();
$arr_date = getdate();
$ls_ano   = $arr_date['year'];
$ls_mes   = $arr_date['mon'];

?>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="500" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo1">Sistema de Facturacion</span></td>
    <td width="278" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_showouput();">
	<img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a>
	 </a><a href="javascript:ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" alt="Excel" width="20" height="20" border="0"></a>
	<img src="../shared/imagebank/tools20/ayuda.gif" alt="Eliminar" width="20" height="20" border="0"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
           <p>&nbsp;</p>


<div align="center">


<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer value='$la_permisos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir value='$la_permisos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar value='$la_permisos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_permisos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_permisos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular value='$la_permisos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_permisos[ejecutar]'>");
}
else
{

	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="592" height="108" border="0" cellpadding="0" cellspacing="0" class="contorno">
  <tr height="200">
  <td width="582" height="100" align="center" >

   <table width="560" height="150" border="0" cellpadding="0" cellspacing="0" class="contorno">
               <tr class="titulo-celdanew">
                 <td width="500" height="22" class="titulo-celdanew">Libro de Ventas</td>
               </tr>


                 <tr>
				  <td colspan="3" align="center"><div align="left"></div></td>
    			</tr>
	<tr>
 	<td height="83" colspan="3" align="center">

	 <table width="530"  height="100"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">


                   <?php
					if ($ls_codtie == '0001') {

					?>

                   <input type="hidden" name="hdnagrotienda" value=""/>

					<tr>
		                <td width="150" height="28" align="right">Desde Unidad Operativa de Suministro:</td>
		                <td colspan="3" >

		                <input name="txtdentienda_desde" type="text" id="txtdentienda_desde" size="30">
		                <input name="txtcodtienda_desde" type="hidden" id="txtcodtienda_desde" size="30">
		                <a href="javascript: ue_buscar_tienda('desde');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>

					<tr>
		                <td  width="150" height="28" align="right">Hasta Unidad Operativa de Suministro:</td>
		                <td colspan="3" >
		                <input name="txtdentienda_hasta" type="text" id="txtdentienda_hasta" size="30">
		                <input name="txtcodtienda_hasta" type="hidden" id="txtcodtienda_hasta" size="30">
		                <a href="javascript: ue_buscar_tienda('hasta');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>


					<?php
					}
					?>

                   <tr>

                    <td width="86" align="right">Fecha Desde:</td>
            		<td width="82"><input name="txtdesde" type="text" id="txtdesde" size="15" onKeyPress="ue_separadores(this,'/',patron,true);"  datepicker="true"></td>
            		<td align="right">Fecha Hasta:</td>
            		<td><input name="txthasta" type="text" id="txthasta" size="15" onKeyPress="ue_separadores(this,'/',patron,true);" datepicker="true"></td>
        <input name="txtcodtie" type="hidden" id="txtcodtie" size="15" value="<? print $ls_codtie ?>">
       </tr>
       </table></td>
	   </tr>
</table>
</td>
    </tr>
</table>


               <?php

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");



$io_in      = new sigesp_include();
$con        = $io_in->uf_conectar();
$io_ds      = new class_datastore();
$io_sql     = new class_sql($con);
$io_msg     = new class_mensajes();
$io_funcion = new class_funciones();
$la_emp     = $_SESSION["la_empresa"];
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion=$_POST["operacion"];
   }
else
   {
	 $ls_operacion="";
   }
  $ls_fechadesde=$_POST["txtdesde"];
  $ls_fechahasta=$_POST["txthasta"];

	  ?>
</body>
<script language="JavaScript">

/************************* TIENDA***************************************/
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
	 f.txtdentienda_desde.value=nomtie;
	}else {
     f.txtcodtienda_hasta.value=codtie;
     f.txtdentienda_hasta.value=nomtie;
	}


}

/************************* TIENDA***************************************/


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
   	ld_desde=f.txtdesde.value;
   	ld_hasta=f.txthasta.value;
	var valido = false;
	var diad = f.txtdesde.value.substr(0, 2);
    var mesd = f.txtdesde.value.substr(3, 2);
    var anod = f.txtdesde.value.substr(6, 4);
    var diah = f.txthasta.value.substr(0, 2);
    var mesh = f.txthasta.value.substr(3, 2);
    var anoh = f.txthasta.value.substr(6, 4);
	if(diad!="" && mesd!="" && anod!="" && diah!="" && mesh!="" && anoh!="")
	{
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
	}
    if (valido==false)
	{
		alert("El rango de fecha es invalido");
		f.txtdesde.value="";
		f.txthasta.value="";
	}
	return valido;
   }


function ue_showouput()
{
	f = document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
    //ls_mes = f.mes.value;
	//ls_ano = f.ano.value;

	ls_tienda=f.txtcodtie.value;
	if (ls_tienda =='0001')
	{
		ld_agro_desde = f.txtcodtienda_desde.value;
		ld_agro_hasta = f.txtcodtienda_hasta.value;
	}
	else
	{
												ld_agro_desde = ls_tienda;
		ld_agro_hasta = ls_tienda;
	}
		ld_desde=f.txtdesde.value;
		ld_hasta=f.txthasta.value;


	if (ld_desde=='')
	{
		alert("Debe selecionar una Fecha");
	}
	else
	{
		if (ld_hasta=='')
		{
		alert("Debe selecionar una Fecha");
		}
		else
		{
			valido=ue_comparar_intervalo();
			if(valido)
			{
				//pagina = "reportes/sigesp_sfc_rep_libroventa.php?hidmes="+ls_mes+"&hidano="+ls_ano+"&desde="+ld_desde+"&hasta="+ld_hasta+"&agro_desde="+ld_agro_desde+"&agro_hasta="+ld_agro_hasta;
				pagina = "reportes/sigesp_sfc_rep_libroventa.php?desde="+ld_desde+"&hasta="+ld_hasta+"&agro_desde="+ld_agro_desde+"&agro_hasta="+ld_agro_hasta;
				window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
			}
		 }
	}
	}else
	{alert("No tiene permiso para realizar esta operaci�n");}
}


function ue_openexcel()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{

		//ls_mes = f.mes.value;
		//ls_ano = f.ano.value;
ls_tienda=f.txtcodtie.value;
	if (ls_tienda =='0001')
		{
				ld_agro_desde = f.txtcodtienda_desde.value;
						ld_agro_hasta = f.txtcodtienda_hasta.value;

							}
								else
									{
											ld_agro_desde = ls_tienda;
													ld_agro_hasta = ls_tienda;

														}

															ld_desde=f.txtdesde.value;
																ld_hasta=f.txthasta.value;



		if (ld_desde=='')
	{
		alert("Debe selecionar una Fecha");
	}
	else
	{
		if (ld_hasta=='')
		{
		alert("Debe selecionar una Fecha");
		}
		else
		{
			valido=ue_comparar_intervalo();
			if(valido)
			{


			pagina="reportes/sigesp_sfc_rep_libroventa_excel.php?desde="+ld_desde+"&hasta="+ld_hasta+"&agro_desde="+ld_agro_desde+"&agro_hasta="+ld_agro_hasta;
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");


			}
		 }
	}
	}
else
	{alert("No tiene permiso para realizar esta operaci�n");}

}



</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
