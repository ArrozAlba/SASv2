<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$dat=$_SESSION["la_empresa"];
	
   //--------------------------------------------------------------
   function uf_select_combo($ls_plazo,&$as_selected0,&$as_selected30,&$as_selected60,&$as_selected90,&$as_selected120)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Luis Lang
		// Fecha Creación: 27/02/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		switch ($ls_plazo)
		{
			case"0":
				$as_selected0="selected";
				$as_selected30="";
				$as_selected60="";
				$as_selected90="";
				$as_selected120="";
			break;
			case"30":
				$as_selected0="";
				$as_selected30="selected";
				$as_selected60="";
				$as_selected90="";
				$as_selected120="";
			break;
			case"60":
				$as_selected0="";
				$as_selected30="";
				$as_selected60="selected";
				$as_selected90="";
				$as_selected120="";
			break;
			case"90":
				$as_selected0="";
				$as_selected30="";
				$as_selected60="";
				$as_selected90="selected";
				$as_selected120="";
			break;
			case"120":
				$as_selected0="";
				$as_selected30="";
				$as_selected60="";
				$as_selected90="";
				$as_selected120="selected";
			break;
		}

   }
   //--------------------------------------------------------------
	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definici&oacute;n de Colocaciones</title>
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
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
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
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
  </tr>
</table>
<?php
require_once("sigesp_scb_c_colocacion.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/sigesp_c_check_relaciones.php");
require_once("../../shared/class_folder/grid_param.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../class_folder/class_funciones_configuracion.php");

$io_conect  = new sigesp_include();//Instanciando la Sigesp_Include.
$conn       = $io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_sql     = new class_sql($conn);//Instanciando la Clase Class Sql.
$io_msg     = new class_mensajes();	
$io_chkrel  = new sigesp_c_check_relaciones($conn);
$lb_guardar = true;
$io_grid        = new grid_param();
$io_fecha=new class_fecha();
$io_ds          = new class_datastore(); //Instanciando la clase datastore
$io_funciones= new class_funciones();
$io_fun_cfg= new class_funciones_configuracion();

  $title[1]="Fecha Reintegro Interés";
  $title[2]="Monto a Reintegrar"; //$title[3]="Edición"; 
  $grid="grid";
  $li_totrows=1;
	//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre       = $_SESSION["la_empresa"];
	$ls_empresa = $arre["codemp"];
	$ls_codemp  = $ls_empresa;
	if(array_key_exists("la_logusr",$_SESSION))
	{
		$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
		$ls_logusr="";
	}
	$ls_sistema="CFG";
	$ls_ventanas="sigesp_scb_d_colocacion.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventanas;
    $in_classcolocacion=new sigesp_scb_c_colocacion($la_security);
	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos=             $_POST["permisos"];
			$la_accesos["leer"]=     $_POST["leer"];
			$la_accesos["incluir"]=  $_POST["incluir"];
			$la_accesos["cambiar"]=  $_POST["cambiar"];
			$la_accesos["eliminar"]= $_POST["eliminar"];
			$la_accesos["imprimir"]= $_POST["imprimir"];
			$la_accesos["anular"]=   $_POST["anular"];
			$la_accesos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]="";
		$la_accesos["incluir"]="";
		$la_accesos["cambiar"]="";
		$la_accesos["eliminar"]="";
		$la_accesos["imprimir"]="";
		$la_accesos["anular"]="";
		$la_accesos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
