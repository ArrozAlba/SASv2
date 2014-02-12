<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";
}
$la_datemp=$_SESSION["la_empresa"];

//	print $ls_estatus;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Forma de pago</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
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
	color: #006699#006699;
}
.style6 {color: #000000}

.styleMonto1 {

	color:#990000;
	cursor:text;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 24px;}
.styleMonto2 {
    border:none;
	color: #003399;
	cursor:text;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 24px;}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
<?php
/************************************************************************************************************************/
/********************************   LIBRERIAS   *************************************************************************/
/************************************************************************************************************************/
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
$io_funsob =   new sigesp_sob_c_funciones_sob();
$io_datastore= new class_datastore();
$io_utilidad = new sigesp_sfc_class_utilidades();
$io_include=new sigesp_include();
$io_msg=new class_mensajes();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_funcdb=new class_funciones_db($io_connect);
/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codcli=$_POST["txtcodcli"];
   $ls_codforpag=$_POST["comboforma"];
	$ls_denforpago=$_POST["hiddenforpago"];
   $ls_numinst=$_POST["txtnuminst"];
	$ls_codban=$_POST["combo_banco"];
	$ls_nombanco=$_POST["txtnombanco"];
	$ls_fecins=$_POST["txtfecins"];
	$ld_monto=$_POST["txtmonto"];
	$ls_obsins=$_POST["txtobsins"];
	$ls_metforpag=$_POST["txtmetforpag"];
	$ls_metforpago=$_POST["txtmetforpago"];
	$ls_montot=$_POST["txttotal"];
	$ls_estatus=$_POST["estatus"];
}
else
{
	$ls_operacion="";
	$ls_codforpag="";
	$ls_numinst="";
	$ls_codcli=$_GET["codcli1"];
	$ls_montot=$_GET["total"];
	$ls_estatus=$_GET["estatus"];
	$ls_nomban="";
	$ls_nombanco="";
	$ls_codban="";
	$ls_denforpag="";
	$ls_denforpago="";
	$ls_fecins="";
	$ld_monto="0,00";
	$ls_obsins="";
	$ls_metforpag="";
	$ls_metforpago="";
}
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
  </p>
  	 <table width="560" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="479" colspan="2" class="titulo-celda"><div align="center">Cat&aacute;logo de Instrumento de pago (Cobranza) </div></td>
    	</tr>
  </table>
	 <br>
	 <table width="560"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
       <tr>
         <td >
           <input name="hidstatus" type="hidden" id="hidstatus">
		    <input name="estatus" type="hidden" id="estatus" value="<?php print $ls_estatus;?>">
           <input name="hidcodforpag" type="hidden" id="hidcodforpag" value="<?php print $ls_codforpag ?>">
           <input name="txtnombanco" type="hidden" id="txtnombanco" value="<?php print $ls_codban ?>">
           <input name="hiddenforpago" type="hidden" id="hiddenforpago" value="<?php print $ls_denforpag ?>">
           <input name="txtmetforpag" type="hidden" id="txtmetforpag" value="<?php print $ls_metforpag ?>">
           <input name="txtmetforpago" type="hidden" id="txtmetforpago" value="<?php print $ls_metforpago ?>">
           <input name="txtcodcli" type="hidden" id="txtcodcli" value="<?php print $ls_codcli ?>">
		   <input name="txttotal" type="hidden" id="txttotal" value="<?php print $ls_montot ?>">
         <td colspan="2" >&nbsp;</td>
       </tr>
       <tr>
         <td width="196" height="22" align="right">Forma de pago </td>
         <td colspan="2" ><!--<a href="javascript:ue_catclientenota();"></a></td>-->
         <?php
		 $ls_sql="SELECT *
                       FROM sfc_formapago
                       ORDER BY denforpag ASC";
			$lb_valest=$io_utilidad->uf_datacombo($ls_sql,&$la_denforpag);

			if($lb_valest)
			 {
			   $io_datastore->data=$la_denforpag;
			   $li_totalfilas=$io_datastore->getRowCount("codforpag");
			 }
			 else
			   $li_totalfilas=0;
	     ?>
         <select name="comboforma" size="1" id="comboforma"  onChange="actualizar_pagina();">
           <option value=""  >Seleccione...</option>
           <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
						 $ls_codigof=$io_datastore->getValue("codforpag",$li_i);
						 $ls_denforpag=$io_datastore->getValue("denforpag",$li_i);
						 $ls_metforpag=$io_datastore->getValue("metforpag",$li_i);
						 if ($ls_codigof!='04' and $ls_estatus!='true')
						 {
							 if ($ls_codigof==$ls_codforpag)
							 {
								  print "<option  value='$ls_codigof' selected>$ls_denforpag</option>";
								  $ls_denforpago=$ls_denforpag;
								  $ls_metforpago=$ls_metforpag;
							 }
							 else
							 {
								  print "<option  value='$ls_codigof' >$ls_denforpag</option>";

							 }
						 }
						 if ($ls_estatus=='true')
						 {
							if ($ls_metforpag=='B' )
							{
							 if ($ls_codigof==$ls_codforpag)
								 {
									  print "<option  value='$ls_codigof' selected>$ls_denforpag</option>";
									  $ls_denforpago=$ls_denforpag;
									  $ls_metforpago=$ls_metforpag;
								 }
								 else
								 {
									  print "<option  value='$ls_codigof' >$ls_denforpag</option>";

								 }
								 }
						 }
					}
	        ?>
       </select>       </tr>
       <tr>
         <td width="196" height="30" align="right">No. control</td>
         <td colspan="2" >
		  <?php
		  print "<script language=JavaScript>document.form1.txtmetforpago.value='".$ls_metforpago."' </script>";
		  if($ls_metforpago=='C'||$ls_codforpag=="03")
		   {
		     if ($ls_metforpago=='C')
			  {
			   $ls_numinst="000000000000000";
			  }
		  ?>
		 <input name="txtnuminst" type="text" id="txtnuminst"   readonly="readonly" style="text-align:center " value="<?php print $ls_numinst ?>" size="28" maxlength="25"  >
		   <?php

		   }
		  else
		   {
		    ?>
			<input name="txtnuminst" type="text" id="txtnuminst"   style="text-align:center " value="<?php print $ls_numinst ?>" size="28" maxlength="25"  >
			<?php
			}
			?>        		          </td>
       </tr>
       <tr>
         <td height="31"><div align="right">Fecha</div></td>
         <td colspan="2"><input name="txtfecins" type="text" id="txtfecins"  style="text-align:left" value="<?php print $ls_fecins ?>" size="11" maxlength="10" onKeyPress="javascript:ue_validafecha();" datepicker="true" readonly="true"></td>
       </tr>
       <tr>
         <td height="31"><div align="right">Banco</div></td>
         <td colspan="2"><label>
		 	     <?php
		 $ls_sql="SELECT *
                       FROM scb_banco
                       ORDER BY nomban ASC";

			$lb_valest=$io_utilidad->uf_datacombo($ls_sql,&$la_nomban);

			if($lb_valest)
			 {
			   $io_datastore->data=$la_nomban;
			   $li_totalfilas=$io_datastore->getRowCount("codban");
			 }
			 else
			   $li_totalfilas=0;
	    //---------------  combo banco="seleccion..." si forma pago='C'-->efectivo,B-->banco  -------------
			   if($ls_metforpago=='C'||$ls_codforpag=="03")
			   {
			     print "<script language=JavaScript>document.form1.txtmetforpago.value='".$ls_metforpago."' </script>";
	        ?>
              <select name="combo_banco" size="1" id="combo_banco">
		       <option value="" selected>Seleccione...</option>
		     <?php

		       }
			   else
			   {
			 ?>
			  <select name="combo_banco" size="1" id="combo_banco">
			   <option value="" >Seleccione...</option>
			 <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
						 $ls_codigob=$io_datastore->getValue("codban",$li_i);
						 $ls_nomban=$io_datastore->getValue("nomban",$li_i);

						 if ($ls_codigob==$ls_codban )
						 {
							  print "<option  value='$ls_codigob' selected>$ls_nomban</option>";
							 $ls_nombanco=$ls_nomban;
						 }
						 else
						 {
							  print "<option  value='$ls_codigob' >$ls_nomban</option>";
						 }
					}
				}
	        ?>
           </select>
         </label></td>
       </tr>
       <tr>
         <td height="8"><div align="right">Observaci&oacute;n</div></td>
         <td colspan="2"><p>

		 <?php
		 if($ls_metforpago=='D')// Si es nota de debito se deshabilta textbox
		  {
		 ?>
           <textarea name="txtobsins"  cols="40" rows="2" id="txtobsins"  readonly="readonly" value="<?php print $ls_obsins ?>" ><?php print $ls_obsins ?></textarea>
		 <?php
		  }
		  else
		  {
		 ?>
		    <textarea name="txtobsins"  cols="40" rows="2" id="txtobsins" value="<?php print $ls_obsins ?>" ><?php print $ls_obsins ?></textarea>
		 <?php
		 }
		 ?>
         </p>
		 </td>
       </tr>
       <tr>
         <td height="30"><div align="right">Monto</div></td>
         <td width="241"><p>
		 <?php
		  if($ls_metforpago=='C')
			  {
			  ?>
			  <input name="txtmonto" type="text" class="styleMonto1" id="txtmonto"   onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $ld_monto ?>" size="20" maxlength="20">	 	    <?php
			   }
		   else
			   {
			   ?>
			   <input name="txtmonto" type="text" class="styleMonto1" id="txtmonto" value="<?php print $ld_monto ?>" size="20" maxlength="20" onKeyPress="return(currencyFormat(this,'.',',',event))">
			   <?php
			   }
			   ?>
         </p>
		 </td>
         <td width="121">
		 <?php
		 if(($ls_metforpago=='D')&&($ls_codforpag=='03'))
			  {
			 ?>
			   <a href="javascript:ue_catnota();">Nota<img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a>
			   <?php
			 }
		?>
		</td>
       </tr>
		 <?php
		   if($ls_denforpago=='Efectivo')
		    {
		 ?>
		  <tr>
          <td height="8"><div align="right">Cambio</div></td>
          <td colspan="2">
		   <input name="txtcambio" type="text" class="styleMonto2" id="txtcambio"  size="20" maxlength="20"   readonly="true">		  </td>
         </tr>
		  <tr>
		    <td height="8">&nbsp;</td>
		    <td colspan="2">&nbsp;</td>
       </tr>
		 <?php
		    }
		 ?>
       <tr>
         <td height="8">&nbsp;</td>
         <td colspan="2"><div align="right">
