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
<title>Modificaci&oacute;n de Comprobante de Retenci&oacute;n</title>
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
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>

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
</head>

<body>

<table width="778" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="528">&nbsp;</td>
  </tr>
</table>
<?php
	include("../shared/class_folder/class_mensajes.php");
	$msg=new class_mensajes();	
	include("../shared/class_folder/class_funciones.php");
	$fun=new class_funciones();	
	$lb_guardar=true;
	include("../shared/class_folder/grid_param.php");
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
	$ls_ventanas="sigesp_scb_p_cmp_ret_mod.php";
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
	include("sigesp_scb_c_cmp_ret.php");
	$io_cmpret=new sigesp_scb_c_cmp_ret($la_security);

	if( array_key_exists("operacion",$_POST))//Cuando aplicamos alguna operacion 
	{
		$ls_operacion= $_POST["operacion"];
		$ls_numcom    = $_POST["txtcomprobante"];
		$ls_codret    = $_POST["txtcodret"];
		$ls_codsujret = $_POST["txtcodigo"];
		$ls_nomsujret = $_POST["txtnombre"];
		$ls_dirsujret = $_POST["txtdireccion"];
		$ls_rifsujret = $_POST["txtrif"];
		$ls_mes      = $_POST["mes"];
		$ls_agno     = $_POST["agno"];		
	}
	else//Caso de apertura de la pagina o carga inicial
	{
		$ls_operacion= "NUEVO" ;		
		$array_fecha=getdate();
		$ls_mes=$array_fecha["mon"];
		$ls_agno=$array_fecha["year"];
		$ls_numcom    = "";
		$ls_codret    = "";
		$ls_codsujret = "";
		$ls_nomsujret = "";
		$ls_dirsujret = "";
		$ls_rifsujret = "";
		$li_i = 1;
		$li_total = 1;
		$object[$li_i][1] = "";
		$object[$li_i][2] = "";
		$object[$li_i][3] = "";
		$object[$li_i][4] = number_format(0,2,",","."); 
		$object[$li_i][5] = number_format(0,2,",",".");			
		$object[$li_i][6] = number_format(0,2,",",".");			
		$object[$li_i][7] = number_format(0,2,",",".");			
		$object[$li_i][8] = number_format(0,2,",",".");							
		$object[$li_i][9] = number_format(0,2,",",".");			
		$object[$li_i][10]= "";			
		$object[$li_i][11]= "<img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar Retención' width=15 height=15 border=0>";
	}
	
	//Declaración de parametros del grid.
	$titleProg[1]="Nº Operación";   
	$titleProg[2]="Factura";    
	$titleProg[3]="Fecha";     
	$titleProg[4]="Total sin IVA";        
    $titleProg[5]="Total con IVA";
    $titleProg[6]="Base Imponible";        
    $titleProg[7]="Porcentaje Impuesto";        
    $titleProg[8]="Total Impuesto";        
    $titleProg[9]="IVA Retenido";        
	$titleProg[10]="Nº Documento";
	$titleProg[11]="Edición";
	   
    $gridProg="grid_prog";
	
	if($ls_operacion=="ELIMINAR")		
	{
		$io_cmpret->SQL->begin_transaction();
		$lb_valido=$io_cmpret->uf_delete_cmp_ret($ls_codret,$ls_numcom);
		if($lb_valido)
		{
			$io_cmpret->SQL->commit();
			$msg->message("Comprobante Eliminado");
			$ls_numcom    = "";
			$ls_codret    = "";
			$ls_codsujret = "";
			$ls_nomsujret = "";
			$ls_dirsujret = "";
			$ls_rifsujret = "";
		}
		else
		{
			$io_cmpret->SQL->rollback();
			$msg->message("".$io_cmpret->is_msg_error);
		}
		$ls_operacion="CARGAR_DT";
		
	}	
	
	if($ls_operacion=="GUARDAR")
	{
		
		$lb_valido=$io_cmpret->uf_update_cmp_ret($ls_codret,$ls_numcom,$ls_codsujret,$ls_nomsujret,$ls_rifsujret,$ls_dirsujret);
		
		$ls_operacion="CARGAR_DT";
	}
	
	if($ls_operacion=="DELETE_DT")		
	{
		$ls_numope=	$_POST["numope"];
		$ls_numfac=	$_POST["numfac"];
		$io_cmpret->SQL->begin_transaction();
		$lb_valido=$io_cmpret->uf_delete_dt_cmp_ret($ls_codret,$ls_numcom,$ls_numope,$ls_numfac);
		if($lb_valido)
		{
			$io_cmpret->SQL->commit();
			$msg->message("Registro Eliminado");
		}
		else
		{
			$io_cmpret->SQL->rollback();
			$msg->message("".$io_cmpret->is_msg_error);
		}
		$ls_operacion="CARGAR_DT";
		
	}
	
	if($ls_operacion=="CARGAR_DT")	
	{
		$io_cmpret->uf_select_cmp_ret($ls_numcom,$ls_codret,&$object,&$li_total);
	}
	

  ?>
