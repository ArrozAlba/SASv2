<?Php
/************************************************************************************************************************/
/***********************************  			Actualizar Serie de Facturas			 ********************************/
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
$ls_codcaj=$_SESSION["ls_codcaj"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<title>Actualizar Series de Facturas</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript" src="js/js_intra/datepickercontrol.js"></script>
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

<Script Language="JavaScript">
function loadImages() {
if (document.getElementById) { // DOM3 = IE5, NS6
document.getElementById('hidepage').style.visibility = 'hidden';
}
else {
if (document.layers) { // Netscape 4
document.hidepage.visibility = 'hidden';
}
else { // IE 4
document.all.hidepage.style.visibility = 'hidden';
}
}
}
</script>
<style type="text/css">
<!--
.Estilo2 {font-size: 9}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699" OnLoad="loadImages()">

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
require_once("class_folder/sigesp_sfc_c_factura.php");
require_once("class_folder/sigesp_sfc_c_secuencia.php");
require_once("class_folder/sigesp_sfc_c_actualizar_series.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");

//$io_funcdb=new class_funciones_db($io_connect);
$io_actualizar=new sigesp_sfc_c_actualizar_series();
$io_secuencia=new sigesp_sfc_c_secuencia();

$io_datastore= new class_datastore();
$io_datastore1= new class_datastore();
$io_datastoreN= new class_datastore();
$io_datastoreR= new class_datastore();
$io_datastoreI= new class_datastore();
$io_datastoreD= new class_datastore();
$io_datastoreF= new class_datastore();
$io_datastoreC= new class_datastore();

$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_sql2=new class_sql($io_connect);
$io_data=new class_datastore();
$io_grid=new grid_param();
$io_function=new class_funciones();
$is_msg=new class_mensajes();
$ls_total_facturado=0;
$io_factura=new  sigesp_sfc_c_factura();
$io_funcsob=new sigesp_sob_c_funciones_sob();

require_once("class_folder/sigesp_sim_c_articuloxalmacen.php");
$io_art=  new sigesp_sim_c_articuloxalmacen();
require_once("class_folder/sigesp_sim_c_despacho.php");
$io_despacho=  new sigesp_sim_c_despacho();
require_once("class_folder/sigesp_sim_c_movimientoinventario.php");
$io_movimiento=    new sigesp_sim_c_movimientoinventario();
require_once("class_folder/sigesp_sim_c_recepcion.php");
$io_recepcion=  new sigesp_sim_c_recepcion();
require_once("class_folder/sigesp_sfc_c_procesarmovimientos.php");
$io_procmovi=  new sigesp_sfc_c_procesarmovimientos();
require_once("class_folder/sigesp_sfc_c_devolucion.php");
$io_devolucion=new  sigesp_sfc_c_devolucion();

/**************   GRID   DETALLES  FACTURAS   *******************/

$ls_tituloordenes="Facturas Por Procesar";
$li_anchoordenes=500;
$ls_nametable="grid";
$la_columordenes[1]="Fecha de Emision";
$la_columordenes[2]="Nro. Factura";
$la_columordenes[3]="Nro. Control";
$la_columordenes[4]="Cliente";
$la_columordenes[5]="Monto";
//$la_columordenes[5]="Cliente";
print "<script language=JavaScript>suiche_submit=false;</script>";


//$ls_codtie=$_SESSION["ls_codtienda"];

/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$lb_fdesde=$_POST["txtfdesde"];
		$lb_fhasta=$_POST["txtfhasta"];
		$li_filasordenes=$_POST["filasordenes"];
		$li_filasmod=$_POST["filasmod"];

		$lb_serie = $_POST["txtserie"];
		$lb_correlativo = $_POST["txtcorrelativo"];
		$lb_control = $_POST["txtcontrol"];

		$lb_codtienda = $_POST["txtcodtienda"];
		$lb_destienda = $_POST["txtdestienda"];

		$lb_codcaja = $_POST["txtcodcaja"];
		$lb_descaja = $_POST["txtdescaja"];

		//print $li_filasmod;
    }
	else
	{
		$ls_operacion="";
		$lb_fdesde="";
		$lb_fhasta="";
		$li_filasordenes="";
		$li_filasmod=0;
		$lb_serie="";
		$lb_correlativo="";
		$lb_control="";
		$lb_codtienda = "";
		$lb_destienda = "";
		$lb_codcaja = "";
		$lb_descaja = "";

	}

?>

<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="467" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturacion</span></td>
    <td width="311" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"-->
		<a href="javascript:ue_guardar();" ><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a>
		<a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a>	</td>
  </tr>
</table>

<?

///////////////////////////////////////////////////////////Cargar la Busqueda/////////////////////////////////////
if($ls_operacion=="BUSCAR"){

	/*$li_fecha=$li_fecemi;
	$li_fecemi=$io_function->uf_convertirdatetobd($li_fecemi);
	if ($li_fecemi=="")
	{
	$li_fecemi="%/".$li_fecemi."%";
	}*/

	$li_p =0;
	$li_filasmod=0;

	$lb_valido = $io_actualizar->uf_buscar_facturas($ls_codemp, $lb_fdesde, $lb_fhasta, $lb_codtienda, $lb_codcaja, $li_p, $li_filasmod, $la_objectordenes);

	$li_filasordenes=$li_p-1;

	if($li_filasmod > 0){
		$is_msg->message("Verifique el Correlativo de la Factura con su Fecha de Emisión y Modifique la Fecha si es Necesario!!");
	}
}

