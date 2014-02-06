<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "close();";
	 print "opener.document.form1.submit();";
	 print "</script>";		
   }
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$io_funcion = new class_funciones();
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

function uf_select_data($sql,$ls_cadena,$ls_campo)
{
	$data=$sql->select($ls_cadena);
	
	if($row=$sql->fetch_row($data))
	{
		$ls_result=$row[$ls_campo];
		
	}	
	else
	{
		$ls_result="";
	}
	$sql->free_result($data);
	return $ls_result;
}

if (array_key_exists("operacion",$_POST)) 
   {  
	 $ls_operacion = $_POST["operacion"];
	 $ls_documento = $_POST["txtdocumento"];
	 $ls_numcol    = $_POST["txtnumcol"];
	 $ld_fecdesde  = $_POST["txtfechadesde"];
	 $ld_fechasta  = $_POST["txtfechahasta"];	
	 $ls_estmov    = $_POST["estmov"];	
   }
else 
   {
	 $ls_operacion = "";
     $ls_documento = "";
	 $ls_numcol    = "";
	 $ld_fecdesde  = '01/'.date("m/Y");
	 $ld_fechasta  = date("d/m/Y");
	 $ls_estmov    = "-";
   }

$lb_sel  = "";
$lb_selN = "";
$lb_selC = "";
$lb_selL = "";
$lb_selA = "";
$lb_selO = "";
   
