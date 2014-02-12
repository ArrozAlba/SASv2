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
	require_once("class_folder/class_funciones_mis.php");
	$oi_fun_integrador=new class_funciones_mis();
	$oi_fun_integrador->uf_load_seguridad("MIS","sigesp_mis_p_contabiliza_asignacion_sob.php",$ls_permisos,$la_seguridad,$la_permisos);

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/04/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_operacion,$lo_title,$li_totrows,$oi_fun_integrador,$li_widthtable,$ls_titletable,$ls_nametable;
		global $ls_fecha;
		
		$ls_fecha=date("d/m/Y");	 
	    $lo_title[1]="";
		$lo_title[2]="Nº Asignación";
		$lo_title[3]="Fecha ";
		$lo_title[4]="Observación"; 
		$lo_title[5]="Detalle";
		$li_widthtable=700;
		$ls_titletable="Asignaciones por Contabilizar";
		$ls_nametable="grid";
		$ls_operacion =$oi_fun_integrador->uf_obteneroperacion();
		$li_totrows=$oi_fun_integrador->uf_obtenervalor("totalfilas",0);
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/04/2007 									Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_fecha,$ls_codasi,$ls_codobr,$ls_fecasi,$ls_codigo;
		
		$ls_fecha=$_POST["txtfecha"];
		$ls_codasi=$_POST["txtcodasi"];
		$ls_codobr=$_POST["txtcodobr"];
		$ls_fecasi=$_POST["txtfecasi"];
		$ls_codigo=$_POST["txtcodigo"];
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//		 Function: uf_agregarlineablanca
		//	    Arguments: aa_object  // arreglo de Objetos
		//		   	       ai_totrows  // total de Filas
		//	  Description: Función que agrega una linea mas en el grid
		// Fecha Creación: 124/04/2007 									Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px onClick='return false;'>";		
		$aa_object[$ai_totrows][2]="<input type=text name=txtcodasi".$ai_totrows." class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$aa_object[$ai_totrows][3]="<input type=text name=txtfecasi".$ai_totrows." class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
		$aa_object[$ai_totrows][4]="<input type=text name=txtobsasi".$ai_totrows." class=sin-borde readonly style=text-align:center size=18 maxlength=22>";
		$aa_object[$ai_totrows][5]="<div align='center'><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></div>";
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Contabilizaci&oacute;n de las Asignaciones</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo2 {font-size: 36px}
-->

</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/report.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion.js"></script>
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
<body>
<?php
	require_once("../shared/class_folder/grid_param.php");
	$io_grid = new grid_param();
	require_once("sigesp_mis_c_contabiliza.php");  
	$in_class_contabiliza = new sigesp_mis_c_contabiliza();
	require_once("class_folder/class_sigesp_sob_integracion.php");  	
	$in_class = new class_sigesp_sob_integracion();  
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

		case "PROCESAR":
			uf_load_variables();
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if(array_key_exists("chksel".$li_i,$_POST))
				{
					$ls_codasi=$_POST["txtcodasi".$li_i];
					$lb_valido=$in_class->uf_procesar_contabilizacion_asignacion($ls_codasi,$ls_fecha,$la_seguridad);
					if($lb_valido)
					{
						$in_class->io_msg->message("Asignación  ".$ls_codasi." fue contabilizada.");
					}
					else
					{
						$in_class->io_msg->message("No se pudo contabilizar la asignacion ".$ls_codasi);
					}		
				}
			}
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
		
		case "BUSCAR":
			uf_load_variables();
	    	$in_class_contabiliza->uf_select_sob_asignaciones_contabilizar($ls_codasi,$ls_codobr,$ls_fecasi,$ls_codigo,0,
																		   $lo_object,$li_totrows);
			if($li_totrows==0)
			{
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			break;
	}
	$in_class->uf_destroy_objects();
	unset($in_class);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="762" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7"><table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
        <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">M&oacute;dulo Integrador - <i>Obras</i></td>
            <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque&ntilde;as"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
        <tr>
          <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
          <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"></a><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Ejecutar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post" action="">
<p>
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$oi_fun_integrador->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($oi_fun_integrador);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
</p>
<p>&nbsp;</p>
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td colspan="2">Contabilizaci&oacute;n de Asignaci&oacute;n</td>
    </tr>
    <tr>
      <td  height="23"><div align="right">N&uacute;mero de Asignaci&oacute;n </div></td>
      <td ><div align="left">
        <input name="txtcodasi" type="text" id="txtcodasi" size="10" maxlength="6">
      </div></td>
    </tr>
    <tr>
      <td  height="23"><div align="right">N&uacute;mero de Obra </div></td>
      <td ><div align="left">
        <input name="txtcodobr" type="text" id="txtcodobr" size="10" maxlength="6">
      </div></td>
    </tr>
    <tr>
      <td  height="23"><div align="right">Proveedor</div></td>
      <td ><div align="left">
        <input name="txtcodigo" type="text" id="txtcodigo" size="15" maxlength="10" readonly>
        <a href="javascript: ue_buscardestino();"><img  src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
        <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" size="50" maxlength="30" readonly>
      </div></td>
    </tr>
    <tr>
      <td width="120"  height="23"><div align="right">Fecha de Asignaci&oacute;n </div></td>
      <td width="380" ><div align="left">
        <input name="txtfecasi" type="text" id="txtfecasi" size="14" maxlength="10" datepicker="true" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" >
      </div></td>
    </tr>
    <tr>
      <td  height="23"><div align="right">Fecha de Contabilizaci&oacute;n </div></td>
      <td ><div align="left">
        <input name="txtfecha" type="text" id="txtfecha" value="<?php print $ls_fecha?>" size="14" maxlength="10" datepicker="true" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" >
      </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
	  <?php
		 $io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
	  ?>
	  </div></td>
    </tr>
    <tr>
      <td><input name="operacion" type="hidden" id="operacion">
      <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);

function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		// Para verificar que se selecciono algun comprobante
		lb_valido=false;
		li_total=f.totalfilas.value;
		for(li_i=1;((li_i<=li_total)&&(lb_valido==false));li_i++)
		{
			lb_valido=eval("f.chksel"+li_i+".checked");
		}
		if(lb_valido)
		{
			f.operacion.value ="PROCESAR";
			f.action="sigesp_mis_p_contabiliza_asignacion_sob.php";
			f.submit();
		}
		else
		{
			alert("No hay nada que contabilizar.");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		f.operacion.value = "BUSCAR";
		f.action="sigesp_mis_p_contabiliza_asignacion_sob.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_verdetalle(codasi)
{
	Xpos=((screen.width/2)-(500/2)); 
	Ypos=((screen.height/2)-(400/2));
	window.open("sigesp_mis_pdt_sobasi.php?codasi="+codasi+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=500,height=400,left="+Xpos+",top="+Ypos+",location=no,resizable=no");
}

function ue_buscardestino()
{
	window.open("sigesp_mis_cat_proveedor.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>