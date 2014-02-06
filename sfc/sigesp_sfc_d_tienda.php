<?Php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../index.php'";
	 print "</script>";
   }
$la_datemp=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definici&oacute;n de Unidad Operativa de Suministro</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
.Estilo1 {font-size: 10px}
.Estilo2 {font-size: 10}
.Estilo3 {
	color: #6699CC;
	font-size: 14px;
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
    <td width="438" height="20" class="cd-menu"><span class="letras-negrita Estilo3">Sistema de Facturaci&oacute;n</span></td>
    <td width="340" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?Php
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_tienda.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST))
	{
		$ls_permisos            = $_POST["permisos"];
		$la_permisos["leer"]    = $_POST["leer"];
		$la_permisos["incluir"] = $_POST["incluir"];
		$la_permisos["cambiar"] = $_POST["cambiar"];
		$la_permisos["eliminar"]= $_POST["eliminar"];
		$la_permisos["imprimir"]= $_POST["imprimir"];
		$la_permisos["anular"]  = $_POST["anular"];
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
	require_once("class_folder/sigesp_sfc_c_tienda.php");
	require_once("class_folder/sigesp_sfc_class_utilidades.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("../shared/class_folder/class_funciones.php");
	$io_grid=new grid_param();
	$io_tienda = new sigesp_sfc_c_tienda();
	$io_datastore= new class_datastore();
	$io_utilidad = new sigesp_sfc_class_utilidades();
	$is_msg=new class_mensajes();
	$io_function=new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$io_sql= new class_sql($io_connect);
	/***********************************************************************************************
	/                                    GRID Ctas. CONTABLES                                      *
	/***********************************************************************************************/
$ls_titulocuenta="Cuentas Contables Asociadas";
$li_anchocuenta=600;
$ls_nametable="grid2";
$la_columcuenta[1]="Cta. Contable";
$la_columcuenta[2]="Descripci&oacute;n";
$la_columcuenta[3]="Edici&oacute;n";

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		//$ls_nrocartagr=$io_function->uf_cerosizquierda($_POST["txtnrocartagr"],'25');
		$ls_codtie=$_POST["txtcodtie"];
		$ls_dentie=$_POST["txtdentie"];
		$ls_dirtie=$_POST["txtdirtie"];
		$ls_teltie=$_POST["txtteltie"];
		$ls_riftie=$_POST["txtriftie"];
		$ls_codest=$_POST["cmbestado"];
		$ls_codmun=$_POST["cmbmunicipio"];
		$ls_codpar=$_POST["cmbparroquia"];
		$ls_codpai=$_POST["hidcodpai"];
		$ls_item=$_POST["txtitem"];
		$ls_spicuenta=$_POST["txtspicuenta"];
		$ls_denospi=$_POST["txtdenospi"];
		$ls_codunidad=$_POST["txtunidadejecutora"];
		$ls_denunidad=$_POST["txtdenunidad"];
		$ls_codestpro1=$_POST["txtcodestpro1"];
		$ls_codestpro2=$_POST["txtcodestpro2"];
		$ls_codestpro3=$_POST["txtcodestpro3"];
		$ls_codestpro4=$_POST["txtcodestpro4"];
		$ls_codestpro5=$_POST["txtcodestpro5"];
		$ls_cuentapre=$_POST["txtcuentapre"];
		$ls_dencuentapre=$_POST["txtdencuentapre"];
		$ls_hidstatus=$_POST["hidstatus"];
		$li_filascuentas=$_POST["filascuentas"];
   		$li_removercuentas=$_POST["hidremovercuentas"];
   		if ($ls_operacion != "ue_cargarcuenta" && $ls_operacion != "ue_removercuenta")
		{
			for($li_i=1;$li_i<$li_filascuentas;$li_i++)
			{
	
				$ls_codcuenta=$_POST["txtcodcuenta".$li_i];
				$ls_dencuenta=$_POST["txtdencuenta".$li_i];
		
				$la_objectcuenta[$li_i][1]="<input name=txtcodcuenta".$li_i." type=text id=txtcodcuenta".$li_i." value='".$ls_codcuenta."' class=sin-borde size=21 style= text-align:center readonly>";
				$la_objectcuenta[$li_i][2]="<input name=txtdencuenta".$li_i." type=text id=txtdencuenta".$li_i." value='".$ls_dencuenta."' class=sin-borde size=45 style= text-align:left readonly>";
		
				if ($ls_operacion!="ue_guardar" )
				{
					$la_objectcuenta[$li_i][3]="<a href=javascript:ue_removercuenta(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=5 height=5 border=0 style= text-align:center></a>";
				}
				else
				{
					$la_objectcuenta[$li_i][3]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=5 border=0 style= text-align:center>";
				}
			}
			$la_objectcuenta[$li_filascuentas][1]="<input name=txtcodcuenta".$li_filascuentas." type=text id=txtcodcuenta".$li_filascuentas." class=sin-borde size=21 style= text-align:center readonly>";
			$la_objectcuenta[$li_filascuentas][2]="<input name=txtdencuenta".$li_filascuentas." type=text id=txtdencuenta".$li_filascuentas." class=sin-borde size=45 style= text-align:left readonly>";
			$la_objectcuenta[$li_filascuentas][3]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
		}
		$ls_facporvol=$_POST["rbfactvolumen"];
		if($ls_facporvol=='t')
		{
			$lb_si_facporvol="checked";
			$lb_no_facporvol="";
		}
		else
		{
			$lb_si_facporvol="";
			$lb_no_facporvol="checked";
		}
		$ls_codtipunisum=$_POST["codunisum"];
		$ls_dentipunisum=$_POST["denunisum"];
		$ls_estatus=$_POST["estatus"];
	}
	else
	{
		$ls_operacion="";
		$ls_hidstatus="";
		$ls_codtie="";
		$ls_dentie="";
		$ls_dirtie="";
		$ls_teltie="";
		$ls_riftie="";
		$ls_codest="";
		$ls_codmun="";
		$ls_codpar="";
		$ls_codpai="058";
		$ls_item="";
		$ls_spicuenta="";
		$ls_denospi="";
		$ls_codunidad="";
		$ls_denunidad="";
		$ls_codestpro1="";
		$ls_codestpro2="";
		$ls_codestpro3="";
		$ls_codestpro4="";
		$ls_codestpro5="";
		$ls_cuentapre="";
		$ls_dencuentapre="";
		$li_filascuentas=1;
    	$la_objectcuenta[$li_filascuentas][1]="<input name=txtcodcuenta".$li_filascuentas." type=text id=txtcodcuenta".$li_filascuentas." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectcuenta[$li_filascuentas][2]="<input name=txtdencuenta".$li_filascuentas." type=text id=txtdencuenta".$li_filascuentas." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectcuenta[$li_filascuentas][3]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
		$lb_si_facporvol="";
		$lb_no_facporvol="checked";
		$ls_codtipunisum="";
		$ls_dentipunisum="";
		$ls_estatus='t';
	}

	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_nuevo")
	{
	    require_once("../shared/class_folder/class_funciones_db.php");
		require_once ("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_connect=$io_include->uf_conectar();
		$io_funcdb=new class_funciones_db($io_connect);
		$ls_codtie=$io_funcdb->uf_generar_codigo(false,0,"sfc_tienda","codtie",4);
		$ls_dentie="";
		$ls_estatus='t';
		$lb_si_facporvol="";
		$lb_no_facporvol="checked";
		$ls_codtipunisum="";
		$ls_dentipunisum="";
		$ls_hidstatus="";
		$ls_dirtie="";
		$ls_teltie="";
		$ls_riftie="";
		$ls_codest="";
		$ls_codmun="";
		$ls_codpar="";
		$ls_denospi="";
		$ls_item="";
		$ls_spicuenta="";
		$ls_codunidad="";
		$ls_denunidad="";
		$ls_codestpro1="";
		$ls_codestpro2="";
		$ls_codestpro3="";
		$ls_codestpro4="";
		$ls_codestpro5="";
		$ls_cuentapre="";
		$ls_dencuentapre="";
		$li_filascuentas=1;
    	$la_objectcuenta[$li_filascuentas][1]="<input name=txtcodcuenta".$li_filascuentas." type=text id=txtcodcuenta".$li_filascuentas." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectcuenta[$li_filascuentas][2]="<input name=txtcuenta".$li_filascuentas." type=text id=txtdencuenta".$li_filascuentas." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectcuenta[$li_filascuentas][3]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	}
	elseif($ls_operacion=="ue_cargarcuenta")
{

	$li_filascuentas=$_POST["filascuentas"];
	$li_filascuentas++;

	for($li_i=1;$li_i<$li_filascuentas;$li_i++)
	{
		$ls_codcuenta=$_POST["txtcodcuenta".$li_i];
		$ls_dencuenta=$_POST["txtdencuenta".$li_i];

		$la_objectcuenta[$li_i][1]="<input name=txtcodcuenta".$li_i." type=text id=txtcodcuenta".$li_i." value='".$ls_codcuenta."' class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectcuenta[$li_i][2]="<input name=txtdencuenta".$li_i." type=text id=txtdencuenta".$li_i." value='".$ls_dencuenta."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectcuenta[$li_i][3]="<a href=javascript:ue_removercuenta(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
		$la_objectcuenta[$li_filascuentas][1]="<input name=txtcodcuenta".$li_filascuentas." type=text id=txtcodcuenta".$li_filascuentas." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectcuenta[$li_filascuentas][2]="<input name=txtdencuenta".$li_filascuentas." type=text id=txtdencuenta".$li_filascuentas." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectcuenta[$li_filascuentas][3]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}
elseif ($ls_operacion=="ue_cargarcuenta_tienda")
{
	    $ls_codpai=$_POST["hidcodpai"];
		$ls_codest=$_POST["hidcodest"];
		$ls_codmun=$_POST["hidcodmun"];
		$ls_codpar=$_POST["hidcodpar"];
		$ls_hidstatus=$_POST["hidstatus"];
	$li_filascuentas=1;
		$la_objectcuenta[$li_filascuentas][1]="<input name=txtcodcuenta".$li_filascuentas." type=text id=txtcodcuenta".$li_filascuentas." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectcuenta[$li_filascuentas][2]="<input name=txtdencuenta".$li_filascuentas." type=text id=txtdencuenta".$li_filascuentas." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectcuenta[$li_filascuentas][3]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";

	$ls_cadena="SELECT sfc_tienda_ctascontables.*,scg_cuentas.denominacion FROM sfc_tienda_ctascontables,scg_cuentas " .
			" WHERE  sfc_tienda_ctascontables.codtiend='".$ls_codtie."' AND sfc_tienda_ctascontables.sc_cuenta=scg_cuentas.sc_cuenta ".
			" AND sfc_tienda_ctascontables.codemp=scg_cuentas.codemp ".
			" ORDER BY  sfc_tienda_ctascontables.sc_cuenta ASC;";
			$arr_detcuenta=$io_sql->select($ls_cadena);

			if($arr_detcuenta==false&&($io_sql->message!=""))
			{
				$is_msg->message("No hay registros de Cuentas");
			}
			else
			{
				if($row=$io_sql->fetch_row($arr_detcuenta))

 				  {
					$la_cuenta=$io_sql->obtener_datos($arr_detcuenta);
					$io_datastore->data=$la_cuenta;
					$totrow=$io_datastore->getRowCount("sc_cuenta");

					for($li_i=1;$li_i<=$totrow;$li_i++)
					{

						$ls_codcuenta=$io_datastore->getValue("sc_cuenta",$li_i);
		                $ls_dencuenta=$io_datastore->getValue("denominacion",$li_i);

						$la_objectcuenta[$li_i][1]="<input name=txtcodcuenta".$li_i." type=text id=txtcodcuenta".$li_i." value='".$ls_codcuenta."' class=sin-borde size=21 style= text-align:center readonly>";
						$la_objectcuenta[$li_i][2]="<input name=txtdencuenta".$li_i." type=text id=txtdencuenta".$li_i." value='".$ls_dencuenta."' class=sin-borde size=45 style= text-align:left readonly>";
						$la_objectcuenta[$li_i][3]="<a href=javascript:ue_removercuenta(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";

					}
			 $li_filascuentas=$li_i;
			$la_objectcuenta[$li_filascuentas][1]="<input name=txtcodcuenta".$li_filascuentas." type=text id=txtcodcuenta".$li_filascuentas." class=sin-borde size=21 style= text-align:center readonly>";
			$la_objectcuenta[$li_filascuentas][2]="<input name=txtdencuenta".$li_filascuentas." type=text id=txtdencuenta".$li_filascuentas." class=sin-borde size=45 style= text-align:left readonly>";
			$la_objectcuenta[$li_filascuentas][3]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
  		 }
	}

}
elseif($ls_operacion=="ue_removercuenta")
{
  	$li_filascuentas=$_POST["filascuentas"];
	$li_filascuentas=$li_filascuentas - 1;
	$li_removercuenta=$_POST["hidremovercuenta"];

	$li_temp=0;

	for($li_i=1;$li_i<=$li_filascuentas;$li_i++)
	{
		if ($li_i!=$li_removercuenta)
		{
		 $li_temp=$li_temp+1;
  		$ls_codcuenta=$_POST["txtcodcuenta".$li_i];
		$ls_dencuenta=$_POST["txtdencuenta".$li_i];

		$la_objectcuenta[$li_temp][1]="<input name=txtcodcuenta".$li_temp." type=text id=txtcodcuenta".$li_temp." value='".$ls_codcuenta."' class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectcuenta[$li_temp][2]="<input name=txtdencuenta".$li_temp." type=text id=txtdencuenta".$li_temp." value='".$ls_dencuenta."' class=sin-borde size=45 style= text-align:left readonly>";
    	$la_objectcuenta[$li_temp][3]="<a href=javascript:ue_removercuenta(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";

		}
	}
        $la_objectcuenta[$li_filascuentas][1]="<input name=txtcodcuenta".$li_filascuentas." type=text id=txtcodcuenta".$li_filascuentas." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectcuenta[$li_filascuentas][2]="<input name=txtdencuenta".$li_filascuentas." type=text id=txtdencuenta".$li_filascuentas." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectcuenta[$li_filascuentas][3]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";

}
	elseif($ls_operacion=="ue_guardar")
	{

	   $la_detalles["codtiend"][1]="";
	   $la_detalles["sc_cuenta"][1]="";
	   $la_detalles["denominacion"][1]="";
		//print 'filas:'.$li_filascuentas;
    for ($li_i=1;$li_i<$li_filascuentas;$li_i++)
     {

	   $la_detalles["codtiend"][$li_i]=$_POST[$ls_codtie];
	   $la_detalles["sc_cuenta"][$li_i]=$_POST["txtcodcuenta".$li_i];
	   $la_detalles["denominacion"][$li_i]=$_POST["txtdencuenta".$li_i];
     }
     if ($li_filascuentas>='10')
     {
		$lb_valido=$io_tienda->uf_guardar_tienda($ls_codtie,$ls_dentie,$ls_dirtie,$ls_teltie,$ls_riftie,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_item,$ls_spicuenta,$ls_codunidad,$ls_cuentapre,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$la_seguridad,$ls_codtipunisum,$ls_facporvol);

		if ($lb_valido)
		{
			$lb_valido=$io_tienda->uf_update_tienda_ctascontables($ls_codtie,$la_detalles,$li_filascuentas,$la_seguridad);
			$is_msg->message ($io_tienda->io_msgc);
			//print 'paso';

		    $ls_dentie="";
		    $ls_dirtie="";
		    $ls_teltie="";
		    $ls_riftie="";
			$ls_codpai="";
		    $ls_codest="";
		    $ls_codmun="";
		    $ls_codpar="";
			$ls_item="";
			$ls_spicuenta="";
			$ls_denospi="";
			$ls_codunidad="";
			$ls_denunidad="";
			$ls_codestpro1="";
			$ls_codestpro2="";
			$ls_codestpro3="";
			$ls_codestpro4="";
			$ls_codestpro5="";
			$ls_cuentapre="";
			$ls_dencuentapre="";
			$ls_hidstatus="";
			$li_filascuentas=1;
			$ls_estatus='t';
			$lb_si_facporvol="";
			$lb_no_facporvol="checked";
			$ls_codtipunisum="";
			$ls_dentipunisum="";
            $la_objectcuenta[$li_filascuentas][1]="<input name=txtcodcuenta".$li_filascuentas." type=text id=txtcodcuenta".$li_filascuentas." class=sin-borde size=21 style= text-align:center readonly>";
		    $la_objectcuenta[$li_filascuentas][2]="<input name=txtdencuenta".$li_filascuentas." type=text id=txtdencuenta".$li_filascuentas." class=sin-borde size=45 style= text-align:left readonly>";
		    $la_objectcuenta[$li_filascuentas][3]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
			if($ls_codtie==$_SESSION["ls_codtienda"])
			{
			$ls_codtie='';
			?>
			<script language="javascript">
			alert("Ahora debera Ingresar Nuevamente para Actualizar su Sesion");
			window.location="../index.php";
			</script>
			<?
			}else{
				$ls_codtie='';

			}
		}
		else
		{
			if($lb_valido===0)
			{
				$ls_codtie="";
		        $ls_dentie="";
		        $ls_dirtie="";
		        $ls_teltie="";
		        $ls_riftie="";
			    $ls_codpai="";
		        $ls_codest="";
		        $ls_codmun="";
		        $ls_codpar="";
				$ls_item="";
				$ls_spicuenta="";
				$ls_denospi="";
				$ls_codunidad="";
				$ls_denunidad="";
				$ls_codestpro1="";
				$ls_codestpro2="";
				$ls_codestpro3="";
				$ls_codestpro4="";
				$ls_codestpro5="";
				$ls_cuentapre="";
				$ls_dencuentapre="";
				$ls_hidstatus="";
				$ls_estatus='t';
				$lb_si_facporvol="";
				$lb_no_facporvol="checked";
				$ls_codtipunisum="";
				$ls_dentipunisum="";
			}
			else
			{
				$is_msg->message ($io_tienda->io_msgc);
			}

		}
		}else
     {
     ?>
     <script language="JavaScript" type="text/javascript">
	alert ('DEBE INGRESAR LAS 13 CUENTAS CONTABLES ASOCIADAS A LA TIENDA QUE SE MUESTRAN EN EL CATALOGO DE CUENTAS');
     </script>
<?php
     }

	}
/****************************************************************************************************************************/
/****************************************  eliminar  ************************************************************************/
/****************************************************************************************************************************/
elseif($ls_operacion=="ue_eliminar")
{

	/***********************  verificar si cajero**********************************************************/
	     $ls_sql="SELECT *
                   FROM sfc_cajero
                  WHERE codemp='".$la_datemp["codemp"]."' AND codtiend='".$ls_codtie."'";

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_caj=false;
			$is_msg="Error en uf_select_cajero ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_caj=true; //Registro encontrado
		        $is_msg->message ("La tienda esta enlazada a un cajero no se puede eliminar!!!");
			}
			else
			{
				$lb_valido_caj=false; //"Registro no encontrado"
			}
		}
     /*****************************************************************************************************************************/
	/***********************  verificar Caja **********************************************************/
	     $ls_sql="SELECT *
                   FROM sfc_caja
                  WHERE codemp='".$la_datemp["codemp"]."' AND codtiend='".$ls_codtie."'";

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_caja=false;
			$is_msg="Error en uf_select_caja ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_caja=true; //Registro encontrado
		        $is_msg->message ("La tienda esta enlazada a un caja no se puede eliminar!!!");
			}
			else
			{
				$lb_valido_caja=false; //"Registro no encontrado"
			}
		}
     /*****************************************************************************************************************************/
     /***********************  verificar Producto **********************************************************/
	     $ls_sql="SELECT *
                   FROM sfc_producto
                  WHERE codemp='".$la_datemp["codemp"]."' AND codtiend='".$ls_codtie."'";

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_producto=false;
			$is_msg="Error en uf_select_producto ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_producto=true; //Registro encontrado
		        $is_msg->message ("La tienda esta enlazada a un producto no se puede eliminar!!!");
			}
			else
			{
				$lb_valido_producto=false; //"Registro no encontrado"
			}
		}
     /*****************************************************************************************************************************/
      /*****************************************************************************************************************************/
     /***********************  verificar Movimientos **********************************************************/
	     $ls_sql="SELECT *
                   FROM sfc_cierrecaja
                  WHERE codemp='".$la_datemp["codemp"]."' AND codtiend='".$ls_codtie."'";

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_cierre=false;
			$is_msg="Error en uf_select_cierrecaja ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_cierre=true; //Registro encontrado
		        $is_msg->message ("La tienda tiene movimientos asociados no se puede eliminar!!!");
			}
			else
			{
				$lb_valido_cierre=false; //"Registro no encontrado"
			}
		}
     /*****************************************************************************************************************************/


	if ($lb_valido_caj==false and $lb_valido_caja==false and $lb_valido_producto==false and $lb_valido_cierre==false)
	 {
//print 'paso';
		//$lb_valido=$io_tienda->uf_delete_ctascontables($ls_codtie,$la_seguridad);

		/*if ($lb_valido===true)
		{*/
		$lb_valido=$io_tienda->uf_delete_tienda($ls_codtie,$la_seguridad);
		$ls_mensaje=$io_tienda->io_msgc;

		    $is_msg->message ($ls_mensaje);
			$ls_codtie="";
		    $ls_dentie="";
		    $ls_dirtie="";
		    $ls_teltie="";
		    $ls_riftie="";
			$ls_codpai="";
		    $ls_codest="";
		    $ls_codmun="";
		    $ls_codpar="";
			$ls_estatus='t';
			$lb_si_facporvol="";
			$lb_no_facporvol="checked";
			$ls_codtipunisum="";
			$ls_dentipunisum="";
			$ls_item="";
			$ls_spicuenta="";
			$ls_denospi="";
			$ls_codunidad="";
			$ls_denunidad="";
			$ls_codestpro1="";
			$ls_codestpro2="";
			$ls_codestpro3="";
			$ls_codestpro4="";
			$ls_codestpro5="";
			$ls_cuentapre="";
			$ls_dencuentapre="";
			$ls_hidstatus="";
			//print 'paso';
			$li_filascuentas=1;
    	$la_objectcuenta[$li_filascuentas][1]="<input name=txtcodcuenta".$li_filascuentas." type=text id=txtcodcuenta".$li_filascuentas." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectcuenta[$li_filascuentas][2]="<input name=txtcuenta".$li_filascuentas." type=text id=txtdencuenta".$li_filascuentas." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectcuenta[$li_filascuentas][3]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";

		//}
	  }
}
	elseif($ls_operacion=="ue_cargartienda")
	{

	}

	elseif($ls_operacion=="ue_cargarcuentastiendas")
	{

	$li_filascuentas=1;
		$la_objectcuenta[$li_filascuentas][1]="<input name=txtcodcuenta".$li_filascuentas." type=text id=txtcodcuenta".$li_filascuentas." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectcuenta[$li_filascuentas][2]="<input name=txtdencuenta".$li_filascuentas." type=text id=txtdencuenta".$li_filascuentas." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectcuenta[$li_filascuentas][3]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";

	$ls_cadena =" SELECT ue.sc_cuenta,ue.denominacion as dencontable
				  FROM scg_cuentas ue
		   		  WHERE ue.codemp = '".$ls_empresa."'" .
		   		  " AND substr(ue.sc_cuenta,10,2)='".substr($ls_codtie,2,2)."' AND " .
		   		  " (substr(ue.sc_cuenta,0,10)='111010101' ".
				  " OR substr(ue.sc_cuenta,0,10)='112030102' ".
				  " OR substr(ue.sc_cuenta,0,10)='112030103'" .
		   		  " OR substr(ue.sc_cuenta,0,10)='219090101' ".
				  " OR substr(ue.sc_cuenta,0,10)='219090201' ".
				  " OR substr(ue.sc_cuenta,0,10)='643010103'" .
		   		  " OR substr(ue.sc_cuenta,0,10)='691010101' ".
				  " OR substr(ue.sc_cuenta,0,10)='113040101' ".
				  " OR substr(ue.sc_cuenta,0,10)='112049902'" .
		   		  " OR substr(ue.sc_cuenta,0,10)='112049904' ".
				  " OR substr(ue.sc_cuenta,0,10)='613140101'".
				  " OR substr(ue.sc_cuenta,0,10)='514010101')".
		   		  " AND substr(ue.sc_cuenta,13,2)='01' AND ".
				  " substr(ue.sc_cuenta,12,3)='001' AND ue.status='C' ".
				  " ORDER BY ue.sc_cuenta";

			$arr_detcuenta=$io_sql->select($ls_cadena);

			if($arr_detcuenta==false&&($io_sql->message!=""))
			{
				$is_msg->message("No hay registros de Cuentas");
			}
			else
			{
				if($row=$io_sql->fetch_row($arr_detcuenta))

 				  {
					$la_cuenta=$io_sql->obtener_datos($arr_detcuenta);
					$io_datastore->data=$la_cuenta;
					$totrow=$io_datastore->getRowCount("sc_cuenta");

					for($li_i=1;$li_i<=$totrow;$li_i++)
					{

						$ls_codcuenta=$io_datastore->getValue("sc_cuenta",$li_i);
		                $ls_dencuenta=$io_datastore->getValue("dencontable",$li_i);

		$la_objectcuenta[$li_i][1]="<input name=txtcodcuenta".$li_i." type=text id=txtcodcuenta".$li_i." value='".$ls_codcuenta."' class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectcuenta[$li_i][2]="<input name=txtdencuenta".$li_i." type=text id=txtdencuenta".$li_i." value='".$ls_dencuenta."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectcuenta[$li_i][3]="<a href=javascript:ue_removercuenta(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";

					}
			if ($li_i<12)
			{
				$is_msg->message("Verifique Cuentas Contables deben ser 12");
			}
		 $li_filascuentas=$li_i;
		$la_objectcuenta[$li_filascuentas][1]="<input name=txtcodcuenta".$li_filascuentas." type=text id=txtcodcuenta".$li_filascuentas." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectcuenta[$li_filascuentas][2]="<input name=txtdencuenta".$li_filascuentas." type=text id=txtdencuenta".$li_filascuentas." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectcuenta[$li_filascuentas][3]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
  		 }
	}


	}
	elseif($ls_operacion=="ue_cargar_ctasspi")
	{
		/* Modificacion de la sentencia
		$ls_cadena =" SELECT spi_cuenta,denominacion as denospi,(previsto+aumento-disminucion) as disponible
				  FROM spi_cuentas
		   		  WHERE codemp = '".$ls_empresa."' AND substr(spi_cuenta,8,2) like '".substr($ls_codtie,2,2)."'
				  AND substr(spi_cuenta,1,7)='3030102' AND status='C' ORDER BY spi_cuenta"; */
				  
	  $ls_cadena =" SELECT spi_cuenta,denominacion as denospi,(previsto+aumento-disminucion) as disponible
				  FROM spi_cuentas
		   		  WHERE codemp = '".$ls_empresa."' 
				  AND substr(trim(both ' ' from spi_cuenta),1,4)='3030' AND status='C' ORDER BY spi_cuenta";			  
	  //print $ls_cadena;
	//$ls_sql="SELECT SC_cuenta,denominacion FROM SIGESP_Plan_unico ";
	$rs_cta=$io_sql->select($ls_cadena);
	if($rs_cta==false)
	{
		$is_msg->message("No hay registros de Cuentas Presupuestaria de Ingreso para esta tienda");
	}
	else
	{
		$data=$rs_cta;
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$io_datastore->data=$data;
			$totrow=$io_datastore->getRowCount("spi_cuenta");
			for($z=1;$z<=$totrow;$z++)
			{
				$ls_spicuenta=$io_datastore->getValue("spi_cuenta",$z);
				$ls_denospi=$io_datastore->getValue("denospi",$z);
				//print $ls_denospi;
				/*$scgcuenta=$io_datastore["sc_cuenta"][$z];
				$status=$io_datastore["status"][$z];
				$disponible=$io_datastore["disponible"][$z];*/
			}
			$io_sql->free_result($rs_cta);
			$io_sql->close();
		}
		else
		{
		?>
		<script language="JavaScript">
		alert("No se han creado Cuentas.....");
		//close();
        </script>
		<?
		}
	}
}
elseif($ls_operacion=="ue_cargar_cuentapre")
{
		/* Modificación de la sentencia falta adicionar el codigo de la tienda
		$ls_cadena ="SELECT ue.spg_cuenta,ue.codestpro1,ue.codestpro2,ue.codestpro3,codestpro4,codestpro5,ue.denominacion as denpre FROM spg_cuentas ue WHERE ue.codemp = '".$ls_empresa."'  AND substr(spg_cuenta,1,7)='4021101'" .
					"   AND substr(spg_cuenta,10,2)='".substr($ls_codtie,2,2)."' AND status='C' ORDER BY ue.spg_cuenta"; */
	    $ls_cadena ="SELECT ue.spg_cuenta,ue.codestpro1,ue.codestpro2,ue.codestpro3,codestpro4,codestpro5,ue.denominacion as denpre FROM spg_cuentas ue WHERE ue.codemp = '".$ls_empresa."'  " .
					"   AND substr(trim(both ' ' from spg_cuenta),1,7)='4021101' AND status='C' ORDER BY ue.spg_cuenta";

	  //print $ls_cadena;
	//$ls_sql="SELECT SC_cuenta,denominacion FROM SIGESP_Plan_unico ";
	$rs_cta=$io_sql->select($ls_cadena);
	if($rs_cta==false)
	{
		$is_msg->message("No hay registros de Unidades Administrativas para esta tienda");
	}
	else
	{
		$data=$rs_cta;
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$io_datastore->data=$data;
			$totrow=$io_datastore->getRowCount("spg_cuenta");
			for($z=1;$z<=$totrow;$z++)
			{
				//print 'paso';
				$ls_codestpro1=$io_datastore->getValue("codestpro1",$z);
				$ls_codestpro2=$io_datastore->getValue("codestpro2",$z);
				$ls_codestpro3=$io_datastore->getValue("codestpro3",$z);
				$ls_codestpro4=$io_datastore->getValue("codestpro4",$z);
				$ls_codestpro5=$io_datastore->getValue("codestpro5",$z);
				$ls_cuentapre=$io_datastore->getValue("spg_cuenta",$z);
				$ls_dencuentapre=$io_datastore->getValue("denpre",$z);
				//print $ls_dencuentapre;
			}
			$io_sql->free_result($rs_cta);
			$io_sql->close();
		}
		else
		{
		?>
		<script language="JavaScript">
		alert("No se han creado Cuentas.....");
		//close();
        </script>
		<?
		}
	}
	}
