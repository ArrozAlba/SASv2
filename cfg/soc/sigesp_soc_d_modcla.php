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
<title>Registro de Modalidad de Clausulas</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
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

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración</td>
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
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript:ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php 
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones_db.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/grid_param.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("class_folder/sigesp_soc_c_modcla.php");
require_once("../../shared/class_folder/sigesp_c_check_relaciones.php");

$io_servicioect = new sigesp_include();//Instanciando la Sigesp_Include.
$conn           = $io_servicioect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_sql         = new class_sql($conn);//Instanciando la Clase Class Sql.
$io_modalidad   = new sigesp_soc_c_modcla($conn);//Instanciando la Clase Sigesp Definiciones.
$io_msg         = new class_mensajes();//Instanciando la Clase Class  Mensajes.
$io_funciondb   = new class_funciones_db($conn);
$io_grid        = new grid_param();
$io_ds          = new class_datastore(); //Instanciando la clase datastore
$io_chkrel      = new sigesp_c_check_relaciones($conn);
$lb_existe      = "";

global $object;
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_codemp   = $ls_empresa;
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "CFG";
	$ls_ventanas = "sigesp_soc_d_modcla.php";

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
     $ls_operacion    = $_POST["operacion"];  
     $ls_codigo       = $_POST["txtcodigo"];
     $ls_denominacion = $_POST["txtdenominacion"];
     $li_lastrow      = $_POST["lastrow"];
     $total           = $_POST["totrows"];
     $ls_estatus      = $_POST["hidestatus"];
   }
else
   {
     $ls_operacion    = "NUEVO";
   	 $ls_codigo       = "";
     $ls_denominacion = "";
     $li_lastrow      = 0;
     $total           = 1;
	 $ls_estatus      = "NUEVO";
	 uf_agregarlineablanca($object,1);	  
   }	
