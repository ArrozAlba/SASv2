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

<body>
    
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");

$io_conect=new sigesp_include();
$conn=$io_conect->uf_conectar();
$io_msg=new class_mensajes();
$io_dsprove=new class_datastore();
$io_sql=new class_sql($conn);
$la_emp=$_SESSION["la_empresa"];
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
?>
<form name="form1" method="post" action="">
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="4">Catalogo de Proveedores </td>
      </tr>
      <tr>
        <td height="14">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="64" height="27"><div align="right">C&oacute;digo&nbsp;</div></td>
        <td width="139"><div align="left">
          <input name="txtcodigo" type="text" id="txtcodigo">        
        </div></td>
        <td width="58"><div align="right">Direcci&oacute;n&nbsp;</div></td>
        <td width="200"><div align="left">
          <input name="txtdireccion" type="text" id="txtdireccion" size="25">
        </div></td>
      </tr>
      <tr>
        <td height="32"><div align="right">Nombre&nbsp;</div></td>
        <td><div align="left">
          <input name="txtnombre" type="text" id="txtnombre">
        </div></td>
        <td><div align="right">Banco&nbsp;</div></td>
        <td><?php
		/*Llenar Combo Banco*/
		$ls_codemp=$la_emp["codemp"];
        $ls_sql="SELECT * FROM scb_banco WHERE codemp='".$ls_codemp."' ORDER BY codban ASC";
        $rs_pro=$io_sql->select($ls_sql);  
        ?>  
          <div align="left">
  <select name="cmbBanco" id="cmbBanco" style="width:150px" >
            <option value="000">Seleccione un Banco</option>
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
          </div>
    <tr>
        <td height="22" colspan="4"><div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0">Buscar Proveedor</a> </div></td>
  </table> 
</form>      
<?php
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=100 style=text-align:center>Código</td>";
print "<td width=400 style=text-align:center>Nombre del Proveedor</td>";
print "</tr>";

if ($ls_operacion=="BUSCAR")
   {
		$ls_codpro = "%".$_POST["txtcodigo"]."%";
		$ls_nombre = "%".$_POST["txtnombre"]."%";
		$ls_dirpro = "%".$_POST["txtdireccion"]."%";
		$ls_codban = "%".$_POST["cmbBanco"]."%";
        if ($ls_codban=="%000%")
		   {  
	         $ls_codban="%%";	
		   } 
		$ls_codemp=$la_emp["codemp"];
        $ls_sql="SELECT * FROM rpc_proveedor ".
		        "WHERE    codemp='".$ls_codemp."'      AND cod_pro like '".$ls_codigo."'   AND ".
				"         nompro like '".$ls_nombre."' AND dirpro like '".$ls_dirpro."' AND ".
				"         codban like '".$ls_codban."' AND cod_pro<>'----------'               ".
				"ORDER BY cod_pro ASC"  ;
		$rs_data    = $io_sql->select($ls_sql);
    	$li_numrows = $io_sql->num_rows($rs_data);
		if ($li_numrows>0)
		   {
			 while($row=$io_sql->fetch_row($rs_data))
			      {
				    print "<tr class=celdas-blancas>";
					$ls_codpro = $row["cod_pro"];
					$ls_nompro = $row["nompro"];
					print "<td width=100 style=text-align:center><a href=\"javascript:aceptar('$ls_codpro','$ls_nompro');\">".$ls_codpro."</a></td>";
					print "<td width=400 style=text-align:left>".$ls_nompro."</td>";
					print "</tr>";			
				  }
		     print "</table>";
		   }
		else
		   { ?>
		     <script language="javascript">
			 alert("No se han creado proveedores !!!");
			 </script>
			 <?php
		   }
   }
?>
</body>

<script language="JavaScript">
function aceptar(codpro,nompro)
  {
	opener.document.form1.txtnombrehas.value    = nompro;
	opener.document.form1.txtcodprobenhas.value = codpro;	
	close();
  }
  
  function ue_search()
  {
    f=document.form1;
    f.operacion.value="BUSCAR";
    f.action="sigesp_cxp_cat_prohasta.php";
    f.submit();
  }
</script>
</html>