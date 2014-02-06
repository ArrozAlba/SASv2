<?php
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";
}



$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
//$_POST["txtcodsubcla"]="";
$ls_codsub="";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Existencias por Clasificaci&oacute;n</title>
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
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >
	<tr>
		<td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
	</tr>
	<tr>
    <td width="493" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="285" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
	<tr>
		<td height="20" colspan="2" class="cd-menu">
			<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>		</td>
	</tr>
	<tr>
		<td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
	</tr>
	<tr>
		<td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"></a><a href="javascript:ue_guardar();"></a><a href="javascript: ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" alt="Excel" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
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
	$ls_ventanas="sigesp_sfc_d_rep_existeclasificacion.php";

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
	$ls_codsub="";
	$ls_operacion=$_POST["operacion"];
	$ls_dencla=$_POST["txtdencla"];
	$ls_densub=$_POST["txtdensub"];
	$ls_denpro=$_POST["txtdenpro"];
	$ls_codcla=$_POST["txtcodcla"];
	$ls_codsub=$_POST["txtcodsubcla"];
	//print $_POST["txtcodsub"];
	$ls_estatus=$_POST["combo_estatus"];
	$ls_codalm="%".$ls_codtie."%";
	$ls_orden=$_POST["combo_orden"];
	$ls_ordenarpor=$_POST["combo_ordenarpor"];

	$ls_tienda_desde = $_POST["txtcodtienda_desde"];
	$ls_tienda_hasta = $_POST["txtcodtienda_hasta"];

}
else
{
	$ls_operacion="";
	$ls_dencla="";
	$ls_densub="";
	$ls_denpro="";
	$ls_codcla="";
	$ls_codsub="";
	$ls_estatus="Null";

	$ls_orden="";
	$ls_ordenarpor="Null";

}


