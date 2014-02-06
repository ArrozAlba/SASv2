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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_reverso_entregach.php",$ls_permisos,$la_seguridad,$la_permisos);
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reverso de Entrega de Cheques</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/valida_fecha.js"></script>
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
.Estilo1 {color: #6699CC}
-->
</style></head>
<body>
<span class="toolbar"><a name="00"></a></span>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="21" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22">&nbsp;</td>
    <td class="toolbar" width="691">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	$msg=new class_mensajes();	
	require_once("../shared/class_folder/class_funciones.php");
	$fun=new class_funciones();	
	$lb_guardar=true;
 	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	
	//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];

	require_once("sigesp_scb_c_reverso_entregach.php");
	$in_classentrega=new sigesp_scb_c_reverso_entregach($la_seguridad);
	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ld_fecha=$_POST["txtfecha"];		
		$ls_tipo=$_POST["rb_provbene"];
		$ls_cedula=$_POST["txtcedula"];
		$ls_nombre=$_POST["txtnombre"];
		$ls_provbene=$_POST["txtprovbene"];
		$ls_desproben=$_POST["txtdesproben"];
		$ls_documento=$_POST["txtdocumento"];
	}
	else
	{
		$ls_operacion= "NUEVO" ;		
		$lb_chkinternet="";
		$ls_tipo="-";
		$ls_provbene="----------";
		$ls_desproben="Ninguno";
		$ld_fecha=date("d/m/Y");
		$ls_cedula="";
		$ls_nombre="";
		$ls_documento="";
		
	}	
	$grid2="grid";
	$title[1]="";       $title[2]="Documento";      $title[3]="Descripción";   $title[4]="Monto";   	   $title[5]="Banco";    $title[6]="Cuenta";      $title[7]="Voucher";     
	if($ls_operacion == "NUEVO")
	{
		$ls_operacion= "";
		$li_total=5;
		
		for($li_row=1;$li_row<=$li_total;$li_row++)
		{
			$object[$li_row][1] = "<input type=checkbox name=chksel".$li_row."   id=chksel".$li_row." value=1 style=width:15px;height:15px onClick='return false;'>";		
			$object[$li_row][2] = "<input type=text     name=txtnumdoc".$li_row."       value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
			$object[$li_row][3] = "<input type=text     name=txtdesdoc".$li_row."    value='' class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
			$object[$li_row][4] = "<input type=text     name=txtmonto".$li_row."     value='' class=sin-borde readonly style=text-align:center size=18 maxlength=22>";
			$object[$li_row][5] = "<input type=text     name=txtcodban".$li_row."    value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>"; 
			$object[$li_row][6] = "<input type=text     name=txtcuenta".$li_row."    value='' class=sin-borde readonly style=text-align:center size=26 maxlength=25>";
			$object[$li_row][7] = "<input type=text     name=txtvoucher".$li_row."   value='' class=sin-borde readonly style=text-align:center size=26 maxlength=25>";
		}
	}
	if($ls_operacion == "GUARDAR")
	{			
		$li_total=$_POST["totalrows"];
		$li_aux=0;
		for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			if(array_key_exists("chksel".$li_i,$_POST))
			{
				$ls_numdoc=$_POST["txtnumdoc".$li_i];
				$ls_codban=$_POST["txtcodban".$li_i];		
				$ls_ctaban=$_POST["txtcuenta".$li_i];
				$ls_codope='CH';
				$li_aux=$li_aux+1;//Contador de registro
				$arr_entregacheques["numdoc"][$li_aux]=$ls_numdoc;			
				$arr_entregacheques["codban"][$li_aux]=$ls_codban;			
				$arr_entregacheques["ctaban"][$li_aux]=$ls_ctaban;										
			}
		}
		if($li_aux>0)
		{
			$lb_valido=$in_classentrega->uf_procesar_reversoch($arr_entregacheques,$ls_provbene,$ls_tipo,0);
			if($lb_valido)		
			{
				$in_classentrega->SQL->commit();
				$msg->message("Entrega Reversada !!!");
			}
			else
			{
				$in_classentrega->SQL->rollback();
				$msg->message($in_classentrega->is_msg_error);
			}
		}
		$ls_operacion="CARGAR";
	}
 if ($ls_operacion == "CARGAR")
	{			
		$ls_cedula=$_POST["txtcedula"];
		$ls_nombre=$_POST["txtnombre"];
		$in_classentrega->uf_cargar_cheques($ls_cedula,$ls_nombre,$ls_provbene,$ls_tipo,&$object,&$li_total);
	}	
	if($ls_tipo=='-')
	{
		$rb_n="checked";
		$rb_p="";
		$rb_b="";			
	}
	if($ls_tipo=='P')
	{
		$rb_n="";
		$rb_p="checked";
		$rb_b="";			
	}
	if($ls_tipo=='B')
	{
		$rb_n="";
		$rb_p="";
		$rb_b="checked";			
	}