<p>&nbsp;</p>
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
       
	   switch ($ls_mes) {
	   case '01':
		   $lb_selEnero="selected";
   		   $lb_selFebrero="";
   		   $lb_selMarzo="";
   		   $lb_selAbril="";
   		   $lb_selMayo="";
   		   $lb_selJunio="";
		   $lb_selJulio="";
		   $lb_selAgosto="";
 		   $lb_selSeptiembre="";
		   $lb_selOctubre="";
		   $lb_selNoviembre="";	
		   $lb_selDiciembre="";
		   break;
	   case '02':
   		   $lb_selEnero="";
   		   $lb_selFebrero="selected";
   		   $lb_selMarzo="";
   		   $lb_selAbril="";
   		   $lb_selMayo="";
   		   $lb_selJunio="";
		   $lb_selJulio="";
		   $lb_selAgosto="";
 		   $lb_selSeptiembre="";
		   $lb_selOctubre="";
		   $lb_selNoviembre="";	
		   $lb_selDiciembre="";
		   break;
	   case '03':
   		   $lb_selEnero="";
   		   $lb_selFebrero="";
   		   $lb_selMarzo="selected";
   		   $lb_selAbril="";
   		   $lb_selMayo="";
   		   $lb_selJunio="";
		   $lb_selJulio="";
		   $lb_selAgosto="";
 		   $lb_selSeptiembre="";
		   $lb_selOctubre="";
		   $lb_selNoviembre="";	
		   $lb_selDiciembre="";
		   break;
	   case '04':
   		   $lb_selEnero="";
   		   $lb_selFebrero="";
   		   $lb_selMarzo="";
   		   $lb_selAbril="selected";
   		   $lb_selMayo="";
   		   $lb_selJunio="";
		   $lb_selJulio="";
		   $lb_selAgosto="";
 		   $lb_selSeptiembre="";
		   $lb_selOctubre="";
		   $lb_selNoviembre="";	
		   $lb_selDiciembre="";
		   break;
	   case '05':
   		   $lb_selEnero="";
   		   $lb_selFebrero="";
   		   $lb_selMarzo="";
   		   $lb_selAbril="";
   		   $lb_selMayo="selected";
   		   $lb_selJunio="";
		   $lb_selJulio="";
		   $lb_selAgosto="";
 		   $lb_selSeptiembre="";
		   $lb_selOctubre="";
		   $lb_selNoviembre="";	
		   $lb_selDiciembre="";
		   break;
	   case '06':
   		   $lb_selEnero="";
   		   $lb_selFebrero="";
   		   $lb_selMarzo="";
   		   $lb_selAbril="";
   		   $lb_selMayo="";
   		   $lb_selJunio="selected";
		   $lb_selJulio="";
		   $lb_selAgosto="";
 		   $lb_selSeptiembre="";
		   $lb_selOctubre="";
		   $lb_selNoviembre="";	
		   $lb_selDiciembre="";
		   break;		   
	   case '07':
   		   $lb_selEnero="";
   		   $lb_selFebrero="";
   		   $lb_selMarzo="";
   		   $lb_selAbril="";
   		   $lb_selMayo="";
   		   $lb_selJunio="";
		   $lb_selJulio="selected";
		   $lb_selAgosto="";
 		   $lb_selSeptiembre="";
		   $lb_selOctubre="";
		   $lb_selNoviembre="";	
		   $lb_selDiciembre="";
		   break;		   		 
	   case '08':
   		   $lb_selEnero="";
   		   $lb_selFebrero="";
   		   $lb_selMarzo="";
   		   $lb_selAbril="";
   		   $lb_selMayo="";
   		   $lb_selJunio="";
		   $lb_selJulio="";
		   $lb_selAgosto="selected";
 		   $lb_selSeptiembre="";
		   $lb_selOctubre="";
		   $lb_selNoviembre="";	
		   $lb_selDiciembre="";
		   break;		   		 		     
	   case '09':
   		   $lb_selEnero="";
   		   $lb_selFebrero="";
   		   $lb_selMarzo="";
   		   $lb_selAbril="";
   		   $lb_selMayo="";
   		   $lb_selJunio="";
		   $lb_selJulio="";
		   $lb_selAgosto="";
 		   $lb_selSeptiembre="selected";
		   $lb_selOctubre="";
		   $lb_selNoviembre="";	
		   $lb_selDiciembre="";
		   break;
	   case '10':
   		   $lb_selEnero="";
   		   $lb_selFebrero="";
   		   $lb_selMarzo="";
   		   $lb_selAbril="";
   		   $lb_selMayo="";
   		   $lb_selJunio="";
		   $lb_selJulio="";
		   $lb_selAgosto="";
 		   $lb_selSeptiembre="";
		   $lb_selOctubre="selected";
		   $lb_selNoviembre="";	
		   $lb_selDiciembre="";
		   break;		   
	   case '11':
   		   $lb_selEnero="";
   		   $lb_selFebrero="";
   		   $lb_selMarzo="";
   		   $lb_selAbril="";
   		   $lb_selMayo="";
   		   $lb_selJunio="";
		   $lb_selJulio="";
		   $lb_selAgosto="";
 		   $lb_selSeptiembre="";
		   $lb_selOctubre="";
		   $lb_selNoviembre="selected";	
		   $lb_selDiciembre="";
		   break;		   
	   case '12':
   		   $lb_selEnero="";
   		   $lb_selFebrero="";
   		   $lb_selMarzo="";
   		   $lb_selAbril="";
   		   $lb_selMayo="";
   		   $lb_selJunio="";
		   $lb_selJulio="";
		   $lb_selAgosto="";
 		   $lb_selSeptiembre="";
		   $lb_selOctubre="";
		   $lb_selNoviembre="";	
		   $lb_selDiciembre="selected";
		   break;		   
	}
	   
	    ?>
  <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><table width="693" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco" id="tabla">
          <tr class="titulo-ventana">
            <td colspan="4">Modificaci&oacute;n de Comprobante de Retenci&oacute;n</td>
          </tr>
          <tr>
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr>
            <td height="22"><div align="right">Periodo</div></td>
            <td colspan="3"><div align="left">
                <select name="mes" id="mes">
                  <option value="01" <?php print $lb_selEnero;     ?>>ENERO</option>
                  <option value="02" <?php print $lb_selFebrero;   ?>>FEBRERO</option>
                  <option value="03" <?php print $lb_selMarzo;     ?>>MARZO</option>
                  <option value="04" <?php print $lb_selAbril;     ?>>ABRIL</option>
                  <option value="05" <?php print $lb_selMayo;      ?>>MAYO</option>
                  <option value="06" <?php print $lb_selJunio;     ?>>JUNIO</option>
                  <option value="07" <?php print $lb_selJulio;     ?>>JULIO</option>
                  <option value="08" <?php print $lb_selAgosto;    ?>>AGOSTO</option>
                  <option value="09" <?php print $lb_selSeptiembre;?>>SEPTIEMBRE</option>
                  <option value="10" <?php print $lb_selOctubre;   ?>>OCTUBRE</option>
                  <option value="11" <?php print $lb_selNoviembre; ?>>NOVIEMBRE</option>
                  <option value="12" <?php print $lb_selDiciembre; ?>>DICIEMBRE</option>
                </select>
			<input name="agno" type="text" id="agno" style="text-align:center " value="<?php print $ls_agno;?>" size="10" maxlength="4">
                        </div>              </td>
          </tr>
          <tr>
            <td width="81" height="22"><div align="right">Comprobante</div></td>
            <td width="193" height="22"><div align="left">
                <input name="txtcomprobante" type="text" id="txtcomprobante" value="<?php print $ls_numcom;?>" readonly style="text-align:center">
                <input name="txtcodret" type="hidden" id="txtcodret" value="<?php print $ls_codret;?>">
            </div></td>
            <td width="169" height="22">&nbsp;</td>
            <td width="219" height="22">&nbsp;</td>
          </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
          </tr>
          <tr class="titulo-ventana">
            <td height="18" colspan="4" >Sujeto Retenci&oacute;n</td>
          </tr>
          <tr>
            <td height="22" colspan="4">&nbsp;</td>
          </tr>
          <tr>
            <td height="22"><div align="right">Codigo</div></td>
            <td height="22"><div align="left">
                <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codsujret;?>" style="text-align:center">
            </div></td>
            <td height="22"><div align="right">RIF</div></td>
            <td height="22"><div align="left">
                <input name="txtrif" type="text" id="txtrif" value="<?php print $ls_rifsujret;?>" style="text-align:center">
            </div></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Nombre</div></td>
            <td height="22" colspan="3"><div align="left">
                <input name="txtnombre" type="text" id="txtnombre" value="<?php print $ls_nomsujret;?>" size="110" onKeyPress="return keyRestrict(event,'0123456789'+'abcdefghijklmnopqrstuvwxyzñ .,*/-()$%&!ºªáéíóú[]{}<>')">
            </div></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Direcci&oacute;n</div></td>
            <td height="22" colspan="3"><input name="txtdireccion" type="text" id="txtdireccion" value="<?php print $ls_dirsujret;?>" size="110" onKeyPress="return keyRestrict(event,'0123456789'+'abcdefghijklmnopqrstuvwxyzñ .,*/-()$%&!ºªáéíóú[]{}<>')"></td>
          </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
          </tr>
          <tr>
            <td height="22" colspan="4">&nbsp;</td>
          </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
          </tr>
            </table></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table width="200" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><?php $io_grid->makegrid($li_total,$titleProg,$object,770,'Documentos ',$gridProg);?>
        <input name="totsol"  type="hidden" id="totsol"  value="<?php print $li_total?>">
        <input name="numope" type="hidden" id="numope">
        <input name="numfac" type="hidden" id="numfac"></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>
    <input name="operacion" type="hidden" id="operacion">
  </p>
  </form>
