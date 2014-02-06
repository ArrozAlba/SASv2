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
require_once("class_funciones_seguridad.php");
$io_fun_seguridad=new class_funciones_seguridad();
$io_fun_seguridad->uf_load_seguridad("SSS","sigespwindow_sss_auditoria.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	function uf_validar_fecha ($ld_desde,$ld_hasta)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	   Function:  uf_validar_fecha
	//	     Access:    public
	//  	 Return:    $lb_fechavalida:  true  -> las fechas son correctas
	//								      false -> las fechas son incorrectas
	//	Description:  Funcion que valida que al tener dos fechas (un periodo de tiempo)
	//                la fecha que inicia el periodo no sea mayor a la fecha que cierra el 
	//                periodo; es decir que lasfechas no esten solapadas.
	//////////////////////////////////////////////////////////////////////////////		
		$lb_fechavalida=false;
		$io_msg= new class_mensajes();
		
		if(($ld_desde=="")and($ld_hasta==""))
		{
			$lb_fechavalida=false;
		}
		else
		{
			$ld_diad= substr($ld_desde,0,2);
			$ld_mesd= substr($ld_desde,3,2);
			$ld_anod= substr($ld_desde,6,4);
			$ld_diah= substr($ld_hasta,0,2);
			$ld_mesh= substr($ld_hasta,3,2);
			$ld_anoh= substr($ld_hasta,6,4);
			
			if($ld_anod<$ld_anoh)
			{$lb_fechavalida=true;}
			elseif($ld_anod==$ld_anoh)
			{
				if($ld_mesd<$ld_mesh)
				{$lb_fechavalida=true;}
				elseif($ld_mesd==$ld_mesh)
				{
					if($ld_diad<=$ld_diah)
					{$lb_fechavalida=true;}
				}
			}
			if($lb_fechavalida==false)
			{
				$io_msg->message("El rango de fechas es invalido");
			}
		}
		return $lb_fechavalida;
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Proceso de Auditor&iacute;a </title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<style type="text/css">
<!--
.Estilo1 {font-size: 12px}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
      <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Seguridad</td>
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
    <td height="13" colspan="11" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones.php");
	$io_fun= new class_funciones();
	require_once("sigesp_sss_c_auditoria.php");
	$io_sss= new sigesp_sss_c_auditoria();
	require_once("../shared/class_folder/sigesp_include.php");
	$in=      new sigesp_include();
	$con=     $in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=  new class_sql($con);
		
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$li_pos=0;
	$ls_codusu="";
	$ld_datedesde="";
	$ld_datehasta="";
	$ld_desde="";
	$ld_hasta="";
	$ls_documento="";

	$io_sss->uf_sss_llenar_combo_sistemas($la_sistema);
	$io_sss->uf_sss_llenar_combo_eventos($la_evento);
	$ls_codsis=$io_fun_seguridad->uf_obtenervalor("cmbsistemas","Todos");
	$ls_evento=$io_fun_seguridad->uf_obtenervalor("cmbevento","Todos");
	$ls_documento=$io_fun_seguridad->uf_obtenervalor("txtdocumento","");
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_usuaux=$_POST["txtcodigo"];
	}
	else
	{
		
		$ls_codusu="";
		$ls_usuaux="";
		$ls_operacion=$io_fun_seguridad->uf_obtenervalor_get("operacion","");
		if($ls_operacion=="BUSCAR")
		{
			$ls_codsis=$io_fun_seguridad->uf_obtenervalor_get("codsis","");
			$ls_codusu=$io_fun_seguridad->uf_obtenervalor_get("codusu","");
			$ls_evento=$io_fun_seguridad->uf_obtenervalor_get("evento","");
			$ld_datedesde=$io_fun_seguridad->uf_obtenervalor_get("desde","");
			$ld_datehasta=$io_fun_seguridad->uf_obtenervalor_get("hasta","");
			$ls_documento=$io_fun_seguridad->uf_obtenervalor_get("documento","");
			$ls_documentoaux="%".$ls_documento."%";
			if($ls_codusu!="")
			{
			//	$ls_usuaux=$ls_codusu;
				$ls_codusu=str_replace("%","",$ls_codusu);
			}
		}
		else
		{
			$ls_codusu="";
			$ls_usuaux="";
		}
	}
	if ($ls_operacion=="BUSCAR")
	{
		$ld_desde=$io_fun_seguridad->uf_obtenervalor("txtdesde","");
		$ld_hasta=$io_fun_seguridad->uf_obtenervalor("txthasta","");
		if($ls_codusu=="")
		{
			$ls_codusu=$io_fun_seguridad->uf_obtenervalor("txtcodigo","");
		}
		$ls_codusu="%".$ls_codusu."%";
		$ls_documentoaux="%".$ls_documento."%";
		if($ls_codsis=="Todos")
		{
			$ls_codsis="%%";
		}
		if($ls_evento=="Todos")
		{
			$ls_evento="%%";
		}
        ///////////////// PAGINACION   /////////////////////
		$li_registros = 30;
		$li_pagina=$io_fun_seguridad->uf_obtenervalor_get("pagina",0);
		if (!$li_pagina) { 
			$li_inicio = 0; 
			$li_pagina = 1; 
		} 
		else { 
			$li_inicio = ($li_pagina - 1) * $li_registros; 
		} 
        ///////////////// PAGINACION   /////////////////////
	}
	elseif ($ls_operacion=="NUEVO")
	{
		$ls_desde="dd/mm/aaaa";
		$ls_hasta="dd/mm/aaaa";
		$ls_usuario="";
	}
 
