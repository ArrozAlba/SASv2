<?Php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";
   }
$ls_logusr = $_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_r_comp_ret_mun.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_reporte=$io_fun_banco->uf_select_config("SCB","REPORTE","FORMATO_IMPMUN","sigesp_scb_rpp_comp_ret_mun_pdf.php","C");
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break;
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Comprobante de Retencion de Impuesto Municipal</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
.Estilo1 {color: #6699CC}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>
 <?Php
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/ddlb_meses.php");
require_once("../shared/class_folder/sigesp_include.php");

$io_grid  = new grid_param();
$sig_inc  = new sigesp_include();
$con      = $sig_inc->uf_conectar();
$io_fecha = new class_fecha();
$ddlb_mes = new ddlb_meses();
$la_emp   = $_SESSION["la_empresa"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion=$_POST["operacion"];
	 $ls_mes=$_POST["mes"];
	 $ls_agno=$_POST["agno"];
   }
else
   {
	$ls_modageret = $_SESSION["la_empresa"]["modageret"];
	if ($ls_modageret=='C')
	   {
		 echo "<script>";
		 echo "alert('Los comprobantes deben ser visualizados a través del Módulo de Cuentas por Pagar !!!');";
		 echo "location.href='sigespwindow_blank.php';";
		 echo "</script>";
	   }
	$ls_operacion="";
	$li_row=1;
	$li_total=1;
	$ls_numcom="";
	$ls_codigo="";
	$ls_nombre="";
	$ls_direccion="";
	$ls_rif="";
	$arr_fecha=getdate();
	$ls_agno=$arr_fecha["year"];
	$ls_mes=$arr_fecha["mon"];
	$object[$li_row][1]="<div align=center><input type=checkbox name=chksel".$li_row."  id=chksel".$li_row." value=1 style=width:15px;height:15px></div>";
				$object[$li_row][2]="<div align=center><input type=text name=txtnumcom".$li_row."   value='".$ls_numcom."'  class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
				$object[$li_row][3]="<div align=center><input type=text name=txtcodigo".$li_row."   value='".$ls_codigo."'  class=sin-borde readonly style=text-align:center size=10 maxlength=10></div>";
				$object[$li_row][4]="<div align=left><input type=text name=txtnombre".$li_row."   value='".$ls_nombre."'  class=sin-borde readonly style=text-align:left size=30 maxlength=80></div>";
				$object[$li_row][5]="<div align=left><input type=text name=txtdireccion".$li_row."    value='".$ls_direccion."' class=sin-borde readonly style=text-align:left size=40 maxlength=200></div>";
				$object[$li_row][6]="<div align=center><input type=text name=txtrif".$li_row."   value='".$ls_rif."' class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
}

	$lb_selEnero	  = "";
    $lb_selFebrero	  = "";
    $lb_selMarzo      = "";
    $lb_selAbril	  = "";
    $lb_selMayo		  = "";
    $lb_selJunio	  = "";
    $lb_selJulio	  = "";
    $lb_selAgosto	  = "";
    $lb_selSeptiembre = "";
    $lb_selOctubre    = "";
    $lb_selNoviembre  = "";
    $lb_selDiciembre  = "";
	switch ($ls_mes) {
	   case '01':
		   $lb_selEnero="selected";
		   break;
	   case '02':
   		   $lb_selFebrero="selected";
		   break;
	   case '03':
   		   $lb_selMarzo="selected";
		   break;
	   case '04':
   		   $lb_selAbril="selected";
		   break;
	   case '05':
   		   $lb_selMayo="selected";
		   break;
	   case '06':
   		   $lb_selJunio="selected";
		   break;
	   case '07':
		   $lb_selJulio="selected";
		   break;
	   case '08':
		   $lb_selAgosto="selected";
		   break;
	   case '09':
 		   $lb_selSeptiembre="selected";
		   break;
	   case '10':
		   $lb_selOctubre="selected";
		   break;
	   case '11':
		   $lb_selNoviembre="selected";
		   break;
	   case '12':
		   $lb_selDiciembre="selected";
		   break;
	}

   if($ls_operacion=="CARGAR_DT")
   {
   		uf_cargar_docs($object,$li_total,$ls_mes,$ls_agno);
   }
   $title[1]="<input name=chkall type=checkbox id=chkall value=1 style=width:15px;height:15px class=sin-borde onClick=javascript:uf_select_all();>";
   $title[2]="Comprobante";
   $title[3]="Código/Cédula";
   $title[4]="Nombre";
   $title[5]="Dirección";
   $title[6]="R.I.F.";
   $grid="grid";

	function uf_cargar_docs(&$object,&$li_row,$ls_mes,$ls_agno)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Metodo: uf_cargar_bancos
	//	Access:  public
	//	Returns: $object=  Arreglo con las cabeceras de los comprobantes de pago
	//
	//	Description:  Función que se encarga de seleccionar los   bancos y retornarlos en un arreglo de object
	//
	//////////////////////////////////////////////////////////////////////////////
	  $ls_codemp=$_SESSION["la_empresa"]["codemp"];
	  $li_row=0;
	  global $con,$io_fecha;
	  require_once("../shared/class_folder/class_sql.php");
	  require_once("../shared/class_folder/class_funciones.php");
	  $io_sql=new class_sql($con);
	  $io_fun=new class_funciones();
	  $ls_fechainicio = "$ls_agno-$ls_mes-01";
	  $ls_fechafin    = $io_fun->uf_convertirdatetobd($io_fecha->uf_last_day($ls_mes,$ls_agno));

	  $ls_sql="SELECT numcom, codsujret, nomsujret, dirsujret, rif
	  		     FROM scb_cmp_ret
			    WHERE fecrep BETWEEN '$ls_fechainicio' AND '$ls_fechafin'
			      AND codret='0000000003'
			   ORDER BY numcom";

	   $rs_data=$io_sql->select($ls_sql);

	   if (($rs_data===false))
	   {
			$lb_valido=false;
	   }
	   else
	   {
		   while($row=$io_sql->fetch_row($rs_data))
		        {
				  $li_row       = $li_row+1;
				  $ls_numcom    = $row["numcom"];
			 	  $ls_codigo    = $row["codsujret"];
				  $ls_nombre    = $row["nomsujret"];
				  $ls_direccion = $row["dirsujret"];
				  $ls_rif       = $row["rif"];
				  $object[$li_row][1]="<div align=center><input type=checkbox name=chksel".$li_row."  id=chksel".$li_row." value=1 style=width:15px;height:15px></div>";
				  $object[$li_row][2]="<div align=center><input type=text name=txtnumcom".$li_row."   value='".$ls_numcom."'  class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
				  $object[$li_row][3]="<div align=center><input type=text name=txtcodigo".$li_row."   value='".$ls_codigo."'  class=sin-borde readonly style=text-align:center size=10 maxlength=10></div>";
				  $object[$li_row][4]="<div align=left><input type=text name=txtnombre".$li_row."   value='".$ls_nombre."'  class=sin-borde readonly style=text-align:left size=30 maxlength=80></div>";
				  $object[$li_row][5]="<div align=left><input type=text name=txtdireccion".$li_row."    value='".$ls_direccion."' class=sin-borde readonly style=text-align:left size=40 maxlength=200></div>";
				  $object[$li_row][6]="<div align=center><input type=text name=txtrif".$li_row."   value='".$ls_rif."' class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
		        }
		  if($li_row==0)
		   {
				$li_row=1;
				$ls_numcom="";
				$ls_codigo="";
				$ls_nombre="";
				$ls_direccion="";
				$ls_rif="";
				$object[$li_row][1]="<div align=center><input type=checkbox name=chksel".$li_row."  id=chksel".$li_row." value=1 style=width:15px;height:15px></div>";
				$object[$li_row][2]="<div align=center><input type=text name=txtnumcom".$li_row."   value='".$ls_numcom."'  class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
				$object[$li_row][3]="<div align=center><input type=text name=txtcodigo".$li_row."   value='".$ls_codigo."'  class=sin-borde readonly style=text-align:center size=10 maxlength=10></div>";
				$object[$li_row][4]="<div align=left><input type=text name=txtnombre".$li_row."   value='".$ls_nombre."'  class=sin-borde readonly style=text-align:left size=30 maxlength=80></div>";
				$object[$li_row][5]="<div align=left><input type=text name=txtdireccion".$li_row."    value='".$ls_direccion."' class=sin-borde readonly style=text-align:left size=40 maxlength=200></div>";
				$object[$li_row][6]="<div align=center><input type=text name=txtrif".$li_row."   value='".$ls_rif."' class=sin-borde readonly style=text-align:center size=15 maxlength=15></div>";
		   }
		   $io_sql->free_result($rs_data);
	   }
	}//fin de uf_cargar_bancos
