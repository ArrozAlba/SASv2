<?php
	session_start();
	$dat=$_SESSION["la_empresa"];
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definici&oacute;n de Agencias</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
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
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div>      <div align="center"></div>      <div align="center"></div>      <div align="center"></div>      <div align="center"></div></td>
  </tr>
</table>
<?php
require_once("../../shared/class_folder/class_mensajes.php");
require_once("sigesp_scb_c_agencia.php");
require_once("../../shared/class_folder/class_funciones_db.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/sigesp_c_check_relaciones.php");
require_once("../../shared/class_folder/sigesp_c_generar_consecutivo.php");
$io_keygen= new sigesp_c_generar_consecutivo();

$io_conect = new sigesp_include();
$conn      = $io_conect->uf_conectar();
$fun_db    = new class_funciones_db($conn);
$io_msg    = new class_mensajes();	
$io_sql    = new class_sql($conn);	
$io_chkrel = new sigesp_c_check_relaciones($conn);
	//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre       = $_SESSION["la_empresa"];
	$ls_empresa = $arre["codemp"];
	$ls_codemp  = $ls_empresa;
	if(array_key_exists("la_logusr",$_SESSION))
	{
		$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
		$ls_logusr="";
	}
	$ls_sistema="CFG";
	$ls_ventanas="sigesp_scb_d_agencia.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventanas;
    $in_classagencia=new sigesp_scb_c_agencia($la_security); 
	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos=             $_POST["permisos"];
			$la_accesos["leer"]=     $_POST["leer"];
			$la_accesos["incluir"]=  $_POST["incluir"];
			$la_accesos["cambiar"]=  $_POST["cambiar"];
			$la_accesos["eliminar"]= $_POST["eliminar"];
			$la_accesos["imprimir"]= $_POST["imprimir"];
			$la_accesos["anular"]=   $_POST["anular"];
			$la_accesos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]="";
		$la_accesos["incluir"]="";
		$la_accesos["cambiar"]="";
		$la_accesos["eliminar"]="";
		$la_accesos["imprimir"]="";
		$la_accesos["anular"]="";
		$la_accesos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
	//Inclusión de la clase de seguridad.
	
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////


	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_codigo   = $_POST["txtcodigo"];
		$ls_denominacion= $_POST["txtdenominacion"];
		$ls_codban   = $_POST["txtcodban"];
		$ls_denban   = $_POST["txtdenban"];
		$readonly    = "";
		$ls_status=$_POST["status"];
	}
	else
	{
		$ls_operacion= "NUEVO";
		$ls_codigo   = "";
		$ls_denominacion= "";
		$ls_codban   = "";
		$ls_denban   = "";
		$readonly="";
		$ls_status="N";
	}
	if($ls_operacion == "NUEVO")
	{
		$ls_codigo= $io_keygen->uf_generar_numero_nuevo("CFG","scb_agencias","codage","CFGAGE",10,"","","");
//		$ls_codigo   = $fun_db->uf_generar_codigo(true,$ls_empresa,"scb_agencias","codage");
		$ls_denominacion= "";
		$ls_codban   = "";
		$ls_denban   = "";
		$readonly="";
		$ls_status="N";

	}
	if($ls_operacion == "GUARDAR")
	{
		$ls_codigoaux=$ls_codigo;
		$lb_valido=$in_classagencia->uf_guardar_agencias($ls_codban,$ls_codigo,$ls_denominacion,$ls_status);
		if(($lb_valido)&&($ls_codigoaux!=$ls_codigo))
		{
			$io_msg->message("Se le asigno el codigo ".$ls_codigo);
		}
		$io_msg->message($in_classagencia->is_msg_error);
		$readonly="readonly";
	}
