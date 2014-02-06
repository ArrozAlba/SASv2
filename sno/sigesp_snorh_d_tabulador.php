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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_tabulador.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codtab,$ls_destab,$ls_activarcodigo,$li_calculada,$li_totrows,$ls_operacion,$ls_existe,$ls_comauto,$li_maxpasgra;
		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$io_fun_nomina,$ls_ajusuerac,$ls_codnom,$io_tabulador;
		
		$ls_codtab="";
		$ls_destab="";
		$ls_codnom="";
		$li_maxpasgra=0;
		$ls_activarcodigo="";
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
		$ls_titletable="Sueldos";
		$li_widthtable=550;
		$ls_nametable="grid";
		$lo_title[1]="Grado";
		$lo_title[2]="Paso";
		$lo_title[3]="Salario";
		$lo_title[4]="Compensación";
		$lo_title[5]="Salario + Compensación";
		$lo_title[6]=" ";
		$lo_title[7]=" ";	
		$lo_title[8]="Primas";	
		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();
		$ls_comauto=$io_sno->uf_select_config("SNO","CONFIG","COMPENSACION_AUTOMATICA_RAC","1","I");
		$ls_ajusuerac=$io_sno->uf_select_config("SNO","CONFIG","AJUSTAR_SUELDO_RAC","0","I");
		unset($io_sno);
		$li_calculada=0;
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//		   Access: private
		//	    Arguments: aa_object  // arreglo de Objetos
		//			       ai_totrows  // total de Filas
		//	  Description: Función que agrega una linea mas en el grid
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		global $ls_comauto;
		
		$ls_readonly="";
		if($ls_comauto=="1")
		{
			$ls_readonly="readonly";
		}
		$aa_object[$ai_totrows][1]="<input name=txtcodgra".$ai_totrows." type=text id=txtcodgra".$ai_totrows." class=sin-borde size=18 maxlength=15 onKeyUp='javascript: ue_validarcomillas(this);'>";
		$aa_object[$ai_totrows][2]="<input name=txtcodpas".$ai_totrows." type=text id=txtcodpas".$ai_totrows." class=sin-borde size=18 maxlength=15 onKeyUp=''><input name='existe".$ai_totrows."' type='hidden' id='existe".$ai_totrows."' value='0'>";
		$aa_object[$ai_totrows][3]="<input name=txtmonsalgra".$ai_totrows." type=text id=txtmonsalgra".$ai_totrows." class=sin-borde size=16 maxlength=13 onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right onBlur='javascript: ue_sumarcompensacion(".$ai_totrows.");javascript: uf_sumarsueldo(".$ai_totrows.");'>";
		$aa_object[$ai_totrows][4]="<input name=txtmoncomgra".$ai_totrows." type=text id=txtmoncomgra".$ai_totrows." class=sin-borde size=16 maxlength=13 onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right onBlur='javascript: uf_sumarsueldo(".$ai_totrows.");' ".$ls_readonly.">";
		$aa_object[$ai_totrows][5]="<input name=txtsueldo".$ai_totrows." type=text id=txtsueldo".$ai_totrows." class=sin-borde size=16 maxlength=13 onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right readonly>";
		$aa_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif title=Agregar alt=Aceptar width=15 height=15 border=0></a>";
		$aa_object[$ai_totrows][7]="<a href=javascript:limpiar_row(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif title=Deshacer alt=Deshacer width=15 height=15 border=0></a>";	
		$aa_object[$ai_totrows][8]="<div align='center'><img src=../shared/imagebank/mas.gif title=Abrir alt=Definir primas border=0></div>";			
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
<title >Definici&oacute;n de Tabulador</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
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
	require_once("sigesp_snorh_c_tabulador.php");
	$io_tabulador=new sigesp_snorh_c_tabulador();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_agregarlineablanca($lo_object,1);
			break;

		case "GUARDAR":
			$ls_codtab=$_POST["txtcodtab"];
			$ls_destab=$_POST["txtdestab"];
			$li_maxpasgra=$_POST["txtmaxpasgra"];
			$ls_codnom=$io_fun_nomina->uf_asignarvalor("cmbnomina",$_POST["txtcodnom"]);
			$io_tabulador->io_sql->begin_transaction();
			$lb_valido=$io_tabulador->uf_guardar($ls_existe,$ls_codtab,$ls_destab,$li_maxpasgra,$ls_codnom,$la_seguridad);
			if($lb_valido)
			{
				for($li_i=1;$li_i<$li_totrows&&$lb_valido;$li_i++)
				{
					$ls_codgra=$_POST["txtcodgra".$li_i];
					$ls_codpas=$_POST["txtcodpas".$li_i];
					$li_monsalgra=$_POST["txtmonsalgra".$li_i];
					$li_moncomgra=$_POST["txtmoncomgra".$li_i];
					$lb_valido=$io_tabulador->uf_guardar_grado($ls_codtab,$ls_codgra,$ls_codpas,$li_monsalgra,$li_moncomgra,$ls_codnom,$la_seguridad);
				}
			}
			if($lb_valido)
			{
				$io_tabulador->io_sql->commit();
				if($ls_existe==="TRUE")
				{
					$io_tabulador->io_mensajes->message("El Tabulador fue Actualizado.");
				}
				else
				{
					$io_tabulador->io_mensajes->message("El Tabulador fue Registrado.");
				}
			}
			else
			{
				$io_tabulador->io_sql->rollback();
				$io_tabulador->io_mensajes->message("Ocurrio un error al guardar el tabulador.");
			}
			uf_limpiarvariables();
			$ls_existe="FALSE";
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINAR":
			$ls_codtab=$_POST["txtcodtab"];
			$ls_codnom=$io_fun_nomina->uf_asignarvalor("cmbnomina",$_POST["txtcodnom"]);
			$lb_valido=$io_tabulador->uf_delete_tabulador($ls_codtab,$ls_codnom,$la_seguridad);
			uf_limpiarvariables();
			$ls_existe="FALSE";
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "AGREGARDETALLE":
			$ls_codtab=$_POST["txtcodtab"];
			$ls_destab=$_POST["txtdestab"];
			$li_maxpasgra=$_POST["txtmaxpasgra"];
			$ls_codnom=$io_fun_nomina->uf_asignarvalor("cmbnomina",$_POST["txtcodnom"]);
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows+1;
			$ls_codgraant="";
			$li_contador=0;
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$ls_codgra=$_POST["txtcodgra".$li_i];
				$ls_codpas=$_POST["txtcodpas".$li_i];
				$li_monsalgra=$_POST["txtmonsalgra".$li_i];
				$li_moncomgra=$_POST["txtmoncomgra".$li_i];
				$li_sueldo=$_POST["txtsueldo".$li_i];
				$li_existe=$_POST["existe".$li_i];
				$ls_readonly="";
				if($ls_comauto=="1")
				{
					$ls_readonly="readonly";
				}
				$ls_estilo = "sin-borde";
				if($li_maxpasgra>0)
				{
					if($ls_codgra!=$ls_codgraant)
					{
						$ls_codgraant=$ls_codgra;
						$li_contador=1;
					}
					else
					{
						$li_contador++;
						if($li_contador>$li_maxpasgra)
						{
							$ls_readonly="";
							$ls_estilo = "sin-borderesaltado";
						}
					}
				}
				$lo_object[$li_i][1]="<input class=".$ls_estilo." name=txtcodgra".$li_i." type=text id=txtcodgra".$li_i."  size=18 maxlength=15 onKeyUp='javascript: ue_validarcomillas(this);' value='".$ls_codgra."' readOnly>";
				$lo_object[$li_i][2]="<input class=".$ls_estilo." name=txtcodpas".$li_i." type=text id=txtcodpas".$li_i."  size=18 maxlength=15 onKeyUp='javascript: ue_validarcomillas(this);' value='".$ls_codpas."' readOnly><input name='existe".$li_i."' type='hidden' id='existe".$li_i."' value='".$li_existe."'>";
				$lo_object[$li_i][3]="<input class=".$ls_estilo." name=txtmonsalgra".$li_i." type=text id=txtmonsalgra".$li_i."  size=16 maxlength=13 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_monsalgra."' onBlur='javascript: ue_sumarcompensacion(".$li_i.");javascript:uf_sumarsueldo(".$li_i.");' style=text-align:right>";
				$lo_object[$li_i][4]="<input class=".$ls_estilo." name=txtmoncomgra".$li_i." type=text id=txtmoncomgra".$li_i."  size=16 maxlength=13 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_moncomgra."' style=text-align:right  onBlur='javascript: uf_sumarsueldo(".$li_i.");' ".$ls_readonly.">";
				$lo_object[$li_i][5]="<input class=".$ls_estilo." name=txtsueldo".$li_i." type=text id=txtsueldo".$li_i." class=sin-borde size=16 maxlength=13 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_sueldo."' style=text-align:right readonly>";
				$lo_object[$li_i][6]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=../shared/imagebank/tools15/aprobado.gif  title=Agregar alt=Aceptar width=15 height=15 border=0></a>";
				$lo_object[$li_i][7]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/deshacer.gif title=Eliminar alt=Aceptar width=15 height=15 border=0></a>";			
				$lo_object[$li_i][8]="<div align='center'><a href=javascript:uf_abrir_prima('".$li_i."');><img src=../shared/imagebank/mas.gif title=Abrir alt=Definir primas border=0></a></div>";			
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARDETALLE":
			$ls_codtab=$_POST["txtcodtab"];
			$ls_destab=$_POST["txtdestab"];
			$li_maxpasgra=$_POST["txtmaxpasgra"];
			$ls_codnom=$io_fun_nomina->uf_asignarvalor("cmbnomina",$_POST["txtcodnom"]);
			$ls_activarcodigo="readOnly";
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;			
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp=$li_temp+1;			
					$ls_codgra=$_POST["txtcodgra".$li_i];
					$ls_codpas=$_POST["txtcodpas".$li_i];
					$li_monsalgra=$_POST["txtmonsalgra".$li_i];
					$li_moncomgra=$_POST["txtmoncomgra".$li_i];
					$li_sueldo=$_POST["txtsueldo".$li_i];
					$li_existe=$_POST["existe".$li_i];
					$ls_readonly="";
					$ls_codgraant=0;
					if($ls_comauto=="1")
					{
						$ls_readonly="readonly";
					}
					$ls_estilo = "sin-borde";
					if($li_maxpasgra>0)
					{
						if($ls_codgra!=$ls_codgraant)
						{
							$ls_codgraant=$ls_codgra;
							$li_contador=1;
						}
						else
						{
							$li_contador++;
							if($li_contador>$li_maxpasgra)
							{
								$ls_readonly="";
								$ls_estilo = "sin-borderesaltado";
							}
						}
					}
					$lo_object[$li_temp][1]="<input class=".$ls_estilo." name=txtcodgra".$li_temp." type=text id=txtcodgra".$li_temp." size=18 maxlength=15 onKeyUp='javascript: ue_validarcomillas(this);' value='".$ls_codgra."'>";
					$lo_object[$li_temp][2]="<input class=".$ls_estilo." name=txtcodpas".$li_temp." type=text id=txtcodpas".$li_temp." size=18 maxlength=15 onKeyUp='javascript: ue_validarcomillas(this);' value='".$ls_codpas."'><input name='existe".$li_temp."' type='hidden' id='existe".$li_temp."' value='".$li_existe."'>";
					$lo_object[$li_temp][3]="<input class=".$ls_estilo." name=txtmonsalgra".$li_temp." type=text id=txtmonsalgra".$li_temp." size=16 maxlength=13 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_monsalgra."' onBlur='javascript: ue_sumarcompensacion(".$li_temp.");javascript:uf_sumarsueldo(".$li_temp.");' style=text-align:right>";
					$lo_object[$li_temp][4]="<input class=".$ls_estilo." name=txtmoncomgra".$li_temp." type=text id=txtmoncomgra".$li_temp." size=16 maxlength=13 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_moncomgra."' style=text-align:right onBlur='javascript: uf_sumarsueldo(".$li_temp.");' ".$ls_readonly.">";
					$lo_object[$li_temp][5]="<input class=".$ls_estilo." name=txtsueldo".$li_temp." type=text id=txtsueldo".$li_temp." class=sin-borde size=16 maxlength=13 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_sueldo."' style=text-align:right readonly>";
					$lo_object[$li_temp][6]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.gif title=Agregar alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_temp][7]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/deshacer.gif title=Eliminar alt=Aceptar width=15 height=15 border=0></a>";			
					$lo_object[$li_temp][8]="<div align='center'><a href=javascript:uf_abrir_prima('".$li_temp."');><img src=../shared/imagebank/mas.gif title=Abrir alt=Definir primas border=0></a></div>";			
				}
				else
				{
					$ls_codgra=$_POST["txtcodgra".$li_i];
					$ls_codpas=$_POST["txtcodpas".$li_i];
					$lb_valido=$io_tabulador->uf_delete_grado($ls_codtab,$ls_codgra,$ls_codpas,$ls_codnom,$la_seguridad);
					$li_rowdelete=0;
					if(!$lb_valido)
					{
						$li_totrows=$li_totrows+1;
						$li_temp=$li_temp+1;			
						$ls_codgra=$_POST["txtcodgra".$li_i];
						$ls_codpas=$_POST["txtcodpas".$li_i];
						$li_monsalgra=$_POST["txtmonsalgra".$li_i];
						$li_moncomgra=$_POST["txtmoncomgra".$li_i];
						$li_sueldo=$_POST["txtsueldo".$li_i];
						$li_existe=$_POST["existe".$li_i];
						$ls_readonly="";
						if($ls_comauto=="1")
						{
							$ls_readonly="readonly";
						}
						$ls_estilo = "sin-borde";
						if($li_maxpasgra>0)
						{
							if($ls_codgra!=$ls_codgraant)
							{
								$ls_codgraant=$ls_codgra;
								$li_contador=1;
							}
							else
							{
								$li_contador++;
								if($li_contador>$li_maxpasgra)
								{
									$ls_readonly="";
									$ls_estilo = "sin-borderesaltado";
								}
							}
						}
						$lo_object[$li_temp][1]="<input class=".$ls_estilo." name=txtcodgra".$li_temp." type=text id=txtcodgra".$li_temp." size=18 maxlength=15 onKeyUp='javascript: ue_validarcomillas(this);' value='".$ls_codgra."'>";
						$lo_object[$li_temp][2]="<input class=".$ls_estilo." name=txtcodpas".$li_temp." type=text id=txtcodpas".$li_temp." size=18 maxlength=15 onKeyUp='javascript: ue_validarcomillas(this);' value='".$ls_codpas."'><input name='existe".$li_temp."' type='hidden' id='existe".$li_temp."' value='".$li_existe."'>";
						$lo_object[$li_temp][3]="<input class=".$ls_estilo." name=txtmonsalgra".$li_temp." type=text id=txtmonsalgra".$li_temp." size=16 maxlength=13 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_monsalgra."' onBlur='javascript: ue_sumarcompensacion(".$li_temp.");javascript:uf_sumarsueldo(".$li_temp.");'>";
						$lo_object[$li_temp][4]="<input class=".$ls_estilo." name=txtmoncomgra".$li_temp." type=text id=txtmoncomgra".$li_temp." size=16 maxlength=13 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_moncomgra."' onBlur='javascript: uf_sumarsueldo(".$li_temp.");' ".$ls_readonly.">";
						$lo_object[$li_temp][5]="<input class=".$ls_estilo." name=txtsueldo".$li_temp." type=text id=txtsueldo".$li_temp." class=sin-borde size=16 maxlength=13 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_sueldo."' style=text-align:right readonly>";
						$lo_object[$li_temp][6]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.gif title=Agregar alt=Aceptar width=15 height=15 border=0></a>";
						$lo_object[$li_temp][7]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/deshacer.gif title=Eliminar alt=Aceptar width=15 height=15 border=0></a>";			
						$lo_object[$li_temp][8]="<div align='center'><a href=javascript:uf_abrir_prima('".$li_temp."');><img src=../shared/imagebank/mas.gif title=Abrir alt=Definir primas border=0></a></div>";			
					}
				}					
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
			
		case "BUSCARDETALLE":
			$ls_codtab=$_POST["txtcodtab"];
			$ls_destab=$_POST["txtdestab"];
			$li_maxpasgra=$_POST["txtmaxpasgra"];
			$ls_codnom=$io_fun_nomina->uf_asignarvalor("cmbnomina",$_POST["txtcodnom"]);
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_tabulador->uf_load_grado($ls_codtab,$ls_comauto,$li_maxpasgra,$ls_codnom,$li_totrows,$lo_object);
			$li_totrows++;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
	}
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
  
 <?php

	if (isset($_GET["valor"]))
	{ $ls_valor=$_GET["valor"];	}
	else
	{ $ls_valor="";}
	
	if ($ls_valor!='srh')
	{
	   print ('<tr>');
	   print ('<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>' );
	   print ('</tr>');
	}
	
  ?>
 
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif"  title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
	
	<?php

	if (isset($_GET["valor"]))
	{ $ls_valor=$_GET["valor"];	}
	else
	{ $ls_valor="";}
	
	if ($ls_valor!='srh')
	{
	    print ('<td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title=Salir alt="Salir" width="20" height="20" border="0"></a></div></td>' );	   
	}
	else
	{
	 print ('<td class="toolbar" width="25"><div align="center"><a href="javascript: close();"><img src="../shared/imagebank/tools20/salir.gif" title=Salir alt="Salir" width="20" height="20" border="0"></a></div></td>' );	
	}
	
  ?>
 
     
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
  <input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="720" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="670" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Definición de Tabulador</td>
        </tr>
        <tr>
          <td width="109" height="22">&nbsp;</td>
          <td width="348">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td><div align="left">
            <input name="txtcodtab" type="text" id="txtcodtab" size="25" maxlength="20" value="<?php print $ls_codtab;?>" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,20);" <?php print $ls_activarcodigo; ?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Descripci&oacute;n</div></td>
          <td><div align="left">
            <input name="txtdestab" type="text" id="txtdestab" size="60" maxlength="100" value="<?php print $ls_destab;?>" onKeyUp="ue_validarcomillas(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nomina</div></td>
          <td><div align="left">
            <?php $io_tabulador->uf_cargarnomina($ls_codnom,$li_calculada); ?>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Total de Pasos por Grado  </div></td>
          <td><label>
            <input name="txtmaxpasgra" type="text" id="txtmaxpasgra" value="<?php print $li_maxpasgra;?>" size="5" maxlength="2" onKeyUp="javascript: ue_validarnumero(this);">
          </label></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
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
			  <input name="comauto" type="hidden" id="comauto" value="<?php print $ls_comauto;?>">
			  <input name="ajusuerac" type="hidden" id="ajusuerac" value="<?php print $ls_ajusuerac;?>">
			  <input name="hidsrh" type="hidden" id="hidsrh" value="<?php print $ls_valor;?>">
            </p></td>
          </tr>
      </table>    
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>    
<?php
	$io_tabulador->uf_destructor();
	unset($io_tabulador);