<!----------------------  IMAGEN aprobado.gif (ENVIA DATOS A LA PAGINA ANTERIOR) -------------------------------------------------->
<a href="javascript:ue_enviar_datos();"><img src="../shared/imagebank/tools20/aprobado.gif" width="22" height="22" border="0"></a>

<!---------------------------------  IMAGEN ELIMINAR ------------------------------------------------------------------------>
<a href="javascript:ue_borrar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
       </tr>
  </table>
	 <br>
<?php
/************************************************************************************************************************/
/*****************************  CARGA LOS DATOS EN PAGINA ANTERIOR DE FACTURA  ******************************************/
/************************************************************************************************************************/
if($ls_operacion=="ENVIAR_DATOS")
{
          $ld_monA=$io_funsob->uf_convertir_cadenanumero($ld_monto);
          $ld_monB=$io_funsob->uf_convertir_cadenanumero($ls_montot);

		   if(($ls_metforpago=="C"))
			{
			  if ($ld_monB<$ld_monA)
			   {
			      $ld_monto=$ls_montot;
			   }
			}
			if ($ls_nombanco=="")
			{
			  $ls_nombanco="N/A";
			  $ls_codban='000';
			   //$ls_numinst="00000000000000";
			}

	         ?>
			<script language="JavaScript">

			 var ls_numinst = '<?php print $ls_numinst ?>' ;
			 var ld_monto = '<?php print $ld_monto ?>' ;
			 var ls_denforpago = '<?php print $ls_denforpago ?>' ;
			 var ls_metforpag='<?php print $ls_metforpago ?>';
			 var ls_nombanco = '<?php print $ls_nombanco ?>' ;
			 var ls_codban = '<?php print $ls_codban ?>' ;
			 var ls_codforpag = '<?php print $ls_codforpag ?>' ;
			 var ls_fecins = '<?php print $ls_fecins ?>' ;
			 var ls_obsins = '<?php print $ls_obsins ?>' ;

			 opener.ue_cargarfpago(ls_numinst,ld_monto,ls_denforpago,ls_nombanco,ls_codban,ls_codforpag,ls_fecins,ls_obsins,ls_metforpag);
			 close();
			</script>

			<?PHP
}
/************************************************************************************************************************/
/******************************************  FUNCIONES JAVASCRIPT  ******************************************************/
/************************************************************************************************************************/
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_catnota()
{

f=document.form1;
	codcliente=f.txtcodcli.value;
	f.operacion.value="";
	//pagina="sigesp_cat_nota.php";
	//alert(codcliente);
	pagina="sigesp_cat_notafactura.php?codcli1="+codcliente;
	popupWin(pagina,"catalogo nota",650,450);
}

