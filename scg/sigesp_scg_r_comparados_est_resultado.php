<?php
    session_start();   
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_funciones_scg.php");
	$io_fun_scg=new class_funciones_scg();
	$io_fun_scg->uf_load_seguridad("SCG","sigesp_scg_r_comparados_est_resultado.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
</script>
<title>Comparado-Estado de Resultado</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
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
-->
</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
.Estilo4 {color: #6699CC}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
   <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo4">Sistema de Contabilidad Patrimonial</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_showouput();"><img src="../shared/imagebank/tools20/imprimir.gif" width="20" height="20" border="0" alt="Imprimir" title="Imprimir"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>
  <?php
require_once("../shared/class_folder/sigesp_include.php");
$io_in=new sigesp_include();
$con=$io_in->uf_conectar();

require_once("../shared/class_folder/class_datastore.php");
$io_ds=new class_datastore();

require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);

require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();

require_once("../shared/class_folder/class_funciones.php");
$io_funcion=new class_funciones(); 


$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
}

if (array_key_exists("cmbmens",$_POST)) 
   {
     $ls_cmbmesdes=$_POST["cmbmens"];
   }
else
   {
     $ls_cmbmesdes="s1";
   }
if (array_key_exists("cmbbimens",$_POST)) 
   {
     $ls_cmbbimens=$_POST["cmbbimens"];
   }
else
   {
     $ls_cmbbimens="s1";
   }
if (array_key_exists("cmbtrim",$_POST)) 
   {
     $ls_cmbtrim=$_POST["cmbtrim"];
   }
else
   {
     $ls_cmbtrim="s1";
   }
if (array_key_exists("cmbsemes",$_POST)) 
   {
     $ls_cmbtrim=$_POST["cmbsemes"];
   }
else
   {
     $ls_cmbtrim="s1";
   }

?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action=""> 
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_scg->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_scg);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="605" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
  <tr>
    <td width="123"></td>
  </tr>
  <tr class="titulo-ventana">
    <td height="17" colspan="3" align="center">Comparado Estado de Resultado </td>
  </tr>
  <tr>
    <td height="17" colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr style="display:none">
    <td height="17" align="center"><div align="right">Reporte en</div></td>
    <td width="124" height="17" align="center"><div align="left">
      <select name="cmbbsf" id="cmbbsf">
        <option value="0" selected>Bs.</option>
        <option value="1">Bs.F.</option>
      </select>
    </div></td>
    <td width="352" height="17" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td height="40" colspan="3" align="center"><div align="left">
      <table width="550" height="84" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celdanew">
          <td height="13" colspan="6"><strong class="titulo-celdanew">Rango de Emisi&oacute;n </strong></td>
        </tr>
        <tr>
          <td><div align="right"> </div></td>
          <td width="67" height="21"><div align="right">
            <input name="botmensual" type="button" class="boton" id="botmensual4" value="Mensual" onClick="uf_cargar_combo('MENSUAL')">
          </div></td>
          <td width="73"><div align="right">
            <input name="botbimensual" type="button" class="boton" id="botbimensual" value="Bi - Mensual" onClick="uf_cargar_combo('BIMENSUAL')">
          </div></td>
          <td width="63"><div align="left">
            <input name="bottrimestral" type="button" class="boton" id="bottrimestral" value="Trimestral" onClick="uf_cargar_combo('TRIMESTRAL')">
          </div></td>
          <td width="77"><div align="left">
            <input name="botsemestral" type="button" class="boton" id="botsemestral" value="Semestral" onClick="uf_cargar_combo('SEMESTRAL')">
          </div></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="107"><div align="right">
            <input name="txtetiqueta" type="text" class="sin-borde" id="txtetiqueta" size="10" maxlength="10" readonly>
          </div></td>
          <td height="21" colspan="2"><select name="combo" size="1" id="combo">
            <option value="s1">Seleccione una opci&oacute;n</option>
            <option> </option>
            <option> </option>
            <option> </option>
            <option> </option>
            <option> </option>
            <option> </option>
          </select></td>
          <td colspan="2"><div align="left">
            <select name="combomes" size="1" id="combomes">
              <option value="s1">Seleccione una opci&oacute;n</option>
              <option> </option>
              <option> </option>
              <option> </option>
              <option> </option>
              <option> </option>
              <option> </option>
            </select>
          </div></td>
          <td width="161">&nbsp;</td>
        </tr>
        <tr>
          <td><div align="right"></div></td>
          <td height="21" colspan="2">&nbsp;</td>
          <td colspan="2"><div align="right"> </div></td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td height="13" colspan="3" align="left"><strong><span class="style14"> </span></strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center"><div align="right"><span class="Estilo1">
      <?php  if($ls_operacion=="")
	{
		?>
      <script language="javascript">
                f=document.form1;    	           
			    f.txtetiqueta.value="Mensual";	
				tipo="MENSUAL";
				if(tipo=="MENSUAL")
				{
					for (var i = f.combomes.options.length;i>=0;i--)
					f.combomes.options[i] = null;
					f.txtetiqueta.value="Mensual";
					//f.combo.options[0]=new Option("Seleccione una opción","s1");
					f.combo.options[0]=new Option("Enero","01");
					f.combo.options[1]=new Option("Febrero","02");
					f.combo.options[2]=new Option("Marzo","03");
					f.combo.options[3]=new Option("Abril","04");
					f.combo.options[4]=new Option("Mayo","05");
					f.combo.options[5]=new Option("Junio","06");
					f.combo.options[6]=new Option("Julio","07");
					f.combo.options[7]=new Option("Agosto","08");
					f.combo.options[8]=new Option("Septiembre","09");
					f.combo.options[9]=new Option("Octubre","10");
					f.combo.options[10]=new Option("Noviembre","11");
					f.combo.options[11]=new Option("Diciembre","12");
					
					//f.combomes.options[0]=new Option("Seleccione una opción","s1");
					f.combomes.options[0]=new Option("Enero","01");
					f.combomes.options[1]=new Option("Febrero","02");
					f.combomes.options[2]=new Option("Marzo","03");
					f.combomes.options[3]=new Option("Abril","04");
					f.combomes.options[4]=new Option("Mayo","05");
					f.combomes.options[5]=new Option("Junio","06");
					f.combomes.options[6]=new Option("Julio","07");
					f.combomes.options[7]=new Option("Agosto","08");
					f.combomes.options[8]=new Option("Septiembre","09");
					f.combomes.options[9]=new Option("Octubre","10");
					f.combomes.options[10]=new Option("Noviembre","11");
					f.combomes.options[11]=new Option("Diciembre","12");
				}
		 </script>
      <?php
	 }	  
	?>
      <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
    </span></div></td>
  </tr>
