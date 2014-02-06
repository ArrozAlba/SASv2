<?php
session_start();
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
<title>Definición de Plan de Cuentas.</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
</head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="2" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="121" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td width="657" class="toolbar"></td>
  </tr>
</table>
<?php
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_fecha.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_sigesp_int.php");
	require_once("../../shared/class_folder/class_sigesp_int_scg.php");
	require_once("../../shared/class_folder/sigesp_c_check_relaciones.php");
	$io_conect  = new sigesp_include();
    $con        = $io_conect->uf_conectar ();
	$io_sql     = new class_sql($con);
	$int_scg    = new class_sigesp_int_scg();
	$ds         = null;
	$io_msg     = new class_mensajes();
	$io_funcion = new class_funciones();
	$io_chkrel  = new sigesp_c_check_relaciones($con);

//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre       = $_SESSION["la_empresa"];
	$ls_empresa = $arre["codemp"];
	$ls_codemp  = $ls_empresa;
	if (array_key_exists("la_logusr",$_SESSION))
	   {
		 $ls_logusr=$_SESSION["la_logusr"];
 	   }
	else
	   {
		 $ls_logusr="";
 	   }
	$ls_sistema     = "CFG";
	$ls_ventanas    = "sigesp_scg_d_plan_ctas.php";
	$la_security[1] = $ls_empresa;
	$la_security[2] = $ls_sistema;
	$la_security[3] = $ls_logusr;
	$la_security[4] = $ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos            = $_POST["permisos"];
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

