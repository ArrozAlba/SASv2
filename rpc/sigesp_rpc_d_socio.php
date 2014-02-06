<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Registro de Socios</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-size: 15px}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Proveedores y Beneficiarios</td>
			<td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>

      </table></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a><a href="javascript:uf_close();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>

<?php 
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("class_folder/sigesp_rpc_c_socio.php");
require_once("../shared/class_folder/class_mensajes.php");

$io_conect= new sigesp_include();//Instanciando la Sigesp_Include.
$conn=$io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_sql= new class_sql($conn);//Instanciando la Clase Class Sql.
$io_socio= new sigesp_rpc_c_socio($conn);//Instanciando la Clase Sigesp Definiciones.
$io_dssoc = new class_datastore();//Instanciando la Clase Class  DataStore.
$io_funcion = new class_funciones();//Instanciando la Clase Class_Funciones.
$io_msg=new class_mensajes(); //Instanciando la clase mensajes 

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="RPC";
	$ls_ventanas="sigesp_rpc_d_socio.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos             =$_POST["permisos"];
			$la_accesos["leer"]     =$_POST["leer"];			
			$la_accesos["incluir"]  =$_POST["incluir"];			
			$la_accesos["cambiar"]  =$_POST["cambiar"];
			$la_accesos["eliminar"] =$_POST["eliminar"];
			$la_accesos["imprimir"] =$_POST["imprimir"];
			$la_accesos["anular"]   =$_POST["anular"];
			$la_accesos["ejecutar"] =$_POST["ejecutar"];
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
//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////



$lb_existe="";
$lb_valido="";
$ls_fotowidth="121";
$ls_fotoheight="94";
$ls_foto ="blanco.jpg";

if(array_key_exists("operacion",$_POST))
	{
	$ls_operacion=$_POST["operacion"];
	$ls_codprov=$_POST["txtprov"];
	}
else
	{
	$ls_operacion="";
	$ls_codprov=$_GET["txtprov"];
	}
if(array_key_exists("operacion",$_POST))
	{
	$ls_operacion=$_POST["operacion"];
	$ls_nomprov=$_POST["txtnombre"];
	}
else
	{
	$ls_operacion="";
	$ls_nomprov=$_GET["txtnombre"];
	}
if(array_key_exists("txtcedula",$_POST))
	{
	$ls_cedula=$_POST["txtcedula"];
	}
else
	{
	$ls_cedula="";
	}
if(array_key_exists("txtnombre",$_POST))
	{
	$ls_nombre=$_POST["txtnombre"];
	}
else
	{
	$ls_nombre="";
	}
if(array_key_exists("txtapellido",$_POST))
	{
	$ls_apellido=$_POST["txtapellido"];
	}
else
	{
	$ls_apellido="";
	}	
if(array_key_exists("txtdireccion",$_POST))
	{
	$ls_direccion=$_POST["txtdireccion"];
	}
else
	{
	$ls_direccion="";
	}
if(array_key_exists("txtcargo",$_POST))
	{
	$ls_cargo=$_POST["txtcargo"];
	}
else
	{
	$ls_cargo="";
	}
if(array_key_exists("txttelefono",$_POST))
	{
	$ls_telefono=$_POST["txttelefono"];
	}
else
	{
	$ls_telefono="";
	}
if(array_key_exists("txtemail",$_POST))
	{
	$ls_email=$_POST["txtemail"];
	}
