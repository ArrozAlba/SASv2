<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
	require_once("class_folder/class_funciones_rpc.php");
	$io_rpc=new class_funciones_rpc();
	$ls_fichaproveedor=$io_rpc->uf_select_config("RPC","REPORTE","FICHA_PROVEEDOR","sigesp_rpc_rpp_ficha_proveedor.php","C");
	$ls_fichabeneficiario=$io_rpc->uf_select_config("RPC","REPORTE","FICHA_BENEFICIARIO","sigesp_rpc_rpp_ficha_beneficiario.php","C");
	unset($io_rpc);

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
<title>Fichas de Proveedores/Beneficiarios</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="css/rpc.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css"></head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Proveedores y Beneficiarios</td>
			<td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>

      </table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_showouput();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20" border="0"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></td>
  </tr>
  </table>
  <?php
$la_emp=$_SESSION["la_empresa"];
$ls_codemp=$la_emp["codemp"];
require_once("../shared/class_folder/sigesp_include.php");
$io_include=new sigesp_include();
$con=$io_include->uf_conectar();
require_once("../shared/class_folder/class_datastore.php");
$io_ds=new class_datastore();
require_once("class_folder/sigesp_rpc_c_documento.php");
$io_documento=new sigesp_rpc_c_documento($con); 
$lb_valido=$io_documento->uf_load_documentos($ls_codemp,&$io_ds);
?>
</div>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
  <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="86"></td>
    </tr>
    <tr class="titulo-celdanew">
      <td height="22" colspan="3" align="center">Fichas Proveedores/Beneficiarios </td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="center"></td>
    </tr>
    <tr style="visibility:hidden">
      <td height="22" align="center"><div align="right">Reporte en </div></td>
      <td width="359" height="22" align="center"><div align="left">
        <select name="cmbbsf" id="cmbbsf">
          <option value="0" selected>Bs.</option>
          <option value="1">Bs.F.</option>
        </select>
      </div></td>
      <td width="53" height="22" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><table width="450" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td><strong>Categoria</strong></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="132" height="20">
            <div align="right"><a href="javascript: ue_showouput();"></a>
              <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
              <input name="radiocategoria" type="radio" value="0" checked>
            Proveedores</div></td>
          <td width="57"><input name="hidcatalogo" type="hidden" id="hidcatalogo" value="0"></td>
          <td width="132">
            <input name="radiocategoria" type="radio" value="1">
            Beneficiarios</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><table width="450" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4"><div align="left"><strong>Intervalo de C&oacute;digos/Ced&uacute;las</strong><strong><span class="style14">
            <input name="hidrangocodigos" type="hidden" id="hidrangocodigos">
          </span></strong></div></td>
          </tr>
        <tr>
          <td width="64" height="25"><div align="right"><span class="style1 style14">Desde</span></div></td>
          <td width="103"><div align="left">
            <input name="txtcodigo1" type="text" id="txtcodigo1" value="" size="12" maxlength="10"  style="text-align:center ">
            <a href="javascript:uf_catalogoprov();"><img src="../shared/imagebank/tools15/buscar.gif" alt="C&oacute;digos Desde..." width="15" height="15" border="0"  onClick="document.form1.hidrangocodigos.value=1"></a></div></td>
          <td width="62"><div align="right"><span class="style1 style14">Hasta</span></div></td>
          <td width="133"><div align="left">
            <input name="txtcodigo2" type="text" id="txtcodigo2" value="" size="12" maxlength="10"  style="text-align:center ">
            <a href="javascript:uf_catalogoprov();"><img src="../shared/imagebank/tools15/buscar.gif" alt="C&oacute;digos Hasta..." width="15" height="15" border="0"  onClick="document.form1.hidrangocodigos.value=2"></a></div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="center">
	  <table width="450" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td><strong>Documentos (Solo para proveedores) </strong></td>
          <td width="47">&nbsp;</td>
        </tr>
        <tr>
          <td height="20" colspan="2">
		  <table width="450" border="0" align="center">
