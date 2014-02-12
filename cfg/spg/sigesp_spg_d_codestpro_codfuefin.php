<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
     echo "<script language=JavaScript>";
     echo "location.href='../sigesp_inicio_sesion.php'";
     echo "</script>";		
   }
require_once("../class_folder/class_funciones_cfg.php");
$io_fun_cfg = new class_funciones_cfg();
$io_fun_cfg->uf_load_seguridad("CFG","sigesp_spg_d_codestpro_codfuefin.php",$ls_permisos,&$la_seguridad,$la_permisos);

$ls_logusr = $_SESSION["la_logusr"];
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}

  //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 22/12/2008.			Fecha Última Modificación : 22/12/2008. 
		///////////////////////////////////////////////////////////////////////////////
   		global $ls_estcla,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
			   $ls_denestpro1,$ls_denestpro2,$ls_denestpro3,$ls_denestpro4,$ls_denestpro5,$ls_operacion,
			   $ls_existe,$ls_parametros,$io_fun_cfg,$li_loncodestpro1,$li_loncodestpro2,$li_loncodestpro3,
			   $li_loncodestpro4,$li_loncodestpro5,$li_estmodest,$li_totrows;
		
		$ls_estcla     = "";
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
		if (isset($_SESSION["la_empresa"]))
		   {
		     $li_estmodest     = $_SESSION["la_empresa"]["estmodest"];
			 $li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
			 $li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
			 $li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
			 $li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
			 $li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		   }
		$ls_operacion  = $io_fun_cfg->uf_obteneroperacion();
		$ls_existe     = $io_fun_cfg->uf_obtenerexiste();	
		$ls_parametros = ""; 
		$li_totrows = 0;
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		/////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 24/12/2008			Fecha Última Modificación : 24/12/2008
		/////////////////////////////////////////////////////////////////////////////////
        global $ls_estcla,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_operacion,
		       $ls_denestpro1,$ls_denestpro2,$ls_denestpro3,$ls_denestpro4,$ls_denestpro5,$li_totrows;
		
		$ls_estcla     = $_POST["hidestcla"];
		$li_totrows    = $_POST["totrows"];
		$ls_codestpro1 = $_POST["txtcodestpro1"];
		$ls_codestpro2 = $_POST["txtcodestpro2"];
		$ls_codestpro3 = $_POST["txtcodestpro3"];
		$ls_denestpro1 = $_POST["txtdenestpro1"];
		$ls_denestpro2 = $_POST["txtdenestpro2"];
		$ls_denestpro3 = $_POST["txtdenestpro3"];
		if (isset($_SESSION["la_empresa"]))
		   {
		     if ($_SESSION["la_empresa"]["estmodest"]==2)
			    {
				  $ls_codestpro4 = $_POST["txtcodestpro4"];
				  $ls_codestpro5 = $_POST["txtcodestpro5"];
				  $ls_denestpro4 = $_POST["txtdenestpro4"];
				  $ls_denestpro5 = $_POST["txtdenestpro5"];				
				}
		     else
			    {
				  $ls_codestpro4 = $ls_codestpro5 = str_pad('',25,'0',0);
				  $ls_denestpro4 = $ls_denestpro5 = "";				
				}
		   }
        $li_totrows   = $_POST["totrows"];
		$ls_operacion = $_POST["operacion"];
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_data(&$as_parametros)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 22/12/2008								Fecha Última Modificación : 22/12/2008.
		//////////////////////////////////////////////////////////////////////////////
   		global $li_totrows,$ls_operacion;
			
		for ($li_i=1;$li_i<=$li_totrows;$li_i++)
		    {
			  $ls_codfuefin = $_POST["txtcodfuefin".$li_i];
			  if (!empty($ls_codfuefin))
			     {
				   $ls_denfuefin = $_POST["txtdenfuefin".$li_i];
				   $lb_exifuefin = $_POST["hidexiste".$li_i];
			       $as_parametros=$as_parametros."&txtcodfuefin".$li_i."=".$ls_codfuefin."&txtdenfuefin".$li_i."=".$ls_denfuefin."&hidexiste".$li_i."=".$lb_exifuefin;
		           if ($ls_operacion=='GUARDAR')
			          {
				        $as_parametros=$as_parametros."&hidexiste".$li_i."=true";
				      }
				 }
			}
		$as_parametros=$as_parametros."&totrows=".$li_totrows."";
   }
   //--------------------------------------------------------------

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Casamiento Estructura Presupuestaria - Fuentes de Financiamiento</title>
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/general.css"  rel="stylesheet" type="text/css" />
<link href="../../shared/css/tablas.css"   rel="stylesheet" type="text/css" />
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
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
<body onLoad="writetostatus('<?php print "Base de Datos: ".$_SESSION["ls_database"].". Usuario: ".$_SESSION["la_logusr"];?>')">
<?php
require_once("class_folder/sigesp_spg_c_codestpro_codfuefin.php");
$io_spg = new sigesp_spg_c_codestpro_codfuefin("../../");
uf_limpiarvariables();
switch($ls_operacion){
  case "GUARDAR":
    uf_load_variables();
	$lb_valido = $io_spg->uf_insert_codestpro_codfuefin($ls_existe,$li_totrows,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
	                                                    $ls_codestpro4,$ls_codestpro5,$ls_estcla,$la_seguridad);
	uf_load_data(&$ls_parametros);
	if ($lb_valido)
	   {
	     $ls_existe = "TRUE";
	   }
  break;
  case "DELETE":
    uf_load_variables();
	uf_load_data(&$ls_parametros);
  break;
}
?>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" alt="Encabezado" width="778" height="40" /></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuraci&oacute;n</td>
        <td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
      </tr>
      <tr>
        <td height="20" bgcolor="#E7E7E7">&nbsp;</td>
        <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:uf_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0" /></a><a href="javascript:ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Guardar" width="20" height="20" border="0" /></a><a href="javascript:ue_eliminar();"></a><a href="sigespwindow_blank.php"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0" /></a></td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="formulario" method="post" action="" id="sigesp_spg_d_codestpro_codfuefin.php">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cfg->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cfg);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="650" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="22" colspan="6" class="titulo-celda"><input name="existe" type="hidden" id="existe" value="<?php echo $ls_existe ?>" />
      <input name="operacion" type="hidden" id="operacion" value="<?php echo $ls_operacion; ?>" />
      Casamiento Estructura Presupuestaria - Fuentes de Financiamiento
      <input name="parametros" type="hidden" id="parametros" value="<?php echo $ls_parametros ?>" />
      <input name="hidestcla"  type="hidden" id="hidestcla"  value="<?php echo $ls_estcla; ?>" />
      <input name="totrows" type="hidden" id="totrows" value="<?php echo $li_totrows ?>" />
      <input name="rowdel" type="hidden" id="rowdel" /></td>
    </tr>
    <tr>
      <td height="13" colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td width="128" height="22" style="text-align:right"><?php print $_SESSION["la_empresa"]["nomestpro1"];  ?></td>
      <td width="520" height="22" colspan="5"><input name="txtcodestpro1" type="text" id="txtcodestpro1" style="text-align:center" value="<?php print $ls_codestpro1;?>" size="<?php print $li_loncodestpro1+2 ?>" maxlength="<?php print $li_loncodestpro1 ?>" readonly="readonly" /> 
        <a href="javascript:catalogo_estpro1();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1" /></a> <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php print $ls_denestpro1;?>" size="65" readonly="readonly" /></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right"><?php print $_SESSION["la_empresa"]["nomestpro2"];  ?></td>
      <td height="22" colspan="5"><input name="txtcodestpro2" type="text" id="txtcodestpro2" style="text-align:center" value="<?php print $ls_codestpro2;?>" size="<?php print $li_loncodestpro2+2 ?>" maxlength="<?php print $li_loncodestpro2 ?>" readonly="readonly" />
      <a href="javascript:catalogo_estpro2();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2" /></a> 
      <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" value="<?php print $ls_denestpro2;?>" size="65" readonly="readonly" />      </td>
    </tr>
    <tr>
      <td height="22" style="text-align:right"><?php print $_SESSION["la_empresa"]["nomestpro3"];  ?></td>
      <td height="22" colspan="5"><input name="txtcodestpro3" type="text" id="txtcodestpro3" style="text-align:center" value="<?php print $ls_codestpro3;?>" size="<?php print $li_loncodestpro3+2 ?>" maxlength="<?php print $li_loncodestpro3 ?>" readonly="readonly" onblur="" />
      <a href="javascript:catalogo_estpro3();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3" /></a>
      <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" value="<?php print $ls_denestpro3;?>" size="65" readonly="readonly" />      </td>
    </tr>
    <?php
	if ($li_estmodest=='2')
	   {
	?>
	<tr>
      <td height="22" style="text-align:right"><?php print $_SESSION["la_empresa"]["nomestpro4"];  ?></td>
      <td height="22" colspan="5"><input name="txtcodestpro4" type="text" id="txtcodestpro4" value="<?php print $ls_codestpro4 ?>" size="<?php print $li_loncodestpro4+2 ?>" maxlength="<?php print $li_loncodestpro4 ?>" readonly="readonly" style="text-align:center" />
      <a href="javascript:catalogo_estpro4();"><img src="../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0" /></a>
      <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" value="<?php print $ls_denestpro4 ?>" size="65" readonly="readonly" style="text-align:left" />      </td>
    </tr>
    <tr>
      <td height="22" style="text-align:right"><?php print $_SESSION["la_empresa"]["nomestpro5"];  ?></td>
      <td height="22" colspan="5"><input name="txtcodestpro5" type="text" id="txtcodestpro5" value="<?php print $ls_codestpro5 ?>" size="<?php print $li_loncodestpro5+2 ?>" maxlength="<?php print $li_loncodestpro5 ?>" readonly="readonly" style="text-align:center" onblur="" />
      <a href="javascript:catalogo_estpro5();"><img src="../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0" /></a>
      <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" value="<?php print $ls_denestpro5 ?>" size="65" readonly="readonly" style="text-align:left" />      </td>
    </tr>
    <?php
	   }
	?>
      <tr>
        <td align="center" colspan="6" style="text-align:center">&nbsp;</td>
      </tr>
      <tr> 
        <td align="center" colspan="6" style="text-align:center"><div id="detalles"></div></td>
      </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
