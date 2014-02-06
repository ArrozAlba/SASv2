<?Php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_sob.php");
	$io_fun_sob=new class_funciones_sob();
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_d_unidad.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$la_datemp=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definici&oacute;n de Unidades</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
		<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
			
              <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Obras </td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>
	</td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>
	<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>	</td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?Php
	require_once("class_folder/sigesp_sob_c_unidad.php");
	require_once("../shared/class_folder/class_mensajes.php");
	$is_msg=new class_mensajes();
	$io_unidad=new sigesp_sob_c_unidad();
	
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_coduni=$_POST["txtcoduni"];
		$ls_codtun=$_POST["txtcodtun"];
		$ls_nomtun=$_POST["txtnomtun"];
		$ls_nomuni=$_POST["txtnomuni"];
		$ls_desuni=$_POST["txtdesuni"];
		$ls_status=$_POST["hidstatus"];
	}
	else
	{
		$ls_operacion="ue_nuevo";
		$ls_coduni="";
		$ls_codtun="";
		$ls_nomtun="";
		$ls_nomuni="";
		$ls_desuni="";
		$ls_status="";
	}
	
	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_nuevo")
	{
		require_once("../shared/class_folder/class_funciones_db.php");
		require_once ("../shared/class_folder/sigesp_include.php");		
		require_once("../shared/class_folder/class_sql.php");
		
		$sig_inc=new sigesp_include();
		$con=$sig_inc->uf_conectar();
		$io_funcdb=new class_funciones_db($con);
		$ls_codemp=$la_datemp["codemp"];
//		$ls_coduni=$io_funcdb->uf_generar_codigo(true,$ls_codemp,"sob_unidad","coduni");
		require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
		$io_keygen= new sigesp_c_generar_consecutivo();
		$ls_coduni= $io_keygen->uf_generar_numero_nuevo("SOB","sob_unidad","coduni","SOB",3,"","","");
		
		$ls_codtun="";
		$ls_nomtun="";
		$ls_nomuni="";
		$ls_desuni="";
		$ls_status="";
	}
	elseif($ls_operacion=="ue_guardar")
	{
		$ls_coduniaux=$ls_coduni;
		$lb_valido=$io_unidad->uf_guardar_unidad($ls_coduni,$ls_codtun,$ls_nomuni,$ls_desuni,$ls_status,$la_seguridad);
		$ls_mensaje=$io_unidad->io_msgc;
		if ($lb_valido===true)
		{
			if($ls_coduniaux!=$ls_coduni)
			{
				$is_msg->message("Se le asigno un nuevo numero ".$ls_coduni);
			}
			$is_msg->message ($ls_mensaje);
			$ls_coduni="";
			$ls_codtun="";
			$ls_nomtun="";
			$ls_nomuni="";
			$ls_desuni="";
		}else
		{
			if($lb_valido==0)
			{
				$ls_coduni="";
				$ls_codtun="";
				$ls_nomtun="";
				$ls_nomuni="";
				$ls_desuni="";
			}
			else
			{
				$is_msg->message ($ls_mensaje);
			}
		}
	}
	elseif($ls_operacion=="ue_eliminar")
	{
		$lb_valido=$io_unidad->uf_delete_unidad($ls_coduni,$ls_codtun,$la_seguridad);			
		if ($lb_valido===true)
		{
			$is_msg->message($io_unidad->io_msgc);
			$ls_coduni="";
			$ls_codtun="";
			$ls_nomtun="";
			$ls_nomuni="";
			$ls_desuni="";
		}
		
	}
	
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_sob->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_sob);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

    <table width="518" height="197" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="195"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="2" class="titulo-ventana">Definici&oacute;n de Unidades de Medida </td>
            </tr>
            <tr>
              <td >
			  <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">
			  <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_status; ?>" >
			  </td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td width="134" height="22" align="right"><span class="style2">C&oacute;digo</span></td>
              <td width="334" ><input name="txtcoduni" style="text-align:center " type="text" id="txtcoduni" value="<?php print  $ls_coduni?>" size="3" maxlength="3"  readonly="true">
              </td>
            </tr>
			<tr>
              <td width="134" height="22" align="right"><span class="style2">Tipo de Unidad</span></td>
              <td width="334" ><input name="txtcodtun" type="text" style="text-align:center " id="txtcodtun" value="<?php print  $ls_codtun?>" size="3" maxlength="3"  readonly="true">
              <a href="javascript:ue_cattipo();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de tipos de unidades"></a>
              <input name="txtnomtun" type="text" id="txtnomtun" value="<?php print $ls_nomtun;?>" class="sin-borde" size="40" readonly></td>
            </tr>
            <tr align="left">
              <td height="22" align="right"><span class="style2">Nombre</span></td>
              <td><input name="txtnomuni" id="txtnomuni" value="<?php print $ls_nomuni?>" type="text" size="30" maxlength="30" onKeyPress="return(validaCajas(this,'x',event))"></td>
            </tr>
            <tr>
              <td height="4" align="right">Descripci&oacute;n</td>
              <td><textarea name="txtdesuni" cols="56" rows="2" id="txtdesuni" value="<?php print $ls_desuni?>" onKeyDown="textCounter(this,254);" onKeyUp="textCounter(this,254);" onKeyPress="return(validaCajas(this,'x',event))"><?php print $ls_desuni?></textarea></td>
            </tr>
            <tr>
              <td height="8">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
  </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
