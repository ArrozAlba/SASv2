<?php
session_start();
require_once("../../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
require_once("../../shared/class_folder/class_funciones.php");
$fun=new class_funciones();
require_once("../../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
require_once("../../shared/class_folder/class_mensajes.php");
$io_msg = new class_mensajes();//Instanciando la Clase Class  Mensajes.

$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];


if(array_key_exists("operacion",$_POST))
{
  $ls_operacion=$_POST["operacion"];
 }
else
{
  $ls_operacion="";
}


if(array_key_exists("codigo",$_POST))
{
  $ls_codigo=$_POST["codigo"];
 }
else
{
  $ls_codigo="";
}


if(array_key_exists("nombre",$_POST))
{
  $ls_denominacion=$_POST["nombre"];
 }
else
{
  $ls_denominacion="";
}


if(array_key_exists("txtcuentascg",$_POST))
{
  $ls_cuentaspg=$_POST["txtcuentascg"];
 }
else
{
  $ls_cuentaspg="";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Presupuestarias</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
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
  <p align="center">&nbsp;  </p>
  <table width="550" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="22" colspan="2" class="titulo-celda" style="text-align:center">Cat&aacute;logo de Cuentas Presupuestaria</td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="114" height="22" align="right">Codigo&nbsp;</td>
        <td width="156" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" value="<?php print $ls_codigo ?>" size="22" maxlength="20" onKeyPress="return keyRestrict(event,'1234567890');">        
        </div></td>
        <td width="278" height="22" colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n&nbsp;</div></td>
        <td height="22" colspan="5"><div align="left">
          <input name="nombre" type="text" id="nombre" value="<?php print $ls_denominacion ?>" size="72">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Cuenta Contable&nbsp;</div></td>
        <td height="22" colspan="5"><div align="left">
          <input name="txtcuentascg" type="text" id="txtcuentascg" value="<?php print $ls_cuentaspg ?>" size="22" maxlength="20"  onKeyPress="return keyRestrict(event,'1234567890');">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22" colspan="4"><div align="right"><a href="javascript: ue_search();">
          <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion ?>">
        <img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?php
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Presupuestaria</td>";
print "<td>Denominación</td>";
print "<td>Contable</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{

   $ls_sql = " SELECT  soc_servic     ".
	         " FROM    sigesp_empresa ".
		     " WHERE   codemp = '".$as_codemp."' ";	 
   $rs=$SQL->select($ls_sql);
   $li_numrows=$SQL->num_rows($rs);
   if ($li_numrows===false)
   {
	  $lb_valido=false;            
	  $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
   }            
   else
   {
	    $ls_sql = " SELECT  DISTINCT spg_cuenta, max(denominacion) as denominacion, max(codestpro1) as codestpro1,     ".
		          "                  max(codestpro2) as codestpro2, max(codestpro3) as codestpro3,                     ".
                  "                  max(sc_cuenta) as sc_cuenta, max(status) as status                                ".
	              " FROM    spg_cuentas                                                                                ".
		          " WHERE   codemp = '".$as_codemp."'                   AND  spg_cuenta like '%".$ls_codigo."%' AND    ".
				  "         denominacion like '%".$ls_denominacion."%'  AND  sc_cuenta  like '%".$ls_cuentaspg."%'     ";
				  
		
		while ($row=$SQL->fetch_row($rs))	  
		{						  
			 $ls_cadena = trim($row["soc_servic"]);                  
		}
   
		$la_spg_cuenta=split(",",$ls_cadena);
		$li_total=count($la_spg_cuenta);
		for($li_i=0;$li_i<$li_total;$li_i++)
		{
			if($li_i==0)
			{
				$ls_sql=$ls_sql."   AND spg_cuenta like '".$la_spg_cuenta[$li_i]."%'";
			}
			else
			{
				$ls_sql=$ls_sql."    OR spg_cuenta like '".$la_spg_cuenta[$li_i]."%'";
			}
		
		}						
		$ls_sql=$ls_sql." GROUP BY spg_cuenta ORDER BY spg_cuenta ASC"; 
		$rs_cta=$SQL->select($ls_sql);
		
		if($rs_cta==false)
		{
			$io_msg->message($fun->uf_convertirmsg($SQL->message));
		}
		else
		{
			$data=$rs_cta;
			if($row=$SQL->fetch_row($rs_cta))
			{
				$data=$SQL->obtener_datos($rs_cta);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$ds->data=$data;
				$totrow=$ds->getRowCount("spg_cuenta");
				for($z=1;$z<=$totrow;$z++)
				{
					$cuenta=$data["spg_cuenta"][$z];
					$denominacion=$data["denominacion"][$z];
					$codest1=$data["codestpro1"][$z];
					$codest2=$data["codestpro2"][$z];
					$codest3=$data["codestpro3"][$z];
					$scgcuenta=$data["sc_cuenta"][$z];
					$status=$data["status"][$z];
					$cuenta=trim($cuenta);
					if ($status=="S")
					{
						print "<tr class=celdas-blancas>";
						print "<td>".$cuenta."</td>";
						print "<td  align=left>".$denominacion."</td>";
						print "<td  align=center>".$scgcuenta."</td>";
					}
					else
					{
						print "<tr class=celdas-azules>";
						print "<td><a href=\"javascript: aceptar('$cuenta');\">".$cuenta."</a></td>";
						print "<td  align=left>".$denominacion."</td>";
						print "<td  align=center>".$scgcuenta."</td>";
					}
					print "</tr>";			
				}
				$SQL->free_result($rs_cta);
				$SQL->close();
			}
			else
			{ ?>
				<script language="javascript">
				alert("No Se Encontraron Registros Para Esta Busqueda !!!");
                close();
				</script>
			<?php
			}
		 }
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

  function aceptar(cuenta)
  {
    opener.document.form1.txtcuenta.value=cuenta;	
	opener.document.form1.txtcuenta.readOnly=true;	
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_soc_cat_ctasspg.php";
	  f.submit();
  }
</script>
</html>