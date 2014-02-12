<?php
session_start();
ini_set("max_execution_time","0");
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
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
<html>
<head>
<title>Tranferencia del Personal de N&oacute;mina a Beneficiarios</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="css/rpc.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/sigesp_cat_ordenar.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<style type="text/css">
.xstooltip 
{
    visibility: hidden; 
    position: absolute; 
    top: 0;  
    left: 0; 
    z-index: 2; 

    font: normal 8pt sans-serif; 
    padding: 3px; 
    border: solid 1px;
}
</style>
<!-- Copyright 2000,2001 Macromedia, Inc. All rights reserved. -->
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Proveedores y Beneficiarios</td>
			<td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>

      </table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:uf_transferir_personal();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Procesar" title="Procesar" name="Transferir" width="20" height="20" border="0" id="Transferir"></a><a href="javascript:ue_guardar();"></a><a href="javascript:ue_buscar();"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>

<?php
require_once("class_folder/sigesp_rpc_c_beneficiario.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/grid_param.php");

$io_beneficiario = new sigesp_rpc_c_beneficiario();
$io_conect       = new sigesp_include();
$con             = $io_conect-> uf_conectar ();
$io_msg          = new class_mensajes(); //Instanciando la clase mensajes 
$io_sql          = new class_sql($con); //Instanciando  la clase sql
$io_funcion      = new class_funciones();
$io_class_grid   = new grid_param();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_codemp   = $ls_empresa; 
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "RPC";
	$ls_ventanas = "sigesp_rpc_p_transferencia.php";

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
		$ls_permisos            = $io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

if  (array_key_exists("operacion",$_POST))
	{
	  $ls_operacion = $_POST["operacion"];
      $ls_cedula1   = $_POST["txtcedula1"];  
      $ls_cedula2   = $_POST["txtcedula2"]; 
	  $li_total     = $_POST["hidtotrows"]; 
      $ls_orden     = $_POST["hidorden"];
	  $ls_sccuenta  = $_POST["txtcontable"];
	  $ls_dencue    = $_POST["txtdencuenta"];
	}
else
	{
	  $ls_operacion = "";
	  $ls_cedula1   = "";
	  $ls_cedula2   = "";
	  $li_total     = 0;
	  $ls_orden     = "cedper ASC";
	  $ls_sccuenta  = "";
	  $ls_dencue    = "";

	}

if ($ls_operacion=="TRANSFERIR")
   { 
	 $lb_valido = true;
  $io_sql->begin_transaction();
	 for ($i=1;$i<=$li_total;$i++)
	     { 
		   if (array_key_exists("checkper".$i,$_POST))
		      {
		        $ls_cedper = trim($_POST["txtcedulas".$i]);
			    $rs_data   = $io_beneficiario->uf_load_datos_personal($ls_codemp,$ls_cedper);
			    if ($row=$io_sql->fetch_row($rs_data))
				   {
					  $ls_nomben                = $row["nomper"];
					  $ls_apeben                = $row["apeper"];
					  $ls_dirben                = $row["dirper"];
					  $ls_telben                = $row["telhabper"];
					  $ls_celben                = $row["telmovper"];
					  $ls_corben                = $row["coreleper"];
					  $ls_codpai                = $row["codpai"];
					  $ls_codest                = $row["codest"];
					  $ls_codmun 			    = $row["codmun"];
					  $ls_codpar 			    = $row["codpar"];
					  $ls_nacben                = $row["nacper"];				     				   
					  $ls_codban                = $row["codban"];				     				   
					  $ls_ctaban                = $row["ctaban"];				     				   

					  $lr_datos["cedula"]       = $ls_cedper;
					  $lr_datos["nombre"]       = $ls_nomben;
					  $lr_datos["apellido"]     = $ls_apeben;
					  $lr_datos["direccion"]    = $ls_dirben;
					  $lr_datos["telefono"]     = $ls_telben; 
					  $lr_datos["celular"]      = $ls_celben;
					  $lr_datos["email"]        = $ls_corben;
					  $lr_datos["pais"]         = $ls_codpai;
					  $lr_datos["estado"]       = $ls_codest;
					  $lr_datos["municipio"]    = $ls_codmun;
					  $lr_datos["parroquia"]    = $ls_codpar;
					  $lr_datos["nacionalidad"] = $ls_nacben;
					  $lr_datos["tipconben"]    = 'F';
					  $lr_datos["contable"]     = $ls_sccuenta;
					  $lr_datos["codban"]       = $ls_codban;
					  $lr_datos["ctaban"]       = $ls_ctaban;
					  
					  $lb_valido                = $io_beneficiario->uf_insert_personal($ls_codemp,$lr_datos,$la_seguridad);
					  if (!$lb_valido)
						 {
						   break;
						 }
			       }
		      } 
         }
     if ($lb_valido)
	    {
		  $io_sql->commit();
		  $io_msg->message("Transferencia realizada con éxito !!!");
		}
     else
	    {
	      $io_sql->rollback();
		  $io_msg->message($io_beneficiario->is_msg_error);
		}
   }
