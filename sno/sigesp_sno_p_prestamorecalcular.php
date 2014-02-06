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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_prestamorecalcular.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
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
   		global $ls_codper,$ls_nomper,$ls_codtippre,$ls_destippre,$ls_codconc,$ls_nomcon,$li_stapre,$la_stapre,$li_monpre,$li_amoprepre;
		global $li_numcuopre,$ls_perinipre,$li_salactpre,$li_moncuopre,$li_monamopre,$ld_fecdesper,$ld_fechasper,$li_numpre,$ls_desper;
		global $li_nuemoncuopre,$li_numcuofalpre,$li_cuopag,$li_sueper,$ls_obsrecpre,$ls_existe,$ls_operacion, $ls_desnom,$io_fun_nomina;
		global $ls_configuracion,$ls_tipocuota,$la_tipcuopre,$ls_tipcuopre,$li_valporpre;
		
		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();
		$lb_valido=$io_sno->uf_crear_sessionnomina();		
		$ls_desnom="";
		$ls_peractnom="";
		$ls_desper="";			
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
		}
		$ls_codper="";
		$ls_nomper="";
		$ls_codtippre="";
		$ls_destippre="";
		$ls_codconc="";
		$ls_nomcon="";
		$li_stapre="";
		$la_stapre[0]="";
		$la_stapre[1]="";
		$la_stapre[2]="";
		$ls_tipocuota=" disabled";
		$la_tipcuopre[0]="";
		$la_tipcuopre[1]="";
		$ls_tipcuopre="0";
		$li_monpre=0;
		$li_numcuopre=1;
		$li_amoprepre=0;
		$li_numpre="";
		$ls_perinipre="";
		$li_salactpre=0;
		$li_moncuopre=0;
		$li_monamopre=0;
		$li_numpre=0;
		$ld_fecdesper="dd/mm/aaaa";
		$ld_fechasper="dd/mm/aaaa";
		$li_numcuofalpre=1;
		$li_nuemoncuopre=0;
		$li_cuopag=0;
		$li_sueper=0;
		$ls_obsrecpre="";
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();			
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_configuracion=$io_sno->uf_select_config("SNO","CONFIG","CONFIGURACION_PRESTAMO","CUOTAS","C");
		$li_valporpre=trim($io_sno->uf_select_config("SNO","CONFIG","VAL_PORCENTAJE_PRESTAMO","1","I"));
		unset($io_sno);			
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
		// Fecha Creación: 30/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codper, $ls_nomper, $ls_codtippre, $ls_destippre, $ls_codconc, $ls_nomcon, $li_stapre, $li_monpre, $li_numcuopre;
		global $ls_perinipre, $li_salactpre, $li_moncuopre, $li_monamopre, $ld_fecdesper, $ld_fechasper, $li_numpre, $li_sueper;
		global $io_fun_nomina,$ls_tipcuopre;
	
		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];
		$ls_codtippre=$_POST["txtcodtippre"];
		$ls_destippre=$_POST["txtdestippre"];
		$ls_codconc=$_POST["txtcodconc"];
		$ls_nomcon=$_POST["txtnomcon"];
		$li_stapre=$_GET["cmbstapre"];
		$li_monpre=$_POST["txtmonpre"];
		$li_numcuopre=$_POST["txtnumcuopre"];
		$ls_perinipre=$_POST["txtperinipre"];
		$li_salactpre=$_POST["txtsalactpre"];
		$li_moncuopre=$_POST["txtmoncuopre"];
		$li_monamopre=$_POST["txtmonamopre"];
		$ld_fecdesper=$_POST["txtfecdesper"];
		$ld_fechasper=$_POST["txtfechasper"];
		$li_numpre=$_POST["txtnumpre"];
		$li_sueper=$_POST["txtsueper"];
		$ls_tipcuopre=$io_fun_nomina->uf_obtenervalor("cmbtipcuopre",$_POST["txttipcuopre"]);
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
<title >Recalcular Cuotas del Prestamo</title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php 
	require_once("sigesp_sno_c_prestamo.php");
	$io_prestamo=new sigesp_sno_c_prestamo();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_load_variables();
			$li_numcuofalpre=$_POST["txtcuofal"];
			$li_nuemoncuopre=$_POST["txtmoncuopre"];
			$li_cuopag=($li_numcuopre-$li_numcuofalpre);
			$io_fun_nomina->uf_seleccionarcombo("1-2-3",$li_stapre,$la_stapre,3);
			$io_fun_nomina->uf_seleccionarcombo("0-1",$ls_tipcuopre,$la_tipcuopre,2);
			$lb_valido=$io_prestamo->uf_select_resumen($ls_codper,$ls_codconc);
			if($lb_valido)
			{
				$io_prestamo->io_mensajes->message("Existen salidas para este personal y concepto. reverse la Nómina y vuelva a calcular");
			}
			break;
			
		case "PROCESAR":
			uf_load_variables();
			$li_cuopag=$_POST["txtcuopag"];
			$li_numcuofalpre=$_POST["txtnumcuofalpre"];
			$li_nuemoncuopre=$_POST["txtnuemoncuopre"];
			$ls_obsrecpre=$_POST["txtobsrecpre"];
			$lb_valido=$io_prestamo->uf_recalcularprestamo($ls_codper,$ls_codtippre,$li_numpre,$li_numcuofalpre,$li_nuemoncuopre,
														$li_sueper,$li_cuopag,$li_salactpre,$ls_obsrecpre,$li_numcuopre,
														$ls_configuracion,$ls_tipcuopre,$la_seguridad);
			if(!$lb_valido)
			{
				$io_fun_nomina->uf_seleccionarcombo("1-2-3",$li_stapre,$la_stapre,3);
				$io_fun_nomina->uf_seleccionarcombo("0-1",$ls_tipcuopre,$la_tipcuopre,2);
			}
			break;
	}
	$io_prestamo->uf_destructor();
	unset($io_prestamo);
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
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title='Ejecutar' alt="Ejecutar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_volver();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigesp_sno_p_prestamo.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="635" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">      <p>&nbsp;</p>
      <table width="585" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Informaci&oacute;n del  Prestamo</td>
        </tr>
        <tr>
          <td width="114" height="22">&nbsp;</td>
          <td width="462" colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Personal</div></td>
          <td><div align="left">
            <input name="txtcodper" type="text" id="txtcodper" size="13" maxlength="10" value="<?php print $ls_codper;?>" readonly>
            <input name="txtnomper" type="text" class="sin-borde" id="txtnomper" size="63" maxlength="100" value="<?php print $ls_nomper;?>" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Prestamo </div></td>
          <td>
            <div align="left">
              <input name="txtcodtippre" type="text" id="txtcodtippre" value="<?php print $ls_codtippre;?>" size="13" maxlength="10"  readonly>
              <input name="txtdestippre" type="text" class="sin-borde" id="txtdestippre" value="<?php print $ls_destippre;?>" size="63" maxlength="100" readonly>
                </div></td></tr>
        <tr>
          <td height="22"><div align="right">Concepto</div></td>
          <td>
            <div align="left">
              <input name="txtcodconc" type="text" id="txtcodconc" value="<?php print $ls_codconc;?>" size="13" maxlength="10" readonly>
              <input name="txtnomcon" type="text" class="sin-borde" id="txtnomcon" value="<?php print $ls_nomcon;?>" size="63" maxlength="30" readonly>
                </div></td></tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;odo de Inicio </div></td>
          <td><div align="left">
            <input name="txtperinipre" type="text" id="txtperinipre" value="<?php print $ls_perinipre;?>" size="6" maxlength="3" readonly>
            <input name="txtfecdesper" type="text" id="txtfecdesper" value="<?php print $ld_fecdesper;?>" size="13" maxlength="10" readonly>
            -
            <input name="txtfechasper" type="text" id="txtfechasper" value="<?php print $ld_fechasper;?>" size="13" maxlength="10" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Cuota </div></td>
          <td><div align="left">
            <select name="cmbtipcuopre" id="cmbtipcuopre" <?php print $ls_tipocuota; ?>>
              <option value="0" <?php print $la_tipcuopre[0]; ?>>Por periodo</option>
              <option value="1" <?php print $la_tipcuopre[1]; ?>>Mensual</option>
            </select>
            <input name="txttipcuopre" type="hidden" id="txttipcuopre" value="<?php print $ls_tipcuopre; ?>">
