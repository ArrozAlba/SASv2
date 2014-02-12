<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
<html>
<head>
<title>Documentos Por Proveedor</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
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
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript:uf_close();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<?php
require_once("class_folder/sigesp_rpc_c_proxdoc.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");

$io_proxdoc=new sigesp_rpc_c_proxdoc();
$io_conect = new sigesp_include();
$con= $io_conect-> uf_conectar ();
$la_emp=$_SESSION["la_empresa"];
$io_msg=new class_mensajes(); //Instanciando la clase mensajes 
$io_sql=new class_sql($con); //Instanciando  la clase sql
$io_dsproxdoc=new class_datastore(); //Instanciando la clase datastore
$io_funcion=new class_funciones(); //Instanciando la clase datastore

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="RPC";
	$ls_ventanas="sigesp_rpc_w_proxdoc.php";

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
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
if (array_key_exists("txtcodigo",$_POST))
   {
	 $ls_coddoc=$_POST["txtcodigo"];
	 $lr_datos["codigo"]=$ls_coddoc;
   }
else
   {
     $ls_coddoc="";
   }    
if (array_key_exists("txtdenominacion",$_POST))
   {
	 $ls_denominacion =$_POST["txtdenominacion"];
   }
else
   {
     $ls_denominacion="";
   }
if (array_key_exists("txtfecrec",$_POST))
   {
	 $ls_fecrec =$_POST["txtfecrec"];
	 $ls_fecrecibimiento=$io_funcion->uf_convertirdatetobd($ls_fecrec);
	 $lr_datos["fecrec"]=$ls_fecrecibimiento;
   }
else
  {
    $ls_fecrec=""; 
  }
if (array_key_exists("txtfecven",$_POST))
   {
	 $ls_fecven=$_POST["txtfecven"];
	 $ls_fecvencimiento=$io_funcion->uf_convertirdatetobd($ls_fecven);
	 $lr_datos["fecven"]=$ls_fecvencimiento; 
   }
else
  {
    $ls_fecven=""; 
  }
if (array_key_exists("cmbestdoc",$_POST))
   {
     $ls_estdoc=$_POST["cmbestdoc"];
     $lr_datos["documento"]=$ls_estdoc;
  }
else
  {
    $ls_estdoc=""; 
  }
if (array_key_exists("cmbestori",$_POST))
   {
    $ls_estori=$_POST["cmbestori"];
    $lr_datos["original"]=$ls_estori;
  }
else
  {
    $ls_estori=""; 
  }
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion=$_POST["operacion"];     
   }
