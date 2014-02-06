<?Php
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
<title>Definici&oacute;n de Clientes</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
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
.style6 {color: #000000}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699" onLoad="">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >

</table>
<?Php
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_cliente.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_permisos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
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
    require_once("../shared/class_folder/class_sql.php");
	require_once("class_folder/sigesp_sob_c_funciones_sob.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/evaluate_formula.php");
	require_once("../shared/class_folder/class_funciones_db.php");
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("class_folder/sigesp_sfc_c_secuencia.php");
	require_once("class_folder/sigesp_sfc_c_cliente.php");
	require_once("class_folder/sigesp_sfc_c_productor.php");
	require_once("class_folder/sigesp_sfc_class_utilidades.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");

	$io_include=new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$io_sql= new class_sql($io_connect);
	$io_funcdb=new class_funciones_db($io_connect);
	$io_secuencia=new sigesp_sfc_c_secuencia();
	$io_funcsob=new sigesp_sob_c_funciones_sob();
	$io_evalform=new evaluate_formula();
	$io_grid=new grid_param();
	$is_msg=new class_mensajes();
	$io_datastore= new class_datastore();
    $io_function=new class_funciones();
	$io_cliente = new sigesp_sfc_c_cliente();
	$io_productor = new sigesp_sfc_c_productor();
	$io_utilidad = new sigesp_sfc_class_utilidades();

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_cedcli=$_POST["txtcedcli"];
		$ls_codcli=$_POST["hidcodcli"];
		$ls_codcla=$_POST["hidcodcli"];
	$ls_codcla=$io_funcdb->uf_generar_codigo(false,"","sfc_cliente","codcli",100); // correlativo incrementa automaticamente

	if ($ls_codcla==""){
	$ls_codcla=1;
	}
		$ls_nomcli=$_POST["txtnomcli"];
		$ls_dircli=$_POST["txtdircli"];
		$ls_telcli=$_POST["txttelcli"];
		$ls_celcli=$_POST["txtcelcli"];
		$ls_codest=$_POST["cmbestado"];
		$ls_codmun=$_POST["cmbmunicipio"];
		$ls_codpar=$_POST["cmbparroquia"];
		$ls_precioestandar=$_POST["cmbprecioestandar"];
		$ls_tentierra=$_POST["cmbtentierra"];
		$ls_codpai=$_POST["hidcodpai"];
		$ls_nrohect=$_POST["txtnrohect"];
		$hectprod=$_POST["hectprod"];
		$ls_nrohectsinprod=$_POST["txthectsinprod"];
		$ls_hidsta=$_POST["hidsta"];
		$ls_cedcliaux=$_POST["hidcedcli"];
		$ls_tipcli=$_POST["cmbtipcli"];
		if($_POST["txtnrocartagr"]=="")
		{
		$ls_nrocartagr=$_POST["txtnrocartagr"];
		}else{
		$ls_nrocartagr=$io_function->uf_cerosizquierda($_POST["txtnrocartagr"],'25');
		}
		$ls_productor=$_POST["txtproductor"];
	}

	else
	{
		$ls_operacion="";
		$ls_codcli="0";
		$ls_codcla=1;
		$ls_cedcli="";
		$ls_nomcli="";
		$ls_dircli="";
		$ls_telcli="";
		$ls_celcli="";
		$ls_codest="";
		$ls_codmun="";
		$ls_codpar="";
		$ls_codpai="";
		$ls_precioestandar="";
		$ls_tentierra="";
		$ls_productor="NO";
		$ls_nrohect="0,00";
		$hectprod="0,00";
		$ls_nrohectsinprod="0,00";
		$ls_nrocartagr="";
		$ls_hidsta="";
		$ls_cedcliaux=$_GET["cedcli"];
		$ls_cedcli2=$_GET["cedcli"];
		if($ls_cedcli2!="")
         {
		   $ls_tipcli=substr($ls_cedcli2,0,1);
		   $ls_cedcli=substr($ls_cedcli2,1,strlen($ls_cedcli2));
		   $ls_operacion="ue_nuevo";

         }


	}

	if($ls_operacion=="ue_nuevo")
	{
	$io_secuencia->uf_obtener_secuencia_cliente(sfc_cliente_codcli_seq,$ls_codcla2); // correlativo incrementa automaticamente
	//$ls_codcla=$io_funcdb->uf_generar_codigo(false,"","sfc_cliente","codcli",100); // correlativo incrementa automaticamente
	if ($ls_codcla2==""){
	$ls_codcla2=1;
	}
	$ls_codcli=$ls_codcla2;


	$ls_cedcliaux=$_GET["cedcli"];
		$ls_cedcli2=$_GET["cedcli"];
		if($ls_cedcli2!="")
         {
		 $ls_codpai="058";
		 $ls_tipcli=substr($ls_cedcli2,0,1);
         $ls_cedcli=substr($ls_cedcli2,1,strlen($ls_cedcli2));
		 $ls_hidsta="V";
		 }
		else
		{
		 }
		$ls_nomcli="";
		$ls_dircli="";
		$ls_telcli="";
		$ls_celcli="";
		$ls_codest="";
		$ls_codmun="";
		$ls_codpar="";
		$ls_precioestandar="PV";
		$ls_tentierra="";
		$ls_productor="NO";
		$ls_nrohect="0,00";
		$ls_nrocartagr="";
		$hectprod="0,00";
		$ls_nrohectsinprod="0,00";
		}
	elseif($ls_operacion=="ue_guardar")
	{
	$io_secuencia->uf_obtener_secuencia_cliente(sfc_cliente_codcli_seq,$ls_codcla2); // correlativo incrementa automaticamente
	//$ls_codcla=$io_funcdb->uf_generar_codigo(false,"","sfc_cliente","codcli",100); // correlativo incrementa automaticamente
	if ($ls_codcla2==""){
	$ls_codcla2=1;
	}
	$ls_codcli=$ls_codcla2;
	$lb_valido=$io_cliente->uf_guardar_cliente($ls_codcli,$ls_tipcli.$ls_cedcli,$ls_nomcli,$ls_dircli,$ls_telcli,$ls_celcli,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_precioestandar,$ls_productor,$la_seguridad);
	$ls_cadena=" SELECT * ".
			" FROM sfc_cliente ".
			" WHERE razcli ilike '".$ls_nomcli."'";

			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni))
				{
					$la_tienda=$io_sql->obtener_datos($rs_datauni);
					$io_datastore->data=$la_tienda;
					$totrow=$io_datastore->getRowCount("codcli");

					for($z=1;$z<=$totrow;$z++)
					{
						$ls_codcli=$io_datastore->getValue("codcli",$z);

						}
				}
				}

		$ls_mensaje=$io_cliente->io_msgc;
		if($lb_valido==true)
		{
			$is_msg->message ($ls_mensaje);

		}
		else
		{
			if($lb_valido==0)
			{

			}
			else
			{
				$is_msg->message ($ls_mensaje);
			}
		}

	if($ls_hidsta=="V")
	  {
	   ?>
	    <script language="javascript">
		var codcli="<?php print $ls_codcli;?>";
		var cedcli="<?php print $ls_cedcli; ?>";
		var nomcli="<?php print $ls_nomcli; ?>";
		var tipcli="<?php print $ls_tipcli.$ls_cedcli;?>";

		opener.ue_cargarcliente(codcli,tipcli,nomcli,"","","","","","","");
		close();
		</script>
	   <?php
		}
	}
	elseif($ls_operacion=="ue_validar")
	{
	$ls_sql="SELECT * FROM sfc_cliente WHERE codemp='".$la_datemp["codemp"]."' AND cedcli='".$ls_tipcli.$ls_cedcli."';";
	    $lb_valido=$io_utilidad->uf_datacombo($ls_sql,&$la_cliente);
		if ($lb_valido==true)
		{
		  $is_msg->message ("El numero de cedula fue registrado para otro cliente!!");
		  $io_datastore->data=$la_cliente;
		  $ls_cedcli=$io_datastore->getValue("cedcli",1);
		  $ls_tipcli=substr($ls_cedcli,0,1);
		  $ls_cedcliaux=$ls_cedcli;
		  $ls_cedcli=substr($ls_cedcli,1,strlen($ls_cedcli));
		  $ls_codcli=$io_datastore->getValue("codcli",1);
		  $ls_nomcli=$io_datastore->getValue("razcli",1);
		  $ls_dircli=$io_datastore->getValue("dircli",1);
		  $ls_telcli=$io_datastore->getValue("telcli",1);
		  $ls_celcli=$io_datastore->getValue("celcli",1);
		  $ls_codpai=$io_datastore->getValue("codpai",1);
		  $ls_codest=$io_datastore->getValue("codest",1);
		  $ls_codmun=$io_datastore->getValue("codmun",1);
		  $ls_codpar=$io_datastore->getValue("codpar",1);
		  $ls_precioestandar=$io_datastore->getValue("precio_estandar",1);
		  $ls_productor=$io_datastore->getValue("productor",1);
		  $ls_tentierra=$io_datastore->getValue("tentierra",1);
		}
	else{

	$ls_codcla=$io_funcdb->uf_generar_codigo(false,"","sfc_cliente","codcli",100); // correlativo incrementa automaticamente
	if ($ls_codcla==""){
	$ls_codcla=1;
		}
	}

}