/////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
    $li_cuantos=0;
    $in_classcolocacion->uf_scb_select_cuentas_ingresos($ls_codemp,$li_cuantos);
   function uf_agregarlineablanca(&$object,$li_totrows)
   { 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $object // arreglo de titulos 
		//				   $li_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que agrega una linea en blanco al final del grid
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 23/03/2006 								Fecha Última Modificación : 23/03/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		  $object[$li_totrows][1]="<input type=text name=txtfechrei".$li_totrows."  value=''  class=sin-borde  readonly  style=text-align:center  size=30>";
		  $object[$li_totrows][2]="<input type=text name=txtmontorei".$li_totrows."  value=''  class=sin-borde  readonly  style=text-align:center  size=30>";

   }
	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_colocacion = $_POST["txtcolocacion"];
		$ls_dencol     = $_POST["txtdencolocacion"];
		$ls_tipocta    = $_POST["txttipocuenta"];
		$ls_dentipcta  = $_POST["txtdentipocuenta"];
		$ls_codban   = $_POST["txtcodban"];
		$ls_denban   = $_POST["txtdenban"];
		$ls_cuenta_banco = $_POST["txtcuenta"];
		$ls_dencuenta_banco=$_POST["txtdenominacion"];
		$ls_tasa= $_POST["txttasa"];
		$ld_desde= $_POST["txtdesde"];
		$ldec_monto=$_POST["txtmonto"];
		$ldec_interes=$_POST["txtinteres"];
		$ls_codtipcol=$_POST["txtcodigotipcol"];
		$ls_nomtipcol=$_POST["txtdenotipcol"];
		//$ls_ctascgspi=$_POST["hidscgcuenta"];
		$ls_cuentascg=$_POST["txtcuentacontable"];
		$ls_dencuentascg=$_POST["txtdencuenta"];
		$ls_cuentaspi=$_POST["txtcuentaspi"]; 
		$ls_codigo=$_POST["txtcodigo"];
		$ls_denominacion=$_POST["txtdenominacion2"];
		$ls_codpro=$_POST["txtcodpro"];
		$ls_denpro=$_POST["txtdenpro"];
		$li_lastrow= $_POST["lastrow"];
        $total= $_POST["totrows"];
		
		$li_plazo=$_POST["txtplazo"];
		$readonly   = "";
		$ls_estatus = $_POST["status"];
		if(array_key_exists("rb_reintegro",$_POST))
		{
			if($_POST["rb_reintegro"]=="M")
			{ 
				$li_estrei = "M";
				$li_estreicol=0;
				$li_reintegro = 0;
			}
			else
			{
				$li_estreicol=1;
				$li_estrei= "F";
				$li_reintegro = 1;
			}
		}
		else
		{ 
				$li_estrei="";
				$li_estreicol=0;
				$li_reintegro = 0;
		}
		if (array_key_exists("hidreintegro",$_POST))
	    {
		   $li_estreicol = $_POST["hidreintegro"];
	    }
		else
		{
		   $li_estreicol=0;
		}
		if( array_key_exists("txthasta",$_POST))
	    {
		   $ld_hasta= $_POST["txthasta"];  
		}
		else
		{
		   $ld_hasta="";
		}
		if( array_key_exists("txtdesproben",$_POST))
	    {
		   $ls_denproben= $_POST["txtdesproben"];
		}
		else
		{
		   $ls_denproben="";
		}
		if( array_key_exists("txtprovbene",$_POST))
	    {
		   $ls_codproben= $_POST["txtprovbene"];  
		}
		else
		{
		   $ls_codproben="";
		}
		if( array_key_exists("txtdencuentaspi",$_POST))
	    {
		   $ls_dencuentaspi= $_POST["txtdencuentaspi"];
		}
		else
		{
		   $ls_dencuentaspi="";
		}
	}
	else
	{
		$ls_operacion= "" ;
		$ls_colocacion = "";
		$ls_dencol     = "";
		$ls_tipocta    = "";
		$ls_dentipcta  = "";
		$ls_codban   = "";
		$ls_denban   = "";
		$ls_cuenta_banco = "";
		$ls_dencuenta_banco="";
		$ls_tasa= 0;
		$ld_desde= "";
		$ld_hasta= "";
		$ldec_monto="";
		$ldec_interes="";
		$ls_codtipcol="";
		$ls_nomtipcol="";
		$readonly    = "" ;
		$li_estrei="";
		$checked="";
		$ls_estatus ='N';
	    $li_plazo = 0;
	    $li_cuantos=0;
		$ls_cuentascg="";
		$ls_dencuentascg="";
		$ls_cuentaspi="";
		$ls_dencuentaspi="";
	    $in_classcolocacion->uf_scb_select_cuentas_ingresos($ls_codemp,$li_cuantos);
		$ls_codigo="";
		$ls_denominacion="";
		$ls_codpro="";
		$ls_denpro="";
		$ls_codproben="";
		$ls_denproben="";
        $li_lastrow = 0;
        $total= 1;
		uf_agregarlineablanca($object,1);
		$ls_selected0="selected";
        $ls_selected30="";
        $ls_selected60="";
        $ls_selected90="";
        $ls_selected120="";
	}
	if($ls_operacion == "NUEVO")
	{
		$ls_operacion= "" ;
		$ls_colocacion = "";
		$ls_dencol     = "";
		$ls_tipocta    = "";
		$ls_dentipcta  = "";
		$ls_codban   = "";
		$ls_denban   = "";
		$ls_cuenta_banco = "";
		$ls_dencuenta_banco="";
		$ls_tasa= 0;
		$ld_desde= "";
		$ld_hasta= "";
		$ldec_monto="";
		$ldec_interes="";
		$ls_codtipcol="";
		$ls_nomtipcol="";
		$li_estrei="";
		$li_plazo="0";
		$checked="";
		$readonly="";		
	    $ls_estatus ='N';
	    $li_cuantos=0;
		$ls_cuentaspi="";
	    $ls_dencuentaspi="";
		$ls_cuentascg   = "";
		$ls_dencuentascg= "";
		$ls_codigo="";
		$ls_denominacion="";
		$ls_codpro="";
		$ls_denpro="";
		$ls_codproben="";
		$ls_denproben="";
		$li_lastrow = 0;
        $total = 1;
		$ls_selected0="selected";
        $ls_selected30="";
        $ls_selected60="";
        $ls_selected90="";
        $ls_selected120="";
	    $in_classcolocacion->uf_scb_select_cuentas_ingresos($ls_codemp,$li_cuantos);
		uf_agregarlineablanca($object,1);

	}
	if($ls_operacion == "GUARDAR")
	{
		require_once("sigesp_scb_c_colocacion.php");
		$in_classcolocacion=new sigesp_scb_c_colocacion($la_security);

		$ls_cuentaspi =$_POST["txtcuentaspi"];
		$ls_dencuentaspi=$_POST["txtdencuentaspi"];
		$ls_cuentascg = $_POST["txtcuentacontable"];
		$ls_dencuentascg=$_POST["txtdencuenta"];
		$ld_desde= $_POST["txtdesde"];
		$ld_hasta= $_POST["txthasta"];
		$ls_codigo=$_POST["txtcodigo"];
		$ls_denominacion=$_POST["txtdenominacion2"];
		$ls_codpro=$_POST["txtcodpro"];
		$ls_denopro=$_POST["txtdenpro"];
		$ls_codproben=$_POST["txtprovbene"];
		$ls_denproben=$_POST["txtdesproben"];
		$ls_intereses=$_POST["txtinteres"]; 
		$ls_plazo=$_POST["txtplazo"];
		$ldec_monto=$_POST["txtmonto"];
		$li_total=$_POST["totrows"];
		uf_select_combo($ls_plazo,$ls_selected0,$ls_selected30,$ls_selected60,$ls_selected90,$ls_selected120); 

		$ls_num=$in_classcolocacion->uf_select_colocacion($ls_codban,$ls_cuenta_banco,$ls_colocacion,$ls_cuentascg,$ls_cuentaspi);
		if($ls_num==0)
		{  
		  $lb_valido=$in_classcolocacion->uf_guardar_colocacion($ls_colocacion ,$ls_dencol,$ls_codban,$ls_cuenta_banco,$ls_tasa,
	   		                                                    $ld_desde,$ld_hasta,$ldec_monto,$ldec_interes,$ls_codtipcol,
								                                $ls_cuentascg,$ls_cuentaspi,$li_estreicol,$li_plazo,$ls_codigo,
																$ls_codpro,$ls_codproben);
		 if($lb_valido)
		  {  
		     for ($i=1;$i<=$li_total;$i++)
			   {  
				   $ls_fechareint=$_POST["txtfechrei".$i];
				   $ls_montoreint= $_POST["txtmontorei".$i]; 

				   $object[$i][1]="<input type=text name=txtfechrei".$i."  value='".$ls_fechareint."'  class=sin-borde  readonly  style=text-align:center  size=30>";
				   $object[$i][2]="<input type=text name=txtmontorei".$i."  value='".$ls_montoreint."'  class=sin-borde  readonly  style=text-align:center  size=50>";
  
				   $lb_valido=$in_classcolocacion->uf_guardar_dt_colocacion($ls_colocacion ,$ls_codban,$ls_cuenta_banco,
																		    $ls_fechareint,$ls_montoreint);
			   } 
		  } 
		}
		else
		{
		  $lb_valido=$in_classcolocacion->uf_update_colocacion($ls_colocacion,$ls_dencol,$ls_codban,$ls_cuenta_banco,$ls_tasa,$ld_desde,$ld_hasta,$ldec_monto,
                               $ldec_interes,$ls_codtipcol,$ls_cuentascg,$ls_cuentaspi,$li_estreicol,$ls_plazo,$ls_codigo,
							   $ls_codpro,$ls_codproben);
		   if($lb_valido)
		    { 
			      $lb_valido = $in_classcolocacion->uf_delete_dt_colocacion($ls_colocacion,$ls_codban,$ls_cuenta_banco,
				                                                            '',"");
		      for ($i=1;($i<=$li_total)&&($lb_valido);$i++)
			   {
				   $ls_fechareint=$_POST["txtfechrei".$i];
				   $ls_montoreint= $_POST["txtmontorei".$i];
				     
		           $object[$i][1]="<input type=text name=txtfechrei".$i."  value='".$ls_fechareint."'  class=sin-borde  readonly  style=text-align:center  size=30>";
				   $object[$i][2]="<input type=text name=txtmontorei".$i."  value='".$ls_montoreint."'  class=sin-borde  readonly  style=text-align:center  size=50>";

				   $lb_valido=$in_classcolocacion->uf_guardar_dt_colocacion($ls_colocacion ,$ls_codban,$ls_cuenta_banco,
																		    $ls_fechareint,$ls_montoreint);
		   		  
               }
		    }
		}
		
		$io_msg->message($in_classcolocacion->is_msg_error);
		$readonly="readonly";

	}