///////////////////////////////////////////// PROCESAR_1 ///////////////////////////////////////////////////////
if( $ls_operacion=="PROCESAR_1"){

	$lb_serie=strtoupper($lb_serie);

	$temp = $io_actualizar->uf_crear_tmp();

	if($temp){

			$is_msg->message ("TMP Creadas!!");

			$lb_serie=strtoupper($lb_serie);
			$lb_ceros = 20;
			if(strlen($lb_serie) == 1){
				$lb_ceros = 20;
			}else{
				if(strlen($lb_serie) == 2){
					$lb_ceros = 19;
				}
			}
			//print "CEROS: ".$lb_ceros."<br>";

			$io_sql->begin_transaction();
			for($li_j=1; $li_j <= $li_filasordenes; $li_j++){

				$li_numfac = $_POST["txtnumfac".$li_j];
				$li_correlativo = $io_function->uf_cerosizquierda($lb_correlativo,$lb_ceros);
				$nvo_numfac = "FAC-".$lb_serie.$li_correlativo;

				$li_numcon = $_POST["txtnumcon".$li_j];
				$li_control = $io_function->uf_cerosizquierda($lb_control,$lb_ceros+3);
				$nvo_control = $lb_serie."-".$li_control;

				$ls_sqlfac = "SELECT numfac FROM tmp_factura WHERE numfac='".$nvo_numfac."' or numcon='".$nvo_control."' ";
				$data_fac = $io_sql->select($ls_sqlfac);

				if($row=$io_sql->fetch_row($data_fac)){

					$lb_valido_ins=false;
					$lb_valido=false;

					$li_j = $li_filasordenes;

					$is_msg->message ("Error, el Correlativo $nvo_numfac &oacute El Control $nvo_control ya existe. Ingrese otro correlativo!!");

				}else{

					if ($li_j == 1){
						$nvo_desde = $nvo_numfac;
						$nvo_condesde = $nvo_control;
					}

					//Buscamos la factura para insertar una igual pero con numfac diferente
					$ls_sqlfac = "SELECT * FROM sfc_factura " .
							"WHERE numfac='".$li_numfac."' AND numcon='".$li_numcon."' AND codemp='".$ls_codemp."' AND codtiend='".$lb_codtienda."' AND cod_caja='".$lb_codcaja."' ";

					$data_fac = $io_sql->select($ls_sqlfac);
					if($row=$io_sql->fetch_row($data_fac)){
						$la_fac=$io_sql->obtener_datos($data_fac);
						$io_datastoreF->data=$la_fac;

						$as_codemp=$io_datastoreF->getValue("codemp",1);
						$as_codcli=$io_datastoreF->getValue("codcli",1);
						$as_codusu=$io_datastoreF->getValue("codusu",1);
						$as_numcot=$io_datastoreF->getValue("numcot",1);
						$as_codciecaj=$io_datastoreF->getValue("codciecaj",1);
						$as_fecemi=$io_datastoreF->getValue("fecemi",1);
						$as_conpag=$io_datastoreF->getValue("conpag",1);
						$as_estfac=$io_datastoreF->getValue("estfac",1);
						$as_estfaccon=$io_datastoreF->getValue("estfaccon",1);
						$as_esppag=$io_datastoreF->getValue("esppag",1);
						$as_monto=$io_datastoreF->getValue("monto",1);
						$as_montoret=$io_datastoreF->getValue("montoret",1);
						$as_montopar=$io_datastoreF->getValue("montopar",1);
						$as_codtiend=$io_datastoreF->getValue("codtiend",1);
						$as_cod_caja=$io_datastoreF->getValue("cod_caja",1);
					}

					if($as_codciecaj !=""){
						$ls_sqlfac = "INSERT INTO tmp_factura(codemp,numfac,codcli,codusu,numcot,codciecaj,fecemi,conpag,estfac,estfaccon,esppag,monto,montoret,montopar,codtiend,cod_caja,numcon) " .
								"values('".$as_codemp."','".$nvo_numfac."','".$as_codcli."','".$as_codusu."','".$as_numcot."','".$as_codciecaj."','".$as_fecemi."','".$as_conpag."','".$as_estfac."','".$as_estfaccon."','".$as_esppag."','".$as_monto."','".$as_montoret."','".$as_montopar."','".$as_codtiend."','".$as_cod_caja."','".$nvo_control."')";
					}else{
						$ls_sqlfac = "INSERT INTO tmp_factura(codemp,numfac,codcli,codusu,numcot,fecemi,conpag,estfac,estfaccon,esppag,monto,montoret,montopar,codtiend,cod_caja,numcon) " .
								"values('".$as_codemp."','".$nvo_numfac."','".$as_codcli."','".$as_codusu."','".$as_numcot."','".$as_fecemi."','".$as_conpag."','".$as_estfac."','".$as_estfaccon."','".$as_esppag."','".$as_monto."','".$as_montoret."','".$as_montopar."','".$as_codtiend."','".$as_cod_caja."','".$nvo_control."')";
					}

					//print $ls_sqlfac."<br>";

					$lb_valido_ins=true;
					$lb_valido=true;

					$lb_valido_ins = $io_sql->execute($ls_sqlfac);

					if($lb_valido_ins){

						$ls_sql = "INSERT INTO tmp_detfactura " .
								"SELECT * FROM sfc_detfactura WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."' ";
						$lb_valido = $io_sql->execute($ls_sql);
						if($lb_valido){

							$ls_sql = "UPDATE tmp_detfactura SET numfac = '".$nvo_numfac."' " .
									"WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."' ";
							$lb_valido = $io_sql->execute($ls_sql);
							if(!$lb_valido){
								$li_j = $li_filasordenes;
								$io_sql->rollback();
								$is_msg->message ("Error Actualizando el Detalles de Facturas!!");
							}else{

								if($nvo_numfac < $li_numfac){
									//$ls_sql = "DELETE FROM sfc_detfactura WHERE numfac ='".$nvo_numfac."' AND codtiend='".$lb_codtienda."' ";
									//$lb_valido = $io_sql->execute($ls_sql);
								}
								$ls_sql = "DELETE FROM sfc_detfactura WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."' ";
								$lb_valido = $io_sql->execute($ls_sql);
								$ls_sql = "INSERT INTO aux_detfactura SELECT * FROM tmp_detfactura";
								$lb_valido = $io_sql->execute($ls_sql);
								$ls_sql = "DELETE FROM tmp_detfactura";
								$lb_valido = $io_sql->execute($ls_sql);
							}
						}

					}else{
						$li_j = $li_filasordenes;
						$io_sql->rollback();
						$is_msg->message ("Error Insertando la Nueva Factura!!");
					}

					if($lb_valido_ins){
						//Actualizar Facturas en INSTPAGO
						$ls_sql = "SELECT numfac FROM sfc_instpago WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."' ";

						$data_instpago = $io_sql->select($ls_sql);
						if($row=$io_sql->fetch_row($data_instpago)){
								$la_instpago=$io_sql->obtener_datos($data_instpago);
								$io_datastoreI->data=$la_instpago;
								$totrowI=$io_datastoreI->getRowCount("numfac");
								//print $totrowI."<br>";
								if($totrowI >0){

									$ls_sql = "INSERT INTO tmp_instpago SELECT * FROM sfc_instpago WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."' ";
									$lb_valido = $io_sql->execute($ls_sql);
									if($lb_valido){

										$ls_sql = "UPDATE tmp_instpago SET numfac = '".$nvo_numfac."' WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."' ";
										$lb_valido = $io_sql->execute($ls_sql);
										if(!$lb_valido){
											$li_j = $li_filasordenes;
											$io_sql->rollback();
											$is_msg->message ("Error actualizando las Formas de Pago asociadas a las Facturas!!");
										}else{

											if($nvo_numfac < $li_numfac){
											$ls_sql = "DELETE FROM sfc_instpago WHERE numfac ='".$nvo_numfac."' AND codtiend='".$lb_codtienda."' ";
												$lb_valido = $io_sql->execute($ls_sql);
											}
											$ls_sql = "DELETE FROM sfc_instpago WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."' ";
											$lb_valido = $io_sql->execute($ls_sql);
											$ls_sql = "INSERT INTO aux_instpago SELECT * FROM tmp_instpago";
											$lb_valido = $io_sql->execute($ls_sql);
											$ls_sql = "DELETE FROM tmp_instpago";
											$lb_valido = $io_sql->execute($ls_sql);
										}
									}
								}
							}

						 //Actualizar Facturas en Retencion
						$ls_sql = "SELECT numfac FROM sfc_facturaretencion WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."' ";

						$data_ret = $io_sql->select($ls_sql);

						if($row=$io_sql->fetch_row($data_ret)){
							$la_ret=$io_sql->obtener_datos($data_ret);
							$io_datastoreR->data=$la_ret;
							$totrowR=$io_datastoreR->getRowCount("numfac");
							if($totrowR >0){

								$ls_sql = "INSERT INTO tmp_facturaretencion " .
										" SELECT * FROM sfc_facturaretencion WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."' ";
								$lb_valido = $io_sql->execute($ls_sql);
								if($lb_valido){

									$ls_sql = "UPDATE tmp_facturaretencion SET numfac = '".$nvo_numfac."' WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."' ";
									$lb_valido = $io_sql->execute($ls_sql);
									if(!$lb_valido){
										$li_j = $li_filasordenes;
										$io_sql->rollback();
										$is_msg->message ("Error actualizando las Retenciones asociadas a las Facturas!!");
									}else{

										if($nvo_numfac < $li_numfac){
										$ls_sql = "DELETE FROM sfc_facturaretencion WHERE numfac ='".$nvo_numfac."' AND codtiend='".$lb_codtienda."' ";
										$lb_valido = $io_sql->execute($ls_sql);
										}
										$ls_sql = "DELETE FROM sfc_facturaretencion WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."' ";
										$lb_valido = $io_sql->execute($ls_sql);
										$ls_sql = "INSERT INTO aux_facturaretencion SELECT * FROM tmp_facturaretencion";
										$lb_valido = $io_sql->execute($ls_sql);
										$ls_sql = "DELETE FROM tmp_facturaretencion";
										$lb_valido = $io_sql->execute($ls_sql);
									}
								}
							}
						}

						//Actualizar Facturas en Nota
						$ls_sql = "SELECT nro_documento FROM sfc_nota WHERE nro_documento = '".$li_numfac."' AND codtiend='".$lb_codtienda."' ";

						$data_nota = $io_sql->select($ls_sql);

						if($row=$io_sql->fetch_row($data_nota)){

							$la_nota=$io_sql->obtener_datos($data_nota);
							$io_datastoreN->data=$la_nota;
							$totrowN=$io_datastoreN->getRowCount("nro_documento");
							if($totrowN >0){

								$ls_sql = "INSERT INTO tmp_nota SELECT * FROM sfc_nota WHERE nro_documento ='".$li_numfac."' AND codtiend='".$lb_codtienda."' ";

								$lb_valido = $io_sql->execute($ls_sql);
								if($lb_valido){

									$ls_sql = "UPDATE tmp_nota SET nro_documento ='".$nvo_numfac."' WHERE nro_documento = '".$li_numfac."' AND codtiend='".$lb_codtienda."' ";

									$lb_valido = $io_sql->execute($ls_sql);
									if(!$lb_valido){
										$li_j = $li_filasordenes;
										$io_sql->rollback();
										$is_msg->message ("Error actualizando las Notas asociadas a las Facturas!!");
									}else{

										$ls_sql = "SELECT numnot FROM tmp_nota WHERE nro_documento = '".$nvo_numfac."' AND tipnot='CXC' AND codtiend='".$lb_codtienda."' ";
										$nota = $io_sql->select($ls_sql);
										if($row=$io_sql->fetch_row($nota)){
											//$ls_sql = "INSERT INTO tmp_nota SELECT * FROM sfc_nota WHERE nro_factura ='".$li_numfac."' AND tipnot='CXC'";
											//$lb_valido = $io_sql->execute($ls_sql);
											//if($lb_valido){

												$ls_sql = "UPDATE tmp_nota SET numnot='".$nvo_numfac."' WHERE nro_documento = '".$nvo_numfac."' AND tipnot='CXC' AND codtiend='".$lb_codtienda."' ";
												$lb_valido = $io_sql->execute($ls_sql);
												if(!$lb_valido){
													$li_j = $li_filasordenes;
													$io_sql->rollback();
													$is_msg->message ("Error actualizando las Notas asociadas a las Facturas!!");
												}else{
													if($nvo_numfac < $li_numfac){
														$ls_sql = "DELETE FROM sfc_nota WHERE nro_documento ='".$nvo_numfac."' AND tipnot='CXC' AND codtiend='".$lb_codtienda."' ";
														$lb_valido = $io_sql->execute($ls_sql);
													}

													$ls_sql = "DELETE FROM sfc_nota WHERE nro_documento ='".$li_numfac."' AND tipnot='CXC' AND codtiend='".$lb_codtienda."' ";
													$lb_valido = $io_sql->execute($ls_sql);
													$ls_sql = "INSERT INTO aux_nota SELECT * FROM tmp_nota";
													$lb_valido = $io_sql->execute($ls_sql);
													$ls_sql = "DELETE FROM tmp_nota";
													$lb_valido = $io_sql->execute($ls_sql);
												}

												//$io_sql->commit();
												//exit('al insertar en tmp_detfactura');
											//}

											if($nvo_numfac < $li_numfac){
											$ls_sql = "DELETE FROM sfc_nota WHERE nro_documento ='".$nvo_numfac."' AND codtiend='".$lb_codtienda."' ";
											$lb_valido = $io_sql->execute($ls_sql);
											}
											$ls_sql = "DELETE FROM sfc_nota WHERE nro_documento ='".$li_numfac."' AND codtiend='".$lb_codtienda."' ";
											$lb_valido = $io_sql->execute($ls_sql);
											$ls_sql = "INSERT INTO aux_nota SELECT * FROM tmp_nota";
											$lb_valido = $io_sql->execute($ls_sql);
											$ls_sql = "DELETE FROM tmp_nota";
											$lb_valido = $io_sql->execute($ls_sql);
										}

									}
								}
							}// $totrowN
						}

						//Actualizar Facturas en Devolucion
						$ls_sql = "SELECT numfac FROM sfc_devolucion " .
								"WHERE numfac = '".$li_numfac."' AND codtiend='".$lb_codtienda."' ";
						$data_desp = $io_sql->select($ls_sql);
						$lb_valido = true;

						if($row=$io_sql->fetch_row($data_desp)){
							$la_desp=$io_sql->obtener_datos($data_desp);
							$io_datastoreD->data=$la_desp;
							$totrowD=$io_datastoreD->getRowCount("numfac");
							if($totrowD >0){

								$ls_sql = "INSERT INTO tmp_devolucion " .
										"SELECT * FROM sfc_devolucion WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."'";
								$lb_valido = $io_sql->execute($ls_sql);
								if($lb_valido){

									$ls_sql = "UPDATE tmp_devolucion SET numfac = '".$nvo_numfac."' " .
											"WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."'";
									$lb_valido = $io_sql->execute($ls_sql);
									if(!$lb_valido){
										$li_j = $li_filasordenes;
										$io_sql->rollback();
										$is_msg->message ("Error actualizando las Devolucion Asociadas a las Facturas!!");
									}else{

										$ls_sql = "INSERT INTO aux_devolucion SELECT * FROM tmp_devolucion";
										$lb_valido = $io_sql->execute($ls_sql);
										$ls_sql = "DELETE FROM tmp_devolucion";
										$lb_valido = $io_sql->execute($ls_sql);

										$ls_sql= "INSERT INTO aux_detdevolucion SELECT * FROM sfc_detdevolucion WHERE coddev IN (SELECT coddev FROM sfc_devolucion WHERE numfac='".$li_numfac."')";
										//print $ls_sql."<br>";
										$lb_valido = $io_sql->execute($ls_sql);
										if($lb_valido){
											if($nvo_numfac < $li_numfac){
											$ls_sql = "DELETE FROM sfc_detdevolucion WHERE coddev IN (SELECT coddev FROM sfc_devolucion WHERE numfac='".$nvo_numfac."' AND codtiend='".$lb_codtienda."')";
											$lb_valido = $io_sql->execute($ls_sql);
											}
											$ls_sql = "DELETE FROM sfc_detdevolucion WHERE coddev IN (SELECT coddev FROM sfc_devolucion WHERE numfac='".$li_numfac."' AND codtiend='".$lb_codtienda."')";
											$lb_valido = $io_sql->execute($ls_sql);
											if($lb_valido){
												//Borra de sfc_devolucion
												if($nvo_numfac < $li_numfac){
												$ls_sql = "DELETE FROM sfc_devolucion WHERE numfac ='".$nvo_numfac."' AND codtiend='".$lb_codtienda."'";
												$lb_valido = $io_sql->execute($ls_sql);
												}
												$ls_sql = "DELETE FROM sfc_devolucion WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."'";
												$lb_valido = $io_sql->execute($ls_sql);
											}else{
												$io_sql->rollback();
												$li_j = $li_filasordenes;
												$is_msg->message ("Error actualizando las Devolucion Asociadas a las Facturas!!");
											}
										}

									}

								}

							}// $totrowD
						}

						//Actualizar sfc_dt_cobrocliente
						$ls_sql = "SELECT numfac FROM sfc_dt_cobrocliente " .
								"WHERE numfac = '".$li_numfac."' AND codtiend='".$lb_codtienda."' ";
						$data_dtcobro = $io_sql->select($ls_sql);
						$lb_valido = true;

						if($row=$io_sql->fetch_row($data_dtcobro)){
							$la_dtcobro=$io_sql->obtener_datos($data_dtcobro);
							$io_datastoreC->data=$la_dtcobro;
							$totrowC=$io_datastoreC->getRowCount("numfac");
							if($totrowC >0){
								$ls_sql = "INSERT INTO tmp_dt_cobrocliente " .
										"SELECT * FROM sfc_dt_cobrocliente WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."'";
								$lb_valido = $io_sql->execute($ls_sql);
								if($lb_valido){
									$ls_sql = "UPDATE tmp_dt_cobrocliente SET numfac = '".$nvo_numfac."' " .
											"WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."'";
									$lb_valido = $io_sql->execute($ls_sql);

									if(!$lb_valido){
										$li_j = $li_filasordenes;
										$io_sql->rollback();
										$is_msg->message ("Error actualizando las Detalles de Cobro Asociadas a las Facturas!!");
									}else{
										$ls_sql = "INSERT INTO aux_dt_cobrocliente SELECT * FROM tmp_dt_cobrocliente";
										$lb_valido = $io_sql->execute($ls_sql);
										$ls_sql = "DELETE FROM tmp_dt_cobrocliente";
										$lb_valido = $io_sql->execute($ls_sql);

										if($lb_valido){
											$ls_sql = "DELETE FROM sfc_dt_cobrocliente WHERE numfac ='".$li_numfac."' AND codtiend='".$lb_codtienda."' ";
											$lb_valido = $io_sql->execute($ls_sql);

											if(!$lb_valido){
												$li_j = $li_filasordenes;
												$io_sql->rollback();
												$is_msg->message ("Error actualizando las Detalles de Cobro Asociadas a las Facturas!!");
											}

										}else{
											$li_j = $li_filasordenes;
											$io_sql->rollback();
											$is_msg->message ("Error actualizando las Detalles de Cobro Asociadas a las Facturas!!");
										}
									}

								}

							}// $totrowD
						}

					}// FIn IF $ls_sql_ins


					if($lb_valido_ins){
						if($nvo_numfac < $li_numfac){
							$ls_sqldel = "DELETE FROM sfc_factura WHERE numfac='".$nvo_numfac."' AND codtiend='".$lb_codtienda."' AND cod_caja='".$lb_codcaja."' ";
							//print $ls_sqldel;
							$lb_valido = $io_sql->execute($ls_sqldel);
						}

						$ls_sqldel = "DELETE FROM sfc_factura WHERE numfac='".$li_numfac."' AND codtiend='".$lb_codtienda."' AND cod_caja='".$lb_codcaja."' ";
						//print $ls_sqldel;
						$lb_valido = $io_sql->execute($ls_sqldel);
					}

					$lb_correlativo++;
					$lb_control++;

				}

			}// For $li_filasordenes

			if($lb_valido_ins and $lb_valido){

				$corr = $lb_correlativo-1;
				$cont = $lb_control-1;

				//$lb_valido = $io_secuencia->uf_actualizar_secuencia($ls_codcaj."fac",$corr);
				$lb_valido = $io_secuencia->uf_actualizar_secuencia($lb_codcaja."fac",$corr);
				$lb_valido1 = $io_secuencia->uf_actualizar_secuencia($lb_codcaja."con",$cont);

				if($lb_valido and $lb_valido1){
					$io_sql->commit();

					$io_sql->begin_transaction();

					$ls_sql = "INSERT INTO sfc_factura SELECT * FROM tmp_factura";
					$lb_valido = $io_sql->execute($ls_sql);
					$ls_sql = "INSERT INTO sfc_detfactura SELECT * FROM aux_detfactura";
					$lb_valido = $io_sql->execute($ls_sql);

					$ls_sql = "SELECT numfac FROM aux_instpago";
					//print $ls_sql."<br>";
					$data_instpago = $io_sql->select($ls_sql);
					if($row=$io_sql->fetch_row($data_instpago)){
						$la_instpago=$io_sql->obtener_datos($data_instpago);
						$io_datastoreI->data=$la_instpago;
						$totrowI=$io_datastoreI->getRowCount("numfac");
						if($totrowI >0){
							$ls_sql = "INSERT INTO sfc_instpago SELECT * FROM aux_instpago";
							$lb_valido = $io_sql->execute($ls_sql);
						}
					}

					$ls_sql = "SELECT numfac FROM aux_facturaretencion";
					$data_ret = $io_sql->select($ls_sql);
					if($row=$io_sql->fetch_row($data_ret)){
						$la_ret=$io_sql->obtener_datos($data_ret);
						$io_datastoreR->data=$la_ret;
						$totrowR=$io_datastoreR->getRowCount("numfac");
						if($totrowR >0){
							$ls_sql = "INSERT INTO sfc_facturaretencion SELECT * FROM aux_facturaretencion";
							$lb_valido = $io_sql->execute($ls_sql);
						}
					}

					$ls_sql = "SELECT nro_documento FROM aux_nota";
					$data_nota = $io_sql->select($ls_sql);

					if($row=$io_sql->fetch_row($data_nota)){
						$la_nota=$io_sql->obtener_datos($data_nota);
						$io_datastoreN->data=$la_nota;
						$totrowN=$io_datastoreN->getRowCount("nro_documento");
						if($totrowN >0){
							$ls_sql = "INSERT INTO sfc_nota SELECT * FROM aux_nota";
							$lb_valido = $io_sql->execute($ls_sql);
						}
					}

					$ls_sql = "SELECT numfac FROM aux_devolucion";
					$data_nota = $io_sql->select($ls_sql);

					if($row=$io_sql->fetch_row($data_nota)){
						$la_nota=$io_sql->obtener_datos($data_nota);
						$io_datastoreN->data=$la_nota;
						$totrowN=$io_datastoreN->getRowCount("numfac");
						if($totrowD >0){
							$ls_sql = "INSERT INTO sfc_devolucion SELECT * FROM aux_devolucion";
							$lb_valido = $io_sql->execute($ls_sql);
							$ls_sql = "INSERT INTO sfc_detdevolucion SELECT * FROM aux_detdevolucion";
							$lb_valido = $io_sql->execute($ls_sql);
						}
					}

					$ls_sql = "SELECT numfac FROM aux_dt_cobrocliente";
					$data_dtcobro = $io_sql->select($ls_sql);

					if($row=$io_sql->fetch_row($data_dtcobro)){
						$la_dtcobro=$io_sql->obtener_datos($data_dtcobro);
						$io_datastoreC->data=$la_dtcobro;
						$totrowC=$io_datastoreC->getRowCount("numfac");
						if($totrowC >0){
							$ls_sql = "INSERT INTO sfc_dt_cobrocliente SELECT * FROM aux_dt_cobrocliente";
							$lb_valido = $io_sql->execute($ls_sql);
						}
					}

					$io_sql->commit();

					$lb_serie = "";
					$lb_correlativo = "";
					$lb_control = "";

					//print 'borrar tmp <br>';
					$io_actualizar->uf_borrar_tmp();

					$is_msg->message ("redireccionando!!!");

					$tope=strlen($_SERVER['HTTP_REFERER'])-34;
					$destURL = substr($_SERVER['HTTP_REFERER'],0,$tope);
					$destURL = $destURL."sigesp_sfc_d_actualizar_facturas.php";

					print "<script languaje=JavaScript>";
					print "alert('Los Correlativos de las Facturas se Actualizaron Correctamente, ahora debe Reprocesar los Movimientos de Facturas!!');";
					print "window.location='$destURL'";
					print "</script>";

				}// secuencia
				else{
					$io_sql->rollback();
					$is_msg->message ("Error actualizando correlativo de las Facturas!!");
				}

			}

	}else{
	}
}

