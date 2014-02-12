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
	require_once("class_funciones_banco.php");
	$io_fun_scb=new class_funciones_banco();
	$io_fun_scb->uf_load_seguridad("SCB","sigesp_scb_p_modcmpret.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 19/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_estatus,$ls_dirsujret,$ls_nomsujret,$ls_operacion,$ls_existe,$ls_mes,$ls_indice;
		global $ls_totrowrecepciones,$io_fun_scb,$ls_numcom,$ls_parametros,$ls_rif,$ls_ano,$ls_codigo;
		
		$ls_estatus="EMITIDO";
		$ls_numcom="";
		$ls_ano="";
		$ls_mes="";
		$ls_codigo="";
		$ls_rif="";
		$ls_nomsujret="";
		$ls_dirsujret="";
		$ls_indice="";
		$ls_parametros="";
		$ls_operacion=$io_fun_scb->uf_obteneroperacion();
		$ls_existe=$io_fun_scb->uf_obtenerexiste();
		$ls_totrowrecepciones=0;
   }
   //--------------------------------------------------------------------------------------------------------------

   //--------------------------------------------------------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 23/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_estsol,$ls_numcom,$ls_ano,$ls_mes,$ls_codigo,$ls_rif,$ls_nomsujret,$ls_dirsujret,$ls_probene;
		global $li_totrowrecepciones,$ls_indice;
		
		$ls_estsol 	  = $_POST["txtestatus"];
		$ls_numcom	  = $_POST["txtnumcom"];
		$ls_mes		  = $_POST["cmbmes"];
		$ls_ano		  = $_POST["txtano"];
		$ls_codigo	  = $_POST["txtcodigo"];
		$ls_rif		  = $_POST["txtrif"];
		$ls_nomsujret = $_POST["txtnomsujret"];
		$ls_dirsujret = $_POST["txtdirsujret"];
		$ls_probene   = $_POST["txtprobene"];
		$ls_indice    = $_POST["txtindice"];
		$li_totrowrecepciones=$_POST["totrowrecepciones"];
   }
   //--------------------------------------------------------------------------------------------------------------

   //--------------------------------------------------------------------------------------------------------------
   function uf_load_data(&$as_parametros)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		global $li_totrowrecepciones,$io_fun_scb;	
			
		for($li_i=1;$li_i<$li_totrowrecepciones;$li_i++)
		{
			$ls_codret=trim($io_fun_scb->uf_obtenervalor("txtcodret".$li_i,""));
			$ls_numope=trim($io_fun_scb->uf_obtenervalor("txtnumope".$li_i,""));
			$ls_fecfac=trim($io_fun_scb->uf_obtenervalor("txtfecfac".$li_i,""));
			$ls_numfac=trim($io_fun_scb->uf_obtenervalor("txtnumfac".$li_i,""));
			$ls_numcon=trim($io_fun_scb->uf_obtenervalor("txtnumcon".$li_i,""));
			$ls_numnd=trim($io_fun_scb->uf_obtenervalor("txtnumnd".$li_i,""));
			$ls_numnc=trim($io_fun_scb->uf_obtenervalor("txtnumnc".$li_i,""));
			$ls_tiptrans=trim($io_fun_scb->uf_obtenervalor("txttiptrans".$li_i,""));
			$ls_tot_cmp_sin_iva=trim($io_fun_scb->uf_obtenervalor("txttotsiniva".$li_i,""));
			$ls_tot_cmp_con_iva=trim($io_fun_scb->uf_obtenervalor("txttotconiva".$li_i,""));
			$ls_basimp=trim($io_fun_scb->uf_obtenervalor("txtbasimp".$li_i,""));
			$ls_porimp=trim($io_fun_scb->uf_obtenervalor("txtporimp".$li_i,""));
			$ls_totimp=trim($io_fun_scb->uf_obtenervalor("txttotimp".$li_i,""));
			$ls_ivaret=trim($io_fun_scb->uf_obtenervalor("txtivaret".$li_i,""));
			$ls_numsop=trim($io_fun_scb->uf_obtenervalor("txtnumsop".$li_i,""));
			$ls_numdoc=trim($io_fun_scb->uf_obtenervalor("txtnumdoc".$li_i,""));
			
			$as_parametros=$as_parametros."&txtcodret".$li_i."=".$ls_codret."&txtnumope".$li_i."=".$ls_numope."&txtfecfac".$li_i."=".$ls_fecfac."&txtnumfac".$li_i."=".$ls_numfac."&txtnumcon".$li_i."=".$ls_numcon."&txtnumnd".$li_i."=".$ls_numnd."&txtnumnc".$li_i."=".$ls_numnc."&txttiptrans".$li_i."=".$ls_tiptrans."&txttotsiniva".$li_i."=".$ls_tot_cmp_sin_iva."&txttotconiva".$li_i."=".$ls_tot_cmp_con_iva."&txtbasimp".$li_i."=".$ls_basimp."&txtporimp".$li_i."=".$ls_porimp."&txttotimp".$li_i."=".$ls_totimp."&txtivaret".$li_i."=".$ls_ivaret."&txtnumsop".$li_i."=".$ls_numsop."&txtnumdoc".$li_i."=".$ls_numdoc;
		}
		$as_parametros=$as_parametros."&totrowrecepciones=".$li_totrowrecepciones."";
   }
   //--------------------------------------------------------------------------------------------------------------
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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Comprobante de Retenci&oacute;n</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="javascript"    src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset="><style type="text/css">
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {font-weight: bold}
.Estilo7 {
	font-size: 10px;
	color: #6699CC;
}
-->
</style></head>
<body>
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("class_folder/sigesp_scb_c_modcmpret.php");
require_once("class_folder/sigesp_scb_c_cmp_retencion.php");

