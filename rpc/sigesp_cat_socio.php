<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Socios por Proveedor</title>
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");

$io_conect=new sigesp_include();
$conn=$io_conect->uf_conectar();
$io_dssocio=new class_datastore();
$io_sql=new class_sql($conn);
$arr=$_SESSION["la_empresa"];
$ls_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
	{
	  $ls_operacion=$_POST["operacion"];
	  $ls_codprov=$_POST["txtprov"];
	}
else
	{
	  $ls_operacion="";
 	  $ls_codprov=$_GET["txtprov"];
	}
$ls_sql=" SELECT * ".
        " FROM rpc_proveedorsocios ".
	    " WHERE cod_pro='".$ls_codprov."' ".
	    " ORDER BY cedsocio ASC" ;
$rs_socio=$io_sql->select($ls_sql);
$data=$rs_socio;
?>
<form name="form1" method="post" action="">
  <input name="txtprov" type="hidden" id="txtprov" value= "<?php print $ls_codprov ?>">
</form>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="15" colspan="2" class="titulo-celda">Cat&aacute;logo de Socios por Proveedor</td>
    </tr>
  </table>
<br>
<?php
if($row=$io_sql->fetch_row($rs_socio))
{
     $data=$io_sql->obtener_datos($rs_socio);
	 $arrcols=array_keys($data);
	 $totcol=count($arrcols);
	 $io_dssocio->data=$data;
	 $totrow=$io_dssocio->getRowCount("cedsocio");
     print "<table width=500 border=0 cellpadding=1  cellspacing=1 class=fondo-tabla align=center>";
	 print "<tr class=titulo-celda>";
     print "<td>Cédula</td>";
     print "<td>Nombre del Socio</td>";
     print "</tr>";
	 for($z=1;$z<=$totrow;$z++)
	 {
		 print "<tr class=celdas-blancas>";
		 $cedula=$data["cedsocio"][$z];
		 $nombre=$data["nomsocio"][$z];
		 $apellido=$data["apesocio"][$z];
		 $direccion=$data["dirsocio"][$z];
		 $cargo=$data["carsocio"][$z];
		 $telefono=$data["telsocio"][$z];
		 $email=$data["email"][$z];
		 print "<td><a href=\"javascript: aceptar('$cedula','$nombre','$apellido','$direccion','$cargo','$telefono','$email');\">".$cedula."</a></td>";
		 print "<td>".$nombre."</td>";
		 print "</tr>";			
     }
      print "</table>";
      $io_sql->free_result($rs_socio);
}
else
{ ?>
  <script language="javascript">
  alert("No se han creado Socios para este Proveedor !!!");
  close();
  </script>
<?php
} 

?>
</table>
</body>
<script language="JavaScript">
  function aceptar(cedula,nombre,apellido,direccion,cargo,telefono,email)
  {
    opener.document.form1.txtcedula.value=cedula;
	opener.document.form1.txtcedula.readOnly=true;
    opener.document.form1.txtnombre.value=nombre;
	opener.document.form1.txtapellido.value=apellido;
    opener.document.form1.txtdireccion.value=direccion;
	opener.document.form1.txtcargo.value=cargo;
    opener.document.form1.txttelefono.value=telefono;
	opener.document.form1.txtemail.value=email;
	close();
  }
</script>
</html>