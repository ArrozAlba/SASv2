<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");
require_once("sigesp_c_cuentas_banco.php");

$in=new sigesp_include();
$con=$in->uf_conectar();
$io_msg=new class_mensajes();

$io_function=new class_funciones();

$SQL=new class_sql($con);
$ds=new class_datastore();
$ds_procedencias=new class_datastore();

$io_ctabanco=new sigesp_c_cuentas_banco();

$SQL_mov=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];

function uf_select_data($sql,$ls_cadena,$ls_campo)
{
	$data=$sql->select($ls_cadena);

	if(($data===false))
	{
		
	}
	else
	{		
		if($row=$sql->fetch_row($data))
		{
			$ls_result=$row[$ls_campo];
			
		}	
		else
		{
			$ls_result="";
		}
		$sql->free_result($data);
	}
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
	$ls_tipo=$_POST["tipo"];
	$ls_codope=$_POST["codope"];
	
}
else
{
	$ls_operacion="";
	$ls_estmov="-";
	$ls_fecdesde="01/".date("m/Y");
	$ls_fechasta=date("d/m/Y");
	$ls_codope="";
}
$ls_tiporep="";
if(array_key_exists("tiporeporte",$_GET))
{
	$ls_tiporep=$_GET["tiporeporte"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Movimientos Bancarios</title>
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
  <p align="center">&nbsp;</p>
  <br>
  <div align="center">
    <table width="565" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="5" align="right" class="titulo-celda"><input name="operacion" type="hidden" id="operacion" >
        Cat&aacute;logo de Movimientos Bancarios</td>
      </tr>
      <tr>
        <td width="94" height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13" colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" align="right">Documento</td>
        <td height="22"><div align="left">
          <input name="txtdocumento" type="text" id="txtdocumento" onBlur="javascript: rellenar_cad(document.form1.txtdocumento.value,15,'doc');" maxlength="15" style="text-align:center">        
        </div></td>
			<td width="53" height="22" align="right"><strong>Fecha</strong> </td>
            <td height="22" style="text-align:right">Desde</td>
            <td height="22" align="left"><input name="txtfechadesde" type="text" id="txtfechadesde" style="text-align:center"  size="10" maxlength="10"  onKeyPress="currencyDate(this);" onBlur="javascript:valFecha(this);" value="<?php print $ls_fecdesde;?>"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Tipo</td>
        <td height="22" align="left">
          <select name="tipo" id="tipo" style="width:120px">
            <option value="P">Proveedor</option>
            <option value="B">Beneficiario</option>
            <option value="-" selected>Ninguno</option>
          </select>
          </td>
        <td height="22">&nbsp;</td>
        <td width="47" height="22" style="text-align:right">Hasta</td>
        <td width="152" height="22" align="left"><input name="txtfechahasta" type="text" id="txtfechahasta" size="10" maxlength="10"  style="text-align:center"   onKeyPress="currencyDate(this);" onBlur="javascript:valFecha(this);" value="<?php print $ls_fechasta;?>"> </td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">C&oacute;digo/C&eacute;dula</td>
        <td height="22" colspan="4" align="left" ><input name="txtprovbene" type="text" id="txtprovbene" style="text-align:center" value="" size="14" maxlength="10" readonly>
        <a href="javascript:catprovbene()"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0">
        <input type="hidden" name="txtdesproben" >
        </a></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Operaci&oacute;n</td>
        <td height="22" colspan="2" align="left" >
		<select name="codope" id="select">
          <option value="N">Todos</option>
          <option value="CH">Cheque</option>
          <option value="OP">Orden de Pago Directa</option>
         </select>	    </td>
        <td height="22"><!--div align="right">Estatus</div--></td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13" colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td height="13" colspan="5" style="text-align:right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar..." width="20" height="20" border="0" longdesc="Buscar Movimientos Bancarios...">Buscar</a></td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13" colspan="4">&nbsp;</td>
      </tr>
    </table>
	<br>
    <?php

print "<table width=565 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Documento</td>";
print "<td>Operación</td>";
print "<td>Fecha</td>";
print "<td>Concepto</td>";
print "<td>Monto</td>";
print "<td>Monto Obj.Ret.</td>";
print "<td>Monto Ret.</td>";
print "<td>Procede</td>";
print "<td>Voucher</td>";
print "<td>Proveedor</td>";
print "<td>Beneficiario</td>";
print "<td>Nombre Prov.\Benef.</td>";
print "<td>Banco</td>";
print "<td>Nombre Banco</td>";
print "<td>Cuenta</td>";
print "<td>Denominación Cuenta</td>";
print "<td>Estatus</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     $ls_sql = "SELECT * FROM scb_movbco 
				 WHERE codemp='".$as_codemp."' AND numdoc like '".$ls_documento."'";		
	 if ((($ls_fecdesde!="")&&($ls_fecdesde!="01/01/1900"))&&(($ls_fechasta!="")&&($ls_fechasta!="01/01/1900")))
		{
		  $ls_fecdesde = $io_function->uf_convertirdatetobd($ls_fecdesde);
		  $ls_fechasta = $io_function->uf_convertirdatetobd($ls_fechasta);
		  $ls_sql	   = $ls_sql." AND fecha>='".$ls_fecdesde."' AND fecha<='".$ls_fechasta."'";
		}
	 if (($ls_tipo=="P")&&($ls_provben!=""))
		{
			$ls_sql=$ls_sql." AND cod_pro like '".$ls_provben."'";
		}
		elseif(($ls_tipo=="B")&&($ls_provben!=""))
		{
			$ls_sql=$ls_sql." AND ced_bene like'".$ls_provben."'";
		}
		if($ls_codope=="CH")
		{
			$ls_sql=$ls_sql." AND codope='CH'";
		}
		elseif($ls_codope=="OP")
		{
			$ls_sql=$ls_sql." AND codope='OP'";
		}
		else
		{
			$ls_sql=$ls_sql." AND codope='OP' OR codope='CH'";
		}
		$ls_sql=$ls_sql." AND estmov='C'";
		$ls_sql=$ls_sql."   AND ctaban IN (SELECT codintper ".
						"					 FROM sss_permisos_internos ".
						"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
						"				    UNION ".
						"				   SELECT codintper ".
						"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
						"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
	$rs_mov=$SQL_mov->select($ls_sql);
	$data=$rs_mov;
	if(($rs_mov==false)&&($SQL_mov->message!=""))
	{
		$io_msg->message($io_function->uf_convertirmsg($SQL_mov->message));
	}
	else
	{
		if($row=$SQL_mov->fetch_row($rs_mov))
		{
			$data=$SQL_mov->obtener_datos($rs_mov);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
			$totrow=$ds->getRowCount("numdoc");
			for($z=1;$z<=$totrow;$z++)
			{
				$ls_documento   = $data["numdoc"][$z];
				$ls_codope	    = $data["codope"][$z];      
				$ls_fecha		= $io_function->uf_convertirfecmostrar($data["fecmov"][$z]);
				$ls_descripcion = $data["conmov"][$z];
				$ldec_monto     = number_format($data["monto"][$z],2,',','.');
				$ldec_monobjret = number_format($data["monobjret"][$z],2,',','.');
				$ldec_monret    = number_format($data["monret"][$z],2,',','.');
				$ls_procedencia = $data["procede"][$z];
				$ls_voucher	    = $data["chevau"][$z];
				$ls_prov=$data["cod_pro"][$z];
				$ls_bene=$data["ced_bene"][$z];
				$ls_provbene=$data["nomproben"][$z];
				$ls_codban=$data["codban"][$z];				
				$ls_nomban=uf_select_data($SQL,"SELECT nomban FROM scb_banco WHERE codemp='".$as_codemp."' AND codban='".$ls_codban."'","nomban");
				$ls_ctaban=$data["ctaban"][$z];
				$ls_dencta=uf_select_data($SQL,"SELECT dencta FROM scb_ctabanco WHERE codemp='".$as_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'","dencta");
				$ls_estmov=$data["estmov"][$z];
				$ls_cuenta_scg=uf_select_data($SQL,"SELECT sc_cuenta FROM scb_ctabanco WHERE codemp='".$as_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'","sc_cuenta");	
				$li_cobing=$data["estcobing"][$z];
				$li_estint=$data["estmovint"][$z];
				$ls_codconmov=$data["codconmov"][$z];
				$io_ctabanco->uf_verificar_saldo($ls_codban,$ls_ctaban,&$ldec_saldo);
				$ldec_saldo=number_format($ldec_saldo,2,",",".");
				print "<tr class=celdas-blancas>";				
				if ($ls_tiporep=='MODCMPRET')
				{
					print "<td><a href=\"javascript: aceptarmodcmpret('$ls_documento');\">".$ls_documento."</a></td>";
				}
				else
				{
					print "<td><a href=\"javascript: uf_aceptar('$ls_documento','$ls_codope','$ls_fecha','$ls_descripcion','$ldec_monto','$ldec_monobjret','$ldec_monret','$ls_prov','$ls_bene','$ls_provbene','$ls_codban','$ls_nomban','$ls_ctaban','$ls_dencta','$ls_estmov','$ls_cuenta_scg','$li_cobing','$li_estint','$ls_codconmov','$ldec_saldo');\">".$ls_documento."</a></td>";
				}
				print "<td>".$ls_codope."</td>";
				print "<td>".$ls_fecha."</td>";				
				print "<td>".$ls_descripcion."</td>";
				print "<td>".$ldec_monto."</td>";
				print "<td>".$ldec_monobjret."</td>";	
				print "<td>".$ldec_monret."</td>";
				print "<td>".$ls_procedencia."</td>";		
				print "<td>".$ls_voucher."</td>";
				print "<td>".$ls_prov."</td>";						
				print "<td>".$ls_bene."</td>";
				print "<td>".$ls_provbene."</td>";
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
			$io_msg->message("No se hay documentos registrados");
		}
	}

}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptarmodcmpret(ls_numsol)
{
	li_indice=opener.document.formulario.txtindice.value;
	eval("opener.document.formulario.txtnumsop"+li_indice+".value='"+ls_numsol+"'");
	close();
}

  function uf_aceptar(ls_documento,ls_codope,ls_fecha,ls_descripcion,ldec_monto,ldec_monobjret,ldec_monret,ls_prov,ls_bene,ls_provbene,ls_codban,ls_nomban,ls_ctaban,ls_dencta,ls_estmov,ls_cuenta_scg,li_cobing,li_estint,ls_codconmov,ldec_saldo)
  {
   	f=opener.document.form1;
	f.txtnumord.value=ls_documento;
    if (ls_prov!="----------")
	{
		f.estprov[0].checked=true;
		f.txtprovbene.value=ls_prov;
	}
	else
	{
		f.estprov[1].checked=true;
		f.txtprovbene.value=ls_bene;
	}
	f.txtdesproben.value=ls_provbene;
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_mov_chq_cmp_ret.php?tiporeporte=<?php print $ls_tiporep;?>";
	  f.submit();
  }
	function catprovbene(provbene)
	{
		f=document.form1;
		provbene=form1.tipo.value;
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
</html>