$io_msg 	  = new class_mensajes();
$io_modcmpret = new sigesp_scb_c_modcmpret("../");
$io_cmpret	  = new sigesp_scb_c_cmp_retencion("../");
$io_include   = new sigesp_include();
$ls_conect    = $io_include->uf_conectar();
$io_sql		  = new class_sql($ls_conect);

uf_limpiarvariables();
switch($ls_operacion)
{
	case "NEW":
	  uf_load_variables();
	  $ls_ano=date('Y');
	  $ls_mes=date('m');
	  $io_cmpret->uf_get_nrocomprobante($ls_ano.$ls_mes,&$ls_numcom);
	  uf_load_data(&$ls_parametros);
	break;

	case "GUARDAR":
		uf_load_variables();
		$io_sql->begin_transaction();
		if($ls_existe=="FALSE")
		{
		  $ls_fecha=date('Y-m-d');
		  $lb_flag=$io_cmpret->uf_crear_comprobante($ls_numcom,$ls_fecha,$ls_ano.$ls_mes,$ls_codigo,$ls_nomsujret,$ls_dirsujret,$ls_rif,$la_seguridad);
		}
		else
		{
		  $lb_flag=true;
		}
		if($lb_flag)
		{
			 $lb_flag=$io_modcmpret->uf_liberar_recepciones($ls_numcom,$ls_probene,$ls_codigo);
		}
		if($lb_flag)
		{
		  $lb_flag=$io_modcmpret->uf_update_cmpret($ls_numcom,$li_totrowrecepciones,$ls_probene,$ls_codigo,$la_seguridad);
		}
		if($lb_flag)
		{
			$io_msg->message("El comprobante se procesó satisfactoriamente !!!");
			$io_sql->commit();
		}
		else
		{
			$io_msg->message("Ocurrio un error al procesar el comprobante !!!");
			$io_sql->rollback();
		}
		uf_load_data(&$ls_parametros);
		
	break;

	case "ELIMINAR":
		uf_load_variables();
		$io_sql->begin_transaction();
		$lb_ulitmo=$io_modcmpret->uf_buscar_ultimo($ls_numcom);		
		if($lb_ulitmo){
		   
		   $lb_flag=$io_modcmpret->uf_delete_cmpret($ls_numcom,$la_seguridad);
		   if($lb_flag)
			{
			  $lb_flag=$io_modcmpret->uf_liberar_rd($ls_probene,$ls_codigo,$li_totrowrecepciones);
			  if ($lb_flag)
			     {
				   $io_msg->message("El comprobante fué eliminado físicamente, por ser el último registro!!");
				   $io_sql->commit();
				   uf_limpiarvariables();
			     }
			  else
			  {
				$io_msg->message("Se genero un problema al eliminar la retención !!!");
				uf_limpiarvariables();
				$io_sql->rollback();
			  }
			}		   
		 }
		 else{
			$lb_anulado=$io_modcmpret->uf_anular_cmpret($ls_numcom,$la_seguridad);
			if($lb_anulado)
			 {
			   $io_msg->message("El comprobante fue anulado, ya que no es el ultimo registro!!");
			   uf_limpiarvariables();
				$io_sql->commit();
			 }
			 else
			 {
				$io_msg->message("Se genero un problema al anular la retencion");
				uf_limpiarvariables();
				$io_sql->rollback();
			 }
		 }
		uf_load_data(&$ls_parametros);
	break;
}
?>
<table width="778" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7"><span class="Estilo7">Caja y Banco</span></td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="21" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0" title="Nuevo"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" width="20" height="20" border="0" title="Guardar"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" title="Buscar"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0" title="Eliminar"></a></div></td>
    <td class="toolbar" width="22"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="23"><div align="center"><a href="javascript: ue_ayuda();"></a></div></td>
    <td class="toolbar" width="624">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
  <form name="formulario" method="post" action="" id="formulario">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_scb->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_scb);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <table width="726" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="790"  height="136"><p>&nbsp;</p>
            <table width="721" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td colspan="4" class="titulo-ventana"> Comprobante de Retenci&oacute;n </td>
              </tr>
              <tr>
                <td height="13">&nbsp;</td>
                <td height="13">&nbsp;</td>
                <td height="13">&nbsp;</td>
                <td height="13">&nbsp;</td>
              </tr>
              <tr>
                <td width="147" height="22" style="text-align:right">Periodo</td>
                <td width="240" height="22"><label>
                  <select name="cmbmes" id="cmbmes">
          <option value="01" <?php if($ls_mes=="01"){ print "selected";} ?>>ENERO</option>
          <option value="02" <?php if($ls_mes=="02"){ print "selected";} ?>>FEBRERO</option>
          <option value="03" <?php if($ls_mes=="03"){ print "selected";} ?>>MARZO</option>
          <option value="04" <?php if($ls_mes=="04"){ print "selected";} ?>>ABRIL</option>
          <option value="05" <?php if($ls_mes=="05"){ print "selected";} ?>>MAYO</option>
          <option value="06" <?php if($ls_mes=="06"){ print "selected";} ?>>JUNIO</option>
          <option value="07" <?php if($ls_mes=="07"){ print "selected";} ?>>JULIO</option>
          <option value="08" <?php if($ls_mes=="08"){ print "selected";} ?>>AGOSTO</option>
          <option value="09" <?php if($ls_mes=="09"){ print "selected";} ?>>SEPTIEMBRE</option>
          <option value="10" <?php if($ls_mes=="10"){ print "selected";} ?>>OCTUBRE</option>
          <option value="11" <?php if($ls_mes=="11"){ print "selected";} ?>>NOVIEMBRE</option>
          <option value="12" <?php if($ls_mes=="12"){ print "selected";} ?>>DICIEMBRE</option>
        </select>
                  <input name="txtano" type="text" id="txtano" size="10" maxlength="4" value="<?php print $ls_ano?>">
                </label></td>
                <td width="142" height="22" style="text-align:right">Estatus</td>
                <td width="190" height="22"><input name="txtestatus" type="text" class="sin-borde2" id="txtestatus" value="<?php print $ls_estatus; ?>" size="20" readonly></td>
              </tr>
              <tr>
                <td height="22" align="right">Comprobante </td>
                <td height="22" align="left"><input name="txtnumcom" type="text" id="txtnumcom" value="<?php print $ls_numcom; ?>" readonly></td>
                <td height="22" align="right">&nbsp;</td>
                <td height="22" align="right">&nbsp;</td>
              </tr>
              
              <tr>
                <td height="13">&nbsp;</td>
                <td colspan="3">&nbsp;</td>
              </tr>
              <tr>
                <td height="13" colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="formato-blanco">
                  <tr>
                    <td colspan="4" class="titulo-ventana">Sujeto Retenci&oacute;n</td>
                  </tr>
                  <tr>
                    <td height="13" colspan="4">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="19%" height="25" style="text-align:right"><?php 
					    if($ls_operacion=="NEW")
                         {
						  print"<div align=center>Proveedor
                                <input name=estprov type=radio value=P>
                                Beneficiario
                                <input name=estprov type=radio value=B>
                                </div>";
						 }
					  ?>
                    C&oacute;digo</td>
                    <td width="24%"><div align="left">
                      <label>
                      <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codigo?>" maxlength="10" readonly style="text-align:center">
					  <?php 
					    if($ls_operacion=="NEW")
                         {
						   print"<a href=javascript:uf_catalogo_proben('D');><img src=../shared/imagebank/tools15/buscar.gif name=buscar1 width=15 height=15 border=0 id=buscar1></a>";
						 }
					  ?>
                      </label>
                    </div></td>
                    <td width="25%" style="text-align:right">RIF</td>
                    <td width="32%"><label>
                      <input name="txtrif" type="text" id="txtrif" value="<?php print $ls_rif?>" maxlength="12" readonly style="text-align:center">
                    </label></td>
                  </tr>
                  <tr>
                    <td height="23" style="text-align:right">Nombre</td>
                    <td colspan="3"><label>
                      <input name="txtnomsujret" type="text" id="txtnomsujret" size="100" maxlength="100" value="<?php print $ls_nomsujret?>" readonly>
                    </label></td>
                  </tr>
                  <tr>
                    <td height="22" style="text-align:right">Direcci&oacute;n</td>
                    <td colspan="3"><label>
                      <input name="txtdirsujret" type="text" id="txtdirsujret" size="100" value="<?php print $ls_dirsujret?>" readonly>
                    </label></td>
                  </tr>
				  <tr>
                   <td height="13" colspan="4" align="center"></td>
                 </tr>
                  
                </table></td>
              </tr>
            </table>
            
            <p align="center">
          <input name="operacion"  type="hidden" id="operacion"  value="<?php print $ls_operacion;?>">
          <input name="existe"     type="hidden" id="existe"     value="<?php print $ls_existe;?>">
          <input name="estapr"     type="hidden" id="estapr"     value="<?php print $li_estaprord;?>">
          <input name="parametros" type="hidden" id="parametros" value="<?php print $ls_parametros;?>">
          <input name="txttipdes"  type="hidden" id="txttipdes"  value="<?php print $ls_tipodestino; ?>">
          <input name="txtfecha"   type="hidden" id="txtfecha"   value="<?php print $ld_fecemisol; ?>">
		  <input name="txtmes"   type="hidden" id="txtmes"   value="<?php print $ls_mes; ?>">
          <input name="totrowrecepciones" type="hidden" id="totrowrecepciones" value="<?php print $li_totrowrecepciones;?>">
          <input name="formato"    type="hidden" id="formato"    value="<?php print $ls_reporte; ?>">
          <input name="txtprobene" type="hidden" id="txtprobene" value="<?php print $ls_probene; ?>">
          <input name="txtindice" type="hidden" id="txtindice" value="<?php print $ls_indice; ?>">
          <input name="hidfilsel" type="hidden" id="hidfilsel">
          <input name="conivaret" type="hidden" id="conivaret" value="<?php print $_SESSION["la_empresa"]["estretiva"]; ?>">
          <input name="modageret" type="hidden" id="modageret" value="<?php print $_SESSION["la_empresa"]["modageret"]; ?>">
          </p></td>
      </tr>
    </table>
	<br>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
                <td><div id="detalles" align="center"></div></td>
     </tr>
    </table>
