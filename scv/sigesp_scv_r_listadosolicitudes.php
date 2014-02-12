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
require_once("class_folder/class_funciones_viaticos.php");
$io_fun_viaticos=new class_funciones_viaticos();
$io_fun_viaticos->uf_load_seguridad("SCV","sigesp_scv_r_listadosolicitudes.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$ls_formato=$io_fun_viaticos->uf_select_config("SCV","REPORTE","LISTADO_SOLICITUDES","sigesp_scv_rpp_listadosolicitudes.php","C");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Listado de Solicitudes de Viaticos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
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
.Estilo2 {color: #6699CC}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" colspan="4" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="4" class="cd-menu">
			<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo2">Control de Viaticos </td>
			  <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>
</td>
  </tr>
  <tr>
    <td height="20" colspan="4" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="7" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td width="20" height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:uf_mostrar_reporte();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></td>
    <td width="718" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
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

require_once("../shared/class_folder/grid_param.php");
$grid=new grid_param();

$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
}
	if ($ls_operacion=="REPORT")
	{
		$ld_fecdesde=$_POST["txtdesde"];
		$ld_fechasta=$_POST["txthasta"];
		$ls_evento="REPORT";
		$ls_descripcion="Generó un reporte de ordenes de despacho. Desde el  ". $ld_fecdesde ." hasta el ".$ld_fechasta;
		$lb_variable= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
								$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
								$la_seguridad["ventanas"],$ls_descripcion);
	}

