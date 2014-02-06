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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_desprogpago.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<title>Desprogramaci&oacute;n de Pagos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
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
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="780" height="40"></td>
  </tr>
  <tr>
  <td height="20" colspan="12" bgcolor="#E7E7E7">
    <table width="780" border="0" align="center" cellpadding="0" cellspacing="0">			
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
  </tr>
  <tr>
    <td height="20" width="22"  class="toolbar"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></td>
    <td height="20" width="22"  class="toolbar"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></td>
    <td height="20" width="22"  class="toolbar"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></td>
    <td height="20" width="673" class="toolbar"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<?php
require_once("sigesp_scb_c_desprogpago.php");
require_once("../shared/class_folder/grid_param.php");	
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
    
$io_msg		= new class_mensajes();	
$io_funcion	= new class_funciones();	
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_grid	= new grid_param();
$io_despag  = new sigesp_scb_c_desprogpago($la_seguridad);
$li_estciespg = $io_fun_banco->uf_load_estatus_cierre($li_estciespi,$li_estciescg);

if (array_key_exists("operacion",$_POST))//Cuando aplicamos alguna operacion 
   {
     $ls_operacion = $_POST["operacion"];
	 if (array_key_exists("rb_provbene",$_POST))
	    {
		  $ls_tipproben = $_POST["rb_provbene"];
		}	 
     else
	    { 
	      $ls_tipproben = '-';
	    }
   }