</form>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
f = document.formulario;
function ue_nuevo()
{
	li_modageret=f.modageret.value;
	li_conivaret=f.conivaret.value;
	if((li_modageret!="C")||(li_conivaret!="C"))
	{
		li_incluir=f.incluir.value;
		if(li_incluir==1)
		{	
			f.txtcodigo.value = "";
			f.txtnomsujret.value = "";
			f.txtdirsujret.value = "";
			f.txtrif.value = "";
			f.operacion.value="NEW";
			f.existe.value="FALSE";		
			f.action="sigesp_scb_p_modcmpret.php";
			f.submit();
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("Los dos comprobantes se manejan por el Módulo de Cuentas Por Pagar !!!");
	}
}

function ue_buscar()
{
	f=document.formulario;
	li_leer=f.leer.value;
	li_conivaret=f.conivaret.value;
	/*if (li_conivaret!="C")
	   {
	     if (li_leer==1)
		    {*/
			  window.open("sigesp_scb_cat_cmpretiva.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,left=50,top=50,location=no,resizable=yes");
		   /* }
		 else
		    {
			  alert("No tiene permiso para realizar esta operacion");
		    }
 	   }
	else
	   {
		 alert("Los comprobantes de Retenciones IVA se manejan por el Módulo de Cuentas por Pagar !!!");
	   }*/
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_cargardatos(ls_numcom,ls_anofiscal,ls_mesfiscal,ls_codsujret,ls_nomsujret,ls_dirsujret,ls_rifsujret,ls_probene,ls_estcmpret)
{
 f=document.formulario;
 f.txtnumcom.value=ls_numcom;
 f.txtano.value=ls_anofiscal;
 f.cmbmes.value=ls_mesfiscal;
 f.txtcodigo.value=ls_codsujret;
 f.txtnomsujret.value=ls_nomsujret;
 f.txtdirsujret.value=ls_dirsujret;
 f.txtrif.value=ls_rifsujret;
 f.txtprobene.value=ls_probene;
 if(ls_estcmpret==1)
 {
 	f.txtestatus.value="EMITIDO";
 }
 else
 {
 	f.txtestatus.value="ANULADO";
 }
 f.existe.value="TRUE";
}

function ue_insert_row()
{
	f=document.formulario;
	parametros="";
	total=ue_calcular_total_fila_local("txtnumope");
	f.totrowrecepciones.value=total;
	rowrecepciones=f.totrowrecepciones.value;
	for (j=1;(j<rowrecepciones);j++)
		{
		  ls_codret			 = eval("document.formulario.txtcodret"+j+".value");
		  ls_numope			 = eval("document.formulario.txtnumope"+j+".value");
		  ls_fecfac			 = eval("document.formulario.txtfecfac"+j+".value");
		  ls_numfac			 = eval("document.formulario.txtnumfac"+j+".value");
		  ls_numcon			 = eval("document.formulario.txtnumcon"+j+".value");
		  ls_numnd			 = eval("document.formulario.txtnumnd"+j+".value");
		  ls_numnc			 = eval("document.formulario.txtnumnc"+j+".value");
		  ls_tiptrans		 = eval("document.formulario.txttiptrans"+j+".value");
		  ls_tot_cmp_sin_iva = eval("document.formulario.txttotsiniva"+j+".value");
		  ls_tot_cmp_con_iva = eval("document.formulario.txttotconiva"+j+".value");
		  ls_basimp			 = eval("document.formulario.txtbasimp"+j+".value");
		  ls_porimp			 = eval("document.formulario.txtporimp"+j+".value");
		  ls_totimp			 = eval("document.formulario.txttotimp"+j+".value");
		  ls_ivaret			 = eval("document.formulario.txtivaret"+j+".value");
		  ls_numsop			 = eval("document.formulario.txtnumsop"+j+".value");
		  ls_numdoc			 = eval("document.formulario.txtnumdoc"+j+".value");
		  parametros		 = parametros+"&txtcodret"+j+"="+ls_codret+"&txtnumope"+j+"="+ls_numope+"&txtfecfac"+j+"="+ls_fecfac+"&txtnumfac"+j+"="+ls_numfac+"&txtnumcon"+j+"="+ls_numcon+"&txtnumnd"+j+"="+ls_numnd+"&txtnumnc"+j+"="+ls_numnc+"&txttiptrans"+j+"="+ls_tiptrans+"&txttotsiniva"+j+"="+ls_tot_cmp_sin_iva+"&txttotconiva"+j+"="+ls_tot_cmp_con_iva+"&txtbasimp"+j+"="+ls_basimp+"&txtporimp"+j+"="+ls_porimp+"&txttotimp"+j+"="+ls_totimp+"&txtivaret"+j+"="+ls_ivaret+"&txtnumsop"+j+"="+ls_numsop+"&txtnumdoc"+j+"="+ls_numdoc;
		}
	j++;	
	totalrecepciones=eval(j);
	f.totrowrecepciones.value=totalrecepciones;
	parametros=parametros+"&totrowrecepciones="+totalrecepciones;	
	if (parametros!="")
	   {
	     divgrid = document.getElementById("detalles");
		 ajax    = objetoAjax();
		 ajax.open("POST","class_folder/sigesp_scb_c_modcmpret_ajax.php",true);
	 	 ajax.onreadystatechange=function() {
		 if (ajax.readyState==4) {
	 		divgrid.innerHTML = ajax.responseText
		    }
	     }
		 ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		 ajax.send("proceso=AGREGARCMPRETINS"+parametros);
       }
}

function ue_delete_detalle(fila)
{
	f=document.formulario;
	if(confirm("¿Desea eliminar el Registro actual?"))
		{
			valido=true;
			parametros="";
			total=ue_calcular_total_fila_local("txtnumope");
			f.totrowrecepciones.value=total;
			rowrecepciones=f.totrowrecepciones.value;
			li_i=1;
			
			for(j=1;(j<rowrecepciones)&&(valido);j++)
			{
				if(j!=fila)
				{
					
					ls_codret=eval("document.formulario.txtcodret"+j+".value");
			        ls_numope=eval("document.formulario.txtnumope"+j+".value");
			        ls_fecfac=eval("document.formulario.txtfecfac"+j+".value");
			        ls_numfac=eval("document.formulario.txtnumfac"+j+".value");
			        ls_numcon=eval("document.formulario.txtnumcon"+j+".value");
			        ls_numnd=eval("document.formulario.txtnumnd"+j+".value");
			        ls_numnc=eval("document.formulario.txtnumnc"+j+".value");
			        ls_tiptrans=eval("document.formulario.txttiptrans"+j+".value");
			        ls_tot_cmp_sin_iva=eval("document.formulario.txttotsiniva"+j+".value");
			        ls_tot_cmp_con_iva=eval("document.formulario.txttotconiva"+j+".value");
			        ls_basimp=eval("document.formulario.txtbasimp"+j+".value");
			        ls_porimp=eval("document.formulario.txtporimp"+j+".value");
			        ls_totimp=eval("document.formulario.txttotimp"+j+".value");
			        ls_ivaret=eval("document.formulario.txtivaret"+j+".value");
			        ls_numsop=eval("document.formulario.txtnumsop"+j+".value");
			        ls_numdoc=eval("document.formulario.txtnumdoc"+j+".value");
		            
					parametros=parametros+"&txtcodret"+li_i+"="+ls_codret+"&txtnumope"+li_i+"="+ls_numope+"&txtfecfac"+li_i+"="+ls_fecfac+"&txtnumfac"+li_i+"="+ls_numfac+"&txtnumcon"+li_i+"="+ls_numcon+"&txtnumnd"+li_i+"="+ls_numnd+"&txtnumnc"+li_i+"="+ls_numnc+"&txttiptrans"+li_i+"="+ls_tiptrans+"&txttotsiniva"+li_i+"="+ls_tot_cmp_sin_iva+"&txttotconiva"+li_i+"="+ls_tot_cmp_con_iva+"&txtbasimp"+li_i+"="+ls_basimp+"&txtporimp"+li_i+"="+ls_porimp+"&txttotimp"+li_i+"="+ls_totimp+"&txtivaret"+li_i+"="+ls_ivaret+"&txtnumsop"+li_i+"="+ls_numsop+"&txtnumdoc"+li_i+"="+ls_numdoc;
					li_i=li_i+1;	
				}
			}
			totalrecepciones=eval(li_i);
			f.totrowrecepciones.value=totalrecepciones;
			parametros=parametros+"&totrowrecepciones="+totalrecepciones;	
			
			
			if((parametros!="")&&(valido))
			{
				divgrid = document.getElementById("detalles");
				ajax=objetoAjax();
				ajax.open("POST","class_folder/sigesp_scb_c_modcmpret_ajax.php",true);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4) {
						divgrid.innerHTML = ajax.responseText
					}
				}
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajax.send("proceso=AGREGARCMPRET"+parametros);
			}
		}
	}