else
	{
	$ls_email="";
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////Operaciones de Insercion y Actualizacion ////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ue_guardar")
   {     
     $arremp=$_SESSION["la_empresa"];
	 $ls_codemp=$arremp["codemp"];
     $lb_valido=true;
	 $lb_valido=$io_socio->uf_select_proveedor($ls_codemp,$ls_codprov);
     if ($lb_valido)
        {
		  $lb_valido=$io_socio->uf_select_socio($ls_codemp,$ls_codprov,$ls_cedula);
		  if ($lb_valido)
        	 {  
			   $lb_valido=$io_socio->uf_update_socio($ls_codemp,$ls_codprov,$ls_cedula,$ls_nombre,$ls_apellido,$ls_direccion,$ls_cargo,$ls_telefono,$ls_email,$la_seguridad);
		     } 
		  else
		     {  
			   $lb_valido=$io_socio->uf_insert_socio($ls_codemp,$ls_codprov,$ls_cedula,$ls_nombre,$ls_apellido,$ls_direccion,$ls_cargo,$ls_telefono,$ls_email,$la_seguridad);
		     }
      }
      else
      {            
            $io_msg->message('El Proveedor No Existe!!!');          
      }
 $ls_cedula="";
 $ls_nombre="";
 $ls_apellido="";
 $ls_direccion="";
 $ls_cargo="";
 $ls_telefono="";
 $ls_email="";	
   } 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////        Fin de las Operaciones de Insercion y Actulizacion  ///////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////    Operacion de Eliminar    ////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ue_eliminar")
   {
      $arremp=$_SESSION["la_empresa"];
	  $ls_codemp=$arremp["codemp"];
	  $lb_valido=$io_socio->uf_delete_socio($ls_codprov,$ls_cedula,$la_seguridad);
      $ls_cedula="";
	  $ls_nombre="";
      $ls_apellido="";
	  $ls_direccion="";
	  $ls_cargo="";
	  $ls_telefono="";
	  $ls_email="";	
   }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////        Fin Operacion de Eliminar        ///////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<p align="center">&nbsp;</p>
<form action="" method="post" enctype="multipart/form-data" name="form1">
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
<table width="410" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr class="titulo-celdanew">
    <td width="209" height="22"><div align="left">&nbsp;&nbsp;Proveedor:<?php print $ls_codprov?></div></td>
    <td width="201" height="22"><div align="left">Nombre:<?php print $ls_nomprov?></div></td>
  </tr>
</table>	
<br>
    <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
      <tr class="titulo-celdanew"> 
        <td height="22" colspan="3">Socio</td>
      </tr>
      <tr>
        <td height="22" >&nbsp;</td>
        <td height="22" colspan="2" >&nbsp;</td>
      </tr>
      <tr>
        <td height="22" >&nbsp;</td>
        <?php 
        if($ls_foto=="")
		 {
		   $ls_foto="blanco.jpg";
	     }
	    ?>
        <td width="136" height="22" ><input name="hidfoto" type="hidden" id="hidfoto"></td>
        <td width="180" height="22" rowspan="3" >&nbsp;</td>
      </tr>
      <tr>
        <td height="22" >&nbsp;</td>
        <td height="22" >&nbsp;</td>
      </tr>
      <tr>
        <td height="22" >&nbsp;</td>
        <td height="22" ><input name="txtprov" type="hidden" id="txtprov" value="<?php print $ls_codprov?>"></td>
      </tr>
      <tr> 
	    <td width="90" height="22" align="right">C&eacute;dula</td>
	    <td height="22" colspan="2" ><input name="txtcedula" type="text" id="txtcedula" value="<?php print  $ls_cedula ?>" size="10" maxlength="10" onKeyPress="return keyRestrict(event,'1234567890');"> 
        <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">          </td>
      </tr>
      <tr> 
        
		<td height="22" align="right">Nombre</td>
        <td height="22" colspan="2"><input name="txtnombre" id="txtnombre" value="<?php print $ls_nombre ?>" type="text" size="50" maxlength="50" onKeyPress="return keyRestrict(event,'abcdefghijklmnñopqrstuvwxyz ');"></td>
      </tr>
      <tr>
        <td height="22" align="right">Apellido</td>
        <td height="22" colspan="2"><input name="txtapellido" type="text" id="txtapellido" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ');" value="<?php print $ls_apellido ?>" size="50" maxlength="50"></td>
      </tr>
      <tr>
        <td height="22" align="right">Direcci&oacute;n</td>
        <td height="22" colspan="2"><input name="txtdireccion" type="text" id="txtdireccion" value="<?php print $ls_direccion ?>" size="50" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz '+'.,-#');"></td>
      </tr>
      <tr>
        <td height="22" align="right">Cargo</td>
        <td height="22" colspan="2"><input name="txtcargo" type="text" id="txtcargo" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz '+'.,-');" value="<?php print $ls_cargo ?>" size="50" maxlength="100"></td>
      </tr>
      <tr>
        <td height="22" align="right">Tel&eacute;fono</td>
        <td height="22" colspan="2"><input name="txttelefono" type="text" id="txttelefono" onKeyPress="return keyRestrict(event,'1234567890'+' ()-');" value="<?php print $ls_telefono?>" size="21" maxlength="20"></td>
      </tr>
      <tr>
        <td height="22" align="right">Email</td>
        <td height="22" colspan="2"><input name="txtemail" type="text" id="txtemail" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz'+'._@');" value="<?php print $ls_email ?>" size="50" maxlength="100"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22" colspan="2">&nbsp; </td>
      </tr>
  </table>
</form>
</body>

<script language="JavaScript">

function uf_ver()
{
   f=document.form1;
   f.operacion.value="VER_FOTO";
   f.action="sigesp_rpc_d_socio.php";
   f.submit();
}

    function ue_nuevo()
    {
		  f=document.form1;
		  li_incluir=f.incluir.value;	
		  if(li_incluir==1)
		  {	  
			  f.txtcedula.value="";
			  f.txtnombre.value="";
			  f.txtapellido.value="";
			  f.txtdireccion.value="";
			  f.txtcargo.value="";
			  f.txttelefono.value="";
			  f.txtemail.value="";
			  f.txtcedula.focus(true);
		  }
		  else
		  {
			  alert("No tiene permiso para realizar esta operacion");
		  }
	}
	
	function ue_guardar()
	{
	var resul="";
	
	with (document.form1)
    {
	 if (campo_requerido(txtcedula,"La cédula del socio debe estar llena !!")==false)
				{
				  txtcedula.focus();
				}
			 else
				{
				  if (campo_requerido(txtnombre,"El nombre del socio debe estar lleno !!")==false)
					 {
					   txtnombre.focus();
					 }
				   else
					 {
					   if (campo_requerido(txtapellido,"El apellido del socio debe estar lleno !!")==false)
						  {
							txtapellido.focus();
						  }
					   else
						  {
							if (campo_requerido(txtdireccion,"La dirección del socio debe estar lleno !!")==false)
							   {
								 txtdireccion.focus();
							   }
							else
							   {
								 if (campo_requerido(txtcargo,"El cargo del socio debe estar lleno !!")==false)
									{
									  txtcargo.focus();
									}          							 
								 else
									{
									  if (campo_requerido(txttelefono,"El teléfono del socio debe estar lleno !!")==false)
										 {
										   txtcargo.focus();
										 }    	 
									  else
										 {
										   if (campo_requerido(txtemail,"El email del socio debe estar lleno !!")==false)
											  {
												txtemail.focus();
											  }                           							 
											else
											  {
												f=document.form1;
												f.operacion.value="ue_guardar";
												f.action="sigesp_rpc_d_socio.php";
												f.submit();
											  }
										 }
									}
								}			
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
		   if(li_eliminar==1)
		   {		
			    if (f.txtcedula.value=="")
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
						  f.action="sigesp_rpc_d_socio.php";
						  f.submit();
					   }
					else
					   { 
						 f=document.form1;
						 f.action="sigesp_rpc_d_socio.php";
						 f.operacion.value="";
						 alert("Eliminación Cancelada !!!");
					   }
				}
		    }
			else
			{
				alert("No tiene permiso para realizar esta operacion");
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
		
		
function uf_close()
{ 
 close();
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
	  document.form1.txtCodigo.value=cadena;
}
		
		
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		f.operacion.value="";
		ls_prov=f.txtprov.value;			
		pagina="sigesp_cat_socio.php?txtprov="+ls_prov;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
</script>
</html>