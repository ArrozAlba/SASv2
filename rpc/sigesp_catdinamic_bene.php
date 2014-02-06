<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Beneficiarios</title>
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
require_once("../shared/class_folder/class_sql.php");
require_once("class_folder/sigesp_rpc_c_beneficiario.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");

$io_beneficiario = new sigesp_rpc_c_beneficiario();
$io_include      = new sigesp_include();
$ls_conect       = $io_include->uf_conectar();
$io_sql          = new class_sql($ls_conect);
$io_msg          = new class_mensajes(); 
$ls_codemp       = $_SESSION["la_empresa"]["codemp"];
$io_funcion      = new class_funciones();
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_cedula    = "%".$_POST["txtcedula"]."%";
	 $ls_nombre    = "%".$_POST["txtnombre"]."%";
   }
else
   {
	 $ls_operacion="";
   }
?>
<form name="form1" method="post" action="">
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="4" align="right"><div align="center">Cat&aacute;logo de Beneficiarios</div></td>
    </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="64" height="22" align="right">C&eacute;dula</td>
        <td width="139" height="22"><input name="txtcedula" type="text" id="txtcedula" maxlength="10" style="text-align:center">        </td>
        <td width="58" height="22" align="right">Apellido</td>
        <td width="200" height="22"><input name="txtapellido" type="text" id="txtapellido" size="26" maxlength="100"></td>
      </tr>
      <tr>
        <td height="22" align="right">Nombre</td>
        <td height="22"><input name="txtnombre" type="text" id="nombre" maxlength="100"></td>
        <td height="22" align="right">Banco</td>
        <td height="22"><?php

/*Llenar Combo Banco*/
$rs_ben=$io_beneficiario->uf_select_banco($ls_codemp);
?>  
          <select name="cmbbanco" id="cmbbanco" style="width:150px ">
            <option value="s1">---seleccione---</option>
            <?php
		while ($row=$io_sql->fetch_row($rs_ben))
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
      <tr>
   <td height="22" colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></div></td>
   </tr>
<input name="operacion" type="hidden" id="operacion"> 
</table> 
</form> 
<br>     
<div align="center">
<?php
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=100 style=text-align:center>Cédula</td>";
print "<td width=400 style=text-align:center>Nombre</td>";
print "</tr>";

