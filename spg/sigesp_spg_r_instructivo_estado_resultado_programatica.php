<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
    
	$ls_empresa=$_SESSION["la_empresa"]["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SPG";
	$ls_ventanas="sigesp_spg_r_instructivo_estado_resultado_programatica.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;
	
	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$ls_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas);
	}
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
<title>ESTADO DE RESULTADO POR PROGRAMATICA</title>
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
.Estilo2 {font-size: 14px}
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
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo4">Contabilidad Presupuestaria de Gasto</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
       <?php
	   if(array_key_exists("confinstr",$_SESSION["la_empresa"]))
	  {
      if($_SESSION["la_empresa"]["confinstr"]=='A')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='V')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2007.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='N')
	  {
   ?>
       <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
  <?php
      }
	  	 }
	  else
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
	<?php 
	}
	?>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_showouput();"><img src="../shared/imagebank/tools20/imprimir.gif" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<?php
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
}
if (array_key_exists("cmbtri",$_POST)) 
{
   $ls_cmbtrim=$_POST["cmbtri"];
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
	if (($ls_permisos)||($ls_logusr=="PSEGIS"))
	{
		print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	}
	else
	{
		print("<script language=JavaScript>");
		print(" location.href='sigespwindow_blank.php'");
		print("</script>");
	}
	//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////
?>
  <table width="569" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="604" colspan="2" class="titulo-ventana">ESTADO DE RESULTADO POR PROGRAMATICA </td>
    </tr>
  </table>
  <table width="400" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="442"></td>
    </tr>
    <tr>
      <td height="17" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left">
          <p>
            <?php 
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		if($li_estmodest==1)
		{
	   ?>
          </p>
        <table width="551" height="70" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
            <!--DWLayoutTable-->
            <tr class="titulo-celda">
              <td height="13" colspan="9" valign="top" class="titulo-celdanew"><strong class="titulo-celdanew">Rango Codigo Programatico </strong></td>
            </tr>
            <tr class="formato-blanco">
              <td width="38" height="14"><div align="right">Desde</div></td>
              <td width="136" height="22"><input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestpro1 ?>" size="22" maxlength="20"></td>
              <td width="20"><a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
              <td width="20"></td>
              <td width="135"><input name="codestpro2" type="text" id="codestpro2" value="<?php print $ls_codestpro2 ?>" size="22" maxlength="6"></td>
              <td width="20"><a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
              <td width="20"></td>
              <td width="136"><input name="codestpro3" type="text" id="codestpro3" value="<?php print $ls_codestpro3 ?>" size="22" maxlength="3"></td>
              <td width="24"><a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            </tr>
            <tr class="formato-blanco">
              <td height="14"><div align="right">Hasta</div></td>
              <td height="22"><input name="codestpro1h" type="text" id="codestpro1h" value="<?php print $ls_codestpro1h ?>" size="22" maxlength="20"></td>
              <td><a href="javascript:catalogo_estprohas1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
              <td></td>
              <td><input name="codestpro2h" type="text" id="codestpro2h" value="<?php print $ls_codestpro2h  ?>" size="22" maxlength="6"></td>
              <td><a href="javascript:catalogo_estprohas2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
              <td></td>
              <td><input name="codestpro3h" type="text" id="codestpro3h" value="<?php print $ls_codestpro3h ?>" size="22" maxlength="3"></td>
              <td><a href="javascript:catalogo_estprohas3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            </tr>
          </table>
        <p>
            <?php 
		  }
		  else
		  {
		?>
          </p>
        <table width="550" height="65" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
            <!--DWLayoutTable-->
            <tr class="titulo-celda">
              <td height="13" colspan="15" valign="top" class="titulo-celdanew"><strong class="titulo-celdanew">Rango Codigo Programatico </strong></td>
            </tr>
            <tr class="formato-blanco">
              <td width="41" height="18"><div align="right">Desde</div></td>
              <td width="50" height="18"><input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestpro1 ?>" size="5" maxlength="2" style="text-align:center"></td>
              <td width="20"><a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
              <td width="40"></td>
              <td width="50"><input name="codestpro2" type="text" id="codestpro2" value="<?php print $ls_codestpro2 ?>" size="5" maxlength="2" style="text-align:center"></td>
              <td width="27"><a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
              <td width="51"></td>
              <td width="50"><input name="codestpro3" type="text" id="codestpro3" value="<?php print $ls_codestpro3 ?>" size="5" maxlength="2" style="text-align:center"></td>
              <td width="20"><a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
              <td width="40"><label></label></td>
              <td width="50"><input name="codestpro4" type="text" id="codestpro4" value="<?php print $ls_codestpro4 ?>" size="5" maxlength="2" style="text-align:center"></td>
              <td width="29"><a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
              <td width="40"><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td width="50"><label>
                <input name="codestpro5" type="text" id="codestpro5" value="<?php print  $ls_codestpro5 ?>" size="5" maxlength="2" style="text-align:center">
              <a href="javascript:catalogo_estpro5();"></a></label></td>
              <td width="35"><a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            </tr>
            <tr class="formato-blanco">
              <td height="29"><div align="right">Hasta</div></td>
              <td height="29"><input name="codestpro1h" type="text" id="codestpro1h" value="<?php print $ls_codestpro1h ?>" size="5" maxlength="2" style="text-align:center"></td>
              <td><a href="javascript:catalogo_estprohas1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
              <td></td>
              <td><input name="codestpro2h" type="text" id="codestpro2h" value="<?php print $ls_codestpro2h  ?>" size="5" maxlength="2" style="text-align:center"></td>
              <td><a href="javascript:catalogo_estprohas2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
              <td></td>
              <td><input name="codestpro3h" type="text" id="codestpro3h" value="<?php print $ls_codestpro3h ?>" size="5" maxlength="2" style="text-align:center"></td>
              <td><a href="javascript:catalogo_estprohas3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td><label>
                <input name="codestpro4h" type="text" id="codestpro4h" value="<?php print  $ls_codestpro4h ?>" size="5" maxlength="2" style="text-align:center">
              </label></td>
              <td><a href="javascript:catalogo_estprohas4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td><input name="codestpro5h" type="text" id="codestpro5h" value="<?php print  $ls_codestpro5h ?>" size="5" maxlength="2" style="text-align:center"></td>
              <td><a href="javascript:catalogo_estprohas5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a></td>
            </tr>
          </table>
        <?php
		  }
		 ?>
      </div></td>
    </tr>
    <tr>
      <td height="17" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="17" colspan="3" align="center"><div align="left"><span class="Estilo2"></span></div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="left"><table width="550" height="34" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celdanew">
          <td height="13" colspan="2"><strong class="titulo-celdanew"> Fechas </strong></td>
        </tr>
        <tr>
          <td width="105">
              <div align="right">Trimestre</div></td><td height="21">
			  <select name="cmbtri"  id="cmbtri">
                <option value="s1">Seleccione una opci&oacute;n</option>
                <option value="0103">Enero - Marzo</option>
                <option value="0406">Abril - Junio</option>
                <option value="0709">Julio - Septiembre</option>
                <option value="1012">Octubre - Diciembre</option>
              </select></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="left"><strong><span class="style14">        
      </span></strong></div></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="right"><span class="Estilo1">
	<?php  /*if($ls_operacion=="")
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
	 }	  */
	?>
		  <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
</span></a></div></td>
    </tr>
  </table>
  <p align="center">&nbsp;</p>
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
	trimestre = f.cmbtri.value;
	if(trimestre=='s1')
	{
	  alert('Favor Seleccionar un trimestre....');
	}
	else
	{
		li_mesdes=trimestre.substr(0,2);
		pagina="reportes/sigesp_spg_rpp_instructivo_estado_resultado.php?trimestre="+trimestre;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
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
		f.combo.options[1]=new Option("Abril - Junio","0406");
		f.combo.options[2]=new Option("Julio - Septiembre","0709");
		f.combo.options[3]=new Option("Octubre - Diciembre","1012");
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