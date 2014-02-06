<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='../../sigesp_conexion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Inicio de Contadores</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="soc/js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {
	color: #6699CC;
	font-size: 14;
}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-negrita"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right" class="letras-negrita"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php 
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/sigesp_c_check_relaciones.php"); 
require_once("class_folder/sigesp_cfg_c_inicio_contadores.php");

$io_servicioect = new sigesp_include();//Instanciando la Sigesp_Include.
$conn           = $io_servicioect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_sql         = new class_sql($conn);//Instanciando la Clase Class Sql.
$io_msg         = new class_mensajes();//Instanciando la Clase Class  Mensajes.
$io_funciondb   = new class_funciones_db($conn);
$io_grid        = new grid_param();
$io_ds          = new class_datastore(); //Instanciando la clase datastore
$io_chkrel      = new sigesp_c_check_relaciones($conn);
$lb_existe      = "";
$io_funcion     = new class_funciones(); 

/////////////////////////////////////////////////////////////////////////////
$io_contadores = new sigesp_cfg_c_inicio_contadores($conn);


//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_codemp   = $ls_empresa;
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "CFG";
	$ls_ventanas = "sigesp_cfg_d_inicio_de_contadores.php";

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;

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
		$ls_permisos            = $io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion         = $_POST["operacion"];
     $ls_id                = $_POST["txtid"]; 
     $ls_codsis            = $_POST["txtcodsis"];
   	 $ls_densis            = $_POST["txtdensis"];
	 $ls_procede           = $_POST["txtprocede"];
   	 $ls_denpro            = $_POST["txtdenpro"];	  
  	 $li_nro_inicial       = $_POST["txtnro_inicial"];	  
  	 $li_nro_final         = $_POST["txtnro_final"];
	 $ls_prefijo           = $_POST["txtprefijo"];
	 
	 $li_lastrow           = $_POST["lastrow"];
     $total                = $_POST["totrows"];
     $ls_estatus           = $_POST["hidestatus"];
	 
	 if(array_key_exists("chk",$_POST))
	 {
		if($_POST["chk"]==1)
		{
			$checked   = "checked" ;	
			$li_estatus = 1;
		}
		else
		{
			$li_estatus = 0;
			$checked="";
		}
	 }
	 else
	 {
			$li_estatus = 0;
			$checked="";
	 }
   }
else
   {
     $ls_operacion    = "NUEVO";
     $ls_id           = ""; 
     $ls_codsis       = "";
   	 $ls_densis       = "";
	 $ls_procede      = "";
	 $ls_denpro       = "";
  	 $li_nro_inicial  = "";	  
  	 $li_nro_final    = "";	  
     $ls_prefijo      = "";	
	 
     $li_lastrow      = 0;
     $total           = 0;
 	 $ls_estatus      = "NUEVO";	  
   }
   $lb_empresa = true;
		
//Titulos de la tabla de Detalle Bienes
  $title[1]="Código"; $title[2]="Sistema"; $title[3]="Prefijo"; $title[4]="Numero Inicial";
  $title[5]="Numero Final"; $title[6]="Id Actual"; $title[7]="Edición"; 
  $grid="grid";	

