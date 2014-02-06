<?Php
/************************************************************************************************************************/
/***********************************  Generar Archivo de Transferencia-Almacenes ****************************************/
/************************************************************************************************************************/

session_start();

if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_conexion.php'";
	 print "</script>";
   }
$arre=$_SESSION["la_empresa"];
$ls_codemp=$arre["codemp"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<title>Archivo de Transferencia Entre Almacenes</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
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
.Estilo2 {font-size: 9}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="506" height="20" class="cd-menu">&nbsp;</td>
    <td width="272" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>
	<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>	</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?Php
/************************************************************************************************************************/
/********************************   LIBRERIAS   *************************************************************************/
/************************************************************************************************************************/
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/sigesp_sfc_c_intarchivo.php");
require_once("class_folder/sigesp_sfc_c_transferencia_almacen.php");
$ls_rutabase = getcwd();
$io_archivoO= new sigesp_sfc_c_intarchivo($ls_rutabase."/transferencias/ALMACENORIGEN");
$io_archivoD= new sigesp_sfc_c_intarchivo($ls_rutabase."/transferencias/ALMACENDESTINO");
//$io_archivoO= new sigesp_sfc_c_intarchivo("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ALMACENORIGEN");
//$io_archivoD= new sigesp_sfc_c_intarchivo("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ALMACENDESTINO");
$io_funcdb=new class_funciones_db($io_connect);
$io_sfc= new sigesp_sfc_c_transferencia_almacen();
$io_datastore= new class_datastore();
$io_datastore1= new class_datastore();
$io_datastore2= new class_datastore();
$io_datastore3= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_grid=new grid_param();
$io_function=new class_funciones();
$is_msg=new class_mensajes();
$ls_total_facturado=0;

/**************   GRID   DETALLES   ORDENES DE COMPRA   *******************/

$ls_tituloalmacenes="Transferencia Entre Almacenes";
$li_anchoalmacenes=800;
$ls_nametable="grid";
$la_columalmacenes[1]="Numero Transferencia";
$la_columalmacenes[2]="Fecha de Emision";
$la_columalmacenes[3]="codigo Almacen Origen";
$la_columalmacenes[4]="Codigo Almacen Destino";
$la_columalmacenes[5]="Codigo Articulo";
print "<script language=JavaScript>suiche_submit=false;</script>";

/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$li_filasalmacenes=$_POST["filasalmacenes"];

    }
else
	{
		$ls_operacion="";

	}



