<?php
session_start();
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cheques</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<?php
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codban=$_POST["codban"];
	$ls_cuenta=$_POST["txtcuenta"];
	$ls_nomban=$_POST["txtnomban"];
	$ls_nomcuenta=$_POST["txtnomcuenta"];	
}
else
{
	$ls_operacion="";
	$ls_codban=$_GET["codban"];
	$ls_nomban=$_GET["nomban"];
	$ls_cuenta=$_GET["cuenta"];
	$ls_nomcuenta=$_GET["nomcuenta"];
}
?>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cheques </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td><div align="right">Banco</div></td>
        <td><input name="txtnomban" type="text" id="codigo" value="<?php print $ls_nomban?>" readonly="true">
		<input type="hidden" name="codban" id="codban" value="<?php print $ls_codban?>">
		</td>
      </tr>
      <tr>
        <td width="67"><div align="right">Cuenta</div></td>
        <td width="431"><div align="left">
          <input name="txtcuenta" type="text" id="txtcuenta" value="<?php print $ls_cuenta?>" readonly="true">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="txtnomcuenta" type="text" id="txtnomcuenta" size="60" value="<?php print $ls_nomcuenta?>" readonly="true">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	 <div align="center"><br>
<?php
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();
require_once("../shared/class_folder/class_funciones.php");
$fun=new class_funciones();
$ls_codemp=$arr["codemp"];
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Chequera</td>";
print "<td>Banco</td>";
print "<td>Cuenta</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	
		$li_x=0;
		$ls_sql="SELECT DISTINCT (a.numchequera) as numchequera,a.codban as codban,a.ctaban as ctaban ,b.nomban as nomban,c.dencta as dencta,d.codtipcta as codtipcta,d.nomtipcta as nomtipcta
			      FROM scb_cheques a,scb_banco b,scb_ctabanco c,scb_tipocuenta d
			     WHERE a.codemp='".$ls_codemp."' 
				   AND a.codban  like '".$ls_codban."' 
				   AND a.ctaban like '".$ls_cuenta."'
				   AND a.codemp=b.codemp 
				   AND a.codemp=c.codemp  
				   AND a.codban=b.codban 
				   AND a.ctaban=c.ctaban
			       AND b.codban=c.codban 
				   AND c.codtipcta=d.codtipcta";
			 
			$rs_cta=$SQL->select($ls_sql);
			$data=$rs_cta;
			if($rs_cta===false)
			{
				$io_msg("Error en select");
			}
			else
			{
				while($row=$SQL->fetch_row($rs_cta))
				{
					$li_x=$li_x+1;						
					print "<tr class=celdas-blancas>";
						$codban=$row["codban"];
						$nomban=$row["nomban"];
						$ctaban=$row["ctaban"];
						$dencta=$row["dencta"];
						$codtipcta=$row["codtipcta"];
						$nomtipcta=$row["nomtipcta"];
						$numchequera=$row["numchequera"];
						print "<td align=center><a href=\"javascript: aceptar('$numchequera','$codban','$nomban','$ctaban','$dencta','$codtipcta','$nomtipcta');\">".$numchequera."</a></td>";
						print "<td align=center>".$nomban."</td>";
						print "<td align=center>".$ctaban."</td>";
					print "</tr>";			
					
				}
				if($li_x==0)				
				{
					$io_msg->message("No se han definido Chequeras");
				}
		 }
}
print "</table>";
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
		opener.document.form1.txtchequera.value  = numchequera;
		/*opener.document.form1.txttipocuenta.value= codtipcta;
		/*opener.document.form1.txtdentipocuenta.value= nomtipcta;
		opener.document.form1.txtcodban.value    = codban;
		opener.document.form1.txtdenban.value    = nomban;
		opener.document.form1.txtcuenta.value    = ctaban;
		opener.document.form1.txtdenominacion.value=dencta;
		opener.document.form1.status.value='C';
		opener.document.form1.operacion.value='CARGAR';
		opener.document.form1.submit();*/
		close();
  }
  
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.submit();
  }
</script>
</html>