if ($ls_operacion == "ELIMINAR")
   {
     $li_total=$_POST["totrows"];
	 $lb_existe = $in_classcolocacion->uf_select_colocacion($ls_codban,$ls_cuenta_banco,$ls_colocacion,$ls_cuentascg,$ls_cuentaspi);
	 if ($lb_existe)
	    {
		  $ls_condicion = " AND (column_name='numcol')";//Nombre del o los campos que deseamos buscar.
		  $ls_mensaje   = "";                              //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
		  $lb_tiene     = $io_chkrel->uf_check_relaciones($ls_codemp,$ls_condicion,"scb_colocacion' AND table_name<> 'scb_dt_colocacion",$ls_colocacion."' AND codban='".$ls_codban."' AND ctaban='".$ls_cuenta_banco."",$ls_mensaje);//Verifica los movimientos asociados a la cuenta 
		  for ($i=1;$i<=$li_total;$i++)
		   {
			   $ls_fechareint=$_POST["txtfechrei".$i];
			   $ls_montoreint= $_POST["txtmontorei".$i];
				 
			   $object[$i][1]="<input type=text name=txtfechrei".$i."  value='".$ls_fechareint."'  class=sin-borde  readonly  style=text-align:center  size=30>";
			   $object[$i][2]="<input type=text name=txtmontorei".$i."  value='".$ls_montoreint."'  class=sin-borde  readonly  style=text-align:center  size=50>";
		   }
		  if (!$lb_tiene)
			 {
			      $lb_valido = $in_classcolocacion->uf_delete_dt_colocacion($ls_colocacion,$ls_codban,$ls_cuenta_banco,
				                                                            $ls_fechareint,$ls_montoreint);
			   if($lb_valido)
			   {
		           $lb_valido = $in_classcolocacion->uf_delete_colocacion($ls_colocacion,$ls_codban,$ls_cuenta_banco);
			   }
			   if ($lb_valido)
			      {
				    $io_sql->commit();
				    $io_msg->message("Registro Eliminado !!!");
				    $ls_operacion   = "" ;
					$ls_colocacion  = "";
					$ls_dencol      = "";
					$ls_tipocta     = "";
					$ls_dentipcta   = "";
					$ls_codban      = "";
					$ls_denban      = "";
					$ls_cuenta_banco= "";
					$ls_dencuenta_banco="";
					$ls_tasa        = 0;
					$ls_desde       = "";
					$ls_hasta       = "";
					$ldec_monto     = "";
					$ldec_interes   = "";
					$ls_codtipcol   = "";
					$ls_nomtipcol   = "";
					$ls_cuentascg   = "";
					$ls_dencuentascg= "";
					$ls_cuentaspi   = "";
					$ls_dencuentaspi= "";
					$readonly       = "";
					$checked        = "";
					$li_status      = 0;
					$li_plazo       = 0;
					$ls_codigo="";
					$ls_denominacion="";
					$ls_codpro="";
					$ls_denpro="";
					$ls_codproben="";
					$ls_denproben="";
					uf_agregarlineablanca($object,1);
				  } 
			   else
			      {
 					$io_sql->rollback();
					$io_msg->message($in_classcolocacion->is_msg_error);
					 for ($i=1;$i<=$li_total;$i++)
					   {
						   $ls_fechareint=$_POST["txtfechrei".$i];
						   $ls_montoreint= $_POST["txtmontorei".$i];
							 
						   $object[$i][1]="<input type=text name=txtfechrei".$i."  value='".$ls_fechareint."'  class=sin-borde  readonly  style=text-align:center  size=30>";
						   $object[$i][2]="<input type=text name=txtmontorei".$i."  value='".$ls_montoreint."'  class=sin-borde  readonly  style=text-align:center  size=50>";
					   }
			      }
			 } 
	      else
 		     {
               $io_msg->message($io_chkrel->is_msg_error);
			 }
        }
	 else
	    {
            $io_msg->message("Este Registro No Existe !!!");
		    $ls_operacion   = "" ;
			$ls_colocacion  = "";
			$ls_dencol      = "";
			$ls_tipocta     = "";
			$ls_dentipcta   = "";
			$ls_codban      = "";
			$ls_denban      = "";
			$ls_cuenta_banco= "";
			$ls_dencuenta_banco="";
			$ls_tasa        = 0;
			$ls_desde       = "";
			$ls_hasta       = "";
			$ldec_monto     = "";
			$ldec_interes   = "";
			$ls_codtipcol   = "";
			$ls_nomtipcol   = "";
			$ls_cuentascg   = "";
			$ls_dencuentascg= "";
			$ls_cuentaspi   = "";
			$ls_dencuentaspi= "";
			$readonly       = "";
			$checked        = "";
			$li_status      = 0;
			$li_plazo       = 0;
			$ls_codigo="";
			$ls_denominacion="";
			$ls_codpro="";
			$ls_denpro="";
			$ls_codproben="";
			$ls_denproben="";
			uf_agregarlineablanca($object,1);
		}	
   }
	
if($li_estrei=="M")	
{
	$lb_estmen="checked";
	$lb_estfin="";
	
}
else if($li_estrei=="F")
{  
	$lb_estmen="";
	$lb_estfin="checked";
	
}elseif ($li_estrei=="")
{
    $lb_estmen="";
	$lb_estfin="";
}