//----------------------------------------------------------------------------------------------------------------------------------
if ($ls_operacion=="NUEVO")
{
	 $ls_id=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sigesp_ctrl_numero','id');
	 if(empty($ls_id))
	 {
	    $io_msg->message($io_funciondb->is_msg_error);
	 }    
	 $ls_codsis            = "";	 $ls_densis            = "";
	 $ls_procede           = "";	 $ls_denpro            = "";	  
	 $li_nro_inicial       = "";     $li_nro_final         = "";
	 $ls_prefijo           = "";     $checked="";
     for ($i=1;$i<=2;$i++)
	 {//4 			   
		  $object[$i][1]="<input type=text name=txtid".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=5>";
		  $object[$i][2]="<input type=text name=txtprocede".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=30>";
		  $object[$i][3]="<input type=text name=txtprefijo".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=10>";
		  $object[$i][4]="<input type=text name=txtnro_inicial".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=10>";
		  $object[$i][5]="<input type=text name=txtnro_final".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=10>";
	      $object[$i][6]="<input name=chk".$i." type=checkbox id=chk".$i." value=0 class=sin-borde print $checked >";
		  $object[$i][7]="<a href=javascript:uf_actualizar(".$i.");><img src=../shared/imagebank/tools20/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
	}
	$total=2;	
}
//----------------------------------------------------------------------------------------------------------------------------------
  if ($ls_operacion=="CARGAR")
  {       
	 $li_row=0;
	 $ls_id      = $_POST["txtid"];
	 $ls_codsis  = $_POST["txtcodsis"];
	 $ls_procede = $_POST["txtprocede"];
	 $ls_sql=" SELECT *                            ".
			 " FROM   sigesp_ctrl_numero           ".
			 " WHERE  codemp='".$ls_codemp."' AND  ".
			 "        codsis='".$ls_codsis."' AND  ".
			 "        procede='".$ls_procede."'    "; 
	 $rs_data = $io_sql->select($ls_sql);       
	 if ($row=$io_sql->fetch_row($rs_data))
	 {
		$data        = $io_sql->obtener_datos($rs_data);
		$arrcols     = array_keys($data);
		$totcol      = count($arrcols);
		$io_ds->data = $data;
		$totrow      = $io_ds->getRowCount("id");
		$total       = $totrow;
		$li_lastrow  = $totrow;
	    
		for ($i=1;$i<=$totrow;$i++)	   	   
		{							                  
		   $ls_codsis      = trim($data["codsis"][$i]);
		   $ls_procede     = trim($data["procede"][$i]);
		   $ls_despro="";
           $lb_valido=$io_contadores->uf_select_denominacion_procede($ls_despro,$ls_procede); 
		   $ls_id          = trim($data["id"][$i]);    
		   $ls_prefijo     = trim($data["prefijo"][$i]);
		   $li_nro_inicial = trim($data["nro_inicial"][$i]);
		   $li_nro_final   = trim($data["nro_final"][$i]);
		   $ls_estidact    = trim($data["estidact"][$i]);
		   if($ls_estidact==1)
		   {
   			 $checked     = "checked" ;
			 $ls_disabled =	"disabled";
		   }
		   else
		   {
   			 $checked   = "" ;	
			 $ls_disabled =	"";
		   }
		  //$li_row    = $li_row+1;
		   
		   $object[$i][1]="<input name=txtid".$i." type=text  id=txtid".$i."  class=sin-borde  size=5 style=text-align:center   value='".$ls_id."' readonly>";
		   $object[$i][2]="<input name=txtprocede".$i." type=text  id=txtprocede".$i."  class=sin-borde  size=30 style=text-align:center  value='".$ls_procede."' readonly>";
		   $object[$i][3]="<input name=txtprefijo".$i." type=text  id=txtprefijo".$i."  class=sin-borde  size=10 style=text-align:center  value='".$ls_prefijo."' readonly>";
		   $object[$i][4]="<input name=txtnro_inicial".$i." type=text  id=txtnro_inicial".$i."  class=sin-borde  size=10 style=text-align:center  value='".$li_nro_inicial."' readonly>";
		   $object[$i][5]="<input name=txtnro_final".$i." type=text  id=txtnro_final".$i."  class=sin-borde  size=10 style=text-align:center  value='".$li_nro_final."' readonly>";
		   $object[$i][6]="<input name=chk".$i." type=checkbox id=chk".$i." value=$ls_estidact class=sin-borde print $checked >";
		   $object[$i][7]="<a href=javascript:uf_actualizar(".$i.");><img src=../shared/imagebank/tools20/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
		}           
	 }
	 else
	 {
		 for ($i=1;$i<=2;$i++)
		 {//4 			   
		   $object[$i][1]="<input type=text name=txtid".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=5>";
		   $object[$i][2]="<input type=text name=txtprocede".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=30>";
		   $object[$i][3]="<input type=text name=txtprefijo".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=10>";
		   $object[$i][4]="<input type=text name=txtnro_inicial".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=10>";
		   $object[$i][5]="<input type=text name=txtnro_final".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=10>";
		   $object[$i][6]="<input name=chk".$i." type=checkbox id=chk".$i." value=0 class=sin-borde print $checked >";
		   $object[$i][7]="<a href=javascript:uf_actualizar(".$i.");><img src=../shared/imagebank/tools20/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
		 }
		 $total=2;	
	 }
  }   

