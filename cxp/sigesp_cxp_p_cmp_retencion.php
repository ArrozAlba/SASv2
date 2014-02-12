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
	require_once("../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$io_fun_cxp->uf_load_seguridad("CXP","sigesp_cxp_p_cmp_retencion.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   
   
   //--------------------------------------------------------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 19/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_mes,$arr_fecha,$ls_agno,$ls_operacion,$ls_existe,$ls_tipo,$ls_mes,$io_funciones;
		global $ls_disenb,$ls_provbenedesde,$io_fun_cxp,$ls_provbenehasta,$ls_flag,$ls_hidopctipret,
		$ls_descmb,$ls_disaposol,$ls_fecpro,$ls_numsol;
		
		$arr_fecha        = getdate();
		$ls_agno          = $arr_fecha["year"];
		$ls_mes           = $arr_fecha["mon"];
		$ls_tipo          = "-";
		$ls_mes           = $io_funciones->uf_cerosizquierda($ls_mes,2);
		$ls_provbenedesde = "";
		$ls_provbenehasta = "";
		$ls_flag          = ""; 
		$ls_hidopctipret  = "";
		$ls_descmb        = "disabled=true"; 
		$ls_disenb        = "disabled=true";
		$ls_disaposol     = "disabled=true";
		$ls_fecpro        = "";
		$ls_numsol        = "";
		$ls_operacion=$io_fun_cxp->uf_obteneroperacion();
   }
   //--------------------------------------------------------------------------------------------------------------

   //--------------------------------------------------------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Gerardo Cordero
		// Fecha Creación: 23/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_disenb,$ls_tipo,$ls_mes,$ls_agno,$ls_provbenedesde,$ls_provbenehasta,$ls_flag,$ls_descmb,$ls_hidopctipret,$ls_fecpro,$ls_numsol  ;
				
	    $ls_tipo          = $_POST["estprov"];
	    $ls_mes           = $_POST["mes"];
	    $ls_agno          = $_POST["agno"];
	    $ls_provbenedesde = $_POST["txtcodigo1"];
	    $ls_provbenehasta = $_POST["txtcodigo2"];
	    $ls_flag          = $_POST["hidflag"]; 
	    $ls_descmb        = $_POST["hiddescmb"]; 
	    $ls_hidopctipret  = $_POST["hidopctipret"];
		$ls_disenb        = $_POST["hiddisenb"];
		$ls_fecpro        = $_POST["txtfecregdes"];
		$ls_numsol        = $_POST["txtnumsol"];	
		
		
   }
   //--------------------------------------------------------------------------------------------------------------
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Comprobante Retenci&oacute;n</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
</style>
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {	font-size: 12;
	font-weight: bold;
}
-->
</style>
</head>
<body>
<table width="771" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="769" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="806" height="40"></td>
  </tr>
  <tr>
    <td width="769" height="20" colspan="11" bgcolor="#E7E7E7"><table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
        <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Cuentas por Pagar </td>
            <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
        <tr>
          <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
          <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
    </table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0" title="Nuevo"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></td>
  </tr>
