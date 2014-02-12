<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
     print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_orden_pago_directo.php",$ls_permisos,&$la_seguridad,$la_permisos);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Otros Cr&eacute;ditos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<style type="text/css">
<!--
.Estilo1 {font-size: 36px}
-->
</style>
</head>
<body>
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg		= new class_mensajes();
$io_sql		= new class_sql($ls_conect);
$io_funcion = new class_funciones(); 
$grid		= new grid_param();
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

require_once("sigesp_scb_c_ordenpago.php");
$in_classmovorden=new sigesp_scb_c_ordenpago($la_seguridad);

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_opener   =$_POST["opener"];
    $li_fila     =$_POST["total"];  
	$ldec_objret=$_POST["objret"];
	$ls_procede=$_POST["txtprocede"];
	$ls_mov_document=$_POST["mov_document"];
	$ls_mov_procede=$_POST["procede"];
	$ld_fecha=$_POST["fecha"];
	$ls_provbene=$_POST["provbene"];
	$ls_tipo=$_POST["tipo"];
	$ls_mov_descripcion=$_POST["descripcion"];
	$ls_codban=$_POST["codban"];
	$ls_ctaban=$_POST["ctaban"];
	$ls_cuenta_scg=$_POST["cuenta_scg"];
	$ls_codope=$_POST["mov_operacion"];
	$ldec_monto_mov=$_POST["monto"];
	$ldec_objret=$_POST["objret"];
	$ldec_retenido=$_POST["txttotal"];
	$ldec_retenido=str_replace(".","",$ldec_retenido);
	$ldec_retenido=str_replace(",",".",$ldec_retenido);
	$ls_chevau=$_POST["chevau"];
	$li_estint=$_POST["estint"];
	$li_cobrapaga=$_POST["cobrapaga"];
	$ls_nomproben=$_POST["txtnomproben"];
	$ls_estbpd=$_POST["estbpd"];
	$ls_estmov=$_POST["estmov"];
	$ls_codconmov=$_POST["codconmov"];
	$ls_estreglib=$_POST["tip_mov"];
	$ls_estdoc   =$_POST["estdoc"];
	$ls_tipdocres=$_POST["tipdocres"];
	$ls_numdocres=$_POST["numdocres"];
	$ls_fecdocres=$_POST["fecdocres"];
	$ls_tipreg   =$_POST["tipreg"];
	$ls_fte_financiamiento=$_POST["ftefinancia"];
	$ls_origen=$_POST["origen"];
	$ls_coduniadm=$_POST["coduniadm"];
	$ls_uel=$_POST["coduniadm"];
	$ls_estuac=$_POST["estuac"];
	$ls_tippag=$_POST["tippag"];
	$ls_mediopago=$_POST["mediopago"];
	$ls_modalidad=$_POST["modalidad"];
	$ls_codbansig=$_POST["codbansig"];
    $ls_codestpro1=$_POST["codestpro1"];
	$ls_nombreaut=$_POST["nombreaut"];
	$ls_codbanaut=$_POST["codbanaut"];
	$ls_nombanaut=$_POST["nombanaut"];
	$ls_rifaut   =$_POST["rifaut"]; 
	$ls_ctabanaut=$_POST["ctabanaut"];
	$ls_codbanbene=$_POST["codbanbene"];
	$ls_ctabanbene=$_POST["ctabanbene"];
	$ls_nombanbene=$_POST["nombanbene"];
	$ls_nrocontrol=$_POST["nrocontrol"];
	$li_estserext = $_POST["hidserext"];	
}
else
{
	$ls_operacion="";	
	$ls_documento=$_GET["mov_document"];
	$ls_procede=$_GET["txtprocedencia"];
	$ls_opener   =$_GET["opener"];
	$ls_mov_document=$_GET["mov_document"];
	$ls_mov_procede=$_GET["procede"];
	$ld_fecha=$_GET["fecha"];
	$ls_provbene=$_GET["provbene"];
	$ls_tipo=$_GET["tipo"];
	$ls_mov_descripcion=$_GET["descripcion"];
	$ls_codban=$_GET["codban"];
	$ls_ctaban=$_GET["ctaban"];
	$ls_cuenta_scg=$_GET["cuenta_scg"];
	$ls_codope=$_GET["mov_operacion"];
	$ldec_monto_mov=$_GET["monto"];
	$ldec_objret=$_GET["objret"];
	$ldec_retenido=$_GET["retenido"];
	$ls_chevau=$_GET["chevau"];
	$li_estint=$_GET["estint"];
	$li_cobrapaga=$_GET["cobrapaga"];
	$ls_estbpd=$_GET["estbpd"];
	$ls_nomproben=$_GET["txtnomproben"];
	$ls_estmov=$_GET["estmov"];
	$ls_codconmov=$_GET["codconmov"];
	$ls_estreglib=$_GET["tip_mov"];
	$ls_estdoc   =$_GET["estdoc"];
	$ls_modalidad=$_GET["modalidad"];
	$ls_tipdocres=$_GET["tipdocres"];
	$ls_numdocres=$_GET["numdocres"];
	$ls_fecdocres=$_GET["fecdocres"];
	$ls_coduniadm=$_GET["coduniadm"];
	$ls_estuac=$_GET["estuac"];
	$ls_tipreg   =$_GET["tipreg"];
	$ls_fte_financiamiento=$_GET["ftefinancia"];
	$ls_origen=$_GET["origen"];
	$ls_tippag=$_GET["tippag"];
	$ls_mediopago=$_GET["mediopago"];
	$ls_codbansig=$_GET["codbansig"];
    $ls_codestpro1=$_GET["codestpro1"];	
	$ls_nombreaut=$_GET["nombreaut"];
	$ls_codbanaut=$_GET["codbanaut"];
	$ls_nombanaut=$_GET["nombanaut"];
	$ls_rifaut   =$_GET["rifaut"]; 
	$ls_ctabanaut=$_GET["ctabanaut"];
	$ls_codbanbene=$_GET["codbanbene"];
	$ls_ctabanbene=$_GET["ctabanbene"];
	$ls_nombanbene=$_GET["nombanbene"];
	$ls_nrocontrol=$_GET["nrocontrol"];	
	$li_estserext=$_GET["hidestserext"];
}

