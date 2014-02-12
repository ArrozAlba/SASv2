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
require_once("class_funciones_activos.php");
$io_fun_activo=new class_funciones_activos();
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_p_desincorporaciones.php",$ls_permisos,$la_seguridad,$la_permisos);
$lb_cierrescg = $io_fun_activo->uf_chkciescg();
require_once("sigesp_saf_c_activo.php");
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
$io_saf_tipcat= new sigesp_saf_c_activo();
$ls_rbtipocat=$io_saf_tipcat->uf_select_valor_config($ls_codemp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	function uf_obtenervalor($as_valor, $as_valordefecto)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_obtenervalor
		//	Access:    public
		//	Arguments:
		// 				as_valor         //  nombre de la variable que desamos obtener
		// 				as_valordefecto  //  contenido de la variable
		// Description: Función que obtiene el valor de una variable si viene de un submit
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists($as_valor,$_POST))
		{
			$valor=$_POST[$as_valor];
		}
		else
		{
			$valor=$as_valordefecto;
		}
		return $valor; 
	}
   //--------------------------------------------------------------
	function uf_limpiarvariables()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
		global $ls_cmpmov,$ls_codres,$ls_codresnew,$ls_nomres,$ls_nomresnew,$ls_descmp,$ld_feccmp,$ls_codcau,$ls_dencau,$ls_estpromov;
		global $li_montot,$li_totdeb,$li_tothab,$li_diferencia,$ls_status;
		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$li_totrows;
		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_titlescg,$li_totrowsscg;
		
		$ls_cmpmov="";
		$ls_codres="";
		$ls_codresnew="";
		$ls_nomres="";
		$ls_nomresnew="";
		$ls_descmp="";
		$ls_codcau="";
		$ls_dencau="";
		$ls_estpromov="";
		$ld_feccmp= date("d/m/Y");
		$li_montot=0.00;
		$li_totdeb="";
		$li_tothab="";
		$li_diferencia="";
		$ls_status="";		
		
		$ls_titletable="Activos";
		$li_widthtable=700;
		$ls_nametable="grid";
		$lo_title[1]="Activo";
		$lo_title[2]="Serial";
		$lo_title[3]="Descripción del Movimiento";
		$lo_title[4]="Monto Activo";
		$lo_title[5]="";
		
		$lo_titlescg[1]="SC Cuenta";
		$lo_titlescg[2]="Documento";
		$lo_titlescg[3]="Deb/Hab";
		$lo_titlescg[4]="Monto";
		$lo_titlescg[5]="";
		$li_totrows=1;
		$li_totrowsscg=1;
	
	}

	function uf_agregarlineablanca(&$aa_object,$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $aa_object // arreglo de titulos 
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que agrega una linea en blanco al final del grid
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 23/03/2006 								Fecha Última Modificación : 23/03/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtdenact".$ai_totrows." type=text   id=txtdenact".$ai_totrows." class=sin-borde size=25 maxlength=150 readonly>".
								   "<input name=txtcodact".$ai_totrows." type=hidden id=txtcodact".$ai_totrows." class=sin-borde size=17 maxlength=15 readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtidact".$ai_totrows." type=text id=txtidact".$ai_totrows." class=sin-borde size=17 maxlength=15 style=text-align:center readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtdesmov".$ai_totrows." type=text id=txtdesmov".$ai_totrows." class=sin-borde size=45 readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtmonact".$ai_totrows." type=text id=txtmonact".$ai_totrows." class=sin-borde size=15 style=text-align:right readonly>";
		$aa_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
	
	}

	function uf_agregarlineablancascg(&$aa_objectscg,$ai_totrowsscg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablancascg
		//         Access: private
		//      Argumento: $aa_objectscg // arreglo de titulos 
		//				   $ai_totrowsscg // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que agrega una linea en blanco al final del grid de cuenta contable
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 23/03/2006 								Fecha Última Modificación : 23/03/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_objectscg[$ai_totrowsscg][1] = "<input type=text   name=txtcontable".$ai_totrowsscg."   id=txtcontable".$ai_totrowsscg."  class=sin-borde style=text-align:center size=25 maxlength=25 readonly>".
										   "<input type=hidden name=txtcuentaact".$ai_totrowsscg."  id=txtcuentaact".$ai_totrowsscg.">".
										   "<input type=hidden name=txtcuentaide".$ai_totrowsscg."  id=txtcuentaide".$ai_totrowsscg.">";		
		$aa_objectscg[$ai_totrowsscg][2] = "<input type=text   name=txtdocscg".$ai_totrowsscg."     id=txtdocscg".$ai_totrowsscg."    class=sin-borde style=text-align:center size=17 maxlength=15 readonly>";
		$aa_objectscg[$ai_totrowsscg][3] = "<input type=text   name=txtdebhab".$ai_totrowsscg."     id=txtdebhab".$ai_totrowsscg."    class=sin-borde style=text-align:center size=8 maxlength=1 readonly>"; 
		$aa_objectscg[$ai_totrowsscg][4] = "<input type=text   name=txtmontocont".$ai_totrowsscg."  id=txtmontocont".$ai_totrowsscg." class=sin-borde style=text-align:right size=22 maxlength=22 readonly>";
		$aa_objectscg[$ai_totrowsscg][5] = "<a href=javascript:uf_delete_scg('".$ai_totrowsscg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
	
	}

	function uf_pintardetalle(&$lo_object,&$ai_totrows,&$ai_montot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pintardetalle
		//         Access: private
		//      Argumento: $aa_object // arreglo de objetos
		//				   $ai_totrows // ultima fila pintada en el grid
		//				   $ai_montot  // monto total del grid
		//	      Returns: 
		//    Description: Funcion que se encarga de repintar el detalle existente en el grid.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/04/2006 								Fecha Última Modificación : 11/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codact= $_POST["txtcodact1"];
		if($ls_codact!="")
		{
			for($li_i=1;$li_i<$ai_totrows;$li_i++)
			{
				$lb_valido=true;
				$ls_codact=    $_POST["txtcodact".$li_i];
				$ls_denact=    $_POST["txtdenact".$li_i];
				$ls_idact=     $_POST["txtidact".$li_i];
				$ls_desmov=    $_POST["txtdesmov".$li_i];
				$li_monact=    $_POST["txtmonact".$li_i];
				$li_monactaux= str_replace(".","",$li_monact);
				$li_monactaux= str_replace(",",".",$li_monactaux);
				$ai_montot=$ai_montot + $li_monactaux;
				if($ls_codact=="")
				{
				  $ai_totrows=$ai_totrows - 1;
				  //  $lb_valido=false;
					break;
				}
	
				$lo_object[$li_i][1]="<input name=txtdenact".$li_i." type=text   id=txtdenact".$li_i." class=sin-borde size=25 maxlength=150 value='".$ls_denact."' readonly>".
									 "<input name=txtcodact".$li_i." type=hidden id=txtcodact".$li_i." class=sin-borde size=17 maxlength=15 value='".$ls_codact."' readonly>";
				$lo_object[$li_i][2]="<input name=txtidact".$li_i."  type=text id=txtidact".$li_i."  class=sin-borde size=17 maxlength=15 value='". $ls_idact ."' readonly>";
				$lo_object[$li_i][3]="<input name=txtdesmov".$li_i." type=text id=txtdesmov".$li_i." class=sin-borde size=45 value='". $ls_desmov ."' readonly>";
				$lo_object[$li_i][4]="<input name=txtmonact".$li_i." type=text id=txtmonact".$li_i." class=sin-borde size=15 value='". $li_monact ."' readonly>";
				$lo_object[$li_i][5]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
			}
			uf_agregarlineablanca($lo_object,$ai_totrows);
		}
		if(!$lb_valido)
		{
			$ai_totrows=1;
			uf_agregarlineablanca($lo_object,$ai_totrows);
		}
	}
	
   	function uf_pintardetallescg(&$ao_objectscg,$ai_totrowsscg,&$ai_totdeb,&$ai_tothab)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pintardetallescg
		//         Access: private
		//      Argumento: $ao_objectscg // arreglo de objetos
		//				   $ai_totrowsscg // ultima fila pintada en el grid de cuentas contables
		//				   $ai_totdeb  // monto total del grid por el debe
		//				   $ai_tothab  // monto total del grid por el haber
		//	      Returns: 
		//    Description: Funcion que se encarga de repintar el detalle existente en el grid de cuentas contables.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/04/2006 								Fecha Última Modificación : 11/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		for($li_i=1;$li_i<$ai_totrowsscg;$li_i++)
		{
			$ls_sccuenta= $_POST["txtcontable".$li_i];
			$ls_cuentaact= $_POST["txtcuentaact".$li_i];
			$ls_cuentaide= $_POST["txtcuentaide".$li_i];
			$ls_docscg=  $_POST["txtdocscg".$li_i];
			$ls_debhab= $_POST["txtdebhab".$li_i];
			$li_montocont= $_POST["txtmontocont".$li_i];
			$li_montocontaux=    str_replace(".","",$li_montocont);
			$li_montocontaux=    str_replace(",",".",$li_montocontaux);
			if($ls_debhab=="D")
			{
				$ai_totdeb=$ai_totdeb+$li_montocontaux;
			}
			else
			{
				$ai_tothab=$ai_tothab+$li_montocontaux;
			}


			$ao_objectscg[$li_i][1] = "<input type=text   name=txtcontable".$li_i."   id=txtcontable".$li_i."  class=sin-borde  value='".$ls_sccuenta."' style=text-align:center size=25 maxlength=25 readonly>".
									  "<input type=hidden name=txtcuentaact".$li_i."  id=txtcuentaact".$li_i." value='".$ls_cuentaact."'>".
									  "<input type=hidden name=txtcuentaide".$li_i."  id=txtcuentaide".$li_i." value='".$ls_cuentaide."'>";		
			$ao_objectscg[$li_i][2] = "<input type=text   name=txtdocscg".$li_i."     id=txtdocscg".$li_i."    class=sin-borde  value='".$ls_docscg."' style=text-align:center size=17 maxlength=17 readonly>";
			$ao_objectscg[$li_i][3] = "<input type=text   name=txtdebhab".$li_i."     id=txtdebhab".$li_i."    class=sin-borde  value='".$ls_debhab."' style=text-align:center size=8 maxlength=1 readonly>"; 
			$ao_objectscg[$li_i][4] = "<input type=text   name=txtmontocont".$li_i."  id=txtmontocont".$li_i." class=sin-borde  value='".$li_montocont."' style=text-align:right size=22 maxlength=22 readonly> ";
			$ao_objectscg[$li_i][5] = "<a href=javascript:uf_delete_scg('".$li_i."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
		}
		uf_agregarlineablancascg($lo_objectscg,$ai_totrowsscg);
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Desincorporaciones</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
.Estilo1 {color: #6699CC}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Activos Fijos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
  <?php 
    if ($ls_rbtipocat == 1) 
    {
   ?>
   <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_csc.js"></script></td>
  <?php 
    }
	elseif ($ls_rbtipocat == 2)
	{
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_cgr.js"></script></td>
  <?php 
	}
	else
	{
   ?>
	<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php 
	}
   ?>	
   <!-- <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td> -->
  </tr>
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=     new sigesp_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=  new class_sql($con);
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fundb= new class_funciones_db($con);
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	require_once("../shared/class_folder/class_funciones.php");
	$io_fun= new class_funciones();
	require_once("sigesp_saf_c_movimiento.php");
	$io_saf= new sigesp_saf_c_movimiento();
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();

	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$li_totrows = uf_obtenervalor("totalfilas",1);	
	$li_totrowsscg = uf_obtenervalor("totalfilasscg",1);	
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		uf_limpiarvariables();
		uf_agregarlineablanca($lo_object,$li_totrows);
		uf_agregarlineablancascg($lo_objectscg,$li_totrowsscg);
		$ls_readonly="readonly";
	}

	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();
			$ls_readonly="";
			
			$ls_emp="";
			$ls_codemp="";
			$ls_tabla="saf_movimiento";
			$ls_columna="cmpmov";
			$ls_cmpmov=$io_fundb->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
			$lo_objectscg="";
			uf_agregarlineablanca($lo_object,1);
			uf_agregarlineablancascg($lo_objectscg,1);
		break;
		
		case "AGREGARDETALLE":
			uf_limpiarvariables();
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$li_totrowsscg = uf_obtenervalor("totalfilasscg",1);
			$li_totrows=$li_totrows+1;
			$li_totrowsscg=$li_totrowsscg+1;
			$ls_cmpmov=$_POST["txtcmpmov"];
			$ls_codcau=$_POST["txtcodcau"];
			$ls_dencau=$_POST["txtdencau"];
			$ld_feccmp=$_POST["txtfeccmp"];
			$ls_descmp=$_POST["txtdescmp"];
			$ls_status=$_POST["hidstatus"];
			uf_pintardetalle($lo_object,$li_totrows,$li_montot);
			uf_pintardetallescg($lo_objectscg,$li_totrowsscg,$li_totdeb,$li_tothab);
			$li_diferencia=abs($li_totdeb-$li_tothab);
			uf_agregarlineablancascg($lo_objectscg,$li_totrowsscg);
		break;
		case "GUARDAR":
			uf_limpiarvariables();
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$li_totrowsscg = uf_obtenervalor("totalfilasscg",1);
			$ls_codusureg=$_SESSION["la_logusr"];
			$ls_cmpmov=$_POST["txtcmpmov"];
			$ls_codcau=$_POST["txtcodcau"];
			$ls_dencau=$_POST["txtdencau"];
			$ld_feccmp=$_POST["txtfeccmp"];
			$ls_descmp=$_POST["txtdescmp"];
			$ls_status=$_POST["hidstatus"];
			$ld_date=date("Y-m-d");
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_date);
			if($lb_valido)
			{
				if(($ls_cmpmov!="")&&($ls_codcau!="")&&($li_totrows>1))
				{
					$ls_estpromov="0";
					$ls_codpro="----------";
					$ls_cedbene="----------";
					$ls_codtipdoc="";
					$ld_feccmpbd=$io_fun->uf_convertirdatetobd($ld_feccmp);
		
					$lb_existe=$io_saf->uf_saf_select_movimiento($ls_codemp,$ls_cmpmov,$ls_codcau,$ld_feccmpbd);
					if($lb_existe)
					{
						$li_totrows=1;
						uf_limpiarvariables();
						uf_agregarlineablanca($lo_object,1);
						$io_msg->message("El numero de comprobante ya existe");
						$lb_valido=false;
					}
					else
					{
						$ls_codrespri="";   $ls_codresuso="";    $ls_coduniadm="";
						$ls_ubigeo="";      $ls_tiprespri="";    $ls_tipresuso="";
						$ls_fecent="1900-01-01";
						$io_sql->begin_transaction();
						$lb_valido=$io_saf->uf_saf_insert_movimento($ls_codemp,$ls_cmpmov,$ls_codcau,$ld_feccmpbd,$ls_descmp,
						                                            $ls_codpro,$ls_cedbene,$ls_codtipdoc,$ls_codusureg,
																	$ls_estpromov,$la_seguridad,$ls_codrespri,$ls_codresuso,
																	$ls_coduniadm,$ls_ubigeo,$ls_tiprespri,$ls_tipresuso,
																	$ls_fecent);
						if($lb_valido)
						{
							for($li_i=1;$li_i<$li_totrows;$li_i++)
							{
								$ls_codact= $_POST["txtcodact".$li_i];
								$ls_denact= $_POST["txtdenact".$li_i];
								$ls_idact=  $_POST["txtidact".$li_i];
								$ls_desmov= $_POST["txtdesmov".$li_i];
								$li_monact= $_POST["txtmonact".$li_i];
								$li_monact= str_replace(".","",$li_monact);
								$li_monact= str_replace(",",".",$li_monact);
								$ls_estsoc=0;
								$ls_estmov="";
								$lb_valido=$io_saf->uf_saf_select_activos_contabilizado($ls_codemp,$ld_feccmpbd,$ls_codact);
								if($lb_valido)
								{
									$io_msg->message(" El Activo ".$ls_codact." esta contabilizado, ir a Modulos/Integrador/Activos Fijos/ Reversar");
								    $lb_valido=false;
								}
								else
								{
									$lb_valido=$io_saf->uf_saf_insert_dt_movimiento($ls_codemp,$ls_cmpmov,$ls_codcau,$ld_feccmpbd,$ls_codact,
																					$ls_idact,$ls_desmov,$li_monact,$ls_estsoc,$ls_estmov,
																					$la_seguridad);
									if($lb_valido)
									{
										$ls_estact="D";
										$lb_valido=$io_saf->uf_saf_update_dtadesincorporacion($ls_codemp,$ls_codact,$ls_idact,$ls_estact,
																							  $ld_feccmpbd,$la_seguridad);
										if(!$lb_valido)
										{break;}
									}
									else
									{break;}
								}	
							}			
							uf_pintardetalle($lo_object,$li_totrows,$li_montot);
							if($lb_valido)
							{
								for($li_i=1;$li_i<$li_totrowsscg;$li_i++)
								{
									$ls_sccuenta= $_POST["txtcontable".$li_i];
									$ls_cuentaact= $_POST["txtcuentaact".$li_i];
									$ls_cuentaide= $_POST["txtcuentaide".$li_i];
									$ls_docscg=  $_POST["txtdocscg".$li_i];
									$ls_debhab= $_POST["txtdebhab".$li_i];
									$li_montocont= $_POST["txtmontocont".$li_i];
									$li_montocontaux=    str_replace(".","",$li_montocont);
									$li_montocontaux=    str_replace(",",".",$li_montocontaux);
									$lb_existe=$io_saf->uf_saf_select_dt_cuentas($ls_codemp,$ls_cmpmov,$ls_codcau,$ld_feccmpbd,$ls_cuentaact,
																				 $ls_cuentaide,$ls_sccuenta,$ls_docscg);
									if(!$lb_existe)
									{
										$lb_valido=$io_saf->uf_saf_insert_dt_cuentas($ls_codemp,$ls_cmpmov,$ls_codcau,$ld_feccmpbd,$ls_codact,
																					 $ls_cuentaide,$ls_sccuenta,$ls_docscg,$ls_debhab,$li_montocontaux,
																					 $ls_cuentaact,$la_seguridad);
										if(!$lb_valido)
										{break;}
									}
									else
									{
										$lb_valido=false;
										$io_msg->message("El movimiento contable ya esta registrado");
										break;
									}
								}
							}
						}
						uf_pintardetallescg($lo_objectscg,$li_totrowsscg,$li_totdeb,$li_tothab);
						if($lb_valido)
						{
							$io_sql->commit();
							$io_msg->message("El registro fue incluido con exito");
							$ls_estpromov=0;
							uf_agregarlineablancascg($lo_objectscg,$li_totrowsscg);
							$ls_status=1;
						}
						else
						{
							$io_sql->rollback();
							$io_msg->message("No se pudo incluir el registro");
							uf_agregarlineablancascg($lo_objectscg,$li_totrowsscg);
						}
					}
				}
				else
				{
					if($li_totrows<=1)
					{
						$io_msg->message("El registro debe tener al menos 1 detalle");
						uf_agregarlineablanca($lo_object,1);
						uf_agregarlineablancascg($lo_objectscg,1);
					}
					else
					{
						$io_msg->message("Debe completar los datos");
						uf_pintardetalle($lo_object,$li_totrows,$li_montot);
						uf_pintardetallescg($lo_objectscg,$li_totrowsscg,$li_totdeb,$li_tothab);
						uf_agregarlineablancascg($lo_objectscg,$li_totrowsscg);
					}
				}			
			}
			else
			{
				$io_msg->message("El mes no esta abierto");
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
				uf_agregarlineablancascg($lo_objectscg,1);
				uf_limpiarvariables();
			}
		break;

		case "PROCESAR":
			$ls_cmpmov=$_POST["txtcmpmov"];
			$ls_codcau=$_POST["txtcodcau"];
			$ls_status=$_POST["hidstatus"];
			$ls_estpromov=1;
			$ld_date=date("Y-m-d");
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_date);
			if($lb_valido)
			{
				$io_sql->begin_transaction();
				$lb_valido=$io_saf->uf_saf_update_procesarincorporacion($ls_codemp,$ls_cmpmov,$ls_codcau,$ls_estpromov,$la_seguridad);
				if($lb_valido)
				{
					$io_sql->commit();
					$io_msg->message("El registro fue procesado con exito");
					uf_limpiarvariables();
					uf_agregarlineablanca($lo_object,1);
					uf_agregarlineablancascg($lo_objectscg,1);
					$li_totrows=1;
				}
				else
				{
					$io_sql->rollback();
					$io_msg->message("No se pudo procesar el registro");
					uf_limpiarvariables();
					uf_agregarlineablanca($lo_object,1);
					uf_agregarlineablancascg($lo_objectscg,1);
				}
			}
			else
			{
				$io_msg->message("El mes no esta abierto");
				uf_limpiarvariables();
				uf_agregarlineablanca($lo_object,1);
				uf_agregarlineablancascg($lo_objectscg,1);
			}
				
		break;

		case "REVERSAR":
			$ls_cmpmov=$_POST["txtcmpmov"];
			$ls_codcau=$_POST["txtcodcau"];
			$ls_status=$_POST["hidstatus"];
			$ls_estpromov=0;
			$ld_date=date("Y-m-d");
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_date);
			if($lb_valido)
			{
				$io_sql->begin_transaction();
				$lb_valido=$io_saf->uf_saf_update_procesarincorporacion($ls_codemp,$ls_cmpmov,$ls_codcau,$ls_estpromov,$la_seguridad);
				if($lb_valido)
				{
					$io_sql->commit();
					$io_msg->message("El registro fue reversado con exito");
					uf_agregarlineablanca($lo_object,1);
					uf_agregarlineablancascg($lo_objectscg,1);
					uf_limpiarvariables();
					$li_totrows=1;
				}
				else
				{
					$io_sql->rollback();
					$io_msg->message("No se pudo reversar el registro");
					uf_limpiarvariables();
					uf_agregarlineablanca($lo_object,1);
					uf_agregarlineablancascg($lo_objectscg,1);
				}
			}
			else
			{
				$io_msg->message("El mes no esta abierto");
				uf_limpiarvariables();
				uf_agregarlineablanca($lo_object,1);
				uf_agregarlineablancascg($lo_objectscg,1);
			}
		break;

		case "ELIMINARDETALLEACTIVO":
			uf_limpiarvariables();
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$ls_cmpmov=$_POST["txtcmpmov"];
			$ls_codcau=$_POST["txtcodcau"];
			$ls_dencau=$_POST["txtdencau"];
			$ls_dencau=$_POST["txtdencau"];
			$ld_feccmp=$_POST["txtfeccmp"];
			$ls_descmp=$_POST["txtdescmp"];
			$ls_status=$_POST["hidstatus"];
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp=$li_temp+1;			
					$ls_codact= $_POST["txtcodact".$li_i];
					$ls_denact= $_POST["txtdenact".$li_i];
					$ls_idact=  $_POST["txtidact".$li_i];
					$ls_desmov= $_POST["txtdesmov".$li_i];
					$li_monact= $_POST["txtmonact".$li_i];
					
					$lo_object[$li_temp][1]="<input name=txtdenact".$li_temp." type=text   id=txtdenact".$li_temp." class=sin-borde size=25 maxlength=150 value='".$ls_denact."' readonly>".
											"<input name=txtcodact".$li_temp." type=hidden id=txtcodact".$li_temp." class=sin-borde size=17 maxlength=15 value='".$ls_codact."' readonly>";
					$lo_object[$li_temp][2]="<input name=txtidact".$li_temp."  type=text   id=txtidact".$li_temp."  class=sin-borde size=17 maxlength=15 value='". $ls_idact ."' readonly>";
					$lo_object[$li_temp][3]="<input name=txtdesmov".$li_temp." type=text   id=txtdesmov".$li_temp." class=sin-borde size=45 value='". $ls_desmov ."' readonly>";
					$lo_object[$li_temp][4]="<input name=txtmonact".$li_temp." type=text   id=txtmonact".$li_temp." class=sin-borde size=15 value='". $li_monact ."' readonly>";
					$lo_object[$li_temp][5]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
				}
				else
				{
					$li_rowdelete= 0;
				}
			}
			uf_pintardetallescg($lo_objectscg,$li_totrowsscg,$li_totdeb,$li_tothab);
			$li_diferencia=abs($li_totdeb-$li_tothab);
			uf_agregarlineablancascg($lo_objectscg,$li_totrowsscg);
			if ($li_temp==0)
			{
				$li_totrows=1;
				//uf_agregarlineablanca($lo_object,$li_totrows);
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
		break;
	
		case "ELIMINARDETALLESCG":
			uf_limpiarvariables();
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$li_totrowsscg = uf_obtenervalor("totalfilasscg",1);
			$ls_cmpmov=$_POST["txtcmpmov"];
			$ls_codcau=$_POST["txtcodcau"];
			$ls_dencau=$_POST["txtdencau"];
			$ld_feccmp=$_POST["txtfeccmp"];
			$ls_descmp=$_POST["txtdescmp"];
			$ls_status=$_POST["hidstatus"];
			$li_totrowsscg=$li_totrowsscg-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			uf_pintardetalle($lo_object,$li_totrows,$li_montot);
			for($li_i=1;$li_i<=$li_totrowsscg;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp=$li_temp+1;			
					$ls_sccuenta=  $_POST["txtcontable".$li_i];
					$ls_cuentaact= $_POST["txtcuentaact".$li_i];
					$ls_cuentaide= $_POST["txtcuentaide".$li_i];
					$ls_docscg=    $_POST["txtdocscg".$li_i];
					$ls_debhab=    $_POST["txtdebhab".$li_i];
					$li_montocont= $_POST["txtmontocont".$li_i];
					$li_montocontaux=    str_replace(".","",$li_montocont);
					$li_montocontaux=    str_replace(",",".",$li_montocontaux);
					if($ls_debhab=="D")
					{
						$li_totdeb=$li_totdeb+$li_montocontaux;
					}
					else
					{
						$li_tothab=$li_tothab+$li_montocontaux;
					}
					
					$lo_objectscg[$li_temp][1] = "<input type=text   name=txtcontable".$li_temp."   id=txtcontable".$li_temp."  class=sin-borde  value='".$ls_sccuenta."' style=text-align:center size=25 maxlength=25 readonly>".
												 "<input type=hidden name=txtcuentaact".$li_temp."  id=txtcuentaact".$li_temp." value='".$ls_cuentaact."'>".
												 "<input type=hidden name=txtcuentaide".$li_temp."  id=txtcuentaide".$li_temp." value='".$ls_cuentaide."'>";		
					$lo_objectscg[$li_temp][2] = "<input type=text   name=txtdocscg".$li_temp."     id=txtdocscg".$li_temp."    class=sin-borde  value='".$ls_docscg."' style=text-align:center size=17 maxlength=15 readonly>";
					$lo_objectscg[$li_temp][3] = "<input type=text   name=txtdebhab".$li_temp."     id=txtdebhab".$li_temp."    class=sin-borde  value='".$ls_debhab."' style=text-align:center size=8 maxlength=1 readonly>"; 
					$lo_objectscg[$li_temp][4] = "<input type=text   name=txtmontocont".$li_temp."  id=txtmontocont".$li_temp." class=sin-borde  value='".$li_montocont."' style=text-align:right size=22 maxlength=22 readonly> ";
					$lo_objectscg[$li_temp][5] = "<a href=javascript:uf_delete_scg('".$li_temp."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
				}
				else
				{
					$li_rowdelete= 0;
				}
			}
			$li_diferencia=abs($li_totdeb-$li_tothab);
			uf_agregarlineablancascg($lo_objectscg,$li_totrowsscg);
			if ($li_temp==0)
			{
				$li_totrowsscg=1;
			}
			uf_agregarlineablancascg($lo_objectscg,$li_totrowsscg);
		break;

		case "BUSCARDETALLE":
			uf_limpiarvariables();
			$ls_cmpmov=$_POST["txtcmpmov"];
			$ls_codcau=$_POST["txtcodcau"];
			$ls_dencau=$_POST["txtdencau"];
			$ld_feccmp=$_POST["txtfeccmp"];
			$ls_estpromov=$_POST["hidestpromov"];
			$ls_status=$_POST["hidstatus"];
			$ld_feccmpbd=$io_fun->uf_convertirdatetobd($ld_feccmp);

			$lb_valido=$io_saf->uf_siv_load_dt_movimiento($ls_codemp,$ls_cmpmov,$ld_feccmpbd,$li_totrows,$lo_object,$li_montot);
			if ($lb_valido)
			{
				$lb_valido=$io_saf->uf_siv_load_dt_movimientocontable($ls_codemp,$ls_cmpmov,$ld_feccmpbd,$ls_codcau,$li_totrowsscg,
																	  $lo_objectscg,$li_totdeb,$li_tothab);
			}
			
		break;
	}

	
