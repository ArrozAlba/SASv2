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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_ajustaraporte.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
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
   		global $ls_desnom,$ls_peractnom,$ld_fecdesper,$ld_fechasper,$ls_codconc,$ls_nomcon;
		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$li_totrows,$io_fun_nomina,$ls_desper,$li_calculada;
		global $ls_operacion;

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
			$ld_fecdesper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
			$ld_fechasper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
		}
		$ls_codconc="";
		$ls_nomcon="";
		$ls_titletable="Aportes Patronales";
		$li_widthtable=700;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Apellidos y Nombres";
		$lo_title[3]="Aporte Empleado";
		$lo_title[4]="Aporte Patrón";
		$lo_title[5]="Valor Empleado";
		$lo_title[6]="Valor Patrón";			
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",0);
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		unset($io_sno);
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
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]=" ";
		$aa_object[$ai_totrows][2]=" ";
		$aa_object[$ai_totrows][3]=" ";
		$aa_object[$ai_totrows][4]=" ";
		$aa_object[$ai_totrows][5]="<input name=txtvalQ1".$ai_totrows." type=text id=txtvalQ1".$ai_totrows." class=sin-borde size=20 maxlength=23 onKeyPress=return(ue_formatonumero(this,'.',',',event))>";
		$aa_object[$ai_totrows][6]="<input name=txtvalQ2".$ai_totrows." type=text id=txtvalQ2".$ai_totrows." class=sin-borde size=20 maxlength=23 onKeyPress=return(ue_formatonumero(this,'.',',',event))>";
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
<title >Ajustar Aportes Patronales</title>
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
	require_once("sigesp_sno_c_ajustes.php");
	$io_ajustes=new sigesp_sno_c_ajustes();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$ls_codconc=$io_fun_nomina->uf_obtenervalor("txtcodconc","");
			$ls_nomcon=$io_fun_nomina->uf_obtenervalor("txtnomcon","");
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "GUARDAR":
			$ls_codperi=$_POST["txtperactnom"];
			$ls_codconc=$_POST["txtcodconc"];
			$ls_nomcon=$_POST["txtnomcon"];
			$io_ajustes->io_sql->begin_transaction();
			$lb_valido=$io_ajustes->uf_delete_ajustaraporte($ls_codperi,$ls_codconc,$la_seguridad);
			if($lb_valido)
			{
				for($li_i=1;($li_i<=$li_totrows)&&($lb_valido);$li_i++)
				{
					$ls_codper=$_POST["txtcodper".$li_i];
					$li_valq1=$_POST["txtvalQ1".$li_i];
					$li_valq1=str_replace(".","",$li_valq1);
					$li_valq1=str_replace(",",".",$li_valq1);
					$li_valq2=$_POST["txtvalQ2".$li_i];
					$li_valq2=str_replace(".","",$li_valq2);
					$li_valq2=str_replace(",",".",$li_valq2);
					if($li_valq1!=0)
					{
						$lb_valido=$io_ajustes->uf_insert_ajustaraporte($ls_codperi,$ls_codconc,$ls_codper,$li_valq1,"Q1",$la_seguridad);
					}
					if(($lb_valido)&&($li_valq2!=0))
					{
						$lb_valido=$io_ajustes->uf_insert_ajustaraporte($ls_codperi,$ls_codconc,$ls_codper,$li_valq2,"Q2",$la_seguridad);
					}
				}
			}

			if ($lb_valido)
			{
				$lb_valido=$io_ajustes->uf_load_ajustaraporte($ls_codperi,$ls_codconc,$li_totrows,$lo_object);
			}

			if($lb_valido)
			{
				$io_ajustes->io_sql->commit();
				$io_ajustes->io_mensajes->message("Los Aportes fueron ajustados.");
			}
			else
			{
				$io_ajustes->io_sql->rollback();
				$li_totrows=0;
				uf_agregarlineablanca($lo_object,$li_totrows);
				$io_ajustes->io_mensajes->message("Ocurrio un error al ajustar los aportes.");
			}
			break;


		case "BUSCAR":
			$ls_codperi=$_POST["txtperactnom"];
			$ls_codconc=$_POST["txtcodconc"];
			$ls_nomcon=$_POST["txtnomcon"];
			$lb_valido=$io_ajustes->uf_load_ajustaraporte($ls_codperi,$ls_codconc,$li_totrows,$lo_object);
			if ($lb_valido===false)
			{
				$li_totrows=0;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			break;
	}
	$io_ajustes->uf_destructor();
	unset($io_ajustes);
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
          <td height="20" colspan="6" class="titulo-ventana">Ajustar Aporte Patronales </td>
        </tr>
        <tr>
          <td width="136" height="22">&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;odo</div></td>
          <td width="30"><input name="txtperactnom" type="text" class="sin-borde3" id="txtperactnom" value="<?php print $ls_peractnom;?>" size="6" maxlength="3" readonly>
          </td>
          <td width="71"><div align="right">Fecha Inicio</div></td>
          <td width="78"><div align="left">
              <input name="txtfecdesper" type="text" class="sin-borde3" id="txtfecdesper" value="<?php print  $ld_fecdesper;?>" size="13" maxlength="10" readonly>
          </div></td>
          <td width="55"><div align="right">Fecha Fin </div></td>
          <td width="316"><div align="left">
              <input name="txtfechasper" type="text" class="sin-borde3" id="txtfechasper" value="<?php print  $ld_fechasper;?>" size="13" maxlength="10" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Concepto</div></td>
          <td colspan="5"><div align="left">
            <input name="txtcodconc" type="text" id="txtcodconc" value="<?php print $ls_codconc;?>" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarconcepto();"><img id="concepto" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtnomcon" type="text" class="sin-borde" id="txtnomcon" value="<?php print $ls_nomcon;?>" size="63" maxlength="30" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="5"><input name="operacion" type="hidden" id="operacion">
            </td>
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
				f.operacion.value="GUARDAR";
				f.action="sigesp_sno_p_ajustaraporte.php";
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
		if(f.txtcodconc.value!="")
		{
			f.operacion.value="BUSCAR";
			f.action="sigesp_sno_p_ajustaraporte.php";
			f.submit();
		}
		else
		{
			alert("Debe seleccionar un Concepto.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_buscarconcepto()
{
	window.open("sigesp_sno_cat_concepto.php?tipo=AJUSTARAPORTE","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

//--------------------------------------------------------
//	Función que coloca los montos en negativo
//--------------------------------------------------------
function ue_actualizarmonto(obj)
{
	monto=obj.value;
	if(monto=="")
	{
		monto=0;
	}
	while(monto.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monto=monto.replace(".","");
	}
	monto=monto.replace(",",".");
	monto=Math.abs(monto);
	monto=(monto*-1);	
	monto=uf_convertir(monto);
	obj.value=monto;
}
</script> 
</html>