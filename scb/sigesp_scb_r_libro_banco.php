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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_r_libro_banco.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<title>Libro de Banco</title>
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
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a><a href="javascript:ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" alt="Excel" title="Excel" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>
  <?Php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");

$io_include = new sigesp_include();
$io_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($io_conect);

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion		 = $_POST["operacion"];
	 $ld_fecha			 = $_POST["txtfecha"];
	 $ls_codban			 = $_POST["txtcodban"];;
	 $ls_denban			 = $_POST["txtnomban"];
	 $ls_cuenta_banco	 = $_POST["txtcuenta"];
	 $ls_dencuenta_banco = $_POST["txtdenominacion"];
	 $ld_fecdesde		 = $_POST["txtfecdesde"];
	 $ld_fechasta		 = $_POST["txtfechasta"];
     $ls_conmov          = $_POST["cmbconmov"]; 
   }
else
   {
	 $ls_operacion		 = "";	
	 $ld_fecha			 = date("d/m/Y");
	 $ls_codban			 = "";
	 $ls_denban			 = "";
	 $ls_cuenta_banco	 = "";
	 $ls_dencuenta_banco = "";
	 $ld_fecdesde		 = $ld_fecha;
	 $ld_fechasta		 = $ld_fecha;
     $ls_conmov          = "---";
   }
   
function uf_load_conceptos($as_codemp)
{
  global $io_sql;
  $ls_sql  = "SELECT codconmov, denconmov FROM scb_concepto WHERE codconmov<>'---'";
  $rs_data = $io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	 }
  return $rs_data;
}   

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
  <table width="535" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
   
    <tr>
      <td width="70"></td>
    </tr>
    <tr class="titulo-ventana">
      <td height="22" colspan="4" align="center"><input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">
        <input name="txttipocuenta" type="hidden" id="txttipocuenta">
        Libro de Banco <span class="Estilo1">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
        <input name="txtcuenta_scg" type="hidden" id="txtcuenta_scg" style="text-align:center" value="<?php print $ls_cuenta_scg;?>" size="24" readonly>
        <input name="txtdisponible" type="hidden" id="txtdisponible" style="text-align:right" size="24" readonly>
      </span></td>
    </tr>
    <tr style="visibility:hidden">
      <td height="22" style="text-align:right">Reporte en</td>
      <td height="22" colspan="3" style="text-align:left">
          <select name="cmbbsf" id="cmbbsf">
              <option value="0" selected>Bs.</option>
              <option value="1">Bs.F.</option>
            </select>      </td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Banco</td>
      <td height="22" colspan="3" align="center"><div align="left">
        <input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" size="10" readonly>
        <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
        <input name="txtdenban" type="text" id="txtdenban" size="65" class="sin-borde" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22" align="center" style="text-align:right">Cuenta</td>
      <td height="22" colspan="3" align="center"><div align="left">
        <input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" size="30" maxlength="25" readonly>
          <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" size="45" maxlength="254" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22" align="center" style="text-align:right">Desde</td>
      <td width="192" height="22" align="center"><div align="left">
        <input name="txtfecdesde" type="text" id="txtfecdesde"  style="text-align:center" onKeyPress="currencyDate(this);" value="<?php print $ld_fecdesde?>" size="20" maxlength="10"  datepicker="true">
      </div></td>
      <td width="45" height="22" align="center" style="text-align:right">Hasta</td>
      <td width="226" height="22" align="center"><div align="left">
        <input name="txtfechasta" type="text" id="txtfechasta" style="text-align:center" onKeyPress="currencyDate(this);" value="<?php print $ld_fechasta?>"  datepicker="true">
      </div></td>
    </tr>
    <tr>
      <td height="22" align="center" style="text-align:right">Concepto</td>
      <td height="22" style="text-align:left"><?php
		//Llenar Combo Conceptos de Movimiento de Banco
		$ls_codemp = $_SESSION["la_empresa"]["codemp"];
		$rs_data   = uf_load_conceptos($ls_codemp);
	  ?>
        <select name="cmbconmov" id="cmbconmov" style="width:120px">
          <option value="---">---seleccione---</option>
          <?php
		    while($row=$io_sql->fetch_row($rs_data))
		     	 {
		   	       $ls_codconmov = $row["codconmov"];
		  	       $ls_denconmov = $row["denconmov"];
		  	       if ($ls_codconmov==$ls_conmov)
		 	          {
		  	            print "<option value='$ls_codconmov' selected>$ls_denconmov</option>";
		  	          }
		  	       else
		   	          {
		   	            print "<option value='$ls_codconmov'>$ls_denconmov</option>";
		  	          } 
			     } 
	      ?>
        </select></td>
      <td height="22" style="text-align:right"><label>
        <input name="chktipdes" type="checkbox" class="sin-borde" id="chktipdes" value="1">
      </label></td>
      <td height="22" style="text-align:left">Detallado</td>
    </tr>
    <tr>
      <td height="22" align="center" style="text-align:right">Ordenar</td>
      <td height="22" align="center"><div align="left">
        <select name="orden" style="width:120px">
          <option value="D">Documento</option>
          <option value="F">Fecha</option>
          <option value="O">Operaci&oacute;n</option>
        </select>
      </div></td>
      <td height="22" style="text-align:right"><label></label></td>
      <td height="22" style="text-align:left"><label></label></td>
    </tr>
    <tr>
      <td height="18" align="center" style="text-align:right">&nbsp;</td>
      <td height="18" align="center">&nbsp;</td>
      <td height="18" style="text-align:right">&nbsp;</td>
      <td height="18" style="text-align:left">&nbsp;</td>
    </tr>
  </table>
 