elseif($ls_operacion=="ue_cargarcliente")
	{
	  $ls_sql="SELECT *
                   FROM sfc_cliente
                  WHERE codemp='".$la_datemp["codemp"]."' AND codcli='".$ls_codcli."'";
	    $lb_valido=$io_utilidad->uf_datacombo($ls_sql,&$la_cliente);
		if ($lb_valido==true)
		{
		  $io_datastore->data=$la_cliente;
		  $ls_cedcli=$io_datastore->getValue("cedcli",1);
		  $ls_tipcli=substr($ls_cedcli,0,1);
		  $ls_cedcliaux=$ls_cedcli;
		  $ls_cedcli=substr($ls_cedcli,1,strlen($ls_cedcli));
		  $ls_codcli=$io_datastore->getValue("codcli",1);
		  $ls_nomcli=$io_datastore->getValue("razcli",1);
		  $ls_dircli=$io_datastore->getValue("dircli",1);
		  $ls_telcli=$io_datastore->getValue("telcli",1);
		  $ls_celcli=$io_datastore->getValue("celcli",1);
		  $ls_codpai=$io_datastore->getValue("codpai",1);
		  $ls_codest=$io_datastore->getValue("codest",1);
		  $ls_codmun=$io_datastore->getValue("codmun",1);
		  $ls_codpar=$io_datastore->getValue("codpar",1);
		  $ls_precioestandar=$io_datastore->getValue("precio_venta",1);
		  $ls_productor=$io_datastore->getValue("productor",1);
		  $ls_tentierra=$io_datastore->getValue("tentierra",1);

			}
		}

