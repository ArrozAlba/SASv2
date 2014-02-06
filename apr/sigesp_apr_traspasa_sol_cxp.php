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
	require_once("class_folder/class_funciones_apr.php");
	$io_fun_apr=new class_funciones_apr();
	$io_fun_apr->uf_load_seguridad("APR","sigesp_apr_traspasa_sol_cxp.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_ruta="resultado";
	@mkdir($ls_ruta,0755);
	$ls_empdes=$_SESSION["ls_data_des"];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
<head>
<title >Apertura de Sigesp</title>



<!--<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script> -->
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script language="JavaScript" type="text/javascript" src="js/ajax.js"></script>

<script type='text/javascript' src='js/sorttable.js'></script>

<meta http-equiv="imagetoolbar" content="no">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css">
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
.xstooltip 
{
    visibility: hidden; 
    position: absolute; 
    top: 0;  
    left: 0; 
    z-index: 2; 

    font: normal 8pt sans-serif; 
    padding: 3px; 
    border: none;
	background:#EAF8FF;
}

.innerb {height:10em; overflow:auto; width:775px;}
.tableone {width:780px;  margin:0 auto;}
.tabletwo {width:780px; } 
-->border-collapse:collapse;
-->marcado,numsol, fecemisol, consol, monsol,pagado
.th1 {width:15px;}
.th2 {width:120px;}
.th3 {width:80px;}
.th4 {width:250px;}
.th5 {width:150px;}
.th6 {width:150px;}

.td1 {width:15px;}
.td2 {width:30px;}
.td3 {width:80px;}
.td4 {width:250px;}
.td5 {width:150px;}
.td6 {width:150px;}



.boton{
	font-size:10px;
	font-family:Verdana,Helvetica;
	font-weight:bold;
	color:white;
	background:#638cb5;
	border:0px;
	width:80px;
	height:19px;
}


</style>


<?php 
	$ls_modulo = "";
 	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];

	//$isno_copia_solicitudes = new sigesp_apr_solicitudes_cxp();	

	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion = $_POST["operacion"];
		$ldt_fecdes=$_POST[""];
	}
	else
	{
		$ls_operacion ="";
		$ldt_fecdes=date("d/m/Y");
	}	
	
	if  (array_key_exists("optTipDoc",$_POST))
	{
	    $li_tipdoc = $_POST["optTipDoc"];		 	 
	}
	else
	{
	    $li_tipdoc = 1; 
	}	
?>

<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">

</head>
 
<body>
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_apr->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'"); 
	unset($io_fun_apr);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <!--  <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu2.js"></script></td>  -->
  </tr>
</table>

<table width="780" border="0" align="center" cellpadding="1" cellspacing="0" class="contorno">
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu2.js"></script></td>
  </tr>
  <tr>
    <td width="776" height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"></a><a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
  
</table>

<p>

</p>
	
<form name="formulario" id="formulario" method="post" >
<div align="center">
	<input name="operacion" type="hidden" id="operacion">
	
<?php 
	//sigesp_apr_solicitudes_cxp
	if ($ls_operacion=='BUSCAR')		
	{
		$isno_copia_solicitudes->ue_cargar_solicitudes();
		//$isno_copia_nomina->ue_copiar_nomina_basico();
		//echo "$ls_modulo";
		?>			
			<script language='JavaScript'> 
				//mostrar('detalles');
				//document.getElementById("scrolltable").style.visibility="visible"; 
				//f=document.form1;
				//f.action='sigesp_apr_traspasa_sol_cxp.php'; 
				//f.operacion.value="MOSTRAR";
				//f.submit(); 
				//alert('Seleccionó buscar');
			</script>
		<?php
	}
//		if ($ls_operacion=="LIMPIA")
//		{
//			$isno_copia_nomina->ue_limpiar_nomina_basico();	
			?>			
				<script language='JavaScript'> 
//					f=document.form1;
//					f.action='sigesp_apr_traspasa_sol_cxp.php'; 
//					f.operacion.value="MOSTRAR";
//					f.submit(); 
				</script>
			<?php
