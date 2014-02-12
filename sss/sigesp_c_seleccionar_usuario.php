<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Seleccionar Usuario a Actualizar</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<!--<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
--><style type="text/css">
<!--
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
</head>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=     new sigesp_include();
	$con= $in->uf_conectar();
	$io_sql=new class_sql($con);
	require_once("class_funciones_seguridad.php");
	$io_fun_seguridad=new class_funciones_seguridad();
	require_once("sigesp_sss_c_permisos_globales.php");
	$io_sss= new sigesp_sss_c_permisos_globales();
	
	$arr=array_keys($_SESSION);	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$li_count=count($arr);
	
	if (array_key_exists("sist",$_GET))
	{	
		$ls_sistema=$_GET["sist"];
	}
	if (array_key_exists("hidsistema",$_POST))
	{	
		$ls_sistema=$_POST["hidsistema"];
	}

	$ls_sql="SELECT * FROM sss_usuarios".
			" WHERE codemp= '".$ls_empresa."'";
	$result=$io_sql->select($ls_sql);
	$li_row=$io_sql->num_rows($result);
	$li_pos=0;
	while($row=$io_sql->fetch_row($result))
	{
		$li_pos=$li_pos+1;
		$la_usuarios["codusu"][$li_pos]=$row["codusu"];   
	}
	if(array_key_exists("operacion",$_POST))
	{
		$ls_usugrup=$_POST["rbusugrup"];
		$ls_operacion=$_POST["operacion"];	
		if($ls_usugrup=='U')	
		{
			$ls_chkusuario="checked";
			$ls_chkgrupo="";
			$ls_usuario=$_POST["cmbusuarios"];	
		}
		else
		{
			$ls_chkusuario="";
			$ls_chkgrupo="checked";	
			$ls_grupo=$_POST["cmbgrupos"];
		}	
	}
	else
	{
		$ls_operacion="";
		$ls_usugrup='U';
		$ls_chkusuario="checked";
		$ls_chkgrupo="";
	}
	if ($ls_operacion=='SELECCIONAR')
	{
	
		if($ls_usugrup=='U')	
		{
			$ls_usuario=$_POST["cmbusuarios"];
		}
		else
		{
			$ls_usuario=$_POST["cmbgrupos"];
		}
		$ls_sql="SELECT * FROM sss_usuarios".
				" WHERE codemp= '".$ls_empresa."'".
				"   AND codusu='".$ls_usuario."' ";
				
		$result=$io_sql->select($ls_sql);
		$li_row=$io_sql->num_rows($result);
		$li_pos=0;
		while($row=$io_sql->fetch_row($result))
		{
			$li_pos=$li_pos+1;
			$la_usuarios["codusu"][$li_pos]=$row["codusu"];   
			$la_nombreusu["nomusu"][$li_pos]=$row["nomusu"];   
			$la_apellidousu["apeusu"][$li_pos]=$row["apeusu"];   
			
		}
		$_SESSION["la_ususeg"]=$ls_usuario;
		$_SESSION["la_tipo_usugrup"]=$ls_usugrup;
		$_SESSION["la_sistema"]["sistema"]=$ls_sistema;

		print("<script language=JavaScript>");
		print("opener.parent.location.href='sigesp_sss_p_derechousuario.php'");
		print("</script>");
		?>
			<script language="JavaScript">
				close();
			</script>
		<?php
	}
	

?>
<body>
<form name="form1" method="post" action="">
  <div align="center"><br>
  </div>
  <div align="center">
    <table width="41%" height="103" border="0" cellpadding="0" cellspacing="0"  class="formato-blanco">
      <tr >
        <td height="22"  class="titulo-celda">Seleccionar Usuario a Actualizar </td>
      </tr>
      <tr>
        <td height="79"><table width="391" height="79" border="0" cellpadding="0" cellspacing="0">
            
            <tr>
              <td>&nbsp;</td>
              <td height="22">Usuario
                <input name="rbusugrup" type="radio" onChange="javascript:uf_verificar_usugrup();" value="U" checked <?php print $ls_chkusuario;?>>
Grupo
<input name="rbusugrup" type="radio" value="G" <?php print $ls_chkgrupo;?> onChange="javascript:uf_verificar_usugrup();">
<input name="operacion" type="hidden" id="operacion"></td>
            </tr>
            <tr>
              <td width="119"><div align="right">Usuario</div></td>
              <td width="272" height="22"><div align="left">
                <input name="hidsistema" type="hidden" id="hidsistema" value="<?php print $ls_sistema?>" size="6">
                <?php  if($ls_usugrup=='U'){$io_sss->uf_llenar_combo_usuarios($la_usuarios);$io_sss->uf_pintar_combo_usuarios($la_usuarios,$ls_usuario);}else{	$io_sss->uf_llenar_combo_grupos($la_grupos);$io_sss->uf_pintar_combo_grupos($la_grupos,$ls_grupo);}?>
              </div></td>
            </tr>
            <tr>
              <td height="13">&nbsp;</td>
              <td height="22"><input name="botAceptar" type="button" id="botAceptar" value="Aceptar" onClick="ue_aceptar('<?php print $ls_sistema;?>')"></td>
            </tr>
        </table>          </td>
      </tr>
    </table>
  </div>
  <div align="center"></div>
  <p>&nbsp;</p>

</form>
</body>
<script language="JavaScript">
function ue_aceptar(ls_codsis)
{
	f=document.form1;
	f.operacion.value='SELECCIONAR';
	f.action="sigesp_c_seleccionar_usuario.php";
	f.submit();
}

function uf_verificar_usugrup(obj)
{	
	f=document.form1;
	f.operacion.value='CAMBIAR_USUARIO_GRUPO';
	f.action="sigesp_c_seleccionar_usuario.php";
	f.submit();
}
</script>

</html>
