<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../../sigesp_inicio_sesion.php'";
	print "</script>";		
}

$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definici&oacute;n de Unidad Ejecutora</title>
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cfg.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
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
<body onLoad="ue_cargargrid()">
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
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div>    </td>
  </tr>
</table>
<?php
require_once("class_folder/sigesp_spg_c_unidad.php");
require_once("../../shared/class_folder/class_funciones.php");
$io_funcion = new class_funciones();

	//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad = new sigesp_c_seguridad();
	$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
	$li_estmodest = $_SESSION["la_empresa"]["estmodest"];
	global  $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla;
	if ($li_estmodest=='1')
	   {
   	     $ls_style       = 'style=visibility:hidden';
	     $ls_nomestpro4  = "";
	     $ls_nomestpro5  = "";
	     $ls_denestpro4  = "";
	     $ls_denestpro5  = "";
	   }

	if(array_key_exists("la_logusr",$_SESSION))
	{
	$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
	$ls_logusr="";
	}
	if (array_key_exists("totrowestpro",$_POST))
	{
		$li_totrowestpro=$_POST["totrowestpro"];
	}else
	{
		$li_totrowestpro=1;
	}
	$ls_sistema     = "CFG";
	$ls_ventanas    = "sigesp_spg_d_unidad.php";
	$arr_seguridad[1] = $ls_codemp;
	$arr_seguridad[2] = $ls_sistema;
	$arr_seguridad[3] = $ls_logusr;
	$arr_seguridad[4] = $ls_ventanas;
	

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
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_codemp,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
	//Inclusión de la clase de seguridad.
	$nvo_estprog=new sigesp_spg_c_unidad($arr_seguridad);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	
	function uf_cargar_dt($li_fila)
	{
		global  $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla;
		if(array_key_exists("txtcodestpro1".$li_fila,$_POST))
		{
			$ls_codestpro1=$_POST["txtcodestpro1".$li_fila];
		}
		if(array_key_exists("txtcodestpro2".$li_fila,$_POST))
		{
			$ls_codestpro2=$_POST["txtcodestpro2".$li_fila];
		}
		if(array_key_exists("txtcodestpro3".$li_fila,$_POST))
		{
			$ls_codestpro3=$_POST["txtcodestpro3".$li_fila];
		}
		if(array_key_exists("txtcodestpro4".$li_fila,$_POST))
		{
			$ls_codestpro4=$_POST["txtcodestpro4".$li_fila];
		}
		else
		{
			$ls_codestpro4="";
		}
		if(array_key_exists("txtcodestpro5".$li_fila,$_POST))
		{
			$ls_codestpro5=$_POST["txtcodestpro5".$li_fila];
		}
		else
		{
			$ls_codestpro5="";
		}
		$ls_estcla=$_POST["txtestcla".$li_fila];
		
		
		
		

	}
	
	require_once("../../shared/class_folder/class_mensajes.php");
	$msg=new class_mensajes();
	$siginc=new sigesp_include();
	$con=$siginc->uf_conectar();
	$ds=null;
   
	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion  = $_POST["operacion"];
		$ls_codunieje = $_POST["txtcodunieje"];
		$ls_denunieje = $_POST["txtdenunieje"];
		$ls_status    = $_POST["status"];		
		if(array_key_exists("estreq",$_POST))
		{
		$ls_estreq=$_POST["estreq"];
		}
		else
		{
		$ls_estreq=0;
		}
		$ls_coduniadm = $_POST["txtcoduniadm"];
	    $ls_denuniadm = "";
    }
	else
	{
		$ls_operacion  = "NUEVO";
		$ls_codestpro1 = "";
		$ls_codestpro2 = "";
		$ls_codestpro3 = "";
		$ls_codestpro4 = "";
		$ls_codestpro5 = "";
	    $ls_denestpro1 = "";
		$ls_denestpro2 = "";
		$ls_denestpro3 = "";
		$ls_denestpro4 = "";
		$ls_denestpro5 = "";
		$ls_codunieje  = "";
		$ls_denunieje  = "";
		$ls_estreq     = "";
		$ls_status     = "N";
		$ls_coduniadm  = "";
		$disabled      = "";
		$ls_denuniadm  = "";
		$ls_estcla1    = "";		
		$ls_estcla2    = "";		
		$ls_estcla3    = "";		
		$ls_estcla4    = "";		
		$ls_estcla5    = "";		
	}
	if($ls_operacion=="GUARDAR")
	{
		if(($ls_codunieje!="")&&($ls_denunieje!=""))
		{
 			$lb_valido = $nvo_estprog->uf_guardar_unidad_adm($ls_codemp,$ls_codunieje,$ls_denunieje,$ls_estreq,$ls_coduniadm,$ls_status);
			if ($lb_valido)
			   {
			     $nvo_estprog->uf_delete_dt_unidad_adm($ls_codemp,$ls_codunieje);
				 for ($i=1;$i<$li_totrowestpro;$i++)
				     {
				  uf_cargar_dt($i);
				  $ls_codestp1 = str_pad(trim($ls_codestpro1),25,0,0);
				  $ls_codestp2 = str_pad(trim($ls_codestpro2),25,0,0);
				  $ls_codestp3 = str_pad(trim($ls_codestpro3),25,0,0);
				  if ($li_estmodest==1)
				     {
					   $ls_codestp4 = str_pad(0,25,0,0);
					   $ls_codestp5 = str_pad(0,25,0,0);
				     }
				  else
				  {
				   	$ls_codestp4 = str_pad($ls_codestpro4,25,0,0);
				  	$ls_codestp5 = str_pad($ls_codestpro5,25,0,0);
				  }
				  
				  $lb_valido= $nvo_estprog->uf_guardar_dt_unidad_adm($ls_codemp,$ls_codunieje,$ls_codestp1,$ls_codestp2,$ls_codestp3,$ls_codestp4,$ls_codestp5,$ls_estcla,$ls_status);
				}
				$msg->message($nvo_estprog->is_msg_error);
				$ls_operacion="NUEVO";
				$disabled="readonly";				
			}
			else
			{
				$msg->message($nvo_estprog->is_msg_error);
				$disabled="";
			}
		}
		else
		{
			$msg->message("Debe completar todos los campos");
			$disabled="";
		}
	}
	elseif($ls_operacion=="BUSCAR")
	{
		$ls_codestpro1 = $_POST["txtcodestpro1"];
		$ls_codestpro2 = $_POST["hicodest2"];
		$ls_codestpro3 = $_POST["hicodest3"];
		$ls_codestpro4 = $_POST["hicodest4"];
		$ls_codestpro5 = $_POST["hicodest5"];
		$disabled   = "readonly";
	}
	elseif($ls_operacion=="ELIMINAR")
	{
		if(($ls_codunieje!="")&&($ls_denunieje!=""))
		{
			$lb_valido=$nvo_estprog->uf_delete_unidad_adm($ls_codunieje,$ls_denunieje,$ls_estreq,$ls_status);
			if($lb_valido)
			{
				$msg->message($nvo_estprog->is_msg_error);
				$ls_codestpro1 = "";
				$ls_codestpro2 = "";
				$ls_codestpro3 = "";
				$ls_codestpro4 = "";
				$ls_codestpro5 = "";
				$ls_codunieje  = "";
				$ls_denunieje  = "";
				$ls_estreq     = "";
				$disabled      = "";
				$ls_operacion  = "NUEVO";
			}
			else
			{
				$msg->message($nvo_estprog->is_msg_error);
				$disabled="";
			}
		}
		else
		{
			$msg->message("Debe completar todos los campos");
		}
	
	}
	if($ls_operacion=="NUEVO")
	{
		$disabled="";
		require_once("../../shared/class_folder/class_funciones_db.php");
		$fundb = new class_funciones_db($con);
		$ls_codunieje = $fundb->uf_generar_codigo(true,$ls_codemp,"spg_unidadadministrativa","coduniadm");
		if($ls_codunieje=="")
		{
			$msg->message($fundb->is_msg_error);
		}
		$ls_coduniadm  = "";
		$ls_denuniadm  = "";
		$ls_denunieje  = "";
		$ls_estreq     = 0;
		$ls_status     = "N";
		$ls_codestpro1 = "";$ls_denestpro1 = "";
		$ls_codestpro2 = "";$ls_denestpro2 = "";
		$ls_codestpro3 = "";$ls_denestpro3 = "";		
	    $ls_codestpro4 = "";$ls_denestpro4 = "";
		$ls_codestpro5 = "";$ls_denestpro5 = "";
		$ls_estcla1    = "";$ls_estcla2    = "";		
		$ls_estcla3    = "";$ls_estcla4    = "";		
		$ls_estcla5    = "";
	}
	else
	{
	$disabled="";
	}