//		}

		
	?>  
<div id="seleccionar" style="visibility:visible">	
  </div>

  <table width="590" border="0" height="180" class="formato-blanco" align="center" >
        <tr class="titulo-ventana">
          <th height="22" colspan="5">Traspaso de Solicitudes Cuentas por Pagar</th>
        </tr>
		<tr class="formato-blanco">
		  <th scope="col"><div align="left">Criterio de Cambio 
		    <hr noshade="noshade" />
		  </div></th>
	  </tr>
		<tr class="formato-blanco">
			<th scope="col"><table width="480"  border="0">
					<tr>
						<td width="127" height="20">Tipo de Documento</td>
						<td width="167" height="20">&nbsp;</td>
						<td width="172" height="20">&nbsp;</td>
					</tr>
					<tr>
						<td height="20">
							<div align="left">
								
							<?php
							if ($li_tipdoc ==1)
							{
								  $ls_conta="checked";
								  $ls_presu="";
								  
							}  
							else
							{			      
								  $ls_conta="";
								  $ls_presu="checked";			  
							}//if (this.value>0) 
						    ?>
								<input name="optTipDoc" type="radio" onClick="ue_procesa_radio_button('sigesp_apr_tipdoc.php?id=',this.value+'&otro=0','tipodoc')" value="1" checked="checked" <?php print $ls_conta ?>>   
						Contable</div></td>
						<!-- capa que contendrá el combo con los tipos de documento -->
						<td height="20"><div id="tipodoc" align="left">
							<?php
							if($ls_operacion=="")
							{
								require_once("../shared/class_folder/class_sql.php");
								require_once("../shared/class_folder/sigesp_include.php");
								$sig_con=new sigesp_include();
								$io_con=$sig_con->uf_conectar();
								$io_conexion_destino= $sig_con->uf_conectar($_SESSION["ls_data_des"]);
								$io_sql=new class_sql($io_con);
								$io_sql_destino= new class_sql($io_conexion_destino);
								$ls_sql="SELECT codtipdoc, dentipdoc".
										"  FROM cxp_documento".
										" WHERE estcon = 1".
										"   AND (estpre = 3 OR estpre = 4)";
								$rs_data=$io_sql_destino->select($ls_sql);
								print "<select name=select_TipDoc id=select_TipDoc style=width:150px>";
								while($row=$io_sql->fetch_row($rs_data))
								{
									$ls_cod = $row["codtipdoc"];
									$ls_nom = $row["dentipdoc"];	
									echo "<option value='$ls_cod'>$ls_nom</option>";
								}
								print "</select>";
							}
							?>
						</div></td>
						
						<td height="20"><div align="right">Fecha
								<input name="txtfecop" type="text" id="txtfecop" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ldt_fecdes; ?>" size="15" maxlength="15" datepicker="true" style="text-align:center">
						</div></td>
					</tr>
					<tr>
						<td height="20"><div align="left">
								
<input type="radio" name="optTipDoc" value="2"  <?php print $ls_presu ?> onclick="ue_procesa_radio_button('sigesp_apr_tipdoc.php?id=',this.value+'&otro=0','tipodoc')" />						
Presupuestario</div></td>
						<td height="20">&nbsp;</td>
						<td height="20"></td>
					</tr>
				</table>
				<table width="585" border="0" cellpadding="0" cellspacing="0">
<tr>
        <td width="128" height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro1"];?></div></td>
        <td width="421" height="22"><div align="left">
          <input name="txtcodestpro1" type="text" id="txtcodestpro1" size="20" maxlength="20" style="text-align:center" readonly>
          <a href="javascript:ue_estructura1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
          <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" size="45" readonly>
          <input name="estmodest" type="hidden" id="estmodest" value="<?php print $ls_modalidad; ?>" />
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro2"];?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro2" type="text" id="txtcodestpro2" size="20" maxlength="6" style="text-align:center" readonly>
          <a href="javascript:ue_estructura2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
          <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" size="45" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro3"];?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro3" type="text" id="txtcodestpro3" size="20" maxlength="3" style="text-align:center" readonly>
          <a href="javascript:ue_estructura3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
          <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" size="45" readonly>
        </div></td>
      </tr>
