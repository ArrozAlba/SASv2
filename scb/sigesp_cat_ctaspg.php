<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "close();";
	 print "opener.document.form1.submit();";
	 print "</script>";		
   }
$la_empresa		  = $_SESSION["la_empresa"];
$li_estmodest     = $la_empresa["estmodest"];
$li_loncodestpro1 = $la_empresa["loncodestpro1"];
$li_loncodestpro2 = $la_empresa["loncodestpro2"];
$li_loncodestpro3 = $la_empresa["loncodestpro3"];
$li_loncodestpro4 = $la_empresa["loncodestpro4"];
$li_loncodestpro5 = $la_empresa["loncodestpro5"];

$li_size1 = $li_loncodestpro1+10;
$li_size2 = $li_loncodestpro2+10;
$li_size3 = $li_loncodestpro3+10;
$li_size4 = $li_loncodestpro4+10;
$li_size5 = $li_loncodestpro5+10;
      
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");

$la_empresa = $_SESSION["la_empresa"];
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql		= new class_sql($ls_conect);
$io_intspg  = new class_sigesp_int_spg();

if (array_key_exists("operacion",$_POST))
   {  
	 $ls_operacion  = $_POST["operacion"];
	 $ls_spgcta     = $_POST["txtcodctaspg"];
	 $ls_denctaspg  = $_POST["txtdenctaspg"];
	 $ls_scgcta     = $_POST["txtcuentascg"];
	 $ls_codestpro1 = $_POST["codestpro1"];
	 $ls_codestpro2 = $_POST["codestpro2"];
	 $ls_codestpro3 = $_POST["codestpro3"];
 	 $ls_denestpro1 = $_POST["txtdenestpro1"];
	 $ls_denestpro2 = $_POST["txtdenestpro2"];
	 $ls_denestpro3 = $_POST["txtdenestpro3"];
	 if (array_key_exists("codestpro4",$_POST))
	    {
		  $ls_codestpro4 = $_POST["codestpro4"];
		  $ls_codestpro5 = $_POST["codestpro5"];
		  $ls_denestpro4 = $_POST["txtdenestpro4"];
		  $ls_denestpro5 = $_POST["txtdenestpro5"];
		}
	 else
	    {
		  $ls_codestpro4 = "";
		  $ls_codestpro5 = "";
		  $ls_denestpro4 = "";
		  $ls_denestpro5 = "";
		}
     $ls_estcla = $_POST["hidestcla"];
	 $ls_fecmov = $_POST["hidfecmov"]; 
   }
else
   {
	 $ls_operacion  = "";
	 $ls_estcla     = $_GET["hidestcla"];
 	 $ls_codestpro1 = $_GET["codestpro1"];
	 $ls_codestpro2 = $_GET["hicodest2"];
	 $ls_codestpro3 = $_GET["hicodest3"];
     $ls_denestpro1 = $_GET["txtdenestpro1"];
	 $ls_denestpro2 = $_GET["txtdenestpro2"];
	 $ls_denestpro3 = $_GET["txtdenestpro3"];
	 if (array_key_exists("hicodest4",$_GET))
	    {
		  $ls_codestpro4 = $_GET["hicodest4"];
		  $ls_codestpro5 = $_GET["hicodest5"];
		  $ls_denestpro4 = $_GET["txtdenestpro4"];
		  $ls_denestpro5 = $_GET["txtdenestpro5"];
		}
	 else
	    {
		  $ls_codestpro4 = "";
		  $ls_codestpro5 = "";
  		  $ls_denestpro4 = "";
	      $ls_denestpro5 = "";
		}
     $ls_fecmov = $_GET["fecmov"];
   }
