<?
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";		
} 
require_once("../shared/class_folder/class_funciones.php");
$io_function=new class_funciones();
$li_count=$_SESSION["contador"];
$la_fotos=$_SESSION["la_fotos"];
if(!array_key_exists("operacion",$_POST))
{
	$ls_codigo=$_GET["codigo"];
}
else
{
	$ls_codigo=$_POST["hidcodigo"];
	$ls_operacion=$_POST["operacion"];
	if($ls_operacion=="adelante")
	{
		for($li_i=0;$li_i<$li_count;$li_i++)
		{
			if($la_fotos["codfot"][$li_i]==$ls_codigo)
			{
				if($li_i+1<$li_count)
				{
					$ls_codigo=$la_fotos["codfot"][$li_i+1];
				}
				else
				{
					$ls_codigo=$la_fotos["codfot"][0];
				}
				break;
			}
		}				
	}
	elseif($ls_operacion=="atras")
	{
		for($li_i=0;$li_i<$li_count;$li_i++)
		{
			if($la_fotos["codfot"][$li_i]==$ls_codigo)
			{
				if($li_i-1>0)
				{
					$ls_codigo=$la_fotos["codfot"][$li_i-1];
				}
				else
				{
					$ls_codigo=$la_fotos["codfot"][$li_count-1];
				}
				break;
			}
		}			
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<title><?Php print $_SESSION["nombre".$ls_codigo]?></title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<title></title>
</head>
<body>
<form name="form1" method="post" action="" >
<input type="hidden" name="operacion" id="operacion" />
<input type="hidden" name="hidcodigo" id="hidcodigo" value="<?Php print $ls_codigo?>">
<table width="600" height="600" border="1" align="center">
  <tr>
     <td height="526" colspan="4" align="center">
<?		
			for($li_i=0;$li_i<$li_count;$li_i++)
			{
				if($la_fotos["codfot"][$li_i]==$ls_codigo)
				{
					$li_ancho=$la_fotos["ancho"][$li_i];
					$li_alto=$la_fotos["alto"][$li_i];
					break;
				}
			}		
			if($li_ancho>600)
				$li_ancho=600;
			if($li_alto>526)
				$li_alto=526;	
			print"<img src='sigesp_sob_d_verfotos2.php?codigo=$ls_codigo' width=$li_ancho height=$li_alto />";
?>
	
	</td>
  </tr>
  <tr>  
     <td width="227" height="50">&nbsp;</td>
    <td align="right"><a href="javascript:ue_moverfoto('atras')"><img src="Imagenes/Flechaizq.jpg" width="50" height="46" border="0"  /></a></td>
   <td  align="left"><a href="javascript:ue_moverfoto('adelante')"><img src="Imagenes/Flecha.jpg" width="50" height="46" border="0"  /></a></td>
     <td width="229">&nbsp;</td>	
 </tr>
</table>
</form>
</body>
<script language="javascript">
function ue_moverfoto(direccion)
{
	f=document.form1;
	f.operacion.value=direccion;
	f.submit();
}
</script>
