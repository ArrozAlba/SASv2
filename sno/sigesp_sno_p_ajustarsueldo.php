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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_ajustarsueldo.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
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
   		global $ls_desnom,$ls_peractnom,$ld_fecdesper,$ld_fechasper,$ls_codcar,$ls_descar,$li_sueperdes,$li_sueperhas,$li_totper,$li_totperfil,$li_poraum;
		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$li_totrows,$io_fun_nomina,$li_rac,$ls_codasicar,$ls_denasicar;
		global $ls_operacion,$ls_desper,$li_monaum,$li_calculada;
		
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
		$ls_codcar="";
		$ls_descar="";
		$li_sueperdes="0,00";
		$li_sueperhas="0,00";
		$li_totper="0";
		$li_totperfil="0";
		$li_poraum="0";
		$li_monaum="0";
		$ls_titletable="Sueldo de Empleados";
		$li_widthtable=700;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Apellidos y Nombres";
		$lo_title[3]="Sueldo Actual";
		$lo_title[4]="Nuevo Sueldo";
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",0);
		$li_rac=$_SESSION["la_nomina"]["racnom"];
		$ls_codasicar="";
		$ls_denasicar="";
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
		$aa_object[$ai_totrows][4]="<input name=txtsuenue".$ai_totrows." type=text id=txtsuenue".$ai_totrows." class=sin-borde size=20 maxlength=23 onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
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
<title >Ajustar Sueldo</title>
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
	if($li_rac=="1")
	{
		print("<script language=JavaScript>");
		print(" alert('Este proceso esta desactivo para nóminas que utilizan RAC. El ajuste de sueldo lo debe hacer por el tabulador.');");
		print(" location.href='sigespwindow_blank_nomina.php'");
		print("</script>");
	}	
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "GUARDAR":
			$io_ajustes->io_sql->begin_transaction();
			$lb_valido=true;
			for($li_i=1;($li_i<=$li_totrows)&&($lb_valido);$li_i++)
			{
				$ls_codper=$_POST["txtcodper".$li_i];
				$li_suenue=$_POST["txtsuenue".$li_i];
				$li_suenue=str_replace(".","",$li_suenue);
				$li_suenue=str_replace(",",".",$li_suenue);
				$li_sueact=$_POST["txtsueper".$li_i];
				$li_sueact=str_replace(".","",$li_sueact);
				$li_sueact=str_replace(",",".",$li_sueact);
				if($li_sueact!=$li_suenue)
				{
					$lb_valido=$io_ajustes->uf_update_ajustarsueldo($ls_codper,$li_suenue,$la_seguridad);
				}
			}
			if($lb_valido)
			{
				$io_ajustes->io_sql->commit();
				$io_ajustes->io_mensajes->message("Los sueldos fueron ajustados.");
			}
			else
			{
				$io_ajustes->io_sql->rollback();
				$io_ajustes->io_mensajes->message("Ocurrio un error al ajustar los sueldos.");
			}
			$li_totrows=0;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "BUSCAR":
			$ls_codperi=$_POST["txtperactnom"];
			$ls_codcar=$_POST["txtcodcar"];
			$ls_descar=$_POST["txtdescar"];
			$li_sueperdes=$_POST["txtsueperdes"];
			$li_sueperhas=$_POST["txtsueperhas"];
			$lb_valido=$io_ajustes->uf_load_ajustarsueldo($ls_codcar,$li_sueperdes,$li_sueperhas,$li_totper,$li_totperfil,$li_totrows,$lo_object);
			if ($lb_valido===false)
			{
				$li_totrows=0;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			break;
			
		case "PROCESAR":
			$ls_codperi=$_POST["txtperactnom"];
			$ls_codcar=$_POST["txtcodcar"];
			$ls_descar=$_POST["txtdescar"];
			$li_sueperdes=$_POST["txtsueperdes"];
			$li_sueperhas=$_POST["txtsueperhas"];
			$li_totper=$_POST["txttotper"];
			$li_totperfil=$_POST["txttotperfil"];
			$ls_tipaum=$_POST["cmbtipaum"];
			$li_monaum=$_POST["txtmonaum"];
			$li_monaum=str_replace(".","",$li_monaum);
			$li_monaum=str_replace(",",".",$li_monaum);
			$li_poraum=$_POST["txtporaum"];
			$li_poraum=str_replace(".","",$li_poraum);
			$li_poraum=str_replace(",",".",$li_poraum);
			$lb_valido=true;
			for($li_i=1;($li_i<=$li_totrows)&&($lb_valido);$li_i++)
			{
				$ls_codper=$_POST["txtcodper".$li_i];
				$ls_nomper=$_POST["txtnomper".$li_i];
				$li_sueact=$_POST["txtsueper".$li_i];
				if($ls_tipaum=="P") // Por Porcentaje
				{
					if($li_poraum>0)
					{
						$li_sueldo=$li_sueact;
						$li_sueldo=str_replace(".","",$li_sueldo);
						$li_sueldo=str_replace(",",".",$li_sueldo);
						$li_suenue=$li_sueldo+(($li_sueldo*$li_poraum)/100);
						$li_suenue=$io_fun_nomina->uf_formatonumerico($li_suenue);
					}
					else
					{
						$li_suenue=$li_sueact;
					}
				}
				if($ls_tipaum=="M") // Por Monto
				{
					if($li_monaum>0)
					{
						$li_sueldo=$li_sueact;
						$li_sueldo=str_replace(".","",$li_sueldo);
						$li_sueldo=str_replace(",",".",$li_sueldo);
						$li_suenue=$li_sueldo+$li_monaum;
						$li_suenue=$io_fun_nomina->uf_formatonumerico($li_suenue);
					}
					else
					{
						$li_suenue=$li_sueact;
					}
				}
				$lo_object[$li_i][1]="<input name=txtcodper".$li_i." type=text id=txtcodper".$li_i." class=sin-borde size=10 maxlength=10 value='".$ls_codper."' readonly>";
				$lo_object[$li_i][2]="<input name=txtnomper".$li_i." type=text id=txtnomper".$li_i." class=sin-borde size=60 maxlength=100 value='".$ls_nomper."' readonly>";
				$lo_object[$li_i][3]="<input name=txtsueper".$li_i." type=text id=txtsueper".$li_i." class=sin-borde size=20 maxlength=23 value='".$li_sueact."' style=text-align:right readonly>";
				$lo_object[$li_i][4]="<input name=txtsuenue".$li_i." type=text id=txtsuenue".$li_i." class=sin-borde size=20 maxlength=23 value='".$li_suenue."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); style=text-align:right>";
			}
			$li_poraum="0,00";
			$li_monaum="0,00";
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title="Ejecutar" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
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
          <td height="20" colspan="4" class="titulo-ventana">Ajustar Sueldo </td>
        </tr>
        <tr>
          <td width="186" height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;odo</div></td>
          <td colspan="3"><div align="left">
            <input name="txtperactnom" type="text" class="sin-borde3" id="txtperactnom" value="<?php print $ls_peractnom;?>" size="6" maxlength="3" readonly>
          Fecha Inicio 
              <input name="txtfecdesper" type="text" class="sin-borde3" id="txtfecdesper" value="<?php print  $ld_fecdesper;?>" size="13" maxlength="10" readonly>
            Fecha Fin 
                  <input name="txtfechasper" type="text" class="sin-borde3" id="txtfechasper" value="<?php print  $ld_fechasper;?>" size="13" maxlength="10" readonly>
            </div></td>
          </tr>
<?php if($li_rac=="0") { ?>		
        <tr>
          <td height="22"><div align="right">Cargo</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodcar" type="text" id="txtcodcar" value="<?php print $ls_codcar;?>" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarcargo();"><img id="cargo" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdescar" type="text" class="sin-borde" id="txtdescar" value="<?php print $ls_descar;?>" size="63" maxlength="30" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Sueldo Desde </div></td>
          <td width="188">
            <div align="left">
              <input name="txtsueperdes" type="text" id="txtsueperdes" value="<?php print $li_sueperdes;?>" size="23" maxlength="20" onKeyPress="javascript: return(ue_formatonumero(this,'.',',',event));" style="text-align:right">
              </div></td>
          <td width="128"><div align="right">Sueldo Hasta </div></td>
          <td width="198"><input name="txtsueperhas" type="text" id="txtsueperhas" value="<?php print $li_sueperhas;?>" size="23" maxlength="20" onKeyPress="javascript: return(ue_formatonumero(this,'.',',',event));" style="text-align:right"></td>
          </tr>
<?php }
	  if(($ls_operacion=="BUSCAR")||($ls_operacion=="PROCESAR")) { ?>		
        <tr>
          <td height="22"><div align="right">Total de Personas</div></td>
          <td colspan="3"><div align="left">
            <input name="txttotper" type="text" id="txttotper" value="<?php print $li_totper;?>" size="10" maxlength="5" style="text-align:right" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Total de Personas Filtradas</div></td>
          <td colspan="3"><div align="left">
            <input name="txttotperfil" type="text" id="txttotperfil" value="<?php print $li_totperfil;?>" size="10" maxlength="5" style="text-align:right">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Aumento 
            </div></td>
          <td colspan="3"><label>
          <select name="cmbtipaum" id="cmbtipaum" onChange="javascript: uf_bloquear();">
            <option value="P" selected>Por Porcentaje</option>
            <option value="M">Por Monto</option>
          </select>
          </label></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Porcentaje de Aumento </div></td>
          <td>
            <div align="left">
              <input name="txtporaum" type="text" id="txtporaum" onKeyPress="javascript: return(ue_formatonumero(this,'.',',',event));" value="<?php print $li_poraum;?>" size="10" maxlength="5" style="text-align:right">
            </div></td>
          <td><div align="right">Monto de Aumento </div></td>
          <td><input name="txtmonaum" type="text" id="txtmonaum" value="<?php print $li_monaum;?>" size="23" maxlength="20" onKeyPress="javascript: return(ue_formatonumero(this,'.',',',event));" style="text-align:right" readonly></td>
          </tr>
<?php } ?>		
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3"><input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion;?>">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
            <input name="rac" type="hidden" id="rac" value="<?php print $li_rac;?>"></td>
        </tr>
        <tr>
          <td colspan="4"><div align="center">
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

function ue_guardar()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_cambiar=f.cambiar.value;
		if(li_cambiar==1)
		{
			if(f.rac.value=="0")
			{
				if((f.operacion.value=="BUSCAR")||(f.operacion.value=="PROCESAR"))
				{
					totperfil = ue_validarvacio(f.txttotperfil.value);
					peractnom = ue_validarvacio(f.txtperactnom.value);
					totrow = ue_validarvacio(f.totalfilas.value);
					if ((totperfil!="")&&(totperfil!="0")&&(peractnom!="")&&(totrow!="")&&(totrow!="0"))
					{
						f.operacion.value="GUARDAR";
						f.action="sigesp_sno_p_ajustarsueldo.php";
						f.submit();
					}
					else
					{
						alert("Debe llenar todos los datos.");
					}
				}
				else
				{
					alert("Primero debe consultar la información");
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
			sueperdes=f.txtsueperdes.value;
			while(sueperdes.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				sueperdes=sueperdes.replace(".","");
			}
			sueperdes=sueperdes.replace(",",".");

			sueperhas=f.txtsueperhas.value;
			while(sueperhas.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				sueperhas=sueperhas.replace(".","");
			}
			sueperhas=sueperhas.replace(",",".");
			if(eval(sueperdes)<=eval(sueperhas))
			{
				f.operacion.value="BUSCAR";
				f.action="sigesp_sno_p_ajustarsueldo.php";
				f.submit();
			}
			else
			{
				alert("El rango de los sueldos esta erroneo.");
			}
		}
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
		if(f.rac.value=="0")
		{
			if((f.operacion.value=="BUSCAR")||(f.operacion.value=="PROCESAR"))
			{
				totperfil = ue_validarvacio(f.txttotperfil.value);
				if((totperfil!="")&&(totperfil!="0"))
				{
					f.operacion.value="PROCESAR";
					f.action="sigesp_sno_p_ajustarsueldo.php";
					f.submit();
				}
				else
				{
					alert("no hay personal que procesar");
				}
			}
			else
			{
				alert("Primero debe consultar la información");
			}
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_buscarcargo()
{
	window.open("sigesp_sno_cat_cargo.php?tipo=ajustarsueldo","Catálogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarasignacioncargo()
{
	window.open("sigesp_sno_cat_asignacioncargo.php?tipo=ajustarsueldo","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function uf_bloquear()
{
	f=document.form1;
	if(f.cmbtipaum.value=="P")
	{
		f.txtporaum.readOnly=false;
		f.txtmonaum.readOnly=true;
		f.txtmonaum.value="0";
	}
	if(f.cmbtipaum.value=="M")
	{
		f.txtporaum.readOnly=true;
		f.txtmonaum.readOnly=false;
		f.txtporaum.value="0";
	}
}
</script> 
</html>