if ($ls_operacion == "ELIMINAR")
   {
	 $lb_existe = $in_classagencia->uf_select_agencias($ls_codigo,$ls_codban);
	 if ($lb_existe)
	    {
		  $ls_condicion = " AND (column_name='codage')";//Nombre del o los campos que deseamos buscar.
		  $ls_mensaje   = "";                              //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
		  $lb_tiene     = $io_chkrel->uf_check_relaciones($ls_codemp,$ls_condicion,'scb_agencias',$ls_codigo,$ls_mensaje);//Verifica los movimientos asociados a la cuenta 
		  if (!$lb_tiene)
			 {
               $lb_valido = $in_classagencia->uf_delete_agencias($ls_codigo,$ls_codban,$ls_denominacion,$ls_status);         
			   if ($lb_valido) 
			      {
				    $io_sql->commit();
				    $io_msg->message("Registro Eliminado !!!");
					$ls_codigo= $io_keygen->uf_generar_numero_nuevo("CFG","scb_agencias","codage","CFGAGE",10,"","","");
				  	//$ls_codigo       = $fun_db->uf_generar_codigo(true,$ls_empresa,"scb_agencias","codage");
					$ls_denominacion = "";
					$ls_codban       = "";
					$ls_denban       = "";
					$readonly        = "";
				  }
			   else
			      {
 					$io_sql->rollback();
					$io_msg->message($in_classagencia->is_msg_error);
				  }
			 }
          else
		     {
               $io_msg->message($io_chkrel->is_msg_error);
			 }
        }
	 else
	    {
          $io_msg->message("Este Registro No Existe !!!");
		}	
   }
?>
<input type="hidden" name="hiddenField">
<p>&nbsp;</p>
<div align="center">
  <table width="601" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="601" height="221" valign="top"><form name="form1" method="post" action="">
		<?php 
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer     value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir  value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar  value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular   value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
        ?>
        <p>&nbsp;</p>
        <table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="3">Definici&oacute;n de Agencias </td>
              </tr>
              <tr class="formato-blanco">
                <td height="13">&nbsp;</td>
                <td width="483" height="13" colspan="2">&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td width="81" height="22"><div align="right" >
                    <p>C&oacute;digo</p>
                </div></td>
                <td height="22" colspan="2"><div align="left" >
                    <input name="txtcodigo" type="text" id="txtcodigo" style="text-align:center " value="<?php print $ls_codigo?>" size="15" maxlength="10" onBlur="javascript:rellenar_cad(this.value,10,'cod')" <?php print $readonly ?> onKeyPress="return keyRestrict(event,'1234567890');">
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Denominaci&oacute;n</div></td>
                <td height="22" colspan="2"><div align="left">
                  <input name="txtdenominacion" type="text" id="txtdenominacion" style="text-align:left" value="<?php print $ls_denominacion?>" size="85" maxlength="80">
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Banco</div></td>
                <td height="22" colspan="2" align="left"><div align="left">
                  <input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
                  <a href="javascript:cat_bancos();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas de Gastos"></a>                  
                  <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="70" class="sin-borde" readonly>
                </div></td>
              </tr>
            <tr class="formato-blanco">
              <td height="22">&nbsp;</td>
              <td height="22" colspan="2">&nbsp;</td>
            </tr>
          </table>
            <p align="center">
            <input name="operacion" type="hidden" id="operacion">
            <input name="status" type="hidden" id="status" value="<?php print $ls_status;?>">
          </p>
        </form></td>
      </tr>
  </table>