?>
</div>
<p>&nbsp;</p>
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
  <table width="696" height="580" border="1" align="center" cellpadding="1" cellspacing="1">


  <div align="center"></div>
  <table width="487" height="300" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td colspan="3"></td>
    </tr>
    <tr>
      <td height="80" colspan="5" align="center">
        <div align="left">
         <table width="430" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
			<tr>
      			<td width="422" colspan="2" class="titulo-ventana">Existencias por Clasificaci&oacute;n (Filtrar)</td>
    		</tr>

            <tr>
              <td colspan="5"><span class="style14"><strong>Ordenar Por</strong></span></td>
            </tr>

            <tr>
			<td width="154" ><p align="right">
			  <select  name="combo_ordenarpor" size="1" >
                <?php
				if ($ls_ordenarpor=="Null")
				 {
				?>
                <option value="Null" selected>Seleccione...</option>
                <option value="cl.dencla">Clasificaci&oacute;n</option>
                <option value="sa.denart">Producto</option>
                <?php
							   }
							  elseif ($ls_ordenarpor=="cl.dencla")
							   {
								?>
                <option value="Null" >Seleccione...</option>
                <option value="cl.dencla">Clasificaci&oacute;n</option>
                <option value="sa.denart">Producto</option>
                <?php
							   }

							    elseif ($ls_ordenarpor=="sa.denart")
							   {
								?>
                <option value="Null" >Seleccione...</option>
                <option value="cl.dencla">Clasificaci&oacute;n</option>
                <option value="sa.denart">Producto</option>
                <?php
							   }
							 ?>
              </select>
			</p>			  </td>
						<td width="258" colspan="2" >
							Orden
							<select name="combo_orden" size="1">
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
			                </select>						</td>
			</tr>
          </table>
        </div></td>
    </tr>
    <tr>
      <td colspan="5" align="center">      <div align="left">
        <table width="430" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="2"><strong>Tipo de Busqueda </strong></td>
          </tr>


		 <?php

              if ($ls_codtie == '0001')
               {

				?>
                   <input type="hidden" name="hdnagrotienda" value=""/>
					<tr>
		                <td height="22" align="right">Desde Tienda:</td>
		                <td colspan="3" >
						<input name="txtdentienda_desde" type="text" id="txtdentienda_desde" size="30">
		                <input name="txtcodtienda_desde" type="hidden" id="txtcodtienda_desde" size="30">
		                <a href="javascript: ue_buscar_tienda('desde');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>

					<tr>
		                <td height="22" align="right">Hasta Tienda:</td>
		                <td colspan="3" >
		                <input name="txtdentienda_hasta" type="text" id="txtdentienda_hasta" size="30">
		                <input name="txtcodtienda_hasta" type="hidden" id="txtcodtienda_hasta" size="30">
		                <a href="javascript: ue_buscar_tienda('hasta');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>


				<?php
				}
				?>

		  <tr>
		      <td height="31" align="right">Clasificaci&oacute;n</td>
		      <td colspan="3" ><input name="txtdencla" type="text" id="txtdencla" size="30">
		      <a href="javascript: ue_buscar_clasificacion();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
		</tr>

		  <tr>
	      <tr>
            <td height="32" align="right">Subclasificaci&oacuten </td>
            <td>
              <input name="txtdensub" type="text" id="txtdensub" size="30">

            <a href="javascript:ue_catsubclasificacion();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
            <td>&nbsp;</td>
          </tr>

            <td width="55"><div align="right">Producto</div></td>
            <td width="373" height="30">
              <!--<input name="txtcodart" type="text" id="txtcodart" size="30" maxlength="30"  style="text-align:center "  onBlur="javascript:rellenar_cad(this.value,10,document.form1.txtcodprov1.name)"> !-->
             <input name="txtdenpro" type="text" id="txtdenpro" size="30">
		     <a href="javascript:ue_catproducto();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>

            </div>

            </tr>
          <tr>
			 <td width="143" height="25" ><div align="right">
                  <input name="estatus" type="hidden" id="estatus" value="<? print $ls_estatus?>">

                Existencia

                </div></td>
                <td width="153" ><p align="left">
                  <select name="combo_estatus" size="1">
                    <?php
				  if ($ls_estatus=="Null")
				   {
				   ?>
                    <option value="Null"  selected>Seleccione...</option>
                    <option value="N">Productos Sin Existencia</option>
                    <option value="C">Productos Con Existencia</option>

                    <?php
				   }
				  elseif($ls_estatus=="N")
				   {
				   ?>
                    <option value="Null"  selected>Seleccione...</option>
                    <option value="N">Productos Sin Existencia</option>
                    <option value="C">Productos Con Existencia</option>

                    <?php
				  }

				  elseif($ls_estatus=="C")
				   {
				   ?>
                    <option value="Null"  selected>Seleccione...</option>
                    <option value="N">Productos Sin Existencia</option>
                    <option value="C">Productos Con Existencia</option>

                    <?php
				  }

				  ?>
                  </select>

		  </tr>

		  <tr>
            <td height="19">&nbsp;</td>
            <td height="22">&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td width="116" align="center"><div align="right" class="style1 style14"></div></td>
      <td colspan="2" align="left">&nbsp;        </td>
      <td width="76" align="center"><div align="right" class="style1 style14"></div></td>
      <td width="244" align="center"><div align="left">

        <input name="hidunidad" type="hidden" id="hidunidad">
      </div></td>
    </tr>
    <tr>
      <td colspan="5" align="center"><div align="left" class="style14"></div></td>
    </tr>

    <tr>
      <td height="24" colspan="5" align="center"><div align="right">
      <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
	   <input name="txtcodcla"   type="hidden"   id="txtcodcla"   value="<?php print $ls_codcla;?>">
	    <input name="txtcodsubcla"   type="hidden"   id="txtcodsubcla"   value="<?php print $ls_codsub;?>">
      </div></td>
    </tr>

  </table>
</table>
</form>

<?php
function sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,$alias_tabla,$ls_codtie)
{

$add_sql = '';
if ($ls_tienda_desde=='') {

$add_sql = "$alias_tabla.codtiend='$ls_codtie'";

}else {

$add_sql = "$alias_tabla.codtiend  BETWEEN '$ls_tienda_desde' AND '$ls_tienda_hasta'";

}

return $add_sql;
}


