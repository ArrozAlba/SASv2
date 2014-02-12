<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
    require_once("class_folder/class_funciones_soc.php");
	$io_funciones_soc=new class_funciones_soc();
	$ls_denart=$io_funciones_soc->uf_obtenervalor_get("denart",""); 
	$ls_codgas=$io_funciones_soc->uf_obtenervalor_get("codgas","");
	$ls_codspg=$io_funciones_soc->uf_obtenervalor_get("codspg","");  
	$ls_codart=$io_funciones_soc->uf_obtenervalor_get("codart","");	 
	$ls_codcargo=$io_funciones_soc->uf_obtenervalor_get("codcargo","");	 
	$ls_status=$io_funciones_soc->uf_obtenervalor_get("estatus","");
	$ls_lugar=$io_funciones_soc->uf_obtenervalor_get("lugar",""); 
	$ls_codgascre=$io_funciones_soc->uf_obtenervalor_get("codgascre",""); 
	$ls_codspgcre=$io_funciones_soc->uf_obtenervalor_get("codspgcre",""); 
	$ls_statuscre=$io_funciones_soc->uf_obtenervalor_get("estatuscre","");
	$ls_tipsol=$io_funciones_soc->uf_obtenervalor_get("tipsol","");  
    $ls_fila=$io_funciones_soc->uf_obtenervalor_get("fila","");  
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
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
-->
</style>
</head>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<body onLoad="javascript: ue_search();">
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="codpro">
<input name="orden" type="hidden" id="orden" value="ASC">
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
		<tr>
		  <td width="500" height="22" colspan="2" class="sin-bordeAzul">Artículo: <? print $ls_codart." ".$ls_denart ?> </td>
		 <? if (($ls_lugar==1)||($ls_lugar==3))
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
		  <td width="500" height="20" colspan="2" class="sin-bordeAzul">Estructura Programatica: <? print $ls_codestpro?> </td>
		  <td width="500" height="20" colspan="2" class="sin-bordeAzul">Cuenta: <? print $ls_codspg?> </td>
	   <? }
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
		 <td width="500" height="20" colspan="2" class="sin-bordeAzul">Estructura Programatica: <? print $ls_codestpro?> </td>
		  <td width="500" height="20" colspan="2" class="sin-bordeAzul">Cuenta: <? print $ls_codspgcre?> </td>
		<? 
		  }
		 ?>
		</tr>
  </table>
