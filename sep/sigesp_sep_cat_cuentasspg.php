<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
	require_once("class_folder/class_funciones_sep.php");
	$io_funciones_sep=new class_funciones_sep();
	$ls_denart=$io_funciones_sep->uf_obtenervalor_get("denart",""); 
	$ls_codgas=$io_funciones_sep->uf_obtenervalor_get("codgas","");
	$ls_codspg=$io_funciones_sep->uf_obtenervalor_get("codspg",""); 
	$ls_codart=$io_funciones_sep->uf_obtenervalor_get("codart","");	 
	$ls_status=$io_funciones_sep->uf_obtenervalor_get("estatus","");
	$ls_lugar=$io_funciones_sep->uf_obtenervalor_get("lugar",""); 
	$ls_codgascre=$io_funciones_sep->uf_obtenervalor_get("codgascre",""); 
	$ls_codspgcre=$io_funciones_sep->uf_obtenervalor_get("codspgcre","");
	$ls_statuscre=$io_funciones_sep->uf_obtenervalor_get("estatuscre","");
	$ls_fila=$io_funciones_sep->uf_obtenervalor_get("fila","0");
	$ls_unidad=$io_funciones_sep->uf_obtenervalor_get("unidad","0"); 
	require_once("class_folder/sigesp_sep_c_solicitud.php");
	$ls_solicitud=new sigesp_sep_c_solicitud("../");
	if ($ls_codgascre=="")
	{
		$ls_valor=$ls_solicitud->uf_buscar_cuenta_unidad($ls_unidad, $ls_codspg);
	}
	else
	{
		$ls_valor=$ls_solicitud->uf_buscar_cuenta_unidad($ls_unidad, $ls_codspgcre);
		$ls_codspg=$ls_codspgcre; print $ls_codspg;
	}
	
	if (($ls_valor==0)&&($ls_codgascre==""))
	{
		
			print("<script language=JavaScript>");
			print(" alert('No puede realizar la Modificación Presuestaria  ya que la cuenta de Gasto no esta asociada en la Unidad Ejecutora seleccionada.');");
			print(" close();");
			print("</script>");		
	}	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Presupuestarias</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sep.js"></script>