?>
</div>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>

  <table width="524" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">

    <tr>
      <td width="58"></td>
    </tr>
    <tr class="titulo-ventana">
      <td height="22" colspan="2" align="center">Comprobantes de Retenci&oacute;n de Impuesto Municipal  </td>
    </tr>
    <tr style="visibility:hidden">
      <td height="13" colspan="2" style="text-align:left">Reporte en
        <select name="cmbbsf" id="cmbbsf">
          <option value="0" selected>Bs.</option>
          <option value="1">Bs.F.</option>
        </select>      </td>
    </tr>
    <tr>
      <td height="13" colspan="2" align="center"><table width="398" border="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celda">
          <td colspan="4"><div align="center"><strong>Periodo</strong></div></td>
        </tr>
        <tr>
          <td width="66" height="22" style="text-align:right">Mes</td>
          <td width="113"><div align="left">
              <select name="mes" id="mes">
                <option value="01" <?php print $lb_selEnero; ?>>ENERO</option>
                <option value="02" <?php print $lb_selFebrero; ?>>FEBRERO</option>
                <option value="03" <?php print $lb_selMarzo; ?>>MARZO</option>
                <option value="04" <?php print $lb_selAbril; ?>>ABRIL</option>
                <option value="05" <?php print $lb_selMayo; ?>>MAYO</option>
                <option value="06" <?php print $lb_selJunio; ?>>JUNIO</option>
                <option value="07" <?php print $lb_selJulio; ?>>JULIO</option>
                <option value="08" <?php print $lb_selAgosto; ?>>AGOSTO</option>
                <option value="09" <?php print $lb_selSeptiembre; ?>>SEPTIEMBRE</option>
                <option value="10" <?php print $lb_selOctubre; ?>>OCTUBRE</option>
                <option value="11" <?php print $lb_selNoviembre; ?>>NOVIEMBRE</option>
                <option value="12" <?php print $lb_selDiciembre; ?>>DICIEMBRE</option>
            </select>
          </div></td>
          <td width="88" style="text-align:right">A&ntilde;o</td>
          <td width="121"><div align="left">
              <input name="agno" type="text" id="agno" style="text-align:center " value="<?php print $ls_agno;?>" size="10" maxlength="4">
          </div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="13" colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center">     <div align="left"><span class="Estilo1">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></div></td>
      <td width="442" align="center"><div align="left"><a href="javascript: uf_cargar_dt();">Cargar Movimientos</a></div></td>
    </tr>
    <tr>
      <td height="18" colspan="2" align="center">
        <p>
          <?php $io_grid->makegrid($li_total,$title,$object,400,'Comprobantes de Retención Municipal',$grid);?></p>
        </td>
    </tr>
  </table>