?>
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
    <table width="200" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
<tr>
				<td height="20" bgcolor="#CCCCCC" class="toolbar"><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0">Guardar</a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0">Cancelar</a></td>
			</tr>
      <tr>
        <td width="200" height="258"><div align="center">
			<table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
             <tr>
				<td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
			 </tr>
			<tr>
                <td colspan="2" class="titulo-ventana">Datos del Cliente </td>
            </tr>
            <tr>
                <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
				<input name="hidstatus" type="hidden" id="hidstatus">
				<input name="hidsta" type="hidden" id="hidsta" value="<? print $ls_hidsta?>">
				<input name="hidcodpai" type="hidden" id="hidcodpai" value="<? print $ls_codpai?>">
				<input name="hidcodest" type="hidden" id="hidcodest" value="<? print $ls_codest?>">
				<input name="hidcodmun" type="hidden" id="hidcodmun" value="<? print $ls_codmun?>">
				<input name="hidcodpar" type="hidden" id="hidcodpar" value="<? print $ls_codpar?>">
				<input name="hidprecioestandar" type="hidden" id="hidprecioestandar" value="<?php print $ls_precioestandar ?>">
				<input name="hidtentierra" type="hidden" id="hidtentierra" value="<?php print $ls_tentierra ?>">
				<input name="hidcodcli" type="hidden" id="hidcodcli" value="<? print $ls_codcli?>">
				<input name="hidcedcli" type="hidden" id="hidcedcli" value="<? print $ls_tipcli?>">
			</tr>
			<tr>
                <td width="151" height="22" align="right"><span class="style2">Cedula o RIF </span></td>
                <td width="629" >

				<input name="cmbtipcli" type="text" id="cmbtipcli" value="<? print $ls_tipcli?>"  size="3" maxlength="3" readonly="true">
	                <input name="txtcedcli" type="text" id="txtcedcli" onKeyPress="return validaCajas(this,'a',event)" value="<? print $ls_cedcli;?>" size="15" maxlength="10" onBlur="ue_validar()" readonly="true" >
				</td>
            </tr>
            <tr>
                <td width="151" height="22" align="right">Razon Social </td>
                <td width="629" ><input name="txtnomcli" type="text" id="txtnomcli"  onKeyPress="return(validaCajas(this,'x',event,50))"  value="<? print  $ls_nomcli?>" size="50" maxlength="225" ></td>
            </tr>
            <tr>
                <td height="4" align="right">Direcci&oacute;n</td>
                <td><textarea name="txtdircli" onKeyDown=" textCounter(this,254)" onKeyUp="textCounter(this,254)"  onKeyPress="return(validaCajas(this,'x',event,254))" cols="47" rows="2" id="txtdircli" ><? print $ls_dircli?></textarea></td>
            </tr>
            <tr align="left">
                <td height="22" align="right"><span class="style2">Telefono Fijo </span></td>
                <td><input name="txttelcli" id="txttelcli"    value="<? print $ls_telcli?>" type="text" size="20" maxlength="20"></td>
            </tr>
            <tr align="left">
                <td height="22" align="right"><span class="style2">Telefono</span> Movil </td>
                <td><input name="txtcelcli" id="txtcelcli"   value="<? print $ls_celcli?>" type="text" size="20" maxlength="20"></td>
            </tr>
            <tr>
                <td height="24" align="right">Estado</td>
                <td><span class="style6">
                  <?Php

				   if($ls_codpai=="")
				    {
						$lb_valest=false;
					}
					else
					{
				       $ls_sql="SELECT codest ,desest FROM sigesp_estados
					   WHERE codpai='".$ls_codpai."' ORDER BY codest ASC";
				       $lb_valest=$io_utilidad->uf_datacombo($ls_sql,$la_estado);
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
                <td height="24" align="right">Municipio</td>
                <td> <span class="style6"><?Php
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
                  </select>
				  </span>
				</td>
            </tr>
            <tr>
                <td height="24" align="right">Parroquia</td>
                <td>
				<span class="style6">
				<?Php
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
                  </select>
				  </span>
				  </td>
            </tr>
            <tr>
               <td height="24" align="right">Precio Estandar</td>
                <td colspan="3" ><span class="style6">
                  <select name="cmbprecioestandar" size="1" id="cmbprecioestandar">
                    <?php
				    if($ls_precioestandar=="")
					 {
				   ?>
                    <option value="" selected>Seleccione Una</option>
                    <option value="PV">Precio de Venta</option>
                    <option value="PU">Precio de Venta 1</option>
					<option value="PD">Precio de Venta 2</option>
					<option value="PT">Precio de Venta 3</option>
                    <?php
					 }
					 elseif($ls_precioestandar=="PV")
					 {
					?>
                    <option value="PV" selected>Precio de Venta</option>
                    <option value="PU">Precio de Venta 1</option>
					<option value="PD">Precio de Venta 2</option>
					<option value="PT">Precio de Venta 3</option>
                    <?php
					 }
					 elseif($ls_precioestandar=="PU")
					 {
					?>
                    <option value="PV">Precio de Venta</option>
                    <option value="PU" selected>Precio de Venta 1</option>
					<option value="PD">Precio de Venta 2</option>
					<option value="PT">Precio de Venta 3</option>
                    <?php
					}
					elseif ($ls_precioestandar=="PD")
					{
					 ?>
                    <option value="PV">Precio de Venta</option>
                    <option value="PU">Precio de Venta 1</option>
					<option value="PD" selected>Precio de Venta 2</option>
					<option value="PT">Precio de Venta 3</option>
					 <?php
					 }
					 elseif ($ls_precioestandar=="PT")
					{
					?>
                    <option value="PV">Precio de Venta</option>
                    <option value="PU">Precio de Venta 1</option>
					<option value="PD">Precio de Venta 2</option>
					<option value="PT" selected>Precio de Venta 3</option>
					<?php
					}
					?>
                  </select>
                </span></td>
            </tr>
			 <tr>
                <td height="24" align="right">productor</td>
                <td><label>
				<?PHP
				if ($ls_productor=="NO")
				  {
				  ?>
				  <input name="txtproductor" type="text" id="txtproductor" value="NO" readonly>
				<!--<input name="check1" type="checkbox" id="check1" value="F" >-->
				<img src="../shared/imagebank/tools20/aprobado.gif" width="22" height="22" border="0">Datos del Productor
				<?php
				  } else if ($ls_cedcli=="")
				  {
				  ?>
				   <input name="txtproductor" type="text" id="txtproductor" value="NO" readonly>
				 <!--<input name="check1" type="checkbox" id="check1" value="F" >-->
				<img src="../shared/imagebank/tools20/aprobado.gif" width="22" height="22" border="0">Datos del Productor
				<?php
				}
				?>
                </label></td>
              </tr>
    </table>
	</td>
	</tr>
	</table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
</body>

<script language="JavaScript">

/***********************************************************************************************************************************/

function ue_nuevo()
{
			f=document.form1;
			f.hidcodcli.value="0";
		    f.operacion.value="ue_nuevo";
			f.hidcodpai.value="058";
			f.hidprecioestandar.value="PV";
			f.txtcedcli.value="";
			f.txtnomcli.value="";
			f.txttelcli.value="";
			f.txtcelcli.value="";
			f.txtdircli.value="";
			f.txtproductor.value="NO";
			f.action="sigesp_cat_clientecotizacion.php";
			f.submit();

}
function ue_guardar()
{

f=document.form1;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status=f.hidstatus.value;
 with(f)
	 {
		  if (ue_valida_null(txtcedcli,"Cedula o RIF")==false)
		   {
			 txtcodcli.focus();
		   }
		   else
		   {
			if (ue_valida_null(txtnomcli,"Razon Social")==false)
				 {
				  txtnomcli.focus();
				 }
			else
			{
					  if (ue_valida_null(txtdircli,"Direccion")==false)
					   {
						txtdircli.focus();
					   }
					   else{
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
							else{
							if (ue_valida_null(cmbparroquia,"Parroquia")==false)
							{
							cmbparroquia.focus();
							}
							else
							{
							 f.operacion.value="ue_guardar";
							 f.action="sigesp_cat_clientecotizacion.php";
							 f.submit();
							 }
							 }
						   }
					   }

			}
		}
	  }
	}