</body>
<script language="javascript">
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value ="NUEVO";
	f.action="sigesp_scb_p_cmp_ret_mod.php";
	f.submit();
}

function ue_guardar()
{
	f=document.form1;
	ls_numcom=f.txtcomprobante.value;
	ls_codret=f.txtcodret.value;
	ls_codigo=f.txtcodigo.value;
	ls_nombre=f.txtnombre.value;
	ls_rif=f.txtrif.value;
	ls_direccion=f.txtdireccion.value;
	if((ls_numcom!="")&&(ls_codret!="")&&(ls_codigo!="")&&(ls_nombre!="")&&(ls_rif!="")&&(ls_direccion!=""))
	{
		f.operacion.value ="GUARDAR";
		f.action="sigesp_scb_p_cmp_ret_mod.php";
		f.submit();
	}
}


function ue_eliminar()
{
	f=document.form1;
	ls_numcom=f.txtcomprobante.value;
	ls_codret=f.txtcodret.value;
	if((ls_numcom!="")&&(ls_codret))
	{
		f.operacion.value ="ELIMINAR";
		f.action="sigesp_scb_p_cmp_ret_mod.php";
		f.submit();
	}
}

function ue_buscar()
{
	window.open("sigesp_cat_cmp_ret.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

  function aceptar(ls_numcom,ls_codret,ls_numope,ls_numfac)
  {
  	  ls_pagina="sigesp_scb_edit_cmp_ret.php?numcom="+ls_numcom+"&codret="+ls_codret+"&numope="+ls_numope+"&numfac="+ls_numfac+"&opener=sigesp_scb_edit_cmp_ret.php";
	  window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=588,height=210,left=50,top=50,location=no,resizable=yes");
  }
  
  
  function uf_delete_dt(ls_numope,ls_numfac)
  {
	  f=document.form1;
	  f.operacion.value ="DELETE_DT";
  	  f.action="sigesp_scb_p_cmp_ret_mod.php";
	  f.numope.value=ls_numope;
	  f.numfac.value=ls_numfac;
	  f.submit();
  
  
  }
  
    //Funciones de validacion de fecha.
	function rellenar_cad(cadena,longitud,campo)
	{
		var mystring=new String(cadena);
		cadena_ceros="";
		lencad=mystring.length;
	
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
		if(campo=="doc")
		{
			document.form1.txtdocumento.value=cadena;
		}
		if(campo=="cmp")
		{
			document.form1.txtcomprobante.value=cadena;
		}
		if(campo=="cod")
		{
			document.form1.txtcodigo.value=cadena;
		}
		if(campo=="chequera")
		{
			document.form1.txtchequera.value=cadena;
		}
		if(campo=="numcheque")
		{
			document.form1.txtnumcheque.value=cadena;
		}
		if(campo=="desde")
		{
			document.form1.txtdesde.value=cadena;
		}
		if(campo=="hasta")
		{
			document.form1.txthasta.value=cadena;
		}
		
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
   
   
   

   function uf_verificar_operacion()
   {
   	f=document.form1;
	f.operacion.value="CAMBIO_OPERA";
	f.submit();   
   }
   
   function uf_desaparecer(objeto)
   {
      eval("document.form1."+objeto+".style.visibility='hidden'");
   }
   function uf_aparecer(objeto)
   {
      eval("document.form1."+objeto+".style.visibility='visible'");
   }
   
 
 
   function uf_objeto(obj)
   {
   		alert(obj.name);   
   }
   
   function uf_registrar(fila,ls_numsol,ldec_monto,ld_fecsol,ls_provbene,obj)
   {
   		f=document.form1;
		ldec_monto_a_cancelar=eval("f.txtmoncan"+fila+".value");
		
		if((obj.name!=('chksel'+fila)))
		{
			f.txtnumsol.value=ls_numsol;
			f.txtmonto.value =uf_convertir(ldec_monto);
			f.txtprovbene.value=ls_provbene;
			f.txtfecha.value = ld_fecsol;
			f.fila.value=fila;
			f.txtcancelado.value=ldec_monto_a_cancelar;
			eval("f.chksel"+fila+".checked=false");
		}
		else
		{
			if(eval("f.chksel"+fila+".checked"))
			{
				f.txtnumsol.value=ls_numsol;
				f.txtmonto.value =uf_convertir(ldec_monto);
				f.txtprovbene.value=ls_provbene;
				f.txtfecha.value = ld_fecsol;
				f.fila.value=fila;
				ldec_cancelar=ldec_monto_a_cancelar;
				while(ldec_cancelar.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					ldec_cancelar=ldec_cancelar.replace(".","");
				}
				ldec_cancelar=ldec_cancelar.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion								
				if(parseFloat(ldec_cancelar)==0)
				{
					ldec_cancelar=ldec_monto;
					f.txtcancelado.value=uf_convertir(ldec_cancelar);				
				}
				else
				{
					f.txtcancelado.value=uf_convertir(ldec_monto_a_cancelar);				
				}
				
			}
			else
			{
				f.txtnumsol.value="";
				f.txtprovbene.value="";
				f.txtfecha.value = "";
				f.fila.value=0;
				f.txtmonto.value =uf_convertir(0);
				f.txtcancelado.value=uf_convertir(0);
			}
		}
		uf_calcular_total();
   }
   
   function uf_calcular_total()
   {
		f=document.form1;
		ldec_total=0;
		li_total=f.totsol.value;
		for(i=1;i<=li_total;i++)
		{
			if(eval("f.chksel"+i+".checked"))
			{				
				ldec_monto=eval("f.txtmoncan"+i+".value");
				while(ldec_monto.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					ldec_monto=ldec_monto.replace(".","");
				}
				ldec_monto=ldec_monto.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
				ldec_total=parseFloat(ldec_monto) + parseFloat(ldec_total);
			}
		}	
		f.txttotalprog.value=uf_convertir(ldec_total);
   }

   function fill_cad(cadena,longitud)
	{
		var mystring=new String(cadena);
		cadena_ceros="";
		lencad=mystring.length;
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
	   return cadena;
   }
  
		
   function uf_verificar_fechas(ld_fec1,ld_fec2)
   {
		ls_dia=ld_fec1.substr(0,2);
		li_dia1 =parseInt(ls_dia,10);
		ls_mes=ld_fec1.substr(3,2);
		li_mes1 =parseInt(ls_mes,10);
		ls_agno=ld_fec1.substr(6,4);
		li_agno1=parseInt(ls_agno,10);
		ls_dia  =ld_fec2.substr(0,2);
		li_dia2 =parseInt(ls_dia,10);
		ls_mes  =ld_fec2.substr(3,2);
		li_mes2 =parseInt(ls_mes,10);
		ls_agno=ld_fec2.substr(6,4);
		li_agno2=parseInt(ls_agno,10);

	   if(li_agno2>=li_agno1)
	   {
			if(li_mes2>li_mes1)
			{
				return true;
			}
			else if(li_mes2==li_mes1)
			{
				if(li_dia2>=li_dia1)
				{
					return true;
				}
				else if((li_dia2<li_dia1)&&(li_agno2>li_agno1))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else if((li_mes2<li_mes1)&&(li_agno2>li_agno1))
			{	
				return true;
			}
			else
			{
				return false;
			}   		
	   }
	   else
	   {
			return false;
	   }
   
   }
   function currencyFormat(fld, milSep, decSep, e)
   { 
		var sep = 0; 
		var key = ''; 
		var i = j = 0; 
		var len = len2 = 0; 
		var strCheck = '0123456789'; 
		var aux = aux2 = ''; 
		var whichCode = (window.Event) ? e.which : e.keyCode; 
		if (whichCode == 13) return true; // Enter 
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
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>