</table>

<input name="total" type="hidden" id="total" value="<?php print $li_total;?>">
<input name="formato"    type="hidden" id="formato"    value="<?php print $ls_reporte; ?>">
</p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_imprimir()
{
  f			 	 = document.form1;
  li_total       = f.total.value;
  ls_documentos  = "";
  ls_mes		 = f.mes.value;
  ls_agno	     = f.agno.value;
  ls_tiporeporte = f.cmbbsf.value;
  li_imprimir    = f.imprimir.value;
  if (li_imprimir=='1')
	 {
	   for (li_i=1;li_i<=li_total;li_i++)
		   {
			 ls_numcom=eval("f.txtnumcom"+li_i+".value");
			 if (eval("f.chksel"+li_i+".checked==true"))
				{
				  if (ls_documentos.length>0)
					 {
					   ls_documentos=ls_documentos+"-"+ls_numcom;
					 }
				  else
					 {
					   ls_documentos=ls_numcom;
					 }
				}
		   }
		if (ls_documentos.length>0)
		   {
		   	 formato=f.formato.value;
			 pagina="reportes/"+formato+"?documentos="+ls_documentos+"&mes="+ls_mes+"&agno="+ls_agno+"&tiporeporte="+ls_tiporeporte;
			 window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		   }
		else
		   {
			 alert("Debe seleccionar al menos un documento !!!");
		   }
	 }
  else
	 {
	   alert("No tiene permiso para realizar esta operación !!!");
	 }
}

	function uf_cargar_dt()
	{
		f=document.form1;
		ls_mes=f.mes.value;
		ls_agno=f.agno.value;
		if((ls_mes!="")&&(ls_agno!=""))
		{
			f.operacion.value='CARGAR_DT';
			f.submit();
		}
		else
		{
			alert("Seleccione los parámetros de búsqueda !!!");
		}
	}
	function uf_select_all()
	{
		  f=document.form1;
		  total=f.total.value;
		  sel_all=f.chkall.value;
		  if(f.chkall.checked)
		  {
			  for(i=1;i<=total;i++)
			  {
				eval("f.chksel"+i+".checked=true");
			  }
		 }
		 else
		 {
			 for(i=1;i<=total;i++)
			  {
				eval("f.chksel"+i+".checked=false");
			  }
		 }
	}


function currencyDate(date)
  {
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;

		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string,10);

			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}

		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string,10);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
   }
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>