if($ls_operacion=="VER")
{
	$ls_operacion="";
	$ls_suiche=false;
	if ($ls_ordenarpor!="Null")
	{

		$ls_suiche=true;

		if($ls_estatus=="Null")//Muestra todos los productos con y sin existencia
		{

		$ls_sql=" SELECT sa.codcla, sa.denart, p.codart, p.preven, p.codcar, a.existencia,u.denunimed,cl.dencla,su.den_sub,p.ultcosart " .
				"FROM  sfc_producto p,sfc_clasificacion cl,sfc_subclasificacion su,sim_articuloalmacen a,sim_unidadmedida u, sim_articulo sa" .
				" WHERE p.codemp=a.codemp AND p.codemp=sa.codemp AND p.codart=a.codart AND p.codart=sa.codart AND p.codtiend=a.codtiend" .
				" AND cl.codcla=su.codcla AND cl.codcla=sa.codcla AND su.cod_sub=sa.cod_sub AND a.codemp=sa.codemp AND a.codart=sa.codart " .
				" AND sa.codunimed=u.codunimed AND p.codemp='".$ls_codemp."' AND a.codalm ilike '".$ls_codalm."' AND sa.denart ilike '%".$ls_denpro."%'" .
				" AND cl.codcla ilike '".$ls_codcla."%' AND su.cod_sub like '%".$ls_codsub."%'" .
				" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie)." AND " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'a',$ls_codtie)."" .
				" ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";

		//print "1".$ls_sql;
		}
		elseif($ls_estatus=="N")// Productos sin existencia
		{
		$ls_sql="SELECT sa.codcla, sa.denart, p.codart, p.preven, p.codcar, a.existencia,u.denunimed,cl.dencla,su.den_sub,p.ultcosart " .
				"FROM  sfc_producto p,sfc_clasificacion cl,sfc_subclasificacion su,sim_articuloalmacen a,sim_unidadmedida u, sim_articulo sa" .
				" WHERE p.codemp=a.codemp AND p.codemp=sa.codemp AND p.codart=a.codart AND p.codart=sa.codart AND p.codtiend=a.codtiend" .
				" AND cl.codcla=su.codcla AND cl.codcla=sa.codcla AND su.cod_sub=sa.cod_sub AND a.codemp=sa.codemp AND a.codart=sa.codart " .
				" AND sa.codunimed=u.codunimed AND p.codemp='".$ls_codemp."' AND a.codalm ilike '".$ls_codalm."' AND sa.denart ilike '%".$ls_denpro."%'" .
				" AND cl.codcla ilike '".$ls_codcla."%' AND su.cod_sub like '%".$ls_codsub."%' " .
				" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie)." " .
				" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'a',$ls_codtie)." AND a.existencia=0" .
				" ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";

		//print "2".$ls_sql;
		}
		elseif($ls_estatus=="C")  //Productos con existencia
		{
		$ls_sql=" SELECT sa.codcla, sa.denart, p.codart, p.preven, p.codcar, a.existencia,u.denunimed,cl.dencla,su.den_sub,p.ultcosart " .
				"FROM  sfc_producto p,sfc_clasificacion cl,sfc_subclasificacion su,sim_articuloalmacen a,sim_unidadmedida u, sim_articulo sa" .
				" WHERE p.codemp=a.codemp AND p.codemp=sa.codemp AND p.codart=a.codart AND p.codart=sa.codart AND p.codtiend=a.codtiend" .
				" AND cl.codcla=su.codcla AND cl.codcla=sa.codcla AND su.cod_sub=sa.cod_sub AND a.codemp=sa.codemp AND a.codart=sa.codart " .
				" AND sa.codunimed=u.codunimed AND p.codemp='".$ls_codemp."' AND a.codalm ilike '".$ls_codalm."' AND sa.denart ilike '%".$ls_denpro."%'" .
				" AND cl.codcla ilike '".$ls_codcla."%' AND su.cod_sub like '%".$ls_codsub."%'" .
				" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie)." AND " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'a',$ls_codtie)." AND a.existencia!=0 ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";
		//print "3".$ls_sql;
		}


	}
	else
	{

	if($ls_estatus=="Null")
		{

		//print "CC-".$ls_codcla."<br>CS-".$ls_codsub;
		$ls_sql=" SELECT sa.codcla, sa.denart, p.codart, p.preven, p.codcar, a.existencia,u.denunimed,cl.dencla,su.den_sub,p.ultcosart " .
				"FROM  sfc_producto p,sfc_clasificacion cl,sfc_subclasificacion su,sim_articuloalmacen a,sim_unidadmedida u, sim_articulo sa" .
				" WHERE p.codemp=a.codemp AND p.codemp=sa.codemp AND p.codart=a.codart AND p.codart=sa.codart AND p.codtiend=a.codtiend" .
				" AND cl.codcla=su.codcla AND cl.codcla=sa.codcla AND su.cod_sub=sa.cod_sub AND a.codemp=sa.codemp AND a.codart=sa.codart " .
				" AND sa.codunimed=u.codunimed AND p.codemp='".$ls_codemp."' AND a.codalm ilike '".$ls_codalm."' AND sa.denart ilike '%".$ls_denpro."%'" .
				" AND cl.codcla ilike '".$ls_codcla."%' AND su.cod_sub like '%".$ls_codsub."%'" .
				" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie)." AND " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'a',$ls_codtie)." ORDER BY sa.denart,cl.dencla ASC;";
	//print "4".$ls_sql;

		}
		elseif($ls_estatus=="N")
		{
		$ls_sql=" SELECT sa.codcla, sa.denart, p.codart, p.preven, p.codcar, a.existencia,u.denunimed,cl.dencla,su.den_sub,p.ultcosart " .
				"FROM  sfc_producto p,sfc_clasificacion cl,sfc_subclasificacion su,sim_articuloalmacen a,sim_unidadmedida u, sim_articulo sa" .
				" WHERE p.codemp=a.codemp AND p.codemp=sa.codemp AND p.codart=a.codart AND p.codart=sa.codart AND p.codtiend=a.codtiend" .
				" AND cl.codcla=su.codcla AND cl.codcla=sa.codcla AND su.cod_sub=sa.cod_sub AND a.codemp=sa.codemp AND a.codart=sa.codart " .
				" AND sa.codunimed=u.codunimed AND p.codemp='".$ls_codemp."' AND a.codalm ilike '".$ls_codalm."' AND sa.denart ilike '%".$ls_denpro."%'" .
				" AND cl.codcla ilike '".$ls_codcla."%' AND su.cod_sub like '%".$ls_codsub."%'" .
				" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie)." AND " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'a',$ls_codtie)." AND a.existencia=0 ORDER BY sa.denart,cl.dencla ASC;";
		//print "5".$ls_sql;
		}
		elseif($ls_estatus=="C")
		{
		$ls_sql=" SELECT sa.codcla, sa.denart, p.codart, p.preven, p.codcar, a.existencia,u.denunimed,cl.dencla,su.den_sub,p.ultcosart " .
				"FROM  sfc_producto p,sfc_clasificacion cl,sfc_subclasificacion su,sim_articuloalmacen a,sim_unidadmedida u, sim_articulo sa" .
				" WHERE p.codemp=a.codemp AND p.codemp=sa.codemp AND p.codart=a.codart AND p.codart=sa.codart AND p.codtiend=a.codtiend" .
				" AND cl.codcla=su.codcla AND cl.codcla=sa.codcla AND su.cod_sub=sa.cod_sub AND a.codemp=sa.codemp AND a.codart=sa.codart " .
				" AND sa.codunimed=u.codunimed AND p.codemp='".$ls_codemp."' AND a.codalm ilike '".$ls_codalm."' AND sa.denart ilike '%".$ls_denpro."%'" .
				" AND cl.codcla ilike '".$ls_codcla."%' AND su.cod_sub like '%".$ls_codsub."%'" .
				" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie)." AND " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'a',$ls_codtie)." AND a.existencia!=0 ORDER BY sa.denart,cl.dencla ASC;";
		//print "6".$ls_sql;

		}


	}


	?>
		<script language="JavaScript">
		var ls_sql="<?php print $ls_sql; ?>";
		pagina="reportes/sigesp_sfc_rep_existeclasificacion.php?sql="+encodeURIComponent(ls_sql);
		popupWin(pagina,"catalogo",580,700);
		</script>
	<?php

	}