function ue_guardar()
{
	f=document.formulario;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		valido=true;		
		// Obtenemos el total de filas de los Conceptos
		total=ue_calcular_total_fila_local("txtnumope");
		f.totrowrecepciones.value=total;
		numcom=ue_validarvacio(f.txtnumcom.value);
		if(valido)
		{
			valido=ue_validarcampo(f.txtnumcom.value,"Debe seleccionar un Comprobante.",f.txtnumsol);
		}
		if(valido)
		{
			valido=ue_validarcampo(f.txtcodigo.value,"Debe seleccionar un Proveedor / Beneficiario.",f.txtnumsol);
		}
		if(valido)
		{
			rowrecepciones=f.totrowrecepciones.value;
			for(row=1;row<rowrecepciones;row++)
			{
				numfac= eval("f.txtnumfac"+row+".value");
				if(numfac=="")
				{
					alert("No se permite numeros de factura en Blanco.");
					valido=false;
					break;
				}
			}		
			if(rowrecepciones<=1)
			{
				alert("El comprobante debe tener al menos un detalle.");
				valido=false;
			}
		}
		if(valido)
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_scb_p_modcmpret.php";
			f.submit();		
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación !!!");
   	}
}	

function uf_load_otros_creditos(li_row)
{
  f=document.formulario;
  ld_basimp = eval("f.txtbasimp"+li_row+".value");
  if ((ld_basimp!="0,00") && (ld_basimp!=""))	  
	 {	 
	   ld_subtotal = eval("f.txttotsiniva"+li_row+".value");
	   f.hidfilsel.value = li_row;
	   pagina="sigesp_scb_pdt_otroscreditos.php?tipo=CMPRET&subtotal="+ld_subtotal;
	   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=600,resizable=yes,location=no");	  
  	 }
  else
	 {
	   alert("Para que pueda seleccionar un cargo la Base Imponible debe ser distinta de cero !!!");
	 }
}