?>

<p>&nbsp;</p>
<div align="center">
          <form name="form1" method="post" action="">

<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_seguridad->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_seguridad);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
            <p>&nbsp;</p>
            <table width="793" height="132" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td width="791" height="17" class="titulo-ventana">Proceso de Auditor&iacute;a </td>
              </tr>
              <tr class="formato-blanco">
                <td height="89" class="formato-blanco"><div align="right"></div>                  <div align="right"></div>                  <div align="left">
                  </div>                  
                <div align="right"></div>                <span class="titulo-celdanew">
                </span>                 <div align="right"></div>                <div align="right"></div>                
                <div align="center">
                  <table width="371" height="110" border="0" cellpadding="0" cellspacing="0" class="celdas-azules">
                    <tr>
                      <td width="38">Usuario</td>
                      <td height="22" colspan="3">                        <div align="left">
                          <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_usuaux?>" onKeyUp="javascript: ue_validarcomillas(this);">                        
                        <a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>                          </div>                        
                      <div align="left"></div></td>
                      <td width="124"><span class="titulo-celdanew">
                        <input name="hidsistema" type="hidden" id="hidsistema">
                        <input name="operacion" type="hidden" id="operacion">
                        </span>
                    </tr>
                    <tr>
                      <td>Sistema</td>
                      <td width="124" height="22" align="left"><?php $io_sss->uf_sss_pintar_combo_sistemas($la_sistema,$ls_codsis);?></td>
                      <td width="24">&nbsp;</td>
                      <td width="61"><div align="right"></div></td>
                      <td rowspan="2"><table width="100" border="0" class="celdas-azules">
                        <tr>
                          <td>Desde</td>
                          <td><input name="txtdesde" type="text" id="txtdesde" value="<?php print $ld_desde?>" size="15" maxlength="10" onKeyPress="ue_solo_numeros(this,'/',patron,true)" datepicker="true"></td>
                        </tr>
                        <tr>
                          <td>Hasta</td>
                          <td><input name="txthasta" type="text" id="txthasta" value="<?php print $ld_hasta?>" size="15" maxlength="10" onKeyPress="ue_solo_numeros(this,'/',patron,true)" datepicker="true"></td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td>Evento</td>
                      <td height="22" align="left"><?php $io_sss->uf_sss_pintar_combo_eventos($la_evento,$ls_evento);?></td>
                      <td>&nbsp;</td>
                      <td><div align="right"></div></td>
                    </tr>
                    <tr>
                      <td height="22" colspan="5">Nro. Documento
                        <input name="txtdocumento" type="text" id="txtdocumento" value="<?php print $ls_documento; ?>" size="30" maxlength="25" onKeyUp="javascript: ue_validarcomillas(this);"></td>
                    </tr>
                    <tr>
                      <td height="22" colspan="5"><div align="right">
                      </div>                        <div align="center">
                        <input name="btnaceptar" type="button" class="boton" id="btnaceptar" onClick="ue_aceptar()" value="Aceptar">
                        </div></td>
                    </tr>
                  </table>
                  <span class="titulo-celdanew">
                </span> </div></td>
              </tr>
              <tr>
                <td height="18">
				  <?php
				  	$lb_fecha=true;
					if($ls_operacion=="BUSCAR")
					{	
						if((($ld_desde!="")&&($ld_hasta!=""))&&(($ld_datedesde=="")&&($ld_datehasta=="")))
						{
							$lb_valido=uf_validar_fecha($ld_desde,$ld_hasta);
							if($lb_valido)
							{
								$ld_datedesde=$io_fun->uf_convertirdatetobd($ld_desde);
								$ld_datehasta=$io_fun->uf_convertirdatetobd($ld_hasta);
							}
							else
							{
								$ld_datedesde="";
								$ld_datehasta="";
								$lb_fecha=false;
							}
						 }
//						 else
//						 {
//							$ld_datedesde="";
//							$ld_datehasta="";
//						 }
						 if($lb_fecha)
						 {
							$lb_valido=$io_sss->uf_sss_obterer_total_registros($ls_codemp,$ls_codusu,$ls_codsis,$ls_evento,$ld_datedesde,$ld_datehasta,$ls_documentoaux,$li_totrows);
							$li_totpag = ceil($li_totrows / $li_registros); 
							if($lb_valido)
							{
								$io_sss->uf_sss_select_registro_eventos($ls_codemp,$ls_codusu,$ls_codsis,$ls_evento,$ld_datedesde,
																		$ld_datehasta,$li_inicio,$li_registros,$ls_documentoaux,$rs_data);
								if($lb_valido)
								{
									print "<table width=780 height=33 border=0 cellpadding=0 cellspacing=0>";
									print "<tr class=titulo-celdanew>";
									print "  <td width=46 height=20>Sistema</td>";
									print "  <td width=49>Usuario</td>";
									print "  <td width=39>Evento</td>";
									print "  <td width=174>Ventana</td>";
									print "  <td width=66 align='center'>Fecha/Hora</td>";
									print "  <td width=37>Equipo</td>";
									print "  <td width=231>Descripci&oacute;n del evento </td>";
									print "</tr>";
									$li_pos=0;
									while($row=$io_sql->fetch_row($rs_data))
									{
										$li_pos=$li_pos+1;
										$la_eventos["codsis"][$li_pos]=$row["codsis"];   
										$la_eventos["codusu"][$li_pos]=$row["codusu"];   
										$la_eventos["evento"][$li_pos]=$row["evento"];   
										$la_eventos["titven"][$li_pos]=$row["titven"];   
										$la_eventos["fecevetra"][$li_pos]=$row["fecevetra"];   
										$la_eventos["equevetra"][$li_pos]=$row["equevetra"];   
										$la_eventos["desevetra"][$li_pos]=$row["desevetra"];   										
										$la_eventos["fecevetra"][$li_pos]=date("d/m/Y H:i",strtotime($la_eventos["fecevetra"][$li_pos]));
										if(($li_pos%2!=0))
										{
											$ls_color="class=celdas-blancas";
										}
										else
										{
											$ls_color="class=celdas-azules";
										}				
										print("<tr ".$ls_color.">");
										print("<td align='center'>".$la_eventos["codsis"][$li_pos]."</td>");
										print("<td>".$la_eventos["codusu"][$li_pos]."</td>");
										print("<td>".$la_eventos["evento"][$li_pos]."</td>");
										print("<td>"."<b> ".$la_eventos["titven"][$li_pos]."</b> "."</td>");
										print("<td align='center'>".$la_eventos["fecevetra"][$li_pos]."</td>");
										print("<td>".$la_eventos["equevetra"][$li_pos]."</td>");
										print("<td>".$la_eventos["desevetra"][$li_pos]."</td>");
										print("</tr>");
									}
									$ls_usuaux=str_replace("%","",$ls_codusu);
									if($li_totrows)
									{
										print "<center>";
										$ls_operacion="BUSCAR";
										if(($li_pagina - 1) > 0) 
										{
											print "<a href='sigesp_sss_r_auditoria.php?pagina=".($li_pagina-1)."&operacion=".$ls_operacion."&codsis=".$ls_codsis."&codusu=".$ls_codusu."&desde=".$ld_datedesde."&hasta=".$ld_datehasta."&evento=".$ls_evento."&documento=".$ls_documento."'>< Anterior</a> ";
										}
										
										for ($li_i=1; $li_i<=$li_totpag; $li_i++)
										{ 
											if ($li_pagina == $li_i) 
												print "<b>".$li_pagina."</b> "; 
											else
												print "<a href='sigesp_sss_r_auditoria.php?pagina=$li_i&operacion=".$ls_operacion."&codsis=".$ls_codsis."&codusu=".$ls_codusu."&desde=".$ld_datedesde."&hasta=".$ld_datehasta."&evento=".$ls_evento."&documento=".$ls_documento."'>$li_i</a> "; 
										}
									  
										if(($li_pagina + 1)<=$li_totpag) 
										{
											print " <a href='sigesp_sss_r_auditoria.php?pagina=".($li_pagina+1)."&operacion=".$ls_operacion."&codsis=".$ls_codsis."&codusu=".$ls_codusu."&desde=".$ld_datedesde."&hasta=".$ld_datehasta."&evento=".$ls_evento."&documento=".$ls_documento."'>Siguiente ></a>";
										}
										
										print "</center>";
									}
									print "</table> ";
									if($li_totrows) 
									{
										print "<center>";
										$ls_operacion="BUSCAR";
										if(($li_pagina - 1) > 0) 
										{
											print "<a href='sigesp_sss_r_auditoria.php?pagina=".($li_pagina-1)."&operacion=".$ls_operacion."&codsis=".$ls_codsis."&codusu=".$ls_codusu."&desde=".$ld_datedesde."&hasta=".$ld_datehasta."&evento=".$ls_evento."&documento=".$ls_documento."'>< Anterior</a> ";
										}
										
										for ($li_i=1; $li_i<=$li_totpag; $li_i++)
										{ 
											if ($li_pagina == $li_i) 
												print "<b>".$li_pagina."</b> "; 
											else
												print "<a href='sigesp_sss_r_auditoria.php?pagina=$li_i&operacion=".$ls_operacion."&codsis=".$ls_codsis."&codusu=".$ls_codusu."&desde=".$ld_datedesde."&hasta=".$ld_datehasta."&evento=".$ls_evento."&documento=".$ls_documento."'>$li_i</a> "; 
										}
									  
										if(($li_pagina + 1)<=$li_totpag) 
										{
											print " <a href='sigesp_sss_r_auditoria.php?pagina=".($li_pagina+1)."&operacion=".$ls_operacion."&codsis=".$ls_codsis."&codusu=".$ls_codusu."&desde=".$ld_datedesde."&hasta=".$ld_datehasta."&evento=".$ls_evento."&documento=".$ls_documento."'>Siguiente ></a>";
										}
										print "</center>";
									}
								}// fin if($lb_valido) uf_sss_select_registro_eventos
							}
						 }
					}
								
				  ?>
                                
                </td>
              </tr>
              <tr class="formato-blanco">
              </tr>
            </table>
          </form>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="sigespwindow_sss_auditoria.php";
	f.submit();
}
function ue_guardar()
{
}
function ue_aceptar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		lb_valido=true;
		if(f.cmbsistemas.value=="---")
		{
			alert("Debe seleccionar un sistema");
			lb_valido=false;
		}
		else
		{
			if(f.cmbevento.value=="---")
			{
				alert("Debe seleccionar un evento");
				lb_valido=false;
			}
			if((f.txtdesde!="")&&(f.txthasta!=""))
			{
				lb_valido=ue_comparar_intervalo();
			}
		}
		if (lb_valido)
		{
			f.operacion.value="BUSCAR";
			f.action="sigesp_sss_r_auditoria.php";
			f.submit();
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_seleccionar()
{
	f=document.form1;
	var selectedItem = document.getElementById("cmbsistema").selectedIndex;
	var selectedText = document.getElementById("cmbsistema").options[selectedItem].text;
	var selectedValue = document.getElementById("cmbsistema").options[selectedItem].value;

	f.hidsistema.value=selectedText;	
}
function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

function ue_buscar()
{
    destino="Auditoria";
	window.open("sigesp_sss_cat_usuarios.php?destino="+destino,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_limpiar(periodo)
{
	f=document.form1;
	if(periodo=="Desde")
	{
		f.txtdesde.value="";
	}
	else
	{
		if(periodo=="Hasta")
		{
			f.txthasta.value="";
		}
	}
	
}

function ue_imprimir()
{
	f=document.form1;
	ls_codigo=f.txtcodigo.value;
	ld_fecdes=f.txtdesde.value;
	ld_fechas=f.txthasta.value;
	ls_sistema=f.cmbsistemas.value;
	ls_evento=f.cmbevento.value;
	var valido = false; 
    var diad = f.txtdesde.value.substr(0, 2); 
    var mesd = f.txtdesde.value.substr(3, 2); 
    var anod = f.txtdesde.value.substr(6, 4); 
    var diah = f.txthasta.value.substr(0, 2); 
    var mesh = f.txthasta.value.substr(3, 2); 
    var anoh = f.txthasta.value.substr(6, 4); 
	if((anod!=anoh)||(mesd!=mesh)||(diad!=diah))
	{
		lb_valido=false;
		alert("Seleccione solo un (1) dia a imprimir");
	}
	else
	{	
		if((ld_fecdes!="")&&(ld_fechas!=""))
		{
			window.open("reportes/sigesp_sss_rpp_auditoria1.php?codigo="+ls_codigo+"&evento="+ls_evento+"&sistema="+ls_sistema+"&fecdes="+ld_fecdes+"&fechas="+ld_fechas+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=50,top=50,location=no,resizable=yes");
		}
		else
		{
			alert("Seleccione solo un (1) dia a imprimir");
		}
	}

}
////////////////////////    Validar la Fecha     ///////////////////////////
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
		  oTxt.value = "01/01/2005"; 
		  oTxt.focus(); 
		 } 
		}
	 
   }

  function esDigito(sChr){ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
   }
////////////////////////    Validar la Fecha     ///////////////////////////
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_solo_numeros(d,sep,pat,nums){
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
//--------------------------------------------------------
//	Función que valida que un intervalo de tiempo sea valido
//--------------------------------------------------------
   function ue_comparar_intervalo()
   { 

	f=document.form1;
   	ld_desde="f.txtdesde";
   	ld_hasta="f.txthasta";
	var valido = false; 
    var diad = f.txtdesde.value.substr(0, 2); 
    var mesd = f.txtdesde.value.substr(3, 2); 
    var anod = f.txtdesde.value.substr(6, 4); 
    var diah = f.txthasta.value.substr(0, 2); 
    var mesh = f.txthasta.value.substr(3, 2); 
    var anoh = f.txthasta.value.substr(6, 4); 
    
	if (anod < anoh)
	{
		 valido = true; 
	 }
    else 
	{ 
     if (anod == anoh)
	 { 
      if (mesd < mesh)
	  {
	   valido = true; 
	  }
      else 
	  { 
       if (mesd == mesh)
	   {
 		if (diad <= diah)
		{
		 valido = true; 
		}
	   }
      } 
     } 
    } 
    if (valido==false)
	{
		alert("El rango de fecha es invalido");
		f.txtdesde.value="";
		f.txthasta.value="";
		
	} 
	return valido;
   } 

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>