elseif($ls_operacion=="ue_validar")
	{
		$ls_codtie=$io_function->uf_cerosizquierda($ls_codtie,4);
		$ls_sql="SELECT *
                   FROM sfc_tienda
                  WHERE codemp='".$la_datemp["codemp"]."' AND codtiend='".$ls_codtie."';";
				  
	    $lb_valido=$io_utilidad->uf_datacombo($ls_sql,&$la_tienda);
		if ($lb_valido==true)
		{
		  $is_msg->message ("El numero de tienda ya fue registrado!!");
		  $ls_codtie='';
		}
	}
?>
		<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <table width="536" height="275" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="534" height="273"><div align="center">
          <table width="596"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td colspan="2" class="titulo-ventana">Unidad Operativa de Suministro</td>
            </tr>
            <tr>
              <td ><input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">
                  <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_hidstatus?>">
                  <input name="hidcodpai" type="hidden" id="hidcodpai" value="<?php print $ls_codpai?>">
                  <input name="hidcodest" type="hidden" id="hidcodest">
                  <input name="hidcodmun" type="hidden" id="hidcodmun">
                  <input name="hidcodpar" type="hidden" id="hidcodpar"></td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td width="205" height="22" align="right"><span class="style2">Codigo </span></td>
              <td width="387" ><input name="txtcodtie" type="text" id="txtcodtie" onKeyPress="return(validaCajas(this,'i',event,254))" value="<?php if ($ls_codtie!='')
				{print $io_function->uf_cerosizquierda($ls_codtie,4);}?>" size="5" maxlength="4" onBlur="ue_validar()">
              <input name="estatus" type="hidden" id="estatus" value="<?php print $ls_estatus;?>"></td>
            </tr>
            <tr>
              <td width="205" height="22" align="right">Razon Social </td>
              <td width="387" ><input name="txtdentie" type="text" id="txtdentie"  onKeyPress="return(validaCajas(this,'x',event,50))"  value="<?php print  $ls_dentie?>" size="50" maxlength="225" >              </td>
            </tr>
            <tr>
              <td height="22" align="right">RIF </td>
              <td ><input name="txtriftie" type="text" id="txtriftie" value="<?php print $ls_riftie ?>" size="25" maxlength="25"></td>
            </tr>
            <tr>
              <td height="22" align="right">Direcci&oacute;n</td>
              <td><textarea name="txtdirtie" onKeyDown="textCounter(this,254)" onKeyUp="textCounter(this,254)"  onKeyPress="return(validaCajas(this,'x',event,254))" cols="47" rows="2" id="txtdirtie" ><?php print $ls_dirtie?></textarea></td>
            </tr>
            <tr align="left">
              <td height="22" align="right"><span class="style2">Telefono</span></td>
              <td><input name="txtteltie" id="txtteltie"  onKeyPress="return validaCajas(this,'i',event)"  value="<?php print $ls_teltie?>" type="text" size="20" maxlength="20"></td>
            </tr>
            <tr>
              <td height="22" align="right">Estado</td>
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
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_desest</option>";
					 }
					}
	                ?>
                </select>
              </span></td>
            </tr>
            <tr>
              <td height="22" align="right">Municipio</td>
              <td><?Php
					$lb_valmun=false;
					if($ls_codest=="")
					{
						$lb_valmun=false;
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
						 else
						 {
							  print "<option value='$ls_codigo'>$ls_denmun</option>";
						 }
					}
	            ?>
                  </select>              </td>
            </tr>
            <tr>
              <td height="22" align="right">Parroquia</td>
              <td><?Php
				$lb_valpar=false;
			    if($ls_codmun=="")
					{
						$lb_valpar=false;
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
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_denpar</option>";
					 }
					}
	            ?>
                  </select>              </td>
            </tr>
            <tr>
              <td height="22"><div align="right">Tipo de Unidad de Suministro </div></td>
              <td><input name="codunisum" type="text" id="codunisum" value="<?php print  $ls_codtipunisum;?>" size="5" maxlength="4" readonly>
              <a href="javascript:ue_catunidadsuministro();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
              <input name="denunisum" type="text" id="denunisum" value="<?php print  $ls_dentipunisum;?>" size="40" readonly>
              </td>
            </tr>
            <tr>
              <td height="22"> <div align="right">Factura por Volumen </div></td>
              <td><table width="200">
                <tr>
                  <td class="Estilo1"><span class="Estilo2">
                    <label>
                    <input type="radio" name="rbfactvolumen" value="t" <?php print $lb_si_facporvol;?>>
                    </label>
                  </span>
                    <label>Si</label>
                  <span class="Estilo2">
                  <label>
                  <input type="radio" name="rbfactvolumen" value="f" <?php print $lb_no_facporvol;?>>