/*if( $ls_operacion=="PROCESAR"){

	$lb_serie=strtoupper($lb_serie);
	$io_sql->begin_transaction();

	for($li_j=1; $li_j <= $li_filasordenes; $li_j++){

		$li_numfac = $_POST["txtnumfac".$li_j];
		$li_correlativo = $io_function->uf_cerosizquierda($lb_correlativo,20);

		$nvo_numfac = "FAC-".$lb_serie.$li_correlativo;

		$ls_sqlfac = "SELECT numfac FROM sfc_factura WHERE numfac='".$nvo_numfac."' ";
		$data_fac = $io_sql->select($ls_sqlfac);

		if($row=$io_sql->fetch_row($data_fac)){

			$lb_valido_ins=false;
			$lb_valido=false;

			$li_j = $li_filasordenes;

			$is_msg->message ("Error, el Correlativo $nvo_numfac ya existe. Ingrese otro correlativo!!");

		}else{

			if ($li_j == 1){
				$nvo_desde = $nvo_numfac;
			}

			//Buscamos la factura para insertar una igual pero con numfac diferente
			$ls_sqlfac = "SELECT * FROM sfc_factura WHERE numfac='".$li_numfac."' AND codemp='".$ls_codemp."'";
			$data_fac = $io_sql->select($ls_sqlfac);
			if($row=$io_sql->fetch_row($data_fac)){
				$la_fac=$io_sql->obtener_datos($data_fac);
				$io_datastoreF->data=$la_fac;

				$as_codemp=$io_datastoreF->getValue("codemp",1);
				$as_codcli=$io_datastoreF->getValue("codcli",1);
				$as_codusu=$io_datastoreF->getValue("codusu",1);
				$as_numcot=$io_datastoreF->getValue("numcot",1);
				$as_codciecaj=$io_datastoreF->getValue("codciecaj",1);
				$as_fecemi=$io_datastoreF->getValue("fecemi",1);
				$as_conpag=$io_datastoreF->getValue("conpag",1);
				$as_estfac=$io_datastoreF->getValue("estfac",1);
				$as_estfaccon=$io_datastoreF->getValue("estfaccon",1);
				$as_esppag=$io_datastoreF->getValue("esppag",1);
				$as_monto=$io_datastoreF->getValue("monto",1);
				$as_montoret=$io_datastoreF->getValue("montoret",1);
				$as_montopar=$io_datastoreF->getValue("montopar",1);
				$as_codtiend=$io_datastoreF->getValue("codtiend",1);
			}

			if($as_codciecaj !=""){
				$ls_sqlfac = "INSERT INTO sfc_factura(codemp,numfac,codcli,codusu,numcot,codciecaj,fecemi,conpag,estfac,estfaccon,esppag,monto,montoret,montopar,codtiend) values('".$as_codemp."','".$nvo_numfac."','".$as_codcli."','".$as_codusu."','".$as_numcot."','".$as_codciecaj."','".$as_fecemi."','".$as_conpag."','".$as_estfac."','".$as_estfaccon."','".$as_esppag."','".$as_monto."','".$as_montoret."','".$as_montopar."','".$as_codtiend."')";
			}else{
				$ls_sqlfac = "INSERT INTO sfc_factura(codemp,numfac,codcli,codusu,numcot,fecemi,conpag,estfac,estfaccon,esppag,monto,montoret,montopar,codtiend) values('".$as_codemp."','".$nvo_numfac."','".$as_codcli."','".$as_codusu."','".$as_numcot."','".$as_fecemi."','".$as_conpag."','".$as_estfac."','".$as_estfaccon."','".$as_esppag."','".$as_monto."','".$as_montoret."','".$as_montopar."','".$as_codtiend."')";
			}

			//print $ls_sqlfac."<br>";

			$lb_valido_ins=true;
			$lb_valido=true;

			$lb_valido_ins = $io_sql->execute($ls_sqlfac);
			if($lb_valido_ins){
				$ls_sql = "UPDATE sfc_detfactura SET numfac = '".$nvo_numfac."' WHERE numfac ='".$li_numfac."'";
				//print $ls_sql."<br>";
				$lb_valido = $io_sql->execute($ls_sql);
				if(!$lb_valido){
					$li_j = $li_filasordenes;
					$io_sql->rollback();
					$is_msg->message ("Error Actualizando el Detalles de Facturas!!");
				}
			}else{
				$li_j = $li_filasordenes;
				$io_sql->rollback();
				$is_msg->message ("Error Insertando la Nueva Factura!!");
			}

			if($lb_valido_ins){

				//Actualizar Facturas en INSTPAGO
				$ls_sql = "SELECT numfac FROM sfc_instpago WHERE numfac ='".$li_numfac."'";
				//print $ls_sql."<br>";
				$data_instpago = $io_sql->select($ls_sql);
				if($row=$io_sql->fetch_row($data_instpago)){
						$la_instpago=$io_sql->obtener_datos($data_instpago);
						$io_datastoreI->data=$la_instpago;
						$totrowI=$io_datastoreI->getRowCount("numfac");
						if($totrowI >0){
							$ls_sql = "UPDATE sfc_instpago SET numfac = '".$nvo_numfac."' WHERE numfac ='".$li_numfac."'";
							//print $ls_sql."<br>";
							$lb_valido = $io_sql->execute($ls_sql);
							if(!$lb_valido){
								$li_j = $li_filasordenes;
								$io_sql->rollback();
								$is_msg->message ("Error actualizando las Formas de Pago asociadas a las Facturas!!");
							}
						}
					}

				 //Actualizar Facturas en Retencion
				$ls_sql = "SELECT numfac FROM sfc_facturaretencion WHERE numfac ='".$li_numfac."'";
				$data_ret = $io_sql->select($ls_sql);

				if($row=$io_sql->fetch_row($data_ret)){
					$la_ret=$io_sql->obtener_datos($data_ret);
					$io_datastoreR->data=$la_ret;
					$totrowR=$io_datastoreR->getRowCount("numfac");
					if($totrowR >0){
						$ls_sql = "UPDATE sfc_facturaretencion SET numfac = '".$nvo_numfac."' WHERE numfac ='".$li_numfac."'";
						$lb_valido = $io_sql->execute($ls_sql);
						if(!$lb_valido){
							$li_j = $li_filasordenes;
							$io_sql->rollback();
							$is_msg->message ("Error actualizando las Retenciones asociadas a las Facturas!!");
						}
					}
				}

				//Actualizar Facturas en Nota
				$ls_sql = "SELECT nro_factura FROM sfc_nota WHERE nro_factura = '".$li_numfac."'";
				$data_nota = $io_sql->select($ls_sql);

				if($row=$io_sql->fetch_row($data_nota)){
					$la_nota=$io_sql->obtener_datos($data_nota);
					$io_datastoreN->data=$la_nota;
					$totrowN=$io_datastoreN->getRowCount("nro_factura");
					if($totrowN >0){

						$ls_sql = "UPDATE sfc_nota SET nro_factura ='".$nvo_numfac."' WHERE nro_factura = '".$li_numfac."'";

						$lb_valido = $io_sql->execute($ls_sql);
						if(!$lb_valido){
							$li_j = $li_filasordenes;
							$io_sql->rollback();
							$is_msg->message ("Error actualizando las Notas asociadas a las Facturas!!");
						}else{
							$ls_sql = "SELECT numnot FROM sfc_nota WHERE nro_factura = '".$nvo_numfac."' AND tipnot='CXC'";
							$nota = $io_sql->select($ls_sql);
							if($row=$io_sql->fetch_row($nota)){
								$ls_sql = "UPDATE sfc_nota SET numnot ='".$nvo_numfac."' WHERE nro_factura = '".$nvo_numfac."' AND tipnot='CXC'";
								$lb_valido = $io_sql->execute($ls_sql);
								if(!$lb_valido){
									$li_j = $li_filasordenes;
									$io_sql->rollback();
									$is_msg->message ("Error actualizando las Notas asociadas a las Facturas!!");
								}
							}
						}
					}// $totrowN
				}

				//Actualizar Facturas en Devolucion
				$ls_sql = "SELECT numfac FROM sfc_devolucion WHERE numfac = '".$li_numfac."'";
				$data_desp = $io_sql->select($ls_sql);

				if($row=$io_sql->fetch_row($data_desp)){
					$la_desp=$io_sql->obtener_datos($data_desp);
					$io_datastoreD->data=$la_desp;
					$totrowD=$io_datastoreD->getRowCount("numfac");
					if($totrowD >0){

						$ls_sql = "UPDATE sfc_devolucion SET numfac = '".$nvo_numfac."' WHERE numfac ='".$li_numfac."'";

						$lb_valido = $io_sql->execute($ls_sql);
						if(!$lb_valido){
							$li_j = $li_filasordenes;
							$io_sql->rollback();
							$is_msg->message ("Error actualizando las Devolucion Asociadas a las Facturas!!");
						}
					}// $totrowD
				}

			}// FIn IF $ls_sql_ins


			if($lb_valido_ins){
				$ls_sqldel = "DELETE FROM sfc_factura WHERE numfac='".$li_numfac."'";

				$lb_valido = $io_sql->execute($ls_sqldel);
				//$io_sql->commit();
			}

			$lb_correlativo++;

		}

	}// For $li_filasordenes

	if($lb_valido_ins and $lb_valido){

		$corr = $lb_correlativo-1;
		//print $corr."<br>";
		//print $ls_codcaj."fac"."<br>";
		$lb_valido = $io_secuencia->uf_actualizar_secuencia($ls_codcaj."fac",$corr);

		if($lb_valido){
			$io_sql->commit();

			$lb_serie = "";
			$lb_correlativo = "";

			$tope=strlen($_SERVER['HTTP_REFERER'])-34;
			$destURL = substr($_SERVER['HTTP_REFERER'],0,$tope);
			$destURL = $destURL."sigesp_sfc_d_actualizar_facturas.php";

			print "<script languaje=JavaScript>";
			print "alert('Los Correlativos de las Facturas se Actualizaron Correctamente, ahora debe Reprocesar los Movimientos de Facturas!!');";
			print "window.location='$destURL'";
			print "</script>";
		}else{
			$io_sql->rollback();
			$is_msg->message ("Error actualizando correlativo de las Facturas!!");
		}

	}else{
		$io_sql->rollback();
	}
}*/

