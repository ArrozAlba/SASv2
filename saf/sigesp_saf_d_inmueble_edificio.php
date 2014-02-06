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
require_once("class_funciones_activos.php");
$io_fun_activo=new class_funciones_activos("../");
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_d_inmueble_edificio.php",$ls_permisos,$la_seguridad,$la_permisos);

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
		global $ls_areatot, $ls_areacons, $ls_numpiso, $ls_areatotpiso, $ls_areanex, $ls_feccont, $ls_moncont, $ls_existe;
		global $ls_avaluo, $ls_fila;
		$ls_areatot="";
		$ls_areacons="";
		$ls_numpiso="";
		$ls_areatotpiso="";
		$ls_areanex="";
		$ls_feccont="dd/mm/yyyy";
		$ls_moncont="";
		$ls_existe="";
		$ls_avaluo="";
		$ls_fila="";
		if(array_key_exists("existe",$_POST))
		{
			$ls_existe=$_POST["existe"];
		}
		else
		{
			$ls_existe="FALSE";
		}		
   }
   
   function uf_agregarlineablanca1(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca1
		//		   Access: private
		//	    Arguments: ai_totrows  // total de Filas
		//	  Description: Función que agrega una linea mas en el grid
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 26/09/2008								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////		
		$aa_object[$ai_totrows][1]="<input type=text   name=txtdentipest".$ai_totrows." class=sin-borde2  size=5 readonly>
		                            <input type=hidden name=txtcodtipest".$ai_totrows." class=sin-borde2  size=5 readonly>";
		$aa_object[$ai_totrows][2]="<input type=text name=txtcodcomp".$ai_totrows." class=sin-borde2  size=5 readonly>";
		$aa_object[$ai_totrows][3]="<input type=text name=txtdencomp".$ai_totrows." class=sin-borde2  size=20 readonly>";
		$aa_object[$ai_totrows][4]="<div align='center'><a href='javascript:ue_eliminar($ai_totrows);'><img src='../shared/imagebank/tools20/eliminar.gif' alt='Buscar' width='15' height='15' border='0'></a></div>";		
   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Datos del Edificio</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
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
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" src="js/yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="js/yui/build/element/element-beta.js"></script>
<script type="text/javascript" src="js/yui/build/tabview/tabview.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="js/yui/build/fonts/fonts-min.css" rel="stylesheet"  type="text/css">
<link href="js/yui/build/tabview/assets/skins/sam/tabview.css" rel="stylesheet" type="text/css">
<link href="../../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">


</head>

<body class=" yui-skin-sam">
<?php
	require_once("../shared/class_folder/grid_param.php");
    $io_grid=new grid_param();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();	
	require_once("class_funciones_activos.php");
	$io_fac= new class_funciones_activos("../");
	require_once("sigesp_c_inmueble_edificio.php");
	$io_inm_edif= new sigesp_c_inmueble_edificio();
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_denact=$io_fac->uf_obtenervalor_get("denact","Ninguno");
	$ls_codact=$io_fac->uf_obtenervalor_get("codact","");
	$ls_nombre= "CODIGO: ".$ls_codact." ACTIVO: ".$ls_denact;
    //---------titulos1//----------------------------------
	$lo_title1[1]="Estructura";
	$lo_title1[2]="C&oacute;digo";
	$lo_title1[3]="Material";
	$lo_title1[4]="Eliminar";
	//-----------------------------------------------------
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];		
	}
	else
	{
		$ls_operacion="NUEVO";
	}	
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();
			uf_agregarlineablanca1($aa_object,1);					 
			$ai_totrows=$io_fac->uf_obtenervalor("htotal","1");
			$lb_valido=$io_inm_edif->uf_select_inmueble_edificio($ls_codact,$ls_expact,$ls_clasfun,$ls_diract,$ls_areatot,
	                                                            $ls_areacons,$ls_numpiso, $ls_areatotpiso, $ls_areanex, 
																$ls_lindero,$ls_estlegprop,$ls_avaluo,$ls_feccont,$ls_moncont, 
																$ls_existe);
			$io_inm_edif->uf_buscar_material($ls_codact,$ls_expact,$aa_object,$ai_totrows);	
			
			if ($ai_totrows>1)
			{	$ai_totrows=$ai_totrows+1;
				uf_agregarlineablanca1($aa_object,$ai_totrows);	
			}												
		break;

		case "GUARDAR":
		    $ls_codact="";
			$ls_codact=$io_fac->uf_obtenervalor("txtcodact","");
			$ls_denact="";
			$ls_denact=$io_fac->uf_obtenervalor("txtdenact","");
			$ls_nombre="";
			$ls_nombre= "CODIGO: ".$ls_codact." ACTIVO: ".$ls_denact; 
			$ls_expact=$io_fac->uf_obtenervalor("txtexpact","");
			$ls_clasfun=$io_fac->uf_obtenervalor("txtclasfun","");
			$ls_diract=$io_fac->uf_obtenervalor("txtdiract","");
			$ls_areatot=$io_fac->uf_obtenervalor("txtareatot","");
			$ls_areacons=$io_fac->uf_obtenervalor("txtareacons","");
			$ls_numpiso=$io_fac->uf_obtenervalor("txtnumpiso","");
			$ls_areatotpiso=$io_fac->uf_obtenervalor("txtareatotpiso","");
			$ls_areanex=$io_fac->uf_obtenervalor("txtareanex","");
			$ls_lindero=$io_fac->uf_obtenervalor("txtlind","");
			$ls_estlegprop=$io_fac->uf_obtenervalor("txtestleg","");
			$ls_avaluo=$io_fac->uf_obtenervalor("txtavaluo","");
			$ls_feccont=$io_fac->uf_obtenervalor("txtfeccont","1900-01-01");
			$ls_existe=$io_fac->uf_obtenervalor("existe",""); 
			if ($ls_feccont=="dd/mm/yyyy")
			{
				$ls_feccont="1900-01-01";
			}
			$ls_moncont=$io_fac->uf_obtenervalor("txtmoncont","0.00"); 
			if ($ls_moncont=="")
			{
				$ls_moncont="0.00";
			}
			$ls_existe=$io_fac->uf_obtenervalor("existe",""); 			
			$lb_valido=$io_inm_edif->guardar($ls_codact,$ls_expact, $ls_clasfun, $ls_diract, $ls_areatot,
	                                          $ls_areacons,$ls_numpiso, $ls_areatotpiso, $ls_areanex, $ls_lindero,
											  $ls_estlegprop, $ls_avaluo, $ls_feccont, $ls_moncont, $ls_existe, $la_seguridad);
			if ($lb_valido)
			{
				$ls_existe="TRUE";
				$ai_totrows=$io_fac->uf_obtenervalor("htotal","");
				if ($ai_totrows>1)
				{
					for ($i=1;$i<$ai_totrows;$i++)
					{
						$ls_dentipest=$io_fac->uf_obtenervalor("txtdentipest".$i,"");
						$ls_codtipest=$io_fac->uf_obtenervalor("txtcodtipest".$i,"");
						$ls_codcomp=$io_fac->uf_obtenervalor("txtcodcomp".$i,"");
						$ls_dencom=$io_fac->uf_obtenervalor("txtdencomp".$i,"");
						$ls_encontrado="";
						$io_inm_edif->uf_select_saf_edificiotipest($ls_codtipest,$ls_codcomp,$ls_codact,$ls_expact,$ls_encontrado);	
						if ($ls_encontrado==0)
						{
							$io_inm_edif->uf_insertar_materiales($ls_codtipest,$ls_codcomp,$ls_codact,$ls_expact, $la_seguridad);
						}					
						$aa_object[$i][1]="<input type=text name=txtdentipest".$i." class=sin-borde  size=30 value='". $ls_dentipest."' readonly>
										   <input type=hidden name=txtcodtipest".$i." value='".$ls_codtipest."' readonly>";
						$aa_object[$i][2]="<input type=text name=txtcodcomp".$i." class=sin-borde  size=5 value='". $ls_codcomp."' readonly>";
						$aa_object[$i][3]="<input type=text name=txtdencomp".$i." class=sin-borde  size=20 value='".$ls_dencom."' readonly>";
						$aa_object[$i][4]="<div align='center'><a href='javascript:ue_eliminar($i);'><img src='../shared/imagebank/tools20/eliminar.gif' alt='Buscar' width='15' height='15' border='0'></a></div>";		
					} //fin del for	
				}//fin del if
				uf_agregarlineablanca1($aa_object,$ai_totrows);
				
			}
		break;
		
		case "AGREGAR":
		$ai_totrows=$io_fac->uf_obtenervalor("htotal","");	
		$parametros=$io_fac->uf_obtenervalor("hparam1",""); 
		$arre_parametros=split("&",$parametros);
		$j=1;
		$k=0;		
		for ($i=0;$i<$ai_totrows;$i++)
		{
		    $k++;  
		    $ls_codcomp=trim(substr($arre_parametros[$j],13,15)); 
			$j++; 
            $ls_dencom=trim(substr($arre_parametros[$j],13,63)); 
			$j++; 
			$ls_dentipest=trim(substr($arre_parametros[$j],15,80)); 
			$j++;
			$ls_codtipest=trim(substr($arre_parametros[$j],15,80));
			$j++;
			$aa_object[$k][1]="<input type=text name=txtdentipest".$k." class=sin-borde  size=30 value='". $ls_dentipest."' readonly>
			                   <input type=hidden name=txtcodtipest".$k." value='".$ls_codtipest."' readonly>";
			$aa_object[$k][2]="<input type=text name=txtcodcomp".$k." class=sin-borde  size=5 value='". $ls_codcomp."' readonly>";
			$aa_object[$k][3]="<input type=text name=txtdencomp".$k." class=sin-borde  size=20 value='".$ls_dencom."' readonly>";
			$aa_object[$k][4]="<div align='center'><a href='javascript:ue_eliminar($k);'><img src='../shared/imagebank/tools20/eliminar.gif' alt='Buscar' width='15' height='15' border='0'></a></div>";		
		} 	
		 $ai_totrows=$ai_totrows+1;
		 uf_agregarlineablanca1($aa_object,$ai_totrows);
		 $ls_codact=$io_fac->uf_obtenervalor("txtcodact","");
		 $ls_expact=$io_fac->uf_obtenervalor("txtexpact","");
		 $lb_valido=$io_inm_edif->uf_select_inmueble_edificio($ls_codact,$ls_expact,$ls_clasfun,$ls_diract,$ls_areatot,
	                                                            $ls_areacons,$ls_numpiso, $ls_areatotpiso, $ls_areanex, 
																$ls_lindero,$ls_estlegprop,$ls_avaluo,$ls_feccont,$ls_moncont, 
																$ls_existe);
		break;	
		
	  case "ELIMINAR":
		 $ai_totrows=$io_fac->uf_obtenervalor("htotal","");	
		 $ls_codact=$io_fac->uf_obtenervalor("txtcodact","");
		 $ls_expact=$io_fac->uf_obtenervalor("txtexpact","");
		 $ls_fila=$io_fac->uf_obtenervalor("fila","");
		 $lb_valido=$io_inm_edif->uf_select_inmueble_edificio($ls_codact,$ls_expact,$ls_clasfun,$ls_diract,$ls_areatot,
	                                                            $ls_areacons,$ls_numpiso, $ls_areatotpiso, $ls_areanex, 
																$ls_lindero,$ls_estlegprop,$ls_avaluo,$ls_feccont,$ls_moncont, 
																$ls_existe);
	    $ai_totrows=$io_fac->uf_obtenervalor("htotal","");
		$ai_totrows=$ai_totrows-1;				
		if ($ai_totrows>1)
		{   $li_temp=0;
			for ($i=1;$i<=$ai_totrows;$i++)
			{
				$li_temp++;	
				$ls_dentipest=$io_fac->uf_obtenervalor("txtdentipest".$i,"");
				$ls_codtipest=$io_fac->uf_obtenervalor("txtcodtipest".$i,"");
				$ls_codcomp=$io_fac->uf_obtenervalor("txtcodcomp".$i,"");
				$ls_dencom=$io_fac->uf_obtenervalor("txtdencomp".$i,"");
				$ls_encontrado="";
				
				if ($i==$ls_fila)
				{
					$io_inm_edif->uf_select_saf_edificiotipest($ls_codtipest,$ls_codcomp,$ls_codact,$ls_expact,$ls_encontrado);	
					if ($ls_encontrado>0)
					{
						$io_inm_edif->uf_eliminar_materiales($ls_codtipest,$ls_codcomp,$ls_codact,$ls_expact,$la_seguridad);
					}
				}
				if ($i!=$ls_fila)
				{	
					$aa_object[$li_temp][1]="<input type=text name=txtdentipest".$li_temp." class=sin-borde  size=30 value='". $ls_dentipest."' readonly>
											   <input type=hidden name=txtcodtipest".$li_temp." value='".$ls_codtipest."' readonly>";
					$aa_object[$li_temp][2]="<input type=text name=txtcodcomp".$li_temp." class=sin-borde  size=5 value='". $ls_codcomp."' readonly>";
					$aa_object[$li_temp][3]="<input type=text name=txtdencomp".$li_temp." class=sin-borde  size=20 value='".$ls_dencom."' readonly>";
					$aa_object[$li_temp][4]="<div align='center'><a href='javascript:ue_eliminar($i);'><img src='../shared/imagebank/tools20/eliminar.gif' alt='Buscar' width='15' height='15' border='0'></a></div>";	
				}	
			} //fin del for
				
			uf_agregarlineablanca1($aa_object,$ai_totrows);
		}//fin del if	 
		break;
	} //FIN DEL switch