if($ls_operacion == "PINTARGRID")
	{
		require_once("sigesp_scb_c_colocacion.php");
		$in_classcolocacion=new sigesp_scb_c_colocacion($la_security);

		$ls_cuentaspi =$_POST["txtcuentaspi"];
		$ls_dencuentaspi=$_POST["txtdencuentaspi"];
		$ls_cuentascg = $_POST["txtcuentacontable"];
		$ls_dencuentascg=$_POST["txtdencuenta"];
		$ld_desde= $_POST["txtdesde"];
		$ld_hasta= $_POST["txthasta"];
		$ls_codigo=$_POST["txtcodigo"];
		$ls_denominacion=$_POST["txtdenominacion2"];
		$ls_codpro=$_POST["txtcodpro"];
		$ls_denopro=$_POST["txtdenpro"];
		$ls_codproben=$_POST["txtprovbene"];
		$ls_denproben=$_POST["txtdesproben"];
		$ls_intereses=$_POST["txtinteres"]; 
		$ls_plazo=$_POST["txtplazo"];
		$ldec_monto=$_POST["txtmonto"]; 
		$lb_check=$_POST["rb_reintegro"]; 
		uf_select_combo($ls_plazo,$ls_selected0,$ls_selected30,$ls_selected60,$ls_selected90,$ls_selected120); 
		$ndias=30;
		if($ls_plazo==30)
		 {
		    $lb_estfin="checked"; 
			$readonly="readonly";
         }
		 else
		 {
		    $lb_estmen="checked";
		 }			
		$mescolocacion=$ls_plazo/30;
		if($mescolocacion<30)
		{
		   $mescolocacion=1;
		}
		if($lb_check=="M")
		{
		  $li_total=$_POST["totrows"];
		  $ls_intereses=str_replace(".","",$ls_intereses);
		  $ls_intereses=str_replace(",",".",$ls_intereses); 
		  $reintegrofinal=($ls_intereses/$mescolocacion); 
		  $reintegrofinal=round($reintegrofinal,2);
		  $reintegrofinal=str_replace('.',',',$reintegrofinal); 
		}
		else
		{
		  $li_total=1;
		  $ls_intereses=str_replace(".","",$ls_intereses);
		  $ls_intereses=str_replace(",",".",$ls_intereses); 
		  $reintegrofinal=$ls_intereses;
		  $reintegrofinal=round($reintegrofinal,2);
		  $reintegrofinal=str_replace('.',',',$reintegrofinal); 
		}
		for($li_x=1;$li_x<=$li_total;$li_x++)
		{ 
		   if($li_x==1)
		   { 
		       $fecha=$in_classcolocacion->sumarDias($ld_desde,$ndias);
			   $object[$li_x][1]="<input type=text name=txtfechrei".$li_x."  value='".$fecha."'  class=sin-borde  readonly  style=text-align:center  size=30>";
			   $object[$li_x][2]="<input type=text name=txtmontorei".$li_x."  value='".$reintegrofinal."'  class=sin-borde  readonly  style=text-align:center  size=50>";
			   $li_aux=1;
			   $fecha_actual =$fecha;
		   }
		  else
		   {  	
			    $fecha_sig=$in_classcolocacion->sumarDias($fecha_actual,$ndias);
				$fecha_actual=$fecha_sig; 
				
				$object[$li_x][1]="<input type=text name=txtfechrei".$li_x."  value='".$fecha_sig."'  class=sin-borde  readonly  style=text-align:center  size=30>";
				$object[$li_x][2]="<input type=text name=txtmontorei".$li_x."  value='".$reintegrofinal."'  class=sin-borde  readonly  style=text-align:center  size=50>";

				$li_aux=$li_x;
				
				
			}
			$li_p=$li_x;
			if($li_x==1)
			{
			   $fecha_actual=$fecha;
			}
			else
			{
			  $fecha_actual=$fecha_sig;
			}
	     }// fin del for 		 
			
		 if($li_aux==1)
		 {
		    $ld_hasta=$fecha; 
		 }
		 else
		 {
		    $ld_hasta=$fecha_actual;
		 }
	}
	

	
 if($ls_operacion == "LLAMARFUNCION")
	{
	    require_once("sigesp_scb_c_colocacion.php");
		$in_classcolocacion=new sigesp_scb_c_colocacion($la_security);

		$ls_cuentaspi =$_POST["txtcuentaspi"];
		$ls_dencuentaspi=$_POST["txtdencuentaspi"];
		$ls_cuentascg = $_POST["txtcuentacontable"];
		$ls_dencuentascg=$_POST["txtdencuenta"];
		$ld_desde= $_POST["txtdesde"];
		$ld_hasta= $_POST["txthasta"];
		$ls_codigo=$_POST["txtcodigo"];
		$ls_denominacion=$_POST["txtdenominacion2"];
		$ls_codpro=$_POST["txtcodpro"];
		$ls_denopro=$_POST["txtdenpro"];
		$ls_codproben=$_POST["txtprovbene"];
		$ls_denproben=$_POST["txtdesproben"];
		$ls_intereses=$_POST["txtinteres"]; 
		$ls_plazo=$_POST["txtplazo"];
		$ldec_monto=$_POST["txtmonto"];
		$lb_check=$_POST["rb_reintegro"]; 
		uf_select_combo($ls_plazo,$ls_selected0,$ls_selected30,$ls_selected60,$ls_selected90,$ls_selected120); 
		$ndias=30;
		$li_total=$ls_plazo/30; 
		$li_aux=0;
		$li_p=0;
		$fecha_hasta="";
        uf_agregarlineablanca($object,1);
		if($ls_plazo==30)
		 {
		    $lb_estfin="checked"; 
			$readonly="readonly";
			
         }
		 else
		 {
		    $lb_estmen="checked";
		 }			

		$mescolocacion=$ls_plazo/30;
		if($ls_plazo<30)
		{
		   $mescolocacion=1;
		}

		if($lb_check=="M")
		{
		  $li_totalaux=$_POST["totrows"];
		  $ls_intereses=str_replace(".","",$ls_intereses);
		  $ls_intereses=str_replace(",",".",$ls_intereses);
		  $reintegrofinal=($ls_intereses/$mescolocacion); 
		  $reintegrofinal=round($reintegrofinal,2);
//		  $reintegrofinal=str_replace('.',',',$reintegrofinal); 
		}
		else
		{ 
		  $li_totalaux=1;
		  $ls_intereses=str_replace(".","",$ls_intereses);
		  $ls_intereses=str_replace(",",".",$ls_intereses); 
		  $reintegrofinal=$ls_intereses;
		  $reintegrofinal=round($reintegrofinal,2);
//		  $reintegrofinal=str_replace('.',',',$reintegrofinal); 
		}
		if($li_total==1)
		{
		    uf_agregarlineablanca($object,1);
		}
		$totalreintegrofinal=0;
		for($li_x=1;$li_x<=$li_total;$li_x++)
		{ 
		   if($li_x<$li_total)
		   {
		   		$totalreintegrofinal=$totalreintegrofinal+$reintegrofinal;
		   }
		   if($li_x==$li_total)
		   {
		   		$reintegrofinal=$ls_intereses-$totalreintegrofinal;
		   }
		   if($li_x==1)
		   { 
		       $fecha=$in_classcolocacion->sumarDias($ld_desde,$ndias);
			   $object[$li_x][1]="<input type=text name=txtfechrei".$li_x."  value='".$fecha."'  class=sin-borde  readonly  style=text-align:center  size=30>";
			   $object[$li_x][2]="<input type=text name=txtmontorei".$li_x."  value='".number_format($reintegrofinal,2,',','.')."'  class=sin-borde  readonly  style=text-align:center  size=50>";
			   $li_aux=1;
			   $fecha_actual =$fecha;
		   }
		  else
		   {  	
			    $fecha_sig=$in_classcolocacion->sumarDias($fecha_actual,$ndias);
				$fecha_actual=$fecha_sig; 
				
				$object[$li_x][1]="<input type=text name=txtfechrei".$li_x."  value='".$fecha_sig."'  class=sin-borde  readonly  style=text-align:center  size=30>";
				$object[$li_x][2]="<input type=text name=txtmontorei".$li_x."  value='".number_format($reintegrofinal,2,',','.')."'  class=sin-borde  readonly  style=text-align:center  size=50>";
				$li_aux=$li_x;
			}
			$li_p=$li_x;
			if($li_x==1)
			{
			   $fecha_actual=$fecha;
			}
			else
			{
			  $fecha_actual=$fecha_sig;
			}
	     }// fin del for 		 
			
		 if($li_aux==1)
		 {
		    $ld_hasta=$fecha; 
		 }
		 else
		 {
		    $ld_hasta=$fecha_actual;
		 }
	}
	
	
	if ($ls_operacion=="CARGAR")
	 {  
	    $ls_cuentaspi =$_POST["txtcuentaspi"];
		$ls_dencuentaspi=$_POST["txtdencuentaspi"];
		$ls_cuentascg = $_POST["txtcuentacontable"];
		$ls_dencuentascg=$_POST["txtdencuenta"];
		$ld_desde= $_POST["txtdesde"];
		$ld_hasta= $_POST["txthasta"];
		$ls_codigo=$_POST["txtcodigo"];
		$ls_denominacion=$_POST["txtdenominacion2"];
		$ls_codpro=$_POST["txtcodpro"];
		$ls_denopro=$_POST["txtdenpro"];
		$ls_codproben=$_POST["txtprovbene"];
		$ls_denproben=$_POST["txtdesproben"];
		$ldec_interes=$_POST["txtinteres"];
		$ldec_interes=$io_fun_cfg->uf_formatonumerico($ldec_interes); 
		$ls_plazo=$_POST["txtplazo"];
		$ldec_monto=$_POST["txtmonto"];
		$ldec_monto=$io_fun_cfg->uf_formatonumerico($ldec_monto);
		$ls_tasa=$_POST["txttasa"];
		$ls_tasa=$io_fun_cfg->uf_formatonumerico($ls_tasa);
		$li_total=$_POST["totrows"]; 
		uf_select_combo($ls_plazo,$ls_selected0,$ls_selected30,$ls_selected60,$ls_selected90,$ls_selected120); 
		
		$li_row=0;
        $ls_sql=" SELECT scb_dt_colocacion.*,scb_colocacion.codban,scb_colocacion.ctaban,scb_colocacion.numcol ".
                " FROM   scb_dt_colocacion,scb_colocacion ".
                " WHERE  scb_colocacion.codemp='".$ls_codemp."' AND scb_dt_colocacion.codemp=scb_colocacion.codemp AND ".
                "        scb_colocacion.codban=scb_dt_colocacion.codban AND scb_dt_colocacion.ctaban=scb_colocacion.ctaban".
				"        AND scb_dt_colocacion.numcol=scb_colocacion.numcol".
				"        AND scb_dt_colocacion.numcol='".$ls_colocacion."' and scb_dt_colocacion.codban='".$ls_codban."'".
				"        AND scb_dt_colocacion.ctaban='".$ls_cuenta_banco."'"; 

         $rs_data = $io_sql->select($ls_sql);       
         if ($row=$io_sql->fetch_row($rs_data))
	        {
              $data        = $io_sql->obtener_datos($rs_data);
		      $arrcols     = array_keys($data);
		      $totcol      = count($arrcols);
 		      $io_ds->data = $data;
              $totrow      = $io_ds->getRowCount("numcol");
              $total       = $totrow;
              for ($i=1;$i<=$totrow;$i++)	   	   
                  {							                  
				     $li_row=$li_row+1;
					 $ls_fechareint=$io_ds->data["fecreint"][$i]; 
					 $ls_montoreint=$io_ds->data["montoreint"][$i];
					 $ls_montoreint=$io_fun_cfg->uf_formatonumerico($ls_montoreint);  
					 $ls_fechareint=$io_funciones->uf_convertirfecmostrar($ls_fechareint); 
					         
					 $object[$li_row][1]="<input type=text name=txtfechrei".$li_row."  value='".$ls_fechareint."'  class=sin-borde  readonly  style=text-align:center  size=30>";
					 $object[$li_row][2]="<input type=text name=txtmontorei".$li_row."  value='".$ls_montoreint."'  class=sin-borde  readonly  style=text-align:center  size=50>";
                  }
            }
         else
            {
               uf_agregarlineablanca($object,1);
         }
		        	
     } 
