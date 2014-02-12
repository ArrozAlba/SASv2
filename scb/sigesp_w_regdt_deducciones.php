<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo Deducciones</title>
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
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
</head>
<body>
<?Php
require_once("../shared/class_folder/sigesp_include.php");
$io_in=new sigesp_include();
$con=$io_in->uf_conectar();

require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();

require_once("../shared/class_folder/class_datastore.php");
$io_ds=new class_datastore();

require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);

require_once("../shared/class_folder/class_funciones.php");
$fun=new class_funciones(); 

require_once("../shared/class_folder/grid_param.php");
$grid=new grid_param();


$la_emp=$_SESSION["la_empresa"];

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
	$ldec_antret=$_POST["retenido"];
	$ls_chevau=$_POST["chevau"];
	$li_estint=$_POST["estint"];
	$li_estcob=$_POST["estcob"];
	$li_cobrapaga=$_POST["cobrapaga"];
	$ls_nomproben=$_POST["txtnomproben"];
	$ls_estbpd=$_POST["estbpd"];
	$ls_estmov=$_POST["estmov"];
	$ls_codconmov=$_POST["codconmov"];
	$ls_estreglib=$_POST["tip_mov"];
	$ls_estdoc   =$_POST["estdoc"];
	$ls_codfuefin =$_POST["codfuefin"];
	$ls_codtipfon 	 = $_POST["hidcodtipfon"];
	$ls_numordpagmin = $_POST["hidnumordpagmin"];
}
else
{
	$ls_operacion="";	
	$ls_documento=$_GET["txtdocumento"];
	$ls_procede=$_GET["txtprocede"];
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
	$ldec_retenido=0;
	$ldec_antret=$_GET["retenido"];
	$ls_chevau=$_GET["chevau"];
	$li_estint=$_GET["estint"];
	$li_estcob=$_GET["estcob"];
	$li_cobrapaga=$_GET["cobrapaga"];
	$ls_estbpd=$_GET["estbpd"];
	$ls_nomproben=$_GET["txtnomproben"];
	$ls_estmov=$_GET["estmov"];
	$ls_codconmov=$_GET["codconmov"];
	$ls_estreglib=$_GET["tip_mov"];
	$ls_estdoc   =$_GET["estdoc"];
	$ls_codfuefin =$_GET["codfuefin"];
	$ls_codtipfon     = $_GET["codtipfon"];
    $ls_numordpagmin  = $_GET["numordpagmin"];
}
if($ls_codfuefin=="")
{
	$ls_codfuefin="--";
}