//----------------------------------------------------------------------------------------------------------------------------------
if ($ls_operacion=="GUARDAR")
{     
     $ls_id                = $_POST["txtid"]; 
     $ls_codsis            = $_POST["txtcodsis"];
   	 $ls_densis            = $_POST["txtdensis"];
	 $ls_procede           = $_POST["txtprocede"];
   	 $ls_denpro            = $_POST["txtdenpro"];	  
  	 $li_nro_inicial       = $_POST["txtnro_inicial"];	  
  	 $li_nro_final         = $_POST["txtnro_final"];
	 $ls_prefijo           = $_POST["txtprefijo"];
     
	 $lb_existe=$io_contadores->uf_select_nro_control_id($ls_codemp,$ls_id,$ls_codsis,$ls_procede);
	 if ($lb_existe)
     {           
		  if($ls_estatus=="GRABADO")
		  {
		       $lb_valido=$io_contadores->uf_update_contador($ls_codemp,$ls_id,$ls_codsis,$ls_procede,$li_nro_inicial,$li_nro_final,$ls_prefijo,$la_seguridad);
		       if ($lb_valido)
		       {
			        $io_sql->commit();
			        $io_msg->message("Registro Actualizado !!!");
					$ls_codsis            = "";
					$ls_densis            = "";
					$ls_procede           = "";
					$ls_denpro            = "";	  
					$li_nro_inicial       = "";	  
					$li_nro_final         = "";
					$ls_prefijo           = "";
					$ls_estatus="NUEVO";
					$li_lastrow=0;
					$ls_id=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sigesp_ctrl_numero','id');
			   }
		       else
		       {
			        $io_sql->rollback();
		            $io_msg->message("Error en Actualización !!!");
			   }
		  } 	 
	  } 
	  else
	  {  
		  $lb_valido=$io_contadores->uf_insert_contador($ls_codemp,$ls_id,$ls_codsis,$ls_procede,$li_nro_inicial,$li_nro_final,$ls_prefijo,$la_seguridad);
		  if ($lb_valido)
		  {
			    $io_sql->commit();
			    $io_msg->message("Registro Incluido !!!");
				$ls_codsis            = "";
				$ls_densis            = "";
				$ls_procede           = "";
				$ls_denpro            = "";	  
				$li_nro_inicial       = "";	  
				$li_nro_final         = "";
				$ls_prefijo           = "";
				$ls_estatus="NUEVO";
				$li_lastrow=0;
				$ls_id=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sigesp_ctrl_numero','id');
		  }
          else
		  {
			   $io_sql->rollback();
		       $io_msg->message("Error en Inclusión !!!");
		  }
	 }
     for ($i=1;$i<=2;$i++)
     { 			   
		  $object[$i][1]="<input type=text name=txtid".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=5>";
		  $object[$i][2]="<input type=text name=txtprocede".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=30>";
		  $object[$i][3]="<input type=text name=txtprefijo".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=10>";
		  $object[$i][4]="<input type=text name=txtnro_inicial".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=10>";
		  $object[$i][5]="<input type=text name=txtnro_final".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=10>";
	      $object[$i][6]="<input name=chk".$i." type=checkbox id=chk".$i." value=0 class=sin-borde print $checked >";
		  $object[$i][7]="<a href=javascript:uf_actualizar(".$i.");><img src=../shared/imagebank/tools20/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
	 }
     $total=2;
} 
//----------------------------------------------------------------------------------------------------------------------------------
if($ls_operacion=="ACTUALIZAR")
{
     $li_total       = $_POST["totrows"];
     $li_rowact      = $_POST["filaact"];
	 $ls_codsis      = $_POST["txtcodsis"];
	 $ls_densis      = $_POST["txtdensis"];
	 $ls_denpro      = $_POST["txtdenpro"];	  
	 $li_nro_inicial = $_POST["txtnro_inicial"];	  
	 $li_nro_final   = $_POST["txtnro_final"];
	 $ls_prefijo     = $_POST["txtprefijo"];
	 for($li=1;$li<=$li_total;$li++)
	 {
		$ls_id          = $_POST["txtid".$li]; 
	    $ls_procede     = $_POST["txtprocede".$li];

		 if($li==$li_rowact)
		 {
		   $ls_estidact=1;
		 }
		 else
		 {
		   $ls_estidact=0;
		 }
		 $lb_existe=$io_contadores->uf_select_nro_control_id($ls_codemp,$ls_id,$ls_codsis,$ls_procede);
		 if ($lb_existe)
		 {           
			  if($ls_estatus=="GRABADO")
			  {
				   $lb_valido=$io_contadores->uf_update_id_actual($ls_codemp,$ls_id,$ls_codsis,$ls_procede,$ls_estidact,$la_seguridad);
				   if ($lb_valido)
				   {
						$io_sql->commit();
						$io_msg->message("Registro Actualizado !!!");
						$ls_codsis            = "";
						$ls_densis            = "";
						$ls_procede           = "";
						$ls_denpro            = "";	  
						$li_nro_inicial       = "";	  
						$li_nro_final         = "";
						$ls_prefijo           = "";
						$ls_estatus="NUEVO";
						$li_lastrow=0;
						$ls_id=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sigesp_ctrl_numero','id');
				   }
				   else
				   {
						$io_sql->rollback();
						$io_msg->message("Error en Actualización !!!");
				   }
			  } 	 
		  } 
		
     }
     for ($i=1;$i<=2;$i++)
     { 			   
		  $object[$i][1]="<input type=text name=txtid".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=5>";
		  $object[$i][2]="<input type=text name=txtprocede".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=30>";
		  $object[$i][3]="<input type=text name=txtprefijo".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=10>";
		  $object[$i][4]="<input type=text name=txtnro_inicial".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=10>";
		  $object[$i][5]="<input type=text name=txtnro_final".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=10>";
	      $object[$i][6]="<input name=chk".$i." type=checkbox id=chk".$i." value=0 class=sin-borde print $checked >";
		  $object[$i][7]="<a href=javascript:uf_actualizar(".$i.");><img src=../shared/imagebank/tools20/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
	 }
     $total=2;
}
//----------------------------------------------------------------------------------------------------------------------------------
	if($ls_operacion=="DELETEROW")
    {             
       $li_total  =$_POST["totrows"];
       $total     =$li_total-1;
       $li_lastrow=$total;
       $li_rowdel =$_POST["filadel"];
       $li_temp   =0;                
       $ls_numeli =$_POST["txtcodcar".$li_rowdel];         

       for($li_i=1;$li_i<=$li_total;$li_i++)
       {
	        if($li_i!=$li_rowdel)
	        {		
		       $li_temp  =$li_temp+1;
               $ls_codcar=$_POST["txtcodcar".$li_i];
	           $ls_dencar=$_POST["txtdencar".$li_i];      
               $ld_porcar=$_POST["txtporcar".$li_i];      
			   
               $object[$li_temp][1]="<input name=txtcodcar".$li_temp."  type=text id=txtcodcar".$li_temp."  class=sin-borde  size=5  value='".$ls_codcar."' readonly>";
	           $object[$li_temp][2]="<input name=txtdencar".$li_temp."  type=text id=txtdencar".$li_temp."  class=sin-borde  size=30 value='".$ls_dencar."' readonly>";
	           $object[$li_temp][3]="<input name=txtporcar".$li_temp."  type=text id=txtporcar".$li_temp."  class=sin-borde  size=10 value='".$ld_porcar."'>";
	           $object[$li_temp][4]="<a href=javascript:uf_delete(".$li_temp.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
            }
	        else
	        {	
		       $li_rowdelete=0;
	        }
         }
    }