?>
<div align="center">
  <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
   <table width="850" border="0" class="formato-blanco">   
   <td width="850">
   	<div id="demo" class="yui-navset">
	  <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>Datos Básicos del Edificio</em></a></li> 
		<li><a href="#tab2"><em>Descripción del Edificio</em></a></li> 
		<li><a href="#tab3"><em>Linderos y Estudios Legales</em></a></li> 
		<li><a href="#tab3"><em>Avaluos</em></a></li> 			     
      </ul>	   
		<div class="yui-content">
			<div><p><table width="726" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
				<tr>
					<td height="27" colspan="5">
						 <input name="txtcodact" type="hidden" id="txtcodact" value="<?php print $ls_codact ?>">
						 <input name="txtdenact" type="text" class="sin-borde2" id="txtdenact" value="<?php print $ls_nombre; ?>" size="100" readonly>					</td>
				</tr>
				<tr>
			      <td width="151" height="22"><div align="right">Expediente</div></td>
			      <td height="22" colspan="4"><input name="txtexpact" type="text" id="txtexpact" style="text-align:center"
				                    value="<?php print $ls_codact ?>" size="20" maxlength="15" >                  </td>				
				</tr>
				<tr>
			      <td width="151" height="22"><div align="right">Clasificación Funcional</div></td>
				  <td height="22" colspan="4"><label><textarea name="txtclasfun" cols="100" rows="3" onBlur="javascript: ue_validarcomillas(this);"><?php print $ls_clasfun ?></textarea></label></td>				
				</tr>
				<tr>
			      <td width="151" height="22"><div align="right">Dirección</div></td>
				  <td height="22" colspan="4"><label><textarea name="txtdiract" cols="100" rows="3" onBlur="javascript: ue_validarcomillas(this);"><?php print $ls_diract ?></textarea></label></td>				
				</tr>
				<tr>
			      <td width="151" height="22"><div align="right">Area Total del Terreno </div></td>
			      <td height="22" colspan="4"><input name="txtareatot" type="text" id="txtareatot" style="text-align:right" maxlength="19" onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="<?php print $ls_areatot ?>" size="25">
			      <lable>  m2</label>                  </td>				
				</tr>	
				<tr>
			      <td width="151" height="22"><div align="right">Area de la Construcción:<br> 
		          Area cubierta </div></td>
			      <td height="22" colspan="4"><input name="txtareacons" type="text" id="txtareacons" style="text-align:right"
				                    value="<?php print $ls_areacons ?>" size="25" maxlength="19"
									 onKeyPress="return(ue_formatonumero(this,'.',',',event));">
			      <lable>  m2</label> </td>				
				</tr>
				<tr>
			      <td width="151" height="22"><div align="right">N&uacute;mero de Pisos </div></td>
			      <td height="22" colspan="4"><input name="txtnumpiso" type="text" id="txtnumpiso" style="text-align:center"
				                    value="<?php print $ls_numpiso ?>" size="25" maxlength="19"
									 onKeyUp="javascript: ue_validarnumero(this);"> </td>				
				</tr>
				<tr>
			      <td width="151" height="22"><div align="right">Area Total de la Construcción:<br> 
		          total de los pisos </div></td>
			      <td height="22" colspan="4"><input name="txtareatotpiso" type="text" id="txtareatotpiso" 
				                               style="text-align:right"
				                               value="<?php print $ls_areatotpiso ?>" size="25" maxlength="19"
											    onKeyPress="return(ue_formatonumero(this,'.',',',event));">
			      <lable>  m2</label> </td>				
				</tr>	
				<tr>
			      <td width="151" height="22"><div align="right">Area de las anexidades:<br> 
		          jardines, pisos, etc, </div></td>
			      <td height="22" colspan="4"><input name="txtareanex" type="text" id="txtareanex" 
				                               style="text-align:right"
				                               value="<?php print $ls_areanex ?>" size="25" maxlength="5"
											    onKeyPress="return(ue_formatonumero(this,'.',',',event));">
			      <lable>  m2</label> </td>				
				</tr>
				<tr>
				 <td>&nbsp;</td>
			      <td width="203">&nbsp;</td>
				  <td width="62">&nbsp;</td>
				  <td width="125"><a href="javascript: ue_guardar_datos_basicos();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_guardar_datos_basicos();">Guardar  </a></td>
				  <td width="151"><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/eliminar.gif" alt="Cancelar" width="15" height="15" border="0"></a><a href="javascript: ue_cancelar();">Cancelar</a></td>
				  <td width="22"><input name="operacion" type="hidden" id="operacion"></td>			      
				</tr>			    
				</table>
			</p></div>
				<div><p><table width="726" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
				<tr>
					<td height="27" colspan="3">
				  <label class="texto-azul"> Seleccione las estructuras y materiales predominantes</label>
				   <input name="htotal" type="hidden" id="htotal" value="<? print $ai_totrows ?>">
				   <input name="htotal2" type="hidden" id="htotal2" value="<? print $ai_totrows ?>">
				   <input name="hparam1" type="hidden" id="hparam1">				  </td>
				</tr>				  
				 <tr>
					<td height="27" colspan="3">
				    	<a href="javascript: buscar_tipos();">Estructuras Predominantes</a>					</td>				
				</tr>
				
				 <tr>
					<td height="27" colspan="3">
						<?php $io_grid->makegrid($ai_totrows,$lo_title1,$aa_object,600,"Estructuras Predominantes","gridtipest"); ?>				                    </td>
				</tr>	 
				 <tr>
				   <td width="420" height="27">&nbsp;</td>
			       <td width="128"><a href="javascript: ue_guardar_datos_basicos();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_guardar_datos_basicos();">Guardar </a></td>
				   <td width="170"><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/eliminar.gif" alt="Cancelar" width="15" height="15" border="0"></a><a href="javascript: ue_cancelar();">Cancelar</a></td>
				 </tr>			 					
				</table>
				</p></div>
				<div><p><table width="726" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
					<tr>
						<td width="151" height="22"><div align="right">Linderos</div></td>
				  		<td height="22" colspan="6"><label><textarea name="txtlind" cols="100" rows="3" onBlur="javascript: ue_validarcomillas(this);"><? print $ls_lindero?></textarea></label></td>	
					</tr>
					<tr>
						<td width="151" height="22"><div align="right">Estudio Legal de la Propiedad:(Obtener dictamen del Procurador del Estado o del Síndico Procurador Municipal) </div></td>
				  		<td height="22" colspan="6"><label><textarea name="txtestleg" cols="100" rows="3" onBlur="javascript: ue_validarcomillas(this);"><? print $ls_estlegprop ?></textarea></label></td>	
					</tr>
					<tr>
					  <td colspan="2">&nbsp;</td>
				  </tr>
				  <tr>
					 <td colspan="2">
						   <label class="texto-azul"> Valor con que figura en la contabilidad: </label>					 </td>
				  </tr>
				<tr>
			      <td width="151" height="22"><div align="right">Fecha: </div></td>
			      <td height="22" colspan="6"><input name="txtfeccont" type="text" id="txtfeccont" onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ls_feccont ?>" size="17" maxlength="10" datepicker="true" style="text-align:center ">				  </td>				  						
				</tr>
				<td width="151" height="22"><div align="right">Valor de la Adquisición: </div></td>
			      <td height="22" colspan="6"><input name="txtmoncont" type="text" id="txtmoncont"  value="<?php print $ls_moncont ?>" size="17" maxlength="10" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:center ">				  </td>	
				</tr>	
				<tr>
				  <td height="22">&nbsp;</td>
				  <td height="22" colspan="6">&nbsp;</td>
				  </tr>
				<tr><td width="151" height="22">&nbsp;</td>
			      <td height="22" colspan="3">&nbsp;</td>	
				  <td width="126" height="22">&nbsp;</td>
				  <td width="85"><a href="javascript: ue_guardar_datos_basicos();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_guardar_datos_basicos();">Guardar </a></td>
				  <td width="85"><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/eliminar.gif" alt="Cancelar" width="15" height="15" border="0"></a><a href="javascript: ue_cancelar();">Cancelar</a></td>
				</tr>	
				</table>
				</p></div>	
				<div><p><table width="726" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
				<tr>
				<td width="151" height="22"><div align="right">Avaluo: </div></td>
			      <td height="22" colspan="4"><textarea name="txtavaluo" cols="100" rows="10" onBlur="javascript: ue_validarcomillas(this);"><? print $ls_avaluo?></textarea></td>	
				</tr>
				<tr>
				  <td height="22">&nbsp;</td>
				  <td height="22" colspan="4">&nbsp;</td>
				  </tr>
				<tr>
				  <td height="22">&nbsp;</td>
				  <td width="143" height="22">&nbsp;</td>
				  <td width="134" height="22">&nbsp;</td>
				  <td width="136" height="22"><a href="javascript: ue_guardar_datos_basicos();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_guardar_datos_basicos();">Guardar </a></td>
				  <td width="150" height="22"><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/eliminar.gif" alt="Cancelar" width="15" height="15" border="0"></a><a href="javascript: ue_cancelar();">Cancelar</a></td>
				</tr>
					
				</table>
				</p></div>
	 </div>
	 </td>	 
    </table>
	<input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
	<input name="fila" type="hidden" id="fila" value="<?php print $ls_fila;?>">
	 <script>
