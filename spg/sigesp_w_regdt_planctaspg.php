<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title>Entrada de Movimientos Contables</title>
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
<style type="text/css">
<!--
.style2 {font-size: 11px}
-->
</style>
</head>
<body>
<form method="post" name="form1" action="">
<?php


$ls_cuentaspg   = $_POST["txtcuentaspg".$i];
$ls_dencuentaspg= $_POST["txtdencuenta".$i];
$ls_cuentascg   = $_POST["txtcuentascg".$i];
$object[$i][1]="<input type=text name=txtcuentaspg".$i." value='".$ls_cuentaspg."' class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
$object[$i][2]="<input type=text name=txtdencuenta".$i." value='".$ls_dencuentaspg."' class=sin-borde readonly style=text-align:center size=50 maxlength=254>";
$object[$i][3]="<input type=text name=txtcuentascg".$i." value='".$ls_cuentascg."' class=sin-borde readonly style=text-align:center size=20 maxlength=25>";
$object[$i][4] ="<a href=javascript:uf_delete_dt('".$i."');><img src=imagebank/tools15/eliminar.gif alt=Cancelar Registro de Detalle Presupuestario width=15 height=15 border=0></a>"; 
?>
</form>
</body>
<script language="JavaScript">
function cat()
{
 f=document.form1;
 f.txtcuenta.readonly=false;
 f.operacion.value="CAT";
 //f.action="sigespwindow_scg_plan_ctas.php";
 window.open("sigesp_catdinamic_ctaspure.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
// f.submit();
}
  function aceptar_cuenta()
  {
  	fop=opener.document.form1;
	f=document.form1;
	i=fop.total.value;
	ls_cuenta=f.txtcuenta.value;
	ls_denominacion=f.txtdenominacion.value;
	ls_cuentascg=f.txtcuentaplan.value;
	eval("fop.txtcuentaspg"+i+".value='"+ls_cuenta+"'");
	eval("fop.txtdencuenta"+i+".value='"+ls_denominacion+"'");
	eval("fop.txtcuentascg"+i+".value='"+ls_cuentascg+"'");
	fop.operacion.value="AGREGAR";
	fop.action="sigesp_spg_d_planctas.php";
	fop.submit();	
  }
  function uf_close()
  {
	  close()
  }
 
 function catalogo_cuentasSCG()
 {
   f=document.form1;
   pagina="sigesp_ctasscg.php";
   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
 }
 
 function valid_cmp(cmp)
{
	if((cmp.value==0)||(cmp.value==""))
	{
	alert("Introduzca un numero comprobante valido");
	cmp.focus();
	}
	else
	{
	rellenar_cad(cmp.value,15,"doc");
	}
}

//Funciones de validacion de fecha.
function rellenar_cad(cadena,longitud,campo)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;
	
	total=longitud-lencad;
	for(i=1;i<=total;i++)
	{
		cadena_ceros=cadena_ceros+"0";
	}
	cadena=cadena_ceros+cadena;
	if(campo=="doc")
	{
		document.form1.txtdocumento.value=cadena;
	}
	else
	{
		document.form1.txtcomprobante.value=cadena;
	}

}

</script>
</html>