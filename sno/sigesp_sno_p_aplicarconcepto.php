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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_aplicarconcepto.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
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
   		global $ls_desnom,$ls_peractnom,$ld_fecdesper,$ld_fechasper,$ls_codconc,$ls_nomcon, $ls_codcar, $ls_descar;
		global $li_totperfil,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$li_totrows,$li_rac,$ls_codasicar;
		global $ls_denasicar,$ls_operacion,$io_fun_nomina,$ls_desper,$li_calculada;

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
			$li_rac=$_SESSION["la_nomina"]["racnom"];
			$ld_fecdesper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
			$ld_fechasper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
		}
		$ls_codconc="";
		$ls_nomcon="";
		$ls_codcar="";
		$ls_descar="";
		$li_totperfil="0";
		$ls_titletable="Personal ";
		$li_widthtable=700;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Apellidos y Nombres";
		$lo_title[3]="Aplicar ";
		$ls_codasicar="";
		$ls_denasicar="";
		$li_totrows = $io_fun_nomina->uf_obtenervalor("totalfilas",0);
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		unset($io_sno);
		require_once("sigesp_sno_c_calcularnomina.php");
		$io_calcularnomina=new sigesp_sno_c_calcularnomina();
		$li_calculada=str_pad($io_calcularnomina->uf_existesalida(),1,"0");
		unset($io_calcularnomina);
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
		// Fecha Creación: 18/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codconc, $ls_nomcon, $ls_codcar, $ls_descar, $ls_codasicar, $ls_denasicar, $io_fun_nomina;
		
		$ls_codconc=$io_fun_nomina->uf_obtenervalor("txtcodconc","");
		$ls_nomcon=$io_fun_nomina->uf_obtenervalor("txtnomcon","");
		$ls_codcar=$io_fun_nomina->uf_obtenervalor("txtcodcar","");
		$ls_descar=$io_fun_nomina->uf_obtenervalor("txtdescar","");
		$ls_codasicar=$io_fun_nomina->uf_obtenervalor("txtcodasicar","");
		$ls_denasicar=$io_fun_nomina->uf_obtenervalor("txtdenasicar","");
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: $aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]=" ";
		$aa_object[$ai_totrows][2]=" ";
		$aa_object[$ai_totrows][3]=" ";
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
<title >Aplicar Concepto</title>
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
	require_once("sigesp_sno_c_conceptopersonal.php");
	$io_conceptopersonal=new sigesp_sno_c_conceptopersonal();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "PROCESAR":
			uf_load_variables();
			$lb_valido=true;
			$io_conceptopersonal->io_sql->begin_transaction();
			for($li_i=1;($li_i<=$li_totrows)&&($lb_valido);$li_i++)
			{
				$ls_codper=$_POST["txtcodper".$li_i];
				$li_aplcon=$io_fun_nomina->uf_obtenervalor("chkaplcon".$li_i,"0");
				$lb_valido=$io_conceptopersonal->uf_update_aplicaconcepto($ls_codconc,$ls_codper,$li_aplcon,$la_seguridad);
			}

			if ($lb_valido)
			{
				$lb_valido=$io_conceptopersonal->uf_load_aplicaconcepto($ls_codcar,$ls_codasicar,$ls_codconc,$li_totperfil,$li_totrows,$lo_object);
			}

			if($lb_valido)
			{
				$io_conceptopersonal->io_sql->commit();
				$io_conceptopersonal->io_mensajes->message("El concepto fué aplicado.");
			}
			else
			{
				$io_conceptopersonal->io_sql->rollback();
				$li_totrows=0;
				uf_agregarlineablanca($lo_object,$li_totrows);
				$io_conceptopersonal->io_mensajes->message("Ocurrio un error al aplicar los conceptos.");
			}
			break;


		case "BUSCAR":
			uf_load_variables();
			$lb_valido=$io_conceptopersonal->uf_load_aplicaconcepto($ls_codcar,$ls_codasicar,$ls_codconc,$li_totperfil,$li_totrows,$lo_object);
			if ($lb_valido===false)
			{
				$li_totrows=0;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			break;
	}
	$io_conceptopersonal->uf_destructor();
	unset($io_conceptopersonal);
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
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
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
<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="710" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="6" class="titulo-ventana">Aplicar Concepto </td>
        </tr>
        <tr>
          <td width="142" height="22">&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;odo</div></td>
          <td width="30"><div align="left">
            <input name="txtperactnom" type="text" class="sin-borde3" id="txtperactnom" value="<?php print $ls_peractnom;?>" size="6" maxlength="3" readonly>
          </div></td>
          <td width="64"><div align="left">Fecha Inicio</div></td>
          <td width="75">
            <div align="left">
              <input name="txtfecdesper" type="text" class="sin-borde3" id="txtfecdesper" value="<?php print  $ld_fecdesper;?>" size="13" maxlength="10" readonly>
                </div></td><td width="57"><div align="left">Fecha Fin </div></td>
                <td width="318">
                    <div align="left">
                      <input name="txtfechasper" type="text" class="sin-borde3" id="txtfechasper" value="<?php print  $ld_fechasper;?>" size="13" maxlength="10" readonly>
                          </div></td></tr>
        <tr>
          <td height="22"><div align="right">Concepto</div></td>
          <td colspan="5"><div align="left">
            <input name="txtcodconc" type="text" id="txtcodconc" value="<?php print $ls_codconc;?>" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarconcepto();"><img id="concepto" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtnomcon" type="text" class="sin-borde" id="txtnomcon" value="<?php print $ls_nomcon;?>" size="63" maxlength="30" readonly>
          </div></td>
        </tr>
	 <?php	if($li_rac=="0") {?>	  
        <tr>
          <td height="22"><div align="right">Cargo</div></td>
          <td colspan="5"><div align="left">
            <input name="txtcodcar" type="text" id="txtcodcar" value="<?php print $ls_codcar;?>" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarcargo();"><img id="cargo" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdescar" type="text" class="sin-borde" id="txtdescar" value="<?php print $ls_descar;?>" size="63" maxlength="30" readonly>
            <input name="txtcodasicar" type="hidden" id="txtcodasicar">
            <input name="txtdenasicar" type="hidden" id="txtdenasicar" >
          </div></td>
        </tr>
     <?php }
	 	   else
		   {
	 ?>
      <tr>
        <td height="22"><div align="right">Asignaci&oacute;n de Cargo </div></td>
        <td colspan="5"><div align="left">
          <input name="txtcodasicar" type="text" id="txtcodasicar" value="<?php print $ls_codasicar;?>" size="10" maxlength="7"  readonly>
          <a href="javascript: ue_buscarasignacioncargo();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
          <input name="txtdenasicar" type="text" class="sin-borde" id="txtdenasicar" value="<?php print $ls_denasicar;?>" size="27" maxlength="24" readonly>
        </div></td>
  		    <input name="txtcodcar" type="hidden" id="txtcodcar">
		    <input name="txtdescar" type="hidden" id="txtdescar" >
      </tr>
     <?php }
 		   if(($ls_operacion=="BUSCAR")||($ls_operacion=="PROCESAR")) { ?>		
        <tr>
          <td height="22"><div align="right">Total de Personas Filtradas</div></td>
          <td colspan="5"><div align="left">
            <input name="txttotperfil" type="text" id="txttotperfil" value="<?php print $li_totperfil;?>" size="10" maxlength="5">
          </div></td>
        </tr>
<?php } ?>		
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="5"><input name="operacion" type="hidden" id="operacion">
		  				  <input name="rac" type="hidden" id="rac" value="<?php print $li_rac;?>">            </td>
        </tr>
        <tr>
          <td colspan="6"><div align="center">
            <?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
          </div>
            <p>
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
			   <input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>">
            </p></td>
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
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_ejecutar=f.ejecutar.value;
		if(li_ejecutar==1)
		{
			codconc = ue_validarvacio(f.txtcodconc.value);
			peractnom = ue_validarvacio(f.txtperactnom.value);
			totrow = ue_validarvacio(f.totalfilas.value);
			if ((codconc!="")&&(peractnom!="")&&(totrow!="")&&(totrow!="0"))
			{
				f.operacion.value="PROCESAR";
				f.action="sigesp_sno_p_aplicarconcepto.php";
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

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		if(f.rac.value=="0")
		{
			codcar=f.txtcodcar.value;
		}
		else
		{
			codcar=f.txtcodasicar.value;
		}
		if((f.txtcodconc.value!="")&&(codcar!=""))
		{
			f.operacion.value="BUSCAR";
			f.action="sigesp_sno_p_aplicarconcepto.php";
			f.submit();
		}
		else
		{
			alert("Debe seleccionar un Concepto y Cargo.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_buscarconcepto()
{
	window.open("sigesp_sno_cat_concepto.php?tipo=APLICARCONCEPTO","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarcargo()
{
	f=document.form1;
	if(f.txtcodconc.value!="")
	{
		window.open("sigesp_sno_cat_cargo.php?tipo=aplicarconcepto","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un Concepto.");
	}
}

function ue_buscarasignacioncargo()
{
	f=document.form1;
	if(f.txtcodconc.value!="")
	{
		window.open("sigesp_sno_cat_asignacioncargo.php?tipo=aplicarconcepto","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un Concepto.");
	}
}

</script> 
</html>