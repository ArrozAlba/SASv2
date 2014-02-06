<?php
session_start();
require_once("class_funciones_activos.php");
$fun_activos=new class_funciones_activos();				
$operacion=$fun_activos->uf_obteneroperacion();
$ls_destino=$fun_activos->uf_obtenervalor_get("destino","");
if($operacion=="BUSCAR")
{$ls_destino=$fun_activos->uf_obtenervalor("destino","");}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo Beneficiarios</title>
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
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Beneficiario </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">Codigo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo">        
          <input name="destino" type="hidden" id="destino" value="<?php print  $ls_destino; ?>">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="nombre" type="text" id="nombre">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
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
require_once("../shared/class_folder/class_funciones.php");
$io_fun= new class_funciones();
$arr=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_nombre="%".$_POST["nombre"]."%";
}
else
{
	$ls_operacion="";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Cedula</td>";
print "<td>Nombre del Beneficiario</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql=" SELECT ced_bene, nombene ".
    	    " FROM   rpc_beneficiario ".
			" WHERE  ced_bene like '".$ls_codigo."' AND ".
			"        nombene  like '".$ls_nombre."' ";
	$rs_cta=$io_sql->select($ls_sql);
    $li_num=$io_sql->num_rows($rs_cta);
	if($li_num>0)
	{
		while(!$rs_cta->EOF)
		{
			print "<tr class=celdas-blancas>";
			$ls_ced_bene = $rs_cta->fields["ced_bene"];
			$ls_nombene  = $rs_cta->fields["nombene"];
			switch ($ls_destino)
			{
		  	   case "":
				print "<td><a href=\"javascript: aceptar('$ls_ced_bene','$ls_nombene');\">".$ls_ced_bene."</a></td>";
			  break;
			  
		      case "responsableuso":
				print "<td><a href=\"javascript: aceptar_resp_uso('$ls_ced_bene','$ls_nombene');\">".$ls_ced_bene."</a></td>";
			  break;
			  
			  case "despachador":
				print "<td><a href=\"javascript: aceptar_despachador('$ls_ced_bene','$ls_nombene');\">".$ls_ced_bene."</a></td>";
			  break;
			  
			  case "responsable":
				print "<td><a href=\"javascript: aceptar_responsable('$ls_ced_bene','$ls_nombene');\">".$ls_ced_bene."</a></td>";
			  break;
			  
			  case "receptor":
				print "<td><a href=\"javascript: aceptar_receptor('$ls_ced_bene','$ls_nombene');\">".$ls_ced_bene."</a></td>";
			  break;
		   }  		
		  print "<td>".$ls_nombene."</td>";
		  print "</tr>";
		  $rs_cta->MoveNext();
		}
	}
	else
	{
	    $io_msg->message("No existen registros..");
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
  function aceptar(as_ced_bene,as_nombene)
  {
    opener.document.form1.txtcodrespri.value=as_ced_bene;
    opener.document.form1.txtdenrespri.value=as_nombene;
	close();
  }
  
  function aceptar_resp_uso(as_ced_bene,as_nombene)
  {
    opener.document.form1.txtcodresuso.value=as_ced_bene;
    opener.document.form1.txtdenresuso.value=as_nombene;
	close();
  }

  function aceptar_responsable(as_ced_bene,as_nombene)
  {
    opener.document.form1.txtcodres.value=as_ced_bene;
    opener.document.form1.txtnomres.value=as_nombene;
	close();
  }
  
  function aceptar_receptor(as_ced_bene,as_nombene)
  {
    opener.document.form1.txtcodrec.value=as_ced_bene;
    opener.document.form1.txtnomrec.value=as_nombene;
	close();
  }
  
  function aceptar_despachador(as_ced_bene,as_nombene)
  {
    opener.document.form1.txtcoddes.value=as_ced_bene;
    opener.document.form1.txtnomdes.value=as_nombene;
	close();
  }
  
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_saf_cat_beneficiario.php";
	  f.submit();
  }
</script>
</html>