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
	require_once("class_folder/class_funciones_scf.php");
	$variable="../";
	$io_fun_scf=new class_funciones_scf($variable);
	$io_fun_scf->uf_load_seguridad("SCF","sigesp_scf_p_cierremensual.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ld_fecha,$ls_procede,$ls_comprobante,$ls_descripcion,$ls_codprovben,$ls_nomprovben,$ls_operacion,$ls_existe;
		global $li_totrow,$ls_parametros,$ls_tipodestino,$ls_codban,$ls_ctaban,$ls_mes,$io_fun_scf;
		
		$ld_fecha=date("d/m/Y");
		$ls_procede="SCGCIE";
		$ls_mes="";
		$ls_comprobante="";
		$ls_descripcion="";
		$ls_codprovben="----------";
		$ls_nomprovben="";
		$ls_parametros="";
		$ls_tipodestino="-";
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$ls_operacion=$io_fun_scf->uf_obteneroperacion();
		$ls_existe=$io_fun_scf->uf_obtenerexiste();	
		$li_totrow=1;
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
		// Fecha Creación: 27/06/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ld_fecha,$ls_procede,$ls_comprobante,$ls_descripcion,$ls_operacion,$ls_existe;
		global $li_totrow,$ls_parametros,$li_totaldebe,$li_totalhaber,$ls_mes,$li_cierre_metodo2,$io_fun_scf;
		
		$ld_fecha=$_POST["txtfecha"];
		$ls_procede="SCGCIE";
		$ls_mes=$_POST["cmbmes"];
		$ls_comprobante=$_POST["txtcomprobante"];
		$ls_descripcion=$_POST["txtdescripcion"];
		$li_totaldebe=$io_fun_scf->uf_obtenervalor("txttotaldebe",0);
		$li_totalhaber=$io_fun_scf->uf_obtenervalor("txttotalhaber",0);
		$ls_operacion=$io_fun_scf->uf_obteneroperacion();
		$ls_existe=$io_fun_scf->uf_obtenerexiste();	
		$li_totrow=$_POST["totrow"];
		$li_cierre_metodo2=trim($io_fun_scf->uf_select_config("SCF","CIERREMENSUAL","METODO2","0","C"));
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_data(&$as_parametros)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $li_totrow;	
			
		for($li_i=1;($li_i<$li_totrow);$li_i++)
		{
			$ls_cuenta=$_POST["txtcuenta".$li_i];
			$ls_descripcion=$_POST["txtdescripcion".$li_i];
			$ls_procede=$_POST["txtprocede".$li_i];
			$ls_documento=$_POST["txtdocumento".$li_i];
			$li_mondeb=$_POST["txtmondeb".$li_i];
			$li_monhab=$_POST["txtmonhab".$li_i];
			$ls_debhab=$_POST["txtdebhab".$li_i];	
			$as_parametros=$as_parametros."&txtcuenta".$li_i."=".$ls_cuenta."&txtdescripcion".$li_i."=".$ls_descripcion."".
					   					  "&txtprocede".$li_i."=".$ls_procede."&txtdocumento".$li_i."=".$ls_documento."".
										  "&txtmondeb".$li_i."=".$li_mondeb."&txtmonhab".$li_i."=".$li_monhab."".
										  "&txtdebhab".$li_i."=".$ls_debhab;
		}
		$as_parametros=$as_parametros."&totrowscg=".$li_totrow."";
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
<title >Registro de Cierre Mensual</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scf.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/scf.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:9px;
	top:151px;
	width:214px;
	height:28px;
	z-index:1;
}
-->
</style>
</head>
<body>
<?php 
	require_once("class_folder/sigesp_scf_c_cierre.php");
	$io_scg=new sigesp_scf_c_cierre("../");
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_scg->uf_delete($ls_comprobante,$ld_fecha,$ls_procede,$ls_codprovben,$ls_tipodestino,$ls_codban,
										  $ls_ctaban,$la_seguridad);
			if(!$lb_valido)
			{
				uf_load_data(&$ls_parametros);
				$ls_existe="TRUE";
			}
			else
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;
			
		case "PROCESAR":
			uf_load_variables();
			if($li_cierre_metodo2=="0")
			{
				$lb_valido=$io_scg->uf_generarcomprobantecierremensual($ls_mes,$ls_procede,$ls_codban,$ls_ctaban,$ls_tipodestino,
																	   $ls_codprovben,&$ls_comprobante,&$ld_fecha,&$ls_descripcion,
																	   $la_seguridad);
			}
			else
			{
				$lb_valido=$io_scg->uf_generarcomprobantecierremensual_metodo2($ls_mes,$ls_procede,$ls_codban,$ls_ctaban,
																			   $ls_tipodestino,$ls_codprovben,&$ls_comprobante,
																			   &$ld_fecha,&$ls_descripcion,$la_seguridad);
			}
			if($lb_valido)
			{
				$ls_existe="TRUE";
			}
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
			
            <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Contabilidad Fiscal </td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>    </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar-off.gif" alt="Nuevo" width="20" height="20" border="0" title="Nuevo"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"></a><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" title="Buscar"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0" title="Eliminar"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="25"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a><a href="javascript: ue_imprimir();"></a></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form action="" method="post" name="formulario" id="formulario">
  <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_scf->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_scf);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="770" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
    <td width="760" height="136"><p>&nbsp;</p>
      <table width="720" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr> 
            <td colspan="4" class="titulo-ventana">Registro de Cierre Mensual </td>
          </tr>
          <tr>
            <td height="22"><div align="right">Mes de Cierre </div></td>
            <td>
              <select name="cmbmes" id="cmbmes">
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
                <option value="05">05</option>
                <option value="06">06</option>
                <option value="07">07</option>
                <option value="08">08</option>
                <option value="09">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
              </select>
           	</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td width="203" height="22"><div align="right">Procedencia</div></td>
            <td width="354"><input name="txtprocede" type="text" id="txtprocede" value="<?php print $ls_procede;?>" size="10" maxlength="15"  readonly></td>
            <td width="89"><div align="right">Fecha</div></td>
            <td width="202"><input name="txtfecha" type="text" id="txtfecha" style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ld_fecha;?>" size="15"  readonly></td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Comprobante</div></td>
            <td height="22" colspan="3">
              <input name="txtcomprobante" type="text" id="txtcomprobante" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_comprobante;?>" size="20" maxlength="15" readonly>            </td>
          </tr>
          <tr> 
            <td height="24"> <div align="right">Descripci&oacute;n</div></td>
            <td height="22" colspan="3" rowspan="2"><textarea name="txtdescripcion" cols="90" rows="3" id="txtdescripcion" onKeyUp="ue_validarcomillas(this);" readonly><?php print $ls_descripcion;?></textarea></td>
          </tr>
          <tr> 
            <td height="11">&nbsp;</td>
          </tr>
        </table>
        <table width="720" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr> 
            <td align="center"><div id="cuentas"></div></td>
          </tr>
        </table>
        <p> 
          <input name="operacion"   type="hidden" id="operacion"   value="<?php print $ls_operacion;?>">
          <input name="existe"      type="hidden" id="existe"      value="<?php print $ls_existe;?>">
          <input name="totrow"      type="hidden" id="totrow"      value="<?php print $li_totrow;?>">
          <input name="parametros"  type="hidden" id="parametros"  value="<?php print $ls_parametros;?>">
          <input name="txtfechacon" type="hidden" id="txtfechacon" value="<?php print $ld_fecha; ?>">
          <input name="txtcodban"   type="hidden" id="txtcodban"   value="<?php print $ls_codban;?>">
          <input name="txtctaban"   type="hidden" id="txtctaban"   value="<?php print $ls_ctaban;?>">
        </p></td>
    </tr>
