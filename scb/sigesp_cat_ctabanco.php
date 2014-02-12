<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
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
<?php
require_once("sigesp_c_cuentas_banco.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");

$io_include  = new sigesp_include();
$ls_conect   = $io_include->uf_conectar();
$io_sql      = new class_sql($ls_conect);
$io_msg      = new class_mensajes();
$io_function = new class_funciones();
$ls_codemp   = $_SESSION["la_empresa"]["codemp"];
$io_ctaban   = new sigesp_c_cuentas_banco();

if (array_key_exists("operacion",$_POST))
   {
		 $ls_operacion = $_POST["operacion"];
		 $ls_codigo    = $_POST["codigo"];
		 $ls_nomban    = $_POST["hidnomban"];
		 $ls_ctaban    = $_POST["cuenta"];
		 $ls_denctaban = $_POST["denominacion"];
		 $ls_codcon= $_POST["codcon"];
   }
else
   {
	 $ls_operacion = "BUSCAR";
	 $ls_codigo    = $_GET["codigo"];
	 $ls_nomban    = $_GET["hidnomban"];
	 if (array_key_exists("codcon",$_GET))
	    {
		  $ls_codcon=$_GET["codcon"];
	    }
	 else
	    {
		  $ls_codcon='---';
	    }
	 $ls_ctaban    = "";
	 $ls_denctaban = "";
   }
?>
<table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        <input name="codigo" type="hidden" id="codigo" value="<?php print $ls_codigo;?>">
          <input name="hidnomban" type="hidden" id="hidnomban" value="<?php print $ls_nomban ?>">
          Cat&aacute;logo de Cuentas <?php print $ls_nomban ?></td>
    </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="67" height="22" style="text-align:right">Cuenta</td>
        <td width="431" height="22"><div align="left">
          <input name="cuenta" type="text" id="cuenta" size="35" maxlength="25" style="text-align:center">        
        </div></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Nombre</td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="60">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
  </table>
  <p align="center">
