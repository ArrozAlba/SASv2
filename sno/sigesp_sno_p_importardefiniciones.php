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
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_importardefiniciones.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codnombus,$ls_desnombus,$ls_desnom,$la_personaldisp,$la_personalsele,$la_conceptodisp,$la_conceptosele;
		global $ls_repetidos,$ls_operacion,$io_fun_nomina,$ls_desper,$ls_espnom,$lb_personal,$li_calculada,$li_rac;
		
		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();
		$lb_valido=$io_sno->uf_crear_sessionnomina();		
		$ls_desnom="";
		$ls_peractnom="";
		$ls_desper="";			
		$ld_fecdesper="";
		$ld_fechasper="";
		if($lb_valido==false)
		{
			print "<script language=JavaScript>";
			print "location.href='sigespwindow_blank.php'";
			print "</script>";		
		}
		else
		{
			$ls_desnom=$_SESSION["la_nomina"]["desnom"];
			$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
			$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
			$ls_espnom=$_SESSION["la_nomina"]["espnom"];
		}
		$ls_codnombus="";
		$ls_desnombus="";
		$la_personaldisp="";
		$la_personalsele="";
		$la_conceptodisp="";
		$la_conceptosele="";
		$li_rac=$_SESSION["la_nomina"]["racnom"];
		$ls_repetidos=$io_sno->uf_select_config("SNO","CONFIG","NOPERMITIR_REPETIDOS","1","C");
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$lb_personal=false;
		if($ls_repetidos=="0")
		{
			$lb_personal=true;
		}
		elseif($ls_espnom=="1")
		{
			$lb_personal=true;
		}
		unset($io_sno);
		require_once("sigesp_sno_c_calcularnomina.php");
		$io_calcularnomina=new sigesp_sno_c_calcularnomina();
		$li_calculada=str_pad($io_calcularnomina->uf_existesalida(),1,"0");
		unset($io_calcularnomina);
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_print_lista($as_nombre,$as_campoclave,$as_campoimprimir,$aa_lista)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_lista
		//		   Access: private
		//      Arguments: as_nombre  // Nombre del Campo
		//      		   as_campoclave  // campo por medio del cual se va filtrar la lista
		//      		   as_campoimprimir  // campo que se va a mostrar
		//      		   aa_lista  // arreglo que se va a colocar en la lista
		//	  Description: Función que imprime 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		if(empty($aa_lista[$as_campoclave]))
		{
			$li_total=0;
		}
		else
		{
			$li_total=count($aa_lista[$as_campoclave]);
		}
		print "<select name='".$as_nombre."[]' id='".$as_nombre."' size='10' style='width:285px' multiple>";
		for($li_i=0;$li_i<$li_total;$li_i++)
		{
			print "<option value='".$aa_lista[$as_campoclave][$li_i]."'>".$aa_lista[$as_campoimprimir][$li_i];
		}
		print "</select>";
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
<title >Importar Definiciones</title>
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
</head>
<body>
<?php 
	require_once("sigesp_sno_c_importardefiniciones.php");
	$io_importar=new sigesp_sno_c_importardefiniciones();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "PROCESAR":
			$li_totper=0;
			$li_totcon=0;
			$ls_codnombus=$io_fun_nomina->uf_obtenervalor("txtcodnombus","");
			$ls_desnombus=$io_fun_nomina->uf_obtenervalor("txtdesnombus","");
			$ls_infrel=$io_fun_nomina->uf_obtenervalor("chkinfrel","0");
			$la_personalsele=$io_fun_nomina->uf_obtenervalor("txtpersonalsele","");
			$la_conceptosele=$io_fun_nomina->uf_obtenervalor("txtconceptosele","");
			$ls_codcar=str_pad($io_fun_nomina->uf_obtenervalor("txtcodcar","0000000000"),10,"0",0);
			
			if (array_key_exists('session_activa',$_SESSION))
			{	
				$ls_codasicar=str_pad($io_fun_nomina->uf_obtenervalor("txtcodasicar","0000000000"),10,"0",0);
			}
			else
			{
				$ls_codasicar=str_pad($io_fun_nomina->uf_obtenervalor("txtcodasicar","0000000"),7,"0",0);
			}
			
			$ls_codtab=str_pad($io_fun_nomina->uf_obtenervalor("txtcodtab","00000000000000000000"),20,"0",0);
			$ls_codpas=str_pad($io_fun_nomina->uf_obtenervalor("txtcodpas","00"),2,"0",0);
			$ls_codgra=str_pad($io_fun_nomina->uf_obtenervalor("txtcodgra","00"),2,"0",0);
			$li_sueper=str_pad($io_fun_nomina->uf_obtenervalor("txtsueper","0"),1,"0",0);
			if(!empty($la_personalsele))
			{
				$li_totper=count($la_personalsele);
			}
			if(!empty($la_conceptosele))
			{
				$li_totcon=count($la_conceptosele);
			}
			if($ls_infrel=="1")// Solo se importa la información que necesita el personal y el concepto
			{
				$lb_valido=$io_importar->uf_importardefiniciones($ls_codnombus,$la_personalsele,$li_totper,$la_conceptosele,
				                                                 $li_totcon,$ls_codcar,$ls_codasicar,$ls_codtab,$ls_codpas,
																 $ls_codgra,$li_sueper,$la_seguridad);
			}
			else// Se importa toda la información
			{
				$lb_valido=$io_importar->uf_importardefiniciones_lote($ls_codnombus,$la_personalsele,$li_totper,$la_conceptosele,
				                                                      $li_totcon,$ls_codcar,$ls_codasicar,$ls_codtab,$ls_codpas,
																 	  $ls_codgra,$li_sueper,$la_seguridad);
			}
			if($lb_valido)
			{
				$ls_codnom=$_SESSION["la_nomina"]["codnom"];
				$lb_valido=$io_importar->uf_update_ocupados($ls_codnom,$ls_codasicar,$la_seguridad);
			}
			$lb_valido=$io_importar->uf_load_nomina($ls_codnombus,$la_personaldisp,$la_conceptodisp);
			break;

		case "BUSCAR":
			$ls_codnombus=$io_fun_nomina->uf_obtenervalor("txtcodnombus","");
			$ls_desnombus=$io_fun_nomina->uf_obtenervalor("txtdesnombus","");
			$lb_valido=$io_importar->uf_load_nomina($ls_codnombus,$la_personaldisp,$la_conceptodisp);
			break;
	}
	$io_importar->uf_destructor();
	unset($io_importar);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
	  </table>
	</td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title='Ejecutar' alt="Ejecutar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Importar Definiciones </td>
        </tr>
        <tr>
          <td width="162" height="22">&nbsp;</td>
          <td width="532">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">N&oacute;mina </div></td>
          <td><div align="left">
            <input name="txtcodnombus" type="text" id="txtcodnombus" value="<?php print $ls_codnombus;?>" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarnomina();"><img id="concepto" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesnombus" type="text" class="sin-borde" id="txtdesnombus" value="<?php print $ls_desnombus;?>" size="80" maxlength="100" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Solo la informaci&oacute;n relacionada</div></td>
          <td><div align="left">
            <input name="chkinfrel" type="checkbox" id="chkinfrel" value="1" checked>
          </div></td>
        </tr>
	 <?php 
	  	   if(($li_rac=="0")&&($lb_personal))
		   {
	 ?>	  
        <tr>
          <td height="22"><div align="right">Cargo</div></td>
          <td><div align="left">
            <input name="txtcodcar" type="text" id="txtcodcar" size="13" maxlength="10"  readonly>
            <a href="javascript: ue_buscarcargo();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> &nbsp;
            <input name="txtdescar" type="text" class="sin-borde" id="txtdescar" size="65" maxlength="100" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Sueldo</div></td>
          <td><input name="txtsueper" type="text" id="txtsueper" maxlength="20" onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right"></td>
        </tr>
     <?php }
	 	   if(($li_rac=="1")&&($lb_personal))
		   {
	 ?>
        <tr>
          <td height="22"><div align="right">Asignaci&oacute;n de Cargo </div></td>
          <td><div align="left">
            <input name="txtcodasicar" type="text" id="txtcodasicar" size="10" maxlength="7"  readonly>
            <a href="javascript: ue_buscarasignacioncargo();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdenasicar" type="text" class="sin-borde" id="txtdenasicar" size="27" maxlength="24" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tabulador</div></td>
          <td><input name="txtcodtab" type="text" id="txtcodtab" size="25" maxlength="20" readonly>
&nbsp;
<input name="txtdestab" type="text" class="sin-borde" id="txtdestab" size="60" maxlength="100"></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Paso </div></td>
          <td><input name="txtcodpas" type="text" id="txtcodpas" size="18" maxlength="15" readonly>
            Grado 
            <input name="txtcodgra" type="text" id="txtcodgra" size="18" maxlength="15" readonly>
            <input name="txtsueper" type="hidden" id="txtsueper"></td>
        </tr>
     <?php }?>
        <tr>
          <td height="22" colspan="2">
