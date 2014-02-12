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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_d_persxconst.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
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
   		global $ls_codper,$ls_nomper,$ls_cedper,$ls_uniad,$ld_sueper,$ls_desnom,$li_totrows,$ls_operacion;
		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$io_fun_nomina,$ls_desper,$li_calculada;
		
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		$ls_codper="";
		$ls_nomper="";
		$ls_cedper="";
		$ls_uniad="";
		$ld_sueper="";
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_titletable="Constantes Asociadas";
		$li_widthtable=550;
		$ls_nametable="grid";
		$lo_title[1]="Codigo";
		$lo_title[2]="Nombre";
		$lo_title[3]="Tope";
		$lo_title[4]="Valor";		
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
		$aa_object[$ai_totrows][1]="<input type=text name=txtcod".$ai_totrows." class=sin-borde  size=20 readonly>";
		$aa_object[$ai_totrows][2]="<input type=text name=txtnom".$ai_totrows." size=30 class=sin-borde readonly >";
		$aa_object[$ai_totrows][3]="<input type=text name=txttopcon".$ai_totrows." class=sin-borde size=15 style=text-align:right readonly>";
		$aa_object[$ai_totrows][4]="<input type=text name=txtmon".$ai_totrows." class=sin-borde size=15 style=text-align:right readonly>";
		
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
<title>Definici&oacute;n de Constantes x Personal.</title>
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
</head>
<body>
<?php 
	require_once("sigesp_sno_c_constantes.php");
	$io_constante=new sigesp_sno_c_constantes();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_agregarlineablanca($lo_object,1);
			break;

		case "GUARDAR":
			$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_cedper=$_POST["txtcedper"];
			$ls_uniad=$_POST["txtuniad"];
			$ld_sueper=$_POST["txtsueper"];
			$lb_valido=true;
			$io_constante->io_sql->begin_transaction();
			for($li_i=1;$li_i<=$li_totrows&&$lb_valido;$li_i++)
			{
				$ls_codcons=$_POST["txtcod".$li_i];  
				$li_moncon=$_POST["txtmon".$li_i]; 
				$li_topcon=$_POST["txttopcon".$li_i];
				$lb_valido=$io_constante->uf_update_constantes_x_personal($ls_codcons,$ls_codper,$li_moncon,$li_topcon,$la_seguridad);
			}
			if($lb_valido)
			{
				$io_constante->io_sql->commit();
				$io_constante->io_mensajes->message("Las constantes del Personal fueron Actualizados.");
				$lb_valido=$io_constante->uf_load_constantes_x_personal($ls_codper,$li_totrows,$lo_object);
			}
			else
			{
				$io_constante->io_sql->rollback();
				$io_constante->io_mensajes->message("Ocurrio un error al actualizar las constantes del personal.");
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			break;
			
		case "BUSCARDETALLE":
			$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_cedper=$_POST["txtcedper"];
			$ls_uniad=$_POST["txtuniad"];
			$ld_sueper=$_POST["txtsueper"];
			$lb_valido=$io_constante->uf_load_constantes_x_personal($ls_codper,$li_totrows,$lo_object);
			if ($lb_valido==false)
			{
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			break;
	}
	$io_constante->uf_destructor();
	unset($io_constante);
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
                <td height="20" colspan="4"><div align="center">Constantes x Personal </div></td>
              </tr>
              <tr >
                <td height="22" colspan="4">&nbsp; </td>
              </tr>
              <tr>
                <td width="146" height="22"><div align="right" >
                    <p>Codigo</p>
                </div></td>
                <td width="210">                      <div align="left">
                  <input name="txtcodper" type="text" class="sin-borde3" id="txtcodper" style="text-align:left "  value="<?php print $ls_codper ?>" size="13" maxlength="10"  readonly>
                </div></td>
                <td width="67"><div align="right">Cedula</div></td>
                <td width="149"><div align="left">
                  <input name="txtcedper" type="text" class="sin-borde3" id="txtnomcon2" style="text-align:left" value="<?php print $ls_cedper ?>" size="13" maxlength="10" readonly>
</div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Nombre</div></td>
                <td colspan="3"><div align="left">
                    <input name="txtnomper" type="text" class="sin-borde3" id="txtnomper"  value="<?php print $ls_nomper ?>" size="50" maxlength="40" readonly>
                </div></td>
              </tr>
              <tr >
                <td height="22"><div align="right">Unidad Administrativa</div></td>
                <td>                <div align="left">
                  <input name="txtuniad" type="text" class="sin-borde3" id="txtuniad" value="<?php print $ls_uniad ?>" size="33" maxlength="30" readonly>
</div></td>
                <td><div align="right">Sueldo</div></td>
                <td><div align="left">
                  <input name="txtsueper" type="text" class="sin-borde3" id="txtsueper2"  value="<?php print $ld_sueper ?>" size="28" maxlength="25" readonly>
                </div></td>
              </tr>
            <tr>
              <td height="18" colspan="4"><div align="center">
		    <?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
                <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows; ?>">
                <input name="operacion" type="hidden" id="operacion">
				<input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>">
              </div></td>
            </tr>
            <tr>
              <td height="18" colspan="4"></td>
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
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_cambiar=f.cambiar.value;
		if(li_cambiar==1)
		{
			lb_valido=true;
			li_total=f.totalfilas.value
			for(li_i=1;((li_i<=li_total)&&(lb_valido));li_i++)
			{
				tope=eval("f.txttopcon"+li_i+".value");
				while(tope.indexOf('.')>0)
				{
					tope=tope.replace(".","");
				}
				tope=tope.replace(",",".");		
				valor=eval("f.txtmon"+li_i+".value");
				while(valor.indexOf('.')>0)
				{
					valor=valor.replace(".","");
				}
				valor=valor.replace(",",".");		
				if(tope!=0)
				{
					if(parseFloat(valor)>parseFloat(tope))
					{
					   lb_valido=false;
					   alert("El valor no es valido, es mayor que el tope "+eval("f.txttopcon"+li_i+".value"));
					   eval("f.txtmon"+li_row+".value=0"); 
					}
				}
	
			}
			if(lb_valido)
			{
				f.operacion.value ="GUARDAR";
				f.action="sigesp_sno_d_persxconst.php";
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
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_sno_cat_persxconst.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_mayor(li_row)
{
	f=document.form1;
	tope=eval("f.txttopcon"+li_row+".value");
	while(tope.indexOf('.')>0)
	{
		tope=tope.replace(".","");
	}
	tope=tope.replace(",",".");		
	valor=eval("f.txtmon"+li_row+".value");
	while(valor.indexOf('.')>0)
	{
		valor=valor.replace(".","");
	}
	valor=valor.replace(",",".");		
	if(tope!=0)
	{
		if(parseFloat(valor)>parseFloat(tope))
		{
		   alert("El valor no es valido, es mayor que el tope "+eval("f.txttopcon"+li_row+".value"));
		   eval("f.txtmon"+li_row+".value=0"); 
		}
	}
}



function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank_nomina.php";
	f.submit();
}
</script>
</html>
