<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "close();";
	 print "opener.document.form1.submit();";
	 print "</script>";		
   }
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");

$in		    = new sigesp_include();
$con	    = $in->uf_conectar();
$io_msg	    = new class_mensajes();
$io_sql	    = new class_sql($con);
$io_funcion = new class_funciones();
$arr	    = $_SESSION["la_empresa"];
$ls_codemp  = $arr["codemp"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_numsolpag = $_POST["txtnumsolpag"];
	 $ld_fecdesde  = $_POST["txtfechadesde"];
	 $ld_fechasta  = $_POST["txtfechahasta"];	
   }
else
   {
	 $ls_operacion = "";
     $ls_numsolpag = "";
	 $ld_fecdesde  = '01/'.date("m/Y");
	 $ld_fechasta  = date("d/m/Y");
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Programaciones de pago a Proveedores</title>
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
        <td height="22" colspan="6" align="right" class="titulo-celda">Cat&aacute;logo de Programaciones de Pago a Proveedores</td>
      </tr>
      <tr>
        <td width="94" height="13" align="right">&nbsp;</td>
        <td height="13" colspan="2">&nbsp;</td>
        <td height="13" colspan="3"><div align="left"></div></td>
      </tr>
      <tr>
        <td height="22" align="right">Documento</td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtnumsolpag" type="text" id="txtnumsolpag" style="text-align:center" onBlur="javascript: rellenar_cad(document.form1.txtnumsolpag.value,15,'doc');" value="<?php print $ls_numsolpag ?>" size="25" maxlength="15">        
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
			?>
			<td width="72" height="22" align="right"><strong>Fecha</strong> </td>
            <td height="22" align="left" style="text-align:right">Desde</td>
            <td height="22" align="left"><input name="txtfechadesde" type="text" id="txtfechadesde" style="text-align:center" onBlur="javascript:valFecha(this);"  onKeyPress="currencyDate(this);" value="<?php print $ld_fecdesde ?>"  size="15" maxlength="12" datepicker="true"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td width="113" height="22" align="left">&nbsp;</td>
        <td width="85" height="22" align="left">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td width="47" height="22" style="text-align:right">Hasta</td>
        <td width="152" height="22" align="left"><input name="txtfechahasta" type="text" id="txtfechahasta"  style="text-align:center" onBlur="javascript:valFecha(this);"   onKeyPress="currencyDate(this);" value="<?php print $ld_fechasta ?>" size="15" maxlength="10" datepicker="true"> </td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22" colspan="5" style="text-align:right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar..." width="20" height="20" border="0">Buscar</a></td>
      </tr>
      <tr>
        <td height="13" colspan="6">&nbsp;</td>
      </tr>
    </table>
	<p><br>
<?php
echo "<table width=565 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td width=70  style=text-align:center>Código</td>";
echo "<td width=265 style=text-align:center>Nombre</td>";
echo "<td width=100 style=text-align:center>Banco</td>";
echo "<td width=130 style=text-align:center>Cuenta</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 require_once("sigesp_c_cuentas_banco.php");
	 $io_ctaban = new sigesp_c_cuentas_banco();
     $ld_fecdesde = $io_funcion->uf_convertirdatetobd($ld_fecdesde);
	 $ld_fechasta = $io_funcion->uf_convertirdatetobd($ld_fechasta);
	 
	 $ls_sql = "SELECT DISTINCT cxp_solicitudes.cod_pro, rpc_proveedor.nompro, scb_prog_pago.codban,
			           scb_prog_pago.ctaban, scb_banco.nomban, scb_ctabanco.dencta, TRIM(scb_ctabanco.sc_cuenta) as sc_cuenta
			      FROM cxp_solicitudes, rpc_proveedor, scb_prog_pago, scb_banco, scb_ctabanco
			     WHERE scb_prog_pago.codemp='".$ls_codemp."'
			       AND scb_prog_pago.numsol like '%".$ls_numsolpag."%'
				   AND cxp_solicitudes.estprosol='S'
			       AND scb_prog_pago.estmov='P'
			       AND cxp_solicitudes.tipproben='P'
				   AND scb_prog_pago.fecpropag BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."'
				   AND scb_prog_pago.codemp=scb_banco.codemp
				   AND scb_prog_pago.codban=scb_banco.codban
				   AND scb_prog_pago.ctaban=scb_ctabanco.ctaban
				   AND cxp_solicitudes.codemp=rpc_proveedor.codemp
				   AND cxp_solicitudes.cod_pro=rpc_proveedor.cod_pro
				   AND cxp_solicitudes.numsol=scb_prog_pago.numsol AND scb_prog_pago.ctaban IN (SELECT codintper ".
			"					 FROM sss_permisos_internos ".
			"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
			"				    UNION ".
			"				   SELECT codintper ".
			"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
			"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)".
			"     ORDER BY cxp_solicitudes.cod_pro, rpc_proveedor.nompro, scb_prog_pago.codban,
				          scb_prog_pago.ctaban, scb_banco.nomban, scb_ctabanco.dencta, sc_cuenta ASC";
      
	  $rs_data = $io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
		 }
      else
	     {
		   $li_totrows = $io_sql->num_rows($rs_data);
		   if ($li_totrows>0)
		      {
			    while(!$rs_data->EOF)
				     {
					   echo "<tr class=celdas-blancas>";
					   $ls_codpro = trim($rs_data->fields["cod_pro"]);
					   $ls_nompro = $rs_data->fields["nompro"];
				       $ls_codban = trim($rs_data->fields["codban"]);
					   $ls_nomban = $rs_data->fields["nomban"];
					   $ls_ctaban = trim($rs_data->fields["ctaban"]);
					   $ls_nomcta = $rs_data->fields["dencta"];
					   $ls_scgcta = $rs_data->fields["sc_cuenta"];
					   $lb_valido = $io_ctaban->uf_verificar_saldo($ls_codban,$ls_ctaban,&$adec_saldo);
					   echo "<td width=70 style=text-align:center><a href=\"javascript: uf_aceptar('$ls_codpro','$ls_nompro','$ls_codban','$ls_nomban','$ls_ctaban','$ls_nomcta','$ls_scgcta','$adec_saldo');\">".$ls_codpro."</a></td>";
					   echo "<td width=265 style=text-align:left title='".$ls_nompro."'>".$ls_nompro."</td>";
					   echo "<td width=100 style=text-align:center title='".$ls_codban.' - '.$ls_nomban."'>".$ls_codban." - ".$ls_nomban."</td>";
					   echo "<td width=130 style=text-align:center title='".$ls_ctaban.' - '.$ls_nomcta."'>".$ls_ctaban." - ".$ls_nomcta."</td>";
				       echo "</tr>";
                       $rs_data->MoveNext();
					 }
			  }
		   else
		      {
			    $io_msg->message("No se han realizado programaciones !!!");   
			  }
		 }  		 
   }
echo "</table>";
?></p>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function uf_aceptar(ls_prov,ls_provbene,ls_codban,ls_nomban,ls_ctaban,ls_dencta,ls_sc_cuenta,ldec_saldo)
  {
   	f=opener.document.form1;
	f.txtcodban.value=ls_codban;
	f.txtdenban.value=ls_nomban;
	f.txtcuenta.value=ls_ctaban;
	f.txtdenominacion.value=ls_dencta;
	f.txtprovbene.value=ls_prov;
	f.txtdesproben.value=ls_provbene;
	f.txtcuenta_scg.value=ls_sc_cuenta;
	f.txtdisponible.value=uf_convertir(ldec_saldo);
	f.operacion.value="CARGAR_DT";
	f.action="sigesp_scb_p_emision_chq.php";
	f.submit();
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_prog_proveedores.php";
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
			document.form1.txtnumsolpag.value=cadena;
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