<?php
echo "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda height=22>";
echo "<td style=text-align:center>Código</td>";
echo "<td style=text-align:center>Denominación</td>";
echo "<td style=text-align:center>Tipo</td>";
echo "<td style=text-align:center>Contable</td>";
echo "<td style=text-align:center>Descripción</td>";
echo "<td style=text-align:center>Apertura</td>";
echo "</tr>";
$ls_casacon=$_SESSION["la_empresa"]["casconmov"];
if ($ls_operacion=="BUSCAR")
   {
	 if (($ls_casacon==1)&&($ls_codcon!=="---"))
	 {
	 	 $ls_sql="SELECT scb_ctabanco.ctaban as ctaban,scb_ctabanco.dencta as dencta,TRIM(scb_ctabanco.sc_cuenta) as sc_cuenta,
					 scg_cuentas.denominacion as denominacion,scb_ctabanco.codban as codban,scb_banco.nomban as nomban,
					 scb_ctabanco.codtipcta as codtipcta,scb_tipocuenta.nomtipcta as nomtipcta,scb_ctabanco.fecapr as fecapr,
					 scb_ctabanco.feccie as feccie,scb_ctabanco.estact as estact
			    FROM scb_ctabanco, scb_tipocuenta, scb_banco, scg_cuentas, scb_casamientoconcepto
			   WHERE scb_ctabanco.codemp='".$ls_codemp."' 
			     AND scb_ctabanco.codban like '%".$ls_codigo."%'  
				 AND scb_ctabanco.ctaban like '".$ls_ctaban."%' 
				 AND scb_casamientoconcepto.codconmov='".$ls_codcon."'
				 AND scb_casamientoconcepto.codban='".$ls_codigo."'
				 AND UPPER(scb_ctabanco.dencta) like '%".strtoupper($ls_denctaban)."%'
			     AND scb_ctabanco.codtipcta=scb_tipocuenta.codtipcta 
			     AND scb_ctabanco.codban=scb_banco.codban 
				 AND scb_ctabanco.sc_cuenta=scg_cuentas.sc_cuenta 
				 AND scb_ctabanco.codemp=scg_cuentas.codemp".
			"   AND scb_ctabanco.ctaban IN (SELECT codintper ".
			"					 FROM sss_permisos_internos ".
			"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
			"				    UNION ".
			"				   SELECT codintper ".
			"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
			"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
	 }
	 else
	 {
		 $ls_sql="SELECT scb_ctabanco.ctaban as ctaban,scb_ctabanco.dencta as dencta,TRIM(scb_ctabanco.sc_cuenta) as sc_cuenta,
						 scg_cuentas.denominacion as denominacion,scb_ctabanco.codban as codban,scb_banco.nomban as nomban,
						 scb_ctabanco.codtipcta as codtipcta,scb_tipocuenta.nomtipcta as nomtipcta,scb_ctabanco.fecapr as fecapr,
						 scb_ctabanco.feccie as feccie,scb_ctabanco.estact as estact
					FROM scb_ctabanco, scb_tipocuenta, scb_banco, scg_cuentas
				   WHERE scb_ctabanco.codemp='".$ls_codemp."' 
					 AND scb_ctabanco.codban like '%".$ls_codigo."%'  
					 AND scb_ctabanco.ctaban like '".$ls_ctaban."%' 
					 AND UPPER(scb_ctabanco.dencta) like '%".strtoupper($ls_denctaban)."%'
					 AND scb_ctabanco.codtipcta=scb_tipocuenta.codtipcta 
					 AND scb_ctabanco.codban=scb_banco.codban 
					 AND scb_ctabanco.sc_cuenta=scg_cuentas.sc_cuenta 
					 AND scb_ctabanco.codemp=scg_cuentas.codemp".
			"   AND scb_ctabanco.ctaban IN (SELECT codintper ".
			"					 FROM sss_permisos_internos ".
			"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
			"				    UNION ".
			"				   SELECT codintper ".
			"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
			"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
	}

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
					   $ls_codban 	   = trim(str_pad($rs_data->fields["codban"],3,0,0));
					   $ls_nomban 	   = $rs_data->fields["nomban"];
					   $ls_ctaban      = trim($rs_data->fields["ctaban"]);
					   $ls_dencta      = $rs_data->fields["dencta"];
					   $ls_codtipcta   = $rs_data->fields["codtipcta"];
					   $ls_nomtipcta   = $rs_data->fields["nomtipcta"];
					   $ls_ctascg      = $rs_data->fields["sc_cuenta"];
					   $ls_denctascg   = $rs_data->fields["denominacion"];
					   $ls_fecapertura = $io_function->uf_convertirfecmostrar($rs_data->fields["fecapr"]);
					   $ls_feccierre   = $io_function->uf_convertirfecmostrar($rs_data->fields["feccie"]);
					   $io_ctaban->uf_verificar_saldo($ls_codban,$ls_ctaban,&$adec_saldo);					  
					   if ($adec_saldo>0)
					      {
						    echo "<tr class=celdas-azules>";						   
						  }
					   else
					      {
						    echo "<tr class=celdas-blancas>"; 
						  }
					   $ldec_saldo = number_format($adec_saldo,2,',','.');
					   $ls_status  = $rs_data->fields["estact"];
					   echo "<td style=text-align:center><a href=\"javascript: aceptar('$ls_codban','$ls_nomban','$ls_ctaban','$ls_dencta','$ls_ctascg','$ls_denctascg','$ls_fecapertura','$ls_feccierre','$ls_status','$ls_codtipcta','$ls_nomtipcta','$ldec_saldo');\">".$ls_ctaban."</a></td>";
					   echo "<td style=text-align:left title='".$ls_dencta."'>".$ls_dencta."</td>";
					   echo "<td style=text-align:left title='".$ls_nomtipcta."'>".$ls_nomtipcta."</td>";
					   echo "<td style=text-align:center>".$ls_ctascg."</td>";
					   echo "<td style=text-align:left title='".$ls_denctascg."'>".$ls_denctascg."</td>";																			
					   echo "<td style=text-align:center>".$ls_fecapertura."</td>";					
					   echo "</tr>";			
                       $rs_data->MoveNext();
					 }
			  }
		   else
		      {
			  		if (($ls_casacon==1)&&($ls_codcon!=="---"))
					{
			    		$io_msg->message("No se han creado Cuentas Bancarias !!!");
					}
					else
					{
						$io_msg->message("No se han asociado Cuentas Bancarias  al tipo de concepto seleccionado!!!");
					}   
			  }
		 }  		 
   }
