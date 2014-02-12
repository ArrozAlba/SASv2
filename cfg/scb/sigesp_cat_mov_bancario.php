<?php
session_start();
include("../../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
include("../../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../../shared/class_folder/class_sql.php");
include("../../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
$ds_procedencias=new class_datastore();
$SQL=new class_sql($con);

$SQL_mov=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];

function uf_cargar_procedencias($sql)
{
	global $ds_procedencias;
	$ls_sql="SELECT * FROM sigesp_procedencias";
	$data=$sql->select($ls_sql);
	if ($row=$sql->fetch_row($data))
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
	print $ls_cadena;
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
	print $ls_cadena;
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
	$ls_procedencia=$_POST["procede"];
	$ls_provben	= "%".$_POST["txtprovbene"]."%";
	$ls_estmov=$_POST["estmov"];
	$ls_tipo=$_POST["tipo"];
	
}
else
{
	$ls_operacion="";
	$ls_estmov="-";
}
if($ls_estmov=="-")
{
	$lb_sel="selected";
	$lb_selN="";
	$lb_selC="";
	$lb_selL="";
	$lb_selA="";
	$lb_selO="";
}

if($ls_estmov=="N")
{
	$lb_sel="";
	$lb_selN="selected";
	$lb_selC="";
	$lb_selL="";
	$lb_selA="";
	$lb_selO="";
}
if($ls_estmov=="C")
{
	$lb_sel="";
	$lb_selN="";
	$lb_selC="selected";
	$lb_selL="";
	$lb_selA="";
	$lb_selO="";
}
if($ls_estmov=="L")
{
	$lb_sel="";
	$lb_selN="";
	$lb_selC="";
	$lb_selL="selected";
	$lb_selA="";
	$lb_selO="";
}
if($ls_estmov=="A")
{
	$lb_sel="";
	$lb_selN="";
	$lb_selC="";
	$lb_selL="";
	$lb_selA="selected";
	$lb_selO="";
}
if($ls_estmov=="O")
{
	$lb_sel="";
	$lb_selN="";
	$lb_selC="";
	$lb_selL="";
	$lb_selA="";
	$lb_selO="selected";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Movimientos de Banco</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
</style></head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion" >
</p>
  <table width="565" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Movimientos de Banco</td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="565" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="94" align="right">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td colspan="3"><div align="left"></div></td>
      </tr>
      <tr>
        <td align="right">Documento</td>
        <td colspan="2"><div align="left">
          <input name="txtdocumento" type="text" id="txtdocumento" onBlur="javascript: rellenar_cad(document.form1.txtdocumento.value,15,'doc');">        
        </div></td>
			<?php
			if(array_key_exists("procede",$_POST))
			{
			$ls_procede_ant=$_POST["procede"];
			$sel_N="";
			}
			else
			{
			$ls_procede_ant="N";
			$sel_N="selected";
			}
			uf_cargar_procedencias($SQL);
			$li_rowcount=$ds_procedencias->getRowCount("procede");
			
			?>
			<td width="72" align="right">Fecha </td>
            <td align="left"><div align="right">Desde
        </div></td>
            <td align="left"><input name="txtfechadesde" type="text" id="txtfechadesde" style="text-align:center"  size="10" maxlength="10"  onKeyPress="currencyDate(this);" onBlur="javascript:valFecha(this);"></td>
      </tr>
      <tr>
        <td><div align="right">Tipo</div></td>
        <td width="113" align="left">
          <select name="tipo" id="tipo" >
            <option value="P">Proveedor</option>
            <option value="B">Beneficiario</option>
            <option value="-" selected>Ninguno</option>
          </select>
          <a href="javascript:catprovbene(document.form1.tipo.value)"><img src="../../shared/imagebank/tools15/buscar.gif" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>
		</td>
        <td width="85" align="left"><input name="txtprovbene" type="text" id="txtprovbene2" style="text-align:center" value="" size="14" maxlength="10"></td>
        <td><div align="right"></div></td>
        <td width="47"><div align="right">Hasta </div></td>
        <td width="152" align="left"><input name="txtfechahasta" type="text" id="txtfechahasta" size="10" maxlength="10"  style="text-align:center"   onKeyPress="currencyDate(this);" onBlur="javascript:valFecha(this);"> </td>
      </tr>
      <tr>
        <td height="10"><div align="right">Procedencia</div></td>
        <td colspan="3" align="left" ><select name="procede" id="select">
          <option value="N" "<?php print $sel_N?>" >Ninguno</option>
          <?php
		  	for($li_i=1;$li_i<=$li_rowcount;$li_i++)
			{
				$ls_procede=$ds_procedencias->getValue("procede",$li_i);
				if($ls_procede_ant==$ls_procede)
				{
				?>
          <option value="<?php print $ls_procede?>" selected><?php print $ls_procede?></option>
          <?php
				}
				else
				{
				?>
          <option value="<?php print $ls_procede?>"><?php print $ls_procede?></option>
          <?php
				}
			}
			?>
        </select></td>
        <td><div align="right">Estatus</div></td>
        <td><div align="left">
          <select name="estmov" id="estmov">
              <option value="-" <?php print $lb_sel;?>>Ninguno</option>
			  <option value="N" <?php print $lb_selN;?>>No Contabilizado</option>
              <option value="C" <?php print $lb_selC;?>>Contabilizado</option>
              <option value="L" <?php print $lb_selL;?>>No Contabilizable</option>
              <option value="A" <?php print $lb_selA;?>>Anulado</option>
              <option value="O" <?php print $lb_selO;?>>Original</option>
          </select>
        </div></td>
      </tr>
      <tr>
        <td height="15"><div align="left"></div></td>
        <td colspan="5"><div align="left">
          <table width="83" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td width="83"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0">Buscar</a></td>
            </tr>
          </table>
        </div></td>
      </tr>
    </table>
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
if($ls_operacion=="BUSCAR")
{
		$ls_sql="SELECT * FROM scb_movbco 
				 WHERE codemp='".$as_codemp."' AND numdoc like '".$ls_documento."' ";
		
		if((($ls_fecdesde!="")&&($ls_fecdesde!="01/01/1900"))&&(($ls_fechasta!="")&&($ls_fechasta!="01/01/1900")))
		{
			$ls_fecdesde=substr($ls_fecdesde,6,4)."/".substr($ls_fecdesde,3,2)."/".substr($ls_fecdesde,0,2);
			$ls_fechasta=substr($ls_fechasta,6,4)."/".substr($ls_fechasta,3,2)."/".substr($ls_fechasta,0,2);
			$ls_sql=$ls_sql." AND fecha>='".$ls_fecdesde."' AND fecha<='".$ls_fechasta."'";
		}
		if($ls_procedencia!="N")
		{
			$ls_sql=$ls_sql." AND procede ='".$ls_procedencia."'";
		}

		if(($ls_tipo=="P")&&($ls_provben!=""))
		{
			$ls_sql=$ls_sql." AND cod_pro like '".$ls_provben."'";
		}
		elseif(($ls_tipo=="B")&&($ls_provben!=""))
		{
			$ls_sql=$ls_sql." AND ced_bene like'".$ls_provben."'";
		}
		if($ls_estmov!="-")
		{
			$ls_sql=$ls_sql." AND estmov='".$ls_estmov."'";
		}
		
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
			$ls_fecha=substr($ls_fecha,8,2)."/".substr($ls_fecha,5,2)."/".substr($ls_fecha,0,4);
			$ls_descripcion=$data["conmov"][$z];
			$ldec_monto    =$data["monto"][$z];
			$ldec_monobjret=$data["monobjret"][$z];
			$ldec_monret=$data["monret"][$z];
			$ls_procedencia=$data["procede"][$z];
			$ls_voucher	   =$data["chevau"][$z];
			$ls_prov=$data["cod_pro"][$z];
			$ls_bene=$data["ced_bene"][$z];
			$ls_provbene=$data["nomproben"][$z];
			$ls_codban=$data["codban"][$z];
			$ls_nomban=uf_select_data($SQL,"SELECT * FROM scb_banco WHERE codemp='".$as_codemp."' AND codban='".$ls_codban."'","nomban");
			$ls_ctaban=$data["ctaban"][$z];
			$ls_dencta=uf_select_data($SQL,"SELECT * FROM scb_ctabanco WHERE codemp='".$as_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'","dencta");
			$ls_estmov=$data["estmov"][$z];
			$ls_cuenta_scg=uf_select_data($SQL,"SELECT * FROM scb_ctabanco WHERE codemp='".$as_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'","sc_cuenta");	
			$li_cobing=$data["estcobing"][$z];
			$li_estint=$data["estmovint"][$z];
			$ls_codconmov=$data["codconmov"][$z];
			
			print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: uf_aceptar('$ls_documento','$ls_codope','$ls_fecha','$ls_descripcion','$ldec_monto','$ldec_monobjret','$ldec_monret','$ls_prov','$ls_bene','$ls_provbene','$ls_codban','$ls_nomban','$ls_ctaban','$ls_dencta','$ls_estmov','$ls_cuenta_scg','$li_cobing','$li_estint','$ls_codconmov');\">".$ls_documento."</a></td>";
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
		$io_msg->message("No se han creado Comprobantes Contables");
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

  function uf_aceptar(ls_documento,ls_codope,ls_fecha,ls_descripcion,ldec_monto,ldec_monobjret,ldec_monret,ls_prov,ls_bene,ls_provbene,ls_codban,ls_nomban,ls_ctaban,ls_dencta,ls_estmov,ls_cuenta_scg,li_cobing,li_estint,ls_codconmov)
  {
   	f=opener.document.form1;
	f.txtdocumento.value=ls_documento;
	f.txtcodban.value=ls_codban;
	f.txtdenban.value=ls_nomban;
	f.txtcuenta.value=ls_ctaban;
	f.txtdenominacion.value=ls_dencta;
	f.cmboperacion.value=ls_codope;
	if((ls_prov=="----------")&&(ls_bene=="----------"))
	{
		f.rb_provbene[2].checked=true;
	}
	else if(ls_prov!="----------")
	{
		f.rb_provbene[0].checked=true;
	}
	else
	{
		f.rb_provbene[1].checked=true;
	}
	f.txtfecha.value=ls_fecha;
	f.txtdesproben.value=ls_provbene;
	f.txtcuenta_scg.value=ls_cuenta_scg;
	f.txtmonto.value=uf_convertir(ldec_monto);
	f.txtmonobjret.value=uf_convertir(ldec_monobjret);
	f.txtretenido.value=uf_convertir(ldec_monret);
	f.txtconcepto.value=ls_descripcion;
	f.opepre.value=li_cobing;	
	f.estint.value=li_estint;
	f.codconmov.value=ls_codconmov;
	f.operacion.value="CARGAR_DT";
	f.action="sigesp_scb_p_movbanco.php";
	f.submit();
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_mov_bancario.php";
	  f.submit();
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
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string);
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
			li_string=parseInt(ls_string);
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
			li_string=parseInt(ls_string);
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