</div>
</body>
<script language="javascript">

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if (li_incluir==1)
	   {	
         f.operacion.value ="NUEVO";
         f.action="sigesp_scb_d_agencia.php";
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
	lb_status=f.status.value;
	if (((lb_status=="C")&&(li_cambiar==1))||(lb_status=="N")&&(li_incluir==1))
	   {
	     ls_codigo=f.txtcodigo.value;
	     ls_denominacion=f.txtdenominacion.value;
	     ls_codban=f.txtcodban.value;
	     if ((ls_codigo!="")&&(ls_denominacion!="")&&(ls_codban!=""))
	        {
		      f.operacion.value ="GUARDAR";
		      f.action="sigesp_scb_d_agencia.php";
		      f.submit();
	        }
	     else
	        {
		      alert("No ha completado los datos");
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
li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
     if (confirm("¿ Está seguro de eliminar este registro ?"))
		{
	      ls_codban=f.txtcodban.value;
	      ls_codigo=f.txtcodigo.value;
	      ls_denominacion=f.txtdenominacion.value;
	      if ((ls_codigo!="")&&(ls_denominacion!="")&&(ls_codban!=""))
	         {
		       f.operacion.value ="ELIMINAR";
		       f.action="sigesp_scb_d_agencia.php";
		       f.submit();
	         }
	      else
	         {
		       alert("No ha completado los datos");
	         }
        }
     else
	    {
	      alert("Eliminación Cancelada !!!");
	    }
   }  
 else
   {
     alert("No tiene permiso para realizar esta operación");
   }
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
       {
	     window.open("sigesp_scb_cat_agencias.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
       }
	else
	   {
		 alert("No tiene permiso para realizar esta operación");
	   }   
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

    //Funciones de validacion de fecha.
	function rellenar_cad(cadena,longitud,campo)
	{
		var mystring=new String(cadena);
		cadena_ceros="";
		lencad=mystring.length;
	
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
		if(campo=="doc")
		{
			document.form1.txtdocumento.value=cadena;
		}
		if(campo=="cmp")
		{
			document.form1.txtcomprobante.value=cadena;
		}
		if(campo=="cod")
		{
			document.form1.txtcodigo.value=cadena;
		}
	}
	
	//Catalogo de cuentas contables
	function catalogo_cuentasSCG()
	 {
	   f=document.form1;
	   pagina="sigesp_cat_filt_scg.php?filtro=1110102";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
	 
	 function cat_tipo_cuenta()
	 {
	   f=document.form1;
	   pagina="sigesp_cat_tipoctas.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
	 
	 function cat_bancos()
	 {
	   f=document.form1;
	   pagina="sigesp_cat_bancos.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no");
	 }

	function currencyDate(date)
    { 
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string);
			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}
			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
			//alert(ls_long);


  //  return false; 
   } 
   
    function valSep(oTxt){ 
    var bOk = false; 
    var sep1 = oTxt.value.charAt(2); 
    var sep2 = oTxt.value.charAt(5); 
    bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
    bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
    return bOk; 
   } 

   function finMes(oTxt){ 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    var nAno = parseInt(oTxt.value.substr(6), 10); 
    var nRes = 0; 
    switch (nMes){ 
     case 1: nRes = 31; break; 
     case 2: nRes = 28; break; 
     case 3: nRes = 31; break; 
     case 4: nRes = 30; break; 
     case 5: nRes = 31; break; 
     case 6: nRes = 30; break; 
     case 7: nRes = 31; break; 
     case 8: nRes = 31; break; 
     case 9: nRes = 30; break; 
     case 10: nRes = 31; break; 
     case 11: nRes = 30; break; 
     case 12: nRes = 31; break; 
    } 
    return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
   } 

   function valDia(oTxt){ 
    var bOk = false; 
    var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
    bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
    return bOk; 
   } 

   function valMes(oTxt){ 
    var bOk = false; 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
    return bOk; 
   } 

   function valAno(oTxt){ 
    var bOk = true; 
    var nAno = oTxt.value.substr(6); 
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
    if (bOk){ 
     for (var i = 0; i < nAno.length; i++){ 
      bOk = bOk && esDigito(nAno.charAt(i)); 
     } 
    } 
    return bOk; 
   } 

   function valFecha(oTxt){ 
    var bOk = true; 
	
		if (oTxt.value != ""){ 
		 bOk = bOk && (valAno(oTxt)); 
		 bOk = bOk && (valMes(oTxt)); 
		 bOk = bOk && (valDia(oTxt)); 
		 bOk = bOk && (valSep(oTxt)); 
		 if (!bOk){ 
		  alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
		  oTxt.value = "01/01/1900"; 
		  oTxt.focus(); 
		 } 
		}
	 
   }

  function esDigito(sChr){ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
   }
   
</script>
</html>
