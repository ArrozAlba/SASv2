<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	
	
	$dat           = $_SESSION["la_empresa"];
	$ls_nomestpro1 = $dat["nomestpro1"];
	$_SESSION["ir3"]='0';
	$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definición de <?php print $ls_nomestpro1?> </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>

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
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div>      <div align="center"></div>      <div align="center"></div>      <div align="center"></div></td>
  </tr>
</table>
<p>
  <?php
	require_once("class_folder/sigesp_spg_c_estprog1.php");
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../cfg/class_folder/class_funciones_configuracion.php");
    $io_fun_cfg=new class_funciones_configuracion();
	$io_conect  = new sigesp_include();
    $conn       = $io_conect->uf_conectar();
	$io_funcion = new class_funciones(); 
	$io_msg     = new class_mensajes();
	$io_sql     = new class_sql($conn);

    $ls_estint=$io_fun_cfg->uf_obtenervalor_get("estint","");
	/////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre                     = $_SESSION["la_empresa"];
	$ls_empresa               = $arre["codemp"];
	$ls_codemp                = $ls_empresa;
	$ls_logusr                = $_SESSION["la_logusr"];
	$ls_sistema               = "CFG";
	$ls_ventanas              = "sigesp_spg_d_estprog1.php";
	
	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;
    $li_estmodest             = $arre["estmodest"];
	
	if  (array_key_exists("rbclasificacion",$_POST))
	{
  	  $ls_clasificacion = $_POST["rbclasificacion"];
	}
 
	
	if (array_key_exists("permisos1",$_POST)||($ls_logusr=="PSEGIS"))
	   {	
		 if ($ls_logusr=="PSEGIS")
		    {
			  $ls_permisos="";
		    }
		 else
		    {
			  $ls_permisos            = $_POST["permisos1"];
			  $la_accesos["leer"]     = $_POST["leer"];			
			  $la_accesos["incluir"]  = $_POST["incluir"];			
			  $la_accesos["cambiar"]  = $_POST["cambiar"];
			  $la_accesos["eliminar"] = $_POST["eliminar"];
			  $la_accesos["imprimir"] = $_POST["imprimir"];
			  $la_accesos["anular"]   = $_POST["anular"];
			  $la_accesos["ejecutar"] = $_POST["ejecutar"];
		    }
	   }
	else
	   {
		 $la_accesos["leer"]     = "";	 	
		 $la_accesos["incluir"]  = "";
		 $la_accesos["cambiar"]  = "";
		 $la_accesos["eliminar"] = "";
		 $la_accesos["imprimir"] = "";
		 $la_accesos["anular"]   = "";
		 $la_accesos["ejecutar"] = "";
		 $ls_permisos            = $io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	   }
	$io_estpro1 = new sigesp_spg_c_estprog1($conn);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
global $readonly,$ls_clasificacion;
if  (array_key_exists("status",$_POST))
	{
  	  $ls_status=$_POST["status"];
	}
else
	{
	  $ls_status="NUEVO";
	  //	  
	}	

if ( array_key_exists("operacion",$_POST))
   {
	 $ls_operacion     = $_POST["operacion"];
	 $ls_codestpro1    = $_POST["txtcodestpro1"];
	 $ls_denestpro1    = $_POST["txtdenestpro1"];
	 $ls_clasificacion = $_POST["rbclasificacion"];
	 $disabled         = "";
	 $readonly         = "";
   }
else
   {
	 $ls_operacion="";
	 
	 if (array_key_exists("txtcodestpro1",$_POST))
		{
		  $ls_codestpro1 = $_POST["txtcodestpro1"];
		  $ls_denestpro1 = $_POST["txtdenestpro1"];
		}
	 else
		{
		  $ls_codestpro1 = "";
		  $ls_denestpro1 = "";
		}
   
   if (array_key_exists("go",$_SESSION))
   {
			if($_SESSION["go"]=='1')
			{
				if (array_key_exists("txtclasificacion",$_POST))
				{
					$ls_clasificacion=$_POST["txtclasificacion"];
					$readonly         = "readonly";
					$_SESSION["go"]='0';
				}
				else
				{
					$ls_clasificacion="";
				
				}
				
				
			}else
			{
				$_SESSION["go"]='0';
				$ls_clasificacion = "P";
				$disabled         = "disabled";
				$readonly         = "";
			 
			
			}
		}
	 
   }
if (array_key_exists("chkintercom",$_POST))
   {
     $ls_chkintercom = $_POST["chkintercom"];
	 $ls_cuenta=$_POST["txtcuenta"];
	 $ls_denocuenta=$_POST["txtdencuenta"];
   }
