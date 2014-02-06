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
	require_once("class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$io_fun_cxp->uf_load_seguridad("CXP","sigesp_cxp_p_solicitudpago.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_reporte=$io_fun_cxp->uf_select_config("CXP","REPORTE","FORMATO_SOLPAG","sigesp_cxp_rfs_solicitudes.php","C");
	$ls_configuracion=$io_fun_cxp->uf_select_config("CXP","CONFIGURACION","VARIOS_REPORTES","","C");
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 19/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_estatus,$ld_fecemisol,$ls_codprovben,$ls_nomprovben,$ls_tipodestino,$ls_operacion,$ls_existe,$ls_rifproben;
		global $ls_totrowrecepciones,$io_fun_cxp,$ls_numsol,$ls_parametros,$ls_codfuefin,$ls_denfuefin,$ls_consol,$ls_obssol;
		global $ls_numordpagmin,$ls_codtipfon;
		
		$ls_estatus="REGISTRO";
		$ld_fecemisol=date("d/m/Y");
		$ls_numsol="";
		$ls_codprovben="";
		$ls_nomprovben=$ls_rifproben="";
		$ls_tipodestino="";
		$ls_parametros="";
		$ls_codfuefin="";
		$ls_denfuefin="";
		$ls_consol="";
		$ls_obssol="";
		$ls_numordpagmin="-";
		$ls_codtipfon="----";
		$ls_operacion=$io_fun_cxp->uf_obteneroperacion();
		$ls_existe=$io_fun_cxp->uf_obtenerexiste();
		$ls_totrowrecepciones=1;
   }
   //--------------------------------------------------------------------------------------------------------------

   //--------------------------------------------------------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 23/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_estsol,$ld_fecemisol,$ls_numsol,$ls_tipodestino,$ls_codprovben,$ls_nomprovben,$ls_consol,$ls_obssol;
		global $io_fun_cxp,$li_totrowrecepciones,$ls_codpro,$ls_cedbene,$ls_codfuefin,$li_monsol,$ls_rifproben;
		global $ls_numordpagmin,$ls_codtipfon;
		
		$ls_estsol=$_POST["txtestatus"];
		$ld_fecemisol=$io_fun_cxp->uf_obtenervalor("txtfecemisol",$_POST["txtfecha"]);
		$ls_numsol=$_POST["txtnumsol"];
		$li_monsol=$_POST["txtmonsol"];
		$ls_tipodestino=$io_fun_cxp->uf_obtenervalor("cmbtipdes",$_POST["txttipdes"]);
		$ls_codprovben=$_POST["txtcodigo"];
		$ls_nomprovben=$_POST["txtnombre"];
		$ls_rifproben = $_POST["txtrifproben"];
		$ls_consol=$_POST["txtconsol"];
		$ls_obssol=$_POST["txtobssol"];
		$ls_codfuefin=$_POST["txtcodfuefin"];
		$ls_numordpagmin=$_POST["txtnumordpagmin"];
		$ls_codtipfon=$_POST["txtcodtipfon"];
		if($ls_codfuefin=="")
		{
			$ls_codfuefin="--";
		}
		$li_totrowrecepciones=$_POST["totrowrecepciones"];
		if($ls_tipodestino=="P")
		{
			$ls_codpro=$ls_codprovben;
			$ls_cedbene="----------";
		}
		else
		{
			$ls_codpro="----------";
			$ls_cedbene=$ls_codprovben;
		}
   }
   //--------------------------------------------------------------------------------------------------------------

   //--------------------------------------------------------------------------------------------------------------
   function uf_load_data(&$as_parametros)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		global $li_totrowrecepciones,$io_fun_cxp;	
			
		for($li_i=1;$li_i<$li_totrowrecepciones;$li_i++)
		{
			$ls_numrecdoc=trim($io_fun_cxp->uf_obtenervalor("txtnumrecdoc".$li_i,""));
			$ls_codtipdoc=trim($io_fun_cxp->uf_obtenervalor("txtcodtipdoc".$li_i,""));
			$ls_dentipdoc=trim($io_fun_cxp->uf_obtenervalor("txtdentipdoc".$li_i,""));
			$li_montotdoc=trim($io_fun_cxp->uf_obtenervalor("txtmontotdoc".$li_i,"0,00"));
			$as_parametros=$as_parametros."&txtnumrecdoc".$li_i."=".$ls_numrecdoc."&txtcodtipdoc".$li_i."=".$ls_codtipdoc."".
					   					  "&txtdentipdoc".$li_i."=".$ls_dentipdoc."&txtmontotdoc".$li_i."=".$li_montotdoc."";
		}
		$as_parametros=$as_parametros."&totrowrecepciones=".$li_totrowrecepciones."";
   }
   //--------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Registro de Solicitud de Orden de Pago </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="javascript" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset="><style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style></head>
