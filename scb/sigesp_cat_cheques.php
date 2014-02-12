<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "opener.document.form1.submit();";
	 print "close();";
	 print "</script>";		
   }
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg		= new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$io_funcion = new class_funciones();
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_codban    = $_POST["codigo"];
	 $ls_ctaban    = $_POST["cuenta"];	
   }
else
   {
	 $ls_operacion = "";
	 $ls_codban    = $_GET["codban"];
	 $ls_ctaban    = $_GET["ctaban"];
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cheques</title>
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
<form name="form1" method="post" action="">
  <p align="center">&nbsp;</p>
  	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de Cheques</td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Banco</td>
        <td height="22"><input name="codigo" type="text" id="codigo" value="<?php  print $ls_codban;?>" size="35"></td>
      </tr>
      <tr>
        <td width="75" height="22" style="text-align:right">Cuenta</td>
        <td width="423" height="22"><input name="cuenta" type="text" id="cuenta" value="<?php  print $ls_ctaban;?>" size="35"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	 <p align="center">
<?php
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Cheque</td>";
print "<td>Chequera</td>";
print "<td>Banco</td>";
print "<td>Cuenta</td>";
print "<td>Estatus</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql = "SELECT scb_cheques.codban as codban,scb_cheques.ctaban as ctaban ,scb_cheques.numche as numche,
	                   scb_cheques.estche as estche,scb_cheques.numchequera as numchequera,scb_banco.nomban as nomban,
					   scb_ctabanco.dencta as dencta,scb_tipocuenta.codtipcta as codtipcta,scb_tipocuenta.nomtipcta as nomtipcta
			      FROM scb_cheques ,scb_banco ,scb_ctabanco ,scb_tipocuenta
		 	     WHERE scb_cheques.codemp='".$ls_codemp."' 
				   AND scb_cheques.codban  like '%".$ls_codban."%' 
				   AND scb_cheques.ctaban like '%".$ls_ctaban."%'
				   AND scb_cheques.estche<>1
				   AND scb_cheques.codemp=scb_banco.codemp 
				   AND scb_cheques.codban=scb_banco.codban 
				   AND scb_cheques.codemp=scb_ctabanco.codemp 
			       AND scb_banco.codban=scb_ctabanco.codban 
				   AND scb_cheques.ctaban=scb_ctabanco.ctaban
				   AND scb_ctabanco.codtipcta=scb_tipocuenta.codtipcta
				   ORDER BY scb_cheques.numchequera, scb_cheques.numche";
     
	 $rs_data = $io_sql->select($ls_sql);
     if ($rs_data===false)
	    {
		  $io_msg->message("Error en Select de Cheques !!!");
		}
     else
	    {
		  $li_numrows = $io_sql->num_rows($rs_data);
		  if ($li_numrows>0)
		     {
			   while($row=$io_sql->fetch_row($rs_data))
			        {
					  print "<tr class=celdas-blancas>";
					  $ls_codban	  = $row["codban"];
					  $ls_nomban	  = $row["nomban"];
					  $ls_ctaban	  = $row["ctaban"];
					  $ls_dencta	  = $row["dencta"];
					  $ls_codtipcta   = $row["codtipcta"];
					  $ls_nomtipcta   = $row["nomtipcta"];
					  $ls_numche	  = $row["numche"];
					  $ls_numchequera = $row["numchequera"];
					  $ls_status      = $row["estche"];
					  print "<td align=center><a href=\"javascript: aceptar('$ls_numche','$ls_numchequera','$ls_codban','$ls_nomban','$ls_ctaban','$ls_dencta','$ls_status','$ls_codtipcta','$ls_nomtipcta');\">".$ls_numche."</a></td>";
					  print "<td align=center>".$ls_numchequera."</td>";		
					  print "<td align=center>".$ls_nomban."</td>";
					  print "<td align=center>".$ls_ctaban."</td>";
					  print "<td align=center>".$ls_status."</td>";					
					  print "</tr>";
					}
			 }
		  else
		     {
			   $io_msg->message("No se han definido Cheques !!!");
			 }
		}
   }
print "</table>";
?>
    </p>
</form>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(numche,numchequera,codban,nomban,ctaban,dencta,status,codtipcta,nomtipcta)
  {
		opener.document.form1.txtdocumento.value = numche;
		opener.document.form1.txtchequera.value = numchequera;
		close();
  }
  
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_cheques.php";
	  f.submit();
  }
</script>
</html>