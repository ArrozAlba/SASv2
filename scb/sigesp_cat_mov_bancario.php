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
	$ls_documento="%".trim($_POST["txtdocumento"])."";
	$ls_fecdesde=$_POST["txtfechadesde"];
	$ls_fechasta=$_POST["txtfechahasta"];	
	$ls_provben	= "%".$_POST["txtprovbene"]."%";
	$ls_estmov=$_POST["estmov"];
	$ls_tipo=$_POST["tipo"];
	$ls_opener=$_POST["opener"];	
	if($ls_opener!='sigesp_scb_p_pago_directo.php')
	{
		$ls_codope=$_POST["codope"];
	}
	else
	{
		$ls_codope="CH";
	}	
}
else
{
	$ls_operacion="";
	$ls_estmov="-";
	$ls_opener=$_GET["opener"];
	$ls_fecdesde="01/".date("m/Y");
	$ls_fechasta=date("d/m/Y");
}

	$lb_sel  = "";
	$lb_selN = "";
	$lb_selC = "";
	$lb_selL = "";
	$lb_selA = "";
	$lb_selO = "";
    
	switch($ls_estmov){
	  case '-':
	    $lb_sel="selected";
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
<title>Cat&aacute;logo de Movimientos Bancarios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
    <input name="operacion" type="hidden" id="operacion" ></p>
  <p><br>
  </p>
  <div align="center">
    <table width="900" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="6" style="text-align:center">Cat&aacute;logo de Movimientos Bancarios</td>
      </tr>
      <tr>
        <td width="100" height="13" align="right">&nbsp;</td>
        <td height="13" colspan="2">&nbsp;</td>
        <td height="13" colspan="3"><div align="left"></div></td>
      </tr>
      <tr>
        <td height="22" align="right">Documento</td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtdocumento" type="text" id="txtdocumento">        
        </div></td>
			
			<td width="106" height="22" align="right">Fecha </td>
            <td height="22" style="text-align:right">Desde</td>
            <td height="22" align="left"><div align="left">
              <input name="txtfechadesde" type="text" id="txtfechadesde" style="text-align:center"  onKeyPress="currencyDate(this);" value="<?php print $ls_fecdesde;?>"  size="14" maxlength="10" datepicker="true">
            </div></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Tipo</td>
        <td width="123" height="22" align="left">
          <select name="tipo" id="tipo" >
            <option value="P">Proveedor</option>
            <option value="B">Beneficiario</option>
            <option value="-" selected>Ninguno</option>
          </select>
          <a href="javascript:catprovbene(document.form1.tipo.value)"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>		</td>
        <td width="164" height="22" align="left"><input name="txtprovbene" type="text" id="txtprovbene" style="text-align:center" value="" size="14" maxlength="10"></td>
        <td height="22">&nbsp;</td>
        <td width="54" height="22" style="text-align:right">Hasta</td>
        <td width="351" height="22" align="left"><div align="left">
          <input name="txtfechahasta" type="text" id="txtfechahasta"  style="text-align:center"   onKeyPress="currencyDate(this);" value="<?php print $ls_fechasta;?>" size="14" maxlength="10" datepicker="true"> 
        </div></td>
      </tr>
      <tr>
	  	<?php if($ls_opener!='sigesp_scb_p_pago_directo.php'){?>
        <td height="22" style="text-align:right">Operaci&oacute;n</td>
        <td height="22" colspan="3" align="left" ><select name="codope" id="select">
          <option value="N">Ninguno</option>
          <option value="ND">N&oacute;ta D&eacute;bito</option>
          <option value="NC">N&oacute;ta Cr&eacute;dito</option>
          <option value="CH">Cheque</option>
          <option value="RT">Retiro</option>
          <option value="DP">Dep&oacute;sito</option>
         </select></td>
		 <?php } ?>
        <td height="22" style="text-align:right">Estatus</td>
        <td height="22"><div align="left">
          <select name="estmov" id="estmov">
              <option value="-" <?php print $lb_sel;?>>Ninguno</option>
			  <option value="N" <?php print $lb_selN;?>>No Contabilizado</option>
              <option value="C" <?php print $lb_selC;?>>Contabilizado</option>
              <option value="L" <?php print $lb_selL;?>>No Contabilizable</option>
              <option value="A" <?php print $lb_selA;?>>Anulado</option>
              <option value="O" <?php print $lb_selO;?>>Original</option>
          </select>
          <a href="javascript: ue_search();"></a></div></td>	  	
      </tr>
      <tr>
        <td height="22" colspan="6" style="text-align:left"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar..." width="20" height="20" border="0">Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <p>&nbsp;</p>
    <table width="200" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td>
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
	print "<td>Carta Orden</td>";
    print "</tr>";
    
	if ($ls_operacion=="BUSCAR")
    {
		 $ls_sql = "SELECT scb_movbco.codemp,scb_movbco.codban,scb_movbco.ctaban,scb_movbco.numdoc,scb_movbco.numordpagmin, ".
 		           "       scb_movbco.codope,scb_movbco.estmov,scb_movbco.cod_pro,scb_movbco.ced_bene, ".
				   "	   scb_movbco.tipo_destino,scb_movbco.codconmov,scb_movbco.fecmov,scb_movbco.conmov, ".
				   "	   scb_movbco.nomproben,scb_movbco.monto,scb_movbco.estcon,scb_movbco.estcobing, ".
				   "	   scb_movbco.chevau,scb_movbco.estimpche,scb_movbco.monobjret,scb_movbco.monret, ".
				   "	   scb_movbco.procede,scb_movbco.estmovint,scb_movbco.numcarord,scb_banco.nomban, ".
				   "	   scb_ctabanco.dencta,scb_ctabanco.sc_cuenta,scb_cheques.numchequera,scb_movbco.codtipfon,scb_movbco.estmovcob, ".
				   "	  (SELECT MAX(codfuefin) ".
				   "		 FROM scb_movbco_fuefinanciamiento ".
				   "		WHERE codemp = scb_movbco.codemp ".
				   "		  AND codban = scb_movbco.codban ".
				   "		  AND ctaban = scb_movbco.ctaban ".
				   "		  AND numdoc = scb_movbco.numdoc ".
				   "		  AND codope = scb_movbco.codope ".
				   "		  AND estmov = scb_movbco.estmov) AS fuentefinanciamiento, ".
		           "      (SELECT scb_tipofondo.dentipfon 
							 FROM scb_tipofondo
							WHERE scb_tipofondo.codemp=scb_movbco.codemp 
							  AND scb_tipofondo.codtipfon=scb_movbco.codtipfon) as dentipfon".
				   "    FROM scb_movbco LEFT JOIN scb_cheques ".
				   "   	    ON scb_movbco.codemp=scb_cheques.codemp ".
				   "	   AND scb_movbco.codban=scb_cheques.codban ".
				   "	   AND scb_movbco.ctaban=scb_cheques.ctaban ".
				   "	   AND scb_movbco.numdoc=scb_cheques.numche, scb_banco, scb_ctabanco ".
				   "  WHERE scb_movbco.codemp='".$as_codemp."'  ".
				   "   AND scb_movbco.numdoc like '".$ls_documento."' ".
				   "	   AND scb_movbco.codope<>'OP'  ".
				   "	   AND scb_movbco.codemp=scb_banco.codemp ".
				   "	   AND scb_movbco.codban=scb_banco.codban ".
				   "	   AND scb_movbco.codemp=scb_ctabanco.codemp ".
				   "	   AND scb_movbco.codban=scb_ctabanco.codban ".
				   "	   AND scb_movbco.ctaban=scb_ctabanco.ctaban";
				   
		  if((($ls_fecdesde!="")&&($ls_fecdesde!="01/01/1900"))&&(($ls_fechasta!="")&&($ls_fechasta!="01/01/1900")))
			{
				$ls_fecdesde=$fun->uf_convertirdatetobd($ls_fecdesde);
				$ls_fechasta=$fun->uf_convertirdatetobd($ls_fechasta);
				$ls_sql=$ls_sql." AND scb_movbco.fecmov>='".$ls_fecdesde."' AND scb_movbco.fecmov<='".$ls_fechasta."'";
			}
			if($ls_codope!="N")
			{
				$ls_sql=$ls_sql." AND scb_movbco.codope ='".$ls_codope."'";
			}
		
			if(($ls_tipo=="P")&&($ls_provben!=""))
			{
				$ls_sql=$ls_sql." AND scb_movbco.cod_pro like '".$ls_provben."'";
			}
			elseif(($ls_tipo=="B")&&($ls_provben!=""))
			{
				$ls_sql=$ls_sql." AND scb_movbco.ced_bene like'".$ls_provben."'";
			}
			if($ls_estmov!="-")
			{
				$ls_sql=$ls_sql." AND scb_movbco.estmov='".$ls_estmov."'";
			}
			if($ls_opener=='sigesp_scb_p_pago_directo.php')
			{
				$ls_sql=$ls_sql." AND scb_movbco.estbpd='D'";
			}
			$ls_sql=$ls_sql."   AND scb_movbco.ctaban IN (SELECT codintper ".
							"					 FROM sss_permisos_internos ".
							"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
							"				    UNION ".
							"				   SELECT codintper ".
							"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
							"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
		  $ls_sql=$ls_sql." ORDER BY scb_movbco.fecmov ASC,scb_movbco.numdoc ASC";
	      $rs_data = $SQL_mov->select($ls_sql);//print $ls_sql;
          if ($rs_data===false)
		  {
			   $io_msg->message("Error en select movimiento de banco !!!");
		  }
		  else
		  {
			   $li_totrows = $SQL_mov->num_rows($rs_data);
			   if ($li_totrows>0)
			   { 
				    while($row=$SQL_mov->fetch_row($rs_data))
					{
						   $ls_documento	 = $row["numdoc"];
						   $ls_codope	     = $row["codope"];    
						   $ls_codban	     = $row["codban"];
						   $ls_nomban		 = $row["nomban"];
						   $ls_ctaban		 = $row["ctaban"];  
						   $ls_fecha		 = $fun->uf_formatovalidofecha($row["fecmov"]);
						   $ls_fecha		 = $fun->uf_convertirfecmostrar($ls_fecha);
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						   //Verifico si tiene detalles presupuestarios que el usuario tenga las estructuras asignadas
						   //sino lleva detalle presupuestario lo da como valido
						   $ls_cadena="SELECT CASE WHEN scb_movbco_spg.codestpro IS NULL THEN true 
											 ELSE ((SELECT codintper FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' AND scb_movbco_spg.codestpro||scb_movbco_spg.estcla=codintper 
													UNION 
												   SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos 
													WHERE codusu='".$_SESSION["la_logusr"]."' AND scb_movbco_spg.codestpro||scb_movbco_spg.estcla=codintper 
													  AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru) IS NULL)=false END valido  
										FROM scb_movbco LEFT OUTER JOIN scb_movbco_spg ON scb_movbco.codemp=scb_movbco_spg.codemp AND scb_movbco.numdoc=scb_movbco_spg.numdoc AND scb_movbco.codope=scb_movbco_spg.codope
										AND scb_movbco.codban=scb_movbco_spg.codban 
										WHERE scb_movbco.numdoc='".$ls_documento."' AND scb_movbco.codope='".$ls_codope."' AND scb_movbco.codban='".$ls_codban."' AND scb_movbco.ctaban='".$ls_ctaban."' ";
						   $lb_valida_estructura=uf_select_data($SQL,$ls_cadena,"valido");
						   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						   if($lb_valida_estructura)
						   {
							   $ls_estcon		 = $row["estcon"];						   
							   $ls_descripcion   = $row["conmov"];
							   $ldec_monto       = $row["monto"];
							   $ldec_monobjret   = $row["monobjret"];
							   $ldec_monret	     = $row["monret"];
							   $ls_procedencia   = $row["procede"];
							   $ls_voucher	     = $row["chevau"];
							   $ls_prov		 	 = $row["cod_pro"];
							   $ls_bene		 	 = $row["ced_bene"];
							   $ls_provbene	 	 = $row["nomproben"];
							   $ls_numchequera   = $row["numchequera"];						  
							   $ls_dencta		 = $row["dencta"];
							   $ls_estmov		 = $row["estmov"];
							   $ls_cuenta_scg    = $row["sc_cuenta"];
							   $ls_codfuefin     = $row["fuentefinanciamiento"];
							   $li_cobing		 = $row["estcobing"];
							   $li_estint		 = $row["estmovint"];
							   $li_estcob		 = $row["estmovcob"];
							   $ls_codconmov	 = $row["codconmov"];
							   $li_estimpche	 = $row["estimpche"];
							   $ls_tipo_destino  = $row["tipo_destino"];
							   $ls_numcarord     = $row["numcarord"];
							   $ls_codtipfon     = $row["codtipfon"];
							   $ls_numordpagmin  = $row["numordpagmin"];
							   if ($ls_numordpagmin=='-')
								  {
									$ls_numordpagmin = "";
								  }
							   $ls_dentipfon     = $row["dentipfon"];						   
							   $io_ctabanco->uf_verificar_saldo($ls_codban,$ls_ctaban,&$ldec_saldo);
							   $ldec_saldo       = number_format($ldec_saldo,2,",",".");
							   print "<tr class=celdas-blancas>";				
							   print "<td><a href=\"javascript: uf_aceptar('$ls_documento','$ls_codope','$ls_fecha','$ls_descripcion','$ldec_monto','$ldec_monobjret','$ldec_monret','$ls_tipo_destino','$ls_prov','$ls_bene','$ls_provbene','$ls_codban','$ls_nomban','$ls_ctaban','$ls_dencta','$ldec_saldo','$ls_estmov','$ls_cuenta_scg','$li_cobing','$li_estint','$li_estcob','$ls_codconmov','$ls_numcarord','$li_estimpche','$ls_estcon','$ls_codfuefin','$ls_numchequera','$ls_numordpagmin','$ls_codtipfon','$ls_dentipfon');\">".$ls_documento."</a></td>";
							   print "<td>".$ls_codope."</td>";
							   print "<td>".$ls_fecha."</td>";				
							   print "<td>".$ls_descripcion."</td>";
							   print "<td>".number_format($ldec_monto,2,",",".")."</td>";
							   print "<td>".number_format($ldec_monobjret,2,",",".")."</td>";	
							   print "<td>".number_format($ldec_monret,2,",",".")."</td>";
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
							   print "<td>".$ls_numcarord."</td>";
							   print "</tr>";			
			             }
					}
	          		$SQL_mov->free_result($rs_data); 
			   }
	           else
		       {
		            $io_msg->message("No se han creado Movimientos Bancarios en el periodo Seleccionado");
			   }
	      }
    }
    print "</table>";
