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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_tablavacacion.php",$ls_permisos,$la_seguridad,$la_permisos);
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
   		global $ls_codtabvac,$ls_dentabvac,$ls_activarcodigo,$ls_adelantaquincena,$ls_desincorporarnomina;
		global $ls_adelantaretencion,$ls_bonoautomatico,$la_periodo,$li_totrows,$ls_operacion,$ls_existe,$io_fun_nomina;
		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre;
	 	$ls_codtabvac="";
		$ls_dentabvac="";
		$ls_activarcodigo="";
		$la_periodo[0]="";
		$la_periodo[1]="";
		$ls_adelantaquincena="";
		$ls_adelantaretencion="";
		$ls_bonoautomatico="";
		$ls_anoserpre="";
		$ls_titletable="Períodos";
		$li_widthtable=500;
		$ls_nametable="grid";
		$lo_title[1]="Lapso";
		$lo_title[2]="Días de Disfrute";
		$lo_title[3]="Días Adicionales de Disfrute";
		$lo_title[4]="Días de Bono";
		$lo_title[5]="Días Adicionales de Bono";
		$lo_title[6]=" ";
		$lo_title[7]=" ";
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
		$aa_object[$ai_totrows][1]="<input name=txtlappervac".$ai_totrows." type=text id=txlappervac".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][2]="<input name=txtdiadisvac".$ai_totrows." type=text id=txtdiadisvac".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][3]="<input name=txtdiaadidisvac".$ai_totrows." type=text id=txtdiaadidisvac".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][4]="<input name=txtdiabonvac".$ai_totrows." type=text id=txtdiabonvac".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][5]="<input name=txtdiaadibonvac".$ai_totrows." type=text id=txtdiaadibonvac".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif title=Agregar Detalle alt=Aceptar width=15 height=15 border=0></a>";
		$aa_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif title=Eliminar Detalle alt=Eliminar width=15 height=15 border=0></a>";			
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
<title >Definici&oacute;n de Tabla de Vacaciones</title>
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
	require_once("sigesp_snorh_c_tablavacacion.php");
	$io_tablavac=new sigesp_snorh_c_tablavacacion();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

		case "GUARDAR":
		 	$ls_codtabvac=$_POST["txtcodtabvac"];
			$ls_dentabvac=$_POST["txtdentabvac"];
			$ls_pertabvac=$io_fun_nomina->uf_obtenervalor("cmbpertabvac","");
		 	$li_adequitabvac=$io_fun_nomina->uf_obtenervalor("chkadequitabvac","0");
			$li_aderettabvac=$io_fun_nomina->uf_obtenervalor("chkaderettabvac","0");			
		 	$li_bonauttabvac=$io_fun_nomina->uf_obtenervalor("chkbonauttabvac","0");
		 	$li_anoserpre=$io_fun_nomina->uf_obtenervalor("chkanoserpre","0");
			$io_tablavac->io_sql->begin_transaction();
			$lb_valido=$io_tablavac->uf_guardar($ls_existe,$ls_codtabvac,$ls_dentabvac,$ls_pertabvac,$li_adequitabvac,$li_aderettabvac,
											 	$li_bonauttabvac,$li_anoserpre,$la_seguridad);
			if($lb_valido)
			{
				for($li_i=1;$li_i<$li_totrows&&$lb_valido;$li_i++)
				{
					$li_lappervac=$_POST["txtlappervac".$li_i];
					$li_diadisvac=$_POST["txtdiadisvac".$li_i];
					$li_diaadidisvac=$_POST["txtdiaadidisvac".$li_i];
					$li_diabonvac=$_POST["txtdiabonvac".$li_i];
					$li_diaadibonvac=$_POST["txtdiaadibonvac".$li_i];
					$lb_valido=$io_tablavac->uf_guardar_periodo($ls_codtabvac,$li_lappervac,$li_diadisvac,$li_diabonvac,$li_diaadidisvac,$li_diaadibonvac,$ls_existe,$la_seguridad);
				}
			}
			if($lb_valido)
			{
				$io_tablavac->io_sql->commit();
				if($ls_existe=="TRUE")
				{
					$io_tablavac->io_mensajes->message("La tabla de Vacación fue Actualizada.");
				}
				else
				{
					$io_tablavac->io_mensajes->message("La tabla de Vacación fue Registrada.");
				}
			}
			else
			{
				$io_tablavac->io_sql->rollback();
				$io_tablavac->io_mensajes->message("Ocurrio un error al guardar la tabla de vacaciones.");
			}
			uf_limpiarvariables();
			$ls_existe="FALSE";
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

		case "ELIMINAR":
			$ls_codtabvac=$_POST["txtcodtabvac"];
			$lb_valido=$io_tablavac->uf_delete_tablavacacion($ls_codtabvac,$la_seguridad);
			uf_limpiarvariables();
			$ls_existe="FALSE";
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

		case "AGREGARDETALLE":
		 	$ls_codtabvac=$_POST["txtcodtabvac"];
			$ls_dentabvac=$_POST["txtdentabvac"];
			$ls_activarcodigo="readOnly";
			$ls_pertabvac=$io_fun_nomina->uf_obtenervalor("cmbpertabvac","");
			$io_fun_nomina->uf_seleccionarcombo("0-1",$ls_pertabvac,$la_periodo,2);
		 	$li_adequitabvac=$io_fun_nomina->uf_obtenervalor("chkadequitabvac","0");
			$ls_adelantaquincena=$io_fun_nomina->uf_obtenervariable($li_adequitabvac,1,0,"checked","","");
			$li_aderettabvac=$io_fun_nomina->uf_obtenervalor("chkaderettabvac","0");			
			$ls_adelantaretencion=$io_fun_nomina->uf_obtenervariable($li_aderettabvac,1,0,"checked","","");
		 	$li_bonauttabvac=$io_fun_nomina->uf_obtenervalor("chkbonauttabvac","0");			
			$ls_bonoautomatico=$io_fun_nomina->uf_obtenervariable($li_bonauttabvac,1,0,"checked","","");
		 	$li_anoserpre=$io_fun_nomina->uf_obtenervalor("chkanoserpre","0");
			$ls_anoserpre=$io_fun_nomina->uf_obtenervariable($li_anoserpre,1,0,"checked","","");
			$li_totrows=$li_totrows+1;			
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$li_lappervac=$_POST["txtlappervac".$li_i];
				$li_diadisvac=$_POST["txtdiadisvac".$li_i];
				$li_diaadidisvac=$_POST["txtdiaadidisvac".$li_i];
				$li_diabonvac=$_POST["txtdiabonvac".$li_i];
				$li_diaadibonvac=$_POST["txtdiaadibonvac".$li_i];
				$lo_object[$li_i][1]="<input name=txtlappervac".$li_i." type=text id=txtlappervac".$li_i." class=sin-borde size=6 value='".$li_lappervac."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
				$lo_object[$li_i][2]="<input name=txtdiadisvac".$li_i." type=text id=txtdiadisvac".$li_i." class=sin-borde size=6 value='".$li_diadisvac."' onKeyUp='javascript: ue_validarnumero(this);'>";
				$lo_object[$li_i][3]="<input name=txtdiaadidisvac".$li_i." type=text id=txtdiaadidisvac".$li_i." class=sin-borde size=6 value='".$li_diaadidisvac."' onKeyUp='javascript: ue_validarnumero(this);'>";
				$lo_object[$li_i][4]="<input name=txtdiabonvac".$li_i." type=text id=txtdiabonvac".$li_i." class=sin-borde size=6 value='".$li_diabonvac."' onKeyUp='javascript: ue_validarnumero(this);'> ";
				$lo_object[$li_i][5]="<input name=txtdiaadibonvac".$li_i." type=text id=txtdiaadibonvac".$li_i." class=sin-borde size=6 value='".$li_diaadibonvac."' onKeyUp='javascript: ue_validarnumero(this);'> ";
				$lo_object[$li_i][6]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=../shared/imagebank/tools15/aprobado.gif title=Agregar Detalle alt=Aceptar width=15 height=15 border=0></a>";
				$lo_object[$li_i][7]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/deshacer.gif title=Eliminar Detalle alt=Aceptar width=15 height=15 border=0></a>";
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARDETALLE":
		 	$ls_codtabvac=$_POST["txtcodtabvac"];
			$ls_dentabvac=$_POST["txtdentabvac"];
			$ls_activarcodigo="readOnly";
			$ls_pertabvac=$io_fun_nomina->uf_obtenervalor("cmbpertabvac","");
			$io_fun_nomina->uf_seleccionarcombo("0-1",$ls_pertabvac,$la_periodo,2);
		 	$li_adequitabvac=$io_fun_nomina->uf_obtenervalor("chkadequitabvac","0");
			$ls_adelantaquincena=$io_fun_nomina->uf_obtenervariable($li_adequitabvac,1,0,"checked","","");
			$li_aderettabvac=$io_fun_nomina->uf_obtenervalor("chkaderettabvac","0");			
			$ls_adelantaretencion=$io_fun_nomina->uf_obtenervariable($li_aderettabvac,1,0,"checked","","");
		 	$li_bonauttabvac=$io_fun_nomina->uf_obtenervalor("chkbonauttabvac","0");			
			$ls_bonoautomatico=$io_fun_nomina->uf_obtenervariable($li_bonauttabvac,1,0,"checked","","");
		 	$li_anoserpre=$io_fun_nomina->uf_obtenervalor("chkanoserpre","0");
			$ls_anoserpre=$io_fun_nomina->uf_obtenervariable($li_anoserpre,1,0,"checked","","");
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp=$li_temp+1;			
					$li_lappervac=$_POST["txtlappervac".$li_i];
					$li_diadisvac=$_POST["txtdiadisvac".$li_i];
					$li_diaadidisvac=$_POST["txtdiaadidisvac".$li_i];
					$li_diabonvac=$_POST["txtdiabonvac".$li_i];
					$li_diaadibonvac=$_POST["txtdiaadibonvac".$li_i];
					$lo_object[$li_temp][1]="<input name=txtlappervac".$li_temp." type=text id=txtlappervac".$li_temp." class=sin-borde size=6 value='".$li_lappervac."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
					$lo_object[$li_temp][2]="<input name=txtdiadisvac".$li_temp." type=text id=txtdiadisvac".$li_temp." class=sin-borde size=6 value='".$li_diadisvac."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_temp][3]="<input name=txtdiaadidisvac".$li_temp." type=text id=txtdiaadidisvac".$li_temp." class=sin-borde size=6 value='".$li_diaadidisvac."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_temp][4]="<input name=txtdiabonvac".$li_temp." type=text id=txtdiabonvac".$li_temp." class=sin-borde size=6 value='".$li_diabonvac."' onKeyUp='javascript: ue_validarnumero(this);'> ";
					$lo_object[$li_temp][5]="<input name=txtdiaadibonvac".$li_temp." type=text id=txtdiaadibonvac".$li_temp." class=sin-borde size=6 value='".$li_diaadibonvac."' onKeyUp='javascript: ue_validarnumero(this);'> ";
					$lo_object[$li_temp][6]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.gif title=Agregar Detalle alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_temp][7]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/deshacer.gif title=Eliminar Detalle alt=Aceptar width=15 height=15 border=0></a>";
				}
				else
				{
					$li_lappervac=$_POST["txtlappervac".$li_i];
					$lb_valido=$io_tablavac->uf_delete_perido($ls_codtabvac,$li_lappervac,$la_seguridad);
					if($lb_valido)
					{
					}			
					$li_rowdelete= 0;
				}					
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
			
		case "BUSCARDETALLE":
		 	$ls_codtabvac=$_POST["txtcodtabvac"];
			$ls_dentabvac=$_POST["txtdentabvac"];
			$ls_activarcodigo="readOnly";
			$ls_pertabvac=$io_fun_nomina->uf_obtenervalor("cmbpertabvac","");
			$io_fun_nomina->uf_seleccionarcombo("0-1",$ls_pertabvac,$la_periodo,2);
		 	$li_adequitabvac=$io_fun_nomina->uf_obtenervalor("chkadequitabvac","0");
			$ls_adelantaquincena=$io_fun_nomina->uf_obtenervariable($li_adequitabvac,1,0,"checked","","");
			$li_aderettabvac=$io_fun_nomina->uf_obtenervalor("chkaderettabvac","0");			
			$ls_adelantaretencion=$io_fun_nomina->uf_obtenervariable($li_aderettabvac,1,0,"checked","","");
		 	$li_bonauttabvac=$io_fun_nomina->uf_obtenervalor("chkbonauttabvac","0");			
			$ls_bonoautomatico=$io_fun_nomina->uf_obtenervariable($li_bonauttabvac,1,0,"checked","","");
		 	$li_anoserpre=$io_fun_nomina->uf_obtenervalor("chkanoserpre","0");
			$ls_anoserpre=$io_fun_nomina->uf_obtenervariable($li_anoserpre,1,0,"checked","","");
			$lb_valido=$io_tablavac->uf_load_tablavacacion_periodo($ls_codtabvac,$li_totrows,$lo_object);
			if ($lb_valido==false)
			{
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			break;
	}
	$io_tablavac->uf_destructor();
	unset($io_tablavac);
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif"  title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif"  title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
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
          <td height="20" colspan="2" class="titulo-ventana">Definici&oacute;n de Tabla de Vacaciones </td>
        </tr>
        <tr>
          <td width="157" height="22">&nbsp;</td>
          <td width="387">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td><div align="left">
            <input name="txtcodtabvac" type="text" id="txtcodtabvac" size="5" maxlength="2" value="<?php print $ls_codtabvac;?>" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,2);" <?php print $ls_activarcodigo;?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Denominaci&oacute;n</div></td>
          <td><div align="left">
            <input name="txtdentabvac" type="text" id="txtdentabvac" value="<?php print $ls_dentabvac;?>" size="60" maxlength="120" onKeyUp="ue_validarcomillas(this);">
          </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;odo</div></td>
          <td>
		    <div align="left">
		      <select name="cmbpertabvac" id="cmbpertabvac">
		        <option value="" selected>--Seleccione Uno--</option>
		        <option value="0" <?php print $la_periodo[0];?>>Quinquenal</option>
		        <option value="1" <?php print $la_periodo[1];?>>Anual</option>
		          </select>
	        </div></td>
          </tr>
        <tr>
          <td><div align="right">Tomar en cuenta A&ntilde;os de Servicios Previos </div></td>
          <td><label>
            <input name="chkanoserpre" type="checkbox" class="sin-borde" id="chkanoserpre" value="1" <?php print $ls_anoserpre; ?>>
          </label></td>
        </tr>
        <tr>
          <td><div align="right"></div></td>
          <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
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
		f.action="sigesp_snorh_d_tablavacacion.php";
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
		ls_codtabvac=ue_validarvacio(f.txtcodtabvac.value);
		ls_dentabvac=ue_validarvacio(f.txtdentabvac.value);
		
		if ((ls_codtabvac=="")||(ls_codtabvac==""))
		{
			alert("Debe llenar todos los datos.");
		}
		else
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_snorh_d_tablavacacion.php";
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
			ls_codtabvac = ue_validarvacio(f.txtcodtabvac.value);
			if (ls_codtabvac=="")
			{
				alert("Debe buscar el registro a eliminar.");
			}
			else
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_snorh_d_tablavacacion.php";
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
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_tablavacaciones.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_snorh_cat_tablavacacion.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
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
		li_lappervacnew=eval("f.txtlappervac"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			li_lappervac=eval("f.txtlappervac"+li_i+".value");
			if((li_lappervac==li_lappervacnew)&&(li_i!=li_row))
			{
				alert("el periodo ya existe");
				lb_valido=true;
			}
		}
		ls_codtabvac=ue_validarvacio(f.txtcodtabvac.value);
		ls_dentabvac=ue_validarvacio(f.txtdentabvac.value);
		li_lappervac=eval("f.txtlappervac"+li_row+".value");
		li_lappervac=ue_validarvacio(li_lappervac);
		li_diadisvac=eval("f.txtdiadisvac"+li_row+".value");
		li_diadisvac=ue_validarvacio(li_diadisvac);
		li_diaadidisvac=eval("f.txtdiaadidisvac"+li_row+".value");
		li_diaadidisvac=ue_validarvacio(li_diaadidisvac);
		li_diabonvac=eval("f.txtdiabonvac"+li_row+".value");
		li_diabonvac=ue_validarvacio(li_diabonvac);
		li_diaadibonvac=eval("f.txtdiaadibonvac"+li_row+".value");
		li_diaadibonvac=ue_validarvacio(li_diaadibonvac);
	
		if((ls_codtabvac=="")||(ls_dentabvac=="")||(li_lappervac=="")||(li_diadisvac=="")||(li_diabonvac=="")||(li_diaadidisvac=="")||(li_diaadibonvac==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_snorh_d_tablavacacion.php";
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
		li_lappervac=eval("f.txtlappervac"+li_row+".value");
		li_lappervac=ue_validarvacio(li_lappervac);
		if(li_lappervac=="")
		{
			alert("la fila a eliminar no debe estar vacio el lapso");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_snorh_d_tablavacacion.php";
				f.submit();
			}
		}
	}
}
</script> 
</html>