<?php
	if($lb_personal)
	{
?>		  
		  <table width="650" border="0" align="center" cellpadding="1" cellspacing="0">
            <tr class="titulo-celdanew">
              <td width="295" height="22" class="titulo-celdanew">Personal Disponible </td>
              <td width="40" height="22" class="formato-blanco"><div align="center"></div></td>
              <td width="295" class="titulo-celdanew">Personal Seleccionado</td>
            </tr>
            <tr>
              <td width="295" rowspan="6"><div align="center"><?php uf_print_lista("txtpersonaldisp","codper","nomper",$la_personaldisp); ?></div></td>
              <td width="40" height="22"><div align="center"></div></td>
              <td width="295" rowspan="6"><div align="center"><?php uf_print_lista("txtpersonalsele","codper","nomper",$la_personalsele); ?></div></td>
            </tr>
            <tr>
              <td width="40" height="22">
                    <div align="center">
                      <input name="btnincluirpersonal" type="button" class="boton" id="btnincluirpersonal" style="width: 40px" value="&gt;" onClick="javascript: ue_pasar(form1.txtpersonaldisp,form1.txtpersonalsele);">
                    </div></td></tr>
            <tr>
              <td width="40" height="22">
                    <div align="center">
                      <input name="btnincluirpersonaltodos" type="button" class="boton" id="btnincluirpersonaltodos" style="width: 40px" value="&gt;&gt;" onClick="javascript: ue_pasartodos(form1.txtpersonaldisp,form1.txtpersonalsele);">
                    </div></td></tr>
            <tr>
              <td width="40" height="22">
                    <div align="center">
                      <input name="btnexcluirpersonal" type="button" class="boton" id="btnexcluirpersonal" style="width: 40px" value="&lt;"  onClick="javascript: ue_pasar(form1.txtpersonalsele,form1.txtpersonaldisp);">
                    </div></td></tr>
            <tr>
              <td width="40" height="22">
                    <div align="center">
                      <input name="btnexcluirpersonaltodos" type="button" class="boton" id="btnexcluirpersonaltodos" style="width: 40px" value="&lt;&lt;" onClick="javascript: ue_pasartodos(form1.txtpersonalsele,form1.txtpersonaldisp);">
                    </div></td></tr>
            <tr>
              <td width="40" height="22"><div align="center"></div></td>
              </tr>
          </table>
		  <p>&nbsp;</p>
<?php
	}
	else
	{
?>
	 	<input name="txtpersonalsele" type="hidden" id="txtpersonalsele" value="">
<?php	
	}
