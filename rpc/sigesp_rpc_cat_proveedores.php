<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Proveedores</title>
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
</head>
<body>
<?php
require_once("class_folder/sigesp_rpc_c_proveedor.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_funciones.php");

$io_proveedor = new sigesp_rpc_c_proveedor();
$io_conect    = new sigesp_include();
$conn         = $io_conect->uf_conectar();
$io_sql       = new class_sql($conn);
$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
$io_fecha     = new class_fecha();
$io_funciones = new class_funciones();

if  (array_key_exists("cmbbanco",$_POST))
	{
	  $ls_banco=$_POST["cmbbanco"];
	  $lr_datos["banco"]=$ls_banco;
    }
else
	{
	  $ls_banco="000";
	}	
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_codpro	   = $_POST["txtcodigo"];
	 $ls_nompro    = $_POST["txtnombre"];
     $ls_dirpro    = $_POST["txtdireccion"];
   	 $ls_rifpro    = $_POST["txtrifpro"];
   }
else
   {
	 $ls_codpro	   = "";
	 $ls_nompro    = "";
     $ls_dirpro    = "";
   	 $ls_rifpro    = "";
	 $ls_operacion = "";
   }
?>
<form name="form1" method="post" action="">
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="4" style="text-align:center">Cat&aacute;logo de Proveedores</td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="74" height="22" align="right">C&oacute;digo</td>
        <td width="145" height="22"><input name="txtcodigo" type="text" id="txtcodigo" maxlength="10" style="text-align:center">        </td>
        <td width="89" height="22" align="right">&nbsp;</td>
        <td width="190" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" align="right">Nombre</td>
        <td height="22" colspan="3"><input name="txtnombre" type="text" id="txtnombre" size="75" maxlength="254" style="text-align:left"></td>
    <tr>
      <td height="22" align="right">Direcci&oacute;n</td>
      <td height="22" colspan="3"><input name="txtdireccion" type="text" id="txtdireccion" size="75" style="text-align:left"></td>
    <tr>
      <td height="22" align="right">Rif</td>
      <td height="22"><label>
        <input name="txtrifpro" type="text" id="txtrifpro" style="text-align:center">
      </label></td>
      <td height="22" align="right">Banco</td>
      <td height="22">
<?php
/*Llenar Combo Banco*/
$rs_pro=$io_proveedor->uf_select_llenarcombo_banco($ls_codemp);
?>  
        <select name="cmbbanco" id="cmbbanco" style="width:140px " >
        <option value="---">---seleccione---</option>
        <?php
		while ($row=$io_sql->fetch_row($rs_pro))
  			  {
			    $ls_codban=$row["codban"];
			    $ls_nomban=$row["nomban"];
			    if ($ls_codban==$ls_banco)
			 	   {
					 print "<option value='$ls_codban' selected>$ls_nomban</option>";
				   }
			    else
				   {
					 print "<option value='$ls_codban'>$ls_nomban</option>";
				   }
			  } 
	  ?>
        </select>
<input name="operacion" type="hidden" id="operacion"> 
    <tr>
      <td height="22" align="right">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22" align="right">&nbsp;</td>
      <td height="22">      <div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar Proveedor</a></div>
    <tr>
      <td height="13" align="right">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13" align="right">&nbsp;</td>
      <td height="13">    
  </table> 
<p align="center">
<?php
echo "<table width=750 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td width=75 style=text-align:center>Código</td>";
echo "<td width=300 style=text-align:center>Nombre del Proveedor</td>";
echo "<td width=215 style=text-align:left>Dirección</td>";
echo "<td width=80 style=text-align:center>RIF</td>";
echo "<td width=80 style=text-align:center>Reg. Nac. Contratistas</td>";
echo "</tr>";