?>

<p>&nbsp;</p>
<div align="center">
  <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <table width="740" height="159" border="0" class="formato-blanco">
      <tr>
        <td width="724" ><div align="left">
            <table width="706" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td colspan="3" class="titulo-ventana">Desincorporaciones</td>
              </tr>
              <tr class="formato-blanco">
                <td width="110" height="19">&nbsp;</td>
                <td width="472"><div align="right">Fecha</div></td>
                <td width="122"><input name="txtfeccmp" type="text" id="txtfeccmp" style="text-align:center " value="<?php print $ld_feccmp ?>" size="13" maxlength="10" onKeyPress="ue_separadores(this,'/',patron,true);" datepicker="true"></td>
              </tr>
              <tr class="formato-blanco" style="visibility:hidden">
                <td height="20"><div align="right">Reporte en</div></td>
                <td height="20" colspan="2"><div align="left">
                    <select name="cmbbsf" id="cmbbsf">
                      <option value="0" selected>Bs.</option>
                      <option value="1">Bs.F.</option>
                    </select>
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="29"><div align="right">Comprobante</div></td>
                <td height="29" colspan="2">
                  <input name="txtcmpmov" type="text" id="txtcmpmov" value="<?php print $ls_cmpmov ?>" size="20" maxlength="15" onBlur="javascript: ue_rellenarcampo(this,'15')" style="text-align:center " readonly>
                  <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_status ?>"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="29"><div align="right">Causa de Movimiento</div></td>
                <td height="29" colspan="2"><input name="txtcodcau" type="text" id="txtcodcau" value="<?php print $ls_codcau ?>" size="10" style="text-align:center " readonly>
                  <a href="javascript: ue_catacausas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                <input name="txtdencau" type="text" class="sin-borde" id="txtdencau" value="<?php print $ls_dencau ?>" size="50" readonly></td>
              </tr>
              <tr class="formato-blanco">
                <td height="28"><div align="right">Observaciones</div></td>
                <td rowspan="2"><textarea name="txtdescmp" cols="60" rows="3" id="txtdescmp"  onKeyUp="javascript: ue_validarcomillas(this)"  onBlur="javascript: ue_validarcomillas(this)"><?php print $ls_descmp ?></textarea></td>
                <td style="visibility:hidden"><input name="btnprocesar" type="button" class="boton" value="Procesar"  style="width:80px "<?php if($ls_estpromov==0){ print "onClick='javascript: ue_procesar();'";}?>>
                <input name="hidestpromov" type="hidden" id="hidestpromov" value="<?php print $ls_estpromov ?>"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="28"><div align="right"></div></td>
                <td style="visibility:hidden"><input name="btnreversar" type="button" class="boton" value="Reversar" style="width:80px "  <?php if($ls_estpromov==1){ print "onClick='javascript: ue_reversar();'";}?> ></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22" colspan="3"><a href="javascript: ue_agregardetalle();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" class="sin-borde">Agregar Activo</a></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22" colspan="3"><div align="center">
                 <?php
					$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
				?>
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="21">&nbsp;</td>
                <td height="21"><div align="right">Total Monto </div></td>
                <td height="21"><input name="txtmontot" type="text" id="txtmontot" value="<?php print number_format($li_montot,2,",","."); ?>" size="20" style="text-align:right " readonly></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22" colspan="3"><a href="javascript: ue_agregardetallescg();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" class="sin-borde">Agregar Cuenta</a></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22" colspan="3" align="center">
                 <?php
					$in_grid->makegrid($li_totrowsscg,$lo_titlescg,$lo_objectscg,$li_widthtable,"Contable","Contable");
				?>
                 <input name="totalfilasscg" type="hidden" id="totalfilasscg" value="<?php print $li_totrowsscg;?>">
                 <input name="filadeletescg" type="hidden" id="filadeletescg"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22" align="center">&nbsp;</td>
                <td height="22" align="center"><div align="right">Total Debe </div></td>
                <td height="22" align="center"><div align="left">
                  <input name="txttotdeb" type="text" id="txttotdeb" value="<?php print number_format($li_totdeb,2,",","."); ?>" size="20" style="text-align:right " readonly>
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="21" align="center">&nbsp;</td>
                <td height="21" align="center"><div align="right">Total Haber</div></td>
                <td height="21" align="center"><div align="left">
                  <input name="txttothab" type="text" id="txttothab" value="<?php print number_format($li_tothab,2,",","."); ?>" size="20" style="text-align:right " readonly>
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="21" align="center">&nbsp;</td>
                <td height="21" align="center"><div align="right">Diferencia</div></td>
                <td height="21" align="center"><div align="left">
                  <input name="txtdiferencia" type="text" id="txtdiferencia" value="<?php print number_format($li_diferencia,2,",","."); ?>" size="20" style="text-align:right " readonly>
                </div></td>
              </tr>
            </table>
            <input name="operacion" type="hidden" id="operacion">
            <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
            <input name="filadelete" type="hidden" id="filadelete">