?>
<p>&nbsp;</p>
<div align="center">
  <table width="645" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="617" height="221" valign="top"><form name="form1" method="post" action="">
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
		<br>
		<br>
        <table width="574" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="4">Definici&oacute;n de Colocaciones </td>
              </tr>
              <tr class="formato-blanco">
                <td height="22">&nbsp;</td>
                <td height="22" colspan="3"><input name="status" type="hidden" id="status" value="<?php print $ls_estatus ?>"></td>
              </tr>
              <tr class="formato-blanco">
                <td width="101" height="22"><div align="right" >
                    <p>N&uacute;mero</p>
                </div></td>
                <td height="22" colspan="3"><div align="left" >
                    <input name="txtcolocacion" type="text" id="txtcolocacion" style="text-align:center " value="<?php print $ls_colocacion ?>" size="22" maxlength="15" onBlur="javascript:rellenar(this,10)" <?php print $readonly ?> >
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Denominaci&oacute;n</div></td>
                <td height="22" colspan="3"><div align="left">
                  <input name="txtdencolocacion" type="text" id="txtdencolocacion" value="<?php print $ls_dencol;?>" size="80" maxlength="80">
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Banco</div></td>
                <td height="22" colspan="3"><div align="left">
                  <input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
                  <a href="javascript:cat_bancos();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Bancos"></a>
                  <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="65" class="sin-borde" readonly>

                </div></td>
              </tr>
			  
              <tr class="formato-blanco">
                <td height="22"><div align="right">Cuenta Bancaria </div></td>
                <td height="22" colspan="3" align="left"><div align="left">
                  <input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_cuenta_banco; ?>" size="30" maxlength="25" readonly>
                  <a href="javascript:catalogo_cuentabanco();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Cuentas Bancarias"></a>
                  <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_dencuenta_banco; ?>" size="45" maxlength="254" <?php print $readonly ?>>

                  <input name="txttipocuenta" type="hidden" id="txttipocuenta" value="<?php print $ls_tipocta;?>" >                  
                  <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta" value="<?php print $ls_dentipcta;?>">
                  <input name="txtcuenta_scg" type="hidden" id="txtcuenta_scg">
                  <input name="txtdisponible" type="hidden" id="txtdisponible">