//----------------------------------------------------- esto no lo uso -------------------------------------------------------------
    function uf_cargar_det()
	   {//1            
		  global $class_grid;
		  global $total;
		  global $title;
		  global $align;
		  global $size;
		  global $maxlength;
		  global $values;
		  global $totrow;
		  global $validaciones;	
		  global $object; 
		  global $total; 
		  global $li_totrows;
								 
		  $total=$_POST["totrows"];
		  $li_totrows=$total+1;
		  $row=0;
		  $y=0;   
		  $ld_montototal=0;   
		  for ($li_i=1;$li_i<=$total;$li_i++)
			  {//2
				$ls_codcar    =$_POST["txtcodcar".$li_i];
				$ls_dencar    =$_POST["txtdencar".$li_i];
				$ld_porcar    =$_POST["txtporcar".$li_i];    
				  
				$object[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde  size=5  style=text-align:center  value='".$ls_codcar."'  readonly>";
				$object[$li_i][2]="<input name=txtdencar".$li_i." type=text id=txtdencar".$li_i." class=sin-borde  size=30  style=text-align:center   value='".$ls_dencar."'>";
				$object[$li_i][3]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." class=sin-borde  size=10  style=text-align:center  value='".$ld_porcar."'    readonly>";
				$object[$li_i][4]="<a href=javascript:uf_delete(".$li_i.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
			  }//2
	   }//1      
//----------------------------------------------------------------------------------------------------------------------------------
       if ($ls_operacion=="PINTAR")
	   {           
          $total=$_POST["totrows"];      
   	      $i=1;
          $li=$total+1;
   
          for($i=1;$i<=$total;$i++)
          {
		      if (array_key_exists("txtcodcar".$i,$_POST))
		      {
                $ls_codcar    =$_POST["txtcodcar".$i];
				$ls_dencar    =$_POST["txtdencar".$i];
				$ld_porcar    =$_POST["txtporcar".$i];    
				  
				$object[$i][1]="<input name=txtcodcar".$i." type=text id=txtcodcar".$i." class=sin-borde  size=5  style=text-align:center  value='".$ls_codcar."'  readonly>";
				$object[$i][2]="<input name=txtdencar".$i." type=text id=txtdencar".$i." class=sin-borde  size=30  style=text-align:center   value='".$ls_dencar."' >";
				$object[$i][3]="<input name=txtporcar".$i." type=text id=txtporcar".$i." class=sin-borde  size=10  style=text-align:center  value='".$ld_porcar."' readonly>";
				$object[$i][4]="<a href=javascript:uf_delete(".$i.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
			 }		  
	         else
	         {	  
				 $object[$i][1]="<input type=text name=txtcodcar".$i." value='' class=sin-borde readonly  style=text-align:center  size=5>";
				 $object[$i][2]="<input type=text name=txtdencar".$i." value='' class=sin-borde readonly  style=text-align:center  size=30>";
				 $object[$i][3]="<input type=text name=txtporcar".$i." value='' class=sin-borde readonly  style=text-align:center  size=10>"; 
				 $object[$i][3]="<input type=text name=txtporcar".$i." value='' class=sin-borde readonly  style=text-align:center  size=10>"; 
				 $object[$i][4]="<a href=javascript:uf_delete(".$i.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=10 border=0></a>";
			 }
          }     
       }
//----------------------------------------------------------------------------------------------------------------------------------
if ($ls_operacion=="ELIMINAR")
   {
	 $lb_existe=$io_servicio->uf_select_servicio($ls_codemp,$ls_codigo);
	 if ($lb_existe)
	    {
		  $ls_condicion = " AND (column_name='codser')";//Nombre del o los campos que deseamos buscar.
	      $ls_mensaje   = "";                              //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
	      $lb_tiene     = $io_chkrel->uf_check_relaciones($ls_codemp,$ls_condicion,"soc_servicios' AND table_name<>'soc_serviciocargo",$ls_codigo,$ls_mensaje);//Verifica los movimientos asociados a la cuenta  
		  if (!$lb_tiene)
		     {
		       $lb_valido=$io_servicio->uf_delete_servicio($ls_codemp,$ls_codigo,$ls_denominacion,$la_seguridad);
			   if ($lb_valido)
				  {
				    $io_sql->commit();
				    $io_msg->message("Registro Eliminado !!!");
				    $ls_denominacion="";
				    $ld_precio="0,00";
				    $ls_cuenta="";
				    $ls_codtipser="";
				    $ls_estatus="NUEVO";
				    $li_lastrow=0;
				    $ls_dentipser='';
				    $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'soc_servicios','codser');
				    for ($i=1;$i<=5;$i++)
					    {			   
						  $object[$i][1]="<input type=text name=txtcodcar".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=5>";
						  $object[$i][2]="<input type=text name=txtdencar".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=30>";
						  $object[$i][3]="<input type=text name=txtporcar".$i."  value=''  class=sin-borde  readonly  style=text-align:center   size=10>";
						  $object[$i][4]="<a href=javascript:uf_delete(".$i.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
					    }
				     $total=5;	 
				  }
			   else
				  {
				    $io_sql->rollback();
				    $io_msg->message($io_servicio->is_msg_error);
				    for ($i=1;$i<=$li_lastrow;$i++)
					    {			   
						  $ls_codcar = $_POST["txtcodcar".$i];
						  $ls_dencar = $_POST["txtdencar".$i];
						  $ld_porcar = $_POST["txtporcar".$i];
						  $object[$i][1]="<input type=text name=txtcodcar".$i."  id=txtcodcar".$i."  value='".$ls_codcar."'  class=sin-borde  readonly  style=text-align:center  size=5>";
						  $object[$i][2]="<input type=text name=txtdencar".$i."  id=txtdencar".$i."  value='".$ls_dencar."'  class=sin-borde  readonly  style=text-align:center  size=30>";
						  $object[$i][3]="<input type=text name=txtporcar".$i."  id=txtporcar".$i."  value='".$ld_porcar."'  class=sin-borde  readonly  style=text-align:center  size=10>";
						  $object[$i][4]="<a href=javascript:uf_delete(".$i.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
					    }
				    $total=$li_lastrow;	 
				  }	
	         }
	      else
		     {
			   $io_msg->message($io_chkrel->is_msg_error);
				for ($i=1;$i<=$li_lastrow;$i++)
					{			   
					  $ls_codcar = $_POST["txtcodcar".$i];
					  $ls_dencar = $_POST["txtdencar".$i];
					  $ld_porcar = $_POST["txtporcar".$i];
					  $object[$i][1]="<input type=text name=txtcodcar".$i."  id=txtcodcar".$i."  value='".$ls_codcar."'  class=sin-borde  readonly  style=text-align:center  size=5>";
					  $object[$i][2]="<input type=text name=txtdencar".$i."  id=txtdencar".$i."  value='".$ls_dencar."'  class=sin-borde  readonly  style=text-align:center  size=30>";
					  $object[$i][3]="<input type=text name=txtporcar".$i."  id=txtporcar".$i."  value='".$ld_porcar."'  class=sin-borde  readonly  style=text-align:center  size=10>";
					  $object[$i][4]="<a href=javascript:uf_delete(".$i.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
					}
				$total=$li_lastrow;	 
			 }
	   }
	 else
	   {
		    $io_msg->message("Este Registro No Existe !!!");
	        for ($i=1;$i<=5;$i++)
	            {			   
	    	        $object[$i][1]="<input type=text name=txtcodcar".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=5>";
					$object[$i][2]="<input type=text name=txtdencar".$i."  value=''  class=sin-borde  readonly  style=text-align:center  size=30>";
					$object[$i][3]="<input type=text name=txtporcar".$i."  value=''  class=sin-borde  readonly  style=text-align:center   size=10>";
					$object[$i][4]="<a href=javascript:uf_delete(".$i.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
	            }
	        $total=5;	 
	   } 
   }