<body>
<?php
	require_once("class_folder/sigesp_cxp_c_solicitudpago.php");
	$io_cxp=new sigesp_cxp_c_solicitudpago("../");
	uf_limpiarvariables();
	switch($ls_operacion)
	{
		case "NUEVO":
			require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
			$io_keygen= new sigesp_c_generar_consecutivo();
			$ls_numsol= $io_keygen->uf_generar_numero_nuevo("CXP","cxp_solicitudes","numsol","CXPSOP",15,"numsolpag","","");
			if($ls_numsol===false)
			{
				print "<script language=JavaScript>";
				print "location.href='sigespwindow_blank.php'";
				print "</script>";		
			}
			unset($io_keygen);
			break;

		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_cxp->uf_guardar($ls_existe,&$ls_numsol,$ls_codpro,$ls_cedbene,$ls_codfuefin,$ls_tipodestino,
										   $ld_fecemisol,$ls_consol,$li_monsol,$ls_obssol,"E",
										   $li_totrowrecepciones,$la_seguridad,$la_permisos["administrador"],$ls_numordpagmin,$ls_codtipfon);
			uf_load_data(&$ls_parametros);
			if($lb_valido)
			{
				$ls_existe="TRUE";
			}
		break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_cxp->uf_delete_solicitud($ls_numsol,$ls_codpro,$ls_cedbene,$ld_fecemisol,$li_totrowrecepciones,$la_seguridad);
			if(!$lb_valido)
			{
				uf_load_data(&$ls_parametros);
			}
			else
			{
				uf_limpiarvariables();
			}
		break;
	}