(Solo para n&oacute;minas quincenales)</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Estado Actual </div></td>
          <td>
            <div align="left">
              <select name="cmbstapre" id="cmbstapre" disabled>
                <option value="" selected>--Seleccione Uno--</option>
                <option value="1" <?php print $la_stapre[0]; ?>>Activo</option>
                <option value="2" <?php print $la_stapre[1]; ?>>Suspendido</option>
                <option value="3" <?php print $la_stapre[2]; ?>>Cancelado</option>
              </select>
                </div></td></tr>
        <tr>
          <td height="22"><div align="right">Monto Prestamo </div></td>
          <td>
            <div align="left">
              <input name="txtmonpre" type="text" id="txtmonpre" value="<?php print $li_monpre;?>" size="23" maxlength="20" style="text-align:right" readonly>
                </div></td></tr>
        <tr>
          <td height="22"><div align="right">Nro Cuotas </div></td>
          <td>
            <div align="left">
              <input name="txtnumcuopre" type="text" id="txtnumcuopre" value="<?php print $li_numcuopre;?>" size="7" maxlength="4" style="text-align:right" readonly>
                </div></td></tr>
        <tr>
          <td height="22"><div align="right">Monto Cuotas</div></td>
          <td>
            <div align="left">
              <input name="txtmoncuopre" type="text" id="txtmoncuopre" value="<?php print $li_moncuopre;?>" size="23" maxlength="20" style="text-align:right" readonly>
                </div></td></tr>
        <tr>
          <td height="22"><div align="right">Amortizado</div></td>
          <td>
            <div align="left">
              <input name="txtmonamopre" type="text" id="txtmonamopre" value="<?php print $li_monamopre;?>" size="23" maxlength="20" style="text-align:right" readonly>
                </div></td></tr>
        <tr>
          <td height="22"><div align="right">Saldo</div></td>
          <td>
            <div align="left">
              <input name="txtsalactpre" type="text" id="txtsalactpre" value="<?php print $li_salactpre;?>" size="23" maxlength="20" style="text-align:right" readonly>
                </div></td></tr>
        <tr>
          <td height="20" colspan="2" class="titulo-celdanew">                        
            <div align="center">Informaci&oacute;n para el Recalculo de cuotas </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Nro Cuotas a Faltantes</div></td>
          <td><div align="left">
            <input name="txtnumcuofalpre" type="text" id="txtnumcuofalpre" value="<?php print $li_numcuofalpre;?>" size="7" maxlength="4" style="text-align:right" onKeyPress="javascript: ue_validarnumero(this);" onfocus="javascript: ue_cambiarestatus();" <?php if($ls_configuracion=="MONTO"){ print " readonly ";}?>>
            <?php if($ls_configuracion=="CUOTAS"){
?>
            <a href="javascript: ue_actualizarcuota();"><img src="../shared/imagebank/tools20/actualizar.jpg" alt="Nuevo" width="15" height="15" border="0"></a>
            <?php }
