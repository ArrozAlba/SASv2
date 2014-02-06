<?php
session_start();
	if (isset($_GET["cedper"]))
	{ $ls_cedper=$_GET["cedper"];	}
	else
	{ $ls_cedper="";}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SIGESP - Sistema Integrado de Gesti&oacute;n para Entes del Sector P&uacute;blico</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_cat_accidentes.js"></script>



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

</head>

<body>

<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_personal.php");
	$io_personal=new sigesp_srh_c_personal("../../../../");
	require_once("../../../class_folder/utilidades/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	global $ls_nomfot,$ls_operacion;
	$ls_nomfot="-";	
	$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
	
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			
			$lb_valido=true;
			$ls_nomfot="";	
			$ls_nomfot=$HTTP_POST_FILES['txtfotper']['name'];
			$ls_cedper=$_POST["txtcedper"];
			if ($ls_nomfot!="")
			{
				$ls_nomfot=$ls_cedper.substr($ls_nomfot,strrpos($ls_nomfot,"."));
			}
			$ls_tipfot=$HTTP_POST_FILES['txtfotper']['type']; 
			$ls_tamfot=$HTTP_POST_FILES['txtfotper']['size']; 
			$ls_nomtemfot=$HTTP_POST_FILES['txtfotper']['tmp_name'];
			$ls_nomfot=$io_personal->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);
			
			
	}
			
?>
<form action="" method="post" enctype="multipart/form-data" name="form1">
  <p align="center">
       
</p>
  <input name="operacion" type="hidden" id="operacion">
  <input name="txtcedper" type="hidden" id="txtceper" value="<?php print $ls_cedper?>">
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Carga de Foto</td>
    </tr>
  </table>
<br>
    <table width="519" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td width="405" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td width="114"><div align="right">Foto</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtfotper" type="file" id="txtfotper" size="50" maxlength="200">
		  <input name="hidfotper" type="hidden" id="hidfotper" size="50" maxlength="200" value="<?php print $ls_nomfot?>">
		  <?php 
		  if (($ls_nomfot!="")&&($ls_nomfot!="-"))
			{
			  ?>
			  <script language="javascript">
			    nomfoto=document.form1.hidfotper.value;
				opener.document.form1.hidfotper.value=nomfoto;
				foto=opener.document.getElementById('foto');
				foto.src="";
				foto.src="../../../../sno/fotospersonal/"+nomfoto;
				alert ('Se Cargo la foto exitosamente');
				close ();
			  </script>
			  <?php  
			}
			elseif ($ls_nomfot!="-")
			{
			  ?>
			  <script language="javascript">
				alert ('Ocurrio un error al cargar la foto');
			 </script>
			  <?php  
			}
		 ?>
        </div></td>
      </tr>
      <tr>
        <td width="114"><div align="right"></div></td>
        <td height="22" colspan="2"><div align="right">
         <a href="javascript: ue_guardar_foto();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0">Cargar Foto</a>
        </div></td>
      </tr>
      
       

  </tr>
  
      <tr>
        <td>&nbsp;</td>
      </tr>
		
  </table>

  
 
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>

<script language="javascript">
function ue_guardar_foto()
{
	if (document.form1.txtfotper.value=="")
	{
		alert('Debe seleccionar un archivo');
	}
	else
	{
		f=document.form1;
		f.operacion.value="GUARDAR";
		f.action="sigesp_srh_cargar_foto.php";
		f.submit();
	}   	

}


</script>

