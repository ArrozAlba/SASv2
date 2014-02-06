<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_cmp_ret_mun_otros.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Registro Manual de Comprobante de Retenci&oacute;n</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
.Estilo1 {color: #6699CC}
-->
</style></head>
<body>

<table width="778" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div>      
    <div align="center"></div>      <div align="center"></div><div align="center"></div></td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_sql.php");
    require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/grid_param.php");
    require_once("../shared/class_folder/class_datastore.php");
	require_once("sigesp_scb_c_cmp_ret_iva.php");
	require_once("../shared/class_folder/class_fecha.php");
	require_once("../shared/class_folder/evaluate_formula.php"); 

	$msg         = new class_mensajes();	
	$fun         = new class_funciones();	
	$io_function = new class_funciones();
	$lb_guardar  = true;	
	$io_grid	 = new grid_param();
	$ds_sol      = new class_datastore();
	$fec         = new class_fecha();
	$io_formula  = new evaluate_formula();
	$io_cmpret   = new sigesp_scb_c_cmp_ret_iva('0000000003');	

	global $object;
	global $li_total;

	$arre       = $_SESSION["la_empresa"];
	$ls_empresa = $arre["codemp"];
	
	if( array_key_exists("operacion",$_POST)) 
	{
		$ls_operacion = $_POST["operacion"];
		$ls_numcom    = $_POST["txtcomprobante"];
		$ls_codret    = $_POST["txtcodret"];
		$ls_codsujret = $_POST["txtprovbene"];
		$ls_nomsujret = $_POST["txtdesproben"];
		$ls_mes       = $_POST["mes"];
		$ls_agno      = $_POST["agno"];		
	}
	else
	{
	    //    Validando si las retenciones municipales se deben hacer por Banco o por Cuentas por pagar
		require_once("sigesp_scb_c_config.php");
		//print_r($la_seguridad);
		$in_classconfig=new sigesp_scb_c_config($la_seguridad);
		$ls_fuente=$in_classconfig->uf_select_fuente();
		if($ls_fuente!=false)
		{
			if($ls_fuente=="C")
			{
				print "<script>";
				print "alert('Los comprobantes deben ser creados a través del módulo de Cuentas por Pagar');";
				print "location.href='sigespwindow_blank.php';";
				print "</script>";
			}
		}
		else
		{
			$io_msg->message("Error al seleccionar la fuente");
		}
		$ls_operacion ="";
		$array_fecha  =getdate();
		$ls_mes       =date("m");//$array_fecha["mon"];
		$ls_agno      =date("Y");//$array_fecha["year"];
		$ls_numcom="";
		$io_cmpret->uf_ccr_get_nro($ls_agno.$ls_mes,$ls_numcom);
		$ls_codret    = "";
		$ls_codsujret = "";
		$ls_nomsujret = "";		
		$li_i = 1;
		$li_total =1;
		$ls_codsujret = "";
		$ls_nonmsujret= "";

        for($li_i=1;$li_i<=$li_total;$li_i++)
		{					
			$object[$li_i][1] = "<input type=text name=txtnumfac".$li_i." value=''     style=text-align:center class=sin-borde size=16 maxlength = 15 >";
			$object[$li_i][2] = "<input type=text name=txtnrocon".$li_i." value=''     style=text-align:center class=sin-borde size=16 maxlength = 15 >";
			$object[$li_i][3] = "<input type=text name=txtfecfac".$li_i." value=''     style=text-align:center class=sin-borde size=13 maxlength = 10 onKeyPress='currencyDate(this)'>";
			$object[$li_i][4] = "<input type=text name=txtsiniva".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
			$object[$li_i][5] = "<input type=text name=txtconiva".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
			$object[$li_i][6] = "<input type=text name=txtbasimp".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
			$object[$li_i][7] = "<div align=right><input type=text name=txtporiva".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 > <a href=javascript:uf_iva(".$li_i.");><img src=../shared/imagebank/tools15/buscar.gif width=15 height=15 border=0></a></div>";			
			$object[$li_i][8] = "<input type=text name=txttotimp".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";							
			$object[$li_i][9] = "<div align=right><input type=text name=txtivaret".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 ><a href=javascript:uf_cat_deducciones(".$li_i.");><img src=../shared/imagebank/tools15/buscar.gif width=15 height=15 border=0></a></div><input  name=txtpor".$li_i." id=txtpor".$li_i." style=text-align:right  class=sin-borde size=15 type=hidden >";			
			$object[$li_i][10]= "<input type=text name=txtnumdoc".$li_i." value=''     style=text-align:center class=sin-borde size=16 maxlength = 15  >";					
			$object[$li_i][11]= "<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar Retención' width=15 height=10 border=0></a><input type=hidden name=hidforcar".$li_i."  value=''><input type=hidden name=hidcodded".$li_i."  value=''><input type=hidden name=hidforded".$li_i."  value=''>";	
		}
	}
	
	if  (array_key_exists("totsol",$_POST))
		{
		  $li_total=$_POST["totsol"];		  
		}
	else
		{
		  $li_total="1";
		}
		
	if  (array_key_exists("txtnumord",$_POST))
		{
		  $ls_numord=$_POST["txtnumord"];		  
		}
    else
		{
		  $ls_numord="";
		}
		if  (array_key_exists("txtdesmov",$_POST))
		{
		  $ls_desmov=$_POST["txtdesmov"];		  
		}
    else
		{
		  $ls_desmov="";
		}
	if  (array_key_exists("filsel",$_POST))
		{
		  $li_filsel=$_POST["filsel"];		  
		}
    else
		{
		  $li_filsel="";
		}
		
	if  (array_key_exists("numlic",$_POST))
		{
		  $ls_numlic=$_POST["numlic"];		  
		}
    else
		{
		  $ls_numlic="";
		}
		
	if  (array_key_exists("nit",$_POST))
		{
		  $ls_nit=$_POST["nit"];		  
		}
    else
		{
		  $ls_nit="";
		}
		
    if  (array_key_exists("tipo",$_POST))
		{
		  $ls_tipo=$_POST["tipo"];		  
		}
    else
		{
		  $ls_tipo="";
		}		
    if  (array_key_exists("estprov",$_POST))
		{
		  $ls_estprov=$_POST["estprov"];		  
		}
	else
		{
		  $ls_estprov="";
		}	
	if(array_key_exists("la_deducciones",$_SESSION))
				unset($_SESSION["la_deducciones"]);
	//Declaración de parametros del grid.	
	$titleProg[1]="Factura";
	$titleProg[2]="N° Control";    
	$titleProg[3]="Fecha";     
	$titleProg[4]="Total sin IVA";        
    $titleProg[5]="Total con IVA";
    $titleProg[6]="Base Imponible";        
    $titleProg[7]="Porcentaje Impuesto";        
    $titleProg[8]="Total Impuesto";        
    $titleProg[9]="Retención Municipal";        
	$titleProg[10]="Nº Solicitud de Pago";
	$titleProg[11]="";
	   
    $gridProg="grid_prog";
	
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
$lb_selDiciembre="";




    switch ($ls_mes) {
	   case '01':
		   $lb_selEnero="selected";
		   break;
	   case '02':
   		   $lb_selFebrero="selected";
		   break;
	   case '03':
   		   $lb_selMarzo="selected";
		   break;
	   case '04':
   		   $lb_selAbril="selected";
		   break;
	   case '05':
   		   $lb_selMayo="selected";
		   break;
	   case '06':
   		   $lb_selJunio="selected";
		   break;		   
	   case '07':
		   $lb_selJulio="selected";
		   break;		   		 
	   case '08':
		   $lb_selAgosto="selected";
		   break;		   		 		     
	   case '09':
 		   $lb_selSeptiembre="selected";
		   break;
	   case '10':
		   $lb_selOctubre="selected";
		   break;		   
	   case '11':
		   $lb_selNoviembre="selected";	
		   break;		   
	   case '12':
		   $lb_selDiciembre="selected";
		   break;		   
	}	
	if($ls_operacion=="NUEVO")
	{
  	    uf_mantener_grid();
  	    $ls_numcom        = "";
	    $ld_fecdesde      = $io_function->uf_convertirdatetobd("01/".$ls_mes."/".$ls_agno);
		$ld_hasta         = $fec->uf_last_day($ls_mes,$ls_agno);
		$ld_fechasta      = $io_function->uf_convertirdatetobd($ld_hasta);
	    $ls_periodofiscal = $io_function->uf_cerosizquierda(substr($ld_fecdesde,0,4),4).$io_function->uf_cerosizquierda(substr($ld_fecdesde,5,2),2) ;				

		$lb_valido = $io_cmpret->uf_ccr_get_nro($ls_periodofiscal,&$ls_numcom);
		if(!$lb_valido) 
		{
			$msg->message("Error al generar el numero del Comprobante");
		}				
	}	
	if($ls_operacion=="CAMBIO_PERIODO")
	{
		$io_cmpret->uf_ccr_get_nro($ls_agno.$ls_mes,$ls_numcom);
		uf_mantener_grid();

	}	
	if($ls_operacion=="ELIMINAR")		
	{		
		$lb_valido=$io_cmpret->uf_delete_cmp_ret($ls_codret,$ls_numcom,$la_seguridad);
		if($lb_valido)
		{			
			$msg->message("Comprobante Eliminado");
			$ls_numcom    = "";
			$ls_codret    = "";
			$ls_codsujret = "";
			$ls_nomsujret = "";
			$ls_dirsujret = "";
			$ls_dessujret = "";
		}
		else
		{			
			$msg->message("".$io_cmpret->is_msg_error);
		}
		$ls_operacion="CARGAR_DT";		
	}			
	if($ls_operacion=="GUARDAR")
	{			
 	     $lb_valido= true;
         $li_total = $_POST["totsol"];			
         $li_filas = 0;
         $ls_numfacl=$_POST["txtnumfac1"];			 	
         if($ls_numfacl!="")		 
		 {
				 for($i=1;$i<=$li_total;$i++)
				 {			
					  $ls_numero=$_POST["txtnumfac".$i];			 	
					 
					  if($ls_numero!="")
					  {					
					     $li_filas=$li_filas+1;
						 $lr_datosgrid["numfac"][$i]     =$_POST["txtnumfac".$i];                  
						 //---------------------------------------------             
						  $ld_fecgrid  =$_POST["txtfecfac".$i];  
						  $ld_fecgrid  =$io_function->uf_convertirdatetobd($ld_fecgrid);                
						  $lr_datosgrid["fecfac"][$i] =$ld_fecgrid;  
						 //---------------------------------------------                	
						  $lr_datosgrid["nrocontrol"][$i]=$_POST["txtnrocon".$i];                  
						 //---------------------------------------------                				 				
						 $ldec_siniva=$_POST["txtsiniva".$i];                  
						 $ldec_siniva=str_replace('.','',$ldec_siniva);
						 $ldec_siniva=str_replace(',','.',$ldec_siniva);				 
						 $lr_datosgrid["siniva"][$i]=$ldec_siniva;                  		
						 //---------------------------------------------
						 $ldec_coniva=$_POST["txtconiva".$i];                  
						 $ldec_coniva=str_replace('.','',$ldec_coniva);
						 $ldec_coniva=str_replace(',','.',$ldec_coniva);				 
						 $lr_datosgrid["coniva"][$i]=$ldec_coniva;                  		
						 //----------------------------------------------
						 $ldec_basimp=$_POST["txtbasimp".$i];                  
						 $ldec_basimp=str_replace('.','',$ldec_basimp);
						 $ldec_basimp=str_replace(',','.',$ldec_basimp);				 
						 $lr_datosgrid["basimp"][$i]=$ldec_basimp;                  		
						 //----------------------------------------------
						 $ldec_poriva=$_POST["txtpor".$i];    							
						 $lr_datosgrid["poriva"][$i]=$ldec_poriva;                  		
						 //----------------------------------------------
						 $ldec_totimp=$_POST["txttotimp".$i];                  
						 $ldec_totimp=str_replace('.','',$ldec_totimp);
						 $ldec_totimp=str_replace(',','.',$ldec_totimp);				 
						 $lr_datosgrid["totimp"][$i]=$ldec_totimp;                  		
						 //----------------------------------------------
						 $ldec_ivaret=$_POST["txtivaret".$i];                  
						 $ldec_ivaret=str_replace('.','',$ldec_ivaret);
						 $ldec_ivaret=str_replace(',','.',$ldec_ivaret);				 
						 $lr_datosgrid["ivaret"][$i]=$ldec_ivaret;                  								
						 //---------------------------------------------            
						 $lr_datosgrid["numdoc"][$i]=$_POST["txtnumdoc".$i];                  
						 //---------------------------------------------            
					  }   
				}	
		  }
		  else
		  {
			 $lb_valido=false;			
		  }			  
		
		if($lb_valido)
		{
			$ld_fecdesde      = $io_function->uf_convertirdatetobd("01/".$ls_mes."/".$ls_agno);
 		    $ld_hasta         = $fec->uf_last_day($ls_mes,$ls_agno);
		    $ld_fechasta      = $io_function->uf_convertirdatetobd($ld_hasta);
	        $ls_periodofiscal = $io_function->uf_cerosizquierda(substr($ld_fecdesde,0,4),4).$io_function->uf_cerosizquierda(substr($ld_fecdesde,5,2),2) ;				
		    
		    $ls_numcom             = $_POST["txtcomprobante"];	
		    $lr_proben["codigo"]   = $_POST["txtprovbene"];
			$lr_proben["nombre"]   = $_POST["txtdesproben"];
			$lr_proben["tipo"]     = $ls_estprov;		
			
			if($lb_valido)
 	        {		   					   			   
	   		   $ls_numord = $_POST["txtnumord"];	
			   $ls_descripcion = $_POST["txtdesmov"];
			   $lb_valido=$io_cmpret->uf_guardar_ret_iva_otros($ls_descripcion,$ls_numcom,$ls_periodofiscal,$lr_proben,
			                                                   $ls_numord,$li_filas,$lr_datosgrid,$la_seguridad);
			}	
			if($lb_valido)			
			{
			    if(array_key_exists("la_deducciones",$_SESSION))
					unset($_SESSION["la_deducciones"]);
				$ls_operacion = "";
				$array_fecha  = getdate();
				$ls_mes       = $array_fecha["mon"];
				$ls_agno      = $array_fecha["year"];
				$ls_numcom    = "";
				$ls_codret    = "";
				$ls_codsujret = "";
				$ls_nomsujret = "";
				$ls_dirsujret = "";
				$ls_dessujret = "";
		        $ls_numlic    = "";		
		        $ls_nit       = "";
                $ls_tipo      = "";		
				$ls_numord    = ""; 
				$ls_desmov	  = "";	 
				$li_i         = 1;
				$li_total     = 1;
				$io_cmpret->uf_ccr_get_nro($ls_agno.$ls_mes,$ls_numcom);

				for($li_i=1;$li_i<=$li_total;$li_i++)
				{					
					$object[$li_i][1] = "<input type=text name=txtnumfac".$li_i." value=''     style=text-align:center class=sin-borde size=16 maxlength = 15 >";
					$object[$li_i][2] = "<input type=text name=txtnrocon".$li_i." value=''     style=text-align:center class=sin-borde size=16 maxlength = 15 >";
					$object[$li_i][3] = "<input type=text name=txtfecfac".$li_i." value=''     style=text-align:center class=sin-borde size=13 maxlength = 10 onKeyPress='currencyDate(this) '>";
					$object[$li_i][4] = "<input type=text name=txtsiniva".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
					$object[$li_i][5] = "<input type=text name=txtconiva".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
					$object[$li_i][6] = "<input type=text name=txtbasimp".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
					$object[$li_i][7] = "<div align=right><input type=text name=txtporiva".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25> <a href=javascript:uf_iva(".$li_i.");><img src=../shared/imagebank/tools15/buscar.gif  width=15 height=15 border=0></a></div>";			
					$object[$li_i][8] = "<input type=text name=txttotimp".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";							
					$object[$li_i][9] = "<div align=right><input type=text name=txtivaret".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25> <a href=javascript:uf_cat_deducciones(".$li_i.");><img src=../shared/imagebank/tools15/buscar.gif  width=15 height=15 border=0></a></div><input name=txtpor".$li_i."  style=text-align:right  class=sin-borde size=15 type=hidden >";			
					$object[$li_i][10]= "<input type=text name=txtnumdoc".$li_i." value=''     style=text-align:center class=sin-borde size=16 maxlength = 15  >";					
					$object[$li_i][11]= "<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar Retención' width=15 height=10 border=0></a><input type=hidden name=hidforcar".$li_i."  value=''><input type=hidden name=hidcodded".$li_i."  value=''><input type=hidden name=hidforded".$li_i."  value=''>";	
                }					
			}
			else
			{
			   uf_mantener_grid();
			}
		}				
	}		
	if($ls_operacion=="DELETE_DT")		
	{
		$li_totalrow =$_POST["totsol"];		
        $li_total    =$li_totalrow-1;
        $li_rowdelete=$_POST["filadelete"];
		$li_x = 0;
		
		for($li_i=1;$li_i<=$li_totalrow;$li_i++)
	    {
			if($li_i!=$li_rowdelete)
			{				
			     $li_x =$li_x+1;      
				 $ls_numfac  =$_POST["txtnumfac".$li_i];				 
				 $ls_numcon  =$_POST["txtnrocon".$li_i];
				 $ld_fecfac  =$_POST["txtfecfac".$li_i];
				 $ld_siniva  =$_POST["txtsiniva".$li_i];      
				 $ld_coniva  =$_POST["txtconiva".$li_i];      
				 $ld_basimp  =$_POST["txtbasimp".$li_i];
				 $ld_poriva  =$_POST["txtporiva".$li_i];      
				 $ld_totimp  =$_POST["txttotimp".$li_i];
				 $ld_ivaret  =$_POST["txtivaret".$li_i];
				 $ls_numdoc  =$_POST["txtnumdoc".$li_i]; 			
				 $ls_formula =$_POST["hidforcar".$li_i]; 		
				 $ls_codded  =$_POST["hidcodded".$li_i]; 		
				 $ls_forded  =$_POST["hidforded".$li_i];
				 $ld_por      =$_POST["txtpor".$li_i];
										
				 $object[$li_x][1] = "<input type=text name=txtnumfac".$li_x." value='".$ls_numfac."' style=text-align:center class=sin-borde size=16 maxlength = 15 >";
				 $object[$li_x][2] = "<input type=text name=txtnrocon".$li_x." value='".$ls_numcon."' style=text-align:center class=sin-borde size=16 maxlength = 15 >";			
				 $object[$li_x][3] = "<input type=text name=txtfecfac".$li_x." value='".$ld_fecfac."' style=text-align:center class=sin-borde size=13 maxlength = 10 onKeyPress='currencyDate(this)'>";
				 $object[$li_x][4] = "<input type=text name=txtsiniva".$li_x." value='".$ld_siniva."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
				 $object[$li_x][5] = "<input type=text name=txtconiva".$li_x." value='".$ld_coniva."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
				 $object[$li_x][6] = "<input type=text name=txtbasimp".$li_x." value='".$ld_basimp."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
				 $object[$li_x][7] = "<div align=right><input type=text name=txtporiva".$li_x." value='".$ld_poriva."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25> <a href=javascript:uf_iva(".$li_x.");><img src=../shared/imagebank/tools15/buscar.gif  width=15 height=15 border=0></a></div>";			
				 $object[$li_x][8] = "<input type=text name=txttotimp".$li_x." value='".$ld_totimp."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";							
				 $object[$li_x][9] = "<div align=right><input type=text name=txtivaret".$li_x." value='".$ld_ivaret."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25> <a href=javascript:uf_cat_deducciones(".$li_x.");><img src=../shared/imagebank/tools15/buscar.gif  width=15 height=15 border=0></a></div><input name=txtpor".$li_x."  style=text-align:right  class=sin-borde size=15 type=hidden value='".$ld_por."'>";			
				 $object[$li_x][10]= "<input type=text name=txtnumdoc".$li_x." value='".$ls_numdoc."' style=text-align:center class=sin-borde size=16 maxlength = 15  >";					
				 $object[$li_x][11]= "<a href=javascript:uf_delete_dt(".$li_x.");><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar Retención' width=15 height=10 border=0></a><input type=hidden name=hidforcar".$li_x." value='".$ls_formula."'><input type=hidden name=hidcodded".$li_x." value='".$ls_codded."'><input type=hidden name=hidforded".$li_x." value='".$ls_forded."'>";	
			}	     
	        else
	        {	
		       $li_rowdelete=0;
	        }
         }		
	}			
    function uf_mantener_grid()
    {            
	  global $class_grid;
	  global $li_total;
	  global $title;
	  global $align;
	  global $size;
	  global $maxlength;
	  global $values;
	  global $totrow;
	  global $validaciones;	
	  global $object;   

	  $li_total=$_POST["totsol"];

      for($li_i=1;$li_i<=$li_total;$li_i++)
	  {
		 $ls_numfac  =$_POST["txtnumfac".$li_i];
 		 $ls_numcon  =$_POST["txtnrocon".$li_i];
		 $ld_fecfac  =$_POST["txtfecfac".$li_i];
		 $ld_siniva  =$_POST["txtsiniva".$li_i];      
		 $ld_coniva  =$_POST["txtconiva".$li_i];      
		 $ld_basimp  =$_POST["txtbasimp".$li_i];
		 $ld_poriva  =$_POST["txtporiva".$li_i];      
		 $ld_totimp  =$_POST["txttotimp".$li_i];
		 $ld_ivaret  =$_POST["txtivaret".$li_i];
		 $ls_numdoc  =$_POST["txtnumdoc".$li_i]; 			
         $ls_formula =$_POST["hidforcar".$li_i]; 		
         $ls_codded  =$_POST["hidcodded".$li_i]; 		
         $ls_forded  =$_POST["hidforded".$li_i]; 
		 $ld_por     =$_POST["txtpor".$li_i]; 		
				 				
		 $object[$li_i][1] = "<input type=text name=txtnumfac".$li_i." value='".$ls_numfac."' style=text-align:center class=sin-borde size=16 maxlength = 15 >";
		 $object[$li_i][2] = "<input type=text name=txtnrocon".$li_i." value='".$ls_numcon."' style=text-align:center class=sin-borde size=16 maxlength = 15 >";			
		 $object[$li_i][3] = "<input type=text name=txtfecfac".$li_i." value='".$ld_fecfac."' style=text-align:center class=sin-borde size=13 maxlength = 10 onKeyPress='currencyDate(this)'>";
		 $object[$li_i][4] = "<input type=text name=txtsiniva".$li_i." value='".$ld_siniva."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
		 $object[$li_i][5] = "<input type=text name=txtconiva".$li_i." value='".$ld_coniva."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
		 $object[$li_i][6] = "<input type=text name=txtbasimp".$li_i." value='".$ld_basimp."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
		 $object[$li_i][7] = "<div align=right><input type=text name=txtporiva".$li_i." value='".$ld_poriva."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25><a href=javascript:uf_iva(".$li_i.");><img src=../shared/imagebank/tools15/buscar.gif  width=15 height=15 border=0></a></div>";			
		 $object[$li_i][8] = "<input type=text name=txttotimp".$li_i." value='".$ld_totimp."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";							
		 $object[$li_i][9] = "<div align=right><input type=text name=txtivaret".$li_i." value='".$ld_ivaret."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25> <a href=javascript:uf_cat_deducciones(".$li_i.");><img src=../shared/imagebank/tools15/buscar.gif  width=15 height=15 border=0></a></div><input name=txtpor".$li_i."  style=text-align:right  class=sin-borde size=15 type=hidden value='".$ld_por."'>";			
		 $object[$li_i][10]= "<input type=text name=txtnumdoc".$li_i." value='".$ls_numdoc."' style=text-align:center class=sin-borde size=16 maxlength = 15  >";					
		 $object[$li_i][11]= "<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar Retención' width=15 height=10 border=0></a><input type=hidden name=hidforcar".$li_i." value='".$ls_formula."'><input type=hidden name=hidcodded".$li_i." value='".$ls_codded."'><input type=hidden name=hidforded".$li_i." value='".$ls_forded."'>";	
	  }
    } 
	if ($ls_operacion=="PINTARFILAS")
	   {          		 
	   
       	 $li_total=$_POST["totsol"];
	     for($li_i=1;$li_i<=$li_total;$li_i++)
	     {
			   if (array_key_exists("txtnumfac".$li_i,$_POST))
			      {
			         $ls_numfac  =$_POST["txtnumfac".$li_i];
					 $ls_numcon  =$_POST["txtnrocon".$li_i];
					 $ld_fecfac  =$_POST["txtfecfac".$li_i];
					 $ld_siniva  =$_POST["txtsiniva".$li_i];      
					 $ld_coniva  =$_POST["txtconiva".$li_i];      
					 $ld_basimp  =$_POST["txtbasimp".$li_i];
					 $ld_poriva  =$_POST["txtporiva".$li_i];      
					 $ld_totimp  =$_POST["txttotimp".$li_i];
					 $ld_ivaret  =$_POST["txtivaret".$li_i];
					 $ls_numdoc  =$_POST["txtnumdoc".$li_i]; 			
					 $ls_formula =$_POST["hidforcar".$li_i]; 		
					 $ls_codded  =$_POST["hidcodded".$li_i]; 		
					 $ls_forded  =$_POST["hidforded".$li_i]; 
					 $ld_por     =$_POST["txtpor".$li_i];		
				
					 $object[$li_i][1] = "<input type=text name=txtnumfac".$li_i." value='".$ls_numfac."' style=text-align:center class=sin-borde size=16 maxlength = 15 >";
					 $object[$li_i][2] = "<input type=text name=txtnrocon".$li_i." value='".$ls_numcon."' style=text-align:center class=sin-borde size=16 maxlength = 15 >";			
					 $object[$li_i][3] = "<input type=text name=txtfecfac".$li_i." value='".$ld_fecfac."' style=text-align:center class=sin-borde size=13 maxlength = 10 onKeyPress='currencyDate(this)'>";
					 $object[$li_i][4] = "<input type=text name=txtsiniva".$li_i." value='".$ld_siniva."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
					 $object[$li_i][5] = "<input type=text name=txtconiva".$li_i." value='".$ld_coniva."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
					 $object[$li_i][6] = "<input type=text name=txtbasimp".$li_i." value='".$ld_basimp."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
					 $object[$li_i][7] = "<div align=right><input type=text name=txtporiva".$li_i." value='".$ld_poriva."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25> <a href=javascript:uf_iva(".$li_i.");><img src=../shared/imagebank/tools15/buscar.gif  width=15 height=15 border=0></a></div>";			
					 $object[$li_i][8] = "<input type=text name=txttotimp".$li_i." value='".$ld_totimp."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";							
					 $object[$li_i][9] = "<div align=right><input type=text name=txtivaret".$li_i." value='".$ld_ivaret."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25> <a href=javascript:uf_cat_deducciones(".$li_i.");><img src=../shared/imagebank/tools15/buscar.gif  width=15 height=15 border=0></a></div><input name=txtpor".$li_i."  style=text-align:right  class=sin-borde size=15 type=hidden value='".$ld_por."'>";			
					 $object[$li_i][10]= "<input type=text name=txtnumdoc".$li_i." value='".$ls_numdoc."' style=text-align:center class=sin-borde size=16 maxlength = 15  >";					
					 $object[$li_i][11]= "<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar Retención' width=15 height=10 border=0></a><input type=hidden name=hidforcar".$li_i." value='".$ls_formula."'><input type=hidden name=hidcodded".$li_i." value='".$ls_codded."'><input type=hidden name=hidforded".$li_i." value='".$ls_forded."'>";	
                  }
		       else
		          {
					 $object[$li_i][1] = "<input type=text name=txtnumfac".$li_i." value=''     style=text-align:center class=sin-borde size=16 maxlength = 15 >";
					 $object[$li_i][2] = "<input type=text name=txtnrocon".$li_i." value=''     style=text-align:center class=sin-borde size=16 maxlength = 15 >";			
					 $object[$li_i][3] = "<input type=text name=txtfecfac".$li_i." value=''     style=text-align:center class=sin-borde size=13 maxlength = 10 onKeyPress='currencyDate(this)'>";
					 $object[$li_i][4] = "<input type=text name=txtsiniva".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
					 $object[$li_i][5] = "<input type=text name=txtconiva".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
					 $object[$li_i][6] = "<input type=text name=txtbasimp".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
					 $object[$li_i][7] = "<div align=right><input type=text name=txtporiva".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 ><a href=javascript:uf_iva(".$li_i.");><img src=../shared/imagebank/tools15/buscar.gif  width=15 height=15 border=0></a></div>";			
					 $object[$li_i][8] = "<input type=text name=txttotimp".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";							
					 $object[$li_i][9] = "<div align=right><input type=text name=txtivaret".$li_i." value='0,00' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25> <a href=javascript:uf_cat_deducciones(".$li_i.");><img src=../shared/imagebank/tools15/buscar.gif  width=15 height=15 border=0></a></div><input name=txtpor".$li_i."  style=text-align:right  class=sin-borde size=15 type=hidden >";			
					 $object[$li_i][10]= "<input type=text name=txtnumdoc".$li_i." value=''     style=text-align:center class=sin-borde size=16 maxlength = 15  >";					
					 $object[$li_i][11]= "<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar Retención' width=15 height=10 border=0></a><input type=hidden name=hidforcar".$li_i." value='".$ls_formula."'><input type=hidden name=hidcodded".$li_i." value='".$ls_codded."'><input type=hidden name=hidforded".$li_i." value='".$ls_forded."'>";	
				  }
           }			
	}     
	if($ls_operacion=="CALCULAR")
	{	
	   
	   uf_mantener_grid();	
	   $li_filacalcular=$_POST["filacalcular"];
	   $ld_poriva  =$_POST["txtporiva".$li_filacalcular];
	   $ld_basimp  =$_POST["txtbasimp".$li_filacalcular]; 
	   $ls_formula =$_POST["hidforcar".$li_filacalcular]; 
	   $ls_codded  =$_POST["hidcodded".$li_filacalcular]; 
       $ls_forded  =$_POST["hidforded".$li_filacalcular]; 
	   $ls_numfac  =$_POST["txtnumfac".$li_filacalcular];
	   $ls_numcon  =$_POST["txtnrocon".$li_filacalcular];
	   $ld_fecfac  =$_POST["txtfecfac".$li_filacalcular];
	   $ld_siniva  =$_POST["txtsiniva".$li_filacalcular];      
	   $ld_coniva  =$_POST["txtconiva".$li_filacalcular];      
	   $ld_basimp  =$_POST["txtbasimp".$li_filacalcular];	  
	   $ld_totimp  =$_POST["txttotimp".$li_filacalcular];
	   $ld_ivaret  =$_POST["txtivaret".$li_filacalcular];
	   $ls_numdoc  =$_POST["txtnumdoc".$li_filacalcular]; 			
	   $ls_formula =$_POST["hidforcar".$li_filacalcular];  
		 if( ($ld_poriva!="0,00") && ($ld_poriva!="0") && ($ld_poriva!="0,0") && ($ld_poriva!="0") && ($ld_poriva!="") )		 
		 {					 					  			  								 			  			
				 if( ($ld_basimp!="0,00") && ($ld_basimp!="0") && ($ld_basimp!="0,0") && ($ld_basimp!="0") && ($ld_basimp!="") )
				 {
						 $ld_basimp =str_replace(".","",$ld_basimp);
						 $ld_basimp =str_replace(",",".",$ld_basimp);	
						 $lb_formula   =true;	
						 $ld_totimp =$io_formula->uf_evaluar($ls_formula,$ld_basimp,$lb_formula);				 
						 $ld_basimp=number_format($ld_basimp,2,",",".");	
						 
						 if( ($ls_codded!="0,00") && ($ls_codded!="0") && ($ls_codded!="0,0") && ($ls_codded!="0") && ($ls_codded!="") )		 
						 {					 					  			  								 			  			
								 if( ($ld_basimp!="0,00") && ($ld_basimp!="0") && ($ld_basimp!="0,0") && ($ld_basimp!="0") && ($ld_basimp!="") )
								 {							
									$lb_formula   =true;		
									$ld_ivaret =$io_formula->uf_evaluar($ls_forded,$ld_totimp,$lb_formula);				 
									$ld_ivaret=number_format($ld_ivaret,2,",",".");	
								 }
								 else
								 {
									$ld_totimp ="0";				 
								 }								 															 					 
						 }		
						 else
						 {
							 $ld_ivaret="0,00";
						 }					
				 }
				 else
				 {
					$ld_totimp ="0";				 
				 }					 
				 $ld_totimp=number_format($ld_totimp,2,",",".");									 					 
		 }
		 $object[$li_filacalcular][1] = "<input type=text name=txtnumfac".$li_filacalcular." value='".$ls_numfac."' style=text-align:center class=sin-borde size=16 maxlength = 15 >";
		 $object[$li_filacalcular][2] = "<input type=text name=txtnrocon".$li_filacalcular." value='".$ls_numcon."' style=text-align:center class=sin-borde size=16 maxlength = 15 >";			
		 $object[$li_filacalcular][3] = "<input type=text name=txtfecfac".$li_filacalcular." value='".$ld_fecfac."' style=text-align:center class=sin-borde size=13 maxlength = 10 onKeyPress='currencyDate(this)'>";
		 $object[$li_filacalcular][4] = "<input type=text name=txtsiniva".$li_filacalcular." value='".$ld_siniva."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
		 $object[$li_filacalcular][5] = "<input type=text name=txtconiva".$li_filacalcular." value='".$ld_coniva."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
		 $object[$li_filacalcular][6] = "<input type=text name=txtbasimp".$li_filacalcular." value='".$ld_basimp."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";
		 $object[$li_filacalcular][7] = "<div align=right><input type=text name=txtporiva".$li_filacalcular." value='".$ld_poriva."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25> <a href=javascript:uf_iva(".$li_filacalcular.");><img src=../shared/imagebank/tools15/buscar.gif  width=15 height=15 border=0></a></div>";			
		 $object[$li_filacalcular][8] = "<input type=text name=txttotimp".$li_filacalcular." value='".$ld_totimp."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25 >";							
		 $object[$li_filacalcular][9] = "<div align=right><input type=text name=txtivaret".$li_filacalcular." value='".$ld_ivaret."' style=text-align:right  class=sin-borde size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) maxlength =25> <a href=javascript:uf_cat_deducciones(".$li_filacalcular.");><img src=../shared/imagebank/tools15/buscar.gif  width=15 height=15 border=0></a></div><input name=txtpor".$li_filacalcular."  style=text-align:right  class=sin-borde size=15 type=hidden >";			
		 $object[$li_filacalcular][10]= "<input type=text name=txtnumdoc".$li_filacalcular." value='".$ls_numdoc."' style=text-align:center class=sin-borde size=16 maxlength = 15  >";					
		 $object[$li_filacalcular][11]= "<a href=javascript:uf_delete_dt(".$li_filacalcular.");><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar Retención' width=15 height=10 border=0></a><input type=hidden name=hidforcar".$li_filacalcular." value='".$ls_formula."'><input type=hidden name=hidcodded".$li_filacalcular." value='".$ls_codded."'><input type=hidden name=hidforded".$li_filacalcular." value='".$ls_forded."'>";	
	}
  ?>
<p>&nbsp;</p>
  <form name="form1" method="post" action="">
    <p>
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    </p>
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><table width="693" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" id="tabla">
          <tr class="titulo-ventana">
            <td height="22" colspan="4" class="titulo-ventana">Registro Manual de Comprobante de Retenci&oacute;n </td>
          </tr>
          <tr>
            <td height="13">&nbsp;</td>
            <td height="13">&nbsp;</td>
            <td height="13">&nbsp;</td>
            <td height="13">&nbsp;</td>
          </tr>
          <tr>
            <td width="107" height="22"><div align="right">Comprobante</div></td>
            <td width="178" height="22"><div align="left">
                <input name="txtcomprobante" type="text" id="txtcomprobante" value="<?php print $ls_numcom;?>" readonly style="text-align:center">
                <input name="txtcodret" type="hidden" id="txtcodret" value="<?php print $ls_codret;?>">
            </div></td>
            <td width="176" height="22"><div align="right">Periodo</div></td>
            <td width="230" height="22"><select name="mes" id="mes" onChange="javascript:uf_cambio_periodo()">
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
              <input name="agno" type="text" id="agno" style="text-align:center " value="<?php print $ls_agno;?>" size="10" maxlength="4"></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Documento </div></td>
            <td height="22" colspan="3"><input name="txtnumord" type="text" id="txtnumord" value="<?php print $ls_numord;?>" style="text-align:center" readonly="true">
            <a href="javascript:cat_sol();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>  <label>
            &nbsp;&nbsp;&nbsp;<input name="txtdesmov" type="text" id="txtdesmov" value="<?php print $ls_desmov;?>" size="80" maxlength="200" class="sin-borde" readonly>
            </label></td>
          </tr>
          <tr>
            <td height="13">&nbsp;</td>
            <td height="13">&nbsp;</td>
            <td height="13">&nbsp;</td>
            <td height="13">&nbsp;</td>
          </tr>
          <tr class="titulo-celdanew">
            <td height="13" colspan="4" class="titulo-celdanew">Sujeto Retenci&oacute;n</td>
          </tr>
          <tr>
            <td height="13" colspan="4">&nbsp;</td>
          </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td height="22" colspan="2"><?php 	
	    if(($ls_estprov=="P")||($ls_estprov==""))
	    {
		   $ls_proveedor="checked";
	       $ls_beneficia="";
        }  
        else
        {   
		   $ls_proveedor="";
	       $ls_beneficia="checked";
	    }	 
	    ?>
              <input name="estprov" type="radio" value="P" onClick="uf_change_radio()" <?php print $ls_proveedor ?> style="border-color:#FFFFFF">
Proveedor&nbsp;&nbsp;&nbsp;
<input name="estprov" onClick="uf_change_radio()" type="radio" value="B" <?php print $ls_beneficia ?> style="border-color:#FFFFFF">
Beneficiario<a href="javascript:catalogo_proveedor();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></td>
            <td height="22">&nbsp;</td>
          </tr>
          <tr>
            <td height="22"><div align="right">Codigo</div></td>
            <td height="22" colspan="3"><div align="left">
                <input name="txtprovbene" type="text" id="txtprovbene" value="<?php print $ls_codsujret;?>" style="text-align:center" readonly>
            
&nbsp;&nbsp;&nbsp;</div></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Nombre</div></td>
            <td height="22" colspan="3"><div align="left">
              <input name="txtdesproben" type="text" id="txtdesproben" value="<?php print $ls_nomsujret;?>" size="110" readonly>
            </div></td>
          </tr>
          <tr>
            <td height="29">&nbsp;</td>
            <td height="29"><input name="numlic" type="hidden" id="numlic" value="<?php print $ls_numlic;?>">
            <input name="nit" type="hidden" id="nit" value="<?php print $ls_nit;?>">
            <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo;?>"></td>
            <td height="29" valign="middle"><div align="right"><a href="javascript:uf_pintar_filas(1);"> Agregar una fila</a> &nbsp;&nbsp;&nbsp;</div></td>
            <td height="29" valign="middle">Agregar Filas
              <select name="cmbfilas" id="cmbfilas" onChange="javascript:uf_pintar_filas(cmbfilas.value);">
                <option value="0">0</option>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="35">35</option>
                <option value="40">40</option>
                <option value="45">45</option>
                <option value="50">50</option>
                <option value="55">55</option>
                <option value="60">60</option>
            </select></td>
          </tr>
            </table></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <table width="200" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="center">
	  	
        <?php $io_grid->makegrid($li_total,$titleProg,$object,770,'',$gridProg);?>
        <input name="totsol"  type="hidden" id="totsol"  value="<?php print $li_total?>">
        <input name="numope"  type="hidden" id="numope">
        <input name="numfac"  type="hidden" id="numfac">
        <input name="filsel"  type="hidden" id="filsel" value="<?php print $li_filsel?>">
        <input name="filadelete" type="hidden" id="filadelete">
		<input name="filacalcular" type="hidden" id="filacalcular">
      </div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
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
	  location.href="sigesp_scb_p_cmp_ret_mun_otros.php";	 
  }
  function ue_guardar()
  {
	  f=document.form1;
	  ls_comprobante=f.txtcomprobante.value;
	  ls_doc=f.txtnumord.value;
 	  li_incluir  = f.incluir.value;
	  ls_provbene=f.txtprovbene.value;
	  ls_numfac = eval("f.txtnumfac1.value;");
      ls_numero = eval("f.txtnrocon1.value;");
      ls_fecha  = eval("f.txtfecfac1.value;");
	  ls_numdoc = eval("f.txtnumdoc1.value;");
	  lb_boolean1= (ls_comprobante != "") &&  (ls_doc != "") &&  (ls_provbene != "");
	  lb_boolean2=  (ls_numfac != "") && (ls_numero != "") &&(ls_fecha != "")&&(ls_numdoc != "");
	  if(lb_boolean1 && lb_boolean2 )
	  { 
	  		 if(li_incluir==1)
			  {	
				  f.operacion.value ="GUARDAR";		  
				  f.submit();
			   }
			  else
			  {
				  alert("No tiene permiso para realizar esta operacion");
			  }  
	  }
	  else
	  {
	  		if(!lb_boolean1)
			{
				  alert("Favor complete los datos de la cabecera del comprobante");
			}
			else
			{
				  alert("Favor complete los datos del detalle del comprobante");
			}
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
		  f.submit();
	  }
  }
  function ue_buscar()
  {
	window.open("sigesp_cat_cmp_ret.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
  }

  function uf_delete_dt(li_row)
  {
	  f=document.form1;
	  if(f.totsol.value>1)
	  {
		  f.filadelete.value=li_row;       
		  f.operacion.value ="DELETE_DT";
		  f.submit();
	  }
	  else
	  {	  

			eval("f.txtnumfac"+li_row+".value='';");
			eval("f.txtnrocon"+li_row+".value='';");
			eval("f.txtfecfac"+li_row+".value='';");
			eval("f.txtsiniva"+li_row+".value='0,00';");
			eval("f.txtconiva"+li_row+".value='0,00';");
			eval("f.txtbasimp"+li_row+".value='0,00';");
			eval("f.txtporiva"+li_row+".value='0,00';");
			eval("f.txttotimp"+li_row+".value='0,00';");
			eval("f.txtivaret"+li_row+".value='0,00';");
			eval("f.txtnumdoc"+li_row+".value='';");
			
	  }
  }  
  function uf_iva(li_row)
  {
	  f=document.form1;	 
	  f.filsel.value=li_row; 
	  f.filacalcular.value=li_row; 	 
	  ls_basimp=eval("f.txtbasimp"+li_row+".value");	 
	  
	  if( (ls_basimp!="0,00") && (ls_basimp!="0") && (ls_basimp!="0,0") && (ls_basimp!="0") && (ls_basimp!="") )	  
	  {	 
		  pagina="sigesp_cxp_cat_iva.php?";
		  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=600,resizable=yes,location=no");	  
  	  }
	  else
	  {
	     alert("Para que pueda seleccionar un cargo la Base Imponible debe ser distinta de cero");
	  }
  }  
  function uf_ret_iva(li_row)
  {
	  f=document.form1;	
      f.filsel.value=li_row;  	 	 
	  ls_porcar=eval("f.txtporiva"+li_row+".value");	  
	  
      if( (ls_porcar!="0,00") && (ls_porcar!="0") && (ls_porcar!="0,0") && (ls_porcar!="0") && (ls_porcar!="") )	  
	  {
		  pagina="sigesp_cxp_cat_iva_ret.php";
		  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=600,resizable=yes,location=no");	  
	  }  
	  else
	  {
	     alert("Para que pueda seleccionar un deducción el porcentaje debe ser distinto de cero");
	  }
  }  
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
			f.txtnumsol.value   =ls_numsol;
			f.txtmonto.value    =uf_convertir(ldec_monto);
			f.txtprovbene.value =ls_provbene;
			f.txtfecha.value    =ld_fecsol;
			f.fila.value        =fila;
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
		if (whichCode == 8)  return true; // Enter 
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
	function cat_sol()
	{
		pagina="sigesp_cat_mov_chq_cmp_ret.php";
		ancho=screen.width;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width="+ancho+",height=400,resizable=yes,location=no,status=yes");
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
    }
	function uf_pintar_filas(fila)
	{
		var antfilas;
		f=document.form1;
		antfilas=eval(f.totsol.value);
		filas=(eval(antfilas)+eval(fila));						
			
		f.totsol.value=filas;					
				
		f.operacion.value="PINTARFILAS";
		f.submit();
	}
    function uf_change_radio()
	{
		 f=document.form1;
		 f.txtprovbene.value="";
		 f.txtdesproben.value="";
	}
    function catalogo_proveedor()
	{
		f=document.form1;
		f.operacion.value="";			
		if (f.estprov[0].checked)
		{ 
		  pagina="sigesp_catdinamic_prov.php";
		  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
		} 
		if (f.estprov[1].checked)
		{ 
		  pagina="sigesp_catdinamic_bene.php";
		  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
		} 
	} 	
	function uf_cambio_periodo()
	{
		document.form1.operacion.value="CAMBIO_PERIODO";
		document.form1.submit();
	}
	
	function uf_cat_deducciones(fila) 
    {
   	   f=document.form1;
	   ls_documento   = f.txtnumord.value;
	   ldec_monto     = eval("f.txtsiniva"+fila+".value");
	   ldec_monto     = parseFloat(uf_convertir_monto(ldec_monto));
	   ldec_monobjret = eval("f.txtbasimp"+fila+".value");
	   ldec_monobjret = parseFloat(uf_convertir_monto(ldec_monobjret));
	   ls_origen="1";
	   if ((ls_documento!="") && uf_validar_montos(fila))   
	      {
	   	    ldec_monobjret = uf_convertir(ldec_monobjret + ldec_monto);
	        ldec_monto     = uf_convertir(ldec_monto);
		    pagina="sigesp_cat_deducciones_otros.php?monto="+ldec_monto+"&objret="+ldec_monobjret+"&txtdocumento="+ls_documento+"&origen="+ls_origen+"&fila="+fila;
		    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,resizable=yes,location=no");
	      }
	   else
	      {
	   	    if (ls_documento=="")
			   {
				 alert("Introduzca un numero de documento !!!");
			   }
			else
			   {
			     alert("La sumatoria del Total sin Iva, Base Imponible y Total Impuesto debe ser menor o igual al Total con Iva");
			   }
	      }
   }	
   function uf_validar_montos(fila)
   {
     f = document.form1;
	 ldec_monto_sin_iva  = eval("f.txtsiniva"+fila+".value");
	 ldec_monto_sin_iva  = parseFloat(uf_convertir_monto(ldec_monto_sin_iva));
	 ldec_monto_con_iva  = eval("f.txtconiva"+fila+".value");
	 ldec_monto_con_iva  = parseFloat(uf_convertir_monto(ldec_monto_con_iva));
	 ldec_base_imponible = eval("f.txtbasimp"+fila+".value");
	 ldec_base_imponible = parseFloat(uf_convertir_monto(ldec_base_imponible));
	 ldec_total_impuesto = eval("f.txttotimp"+fila+".value");
	 ldec_total_impuesto = parseFloat(uf_convertir_monto(ldec_total_impuesto));
	 return ((ldec_monto_sin_iva+ldec_base_imponible+ldec_total_impuesto) <= ldec_monto_con_iva)
   }				
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>