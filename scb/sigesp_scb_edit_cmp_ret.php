<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Entrada de Comprobante de Gastos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>

<style type="text/css">
<!--
.style2 {font-size: 11px}
-->
</style>
</head>
<body>
<?php
$dat=$_SESSION["la_empresa"];
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
$msg=new class_mensajes();
$siginc=new sigesp_include();
$con=$siginc->uf_conectar();
require_once("../shared/class_folder/class_sql.php");
$fun=new class_funciones();
$SQL=new class_sql($con);
require_once("../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad= new sigesp_c_seguridad();
  /////////////////////////////////////Parametros necesarioa para seguridad////////////////////////////
	$ls_empresa=$dat["codemp"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
	$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
	$ls_logusr="";
	}
	$ls_sistema="SCB";
	$ls_ventana="sigesp_scb_edit_cmp_ret.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventana;
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
	
	include("sigesp_scb_c_cmp_ret.php");
	$io_cmpret=new sigesp_scb_c_cmp_ret($la_security);
	if (array_key_exists("operacion",$_POST))
	{
		$ls_numcom=$_POST["numcom"];
		$ls_operacion=$_POST["operacion"];
		$ls_numfac=$_POST["txtnumfac"];
		$ls_codret=$_POST["codret"];
		$ls_numope=$_POST["numope"];		
		$ld_fecfac=$_POST["txtfecfac"];
		$ls_numcon=$_POST["txtnumcon"];
		$ldec_totconiva=$_POST["txttotconiva"];
		$ldec_totsiniva=$_POST["txttotsiniva"];
		$ldec_baseimp=$_POST["txtbaseimp"];
		$ldec_porimp=$_POST["txtporimp"];
		$ldec_totimp=$_POST["txttotimp"];
		$ldec_retmun=$_POST["txtretmun"];
		$ls_solpag=$_POST["txtsolpag"];
	}
	else
	{
		$ls_operacion="";
		$ls_numcom=$_GET["numcom"];
		$ls_codret=$_GET["codret"];
		$ls_numope=$_GET["numope"];
		$ls_numfac=$_GET["numfac"];
		$io_cmpret->uf_cargar_dt_cmp_ret($ls_numcom,$ls_codret,$ls_numope,$ls_numfac);
		$ld_fecfac=$io_cmpret->ds_dt_cmpret->getValue("fecfac",1);
		$ld_fecfac=$fun->uf_convertirfecmostrar($ld_fecfac);
		$ls_numcon=$io_cmpret->ds_dt_cmpret->getValue("numcon",1);
		$ls_numnd =number_format($io_cmpret->ds_dt_cmpret->getValue("numnd",1),2,",",".");
		$ls_numnc =number_format($io_cmpret->ds_dt_cmpret->getValue("numnc",1),2,",",".");
		//$ls_tiptra=$io_cmpret->ds_dt_cmpret->getValue("tiptrans",1);
		$ldec_totconiva=number_format($io_cmpret->ds_dt_cmpret->getValue("totcmp_con_iva",1),2,",",".");
		$ldec_totsiniva=number_format($io_cmpret->ds_dt_cmpret->getValue("totcmp_sin_iva",1),2,",",".");
		$ldec_baseimp=number_format($io_cmpret->ds_dt_cmpret->getValue("basimp",1),2,",",".");
		$ldec_porimp=number_format($io_cmpret->ds_dt_cmpret->getValue("porimp",1),2,",",".");
		$ldec_totimp=number_format($io_cmpret->ds_dt_cmpret->getValue("totimp",1),2,",",".");
		$ldec_retmun=number_format($io_cmpret->ds_dt_cmpret->getValue("iva_ret",1),2,",",".");
		$ls_solpag=$io_cmpret->ds_dt_cmpret->getValue("numsop",1);
		
	}

    if($ls_operacion=="GUARDAR")
	{
		$io_cmpret->io_sql->begin_transaction();
		$lb_valido=$io_cmpret->uf_update_dt_cmp_ret($ls_codret,$ls_numcom,$ls_numope,$ld_fecfac,$ls_numfac,$ls_numcon,uf_convertir($ldec_totsiniva),uf_convertir($ldec_totconiva),uf_convertir($ldec_baseimp),uf_convertir($ldec_porimp),uf_convertir($ldec_totimp),uf_convertir($ldec_retmun),$ls_solpag );
												    
		if($lb_valido)
		{
			$io_cmpret->io_sql->commit();
			print "<script>";
			print "alert('El comprobante fue modificado');";
			print "opener.document.form1.operacion.value='CARGAR_DT';";
			print "opener.document.form1.submit();";
			print "</script>";
		}
		else
		{
			$io_cmpret->io_sql->rollback();
		}
	}
function uf_convertir($ls_numero)
{
	$ls_numero=str_replace(".","",$ls_numero);
	$ls_numero=str_replace(",",".",$ls_numero);
	return $ls_numero;
}	

 ?>
<form method="post" name="form1" action=""> 
<table width="583" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
   <td colspan="4" class="titulo-celda">Entrada de Comprobante de Gastos </td>
  </tr>
  <tr>
    <td height="15" colspan="4" >&nbsp;</td>
  </tr>
  <tr>
    <td width="112" height="22" ><div align="right">N&ordm; Factura </div></td>
    <td width="173" ><div align="left">
      <input name="txtnumfac" type="text" id="txtnumfac" style="text-align:center" value="<?php print $ls_numfac;?>" size="24" maxlength="15">
    </div></td>
    <td width="137" ><div align="right">
      <div align="right">Total con IVA</div>
    </div></td>
    <td width="159" ><input name="txttotconiva" type="text" id="txttotconiva" style="text-align:right" value="<?php print $ldec_totconiva;?>" size="24" maxlength="22" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
  </tr>
  <tr>
    <td height="22" ><div align="right">N&ordm; Control </div></td>
    <td height="22" ><div align="left">
      <input name="txtnumcon" type="text" id="txtnumcon" style="text-align:center" value="<?php print $ls_numcon;?>" size="24" maxlength="15">
    </div></td>
    <td height="22" ><div align="right">Base Imponible </div></td>
    <td height="22" ><input name="txtbaseimp" type="text" id="txtbaseimp" style="text-align:right" value="<?php print $ldec_baseimp;?>" size="24" maxlength="22" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
  </tr>
  <tr>
    <td height="22" ><div align="right">N&ordm; Solicitud de Pago</div></td>
    <td height="22" ><input name="txtsolpag" type="text" id="txtsolpag" value="<?print $ls_solpag;?>" size="24" maxlength="22"  style="text-align:center"></td>
    <td height="22" ><div align="right">Porcentaje Impuesto</div></td>
    <td height="22" ><div align="left">
      <input name="txtporimp" type="text" id="txtporimp" style="text-align:right" value="<?php print $ldec_porimp;?>" size="24" maxlength="22" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)">
    </div></td>
  </tr>
  <tr>
    <td height="22" ><div align="right">Fecha</div></td>
    <td height="22" ><div align="left">
      <input name="txtfecfac" type="text" id="txtfecfac" style="text-align:center" value="<?php print $ld_fecfac;?>" size="24" maxlength="10" onKeyPress="currencyDate(this);">
    </div></td>
    <td height="22" ><div align="right">Total Impuesto</div></td>
    <td height="22" ><input name="txttotimp" type="text" id="txttotimp" style="text-align:right" value="<?php print $ldec_totimp;?>" size="24" maxlength="22" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
  </tr>
  <tr>
    <td height="22" ><div align="right">Total sin IVA</div></td>
    <td height="22" ><input name="txttotsiniva" type="text" id="txttotsiniva" style="text-align:right" value="<?php print $ldec_totsiniva;?>" size="24" maxlength="22" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
    <td height="22" ><div align="right">Retenci&oacute;n Municipal</div></td>
    <td height="22" ><input name="txtretmun" type="text" id="txtretmun" style="text-align: right" value="<?php print $ldec_retmun;?>" size="24" maxlength="22" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
  </tr>
  <tr>
    <td height="41" ><div align="right"></div></td>
    <td height="41" ><div align="left">
      <label></label>
      <div align="right"><a href="javascript:uf_guardar();"><img src="../shared/imagebank/aprobado.gif" width="15" height="15" border="0">Guardar Cambios</a></div>
    </div></td>
    <td height="41" ><div align="left"><a href="javascript:uf_cancelar();"><img src="../shared/imagebank/eliminar.gif" width="15" height="15" border="0">Cancelar</a></div></td>
    <td height="41" ><div align="left"></div></td>
  </tr>
  <tr>
    <td height="15" colspan="4" ><input name="operacion" type="hidden" id="operacion">
      <input name="numcom" type="hidden" id="numcom" value="<?php print $ls_numcom;?>">
      <input name="codret" type="hidden" id="codret" value="<?php print $ls_codret;?>">
      <input name="numope" type="hidden" id="numope" value="<?php print $ls_numope;?>">
      <input name="numfac" type="hidden" id="numfac" value="<?php print $ls_numfac;?>"></td>
  </tr>
