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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_hmodificarpersonalnomina.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/11/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codper,$ls_nomper,$ls_estper, $ls_cueaboper,$ls_dencueaboper,$ls_codcueban,$ls_codban,$ls_nomban,$ls_codage;
		global $ls_nomage,$la_tipcuebanper,$ls_pagoefectivo,$ls_pagobanco,$ls_operacion,$io_fun_nomina,$ls_desnom,$ls_codnom;
		global $ls_desper,$li_contabilizado;
		
		require_once("sigesp_sno_c_ajustarcontabilizacion.php");
		$io_ajustar=new sigesp_sno_c_ajustarcontabilizacion();
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		$ls_codper="";
		$ls_nomper="";
		$ls_estper="";
		$ls_cueaboper="";
		$ls_dencueaboper="";
  	    $ls_codcueban="";
		$ls_codban="";
		$ls_nomban="";
		$ls_codage="";
		$ls_nomage="";
		$la_tipcuebanper[0]="";
		$la_tipcuebanper[1]="";
		$la_tipcuebanper[2]="";
		$ls_pagoefectivo="";
		$ls_pagobanco="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$li_contabilizado=$io_ajustar->uf_contabilizado();
		unset($io_ajustar);
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
		// Fecha Creación: 24/11/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codper,$ls_nomper,$ls_estper, $ls_cueaboper,$ls_dencueaboper,$ls_codcueban,$ls_codban,$ls_nomban,$ls_codage;
		global $ls_nomage,$ls_tipcuebanper,$li_pagefeper,$li_pagbanper,$io_fun_nomina;

		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];
		$ls_estper=$_POST["txtestper"];
		$ls_cueaboper=$_POST["txtcuecon"];
		$ls_dencueaboper=$_POST["txtdencuecon"];
		$ls_codcueban=$_POST["txtcodcueban"];
		$ls_codban=$_POST["txtcodban"];
		$ls_nomban=$_POST["txtnomban"];
		$ls_codage=$_POST["txtcodage"];
		$ls_nomage=$_POST["txtnomage"];
		$ls_tipcuebanper=$io_fun_nomina->uf_obtenervalor("cmbtipcuebanper","");
		$li_pagefeper=$io_fun_nomina->uf_obtenervalor("chkpagefeper","0");
		$li_pagbanper=$io_fun_nomina->uf_obtenervalor("chkpagbanper","0");
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
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.keyCode == 17 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title >Modificar Personal a N&oacute;mina</title>
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
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	require_once("sigesp_sno_c_personalnomina.php");
	$io_personalnomina=new sigesp_sno_c_personalnomina();
	uf_limpiarvariables();
	$ld_ano=substr($_SESSION["la_empresa"]["periodo"],0,4);
	$ld_fechasnom=substr($_SESSION["la_nomina"]["fechasper"],0,4);
	if($ld_fechasnom!=$ld_ano)
	{
		print("<script language=JavaScript>");
		print(" alert('Este proceso esta desactivo para Períodos de años Diferentes al Periodo de la Empresa.');");
		print(" location.href='sigespwindow_blank_hnomina.php'");
		print("</script>");
	}	
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_personalnomina->uf_update_personalnominahistorico($ls_codper,$li_pagefeper,$li_pagbanper,$ls_codban,
																			 $ls_codcueban,$ls_tipcuebanper,
											          						 $ls_cueaboper,$ls_codage,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("A-C-L",$ls_tipcuebanper,$la_tipcuebanper,3);
				$ls_pagobanco=$io_fun_nomina->uf_obtenervariable($li_pagbanper,1,0,"checked","","");
				$ls_pagoefectivo=$io_fun_nomina->uf_obtenervariable($li_pagefeper,1,0,"checked","","");				
			}
			break;
	}
	$io_personalnomina->uf_destructor();
	unset($io_personalnomina);
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
	  </table>	</td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_hnomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"></a><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"></a><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"></a></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_hnomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">      <p>&nbsp;</p>
      <table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr class="titulo-ventana">
        <td height="20" colspan="4" class="titulo-ventana">Modificar  Personal a N&oacute;mina </td>
      </tr>
      <tr>
        <td height="20" colspan="4" class="titulo-celdanew">Informaci&oacute;n de Personal </td>
      </tr>
      <tr>
        <td width="134" height="22"><div align="right">C&oacute;digo</div></td>
        <td colspan="3"><input name="txtcodper" type="text" id="txtcodper" value="<?php print $ls_codper;?>" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarpersonal();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="txtestper" type="text" class="sin-borde2" id="txtestper" value="<?php print $ls_estper;?>" size="20" maxlength="20" readonly></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre y Apellido </div></td>
        <td colspan="3"><input name="txtnomper" type="text" class="sin-borde" id="txtnomper" value="<?php print $ls_nomper;?>" size="90" maxlength="120" readonly></td>
      </tr>
      <tr class="titulo-celdanew">
        <td height="20" colspan="4"><div align="right" class="titulo-celdanew">Informaci&oacute;n de Pago</div></td>
        </tr>
      <tr>
        <td height="22"><div align="right">Pago en Efectivo &oacute; Cheque </div></td>
        <td width="152">
          <div align="left">
            <input name="chkpagefeper" type="checkbox" id="chkpagefeper" value="1"  onClick="javascript: ue_camposefectivo();" <?php print $ls_pagoefectivo;?>>
            </div></td>
        <td width="172"><div align="right">Pago por Banco </div></td>
        <td width="232"><div align="left">
          <input name="chkpagbanper" type="checkbox" id="chkpagbanper" value="1" onClick="javascript: ue_camposbanco();" <?php print $ls_pagobanco;?>>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Cuenta Abono</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcuecon" type="text" id="txtcuecon" value="<?php print $ls_cueaboper;?>" size="28" maxlength="25" readonly>
          <a href="javascript: ue_buscarcuentaabono();"><img id="cuentaabono" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
          <input name="txtdencuecon" type="text" class="sin-borde" id="txtdencuecon" value="<?php print $ls_dencueaboper;?>" size="50" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Banco</div></td>
        <td colspan="3">
          <div align="left">
            <input name="txtcodban" type="text" id="txtcodban" value="<?php print $ls_codban;?>" size="7" maxlength="4" readonly>
            <a href="javascript: ue_buscarbanco();"><img id="banco" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0" style="visibility:hidden "></a>
            <input name="txtnomban" type="text" class="sin-borde" id="txtnomban" value="<?php print $ls_nomban;?>" size="50" readonly>
            </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Agencia</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodage" type="text" id="txtcodage" value="<?php print $ls_codage;?>" size="13" maxlength="10" readonly>
          <a href="javascript: ue_buscaragencia();"><img id="agencia" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0" style="visibility:hidden "></a>
          <input name="txtnomage" type="text" class="sin-borde" id="txtnomage" value="<?php print $ls_nomage;?>" size="50" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nro de Cuenta </div></td>
        <td colspan="3">
          <div align="left">
            <input name="txtcodcueban" type="text" id="txtcodcueban" value="<?php print $ls_codcueban;?>" size="30" maxlength="25" onKeyUp="javascript: ue_validarnumero(this);" readonly>
            </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tipo de Cuenta </div></td>
        <td colspan="3"><div align="left">
          <select name="cmbtipcuebanper" id="cmbtipcuebanper" disabled>
            <option value="" selected>--Seleccione Una--</option>
            <option value="A" <?php print $la_tipcuebanper[0];?>>Ahorro</option>
            <option value="C" <?php print $la_tipcuebanper[1];?>>Corriente</option>
            <option value="L" <?php print $la_tipcuebanper[2];?>>Activos L&iacute;quidos</option>
          </select>
          
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td colspan="3"><input name="operacion" type="hidden" id="operacion">
		<input name="contabilizado" type="hidden" id="contabilizado" value="<?php print $li_contabilizado;?>"></td>
      </tr>
    </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_guardar()
{
	valido=true;
	f=document.form1;
	if(f.contabilizado.value=="0")
	{
		li_incluir=f.incluir.value;
		li_cambiar=f.cambiar.value;
		if(li_cambiar==1)
		{
			codper = ue_validarvacio(f.txtcodper.value);
			if (codper!="")
			{
				if (f.chkpagbanper.checked)
				{
					codban = ue_validarvacio(f.txtcodban.value);
					codage = ue_validarvacio(f.txtcodage.value);
					codcueban = ue_validarvacio(f.txtcodcueban.value);
					tipcueban = ue_validarvacio(f.cmbtipcuebanper.value);
					if (!((codban!="")&&(codage!="")&&(codcueban!="")&&(tipcueban!="")))
					{
						valido=false;
						alert("Debe llenar todos los datos del banco.");
					}
				}
				if (f.chkpagefeper.checked)
				{
					cueaboper = ue_validarvacio(f.txtcuecon.value);
					if (cueaboper=="")
					{
						valido=false;
						alert("Debe llenar la cuenta abono.");
					}
				}
				
				if((f.chkpagbanper.checked==false)&&(f.chkpagefeper.checked==false))
				{
					valido=false;
					alert("Debe seleccionar una forma de pago.");
				}
			}
			else
			{
				valido=false;
				alert("Debe seleccionar el personal.");
			}
			if(valido)
			{
					f.operacion.value="GUARDAR";
					f.action="sigesp_sno_p_hmodificarpersonalnomina.php";
					f.submit();			
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("El período está contabilizado. no se puede hacer ningún cambio.");
	}
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank_hnomina.php";
}

function ue_buscarpersonal()
{
	window.open("sigesp_sno_cat_hpersonalnomina.php?tipo=modpersonalhistorico","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}


function ue_buscarbanco()
{
	f=document.form1;
	if(f.chkpagbanper.checked)
	{
		window.open("sigesp_snorh_cat_banco.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscaragencia()
{
	f=document.form1;
	codban = ue_validarvacio(f.txtcodban.value);
	if(f.chkpagbanper.checked)
	{
		if(codban!="")
		{
			window.open("sigesp_snorh_cat_agencia.php?txtcodban="+codban+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
		}
		else
		{
			alert("Debe seleccionar el banco");
		}
	}
}

function ue_buscarcuentaabono()
{
	f=document.form1;
	if(f.chkpagefeper.checked)
	{
		window.open("sigesp_sno_cat_cuentacontable.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

//--------------------------------------------------------
//	Función que habilita campos de Banco
//--------------------------------------------------------
function ue_camposbanco()
{
	f=document.form1;
	if(f.chkpagbanper.checked)
	{
		f.cmbtipcuebanper.disabled=false;
		f.txtcodcueban.readOnly=false;
		document.images["banco"].style.visibility="visible";
		document.images["agencia"].style.visibility="visible";
		document.images["cuentaabono"].style.visibility="hidden";
		f.chkpagefeper.checked=false;
		f.txtcuecon.value="";
		f.txtdencuecon.value="";
	}
	else
	{
		f.cmbtipcuebanper.disabled=true;
		f.txtcodcueban.readOnly=true;
		document.images["banco"].style.visibility="hidden";
		document.images["agencia"].style.visibility="hidden";
		f.txtcodban.value="";
		f.txtnomban.value="";
		f.txtcodage.value="";
		f.txtnomage.value="";
		f.txtcodcueban.value="";
		f.cmbtipcuebanper.value="";
	}
}

//--------------------------------------------------------
//	Función que habilita campos de Banco
//--------------------------------------------------------
function ue_camposefectivo()
{
	f=document.form1;
	if(f.chkpagefeper.checked)
	{
		f.chkpagbanper.checked=false;
		f.cmbtipcuebanper.disabled=true;
		f.txtcodcueban.readOnly=true;
		document.images["cuentaabono"].style.visibility="visible";
		document.images["banco"].style.visibility="hidden";
		document.images["agencia"].style.visibility="hidden";
		f.txtcodban.value="";
		f.txtnomban.value="";
		f.txtcodage.value="";
		f.txtnomage.value="";
		f.txtcodcueban.value="";
		f.cmbtipcuebanper.value="";
	}
	else
	{
		document.images["cuentaabono"].style.visibility="hidden";
		f.txtcuecon.value="";
		f.txtdencuecon.value="";
	}
}
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script> 
</html>