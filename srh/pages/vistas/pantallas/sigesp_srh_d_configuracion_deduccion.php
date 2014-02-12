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
$io_fun_srh=new class_funciones_srh('../../../../');
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_configuracion_deduccion.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		
   		global $ls_codtipded,$ls_dentipded,$ls_activarcodigo,$ls_operacion,$ls_guardar, $punto, $coma,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
		$ls_codtipded="";	
		$ls_dentipded="";	
		$ls_guardar="";
		$ls_activarcodigo="";
		$ls_titletable="Detalles de Deducciones de Seguro";
		$li_widthtable=600;
		$ls_nametable="grid";
		$ls_titletableseguro="Items de la Entrevista Técnica";
		$li_widthtableseguro=600;
		$ls_nametableseguro="gridseguro";
		$lo_title[1]="Titular";
		$lo_title[2]="Sueldo";
		$lo_title[3]="Edad Mínima";
		$lo_title[4]="Edad Máxima";
		$lo_title[5]="Género";
		$lo_title[6]="HCM";
		$lo_title[7]="Nexo Familiar";
		$lo_title[8]="Valor Prima";
		$lo_title[9]="% Aporte Empresa";
		$lo_title[10]="% Aporte Empleado";
		$lo_title[11]="Agregar";
		$lo_title[12]="Eliminar";
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
		$punto='"."';
		$coma='","';
		$aa_object[$ai_totrows][1]="<select name=cmbtitular".$ai_totrows." id=cmbtitular".$ai_totrows." onChange='javascript:chequear_titular(this,".$ai_totrows.");'><option value=''>--Seleccione--</option><option value='S' >Si</option><option value='N' >No</option></select>";
		$aa_object[$ai_totrows][2]="<input name=txtsueldo".$ai_totrows." type=text id=txtsueldo".$ai_totrows." class=sin-borde size=14 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))' style='text-align:center'>";
		$aa_object[$ai_totrows][3]="<input name=txtedadmin".$ai_totrows." type=text id=txtedadmin".$ai_totrows." class=sin-borde maxlength=2 size=8 onKeyUp='javascript: ue_validarnumero(this);' style='text-align:center'>";		
		$aa_object[$ai_totrows][4]="<input name=txtedadmax".$ai_totrows." type=text id=txtedadmax".$ai_totrows." class=sin-borde maxlength=3 size=8 onKeyUp='javascript: ue_validarnumero(this);' style='text-align:center' onChange='javascript:valida_edad(this,txtedadmin".$ai_totrows.")';>";
		$aa_object[$ai_totrows][5]="<select name=cmbsexper".$ai_totrows." id=cmbsexper".$ai_totrows."><option value=''>--Seleccione--</option><option value='F' > Femenino</option>
		               <option value='M' > Masculino</option></select>";
		$aa_object[$ai_totrows][6]=" <select name=cmbhcm".$ai_totrows." id=cmbhcm".$ai_totrows." >	
		    	<option value='1'>Si</option>
				<option value='0'selected >No</option></select> ";
		$aa_object[$ai_totrows][7]="<select name=cmbnexfam".$ai_totrows." id=cmbnexfam".$ai_totrows.">
              <option value='' selected>--Seleccione--</option>
              <option value='C' >Conyuge</option>
              <option value='H' >Hijo</option>
              <option value='P' >Progenitor</option>
              <option value='E' >Hermano</option>
            </select>";
		$aa_object[$ai_totrows][8]="<input name=txtprima".$ai_totrows." type=text id=txtprima".$ai_totrows." class=sin-borde size=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))'  >";
		$aa_object[$ai_totrows][9]="<input name=txtaporempre".$ai_totrows." type=text id=txtaporempre".$ai_totrows." class=sin-borde size=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))' >";
		$aa_object[$ai_totrows][10]="<input name=txtaporemple".$ai_totrows." type=text id=txtaporemple".$ai_totrows." class=sin-borde size=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))' >";
		$aa_object[$ai_totrows][11]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
		$aa_object[$ai_totrows][12]="<a href=javascript:uf_delete_dt(".$ai_totrows.");   align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";
	
   }
   //--------------------------------------------------------------

 //--------------------------------------------------------------
  function uf_cargar_dt($li_i)
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_titular,$ls_sueldo,$ls_edadmin, $ls_edadmax,$ls_sexo, $la_sexo, $ls_hcm, $ls_nexo, $la_nexo, $li_prima, $li_aporempre, $li_aporemple, $la_titular, $la_hcm, $ls_disable;
		$ls_titular=$_POST["cmbtitular".$li_i];
		$la_titular[0]="";
		$la_titular[1]="";
		$ls_disable="";
		$ls_sueldo=$_POST["txtsueldo".$li_i];
		$ls_edadmin=$_POST["txtedadmin".$li_i];
		$ls_edadmax=$_POST["txtedadmax".$li_i];
		$ls_sexo=$_POST["cmbsexper".$li_i];
		$la_sexo[0]="";
		$la_sexo[1]="";
		
		
		if (array_key_exists("cmbhcm".$li_i,$_POST))
		{
		 	$ls_hcm=$_POST["cmbhcm".$li_i];
			$la_hcm[0]="";
			$la_hcm[1]="";
		}
		else
		{
			$ls_hcm="";
			$la_hcm[0]="";
			$la_hcm[1]="";
		}
		
		if (array_key_exists("cmbnexfam".$li_i,$_POST))
		{
		 	$ls_nexo=$_POST["cmbnexfam".$li_i];
			$la_nexo[0]="";
			$la_nexo[1]="";
			$la_nexo[2]="";
			$la_nexo[3]="";
		}
		else
		{
			$ls_nexo="";
			$la_nexo[0]="";
			$la_nexo[1]="";
			$la_nexo[2]="";
			$la_nexo[3]="";
		}
			
		
		
		$li_prima=$_POST["txtprima".$li_i];
		$li_aporempre=$_POST["txtaporempre".$li_i];
		$li_aporemple=$_POST["txtaporemple".$li_i];
	   switch($ls_sexo)
		{
			case "F":
				$la_sexo[0]="selected";
				break;
			case "M":
				$la_sexo[1]="selected";
				break;
		}
	   switch($ls_nexo)
		{
			case "C":
				$la_nexo[0]="selected";
				break;
			case "H":
				$la_nexo[1]="selected";
				break;
			case "P":
				$la_nexo[2]="selected";
			  	break;
			case "E":
				$la_nexo[3]="selected";
				break;
		}
	    switch($ls_titular)
		{
			case "S":
				$la_titular[0]="selected";
				$ls_disable="disabled";
				break;
			case "N":
				$la_titular[1]="selected";
				$ls_disable="";
				break;
		}
		switch($ls_hcm)
		{
			case "1":
				$la_hcm[0]="selected";
				break;
			case "0":
				$la_hcm[1]="selected";
				break;
		}
		
			
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
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_configuracion_deduccion.js"></script>