function ue_cargarnota(nomcli,codcli,numnot,dennot,tipnot,fecnot,monto,estnot)
{

 	  f=document.form1;
	  f.txtnuminst.value=numnot;
	  f.txtobsins.value=dennot;
	  f.txtmonto.value=monto;
	  f.operacion.value="";
	  f.action="sigesp_cat_instpagcarta.php";
	  f.submit();
}
function actualizar_pagina()
{

  f=document.form1;
  f.operacion.value="";
  f.txtnuminst.value="";
  f.txtmonto.value="";
  f.txtfecins.value="";
  f.txtobsins.value="";
  //f.txtmetforpago.value
  f.action="sigesp_cat_instpagcarta.php";
  f.submit();
}
function ue_borrar()
 {
  f=document.form1;

  f.txtnuminst.value="";
  f.txtmonto.value="";
  f.comboforma.value="nulo"


 }

  function ue_enviar_datos()
  {

  f=document.form1;
	with(f)
		{

			if (ue_valida_null(txtmonto,"Monto")==false)
			 {
				txtmonto.focus();
			 }
			else if (ue_valida_null(txtfecins,"Fecha")==false)
			 {
			   txtfecins.focus();
			 }
			else if (txtnuminst.value!="000000000000000" && txtmetforpago.value!="D")
			 {
			   	if (txtnuminst.value=="")
				 {
				  alert("Introduzca el número de instrumento de pago!");
				 }
				 else if(combo_banco.value=="")
				 {
				 alert("Debe seleccionar un banco.");
				 }
				 else
				 {
				   operacion.value="ENVIAR_DATOS";
				   action="sigesp_cat_instpagcarta.php";
				   submit();
				 }
			 }


			 else
			 {

			operacion.value="ENVIAR_DATOS";
			action="sigesp_cat_instpagcarta.php";
			submit();

			 }

		}


}
 function cambio()
 {
   f=document.form1;
   ls_flag=f.comboforma.value;
   if (ls_flag=="01")
   {
    ld_monto=parseFloat(uf_convertir_monto(f.txttotal.value));
    ld_pago=parseFloat(uf_convertir_monto(f.txtmonto.value));
	if (ld_pago>ld_monto)
	 {
	   ld_cambio=ld_pago-ld_monto;
	 }
	 else
	 {
	   ld_cambio=0.00;
	 }

    f.txtcambio.value=uf_convertir(ld_cambio);
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
	cambio();
	return false;
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