No</label>
                    </span></td>
                </tr>
                
              </table>
              </td>
            </tr>
            <tr>
              <td height="22"><div align="right">No. item facturas </div></td>
              <td><input name="txtitem" type="text" id="txtitem" value="<?php print $ls_item ?>" size="5" maxlength="5" onKeyPress="return validaCajas(this,'i',event)"></td>
		    </tr>

            <tr>
			<td height="8" colspan="2">			  </td>
		    </tr>
			  <tr>
              <td height="8" colspan="2"><table width="415" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td height="13" colspan="2" align="right" class="titulo-ventana">Cuenta Presupuestaria de Ingreso Asociada </td>
                  </tr>
                  <tr>
                    <td width="89" height="13" align="right">&nbsp;</td>
                    <td width="324" >&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="20" align="right">Codigo</td>
                    <td ><label>
                      <input name="txtspicuenta" type="text" id="txtspicuenta" value="<?php print $ls_spicuenta?>" size="25" maxlength="25" readonly="true">
                      </label>
                      <a href="javascript:ue_catctasspi();"> <img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></td>
                  </tr>
                  <tr>
                    <td height="22" align="right">Denominacion</td>
                    <td ><label>
                      <input name="txtdenospi" type="text" id="txtdenospi" value="<?php print $ls_denospi?>" size="55" maxlength="254" readonly="true">
                    </label></td>
                  </tr>
                </table>				</td>
			</tr>
				<tr>
				<td>				</td>
            </tr>
            <tr>
              <td height="8" colspan="2">			  </td>
			  <td width="2">
            <tr>
              <td height="8" colspan="2"><table width="415" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td height="13" colspan="2" align="right" class="titulo-ventana">Unidad Administrativa Ejecutora Asociada </td>
                  </tr>
                  <tr>
                    <td width="89" height="13" align="right">&nbsp;</td>
                    <td width="324" >&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="20" align="right">Codigo</td>
                    <td ><label>
                      <input name="txtunidadejecutora" type="text" id="txtunidadejecutora" value="<?php print $ls_codunidad?>" size="25" maxlength="10" readonly="true">
                      </label>
                      <a href="javascript:ue_catunidadejecutora();"> <img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></td>
                  </tr>
                  <tr>
                    <td height="22" align="right">Denominacion</td>
                    <td ><label>
                      <input name="txtdenunidad" type="text" id="txtdenunidad" value="<?php print $ls_denunidad?>" size="55" maxlength="100" readonly="true">
                    </label></td>
                  </tr>
                </table></td>
            </tr>
			<tr>
				<td>				</td>
            </tr>
            <tr>
              <td height="8" colspan="2">			  </td>
			  <td>
            <tr>
              <td height="8" colspan="2"><table width="415" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td height="13" colspan="2" align="right" class="titulo-ventana">Cuenta Presupuestaria de Egreso Asociada </td>
                  </tr>
                  <tr>
                    <td width="89" height="13" align="right">&nbsp;</td>
                    <td width="324" >&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="20" align="right">Codigo</td>
                    <td ><label>
                        <input name="txtcodestpro1" type="hidden" id="txtcodestpro1" value="<?php print $ls_codestpro1?>" size="25" maxlength="25" readonly="true">
                        <input name="txtcodestpro2" type="hidden" id="txtcodestpro2" value="<?php print $ls_codestpro2?>" size="25" maxlength="25" readonly="true">
                        <input name="txtcodestpro3" type="hidden" id="txtcodestpro3" value="<?php print $ls_codestpro3?>" size="25" maxlength="25" readonly="true">
                        <input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="<?php print $ls_codestpro4?>" size="25" maxlength="25" readonly="true">
                         <input name="txtcodestpro5" type="hidden" id="txtcodestpro5" value="<?php print $ls_codestpro5?>" size="25" maxlength="25" readonly="true">
                      <input name="txtcuentapre" type="hidden" id="txtcuentapre" value="<?php print $ls_cuentapre?>" size="25" maxlength="25" readonly="true">

                      </label>
                      <a href="javascript:ue_catcuentapre();"> <img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></td>
                  </tr>
                  <tr>
                    <td height="22" align="right">Denominacion</td>
                    <td ><label>
                      <input name="txtdencuentapre" type="text" id="txtdencuentapre" value="<?php print $ls_dencuentapre?>" size="55" maxlength="100" readonly="true">
                    </label></td>
                  </tr>
                </table></td>
            </tr>
          </table>
      </tr>
      <tr class="formato-blanco">
        <td colspan="9"><div align="center">
          <table width="609" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr class="formato-blanco">
              <td width="14" height="11">&nbsp;</td>
              <td width="593">
				  <a href="javascript:ue_catcuenta();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catcuenta();">Cargar Ctas. Contables </a>
		      </td>
            </tr>
            <tr align="center" class="formato-blanco">
              <td height="11" colspan="2"><?php $io_grid->makegrid($li_filascuentas,$la_columcuenta,$la_objectcuenta,$li_anchocuenta,$ls_titulocuenta,$ls_nametable);?>              </td>
              <input name="filascuentas" type="hidden" id="filascuentas" value="<? print $li_filascuentas;?>">
			  <input name="hidremovercuenta" type="hidden" id="hidremovercuenta" value="">
            </tr>
            <tr class="formato-blanco">
              <td colspan="2">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>

			 </td>
  </table>



    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