</body>

<script language="JavaScript">


/*******************************************************************************************************************************/

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{		
		f.operacion.value="ue_nuevo";
		f.txtcoduni.value="";
		f.txtcodtun.value="";
		f.txtnomtun.value="";
		f.txtnomuni.value="";
		f.txtdesuni.value="";
		f.action="sigesp_sob_d_unidad.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}	
}


function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		with(f)
		{
			if (ue_valida_null(txtcoduni,"Código")==false)
			{
				txtcoduni.focus();
			}
			else
			{ 
				if (ue_valida_null(txtcodtun,"Tipo de Unidad")==false)
				{
				txtcodtun.focus();
				}
				else
				{
					if (ue_valida_null(txtnomuni,"Nombre")==false)
					{
						txtnomuni.focus();
					}
					else
					{
						f.operacion.value="ue_guardar";
						f.action="sigesp_sob_d_unidad.php";
						f.submit();
					}
				} 
			}
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
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		with(f)
		{
			if (ue_valida_null(txtcoduni,"Código")==false)
			{
				txtcoduni.focus();
			}
			else
			{
				if (confirm("¿ Esta seguro de eliminar este registro ?"))
				{ 		
					f.operacion.value="ue_eliminar";
					f.action="sigesp_sob_d_unidad.php";
					f.submit();
				}
				else
				{ 
					f=document.form1;
					f.action="sigesp_sob_d_unidad.php";
					alert("Eliminación Cancelada !!!");
					f.txtcoduni.value="";
					f.txtcodtun.value="";
					f.txtnomtun.value="";
					f.txtnomuni.value="";
					f.txtdesuni.value="";
					f.operacion.value="";
					f.submit();
				}
			}	   
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
	if(li_leer==1)
	{
		f.operacion.value="";			
		pagina="sigesp_cat_unidades.php";
		popupWin(pagina,"catalogo",650,300);
		//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=300,resizable=yes,location=no");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

/***************************************************************************************************************************/
		function ue_cargarunidades(c,d,e,x,g)
		{
			f=document.form1;
			f.txtcoduni.value=c;
			f.txtcodtun.value=d;
	        f.txtnomtun.value=e;
	        f.txtnomuni.value=x;
         	f.txtdesuni.value=g;
			f.hidstatus.value="C";
			
		}	
		
		function ue_cargartipounidad(h,i,j)
		{
			f=document.form1;
			f.txtcodtun.value=h;
	        f.txtnomtun.value=i;
	    }
/***********************************************************************************************************************************/        
		
		function ue_cattipo()
		{
            f=document.form1;
			if (f.hidstatus.value!="C")
			{
				f.operacion.value="";						
				pagina="sigesp_cat_tipounidades.php";
				popupWin(pagina,"catalogo",520,200);
				//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
			}
			else
			{
				alert("Este campo no puede ser modificado!!!");
			}
		}
/***********************************************************************************************************************************/

</script>
</html>