<?php if($ls_modalidad=="1") // Por Proyecto
	  {?>
 				<input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="00">
 				<input name="txtdenestpro4" type="hidden" id="txtdenestpro4" value="">
 				<input name="txtcodestpro5" type="hidden" id="txtcodestpro5" value="00">
 				<input name="txtdenestpro5" type="hidden" id="txtdenestpro5" value="">
<?php }
	  else
	  {?>
      <tr>
        <td height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro4"];?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro4" type="text" id="txtcodestpro4" size="20" maxlength="2" style="text-align:center" readonly>
          <a href="javascript:ue_estructura4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
          <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" size="45" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro5"];?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro5" type="text" id="txtcodestpro5" size="20" maxlength="2" style="text-align:center" readonly>
          <a href="javascript:ue_estructura5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
          <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" size="45" readonly>
        </div></td>
      </tr>	
<?php }?>
      <tr>
        <td height="22"><div align="right">Cuenta</div></td>
        <td height="22"><div align="left">
          <input name="txtspgcuenta" type="text" id="txtspgcuenta" style="text-align:center" size="27" maxlength="25" readonly>
          <a href="javascript:ue_cuentasspg();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas de Gastos"></a> 
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" size="40">
          <input name="sccuenta" type="hidden" id="sccuenta">
          <input name="disponibilidad" type="hidden" id="disponibilidad">
        </div></td>
      </table>			</th>
		</tr>
		<tr class="formato-blanco">
		  <th scope="col"><table width="588" height="85" border="0" align="center" class="formato-blanco">
            <tr>
              <th height="25" scope="col"><div align="left">Criterio de B&uacute;squeda
                <hr noshade="noshade" />
              </div></th>
            </tr>
            <tr>
              <th height="41" scope="col"><table width="400" border="0" align="center">
                  <tr>
                    <td width="190" scope="col"><p align="center">Fecha Desde
                      <label>
                            <input type="text" id="txtFDesde" name="txtFDesde" datepicker="true" style="text-align:center"  onkeypress="ue_formatofecha(this,'/',patron,true);" />
                            </label>
                    </p></td>
                    <td width="191" scope="col"><div align="center">
                        <p align="center">Fecha Hasta
                          <label>
                            <input type="text" id="txtFHasta" name="txtFHasta" datepicker="true" style="text-align:center"  onkeypress="ue_formatofecha(this,'/',patron,true);" />
                            </label>
                        </p>
                    </div></td>
                  </tr>
              </table></th>
            </tr>
            <tr>
              <th height="41" scope="col"><div align="center">
                  <div align="center">
                    <table width="524" border="0" cellpadding="0" cellspacing="0">
                      <tr style="visibility:hidden">
                        <td width="193"><div align="right">
                            <input name="rdtipproben" type="radio" class="sin-borde" id="radio" value="0" checked="checked" />
                          Proveedor</div></td>
                        <td width="138"><div align="center">
                            <input name="rdtipproben" type="radio" class="sin-borde" id="radio2" value="1" />
                          Contratista</div></td>
                        <td width="193"><div align="left">
                            <input name="rdtipproben" type="radio" class="sin-borde" id="rdtipproben" value="2" />
                          Beneficiario</div></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>Prefijo
                          <label>
                            <input name="txtPrefijo" type="text" id="txtPrefijo" width="width" />
                          </label></td>
                        <td><input name="statuspago" type="radio" class="sin-borde" value="1" checked="checked" />
                          Pago Parcial</td>
                        <td><div align="center">
                            <input name="statuspago" type="radio" class="sin-borde" value="2" />
                          Contabilizada </div></td>
                      </tr>
                    </table>
                  </div>
              </div></th>
            </tr>
            <tr> </tr>
          </table></th>
	  </tr>
	</table>

  <p align="center"> <input name"btn_buscar" id="buscar" type="button" value="Buscar" onClick="ue_buscar()"  onmouseover="xstooltip_show('tooltip_buscar', 'buscar', 289, 49);" onmouseout="xstooltip_hide('tooltip_buscar');" >
	<div id="concepto_det" style="visibility:hidden;">	
	<div id="detalle_arreglo"></div>
	<table class=tableone border=0 cellpadding=1 cellspacing=1  align=center>
	<thead> 		
	<tr class=titulo-celda>
	<td class='sin-bordeAzul'><div align="left" class="titulo-celdanew">Concepto
	    <input name="txtConcepto" type="text" id="txtconcepto" onkeyup="ue_buscar()" size="110" />
	  </div></td>
	</tr>
	</thead>
	</table>	
	</div>
	
	<div id="detalles" style="visibility:hidden; ">
	</div>
	
	<div id="proceder" style="visibility:hidden">
	<p align="center"> <input name"btn_proceder" id="comenzar" type="button" class="boton" value="Proceder" onClick="javascript:ue_procesar_solicitud('sigesp_apr_procesa_solicitudes.php?id=','2','detalle_arreglo');"  onmouseover="xstooltip_show('tooltip_proceder', 'comenzar', 289, 49);" onmouseout="xstooltip_hide('tooltip_proceder');" >
	</p>

	<div id="progress" style="visibility:hidden">
	  	<div align="center">
			<p><img src="iconos/indicator2.gif" width="16" height="16"></p>
		</div>
	</div>
	
	  
