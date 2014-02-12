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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_progpago.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<title>Programaci&oacute;n de Pagos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/valida_fecha.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<table width="778" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
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
    <td width="665" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="22" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></td>
    <td colspan="3" class="toolbar"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<?php
	require_once("sigesp_c_cuentas_banco.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("../shared/class_folder/ddlb_conceptos.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");

	$io_msg       = new class_mensajes();	
	$fun          = new class_funciones();	
	$lb_guardar   = true;
	$io_grid      = new grid_param();
	$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
	$ls_casacon    = $_SESSION["la_empresa"]["casconmov"];//verifica si lo cocneptos estas casdaos a una cueta contable
	$io_include	= new sigesp_include();
    $ls_conect	= $io_include->uf_conectar();
	$obj_con	= new ddlb_conceptos($ls_conect);
	$li_estciespg = $io_fun_banco->uf_load_estatus_cierre($li_estciespi,$li_estciescg);
	$io_ctaban    = new sigesp_c_cuentas_banco();
	
	require_once("sigesp_scb_c_progpago.php");
	$io_propag=new sigesp_scb_c_progpago($la_seguridad);

	if (array_key_exists("chktipvia",$_POST))
	   {
		 $li_tipvia = $_POST["chktipvia"];	   
	   }
    else
	   {
	     $li_tipvia = 0;
	   }
	if ($li_tipvia=='0')
	   {
	     $ls_checked = '';
	   }
	else
	   {
	     $ls_checked = 'checked';
	   }

	if (array_key_exists("operacion",$_POST))//Cuando aplicamos alguna operacion 
	   {
	     $ls_operacion		 = $_POST["operacion"];
		 $ld_fecha           = $_POST["txtfecha"];
		 $ls_codban			 = $_POST["txtcodban"];
		 $ls_denban			 = $_POST["txtdenban"];
		 $ls_cuenta_banco    = $_POST["txtcuenta"];
		 $ls_dencuenta_banco = $_POST["txtdenominacion"];
		 if (array_key_exists("rb_provbene",$_POST))
		 {
		    $ls_tipproben = $_POST["rb_provbene"];	
	     }
		 else
		 {
			$ls_tipproben = '-';
		 }
		 if (array_key_exists("cmboperacion",$_POST))
		 {
			 $ls_mov_operacion=$_POST["cmboperacion"];
			 if($ls_mov_operacion=='ND')
			 {
				$lb_nd="selected";			
				$lb_ch="";
			 }
			 
			 if($ls_mov_operacion=='CH')
			 {
				$lb_nd="";			
				$lb_ch="selected";
			 }	
		}
		if($ls_operacion=="CAMBIO_OPERA")
		{
			$ls_opepre=0;	
			$ls_codconmov="---";
		}
		if(array_key_exists("opepre",$_POST))
		{			
			$ls_opepre=$_POST["opepre"];
		}		
		if(array_key_exists("ddlb_conceptos",$_POST))
		{			
			$ls_codigoconcepto=$_POST["ddlb_conceptos"]; 	
		}
	}
	else//Caso de apertura de la pagina o carga inicial
	{
		
		 $ls_mov_operacion="ND";
	     if($ls_mov_operacion=='ND')
		 {
			$lb_nd="selected";			
			$lb_ch="";
		 }
		 if($ls_mov_operacion=='CH')
		 {
			$lb_nd="";			
			$lb_ch="selected";
		}	
		
		$ls_operacion= "NUEVO" ;
		$ls_mov_operacion="NC";
	    $ls_seleccionado="";
		$ls_codban="";
		$ls_denban="";
		$ls_cuenta_banco="";
		$ls_dencuenta_banco="";	
		$ls_tipproben='-';
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		$ld_fecha=$fun->uf_cerosizquierda($ls_dia,2)."/".$fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
		$ldec_total_prog = 0;
	}

	$ls_disable = "";
	if ($li_estciescg==1)
	   {
	     $ls_disable = "disabled";		 
	   }
	
	if (($li_estciespg==1 || $li_estciespi==1) && $li_estciescg==0 && $ls_operacion=="NUEVO")
	   {
	     $io_msg->message("Ya fué procesado el Cierre Presupuestario, sólo serán cargadas Solicitudes de Pago asociadas a Recepciones de Documentos netamente Contables !!!");	   
	   }
    elseif($li_estciespg==1 && $li_estciespi==1 && $li_estciescg==1 && $ls_operacion=="NUEVO")
       {
         $io_msg->message("Ya fué procesado el Cierre Contable, No pueden efectuarse movimientos, Contacte al Administrador del Sistema !!!");
       }
	
	if($ls_tipproben=='P')
	{
		$rb_prov	 = "checked";
		$rb_bene	 = "";
	    $ls_disabled = "disabled";
	}
	elseif($ls_tipproben=='B')
	{
		$rb_prov	 = "";
		$rb_bene     = "checked";
	    $ls_disabled = "";
	}
	else
	{
		$rb_prov	 = "";
		$rb_bene	 = "";
	    $ls_disabled = "disabled";
	}
	//Declaración de parametros del grid.
	$titleProg[1]="<input name=chksel type=checkbox id=chksel value=T style=height:15px;width:15px onClick=javascript:uf_select_all(); >";   
	$titleProg[2]="Solicitud";     
	$titleProg[3]="Monto";     
	$titleProg[4]="Saldo";     
	$titleProg[5]="Fecha";  	
	$titleProg[6]="Proveedor\Beneficiario";  
	$titleProg[7]="Fecha Prog.";   
    $gridProg="grid_prog";
	
if ($ls_operacion=="NUEVO")
   {
	 
	  $ls_mov_operacion="ND";
	  if($ls_mov_operacion=='ND')
	  {
		$lb_nd="selected";			
		$lb_ch="";
	  }
	  if($ls_mov_operacion=='CH')
	  {
		$lb_nd="";			
		$lb_ch="selected";
	  }	
	 $ls_codconmov="---";	
	 $rb_prov = "";
	 $rb_bene = "";
	 $ls_codban="";
	 $ls_denban="";
	 $ls_cuenta_banco="";
	 $ls_dencuenta_banco="";
	 $ls_tipproben='-';
	 $ldec_total_prog = 0;
	 $array_fecha=getdate();
	 $ls_dia   = $array_fecha["mday"];
	 $ls_mes   = $array_fecha["mon"];
	 $ls_ano   = $array_fecha["year"];
	 $ld_fecha = $fun->uf_cerosizquierda($ls_dia,2)."/".$fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
     uf_clear_grid(&$li_totsolpag,&$object);
   }
   
if ($ls_operacion=="GUARDAR")
   {
	 $li_totsolpag = $_POST["totsol"];
	 $lb_valido    = true;
	 $io_dssolvia  = new class_datastore();
	 $io_propag->SQL->begin_transaction();
	 for ($i=0;($i<=$li_totsolpag)&&($lb_valido);$i++)
	     {
		   if (array_key_exists("chksel".$i,$_POST))
		      {
			    $ls_numsol    = $_POST["txtnumsol".$i];
				$ldec_monto   = $_POST["txtmonsol".$i];
				$li_saldo     = $_POST["txtsaldo".$i];
				$ls_codban    = $_POST["txtcodban"];
				$ls_ctaban    = $_POST["txtcuenta"];
				$ls_provbene  = $_POST["txtcodproben".$i];
				$ls_tipproben = $_POST["rb_provbene"];
				$ld_fecpropag = $_POST["txtfecprog".$i];
				if ($ld_fecpropag!="")	
				   {
					 $ls_estmov='P';				
					 $lb_valido=$io_propag->uf_procesar_programacion($ls_numsol,$ld_fecpropag,$ls_estmov,$ls_codban,$ls_ctaban,$ls_provbene,$ls_tipproben,$li_tipvia);
				   }
				else
				   {
					 $io_msg->message("Seleccione la, solicitud a programar y asigne la fecha de programación");			
				   }
		      }			
	     }		
	if ($lb_valido)
	   {
		 $io_propag->SQL->commit();	
		 $io_msg->message("El movimiento fue registrado");
	   }
	else
	   {
		 $io_propag->SQL->rollback();
		 $io_msg->message($io_propag->is_msg_error);
	   }
   }

if (($ls_operacion=="CAMBIO_TIPO")||($ls_operacion=="GUARDAR")||($ls_operacion=="CAMBIO_OPERA"))
{     
	if(array_key_exists("opepre",$_POST))
	{			
		$ls_opepre=$_POST["opepre"];
	}
	if(array_key_exists("ddlb_conceptos",$_POST))
	{			
		$ls_codigoconcepto=$_POST["ddlb_conceptos"]; 	
	}
	
	if(array_key_exists("codconmov",$_POST))
	{			
		$ls_codconmov=$_POST["codconmov"]; 	
	}
	 //Cargo los datos de las programaciones.
	 $ldec_total_prog = $_POST["txttotalprog"];
	 $rs_solpag = $io_propag->uf_cargar_solicitudes($ls_codemp,$ls_tipproben,$li_tipvia);
     $li_totsolpag = $io_propag->SQL->num_rows($rs_solpag);
	 if ($li_totsolpag>0)
	 {
		  $li_i = 0;
		  while(!$rs_solpag->EOF)
	      {
			     $ld_montotsal = $ld_montotnot = 0;
			     $ls_numsol    = $rs_solpag->fields["numsol"];
				 $ls_codproben = $rs_solpag->fields["codproben"];
				 $li_estcodtipdoc=$rs_solpag->fields["estcodtipdoc"];
				 $li_estcon=substr($li_estcodtipdoc,0,1);
				 $li_estpre=substr($li_estcodtipdoc,1,1);
				 if($li_estpre!=3&&$li_estpre!=4)// Si el documento aplica imputacion presupuestaria verifico si el usuario tiene asignada
				 {								 // las estructura para filtrar solo las estructuras disponibles para el usuario.
				 	$lb_valido=$io_propag->uf_validar_asignacion_estructura($ls_numsol,$ls_codproben,$ls_tipproben);
				 }
				 else
				 {
				 	$lb_valido=true;
				 }				 
				 if($lb_valido)		
				 {		
					 $ld_monsolpag = $rs_solpag->fields["monsol"];
					 $ls_fecpagsol = $rs_solpag->fields["fecpagsol"];
					 $io_propag->uf_load_notas_asociadas($ls_codemp,$ls_numsol,$ld_montotnot);
					 $ld_montotsal = $ld_monsolpag+$ld_montotnot;
					 $ls_fecemisol = $fun->uf_convertirfecmostrar($rs_solpag->fields["fecemisol"]);
					 $ls_codproben = trim($rs_solpag->fields["codproben"]);
					 if ($ls_tipproben=='P')
					 {
						  $ls_nomproben = $rs_solpag->fields["nomproben"];
					 }
					 elseif($ls_tipproben=='B')
					 {
						  $ls_nomproben = $rs_solpag->fields["nombene"];
						  $ls_apeben    = $rs_solpag->fields["apebene"];
						  if (!empty($ls_apebene))
						  {
									$ls_nomproben = $ls_nomproben.', '.$ls_apeben;
						  } 								   
					 }
					 $ls_procede = $rs_solpag->fields["procede"];
					 $ls_valor=$io_propag->uf_buscar_detalles_pre($ls_codemp,$ls_numsol, $ls_tipproben);				  
					 if (($li_estciespg==1 || $li_estciespi==1) && ($ls_valor==0 && $li_estciescg==0) || 
						($li_estciespg==0 && $li_estciespi==0 && $li_estciescg==0))
					 {
						  $li_i++;
						  $ls_numordpagmin = $rs_solpag->fields["numordpagmin"];
						  $ls_codtipfon    = $rs_solpag->fields["codtipfon"];
						  if (!empty($ls_numordpagmin) && !empty($ls_codtipfon) && $ls_codtipfon!='----' && $ls_numordpagmin!='-')
						  {
							   $ls_banco  = $io_propag->uf_load_datos_orden_pago($ls_numordpagmin,$ls_codtipfon,$ls_cuenta,$ls_nomban,$ls_dencta);
							   $io_ctaban->uf_verificar_saldo($ls_banco,$ls_cuenta,&$ld_totmondis);
						  }
						  else
						  {
							   $ls_cuenta = $ls_banco = $ls_nomban = $ls_dencta = $ld_totmondis = "";
							   $ld_totmondis = "0,00";
						  }
						  $object[$li_i][1] = "<input type=checkbox name=chksel".$li_i."       id=chksel".$li_i."       value=1 onClick=javascript:uf_registrar($li_i,'$ls_numsol','$ld_montotsal','$ls_fecemisol','$ls_codproben',this);>";		
						  $object[$li_i][2] = "<input type=text     name=txtnumsol".$li_i."    id=txtnumsol".$li_i."    value='".$ls_numsol."'                             class=sin-borde readonly style=text-align:center size=15 maxlength=15 onClick=javascript:uf_registrar($li_i,'$ls_numsol','$ld_montotsal','$ls_fecemisol','$ls_codproben',this);><input type=hidden name=hidcodban".$li_i." value='".$ls_banco."';><input type=hidden name=hidctaban".$li_i." value='".$ls_cuenta."';>";
						  $object[$li_i][3] = "<input type=text     name=txtmonsol".$li_i."    id=txtmonsol".$li_i."    value='".number_format($ld_monsolpag,2,",",".")."' class=sin-borde readonly style=text-align:right  size=18 maxlength=18 onClick=javascript:uf_registrar($li_i,'$ls_numsol','$ld_montotsal','$ls_fecemisol','$ls_codproben',this);><input type=hidden name=hidnomban".$li_i." value='".$ls_nomban."';><input type=hidden name=hiddenctaban".$li_i." value='".$ls_dencta."';>";
						  $object[$li_i][4] = "<input type=text     name=txtsaldo".$li_i."     id=txtsaldo".$li_i."     value='".number_format($ld_montotsal,2,",",".")."' class=sin-borde readonly style=text-align:right  size=18 maxlength=18 onClick=javascript:uf_registrar($li_i,'$ls_numsol','$ld_montotsal','$ls_fecemisol','$ls_codproben',this);><input type=hidden name=hidmondis".$li_i." value='".number_format($ld_totmondis,2,',','.')."';>";
						  $object[$li_i][5] = "<input type=text     name=txtfecsol".$li_i."    id=txtfecsol".$li_i."    value='".$ls_fecemisol."'                          class=sin-borde readonly style=text-align:center size=10 maxlength=10 onClick=javascript:uf_registrar($li_i,'$ls_numsol','$ld_montotsal','$ls_fecemisol','$ls_codproben',this);>"; 
						  $object[$li_i][6] = "<input type=hidden   name=txtcodproben".$li_i." id=txtcodproben".$li_i." value='".$ls_codproben."'><input type=text name=txtnomprovbene".$li_i." value='".$ls_nomproben."' title='".$ls_nomproben."'    class=sin-borde readonly style=text-align:left size=60 maxlength=60 onClick=javascript:uf_registrar($li_i,'$ls_numsol','$ld_montotsal','$ls_fecemisol','$ls_codproben',this);>";			
						  $object[$li_i][7] = "<input type=text     name=txtfecprog".$li_i."   id=txtfecprog".$li_i."   value=''  class=sin-borde readonly style=text-align:center size=10 maxlength=10 onClick=javascript:uf_registrar($li_i,'$ls_numsol','$ld_montotsal','$ls_fecemisol','$ls_codproben',this);><input type=hidden name=hidprocede".$li_i." value='".$ls_procede."' class=sin-borde readonly style=text-align:left size=10 maxlength=6;>";
					 }//fin del if ($ls_valor==0 && $li_estciescg==0)																  
			  	}
				$rs_solpag->MoveNext();	
		  }
		  $li_totsolpag = $li_i;
		  if ($li_i==0)
		  {
			   uf_clear_grid(&$li_totsolpag,&$object);
		  }    
	 }
	 else
	 {
          uf_clear_grid(&$li_totsolpag,&$object);
	 }
}

function uf_clear_grid(&$li_totsolpag,&$object)
{
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  // Funcion     = uf_clear_grid.
  // Descripcion = Método que permite imprimir el grid en blanco cuando no haya registros para mostrar.
  // Creación    = 10/09/2008.
  // Creado Por  = Ing. Néstor Falcón.
  ///////////////////////////////////////////////////////////////////////////////////////////////////////
  global $ls_disabled,$ldec_total_prog;
   
  $li_totsolpag = $i = 1;
  $ldec_total_prog = 0;
  $object[1][1] = "<input type=checkbox name=chksel".$i."   id=chksel".$i."       value=1  $ls_disabled>";		
  $object[1][2] = "<input type=text name=txtnumsol".$i."    id=txtnumsol".$i."    value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15><input type=hidden name=hidcodban".$i." value='';><input type=hidden name=hidctaban".$i." value='';>";
  $object[1][3] = "<input type=text name=txtmonsol".$i."    id=txtmonsol".$i."    value='".number_format(0,2,",",".")."'  class=sin-borde readonly style=text-align:right size=18 maxlength=18><input type=hidden name=hidnomban".$i." value='';><input type=hidden name=hiddenctaban".$i." value='';>";
  $object[1][4] = "<input type=text name=txtsaldo".$i."     id=txtsaldo".$i."     value='".number_format(0,2,",",".")."'  class=sin-borde readonly style=text-align:right size=18 maxlength=18>";
  $object[1][5] = "<input type=text name=txtfecsol".$i."    id=txtfecsol".$i."    value=''  class=sin-borde readonly style=text-align:center size=10 maxlength=10>"; 
  $object[1][6] = "<input type=text name=txtcodproben".$i." id=txtcodproben".$i." value=''  class=sin-borde readonly style=text-align:center size=60 maxlength=60>";			
  $object[1][7] = "<input type=text name=txtfecprog".$i."   id=txtfecprog".$i."   value=''  class=sin-borde readonly style=text-align:center size=10 maxlength=10><input type=hidden name=hidprocede".$i." value='' class=sin-borde readonly style=text-align:left size=10 maxlength=6;>";
}
?>
<form name="form1" method="post" action="" id="sigesp_scb_p_progpago.php">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <br>
  <table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco" id="tabla">
    <tr class="titulo-ventana">
      <td height="22" colspan="4"><input name="hidmesabi" type="hidden" id="hidmesabi" value="true">
        Programaci&oacute;n de Pagos 
      <input name="operacion" type="hidden" id="operacion">
      <input name="hidestciescg" type="hidden" id="hidestciescg" value="<?php echo $li_estciescg; ?>">
      <input name="hidestciespi" type="hidden" id="hidestciespi" value="<?php echo $li_estciespi; ?>">
      <input name="hidestciespg" type="hidden" id="hidestciespg" value="<?php echo $li_estciespg; ?>">
	  <input name="hidcasacon" type="hidden" id="hidcasacon" value="<?php echo $ls_casacon; ?>">
	  </td>
    </tr>
    <tr>
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22" colspan="2"><p>
          <label>
          <input name="rb_provbene" type="radio" class="sin-borde" id="rb_provbene" onClick="javascript:uf_cambiar();" value="P" <?php print $rb_prov;echo $ls_disable; ?>>
            Proveedor</label>
          <label>
          <input name="rb_provbene" type="radio" class="sin-borde" id="rb_provbene" onClick="javascript:uf_cambiar();" value="B" <?php print $rb_bene;echo $ls_disable; ?>>
            Beneficiario</label>
          <br>
      </p></td>
      <td width="205" height="22">Tipo Vi&aacute;tico
        <label>
          <input name="chktipvia" type="checkbox" class="sin-borde" id="chktipvia" value="1" <?php print $ls_checked; ?><?php print $ls_disabled ?> onClick="javascript:uf_cambiar();">
        </label></td>
    </tr>
    <tr>
	  <? if ($ls_casacon==1)
	   { ?>
      <td height="22" style="text-align:right">Operaci&oacute;n</td>
      <td height="22" colspan="2"><select name="cmboperacion" id="select" onChange="javascript:uf_verificar_operacion();" style="width:120px">
        <option value="ND" <?php print $lb_nd;?>>Nota de D&eacute;bito</option>       
        <option value="CH" <?php print $lb_ch;?>>Cheque</option>
      </select> <input name="opepre" type="hidden" id="opepre" value="<?php print $ls_opepre;?>"></td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
       <td width="86" height="22" style="text-align:right">Tipo  Concepto</td>
       <td height="22" colspan="2"><?php $obj_con->uf_cargar_conceptos($ls_mov_operacion,$ls_codconmov);	?>
          <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>"></td>
      <td height="22">&nbsp;</td>
    </tr>
    <? } ?>
    <tr>
      <td width="86" height="22" style="text-align:right">Banco</td>
      <td height="22" colspan="3"><input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>        <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
      <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="70" class="sin-borde" readonly></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Cuenta</td>
      <td height="22" colspan="3"><input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_cuenta_banco; ?>" size="30" maxlength="25" readonly>
        <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
        <input name="txtdenominacion"  type="text"   class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_dencuenta_banco; ?>" size="50" maxlength="254" readonly>
        <input name="txttipocuenta"    type="hidden" id="txttipocuenta">
        <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">
      <input name="txtcuenta_scg"    type="hidden" id="txtcuenta_scg"></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Disponible</td>
      <td height="22" style="text-align:left"><input name="txtdisponible" type="text" id="txtdisponible" style="text-align:right" size="22" readonly></td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr class="formato-azul">
      <td height="22" colspan="4" style="text-align:center"><strong>Detalles Solicitudes</strong></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Solicitud</td>
      <td width="264" height="22" style="text-align:left"><input name="txtnumsol" type="text" id="txtnumsol" style="text-align:center" size="22" readonly></td>
      <td width="205" height="22" style="text-align:right">Fecha</td>
      <td height="22" style="text-align:left"><input name="txtfecha" type="text" id="txtfecha" style="text-align:center" size="22" readonly></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Prov/Bene</td>
      <td height="22" style="text-align:left"><input name="txtprovbene" type="text" id="txtprovbene" style="text-align:center" size="22" readonly></td>
      <td height="22" style="text-align:right">Fecha Programada</td>
      <td height="22"> <input name="txtfecpropag" type="text" id="txtfecpropag" size="22" maxlength="10" style="text-align:center" datepicker="true" <?php echo $ls_disable; ?> onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"></td>
    </tr>
      <script language="javascript">uf_validar_estatus_mes();</script>
	<tr>
      <td height="22" style="text-align:right">Monto</td>
      <td height="22" style="text-align:left"><input name="txtmonto" type="text" id="txtmonto" style="text-align:right" size="22" readonly></td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;&nbsp;</td>
      <td height="22"><a href="javascript:uf_procesar();"><img src="../shared/imagebank/aprobado.gif" alt="Aceptar" title="Aceptar" width="15" height="15" border="0">Procesar Programaci&oacute;n</a> <img src="../shared/imagebank/mas.gif" alt="Aplicar a Todas" width="9" height="17"><a href="javascript:uf_aplicar_all();">Aplicar Fecha a Todas</a> </td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>                                              
      <td height="22" colspan="4"><div align="center"><?php $io_grid->make_gridScroll($li_totsolpag,$titleProg,$object,800,'Solicitudes a Programar ',$gridProg,370);?>
          <input name="totsol"  type="hidden" id="totsol"  value="<?php print $li_totsolpag; ?>">
          <input name="fila"    type="hidden" id="fila">
      </div></td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22" style="text-align:right"><strong>Total Programaci&oacute;n</strong></td>
      <td height="22" style="text-align:left"><input name="txttotalprog" type="text" id="txttotalprog" style="text-align:right" value="<?php print number_format($ldec_total_prog,2,",",".");?>" readonly></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
<script language="javascript">
f=document.form1;
var patron = new Array(2,2,4);
function ue_nuevo()
{
  if (uf_evaluate_cierre('SCG'))
     {
       f.operacion.value ="NUEVO";
       f.action="sigesp_scb_p_progpago.php";
       f.submit();	 
	 }
}

function ue_guardar()
{
lb_mesabi = f.hidmesabi.value;
if (lb_mesabi=='true')
   {
     if (uf_evaluate_cierre('SCG'))
		{
		   ls_codban  = f.txtcodban.value;
		   ls_cuenta  = f.txtcuenta.value;
		   ldec_monto = f.txttotalprog.value;
		   while(ldec_monto.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
				  ldec_monto=ldec_monto.replace(".","");
				}
		   ldec_monto=ldec_monto.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
		   if ((ldec_monto!="")&&(ldec_monto>0))
			  {
				if ((ls_codban!="")&&(ls_cuenta!=""))
				   {
					 f.operacion.value ="GUARDAR";
					 f.action="sigesp_scb_p_progpago.php";
					 f.submit();
				   }
				else
				   {
					 alert("Seleccione el banco y la cuenta");
				   }
			  }
		   else
			  {
				alert("Monto Programado debe ser mayor a 0");
			  }	 
		 }
   }
else
   {
     alert("Operación No puede ser procesada, El Més está Cerrado !!!");
   }
}
	
function catalogo_cuentabanco()
{
  uf_validar_estatus_mes();
  if (f.hidcasacon.value==1)
  {
  	valor=f.ddlb_conceptos.value; 
  }
  else
  {
  	valor="";
  }
  if (uf_evaluate_cierre('SCG'))
     {
	   ls_codban=f.txtcodban.value;
	   ls_nomban=f.txtdenban.value;
	   if ((ls_codban!=""))
		  {
		    pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_nomban+"&codcon="+valor;
		    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no");
		  }
	   else
		  {
		    alert("Seleccione el Banco !!!");
		  }
     }
}
	 
function cat_bancos()
{
  uf_validar_estatus_mes();
  if (f.hidcasacon.value==1)
  {
  	valor=f.ddlb_conceptos.value; 
  }
  else
  {
  	valor="";
  }
  if (uf_evaluate_cierre('SCG'))
     {
       pagina="sigesp_cat_bancos.php?codcon="+valor;
       window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
}

function uf_registrar(fila,ls_numsol,ldec_monto,ld_fecsol,ls_provbene,obj)
{
        if((obj.name!=('chksel'+fila)))
		{
			f.txtnumsol.value=ls_numsol;
			f.txtmonto.value =uf_convertir(ldec_monto);
			f.txtprovbene.value=ls_provbene;
			f.txtfecha.value = ld_fecsol;
			f.fila.value=fila;
			eval("f.chksel"+fila+".checked=false");
		}
		else
		{
		  if (eval("f.chksel"+fila+".checked"))
			 {
			   ls_numban = f.txtcodban.value;
			   ls_numcta = f.txtcuenta.value;
			   if (ls_numban=="" && ls_numcta=="")
			      {
				    ls_codban = eval("f.hidcodban"+fila+".value");
				    ls_ctaban = eval("f.hidctaban"+fila+".value");
					ld_mondis = eval("f.hidmondis"+fila+".value");
					if (ls_codban!='' && ls_ctaban!='')
					   {
						 ls_nomban    = eval("f.hidnomban"+fila+".value");
						 ls_denctaban = eval("f.hiddenctaban"+fila+".value");

					     f.txtcodban.value = ls_codban;
			             f.txtcuenta.value = ls_ctaban;	
						 f.txtdenban.value = ls_nomban;
			             f.txtdenominacion.value = ls_denctaban;
						 f.txtdisponible.value   = ld_mondis;	
					   }
				  }
			   else
			      {
				    ls_codban = eval("f.hidcodban"+fila+".value");
				    ls_ctaban = eval("f.hidctaban"+fila+".value");
					if ((ls_codban!=ls_numban || ls_ctaban!=ls_numcta) && ls_codban!="" && ls_ctaban!="")
					   {
					     eval("f.chksel"+fila+".checked=false");
					     alert("La Solicitud está asociada a una Orden de Pago Ministerio, Emitida por Banco o Cuenta distinta a la seleccionada !!!");
					   }
				  }
			   f.txtnumsol.value   = ls_numsol;
			   f.txtmonto.value    = uf_convertir(ldec_monto);
			   f.txtprovbene.value = ls_provbene;
			   f.txtfecha.value    = ld_fecsol;
			   uf_validar_estatus_mes();
			   f.fila.value        = fila;
			 }
		  else
			 {
			   f.txtnumsol.value="";
			   f.txtprovbene.value="";
			   f.txtfecha.value = "";
			   f.fila.value=0;
			   f.txtmonto.value =uf_convertir(0);
			 }
		}
		uf_calcular_total();
   }
   
function uf_calcular_total()
{
	ldec_total=0;
	li_total=f.totsol.value;
	for(i=1;i<=li_total;i++)
	{
		if(eval("f.chksel"+i+".checked"))
		{				
			ldec_monto=eval("f.txtmonsol"+i+".value");
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ldec_monto=ldec_monto.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
			ldec_total=parseFloat(ldec_monto) + parseFloat(ldec_total);
		}
	}	
	f.txttotalprog.value=uf_convertir(ldec_total);
}

function fill_cad(cadena,longitud)
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
   return cadena;
}
   
function uf_procesar()
{
    uf_validar_estatus_mes();
	lb_mesabi = f.hidmesabi.value;
	if (lb_mesabi=='true')
	   {
		li_totsol=f.totsol.value;
		fila=f.fila.value;
		ldec_monto=f.txtmonto.value;
		ls_numsol=f.txtnumsol.value;
		ls_provbene=f.txtprovbene.value;
		ls_fecha=f.txtfecha.value;
		ld_fecprog=f.txtfecpropag.value;
		li_totsol=f.totsol.value;
		if(ls_numsol!="")
		{
				
			lb_valido=uf_verificar_fechas(ls_fecha,ld_fecprog);
			if(!lb_valido)
			{
				alert("Fecha de programación debe ser mayor a la de la solicitud");
				return;
			}
			else
			{
				var ld_fecnow=new Date();
				ld_fec=fill_cad(ld_fecnow.getDate(),2)+"/"+fill_cad((ld_fecnow.getMonth()+1),2)+"/"+ld_fecnow.getFullYear();
				lb_valido=uf_verificar_fechas(ld_fec,ld_fecprog);
			}
			if((ldec_monto!="")&&(ls_numsol!="")&&(ls_fecha!="")&&(ls_provbene!=""))
			{
					eval("f.chksel"+fila+".checked=true");
					eval("f.txtfecprog"+fila+".value='"+ld_fecprog+"'");
					f.txtmonto.value="";
					f.txtnumsol.value="";
					f.txtfecha.value="";
					f.txtprovbene.value="";
	
			}
			else
			{
				alert("Complete o seleccione los datos de la solicitud a programar");
			}
			uf_calcular_total();
		}		
       }
	else
	   {
		 alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	   }
}

function uf_aplicar_all()
{	
	uf_validar_estatus_mes();
	li_totsol=f.totsol.value;
	ld_fecprog=f.txtfecpropag.value;
	ls_fecha=f.txtfecha.value;
	li_totsol=f.totsol.value;
	lb_valido=uf_verificar_fechas(ls_fecha,ld_fecprog);
	if(!lb_valido)
	{
		alert("Fecha de programación debe ser mayor a la de la solicitud");
		return;
	}
	else
	{
		var ld_fecnow=new Date();
		ld_fec=fill_cad(ld_fecnow.getDate(),2)+"/"+fill_cad((ld_fecnow.getMonth()+1),2)+"/"+ld_fecnow.getFullYear();
		lb_valido=uf_verificar_fechas(ld_fec,ld_fecprog);
	}
	for(li_i=1;li_i<=li_totsol;li_i++)
	{
		if(eval("f.chksel"+li_i+".checked==true"))
		{
			eval("f.txtfecprog"+li_i+".value='"+ld_fecprog+"'");
		}
	}
	uf_calcular_total();		  
}

function uf_verificar_fechas(ld_fec1,ld_fec2)
{
	ls_dia=ld_fec1.substr(0,2);
	li_dia1 =parseInt(ls_dia,10);
	ls_mes=ld_fec1.substr(3,2);
	li_mes1 =parseInt(ls_mes,10);
	ls_agno=ld_fec1.substr(6,4);
	li_agno1=parseInt(ls_agno,10);
	ls_dia  =ld_fec2.substr(0,2);
	li_dia2 =parseInt(ls_dia,10);
	ls_mes  =ld_fec2.substr(3,2);
	li_mes2 =parseInt(ls_mes,10);
	ls_agno=ld_fec2.substr(6,4);
	li_agno2=parseInt(ls_agno,10);

   if(li_agno2>=li_agno1)
   {
		if(li_mes2>li_mes1)
		{
			return true;
		}
		else if(li_mes2==li_mes1)
		{
			if(li_dia2>=li_dia1)
			{
				return true;
			}
			else if((li_dia2<li_dia1)&&(li_agno2>li_agno1))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else if((li_mes2<li_mes1)&&(li_agno2>li_agno1))
		{	
			return true;
		}
		else
		{
			return false;
		}   		
   }
   else
   {
		return false;
   }

}

function uf_cambiar()
{
	if (f.rb_provbene[1].selected==true)
	   {
		 f.chktipvia.readOnly=false;
	   }
	else
	   {
		 f.chktipvia.readOnly=true;
	   }	
	f.operacion.value="CAMBIO_TIPO";
	f.action="sigesp_scb_p_progpago.php";
	f.submit();
}

function uf_evaluate_cierre(as_tipafe)
{
  lb_valido = true;
  if (as_tipafe=='SPG' || as_tipafe=='SPI')
     {
       li_estciespg = f.hidestciespg.value;
       li_estciespi = f.hidestciespi.value;
	   if (li_estciespg==1 || li_estciespi==1)
		  {
		    lb_valido = false;
		    alert("Ya fué procesado el Cierre Presupuestario, No pueden efectuarse movimientos, Contacte al Administrador del Sistema !!!");
		  }	   
	 }
  else
     {
	   if (as_tipafe=='SCG')
	      {
  		    li_estciescg = f.hidestciescg.value;
			if (li_estciescg==1)
			   {
			     lb_valido = false;
			     alert("Ya fué procesado el Cierre Contable, No pueden efectuarse movimientos, Contacte al Administrador del Sistema !!!");
			   }
		  }
	 }
  return lb_valido
}	

function uf_verificar_operacion()
{	
	f.txtcodban.value="";
	f.txtdenban.value="";
	f.txtcuenta.value="";
	f.txtdenominacion.value="";
	f.operacion.value="CAMBIO_OPERA";
	f.opepre.value=f.cmboperacion.value;
	f.submit();
}

function uf_select_all()
{
	  total=ue_calcular_total_fila_local("txtnumsol");
	  sel_all=f.chksel.value;
	  li_sel=0;
	  li_row=0;	  
	  if(sel_all=='T')
	  {
		  for(i=1;i<=total&&li_sel<=50;i++)	
		  {
			eval("f.chksel"+i+".checked=true")
			li_sel=li_sel+1;
		  }		  
	   }
} 

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>