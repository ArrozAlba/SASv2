<?Php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
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
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
$io_ds=new class_datastore();
require_once("../shared/class_folder/class_funciones.php");
$io_fun=new class_funciones(); 
require_once("../shared/class_folder/grid_param.php");
$grid=new grid_param();
require_once("../shared/class_folder/evaluate_formula.php");
$io_evaluate=new evaluate_formula();

$la_emp=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
    $li_fila     =$_POST["total"];  
	$ldec_objret=$_POST["objret"];	
	$ldec_monto_mov=$_POST["monto"];
	$ldec_objret=$_POST["objret"];
	$ldec_retenido=$_POST["retenido"];
	$ls_municipal=$_POST["municipal"];
	$ls_fila=$_POST["fila"];	
}
else
{
	$ls_operacion="";	
	$ls_documento=$_GET["txtdocumento"];	
	$ldec_monto_mov=$_GET["monto"];
	$ldec_objret=$_GET["objret"];
	$ls_fila=$_GET["fila"];
	if(array_key_exists("origen",$_GET))
		$ls_municipal=$_GET["origen"];
	else
		$ls_municipal="0";
}
$ldec_objret=str_replace(".","",$ldec_objret);
$ldec_objret=str_replace(",",".",$ldec_objret);
$ldec_monto_mov=str_replace(".","",$ldec_monto_mov);
$ldec_monto_mov=str_replace(",",".",$ldec_monto_mov);