</div>


  <input name="c_modulo" type="hidden" id="c_modulo" >  
</div></div>
</form>



<div align="center"><div align="center"><div id="tooltip_buscar" class="xstooltip">
	Haga click en este botón para comenzar la búsqueda según el criterio seleccionado
</div>

<div id="tooltip_proceder" class="xstooltip">
	Haga click en este botón para comenzar el proceso de copiado<br/>
	Si ocurre un error durante el mismo, serán revertidos los cambios<br/>
	Si el proceso culmina sin errores los cambios <b>no podrán</b> ser revertidos<br/>
</div> 

</div>
</div>



</body>

<div align="center"><div align="center">

<script language="javascript">
f=document.formulario;

function ue_cerrar()
{
	window.open("sigespwindow_blank.php","Blank","_self");
}

function ue_evaluar_click(valor)
{
	var ctaSPG	= f.txtspgcuenta.value;
	var fDesde	= f.txtFDesde.value;
	var fHasta	= f.txtFHasta.value;
	var prefijo	= f.txtPrefijo.value;
	li_totrows  = f.total_rows.value;
	//var estado	= f.statuspago.value;
	var fopera	= f.txtfecop.value;
	var cadena	= '&ctaspg='+ctaSPG+'&fopera='+fopera+'&prefijo='+prefijo;
	MostrarConsultaCapa('sigesp_apr_solicitudes_cxp.php?id=1&posicion='+valor+cadena ,'detalle_arreglo');
}


function ue_buscar()
{
	var fDesde	 = f.txtFDesde.value;
	var fHasta	 = f.txtFHasta.value;
	var prefijo	 = f.txtPrefijo.value;
	if(f.statuspago[0].checked)
	{
		ls_status='S';
	}
	else
	{
		ls_status='C';
	}
	if(f.rdtipproben[0].checked)
	{
		ls_tipproben='P';
	}
	else
	{
		if(f.rdtipproben[1].checked)
		{
			ls_tipproben='C';
		}
		else
		{
			ls_tipproben='B';
		}
	}
	if((fDesde!="")&&(fHasta!=""))
	{
		var concepto = document.getElementById('txtconcepto').value;
		var cadena	= '&fdesde='+fDesde+'&fhasta='+fHasta+'&prefijo='+prefijo+'&status='+ls_status+'&tipproben='+ls_tipproben;				
	
		ocultar('detalles');
		MostrarConsultaCapa('sigesp_apr_carga_solicitudes.php?id=1' + '&conce='+concepto+cadena,'detalles');	//concepto+
		mostrar('concepto_det');	
		mostrar('detalles');
		mostrar('proceder');
	}
	else
	{
		alert("Debe indicar el periodo de busqueda");
	}
}


