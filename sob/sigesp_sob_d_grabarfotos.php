<?Php
	session_start();
	if (!array_key_exists("la_logusr",$_SESSION))
	   {
		 print "<script language=JavaScript>";
		 print "location.href='../sigesp_conexion.php';";
		 print "</script>";		
	   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Guardar Fotos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<style type="text/css">
<!--
body {
	margin-top: 40px;
}
-->
</style>

</head>
<?
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

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_campoclave=$_SESSION["campoclave"];
	$ls_opener=$_POST["hidopener"];
	if($ls_operacion=="ue_aceptar")
	{
		$la_nombrearchivospermitidos=array("","GIF","JPG","PNG","SWF","PSD","BMP","TIFF","TIFF","JPC","JP2","JPX","JB2","SWC","IFF","WBMP","XBM");
		$ls_ruta=$_POST["hidfile"];
		$la_dataimagen=getimagesize($ls_ruta);
		$ls_ext=$la_dataimagen[2];
		$li_alto=$la_dataimagen[1];
		$li_ancho=$la_dataimagen[0];
		$la_nombre=explode(".",basename($ls_ruta));
		if($_POST["txtnomfot"]!="")
		{
			$ls_nombre=$_POST["txtnomfot"];
		}
		else
		{
			$ls_nombre=$la_nombre[0];
		}
		$ls_descripcion=$_POST["txtdesfot"];
		$li_tamano=filesize($ls_ruta);		
		if($ls_ext!==false)
		{
			$ls_ext=$la_nombrearchivospermitidos[$ls_ext];
			if($li_tamano<=102400)
			{
				if ($li_tamano!==false && $li_tamano>0)
				{
					  $ls_apuntador=fopen($ls_ruta,"rb");
					  $li_cont=0;
					  while((!feof($ls_apuntador))&&($li_cont<=0))
					  {
						   $datarc=chunk_split(base64_encode(fread($ls_apuntador,$li_tamano)));
						   $li_cont++;
					  }			
				}					
					
				 $ls_codfot=$io_fundb->uf_generar_codigo(false,$ls_codemp,"sob_foto","codfot",10);
				 if($ls_opener=="obra")
				 {
				 $ls_sql="INSERT INTO sob_foto (codfot,codobr,codemp,nomfot,tipfot,desfot,altfot,ancfot,foto,tamfot) VALUES ('$ls_codfot','$ls_campoclave','$ls_codemp','$ls_nombre','$ls_ext','$ls_descripcion','$li_alto','$li_ancho','$datarc','$li_tamano')";
				 }	
				 else
				 {
				 	$ls_contrato=$_SESSION["contrato"];
					$ls_sql="INSERT INTO sob_foto (codfot,codval,codcon,codemp,nomfot,tipfot,desfot,altfot,ancfot,foto,tamfot) VALUES ('$ls_codfot','$ls_campoclave','$ls_contrato','$ls_codemp','$ls_nombre','$ls_ext','$ls_descripcion','$li_alto','$li_ancho','$datarc','$li_tamano')";			
					    
				 }
				 $io_sql->begin_transaction();	
				 $li_row=$io_sql->execute($ls_sql);
				 if($li_row===false)
				 {			
						print "Error al insertar Foto".$io_funcion->uf_convertirmsg($io_sql->message);
						$io_sql->rollback();	
				 }
				 else
				 {
					if($li_row>0)
					{			
						$io_sql->commit();
						$io_msg->message('La foto fue incluida!!!');
							
					}
					else
					{			
						$io_sql->rollback();
					}
				  }	 
			}
			else
			{
				$io_msg->message("Seleccione un archivo menor a 100 Kb.");
			}
			 
		}
		else
		{
			$io_msg->message('Seleccione un archivo permitido: \n   -JPG \n -GIF');
		}		
	}	
}
else
{
	$ls_opener=$_GET["opener"];
}
?>

<body>
<form name="form1" method="post" action="">
<table width="356" border="0" align="center" cellpadding="0" cellspacing="0" class="titulo-celdanew">
  <tr>
    <td width="301"><div align="center">Seleccione la plantilla del Documento </div></td>
  </tr>
</table>
  <table width="356" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="43"><div align="right">Nombre</div></td>
      <td height="43"><label>
        <input name="txtnomfot" type="text" id="txtnomfot" size="40" maxlength="26">
      </label></td>
    </tr>
    <tr>
      <td height="43"><div align="right">Descripci&oacute;n</div></td>
      <td height="43"><label>
        <textarea name="txtdesfot" cols="37" rows="2" wrap="virtual" id="txtdesfot"></textarea>
      </label></td>
    </tr>
    <tr>
      <td height="43" colspan="2"><div align="center"><input type="file" class="celdas-amarillas" size="40" name="txtfile" id="txtfile" >
      </div></td>
    </tr>
    <tr>
      <td width="107"><div align="right"></div></td>
      <td width="247"><a href="javascript:uf_aceptar();"><img src="../shared/imagebank/aprobado.gif" alt="Aceptar" width="15" height="15" border="0" ></a><a href="javascript:uf_cancelar();"><img src="../shared/imagebank/eliminar.gif" border="0" alt="Cancelar" width="15" height="15" ></a></td>
    </tr>
  </table>
  <input type="hidden" name="operacion" id="operacion">
  <input type="hidden" name="hidfile" id="hidfile">
  <input type="hidden" name="hidopener" id="hidopener" value="<?Php print $ls_opener?>">
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
function uf_aceptar()
{
	f=document.form1;
	if (f.txtfile.value!="")
	{
		f.hidfile.value=f.txtfile.value;
		f.operacion.value="ue_aceptar";
		f.submit();	
	}	
	else
	{
		alert("Seleccione un Archivo!!!");
		
	}

}

function uf_cancelar()
{
	close();
}

</script>
</html>