if ($ls_operacion=="EXCEL")
	{
	$ls_operacion="";
	$ls_suiche=false;
	if ($ls_ordenarpor!="Null")
	{

		$ls_suiche=true;

		if($ls_estatus=="Null")
		{
		$ls_sql=" SELECT sa.codcla, sa.denart, p.codart, p.preven, p.codcar, a.existencia,u.denunimed,cl.dencla,su.den_sub,p.ultcosart " .
				"FROM  sfc_producto p,sfc_clasificacion cl,sfc_subclasificacion su,sim_articuloalmacen a,sim_unidadmedida u, sim_articulo sa" .
				" WHERE p.codemp=a.codemp AND p.codemp=sa.codemp AND p.codart=a.codart AND p.codart=sa.codart AND p.codtiend=a.codtiend" .
				" AND cl.codcla=su.codcla AND cl.codcla=sa.codcla AND su.cod_sub=sa.cod_sub AND a.codemp=sa.codemp AND a.codart=sa.codart " .
				" AND sa.codunimed=u.codunimed AND p.codemp='".$ls_codemp."' AND a.codalm ilike '".$ls_codalm."' AND sa.denart ilike '%".$ls_denpro."%'" .
				" AND cl.codcla ilike '".$ls_codcla."%' AND su.cod_sub like '%".$ls_codsub."%'" .
				" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie)." AND " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'a',$ls_codtie)."" .
				" ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";

		//print "1".$ls_sql;
		}
		elseif($ls_estatus=="N")
		{
		$ls_sql="SELECT sa.codcla, sa.denart, p.codart, p.preven, p.codcar, a.existencia,u.denunimed,cl.dencla,su.den_sub,p.ultcosart " .
				"FROM  sfc_producto p,sfc_clasificacion cl,sfc_subclasificacion su,sim_articuloalmacen a,sim_unidadmedida u, sim_articulo sa" .
				" WHERE p.codemp=a.codemp AND p.codemp=sa.codemp AND p.codart=a.codart AND p.codart=sa.codart AND p.codtiend=a.codtiend" .
				" AND cl.codcla=su.codcla AND cl.codcla=sa.codcla AND su.cod_sub=sa.cod_sub AND a.codemp=sa.codemp AND a.codart=sa.codart " .
				" AND sa.codunimed=u.codunimed AND p.codemp='".$ls_codemp."' AND a.codalm ilike '".$ls_codalm."' AND sa.denart ilike '%".$ls_denpro."%'" .
				" AND cl.codcla ilike '".$ls_codcla."%' AND su.cod_sub like '%".$ls_codsub."%' " .
				" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie)." " .
				" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'a',$ls_codtie)." AND a.existencia=0" .
				" ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";
		//print "2".$ls_sql;
		}
		elseif($ls_estatus=="C")
		{
		$ls_sql=" SELECT sa.codcla, sa.denart, p.codart, p.preven, p.codcar, a.existencia,u.denunimed,cl.dencla,su.den_sub,p.ultcosart " .
				"FROM  sfc_producto p,sfc_clasificacion cl,sfc_subclasificacion su,sim_articuloalmacen a,sim_unidadmedida u, sim_articulo sa" .
				" WHERE p.codemp=a.codemp AND p.codemp=sa.codemp AND p.codart=a.codart AND p.codart=sa.codart AND p.codtiend=a.codtiend" .
				" AND cl.codcla=su.codcla AND cl.codcla=sa.codcla AND su.cod_sub=sa.cod_sub AND a.codemp=sa.codemp AND a.codart=sa.codart " .
				" AND sa.codunimed=u.codunimed AND p.codemp='".$ls_codemp."' AND a.codalm ilike '".$ls_codalm."' AND sa.denart ilike '%".$ls_denpro."%'" .
				" AND cl.codcla ilike '".$ls_codcla."%' AND su.cod_sub like '%".$ls_codsub."%'" .
				" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie)." AND " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'a',$ls_codtie)." AND a.existencia!=0 ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";
		//print "3".$ls_sql;
		}


	}
	else
	{

	if($ls_estatus=="Null")
		{

		//print "CC-".$ls_codcla."<br>CS-".$ls_codsub;
		$ls_sql=" SELECT sa.codcla, sa.denart, p.codart, p.preven, p.codcar, a.existencia,u.denunimed,cl.dencla,su.den_sub,p.ultcosart " .
				"FROM  sfc_producto p,sfc_clasificacion cl,sfc_subclasificacion su,sim_articuloalmacen a,sim_unidadmedida u, sim_articulo sa" .
				" WHERE p.codemp=a.codemp AND p.codemp=sa.codemp AND p.codart=a.codart AND p.codart=sa.codart AND p.codtiend=a.codtiend" .
				" AND cl.codcla=su.codcla AND cl.codcla=sa.codcla AND su.cod_sub=sa.cod_sub AND a.codemp=sa.codemp AND a.codart=sa.codart " .
				" AND sa.codunimed=u.codunimed AND p.codemp='".$ls_codemp."' AND a.codalm ilike '".$ls_codalm."' AND sa.denart ilike '%".$ls_denpro."%'" .
				" AND cl.codcla ilike '".$ls_codcla."%' AND su.cod_sub like '%".$ls_codsub."%'" .
				" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie)." AND " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'a',$ls_codtie)." ORDER BY sa.denart,cl.dencla ASC;";
	//print "4".$ls_sql;

		}
		elseif($ls_estatus=="N")
		{
		$ls_sql=" SELECT sa.codcla, sa.denart, p.codart, p.preven, p.codcar, a.existencia,u.denunimed,cl.dencla,su.den_sub,p.ultcosart " .
				"FROM  sfc_producto p,sfc_clasificacion cl,sfc_subclasificacion su,sim_articuloalmacen a,sim_unidadmedida u, sim_articulo sa" .
				" WHERE p.codemp=a.codemp AND p.codemp=sa.codemp AND p.codart=a.codart AND p.codart=sa.codart AND p.codtiend=a.codtiend" .
				" AND cl.codcla=su.codcla AND cl.codcla=sa.codcla AND su.cod_sub=sa.cod_sub AND a.codemp=sa.codemp AND a.codart=sa.codart " .
				" AND sa.codunimed=u.codunimed AND p.codemp='".$ls_codemp."' AND a.codalm ilike '".$ls_codalm."' AND sa.denart ilike '%".$ls_denpro."%'" .
				" AND cl.codcla ilike '".$ls_codcla."%' AND su.cod_sub like '%".$ls_codsub."%'" .
				" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie)." AND " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'a',$ls_codtie)." AND a.existencia=0 ORDER BY sa.denart,cl.dencla ASC;";
		//print "5".$ls_sql;
		}
		elseif($ls_estatus=="C")
		{
		$ls_sql=" SELECT sa.codcla, sa.denart, p.codart, p.preven, p.codcar, a.existencia,u.denunimed,cl.dencla,su.den_sub,p.ultcosart " .
				"FROM  sfc_producto p,sfc_clasificacion cl,sfc_subclasificacion su,sim_articuloalmacen a,sim_unidadmedida u, sim_articulo sa" .
				" WHERE p.codemp=a.codemp AND p.codemp=sa.codemp AND p.codart=a.codart AND p.codart=sa.codart AND p.codtiend=a.codtiend" .
				" AND cl.codcla=su.codcla AND cl.codcla=sa.codcla AND su.cod_sub=sa.cod_sub AND a.codemp=sa.codemp AND a.codart=sa.codart " .
				" AND sa.codunimed=u.codunimed AND p.codemp='".$ls_codemp."' AND a.codalm ilike '".$ls_codalm."' AND sa.denart ilike '%".$ls_denpro."%'" .
				" AND cl.codcla ilike '".$ls_codcla."%' AND su.cod_sub like '%".$ls_codsub."%'" .
				" AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie)." AND " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'a',$ls_codtie)." AND a.existencia!=0 ORDER BY sa.denart,cl.dencla ASC;";
		//print "6".$ls_sql;

		}


	}


	?>
		<script language="JavaScript">
		var ls_sql="<?php print $ls_sql; ?>";
		pagina="reportes/sigesp_sfc_rep_existeclasificacion_excel.php?sql="+encodeURIComponent(ls_sql);
		popupWin(pagina,"catalogo",580,700);
		</script>
	<?php


	}