else
   {
     $ls_tipproben = '-';
	 $ls_operacion = "NUEVO" ;
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
if ($ls_tipproben=='P')
   {
     $rb_prov = "checked";
	 $rb_bene = "";
   }
elseif($ls_tipproben=='B')
   {
	 $rb_prov = "";
     $rb_bene = "checked";
   }
else
   {
	 $rb_prov = "";
     $rb_bene = "";
   }

//Encabezado del Grid.
$titleProg[1]="";
$titleProg[2]="Solicitud";
$titleProg[3]="Monto";
$titleProg[4]="Prov/Benef.";
$titleProg[5]="Fecha Prog.";
$titleProg[6]="Banco";
$titleProg[7]="Cuenta Banco";   
$gridProg="grid_prog";

if (($ls_operacion=="CAMBIO_TIPO")||($ls_operacion=="GUARDAR"))
   {
	 //Cargo los datos de las programaciones.
	 $rs_solpag = $io_despag->uf_cargar_programaciones($_SESSION["la_empresa"]["codemp"],$ls_tipproben);		
     $li_totsolpag = $io_despag->SQL->num_rows($rs_solpag);
	 if ($li_totsolpag>0)
	    {
		  $li_i = 0;
		  while(!$rs_solpag->EOF)
			   {
			     $li_detspg = $rs_solpag->fields["detspg"];
				 $ls_numsol    = $rs_solpag->fields["numsol"];
			     $ls_codproben = $rs_solpag->fields["codproben"];
			  	 $li_estcodtipdoc=$rs_solpag->fields["estcodtipdoc"];
				 $li_estcon=substr($li_estcodtipdoc,0,1);
				 $li_estpre=substr($li_estcodtipdoc,1,1);
			 
				  if($li_estpre!='3'&&$li_estpre!='4')// Si el documento aplica imputacion presupuestaria verifico si el usuario tiene asignada
				 {								 // las estructura para filtrar solo las estructuras disponibles para el usuario.
				 	
					$lb_valido=$io_despag->uf_validar_asignacion_estructura($ls_numsol,$ls_codproben,$ls_tipproben);
				 }
				 else
				 {
				 	$lb_valido=true;
				 }	
				 if($lb_valido)
				 {
					 if (($li_estciespg==1 || $li_estciespi==1) && ($li_detspg==0 && $li_estciescg==0) || 
						 ($li_estciespg==0 && $li_estciespi==0 && $li_estciescg==0))
					 {
						   $li_i++;
						   $ld_monsolpag = $rs_solpag->fields["monsol"];
						   if ($ls_tipproben=='P')
							  {
								$ls_nomproben = $rs_solpag->fields["nomproben"];
							  }
						   elseif($ls_tipproben=='B')
							  {
								$ls_nomproben = $rs_solpag->fields["nombene"];
								$ls_apeben    = $rs_solpag->fields["apebene"];
								if (!empty($ls_apeben))
								   {
									 $ls_nomproben = $ls_nomproben.', '.$ls_apeben;
								   }
							  }
						   $ld_fecpropag = $io_funcion->uf_convertirfecmostrar($rs_solpag->fields["fecpropag"]);
						   $ls_codban    = $rs_solpag->fields["codban"];
						   $ls_nomban    = $rs_solpag->fields["nomban"];
						   $ls_ctaban    = $rs_solpag->fields["ctaban"];
						   $ls_denctaban = $rs_solpag->fields["dencta"];					   
						   $object[$li_i][1] = "<input type=checkbox name=chksel".$li_i."       id=chksel".$li_i."       value=1>";
						   $object[$li_i][2] = "<input type=text     name=txtnumsol".$li_i."    id=txtnumsol".$li_i."    value='".$ls_numsol."' class=sin-borde readonly style=text-align:center size=17 maxlength=15>";
						   $object[$li_i][3] = "<input type=text     name=txtmonsol".$li_i."    id=txtmonsol".$li_i."    value='".number_format($ld_monsolpag,2,",",".")."' class=sin-borde readonly style=text-align:right size=15 maxlength=22>";
						   $object[$li_i][4] = "<input type=hidden   name=txtcodproben".$li_i." id=txtcodproben".$li_i." value='".$ls_codproben."'><input type=text name=txtnomprovbene".$li_i." value='".$ls_nomproben."' title='".$ls_nomproben."' class=sin-borde readonly style=text-align:left size=20 maxlength=22>";
						   $object[$li_i][5] = "<input type=text     name=txtfecprog".$li_i."   id=txtfecprog".$li_i."   value='".$ld_fecpropag."' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";			
						   $object[$li_i][6] = "<input type=hidden   name=txtcodban".$li_i."    id=txtcodban".$li_i."    value='".$ls_codban."'><input type=text name=txtnomban".$li_i." value='".$ls_nomban."'    title='".$ls_nomban."'    class=sin-borde readonly style=text-align:left size=20 maxlength=22>";			
						   $object[$li_i][7] = "<input type=hidden   name=txtctaban".$li_i."    id=txtctaban".$li_i."    value='".$ls_ctaban."'><input type=text name=txtdencta".$li_i." value='".$ls_denctaban."' title='".$ls_denctaban."' class=sin-borde readonly style=text-align:left size=20 maxlength=22>";			
					 }
	             }
				 $rs_solpag->MoveNext();
			   }
		  $li_totsolpag = $li_i;
		  if ($li_totsolpag==0)
		     {
			   $ls_operacion = "NUEVO";
			 }
		}
     else
	    {
		  $ls_operacion = "NUEVO";
		} 
   }

if ($ls_operacion=="NUEVO")
   {
     $ls_tipproben = '-';
	 $li_totsolpag = $i = 1;
	 $object[1][1] = "<input type=checkbox name=chksel".$i."       id=chksel".$i."       value=1 $ls_disable>";		
	 $object[1][2] = "<input type=text     name=txtnumsol".$i."    id=txtnumsol".$i."    value=''                                  class=sin-borde readonly style=text-align:center size=17 maxlength=15>";
	 $object[1][3] = "<input type=text     name=txtmonsol".$i."    id=txtmonsol".$i."    value='".number_format(0,2,",",".")."'    class=sin-borde readonly style=text-align:right  size=15 maxlength=22>";
	 $object[1][4] = "<input type=hidden   name=txtcodproben".$i." id=txtcodproben".$i." value=''><input type=text name=txtnomprovbene".$i." value=''    class=sin-borde style=text-align:right size=20 maxlength=22 readonly>";
	 $object[1][5] = "<input type=text     name=txtfecprog".$i."   id=txtfecprog".$i."   value=''    class=sin-borde readonly style=text-align:center size=10 maxlength=10>";			
	 $object[1][6] = "<input type=hidden   name=txtcodban".$i."    id=txtcodban".$i."    value=''><input type=text name=txtnomban".$i." value=''    class=sin-borde readonly style=text-align:center size=20 maxlength=22>";			
	 $object[1][7] = "<input type=hidden   name=txtctaban".$i."    id=txtctaban".$i."    value=''><input type=text name=txtdencta".$i." value=''    class=sin-borde readonly style=text-align:center size=20 maxlength=22>";			
   }	
elseif($ls_operacion=="GUARDAR")
   {
     $li_totsolpag=$_POST["totsol"];
     $lb_valido=true;
	 $io_despag->SQL->begin_transaction();
	 for ($i=0;($i<=$li_totsolpag)&&($lb_valido);$i++)
	     {
		   if (array_key_exists("chksel".$i,$_POST))
			  {
			    $ls_numsol    = $_POST["txtnumsol".$i];
				$ldec_monto   = $_POST["txtmonsol".$i];
				$ls_codban    = $_POST["txtcodban".$i];
				$ls_ctaban    = $_POST["txtctaban".$i];
				$ls_provbene  = $_POST["txtcodproben".$i];
				$ls_tipproben = $_POST["rb_provbene"];
				$ld_fecpropag = $_POST["txtfecprog".$i];	
				$ls_estmov    = 'D';				
				$lb_valido    = $io_despag->uf_procesar_desprogramacion($ls_numsol,$ld_fecpropag,$ls_estmov,$ls_codban,$ls_ctaban,$ls_provbene,$ls_tipproben);
			  }			
		 }		
	 if ($lb_valido)
		{
		  $io_despag->SQL->commit();	
		  $io_msg->message("El movimiento fue registrado");
		}
	 else
	    {
		  $io_despag->SQL->rollback();
		}		
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
      <td height="22" colspan="4">Desprogramaci&oacute;n de Pagos
      <input name="hidestciescg" type="hidden" id="hidestciescg" value="<?php echo $li_estciescg ?>">
      <input name="hidestciespi" type="hidden" id="hidestciespi" value="<?php echo $li_estciespi; ?>">
      <input name="hidestciespg" type="hidden" id="hidestciespg" value="<?php echo $li_estciespg; ?>"></td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td width="75" height="22">&nbsp;</td>
      <td colspan="2"><p>
        <label>
        <input name="rb_provbene" type="radio" class="sin-borde" id="rb_provbene" onClick="javascript:uf_cambiar();" value="P" <?php print $rb_prov;echo $ls_disable; ?>>
  Proveedor</label>
        <label>
        <input name="rb_provbene" type="radio" class="sin-borde" id="rb_provbene" onClick="javascript:uf_cambiar();" value="B" <?php print $rb_bene;echo $ls_disable;?>>
  Beneficiario</label>
        <br>
      </p></td>
      <td width="202">&nbsp;</td>
    </tr>
    <tr>
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center">
        <input name="operacion" type="hidden" id="operacion">
        <?php $io_grid->makegrid($li_totsolpag,$titleProg,$object,770,'Solicitudes Programadas',$gridProg);?>
          <input name="totsol"  type="hidden" id="totsol"  value="<?php print $li_totsolpag?>">
          <input name="fila"    type="hidden" id="fila">
      </div></td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td width="249" height="22">&nbsp;</td>
      <td width="252" height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
<script language="javascript">
f = document.form1;
function ue_nuevo()
{
  if (uf_evaluate_cierre('SCG'))
     {
       f.operacion.value ="NUEVO";
       f.action="sigesp_scb_p_desprogpago.php";
       f.submit();	 
	 }
}

function ue_guardar()
{
  if (uf_evaluate_cierre('SCG'))
     {
       f.operacion.value ="GUARDAR";
       f.action="sigesp_scb_p_desprogpago.php";
       f.submit();
	 }
}
	
function uf_cambiar()
{
  f.operacion.value="CAMBIO_TIPO";
  f.action="sigesp_scb_p_desprogpago.php";
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
</script>
</html>