$title[1]="Check"; $title[2]="Documento"; /*$title[3]="Código";*/ $title[3]="Denominación"; /*$title[5]="Porcentaje";*/ $title[4]="Monto Obj.Ret.";  $title[5]="Monto Ret."; $title[6]="Monto Deducible"; $title[7]="ISLR";  
$grid1="grid";	
$totrow=0;
$ldec_totret=0;
if($ls_operacion=="")
{
    $ls_codemp=$la_emp["codemp"];
	if($ls_municipal=="0")
    	$ls_sql=" SELECT * FROM sigesp_deducciones ";  
	else
		$ls_sql=" SELECT * FROM sigesp_deducciones where estretmun = '1' ";  

    $rs=$io_sql->select($ls_sql);	

	if($rs==false)
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
					//$ld_porcar=$data["PorDed"][$z];
					$ls_formula=$data["formula"][$z];
					$li_status_islr=$data["islr"][$z];
					$li_status_iva=$data["iva"][$z];
					$li_status_retmun=$data["estretmun"][$z];
					$ldec_monded=$data["monded"][$z];
					$ls_cuenta=$data["sc_cuenta"][$z];
					$ls_porded=$data["porded"][$z];
					if($li_status_islr==1)
					{
						$chk_islr="checked";
					}
					else
					{
					
						$chk_islr="";
                   	}
					if(array_key_exists("la_deducciones",$_SESSION))
					{
						$la_deducciones=$_SESSION["la_deducciones"];
						
						if(array_key_exists("Codded",$la_deducciones))
						{
								$ls_codded=$la_deducciones["Codded"][$z];
								if($ls_codded!="")
								{
									$lb_chk="checked";
									
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
									$lb_chk="";
									$ldec_montoret=0;
								}
						}
						else
						{
							$lb_chk="";
							$ldec_montoret=0;
						}
					}
					else
					{
						$lb_chk="";
						$ldec_montoret=0;
					}
					$object[$z][1]="<input name=chk".$z." type=checkbox id=chk".$z." value=1 class=sin-borde onClick=javascript:uf_calcular_monret($z); ".$lb_chk."><input name=formula".$z." type=hidden id=formula".$z." value='".$ls_formula."'> ";
					$object[$z][2]="<input type=text name=txtdoc".$z." value='".$ls_documento."'     id=txtdoc".$z." class=sin-borde readonly style=text-align:right  size=17 maxlength=15>";
					$object[$z][3]="<input name=txtcodded".$z." type=hidden id=txtcodded".$z." value='".$ls_codded."'><input type=text name=txtdended".$z." value='".$ls_dended."' id=txtdended".$z." class=sin-borde readonly style=text-align:left   size=25 maxlength=254><input name=txtporded".$z." type=hidden id=txtporded".$z." value='".$ls_porded."'>";
					$object[$z][4]="<input type=text name=txtmonobjret".$z." value='".number_format($ldec_objret,2,',','.')."' id=txtmonobjret".$z." class=sin-borde style=text-align:right  size=10 maxlength=20 onBlur=javascript:uf_calcular_monret($z);>";
					$object[$z][5]="<input type=text name=txtmonret".$z." value='".number_format($ldec_montoret,2,',','.')."' id=txtmonret".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][6]="<input type=text name=txtmonded".$z." value='".number_format($ldec_monded,2,',','.')."' id=txtmonded".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][7]="<input name=chkislr".$z." type=checkbox id=chkislr".$z." value=1 class=sin-borde onClick='return false;' ".$chk_islr." ><input name=txtcuenta".$z." type=hidden id=txtcuenta".$z." value='".$ls_cuenta."'>";
				}				
			}
			else
			{
					$z=1;
					$object[$z][1]="<input name=chk".$z." type=checkbox id=chk".$z." value=1 class=sin-borde><input name=formula".$z." type=hidden id=formula".$z." value=''> ";
					$object[$z][2]="<input type=text name=txtdoc".$z." value=''     id=txtdoc".$z." class=sin-borde readonly style=text-align:right  size=17 maxlength=15>";
					$object[$z][3]="<input name=txtcodded".$z." type=hidden id=txtcodded".$z." value=''><input type=text name=txtdended".$z." value='' id=txtdended".$z." class=sin-borde readonly style=text-align:left   size=25 maxlength=254><input name=txtporded".$z." type=hidden id=txtporded".$z." >";
					$object[$z][4]="<input type=text name=txtmonobjret".$z." value='".number_format(0,2,',','.')."' id=txtmonobjret".$z." class=sin-borde style=text-align:right  size=10 maxlength=20 onBlur=javascript:uf_calcular_monret($z);>";
					$object[$z][5]="<input type=text name=txtmonret".$z." value='".number_format(0,2,',','.')."' id=txtmonret".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][6]="<input type=text name=txtmonded".$z." value='".number_format(0,2,',','.')."' id=txtmonded".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][7]="<input name=chkislr".$z." type=checkbox id=chkislr".$z." value=1 class=sin-borde onClick='return false;' ".$chk_islr." ><input name=txtcuenta".$z." type=hidden id=txtcuenta".$z." value=''>";
					$totrow=1;
			}

		}
		else
		{
			$io_msg->message( "No se han creado Cargos");	
			$z=1;
			$chk_islr="";
			$object[$z][1]="<input name=chk".$z." type=checkbox id=chk".$z." value=1 class=sin-borde><input name=formula".$z." type=hidden id=formula".$z." value=''> ";
			$object[$z][2]="<input type=text name=txtdoc".$z." value=''     id=txtdoc".$z." class=sin-borde readonly style=text-align:right  size=17 maxlength=15>";
			$object[$z][3]="<input name=txtcodded".$z." type=hidden id=txtcodded".$z." value=''><input type=text name=txtdended".$z." value='' id=txtdended".$z." class=sin-borde readonly style=text-align:left   size=25 maxlength=254><input name=txtporded".$z." type=hidden id=txtporded".$z." >";
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
		$ls_porded=$_POST["txtporded".$z];
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
					$object[$z][3]="<input name=txtcodded".$z." type=hidden id=txtcodded".$z." value='".$ls_codded."'><input type=text name=txtdended".$z." value='".$ls_dended."' id=txtdencar".$z." class=sin-borde readonly style=text-align:left   size=25 maxlength=254><input name=txtporded".$z." type=hidden id=txtporded".$z." value='".$ls_porded."'>";
					$object[$z][4]="<input type=text name=txtmonobjret".$z." value='".number_format($ldec_objret,2,',','.')."'   id=txtmonobjret".$z." class=sin-borde style=text-align:right  size=10 maxlength=20 onBlur=javascript:uf_calcular_monret($z);>";
					$object[$z][5]="<input type=text name=txtmonret".$z."    value='".number_format($ldec_montoret,2,',','.')."' id=txtmonret".$z."    class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][6]="<input type=text name=txtmonded".$z."    value='".number_format($ldec_monded,2,',','.')."'   id=txtmonded".$z."    class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][7]="<input name=chkislr".$z." type=checkbox id=chkislr".$z." value=1 class=sin-borde onClick='return false;' ".$chk_islr." ><input name=txtcuenta".$z." type=hidden id=txtcuenta".$z." value='".$ls_cuenta."'>";
	}
}
if($ls_operacion=="PROCESAR")
{
	$totrow=$_POST["total"];
	$la_deducciones="";
	for($z=1;$z<=$totrow;$z++)
	{
		
		$ls_codded=$_POST["txtcodded".$z];
		$ls_dended=$_POST["txtdended".$z];
		$ldec_objret=$_POST["txtmonobjret".$z];
		$ldec_objret=str_replace('.','',$ldec_objret);
		$ldec_objret=str_replace(',','.',$ldec_objret);
		$ldec_monded=$_POST["txtmonded".$z];
		$ldec_monded=str_replace('.','',$ldec_monded);
		$ldec_monded=str_replace(',','.',$ldec_monded);
		$ls_formula=$_POST["formula".$z];
		$lb_bool=true;
		$ldec_montoret=$io_evaluate->uf_evaluar($ls_formula,$ldec_objret,$lb_bool);
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
		if($li_status_islr==1)
		{
			$ldec_montoret=$ldec_montoret-$ldec_monded;
		}
		$ldec_totret=$ldec_totret+$ldec_montoret;
		if(array_key_exists("chk".$z,$_POST))
		{
			$la_deducciones["Codded"][$z]=$ls_codded;
			$la_deducciones["MonObjRet"][$z]=$ldec_objret;
			$la_deducciones["MonRet"][$z]=$ldec_montoret;
			$la_deducciones["Dended"][$z]=$ls_dended;
			$la_deducciones["SC_Cuenta"][$z]=$ls_cuenta;
		}
		else
		{
			$la_deducciones["Codded"][$z]='';
			$la_deducciones["MonObjRet"][$z]=0;
			$la_deducciones["MonRet"][$z]=0;
			$la_deducciones["Dended"][$z]='';
			$la_deducciones["SC_Cuenta"][$z]='';
		}
	}
	if($la_deducciones!="")
	{
		//$_SESSION["la_deducciones"]=$la_deducciones;
	}
	else
	{
		if(array_key_exists("la_deducciones",$_SESSION))
		{
			unset($_SESSION["la_deducciones"]);
		}
	
	}
	print "<script languaje=javascript>";
	print " close();";
	print "</script>";
}


