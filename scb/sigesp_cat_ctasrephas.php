<?php
session_start();
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($ls_conect);
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>C&aacute;talogo de Cuentas Presupuestarias</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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
<p align="center">
<?php
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion=$_POST["operacion"];
	 $ls_codigo=$_POST["codigo"]."%";
	 $ls_denominacion="%".$_POST["nombre"]."%";
   }
else
   {
	 $ls_operacion="";
	 $ls_codigo="";
	 $ls_denominacion="";
   }
?>
  </p>
  <br>
  <div align="center">
    <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="3" style="text-align:center">Cat&aacute;logo de Cuentas Presupuestaria
        <input name="operacion" type="hidden" id="operacion"></td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="135" height="22" align="right">C&oacute;digo</td>
        <td width="122" height="22" style="text-align:left"><input name="codigo" type="text" id="codigo" size="22" maxlength="20" onKeyPress="return keyRestrict(event,'1234567890');" style="text-align:center"></td>
        <td width="341" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" colspan="2" style="text-align:left"><input name="nombre" type="text" id="nombre" size="72" style="text-align:left"></td>
      </tr>
      <tr>
        <td height="22" colspan="3" style="text-align:right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
      </tr>
      <tr>
        <td height="13" colspan="3">&nbsp;</td>
      </tr>
    </table>
	<br>
<?php
print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td style=text-align:center width=100>Código</td>";
print "<td style=text-align:center width=400>Denominación</td>";
print "<td style=text-align:center width=50>Tipo</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql = "SELECT trim(spg_cuenta) as spg_cuenta,
	                   max(denominacion) as denominacion, 
					   max(status) as status,
					   max(estcla) as estcla
	              FROM spg_cuentas
				 WHERE codemp = '".$ls_codemp."' 
				   AND spg_cuenta like '".$ls_codigo."' 
				   AND denominacion like '".$ls_denominacion."' 
				 GROUP BY spg_cuenta
				 ORDER BY spg_cuenta ASC";
	 $rs_data = $io_sql->select($ls_sql);
	 if ($rs_data===false)
	    {
		  $io_msg->message("Error en Select Cuentas Gasto !!!");
		}
	 else
	    {
		  $li_numrows = $io_sql->num_rows($rs_data);
		  if ($li_numrows>0)
		     {
			    while($row=$io_sql->fetch_row($rs_data))
				     {
					   $ls_spgcta = $row["spg_cuenta"];
			 	       $ls_dencta = $row["denominacion"];
				       $ls_estcta = $row["status"];
					   $ls_estcla = $row["estcla"];
					   if ($ls_estcla=='P')
					      {
						    if ($_SESSION["la_empresa"]["estmodest"]=='2')
							   {
							     $ls_denestcla=$_SESSION["la_empresa"]["denestpro1"];  
							   }
							else
							   {
							     $ls_denestcla='Proyecto';
							   }
						  }
					   elseif($ls_estcla=='A')
					      {
						    $ls_denestcla='Acción';
						  }
					   if ($ls_estcta=="S")
				          {
					        print "<tr class=celdas-blancas>";
					        print "<td style=text-align:center width=100>$ls_spgcta</td>";
				          }
				       else
				          {
					        print "<tr class=celdas-azules>";
					        print "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_spgcta','$ls_dencta');\">".$ls_spgcta."</a></td>";
				          }						
					   print "<td style=text-align:left width=400>".$ls_dencta."</td>";
					   print "<td style=text-align:left width=50>".$ls_denestcla."</td>";
				       print "</tr>";
					 }
			    $io_sql->free_result($rs_data);
			 }
	      else
		     {
			   $io_msg->message("No se han creado Cuentas de gasto para la programática seleccionada !!!");
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

  function aceptar(cuenta,deno)
  {
    opener.document.form1.txtcuentahas.value=cuenta;
    opener.document.form1.txtcuentahas.readOnly=true;
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_ctasrephas.php";
	  f.submit();
  }
</script>
</html>