<?php
	session_start();
  	unset($_SESSION["parametros"]);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("../../../class_folder/utilidades/class_funciones_srh.php");
require_once("../../../class_folder/utilidades/class_srh.php");
$io_fun_srh=new class_funciones_srh('../../../../');
$io_class_srh=new class_srh('../../../../');
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_evaluacion_desempeno.php",$ls_permisos,$la_seguridad,$la_permisos);
require_once("../../../class_folder/utilidades/class_funciones_nomina.php");
$io_fun_nomina=new class_funciones_nomina();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
 $ls_reporte=$io_class_srh->uf_select_config("SRH","REPORTE","REGISTRO_ODI","sigesp_srh_rpp_registro_evaluacion_desempeno.php","C");

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

 function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		

   		global $ls_nroeval, $ls_codper,$ls_nomper,$ls_carper, $ls_codcarper, $ls_fecha, $ls_codeval, $ls_deneval,$ls_fecini,$ls_fecfin,$ls_codeva,$ls_nomeva,$ls_codcareva, $ls_codsup,$ls_nomsup,$ls_codcarsup, $ls_ranact,$li_resodi, $li_rescom, $li_total, $ls_obs, $ls_opi,$ls_activarcodigo,$ls_operacion,$ls_guardar,$ls_existe,$ls_existe2,$io_fun_nomina;		
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$ls_anoserpre, $li_totrows2,$ls_titletable2,$li_widthtable2,$ls_nametable2,$lo_title2;
	 	$ls_codper="";
		$ls_nomper="";
		$ls_carper="";
		$ls_nroeval="";
		$ls_fecha="";
		$ls_fecini="";
		$ls_fecfin="";
		$ls_codeval="";
		$ls_deneval="";
		$ls_codeva="";
		$ls_codcarper="";
		$ls_nomeva="";
		$ls_codcareva="";
		$ls_codsup="";
		$ls_nomsup="";
		$ls_codcarsup="";
		$li_resodi=0;
		$li_rescom=0;
		$li_total=0;
		$ls_ranact="";
		$ls_obs="";
		$ls_opi="";
		$ls_activarcodigo="";
		$ls_titletable="Evaluación de Objetivos de Desempeño Individual (O.D.I)";
		$li_widthtable=650;
		$ls_nametable="grid";
		$lo_title[1]="Objetivo de Desempeño Individual";
		$lo_title[2]="Peso";
		$lo_title[3]="Rango (1 - 5)";
		$lo_title[4]="Peso x Rango";
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
		
		$ls_titletable2="Competencias";
		$li_widthtable2=650;
		$ls_nametable2="grid2";
		$lo_title2[1]="Código";
		$lo_title2[2]="Denominación";
		$lo_title2[3]="Peso";
		$lo_title2[4]="Rango (1 - 5)";
		$lo_title2[5]="Peso x Rango";
		$li_totrows2=$io_fun_nomina->uf_obtenervalor("totalfilas2",1);
		$ls_existe2=$io_fun_nomina->uf_obtenerexiste();
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<textarea name=txtodi".$ai_totrows."  cols=60 rows=3 id=txtodi".$ai_totrows." class=sin-borde readonly></textarea>";
		$aa_object[$ai_totrows][2]="<textarea name=txtvalor".$ai_totrows."    cols=6 rows=3 id=txtvalor".$ai_totrows."  class=sin-borde readonly> </textarea>";
		$aa_object[$ai_totrows][3]="<textarea name=txtrango".$ai_totrows."    cols=6 rows=3 id=txtrango".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);' onChange='javascript: ue_chequear_numero(this); ue_multiplicar(this,txtvalor".$ai_totrows." , txtpesran".$ai_totrows."); sumar_odi(txtresodi,txtrescom,txttotal);' > </textarea>";
		$aa_object[$ai_totrows][4]="<textarea name=txtpesran".$ai_totrows."    cols=6 rows=3 id=txtpesran".$ai_totrows."  class=sin-borde readonly> </textarea>";			
   }
   
    function uf_agregarlineablanca_competencia(&$aa_object1,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object1[$ai_totrows][1]="<textarea name=txtcodite".$ai_totrows."  cols=15 rows=3 id=txtcodite".$ai_totrows." class=sin-borde readonly></textarea>";
		$aa_object1[$ai_totrows][2]="<textarea name=txtdenite".$ai_totrows."    cols=35 rows=3 id=txtdenite".$ai_totrows."  class=sin-borde readonly> </textarea>";
		$aa_object1[$ai_totrows][3]="<textarea name=txtpeso".$ai_totrows."    cols=6 rows=3 id=txtpeso".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);'  onChange='javascript: ue_multiplicar(this,txtrangoc".$ai_totrows.",txtpesranc".$ai_totrows."); sumar_competencias(txtrescom,txtresodi,txttotal);' > </textarea>";
		$aa_object1[$ai_totrows][4]="<textarea name=txtrangoc".$ai_totrows."    cols=6 rows=3 id=txtrangoc".$ai_totrows."  class=sin-borde onKeyUp='javascript: ue_validarnumero(this);' onChange='javascript: ue_chequear_numero(this); ue_multiplicar(this,txtpeso".$ai_totrows.",txtpesranc".$ai_totrows."); sumar_competencias(txtrescom,txtresodi,txttotal);'> </textarea>";
		$aa_object1[$ai_totrows][5]="<textarea name=txtpesranc".$ai_totrows."    cols=6 rows=3 id=txtpesranc".$ai_totrows."  class=sin-borde readonly > </textarea>";	
		
				
   }
   
   
