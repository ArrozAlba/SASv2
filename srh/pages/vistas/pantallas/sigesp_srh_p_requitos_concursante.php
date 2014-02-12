<?php
session_start();
	
if (isset($_GET["codper"]))
{
	 $ls_codper=$_GET["codper"];	
}	
	

if (isset($_GET["codcon"]))
{
	$ls_codcon=$_GET["codcon"];
}	

if (isset($_GET["operacion"]))
{
	$ls_operacion=$_GET["operacion"];
}	
else
{
	$ls_operacion="";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Requisitos del Concurso</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_inscripcion_concurso.js"></script>


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
	require_once("../../../class_folder/dao/sigesp_srh_c_inscripcion_concurso.php");
	$io_req=new sigesp_srh_c_inscripcion_concurso("../../../../");
	require_once("../../../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title;
	 
	$ls_titletable="Requisitos del Concurso";
	$li_widthtable=550;
	$ls_nametable="grid";
	$lo_title[1]="Código";
	$lo_title[2]="Descripción";
	$lo_title[3]="Cantidad";
	$lo_title[4]="Entrega";
	$lo_title[5]="Cantidad Entregada";
	
	switch ($ls_operacion) 
	{
		case "CARGAR":
			$lb_valido=$io_req->uf_srh_cargar_requistos_concurso($ls_codcon,$li_totrows,$lo_object);
		break;
		
		case "CONSULTAR":
			$lb_valido=$io_req->uf_srh_consultar_requistos_concursante($ls_codcon,$ls_codper,$li_totrows,$lo_object,$lb_existe);
			if (!$lb_existe)			
			{
			   print "<script>";
			   print "close();";
			   print "</script>";	
			}
		break;
	}
	
?>

<form name="form1" method="post" action="">
  <p align="center">
  
  
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Requisitos del Concurso</td>
    </tr>
  </table>
  <br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td width="272" colspan="2">&nbsp;</td>
      </tr>
	   <tr>
        <td width="228"><div align="right">C&oacute;digo Concursante </div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtcodcon" type="text" id="txtcodcon" value="<?php print $ls_codcon?>"  size=16 readonly style="text-align:center">
        </div></td>
      </tr>
	        <tr>
        <td width="228"><div align="right">C&eacute;dula Concursante </div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtcodper" type="text" id="txtcodper" value="<?php print $ls_codper?>"  size=16 readonly style="text-align:center">
        </div></td>
      </tr>
	   
	  <tr>
        <td><div align="right"></div></td>
        <td width="272" colspan="2"><div align="right"><a href="javascript: ue_guardar_requisitos_concurso();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0">Guardar / Modificar Requisitos</a></div></td>
      </tr>
	  <tr>
		<p><div id="mostrar" align="center"></div></p>	
	   </tr>
       <tr>
          <td colspan="4">
		  	<div align="center">
			<?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
			  </div>
		  	<p>
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">              
			  <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
			  <input name="operacion" type="hidden" id="operacion">
			</p>			</td>		  
		</tr>
		
	  
    </table>
	
 
</div>
</form>
<p>&nbsp;</p>

</body>

</html>

