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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_elimin_carta_orden.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<title>Eliminaci&oacute;n de Carta Orden no Contabilizada</title>
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
-->
</style>
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<style type="text/css">
<!--
.Estilo15 {color: #6699CC}
-->
</style>
</head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td height="20" colspan="12" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo15">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="22" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Ejecutar" title="Ejecutar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="687">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("../shared/class_folder/class_datastore.php");
	$msg        = new class_mensajes();	
	$fun        = new class_funciones();	
	$lb_guardar = true;
	$io_grid    = new grid_param();
	$ds_carta   = new class_datastore();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];

	require_once("sigesp_scb_c_elimin_carta_orden.php");
	$io_carta=new sigesp_scb_c_elimin_carta_orden($la_seguridad);

	if( array_key_exists("operacion",$_POST))//Cuando aplicamos alguna operacion 
	{
		$ls_operacion= $_POST["operacion"];
		if(array_key_exists("rb_provbene",$_POST))
		{
			$ls_tipo=$_POST["rb_provbene"];
		}
		else
		{
			$ls_tipo="";
		}
	}
	else//Caso de apertura de la pagina o carga inicial
	{
		$ls_operacion= "NUEVO" ;			
	}

	//Declaración de parametros del grid.
	$titleProg[1]="";   
	$titleProg[2]="Documento";
	$titleProg[3]="Carta Orden";
	$titleProg[4]="Concepto";	
	$titleProg[5]="Monto";
	$titleProg[6]="Fecha";
	$titleProg[7]="Prov./Bene.";	
	$titleProg[8]="Nombre Prov./Bene.";
    $gridProg="grid_prog";
	
	if($ls_operacion == "PROCESAR")
	{
		$li_total=$_POST["totdoc"];
		$lb_valido=true;
		$li_entro=0;
		$io_carta->SQL->begin_transaction();
		$lb_conciliado=false;
		for($i=0;($i<=$li_total)&&($lb_valido);$i++)
		{
			if(array_key_exists("chksel".$i,$_POST))
			{
					$ls_numdoc=$_POST["txtnumdoc".$i];
					$ls_estcon=$_POST["estcon".$i];
					if($ls_estcon==0)
					{
						$li_entro++;					
						$ls_codban		= $_POST["codban".$i];
						$ls_ctaban		= $_POST["ctaban".$i];
						$ls_codope		= 'CH';
						$ls_prov		= $_POST["txtprov".$i];
						$ls_numerocarta = $_POST["txtcarta".$i];
						$ls_tipo		= $_POST["rb_provbene"];
						$ls_estprosol	= 'S';				
						$ls_estprogpago = 'P';
						$lb_valido=$io_carta->uf_procesar_eliminacion($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_prov,$ls_tipo,$ls_estprosol,$ls_estprogpago,$ls_numerocarta);
					}
					else
					{
						$msg->message("La Carta Orden $ls_numdoc no puede ser eliminada, ya fue Conciliada");
						$lb_conciliado=true;
					}
			}			
		}
		if($li_entro>0)
		{		
			if($lb_valido)
			{
				$io_carta->SQL->commit();	
				$msg->message("Proceso realizado correctamente !!!");
				$ls_operacion="NUEVO";
			}
			else
			{
				$io_carta->SQL->rollback();
				$msg->message("Error en operación !!!");
			}
		
		}
		else
		{
			if(!$lb_conciliado)
				$msg->message("No se seleccionaron Cheques !!!");
			$ls_operacion="NUEVO";
		}
	}
	if($ls_operacion=="NUEVO")
	{
		$ls_mov_operacion="NC";
	    $ls_seleccionado="";
		$ls_tipo='-';
		$ls_desproben="Ninguno";
		$lastspg = 0;
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		$ld_fecha=$fun->uf_cerosizquierda($ls_dia,2)."/".$fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
		$lastscg=0;
		$ls_documento="";
		$ld_fechadesde="";
		$ld_fechahasta="";
		$ldec_total_prog=0;
		$ls_numcarta="";
		
		$i=1;
		$object[$i][1] = "<input type=checkbox name=chksel".$i."    id=chksel".$i." value=1>";		
		$object[$i][2] = "<input type=text name=txtnumdoc".$i."     value=''    class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";
		$object[$i][3] = "<input type=text name=txtcarta".$i."     value=''    class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";
		$object[$i][4] = "<input type=text name=txtconmov".$i."     value=''    class=sin-borde readonly style=text-align:left size=22 maxlength=22>";
		$object[$i][5] = "<input type=text name=txtmonto".$i."      value='".number_format(0,2,",",".")."'    class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
		$object[$i][6] = "<input type=text name=txtfecmov".$i."     value=''    class=sin-borde readonly style=text-align:center size=10 maxlength=10  >";
		$object[$i][7] = "<input type=text name=txtprov".$i."       value=''    class=sin-borde readonly style=text-align:center size=15 maxlength=15 >"; 
		$object[$i][8]  = "<input type=text name=txtnomproben".$i." value=''    class=sin-borde readonly style=text-align:left size=30 maxlength=30>";			
		$li_total=1;		
	
	
	
	}
	if(($ls_operacion=="CAMBIO_TIPO"))
	{
		//Cargo los datos de las programaciones.
		$ld_fechadesde=$_POST["txtfechadesde"];
		$ld_fechahasta=$_POST["txtfechahasta"];
		$ls_documento=$_POST["txtdocumento"];	
		$ls_numcarta=$_POST["txtnumcarta"];	
		$data=$io_carta->uf_cargar_cartas_filtradas($ls_empresa,$ls_tipo,$ld_fechadesde,$ld_fechahasta,$ls_documento,$ls_numcarta);		
		$ldec_total_prog=0;
		if($data!="")
		{
			$ds_carta->data=$data;
			$li_total=$ds_carta->getRowCount("numdoc");
			for($i=1;$i<=$li_total;$i++)
			{
				$ls_numdoc  = $ds_carta->getValue("numdoc",$i);
				$ldec_monto = $ds_carta->getValue("monto",$i);
				$ld_fecmov  = $fun->uf_formatovalidofecha($ds_carta->getValue("fecmov",$i));
				$ld_fecmov  = $fun->uf_convertirfecmostrar($ld_fecmov);
				$ls_prov	= $ds_carta->getValue("cod_pro",$i);
				if($ls_prov=="----------")
				{
					$ls_prov=$ds_carta->getValue("ced_bene",$i);		
				}
				$ls_nomproben = $ds_carta->getValue("nomproben",$i);
				$ls_codban	  = $ds_carta->getValue("codban",$i);				
				$ls_ctaban	  = $ds_carta->getValue("ctaban",$i);				
				$ls_conmov	  = $ds_carta->getValue("conmov",$i);
				$ls_carta	  = $ds_carta->getValue("numcarord",$i);
				$ls_estcon	  = $ds_carta->getValue("estcon",$i);
				$ld_fecpropag='';						
				$object[$i][1] = "<input type=checkbox name=chksel".$i."   id=chksel".$i." value=1 >";		
				$object[$i][2] = "<input type=text name=txtnumdoc".$i."    value='".$ls_numdoc."'      class=sin-borde readonly style=text-align:center size=17 maxlength=15 ><input name=codban".$i." type=hidden id=codban".$i."  value='".$ls_codban."'> <input name=ctaban".$i."  type=hidden id=ctaban".$i."  value='".$ls_ctaban."'>";
				$object[$i][3] = "<input type=text name=txtcarta".$i."     value='$ls_carta'           class=sin-borde readonly style=text-align:center size=17 maxlength=15 ><input name=estcon".$i."  type=hidden id=estcon".$i."  value='".$ls_estcon."'>";
				$object[$i][4] = "<input type=text name=txtconmov".$i."    value='".$ls_conmov."'    class=sin-borde readonly style=text-align:left size=30 maxlength=22>";
				$object[$i][5] = "<input type=text name=txtmonto".$i."     value='".number_format($ldec_monto,2,",",".")."'    class=sin-borde readonly style=text-align:right size=18 maxlength=22>";
				$object[$i][6] = "<input type=text name=txtfecmov".$i."    value='".$ld_fecmov."'    class=sin-borde readonly style=text-align:center size=10 maxlength=10  >";
				$object[$i][7] = "<input type=text name=txtprov".$i."      value='".$ls_prov."'      class=sin-borde readonly style=text-align:center size=12 maxlength=15 >"; 
				$object[$i][8] = "<input type=text name=txtnomproben".$i." value='".$ls_nomproben."'    class=sin-borde readonly style=text-align:left size=30 maxlength=30>";			
			}
		}	
		else
		{
				$i=1;
				$object[$i][1] = "<input type=checkbox name=chksel".$i."    id=chksel".$i." value=1>";		
				$object[$i][2] = "<input type=text name=txtnumdoc".$i."     value=''    class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";
				$object[$i][3] = "<input type=text name=txtcarta".$i."     value=''      class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";
				$object[$i][4] = "<input type=text name=txtconmov".$i."     value=''    class=sin-borde readonly style=text-align:left size=22 maxlength=22>";
				$object[$i][5] = "<input type=text name=txtmonto".$i."      value='".number_format(0,2,",",".")."'    class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
				$object[$i][6] = "<input type=text name=txtfecmov".$i."     value=''    class=sin-borde readonly style=text-align:center size=10 maxlength=10  >";
				$object[$i][7] = "<input type=text name=txtprov".$i."       value=''    class=sin-borde readonly style=text-align:center size=15 maxlength=15 >"; 
				$object[$i][8]  = "<input type=text name=txtnomproben".$i." value=''    class=sin-borde readonly style=text-align:left size=30 maxlength=30>";			
				$li_total=1;
		}
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
?>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <br>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco" id="tabla">
    <tr class="titulo-ventana">
      <td height="22" colspan="6">Eliminaci&oacute;n de Carta Orden no Contabilizada</td>
    </tr>
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td style="text-align:right">Documento</td>
      <td colspan="3"><label>
        <input name="txtdocumento" type="text" id="txtdocumento" value="<?php print $ls_documento;?>" size="24" maxlength="15" onChange="rellenar_cad(this.value,15,this)" style="text-align:center">
      </label></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td style="text-align:right">Carta Orden</td>
      <td><input name="txtnumcarta" type="text" id="txtnumcarta" value="<?php print $ls_numcarta;?>" size="24" maxlength="15" onChange="javascript:rellenar_cad(this.value,15,this);" style="text-align:center"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td style="text-align:right">Desde</td>
      <td width="156"><label><input name="txtfechadesde" type="text" id="txtfechadesde"  style="text-align:center" value="<?php print $ld_fechadesde;?>" size="24" maxlength="10" onKeyPress="currencyDate(this);return keyRestrict(event,'1234567890');"   datepicker="true" ></label></td>
      <td width="54" style="text-align:right">Hasta</td>
      <td width="229"><div align="left">
        <input name="txtfechahasta" type="text" id="txtfechahasta"  style="text-align:center" value="<?php print $ld_fechahasta;?>" size="24" maxlength="10" onKeyPress="currencyDate(this);return keyRestrict(event,'1234567890');"   datepicker="true" >
      </div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="72" height="22">&nbsp;</td>
      <td><p>
        <label></label>
        <br>
      </p></td>
      <td colspan="3"><label>
        <input name="rb_provbene" id="rb_provbene" type="radio" value="P"  <?php print $rb_prov;?>>
Proveedor</label>
        <label>
        <input type="radio" name="rb_provbene" id="rb_provbene" value="B"  <?php print $rb_bene;?>>
Beneficiario</label></td>
      <td width="200">&nbsp;</td>
    </tr>
    <tr>
      <td height="13" colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13"><a href="javascript:uf_cambiar();" >Cargar Cartas Orden </a></td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="6"><div align="center"><?php $io_grid->makegrid($li_total,$titleProg,$object,800,'Carta Orden no Contabilizadas ',$gridProg);?>
          <p>
            <input name="totdoc"  type="hidden" id="totdoc"  value="<?php print $li_total?>">
            <input name="fila"    type="hidden" id="fila">
          </p>
      </div></td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td width="67" height="22">&nbsp;</td>
      <td height="22" colspan="3">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
  </table>
  <p><input name="operacion" type="hidden" id="operacion">
</p>
  </form>
</body>
<script language="javascript">
f = document.form1;
function ue_nuevo()
{
	f.operacion.value ="NUEVO";
	f.submit();
}

function ue_procesar()
{
  if (confirm("Está seguro de eliminar este(os) registro(s)?\n  Esta operación no puede reversarse"))
	 {
	   f.operacion.value ="PROCESAR";
	   f.submit();
	 }
}

function uf_cambiar()
{
  if ((f.rb_provbene[0].checked)||(f.rb_provbene[1].checked))
     {
       f.operacion.value="CAMBIO_TIPO";
 	   f.submit();
     }
  else
	 {
	   alert("Seleccione el tipo de destino (Proveedor/Beneficiario)");
	 }
}

function rellenar_cad(cadena,longitud,campo)
{
  if (campo.value!="")
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
	   campo.value=cadena;		
	 } 		
}
	
function currencyDate(date)
{ 
  ls_date=date.value;
  li_long=ls_date.length;
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
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>