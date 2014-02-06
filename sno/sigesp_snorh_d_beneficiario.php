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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_beneficiario.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: privatela_nacben
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/11/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codben, $ls_cedben, $ls_nomben, $ls_apeben, $ls_dirben, $ls_telben, $ls_tipben, $la_tipben, $ls_nomcheben, $ls_forpagben, $la_forpagben;
		global $li_porpagben, $li_monpagben, $ls_codban, $ls_nomban, $ls_ctaban, $ls_sccuenta, $ls_operacion, $ls_existe,$io_fun_nomina,$ls_nacben,$la_nacben;
		global $ls_tipcueben,$la_tipcueben,$la_nexben,$ls_cedaut,$ls_numexpben;
		
		$ls_codben="";
		$ls_cedben="";
		$ls_nomben="";
		$ls_apeben="";
		$ls_dirben="";
		$ls_telben="";
		$ls_nomcheben="";
		$li_porpagben="0,00";
		$li_monpagben="0,00";
		$ls_codban="";
		$ls_nomban="";
		$ls_ctaban="";
		$ls_sccuenta="";
		$ls_tipben="";
		$la_tipben[0]="";
		$la_tipben[1]="";
		$la_tipben[2]="";	
		$ls_forpagben="";
		$la_forpagben[0]="";
		$la_forpagben[1]="";
		$ls_nacben="";
		$la_nacben[0]="";
		$la_nacben[1]="";
		$ls_tipcueben="";
		$la_tipcueben[0]="";
		$la_tipcueben[1]="";
		$la_tipcueben[2]="";
		$la_nexben[0]="";
		$la_nexben[1]="";
		$la_nexben[2]="";
		$la_nexben[3]="";
		$ls_cedaut="";
		$ls_numexpben="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
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
		// Fecha Creación: 07/11/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codper, $ls_nomper, $ls_codben, $ls_cedben, $ls_nomben, $ls_apeben, $ls_dirben, $ls_telben, $ls_tipben, $ls_nomcheben;
		global $li_porpagben, $li_monpagben, $ls_codban, $ls_nomban, $ls_ctaban, $ls_sccuenta,$ls_forpagben,$ls_nacben,$ls_tipcueben,$ls_nexben,$ls_cedaut,$ls_numexpben;
		
		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];
		$ls_codben=$_POST["txtcodben"];
		$ls_cedben=$_POST["txtcedben"];
		$ls_nomben=$_POST["txtnomben"];
		$ls_apeben=$_POST["txtapeben"];
		$ls_dirben=$_POST["txtdirben"];
		$ls_telben=$_POST["txttelben"];
		$ls_tipben=$_POST["cmbtipben"];
		$ls_nacben=$_POST["cmbnacben"];
		$ls_tipcueben=$_POST["cmbtipcueben"];
		$ls_nomcheben=$_POST["txtnomcheben"];
		$li_porpagben=$_POST["txtporpagben"];
		$li_monpagben=$_POST["txtmonpagben"];
		$ls_codban=$_POST["txtcodban"];
		$ls_nomban=$_POST["txtnomban"];
		$ls_ctaban=$_POST["txtctaban"];
		$ls_forpagben=$_POST["cmbforpagben"];
		$ls_nexben=$_POST["cmbnexben"];
		$ls_cedaut=$_POST["txtcedaut"];
		$ls_numexpben=$_POST["txtnumexpben"];
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
<title >Definici&oacute;n de Beneficiarios</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_beneficiario.php");
	$io_beneficiario=new sigesp_snorh_c_beneficiario();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
		 	$ls_codper=$_GET["codper"];
			$ls_nomper=$_GET["nomper"];
			require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
			$io_keygen= new sigesp_c_generar_consecutivo();
			$ls_codben= $io_keygen->uf_generar_numero_nuevo("sno","sno_beneficiario","codben","SNOCNO",10,"","codper",$ls_codper);
			unset($io_keygen);
			break;

		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_beneficiario->uf_guardar($ls_existe,$ls_codper,$ls_codben,$ls_cedben,$ls_nomben,$ls_apeben,$ls_dirben,
													$ls_telben,$ls_tipben,$ls_nomcheben,$li_porpagben,$li_monpagben,$ls_codban,
													$ls_ctaban,$ls_forpagben,$ls_nacben,$ls_tipcueben,$ls_nexben,$ls_cedaut,$ls_numexpben,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
				require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
				$io_keygen= new sigesp_c_generar_consecutivo();
				$ls_codben= $io_keygen->uf_generar_numero_nuevo("sno","sno_beneficiario","codben","SNOCNO",10,"","codper",$ls_codper);
				unset($io_keygen);
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("0-1-2",$ls_tipben,$la_tipben,3);
				$io_fun_nomina->uf_seleccionarcombo("0-1",$ls_forpagben,$la_forpagben,2);
				$io_fun_nomina->uf_seleccionarcombo("V-E",$ls_nacben,$la_nacben,2);
				$io_fun_nomina->uf_seleccionarcombo("A-C-L",$ls_tipcueben,$la_tipcueben,3);
				$io_fun_nomina->uf_seleccionarcombo("C-H-P-E",$ls_nexben,$la_nexben,4);
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_beneficiario->uf_delete_beneficiario($ls_codper,$ls_codben,$ls_tipben,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
				require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
				$io_keygen= new sigesp_c_generar_consecutivo();
				$ls_codben= $io_keygen->uf_generar_numero_nuevo("sno","sno_beneficiario","codben","SNOCNO",10,"","codper",$ls_codper);
				unset($io_keygen);
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("0-1-2",$ls_tipben,$la_tipben,3);
				$io_fun_nomina->uf_seleccionarcombo("0-1",$ls_forpagben,$la_forpagben,2);
				$io_fun_nomina->uf_seleccionarcombo("V-E",$ls_nacben,$la_nacben,2);
				$io_fun_nomina->uf_seleccionarcombo("A-C-L",$ls_tipcueben,$la_tipcueben,3);
				$io_fun_nomina->uf_seleccionarcombo("C-H-P-E",$ls_nexben,$la_nexben,4);
			}
			break;
	}
	$io_beneficiario->uf_destructor();
	unset($io_beneficiario);
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
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif"  title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_volver();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigesp_snorh_d_personal.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="2"><input name="txtnomper" type="text" class="sin-borde2" id="txtnomper" value="<?php print $ls_nomper;?>" size="60" readonly>
            <input name="txtcodper" type="hidden" id="txtcodper" value="<?php print $ls_codper;?>"></td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Definici&oacute;n de Beneficiarios </td>
        </tr>
        <tr>
          <td width="166" height="22"><div align="right">C&oacute;digo</div></td>
          <td width="378"><div align="left">
            <input name="txtcodben" type="text" id="txtcodben" value="<?php print $ls_codben;?>" size="13" maxlength="10" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,10);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&eacute;dula</div></td>
          <td><div align="left">
            <input name="txtcedben" type="text" id="txtcedben" value="<?php print $ls_cedben;?>" size="13" maxlength="10" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre</div></td>
          <td><div align="left">
            <input name="txtnomben" type="text" id="txtnomben" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_nomben;?>" size="53" maxlength="50">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Apellido</div></td>
          <td><div align="left">
            <input name="txtapeben" type="text" id="txtapeben" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_apeben;?>" size="53" maxlength="50">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nacionalidad</div></td>
          <td><div align="left">
            <select name="cmbnacben" id="cmbnacben">
              <option value="" selected>--Seleccione Uno--</option>
              <option value="V" <?php print $la_nacben[0];?>>Venezolano</option>
              <option value="E" <?php print $la_nacben[1];?>>Extranjero</option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Direccion</div></td>
          <td><input name="txtdirben" type="text" id="txtdirben" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_dirben;?>" size="70" maxlength="100"></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Telefono</div></td>
          <td><input name="txttelben" type="text" id="txttelben" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_telben;?>" size="70" maxlength="80"></td>
        </tr>
        <tr>
           <td height="22"><div align="right">Parentesco </div></td>
           <td><div align="left">
            <select name="cmbnexben" id="cmbnexben">
              <option value="-" selected>--Sin Parentesco--</option>
              <option value="C" <?php print $la_nexben[0];?>>Conyuge</option>
              <option value="H" <?php print $la_nexben[1];?>>Hijo</option>
              <option value="P" <?php print $la_nexben[2];?>>Progenitor</option>
              <option value="E" <?php print $la_nexben[3];?>>Hermano</option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Beneficiario </div></td>
          <td><div align="left">
            <select name="cmbtipben" id="cmbtipben">
              <option value="" selected>--Seleccione Uno--</option>
              <option value="0" <?php print $la_tipben[0]; ?> >Pension Sobrevivientes</option>
              <option value="1" <?php print $la_tipben[1]; ?>>Pension Judicial</option>
	     	  <option value="2" <?php print $la_tipben[2]; ?>>Pension Alimenticia</option>
            </select>
          </div></td>
        </tr>
		
		<tr>
          <td height="22"><div align="right">Expediente</div></td>
          <td colspan="3"><div align="left">
            <input name="txtnumexpben" type="text" id="txtnumexpben" onKeyUp="javascript: ue_validarcomillas(this);"  value="<?php print $ls_numexpben;?>"  size="40" maxlength="40">
          </div></td>
        </tr>	
		
        <tr>
          <td height="22"><div align="right">Porcentaje que le Corresponde </div></td>
          <td><div align="left">
            <input name="txtporpagben" type="text" id="txtporpagben" value="<?php print $li_porpagben;?>" size="10" maxlength="6" onKeyPress="return(ue_formatonumero(this,'.',',',event))"  onBlur="javascript: ue_limpiar('0');" style="text-align:right">
            <strong>%</strong></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Monto que le corresponde </div></td>
          <td><div align="left">
            <input name="txtmonpagben" type="text" id="txtmonpagben" value="<?php print $li_monpagben;?>" size="23" maxlength="20" onKeyPress="return(ue_formatonumero(this,'.',',',event))" onBlur="javascript: ue_limpiar('1');" style="text-align:right">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Forma de Pago </div></td>
          <td><select name="cmbforpagben" id="cmbforpagben">
            <option value="" selected>--Seleccione Uno--</option>
            <option value="0" <?php print $la_forpagben[0]; ?> >Cheque</option>
            <option value="1" <?php print $la_forpagben[1]; ?> >Deposito en Cuenta</option>
                    </select></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre del cheque </div></td>
          <td><input name="txtnomcheben" type="text" id="txtnomcheben" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_nomcheben;?>" size="70" maxlength="80"></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cédula del Autorizado</div></td>
          <td><div align="left">
            <input name="txtcedaut" type="text" id="txtcedaut" value="<?php print $ls_cedaut;?>" size="13" maxlength="10" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Banco</div></td>
          <td><div align="left">
            <input name="txtcodban" type="text" id="txtcodban" value="<?php print $ls_codban;?>" size="7" maxlength="4" readonly>
            <a href="javascript: ue_buscarbanco();"><img id="banco" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtnomban" type="text" class="sin-borde" id="txtnomban" value="<?php print $ls_nomban;?>" size="50" readonly>