</body>

<script language="JavaScript">

/**********************************************************************************************************************************/
function ue_catctasspi()
{
    f=document.form1;
	ls_estatus=f.estatus.value;
	if(ls_estatus=='t')
	{	
		f.operacion.value="ue_cargar_ctasspi";
		f.action="sigesp_sfc_d_tienda.php";
		f.submit();
	}
	else
	{
		alert("La tienda esta en estatus inactiva, no puede editar el registro");
	}
}
function ue_cargarctasspi(cod,deno)
{
	f=document.form1;
	f.txtspicuenta.value=cod;
    f.txtdenospi.value=deno;
}
function ue_catunidadejecutora()
{
    f=document.form1;
	/*f.operacion.value="ue_cargar_unidadejecutora";
	f.action="sigesp_sfc_d_tienda.php";*/
	ls_estatus=f.estatus.value;
	if(ls_estatus=='t')
	{	
		f.operacion.value="";
		pagina="sigesp_cat_unidadejecutora.php";
		popupWin(pagina,"catalogo",600,250);
	}	
	else
	{
		alert("La tienda esta en estatus inactiva, no puede editar el registro");
	}
	//f.submit();
}
function ue_cargar_unidadejecutora(cod,deno)
{
	f=document.form1;
	f.txtunidadejecutora.value=cod;
    f.txtdenunidad.value=deno;
}
function ue_catcuenta()
{
    f=document.form1;
	ls_estatus=f.estatus.value;
	if(ls_estatus=='t')
	{	
		f.operacion.value="";
		codtiend=f.txtcodtie.value;
		f.operacion.value='ue_cargarcuentastiendas';
		f.action="sigesp_sfc_d_tienda.php";
		f.submit();
	}
	else
	{
		alert("La tienda esta en estatus inactiva, no puede editar el registro");
	}	
}

