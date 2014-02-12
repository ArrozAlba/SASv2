<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="javascript" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="javascript1.2" src="js/valida_tecla_grid.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style2 {font-size: 11px}
.Estilo1 {font-weight: bold}
.Estilo2 {font-size: 15px}
-->
</style>
</head>
<body>
<?php
$dat=$_SESSION["la_empresa"];
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
require_once("../shared/class_folder/grid_param.php");

$io_function=new class_funciones();	
$io_include=new sigesp_include();	
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_msg = new class_mensajes();

require_once("sigesp_spg_class_apertura.php");
$io_class_apertura=new sigesp_spg_class_apertura();
require_once("../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad= new sigesp_c_seguridad();
$ds_aper=new class_datastore();
$io_class_grid=new grid_param();
/////////////////////////////////////Parametros necesarios para seguridad////////////////////////////
	$ls_empresa=$dat["codemp"];
	$li_estmodest=$dat["estmodest"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
	$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
	$ls_logusr="";
	}
	$ls_sistema="SPG";
	$ls_ventana="sigesp_spg_p_apertura.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventana;
//////////////////////////////////////////////////////////////////////////////////////////////////
if (array_key_exists("operacion",$_POST))
{
 $ls_operacion=$_POST["operacion"];	
 $ls_estpro1=$io_function->uf_cerosizquierda($_POST["codestpro1"],25);
 $ls_estpro2=$io_function->uf_cerosizquierda($_POST["codestpro2"],25);
 $ls_estpro3=$io_function->uf_cerosizquierda($_POST["codestpro3"],25);
 if($li_estmodest==2)
 {
  $ls_estpro4=$io_function->uf_cerosizquierda($_POST["codestpro4"],25);
  $ls_estpro5=$io_function->uf_cerosizquierda($_POST["codestpro5"],25);
 }
 else
 {
  $ls_estpro4=$io_function->uf_cerosizquierda("0",25);
  $ls_estpro5=$io_function->uf_cerosizquierda("0",25);
 } 
 $ls_estcla =$_POST["estcla"];
 $ls_cuentaplan=$_POST["txtcuenta"];
 $ls_denominacion=$_POST["txtdenominacion"];
 $ld_asignado=$_POST["txtasignado"];
}
else
{
  $ls_operacion="";
  $ls_estpro1= $io_function->uf_cerosizquierda($_GET["codestpro1"],25);
  $ls_estpro2= $io_function->uf_cerosizquierda($_GET["codestpro2"],25);
  $ls_estpro3= $io_function->uf_cerosizquierda($_GET["codestpro3"],25);
  $ls_estpro4= $io_function->uf_cerosizquierda($_GET["codestpro4"],25);
  $ls_estpro5= $io_function->uf_cerosizquierda($_GET["codestpro5"],25);
  $ls_estcla =$_GET["estcla"];
  $ls_cuentaplan=$_GET["spg_cuenta"];
  $ls_denominacion=$_GET["denominacion"];
  $ld_asignado=$_GET["asignacion"];

}
if  (array_key_exists("radiobutton",$_POST))
	{
	  $ls_distribucion=$_POST["radiobutton"];
    }
else
	{
	  $ls_distribucion="A";
	}
if(array_key_exists("li_totnum",$_POST))
{
  $li_totnum=$_POST["li_totnum"];
}
else
{
  $li_totnum=0;
}
?>
<form method="post" name="form1" action=""> 
<table width="583" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
   <td height="22" colspan="4" class="titulo-celda">Distribuci&oacute;n de la Fuente de Financiamiento </td>
  </tr>
  <tr>
    <?php 
	  $li_estmodest  = $dat["estmodest"];
	  $ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	  $ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	  $ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	  $ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	  $ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
	  if($li_estmodest==1)
	  {
	  ?>
    <td width="119" height="22"><div align="right"><?php print $dat["nomestpro1"];  ?></div></td>
    <td colspan="3"><input name="codestpro1" type="text" id="codestpro1" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" style="text-align:center" value="<?php print substr($ls_estpro1,-$ls_loncodestpro1); ?>" readonly>
        <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" size="53" readonly>
        <div align="left"> </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro2"] ; ?></div></td>
    <td colspan="3"><input name="codestpro2" type="text" id="codestpro2" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" style="text-align:center" value="<?php print substr($ls_estpro2,-$ls_loncodestpro2); ?>" readonly>
        <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" size="53" readonly></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro3"] ; ?></div></td>
    <td colspan="3"><div align="left">
        <input name="codestpro3" type="text" id="codestpro3" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" style="text-align:center" value="<?php print substr($ls_estpro3,-$ls_loncodestpro3); ?>" readonly>
        <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" size="53" readonly>
    </div></td>
  </tr>
  <? }
  elseif($li_estmodest==2)
  {	
  ?>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro4"] ; ?></div></td>
    <td colspan="3"><div align="left">
        <input name="codestpro4" type="text" id="codestpro4" size="<?php print $ls_loncodestpro4; ?>" maxlength="<?php print $ls_loncodestpro4; ?>" style="text-align:center" value="<?php print substr($ls_estpro4,-$ls_loncodestpro4); ?>"readonly>
        <input name="denestpro4" type="text" class="sin-borde" id="denestpro4" size="53" readonly>
    </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro5"] ; ?></div></td>
    <td colspan="3"><div align="left">
        <input name="codestpro5" type="text" id="codestpro5" size="<?php print $ls_loncodestpro5 ?>" maxlength="<?php print $ls_loncodestpro5 ?>" style="text-align:center" value="<?php print substr($ls_estpro5,-$ls_loncodestpro5); ?>" readonly>
        <input name="denestpro5" type="text" class="sin-borde" id="denestpro5" size="53" readonly>
    </div></td>
  </tr>
  	<?php
	}
	?>
  <tr>
    <td height="22"><div align="right">Cuenta</div></td>
    <td colspan="3"><input name="txtcuenta" type="text" id="txtcuenta" readonly="true" value="<?php print $ls_cuentaplan ?>" size="25" style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890');">
        <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion3" style="text-align:left" value="<?php print $ls_denominacion ?>" size="50" maxlength="254"></td>
  </tr>
  <tr>
    <td height="13"><div align="right">Monto Asignado: </div></td>
    <td colspan="3"><input name="txtasignado" type="text" id="txtasignado" readonly="true" value="<?php print $ld_asignado ?>" size="25" style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890');"></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Distribuci&oacute;n</div></td>
    <?php 	 
		  if($ls_distribucion=="A")
		  {
				$ls_auto="";
				$ls_manual="";
		  }
		  elseif($ls_distribucion=="M")
		  {
				$ls_auto="";
				$ls_manual="checked";
		  }
		  ?>
    <td width="95"><input name="radiobutton" type="radio" value="A" <?php print $ls_auto ?> onClick='ue_distribuir()'>
      Automatico</td>
    <td width="69"><input name="radiobutton" type="radio" value="M" <?php print $ls_manual ?>>
      Manual </td>
   <!-- <td width="298"><a href="javascript:ue_distribuir();"><img src="../shared/imagebank/tools15/actualizar(1).gif" width="15" height="15" class="sin-borde"></a></td> -->
  </tr>
  <tr>
    <td height="13" colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td height="13" colspan="4"><div align="center">
      <?php
	//Titulos de la tabla
  $title[1]="Codigo";   $title[2]="Fuente Financiamiento";  $title[3]="Asignaci&oacute;n"; 
  $ls_nombre="grid_disfuefin";
  if ($ls_operacion == "")
  { 
   $rs_load=$io_class_apertura->uf_spg_load_fuefin_estructura($ls_empresa,$ls_estpro1,$ls_estpro2,$ls_estpro3,$ls_estpro4,$ls_estpro5,$ls_estcla,$ls_cuentaplan);
   if($row=$io_sql->fetch_row($rs_load))
   {
    $data=$io_sql->obtener_datos($rs_load);
    $ds_aper->data=$data;
    $li_num=$ds_aper->getRowCount("codfuefin");
    $li_totnum=$li_num;
    for($i=1;$i<=$li_num;$i++)
    {    
        $ls_codfuefin = $data["codfuefin"][$i]; 
		$ls_denfuefin = $data["denfuefin"][$i];
		$ld_monto =number_format($data["monto"][$i],2,",",".");
		$object[$i][1]="<input type=text name=txtCodfuefin".$i." value=$ls_codfuefin class=sin-borde readonly><input name=codfuefin".$i." type=hidden id=codfuefin value='$ls_codfuefin'>";
		$object[$i][2]="<input type=text name=txtDenfuefin".$i." value='$ls_denfuefin' size=50 class=sin-borde readonly >";
		$object[$i][3]="<input type=text name=txtMonto".$i." onBlur=uf_format(this,$i) onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)' value=$ld_monto class=sin-borde style=text-align:right onClick='calcular_porasignar()'>";
		
    }//for
    //$io_class_grid->make_gridScroll($li_totnum,$title,$object,610,'DISTRIBUCION DE LA FUENTE DE FINANCIAMIENTO',$ls_nombre,245);     
	$io_class_grid->make_gridScroll($li_totnum,$title,$object,550,'DISTRIBUCION DE LA FUENTE DE FINANCIAMIENTO',$ls_nombre,150);     
  }//if
  else
  {
    $io_msg->message("No se han definido Fuentes de Financimiento para la Estructura Seleccionada");
	print "<script language='JavaScript'>";
	print "close()";
	print "</script>";
  }
 }
  
 if($ls_operacion=="GUARDARDISFUEFIN")
 {
   $li_num=$_POST["li_totnum"];
   $lb_valido=true;
   $lb_existe=true;
   $ls_cuenta=$_POST["txtcuenta"];
   $ls_estpro1=$_POST["codestpro1"];
   $ls_estpro2=$_POST["codestpro2"];
   $ls_estpro3=$_POST["codestpro3"];
   if($li_estmodest==2)
   {
    $ls_estpro4=$_POST["codestpro4"];
    $ls_estpro5=$_POST["codestpro5"];
   }
   elseif($li_estmodest == 1)
   {
    $ls_estpro4="0000000000000000000000000";
    $ls_estpro5="0000000000000000000000000";
   }
   
   if($li_estmodest==2)
  {
	    $ls_codestpro1=$io_function->uf_cerosizquierda($ls_estpro1,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro2=$io_function->uf_cerosizquierda($ls_estpro2,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro3=$io_function->uf_cerosizquierda($ls_estpro3,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro4=$io_function->uf_cerosizquierda($ls_estpro4,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro5=$io_function->uf_cerosizquierda($ls_estpro5,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
  }
  else
  {
	    $ls_codestpro1=$io_function->uf_cerosizquierda($ls_estpro1,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro2=$io_function->uf_cerosizquierda($ls_estpro2,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro3=$io_function->uf_cerosizquierda($ls_estpro3,25); // CAMBIO DE TAMAÑO DE LA ESTRUCTURA PROGRAMATICA
	    $ls_codestpro4=$io_function->uf_cerosizquierda(0,25);
	    $ls_codestpro5=$io_function->uf_cerosizquierda(0,25);
  }	
   $ls_estcla =$_POST["estcla"];
   for($i=1;$i<=$li_num;$i++)
   { 
    $ls_codfuefin=$_POST["txtCodfuefin".$i];
	$ls_denfuefin=$_POST["txtDenfuefin".$i];
	$ld_monto=$_POST["txtMonto".$i];
	$ld_monto=str_replace('.','',$ld_monto);
    $ld_monto=str_replace(',','.',$ld_monto);
	$object[$i][1]="<input type=text name=txtCodfuefin".$i." value=$ls_codfuefin class=sin-borde readonly><input name=codfuefin".$i." type=hidden id=codfuefin value='$ls_codfuefin'>";
	$object[$i][2]="<input type=text name=txtDenfuefin".$i." value='$ls_denfuefin' size=50 class=sin-borde readonly >";
	$object[$i][3]="<input type=text name=txtMonto".$i." onBlur=uf_format(this,$i) onKeyPress='return keyRestrictgrid(event)'  onKeyUp='ue_validarcomas_puntos(this)' value=".number_format($ld_monto,2,",",".")." class=sin-borde style=text-align:right onClick='calcular_porasignar()'>";	
	$lb_existe =  $io_class_apertura->uf_spg_existe_fuefin_estructura($ls_empresa,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_cuenta,$ls_codfuefin);
	if(!$lb_existe)
	{
	 $lb_existe = $io_class_apertura->uf_spg_existe_fuefin_estructura($ls_empresa,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_cuenta,"--");
	 if(!$lb_existe)
	 {
	  $lb_valido=$io_class_apertura->uf_spg_insert_fuefin_estructura($ls_empresa,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_cuenta,"--",0); 
	 }
	 if($lb_valido)
	 {
	  $lb_valido=$io_class_apertura->uf_spg_insert_fuefin_estructura($ls_empresa,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_cuenta,$ls_codfuefin,$ld_monto);
	 } 
	}
	else
	{
	 $lb_valido=$io_class_apertura->uf_spg_update_fuefin_estructura($ls_empresa,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_cuenta,$ls_codfuefin,$ld_monto);
	} 
   } 
   if($lb_valido)
   {
     $io_msg->message("La Distribucion de lo Asignado por Fuente de Financiamiento fue registrado con Exito.....");
	  $ls_evento="PROCESS";
	  $ls_estrutura = "";
	  if($li_estmodest==1)
	  {
	   $ls_estrutura = substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3);
	  }
	  elseif($li_estmodest==1)
	  {
	   $ls_estrutura = substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3)."-".substr($ls_codestpro4,-$ls_loncodestpro4)."-".substr($ls_codestpro5,-$ls_loncodestpro5);
	  } 
	   
	  $ls_desc_event="Se asigno monto a las Fuentes de Financiamiento de la Cuenta de Gasto ".$ls_cuenta." de la Estructura Presupuestaria ".$ls_estrutura;
	  $ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
   }
   else
   {
    $io_msg->message("Ocurrio un Error en la Distribucion de lo Asignado por Fuente de Financiamiento.....");
   }
   $io_class_grid->make_gridScroll($li_totnum,$title,$object,550,'DISTRIBUCION DE LA FUENTE DE FINANCIAMIENTO',$ls_nombre,150); 
 }
?>
    </div></td>
  </tr>
  <tr>
    <td height="13" colspan="3"><div align="right">Por Asignar: </div></td>
    <td height="13"><input name="txtporasignar" type="text" id="txtporasignar" readonly="true" size="25" style="text-align:center"></td>
  </tr>
  <tr>
    <td height="13" colspan="4">
	  <div align="center">
	    <p>&nbsp;</p>
	    <p><span class="Estilo2"><a href="javascript:guardar_disfuefin();"><img src="../shared/imagebank/tools/ejecutar.gif" alt="Agregar Detalle Presupuestario" width="20" height="20" border="0"></a> <a href="javascript: close();"><img src="../shared/imagebank/iconos/salir.gif" alt="Cancelar Registro de Detalle Presupuestario" width="20" height="20" border="0"></a></span></p>
	  </div></td>
    </tr>
  <tr>
    <td height="13" colspan="4"><div align="center">
      <input name="operacion" type="hidden" id="operacion">
      <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo;?>">
      <span class="Estilo1">
        <input name="estmodest" type="hidden" id="estmodest" value="<?php print $li_estmodest; ?>">
        <input name="estcla"    type="hidden" id="estcla" value="<?php print $ls_estcla; ?>">
        <span class="Estilo2">
          <input name="li_totnum" type="hidden" id="li_totnum" value="<?php print $li_totnum; ?>">
        </span></span></div></td>
    </tr>
</table>
</form>
</body>
<script language="JavaScript">
  function guardar_disfuefin()
  {
  	f=document.form1;
	ld_suma = 0;
	li_total=f.li_totnum.value;
	txtasignado="txtasignado";
	ld_asignado=eval("f."+txtasignado+".value");
	ld_asignado=uf_convertir_monto(ld_asignado);
	
	for (li=1;li<=li_total;li++)
	{
	 txtmonto="txtMonto"+li;
	 ld_monto=eval("f."+txtmonto+".value");
	 ld_monto=uf_convertir_monto(ld_monto);
	 ld_suma = ld_suma +  parseFloat(ld_monto);
	}
	ld_suma=uf_convertir_monto(ld_suma);
	if(parseFloat(ld_suma) < parseFloat(ld_asignado))
	{
	 alert("La Suma de los Montos es menor al Total Asignado, verifique por favor!!");
	}
	else if (parseFloat(ld_suma) > parseFloat(ld_asignado))
	{
	 alert("La Suma de los Montos es mayor al Total Asignado, verifique por favor!!");
	}
	else
	{
	 f.operacion.value="GUARDARDISFUEFIN";
	 f.action="sigesp_w_regdt_distfuefin.php";
	 f.submit();
	} 
  }
  function uf_close()
  {
	  close()
  }
  
function calcular_porasignar()
  {
  	f=document.form1;
	ld_suma = 0;
	li_total=f.li_totnum.value;
	txtasignado="txtasignado";
	ld_asignado=eval("f."+txtasignado+".value");
	ld_asignado=uf_convertir_monto(ld_asignado);
	
	for (li=1;li<=li_total;li++)
	{
	 txtmonto="txtMonto"+li;
	 ld_monto=eval("f."+txtmonto+".value");
	 ld_monto=uf_convertir_monto(ld_monto);
	 ld_suma = ld_suma + parseFloat(ld_monto);
	}
	ld_suma=uf_convertir_monto(ld_suma);
	ld_suma=parseFloat(ld_suma);
	ld_porasignar = ld_asignado - ld_suma;
	formatNumber(f.txtporasignar,ld_porasignar);
  }

//Funciones de validacion de fecha.

function  uf_format(obj,fila)
{

	f=document.form1;
	ldec_temp1=obj.value;
	if((ldec_temp1=="")||(ldec_temp1==".")||(ldec_temp1==","))
	{
	  obj.value="0,00";
	  obj.focus();
	}
	
	if(ldec_temp1.indexOf('.')<0)
	{
	   ldec_temp1=ldec_temp1+".00"
	}
	if(ldec_temp1.indexOf(',')<0)
    {
	  ldec_temp1=ldec_temp1.replace(",",".");
    }
	
	if((ldec_temp1.indexOf('.')>0)||(ldec_temp1.indexOf(',')>0))
	{
		obj.value=uf_convertir(ldec_temp1);
		
		var ld_monto;
		lz=parseInt(fila)+1;
		txtmonto="txtMonto"+fila;
		ld_monto=eval("f."+txtmonto+".value");  
		ld_monto=parseFloat(uf_convertir_monto(ld_monto));
	 }	
  
}

function formatNumber(obj,num){
	f=document.form1;
	//alert(num);
	obj.value = num;
	ldec_temp1=obj.value;
	if((ldec_temp1=="")||(ldec_temp1==".")||(ldec_temp1==","))
	{
	  obj.value="0,00";
	}
	
	if(ldec_temp1.indexOf('.')<0)
	{
	   ldec_temp1=ldec_temp1+".00"
	}
	if(ldec_temp1.indexOf(',')<0)
    {
	  ldec_temp1=ldec_temp1.replace(",",".");
    }
	
	if((ldec_temp1.indexOf('.')>0)||(ldec_temp1.indexOf(',')>0))
	{
		obj.value=uf_convertir(ldec_temp1);
	}	
}

function currency_Format(fld, milSep, decSep, e) { 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789-'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Enter 
	if (whichCode == 127) return true; // Enter 	
	if (whichCode == 9) return true; // Enter 	
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
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
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
   
 function ue_validarcomas_puntos(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto != ",")&&(texto != '.'))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}

function ue_distribuir()
{
   var i ;
   f=document.form1;
		for (i=0;i<f.radiobutton.length;i++)
		{ 
		   if (f.radiobutton[i].checked) 
			  break; 
		} 
		document.opcion = f.radiobutton[i].value; 
		 
	   if (document.opcion=="A")
	   {
		    f=document.form1;
		    li_total=f.li_totnum.value;
			 for (li=1;li<=li_total;li++)
			 {
			   	txtasignado="txtasignado";
				ld_monto=eval("f."+txtasignado+".value");
				ld_monto=uf_convertir_monto(ld_monto);
				ld_division=parseFloat((ld_monto/li_total));
			   
				ld_division=redondear2(ld_division); 		   
				ld_suma=redondear2(ld_division*li_total);
				ld_ultfuefin=redondear2((ld_suma-ld_monto));
			   if(ld_ultfuefin>=0)
			   {
			    ld_monultfuefin=ld_division-ld_ultfuefin;
			   } 			
               if(ld_ultfuefin<0)
			   {
			    ld_monultfuefin=ld_division+ld_ultfuefin;
			   } 	
			   ld_total=(ld_division*(li_total-1))+ld_monultfuefin;
			   ld_resto=redondear2(ld_monto-ld_total);
               ld_monultfuefin=ld_monultfuefin+ld_resto;
			   ld_division=uf_convertir(ld_division);
			   ld_monultfuefin=uf_convertir(ld_monultfuefin);
			   txtmonto="txtMonto"+li;
			   if(li != li_total)
			   {
			   eval("f."+txtmonto+".value='"+ld_division+"'") ;
			   }
			   else
			   {
			    eval("f."+txtmonto+".value='"+ld_monultfuefin+"'") ;
			   }
			  }
			calcular_porasignar();
		    f.action="sigesp_w_regdt_distfuefin.php";
   }
}
function redondear2(numero)
{
	numero2='';
	numero=parseFloat(numero);
	numero=Math.ceil(numero*10)/10
	AuxString = numero.toString();
	if(AuxString.indexOf('.')>=0)
	{
		AuxArr=AuxString.split('.');
		if(AuxArr[1]>=5)
		{
			numero=Math.ceil(numero);
		}
		else
		{
			numero=Math.floor(numero);
		}
	}
    return numero;
}
</script>
</html>
