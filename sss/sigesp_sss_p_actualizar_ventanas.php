<?php
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_seguridad.php");
$io_fun_seguridad=new class_funciones_seguridad();
$io_fun_seguridad->uf_load_seguridad("SSS","sigesp_c_actualizar_ventanas.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	function uf_pintar_combo($la_sistemas,$ls_sistemas)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_pintar_combo
	//	Access:    public
	//	Arguments:
	//  la_sistema // arreglo de valores que puede tomar el combo.
	//  ls_sistema // item seleccionado.
	//	Description:  Esta funcion carga el combo de metodo de depreciacion manteniendo la seleccion.
	//              
	//////////////////////////////////////////////////////////////////////////////		
		
		print "<select name='cmbsistemas' id='cmbsistemas' style='width:280px' onChange='javascript: ue_seleccionar();'>";
		print "<option value= --- selected>--Seleccione Uno-- </option>";
		$li_total=count($la_sistemas["codsis"]);
		for($i=0; $i < $li_total ; $i++)
		{			
			print "<option value='".$la_sistemas["codsis"][$i]."'>".$la_sistemas["nomsis"][$i]."</option>";
		}
		print"</select>";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Actualizaci&oacute;n de Ventanas del Sistema</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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

<style type="text/css">
<!--
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
</head>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("sigesp_sss_c_actualizar_ventana.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	$io_sss= new sigesp_sss_c_actualizar_ventana();
	$io_msg = new class_mensajes();
	$in=     new sigesp_include();
	$con= $in->uf_conectar();
	$io_sql=new class_sql($con);

	$ls_empresa=$_SESSION["la_empresa"]["codemp"];
	$io_sss->uf_sss_load_sistemas($la_sistemas);
	$ls_operacion=$io_fun_seguridad->uf_obteneroperacion();
	$ls_codsis=$io_fun_seguridad->uf_obtenervalor(trim("hidsist"),"");
	if($ls_operacion=="ACTUALIZAR")
	{
		if($ls_codsis!="")
		{
			$ls_codsisaux=strtolower($ls_codsis);
			if(file_exists("arbol/sigesp_arbol_".$ls_codsisaux.".php"))
			{
				include("arbol/sigesp_arbol_".$ls_codsisaux.".php");
				$li_total=$gi_total;
				$io_sql->begin_transaction();
				for($i=1; $i<=$li_total; $i++)
				{
					$ls_nomfisico=$arbol["nombre_fisico"][$i];
					$ls_nomlogico=$arbol["nombre_logico"][$i];
					$ls_numhij=$arbol["numero_hijos"][$i];
					$ls_nompadre=$arbol["padre"][$i];
					if ($ls_nomfisico!="")
					{
						$lb_existe=$io_sss->uf_sss_select_ventana($ls_codsis,$ls_nomfisico);
						if($lb_existe)
						{
							$lb_valido=$io_sss->uf_sss_update_ventana($ls_codsis,$ls_nomfisico,$ls_nomlogico,$la_seguridad); 
						}
						else
						{
							$ls_descripcion="";
							$lb_valido=$io_sss->uf_sss_insert_ventana($ls_codsis,$ls_nomfisico,$ls_nomlogico,$ls_descripcion,$la_seguridad);
					
						}
						if(!$lb_valido)
						{break;}
					}
		
				}//end del for($i=1; $i<=$li_total; $i++)
				if($lb_valido)
				{
					$io_msg->message("Las ventanas fueron procesadas");
					$io_sql->commit();
				}
				else
				{
					$io_msg->message("No se pudieron procesar las ventanas");
					$io_sql->rollback();		  
				}
			}
			else
			{
				$io_msg->message("No existe archivo de Ventanas");
				$ls_sistemas="";
			}
		}
		else
		{
			$io_msg->message("Debe elegir un sistema");
			$ls_sistemas="";
		}
	}
	
?>
<body>
<form name="form1" method="post" action="">

<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_seguridad->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_seguridad);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  

  <div align="center"><br>
  </div>
  <div align="center">
    <table width="38%" height="194" border="0" cellpadding="0" cellspacing="0"  class="formato-blanco">
      <tr >
        <td height="22"  class="titulo-celdanew">Actualizaci&oacute;n de Ventanas del Sistema</td>
      </tr>
      <tr>
        <td height="170"  class="formato-blanco"><p>&nbsp;</p>
        <table width="383" height="138" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="92">&nbsp;</td>
              <td width="99">&nbsp;</td>
              <td width="115">&nbsp;</td>
              <td width="77">&nbsp;</td>
            </tr>
            <tr>
              <td height="26"><div align="right"><span class="Estilo1">Sistema</span></div></td>
              <td colspan="2">
                <div align="right">                </div>                <div align="center">
                  <?php uf_pintar_combo($la_sistemas,$ls_codsis);?>
                </div></td><td><input name="operacion" type="hidden" id="operacion">
                  <input name="hidsist" type="hidden" id="hidsist"></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2"><div align="center">
                <input name="btnactualizar" type="button" class="boton" id="btnactualizar" onClick="javascript: ue_aceptar();" value="Actualizar Ventanas">
</div></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
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
function ue_aceptar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		f.operacion.value="ACTUALIZAR";
		f.action="sigesp_sss_p_actualizar_ventanas.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_seleccionar()
{
	f=document.form1;
	var selectedItem = document.getElementById('cmbsistemas').selectedIndex;
	var selectedText = document.getElementById('cmbsistemas').options[selectedItem].text;
	var selectedValue = document.getElementById('cmbsistemas').options[selectedItem].value;
	f.hidsist.value=selectedValue;
}
</script>

</html>
