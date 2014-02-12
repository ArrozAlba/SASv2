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
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_archivostxtprestamos.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codarch,$ls_denarch,$ls_tiparch, $la_tiparch, $ls_acumon, $ls_activarcodigo,$ls_activo,
		       $ls_operacion,$ls_mostrar,$ls_existe,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_codarch="";
		$ls_denarch="";
		$ls_tiparch="";
		$la_tiparch[0]="";
		$la_tiparch[1]="";		
		$ls_acumon="";
		$ls_mostrar="display:none";
		$ls_activarcodigo="";
		$ls_activo="";
		$ls_titletable="Campos TXT";
		$li_widthtable=500;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Descripcion";
		$lo_title[3]="Inicio";
		$lo_title[4]="Longitud";		
		$lo_title[5]="Tipo";
		$lo_title[6]="Tabla";
		$lo_title[7]="Item";
		$lo_title[8]=" ";
		$lo_title[9]=" ";
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
		$aa_object[$ai_totrows][1]="<input name=txtcodcam".$ai_totrows." type=text id=txtcodcam".$ai_totrows." class=sin-borde size=3 maxlength=2 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][2]="<input name=txtdescam".$ai_totrows." type=text id=txtdescam".$ai_totrows." class=sin-borde size=30 maxlength=20 onKeyUp='javascript: ue_validarcomillas(this);'>";
		$aa_object[$ai_totrows][3]="<input name=txtinicam".$ai_totrows." type=text id=txtinicam".$ai_totrows." class=sin-borde size=8 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][4]="<input name=txtloncam".$ai_totrows." type=text id=txtloncam".$ai_totrows." class=sin-borde size=8 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";		
		$aa_object[$ai_totrows][5]="<select name=cmbtipcam".$ai_totrows." id=cmbtipcam".$ai_totrows."><option value='C'>Caracter</option><option value='N'>Numerico</option><option value='E' ".$la_tipcam[2].">Numerico Entero</option></select>";		
		$aa_object[$ai_totrows][6]="<select name=cmbtabrelcam".$ai_totrows." id=cmbtabrelcam".$ai_totrows."><option value='sno_prestamos'>Prestamos</option></select>";
		$aa_object[$ai_totrows][7]="<select name=cmbiterelcam".$ai_totrows." id=cmbiterelcam".$ai_totrows."> ".
								   "<option value='codper'>Código Personal</option> ".
		                           "<option value='codtippre'>Tipo Prestamos</option> ".
								    "<option value='codconc'>Codigo Concepto</option> ".
									"<option value='monpre'>Monto Prestamo</option> ".									
									"<option value='numcuopre'>Número Cuotas</option>".
									"<option value='fecpre'>Fecha Prestamo (aaaammdd)</option>".
									"<option value='obsrecpre'>Observacion Prestamo</option>".
									"<option value='stapre'>Estatus del Pestamos</option></select>";		
		$aa_object[$ai_totrows][8]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif title=Agregar alt=Aceptar width=15 height=15 border=0></a>";
		$aa_object[$ai_totrows][9]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif title=Eliminar alt=Eliminar width=15 height=15 border=0></a>";			
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
   		global $li_codcam,$ls_descam,$li_inicam,$li_loncam,$ls_edicam,$la_edicam,$ls_clacam,$la_clacam, $ls_actcam,$la_actcam;		
		global $ls_tabrelcam,$ls_iterelcam,$la_iterelcam,$ls_cricam,$ls_tipcam,$la_tipcam;

		$li_codcam=$_POST["txtcodcam".$li_i];
		$ls_descam=$_POST["txtdescam".$li_i];
		$li_inicam=$_POST["txtinicam".$li_i];
		$li_loncam=$_POST["txtloncam".$li_i];		
		
		$ls_edicam="0";
		$ls_clacam="0";
		$ls_actcam="N";
		$ls_cricam="";
		
		$ls_tabrelcam=$_POST["cmbtabrelcam".$li_i];
		$ls_iterelcam=$_POST["cmbiterelcam".$li_i];
		$la_iterelcam[0]="";
		$la_iterelcam[1]="";
		$la_iterelcam[2]="";
		$la_iterelcam[3]="";
		$la_iterelcam[4]="";
		$la_iterelcam[5]="";
		$la_iterelcam[6]="";
		$la_iterelcam[7]="";
		$la_tipcam[0]="";
		$la_tipcam[1]="";
		$la_tipcam[2]="";
		$ls_tipcam=$_POST["cmbtipcam".$li_i];
		switch($ls_tipcam)
		{
			case "C":
				$la_tipcam[0]="selected";
				break;
			case "N":
				$la_tipcam[1]="selected";
				break;
			case "E":
				$la_tipcam[2]="selected";
				break;
		}		
		switch($ls_iterelcam)
		{
			case "codper":
				$la_iterelcam[0]="selected";
				break;
			case "codtippre":
				$la_iterelcam[1]="selected";
				break;
			case "codconc":
				$la_iterelcam[2]="selected";
				break;
			case "monpre":
				$la_iterelcam[3]="selected";
				break;
			case "numcuopre":
				$la_iterelcam[4]="selected";
				break;
			case "fecpre":
				$la_iterelcam[5]="selected";
				break;
			case "obsrecpre":
				$la_iterelcam[6]="selected";
				break;
			case "stapre":
				$la_iterelcam[7]="selected";
				break;		
		}
		
	
		
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title >Definici&oacute;n de Archivos TXT  para Pr&eacute;stamos</title>
<meta http-equiv="imagetoolbar" content="no"> 
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
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_archivotxtprestamos.php");
	$io_archivo=new sigesp_snorh_c_archivotxtprestamos();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	$ls_codarch=$io_archivo->uf_nuevo_codigo();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
		break;
		
		
		case "GUARDAR":
		 	$ls_codarch=$_POST["txtcodarch"];
			$ls_denarch=$_POST["txtdenarch"];			
			$io_archivo->io_sql->begin_transaction();			
			$lb_valido=$io_archivo->uf_guardar($ls_existe,$ls_codarch,$ls_denarch,$la_seguridad);
			if($lb_valido)
			{
				$lb_valido=$io_archivo->uf_delete_campos($ls_codarch,$la_seguridad);
				for($li_i=1;($li_i<$li_totrows)&&($lb_valido);$li_i++)
				{
					uf_cargar_dt($li_i);
					$lb_valido=$io_archivo->uf_insert_archivotxt_campos($ls_codarch,$li_codcam,$ls_descam,$li_inicam,$li_loncam,$ls_edicam,$ls_clacam,$ls_actcam,
																		$ls_tabrelcam,$ls_iterelcam,$ls_cricam,$ls_tipcam,$la_seguridad);
				}
			}
			if($lb_valido)
			{
				$io_archivo->io_sql->commit();
				if($ls_existe=="TRUE")
				{
					$io_archivo->io_mensajes->message("El archivo txt fue Actualizado.");
				}
				else
				{
					$io_archivo->io_mensajes->message("El archivo txt fue Registrado.");
				}
			}
			else
			{
				$io_archivo->io_sql->rollback();
				$io_archivo->io_mensajes->message("Ocurrio un error al guardar el archivo txt.");
			}
			uf_limpiarvariables();
			$ls_codarch=$io_archivo->uf_nuevo_codigo();
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			$ls_existe="FALSE";
			break;

		case "ELIMINAR":
			$ls_codarch=$_POST["txtcodarch"];
			$lb_valido=$io_archivo->uf_delete_archivotxt($ls_codarch,$la_seguridad);
			uf_limpiarvariables();
			$ls_existe="FALSE";
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			$ls_codarch=$io_archivo->uf_nuevo_codigo();
			break;

		case "AGREGARDETALLE":
		 	$ls_codarch=$_POST["txtcodarch"];
			$ls_denarch=$_POST["txtdenarch"];			
			$li_totrows=$li_totrows+1;			
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				uf_cargar_dt($li_i);
				
				$lo_object[$li_i][1]="<input name=txtcodcam".$li_i." type=text id=txtcodcam".$li_i." class=sin-borde size=3 maxlength=2 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_codcam."'>";
				$lo_object[$li_i][2]="<input name=txtdescam".$li_i." type=text id=txtdescam".$li_i." class=sin-borde size=30 maxlength=20 onKeyUp='javascript: ue_validarcomillas(this);' value='".$ls_descam."'>";
				$lo_object[$li_i][3]="<input name=txtinicam".$li_i." type=text id=txtinicam".$li_i." class=sin-borde size=8 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_inicam."'>";
				$lo_object[$li_i][4]="<input name=txtloncam".$li_i." type=text id=txtloncam".$li_i." class=sin-borde size=8 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_loncam."'>";		
				$lo_object[$li_i][5]="<select name=cmbtipcam".$li_i." id=cmbtipcam".$li_i."><option value='C' ".$la_tipcam[0].">Caracter</option><option value='N' ".$la_tipcam[1].">Numerico</option><option value='E' ".$la_tipcam[2].">Numerico Entero</option></select>";		
				$lo_object[$li_i][6]="<select name=cmbtabrelcam".$li_i." id=cmbtabrelcam".$li_i."><option value='sno_prestamos'>Prestamos</option></select>";
				$lo_object[$li_i][7]="<select name=cmbiterelcam".$li_i." id=cmbiterelcam".$li_i."> ".
								   "<option value='codper'  ".$la_iterelcam[0].">Código Personal</option> ".
										   "<option value='codtippre'  ".$la_iterelcam[1].">Tipo Prestamos</option> ".
											"<option value='codconc'  ".$la_iterelcam[2].">Codigo Concepto</option> ".
											"<option value='monpre'  ".$la_iterelcam[3].">Monto Prestamo</option> ".										
											"<option value='numcuopre'  ".$la_iterelcam[4].">Número Cuotas</option>".
											"<option value='fecpre'  ".$la_iterelcam[5].">Fecha Prestamo (aaaammdd)</option>".
											"<option value='obsrecpre'  ".$la_iterelcam[6].">Observacion Prestamo</option>".
											"<option value='stapre'  ".$la_iterelcam[7].">Estatus del Pestamos</option></select>";		
				$lo_object[$li_i][8]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=../shared/imagebank/tools15/aprobado.gif title=Agregar alt=Aceptar width=15 height=15 border=0></a>";
				$lo_object[$li_i][9]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/deshacer.gif title=Eliminar alt=Eliminar width=15 height=15 border=0></a>";			
			}
			uf_agregarlineablanca($lo_object,$li_totrows);			
			break;

		case "ELIMINARDETALLE":
		 	$ls_codarch=$_POST["txtcodarch"];
			$ls_denarch=$_POST["txtdenarch"];
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp++;			
					uf_cargar_dt($li_i);
					$lo_object[$li_temp][1]="<input name=txtcodcam".$li_temp." type=text id=txtcodcam".$li_temp." class=sin-borde size=3 maxlength=2 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_codcam."'>";
					$lo_object[$li_temp][2]="<input name=txtdescam".$li_temp." type=text id=txtdescam".$li_temp." class=sin-borde size=30 maxlength=20 onKeyUp='javascript: ue_validarcomillas(this);'  value='".$ls_descam."'>";
					$lo_object[$li_temp][3]="<input name=txtinicam".$li_temp." type=text id=txtinicam".$li_temp." class=sin-borde size=8 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'  value='".$li_inicam."'>";
					$lo_object[$li_temp][4]="<input name=txtloncam".$li_temp." type=text id=txtloncam".$li_temp." class=sin-borde size=8 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);' value='".$li_loncam."'>";		
					$lo_object[$li_temp][5]="<select name=cmbtipcam".$li_temp." id=cmbtipcam".$li_temp."><option value='C' ".$la_tipcam[0].">Caracter</option><option value='N' ".$la_tipcam[1].">Numerico</option><option value='E' ".$la_tipcam[2].">Numerico Entero</option></select>";		
					$lo_object[$li_temp][6]="<select name=cmbtabrelcam".$li_temp." id=cmbtabrelcam".$li_temp."><option value='sno_prestamos'>Prestamos</option></select>";
					$lo_object[$li_temp][7]="<select name=cmbiterelcam".$li_temp." id=cmbiterelcam".$li_temp."> ".
									  "<option value='codper'  ".$la_iterelcam[0].">Código Personal</option> ".
										   "<option value='codtippre'  ".$la_iterelcam[1].">Tipo Prestamos</option> ".
											"<option value='codconc'  ".$la_iterelcam[2].">Codigo Concepto</option> ".
											"<option value='monpre'  ".$la_iterelcam[3].">Monto Prestamo</option> ".										
											"<option value='numcuopre'  ".$la_iterelcam[4].">Número Cuotas</option>".
											"<option value='fecpre'  ".$la_iterelcam[5].">Fecha Prestamo (aaaammdd)</option>".
											"<option value='obsrecpre'  ".$la_iterelcam[6].">Observacion Prestamo</option>".
											"<option value='stapre'  ".$la_iterelcam[7].">Estatus del Pestamos</option></select>";		
					$lo_object[$li_temp][8]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.gif title=Agregar alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_temp][9]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/deshacer.gif title=Eliminar alt=Eliminar width=15 height=15 border=0></a>";				
				}
			}
			uf_agregarlineablanca($lo_object,$li_totrows);			
			break;
			
		case "BUSCARDETALLE":
		 	$ls_codarch=$_POST["txtcodarch"];
			$ls_denarch=$_POST["txtdenarch"];
			$lb_valido=$io_archivo->uf_load_archivotxt_campos($ls_codarch,$li_totrows,$lo_object);
			$li_totrows++;
			uf_agregarlineablanca($lo_object,$li_totrows);			
			break;
	}
	$io_archivo->uf_destructor();
	unset($io_archivo);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Nómina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
        </table>
	 </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
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
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Definici&oacute;n de Archivos TXT para Pr&eacute;stamos</td>
        </tr>
        <tr>
          <td width="157" height="22">&nbsp;</td>
          <td width="387">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td><div align="left">
            <input name="txtcodarch" type="text" id="txtcodarch" size="6" maxlength="4" value="<?php print $ls_codarch;?>" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,4);" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Denominaci&oacute;n</div></td>
          <td><div align="left">
            <input name="txtdenarch" type="text" id="txtdenarch" value="<?php print $ls_denarch;?>" size="60" maxlength="120" onKeyUp="ue_validarcomillas(this);">
          </div></td>
          </tr>
        <tr>
        <tr>
          <td><div align="right"></div></td>
          <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
        <tr>
          <td colspan="2">
		  	<div align="center" id="grid" >
			<?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
			  </div>
		  	<p>
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
              <input name="filadelete" type="hidden" id="filadelete">
			</p>			</td>		  
          </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