if($ls_operacion=="PROCESAR")
{

						$ls_cadena="SELECT * FROM sim_transferencia WHERE sim_transferencia.codemp = '".$ls_codemp."'";
						$arr_almacenes=$io_sql->select($ls_cadena);

						if($arr_almacenes==false)
						{
							$is_msg->message("No hay Transferencia Entre Almacenes");
						}
						else
						{
							if($row=$io_sql->fetch_row($arr_almacenes))
							  {
								$la_almacenes=$io_sql->obtener_datos($arr_almacenes);
								$io_datastore1->data=$la_almacenes;
								$li_p=1;
								$totrow1=$io_datastore1->getRowCount("numtra");

								for($li_i=1;$li_i<=$totrow1;$li_i++)
								{

									$ls_codemp=$io_datastore1->getValue("codemp",$li_i);
									$ls_numtra=$io_datastore1->getValue("numtra",$li_i);
									$ls_fecemi=$io_datastore1->getValue("fecemi",$li_i);
									$ls_codalmori=$io_datastore1->getValue("codalmori",$li_i);
									$ls_codalmdes=$io_datastore1->getValue("codalmdes",$li_i);


									$ls_cadena2="SELECT * FROM sim_dt_transferencia WHERE numtra = '".$ls_numtra."' ";
									$arr_almacenes2=$io_sql->select($ls_cadena2);
									if($arr_almacenes2==true){
										if($row=$io_sql->fetch_row($arr_almacenes2))
										  {
											$la_almacenes2=$io_sql->obtener_datos($arr_almacenes2);
											$io_datastore->data=$la_almacenes2;
											$totrow=$io_datastore->getRowCount("numtra");

											for($li_z=1;$li_z<=$totrow;$li_z++)
											{
												$ls_codart=$io_datastore->getValue("codart",$li_z);
												$ls_cantidad=$io_datastore->getValue("cantidad",$li_z);

												$lb_valido=false;
												$lb_valido=$io_sfc->uf_sfc_select_dt_transferencia_almacen($ls_codemp,$ls_numtra,$ls_codart);
												if (!$lb_valido){
													$lb_valido_transf=false;
													$lb_valido_transf=$io_sfc->uf_sfc_insert_dt_transferencia_almacen($ls_codemp,$ls_numtra,$ls_fecemi,$ls_codart,$ls_cantidad);
													$lb_valido2=false;
													$lb_valido2=$io_sfc->uf_sfc_select_transferencia_almacen($ls_codemp,$ls_numtra,$ls_fecemi,$ls_codalmori,$ls_codalmdes);
													if ((!$lb_valido2) and (!$lb_valido)){
														$lb_valido_transf2=false;
														$lb_valido_transf2=$io_sfc->uf_sfc_insert_transferencia_almacen($ls_codemp,&$ls_numtra,$ls_fecemi,$ls_codalmori,$ls_codalmdes);
													}	//if lb_valido2
										$la_objectoalmacenes[$li_p][1]="<input name=txtcodret".$li_p." type=text id=txtcodret".$li_p." class=sin-borde value='".$ls_numtra."' style= text-align:center size=15 readonly>";

										$la_objectoalmacenes[$li_p][2]="<input name=txtdesret".$li_p." type=text id=txtdesret".$li_p." class=sin-borde value='".$ls_fecemi."' style= text-align:center size=8 readonly>";

										$la_objectoalmacenes[$li_p][3]="<input name=txtmontoret".$li_p." type=text id=txtmontoret".$li_p." class=sin-borde value='".$ls_codalmori."' style= text-align:center size=8 readonly>";

										$la_objectoalmacenes[$li_p][4]="<input name=txttotalret".$li_p." type=text id=txttotalret".$li_p." class=sin-borde value='".$ls_codalmdes."' style= text-align:center size=8 readonly>";

										$la_objectoalmacenes[$li_p][5]="<input name=txttotalret".$li_p." type=text id=txttotalret".$li_p." class=sin-borde value='".$ls_codart."' style= text-align:center size=25 readonly>";


$ls_sql_resta =  "UPDATE sim_articuloalmacen SET existencia= (existencia - '". $ls_cantidad ."') WHERE codemp='" . $ls_codemp ."'".
					"   AND codart='" . $ls_codart ."'".
					"   AND codalm='" . $ls_codalmori ."';";

				  /****************************************Archivo de Transferencia*************************************/

					$ls_nomarchivo="trans".$ls_codalmori;
					$io_archivoO->crear_archivo($ls_nomarchivo);
					$io_archivoO->escribir_archivo($ls_sql_resta);
					$io_archivoO->cerrar_archivo();


					/**************************************************************************************************/

$ls_sql_suma= "UPDATE sim_articuloalmacen SET existencia= (existencia + '". $ls_cantidad ."') WHERE codemp='" . $ls_codemp ."'".
				  "   AND codart='" . $ls_codart ."'".
				  "   AND codalm='" . $ls_codalmdes ."';";

				   /****************************************Archivo de Transferencia***********************************/


					$ls_nomarchivo2="trans".$ls_codalmdes;
					$io_archivoD->crear_archivo($ls_nomarchivo2);
					$io_archivoD->escribir_archivo($ls_sql_suma);
					$io_archivoD->cerrar_archivo();


					/**************************************************************************************************/
			$li_p++;
			$li_filasalmacenes++;
													} // if
												} // for li_z

						$li_filasalmacenes=$li_p;
						$la_objectoalmacenes[$li_filasalmacenes][1]="<input name=txtcodret".$li_filasalmacenes." type=text id=txtcodret".$li_filasalmacenes." class=sin-borde style= text-align:center size=15 readonly>";
				$la_objectoalmacenes[$li_filasalmacenes][2]="<input name=txtdesret".$li_filasalmacenes." type=text id=txtdesret".$li_filasalmacenes." class=sin-borde style= text-align:center size=8 readonly>";
				$la_objectoalmacenes[$li_filasalmacenes][3]="<input name=txtmontoret".$li_filasalmacenes." type=text id=txtmontoret".$li_filasalmacenes." class=sin-borde style= text-align:center size=8 >";
				$la_objectoalmacenes[$li_filasalmacenes][4]="<input name=txttotalret".$li_filasalmacenes." type=text id=txttotalret".$li_filasalmacenes." class=sin-borde style= text-align:center size=8 readonly>";
				$la_objectoalmacenes[$li_filasalmacenes][5]="<input name=txttotalret".$li_filasalmacenes." type=text id=txttotalret".$li_filasalmacenes." class=sin-borde style= text-align:center size=25 readonly>";

											} // if almacen2
										}	// if

								} // for li_i

						 }	// if

					} // else

} // if operacion procesar
else{
	if($ls_operacion=="PROCESAR2")
{

						$ls_sql_sfc="SELECT * FROM sfc_transferenciaalmacen WHERE sfc_transferenciaalmacen.codemp = '".$ls_codemp."'";
						$arr_alm_sfc=$io_sql->select($ls_sql_sfc);

						if($arr_alm_sfc==false)
						{
							$is_msg->message("No hay Transferencia Entre Almacenes");
						}
						else
						{
							if($row=$io_sql->fetch_row($arr_alm_sfc))
							  {
								$la_alm_sfc=$io_sql->obtener_datos($arr_alm_sfc);
								$io_datastore2->data=$la_alm_sfc;
								$li_x=1;
								$totrow2=$io_datastore2->getRowCount("numtra");

								for($li_j=1;$li_j<=$totrow2;$li_j++)
								{

									$ls_codemp=$io_datastore2->getValue("codemp",$li_j);
									$ls_numtra=$io_datastore2->getValue("numtra",$li_j);
									$ls_fecemi=$io_datastore2->getValue("fecemi",$li_j);
									$ls_codalmori=$io_datastore2->getValue("codalmori",$li_j);
									$ls_codalmdes=$io_datastore2->getValue("codalmdes",$li_j);


									$ls_sql_sfc2="SELECT * FROM sfc_dt_transferenciaalmacen WHERE numtra = '".$ls_numtra."' ";
									$arr_alm_sfc2=$io_sql->select($ls_sql_sfc2);
									if($arr_alm_sfc2==true){
										if($row=$io_sql->fetch_row($arr_alm_sfc2))
										  {
											$la_alm_sfc2=$io_sql->obtener_datos($arr_alm_sfc2);
											$io_datastore3->data=$la_alm_sfc2;
											$totrow3=$io_datastore3->getRowCount("numtra");

											for($li_n=1;$li_n<=$totrow3;$li_n++)
											{
												$ls_codart=$io_datastore3->getValue("codart",$li_n);
												$ls_cantidad=$io_datastore3->getValue("cantidad",$li_n);

												$lb_valido9=false;
												$lb_valido9=$io_sfc->uf_sim_select_dt_transferencia_almacen($ls_codemp,$ls_numtra,$ls_codart);
												$lb_valido8=false;
												$lb_valido8=$io_sfc->uf_sim_select_transferencia_almacen($ls_codemp,$ls_numtra,$ls_fecemi,$ls_codalmori,$ls_codalmdes);

												if ((!$lb_valido9) and (!$lb_valido8)){

												$ls_sql_sfc3="DELETE FROM sfc_transferenciaalmacen WHERE numtra = '".$ls_numtra."' AND codalmori = '".$ls_codalmori."' AND codalmdes = '".$ls_codalmdes."' ";
												$arr_alm_sfc3=$io_sql->execute($ls_sql_sfc3);

												$ls_sql_sfc4="DELETE FROM sfc_dt_transferenciaalmacen WHERE numtra = '".$ls_numtra."' AND codart='".$ls_codart."' AND fecemi = '".$ls_fecemi."' ";
												$arr_alm_sfc4=$io_sql->execute($ls_sql_sfc4);

										$la_objectoalmacenes[$li_x][1]="<input name=txtcodret".$li_x." type=text id=txtcodret".$li_x." class=sin-borde value='".$ls_numtra."' style= text-align:center size=15 readonly>";

										$la_objectoalmacenes[$li_x][2]="<input name=txtdesret".$li_x." type=text id=txtdesret".$li_x." class=sin-borde value='".$ls_fecemi."' style= text-align:center size=8 readonly>";

										$la_objectoalmacenes[$li_x][3]="<input name=txtmontoret".$li_x." type=text id=txtmontoret".$li_x." class=sin-borde value='".$ls_codalmori."' style= text-align:center size=8 readonly>";

										$la_objectoalmacenes[$li_x][4]="<input name=txttotalret".$li_x." type=text id=txttotalret".$li_x." class=sin-borde value='".$ls_codalmdes."' style= text-align:center size=8 readonly>";

										$la_objectoalmacenes[$li_x][5]="<input name=txttotalret".$li_x." type=text id=txttotalret".$li_x." class=sin-borde value='".$ls_codart."' style= text-align:center size=25 readonly>";

$ls_sql_resta2 =  "UPDATE sim_articuloalmacen SET existencia= (existencia - '". $ls_cantidad ."') WHERE codemp='" . $ls_codemp ."'".
					"   AND codart='" . $ls_codart ."'".
					"   AND codalm='" . $ls_codalmdes ."';";

				  /****************************************Archivo de Transferencia*************************************/

					$ls_nomarchivo3="trans".$ls_codalmdes;
					$io_archivoD->crear_archivo($ls_nomarchivo3);
					$io_archivoD->escribir_archivo($ls_sql_resta2);
					$io_archivoD->cerrar_archivo();


					/**************************************************************************************************/

$ls_sql_suma2 = "UPDATE sim_articuloalmacen SET existencia= (existencia + '". $ls_cantidad ."') WHERE codemp='" . $ls_codemp ."'".
				  "   AND codart='" . $ls_codart ."'".
				  "   AND codalm='" . $ls_codalmori ."';";

				   /****************************************Archivo de Transferencia***********************************/


					$ls_nomarchivo4="trans".$ls_codalmori;
					$io_archivoO->crear_archivo($ls_nomarchivo4);
					$io_archivoO->escribir_archivo($ls_sql_suma2);
					$io_archivoO->cerrar_archivo();


					/**************************************************************************************************/
			$li_x++;
			$li_filasalmacenes++;
												} // if doble
											} // for li_n

						$li_filasalmacenes=$li_x;
						$la_objectoalmacenes[$li_filasalmacenes][1]="<input name=txtcodret".$li_filasalmacenes." type=text id=txtcodret".$li_filasalmacenes." class=sin-borde style= text-align:center size=15 readonly>";
				$la_objectoalmacenes[$li_filasalmacenes][2]="<input name=txtdesret".$li_filasalmacenes." type=text id=txtdesret".$li_filasalmacenes." class=sin-borde style= text-align:center size=8 readonly>";
				$la_objectoalmacenes[$li_filasalmacenes][3]="<input name=txtmontoret".$li_filasalmacenes." type=text id=txtmontoret".$li_filasalmacenes." class=sin-borde style= text-align:center size=8 >";
				$la_objectoalmacenes[$li_filasalmacenes][4]="<input name=txttotalret".$li_filasalmacenes." type=text id=txttotalret".$li_filasalmacenes." class=sin-borde style= text-align:center size=8 readonly>";
				$la_objectoalmacenes[$li_filasalmacenes][5]="<input name=txttotalret".$li_filasalmacenes." type=text id=txttotalret".$li_filasalmacenes." class=sin-borde style= text-align:center size=25 readonly>";

											} // if $row=$io_sql->fetch_row($arr_alm_sfc2)
										}	// if $arr_alm_sfc2==true

								} // for li_j

						 }	// if $row=$io_sql->fetch_row($arr_alm_sfc)

					} // else

} // if operacion procesar2

} // else

