<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");

$in     = new sigesp_include();
$con    = $in->uf_conectar();
$dat    = $_SESSION["la_empresa"];
$io_msg = new class_mensajes();
$grid   = new grid_param();
$fun    = new class_funciones();
$SQL    = new class_sql($con);
$ds     = new class_datastore();

$arr         = $_SESSION["la_empresa"];
$as_codemp   = $arr["codemp"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Activos para Entrega</title>
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion    = $_POST["operacion"];
		$ls_codigo       = $_POST["codigo"];
		$ls_denominacion = $_POST["nombre"];
	}
	else
	{
		$ls_operacion    = "";
		$ls_codigo       = "";
		$ls_denominacion = "";
	}
?>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <br>
  <div align="center">
    <table width="543" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="6" align="right" class="titulo-celda">		  <div align="center">Cat&aacute;logo de Activos para Entrega </div>
          <div align="left">   	        </div>
          <div align="left">            </div>          <div align="left">
          </div></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td width="112" height="22" align="right">Codigo</td>
        <td width="269" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="15" maxlength="15" style="text-align:center">        
        </div></td>
        <td width="160" height="22" colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="5"><div align="left">
          <input name="nombre" type="text" id="nombre" size="72">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"></td>
        <td height="22" colspan="4"><div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a> <a href="javascript:aceptar();"></a> </div></td>
      </tr>
    </table>
<br>	
	<?php

$title[1]="";	$title[2]="Codigo";   $title[3]="Serial"; $title[4]="Denominacion"; $title[5]="Monto"; 
$grid1="grid";	
if($ls_operacion=="BUSCAR")
{
    $ls_cadena = " SELECT dta.codact, activo.denact, dta.ideact, dta.seract, activo.costo " .
                 "  FROM saf_dta dta ".
                 " JOIN saf_activo activo ON dta.codemp = activo.codemp AND  ".
                 "                           dta.codact = activo.codact ".
                 " WHERE dta.codemp = '".$as_codemp."'AND dta.codact like '%".$ls_codigo."'".
			     "      AND activo.denact like '%".$ls_denominacion."%'  AND dta.estact ='R' AND dta.codact||dta.ideact not in (Select codact||ideact from saf_dt_entrega) ".
			     " ORDER BY dta.codact";
	$rs_cta=$SQL->select($ls_cadena);
	if($rs_cta===false)
	{
		$io_msg->message($fun->uf_convertirmsg($SQL->message));
	}
	else
	{
		$data=$rs_cta;
		if($row=$SQL->fetch_row($rs_cta))
		{
			$data=$SQL->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
			$totrow=$ds->getRowCount("ideact");
			if($totrow>0)
			{
				for($z=1;$z<=$totrow;$z++)
				{
					$codact       = $data["codact"][$z];
					$seract       = $data["ideact"][$z];
					$denominacion = $data["denact"][$z];
					$costo        = $data["costo"][$z];
		            $costo        = number_format($costo,2,",",".");
					$object[$z][1]="<div align=center><img src=../shared/imagebank/tools15/aprobado.gif width=15 height=15  onClick='javascript: ue_agregar(".$z.");'></div>"; 
					$object[$z][2]="<input type=text name=txtcodact".$z." value='".$codact."' id=txtcodact".$z." class=sin-borde readonly style=text-align:center size=15 maxlength=15 >";		
					$object[$z][3]="<input type=text name=txtseract".$z." value='".$seract."' id=txtseract".$z." class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
					$object[$z][4]="<input type=text name=txtdenominacion".$z." value='".$denominacion."' id=txtdenominacion".$z." class=sin-borde readonly style=text-align:left size=25 maxlength=25>";
					$object[$z][5]="<input type=text name=txtcosto".$z." value='".$costo."' id=txtcosto".$z." class=sin-borde readonly style=text-align:right size=25 maxlength=25>";
				}				
			}
			else
			{
					$object[1][1]="<div align=center><img src=../shared/imagebank/tools15/aprobado.gif width=15 height=15  onClick='javascript: ue_agregar();'></div>"; 
					$object[1][2]="<input type=text name=txtcodact1 value='' id=txtcodact1 class=sin-borde readonly style=text-align:center size=15 maxlength=15>";		
					$object[1][3]="<input type=text name=txtseract1 value='' id=txtseract1 class=sin-borde readonly style=text-align:center size=50 maxlength=254>";
					$object[1][4]="<input type=text name=txtdenominacion1 value='' id=txtdenominacion1 class=sin-borde readonly style=text-align:left size=16 maxlength=25>";
					$object[1][5]="<input type=text name=txtcosto1 value='' id=txtcosto1 class=sin-borde readonly style=text-align:right size=25 maxlength=25>";
					$totrow=0;
			}
			$grid->makegrid($totrow,$title,$object,700,'Catalogo de Activos',$grid1);
		}
		else
		{
			$io_msg->message("No hay Activos por Entregar...");
			print "<script language=JavaScript>";
			print " close();";
			print "</script>";
		}
	}
}
print "</table>";
?>
    <input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
  </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(codact,seract,denominacion,monto)
  {
    opener.document.form1.txtcodact.value=codact;
	opener.document.form1.txtseract.value=seract;
	opener.document.form1.txtdenact.value=denominacion;
	opener.document.form1.txtmonact.value=monto;
	close();
  }
function ue_agregar(li_row)
{
	f=document.form1;
	fop=opener.document.form1;
	lb_valido=true;
	li_totrows=f.total.value;
	li_gridtotrows=fop.totalfilas.value;
	ls_codact=eval("f.txtcodact"+li_row+".value"); 
	ls_denact=eval("f.txtdenominacion"+li_row+".value");
    ls_ideact=eval("f.txtseract"+li_row+".value");
	li_monact=eval("f.txtcosto"+li_row+".value");
	for(li_j=1; (li_j<=li_gridtotrows)&& lb_valido; li_j++)
	{
		ls_codactgrid=eval("fop.txtcodact"+li_j+".value");
		ls_ideactgrid=eval("fop.txtidact"+li_j+".value");
		if((ls_codactgrid==ls_codact)&&(ls_ideactgrid==ls_ideact))
		{
			alert("El Activo "+ls_ideactgrid+" ya esta en el Comprobante de Entrega");
			lb_valido=false;
			
		}
	}
	if (lb_valido)
	{
//		li_gridtotrows=eval("opener.document.form1.totalfilas.value");
		ls_codact=eval("f.txtcodact"+li_row+".value"); 
		ls_denact=eval("f.txtdenominacion"+li_row+".value");
		ls_ideact=eval("f.txtseract"+li_row+".value");
		li_monact=eval("f.txtcosto"+li_row+".value");
		obj=eval("fop.txtcodact"+li_gridtotrows+"");
		obj.value=ls_codact;
		obj=eval("fop.txtdenact"+li_gridtotrows+"");
		obj.value=ls_denact;
		obj=eval("fop.txtidact"+li_gridtotrows+"");
		obj.value=ls_ideact;
		obj=eval("fop.txtmonact"+li_gridtotrows+"");
		obj.value=li_monact;
		fop.operacion.value="AGREGARDETALLE";
		fop.submit();
//		close();
	}
}

function ue_search()
{
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_saf_cat_actent.php";
	  f.submit();
}
</script>
</html>