if ($ls_operacion=="BUSCAR")
   {
	   $ls_sqlaux = "";
	   $ls_codban = $_POST["cmbbanco"];
	   if ($ls_codban!='---')
	      {
		    $ls_sqlaux = "AND a.codban = '".$ls_codban."'";
		  }
	   $ls_sql = "SELECT a.*, b.denominacion, (SELECT denominacion FROM scg_cuentas WHERE a.sc_ctaant=scg_cuentas.sc_cuenta) AS denctaant
                    FROM rpc_proveedor a
				    LEFT JOIN scg_cuentas b ON (a.sc_cuenta=b.sc_cuenta )
                   WHERE a.cod_pro like '%".$ls_codpro."%' 
				     AND a.nompro like '%".$ls_nompro."%'
					 AND a.dirpro like '%".$ls_dirpro."%' 
					 AND a.rifpro like '%".$ls_rifpro."%' $ls_sqlaux
					 AND a.cod_pro<>'----------'
                   ORDER BY a.cod_pro ASC";
		$rs_data =$io_sql->select($ls_sql);
		$lb_existe=false;
		while (!$rs_data->EOF)
			  {
					$lb_existe=true;
						print "<tr class=celdas-blancas>";
						$ls_codpro=$rs_data->fields["cod_pro"];
						$ls_nompro=$rs_data->fields["nompro"];
						$ls_dirpro=$rs_data->fields["dirpro"];
						$ls_tipoorg =$rs_data->fields["codtipoorg"];
						$ls_telpro=$rs_data->fields["telpro"];
						$ls_faxpro=$rs_data->fields["faxpro"];
						$ls_nacpro=$rs_data->fields["nacpro"];
						$ls_especialidad=$rs_data->fields["codesp"];
						$ls_rifpro=$rs_data->fields["rifpro"];
						$ls_tipperrif = substr($ls_rifpro,0,1);//Tipo Persona RIF.(J=Juridico,G=Gubernamental,V=Natural Venezolano,E=Natural Extranjero).
						$ls_numpririf = substr($ls_rifpro,2,8);//Número Principal del RIF, 8 Dígitos (0-9).
						$ls_numterrif = substr($ls_rifpro,11,1);//Número Terminal  del RIF, 1 Dígitos (0-9).
						$ls_nitpro=$rs_data->fields["nitpro"];
						$ld_capital= number_format ($rs_data->fields["capital"],2,',','.');
						$ld_monmax= number_format ($rs_data->fields["monmax"],2,',','.');
						$ls_banco=$rs_data->fields["codban"];
						$ls_cuenta=$rs_data->fields["ctaban"];
                     	$ls_moneda=$rs_data->fields["codmon"];
						//Zona Geografica
						$ls_pais=$rs_data->fields["codpai"];
						$ls_estado=$rs_data->fields["codest"];
						$ls_municipio=$rs_data->fields["codmun"];
						$ls_parroquia = $rs_data->fields["codpar"];
						//Fin de Zona Geográfica
						$ls_provee=$rs_data->fields["estpro"];
						$ls_contra=$rs_data->fields["estcon"];
						$ls_contable      = trim($rs_data->fields["sc_cuenta"]);
						$ls_contablerecdoc      = $rs_data->fields["sc_cuentarecdoc"];
						$ls_denocontable  = $rs_data->fields["denominacion"];
						$ls_observacion   = $rs_data->fields["obspro"];
						$ls_cedula        = $rs_data->fields["cedrep"];
						$ls_nomrep        = $rs_data->fields["nomreppro"];
						$ls_cargo         = $rs_data->fields["carrep"];
						$ls_numregRNC     = $rs_data->fields["ocei_no_reg"];
                     	$ls_registro      = $rs_data->fields["registro"];
						$ls_fecharegistro = $io_funciones->uf_formatovalidofecha($rs_data->fields["fecreg"]);
 					    $ls_fecreg        = $io_funciones->uf_convertirfecmostrar($ls_fecharegistro);
						$ls_numero        = $rs_data->fields["nro_reg"];
						$ls_tomo          = $rs_data->fields["tomo_reg"];
						$ls_tommod		  = $rs_data->fields["tommod"];
						$ls_fecRNC		  = $io_funciones->uf_formatovalidofecha($rs_data->fields["ocei_fec_reg"]);
						$ls_fecregRNC     =  $io_funciones->uf_convertirfecmostrar($ls_fecRNC);
						$ls_fecmod        = $io_funciones->uf_formatovalidofecha($rs_data->fields["fecregmod"]);
						$ls_fecregmod     = $io_funciones->uf_convertirfecmostrar($ls_fecmod);
						$ls_regmod        = $rs_data->fields["regmod"];
						$ls_nummod        = $rs_data->fields["nummod"];
					    $ls_numfol        = $rs_data->fields["folreg"];
						$ls_numfolmod     = $rs_data->fields["folmod"];
						$ls_numlic        = $rs_data->fields["numlic"];
                     	$ls_inspector     = $rs_data->fields["inspector"];
				   		$ls_pagweb        = $rs_data->fields["pagweb"];
						$ls_email         = $rs_data->fields["email"];
						$ls_estatus       = $rs_data->fields["estprov"];
                        $ls_emailrep      = $rs_data->fields["emailrep"];
                        $ls_grado         = $rs_data->fields["graemp"];
                        $ls_ctaant        = $rs_data->fields["sc_ctaant"];
                        $ls_denctaant     = $rs_data->fields["denctaant"];
                        $ls_tipperpro     = $rs_data->fields["tipperpro"];
						if ($ls_estatus==0)
						   {
						    $ls_estprov="A";
						   }
						else
						if ($ls_estatus==1)
						   {
						     $ls_estprov="I";
						   }
						else
						   if ($ls_estatus==2)
						   {
						     $ls_estprov="B";
						   }
						else
						   if ($ls_estatus==3)
						   {
						     $ls_estprov="S";
						   }
						$ls_fechavenRNC   = $io_funciones->uf_formatovalidofecha($rs_data->fields["fecvenrnc"]);
						$ls_fecvenRNC     = $io_funciones->uf_convertirfecmostrar($ls_fechavenRNC);
						$ls_regSSO        = $rs_data->fields["numregsso"];
												
						$ls_fechavenSSO   = $io_funciones->uf_formatovalidofecha($rs_data->fields["fecvensso"]);
						$ls_fecvenSSO     = $io_funciones->uf_convertirfecmostrar($ls_fechavenSSO);
						$ls_regINCE       = $rs_data->fields["numregince"];
						$ls_fechavenINCE  = $io_funciones->uf_formatovalidofecha($rs_data->fields["fecvenince"]);
						$ls_fecvenINCE    = $io_funciones->uf_convertirfecmostrar($ls_fechavenINCE);
					    $ls_codbansig     = $rs_data->fields["codbansig"];
						$ls_tipconpro     = $rs_data->fields["tipconpro"];
						$ls_denbansig     = "";
						$ls_sql2          = "SELECT denbansig FROM sigesp_banco_sigecof WHERE codbansig = '".$ls_codbansig."'"; 
						$rs_datos         = $io_sql->select($ls_sql2);
						$ld_hoy=date('Y')."-".date('m')."-".date('d');
						if($io_fecha->uf_comparar_fecha($ld_hoy,$ls_fechavenRNC))
						{
							$lb_registronacional="VIGENTE";
						}
						else
						{
							$lb_registronacional="VENCIDO";
						}
   	                    if ($row2=$io_sql->fetch_row($rs_datos))
	                       {
 		                     $ls_denbansig = $row2["denbansig"];   
						   }
						echo "<td width=75 style=text-align:center><a href=\"javascript: aceptar('$ls_codpro','$ls_nompro','$ls_dirpro','$ls_tipoorg','$ls_telpro','$ls_faxpro','$ls_nacpro','$ls_especialidad','$ls_rifpro','$ls_nitpro','$ld_capital','$ld_monmax',
						'$ls_banco','$ls_cuenta','$ls_moneda','$ls_provee','$ls_contra','$ls_contable','$ls_denocontable','$ls_observacion','$ls_cedula','$ls_nomrep','$ls_cargo',
						'$ls_numregRNC','$ls_registro','$ls_fecreg','$ls_numero','$ls_tomo','$ls_tommod','$ls_fecregRNC','$ls_fecregmod','$ls_regmod','$ls_nummod','$ls_numfol','$ls_numfolmod','$ls_numlic',
                     	'$ls_inspector','$ls_pais','$ls_estado','$ls_municipio','$ls_parroquia','$ls_pagweb','$ls_email','$ls_estprov','$ls_fecvenRNC','$ls_regSSO','$ls_fecvenSSO','$ls_regINCE','$ls_fecvenINCE',
						'$ls_emailrep','$ls_grado','$ls_codbansig','$ls_denbansig','$ls_tipconpro','$lb_registronacional','$ls_contablerecdoc','$ls_tipperrif','$ls_numpririf','$ls_numterrif','$ls_ctaant','$ls_denctaant','$ls_tipperpro');\">".$ls_codpro."</a></td>";
						echo "<td width=300 style=text-align:left>".$ls_nompro."</td>";
						echo "<td width=215 style=text-align:left>".$ls_dirpro."</td>";
						echo "<td width=80 style=text-align:left>".$ls_rifpro."</td>";
						echo "<td width=80 style=text-align:center>".$lb_registronacional."</td>";
						echo "</tr>";
						$rs_data->MoveNext();			
			}
   }