</div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Tipo Colocaci&oacute;n </div></td>
                <td height="22" colspan="3" align="left"><div align="left">
                  <input name="txtcodigotipcol" type="text" id="txtcodigotipcol"  style="text-align:center" value="<?php print $ls_codtipcol;?>" size="10" readonly>
                  <a href="javascript:cat_tipocol();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de tipos de Colocaciones"></a>
                  <input name="txtdenotipcol" type="text" id="txtdenotipcol" value="<?php print $ls_nomtipcol?>" size="51" class="sin-borde"  readonly>
                </div></td>
              </tr>
            <tr class="formato-blanco">
                <td height="22"><div align="right">Fecha Inicio </div></td>
                <td height="22"><div align="left" >
                  <input name="txtdesde" type="text" id="txtdesde"  style="text-align:center" value="<?php print $ld_desde;?>" size="22" maxlength="10"  onKeyPress="currencyDate(this);"  datepicker="true">
              </div></td>
                <td height="22"><div align="right">Fecha Culminaci&oacute;n</div></td>
                <td height="22"><div align="left">
                  <input name="txthasta" type="text" id="txthasta"  style="text-align:center" value="<?php print $ld_hasta;?>" size="22" maxlength="10"  onKeyPress="currencyDate(this);" readonly>
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22"><div align="right">Plazo</div></td>
              <td height="22"><div align="left">
                <label>
                <select name="txtplazo" id="txtplazo" onBlur="javascript:uf_calculo_interes();">
                  <option value="0" <?php print $ls_selected0; ?>>0</option>
                  <option value="30" <?php print $ls_selected30; ?>>30</option>
                  <option value="60" <?php print $ls_selected60; ?>>60</option>
                  <option value="90" <?php print $ls_selected90; ?>>90</option>
                  <option value="120" <?php print $ls_selected120; ?>>120</option>
                </select>
                </label>
              </div></td>
              <td height="22"><div align="right">Reintegro</div></td>
              <td width="186" height="22"><div align="left">
                <label>
                <input name="rb_reintegro" type="radio" value="M" <?php print $lb_estmen; ?> onClick="javascript:uf_calculo_reintegro();">
Mensual</label>

              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22"><div align="right">Tasa</div></td>
              <td height="22"><div align="left">
                <input name="txttasa" type="text" id="txttasa" value="<?php print $ls_tasa;?>" style="text-align:right" size="22" maxlength="5" onKeyPress="return(currencyFormat(this,'.',',',event))" >
              </div></td>
              <td height="22">&nbsp;</td>
              <td height="22"><div align="left">
                <p>
                  <label>                  </label>
                  <label>
                  <input type="radio" name="rb_reintegro" value="F"  <?php print $lb_estfin; ?> "<?php print $readonly ?>" onClick="javascript:uf_calculo_reintegro();">
  Final</label>
                  <input name="hidreintegro" type="hidden" id="hidreintegro" value="<?php print $li_reintegro?>">
                  <br>
                </p>
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22"><div align="right">Monto</div></td>
              <td width="175" height="22"><div align="left">
                <input name="txtmonto" type="text" id="txtmonto" value="<?php print $ldec_monto;?>" size="22" style="text-align:right"  onBlur="javascript:uf_calculo_interes();" onKeyPress="return(currencyFormat(this,'.',',',event))">
              </div></td>
			  <?php 
		        $readonly="readonly"; 
     		?>
              <td width="110" height="22"><div align="right">Interes</div></td>
              <td height="22"><div align="left">
			   <input name="txtinteres" type="text" id="txtinteres" value="<?php print $ldec_interes;?>" size="22"  style="text-align:right" readonly='true'  >
              </div></td>
            </tr>
			<tr class="formato-blanco">
              <td height="22"><div align="right">Cuenta Contable </div></td>
              <td height="22" colspan="3"><div align="left">
                <input name="txtcuentacontable" type="text" id="txtcuentacontable" style="text-align:center" value="<?php print $ls_cuentascg; ?>" size="30" maxlength="25" readonly>
                  <a href="javascript:catalogo_cuentascg();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
                  <input name="txtdencuenta" type="text" class="sin-borde" id="txtdencuenta" style="text-align:left" value="<?php print $ls_dencuentascg; ?>" size="45" maxlength="254" readonly>
              </div></td>
            </tr>
			<?php 
		    if($li_cuantos!=0)
			 { 
     		?>
            <tr class="formato-blanco">
              <td height="22"><div align="right">Cuenta Ingresos </div></td>
              <td height="22" colspan="3"><div align="left">
                <input name="txtcuentaspi" type="text" id="txtcuentaspi" style="text-align:center" value="<?php print $ls_cuentaspi; ?>" size="30" maxlength="25" readonly>
                  <a href="javascript:catalogo_cuentaspi();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
                  <input name="txtdencuentaspi" type="text" class="sin-borde" id="txtdencuentaspi" style="text-align:left" value="<?php print $ls_dencuentaspi; ?>" size="45" maxlength="254" readonly>
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22"><div align="right">Concepto de la Nota de Cr&eacute;dito </div></td>
              <td height="22" colspan="3"><input name="txtcodigo" type="text" id="txtcodigo" style="text-align:center " value="<?php print $ls_codigo?>" size="6" maxlength="3" onBlur="javascript:rellenar_cad(this.value,3,'cod')" <?php print $readonly ?> onKeyPress="return keyRestrict(event,'1234567890');">
                <a href="javascript:catalogo_concemov();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
              <input name="txtdenominacion2" type="text" class="sin-borde" id="txtdenominacion2" style="text-align:left" value="<?php print $ls_denominacion?>" size="60" maxlength="80"></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22"><div align="right">Proveedor </div></td>
              <td height="22" colspan="3"><input name="txtcodpro" type="text" id="txtcodpro" value="<?php print $ls_codpro?>" size="15" maxlength="10" style="text-align:center " readonly>
                <a href="javascript:catalogo_proveedor();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>                <a href="javascript: ue_cataproveedor();"></a>
                <input name="txtdenpro" type="text" class="sin-borde" id="txtdenpro" value="<?php print $ls_denpro ?>" size="50" readonly></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22"><div align="right">Beneficiario</div></td>
              <td height="22" colspan="3"><input name="txtprovbene" type="text" id="txtprovbene" value="<?php print $ls_codproben?>" size="15" maxlength="10" style="text-align:center " readonly>
                <a href="javascript:catalogo_beneficiario();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a> <a href="javascript: ue_cataproveedor();"></a>
                <input name="txtdesproben" type="text" class="sin-borde" id="txtdesproben" value="<?php print $ls_denproben ?>" size="50" readonly></td>
            </tr>
			<?php 
			  }
			  else
			  {
			?>
                <input name="txtcuentaspi" type="hidden" id="txtcuentaspi" value="<?php print $ls_cuentaspi; ?>">
				<input name="txtcodigo" type="hidden" id="txtcodigo" value="<?php print $ls_codigo?>">
				<input name="txtcodpro" type="hidden" id="txtcodpro" value="<?php print $ls_codpro?>">
				<input name="txtprovbene" type="hidden" id="txtprovbene" value="<?php print $ls_codproben?>">
                <input name="txtdenominacion2" type="hidden"  id="txtdenominacion2"  value="<?php print $ls_denominacion?>">
                <input name="txtdenpro" type="hidden"  id="txtdenpro" value="<?php print $ls_denpro ?>" >
                <input name="txtdesproben" type="hidden"  id="txtdesproben" value="<?php print $ls_denproben ?>">
                <input name="txtdencuentaspi" type="hidden"  id="txtdencuentaspi"  value="<?php print $ls_dencuentaspi; ?>">
				 <?php			  
			  }
			?>
            <tr class="formato-blanco">
              <td height="22">&nbsp;</td>
              <td height="22" colspan="3">&nbsp;</td>
            </tr>
            
            <tr class="formato-blanco">
              <td height="22" colspan="4"><div align="center">
			  <?php 
			 $io_grid->makegrid($total,$title,$object,500,'Reintegro de Intererses',$grid);
		  ?>
		  </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22"><input name="hidscgcuenta" type="hidden" id="hidscgcuenta" value="<?php print $ls_ctascgspi; ?>"></td>
              <td height="22" colspan="3"><input name="totrows"  type="hidden" id="totrows"     value="<?php print $total;?>">
                <input name="lastrow"  type="hidden"   id="lastrow"    value="<?php print $li_lastrow;?>">
                <input name="filadel"    type="hidden"   id="filadel"></td>
            </tr>
          </table>
          <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion?>">