?>  
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_nuevo()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_incluir=f.incluir.value;
		if(li_incluir==1)
		{	
			f.operacion.value="NUEVO";
			f.existe.value="FALSE";		
			f.totalfilas.value=1;
			valor=f.hidsrh.value;	
			if (valor=='srh')
			{
			  f.action="sigesp_snorh_d_tabulador.php?valor="+valor;	  
			}
			else
			{
			 f.action="sigesp_snorh_d_tabulador.php";		
			}
			f.submit();
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function ue_guardar()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_incluir=f.incluir.value;
		li_cambiar=f.cambiar.value;
		lb_existe=f.existe.value;
		if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
		{
			codtab = ue_validarvacio(f.txtcodtab.value);
			codnom = ue_validarvacio(f.cmbnomina.value);
			destab = ue_validarvacio(f.txtdestab.value);
			li_total=f.totalfilas.value;
			lb_valido=true;
			for(li_i=1;li_i<=li_total&&lb_valido;li_i++)
			{
				monsalgra=ue_validarvacio(eval("f.txtmonsalgra"+li_i+".value"));
				moncomgra=ue_validarvacio(eval("f.txtmoncomgra"+li_i+".value"));
				monsalgra=uf_convertir_monto(monsalgra);
				moncomgra=uf_convertir_monto(moncomgra);
				if((parseFloat(monsalgra)<=0)||(parseFloat(moncomgra)<0))
				{
					alert("El Monto del Salario y el de la Compensación deben ser mayores que cero");
					lb_valido=false;
				}
			}
			if(lb_valido)
			{
				if ((codtab!="")&&(destab!="")&&(codnom!=""))
				{
					f.operacion.value="GUARDAR";
					valor=f.hidsrh.value;	
					if (valor=='srh')
					{
					  f.action="sigesp_snorh_d_tabulador.php?valor="+valor;	  
					}
					else
					{
					 f.action="sigesp_snorh_d_tabulador.php";		
					}
					f.submit();
				}
				else
				{
					alert("Debe llenar todos los datos.");
				}
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function ue_eliminar()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_eliminar=f.eliminar.value;
		if(li_eliminar==1)
		{	
			if(f.existe.value=="TRUE")
			{
				codtab = ue_validarvacio(f.txtcodtab.value);
				codnom = ue_validarvacio(f.cmbnomina.value);
				if ((codtab!="")&&(codnom!=""))
				{
					if(confirm("¿Desea eliminar el Registro actual?"))
					{
						f.operacion.value="ELIMINAR";
						valor=f.hidsrh.value;	
						if (valor=='srh')
						{
						  f.action="sigesp_snorh_d_tabulador.php?valor="+valor;	  
						}
						else
						{
						 f.action="sigesp_snorh_d_tabulador.php";		
						}
						f.submit();
					}
				}
				else
				{
					alert("Debe buscar el registro a eliminar.");
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
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
	    valor=f.hidsrh.value;
		window.open("sigesp_snorh_cat_tabulador.php?valor="+valor,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_total=f.totalfilas.value;
		if(li_total==li_row)
		{
			ls_codpasnew=ue_validarvacio(eval("f.txtcodpas"+li_row+".value"));
			ls_codgranew=ue_validarvacio(eval("f.txtcodgra"+li_row+".value"));
			li_total=f.totalfilas.value;
			lb_valido=false;
			for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
			{
				ls_codpas=ue_validarvacio(eval("f.txtcodpas"+li_i+".value"));
				ls_codgra=ue_validarvacio(eval("f.txtcodgra"+li_i+".value"));
				if((ls_codpas==ls_codpasnew)&&(ls_codgra==ls_codgranew)&&(li_i!=li_row))
				{
					alert("El paso y grado ya existe");
					lb_valido=true;
				}
			}
			ls_codtab=ue_validarvacio(f.txtcodtab.value);
			ls_codpas=ue_validarvacio(eval("f.txtcodpas"+li_row+".value"));
			ls_codgra=ue_validarvacio(eval("f.txtcodgra"+li_row+".value"));
			li_monsalgra=ue_validarvacio(eval("f.txtmonsalgra"+li_row+".value"));
			li_moncomgra=ue_validarvacio(eval("f.txtmoncomgra"+li_row+".value"));
			if((ls_codtab=="")||(ls_codpas=="")||(ls_codgra=="")||(li_monsalgra=="")||(li_moncomgra==""))
			{
				alert("Debe llenar todos los campos");
				lb_valido=true;
			}
			
			if(!lb_valido)
			{
				uf_sumarsueldo(li_row);
				f.operacion.value="AGREGARDETALLE";
				valor=f.hidsrh.value;	
				if (valor=='srh')
				{
				  f.action="sigesp_snorh_d_tabulador.php?valor="+valor;	  
				}
				else
				{
				 f.action="sigesp_snorh_d_tabulador.php";		
				}
				f.submit();
			}
		}
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function uf_delete_dt(li_row)
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_total=f.totalfilas.value;
		if(li_total>li_row)
		{
			ls_codpas=ue_validarvacio(eval("f.txtcodpas"+li_row+".value"));
			ls_codgra=ue_validarvacio(eval("f.txtcodgra"+li_row+".value"));
			if((ls_codpas=="")||(ls_codgra==""))
			{
				alert("la fila a eliminar no debe tener el Paso y Grado vacio");
			}
			else
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.filadelete.value=li_row;
					f.operacion.value="ELIMINARDETALLE"
					valor=f.hidsrh.value;	
					if (valor=='srh')
					{
					  f.action="sigesp_snorh_d_tabulador.php?valor="+valor;	  
					}
					else
					{
					 f.action="sigesp_snorh_d_tabulador.php";		
					}
					f.submit();
				}
			}
		}
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function uf_abrir_prima(li_row)
{
	f=document.form1;
	codtab=f.txtcodtab.value;
	destab=f.txtdestab.value;
	codnom=f.cmbnomina.value;
	calculada=f.calculada.value;
	codpas=ue_validarvacio(eval("f.txtcodpas"+li_row+".value"));
	codgra=ue_validarvacio(eval("f.txtcodgra"+li_row+".value"));
	existe=ue_validarvacio(eval("f.existe"+li_row+".value"));
	if(existe=="0")
	{
		alert("Primero debe Guardar, para luego agregar las primas.");
	}
	else
	{
		pagina="sigesp_snorh_pdt_primagrado.php?codtab="+codtab+"&destab="+destab+"&codpas="+codpas+"&codgra="+codgra+"&codnom="+codnom+"&calculada="+calculada;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_sumarcompensacion(li_row)
{
	f=document.form1;
	li_comauto=f.comauto.value;
	li_pasmax=f.txtmaxpasgra.value;
	if(li_comauto==1)
	{
		li_total=f.totalfilas.value;
		if(li_total>1)
		{
			t=0;
			k=0;
			codgraant=ue_validarvacio(eval("f.txtcodgra"+li_row+".value"));
			for(i=1;i<li_row;i++)
			{
				codgra=ue_validarvacio(eval("f.txtcodgra"+i+".value"));
				if(codgraant==codgra)
				{
					t++;
					k=i;
				}
				
			}
				if(t<li_pasmax)
				{
					if(k>0)
					{
							codgraant=ue_validarvacio(eval("f.txtcodgra"+k+".value"));
							codgra=ue_validarvacio(eval("f.txtcodgra"+li_row+".value"));
							salario=ue_validarvacio(eval("f.txtmonsalgra"+k+".value"));
							if(codgraant==codgra)
						    {
								monsalgraant=ue_validarvacio(eval("f.txtmonsalgra"+k+".value"));
								monsalgra=ue_validarvacio(eval("f.txtmonsalgra"+li_row+".value"));
								monsalgraant=uf_convertir_monto(monsalgraant);
								monsalgra=uf_convertir_monto(monsalgra);
								moncomgra=(monsalgra-monsalgraant);
								if(parseFloat(moncomgra)<=0)
								{
									eval("f.txtmonsalgra"+li_row+".value='0,00';");	
									eval("f.txtmoncomgra"+li_row+".value='0,00';");
									alert("la compensación no puede ser cero ó menor que cero");
								}
								else
								{
									moncomgra=uf_convertir(moncomgra);
									eval("f.txtmoncomgra"+li_row+".value='"+moncomgra+"'");
									if(f.ajusuerac.value=="1")
									{
										eval("f.txtmonsalgra"+li_row+".value='"+salario+"'");
									}
								}
						    }
					}
					else
					{
						eval("f.txtmoncomgra"+li_row+".value='0,00'");
					}
					uf_sumarsueldo(li_row);
				}
				else
				{
					t=0;
					ckeck=0;
					p=0;
					codgraant=ue_validarvacio(eval("f.txtcodgra"+li_row+".value"));
					for(i=1;i<li_row;i++)
					{	
						codgra=ue_validarvacio(eval("f.txtcodgra"+i+".value"));
						if((codgraant==codgra)||(ckeck==0))
						{
							t++;
							ckeck=1;
						}
						if(codgraant==codgra)
						{
							codpas=ue_validarvacio(eval("f.txtcodpas"+i+".value"));
							if(codpas==li_pasmax)
							{
								p=i;
							    
							}
							
						}
					}
					monsalgraant=ue_validarvacio(eval("f.txtmonsalgra"+p+".value"));
					monsalgraant=uf_convertir_monto(monsalgraant);
					salario=ue_validarvacio(eval("f.txtsueldo"+p+".value"));
					salario=uf_convertir_monto(salario);
					porcent=ue_validarvacio(eval("f.txtcodpas"+li_row+".value"));
					porcentaje=eval(porcent+"/"+100);//porcentaje
					monto=eval(porcentaje+"*"+salario);
					monto=redondear(monto,3) ;//Redondeo de Porcentaje
					sueldo=eval(monto+"+"+salario);//monto_porcentaje+salario==>total
					monto=eval(sueldo+"-"+monsalgraant);
					sueldo=eval(monsalgraant+"+"+monto);
					monto=uf_convertir(monto);
					salario=uf_convertir(salario);
					sueldo=uf_convertir(sueldo);
					monsalgraant=uf_convertir(monsalgraant);
					eval("f.txtmonsalgra"+li_row+".value='"+monsalgraant+"';");	
					eval("f.txtmoncomgra"+li_row+".value='"+monto+"';");	
					eval("f.txtsueldo"+li_row+".value='"+sueldo+"';");	
				}			
			
		}
		else
		{
			eval("f.txtmoncomgra"+li_row+".value='0,00'");
		}
	}
	else
	{
		eval("f.txtmoncomgra"+li_row+".value='0,00'");
	}
	
}

function uf_sumarsueldo(li_row)
{
	f=document.form1;
	li_monsalgra=ue_validarvacio(eval("f.txtmonsalgra"+li_row+".value"));
	li_moncomgra=ue_validarvacio(eval("f.txtmoncomgra"+li_row+".value"));
	li_monsalgra=uf_convertir_monto(li_monsalgra);
	li_moncomgra=uf_convertir_monto(li_moncomgra);
	sueldo=eval(li_monsalgra+"+"+li_moncomgra);
	sueldo=uf_convertir(sueldo);
	eval("f.txtsueldo"+li_row+".value='"+sueldo+"';");	
}

function uf_cambiarnomina()
{
	f=document.form1;
	f.txtcodnom.value=f.cmbnomina.value;
	f.calculada.value=eval("document.form1.calculada"+f.txtcodnom.value+".value");
}


function limpiar_row(li_row)
{
	f=document.form1;
	eval("f.txtcodpas"+li_row+".value='"+""+"';");
	eval("f.txtcodgra"+li_row+".value='"+""+"';");
	eval("f.txtmonsalgra"+li_row+".value='"+""+"';");
	eval("f.txtmoncomgra"+li_row+".value='"+""+"';");
	eval("f.txtsueldo"+li_row+".value='"+""+"';");

}
</script> 
</html>