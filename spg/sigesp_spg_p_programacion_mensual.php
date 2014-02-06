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
	require_once("class_funciones_gasto.php");
	$io_gasto= new class_funciones_gasto();
	$ls_origen=$io_gasto->uf_obtenervalor_get("origen","");
	if($ls_origen=="")
	{
		$ls_origen=$io_gasto->uf_obtenervalor("txtorigen","");;
	}
	$io_gasto->uf_load_seguridad("SPG",$ls_origen,$ls_permisos,$la_seguridad,$la_permisos);
	$ls_estmodprog=	$_SESSION["la_empresa"]["estmodprog"];
	if($ls_estmodprog!="1")
	{
		print "<script language=JavaScript>";
		print "alert('Esta opcion esta disponible solo cuando se valida contra el programado.');";
		print "close();";
		print "</script>";		
	}	
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<!--<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
--><title>Programacion Mensual </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="javascript1.2" src="../shared/js/valida_tecla.js"></script>
<style type="text/css">
<!--
.Estilo1 {font-size: 15px}
-->
</style>
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../sno/css/nomina.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
	require_once("sigesp_spg_c_mod_presupuestarias.php");
	$io_spg= new sigesp_spg_c_mod_presupuestarias();
	require_once("../shared/class_folder/class_funciones.php");
	$io_fun=new class_funciones();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=new class_mensajes();
	$ls_operacion=$io_gasto->uf_obteneroperacion();
	$ls_readonly="";
	switch($ls_operacion)
	{
		case "NUEVO":
			$ls_fecha=$io_gasto->uf_obtenervalor_get("fecha",date("d/m/Y"));
			$ls_comprobante=$io_gasto->uf_obtenervalor_get("comprobante","");
			$ls_cuentaspg=$io_gasto->uf_obtenervalor_get("cuentaspg","");
			$ls_dencta=$io_gasto->uf_obtenervalor_get("dencta","");
			$ls_codestpro=$io_gasto->uf_obtenervalor_get("codestpro","");
			$ls_estcla=$io_gasto->uf_obtenervalor_get("estcla","");
			$ls_procede=$io_gasto->uf_obtenervalor_get("procede","");
			$ls_operacion=$io_gasto->uf_obtenervalor_get("operacion","");
			$li_monto=$io_gasto->uf_obtenervalor_get("monto","");
			$li_mondis=$li_monto;
			$ls_mes=substr($ls_fecha,3,2);
			$li_enero="0,00";
			$li_febrero="0,00";
			$li_marzo="0,00";
			$li_abril="0,00";
			$li_mayo="0,00";
			$li_junio="0,00";
			$li_julio="0,00";
			$li_agosto="0,00";
			$li_septiembre="0,00";
			$li_octubre="0,00";
			$li_noviembre="0,00";
			$li_diciembre="0,00";
			
			$io_gasto->uf_formatoprogramatica($ls_codestpro,&$ls_programatica);
			$ls_codestpro1=substr($ls_codestpro,0,25);
			$ls_codestpro2=substr($ls_codestpro,25,25);
			$ls_codestpro3=substr($ls_codestpro,50,25);
			$ls_codestpro4=substr($ls_codestpro,75,25);
			$ls_codestpro5=substr($ls_codestpro,100,25);
			$ld_fecha=$io_fun->uf_convertirdatetobd($ls_fecha);
			$rs_data=$io_spg->uf_select_dtmp_mensual($ls_procede,$ls_comprobante,$ld_fecha,$ls_codestpro1,$ls_codestpro2,
															 $ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_cuentaspg,
															 $ls_operacion);
			if((!$rs_data->EOF))
			{
				$li_enero=number_format($rs_data->fields["enero"],2,',','.');
				$li_febrero=number_format($rs_data->fields["febrero"],2,',','.');
				$li_marzo=number_format($rs_data->fields["marzo"],2,',','.');
				$li_abril=number_format($rs_data->fields["abril"],2,',','.');
				$li_mayo=number_format($rs_data->fields["mayo"],2,',','.');
				$li_junio=number_format($rs_data->fields["junio"],2,',','.');
				$li_julio=number_format($rs_data->fields["julio"],2,',','.');
				$li_agosto=number_format($rs_data->fields["agosto"],2,',','.');
				$li_septiembre=number_format($rs_data->fields["septiembre"],2,',','.');
				$li_octubre=number_format($rs_data->fields["octubre"],2,',','.');
				$li_noviembre=number_format($rs_data->fields["noviembre"],2,',','.');
				$li_diciembre=number_format($rs_data->fields["diciembre"],2,',','.');
				$li_mondis="0,00";
			}
			
		break;
		case "GUARDAR":
			$ls_fecha=$io_gasto->uf_obtenervalor("txtfecha",date("d/m/Y"));
			$ls_mes=substr($ls_fecha,3,2);
			$ls_comprobante=$io_gasto->uf_obtenervalor("txtcomprobante","");
			$li_mondis=$io_gasto->uf_obtenervalor("txtmondis","0,00");
			$ls_cuentaspg=trim($io_gasto->uf_obtenervalor("txtcuentaspg",""));
			$ls_dencta=rtrim(ltrim($io_gasto->uf_obtenervalor("txtdenominacion","")));
			$ls_codestpro=$io_gasto->uf_obtenervalor("txtcodestpro","");
			$ls_estcla=$io_gasto->uf_obtenervalor("txtestcla","");
			$ls_procede=$io_gasto->uf_obtenervalor("txtprocede","");
			$ls_operacion=$io_gasto->uf_obtenervalor("txtoperacion","");
			$li_monto=$io_gasto->uf_obtenervalor("txtmonto","");
			$li_enero=$io_gasto->uf_obtenervalor("txtenero",0);
			$li_febrero=$io_gasto->uf_obtenervalor("txtfebrero",0);
			$li_marzo=$io_gasto->uf_obtenervalor("txtmarzo",0);
			$li_abril=$io_gasto->uf_obtenervalor("txtabril",0);
			$li_mayo=$io_gasto->uf_obtenervalor("txtmayo",0);
			$li_junio=$io_gasto->uf_obtenervalor("txtjunio",0);
			$li_julio=$io_gasto->uf_obtenervalor("txtjulio",0);
			$li_agosto=$io_gasto->uf_obtenervalor("txtagosto",0);
			$li_septiembre=$io_gasto->uf_obtenervalor("txtseptiembre",0);
			$li_octubre=$io_gasto->uf_obtenervalor("txtoctubre",0);
			$li_noviembre=$io_gasto->uf_obtenervalor("txtnoviembre",0);
			$li_diciembre=$io_gasto->uf_obtenervalor("txtdiciembre",0);;
			$io_gasto->uf_formatoprogramatica($ls_codestpro,&$ls_programatica);
			$ld_fecha=$io_fun->uf_convertirdatetobd($ls_fecha);
			$ls_codestpro1=substr($ls_codestpro,0,25);
			$ls_codestpro2=substr($ls_codestpro,25,25);
			$ls_codestpro3=substr($ls_codestpro,50,25);
			$ls_codestpro4=substr($ls_codestpro,75,25);
			$ls_codestpro5=substr($ls_codestpro,100,25);
			$lb_valido=$io_spg->uf_select_mp_mensual($ls_procede,$ls_comprobante,$ld_fecha,&$ls_estapro);
			if($lb_valido)
			{
				if($ls_estapro=="0")
				{
					$io_spg->io_sql->begin_transaction();
					$rs_data=$io_spg->uf_select_dtmp_mensual($ls_procede,$ls_comprobante,$ld_fecha,$ls_codestpro1,$ls_codestpro2,
															 $ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_cuentaspg,
															 $ls_operacion);
					if((!$rs_data->EOF))
					{
						$lb_valido=$io_spg->uf_delete_dtmp_mensual($ls_procede,$ls_comprobante,$ld_fecha,$ls_codestpro1,
																   $ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
																   $ls_estcla,$ls_cuentaspg,$ls_operacion,$la_seguridad);
						if($lb_valido)
						{
							$lb_valido=$io_spg->uf_insert_mp_mensual($ls_procede,$ls_comprobante,$ld_fecha,$ls_codestpro1,
																	 $ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
																	 $ls_estcla,$ls_cuentaspg,$ls_operacion,$li_enero,$li_febrero,
								   									 $li_marzo,$li_abril,$li_mayo,$li_junio,$li_julio,$li_agosto,
																	 $li_septiembre,$li_octubre,$li_noviembre,$li_diciembre,$la_seguridad);
						}					
					}
					else
					{
						$lb_valido=$io_spg->uf_insert_mp_mensual($ls_procede,$ls_comprobante,$ld_fecha,$ls_codestpro1,
																 $ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
																 $ls_estcla,$ls_cuentaspg,$ls_operacion,$li_enero,$li_febrero,
																 $li_marzo,$li_abril,$li_mayo,$li_junio,$li_julio,$li_agosto,
																 $li_septiembre,$li_octubre,$li_noviembre,$li_diciembre,$la_seguridad);
					}
					if($lb_valido)
					{
						$io_spg->io_sql->commit();
						$io_msg->message("La distribucion mensual se ha registrado satisfactoriamente");	
					}
					else
					{
						$io_spg->io_sql->rollback();
						$io_msg->message("La ha ocurrido un error al realizar la distribucion mensual.");	
					}

				}
				else
				{
					$io_msg->message("La modificacion ha sido aprobada y no puede ser modificada");	
				}
			}
		break;
	}