$_SESSION["fechacomprobante"] = $ls_fecmov;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Presupuestarias de Gasto</title>
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
</head>
<body>
<form name="form1" method="post" action="">
  <p align="center">&nbsp;</p>
  <br>
  <div align="center">
    <table width="623" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="6" style="text-align:center"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de Cuentas Presupuestarias de Gasto
        <input name="hidestcla" type="hidden" id="hidestcla" value="<?php echo $ls_estcla ?>">
        <input name="hidfecmov" type="hidden" id="hidfecmov" value="<?php echo $ls_fecmov; ?>"></td>
      </tr>
      <tr>
        <td width="106" align="right">&nbsp;</td>
        <td width="122">&nbsp;</td>
        <td width="63" align="right">&nbsp;</td>
        <td width="127">&nbsp;</td>
        <td width="52" align="right">&nbsp;</td>
        <td width="151" >&nbsp;</td>
      </tr>
      <tr>
        <td height="21" style="text-align:right"><?php print $la_empresa["nomestpro1"];?></td>
        <td height="21" colspan="9"><input name="codestpro1" type="text" id="codestpro1" size="<?php print $li_size1 ;?>" maxlength="<?php echo $li_loncodestpro1;?>" style="text-align:center " readonly value="<?php print $ls_codestpro1;?>">
          <label>
          <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php echo $ls_denestpro1 ?>" size="50" readonly>
        </label></td>
      </tr>
      <tr>
        <td height="21" style="text-align:right"><?php print $la_empresa["nomestpro2"];?></td>
        <td height="21" colspan="9"><input name="codestpro2" type="text" id="codestpro2" size="<?php echo $li_size2;?>" maxlength="<?php echo $li_loncodestpro2;?>" style="text-align:center " readonly value="<?php print $ls_codestpro2;?>">
        <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" value="<?php echo $ls_denestpro2 ?>" size="50" readonly></td>
      </tr>
      <tr>
        <td height="21" style="text-align:right"><?php print $la_empresa["nomestpro3"];?></td>
        <td height="21" colspan="9"><input name="codestpro3" type="text" id="codestpro3" size="<?php echo $li_size3;?>" maxlength="<?php echo $li_loncodestpro3;?>" style="text-align:center " readonly value="<?php print $ls_codestpro3;?>">
        <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" value="<?php echo $ls_denestpro3 ?>" size="50" readonly></td>
      </tr>
      <?php
	    if ($li_estmodest=='2')
		   {
	  ?>
	  <tr>
        <td height="21" style="text-align:right"><?php echo $la_empresa["nomestpro4"]; ?></td>
        <td height="21" colspan="9"><input name="codestpro4" id="codestpro4" type="text" size="<?php echo $li_size4;?>" maxlength="<?php echo $li_loncodestpro4;?>" readonly style="text-align:center" value="<?php print $ls_codestpro4;?>">
        <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" value="<?php echo $ls_denestpro4 ?>" size="50" readonly></td>
      </tr>
      <tr>
        <td height="21" style="text-align:right"><?php echo $la_empresa["nomestpro5"]; ?></td>
        <td height="21" colspan="9"><input name="codestpro5" id="codestpro5" type="text" size="<?php echo $li_size5;?>" maxlength="<?php echo $li_loncodestpro5;?>" readonly style="text-align:center" value="<?php print $ls_codestpro5;?>">
        <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" value="<?php echo $ls_denestpro5 ?>" size="50" readonly></td>
      </tr>
      <?php
		   }
	  ?>
	  <tr>
        <td height="21" style="text-align:right">C&oacute;digo</td>
        <td height="21" colspan="9"><input name="txtcodctaspg" type="text" id="txtcodctaspg" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="21" style="text-align:right">Denominaci&oacute;n</td>
        <td height="21" colspan="9"><input name="txtdenctaspg" type="text" id="txtdenctaspg" size="72"></td>
      </tr>
      <tr>
        <td height="21" style="text-align:right">Cuenta Contable</td>
        <td height="21" colspan="9" style="text-align:left"><input name="txtcuentascg" type="text" id="txtcuentascg" size="22" maxlength="20"></td>
      </tr>
      <tr>
        <td height="21">&nbsp;</td>
        <td height="21">&nbsp;</td>
        <td height="21" colspan="8"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<p><br>
<?php
echo "<table width=750 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td>Presupuestaria</td>";
echo "<td style=text-align:center>".$la_empresa["nomestpro1"]."</td>";
echo "<td style=text-align:center>".$la_empresa["nomestpro2"]."</td>";
echo "<td style=text-align:center>".$la_empresa["nomestpro3"]."</td>";
if ($li_estmodest=='2')
   { 
	 echo "<td style=text-align:center>".$la_empresa["nomestpro4"]."</td>";
	 echo "<td style=text-align:center>".$la_empresa["nomestpro5"]."</td>";	
   }
echo "<td style=text-align:center>Denominación</td>";
echo "<td style=text-align:center>Contable</td>";
echo "<td style=text-align:center>Disponible</td>";
echo "</tr>";

