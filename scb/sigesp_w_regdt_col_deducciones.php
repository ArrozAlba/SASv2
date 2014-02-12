<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_movcol.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<style type="text/css">
<!--
.Estilo1 {font-size: 36px}
-->
</style>
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
   $ls_codope= $_POST["mov_operacion"];	
   $ls_cuenta_scg=$_POST["cuenta_scg"];
   $ls_codban=$_POST["codban"];
   $ls_ctaban=$_POST["ctaban"];
   $ld_fecha=$_POST["fecha"];
   $ls_mov_colocacion=$_POST["numdoc"];
   $ls_numcol=$_POST["txtdoccol"];
   $ls_descripcion=$_POST["txtdescripcion"];
   $ldec_tasa=$_POST["tasa"];
   $li_cobrapaga= $_POST["cobrapaga"];
   $ls_opener   =$_POST["opener"];
   $ls_mov_procede=$_POST["procede"];
   $ls_mov_descripcion=$_POST["descripcion"];
   $ldec_monto_mov=$_POST["monto"];
   $ls_chevau=$_POST["chevau"];
   $li_estint=$_POST["estint"];
   $ls_estbpd=$_POST["estbpd"];
   $ls_estmov=$_POST["estmov"];
   $ls_codconmov=$_POST["codconmov"]; 
  
}
else
{
	$ls_operacion="";
  	$ls_cuentaplan = "";
	$ls_documento  = "";
	$ls_procedencia= $_GET["txtprocedencia"];
	$ls_descripcion= "";
	$ls_denominacion="";
	$ld_fecha	   = "";
	$ldec_monto	   = "";
	$ls_mov_colocacion=$_GET["numdoc"];
	$li_cobrapaga=$_GET["cobrapaga"];
	$ls_numcol=$_GET["txtdoccol"];
	$ls_mov_procede=$_GET["procede"];
	$ld_fecha=$_GET["fecha"];
	$ls_mov_descripcion=$_GET["descripcion"];
	$ls_codban=$_GET["codban"];
	$ls_ctaban=$_GET["ctaban"];
	$ls_cuenta_scg=$_GET["cuenta_scg"];
	$ls_codope=$_GET["mov_operacion"];
	$ldec_monto_mov=$_GET["monto"];
	$ls_chevau=$_GET["chevau"];
	$li_estint=$_GET["estint"];
	$li_cobrapaga=$_GET["cobrapaga"];
	$ls_estbpd=$_GET["estbpd"];
	$ls_estmov=$_GET["estmov"];
	$ls_codconmov=$_GET["codconmov"];
	$ldec_tasa=$_GET["tasa"];
	$ls_opener   =$_GET["opener"];
}


