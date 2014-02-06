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
<style type="text/css">
<!--
.style1 {color: #000000}
-->
</style>
</head>
<body>
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("class_folder/sigesp_rpc_c_proveedor.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");

$io_proveedor = new sigesp_rpc_c_proveedor();
$io_include   = new sigesp_include();
$ls_conect    = $io_include->uf_conectar();
$io_sql       = new class_sql($ls_conect);
$io_msg       = new class_mensajes();
$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
$io_fecha     = new class_fecha();

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
	 $ls_operacion=$_POST["operacion"];
	 $ls_codigo="%".$_POST["txtcodigo"]."%";
	 $ls_nombre="%".$_POST["txtnombre"]."%";
   }
else
   {
	$ls_operacion="";
   }
if (array_key_exists("txtcodigo",$_POST))
   {
     $ls_codigo="%".$_POST["txtcodigo"]."%";
   }
else
   {
	$ls_codigo="";
   }  
if (array_key_exists("tipo",$_GET))
   {
     $ls_tipo=$_GET["tipo"];
   }
else
   {
	$ls_tipo="";
   }      
?>
<form name="form1" method="post" action="">
  <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="18" colspan="4" style="text-align:center">Cat&aacute;logo de Proveedores</td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="64" height="22" align="right"><span class="style1">C&oacute;digo</span></td>
        <td width="139" height="22"><input name="txtcodigo" type="text" id="txtcodigo" maxlength="10">        </td>
        <td width="58" height="22" align="right">Direcci&oacute;n</td>
        <td width="220" height="22"><input name="txtdireccion" type="text" id="txtdireccion" size="25" maxlength="100"></td>
      </tr>
      <tr>
        <td height="22" align="right">Nombre</td>
        <td height="22"><input name="txtnombre" type="text" id="txtnombre" maxlength="100"></td>
        <td height="22" align="right">Banco</td>
        <td height="22">
<?php
/*Llenar Combo Banco*/
$rs_pro=$io_proveedor->uf_select_llenarcombo_banco($ls_codemp);
?>  
          <select name="cmbbanco" id="cmbbanco"  style="width:150px " >
            <option value="s1">---seleccione---</option>
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
        <td height="13" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td>      
    <tr>
      <td height="15" align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td>      <div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar Proveedor</a></div>
  </table> 
<p align="center">
  <?php
echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td width=100 style=text-align:center>C&oacute;digo</td>";
echo "<td width=300 style=text-align:center>Nombre</td>";
echo "<td width=100 style=text-align:center>RNC</td>";
echo "</tr>";

if ($ls_operacion=="BUSCAR")
   {
		$lb_existe=false;
		$ls_codpro="%".$_POST["txtcodigo"]."%";
		$ls_nombre="%".$_POST["txtnombre"]."%";
		$ls_direccion="%".$_POST["txtdireccion"]."%";
		$ls_codban="%".$_POST["cmbbanco"]."%";
		if ($ls_codban=="%s1%")
		   {  
	         $ls_codban="%%";	
		   } 
		 if($ls_tipo="CAMBIOESTATUS")
		 {
		 	$ls_estatus="";
		 }
		 else
		 {
		 	$ls_estatus=" AND estprov=0 ";
		 }
		$ls_sql=" SELECT cod_pro, nompro, fecvenrnc
                    FROM rpc_proveedor
                   WHERE cod_pro like '".$ls_codigo."' 
				     AND cod_pro <> '----------'
					 AND nompro like '".$ls_nombre."'
					 AND dirpro like '".$ls_direccion."'
					 AND codban like '".$ls_codban."' $ls_estatus
                   ORDER BY cod_pro ASC";

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
			    while (!$rs_data->EOF)
				      {
					    echo "<tr class=celdas-blancas>";
						$ls_codpro    = trim($rs_data->fields["cod_pro"]);
			            $ls_nompro    = ltrim($rs_data->fields["nompro"]);
			            $ls_fecvenrnc = $rs_data->fields["fecvenrnc"];
						$ld_fecact    = date('Y')."-".date('m')."-".date('d');
					    if ($io_fecha->uf_comparar_fecha($ld_fecact,$ls_fecvenrnc))
						   {
							 $ls_estrnc = "VIGENTE";
						   }
					    else
						   {
							 $ls_estrnc = "VENCIDO";
						   }
					    echo "<td width=100 style=text-align:center><a href=\"javascript: aceptar('$ls_codpro');\">".$ls_codpro."</a></td>";
					    echo "<td width=300 style=text-align:left title='".ltrim($ls_nompro)."'>".$ls_nompro."</td>";
					    echo "<td width=100 style=text-align:center>".$ls_estrnc."</td>";
					    echo "</tr>";
						$rs_data->MoveNext();
					  }
			  }		  
		   else
		      {
			    $io_msg->message("No se han definido Proveedores !!!");			  
			  }
		 }
   } 
echo "</table>";
?>
</p>
</form>      
</body>
<script language="JavaScript">
function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_catdin_prove.php?tipo=<?php print $ls_tipo; ?>";
	f.submit();
}
  
function aceptar(codpro)
{
buscar=opener.document.form1.hidrango.value;
if (buscar=="1")
   {
   	 opener.document.form1.txtcodprov1.value=codpro;
   }
if (buscar=="2")
   {
	 opener.document.form1.txtcodprov2.value=codpro;
   }
if (buscar=="3")
   {
	 opener.document.form1.txtcodprovdesde.value=codpro;
   }
if (buscar=="4")
   {
	 opener.document.form1.txtcodprovhasta.value=codpro;
   }
if (buscar=="5")
   {
	 opener.document.form1.txtcodprov.value=codpro;
   }
close();
}

</script>
</html>