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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_d_primadocpersonal.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/07/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codper,$ls_nomper,$ls_uniadm,$ls_desnom,$li_totrows,$ls_operacion,$ls_desper,$li_calculada;
		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$io_fun_nomina,$li_totdiaper;
		global $ls_conpronom;
		
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		$ls_conpronom=$_SESSION["la_nomina"]["conpronom"];
		$ls_codper="";
		$ls_nomper="";
		$ls_uniadm="";
		$li_totdiaper="0";
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_titletable="Primas Asociadas";
		$li_widthtable=550;
		$ls_nametable="grid";
		$lo_title[1]=" ";
		$lo_title[2]="Código";
		$lo_title[3]="Nombre";
		$lo_title[4]="Tipo de Prima";
		
		require_once("sigesp_sno_c_calcularnomina.php");
		$io_calcularnomina=new sigesp_sno_c_calcularnomina();
		$li_calculada=str_pad($io_calcularnomina->uf_existesalida(),1,"0");
		unset($io_calcularnomina);
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//		   Access: private
		//	    Arguments: aa_object  // arreglo de Objetos
		//			       ai_totrows  // total de Filas
		//	  Description: Función que agrega una linea mas en el grid
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/07/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<div align='center'><a href='javascript:ue_buscarproyecto();'><img src='../shared/imagebank/tools20/buscar.gif' alt='Buscar' width='15' height='15' border='0'></a></div>";
		$aa_object[$ai_totrows][2]="<input type=text   name=txtcodpridoc".$ai_totrows."   id=txtcodpridoc".$ai_totrows."   value=''   size=12 class=sin-borde readonly><input type=hidden   name=hidtippridoc".$ai_totrows."   id=hidtippridoc".$ai_totrows."   value=''>";
		$aa_object[$ai_totrows][3]="<input type=text   name=txtdespridoc".$ai_totrows."   id=txtdespridoc".$ai_totrows."   value=''   size=50 class=sin-borde readonly >";
		$aa_object[$ai_totrows][4]="<input type=text   name=txttippridoc".$ai_totrows."   id=txttippridoc".$ai_totrows."   value=''   size=50 class=sin-borde readonly >";
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
<title>Definici&oacute;n de Primas x Personal</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #EAEAEA;
	margin-left: 0px;
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
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
</head>
<body>
<?php 
	require_once("sigesp_sno_c_primadocpersonal.php");
	$io_proyecto=new sigesp_sno_c_primadocpersonal();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("sigesp_sno_c_calcularnomina.php");
	$io_calcularnomina=new sigesp_sno_c_calcularnomina();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_agregarlineablanca($lo_object,1);
			break;

		case "GUARDAR":
			$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_uniadm=$_POST["txtuniadm"];
			$ls_codpridoc=$_POST["txtcodpridoc"];  
			$lb_valido=true;
			$io_proyecto->io_sql->begin_transaction();
			$lb_valido=$io_proyecto->uf_delete_personalprima($ls_codper,$ls_codpridoc,$la_seguridad);
			if ($lb_valido)
			{
				for($li_i=1;$li_i<$li_totrows&&$lb_valido;$li_i++)
				{
					$ls_codpridoc=$_POST["txtcodpridoc".$li_i];  
					$ls_tippridoc=$_POST["txttippridoc".$li_i];  
					$ls_despridoc=$_POST["txtdespridoc".$li_i];
					$lb_valido=$io_proyecto->uf_guardar_personalprima($ls_codper,$ls_codpridoc,$la_seguridad);
				}
			}	
			if($lb_valido)
			{
				$io_proyecto->io_sql->commit();
				$io_proyecto->io_mensajes->message("Las Primas del Personal fueron Actualizados.");
			}
			else
			{
				$io_proyecto->io_sql->rollback();
				$io_proyecto->io_mensajes->message("Ocurrio un error al actualizar las primas del personal.");
			}
			$lb_valido=$io_proyecto->uf_load_personalprima($ls_codper,$li_totrows,$lo_object);
			if($lb_valido==false)
			{
				for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				$ls_codpridoc=$_POST["txtcodpridoc".$li_i];  
				$ls_tippridoc=$_POST["txttippridoc".$li_i];
				$ls_codtippri=$_POST["hidtippridoc".$li_i];
				$ls_despridoc=$_POST["txtdespridoc".$li_i]; 
				$lo_object[$li_i][1]="<div align='center'><a href=javascript:ue_eliminarproyecto('$ls_codpridoc');><img src='../shared/imagebank/tools20/eliminar.gif' alt='Eliminar' width='15' height='15' border='0'></a></div>";
				$lo_object[$li_i][2]="<input type=text   name=txtcodpridoc".$li_i."   id=txtcodpridoc".$li_i."   value='".$ls_codpridoc."'   size=12 class=sin-borde readonly><input type=hidden   name=hidtippridoc".$li_i."   id=hidtippridoc".$li_i."   value='".$ls_codtippri."'>";
				$lo_object[$li_i][3]="<input type=text   name=txtdespridoc".$li_i."   id=txtdespridoc".$li_i."   value='".$ls_despridoc."'   size=50 class=sin-borde readonly >";
				$lo_object[$li_i][4]="<input type=text   name=txttippridoc".$li_i."   id=txttippridoc".$li_i."   value='".$ls_tippridoc."'   size=50 class=sin-borde readonly >";
			
			}
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			else
			{
				for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				$ls_codpridoc=$_POST["txtcodpridoc".$li_i];  
				$ls_tippridoc=$_POST["txttippridoc".$li_i];
				$ls_codtippri=$_POST["hidtippridoc".$li_i];
				$ls_despridoc=$_POST["txtdespridoc".$li_i]; 
				$lo_object[$li_i][1]="<div align='center'><a href=javascript:ue_eliminarproyecto('$ls_codpridoc');><img src='../shared/imagebank/tools20/eliminar.gif' alt='Eliminar' width='15' height='15' border='0'></a></div>";
				$lo_object[$li_i][2]="<input type=text   name=txtcodpridoc".$li_i."   id=txtcodpridoc".$li_i."   value='".$ls_codpridoc."'   size=12 class=sin-borde readonly><input type=hidden   name=hidtippridoc".$li_i."   id=hidtippridoc".$li_i."   value='".$ls_codtippri."'>";
				$lo_object[$li_i][3]="<input type=text   name=txtdespridoc".$li_i."   id=txtdespridoc".$li_i."   value='".$ls_despridoc."'   size=50 class=sin-borde readonly >";
				$lo_object[$li_i][4]="<input type=text   name=txttippridoc".$li_i."   id=txttippridoc".$li_i."   value='".$ls_tippridoc."'   size=50 class=sin-borde readonly >";
			}
				$li_totrows=$li_totrows+1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			break;
			
		case "BUSCARDETALLE":
			$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_uniadm=$_POST["txtuniadm"];
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				$ls_codpridoc=$_POST["txtcodpridoc".$li_i];  
				$ls_tippridoc=$_POST["txttippridoc".$li_i];
				switch ($ls_tippridoc)
				{
					case "0":
						$ls_tippridoc='Jerarquia';
					break;
					case "1":
						$ls_tippridoc='Antiguedad';
					break;
					case "2":
						$ls_tippridoc='Hogar e Hijos';
					break;
				}
				$ls_codtippri=$_POST["hidtippridoc".$li_i];
				$ls_despridoc=$_POST["txtdespridoc".$li_i]; 
				$lo_object[$li_i][1]="<div align='center'><a href=javascript:ue_eliminarproyecto('$ls_codpridoc');><img src='../shared/imagebank/tools20/eliminar.gif' alt='Eliminar' width='15' height='15' border='0'></a></div>";
				$lo_object[$li_i][2]="<input type=text   name=txtcodpridoc".$li_i."   id=txtcodpridoc".$li_i."   value='".$ls_codpridoc."'   size=12 class=sin-borde readonly><input type=hidden   name=hidtippridoc".$li_i."   id=hidtippridoc".$li_i."   value='".$ls_codtippri."'>";
				$lo_object[$li_i][3]="<input type=text   name=txtdespridoc".$li_i."   id=txtdespridoc".$li_i."   value='".$ls_despridoc."'   size=50 class=sin-borde readonly >";
				$lo_object[$li_i][4]="<input type=text   name=txttippridoc".$li_i."   id=txttippridoc".$li_i."   value='".$ls_tippridoc."'   size=50 class=sin-borde readonly >";
			}
			$lb_valido=$io_proyecto->uf_load_personalprima($ls_codper,$li_totrows,$lo_object);
			if($lb_valido==false)
			{
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			else
			{
				
				$li_totrows=$li_totrows+1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			break;

		case "CARGARPROYECTO":
			$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_uniadm=$_POST["txtuniadm"];
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				$ls_codpridoc=$_POST["txtcodpridoc".$li_i];  
				$ls_tippridoc=$_POST["txttippridoc".$li_i];
				$ls_codtippri=$_POST["hidtippridoc".$li_i];
				$ls_despridoc=$_POST["txtdespridoc".$li_i]; 
				$lo_object[$li_i][1]="<div align='center'><a href=javascript:ue_eliminarproyecto('$ls_codpridoc');><img src='../shared/imagebank/tools20/eliminar.gif' alt='Eliminar' width='15' height='15' border='0'></a></div>";
				$lo_object[$li_i][2]="<input type=text   name=txtcodpridoc".$li_i."   id=txtcodpridoc".$li_i."   value='".$ls_codpridoc."'   size=12 class=sin-borde readonly><input type=hidden   name=hidtippridoc".$li_i."   id=hidtippridoc".$li_i."   value='".$ls_codtippri."'>";
				$lo_object[$li_i][3]="<input type=text   name=txtdespridoc".$li_i."   id=txtdespridoc".$li_i."   value='".$ls_despridoc."'   size=50 class=sin-borde readonly >";
				$lo_object[$li_i][4]="<input type=text   name=txttippridoc".$li_i."   id=txttippridoc".$li_i."   value='".$ls_tippridoc."'   size=50 class=sin-borde readonly >";
				

			}
			$li_totrows=$li_totrows+1;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARPROYECTO":
			$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_uniadm=$_POST["txtuniadm"];
			$ls_proyecto=$_GET["codpridoc"];
			$li_j=0;
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$ls_codpridoc=$_POST["txtcodpridoc".$li_i];  
				if($ls_proyecto!=$ls_codpridoc)
				{
					$li_j++;
					$ls_codpridoc=$_POST["txtcodpridoc".$li_i];  
					$ls_tippridoc=$_POST["txttippridoc".$li_i];
					$ls_codtippri=$_POST["hidtippridoc".$li_i];
					$ls_despridoc=$_POST["txtdespridoc".$li_i]; 
					$lo_object[$li_j][1]="<div align='center'><a href=javascript:ue_eliminarproyecto('$ls_codpridoc');><img src='../shared/imagebank/tools20/eliminar.gif' alt='Eliminar' width='15' height='15' border='0'></a></div>";
					$lo_object[$li_j][2]="<input type=text   name=txtcodpridoc".$li_j."   id=txtcodpridoc".$li_j."   value='".$ls_codpridoc."'   size=12 class=sin-borde readonly><input type=hidden   name=hidtippridoc".$li_j."   id=hidtippridoc".$li_j."   value='".$ls_codtippri."'>";
					$lo_object[$li_j][3]="<input type=text   name=txtdespridoc".$li_j."   id=txtdespridoc".$li_j."   value='".$ls_despridoc."'   size=50 class=sin-borde readonly >";
					$lo_object[$li_j][4]="<input type=text   name=txttippridoc".$li_j."   id=txttippridoc".$li_j."   value='".$ls_tippridoc."'   size=50 class=sin-borde readonly >";
					
				}
			}
			$li_totrows=$li_j+1;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
	}
	$io_proyecto->uf_destructor();
	unset($io_proyecto);
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
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
<div align="center">
<form name="form1" method="post" action="">
		<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
        ?>
  <table width="650" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td valign="top">
		<p>&nbsp;</p>
		<table width="600" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="20" colspan="2"><div align="center">Primas x Personal</div></td>
              </tr>
              <tr >
                <td height="22" colspan="2">&nbsp; </td>
              </tr>
              <tr>
                <td width="146" height="22" align="right">Codigo</td>
                <td align="left">
                  <input name="txtcodper" type="text" class="sin-borde3" id="txtcodper" style="text-align:left " value="<?php print $ls_codper; ?>" size="13" maxlength="10"  readonly>
                </td>
              </tr>
              <tr>
                <td height="22" align="right">Nombre</td>
                <td align="left">
                    <input name="txtnomper" type="text" class="sin-borde3" id="txtnomper"  value="<?php print $ls_nomper; ?>" size="50" maxlength="40" readonly>
                </td>
              </tr>
              <tr >
                <td height="22" align="right">Unidad Administrativa</td>
                <td align="left">
                  <input name="txtuniadm" type="text" class="sin-borde3" id="txtuniadm" value="<?php print $ls_uniadm; ?>" size="50" maxlength="30" readonly>