$title[1]="Check"; $title[2]="Documento"; $title[3]="Denominación"; $title[4]="Monto Obj.Ret.";  $title[5]="Monto Car.";  $title[6]="x";
$grid1="grid";	
$totrow=0;
$ldec_totcar=0;
if ($ls_operacion=="")
   {
     $ls_sqlaux = "";
	 $ls_gestor = strtoupper($_SESSION["ls_gestor"]);
	 switch ($ls_gestor){
	   case 'MYSQLT':
	     $ls_sqlaux = " AND sigesp_cargos.codestpro=CONCAT(spg_cuentas.codestpro1,spg_cuentas.codestpro2,spg_cuentas.codestpro3,
		                    spg_cuentas.codestpro4,spg_cuentas.codestpro5)";
	   break;
	   case 'POSTGRES':
	     $ls_sqlaux = " AND sigesp_cargos.codestpro=spg_cuentas.codestpro1||spg_cuentas.codestpro2||spg_cuentas.codestpro3||
		                    spg_cuentas.codestpro4||spg_cuentas.codestpro5";

	   break;
	 }
	 
     $ls_sql    = "SELECT sigesp_cargos.codcar,sigesp_cargos.dencar,sigesp_cargos.formula,sigesp_cargos.codestpro,
	                      sigesp_cargos.spg_cuenta,sigesp_cargos.estcla,spg_cuentas.sc_cuenta 
	                 FROM sigesp_cargos, spg_cuentas 
					WHERE sigesp_cargos.codemp='".$ls_codemp."' 
					  AND sigesp_cargos.codemp=spg_cuentas.codemp 
					  AND sigesp_cargos.spg_cuenta=spg_cuentas.spg_cuenta $ls_sqlaux
					  AND sigesp_cargos.estcla=spg_cuentas.estcla";  

    $rs_data = $io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $io_msg->message($io_funcion->uf_convertirmsg($io_sql->message));
	   }
	else
	   {
		$totrow = $io_sql->num_rows($rs_data);
		if ($totrow>0)
		   {
		     $z=0;
			 while($row=$io_sql->fetch_row($rs_data))
			      {
					$z++;
					$ls_codcar     = $row["codcar"];
					$ls_dencar     = $row["dencar"];
					$ls_formula    = $row["formula"];
					$ls_codestpro  = $row["codestpro"];
					$ls_spg_cuenta = $row["spg_cuenta"];
					$ls_scg_cuenta = $row["sc_cuenta"];
					$ls_estcla     = $row["estcla"];
					$object[$z][1]="<input type=checkbox name=chk".$z."  id=chk".$z." value=1 class=sin-borde onClick=javascript:uf_calcular_monret($z);><input name=formula".$z." type=hidden id=formula".$z." value='".$ls_formula."'> ";
					$object[$z][2]="<input type=text     name=txtdoc".$z."       value='".$ls_mov_document."'     id=txtdoc".$z." class=sin-borde readonly style=text-align:right  size=17 maxlength=15><input type=hidden name=hidestcla".$z." id=hidestcla".$z." value='".$ls_estcla."'>";
					$object[$z][3]="<input type=hidden   name=txtcodcar".$z."    id=txtcodcar".$z." value='".$ls_codcar."'><input type=text name=txtdencar".$z." value='".$ls_dencar."' id=txtdencar".$z." class=sin-borde readonly style=text-align:left   size=25 maxlength=254>";
					$object[$z][4]="<input type=text     name=txtmonobjret".$z." value='".number_format($ldec_objret,2,',','.')."' id=txtmonobjret".$z." class=sin-borde style=text-align:right  size=10 maxlength=20 onBlur=javascript:uf_calcular_monret($z);>";
					$object[$z][5]="<input type=text     name=txtmoncar".$z."    value='".number_format($ldec_retenido,2,',','.')."' id=txtmoncar".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][6]="<input type=hidden   name=txtcuenta".$z."   id=txtcuenta".$z." value='".$ls_spg_cuenta."'><input name=txtcodestpro".$z." type=hidden id=txtcodestpro".$z." value='".$ls_codestpro."'><input name=txtscgcuenta".$z." type=hidden id=txtscgcuenta".$z." value='".$ls_scg_cuenta."'>";
				  }
		   }
		else
		   {
			 $z=1;
			 $io_msg->message("No se han creado Otros Créditos !!!");
			 $object[$z][1]="<input name=chk".$z." type=checkbox id=chk".$z." value=1 class=sin-borde><input name=formula".$z." type=hidden id=formula".$z." value=''> ";
			 $object[$z][2]="<input type=text name=txtdoc".$z." value=''     id=txtdoc".$z." class=sin-borde readonly style=text-align:right  size=17 maxlength=15><input type=hidden name=hidestcla".$z." id=hidestcla".$z." value=''>";
			 $object[$z][3]="<input name=txtcodcar".$z." type=hidden id=txtcodcar".$z." value=''><input type=text name=txtdencar".$z." value='' id=txtdencar".$z." class=sin-borde readonly style=text-align:left   size=25 maxlength=254>";
			 $object[$z][4]="<input type=text name=txtmonobjret".$z." value='".number_format($ldec_objret,2,',','.')."' id=txtmonobjret".$z." class=sin-borde style=text-align:right  size=10 maxlength=20 onBlur=javascript:uf_calcular_monret($z);>";
			 $object[$z][5]="<input type=text name=txtmoncar".$z." value='".number_format(0,2,',','.')."' id=txtmoncar".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
			 $object[$z][6]="<input name=txtcuenta".$z." type=hidden id=txtcuenta".$z." value=''><input name=txtcodestpro".$z." type=hidden id=txtcodestpro".$z." value=''><input name=txtscgcuenta".$z." type=hidden id=txtscgcuenta".$z." value=''>";
			 $totrow=1;
		   }
	 }
  }