function ue_catcuentapre()
{
    f=document.form1;
	ls_estatus=f.estatus.value;
	if(ls_estatus=='t')
	{	
		f.operacion.value="ue_cargar_cuentapre";
		f.action="sigesp_sfc_d_tienda.php";
		f.submit();
	}
	else
	{
		alert("La tienda esta en estatus inactiva, no puede editar el registro");
	}
}
function ue_removercuenta(li_fila)
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	ls_estatus=f.estatus.value;
	if(ls_estatus=='t')
	{	
		if(li_eliminar==1)
		{
		f.hidremovercuenta.value=li_fila;
		f.operacion.value="ue_removercuenta";
		f.action="sigesp_sfc_d_tienda.php";
		f.submit();
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("La tienda esta en estatus inactiva, no puede editar el registro");
	}
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		f.operacion.value="ue_nuevo";
		f.hidcodpai.value="058";
		f.txtcodtie.value="";
		f.txtdentie.value="";
		f.txtteltie.value="";
		f.txtdirtie.value="";
		f.txtitem.value="";
		f.txtspicuenta.value="";
		f.action="sigesp_sfc_d_tienda.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	ls_estatus=f.estatus.value;
	if(ls_estatus=='t')
	{	
		if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
		{
			if (lb_status!="C")
			{
			f.hidstatus.value="C";
			}
			with(f)
			{
				if (ue_valida_null(txtcodtie,"Codigo")==false)
				{
				 txtcodtie.focus();
				}
				else
				{
					if (ue_valida_null(txtdentie,"Razon Social")==false)
					{
					 txtdentie.focus();
					}
					else
					{
						if (ue_valida_null(txtdirtie,"Direccion")==false)
						{
							txtdirtie.focus();
						}
						else
						{
							if (ue_valida_null(txtriftie,"RIF")==false)
							{
							  txtriftie.focus();
							}
							else
							{
								 if (ue_valida_null(cmbestado,"Estado")==false)
								 {
									cmbestado.focus();
								 }
								 else
								 {
									if (ue_valida_null(cmbmunicipio,"Municipio")==false)
									 {
									  cmbmunicipio.focus();
									 }
									 else
									 {
										if (ue_valida_null(cmbparroquia,"Parroquia")==false)
										{
										  cmbparroquia.focus();
										}
										else
										{
											if (ue_valida_null(txtitem,"item")==false)
											{
											   txtitem.focus();
											}
											else
											{
												if (ue_valida_null(txtspicuenta,"cuenta")==false)
												{
												  txtspicuenta.focus();
												}
												else
												if (ue_valida_null(txtunidadejecutora,"Unidad Ejecutora")==false)
												{
												  txtunidadejecutora.focus();
												}
												else
												{
													 if (ue_valida_null(codunisum,"tipo de unidad de suministro")==false)
													 {
													  codunisum.focus();
													 }
													 else
													 {	   
														   if (ue_valida_null(txtcuentapre,"cuenta presupuestaria de egreso")==false)
														   {
															  txtcuentapre.focus();
														   }
														   else
														   {
															   f.operacion.value="ue_guardar";
															   f.action="sigesp_sfc_d_tienda.php";
															   f.submit();
														   }
													 }	   
												 }

											}
										}
									 }
								 }
							 }
						 }
					 }
				}
			 }
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("La tienda esta en estatus inactiva, no puede editar el registro");
	}	
}

function ue_eliminar()
{
	f=document.form1;
	ls_estatus=f.estatus.value;
	if(ls_estatus=='t')
	{	
		li_eliminar=f.eliminar.value;
		if(li_eliminar==1)
		{
		if (f.txtcodtie.value=="")
		   {
			 alert("No ha seleccionado ningún registro para eliminar !!!");
		   }
			else
			{
			 if (confirm("¿Esta seguro de eliminar este registro?"))
				   {
					 f=document.form1;
					 f.operacion.value="ue_eliminar";
					 f.action="sigesp_sfc_d_tienda.php";
					 f.submit();
				   }
				else
				   {
					 f=document.form1;
					 f.action="sigesp_sfc_d_tienda.php";
					 alert("Eliminación Cancelada !!!");
					 f.txtcodtie.value="";
					 f.txtdentie.value="";
					 f.txtdirtie.value="";
					 f.txtteltie.value="";
					 f.txtriftie.value="";
					 f.submit();
				   }
			}

		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("La tienda esta en estatus inactiva, no puede editar el registro");
	}
}


function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		f.operacion.value="";
		pagina="sigesp_cat_tienda.php";
		popupWin(pagina,"catalogo",600,250);
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

/***********************************************************************************************************************************/

function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,
items,spi_cuenta,denominacion,codunidad,denunidad,cuentapre,denpre,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,facporvol,codtipundopesum,dentipundopesum,estatus)
{
	f=document.form1;
	f.hidstatus.value="C";
	f.txtcodtie.value=codtie;
	f.txtdentie.value=nomtie;
	f.txtteltie.value=teltie;
	f.txtdirtie.value=dirtie;
	f.txtriftie.value=riftie;
	f.txtitem.value=items;
	f.txtunidadejecutora.value=codunidad;
	f.txtdenunidad.value=denunidad;
	f.txtdenospi.value=denominacion;
	f.hidcodpai.value=codpai;
	f.hidcodest.value=codest;
	f.hidcodmun.value=codmun;
	f.hidcodpar.value=codpar;
	f.operacion.value="ue_cargarcuenta_tienda";
	f.txtspicuenta.value=spi_cuenta;
	f.txtcodestpro1.value=codestpro1;
	f.txtcodestpro2.value=codestpro2;
	f.txtcodestpro3.value=codestpro3;
	f.txtcodestpro4.value=codestpro4;
	f.txtcodestpro5.value=codestpro5;
	f.txtcuentapre.value=cuentapre;
	f.txtdencuentapre.value=denpre;
	f.codunisum.value=codtipundopesum;
	f.denunisum.value=dentipundopesum;
	if(facporvol=='t')
	{
		f.rbfactvolumen[0].checked=true;
	}
	else
	{
		f.rbfactvolumen[1].checked=true;
	}
	f.estatus.value=estatus;
	f.submit();

}

/***********************************************************************************************************************************/

function EvaluateText(cadena, obj)
{
	opc = false;

	if (cadena == "%d")
	  if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))
	  opc = true;
	if (cadena == "%f"){
	 if (event.keyCode > 47 && event.keyCode < 58)
	  opc = true;
	 if (obj.value.search("[.*]") == -1 && obj.value.length != 0)
	  if (event.keyCode == 46)
	   opc = true;
	}
	 if (cadena == "%s") // toma numero y letras
		 if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46))
		  opc = true;
	 if (cadena == "%c") // toma numero y punto
		 if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==46))
			opc = true;
	 if(opc == false)
	   event.returnValue = false;
}

function ue_llenarcmb()
{
	f=document.form1;
	ls_estatus=f.estatus.value;
	if(ls_estatus=='t')
	{	
		f.action="sigesp_sfc_d_tienda.php";
		f.operacion.value="";
		f.submit();
	}
	else
	{
		alert("La tienda esta en estatus inactiva, no puede editar el registro");
	}
}

function ue_validar()
{
	f=document.form1;
	ls_estatus=f.estatus.value;
	if(ls_estatus=='t')
	{	
		f.action="sigesp_sfc_d_tienda.php";
		f.operacion.value="ue_validar";
		f.submit();
	}
	else
	{
		alert("La tienda esta en estatus inactiva, no puede editar el registro");
	}
}
		
function ue_catunidadsuministro()
{
    f=document.form1;
	ls_estatus=f.estatus.value;
	if(ls_estatus=='t')
	{	
		f.operacion.value="";
		pagina="sigesp_cat_tipo_unidad_suministro.php";
		popupWin(pagina,"catalogo",600,250);
	}
	else
	{
		alert("La tienda esta en estatus inactiva, no puede editar el registro");
	}
}
</script>
</html>