function ue_procesa_radio_button(destino,valores,donde)
{
	MostrarConsultaCapa('sigesp_apr_tipdoc.php?id=' + valores,donde);
}


function ue_procesar_solicitud(destino,valores,donde)
{
	var ctaSPG	= f.txtspgcuenta.value;
	var fDesde	= f.txtFDesde.value;
	var fHasta	= f.txtFHasta.value;
	var prefijo	= f.txtPrefijo.value;
	li_totrows  = f.total_rows.value;
	//var estado	= f.statuspago.value;
	var fopera	= f.txtfecop.value;
	ls_codtipdoc=f.select_TipDoc.value;
	ls_codestpro1=f.txtcodestpro1.value;
	ls_codestpro2=f.txtcodestpro2.value;
	ls_codestpro3=f.txtcodestpro3.value;
	if(f.estmodest.value==2) // Si es por proyecto
	{
		ls_codestpro4="00";
		ls_codestpro5="00";
	}
	else
	{
		ls_codestpro4=f.txtcodestpro4.value;
		ls_codestpro5=f.txtcodestpro5.value;
	}
	if(f.optTipDoc[0].checked==true)
	{
		ls_estconpre=0;
	}
	else
	{
		ls_estconpre=1;
	}
	var cadena	= '&ctaspg='+ctaSPG+'&fopera='+fopera+'&prefijo='+prefijo+'&codtipdoc='+ls_codtipdoc+'&estconpre='+ls_estconpre+'&codestpro1='+ls_codestpro1+'&codestpro2='+ls_codestpro2+'&codestpro3='+ls_codestpro3+'&codestpro4='+ls_codestpro4+'&codestpro5='+ls_codestpro5;
	if(ls_estconpre==1)
	{
		if(eval(f.txtspgcuenta.value.length) == 0) 
			{
			alert('La Cuenta Presupuestaria no puede estar en blanco');
			return false
			}
	}
	if(eval(f.txtPrefijo.value.length) == 0) 
		{
		alert('El prefijo no puede estar en blanco');
		return false
		}
	else if(eval(f.txtFDesde.value.length) == 0) 
		{
		alert('La Fecha de Inicio no puede estar en blanco');
		return false
		}
	else if(eval(f.txtFHasta.value.length) == 0) 
		{
		alert('La Fecha final no puede estar en blanco');
		return false
		}
	else if(ls_codtipdoc=="")
		{
			alert('El tipo de documento no puede estar en blanco');
			return false
		}
	//else if(eval(f.statuspago.value.length) == 0) 
	//	{
	//	alert('El estado del pago no puede estar en blanco');
	//	return false
	//	}
	//alert(destino + valores+cadena+', donde:'+donde);
	for(a=1;a<=li_totrows;a++)
	{
		if(eval("f.chk"+a+".checked"))
		{
			MostrarConsultaCapa('sigesp_apr_solicitudes_cxp.php?id=2'+cadena+"&posicion="+a,donde);		
		}	
	}

}


function ue_regresar()
{	
	f.action='sigesp_apr_traspasa_sol_cxp.php'; 
	f.operacion.value="MOSTRAR";
	f.submit(); 
}

function ue_cuentasscg()
{
	f=document.formulario;
	pagina="sigesp_apr_cat_cuentasscg.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=610,height=400,resizable=yes,location=no");
	f.txtscgcuenta.value="";
	f.txtscdenominacion.value="";
}