</td>
              </tr>
            <tr>
              <td height="18" colspan="2"><div align="center">
		    <?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
                <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows; ?>">
                <input name="operacion" type="hidden" id="operacion">
				<input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>">
				<input name="txttotdiaper" type="hidden" id="txttotdiaper" value="<?php print $li_totdiaper; ?>">
            </tr>
            <tr>
              <td height="18" colspan="2"></td>
            </tr>
          </table>
        <p>&nbsp;</p></td>
      </tr>
  </table>
</form>  
</div>
</body>
<script language="javascript">

function ue_guardar()
{
	f=document.form1;
	li_cambiar=f.cambiar.value;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{	
		if(li_cambiar==1)
			{
				li_total=f.totalfilas.value
				
				if(li_total>1)
				{
					f.operacion.value ="GUARDAR";
					f.action="sigesp_sno_d_primadocpersonal.php";
					f.submit();
				}
				else
				{
				alert("Debe agregar primas al personal")
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

function ue_eliminarproyecto(codpridoc)
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		f=document.form1;
		f.operacion.value="ELIMINARPROYECTO";
		f.action="sigesp_sno_d_primadocpersonal.php?codpridoc="+codpridoc+"";
		f.submit();
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}	
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_sno_cat_personalnomina.php?tipo=personalprima","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_buscarproyecto()
{
	window.open("sigesp_snorh_cat_primasdocentes.php?tipo=personalprima","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank_nomina.php";
	f.submit();
}
</script>
</html>