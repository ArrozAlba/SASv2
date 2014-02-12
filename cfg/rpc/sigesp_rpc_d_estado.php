<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Registro de Estados</title>
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
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"><a href="javascript:ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript:ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php 
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("class_folder/sigesp_rpc_c_estado.php");
require_once("../../shared/class_folder/class_funciones_db.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/sigesp_c_check_relaciones.php");

$io_conect    = new sigesp_include();//Instanciando la Sigesp_Include.
$conn         = $io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_sql       = new class_sql($conn);//Instanciando la Clase Class Sql.
$io_estado    = new sigesp_rpc_c_estado($conn);//Instanciando la Clase Sigesp Definiciones.
$io_dsest     = new class_datastore();//Instanciando la Clase Class  DataStore.
$io_funcion   = new class_funciones();//Instanciando la Clase Class_Funciones.
$io_funciondb = new class_funciones_db($conn);
$io_msg       = new class_mensajes();
$io_chkrel    = new sigesp_c_check_relaciones($conn);
$lb_existe    = "";

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "CFG";
	$ls_ventanas = "sigesp_rpc_d_estado.php";

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
     $ls_operacion     = $_POST["operacion"];
     $ls_codigo        = $_POST["txtcodigo"];
     $ls_desest        = $_POST["txtdenominacion"];
     $ls_codpais       = $_POST["cmbpais"];
	 $lr_datos["pais"] = $ls_codpais;
     $ls_estatus       = $_POST["hidestatus"];
   }
else
   {
     $ls_operacion     = "";
     $ls_codigo        = "";
     $ls_desest        = "";
	 $ls_codpais       = "---";
	 $lr_datos["pais"] = $ls_codpais;
	 $ls_estatus       = "NUEVO";	  
   }	