else
	{
	  $ls_chkintercom = "0";
	  $ls_cuenta="";
      $ls_denocuenta="";	  
	}
if ($ls_operacion == "NUEVO" )
   {
	 $ls_codestpro1    = "";
	 $ls_denestpro1    = "";
	 $ls_clasificacion = "P";
	 $disabled         = "disabled";
	 $readonly         = "";
	 $ls_chkintercom="0";
	 $ls_cuenta="";
	 $ls_denocuenta="";
	 $ls_status        = "NUEVO";
   }

	
if ($ls_operacion == "GUARDAR")
   {
	 //$ls_clasificacion = $_POST["rbclasificacion"];
	 $ls_codestpro1    = $io_funcion->uf_cerosizquierda($ls_codestpro1,25);
	 $lb_existe        = $io_estpro1->uf_spg_select_estprog1($ls_codemp,$ls_codestpro1,$ls_clasificacion); 
	 if (!$lb_existe)
	    {
          $lb_valido = $io_estpro1->uf_spg_insert_estprog1($ls_codemp,$ls_codestpro1,$ls_denestpro1,$ls_clasificacion,$ls_chkintercom,$ls_cuenta,$la_seguridad);		
		  if ($lb_valido)
		     {
			   $io_sql->commit();
			   $io_msg->message("Registro Incluido !!!");
			   $ls_codestpro1 = "";
			   $ls_denestpro1 = "";
			   $ls_chkintercom="";
			   $ls_cuenta="";
			   $ls_denocuenta="";
	   		   $ls_status = "NUEVO";
			 }
		  else
		     {
			   $io_sql->rollback();
			   $io_msg->message($io_estpro1->is_msg_error);
			 }
		}
	 else
	    { 
		  $lb_valido = $io_estpro1->uf_spg_update_estprog1($ls_codemp,$ls_codestpro1,$ls_denestpro1,$ls_clasificacion,$ls_chkintercom,$ls_cuenta,$la_seguridad);		
		  if ($lb_valido)
		     {
			   $io_sql->commit();
			   $io_msg->message("Registro Actualizado !!!");
			   $ls_codestpro1 = "";
			   $ls_denestpro1 = "";
			   $ls_chkintercom="";
	           $ls_cuenta="";
	           $ls_denocuenta="";
	   		   $ls_status     = "NUEVO";
			 }
		  else
		     {
			   $io_sql->rollback();
			   $io_msg->message($io_estpro1->is_msg_error);
			 }
		}
   }	 
	
if ($ls_operacion == "ELIMINAR")
   {
     
	 $ls_codestpro1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,25);
	 
	 $lb_valido     = $io_estpro1->uf_spg_delete_estpro1($ls_codemp,$ls_codestpro1,$ls_denestpro1,$ls_clasificacion,$ls_chkintercom,$ls_cuenta,$la_seguridad);
	 if ($lb_valido)
	    {
		  $io_sql->commit();
		  $io_msg->message("Registro Eliminado !!!");
		}
	 else
	    {
		  $io_sql->rollback();
		  $io_msg->message($io_estpro1->is_msg_error);
		}
	 $ls_codestpro1 = "";
	 $ls_denestpro1 = "";
	 $ls_chkintercom="";
	 $ls_cuenta="";
	 $ls_denocuenta="";
	 $ls_status     = "NUEVO";
 	 $readonly      = "";
   }
	
if ($ls_operacion == "BUSCAR")
   {
     $ls_codestpro1=$_POST["txtcodestpro1"];
	 $ls_denestpro1=$_POST["txtdenestpro1"];
	 $ls_clasificacion=$_POST["rbclasificacion"];
	 $ls_chkintercom=$_POST["chkintercom"];
	 $ls_cuenta=$_POST["txtcuenta"];
	 $ls_denocuenta=$_POST["txtdencuenta"];

	 $disabled="";
	 $readonly="readonly";
   }
	
	