</body>
<script language="javascript">

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f=document.form1;
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";		
		f.action="sigesp_snorh_d_archivostxtprestamos.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		ls_codarch=ue_validarvacio(f.txtcodarch.value);
		ls_denarch=ue_validarvacio(f.txtdenarch.value);
		
		li_total=f.totalfilas.value;
		if ((ls_codarch=="")||(ls_codarch=="")||(li_total=="0"))
		{
			alert("Debe llenar todos los datos.");
		}
		else
		{
		
			f.operacion.value="GUARDAR";
			f.action="sigesp_snorh_d_archivostxtprestamos.php";
			f.submit();
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			ls_codarch = ue_validarvacio(f.txtcodarch.value);
			if (ls_codarch=="")
			{
				alert("Debe buscar el registro a eliminar.");
			}
			else
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_snorh_d_archivostxtprestamos.php";
					f.submit();
				}
			}
		}
		else
		{
			alert("Debe buscar el registro a eliminar.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_snorh_cat_archivotxtprestamos.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
		
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	if(li_total==li_row)
	{
		li_codcamnew=eval("f.txtcodcam"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			li_codcam=eval("f.txtcodcam"+li_i+".value");
			if((li_codcam==li_codcamnew)&&(li_i!=li_row))
			{
				alert("el campo ya existe");
				lb_valido=true;
			}
		}
		ls_codarch=ue_validarvacio(f.txtcodarch.value);
		ls_denarchc=ue_validarvacio(f.txtdenarch.value);
		li_codcam=eval("f.txtcodcam"+li_row+".value");
		li_codcam=ue_validarvacio(li_codcam);
		ls_descam=eval("f.txtdescam"+li_row+".value");
		ls_descam=ue_validarvacio(ls_descam);
		li_inicam=eval("f.txtinicam"+li_row+".value");
		li_inicam=ue_validarvacio(li_inicam);
		li_loncam=eval("f.txtloncam"+li_row+".value");
		li_loncam=ue_validarvacio(li_loncam);
		if((ls_codarch=="")||(ls_denarchc=="")|(li_codcam=="")||(ls_descam=="")||(li_inicam=="")||(li_loncam==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_snorh_d_archivostxtprestamos.php";
			f.submit();
		}
	}
}

function uf_delete_dt(li_row)
{
	f=document.form1;
	li_total=f.totalfilas.value;
	if(li_total>li_row)
	{
		li_codcam=eval("f.txtcodcam"+li_row+".value");
		li_codcam=ue_validarvacio(li_codcam);
		if(li_codcam=="")
		{
			alert("la fila a eliminar no debe estar vacio el lapso");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_snorh_d_archivostxtprestamos.php";
				f.submit();
			}
		}
	}
}




</script> 
</html>