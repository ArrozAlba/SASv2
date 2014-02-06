<?php
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("../../../class_folder/utilidades/class_funciones_srh.php");
$io_fun_srh=new class_funciones_srh('../../../../');
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_requerimiento_cargo.php",$ls_permisos,$la_seguridad,$la_permisos);
require_once("../../../class_folder/utilidades/class_funciones_nomina.php");
		$io_fun_nomina=new class_funciones_nomina();

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 27/11/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		

   		global $ls_codcar,$ls_descar,$ls_codnom, $ls_desnom,$ls_activarcodigo,$ls_operacion,$ls_guardar,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_codcar="";
		$ls_descar="";
		$ls_codnom="";
		$ls_desnom="";
		$ls_guardar="";
		$ls_activarcodigo="";
		$ls_titletable="Requerimientos de Cargo";
		$li_widthtable=550;
		$ls_nametable="grid";
		$lo_title[1]="Codigo Tipo de Requerimiento";
		$lo_title[2]="Tipo de Requerimiento";
		$lo_title[3]="Codigo Requerimiento";
		$lo_title[4]="Requerimiento";
		$lo_title[5]="Buscar";
		$lo_title[6]="Agregar";
		$lo_title[7]="Eliminar";
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtcodtipreq".$ai_totrows." type=text id=txtcodtipreq".$ai_totrows." class=sin-borde size=15 readonly >";
		$aa_object[$ai_totrows][2]="<input name=txtdentipreq".$ai_totrows." type=text id=txtdentipreq".$ai_totrows." class=sin-borde size=40  readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtcodreq".$ai_totrows." type=text id=txtcodreq".$ai_totrows." class=sin-borde size=15 readonly >";
		$aa_object[$ai_totrows][4]="<input name=txtdenreq".$ai_totrows." type=text id=txtdenreq".$ai_totrows." class=sin-borde size=40  readonly>";
		$aa_object[$ai_totrows][5]="<a href=javascript:catalogo_requerimiento(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.gif alt=Buscar width=15 height=15 border=0 align=center></a>";		
		$aa_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
		$aa_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";			
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_cargar_dt($li_i)
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 27/11/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codtipreq,$ls_dentipreq,$ls_codreq,$ls_denreq;

		$ls_codreq=$_POST["txtcodreq".$li_i];
		$ls_denreq=$_POST["txtdenreq".$li_i];
		$ls_dentipreq=$_POST["txtdentipreq".$li_i];
		$ls_codtipreq=$_POST["txtcodtipreq".$li_i];
		
   }
   //--------------------------------------------------------------

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Requerimientos por Cargo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
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

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_requerimiento_cargo.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>



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
.style1 {color: #EBEBEB}
.Estilo1 {
	color: #6699CC;
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
}
-->
</style>
</head>

<body >

<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_cargo.php");
	$io_cargo=new sigesp_srh_c_cargo("../../../../");
	require_once("../../../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;
			
		case "AGREGARDETALLE":
		 	$ls_codcar=$_POST["txtcodcar"];
			$ls_descar=$_POST["txtdescar"];
			$ls_codnom=$_POST["txtcodnom"];
			$ls_desnom=$_POST["txtdesnom"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows+1;			
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				uf_cargar_dt($li_i);
				$lo_object[$li_i][1]="<input name=txtcodtipreq".$li_i." type=text id=txtcodtipreq".$li_i." class=sin-borde size=15 value=".$ls_codtipreq." readonly >";
				$lo_object[$li_i][2]="<input name=txtdentipreq".$li_i." type=text id=txtdentipreq".$li_i." class=sin-borde size=40 value=".$ls_dentipreq." readonly>";
				$lo_object[$li_i][3]="<input name=txtcodreq".$li_i." type=text id=txtcodreq".$li_i." class=sin-borde size=15 value=".$ls_codreq." readonly >";
				$lo_object[$li_i][4]="<input name=txtdenreq".$li_i." type=text id=txtdenreq".$li_i." class=sin-borde size=40 value=".$ls_denreq."  readonly>";
				$lo_object[$li_i][5]="<a href=javascript:catalogo_requerimiento(".$li_i.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.gif alt=Buscar width=15 height=15 border=0 align=center></a>";		
				$lo_object[$li_i][6]="<a href=javascript:uf_agregar_dt(".$li_i.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object[$li_i][7]="<a href=javascript:uf_delete_dt(".$li_i.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";
			
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARDETALLE":
		 	$ls_codcar=$_POST["txtcodcar"];
			$ls_descar=$_POST["txtdescar"];
			$ls_codnom=$_POST["txtcodnom"];
			$ls_desnom=$_POST["txtdesnom"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=($li_rowdelete))
				{		
					$li_temp++;			
					uf_cargar_dt($li_i);
					$lo_object[$li_temp][1]="<input name=txtcodtipreq".$li_temp." type=text id=txtcodtipreq".$li_temp." class=sin-borde size=15 value=".$ls_codtipreq." readonly >";
					$lo_object[$li_temp][2]="<input name=txtdentipreq".$li_temp." type=text id=txtdentipreq".$li_temp." class=sin-borde size=40 value=".$ls_dentipreq." readonly>";
					$lo_object[$li_temp][3]="<input name=txtcodreq".$li_temp." type=text id=txtcodreq".$li_temp." class=sin-borde size=15 value=".$ls_codreq." readonly >";
					$lo_object[$li_temp][4]="<input name=txtdenreq".$li_temp." type=text id=txtdenreq".$li_temp." class=sin-borde size=40 value=".$ls_denreq."  readonly>";
					$lo_object[$li_temp][5]="<a href=javascript:catalogo_requerimiento(".$li_temp.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.gif alt=Buscar width=15 height=15 border=0 align=center></a>";		
					$lo_object[$li_temp][6]="<a href=javascript:uf_agregar_dt(".$li_temp.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
					$lo_object[$li_temp][7]="<a href=javascript:uf_delete_dt(".$li_temp.");    align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";
				
					
				}
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
			
		case "BUSCARDETALLE":
		 	$ls_codcar=$_POST["txtcodcar"];
			$ls_codcar=$_POST["txtcodcar"];
			$ls_descar=$_POST["txtdescar"];
			$ls_codnom=$_POST["txtcodnom"];
			$ls_desnom=$_POST["txtdesnom"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_cargo->uf_srh_load_requerimiento_cargo_campos($ls_codcar,$li_totrows,$lo_object);
			$li_totrows++;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
	}

?>





<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../../../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Recursos Humanos</td>
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
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../../../../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../../../../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../../../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../../../../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
 <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
 <table width="600" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
 	 <table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
 	 <tr>
    <td height="20" colspan="3" class="titulo-celdanew">Definici&oacute;n de Requerimientos por Cargo</td>
  </tr>
  <tr class="formato-blanco">
    <td width="134" height="19">&nbsp;</td>
    <td colspan="2"><div id="resultado" ></div></td>
  </tr>
  <tr class="formato-blanco">
  
   <tr class="formato-blanco">
    <td height="28"><div align="right">C&oacute;digo Cargo</div></td>
  <td colspan="3"><div align="left">
            <input name="txtcodcar" type="text" id="txtcodcar"   size="16"  style="text-align:center" value="<?php print $ls_codcar?>"   readonly> 
			<a href="javascript:catalogo_cargo();"><img src="../../../../shared/imagebank/tools15/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip">Buscar Cargo</a>
			<a href="javascript:catalogo_cargo_rac();"><img src="../../../../shared/imagebank/tools15/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip">Buscar Cargo (RAC)</a>
			 </div>
			 
			 </td>
  </tr>
  
  <tr>
        <td height="22"><div align="right">Denominaci&oacute;n Cargo</div></td>
		<td colspan="3"><div align="left">
             
            <input name="txtdescar" type="text" id="txtdescar"  value="<?php print $ls_descar?>"  style="text-align:justify" size="60"  readonly >
        </div></td>
      </tr>
  
    
  
    <tr class="formato-blanco">
    <td width="134" height="28" align="right">C&oacute;digo N&oacute;mina</td>
    <td width="426"  height="28" valign="middle"><input name="txtcodnom"  type="text" id="txtcodnom"  size="16" maxlength="15" style="text-align:center" value="<?php print $ls_codnom?>" readonly>
      <input name="txtdesnom" type="text" class="sin-borde" id="txtdesnom" value="<?php print $ls_desnom?>" size="50" maxlength="59" readonly>          </td>
        <td width="6">&nbsp;</td>
  </tr>
  <tr>
          <td><div align="right"></div></td>
          <td><input name="operacion" type="hidden" id="operacion">
		  
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
            <input name="hidstatus" type="hidden" id="hidstatus"></td>
        </tr>
 
        <tr>
          <td colspan="2">
		  	<div align="center">
			<?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
			  </div>
		  	<p>
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
              <input name="filadelete" type="hidden" id="filadelete">
			  <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
			  <input name="hidcontrol" type="hidden" id="hidcontrol" value="1">
			  <input name="hidcontrolcar" type="hidden" id="hidcontrolcar" value="">
			</p>			</td>		  
          </tr>
      </table>
    <tr>
		  <td  >&nbsp;</td> 
		  </tr>
		   <tr>
		  <td  >&nbsp;</td> 
		  </tr>
  </table>
</div>
<div align="center"></div>
<p align="center" class="style1" id="mostrar" style="font:#EBEBEB" ></p>
</body>

</html>