?>
  <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <p>&nbsp;</p>
  <p><br>
  </p>
  <table width="722" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="4">Reverso de Entrega de Cheques </td>
    </tr>
    <tr>
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">Tipo Destino</div></td>
      <td colspan="2">
        <div align="left"></div>
        <table width="249" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td width="353"><label>
              <input type="radio" name="rb_provbene" id="radio" value="P" class="sin-borde" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" <?php print $rb_p;?>>
          Proveedor</label>
                <label>
                <input type="radio" name="rb_provbene" id="radio" value="B" class="sin-borde" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" <?php print $rb_b;?>>
          Beneficiario</label>
                <label>
                <input name="rb_provbene" id="radio" type="radio"  class="sin-borde" value="-" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" <?php print $rb_n;?>>
          Ninguno</label>
                <input name="tipo" type="hidden" id="tipo"></td>
          </tr>
      </table></td>
      <td width="180">&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">
        <label>
        <input name="txttitprovbene" type="text" id="txttitprovbene" size="15" readonly="true"  class="sin-borde" style="text-align:right">
        </label>
      </div></td>
      <td colspan="3"><div align="left">
          <input name="txtprovbene" type="text" id="txtprovbene" style="text-align:center" value="<?php print $ls_provbene?>" size="24" readonly>
          <a href="javascript:catprovbene()"><img id="bot_provbene" src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>
          <input name="txtdesproben" type="text" id="txtdesproben" size="42" maxlength="250" class="sin-borde" value="<?php print $ls_desproben;?>"  readonly>
      </div></td>
    </tr>
    <tr>
      <td><div align="right">Documento
      </div>
      <div align="left"></div></td>
      <td colspan="2"><label>
        <input name="txtdocumento" type="text" id="txtdocumento" onBlur="javascript: rellenar_cad(document.form1.txtdocumento.value,15,'doc');" value="<?php print $ls_documento ?>">
      </label></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr class="formato-azul">
      <td height="15" colspan="4"><div align="center"><strong>Datos de la Entrega </strong></div></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td width="203">&nbsp;</td>
      <td width="228">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">C&eacute;dula</td>
      <td style="text-align:left"><input name="txtcedula" type="text" id="txtcedula" value="<?php print $ls_cedula;?>" onKeyPress="return keyRestrict(event,'0123456789'); " maxlength="10" style="text-align:center"></td>
      <td style="text-align:right">Fecha</td>
      <td style="text-align:left"><input name="txtfecha" type="text" id="txtfecha"  style="text-align:center" value="<?php print $ld_fecha;?>" size="24" maxlength="10" datepicker="true" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Nombre</td>
      <td height="13" colspan="3" style="text-align:left"><input name="txtnombre" type="text" id="txtnombre" value="<?php print $ls_nombre;?>" size="111" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',-.;*&?¿!¡+()[]{}%@/'+'´áéíóú');" style="text-align:left"></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13"><a href="javascript:uf_cargar();">Cargar Cheques </a></td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="13" colspan="4"><div align="center">
        <?php $io_grid->makegrid($li_total,$title,$object,770,"Cheques a entregar",$grid2);?>
        <input name="totalrows"  type="hidden" id="totalrows"  size=5 value="<?php print $li_total?>">
        <input name="lastscg" type="hidden" id="lastscg" size=5 value="<?php print $lastscg;?>">
        <input name="delete_scg" type="hidden" id="delete_scg" size=5>
</div></td>
    </tr>
    <tr>
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
  </table>
  <p><input name="operacion" type="hidden" id="operacion">
</p>
  </form>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
function ue_nuevo()
{
	location.href="sigesp_scb_p_reverso_entregach.php";
}

function ue_guardar()
{
	f=document.form1;
	li_cambiar=f.cambiar.value;
	if (li_cambiar==1) 
	   {
	     f.operacion.value ="GUARDAR";
		 f.action="sigesp_scb_p_reverso_entregach.php";
		 f.submit();
	   }
	else
	   {
	     alert("No tiene permiso para realizar esta operación !!!");
	   }   
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

//Funciones de validacion de fecha.
function rellenar_cad(cadena,longitud,campo)
{
	if(cadena!="")
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
	}		
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
   
function uf_cargar()
{
	f=document.form1;
	f.operacion.value ="CARGAR";	
	f.submit();	
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>