<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="codpro">
<input name="orden" type="hidden" id="orden" value="ASC">
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="500" height="20" colspan="2" class="sin-bordeAzul">Artículo: <?php print $ls_codart." ".$ls_denart ?> </td>
	 <?php if (($ls_lugar==1)||($ls_lugar==3)||($ls_lugar==4))
	 {
		      global $li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,$li_loncodestpro4,$li_loncodestpro5;
			  
			  $li_estmodest     = $_SESSION["la_empresa"]["estmodest"];
			  $li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
			  $li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
			  $li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
			  $li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
			  $li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		 	  $ls_codestpro1 = substr($ls_codgas,0,25); 
			  $ls_codestpro1 = substr($ls_codestpro1,-$li_loncodestpro1);
			  $ls_codestpro2 = substr($ls_codgas,25,25);
			  $ls_codestpro2 = substr($ls_codestpro2,-$li_loncodestpro2);
			  $ls_codestpro3 = substr($ls_codgas,50,25);
			  $ls_codestpro3 = substr($ls_codestpro3,-$li_loncodestpro3);
			  $ls_codestpro  = "";
              
			  if(($ls_codestpro1!="")&&($ls_codestpro2!="")&&($ls_codestpro3!=""))
			  {
		          $ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
              }
			  if ($li_estmodest==2)
				 {

					    $ls_denestcla  = $_SESSION["la_empresa"]["nomestpro1"]; 
					    $ls_codestpro4 = substr($ls_codgas,75,25);
					    $ls_codestpro4 = substr($ls_codestpro4,-$li_loncodestpro4);
					    $ls_codestpro5 = substr($ls_codgas,100,25);
					    $ls_codestpro5 = substr($ls_codestpro5,-$li_loncodestpro5);
						if(($ls_codestpro4!="")&&($ls_codestpro5!=""))
			            {
					       $ls_codestpro  = $ls_codestpro.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
						}   
				 }
	 ?>
	  <td width="500" height="20" colspan="2" class="sin-bordeAzul">Estructura Programatica: <?php print $ls_codestpro?> </td>
	  <td width="500" height="20" colspan="2" class="sin-bordeAzul">Cuenta: <?php print $ls_codspg?> </td>
   <?php }
      else
	  {
		      global $li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,$li_loncodestpro4,$li_loncodestpro5;
			  
			  $li_estmodest     = $_SESSION["la_empresa"]["estmodest"];
			  $li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
			  $li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
			  $li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
			  $li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
			  $li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		 	  $ls_codestpro1 = substr($ls_codgascre,0,25); 
			  $ls_codestpro1 = substr($ls_codestpro1,-$li_loncodestpro1);
			  $ls_codestpro2 = substr($ls_codgascre,25,25);
			  $ls_codestpro2 = substr($ls_codestpro2,-$li_loncodestpro2);
			  $ls_codestpro3 = substr($ls_codgascre,50,25);
			  $ls_codestpro3 = substr($ls_codestpro3,-$li_loncodestpro3);
			  $ls_codestpro  = "";
              
			  if(($ls_codestpro1!="")&&($ls_codestpro2!="")&&($ls_codestpro3!=""))
			  {
		          $ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
              }
			  if ($li_estmodest==2)
				 {

					    $ls_denestcla  = $_SESSION["la_empresa"]["nomestpro1"]; 
					    $ls_codestpro4 = substr($ls_codgascre,75,25);
					    $ls_codestpro4 = substr($ls_codestpro4,-$li_loncodestpro4);
					    $ls_codestpro5 = substr($ls_codgascre,100,25);
					    $ls_codestpro5 = substr($ls_codestpro5,-$li_loncodestpro5);
						if(($ls_codestpro4!="")&&($ls_codestpro5!=""))
			            {
					       $ls_codestpro  = $ls_codestpro.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
						}   
				 }
	 ?>
	 <td width="500" height="20" colspan="2" class="sin-bordeAzul">Estructura Programatica: <?php print $ls_codestpro?> </td>
	  <td width="500" height="20" colspan="2" class="sin-bordeAzul">Cuenta: <?php print $ls_codspgcre?> </td>
	<?php 
	  }
	 ?>
    </tr>
  </table>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="500" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Cuentas Presupuestarias </td>
    </tr>
  </table>
  <br>
    <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="148" height="22"><div align="right">Cuenta Presupuestaria </div></td>
        <td width="346" height="22"><div align="left">
          <input name="txtspgcuenta" type="text" id="txtspgcuenta" onKeyPress="javascript: ue_mostrar(this,event);" value="<?php if($ls_codspg!=""){print $ls_codspg;}else{print $ls_codspgcre;} ?>" readonly>        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><input name="txtdencue" type="text" id="nombre" onKeyPress="javascript: ue_mostrar(this,event);">      </td>
      </tr>
	  <tr>
        <td colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a><a href="javascript: ue_close();"> <img src="../shared/imagebank/eliminar.gif" alt="Cerrar" width="15" height="15" class="sin-borde">Cerrar</a></div></td>
		<input name="txtcodart" type="hidden" id="txtcodart" value="<?php print $ls_codart; ?>">
		<input name="txtdenart" type="hidden" id="txtdenart" value="<?php print $ls_denart; ?>">
		<input name="txtcodgas" type="hidden" id="txtcodgas" value="<?php print $ls_codgas; ?>">
        <input name="txtcodspg" type="hidden" id="txtcodspg" value="<?php print $ls_codspg; ?>">
		<input name="txtstatus" type="hidden" id="txtstatus" value="<?php print $ls_status; ?>">
		<input name="txtlugar" type="hidden" id="txtlugar" value="<?php print $ls_lugar; ?>">
		<input name="txtcodgascre" type="hidden" id="txtcodgascre" value="<?php print $ls_codgascre; ?>">
        <input name="txtcodspgcre" type="hidden" id="txtcodspgcre" value="<?php print $ls_codspgcre; ?>">
		<input name="txtstatuscre" type="hidden" id="txtstatuscre" value="<?php print $ls_statuscre; ?>">
		<input name="txtfila" type="hidden" id="txtfila" value="<?php print $ls_fila; ?>">	
	  </tr>	  
	</table> 
	<p>
  		<div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">
