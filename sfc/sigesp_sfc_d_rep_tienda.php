<?Php
/******************************************/
/* FECHA: 27/08/2007                      */
/* AUTOR: ING. ZULHEYMAR RODR�GUEZ        */
/******************************************/

session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_conexion.php'";
	 print "</script>";
   }
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Listado de Tiendas</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
.style6 {color: #000000}
.Estilo1 {
	font-size: 14px;
	color: #6699CC;
}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="476" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="302" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>	</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_cancelar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?Php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_rep_tienda.php";

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
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
$io_utilidad = new sigesp_sfc_class_utilidades();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$ls_codemp=$la_datemp["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_dentie="%/".$_POST["txtdentie"]."%";
	$ls_dentie1=$_POST["txtdentie"];
	$ls_orden=$_POST["combo_orden"];
	$ls_ordenarpor=$_POST["combo_ordenarpor"];
	$ls_codest1="%/".$_POST["cmbestado"]."%";
	$ls_codmun1="%/".$_POST["cmbmunicipio"]."%";
	$ls_codpar1="%/".$_POST["cmbparroquia"]."%";
	$ls_codest2=$_POST["cmbestado"];
	$ls_codmun2=$_POST["cmbmunicipio"];
	$ls_codpar2=$_POST["cmbparroquia"];
	$ls_codpai=$_POST["hidcodpai"];
	$ls_codest=$_POST["hidcodest"];
	$ls_codmun=$_POST["hidcodmun"];
	$ls_codpar=$_POST["hidcodpar"];
}
else
{
	$ls_operacion="";
    $ls_codtie="";
	$ls_orden="";
	$ls_ordenarpor="Null";
	$ls_codest="";
	$ls_codest="";
	$ls_codmun="";
	$ls_codpar="";
	$ls_codpai="058";
}
?>
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
    <table width="518" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="258"><div align="center">
            <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
              <tr>
                <td colspan="3" class="titulo-ventana">Listado de Unidad Operativa de Suministro (Filtrar) </td>
              </tr>
              <tr>
                <td colspan="3" class="sin-borde">&nbsp;
				<input name="hidcodpai" type="hidden" id="hidcodpai" value="<?PHP print $ls_codpai;?>">
				<input name="hidcodcli" type="hidden" id="hidcodcli">
				<input name="hidcodest" type="hidden" id="hidcodest" value="<?PHP print $ls_codest;?>">
				<input name="hidcodmun" type="hidden" id="hidcodmun" value="<?PHP print $ls_codmun;?>">
				<input name="hidcodpar" type="hidden" id="hidcodpar" value="<?PHP print $ls_codpar;?>">				</td>
              </tr>
              <tr>
                <td width="143" ><div align="right">
                  <input name="operacion" type="hidden" id="operacion" value="<? print $ls_operacion?>">
                  Ordenar por
                </div></td>
                <td width="153" ><p align="left">
                  <select name="combo_ordenarpor" size="1" >
				  <?php
				  if ($ls_ordenarpor=="Null")
				   {
				   ?>
				    <option value="Null" selected>Seleccione...</option>
				    <option value="tie.codtiend">C&oacute;digo de tienda</option>
				    <option value="tie.dentie">Nombre</option>
				    <option value="pai.despai">Pa&iacute;s</option>
				    <option value="est.desest">Estado</option>
				    <option value="mun.denmun">Municipio</option>
				    <option value="par.denpar">Parroquia</option>
				  <?php
				   }
				  elseif ($ls_ordenarpor=="tie.codtiend")
				   {
				    ?>
					<option value="Null" >Seleccione...</option>
				    <option value="tie.codtiend" selected >C&oacute;digo de tienda</option>
				    <option value="tie.dentie">Nombre</option>
				    <option value="pai.despai">Pa&iacute;s</option>
				    <option value="est.desest">Estado</option>
				    <option value="mun.dendmun">Municipio</option>
				    <option value="par.denpar">Parroquia</option>
				  <?php
				   }
				   elseif ($ls_ordenarpor=="tie.dentie")
				   {
				    ?>
					<option value="Null" >Seleccione...</option>
				    <option value="tie.codtiend">C&oacute;digo de tienda</option>
				    <option value="tie.dentie" selected="selected">Nombre</option>
				    <option value="pai.despai">Pa&iacute;s</option>
				    <option value="est.desest">Estado</option>
				    <option value="mun.denmun">Municipio</option>
				    <option value="par.denpar">Parroquia</option>
				  <?php
				    }
				   elseif ($ls_ordenarpor=="pai.despai")
				   {
				  ?>
				  <option value="Null" >Seleccione...</option>
				    <option value="tie.codtiend">C&oacute;digo de tienda</option>
				    <option value="tie.dentie">Nombre</option>
				    <option value="pai.despai">Pa&iacute;s</option>
				    <option value="est.desest">Estado</option>
				    <option value="mun.denmun">Municipio</option>
				    <option value="par.denpar">Parroquia</option>
					<?php
				   }
				   elseif ($ls_ordenarpor=="est.desest")
				   {
				  ?>
				  <option value="Null" >Seleccione...</option>
				    <option value="tie.codtiend">C&oacute;digo de tienda</option>
				    <option value="tie.dentie">Nombre</option>
				    <option value="pai.despai">Pa&iacute;s</option>
				    <option value="est.desest" selected="selected">Estado</option>
				    <option value="mun.denmun">Municipio</option>
				    <option value="par.denpar">Parroquia</option>
				 <?php
				   }
				   elseif ($ls_ordenarpor=="mun.denmun")
				   {
				   ?>
				    <option value="Null" >Seleccione...</option>
				    <option value="tie.codtiend" >C&oacute;digo de tienda</option>
				    <option value="tie.dentie">Nombre</option>
				    <option value="pai.despai">Pa&iacute;s</option>
				    <option value="est.desest">Estado</option>
				    <option value="mun.denmun" selected="selected">Municipio</option>
				    <option value="par.denpar">Parroquia</option>
				 <?php
				   }
				   else
				   {
				     ?>
					 <option value="Null" >Seleccione...</option>
				    <option value="tie.codtiend" >C&oacute;digo de tienda</option>
				    <option value="tie.dentie">Nombre</option>
				    <option value="pai.despai">Pa&iacute;s</option>
				    <option value="est.desest">Estado</option>
				    <option value="mun.denmun">Municipio</option>
				    <option value="par.denpar" selected="selected">Parroquia</option>
					 <?php
				   }
				  ?>
                  </select>
                </p>				</td>
                <td width="" >
				Orden<select name="combo_orden" size="1">
				<?php
				  if ($ls_orden=="ASC")
				   {
				   ?>
                  <option value="ASC" selected>ASC</option>
                  <option value="DESC">DESC</option>
				  <?php
				   }
				  else
				   {
				   ?>
                  <option value="ASC" >ASC</option>
                  <option value="DESC" selected>DESC</option>
				  <?php
				  }
				  ?>
                </select></td>
              </tr>
              <tr>
                <td height="22" align="right">Nombre </td>
                <td colspan="2" ><input name="txtdentie" type="text" id="txtdentie" VALUE="<?PHP print $ls_dentie1;?>"><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>
              </tr>
              <tr>
                <td height="32" align="right">Estado</td>
                <td><span class="style6">
                  <?Php

				   if($ls_codpai=="")
				    {
						$lb_valest=false;
					}
					else
					 {
				       $ls_sql="SELECT codest ,desest
                                FROM sigesp_estados
                                WHERE codpai='$ls_codpai' ORDER BY codest ASC";
					   $lb_valest=$io_utilidad->uf_datacombo($ls_sql,&$la_estado);
					 }

					if($lb_valest)
				     {
					   $io_datastore->data=$la_estado;
					   $li_totalfilas=$io_datastore->getRowCount("codest");
				     }
					 else
					 	$li_totalfilas=0;

				    ?>
                  <select name="cmbestado" size="1" id="cmbestado" onChange="javascript:ue_llenarcmb();">
                    <option value="">Seleccione...</option>
                    <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codest",$li_i);
					 $ls_desest=$io_datastore->getValue("desest",$li_i);
					 if ($ls_codigo==$ls_codest)
					 {
						  print "<option value='$ls_codigo' selected>$ls_desest</option>";
					 }
					 else if ($ls_codigo==$ls_codest2 and $ls_codest=="")
					 {
						print "<option value='$ls_codigo' selected>$ls_desest</option>";
					 }else{
					   print "<option value='$ls_codigo'>$ls_desest</option>";
					 }
					}
	                ?>
                  </select>
                </span></td>
              </tr>
              <tr>
                <td height="4" align="right">Municipio</td>
                <td><span class="style6"><?Php
					$lb_valmun=false;
					if($ls_codest=="")
					{
						$lb_valmun=false;
						if($ls_codest2=="")
					{
						$lb_valmun=false;
					}
					else
					 {
						 $ls_sql="SELECT codmun ,denmun
                                  FROM sigesp_municipio
                                  WHERE codpai='$ls_codpai' AND codest='".$ls_codest2."' ORDER BY codmun ASC";
				         $lb_valmun=$io_utilidad->uf_datacombo($ls_sql,&$la_municipio);
					 }
					}
					else
					 {
						 $ls_sql="SELECT codmun ,denmun
                                  FROM sigesp_municipio
                                  WHERE codpai='$ls_codpai' AND codest='".$ls_codest."' ORDER BY codmun ASC";
				         $lb_valmun=$io_utilidad->uf_datacombo($ls_sql,&$la_municipio);
					 }

					if($lb_valmun)
					{
						$io_datastore->data=$la_municipio;
						$li_totalfilas=$io_datastore->getRowCount("codmun");
					}
					else{$li_totalfilas=0;}
			    ?>
                  <select name="cmbmunicipio" size="1" id="cmbmunicipio" onChange="javascript:ue_llenarcmb();">
                  <option value="">Seleccione...</option>
                  <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
						 $ls_codigo=$io_datastore->getValue("codmun",$li_i);
						 $ls_denmun=$io_datastore->getValue("denmun",$li_i);
						 if ($ls_codigo==$ls_codmun)
						 {
							  print "<option value='$ls_codigo' selected>$ls_denmun</option>";
						 }
						 else if ($ls_codigo==$ls_codmun2)
						 {
						  print "<option value='$ls_codigo' selected>$ls_denmun</option>";
						 }else{
							  print "<option value='$ls_codigo'>$ls_denmun</option>";
						 }
					}
	            ?>
                </select>
                </span>


                </td>
              </tr>
              <tr>
                <td height="32" align="right">Parroquia</td>
                <td><?Php
				$lb_valpar=false;
			    if($ls_codmun=="")
					{
						$lb_valpar=false;
						 if($ls_codmun2=="")
					{
						$lb_valpar=false;
					}
					else
					 {
						 $ls_sql="SELECT codpar ,denpar
                                  FROM sigesp_parroquia
                                  WHERE codpai='$ls_codpai' AND codest='".$ls_codest2."' AND codmun='".$ls_codmun2."' ORDER BY codpar ASC";
				         $lb_valpar=$io_utilidad->uf_datacombo($ls_sql,&$la_parroquia);
					 }
					}
					else
					 {
						 $ls_sql="SELECT codpar ,denpar
                                  FROM sigesp_parroquia
                                  WHERE codpai='$ls_codpai' AND codest='".$ls_codest."' AND codmun='".$ls_codmun."' ORDER BY codpar ASC";
				         $lb_valpar=$io_utilidad->uf_datacombo($ls_sql,&$la_parroquia);
					 }


					if($lb_valpar)
					{
						$io_datastore->data=$la_parroquia;
						$li_totalfilas=$io_datastore->getRowCount("codpar");
					}
					else{$li_totalfilas=0;}
			    ?>
                  <select name="cmbparroquia" size="1" id="cmbparroquia" onChange="javascript:ue_llenarcmb();">
                  <option value="">Seleccione...</option>
                  <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codpar",$li_i);
					 $ls_denpar=$io_datastore->getValue("denpar",$li_i);
					 if ($ls_codigo==$ls_codpar)
					 {
						  print "<option value='$ls_codigo' selected>$ls_denpar</option>";
					 }
					 else if ($ls_codigo==$ls_codpar2)
					 {
						print "<option value='$ls_codigo' selected>$ls_denpar</option>";
					 }else{
					 print "<option value='$ls_codigo'>$ls_denpar</option>";
					 }
					}
	            ?>
                </select>

                </td>
              </tr>
            </table>
        </div></td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