if($ls_operacion=="GUARDAR"){

 $lb_valido = true;
 $io_sql->begin_transaction();

	for($li_j=1; $li_j <= $li_filasordenes; $li_j++){
		$li_numfac = $_POST["txtnumfac".$li_j];
		$li_fecemi = $_POST["txtfecemi".$li_j];
		$li_fecemi=$io_function->uf_convertirdatetobd($li_fecemi);

		$ls_sql = "UPDATE sfc_factura SET fecemi ='".$li_fecemi."' WHERE numfac = '".$li_numfac."' AND codtiend='".$lb_codtienda."' AND cod_caja='".$lb_codcaja."' ";
		$lb_valido = $io_sql->execute($ls_sql);

		if($lb_valido){
			$ls_sql = "SELECT nro_documento FROM sfc_nota WHERE nro_documento = '".$li_numfac."' AND codtiend='".$lb_codtienda."' ";

			$data_nota = $io_sql->select($ls_sql);

			if($row=$io_sql->fetch_row($data_nota)){
				$la_nota=$io_sql->obtener_datos($data_nota);
				$io_datastoreN->data=$la_nota;
				$totrowN=$io_datastoreN->getRowCount("nro_factura");
				if($totrowN >0){

					$ls_sql = "UPDATE sfc_nota SET fecnot ='".$li_fecemi."' WHERE nro_documento = '".$li_numfac."' AND codtiend='".$lb_codtienda."' ";

					$lb_valido = $io_sql->execute($ls_sql);
					if(!$lb_valido){
						$li_j=$li_filasordenes;
					}
				}// $totrowN
			}// If Existen Notas para la Factura

		}else{
			$li_j = $li_filasordenes;
		}
	}// Fin FOR

	if(!$lb_valido){
		$io_sql->rollback();
		$is_msg->message ("Error actualizando la fecha de las facturas!!");
	}else{
		$io_sql->commit();
	}

	$li_p =0;
	$li_filasmod=0;

	$lb_valido = $io_actualizar->uf_buscar_facturas($ls_codemp, $lb_fdesde, $lb_fhasta, $lb_codtienda, $lb_codcaja, $li_p, $li_filasmod, $la_objectordenes);

	$li_filasordenes=$li_p-1;

	if($li_filasmod > 0){
		$is_msg->message("Verifique el Correlativo de la Factura con su Fecha de Emisión y Modifique la Fecha si es Necesario!!");
	}

} //FIn $ls_operacion = "GUARDAR"


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

    <table width="520" height="197" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">

	 <tr>
		<td width="100%" height="195">
		<div align="center">
          <table  border="0" width="97%" cellspacing="0" class="formato-blanco" align="center">

            <tr>
              <td colspan="2" class="titulo-ventana">Actualizar Series de Facturas </td>
            </tr>

			<tr>
				<td height="30" colspan="2" >&nbsp;</td>
			</tr>

			<tr>
		        <td height="22"><div align="right">Unidad Operativa de Suministro</div></td>
		        <td>
		        	<input name="txtcodtienda" type="text" id="txtcodtienda" value="<? print $lb_codtienda?>">
		        	<a href="javascript:ue_buscartienda();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
		        	<input name="txtdestienda" type="text" id="txtdestienda"   class="sin-borde" value="<? print $lb_destienda?>" size="20" readonly="true">
		        </td>
	      	</tr>

	      	<tr>
		        <td height="22"><div align="right">Caja</div></td>
		        <td>
		        	<input name="txtcodcaja" type="text" id="txtcodcaja" value="<? print $lb_codcaja?>">
		        	<a href="javascript:ue_buscarcaja();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
		        	<input name="txtdescaja" type="text" id="txtdescaja"   class="sin-borde" value="<? print $lb_descaja?>" size="20" readonly="true">
		        </td>
	      	</tr>

			<tr>
				<td height="25"><div align="right">Fac. Desde:</div></td>
			  <td ><input name="txtfdesde" type="text"  id="txtfdesde" value="<? print $lb_fdesde;?>" size="30" maxlength="25" > <a href="javascript: ue_catalogo('txtfdesde');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>
			</tr>

			<tr>
				<td height="25"><div align="right">Fac. Hasta:</div></td>
				<td>
					<input name="txtfhasta" type="text"  id="txtfhasta" value="<? print $lb_fhasta;?>" size="30" maxlength="25" > <a href="javascript: ue_catalogo('txtfhasta');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a>
				</td>
			</tr>

			<tr>
			  <td height="25"><input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>"> <input name="filasordenes" type="hidden" id="filasordenes"  value="<? print $li_filasordenes?>"> <input name="filasmod" type="hidden" id="filasmod"  value="<? print $li_filasmod?>"> <input name="cajaF" type="hidden" id="cajaf"> <input name="txtconsulta" type="hidden" id="txtconsulta"></td>
				<td align="center"><a href="javascript: ue_buscar();">Buscar</a></td>
		  	</tr>