</table>
<?php
require_once("class_folder/sigesp_cxp_c_cmp_retencion.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/ddlb_meses.php");
$io_cmbmes=new ddlb_meses();
$io_retencion=new sigesp_cxp_c_cmp_retencion("../");
$io_msg=new class_mensajes();
uf_limpiarvariables();
$ls_modageret=$_SESSION["la_empresa"]["modageret"];
$ls_conivaret=$_SESSION["la_empresa"]["estretiva"];

	switch($ls_operacion)
	{
		case "NEW":
	        if($ls_modageret=="B")
	        {
	            $ls_descmb="disabled";
	        }
			if($ls_conivaret=="B")
			{
	            $ls_disenb="disabled";
			}
			$ls_disaposol="";
			if(($ls_modageret=="B")&&($ls_conivaret=="B"))
			{
				$io_msg->message("Los dos comprobantes se generan por el Módulo de Caja y Banco");	
			}
			else
			{
				uf_load_variables(); 
				$lb_flag=$io_retencion->uf_validar_estempresa();
			}
		break;
        
		case "PROCESAR":
			uf_load_variables(); 
	        $li_numcmp=$io_retencion->uf_procesar_cmp_retencion($ls_fecpro,$ls_mes,$ls_agno,$ls_provbenedesde,$ls_provbenehasta,
			                                                    $ls_tipo,$ls_hidopctipret,$la_numcmp,$ls_numsol,$la_seguridad);
	        if($li_numcmp>0)
	         {
	           for($li_i=1;$li_i<=$li_numcmp;$li_i++)
		         {
	                 $io_msg->message("Se proceso satisfactoriamente el Comprobante Nº.".$la_numcmp[$li_i]);
		         } 
	         }
	         else
	         {
	           $io_msg->message("No se generaron Comprobantes de Retencion verifique sus datos!!");
	         }	
		break;
		
		case "VALIDARMES":
		  $io_msg->message("Solo es posible generar comprobantes de retencion de el mes en curso!!");
		break;
	}
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">  
  <p>
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cxp->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cxp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  </p>
  <table width="474" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr class="titulo-celdanew">
      <td width="472" height="22" colspan="4" style="text-align:center" class="titulo-celdanew">Comprobante de Retenci&oacute;n</td>
    </tr>
    <tr>
      <td height="13" colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="13" colspan="4" align="center"><table width="398" border="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celda">
          <td width="98">&nbsp;</td>
          <td width="143"><div align="center"><strong>Tipo de Retencion </strong></div></td>
          <td width="106">&nbsp;</td>
          <td width="41">&nbsp;</td>
        </tr>
        <tr>
          <td><div align="right">IVA
            <input type="radio" name="opctipret" value="I"   <?php print $ls_disenb;?>>
          </div></td>
          <td height="22"><div align="center">
			    <div align="right">Impuesto Municipal
			      <input type="radio" name="opctipret" value="M" <?php print $ls_descmb;?>>
		        </div>
          </div></td>
          <td><div align="right">Aporte Social
            <input name="opctipret" type="radio" value="A" <?php print $ls_disaposol;?>>
          </div></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="48" colspan="4" align="center"><table width="398" border="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celda">
          <td colspan="4"><div align="center"><strong>Periodo</strong></div></td>
        </tr>
        <tr>
          <td height="22" colspan="2"><div align="right" >Fecha de Procesamiento</div></td>
          <td colspan="2"><input name="txtfecregdes" type="text" id="txtfecregdes" onBlur="javascript: ue_validar_formatofecha(this);"  onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" size="15" maxlength="10"  datepicker="true"></td>
          </tr>
        <tr>
          <td height="15">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="15" colspan="2"><div align="right">Orden de Pago </div></td>
          <td colspan="2"><div align="left">
            <input name="txtnumsol" type="text" id="txtnumsol" value="<?php print $ls_numsol;?>" size="25" maxlength="15">
            <a href="javascript:cat_sol();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></div></td>
          </tr>
        <tr>
          <td height="15">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="66" height="22"><div align="right">Mes
          </div></td>
          <td width="113"><div align="left">
            	<?php $io_retencion->uf_cmb_mes($ls_mes); //Combo que contiene los meses del año y retorna selecciona el que el ususario tenga acutalmente ?>
          </div></td>
          <td width="88"><div align="right">A&ntilde;o            </div></td>
          <td width="121"><div align="left">
            <input name="agno" type="text" id="agno" style="text-align:center " value="<?php print $ls_agno;?>" size="10" maxlength="4">
</div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="73" colspan="4" align="center">
        <table width="398" border="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celda">
            <td colspan="4" align="center"><strong>Proveedor / Beneficiario </strong></td>
          </tr>
          <tr>
            <td height="15" colspan="4" align="right">&nbsp;</td>
          </tr>
            <?php
 			if(($ls_modageret!="B")||($ls_conivaret!="B"))
			{
			?>
          <tr>
            <td height="22" colspan="4" align="right">
                  <div align="center">Proveedor
                    <input name="estprov" type="radio" value="P"  onClick="javascript:uf_cambio()" checked="checked">
                  Beneficiario
                  <input name="estprov" type="radio" value="B"  onClick="javascript:uf_cambio()" >
            </div>
			</td>
          </tr>
          <tr>
            <td height="15" colspan="4" align="right">&nbsp;</td>
          </tr>
          <tr>
            <td width="39" height="22" align="right">Desde</td>
            <td width="159" align="left"><input name="txtcodigo1" type="text" id="txtcodigo1">              <a href="javascript:uf_catalogo_proben('D');"><img src="../shared/imagebank/tools15/buscar.gif" name="buscar1" width="15" height="15" border="0"  id="buscar1" onClick="document.form1.hidrangocodigos.value=1"></a></td>
            <td width="43" align="right">Hasta</td>
            <td width="147" align="left"><input name="txtcodigo2" type="text" id="txtcodigo2">              <a href="javascript:uf_catalogo_proben('H');"><img src="../shared/imagebank/tools15/buscar.gif" name="buscar2" width="15" height="15" border="0" id="buscar2"  onClick="document.form1.hidrangocodigos.value=2"></a></td>
          </tr>
			<?php }?>
      </table>      </td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center">
        <p align="right"><a href="javascript:ue_procesar();"><strong>
          <span class="style14">
		  </span>
          
      </strong><img src="../shared/imagebank/tools20/ejecutar.gif" width="20" height="20" border="0">Ejecutar</a></p>        </td>
    </tr>
  </table> 