if ($ls_operacion=="BUSCAR")
   {
	 $ls_sqlaux     = "";
	 $ls_codestpro1 = str_pad($ls_codestpro1,25,0,0);
	 $ls_codestpro2 = str_pad($ls_codestpro2,25,0,0);
	 $ls_codestpro3 = str_pad($ls_codestpro3,25,0,0);
	 if ($li_estmodest==2)
	    {  
		  $ls_codestpro4 = str_pad($ls_codestpro4,25,0,0);
 	      $ls_codestpro5 = str_pad($ls_codestpro5,25,0,0);
		  $ls_sqlaux     = " AND codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."'";
	    }
	
	 $ls_sql = "SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,
	                   TRIM(spg_cuenta) as spg_cuenta,denominacion,TRIM(sc_cuenta) as sc_cuenta,status
	              FROM spg_cuentas 																   
			     WHERE codemp = '".$la_empresa["codemp"]."'
			       AND spg_cuenta like '".$ls_spgcta."%'
			       AND denominacion like '%".$ls_denctaspg."%'
			       AND sc_cuenta like '".$ls_scgcta."%'
			       AND codestpro1='".$ls_codestpro1."'
			       AND codestpro2='".$ls_codestpro2."'
			       AND codestpro3='".$ls_codestpro3."' $ls_sqlaux
				   AND estcla='".$ls_estcla."'   
			     ORDER BY spg_cuenta";
	  $rs_data = $io_sql->select($ls_sql);
	  if ($rs_data==false)
	     {
		   $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
	     }
	  else
	     {
		   $li_numrows = $io_sql->num_rows($rs_data);
		   if ($li_numrows>0)
		      {
				while (!$rs_data->EOF)
				      {
	                    $ls_spgcta     = $rs_data->fields["spg_cuenta"];
						$ls_denctaspg  = $rs_data->fields["denominacion"];
						$ls_codestpro1 = trim(substr($la_estprog[0] = $rs_data->fields["codestpro1"],-$li_loncodestpro1));
						$ls_codestpro2 = trim(substr($la_estprog[1] = $rs_data->fields["codestpro2"],-$li_loncodestpro2));
						$ls_codestpro3 = trim(substr($la_estprog[2] = $rs_data->fields["codestpro3"],-$li_loncodestpro3));
						$ls_codestpro4 = trim(substr($la_estprog[3] = $rs_data->fields["codestpro4"],-$li_loncodestpro4));
						$ls_codestpro5 = trim(substr($la_estprog[4] = $rs_data->fields["codestpro5"],-$li_loncodestpro5));
						$ls_estcla     = $la_estprog[5] = $rs_data->fields["estcla"];
						$ls_scgcta     = $rs_data->fields["sc_cuenta"];
						$ls_estctaspg  = $rs_data->fields["status"];
						
				        $lb_valido=$io_intspg->uf_spg_saldo_select($la_empresa["codemp"],$la_estprog,$ls_spgcta,&$ls_status,&$adec_asignado, 
				                                           		  &$adec_aumento,&$adec_disminucion,&$adec_precomprometido,
													   	   		  &$adec_comprometido,&$adec_causado,&$adec_pagado);
						$ld_disponible = ($adec_asignado-($adec_comprometido+$adec_precomprometido)+$adec_aumento-$adec_disminucion);
						$ld_disponible = number_format($ld_disponible,2,",",".");						
						if ($ls_estctaspg=='S')
						   {
						     echo "<tr class=celdas-blancas>";
						     echo "<td style=text-align:center>".$ls_spgcta."</td>";
						   }
						elseif($ls_estctaspg=='C')
						   {
						     echo "<tr class=celdas-azules>";
							 echo "<td style=text-align:center><a href=\"javascript: aceptar('$ls_spgcta','$ls_denctaspg','$ls_scgcta');\">".$ls_spgcta."</a></td>";
						   }
						echo "<td style=text-align:center>".$ls_codestpro1."</td>";
						echo "<td style=text-align:center>".$ls_codestpro2."</td>";
						echo "<td style=text-align:center>".$ls_codestpro3."</td>";
						if ($li_estmodest=='2')
						   {
						     echo "<td style=text-align:center>".$ls_codestpro4."</td>";
						     echo "<td style=text-align:center>".$ls_codestpro5."</td>";
						   }
						echo "<td style=text-align:left>".$ls_denctaspg."</td>";
						echo "<td style=text-align:center>".$ls_scgcta."</td>";
						echo "<td style=text-align:right>".$ld_disponible."</td>";				
					    echo "</tr>";
						$rs_data->MoveNext();
					  }
				   $io_sql->free_result($rs_data);
				   $io_sql->close();
		      }
		   else
		      {
		        $io_msg->message("No se han creado Cuentas de gasto para la programatica seleccionada !!!");
		      }
         }
   }
print "</table>";
?>
</p>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(cuenta,deno,scgcuenta)
  {
    opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=deno;
    opener.document.form1.txtcuentascg.value=scgcuenta;
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_ctaspg.php";
	  f.submit();
  }	
</script>
</html>