function uf_load_retenciones(li_row)
{
  f         = document.formulario;
  f.hidfilsel.value = li_row;
  ld_porcar = eval("f.txtporimp"+li_row+".value");
  if ((ld_porcar!="0,00") && (ld_porcar!=""))
	 {
	   window.open("sigesp_scb_pdt_deducciones.php?tipo=CMPRETIVA","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=600,resizable=yes,location=no");	  
	 }  
  else
	 {
	   alert("Para que pueda seleccionar un deducción el porcentaje debe ser distinto a cero !!!");
	 }
  } 
  
function ue_reload()
{
	f=document.formulario;
	parametros=f.parametros.value;
	proceso="AGREGARCMPRET";
	if(parametros!="")
	{
		divgrid = document.getElementById("detalles");
		ajax=objetoAjax();
		ajax.open("POST","class_folder/sigesp_scb_c_modcmpret_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("proceso="+proceso+""+parametros);
	}
}  

function ue_eliminar()
{
    f=document.formulario;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
    if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		valido=true;
		// Obtenemos el total de filas de los Conceptos
		total=ue_calcular_total_fila_local("txtnumope");
		f.totrowrecepciones.value=total;
		numcom=ue_validarvacio(f.txtnumcom.value);
		if(valido)
		{
			valido=ue_validarcampo(numcom,"Debe seleccionar un Comprobante.",f.txtnumsol);
		}
		if(valido)
		{
			rowrecepciones=f.totrowrecepciones.value;
			if(rowrecepciones<=1)
			{
				alert("El comprobante debe tener al menos un detalle.");
				valido=false;
			}
		}
		if(valido)
		{
			if(confirm("¿Realmente desea inutilizar este registro?"))
			 {
			   f.operacion.value="ELIMINAR";
			   f.action="sigesp_scb_p_modcmpret.php";
			   f.submit();		
			 }
			 else
			 {
		       alert("Operación Cancelada!!!");	  
			 }  
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación.");
   	}
}

function ue_cat_solicitud(li_fila)
{
  f=document.formulario;
  f.txtindice.value=li_fila;
  ls_catalogo="sigesp_cat_mov_chq_cmp_ret.php?tiporeporte=MODCMPRET";
  window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

function uf_catalogo_proben()
{
  if (f.estprov[0].checked==true)
	 {
	   f.txtprobene.value="P";
	   pagina="sigesp_scb_cat_proveedor.php?tipo=MODCMPRET";
	   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=560,height=400,resizable=yes,location=no");
	 }
  else
	 {
	   if (f.estprov[1].checked==true)
		  {
		    ls_tipo = "MODCMPRET";
	        f.txtprobene.value="B";
			pagina="sigesp_scb_cat_beneficiario.php?tipo=MODCMPRET";
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=560,height=400,resizable=yes,location=no");
		  }
	 }
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<?php
if(($ls_operacion=="GUARDAR"))
{
	print "<script language=JavaScript>";
	print "   ue_reload();";
	print "</script>";
}
if($ls_operacion=="NEW")
{
	print "<script language=JavaScript>";
	print "   ue_insert_row();";
	print "</script>";
}
?>		  
</html>