function ue_catascg()
{
	window.open("sigesp_apr_cat_ctasscg.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_estructura1()
{
	window.open("sigesp_apr_cat_estpre1.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	f=document.formulario;
	f.txtcodestpro2.value="";
	f.txtcodestpro3.value="";
	f.txtcodestpro4.value="";
	f.txtcodestpro5.value="";
	f.txtdenestpro2.value="";
	f.txtdenestpro3.value="";
	f.txtdenestpro4.value="";
	f.txtdenestpro5.value="";
	f.txtspgcuenta.value="";
	f.txtdenominacion.value="";
	f.sccuenta.value="";
	f.disponibilidad.value=0;
	f.txtmonto.value="0,00";
}

function ue_estructura2()
{
	f=document.formulario;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	if(codestpro1!="")
	{
		pagina="sigesp_apr_cat_estpre2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
		f.txtcodestpro3.value="";
		f.txtcodestpro4.value="";
		f.txtcodestpro5.value="";
		f.txtdenestpro3.value="";
		f.txtdenestpro4.value="";
		f.txtdenestpro5.value="";
		f.txtspgcuenta.value="";
		f.txtdenominacion.value="";
		f.sccuenta.value="";
		f.disponibilidad.value=0;
		f.txtmonto.value="0,00";
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_estructura3()
{
	f=document.formulario;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	estmodest=f.estmodest.value;
	if(estmodest==2)
	{
		if((codestpro1!="")&&(codestpro2!=""))
		{
			pagina="sigesp_apr_cat_estpre3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione la Estructura de nivel Anterior");
		}
	}
	else
	{
		pagina="sigesp_apr_cat_estpre3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	f.txtcodestpro4.value="";
	f.txtcodestpro5.value="";
	f.txtdenestpro4.value="";
	f.txtdenestpro5.value="";
	f.txtspgcuenta.value="";
	f.txtdenominacion.value="";
	f.sccuenta.value="";
	f.disponibilidad.value=0;
	f.txtmonto.value="0,00";
}

function ue_estructura4()
{
	f=document.formulario;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	codestpro3=f.txtcodestpro3.value;
	denestpro3=f.txtdenestpro3.value;
	if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!=""))
	{
    	pagina="sigesp_cxp_apr_estpre4.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
		f.txtcodestpro5.value="";
		f.txtdenestpro5.value="";
		f.txtspgcuenta.value="";
		f.txtdenominacion.value="";
		f.sccuenta.value="";
		f.disponibilidad.value=0;
		f.txtmonto.value="0,00";
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_estructura5()
{
	f=document.formulario;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	codestpro3=f.txtcodestpro3.value;
	denestpro3=f.txtdenestpro3.value;
	codestpro4=f.txtcodestpro4.value;
	denestpro4=f.txtdenestpro4.value;
	pagina="sigesp_cxp_apr_estpre5.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&codestpro4="+codestpro4+"&denestpro4="+denestpro4;
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	f.txtspgcuenta.value="";
	f.txtdenominacion.value="";
	f.disponibilidad.value=0;
}
function ue_cuentasspg()
{
	f=document.formulario;
	estmodest=f.estmodest.value;
	if(estmodest==1) // Si es por proyecto
	{
		codestpro1=f.txtcodestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		codestpro3=f.txtcodestpro3.value;
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!=""))
		{
			pagina="sigesp_apr_cat_cuentasspg.php";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=610,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione la Estructura Presupuestaria");
		}
	}
	else
	{
		codestpro1=f.txtcodestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		codestpro3=f.txtcodestpro3.value;
		codestpro4=f.txtcodestpro4.value;
		codestpro5=f.txtcodestpro5.value;
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro4!="")&&(codestpro5!=""))
		{
			pagina="sigesp_apr_cat_cuentasspg.php";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=610,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione la Estructura Presupuestaria");
		}
	}
	f.txtspgcuenta.value="";
	f.txtdenominacion.value="";
	f.sccuenta.value="";
	f.disponibilidad.value=0;
	f.txtmonto.value="0,00";
}
function ue_close()
{
	location.href = "sigesp_apr_traspasa_sol_cxp.php";
}
//--------------------------------------------------------
//	Función que le da formato a la fecha
//--------------------------------------------------------
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_formatofecha(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++)
		{
			val2 += val[r]	
		}
		if(nums)
		{
			for(z=0;z<val2.length;z++)
			{
				if(isNaN(val2.charAt(z)))
				{
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++)
		{
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++)
		{
			if(q ==0)
			{
				val = val3[q]
			}
			else
			{
				if(val3[q] != "")
				{
					val += sep + val3[q]
				}
			}
		}
		d.value = val
		d.valant = val
	}
}


</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</div></div></html>
