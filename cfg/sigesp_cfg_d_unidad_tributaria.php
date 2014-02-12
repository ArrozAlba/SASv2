<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='sigesp_conexion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
<html>
<head>
<title>Definición de Unidad Tributaria</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
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
<style type="text/css">
<!--
.Estilo1 {
	font-size: 14;
	color: #6699CC;
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
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a>
	<a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a>
	<a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a>
	<a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20" border="0"></a>
	<a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a>
	<a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?php
require_once("class_folder/sigesp_cfg_c_unidad_tributaria.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_c_check_relaciones.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/class_fecha.php");

$io_unidad_tributaria = new sigesp_cfg_c_unidad_tributaria();
$io_conect       = new sigesp_include();
$con             = $io_conect-> uf_conectar ();
$la_emp          = $_SESSION["la_empresa"];
$io_msg          = new class_mensajes(); //Instanciando la clase mensajes 
$io_sql          = new class_sql($con); //Instanciando  la clase sql
$io_dsest        = new class_datastore(); //Instanciando la clase datastore
$lb_valido       = "";
$io_chkrel       = new sigesp_c_check_relaciones($con);
$fun_db          = new class_funciones_db($con);
$io_fec          = new class_fecha ();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "CFG";
	$ls_ventanas = "sigesp_cfg_d_unidad_tributaria.php";

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
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
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////


if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion   = $_POST["operacion"];
	 $ls_codigo      = $_POST["txtcodigo"];
	 $ls_anno        = $_POST["txtanno"];
	 $ld_fecentvig   = $_POST["txtfecentvig"];
	 $ls_gacofi      = $_POST["txtgacofi"];
     $ld_fecpubgac   = $_POST["txtfecpubgac"];
	 $ls_decnro      = $_POST["txtdecnro"];
	 $ld_fecdec      = $_POST["txtfecdec"];
	 $ls_valunitri   = $_POST["txtvalunitri"]; 
	 $ls_estatus     = $_POST["status"];
   }