(function() {
    var tabView = new YAHOO.widget.TabView('demo');		
})();

            </script>
  </form>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones 
function ue_cancelar()
{
	window.close();
}

function ue_guardar_datos_basicos()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	if((li_cambiar==1)||(li_incluir==1))
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_saf_d_inmueble_edificio.php";
		f.submit();
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function buscar_tipos()
{
    f=document.form1;
	total=f.htotal.value; 
	window.open("sigesp_saf_cat_material_edificio.php?total="+total,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=450,left=50,top=50,location=no,resizable=yes");
}

var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums){
if(d.valant != d.value){
	val = d.value
	largo = val.length
	val = val.split(sep)
	val2 = ''
	for(r=0;r<val.length;r++){
		val2 += val[r]	
	}
	if(nums){
		for(z=0;z<val2.length;z++){
			if(isNaN(val2.charAt(z))){
				letra = new RegExp(val2.charAt(z),"g")
				val2 = val2.replace(letra,"")
			}
		}
	}
	val = ''
	val3 = new Array()
	for(s=0; s<pat.length; s++){
		val3[s] = val2.substring(0,pat[s])
		val2 = val2.substr(pat[s])
	}
	for(q=0;q<val3.length; q++){
		if(q ==0){
			val = val3[q]
		}
		else{
			if(val3[q] != ""){
				val += sep + val3[q]
				}
		}
	}
	d.value = val
	d.valant = val
	}
}

function ue_eliminar(fila)
{
	f=document.form1;
	f.fila.value=fila;
	f.operacion.value="ELIMINAR";
	f.action="sigesp_saf_d_inmueble_edificio.php";
	f.submit();
}
</script>
<script language="javascript"  src="../../shared/js/js_intra/datepickercontrol.js"></script>
</html>