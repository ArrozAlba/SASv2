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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_movimientonominas.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
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
   		global $ls_desnom,$ls_codper,$ls_nomper,$ld_fecegrper,$ls_cauegrper,$la_nominanormal,$la_nominaespecial;
		global $ls_repetidos,$ls_operacion,$io_fun_nomina,$ls_desper,$li_calculada,$li_rac,$li_subnomina;
		
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
		$ld_fecegrper="dd/mm/aaaa";
		$ls_cauegrper="";
		$la_nominanormal="";
		$la_nominaespecial="";
		$li_rac=$_SESSION["la_nomina"]["racnom"];
		$li_subnomina=$_SESSION["la_nomina"]["subnom"];	
		$ls_repetidos=$io_sno->uf_select_config("SNO","CONFIG","NOPERMITIR_REPETIDOS","1","C");
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
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
		//	     Function : uf_print_lista
		//		   Access : private
		//      Arguments : as_nombre  // Nombre del Campo
		//      			as_campoclave  // campo por medio del cual se va filtrar la lista
		//      			as_campoimprimir  // campo que se va a mostrar
		//      			aa_lista  // arreglo que se va a colocar en la lista
		//	  Description : Función que imprime 
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creación : 22/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////

		if(empty($aa_lista[$as_campoclave]))
		{
			$li_total=0;
		}
		else
		{
			$li_total=count($aa_lista[$as_campoclave]);
		}
		print "<select name='".$as_nombre."[]' id='".$as_nombre."' size='10' style='width:280px' multiple>";
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
<title >Movimiento entre N&oacute;minas</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
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
	require_once("sigesp_sno_c_movimientonominas.php");
	$io_movimiento=new sigesp_sno_c_movimientonominas();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			$li_totnomnor=0;
			$li_totnomesp=0;
			$ls_codper=$io_fun_nomina->uf_obtenervalor("txtcodper","");
			$ls_nomper=$io_fun_nomina->uf_obtenervalor("txtnomper","");
			$ls_egenom=$io_fun_nomina->uf_obtenervalor("chkegenom","0");
			$ld_fecegrper=$io_fun_nomina->uf_obtenervalor("txtfecegrper","1900-01-01");
			$ls_cauegrper=$io_fun_nomina->uf_obtenervalor("txtcauegrper","");
			$la_nominanormal=$io_fun_nomina->uf_obtenervalor("txtnominanormal","");
			$la_nominaespecial=$io_fun_nomina->uf_obtenervalor("txtnominaespecial","");
			$ls_codsubnom=$io_fun_nomina->uf_obtenervalor("txtcodsubnom","0000000000");
			$ls_codcar=$io_fun_nomina->uf_obtenervalor("txtcodcar","0000000000");			
			if (array_key_exists('session_activa',$_SESSION))
			{	
				$ls_codasicar=$io_fun_nomina->uf_obtenervalor("txtcodasicar","0000000000");
			}
			else
			{
				$ls_codasicar=$io_fun_nomina->uf_obtenervalor("txtcodasicar","0000000");	
			}			
			$ls_codtab=$io_fun_nomina->uf_obtenervalor("txtcodtab","00000000000000000000");
			$ls_codpas=$io_fun_nomina->uf_obtenervalor("txtcodpas","00");
			$ls_codgra=$io_fun_nomina->uf_obtenervalor("txtcodgra","00");
			$li_sueper=$io_fun_nomina->uf_obtenervalor("txtsueper",0);
			$ls_codded=$io_fun_nomina->uf_obtenervalor("txtcodded","000");
			$ls_codtipper=$io_fun_nomina->uf_obtenervalor("txtcodtipper","0000");
			$ls_coduniadm=$io_fun_nomina->uf_obtenervalor("txtcoduniadm","");
			if(!empty($la_nominanormal))
			{
				$li_totnomnor=count($la_nominanormal);
			}
			if(!empty($la_nominaespecial))
			{
				$li_totnomesp=count($la_nominaespecial);
			}
			$lb_valido=$io_movimiento->uf_mover_a_nomina($ls_codper,$ls_egenom,$ld_fecegrper,$ls_cauegrper,$li_totnomnor,
														 $la_nominanormal, $li_totnomesp,$la_nominaespecial,$ls_codsubnom,
														 $ls_codcar,$ls_codasicar, $ls_codtab,$ls_codpas,$ls_codgra, 
														 $li_sueper,$ls_codded,$ls_codtipper,$ls_coduniadm,$la_seguridad);
			$lb_valido=$io_movimiento->uf_select_nomina($ls_codper,$la_nominanormal,$la_nominaespecial);
			break;


		case "BUSCAR":
			$ls_codper=$io_fun_nomina->uf_obtenervalor("txtcodper","");
			$ls_nomper=$io_fun_nomina->uf_obtenervalor("txtnomper","");
			$lb_valido=$io_movimiento->uf_select_nomina($ls_codper,$la_nominanormal,$la_nominaespecial);
			break;
	}
	$io_movimiento->uf_destructor();
	unset($io_movimiento);
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
          <td height="20" colspan="4" class="titulo-ventana">Movimiento entre N&oacute;minas </td>
        </tr>
        <tr>
          <td width="159" height="22"><div align="right">Personal</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtcodper" type="text" id="txtcodper" size="13" maxlength="10" value="<?php print $ls_codper;?>" readonly>
              <a href="javascript: ue_buscarpersonal();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtnomper" type="text" class="sin-borde" id="txtnomper" value="<?php print $ls_nomper;?>" size="60" maxlength="120" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Egresar de la N&oacute;mina Actual</div></td>
          <td colspan="3"><div align="left">
            <input name="chkegenom" type="checkbox" id="chkegenom" value="1" checked>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Egreso </div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtfecegrper" type="text" id="txtfecegrper" value="<?php print $ld_fecegrper;?>" size="15" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Observaci&oacute;n de Egreso </div></td>
          <td colspan="3">
            <div align="left">
              <textarea name="txtcauegrper" cols="80" rows="3" id="txtcauegrper" onKeyUp="javascript: ue_validarcomillas(this);"><?php print $ls_cauegrper;?></textarea>
              </div></td></tr>
	 <?php if($li_subnomina=="1") {?>	  
      <tr>
        <td height="22"><div align="right">Subn&oacute;mina</div></td>
        <td colspan="5"><div align="left">
          <input name="txtcodsubnom" type="text" id="txtcodsubnom" size="13" maxlength="10" readonly>
          <a href="javascript: ue_buscarsubnomina();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
          <input name="txtdessubnom" type="text" class="sin-borde" id="txtdessubnom" size="63" maxlength="60" readonly>
        </div></td>
      </tr>
	 <?php }
	  	   if($li_rac=="0") {
	 ?>	  
      <tr>
        <td height="22"><div align="right">Cargo</div></td>
        <td colspan="5"><div align="left">
          <input name="txtcodcar" type="text" id="txtcodcar" size="13" maxlength="10"  readonly>
          <a href="javascript: ue_buscarcargo();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
          &nbsp;
            <input name="txtdescar" type="text" class="sin-borde" id="txtdescar" size="65"  readonly>
        </div></td>
      </tr>
     <?php }
	 	   else
		   {
	 ?>
      <tr>
        <td height="22"><div align="right">Asignaci&oacute;n de Cargo </div></td>
        <td colspan="5"><div align="left">
          <input name="txtcodasicar" type="text" id="txtcodasicar" size="10" maxlength="7"  readonly>
          <a href="javascript: ue_buscarasignacioncargo();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
          <input name="txtdenasicar" type="text" class="sin-borde" id="txtdenasicar" size="27" maxlength="24" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tabulador</div></td>
        <td colspan="5"><div align="left">
          <input name="txtcodtab" type="text" id="txtcodtab" size="25" maxlength="20" readonly>
          &nbsp;
            <input name="txtdestab" type="text" class="sin-borde" id="txtdestab" size="60" maxlength="100">
        </div></td>
      </tr>
        <tr>
          <td height="22"><div align="right">Paso </div></td>
          <td width="189"><input name="txtcodpas" type="text" id="txtcodpas" size="18" maxlength="15" readonly></td>
          <td width="97"><div align="right">Grado </div></td>
          <td width="245"><input name="txtcodgra" type="text" id="txtcodgra" size="18" maxlength="15" readonly></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Sueldo </div></td>
          <td width="189"><input name="txtsueper" type="text" id="txtsueper" size="18" maxlength="15" readonly></td>          
        </tr>
		<tr>
        <td height="22"><div align="right">Dedicaci&oacute;n</div></td>
        <td colspan="5"><div align="left">
          <input name="txtcodded" type="text" id="txtcodded" size="25" maxlength="20" readonly>
          &nbsp;
            <input name="txtdesded" type="text" class="sin-borde" id="txtdesded" size="60" maxlength="100">
        </div></td>
      </tr>
	  <tr>
        <td height="22"><div align="right">Tipo de Personal</div></td>
        <td colspan="5"><div align="left">
          <input name="txtcodtipper" type="text" id="txtcodtipper" size="25" maxlength="20" readonly>
          &nbsp;
            <input name="txtdestipper" type="text" class="sin-borde" id="txtdestipper" size="60" maxlength="100">
        </div></td>
      </tr>
	  <tr>
        <td height="22"><div align="right">Unidad Administrativa</div></td>
        <td colspan="5"><div align="left">
          <input name="txtcoduniadm" type="text" id="txtcoduniadm" size="25" maxlength="20" readonly>
          &nbsp;
            <input name="txtdesuniadm" type="text" class="sin-borde" id="txtdesuniadm" size="60" maxlength="100">
        </div></td>
      </tr>
     <?php }?>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" colspan="4"><table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="280" height="22" class="titulo-celdanew">N&oacute;minas Normales </td>
              <td width="40" height="22">&nbsp;</td>
              <td width="280" height="22" class="titulo-celdanew">N&oacute;minas Especiales</td>
            </tr>
            <tr>
              <td height="22"><div align="center"><?php uf_print_lista("txtnominanormal","codnom","desnom",$la_nominanormal); ?></div></td>
              <td height="22">&nbsp;</td>
              <td height="22"><div align="center"><?php uf_print_lista("txtnominaespecial","codnom","desnom",$la_nominaespecial); ?></div></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3"><input name="operacion" type="hidden" id="operacion">
            <input name="repetidos" type="hidden" id="repetidos" value="<?php print $ls_repetidos;?>">
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
	var totnomnor=0;
	var totnomesp=0;
	var selnomnor=0;
	var selnomesp=0;
	var valido=true;
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_ejecutar=f.ejecutar.value;
		if(li_ejecutar==1)
		{
			codper=ue_validarvacio(f.txtcodper.value);
			nomper=ue_validarvacio(f.txtnomper.value);
			repetidos=ue_validarvacio(f.repetidos.value);
			if(f.txtnominanormal!=null)
			{
				totnomnor = f.txtnominanormal.length;
			}
			for(i=0;i<totnomnor;i++) // se coloca en el arreglo los campos seleccionados
			{	
				if(f.txtnominanormal.options[i].selected) 
				{
					selnomnor=selnomnor+1;
				}
			}
			if(f.txtnominaespecial!=null)
			{
				totnomesp = f.txtnominaespecial.length;
			}
			for(i=0;i<totnomesp;i++) // se coloca en el arreglo los campos seleccionados
			{	
				if(f.txtnominaespecial.options[i].selected) 
				{
					selnomesp=selnomesp+1;
				}
			}	
			if((repetidos=="1")&&(selnomnor>1))
			{
				alert("No puede selecionar mas de una nómina normal, No se permiten repetidos entre nómina.");
				valido=false;
			}	
			if((repetidos=="1")&&(!f.chkegenom.checked))
			{
				alert("Para poder mover al personal debe egresar al personal, No se permiten repetidos entre nómina.");
				valido=false;
			}
			
			if((f.chkegenom.checked)&&(valido))
			{
				fecegrper = ue_validarfecha(f.txtfecegrper.value);
				cauegrper = ue_validarvacio(f.txtcauegrper.value);
				if ((codper!="")&&(nomper!="")&&((selnomnor>0)||(selnomesp>0))&&(fecegrper!="1900-01-01")&&(fecegrper!="")&&(cauegrper!=""))
				{
					f.operacion.value="GUARDAR";
					f.action="sigesp_sno_p_movimientonominas.php";
					f.submit();
				}
				else
				{
					alert("Debe llenar todos los datos.");
				}	
			}
			else
			{
				if(valido)
				{
					if ((codper!="")&&(nomper!="")&&((selnomnor>0)||(selnomesp>0)))
					{
						f.operacion.value="GUARDAR";
						f.action="sigesp_sno_p_movimientonominas.php";
						f.submit();
					}
					else
					{
						alert("Debe llenar todos los datos.");
					}	
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

function ue_buscarsubnomina()
{
	window.open("sigesp_snorh_cat_subnomina.php?tipo=movimiento","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarasignacioncargo()
{
	window.open("sigesp_sno_cat_asignacioncargo.php?tipo=movimiento","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarcargo()
{
	window.open("sigesp_sno_cat_cargo.php?tipo=movimiento","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank_nomina.php";
}

function ue_buscarpersonal()
{
	window.open("sigesp_sno_cat_personalnomina.php?tipo=movimientonominas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script> 
</html>