//----------------------------------------------------------------------------------------------------------------------------------
?>
<form name="form1" method="post" action="">
  <p>
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
</p>
  <p>&nbsp;  </p>
  <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
      <tr> 
        <td height="22" colspan="2" class="titulo-ventana">Inicializacion de Contadores </td>
      </tr>
      <tr>
        <td height="22" ><input name="hidmaestro" type="hidden" id="hidmaestro" value="N"></td>
        <td width="366" height="22" ><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>">
          <input name="lastrow"  type="hidden"   id="lastrow" value="<?php print $li_lastrow;?>">
          <input name="hiddensis" type="hidden" id="hiddensis" value="<?php print $ls_densis ?>">
          <input name="hiddenpro" type="hidden" id="hiddenpro" value="<?php print $ls_denpro ?>"></td>
      </tr>
      <tr> 
        <td width="107" height="22" align="right">C&oacute;digo </td>
        <td height="22" ><input name="txtid" type="text" id="txtid" value="<?php print $ls_id ?>" size="10" maxlength="4" style="text-align:center" onBlur="javascript:rellenar_cadena(this.value,4,txtid);"  onKeyPress="return keyRestrict(event,'1234567890');" readonly>
        <input name="operacion" type="hidden" class="formato-blanco" id="operacion"  value="<?php print $ls_operacion ?>"> </td>
      </tr>
      <tr>
        <td height="22"><div align="right"> Sistema </div></td>
        <td height="22"><label>
          <input name="txtcodsis" type="text" id="txtcodsis" style="text-align:center" value="<?php print $ls_codsis ?>" size="10" maxlength="3" readonly>
          <a href="javascript:uf_catalogo_sistemas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
          <input name="txtdensis" type="text" class="sin-borde" id="txtdensis" value="<?php print $ls_densis ?>" size="50">
        </label></td>
      </tr>
      <tr> 
        <td height="22" align="right">Procedencia </td>
        <td height="22"><p>
          <input name="txtprocede" id="txtprocede"  style="text-align:center" value="<?php print $ls_procede ?>" type="text" size="10" maxlength="6"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'.,-');">
          <a href="javascript:uf_catalogo_procede();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
          <input name="txtdenpro" type="text" class="sin-borde" id="txtdenpro" value="<?php print $ls_denpro ?>" size="50">
        </p>        </td>
      </tr>
      <tr>
        <td height="22"><div align="right">Prefijo</div></td>
        <td height="22"><input name="txtprefijo" id="txtprefijo" value="<?php print $ls_prefijo ?>" type="text" size="10" maxlength="6"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'.,-');"  style="text-align:center "></td>
    </tr>
      <tr>
        <td height="22"><div align="right">Numero Inicial </div></td>
        <td height="22"><input name="txtnro_inicial" id="txtnro_inicial" value="<?php print $li_nro_inicial ; ?>" type="text" size="20" maxlength="15"  style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890');" ></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Numero Final </div></td>
        <td height="22"><input name="txtnro_final" id="txtnro_final" value="<?php print $li_nro_final ; ?>" type="text" size="20" maxlength="15"  style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890');" ></td>
      </tr>
      
      <tr>
        <td height="22" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="2"><a href="javascript:catalogo_cargos();"></a>
          <input name="totrows"  type="hidden"   id="totrows" value="<?php print $total;?>">
          <input   name="filaact"  type="hidden"   id="filaact">        </td>
      </tr>
      <tr>
        <td height="22" colspan="2">
          <div align="center"></div>
          <div align="center">
            <?php 
			 $io_grid->makegrid($total,$title,$object,500,'Detalle  de los Contadores',$grid);
		  ?>
          </div>
          <div align="center"></div>
          <div align="center"></div>
        <div align="center"></div></td>
      </tr>
      <tr>
        <td height="22" colspan="2"><div align="right"></div> </td>
      </tr>
  </table>
