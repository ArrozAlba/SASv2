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
</head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<body>
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_fecha.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$io_fecha   = new class_fecha();
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
     $ls_codpro = $_POST["txtcodigo"];
 	 $ls_nompro = $_POST["txtnombre"];
	 $ls_dirpro = $_POST["txtdireccion"];
	 $ls_rifpro = $_POST["txtrifpro"];
	 $ls_banpro = $_POST["cmbbanco"];
     $ls_operacion = $_POST["operacion"];
   }
else
   {
     $ls_codpro = "";
 	 $ls_nompro = "";
	 $ls_dirpro = "";
	 $ls_rifpro = "";
	 $ls_banpro = "";
	 $ls_operacion = "";
   }
?>
<form name="form1" method="post" action="">
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="4" class="titulo-celda">Cat&aacute;logo de Proveedores</td>
      </tr>
      <tr class="formato-blanco">
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td width="68" height="22" style="text-align:right">C&oacute;digo</td>
        <td width="151" height="22"><input name="txtcodigo" type="text" id="txtcodigo" style="text-align:center"  maxlength="10">        </td>
        <td width="62" height="22" style="text-align:right">&nbsp;</td>
        <td width="217" height="22"><label></label></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22" style="text-align:right">Nombre</td>
        <td height="22" colspan="3"><input name="txtnombre" type="text" id="txtnombre" size="75" maxlength="254" style="text-align:left"></td>
      <tr class="formato-blanco">
        <td height="22" style="text-align:right">Direcci&oacute;n</td>
        <td height="22" colspan="3"><input name="txtdireccion" type="text" id="txtdireccion" size="75" maxlength="254" style="text-align:left"></td>
    <tr class="formato-blanco">
      <td height="22" style="text-align:right">Rif</td>
      <td height="22"><input name="txtrifpro" type="text" id="txtrifpro" style="text-align:center"></td>
      <td height="22" style="text-align:right">Banco</td>
      <td height="22"><?php
		/*Llenar Combo Banco*/
		$ls_sql=" SELECT codban,nomban FROM scb_banco WHERE codemp='".$ls_codemp."' ORDER BY codban ASC";
        $rs_pro=$io_sql->select($ls_sql);  
        ?>  
        <select name="cmbbanco" id="cmbbanco" style="width:140px">
        <option value="---">---seleccione---</option>
        <?php
		while ($row=$io_sql->fetch_row($rs_pro))
  			  {
			    $ls_codban=$row["codban"];
			    $ls_nomban=$row["nomban"];
			    if ($ls_codban==$ls_banpro)
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
    <tr class="formato-blanco">
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">      
    <tr class="formato-blanco">
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22"><div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0"></a><a href="javascript: ue_search();">Buscar Proveedor </a></div>
    <tr class="formato-blanco">
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">    
  </table> 
<p align="center">
<?php
echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td width=100 style=text-align:center>Código</td>";
echo "<td width=300 style=text-align:center>Nombre del Proveedor</td>";
echo "<td width=100 style=text-align:center>Reg. Nac. Contratistas</td>";
echo "</tr>";

if ($ls_operacion=="BUSCAR")
   {
     $ls_sql=" SELECT cod_pro, nompro, sc_cuenta, rifpro, fecvenrnc
		         FROM rpc_proveedor
                WHERE codemp='".$ls_codemp."'      
				  AND cod_pro like '%".$ls_codpro."%'
				  AND nompro  like '%".$ls_nompro."%'
				  AND rifpro  like '%".$ls_rifpro."%'
				  AND dirpro  like '%".$ls_dirpro."%'
				  AND codban  like '%".$ls_banpro."%'
				  AND cod_pro<>'----------' 
				  AND estprov=0
                ORDER BY cod_pro ASC";
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
			   while ($row=$io_sql->fetch_row($rs_data))
				     {
					   $lb_existe	   = true;
					   $ls_codpro	   = $row["cod_pro"];
					   $ls_nompro	   = $row["nompro"];
					   $ls_sccuenta	   = $row["sc_cuenta"];
					   $ls_rifpro	   = $row["rifpro"];
					   $ls_fechavenRNC = $row["fecvenrnc"];
					   $ld_fechoy      = date('Y')."-".date('m')."-".date('d');
					   if ($io_fecha->uf_comparar_fecha($ld_fechoy,$ls_fechavenRNC))
						  {
						    $lb_registronacional="VIGENTE";
						  }
					   else
						  {
						    $lb_registronacional="VENCIDO";
						  }
					   echo "<tr class=celdas-blancas>";
					   echo "<td width=100 style=text-align:center><a href=\"javascript:aceptar('$ls_codpro','$ls_nompro','$ls_sccuenta','$ls_rifpro');\">".$ls_codpro."</a></td>";
					   echo "<td width=300 style=text-align:left>".$row["nompro"]."</td>";
					   echo "<td width=100 style=text-align:center>".$lb_registronacional."</td>";
					   echo "</tr>";			
					 }
			    $io_sql->free_result($rs_data);
			 }
		  else
		     {
			   $io_msg->message("No se han definido Proveedores !!!");
			 }  
		}
   }
print "</table>";
?>  
</p>
</form>
<script language="JavaScript">
function aceptar(codigo,nompro,sc_cuenta,rif_proveedor)
  {
    li_principal=(opener.document.form1.hidcatalogo.value); //Para decidir que campos vamos a rellenar para cada llamado del catalogo.
	if (li_principal==1)
	   {
	     opener.document.form1.txtcodproben.value=codigo;
	     opener.document.form1.txtnombre.value=nompro;
	     opener.document.form1.hidcodcuenta.value=sc_cuenta;
	     opener.document.form1.txtrif.value=rif_proveedor;
	   }
	else
	   {
	    buscar=opener.document.form1.hidrangocodigos.value;
        if (buscar=="1")
           {
	         opener.document.form1.txtcodigo1.value=codigo;
           }
        else
           {
	         opener.document.form1.txtcodigo2.value=codigo;   
           }
	   }
	close();
  }
  
  function ue_search()
  {
    f=document.form1;
    f.operacion.value="BUSCAR";
    f.action="sigesp_cxp_cat_proveedores.php";
    f.submit();
  }
</script>
</html>