?>
<p>&nbsp;</p>
<div align="center">
  <table width="772" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
<tr>
        <td width="770" height="221" valign="top"><form name="form1" method="post" action="">
<?php 
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=permisos value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=permisos value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=permisos value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=permisos value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=permisos value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=permisos value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=permisos value='$la_accesos[ejecutar]'>");
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
          <table width="718" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="2"> Unidad Ejecutora</td>
              </tr>
              <tr >
                <td height="18">&nbsp;</td>
                <td><input name="hidmaestro" type="hidden" id="hidmaestro" value="N">
                    <input name="estmodest" type="hidden" id="estmodest" value="<?php print $li_estmodest?>"></td>
              </tr>
              <tr>
                <td width="143" height="20"><div align="right" >
                    <p>Codigo</p>
                </div></td>
                <td width="545"><div align="left" >
                  <input name="txtcodunieje" type="text" id="txtcodunieje" style="text-align:center " value="<?php print $ls_codunieje ?>" size="12" maxlength="10" onBlur="javascript:rellenar_cad(this.value,10)" <?php print $disabled ?>  onKeyPress="return keyRestrict(event,'1234567890');" >
</div></td>
              </tr>
              <tr >
                <td height="20"><div align="right">Denominaci&oacute;n</div></td>
                <td><div align="left">
                  <input name="txtdenunieje" type="text" id="txtdenunieje" style="text-align:left" value="<?php print $ls_denunieje ?>" size="85" maxlength="85"  >
                </div></td>
              </tr>
              <tr>
                <td height="20"><div align="right">Emite Requisici&oacute;n </div></td>
                <td height="20"><div align="left">
                  <?php
				  if($ls_estreq==1)
				  {
				  ?>
				  	<input name="estreq" type="checkbox" id="estreq" value="1" checked>
				  <?php
				  }
				  else
				  {
				  ?>
				 	 <input name="estreq" type="checkbox" id="estreq" value="1">
				  <?php
				  }
				  ?>
                </div></td>
              </tr>
              <tr>
                
			</tr>			 
			 <?  
			  // }
			?>
            <tr>
              <td height="18"><div align="right"> Unidad  Administradora </div></td>
              <td><div align="left">
                <input name="txtcoduniadm" type="text" id="txtcoduniadm" style="text-align:center" value="<?php print $ls_coduniadm ?>" size="22" maxlength="5" >
                <a href="javascript:catalogo_unidad_administradora();"><img src="../../shared/imagebank/tools15/buscar.gif" alt="Buscar..." width="15" height="15" border="0"></a> 
                <label>
                <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadm ?>" size="50" maxlength="50"><input name="totrowestpro"  type="hidden" id="totrowestpro"     value="" readonly>
                </label>
              </div></td>
            </tr>
            <tr>
              <td height="18" colspan="2"><div id="estructuras" align="center"></div></td>
            </tr>
            <tr>
              <td height="18" colspan="2">&nbsp;</td>
            </tr>
          </table>
          
          <p>&nbsp;</p>
            <p align="center">
            <input name="operacion" type="hidden" id="operacion">
            <input name="status" type="hidden" id="status" value="<?php print $ls_status; ?>">
          </p>
        </form></td>
    </tr>
  </table>