f = document.formulario;
function writetostatus(input){
    window.status=input
    return true
}

function uf_nuevo()
{
	f.hidestcla.value     = "";
	f.txtcodestpro1.value = "";
	f.txtcodestpro2.value = "";
	f.txtcodestpro3.value = "";
	f.txtdenestpro1.value = "";
	f.txtdenestpro2.value = "";
	f.txtdenestpro3.value = "";
	li_estmodest = "<?php print $_SESSION["la_empresa"]["estmodest"]; ?>";
	if (li_estmodest==2)
	   {
		 f.txtcodestpro4.value = "";
		 f.txtcodestpro5.value = "";
		 f.txtdenestpro4.value = "";
		 f.txtdenestpro5.value = "";
	   }	
	
	divgrid = document.getElementById("detalles");
	ajax=objetoAjax();
	ajax.open("POST","class_folder/sigesp_spg_c_codestpro_codfuefin_ajax.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			divgrid.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("proceso=LIMPIAR");
}

function ue_guardar()
{
  li_incluir = f.incluir.value;
  li_cambiar = f.cambiar.value;
  lb_existe  = f.existe.value;
  parametros = "";
  if (((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	 {
	   lb_valido    = true;
	   ls_codestpro1 = f.txtcodestpro1.value;
	   ls_codestpro2 = f.txtcodestpro2.value;
	   ls_codestpro3 = f.txtcodestpro3.value;
	   if (lb_valido)
		  {
		    lb_valido = ue_validarcampo(ls_codestpro1,"La Estructura Presupuestaria de Nivel 1 no puede estar vacía !!!.",f.txtcodestpro1);
		  }
	   if (lb_valido)
		  {
		   lb_valido = ue_validarcampo(ls_codestpro2,"La Estructura Presupuestaria de Nivel 2 no puede estar vacía !!!.",f.txtcodestpro2);
		  }
	   if (lb_valido)
		  {
		    lb_valido = ue_validarcampo(ls_codestpro3,"La Estructura Presupuestaria de Nivel 3 no puede estar vacía !!!.",f.txtcodestpro3);
		  }
	   li_estmodest = "<?php print $_SESSION["la_empresa"]["estmodest"]; ?>";
	   if (li_estmodest==2)
	      {
		    ls_codestpro4 = f.txtcodestpro4.value;
	        ls_codestpro5 = f.txtcodestpro5.value;
			if (lb_valido)
			   {
			    lb_valido = ue_validarcampo(ls_codestpro4,"La Estructura Presupuestaria de Nivel 4 no puede estar vacía !!!.",f.txtcodestpro4);
			   }
		    if (lb_valido)
			   {
				 lb_valido = ue_validarcampo(ls_codestpro5,"La Estructura Presupuestaria de Nivel 5 no puede estar vacía !!!.",f.txtcodestpro5);
			   }
		  }	
       if (lb_valido)
		  {
		    li_totrows   = ue_calcular_total_fila_local("txtcodfuefin");
		    ls_codfuefin = eval("f.txtcodfuefin1.value");
		    if (li_totrows>=1 && ls_codfuefin!="")
			   {
				 for (li_i=1;li_i<=li_totrows;li_i++)
					 {
					   ls_codfuefin = eval("f.txtcodfuefin"+li_i+".value");
					   ls_denfuefin = eval("f.txtdenfuefin"+li_i+".value");
					   lb_exifuefin = eval("f.hidexiste"+li_i+".value");
				       parametros   = parametros+"&txtcodfuefin"+li_i+"="+ls_codfuefin+"&txtdenfuefin"+li_i+"="+ls_denfuefin+"&hidexiste="+lb_exifuefin+"&totrows="+li_totrows;
					 }
			     f.totrows.value = li_totrows;
			   }
		    else
			   {
			     lb_valido = false;
				 alert("Debe Incorporar al menos una Fuente de Financiamiento !!!");			   
			   }
		  }	   
	   if (lb_valido)
		  {
		    f.operacion.value="GUARDAR";
			f.action="sigesp_spg_d_codestpro_codfuefin.php";
			f.submit();		
		  }
     } 
  else
	 {
 	   alert("No tiene permiso para realizar esta operación !!!");
	 }	 
}

function catalogo_estpro1()
{
  ls_opener = f.id;
  pagina="sigesp_spg_cat_estpro1.php?opener="+ls_opener;
  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=620,height=400,resizable=yes,location=no");
}

function catalogo_estpro2()
{
  ls_opener     = f.id;
  ls_codestpro1 = f.txtcodestpro1.value;
  ls_denestpro1 = f.txtdenestpro1.value;
  ls_estcla     = f.hidestcla.value;
  if ((ls_codestpro1!="")&&(ls_denestpro1!=""))
	 {
	   pagina="sigesp_spg_cat_estpro2.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtclasificacion="+ls_estcla+"&opener="+ls_opener;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=620,height=400,resizable=yes,location=no");
	 }
  else
	 {
	   alert("Debe seleccionar una estructura del Nivel 1 !!!");
	 }
}

function catalogo_estpro3()
{
  ls_codestpro1 = f.txtcodestpro1.value;
  ls_codestpro2 = f.txtcodestpro2.value;
  li_estmodest  = "<?php print $_SESSION["la_empresa"]["estmodest"]; ?>";
  if ((ls_codestpro1!='' && ls_codestpro2=='') || (ls_codestpro1=='' && ls_codestpro2=='' && li_estmodest=='2'))
     {
	   alert("Debe seleccionar una estructura del Nivel 2 !!!");
	 }
  else
     {
	   ls_estcla     = f.hidestcla.value;
	   ls_opener     = f.id;
	   ls_denestpro1 = f.txtdenestpro1.value;
	   ls_denestpro2 = f.txtdenestpro2.value;
	   ls_codestpro3 = f.txtcodestpro3.value;   
	   pagina = "sigesp_spg_cat_estpro3.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtclasificacion="+ls_estcla+"&opener="+ls_opener;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=780,height=450,resizable=yes,location=no");
	 }
}

function catalogo_estpro4()
{
  ls_codestpro1 = f.txtcodestpro1.value;
  ls_codestpro2 = f.txtcodestpro2.value;
  ls_codestpro3 = f.txtcodestpro3.value;

  if (ls_codestpro1=='' || ls_codestpro2=='' || ls_codestpro3=='')
     {
	   alert("Debe seleccionar una estructura del Nivel 3 !!!");
	 }
  else
     {
	   ls_estcla     = f.hidestcla.value;
	   ls_opener     = f.id;	   
	   ls_denestpro1 = f.txtdenestpro1.value;
	   ls_denestpro2 = f.txtdenestpro2.value;
	   ls_denestpro3 = f.txtdenestpro3.value;
	   
	   pagina = "sigesp_spg_cat_estpro4.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1
	                                     +"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2
										 +"&txtcodestpro3="+ls_codestpro3+"&txtdenestpro3="+ls_denestpro3
										 +"&txtclasificacion="+ls_estcla+"&opener="+ls_opener;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=780,height=450,resizable=yes,location=no");
	 }
}

function catalogo_estpro5()
{
  ls_codestpro1 = f.txtcodestpro1.value;
  ls_codestpro2 = f.txtcodestpro2.value;
  ls_codestpro3 = f.txtcodestpro3.value;
  ls_codestpro4 = f.txtcodestpro4.value;
  if ((ls_codestpro1!='' && ls_codestpro2!='' && ls_codestpro3!='' && ls_codestpro4=='') ||
     (ls_codestpro1!='' && ls_codestpro2!='' && ls_codestpro3=='' && ls_codestpro4=='') ||
	 (ls_codestpro1!='' && ls_codestpro2=='' && ls_codestpro3=='' && ls_codestpro4==''))
     {
	   alert("Debe seleccionar una estructura del Nivel 4 !!!");
	 }
  else
     {
	   ls_estcla     = f.hidestcla.value;
	   ls_opener     = f.id;
	   
	   ls_denestpro1 = f.txtdenestpro1.value;
	   ls_denestpro2 = f.txtdenestpro2.value;
	   ls_denestpro3 = f.txtdenestpro3.value;
	   ls_denestpro4 = f.txtdenestpro4.value;
	   
	   pagina = "sigesp_spg_cat_estpro5.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1
	                                     +"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2
										 +"&txtcodestpro3="+ls_codestpro3+"&txtdenestpro3="+ls_denestpro3
										 +"&txtcodestpro4="+ls_codestpro4+"&txtdenestpro4="+ls_denestpro4
										 +"&txtclasificacion="+ls_estcla+"&opener="+ls_opener;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=780,height=450,resizable=yes,location=no");
	 }
}

function uf_catalogo_fuente_financiamiento()
{
  li_estmodest = "<?php print $_SESSION["la_empresa"]["estmodest"]; ?>";
  lb_valido    = true;
  if (li_estmodest==1)
     {
	   if (f.txtcodestpro3.value=='')
	      {
		    lb_valido = false;
			alert("Debe Completar Estructura Presupuestaria !!!");
		  }
	 }
  else if (li_estmodest==2)
     {
	   if (f.txtcodestpro5.value=='')
	      {
		    lb_valido = false;
			alert("Debe Completar Estructura Presupuestaria !!!");
		  }
	 }
  if (lb_valido)
     {
	   ls_opener = f.id;
	   pagina="sigesp_spg_cat_fuentefinan.php?opener="+ls_opener;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=620,height=400,resizable=yes,location=no");
	 }
}

function ue_reload()
{
  lb_existe  = f.existe.value;
  parametros = f.parametros.value;
  divgrid    = document.getElementById("detalles");
  ajax=objetoAjax();
  ajax.open("POST","class_folder/sigesp_spg_c_codestpro_codfuefin_ajax.php",true);
  ajax.onreadystatechange=function() {
  if (ajax.readyState==4) {
	   divgrid.innerHTML = ajax.responseText
	 }
  }
  ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  ajax.send("proceso=LIMPIAR"+parametros+"&existe="+lb_existe);
}

function uf_delete_dt(ai_fila)
{
  ls_estcla     = f.hidestcla.value;
  li_totrows    = ue_calcular_total_fila_local("txtcodfuefin");
  ls_codestpro1 = f.txtcodestpro1.value;
  ls_codestpro2 = f.txtcodestpro2.value;
  ls_codestpro3 = f.txtcodestpro3.value;  
  li_estmodest  = "<?php print $_SESSION["la_empresa"]["estmodest"]; ?>";
  f.totrows.value = li_totrows;
  if (li_estmodest==2)
     {
	   ls_codestpro4 = f.txtcodestpro4.value;
	   ls_codestpro5 = f.txtcodestpro5.value;
     }
  else
     {
	   ls_codestpro4 = "";
	   ls_codestpro5 = "";
	 }
  parametros = "";
  ls_codfuefindel = eval("f.txtcodfuefin"+ai_fila+".value");
  ls_denfuefindel = eval("f.txtdenfuefin"+ai_fila+".value");
  for (li_i=1;li_i<=li_totrows;li_i++)
      {
	    ls_codfuefin = eval("f.txtcodfuefin"+li_i+".value");
	    ls_denfuefin = eval("f.txtdenfuefin"+li_i+".value");
		lb_exifuefin = eval("f.hidexiste"+li_i+".value");
	    parametros   = parametros+"&txtcodfuefin"+li_i+"="+ls_codfuefin+"&txtdenfuefin"+li_i+"="+ls_denfuefin+"&hidexiste"+li_i+"="+lb_exifuefin;
	  }
  parametros   = parametros+"&totrows"+li_totrows;
  f.parametros.value = parametros;
  divgrid      = document.getElementById("detalles");
  ajax=objetoAjax();
  ajax.open("POST","class_folder/sigesp_spg_c_codestpro_codfuefin_ajax.php",true);
  ajax.onreadystatechange=function() {
  if (ajax.readyState==4) {
		divgrid.innerHTML = ajax.responseText
	 }
  }
  ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  ajax.send("proceso=DELETE_DT"+parametros+"&codfuefin="+ls_codfuefindel+"&codestpro1="+ls_codestpro1+"&codestpro2="+ls_codestpro2+"&codestpro3="+ls_codestpro3+"&codestpro4="+ls_codestpro4+"&codestpro5="+ls_codestpro5+"&estcla="+ls_estcla+"&totrows="+li_totrows+"&denfuefin="+ls_denfuefindel);
}

function uf_delete_detalle(ai_fila)
{
  li_eliminar = f.eliminar.value;
  lb_existe   = f.existe.value;
  parametros  = "";
  if (li_eliminar==1)
     {
	   if (confirm("¿Desea eliminar el Registro actual?"))
		  {
		    li_x = 0;
		    parametros = "";
		    li_totrows = ue_calcular_total_fila_local("txtcodfuefin");
		    for (li_y=1;li_y<=li_totrows;li_y++)
			    {
				  if (li_y!=ai_fila)
					 {
					   li_x++;
					   ls_codfuefin = eval("f.txtcodfuefin"+li_y+".value");
					   ls_denfuefin = eval("f.txtdenfuefin"+li_y+".value");
					   lb_exifuefin = eval("f.hidexiste"+li_y+".value");
					   parametros   = parametros+"&txtcodfuefin"+li_x+"="+ls_codfuefin+"&txtdenfuefin"+li_x+"="+ls_denfuefin+"&hidexiste"+li_x+"="+lb_exifuefin;
					 }
			    }
		    if (parametros!='')
			   {
				 parametros = parametros+"&totrows="+li_x;     
				 f.parametros.value = parametros;
				 divgrid      = document.getElementById("detalles");
				 ajax=objetoAjax();
				 ajax.open("POST","class_folder/sigesp_spg_c_codestpro_codfuefin_ajax.php",true);
				 ajax.onreadystatechange=function() {
				 if (ajax.readyState==4) {
						divgrid.innerHTML = ajax.responseText
				    }
				 }
				 ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				 ajax.send("proceso=LIMPIAR"+parametros);
			   }	   
		  }
	 }
  else
     {
	   alert("No tiene permiso para realizar esta operación !!!");
	 }
}  
</script>
<?php
if ($ls_operacion=="NUEVO")
   {
	 echo "<script language=JavaScript>";
	 echo "   uf_nuevo();";
	 echo "</script>";
   }
elseif($ls_operacion=="GUARDAR")
   {
	 echo "<script language=JavaScript>";
	 echo "   ue_reload();";
	 echo "</script>";
   }
?>
</html>