$ls_format_scg = $arre["formcont"];
$ls_format_scg = str_replace("-","",$ls_format_scg);
$li_size_form  = strlen(trim($ls_format_scg));
$ls_formplan   = $arre["formplan"];
$disabled      = "";

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

	function uf_valida_cuenta($as_cuenta,$as_denominacion )
	{
		$li_nivel         = 0;
		$lb_valido        = true;
		$ls_pad_cuenta    = "";
		$ls_status        = "";
		$ls_NextCuenta    = "";
		$ls_mensaje_error = "";
		$int_scg          = new class_sigesp_int_scg();
		$io_msg           = new class_mensajes();
		$arre              = $_SESSION["la_empresa"];
		$ls_formplan      = $arre["formplan"];
		$is_codemp        = $arre["codemp"];
		$ls_pad_cuenta    = $int_scg->uf_pad_cuenta_plan($ls_formplan ,$as_cuenta); 
		if (!$int_scg->uf_select_plan_unico_cuenta($ls_pad_cuenta,&$as_denominacion))
		   {
			$int_scg->uf_init_niveles();
			$li_nivel = $int_scg->uf_scg_obtener_nivel($as_cuenta);
				
			if($li_nivel<=1)
			{
			   $io_msg->message("Las cuentas de nivel 1 no son validas.");				
			   return false;
			}
			// verifico si no hay cuentas con movimientos de nivel superior
			if($li_nivel > 1)
			{
			   $ls_NextCuenta = $int_scg->uf_scg_next_cuenta_nivel($as_cuenta);
				
				do
				{
				  
				  if($int_scg->uf_scg_select_cuenta($is_codemp,$ls_NextCuenta,$ls_status,$as_denominacion))
				  {
						if ($ls_status == "C")
						{
							$io_msg->message("Existen cuentas de nivel superior con Movimiento.");			      
							return false;				
						}
				  }
			   $ls_NextCuenta = $int_scg->uf_scg_next_cuenta_nivel($ls_NextCuenta);
			   $li_nivel=$int_scg->uf_scg_obtener_nivel($ls_NextCuenta);
			   
			   }while( $li_nivel > 1);
			}
						
		}
		
		return $lb_valido;
	
	}//uf_valida_cuenta
	

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_status_ope=$_POST["status"];
	}
	else
	{
		$ls_operacion="";
		$ls_status_ope="N";
	}
		//print $ls_operacion;
	
	if($ls_operacion=="GUARDAR")
	{
		$ls_cuenta_valid=$_POST["txtcuenta"];
		$ls_denominacion_cta=$_POST["txtdenominacion"];
		
		$io_msg=new class_mensajes();
		$io_funcion=new class_funciones();
		$arre=$_SESSION["la_empresa"];
		$ls_formplan=$arre["formplan"];
		$is_codemp=$arre["codemp"];		
		$lb_valido=true;	
			if( ($ls_cuenta_valid=="")||($ls_denominacion_cta==""))
			{
				$io_msg->message("Debe completar todos los campos") ;
				$ls_cuenta=$ls_cuenta_valid;
			    $ls_denominacion=$ls_denominacion_cta;
			    $ls_status="";
			    $disabled="";
			}
			else
			{
			
				$li_len=strlen($ls_cuenta_valid);
				$io_funciones=new class_funciones();
				
				$ls_cuenta_valid  = $int_scg->uf_pad_scg_cuenta($arre["formcont"],$ls_cuenta_valid);
				
				if(uf_valida_cuenta($ls_cuenta_valid,$ls_denominacion_cta))
				{
					//Tomo los valores actuales de las cajas de texto.
					$ls_cuenta=$_POST["txtcuenta"];
					$ls_denominacion=$_POST["txtdenominacion"];
					$ls_status=$_POST["txtstatus"];
					if($li_len!=$li_size_form)
					{
						$ls_cuenta=$io_funciones->uf_cerosderecha($ls_cuenta,$li_size_form);
					}
					//Tomo los valores anteriores de la cuenta y denominacion.
					if($int_scg->uf_scg_select_cuenta($is_codemp,$ls_cuenta,$ls_status,$ls_denominacion))
					{
							if($ls_status_ope=='C')
							{
								$lb_valido=$int_scg->uf_scg_update_cuenta($is_codemp,$ls_cuenta,$ls_denominacion_cta,"Error al actualizar");
								$ls_evento="UPDATE";
								$ls_descripcion="Actualizo la cuenta de plan de cuentas contables $ls_cuenta, con denominacion $ls_denominacion";
								$io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas,$ls_descripcion);
								$ls_cuenta="";
								$ls_denominacion="";
								$ls_status="";
								$io_msg->message("Denominación Actualizada");
								$disabled="";
							}
							else
							{
								$io_msg->message("Codigo Contable ya existe, introduzca uno nuevo");								
								$disabled="";
							}
					}
					else
					{
						$ls_cuenta_tempo = $ls_cuenta_valid;
						$ls_denominacion = "";
						$int_scg->uf_init_niveles();
						$ls_NextCuenta = $int_scg->uf_scg_next_cuenta_nivel($ls_cuenta_valid);
								
						$li_Nivel      = $int_scg->uf_scg_obtener_nivel($ls_NextCuenta);
																			
						$li_fila = 1	; 	 
						 $lds_cuenta_temp=new class_datastore();		
						
						do 
						{
			
						  if(!$int_scg->uf_scg_select_cuenta($is_codemp,$ls_NextCuenta,&$as_status,&$as_denominacion))
						  {
							 
							  $ls_status=$as_status;								
																		  
							  $ls_PadNextCuenta =$int_scg->uf_pad_cuenta_plan($arre["formplan"] , $ls_NextCuenta)	;				
							 
							  $lb_existe=$int_scg->uf_select_plan_unico_cuenta($ls_PadNextCuenta,&$as_denominacion_plan);
							  if(!$lb_existe)
							  {
							  	$as_denominacion_plan=$ls_denominacion_cta;
							  }
												
								  if($li_Nivel > 1)
								  {
									  $ls_cuenta_ref = $int_scg->uf_scg_next_cuenta_nivel( $ls_NextCuenta );
									  
								  }
								  else	
								  {
									  $ls_cuenta_ref = "             ";
								  }
								 
								  $lds_cuenta_temp->insertRow("SC_cuenta",$ls_NextCuenta);				  			  
								  $lds_cuenta_temp->insertRow("denominacion",$as_denominacion_plan);				  			  
								  $lds_cuenta_temp->insertRow("sc_cuenta_ref",$ls_cuenta_ref);				  			  
								  $lds_cuenta_temp->insertRow("Nivel",$li_Nivel);				  			  
								  $li_fila =  $li_fila + 1;
								  
							  } 
				
							if ($li_Nivel > 1)
							{
									$ls_NextCuenta = $int_scg->uf_scg_next_cuenta_nivel( $ls_NextCuenta );
									$li_Nivel      = $int_scg->uf_scg_obtener_nivel( $ls_NextCuenta );
									
							}
							else
							{
								$li_Nivel = 0 ;
							}
							
						}while( $li_Nivel >= 1);
						
						$li_total = $lds_cuenta_temp->getRowCount("SC_cuenta");
			
						 if($li_total>0)
						 {
							for($li_fila=1;$li_fila<=$li_total;$li_fila++)
							{
								 $ls_cuenta      = $lds_cuenta_temp->getValue("SC_cuenta",$li_fila); 	    
								 $ls_denominacion= $lds_cuenta_temp->getValue("denominacion",$li_fila) ;	    		 
								 $ls_cuenta_ref  = $lds_cuenta_temp->getValue("sc_cuenta_ref",$li_fila) ;	    		 
								 $li_Nivel       = $lds_cuenta_temp->getValue("Nivel",$li_fila); 	    		 
								 $ls_mensaje_error="Error en Guardar";
								 $ls_status = "S";
								 $int_scg->io_sql->begin_transaction();
								 $lb_valido = $int_scg->uf_scg_insert_cuenta($is_codemp,$ls_cuenta,$ls_denominacion,$ls_status,$li_Nivel,$ls_cuenta_ref,$ls_mensaje_error);
								
								 
								 if (!$lb_valido)
								 {
									break; 
								 }	
							}
						 }
							
						 if($lb_valido)
						 {							
								$ls_cuenta = $ls_cuenta_tempo;
								$ls_Cuenta_temp = $int_scg->uf_pad_cuenta_plan( $arre["formplan"] , $ls_cuenta);
								$int_scg->uf_select_plan_unico_cuenta($ls_Cuenta_temp,&$as_denominacion_plan);
								$ls_NextCuenta = $int_scg->uf_scg_next_cuenta_nivel($ls_cuenta);
								$li_Nivel      = $int_scg->uf_scg_obtener_nivel($ls_cuenta);
								$lb_valido     = $int_scg->uf_scg_insert_cuenta($is_codemp,$ls_cuenta,$ls_denominacion_cta,"C",$li_Nivel,$ls_NextCuenta,&$ls_mensaje_error);		
						 }
						
							 if ($lb_valido)
							 {
								if(!$int_scg->uf_sql_transaction( $lb_valido ))
								{
								   $io_msg->message("Error al grabar en la base de datos.");
								}
								else
								{
								    $io_msg->message("La cuenta fue grabada.");					   
								    $ls_evento="INSERT";
								    $ls_descripcion="Inserto la cuenta de plan de cuentas contables $ls_cuenta, con denominacion $ls_denominacion";
									$io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas,$ls_descripcion);
								    $ls_cuenta="";
								    $ls_denominacion="";
								    $ls_status="";
								    $disabled="";
								}
							 }
							 else
							 {
								   $io_msg->message($int_scg->is_msg_error);
							 }
					}
				}
			}
}
elseif("NUEVO")
{
	$ls_cuenta="";
	$ls_denominacion="";
	$ls_status="";
	$ls_status_ope="N";
	$disabled="";
}