<?php
if($ls_operacion=="VER")
{
        $ls_operacion="";
		if ($ls_ordenarpor!="Null")
		{
  	    $ls_sql="SELECT tie.*,mun.codmun,mun.denmun,par.codpar,par.denpar,pai.codpai,pai.despai,est.codest,est.desest FROM sfc_tienda tie, sigesp_municipio mun, sigesp_parroquia par, sigesp_pais pai, sigesp_estados est WHERE tie.codpai=pai.codpai AND par.codpai=est.codpai AND est.codest=tie.codest AND mun.codest=est.codest AND mun.codmun=tie.codmun AND mun.codmun=par.codmun AND par.codpar=tie.codpar AND tie.codmun=par.codmun AND tie.codest=par.codest AND tie.dentie like '".$ls_dentie."' AND est.codest like '".$ls_codest1."' AND mun.codmun like '".$ls_codmun1."' AND par.codpar like '".$ls_codpar1."' ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";
		}else{
		$ls_sql="SELECT tie.*,mun.denmun,par.denpar,pai.despai,est.desest FROM sfc_tienda tie, sigesp_municipio mun, sigesp_parroquia par, sigesp_pais pai, sigesp_estados est WHERE tie.codpai=pai.codpai AND par.codpai=est.codpai AND est.codest=tie.codest AND mun.codest=est.codest AND mun.codmun=tie.codmun AND mun.codmun=par.codmun AND par.codpar=tie.codpar AND tie.codmun=par.codmun AND tie.codest=par.codest AND tie.dentie like '".$ls_dentie."' AND est.codest like '".$ls_codest1."' AND mun.codmun like '".$ls_codmun1."' AND par.codpar like '".$ls_codpar1."';";
		}
		//print $ls_sql;
?>
     <script language="JavaScript">
   	 	var ls_sql="<?php print $ls_sql; ?>";
	   	pagina="reportes/sigesp_sfc_rep_tienda.php?sql="+ls_sql;
	  	popupWin(pagina,"catalogo",580,700);
     </script>
<?PHP
} elseif ($ls_operacion=="ACTUALIZAR")
{
$ls_codpai=$_POST["hidcodpai"];
$ls_codest=$_POST["hidcodest"];
$ls_codmun=$_POST["hidcodmun"];
$ls_codpar=$_POST["hidcodpar"];

} elseif ($ls_operacion=="CARGAR")
{
$ls_codpai=$_POST["hidcodpai"];
$ls_codest=$_POST["hidcodest"];
$ls_codmun=$_POST["hidcodmun"];
$ls_codpar=$_POST["hidcodpar"];

}
 elseif ($ls_operacion=="CANCELAR")
{
$ls_codpai="";
$ls_codest="";
$ls_codmun="";
$ls_codpar="";
$ls_dentie="";
}
?>
</body>
<script language="JavaScript">

  function aceptar(codcla,nomcla)
  {
    opener.ue_cargarclasificacion(codcla,nomcla);
	close();
  }

  function ue_ver()
  {
  f=document.form1;
   li_imprimir=f.imprimir.value;
if(li_imprimir==1)
{
  f.operacion.value="VER";
  f.action="sigesp_sfc_d_rep_tienda.php";
  f.submit();
  }
 else
	{alert("No tiene permiso para realizar esta operaci�n");}

  }
  function ue_cancelar()
  {

  f=document.form1;
  f.operacion.value="CANCELAR";
  f.combo_ordenarpor.value="";
  f.combo_orden.value="";
  f.txtdentie.value="";
  f.hidcodest.value="";
  f.hidcodmun.value="";
  f.hidcodpar.value="";
  f.action="sigesp_sfc_d_rep_tienda.php";
  f.submit();

  }
  function actualizar_combo()
  {
   f=document.form1;
  f.combo_ordenarpor.value="VER";
  f.action="sigesp_sfc_d_rep_tienda.php";
  f.submit();

  }
  function ue_llenarcmb()
        {
	        f=document.form1;
	        f.action="sigesp_sfc_d_rep_tienda.php";
	        f.operacion.value="ACTUALIZAR";
	        f.submit();
        }
	 function ue_buscar()
		{
            f=document.form1;
			f.operacion.value="";
			pagina="sigesp_cat_tienda.php";
			popupWin(pagina,"catalogo",600,250);
			/*li_leer=f.leer.value;
			if(li_leer==1)
			{
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}*/
		}

		function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion)
		{
			f=document.form1;
			f.txtdentie.value=nomtie;
			f.hidcodpai.value=codpai;
            f.hidcodest.value=codest;
			f.hidcodmun.value=codmun;
			f.hidcodpar.value=codpar;
            f.operacion.value="CARGAR";
			f.submit();

		}
</script>

<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