?>



<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
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



	function uf_mostrar_reporte()
	{
		f=document.form1;
		 li_imprimir=f.imprimir.value;
if(li_imprimir==1)
{
		ls_codart=f.txtcodart.value;
		ls_codalm=f.txtcodalm.value;
		if(f.radioordenalm[0].checked)
		{
			li_ordenalm=0;
		}
		else
		{
			li_ordenalm=1;
		}

		if(f.radioordenart[0].checked)
		{
			li_ordenart=0;
		}
		else
		{
			li_ordenart=1;
		}
	}
 else
	{alert("No tiene permiso para realizar esta operaci�n");}
	   // f.submit();
	}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

function ue_buscar_clasificacion()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_clasificacion.php";
	popupWin(pagina,"catalogo",600,250);
}


function ue_cargarclasificacion(codcla,nomcla)
{
	f=document.form1;
	f.txtdencla.value=nomcla;
	f.txtcodcla.value=codcla;
}
function ue_cargarsubclasificacion(codsub,nomsub,codcla,nomcla1)
{
    f=document.form1;
	f.operacion.value="";
	f.txtdensub.value=nomsub;
	f.txtcodsubcla.value=codsub;
}
  function ue_catsubclasificacion()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_sub_clasificacion.php";
	popupWin(pagina,"catalogo",580,300);
}

