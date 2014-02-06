<?php
session_start();
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
require_once("../shared/class_folder/class_funciones.php");
$fun=new class_funciones();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
require_once("../shared/class_folder/class_mensajes.php");
$io_msg = new class_mensajes();//Instanciando la Clase Class  Mensajes.
$as_codemp=$_SESSION["la_empresa"]["codemp"];
require_once("class_funciones_inventario.php");
$io_fun_inventario=new class_funciones_inventario();
$ls_tipo=$io_fun_inventario->uf_obtenertipo();
$ls_operacion=$io_fun_inventario->uf_obteneroperacion();
$ls_codigo=$io_fun_inventario->uf_obtenervalor("codigo","");
$ls_denominacion=$io_fun_inventario->uf_obtenervalor("nombre","");
$ls_cuentaspg=$io_fun_inventario->uf_obtenervalor("txtcuentascg","");
if($ls_tipo=="ACTIVOSFIJOS")
{
	$ls_codigo="404".$ls_codigo;
}
if($ls_operacion=="BUSCAR")
{
	$ls_tipo=$io_fun_inventario->uf_obtenervalor("tipo","");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Presupuestarias</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
  <table width="511" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="507" height="22" colspan="2" class="titulo-celda" style="text-align:center">Cat&aacute;logo de Cuentas Presupuestaria</td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="511" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="114" height="22" align="right">Codigo&nbsp;</td>
        <td width="156" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" value="<? print $ls_codigo ?>" size="22" maxlength="20" onKeyPress="return keyRestrict(event,'1234567890');">        
        </div></td>
        <td width="255" height="22" colspan="4"><input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n&nbsp;</div></td>
        <td height="22" colspan="5"><div align="left">
          <input name="nombre" type="text" id="nombre" value="<? print $ls_denominacion ?>" size="72">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Cuenta Contable&nbsp;</div></td>
        <td height="22" colspan="5"><div align="left">
          <input name="txtcuentascg" type="text" id="txtcuentascg" value="<? print $ls_cuentaspg ?>" size="22" maxlength="20"  onKeyPress="return keyRestrict(event,'1234567890');">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22" colspan="4"><div align="right"><a href="javascript: ue_search();">
          <input name="operacion" type="hidden" id="operacion" value="<? print $ls_operacion ?>">
        <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Presupuestaria</td>";
print "<td>Denominación</td>";
print "<td>Contable</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{

   $ls_sql = " SELECT  soc_gastos     ".
	         " FROM    sigesp_empresa ".
		     " WHERE   codemp = '".$as_codemp."' ";
			 
   $rs=$io_sql->select($ls_sql);
   $li_numrows=$io_sql->num_rows($rs);
   if ($li_numrows===false)
   {
	  $lb_valido=false;            
	  $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
   }            
   else
   {
	   /* $ls_sql = " SELECT  DISTINCT spg_cuenta, denominacion, codestpro1,codestpro2, codestpro3,sc_cuenta,status   ".
	              " FROM    spg_cuentas                                                                             ".
		          " WHERE   codemp = '".$as_codemp."'                   AND  spg_cuenta like '%".$ls_codigo."%' AND ".
				  "         denominacion like '%".$ls_denominacion."%'  AND  sc_cuenta  like '%".$ls_cuentaspg."%'   ";*/
				  
	  $ls_sql = " SELECT  DISTINCT spg_cuenta, MAX(denominacion) as denominacion, MAX(sc_cuenta) as sc_cuenta, MAX(status)as status   ".
	              " FROM    spg_cuentas                                                                             ".
		          " WHERE   codemp = '".$as_codemp."' AND status='C'     AND  spg_cuenta like '%".$ls_codigo."%' AND ".
				  "         denominacion like '%".$ls_denominacion."%'  AND  sc_cuenta  like '%".$ls_cuentaspg."%'   ";
		while ($row=$io_sql->fetch_row($rs))	  
		{						  
			 $ls_cadena = trim($row["soc_gastos"]);                  
		}
   
		$la_spg_cuenta=split(",",$ls_cadena);
		$li_total=count($la_spg_cuenta);
		for($li_i=0;$li_i<$li_total;$li_i++)
		{
			if($li_i==0)
			{
				$ls_sql=$ls_sql."   AND (spg_cuenta like '".$la_spg_cuenta[$li_i]."%'";
			}
			else
			{
				$ls_sql=$ls_sql."    OR spg_cuenta like '".$la_spg_cuenta[$li_i]."%'";
			}
		
		}						
		
		/*$ls_sql= $ls_sql." ) GROUP BY spg_cuenta, denominacion, codestpro1,codestpro2, codestpro3,sc_cuenta,status".
						 "   ORDER BY spg_cuenta"; 	*/
		$ls_sql= $ls_sql." ) GROUP BY spg_cuenta ".
						 "   ORDER BY spg_cuenta"; 					  
		$rs_cta=$io_sql->select($ls_sql);
		if($rs_cta==false)
		{
			$io_msg->message($fun->uf_convertirmsg($io_sql->message));
		}
		else
		{
			$li_row=$io_sql->num_rows($rs_cta);
			if($li_row>0)
			{
				while($row=$io_sql->fetch_row($rs_cta))
				{
					$ls_spgcuenta=trim($row["spg_cuenta"]);
					$ls_denominacion=$row["denominacion"];
					$ls_sccuenta=trim($row["sc_cuenta"]);
					$status=$row["status"];

					if ($status=="S")
				    {
						print "<tr class=celdas-blancas>";
						print "<td>".$ls_spgcuenta."</td>";
						print "<td  align=left>".$ls_denominacion."</td>";
						print "<td  align=center>".$ls_sccuenta."</td>";
					}
					else
					{
						if($ls_tipo=="ACTIVOSFIJOS")
						{
							print "<tr class=celdas-azules>";
							print "<td><a href=\"javascript: aceptar_activo('$ls_spgcuenta','$ls_denominacion');\">".$ls_spgcuenta."</a></td>";
							print "<td  align=left>".$ls_denominacion."</td>";
							print "<td  align=center>".$ls_sccuenta."</td>";
						}
						else
						{
							print "<tr class=celdas-azules>";
							print "<td><a href=\"javascript: aceptar('$ls_spgcuenta');\">".$ls_spgcuenta."</a></td>";
							print "<td  align=left>".$ls_denominacion."</td>";
							print "<td  align=center>".$ls_sccuenta."</td>";
						}
					}
					print "</tr>";			
				}
				$io_sql->free_result($rs_cta);
				$io_sql->close();
			}
			else
			{ ?>
				<script language="javascript">
				alert("No Se Encontraron Registros Para Esta Busqueda !!!");
				close();
				</script>
			<?
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
    opener.document.form1.txtspg_cuenta.value=cuenta;	
	close();
  }
  function aceptar_activo(cuenta,denominacion)
  {
    opener.document.form1.txtspg_cuenta.value=cuenta;	
    opener.document.form1.txtspgdenominacion.value=denominacion;	
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_siv_cat_ctasspg.php";
	  f.submit();
  }
</script>
</html>