if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
{
}
else
{
	alert("No tiene permiso para realizar esta operacion");
}


/*******************************************************************************************************************************/

function ue_cargarcliente(codcli,cedcli,nomcli,dircli,telcli,celcli,codpai,codest,codmun,codpar,productor,precioestandar,tentierra)
{
			f=document.form1;
			//f.hidstatus.value="C"
			f.hidcodcli.value=codcli;
			f.txtcedcli.value=cedcli;
            f.txtnomcli.value=nomcli;
            f.txttelcli.value=telcli;
            f.txtdircli.value=dircli;
            f.txtcelcli.value=celcli;
            f.hidcodpai.value=codpai;
            f.hidcodest.value=codest;
			f.hidcodmun.value=codmun;
			f.hidcodpar.value=codpar;
			f.hidprecioestandar.value=precioestandar;
			f.hidtentierra.value=tentierra;
			if (productor=="V")
			 {
			  f.txtproductor.value=productor;
			  /*f.check1.checked=true;
			  f.operacion.value="";	*/
			 }
			 else
			 {
			  f.txtproductor.value=productor;
			/*  f.check1.checked=false;*/
			  }

			f.operacion.value="ue_cargarcliente";
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
	        f.action="sigesp_cat_clientecotizacion.php";
	        f.operacion.value="";
			f.hidcodpai.value="058";
	        f.submit();
        }