elseif($ls_operacion=="EVALUAR")
{
	require_once("../shared/class_folder/evaluate_formula.php");
	$io_evaluate=new evaluate_formula();
	
	$totrow=$_POST["total"];

	for($z=1;$z<=$totrow;$z++)
	{
		$ls_codcar = $_POST["txtcodcar".$z];
		$ls_dencar = $_POST["txtdencar".$z];
		$ls_estcla = $_POST["hidestcla".$z];
		$ls_formula=$_POST["formula".$z];
		$ls_documento=$_POST["txtdoc".$z];
		$ldec_objret=$_POST["txtmonobjret".$z];
		$ldec_objret=str_replace('.','',$ldec_objret);
		$ldec_objret=str_replace(',','.',$ldec_objret);
		$ldec_moncar=$_POST["txtmoncar".$z];
		$ldec_moncar=str_replace('.','',$ldec_moncar);
		$ldec_moncar=str_replace(',','.',$ldec_moncar);
		$ls_cuenta=$_POST["txtcuenta".$z];
		$ls_codestpro=$_POST["txtcodestpro".$z];
		$ls_scg_cuenta=$_POST["txtscgcuenta".$z];

		if(array_key_exists("chk".$z,$_POST))
		{
				$lb_bool=true;
				$ldec_montocar=$io_evaluate->uf_evaluar($ls_formula,$ldec_objret,$lb_bool);
				$lb_sel="checked";
				$ldec_totcar=$ldec_totcar+$ldec_montocar;
		}
		else
		{
			$ldec_montocar=0;
			$lb_sel="";
		}
			$object[$z][1]="<input name=chk".$z." type=checkbox id=chk".$z." value=1 class=sin-borde onClick=javascript:uf_calcular_monret($z);  ".$lb_sel."><input name=formula".$z." type=hidden id=formula".$z." value='".$ls_formula."'> ";
			$object[$z][2]="<input type=text name=txtdoc".$z." value='".$ls_mov_document."'     id=txtdoc".$z." class=sin-borde readonly style=text-align:right  size=17 maxlength=15><input type=hidden name=hidestcla".$z." id=hidestcla".$z." value='".$ls_estcla."'>";
			$object[$z][3]="<input name=txtcodcar".$z." type=hidden id=txtcodcar".$z." value='".$ls_codcar."'><input type=text name=txtdencar".$z." value='".$ls_dencar."' id=txtdencar".$z." class=sin-borde readonly style=text-align:left   size=25 maxlength=254>";
			$object[$z][4]="<input type=text name=txtmonobjret".$z." value='".number_format($ldec_objret,2,',','.')."' id=txtmonobjret".$z." class=sin-borde style=text-align:right  size=10 maxlength=20 onBlur=javascript:uf_calcular_monret($z);>";
			$object[$z][5]="<input type=text name=txtmoncar".$z."    value='".number_format($ldec_montocar,2,',','.')."' id=txtmoncar".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
			$object[$z][6]="<input name=txtcuenta".$z." type=hidden id=txtcuenta".$z." value='".$ls_cuenta."'><input name=txtcodestpro".$z." type=hidden id=txtcodestpro".$z." value='".$ls_codestpro."'><input name=txtscgcuenta".$z." type=hidden id=txtscgcuenta".$z." value='".$ls_scg_cuenta."'>";
	}
}
if($ls_operacion=="GUARDARRET")
{
	require_once("sigesp_scb_c_ordenpago.php");
	$in_classmovbanco=new sigesp_scb_c_ordenpago($la_seguridad);
	$totrow=$_POST["total"];

	$ls_estmov="N";

	if($ls_tipo=="P")
	{
		$ls_codpro =$ls_provbene;
		$ls_cedbene="----------";
	}
	else
	{
		$ls_cedbene=$ls_provbene;
		$ls_codpro ="----------";
	}
	
	$ls_nomproben=$_POST["txtnomproben"];
	$in_classmovorden->io_sql->begin_transaction();

	$lb_valido=$in_classmovorden->uf_guardar_automatico($ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ld_fecha,$ls_mov_descripcion,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,($ldec_monto_mov+$ldec_retenido),($ldec_monto_mov+$ldec_retenido),$ldec_retenido,$ls_chevau,$ls_estmov,$li_estint,$li_cobrapaga,$ls_estbpd,$ls_mov_procede,$ls_estreglib,$ls_estdoc,$ls_tipo,$ls_tipdocres,$ls_numdocres,$ls_fecdocres,$ls_tipreg,$ls_fte_financiamiento,$ls_origen,$ls_tippag,$ls_mediopago,$ls_modalidad,$ls_coduniadm,$ls_codbansig,$ls_codestpro1,$ls_codbanbene,$ls_nombanbene,$ls_ctabanbene,$ls_codbanaut,$ls_nombanaut,$ls_ctabanaut,$ls_rifaut,$ls_nombreaut,$ls_nrocontrol,$li_estserext);

	$arr_movbco["codban"]=$ls_codban;
	$arr_movbco["ctaban"]=$ls_ctaban;
	$arr_movbco["mov_document"]=$ls_mov_document;
	$ld_fecdb=$io_funcion->uf_convertirdatetobd($ld_fecha);
	$arr_movbco["codope"]=$ls_codope;
	$arr_movbco["fecha"]=$ld_fecha;
	$arr_movbco["codpro"]=$ls_codpro;
	$arr_movbco["cedbene"]=$ls_cedbene;
	$arr_movbco["estmov"]=$ls_estmov;	
	$arr_movbco["monto_mov"]=$ldec_monto_mov;
	$arr_movbco["objret"]   =$ldec_objret;
	$arr_movbco["retenido"] =$ldec_retenido;
	$ls_uel=$ls_coduniadm;
	$ls_codded="00000";
	if($lb_valido)
	{
		
		$ls_operacioncon="H";		
		$lb_valido=$in_classmovorden->uf_procesar_dt_contable($arr_movbco,$ls_cuenta_scg,$ls_mov_procede,$ls_mov_descripcion,$ls_mov_document,$ls_operacioncon,($ldec_retenido),$ldec_objret,false,'00000');
		for($z=1;$z<=$totrow && $lb_valido ;$z++)
		{
			if(array_key_exists("chk".$z,$_POST))
			{
				$ls_codcar       = $_POST["txtcodcar".$z];
				$ls_cuenta       = $_POST["txtscgcuenta".$z];
				$ls_documento    = $_POST["txtdoc".$z];
				$ls_denominacion = $_POST["txtdencar".$z];
				$ls_operacioncon = "D";
				$ld_monto        = $_POST["txtmoncar".$z];
				$ldec_monto		 = str_replace(".","",$ld_monto);
				$ldec_monto		 = str_replace(",",".",$ldec_monto);
				$ldec_baseimp	 = $_POST["txtmonobjret".$z];
				$ldec_baseimp	 = str_replace(".","",$ldec_baseimp);
				$ldec_baseimp    = str_replace(",",".",$ldec_baseimp);
				if($lb_valido)
				{
					$lb_valido=$in_classmovorden->uf_procesar_dt_contable($arr_movbco,$ls_cuenta,$ls_mov_procede,$ls_denominacion,$ls_mov_document,$ls_operacioncon,$ldec_monto,$ldec_objret,false,'00000');
									 
					$ls_spgcuenta = $_POST["txtcuenta".$z];
					$ls_estpro    = $_POST["txtcodestpro".$z];
					$ls_desmov    = $_POST["txtdencar".$z];
					$ls_estcla    = $_POST["hidestcla".$z];
					$ls_operacion = 'CCP';
					$lb_valido    = $in_classmovorden->uf_procesar_dt_gasto($ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ls_estmov,$ls_estpro,$ls_spgcuenta,$ls_mov_document,$ls_denominacion,$ls_mov_procede,$ldec_monto,$ls_operacion,$ls_uel,$ldec_baseimp,$ls_codcar,$ls_estcla);
					if($lb_valido)
					{
						$in_classmovorden->io_sql->commit();
						$ls_estdoc='C';
						?>
						<script language="javascript">
							f=opener.document.form1;
							f.operacion.value="CARGAR_DT";
							f.status_doc.value='C';
							f.txtmonto.value     = "<?php print number_format($ldec_monto_mov+$ldec_retenido,2,',','.'); ?>";
							f.txtmonobjret.value = "<?php print number_format($ldec_monto_mov+$ldec_retenido,2,',','.'); ?>";
							f.totalcargo.value   = "<?php print $ldec_retenido; ?>";
							f.action="<?php print $ls_opener;?>";
							f.submit();
							close();
						</script>	
						<?php
					}
					else
					{
						$in_classmovorden->io_sql->rollback();
						$io_msg->message($in_classmovorden->is_msg_error);
					}
				}
				else
				{
					$io_msg->message($in_classmovorden->is_msg_error);
					$in_classmovorden->io_sql->rollback();
				}	
			}//if array_key_exists
		}
	}	
}	
?>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="550" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="127" align="center">    
      
        <div align="right">Documento</div>
      <td width="421" align="center">      <div align="left">
        <input name="txtdocumento" type="text" id="txtdocumento" value="<?php print $ls_mov_document;?>" style="text-align:center" readonly >
      </div>
    </tr>
    <tr>
      <td align="center">    
      
        <div align="right">Procede</div>
      <td align="center">      <div align="left">
        <input name="txtprocede" type="text" id="txtprocede" value="<?php print $ls_procede;?>" style="text-align:center" readonly >
      </div>
    </tr>
    <tr>
      <td align="center">    <div align="right">Total Retenido </div>
      <td align="center">    <div align="left">
        <input name="txttotal" type="text" id="txttotal" value="<?php print number_format($ldec_totcar,2,',','.');?>" style="text-align:right" readonly>
      </div>
    </tr>
    <tr>
      <td align="center"><p align="right">&nbsp;</p>            
      <td align="center"><div align="left">          <a href="javascript: uf_aceptar();"><img src="../shared/imagebank/tools20/aprobado.gif" alt="Aceptar" width="20" height="20" border="0"></a> <a href="javascript: uf_aceptar();">Procesar Otros Cr&eacute;ditos </a>      </div>
    </tr>
  </table>
  
  <p align="center">
