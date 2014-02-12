<?php
session_start();
require_once("class_funciones_activos.php");
$io_fact= new class_funciones_activos();
if (array_key_exists("coddestino",$_POST))
   {
	 $ls_coddestino=$_POST["coddestino"];
	 $ls_dendestino=$_POST["dendestino"];
   }
else
   {
	 $ls_coddestino=$io_fact->uf_obtenervalor_get("coddestino","txtcatalogo");
	 $ls_dendestino=$io_fact->uf_obtenervalor_get("dendestino","txtdencat");
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo SIGECOF</title>
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
        <td height="22" colspan="2"><input name="coddestino" type="hidden" id="coddestino" value="<?php print $ls_coddestino ?>">
          <input name="hidstatus" type="hidden" id="hidstatus">
          <input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo SIGECOF 
        <input name="txtempresa" type="hidden" id="txtempresa">
        <input name="dendestino" type="hidden" id="dendestino" value="<?php print $ls_dendestino ?>">
        <input name="txtnombrevie" type="hidden" id="txtnombrevie"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="67" height="22" style="text-align:right">C&oacute;digo</td>
        <td width="431" height="22" style="text-align:left"><input name="txtcatalogo" type="text" id="txtcatalogo"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" style="text-align:left"><input name="txtdenominacion" type="text" id="txtdenominacion" size="70"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
    <p align="center">
      <?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	require_once("../shared/class_folder/class_datastore.php");
	$ds=new class_datastore();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($con);
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=new class_mensajes();
	$arr=$_SESSION["la_empresa"];
	
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_catalogo="%".$_POST["txtcatalogo"]."%";
		$ls_denominacion="%".$_POST["txtdenominacion"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
	}
	else
	{
		$ls_operacion="";
	
	}
echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center width=100>C&oacute;digo</td>";
echo "<td style=text-align:center width=300>Denominaci&oacute;n</td>";
echo "<td style=text-align:center width=100>Cuenta</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     $ls_sql="SELECT trim(catalogo) as catalogo, rtrim(dencat) as dencat, trim(spg_cuenta) as spg_cuenta
	            FROM saf_catalogo
			   WHERE catalogo like '".$ls_catalogo."'
			     AND UPPER(dencat) like '".strtoupper($ls_denominacion)."'
			     AND spg_cuenta like '404%'"; 
   
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
			           $ls_nomcat = $rs_data->fields["catalogo"];
			           $ls_dencat = $rs_data->fields["dencat"];
				       $ls_spgcta = $rs_data->fields["spg_cuenta"];
					   echo "<td align='center'><a href=\"javascript: aceptar('$ls_nomcat','$ls_dencat','$ls_spgcta','$ls_status','$ls_coddestino','$ls_dendestino');\">".$ls_nomcat."</a></td>";
				       echo "<td style=text-align:left   width=300 title='".$ls_dencat."'>".$ls_dencat."</td>";
					   echo "<td style=text-align:center width=100>".$ls_spgcta."</td>";
				       echo "</tr>";
                       $rs_data->MoveNext();
					 }
			  }
		   else
		      {
			    $io_msg->message("No se han definido Cuentas para el Cat&aacute;logo !!!");
			  }
		 }  		 
   }
echo "</table>";
?>
  </p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(ls_codigo,ls_denominacion,ls_cuenta,ls_status,ls_coddestino,ls_dendestino)
	{
		obj=eval("opener.document.form1."+ls_coddestino+"");
		obj.value=ls_codigo;
		obj=eval("opener.document.form1."+ls_dendestino+"");
		obj.value=ls_denominacion;
		opener.document.form1.txtcuenta.value=ls_cuenta;
		opener.document.form1.hidstatus.value="C";
		close();
	}

	function ue_search()
	{
		f=document.form1;
		ls_codigo=f.txtcatalogo.value;
		ls_denominacion=f.txtdenominacion.value;
		f.operacion.value="BUSCAR";
		f.action="sigesp_saf_cat_sigecof.php";
		f.submit();
	}
</script>
</html>