$ls_empresa=$la_emp["codemp"];
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB",$ls_opener,$ls_permisos,&$la_seguridad,$la_permisos);
$title[1]="Check"; $title[2]="Documento"; $title[3]="Denominación"; $title[4]="Monto Obj.Ret.";  $title[5]="Monto Ret."; $title[6]="Monto Deducible"; $title[7]="ISLR";  
$grid1="grid";	
$totrow=0;
$ldec_totret=0;
if($ls_operacion=="")
{
    $ls_codemp=$la_emp["codemp"];
    $ls_sql=" SELECT * FROM sigesp_deducciones";  
    $rs=$io_sql->select($ls_sql);	
	if($rs===false)
	{
		$io_msg->message($fun->uf_convertirmsg($io_sql->message));
	}
	else
	{
		$data=$rs;
		if($row=$io_sql->fetch_row($rs))
		{          
			$data=$io_sql->obtener_datos($rs);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$io_ds->data=$data;
			$totrow=$io_ds->getRowCount("codded");
        
			if($totrow>0)
			{
				for($z=1;$z<=$totrow;$z++)
				{
					$ls_codded=$data["codded"][$z];
					$ls_dended=$data["dended"][$z];
					$ls_formula=$data["formula"][$z];
					$li_status_islr=$data["islr"][$z];
					$li_status_iva=$data["iva"][$z];
					$li_status_retmun=$data["estretmun"][$z];
					$ldec_monded=$data["monded"][$z];
					$ls_cuenta=$data["sc_cuenta"][$z];
					if($li_status_islr==1)
					{
						$chk_islr="checked";
					}
					else
					{
					
						$chk_islr="";
                   	}
					$object[$z][1]="<input name=chk".$z." type=checkbox id=chk".$z." value=1 class=sin-borde onClick=javascript:uf_calcular_monret($z);><input name=formula".$z." type=hidden id=formula".$z." value='".$ls_formula."'> ";
					$object[$z][2]="<input type=text name=txtdoc".$z." value='".$ls_documento."'     id=txtdoc".$z." class=sin-borde readonly style=text-align:right  size=17 maxlength=15>";
					$object[$z][3]="<input name=txtcodded".$z." type=hidden id=txtcodded".$z." value='".$ls_codded."'><input type=text name=txtdended".$z." value='".$ls_dended."' id=txtdended".$z." class=sin-borde readonly style=text-align:left   size=25 maxlength=254>";
					$object[$z][4]="<input type=text name=txtmonobjret".$z." value='".number_format($ldec_objret,2,',','.')."' id=txtmonobjret".$z." class=sin-borde style=text-align:right  size=10 maxlength=20 onBlur=javascript:uf_calcular_monret($z);>";
					$object[$z][5]="<input type=text name=txtmonret".$z." value='".number_format(0,2,',','.')."' id=txtmonret".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][6]="<input type=text name=txtmonded".$z." value='".number_format($ldec_monded,2,',','.')."' id=txtmonded".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][7]="<input name=chkislr".$z." type=checkbox id=chkislr".$z." value=1 class=sin-borde onClick='return false;' ".$chk_islr." ><input name=txtcuenta".$z." type=hidden id=txtcuenta".$z." value='".$ls_cuenta."'>";
				}				
			}
			else
			{
					$z=1;
					$object[$z][1]="<input name=chk".$z." type=checkbox id=chk".$z." value=1 class=sin-borde><input name=formula".$z." type=hidden id=formula".$z." value=''> ";
					$object[$z][2]="<input type=text name=txtdoc".$z." value=''     id=txtdoc".$z." class=sin-borde readonly style=text-align:right  size=17 maxlength=15>";
					$object[$z][3]="<input name=txtcodded".$z." type=hidden id=txtcodded".$z." value=''><input type=text name=txtdended".$z." value='' id=txtdended".$z." class=sin-borde readonly style=text-align:left   size=25 maxlength=254>";
					$object[$z][4]="<input type=text name=txtmonobjret".$z." value='".number_format(0,2,',','.')."' id=txtmonobjret".$z." class=sin-borde style=text-align:right  size=10 maxlength=20 onBlur=javascript:uf_calcular_monret($z);>";
					$object[$z][5]="<input type=text name=txtmonret".$z." value='".number_format(0,2,',','.')."' id=txtmonret".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][6]="<input type=text name=txtmonded".$z." value='".number_format(0,2,',','.')."' id=txtmonded".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][7]="<input name=chkislr".$z." type=checkbox id=chkislr".$z." value=1 class=sin-borde onClick='return false;' ".$chk_islr." ><input name=txtcuenta".$z." type=hidden id=txtcuenta".$z." value=''>";
					$totrow=1;
			}

		}
		else
		{
			$io_msg->message("No se han creado Retenciones Municipales");	
			$z=1;
			$chk_islr="";
			$object[$z][1]="<input name=chk".$z." type=checkbox id=chk".$z." value=1 class=sin-borde><input name=formula".$z." type=hidden id=formula".$z." value=''> ";
			$object[$z][2]="<input type=text name=txtdoc".$z." value=''     id=txtdoc".$z." class=sin-borde readonly style=text-align:right  size=17 maxlength=15>";
			$object[$z][3]="<input name=txtcodded".$z." type=hidden id=txtcodded".$z." value=''><input type=text name=txtdended".$z." value='' id=txtdended".$z." class=sin-borde readonly style=text-align:left   size=25 maxlength=254>";
			$object[$z][4]="<input type=text name=txtmonobjret".$z." value='".number_format(0,2,',','.')."' id=txtmonobjret".$z." class=sin-borde style=text-align:right  size=10 maxlength=20 onBlur=javascript:uf_calcular_monret($z);>";
			$object[$z][5]="<input type=text name=txtmonret".$z." value='".number_format(0,2,',','.')."' id=txtmonret".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
			$object[$z][6]="<input type=text name=txtmonded".$z." value='".number_format(0,2,',','.')."' id=txtmonded".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
			$object[$z][7]="<input name=chkislr".$z." type=checkbox id=chkislr".$z." value=1 class=sin-borde onClick='return false;' ".$chk_islr." ><input name=txtcuenta".$z." type=hidden id=txtcuenta".$z." value=''>";
			$totrow=0;		
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
		
		$ls_codded=$_POST["txtcodded".$z];
		$ls_dended=$_POST["txtdended".$z];
		$ls_formula=$_POST["formula".$z];
		$ls_documento=$_POST["txtdoc".$z];
		$ldec_objret=$_POST["txtmonobjret".$z];
		$ldec_objret=str_replace('.','',$ldec_objret);
		$ldec_objret=str_replace(',','.',$ldec_objret);
		$ldec_monded=$_POST["txtmonded".$z];
		$ldec_monded=str_replace('.','',$ldec_monded);
		$ldec_monded=str_replace(',','.',$ldec_monded);
		$ls_cuenta=$_POST["txtcuenta".$z];
		if(array_key_exists("chkislr".$z,$_POST))
		{
			$chk_islr="checked";
			$li_status_islr=1;
		}
		else
		{
			$chk_islr="";
			$li_status_islr=0;
       	}
		if(array_key_exists("chk".$z,$_POST))
		{
				$lb_bool=true;
				$ldec_montoret=$io_evaluate->uf_evaluar($ls_formula,$ldec_objret,$lb_bool);
				if($li_status_islr==1)
				{
					$ldec_montoret=$ldec_montoret-$ldec_monded;
				}
				$lb_sel="checked";
				$ldec_totret=$ldec_totret+$ldec_antret+$ldec_montoret;
		}
		else
		{
			$ldec_montoret=0;
			$lb_sel="";
		}
		
					$object[$z][1]="<input name=chk".$z." type=checkbox id=chk".$z." value=1 class=sin-borde onClick=javascript:uf_calcular_monret($z); ".$lb_sel."><input name=formula".$z." type=hidden id=formula".$z." value='".$ls_formula."'> ";
					$object[$z][2]="<input type=text name=txtdoc".$z." value='".$ls_documento."'     id=txtdoc".$z." class=sin-borde readonly style=text-align:right  size=17 maxlength=15>";
					$object[$z][3]="<input name=txtcodded".$z." type=hidden id=txtcodded".$z." value='".$ls_codded."'><input type=text name=txtdended".$z." value='".$ls_dended."' id=txtdencar".$z." class=sin-borde readonly style=text-align:left   size=25 maxlength=254>";
					$object[$z][4]="<input type=text name=txtmonobjret".$z." value='".number_format($ldec_objret,2,',','.')."' id=txtmonobjret".$z." class=sin-borde style=text-align:right  size=10 maxlength=20 onBlur=javascript:uf_calcular_monret($z);>";
					$object[$z][5]="<input type=text name=txtmonret".$z." value='".number_format($ldec_montoret,2,',','.')."' id=txtmonret".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][6]="<input type=text name=txtmonded".$z." value='".number_format($ldec_monded,2,',','.')."' id=txtmonded".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][7]="<input name=chkislr".$z." type=checkbox id=chkislr".$z." value=1 class=sin-borde onClick='return false;' ".$chk_islr." ><input name=txtcuenta".$z." type=hidden id=txtcuenta".$z." value='".$ls_cuenta."'>";
	}
}
if($ls_operacion=="GUARDARRET")
{
	require_once("sigesp_scb_c_movbanco.php");
	$in_classmovbanco=new sigesp_scb_c_movbanco($la_seguridad);
	$totrow=$_POST["total"];
	$ldec_totret=$ldec_retenido;
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
	$in_classmovbanco->io_sql->begin_transaction();
	$lb_valido=$in_classmovbanco->uf_guardar_automatico($ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ld_fecha,$ls_mov_descripcion,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto_mov,$ldec_objret,$ldec_totret,$ls_chevau,$ls_estmov,$li_estint,$li_cobrapaga,$ls_estbpd,$ls_mov_procede,$ls_estreglib,$ls_estdoc,$ls_tipo,$ls_codfuefin,$ls_numordpagmin,$ls_codtipfon,$li_estcob);

	$arr_movbco["codban"]=$ls_codban;
	$arr_movbco["ctaban"]=$ls_ctaban;
	$arr_movbco["mov_document"]=$ls_mov_document;
	$ld_fecdb=$fun->uf_convertirdatetobd($ld_fecha);
	$arr_movbco["codope"]=$ls_codope;
	$arr_movbco["fecha"]=$ld_fecha;
	$arr_movbco["codpro"]=$ls_codpro;
	$arr_movbco["cedbene"]=$ls_cedbene;
	$arr_movbco["estmov"]=$ls_estmov;	
	$arr_movbco["monto_mov"]=$ldec_monto_mov;

	$arr_movbco["objret"]   =$ldec_objret;
	$arr_movbco["retenido"] =$ldec_totret;
	$ls_codded="00000";
	if($lb_valido)
	{
		
		if(($ls_codope=="ND")||($ls_codope=="RE")||($ls_codope=="CH"))
		{
			$ls_operacioncon="H";
		}
		else
		{
			$ls_operacioncon="D";
		}
			
		$lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_cuenta_scg,$ls_mov_procede,$ls_mov_descripcion,$ls_mov_document,$ls_operacioncon,$ldec_monto_mov,$ldec_objret,true,$ls_codded);
			
		if($lb_valido)
		{
			$ldec_monto_aux=$ldec_monto_mov-$ldec_totret;
			for($z=1;$z<=$totrow && $lb_valido ;$z++)
			{
				if(array_key_exists("chkislr".$z,$_POST))
				{
					$chk_islr="checked";
					$li_status_islr=1;
				}
				else
				{
					$chk_islr="";
					$li_status_islr=0;
				}
				$ls_codded=$_POST["txtcodded".$z];
				$ls_dended=$_POST["txtdended".$z];
				$ls_formula=$_POST["formula".$z];
				$ls_documento=$_POST["txtdoc".$z];
				$ldec_objret=$_POST["txtmonobjret".$z];
				$ldec_objret=str_replace('.','',$ldec_objret);
				$ldec_objret=str_replace(',','.',$ldec_objret);
				$ldec_monded=$_POST["txtmonded".$z];
				$ldec_monded=str_replace('.','',$ldec_monded);
				$ldec_monded=str_replace(',','.',$ldec_monded);
				$ls_cuenta=$_POST["txtcuenta".$z];
				$ldec_monto=$_POST["txtmonret".$z];
				$ldec_monto=str_replace('.','',$ldec_monto);
				$ldec_monto=str_replace(',','.',$ldec_monto);
					
				if(array_key_exists("chk".$z,$_POST))
				{
					$lb_sel="checked";
					$lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_cuenta,$ls_procede,$ls_dended,$ls_mov_document,'H',$ldec_monto,$ldec_objret,false,$ls_codded);
				}
				else
				{
					$lb_sel="";
				}
					$object[$z][1]="<input name=chk".$z." type=checkbox id=chk".$z." value=1 class=sin-borde onClick=javascript:uf_calcular_monret($z); ".$lb_sel."><input name=formula".$z." type=hidden id=formula".$z." value='".$ls_formula."'> ";
					$object[$z][2]="<input type=text name=txtdoc".$z." value='".$ls_documento."'     id=txtdoc".$z." class=sin-borde readonly style=text-align:right  size=17 maxlength=15>";
					$object[$z][3]="<input name=txtcodded".$z." type=hidden id=txtcodded".$z." value='".$ls_codded."'><input type=text name=txtdended".$z." value='".$ls_dended."' id=txtdencar".$z." class=sin-borde readonly style=text-align:left   size=25 maxlength=254>";
					$object[$z][4]="<input type=text name=txtmonobjret".$z." value='".number_format($ldec_objret,2,',','.')."' id=txtmonobjret".$z." class=sin-borde style=text-align:right  size=10 maxlength=20 onBlur=javascript:uf_calcular_monret($z);>";
					$object[$z][5]="<input type=text name=txtmonret".$z." value='".number_format($ldec_monto,2,',','.')."' id=txtmonret".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][6]="<input type=text name=txtmonded".$z." value='".number_format($ldec_monded,2,',','.')."' id=txtmonded".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][7]="<input name=chkislr".$z." type=checkbox id=chkislr".$z." value=1 class=sin-borde onClick='return false;' ".$chk_islr." ><input name=txtcuenta".$z." type=hidden id=txtcuenta".$z." value='".$ls_cuenta."'>";
				}
				if($ls_codded!="00000")
				{						
					$lb_valido=$in_classmovbanco->uf_update_monto_mov($arr_movbco,$ls_cuenta_scg,$ls_procede,$ls_mov_descripcion,$ls_mov_document,$ls_operacioncon,$ldec_monto_aux,$ldec_objret,'00000');
				}
				if(!$lb_valido)
				{
					$in_classmovbanco->io_sql->rollback();
					$io_msg->message($in_classmovbanco->is_msg_error);
				}
				else
				{
					$in_classmovbanco->io_sql->commit();
					$ls_estdoc='C';
					?>
					<script language="javascript">
						f=opener.document.form1;
						f.operacion.value="CARGAR_DT";
						f.status_doc.value='C';//Cambio estatus a actualizable
						f.txtretenido.value="<?php print number_format($ldec_totret,2,",",".") ;?>";
						f.action="<?php print $ls_opener;?>";
						f.submit();
						close();
					</script>	
					<?php
				}
			}
			else
			{
					$in_classmovbanco->io_sql->rollback();
					$io_msg->message($in_classmovbanco->is_msg_error);
			}
			
	}
	else
	{
		$in_classmovbanco->io_sql->rollback();
		$io_msg->message($in_classmovbanco->is_msg_error);
	}
}	