$lb_empresa = true;
//Titulos de la tabla de Detalle Bienes
  $title[1]="Código"; $title[2]="Denominación"; $title[3]="Edición"; 
  $grid="grid";	$li_totrows=1;
  
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
		  $object[$li_totrows][1]="<input type=text name=txtcodcla".$li_totrows."  value=''  class=sin-borde  readonly  style=text-align:center  size=5>";
		  $object[$li_totrows][2]="<input type=text name=txtdencla".$li_totrows."  value=''  class=sin-borde  readonly  style=text-align:center  size=30>";
		  $object[$li_totrows][3]="<a href=javascript:uf_delete(".$li_totrows.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";

   }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////             Operación  Nuevo    ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="NUEVO")
   {
	 $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'soc_modalidadclausulas','codtipmod');
	 if(empty($ls_codigo))
	   {
	 	 $io_msg->message($io_funciondb->is_msg_error);
	   }
    $ls_denominacion="";
	$total=1;
	$li_lastrow=0;
	$li_totrow=1;
	uf_agregarlineablanca($object,1);
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////       Fin  Operacion  Nuevo     ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  if ($ls_operacion=="CARGAR")
	 {       
		$li_row=0;
        $ls_codcon=$_POST["txtcodigo"];
        $ls_sql=" SELECT c.codtipmod as codigo,c.codcla as clausula,s.dencla as denominacion ".
                " FROM   soc_dtm_clausulas c, soc_clausulas s                                ".
                " WHERE  c.codemp='".$ls_codemp."' AND c.codtipmod='".$ls_codigo."' AND      ".
                "        c.codemp=s.codemp         AND c.codcla=s.codcla                     "; 
         $rs_data = $io_sql->select($ls_sql);       
         if ($row=$io_sql->fetch_row($rs_data))
	        {
              $data        = $io_sql->obtener_datos($rs_data);
		      $arrcols     = array_keys($data);
		      $totcol      = count($arrcols);
 		      $io_ds->data = $data;
              $totrow      = $io_ds->getRowCount("codigo");
              $total       = $totrow;
              $li_lastrow  = $totrow;
			  $total=$totrow+1; 
              for ($i=1;$i<=$totrow;$i++)	   	   
                  {							                  
				    $ls_codcla   =trim($data["clausula"][$i]);
				    $ls_dencla   =trim($data["denominacion"][$i]);              
				    $li_row=$li_row+1;
			   	    $object[$i][1]="<input name=txtcodcla".$li_row." type=text  id=txtcodcla".$li_row."  class=sin-borde  size=5 style=text-align:center   value='".$ls_codcla."' readonly>";
			        $object[$i][2]="<input name=txtdencla".$li_row." type=text  id=txtdencla".$li_row."  class=sin-borde  size=30 style=text-align:center  value='".$ls_dencla."' readonly>";
		   	        $object[$i][3]="<a href=javascript:uf_delete(".$li_row.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
                  }
				 uf_agregarlineablanca($object,$total);
            }
         else
            {
               uf_agregarlineablanca($object,1);
         }
		
     }   


 if ($ls_operacion=="DELETEROW")
    {             
       $li_total   = $_POST["totrows"];
       $total      = $li_total-1;
       $li_lastrow = $total;
       $li_rowdel  = $_POST["filadel"];
       $li_temp    = 0;                
       $ls_numeli  = $_POST["txtcodcla".$li_rowdel];         
       for ($li_i=1;$li_i<=$li_total;$li_i++)
           {
	         if ($li_i!=$li_rowdel)
	            {		
		          $li_temp   = $li_temp+1;
                  $ls_codcla = $_POST["txtcodcla".$li_i];
	              $ls_dencla = $_POST["txtdencla".$li_i];                  
			      $object[$li_temp][1]="<input name=txtcodcla".$li_temp."  type=text id=txtcodcla".$li_temp."  class=sin-borde  size=5  value='".$ls_codcla."' readonly>";
	              $object[$li_temp][2]="<input name=txtdencla".$li_temp."  type=text id=txtdencla".$li_temp."  class=sin-borde  size=30 value='".$ls_dencla."' readonly>";
	              $object[$li_temp][3]="<a href=javascript:uf_delete(".$li_temp.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
                }
	         else
	            {	
		          $li_rowdelete=0;
	            }
           }
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////        Operaciones de Insercion y Actualizacion         ///////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ue_guardar")
   {     
      //LLENADO DE GRID CARGOS BIENES
      $ld_total  = $_POST["totrows"];                                 
	  $ls_codcla = $_POST["txtcodcla1"];  
	  $li_total=0;
	  if ($ls_codcla!="")
	     {
	       for ($i=1;$i<=$ld_total;$i++)
		       {
		         $ls_codcla = $_POST["txtcodcla".$i];                
				 if ($ls_codcla!="") 
  	                {
					  $li_total=$li_total+1;
					  $lr_grid["clausula"][$i]=$ls_codcla;                          
	                } 	
               }
	     }
	 else
     {
        $lr_grid="";
	 }                  

     $lb_existe=$io_modalidad->uf_select_modalidad($ls_codemp,$ls_codigo);
	 if ($lb_existe)
        {           
	      if ($ls_estatus=="NUEVO")
		     {
			   $io_msg->message("Este Código de Modalidad de Clausula ya existe !!!");  
			   $lb_valido=false;
			 }
		  elseif($ls_estatus=="GRABADO")
		     {
		       $lb_valido=$io_modalidad->uf_update_modalidad($ls_codemp,$ls_codigo,$ls_denominacion,$lr_grid,$li_total,$la_seguridad);
		       if ($lb_valido)
			      {
			        $io_sql->commit();
			        $io_msg->message("Registro Actualizado !!!");
                    $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'soc_modalidadclausulas','codtipmod');
    			    $ls_denominacion="";
					$ls_estatus="NUEVO";
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
		  $lb_valido=$io_modalidad->uf_insert_modalidad($ls_codemp,$ls_codigo,$ls_denominacion,$lr_grid,$li_total,$la_seguridad);
		  if ($lb_valido)
		     {
			   $io_sql->commit();
			   $io_msg->message("Registro Incluido !!!");
			   $ls_denominacion="";
		  	   $ls_estatus="NUEVO";
               $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'soc_modalidadclausulas','codtipmod');
			 }
          else
		     {
			   $io_sql->rollback();
		       $io_msg->message("Error en Inclusión !!!");
			 }
	   }
	$total=1;
	$li_lastrow=0;
	
	uf_agregarlineablanca($object,$total);
   } 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////            Fin de las Operaciones de Insercion y Actualizacion      /////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
			  {
				$ls_codcla =$_POST["txtcodcla".$li_i];
				$ls_dencla =$_POST["txtdencla".$li_i];
				$object[$li_i][1]="<input name=txtcodcla".$li_i." type=text id=txtcodcla".$li_i." class=sin-borde  size=5   style=text-align:center   value='".$ls_codcla."'  readonly>";
				$object[$li_i][2]="<input name=txtdencla".$li_i." type=text id=txtdencla".$li_i." class=sin-borde  size=30  style=text-align:center   value='".$ls_dencla."'>";
				$object[$li_i][3]="<a href=javascript:uf_delete(".$li_i.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
			  }
	   }      

       if ($ls_operacion=="PINTAR")
	   {     
  	       
          $total=$_POST["totrows"];      
   	      $i=1;
          $li=$total+1;
   
          for($i=1;$i<=$total;$i++)
          {
		     if (array_key_exists("txtcodcla".$i,$_POST))
			 {//3
                 $ls_codcla =$_POST["txtcodcla".$i];
				 $ls_dencla =$_POST["txtdencla".$i];
								
				 $object[$i][1]="<input name=txtcodcla".$i." type=text id=txtcodcla".$i." class=sin-borde  size=5   style=text-align:center value='".$ls_codcla."'  readonly>";
				 $object[$i][2]="<input name=txtdencla".$i." type=text id=txtdencla".$i." class=sin-borde  size=30  style=text-align:center value='".$ls_dencla."'>";
				 $object[$i][3]="<a href=javascript:uf_delete(".$i.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			 }
			 else
			 {
                 $object[$i][1]="<input type=text name=txtcodcla".$i." value='' class=sin-borde readonly  style=text-align:center  size=5>";
				 $object[$i][2]="<input type=text name=txtdencla".$i." value='' class=sin-borde readonly  style=text-align:center  size=30>";
				 $object[$i][3]="<a href=javascript:uf_delete(".$i.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=10 border=0></a>";			 
			 }
          }       
       }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////    Operacion de Eliminar   ////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ue_eliminar")
   {
	  $lb_existe=$io_modalidad->uf_select_modalidad($ls_codemp,$ls_codigo);
      if ($lb_existe)
	     {
	       $ls_condicion = " AND (column_name='codtipmod')";//Nombre del o los campos que deseamos buscar.
	       $ls_mensaje   = "";                              //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
	       $lb_tiene     = $io_chkrel->uf_check_relaciones($ls_codemp,$ls_condicion,"soc_modalidadclausulas' AND table_name<>'soc_dtm_clausulas",$ls_codigo,$ls_mensaje);//Verifica los movimientos asociados a la cuenta  
		   if (!$lb_tiene)
		      {
			    $lb_valido=$io_modalidad->uf_delete_modalidad($ls_codemp,$ls_codigo,$la_seguridad);
			    if ($lb_valido)
				   { 
					 $io_sql->commit();
					 $io_msg->message("Registro Eliminado !!!");
					 $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'soc_modalidadclausulas','codtipmod');
					 $ls_denominacion="";
					 $ls_estatus="NUEVO";
				   }
			    else
				   { 
					 $io_sql->rollback();
					 $io_msg->message("Error en Eliminación !!!");
				   }	
		   	  }
		   else
		      {
			    $io_msg->message($io_chkrel->is_msg_error);
			    for ($i=1;$i<=$total;$i++)
                    {
		              if (array_key_exists("txtcodcla".$i,$_POST))
			             {
						   $ls_codcla     = $_POST["txtcodcla".$i];
						   $ls_dencla     = $_POST["txtdencla".$i];
					 	   $object[$i][1] = "<input name=txtcodcla".$i." type=text id=txtcodcla".$i." class=sin-borde  size=5   style=text-align:center value='".$ls_codcla."'  readonly>";
						   $object[$i][2] = "<input name=txtdencla".$i." type=text id=txtdencla".$i." class=sin-borde  size=30  style=text-align:center value='".$ls_dencla."'>";
						   $object[$i][3] = "<a href=javascript:uf_delete(".$i.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";	
						 }
					 }
		      }
		 }	   
	   else
	     {
		    $io_msg->message("Este Registro No Existe !!!");
		 }
