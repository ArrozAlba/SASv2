<?php
//session_id('8675309');
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
$fun=new class_funciones();
$SQL=new class_sql($con);
$ds=new class_datastore();
$ds_procedencias=new class_datastore();
require_once("sigesp_c_cuentas_banco.php");
$io_ctabanco=new sigesp_c_cuentas_banco();
$SQL_mov=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];

function uf_cargar_procedencias($sql)
{
	global $ds_procedencias;
	$ls_sql="SELECT * FROM sigesp_procedencias";
	$data=$sql->select($ls_sql);
	if($row=$sql->fetch_row($data))
	{
		$data=$sql->obtener_datos($data);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds_procedencias->data=$data;
		
	}	
}

function uf_select_provbene($sql,$ls_cadena,$ls_campo)
{
	$data=$sql->select($ls_cadena);
	
	if($row=$sql->fetch_row($data))
	{
		$ls_provbene=$row[$ls_campo];
		
	}	
	else
	{
		$ls_provbene="";
	}
	$sql->free_result($data);
	return $ls_provbene;
}

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

if(array_key_exists("operacion",$_POST))
{
	$ls_codemp=$as_codemp;
	$ls_operacion=$_POST["operacion"];
	$ls_documento="%".$_POST["txtdocumento"]."%";
	$ls_fecdesde=$_POST["txtfechadesde"];
	$ls_fechasta=$_POST["txtfechahasta"];	
	$ls_provben	= "%".$_POST["txtprovbene"]."%";
	//$ls_estmov=$_POST["estmov"];
	$ls_tipo=$_POST["tipo"];
	//$ls_opener=$_POST["opener"];	
	$ls_codope=$_POST["codope"];
	$ls_codban=$_POST["codban"];
	$ls_ctaban=$_POST["ctaban"];
	$ls_mesano=$_POST["mesano"];

}
else
{
	$ls_operacion="";
	//$ls_estmov="-";
	//$ls_opener=$_GET["opener"];
	$ls_fecdesde="01/".date("m/Y");
	$ls_fechasta=date("d/m/Y");
	$ls_codban=$_GET["codban"];
	$ls_ctaban=$_GET["ctaban"];
	$ls_mesano=$_GET["mesano"];
	$ls_mes=substr($ls_mesano,0,2);
	$ls_ano=substr($ls_mesano,3,4);
	$ls_mesano=$ls_mes.$ls_ano;	
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Errores en Banco</title>
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
	<input type="hidden" name="mesano" id="mesano" value="<?php print $ls_mesano; ?>">
	</p>
  <table width="656" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="766"  class="titulo-celda">Cat&aacute;logo de Errores en Banco </td>
    </tr>
  </table>
  <p><br>
  </p>
  <div align="center">
    <table width="656" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="100" align="right">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td colspan="3"><div align="left"></div></td>
      </tr>
      <tr>
        <td align="right">Documento</td>
        <td colspan="2"><div align="left">
          <input name="txtdocumento" type="text" id="txtdocumento" onBlur="javascript: rellenar_cad(document.form1.txtdocumento.value,15,'doc');">        
        </div></td>
			
			<td width="106" align="right">Fecha </td>
            <td align="left"><div align="right">Desde
        </div></td>
            <td align="left"><div align="left">
              <input name="txtfechadesde" type="text" id="txtfechadesde" style="text-align:center"  onKeyPress="currencyDate(this);" value="<?php print $ls_fecdesde;?>"  size="14" maxlength="10" datepicker="true">
            </div></td>
      </tr>
      <tr>
        <td><div align="right">Tipo</div></td>
        <td width="113" align="left">
          <select name="tipo" id="tipo" >
            <option value="P">Proveedor</option>
            <option value="B">Beneficiario</option>
            <option value="-" selected>Ninguno</option>
          </select>
          <a href="javascript:catprovbene(document.form1.tipo.value)"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>		</td>
        <td width="174" align="left"><input name="txtprovbene" type="text" id="txtprovbene" style="text-align:center" value="" size="14" maxlength="10"></td>
        <td><div align="right"></div></td>
        <td width="54"><div align="right">Hasta</div></td>
        <td width="107" align="left"><div align="left">
          <input name="txtfechahasta" type="text" id="txtfechahasta"  style="text-align:center"   onKeyPress="currencyDate(this);" value="<?php print $ls_fechasta;?>" size="14" maxlength="10" datepicker="true"> 
        </div></td>
      </tr>
      <tr>
	  	
        <td height="10"><div align="right">Operaci&oacute;n</div></td>
        <td colspan="3" align="left" ><select name="codope" id="select">
          <option value="N">Ninguno</option>
          <option value="ND">N&oacute;ta D&eacute;bito</option>
          <option value="NC">N&oacute;ta Cr&eacute;dito</option>
          <option value="CH">Cheque</option>
          <option value="RT">Retiro</option>
          <option value="DP">Dep&oacute;sito</option>
         </select></td>
		
         <td>&nbsp;</td>
         <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="15"><div align="left"></div></td>
        <td colspan="5"><div align="left">
          <table width="72" border="0" align="right" cellpadding="0" cellspacing="0" class="letras-peque&ntilde;as">
            <tr>
              <td width="28"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0"></a></td>
              <td width="44"><a href="javascript: ue_search();">Buscar</a></td>
              </tr>
          </table>
        </div></td>
      </tr>
    </table>
	<div align="left"><br>
    </div>
	<p>&nbsp;</p>
    <table width="200" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><?php

print "<table width=565 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
	print "<td>Documento</td>";
	print "<td>Operación</td>";
	print "<td>Fecha</td>";
	print "<td>Concepto</td>";
	print "<td>Monto</td>";
	print "<td>Monto Ret.</td>";
	print "<td>Voucher</td>";
	print "<td>Banco</td>";
	print "<td>Nombre Banco</td>";
	print "<td>Cuenta</td>";
	print "<td>Denominación Cuenta</td>";
	print "<td>Estatus</td>";
	
print "</tr>";
if($ls_operacion=="BUSCAR")
{
		$ls_sql="SELECT * FROM scb_errorconcbco
				 WHERE codemp='".$as_codemp."' AND numdoc like '".$ls_documento."' AND codope<>'OP' and
				 ctaban='$ls_ctaban' AND codban='$ls_codban'  AND fecmesano='$ls_mesano'";
		
		if((($ls_fecdesde!="")&&($ls_fecdesde!="01/01/1900"))&&(($ls_fechasta!="")&&($ls_fechasta!="01/01/1900")))
		{
			$ls_fecdesde=$fun->uf_convertirdatetobd($ls_fecdesde);
			$ls_fechasta=$fun->uf_convertirdatetobd($ls_fechasta);
			$ls_sql=$ls_sql." AND fecmov>='".$ls_fecdesde."' AND fecmov<='".$ls_fechasta."'";
		}
		if($ls_codope!="N")
		{
			$ls_sql=$ls_sql." AND codope ='".$ls_codope."'";
		}

		if(($ls_tipo=="P")&&($ls_provben!=""))
		{
			$ls_sql=$ls_sql." AND cod_pro like '".$ls_provben."'";
		}
		elseif(($ls_tipo=="B")&&($ls_provben!=""))
		{
			$ls_sql=$ls_sql." AND ced_bene like'".$ls_provben."'";
		}
		$ls_sql=$ls_sql." AND ctaban IN (SELECT codintper ".
						"					 FROM sss_permisos_internos ".
						"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
						"				    UNION ".
						"				   SELECT codintper ".
						"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
						"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";	
		$ls_sql=$ls_sql." ORDER BY fecmov ASC,numdoc ASC";
	//print $ls_sql;
	$rs_mov=$SQL_mov->select($ls_sql);
	$data=$rs_mov;
	if($row=$SQL_mov->fetch_row($rs_mov))
	{
		$data=$SQL_mov->obtener_datos($rs_mov);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("numdoc");
		for($z=1;$z<=$totrow;$z++)
		{
			$ls_documento=$data["numdoc"][$z];
			$ls_codope	   =$data["codope"][$z];      
			$ls_fecha=$data["fecmov"][$z];
			$ls_fecha=$fun->uf_convertirfecmostrar($ls_fecha);
			$ls_estcon=$data["estcon"][$z];
			$ls_descripcion=$data["conmov"][$z];
			$ldec_monto    =$data["monmov"][$z];
			$ldec_monobjret="0";
			$ldec_monret=$data["monret"][$z];
			$ls_voucher	   =$data["chevou"][$z];
			$ls_codban=$data["codban"][$z];
			$ls_nomban=uf_select_data($SQL,"SELECT * FROM scb_banco WHERE codemp='".$as_codemp."' AND codban='".$ls_codban."'","nomban");
			$ls_ctaban=$data["ctaban"][$z];
			$ls_dencta=uf_select_data($SQL,"SELECT * FROM scb_ctabanco WHERE codemp='".$as_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'","dencta");
			$ls_estmov=$data["estmov"][$z];
			$ls_cuenta_scg=uf_select_data($SQL,"SELECT * FROM scb_ctabanco WHERE codemp='".$as_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'","sc_cuenta");	
			$li_estimpche=$data["estimpchq"][$z];
			$io_ctabanco->uf_verificar_saldo($ls_codban,$ls_ctaban,&$ldec_saldo);
			$ldec_saldo=number_format($ldec_saldo,2,",",".");
			print "<tr class=celdas-blancas>";				
				print "<td><a href=\"javascript: uf_aceptar('$ls_documento','$ls_codope','$ls_fecha','$ls_descripcion','$ldec_monto','$ldec_monobjret','$ldec_monret','$ls_codban','$ls_nomban','$ls_ctaban','$ls_dencta','$ldec_saldo','$ls_estmov','$ls_cuenta_scg','$li_estimpche','$ls_estcon');\">".$ls_documento."</a></td>";
				print "<td>".$ls_codope."</td>";
				print "<td>".$ls_fecha."</td>";				
				print "<td>".$ls_descripcion."</td>";
				print "<td>".number_format($ldec_monto,2,",",".")."</td>";
				print "<td>".number_format($ldec_monret,2,",",".")."</td>";
				print "<td>".$ls_voucher."</td>";
				print "<td>".$ls_codban."</td>";
				print "<td>".$ls_nomban."</td>";
				print "<td>".$ls_ctaban."</td>";
				print "<td>".$ls_dencta."</td>";
				print "<td>".$ls_estmov."</td>";
		print "</tr>";			
		}
		$SQL_mov->free_result($rs_mov);	
	}
	else
	{
		$io_msg->message("No se han creado Movimientos Bancarios en el periodo Seleccionado");
	}
}
print "</table>";

?>
        <!--input name="opener" type="hidden" id="opener" value="<?php /*print $ls_opener;/*/?>"--></td>
		<input name="txtdesproben" type="hidden" id="txtdesproben" ></td>
		<input type="hidden" name="ctaban" id="ctaban" value="<?php print $ls_ctaban;?>">
		<input type="hidden" name="codban" id="codban" value="<?php print $ls_codban;?>">
		
      </tr>
    </table>
    <p>&nbsp;</p>
  </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function uf_aceptar(ls_documento,ls_codope,ls_fecha,ls_descripcion,ldec_monto,ldec_monobjret,ldec_monret,ls_codban,ls_nomban,ls_ctaban,ls_dencta,ldec_saldo,ls_estmov,ls_cuenta_scg,li_estimpche,ls_estcon)
  					
  {
   	f=opener.document.form1;
	f.txtdocumento.value=ls_documento;
	f.txtcodban.value=ls_codban;
	f.txtdenban.value=ls_nomban;
	f.txtcuenta.value=ls_ctaban;
	f.txtdenominacion.value=ls_dencta;
	f.txtdisponible.value=ldec_saldo;
	f.cmboperacion.value=ls_codope;
	f.txtfecha.value=ls_fecha;
	f.txtcuenta_scg.value=ls_cuenta_scg;
	f.txtmonto.value=uf_convertir(ldec_monto);
	f.txtmonobjret.value=uf_convertir(ldec_monobjret);
	f.txtretenido.value=uf_convertir(ldec_monret);
	f.txtconcepto.value=ls_descripcion;
	x=opener.document.getElementById("ddlb_spg");
	y=opener.document.getElementById("ddlb_spi");
	
	if(ls_estmov=='L')
	{
		f.nocontabili.checked=true;	
	}
	else
	{
		f.nocontabili.checked=false;	
	}	
	f.status_doc.value="C";//Va del catalogo
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  ls_fecdesde=f.txtfechadesde.value;
	  ls_fechasta=f.txtfechahasta.value;
	  if((ls_fecdesde!="")&&(ls_fechasta!=""))
	  {
		  f.operacion.value="BUSCAR";
		  //f.action="sigesp_cat_mov_bancario.php";
		  f.submit();
	  }
	  else
	  {
	  	alert("Indique un  rango de fechas.");
	  }
  }
	function catprovbene(provbene)
	{
		f=document.form1;
		if(provbene=="P")
		{
			f.txtprovbene.disabled=false;	
			window.open("sigesp_catdinamic_prov.php","_blank","width=502,height=350");
		}
		else if(provbene=="B")
		{
			f.txtprovbene.disabled=false;	
			window.open("sigesp_catdinamic_bene.php","_blank","width=502,height=350");
		}
	}
	//Funciones de validacion de fecha.
	function rellenar_cad(cadena,longitud,campo)
	{
		if(cadena!="")
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