</form>
</body>
<script language="JavaScript">
function uf_actualizar(fila)
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	f.filaact.value=fila;
	lb_status=f.hidestatus.value;
	lb_valido=false;
	li_total=f.totrows.value;
	tildados=0;
	if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status=="NUEVO")&&(li_incluir==1))
	{
		for(li_i=1;(li_i<=li_total);li_i++)
		{
			lb_valido=eval("f.chk"+li_i+".checked");
			if(lb_valido)
			{
				tildados=tildados+1;
			}
		}
		if(tildados>0)
		{
			if(tildados>1)
			{
				alert("Debe Seleccionar solo un codigo.");
			}
			else
			{
				 f.operacion.value="ACTUALIZAR";
				 f.action="sigesp_cfg_d_inicio_de_contadores.php";
				 f.submit();
			}
		}	
	}
	else
	{
     alert("No tiene permiso para realizar esta operación");
	}		
} 		

function ue_nuevo()
{
f=document.form1;
li_incluir=f.incluir.value;
if (li_incluir==1)
   {	
     f.operacion.value="NUEVO";
	 f.txtid.value="";
	// f.txtdenominacion.value="";
	 //f.txtdenominacion.focus(true);
	 f.action="sigesp_cfg_d_inicio_de_contadores.php";	
	 f.submit(); 
   }
else
   {
     alert("No tiene permiso para realizar esta operación");
   }   
}