function ue_cargarproducto(codpro,denpro,tippro,preven,codcar,dencar,codcla,dencla,codart,denart,ultcosart,spi_cuenta,denspi,sc_cuenta,denscg,codalm)
{
    f=document.form1;
	f.operacion.value="";

	f.txtdenpro.value=denpro;
}
  function ue_catproducto()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_producto.php";
	popupWin(pagina,"catalogo",580,300);
}

function ue_ver()
{
	f=document.form1;
	 li_imprimir=f.imprimir.value;
if(li_imprimir==1)
{
	f.operacion.value="VER";
	f.action="sigesp_sfc_d_rep_existeclasificacion.php";
	f.submit();
	f.txtdencla.value="";
	f.txtdenpro.value="";
	f.txtcodcla.value="";
	f.txtcodsubcla.value="";
	}
 else
	{alert("No tiene permiso para realizar esta operación");}
}


function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="";
	f.txtdencla.value="";
	f.txtdenpro.value="";


}
function actualizar_combo()
{
	f=document.form1;
	f.combo_ordenarpor.value="VER";
	f.action="sigesp_sfc_d_rep_existeclasificacion.php";
	f.submit();
}


function ue_openexcel()
{
	f=document.form1;
	 li_imprimir=f.imprimir.value;
if(li_imprimir==1)
{
	//alert("Generar Excel");
	f.operacion.value="EXCEL";
	f.action="sigesp_sfc_d_rep_existeclasificacion.php";
	f.submit();
	}
else
	{alert("No tiene permiso para realizar esta operación");}
		/*pagina="reportes/sigesp_sfc_rep_existeclasificacion_excel.php?sql="+encodeURIComponent(ls_sql);
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");*/
}




</script>
</html>