</table>
</form>   
<?php
	$io_scg->uf_destructor();
	unset($io_scg);
?>   
<p>&nbsp;</p>
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
		f.operacion.value="PROCESAR";
		f.existe.value="FALSE";		
		f.action="sigesp_scf_p_cierremensual.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_procesar()
{
	f=document.formulario;
	li_procesar=f.ejecutar.value;
	if(li_procesar==1)
	{	
		mes=f.cmbmes.value;
		if(confirm("¿Desea Generar el Cierre del Mes "+mes+"?"))
		{
			f.operacion.value="PROCESAR";
			f.existe.value="FALSE";		
			f.action="sigesp_scf_p_cierremensual.php";
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
	f=document.formulario;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			procede=f.txtprocede.value;
			if(procede=="SCGCIE")
			{
				// Obtenemos el total de filas de los bienes
				total=ue_calcular_total_fila_local("txtcuenta");
				f.totrow.value=total;
				comprobante = ue_validarvacio(f.txtcomprobante.value);
				if (comprobante!="")
				{
					if(confirm("¿Desea eliminar el Registro actual?"))
					{
						f.operacion.value="ELIMINAR";
						f.action="sigesp_scf_p_cierremensual.php";
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

function ue_buscar()
{
	f=document.formulario;
	li_leer=f.leer.value;
	procede=f.txtprocede.value;
	if (li_leer==1)
   	{
		window.open("sigesp_scf_cat_comprobante.php?procede="+procede+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_reload()
{
	f=document.formulario;
	parametros=f.parametros.value;
	if(parametros!="")
	{
		divgrid = document.getElementById("cuentas");
		ajax=objetoAjax();
		ajax.open("POST","class_folder/sigesp_scf_c_cierre_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("proceso=PROCESAR"+parametros);
	}
}

function ue_load()
{
	f=document.formulario;
	parametros="";
	ue_rellenarcampo(f.txtcomprobante,15);
	comprobante=f.txtcomprobante.value;
	fecha=f.txtfecha.value;
	procede=f.txtprocede.value;
	f.totrow.value=0;
	parametros="&comprobante="+comprobante+"&fecha="+fecha+"&procede="+procede;
	if(parametros!="")
	{
		divgrid = document.getElementById("cuentas");
		ajax=objetoAjax();
		ajax.open("POST","class_folder/sigesp_scf_c_cierre_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("proceso=LOADCOMPROBANTE"+parametros);
	}
}

</script> 
<?php
if(($ls_operacion=="GUARDAR")||(($ls_operacion=="ELIMINAR")&&(!$lb_valido)))
{
	print "<script language=JavaScript>";
	print "   ue_reload();";
	print "</script>";
}
if(($ls_operacion=="PROCESAR")&&($lb_valido))
{
	print "<script language=JavaScript>";
	print "   ue_load();";
	print "</script>";
}
?>		  
</html>