?>
</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nuevo Monto Cuota </div></td>
          <td><div align="left">
            <input name="txtnuemoncuopre" type="text" id="txtnuemoncuopre" value="<?php print $li_nuemoncuopre;?>" size="23" maxlength="20" style="text-align:right" onKeyPress="javascript: return(ue_formatonumero(this,'.',',',event))" onfocus="javascript: ue_cambiarestatus();" <?php if($ls_configuracion=="CUOTAS"){ print " readonly ";} ?>>
            <?php if($ls_configuracion=="MONTO"){
?>
            <a href="javascript: ue_actualizarcuotamonto();"><img src="../shared/imagebank/tools20/actualizar.jpg" alt="Nuevo" width="15" height="15" border="0"></a>
            <?php }
?>
</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Observaci&oacute;n</div></td>
          <td><div align="left">
            <textarea name="txtobsrecpre" cols="70" rows="3" id="txtobsrecpre" onKeyUp="ue_validarcomillas(this);"><?php print $ls_obsrecpre;?></textarea>
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td><input name="operacion" type="hidden" id="operacion">
              <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
              <input name="txtnumpre" type="hidden" id="txtnumpre" value="<?php print $li_numpre;?>">
              <input name="txtsueper" type="hidden" id="txtsueper" value="<?php print $li_sueper;?>">
              <input name="txtcuopag" type="hidden" id="txtcuopag" value="<?php print $li_cuopag;?>">
              <input name="configuracion" type="hidden" id="configuracion" value="<?php print $ls_configuracion;?>">
              <input name="modificado" type="hidden" id="modificado" value="0">
              <input name="valporpre"  type="hidden" id="valporpre" value="<?php print $li_valporpre;?>"></td>
        </tr>
      </table>      
      <p>&nbsp;</p>      </td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_volver()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.existe.value="TRUE";	
	f.action="sigesp_sno_p_prestamo.php";
	f.submit();
}