?>
<form name="form1" method="post" action="">
  <p>&nbsp;</p>
  <table width="596" height="368" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td colspan="4">
	  <div align="center" class="titulo-ventana">
            Programacion Mensual </div>
		</td>
    </tr>
    <tr>
      <td colspan="2" class="fd-blanco"><input name="txtcomprobante" type="hidden" id="txtcomprobante" value="<?php print $ls_comprobante; ?>">
      <input name="txtcodestpro" type="hidden" id="txtcodestpro" value="<?php print $ls_codestpro; ?>">
      <input name="txtestcla" type="hidden" id="txtestcla" value="<?php print $ls_estcla; ?>">
      <input name="txtoperacion" type="hidden" id="txtoperacion" value="<?php print $ls_operacion; ?>">
      <input name="txtprocede" type="hidden" id="txtprocede" value="<?php print $ls_procede; ?>">
      <input name="txtorigen" type="hidden" id="txtorigen" value="<?php print $ls_origen; ?>"></td>
      <td class="fd-blanco">&nbsp;</td>
      <td width="211"><div align="right">Fecha 
          <input name="txtfecha" type="text" class="sin-borde3" id="txtfecha" value="<?php print $ls_fecha; ?>" size="10" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Cuenta</div></td>
      <td height="22"><div align="left">
        <input name="txtcuentaspg" type="text" class="sin-borde3" id="txtcuentaspg" value="    <?php print   $ls_cuentaspg; ?>" readonly>
      </div></td>
      <td height="22" colspan="2"><input name="txtcodporg" type="text" class="sin-borde3" id="txtcodporg" value="<?php print $ls_programatica; ?>" size="50" readonly></td>
    </tr>
    <tr>
      <td height="22" ><div align="right">Denominaci&oacute;n</div></td>
      <td height="22" colspan="3"><div align="left">
        <input name="txtdenominacion" type="text" class="sin-borde3" id="txtdenominacion" value="   <?php print   $ls_dencta;  ?>" size="90" maxlength="150" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22" ><div align="right">Monto Max. </div></td>
      <td height="22"><input name="txtmonto" type="text" class="sin-borde3" id="txtmonto" value="<?php print $li_monto; ?>" size="15" readonly></td>
      <td height="22" class="fd-blanco"><div align="right">Disponible
      </div></td>
      <td height="22"><input name="txtmondis" type="text" class="sin-bordeAzul" id="txtmondis" value="<?php print $li_mondis; ?>" size="20" style="text-align:right" readonly></td>
    </tr>
    <tr>
      <td class="fd-blanco">&nbsp;</td>
      <td>&nbsp;</td>
      <td class="fd-blanco">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
	<?php
		if($ls_mes<="01")
		{
	?>
      <td width="100"><div align="right">Enero</div></td>
      <td width="193">
        <div align="left">
          <input name="txtenero" type="text" id="txtenero" value="<?php print $li_enero ?>" size="25" maxlength="25" style="text-align:right"  onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $ls_readonly ?> onBlur="javascript: ue_calcular_disponible();">
</div></td>
	<?php
		}
		else
		{
	?>
      <td width="100"></td>
      <td width="211"></td>
	<?php		
		}
		if($ls_mes<="07")
		{
	?>
	  <td class="fd-blanco"><div align="right">Julio</div></td>
      <td><input name="txtjulio" type="text" id="txtjulio" value="<?php print $li_julio?>" size="25" maxlength="25" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $ls_readonly ?> onBlur="javascript: ue_calcular_disponible();"></td>
	  <?php
	  	}
	  ?>
	  </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
	<?php
		if($ls_mes<="02")
		{
	?>
      <td><div align="right">Febrero</div></td>
      <td><input name="txtfebrero" type="text" id="txtfebrero" value="<?php  print $li_febrero?>" size="25" maxlength="25" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $ls_readonly ?> onBlur="javascript: ue_calcular_disponible();"></td>
	<?php
		}
		else
		{
	?>
      <td width="100"></td>
      <td width="211"></td>
	<?php		
		}
		if($ls_mes<="08")
		{
	?>
      <td><div align="right">Agosto</div></td>
      <td><input name="txtagosto" type="text" id="txtagosto" value="<?php  print $li_agosto?>" size="25" maxlength="25" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $ls_readonly ?> onBlur="javascript: ue_calcular_disponible();"></td>
	  <?php
	  	}
	  ?>
    </tr>
    <tr>
      <td >&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
	<?php
		if($ls_mes<="03")
		{
	?>
      <td><div align="right">Marzo</div></td>
      <td><input name="txtmarzo" type="text" id="txtmarzo" value="<?php print $li_marzo?>" size="25" maxlength="25" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $ls_readonly ?> onBlur="javascript: ue_calcular_disponible();"></td>
	<?php
		}
		else
		{
	?>
      <td width="100"></td>
      <td width="211"></td>
	<?php		
		}
		if($ls_mes<="09")
		{
	?>
      <td><div align="right">Septiembre</div></td>
      <td><input name="txtseptiembre" type="text" id="txtseptiembre" value="<?php print $li_septiembre?>" size="25" maxlength="25" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $ls_readonly ?> onBlur="javascript: ue_calcular_disponible();"></td>
	  <?php
	  	}
	  ?>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
	<?php
		if($ls_mes<="04")
		{
	?>
      <td><div align="right">Abril</div></td>
      <td><input name="txtabril" type="text" id="txtabril" value="<?php print $li_abril?>" size="25" maxlength="25" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $ls_readonly ?> onBlur="javascript: ue_calcular_disponible();"></td>
	<?php
		}
		else
		{
	?>
      <td width="100"></td>
      <td width="211"></td>
	<?php		
		}
		if($ls_mes<="10")
		{
	?>
      <td><div align="right">Octubre</div></td>
      <td><input name="txtoctubre" type="text" id="txtoctubre" value="<?php print $li_octubre?>" size="25" maxlength="25" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $ls_readonly ?> onBlur="javascript: ue_calcular_disponible();"></td>
	  <?php
	  	}
	  ?>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
	<?php
		if($ls_mes<="05")
		{
	?>
      <td><div align="right">Mayo</div></td>
      <td><input name="txtmayo" type="text" id="txtmayo" value="<?php print $li_mayo?>" size="25" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $ls_readonly ?> onBlur="javascript: ue_calcular_disponible();"></td>
	<?php
		}
		else
		{
	?>
      <td width="100"></td>
      <td width="211"></td>
	<?php		
		}
		if($ls_mes<="11")
		{
	?>
      <td><div align="right">Noviembre</div></td>
      <td><input name="txtnoviembre" type="text" id="txtnoviembre" value="<?php print $li_noviembre?>" size="25" maxlength="25" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $ls_readonly ?> onBlur="javascript: ue_calcular_disponible();"></td>
	  <?php
	  	}
	  ?>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
	<?php
		if($ls_mes<="06")
		{
	?>
      <td><div align="right">Junio </div></td>
      <td><input name="txtjunio" type="text" id="txtjunio" value="<?php print $li_junio?>" size="25" maxlength="25" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $ls_readonly ?> onBlur="javascript: ue_calcular_disponible();"></td>
	<?php
		}
		else
		{
	?>
      <td width="100"></td>
      <td width="211"></td>
	<?php		
		}
		if($ls_mes<="12")
		{
	?>
      <td><div align="right">Diciembre</div></td>
      <td><input name="txtdiciembre" type="text" id="txtdiciembre" value="<?php print $li_diciembre?>" size="25" maxlength="25" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" <?php print $ls_readonly ?> onBlur="javascript: ue_calcular_disponible();"></td>
	  <?php
	  	}
	  ?>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
      <td colspan="4" class="fd-blanco"><div align="center">
          <input name="botAceptar" type="button" class="boton" id="botAceptar" onClick="ue_aceptar()" value="Aceptar">        
          <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion?>">
          <input name="fila" type="hidden" id="fila" value="<?php print $i?>">        
        <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo ?>">
      </div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<script language="javascript">