?>
<form name="form1" method="post" action="">
  <table width="550" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="127" align="center">    
      
        <div align="right">Documento</div>
      <td width="421" align="center">      <div align="left">
        <input name="txtdocumento" type="text" id="txtdocumento" value="<?php print $ls_documento;?>" style="text-align:center" readonly >
      </div>
    </tr>
    <tr>
      <td align="center">    <div align="right">Total Retenido </div>
      <td align="center">    <div align="left">
        <input name="txttotal" type="text" id="txttotal" value="<?php print number_format($ldec_totret,2,',','.');?>" style="text-align:right" readonly>
      </div>
    </tr>
    <tr>
      <td align="center"><p align="right">&nbsp;  </p>            
      <td align="center"><div align="left">          <a href="javascript: uf_aceptar();"><img src="../shared/imagebank/tools20/aprobado.gif" alt="Aceptar" width="20" height="20" border="0"></a> <a href="javascript: uf_aceptar();">Procesar Deducciones</a> </div>
    </tr>
  </table>
  <p align="center">
    <?Php

$grid->makegrid($totrow,$title,$object,520,'Catalogo de Retenciones',$grid1);

?>
    <span class="Estilo1"></span>
    <input name="operacion" type="hidden" id="operacion">
    <input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
    <input name="monto" type="hidden" id="monto" value="<?php print $ldec_monto_mov;?>">
    <input name="objret" type="hidden" id="objret" value="<?php print $ldec_objret;?>">
    <input name="retenido" type="hidden" id="retenido" value="<?php print $ldec_retenido;?>">
	<input name="municipal" type="hidden" id="municipal" value="<?php print $ls_municipal;?>">
	<input name="fila" type="hidden" id="fila" value="<?php print $ls_fila;?>">
	<input name="porded" type="hidden" id="porded" >
  
  </p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
&nbsp; </body>
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
	ldec_monret=f.txttotal.value;
	f.operacion.value ="PROCESAR";
	eval("opener.document.form1.txtivaret"+f.fila.value+".value=ldec_monret;");	
	f.submit();	
  }
  
  function uf_calcular_monret(mifila)
  {
  	f=document.form1;
	li_total=f.total.value;
	porded=eval("f.txtporded"+mifila+".value;");
	eval("opener.document.form1.txtpor"+f.fila.value+".value="+porded+";");
	for(li_i=1;li_i<=li_total;li_i++)
	{
		if((mifila!=li_i) && (eval("f.chk"+li_i+".checked==true")))
		{
			eval("f.chk"+li_i+".checked=false");
			break;
		}
	}
	f.operacion.value="EVALUAR";
	f.submit();
  }  
</script>
</html>