<? if($li_filasordenes >0 and $li_filasmod == 0){ ?>
		  <tr>
			<td height="25"><div align="right">Serie</div></td>
			<td ><input name="txtserie" type="text"  id="txtserie" value="<? print $lb_serie;?>" size="8" maxlength="5" ></td>
		  </tr>

		  <tr>
			<td height="30"><div align="right">Correlativo</div></td>
			<td ><input name="txtcorrelativo" type="text"  id="txtcorrelativo" value="<? print $lb_correlativo;?>" onKeyPress="return validaCajas(this,'i',event)" size="11" maxlength="10" ></td>
		  </tr>

		  <tr>
			<td height="25"><div align="right">Nro. Control</div></td>
		  	<td >
		  		<input name="txtcontrol" type="text"  id="txtcontrol" value="<? print $lb_control;?>" onKeyPress="return validaCajas(this,'i',event)" size="11" maxlength="10" > <!-- a href="javascript: ue_catalogo('txtfdesde');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="21" border="0"></a -->
		  	</td>
		 </tr>

		<tr>
		  <td height="25">&nbsp;</td>
			<td width="392" >
				<a href="javascript: ue_procesar();"><img src="Imagenes/ejecutar.gif" width="20" height="20" border="0">Procesar </a>
			</td>
		</tr>
<? }?>
            <tr>
              <td width="76" height="22" align="right">&nbsp;</td>
              <td width="392" >&nbsp;</td>
            </tr>

            <tr>
              <td height="8" colspan="2"><table width="430" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">

				<?PHP
				/************************************************************************************************************************/