?>
<table width="751" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="807" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7"><table width="739" border="0" align="center" cellpadding="0" cellspacing="0">
        <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Cuentas por Pagar </td>
            <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
        <tr>
          <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
          <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
    </table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="28" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0" title="Nuevo"></a></div></td>
    <td class="toolbar" width="28"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0" title="Guardar"></a></div></td>
    <td class="toolbar" width="28"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" title="Buscar"></a></div></td>
    <td class="toolbar" width="28"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0" title="Eliminar"></a></div></td>
    <td class="toolbar" width="28"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></td>
    <td class="toolbar" width="28"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="28"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
    <td class="toolbar" width="28"><div align="center"></div></td>
    <td class="toolbar" width="28"><div align="center"></div></td>
    <td class="toolbar" width="28"><div align="center"></div></td>
    <td class="toolbar" width="469">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
  <form name="formulario" method="post" action="" id="formulario">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cxp->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cxp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <table width="726" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="790"  height="136"><p>&nbsp;</p>
            <table width="721" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td colspan="4" class="titulo-ventana">Registro de Solicitud de Orden de Pago </td>
              </tr>
              <tr>
			  <?php 
			  	if($ls_configuracion=="1")
				{
			  ?>
                <td height="22"><div align="right">Formato de la Orden de Pago</div></td>
                <td height="22"><select name="cmbimpresion" id="cmbimpresion" onChange="javascript:ue_cambioformato();">
                  <option value="--">--SELECCIONE UNO--</option>
                  <option value="OP_ADMON">OP_ADMON</option>
                  <option value="OP_PERSONAS">OP_PERSONAS</option>
                  <option value="OP_CONTRALORIA">OP_CONTRALORIA</option>
                  <option value="OP_PRESIDENCIA">OP_PRESIDENCIA</option>
                </select></td>
				<?php 
				}
				?>
                <td height="22">&nbsp;</td>
                <td height="22">&nbsp;</td>
              </tr>
              <tr>
                <td width="165" height="22"><div align="right">Estatus</div></td>
                <td width="392" height="22"><input name="txtestatus" type="text" class="sin-borde2" id="txtestatus" value="<?php print $ls_estatus; ?>" size="40" readonly>                </td>
                <td width="109" height="22"><div align="right">Fecha de Emisi&oacute;n </div></td>
                <td width="119" height="22"><input name="txtfecemisol" type="text" id="txtfecemisol" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" value="<?php print $ld_fecemisol;?>" size="17"  style="text-align:center" datepicker="true"></td>
              </tr>
              <tr>
                <td height="22" align="right">Numero Solicitud </td>
                <td height="22" align="left"><input name="txtnumsol" type="text" id="txtnumsol" onBlur="javascript: ue_rellenarcampo(this,15);" value="<?php print $ls_numsol; ?>" maxlength="15" <?php if(($la_permisos["administrador"]!=1)||($ls_operacion!="NUEVO"))?> onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz'+'-/');" style="text-align:center"></td>
                <td height="22" colspan="2" style="text-align:left">&nbsp;</td>
              </tr>
              <tr>
                <td height="22" align="right">
                  <select name="cmbtipdes" id="cmbtipdes" onChange="javascript: ue_cambiardestino();">
                    <option value="-">---seleccione---</option>
                    <option value="P" <?php if($ls_tipodestino=="P"){ print "selected";} ?>>PROVEEDOR</option>
                    <option value="B" <?php if($ls_tipodestino=="B"){ print "selected";} ?>>BENEFICIARIO</option>
                  </select>                </td>
                <td height="22" align="left"><div align="left">
                  <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codprovben;?>" size="15" maxlength="10" readonly style="text-align:center">
                  <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" value="<?php print $ls_nomprovben;?>" size="45" maxlength="30" readonly>                
                </div></td>
                <td height="22" colspan="2" style="text-align:left"><strong>RIF: 
                  <label>
                  <input name="txtrifproben" type="text" class="sin-borde" id="txtrifproben" style="text-align:left" value="<?php echo $ls_rifproben; ?>" size="17" maxlength="12" readonly>
                  </label>
                </strong></td>
              </tr>
              <tr>
                <td height="22" align="right">Fuente de Financiamiento </td>
                <td height="22" colspan="3" align="left"><input name="txtcodfuefin" type="text" id="txtcodfuefin" style="text-align:center" value="<?php print $ls_codfuefin; ?>" size="10" readonly>
                  <a href="javascript: ue_catalogo('sigesp_cxp_cat_fuente.php');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                <input name="txtdenfuefin" type="text" class="sin-borde" id="txtdenfuefin" value="<?php print $ls_denfuefin; ?>" size="60"></td>
              </tr>
              <tr>
                <td height="13"><div align="right">Concepto</div></td>
                <td colspan="3" rowspan="2"><textarea name="txtconsol" cols="80" rows="3" id="txtconsol"><?php print $ls_consol; ?></textarea></td>
              </tr>
              <tr>
                <td height="13">&nbsp;</td>
              </tr>
              <tr>
                <td height="13"><div align="right">Observaci&oacute;n</div></td>
                <td colspan="3" rowspan="2"><textarea name="txtobssol" cols="80" rows="3" id="txtobssol"><?php print $ls_obssol; ?></textarea></td>
              </tr>
              <tr>
                <td height="13">&nbsp;</td>
              </tr>
              <tr>
                <td height="22" align="center"><div align="right">No. Orden de Pago Ministerio</div></td>
                <td height="13" align="center"><div align="left">
                  <input name="txtnumordpagmin" type="text" id="txtnumordpagmin" value="<?php print $ls_numordpagmin; ?>" size="19" maxlength="19" style="text-align:center" readonly>
                  <a href="javascript: ue_ordenpagoministerio();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
                <td height="13" align="center">&nbsp;</td>
                <td height="13" align="center">&nbsp;</td>
              </tr>
              <tr>
                <td height="13" colspan="4" align="center"><div id="recepciones"></div></td>
              </tr>
              <tr>
                <td height="13">&nbsp;</td>
                <td colspan="3">&nbsp;</td>
              </tr>
            </table>
        <p>
          <input name="operacion"  type="hidden" id="operacion"  value="<?php print $ls_operacion;?>">
          <input name="existe"     type="hidden" id="existe"     value="<?php print $ls_existe;?>">
          <input name="estapr"     type="hidden" id="estapr"     value="<?php print $li_estaprord;?>">
          <input name="parametros" type="hidden" id="parametros" value="<?php print $ls_parametros;?>">
          <input name="txttipdes"  type="hidden" id="txttipdes"  value="<?php print $ls_tipodestino; ?>">
          <input name="txtfecha"   type="hidden" id="txtfecha"   value="<?php print $ld_fecemisol; ?>">
          <input name="totrowrecepciones" type="hidden" id="totrowrecepciones" value="<?php print $li_totrowrecepciones;?>">
          <input name="formato"    type="hidden" id="formato"    value="<?php print $ls_reporte; ?>">
          <input name="txtcodtipfon" type="hidden" id="txtcodtipfon" value="<?php print $ls_codtipfon;?>">
        </p></td>
      </tr>
    </table>
</form>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);