</table>
<div align="left"></div>
  <p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function uf_desaparecer(objeto)
{
    eval("document.form1."+objeto+".style.visibility='hidden'");
}
function uf_aparecer(objeto)
{
    eval("document.form1."+objeto+".style.visibility='visible'");
}

function ue_showouput()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		txtetiqueta = f.txtetiqueta.value;
		tiporeporte=f.cmbbsf.value;
		if(txtetiqueta=="Mensual")
		{
			combo = f.combo.value;
			combomes = f.combomes.value;
			li_mesdes=combo.substr(0,2);
			li_meshas=combomes.substr(0,2);
			if(li_mesdes>li_meshas)
			{
			  alert("Intervalo de meses incorrecto...");
			}
			else
			{
				if((combo=="s1")||(combomes=="s1"))
				{
				  alert("Por Favor Seleccionar todos los parametros de busqueda");
				}
				else
				{
				   pagina="reportes/sigesp_scg_rpp_comparado_est_resultado.php?combo="+combo
						   +"&combomes="+combomes+"&txtetiqueta="+txtetiqueta+"&tiporeporte="+tiporeporte;
				   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
				}
			}	
		}
		else
		{
			if(txtetiqueta=="Bi-Mensual")
			{
			  combo = f.combo.value;
			}
			if(txtetiqueta=="Trimestral")
			{
			  combo = f.combo.value;
			}
			if(txtetiqueta=="Semestral")
			{
			  combo = f.combo.value;
			}
			if(combo=="s1")
			{
			  alert("Por Favor Seleccionar todos los parametros de busqueda");
			}
			else
			{
			   pagina="reportes/sigesp_scg_rpp_comparado_est_resultado.php?combo="+combo+"&txtetiqueta="+txtetiqueta+"&tiporeporte="+tiporeporte;
			   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
			}
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}