$total=1;
$li_lastrow=0;          
 uf_agregarlineablanca($object,$total);	 	  
}

if ($ls_operacion=="AGREGARDETALLE")
   {
		$total=$_POST["totrows"];
		$total=$total+1;
		$ls_codigo =$_POST["txtcodigo"];
		$ls_denominacion =$_POST["txtdenominacion"];
	
		for($li_i=1;$li_i<$total;$li_i++)
		{ 
			$ls_codcla= $_POST["txtcodcla".$li_i];
			$ls_dencla= $_POST["txtdencla".$li_i];
					
		    $object[$li_i][1] = "<input name=txtcodcla".$li_i." type=text id=txtcodcla".$li_i." class=sin-borde  size=5   style=text-align:center value='".$ls_codcla."'  readonly>";
		    $object[$li_i][2] = "<input name=txtdencla".$li_i." type=text id=txtdencla".$li_i." class=sin-borde  size=30  style=text-align:center value='".$ls_dencla."'>";
		    $object[$li_i][3] = "<a href=javascript:uf_delete(".$li_i.");><img src=../../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";	
	
		} 
		uf_agregarlineablanca($object,$total);
  }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////            Fin Operación de Eliminar            ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<form name="form1" method="post" action="">
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
<table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
      <tr> 
        <td height="22" colspan="2" class="titulo-ventana">Modalidad de Clausulas </td>
      </tr>
      <tr>
        <td height="22" >&nbsp;</td>
        <td width="334" height="22" ><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>"></td>
      </tr>
      <tr> 
        <td width="103" height="22" align="right">C&oacute;digo </td>
        <td height="22" ><input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codigo ?>" size="4" maxlength="2" style="text-align:center" onBlur="javascript:rellenar_cad(this.value,2)" onKeyPress="return keyRestrict(event,'1234567890');"> 
        <input name="operacion" type="hidden" class="formato-blanco" id="operacion"  value="<?php print $ls_operacion?>"> </td>
      </tr>
      <tr> 
        <td height="22" align="right">Denominación </td>
        <td height="22"><p>
          <input name="txtdenominacion" id="txtdenominacion" value="<?php print $ls_denominacion ?>" type="text" size="60" maxlength="100"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',.-');">
        </p>        </td>
      </tr>
      <tr>
        <td height="22" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="2"><a href="javascript:uf_cat_clausulas();"><img src="../../shared/imagebank/tools20/nuevo.gif" width="20" height="20" border="0" alt="Registrar Detalles Contables">Agregar Clausulas </a>
          <input name="totrows"  type="hidden" id="totrows"     value="<?php print $total;?>">
          <input name="lastrow"  type="hidden"   id="lastrow"    value="<?php print $li_lastrow;?>"> 
        <input name="filadel"    type="hidden"   id="filadel"></td>
      </tr>
      <tr>
        <td height="22" colspan="2">
          <div align="center"></div>
          <div align="center">
            <?php 
			 $io_grid->makegrid($total,$title,$object,500,'Detalle de Clausulas',$grid);
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
function uf_cat_clausulas()
{
	f=document.form1;
	f.operacion.value="";			
	lastrow=f.lastrow.value;           
	pagina="sigesp_cat_dt_clausulas.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
} 		

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if (li_incluir==1)
	   {	
		 f.operacion.value="NUEVO";
		 f.txtcodigo.value="";
		 f.txtdenominacion.value="";
		 f.txtdenominacion.focus(true);
		 f.action="sigesp_soc_d_modcla.php";
		 f.submit();
	   }
	else
	   {
		 alert("No tiene permiso para realizar esta operación");
	   }
}