switch($ls_estmov){
  case '-':
    $lb_sel = "selected";
  break;
  case 'N':
	$lb_selN="selected";
  break;
  case 'C':
	$lb_selC="selected";
  break;
  case 'L':
	$lb_selL="selected";
  break;
  case 'A':
	$lb_selA="selected";
  break;
  case 'O':
	$lb_selO="selected";
  break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Movimientos de Colocaci&oacute;n</title>
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion" >
</p>
  <br>
  <div align="center">
    <table width="565" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="5" style="text-align:center">Cat&aacute;logo de Movimientos de Colocaci&oacute;n</td>
      </tr>
      <tr>
        <td width="94" height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13" colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" align="right">Documento</td>
        <td height="22"><div align="left">
          <input name="txtdocumento" type="text" id="txtdocumento" onBlur="javascript: rellenar_cad(document.form1.txtdocumento.value,15,'doc');" style="text-align:center" maxlength="15">        
        </div></td>
				<td width="72" height="22" align="right"><strong>Fecha</strong> </td>
        <td height="22" style="text-align:right">Desde</td>
            <td height="22" align="left"><input name="txtfechadesde" type="text" id="txtfechadesde" style="text-align:center" onBlur="javascript:valFecha(this);"  onKeyPress="currencyDate(this);" value="<?php print $ld_fecdesde ?>"  size="15" maxlength="10" datepicker="true"></td>
      </tr>
      <tr>
        <td height="22" align="right">Colocaci&oacute;n</td>
        <td height="22" align="left"><input name="txtnumcol" type="text" id="txtnumcol" maxlength="15" style="text-align:center"></td>
        <td height="22">&nbsp;</td>
        <td width="47" height="22" style="text-align:right">Hasta</td>
        <td width="152" height="22" align="left"><input name="txtfechahasta" type="text" id="txtfechahasta"  style="text-align:center" onBlur="javascript:valFecha(this);"   onKeyPress="currencyDate(this);" value="<?php print $ld_fechasta ?>" size="15" maxlength="10" datepicker="true"> </td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Estatus</td>
        <td height="22" colspan="2" align="left" ><select name="estmov" id="estmov">
          <option value="-" <?php print $lb_sel;?>>Ninguno</option>
          <option value="N" <?php print $lb_selN;?>>No Contabilizado</option>
          <option value="C" <?php print $lb_selC;?>>Contabilizado</option>
          <option value="L" <?php print $lb_selL;?>>No Contabilizable</option>
          <option value="A" <?php print $lb_selA;?>>Anulado</option>
          <option value="O" <?php print $lb_selO;?>>Original</option>
        </select></td>
        <td height="22" style="text-align:right">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22" colspan="4" style="text-align:right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar..." width="20" height="20" border="0">Buscar</a></td>
      </tr>
      <tr>
        <td height="13" colspan="5">&nbsp;</td>
      </tr>
    </table>
	<p><br>
<?php
print "<table width=565 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Documento</td>";
print "<td>Colocación</td>";
print "<td>Operación</td>";
print "<td>Fecha</td>";
print "<td>Concepto</td>";
print "<td>Monto</td>";
print "<td>Banco</td>";
print "<td>Nombre Banco</td>";
print "<td>Cuenta</td>";
print "<td>Denominación Cuenta</td>";
print "<td>Estatus</td>";
print "</tr>";

if ($ls_operacion=='BUSCAR')
   {
     $ls_sqlaux = "";
	 $ld_fecdesde = $io_funcion->uf_convertirdatetobd($ld_fecdesde);
	 $ld_fechasta = $io_funcion->uf_convertirdatetobd($ld_fechasta);
	 if ($ls_estmov!='-')
	    {
		  $ls_sqlaux = " AND scb_movcol.estcol='".$ls_estmov."'";
		}
	 $ls_sql = "SELECT scb_movcol.codban, scb_movcol.ctaban, scb_movcol.numcol, scb_movcol.numdoc, scb_movcol.codope,
					   scb_movcol.estcol, scb_movcol.fecmovcol, scb_movcol.monmovcol, scb_movcol.tasmovcol,
					   scb_movcol.conmov, scb_banco.nomban, scb_ctabanco.dencta, scb_ctabanco.sc_cuenta,
					   scb_colocacion.dencol
				  FROM scb_colocacion, scb_movcol, scb_banco, scb_ctabanco
				 WHERE scb_movcol.codemp='".$ls_codemp."'
				   AND scb_movcol.numdoc like '%".$ls_documento."%'
				   AND scb_movcol.numcol like '%".$ls_numcol."%'
				   AND scb_movcol.fecmovcol BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."' $ls_sqlaux
				   AND scb_colocacion.codemp = scb_movcol.codemp
				   AND scb_colocacion.codban = scb_movcol.codban
				   AND scb_colocacion.ctaban = scb_movcol.ctaban
				   AND scb_colocacion.numcol = scb_movcol.numcol
				   AND scb_movcol.codemp = scb_banco.codemp
				   AND scb_movcol.codban = scb_banco.codban
				   AND scb_banco.codemp = scb_ctabanco.codemp
				   AND scb_banco.codban = scb_ctabanco.codban
				   AND scb_movcol.ctaban = scb_ctabanco.ctaban";
	 $ls_sql=$ls_sql."   AND scb_movcol.ctaban IN (SELECT codintper ".
					"					 FROM sss_permisos_internos ".
					"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
					"				    UNION ".
					"				   SELECT codintper ".
					"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
					"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
     $rs_data = $io_sql->select($ls_sql);
	 if ($rs_data===false)
	    {
		  $lb_valido = false;
		}
     else
	    {
		  $li_numrows = $io_sql->num_rows($rs_data);
		  if ($li_numrows>0)
		     {
			   while($row=$io_sql->fetch_row($rs_data))
			        {
			          $ls_numdoc    = $row["numdoc"];
					  $ls_numcol    = $row["numcol"];
					  $ls_codope    = $row["codope"];      
			          $ld_fecmovcol = $io_funcion->uf_formatovalidofecha($row["fecmovcol"]);
					  $ld_fecmovcol = $io_funcion->uf_convertirfecmostrar($ld_fecmovcol);
					  $ls_conmov    = $row["conmov"];
					  $ld_monmovcol = number_format($row["monmovcol"],2,',','.');
			          $ld_tasmovcol = $row["tasmovcol"];
					  $ls_codban	= $row["codban"];
			          $ls_nomban    = $row["nomban"];
					  $ls_ctaban    = $row["ctaban"];
					  $ls_dencta    = $row["dencta"];
					  $ls_estmov    = $row["estcol"]; 
					  $ls_scgcta    = $row["sc_cuenta"]; 
					  $ls_dencol    = $row["dencol"]; 
					  print "<tr class=celdas-blancas>";
					  print "<td><a href=\"javascript: uf_aceptar('$ls_numdoc','$ls_numcol','$ls_dencol','$ls_codope','$ld_fecmovcol','$ls_conmov','$ld_monmovcol','$ls_codban','$ls_nomban','$ls_ctaban','$ls_dencta','$ls_estmov','$ls_scgcta','$ld_tasmovcol');\">".$ls_numdoc."</a></td>";
					  print "<td>".$ls_numcol."</td>";
					  print "<td>".$ls_codope."</td>";
					  print "<td>".$ld_fecmovcol."</td>";				
					  print "<td>".$ls_conmov."</td>";
					  print "<td>".$ld_monmovcol."</td>";
					  print "<td>".$ls_codban."</td>";
					  print "<td>".$ls_nomban."</td>";
					  print "<td>".$ls_ctaban."</td>";
					  print "<td>".$ls_dencta."</td>";
					  print "<td>".$ls_estmov."</td>";
			          print "</tr>";			
					}
			 }
		  else
		     {
               $io_msg->message("No se han creado Movimientos de Colocación !!!");
			 }
		}
   }
print "</table>";
?>
</p>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function uf_aceptar(ls_documento,ls_numcol,ls_dencol,ls_codope,ls_fecha,ls_descripcion,ldec_monto,ls_codban,ls_nomban,ls_ctaban,ls_dencta,ls_estmov,ls_cuenta_scg,ldec_tasa)
  {
    f = opener.document.form1;
	f.txtdocumento.value  	= ls_documento;
	f.txtcolocacion.value 	= ls_numcol;
	f.txtdencol.value	  	= ls_dencol;
	f.txtcodban.value	  	= ls_codban;
	f.txtdenban.value	  	= ls_nomban;
	f.txtcuenta.value	  	= ls_ctaban;
	f.txtdenominacion.value = ls_dencta;
	f.cmboperacion.value	= ls_codope;
	f.txtfecha.value		= ls_fecha;
	f.txttasa.value			= ldec_tasa;
	f.txtcuenta_scg.value	= ls_cuenta_scg;
	f.txtmonto.value		= uf_convertir(ldec_monto);
	f.txtconcepto.value		= ls_descripcion;
	f.operacion.value		= "CARGAR_DT";
	f.estcol.value			= ls_estmov;
	f.submit();
	close();
  }

  function ue_search()
  {
	  f = document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_mov_colocacion.php";
	  f.submit();
  }
	//Funciones de validacion de fecha.
	function rellenar_cad(cadena,longitud,campo)
	{
		var mystring=new String(cadena);
		cadena_ceros="";
		lencad=mystring.length;
	
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
		if(campo=="doc")
		{
			document.form1.txtdocumento.value=cadena;
		}
		else
		{
			document.form1.txtcomprobante.value=cadena;
		}
	
	}

	  function valSep(oTxt){ 
    var bOk = false; 
    var sep1 = oTxt.value.charAt(2); 
    var sep2 = oTxt.value.charAt(5); 
    bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
    bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
    return bOk; 
   } 

   function finMes(oTxt){ 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    var nAno = parseInt(oTxt.value.substr(6), 10); 
    var nRes = 0; 
    switch (nMes){ 
     case 1: nRes = 31; break; 
     case 2: nRes = 28; break; 
     case 3: nRes = 31; break; 
     case 4: nRes = 30; break; 
     case 5: nRes = 31; break; 
     case 6: nRes = 30; break; 
     case 7: nRes = 31; break; 
     case 8: nRes = 31; break; 
     case 9: nRes = 30; break; 
     case 10: nRes = 31; break; 
     case 11: nRes = 30; break; 
     case 12: nRes = 31; break; 
    } 
    return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
   } 

   function valDia(oTxt){ 
    var bOk = false; 
    var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
    bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
    return bOk; 
   } 

   function valMes(oTxt){ 
    var bOk = false; 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
    return bOk; 
   } 

   function valAno(oTxt){ 
    var bOk = true; 
    var nAno = oTxt.value.substr(6); 
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
    if (bOk){ 
     for (var i = 0; i < nAno.length; i++){ 
      bOk = bOk && esDigito(nAno.charAt(i)); 
     } 
    } 
    return bOk; 
   } 

   function valFecha(oTxt){ 
    var bOk = true; 
	
		if (oTxt.value != ""){ 
		 bOk = bOk && (valAno(oTxt)); 
		 bOk = bOk && (valMes(oTxt)); 
		 bOk = bOk && (valDia(oTxt)); 
		 bOk = bOk && (valSep(oTxt)); 
		 if (!bOk){ 
		  alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
		  oTxt.value = "01/01/1900"; 
		  oTxt.focus(); 
		 } 
		}
	 
   }

  function esDigito(sChr){ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
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
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>