</form><br>
<br>
</td>
      </tr>
  </table>
</div>
</body>
<script language="javascript">

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if (li_incluir==1)
	   {	
	     f.operacion.value ="NUEVO";
		 f.action="sigesp_scb_d_colocacion.php";
		 f.submit();
	   }
	else
	   {
 	     alert("No tiene permiso para realizar esta operacion");
	   } 
}

function ue_guardar()
{
	f          = document.form1;
	li_incluir = f.incluir.value;
	li_cambiar = f.cambiar.value;
	lb_status  = f.status.value;
	if (((lb_status=="C")&&(li_cambiar==1))||(lb_status=="N")&&(li_incluir==1))
	   {
 	     ls_colocacion= f.txtcolocacion.value;
	     ls_tipcol   = f.txtcodigotipcol.value;
	     ls_codban    = f.txtcodban.value;
	     ls_ctaban    = f.txtcuenta.value;
		 ls_cuentaspi = f.txtcuentaspi.value;
		 ls_dencuentaspi=f.txtdencuenta.value;
		 ls_cuentascg = f.txtcuentacontable.value;
		 ls_dencuentascg=f.txtdencuenta.value;
		/* if(ls_cuentaspi=="")
		 {
	     	ls_cuentascg = f.txtcuentacontable.value;
		 }
		 else
		 {
	     	ls_cuentascg = f.hidscgcuenta.value;
		 }	*/
	     ls_dencol    = f.txtdencolocacion.value;
		 if ((ls_colocacion!="")&&(ls_tipcol!="")&&(ls_codban!="")&&(ls_ctaban!="")&&(ls_cuentascg!="")&&(ls_dencol!=""))
	 	    {
			  f.operacion.value ="GUARDAR";
			  f.action="sigesp_scb_d_colocacion.php";
			  f.submit();
		    }
		 else
		    {
			  alert("No ha completado los datos");
	 	    }
       }
	else
	   {
 	     alert("No tiene permiso para realizar esta operacion");
	   }
}

