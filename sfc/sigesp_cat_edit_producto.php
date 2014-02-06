<?Php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
 	print "opener.location.href='../sigesp_conexion.php'";
 	print "close();";
 	print "</script>";
}
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Edici&oacute;n de Art&iacute;culo por Tienda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
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
</style>
</head>

<body link="#006699" vlink="#006699" alink="#006699">
<?Php
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	/*require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_cat_edit_producto.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST))
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
	}*/
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
require_once("class_folder/sigesp_sfc_c_producto.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
require_once("../shared/class_folder/evaluate_formula.php");

$io_funcsob=   new sigesp_sob_c_funciones_sob();
$io_evalform = new evaluate_formula();

if (array_key_exists("operacion",$_POST)){

	$ls_operacion=$_POST["operacion"];
	$ls_forcar=$_POST["hidforcar"];
	$li_codart=$_POST["txtcodart"];
	$li_denart=$_POST["txtdenart"];
	$li_codtiend=$_POST["txtcodtiend"];
	$li_dentiend=$_POST["txtdentiend"];
	$li_porgan=$_POST["txtporgan"];
	$li_flete=$_POST["txtcosfle"];
	$li_preuni=$_POST["txtpreven"];
	$li_codcar=$_POST["txtcodcar"];
	$li_dencar=$_POST["txtdencar"];
	$li_moncar=$_POST["txtmoncar"];
	$li_preven=$_POST["txtpretot"];
	$li_preven1=$_POST["txtpreven2"];
	$li_preven2=$_POST["txtpreven3"];
	$li_preven3=$_POST["txtpreven4"];
	$li_min=$_POST["txtmin"];
	$li_max=$_POST["txtmax"];
	$li_reorden=$_POST["txtreorden"];
	$li_tipcos=$_POST["txttipcost"];
	$li_ultcos=$_POST["txtcosart"];
	$li_cospro=$_POST["txtcosproart"];

}
elseif (array_key_exists("li_codart",$_REQUEST))
{
	$ls_operacion='';
	$ls_forcar='';
	$li_codart=$_REQUEST["li_codart"];
	$li_denart=$_REQUEST["li_denart"];
	$li_codtiend=$_REQUEST["li_codtiend"];
	$li_dentiend=$_REQUEST["li_dentiend"];
	$li_porgan=$_REQUEST["li_porgan"];
	$li_flete=$_REQUEST["li_flete"];
	$li_preuni=$_REQUEST["li_preuni"];
	$li_codcar=$_REQUEST["li_codcar"];
	$li_dencar=$_REQUEST["li_dencar"];
	$li_moncar=$_REQUEST["li_moncar"];
	$li_preven=$_REQUEST["li_preven"];
	$li_preven1=$_REQUEST["li_preven1"];
	$li_preven2=$_REQUEST["li_preven2"];
	$li_preven3=$_REQUEST["li_preven3"];
	$li_min=$_REQUEST["li_min"];
	$li_max=$_REQUEST["li_max"];
	$li_reorden=$_REQUEST["li_reorden"];
	$li_tipcos=$_REQUEST["li_tipcos"];
	$li_ultcos=$_REQUEST["li_ultcos"];
	$li_cospro=$_REQUEST["li_cospro"];

}else{

	$ls_operacion='';
	$ls_forcar='';
	$li_codart='';
	$li_denart='';
	$li_codtiend='';
	$li_dentiend='';
	$li_porgan='0,00';
	$li_flete='0,00';
	$li_preuni='0,00';
	$li_codcar='';
	$li_dencar='';
	$li_moncar='0,00';
	$li_preven='0,00';
	$li_preven1='0,00';
	$li_preven2='0,00';
	$li_preven3='0,00';
	$li_min='0,00';
	$li_max='0,00';
	$li_reorden='0,00';
	$li_tipcos='0,00';
	$li_ultcos='0,00';
	$li_cospro='0,00';
}


if($ls_operacion=="ue_cargo")
{
	$ld_basimpval=$io_funcsob->uf_convertir_cadenanumero($li_preuni);
  	$ld_momcar=$io_evalform->uf_evaluar($ls_forcar,$ld_basimpval,$ls_valido);
  	$li_moncar=$io_funcsob->uf_convertir_numerocadena($ld_momcar);
 	//$ld_pretot=$ld_basimpval+$ld_momcar;
  	//$li_preven=$io_funcsob->uf_convertir_numerocadena($ld_pretot);
}

?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
/*if (($ls_permisos))
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
	print(" close();");
	print("</script>");
}*/
?>

	<p align="center">
		<input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
		<input name="hidforcar" type="hidden" id="hidforcar" value="<?php print $ls_forcar ?>">

		<input name="txttipcost" type="hidden" id="txttipcost" value="<?php print $li_tipcos ?>">
		<input name="txtcosart" type="hidden" id="txtcosart" value="<?php print $li_ultcos ?>">
		<input name="txtcosproart" type="hidden" id="txtcosproart" value="<?php print $li_cospro ?>">
	</p>

	<table width="500" border="0" align="center" cellpadding="1" cellspacing="1" class="formato-blanco">
		<tr>
			<td width="500" colspan="4" class="titulo-celda">Edici&oacute;n de Producto </td>
		</tr>
		<tr>
        	<td width="100" height="24" ><div align="right">Ar&iacute;culo </div></td>
        	<td colspan="3">
        		<input name="txtcodart" type="text" id="txtcodart" value="<? print $li_codart?>" size="20" readonly="true" >
        		<input name="txtdenart" type="text" id="txtdenart" value="<? print $li_denart?>" size="25" class="sin-borde" readonly="true" >
        	</td>
      	</tr>
      	<tr>
        	<td width="100" height="24" ><div align="right">Unidad Operativa de Suministro </div></td>
        	<td colspan="3">
        		<input name="txtcodtiend" type="text" id="txtcodtiend" value="<? print $li_codtiend?>" size="6" readonly="true" >
        		<input name="txtdentiend" type="text" id="txtdentiend" value="<? print $li_dentiend?>" size="15" class="sin-borde" readonly="true" >
        	</td>
      	</tr>
      	<tr>
        	<td width="100" height="24" ><div align="right">Procentaje Ganancia </div></td>
        	<td>
        		<label>
        		<input name="txtporgan" type="text" id="txtporgan" value="<? print $li_porgan?>" size="5" maxlength="5" onKeyPress="return(currencyFormat(this,'.',',',event))" onBlur="ue_calprecio();">
        		%</label>
        	</td>
        	<td width="100" ><div align="right">Flete</div></td>
		    <td>
		    	<label>
		        <input name="txtcosfle" type="text" id="txtcosfle" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $li_flete?>" size="20" maxlength="20" onBlur="ue_calprecio();">
		        </label>
		    </td>
      	</tr>
      	<tr>
            <td height="24" align="right">Precio Unitario </td>
			<td colspan="3" ><input name="txtpreven" type="text" id="txtpreven" size="15" value="<?php print $li_preuni?>" readonly="true"></td>
		</tr>
      	<tr>
	        <td height="24" align="right">Cargo Asociado </td>
	        <td colspan="3" ><input name="txtcodcar" type="text" id="txtcodcar" size="7" maxlength="5" value="<?php print $li_codcar?>">
	        <a href="javascript:ue_catcargos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
	        <input name="txtdencar" type="text" id="txtdencar" value="<? print $li_dencar;?>" class="sin-borde" size="40">
	        </td>
        </tr>

        <tr>
        	<td height="25" align="right">Monto Cargo</td>
        	<td colspan="3" ><input name="txtmoncar" type="text" id="txtmoncar" size="15" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $li_moncar?>"></td>
      	</tr>
	   	<tr>
       		<td height="23" align="right">Precio de Venta </td>
        	<td colspan="3" ><input name="txtpretot" type="text" id="txtpretot" size="15" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $li_preven?>" ></td>
      	</tr>

        <tr>
        	<td height="24" align="right">Precio de Venta 1 </td>
	    	<td colspan="3" ><input name="txtpreven2" type="text" id="txtpreven2" size="15" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $li_preven1?>"></td>
        </tr>
	    <tr>
	    	<td height="24" align="right">Precio de Venta 2 </td>
	    	<td colspan="3" ><input name="txtpreven3" type="text" id="txtpreven3" size="15" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $li_preven2?>"></td>
        </tr>
	    <tr>
	    	<td height="13" align="right">Precio de Venta 3 </td>
	    	<td colspan="3" ><input name="txtpreven4" type="text" id="txtpreven4" size="15" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $li_preven3?>"></td>
      	</tr>
      	<tr>
	    	<td height="13" align="right">Stock. Max </td>
	    	<td colspan="3" ><input name="txtmax" type="text" id="txtmax" size="15" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $li_max?>" onBlur="ue_validarmax();"></td>
      	</tr>
      	<tr>
	    	<td height="13" align="right">Stock. Min </td>
	    	<td colspan="3" ><input name="txtmin" type="text" id="txtmin" size="15" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $li_min?>" onBlur="ue_validarmin();"></td>
      	</tr>
      	<tr>
	    	<td height="13" align="right">Pto. Reorden </td>
	    	<td colspan="3" ><input name="txtreorden" type="text" id="txtreorden" size="15" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $li_reorden?>" onBlur="ue_validarreord();"></td>
      	</tr>

      	<tr><td colspan="4">&nbsp;</td></tr>
      	<tr>
      		<td colspan="4" align="center">
      			<input type="button" class="boton" name="guardar" id="guardar" value="Guardar"onClick="javascript: ue_guardarprod(this);" />
      			&nbsp;
      			<input type="button" class="boton" name="salir" id="salir" value="Salir"onClick="javascript: ue_salir();" />
      		</td>
      	</tr>

 	</table>
</form>
</body>

<script language="JavaScript">
function ue_catcargos()
{
    f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_cargos.php";
	popupWin(pagina,"catcargos",600,250);
}

function ue_cargarcargo(codcar,nomcar,formula,porcar)
{
	f=document.form1;
	f.operacion.value="ue_cargo";
	f.txtcodcar.value=codcar;
    f.txtdencar.value=nomcar;
	f.hidforcar.value=formula;
	f.submit();
}

function ue_calprecio()
{
 f=document.form1;
 var ls_flagcos=f.txttipcost.value;


 if(ls_flagcos=="UC")
  {
    costo=parseFloat(uf_convertir_monto(f.txtcosart.value));
  }
  else
  {
    costo=parseFloat(uf_convertir_monto(f.txtcosproart.value));

  }

  ganancia=parseFloat(uf_convertir_monto(f.txtporgan.value));
  flete=parseFloat(uf_convertir_monto(f.txtcosfle.value));
  ld_precio=(costo/((100-ganancia)/100))+flete;

  ld_moncar = parseFloat(uf_convertir_monto(f.txtmoncar.value));
  ld_pretot = ld_precio;

  ld_precio=roundNumber(ld_precio);
  ld_pretot=roundNumber(ld_pretot);

  f.txtpreven.value=uf_convertir(ld_precio);
  f.txtpretot.value=uf_convertir(ld_pretot);
}

function ue_calganancia()
{
 f=document.form1;
 var ls_flagcos=f.txttipcost.value;

 if (f.txtpreven.value=='0,00')
 {
 f.txtpreven.value='';
 }

if (f.txtpreven.value!='')
{
	precio_venta=parseFloat(uf_convertir_monto(f.txtpreven.value));
	//alert(precio_venta);
	if(ls_flagcos=="UC")
	  {
		costo=parseFloat(uf_convertir_monto(f.txtcosart.value));

	  }
	  else
	  {
		costo=parseFloat(uf_convertir_monto(f.txtcosproart.value));

	  }

	ganancia=100-((costo/precio_venta)*100);
	ganancia=roundNumber(ganancia);
	f.txtporgan.value=uf_convertir(ganancia);
	f.txtpretot.value=f.txtpreven.value;

  }else{

	  f.txtporgan.value='0,00';
	  ganancia=parseFloat(uf_convertir_monto(f.txtporgan.value));
	  flete=parseFloat(uf_convertir_monto(f.txtcosfle.value));
	  ld_precio=(costo/((100-ganancia)/100))+flete;
	  //alert (ld_precio);
	  ld_precio=roundNumber(ld_precio);
	  f.txtpreven.value=uf_convertir(ld_precio);
  }
}

function ue_validarmin(){
	f=document.form1;
	auxmin=parseFloat(uf_convertir_monto(f.txtmin.value));
	auxmax=parseFloat(uf_convertir_monto(f.txtmax.value));
	if(auxmin > auxmax){
		alert('El Stock M�nimo No puede ser mayor al Stock Max!!')
		f.txtmin.value='0,00';
	}
}

function ue_validarmax(){
	f=document.form1;
	auxmin=parseFloat(uf_convertir_monto(f.txtmin.value));
	auxmax=parseFloat(uf_convertir_monto(f.txtmax.value));
	if(auxmax < auxmin){
		alert('El Stock M�ximo No puede ser menor al Stock M�nimo!!')
		f.txtmax.value=uf_convertir(auxmin);
	}
}

function ue_validarreord(){
	f=document.form1;
	auxmin=parseFloat(uf_convertir_monto(f.txtmin.value));
	auxmax=parseFloat(uf_convertir_monto(f.txtmax.value));
	auxreorden=parseFloat(uf_convertir_monto(f.txtreorden.value));
	if(auxreorden < auxmin){
		alert('El Punto de Reorden No puede ser menor al Stock M�nimo!!')
		f.txtreorden.value=uf_convertir(auxmin);
	}else{
		if(auxreorden > auxmax){
			alert('El Punto de Reorden No puede ser mayor al Stock M�ximo!!')
			f.txtreorden.value=uf_convertir(auxmin);
		}
	}
}

function ue_salir(){
	close();
}

function ue_guardarprod(){

	f=document.form1;
	codart=f.txtcodart.value;
	codtiend=f.txtcodtiend.value;
	porgan=f.txtporgan.value;
	flete=f.txtcosfle.value;
	preuni=f.txtpreven.value;
	codcar=f.txtcodcar.value;
	moncar=f.txtmoncar.value;
	preven=f.txtpretot.value;
	preven1=f.txtpreven2.value;
	preven2=f.txtpreven3.value;
	preven3=f.txtpreven4.value;
	max=f.txtmax.value;
	min=f.txtmin.value;
	reorden=f.txtreorden.value;

	auxmin=parseFloat(uf_convertir_monto(f.txtmin.value));
	auxmax=parseFloat(uf_convertir_monto(f.txtmax.value));
	auxreorden=parseFloat(uf_convertir_monto(f.txtreorden.value));

	if( (auxmin == 0) || (auxmax == 0) || (auxreorden == 0) ){
		alert('Los Valores Stock Max, Stock Min y Pto. Reorden, deben ser Mayor a Cero!!');
	}else{
		opener.ue_guardar(codart,codtiend,porgan,flete,preuni,codcar,moncar,preven,preven1,preven2,preven3,max,min,reorden);
		close();
	}
}

</script>
</html>
