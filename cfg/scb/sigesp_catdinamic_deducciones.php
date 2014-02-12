<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo Deducciones</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body>

<?php
require_once("../../shared/class_folder/sigesp_include.php");
$io_in=new sigesp_include();
$con=$io_in->uf_conectar();

require_once("../../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();

require_once("../../shared/class_folder/class_datastore.php");
$io_ds=new class_datastore();

require_once("../../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);

require_once("../../shared/class_folder/class_funciones.php");
$io_fun=new class_funciones(); 

require_once("../../shared/class_folder/grid_param.php");
$grid=new grid_param();


$la_emp=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
    $li_fila     =$_POST["total"];  
	$ldec_montoobjret=$_POST["monobjret"];
	$ls_documento=$_POST["txtdocumento"];
	$li_totrow=$_POST["cmbfilas"];
}
else
{
	$ls_operacion="";	
	$ldec_montoobjret=$_GET["monobjret"];
	$ls_documento=$_GET["txtdocumento"];
	$li_totrow=$_GET["cmbfilas"];
}
switch ($li_totrow) {
   case 5:
       $lb_5="selected";
       $lb_10="";
       $lb_15="";
       $lb_20="";
       $lb_25="";
       $lb_30="";
       $lb_35="";
       $lb_40="";
       $lb_45="";
       $lb_50="";
       $lb_55="";
       break;
   case 10:
       $lb_5="";
       $lb_10="selected";
       $lb_15="";
       $lb_20="";
       $lb_25="";
       $lb_30="";
       $lb_35="";
       $lb_40="";
       $lb_45="";
       $lb_50="";
       $lb_55="";
       break;
   case 15:
       $lb_5="";
       $lb_10="";
       $lb_15="selected";
       $lb_20="";
       $lb_25="";
       $lb_30="";
       $lb_35="";
       $lb_40="";
       $lb_45="";
       $lb_50="";
       $lb_55="";
       break;
   case 20:
       $lb_5="";
       $lb_10="";
       $lb_15="";
       $lb_20="selected";
       $lb_25="";
       $lb_30="";
       $lb_35="";
       $lb_40="";
       $lb_45="";
       $lb_50="";
       $lb_55="";
       break;
     case 25:
       $lb_5="";
       $lb_10="";
       $lb_15="";
       $lb_20="";
       $lb_25="selected";
       $lb_30="";
       $lb_35="";
       $lb_40="";
       $lb_45="";
       $lb_50="";
       $lb_55="";
       break;
     case 30:
       $lb_5="";
       $lb_10="";
       $lb_15="";
       $lb_20="";
       $lb_25="";
       $lb_30="selected";
       $lb_35="";
       $lb_40="";
       $lb_45="";
       $lb_50="";
       $lb_55="";
       break;
     case 35:
       $lb_5="";
       $lb_10="";
       $lb_15="";
       $lb_20="";
       $lb_25="";
       $lb_30="";
       $lb_35="selected";
       $lb_40="";
       $lb_45="";
       $lb_50="";
       $lb_55="";
       break;
     case 40:
       $lb_5="";
       $lb_10="";
       $lb_15="";
       $lb_20="";
       $lb_25="";
       $lb_30="";
       $lb_35="";
       $lb_40="selected";
       $lb_45="";
       $lb_50="";
       $lb_55="";
       break;
    case 45:
       $lb_5="";
       $lb_10="";
       $lb_15="";
       $lb_20="";
       $lb_25="";
       $lb_30="";
       $lb_35="";
	   $lb_40="";
       $lb_45="selected";
       $lb_50="";
       $lb_55="";
       break;
     case 50:
       $lb_5="";
       $lb_10="";
       $lb_15="";
       $lb_20="";
       $lb_25="";
       $lb_30="";
       $lb_35="";
	   $lb_40="";
	   $lb_45="";
       $lb_50="selected";
       $lb_55="";
       break;
     case 55:
       $lb_5="";
       $lb_10="";
       $lb_15="";
       $lb_20="";
       $lb_25="";
       $lb_30="";
       $lb_35="";
	   $lb_40="";
	   $lb_45="";
       $lb_50="";
       $lb_55="selected";
	   break;
}



?>
<form name="form1" method="post" action="">
  <table width="254" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="252" align="center">N&uacute;mero de Retenciones
        <select name="cmbfilas" id="cmbfilas" onChange="javascript:uf_pintar_filas(cmbfilas.value);">
            <option value="5" <?php print $lb_5;?>>5</option>
            <option value="10" <?php print $lb_10;?>>10</option>
            <option value="15" <?php print $lb_15;?>>15</option>
            <option value="20" <?php print $lb_20;?>>20</option>
            <option value="25" <?php print $lb_25;?>>25</option>
            <option value="30" <?php print $lb_30;?>>30</option>
            <option value="35" <?php print $lb_35;?>>35</option>
            <option value="40" <?php print $lb_40;?>>40</option>
            <option value="45" <?php print $lb_45;?>>45</option>
            <option value="50" <?php print $lb_50;?>>50</option>
            <option value="55" <?php print $lb_55;?>>55</option>
        </select>
      <a href="javascript: uf_aceptar(document.form1.total.value);"><img src="../../shared/imagebank/tools20/aprobado.gif" alt="Aceptar" width="20" height="20" border="0"></a>      </tr>
  </table>
  <p align="center">
    <?php
$title[1]="Check"; $title[2]="Documento"; $title[3]="Código"; $title[4]="Denominación"; $title[5]="Porcentaje"; $title[6]="Monto Obj.Ret.";  $title[7]="Monto Ret.";  
$grid1="grid";	
if($ls_operacion=="")
{
    $ls_codemp=$la_emp["codemp"];
    $ls_sql=" SELECT codcar,dencar,formula,codestpro,spg_cuenta,porcar ".
            " FROM sigesp_cargos ORDER BY codcar ASC";  

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
			$totrow=$io_ds->getRowCount("codcar");
        
			if($totrow>0)
			{
				for($z=1;$z<=$totrow;$z++)
				{
					$ls_codcar=$data["codcar"][$z];
					$ls_dencar=$data["dencar"][$z];
					$ld_porcar=$data["porcar"][$z];
					$ls_formula=$data["formula"][$z];
                   	$object[$z][1]="<input name=chk".$z." type=checkbox id=chk".$z." value=1 class=sin-borde onClick=javascript:uf_calcular_monobjret($z);><input name=formula".$z." type=hidden id=formula".$z." value=".$ls_formula."> ";
					$object[$z][2]="<input type=text name=txtdoc".$z." value='".$ls_documento."'     id=txtdoc".$z." class=sin-borde readonly style=text-align:right  size=17 maxlength=15>";
					$object[$z][3]="<input type=text name=txtcodcar".$z." value='".$ls_codcar."' id=txtcodcar".$z." class=sin-borde readonly style=text-align:center size=15 maxlength=10 >";		
					$object[$z][4]="<input type=text name=txtdencar".$z." value='".$ls_dencar."' id=txtdencar".$z." class=sin-borde readonly style=text-align:left   size=25 maxlength=254>";
					$object[$z][5]="<input type=text name=txtporcar".$z." value='".$ld_porcar."' id=txtporcar".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[$z][6]="<input type=text name=txtmonobjret".$z." value='".number_format($ldec_montoobjret,2,',','.')."' id=txtmonobjret".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20 onBlur=javascript:uf_calcular_monret($z);>";
					$object[$z][7]="<input type=text name=txtmonret".$z." value='".number_format(0,2,',','.')."' id=txtmonret".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";

				}				
			}
			else
			{
                   	$object[1][1]="<input name=chk1 type=checkbox id=chk1 value=1 class=sin-borde>";
					$object[1][2]="<input type=text name=txtdoc1 value=''     id=txtdoc1 class=sin-borde readonly style=text-align:right  size=17 maxlength=15>";
					$object[1][3]="<input type=text name=txtcodcar1 value='' id=txtcodcar1 class=sin-borde readonly style=text-align:center size=15 maxlength=10 >";		
					$object[1][4]="<input type=text name=txtdencar1 value='' id=txtdencar1 class=sin-borde readonly style=text-align:left   size=25 maxlength=254>";
					$object[1][5]="<input type=text name=txtporcar1 value='' id=txtporcar1 class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[1][6]="<input type=text name=txtmonobjret1 value='".number_format(0,2,',','.')."' id=txtmonobjret1 class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					$object[1][7]="<input type=text name=txtmonret1 value='".number_format(0,2,',','.')."' id=txtmonret1 class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";					$totrow=1;
			}

		}
		else
		{
			$io_msg->message("No se han definido deducciones");
		}
	 }
  }