function ue_aceptar()
{
	f=document.form1;
	disponible=f.txtmondis.value;
	disponible=ue_formato_operaciones(disponible);
	if(parseFloat(disponible)==0)
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_spg_p_programacion_mensual.php";
		f.submit();
	}
	else
	{
		alert("El disponible debe ser igual a 0.");
	}
}

//---------------------------------------------------------------------
//     Funcion que verifica si existe un objeto en el formulario
//---------------------------------------------------------------------
function valida_objeto(campo)
{
	valido=false;
	existe=document.getElementById(campo);
	if(existe!=null)
	{
		valido=true;
	}
	return valido;
}
//---------------------------------------------------------------------
//     Funcion que devuelve un monto con el formato
//	   debido para realizar operaciones matemeticas
//---------------------------------------------------------------------
function ue_formato_operaciones(valor)
{
	while (valor.indexOf('.')>0)
	{
		valor=valor.replace(".","");
	}
	valor=valor.replace(",",".");
	
	return valor;
	
}
  
function ue_calcular_disponible()
{
	f=document.form1;
	total=0;
	monto=f.txtmonto.value;
	monto=ue_formato_operaciones(monto);
	if(valida_objeto("txtenero"))
	{
		enero=f.txtenero.value;
		enero=ue_formato_operaciones(enero);
		total=(parseFloat(total) + parseFloat(enero));
	}
	if(valida_objeto("txtfebrero"))
	{
		febrero=f.txtfebrero.value;
		febrero=ue_formato_operaciones(febrero);
		total=(parseFloat(total) + parseFloat(febrero));
	}
	if(valida_objeto("txtmarzo"))
	{
		marzo=f.txtmarzo.value;
		marzo=ue_formato_operaciones(marzo);
		total=(parseFloat(total) + parseFloat(marzo));
	}
	if(valida_objeto("txtabril"))
	{
		abril=f.txtabril.value;
		abril=ue_formato_operaciones(abril);
		total=(parseFloat(total) + parseFloat(abril));
	}
	if(valida_objeto("txtmayo"))
	{
		mayo=f.txtmayo.value;
		mayo=ue_formato_operaciones(mayo);
		total=(parseFloat(total) + parseFloat(mayo));
	}
	if(valida_objeto("txtjunio"))
	{
		junio=f.txtjunio.value;
		junio=ue_formato_operaciones(junio);
		total=(parseFloat(total) + parseFloat(junio));
	}
	if(valida_objeto("txtjulio"))
	{
		julio=f.txtjulio.value;
		julio=ue_formato_operaciones(julio);
		total=(parseFloat(total) + parseFloat(julio));
	}
	if(valida_objeto("txtagosto"))
	{
		agosto=f.txtagosto.value;
		agosto=ue_formato_operaciones(agosto);
		total=(parseFloat(total) + parseFloat(agosto));
	}
	if(valida_objeto("txtseptiembre"))
	{
		septiembre=f.txtseptiembre.value;
		septiembre=ue_formato_operaciones(septiembre);
		total=(parseFloat(total) + parseFloat(septiembre));
	}
	if(valida_objeto("txtoctubre"))
	{
		octubre=f.txtoctubre.value;
		octubre=ue_formato_operaciones(octubre);
		total=(parseFloat(total) + parseFloat(octubre));
	}
	if(valida_objeto("txtnoviembre"))
	{
		noviembre=f.txtnoviembre.value;
		noviembre=ue_formato_operaciones(noviembre);
		total=(parseFloat(total) + parseFloat(noviembre));
	}
	if(valida_objeto("txtdiciembre"))
	{
		diciembre=f.txtdiciembre.value;
		diciembre=ue_formato_operaciones(diciembre);
		total=(parseFloat(total) + parseFloat(diciembre));
	}
	disponible=(parseFloat(monto) - parseFloat(total));
	if(parseFloat(disponible)<0)
	{
		alert("El disponible es menor a 0 (cero). Favor Reajustar");
	}
	disponible=uf_convertir(disponible);
	f.txtmondis.value=disponible;
}
 //--------------------------------------------------------
//	Función que formatea un número
//--------------------------------------------------------
function ue_formatonumero(fld, milSep, decSep, e)
{ 
	var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 

	if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Return
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
    	if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
    	if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
     	fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
    return false; 
}
</script>
</html>