function ue_guardar()
{
var resul="";					   
f=document.form1;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status=f.hidestatus.value;
if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status=="NUEVO")&&(li_incluir==1))
   {
     with (document.form1)
          {	
	        if (campo_requerido(txtcodigo,"El Código del Servicio debe estar lleno !!")==false)
	 	       {
		         txtcodigo.focus();
		       } 
 	        else
		       { 
		         if (campo_requerido(txtdenominacion,"La Denominación debe estar llena !!")==false)
			        {
			          txtdenominacion.focus();
			        }
		         else
			        {
				      f=document.form1;
				      f.operacion.value="ue_guardar";
				      f.action="sigesp_soc_d_modcla.php";
				      f.submit();
                    }			
               }
	      }
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
			   f.operacion.value="ue_eliminar";
			   f.action="sigesp_soc_d_modcla.php";
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
		
function rellenar_cad(cadena,longitud)
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
	document.form1.txtcodigo.value=cadena;
}
		
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
	     f.operacion.value="";			
	     pagina="sigesp_soc_cat_modalidad.php";
	     window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
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
	totalfila=f.totrows.value; 
    f.filadel.value=li_row;
	        
		f.operacion.value="DELETEROW"
		f.action="sigesp_soc_d_modcla.php";
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
</script>
</html>