elseif("EVALUAR")
{
	require_once("class_folder\evaluate_formula.php");
	$io_evaluate=new evaluate_formula();
	
	$totrow=$_POST["total"];
	
	for($z=1;$z<=$totrow;$z++)
	{
		
		$ls_codcar=$_POST["txtcodcar".$z];
		$ls_dencar=$_POST["txtdencar".$z];
		$ld_porcar=$_POST["txtporcar".$z];
		$ls_formula=$_POST["formula".$z];
		$ls_documento=$_POST["txtdoc".$z];
		$ldec_montoobjret=$_POST["txtmonobjret".$z];
		$ldec_montoobjret=str_replace('.','',$ldec_montoobjret);
		$ldec_montoobjret=str_replace(',','.',$ldec_montoobjret);
		if(array_key_exists("chk".$z,$_POST))
		{
				$ldec_montoret=$io_evaluate->uf_evaluar($ls_formula,$ldec_montoobjret);
				$lb_sel="checked";
		}
		else
		{
			$ldec_montoret=0;
			$lb_sel="";
		}
		$object[$z][1]="<input name=chk".$z." type=checkbox id=chk".$z." value=1 class=sin-borde onClick=javascript:uf_calcular_monobjret($z); ".$lb_sel."><input name=formula".$z." type=hidden id=formula".$z." value=".$ls_formula."> ";
		$object[$z][2]="<input type=text name=txtdoc".$z." value='".$ls_documento."'     id=txtdoc".$z." class=sin-borde readonly style=text-align:right  size=17 maxlength=15>";
		$object[$z][3]="<input type=text name=txtcodcar".$z." value='".$ls_codcar."' id=txtcodcar".$z." class=sin-borde readonly style=text-align:center size=15 maxlength=10 >";		
		$object[$z][4]="<input type=text name=txtdencar".$z." value='".$ls_dencar."' id=txtdencar".$z." class=sin-borde readonly style=text-align:left   size=25 maxlength=254>";
		$object[$z][5]="<input type=text name=txtporcar".$z." value='".$ld_porcar."' id=txtporcar".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
		$object[$z][6]="<input type=text name=txtmonobjret".$z." value='".number_format($ldec_montoobjret,2,',','.')."' id=txtmonobjret".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20 onBlur=javascript:uf_calcular_monret($z);>";
		$object[$z][7]="<input type=text name=txtmonret".$z." value='".number_format($ldec_montoret,2,',','.')."' id=txtmonret".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
	}
}
$grid->makegrid($totrow,$title,$object,520,'Catalogo de Retenciones',$grid1);
print "</table>";
?>
    <span class="Estilo1"></span>
    <input name="operacion" type="hidden" id="operacion">
    <input name="txtdocumento" type="hidden" id="txtdocumento" value="<?php print $ls_documento;?>">
    <input name="monobjret" type="hidden" id="monobjret" value="<?php print $ldec_montoobjret;?>">
    <input name="total" type="hidden" id="total" value="<?php print $totrow; ?>">
  </p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  /*function aceptar(cuenta,deno,scgcuenta,codest1,codest2,codest3,status)
  {
    opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=deno;
	close();
  }*/

  function uf_pintar_filas(fila)
  {
    var antfilas;
    
    antfilas=eval(opener.document.form1.totrowscarbie.value);
    filas=(eval(antfilas)+eval(fila))-1;

    opener.document.form1.totrowscarbie.value=filas;
    opener.document.form1.operacion.value="PINTARBIECAR";
    opener.document.form1.submit();
  }

	
  function uf_aceptar(fil)
  {
	 
  }
  
  function uf_calcular_monobjret()
  {
  	f=document.form1;
	f.operacion.value="EVALUAR";
	f.action="sigesp_catdinamic_deducciones.php";
	f.submit();
  }




  function uf_select_all()
  {
	  f=document.form1;
	  fop=opener.document.form1;
	  total=f.total.value;
	  sel_all=f.chkall.value;
	  li_sel=0;
	  li_row=0;
	  if(sel_all=='T')
	  {
		  for(i=1;i<=total&&li_sel<=50;i++)	
		  {
			eval("f.chkcta"+i+".checked=true")
			li_sel=li_sel+1;
		  }
		  if(li_sel>50)
		  {
			alert("Se seleccionaran solo 50 cuentas a procesar");
			return ;
		  }
	   }
   }

   function cat_scg()
   {
	   pagina="sigesp_catdinamic_biecar.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
   }

</script>
</html>