?>
</p>
<p>&nbsp;</p>
<form name="formulario" method="post" action="">
  <table width="625" height="230" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="622" height="226"><div align="center">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos1 id=permisos1 value='$ls_permisos'>");
	print("<input type=hidden name=leer      id=leer      value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir   id=incluir   value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar   id=cambiar   value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar  id=eliminar  value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir  id=imprimir  value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular    id=anular    value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar  id=ejecutar  value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
?>
          <table width="565" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr class="titulo-ventana">
              <td height="22" colspan="3"><input name="hidmaestro" type="hidden" id="hidmaestro" value="Y">
              <?php print $ls_nomestpro1?></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22">&nbsp;</td>
              <td height="22" colspan="2"><input name="status" type="hidden" id="status" value="<?php print $ls_status ?>"></td>
            </tr>
            <tr class="formato-blanco">
              <td width="94" height="22"><div align="right" >
                  <p>Codigo</p>
              </div></td>
              <td height="22" colspan="2"><div align="left" >
                  <input name="txtcodestpro1" type="text" id="txtcodestpro1" style="text-align:center " value="<?php print $ls_codestpro1 ?>" size="<?php print $ls_loncodestpro1+10 ?>" maxlength="<?php print $ls_loncodestpro1 ?>" onBlur="javascript:rellenar_cad(this.value,<?php print $ls_loncodestpro1 ?>,'cod')" <?php print $readonly ?> onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz '+'.,-');">
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22"><div align="right">Denominaci&oacute;n</div></td>
              <td height="22" colspan="2"><div align="left">
                  <input name="txtdenestpro1" type="text" id="txtdenestpro1" style="text-align:left" value="<?php print $ls_denestpro1 ?>" size="82" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',-.;*&?¿!¡+()[]{}%@/'+'´áéíóú');">
              </div></td>
            </tr>
            <?php
			if ($li_estmodest=='2')
			   { 
			   	$ls_clasificacion='A';
				$bloqueado='none';
			  
			   }
			 else
			 {
			 
			 $bloqueado='block';
			 }?>
          
       
				 <tr class="formato-blanco">
				   <td height="13">&nbsp;</td>
				   <td height="13" colspan="2" align="left">&nbsp;</td>
		    </tr>
				 <tr class="formato-blanco">
				   <td height="22"><div align="right">
				     <input name="chkintercom" type="checkbox" id="chkintercom" value="1" <?php print $ls_chkintercom ?> onClick="javascript:ue_cambio();">
			       </div></td>
				   <td height="22" colspan="2" align="left">Intercompa&ntilde;ia</td>
		    </tr>
				 <tr class="formato-blanco">
				   <td height="22"><div align="right">Cuenta Contable </div></td>
				   <td width="176" height="22" align="left"><input name="txtcuenta" id="txtcuenta" value="<?php print $ls_cuenta ?>" type="text" size="25" maxlength="25" readonly  style="text-align:center ">
                     <a href="javascript:catalogo_cuentas();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"> </a></td>
		           <td width="293" height="22" align="left">
		             <input name="txtdencuenta" type="text" class="sin-borde" id="txtdencuenta" value="<?php print $ls_denocuenta ?>" size="50" maxlength="254">
		           </a></td>
		    </tr>
			 	 <tr class="formato-blanco">
				  <td height="22"><div align="right"></div></td>
				  <td height="22" colspan="2" align="left"><div style="display:<?PHP print $bloqueado;?>"><p>
					  <?php
					if($ls_clasificacion=='P')
					{
						$rb_pro="checked";
						$rb_accion="";
					}
					else
					{
						$rb_pro="";
						$rb_accion="checked";
					}
				?>
                  <label>
                  <input name="rbclasificacion" type="radio" value="P" <?php print $rb_pro;?>>
                    Proyecto</label>
                  <label>
                  <input type="radio" name="rbclasificacion" value="A"  <?php print $rb_accion;?>>
                    Acciones Centralizadas </label>
                  <br>
              </p></div></td>
            </tr>			   
             
			<tr class="formato-blanco">
              <td height="22" colspan="3">
                  <table width="200" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                    <tr>
                      <td align="center"><a href="javascript: uf_estprog2();"><?php print "Ir a ".$dat["nomestpro2"]?></a></td>
                    </tr>
                </table></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22"><div align="right" ></div></td>
              <td height="22" colspan="2"><div align="left" >
                  <input name="operacion" type="hidden" id="operacion">
              </div></td>
            </tr>
          </table>
      </div></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<div align="center"></div>
</body>
<script language="javascript">
f=document.formulario;
function ue_nuevo()
{
  li_incluir = f.incluir.value;
  if (li_incluir==1)
	 {	
		f.operacion.value ="NUEVO";
		f.action="sigesp_spg_d_estprog1.php";
		f.submit();
	 }
   else
     {
 	   alert("No tiene permiso para realizar esta operación");
	 }
}