$lb_empresa=false;
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////             Operación  Nuevo    ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="NUEVO") 
   {
	 $ls_codigo=$io_estado->uf_generar_codigo($lb_empresa,$ls_empresa,'sigesp_estados','codest',$ls_codpais);
	 if(empty($ls_codigo))
	   {
	 	$io_msg->message($io_funciondb->is_msg_error);
	   }
   }  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////       Fin  Operacion  Nuevo     ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////      Operaciones de Insercion y Actualizacion      /////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ue_guardar")
   { 
	 $lb_existe=$io_estado->uf_load_estado($ls_codpais,$ls_codigo);
	 if ($lb_existe)
        { 
	      if ($ls_estatus=="NUEVO")
		     {
			   $io_msg->message("Este Código de Estado ya existe !!!");  
			   $lb_valido=false;
			 }
		  elseif($ls_estatus=="GRABADO")
		     {
	           $lb_valido=$io_estado->uf_update_estado($ls_codpais,$ls_codigo,$ls_desest,$la_seguridad);
	           if ($lb_valido)
		          {
	    	        $io_sql->commit();
			        $io_msg->message("Registro Actualizado !!!");
			        $ls_codpais="";
	                $ls_codigo="";
	                $ls_desest="";
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
		  $lb_valido=$io_estado->uf_insert_estado($ls_codpais,$ls_codigo,$ls_desest,$la_seguridad);
	      if ($lb_valido)
			 {
			   $io_sql->commit();
			   $io_msg->message("Registro Incluido !!!");
			   $ls_codpais="";
	           $ls_codigo="";
	           $ls_desest="";
			 }
		   else
			  {
				$io_sql->rollback();
				$io_msg->message("Error en Inclusión !!!");
			  }
		} 
   } 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////         Fin de las Operaciones de Insercion y Actualizacion       ///////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////    Operaciones de Eliminar ////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ue_eliminar")
   {
	  $lb_existe = $io_estado->uf_load_estado($ls_codpais,$ls_codigo);
      if ($lb_existe)
	     {
	       $ls_condicion = " AND (column_name='codest')";//Nombre del o los campos que deseamos buscar.
	       $ls_mensaje   = "";  //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
	       $lb_tiene     = $io_chkrel->uf_check_relaciones($ls_empresa,$ls_condicion,'sigesp_estados',$ls_codigo,$ls_mensaje);//Verifica los movimientos asociados a la cuenta
           if (!$lb_tiene)
		      {
			    $lb_valido=$io_estado->uf_delete_estado($ls_empresa,$ls_codpais,$ls_codigo,$la_seguridad,$ls_desest);
			    if ($lb_valido)
				   {
					 $io_sql->commit();
					 $io_msg->message("Registro Eliminado !!!");
					 $ls_codpais="";
					 $ls_codigo="";
					 $ls_desest="";
				   }
			    else
				   {
					 $io_sql->rollback();
					 $io_msg->message($io_estado->is_msg_error);
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
		 }
   } 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////      Operacion de Eliminar     ////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<p>&nbsp;</p>
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
    <table width="521" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="519" height="192"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td height="22" colspan="2" class="titulo-ventana">Registro Estados</td>
            </tr>
            <tr>
              <td height="22" >&nbsp;</td>
              <td height="22" ><input name="hidpais" type="hidden" id="hidpais" value="<?php print $ls_codpais ?>">
                <span class="style1">
                <input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>">
              </span></td>
            </tr>
            <tr>
              <td height="22" align="right">Pais</td>
              <td height="22">
                <?php
               //Llenar Combo Pais
                $rs_est=$io_estado->uf_llenarcombo_pais();
              ?>
                <select name="cmbpais" id="cmbpais" onChange="javascript:uf_cambiopais();" style="width:120px">
                  <option value="---">---seleccione---</option>
                  <?php
 		        while($row=$io_sql->fetch_row($rs_est))
		        {
		         $ls_pais=$row["codpai"];
		         $ls_denpai=$row["despai"];
		         if ($ls_pais==$ls_codpais)
			     {
				  print "<option value='$ls_pais' selected>$ls_denpai</option>";
			     }
		         else
			     {
				  print "<option value='$ls_pais'>$ls_denpai</option>";
			     }
		       } 
	        ?>
                </select></td>
            </tr>
            <tr>
              <td width="134" height="22" align="right"><span class="style2">C&oacute;digo</span></td>
              <td width="334" height="22" ><input name="txtcodigo" type="text" id="txtcodigo" value="<?php print  $ls_codigo?>" size="3" maxlength="3" onKeyPress="return keyRestrict(event,'1234567890');" onBlur="javascript:rellenar_cad(this.value,3)" style="text-align:center ">
              <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">              </td>
            </tr>
            <tr>
              <td height="22" align="right"><span class="style2">Denominaci&oacute;n</span></td>
              <td height="22"><input name="txtdenominacion" id="txtdenominacion" value="<?php print $ls_desest ?>" type="text" size="60" maxlength="60" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',.-');"></td>
            </tr>
            <tr>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp; </td>
            </tr>
          </table>
        </div></td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
<script language="JavaScript">
function ue_nuevo()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
       f.operacion.value="ue_nuevo";
	   f.cmbpais[0].selected=true;
	   f.txtcodigo.value="";
	   f.txtdenominacion.value="";
	   f.txtdenominacion.focus(true);
	   f.action="sigesp_rpc_d_estado.php";
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
ls_codpais=f.cmbpais.value;
if (ls_codpais!='---')
   {
if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status=="NUEVO")&&(li_incluir==1))
   {
     with (document.form1)
	      {
	        if (campo_requerido(txtcodigo,"El Código del Estado debe estar lleno !!")==false)
			   {
			     txtcodigo.focus();
			   }
			else
			   { 
			     resul=rellenar_cad(document.form1.txtcodigo.value,3);
				 if (campo_requerido(txtdenominacion,"La Denominación del Estado debe estar llena !!")==false)
					{
					  txtdenominacion.focus();
					}
				 else
					{
				      f=document.form1;
					  f.operacion.value="ue_guardar";
					  f.action="sigesp_rpc_d_estado.php";
					  f.submit();
				    }
			  }
		  }			
   }
 else
   {
     alert("No tiene permiso para realizar esta operación");
   }
  }
 else
  {
    alert('Debe seleccionar un País válido !!!');
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
			   f.action="sigesp_rpc_d_estado.php";
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
	     if (f.cmbpais.value=="s1")
	        {
		      alert("Debe seleccionar un Pais para ver el Catálogo de Estados");
	        }
	     else
	        {    
		      f.operacion.value="";
		      ls_pais=f.hidpais.value;			
		      pagina="sigesp_cfg_cat_estados.php?hidpais="+ls_pais;
		      window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=500,resizable=yes,location=no");
	        }
       }
	else
	   {
	     alert("No tiene permiso para realizar esta operación");
	   }  
}
		
function uf_cambiopais()
{
	f=document.form1;
	if (f.cmbpais.value=="s1")
	   {
		  f.operacion.value="";
		  f.txtcodigo.value="";
		  f.txtdenominacion.value=""; 
	   }
	else
	   {   
		  f.operacion.value="NUEVO";
	   }  
	f.action="sigesp_rpc_d_estado.php";
	f.submit();
}
</script>
</html>