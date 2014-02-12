<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Bancarias</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
</head>

<body>
<form name="form1" method="post" action="">
<?php
require_once("sigesp_c_cuentas_banco.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$io_funcion = new class_funciones();
$ls_codemp  = $arr["codemp"];
$io_ctaban  = new sigesp_c_cuentas_banco();

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo=$_POST["codigo"];
	$ls_denban=$_POST["denban"];
	$ls_ctaban="%".$_POST["cuenta"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
}
else
{
	$ls_operacion="";
	$ls_codigo=$_GET["codigo"];
	$ls_denban=$_GET["denban"];
}
?>
<table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de Cuentas Bancarias <?php echo $ls_denban ?></td>
       </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="67" height="22" style="text-align:right">Cuenta</td>
        <td width="431" height="22"><div align="left">
          <input name="cuenta" type="text" id="cuenta">        
        </div></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Nombre</td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="60">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Banco</div></td>
        <td height="22"><input name="denban" type="text" id="denban" value="<?php print $ls_denban;?>">
        <input name="codigo" type="hidden" id="codigo" value="<?php print $ls_codigo;?>"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<div align="center"><br>
<?php
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "<td>Banco</td>";
print "<td>Tipo de Cuenta</td>";
print "<td>Cuenta Contable</td>";
print "<td>Denominación Cta. Contable</td>";
print "<td>Apertura</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     $ls_sql = "SELECT scb_ctabanco.ctaban as ctaban,scb_ctabanco.dencta as dencta,
	                   trim(scb_ctabanco.sc_cuenta) as sc_cuenta,
	                   scg_cuentas.denominacion as denominacion,scb_ctabanco.codban as codban,scb_banco.nomban as nomban,
					   scb_ctabanco.codtipcta as codtipcta,scb_tipocuenta.nomtipcta as nomtipcta,
					   scb_ctabanco.fecapr as fecapr,scb_ctabanco.feccie as feccie,scb_ctabanco.estact as estact
				  FROM scb_ctabanco, scb_tipocuenta, scb_banco, scg_cuentas
                 WHERE scb_ctabanco.codemp='".$ls_codemp."'
				   AND scb_ctabanco.codban like '%".$ls_codigo."%'
				   AND scb_ctabanco.ctaban like '".$ls_ctaban."'
				   AND scb_ctabanco.codtipcta=scb_tipocuenta.codtipcta 
				   AND scb_ctabanco.codban=scb_banco.codban 
				   AND trim(scb_ctabanco.sc_cuenta)=trim(scg_cuentas.sc_cuenta)
   				   AND scb_ctabanco.codemp=scg_cuentas.codemp ".
			"   AND ctaban IN (SELECT codintper ".
			"					 FROM sss_permisos_internos ".
			"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
			"				    UNION ".
			"				   SELECT codintper ".
			"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
			"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)".
			"	 ORDER BY ctaban ASC";
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
			           $ls_codban = $rs_data->fields["codban"];
			           $ls_denban = $rs_data->fields["nomban"];
					   $ls_ctaban = $rs_data->fields["ctaban"];
					   $ls_denctaban = $rs_data->fields["dencta"];
					   $ls_codtipcta = $rs_data->fields["codtipcta"];
					   $ls_nomtipcta = $rs_data->fields["nomtipcta"];
					   $ls_scgcta    = $rs_data->fields["sc_cuenta"];
					   $ls_denscgcta = $rs_data->fields["denominacion"];					   
					   $fecapertura  = $io_funcion->uf_convertirfecmostrar($rs_data->fields["fecapr"]);
					   $feccierre    = $io_funcion->uf_convertirfecmostrar($rs_data->fields["feccie"]);
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
					   $ls_estctaban = $rs_data->fields["estact"];				   
					   echo "<td><a href=\"javascript: aceptar('$ls_codban','$ls_denban','$ls_ctaban','$ls_denctaban','$ls_scgcta','$ls_denscgcta','$fecapertura','$feccierre','$ls_estctaban','$ls_codtipcta','$ls_nomtipcta','$ldec_saldo');\">".$ls_ctaban."</a></td>";
					   echo "<td>".$ls_denctaban."</td>";
					   echo "<td>".$ls_denban."</td>";
					   echo "<td>".$ls_nomtipcta."</td>";
					   echo "<td>".$ls_scgcta."</td>";
					   echo "<td>".$ls_denscgcta."</td>";																			
					   echo "<td>".$fecapertura."</td>";					
					   echo "</tr>";				   
                       $rs_data->MoveNext();
					 }
			  }
		   else
		      {
			    $io_msg->message("No se han definido Cuentas Bancarias !!!");
			  }
		 }  		 
   }
echo "</table>";
?>
      </div>
    </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(codban,nomban,ctaban,dencta,ctascg,denctascg,fecapertura,feccierre,status,codtipcta,nomtipcta,saldo)
  {
  	fop=opener.document.form1;
    fop.txtcuentadestino.value=ctaban;
    fop.txtdenominaciondestino.value=dencta;
	fop.txttipocuentadestino.value=codtipcta;
	fop.txtdentipocuentadestino.value=nomtipcta;
	fop.txtcuenta_scgdestino.value=ctascg;
	fop.txtdisponibledestino.value=saldo;	
	opener.uf_verificar_operacion();
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_ctabancodestino.php";
  f.submit();
  }
</script>
</html>