</table>
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
f = document.form1;
function ue_imprimir()
{
  ld_fecdesde    = f.txtfecdesde.value;
  ld_fechasta    = f.txtfechasta.value;
  ls_codban      = f.txtcodban.value;
  ls_nomban      = f.txtdenban.value;
  ls_ctaban      = f.txtcuenta.value;
  ls_denctaban   = f.txtdenominacion.value;
  ls_orden       = f.orden.value;
  ls_codconmov   = f.cmbconmov.value;
  li_fila        = f.cmbconmov.selectedIndex;
  ls_tiporeporte = f.cmbbsf.value;
  ls_desconmov   = f.cmbconmov.options[li_fila].text;
  if (f.chktipdes.checked==true)
     {
	   li_tipdes  = '1'; 
	   ls_reporte = "sigesp_scb_rpp_libro_banco_detallado.php";
	   ls_tiprep  = "D";
	 }
  else
     {
	   li_tipdes  = '0';
	   ls_reporte = "sigesp_scb_rpp_libro_banco_pdf.php";   
	   ls_tiprep  = "C";
	 }
  li_imprimir  = f.imprimir.value;
  if (li_imprimir=='1')
     {
       if ((ld_fecdesde!="")&&(ld_fechasta!="")&&(ls_codban!="")&&(ls_ctaban!=""))
          {
	        pagina="reportes/"+ls_reporte+"?fecdes="+ld_fecdesde+"&fechas="+ld_fechasta+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&orden="+ls_orden+"&nomban="+ls_nomban+"&denctaban="+ls_denctaban+"&tipdes="+li_tipdes+"&codconmov="+ls_codconmov+"&tiprep="+ls_tiprep+"&desconmov="+ls_desconmov+"&tiporeporte="+ls_tiporeporte;
	        window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
          }
        else
          {
   	        alert("Seleccione los parámetros de búsqueda !!!");
          }
	 }
  else
     {
	   alert("No tiene permiso para realizar esta operación !!!");
	 }
}

function ue_openexcel()
{
  ld_fecdesde    = f.txtfecdesde.value;
  ld_fechasta    = f.txtfechasta.value;
  ls_codban      = f.txtcodban.value;
  ls_ctaban      = f.txtcuenta.value;
  ls_orden       = f.orden.value;
  li_imprimir    = f.imprimir.value;
  ls_nomban      = f.txtdenban.value;
  ls_tiporeporte = f.cmbbsf.value;
  if (li_imprimir=='1')
     {
	  if (f.chktipdes.checked==true)
		 {
		   li_tipdes  = '1'; 
		   ls_reporte = "sigesp_scb_rpp_libro_banco_detallado_excel.php";
		   ls_tiprep  = "D";
		 }
	  else
		 {
		   li_tipdes  = '0';
		   ls_reporte = "sigesp_scb_rpp_libro_banco_excel.php";   
		   ls_tiprep  = "C";
		 }       
	 	  if ((ld_fecdesde!="")&&(ld_fechasta!="")&&(ls_codban!="")&&(ls_ctaban!=""))
          {
	        pagina="reportes/"+ls_reporte+"?fecdes="+ld_fecdesde+"&fechas="+ld_fechasta+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&orden="+ls_orden+"&nomban="+ls_nomban+"&tiporeporte="+ls_tiporeporte;
	        window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
          }
        else
          {
   	        alert("Seleccione los parámetros de búsqueda !!!");
          }
	 }
  else
     {
	   alert("No tiene permiso para realizar esta operación !!!");
	 }
}

function uf_catalogoprov()
{
    f.operacion.value="BUSCAR";
    pagina="sigesp_catdin_prove.php";
    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}

function rellenar_cad(cadena,longitud,objeto)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;

	total=longitud-lencad;
	if (cadena!="")
	   {
		for (i=1;i<=total;i++)
			{
			  cadena_ceros=cadena_ceros+"0";
			}
		cadena=cadena_ceros+cadena;
		if (objeto=="txtcodprov1")
		   {
			 document.form1.txtcodprov1.value=cadena;
		   }
		 else
		   {
			 document.form1.txtcodprov2.value=cadena;
		   }  
        }
}

 function currencyDate(date)
  { 
	ls_date=date.value;
	li_long=ls_date.length;
			 
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

//Catalogo de cuentas catalogo_cuentabanco
	function catalogo_cuentabanco()
	 {
	   ls_codban=f.txtcodban.value;
	   ls_denban=f.txtdenban.value;
	  	   if((ls_codban!=""))
		   {
			   pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_denban;
			   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,resizable=yes,location=no");
		   }
		   else
		   {
				alert("Seleccione el Banco");   
		   }
	  
	 }	
	 	 
	 function cat_bancos()
	 {
	   pagina="sigesp_cat_bancos.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }

	function cat_conceptos()
	{
	   ls_codope=f.cmboperacion.value;
	   pagina="sigesp_cat_conceptos.php?codope="+ls_codope;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>