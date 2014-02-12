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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_elimin_anulado.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Eliminaci&oacute;n de Cheques Anulados Monto Cero</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/report.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/valida_fecha.js"></script>
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
  <?php
  require_once("../shared/class_folder/grid_param.php");
  require_once("../shared/class_folder/ddlb_generic.php");
  require_once("sigesp_scb_c_elimin_anulado.php");  
  require_once("sigesp_scb_c_movbanco.php");  
  $io_grid 			    = new grid_param();
  $in_class_contabiliza = new sigesp_scb_c_elimin_anulado();
  $in_movbco 			= new sigesp_scb_c_movbanco($la_seguridad);
  if (array_key_exists("operacion",$_POST))
     {
       $ls_operacion	 = $_POST["operacion"];
	   $ls_operacion_bco = 'CH';
	   $li_total_record  = $_POST["hide_total_row"];
	   $ls_numdoc		 = $_POST["txtnumdoc"]; 
	   $ld_fecha		 = $_POST["txtfecha"];
     }
  else
     {
       $ls_operacion     = "";
	   $ls_operacion_bco = "CH";
	   $li_total_record  = 0;
	   $ls_numdoc=""; 
	   $ld_fecha=""; 
	   $li_total=1;
	
	for($li_row=1;$li_row<=$li_total;$li_row++)
	{
		$arr_object[$li_row][1] = "<input type=checkbox name=chksel".$li_row."   id=chksel".$li_row." value=1 style=width:15px;height:15px onClick='return false;'>";		
		$arr_object[$li_row][2] = "<input type=text     name=txtnumdoc".$li_row."       value='' class=sin-borde readonly style=text-align:center size=17 maxlength=15>";
		$arr_object[$li_row][3] = "<input type=text     name=txtfecmov".$li_row."       value='' class=sin-borde readonly style=text-align:left size=12 maxlength=10>";
		$arr_object[$li_row][4] = "<input type=text     name=txtconmov".$li_row."       value='' class=sin-borde readonly style=text-align:center size=60 maxlength=60>".
								  "<input type=hidden   name=txtcodban".$li_row."       value='' >".
								  "<input type=hidden   name=txtctaban".$li_row."       value='' >".
								  "<input type=hidden   name=txtestmov".$li_row."       value='' >";
		
	}
	$li_row=$li_total;
  }
  $la_value=array("ND","NC","CH","DP","RE");
   if ($ls_operacion=="PROCESAR")  
  {  
		for($li_i=1;$li_i<=$li_total_record;$li_i++)
		{
			if(array_key_exists("chksel".$li_i,$_POST))
			{
				$ls_docum=$_POST["txtnumdoc".$li_i];
				$ls_estcon=$_POST["estcon".$li_i];
				if($ls_estcon==0)
				{
					$ls_conmov=$_POST["txtconmov".$li_i];
					$ls_codban=$_POST["txtcodban".$li_i];
					$ls_ctaban=$_POST["txtctaban".$li_i];
					$ls_estmov=$_POST["txtestmov".$li_i];
					$lb_valido = $in_movbco->uf_delete_anulado($ls_docum,$ls_codban,$ls_ctaban,'CH',$ls_estmov);
					if($lb_valido)		
					{
						$in_movbco->msg->message("La anulación del cheque $ls_docum fue eliminada");
					}
					else
					{
						$in_movbco->msg->message("Error en la eliminación de la anulación del cheque $ls_docum.");
					}
				}
				else
				{
					$in_movbco->msg->message("El cheque $ls_docum no puede ser eliminado, ya fue Conciliado");
				}		
			}
		}
	  $ls_operacion='CARGAR_DT';
  }  
  unset($in_movbco);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="781" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
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
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Procesar" title="Procesar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></td>	
  </tr>
</table>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<p>&nbsp;</p>
  <table width="502" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="4">Eliminaci&oacute;n de Cheques Anulados Monto Cero </td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td width="76"><div align="right">Documento</div></td>
      <td width="251"><div align="left">
        <input name="txtnumdoc" type="text" id="txtnumdoc" style="text-align:center" value="<?php print $ls_numdoc;?>" size="20" maxlength="15">
      </div></td>
      <td width="72"><div align="right">Fecha</div></td>
      <td width="101"><div align="left">
        <input name="txtfecha" type="text" id="txtfecha" style="text-align:center" value="<?php print $ld_fecha;?>" size="15" maxlength="10" datepicker="true" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);">
      </div></td>
    </tr>
    <tr>
      <td colspan="4" style="text-align:left"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar..." width="20" height="20" border="0">Buscar</a></td>
    </tr>
    <tr>
      <td colspan="4"><div align="center">
	  <?php
	     $title[1]='&nbsp;&nbsp;&nbsp;';$title[2]="Nº Documento";$title[3]="Fecha Movimiento";$title[4]="Concepto"; 
		 if($ls_operacion=='CARGAR_DT')
		 {
	     	$in_class_contabiliza->uf_select_banco_contabilizar( $ls_operacion_bco, $arr_object ,$li_total_record,"A",$ls_numdoc,$ld_fecha);
		 }
		 $io_grid->makegrid($li_total_record,$title,$arr_object,500,"Movimientos de Banco Anulados","grdsep" );
	  ?>
	  </div></td>
    </tr>
    <tr>
      <td colspan="4"><input name="operacion" type="hidden" id="operacion">
      <input name="hide_total_row" type="hidden" id="hide_total_row" value="<?php print $li_total_record;?>"></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		if(confirm("Está seguro de eliminar este(os) registro(s)?\n  Esta operación no puede reversarse"))
		{
			f.operacion.value ="PROCESAR";
  		    f.action="sigesp_scb_p_elimin_anulado.php";
			f.submit();
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_search()
{
	f=document.form1;
	f.operacion.value ="CARGAR_DT";
	f.action="sigesp_scb_p_elimin_anulado.php";
	f.submit();
}
function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>