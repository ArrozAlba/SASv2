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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_elimin_chq.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<title>Eliminaci&oacute;n de Cheques no Contabilizados</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td height="20" colspan="12" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
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
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="22" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Procesar" title="Procesar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="21"><div align="center"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
    <td class="toolbar" width="691">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("../shared/class_folder/class_datastore.php");
	$io_include = new sigesp_include();
	$ls_conect  = $io_include->uf_conectar();
	$io_sql     = new class_sql($ls_conect);
	$msg		= new class_mensajes();	
	$fun		= new class_funciones();	
	$lb_guardar	= true;
	$io_grid	= new grid_param();
	$ls_empresa = $_SESSION["la_empresa"]["codemp"];
	
	require_once("sigesp_scb_c_elimin_chq.php");
	$io_cheques=new sigesp_scb_c_elimin_chq($la_seguridad);

	if( array_key_exists("operacion",$_POST))//Cuando aplicamos alguna operacion 
	{
		$ls_operacion= $_POST["operacion"];
		if(array_key_exists("rb_provbene",$_POST))
		{
			$ls_tipo=$_POST["rb_provbene"];			
		}
		else
		{
			$ls_tipo="-";			
		}
	}
	else//Caso de apertura de la pagina o carga inicial
	{
		$ls_operacion= "NUEVO" ;			
	}

	//Declaración de parametros del grid.
	$titleProg[1]="";   
	$titleProg[2]="Documento";     
	$titleProg[3]="Concepto";     
	$titleProg[4]="Monto";   	
	$titleProg[5]="Fecha";   
	$titleProg[6]="Proveedor";   
	$titleProg[7]="Beneficiario"; 
	$titleProg[8]="Nombre Prov./Bene.";
    $gridProg="grid_prog";
	
	if($ls_operacion == "PROCESAR")
	{
		$li_total=$_POST["totdoc"];
		$lb_valido=true;
		$li_entro=0;
		$io_cheques->SQL->begin_transaction();
		$lb_conciliado=false;
		$ls_tipo   = "-";
		for ($i=1;($i<=$li_total)&&($lb_valido);$i++)
		    {
			  if (array_key_exists("chksel".$i,$_POST))
			     {
				   $ls_estcon=$_POST["estcon".$i];
				   $ls_numdoc=$_POST["txtnumdoc".$i];
				   if ($ls_estcon==0)
					  {
					    $li_entro++;
						$ls_codban = $_POST["codban".$i];
						$ls_ctaban = $_POST["ctaban".$i];
						$ls_codope = 'CH';
						$ls_prov   = $_POST["txtprov".$i];
						$ls_estprosol='S';				
						$ls_estprogpago='P';
						$lb_valido=$io_cheques->uf_procesar_eliminacion($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$ls_prov,$ls_tipo,$ls_estprosol,$ls_estprogpago);
					  }
				   else
					  {
					    $msg->message("El Cheque $ls_numdoc no puede ser eliminado, ya fue Conciliado");
						$lb_conciliado=true;
					  }
			     }			
		    }		
		if($li_entro>0)
		{		
			if($lb_valido)
			{
				$io_cheques->SQL->commit();	
				$msg->message("Proceso realizado correctamente !!!");
				$ls_operacion="NUEVO";
			}
			else
			{
				$io_cheques->SQL->rollback();
				$msg->message("Error en operación !!!");
				$ls_operacion="NUEVO";
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
		
		$i=1;
		$object[$i][1] = "<input type=checkbox name=chksel".$i."    id=chksel".$i." value=1>";		
		$object[$i][2] = "<input type=text name=txtnumdoc".$i."     value=''    class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";
		$object[$i][3] = "<input type=text name=txtconmov".$i."     value=''    class=sin-borde readonly style=text-align:left size=22 maxlength=22>";
		$object[$i][4] = "<input type=text name=txtmonto".$i."      value='".number_format(0,2,",",".")."'    class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
		$object[$i][5] = "<input type=text name=txtfecmov".$i."     value=''    class=sin-borde readonly style=text-align:center size=10 maxlength=10  >";
		$object[$i][6] = "<input type=text name=txtprov".$i."       value=''    class=sin-borde readonly style=text-align:center size=15 maxlength=15 >"; 
		$object[$i][7] = "<input type=text name=txtbene".$i."       value=''    class=sin-borde readonly style=text-align:center size=15 maxlength=22>";			
		$object[$i][8]  = "<input type=text name=txtnomproben".$i." value=''    class=sin-borde readonly style=text-align:left size=15 maxlength=22>";			
		$li_total=1;		
	}
	
	if($ls_operacion=="CAMBIO_TIPO")
	{
		$ld_fechadesde = $_POST["txtfechadesde"];
		$ld_fechahasta = $_POST["txtfechahasta"];
		$ls_documento  = $_POST["txtdocumento"];
		$rs_data       = $io_cheques->uf_cargar_cheques_filtrados($ls_empresa,$ls_tipo,$ld_fechadesde,$ld_fechahasta,$ls_documento,$lb_valido);		
		if ($lb_valido)
		   {
		     $li_numrows = $io_sql->num_rows($rs_data);
		     if ($li_numrows>0)
			    {
				  $li_i = 0;
				  while($row=$io_sql->fetch_row($rs_data))
			           {
				         $li_i++;
						 $ls_numdoc    = $row["numdoc"];
				         $ld_monmov    = $row["monto"];
				         $ld_fecmov    = $fun->uf_formatovalidofecha($row["fecmov"]);
						 $ld_fecmov    = $fun->uf_convertirfecmostrar($ld_fecmov);
				         $ls_codpro    = $row["cod_pro"];
				         $ls_cedben	   = $row["ced_bene"];				
				         $ls_nomproben = $row["nomproben"];
						 $ls_codban	   = $row["codban"];				
						 $ls_ctaban	   = $row["ctaban"];
						 $ls_conmov	   = $row["conmov"];
						 $ls_estcon    = $row["estcon"];
						 $ld_fecpropag = '';						
						 
						 $object[$li_i][1] = "<input type=checkbox name=chksel".$li_i."    id=chksel".$li_i."        value=1>";		
						 $object[$li_i][2] = "<input type=text name=txtnumdoc".$li_i."     id=txtnumdoc".$li_i."     value='".$ls_numdoc."'      					  class=sin-borde readonly style=text-align:center size=17 maxlength=15 ><input name=codban".$li_i." type=hidden id=codban".$li_i."  value='".$ls_codban."'> <input name=ctaban".$li_i."  type=hidden id=ctaban".$li_i."  value='".$ls_ctaban."'>";
						 $object[$li_i][3] = "<input type=text name=txtconmov".$li_i."     id=txtconmov".$li_i."     value='".$ls_conmov."'    						  class=sin-borde readonly style=text-align:left size=30 maxlength=22><input name=estcon".$li_i."  type=hidden id=estcon".$li_i."  value=".$ls_estcon.">";
						 $object[$li_i][4] = "<input type=text name=txtmonto".$li_i."      id=txtmonto".$li_i."      value='".number_format($ld_monmov,2,",",".")."'  class=sin-borde readonly style=text-align:right size=18 maxlength=22>";
						 $object[$li_i][5] = "<input type=text name=txtfecmov".$li_i."     id=txtfecmov".$li_i."     value='".$ld_fecmov."'    						  class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
						 $object[$li_i][6] = "<input type=text name=txtprov".$li_i."       id=txtprov".$li_i."       value='".$ls_codpro."'      					  class=sin-borde readonly style=text-align:center size=12 maxlength=15>"; 
						 $object[$li_i][7] = "<input type=text name=txtbene".$li_i."       id=txtbene".$li_i."       value='".$ls_cedben."'   	 					  class=sin-borde readonly style=text-align:center size=12 maxlength=22>";			
						 $object[$li_i][8] = "<input type=text name=txtnomproben".$li_i."  id=txtnomproben".$li_i."  value='".$ls_nomproben."'    					  class=sin-borde readonly style=text-align:left size=15 maxlength=22>";			
			           }
				    $li_total = $li_i;
				}
		     else
			    {
				  $li_i = 1;
				  $object[$li_i][1] = "<input type=checkbox name=chksel".$li_i."    id=chksel".$li_i." value=1>";		
				  $object[$li_i][2] = "<input type=text     name=txtnumdoc".$li_i."     value=''    class=sin-borde readonly style=text-align:center size=17 maxlength=15>";
				  $object[$li_i][3] = "<input type=text     name=txtconmov".$li_i."     value=''    class=sin-borde readonly style=text-align:left size=22 maxlength=22>";
				  $object[$li_i][4] = "<input type=text     name=txtmonto".$li_i."      value='".number_format(0,2,",",".")."'  class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
				  $object[$li_i][5] = "<input type=text     name=txtfecmov".$li_i."     value=''    class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
				  $object[$li_i][6] = "<input type=text     name=txtprov".$li_i."       value=''    class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
				  $object[$li_i][7] = "<input type=text     name=txtbene".$li_i."       value=''    class=sin-borde readonly style=text-align:center size=15 maxlength=22>";			
				  $object[$li_i][8] = "<input type=text     name=txtnomproben".$li_i."  value=''    class=sin-borde readonly style=text-align:left size=15 maxlength=22>";			
				  $li_total=1;
				}
		   }
	}	
		
 if ($ls_tipo=='P')
	{
	  $rb_prov = "checked";
	  $rb_bene = "";
	}
 elseif($ls_tipo=='B')
	{
	  $rb_prov="";
	  $rb_bene="checked";
	}
 else
	{
	  $rb_prov = "";
	  $rb_bene = "";
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
      <td height="22" colspan="6">Eliminaci&oacute;n de Cheques no Contabilizados</td>
    </tr>
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td><div align="right">Documento</div></td>
      <td colspan="3"><label>
        <input name="txtdocumento" type="text" id="txtdocumento" value="<?php print $ls_documento;?>" size="24" maxlength="15" onBlur="rellenar_cad(this.value,15,'doc')" style="text-align:center">
      </label></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td><div align="right">Desde</div></td>
      <td width="156"><label><input name="txtfechadesde" type="text" id="txtfechadesde"  style="text-align:center" value="<?php print $ld_fechadesde;?>" size="24" maxlength="10" onKeyPress="currencyDate(this);"   datepicker="true" ></label></td>
      <td width="54"><div align="right">Hasta</div></td>
      <td width="229"><div align="left">
        <input name="txtfechahasta" type="text" id="txtfechahasta"  style="text-align:center" value="<?php print $ld_fechahasta;?>" size="24" maxlength="10" onKeyPress="currencyDate(this);"   datepicker="true" >
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
        <input name="rb_provbene" type="radio" class="sin-borde" id="rb_provbene" value="P" <?php print $rb_prov;?>>
Proveedor</label>
        <label>
        <input name="rb_provbene" type="radio" class="sin-borde" id="rb_provbene" value="B" <?php print $rb_bene;?>>
Beneficiario</label></td>
      <td width="200">&nbsp;</td>
    </tr>
    <tr>
      <td height="13" colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13"><a href="javascript:uf_cambiar();" >Cargar Cheques</a></td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="6"><div align="center"><?php $io_grid->makegrid($li_total,$titleProg,$object,770,'Cheques no Contabilizados ',$gridProg);?>
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
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value ="NUEVO";
	f.action="sigesp_scb_p_elimin_chq.php";
	f.submit();
}

function ue_procesar()
{
	if(confirm("Está seguro de eliminar este(os) registro(s)?\n  Esta operación no puede reversarse"))
	{
		f=document.form1;
		f.operacion.value ="PROCESAR";
		f.action="sigesp_scb_p_elimin_chq.php";
		f.submit();
	}
}


    //Funciones de validacion de fecha.
	function rellenar_cad(cadena,longitud,campo)
	{
		if (cadena!="")
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
   
	function uf_cambiar()
	{
		f=document.form1;
		f.operacion.value="CAMBIO_TIPO";
		f.action="sigesp_scb_p_elimin_chq.php";
		f.submit();
	}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>