$title[1]="Check"; $title[2]="Documento"; /*$title[3]="Código";*/ $title[3]="Denominación"; /*$title[5]="Porcentaje";*/ $title[4]="Monto Obj.Ret.";  $title[5]="Monto Ret."; $title[6]="Monto Deducible"; $title[7]="ISLR";  
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
		$msg->message($fun->uf_convertirmsg($io_sql->message));
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
					$li_status_iva=$data["IVA"][$z];
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
					$object[$z][4]="<input type=text name=txtmonobjret".$z." value='".number_format($ldec_monto_mov,2,',','.')."' id=txtmonobjret".$z." class=sin-borde style=text-align:right  size=10 maxlength=20 onBlur=javascript:uf_calcular_monret($z);>";
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
			print "No se han creado Cargos";	
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
				$ldec_totret=$ldec_totret+$ldec_montoret;
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
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$in_classmovcol=new sigesp_scb_c_movcol($la_seguridad);
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
	$in_classmovcol->SQL->begin_transaction();
	$lb_valido=$in_classmovcol->uf_guardar_automatico($ls_codban,$ls_ctaban,$ls_mov_colocacion,$ls_numcol,$ls_codope,$ld_fecha,$ls_descripcion,$ldec_monto,$ldec_tasa,'N',$li_cobrapaga,$ls_esttransf);

	$arr_movbco["codban"]=$ls_codban;
	$arr_movbco["ctaban"]=$ls_ctaban;
    $arr_movbco["mov_colocacion"]=$ls_mov_colocacion;
	$arr_movbco["numcol"]= $ls_numcol ;
	$arr_movbco["codope"]= $ls_codope; 
	
	
	$arr_movbco["objret"]   =$ldec_objret;
	$arr_movbco["retenido"] =$ldec_retenido;
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
			
		$lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_cuenta_scg,$ls_mov_procede,$ls_mov_descripcion,$ls_mov_document,$ls_operacioncon,$ldec_monto_mov,$ldec_objret,true,$ls_codded,'N');
			
		if($lb_valido)
		{

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
					$lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_cuenta,$ls_procede,$ls_dended,$ls_documento,'H',$ldec_monto,$ldec_objret,false,$ls_codded,'N');
					if($ls_codded!="00000")
					{
						$ldec_monto_mov=$ldec_monto_mov-$ldec_monto;
						$lb_valido=$in_classmovbanco->uf_update_monto_mov($arr_movbco,$ls_cuenta_scg,$ls_procede,$ls_mov_descripcion,$ls_documento,$ls_operacioncon,$ldec_monto_mov,$ldec_objret,'00000');
					}
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
				if(!$lb_valido)
				{
					$in_classmovbanco->SQL->rollback();
					$msg->message($in_classmovbanco->is_msg_error);
				}
				else
				{
					$in_classmovbanco->SQL->commit();
					?>
					<script language="javascript">
						f=opener.document.form1;
						f.operacion.value="CARGAR_DT";
						f.action="<?php print $ls_opener;?>";
						f.submit();
					</script>	
					<?php
				}
			}
			else
			{
					$in_classmovbanco->SQL->rollback();
					$msg->message($in_classmovbanco->is_msg_error);
			}
			
	}
	else
	{
		$in_classmovbanco->SQL->rollback();
		$msg->message($in_classmovbanco->is_msg_error);
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
        <input name="txtdocumento" type="text" id="txtdocumento" value="<?php print $ls_documento;?>" style="text-align:center" readonly >
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
        <input name="txttotal" type="text" id="txttotal" value="<?php print number_format($ldec_totret,2,',','.');?>" style="text-align:right" readonly>
      </div>
    </tr>
    <tr>
      <td align="center"><p align="right">Numero de Retenciones
          </p>            
      <td align="center"><div align="left">          <a href="javascript: uf_aceptar();"><img src="../shared/imagebank/tools20/aprobado.gif" alt="Aceptar" width="20" height="20" border="0"></a>       
      </div>
    </tr>
  </table>
  
  <p align="center">
    <?php

$grid->makegrid($totrow,$title,$object,520,'Catalogo de Retenciones',$grid1);

?>
    <span class="Estilo1"></span>
    <input name="txtcuentascg" type="hidden" id="txtcuentascg">
    <input name="cuenta_scg" type="hidden" id="cuenta_scg" value="<?php print $ls_cuenta_scg;?>">
    <input name="comprobante" type="hidden" id="comprobante" value="<?php print $ls_comprobante;?>">
    <input name="descripcion" type="hidden" id="descripcion" value="<?php print $ls_mov_descripcion;?>">
    <input name="operacion" type="hidden" id="operacion">
    <!-- Datos del movimiento-->
    <input name="codban" type="hidden" id="codban" value="<?php print $ls_codban;?>">
    <input name="ctaban" type="hidden" id="ctaban" value="<?php print $ls_ctaban;?>">
    <input name="txtdoccol" type="hidden" id="txtdoccol" value="<?php print $ls_numcol;?>">
    <input name="numdoc" type="hidden" id="numdoc" value="<?php print $ls_mov_colocacion;?>">
    <input name="mov_operacion" type="hidden" id="mov_operacion" value="<?php print $ls_codope;?>">
    <input name="fecha" type="hidden" id="fecha" value="<?php print $ld_fecha;?>">
    <input name="conmov" type="hidden" id="conmov" value="<?php print $ls_conmov;?>">
    <input name="monto" type="hidden" id="monto" value="<?php print $ldec_monto_mov;?>">
    <input name="tasa" type="hidden" id="tasa" value="<?php print $ldec_tasa;?>">
    <input name="cobrapaga"      type="hidden"  id="cobrapaga"      value="<?php print $li_cobrapaga;?>">
    <!-- Opener del documento-->
    <input name="opener" type="hidden" id="opener" value="<?php print $ls_opener;?>">
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
	f.operacion.value="GUARDARRET";
	f.action="sigesp_w_regdt_col_deducciones.php";
	f.submit();
  }
  
  function uf_calcular_monret()
  {
  	f=document.form1;
	f.operacion.value="EVALUAR";
	f.action="sigesp_w_regdt_col_deducciones.php";
	f.submit();
  }


</script>
</html>