if ($ls_operacion=="ELIMINAR")
   {
     global $int_scg;
  	 $ls_cuenta        = $_POST["txtcuenta"];
  	 $ls_denominacion  = $_POST["txtdenominacion"];
	 $ls_mensaje_error = "";
	 $ls_cuenta_cero   = "";
	 $ls_status        = "";
	 $ls_denominacion  = "";
 	 $lb_valido        = false ;
	 $li_total_rows    = 0;
	 $ls_condicion     = " AND (column_name='sc_cuenta' OR column_name='scg_cuenta')";//Nombre del o los campos que deseamos buscar.
	 $ls_mensaje       = "";  //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
	 $lb_valido= $int_scg->uf_delete_relacion_sin_movimiento($ls_codemp,$ls_cuenta);
	 $lb_tiene         = $io_chkrel->uf_check_relaciones($ls_codemp,$ls_condicion,'scg_cuentas',$ls_cuenta,$ls_mensaje);//Verifica los movimientos asociados a la cuenta
	 if ($int_scg->ib_db_error )
	 {
	   $io_msg->message($ls_mensaje_error)	;
	   $int_scg->ib_db_error = false;
	}
	if ($lb_tiene)
	{
		$io_msg->message($io_chkrel->is_msg_error);
	}
	else
	{
		$ls_cuenta_cero = $int_scg->uf_scg_sin_ceros($ls_cuenta) ;
		$li_total_rows = $int_scg->uf_scg_select_cuenta_sin_cero($ls_codemp,$ls_cuenta_cero);
		if($li_total_rows > 1)
		{
		   $io_msg->message("Existen cuentas de nivel inferior ... no se puede eliminar.");				
		}
		else 
		{
			$lb_valido = $int_scg->uf_scg_delete_cuenta($ls_codemp,$ls_cuenta);   
			if($lb_valido)
			{
				$io_msg->message("Cuenta eliminada Satisfactoriamente");
				$ls_evento="DELETE";
				$ls_descripcion="Elimino la cuenta de plan de cuentas contables $ls_cuenta, con denominacion $ls_denominacion";
				$io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas,$ls_descripcion);
			}
			else
			{
				$io_msg->message($int_scg->is_msg_error);
			}
		}
	}	