?>
	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>SIGESP - Sistema Integrado de Gesti&oacute;n para Entes del Sector P&uacute;blico</title>



<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #f3f3f3;
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
.Estilo25 {
	color: #6699CC;
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
}
-->
</style>

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_evaluacion_desempeno.js"></script>

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

<body onLoad="javascript: ue_nuevo_codigo();">

<?php 
	require_once("../../../class_folder/dao/sigesp_srh_c_evaluacion_desempeno.php");
	$io_obj=new sigesp_srh_c_evaluacion_desempeno("../../../../");
	
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			$li_totrows2=1;
			uf_agregarlineablanca($lo_object,1);
			uf_agregarlineablanca_competencia($lo_object1,1);
			break;

			
		case "BUSCARDETALLE":
		    $ls_nroeval=$_POST["txtnroeval"];
		    $ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_fecini=$_POST["txtfecini"];
			$ls_fecfin=$_POST["txtfecfin"];
			$ls_codcarper=$_POST["txtcodcarper"];
			$ls_codeva=$_POST["txtcodeva"];
			$ls_nomeva=$_POST["txtnomeva"];
			$ls_codcareva=$_POST["txtcodcareva"];
			$ls_codsup=$_POST["txtcodsup"];
			$ls_codeval=$_POST["txtcodeval"];
    		$ls_deneval=$_POST["txtdeneval"];
			$ls_nomsup=$_POST["txtnomsup"];
			$li_resodi=$_POST["txtresodi"];
			$li_rescom=$_POST["txtrescom"];
			$li_total=$_POST["txttotal"];
			$ls_ranact=$_POST["txtranact"];
			$ls_obs=$_POST["txtobs"];
			$ls_opi=$_POST["txtopi"];
			$ls_codcarsup=$_POST["txtcodcarsup"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_obj->uf_load_evaluacion_desempeno_competencia($ls_nroeval,$li_totrows2,$lo_object1);
			$lb_valido1=$io_obj->uf_load_evaluacion_desempeno_odi($ls_nroeval,$ls_codper,$ls_fecini,$ls_fecfin,$li_totrows,$lo_object);
			
			break;
			
		 case "CONSULTAR":
		 	 $ls_nroeval=$_POST["txtnroeval"];
		    $ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_fecha=$_POST["txtfecha"];
			$ls_fecini=$_POST["txtfecini"];
			$ls_fecfin=$_POST["txtfecfin"];
			$ls_codcarper=$_POST["txtcodcarper"];
			$ls_codeva=$_POST["txtcodeva"];
			$ls_nomeva=$_POST["txtnomeva"];
			$ls_codcareva=$_POST["txtcodcareva"];
			$ls_codsup=$_POST["txtcodsup"];
			$ls_codeval=$_POST["txtcodeval"];
    		$ls_deneval=$_POST["txtdeneval"];
			$ls_nomsup=$_POST["txtnomsup"];
			$li_resodi=$_POST["txtresodi"];
			$li_rescom=$_POST["txtrescom"];
			$li_total=$_POST["txttotal"];
			$ls_ranact=$_POST["txtranact"];
			$ls_obs=$_POST["txtobs"];
			$ls_opi=$_POST["txtopi"];
			$ls_codcarsup=$_POST["txtcodcarsup"];
			$ls_guardar=$_POST["hidguardar"];
			$ls_activarcodigo="readOnly";
			$lb_valido=$io_obj->uf_srh_consultar_evaluacion_desempeno($ls_codper,$ls_fecini,$ls_fecfin,$li_totrows,$lo_object,$ls_nomper);
			$lb_valido=$io_obj->uf_srh_consultar_items($ls_codeval,$li_totrows2,$lo_object1);
			break;	
			
			
	}
	
	unset($io_obj);