function ue_aceptar(as_programatica,as_cuenta,as_denominacion,as_codespro,as_estcla)
{
	//---------------------------------------------------------------------------------
	// Verificamos que la cuenta presupuestaria no esté en el formulario
	//---------------------------------------------------------------------------------
	valido=true; 
	// Obtenemos el total de filas de los Conceptos
	total=ue_calcular_total_fila_opener("txtcodcon");
	opener.document.formulario.totrowconceptos.value=total;
	// Obtenemos el total de filas de los servicios
	total=ue_calcular_total_fila_opener("txtcodser");
	opener.document.formulario.totrowservicios.value=total;
	// Obtenemos el total de filas de los bienes
	total=ue_calcular_total_fila_opener("txtcodart");
	opener.document.formulario.totrowbienes.value=total;
	// Obtenemos el total de filas de los cargos
	total=ue_calcular_total_fila_opener("txtcodservic");
	opener.document.formulario.totrowcargos.value=total;
	//obtener el numero de filas real de las Cuentas y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcuentagas");
	opener.document.formulario.totrowcuentas.value=total;
	rowcuentas=opener.document.formulario.totrowcuentas.value;
	opener.document.formulario.crearasiento.value=0;
	
	codgas=document.formulario.txtcodgas.value;
    codspg=document.formulario.txtcodspg.value;
	estatus=document.formulario.txtstatus.value;		
	lugar=document.formulario.txtlugar.value;
	fila =document.formulario.txtfila.value; 
	if ((lugar==1)||(lugar==3)||(lugar==4))
	{
		for(j=1;(j<=rowcuentas)&&(valido);j++)
		{ 
			cuentagrid=eval("opener.document.formulario.txtcuentagas"+j+".value");
			programatica=eval("opener.document.formulario.txtprogramaticagas"+j+".value");		
			estclagrid=eval("opener.document.formulario.txtestclagas"+j+".value");						 
			if(((cuentagrid==codspg)&&(programatica==codgas)&&(estclagrid==estatus))||((cuentagrid==codspg)&&(programatica=="")))
			{
	
				eval("opener.document.formulario.txtprogramaticagas"+j+".value='"+as_programatica+"'");
				eval("opener.document.formulario.txtcodprogas"+j+".value='"+as_codespro+"'");
				eval("opener.document.formulario.txtcuentagas"+j+".value='"+as_cuenta+"'");
				eval("opener.document.formulario.txtestclagas"+j+".value='"+as_estcla+"'");
				eval("opener.document.formulario.txtspgcuenta"+j+".value='"+as_cuenta+"'");			   
				//valido=false;			
			}			
		}//fin del for
	}
	
	tiposolicitud=opener.document.formulario.cmbcodtipsol.value;
	tipo=tiposolicitud.substr(3,1);// Para saber si es de bienes, servicios ó conceptos
	parametros="";
	proceso="";
	if(tipo=="B")
	{
		proceso="AGREGARBIENES";
		//---------------------------------------------------------------------------------
		// Cargar los Bienes del opener y el seleccionado
		//---------------------------------------------------------------------------------
		rowbienes=opener.document.formulario.totrowbienes.value;
		for(j=1;(j<rowbienes)&&(valido);j++)
		{
			codart=eval("opener.document.formulario.txtcodart"+j+".value");
			denart=eval("opener.document.formulario.txtdenart"+j+".value");
			canart=eval("opener.document.formulario.txtcanart"+j+".value");
			unidad=eval("opener.document.formulario.cmbunidad"+j+".value");
			preart=eval("opener.document.formulario.txtpreart"+j+".value");
			subtotart=eval("opener.document.formulario.txtsubtotart"+j+".value");
			carart=eval("opener.document.formulario.txtcarart"+j+".value");
			totart=eval("opener.document.formulario.txttotart"+j+".value");
			ls_codestpro = eval("opener.document.formulario.txtcodgas"+j+".value"); 
			ls_estcla    = eval("opener.document.formulario.txtstatus"+j+".value");
			spgcuenta=eval("opener.document.formulario.txtcodspg"+j+".value");			
			unidadfisica=eval("opener.document.formulario.txtunidad"+j+".value"); 
			if (lugar!=1)
			{ 
				as_programatica1=eval("opener.document.formulario.txtcodgas"+j+".value");				
				as_cuenta1=eval("opener.document.formulario.txtcodspg"+j+".value");
				as_estcla1=eval("opener.document.formulario.txtstatus"+j+".value"); 				
				parametros=parametros+"&txtcodart"+j+"="+codart+"&txtdenart"+j+"="+denart+""+
					   "&txtcanart"+j+"="+canart+"&cmbunidad"+j+"="+unidad+""+
					   "&txtpreart"+j+"="+preart+"&txtsubtotart"+j+"="+subtotart+""+
					   "&txtcarart"+j+"="+carart+"&txttotart"+j+"="+totart+""+
					   "&txtspgcuenta"+j+"="+as_cuenta1+"&txtunidad"+j+"="+unidadfisica+""+
					   "&txtcodprogas"+j+"="+as_programatica1+"&txtcuentagas"+j+"="+as_cuenta1+
					   "&txtestclagas"+j+"="+as_estcla1+"&txtcodgas"+j+"="+ls_codestpro+"&txtcodspg"+j+"="+spgcuenta+
					   "&txtstatus"+j+"="+ls_estcla+"";					   				  
			}
			else
			{
				
				if(j==fila)
				{
					spgcuenta=as_cuenta;
					ls_codestpro=as_programatica;
					ls_estcla=as_estcla;
				}
				parametros=parametros+"&txtcodart"+j+"="+codart+"&txtdenart"+j+"="+denart+""+
					   "&txtcanart"+j+"="+canart+"&cmbunidad"+j+"="+unidad+""+
					   "&txtpreart"+j+"="+preart+"&txtsubtotart"+j+"="+subtotart+""+
					   "&txtcarart"+j+"="+carart+"&txttotart"+j+"="+totart+""+
					   "&txtspgcuenta"+j+"="+spgcuenta+"&txtunidad"+j+"="+unidadfisica+""+
					   "&txtcodprogas"+j+"="+ls_codestpro+"&txtcuentagas"+j+"="+spgcuenta+
					   "&txtestclagas"+j+"="+ls_estcla+"&txtcodgas"+j+"="+ls_codestpro+"&txtcodspg"+j+"="+spgcuenta+
					   "&txtstatus"+j+"="+ls_estcla+"";					   
			}			
		}		
		parametros=parametros+"&totalbienes="+rowbienes+"";
	}
	if(tipo=="S")
	{
		proceso="AGREGARSERVICIOS";
		//---------------------------------------------------------------------------------
		// Cargar los Servicios del opener y el seleccionado
		//---------------------------------------------------------------------------------
		rowservicios=opener.document.formulario.totrowservicios.value;
		for(j=1;(j<rowservicios)&&(valido);j++)
		{
			codser=eval("opener.document.formulario.txtcodser"+j+".value");
			denser=eval("opener.document.formulario.txtdenser"+j+".value");
			canser=eval("opener.document.formulario.txtcanser"+j+".value");
			preser=eval("opener.document.formulario.txtpreser"+j+".value");
			subtotser=eval("opener.document.formulario.txtsubtotser"+j+".value");
			carser=eval("opener.document.formulario.txtcarser"+j+".value");
			totser=eval("opener.document.formulario.txttotser"+j+".value");
			ls_codestpro = eval("opener.document.formulario.txtcodgas"+j+".value"); 
			ls_estcla    = eval("opener.document.formulario.txtstatus"+j+".value");
			spgcuenta=eval("opener.document.formulario.txtcodspg"+j+".value");			
			//spgcuenta=eval("opener.document.formulario.txtspgcuenta"+j+".value");					
			if (lugar!=3)
			{ 
				as_programatica1=eval("opener.document.formulario.txtcodgas"+j+".value");				
				as_cuenta1=eval("opener.document.formulario.txtcodspg"+j+".value");
				as_estcla1=eval("opener.document.formulario.txtstatus"+j+".value");			
				parametros=parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
					   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
					   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
					   "&txttotser"+j+"="+totser+"&txtspgcuenta"+j+"="+as_cuenta1+
					   "&txtcodprogas"+j+"="+as_programatica1+"&txtcuentagas"+j+"="+as_cuenta1+
					   "&txtestclagas"+j+"="+as_estcla1+"&txtcodgas"+j+"="+ls_codestpro+"&txtcodspg"+j+"="+spgcuenta+
					   "&txtstatus"+j+"="+ls_estcla+"";					  
			}
			else
			{
				if(j==fila)
				{
					spgcuenta=as_cuenta;
					ls_codestpro=as_programatica;
					ls_estcla=as_estcla;
				}
				parametros=parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
					   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
					   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
					   "&txttotser"+j+"="+totser+"&txtspgcuenta"+j+"="+spgcuenta+
					   "&txtcodprogas"+j+"="+ls_codestpro+"&txtcuentagas"+j+"="+spgcuenta+
					   "&txtestclagas"+j+"="+ls_estcla+"&txtcodgas"+j+"="+ls_codestpro+"&txtcodspg"+j+"="+spgcuenta+
					   "&txtstatus"+j+"="+ls_estcla+"";					   
			}				
		}
		parametros=parametros+"&totalservicios="+rowservicios+"";
	}
	if(tipo=="O")
	{
		proceso="AGREGARCONCEPTOS";
		//---------------------------------------------------------------------------------
		// Cargar los Conceptos del opener y el seleccionado
		//---------------------------------------------------------------------------------
		rowconceptos=opener.document.formulario.totrowconceptos.value;
		for(j=1;(j<rowconceptos)&&(valido);j++)
		{
			codcon=eval("opener.document.formulario.txtcodcon"+j+".value");
			dencon=eval("opener.document.formulario.txtdencon"+j+".value");
			cancon=eval("opener.document.formulario.txtcancon"+j+".value");
			precon=eval("opener.document.formulario.txtprecon"+j+".value");
			subtotcon=eval("opener.document.formulario.txtsubtotcon"+j+".value");
			carcon=eval("opener.document.formulario.txtcarcon"+j+".value");
			totcon=eval("opener.document.formulario.txttotcon"+j+".value");
			ls_codestpro = eval("opener.document.formulario.txtcodgas"+j+".value"); 
			ls_estcla    = eval("opener.document.formulario.txtstatus"+j+".value");
			spgcuenta=eval("opener.document.formulario.txtcodspg"+j+".value");			
			if (lugar!=4)
			{ 
				as_programatica1=eval("opener.document.formulario.txtcodgas"+j+".value");				
				as_cuenta1=eval("opener.document.formulario.txtcodspg"+j+".value");
				as_estcla1=eval("opener.document.formulario.txtstatus"+j+".value");				
				parametros=parametros+"&txtcodcon"+j+"="+codcon+"&txtdencon"+j+"="+dencon+""+
					   "&txtcancon"+j+"="+cancon+"&txtprecon"+j+"="+precon+""+
					   "&txtsubtotcon"+j+"="+subtotcon+"&txtcarcon"+j+"="+carcon+""+
					   "&txttotcon"+j+"="+totcon+"&txtspgcuenta"+j+"="+as_cuenta1+
					   "&txtcodprogas"+j+"="+as_programatica1+"&txtcuentagas"+j+"="+as_cuenta1+
					   "&txtestclagas"+j+"="+as_estcla1+"&txtcodgas"+j+"="+ls_codestpro+"&txtcodspg"+j+"="+spgcuenta+
					   "&txtstatus"+j+"="+ls_estcla+"";					  
			}
			else
			{
				if(j==fila)
				{
					spgcuenta=as_cuenta;
					ls_codestpro=as_programatica;
					ls_estcla=as_estcla;
				}
				parametros=parametros+"&txtcodcon"+j+"="+codcon+"&txtdencon"+j+"="+dencon+""+
						   "&txtcancon"+j+"="+cancon+"&txtprecon"+j+"="+precon+""+
						   "&txtsubtotcon"+j+"="+subtotcon+"&txtcarcon"+j+"="+carcon+""+
						   "&txttotcon"+j+"="+totcon+"&txtspgcuenta"+j+"="+spgcuenta+
						   "&txtcodprogas"+j+"="+ls_codestpro+"&txtcuentagas"+j+"="+spgcuenta+
						   "&txtestclagas"+j+"="+ls_estcla+"&txtcodgas"+j+"="+ls_codestpro+"&txtcodspg"+j+"="+spgcuenta+
					       "&txtstatus"+j+"="+ls_estcla+"";
									   
			}			
	
			
		}
		parametros=parametros+"&totalconceptos="+rowconceptos+"";
	}
	//---------------------------------------------------------------------------------
	// Cargar los Cargos del opener y el seleccionado
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de los cargos y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcodservic");
	opener.document.formulario.totrowcargos.value=total;
	rowcargos=opener.document.formulario.totrowcargos.value;
	for(j=1;(j<=rowcargos)&&(valido);j++)
	{   
	    codservic=eval("opener.document.formulario.txtcodservic"+j+".value");
		codcar=eval("opener.document.formulario.txtcodcar"+j+".value");
        dencar=eval("opener.document.formulario.txtdencar"+j+".value");
		bascar=eval("opener.document.formulario.txtbascar"+j+".value");
		moncar=eval("opener.document.formulario.txtmoncar"+j+".value");
		subcargo=eval("opener.document.formulario.txtsubcargo"+j+".value");
		codgascre  = eval("opener.document.formulario.txtcodgascre"+j+".value");
		codspgcre  = eval("opener.document.formulario.txtcodspgcre"+j+".value");
		statuscre  = eval("opener.document.formulario.txtstatuscre"+j+".value");
		cuentacargo=eval("opener.document.formulario.cuentacargo"+j+".value"); 
		if (lugar==2)
		{ 
			if(j==fila)
			{
				codgascre= as_programatica;
				codspgcre= as_cuenta;
				statuscre= as_estcla;
			}
		}
		else
		{
		}		
		formulacargo=eval("opener.document.formulario.formulacargo"+j+".value");
		parametros=parametros+"&txtcodservic"+j+"="+codservic+"&txtcodcar"+j+"="+codcar+
					   "&txtdencar"+j+"="+dencar+"&txtbascar"+j+"="+bascar+
					   "&txtmoncar"+j+"="+moncar+"&txtsubcargo"+j+"="+subcargo+
					   "&cuentacargo"+j+"="+cuentacargo+"&formulacargo"+j+"="+formulacargo+
									"&txtcodgascre"+j+"="+codgascre+"&txtcodspgcre"+j+"="+codspgcre+"&txtstatuscre"+j+"="+statuscre;
	}
	parametros=parametros+"&totalcargos="+rowcargos;
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias del opener y el seleccionado
	//---------------------------------------------------------------------------------
/*	for(j=1;(j<rowcuentas)&&(valido);j++)
		{
			codpro=eval("opener.document.formulario.txtprogramaticagas"+j+".value"); 
			estclagas=eval("opener.document.formulario.txtestclagas"+j+".value");
			cuenta=eval("opener.document.formulario.txtcuentagas"+j+".value");
			moncue=eval("opener.document.formulario.txtmoncuegas"+j+".value");
			parametros=parametros+"&txtcodprogas"+j+"="+codpro+"&txtcuentagas"+j+"="+cuenta+
					   "&txtmoncuegas"+j+"="+moncue+"&txtestclagas"+j+"="+estclagas;
					 
		}
		totalcuentas=eval(rowcuentas+"-"+1);		
		opener.document.formulario.totrowcuentas.value=totalcuentas;
		if ((lugar!=1)||(lugar!=3)||(lugar!=4))
		{	
			parametros=parametros+"&txtcodprogas"+rowcuentas+"="+as_codespro+"&txtcuentagas"+rowcuentas+"="+as_cuenta+
					   "&totalcuentas="+totalcuentas+"&txtestclagas"+j+"="+as_estcla;
		}	
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias del Cargo del opener y el seleccionado
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de las cuentas y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcuentacar");
	opener.document.formulario.totrowcuentascargo.value=total;
	rowcuentas=opener.document.formulario.totrowcuentascargo.value;
	
	codgascre=document.formulario.txtcodgascre.value;
    codspgcre=document.formulario.txtcodspgcre.value;
	estatuscre=document.formulario.txtstatuscre.value;
	if (lugar==2)
	{
		for(j=1;(j<=rowcuentas)&&(valido);j++)
		{ 
			//------informacion del grid-----------------------------------
			cuentacregrid=eval("opener.document.formulario.txtcuentacar"+j+".value");
			progcregrid=eval("opener.document.formulario.txtprogramaticacar"+j+".value");		
			estclacregrid=eval("opener.document.formulario.txtestclacar"+j+".value");					 
			//----------------------------------------------------------------------------
            if((cuentacregrid==codspgcre)&&(progcregrid==codgascre)&&(estclacregrid==estatuscre))
			{
				eval("opener.document.formulario.txtprogramaticacar"+j+".value='"+as_programatica+"'");
	            eval("opener.document.formulario.txtestclacar"+j+".value='"+as_estcla+"'"); 
			    eval("opener.document.formulario.txtcuentacar"+j+".value='"+as_cuenta+"'");	
				
				eval("opener.document.formulario.txtcodgascre"+j+".value='"+codgascre+"'");
				eval("opener.document.formulario.txtcodspgcre"+j+".value='"+codspgcre+"'");
				eval("opener.document.formulario.txtstatuscre"+j+".value='"+estatuscre+"'");				  		  
				//valido=false;			
			}			
		}//fin del for
	}// fin del if
	for(j=1;(j<rowcuentas)&&(valido);j++)
	{  
		cargo=eval("opener.document.formulario.txtcodcargo"+j+".value");
		codpro=eval("opener.document.formulario.txtprogramaticacar"+j+".value");
		estclacar=eval("opener.document.formulario.txtestclacar"+j+".value"); 
		cuenta=eval("opener.document.formulario.txtcuentacar"+j+".value");
		moncue=eval("opener.document.formulario.txtmoncuecar"+j+".value");
		parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
					   "&txtmoncuecar"+j+"="+moncue+"&txtestclacar"+j+"="+estclacar+
					   "&txtcodgascre"+j+"="+codgascre+"&txtcodspgcre"+j+"="+codspgcre+
					   "&txtstatuscre"+j+"="+estatuscre+"";
	}			
	totalcuentascargo=eval(rowcuentas);
	parametros=parametros+"&totalcuentascargo="+totalcuentascargo;	
	//---------------------------------------------------------------------------------
	// Cargar los totales
	//---------------------------------------------------------------------------------
	subtotal=eval("opener.document.formulario.txtsubtotal.value");
	cargos=eval("opener.document.formulario.txtcargos.value");
	total=eval("opener.document.formulario.txttotal.value");
	parametros=parametros+"&subtotal="+subtotal+"&cargos="+cargos+"&total="+total;*/
	if((parametros!="")&&(valido))
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("bienesservicios");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_sep_c_solicitud_ajax.php",true);
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1)
			{
				//divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
			}
			else
			{
				if(ajax.readyState==4)
				{
					if(ajax.status==200)
					{//mostramos los datos dentro del contenedor
						divgrid.innerHTML = ajax.responseText
					}
					else
					{
						if(ajax.status==404)
						{
							divgrid.innerHTML = "La página no existe";
						}
						else
						{//mostramos el posible error     
							divgrid.innerHTML = "Error:".ajax.status;
						}
					}
					
				}
			}
		}	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("proceso="+proceso+"&cargarcargos=0"+parametros); 
	}
	setTimeout(close,500);
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	tiposolicitud=opener.document.formulario.cmbcodtipsol.value;
	tipo=tiposolicitud.substr(3,1);// Para saber si es de bienes, servicios ó conceptos
	
	codestpro1=opener.document.formulario.txtcodestpro1.value; 
	codestpro2=opener.document.formulario.txtcodestpro2.value;
	codestpro3=opener.document.formulario.txtcodestpro3.value; 
	li_estmodest = "<?php print $_SESSION["la_empresa"]["estmodest"] ?>";
	if (li_estmodest=='2')//Presupuesto por Programas.
	   {
	     codestpro4=opener.document.formulario.txtcodestpro4.value;
		 codestpro5=opener.document.formulario.txtcodestpro5.value;	   
	   }
	else
	   {
	     codestpro4 = codestpro5 = "";
	   }
	estcla=	opener.document.formulario.txtestcla.value;	
	scg_cuenta = "<?php print $ls_codspg; ?>";	
	spgcuenta=f.txtspgcuenta.value;
	dencue=f.txtdencue.value;
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_sep_c_catalogo_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText
				}
				else
				{
					if(ajax.status==404)
					{
						divgrid.innerHTML = "La página no existe";
					}
					else
					{//mostramos el posible error     
						divgrid.innerHTML = "Error:".ajax.status;
					}
				}
				
			}
		}
	}	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("catalogo=CUENTASSPG&spgcuenta="+spgcuenta+"&dencue="+dencue+"&codestpro1="+codestpro1+
			  "&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&codestpro5="+codestpro5+
			  "&orden="+orden+"&campoorden="+campoorden+"&tipo="+tipo+"&estcla="+estcla+"&estmodest="+li_estmodest+"&scg_cuenta="+scg_cuenta);
}

function ue_close()
{
	close();
}
</script>
</html>