$ls_cuenta="";
$ls_denominacion="";
$ls_status="";
$disabled="";
}
?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div align="center">
<form name="form1" method="post" action="">
  <?php 
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular value='$la_accesos[anular]'>");
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
  <table width="658" height="184" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="182">
          <p>&nbsp;</p>
          <table width="614" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="2" class="titulo-ventana">Definici&oacute;n Plan de Cuentas </td>
              </tr>
              <tr class="formato-blanco">
                <td height="20">&nbsp;</td>
                <td height="20">&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td width="99" height="22" style="text-align:right">C&oacute;digo Contable</td>
                <td width="513" height="22"><input name="txtcuenta" <?php print $disabled ?> type="text" id="txtcuenta" value="<?php print $ls_cuenta ?>" maxlength="<?php print $li_size_form;?>" onKeyPress="return keyRestrict(event,'1234567890');"  onBlur="rellenar_cad(this.value,<?php print $li_size_form;?>)" style="text-align:center">                    <a href="javascript: cat();"><img src="../../shared/imagebank/tools15/buscar.gif" alt="Catalogo de Cuentas del Plan Unico" width="15" height="15" border="0"></a>
                    <label style="color:#003399 "><strong><?php print "Formato: ".$arre["formcont"]?></strong></label>
                    <input name="status" type="hidden" id="status" value="<?php print $ls_status_ope;?>">
              </td>
              </tr>
              <tr class="formato-blanco">
                <td height="22" style="text-align:right">Denominaci&oacute;n</td>
                <td height="22"><input name="txtdenominacion" type="text" id="txtdenominacion" value="<?php print $ls_denominacion?>" size="95" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyzñ '+'.,-()*/%$!');">
                  <input name="txtstatus" type="hidden" id="txtstatus" value="<?php print $ls_status ?>">
                </td>
              </tr>
            <tr class="formato-blanco">
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
            </tr>
          </table>
            <p align="center">
            <input name="operacion" type="hidden" id="operacion">
</p>        </td>
      </tr>
  </table>
  </form>
</div>
</body>
<script language="javascript">
function cat()
{
	f=document.form1;
	f.txtcuenta.readonly=false;
	f.operacion.value="CAT";
	window.open("sigesp_cat_ctaspu.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_nuevo()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
       f.operacion.value ="NUEVO";
       f.action="sigesp_scg_d_plan_ctas.php";
	   f.submit();
	 }
   else
     {
 	   alert("No tiene permiso para realizar esta operación");
	 }
}

function ue_guardar()
{
f=document.form1;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status=f.status.value;
  if (((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	 {
	   f.operacion.value ="GUARDAR";
	   f.action="sigesp_scg_d_plan_ctas.php";
	   f.submit();
	   f.txtcuenta.focus(true);
	 }
   else
     {
 	   alert("No tiene permiso para realizar esta operación");
	 }
}

function ue_eliminar()
{
f=document.form1;
li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
     borrar=confirm("¿ Esta seguro de eliminar este registro ?");
	 if (borrar==true)
	    { 
	      f.operacion.value ="ELIMINAR";
          f.action="sigesp_scg_d_plan_ctas.php";
          f.submit();
		}
     else
	    {
	      alert("Eliminación Cancelada !!!");			
 		}
    }
 else
    {
      alert("No tiene permiso para realizar esta operación !!!");
    }
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
	     window.open("sigesp_scg_cat_ctas.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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

function rellenar_cad(cadena,longitud)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;

	total=longitud-lencad;
	for(i=1;i<=total;i++)
	{
		cadena_ceros=cadena_ceros+"0";
	}
	cadena=cadena+cadena_ceros;	
	document.form1.txtcuenta.value=cadena;	
}
</script>
</html>