<?PHP
		$li_totrow=$io_ds->getRowCount("coddoc");
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			$ls_coddoc=$io_ds->data["coddoc"][$li_i];
			$ls_dendoc=$io_ds->data["dendoc"][$li_i];
			print "<tr>";
			print "	<td width='30' align='center'>";
            print "		<input name=chkcoddoc".$li_i." type=checkbox class=sin-borde id=chkcoddoc".$li_i." value='".$ls_coddoc."'>";
			print "	</td>";
            print "		<td width='420' align='left'>".$ls_dendoc."</td>";
			print "</tr>";
		}
?>
          </table>		  </td>
          </tr>
      </table>	  </td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="left"><table width="450" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4"><div align="left"><strong>Ordenado Por</strong>
          </div></td>
          </tr>
        <tr>
          <td width="65" height="20"><div align="right">
            <input name="radioorden" type="radio" value="0" checked>
          </div></td>
          <td width="97">C&oacute;digo/C&eacute;dula</td>
          <td width="82"><div align="right">
            <input name="radioorden" type="radio" value="1">
          </div></td>
          <td width="118">Nombre            </td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"></td>
    </tr>
  </table>
  <input name="total" type="hidden" id="total" value="<?php print $li_totrow;?>">
  <input name="reporteprove" type="hidden" id="reporte" value="<?php print $ls_fichaproveedor;?>">
  <input name="reportebenef" type="hidden" id="reporte" value="<?php print $ls_fichabeneficiario;?>">
</form>      
</body>
<script language="JavaScript">
function rellenar_cad(cadena,longitud,objeto)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;

	total=longitud-lencad;
	for (i=1;i<=total;i++)
	    {
		  cadena_ceros=cadena_ceros+"0";
	    }
	cadena=cadena_ceros+cadena;
 	if (objeto=="txtcedula1")
	   {
	 	 document.form1.txtcedula1.value=cadena;
	   }
	 else
	   {
	     document.form1.txtcedula2.value=cadena;
	   }  
}


function uf_catalogoprov()
{
    f=document.form1;
    if (f.radiocategoria[0].checked==true)
	   {
	     pagina="sigesp_cxp_cat_proveedores.php";
         window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
	   }
    if (f.radiocategoria[1].checked==true)
	   {
	     pagina="sigesp_cxp_cat_beneficiarios.php";
         window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
	   }   
}

function ue_showouput()
{
	f             = document.form1;
	ls_codproben1 = f.txtcodigo1.value;
	ls_codproben2 = f.txtcodigo2.value;
	if (f.radiocategoria[0].checked==true)
	   {
	     ls_tipproben = "P"; 
	   }
	else
	   {
	     ls_tipproben = "B"; 
	   }
	if (ls_codproben1<=ls_codproben2)
	   {
	     if (f.radioorden[0].checked==true)
	        {
	          li_orden = f.radioorden[0].value;
	        }
 	     else
	        {
	          li_orden = f.radioorden[1].value;
	        }     
	     if (ls_tipproben=='P')
		    {
			  reporte=f.reporteprove.value;
			  total=f.total.value;
			  parametros="";
			  li_s=0;
			  for(li_i=1;li_i<=total;li_i++)
			  {
				valido=eval("f.chkcoddoc"+li_i+".checked");
			  	if(valido==true)
				{
					li_s=li_s+1;
					coddoc=eval("f.chkcoddoc"+li_i+".value");
			  		parametros=parametros+"&coddoc"+li_s+"="+coddoc;
				}
			  }
			  tiporeporte=f.cmbbsf.value;
			  pagina="reportes/"+reporte+"?hidorden="+li_orden+"&hidcodproben1="+ls_codproben1+"&hidcodproben2="+ls_codproben2;
			  pagina=pagina+"&total="+li_s+parametros+"&tiporeporte="+tiporeporte;
			}
		 else
		    {
				reporte=f.reportebenef.value;
		      pagina="reportes/"+reporte+"?hidorden="+li_orden+"&hidcodproben1="+ls_codproben1+"&hidcodproben2="+ls_codproben2;
			}

	     window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
       }
    else
       {
	     alert("Error en Rango de Códigos/Cédulas !!!");
	   }
}
</script>
</html>