function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		if(f.existe.value=="TRUE")
		{
			if(f.modificado.value=="1")
			{
				valido=true;
				codper = ue_validarvacio(f.txtcodper.value);
				codtippre = ue_validarvacio(f.txtcodtippre.value);
				numcuopre = ue_validarvacio(f.txtnumcuofalpre.value);
				obsrecpre = ue_validarvacio(f.txtobsrecpre.value);
				valporpre = ue_validarvacio(f.valporpre.value);
				if(valporpre==1)
				{
					sueper=f.txtsueper.value;
					sueper=(sueper*0.3);
					moncuopre=f.txtnuemoncuopre.value;
					while(moncuopre.indexOf('.')>0)
					{//Elimino todos los puntos o separadores de miles
						moncuopre=moncuopre.replace(".","");
					}
					moncuopre=moncuopre.replace(",",".");
					if(sueper<moncuopre)
					{
						valido=false;
						alert("El monto de la cuota no puede ser mayor que el 30% del sueldo");
					}
				}		
				if(valido)
				{
					if ((codper!="")&&(codtippre!="")&&(numcuopre!="")&&(numcuopre!="0")&&(obsrecpre!=""))
					{
						f.operacion.value="PROCESAR";
						f.action="sigesp_sno_p_prestamorecalcular.php?cmbstapre="+f.cmbstapre.value;
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
				alert("No se realizó una modificación");
			}
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_cambiarestatus()
{
	f=document.form1;
	f.modificado.value=0;
}

//--------------------------------------------------------
//	Función que calcula las cuotas mensuales
//--------------------------------------------------------
function ue_actualizarcuota()
{
	f=document.form1;
	monpre=f.txtsalactpre.value;
	ue_validarnumero(f.txtnumcuofalpre);
	numcuopre=f.txtnumcuofalpre.value;
	if(monpre=="")
	{
		monpre=0;
	}
	if((numcuopre!="")&&(numcuopre>0))
	{
		while(monpre.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			monpre=monpre.replace(".","");
		}
		monpre=monpre.replace(",",".");
	
		moncuopre=(monpre/numcuopre);
	
		if((monpre<0)||(moncuopre<0))
		{
			alert("El saldo está negativo!!!");
			f.txtnumcuofalpre.value=numcuopre;
		}
		else
		{
			monpre=uf_convertir(monpre);
			moncuopre=uf_convertir(moncuopre);
			f.txtnuemoncuopre.value=moncuopre;
			f.modificado.value=1;
		}
	}
	else
	{
		monpre=uf_convertir(monpre);
		f.txtsalactpre.value=monpre;
		f.txtnumcuofalpre.value=numcuopre;
		alert("Número de Cuotas Inválido");
	}
}

//--------------------------------------------------------
//	Función que calcula las cuotas mensuales por monto
//--------------------------------------------------------
function ue_actualizarcuotamonto()
{
	f=document.form1;
	monpre=f.txtsalactpre.value;
	moncuopre=f.txtnuemoncuopre.value;
	monamopre=f.txtmonamopre.value;
	if(monpre=="")
	{
		monpre=0;
	}
	while(moncuopre.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		moncuopre=moncuopre.replace(".","");
	}
	moncuopre=moncuopre.replace(",",".");
	if((moncuopre!="")&&(moncuopre>0))
	{
		while(monpre.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			monpre=monpre.replace(".","");
		}
		monpre=monpre.replace(",",".");
	
		while(monamopre.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			monamopre=monamopre.replace(".","");
		}
		monamopre=monamopre.replace(",",".");

		numcuopre=Math.ceil((monpre/moncuopre));
	
		if((monpre<0)||(numcuopre<0))
		{
			alert("El saldo está negativo!!!");
			f.txtnuemoncuopre.value=0;
			f.txtnumcuofalpre.value=0;
		}
		else
		{
			moncuopre=uf_convertir(moncuopre);
			f.txtnuemoncuopre.value=moncuopre;
			f.txtnumcuofalpre.value=numcuopre;
			f.modificado.value=1;
		}
	}
	else
	{
		f.txtnuemoncuopre.value="";
		f.txtnumcuofalpre.value="0";
		alert("Monto de Cuotas Inválido");
	}
}

</script> 
<?php
if ((($ls_operacion=="PROCESAR")&&($lb_valido))||(($ls_operacion=="NUEVO")&&($lb_valido)))
{
	print "<script language=JavaScript>";
	print "   ue_volver();";
	print "</script>";
}
?>		  
</html>