</head>


<body>
<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_tipodeduccion.php");
	$io_deduccion=new sigesp_srh_c_tipodeduccion("../../../../");
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
		    $ls_codtipded=$_POST["txtcodtipded"];
			$ls_dentipded=$_POST["txtdentipded"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$punto='"."';
			$coma='","';
			$li_totrows=$li_totrows+1;			
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				uf_cargar_dt($li_i);				 			
					
				$lo_object[$li_i][1]="<select name=cmbtitular".$li_i." id=cmbtitular".$li_i." onChange='javascript:chequear_titular(this,".$li_i.");'><option value=''>--Seleccione--</option>
				<option value='S' ".$la_titular[0].">Si</option>
				<option value='N' ".$la_titular[1]." >No</option></select>";
				$lo_object[$li_i][2]="<input name=txtsueldo".$li_i." type=text id=txtsueldo".$li_i." class=sin-borde size=14 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))' style='text-align:center'  value='".$ls_sueldo."'>";
				$lo_object[$li_i][3]="<input name=txtedadmin".$li_i." type=text id=txtedadmin".$li_i." class=sin-borde maxlength=2 size=8 onKeyUp='javascript: ue_validarnumero(this);' style='text-align:center' value='".$ls_edadmin."'>";		
				$lo_object[$li_i][4]="<input name=txtedadmax".$li_i." type=text id=txtedadmax".$li_i." class=sin-borde maxlength=3 size=8 onKeyUp='javascript: ue_validarnumero(this);' style='text-align:center' onChange='javascript:valida_edad(this,txtedadmin".$li_i.")';  value='".$ls_edadmax."'>";
				$lo_object[$li_i][5]="<select name=cmbsexper".$li_i." id=cmbsexper".$li_i."><option value=''>--Seleccione--</option>       <option value='F' ".$la_sexo[0]."  > Femenino</option>
		        <option value='M' ".$la_sexo[1]." > Masculino</option></select>";
				$lo_object[$li_i][6]="  <select name=cmbhcm".$li_i." id=cmbhcm".$li_i." >		<option value='1' ".$la_hcm[0].">Si</option>
				<option value='0' ".$la_hcm[1]." >No</option></select> ";
				$lo_object[$li_i][7]="<select name=cmbnexfam".$li_i." id=cmbnexfam".$li_i."  ".$ls_disable.">
              <option value='' selected>--Seleccione--</option>
              <option value='C' ".$la_nexo[0]." >Conyuge</option>
              <option value='H' ".$la_nexo[1]."  >Hijo</option>
              <option value='P' ".$la_nexo[2]."  >Progenitor</option>
              <option value='E' ".$la_nexo[3]."  >Hermano</option>
            </select>";
			$lo_object[$li_i][8]="<input name=txtprima".$li_i." type=text id=txtprima".$li_i." class=sin-borde size=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))'  value='".$li_prima."'>";
			$lo_object[$li_i][9]="<input name=txtaporempre".$li_i." type=text id=txtaporempre".$li_i." class=sin-borde size=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))' value='".$li_aporempre."' >";
			$lo_object[$li_i][10]="<input name=txtaporemple".$li_i." type=text id=txtaporemple".$li_i." class=sin-borde size=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))' value='".$li_aporemple."'>";
			$lo_object[$li_i][11]="<a href=javascript:uf_agregar_dt(".$li_i.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
			$lo_object[$li_i][12]="<a href=javascript:uf_delete_dt(".$li_i.");   align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";
					
										
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARDETALLE":
		 	$ls_codtipded=$_POST["txtcodtipded"];
			$ls_dentipded=$_POST["txtdentipded"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$punto='"."';
		    $coma='","';
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=($li_rowdelete))
				{		
					$li_temp++;			
					uf_cargar_dt($li_i);
					
					
					$lo_object[$li_temp][1]="<select name=cmbtitular".$li_temp." id=cmbtitular".$li_temp." onChange='javascript:chequear_titular(this,".$li_temp.");'><option value=''>--Seleccione--</option>
				<option value='S' ".$la_titular[0].">Si</option>
				<option value='N' ".$la_titular[1]." >No</option></select>";
					$lo_object[$li_temp][2]="<input name=txtsueldo".$li_temp." type=text id=txtsueldo".$li_temp." class=sin-borde size=14 onKeyPress='return(ue_formatonumero(this,'.',',',event))' style='text-align:center' value='".$ls_sueldo."'>";
					$lo_object[$li_temp][3]="<input name=txtedadmin".$li_temp." type=text id=txtedadmin".$li_temp." class=sin-borde maxlength=2 size=8 onKeyUp='javascript: ue_validarnumero(this);' style='text-align:center' value='".$ls_edadmin."'>";		
					$lo_object[$li_temp][4]="<input name=txtedadmax".$li_temp." type=text id=txtedadmax".$li_temp." class=sin-borde maxlength=3 size=8 onKeyUp='javascript: ue_validarnumero(this);' style='text-align:center' onChange='javascript:valida_edad(this,txtedadmin".$li_temp.")';  value='".$ls_edadmax."'>";
					$lo_object[$li_temp][5]="<select name=cmbsexper".$li_temp." id=cmbsexper".$li_temp."><option value=''>--Seleccione--</option>       <option value='F' ".$la_sexo[0]."  > Femenino</option>
		        <option value='M' ".$la_sexo[1]." > Masculino</option></select>";
					$lo_object[$li_temp][6]="  <select name=cmbhcm".$li_temp." id=cmbhcm".$li_temp." >	
				<option value='1' ".$la_hcm[0].">Si</option>
				<option value='0' ".$la_hcm[1].">No</option></select> ";
				$lo_object[$li_temp][7]="<select name=cmbnexfam".$li_temp." id=cmbnexfam".$li_temp." ".$ls_disable.">
              	 <option value='' selected>--Seleccione--</option>
             	 <option value='C' ".$la_nexo[0]." >Conyuge</option>
             	 <option value='H' ".$la_nexo[1]."  >Hijo</option>
                 <option value='P' ".$la_nexo[2]."  >Progenitor</option>
                 <option value='E' ".$la_nexo[3]."  >Hermano</option>
                 </select>";
				$lo_object[$li_temp][8]="<input name=txtprima".$li_temp." type=text id=txtprima".$li_temp." class=sin-borde size=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))'  value='".$li_prima."'>";
				$lo_object[$li_temp][9]="<input name=txtaporempre".$li_temp." type=text id=txtaporempre".$li_temp." class=sin-borde size=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))' value='".$li_aporempre."' >";
				$lo_object[$li_temp][10]="<input name=txtaporemple".$li_temp." type=text id=txtaporemple".$li_temp." class=sin-borde size=8 onKeyPress='return(ue_formatonumero(this,".$punto.", ".$coma.",event))' value='".$li_aporemple."'>";
				$lo_object[$li_temp][11]="<a href=javascript:uf_agregar_dt(".$li_temp.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$lo_object[$li_temp][12]="<a href=javascript:uf_delete_dt(".$li_temp.");   align=center><img src=../../../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0 align=center></a>";
					
					
				}
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
			
		case "BUSCARDETALLE":
		 	$ls_codtipded=$_POST["txtcodtipded"];
			$ls_dentipded=$_POST["txtdentipded"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_deduccion->uf_srh_load_configuracion_deduccion($ls_codtipded,$li_totrows,$lo_object);
			$li_totrows++;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
			
			
		
	}
	
	unset($io_deduccion);
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
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../../../public/imagenes/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../../../public/imagenes/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../../../public/imagenes/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_eliminar();"><img src="../../../public/imagenes/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../../public/imagenes/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><img src="../../../public/imagenes/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>


<p>&nbsp;</p>
<form name="form1" method="post" action=""  ><div >


<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>

<table width="715" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr><td width="715" height="136">
  
  <table width="688" height="240" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco"> 
          <tr class="titulo-nuevo">        
       
          <td height="20" colspan="4" class="titulo-ventana">Configuraci&oacute;n de Deducci&oacute;n</td>
        </tr>
        <tr>
          <td width="185" height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
       
       <tr class="formato-blanco">
    <td height="28"><div align="right">Tipo Deducci&oacute;n</div></td>
    <td width="122" height="28" valign="middle"><input name="txtcodtipded" type="text" id="txtcodtipded"  value="<?php print $ls_codtipded ?>" size="16" maxlength="15" readonly style="text-align:center" >
      <a href="javascript:catalogo_tipo_deduccion();"><img src="../../../public/imagenes/buscar.gif" alt="Cat&aacute;logo de Tipo Deduccion" name="buscartip" width="15" height="15" border="0" id="buscartip"></a></td>
            <td width="375"> <input name="txtdentipded" onKeyUp="ue_validarcomillas(this);" type="text" class="sin-borde" id="txtdentipded" value="<?php print $ls_dentipded ?>" size="60" maxlength="80" readonly>
             </td>
  </tr>  
	 <tr>
          <td  colspan="9">
		  	<div align="center">
			<?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
		    </div>
		  	<p>
			  <input name="operacion" type="hidden" id="operacion">
              <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe; ?>">
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
              <input name="filadelete" type="hidden" id="filadelete">
			</p>			</td>			  
        </tr>
      <p>&nbsp;</p>      
</table>
</td>
</tr>
<tr><td>
<p>&nbsp;</p></td>
</tr>
</table>
<div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
  <p>
     <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">
    <input name="hidstatus" type="hidden" id="hidstatus">
     <input name="hidcontrol" type="hidden" id="hidcontrol" value="1">
  </p>
</form>
</body>
</html>