else
   {
	 $ls_operacion="";     
   }

   if ($ls_operacion=="")
	 {
         $array_fecha=getdate();
 	     $ls_dia=$array_fecha["mday"];
	     $ls_mes=$array_fecha["mon"];
	     $ls_ano=$array_fecha["year"];		
	     $ls_fecha=$io_funcion->uf_cerosizquierda($ls_dia,2)."/".$io_funcion->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
  	     $ls_fecrec=$ls_fecha;
         $ls_fecven=$ls_fecha;
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

    $ls_codemp=$la_emp["codemp"];
	if ($ls_operacion=="GUARDAR")
	   { 
	     if ($ls_codprov=="")
		   {
		     $ls_operacion="";
			 $io_msg->message("Debe seleccionar un Proveedor Valido!!!");
		     $ls_coddoc="";
			 $ls_denominacion="";
			 $ls_fecrec="";
			 $ls_fecven="";
			 $ls_estdoc=""; 
			 $ls_estori="";
		   }
		 else
		   {
             $lb_existe=$io_proxdoc->uf_existe_proveedor($ls_codemp,$ls_codprov);
             if($lb_existe)
             {
  				 $io_proxdoc->ue_guardar($ls_codemp,$ls_codprov,$lr_datos,$la_seguridad);
				 $ls_coddoc="";
				 $ls_denominacion="";
				 $ls_fecrec="";
				 $ls_fecven="";
				 $ls_estdoc=""; 
				 $ls_estori="";
			}
            else
            {            
                $io_msg->message('El Proveedor No Existe!!!');          
            }
	     }  
	  }

	if ($ls_operacion=="ELIMINAR")
	   {
         $li_return=$io_proxdoc->ue_eliminar($ls_codemp,$ls_coddoc,$ls_codprov,$la_seguridad);
         $ls_coddoc="";
		 $ls_denominacion="";
		 $ls_fecrec="";
		 $ls_fecven="";
		 $ls_estdoc=""; 
		 $ls_estori="";
	   }
?> 
<table width="518" border="0" align="center" cellpadding="0" cellspacing="0">
<tr class="titulo-celdanew"> 
	<td height="22" colspan="2" style="text-align:left ">&nbsp;&nbsp;Proveedor:<?php print $ls_codprov?></td>
	<td width="289" height="22"  style="text-align:left ">Nombre: <?php print $ls_nomprov?></td>
  </tr>
</table>
<br>
  <table width="518" border="0" align="center" class="formato-blanco" cellpadding="0" cellspacing="0">
    <tr>
      <td height="22" colspan="3" align="center" class="titulo-celdanew">Documentos del Proveedor</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22"><input name="txtprov" type="hidden" id="txtprov" value="<?php print $ls_codprov?>">
      <input name="txtnombre"   type="hidden" id="txtnombre" value="<?php print $ls_nomprov ?>"></td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td width="163" height="22" align="right">Codigo</td>
      <td width="171" height="22"><input name="txtcodigo" type="text"  id="txtcodigo" value="<?php print $ls_coddoc ?>" size="6" maxlength="3" readonly style="text-align:center ">
        <a href="javascript:catalogo_documentos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>        <input name="operacion" type="hidden" id="operacion"></td>
      <td width="182" height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" align="right">Denominaci&oacute;n</td>
      <td height="22" colspan="2"><input name="txtdenominacion" type="text" id="txtdenominacion" value="<?php print $ls_denominacion ?>" size="55" maxlength="254" readonly></td>
    </tr>
    <tr>
      <td height="22" align="right">Fecha de Recepci&oacute;n</td>
      <td height="22"><input name="txtfecrec" type="text" id="txtfecrec" onBlur="valFecha(document.form1.txtfecrec)" value="<?php print $ls_fecrec ?>" size="15" maxlength="10" datepicker="true" onKeyPress="currencyDate(this);"></td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" align="right">Fecha de Vencimiento</td>
      <td height="22"><input name="txtfecven" type="text" id="txtfecven" onBlur="valFecha(document.form1.txtfecven)" value="<?php print $ls_fecven ?>" size="15" maxlength="10" datepicker="true" onKeyPress="currencyDate(this);"></td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" align="right">Estatus del Documento</td>
      <td height="22"><select name="cmbestdoc" id="select">
        <option value="0" selected>No Entregado</option>
        <option value="1">Entregado</option>
        <option value="2">En Tramite</option>
      <option value="3">No Aplica al Proveedor</option></select>        </td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" align="right">Estatus de Originalidad</td>
      <td height="22"><select name="cmbestori" id="select">
        <option value="0" selected>Copia del Documento</option>
      <option value="1">Original</option></select>      </td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><span class="style1"></span></td>
      <td height="22"><span class="style1"></span></td>
      <td height="22"><span class="style1"></span></td>
    </tr>
  </table>
</form>
</body>
<script language="javascript">

function ue_nuevo()
{
  f=document.form1;
  f.txtcodigo.value="";
  f.txtdenominacion.value="";
  f.txtfecrec.value="";
  f.txtfecven.value="";
  f.cmbestdoc[0].selected=true;
  f.cmbestori[0].selected=true;
  f.operacion.value="ue_nuevo";
  f.action="sigesp_rpc_w_proxdoc.php";
  f.submit();
}

function ue_guardar()
{
  var resul="";
  with (document.form1)
  {
   if (valida_null(txtcodigo,"El Código esta vacio !!!")==false)
   {
    txtcodigo.focus();
   }
   else
   {
    if (valida_null(txtfecrec,"La Fecha de Recepción esta vacia !!!")==false)
    {
      txtfecrec.focus();
    }
    else
    {
     if (valida_null(txtfecven,"La Fecha de Vencimiento esta vacia !!!")==false)
     {
        txtfecven.focus();
     }
     else
     {
        f=document.form1;
		f.operacion.value="GUARDAR";
	   	f.action="sigesp_rpc_w_proxdoc.php";
		f.submit();
	 }
	}
   }
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

if (f.txtcodigo.value=="")
   {
	 alert("No ha seleccionado ningún registro para eliminar !!!");
     f.txtcodigo.focus=true;
   }
	else
	{
		borrar=confirm("¿ Esta seguro de eliminar este registro ?");
		if (borrar==true)
		   { 
			  f.operacion.value="ELIMINAR";
			  f.action="sigesp_rpc_w_proxdoc.php";
			  f.submit();
		   }
		else
		   { 
			 alert("Eliminación Cancelada !!!");
		   }
	}
}

function valSep(oTxt)
{ 
    var bOk = false; 
    var sep1 = oTxt.value.charAt(2); 
    var sep2 = oTxt.value.charAt(5); 
    bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
    bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
    return bOk; 
} 

function finMes(oTxt)
{ 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    var nAno = parseInt(oTxt.value.substr(6), 10); 
    var nRes = 0; 
    switch (nMes)
    { 
     case 1: nRes = 31; break; 
     case 2: nRes = 28; break; 
     case 3: nRes = 31; break; 
     case 4: nRes = 30; break; 
     case 5: nRes = 31; break; 
     case 6: nRes = 30; break; 
     case 7: nRes = 31; break; 
     case 8: nRes = 31; break; 
     case 9: nRes = 30; break; 
     case 10: nRes = 31; break; 
     case 11: nRes = 30; break; 
     case 12: nRes = 31; break; 
    } 
 return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
} 

function valDia(oTxt)
{ 
   var bOk = false; 
   var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
   bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
   return bOk; 
} 

function valMes(oTxt)
{ 
    var bOk = false; 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
    return bOk; 
} 

function valAno(oTxt)
{ 
    var bOk = true; 
    var nAno = oTxt.value.substr(6); 
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
    if (bOk)
    { 
     for (var i = 0; i < nAno.length; i++)
     { 
       bOk = bOk && esDigito(nAno.charAt(i)); 
     } 
    } 
 return bOk; 
 } 

 function valFecha(oTxt)
 { 
    var bOk = true; 
		if (oTxt.value != "")
        { 
		 bOk = bOk && (valAno(oTxt)); 
		 bOk = bOk && (valMes(oTxt)); 
		 bOk = bOk && (valDia(oTxt)); 
		 bOk = bOk && (valSep(oTxt)); 
		 if (!bOk)
         { 
		  alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
		  oTxt.value = "01/01/1900"; 
		  oTxt.focus(); 
		 } 
		}
}

function esDigito(sChr)
{ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
}


	function catalogo_documentos()
	{
	  f=document.form1;
	  f.operacion.value="";			
	  pagina="sigesp_rpc_cat_doc.php";
  	  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
	}
	
	function ue_buscar()
		{
            f=document.form1;
			f.operacion.value="";
			ls_prov=f.txtprov.value;			
			pagina="sigesp_cat_docxpro.php?txtprov="+ls_prov;
  			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=400,resizable=yes,location=no");
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
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>