function ue_guardar()
{//1
var resul="";					   
f=document.form1;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status=f.hidestatus.value;
if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status=="NUEVO")&&(li_incluir==1))
   {
     with (document.form1)
          {
 	        if (campo_requerido(txtcodsis,"El Código del Sistema debe estar lleno !!")==false)
		       {
		         txtcodsis.focus();
		       }
 	        else
		       { 
		         if (campo_requerido(txtprocede,"El procede debe estar lleno !!")==false)
		            {
		              txtprocede.focus();
		            }
 	            else
		            {
					  if (campo_requerido(txtprefijo,"El Prefijo debe estar llena !!")==false)
						 {
						   txtprefijo.focus();
						 }
					  else
						 {
						   if (campo_requerido(txtnro_inicial,"El Numero Inicial debe estar lleno !!")==false)
							  {
							    txtnro_inicial.focus();
							  }
						   else
							  {  
							    if (campo_requerido(txtnro_final,"El Numero Final debe estar llena !!")==false)
								   {
									 txtnro_final.focus();
								   }
							    else
								   {
					                 f.operacion.value="GUARDAR";
						             f.action="sigesp_cfg_d_inicio_de_contadores.php";
						             f.submit();
		                           }
	                          }
                         }			
                    }
			   }
	     }
	}
  else
    {
      alert("No tiene permiso para realizar esta operación");
	}
}	 					
					