?>
        <input name="opener" type="hidden" id="opener" value="<?php print $ls_opener;?>"></td>
		<input name="txtdesproben" type="hidden" id="txtdesproben" ></td>
      </tr>
    </table>
  </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function uf_aceptar(ls_documento,ls_codope,ls_fecha,ls_descripcion,ldec_monto,ldec_monobjret,ldec_monret,ls_tipo_destino,ls_prov,ls_bene,ls_provbene,ls_codban,
  					  ls_nomban,ls_ctaban,ls_dencta,ldec_saldo,ls_estmov,ls_cuenta_scg,li_cobing,li_estint,li_estcob,ls_codconmov,ls_numcarord,li_estimpche,ls_estcon,
					  ls_codfuefin,ls_numchequera,ls_numordpagmin,ls_codtipfon,ls_dentipfon)
  {
   	f=opener.document.form1;
	ls_opemovbco = eval("f.cmboperacion.value");
	if (ls_opemovbco=='DP' || ls_opemovbco=='NC')
	{
	     f.txtcodtipfon.value = ls_codtipfon;
		 f.txtdentipfon.value = ls_dentipfon;
	}
	f.hidcodtipfon.value = ls_codtipfon;
	f.hiddentipfon.value = ls_dentipfon;	   
	f.txtnumordpagmin.value = ls_numordpagmin;
	f.txtdocumento.value=ls_documento;
	f.txtcodban.value=ls_codban;
	f.txtdenban.value=ls_nomban;
	f.txtcuenta.value=ls_ctaban;
	f.txtdenominacion.value=ls_dencta;
	f.txtdisponible.value=ldec_saldo;
	f.cmboperacion.value=ls_codope;
	if(document.form1.opener.value=='sigesp_scb_p_movbanco.php')
	{
	f.txtnumcarord.value=ls_numcarord;	
	f.estimpche.value=li_estimpche;
	}
	if(document.form1.opener.value=='sigesp_scb_p_pago_directo.php')
	{
	  f.txtchequera.value=ls_numchequera;	
	}
	if(ls_tipo_destino=="N")
	{
		f.rb_provbene[2].checked=true;
		f.txtprovbene.value=ls_prov;
	}
	else if(ls_tipo_destino=="P")
	{
		f.rb_provbene[0].checked=true;
		f.txtprovbene.value=ls_prov;
	}
	else
	{
		f.rb_provbene[1].checked=true;
		f.txtprovbene.value=ls_bene;
	}
	f.txtfecha.value=ls_fecha;	
	f.txtdesproben.value=ls_provbene;
	f.txtcuenta_scg.value=ls_cuenta_scg;
	f.txtmonto.value=uf_convertir(ldec_monto);
	f.txtmonobjret.value=uf_convertir(ldec_monobjret);
	f.txtretenido.value=uf_convertir(ldec_monret);
	f.txtconcepto.value=ls_descripcion;
	x=opener.document.getElementById("ddlb_spg");
	y=opener.document.getElementById("ddlb_spi");
	if((x==null) && (y!=null))
	{
	 f.ddlb_spi.value=li_cobing;
	}	
	else if((y==null) && (x!=null))
	{
	  f.ddlb_spg.value=li_cobing;
	}
	f.opepre.value=li_cobing;
	f.txtftefinanciamiento.value=ls_codfuefin;
	f.estint.value=li_estint;
	f.estcob.value=li_estcob;
	f.codconmov.value=ls_codconmov;
	f.operacion.value="CARGAR_DT";
	f.estmov.value=ls_estmov;
	f.estcon.value=ls_estcon;	
	if(ls_estmov=='L')
	{
		f.nocontabili.checked=true;
	}
	else
	{
		f.nocontabili.checked=false;
	}	
	f.status_doc.value="C";
	f.action=document.form1.opener.value;
	f.submit();
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
		  f.action="sigesp_cat_mov_bancario.php";
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
   
function uf_validar_mes(ad_fecmov)
{
  ls_proceso = "VERIFICAR_MES";
  parametros = "";
  parametros = "&fecmov="+ad_fecmov;
  if (parametros!="" && ad_fecmov!='')
	 {
	   //Instancia del Objeto AJAX
	   ajax=objetoAjax();
	   //Pagina donde están los métodos para buscar y pintar los resultados
	   ajax.open("POST","class_folder/sigesp_scb_c_catalogo_ajax.php",true);
	   ajax.onreadystatechange = function() {
	   if (ajax.readyState==4) {
		  texto=ajax.responseText;
		  if (texto.indexOf("ERROR->")!=-1)
			 {
			   opener.document.form1.hidmesabi.value = false;			  
			 }				
		    }
		  }
	   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	   //Enviar todos los campos a la pagina para que haga el procesamiento
	   ajax.send("catalogo="+ls_proceso+parametros);
     }
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