/***************************   PROCESAR ********************************************************************************/
/************************************************************************************************************************/
if ($ls_operacion=="PROCESAR" or $ls_operacion=="PROCESAR1" or $ls_operacion=="BUSCAR" or $ls_operacion=="GUARDAR")
{ ?>
		<tr align="center" class="formato-blanco">
		<td height="11" colspan="2"><?php $io_grid->makegrid_2($li_filasordenes,$la_columordenes,$la_objectordenes,$li_anchoordenes,$ls_tituloordenes,$ls_nametable);?></td>
<?php
} elseif( $ls_operacion=="" )
	{
	?>
		<tr align="center" class="formato-blanco">
		<td height="11" colspan="2"><?php $io_grid->makegrid($li_filasordenes,$la_columordenes,$la_objectordenes,$li_anchoordenes,$ls_tituloordenes,$ls_nametable);?></td>
		</tr>
	<?php
	}

?>
               </table>
			  </td>
            </tr>
          </table>
        </div>
		</td>
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
function ue_guardar()
  {
	f=document.form1;

	f.operacion.value = "";
	lb_filasmod = f.filasmod.value;

	if( lb_filasmod > 0){
		f.operacion.value="GUARDAR";
		f.action="sigesp_sfc_d_actualizar_series.php";
		f.submit();
	}else{
		alert("No Existen Facturas que Modificar!");
	}

  }

