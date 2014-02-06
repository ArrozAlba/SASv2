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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_p_fideicomiso.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	global $ls_sueint;
	$ls_sueint=trim($io_sno->uf_select_config("SNO","NOMINA","DENOMINACION SUELDO INTEGRAL","C",""));
	if ($ls_sueint=="")
	{
		$ls_sueint="Sueldo Integral";
	}
	unset($io_sno);
   //--------------------------------------------------------------
   function uf_limpiarvariables($ls_sueint)
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $li_anocurper,$ls_mescurper,$ls_desmesper,$li_totrows,$ls_operacion,$ls_titletable,$li_widthtable,$ls_nametable;
		global $lo_title,$la_nominas,$ls_existe,$ls_metodofideicomiso,$io_fun_nomina,$ls_meses,$la_nomsele,$li_fidconper;

		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();
	 	$li_anocurper="";
		$ls_mescurper="";
		$ls_desmesper="";
		$ls_titletable="Personal";
		$li_widthtable=710;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Cédula";
		$lo_title[3]="Apellidos y Nombres";
		$lo_title[4]=$ls_sueint;
		$lo_title[5]="Asig. Extra";
		$lo_title[6]="Monto Vacacion";
		$lo_title[7]="Monto Aguinaldo";
		$lo_title[8]="Monto Aporte";
		$lo_title[9]="";
		$la_nominas="";
		$la_nomsele="";
		$li_fidconper="0";
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
		$ls_metodofideicomiso=$io_sno->uf_select_config("SNO","CONFIG","METODO FIDECOMISO","VERSION 2","C");
		if($ls_existe=="TRUE")
		{
			$ls_meses="style='visibility:hidden'";
		}
		else
		{
			$ls_meses="style='visibility:visible'";
		}
		unset($io_sno);
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]=" ";
		$aa_object[$ai_totrows][2]=" ";
		$aa_object[$ai_totrows][3]=" ";
		$aa_object[$ai_totrows][4]=" ";
		$aa_object[$ai_totrows][5]=" ";
		$aa_object[$ai_totrows][6]=" ";
		$aa_object[$ai_totrows][7]=" ";
		$aa_object[$ai_totrows][8]=" ";
		$aa_object[$ai_totrows][9]=" ";
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_print_lista($as_nombre,$as_campoclave,$as_campoimprimir,$aa_lista,$aa_seleccionado)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_lista
		//		   Access: private
		//      Arguments: as_nombre  // Nombre del Campo
		//      		   as_campoclave  // campo por medio del cual se va filtrar la lista
		//      		   as_campoimprimir  // campo que se va a mostrar
		//      		   aa_lista  // arreglo que se va a colocar en la lista
		//      		   aa_seleccionado  // arreglo de nóminas que ya se ha seleccionado
		//	  Description: Función que imprime un arreglo de lista
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/04/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		if(empty($aa_lista[$as_campoclave]))
		{
			$li_total=0;
		}
		else
		{
			$li_total=count($aa_lista[$as_campoclave]);
		}
		print "<select name='".$as_nombre."[]' id='".$as_nombre."' size='5' style='width:350px' multiple>";
		for($li_i=0;$li_i<$li_total;$li_i++)
		{
			if(empty($aa_seleccionado))
			{
				$li_totalselec=0;
			}
			else
			{
				$li_totalselec=count($aa_seleccionado);
			}
			$ls_seleccionado="";
			for($li_j=0;$li_j<$li_totalselec;$li_j++)
			{
				if($aa_seleccionado[$li_j]==$aa_lista[$as_campoclave][$li_i])
				{
					$ls_seleccionado=" selected";
					break;
				}
			}
			print "<option value='".$aa_lista[$as_campoclave][$li_i]."' ".$ls_seleccionado.">".$aa_lista[$as_campoimprimir][$li_i];
		}
		print "</select>";
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
		// Fecha Creación: 10/04/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $li_anocurper,$ls_mescurper,$ls_desmesper,$li_totrows,$ls_operacion,$la_nomsele,$ls_existe,$io_fun_nomina,$li_fidconper;
		
	 	$li_anocurper=$_POST["txtanocurper"];
		$ls_mescurper=$_POST["txtmescurper"];
		$ls_desmesper=$_POST["txtdesmesper"];
		$la_nomsele=$io_fun_nomina->uf_obtenervalor("txtnominas","");
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$li_fidconper=$_POST["fidconper"];
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
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
<title >Prestaci&oacute;n de Antiguedad</title>
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
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_fideicomiso.php");
	$io_fideicomiso=new sigesp_snorh_c_fideicomiso();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables($ls_sueint);
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

		case "BUSCAR":
			uf_load_variables();
			$lb_valido=$io_fideicomiso->uf_load_fideiperiodo($li_anocurper,$ls_mescurper,$la_nomsele,$li_totrows,$lo_object,$ls_sueint);
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_fideicomiso->uf_delete_fideicomiso_periodo($li_anocurper,$ls_mescurper,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables($ls_sueint);
				$ls_existe="FALSE";
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,1);
			}
			else
			{
				$lb_valido=$io_fideicomiso->uf_load_fideiperiodo($li_anocurper,$ls_mescurper,$la_nomsele,$li_totrows,$lo_object,$ls_sueint);
			}
			break;

		case "PROCESAR":
			uf_load_variables();
			$lb_valido=false;
			switch(trim($ls_metodofideicomiso))
			{
				case "VERSION 2":
					$lb_valido=$io_fideicomiso->uf_procesar_fideicomiso_version2($li_anocurper,$ls_mescurper,$la_nomsele,$la_seguridad);
					break;

				case "VERSION CONSEJO":
					$lb_valido=$io_fideicomiso->uf_procesar_fideicomiso_version_consejo($li_anocurper,$ls_mescurper,$la_nomsele,$la_seguridad);
					break;
			}
			if($lb_valido===false)
			{
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,1);
				$ls_existe="FALSE";
				$ls_meses="style='visibility:visible'";
			}
			else
			{
				$ls_existe="TRUE";
				$ls_meses="style='visibility:hidden'";
				$lb_valido=$io_fideicomiso->uf_load_fideiperiodo($li_anocurper,$ls_mescurper,$la_nomsele,$li_totrows,$lo_object,$ls_sueint);
			}
			break;
	}
	$lb_valido=$io_fideicomiso->uf_load_nomina($la_nominas);
	$io_fideicomiso->uf_destructor();
	unset($io_fideicomiso);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="762" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
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
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif"  title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif"  title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title="Ejecutar" alt="Ejecutar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="782" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="780">
      <p>&nbsp;</p>
      <table width="750" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Prestaci&oacute;n de Antiguedad</td>
        </tr>
        <tr>
          <td width="170" height="22"><div align="right"></div></td>
          <td width="574">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;odos de N&oacute;minas Hist&oacute;ricas </div></td>
          <td>
		  	<div align="left">
		  	  <input name="txtanocurper" type="text" id="txtanocurper" value="<?php print $li_anocurper;?>" size="7" maxlength="4" readonly>
		  	  <input name="txtmescurper" type="text" id="txtmescurper" value="<?php print $ls_mescurper;?>" size="6" maxlength="3" readonly>
		  	  <a href="javascript: ue_buscarmeses();"><img id="meses" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0" <?php print $ls_meses; ?>></a>
		  	  <input name="txtdesmesper" type="text" class="sin-borde" id="txtdesmesper" value="<?php print $ls_desmesper;?>" size="30" maxlength="20" readonly>
	  	      </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">N&oacute;minas</div></td>
          <td><div align="left">
            <?php uf_print_lista("txtnominas","codnom","desnom",$la_nominas,$la_nomsele); ?>
          </div></td>
        </tr>
        <tr>
          <td><div align="right"></div></td>
          <td>
		  	<div align="left">
		  	  <input name="operacion" type="hidden" id="operacion">
		  	  <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
	  	      </div></td>
        </tr>
        <tr>
          <td colspan="2">
		  	<div align="center">
			    <?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
				?>
			  </div>
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
              <input name="fidconper" type="hidden" id="fidconper" value="<?php print $li_fidconper;?>">
			  <input name="hidsueint" type="hidden" id="hidsueint" value="<?php print $ls_sueint;?>"></td>		  
          </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
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
		f.action="sigesp_snorh_p_fideicomiso.php";
		f.submit();
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
			fidconper=f.fidconper.value;
			if(fidconper=="0")
			{
				var nomsel=0;
				var totnom=0;
				anocurper=ue_validarvacio(f.txtanocurper.value);
				mescurper=ue_validarvacio(f.txtmescurper.value);
				if(f.txtnominas!=null)
				{
					totnom=f.txtnominas.length;
				}
				for(i=0;i<totnom;i++) // se coloca en el arreglo los campos seleccionados
				{	
					if(f.txtnominas.options[i].selected) 
					{
						nomsel=nomsel+1;
					}
				}
				if ((anocurper!="")&&(mescurper!="")&&(nomsel>0))
				{
					if(confirm("¿Desea eliminar el Registro Fideicomiso del Año "+anocurper+" Período "+mescurper+"?"))
					{
						f.operacion.value="ELIMINAR";
						f.action="sigesp_snorh_p_fideicomiso.php";
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
				alert("La Prestación Antiguedad esta Contabilizada. Debe Reversarla para poder eliminar.");
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

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_snorh_cat_fideicomiso.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{	
		fidconper=f.fidconper.value;
		if(fidconper=="0")
		{
			totnom=0;
			nomsel=0;
			anocurper=ue_validarvacio(f.txtanocurper.value);
			mescurper=ue_validarvacio(f.txtmescurper.value);
			if(f.txtnominas!=null)
			{
				totnom=f.txtnominas.length;
			}
			for(i=0;i<totnom;i++) // se coloca en el arreglo los campos seleccionados
			{	
				if(f.txtnominas.options[i].selected) 
				{
					nomsel=nomsel+1;
				}
			}
			if ((anocurper!="")&&(mescurper!="")&&(nomsel>0))
			{
				f.operacion.value="PROCESAR";
				f.action="sigesp_snorh_p_fideicomiso.php";
				f.submit();
			}
			else
			{
				alert("Debe seleccionar el año, mes y al menos una nómina.");
			}
		}
		else
		{
			alert("La Prestación Antiguedad esta Contabilizada. Debe Reversarla para poder procesar.");
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

function ue_buscarmeses()
{
	f=document.form1;
	if(f.existe.value=="FALSE")
	{
		window.open("sigesp_sno_cat_hmes.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
}

function ue_mostrar_sueldo(ls_codper)
{
	f=document.form1;
	ls_anocurper=f.txtanocurper.value;
	ls_mescurpe=f.txtmescurper.value;
    ls_sueint=f.hidsueint.value;
	if (ls_sueint=="")
	{
		ls_sueint='Sueldo Integral';
	}
	window.open("sigesp_snorh_pdt_sueldointegral.php?codper="+ls_codper+"&anocurper="+ls_anocurper+"&mescurpe="+ls_mescurpe+"&sueint="+ls_sueint,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
}

</script> 
</html>