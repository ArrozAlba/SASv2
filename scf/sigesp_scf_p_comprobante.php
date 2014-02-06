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
	$io_fun_scf=new class_funciones_scf("../");
	$io_fun_scf->uf_load_seguridad("SCF","sigesp_scf_p_comprobante.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		global $li_totrow,$ls_parametros,$ls_tipodestino,$ls_codban,$ls_ctaban,$io_fun_scf;
		
		$ld_fecha=date("d/m/Y");
		$ls_procede="SCGCMP";
		$ls_comprobante="";
		$ls_descripcion="";
		$ls_codprovben="";
		$ls_nomprovben="";
		$ls_parametros="";
		$ls_tipodestino="";
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
   		global $ld_fecha,$ls_procede,$ls_comprobante,$ls_descripcion,$ls_codprovben,$ls_nomprovben,$ls_operacion,$ls_existe;
		global $li_totrow,$ls_parametros,$ls_tipodestino,$li_totaldebe,$li_totalhaber,$io_fun_scf;
		
		$ld_fecha=$io_fun_scf->uf_obtenervalor("txtfecha",$_POST["txtfechacon"]);
		$ls_procede="SCGCMP";
		$ls_comprobante=$_POST["txtcomprobante"];
		$ls_descripcion=$_POST["txtdescripcion"];
		$ls_codprovben=$_POST["txtcodigo"];
		$ls_nomprovben=$_POST["txtnombre"];
		$ls_tipodestino=$_POST["cmbtipdes"];
		$li_totaldebe=$_POST["txttotaldebe"];
		$li_totalhaber=$_POST["txttotalhaber"];
		$ls_operacion=$io_fun_scf->uf_obteneroperacion();
		$ls_existe=$io_fun_scf->uf_obtenerexiste();	
		$li_totrow=$_POST["totrow"];
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
<title >Registro de Comprobantes</title>
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
	require_once("class_folder/sigesp_scf_c_comprobante.php");
	$io_scg=new sigesp_scf_c_comprobante("../");
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_scg->uf_guardar($ls_existe,$ls_comprobante,$ld_fecha,$ls_procede,$ls_descripcion,$ls_codprovben,
										   $ls_tipodestino,$ls_codban,$ls_ctaban,$li_totaldebe,$li_totrow,$la_seguridad);
			uf_load_data(&$ls_parametros);
			if($lb_valido)
			{
				$ls_existe="TRUE";
			}
			break;
			
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0" title="Nuevo"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0" title="Guardar"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" title="Buscar"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0" title="Eliminar"></a></div></td>
    <td class="toolbar" width="25"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a><a href="javascript: ue_imprimir();"></a></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
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
            <td colspan="4" class="titulo-ventana">Registro de Comprobante </td>
          </tr>
          <tr> 
            <td width="203" height="22"><div align="right">Procedencia</div></td>
            <td width="354"><input name="txtprocede" type="text" id="txtprocede" value="<?php print $ls_procede;?>" size="10" maxlength="15"  readonly></td>
            <td width="89"><div align="right">Fecha</div></td>
            <td width="202"><input name="txtfecha" type="text" id="txtfecha" style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ld_fecha;?>" size="15"  datepicker="true"></td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Comprobante</div></td>
            <td height="22" colspan="3">
              <input name="txtcomprobante" type="text" id="txtcomprobante" onKeyUp="javascript: ue_validarcomillas(this);" onBlur="javascript: ue_verificar_comprobante();" value="<?php print $ls_comprobante;?>" size="20" maxlength="15">
            </td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Destino</div></td>
            <td height="22" colspan="3"><select name="cmbtipdes" id="cmbtipdes" onChange="javascript: ue_cambiardestino();">
                <option value="-" selected>-- Seleccione Uno --</option>
                <option value="P" <?php if($ls_tipodestino=="P"){ print "selected";} ?>>PROVEEDOR</option>
                <option value="B" <?php if($ls_tipodestino=="B"){ print "selected";} ?>>BENEFICIARIO</option>
              </select> <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codprovben;?>" size="15" maxlength="10" readonly>
              <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" value="<?php print $ls_nomprovben;?>" size="50" maxlength="30" readonly>            </td>
          </tr>
          <tr> 
            <td height="24"> <div align="right">Descripci&oacute;n</div></td>
            <td height="22" colspan="3" rowspan="2"><textarea name="txtdescripcion" cols="90" rows="3" id="txtdescripcion" onKeyUp="ue_validarcomillas(this);" ><?php print $ls_descripcion;?></textarea></td>
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
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";		
		f.action="sigesp_scf_p_comprobante.php";
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
	valido=true;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		procede=f.txtprocede.value;
		if(procede=="SCGCMP")
		{
			// Obtenemos el total de filas de las cuentas
			total=ue_calcular_total_fila_local("txtcuenta");
			f.totrow.value=total;
			comprobante=ue_validarvacio(f.txtcomprobante.value);
			fecha=ue_validarvacio(f.txtfecha.value);
			descripcion=ue_validarvacio(f.txtdescripcion.value);
			codigo=ue_validarvacio(f.txtcodigo.value);
			tipodestino=ue_validarvacio(f.cmbtipdes.value);
			if(valido)
			{
				valido=ue_validarcampo(comprobante,"El Número del comprobante no puede estar vacio.",f.txtcomprobante);
			}
			if(valido)
			{
				valido=ue_validarcampo(fecha,"La Fecha no puede estar vacia.",f.txtcfecha);
			}
			if(valido)
			{
				valido=ue_validarcampo(descripcion,"La Descripción no puede estar vacia.",f.txtdescripcion);
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
				totaldebe=f.txttotaldebe.value;
				totaldebe=ue_formato_calculo(totaldebe);
				totalhaber=f.txttotalhaber.value;
				totalhaber=ue_formato_calculo(totalhaber);
				if((parseFloat(totaldebe)==0)&&(parseFloat(totalhaber)==0))
				{
					alert("Debe colocar los detalles contables.");
					valido=false;
				}
				if(parseFloat(totaldebe)!=parseFloat(totalhaber))
				{
					alert("El comprobante está descuadrado.");
					valido=false;
				}
			}
			if(valido)
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_scf_p_comprobante.php";
				f.submit();		
			}
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación.");
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
			if(procede=="SCGCMP")
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
						f.action="sigesp_scf_p_comprobante.php";
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
	if (li_leer==1)
   	{
		window.open("sigesp_scf_cat_comprobante.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_verificar_comprobante()
{
	f=document.formulario;
	if(f.existe.value=="FALSE")
	{
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
			ajax.open("POST","class_folder/sigesp_scf_c_comprobante_ajax.php",true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) 
				{
					texto=ajax.responseText;
					if(texto.indexOf("ERROR->")!=-1)
					{
						posicion=texto.indexOf("ERROR->");
						lontitud=texto.length-posicion;
						alert(texto.substr(posicion,lontitud));
					}
					else
					{
						f.txtfechacon.value=fecha;
						f.txtfecha.disabled=true;
						f.txtcomprobante.readOnly=true;
						divgrid.innerHTML = ajax.responseText;
					}
					
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send("proceso=NUEVO"+parametros);
		}
	}
}

function ue_cambiardestino()
{
	f=document.formulario;
	procede=f.txtprocede.value;
	if(procede=="SCGCMP")
	{
		// Se verifica si el destino es un proveedor ó beneficiario y se carga el catalogo
		// dependiendo de esa información
		f.txtcodigo.value="";
		f.txtnombre.value="";
		tipdes=ue_validarvacio(f.cmbtipdes.value);
		if(tipdes!="-")
		{
			if(tipdes=="P")
			{
				window.open("sigesp_scf_cat_proveedor.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
			}
			else
			{
				window.open("sigesp_scf_cat_beneficiario.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
			}	
		}
	}
}

function ue_catalogo_cuentas_scg()
{
	f=document.formulario;
	procede=f.txtprocede.value;
	descripcion=ue_validarvacio(f.txtdescripcion.value);
	if(procede=="SCGCMP")
	{
		if(descripcion!="")
		{
			window.open("sigesp_scf_pdt_scgcuentas.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=680,height=200,left=50,top=50,location=no,resizable=no");
		}
		else
		{
			alert("Debe llenar la descripción del comprobante.");
		}
	}
}

function ue_delete_scg_cuenta(fila)
{
	f=document.formulario;
	totrowscg=ue_calcular_total_fila_local("txtcuenta");
	f.totrow.value=totrowscg;
	procede=f.txtprocede.value;
	if(procede=="SCGCMP")
	{
		// Inicializaciones
		parametros="";
		li_i=0;
		//---------------------------------------------------------------------------------
		// Cargar las cuentas contables
		//---------------------------------------------------------------------------------
		for(j=1;(j<totrowscg);j++)
		{
			if(j!=fila)
			{
				li_i=li_i+1;
				cuenta=eval("f.txtcuenta"+j+".value");
				descripcion=eval("f.txtdescripcion"+j+".value");
				procede=eval("f.txtprocede"+j+".value");
				documento=eval("f.txtdocumento"+j+".value");
				mondeb=eval("f.txtmondeb"+j+".value");
				monhab=eval("f.txtmonhab"+j+".value");
				debhab=eval("f.txtdebhab"+j+".value");
				parametros=parametros+"&txtcuenta"+li_i+"="+cuenta+"&txtdescripcion"+li_i+"="+descripcion+"&txtprocede"+li_i+"="+procede+
									  "&txtdocumento"+li_i+"="+documento+"&txtmondeb"+li_i+"="+mondeb+"&txtmonhab"+li_i+"="+monhab+
									  "&txtdebhab"+li_i+"="+debhab;
			}
		}
		li_i=li_i+1;
		parametros=parametros+"&totrowscg="+li_i;
		if(parametros!="")
		{
			// Div donde se van a cargar los resultados
			divgrid = document.getElementById("cuentas");
			// Instancia del Objeto AJAX
			ajax=objetoAjax();
			// Pagina donde están los métodos para buscar y pintar los resultados
			ajax.open("POST","class_folder/sigesp_scf_c_comprobante_ajax.php",true);
			ajax.onreadystatechange=function(){
				if(ajax.readyState==1)
				{
					//divgrid.innerHTML = "";//<-- aqui iria la precarga en AJAX 
				}
				else
				{
					if(ajax.readyState==4)
					{
						if(ajax.status==200)
						{//mostramos los datos dentro del contenedor
							divgrid.innerHTML = ajax.responseText
						}
						else
						{
							if(ajax.status==404)
							{
								divgrid.innerHTML = "La página no existe";
							}
							else
							{//mostramos el posible error     
								divgrid.innerHTML = "Error:".ajax.status;
							}
						}
						
					}
				}
			}	
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			// Enviar todos los campos a la pagina para que haga el procesamiento
			ajax.send("proceso=PROCESAR"+parametros);
			f.totrow.value=li_i;
		}
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
		ajax.open("POST","class_folder/sigesp_scf_c_comprobante_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("proceso=PROCESAR"+parametros);
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
?>		  
</html>