else
   {
	 $ls_operacion = "";
	 $ls_codigo    = $io_unidad_tributaria->retonar_ultimo();
	 $ls_anno      = "";
     $ld_fecentvig = "";
	 $ls_gacofi    = "";
	 $ld_fecpubgac = "";
	 $ls_decnro    = "";
     $ld_fecdec    = "";
	 $ls_valunitri = "";
	 $ls_estatus   = "N";
   }
   if($ls_operacion == "NUEVO")
	{ 
	    $ls_codigo    = $io_unidad_tributaria->retonar_ultimo();
		$ls_operacion = "";
	    $ls_anno      = "";
        $ld_fecentvig = "";
	    $ls_gacofi    = "";
	    $ld_fecpubgac = "";
	    $ls_decnro    = "";
        $ld_fecdec    = "";
	    $ls_valunitri = "";
	    $ls_estatus   = "N";
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 if ($ls_operacion=="GUARDAR")
	{ 
      
	  $lb_valido = $io_unidad_tributaria->uf_guardar_unidad_tributaria($ls_codigo,$ls_anno,$ld_fecentvig,$ls_gacofi,$ld_fecpubgac,
	                                                                   $ls_decnro,$ld_fecdec,$ls_valunitri,$la_seguridad);
     $ls_codigo    = $io_unidad_tributaria->retonar_ultimo();
	 $ls_anno      = "";
     $ld_fecentvig = "";
	 $ls_gacofi    = "";
	 $ld_fecpubgac = "";
	 $ls_decnro    = "";
     $ld_fecdec    = "";
	 $ls_valunitri = "";
    }
	   
if ($ls_operacion=="ELIMINAR")
   {
	 /* $ls_ultimo= "select max(codunitri) from sigesp_unidad_tributaria ";
	  if ($ls_codigo!=$ls_ultimo)
	  {
	   $io_msg->message("Registro no se puede eliminar !!!");
	  }
	  else 
	  {*/
	  
	  $lb_existe = $io_unidad_tributaria->uf_select_unidad_tributaria($ls_codigo);
	  if ($lb_existe)
	     { 
		   $ls_condicion = " AND (column_name='codunitri' OR column_name='codigo_doc')";//Nombre del o los campos que deseamos buscar.
	       $ls_mensaje   = "";  //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
	       $lb_tiene     = $io_chkrel->uf_check_relaciones($ls_empresa,$ls_condicion,'sigesp_unidad_tributaria',$ls_codigo,$ls_mensaje);
	       if (!$lb_tiene)
	          {
		        $lb_valido = $io_unidad_tributaria->uf_delete_unidad_tributaria($ls_codigo,$la_seguridad);
		        if ($lb_valido)
	               {
			         $io_sql->commit();
			         $io_msg->message("Registro Eliminado !!!");
		             $ls_codigo    = $io_unidad_tributaria->retonar_ultimo();
	                 $ls_anno      = "";
                     $ld_fecentvig = "";
	                 $ls_gacofi    = "";
	                 $ld_fecpubgac = "";
	                 $ls_decnro    = "";
                     $ld_fecdec    = "";
	                 $ls_valunitri = "";
			       }
		        else
			       { 
			         $io_sql->rollback();
			        // $io_msg->message($io_unidad_tributaria->io_msg_error);
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
	//}
}
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
<table width="611" height="280" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="616" height="221"><table width="564"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="22" colspan="2" class="titulo-ventana">Unidad Tributaria</td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td height="22"><input name="status" type="hidden" id="status" value="<?php print $ls_estatus ?>"></td>
       <tr>
          <td width="176" height="22" align="right">C&oacute;digo:</td>
          <td width="386" height="22"><input name="txtcodigo" type="text" id="txtcodigo" size="8" maxlength="4" onKeyUp="" onBlur="javascript:rellenar_cadena(this.value,4);" onKeyPress="" value="<?php print $ls_codigo ?>"  style="text-align:center ">
            <input name="operacion" type="hidden" id="operacion"></td>
        <tr>
        <tr>
          <td width="176" height="22" align="right">A&ntilde;o:</td>
          <td width="386" height="22"><input name="txtanno" type="text" id="txtanno" size="8" maxlength="4" onKeyPress="return keyRestrict(event,'0123456789');" value="<?php print $ls_anno ?>"  style="text-align:center ">
            <input name="txtcodsis" type="hidden" id="txtcodsis"  value="<?php print $ls_codsis ?>"  style="text-align:center "></td>
        <tr>
          <td height="22" align="right">Fecha Entrada en Vigencia: </td>
          <td height="22" colspan="2">
		  <input name="txtfecentvig" type="text" id="txtfecentvig"  style="text-align:center" value="<?php print $ld_fecentvig;?>" size="20" maxlength="10"  onKeyPress="currencyDate(this);return keyRestrict(event,'1234567890');"  datepicker="true"></td>
        </tr>
        <tr>
          <td height="22" align="right">Gaceta oficial:</td>
          <td height="22" colspan="2"><input name="txtgacofi" type="text" id="txtgacofi" size="10" maxlength="10" onKeyPress="return keyRestrict(event,'1234567890'+'.,-');" value="<?php print $ls_gacofi ?>"  style="text-align:center "></td>
        </tr>
        <tr>
          <td height="22" align="right">Fecha de Publicaci&oacute;n: </td>
          <td height="22" colspan="2"><input name="txtfecpubgac" type="text" id="txtfecpubgac"  style="text-align:center" value="<?php print $ld_fecpubgac;?>" size="20" maxlength="10"  onKeyPress="currencyDate(this);return keyRestrict(event,'1234567890');"  datepicker="true" ></td>
        </tr>
        <tr>
          <td height="22" align="right">Decreto N&ordm;/ Providencia : </td>
          <td height="22" colspan="2"><input name="txtdecnro" type="text" id="txtdecnro" style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890'+'.,-');" value="<?php print $ls_decnro ?>" size="10" maxlength="10"></td>
        </tr>
        <tr>
          <td height="22" align="right">Fecha Decreto: </td>
          <td height="22" colspan="2"><input name="txtfecdec" type="text" id="txtfecdec"  style="text-align:center" value="<?php print $ld_fecdec;?>" size="20" maxlength="10"  onKeyPress="currencyDate(this);return keyRestrict(event,'1234567890');"  datepicker="true">
            <a href="javascript:catalogo_sistemas();"></a> </td>
        </tr>
        <tr>
          <td height="22" align="right">Valor de la U.T. : </td>
          <td height="22" colspan="2"><input name="txtvalunitri" type="text" id="txtvalunitri" value="<?php print number_format($ls_valunitri,3,",",".");?>" style="text-align:right" size="15" maxlength="10" onKeyPress="return(currencyFormat(this,'.',',',event))">
           </p></td>
        </tr>            
        <tr>
          <td height="25" align="right">&nbsp;</td>
          <td height="25" colspan="2">&nbsp;</td>
        </tr>
		</table></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  
</form><br>
<br>
</body>

<script language="javascript">

function ue_nuevo()
{ 
  f=document.form1;
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
	   /* f.txtcodigo.readOnly=false;
	   f.txtcodigo.value="";
	   f.txtanno.value="";
	   f.txtfecentvig.value="";
	   f.txtgacofi.value="";
	   f.txtfecpubgac.value="";
	   f.txtdecnro.value="";
	   f.txtfecdec.value="";
	   f.txtvalunitri.value="";*/
	   f.operacion.value="NUEVO";
	   //f.txtcodigo.focus(); 
	   f.action="sigesp_cfg_d_unidad_tributaria.php";
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
 
  f          = document.form1;
  li_incluir = f.incluir.value;
  li_cambiar = f.cambiar.value;
  lb_status  = f.status.value; 
  if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status!="GRABADO")&&(li_incluir==1))
    {
  	 with (document.form1)
	      {
            if (valida_null(txtcodigo,"El Código de la Unidad esta vacio !!!")==false)
               {
                 f.txtcodigo.focus();
               }
            else
               {
	             if (valida_null(txtanno,"El año de la Unidad esta vacio  !!!")==false)
	                {
	                  f.txtanno.focus();
	                }
					
				 else
				  {  
				    var fechaactual = new Date();
					var annoactual = fechaactual.getFullYear();    
					var txt =parseInt(f.txtanno.value);						           
				    if (f.txtanno.value<'1990' || txt>annoactual)
				   	 {
					  alert ("El año es incorrecto");
					  f.txtanno.focus();
					 }
										 
				  else  
	                {
                      if (valida_null(txtfecentvig,"La fecha de entrada en vigencia esta vacia !!!")==false)
  		                 {
 	                       f.txtfecentvig.focus();
	    		         }
		              else
		                 {
		                   if (valida_null(txtgacofi,"La Descripción de la Gaceta Oficial esta vacia !!!")==false)
  		                      {
 	                            f.txtgacofi.focus();
	    		              }
						 else
		                 {
		                   if (valida_null(txtfecpubgac,"La Fecha de publicación de la Gaceta Oficial esta vacia !!!")==false)
  		                      {
 	                            f.txtfecpubgac.focus();
	    		              }
						 else
		                 {
		                   if (valida_null(txtdecnro,"El numero de Decreto esta vacio !!!")==false)
  		                      {
 	                            f.txtdecnro.focus();
	    		              }
						 else
		                 {
		                   if (valida_null(txtfecdec,"La Fecha del Decreto esta vacia !!!")==false)
  		                      {
 	                            f.txtfecdec.focus();
	    		              }	
						 else
		                 {
		                   if (valida_null(txtvalunitri,"El valor de la unidad tributaria esta vacio !!!")==false)
  		                      {
 	                            f.txtvalunitri.focus();
	    		              }	    	  	  
				           else
				              {
					            f=document.form1;
					            f.operacion.value="GUARDAR";
					            f.action="sigesp_cfg_d_unidad_tributaria.php";
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
      }
   }
  else
	{
	  alert("No tiene permiso para realizar esta operación");
	}
}

function valida_null(field,mensaje)
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
			   f.operacion.value="ELIMINAR";
			   f.action="sigesp_cfg_d_unidad_tributaria.php";
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

function ue_buscar()
{
	f=document.form1;
    li_leer=f.leer.value;
	if (li_leer==1)
	   {
	     f.operacion.value="";			
	     pagina="sigesp_cfg_cat_unidad_tributaria.php";
	     window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
       }
	else
	   {
	     alert("No tiene permiso para realizar esta operación");
	   }   
}

function ue_print()
{
   	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
		{	
		  f.operacion.value="";
		  pagina="reportes/sigesp_cfg_rpp_listado_unidades_tributarias.php";
		  window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");	
		 } //fin del if
	 else		
		{
		 alert("No tiene permiso para realizar esta operación");
		 }
} // fin de la funcion print


function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

function rellenar_cadena(cadena,longitud)
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
	///alert (len);
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
	if (len == 3) fld.value = '0'+ decSep + aux; 
    if (len > 3) 
	{ 
     aux2 = ''; 
     for (j = 0, i = len - 4; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length;
	 ///alert (len2); 
     for (i = len2 - 1; i >= 0; i--) 
      fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 3, len); 
    } 
    return false; 
   } 
  
</script>
</html>