?>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
  <div align="center">
    <p>
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
?></p>
    <table width="560" height="198" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="555" height="179"><div align="center">
          <div align="center">
            <table width="490"  border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
              <tr class="titulo-celdanew">
                <td height="22" class="titulo-celdanew">Transferencia de Personal </td>
              </tr>
              <tr class="formato-blanco">
                <td height="13">&nbsp;</td>
                <tr class="formato-blanco">
                <td height="50"><table width="438" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td height="26" colspan="4"><strong>Cuenta</strong> <label>
                      <input name="txtcontable" type="text" id="txtcontable" value="<?php print $ls_sccuenta ?>" size="30" maxlength="25" style="text-align:center" readonly>
                      <a href="javascript:catalogo_cuentas();"><img src="../shared/imagebank/tools15/buscar.gif" name="catalogo" width="15" height="15" border="0" id="catalogo" onMouseOver="xstooltip_show('tooltip_cuentas', 'catalogo',0,95);" onMouseOut="xstooltip_hide('tooltip_cuentas');"></a>
                      <input name="txtdencuenta" type="text" class="sin-borde" id="txtdencuenta" value="<?php print $ls_dencue ?>" size="30" style="text-align:left" readonly>
                    </label></td>
                  </tr>
                  <tr>
                    <td height="22" colspan="4"><strong>Intervalo de C&eacute;dulas/C&oacute;digos de Personal 
                      <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion ?>">
                    </strong></td>
                  </tr>
                  <tr>
                    <td width="59" height="22"><div align="right"><span class="style1 style14">Desde</span></div></td>
                    <td width="124" height="22"><input name="txtcedula1" type="text" id="txtcedula1" value="<?php print $ls_cedula1 ?>" size="15" maxlength="10"  style="text-align:center " onKeyPress="return keyRestrict(event,'0123456789');">
                      <a href="javascript:uf_catalogo_personal();"><img src="../shared/imagebank/tools15/buscar.gif" alt="C&oacute;digos..." width="15" height="15" border="0"  onClick="document.form1.hidrango.value=1"></a></td>
                    <td width="64" height="22"><div align="right"><span class="style1 style14">Hasta</span></div></td>
                    <td width="159" height="22"><input name="txtcedula2" type="text" id="txtcedula2" value="<?php print $ls_cedula2 ?>" size="15" maxlength="10"  style="text-align:center " onKeyPress="return keyRestrict(event,'0123456789');">
                        <a href="javascript:uf_catalogo_personal();"><img src="../shared/imagebank/tools15/buscar.gif" alt="C&oacute;digos..." width="15" height="15" border="0"  onClick="document.form1.hidrango.value=2"><strong><span class="style14">
                        <input name="hidrango" type="hidden" id="hidrango">                        
                      </span></strong></a><a href="javascript:ue_buscar();">
                      <input type="hidden" name="hidorden" id="hidorden" value="<?php print $ls_orden?>"/>
                      </a></td>
                  </tr>
                  <tr>
                    <td height="22">&nbsp;</td>
                    <td height="22">&nbsp;</td>
                    <td height="22">&nbsp;</td>
                    <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></div></td>
                  </tr>
                </table></td>
                <tr class="formato-blanco">
                <td height="22">&nbsp;</td>
              </table>
          </div>
        </div></td>
      </tr>
    </table>
    <div id=transferir style="visibility:hidden"><img src="../shared/imagebank/cargando.gif">Transfiriendo personal... </div>