function uf_cargar_combo(tipo)
{
	f=document.form1;
	for (var i = f.combo.options.length;i>=0;i--)
		f.combo.options[i] = null;
	if(tipo=="MENSUAL")
	{
		uf_aparecer("combomes");
	    for (var i = f.combomes.options.length;i>=0;i--)
		f.combomes.options[i] = null;
		f.txtetiqueta.value="Mensual";
		//f.combo.options[0]=new Option("Seleccione una opción","s1");
		f.combo.options[0]=new Option("Enero","01");
		f.combo.options[1]=new Option("Febrero","02");
		f.combo.options[2]=new Option("Marzo","03");
		f.combo.options[3]=new Option("Abril","04");
		f.combo.options[4]=new Option("Mayo","05");
		f.combo.options[5]=new Option("Junio","06");
		f.combo.options[6]=new Option("Julio","07");
		f.combo.options[7]=new Option("Agosto","08");
		f.combo.options[8]=new Option("Septiembre","09");
		f.combo.options[9]=new Option("Octubre","10");
		f.combo.options[10]=new Option("Noviembre","11");
		f.combo.options[11]=new Option("Diciembre","12");
		
		//f.combomes.options[0]=new Option("Seleccione una opción","s1");
		f.combomes.options[0]=new Option("Enero","01");
		f.combomes.options[1]=new Option("Febrero","02");
		f.combomes.options[2]=new Option("Marzo","03");
		f.combomes.options[3]=new Option("Abril","04");
		f.combomes.options[4]=new Option("Mayo","05");
		f.combomes.options[5]=new Option("Junio","06");
		f.combomes.options[6]=new Option("Julio","07");
		f.combomes.options[7]=new Option("Agosto","08");
		f.combomes.options[8]=new Option("Septiembre","09");
		f.combomes.options[9]=new Option("Octubre","10");
		f.combomes.options[10]=new Option("Noviembre","11");
		f.combomes.options[11]=new Option("Diciembre","12");

	}
	if(tipo=="BIMENSUAL")
	{
		f.txtetiqueta.value="Bi-Mensual";
		//f.combo.options[0]=new Option("Seleccione una opción","s1");
		f.combo.options[0]=new Option("Enero - Febrero","0102");
		f.combo.options[1]=new Option("Febrero - Marzo","0203");
		f.combo.options[2]=new Option("Marzo - Abril","0304");
		f.combo.options[3]=new Option("Abril - Mayo","0405");
		f.combo.options[4]=new Option("Mayo - Junio","0506");
		f.combo.options[5]=new Option("Junio - Julio","0607");
		f.combo.options[6]=new Option("Julio - Agosto","0708");
		f.combo.options[7]=new Option("Agosto - Septiembre","0809");
		f.combo.options[8]=new Option("Septiembre - Octubre","0910");
		f.combo.options[9]=new Option("Octubre - Noviembre","1011");
		f.combo.options[10]=new Option("Noviembre - Diciembre","1112");
		uf_desaparecer("combomes");

	}
	if(tipo=="TRIMESTRAL")
	{
		f.txtetiqueta.value="Trimestral";
		//f.combo.options[0]=new Option("Seleccione una opción","s1");
		f.combo.options[0]=new Option("Enero - Marzo","0103");
		f.combo.options[1]=new Option("Febrero - Abril","0204");
		f.combo.options[2]=new Option("Marzo - Mayo","0305");
		f.combo.options[3]=new Option("Abril - Junio","0406");
		f.combo.options[4]=new Option("Mayo - Julio","0507");
		f.combo.options[5]=new Option("Junio - Agosto","0608");
		f.combo.options[6]=new Option("Julio - Septiembre","0709");
		f.combo.options[7]=new Option("Agosto - Octubre","0810");
		f.combo.options[8]=new Option("Septimbre - Noviembre","0911");
		f.combo.options[9]=new Option("Octubre - Diciembre","1012");
		uf_desaparecer("combomes");
	} 
	if(tipo=="SEMESTRAL")
	{
		f.txtetiqueta.value="Semestral";
		//f.combo.options[0]=new Option("Seleccione una opción","s1");
		f.combo.options[0]=new Option("Enero - Junio","0106");
		f.combo.options[1]=new Option("Febrero - Julio","0207");
		f.combo.options[2]=new Option("Marzo - Agosto","0308");
		f.combo.options[3]=new Option("Abril - Septiembre","0409");
		f.combo.options[4]=new Option("Mayo - Octubre","0510");
		f.combo.options[5]=new Option("Junio - Noviembre","0611");
		f.combo.options[6]=new Option("Julio - Diciembre","0712");
		uf_desaparecer("combomes");
	} 
}
</script>
</html>