</table>
 <input name="hidopctipret" type="hidden" id="hidopctipret" value="<?php print $ls_hidopctipret?>">
 <input name="hidflag" type="hidden" id="hidflag" value="<?php print $ls_flag?>">
 <input name="hiddescmb" type="hidden" id="hiddescmb" value="<?php print $ls_descmb?>">
 <input name="hiddisenb" type="hidden" id="hiddisenb" value="<?php print $ls_disenb?>">
 <input name="hidrangocodigos" type="hidden" id="hidrangocodigos">
 <input name="hidcatalogo" type="hidden" id="hidcatalogo" value="0">
 <input name="operacion" type="hidden" id="operacion">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	
	function ue_nuevo()
	{
		  f=document.form1;
		  li_incluir=f.incluir.value;	
		  if(li_incluir==1)
		  {	
			  f.operacion.value="NEW";
			  f.hiddisenb.value="";
			  f.hiddescmb.value="";
			  f.action="sigesp_cxp_p_cmp_retencion.php";
			  f.submit();	  
		  }
		  else
		  {
			alert("No tiene permiso para realizar esta operacion");
		  }  
	}
	
	
	
	function ue_procesar()
	{
	    f=document.form1;
	    li_ejecutar=f.ejecutar.value;	
	    if(li_ejecutar==1)
	    {	
		    var valrad=getRadioButton();
		    
			if (valrad=="")
			 {
			   alert("Debe seleccionar el tipo de comprobante a procesar");
			 }
			 else
			 {
			   f.hidopctipret.value=valrad;
			   f.operacion.value="PROCESAR";
		       f.action="sigesp_cxp_p_cmp_retencion.php";
	  	       f.submit();
			 }
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}  
	}

	
	
	function uf_catalogo_proben(flag)
    {
    fop=document.form1;
	if (fop.estprov[0].checked==true)
	   {
	     fop.hidflag.value=flag;
		 ls_tipo="CMPRET";
		 pagina="sigesp_cxp_cat_proveedor.php?tipo="+ls_tipo;
	     window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=560,height=400,resizable=yes,location=no");
	   }
	else
	   {
	     if (fop.estprov[1].checked==true)
		    {
			  fop.hidflag.value=flag;
      		  ls_tipo="CMPRET";
		      pagina="sigesp_cxp_cat_beneficiario.php?tipo="+ls_tipo;
	          window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=560,height=400,resizable=yes,location=no");
		    }
	   }
    }
    
	function uf_cambio()
	{
		 f=document.form1;
		 f.txtcodigo1.value="";
		 f.txtcodigo2.value="";
	}
	
	function cargarcodpro(codpro)
	{
	  f=document.form1;
	  flag=f.hidflag.value;
	   if(flag=="D")
	    {
	      f.txtcodigo1.value=codpro;
	    }
	    else
	    {
	      f.txtcodigo2.value=codpro;
	    }
	}
	
	function cargarcedbene(codpro)
	{
	  f=document.form1;
	  flag=f.hidflag.value;
	   if(flag=="D")
	    {
	      f.txtcodigo1.value=codpro;
	    }
	    else
	    {
	      f.txtcodigo2.value=codpro;
	    }
	}
	
function getRadioButton()
 {
   var valor="";
   
   for (i=0; i < document.form1.opctipret.length; i++) {
     if (document.form1.opctipret[i].checked) {
       valor=document.form1.opctipret[i].value;
      }
   } 
   return valor;
 }

function validarmes()
{
  f=document.form1;
  f.operacion.value="VALIDARMES";
  f.action="sigesp_cxp_p_cmp_retencion.php";
  f.submit();
}

function cat_sol()
{
	f=document.formulario;
	pagina="sigesp_cxp_cat_sol_ret_iva.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