?>		  
            <table width="650" border="0" align="center" cellpadding="1" cellspacing="0">
              <tr class="titulo-celdanew">
                <td width="295" class="titulo-celdanew">Conceptos Disponibles </td>
                <td width="40" height="22" class="formato-blanco"><div align="center"></div></td>
                <td width="295" class="titulo-celdanew">Conceptos Seleccionados </td>
              </tr>
              <tr>
                <td width="295" rowspan="6"><div align="center"><?php uf_print_lista("txtconceptodisp","codconc","nomcon",$la_conceptodisp); ?></div></td>
                <td width="40" height="22"><div align="center"></div></td>
                <td width="295" rowspan="6"><div align="center"><?php uf_print_lista("txtconceptosele","codconc","nomcon",$la_conceptosele); ?></div></td>
              </tr>
              <tr>
                <td width="40" height="22">
                  <div align="center">
                    <input name="btnincluirconcepto" type="button" class="boton" id="btnincluirconcepto" style="width: 40px" value="&gt;" onClick="javascript: ue_pasar(form1.txtconceptodisp,form1.txtconceptosele);">
                  </div></td>
                </tr>
              <tr>
                <td width="40" height="22">
                  <div align="center">
                    <input name="btnincluirconceptotodos" type="button" class="boton" id="btnincluirconceptotodos" style="width: 40px" value="&gt;&gt;" onClick="javascript: ue_pasartodos(form1.txtconceptodisp,form1.txtconceptosele);">
                  </div></td>
                </tr>
              <tr>
                <td width="40" height="22">
                  <div align="center">
                    <input name="btnexcluirconcepto" type="button" class="boton" id="btnexcluirconcepto" style="width: 40px" value="&lt;"  onClick="javascript: ue_pasar(form1.txtconceptosele,form1.txtconceptodisp);">
                  </div></td>
                </tr>
              <tr>
                <td width="40" height="22">
                  <div align="center">
                    <input name="btnexcluirconceptotodos" type="button" class="boton" id="btnexcluirconceptotodos" style="width: 40px" value="&lt;&lt;" onClick="javascript: ue_pasartodos(form1.txtconceptosele,form1.txtconceptodisp);">
                  </div></td>
                </tr>
              <tr>
                <td width="40" height="22"><div align="center"></div></td>
                </tr>
            </table>			</td>
          </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td><input name="operacion" type="hidden" id="operacion">
		  <input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>"></td>
        </tr>
      </table>    
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_procesar()
{
	var totcon=0;
	var totper=0;
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_ejecutar=f.ejecutar.value;
		if(li_ejecutar==1)
		{
			codnom = ue_validarvacio(f.txtcodnombus.value);
			desnom = ue_validarvacio(f.txtdesnombus.value);
			totper = f.txtpersonalsele.length;
			totcon = f.txtconceptosele.length;
			if(f.txtpersonalsele!=null)
			{
				totper=f.txtpersonalsele.length;	
			}
			for(i=0;i<totper;i++)
			{
				f.txtpersonalsele[i].selected=true;
			}
			if(f.txtconceptosele!=null)
			{
				totcon=f.txtconceptosele.length;	
			}
			for(i=0;i<totcon;i++)
			{
				f.txtconceptosele[i].selected=true;
			}
			if ((codnom!="")&&(desnom!="")&&((totper>0)||(totcon>0)))
			{
				f.operacion.value="PROCESAR";
				f.action="sigesp_sno_p_importardefiniciones.php";
				f.submit();
			}
			else
			{
				alert("Debe llenar todos los datos.");
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
	location.href = "sigespwindow_blank_nomina.php";
}

function ue_buscarnomina()
{
	window.open("sigesp_snorh_cat_nomina.php?tipo=importar","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarasignacioncargo()
{
	window.open("sigesp_sno_cat_asignacioncargo.php?tipo=importar","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarcargo()
{
	window.open("sigesp_sno_cat_cargo.php?tipo=importar","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_pasar(obj_desde,obj_hasta)
{
	totdes=obj_desde.length;
	tothas=obj_hasta.length;
	for(i=0;i<totdes;i++)
	{
		if(obj_desde.options[i].selected)
		{
			asignar = new Option(obj_desde.options[i].text, obj_desde.options[i].value, false, false);
			asignados=obj_hasta.length;
			if (asignados< 1)
			{
				obj_hasta.options[asignados] = asignar;
			}
			else
			{
				obj_hasta.options[tothas] = asignar;
			}
			tothas=asignados + 1;
		}
	
	}
	ue_borrar_listaseleccionado(obj_desde);
}

function ue_pasartodos(obj_desde,obj_hasta)
{
	totdes=obj_desde.length;
	tothas=obj_hasta.length;
	for(i=0;i<totdes;i++)
	{
		asignar = new Option(obj_desde.options[i].text, obj_desde.options[i].value, false, false);
		asignados=obj_hasta.length;
		if (asignados< 1)
		{
			obj_hasta.options[asignados] = asignar;
		}
		else
		{
			obj_hasta.options[tothas] = asignar;
		}
		tothas=asignados + 1;
		
	}
	ue_borrar_listacompleta(obj_desde);
}

function ue_borrar_listacompleta(obj) 
{
	var  largo= obj.length;
	for (i=largo-1;i>=0;i--) 
	{	
		obj.options[i] = null;
	}
}

function ue_borrar_listaseleccionado(obj) 
{
	var largo= obj.length;
	var x;
	var count=0;
	arrSelected = new Array();
	for(i=0;i<largo;i++) // se coloca en el arreglo los campos seleccionados
	{	
		if(obj.options[i].selected) 
		{
			arrSelected[count]=obj.options[i].value;
		}
		count++;
	}
	for(i=0;i<largo;i++) // se colocan en null los que están en el arreglo
	{
		for(x=0;x<arrSelected.length;x++) 
		{
			if (obj.options[i].value==arrSelected[x]) 
			{
				obj.options[i]=null;
			}
		}
		largo = obj.length;
	}
}
</script> 
</html>