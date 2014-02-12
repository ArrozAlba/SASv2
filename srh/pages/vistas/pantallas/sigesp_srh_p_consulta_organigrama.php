<?php
	session_start();
  	unset($_SESSION["parametros"]);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("../../../class_folder/utilidades/class_funciones_srh.php");
require_once("../../../class_folder/utilidades/class_srh.php");
$io_fun_srh=new class_funciones_srh('../../../../');
$io_class_srh=new class_srh('../../../../');
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_consulta_organigrama.php",$ls_permisos,$la_seguridad,$la_permisos);
require_once("../../../class_folder/utilidades/class_funciones_nomina.php");
$io_fun_nomina=new class_funciones_nomina();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
 
 function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		

   		global $ls_nivorg, $ls_codorg,$ls_desorg,$ls_operacion,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_nivorg="";
		$ls_desorg="";
		$ls_codorg="";		
		$la_nivorg[0]="";
		$la_nivorg[1]="";
		$la_nivorg[2]="";
		$la_nivorg[3]="";
		$la_nivorg[4]="";
		$la_nivorg[5]="";
		$la_nivorg[6]="";
		$la_nivorg[7]="";
		$la_nivorg[8]="";
		$la_nivorg[9]="";
		$la_nivorg[10]="";
		$ls_titletable="Detalle del Personal";
		$li_widthtable=550;
		$ls_nametable="grid";
		$lo_title[1]=" ";
		$lo_title[2]="Código";
		$lo_title[3]="Cédula";
		$lo_title[4]="Apellidos y Nombres";
		$lo_title[5]="Unidad Administrativa";		
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
	
   }
   //--------------------------------------------------------------

  
   
?>
	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>SIGESP - Sistema Integrado de Gesti&oacute;n para Entes del Sector P&uacute;blico</title>



<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #f3f3f3;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
.Estilo25 {
	color: #6699CC;
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
}
-->
</style>

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_consulta_organigrama.js"></script>

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


</head>

<body >

<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_organigrama.php");
	$io_org=new sigesp_srh_c_organigrama("../../../../");
	require_once("../../../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();		
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
	
	
		 case "CONSULTAR":
		 	$ls_codorg=$_POST["txtcodorg"];	
			$ls_desorg=$_POST["txtdesorg"];	   
			$ls_nivorg=$_POST["cmbnivorg"];
			switch ($ls_nivorg)
			{
				case '0':
					$la_nivorg[0]="selected";					
				break;
				case '1':
					$la_nivorg[1]="selected";					
				break;
				case '2':
					$la_nivorg[2]="selected";					
				break;
				case '3':
					$la_nivorg[3]="selected";					
				break;
				case '4':
					$la_nivorg[4]="selected";					
				break;
				case '5':
					$la_nivorg[5]="selected";					
				break;
				case '6':
					$la_nivorg[6]="selected";					
				break;
				case '7':
					$la_nivorg[7]="selected";					
				break;
				case '8':
					$la_nivorg[8]="selected";					
				break;
				case '9':
					$la_nivorg[9]="selected";					
				break;
				case '10':
					$la_nivorg[10]="selected";					
				break;
			
			}
			$lb_valido=$io_org->uf_srh_consultar_organigrama($ls_codorg,$ls_nivorg,$lo_object,$li_totrows);
			break;	
			
			
	}
	
	unset($io_obj);
?>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../../../public/imagenes/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo25">Sistema de Recursos Humanos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
 <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../../js/menu/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>

  <tr>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../../public/imagenes/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../../../public/imagenes/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
   
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>


<p>&nbsp;</p>

<form name="form1" method="post" action=""  >
 <div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
      <table width="689" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="689" height="136"><p>
      <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    </p>
      <p>&nbsp;</p>
      <table width="623" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
        <tr class="titulo-nuevo">
          <td height="22" colspan="11">Consulta de Organigrama</td>
        </tr>
        
       <tr>
          <td height="22"><div align="right">Nivel</div></td>
          <td height="22" colspan="4">
            <div align="left">
              <select name="cmbnivorg" id="cmbnivorg">
                <option value="" selected>-- Seleccione --</option>
				<option value="0" <?php print $la_nivorg[0] ?>>0</option>
                <option value="1" <?php print $la_nivorg[1] ?>>1</option>
                <option value="2" <?php print $la_nivorg[2] ?>>2</option>
				<option value="3" <?php print $la_nivorg[3] ?>>3</option>
				<option value="4" <?php print $la_nivorg[4] ?>>4</option>
				<option value="5" <?php print $la_nivorg[5] ?>>5</option>
				<option value="6" <?php print $la_nivorg[6] ?>>6</option>
				<option value="7" <?php print $la_nivorg[7] ?>>7</option>
				<option value="8" <?php print $la_nivorg[8] ?>>8</option>
				<option value="9" <?php print $la_nivorg[9] ?>>9</option>
				<option value="10" <?php print $la_nivorg[10] ?>>10</option>
              </select>
            </div></td>
        </tr>
	   
        <tr>
          <td height="22"><div align="right"> Unidad Administrativa </div></td>
          <td colspan="5"><input name="txtcodorg" type="text" id="txtcodorg"   size="16"   style="text-align:center" readonly value="<?php print $ls_codorg?>" ><a href="javascript:ue_buscarunidad();"><img src="../../../public/imagenes/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip"></a> <input name="txtdesorg" type="text" id="txtdesorg" size="60" maxlength="254" readonly class="sin-borde" "<?php print $ls_desorg?>">
            </td>
         
        </tr>
		<tr>
          <td width="138" height="22">&nbsp;</td>
          
          <td height="22" colspan="6"><input name="operacion" type="hidden" id="operacion"></td>
        </tr>
       <tr>  	
		<tr>
		<td height="22"><div align="right"></div></td>
		<td height="22" colspan="2"></td>

	    <td width="135" height="22" align="right"><a href="javascript: Limpiar_Datos();"><img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar Datos</a> <a href="javascript: Consultar();"></a></td>
	    
	    <td width="238"><a href="javascript: Consultar();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Consultar Datos</a></td>
	   
		</tr>
		 <tr>
		 <td colspan="6">
          <div align="center">
			<?php
				$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
				unset($io_grid);
				
			?>
			  </div>
		  </td>
        </tr>
      </table>	 
      <p>&nbsp;</p>
     
 </td> 
</table>

</form>


</body>


</html>