<?php
if ($ls_operacion=="BUSCAR")
   {
	 $lb_valido = true;
	 $li_total  = 0;
	 $rs_data   = $io_beneficiario->uf_load_personal($ls_codemp,$ls_cedula1,$ls_cedula2,$ls_orden,&$lb_valido);
	 if ($lb_valido)
	    {
          $title[1] = "<input name=chktodos type=checkbox id=chktodos value=1 style=height:15px;width:15px onClick=javascript:uf_select_all(); >";
	      $title[2] = "<a href=javascript:ue_ordenar('cedper');><font color=#FFFFFF>Cédula/Código</font></a>"; 
		  $title[3] = "<a href=javascript:ue_ordenar('apeper');><font color=#FFFFFF>Nombre del Personal</font></a>"; 
		  $ls_grid  = "grid_personal";
		  while($row=$io_sql->fetch_row($rs_data))
		       {
			     $li_total++;
				 $ls_cedper = trim($row["cedper"]);
				 $ls_nomper = trim($row["nomper"]);
				 $ls_apeper = trim($row["apeper"]);
				 $ls_nombre = $ls_apeper.', '.$ls_nomper;
				 $object[$li_total][1]="<input type=checkbox  id=checkper".$li_total."   name=checkper".$li_total."   value=1 class=sin-borde   size=5  style=text-align:center>"; 
		         $object[$li_total][2]="<input type=text      id=txtcedulas".$li_total." name=txtcedulas".$li_total." value='".$ls_cedper."' class=sin-borde size=20 style=text-align:center readonly>";
		         $object[$li_total][3]="<input type=text      id=txtnombre".$li_total."  name=txtnombre".$li_total."  value='".$ls_nombre."' class=sin-borde size=80 style=text-align:left   readonly>";
			   }
		  $io_class_grid->make_gridScroll($li_total,$title,$object,560,'Personal Nómina',$ls_grid,200);
		}
     else
	    {
		  $li_numrows = $io_sql->num_rows($rs_data);
		  if ($li_numrows<=0)
		     {
			   $io_msg->message("No se ha encontrado personal por transferir !!!");
			 }
		}
   }
?>
&nbsp;</p>
  </div>
<input name="hidtotrows" type="hidden" id="hidtotrows" value="<?php print $li_total ?>">  
</form>
<div id="tooltip_cuentas" class="xstooltip">
	Este catálogo le permitirá seleccionar la <b>Cuenta Contable</b> que será asociada<br/>
	al beneficiario una vez sea transferido.<br/>
</div>
</body>

<script language="javascript">
f = document.form1;
function uf_transferir_personal()
{
  li_totrows  = f.hidtotrows.value;
  li_ejecutar = f.ejecutar.value;
  if (li_ejecutar=='1')
     {
	   lb_marcadas = false;
	   for (i=1;i<=li_totrows;i++)
	       {
		     if (eval("f.checkper"+i+".checked==true"))
			    {
				  lb_marcadas = true;
				}
		   }
	    if (lb_marcadas)
		   {
		     ls_cuenta = f.txtcontable.value;
			 if (ls_cuenta=="")
			    {
				  alert("Debe establecer la Cuenta Contable que será asociada al Beneficiario !!!");
				}
			 else
			    {
				  mostrar('transferir');
				  f.operacion.value = "TRANSFERIR";
				  f.action          = "sigesp_rpc_p_transferencia.php";
				  f.submit();
				}
		   }
		else
		   {
		     alert("No ha seleccionado ningun personal para transferir !!!");
		   }   
	 }
   else
     {
	   alert("No tiene permiso para realizar esta operación !!!");
	 }
}

function uf_catalogo_personal()
{
  ls_cedula1 = f.txtcedula1.value;
  ls_cedula2 = f.txtcedula2.value;
  li_leer    = f.leer.value;
  if (li_leer==1)
	 {
       pagina="sigesp_rpc_cat_personal.php";
	   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=350,resizable=yes,location=no");
	 }
  else
	 {
	   alert("No tiene permiso para realizar esta operacion");
 	 } 
}

function ue_search()
{
  li_leer = f.leer.value;
  if (li_leer==1)
	 {
       ls_cedula_1 = f.txtcedula1.value;
       ls_cedula_2 = f.txtcedula2.value;
	   if (ls_cedula_1=="" || ls_cedula_2=="")
	      {
		    alert("Debe establecer el Rango de Cédulas/Códigos para realizar la Búsqueda !!!");  
		  }
       else
	      {
		    if (parseFloat(ls_cedula_1)<=parseFloat(ls_cedula_2))
			   {
	             f.operacion.value = "BUSCAR";
                 f.action="sigesp_rpc_p_transferencia.php";
				 f.submit();
			   }
		    else
			   {
			     alert("Error en rango de Cédulas/Códigos !!!");
			   } 
		  }
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function uf_select_all()
{
	total = f.hidtotrows.value;
	if (document.form1.chktodos.checked==true)
	   {
         sel_all='T';	
	   }
	else
	   {
	     sel_all='F';
	   }
	if (sel_all=='T')
	   {
	     for (i=1;i<=total;i++)	
		     {
			   eval("f.checkper"+i+".checked=true");
		     }
	   }
     else
	   {
         for (i=1;i<=total;i++)	
		     {
			   eval("f.checkper"+i+".checked=false");
		     }
  	   } 
}

function catalogo_cuentas()
{
	f.operacion.value="";			
	pagina="sigesp_catdinamic_ctas.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no");
}
</script>
</html>