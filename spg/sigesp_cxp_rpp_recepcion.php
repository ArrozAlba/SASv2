<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Relación de Recepción de Documentos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/report.css" rel="stylesheet" type="text/css">
</head>

<body>
<?
require_once("../../shared/class_folder/sigesp_include.php");
$io_in=new sigesp_include();
$con=$io_in->uf_conectar();

require_once("sigesp_cxp_class_report.php");
$io_report = new sigesp_cxp_class_report($con);

require_once("../../shared/class_folder/class_sql.php");
$io_sql = new class_sql($con);
$io_sql2 = new class_sql($con);

require_once("../../shared/class_folder/class_funciones.php");
$io_funcion=new class_funciones();


if (array_key_exists("txtcategoria",$_POST))
   {
     $ls_categoria=$_POST["txtcategoria"];
   }
else
   {
     $ls_categoria=$_GET["txtcategoria"];
   }
if (array_key_exists("txtcodigo1",$_POST))
   {
     $ls_codigo1=$_POST["txtcodigo1"];
   }
else
   {
     $ls_codigo1=$_GET["txtcodigo1"];
   }
if (array_key_exists("txtcodigo2",$_POST))
   {
     $ls_codigo2=$_POST["txtcodigo2"];
   }
else
   {
     $ls_codigo2=$_GET["txtcodigo2"];
   }
if (array_key_exists("txttipodoc1",$_POST))
   {
     $ls_tipodoc1=$_POST["txttipodoc1"];
   }
else
   {
     $ls_tipodoc1=$_GET["txttipodoc1"];
   }
if (array_key_exists("txttipodoc2",$_POST))
   {
     $ls_tipodoc2=$_POST["txttipodoc2"];
   }
else
   {
     $ls_tipodoc2=$_GET["txttipodoc2"];
   }   
if (array_key_exists("txtrecibidas",$_POST))
   {
     $ls_recibidas=$_POST["txtrecibidas"];
   }
else
   {
     $ls_recibidas=$_GET["txtrecibidas"];
   }
if (array_key_exists("txtanuladas",$_POST))
   {
     $ls_anuladas=$_POST["txtanuladas"];
   }
else
   {
     $ls_anuladas=$_GET["txtanuladas"];
   }
if (array_key_exists("txtprocesadas",$_POST))
   {
     $ls_procesadas=$_POST["txtprocesadas"];
   }
else
   {
     $ls_procesadas=$_GET["txtprocesadas"];
   }
if (array_key_exists("txtfechadesde",$_POST))
   {
     $ls_fechadesde=$_POST["txtfechadesde"];
   }
else
   {
     $ls_fechadesde=$_GET["txtfechadesde"];
   }
if (array_key_exists("txtfechahasta",$_POST))
   {
     $ls_fechahasta=$_POST["txtfechahasta"];
   }
else
   {
     $ls_fechahasta=$_GET["txtfechahasta"];
   }

   
$li_cont=0;
$li_page=0;
$arr_emp=$_SESSION["la_empresa"];
$ls_codemp=$arr_emp["CodEmp"];
$rs_recepcion=$io_report->uf_select_recepcion($ls_codemp,$ls_categoria,$ls_codigo1,$ls_codigo2,$ls_tipodoc1,$ls_tipodoc2,$ls_recibidas,$ls_anuladas,$ls_procesadas,$ls_fechadesde,$ls_fechahasta);
$li_total=$io_sql->num_rows($rs_recepcion);
$data=$io_sql->obtener_datos($rs_recepcion);
$li_maxlines=51;
$li_aux=$li_total/$li_maxlines;
$li_total_page=ceil($li_aux);