</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta de Banco </div></td>
          <td><div align="left">
            <input name="txtctaban" type="text" id="txtctaban" value="<?php print $ls_ctaban;?>" size="30" maxlength="25" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Cuenta</div></td>
          <td><select name="cmbtipcueben" id="cmbtipcueben">
            <option value="" selected>--Seleccione Una--</option>
            <option value="A" <?php print $la_tipcueben[0];?>>Ahorro</option>
            <option value="C" <?php print $la_tipcueben[1];?>>Corriente</option>
            <option value="L" <?php print $la_tipcueben[2];?>>Activos L&iacute;quidos</option>
          </select></td>
        </tr>
        <tr>
          <td height="22"><div align="right"></div></td>
          <td><input name="operacion" type="hidden" id="operacion">
          <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";	
		codper=ue_validarvacio(f.txtcodper.value);
		nomper=ue_validarvacio(f.txtnomper.value);	
		f.cmbtipben.disabled="";
		f.action="sigesp_snorh_d_beneficiario.php?codper="+codper+"&nomper="+nomper;
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_volver()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.existe.value="TRUE";	
	codper=ue_validarvacio(f.txtcodper.value);
	f.action="sigesp_snorh_d_personal.php?codper="+codper;
	f.submit();
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		valido=true;
		f.cmbtipben.disabled="";
		codper = ue_validarvacio(f.txtcodper.value);
		codben = ue_validarvacio(f.txtcodben.value);
		nomben = ue_validarvacio(f.txtnomben.value);
		apeben = ue_validarvacio(f.txtapeben.value);		
		tipben = ue_validarvacio(f.cmbtipben.value);
		forpagben = ue_validarvacio(f.cmbforpagben.value);
		if ((codper!="")&&(codben!="")&&(nomben!="")&&(apeben!="")&&(forpagben!="")&&(tipben!=""))
		{
			
			f.operacion.value="GUARDAR";
			f.action="sigesp_snorh_d_beneficiario.php";
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

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			codper = ue_validarvacio(f.txtcodper.value);
			codben = ue_validarvacio(f.txtcodben.value);
			if ((codper!="")&&(codben!=""))
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.cmbtipben.disabled="";
					f.operacion.value="ELIMINAR";
					f.action="sigesp_snorh_d_beneficiario.php";
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

function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_personal.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		codper = ue_validarvacio(f.txtcodper.value);
		window.open("sigesp_snorh_cat_beneficiario.php?codper="+codper+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_buscarbanco()
{
	window.open("sigesp_snorh_cat_banco.php?tipo=beneficiario","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_limpiar(tipo)
{
	f=document.form1;
	if(tipo=="0")
	{
		if(parseFloat(f.txtporpagben.value)>0)
		{
			f.txtmonpagben.value="0,00";
		}
	}
	if(tipo=="1")
	{
		if(parseFloat(f.txtmonpagben.value)>0)
		{
			f.txtporpagben.value="0,00";
		}
	}
}
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script> 
</html>