function ue_nuevo()
{
	f=document.formulario;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";		
		f.action="sigesp_cxp_p_solicitudpago.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_guardar()
{
	f=document.formulario;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		valido=true;
		estapro=f.estapr.value;
		if(estapro=="1")
		{
			valido=false;
			alert("La solicitud esta aprobada no la puede modificar.");
		}
		// Obtenemos el total de filas de los Conceptos
		total=ue_calcular_total_fila_local("txtnumrecdoc");
		f.totrowrecepciones.value=total;
		numsol=ue_validarvacio(f.txtnumsol.value);
		fecha=ue_validarvacio(f.txtfecemisol.value);
		codigo=ue_validarvacio(f.txtcodigo.value);
		concepto=ue_validarvacio(f.txtconsol.value);
		tipodestino=ue_validarvacio(f.cmbtipdes.value);
		concepto=ue_validarvacio(f.txtconsol.value);
		totalgeneral=0;
		totalcuenta=0;
		totalcuentacargo=0;
		if(valido)
		{
			valido=ue_validarcampo(numsol,"El Número de Solicitud no puede estar vacio.",f.txtnumsol);
		}
		if(valido)
		{
			valido=ue_validarcampo(concepto,"La Unidad Ejecutora no puede estar vacia.",f.txtconsol);
		}
		if(valido)
		{
			valido=ue_validarcampo(fecha,"La Fecha no puede estar vacia.",f.txtfecemisol);
		}
		if(valido)
		{
			if(tipodestino=="B")
			{
				valido=ue_validarcampo(codigo,"Debe seleccionar un Beneficiario.",f.txtcodigo);
			}
			if(tipodestino=="P")
			{
				valido=ue_validarcampo(codigo,"Debe seleccionar un Proveedor.",f.txtcodigo);
			}
		}
		if(valido)
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_cxp_p_solicitudpago.php";
			f.submit();		
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación.");
   	}
}