for ($z=1;$z<=$li_total;$z++)
	{//1
	  $ls_numrecdoc   =$data["NumRecDoc"][$z];
	  $ls_denominacion=$data["DenConDoc"][$z];
	  $ls_codtipdoc   =$data["CodTipDoc"][$z];
	  $ls_sql=" SELECT DenTipDoc ".
	          " FROM cxp_documento ".
			  " WHERE CodTipDoc='".$ls_codtipdoc."'";
	  $rs_recdoc=$io_sql2->select($ls_sql);
      if ($row=$io_sql2->fetch_row($rs_recdoc))
         {//2
	       $ls_dentipdoc=$row["DenTipDoc"];
		 }//2
	  $ls_numref         =$data["NumRef"][$z];
	  $ls_fecemision     =$data["FecEmiDoc"][$z];
	  $ls_fecemision     =$io_funcion->uf_convertirfecmostrar($ls_fecemision);
	  $ls_fecregistro    =$data["FecRegDoc"][$z];
	  $ls_fecregistro    =$io_funcion->uf_convertirfecmostrar($ls_fecregistro);
	  $ls_fecvencimiento =$data["FecVenDoc"][$z];
	  $ls_fecvencimiento =$io_funcion->uf_convertirfecmostrar($ls_fecvencimiento);
	  $ls_tipo           =$data["TipProBen"][$z];       
	  if ($ls_tipo=="P")
	     {//3
		   $ls_tipproben="Proveedor";
		   $ls_codproben=$data["Cod_Pro"][$z];
   		   $ls_tabla    ="rpc_proveedor";
		   $ls_columna  ="NomPro";
	       $ls_campo    ="Cod_Pro";
		 }//3
	  else
	     {//4
		   $ls_tipproben="Beneficiario";
		   $ls_codproben=$data["ced_bene"][$z];
		   $ls_tabla    ="rpc_beneficiario";
		   $ls_columna  ="nombene";
		   $ls_campo    ="ced_bene";
		 }//4	 
	  $ls_sql=" SELECT $ls_columna ".
	          " FROM $ls_tabla ".
			  " WHERE $ls_campo= '".$ls_codproben."'";
	  $rs_recep=$io_sql2->select($ls_sql);
	  if ($row=$io_sql2->fetch_row($rs_recep))
         {//5
		   $ls_nombre=$row[$ls_columna];
		 }//5
	  $ls_estatus  =$data["EstProDoc"][$z];
	  switch ($ls_estatus)
	         {//6
				case "A":
				  $ls_estatus="Anulada";
				  break;
				case "R":
				  $ls_estatus="Recibida";
				  break;
				case "E":
				  $ls_estatus="Emitida";
				  break;
		  	 }//6
 	  $li_cont       =$li_cont+1;
	  $ls_concepto   =$data["DenConDoc"][$z];
	  $ld_subtotal   =$data["MonTotDoc"][$z];
	  $ld_cargos     =$data["MonCarDoc"][$z];
	  $ld_deducciones=$data["MonDedDoc"][$z];
	  if (($li_cont==1))
	     {//7
		    $li_page=$li_page+1;
 		?>
<table width="633" border="0" align="center" cellpadding="0" cellspacing="0" class="report">
  <tr>
    <td colspan="2"><img src="../../shared/imagebank/logo.jpg" width="132" height="50"></td>
    <td colspan="5" valign="bottom"><div align="center" class="titulo_report"><strong>Relaci&oacute;n de Recepciones de Documentos</strong></div></td>
    <td width="42" align="right" valign="top" class="fecha_report"><? print date("d/m/Y")?></td>
  </tr>
  <tr>
    <td height="25" colspan="8">&nbsp;</td>
  </tr>
  <tr>
    <td height="24" align="right"><div align="left"><strong>N&deg; Documento: </strong><? print $ls_numrecdoc?></div></td>
    <td height="24" colspan="4" align="right"><div align="left"><strong>N&deg; Ref: </strong><?php print $ls_numref ?>
    </div>
    <div align="right"></div></td>
    <td width="129" align="right"><strong>Fecha Emisi&oacute;n:</strong></td>
    <td height="24" colspan="2"><?php print $ls_fecemision ?>&nbsp;</td>
  </tr>
  <tr>
    <td height="25" colspan="4" align="left"><strong>Tipo Documento:&nbsp;</strong><?php print $ls_dentipdoc ?></td>
    <td height="25" colspan="2" align="left"><div align="right"><strong>Fecha Registro:</strong></div></td>
    <td height="20" colspan="2"><?php print $ls_fecregistro ?>&nbsp;</td>
  </tr>
  <tr>
    <td height="25" align="left"><strong>Tipo:&nbsp;</strong><?php print $ls_tipproben ?></td>
    <td height="25" colspan="3" align="left"><strong>Estatus:</strong> <?php print $ls_estatus ?>
        <div align="right"></div></td>
    <td height="25" colspan="2" align="left"><div align="right"><strong>Fecha Vencimiento:</strong></div></td>
    <td height="20" colspan="2"><?php print $ls_fecvencimiento ?></td>
  </tr>
  <tr>
    <td height="25" colspan="8" align="left"><strong>Nombre:</strong><?php print $ls_nombre ?></td>
  </tr>
  <tr>
    <td height="25" colspan="8"><div align="left"><strong>Concepto: </strong><?php print $ls_concepto ?></div></td>
  </tr>
  <tr>
    <td height="25" colspan="8">&nbsp;</td>
  </tr>
  <?php
           }//7
  ?>
  <tr>
    <td height="17" colspan="8"><strong>Detalles Presupuestarios </strong></td>
  </tr>
  <tr>
    <td height="25" colspan="2"><div align="left"><strong>Compromiso</strong></div></td>
    <td height="20" colspan="2"><div align="center"><strong>Program&aacute;tica</strong></div>
        <div align="center"></div>
        <div align="center"></div></td>
    <td width="155" height="20" align="right"><div align="center"><strong>Estad&iacute;stico</strong></div></td>
    <td height="20" colspan="3" align="right"><div align="center"><strong>Monto</strong></div></td>
  </tr>
  <?php

	  $lb_valido=$io_report->uf_select_detallespresupuestarios($ls_codemp,$ls_numrecdoc);
	  if ($lb_valido)
	     {//8
  	       $totrow=$io_report->ds_detpresupuesto->getRowCount("NumRecDoc");
		   for ($x=1;$x<=$totrow;$x++)
			   {//9
				 $ls_compromiso  = $io_report->ds_detpresupuesto->data["NumRecDoc"][$x];
				 $ls_programatica= $io_report->ds_detpresupuesto->data["CodEstPro"][$x];
				 $ls_estadistico = $io_report->ds_detpresupuesto->data["SPG_cuenta"][$x];
				 $ld_montopre    = $io_report->ds_detpresupuesto->data["monto"][$x];
		         $ld_montopre    = number_format($ld_montopre,2,',','.');
?>
  <tr>
    <td width="131" height="25" align="center"><div align="left"><?php print $ls_compromiso ?></div></td>
    <td height="20" colspan="3" align="left"><?php print $ls_programatica ?>&nbsp;
        <div align="left"></div></td>
    <td height="20" align="right"><div align="center"><?php print $ls_estadistico ?></div></td>
    <td height="20" colspan="3" align="right"><div align="right"><?php print $ld_montopre ?></div></td>
  </tr>
  <?php 
               }//9
         }//8
  ?>
  <tr>
    <td height="25" colspan="8">&nbsp;</td>
  </tr>
  <tr>
    <td height="17" colspan="8"><strong>Detalles Contables </strong></td>
  </tr>
  <tr>
    <td height="25" colspan="2"><div align="left"><strong>Compromiso</strong></div></td>
    <td height="20" colspan="2"><div align="center"><strong>C&oacute;digo Contable </strong></div>
        <div align="center"></div>
        <div align="center"></div></td>
    <td height="20" align="right"><div align="center"><strong>Operaci&oacute;n</strong></div></td>
    <td height="20" colspan="3" align="right"><div align="center"><strong>Monto</strong></div></td>
  </tr>
  <?php 
	  $lb_valido=$io_report->uf_select_detallescontables($ls_codemp,$ls_numrecdoc);
	  if ($lb_valido)
	     {//10
  	       $totrow=$io_report->ds_detcontable->getRowCount("NumRecDoc");
		   for ($x=1;$x<=$totrow;$x++)
			   {//11
				 $ls_numero    = $io_report->ds_detcontable->data["NumRecDoc"][$x];
				 $ls_contable  = $io_report->ds_detcontable->data["SC_Cuenta"][$x];
				 $ls_debehaber = $io_report->ds_detcontable->data["debhab"][$x];
				 $ld_montocon  = $io_report->ds_detcontable->data["monto"][$x];
		         $ld_montocon  = number_format($ld_montocon,2,',','.');
?>
  <tr>
    <td height="25" colspan="2"><?php print $ls_numero ?>&nbsp;</td>
    <td height="20" colspan="2" align="center"><?php print $ls_contable ?>
        <div align="center"></div>
   
        <div align="center"></div></td>
    <td height="20" align="right"><div align="center"><?php print $ls_debehaber ?></div></td>
    <td height="20" colspan="3" align="right"><div align="right"><?php print $ld_montocon ?></div></td>
  </tr>
  <?php
  	            }//11
	  	 }//10
?>
  <tr>
    <td height="25" colspan="8">&nbsp;</td>
  </tr>
  <tr>
    <td height="25" colspan="4"><strong>Monto Subtotal:</strong><?php print number_format($ld_subtotal,2,',','.') ?></td>
    <td height="20" colspan="4" align="right">&nbsp;</td>
  </tr>
  <tr>
    <td height="25" colspan="4"><strong>Monto Cargos: </strong><?php print number_format($ld_cargos,2,',','.') ?></td>
    <td height="20" colspan="4" align="right">&nbsp;</td>
  </tr>
  <tr>
    <td height="25" colspan="4"><div align="left"><strong>Monto Deducciones: </strong><?php print number_format($ld_deducciones,2,',','.') ?></div></td>
    <td height="20" colspan="4" align="right">&nbsp;</td>
  </tr>
</table>
<table  width="930">
	 <tr>
<?
	if (($li_cont>=$li_maxlines))//Si numero de lineas es mayor o igual al maximo de lineas.
	    {//12
		$li_cont=0;
        print "<td style=text-align:right>".$li_page." de ".$li_total_page."</td>";
?>	 
	 </tr>
</table>
	 <br>
	<?
	    }//12
	elseif(($x==$li_total)&&($li_cont<=$li_maxlines))//Si numero de registro == al total de registros y contador <= al numero maximo de lineas, indica que es el final de los registros 
	   {//13 
	?>	 	  
    <td height="25" colspan="2">&nbsp;</td>
    <td height="20" colspan="3" align="center">&nbsp;</td>
    <td height="20" colspan="4" align="right">&nbsp;</td>
  </tr>
  <tr>
    <td height="25" colspan="2">&nbsp;</td>
    <td height="20" colspan="3" align="center">&nbsp;</td>
    <td height="20" colspan="4" align="right">&nbsp;</td>
  </tr>
  <tr>
    <td height="25" colspan="2">&nbsp;</td>
    <td height="20" colspan="3" align="center">&nbsp;</td>
    <td height="20" colspan="4" align="right">&nbsp;</td>
  </tr>
  <tr>
    <td height="25">&nbsp;</td>
    <td height="20" align="center">&nbsp;</td>
    <td height="20" colspan="3" align="center">&nbsp;</td>
    <td height="20" colspan="4" align="right">&nbsp;</td>
  </tr>
  </table>
		<?
		print "CONTADOR ANTES  -->".$li_cont;
		$li_cont=$li_cont+3;//Incremento 3 por las filas de total debito, total credito, total saldo.
		print "CONTADOR -->".$li_cont;
		print "MAXIMO DE LINEAS -->".$li_maxlines;
		
		for ($x=$li_cont+1;$x<=$li_maxlines;$x++)//hago un ciclo para terminar de llenar de espacios en blanco en caso de que no se haya llegado al final de la pagina.
		    {//14
		     print "Entro".$x;
			 ?>
		     <tr>
			 <td colspan="3">&nbsp;</td>
		     </tr>
		     <?
		    }//14
		     ?>
		    </table>
		    <table width="930">
			 <tr>
			 <? //print "<td  style=text-align:right>".$li_page." de ".$li_total_page."</td>";?>	 
			 </tr>
		 </table>		 
	<?
    }//13
}//1
?> 
</body>
</html>