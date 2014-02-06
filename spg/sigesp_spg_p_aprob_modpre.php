<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$dat=$_SESSION["la_empresa"];	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Aprobaci&oacute;n de Modificaciones Presupuestarias</title>
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
-->
</style>
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
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
</script>
<style type="text/css">
<!--
.Estilo15 {color: #6699CC}
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
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo15">Contabilidad Presupuestaria de Gasto</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
           <?php
	   if(array_key_exists("confinstr",$_SESSION["la_empresa"]))
	  {
      if($_SESSION["la_empresa"]["confinstr"]=='A')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='V')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2007.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='N')
	  {
   ?>
       <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
  <?php
      }
	  	 }
	  else
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
	<?php 
	}
	?>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_ejecutar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Ejecutar" width="20" height="20" border="0"></a><a href="javascript: ue_nuevo();"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"></a></div></td>
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
	$msg=new class_mensajes();	
	require_once("../shared/class_folder/class_funciones.php");
	$fun=new class_funciones();	
	$lb_guardar=true;
    require_once("../shared/class_folder/sigesp_include.php");
    $sig_inc=new sigesp_include();
    $con=$sig_inc->uf_conectar();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("../shared/class_folder/class_datastore.php");
	$ds_sol=new class_datastore();
	//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
		$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
		$ls_logusr="";
	}
	
	$ls_sistema="SCB";
	$ls_ventanas="sigesp_scb_p_desprogpago.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$lb_permisos=true;
		}
		else
		{
			$lb_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$lb_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas);		
	}
	//Inclusión de la clase de seguridad.
	
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	include("sigesp_spg_c_aprob_modpre.php");
	$io_cmp=new sigesp_spg_c_aprob_modpre($la_security);

	if( array_key_exists("operacion",$_POST))//Cuando aplicamos alguna operacion 
	{
		$ls_operacion  = $_POST["operacion"];
		$ls_tipo       = $_POST["rb_provbene"];
		$ls_comprobante= $_POST["txtcomprobante"];
		$ls_procede    = $_POST["txtprocede"];
		$ld_fecha      = $_POST["txtfecha"];		
	}
	else//Caso de apertura de la pagina o carga inicial
	{
		$ls_operacion= "NUEVO" ;
		$ls_tipo='-';
		$ls_desproben="Ninguno";
		$ls_comprobante= "";
		$ls_procede    = "";
		$ld_fecha      = "";
	}
	
	//Declaración de parametros del grid.
	$titleProg[1]="Check";   $titleProg[2]="Comprobante";     $titleProg[3]="Fecha";     $titleProg[4]="Descripcion";  $titleProg[5]="Procede "; 
    $gridProg="grid_prog";
	
	if($ls_operacion == "EJECUTAR")
	{
		$li_row=$_POST["totcomprobantes"];
		$lb_valido=true;
		$io_sol->SQL->begin_transaction();
		for($i=0;($i<=$li_total)&&($lb_valido);$i++)
		{
			if(array_key_exists("chksel".$i,$_POST))
			{
				$ls_comprobante=$_POST["txtcomprobante".$i];
				$ld_fecha      =$_POST["txtfecha".$i];
				$ls_descripcion=$_POST["txtdescripcion".$i];
				$ls_procede    =$_POST["txtprocede".$i];
				$lb_valido=$io_cmp->uf_procesar_aprobacion($ls_comprobante,$ld_fecha,$ls_procede,$ls_descripcion);
			}			
		}		
		if($lb_valido)
		{
			$io_sol->SQL->commit();	
			$msg->message("El proceso fue ejecutado satisfactoriamente");
		}
		else
		{
			$io_sol->SQL->rollback();
		}
		
	}
	if($ls_operacion=="NUEVO")
	{
		
	}
	if($ls_operacion=="CARGAR_DT")
	{
		//Cargo los datos de los comprobantes de modificaciones presupuestarias
		$io_cmp->uf_cargar_comprobantes($_SESSION["la_empresa"]["codemp"],$ls_comprobante,$ls_procede,$ld_fecha,$object,$li_row);
	}
		
  ?>
  <form name="form1" method="post" action="">
  <?php 
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
		if (($lb_permisos)||($ls_logusr=="PSEGIS"))
		{
			print("<input type=hidden name=permisos id=permisos value='$lb_permisos'>");			
		}
		else
		{			
			print("<script language=JavaScript>");
			print(" location.href='sigespwindow_blank.php'");
			print("</script>");
		}
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
        ?>
  <br>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td colspan="4">Aprobaci&oacute;n de Modificaciones Presupuestarias </td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">Comprobante</div></td>
      <td><input name="txtcomprobante" type="text" id="txtcomprobante" value="<?php print $ls_comprobante;?>"></td>
      <td><div align="right">Fecha</div></td>
      <td><input name="txtfecha" type="text" id="txtfecha" style="text-align:center" onKeyPress="javascript:currencyDate(this)" value="<?php print $ld_fecha;?>" maxlength="10" datepicker="true"></td>
    </tr>
    <tr>
      <td width="100" height="22"><div align="right">Procede</div></td>
      <td colspan="2"><input name="txtprocede" type="text" id="txtprocede" value="<?php print $ls_procede;?>"></td>
      <td width="267">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;&nbsp;<a href="javascript: uf_cargar_dt();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Cargar Comprobantes" width="15" height="15" border="0">Cargar Comprobantes</a></td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center"><?php $io_grid->makegrid($li_row,$titleProg,$object,770,'Comprobantes',$gridProg);?>
          <input name="totcomprobantes"  type="hidden" id="totcomprobantes"  value="<?php print $li_row?>">
          <input name="fila"    type="hidden" id="fila">
      </div></td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td width="276" height="22">&nbsp;</td>
      <td width="135" height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
  </table>
  <p><input name="operacion" type="hidden" id="operacion">
</p>
  </form>
</body>
<script language="javascript">

	function ue_ejecutar()
	{
		f=document.form1;
		f.operacion.value ="EJECUTAR";
		f.action="sigesp_spg_p_aprob_modpre.php";
		f.submit();
	}
	
	function ue_eliminar()
	{
		
	}
	
	function ue_buscar()
	{
		//window.open("sigesp_catdinamic_progpago.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	
	function ue_cerrar()
	{
		f=document.form1;
		f.action="sigespwindow_blank.php";
		f.submit();
	}

	function uf_cargar_dt()
	{
		f=document.form1;
		f.operacion.value="CARGAR_DT";
		f.action="sigesp_spg_p_aprob_modpre.php";
		f.submit();
	}
	
	function currencyDate(date)
	{ 
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}
			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string,10);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
			//alert(ls_long);
	
	
	//  return false; 
	}   
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>