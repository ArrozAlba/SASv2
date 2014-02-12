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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_anulacion.php",$ls_permisos,&$la_seguridad,$la_permisos);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Anulaci&oacute;n de Documentos</title>
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
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
</head>

<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
    require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("sigesp_scb_c_anulacion.php");	
	$msg		   = new class_mensajes();	
	$fun		   = new class_funciones();	
	$lb_guardar	   = true;
    $sig_inc	   = new sigesp_include();
    $con		   = $sig_inc->uf_conectar();
	$io_grid       = new grid_param();
	$ds_sol		   = new class_datastore();
	$io_documentos = new sigesp_scb_c_anulacion($la_seguridad);

	if( array_key_exists("operacion",$_POST))//Cuando aplicamos alguna operacion 
	{
		$ls_operacion= $_POST["operacion"];
		$ls_tipo=$_POST["rb_provbene"];
		$ls_codope=$_POST["codope"];
		
	}
	else//Caso de apertura de la pagina o carga inicial
	{
		$ls_operacion= "NUEVO" ;
		$ls_tipo='-';
		$ls_desproben="Ninguno";
		$li_row=1;
		$object[$li_row][1] = "<input type=checkbox name=chksel".$li_row."   id=chksel".$li_row." value=1 >";		
		$object[$li_row][2] = "<input type=text name=txtnumdoc".$li_row."    value=''    class=sin-borde readonly style=text-align:center size=17 maxlength=15>";
		$object[$li_row][3] = "<input type=text name=txtcodban".$li_row."    value=''    class=sin-borde readonly style=text-align:center size=22 maxlength=22>";
		$object[$li_row][4] = "<input type=text name=txtctaban".$li_row."    value=''    class=sin-borde readonly style=text-align:center size=22 maxlength=22>";
		$object[$li_row][5] = "<input type=text name=txtfecmov".$li_row."    value=''    class=sin-borde readonly style=text-align:center size=10 maxlength=10>";			
		$object[$li_row][6] = "<input type=hidden name=txtcedbene".$li_row."   value=''><input type=hidden name=txtcodpro".$li_row."    value=''    ><input type=text name=txtnomproben".$li_row." value=''    class=sin-borde readonly style=text-align:left size=17 maxlength=15>";			
		$object[$li_row][7] = "<input type=text name=txtmonto".$li_row."     value=''    class=sin-borde readonly style=text-align:right size=17 maxlength=15>";
		$li_total=1;
		$ls_codope="NC";
	}
	
	if($ls_tipo=='P')
	{
		$rb_prov="checked";
		$rb_bene="";
	}
	elseif($ls_tipo=='B')
	{
		$rb_prov="";
		$rb_bene="checked";
	}
	else
	{
		$rb_prov="";
		$rb_bene="";
	}
	//Declaración de parametros del grid.
	$title[1]="";   $title[2]="Documento";     $title[3]="Banco";     $title[4]="Cuenta.";  $title[5]="Fecha"; $title[6]="Proveedor\Beneficiario"; $title[7]="Monto";   
    $grid="grid";
	
	if($ls_operacion == "GUARDAR")
	{
		$li_total=$_POST["totsol"];
		$lb_valido=true;
		$io_sol->SQL->begin_transaction();
		for($i=0;($i<=$li_total)&&($lb_valido);$i++)
		{
			if(array_key_exists("chksel".$i,$_POST))
			{
					$ls_numdoc=$_POST["txtnumdoc".$i];
					$ldec_monto=$_POST["txtmonto".$i];
					$ls_codban=$_POST["txtcodban".$i];
					$ls_ctaban=$_POST["txtctaban".$i];
					$ls_prov=$_POST["txtcodpro".$i];
					$ls_bene=$_POST["txtcedbene".$i];
					$ls_nomproben=$_POST["txtnomproben"];
					$ls_estmov='C';
					$lb_valido=$io_sol->uf_procesar_anulacion_movimiento($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_estmov);
			}			
		}		
		if($lb_valido)
		{
			$io_sol->SQL->commit();	
			$msg->message("El movimiento fue registrado");
		}
		else
		{
			$io_sol->SQL->rollback();
		}
		
	}
	if(($ls_operacion=="CAMBIO_TIPO"))
	{
		//Cargo los datos de los movimientos.
		$io_documentos->uf_cargar_documentos($ls_codemp,$ls_codope,$object,$li_total);		
		$ldec_total_prog=0;		
	}

	
	switch ($ls_codope) {
	case 'CH':
	   $rb_cheque  = "selected";
   	   $rb_credito = "";
   	   $rb_debito  = "";
	   $rb_deposito= "";
	   $rb_retiro  = "";
	   break;
	case 'NC':
	   $rb_cheque  = "";
   	   $rb_credito = "selected";
   	   $rb_debito  = "";
	   $rb_deposito= "";
	   $rb_retiro  = "";
	   break;
	case 'ND':
   	   $rb_cheque  = "";
   	   $rb_credito = "";
   	   $rb_debito  = "selected";
	   $rb_deposito= "";
	   $rb_retiro  = "";
	   break;
	case 'DP':
   	   $rb_cheque  = "";
   	   $rb_credito = "";
   	   $rb_debito  = "";
	   $rb_deposito= "selected";
	   $rb_retiro  = "";
	   break;
   case 'RE':
   	   $rb_cheque  = "";
   	   $rb_credito = "";
   	   $rb_debito  = "";
	   $rb_deposito= "";
	   $rb_retiro  = "selected";
	   break;   
	}
  ?>
  <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <br>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td colspan="4">Anulaci&oacute;n de Documentos </td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">Operaci&oacute;n</div></td>
      <td colspan="2"  align="left">
            
        <select name="codope" id="codope" onChange="javascript:uf_cambiar();">
          <option value="NC" <?php print $rb_credito;?>>Nota Cr&eacute;dito</option>
          <option value="ND" <?php print $rb_debito;?>>Nota D&eacute;bito</option>
          <option value="CH" <?php print $rb_cheque;?>>Cheque</option>
          <option value="DP" <?php print $rb_deposito;?>>Dep&oacute;sito</option>
          <option value="RE" <?php print $rb_retiro;?>>Ret&iacute;ro</option>
        </select>
       </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="75" height="22">&nbsp;</td>
      <td colspan="2"><p>
        <label>
        <input name="rb_provbene" id="rb_provbene" type="radio" value="P" onClick="javascript:uf_cambiar();" <?php print $rb_prov;?>>
  Proveedor</label>
        <label>
        <input type="radio" name="rb_provbene" id="rb_provbene" value="B" onClick="javascript:uf_cambiar();" <?php print $rb_bene;?>>
  Beneficiario</label>
        <br>
      </p></td>
      <td width="203">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center"><?php $io_grid->makegrid($li_total,$title,$object,770,'Documentos ',$grid);?>
          <input name="totsol"  type="hidden" id="totsol"  value="<?php print $li_total;?>">
          <input name="fila"    type="hidden" id="fila">
      </div></td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td width="249" height="22">&nbsp;</td>
      <td width="251" height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
  </table>
  <p><input name="operacion" type="hidden" id="operacion">