</table>
</form>
</body>
<script language="JavaScript">
  function uf_guardar()
  {
  	f=document.form1;
	f.operacion.value="GUARDAR";
	f.action="sigesp_scb_edit_cmp_ret.php";
	f.submit();
  }
  
  function uf_cancelar()
  {
	  close()
  }
	 	
function valid_cmp(cmp)
{
	if((cmp.value==0)||(cmp.value==""))
	{
		alert("Introduzca un numero comprobante valido");
		cmp.focus();
	}
	else
	{
		rellenar_cad(cmp.value,15,"doc");
	}
}

//Funciones de validacion de fecha.
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
	else
	{
		document.form1.txtcomprobante.value=cadena;
	}

}

function currencyFormat(fld, milSep, decSep, whichCode) 
{  
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
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
    if (len > 2)
	{ 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--)
	 { 
       if (j == 3)
	   { 
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
/*************************************************
  Funcion que valida el texto de un caja de texto
  segun el tipo de dto que se quiere validar
  "x" -> Cualquier caracter menos comillas simples(') y comillas dobles(")
  "i" -> Numericos (Ejm: Codigos)
  "c" -> Numericos con guiones (Ejm: Cuentas Bancarias)
  "s" -> Alfabeticos (Ejm: Nombres)
  "a" -> Alfanumericos (Ejm: Direcciones)
  "e" -> email
  "t" -> telefono (Ejm: 0251-2555555)
  "g" -> Codigos alfanumericos y guiones
  "d" -> double (Ejm: 2.000.000,00)
  "m" -> enteros con puntos de miles (Ejm: 123.456.789)
  NOTA: Algunos caracteres para guiarse 
   Backspace=8, Enter=13, Barra Espaciadora= 32, '0'=48, '9'=57, 'A'=65, 'Z'=90, 'a'=97, 'z'=122		
**************************************************/
//var nav4 = window.Event ? true : false;
function validaCajas(cajaTexto,tipo_dato,evt)
{
	key = evt.which || evt.keyCode;
	if (key <= 13)
	{return true;}
	if ((tipo_dato == "x")||(tipo_dato == "i")||(tipo_dato == "c")||(tipo_dato == "s")||
	    (tipo_dato == "a")||(tipo_dato == "e")||(tipo_dato == "t")||(tipo_dato == "g")||
		(tipo_dato == "r")||
		(tipo_dato == 0)||(tipo_dato == 1)||(tipo_dato == 2)||(tipo_dato == 3)||
	    (tipo_dato == 4)||(tipo_dato == 5)||(tipo_dato == 6)||(tipo_dato == 7)||
		(tipo_dato == 10))
	{
		if (((arguments.length > 3) && (cajaTexto.value.length < arguments[3])) ||
		    (arguments.length <= 3))
		{
			switch(tipo_dato)
			{
				case "x": case 0: return ((key != 34) && (key != 39));break;
				case "i": case 1: return ((key >= 48 && key <= 57)); break;
				case "c": case 2: return ((key >= 48 && key <= 57) || (key == 45)); break;
				case "s": case 3: return ((key == 32) || 
								  (key >= 65 && key <= 90) || (key >= 97 && key <= 122) || 
								  (key == 225) || (key == 233) || (key == 237) || (key == 243) || (key == 250) || //VOCALES MINUSCULAS ACENTUADAS
								  (key == 193) || (key == 201) || (key == 205) || (key == 211) || (key == 218) || //VOCALES MAYUSCULAS ACENTUADAS
								  (key == 241) || (key == 209) || (key == 44) || (key == 46) // Ñ, ñ, "," y "."
								  ); break;
				case "a": case 4: return ((key == 32) || (key >= 48 && key <= 57) || 
								  (key >= 65 && key <= 90) || (key >= 97 && key <= 122) || 
								  (key == 225) || (key == 233) || (key == 237) || (key == 243) || (key == 250) || //VOCALES MINUSCULAS ACENTUADAS
								  (key == 193) || (key == 201) || (key == 205) || (key == 211) || (key == 218) || //VOCALES MAYUSCULAS ACENTUADAS
								  (key == 241) || (key == 209) || (key == 44)// Ñ, ñ y ","
								  ); break;
				case "e": case 5: return ((key >= 45 && key <= 57) || (key >= 65 && key <= 122) || (key == 64 && cajaTexto.value.indexOf('@', 0) == -1));break;
				case "t": case 6: if (cajaTexto.value.length == 4 && cajaTexto.value.indexOf('-', 0) == -1 && key != 8)
						          {cajaTexto.value = cajaTexto.value + "-";}
						          return ((key > 48 && key <= 57 && cajaTexto.value != "") || (key == 48 ));break;
				case "g": case 7: return ((key == 32) || (key >= 48 && key <= 57) ||
								  (key >= 65 && key <= 90) || (key >= 97 && key <= 122) ||
								  (key == 241) || (key == 209) || (key == 45)// Ñ, ñ y "-"
								  ); break;
				case "r": case 10: return ((key >= 48 && key <= 57 && cajaTexto.value != "") || (cajaTexto.value == "" && (key == 74 || key == 106)));break;
			}
		}
		else
		{return false;}
	}
	else
	{
		switch(tipo_dato)
		{
			case "d":
			case 8  : if (arguments.length > 3)			
					  {
						if (parseFloat(uf_convertir_monto(cajaTexto.value)) == 0)
						{cajaTexto.value = "";}
						if (document.selection)//IE
						  selecciono = (document.selection.createRange().text.length > 0);
						else//NS ó MFF
						  selecciono = (cajaTexto.selectionStart < cajaTexto.value.length);
						if ((cajaTexto.value.length < arguments[3]) || (key <= 13) || (selecciono))
						{
						  if (selecciono)
						  {
							if (document.selection)//IE
							{
							  seleccion = document.selection.createRange();
							  seleccion.text="";
							  cajaTexto.createTextRange().moveStart('character',-1);
							  cajaTexto.createTextRange().moveEnd('character',0);
							  cajaTexto.createTextRange().select();
							}
							else//Otro NS ó MFF
							{
							  cajaTexto.value = (cajaTexto.value).substring(0,cajaTexto.selectionStart);
							}
						  };
						  return (currencyFormat(cajaTexto,'.',',',key));
						}
						else
						{return false;}
					  }
					  else
					  {return (currencyFormat(cajaTexto,'.',',',key));}
					  break;
			case "m": 
			case 9  : if (arguments.length > 3)
					  {
						if (document.selection)//IE
						  selecciono = (document.selection.createRange().text.length > 0);
						else//NS ó MFF
						  selecciono = (cajaTexto.selectionStart < cajaTexto.value.length);
						if ((cajaTexto.value.length < arguments[3]) || (key <= 13) || (selecciono))
						{
						   if (parseFloat(uf_convertir_monto(cajaTexto.value)) == 0)
						   {cajaTexto.value = "";}
						   return (FormatoMiles(cajaTexto,'.',key));
						}
						else
						{return false;}
					  }
					  else
					  {return (FormatoMiles(cajaTexto,'.',key));} 
					  break;			
		}
	}
}


/*****************************************************************
  Funcion que quita a los extremos de un string los espacios
  en blancos
******************************************************************/
function trim(cadena)
{
	return cadena.replace(/^\s*|\s*$/g,"");
}

function ue_valida_null(field,mensaje)
{
    with (field) 
    {
      if (((value==null||trim(value)==""||parseFloat(uf_convertir_monto(value))==0) && 
           (type=="text"||type=="textarea")) ||
	      ((value=="s1") && (type=="select-one")))
      {
		if ((arguments.length > 1) && (mensaje != ""))
		{
		  if ((type=="text") || (type=="textarea"))
		  {alert("Debe Indicar "+mensaje+"!!!");}
		  else if (type=="select-one")
		  {alert("Debe Seleccionar "+mensaje+"!!!")}
		}
        return false;
      }
      else
      {return true;}
    }
}
/*************************************************
  Funcion que coloca el contenido de una caja de
  texto con formato de double (xxx.xxx,xx)
  Ejm: 1..00.0 -> 1.000,00
**************************************************/
function ue_getformat(txt)
{	
	if(ue_valida_null(txt,"") == false)
	{txt.value="0,00";}
	else
	{txt.value=uf_convertir(uf_convertir_monto(txt.value));}
	if ((arguments.length > 1) && (arguments[1] == "i"))
	{txt.value=txt.value.substring(0,txt.value.length-3);}
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

</script>
</html>