?>
</p>
</form>      
</body>
<script language="JavaScript">
function aceptar(codpro,nompro,dirpro,tipoorg,telpro,faxpro,nacpro,especialidad,rifpro,nitpro,capital,monmax,banco,cuenta,moneda,provee,contra,contable,denocontable,observacion,cedula,nomrep,cargo,
				 numregRNC,registro,fecreg,numero,tomo,tommod,fecregRNC,fecregmod,regmod,nummod,numfol,numfolmod,licencia,inspector,pais,estado,municipio,parroquia,pagweb,email,estatus,fecvenRNC,
				 regSSO,fecvenSSO,regINCE,fecvenINCE,emailrep,grado,codbansig,denbansig,ls_tipconpro,registronacional,contablerecdoc,as_tipperrif,as_numpririf,as_numterrif,ctaant,denctaant,tipperpro)
  {
    opener.document.form1.txtcodigo.value       = codpro;
	opener.document.form1.txtnombre.value       = nompro;
	opener.document.form1.txtdireccion.value    = dirpro;
	opener.document.form1.cmbtiporg.value       = tipoorg;
	opener.document.form1.txttelefono.value     = telpro;
	opener.document.form1.txtfax.value          = faxpro;
	opener.document.form1.cmbnacionalidad.value = nacpro;
	opener.document.form1.cmbespecialidad.value = especialidad;
	opener.document.form1.cmbtipperrif.value    = as_tipperrif;
	opener.document.form1.txtnumpririf.value    = as_numpririf;
	opener.document.form1.txtnumterrif.value    = as_numterrif;	
	opener.document.form1.txtnit.value          = nitpro;
	opener.document.form1.txtcapital.value      = capital;
	opener.document.form1.txtmonmax.value       = monmax;
	opener.document.form1.cmbbanco.value        = banco;
	opener.document.form1.txtcuenta.value       = cuenta;
	opener.document.form1.cmbmoneda.value       = moneda;
	opener.document.form1.cmbpais.value         = pais;
	opener.document.form1.cmbgraemp.value       = grado;
	opener.document.form1.chkestpro.checked=false;
	opener.document.form1.chkestcon.checked=false;
	if (provee=="1")
	   {
	     opener.document.form1.chkestpro.checked=true;
	   }
	if (contra=="1")
	   {
	     opener.document.form1.chkestcon.checked=true;
	   }
	opener.document.form1.txtcontable.value     = contable;
	opener.document.form1.txtdencuenta.value    = denocontable;
    opener.document.form1.txtobservacion.value  = observacion;
	opener.document.form1.txtcedula.value       = cedula;
	opener.document.form1.txtnomrep.value       = nomrep;
	opener.document.form1.txtcargo.value        = cargo;
	opener.document.form1.txtnumregRNC.value    = numregRNC;
	opener.document.form1.txtregistro.value     = registro;
	opener.document.form1.txtfecreg.value       = fecreg;
	opener.document.form1.txtnumero.value       = numero;
	opener.document.form1.txttomo.value         = tomo;
	opener.document.form1.txttommod.value       = tommod;
	opener.document.form1.txtfecregRNC.value    = fecregRNC;
	opener.document.form1.txtfecregmod.value    = fecregmod;
	opener.document.form1.txtregmod.value       = regmod;
	opener.document.form1.txtnummod.value       = nummod;
	opener.document.form1.txtnumfol.value       = numfol;
	opener.document.form1.txtnumfolmod.value    = numfolmod;
	opener.document.form1.txtnumlic.value       = licencia;
	opener.document.form1.txtemailrep.value     = emailrep;
	opener.document.form1.txtctaant.value       = ctaant;
	opener.document.form1.txtdenctaant.value    = denctaant;
	opener.document.form1.cmbtipopersona.value =  tipperpro;

	if(inspector==1)
	{
	opener.document.form1.cbinspector.checked=true;	
	}
	else
	{
	opener.document.form1.cbinspector.checked=false;	
	}
    
	opener.document.form1.hidestado.value=estado;
	opener.document.form1.hidmunicipio.value=municipio;
	opener.document.form1.hidparroquia.value=parroquia;
    opener.document.form1.hidestatus.value='GRABADO';
	opener.document.form1.txtpagweb.value=pagweb;
	opener.document.form1.txtemail.value=email;
    if (estatus=="A")
	   {
	     opener.document.form1.estprov[0].checked=true;
	   }
	else if(estatus=="I")
	   {
	     opener.document.form1.estprov[1].checked=true;
 	   }
	else if (estatus=="B")
  	   {
	     opener.document.form1.estprov[2].checked=true;
	   }
	else
	   {
	     opener.document.form1.estprov[3].checked=true;
	   }
	opener.document.form1.txtfecvenRNC.value     = fecvenRNC;
	opener.document.form1.txtregSSO.value        = regSSO;
	opener.document.form1.txtfecvenSSO.value     = fecvenSSO;
	opener.document.form1.txtregINCE.value       = regINCE;
    opener.document.form1.txtfecvenINCE.value    = fecvenINCE;
	opener.document.form1.txtcodbancof.value     = codbansig;
	opener.document.form1.txtnombancof.value     = denbansig;
	opener.document.form1.cmbcontribuyente.value = ls_tipconpro;
	opener.document.form1.txtestatusRNC.value = registronacional;
	opener.document.form1.txtcontablerecdoc.value = contablerecdoc;
	opener.document.form1.operacion.value        = "buscar";
	opener.document.form1.submit();
	close();
  }
  
  function ue_search()
  {
    f=document.form1;
    f.operacion.value="BUSCAR";
    f.action="sigesp_rpc_cat_proveedores.php";
    f.submit();
  }
</script>
</html>