function ue_buscar()
{
	f=document.formulario;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_cxp_cat_solicitudpago.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_eliminar()
{
	f=document.formulario;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			estapro=f.estapr.value;
			if(estapro=="1")
			{
				alert("La solicitud esta aprobada no la puede eliminar.");
			}
			else
			{
				// Obtenemos el total de filas de los bienes
				total=ue_calcular_total_fila_local("txtnumrecdoc");
				f.totrowrecepciones.value=total;
				numsol = ue_validarvacio(f.txtnumsol.value);
				if (numsol!="")
				{
					if(confirm("¿Desea eliminar el Registro actual?"))
					{
						f.operacion.value="ELIMINAR";
						f.action="sigesp_cxp_p_solicitudpago.php";
						f.submit();
					}
				}
				else
				{
					alert("Debe buscar el registro a eliminar.");
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

function ue_catalogo(ls_catalogo)
{
	// abre el catalogo que se paso por parametros
	window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_imprimir()
{
	f=document.formulario;
	li_imprimir=f.imprimir.value;
	lb_existe=f.existe.value;
	if(li_imprimir==1)
	{
		if(lb_existe=="TRUE")
		{
			numsol=f.txtnumsol.value;
			formato=f.formato.value;
			window.open("reportes/"+formato+"?numsol="+numsol+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{
			alert("Debe existir un documento a imprimir");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_cambiardestino()
{
	f=document.formulario;
	// Se verifica si el destino es un proveedor ó beneficiario y se carga el catalogo
	// dependiendo de esa información
	estapr=f.estapr.value;
	if(estapr=="1")
	{
		alert("La solicitud esta aprobada no la puede modificar.");
	}
	else
	{
		f.txtcodigo.value="";
		f.txtnombre.value="";
		tipdes=ue_validarvacio(f.cmbtipdes.value);
		if(tipdes!="-")
		{
			tipo="SOLICITUDPAGO";
			if(tipdes=="P")
			{
				window.open("sigesp_cxp_cat_proveedor.php?tipo="+tipo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
			}
			else
			{
				window.open("sigesp_cxp_cat_beneficiario.php?tipo="+tipo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
			}	
		}
		// Cargamos las variables para pasarlas al AJAX
		f.totrowrecepciones.value=1;
		// Div donde se van a cargar los resultados
		divgrid = document.getElementById('recepciones');
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_cxp_c_solicitudpago_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("totalbienes=1&proceso=LIMPIAR");
	}
}
function ue_cambioformato()
{
	f=document.formulario;
	tipdes=ue_validarvacio(f.cmbimpresion.value);
	if(tipdes!="--")
	{
		ls_proceso="CARGAR_REPORTE";
		parametros="&formato="+tipdes;
		ajax=objetoAjax();
		divgrid = document.getElementById('cuentas');
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_cxp_c_solicitudpago_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				texto=ajax.responseText;
				//divgrid.innerHTML = ajax.responseText;
				if(texto.indexOf("REPORTE->")!=-1)
				{
					posicion=texto.indexOf("REPORTE->")+9;
					lontitud=texto.length-posicion+9;
					formato=texto.substr(posicion,lontitud);
					f.formato.value=formato;
				}
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("proceso="+ls_proceso+parametros);
			
	}
}

function ue_catalogorecepciones()
{
	f=document.formulario;
	tipdes=f.cmbtipdes.value;
	codproben=f.txtcodigo.value;
	nombre=f.txtnombre.value;
	numordpagmin=f.txtnumordpagmin.value;
	codtipfon=f.txtcodtipfon.value;
	if(codproben!="")
	{
		window.open("sigesp_cxp_cat_recepcion.php?tipdes="+tipdes+"&codproben="+codproben+"&nombre="+nombre+"&numordpagmin="+numordpagmin+"&codtipfon="+codtipfon+"&tipo=SOLICITUDPAGO","_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("Debe seleccionar el Proveedor/Beneficiario");
	}
	
}
function ue_delete_recepcion(fila)
{
	f=document.formulario;
	estapro=f.estapr.value;
	if(estapro=="1")
	{
		alert("La solicitud esta aprobada no la puede modificar.");
	}
	else
	{
		if(confirm("¿Desea eliminar el Registro actual?"))
		{
			valido=true;
			parametros="";
			montobien=0;
			montocargo=0;
			//---------------------------------------------------------------------------------
			// Cargar los Cargos del opener y el seleccionado
			//---------------------------------------------------------------------------------
			total=ue_calcular_total_fila_local("txtnumrecdoc");
			f.totrowrecepciones.value=total;
			rowrecepciones=f.totrowrecepciones.value;
			montotal=0;
			li_i=0;
			for(j=1;(j<rowrecepciones)&&(valido);j++)
			{
				if(j!=fila)
				{
					li_i=li_i+1;
					numrecdoc=eval("document.formulario.txtnumrecdoc"+j+".value");
					dentipdoc=eval("document.formulario.txtdentipdoc"+j+".value");
					montotdoc=eval("document.formulario.txtmontotdoc"+j+".value");
					codtipdoc=eval("document.formulario.txtcodtipdoc"+j+".value");
					parametros=parametros+"&txtnumrecdoc"+li_i+"="+numrecdoc+"&txtdentipdoc"+li_i+"="+dentipdoc+"&txtmontotdoc"+li_i+"="+montotdoc+
							   "&txtcodtipdoc"+li_i+"="+codtipdoc;
					montotdocaux=ue_formato_calculo(montotdoc);
					montotal=eval(montotal+"+"+montotdocaux);
				}
			}
			li_i=li_i+1;
			totalrecepciones=eval(li_i);
			f.totrowrecepciones.value=totalrecepciones;	
			parametros=parametros+"&totrowrecepciones="+totalrecepciones+"&total="+montotal;
			if((parametros!="")&&(valido))
			{
				divgrid = document.getElementById("recepciones");
				ajax=objetoAjax();
				ajax.open("POST","class_folder/sigesp_cxp_c_solicitudpago_ajax.php",true);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4) {
						divgrid.innerHTML = ajax.responseText
					}
				}
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajax.send("proceso=AGREGARRECEPCIONES"+parametros);
			}
		}
	}
}

function ue_reload()
{
	f=document.formulario;
	parametros=f.parametros.value;
	f.txtfecemisol.disabled=true;
	f.cmbtipdes.disabled=true;
	proceso="AGREGARRECEPCIONES";
	if(parametros!="")
	{
		divgrid = document.getElementById("recepciones");
		ajax=objetoAjax();
		ajax.open("POST","class_folder/sigesp_cxp_c_solicitudpago_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("proceso="+proceso+"&cargarcargos=0"+parametros);
	}
}
function ue_ordenpagoministerio()
{
	f=document.formulario;
	total=ue_calcular_total_fila_local("txtnumrecdoc");
	tipo="SOLICITUDDEPAGO";
	if(total<=1)
	{
		window.open("sigesp_cxp_cat_ordenministerio.php?tipo="+tipo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=750,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Ya existen Recepciones de Documentos asociadas a la Solicitud de Pago");
	}
}


</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<?php
if(($ls_operacion=="GUARDAR")||(($ls_operacion=="ELIMINAR")&&(!$lb_valido)))
{
	print "<script language=JavaScript>";
	print "   ue_reload();";
	print "</script>";
}
?>		  
</html>