function ue_eliminar()
{
var borrar="";
f=document.form1;
li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
     if (f.txtcodigo.value=="")
        {
	      alert("No ha seleccionado ningún registro para eliminar !!!");
        }
	 else
	    {
		  borrar=confirm("¿ Esta seguro de eliminar este registro ?");
		  if (borrar==true)
		     { 
			   f=document.form1;
			   f.operacion.value="ELIMINAR";
			   f.action="sigesp_cfg_d_inicio_de_contadores.php";
			   f.submit();
		     }
		  else
		     { 
			   alert("Eliminación Cancelada !!!");
		     }
  	    }	   
    }
  else
    {
      alert("No tiene permiso para realizar esta operación");
	}
}	
		
function campo_requerido(field,mensaje)
{
  with (field) 
		{
		if (value==null||value=="")
		   {
			 alert(mensaje);
			 return false;
		   }
		else
		   {
			 return true;
		   }
		}
}
	
function rellenar_cadena(cadena,longitud,txt)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;
	total=longitud-lencad;
	for (i=1;i<=total;i++)
		{
		  cadena_ceros=cadena_ceros+"0";
		}
	cadena=cadena_ceros+cadena;
	document.form1.txt.value=cadena;
}
		
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
	     f.operacion.value="";			
		 pagina="sigesp_cfg_cat_contadores.php";
		 window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
       }
	else
       {
         alert("No tiene permiso para realizar esta operación");
	   }
}

function uf_delete(li_row)
 {     
    var borrar="";
    f=document.form1;
    f=document.form1;
    f.filadel.value=li_row;          
    f.operacion.value="DELETEROW"
    f.action="sigesp_soc_d_servicio.php";
    f.submit();
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
   
function uf_catalogo_sistemas()
{
	f=document.form1;
	f.txtprocede.value="";
	f.txtdenpro.value="";
	pagina="sigesp_cfg_cat_sistemas_contadores.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}
function uf_catalogo_procede()
{
	f=document.form1;
	codsis=f.txtcodsis.value;
	if(codsis=="")
	{
	  alert( " Por Favor Seleccione un Sistema");
	}
	else
	{
		pagina="sigesp_cfg_cat_procede_contadores.php?codsis="+codsis;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
	}	
}
</script>
</html>