?>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../../../public/imagenes/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo25">Sistema de Recursos Humanos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
 <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../../js/menu/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>

  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../../../public/imagenes/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../../../public/imagenes/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../../../public/imagenes/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../../../public/imagenes/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../../public/imagenes/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../../../public/imagenes/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_print();"><img src="../../../../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php

	

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		}

	
	
	//
?>

<p>&nbsp;</p>

<form name="form1" method="post" action=""  >
 <div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
      <table width="681" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="859" height="136"><p>
      <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    </p>
      <p>&nbsp;</p>
      <table width="646" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
        <tr class="titulo-nuevo">
          <td height="22" colspan="10">Evaluaci&oacute;n de Desempe&ntilde;o</td>
        </tr>
		 <tr class="titulo-celda">
          <td height="18" colspan="10">Datos del Evaluado</td>
        </tr>
        <tr>
          <td width="87" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
       <tr>
          <td height="22" align="left"><div align="right">C&oacute;digo Personal</div></td>
          <td height="22"  colspan="3"><input name="txtcodper" type="text" id="txtcodper" value="<? print $ls_codper?>" maxlength="10" size="16"  style="text-align:center"  readonly >  <a href="javascript:catalogo_personal();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
            </td>
       </tr>
	    <tr>
			  <td height="22" align="left"><div align="right">Nombre</div></td>
          <td height="22" colspan="6"><input name="txtnomper" type="text" id="txtnomper" value="<? print $ls_nomper?>" maxlength="40" style="text-align:justify" size="50" readonly  >             </td>
		  </tr>	
		  	
	   
	    <tr>
			  <td height="22" align="left"><div align="right">Cargo</div></td>
          <td height="22" colspan="6"><input name="txtcodcarper" type="text" id="txtcodcarper" value="<? print $ls_codcarper?>"  style="text-align:justify" size="50" readonly >                  </td>
		  </tr>	
		      <tr> 
 <td height="28"><div align="right"> Tipo de Evaluaci&oacute;n</div></td>
  <td height="28"  colspan="4"><input name="txtcodeval" type="text" id="txtcodeval" value="<?php print $ls_codeval ?>" size="16" maxlength="15"  style="text-align:center" readonly>
          <a href="javascript: catalogo_evaluacion();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo  Evaluaci&oacute;n" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>   
           <input name="txtdeneval" type="text" class="sin-borde" id="txtdeneval" value="<?php print $ls_deneval ?>" size="50
          " maxlength="80" readonly>          </td>
    </tr>
		<tr>
          <td width="87" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
       <tr>  	
	 <tr class="titulo-nuevo">
		   <td height="22" colspan="10">Per&iacute;odo de Evaluaci&oacute;n</td>
        </tr>
		<tr>
          <td width="87" height="22">&nbsp;</td>
          
          <td height="22" colspan="8">&nbsp;</td>
        </tr>
		<tr>
		 <td height="22"><div align="right">Del</div></td>
          <td height="22" colspan="2"><input name="txtfecini" type="text" id="txtfecini"  maxlength="15" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecini?>">    <input name="reset" type="reset" onClick="return showCalendar('txtfecini', '%d/%m/%Y');" value=" ... " />         </td> 			
          <td width="42"  height="22"><div align="right">Al</div></td>
          <td width="196"><input name="txtfecfin" type="text" id="txtfecfin"  maxlength="15" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecfin?>">        <input name="reset" type="reset" onClick="return showCalendar('txtfecfin', '%d/%m/%Y');" value=" ... " />    </td>	   
	    </tr>
		
		<tr>
		<td height="22"><div align="right"></div></td>
		<td height="22" colspan="2"><div align="right"><a href="javascript: Limpiar_Datos();"><img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar Datos</a></div></td>

	    <td height="22" colspan="2"><a href="javascript: Consultar();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Consultar Datos</a></td>
	    <td height="22" colspan="2">&nbsp;</td>
	    <td width="4" colspan="2">&nbsp;</td>
		</tr>
		 <tr class="titulo-nuevo">
		   <td height="22" colspan="10">Datos de la Evaluaci&oacute;n</td>
        </tr>
		
		 <tr>
		  <td height="22" align="left"><div align="right">Nro. Evaluaci&oacute;n</div></td>
          <td height="22" colspan="6"><input name="txtnroeval" type="text" id="txtnroeval" value="<? print $ls_nroeval?>" maxlength="15" style="text-align:center" size="15" readonly >               </td>
		  </tr>
		  	<tr>
		 <td height="22"><div align="right">Fecha Evaluaci&oacute;n</div></td>
          <td height="22" colspan="5"><input name="txtfecha" type="text" id="txtfecha" size="16"  maxlength="15" style="text-align:center" datepicker="true" readonly value="<?php print $ls_fecha?>"> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecha', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
		  
		
	 
		 
		
		
		
		<tr>
          <td><div align="right"></div></td>
          <td width="210"><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
			<input name="existe2" type="hidden" id="existe2" value="<?php print $ls_existe2;?>"></td>
        </tr>
		
		
	   
		<tr>
          <td width="87" height="22">&nbsp;</td>
          
          <td height="22" colspan="8">&nbsp;</td>
        </tr>
		 <tr class="titulo-nuevo">
          <td height="22" colspan="10">Datos del Evaluador</td>
        </tr>
        <tr>
          <td width="87" height="22">&nbsp;</td>
          
          <td height="22" colspan="4">&nbsp;</td>
        <tr>
			  <td height="22" align="left"><div align="right">C&oacute;digo Evaluador</div></td>
          <td height="22" colspan="2"><input name="txtcodeva" type="text" id="txtcodeva"  maxlength="10" style="text-align:center"  readonly value="<? print $ls_codeva?>" >   <a href="javascript:catalogo_evaluador();"><img src="../../../../shared/imagebank/tools15/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
		  </tr>
		 		       
        <tr>
		  <td height="22" align="left"><div align="right">Nombre</div></td>
          <td height="22" colspan="2"><input name="txtnomeva" type="text" id="txtnomeva"   style="text-align:justify" size="50" readonly value="<? print $ls_nomeva?>">           </td>
        </tr>
         <tr>
             <td height="22"><div align="right">Cargo </div></td>
          <td height="22" colspan="3" valign="middle"><input name="txtcodcareva" type="text" id="txtcodcareva" size="50" value="<? print $ls_codcareva?>" readonly>  
          </a></td>
        </tr>
		<tr>
          <td height="22" colspan="4">&nbsp;</td>
        </tr>
		 <tr class="titulo-nuevo">
          <td height="22" colspan="10">Datos del Supervisor </td>
        </tr>
        <tr>
          <td width="87" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		  <tr>
			  <td height="22" align="left"><div align="right">C&oacute;digo Supervisor</div></td>
          <td height="22" colspan="4"><input name="txtcodsup" type="text" id="txtcodsup"  maxlength="10" style="text-align:center"    readonly value="<? print $ls_codsup?>">   <a href="javascript:catalogo_supervisor();"><img src="../../../../shared/imagebank/tools15/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
		  </tr>
		 		       
        <tr>
		  <td height="22" align="left"><div align="right">Nombre</div></td>
          <td height="22" colspan="2"><input name="txtnomsup" type="text" id="txtnomsup"  maxlength="50" style="text-align:justify" size="50" readonly value="<? print $ls_nomsup?>">            </td>
        </tr>
         <tr>
             <td height="22"><div align="right">Cargo </div></td>
          <td height="22" colspan="3" valign="middle"><input name="txtcodcarsup" type="text" id="txtcodcarsup" size="50" value="<? print $ls_codcarsup?>" readonly>
            </a></td>
        </tr>
		<tr>
          <td width="87" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		     <td height="22" colspan="4">&nbsp;</td>
        </tr>
		 <tr class="titulo-nuevo">
          <td height="22" colspan="10">Objetivos de Desempe&ntilde;o Individual</td>
        </tr>
        <tr>
          <td width="87" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		
		<tr>
          <td colspan="10">
		  	<div align="center">
			<?php
				require_once("../../../../shared/class_folder/grid_param.php");
				$io_grid=new grid_param();
				$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
				unset($io_grid);
			?>
			  </div>		  	</td>		  
          </tr>
		  <tr>
          <td width="87" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		     <td height="22" colspan="4">&nbsp;</td>
        </tr>
		 <tr class="titulo-nuevo">
          <td height="22" colspan="10">Competencias de la Evaluaci&oacute;n</td>
        </tr>
        <tr>
          <td width="87" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		
		<tr>
          <td colspan="10">
		  	<div align="center">
			<?php
			        require_once("../../../../shared/class_folder/grid_param.php");
					$io_grid=new grid_param();
					$io_grid->makegrid($li_totrows2,$lo_title2,$lo_object1,$li_widthtable2,$ls_titletable2,$ls_nametable2);
					unset($io_grid);
			?>
			  </div>
		  	<p>
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
			  <input name="totalfilas2" type="hidden" id="totalfilas2" value="<?php print $li_totrows2;?>">
              <input name="filadelete" type="hidden" id="filadelete">
			  
			</p>			</td>		  
          </tr>
		  <tr>
          <td width="87" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		 <tr class="titulo-nuevo">
          <td height="22" colspan="10">Calificaci&oacute;n Final</td>
        </tr>
		 <tr>
          <td width="87" height="22">&nbsp;</td>
         <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr>
		 <td height="22" align="left"><div align="right">Total Objetivos Desempe&ntilde;o Individual</div></td>
	  <td height="22"><input name="txtresodi" type="text" id="txtresodi" maxlength=3 size="7" style="text-align:center"  onKeyUp="javascript: ue_validarnumero(this);"  value="<?php print $li_resodi?>"  readonly>            </td>
	      <td width="90" height="22"><div align="right">Total Competencias de Evaluaci&oacute;n</div></td>
	      <td height="22"><input name="txtrescom" type="text" id="txtrescom" maxlength=3 size="7" style="text-align:center"  onKeyUp="javascript: ue_validarnumero(this);"   value="<?php print $li_rescom?>" readonly></td>
	      <td height="22">&nbsp;</td>
		</tr>
		  <tr>
		 <td height="22" align="left"><div align="right">Total Calificaci&oacute;n Final</div></td>
	  <td height="22"><input name="txttotal" type="text" id="txttotal" maxlength=3 size="7" style="text-align:center"  onKeyUp="javascript: ue_validarnumero(this);"  value="<?php print $li_total?>"  readonly></td>
	      <td height="22" ><div align="right">Rango de Actuaci&oacute;n</div></td>
	      <td height="22" colspan="2"><input name="txtranact" type="text" id="txtranact" size="40" style="text-align:justify"    value="<?php print $ls_ranact?>"  onFocus="javascript: consultar_rango_actuacion();"  readonly></td>
	      <td width="2" height="22">&nbsp;</td>
		  </tr>
		  <tr>
          <td height="22" align="left"><div align="right">Comentario del Supervisor</div></td>
          <td height="22" colspan="4"><textarea name="txtobs" cols="86" rows="6" id="txtobs" style="text-align:justify" onKeyUp="ue_validarcomillas(this);"  ><?php print $ls_obs?></textarea></td>
        </tr>
		  <tr>
          <td height="22" align="left"><div align="right">Opini&oacute;n del Jefe Inmediato</div></td>
          <td height="22" colspan="4"><textarea name="txtopi" cols="86" rows="4" id="txtopi" style="text-align:justify" onKeyUp="ue_validarcomillas(this);" ><?php print $ls_opi?></textarea></td>
        </tr>
		
		<tr>
          <td width="87" height="22">&nbsp;<input type="hidden" name="txtreporte" id="txtreporte" size="20" value="<? print $ls_reporte;?>"></td>
         <td height="22" colspan="5">&nbsp;</td>
        </tr>
      </table>	 
      <p>&nbsp;</p>
     
 </td> 
</table>
 <input type="hidden" id="higuardar2">
  
 <input type="hidden" name="hidguardar" id="hidguardar" value="<? print $ls_guardar;?>">
  <p>
    <input name="hidcontrol" type="hidden" id="hidcontrol" value="1">
	<input name="hidcontrol2" type="hidden" id="hidcontrol2" value="2">
	<input name="hidstatus" type="hidden" id="hidstatus">
	    
  </p>


</form>


</body>


</html>