/*******************************************************************************************************************************/
function ue_procesar()
  {
	f=document.form1;

	f.operacion.value = "";
	lb_serie = f.txtserie.value;
	lb_correlativo = f.txtcorrelativo.value;
	lb_control = f.txtcontrol.value;

	if( lb_serie !="" && lb_correlativo != "" && lb_control != ""){
		f.operacion.value="PROCESAR_1";
		f.action="sigesp_sfc_d_actualizar_series.php";
		f.submit();
	}else{
		alert("Indique la Serie, Nuevo Correlativo y el Nuevo Control de la Factura");
	}

  }

/*******************************************************************************************************************************/
function ue_buscar()
  {
	f=document.form1;

	lb_fdesde = f.txtfdesde.value;
	lb_fhasta = f.txtfhasta.value;
	lb_tienda = f.txtcodtienda.value;
	lb_caja = f.txtcodcaja.value;

	if( lb_fdesde!="" && lb_fhasta!="" && lb_tienda!="" && lb_caja!=""){
		f.operacion.value="BUSCAR";
		f.action="sigesp_sfc_d_actualizar_series.php";
		f.submit();
	}else{
		alert("Indique la Tienda, Caja y el rango de facturas que desea procesar!");
	}

  }

/*******************************************************************************************************************************/
function ue_catalogo(caja)
{
	f=document.form1;
	/*li_leer=f.leer.value;
	if(li_leer==1)
	{*/

	lb_tienda = f.txtcodtienda.value;
	lb_caja = f.txtcodcaja.value;

	if( lb_tienda == "" || lb_caja == ""){
		alert('Seleccione la Tienda y la Caja de las Facturas a Buscar');

	}else{
		f.operacion.value="";
		f.cajaF.value = caja;
		pagina="sigesp_cat_factura.php?tienda="+f.txtcodtienda.value+"&caja="+f.txtcodcaja.value;
		popupWin(pagina,"catalogo",850,450);
	}

}

