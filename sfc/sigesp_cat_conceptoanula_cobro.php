<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	//require_once("class_folder/class_funciones_mis.php");
	//$in_class_mis=new class_funciones_mis();
	//$li_row=$in_class_mis->uf_obtenervalor_get("row","");
        $cobro = $_GET['cobro'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
<title>Anulaci&oacute;n del Cobro # <?php echo $cobro; ?></title>
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
  <table width="382" height="88" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="2">Concepto de Anulaci&oacute;n </td>
    </tr>
    <tr>
      <td height="22" colspan="2"><div align="center">Favor indique el motivo de la anulaci&oacute;n del Cobro  <?php echo $cobro; ?>: </div></td>
    </tr>
    <tr>
      <td height="22" colspan="2" align="center"><textarea name="txtconanu" cols="65" rows="4" id="txtconanu"  onKeyUp="/*javascript: ue_validarcomillas(this);*/" onKeyPress="javascript: ue_enviar(event);"></textarea></td>
    </tr>
    <tr>
      <td width="308" height="22" align="center" ><div align="right"><a href="javascript: ue_aceptar();"><img src="../shared/imagebank/tools15/aprobado.gif" width="15" height="15" class="sin-borde">Aceptar</a></div></td>
      <td width="72" align="center"><div align="center"><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/tools15/eliminar.gif" width="15" height="15" class="sin-borde">Cancelar</a></div></td>
    </tr>
  </table>
  <p align="center">
    <input name="row" type="hidden" id="row" value="<?php print $li_row; ?>">
  </p>
  </div>
</form>
</body>
<script language="javascript">
function ue_aceptar()
{
	f=document.form1;
	row=f.row.value;
	conanu=f.txtconanu.value;
	if(conanu!="")
	{
                
		window.opener.verificarAnulacion(conanu);
                //obj=eval("opener.document.form1.txtconanu"+row+"");
		//obj.value=conanu;
                close();
	}
	else
	{
		alert('Debe insertar un motivo');
                
	}
	
}

function ue_cancelar()
{
	//f=document.form1;
	//row=f.row.value;
	//obj=eval("opener.document.form1.chksel"+row+"");
	//obj.checked=false;
       
	close();
}
function ue_enviar(e)
{
    var whichCode = (window.Event) ? e.which : e.keyCode; 
	if (whichCode == 13) // Enter 
	{
		ue_aceptar();
	}
}
</script>
</html>