echo "</table>";
?></p>
<input name="codcon" type="hidden" id="codcon" value="<? print $ls_codcon;?>">

</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(codban,nomban,ctaban,dencta,ctascg,denctascg,fecapertura,feccierre,status,codtipcta,nomtipcta,saldo)
  {
    fop 	  = opener.document.form1;
	ls_opener = opener.document.form1.id;
	if (ls_opener=='sigesp_scb_p_progpago_creditos.php')
	   {
	     li_filsel = fop.hidfilsel.value;
		 ls_denctaban = ctaban+" - "+dencta;
		 eval("fop.txtctaban"+li_filsel+".value="+"'      "+ls_denctaban+"'");
		 eval("fop.txtctaban"+li_filsel+".title="+"'"+ls_denctaban+"'");
		 eval("fop.hidctaban"+li_filsel+".value="+"'"+ctaban+"'");
	   }
	else
	   {
		 if (ls_opener=='sigesp_scb_p_liquidacion_creditos.php')
		    {
			  if (fop.hidcodope.value=='CH')
			     {
				   li_filins = fop.hidtotrowscg.value;//Fila donde insertaremos la Cuenta Contable y su denominación.
				   eval("fop.txtscgcta"+li_filins+".value="+ctascg);
				   eval("fop.txtdenscgcta"+li_filins+".value="+"'"+denctascg+"'");
				   eval("fop.txtdenscgcta"+li_filins+".title="+"'"+denctascg+"'");
				   fop.txtctaban.value    = ctaban;
				   fop.txtdenctaban.value = dencta;
				   fop.hidscgcta.value    = ctascg;
				 }
			}
		 else
		    {
			  if (ls_opener=='sigesp_scb_p_progpago.php')
			     {
				   lb_valido = uf_evaluate_datos_programacion(ctaban);
				   if (lb_valido)
				      {
					    fop.txtcuenta.value=ctaban;
					    fop.txtdenominacion.value=dencta;
					    fop.txttipocuenta.value=codtipcta;
					    fop.txtdentipocuenta.value=nomtipcta;
					    fop.txtcuenta_scg.value=ctascg;
					    fop.txtdisponible.value=saldo;
					  }
				 }
			  else
			     {
				   fop.txtcuenta.value=ctaban;
				   fop.txtdenominacion.value=dencta;
				   fop.txttipocuenta.value=codtipcta;
				   fop.txtdentipocuenta.value=nomtipcta;
				   fop.txtcuenta_scg.value=ctascg;
				   fop.txtdisponible.value=saldo;
				 }
			}
	   }
	close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_ctabanco.php";
  f.submit();
  }

function uf_evaluate_datos_programacion(as_ctaban)
{
  fop       = opener.document.form1;
  li_totrow = fop.totsol.value;
  lb_valido = true;
  for (li_i=1;li_i<=li_totrow;li_i++)
      {
	    if (eval("fop.chksel"+li_i+".checked"))
		   {
			 ls_ctaban = eval("fop.hidctaban"+li_i+".value");			 
			 if (ls_ctaban!="")
			    {
				  if (ls_ctaban!=as_ctaban)
				     {
					   lb_valido = false;
					   ls_numsol = eval("fop.txtnumsol"+li_i+".value");
					   alert("Solicitud "+ls_numsol+ ", esta asociada a Orden de Pago Ministerio emitida por Cuenta Bancaria Distinta !!!");
					 }
				}
		   }
	  }
  return lb_valido;
}  
</script>
</html>