/*******************************************************************************************************************************/

function ue_cargarfactura(codigo,numfac,cotizacion,fecemi,conpag,monto,estfac,nombre,estfaccon,esppag,cedcli)
{
	f=document.form1;
	//f.txtconsulta.value="M";

	lb_caja = f.cajaF.value;

	if(lb_caja == "txtfdesde"){
		f.txtfdesde.value = numfac;
		f.txtfhasta.value = numfac;
	}else{
		if(lb_caja == "txtfhasta"){
			f.txtfhasta.value = numfac;
		}
	}
	f.cajaF.value="";
	//lb_fdesde = f.txtfdesde.value;
	//lb_fhasta = f.txtfhasta.value;
}

/////////////////////////////////////////////////////////////////////////////
function ue_buscartienda()
{
    f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_tienda.php" ;
	popupWin(pagina,"catalogo_tiendas",600,250);
}

/////////////////////////////////////////////////////////////////////////////
function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentapre,denpre,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)
{
	f=document.form1;
	f.txtcodtienda.value=codtie;
	f.txtdestienda.value=nomtie;
}

/////////////////////////////////////////////////////////////////////////////
function ue_buscarcaja()
{
    f=document.form1;
	f.operacion.value="";
	tienda=f.txtcodtienda.value;
	if(tienda==''){
		alert('Selecciona la tienda!!');
	}else{
		pagina="sigesp_cat_cajatienda.php?codtienda="+tienda ;
		popupWin(pagina,"catalogo_caja",600,250);
	}

}

/////////////////////////////////////////////////////////////////////////////
function ue_cargarcaja(codtienda,ls_destienda,codcaja,desccaja,precot,prefac,predev,preped,sercot,serfac,serdev,serped,sernot,sercon,formalibre,precob,sercob)
{
	f=document.form1;
	f.txtcodcaja.value=codcaja;
	f.txtdescaja.value=desccaja;
}

</script>
</html>