function ue_guardar()
{
li_incluir    = f.incluir.value;
li_cambiar    = f.cambiar.value;
lb_status     = f.status.value;
ls_codestpro1 = f.txtcodestpro1.value;
ls_denestpro1 = f.txtdenestpro1.value;
ls_cuenta=f.txtcuenta.value;
ls_dencuenta=f.txtdencuenta.value;
if (((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
   {
	 if ((ls_codestpro1!="") && (ls_denestpro1!=""))
	    {
				 if (lb_status=="C")
				 { 
				    if((f.chkintercom.checked==true)&&(ls_cuenta!="")&&(ls_dencuenta!=""))
					{
					  mod=confirm("¿ Esta seguro de actualizar este registro ?");
					  if (mod==true)
					  {
						  f.operacion.value ="GUARDAR";
						  f.action="sigesp_spg_d_estprog1.php";
						  f.submit();
					  }
					  else
					  {
						 alert("Actualización Cancelada!!!");
					  }
					}
					else if((f.chkintercom.checked==false)&&(ls_cuenta=="")&&(ls_dencuenta==""))
					  { 
					      f.operacion.value ="GUARDAR";
						  f.action="sigesp_spg_d_estprog1.php";
						  f.submit();
					  }
					  else
					  { 
					    alert("Debe selccionar la cuenta contable..");
					  }
				  }
				  else
				  { 
				     if((f.chkintercom.checked==true)&&(ls_cuenta!="")&&(ls_dencuenta!=""))
					  {
				  		  f.operacion.value ="GUARDAR";
						  f.action="sigesp_spg_d_estprog1.php";
						  f.submit();
				      }
					  else if((f.chkintercom.checked==false)&&(ls_cuenta=="")&&(ls_dencuenta==""))
					  { 
					      f.operacion.value ="GUARDAR";
						  f.action="sigesp_spg_d_estprog1.php";
						  f.submit();
					  }
					  else
					  { 
					    alert("Debe selccionar la cuenta contable..");
					  }
				  }
		}
     else
	    {
		  alert("Debe completar todos los campos !!!");
		}
  }
 else
  {
    alert("No tiene permiso para realizar esta operación");
  }  
}

function ue_eliminar()
{
f           = document.formulario;
li_eliminar = f.eliminar.value;
if (li_eliminar==1)
   {	
     borrar=confirm("¿ Esta seguro de eliminar este registro ?");
	 if (borrar==true)
	    { 
          f.operacion.value ="ELIMINAR";
          f.action="sigesp_spg_d_estprog1.php";
          f.submit();
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
    f=document.formulario;
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
	     window.open("sigesp_spg_cat_estpro1.php?destino=inter","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
       }
	 else
	   {
 	     alert("No tiene permiso para realizar esta operación");
	   }
}
 
function ue_cerrar()
{
	f=document.formulario;
	f.action="sigespwindow_blank.php";
	f.submit();
}

function catalogo_cuentas()
{
	f=document.formulario;
	f.operacion.value="";
	 if (f.chkintercom.checked==true)
     {
	   pagina="sigesp_catdinamic_ctas.php?cuenta=cuenta";
	   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=500,height=500,resizable=yes,location=no");
	 }
  else
     {
	   alert("Debe tildar el intercompañía para seleccionar la cuenta contable..");
	 }			
	
} 

function uf_estprog2()
{
	f             = document.formulario;
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	if(f.rbclasificacion[0].checked)
	{
		ls_clasificacion='P';  
		
	}
	else
	{
		ls_clasificacion='A';
	}
	if ((ls_codestpro1!="")&&(ls_denestpro1!=""))
	   {
		 f.action="sigesp_spg_d_estprog2.php?ls_clasificacion="+ls_clasificacion+"";
		 f.submit();
 	   }
	else
	   {
		 alert("Debe seleccionar algun valor para continuar");
	   }
}

function ue_cambio()
{
   f           = document.formulario;
   f.txtcuenta.value="";
   f.txtdencuenta.value="";
}
//Funciones de validacion de fecha.
	function rellenar_cad(cadena,longitud,campo)
	{
		var mystring=new String(cadena);
		cadena_ceros="";
		lencad=mystring.length;
	
		total=longitud-lencad;
		if (cadena!="")
		{
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
		if(campo=="doc")
		{
			document.formulario.txtdocumento.value=cadena;
		}
		if(campo=="cmp")
		{
			document.formulario.txtcomprobante.value=cadena;
		}
		if(campo=="cod")
		{
			document.formulario.txtcodestpro1.value=cadena;
		}
	    }
	}

</script>
</html>