?>
<form name="form1" method="post" action="">
  <table width="550" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-celda">
      <td height="22" colspan="2" align="center"><input name="hidcodtipfon" type="hidden" id="hidcodtipfon" value="<?php echo $ls_codtipfon; ?>">
      Cat&aacute;logo Deducciones            
      <input name="hidnumordpagmin" type="hidden" id="hidnumordpagmin" value="<?php echo $ls_numordpagmin; ?>">
      </tr>
    <tr>
      <td height="13" align="center">    
      <td height="13" align="center">        </tr>
    <tr>
      <td width="127" height="22" align="center">    
      
        <div align="right">Documento</div>
      <td width="421" height="22" align="center">      <div align="left">
        <input name="txtdocumento" type="text" id="txtdocumento" value="<?php print $ls_mov_document;?>" style="text-align:center" readonly >
    </div>    </tr>
    <tr>
      <td height="22" align="center">    
      
        <div align="right">Procede</div>
      <td height="22" align="center">      <div align="left">
        <input name="txtprocede" type="text" id="txtprocede" value="<?php print $ls_procede;?>" style="text-align:center" readonly >
    </div>    </tr>
    <tr>
      <td height="22" align="center">    <div align="right">Total Retenido </div>
      <td height="22" align="center">    <div align="left">
        <input name="txttotal" type="text" id="txttotal" value="<?php print number_format($ldec_totret,2,',','.');?>" style="text-align:right" readonly>
    </div>    </tr>
    <tr>
      <td height="22" align="center"><p align="right">&nbsp;</p>            
    <td height="22" align="center"><div align="left">          <a href="javascript: uf_aceptar();"><img src="../shared/imagebank/tools20/aprobado.gif" alt="Aceptar" width="20" height="20" border="0"></a> <a href="javascript: uf_aceptar();">Procesar deducciones </a>      </div>    </tr>
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
    <input name="retenido" type="hidden" id="retenido" value="<?php print $ldec_antret;?>">
    <input name="chevau" type="hidden" id="chevau" value="<?php print $ls_chevau;?>">
    <input name="estcob" type="hidden" id="estcob" value="<?php print  $li_estcob;?>">
	<input name="estint" type="hidden" id="estint" value="<?php print  $li_estint;?>">
    <input name="cobrapaga" type="hidden" id="cobrapaga" value="<?php print $li_cobrapaga;?>">
    <input name="estbpd" type="hidden" id="estbpd" value="<?php print $ls_estbpd;?>">
    <input name="estmov" type="hidden" id="estmov" value="<?php print $ls_estmov;?>">
    <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>">
    <input name="tip_mov" type="hidden" id="tip_mov" value="<?php print $ls_estreglib;?>">
    <input name="estdoc" type="hidden" id="estdoc" value="<?php print $ls_estdoc;?>">
	 <input name="codfuefin" type="hidden" id="codfuefin" value="<?php print $ls_codfuefin;?>">
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
	ldec_monobjret=f.objret.value;
	ldec_total=f.txttotal.value;
	if(li_total>0)
	{
		ldec_total=uf_convertir_monto(ldec_total);
		if(parseFloat(ldec_total)<=parseFloat(ldec_monobjret))
		{
			f.operacion.value="GUARDARRET";
			f.action="sigesp_w_regdt_deducciones.php";
			f.submit();
		}
		else
		{
			alert("Monto Retenido no puede ser mayor al monto objeto a Retención");
		}
	}
  }
  
  function uf_calcular_monret()
  {
  	f=document.form1;
	f.operacion.value="EVALUAR";
	f.action="sigesp_w_regdt_deducciones.php";
	f.submit();
  }
</script>
</html>