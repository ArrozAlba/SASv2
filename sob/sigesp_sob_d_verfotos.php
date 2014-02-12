<?
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";		
} 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Visualizador de Fotos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699#006699;
}
-->
</style></head>
<?Php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_datastore.php");
require_once ("class_folder/sigesp_sob_c_funciones_sob.php");
require_once("../shared/class_folder/class_funciones_db.php");
$io_funsob= new sigesp_sob_c_funciones_sob(); 
$io_datastore=new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_fundb = new class_funciones_db($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$la_empresa=$_SESSION["la_empresa"];
$ls_codemp=$la_empresa["codemp"];

	//-------------------------------------Seleccionando las fotos desde BD------------------------------//
	$ls_opener=$_GET["opener"];
	$ls_campo=$_GET["campocodigo"];
	if($ls_opener=="obra")
	{
		$ls_sql="SELECT * FROM sob_foto WHERE codemp='$ls_codemp' AND codobr='$ls_campo'";		
	}
	else
	{
		$ls_contrato=$_GET["contrato"];
		$ls_sql="SELECT * FROM sob_foto WHERE codemp='$ls_codemp' AND codcon='$ls_contrato' AND codval='$ls_campo'";		
	}
	
	$rs_data=$io_sql->select($ls_sql);
	$data=$rs_data;
	if($rs_data===false)
	{
		$is_msg_error="Error en select de fotos".$io_funcion->uf_convertirmsg($io_sql->message);
		print $is_msg_error;
	}else
	{
		$la_fotos=array();
		$li_count=0;
		while($row=$io_sql->fetch_row($rs_data))
		{
			$la_fotos["codfot"][$li_count]=$row["codfot"];
			$la_fotos["foto"][$li_count]=$row["foto"];
			$la_fotos["tam"][$li_count]=$row["tamfot"];
			$la_fotos["tipo"][$li_count]=$row["tipfot"];
			$la_fotos["nombre"][$li_count]=$row["nomfot"];
			$la_fotos["alto"][$li_count]=$row["altfot"];
			$la_fotos["ancho"][$li_count]=$row["ancfot"];			
			$li_count++;			
		}
	}
?>

<body>
<form name="form1" method="post" action="">

  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="790" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="800" colspan="2" class="titulo-celda">Visualizador de Fotos</td>
    	</tr>
  </table>
	 <br>
	 <table width="800" border="0" cellpadding="0" cellspacing="3" class="formato-blanco" align="center">
      <tr>
        <td height="116" colspan="6" class="contorno">
		<div align="center">	
		
		  <table  border="1">
            <tr>             
			  <?
			  $li_columnas=0;
			  $_SESSION["contador"]=$li_count;
			  $_SESSION["la_fotos"]=$la_fotos;
			  for($li_i=0;$li_i<$li_count;$li_i++)
			  {
			  		$li_columnas++;
					if($li_columnas==7)
					{
						$li_columnas=1;
						print "</tr>";
						print "<tr>";
					}
			  	    print"<td width='130' height='130' align=center>";
					$ls_codigo=$la_fotos["codfot"][$li_i];
			  		$_SESSION["foto".$ls_codigo]=$la_fotos["foto"][$li_i];
					$ls_nombre=$la_fotos["nombre"][$li_i];
					$_SESSION["nombre".$ls_codigo]=$ls_nombre;
					print "<a href=javascript:ue_mostrar_foto('$ls_codigo')><img src='sigesp_sob_d_verfotos2.php?codigo=$ls_codigo'  border='0' width='120' height='120' style=' border-color:#3399FF; border:groove ' title='$ls_nombre'/></a>";					
					print "\n $ls_nombre";
					print"</td>";
			  }
			 	?>	
					  
			</tr>
          </table>
		  </div>
	    </td>
	   </tr>
  </table>
        </div></td>
       <td height="2"></tr>
    </table>
	<br>

</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="javascript">
	function ue_mostrar_foto(codigo)
	{
		pagina="sigesp_sob_d_verfotoampliada.php?codigo="+codigo;
		popupWin(pagina,"catalogo",650,630);
	}

</script>
</html>
