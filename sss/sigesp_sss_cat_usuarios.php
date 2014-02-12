<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo Usuarios</title>
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
    <input name="txtempresa" type="hidden" id="txtempresa">
    <input name="txtloginviejo" type="hidden" id="txtloginviejo">
    <input name="hidstatus" type="hidden" id="hidstatus2">
    <input name="txtcedula" type="hidden" id="txtcedula">
    <input name="txtapellido" type="hidden" id="txtapellido">
    <input name="txttelefono" type="hidden" id="txttelefono">
  </p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Usuarios </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">Codigo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="txtcodigo" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td height="22"><div align="left">          <input name="txtnombre" type="text" id="txtnombre">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
    <?php
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
require_once("../shared/class_folder/class_funciones.php");
$io_fun=new class_funciones();
require_once("class_funciones_seguridad.php");
$io_funciones_seguridad=new class_funciones_seguridad();

$ls_destino=$io_funciones_seguridad->uf_obtenervalor_get("destino","");
if($ls_destino=="")
{$ls_destino=$io_funciones_seguridad->uf_obtenervalor("hiddestino","");}

$arr=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_empresa="%".$_POST["txtempresa"]."%";
	$ls_nombre="%".$_POST["txtnombre"]."%";
	$ls_loginviejo="%".$_POST["txtloginviejo"]."%";
	$ls_codigo="%".$_POST["txtcodigo"]."%";
	$ls_status="%".$_POST["hidstatus"]."%";
}
else
{
	$ls_operacion="";

}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Login</td>";
print "<td>Nombre</td>";
print "<td>Apellido</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT sss_usuarios.*,sno_unidadadmin.desuniadm FROM sss_usuarios ".	
			" LEFT OUTER JOIN sno_unidadadmin ".
			" ON TRIM(sss_usuarios.coduniadm)=TRIM(sno_unidadadmin.minorguniadm||sno_unidadadmin.ofiuniadm||sno_unidadadmin.uniuniadm||sno_unidadadmin.depuniadm||sno_unidadadmin.prouniadm)".
			" WHERE sss_usuarios.codemp iLIKE '".$ls_empresa."' ".
			" AND sss_usuarios.codusu iLIKE '".$ls_codigo."' ".
			" AND sss_usuarios.nomusu iLIKE '".$ls_nombre."' ".
			" ORDER BY codusu";
    $rs_cta=$io_sql->select($ls_sql);
	$li=0;
	while($row=$io_sql->fetch_row($rs_cta))
	{
		$li++;
		print "<tr class=celdas-blancas>";
		$ls_empresa=$row["codemp"];
		$ls_codigo=$row["codusu"];
		$ls_loginviejo=$row["codusu"];
		$ls_cedula=$row["cedusu"];
		$ls_nombre=$row["nomusu"];
		$ls_apellido=$row["apeusu"];
		$ls_telefono=$row["telusu"];
		$ls_codigovie=$row["codusu"];
		$ls_nota=$row["nota"];
		$ls_foto=trim($row["fotousu"]);
		if($ls_foto=="")
		{$ls_foto="blanco.jpg";}
		$ls_estusu=$row["estusu"];
		$ls_coduniadm=$row["coduniadm"];
		$ls_denuniadm=$row["desuniadm"];
		$ld_ultingusu=$row["ultingusu"];
		$ld_ultingusu=$io_fun->uf_convertirfecmostrar($ld_ultingusu);
		print "<td><a href=\"javascript: aceptar('$ls_empresa','$ls_codigo','$ls_cedula','$ls_nombre','$ls_apellido',".
							"					 '$ls_telefono','$ls_nota','$ls_status','$ls_codigovie','$ls_foto',".
							"                    '$ls_destino','$ld_ultingusu','$ls_estusu','$ls_coduniadm','$ls_denuniadm');\">".$ls_codigo."</a></td>";
		print "<td>".$row["nomusu"]."</td>";
		print "<td>".$row["apeusu"]."</td>";
		print "</tr>";			
		
	}
	if($li<=0)
	{
		$io_msg->message("No hay registros");
	}
	
}
print "</table>";
?>
</div>
<input name="hiddestino" type="hidden" id="hiddestino" value="<?php print $ls_destino?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(empresa,codigo,cedula,nombre,apellido,telefono,nota,status,codviejo,foto,destino,ultingusu,ls_estusu,ls_coduniadm,ls_denuniadm)
	{
		if(destino=="")
		{
			opener.document.form1.txtempresa.value=empresa;
			opener.document.form1.txtnombre.value=nombre;
			opener.document.form1.txtcodigo.value=codigo;
			opener.document.form1.txtloginviejo.value=codigo;
			opener.document.form1.txtcedula.value=cedula;
			opener.document.form1.txtapellido.value=apellido;
			opener.document.form1.txttelefono.value=telefono;
			opener.document.form1.txtultingusu.value=ultingusu;
			opener.document.form1.operacion.value="MODIFICAR";
			opener.document.form1.txtnota.value=nota;
			opener.document.form1.hidfoto.value=foto;
			if(opener.document.getElementById("foto")!=null)
			{
				opener.document.images["foto"].src="fotosusuarios/"+foto;
			}
			if(ls_estusu=='t')
			{
				opener.document.form1.rbestatus[0].checked=true;
			}
			else
			{
				opener.document.form1.rbestatus[1].checked=true;			
			}
			opener.document.form1.txtcoduniadm.value=ls_coduniadm;	
			opener.document.form1.txtdenuniadm.value=ls_denuniadm;				
			opener.document.form1.hidstatus.value="C";
			opener.document.form1.submit();
		}
		if(destino=="Reporte") 
		{
			opener.document.form1.txtcodusu.value=codigo;
			opener.document.form1.txtnomusu.value=nombre+" "+apellido;
		}
		if (destino=="Auditoria") 
		{
			opener.document.form1.txtcodigo.value=codigo;
		}
		close();
	}

	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_sss_cat_usuarios.php";
		f.submit();
	}
</script>
</html>