?>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
/*/////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
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
//////////////////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////*/
?>

    <table width="541" height="155" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="539" height="153"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="3" class="titulo-ventana">Generar Archivo de Transferencia Entre Almacenes </td>
            </tr>
            <tr>
              <td >
			  <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">			  </td>
              <td colspan="2" >&nbsp;</td>
            </tr>

			<tr>
              <td width="42" height="22" align="right">&nbsp;</td>
              <td width="185" ><!-- javascript:ue_catusuario(); -->
                <a href="javascript:ue_procesar();"><img src="Imagenes/ejecutar.gif" width="20" height="20" border="0">Procesar Transferencias</a></td>
			  <td width="263" ><a href="javascript:ue_procesar2();"><img src="Imagenes/ejecutar.gif" width="20" height="20" border="0">Procesar Reversos</a></td>
			</tr>
            <tr>
              <td width="42" height="22" align="right">&nbsp;</td>
              <td colspan="2" >&nbsp;</td>
            </tr>

            <tr>
              <td height="52" colspan="3"><table width="430" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">


				<?PHP
/************************************************************************************************************************/
/***************************   PROCESAR ********************************************************************************/
/************************************************************************************************************************/
?>
<tr align="center" class="formato-blanco">
              <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasalmacenes,$la_columalmacenes,$la_objectoalmacenes,$li_anchoalmacenes,$ls_tituloalmacenes,$ls_nametable);?></td>
            </tr>


               </table>
              <p></p></td>
            </tr>
          </table>
          <p></p>
        </div></td>
      </tr>
  </table>

<?PHP

/************************************************************************************************************************/
/***************************************   FIN DEL FORMULARIO  **********************************************************/
/************************************************************************************************************************/
?>
</form>
</body>

<script language="JavaScript">


/*******************************************************************************************************************************/
function ue_procesar()
{

  f=document.form1;
  f.operacion.value="PROCESAR";
  f.action="sigesp_sfc_d_generar_transf_almacen.php";
  f.submit();

  }

function ue_procesar2()
{

  f=document.form1;
  f.operacion.value="PROCESAR2";
  f.action="sigesp_sfc_d_generar_transf_almacen.php";
  f.submit();

  }

</script>
</html>