<br>
    <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas Presupuestarias </td>
      </tr>
      <tr>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
      </tr>
      <tr>
        <td width="90" height="22" style="text-align:right">Cuenta</td>
        <td width="404" height="22" style="text-align:left"><input name="txtspgcuenta" type="text" id="txtspgcuenta" onKeyPress="javascript: ue_mostrar(this,event);" value="<?php if($ls_codspg!=""){print $ls_codspg;}else{print $ls_codspgcre;} ?>" readonly></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" style="text-align:left"><input name="txtdencue" type="text" id="nombre" onKeyPress="javascript: ue_mostrar(this,event);" size="70"></td>
      </tr>
	  <tr>
        <td colspan="2" style="text-align:right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a><a href="javascript: ue_close();"> <img src="../shared/imagebank/eliminar.gif" alt="Cerrar" width="15" height="15" class="sin-borde">Cerrar</a></td>
			<input name="txtcodart" type="hidden" id="txtcodart" value="<? print $ls_codart; ?>">
			<input name="txtdenart" type="hidden" id="txtdenart" value="<? print $ls_denart; ?>">
			<input name="txtcodgas" type="hidden" id="txtcodgas" value="<? print $ls_codgas; ?>">
			<input name="txtcodspg" type="hidden" id="txtcodspg" value="<? print $ls_codspg; ?>">
			<input name="txtstatus" type="hidden" id="txtstatus" value="<? print $ls_status; ?>">
			<input name="txtlugar" type="hidden" id="txtlugar" value="<? print $ls_lugar; ?>">
			<input name="txtcodcargo" type="hidden" id="txtcodcargo" value="<? print $ls_codcargo; ?>">
			<input name="txtcodgascre" type="hidden" id="txtcodgascre" value="<? print $ls_codgascre; ?>">
			<input name="txtcodspgcre" type="hidden" id="txtcodspgcre" value="<? print $ls_codspgcre; ?>">
			<input name="txtstatuscre" type="hidden" id="txtstatuscre" value="<? print $ls_statuscre; ?>">	
			<input name="tipsol" type="hidden" id="tipsol" value="<? print $ls_tipsol; ?>">	
			<input name="fila" type="hidden" id="fila" value="<? print $ls_fila; ?>">
	  </tr>
	</table> 
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">
function ue_aceptar(as_programatica,as_cuenta,as_denominacion,as_codestpro,as_estcla,ad_mondiscta)
{
	//---------------------------------------------------------------------------------
	// Verificamos que la cuenta presupuestaria no esté en el formulario
	//---------------------------------------------------------------------------------
	valido = true;
	f=document.formulario;
	li_valdispre = "<?php print $_SESSION["la_empresa"]["estparsindis"] ?>";//Validar disponibilidad Presupuestaria.
    if (li_valdispre==1 && ad_mondiscta<=0)
	   {
	     alert("La Partida No tiene Disponibilidad Presupuestaria !!!");
		 valido = false;
	   }
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
	ls_tipsol  = f.tipsol.value;
	codgas=document.formulario.txtcodgas.value;
    codspg=document.formulario.txtcodspg.value;
	estatus=document.formulario.txtstatus.value;		
	lugar=document.formulario.txtlugar.value; 
	fila=document.formulario.fila.value;  
	
/*	if ((lugar==1)||(lugar==3))
	{
		for(j=1;(j<rowcuentas)&&(valido);j++)
		{ 
			cuentagrid=eval("opener.document.formulario.txtcuentagas"+j+".value");
			programatica=eval("opener.document.formulario.txtcodprogas"+j+".value");
			estclagrid=eval("opener.document.formulario.estclapre"+j+".value");
			if((cuentagrid==codspg)&&(programatica==codgas)&&(estclagrid==estatus))
			{  
				eval("opener.document.formulario.txtprogramaticagas"+j+".value='"+as_programatica+"'");
				eval("opener.document.formulario.txtcodprogas"+j+".value='"+as_codestpro+"'");
				eval("opener.document.formulario.txtcuentagas"+j+".value='"+as_cuenta+"'");
				eval("opener.document.formulario.estclapre"+j+".value='"+as_estcla+"'");
				eval("opener.document.formulario.txtspgcuenta"+j+".value='"+as_cuenta+"'");	
			}
		}
	}
*/	tipo=opener.document.formulario.tipord.value;
	parametros="";
	proceso="";
	coincidencias=0;
	if(tipo=="B")
	{
		proceso="AGREGARBIENES";
		//---------------------------------------------------------------------------------
		// Cargar los Bienes del opener y el seleccionado
		//---------------------------------------------------------------------------------
		rowbienes=opener.document.formulario.totrowbienes.value; 
		for(j=1;(j<rowbienes)&&(valido);j++)
		{ 
			codart		 = eval("opener.document.formulario.txtcodart"+j+".value");
			denart		 = eval("opener.document.formulario.txtdenart"+j+".value");
			canart		 = eval("opener.document.formulario.txtcanart"+j+".value");
			unidad		 = eval("opener.document.formulario.cmbunidad"+j+".value");
			preart		 = eval("opener.document.formulario.txtpreart"+j+".value");
			subtotart	 = eval("opener.document.formulario.txtsubtotart"+j+".value");
			carart		 = eval("opener.document.formulario.txtcarart"+j+".value");
			totart		 = eval("opener.document.formulario.txttotart"+j+".value");
			spgcuenta	 = eval("opener.document.formulario.txtcodspg"+j+".value");
			unidadfisica = eval("opener.document.formulario.txtunidad"+j+".value");
			ls_codunieje = eval("opener.document.formulario.txtcoduniadmsep"+j+".value");
			ls_denunieje = eval("opener.document.formulario.txtdenuniadmsep"+j+".value");
			ls_codestpro = eval("opener.document.formulario.txtcodgas"+j+".value"); 
			ls_estcla    = eval("opener.document.formulario.txtstatus"+j+".value");
			ls_numsep    = eval("opener.document.formulario.txtnumsolord"+j+".value");
			if((codgas==ls_codestpro)&&(codspg==spgcuenta)&&(estatus==ls_estcla))
			{
				coincidencias++;
			}
			if (lugar!=1)
			{ 
			    as_codestpro1=eval("opener.document.formulario.txtcodgas"+j+".value");				
				as_cuenta1=eval("opener.document.formulario.txtcodspg"+j+".value");
				as_estcla1=eval("opener.document.formulario.txtstatus"+j+".value"); 
			    parametros=parametros+"&txtcodart"+j+"="+codart+"&txtdenart"+j+"="+denart+""+
					   "&txtcanart"+j+"="+canart+"&cmbunidad"+j+"="+unidad+""+
					   "&txtpreart"+j+"="+preart+"&txtsubtotart"+j+"="+subtotart+""+
					   "&txtcarart"+j+"="+carart+"&txttotart"+j+"="+totart+""+
					   "&txtspgcuenta"+j+"="+as_cuenta1+"&txtunidad"+j+"="+unidadfisica+""+
					   "&txtdenuniadmsep"+j+"="+ls_denunieje+"&txtnumsolord"+j+"="+ls_numsep+""+
					   "&txtcoduniadmsep"+j+"="+ls_codunieje+"&hidcodestpro"+j+"="+ls_codestpro+
					   "&estcla"+j+"="+ls_estcla+"&txtcodprogas"+j+"="+as_codestpro1+"&txtcuentagas"+j+"="+as_cuenta1+
					   "&txtestclagas"+j+"="+as_estcla1+"";
			}
			else
			{  
				if(j==fila)
				{
					spgcuenta=as_cuenta;
					ls_codestpro=as_codestpro;
					ls_estcla=as_estcla;
				}
			    parametros=parametros+"&txtcodart"+j+"="+codart+"&txtdenart"+j+"="+denart+""+
					   "&txtcanart"+j+"="+canart+"&cmbunidad"+j+"="+unidad+""+
					   "&txtpreart"+j+"="+preart+"&txtsubtotart"+j+"="+subtotart+""+
					   "&txtcarart"+j+"="+carart+"&txttotart"+j+"="+totart+""+
					   "&txtspgcuenta"+j+"="+spgcuenta+"&txtunidad"+j+"="+unidadfisica+""+
					   "&txtdenuniadmsep"+j+"="+ls_denunieje+"&txtnumsolord"+j+"="+ls_numsep+""+
					   "&txtcoduniadmsep"+j+"="+ls_codunieje+"&hidcodestpro"+j+"="+ls_codestpro+"&estcla"+j+"="+ls_estcla+"";
					   //+""+"&txtcuentagas"+j+"="+as_cuenta+;
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
			codser		 = eval("opener.document.formulario.txtcodser"+j+".value");
			denser		 = eval("opener.document.formulario.txtdenser"+j+".value");
			canser		 = eval("opener.document.formulario.txtcanser"+j+".value");
			preser		 = eval("opener.document.formulario.txtpreser"+j+".value");
			subtotser	 = eval("opener.document.formulario.txtsubtotser"+j+".value");
			carser		 = eval("opener.document.formulario.txtcarser"+j+".value");
			totser		 = eval("opener.document.formulario.txttotser"+j+".value");
			spgcuenta    = eval("opener.document.formulario.txtcodspg"+j+".value");
			ls_codunieje = eval("opener.document.formulario.txtcoduniadmsep"+j+".value");
			ls_denunieje = eval("opener.document.formulario.txtdenuniadmsep"+j+".value");
			ls_codestpro = eval("opener.document.formulario.txtcodgas"+j+".value");
			ls_estcla    = eval("opener.document.formulario.txtstatus"+j+".value");
			ls_numsep    = eval("opener.document.formulario.txtnumsolord"+j+".value"); 
			if((codgas==ls_codestpro)&&(codspg==spgcuenta)&&(estatus==ls_estcla))
			{
				coincidencias++;
			}
            if (lugar!=3)
			  { 
			     as_codestpro1=eval("opener.document.formulario.txtcodgas"+j+".value");				
				 as_cuenta1=eval("opener.document.formulario.txtcodspg"+j+".value");
				 as_estcla1=eval("opener.document.formulario.txtstatus"+j+".value"); 
			     parametros=parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
					   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
					   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
					   "&txttotser"+j+"="+totser+"&txtspgcuenta"+j+"="+as_cuenta1+""+
					   "&txtdenuniadmsep"+j+"="+ls_denunieje+"&txtnumsolord"+j+"="+ls_numsep+""+
					   "&txtcoduniadmsep"+j+"="+ls_codunieje+"&hidcodestpro"+j+"="+ls_codestpro+""+
					   "&estcla"+j+"="+ls_estcla+"&txtcodprocargo"+j+"="+as_codestpro1+""+
					   "&cuentacargo"+j+"="+as_cuenta1+"&estclacargo"+j+"="+as_estcla1+""; 
			  }
			  else
			  { 
				if(j==fila)
				{
					spgcuenta=as_cuenta;
					ls_codestpro=as_codestpro;
					ls_estcla=as_estcla;
				}
			     parametros=parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
					   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
					   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
					   "&txttotser"+j+"="+totser+"&txtspgcuenta"+j+"="+spgcuenta+""+
					   "&txtdenuniadmsep"+j+"="+ls_denunieje+"&txtnumsolord"+j+"="+ls_numsep+""+
					   "&txtcoduniadmsep"+j+"="+ls_codunieje+"&hidcodestpro"+j+"="+ls_codestpro+""+
					   "&estcla"+j+"="+ls_estcla+"&txtcodprocargo"+j+"="+as_programatica+""+
					   "&estclacargo"+j+"="+ls_estcla+"";
			  }
		}
		parametros=parametros+"&totalservicios="+rowservicios+"";
	}
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias del opener y el seleccionado
	//---------------------------------------------------------------------------------
/*	total=ue_calcular_total_fila_opener("txtcuentagas");
	opener.document.formulario.totrowcuentas.value=total;
	rowcuentas=opener.document.formulario.totrowcuentas.value;
	if ((lugar==1)||(lugar==3))
	    {
			if(coincidencias>1)
			{
				for(j=1;(j<rowcuentas)&&(valido);j++)
				{  
					codpro	  = eval("opener.document.formulario.txtcodprogas"+j+".value");
					cuenta	  = eval("opener.document.formulario.txtcuentagas"+j+".value");
					moncue    = eval("opener.document.formulario.txtmoncuegas"+j+".value");
					ls_estcla = eval("opener.document.formulario.estclapre"+j+".value");
			
					parametros=parametros+"&txtcodprogas"+j+"="+codpro+"&txtcuentagas"+j+"="+cuenta+
							   "&txtmoncuegas"+j+"="+moncue+"&estclapre"+j+"="+ls_estcla;
				}
				  parametros=parametros+"&txtcodprogas"+rowcuentas+"="+as_codestpro+"&txtcuentagas"+rowcuentas+"="+as_cuenta
				             +"&estclapre"+rowcuentas+"="+as_estcla;
				  totalcuentas=rowcuentas;
			}
			else
			{
				for(j=1;(j<rowcuentas)&&(valido);j++)
				{  
					codpro	  = eval("opener.document.formulario.txtcodprogas"+j+".value");
					cuenta	  = eval("opener.document.formulario.txtcuentagas"+j+".value");
					moncue    = eval("opener.document.formulario.txtmoncuegas"+j+".value");
					ls_estcla = eval("opener.document.formulario.estclapre"+j+".value");
					if((codpro==codgas)&&(cuenta==codspg)&&(ls_estcla==estatus))
					{
						parametros=parametros+"&txtcodprogas"+j+"="+as_codestpro+"&txtcuentagas"+j+"="+as_cuenta+
								   "&txtmoncuegas"+j+"="+moncue+"&estclapre"+j+"="+as_estcla;
					}
					else
					{
						parametros=parametros+"&txtcodprogas"+j+"="+codpro+"&txtcuentagas"+j+"="+cuenta+
								   "&txtmoncuegas"+j+"="+moncue+"&estclapre"+j+"="+ls_estcla;
					}
				}
				totalcuentas=rowcuentas-1;
			}
		}
		else
		{
			for(j=1;(j<rowcuentas)&&(valido);j++)
			{  
				codpro	  = eval("opener.document.formulario.txtcodprogas"+j+".value");
				cuenta	  = eval("opener.document.formulario.txtcuentagas"+j+".value");
				moncue    = eval("opener.document.formulario.txtmoncuegas"+j+".value");
				ls_estcla = eval("opener.document.formulario.estclapre"+j+".value");
		
				parametros=parametros+"&txtcodprogas"+j+"="+codpro+"&txtcuentagas"+j+"="+cuenta+
						   "&txtmoncuegas"+j+"="+moncue+"&estclapre"+j+"="+ls_estcla;
			}
			totalcuentas=rowcuentas-1;
		}*/
	//---------------------------------------------------------------------------------
	// Cargar los Cargos del opener y el seleccionado
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de los cargos y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcodservic");
	opener.document.formulario.totrowcargos.value=total;
	rowcargos=opener.document.formulario.totrowcargos.value;
	codgascre=document.formulario.txtcodgascre.value;
    codspgcre=document.formulario.txtcodspgcre.value;
	estatuscre=document.formulario.txtstatuscre.value;
	codcargo=document.formulario.txtcodcargo.value;
	coincidencias=0;
	for(j=1;(j<=rowcargos)&&(valido);j++)
	{
		codservic=eval("opener.document.formulario.txtcodservic"+j+".value");
		codcar=eval("opener.document.formulario.txtcodcar"+j+".value");
		dencar=eval("opener.document.formulario.txtdencar"+j+".value");
		bascar=eval("opener.document.formulario.txtbascar"+j+".value");
		moncar=eval("opener.document.formulario.txtmoncar"+j+".value");
		subcargo=eval("opener.document.formulario.txtsubcargo"+j+".value");
		formulacargo=eval("opener.document.formulario.formulacargo"+j+".value");
		ls_numsep = eval("opener.document.formulario.hidnumsepcar"+j+".value");
	    codprogcargo=eval("opener.document.formulario.codprogcargo"+j+".value");
		estclacargo=eval("opener.document.formulario.estclacargo"+j+".value");
		cuentacargo=eval("opener.document.formulario.cuentacargo"+j+".value"); 
		if (lugar==2)
		{
			if((codcargo==codcar)&&(codgascre==codprogcargo)&&(codspgcre==cuentacargo)&&(estatuscre==estclacargo))
			{
				coincidencias++;
			}
			if(j==fila)
			{
				cuentacargo=as_cuenta; 
				codprogcargo=as_codestpro;
				estclacargo=as_estcla;  
			}
		}
		parametros=parametros+"&txtcodservic"+j+"="+codservic+"&txtcodcar"+j+"="+codcar+
				   "&txtdencar"+j+"="+dencar+"&txtbascar"+j+"="+bascar+
				   "&txtmoncar"+j+"="+moncar+"&txtsubcargo"+j+"="+subcargo+
				   "&cuentacargo"+j+"="+cuentacargo+"&formulacargo"+j+"="+formulacargo+
				   "&hidnumsepcar"+j+"="+ls_numsep+
				   "&codprogcargo"+j+"="+codprogcargo+"&estclacargo"+j+"="+estclacargo;
	}
	parametros=parametros+"&totalcargos="+rowcargos; 
//	opener.document.formulario.totrowcuentas.value=totalcuentas;
//	if ((lugar==1)||(lugar==3))
//		{ 
//			  if (fila!=totalcuentas)
//			  { 
//				  parametros=parametros+"&txtcodprogas"+rowcuentas+"="+as_codestpro+"&txtcuentagas"+rowcuentas+"="+as_cuenta
//				             +"&estclapre"+rowcuentas+"="+as_estcla;
//			  }
//			  else
//			  {
//				  parametros=parametros+"&txtcodprogas"+totalcuentas+"="+as_codestpro+"&txtcuentagas"+totalcuentas+"="+as_cuenta
//				             +"&estclapre"+totalcuentas+"="+as_estcla;
//			  }
//	    }
//	parametros=parametros+"&totalcuentas="+totalcuentas; 
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias del Cargo del opener y el seleccionado
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de las cuentas y asignarlo al total row
/*	total=ue_calcular_total_fila_opener("txtcuentacar");
	opener.document.formulario.totrowcuentascargo.value=total;
	rowcuentas=opener.document.formulario.totrowcuentascargo.value;
	if (lugar==2)
	{
		if(coincidencias>1)
		{
			for(j=1;j<rowcuentas;j++)
			{  
				cargo=eval("opener.document.formulario.txtcodcargo"+j+".value"); 
				codpro=eval("opener.document.formulario.txtcodprocar"+j+".value"); 
				cuenta=eval("opener.document.formulario.txtcuentacar"+j+".value"); 
				moncue=eval("opener.document.formulario.txtmoncuecar"+j+".value");
				ls_estcla=eval("opener.document.formulario.estclacar"+j+".value");
		
				parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
						   "&txtmoncuecar"+j+"="+moncue+"&estclacar"+j+"="+ls_estcla+"&txtcodgascre"+j+"="+codgascre+
						   "&txtcodspgcre"+j+"="+codspgcre+"&txtstatuscre"+j+"="+estatuscre;
			}
			parametros=parametros+"&txtcodcargo"+rowcuentas+"="+cargo+"&txtcodprocar"+rowcuentas+"="+as_codestpro+"&txtcuentacar"+rowcuentas+"="+as_cuenta+
					   "&txtmoncuecar"+rowcuentas+"="+moncue+"&estclacar"+rowcuentas+"="+as_estcla+"&txtcodgascre"+rowcuentas+"="+codgascre+
					   "&txtcodspgcre"+rowcuentas+"="+codspgcre+"&txtstatuscre"+rowcuentas+"="+estatuscre;
				  totalcuentascargo=rowcuentas;
			}
			else
			{
				for(j=1;j<rowcuentas;j++)
				{  
					cargo=eval("opener.document.formulario.txtcodcargo"+j+".value"); 
					codpro=eval("opener.document.formulario.txtcodprocar"+j+".value"); 
					cuenta=eval("opener.document.formulario.txtcuentacar"+j+".value"); 
					moncue=eval("opener.document.formulario.txtmoncuecar"+j+".value");
					ls_estcla=eval("opener.document.formulario.estclacar"+j+".value");
					if((codcargo==cargo)&&(codpro==codgascre)&&(cuenta==codspgcre)&&(ls_estcla==estatuscre))
					{
						parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+as_codestpro+"&txtcuentacar"+j+"="+as_cuenta+
								   "&txtmoncuecar"+j+"="+moncue+"&estclacar"+j+"="+as_estcla+"&txtcodgascre"+j+"="+codgascre+
								   "&txtcodspgcre"+j+"="+codspgcre+"&txtstatuscre"+j+"="+estatuscre;
					}
					else
					{
						parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
								   "&txtmoncuecar"+j+"="+moncue+"&estclacar"+j+"="+ls_estcla+"&txtcodgascre"+j+"="+codgascre+
								   "&txtcodspgcre"+j+"="+codspgcre+"&txtstatuscre"+j+"="+estatuscre;
					}
				}
				totalcuentascargo=rowcuentas-1;
			}
		}
		else
		{
			for(j=1;j<rowcuentas;j++)
			{  
				cargo=eval("opener.document.formulario.txtcodcargo"+j+".value"); 
				codpro=eval("opener.document.formulario.txtcodprocar"+j+".value"); 
				cuenta=eval("opener.document.formulario.txtcuentacar"+j+".value"); 
				moncue=eval("opener.document.formulario.txtmoncuecar"+j+".value");
				ls_estcla=eval("opener.document.formulario.estclacar"+j+".value");
		
				parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
						   "&txtmoncuecar"+j+"="+moncue+"&estclacar"+j+"="+ls_estcla+"&txtcodgascre"+j+"="+codgascre+
						   "&txtcodspgcre"+j+"="+codspgcre+"&txtstatuscre"+j+"="+estatuscre;
			}
			totalcuentascargo=rowcuentas-1;
		}*/
//	if (lugar==2)
//	{
//		for(j=1;(j<rowcuentas)&&(valido);j++)
//		{   
//			//------informacion del grid--------------------------------------------------
//			cuentacregrid=eval("opener.document.formulario.cuentacargo"+j+".value"); 
//			progcregrid=eval("opener.document.formulario.txtprogramaticacar"+j+".value");		
//			estclacregrid=eval("opener.document.formulario.estclacar"+j+".value");					 
//			//----------------------------------------------------------------------------
//            if((cuentacregrid==codspgcre)&&(progcregrid==codgascre)&&(estclacregrid==estatuscre))
//			{ 
//				eval("opener.document.formulario.txtprogramaticacar"+j+".value='"+as_programatica+"'"); 
//				eval("opener.document.formulario.txtcuentacar"+j+".value='"+as_cuenta+"'"); 
//				eval("opener.document.formulario.estclacar"+j+".value='"+as_estcla+"'");
//				
//				eval("opener.document.formulario.txtcodgascre"+j+".value='"+codgascre+"'");
//				eval("opener.document.formulario.txtcodspgcre"+j+".value='"+codspgcre+"'");
//				eval("opener.document.formulario.txtstatuscre"+j+".value='"+estatuscre+"'");
//		     }
//		}
//	}
//	for(j=1;(j<rowcuentas)&&(valido);j++)
//	{
//		cargo=eval("opener.document.formulario.txtcodcargo"+j+".value"); 
//		codpro=eval("opener.document.formulario.txtcodprocar"+j+".value"); 
//		cuenta=eval("opener.document.formulario.txtcuentacar"+j+".value"); 
//		moncue=eval("opener.document.formulario.txtmoncuecar"+j+".value");
//		ls_estcla=eval("opener.document.formulario.estclacar"+j+".value");
//		
//		parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
//				   "&txtmoncuecar"+j+"="+moncue+"&estclacar"+j+"="+ls_estcla+"&txtcodgascre"+j+"="+codgascre+
//				   "&txtcodspgcre"+j+"="+codspgcre+"&txtstatuscre"+j+"="+estatuscre;
//	}
//	if (lugar==2)
//	{ 
//	  totalcuentascargo=eval(rowcuentas+"-"+1);
//	}
//	else
//	{
//	   totalcuentascargo=eval(rowcuentas);
//	} 
//	parametros=parametros+"&totalcuentascargo="+totalcuentascargo; 
//	if (lugar==2)
//	{ 
//	    if (fila!=totalcuentascargo)
//		 {
//		     j=fila-1;
//		     codpro=eval("opener.document.formulario.txtcodprocar"+j+".value");
//		     if(codpro!=as_codestpro)
//			 {
//				  parametros=parametros+"&txtcodprocar"+totalcuentascargo+"="+as_codestpro+"&txtcuentacar"+totalcuentascargo+"="+as_cuenta
//				      +"&estclacar"+totalcuentascargo+"="+as_estcla;
//			 }
//			 else
//			 {
//			      parametros=parametros+"&txtcodprocar"+rowcuentas+"="+as_codestpro+"&txtcuentacar"+rowcuentas+"="+as_cuenta
//						   +"&estclacar"+rowcuentas+"="+as_estcla;
//			 }
//         }
//		 else
//		 {
//		     parametros=parametros+"&txtcodprocar"+totalcuentascargo+"="+as_codestpro+"&txtcuentacar"+totalcuentascargo+"="+as_cuenta
//				      +"&estclacar"+totalcuentascargo+"="+as_estcla;
//		 }
//	}
	//---------------------------------------------------------------------------------
	// Cargar los totales
	//---------------------------------------------------------------------------------
/*	subtotal=eval("opener.document.formulario.txtsubtotal.value");
	cargos=eval("opener.document.formulario.txtcargos.value");
	total=eval("opener.document.formulario.txttotal.value");
	parametros=parametros+"&subtotal="+subtotal+"&cargos="+cargos+"&total="+total+"&tipsol="+ls_tipsol;*/
	parametros=parametros+"&tipsol="+ls_tipsol;
	if((parametros!="")&&(valido))
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("bienesservicios");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_soc_c_registro_orden_compra_ajax.php",true);
		ajax.onreadystatechange=function()
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
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("proceso="+proceso+"&cargarcargos=0"+parametros);
	}
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	tipo=opener.document.formulario.tipord.value;
	fila=document.formulario.fila.value;
	codspgcre=document.formulario.txtcodspgcre.value; 
	codestpro1=opener.document.formulario.txtcodestpro1.value;
	codestpro2=opener.document.formulario.txtcodestpro2.value;
	codestpro3=opener.document.formulario.txtcodestpro3.value;
	codestpro4=opener.document.formulario.txtcodestpro4.value;
	codestpro5=opener.document.formulario.txtcodestpro5.value;
	codunieje=opener.document.formulario.txtcodunieje.value;
	ls_estcla=opener.document.formulario.hidestcla.value;
	ls_tipsol  =f.tipsol.value;
	if(codspgcre!="")
	{
	    scg_cuenta =codspgcre;
		//scg_cuenta ="";
	}
	else
    {
	    scg_cuenta = "<?php print $ls_codspg; ?>";
	}	
	spgcuenta=f.txtspgcuenta.value; 
	dencue=f.txtdencue.value;
	orden=f.orden.value;
	lugar=f.txtlugar.value;
	campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_soc_c_catalogo_ajax.php",true);
	ajax.onreadystatechange=function()
	{
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";
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
	ajax.send("catalogo=CUENTAS-SPG&spgcuenta="+spgcuenta+"&dencue="+dencue+"&codestpro1="+codestpro1+
			  "&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&codestpro5="+codestpro5+
			  "&orden="+orden+"&campoorden="+campoorden+"&tipo="+tipo+"&hidestcla="+ls_estcla+"&scg_cuenta="+scg_cuenta+
			  "&codunieje="+codunieje+"&tipsol="+ls_tipsol+"&fila="+fila+"&lugar="+lugar);
}

function ue_close()
{
	close();
}
</script>
</html>