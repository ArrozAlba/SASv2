<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
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
<title>Definición de Plan de Cuentas.</title>
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
.Estilo5 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.Estilo6 {
	color: #006699;
	font-size: 12px;
}
.Estilo8 {font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; }
.Estilo10 {font-size: 10px}
.Estilo11 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo13 {font-size: 12px}
.Estilo14 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
-->
</style>
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
</head>

<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
   <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Contabilidad Patrimonial</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Ejecutar" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/deshacer.gif" alt="Deshacer" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/filtrar.gif" alt="Filtrar" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<?php
	//include("index.php");
	require_once("../shared/class_folder/class_sigesp_int_scg.php");
	/*require_once("class_sql.php");
	/*$SQL=new class_sql();*/
	$dat=$_SESSION["la_empresa"];
	$ls_format_scg=$dat["formcont"];
	$ls_format_scg=str_replace("-","",$ls_format_scg);
	$li_size_form=strlen($ls_format_scg);
	$int_scg=new class_sigesp_int_scg();
	$ds=null;

	function uf_valida_cuenta($as_cuenta )
	{
		$li_nivel =0;
		$lb_valido=true;
		$ls_pad_cuenta="";$ls_denominacion="";$ls_status="";$ls_NextCuenta="";$ls_mensaje_error="";
		$int_scg=new class_sigesp_int_scg();
		$msg=new class_mensajes();
		$dat=$_SESSION["la_empresa"];
		$ls_formplan=$dat["formplan"];
		$is_codemp=$dat["CodEmp"];
		
		$ls_pad_cuenta = $int_scg->uf_pad_cuenta_plan($ls_formplan ,$as_cuenta); 

		if(!$int_scg->uf_select_plan_unico_cuenta($ls_pad_cuenta,$ls_denominacion))
		{
			$msg->message("La cuenta no existe en el Plan Unico de Cuentas.");		
			return false;
		}
		
		/*if($int_scg->uf_scg_select_cuenta($is_codemp,$as_cuenta,$ls_status,$ls_denominacion))      
		{
			$msg->message("La cuenta ya existe en el Plan de Cuentas.");	
			return false;	
		}*/
		
		// verifico si el nivel es el apropiado
		//$msg->message($as_cuenta);	
		$int_scg->uf_init_niveles();
		$li_nivel = $int_scg->uf_scg_obtener_nivel($as_cuenta);
			
		if($li_nivel<=1)
		{
		   $msg->message("Las cuentas de nivel 1 no son validas.");				
		   return false;
		}
		// verifico si no hay cuentas con movimientos de nivel superior
		if($li_nivel > 1)
		{
		   $ls_NextCuenta = $int_scg->uf_scg_next_cuenta_nivel($as_cuenta);
			
			do
			{
			  
			  if($int_scg->uf_scg_select_cuenta($is_codemp,$ls_NextCuenta,$ls_status,$ls_denominacion))
			  {
					if ($ls_status == "C")
					{
						$msg_message("Existen cuentas de nivel superior con Movimiento.");			      
						return false;				
					}
			  }
		   $ls_NextCuenta = $int_scg->uf_scg_next_cuenta_nivel($ls_NextCuenta);
		   $li_nivel=$int_scg->uf_scg_obtener_nivel($ls_NextCuenta);
		   
		   }while( $li_nivel > 1);
		}
		
		return $lb_valido;
	
	}//uf_valida_cuenta
	

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
	}
		//print $ls_operacion;
	if($ls_operacion=="CAT")
	{
		$ls_cuenta=$_POST["txtcuenta"];
		$_SESSION["ls_cuenta"]=$ls_cuenta;
		$ls_denominacion=$_POST["txtdenominacion"];
		$_SESSION["ls_denominacion"]=$ls_denominacion;
		$ls_status=$_POST["txtstatus"];
		$_SESSION["ls_status"]=$ls_status;
		$disabled="";
	}
	if($ls_operacion=="EDITAR")
	{
		$ls_cuenta=$_POST["txtcuenta"];
		$_SESSION["ls_cuenta"]=$ls_cuenta;
		$ls_denominacion=$_POST["txtdenominacion"];
		$_SESSION["ls_denominacion"]=$ls_denominacion;
		$ls_status=$_POST["txtstatus"];
		$_SESSION["ls_status"]=$ls_status;
		$disabled="readonly";
	}
	elseif($ls_operacion=="CAMBIAR")
	{
		$msg=new class_mensajes();
		//Tomo los valores actuales de las cajas de texto.
		$ls_cuenta2=$_POST["txtcuenta"];
		$ls_denominacion2=$_POST["txtdenominacion"];
		$ls_status=$_POST["txtstatus"];
		//Tomo los valores anteriores de la cuenta y denominacion.
		$ls_cuenta=$_SESSION["ls_cuenta"];
		$ls_denominacion=$_SESSION["ls_denominacion"];

		
		if($ls_status=="C")
		{		
			$lb_valido=$int_scg->uf_scg_update_cuenta($dat["codemp"],$ls_cuenta,$ls_denominacion2,"Error al actualizar");
			//print $lb_valido;
			$ls_cuenta="";
			$ls_denominacion="";
			$ls_status="";
			$msg->message("Denominación Actualizada");
			$disabled="";
		}
		elseif($ls_status=="S")
		{
			$msg->message("La cuenta no es de movimiento, no puede actualizar");
			$ls_cuenta="";
			$ls_denominacion="";
			$ls_status="";
			$disabled="";
		}
	}
	elseif($ls_operacion=="GUARDAR")
	{
		$ls_cuenta_valid=$_POST["txtcuenta"];
		$ls_denominacion_cta=$_POST["txtdenominacion"];
		
		$msg=new class_mensajes();
		$fun=new class_funciones();
		//$ls_cuenta_valid  = trim(str_replace("-"," ",$ls_cuenta_valid));
		$dat=$_SESSION["la_empresa"];
		$ls_formplan=$dat["formplan"];
		$is_codemp=$dat["codemp"];		
		$lb_valido=true;	
			if( ($ls_cuenta_valid=="")||($ls_denominacion_cta==""))
			{
				$msg->message("Debe compeltar todos los campos") ;
			}
			else
			{
			
				$ls_cuenta_valid  = $int_scg->uf_pad_scg_cuenta($dat["formcont"],$ls_cuenta_valid);
				
				if(uf_valida_cuenta($ls_cuenta_valid))
				{
	
					//$ls_cuenta="";
					//$ls_denominacion="";
					//Tomo los valores actuales de las cajas de texto.
					$ls_cuenta=$_POST["txtcuenta"];
					$ls_denominacion=$_POST["txtdenominacion"];
					$ls_status=$_POST["txtstatus"];
					//Tomo los valores anteriores de la cuenta y denominacion.
					if($int_scg->uf_scg_select_cuenta($is_codemp,$ls_cuenta,$ls_status,$ls_denominacion))
					{
							$lb_valido=$int_scg->uf_scg_update_cuenta($is_codemp,$ls_cuenta,$ls_denominacion,"Error al actualizar");
							$lb_valido=$int_scg->uf_sql_transaction($lb_valido);
							$ls_cuenta="";
							$ls_denominacion="";
							$ls_status="";
							$msg->message("Denominación Actualizada");
							$disabled="";
					}
					else
					{
						$ls_cuenta_tempo = $ls_cuenta_valid;
						$ls_denominacion = "";
			
						$ls_NextCuenta = $int_scg->uf_scg_next_cuenta_nivel($ls_cuenta_valid);
								
						$li_Nivel      = $int_scg->uf_scg_obtener_nivel($ls_NextCuenta);
																			
						$li_fila = 1	; 	 
						 $lds_cuenta_temp=new class_datastore();		
						
						do 
						{
			
						  if(!$int_scg->uf_scg_select_cuenta($is_codemp,$ls_NextCuenta,&$as_status,&$as_denominacion))
						  {
							 
							  $ls_status=$as_status;
								
							  $ls_denominacion=$as_denominacion;
											  
							  $ls_PadNextCuenta =$int_scg->uf_pad_cuenta_plan($dat["formplan"] , $ls_NextCuenta)	;				
							 
							  $int_scg->uf_select_plan_unico_cuenta($ls_PadNextCuenta,&$as_denominacion_plan);
							  
												
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
								 $int_scg->SQL->begin_transaction();
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
							
								$ls_Cuenta_temp = $int_scg->uf_pad_cuenta_plan( $dat["formplan"] , $ls_cuenta);						 
							
								if($int_scg->uf_select_plan_unico_cuenta($ls_Cuenta_temp,&$as_denominacion_plan))
								{
								   $ls_NextCuenta = $int_scg->uf_scg_next_cuenta_nivel($ls_cuenta);
								   $li_Nivel      = $int_scg->uf_scg_obtener_nivel($ls_cuenta);
								   $lb_valido     = $int_scg->uf_scg_insert_cuenta($is_codemp,$ls_cuenta,$ls_denominacion_cta,"C",$li_Nivel,$ls_NextCuenta,&$ls_mensaje_error);
								}
						 }
						
							 if ($lb_valido)
							 {
								if(!$int_scg->uf_sql_transaction( $lb_valido ))
								{
								   $msg->message("Error al grabar en la base de datos.");
								}
								else
								{
								   $msg->message("La cuenta fue grabada.");
								}
							 }
							 else
							 {
								   $msg->message($int_scg->is_msg_error);
							 }
					}
				}
			}
$ls_cuenta="";
$ls_denominacion="";
$ls_status="";
$disabled="";
		//return true;	
}
elseif($ls_operacion=="ELIMINAR")
{
		global $int_scg;
		$ls_cuenta       = $_POST["txtcuenta"];
		$ls_denominacion = $_POST["txtdenominacion"];
		$msg=new class_mensajes();
		$fun=new class_funciones();
		//$ls_cuenta_valid  = trim(str_replace("-"," ",$ls_cuenta_valid));
		$dat             = $_SESSION["la_empresa"];
		$ls_formplan     = $dat["formplan"];
		$is_codemp       = $dat["codemp"];		
		$ls_mensaje_error="";$ls_cuenta_cero="";$ls_status="";$ls_denominacion="";
		$lb_valido=false ;
		$li_total_rows=0;

		$lb_valido = $int_scg->uf_scg_select_cuenta_movimiento( $is_codemp,$ls_cuenta,&$ls_mensaje_error) ;

	if ($int_scg->ib_db_error )
	{
	   $msg->message($ls_mensaje_error)	;
	   $int_scg->ib_db_error = false;
		//return false;
	}

	if ($lb_valido)
	{
		$msg->message("Existen movimientos asociados a esta cuenta ... no se puede eliminar.");
	 //  return false	;
	}
	else
	{
		$ls_cuenta_cero = $int_scg->uf_scg_sin_ceros($ls_cuenta) ;
		$li_total_rows = $int_scg->uf_scg_select_cuenta_sin_cero($is_codemp,$ls_cuenta_cero);
		
		if($li_total_rows > 1)
		{
		   $msg->message("Existen cuentas de nivel inferior ... no se puede eliminar.");				
			//return false;
		}
		else 
		{
			
			$lb_valido = $int_scg->uf_scg_delete_cuenta( $is_codemp,$ls_cuenta);   
			$msg->message($int_scg->is_msg_error);
			if($int_scg->ib_db_error)
			{
			   $msg->message($int_scg->is_msg_error);	
			   $int_scg->ib_db_error = false;		
			 //  return false;
			}
		}
	}	
$ls_cuenta="";
$ls_denominacion="";
$ls_status="";
$disabled="";
}
else 
{
	$ls_cuenta="";
	$ls_denominacion="";
	$ls_status="";
	$disabled="";
}

?>
<p>&nbsp;</p>
<div align="center">
  <table width="601" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="601" height="221" valign="top" bgcolor="#DEDBDE" class="formato-blanco"><form name="form1" method="post" action="">
          <p>&nbsp;</p>
          <table width="566" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td colspan="2" class="titulo-nuevo">Definici&oacute;n Plan de Cuentas </td>
              </tr>
              <tr class="formato-blanco">
                <td height="18">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td width="104" height="37"><div align="right" >
                    <p>Codigo Contable</p>
                </div></td>
                <td width="460"><div align="left" >
                    <input name="txtcuenta" <?php print $disabled ?> type="text" id="txtcuenta" value="<?php print $ls_cuenta?>" maxlength="<?php print $li_size_form;?>">
                    <a href="javascript: cat();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo de Cuentas del Plan Unico" width="15" height="15" border="0"></a>
                    <label style="color:#003399 "><strong><?php print "Formato: ".$dat["formplan"]?></strong></label>
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="36"><div align="right">Denominaci&oacute;n</div></td>
                <td><div align="left">
                  <input name="txtdenominacion" type="text" id="txtdenominacion" value="<?php print $ls_denominacion?>" size="70">
                  <input name="txtstatus" type="hidden" id="txtstatus" value="<?php print $ls_status ?>">
                </div></td>
              </tr>
            <tr class="formato-blanco">
                <td height="20"><div align="right" ></div></td>
                <td><div align="left" >
                </div></td>
            </tr>
          </table>
            <p align="center">
            <input name="operacion" type="hidden" id="operacion">
</p>
        </form></td>
      </tr>
  </table>
</div>
</body>
<script language="javascript">
function cat()
{
 f=document.form1;
 f.txtcuenta.readonly=false;
 f.operacion.value="CAT";
 //f.action="sigespwindow_scg_plan_ctas.php";
 window.open("sigesp_catdinamic_ctaspu.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
// f.submit();
}

function editar(cuenta , deno , status)
{
f=document.form1;
//f.txtcuenta.readonly=false;
f.txtcuenta.value=cuenta;
f.txtdenominacion.value=deno;
f.txtstatus.value=status;
f.operacion.value ="EDITAR";
f.action="sigespwindow_scg_plan_ctas.php";
f.submit();
f.txtdenominacion.focus(true);
}

/*function cambiar(cuenta , deno , status)
{
f=document.form1;
//f.txtcuenta.disabled=false;
f.txtcuenta.value=cuenta;
f.txtdenominacion.value=deno;
f.txtstatus.value=status;
f.operacion.value ="CAMBIAR";
f.action="sigespwindow_scg_plan_ctas.php";
f.submit();
f.txtdenominacion.focus(true);
}*/
function ue_nuevo()
{
}
function ue_guardar()
{
f=document.form1;
f.operacion.value ="GUARDAR";
f.action="sigespwindow_scg_plan_ctas.php";
f.submit();
f.txtcuenta.focus(true);
}

function ue_eliminar()
{
f=document.form1;
//f.txtcuenta.disabled=false;
f.operacion.value ="ELIMINAR";
f.action="sigespwindow_scg_plan_ctas.php";
f.submit();
//f.txtcuenta.focus(true);
}
function ue_buscar()
{
	window.open("sigesp_catdinamic_ctas.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}


</script>
</html>
