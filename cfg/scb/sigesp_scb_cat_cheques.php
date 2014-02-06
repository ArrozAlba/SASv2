<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cheques</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
    <input name="operacion" type="hidden" id="operacion"></p>
  	 <table width="600" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="21" colspan="2" class="titulo-celda">Cat&aacute;logo de Cheques</td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="104" height="21"><div align="right">Cuenta</div></td>
        <td width="494" height="21"><div align="left">
          <input name="cuenta" type="text" id="cuenta" style="text-align:center" maxlength="25">        
        </div></td>
      </tr>
      <tr>
        <td height="21"><div align="right">Nombre</div></td>
        <td height="21"><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="60">
        </div></td>
      </tr>
      <tr>
        <td height="21"><div align="right">Banco</div></td>
        <td height="21"><input name="codigo" type="text" id="codigo"></td>
      </tr>
      <tr>
        <td height="21">&nbsp;</td>
        <td height="21"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	 <div align="center"><br>
<?php
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_funciones.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_codban    = "%".$_POST["codigo"]."%";
	 $ls_cuenta    = "%".$_POST["cuenta"]."%";
	 $ls_denctaban = $_POST["denominacion"];
   }
else
   {
	 $ls_operacion = "";
	 $ls_codban    = "";
	 $ls_cuenta    = "";
	 $ls_denctaban = "";
   }
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td style=text-align:center width=100>Chequera</td>";
print "<td style=text-align:center width=300>Banco</td>";
print "<td style=text-align:center width=200>Cuenta</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     $li_x=0;
	 $ls_sql="SELECT DISTINCT (scb_cheques.numchequera) as numchequera,
				     scb_cheques.codban as codban,
				     scb_cheques.ctaban as ctaban ,
				     scb_banco.nomban as nomban,
					 scb_ctabanco.dencta as dencta,
					 scb_tipocuenta.codtipcta as codtipcta,
					 scb_tipocuenta.nomtipcta as nomtipcta
 		        FROM scb_cheques, scb_banco, scb_ctabanco, scb_tipocuenta
			   WHERE scb_cheques.codemp = '".$ls_codemp."'
				 AND scb_cheques.codban like '".$ls_codban."' 
				 AND scb_cheques.ctaban like '".$ls_cuenta."'
				 AND scb_ctabanco.dencta like '%".$ls_denctaban."%'
				 AND scb_cheques.codemp=scb_banco.codemp 
				 AND scb_cheques.codban=scb_banco.codban 
				 AND scb_cheques.codemp=scb_ctabanco.codemp  
				 AND scb_banco.codban=scb_ctabanco.codban
				 AND scb_cheques.ctaban=scb_ctabanco.ctaban
				 AND scb_ctabanco.codtipcta=scb_tipocuenta.codtipcta";
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
			          $ls_codban = $rs_data->fields["codban"];
			          $ls_denban = $rs_data->fields["nomban"];
					  $ls_ctaban = $rs_data->fields["ctaban"];
					  $ls_dencta = $rs_data->fields["dencta"];
					  $ls_codtipcta = $rs_data->fields["codtipcta"];
					  $ls_dentipcta = $rs_data->fields["nomtipcta"];
					  $ls_chequera  = $rs_data->fields["numchequera"];
					  print "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_chequera','$ls_codban','$ls_denban','$ls_ctaban','$ls_dencta','$ls_codtipcta','$ls_dentipcta');\">".$ls_chequera."</a></td>";
					  print "<td style=text-align:left   width=300 title='".$ls_denban."'>".$ls_denban."</td>";
					  print "<td style=text-align:center width=200>".$ls_ctaban."</td>";
					  print "</tr>";			
                      $rs_data->MoveNext();
					}
			 }
		  else
		     {
			   $io_msg->message("No se han definido Chequeras");
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
function aceptar(numchequera,codban,nomban,ctaban,dencta,codtipcta,nomtipcta)
{
  opener.document.form1.txtchequera.value      = numchequera;
  opener.document.form1.txttipocuenta.value    = codtipcta;
  opener.document.form1.txtdentipocuenta.value = nomtipcta;
  opener.document.form1.txtcodban.value        = codban;
  opener.document.form1.txtdenban.value        = nomban;
  opener.document.form1.txtcuenta.value        = ctaban;
  opener.document.form1.txtdenominacion.value  = dencta;
  opener.document.form1.status.value           = 'G';
  opener.document.form1.operacion.value        = 'CARGAR';
  opener.document.form1.submit();
  close();
}
  
function ue_search()
{
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_scb_cat_cheques.php";
  f.submit();
}
</script>
</html>