<?php
$grid->makegrid($totrow,$title,$object,520,'Catalogo de Retenciones',$grid1);
?>
    <span class="Estilo1"></span>
    <input name="operacion" type="hidden" id="operacion">
    <input name="opener" type="hidden" id="opener" value="<?php print $ls_opener;?>">
    <input name="mov_document" type="hidden" id="mov_document" value="<?php print $ls_mov_document;?>">
    <input name="procede" type="hidden" id="procede" value="<?php print $ls_mov_procede;?>">
    <input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
    <input name="fecha" type="hidden" id="fecha" value="<?php print $ld_fecha;?>">
    <input name="provbene" type="hidden" id="provbene" value="<?php print $ls_provbene;?>">
    <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo;?>">
    <input name="descripcion" type="hidden" id="descripcion" value="<?php print $ls_mov_descripcion;?>">
    <input name="codban" type="hidden" id="codban" value="<?php print $ls_codban;?>">
    <input name="ctaban" type="hidden" id="ctaban" value="<?php print $ls_ctaban;?>">
    <input name="cuenta_scg" type="hidden" id="cuenta_scg" value="<?php print $ls_cuenta_scg;?>">
    <input name="mov_operacion" type="hidden" id="mov_operacion" value="<?php print $ls_codope?>">
    <input name="txtnomproben" type="hidden" id="txtnomproben" value="<?php print $ls_nomproben;?>">
    <input name="monto" type="hidden" id="monto" value="<?php print $ldec_monto_mov;?>">
    <input name="objret" type="hidden" id="objret" value="<?php print $ldec_objret;?>">
    <input name="retenido" type="hidden" id="retenido" value="<?php print $ldec_retenido;?>">
    <input name="chevau" type="hidden" id="chevau" value="<?php print $ls_chevau;?>">
    <input name="estint" type="hidden" id="estint" value="<?php print  $li_estint;?>">
    <input name="cobrapaga" type="hidden" id="cobrapaga" value="<?php print $li_cobrapaga;?>">
    <input name="estbpd" type="hidden" id="estbpd" value="<?php print $ls_estbpd;?>">
    <input name="estmov" type="hidden" id="estmov" value="<?php print $ls_estmov;?>">
    <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>">
    <input name="tip_mov" type="hidden" id="tip_mov" value="<?php print $ls_estreglib;?>">
    <input name="estdoc" type="hidden" id="estdoc" value="<?php print $ls_estdoc;?>">
	<input name="tipdocres" type="hidden" id="tipdocres" value="<?php print $ls_tipdocres;?>">
	<input name="numdocres" type="hidden" id="numdocres" value="<?php print $ls_numdocres;?>">
	<input name="fecdocres" type="hidden" id="fecdocres" value="<?php print $ls_fecdocres;?>">
	<input name="tipreg" type="hidden" id="tipreg" value="<?php print $ls_tipreg;?>">
	<input name="ftefinancia" type="hidden" id="ftefinancia" value="<?php print $ls_fte_financiamiento;?>">
	<input name="origen" type="hidden" id="origen" value="<?php print $ls_origen;?>">
	<input name="tippag" type="hidden" id="tippag" value="<?php print $ls_tippag;?>">
	<input name="mediopago" type="hidden" id="mediopago" value="<?php print $ls_mediopago;?>">
	<input name="modalidad" type="hidden" id="modalidad" value="<?php print $ls_modalidad;?>">
	<input name="coduniadm" type="hidden" id="coduniadm" value="<?php print $ls_coduniadm;?>">
	<input name="estuac" type="hidden" id="estuac" value="<?php print $ls_estuac; ?>">
	<input name="codbansig" type="hidden" id="codbansig" value="<?php print $ls_codbansig;?>">
    <input name="codestpro1" type="hidden" id="codestpro1" value="<?php print  $ls_codestpro1;?>">
		  <input name="codbanbene" type="hidden" id="codbanbene" value="<?php print $ls_codbanbene;?>">
  	  <input name="ctabanbene" type="hidden" id="ctabanbene" value="<?php print $ls_ctabanbene;?>">
	  <input name="nombanbene" type="hidden" id="nombanbene" value="<?php print $ls_nombanbene;?>">
	  <input name="nombreaut"  type="hidden" id="nombreaut"  value="<?php print $ls_nombreaut;?>">
	  <input name="codbanaut"  type="hidden" id="codbanaut"  value="<?php print $ls_codbanaut; ?>">
	  <input name="ctabanaut"  type="hidden" id="ctabanaut"  value="<?php print $ls_ctabanaut;?>">
	  <input name="rifaut"     type="hidden" id="rifaut"     value="<?php print $ls_rifaut;?>">
	  <input name="nombanaut"  type="hidden" id="nombanaut"  value="<?php print $ls_nombanaut;?>">
	  <input name="nrocontrol" type="hidden" id="nrocontrol" value="<?php print $ls_nrocontrol;?>">
      <input name="hidserext" type="hidden" id="hidserext" value="<?php echo $li_estserext; ?>">
  </p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function  uf_cambiar()
	{
		f=document.form1;
		fop=opener.document.form1;
		li_newtotal=f.cmbfilas.value;
		fop.totret.value=li_newtotal;
		fop.operacion.value="RECARGAR"
		fop.submit();		
	}
	
  function uf_aceptar()
  {
  	f=document.form1;
	li_total=f.total.value;
	if(li_total>0)
	{
		f.operacion.value="GUARDARRET";
		f.action="sigesp_w_regdt_cargos_op.php";
		f.submit();
	}
  }
  
  function uf_calcular_monret()
  {
  	f=document.form1;
	f.operacion.value="EVALUAR";
	f.action="sigesp_w_regdt_cargos_op.php";
	f.submit();
  }
</script>
</html>