function ue_eliminar()
{
f=document.form1;
li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
     if (confirm("¿ Está seguro de eliminar este registro ?"))
		{
	      ls_colocacion= f.txtcolocacion.value;
		  ls_tipcol    = f.txtcodigotipcol.value;
		  ls_codban    = f.txtcodban.value;
		  ls_ctaban    = f.txtcuenta.value;
		  ls_cuentaspi = f.txtcuentaspi.value;
		  ls_dencuentaspi=f.txtdencuenta.value;
		  ls_cuentascg = f.txtdencuentaspi.value;
		  ls_dencuentascg=f.txtdencuenta.value;
		  ls_dencol    = f.txtdencolocacion.value;
		  if ((ls_colocacion!="")&&(ls_tipcol!="")&&(ls_codban!="")&&(ls_ctaban!="")&&(ls_cuentascg!="")&&(ls_dencol!=""))
			 { 
			   f.operacion.value ="ELIMINAR";
			   f.action="sigesp_scb_d_colocacion.php";
			   f.submit();
			 }
		  else
			{
			  alert("No ha completado los datos");
			}
        }
     else
	    {
		  alert("Eliminación Cancelada !!!");
		}		
    }
  else
    {
 	  alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
    {
	   window.open("sigesp_scb_cat_colocacion.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,left=50,top=50,location=no,resizable=yes");
    }
	else
    {
	   alert("No tiene permiso para realizar esta operacion");
    }   
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

    //Funciones de validacion de fecha.
	function rellenar(obj,longitud)
	{
		var mystring=new String(obj.value);
		cadena_ceros="";
		lencad=mystring.length;
			
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+mystring;
		obj.value=cadena;
	}
	
	//Catalogo de cuentas contables
	function catalogo_cuentabanco()
	 {
	   f=document.form1;
	   ls_codban=f.txtcodban.value;
	   ls_denban=f.txtdenban.value;
	   if((!f.txtcolocacion.readonly)&&(!f.txtcolocacion.readonly))
	   {
		   if((ls_codban!=""))
		   {
			   pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&denban="+ls_denban;
			   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
		   }
		   else
		   {
				alert("Seleccione el Banco");   
		   }
	   }
	   else
	   {
	   	alert("No puede Editar la Cuenta, forma parte de la clave primaria ");
	   }	   
	 }
	 
	 function cat_tipocol()
	 {
	   f=document.form1;
	   pagina="sigesp_cat_tipocolocacion.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
	 
	 function catalogo_cuentascg()
	 {
	   f=document.form1;
	   pagina="sigesp_cat_filt_scg.php?filtro="+'11102'+"&opener=sigesp_scb_d_colocacion.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
	 
	 function catalogo_cuentaspi()
	 {
	   f=document.form1;
	   pagina="sigesp_cat_ctasspi.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no");
	 }
	 
	 function cat_bancos()
	 {
	   pagina="sigesp_cat_bancos.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
	 
	 function catalogo_concemov()
	 {
	   pagina="sigesp_scb_cat_conceptos.php?destino=destino";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
	 
	 function catalogo_proveedor()
	 {
	   pagina="sigesp_catdinamic_prov.php?proveedor=proveedor";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
	 
	 function catalogo_beneficiario()
	 {
	   pagina="sigesp_catdin_bene.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
	function redondear(cantidad, decimales) 
	{
		var cantidad = parseFloat(cantidad);
		var decimales = parseFloat(decimales);
		    decimales = (!decimales ? 2 : decimales);
	    return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);
	}

	function uf_calculo_interes()
	{
		f           = document.form1;
		ldec_monto  = f.txtmonto.value;
		ldec_monto  = uf_convertir_monto(ldec_monto);
		ldec_tasa   = f.txttasa.value;
		ldec_tasa   = uf_convertir_monto(ldec_tasa);
		li_dias     = f.txtplazo.value;
		ldec_monint = (ldec_monto*ldec_tasa*li_dias)/(360*100);
		ldec_monint=redondear(ldec_monint,2);
		f.txtinteres.value=uf_convertir(ldec_monint);
      	ls_fechaini=f.txtdesde.value;
	    ls_fechahasta=f.txthasta.value;
		ls_plazo =f.txtplazo.value;
		ls_fechaini=ls_fechaini.split('/');
		mesini = (ls_fechaini[1]);  
		ls_fechaculm=ls_fechahasta.split('/'); 
	    diafin = parseInt(ls_fechaculm[0]); 
	    mesfin = parseInt(ls_fechaculm[1]); 
	    anofin = parseInt(ls_fechaculm[2]);
		if(mesini!=mesfin)
		{
		  ls_meses=ls_plazo/30; 
		}
		else
		{
		  ls_meses=1; 
		}
		if(ls_plazo==30)
		{ 
		   f.rb_reintegro[1].checked=true;
		   f.rb_reintegro[1].readonly=true;
		}
		else
		{
		   f.rb_reintegro[0].checked=true; 
		}
		if((ls_fechaini!="")&&(ls_plazo>0))
		{
			f.totrows.value=ls_meses;
			f.operacion.value='LLAMARFUNCION';
			f.action="sigesp_scb_d_colocacion.php";
			f.submit();
		}
     }
	
	function uf_calculo_reintegro()
	{ 
		f= document.form1;
		total=f.totrows.value;
        ls_fechaini=f.txtdesde.value;
	    ls_fechahasta=f.txthasta.value;
		ls_plazo =f.txtplazo.value;
		ls_fechaini=ls_fechaini.split('/');
		mesini = (ls_fechaini[1]);  
		ls_fechaculm=ls_fechahasta.split('/'); 
	    diafin = parseInt(ls_fechaculm[0]); 
	    mesfin = parseInt(ls_fechaculm[1]); 
	    anofin = parseInt(ls_fechaculm[2]);
		if(mesini!=mesfin)
		{
		  ls_meses=ls_plazo/30; 
		}
		else
		{
		  ls_meses=1; 
		}
	    if(ls_plazo==30)
		{ 
		   f.rb_reintegro[1].checked=true;
		}
		else
		{
		  f.rb_reintegro[0].checked=true;
		}
		if((ls_fechaini!="")&&(ls_plazo>0))
		{
			f.totrows.value=ls_meses;
			f.operacion.value='PINTARGRID';
			f.action="sigesp_scb_d_colocacion.php";
			f.submit();	
		}
   }
	
function calcular_fechafin() 
   //////////////////////////////////////////////////////
   // Función que dada la fecha de inicio y el 
   // plazo en días calcula la fecha de culminación
   // Agregado:24/09/08 
   /////////////////////////////////////////////////////
   {
      f=document.form1;
	  num=f.txtplazo.value; 
	  ls_fechaini=f.txtdesde.value; 
	  // pasaremos la fecha a formato mm/dd/yyyy 
	  ls_fechaini=ls_fechaini.split('/'); 
	  ls_fechaini=ls_fechaini[1]+'/'+ls_fechaini[0]+'/'+ls_fechaini[2];  
	  // 
	  hoy=new Date(ls_fechaini); 
	  s= hoy.setTime(hoy.getTime()+num*24*60*60*1000);
	  mes=hoy.getMonth()+1;
	  if(mes<9)
	  {
	     mes='0'+mes;
	  } 
	  fecha=hoy.getDate()+'/'+mes+'/'+hoy.getFullYear();
	  fecha=fecha.split('/'); 
	  dia = parseInt(fecha[0]);
	  mes = parseInt(fecha[1]); 
	  ano = parseInt(fecha[2]); 
	  if (dia<10)
      {
	    dia='0'+dia;  
	  }
	  if (mes<10)
      {
	    mes='0'+mes;  
	  }
	  fecha=dia+'/'+mes+'/'+ano;
	  return fecha; 
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
			li_string=parseInt(ls_string);
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
			li_string=parseInt(ls_string);
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
			li_string=parseInt(ls_string);
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
   
 function currencyFormat(fld, milSep, decSep, e) { 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
    if (whichCode == 8) return true; // Backspace <-
    if (whichCode == 127) return true; // Suprimir -Del 
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
<script language="javascript" src="../../shared/js/js_intra/datepickercontrol.js"></script>
</html>