?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_viaticos->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_viaticos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

  <table width="442" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="438" colspan="2" class="titulo-ventana">Listado de Solicitudes de Viaticos</td>
    </tr>
  </table>
  <table width="437" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="77"></td>
    </tr>
    <tr style="display:none">
      <td colspan="3" align="center"><div align="left">Reporte en
          <select name="cmbbsf" id="cmbbsf">
            <option value="0" selected>Bs.</option>
            <option value="1">Bs.F.</option>
          </select>
      </div></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="33" colspan="3" align="center">      <div align="left">
        <table width="415" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="2"><strong>Fecha de Registro </strong></td>
            <td width="65">&nbsp;</td>
            <td width="172">&nbsp;</td>
            <td width="30">&nbsp;</td>
          </tr>
          <tr>
            <td width="81"><div align="right">Desde</div></td>
            <td width="65"><input name="txtdesde" type="text" id="txtdesde" size="15"  onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"  datepicker="true"></td>
            <td><div align="right">Hasta</div></td>
            <td><div align="left">
                <input name="txthasta" type="text" id="txthasta" size="15"  onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">
            </div></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="22"><div align="right">Unidad</div></td>
            <td height="22"><input name="txtcoduniadm" type="text" id="txtcoduniadm" size="17" style="text-align:center" readonly></td>
            <td height="22" colspan="3"><a href="javascript: ue_buscarunidad();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" size="35" readonly></td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><div align="right" class="style1 style14"></div>        <div align="right" class="style1 style14"></div>        <div align="left"></div></td>
    </tr>
    <tr>
      <td height="24" colspan="3" align="center"><table width="415" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="2"><strong>Ordenado por </strong></td>
          <td width="98">&nbsp;</td>
          <td width="89">&nbsp;</td>
        </tr>
        <tr>
          <td width="134"><div align="right">
            <label >Numero de Solicitud
            <input name="radioorden" type="radio" class="sin-borde" value="scv_solicitudviatico.codsolvia DESC" checked>
            </label>
          </div></td>
          <td width="92"><div align="right">
            <label>Nombre
            <input name="radioorden" type="radio" class="sin-borde" value="nombre">
            </label>
          </div></td>
          <td><div align="right">
            <label>Cédula
            <input name="radioorden" type="radio" class="sin-borde" value="cedula">
            </label>
          </div></td>
          <td><div align="right">
            <label>Ruta
            <input name="radioorden" type="radio" class="sin-borde" value="scv_rutas.desrut">
            </label>
          </div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="24" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="24" colspan="3" align="center"><div align="right">
        <input name="formato" type="hidden" id="formato" value="<?php print $ls_formato;?>">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
		<input name="txtcodestpro1" type="hidden" id="txtcodestpro1" readonly>
	    <input name="txtcodestpro2" type="hidden" id="txtcodestpro2" readonly>
	    <input name="txtcodestpro3" type="hidden" id="txtcodestpro3" readonly>
	    <input name="txtcodestpro4" type="hidden" id="txtcodestpro4" readonly>
	    <input name="txtcodestpro5" type="hidden" id="txtcodestpro5" readonly>
	    <input name="hidestcla"     type="hidden" id="hidestcla" readonly>
      </div></td>
    </tr>
  </table>
  <div align="left"></div>
  <p align="center">&nbsp;</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function ue_buscarunidad()
	{
		f=document.form1;
		li_leer=f.leer.value;
		if (li_leer==1)
		{
			window.open("sigesp_scv_cat_unidadadm.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}

	function uf_mostrar_reporte()
	{
		valido=ue_comparar_intervalo();
		if(valido)
		{
			f=document.form1;
			li_imprimir=f.imprimir.value;
			formato=f.formato.value;
			if(li_imprimir==1)
			{
				ld_desde= f.txtdesde.value;
				ld_hasta= f.txthasta.value;
				ls_coduniadm= f.txtcoduniadm.value;
				ls_tiporeporte=f.cmbbsf.value;
				for(i=0;i<f.radioorden.length;i++)
					if(f.radioorden[i].checked) ls_orden=f.radioorden[i].value;
				if ((ld_desde!="")&&(ld_hasta!=""))
				{
					window.open("reportes/"+formato+"?orden="+ls_orden+"&desde="+ld_desde+"&hasta="+ld_hasta+"&coduniadm="+ls_coduniadm+"&tiporeporte="+ls_tiporeporte+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
				}
				else
				{
					alert("Debe indicar un rango de fechas");
				}
			}
			else
			{
				alert("No tiene permiso para realizar esta operación");
			}
		}
	}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

//--------------------------------------------------------
//	Función que da formato a la fecha colocando los separadores (/).
//--------------------------------------------------------
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++){
			val2 += val[r]	
		}
		if(nums){
			for(z=0;z<val2.length;z++){
				if(isNaN(val2.charAt(z))){
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++){
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++){
			if(q ==0){
				val = val3[q]
			}
			else{
				if(val3[q] != ""){
					val += sep + val3[q]
					}
			}
		}
	d.value = val
	d.valant = val
	}
}

//--------------------------------------------------------
//	Función que valida que un intervalo de tiempo sea valido
//--------------------------------------------------------
   function ue_comparar_intervalo()
   { 

	f=document.form1;
   	ld_desde="f.txtdesde";
   	ld_hasta="f.txthasta";
	var valido = false; 
    var diad = f.txtdesde.value.substr(0, 2); 
    var mesd = f.txtdesde.value.substr(3, 2); 
    var anod = f.txtdesde.value.substr(6, 4); 
    var diah = f.txthasta.value.substr(0, 2); 
    var mesh = f.txthasta.value.substr(3, 2); 
    var anoh = f.txthasta.value.substr(6, 4); 
    
	if (anod < anoh)
	{
		 valido = true; 
	 }
    else 
	{ 
     if (anod == anoh)
	 { 
      if (mesd < mesh)
	  {
	   valido = true; 
	  }
      else 
	  { 
       if (mesd == mesh)
	   {
 		if (diad <= diah)
		{
		 valido = true; 
		}
	   }
      } 
     } 
    } 
    if (valido==false)
	{
		alert("El rango de fecha es invalido");
		f.txtdesde.value="";
		f.txthasta.value="";
	} 
	return valido;
   } 

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>

</html>