</div></td>
      </tr>
    </table>
  </form>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones 
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_saf_cat_desincorporaciones.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_agregardetalle()
{
	f=document.form1;
	ls_cmpmov=f.txtcmpmov.value;
	if(ls_cmpmov=="")
	{
		alert("Debe existir un numero de comprobante");
	}
	else
	{
		li_totrow=f.totalfilas.value;
		window.open("sigesp_saf_pdt_activocont.php?totrow="+ li_totrow +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=300,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_agregardetallescg()
{
	f=document.form1;
	ls_cmpmov=f.txtcmpmov.value;
	if(ls_cmpmov=="")
	{alert("Debe existir un numero de comprobante");}
	else
	{
		li_totrow=f.totalfilas.value;
		if(li_totrow>1)
		{
			li_totrow=f.totalfilas.value;
			window.open("sigesp_saf_pdt_cuentacont.php?totrow="+ li_totrow +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=280,left=50,top=50,location=no,resizable=yes");
		}
		else
		{alert ("Debe existir al menos un activo a desincorporar");}
	}
}

function ue_catacausas()
{
	tipo="D";
	window.open("sigesp_saf_cat_causasmovimiento.php?tipo="+tipo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="sigesp_saf_p_desincorporaciones.php";
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
	ls_diferencia=f.txtdiferencia.value;
	li_totrows=f.totalfilas.value;
	li_totrowsscg=f.totalfilasscg.value;
	ls_status=f.hidstatus.value;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	if(((ls_status=="C")&&(li_cambiar==1))||(ls_status=="")&&(li_incluir==1))
	{
		if(ls_status!="C")
		{
			if((ls_diferencia=="0,00")&&(li_totrowsscg>1))
			{
				if(li_totrows>1)
				{
					f.operacion.value="GUARDAR";
					f.action="sigesp_saf_p_desincorporaciones.php";
					f.submit();
				}
				else
				{alert("El movimiento debe tener al menos 1 detalle");}
			}
			else
			{alert("Asiento contable descuadrado");}
		}
		else
		{alert("Este documento no debe ser modificado");}
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{	
		f.operacion.value="PROCESAR";
		f.action="sigesp_saf_p_desincorporaciones.php";
		f.submit();
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_reversar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{	
		f.operacion.value="REVERSAR";
		f.action="sigesp_saf_p_desincorporaciones.php";
		f.submit();
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_delete_dt(li_row)
{
	f=document.form1;
	li_fila=f.totalfilas.value;
	if(li_fila!=li_row)
	{
		if(confirm("¿Desea eliminar el Registro actual?"))
		{	
			f.filadelete.value=li_row;
			f.operacion.value="ELIMINARDETALLEACTIVO"
			f.action="sigesp_saf_p_desincorporaciones.php";
			f.submit();
		}
	}
}

function uf_delete_scg(li_row)
{
	f=document.form1;
	li_fila=f.totalfilasscg.value;
	if(li_fila!=li_row)
	{
		if(confirm("¿Desea eliminar el Registro actual?"))
		{	
			f.filadelete.value=li_row;
			f.operacion.value="ELIMINARDETALLESCG"
			f.action="sigesp_saf_p_desincorporaciones.php";
			f.submit();
		}
	}
}

function ue_imprimir()
{
	f = document.form1;
	ls_status = f.hidstatus.value;
	ls_cmpmov = f.txtcmpmov.value;
	li_imprimir = f.imprimir.value;
	tipoformato = f.cmbbsf.value;
	if(ls_status=="C")
	{
		if (li_imprimir==1)
		{
			window.open("reportes/sigesp_saf_rfs_desincorporacion.php?cmpmov="+ls_cmpmov+"&tipoformato="+tipoformato,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("Seleccione un documento a imprimir");
	}
}


function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}
//--------------------------------------------------------
//	Función que coloca los separadores (/) de las fechas
//--------------------------------------------------------
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++){
			val2 += val[r]	
		}
		if(nums){
			for(z=0;z<val2.length;z++){
				if(isNaN(val2.charAt(z))){
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++){
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++){
			if(q ==0){
				val = val3[q]
			}
			else{
				if(val3[q] != ""){
					val += sep + val3[q]
					}
			}
		}
	d.value = val
	d.valant = val
	}
}
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>