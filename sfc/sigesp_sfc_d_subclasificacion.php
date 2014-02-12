<?Php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_conexion.php'";
	 print "</script>";		
   }
$la_datemp=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definici&oacute;n de Subl&iacute;neas</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
.style6 {color: #000000}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<?Php
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	/*require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SOB";
	$ls_ventanas="sigesp_sob_d_organismo.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_permisos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos=             $_POST["permisos"];
			$la_permisos["leer"]=     $_POST["leer"];
			$la_permisos["incluir"]=  $_POST["incluir"];
			$la_permisos["cambiar"]=  $_POST["cambiar"];
			$la_permisos["eliminar"]= $_POST["eliminar"];
			$la_permisos["imprimir"]= $_POST["imprimir"];
			$la_permisos["anular"]=   $_POST["anular"];
			$la_permisos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_permisos["leer"]="";
		$la_permisos["incluir"]="";
		$la_permisos["cambiar"]="";
		$la_permisos["eliminar"]="";
		$la_permisos["imprimir"]="";
		$la_permisos["anular"]="";
		$la_permisos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_permisos);
	}
*/
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("class_folder/sigesp_sfc_c_subclasificacion.php");
	require_once("class_folder/sigesp_sfc_class_utilidades.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	$io_clasificacion = new sigesp_sfc_c_subclasificacion();
	$io_datastore= new class_datastore();
	$io_utilidad = new sigesp_sfc_class_utilidades();
	$is_msg=new class_mensajes();
	
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();		
	$io_sql= new class_sql($io_connect);
	
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codsub=$_POST["txtcodsub"];
		$ls_densub=$_POST["txtdensub"];
		$ls_codcla=$_POST["txtcodcla"];
	}
	else
	{
		$ls_operacion="";
		$ls_codsub="";
		$ls_densub="";
		$ls_codcla=$_GET["codcla"];
     }
	
	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_nuevo")
	{
	    require_once("../shared/class_folder/class_funciones_db.php");
		require_once ("../shared/class_folder/sigesp_include.php");		
		$io_include=new sigesp_include();
		$io_connect=$io_include->uf_conectar();
		$io_funcdb=new class_funciones_db($io_connect);
		$ls_codsub=$io_funcdb->uf_generar_codigo(false,0,"sfc_subclasificacion","codsub",3); /* correlativo incrementa automaticamente */
		$ls_densub="";
		
	}
	
/*******************************************************************************************************************************/
/******************************************   ELIMINAR   ***********************************************************************/
/*******************************************************************************************************************************/
elseif($ls_operacion=="ue_eliminar")
{
	
	/***********************  verificar si cajero generó "COTIZACION"  **********************************************************/
	    
				     
	/****************************************************************************************************************************/
	
		$lb_valido=$io_clasificacion->uf_delete_subclasificacion($ls_codsub);			
		$ls_mensaje=$io_clasificacion->io_msgc;
		if ($lb_valido===true)
		{
		    $is_msg->message ($ls_mensaje);
			$ls_codsub="";
		    $ls_densub="";
		}
	  			
}
	
	
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
/*if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer value='$la_permisos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir value='$la_permisos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar value='$la_permisos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_permisos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_permisos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular value='$la_permisos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_permisos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}*/
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
    <table width="500" height="50" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="500" height="100"><div align="center">
            <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
              <tr>
			 <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></td>
			 </tr>
			  <tr>
                <td colspan="2" class="titulo-ventana">Subl&iacute;nea de Productos </td>
              </tr>            
			 
			  <tr>
                <td >
				  <div align="right">
				    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
				    <input name="hidstatus" type="hidden" id="hidstatus">				
				    <input name="txtcodcla" type="text" id="txtcodcla" value="<?php print $ls_codcla ?>">
		        </div></td>
                <td >&nbsp;</td>
              </tr>
              <tr>
                <td width="134" height="22" align="right"><span class="style2">Codigo </span></td>
                <td width="343" ><input name="txtcodsub" type="text" id="txtcodsub" value="<? print  $ls_codsub?>" size="3" maxlength="3" readonly="true"></td>
              </tr>
              <tr>
                <td width="134" height="22" align="right">Descripcion</td>
                <td width="343" ><input name="txtdensub" type="text" id="txtdensub"  onKeyPress="return(validaCajas(this,'x',event,50))"  value="<? print  $ls_densub?>" size="65" maxlength="65" >                </td>
              </tr>
              <tr>
                <td height="8">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
			   <tr>
         <td height="8">&nbsp;</td>
         <td colspan="2"><div align="right">
<!----------------------  IMAGEN aprobado.gif (ENVIA DATOS A LA PAGINA ANTERIOR) -------------------------------------------------->
<a href="javascript:aceptar(document.form1.txtcodsub.value,document.form1.txtdensub.value);"><img src="../shared/imagebank/tools20/aprobado.gif" width="22" height="22" border="0"></a>

<!---------------------------------  IMAGEN ELIMINAR ------------------------------------------------------------------------>
<a href="javascript:ue_borrar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
       </tr>
            </table>
        </div></td>
      </tr>
    </table>
	<?php
	/************************************************************************************************************************/
/*****************************  CARGA LOS DATOS EN PAGINA ANTERIOR DE FACTURA  ******************************************/		  
/************************************************************************************************************************/
if($ls_operacion=="ENVIAR_DATOS")
{
						
	         ?>
			<script language="JavaScript">
			
			 var ls_codsub = '<?php print $ls_codsub ?>' ;
			 var ls_densub = '<?php print $ls_densub ?>' ;			
			
			 opener.ue_cargarsubclasificacion(ls_codsub,ls_densub);
			 close();
			</script>
		<?php	
			
}
?>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
</body>

<script language="JavaScript">

/***********************************************************************************************************************************/

		function ue_nuevo()
		{
			f=document.form1;
		    f.operacion.value="ue_nuevo";
			f.txtdensub.value="";
			f.action="sigesp_sfc_d_subclasificacion.php";
			f.submit();
			/*li_incluir=f.incluir.value;
			if(li_incluir==1)
			{	
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}*/
		}


		function ue_guardar()
		{
			f=document.form1;
			//li_incluir=f.incluir.value;
			//li_cambiar=f.cambiar.value;
			//lb_status=f.hidstatus.value;
			 with(f)
				 { 
				  if (ue_valida_null(txtcodsub,"Codigo")==false)
				   {
					 txtcodsub.focus();
				   }
				   else
				   { 
					if (ue_valida_null(txtdensub,"Descripcion")==false)
					 {
					  txtdensub.focus();
					 }
					 else
					 {
					    f.operacion.value="ue_guardar";
					    f.action="sigesp_sfc_d_subclasificacion.php";
					    f.submit();
					 }
				   }
				 }
			
			/*if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
			{
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}*/
   	    }					
					
		function ue_eliminar()
		{
		
		
		f=document.form1;
		if (f.txtcodsub.value=="")
			   {
				 alert("No ha seleccionado ningún registro para eliminar !!!");
			   }
				else
				{
				 if (confirm("¿ Esta seguro de eliminar este registro ?"))
					   { 
						 f=document.form1;
						 f.operacion.value="ue_eliminar";
						 f.action="sigesp_sfc_d_subclasificacion.php";
						 f.submit();
					   }
					else
					   { 
						 f=document.form1;
						 f.action="sigesp_sfc_d_subclasificacion.php";
						 alert("Eliminación Cancelada !!!");
						 f.txtcodsub.value="";
						 f.txtdensub.value="";
						
						 f.submit();
					   }
				}
		
		/*li_eliminar=f.eliminar.value;
		if(li_eliminar==1)
		{	
				   
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}*/
		}


       function ue_buscar()
		{
            f=document.form1;
			f.operacion.value="";			
			pagina="sigesp_cat_subclasificacion.php";
			popupWin(pagina,"catalogo",600,250);
			/*li_leer=f.leer.value;
			if(li_leer==1)
			{
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}*/
		}

/***********************************************************************************************************************************/

		function ue_cargarsubclasificacion(codcla,nomcla)
		{
			f=document.form1;
			//f.hidstatus.value="C"
			f.txtcodsub.value=codcla;
            f.txtdensub.value=nomcla;
		}	
		
/***********************************************************************************************************************************/
				
		function EvaluateText(cadena, obj)
		{ 
		opc = false; 
			
			if (cadena == "%d")  
			  if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
			  opc = true; 
			if (cadena == "%f"){ 
			 if (event.keyCode > 47 && event.keyCode < 58) 
			  opc = true; 
			 if (obj.value.search("[.*]") == -1 && obj.value.length != 0) 
			  if (event.keyCode == 46) 
			   opc = true; 
			} 
			 if (cadena == "%s") // toma numero y letras
			 if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)) 
			  opc = true; 
			 if (cadena == "%c") // toma numero y punto
			 if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==46))
			  opc = true; 
			if(opc == false) 
			 event.returnValue = false; 
		   } 		

function ue_borrar()
 {
  f=document.form1;
  /*f.txtcodcli.value="";
  f.txtnumfac.value="";
  f.txtcodcli.value="";
  f.txtnumfac.value="";*/
  f.txtcodsub.value="";
  f.txtdensub.value=""; 
   
 
 }
 
  function ue_enviar_datos()
  {
  
  f=document.form1;
	with(f)
		{
	
			
			if (ue_valida_null(txtcodsub,"Código")==false)
			 {
				txtcodsub.focus();
			 }
			 else if (ue_valida_null(txtdensub,"Denominación")==false)
			 {
			    txtdensub.focus();
				
			 }	 
			else	
			 {	
			operacion.value="ENVIAR_DATOS";
			action="sigesp_sfc_d_clasificacion.php";
			submit();	
			   
			 } 
			
		} 
	
  }
 
	 function aceptar(codcla,nomcla)
  {
    opener.ue_cargarsublineas(codcla,nomcla);
	close();
  }	

</script>
</html>