if ($ls_operacion=="BUSCAR")
   {
		$ls_cedbene = "%".$_POST["txtcedula"]."%";
		$ls_nombene = "%".$_POST["txtnombre"]."%";
		$ls_apebene = "%".$_POST["txtapellido"]."%";
		$ls_codban  = "%".$_POST["cmbbanco"]."%";
		if ($ls_codban=="%s1%")
		   {  
	         $ls_codban="%%";	
		   } 
		$ls_sql = " SELECT rpc_beneficiario.ced_bene,rpc_beneficiario.rifben,rpc_beneficiario.nombene,rpc_beneficiario.apebene,
						   rpc_beneficiario.dirbene,rpc_beneficiario.telbene,rpc_beneficiario.celbene,rpc_beneficiario.email,
						   rpc_beneficiario.sc_cuenta,rpc_beneficiario.sc_cuentarecdoc,rpc_beneficiario.codban,
						   rpc_beneficiario.ctaban,rpc_beneficiario.codtipcta,rpc_beneficiario.codpai,rpc_beneficiario.codest,
						   rpc_beneficiario.codmun,rpc_beneficiario.codpar,rpc_beneficiario.codbansig,rpc_beneficiario.nacben,
						   rpc_beneficiario.numpasben,rpc_beneficiario.fecregben,rpc_beneficiario.tipconben,
						   scg_cuentas.denominacion as denscgcta,
						   (SELECT COALESCE(denbansig,'') as denbansig 
						      FROM sigesp_banco_sigecof 
							 WHERE rpc_beneficiario.codbansig=sigesp_banco_sigecof.codbansig) as denbansig
		              FROM rpc_beneficiario, scg_cuentas
				     WHERE rpc_beneficiario.ced_bene like '".$ls_cedbene."' 
				       AND rpc_beneficiario.nombene like '".$ls_nombene."' 
					   AND rpc_beneficiario.apebene like '".$ls_apebene."'
					   AND rpc_beneficiario.codban like '".$ls_codban."'					   
					   AND rpc_beneficiario.ced_bene<>'----------'
					   AND rpc_beneficiario.codemp=scg_cuentas.codemp
					   AND rpc_beneficiario.sc_cuenta=scg_cuentas.sc_cuenta
				     ORDER BY rpc_beneficiario.ced_bene ASC";	
		
      $rs_data = $io_sql->select($ls_sql);//echo $ls_sql.'<br>';
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
					   $ls_cedbene = trim($rs_data->fields["ced_bene"]);
					   $ls_rifbene = trim($rs_data->fields["rifben"]);
					   $ls_tipperrif = substr($ls_rifbene,0,1);//Tipo Persona RIF.(J=Juridico,G=Gubernamental,V=Natural Venezolano,E=Natural Extranjero).
					   $ls_numpririf = substr($ls_rifbene,2,8);//Número Principal del RIF, 8 Dígitos (0-9).
					   $ls_numterrif = substr($ls_rifbene,11,1);//Número Terminal  del RIF, 1 Dígitos (0-9).
					   $ls_nombene = $rs_data->fields["nombene"];
					   $ls_apebene = ltrim($rs_data->fields["apebene"]);
					   if (!empty($ls_apebene))
						  {
						    $ls_nombre = $ls_apebene.', '.$ls_nombene;
						  }
					   else
						  {
						    $ls_nombre = $ls_nombene;
						  }
					   $ls_dirbene      = $rs_data->fields["dirbene"];
					   $ls_telbene      = $rs_data->fields["telbene"];
					   $ls_celbene      = $rs_data->fields["celbene"];
					   $ls_email        = $rs_data->fields["email"];
					   $ls_contable     = trim($rs_data->fields["sc_cuenta"]);
					   $ls_scgrecdoc    = trim($rs_data->fields["sc_cuentarecdoc"]);
					   $ls_denocontable = $rs_data->fields["denscgcta"];
					   $ls_banco        = $rs_data->fields["codban"];
					   $ls_cuenta       = $rs_data->fields["ctaban"];
					   $ls_tipocuenta   = $rs_data->fields["codtipcta"];
					   $ls_pais         = $rs_data->fields["codpai"];
                       $ls_estado       = $rs_data->fields["codest"];
					   $ls_municipio    = $rs_data->fields["codmun"];
                       $ls_parroquia    = $rs_data->fields["codpar"];
					   $ls_codbansig    = trim($rs_data->fields["codbansig"]);
					   $ls_nacben       = $rs_data->fields["nacben"];
					   $ls_numpasben    = $rs_data->fields["numpasben"];
					   $ls_fecregben    = $rs_data->fields["fecregben"];
					   $ls_fecregben    = $io_funcion->uf_convertirfecmostrar($ls_fecregben);
					   $ls_tipconben    = $rs_data->fields["tipconben"];
					   $ls_denbansig    = $rs_data->fields["denbansig"];
					   echo "<td  style=text-align:center width=100><a href=\"javascript: aceptar('$ls_cedbene','$ls_nombene','$ls_apebene','$ls_dirbene','$ls_telbene','$ls_celbene','$ls_email','$ls_contable','$ls_denocontable','$ls_banco','$ls_cuenta','$ls_tipocuenta','$ls_pais','$ls_estado','$ls_municipio','$ls_parroquia','$ls_codbansig','$ls_denbansig','$ls_tipperrif','$ls_numpririf','$ls_numterrif','$ls_fecregben','$ls_nacben','$ls_numpasben','$ls_tipconben','$$ls_scgrecdoc');\">".$ls_cedbene."</a></td>";
					   echo "<td  style=text-align:left   width=400 title='".ltrim($ls_nombre)."'>".$ls_nombre."</td>";
					   echo "</tr>";					   
		               $rs_data->MoveNext();
					 }
	          }	
		   else
		      {
			    $io_msg->message("No se han definido Beneficiarios !!!");
			  } 
		 }
   }
echo "</table>";
?>
</div>
</body>
<script language="JavaScript">
fop = opener.document.form1;
  function aceptar(cedula,nombre,apellido,direccion,telefono,celular,email,contable,denocontable,banco,cuenta,tipocuenta,pais,estado,municipio,parroquia,codbansig,denbansig,as_tipperrif,as_numpririf,as_numterrif,ls_fecregben,ls_nacben,ls_numpasben,ls_tipconben,ls_contablerecdoc)
  {
    fop.txtcedula.value    = cedula;
	fop.txtcedula.readOnly = true;
	fop.cmbtipperrif.value = as_tipperrif;
	fop.txtnumpririf.value = as_numpririf;
	fop.txtnumterrif.value = as_numterrif;
	fop.txtnombre.value    = nombre;
	fop.txtapellido.value  = apellido;
	fop.txtdireccion.value = direccion;
	fop.txttelefono.value  = telefono;
	fop.txtcelular.value   = celular;
	fop.txtemail.value     = email;
	fop.txtcontable.value  = contable;
	fop.txtcontablerecdoc.value  = ls_contablerecdoc;
	fop.txtdencuenta.value = denocontable;
	fop.cmbbanco.value     = banco;
	fop.txtcuenta.value    = cuenta;
	fop.cmbtipcue.value    = tipocuenta;
	fop.cmbpais.value      = pais;
	fop.hidestado.value    = estado;
	fop.hidmunicipio.value = municipio;
	fop.hidparroquia.value = parroquia;
	fop.txtcodbancof.value = codbansig;
	fop.txtnombancof.value = denbansig;
	fop.txtfecregben.value = ls_fecregben;
	fop.txtnumpasben.value = ls_numpasben;
	fop.cmbcontribuyente.value = ls_tipconben;
	if (ls_nacben=='V')
	   {
	     fop.radionacionalidad[0].checked = true;
	   }
	else
	   {
	     fop.radionacionalidad[1].checked = true;
	   }
	fop.operacion.value    = "buscar";
	fop.hidestatus.value   = "GRABADO";
	fop.submit();
	close();
  }
  
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_catdinamic_bene.php";
	  f.submit();
  }
</script>
</html>