</div>
</body>
<script language="javascript">
f = document.form1;
function cat()
{
	f.txtcuenta.readonly=false;
	f.operacion.value="CAT";
	window.open("sigesp_catdinamic_ctaspu.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_nuevo()
{
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
	   f.operacion.value ="NUEVO";
	   f.action="sigesp_spg_d_unidad.php";
	   f.submit();
	 }
  else
     {
 	   alert("No tiene permiso para realizar esta operación");
	 }	 
}

function ue_guardar()
{
li_incluir = f.incluir.value;
li_cambiar = f.cambiar.value;
lb_status  = f.status.value;
totrow=ue_calcular_total_fila_local("txtcodestpro1");
f.totrowestpro.value=totrow;
if (((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
   {
     
		 if(f.txtcodestpro11.value=="")
		 {
			alert("Debe agregar por lo menos una Estructura Presupuestaria");
		 }else
		 {
		 f.operacion.value ="GUARDAR";
		 f.action="sigesp_spg_d_unidad.php";
		 f.submit();
		 }
   }
else
   {
     alert("No tiene permiso para realizar esta operación");
   }	
}

function ue_eliminar()
{
li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
     borrar=confirm("¿ Esta seguro de eliminar este registro ?");
	 if (borrar==true)
		{ 
	      f.operacion.value ="ELIMINAR";
	      f.action="sigesp_spg_d_unidad.php";
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
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
 	     window.open("sigesp_spg_cat_unidad.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=630,height=450,left=50,top=50,location=no,resizable=yes");
       }
    else
	   {
         alert("No tiene permiso para realizar esta operación");
	   }
}

function ue_cerrar()
{
	f.action="sigespwindow_blank.php";
	f.submit();
}

function rellenar_cad(cadena,longitud)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;

	if (cadena!='')
	   {
	     li_total = longitud-lencad;   
		 for (i=1;i<=li_total;i++)
		     {
			   cadena_ceros=cadena_ceros+"0";
		     }
		 cadena=cadena_ceros+cadena;
		 document.form1.txtcodunieje.value=cadena;
	   }
}
	
function catalogo_unidad_administradora()
{
	pagina="sigesp_spg_cat_uniadm.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}


function catalogo_estpro()
{
	ls_codestpro1 = "";
	ls_denestpro1 = "";
	ls_codestpro2 = "";
	ls_denestpro2 = "";
	ls_codestpro3 = "";
	ls_estcla ='P';	
	li_estmodest = "<?php print $li_estmodest ?>";
    pagina="sigesp_cat_public_estpro.php?tipo=grid";
    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=630,height=450,resizable=yes,location=no");
}

function ue_cargargrid()
{
		f.totrowestpro.value=1;
		// Div donde se van a cargar los resultados
		divgrid = document.getElementById('estructuras');
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_spg_c_unidad_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("&totalbienes=1&proceso=LIMPIAR&operacion=imprimir_grid");	
}

function ue_delete_estructura(fila)
{
  f = document.form1;
  if (confirm("¿Desea eliminar el Registro actual?"))
	 {
	   valido      = true;
	   parametros  = "";
	   montobien   = 0;
	   montocargo  = 0;
	   cuentacargo = "";
	   codigo      = "";
	   cuentabien  = "";
	   ls_estmodest=f.estmodest.value;
	   
	   //---------------------------------------------------------------------------------
	   // Cargar las estructura presupuestaria y eliminar el seleccionado
	   //---------------------------------------------------------------------------------
	   // Obtenemos el total de filas de las estructuras presupuestarias
	   rowestpro = ue_calcular_total_fila_local("txtcodestpro1");
	   f.totrowestpro.value = rowestpro;
	   li_i=0;
	   for (j=1;(j<rowestpro)&&(valido);j++) 
		   {
		     if (j!=fila)
				{
				  li_i++;
				  estcla       = eval("document.form1.txtestcla"+j+".value");
				  codestpro1   = eval("document.form1.txtcodestpro1"+j+".value");
				  codestpro2   = eval("document.form1.txtcodestpro2"+j+".value");
				  codestpro3   = eval("document.form1.txtcodestpro3"+j+".value");
				  dencodestpro = eval("document.form1.txtdenominacion"+j+".value");				  
				  if (ls_estmodest==2)
					 {
					   codestpro4   = eval("document.form1.txtcodestpro4"+j+".value");
					   codestpro5   = eval("document.form1.txtcodestpro5"+j+".value");
					   
					   parametros   = parametros+"&txtcodestpro1"+li_i+"="+codestpro1+"&txtcodestpro2"+li_i+"="+codestpro2+""+
							                     "&txtcodestpro3"+li_i+"="+codestpro3+"&txtcodestpro4"+li_i+"="+codestpro4+""+
							                     "&txtcodestpro5"+li_i+"="+codestpro5+""+"&txtdenominacion"+li_i+"="+dencodestpro+""+
							                     "&txtestcla"+li_i+"="+estcla;	
					 }
				  else
					 {
					   parametros   = parametros+"&txtcodestpro1"+li_i+"="+codestpro1+"&txtcodestpro2"+li_i+"="+codestpro2+""+
							                     "&txtcodestpro3"+li_i+"="+codestpro3+""+"&txtdenominacion"+li_i+"="+dencodestpro+""+
							                     "&txtestcla"+li_i+"="+estcla;
					 }
				}				
		   }
	   li_i++;
	   parametros=parametros+"&totrowestpro="+li_i+"";
	   f.totrowestpro.value=li_i;
	   if ((parametros!="")&&(valido))
		  {
		    divgrid = document.getElementById("estructuras");
		    ajax=objetoAjax();
			ajax.open("POST","class_folder/sigesp_spg_c_unidad_ajax.php",true);
		    ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
						divgrid.innerHTML = ajax.responseText
			   }
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.send("proceso=AGREGARESTPRO"+parametros);
		  }
	 } 
}

function ue_calcular_total_fila_local(campo)
{
	existe=true;
	li_i=1;
	while(existe)
	{
		existe=document.getElementById(campo+li_i);
		if(existe!=null)
		{
			li_i=li_i+1;
		}
		else
		{
			existe=false;
			li_i=li_i-1;
		}
	}
	return li_i;
}

</script>
</html>