function ue_validar()
        {
	        f=document.form1;
			f.action="sigesp_cat_clientecotizacion.php";
	        f.operacion.value="ue_validar";
	        f.submit();
        }

/********************************************************************************************************************************
*************************************************  VALIDACIONES DE CAJAS DE TEXTO  ********************************************
*********************************************************************************************************************************/
function currencyFormat(fld, milSep, decSep, e)
 {
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13) return true; // Enter
    if (whichCode == 8) return true; // Backspace <-
    if (whichCode == 127) return true; // Suprimir -Del
    key = String.fromCharCode(whichCode); // Get key value from key code
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key
    len = fld.value.length;
    for(i = 0; i < len; i++)
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;
    aux = '';
    for(; i < len; i++)
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0) fld.value = '';
    if (len == 1) fld.value = '0'+ decSep + '0' + aux;
    if (len == 2) fld.value = '0'+ decSep + aux;
    if (len > 2)
	{
     aux2 = '';
     for (j = 0, i = len - 3; i >= 0; i--)
	 {
      if (j == 3)
	  {
       aux2 += milSep;
       j = 0;
      }
      aux2 += aux.charAt(i);
      j++;
     }

     fld.value = '';
     len2 = aux2.length;
     for (i = len2 - 1; i >= 0; i--)
      fld.value += aux2.charAt(i);

     fld.value += decSep + aux.substr(len - 2, len);
    }
	return false;
}
</script>
</html>