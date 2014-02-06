<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='sigesp_conexion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Par&aacute;metros de Actas</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">

<style type="text/css">
<!--
.style6 {color: #000000}
a:link {
	text-decoration: none;
	color: #000000;
}
a:visited {
	text-decoration: none;
	color: #000000;
}
a:hover {
	text-decoration: none;
	color: #000000;
}
a:active {
	text-decoration: none;
	color: #000000;
}

-->
</style>

<meta http-equiv="Content-Type" content="text/html; charset="></head>
<body>
<span class="toolbar"><a name="inicio"></a></span>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img name="imgnuevo" id="imgnuevo" src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
  <p>&nbsp;
  </p>
 
<form name="form1" method="post" action="">
<?Php
require_once("class_folder/sigesp_sob_c_propietario.php");
	require_once("../shared/class_folder/class_mensajes.php");
	$io_propietario=new sigesp_sob_c_propietario;
	$io_msg=new class_mensajes();
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];		
	}
	else
	{		
		$ls_areamodificable="Area Modificable";
		$ls_pintar="TEXTO";
	}
	
if	(array_key_exists("hidareamodificable",$_POST)){$ls_areamodificable=$_POST["hidareamodificable"]; }
if	(array_key_exists("hidpintar",$_POST)){$ls_pintar=$_POST["hidpintar"]; }
//if	(array_key_exists("txtareramodificable",$_POST)){$ls_areamodificable=$_POST["txtareramodificable"]; }

?>

  <table width="385" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td colspan="6" class="titulo-celdanew">Par&aacute;metros de Actas</td>
        <td>&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="8">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td width="13" height="22">&nbsp;</td>
        <td colspan="6" valign="top"><table width="360" height="381" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000" class="contorno">
          <tr>
            <td height="379" valign="top" class="formato-blanco"><table width="360" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="32" colspan="9"><img src="class_folder/cabecera_minfra.jpg" width="356" height="36"></td>
              </tr>
              <tr>
                <td height="19" colspan="9" class="celdas-blancas"><div align="center">Acta de Inicio </div></td>
              </tr>
              <tr>
                <td width="11" height="2"></td>
                <td colspan="7" bgcolor="#000000"></td>
                <td width="14"></td>
              </tr>
              <tr>
                <td height="19">&nbsp;</td>
                <td height="19" colspan="7" class="celdas-blancas">Obra:
                  </td>
                <td height="19">&nbsp;</td>
              </tr>
              <tr>
                <td height="1"></td>
                <td height="1" colspan="7" bgcolor="#000000"></td>
                <td height="1"></td>
              </tr>
              <tr>
                <td height="19">&nbsp;</td>
                <td height="19" colspan="7" class="celdas-blancas">Ubicaci&oacute;n:</td>
                <td height="19">&nbsp;</td>
              </tr>
			   <tr>
                <td width="11" height="2"></td>
                <td colspan="7" bgcolor="#000000"></td>
                <td width="14"></td>
              </tr>
              <tr>
                <td height="19">&nbsp;</td>
                <td height="19" colspan="5" class="celdas-blancas">Contrato:</td>
                <td height="19" >&nbsp;</td>
                <td height="19" class="celdas-blancas">N&ordm; Contrato:</td>
                <td height="19">&nbsp;</td>
              </tr>
			 <tr>
                <td width="11" height="2"></td>
                <td colspan="7" bgcolor="#000000"></td>
                <td width="14"></td>
              </tr>
              <tr>
                <td height="30" rowspan="2">&nbsp;</td>
                <td height="15" colspan="7" class="celdas-blancas"><p>Aprobaci&oacute;n Contralor&iacute;a Interna:
</p>
                  </td>
                <td height="30" rowspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td height="15" colspan="7" class="celdas-blancas">N&ordm;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Memorandum:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha:</td>
              </tr>
			   <tr>
                <td height="1"></td>
                <td height="1" colspan="7" bgcolor="#000000"></td>
                <td height="1"></td>
              </tr>			 
              <tr>
                <td height="19">&nbsp;</td>
                <td height="19" colspan="7" class="celdas-blancas">Objeto:</td>
                <td height="19">&nbsp;</td>
              </tr>
			   <tr>
                <td width="11" height="2"></td>
                <td colspan="7" bgcolor="#000000"></td>
                <td width="14"></td>
              </tr>
			  
			  
              <tr>
                <td height="110">&nbsp;</td>
                <td height="110" colspan="7" class="celdas-blancas">
                  
				 <?
					if($ls_pintar=="TEXTO")
					{						
					?>
				 	 <table width="329" border="0" cellspacing="0" cellpadding="0" >
				 	<tr>
				 	<td width="21" height="70" class="celdas-blancas" valign="top"><p onDblClick="javascript:pintar()" title="Doble Clic para modificar" ><? print $ls_areamodificable?></p></td>
				 	</tr>
				 	</table>
					<?php
					}else
					{
						$la_textos=array("Ing.Inspector","Ing.Residente","Nº1","Nº2","Dia","Mes","Año");
					?>
					 <table width="329" border="0" cellspacing="0" cellpadding="0" >
				 	<td height="17" colspan="2" valign="top" class="celdas-blancas">
					   <div align="left">Seleccione la variables que desee agregar</div></td>
				 	    <tr>                     
                      <td width="83" height="32" valign="top" class="celdas-blancas">
					  <select name="select">
					  <option value="---">Seleccione</option>
					  <?
						  for($li_i=0;$li_i<count($la_textos);$li_i++)
							{
							 $ls_codigo=$la_textos[$li_i];
							 $ls_descripcion=$la_textos[$li_i];
							 if ($ls_codigo==$ls_hidtexto)
							 {
								  print "<option value='$ls_codigo' selected>$ls_descripcion</option>";
							 }
							 else
							 {
								  print "<option value='$ls_codigo'>$ls_descripcion</option>";
							 }
							}
						?>
                      </select>
					     <input name="hidtexto" type="hidden" id="hidtexto" value="<? print $ls_hidtexto ?>">	
					  </td>
                      <td width="225" valign="top" class="celdas-blancas"><div align="right">
                        <textarea name="txtareamodificable" cols="40" rows="5"  id="textarea" title="Doble Clic para visualizar" onDblClick="javascript:pintar()" onKeyPress="return validaCajas(this,'a',event)"><? print $ls_areamodificable?></textarea>
                      </div></td>
                    </tr>
                  </table>		
				  <?
				  }
				  ?>		  
				  
				  
                 
                  <div align="left"></div></td>
                <td height="110">&nbsp;</td>
              </tr>		 
			  
              <tr>
                <td height="19" rowspan="2">&nbsp;</td>
                <td width="33" height="19" rowspan="2">&nbsp;</td>
                <td width="100" class="celdas-blancas"><p>Por &quot;El Ministerio&quot; </p>
                  </td>
                <td width="71" rowspan="2">&nbsp;</td>
                <td width="11" rowspan="2">&nbsp;</td>
                <td width="1" rowspan="2">&nbsp;</td>
                <td height="19"  width="1" rowspan="2">&nbsp;</td>
                <td width="110" height="10" class="celdas-blancas">Por:</td>
                <td height="19" rowspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td class="celdas-blancas">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Firma)</td>
                <td height="9" class="celdas-blancas">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Firma)</td>
              </tr>
              <tr>
                <td height="29">&nbsp;</td>
                <td height="29">&nbsp;</td>
                <td height="29" align="left" valign="bottom"><div align="left"></div>
                  <hr width="100" noshade color="#000000"></td>
                <td height="29">&nbsp;</td>
                <td height="29">&nbsp;</td>
                <td height="29" width="1">&nbsp;</td>
                <td height="29" width="1">&nbsp;</td>
                <td height="29" valign="bottom"><hr width="100" noshade color="#000000"></td>
                <td height="29">&nbsp;</td>
              </tr>
              <tr>
                <td height="19">&nbsp;</td>
                <td height="19">&nbsp;</td>
                <td height="19" class="celdas-blancas">Nombre</td>
                <td height="19">&nbsp;</td>
                <td height="19">&nbsp;</td>
                <td height="19" width="1">&nbsp;</td>
                <td height="19" width="1">&nbsp;</td>
                <td height="19"><span class="celdas-blancas">Nombre</span></td>
                <td height="19">&nbsp;</td>
              </tr>
              <tr>
                <td height="19">&nbsp;</td>
                <td height="19">&nbsp;</td>
                <td height="19" class="celdas-blancas">C.I.:</td>
                <td height="19">&nbsp;</td>
                <td height="19">&nbsp;</td>
                <td height="19" width="1">&nbsp;</td>
                <td height="19" width="1">&nbsp;</td>
                <td height="19" class="celdas-blancas">C.I.:</td>
                <td height="19">&nbsp;</td>
              </tr>
              <tr>
                <td height="19" colspan="9">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td width="9">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="13" colspan="8"><div align="right"></div><a href="javascript:uf_mostrar_ocultar_asignacion();">&nbsp;&nbsp;&nbsp;</a>          <div align="right"></div></td>
      </tr>		
  </table>
  <!-- Los Hidden son colocados a partir de aca-->
<input name="operacion" type="hidden" id="operacion" value="<? print $ls_operacion;?>">
<input name="hidareamodificable" type="hidden" value="<? print $ls_areamodificable?>">
<input name="hidpintar" type="hidden" value="<? print $ls_pintar?>">
<!-- Fin de la declaracion de Hidden-->

</form>
</body>
<script language="javascript">
function pintar()
{
	f=document.form1;	
	if(f.hidpintar.value=="TEXTO")
	{
		f.hidpintar.value="AREA";
		cadena=f.hidareamodificable.value;
		for(i=0;i<cadena.length;i++)
		{
		 //alert(String.fromCharCode(13));
		 if((cadena.charAt(i)=='<') && (cadena.charAt(i+1)=='b') && (cadena.charAt(i+2)=='r') && (cadena.charAt(i+3)=='>'))
			{
				//alert("");
				cadena=cadena.substr(0,i)+"\n"+cadena.substr(i+4,cadena.length-i+4);				
				i=i+3;
			}			
		}		
		
	}
	else
	{		
		cadena=f.txtareamodificable.value;
		for(i=0;i<cadena.length;i++)
		{
		 //alert(String.fromCharCode(13));
			if(cadena.charAt(i)=='\n')
			{
				
				cadena=cadena.substr(0,i)+"<br>"+cadena.substr(i+1,cadena.length-i);
			}	
					
		}		
		
		f.hidpintar.value="TEXTO";
	}
	f.hidareamodificable.value=cadena;
	f.submit();
}

function ue_guardar()
{

}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>