</p>
  </form>
</body>
<script language="javascript">
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value ="NUEVO";
	f.action="sigesp_scb_p_desprogpago.php";
	f.submit();
}

function ue_guardar()
{
	f=document.form1;
	f.operacion.value ="GUARDAR";
	f.action="sigesp_scb_p_desprogpago.php";
	f.submit();
}

function ue_eliminar()
{
	
}

function ue_buscar()
{
	window.open("sigesp_catdinamic_progpago.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

    //Catalogo de cuentas contables
	function catalogo_cuentabanco()
	 {
	   f=document.form1;
	   ls_codban=f.txtcodban.value;
	   ls_denban=f.txtdenban.value;
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
	 
	 function cat_bancos()
	 {
	   f=document.form1;
	   pagina="sigesp_cat_bancos.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
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
			//alert(ls_long);


  //  return false; 
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
   
	function catprovbene()
	{
		f=document.form1;
		if(f.rb_provbene[0].checked==true)
		{
			f.txtprovbene.disabled=false;	
			window.open("sigesp_catdinamic_prov.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else if(f.rb_provbene[1].checked==true)
		{
			f.txtprovbene.disabled=false;	
			window.open("sigesp_catdinamic_bene.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		}
	}   

	function uf_verificar_provbene(lb_checked,obj)
	{
		f=document.form1;
	
		if((f.rb_provbene[0].checked)&&(obj!='P'))
		{
			f.tipo.value='P';
			f.txtprovbene.value="";
			f.txtdesproben.value="";
			f.txttitprovbene.value="Proveedor";
		}
		if((f.rb_provbene[1].checked)&&(obj!='B'))
		{
			f.txtprovbene.value="";
			f.txtdesproben.value="";
			f.tipo.value='B';
			f.txttitprovbene.value="Beneficiario";
		}
		if((f.rb_provbene[2].checked)&&(obj!='N'))
		{
			f.txtprovbene.value="----------";
			f.txtdesproben.value="Ninguno";
			f.tipo.value='N';
			f.txttitprovbene.value="";
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
	
	function uf_cambiar()
	{
		f=document.form1;
		f.operacion.value="CAMBIO_TIPO";
		f.action="sigesp_scb_p_anulacion.php";
		f.submit();
	}
</script>
</html>